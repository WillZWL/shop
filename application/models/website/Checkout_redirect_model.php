<?php

//include_once(BASEPATH . "libraries/Model.php");
include_once(APPPATH . "models/website/checkout_model.php");

interface Checkout_redirect_interface
{
    public function set_pmgw_service();
//  public function process_payment_status($general_data = array(), $get_data = array());
}

abstract class Checkout_redirect_model extends Checkout_model implements Checkout_redirect_interface
{
    public $debug = 0;
    public $pmgw_redirect_service;
    private $_config;

    public function __construct($debug = 0)
    {
        parent::Checkout_model();
        include_once(APPPATH . "libraries/service/Context_config_service.php");
        $this->set_config(new Context_config_service());
        $system_debug_open = $this->get_config()->value_of("payment_debug_allow");
        if (($debug == 1) && ($system_debug_open))
            $this->debug = $debug;
        else
            $this->debug = 0;
        $this->set_pmgw_service($this->debug);
    }

    public function get_config()
    {
        return $this->_config;
    }

    public function set_config($value)
    {
        $this->_config = $value;
    }

    /***************************************
     *   we pass both post and get data, because in general
     *   we don't know exactly different payment gateway handling method
     *   general_data, normally will put $_POST
     *   get_data, normally will put $_GET
     ****************************************/
    public function process_payment_status_in_general($general_data = array(), $get_data = array())
    {
        return $this->pmgw_redirect_service->process_payment_status_in_general($general_data, $get_data);
    }

    public function update_pending_list()
    {
        $this->pmgw_redirect_service->update_pending_list();
    }

    public function query_transaction_in_general($so_no)
    {
        return $this->pmgw_redirect_service->query_payment_status_in_general($so_no);
    }
}

?>
