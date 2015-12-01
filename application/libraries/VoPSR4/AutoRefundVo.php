<?php
class AutoRefundVo extends \BaseVo
{
    private $refund_id;
    private $so_no;
    private $payment_gateway_id;
    private $action;
    private $amount;
    private $log_out;
    private $log_in;
    private $create_on = '0000-00-00 00:00:00';
    private $create_at = '2130706433';
    private $create_by = 'system';
    private $modify_on = '';
    private $modify_at = '2130706433';
    private $modify_by = 'system';

    private $primary_key = ['refund_id'];
    private $increment_field = '';

    public function setRefundId($refund_id)
    {
        if ($refund_id != null) {
            $this->refund_id = $refund_id;
        }
    }

    public function getRefundId()
    {
        return $this->refund_id;
    }

    public function setSoNo($so_no)
    {
        if ($so_no != null) {
            $this->so_no = $so_no;
        }
    }

    public function getSoNo()
    {
        return $this->so_no;
    }

    public function setPaymentGatewayId($payment_gateway_id)
    {
        if ($payment_gateway_id != null) {
            $this->payment_gateway_id = $payment_gateway_id;
        }
    }

    public function getPaymentGatewayId()
    {
        return $this->payment_gateway_id;
    }

    public function setAction($action)
    {
        if ($action != null) {
            $this->action = $action;
        }
    }

    public function getAction()
    {
        return $this->action;
    }

    public function setAmount($amount)
    {
        if ($amount != null) {
            $this->amount = $amount;
        }
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setLogOut($log_out)
    {
        if ($log_out != null) {
            $this->log_out = $log_out;
        }
    }

    public function getLogOut()
    {
        return $this->log_out;
    }

    public function setLogIn($log_in)
    {
        if ($log_in != null) {
            $this->log_in = $log_in;
        }
    }

    public function getLogIn()
    {
        return $this->log_in;
    }

    public function setCreateOn($create_on)
    {
        if ($create_on != null) {
            $this->create_on = $create_on;
        }
    }

    public function getCreateOn()
    {
        return $this->create_on;
    }

    public function setCreateAt($create_at)
    {
        if ($create_at != null) {
            $this->create_at = $create_at;
        }
    }

    public function getCreateAt()
    {
        return $this->create_at;
    }

    public function setCreateBy($create_by)
    {
        if ($create_by != null) {
            $this->create_by = $create_by;
        }
    }

    public function getCreateBy()
    {
        return $this->create_by;
    }

    public function setModifyOn($modify_on)
    {
        if ($modify_on != null) {
            $this->modify_on = $modify_on;
        }
    }

    public function getModifyOn()
    {
        return $this->modify_on;
    }

    public function setModifyAt($modify_at)
    {
        if ($modify_at != null) {
            $this->modify_at = $modify_at;
        }
    }

    public function getModifyAt()
    {
        return $this->modify_at;
    }

    public function setModifyBy($modify_by)
    {
        if ($modify_by != null) {
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
