<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Report_service.php";

class Rpt_customer_extraction_service extends Report_service
{
	private $so_service;

	public function __construct()
	{
		parent::__construct();
		include_once(APPPATH."libraries/service/So_service.php");
		$this->set_so_service(new So_service());
		$this->set_output_delimiter(',');
	}

	public function set_so_service($value)
	{
		$this->so_service = $value;
		return $this;
	}

	public function get_so_service()
	{
		return $this->so_service;
	}

	public function get_data($record, $where)
	{
		$start_date = $record['start_date'];
		$end_date = $record['end_date'];
		$plat_arr = $record['plat_list'];
		$cat_arr = $record['cat_list'];
		$plat_box = $record['plat_box'];
		$period_box = $record['period_box'];
		$freq_box = $record['freq_box'];
		$order_box = $record['order_box'];
		$cat_box = $record['cat_box'];
		$frequency = $record['frequency'];
		$order_value = $record['order_value'];
		$curr = strtoupper($record['currency']);

		$where = '';
		$where1 = '';
		$where2 = '';

		$w1_flag = 0;
		$w2_flag = 0;

		if ($period_box)
		{
			if(w_flag == 0)
			{
				if (!empty($start_date))
				{
//					$start_date = mysql_real_escape_string($start_date);
					$start_date .= " 00:00:00";
					$where .= " AND so.order_create_date >= '$start_date'";
				}

				if (!empty($end_date))
				{
//					$end_date = mysql_real_escape_string($end_date);
					$end_date .= " 23:59:59";
					$where .= " AND so.order_create_date <= '$end_date'";
				}
			}
		}

		if ($freq_box)
		{
			$where2 .= ($w2_flag>0?" AND ":" WHERE ").$frequency;
			$w2_flag++;
		}

		if ($order_box)
		{
			$where2 .= ($w2_flag>0?" AND ":" WHERE ").$order_value;
			$w2_flag++;
		}

		if($plat_box){
			if($plat_arr)
			{
				$where .= " AND (platform_id='";
				$where .= implode($plat_arr, "' OR platform_id='");
				$where .= "')";
			} else {
				$where .= " AND platform_id='--')";
			}
		}

		if($cat_box){
			if($cat_arr)
			{
				$flag_first = TRUE;
				foreach($cat_arr as $k=>$v)
				{
					if($flag_first == TRUE)
					{
						$where1 .= ($w1_flag>0?" AND ":" WHERE ")."(b.cat_id='$v'";
						$flag_first = FALSE;
						$w1_flag++;
					} else {
						$where1 .= " OR b.cat_id='$v'";
					}
					if($flag_first == TRUE)
					{
						$where1 .= ($w1_flag>0?" AND ":" WHERE ")."(b.sub_cat_id='$v'";
						$flag_first = FALSE;
						$w1_flag++;
					} else {
						$where1 .= " OR b.sub_cat_id='$v'";
					}
				}
				$where1 .= ")";
			} else {
				$where1 .= ($w1_flag>0?" AND ":" WHERE ")." b.cat_id='-1'";
			}
		}


		return $this->get_so_service()->get_dao()->get_customer_extraction_item_list($where, $where1, $where2, $curr);
	}

	public function get_customer_extraction_item_list($where, $option=array())
	{
		return $this->get_so_service()->get_dao()->get_customer_extraction_item_list($where, $option);
	}

	public function get_csv($record, $where)
	{
		$arr = $this->get_data($record, $where);
		return $this->convert($arr);
	}

	protected function get_default_vo2xml_mapping()
	{
		return '';
	}

	protected function get_default_xml2csv_mapping()
	{
		return '';
	}
}
