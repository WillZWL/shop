<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Product_search_service extends Base_service
{
    private $pc_dao;

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/dao/Product_dao.php");
        $this->set_dao(new Product_dao());
        include_once(APPPATH . "libraries/dao/Product_content_dao.php");
        $this->set_pc_dao(new Product_content_dao());
    }

    public function get_pc_dao()
    {
        return $this->pc_dao;
    }

    public function set_pc_dao(Base_dao $dao)
    {
        $this->pc_dao = $dao;
    }

    public function search_by_keyword_full_match($where, $option)
    {
        $where['skey'] = $this->format_search_key($where['keyword'], ".?");
        return $this->get_dao()->search_by_keyword_full_match($where, $option);
    }

    public function format_search_key($skey = "", $replace = " ")
    {
        /*
         *  1.  Pre-process the search key to remove unnecessary character.
         *  2.  Insert a whitespace as default inbetween number and alphabet or any other characters as specified in the 2nd parameter
         *
         */
        $uf_arr = explode(" ", $skey);

        foreach ($uf_arr as $k => $v) {
            if ($v != "") {
                $v = trim(preg_replace('/[.,`\[\]\(\)\"\';\/\\\\?*\+]/', "$replace", $v));
                $v = trim(preg_replace('/([0-9]{1,})([a-zA-Z]{1,})/', "\\1$replace\\2", $v));
                $v = trim(preg_replace('/([a-zA-Z]{1,})([0-9]{1,})/', "\\1$replace\\2", $v));
                if ((trim(str_replace('.?', '', $v))) != "") {
                    $f_arr[] = $v;
                }
            } else {
                unset($uf_arr[$k]);
            }
        }
        $sk['unformated'] = $uf_arr;
        $sk['formated'] = $f_arr;

        return $sk;
    }

    public function search_by_keyword_partial_match($where, $option)
    {
        $where['skey'] = $this->format_search_key($where['keyword'], ".?");
        return $this->get_dao()->search_by_keyword_partial_match($where, $option);
    }

    public function search_without_keyword($where, $option)
    {
        return $this->get_dao()->search_without_keyword($where, $option);
    }

    public function get_product_search_list($where, $option)
    {
        $debug = 0;
        if ($where['keyword']) {
            $rs = $this->search_by_product_name($where, $option);
            if ($debug == 1) {
                echo "<br>Frist Level Result Set<br>";
                var_dump($rs);
                echo "<br>";
            }

            return $rs;
        }
    }

    public function search_by_product_name($where, $option)
    {
        $where['skey'] = $this->format_search_key($where['keyword'], ".?");
        return $this->get_dao()->search_by_product_name($where, $option);
    }

    public function merge_search_result_object($rs, $rs2)
    {
        $rs = (array)$rs;
        $rs2 = (array)$rs2;
        foreach ($rs2 as $k => $v) {
            $flag = TRUE;
            foreach ($rs as $k1 => $v1) {
                if ($v->get_sku() == $v1->get_sku()) {
                    $flag = FALSE;
                }
            }
            if ($flag == TRUE) {
                array_push($rs, $v);
            }
        }
        return $rs;
    }

    public function get_product_search_list_for_ss_live_price($platform_id, $sku = '', $with_rrp = FALSE)
    {
        include_once APPPATH . 'helpers/price_helper.php';

        include_once APPPATH . 'libraries/service/Price_website_service.php';
        $price_srv = new Price_website_service();

        include_once APPPATH . 'libraries/service/Platform_biz_var_service.php';
        $pbv_srv = new Platform_biz_var_service();

        $pbv_obj = $pbv_srv->get_platform_biz_var($platform_id);
        $lang_id = $pbv_obj->get_language_id();
        $language_path = APPPATH . "/language/" . $lang_id . "/nocontroller/data_feed.ini";
        if (file_exists($language_path)) {
            $lang = parse_ini_file($language_path);
        }

        if ($sku != '') {
            $sku_list = explode(',', $sku);
        }

        $json = array();
        foreach ($sku_list as $sku) {
            if ($listing_info = $price_srv->get_listing_info($sku, $platform_id, $lang_id)) {
                if ($with_rrp) {
                    $rrp = $listing_info->get_rrp_price();
                    $price = $listing_info->get_price();

                    $live_price_data = array();
                    $live_price_data[] = platform_curr_format($rrp);
                    $live_price_data[] = platform_curr_format($price);

                    if ($price_srv->is_display_saving_message() == 'T') {
                        $live_price_data[] = $lang['save'] . number_format(($rrp == 0 ? 0 : ($rrp - $price) / $rrp * 100), 0) . '%';
                    } else {
                        $live_price_data[] = '';
                    }

                    $status = '';
                    switch ($listing_info->get_status()) {
                        case 'I':
                            $status = $lang['in_stock'];
                            break;
                        case 'O':
                            $status = $lang['out_stock'];
                            break;
                        case 'P':
                            $status = $lang['pre_order'];
                            break;
                        case 'A':
                            $status = $lang['arriving'];
                            break;
                    }
                    $live_price_data[] = $listing_info->get_status() == 'I' ? $listing_info->get_qty() . " " . $status : $status;
                    $live_price_data[] = $listing_info->get_status();

                    $json[$sku] = $live_price_data;
                } else {
                    $json[$sku] = platform_curr_format(random_markup($listing_info->get_price()));
                }
            }
        }
        return json_encode($json);
    }
}



