<?php
class SoPaymentStatusWithGatewayNameDto
{
    private $id;
    private $so_no;
    private $payment_gateway_id = '';
    private $payment_gateway_name;
    private $pay_to_account = '';
    private $card_id = '';
    private $payment_status = 'N';
    private $remark;
    private $mac_token = '';
    private $retry = '0';
    private $payer_email = '';
    private $payer_ref = '';
    private $risk_ref_1 = '';
    private $risk_ref_2 = '';
    private $risk_ref_3 = '';
    private $risk_ref_4 = '';
    private $pay_date = '0000-00-00 00:00:00';
    private $pending_action = '';
    private $create_on = '0000-00-00 00:00:00';
    private $create_at = '2130706433';
    private $create_by = 'system';
    private $modify_on = '';
    private $modify_at = '2130706433';
    private $modify_by = 'system';

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

    public function setPayToAccount($pay_to_account)
    {
        $this->pay_to_account = $pay_to_account;
    }

    public function getPayToAccount()
    {
        return $this->pay_to_account;
    }

    public function setCardId($card_id)
    {
        $this->card_id = $card_id;
    }

    public function getCardId()
    {
        return $this->card_id;
    }

    public function setPaymentStatus($payment_status)
    {
        $this->payment_status = $payment_status;
    }

    public function getPaymentStatus()
    {
        return $this->payment_status;
    }

    public function setRemark($remark)
    {
        $this->remark = $remark;
    }

    public function getRemark()
    {
        return $this->remark;
    }

    public function setMacToken($mac_token)
    {
        $this->mac_token = $mac_token;
    }

    public function getMacToken()
    {
        return $this->mac_token;
    }

    public function setRetry($retry)
    {
        $this->retry = $retry;
    }

    public function getRetry()
    {
        return $this->retry;
    }

    public function setPayerEmail($payer_email)
    {
        $this->payer_email = $payer_email;
    }

    public function getPayerEmail()
    {
        return $this->payer_email;
    }

    public function setPayerRef($payer_ref)
    {
        $this->payer_ref = $payer_ref;
    }

    public function getPayerRef()
    {
        return $this->payer_ref;
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

    public function setPayDate($pay_date)
    {
        $this->pay_date = $pay_date;
    }

    public function getPayDate()
    {
        return $this->pay_date;
    }

    public function setPendingAction($pending_action)
    {
        $this->pending_action = $pending_action;
    }

    public function getPendingAction()
    {
        return $this->pending_action;
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
