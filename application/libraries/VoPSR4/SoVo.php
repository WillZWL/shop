<?php
class SoVo extends \BaseVo
{
    private $id;
    private $so_no;
    private $platform_order_id;
    private $platform_id;
    private $txn_id;
    private $client_id;
    private $biz_type;
    private $amount;
    private $cost;
    private $vat_percent;
    private $rate = '1.000000';
    private $ref_1 = '1.000000';
    private $delivery_charge;
    private $delivery_type_id;
    private $weight;
    private $currency_id;
    private $lang_id = 'en';
    private $bill_name;
    private $bill_company;
    private $bill_address;
    private $bill_postcode;
    private $bill_city;
    private $bill_state;
    private $bill_country_id;
    private $delivery_name;
    private $delivery_company;
    private $delivery_address;
    private $delivery_postcode;
    private $delivery_city;
    private $delivery_state;
    private $delivery_country_id;
    private $parent_so_no;
    private $status = '1';
    private $refund_status;
    private $hold_status;
    private $promotion_code;
    private $client_promotion_code;
    private $expect_delivery_date = '0000-00-00';
    private $expect_ship_days;
    private $expect_del_days;
    private $order_create_date;
    private $dispatch_date = '0000-00-00 00:00:00';
    private $finance_dispatch_date = '0000-00-00 00:00:00';
    private $fingerprintId;
    private $cc_reminder_schedule_date = '0000-00-00 00:00:00';
    private $cc_reminder_type;
    private $cs_customer_query;
    private $split_status;
    private $split_create_on = '0000-00-00 00:00:00';
    private $split_create_by;
    private $split_so_group;
    private $create_on = '0000-00-00 00:00:00';
    private $create_at = '2130706433';
    private $create_by = 'system';
    private $modify_on = 'CURRENT_TIMESTAMP';
    private $modify_at = '2130706433';
    private $modify_by = 'system';

    private $primary_key = ['id'];
    private $increment_field = 'id';

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

    public function setPlatformOrderId($platform_order_id)
    {
        $this->platform_order_id = $platform_order_id;
    }

    public function getPlatformOrderId()
    {
        return $this->platform_order_id;
    }

    public function setPlatformId($platform_id)
    {
        $this->platform_id = $platform_id;
    }

    public function getPlatformId()
    {
        return $this->platform_id;
    }

    public function setTxnId($txn_id)
    {
        $this->txn_id = $txn_id;
    }

    public function getTxnId()
    {
        return $this->txn_id;
    }

    public function setClientId($client_id)
    {
        $this->client_id = $client_id;
    }

    public function getClientId()
    {
        return $this->client_id;
    }

    public function setBizType($biz_type)
    {
        $this->biz_type = $biz_type;
    }

