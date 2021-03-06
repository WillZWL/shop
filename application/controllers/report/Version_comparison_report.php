<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Version_comparison_report extends MY_Controller
{
    private $appId = "RPT0018";
    private $lang_id = "en";
    private $model;
    private $export_filename;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('report/version_comparison_report_model');
        $this->load->helper(array('url'));
        $this->load->library('service/context_config_service');
        $this->_set_export_filename('version_comparison_report.csv');
    }

    public function query()
    {
        $sub_app_id = $this->getAppId() . "00";
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
        $data['lang'] = $lang;
        if ($this->input->post('posted')) {
            $data['output'] = $this->version_comparison_report_model->get_csv();
            $data['filename'] = $this->_get_export_filename();

            $this->load->view('output_csv.php', $data);
        }
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
        $sub_app_id = $this->getAppId() . "00";
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
        $data['lang'] = $lang;

        $this->load->view('report/version_comparison_report', $data);
    }

    private function _load_parent_lang()
    {
        $sub_app_id = $this->getAppId() . "00";
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");

        return $lang;
    }

    public function getAppId()
    {
        return $this->appId;
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


