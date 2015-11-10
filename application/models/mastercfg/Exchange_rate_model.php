<?php

class Exchange_rate_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/pagination_service');
        $this->load->library('service/exchange_rate_service');
        $this->load->library('service/currency_service');
    }

    public function alter_exchange_rate($from, $to, $rate, $platform = "")
    {
        if ($platform == "approval") {
            $dao = "exchange_rate_approval_dao";
        } else {
            $dao = "exchange_rate_dao";
        }
        return $this->exchange_rate_service->alter_exchange_rate($from, $to, $rate, $dao);
    }

    public function get_based_rate($base, $currency_list, $platform = "")
    {
        if ($platform == "approval") {
            $dao = "exchange_rate_approval_dao";
        } else {
            $dao = "exchange_rate_dao";
        }
        return $this->exchange_rate_service->get_based_rate($base, $currency_list, $dao);
    }

    public function get_based_approval_rate($base, $currency_list)
    {
        return $this->exchange_rate_service->get_based_approval_rate($base, $currency_list);
    }

    public function get_currency_list($where = array(), $option = array())
    {
        return $this->exchange_rate_service->get_currency_list($where, $option);
    }

    public function get_active_currency_list($where = array(), $option = array())
    {
        return $this->exchange_rate_service->get_active_currency_list($where, $option);
    }

    public function get_active_currency_obj_list($where = array(), $option = array())
    {
        return $this->exchange_rate_service->get_active_currency_obj_list($where, $option);
    }

    public function get_currency_full_list($where = array(), $option = array())
    {
        return $this->currency_service->get_list($where, $option);
    }

    public function get_exchange_rate_approval_list($where = array(), $option = array())
    {
        return $this->exchange_rate_service->get_exchange_rate_approval_list($where, $option);
    }

    public function notification_email($sent_to, $value)
    {
        return $this->exchange_rate_service->notification_email($sent_to, $value);
    }

    public function get_sign($platform = "")
    {
        return $this->currency_service->get_sign($platform);
    }

    public function upload_exchange_rate()
    {
        return $this->exchange_rate_service->upload_exchange_rate();
    }

    public function update_exchange_rate_from_cv()
    {
        return $this->exchange_rate_service->update_exchange_rate_from_cv();
    }

    public function compare_difference($from = "", $to = "", $rate = "")
    {
        return $this->exchange_rate_service->compare_difference($from, $to, $rate);
    }

    public function currency_exchange($from_currency, $to_currency, $amount)
    {
        $exchange_rate = $this->get_exchange_rate($from_currency, $to_currency);
        return $exchange_rate->get_rate() * $amount;
    }

    public function get_exchange_rate($from = "", $to = "")
    {
        return $this->exchange_rate_service->get_exchange_rate($from, $to);
    }
}

?>