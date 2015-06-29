<?php

class Complementary_acc_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/complementary_acc_service');
        $this->load->library('service/product_service');
        $this->load->library('service/category_service');
        $this->load->library('service/price_service');
    }

    public function get_accessory_catid_arr()
    {
        return $this->complementary_acc_service->get_accessory_catid_arr();
    }

    public function get_product_list($where = array(), $option = array())
    {
        return $this->product_service->get_dao()->get_list_w_name($where, $option, "Product_list_w_name_dto");
    }

    public function get_product_list_total($where = array())
    {
        return $this->product_service->get_dao()->get_list_w_name($where, array("num_rows" => 1));
    }

    public function get_list($service, $where = array(), $option = array())
    {
        $service = $service . "_service";
        return $this->$service->get_list($where, $option);
    }

    public function check_cat($sku = "", $is_ca = true)
    {
        return $this->complementary_acc_service->check_cat($sku, $is_ca);
    }


}
/* End of file complementary_acc_model.php */
/* Location: ./system/application/models/complementary_acc_model.php */
