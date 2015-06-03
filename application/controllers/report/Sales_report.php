<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_report extends MY_Controller
{
	protected $app_id="RPT0002";
	private $lang_id="en";
	private $model;
	private $export_filename;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('report/sales_report_model');
		$this->load->helper(array('url', 'notice', 'image'));
		$this->load->library('service/context_config_service');
		$this->load->library('service/country_service');
		$this->load->library('service/payment_gateway_service');
		$this->load->library('service/so_service');
		$this->load->library('service/so_shipment_service');
		$this->_set_model($this->sales_report_model);
		$this->_set_export_filename('sales_report.csv');
	}

	private function _load_parent_lang()
	{
		$sub_app_id = $this->_get_app_id()."00";
		include_once(APPPATH."language/".$sub_app_id."_".$this->_get_lang_id().".php");

		return $lang;
	}

	public function query()
	{
		$data['lang'] = $this->_load_parent_lang();
		if($this->input->post('is_query'))
		{
			$from_year = $this->input->post('from_year');
			$from_month = $this->input->post('from_month');
			$from_day = $this->input->post('from_day');
			$to_year = $this->input->post('to_year');
			$to_month = $this->input->post('to_month');
			$to_day = $this->input->post('to_day');
			$currency_id = $this->input->post('currency');
			$country_id = $this->input->post('country');
			$payment_gateway = $this->input->post('payment_gateway');
			$is_sales_rpt = $this->input->post('is_sales_rpt');
			$is_light_version = $this->input->post("light_version");
			$is_china_oem = $this->input->post('china_oem');


			$clearance = $this->input->post('clearance');

			$where = array();
			if ($currency_id != -1)
				$where['so.currency_id'] = $currency_id;
			if ($country_id != -1)
				$where['so.delivery_country_id'] = $country_id;
			if ($payment_gateway != -1)
				$where['sps.payment_gateway_id'] = $payment_gateway;
			if ($is_china_oem != -1){
				if($is_china_oem == 0 || $is_china_oem == 1){
					$where['p.china_oem'] = $is_china_oem;
				}
			}
			// sbf #4056 include filters for clearance
			switch ($clearance) {
				case 'clearance':
					$where['clearance'] = 1;
					break;
				case 'exclude_negative_clearance':
					// equivalent to NOT ( soid.profit < 0 AND clearance )
					$where['(soid.profit > 0 OR clearance = 0)'] = null;
					break;
			}

			$from_date = $from_year . '-' . $from_month . '-' . $from_day;
			$to_date = $to_year . '-' . $to_month . '-' . $to_day;



			$data['output'] = $this->_get_model()->get_csv($from_date, $to_date, $where, $is_sales_rpt, $is_light_version);

			if($is_light_version)
			{
				$data['filename'] = "light_sales_report.csv";
			}
			else
			{
				$data['filename'] = $this->_get_export_filename();
			}


			$this->load->view('output_csv.php', $data);
		}
	}

	public function index()
	{
		$data['lang'] = $this->_load_parent_lang();
		$data['controller'] = strtolower(get_class($this));
		$data['countrys'] = $this->country_service->get_dao()->get_list(array("allow_sell" => "1"), array("orderby" => "name", "limit"=>-1));
		$data['currencys'] = $this->country_service->get_sell_currency_list();
		$data['gateways'] = $this->payment_gateway_service->get_list(array("status"=>1), array("limit"=>-1));

//		print $this->country_service->get_dao()->db->last_query();
//		var_dump($data['currency']);
		$this->load->view('report/sales_report', $data);
	}

	public function split_orders_report()
	{
		$data['lang'] = $this->_load_parent_lang();
		$data["start_date"] = date('Y-m-d', strtotime(date('Y-m-d'). ' - 10 day'));
		$data["end_date"] = date('Y-m-d');
		$data["notice"] = notice($data['lang']);
		$data["prompt_notice"] = 0;

		if($this->input->post('is_query'))
		{
			$ret = $this->query_split_order();

			if($ret["status"] === FALSE)
			{
				$_SESSION["NOTICE"] = $ret["message"];
			}
			else
			{
				if($ret["data"])
				{
					$filename = "split_orders_report_".date('Ymd_His').".csv";
					$fp = fopen('php://output', 'w');
					header( 'Content-Type: text/csv' );
		            header( 'Content-Disposition: attachment;filename='.$filename);
					foreach ($ret["data"] as $fields)
					{
						fputcsv($fp, $fields);
					}
					fclose($fp);
		            die();

				}
				else
				{
					$_SESSION["NOTICE"] = "Error getting data";
				}
			}

			// if any errors, redirect back with notice
			Redirect(base_url()."report/sales_report/split_orders_report");
		}

		$this->load->view('report/split_orders_report', $data);
	}

	private function query_split_order()
	{
		$data['lang'] = $this->_load_parent_lang();
		$ret["status"] = false;
		if($this->input->post('is_query'))
		{
			$from_date = $this->input->post("start_date");
			$to_date = $this->input->post("end_date");

			// get data and construct csv
			$ret = $this->_get_model()->get_split_order_csv($from_date, $to_date);

		}
		return $ret;
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

	public function get_shipped_summary($start_date = "", $end_date = "")
	{
		if ($start_date == "") $start_date = $_GET["start_date"];#"2013-01-01";
		if ($end_date == "") $end_date = $_GET["end_date"];#2013-01-15";

		$start_date_ok = "";
		$end_date_ok = "";

		if (preg_match("/[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}/", $start_date, $matches)) $start_date_ok = $matches[0];
		if (preg_match("/[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}/", $end_date, $matches)) $end_date_ok = $matches[0];

		if ($start_date_ok == "" || $end_date_ok == "")
			die();

		date_default_timezone_set("GMT+0");
		$xml = new SimpleXMLElement('<shipped/>');
		$xml->description = "Shipped orders summary from $start_date_ok to $end_date_ok";

		$result = $this->so_shipment_service->get_dao()->get_shipped_summary($start_date_ok, $end_date_ok);
		if ($result)
		{
			foreach ($result as $row)
			{
				$sku = $xml->addChild("sku");
				$sku->master_sku 		= $row->master_sku;
				$sku->total_quantity 	= $row->total_quantity;
				$sku->total_amount_hkd 	= $row->total_amount_hkd;
			}
		}
		header('Content-type: text/xml'); print($xml->asXML());
	}

	public function get_sales_summary($start_date = "", $end_date = "")
	{
		if ($start_date == "") $start_date = $_GET["start_date"];#"2013-01-01";
		if ($end_date == "") $end_date = $_GET["end_date"];#2013-01-15";

		$start_date_ok = "";
		$end_date_ok = "";

		if (preg_match("/[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}/", $start_date, $matches)) $start_date_ok = $matches[0];
		if (preg_match("/[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}/", $end_date, $matches)) $end_date_ok = $matches[0];

		if ($start_date_ok == "" || $end_date_ok == "")
			die();

		date_default_timezone_set("GMT+0");
		$xml = new SimpleXMLElement('<sales/>');
		$xml->description = "Sales orders summary from $start_date_ok to $end_date_ok";

		$result = $this->so_service->get_dao()->get_sales_summary($start_date_ok, $end_date_ok);
		if ($result)
		{
			foreach ($result as $row)
			{
				$sku = $xml->addChild("sku");
				$sku->master_sku 		= $row->master_sku;
				$sku->total_quantity 	= $row->total_quantity;
				$sku->total_amount_hkd 	= $row->total_amount_hkd;
			}
		}
		header('Content-type: text/xml'); print($xml->asXML());
	}

}

/* End of file purchaser.php */
/* Location: ./system/application/controllers/report/inventory/stock_valuation.php */