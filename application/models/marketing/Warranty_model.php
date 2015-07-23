<?php

class Warranty_model extends CI_Model
{


    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/bundle_service');
        $this->load->library('service/product_service');
        $this->load->library('service/warranty_service');
        $this->load->library('service/product_warranty_service');
    }

    public function get_product_list($where = array(), $option = array())
    {
        return $this->product_service->get_dao()->get_list_w_name($where, $option, "product_list_w_name_dto");
    }

    public function get_product_list_total($where = array())
    {
        return $this->product_service->get_dao()->get_list_w_name($where, array("num_rows" => 1));
    }


    public function update_warranty($sku, $warranty_in_month)
    {
        $product_obj = $this->product_service->get_dao()->get(array("sku" => $sku));
        $product_obj->set_warranty_in_month($warranty_in_month);
        return $this->product_service->get_dao()->update($product_obj);
    }

    public function update_country_warranty($sku, $platform_id, $warranty_in_month)
    {
        $product_warranty_obj = $this->product_warranty_service->get_dao()->get(array("sku" => $sku, "platform_id" => $platform_id));
        if ($product_warranty_obj) {
            $product_warranty_obj->set_warranty_in_month($warranty_in_month);
            return $this->product_warranty_service->get_dao()->update($product_warranty_obj);
        } else
            return FALSE;
    }

    public function add_country_warranty($product_warranty_obj)
    {
        return $this->product_warranty_service->get_dao()->insert($product_warranty_obj);
    }

    public function get_country_warranty($where = array())
    {
        return $this->product_warranty_service->get_dao()->get($where);
    }

    public function get_country_warranty_list($where = array())
    {
        $dao = $this->product_warranty_service->get_dao();
        $dao->db->from('product_warranty');
        $dao->include_vo('Product_warranty_vo');
        return $dao->common_get_list($where, array(), 'Product_warranty_vo');
    }

    public function get_sku_warranty($sku = '', $platform_id = '')
    {

        return $this->product_warranty_service->get_dao()->get_sku_warranty($sku, $platform_id);
    }

    public function get_product_warranty_dao()
    {
        return $this->product_warranty_service->get_dao();
    }
}
