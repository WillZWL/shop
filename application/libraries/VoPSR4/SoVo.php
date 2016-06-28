<?php
class SoVo extends \BaseVo
{
    private $id;
    private $so_no;
    private $platform_order_id;
    private $platform_id;
    private $txn_id = '';
    private $client_id = '0';
    private $biz_type;
    private $amount;
    private $cost;
    private $vat_percent = '0.00';
    private $vat = '0.00';
    private $rate = '1.000000';
    private $rate_to_hkd = '1.000000';
    private $ref_1 = '1.000000';
    private $delivery_charge = '0.00';
    private $declared_value = '0.00';
    private $delivery_type_id;
    private $rec_courier = '';
    private $weight;
    private $order_total_item = '0';
    private $currency_id;
    private $lang_id = 'en';
    private $bill_name = '';
    private $bill_company = '';
    private $bill_address;
    private $bill_postcode = '';
    private $bill_city = '';
    private $bill_state = '';
    private $bill_country_id = '';
    private $delivery_name = '';
    private $delivery_company = '';
    private $delivery_address;
    private $delivery_postcode = '';
    private $delivery_city = '';
    private $delivery_state = '';
    private $delivery_country_id = '';
    private $parent_so_no = '';
    private $status = '1';
    private $refund_status = '0';
    private $hold_status = '0';
    private $hold_reason = '';
    private $refund_reason = '';
    private $order_note = '';
    private $payment_gateway_id = '';
    private $promotion_code = '';
    private $promo_disc_total = '';
    private $client_promotion_code = '';
    private $expect_delivery_date = '0000-00-00';
    private $expect_ship_days = '';
    private $expect_del_days = '';
    private $order_create_date;
    private $dispatch_date = '0000-00-00 00:00:00';
    private $fingerprint_id = '';
    private $cc_reminder_schedule_date = '0000-00-00 00:00:00';
    private $cc_reminder_type = '';
    private $cs_customer_query = '0';
    private $split_status = '0';
    private $split_create_on = '0000-00-00 00:00:00';
    private $split_create_by = '';
    private $split_so_group = '';

