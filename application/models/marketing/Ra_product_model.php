<?php

class Ra_product_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/ra_group_service');
        $this->load->library('service/ra_group_product_service');
        $this->load->library('service/ra_product_service');
        $this->load->library('service/product_service');
    }

    public function __autoload()
    {
        $this->ra_product_service->get_dao()->include_vo();
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
        return $this->ra_product_service->get_dao()->insert($data);
    }

    public function update($data)
    {
        return $this->ra_product_service->get_dao()->update($data);
    }

    public function get_ra_product_obj($sku = '')
    {
        if ($sku <> '') {
            return $this->ra_product_service->get_dao()->get(array('sku' => $sku));
        } else {
            return $this->ra_product_service->get_dao()->get();
        }
    }
}

?>