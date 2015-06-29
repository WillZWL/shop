<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Refund_amount_by_pmgw_currency_dto extends Base_dto
{
    //class variable
    private $refund_count;
    private $refund_amount;
    private $currency_id;
    private $payment_gateway_id;
    private $pmgw_name;
    private $refund_reason;
    private $platfrom_country_id;

    //instance method
    public function get_refund_count()
    {
        return $this->refund_count;
    }

    public function set_refund_count($value)
    {
        $this->refund_count = $value;
    }

    public function get_refund_amount()
    {
        return $this->refund_amount;
    }

    public function set_refund_amount($value)
    {
        $this->refund_amount = $value;
    }

    public function get_currency_id()
    {
        return $this->currency_id;
    }

    public function set_currency_id($value)
    {
        $this->currency_id = $value;
    }

    public function get_payment_gateway_id()
    {
        return $this->payment_gateway_id;
    }

    public function set_payment_gateway_id($value)
    {
        $this->payment_gateway_id = $value;
    }

    public function get_pmgw_name()
    {
        return $this->pmgw_name;
    }

    public function set_pmgw_name($value)
    {
        $this->pmgw_name = $value;
    }

    public function get_refund_reason()
    {
        return $this->refund_reason;
    }

    public function set_refund_reason($value)
    {
        $this->refund_reason = $value;
    }

    public function get_platform_country_id()
    {
        return $this->platform_country_id;
    }

    public function set_platform_country_id($value)
    {
        $this->platform_country_id = $value;
    }
}
/* End of file refund_amount_by_pmgw_currency_dto.php */
/* Location: ./system/application/libraries/dto/refund_amount_by_pmgw_currency_dto.php */