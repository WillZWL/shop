<?php

include_once "Base_service.php";

class Competitor_map_service extends Base_service
{

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/dao/Competitor_map_dao.php");
        $this->set_dao(new Competitor_map_dao());
    }

    public function set_dao($value)
    {
        $this->dao = $value;
    }

    public function get($where = array())
    {
        return $this->get_dao()->get($where);
    }

    public function get_dao()
    {
        return $this->dao;
    }

    public function get_list($where = array(), $option = array())
    {
        return $this->get_dao()->get_list($where, $option);
    }

    public function get_active_mapped_list($country_id = "", $master_sku = "")
    {
        return $this->get_dao()->get_active_mapped_list($country_id, $master_sku);
    }

    public function get_competitor_rpt_data($where)
    {
        return $this->get_dao()->get_competitor_rpt_data($where);
    }

    public function update_last_price($country_id)
    {
        return $this->get_dao()->update_last_price($country_id);
    }

    public function get_product_identifier_list_grouped_by_country($where = array())
    {
        return $this->get_dao()->get_product_identifier_list_grouped_by_country($where);
    }
}
