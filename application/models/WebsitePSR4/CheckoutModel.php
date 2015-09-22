<?php
namespace ESG\Panther\Models\Website;
use ESG\Panther\Service\CountryService;
use ESG\Panther\Service\CountryStateService;

class CheckoutModel extends \CI_Model
{
    const BILLING_COUNTRY = "billing";
    const SHIPPING_COUNTRY = "shipping";

    private $_countryService;
    private $_stateService;

    public function __construct() {
        parent::__construct();
        $this->setCountryService(new CountryService());
        $this->setCountryStateService(new CountryStateService());
    }

    public function getCheckoutFormCountryList($platformCountryId, $type = self::BILLING_COUNTRY) {
        $data = array();
        $countryExtObj = $this->_countryService->getCountryExtDao()->get(["cid" => $platformCountryId, "lang_id" => LANG_ID]);
        $data[$type]["countryName"] = $countryExtObj->getName();
        $data[$type]["countryId"] = $platformCountryId;
        return $data;
    }

    public function getCheckoutFormStateList($platformCountryId, $type = self::BILLING_COUNTRY) {
        $stateList = $this->getCountryStateService()->getDao()->getList(["country_id" => $platformCountryId, "status" => 1], ["limit" => -1]);
        return $stateList;
    }

    public function setCountryService($countryService) {
        $this->_countryService = $countryService;
    }

    public function getCountryService() {
        return $this->_countryService;
    }

    public function setCountryStateService($stateService) {
        $this->_stateService = $stateService;
    }

    public function getCountryStateService() {
        return $this->_stateService;
    }
}
