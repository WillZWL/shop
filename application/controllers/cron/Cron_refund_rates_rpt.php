<?php

class Cron_refund_rates_rpt extends MY_Controller
{
    private $appId = "CRN0010";

    function __construct()
    {
        parent::__construct();
        $this->load->model('report/compliance_refund_rates_rpt_model');
    }

    function send_report()
    {
        $week_of_data_to_show = 8;  // The number of week of data that the report need to show
        $this->compliance_refund_rates_rpt_model->send_report($week_of_data_to_show);
    }

    public function getAppId()
    {
        return $this->appId;
    }
}



