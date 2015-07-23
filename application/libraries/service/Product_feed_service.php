<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Product_feed_service extends Base_service
{

    private $pfc_dao;

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/dao/Product_feed_dao.php");
        $this->set_dao(new Product_feed_dao());
        include_once(APPPATH . "libraries/dao/Product_feed_cat_dao.php");
        $this->set_pfc_dao(new Product_feed_cat_dao());
    }

    public function get_pfc_dao()
    {
        return $this->pfc_dao;
    }

    public function set_pfc_dao(Base_dao $dao)
    {
        $this->pfc_dao = $dao;
    }

    public function get_list_w_sku_key($where = array(), $option = array())
    {
        $option["result_type"] = "array";
        $rslist = array();
        if ($ar_list = $this->get_list($where, $option)) {
            foreach ($ar_list as $rsdata) {
                $rslist[$rsdata["sku"]] = $rsdata;
            }
        }
        return $rslist;
    }

    public function get_prod_feed_list_w_feeder_key($where = array(), $option = array())
    {
        $option["result_type"] = "array";
        $rslist = array();
        if ($ar_list = $this->get_list($where, $option)) {
            foreach ($ar_list as $rsdata) {
                $rslist[$rsdata["feeder"]] = $rsdata;
            }
        }
        return $rslist;
    }
}


