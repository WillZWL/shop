<?php
class Chargeback_report extends MY_Controller
{
	private $app_id="ORD0029";
	private $lang_id="en";

	function __construct()
	{
		parent::__construct();
		$this->load->model('account/flex_model');
		$this->load->helper(array('url','notice','object','image'));
		$this->load->library('service/context_config_service');
		$this->load->library('service/pagination_service');
		$this->load->library('service/platform_biz_var_service');
		$this->load->library('service/payment_gateway_service');
		$this->load->library('service/country_service');
		$this->load->library('service/chargeback_service');

		$this->load->library('dao/so_hold_reason_dao');
		$this->load->library('dao/chargeback_dao');

	}

	public function index()
	{
		$sub_app_id = $this->_get_app_id()."01";
		include_once(APPPATH."language/".$sub_app_id."_".$this->_get_lang_id().".php");
		$_SESSION["LISTPAGE"] = base_url()."order/chargeback_report/?".$_SERVER['QUERY_STRING'];
		$data["lang"] = $lang;
		$filter = array();
		if($this->input->post("search"))
		{
			$filter["platform_id"] 			= trim($_POST["platform"]);
			$filter["order_start_date"] 	= trim($_POST["orderstart"]);
			$filter["order_end_date"] 		= trim($_POST["orderend"]);
			$filter["payment_gateway_id"] 	= trim($_POST["pmgw"]);
			$filter["hold_reason"]			= trim($_POST["rsn"]);
			$filter["chargeback_reason"] 	= trim($_POST["cbrsn"]);
			$filter["chargeback_start_date"]= trim($_POST["cbstart"]);
			$filter["chargeback_end_date"] 	= trim($_POST["cbend"]);
			$filter["chargeback_status"] 	= trim($_POST["cbstatus"]);
			$filter["chargeback_remark"] 	= trim($_POST["cbremark"]);
			$filter["so_no"] 				= trim($_POST["so"]);
			$filter["currency_id"] 			= trim($_POST["curr"]);

			if($result = (array)$this->chargeback_service->get_chargeback_data($filter))
			{
				$output = $this->chargeback_service->process_data($result, 'csv');

				if($output)
				{
					$filename = "chargeback_orders_".date('YmdHis').".csv";
					header("Content-type: text/csv");
					header("Cache-Control: no-store, no-cache");
					header("Content-disposition: filename=$filename");
					echo $output;
					die();
				}

			}
			else
			{
				$_SESSION["NOTICE"] = "No data available for your selection";
			}
			$data["notice"] = notice($lang);
		}


		$data["selling_platform"] = $this->platform_biz_var_service->get_selling_platform_list();
		$data["currency_list"] = $this->country_service->get_sell_currency_list();
		$data["pmgw_list"] = $this->payment_gateway_service->get_list(array(), array("orderby"=>"name ASC", "limit"=>-1));
		$data["hold_reason_list"] = $this->so_hold_reason_dao->get_reason_list();
		$data["chargeback_reason_list"] = $this->chargeback_dao->get_chargeback_reason_list();
		$data["chargeback_status_list"] = $this->chargeback_dao->get_chargeback_status_list();
		$data["chargeback_remark_list"] = $this->chargeback_dao->get_chargeback_remark_list();

		// echo "<pre>"; var_dump($data["chargeback_reason_list"]);die();


		$this->load->view('order/chargeback_report/index_v', $data);
	}


	public function _get_app_id()
	{
		return $this->app_id;
	}

	public function _get_lang_id()
	{
		return $this->lang_id;
	}

	public function get_contact_email()
	{
		return 'oswald-alert@eservicesgroup.com';
	}

}

/* End of file chargeback_report.php */
/* Location: ./app/controllers/chargeback_report.php */
