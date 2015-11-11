<?php
class QuickSearchResultDto
{
    private $amount;
    private $cost;
    private $txn_id;
    private $biz_type;
    private $currency_id;
    private $so_no;
    private $platform_order_id;
    private $name;
    private $dispatch_date;
    private $platform_id;
    private $client_id;
    private $status;
    private $hold_status;
    private $refund_status;
    private $tracking_no;
    private $expect_delivery_date;
    private $password;
    private $title;
    private $payment_gateway_id;
    private $payment_gateway_name;
    private $forename;
    private $surname;
    private $items;
    private $tel;
    private $delivery_charge;
    private $delivery_name;
    private $warehouse;
    private $packed_on;
    private $delivery_mode;
    private $shipped_on;
    private $order_create_date;
    private $email;
    private $bt_total_received;
    private $bt_total_bank_charge;
    private $ext_client_id;
    private $expect_ship_days;
    private $expect_del_days;
    private $fulfilled;
    private $cs_customer_query;
    private $split_so_group;

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

    public function setTxnId($txn_id)
    {
        $this->txn_id = $txn_id;
    }

    public function getTxnId()
    {
        return $this->txn_id;
    }

    public function setBizType($biz_type)
    {
        $this->biz_type = $biz_type;
    }

    public function getBizType()
    {
        return $this->biz_type;
    }

    public function setCurrencyId($currency_id)
    {
        $this->currency_id = $currency_id;
    }

    public function getCurrencyId()
    {
        return $this->currency_id;
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

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setDispatchDate($dispatch_date)
    {
        $this->dispatch_date = $dispatch_date;
    }

    public function getDispatchDate()
    {
        return $this->dispatch_date;
    }

    public function setPlatformId($platform_id)
    {
        $this->platform_id = $platform_id;
    }

    public function getPlatformId()
    {
        return $this->platform_id;
    }

    public function setClientId($client_id)
    {
        $this->client_id = $client_id;
    }

    public function getClientId()
    {
        return $this->client_id;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setHoldStatus($hold_status)
    {
        $this->hold_status = $hold_status;
    }

    public function getHoldStatus()
    {
        return $this->hold_status;
    }

    public function setRefundStatus($refund_status)
    {
        $this->refund_status = $refund_status;
    }

    public function getRefundStatus()
    {
        return $this->refund_status;
    }

    public function setTrackingNo($tracking_no)
    {
        $this->tracking_no = $tracking_no;
    }

    public function getTrackingNo()
    {
        return $this->tracking_no;
    }

    public function setExpectDeliveryDate($expect_delivery_date)
    {
        $this->expect_delivery_date = $expect_delivery_date;
    }

    public function getExpectDeliveryDate()
    {
        return $this->expect_delivery_date;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setPaymentGatewayId($payment_gateway_id)
    {
        $this->payment_gateway_id = $payment_gateway_id;
    }

    public function getPaymentGatewayId()
    {
        return $this->payment_gateway_id;
    }

    public function setPaymentGatewayName($payment_gateway_name)
    {
        $this->payment_gateway_name = $payment_gateway_name;
    }

    public function getPaymentGatewayName()
    {
        return $this->payment_gateway_name;
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

    public function setItems($items)
    {
        $this->items = $items;
    }

    public function getItems()
    {
        return $this->items;
    }

    public function setTel($tel)
    {
        $this->tel = $tel;
    }

    public function getTel()
    {
        return $this->tel;
    }

    public function setDeliveryCharge($delivery_charge)
    {
        $this->delivery_charge = $delivery_charge;
    }

    public function getDeliveryCharge()
    {
        return $this->delivery_charge;
    }

    public function setDeliveryName($delivery_name)
    {
        $this->delivery_name = $delivery_name;
    }

    public function getDeliveryName()
    {
        return $this->delivery_name;
    }

    public function setWarehouse($warehouse)
    {
        $this->warehouse = $warehouse;
    }

    public function getWarehouse()
    {
        return $this->warehouse;
    }

    public function setPackedOn($packed_on)
    {
        $this->packed_on = $packed_on;
    }

    public function getPackedOn()
    {
        return $this->packed_on;
    }

    public function setDeliveryMode($delivery_mode)
    {
        $this->delivery_mode = $delivery_mode;
    }

    public function getDeliveryMode()
    {
        return $this->delivery_mode;
    }

    public function setShippedOn($shipped_on)
    {
        $this->shipped_on = $shipped_on;
    }

    public function getShippedOn()
    {
        return $this->shipped_on;
    }

    public function setOrderCreateDate($order_create_date)
    {
        $this->order_create_date = $order_create_date;
    }

    public function getOrderCreateDate()
    {
        return $this->order_create_date;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setBtTotalReceived($bt_total_received)
    {
        $this->bt_total_received = $bt_total_received;
    }

    public function getBtTotalReceived()
    {
        return $this->bt_total_received;
    }

    public function setBtTotalBankCharge($bt_total_bank_charge)
    {
        $this->bt_total_bank_charge = $bt_total_bank_charge;
    }

    public function getBtTotalBankCharge()
    {
        return $this->bt_total_bank_charge;
    }

    public function setExtClientId($ext_client_id)
    {
        $this->ext_client_id = $ext_client_id;
    }

    public function getExtClientId()
    {
        return $this->ext_client_id;
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

    public function setFulfilled($fulfilled)
    {
        $this->fulfilled = $fulfilled;
    }

    public function getFulfilled()
    {
        return $this->fulfilled;
    }

    public function setCsCustomerQuery($cs_customer_query)
    {
        $this->cs_customer_query = $cs_customer_query;
    }

    public function getCsCustomerQuery()
    {
        return $this->cs_customer_query;
    }

    public function setSplitSoGroup($split_so_group)
    {
        $this->split_so_group = $split_so_group;
    }

    public function getSplitSoGroup()
    {
        return $this->split_so_group;
    }

}
