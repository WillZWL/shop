<?php

include_once "Base_service.php";

class Display_qty_service extends Base_service
{
    private $display_qty_class_dao;
    private $display_qty_factor_dao;
    private $cat_srv;
    private $config;

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/dao/Display_qty_class_dao.php");
        $this->set_display_qty_class_dao(new Display_qty_class_dao());
        include_once(APPPATH . "libraries/dao/Display_qty_factor_dao.php");
        $this->set_display_qty_factor_dao(new Display_qty_factor_dao());
        include_once(APPPATH . "libraries/service/Category_service.php");
        $this->set_cat_srv(new Category_service());
        include_once(APPPATH . "libraries/service/Context_config_service.php");
        $this->set_config(new Context_config_service());
    }

    public function require_update_display_qty($display_qty, $cat_id)
    {
        return ($display_qty < $this->get_min_display_qty($cat_id));
    }

    public function get_min_display_qty($cat_id)
    {
        if ($cat_obj = $this->get_cat_srv()->get_dao()->get(array("id" => $cat_id))) {
            $min_display_qty = $cat_obj->get_min_display_qty() * 1;
        } else {
            $min_display_qty = $this->get_config()->value_of("default_min_display_qty");
        }
        return $min_display_qty;
    }

    public function get_cat_srv()
    {
        return $this->cat_srv;
    }

    public function set_cat_srv($value)
    {
        $this->cat_srv = $value;
    }

    public function get_config()
    {
        return $this->config;
    }

    public function set_config($value)
    {
        $this->config = $value;
    }

    public function cron_drop_display_qty()
    {
        include_once(APPPATH . "libraries/dao/Product_dao.php");
        $prod_dao = new Product_dao();
//      $where = array("p.status"=>2, "s.sales IS NULL"=>NULL);
        $where = array("p.status" => 2);

        if ($total = $prod_dao->get_prod_w_sales($where, array("num_rows" => 1))) {
            $limit = 100;
            $total_loops = ceil($total / $limit);
            for ($i = 0; $i < $total_loops; $i++) {
                $offset = $i * $limit;
                if ($prod_list = $prod_dao->get_prod_w_sales($where, array("limit" => $limit, "offset" => $offset))) {
                    foreach ($prod_list as $prod_obj) {
                        $display_qty = $prod_obj->get_display_quantity();
                        $cat_id = $prod_obj->get_cat_id();

                        $vpo_where = array("vpo.sku" => $prod_obj->get_sku());
                        $vpo_option = array("to_currency_id" => "HKD", "orderby" => "vpo.price > 0 DESC, vpo.platform_currency_id = 'HKD' DESC, vpo.price *  er.rate DESC", "limit" => 1);

                        if ($display_qty <= 0 && ($website_qty = $prod_obj->get_website_quantity())) {
                            if ($vpo_obj = $prod_dao->get_prod_overview_wo_cost_w_rate($vpo_where, $vpo_option)) {
                                $display_qty = $this->calc_display_qty($cat_id, $website_qty, $vpo_obj->get_price());
                            }
                        } else {
                            $min_display_qty = $this->get_min_display_qty($cat_id);
                            if ($display_qty > $min_display_qty) {
                                if ($vpo_obj = $prod_dao->get_prod_overview_wo_cost_w_rate($vpo_where, $vpo_option)) {
                                    if ($class_obj = $this->get_display_qty_class($vpo_obj->get_price())) {
                                        $drop_qty = $class_obj->get_drop_qty();
                                        if ($display_qty - $drop_qty > $min_display_qty) {
                                            $display_qty -= $drop_qty;
                                        }
                                    }
                                }
                            }
                        }

                        if ($prod_obj->get_display_quantity() != $display_qty) {
                            $prod_obj->set_display_quantity($display_qty);
                            $prod_dao->update($prod_obj);
                        }
                    }
                }
            }
        }
    }

    public function calc_display_qty($cat_id, $website_qty, $price, $currency = "HKD")
    {
        $display_qty = $website_qty;
        if ($website_qty) {
            if ($class_obj = $this->get_display_qty_class($price, $currency)) {
                $factor = $class_obj->get_default_factor();
                if ($factor_obj = $this->get_display_qty_factor_dao()->get(array("cat_id" => $cat_id, "class_id" => $class_obj->get_id()))) {
                    $factor = $factor_obj->get_factor();
                }
                $display_qty = round((is_null($class_obj->get_qty2()) ? $class_obj->get_qty() : rand($class_obj->get_qty(), $class_obj->get_qty2())) * $factor);
            }
        }
        return $display_qty;
    }

    public function get_display_qty_class($price, $currency = "HKD")
    {
        if ($currency != "HKD") {
            include_once(APPPATH . 'libraries/service/Exchange_rate_service.php');
            $ex_srv = new Exchange_rate_service();
            if ($ex_obj = $ex_srv->get_exchange_rate($currency, "HKD")) {
                $price = $price * $ex_obj->get_rate();
            }
        }
        return $this->get_display_qty_class_dao()->get_list(array("price < " => $price), array("orderby" => "price DESC", "limit" => 1));
    }

    public function get_display_qty_class_dao()
    {
        return $this->display_qty_class_dao;
    }

    public function set_display_qty_class_dao(Base_dao $dao)
    {
        $this->display_qty_class_dao = $dao;
    }

    public function get_display_qty_factor_dao()
    {
        return $this->display_qty_factor_dao;
    }

    public function set_display_qty_factor_dao(Base_dao $dao)
    {
        $this->display_qty_factor_dao = $dao;
    }

    public function get_class_list_w_key($where = array(), $option = array())
    {
        $data = array();
        if ($obj_list = $this->get_display_qty_class_dao()->get_list($where, $option)) {
            foreach ($obj_list as $obj) {
                $data[$obj->get_id()] = $obj;
            }
        }
        return $data;
    }

    public function get_factor_list_w_key($where = array(), $option = array())
    {
        $data = array();
        if ($obj_list = $this->get_display_qty_factor_dao()->get_list($where, $option)) {
            foreach ($obj_list as $obj) {
                $data[$obj->get_cat_id()][$obj->get_class_id()] = $obj;
            }
        }
        return $data;
    }
}


