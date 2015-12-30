<?php

class CheckoutInfoDto
{
    public $formSalt;
    public $cybersourceFingerprint;
    public $billFirstName;
    public $billLastName;
    public $billCompany;
    public $billCountry;
    public $billAddress1;
    public $billAddress2;
    public $billAddress3;
    public $billCity;
    public $billPostal;
    public $billState;
    public $billTelCountryCode;
    public $billTelAreaCode;
    public $billTelNumber;
    public $email;
    public $billPassword;
    public $billConfirmPassword;
    public $shipFirstName;
    public $shipLastName;
    public $shipCompany;
    public $shipAddress1;
    public $shipAddress2;
    public $shipAddress3;
    public $shipCity;
    public $shipPostal;
    public $shipState;
    public $shipTelCountryCode;
    public $shipTelAreaCode;
    public $shipTelNumber;
    public $shipCountry;
    public $paymentMethod;
    public $paymentCardCode;
    public $paymentCardId;
    public $paymentGatewayId;
    public $extClientId;
    public $clientIdNo;
    public $subscriber;
    public $partySubscriber;
    public $vip;
    public $convSiteId;
    public $convSiteRef;
    public $orderReason;
    public $orderNotes;
    public $parentSoNo;
    public $langId;

    public function setFormSalt($formSalt) {
        $this->formSalt = $formSalt;
    }

    public function getFormSalt() {
        return $this->formSalt;
    }

    public function setCybersourceFingerprint($cybersourceFingerprint) {
        $this->cybersourceFingerprint = $cybersourceFingerprint;
    }

    public function getCybersourceFingerprint() {
        return $this->cybersourceFingerprint;
    }

    public function setBillFirstName($billFirstName) {
        $this->billFirstName = $billFirstName;
    }

    public function getBillFirstName() {
        return $this->billFirstName;
    }

    public function setBillLastName($billLastName) {
        $this->billLastName = $billLastName;
    }

    public function getBillLastName() {
        return $this->billLastName;
    }

    public function setBillCompany($billCompany) {
        $this->billCompany = $billCompany;
    }

    public function getBillCompany() {
        return $this->billCompany;
    }

    public function setBillCountry($billCountry) {
        $this->billCountry = $billCountry;
    }

    public function getBillCountry() {
        return $this->billCountry;
    }
    
    public function setBillAddress1($billAddress1) {
        $this->billAddress1 = $billAddress1;
    }

    public function getBillAddress1() {
        return $this->billAddress1;
    }
    
    public function setBillAddress2($billAddress2) {
        $this->billAddress2 = $billAddress2;
    }

    public function getBillAddress2() {
        return $this->billAddress2;
    }

    public function setBillAddress3($billAddress3) {
        $this->billAddress3 = $billAddress3;
    }

    public function getBillAddress3() {
        return $this->billAddress3;
    }

    public function setBillCity($billCity) {
        $this->billCity = $billCity;
    }

    public function getBillCity() {
        return $this->billCity;
    }

    public function setBillPostal($billPostal) {
        $this->billPostal = $billPostal;
    }

    public function getBillPostal() {
        return $this->billPostal;
    }

    public function setBillState($billState) {
        $this->billState = $billState;
    }

    public function getBillState() {
        return $this->billState;
    }

    public function setBillTelCountryCode($billTelCountryCode) {
        $this->billTelCountryCode = $billTelCountryCode;
    }

    public function getBillTelCountryCode() {
        return $this->billTelCountryCode;
    }

    public function setBillTelAreaCode($billTelAreaCode) {
        $this->billTelAreaCode = $billTelAreaCode;
    }

    public function getBillTelAreaCode() {
        return $this->billTelAreaCode;
    }

    public function setBillTelNumber($billTelNumber) {
        $this->billTelNumber = $billTelNumber;
    }

