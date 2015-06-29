<?php

class Bundle_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/bundle_service');
        $this->load->library('service/product_service');
        $this->load->library('service/supplier_service');
        $this->load->library('service/brand_service');
        $this->load->library('service/colour_service');
        $this->load->library('service/freight_cat_service');
        $this->load->library('service/category_service');
        $this->load->library('service/ra_prod_prod_service');
        $this->load->library('service/price_service');
    }

    public function get_bundle_list($where = array(), $option = array())
    {
        return $this->bundle_service->get_dao()->get_list_w_name($where, $option, "Product_list_w_name_dto");
    }

    public function get_bundle_list_total($where = array())
    {
        return $this->bundle_service->get_dao()->get_list_w_name($where, array("num_rows" => 1));
    }

    public function get_components_tr($where = array(), $option = array(), $lang = array())
    {
        return $this->price_service->get_components_tr($where, $option, "Product_cost_dto", $lang);
    }

    public function get_ra_prod_list($where = array(), $option = array())
    {
        return $this->product_service->get_dao()->get_ra_prod_list_w_name($where, $option, "Product_list_w_name_dto");
    }

    public function get_ra_prod_list_total($where = array())
    {
        return $this->product_service->get_dao()->get_ra_prod_list_w_name($where, array("num_rows" => 1));
    }

    public function get_ra_prod_tr($sku = "", $platform_id = "", $lang = "")
    {
        return $this->price_service->get_ra_prod_tr($sku, $platform_id, "Product_cost_dto", $lang);
    }

    public function get_list($service, $where = array(), $option = array())
    {
        $service = $service . "_service";
        return $this->$service->get_list($where, $option);
    }

    public function get($service, $where = array())
    {
        $service = $service . "_service";
        return $this->$service->get($where);
    }

    public function get_supplier_prod($where = array())
    {
        return $this->supplier_service->get_sp_dao()->get($where);
    }

    public function get_product_content($where = array())
    {
        return $this->product_service->get_pc_dao()->get($where);
    }

    public function update($service, $obj)
    {
        $service = $service . "_service";
        return $this->$service->update($obj);
    }

    public function del_product_content($where = array())
    {
        return $this->product_service->get_pc_dao()->q_delete($where);
    }

    public function include_vo($service)
    {
        $service = $service . "_service";
        return $this->$service->include_vo();
    }

    public function include_pc_vo()
    {
        return $this->product_service->get_pc_dao()->include_vo();
    }

    public function include_dto($service, $dto)
    {
        $service = $service . "_service";
        return $this->$service->include_dto($dto);
    }

    public function add($service, $obj)
    {
        $service = $service . "_service";
        return $this->$service->insert($obj);
    }

    public function add_supplier_prod($obj)
    {
        return $this->supplier_service->get_sp_dao()->insert($obj);
    }

    public function add_product_content($obj)
    {
        return $this->product_service->get_pc_dao()->insert($obj);
    }

    public function seq_next_val()
    {
        return $this->product_service->get_dao()->seq_next_val();
    }

    public function update_seq($new_value)
    {
        return $this->product_service->get_dao()->update_seq($new_value);
    }

}
