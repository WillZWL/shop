<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Data_feed_service.php";

class Apac_product_feed_service extends Data_feed_service
{
    public function __construct()
    {
        parent::Data_feed_service();
    }

    public function set_price_srv(Base_service $srv)
    {
        $this->price_srv = $srv;
    }

    public function set_product_srv(Base_service $srv)
    {
        $this->product_srv = $srv;
    }

    public function get_data_feed($platform_id, $first_line_headling = TRUE)
    {
        if ($platform_id == "WEBSG") {
            $c_id = 'SG';
        } else {
            $c_id = 'MY';
        }
        $arr = $this->get_data_list(array("pr.platform_id" => $platform_id, "c_id" => $c_id));
        //echo "<pre>"; var_dump($arr); die();
        // var_dump($this->get_prod_srv()->db->last_query()); die();
        if (!$arr) {
            return;
        }

        $affiliate_data = $this->get_affiliate_data($platform_id);
        $affiliate_id = $affiliate_data['af'];

        $new_list = array();

        foreach ($arr as $row) {
            $price_srv = $this->get_price_srv();
            if ($prod_obj = $this->get_product_srv()->get_dao()->get_product_overview(array("sku" => $row->get_sku(), "platform_id" => "WEBAU"), array("limit" => 1))) {
                $price_srv->calc_logistic_cost($prod_obj);
                $price_srv->calculate_profit($prod_obj);
                if ($prod_obj->get_margin() >= 5) {
                    if ($affiliate_id == 'PPSG' or $affiliate_id == 'PPMY') {
                        if ($this->get_data_feed_by_affiliate($affiliate_id, $row->get_platform_id(), $row->get_sku())) {
                            $new_list[] = $this->process_data_row($row);
                        }
                    } else {
                        $new_list[] = $this->process_data_row($row);
                    }
                }
            }
        }
        $content = $this->convert($new_list, $first_line_headling);
        //var_dump($content); die();
        return $content;
    }

    protected function get_data_list($where = array(), $option = array())
    {
        // return $this->get_prod_srv()->get_price_panda_product_feed_dto($where, array("limit"=>-1));
        return $this->get_prod_srv()->get_price_panda_product_feed_dto($where, array("limit" => -1));
    }

    public function get_price_srv()
    {
        return $this->price_srv;
    }

    public function get_product_srv()
    {
        return $this->product_srv;
    }

    public function get_data_feed_by_affiliate($affiliate_id, $platform_id, $sku)
    {
        include_once(APPPATH . 'libraries/service/Affiliate_sku_platform_service.php');
        $this->affiliate_sku_platform_service = New Affiliate_sku_platform_service();
        $data = $this->affiliate_sku_platform_service->get_sku_feed_list($affiliate_id);
        $status = $data[$platform_id][$sku];
        //$status  0 = auto / 1 = exclude / 2 = include.
        if ($status == 1) {
            return false;
        } else {
            return ture;
        }
    }

    public function process_data_row($data = NULL)
    {
        if (!is_object($data)) {
            return NULL;
        }
        $search = array(chr(10), chr(13), chr(9));
        $replace = array(" ", " ", " ");
        $detail_desc = str_replace($search, $replace, $data->get_detail_desc());
        $detail_desc = trim($detail_desc);
        $data->set_detail_desc($detail_desc);

        $affiliate_data = $this->get_affiliate_data($data->get_platform_id());
        $domain = $affiliate_data['domain'];
        $locale = $affiliate_data['locale'];
        $af = $affiliate_data['af'];

        $product_url = $data->get_product_url();
        $product_url .= "?AF=" . $af;

        $create_date = date('d/m/Y', strtotime($data->get_create_on()));
        $today = date('d/m/Y');

        if ($create_date == $today) {
            $data->set_create_on($today);
        } else {
            $data->set_create_on("");
        }

        if (!$data->get_image_url() || !file_exists($this->get_config_srv()->value_of("prod_img_path") . basename($data->get_image_url()))) {
            $data->set_image_url("http://{$domain}/images/product/imageunavailable.jpg");
        } else {
            $data->set_image_url("http://{$domain}" . $data->get_image_url());
        }

        $data->set_product_url("http://{$domain}/{$locale}" . $product_url);
        return $data;
    }


    public function convert($list = array(), $first_line_headling = TRUE)
    {
        $out_xml = new Vo_to_xml($list, $this->get_vo2xml_mapping());
        $out_csv = new Xml_to_csv("", $this->get_xml2csv_mapping(), $first_line_headling, $this->get_output_delimiter(), FALSE);

        return $this->get_dex_srv()->convert($out_xml, $out_csv);
    }

    public function get_contact_email()
    {
        return 'itsupport@eservicesgroup.net';
    }

    protected function get_default_vo2xml_mapping()
    {
        return '';
    }

    protected function get_default_xml2csv_mapping()
    {
        return APPPATH . 'data/price_panda_product_feed_xml2csv.txt';
    }
}


