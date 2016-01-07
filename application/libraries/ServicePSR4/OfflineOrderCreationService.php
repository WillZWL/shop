<?php
namespace ESG\Panther\Service;

class OfflineOrderCreationService extends BaseService implements CreateSoInterface
{
    private $_checkoutFormData = null;
    private $_checkoutInfoDto = null;

    public function __construct($formValue) {
        parent::__construct();
        $this->_checkoutFormData = $formValue;
    }

    public function getBizType() {
        return "OFFLINE";
    }

    public function selfCreateClientObj() {
        return false;
    }

    public function getCheckoutData() {
        if (!$this->_checkoutInfoDto) {
            $this->_checkoutInfoDto = new \CheckoutInfoDto;
            $this->_checkoutInfoDto->setOrderReason($this->_checkoutFormData["so_extend"]["order_reason"]);
            $this->_checkoutInfoDto->setOrderNotes($this->_checkoutFormData["so_extend"]["notes"]);
            $this->_checkoutInfoDto->setEmail($this->_checkoutFormData["client"]["email"]);
            $this->_checkoutInfoDto->setBillPassword($this->_checkoutFormData["client"]["password"]);
            $this->_checkoutInfoDto->setBillConfirmPassword($this->_checkoutFormData["client"]["password"]);
            $this->_checkoutInfoDto->setTitle($this->_checkoutFormData["client"]["title"]);

            $this->_checkoutInfoDto->setBillFirstName($this->_checkoutFormData["client"]["forename"]);
            $this->_checkoutInfoDto->setBillLastName($this->_checkoutFormData["client"]["surname"]);
            $this->_checkoutInfoDto->setBillCompany($this->_checkoutFormData["client"]["companyname"]);
            $this->_checkoutInfoDto->setBillCountry($this->_checkoutFormData["client"]["country_id"]);
            $this->_checkoutInfoDto->setBillAddress1($this->_checkoutFormData["client"]["address_1"]);
            $this->_checkoutInfoDto->setBillAddress2($this->_checkoutFormData["client"]["address_2"]);
            $this->_checkoutInfoDto->setBillCity($this->_checkoutFormData["client"]["city"]);
            $this->_checkoutInfoDto->setBillState($this->_checkoutFormData["client"]["state"]);
            $this->_checkoutInfoDto->setBillPostal($this->_checkoutFormData["client"]["postcode"]);
            $this->_checkoutInfoDto->setBillTelCountryCode($this->_checkoutFormData["client"]["tel_1"]);
            $this->_checkoutInfoDto->setBillTelAreaCode($this->_checkoutFormData["client"]["tel_2"]);
            $this->_checkoutInfoDto->setBillTelNumber($this->_checkoutFormData["client"]["tel_3"]);
            $this->_checkoutInfoDto->setMobile($this->_checkoutFormData["client"]["mtel_1"] . $this->_checkoutFormData["client"]["mtel_2"] . $this->_checkoutFormData["client"]["mtel_3"]);

            $this->_checkoutInfoDto->setShipFirstName($this->_checkoutFormData["client"]["forename"]);
            $this->_checkoutInfoDto->setShipLastName($this->_checkoutFormData["client"]["surname"]);
            $this->_checkoutInfoDto->setShipCompany($this->_checkoutFormData["client"]["companyname"]);
            $this->_checkoutInfoDto->setShipCountry($this->_checkoutFormData["client"]["country_id"]);
            $this->_checkoutInfoDto->setShipAddress1($this->_checkoutFormData["client"]["address_1"]);
            $this->_checkoutInfoDto->setShipAddress2($this->_checkoutFormData["client"]["address_2"]);
            $this->_checkoutInfoDto->setShipCity($this->_checkoutFormData["client"]["city"]);
            $this->_checkoutInfoDto->setShipState($this->_checkoutFormData["client"]["state"]);
            $this->_checkoutInfoDto->setShipPostal($this->_checkoutFormData["client"]["postcode"]);
            $this->_checkoutInfoDto->setShipTelCountryCode($this->_checkoutFormData["client"]["tel_1"]);
            $this->_checkoutInfoDto->setShipTelAreaCode($this->_checkoutFormData["client"]["tel_2"]);
            $this->_checkoutInfoDto->setShipTelNumber($this->_checkoutFormData["client"]["tel_3"]);

            $this->_checkoutInfoDto->setLangId("en");
        }
        return $this->_checkoutInfoDto;
    }

    public function getCartDto() {
        $cart = $this->getService("CartSession")->getCart();
        return $cart;
    }
}
