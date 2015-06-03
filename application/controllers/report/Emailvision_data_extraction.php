<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Emailvision_data_extraction extends MY_Controller
{
	protected $app_id="RPT0030";
	private $lang_id="en";
	private $model;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('url'));
		$this->load->library('service/client_service');
		$this->load->library('service/so_service');
		$this->load->library('service/platform_biz_var_service');
		$this->load->library('service/context_config_service');
		$this->load->library('service/selling_platform_service');
		$this->load->library('service/country_service');
	}

	private function _load_parent_lang()
	{
		$sub_app_id = $this->_get_app_id()."00";
		include_once(APPPATH."language/".$sub_app_id."_".$this->_get_lang_id().".php");

		return $lang;
	}

	public function query()
	{
		if($this->input->post('is_query'))
		{
			$start_date = $this->input->post('start_date');
			$end_date = $this->input->post('end_date');
			$platform_list = $this->input->post('platform');

			$where = array();
			if ($platform_list && count($platform_list) > 0)
			{
				$where["platform_id IN ('" . implode("','" ,$platform_list) . "')"] = null;
			}
			$where["((order_create_date BETWEEN '" . $start_date . " 00:00:00' AND '" . $end_date . " 23:59:59') OR (dispatch_date BETWEEN '" . $start_date . " 00:00:00' AND '" . $end_date . " 23:59:59'))"] =  null;

			set_time_limit(300);
			$client_arr = $this->so_service->get_distinct_client_id_list($where, array("limit"=>-1));

			header("Content-type: application/csv");
			header("Content-disposition: filename=emailvision_".date("Ymdhis").".csv");

			echo "EMAIL ADDRESS, TITLE, FIRSTNAME, LASTNAME, POSTCODE, COUNTRY_ID, PLATFORM_ID, LANGUAGE_ID, CURRENCY, LIFETIME_SPEND, LAST_PURCHASE_DATE, LIFETIME_TRANSACTIONS, NON_PURCHASER, VIP, VIP_JOINED_DATE, TRANSACTION1_PRODUCT, TRANSACTION1_DATE, TRANSACTION2_PRODUCT, TRANSACTION2_DATE, TRANSACTION3_PRODUCT, TRANSACTION3_DATE, TRANSACTION4_PRODUCT, TRANSACTION4_DATE, TRANSACTION5_PRODUCT, TRANSACTION5_DATE, TRANSACTION6_PRODUCT, TRANSACTION6_DATE, TRANSACTION7_PRODUCT, TRANSACTION7_DATE, TRANSACTION8_PRODUCT, TRANSACTION8_DATE, TRANSACTION9_PRODUCT, TRANSACTION9_DATE, TRANSACTION10_PRODUCT, TRANSACTION10_DATE\r\n";

			$max_transaction = 0;
			$data = array();

			if($client_arr)
			{
				foreach($client_arr as $cid)
				{
					if($cid && $client_obj = $this->client_service->get(array("id"=>$cid)))
					{
						$i = 0;

						$data[$i][0] = $client_obj->get_email();
						$data[$i][1] = $client_obj->get_title();
						$data[$i][2] = $client_obj->get_forename();
						$data[$i][3] = $client_obj->get_surname();

						// initialize
						$data[$i][4] = "";
						$data[$i][5] = "";
						$data[$i][6] = "";
						$data[$i][7] = "";
						$data[$i][8] = "";
						$data[$i][9] = "";
						$data[$i][10] = "";
						$data[$i][11] = 0;
						$data[$i][12] = 0;

						$data[$i][11] = $this->so_service->get_lifetime_transaction_by_client_id($client_obj->get_id());
						$data[$i][13] = $client_obj->get_vip();
						$data[$i][14] = $client_obj->get_vip_joined_date();

						unset($where);
						$where["client_id"] = $client_obj->get_id();
						if ($platform_list && count($platform_list) > 0)
						{
							$where["platform_id IN ('" . implode("','" ,$platform_list) . "')"] = null;
						}
						$so_list = $this->so_service->get_list($where, array("orderby"=>"order_create_date DESC", "limit"=>-1));

						$latest_order = true;
						foreach($so_list as $so_obj)
						{
							if($so_obj->get_status() == 6 && $so_obj->get_refund_status() == 0 && $so_obj->get_hold_status() == 0 && $latest_order)
							{
								$data[$i][4] = $so_obj->get_bill_postcode();
								$data[$i][5] = $so_obj->get_bill_country_id();
								$data[$i][6] = $so_obj->get_platform_id();
								$data[$i][7] = $so_obj->get_lang_id();

								$pbv_obj = $this->platform_biz_var_service->get(array("selling_platform_id"=>$so_obj->get_platform_id()));
								$data[$i][8] = $pbv_obj->get_platform_currency_id();

								$data[$i][9] += $so_obj->get_amount();
								$data[$i][10] = date("Y-m-d",strtotime($so_obj->get_order_create_date()));
								$data[$i][12] = 1; // shipped

								$transaction_list = $this->so_service->get_last_ten_transaction_info_by_client_id($so_obj->get_client_id());
								if(count($transaction_list) > $max_transaction)
								{
									$max_transaction = count($transaction_list);
								}
								if($transaction_list)
								{
									$trans_index = 14;
									foreach($transaction_list as $transaction)
									{
										for($n=0; $n<$transaction['qty']; $n++)
										{
											if($trans_index < 34)
											{
												$trans_index++;
												$data[$i][$trans_index] = $transaction["trans_product"];
												$trans_index++;
												$data[$i][$trans_index] = date("Y-m-d",strtotime($transaction["order_create_date"]));
											}
										}
									}
									unset($transaction_list);
								}

								$latest_order = false;
							}
							elseif($so_obj->get_status() == 6)
							{
								$data[$i][4] = $so_obj->get_bill_postcode();
								$data[$i][5] = $so_obj->get_bill_country_id();
								$data[$i][6] = $so_obj->get_platform_id();
								$data[$i][7] = $so_obj->get_lang_id();

								$pbv_obj = $this->platform_biz_var_service->get(array("selling_platform_id"=>$so_obj->get_platform_id()));
								$data[$i][8] = $pbv_obj->get_platform_currency_id();

								if($so_obj->get_refund_status() == 0 && $so_obj->get_hold_status() == 0)
								{
									$data[$i][9] += $so_obj->get_amount();

									$transaction_list = $this->so_service->get_last_ten_transaction_info_by_client_id($so_obj->get_client_id());
									if(count($transaction_list) > $max_transaction)
									{
										$max_transaction = count($transaction_list);
									}
									if($transaction_list)
									{
										$trans_index = 14;
										foreach($transaction_list as $transaction)
										{
											for($n=0; $n<$transaction['qty']; $n++)
											{
												if($trans_index < 34)
												{
													$trans_index++;
													$data[$i][$trans_index] = $transaction["trans_product"];
													$trans_index++;
													$data[$i][$trans_index] = date("Y-m-d",strtotime($transaction["order_create_date"]));
												}
											}
										}
										unset($transaction_list);
									}
								}
								else
								{
									if($data[$i][12] == 0)
										$data[$i][12] = 3; // held/refunded
								}
							}
							elseif($so_obj->get_status() >= 3)
							{
								$data[$i][4] = $so_obj->get_bill_postcode();
								$data[$i][5] = $so_obj->get_bill_country_id();
								$data[$i][6] = $so_obj->get_platform_id();
								$data[$i][7] = $so_obj->get_lang_id();

								$pbv_obj = $this->platform_biz_var_service->get(array("selling_platform_id"=>$so_obj->get_platform_id()));
								$data[$i][8] = $pbv_obj->get_platform_currency_id();
								if($data[$i][12] == 0)
									$data[$i][12] = 2; // no purchase
							}
						}

						if($data[$i][12] == 0)
						{
								$client_obj = $this->client_service->get(array("id"=>$client_obj->get_id()));
								$data[$i][4] = $client_obj->get_postcode();
								$data[$i][5] = $client_obj->get_country_id();
								if($pbv_obj = $this->platform_biz_var_service->get(array("platform_country_id"=>$client_obj->get_country_id(), "selling_platform_id LIKE '%WEB%'"=>null)))
								{
									$data[$i][6] = $pbv_obj->get_selling_platform_id();
									$data[$i][7] = $pbv_obj->get_language_id();
									$data[$i][8] = $pbv_obj->get_platform_currency_id();
								}
								elseif($country_obj = $this->country_service->get(array("id"=>$client_obj->get_country_id())))
								{
									$data[$i][6] = "";
									$data[$i][7] = $country_obj->get_language_id();
									$data[$i][8] = $country_obj->get_currency_id();
								}
								else
								{
									$data[$i][6] = "";
									$data[$i][7] = "";
									$data[$i][8] = "";
								}
						}

						echo "\"".implode("\",\"", $data[$i])."\"" ;
						echo "\r\n";
						unset($data);
						$i++;
					}
				}
			}
		}
	}

	public function index()
	{
		$data['lang'] = $this->_load_parent_lang();
		$data['controller'] = strtolower(get_class($this));
		$data['platforms'] = $this->selling_platform_service->get_dao()->get_list(array("status" => "1"), array("orderby" => "id", "limit"=>-1));

		$this->load->view('report/emailvision_data_extraction', $data);
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
}

/* End of file emailvision_data_extraction.php */
/* Location: ./system/application/controllers/report/emailvision_data_extraction.php */