<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Complementary_acc_service extends Base_service
{
    public $accessory_catid_arr;
    private $product_dao;

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/dao/Product_dao.php");
        $this->set_product_dao(new Product_dao());
        include_once(APPPATH . "libraries/dao/Product_complementary_acc_dao.php");
        $this->set_complementary_acc_dao(new Product_complementary_acc_dao());

        // sets the category id of complementary accessory
        $this->set_accessory_catid_arr();
    }

    public function set_complementary_acc_dao(Base_dao $dao)
    {
        $this->complementary_acc_dao = $dao;
    }

    private function set_accessory_catid_arr()
    {
        $this->accessory_catid_arr = $this->get_accessory_catid_arr();
    }

    public function get_accessory_catid_arr()
    {
        $accessory_catid_arr = $this->get_complementary_acc_dao()->get_accessory_catid_arr();
        return $accessory_catid_arr;
    }

    public function get_complementary_acc_dao()
    {
        return $this->complementary_acc_dao;
    }

    public function get_product_dao()
    {
        return $this->product_dao;
    }

    public function set_product_dao(Base_dao $dao)
    {
        $this->product_dao = $dao;
    }

    public function get_mapped_acc_list_w_name($where = array(), $option = array(), $active = true)
    {
        //$where["dest_country_id"] = $country_id;
        // $where["mainprod_sku"] = $sku;
        return $this->get_complementary_acc_dao()->get_mapped_acc_list_w_name($where, $option, $active);
    }

    public function check_cat($sku = "", $is_ca = true)
    {
        # if you want to check if product is NOT a complementary accessory,
        # then set $is_ca to false
        $ret = $this->get_complementary_acc_dao()->check_cat($sku, $is_ca);
        return $ret;
    }
}

/* End of file complentary_acc_service.php */
/* Location: ./system/application/libraries/service/Complentary_acc_service.php */
