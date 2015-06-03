<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_comparison_by_period_report extends MY_Controller
{
	private $app_id="RPT0014";
	private $lang_id="en";
	private $model;
	private $export_filename;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('report/sales_comparison_by_period_report_model');
		$this->load->helper(array('url'));
		$this->load->library('service/context_config_service');
		$this->_set_model($this->sales_comparison_by_period_report_model);
		$this->_set_export_filename('sales_comparison_by_period_report.xls');
	}

	private function _load_parent_lang()
	{
		$sub_app_id = $this->_get_app_id()."00";
		include_once(APPPATH."language/".$sub_app_id."_".$this->_get_lang_id().".php");

		return $lang;
	}

	public function query()
	{
		if($_POST["post"])
		{
			$from_date1 = $_POST["from_date1"];
			$to_date1 = $_POST["to_date1"];
			$from_date2 = $_POST["from_date2"];
			$to_date2 = $_POST["to_date2"];
			//$data['lang'] = $this->_load_parent_lang();
			$data['output'] = $this->_get_model()->get_xls($from_date1, $to_date1,
				$from_date2, $to_date2);
			$data['filename'] = $this->_get_export_filename();
			$this->load->view('output_csv.php', $data);
		}
	}

	public function index()
	{
		$data['lang'] = $this->_load_parent_lang();
		$data['controller'] = strtolower(get_class($this));
		$data["start_date"] = "2010-09-01";
		$data["end_date"] = date('Y-m-d');
		$this->load->view('report/sales_comparison_by_period_report', $data);
	}

	public function _set_app_id($value)
	{
		$this->app_id = $value;
	}

	public function _get_app_id()
	{
		return $this->app_id;
	}

	public function _get_lang_id()
	{
		return $this->lang_id;
	}

	public function _set_model($value)
	{
		$this->model = $value;
	}

	public function _get_model()
	{
		return $this->model;
	}

	public function _set_export_filename($value)
	{
		$this->export_filename = $value;
	}

	public function _get_export_filename()
	{
		return $this->export_filename;
	}
}

/* End of file skype_report.php */
/* Location: ./system/application/controllers/report/inventory/skype_report.php */