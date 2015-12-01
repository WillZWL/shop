<?php
class SoPaymentStatusVo extends \BaseVo
{
    private $id;
    private $so_no;
    private $payment_gateway_id = '';
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

    private $primary_key = ['id'];
    private $increment_field = 'id';

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

    public function setPayToAccount($pay_to_account)
    {
        if ($pay_to_account !== null) {
            $this->pay_to_account = $pay_to_account;
        }
    }

    public function getPayToAccount()
    {
        return $this->pay_to_account;
    }

    public function setCardId($card_id)
    {
        if ($card_id !== null) {
            $this->card_id = $card_id;
        }
    }

    public function getCardId()
    {
        return $this->card_id;
    }

    public function setPaymentStatus($payment_status)
    {
        if ($payment_status !== null) {
            $this->payment_status = $payment_status;
        }
    }

    public function getPaymentStatus()
    {
        return $this->payment_status;
    }

    public function setRemark($remark)
    {
        if ($remark !== null) {
            $this->remark = $remark;
        }
    }

    public function getRemark()
    {
        return $this->remark;
    }

    public function setMacToken($mac_token)
    {
        if ($mac_token !== null) {
            $this->mac_token = $mac_token;
        }
    }

    public function getMacToken()
    {
        return $this->mac_token;
    }

    public function setRetry($retry)
    {
        if ($retry !== null) {
            $this->retry = $retry;
        }
    }

    public function getRetry()
    {
        return $this->retry;
    }

    public function setPayerEmail($payer_email)
    {
        if ($payer_email !== null) {
            $this->payer_email = $payer_email;
        }
    }

    public function getPayerEmail()
    {
        return $this->payer_email;
    }

    public function setPayerRef($payer_ref)
    {
        if ($payer_ref !== null) {
            $this->payer_ref = $payer_ref;
        }
    }

    public function getPayerRef()
    {
        return $this->payer_ref;
    }

    public function setRiskRef1($risk_ref_1)
    {
        if ($risk_ref_1 !== null) {
            $this->risk_ref_1 = $risk_ref_1;
        }
    }

    public function getRiskRef1()
    {
        return $this->risk_ref_1;
    }

    public function setRiskRef2($risk_ref_2)
    {
        if ($risk_ref_2 !== null) {
            $this->risk_ref_2 = $risk_ref_2;
        }
    }

    public function getRiskRef2()
    {
        return $this->risk_ref_2;
    }

    public function setRiskRef3($risk_ref_3)
    {
        if ($risk_ref_3 !== null) {
            $this->risk_ref_3 = $risk_ref_3;
        }
    }

    public function getRiskRef3()
    {
        return $this->risk_ref_3;
    }

    public function setRiskRef4($risk_ref_4)
    {
        if ($risk_ref_4 !== null) {
            $this->risk_ref_4 = $risk_ref_4;
        }
    }

    public function getRiskRef4()
    {
        return $this->risk_ref_4;
    }

    public function setPayDate($pay_date)
    {
        if ($pay_date !== null) {
            $this->pay_date = $pay_date;
        }
    }

    public function getPayDate()
    {
        return $this->pay_date;
    }

    public function setPendingAction($pending_action)
    {
        if ($pending_action !== null) {
            $this->pending_action = $pending_action;
        }
    }

    public function getPendingAction()
    {
        return $this->pending_action;
    }

    public function setCreateOn($create_on)
    {
        if ($create_on !== null) {
            $this->create_on = $create_on;
        }
    }

    public function getCreateOn()
    {
        return $this->create_on;
    }

    public function setCreateAt($create_at)
    {
        if ($create_at !== null) {
            $this->create_at = $create_at;
        }
    }

    public function getCreateAt()
    {
        return $this->create_at;
    }

    public function setCreateBy($create_by)
    {
        if ($create_by !== null) {
            $this->create_by = $create_by;
        }
    }

    public function getCreateBy()
    {
        return $this->create_by;
    }

    public function setModifyOn($modify_on)
    {
        if ($modify_on !== null) {
            $this->modify_on = $modify_on;
        }
    }

    public function getModifyOn()
    {
        return $this->modify_on;
    }

    public function setModifyAt($modify_at)
    {
        if ($modify_at !== null) {
            $this->modify_at = $modify_at;
        }
    }

    public function getModifyAt()
    {
        return $this->modify_at;
    }

    public function setModifyBy($modify_by)
    {
        if ($modify_by !== null) {
            $this->modify_by = $modify_by;
        }
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
