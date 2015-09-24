<?php
namespace ESG\Panther\Models\Website;
use ESG\Panther\Service\CountryService;
use ESG\Panther\Service\CountryStateService;
use ESG\Panther\Service\PaymentOptionService;
use ESG\Panther\Service\SoFactoryService;
use ESG\Panther\Service\CartSessionService;

class CheckoutModel extends \CI_Model
{
    const BILLING_COUNTRY = "billing";
    const SHIPPING_COUNTRY = "shipping";

    private $_countryService;
    private $_stateService;
    private $_paymentOptionService;
    private $_soFactoryService;
    private $_cartSessionService;

    public function __construct() {
        parent::__construct();
        $this->setCountryService(new CountryService());
        $this->setCountryStateService(new CountryStateService());
        $this->setPaymentOptionService(new PaymentOptionService());
        $this->setSoFactoryService(new SoFactoryService());
        $this->setCartSessionService(new CartSessionService());
    }

    public function createSaleOrder($formValue) {
        $cart = $this->getCartSessionService()->getCart();
        var_dump($cart);
        $this->getSoFactoryService()->createSaleOrder($formValue, $cart);
    }

    public function getPaymentOption($platformId) {
        return $this->getPaymentOptionService()->getPaymentOptionByPlatformId($platformId);    
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

    public function setPaymentOptionService($paymentOptionService) {
        $this->_paymentOptionService = $paymentOptionService;
    }

    public function getPaymentOptionService() {
        return $this->_paymentOptionService;
    }

    public function setSoFactoryService($soFactoryService) {
        $this->_soFactoryService = $soFactoryService;
    }

    public function getSoFactoryService() {
        return $this->_soFactoryService;
    }

    public function setCartSessionService($cartSessionService) {
        $this->_cartSessionService = $cartSessionService;
    }

    public function getCartSessionService() {
        return $this->_cartSessionService;
    }

}
