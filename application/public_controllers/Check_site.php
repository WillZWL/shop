<?php

class Check_site extends PUB_Controller
{
    public function Check_site()
    {
        DEFINE("SKIPCUR", 1);
        parent::PUB_Controller();
        $this->load->helper('url');
    }

    public function index()
    {
        echo "HTTP and PHP RUNNING.";
        include_once(APPPATH . "libraries/service/exchange_rate_service.php");
        $er_srv = new Exchange_rate_service();
        if ($er_srv->exchange_rate_dao->get(array("from_currency_id" => "GBP", "to_currency_id" => "GBP"))) {
            echo "MySQL response OK.";
        } else {
            echo "mysql query failed: " . $er_srv->get_dao()->db->_error_message();
        }
    }

}
