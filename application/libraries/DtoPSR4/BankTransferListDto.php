<?php
class BankTransferListDto
{
    private $so_no;
    private $id;
    private $sbt_status;
    private $net_diff_status;
    private $pending_action;
    private $risk_ref_1;
    private $risk_ref_2;
    private $risk_ref_3;
    private $risk_ref_4;
    private $risk_ref_desc;
    private $platform_order_id;
    private $platform_id;
    private $payment_gateway_id;
    private $txn_id;
    private $client_id;
    private $biz_type;
    private $amount;
    private $currency_id;
    private $order_create_date;
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
    private $tel__1;
    private $tel__2;
    private $tel__3;
    private $del_tel__1;
    private $del_tel__2;
    private $del_tel__3;
    private $status;
    private $hold_status;
    private $refund_status;
    private $reason;
    private $ext_ref_no;
    private $received_amt_localcurr;
    private $bank_account_id;
    private $bank_account_no;
    private $received_date_localtime;
    private $bank_charge;
    private $notes;
    private $so_create_on;
    private $so_create_at;
    private $so_create_by;
    private $so_modify_on;
    private $so_modify_at;
    private $so_modify_by;
    private $sbt_create_on;
    private $sbt_create_at;
    private $sbt_create_by;
    private $sbt_modify_on;
    private $sbt_modify_at;
    private $sbt_modify_by;
    private $items;
    private $forename;
    private $surname;
    private $del_name;
    private $email;
    private $password;
    private $delivery_type_id;

    public function setSoNo($so_no)
    {
        $this->so_no = $so_no;
    }

