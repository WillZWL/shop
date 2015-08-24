<?php
namespace AtomV2\Service;

use AtomV2\Dao\CountryDao;
use AtomV2\Dao\CountryExtDao;
use AtomV2\Dao\RmaFcDao;
use AtomV2\Service\CountryStateService;

class CountryService extends BaseService
{
    private $countryDao;
    private $countryExtDao;
    private $rmaFcDao;
    private $countryStateSrv;

    public function __construct()
    {
        parent::__construct();
        $CI =& get_instance();
        $this->load = $CI->load;

        $this->setDao(new CountryDao);
        $this->setCountryDao(new CountryDao);
        $this->setCountryExtDao(new CountryExtDao);
        $this->setRmaFcDao(new RmaFcDao);
        $this->setCountryStateSrv(new CountryStateService);
    }

    public function getCountryDao()
    {
        return $this->countryDao;
    }

    public function setCountryDao($dao)
    {
        $this->countryDao = $dao;
    }

    public function getCountryExtDao()
    {
        return $this->countryExtDao;
    }

    public function setCountryExtDao($dao)
    {
        $this->countryExtDao = $dao;
    }

    public function getRmaFcDao()
    {
        return $this->rmaFcDao;
    }

    public function setRmaFcDao($dao)
    {
        $this->rmaFcDao = $dao;
    }

    public function getCountryStateSrv()
    {
        return $this->countryStateSrv;
    }

    public function setCountryStateSrv(BaseService $service)
    {
        $this->countryStateSrv = $service;
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


