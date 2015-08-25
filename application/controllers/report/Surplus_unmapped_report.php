<?php
DEFINE("PLATFORM_TYPE", "WEBSITE");

class Surplus_unmapped_report extends MY_Controller
{
    private $appId = "RPT0042";
    private $lang_id = "en";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('report/surplus_report_model');
    }

    public function get_report($format = 'xml', $echo = 1)
    {
        $list = $this->surplus_report_model->get_unmapped_surplus($format);
        if ($echo) {
            if ($format == 'xml') {
                header("Content-Type: application/xml");
                echo $list;
            } elseif ($format == 'csv') {
                header("Cache-Control: no-store, no-cache");
                header("Content-Disposition: attachment; filename=\"unmapped_surplus_report_{$list["timestamp"]}.csv\"");
                echo $list["csv"];
            }
        } else {
            return $list;
        }
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }
}