    public function setId($id)
    {
        if ($id !== null) {
            $this->id = $id;
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function setSoNo($so_no)
    {
        if ($so_no !== null) {
            $this->so_no = $so_no;
        }
    }

    public function getSoNo()
    {
        return $this->so_no;
    }

    public function setPlatformOrderId($platform_order_id)
    {
        if ($platform_order_id !== null) {
            $this->platform_order_id = $platform_order_id;
        }
    }

    public function getPlatformOrderId()
    {
        return $this->platform_order_id;
    }

    public function setPlatformId($platform_id)
    {
        if ($platform_id !== null) {
            $this->platform_id = $platform_id;
        }
    }

    public function getPlatformId()
    {
        return $this->platform_id;
    }

    public function setTxnId($txn_id)
    {
        if ($txn_id !== null) {
            $this->txn_id = $txn_id;
        }
    }

    public function getTxnId()
    {
        return $this->txn_id;
    }

    public function setClientId($client_id)
    {
        if ($client_id !== null) {
            $this->client_id = $client_id;
        }
    }

    public function getClientId()
    {
        return $this->client_id;
    }

    public function setBizType($biz_type)
    {
        if ($biz_type !== null) {
            $this->biz_type = $biz_type;
        }
    }

    public function getBizType()
    {
        return $this->biz_type;
    }

    public function setAmount($amount)
    {
        if ($amount !== null) {
            $this->amount = $amount;
        }
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setCost($cost)
    {
        if ($cost !== null) {
            $this->cost = $cost;
        }
    }

    public function getCost()
    {
        return $this->cost;
    }

    public function setVatPercent($vat_percent)
    {
        if ($vat_percent !== null) {
            $this->vat_percent = $vat_percent;
        }
    }

    public function getVatPercent()
    {
        return $this->vat_percent;
    }

    public function setVat($vat)
    {
        if ($vat !== null) {
            $this->vat = $vat;
        }
    }

    public function getVat()
    {
        return $this->vat;
    }

    public function setRate($rate)
    {
        if ($rate !== null) {
            $this->rate = $rate;
        }
    }

    public function getRate()
    {
        return $this->rate;
    }

    public function setRateToHkd($rate_to_hkd)
    {
        if ($rate_to_hkd !== null) {
            $this->rate_to_hkd = $rate_to_hkd;
        }
    }

    public function getRateToHkd()
    {
        return $this->rate_to_hkd;
    }

    public function setRef1($ref_1)
    {
        if ($ref_1 !== null) {
            $this->ref_1 = $ref_1;
        }
    }

    public function getRef1()
    {
        return $this->ref_1;
    }

    public function setDeliveryCharge($delivery_charge)
    {
        if ($delivery_charge !== null) {
            $this->delivery_charge = $delivery_charge;
        }
    }

    public function getDeliveryCharge()
    {
        return $this->delivery_charge;
    }

    public function setDeclaredValue($declared_value)
    {
        if ($declared_value !== null) {
            $this->declared_value = $declared_value;
        }
    }

    public function getDeclaredValue()
    {
        return $this->declared_value;
    }

    public function setDeliveryTypeId($delivery_type_id)
    {
        if ($delivery_type_id !== null) {
            $this->delivery_type_id = $delivery_type_id;
        }
    }

    public function getDeliveryTypeId()
    {
        return $this->delivery_type_id;
    }

    public function setRecCourier($rec_courier)
    {
        if ($rec_courier !== null) {
            $this->rec_courier = $rec_courier;
        }
    }

    public function getRecCourier()
    {
        return $this->rec_courier;
    }

    public function setWeight($weight)
    {
        if ($weight !== null) {
            $this->weight = $weight;
        }
    }

    public function getWeight()
    {
        return $this->weight;
    }

    public function setOrderTotalItem($order_total_item)
    {
        if ($order_total_item !== null) {
            $this->order_total_item = $order_total_item;
        }
    }

    public function getOrderTotalItem()
    {
        return $this->order_total_item;
    }

    public function setCurrencyId($currency_id)
    {
        if ($currency_id !== null) {
            $this->currency_id = $currency_id;
        }
    }

    public function getCurrencyId()
    {
        return $this->currency_id;
    }

    public function setLangId($lang_id)
    {
        if ($lang_id !== null) {
            $this->lang_id = $lang_id;
        }
    }

    public function getLangId()
    {
        return $this->lang_id;
    }

    public function setBillName($bill_name)
    {
        if ($bill_name !== null) {
            $this->bill_name = $bill_name;
        }
    }

    public function getBillName()
    {
        return $this->bill_name;
    }

    public function setBillCompany($bill_company)
    {
        if ($bill_company !== null) {
            $this->bill_company = $bill_company;
        }
    }

    public function getBillCompany()
    {
        return $this->bill_company;
    }

    public function setBillAddress($bill_address)
    {
        if ($bill_address !== null) {
            $this->bill_address = $bill_address;
        }
    }

    public function getBillAddress()
    {
        return $this->bill_address;
    }

    public function setBillPostcode($bill_postcode)
    {
        if ($bill_postcode !== null) {
            $this->bill_postcode = $bill_postcode;
        }
    }

    public function getBillPostcode()
    {
        return $this->bill_postcode;
    }

    public function setBillCity($bill_city)
    {
        if ($bill_city !== null) {
            $this->bill_city = $bill_city;
        }
    }

    public function getBillCity()
    {
        return $this->bill_city;
    }

    public function setBillState($bill_state)
    {
        if ($bill_state !== null) {
            $this->bill_state = $bill_state;
        }
    }

    public function getBillState()
    {
        return $this->bill_state;
    }

    public function setBillCountryId($bill_country_id)
    {
        if ($bill_country_id !== null) {
            $this->bill_country_id = $bill_country_id;
        }
    }

    public function getBillCountryId()
    {
        return $this->bill_country_id;
    }

    public function setDeliveryName($delivery_name)
    {
        if ($delivery_name !== null) {
            $this->delivery_name = $delivery_name;
        }
    }

    public function getDeliveryName()
    {
        return $this->delivery_name;
    }

    public function setDeliveryCompany($delivery_company)
    {
        if ($delivery_company !== null) {
            $this->delivery_company = $delivery_company;
        }
    }

    public function getDeliveryCompany()
    {
        return $this->delivery_company;
    }

    public function setDeliveryAddress($delivery_address)
    {
        if ($delivery_address !== null) {
            $this->delivery_address = $delivery_address;
        }
    }

    public function getDeliveryAddress()
    {
        return $this->delivery_address;
    }

    public function setDeliveryPostcode($delivery_postcode)
    {
        if ($delivery_postcode !== null) {
            $this->delivery_postcode = $delivery_postcode;
        }
    }

    public function getDeliveryPostcode()
    {
        return $this->delivery_postcode;
    }

    public function setDeliveryCity($delivery_city)
    {
        if ($delivery_city !== null) {
            $this->delivery_city = $delivery_city;
        }
    }

    public function getDeliveryCity()
    {
        return $this->delivery_city;
    }

    public function setDeliveryState($delivery_state)
    {
        if ($delivery_state !== null) {
            $this->delivery_state = $delivery_state;
        }
    }

    public function getDeliveryState()
    {
        return $this->delivery_state;
    }

    public function setDeliveryCountryId($delivery_country_id)
    {
        if ($delivery_country_id !== null) {
            $this->delivery_country_id = $delivery_country_id;
        }
    }

    public function getDeliveryCountryId()
    {
        return $this->delivery_country_id;
    }

    public function setParentSoNo($parent_so_no)
    {
        if ($parent_so_no !== null) {
            $this->parent_so_no = $parent_so_no;
        }
    }

    public function getParentSoNo()
    {
        return $this->parent_so_no;
    }

    public function setStatus($status)
    {
        if ($status !== null) {
            $this->status = $status;
        }
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setRefundStatus($refund_status)
    {
        if ($refund_status !== null) {
            $this->refund_status = $refund_status;
        }
    }

    public function getRefundStatus()
    {
        return $this->refund_status;
    }

    public function setHoldStatus($hold_status)
    {
        if ($hold_status !== null) {
            $this->hold_status = $hold_status;
        }
    }

    public function getHoldStatus()
    {
        return $this->hold_status;
    }

    public function setHoldReason($hold_reason)
    {
        if ($hold_reason !== null) {
            $this->hold_reason = $hold_reason;
        }
    }

    public function getHoldReason()
    {
        return $this->hold_reason;
    }

    public function setRefundReason($refund_reason)
    {
        if ($refund_reason !== null) {
            $this->refund_reason = $refund_reason;
        }
    }

    public function getRefundReason()
    {
        return $this->refund_reason;
    }

    public function setOrderNote($order_note)
    {
        if ($order_note !== null) {
            $this->order_note = $order_note;
        }
    }

    public function getOrderNote()
    {
        return $this->order_note;
    }

    public function setPaymentGatewayId($payment_gateway_id)
    {
        if ($payment_gateway_id !== null) {
            $this->payment_gateway_id = $payment_gateway_id;
        }
    }

    public function getPaymentGatewayId()
    {
        return $this->payment_gateway_id;
    }

    public function setPromotionCode($promotion_code)
    {
        if ($promotion_code !== null) {
            $this->promotion_code = $promotion_code;
        }
    }

    public function getPromotionCode()
    {
        return $this->promotion_code;
    }

    public function getPromoDiscTotal() {
        return $this->promo_disc_total;
    }

    public function setPromoDiscTotal($promo_disc_total) {
        $this->promo_disc_total = $promo_disc_total;
    }

    public function setClientPromotionCode($client_promotion_code)
    {
        if ($client_promotion_code !== null) {
            $this->client_promotion_code = $client_promotion_code;
        }
    }

    public function getClientPromotionCode()
    {
        return $this->client_promotion_code;
    }

    public function setExpectDeliveryDate($expect_delivery_date)
    {
        if ($expect_delivery_date !== null) {
            $this->expect_delivery_date = $expect_delivery_date;
        }
    }

    public function getExpectDeliveryDate()
    {
        return $this->expect_delivery_date;
    }

    public function setExpectShipDays($expect_ship_days)
    {
        if ($expect_ship_days !== null) {
            $this->expect_ship_days = $expect_ship_days;
        }
    }

    public function getExpectShipDays()
    {
        return $this->expect_ship_days;
    }

    public function setExpectDelDays($expect_del_days)
    {
        if ($expect_del_days !== null) {
            $this->expect_del_days = $expect_del_days;
        }
    }

    public function getExpectDelDays()
    {
        return $this->expect_del_days;
    }

    public function setOrderCreateDate($order_create_date)
    {
        if ($order_create_date !== null) {
            $this->order_create_date = $order_create_date;
        }
    }

    public function getOrderCreateDate()
    {
        return $this->order_create_date;
    }

    public function setDispatchDate($dispatch_date)
    {
        if ($dispatch_date !== null) {
            $this->dispatch_date = $dispatch_date;
        }
    }

    public function getDispatchDate()
    {
        return $this->dispatch_date;
    }

    public function setFingerprintId($fingerprint_id)
    {
        if ($fingerprint_id !== null) {
            $this->fingerprint_id = $fingerprint_id;
        }
    }

    public function getFingerprintId()
    {
        return $this->fingerprint_id;
    }

    public function setCcReminderScheduleDate($cc_reminder_schedule_date)
    {
        if ($cc_reminder_schedule_date !== null) {
            $this->cc_reminder_schedule_date = $cc_reminder_schedule_date;
        }
    }

    public function getCcReminderScheduleDate()
    {
        return $this->cc_reminder_schedule_date;
    }

    public function setCcReminderType($cc_reminder_type)
    {
        if ($cc_reminder_type !== null) {
            $this->cc_reminder_type = $cc_reminder_type;
        }
    }

    public function getCcReminderType()
    {
        return $this->cc_reminder_type;
    }

    public function setCsCustomerQuery($cs_customer_query)
    {
        if ($cs_customer_query !== null) {
            $this->cs_customer_query = $cs_customer_query;
        }
    }

    public function getCsCustomerQuery()
    {
        return $this->cs_customer_query;
    }

    public function setSplitStatus($split_status)
    {
        if ($split_status !== null) {
            $this->split_status = $split_status;
        }
    }

    public function getSplitStatus()
    {
        return $this->split_status;
    }

    public function setSplitCreateOn($split_create_on)
    {
        if ($split_create_on !== null) {
            $this->split_create_on = $split_create_on;
        }
    }

    public function getSplitCreateOn()
    {
        return $this->split_create_on;
    }

    public function setSplitCreateBy($split_create_by)
    {
        if ($split_create_by !== null) {
            $this->split_create_by = $split_create_by;
        }
    }

    public function getSplitCreateBy()
    {
        return $this->split_create_by;
    }

    public function setSplitSoGroup($split_so_group)
    {
        if ($split_so_group !== null) {
            $this->split_so_group = $split_so_group;
        }
    }

    public function getSplitSoGroup()
    {
        return $this->split_so_group;
    }

}
