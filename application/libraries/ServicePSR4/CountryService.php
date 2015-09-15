<?php
namespace ESG\Panther\Service;

use ESG\Panther\Dao\CountryDao;
use ESG\Panther\Dao\CountryExtDao;
use ESG\Panther\Dao\RmaFcDao;
use ESG\Panther\Service\CountryStateService;

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
        $this->setCountryExtDao(new CountryExtDao);
        $this->setRmaFcDao(new RmaFcDao);
        $this->setCountryStateSrv(new CountryStateService);
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

    public function getCountryIdWithPlatform($platform_id)
    {
        return $this->getDao('Country')->getCountryIdWithPlatform($platform_id);
    }

    public function getCountryLanguageList()
    {
        return $this->getDao('Country')->getCountryLanguageList();
    }

    public function isAvailableCountryId($country_id = null)
    {
        return $this->getDao('Country')->isAvailableCountryId($country_id);
    }

    public function getCountryNameListWithKey($where = array(), $option = array())
    {
        $data = array();
        if ($objList = $this->getDao('Country')->getList($where, $option)) {
            foreach ($objList as $obj) {
                $data[$obj->getCountryId()] = $obj->getName();
            }
        }
        return $data;
    }

    public function getSellCountryList($detail = 1)
    {
        return $this->getDao('Country')->getSellCountryList($detail);
    }

    public function getSellCurrencyList()
    {
        return $this->getDao('Country')->getSellCurrencyList();
    }

    public function getAllAvailableCountryWithCorrectLang($lang_id)
    {
        return $this->getDao('Country')->getAllAvailableCountryWithCorrectLang($lang_id);
    }

    public function isAllowedPostal($country_code, $postal_code)
    {
        return $this->getDao('Country')->isAllowedPostal($country_code, $postal_code);
    }

}


