<?php
include_once 'Base_vo.php';

class So_payment_status_vo extends Base_vo
{

    //class variable
    private $so_no;
    private $payment_gateway_id;
    private $pay_to_account;
    private $card_id;
    private $payment_status = 'N';
    private $remark;
    private $mac_token;
    private $retry;
    private $payer_email;
    private $payer_ref;
    private $risk_ref1;
    private $risk_ref2;
    private $risk_ref3;
    private $risk_ref4;
    private $pay_date;
    private $pending_action;
    private $create_on = '0000-00-00 00:00:00';
    private $create_at = '127.0.0.1';
    private $create_by;
    private $modify_on;
    private $modify_at = '127.0.0.1';
    private $modify_by;

    //primary key
    private $primary_key = array("so_no");

    //auo increment
    private $increment_field = "";

    //instance method
    public function get_so_no()
    {
        return $this->so_no;
    }

    public function set_so_no($value)
    {
        $this->so_no = $value;
        return $this;
    }

    public function get_payment_gateway_id()
    {
        return $this->payment_gateway_id;
    }

    public function set_payment_gateway_id($value)
    {
        $this->payment_gateway_id = $value;
        return $this;
    }

    public function get_pay_to_account()
    {
        return $this->pay_to_account;
    }

    public function set_pay_to_account($value)
    {
        $this->pay_to_account = $value;
        return $this;
    }

    public function get_card_id()
    {
        return $this->card_id;
    }

    public function set_card_id($value)
    {
        $this->card_id = $value;
        return $this;
    }

    public function get_payment_status()
    {
        return $this->payment_status;
    }

    public function set_payment_status($value)
    {
        $this->payment_status = $value;
        return $this;
    }

    public function get_remark()
    {
        return $this->remark;
    }

    public function set_remark($value)
    {
        $this->remark = $value;
        return $this;
    }

    public function get_mac_token()
    {
        return $this->mac_token;
    }

    public function set_mac_token($value)
    {
        $this->mac_token = $value;
        return $this;
    }

    public function get_retry()
    {
        return $this->retry;
    }

    public function set_retry($value)
    {
        $this->retry = $value;
        return $this;
    }

    public function get_payer_email()
    {
        return $this->payer_email;
    }

    public function set_payer_email($value)
    {
        $this->payer_email = $value;
        return $this;
    }

    public function get_payer_ref()
    {
        return $this->payer_ref;
    }

    public function set_payer_ref($value)
    {
        $this->payer_ref = $value;
        return $this;
    }

    public function get_risk_ref1()
    {
        return $this->risk_ref1;
    }

    public function set_risk_ref1($value)
    {
        $this->risk_ref1 = $value;
        return $this;
    }

    public function get_risk_ref2()
    {
        return $this->risk_ref2;
    }

    public function set_risk_ref2($value)
    {
        $this->risk_ref2 = $value;
        return $this;
    }

    public function get_risk_ref3()
    {
        return $this->risk_ref3;
    }

    public function set_risk_ref3($value)
    {
        $this->risk_ref3 = $value;
        return $this;
    }

    public function get_risk_ref4()
    {
        return $this->risk_ref4;
    }

    public function set_risk_ref4($value)
    {
        $this->risk_ref4 = $value;
        return $this;
    }

    public function get_pay_date()
    {
        return $this->pay_date;
    }

    public function set_pay_date($value)
    {
        $this->pay_date = $value;
        return $this;
    }

    public function get_pending_action()
    {
        return $this->pending_action;
    }

    public function set_pending_action($value)
    {
        $this->pending_action = $value;
        return $this;
    }

    public function get_create_on()
    {
        return $this->create_on;
    }

    public function set_create_on($value)
    {
        $this->create_on = $value;
        return $this;
    }

    public function get_create_at()
    {
        return $this->create_at;
    }

    public function set_create_at($value)
    {
        $this->create_at = $value;
        return $this;
    }

    public function get_create_by()
    {
        return $this->create_by;
    }

    public function set_create_by($value)
    {
        $this->create_by = $value;
        return $this;
    }

    public function get_modify_on()
    {
        return $this->modify_on;
    }

    public function set_modify_on($value)
    {
        $this->modify_on = $value;
        return $this;
    }

    public function get_modify_at()
    {
        return $this->modify_at;
    }

    public function set_modify_at($value)
    {
        $this->modify_at = $value;
        return $this;
    }

    public function get_modify_by()
    {
        return $this->modify_by;
    }

    public function set_modify_by($value)
    {
        $this->modify_by = $value;
        return $this;
    }

    public function _get_primary_key()
    {
        return $this->primary_key;
    }

    public function _get_increment_field()
    {
        return $this->increment_field;
    }

}

?>