    public function getBillTelNumber() {
        return $this->billTelNumber;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setBillPassword($billPassword) {
        $this->billPassword = $billPassword;
    }

    public function getBillPassword() {
        return $this->billPassword;
    }

    public function setBillConfirmPassword($billConfirmPassword) {
        $this->billConfirmPassword = $billConfirmPassword;
    }

    public function getBillConfirmPassword() {
        return $this->billConfirmPassword;
    }

    public function setShipFirstName($shipFirstName) {
        $this->shipFirstName = $shipFirstName;
    }

    public function getShipFirstName() {
        return $this->shipFirstName;
    }

    public function setShipLastName($shipLastName) {
        $this->shipLastName = $shipLastName;
    }

    public function getShipLastName() {
        return $this->shipLastName;
    }

    public function setShipCompany($shipCompany) {
        $this->shipCompany = $shipCompany;
    }

    public function getShipCompany() {
        return $this->shipCompany;
    }

    public function setShipAddress1($shipAddress1) {
        $this->shipAddress1 = $shipAddress1;
    }

    public function getShipAddress1() {
        return $this->shipAddress1;
    }

    public function setShipAddress2($shipAddress2) {
        $this->shipAddress2 = $shipAddress2;
    }

    public function getShipAddress2() {
        return $this->shipAddress2;
    }
    
    public function setShipAddress3($shipAddress3) {
        $this->shipAddress3 = $shipAddress3;
    }

    public function getShipAddress3() {
        return $this->shipAddress3;
    }

    public function setShipCity($shipCity) {
        $this->shipCity = $shipCity;
    }

    public function getShipCity() {
        return $this->shipCity;
    }

    public function setShipPostal($shipPostal) {
        $this->shipPostal = $shipPostal;
    }

    public function getShipPostal() {
        return $this->shipPostal;
    }

    public function setShipState($shipState) {
        $this->shipState = $shipState;
    }

    public function getShipState() {
        return $this->shipState;
    }

    public function setShipTelCountryCode($shipTelCountryCode) {
        $this->shipTelCountryCode = $shipTelCountryCode;
    }

    public function getShipTelCountryCode() {
        return $this->shipTelCountryCode;
    }

    public function setShipTelAreaCode($shipTelAreaCode) {
        $this->shipTelAreaCode = $shipTelAreaCode;
    }

    public function getShipTelAreaCode() {
        return $this->shipTelAreaCode;
    }

    public function setShipTelNumber($shipTelNumber) {
        $this->shipTelNumber = $shipTelNumber;
    }

    public function getShipTelNumber() {
        return $this->shipTelNumber;
    }

    public function setShipCountry($shipCountry) {
        $this->shipCountry = $shipCountry;
    }

    public function getShipCountry() {
        return $this->shipCountry;
    }

    public function setPaymentMethod($paymentMethod) {
        $this->paymentMethod = $paymentMethod;
    }

    public function getPaymentMethod() {
        return $this->paymentMethod;
    }

    public function setPaymentCardCode($paymentCardCode) {
        $this->paymentCardCode = $paymentCardCode;
    }

    public function getPaymentCardCode() {
        return $this->paymentCardCode;
    }

    public function setPaymentCardId($paymentCardId) {
        $this->paymentCardId = $paymentCardId;
    }

    public function getPaymentCardId() {
        return $this->paymentCardId;
    }

    public function setPaymentGatewayId($paymentGatewayId) {
        $this->paymentGatewayId = $paymentGatewayId;
    }

    public function getPaymentGatewayId() {
        return $this->paymentGatewayId;
    }

    public function setExtClientId($extClientId) {
        $this->extClientId = $extClientId;
    }

    public function getExtClientId() {
        return $this->extClientId;
    }

    public function setClientIdNo($clientIdNo) {
        $this->clientIdNo = $clientIdNo;
    }

    public function getClientIdNo() {
        return $this->clientIdNo;
    }

    public function setSubscriber($subscriber) {
        $this->subscriber = $subscriber;
    }

    public function getSubscriber() {
        return $this->subscriber;
    }

    public function setPartySubscriber($partySubscriber) {
        $this->partySubscriber = $partySubscriber;
    }

    public function getPartySubscriber() {
        return $this->partySubscriber;
    }

    public function setVip($vip) {
        $this->vip = $vip;
    }

    public function getVip() {
        return $this->vip;
    }

    public function setConvSiteId($convSiteId) {
        $this->convSiteId = $convSiteId;
    }

    public function getConvSiteId() {
        return $this->convSiteId;
    }

    public function setConvSiteRef($convSiteRef) {
        $this->convSiteRef = $convSiteRef;
    }

    public function getConvSiteRef() {
        return $this->convSiteRef;
    }

    public function setOrderReason($orderReason) {
        $this->orderReason = $orderReason;
    }

    public function getOrderReason() {
        return $this->orderReason;
    }

    public function setOrderNotes($orderNotes) {
        $this->orderNotes = $orderNotes;
    }

    public function getOrderNotes() {
        return $this->orderNotes;
    }

    public function setParentSoNo($parentSoNo) {
        $this->parentSoNo = $parentSoNo;
    }

    public function getParentSoNo() {
        return $this->parentSoNo;
    }

    public function setLangId($langId) {
        $this->langId = $langId;
    }

    public function getLangId() {
        return $this->langId;
    }
}