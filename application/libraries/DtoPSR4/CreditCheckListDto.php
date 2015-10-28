<?php
class CreditCheckListDto
{
    private $sohr_reason;
    private $so_no;
    private $sor_obj;
    private $id;
    private $payment_status;
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
    private $tel_1;
    private $tel_2;
    private $tel_3;
    private $del_tel_1;
    private $del_tel_2;
    private $del_tel_3;
    private $status;
    private $refund_status;
    private $hold_status;
    private $expect_delivery_date;
    private $t_3m_is_sent;
    private $t_3m_in_file;
    private $t_3m_result;
    private $fd_status;
    private $create_on;
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;
    private $items;
    private $forename;
    private $surname;
    private $email;
    private $password;
    private $pw_count;
    private $reason;
    private $hold_date;
    private $delivery_type_id;
    private $card_type;

    public function setSohrReason($sohr_reason)
    {
        $this->sohr_reason = $sohr_reason;
    }

    public function getSohrReason()
    {
        return $this->sohr_reason;
    }

    public function setSoNo($so_no)
    {
        $this->so_no = $so_no;
    }

    public function getSoNo()
    {
        return $this->so_no;
    }

    public function setSorObj($sor_obj)
    {
        $this->sor_obj = $sor_obj;
    }

    public function getSorObj()
    {
        return $this->sor_obj;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setPaymentStatus($payment_status)
    {
        $this->payment_status = $payment_status;
    }

    public function getPaymentStatus()
    {
        return $this->payment_status;
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

    public function setDelTel1($del_tel_1)
    {
        $this->del_tel_1 = $del_tel_1;
    }

    public function getDelTel1()
    {
        return $this->del_tel_1;
    }

    public function setDelTel2($del_tel_2)
    {
        $this->del_tel_2 = $del_tel_2;
    }

    public function getDelTel2()
    {
        return $this->del_tel_2;
    }

    public function setDelTel3($del_tel_3)
    {
        $this->del_tel_3 = $del_tel_3;
    }

    public function getDelTel3()
    {
        return $this->del_tel_3;
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

    public function setExpectDeliveryDate($expect_delivery_date)
    {
        $this->expect_delivery_date = $expect_delivery_date;
    }

    public function getExpectDeliveryDate()
    {
        return $this->expect_delivery_date;
    }

    public function setT3mIsSent($t_3m_is_sent)
    {
        $this->t_3m_is_sent = $t_3m_is_sent;
    }

    public function getT3mIsSent()
    {
        return $this->t_3m_is_sent;
    }

    public function setT3mInFile($t_3m_in_file)
    {
        $this->t_3m_in_file = $t_3m_in_file;
    }

    public function getT3mInFile()
    {
        return $this->t_3m_in_file;
    }

    public function setT3mResult($t_3m_result)
    {
        $this->t_3m_result = $t_3m_result;
    }

    public function getT3mResult()
    {
        return $this->t_3m_result;
    }

    public function setFdStatus($fd_status)
    {
        $this->fd_status = $fd_status;
    }

    public function getFdStatus()
    {
        return $this->fd_status;
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

    public function setPwCount($pw_count)
    {
        $this->pw_count = $pw_count;
    }

    public function getPwCount()
    {
        return $this->pw_count;
    }

    public function setReason($reason)
    {
        $this->reason = $reason;
    }

    public function getReason()
    {
        return $this->reason;
    }

    public function setHoldDate($hold_date)
    {
        $this->hold_date = $hold_date;
    }

    public function getHoldDate()
    {
        return $this->hold_date;
    }

    public function setDeliveryTypeId($delivery_type_id)
    {
        $this->delivery_type_id = $delivery_type_id;
    }

    public function getDeliveryTypeId()
    {
        return $this->delivery_type_id;
    }

    public function setCardType($card_type)
    {
        $this->card_type = $card_type;
    }

    public function getCardType()
    {
        return $this->card_type;
    }

}
