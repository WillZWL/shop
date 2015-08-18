<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Country_service extends Base_service
{
    private $country_dao;
    private $country_ext_dao;
    private $rma_fc_dao;
    private $country_state_srv;

    public function __construct()
    {
        parent::__construct();
        $CI =& get_instance();
        $this->load = $CI->load;
        $this->load->library('dao/country_dao');
        include_once(APPPATH . "libraries/dao/Country_dao.php");
        $this->set_dao(new Country_dao());
        $this->set_country_dao(new Country_dao());
        include_once(APPPATH . "libraries/dao/Country_ext_dao.php");
        $this->set_country_ext_dao(new Country_ext_dao());
        include_once(APPPATH . "libraries/dao/Rma_fc_dao.php");
        $this->set_rma_fc_dao(new Rma_fc_dao());
        include_once(APPPATH . "libraries/service/Country_state_service.php");
        $this->set_country_state_srv(new Country_state_service());
    }

    public function get_country_dao()
    {
        return $this->country_dao;
    }

    public function set_country_dao(Base_dao $dao)
    {
        $this->country_dao = $dao;
    }

    public function get_country_ext_dao()
    {
        return $this->country_ext_dao;
    }

    public function set_country_ext_dao(Base_dao $dao)
    {
        $this->country_ext_dao = $dao;
    }

    public function get_rma_fc_dao()
    {
        return $this->rma_fc_dao;
    }

    public function set_rma_fc_dao(Base_dao $dao)
    {
        $this->rma_fc_dao = $dao;
    }

    public function get_country_state_srv()
    {
        return $this->country_state_srv;
    }

    public function set_country_state_srv(Base_service $service)
    {
        $this->country_state_srv = $service;
    }

    public function get_country_id_w_platform($platform_id)
    {
        return $this->get_dao()->get_country_id_w_platform($platform_id);
    }

    public function get_country_language_list()
    {
        return $this->get_dao()->get_country_language_list();
    }

    public function is_available_country_id($country_id = null)
    {
        return $this->get_dao()->is_available_country_id($country_id);
    }

    public function get_country_name_list_w_key($where = array(), $option = array())
    {
        $data = array();
        if ($obj_list = $this->get_list($where, $option)) {
            foreach ($obj_list as $obj) {
                $data[$obj->get_id()] = $obj->get_name();
            }
        }
        return $data;
    }

    public function get_sell_country_list($detail = 1)
    {
        return $this->get_dao()->get_sell_country_list($detail);
    }

    public function get_sell_currency_list()
    {
        return $this->get_dao()->get_sell_currency_list();
    }

    public function get_all_available_country_w_correct_lang($lang_id)
    {
        return $this->get_dao()->get_all_available_country_w_correct_lang($lang_id);
    }

    public function is_allowed_postal($country_code, $postal_code)
    {
        return $this->get_dao()->is_allowed_postal($country_code, $postal_code);
    }

}