    public function getSoNo()
    {
        return $this->so_no;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setSbtStatus($sbt_status)
    {
        $this->sbt_status = $sbt_status;
    }

    public function getSbtStatus()
    {
        return $this->sbt_status;
    }

    public function setNetDiffStatus($net_diff_status)
    {
        $this->net_diff_status = $net_diff_status;
    }

    public function getNetDiffStatus()
    {
        return $this->net_diff_status;
    }

    public function setPendingAction($pending_action)
    {
        $this->pending_action = $pending_action;
    }

    public function getPendingAction()
    {
        return $this->pending_action;
    }

    public function setRiskRef1($risk_ref_1)
    {
        $this->risk_ref_1 = $risk_ref_1;
    }

    public function getRiskRef1()
    {
        return $this->risk_ref_1;
    }

    public function setRiskRef2($risk_ref_2)
    {
        $this->risk_ref_2 = $risk_ref_2;
    }

    public function getRiskRef2()
    {
        return $this->risk_ref_2;
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

    public function setRiskRefDesc($risk_ref_desc)
    {
        $this->risk_ref_desc = $risk_ref_desc;
    }

    public function getRiskRefDesc()
    {
        return $this->risk_ref_desc;
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

    public function setPaymentGatewayId($payment_gateway_id)
    {
        $this->payment_gateway_id = $payment_gateway_id;
    }

    public function getPaymentGatewayId()
    {
        return $this->payment_gateway_id;
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

    public function setCurrencyId($currency_id)
    {
        $this->currency_id = $currency_id;
    }

    public function getCurrencyId()
    {
        return $this->currency_id;
    }

    public function setOrderCreateDate($order_create_date)
    {
        $this->order_create_date = $order_create_date;
    }

    public function getOrderCreateDate()
    {
        return $this->order_create_date;
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

    public function setTel1($tel__1)
    {
        $this->tel__1 = $tel__1;
    }

    public function getTel1()
    {
        return $this->tel__1;
    }

    public function setTel2($tel__2)
    {
        $this->tel__2 = $tel__2;
    }

    public function getTel2()
    {
        return $this->tel__2;
    }

    public function setTel3($tel__3)
    {
        $this->tel__3 = $tel__3;
    }

    public function getTel3()
    {
        return $this->tel__3;
    }

    public function setDelTel1($del_tel__1)
    {
        $this->del_tel__1 = $del_tel__1;
    }

    public function getDelTel1()
    {
        return $this->del_tel__1;
    }

    public function setDelTel2($del_tel__2)
    {
        $this->del_tel__2 = $del_tel__2;
    }

    public function getDelTel2()
    {
        return $this->del_tel__2;
    }

    public function setDelTel3($del_tel__3)
    {
        $this->del_tel__3 = $del_tel__3;
    }

    public function getDelTel3()
    {
        return $this->del_tel__3;
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

    public function setReason($reason)
    {
        $this->reason = $reason;
    }

    public function getReason()
    {
        return $this->reason;
    }

    public function setExtRefNo($ext_ref_no)
    {
        $this->ext_ref_no = $ext_ref_no;
    }

    public function getExtRefNo()
    {
        return $this->ext_ref_no;
    }

    public function setReceivedAmtLocalcurr($received_amt_localcurr)
    {
        $this->received_amt_localcurr = $received_amt_localcurr;
    }

    public function getReceivedAmtLocalcurr()
    {
        return $this->received_amt_localcurr;
    }

    public function setBankAccountId($bank_account_id)
    {
        $this->bank_account_id = $bank_account_id;
    }

    public function getBankAccountId()
    {
        return $this->bank_account_id;
    }

    public function setBankAccountNo($bank_account_no)
    {
        $this->bank_account_no = $bank_account_no;
    }

    public function getBankAccountNo()
    {
        return $this->bank_account_no;
    }

    public function setReceivedDateLocaltime($received_date_localtime)
    {
        $this->received_date_localtime = $received_date_localtime;
    }

    public function getReceivedDateLocaltime()
    {
        return $this->received_date_localtime;
    }

    public function setBankCharge($bank_charge)
    {
        $this->bank_charge = $bank_charge;
    }

    public function getBankCharge()
    {
        return $this->bank_charge;
    }

    public function setNotes($notes)
    {
        $this->notes = $notes;
    }

    public function getNotes()
    {
        return $this->notes;
    }

    public function setSoCreateOn($so_create_on)
    {
        $this->so_create_on = $so_create_on;
    }

    public function getSoCreateOn()
    {
        return $this->so_create_on;
    }

    public function setSoCreateAt($so_create_at)
    {
        $this->so_create_at = $so_create_at;
    }

    public function getSoCreateAt()
    {
        return $this->so_create_at;
    }

    public function setSoCreateBy($so_create_by)
    {
        $this->so_create_by = $so_create_by;
    }

    public function getSoCreateBy()
    {
        return $this->so_create_by;
    }

    public function setSoModifyOn($so_modify_on)
    {
        $this->so_modify_on = $so_modify_on;
    }

    public function getSoModifyOn()
    {
        return $this->so_modify_on;
    }

    public function setSoModifyAt($so_modify_at)
    {
        $this->so_modify_at = $so_modify_at;
    }

    public function getSoModifyAt()
    {
        return $this->so_modify_at;
    }

    public function setSoModifyBy($so_modify_by)
    {
        $this->so_modify_by = $so_modify_by;
    }

    public function getSoModifyBy()
    {
        return $this->so_modify_by;
    }

    public function setSbtCreateOn($sbt_create_on)
    {
        $this->sbt_create_on = $sbt_create_on;
    }

    public function getSbtCreateOn()
    {
        return $this->sbt_create_on;
    }

    public function setSbtCreateAt($sbt_create_at)
    {
        $this->sbt_create_at = $sbt_create_at;
    }

    public function getSbtCreateAt()
    {
        return $this->sbt_create_at;
    }

    public function setSbtCreateBy($sbt_create_by)
    {
        $this->sbt_create_by = $sbt_create_by;
    }

    public function getSbtCreateBy()
    {
        return $this->sbt_create_by;
    }

    public function setSbtModifyOn($sbt_modify_on)
    {
        $this->sbt_modify_on = $sbt_modify_on;
    }

    public function getSbtModifyOn()
    {
        return $this->sbt_modify_on;
    }

    public function setSbtModifyAt($sbt_modify_at)
    {
        $this->sbt_modify_at = $sbt_modify_at;
    }

    public function getSbtModifyAt()
    {
        return $this->sbt_modify_at;
    }

    public function setSbtModifyBy($sbt_modify_by)
    {
        $this->sbt_modify_by = $sbt_modify_by;
    }

    public function getSbtModifyBy()
    {
        return $this->sbt_modify_by;
    }

    public function setItems($items)
    {
        $this->items = $items;
    }

    public function getItems()
    {
        return $this->items;
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

    public function setDelName($del_name)
    {
        $this->del_name = $del_name;
    }

    public function getDelName()
    {
        return $this->del_name;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setDeliveryTypeId($delivery_type_id)
    {
        $this->delivery_type_id = $delivery_type_id;
    }

    public function getDeliveryTypeId()
    {
        return $this->delivery_type_id;
    }

}
