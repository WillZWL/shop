<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Skype_report extends MY_Controller
{
    private $app_id = "RPT0012";
    private $lang_id = "en";
    private $model;
    private $export_filename;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('report/skype_report_model');
        $this->load->helper(array('url'));
        $this->load->library('service/context_config_service');
        $this->_set_model($this->skype_report_model);
        $this->_set_export_filename('skype_report.xls');
    }

    public function query()
    {
        if ($_POST["post"]) {
            $start_date = $_POST["start_date"];
            $end_date = $_POST["end_date"];
            if (trim($_POST["sku"]) != "") {
                if ($_POST["inclusion"]["sku"]) {
                    $where["sku"] = " IN ('";
                } else {
                    $where["sku"] = " NOT IN ('";
                }
                $where["sku"] .= implode("','", array_map('trim', explode(",", $_POST["sku"])));
                $where["sku"] .= "') ";
            }
            if (trim($_POST["conv_site_id"]) != "") {
                if ($_POST["inclusion"]["conv_site_id"]) {
                    $where["conv_site_id"] = " IN ('";
                } else {
                    $where["conv_site_id"] = " NOT IN ('";
                }
                $where["conv_site_id"] .= implode("','", array_map('trim', explode(",", $_POST["conv_site_id"])));
                $where["conv_site_id"] .= "') ";
            }
            if (trim($_POST["promotion_code"]) != "") {
                if ($_POST["inclusion"]["promotion_code"]) {
                    $where["promotion_code"] = " IN ('";
                } else {
                    $where["promotion_code"] = " NOT IN ('";
                }
                $where["promotion_code"] .= implode("','", array_map('trim', explode(",", $_POST["promotion_code"])));
                $where["promotion_code"] .= "') ";
            }

            //$data['lang'] = $this->_load_parent_lang();
            $data['output'] = $this->_get_model()->get_skype_report($start_date, $end_date, $where);
            $data['filename'] = $this->_get_export_filename();
            $this->load->view('output_csv.php', $data);
        }
    }

    public function _get_model()
    {
        return $this->model;
    }

    public function _set_model($value)
    {
        $this->model = $value;
    }

    public function _get_export_filename()
    {
        return $this->export_filename;
    }

    public function _set_export_filename($value)
    {
        $this->export_filename = $value;
    }

    public function index()
    {
        $data['lang'] = $this->_load_parent_lang();
        $data['controller'] = strtolower(get_class($this));
        $data["start_date"] = "2010-09-01";
        $data["end_date"] = date('Y-m-d');
        $this->load->view('report/skype_report', $data);
    }

    private function _load_parent_lang()
    {
        $sub_app_id = $this->_get_app_id() . "00";
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");

        return $lang;
    }

    public function _get_app_id()
    {
        return $this->app_id;
    }

    public function _set_app_id($value)
    {
        $this->app_id = $value;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }
}


