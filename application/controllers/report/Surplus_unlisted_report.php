<?php
DEFINE("PLATFORM_TYPE", "WEBSITE");

class Surplus_unlisted_report extends MY_Controller
{
    private $app_id = "RPT0043";
    private $lang_id = "en";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('report/surplus_report_model');
    }

    public function get_report($format = 'xml', $echo = 1)
    {
        // return $this->surplus_report_model->get_unlisted_surplus($format);

        $list = $this->surplus_report_model->get_unlisted_surplus($format);
        if ($echo) {
            if ($format == 'xml') {
                header("Content-Type: application/xml");
                echo $list;
            } elseif ($format == 'csv') {
                header("Cache-Control: no-store, no-cache");
                header("Content-Disposition: attachment; filename=\"unlisted_surplus_report_{$list["timestamp"]}.csv\"");
                echo $list["csv"];
            }
        } else {
            return $list;
        }

    }

    public function _get_app_id()
    {
        return $this->app_id;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }
}
