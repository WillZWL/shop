<?php
class LstransDto
{
    private $id;
    private $so_no;
    private $item_sku;
    private $qty;
    private $amount;
    private $currency_id;
    private $payment = "NA";
    private $is_sent;
    private $conv_site_ref;
    private $ls_time_entered;
    private $pay_date;
    private $email;
    private $delivery_postcode;
    private $prod_name;
    private $create_on = "0000-00-00 00:00:00";
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setSoNo($so_no)
    {
        $this->so_no = $so_no;
    }

    public function getSoNo()
    {
        return $this->so_no;
    }

    public function setItemSku($item_sku)
    {
        $this->item_sku = $item_sku;
    }

    public function getItemSku()
    {
        return $this->item_sku;
    }

    public function setQty($qty)
    {
        $this->qty = $qty;
    }

    public function getQty()
    {
        return $this->qty;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setCurrencyId($currency_id)
    {
        $this->currency_id = $currency_id;
    }

    public function getCurrencyId()
    {
        return $this->currency_id;
    }

    public function setPayment($payment)
    {
        $this->payment = $payment;
    }

    public function getPayment()
    {
        return $this->payment;
    }

    public function setIsSent($is_sent)
    {
        $this->is_sent = $is_sent;
    }

    public function getIsSent()
    {
        return $this->is_sent;
    }

    public function setConvSiteRef($conv_site_ref)
    {
        $this->conv_site_ref = $conv_site_ref;
    }

    public function getConvSiteRef()
    {
        return $this->conv_site_ref;
    }

    public function setLsTimeEntered($ls_time_entered)
    {
        $this->ls_time_entered = $ls_time_entered;
    }

    public function getLsTimeEntered()
    {
        return $this->ls_time_entered;
    }

    public function setPayDate($pay_date)
    {
        $this->pay_date = $pay_date;
    }

    public function getPayDate()
    {
        return $this->pay_date;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setDeliveryPostcode($delivery_postcode)
    {
        $this->delivery_postcode = $delivery_postcode;
    }

    public function getDeliveryPostcode()
    {
        return $this->delivery_postcode;
    }

    public function setProdName($prod_name)
    {
        $this->prod_name = $prod_name;
    }

    public function getProdName()
    {
        return $this->prod_name;
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

}