    public function getBizType()
    {
        return $this->biz_type;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setCost($cost)
    {
        $this->cost = $cost;
    }

    public function getCost()
    {
        return $this->cost;
    }

    public function setVatPercent($vat_percent)
    {
        $this->vat_percent = $vat_percent;
    }

    public function getVatPercent()
    {
        return $this->vat_percent;
    }

    public function setRate($rate)
    {
        $this->rate = $rate;
    }

    public function getRate()
    {
        return $this->rate;
    }

    public function setRef1($ref_1)
    {
        $this->ref_1 = $ref_1;
    }

    public function getRef1()
    {
        return $this->ref_1;
    }

    public function setDeliveryCharge($delivery_charge)
    {
        $this->delivery_charge = $delivery_charge;
    }

    public function getDeliveryCharge()
    {
        return $this->delivery_charge;
    }

    public function setDeliveryTypeId($delivery_type_id)
    {
        $this->delivery_type_id = $delivery_type_id;
    }

    public function getDeliveryTypeId()
    {
        return $this->delivery_type_id;
    }

    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    public function getWeight()
    {
        return $this->weight;
    }

    public function setCurrencyId($currency_id)
    {
        $this->currency_id = $currency_id;
    }

    public function getCurrencyId()
    {
        return $this->currency_id;
    }

    public function setLangId($lang_id)
    {
        $this->lang_id = $lang_id;
    }

    public function getLangId()
    {
        return $this->lang_id;
    }

    public function setBillName($bill_name)
    {
        $this->bill_name = $bill_name;
    }

    public function getBillName()
    {
        return $this->bill_name;
    }

    public function setBillCompany($bill_company)
    {
        $this->bill_company = $bill_company;
    }

    public function getBillCompany()
    {
        return $this->bill_company;
    }

    public function setBillAddress($bill_address)
    {
        $this->bill_address = $bill_address;
    }

    public function getBillAddress()
    {
        return $this->bill_address;
    }

    public function setBillPostcode($bill_postcode)
    {
        $this->bill_postcode = $bill_postcode;
    }

    public function getBillPostcode()
    {
        return $this->bill_postcode;
    }

    public function setBillCity($bill_city)
    {
        $this->bill_city = $bill_city;
    }

    public function getBillCity()
    {
        return $this->bill_city;
    }

    public function setBillState($bill_state)
    {
        $this->bill_state = $bill_state;
    }

    public function getBillState()
    {
        return $this->bill_state;
    }

    public function setBillCountryId($bill_country_id)
    {
        $this->bill_country_id = $bill_country_id;
    }

    public function getBillCountryId()
    {
        return $this->bill_country_id;
    }

    public function setDeliveryName($delivery_name)
    {
        $this->delivery_name = $delivery_name;
    }

    public function getDeliveryName()
    {
        return $this->delivery_name;
    }

    public function setDeliveryCompany($delivery_company)
    {
        $this->delivery_company = $delivery_company;
    }

    public function getDeliveryCompany()
    {
        return $this->delivery_company;
    }

    public function setDeliveryAddress($delivery_address)
    {
        $this->delivery_address = $delivery_address;
    }

    public function getDeliveryAddress()
    {
        return $this->delivery_address;
    }

    public function setDeliveryPostcode($delivery_postcode)
    {
        $this->delivery_postcode = $delivery_postcode;
    }

    public function getDeliveryPostcode()
    {
        return $this->delivery_postcode;
    }

    public function setDeliveryCity($delivery_city)
    {
        $this->delivery_city = $delivery_city;
    }

    public function getDeliveryCity()
    {
        return $this->delivery_city;
    }

    public function setDeliveryState($delivery_state)
    {
        $this->delivery_state = $delivery_state;
    }

    public function getDeliveryState()
    {
        return $this->delivery_state;
    }

    public function setDeliveryCountryId($delivery_country_id)
    {
        $this->delivery_country_id = $delivery_country_id;
    }

    public function getDeliveryCountryId()
    {
        return $this->delivery_country_id;
    }

    public function setParentSoNo($parent_so_no)
    {
        $this->parent_so_no = $parent_so_no;
    }

    public function getParentSoNo()
    {
        return $this->parent_so_no;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setRefundStatus($refund_status)
    {
        $this->refund_status = $refund_status;
    }

    public function getRefundStatus()
    {
        return $this->refund_status;
    }

    public function setHoldStatus($hold_status)
    {
        $this->hold_status = $hold_status;
    }

    public function getHoldStatus()
    {
        return $this->hold_status;
    }

    public function setPromotionCode($promotion_code)
    {
        $this->promotion_code = $promotion_code;
    }

    public function getPromotionCode()
    {
        return $this->promotion_code;
    }

    public function setClientPromotionCode($client_promotion_code)
    {
        $this->client_promotion_code = $client_promotion_code;
    }

    public function getClientPromotionCode()
    {
        return $this->client_promotion_code;
    }

    public function setExpectDeliveryDate($expect_delivery_date)
    {
        $this->expect_delivery_date = $expect_delivery_date;
    }

    public function getExpectDeliveryDate()
    {
        return $this->expect_delivery_date;
    }

    public function setExpectShipDays($expect_ship_days)
    {
        $this->expect_ship_days = $expect_ship_days;
    }

    public function getExpectShipDays()
    {
        return $this->expect_ship_days;
    }

    public function setExpectDelDays($expect_del_days)
    {
        $this->expect_del_days = $expect_del_days;
    }

    public function getExpectDelDays()
    {
        return $this->expect_del_days;
    }

    public function setOrderCreateDate($order_create_date)
    {
        $this->order_create_date = $order_create_date;
    }

    public function getOrderCreateDate()
    {
        return $this->order_create_date;
    }

    public function setDispatchDate($dispatch_date)
    {
        $this->dispatch_date = $dispatch_date;
    }

    public function getDispatchDate()
    {
        return $this->dispatch_date;
    }

    public function setFinanceDispatchDate($finance_dispatch_date)
    {
        $this->finance_dispatch_date = $finance_dispatch_date;
    }

    public function getFinanceDispatchDate()
    {
        return $this->finance_dispatch_date;
    }

    public function setFingerprintId($fingerprintId)
    {
        $this->fingerprintId = $fingerprintId;
    }

    public function getFingerprintId()
    {
        return $this->fingerprintId;
    }

    public function setCcReminderScheduleDate($cc_reminder_schedule_date)
    {
        $this->cc_reminder_schedule_date = $cc_reminder_schedule_date;
    }

    public function getCcReminderScheduleDate()
    {
        return $this->cc_reminder_schedule_date;
    }

    public function setCcReminderType($cc_reminder_type)
    {
        $this->cc_reminder_type = $cc_reminder_type;
    }

    public function getCcReminderType()
    {
        return $this->cc_reminder_type;
    }

    public function setCsCustomerQuery($cs_customer_query)
    {
        $this->cs_customer_query = $cs_customer_query;
    }

    public function getCsCustomerQuery()
    {
        return $this->cs_customer_query;
    }

    public function setSplitStatus($split_status)
    {
        $this->split_status = $split_status;
    }

    public function getSplitStatus()
    {
        return $this->split_status;
    }

    public function setSplitCreateOn($split_create_on)
    {
        $this->split_create_on = $split_create_on;
    }

    public function getSplitCreateOn()
    {
        return $this->split_create_on;
    }

    public function setSplitCreateBy($split_create_by)
    {
        $this->split_create_by = $split_create_by;
    }

    public function getSplitCreateBy()
    {
        return $this->split_create_by;
    }

    public function setSplitSoGroup($split_so_group)
    {
        $this->split_so_group = $split_so_group;
    }

    public function getSplitSoGroup()
    {
        return $this->split_so_group;
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

    public function getPrimaryKey()
    {
        return $this->primary_key;
    }

    public function getIncrementField()
    {
        return $this->increment_field;
    }
}
