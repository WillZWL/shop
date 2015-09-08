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

    public function getCountryIdWithPlatform($platform_id)
    {
        return $this->getDao()->getCountryIdWithPlatform($platform_id);
    }

    public function getCountryLanguageList()
    {
        return $this->getDao()->getCountryLanguageList();
    }

    public function isAvailableCountryId($country_id = null)
    {
        return $this->getDao()->isAvailableCountryId($country_id);
    }

    public function getCountryNameListWithKey($where = array(), $option = array())
    {
        $data = array();
        if ($objList = $this->getDao()->getList($where, $option)) {
            foreach ($objList as $obj) {
                $data[$obj->getCountryId()] = $obj->getName();
            }
        }
        return $data;
    }

    public function getSellCountryList($detail = 1)
    {
        return $this->getDao()->getSellCountryList($detail);
    }

    public function getSellCurrencyList()
    {
        return $this->getDao()->getSellCurrencyList();
    }

    public function getAllAvailableCountryWithCorrectLang($lang_id)
    {
        return $this->getDao()->getAllAvailableCountryWithCorrectLang($lang_id);
    }

    public function isAllowedPostal($country_code, $postal_code)
    {
        return $this->getDao()->isAllowedPostal($country_code, $postal_code);
    }

}


