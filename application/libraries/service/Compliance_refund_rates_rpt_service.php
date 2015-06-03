<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Compliance_refund_rates_rpt_service extends Base_service
{
	protected $ex_rate_srv;
	protected $ex_rate_list;
	protected $so_srv;
	protected $refund_srv;
	protected $config_srv;

	public function __construct()
	{
		parent::__construct();

		include_once(APPPATH."libraries/service/Exchange_rate_service.php");
		$this->set_ex_rate_srv(new Exchange_rate_service());
		include_once(APPPATH."libraries/service/So_service.php");
		$this->set_so_srv(new So_service());
		include_once(APPPATH."libraries/service/Refund_service.php");
		$this->set_refund_srv(new Refund_service());
		include_once(APPPATH."libraries/service/Context_config_service.php");
		$this->set_config_srv(new Context_config_service());

		$this->get_hk_ex_rate();
	}

	public function get_ex_rate_srv()
	{
		return $this->ex_rate_srv;
	}

	public function set_ex_rate_srv($srv)
	{
		$this->ex_rate_srv = $srv;
	}

	public function get_so_srv()
	{
		return $this->so_srv;
	}

	public function set_so_srv($srv)
	{
		$this->so_srv = $srv;
	}

	public function get_refund_srv()
	{
		return $this->refund_srv;
	}

	public function set_refund_srv($srv)
	{
		$this->refund_srv = $srv;
	}

	public function get_config_srv()
	{
		return $this->config_srv;
	}

	public function set_config_srv($srv)
	{
		$this->config_srv = $srv;
	}

	protected function get_hk_ex_rate()
	{
		$this->ex_rate_list = array();

		$ex_rate_list = $this->get_ex_rate_srv()->get_list(array('to_currency_id'=>'HKD'), array('limit'=>-1));
		foreach ($ex_rate_list as $ex_rate)
		{
			$this->ex_rate_list[$ex_rate->get_from_currency_id()] = $ex_rate->get_rate();
		}
	}

	protected function calc_hk_amount($from_currency_id, $amount)
	{
		$ex_rate = $this->ex_rate_list[$from_currency_id];
		if (empty($ex_rate) || !is_numeric($amount))
		{
			return 0;
		}

		return $ex_rate * $amount;
	}

	public function get_sales_data($start_date, $end_date)
	{
		$start_date .= ' 00:00:00';
		$end_date .= ' 23:59:59';

		$where = array();
		$where['so.biz_type !='] = 'SPECIAL';
		$where["so.order_create_date between '" . $start_date . "' and '" . $end_date . "'"] = NULL;
		$where['!(so.status in (0, 1) and so.refund_status = 0 and so.hold_status = 0)'] = NULL;

		$option = array();
		$option['orderby'] = 'sops.payment_gateway_id, so.currency_id, pbv.platform_country_id';

		$result = array();

		//#4274 - Create and use new function to avoid if previous function actually used by other features
		$so_list = $this->get_so_srv()->get_dao()->get_so_amount_by_pmgw_currency_with_eur_country($where, $option);
		//print_r($so_list);
		foreach ($so_list as $so)
		{
			$pmgw_id = $so->get_payment_gateway_id();
			if ($pmgw_id == '') $pmgw_id = 'NA';
			if (!array_key_exists($pmgw_id, $result))
			{
				$result[$pmgw_id] = array();
				$result[$pmgw_id]['pmgw_name'] = (is_null($so->get_pmgw_name()) ? 'NA' : $so->get_pmgw_name());
			}

			$currency_id = $so->get_currency_id();
			if ($currency_id == '') $currency_id = 'NA';

			//#4274 - Adding new column for selling platform country
			if ($currency_id == 'EUR') $currency_id = 'EUR-'.$so->get_platform_country_id();
			if (!array_key_exists($currency_id, $result[$pmgw_id]))
			{
				$result[$pmgw_id][$currency_id] = array();
			}

			$result[$pmgw_id][$currency_id]['count'] = $so->get_so_count();
			$result[$pmgw_id][$currency_id]['amount'] = $so->get_so_amount();

			#4274 - Even this is break down by selling platform country, but all currency is still EUR
			if (substr($currency_id, 0,4) == 'EUR-')
				$result[$pmgw_id][$currency_id]['hk_amount'] = $this->calc_hk_amount('EUR', $so->get_so_amount());
			else
				$result[$pmgw_id][$currency_id]['hk_amount'] = $this->calc_hk_amount($currency_id, $so->get_so_amount());

			$result[$pmgw_id][$currency_id]['platform_country_id'] = $so->get_platform_country_id();
		}

		return $result;
	}

	public function get_refund_data($start_date, $end_date)
	{
		$start_date .= ' 00:00:00';
		$end_date .= ' 23:59:59';

		$where = array();
		$where['so.biz_type !='] = 'SPECIAL';
		$where["rh.modify_on between '" . $start_date . "' and '" . $end_date . "'"] = NULL;
		$where['rh.app_status'] = 'A';
		$where['rh.status'] = 'C';
		$where['r.reason NOT IN (30,32)'] = NULL;
		$option = array();
		$option['orderby'] = 'pmgw.id, so.currency_id, pbv.platform_country_id, rr.description';

		$result = array();

		//#4274 - Create and use new function to avoid if previous function actually used by other features
		$refund_list = $this->get_refund_srv()->get_dao()->get_refund_amount_by_pmgw_currency_with_eur_country($where, $option);
		//print_r($refund_list);
		foreach ($refund_list as $refund)
		{
			$pmgw_id = $refund->get_payment_gateway_id();
			if ($pmgw_id == '') $pmgw_id = 'NA';
			if (!array_key_exists($pmgw_id, $result))
			{
				$result[$pmgw_id] = array();
				$result[$pmgw_id]['pmgw_name'] = (is_null($refund->get_pmgw_name()) ? 'NA' : $refund->get_pmgw_name());
			}

			$currency_id = $refund->get_currency_id();

			if ($currency_id == 'EUR')
			{
				$currency_id = 'EUR-'.$refund->get_platform_country_id();
			}

			if ($currency_id == '') $currency_id = 'NA';
			if (!array_key_exists($currency_id, $result[$pmgw_id]))
			{
				$result[$pmgw_id][$currency_id] = array();

				$result[$pmgw_id][$currency_id]['count'] = 0;
				$result[$pmgw_id][$currency_id]['amount'] = 0;
				$result[$pmgw_id][$currency_id]['hk_amount'] = 0;

				$result[$pmgw_id][$currency_id]['refund_reason'] = array();
				$result[$pmgw_id][$currency_id]['refund_reason']['Fail CC'] = array();
				$result[$pmgw_id][$currency_id]['refund_reason']['Fail CC']['count'] = 0;
				$result[$pmgw_id][$currency_id]['refund_reason']['Fail CC']['amount'] = 0;
				$result[$pmgw_id][$currency_id]['refund_reason']['Fail CC']['hk_amount'] = 0;

				$result[$pmgw_id][$currency_id]['refund_reason']['Refuse CC'] = array();
				$result[$pmgw_id][$currency_id]['refund_reason']['Refuse CC']['count'] = 0;
				$result[$pmgw_id][$currency_id]['refund_reason']['Refuse CC']['amount'] = 0;
				$result[$pmgw_id][$currency_id]['refund_reason']['Refuse CC']['hk_amount'] = 0;
			}

			if (strtoupper(trim($refund->get_refund_reason())) == 'REFUSED CREDIT CHECK')
			{
				$result[$pmgw_id][$currency_id]['refund_reason']['Refuse CC']['count'] = $refund->get_refund_count();
				$result[$pmgw_id][$currency_id]['refund_reason']['Refuse CC']['amount'] = $refund->get_refund_amount();

				#4274 - Even this is break down by selling platform country, but all currency is still EUR
				if (substr($currency_id, 0,4) == 'EUR-')
					$result[$pmgw_id][$currency_id]['refund_reason']['Refuse CC']['hk_amount'] = $this->calc_hk_amount('EUR', $refund->get_refund_amount());
				else
					$result[$pmgw_id][$currency_id]['refund_reason']['Refuse CC']['hk_amount'] = $this->calc_hk_amount($currency_id, $refund->get_refund_amount());
			}

			if (strtoupper(trim($refund->get_refund_reason())) == 'FAILED CREDIT CHECK')
			{
				$result[$pmgw_id][$currency_id]['refund_reason']['Fail CC']['count'] = $refund->get_refund_count();
				$result[$pmgw_id][$currency_id]['refund_reason']['Fail CC']['amount'] = $refund->get_refund_amount();

				#4274 - Even this is break down by selling platform country, but all currency is still EUR
				if (substr($currency_id, 0,4) == 'EUR-')
					$result[$pmgw_id][$currency_id]['refund_reason']['Fail CC']['hk_amount'] = $this->calc_hk_amount('EUR', $refund->get_refund_amount());
				else
					$result[$pmgw_id][$currency_id]['refund_reason']['Fail CC']['hk_amount'] = $this->calc_hk_amount($currency_id, $refund->get_refund_amount());
			}

			$result[$pmgw_id][$currency_id]['count'] += $refund->get_refund_count();
			$result[$pmgw_id][$currency_id]['amount'] += $refund->get_refund_amount();

			#4274 - Even this is break down by selling platform country, but all currency is still EUR
			if (substr($currency_id, 0,4) == 'EUR-')
				$result[$pmgw_id][$currency_id]['hk_amount'] += $this->calc_hk_amount('EUR', $refund->get_refund_amount());
			else
				$result[$pmgw_id][$currency_id]['hk_amount'] += $this->calc_hk_amount($currency_id, $refund->get_refund_amount());

			$result[$pmgw_id][$currency_id]['platform_country_id'] = $refund->get_platform_country_id();
		}

		return $result;
	}

	public function save_report($data_array)
	{
		$content = array();
		$eof = "\r\n";
		$delimiter = ',';

		$line = array('Date Range', 'PSP', 'Currency', 'Order Type', 'Sales Count', 'Sales Amount (HKD)', 'Refund Count', 'Refund Count Rate', 'Refund Amount (HKD)', 'Refund Amount Rate', 'Fail CC Count', 'Fail CC Amount (HKD)', 'Refuse CC Count', 'Refuse CC Amount (HKD)');
		$content[] = implode($delimiter, $line);

		for ($i = 0; $i < sizeof($data_array); $i++)
		{
			$start_date = $data_array[$i]['start_date'];
			$end_date = $data_array[$i]['end_date'];

			unset($data_array[$i]['start_date']);
			unset($data_array[$i]['end_date']);

			if (sizeof($data_array[$i]) == 0)
			{
				$content[] = $start_date . ' - ' . $end_date;
				$content[] = '';
				continue;
			}

			$total_sales_count = 0;
			$total_sales_amount = 0;
			$total_refund_count = 0;
			$total_refund_amount = 0;
			$total_fail_cc_count = 0;
			$total_fail_cc_amount = 0;
			$total_refuse_cc_count = 0;
			$total_refuse_cc_amount = 0;

			foreach ($data_array[$i] as $pmgw_id=>$all_currency)
			{
				$subtotal_sales_count = 0;
				$subtotal_sales_amount = 0;
				$subtotal_refund_count = 0;
				$subtotal_refund_amount = 0;
				$subtotal_fail_cc_count = 0;
				$subtotal_fail_cc_amount = 0;
				$subtotal_refuse_cc_count = 0;
				$subtotal_refuse_cc_amount = 0;

				$pmgw_name = $all_currency['pmgw_name'];
				unset($all_currency['pmgw_name']);

				$first_row = TRUE;
				foreach ($all_currency as $currency_id=>$data)
				{
					$line = array();

					if ($first_row)
					{
						$line[] = $start_date . ' - ' . $end_date;
						$line[] = $pmgw_name;
						$first_row = FALSE;
					}
					else
					{
						$line[] = '';
						$line[] = '';
					}

					//#4274 - Adding new column for selling platform country
					if (substr($currency_id,0,4) == 'EUR-')
					{
						$currency_id = 'EUR';
					}

					$line[] = $currency_id;

					//#4274
					if (!isset($data['sales']['platform_country_id']) || $data['sales']['platform_country_id'] == '')
						$line[] = $data['refund']['platform_country_id']; //for worldpay
					else
						$line[] = $data['sales']['platform_country_id'];

					if (isset($data['sales']))
					{
						$sales_count = $data['sales']['count'];
						$sales_amount = $data['sales']['hk_amount'];

						$line[] = $sales_count;
						$line[] = '"' . number_format(floor($sales_amount)) . '"';

						$subtotal_sales_count += $sales_count;
						$subtotal_sales_amount += $sales_amount;
					}
					else
					{
						$line[] = '0';
						$line[] = '0';

						$sales_count = 0;
						$sales_amount = 0;
					}

					if (isset($data['refund']))
					{
						$line[] = $data['refund']['count'];
						if ($sales_count == 0)
						{
							$line[] = 'NA';
						}
						else
						{
							$line[] = round(($data['refund']['count'] / $sales_count) * 100, 2) . '%';
						}

						$line[] = '"' . number_format(floor($data['refund']['hk_amount'])) . '"';
						if ($sales_amount == 0)
						{
							$line[] = 'NA';
						}
						else
						{
							$line[] = round(($data['refund']['hk_amount'] / $sales_amount) * 100, 2) . '%';
						}

						if (isset($data['refund']['refund_reason']))
						{
							$refund_data = $data['refund']['refund_reason'];

							if (isset($refund_data['Fail CC']))
							{
								$line[] = $refund_data['Fail CC']['count'];
								$line[] = '"' . number_format(floor($refund_data['Fail CC']['hk_amount'])) . '"';

								$subtotal_fail_cc_count += $refund_data['Fail CC']['count'];
								$subtotal_fail_cc_amount += $refund_data['Fail CC']['hk_amount'];
							}
							else
							{
								$line[] = '0';
								$line[] = '0';
							}

							if (isset($refund_data['Refuse CC']))
							{
								$line[] = $refund_data['Refuse CC']['count'];
								$line[] = '"' . number_format(floor($refund_data['Refuse CC']['hk_amount'])) . '"';

								$subtotal_refuse_cc_count += $refund_data['Refuse CC']['count'];
								$subtotal_refuse_cc_amount += $refund_data['Refuse CC']['hk_amount'];
							}
							else
							{
								$line[] = '0';
								$line[] = '0';
							}
						}
						else
						{
							$line[] = '0';
							$line[] = '0';
							$line[] = '0';
							$line[] = '0';
						}

						$subtotal_refund_count += $data['refund']['count'];
						$subtotal_refund_amount += $data['refund']['hk_amount'];
					}
					else
					{
						$line[] = '0';
						$line[] = '0';
						$line[] = '0';
						$line[] = '0';
						$line[] = '0';
						$line[] = '0';
						$line[] = '0';
						$line[] = '0';
					}

					$content[] = implode($delimiter, $line);
				}

				$line = array();
				$line[] = 'Subtotal';
				$line[] = $pmgw_name;
				$line[] = 'ALL';
				$line[] = 'ALL';
				$line[] = $subtotal_sales_count;
				$line[] = '"' . number_format(floor($subtotal_sales_amount)) . '"';
				$line[] = $subtotal_refund_count;
				$line[] = ($subtotal_sales_count == 0 ? 'NA' : round(($subtotal_refund_count / $subtotal_sales_count) * 100, 2) . '%');
				$line[] = '"' . number_format(floor($subtotal_refund_amount)) . '"';
				$line[] = ($subtotal_sales_amount == 0 ? 'NA' : round(($subtotal_refund_amount / $subtotal_sales_amount) * 100, 2) . '%');
				$line[] = $subtotal_fail_cc_count;
				$line[] = '"' . number_format(floor($subtotal_fail_cc_amount)) . '"';
				$line[] = $subtotal_refuse_cc_count;
				$line[] = '"' . number_format(floor($subtotal_refuse_cc_amount)) . '"';

				$content[] = implode($delimiter, $line);

				$total_sales_count += $subtotal_sales_count;
				$total_sales_amount += $subtotal_sales_amount;
				$total_refund_count += $subtotal_refund_count;
				$total_refund_amount += $subtotal_refund_amount;
				$total_fail_cc_count += $subtotal_fail_cc_count;
				$total_fail_cc_amount += $subtotal_fail_cc_amount;
				$total_refuse_cc_count += $subtotal_refuse_cc_count;
				$total_refuse_cc_amount += $subtotal_refuse_cc_amount;
			}

			$line = array();
			$line[] = 'Total';
			$line[] = '';
			$line[] = 'ALL';
			$line[] = 'ALL';
			$line[] = $total_sales_count;
			$line[] = '"' . number_format(floor($total_sales_amount)) . '"';
			$line[] = $total_refund_count;
			$line[] = ($total_sales_count == 0 ? 'NA' : round(($total_refund_count / $total_sales_count) * 100, 2) . '%');
			$line[] = '"' . number_format(floor($total_refund_amount)) . '"';
			$line[] = ($total_sales_amount == 0 ? 'NA' : round(($total_refund_amount / $total_sales_amount) * 100, 2) . '%');
			$line[] = $total_fail_cc_count;
			$line[] = '"' . number_format(floor($total_fail_cc_amount)) . '"';
			$line[] = $total_refuse_cc_count;
			$line[] = '"' . number_format(floor($total_refuse_cc_amount)) . '"';

			$content[] = implode($delimiter, $line);
			$content[] = '';
		}

		$filename = $this->get_config_srv()->value_of('data_path') . 'refund_rates_report/refund_rates_report_' . date('Ymd_his') . '.csv';
		$fp = fopen($filename, 'w');

		if (fwrite($fp, implode($eof, $content)))
		{
			return $filename;
		}

		return FALSE;
	}

	public function send_report($file)
	{
		 //add From: header
		$headers = "From: itsupport@eservicesgroup.com\r\n";

		//specify MIME version 1.0
		$headers .= "MIME-Version: 1.0\r\n";

		//unique boundary
		$boundary = uniqid("HTMLDEMO");

		//tell e-mail client this e-mail contains//alternate versions
		$headers .= "Content-Type: multipart/mixed; boundary = $boundary\r\n\r\n";

		$fp = @fopen($file, "rb");
		$data = @fread($fp,filesize($file));
		$data = chunk_split(base64_encode($data));
		@fclose($fp);

		//HTML version of message
		$body .= "--$boundary\r\n" .
			"Content-Type: octet-stream; name= \"refund_rates_report_" . date("Ymd") . ".csv\" charset=ISO-8859-1\r\n" .
			"Content-Description: " . basename($file) . "\n" .
			"Content-Disposition: attachment;\n" . " filename=\"".basename($file)."\"; size=".filesize($file).";\n" .
			"Content-Transfer-Encoding: base64\r\n\r\n" . $data . "\r\n";

		//send message
		$to = "vbrefundreport@valuebasket.com";
		mail($to, "[VB] Refund Rates Report", $body, $headers);
	}
}

/* End of file compliance_refund_rates_rpt_service.php */
/* Location: ./system/application/libraries/service/Compliance_refund_rates_rpt_service.php */