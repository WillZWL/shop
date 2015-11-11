<?php
class SoWithClientAndItemDto
{
    public $so_item = [];
    private $so_no;
    private $fingerprint_id;
    private $forename;
    private $surname;
    private $companyname;
    private $payment_gateway_id;
    private $risk_ref_3;
    private $risk_ref_4;
    private $tel_1;
    private $tel_2;
    private $tel_3;
    private $create_on;
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;
    private $currency_id;
    private $amount;
    private $lang_id;
    private $client_id_no;
    private $address_1;
    private $address_2;
    private $address_3;
    private $postcode;
    private $city;
    private $state;
    private $country_id;
    private $del_name;
    private $del_address_1;
    private $del_address_2;
    private $del_address_3;
    private $del_postcode;
    private $del_city;
    private $del_state;
    private $del_country_id;
    private $del_company;
    private $line_no;
    private $prod_sku;
    private $prod_name;
    private $qty;
    private $email;
    private $payer_email;
    private $unit_price;

    public function setSoNo($so_no)
    {
        $this->so_no = $so_no;
    }

    public function getSoNo()
    {
        return $this->so_no;
    }

    public function setFingerprintId($fingerprint_id)
    {
        $this->fingerprint_id = $fingerprint_id;
    }

    public function getFingerprintId()
    {
        return $this->fingerprint_id;
    }

    public function setForename($forename)
    {
        $this->forename = $forename;
    }

    public function getForename()
    {
        return $this->forename;
    }

    public function setSurname($surname)
    {
        $this->surname = $surname;
    }

    public function getSurname()
    {
        return $this->surname;
    }

    public function setCompanyname($companyname)
    {
        $this->companyname = $companyname;
    }

    public function getCompanyname()
    {
        return $this->companyname;
    }

    public function setPaymentGatewayId($payment_gateway_id)
    {
        $this->payment_gateway_id = $payment_gateway_id;
    }

    public function getPaymentGatewayId()
    {
        return $this->payment_gateway_id;
    }

    public function setRiskRef3($risk_ref_3)
    {
        $this->risk_ref_3 = $risk_ref_3;
    }

    public function getRiskRef3()
    {
        return $this->risk_ref_3;
    }

    public function setRiskRef4($risk_ref_4)
    {
        $this->risk_ref_4 = $risk_ref_4;
    }

    public function getRiskRef4()
    {
        return $this->risk_ref_4;
    }

    public function setTel1($tel_1)
    {
        $this->tel_1 = $tel_1;
    }

    public function getTel1()
    {
        return $this->tel_1;
    }

    public function setTel2($tel_2)
    {
        $this->tel_2 = $tel_2;
    }

    public function getTel2()
    {
        return $this->tel_2;
    }

    public function setTel3($tel_3)
    {
        $this->tel_3 = $tel_3;
    }

    public function getTel3()
    {
        return $this->tel_3;
    }

    public function setCreateOn($create_on)
    {
        $this->create_on = $create_on;
    }

    public function getCreateOn()
    {
        return $this->create_on;
    }

    public function setCreateAt($create_at)
    {
        $this->create_at = $create_at;
    }

    public function getCreateAt()
    {
        return $this->create_at;
    }

    public function setCreateBy($create_by)
    {
        $this->create_by = $create_by;
    }

    public function getCreateBy()
    {
        return $this->create_by;
    }

    public function setModifyOn($modify_on)
    {
        $this->modify_on = $modify_on;
    }

    public function getModifyOn()
    {
        return $this->modify_on;
    }

    public function setModifyAt($modify_at)
    {
        $this->modify_at = $modify_at;
    }

    public function getModifyAt()
    {
        return $this->modify_at;
    }

    public function setModifyBy($modify_by)
    {
        $this->modify_by = $modify_by;
    }

    public function getModifyBy()
    {
        return $this->modify_by;
    }

    public function setCurrencyId($currency_id)
    {
        $this->currency_id = $currency_id;
    }

    public function getCurrencyId()
    {
        return $this->currency_id;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setLangId($lang_id)
    {
        $this->lang_id = $lang_id;
    }

    public function getLangId()
    {
        return $this->lang_id;
    }

    public function setClientIdNo($client_id_no)
    {
        $this->client_id_no = $client_id_no;
    }

    public function getClientIdNo()
    {
        return $this->client_id_no;
    }

    public function setAddress1($address_1)
    {
        $this->address_1 = $address_1;
    }

    public function getAddress1()
    {
        return $this->address_1;
    }

    public function setAddress2($address_2)
    {
        $this->address_2 = $address_2;
    }

    public function getAddress2()
    {
        return $this->address_2;
    }

    public function setAddress3($address_3)
    {
        $this->address_3 = $address_3;
    }

    public function getAddress3()
    {
        return $this->address_3;
    }

    public function setPostcode($postcode)
    {
        $this->postcode = $postcode;
    }

    public function getPostcode()
    {
        return $this->postcode;
    }

    public function setCity($city)
    {
        $this->city = $city;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setState($state)
    {
        $this->state = $state;
    }

    public function getState()
    {
        return $this->state;
    }

    public function setCountryId($country_id)
    {
        $this->country_id = $country_id;
    }

    public function getCountryId()
    {
        return $this->country_id;
    }

    public function setDelName($del_name)
    {
        $this->del_name = $del_name;
    }

    public function getDelName()
    {
        return $this->del_name;
    }

    public function setDelAddress1($del_address_1)
    {
        $this->del_address_1 = $del_address_1;
    }

    public function getDelAddress1()
    {
        return $this->del_address_1;
    }

    public function setDelAddress2($del_address_2)
    {
        $this->del_address_2 = $del_address_2;
    }

    public function getDelAddress2()
    {
        return $this->del_address_2;
    }

    public function setDelAddress3($del_address_3)
    {
        $this->del_address_3 = $del_address_3;
    }

    public function getDelAddress3()
    {
        return $this->del_address_3;
    }

    public function setDelPostcode($del_postcode)
    {
        $this->del_postcode = $del_postcode;
    }

    public function getDelPostcode()
    {
        return $this->del_postcode;
    }

    public function setDelCity($del_city)
    {
        $this->del_city = $del_city;
    }

    public function getDelCity()
    {
        return $this->del_city;
    }

    public function setDelState($del_state)
    {
        $this->del_state = $del_state;
    }

    public function getDelState()
    {
        return $this->del_state;
    }

    public function setDelCountryId($del_country_id)
    {
        $this->del_country_id = $del_country_id;
    }

    public function getDelCountryId()
    {
        return $this->del_country_id;
    }

    public function setDelCompany($del_company)
    {
        $this->del_company = $del_company;
    }

    public function getDelCompany()
    {
        return $this->del_company;
    }

    public function setLineNo($line_no)
    {
        $this->line_no = $line_no;
    }

    public function getLineNo()
    {
        return $this->line_no;
    }

    public function setProdSku($prod_sku)
    {
        $this->prod_sku = $prod_sku;
    }

    public function getProdSku()
    {
        return $this->prod_sku;
    }

    public function setProdName($prod_name)
    {
        $this->prod_name = $prod_name;
    }

    public function getProdName()
    {
        return $this->prod_name;
    }

    public function setQty($qty)
    {
        $this->qty = $qty;
    }

    public function getQty()
    {
        return $this->qty;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setPayerEmail($payer_email)
    {
        $this->payer_email = $payer_email;
    }

    public function getPayerEmail()
    {
        return $this->payer_email;
    }

    public function setUnitPrice($unit_price)
    {
        $this->unit_price = $unit_price;
    }

    public function getUnitPrice()
    {
        return $this->unit_price;
    }

}
