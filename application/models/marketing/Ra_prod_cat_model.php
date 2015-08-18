<?php

class Ra_prod_cat_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/ra_prod_cat_service');
        $this->load->library('service/category_service');
        $this->load->library('service/product_service');
        $this->load->library('service/wsgb_price_service');
        $this->load->library('service/warranty_service');
    }

    public function __autoload()
    {
        $this->ra_prod_cat_service->get_dao()->include_vo();
    }

    public function get_ra_obj($id = "")
    {
        if ($id == "") {
            $ret = $this->ra_prod_cat_service->get_dao()->get();
        } else {
            $ret = $this->ra_prod_cat_service->get_dao()->get(array("ss_cat_id" => $id));
        }

        return $ret;
    }

    public function insert($data)
    {
        return $this->ra_prod_cat_service->get_dao()->insert($data);
    }

    public function update($data)
    {
        return $this->ra_prod_cat_service->get_dao()->update($data);
    }

    public function  get_scat_list()
    {
        return $this->category_service->get_dao()->get_list(array("level" => "2"), array("limit" => -1));
    }

    public function  get_sscat_list()
    {
        return $this->category_service->get_dao()->get_list(array("level" => "3"), array("limit" => -1));
    }

    public function get_sscat_prod($id)
    {
        return $this->wsgb_price_service->get_product_list_w_profit(array("sub_sub_cat_id" => $id));
    }

    public function get_raprod_prod_obj($prodid = "")
    {
        if ($prodid <> "") {
            return $this->ra_prod_prod_service->get_dao()->get(array("prod_id" => $prodid));
        } else {
            return $this->ra_prod_prod_service->get_dao()->get(array());
        }
    }

    public function get_warranty_cat_list()
    {
        return $this->category_service->get_warranty_cat_list();
    }

    public function get_warranty_by_sku($sku = "", $platform_id = "")
    {
        return $this->warranty_service->get_warranty_by_sku($sku, $platform_id);
    }
}

?>