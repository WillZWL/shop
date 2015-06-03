<?php
class Aftership_report extends MY_Controller
{
	private $app_id = "RPT0033";
	private $lang_id = "en";

	public function __construct()
	{
		parent::__construct();
		$this->load->model('report/aftership_report_model');
		$this->load->helper(array('url','notice'));
		$this->load->library('input');
		$this->load->library('service/context_config_service');
	}

	private function _load_parent_lang()
	{
		$sub_app_id = $this->_get_app_id()."00";
		include_once(APPPATH."language/".$sub_app_id."_".$this->_get_lang_id().".php");

		return $lang;
	}

	public function index()
	{
		include_once APPPATH.'/language/'.$this->_get_app_id().'00_'.$this->_get_lang_id().'.php';
		$data["lang"] = $lang;
		$data["title"] = "Voucher Report";
		$data["start_date"] = $data["end_date"] = date('Y-m-d');
		if($this->input->post('is_query'))
		{
			$data["posted"] = 1;
			$data["start_date"] = $this->input->post('start_date');
			$data["end_date"] = $this->input->post('end_date');
			$data["display_type"] = $this->input->post('display_type');
		}

		$this->load->view('report/aftership',$data);

	}

	public function export_csv($start_date, $end_date)
	{
		$where = array();
		$data['lang'] = $this->_load_parent_lang();
		$data['output'] = $this->aftership_report_model->get_data($start_date, $end_date, $where);

		$data['filename'] = 'aftership_report_'.$start_date.'-'.$end_date.'.csv';

		$this->load->view('output_csv.php', $data);
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