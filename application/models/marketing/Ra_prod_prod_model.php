<?php

class Ra_prod_prod_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/ra_prod_cat_service');
        $this->load->library('service/ra_prod_prod_service');
        $this->load->library('service/category_service');
        $this->load->library('service/product_service');
        $this->load->library('service/price_service');
    }

    public function __autoload()
    {
        $this->ra_prod_prod_service->get_dao()->include_vo();
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

    public function get_product($where = array())
    {
        return $this->product_service->get($where);
    }

    public function update_product($obj)
    {
        return $this->product_service->update($obj);
    }

    public function insert($data)
    {
        return $this->ra_prod_prod_service->get_dao()->insert($data);
    }

    public function update($data)
    {
        return $this->ra_prod_prod_service->get_dao()->update($data);
    }

    public function  get_sscat_list()
    {
        return $this->category_service->get_dao()->get_list(array("level" => "3"));
    }

    public function get_scat_prod($id)
    {
        return $this->price_service->get_product_list_w_profit(array("platform_id" => "WEBHK", "sub_cat_id" => $id), array("orderby" => "prod_name", "limit" => -1), "Product_cost_dto");
    }

    public function get_sscat_prod($id)
    {
        return $this->price_service->get_product_list_w_profit(array("platform_id" => "WEBHK", "sub_sub_cat_id" => $id), array("orderby" => "prod_name", "limit" => -1), "Product_cost_dto");
    }

    public function get_raprod_prod_obj($prodid = "")
    {
        if ($prodid <> "") {
            return $this->ra_prod_prod_service->get_dao()->get(array("sku" => $prodid));
        } else {
            return $this->ra_prod_prod_service->get_dao()->get(array());
        }
    }
}

?>