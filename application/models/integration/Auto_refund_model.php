<?php
include_once(BASEPATH . "libraries/Model.php");

class Auto_refund_model extends CI_Model
{
    public $gateway_id = "";

    public function __construct($gateway, $debug = 0)
    {
        parent::__construct();
        include_once(APPPATH."libraries/service/auto_refund_service.php");
        include_once(APPPATH . "libraries/service/payment_gateway_redirect_yandex_service.php");

        $this->auto_refund_service = new Auto_refund_service();

        if ($gateway == "yandex")
        {
            $this->gateway_service = new Payment_gateway_redirect_yandex_service($this->debug);
            $this->gateway_id = $gateway;
        }
    }

    public function refund_orders($so_no = null)
    {
        if ($this->gateway_id == "")
            return;

        $where = array();
        $where["payment_gateway_id"] = $this->gateway_id;
        $where["action in ('R', 'I')"] = null;

        if ($so_no != null)
        {
            $where["so_no"] = $so_no;
        }

        $autoRefundList = $this->auto_refund_service->get_list($where, array("limit" => -1));
        foreach($autoRefundList as $refund)
        {
            $requestOut = null;
            $requestIn = null;
            $result = $this->gateway_service->refund_order($refund, $autoRefundObj, $requestOut, $requestIn);
            if ($autoRefundObj !== FALSE)
            {
                if ($requestOut != null)
                {
                    $previousLog = $autoRefundObj->get_log_out() . "\r\n" . date("Y-m-d H:i:s") . "\r\n";
                    $autoRefundObj->set_log_out($previousLog . $requestOut);
                }
                if ($requestIn != null)
                {
                    $previousLog = $autoRefundObj->get_log_in() . "\r\n" . date("Y-m-d H:i:s") . "\r\n";
                    $autoRefundObj->set_log_in($previousLog . $requestIn);
                }

                if ($result == Payment_gateway_redirect_service::REFUND_STATUS_SUCCESS)
                {
                    $autoRefundObj->set_action("C");
                }
                elseif ($result == Payment_gateway_redirect_service::REFUND_STATUS_REQUIRE_RETRY)
                {
                    $autoRefundObj->set_action("I");
                }
                else
                {
                    $message = "In:" . $requestIn . " Out:". $requestOut;
                    mail($this->gateway_service->get_technical_support_email(), '[VB] ' . $this->gateway_service->get_payment_gateway_name() . ' Auto Refund Error:' . $autoRefundObj->get_so_no(), $message, 'From: website@valuebasket.com');
                    $autoRefundObj->set_action("IT");
                }
                $this->auto_refund_service->get_dao()->update($autoRefundObj);
            }
        }
    }
}
