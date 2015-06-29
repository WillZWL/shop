<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron_affiliate_order extends MY_Controller
{
    private $app_id="CRN0022";

    function __construct()
    {
        parent::__construct();
        $this->load->model('order/affiliate_order_model');
    }

    public function kelkoo_report($country_id = "all", $day_diff=0)
    {
        # $country_id = ES, FR, IT, etc for a specific country, else it will take all affiliate-related orders
        # $day_diff: search filter for start date; how many days to start from current_time?
        $this->affiliate_order_model->kelkoo_report($country_id, $day_diff);
    }

    public function affiliate_delay_report($affiliate_prefix = "all", $day_diff=0)
    {
        $this->affiliate_order_model->affiliate_delay_report($affiliate_prefix, $day_diff);
    }


    public function _get_app_id()
    {
        return $this->app_id;
    }

}