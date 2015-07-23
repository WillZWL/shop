<?php

class Cron_ebay extends MY_Controller
{
    private $app_id = "CRN0007";

    function __construct()
    {
        parent::__construct();
        $this->load->model('marketing/ebay_model');
    }

    public function add_items($debug = 0, $enable_log = 0)
    {
        if ($platform_list = $this->ebay_model->ebay_service->get_pbv_srv()->selling_platform_dao->get_list(array("type" => "EBAY", "status" => 1))) {
            $err_msg = "";
            foreach ($platform_list as $platform_obj) {
                if ($enable_log) {
                    echo date("Y-m-d H:i:s") . " - Processing AddItems Process on " . $platform_obj->get_id() . "<br>";
                }
                if ($rs = $this->ebay_model->ebay_service->add_items($platform_obj->get_id(), $debug, $enable_log)) {
                    $err_msg .= $platform_obj->get_id() . " Product Update Failed:\n";
                    $err_msg .= $rs;
                    $err_msg .= "\n\n\n";
                }
            }
            if (strlen(trim($err_msg)) > 0) {
                $this->send_error_email("[VB] Ebay Product Update Error", $err_msg);
            }
        }
    }

    public function send_error_email($subj, $msg)
    {
        $headers .= 'From: Admin <admin@valuebasket.net>' . "\r\n";
        $headers .= 'Cc: oswald-alert@eservicesgroup.com' . "\r\n";
        mail("bd.platformteam@eservicesgroup.net", $subj, $msg, $headers);
    }

    public function cron_send_feedback_email()
    {
        $this->ebay_model->ebay_service->cron_send_feedback_email();
    }

    public function cron_update_shipment_status()
    {
        $platform_arr = array("EBAYAU", "EBAYSG", "EBAYUS");
        foreach ($platform_arr as $platform_id) {
            $this->ebay_model->ebay_service->update_shipment_status($platform_id);
        }
    }

    public function _get_app_id()
    {
        return $this->app_id;
    }
}



