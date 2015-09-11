<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Website_service extends Base_service
{
    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/service/Category_service.php");
        $this->category_service = new Category_service();
        include_once(APPPATH . "libraries/service/Best_seller_service.php");
        $this->best_seller_service = new Best_seller_service();
        include_once(APPPATH . "libraries/service/Latest_arrivals_service.php");
        $this->latest_arrivals_service = new Latest_arrivals_service();
        include_once(APPPATH . "libraries/service/Pick_of_the_day_service.php");
        $this->pick_of_the_day_service = new Pick_of_the_day_service();
        include_once(APPPATH . "libraries/service/Cart_session_service.php");
        $this->cart_session_service = new Cart_session_service();
        include_once(APPPATH . "libraries/service/Banner_service.php");
        $this->banner_service = new Banner_service();
        include_once(APPPATH . "libraries/service/Display_banner_service.php");
        $this->set_display_banner_service(new Display_banner_service());
        include_once(APPPATH . "libraries/service/Context_config_service.php");
        $this->context_config_service = new Context_config_service();
        include_once(APPPATH . "libraries/service/Menu_service.php");
        $this->menu_service = new Menu_service();
        include_once(APPPATH . "libraries/service/Product_service.php");
        $this->product_service = new Product_service();
        include_once(APPPATH . "libraries/service/Price_service.php");
        $this->price_service = new Price_service();
        include_once(APPPATH . "libraries/service/Category_service.php");
        $this->category_service = new Category_service();

    }

    public function set_display_banner_service($serv)
    {
        $this->display_banner_service = $serv;
    }

    public function get_home_content($lang_id = 'en')
    {
        $grid_display_limit = 6;

        $option['limit'] = 6;

        // latest arrival
        if ($la_list = $this->get_latest_arrival_grid_info(PLATFORMID)) {
            if ($la_info_list = $this->price_service->get_listing_info_list($la_list, PLATFORMID, $lang_id, $option)) {
                foreach ($la_info_list as $sku => $result) {
                    if ($result) {
                        $latest_arrival_arr[$sku] = $result;
                    }
                }
            }
        }
        $data["latest_arrival"] = $latest_arrival_arr;

        // best seller
        if ($bs_list = $this->get_best_seller_grid_info(PLATFORMID)) {
            if ($bs_info_list = $this->price_service->get_listing_info_list($bs_list, PLATFORMID, $lang_id, $option)) {
                foreach ($bs_info_list as $sku => $result) {
                    if ($result) {
                        $best_seller_arr[$sku] = $result;
                    }
                }
            }
        }

        // use latest arrival list as backup if best seller has no results
        if (count($best_seller_arr) < $grid_display_limit && count($latest_arrival_arr) > 0) {
            foreach ($latest_arrival_arr as $sku => $result) {
                if ($result) {
                    $best_seller_arr[$sku] = $result;
                }
                if (count($best_seller_arr) === $grid_display_limit) {
                    break;
                }
            }
        }
        $data["best_seller"] = $best_seller_arr;

        return $data;
    }

    public function get_latest_arrival_grid_info($platform_id = "")
    {
        return $this->latest_arrivals_service->get_home_latest_arrival_grid_info($platform_id);
    }

    public function get_best_seller_grid_info($platform_id = "")
    {
        return $this->best_seller_service->get_home_best_seller_grid_info($platform_id);
    }

    public function get_clearance_product_gird_info($platform_id = "")
    {
        return $this->product_service->get_clearance_product_gird_info($platform_id);
    }

    public function get_footer_menu_list($lang_id = "en")
    {
        $where["m.menu_type"] = "F";
        $where["m.status"] = $where["fo_def.status"] = "1";
        $option["orderby"] = "priority ASC";

        if (!$list = $this->menu_service->get_fm_list_w_name($lang_id, $where, $option)) {
            $list = $this->menu_service->get_fm_list_w_name($lang_id, $where, $option);
        }

        if ($list) {
            foreach ($list as $obj) {
                if ($obj->get_level() == 0) {
                    $rs['menu_list'][$obj->get_menu_id()] = $obj;
                } else {
                    $rs['menu_item_list'][$obj->get_parent_id()][$obj->get_menu_id()] = $obj;
                }
            }
        }
        return $rs;
    }

    public function get_home_category_info_list()
    {
        $cat_list = $this->get_home_category_list();
        $total_grid_size = 9;
        $result = array();
        $count = 0;

        foreach ($cat_list as $cat) {
            if ($count >= $total_grid_size) {
                break;
            }

            $result[$count]["category"] = $cat;
            $sub_cat_list = $this->category_service->get_item_with_pop_child_count(2, $cat->get_id());

            if ($sub_cat_list) {
                $result[$count]["pop_sub_cat_list"] = $sub_cat_list;
            }

            $brand_list = $this->category_service->get_display_list($cat->get_id(), "brand");

            if ($brand_list) {
                $result[$count]["pop_brand_list"] = $brand_list;
            }

            $count++;
        }

        return $result;
    }

    public function get_home_category_list()
    {
        $where["level"] = "1";
        $where["status"] = "1";
        $where["id > "] = "0";
        $option["orderby"] = "priority ASC";
        return $this->category_service->get_dao()->get_list($where, $option, $this->category_service->get_dao()->get_vo_classname());
    }

    public function get_display_banner_service()
    {
        return $this->display_banner_service;
    }

    public function get_display_service()
    {
        return $this->display_service;
    }

    public function set_display_service($serv)
    {
        $this->display_service = $serv;
    }

    public function get_cat_url($cat_id, $relative_path = FALSE)
    {
        if (empty($cat_id) || $cat_id == 0) {
            return false;
        }

        if ($cat_obj = $this->category_service->get(array('id' => $cat_id))) {
            $cat_name = str_replace(array(" ", "/", "."), "-", $cat_obj->get_name());

            if ($relative_path) {
                return "/" . $cat_name . "/cat/view/" . $cat_obj->get_id();
            } else {
                return base_url() . $cat_name . "/cat/view/" . $cat_obj->get_id();
            }
        }

        return false;
    }

    public function get_prod_url($sku, $relative_path = FALSE)
    {
        if (empty($sku)) {
            return false;
        }

        if ($prod_obj = $this->product_service->get(array("sku" => $sku))) {
            $prod_name = str_replace(array(" ", "/", "."), "-", $prod_obj->get_name());

            if ($relative_path) {
                return "/" . $prod_name . "/mainproduct/view/" . $prod_obj->get_sku();
            } else {
                return base_url() . $prod_name . "/mainproduct/view/" . $prod_obj->get_sku();
            }
        }
        return $sku;
    }

    public function get_listing_info($sku = "", $platform_id = "", $lang_id = 'en', $option = array())
    {
        return $this->price_service->get_listing_info($sku, $platform_id, $lang_id, $option);
    }

    public function get_listing_info_list($sku_arr = array(), $platform_id = "", $lang_id = 'en', $option = array())
    {
        return $this->price_service->get_listing_info_list($sku_arr, $platform_id, $lang_id, $option);
    }

}

