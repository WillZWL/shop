<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Landpage_listing_service.php";

class Best_seller_service extends Landpage_listing_service
{
    private $type = 'BS';
    private $latest_arrivals_service;
    private $set_ra_prod_cat_service;

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/service/Product_service.php");
        $this->product_service = new Product_service();
        include_once(APPPATH . "libraries/service/Price_service.php");
        $this->price_service = new Price_service();
        include_once(APPPATH . "libraries/service/Latest_arrivals_service.php");
        $this->set_latest_arrivals_service(new Latest_arrivals_service());
        include_once(APPPATH . "libraries/service/Ra_prod_cat_service.php");
        $this->set_ra_prod_cat_service(new Ra_prod_cat_service());
    }

    // public function set_top_deals_service($srv=NULL)
    // {
    //  $this->top_deals_service = $srv;
    // }

    public function set_ra_prod_cat_service($srv)
    {
        $this->ra_prod_cat_service = $srv;
    }

    public function get_top_deals_service()
    {
        return $this->top_deals_service;
    }

    public function get_count($catid = "", $mode = "", $platform)
    {
        if ($catid == "") {
            return FALSE;
        } else {
            return $this->get_dao()->get_num_rows(array("catid" => $catid, "type" => 'BS', "mode" => $mode, "platform_id" => $platform));
        }
    }

    public function get_best_seller($catid = "", $rank = "", $platform = "")
    {
        if ($catid === "") {
            return $this->get_dao()->get();
        } else {
            //$obj =  $this->get_dao()->get(array("catid"=>$catid,"type"=>'BS',"rank"=>$rank));
            $obj = $this->get_dao()->get_item_by_rank($catid, "BS", $rank, $platform, "Product_list_w_name_dto");

            return $obj;
        }
    }

    public function get_ra_bs_list($cat_id, $platform_id, $lang_id = "en", $recommended_total = 3, $recommended_only = FALSE)
    {
        $data = array();
        if ($data["ra_cat_list"] = $this->get_ra_prod_cat_service()->get_ra_prod_cat_w_ext_name_list($cat_id, $lang_id)) {
            $max_count = 0;
            foreach ($data["ra_cat_list"] as $rskey => $obj) {
                $cat_id = $obj->get_cat_id();
                if ($bs_list = $this->get_best_seller_list_by_cat($cat_id, $platform_id, $lang_id)) {
                    $match = FALSE;
                    if ($data["recommended"]) {
                        foreach ($data["recommended"] AS $obj) {
                            if ($obj->get_sku() == $bs_list[0]->get_sku()) {
                                $match = TRUE;
                            }
                        }
                    }

                    if ($match == FALSE) {
                        $data["recommended"][] = $bs_list[0];
                        $ra_cat_item_count[$rskey] = $cur_count = count($bs_list);
                        $max_count = max($max_count, $cur_count);
                        $data["ra_cat_item_list"][$rskey] = $bs_list;
                    }
                } else {
                    unset($data["ra_cat_list"][$rskey]);
                }
            }

            $count_recommended = count($data["recommended"]);
            $count_ra_cat = count($data["ra_cat_list"]);
            if ($count_recommended < $recommended_total) {
                if ($count_ra_cat == 1) {
                    if ($data["ra_cat_item_list"][0]) {
                        $data["recommended"] = array();
                        $data["recommended"] = array_slice($data["ra_cat_item_list"][0], 0, $recommended_total);
                    }
                } else {
                    for ($j = 1; $j < $max_count; $j++) {
                        for ($i = 0; $i < $count_ra_cat; $i++) {
                            if ($data["ra_cat_item_list"][$i][$j]) {
                                $data["recommended"][] = $data["ra_cat_item_list"][$i][$j];
                                $count_recommended++;
                            }

                            if ($count_recommended == $recommended_total) {
                                break;
                            }
                        }

                        if ($count_recommended == $recommended_total) {
                            break;
                        }

                    }
                }
            }
        }
        return $recommended_only ? $data["recommended"] : $data;
    }

    public function get_ra_prod_cat_service()
    {
        return $this->ra_prod_cat_service;
    }

    public function get_best_seller_list_by_cat($cat_id, $platform_id, $lang_id = "en")
    {
        $where["ll.type"] = "BS";
        $where["ll.catid"] = $cat_id;
        $where["ll.platform_id"] = $platform_id;
        $where["pr.listing_status"] = "L";
        $where["pr.price >"] = 0;
        $option["orderby"] = "ll.mode = 'M' DESC, ll.rank";
        $option["lang_id"] = $lang_id;
        $option["array_list"] = 1;

        // calculate RRP
        if ($bs_list = $this->get_dao()->get_landpage_product_info($where, $option)) {
            foreach ($bs_list AS $obj) {
                $rrp = $this->price_service->calc_website_product_rrp($obj->get_price(), $obj->get_fixed_rrp(), $obj->get_rrp_factor());
                $obj->set_rrp_price($rrp);
            }
        }

        return $bs_list;
    }

    public function insert(Best_seller_vo $obj)
    {
        return $this->get_dao()->insert($obj);
    }

    public function update($obj)
    {
        return $this->get_dao()->update($obj);
    }

    public function get_product_list($where = array(), $option = array())
    {
        return $this->product_service->get_dao()->get_list_w_name($where, $option, "Product_list_w_name_dto");
    }

    public function get_product_list_total($where = array(), $option = array())
    {
        return $this->product_service->get_dao()->get_list_w_name($where, $option, "Product_list_w_name_dto");
    }

    public function delete_bs($where = array())
    {
        return $this->get_dao()->q_delete($where);
    }

    public function trans_start()
    {
        $this->get_dao()->trans_start();
    }

    public function trans_complete()
    {
        $this->get_dao()->trans_complete();
    }

    public function display_list($catid = "")
    {
        if ($catid == "") {
            return FALSE;
        } else {
            $ret = array();
            $added = array();

            if ($manual = $this->get_list_w_name($catid, "M", "BS", PLATFORMID)) {
                foreach ($manual as $obj) {
                    $ret[] = array("prodid" => $obj->get_selection(), "name" => $obj->get_name(), "image" => $obj->get_image(), "price" => $obj->get_price(), "website_status" => $obj->get_website_status(), "qty" => $obj->get_quantity(), "web_qty" => $obj->get_website_quantity());
                    $added[] = $obj->get_selection();
                }
            }

            $cnt = count($added);

            if ($auto = $this->get_list_w_name($catid, "A", "BS", PLATFORMID)) {
                foreach ($auto as $obj) {
                    if ($cnt < $this->get_limit() && !in_array($obj->get_selection(), $added)) {
                        $ret[] = array("prodid" => $obj->get_selection(), "name" => $obj->get_name(), "image" => $obj->get_image(), "price" => $obj->get_price(), "website_status" => $obj->get_website_status(), "qty" => $obj->get_quantity(), "web_qty" => $obj->get_website_quantity());
                        $added[] = $obj->get_selection();
                        $cnt++;
                    }
                }
            }

            if ($latest_arrivals_list = $this->get_latest_arrivals_service()->display_list($catid)) {
                foreach ($latest_arrivals_list as $obj) {
                    if ($cnt < $this->get_limit() && !in_array($obj['prodid'], $added)) {
                        $ret[] = array("prodid" => $obj['prodid'], "name" => $obj['name'], "image" => $obj['image'], "price" => $obj['price'], "website_status" => $obj['website_status'], "qty" => $obj['qty'], "web_qty" => $obj['web_qty']);
                        $added[] = $obj['prodid'];
                        $cnt++;
                    }
                }
            }

            return $ret;
        }
    }

    public function get_list_w_name($catid, $mode = "M", $type = "BS", $platform, $rtype = "object")
    {
        return $this->get_dao()->get_list_w_pname($catid, $mode, $type, $platform, "Best_seller_prodname_dto", $rtype);
    }

    public function get_latest_arrivals_service()
    {
        return $this->latest_arrivals_service;
    }

    public function set_latest_arrivals_service($srv = NULL)
    {
        $this->latest_arrivals_service = $srv;
    }

    public function get_type()
    {
        return $this->type;
    }

    public function get_home_best_seller_grid_info($platform_id = "")
    {
        return $this->product_service->get_home_best_seller_grid_info($platform_id);
    }

    protected function _get_product_list_for_home($platform = "")
    {
        return $this->product_service->get_best_seller_list_by_cat('', 0, 30, $this->get_limit(), $platform);
    }

    protected function _get_product_list($filter_column = '', $cat_id = '', $platform = "")
    {
        return $this->product_service->get_best_seller_list_by_cat($filter_column, $cat_id, 30, $this->get_limit(), $platform);
    }
}

/* End of file best_seller_service.php */
/* Location: ./app/libraries/service/Best_seller.php */