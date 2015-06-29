<?php

class Freight_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/courier_service');
        $this->load->library('service/freight_cat_service');
        $this->load->library('service/weight_cat_service');
    }

    public function get_courier_list($where = array(), $option = array())
    {
        return $this->courier_service->get_list($where, $option);
    }

    public function get_courier($where = array())
    {
        return $this->courier_service->get($where);
    }

    public function get_courier_list_w_region($where = array(), $option = array())
    {
        return $this->courier_service->get_dao()->get_list_w_name($where, $option, "Courier_w_region_dto");
    }

    public function get_courier_region_country($where = array(), $option = array())
    {
        return $this->courier_service->get_dao()->get_region_country_list($where, $option, "Courier_region_country_dto");
    }

    public function get_freight_cat_list($where = array(), $option = array())
    {
        return $this->freight_cat_service->get_list($where, $option);
    }

    public function get_freight_cat_total($where = array())
    {
        return $this->freight_cat_service->get_num_rows($where);
    }

    public function get_weight_cat_list($where = array(), $option = array())
    {
        return $this->weight_cat_service->get_list($where, $option);
    }

    public function get_weight_cat_total($where = array())
    {
        return $this->weight_cat_service->get_num_rows($where);
    }

    public function get_freight_cat($where = array())
    {
        return $this->freight_cat_service->get($where);
    }

    public function get_weight_cat($where = array())
    {
        return $this->weight_cat_service->get($where);
    }

    public function include_freight_cat_vo()
    {
        return $this->freight_cat_service->include_vo();
    }

    public function include_freight_cat_charge_vo()
    {
        return $this->freight_cat_service->get_fcc_dao()->include_vo();
    }

    public function include_weight_cat_charge_vo()
    {
        return $this->weight_cat_service->get_wcc_dao()->include_vo();
    }

    public function include_freight_cat_w_region_dto()
    {
        return $this->freight_cat_service->include_dto("Freight_cat_w_region_dto");
    }

    public function include_weight_cat_vo()
    {
        return $this->weight_cat_service->include_vo();
    }

    public function add_freight_cat(Base_vo $obj)
    {
        return $this->freight_cat_service->insert($obj);
    }

    public function add_weight_cat(Base_vo $obj)
    {
        return $this->weight_cat_service->insert($obj);
    }

    public function add_courier(Base_vo $obj)
    {
        return $this->courier_service->insert($obj);
    }

    public function get_fcc($where = array())
    {
        return $this->freight_cat_service->get_fcc_dao()->get($where);
    }

    public function get_wcc($where = array())
    {
        return $this->weight_cat_service->get_wcc_dao()->get($where);
    }

    public function get_fcc_w_reg_list($where = array(), $option = array())
    {
        return $this->freight_cat_service->get_fcc_w_reg_list($where, $option);
    }

    public function get_wcc_w_reg_list($where = array(), $option = array())
    {
        return $this->weight_cat_service->get_wcc_w_reg_list($where, $option);
    }

    public function include_fcc_vo()
    {
        return $this->freight_cat_service->get_fcc_dao()->include_vo();
    }

    public function include_wcc_vo()
    {
        return $this->weight_cat_service->get_wcc_dao()->include_vo();
    }

    public function get_fcc_nearest_amount($fcat_id, $weight)
    {
        return $this->freight_cat_service->get_fcc_dao()->get_nearest_amount($fcat_id, $weight);
    }

    public function get_wcc_nearest_amount($wcat_id, $weight)
    {
        return $this->weight_cat_service->get_wcc_dao()->get_nearest_amount($wcat_id, $weight);
    }

    public function add_fcc(Base_vo $obj)
    {
        return $this->freight_cat_service->get_fcc_dao()->insert($obj);
    }

    public function add_wcc(Base_vo $obj)
    {
        return $this->weight_cat_service->get_wcc_dao()->insert($obj);
    }

    public function update_freight_cat(Base_vo $obj)
    {
        return $this->freight_cat_service->update($obj);
    }

    public function update_weight_cat(Base_vo $obj)
    {
        return $this->weight_cat_service->update($obj);
    }

    public function del_fcc($where = array())
    {
        return $this->freight_cat_service->get_fcc_dao()->q_delete($where);
    }

    public function del_wcc($where = array())
    {
        return $this->weight_cat_service->get_wcc_dao()->q_delete($where);
    }

    public function get_origin_country_list()
    {
        return $this->freight_cat_service->get_origin_country_list();
    }

    public function get_full_freight_cat_charge_list($where = array(), $option = array())
    {
        return $this->freight_cat_service->get_full_freight_cat_charge_list($where, $option);
    }

    public function get_freight_cat_charge_obj($where = array())
    {
        return $this->freight_cat_service->get_freight_cat_charge_obj($where);
    }

    public function update_fcc($obj)
    {
        return $this->freight_cat_service->update_fcc($obj);
    }

    public function insert_fcc($obj)
    {
        return $this->freight_cat_service->insert_fcc($obj);
    }
}
