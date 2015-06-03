<?php
include_once "Base_report.php";
class Special_order_report extends Base_report
{
	private $app_id = "RPT0029";
	private $lang_id = "en";

	public function __construct()
	{
		parent::__construct();
		$this->load->model('report/special_order_report_model');
		$this->load->helper(array('url','notice'));
//		$this->load->library('input');
//		$this->load->library('service/context_config_service');
		$this->load->library('template');
		$this->template->set_template('report');
	}

	public function index()
	{
		$data["title"] = "Special Order Report";
//No content, use the template only, so no need to write into content region
//		$this->template->write_view("content", "report/refund_report", $data, TRUE);
		$this->template->write('_title', $data["title"]);
		$this->template->render();
	}

	public function export_csv()
	{
		if($this->input->post('is_query'))
		{
			$data["posted"] = 1;
			if($_POST["check"]["order_create"])
			{
				if($_POST["start_date"]["order_create"])
				{
					$where["so.order_create_date >="] = $_POST["start_date"]["order_create"] . " 00:00:00";
				}
				if($_POST["end_date"]["order_create"])
				{
					$where["so.order_create_date <="] = $_POST["end_date"]["order_create"] . " 23:59:59";
				}
			}

			$data['output'] = $this->special_order_report_model->get_csv($where);
			$data['filename'] = 'special_order_report.csv';
			$this->load->view('output_csv.php', $data);
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