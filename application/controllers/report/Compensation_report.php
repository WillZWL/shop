<?php
include_once "base_report.php";
class Compensation_report extends Base_report
{
	private $app_id = "RPT0031";
	private $lang_id = "en";

	public function __construct()
	{
		parent::__construct();
		$this->load->model('report/compensation_report_model');
		$this->load->helper(array('url','notice'));
		$this->load->library('service/pagination_service');
		$this->load->library('template');
		$this->template->set_template('report');
	}

	public function index()
	{
		$data["title"] = "Compensation Report";

		$langfile = $this->_get_app_id()."01_".$this->_get_lang_id().".php";
		include_once APPPATH."language/".$langfile;
		$data["lang"] = $lang;


		if($_POST["display_report"] == 1 || $_GET['per_page'] ||$_GET["search"])
		{
			if($_POST["compensation_approve_start_date"] && $_POST["compensation_approve_end_date"])
			{
				$data['start_date'] = $_POST["compensation_approve_start_date"];
				$data['end_date'] = $_POST["compensation_approve_end_date"];
				$_SESSION["start_date"] = $data['start_date'];
				$_SESSION["end_date"] = $data['end_date'];
			}
			else
			{
				$data['start_date'] = $_SESSION["start_date"];
				$data['end_date'] = $_SESSION["end_date"];
			}

			$where = array();
			$option = array();

			$where["soc.create_on >="] = $_SESSION["start_date"] . " 00:00:00";
			$where["soc.create_on <="] = $_SESSION["end_date"] . " 23:59:59";

			$sort = $this->input->get('sort');
			if($sort == "")
			{
				$sort = "so.so_no";
			}
			$data["sort"] = $sort;

			$order = $this->input->get('order');
			if (empty($order))
			{
				$order = "asc";
			}
			$data["order"] = $order;

			$option["orderby"] = $sort." ".$order;




			$data["sortimg"][$sort] = "<img src='".base_url()."images/".$order.".gif'>";
			$data["xsort"][$sort] = $order=="asc"?"desc":"asc";
			$option["orderby"] = $sort." ".$order;

			if($this->input->get('so_no')!="")
			{
				$where["so.so_no"] = $this->input->get('so_no');
			}
			if($this->input->get('platform_id')!="")
			{
				$where["so.platform_id"] = $this->input->get('platform_id');
			}
			if($this->input->get('sku')!="")
			{
				$where["soc.item_sku"] = $this->input->get('sku');
			}
			if($this->input->get('product_name')!="")
			{
				$where["p.name like"] = "%".$this->input->get('product_name')."%";
			}
			if($this->input->get('reason')!="")
			{
				$where["soch.note like"] = "%".$this->input->get('reason')."%";
			}
			if($this->input->get('request_date')!="")
			{
				$where["soc.create_on"] = $this->input->get('request_date');
			}
			if($this->input->get('request_by')!="")
			{
				$where["soc.create_by"] = $this->input->get('request_by');
			}
			if($this->input->get('approved_date')!="")
			{
				$where["soc.modify_on"] = $this->input->get('approved_date');
			}
			if($this->input->get('approved_by')!="")
			{
				$where["soc.modify_by"] = $this->input->get('approved_by');
			}

			$option["limit"] = $pconfig['per_page'] = 20;
			if ($option["limit"])
			{
				$option["offset"] = $this->input->get("per_page");
			}

			$_SESSION["LISTPAGE"] = base_url()."report/compensation_report/index?".$_SERVER['QUERY_STRING'];
			$_SESSION["QUERY_STRING"] = $_SERVER['QUERY_STRING'];
			$data['list'] = $this->compensation_report_model->get_obj_list($where, $option);

			$option['num_rows'] = 1;
			$data['total'] = $this->compensation_report_model->get_obj_list($where, $option);


			$_SESSION["LISTPAGE"] = base_url()."report/compensation_report/index?".$_SERVER['QUERY_STRING'];
			$pconfig['base_url'] = $_SESSION["LISTPAGE"];
			$pconfig['total_rows'] = $data['total'];
			$this->pagination_service->set_show_count_tag(TRUE);
			$this->pagination_service->initialize($pconfig);
		}

		$this->load->view('report/compensation_report',$data);
	}

	public function export_csv()
	{
		if($this->input->post('is_query'))
		{
			$data["posted"] = 1;

			if($_POST["start_date"]["order_create"])
			{
				$_SESSION['start_date'] = $_POST["start_date"]["order_create"];
				$where["soc.create_on >="] = $_POST["start_date"]["order_create"] . " 00:00:00";
			}
			if($_POST["end_date"]["order_create"])
			{
				$_SESSION['end_date'] = $_POST["end_date"]["order_create"];
				$where["soc.create_on <="] = $_POST["end_date"]["order_create"] . " 23:59:59";
			}
			$data['output'] = $this->compensation_report_model->get_csv($where);
			$data['filename'] = 'compensation_report.csv';
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