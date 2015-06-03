<?php
class Compliance_refund_rates_rpt_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('service/compliance_refund_rates_rpt_service');
	}

	public function send_report($week_of_data_to_show=1)
	{
		$ts = time() - (date('w', time()) * 86400);  // Get the date of last Sunday

		$data = array();
		for ($i = 0; $i < $week_of_data_to_show; $i++)
		{
			$start_date = date('Y-m-d', ($ts - 6 * 86400));  // 6 days before
			$end_date = date('Y-m-d', $ts);

			$data[$i] = array();
			$data[$i]['start_date'] = $start_date;
			$data[$i]['end_date'] = $end_date;

			$sales_data = $this->compliance_refund_rates_rpt_service->get_sales_data($start_date, $end_date);
			foreach ($sales_data as $pmgw_id=>$all_currency)
			{
				$data[$i][$pmgw_id] = array();
				foreach ($all_currency as $currency_id=>$sales)
				{
					if ($currency_id == 'pmgw_name')
					{
						$data[$i][$pmgw_id]['pmgw_name'] = $sales;
					}
					else
					{
						$data[$i][$pmgw_id][$currency_id]['sales'] = $sales;
					}
				}
			}

			$refund_data = $this->compliance_refund_rates_rpt_service->get_refund_data($start_date, $end_date);
			foreach ($refund_data as $pmgw_id=>$all_currency)
			{
				if (!array_key_exists($pmgw_id, $data[$i]))
				{
					$data[$i][$pmgw_id] = array();
				}

				foreach ($all_currency as $currency_id=>$refund)
				{
					if ($currency_id == 'pmgw_name')
					{
						$data[$i][$pmgw_id]['pmgw_name'] = $refund;
					}
					else
					{
						$data[$i][$pmgw_id][$currency_id]['refund'] = $refund;
					}
				}
			}

			$ts -= (7 * 86400);  // Shift 1 week before
		}

		if (!empty($data))
		{
			$report_fullpath = $this->compliance_refund_rates_rpt_service->save_report($data);
			if ($report_fullpath !== FALSE)
			{
				return $this->compliance_refund_rates_rpt_service->send_report($report_fullpath);
			}

			return FALSE;
		}

		return TRUE;
	}
}
?>