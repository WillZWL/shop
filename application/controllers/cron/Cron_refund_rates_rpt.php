<?php

class Cron_refund_rates_rpt extends MY_Controller
{
    private $app_id = "CRN0010";

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

    public function _get_app_id()
    {
        return $this->app_id;
    }
}

/* End of file Cron_refund_rates_rpt.php */
/* Location: ./app/controllers/cron/cron_refund_rates_rpt.php */
