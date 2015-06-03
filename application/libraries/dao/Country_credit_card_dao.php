<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Country_credit_card_dao extends Base_dao
{
	private $table_name="country_credit_card";
	private $vo_class_name="Country_credit_card_vo";
	private $seq_name="";
	private $seq_mapping_field="";

	public function __construct()
	{
		parent::__construct();
	}

	public function get_vo_classname()
	{
		return $this->vo_class_name;
	}

	public function get_table_name()
	{
		return $this->table_name;
	}

	public function get_seq_name()
	{
		return $this->seq_name;
	}

	public function get_seq_mapping_field()
	{
		return $this->seq_mapping_field;
	}

	public function get_country_pmgw_card_list($where=array(), $option=array(), $classname="Country_pmgw_card_dto")
	{
		$this->db->from('pmgw_card AS pc');
		$this->db->join('payment_gateway AS pg', 'pg.id = pc.payment_gateway_id', 'INNER');
		$this->db->join('platform_pmgw AS pp', 'pp.payment_gateway_id = pg.id', 'INNER');
		$this->db->join('platform_biz_var AS pbv', 'pbv.selling_platform_id = pp.platform_id', 'INNER');
		$this->db->join('country_credit_card AS ccc', 'ccc.card_code = pc.code', 'LEFT');
		$this->db->join('exchange_rate AS ex', "ex.from_currency_id = pbv.platform_currency_id and ex.to_currency_id = pp.pmgw_ref_currency_id", 'LEFT');

		if (isset($where["order_amount"]))
		{
			$tmp_st = "((ex.rate * " . $where["order_amount"] . ") >= pp.ref_from_amt and (ex.rate * " . $where["order_amount"] . ") < pp.ref_to_amt_exclusive ";
			unset($where["order_amount"]);

			$tmp_st_time = "(pp.time_from < pp.time_to_exclusive and time(now()) >= pp.time_from and time(now()) < pp.time_to_exclusive )
							or (pp.time_from > pp.time_to_exclusive and ((time(now()) >= pp.time_from and time(now()) < '24:00:00')
								or (time(now()) >= '00:00:00' and time(now()) < pp.time_to_exclusive))) ";
			if ((isset($where["include_default"])) && ($where["include_default"] === TRUE))
			{
				$tmp_st = "(" . $tmp_st;
				$tmp_st .= ") or (pp.ref_from_amt is null and pp.ref_to_amt_exclusive is null)";
				unset($where["include_default"]);

				$tmp_st_time = "(" . $tmp_st_time . "or (pp.time_from is null and pp.time_to_exclusive is null))";
			}
			$tmp_st .= ")";

			$where[$tmp_st] = NULL;
			$where[$tmp_st_time] = NULL;
		}

		if ($where)
		{
			$this->db->where($where);
		}

		if (empty($option["num_rows"]))
		{
			$this->include_dto($classname);

			$this->db->select('ccc.country_id, pc.*');

			if (isset($option["groupby"]))
			{
				$this->db->group_by($option["groupby"]);
			}

			if (isset($option["orderby"]))
			{
				$this->db->order_by($option["orderby"]);
			}

			if (empty($option["limit"]))
			{
				$option["limit"] = $this->rows_limit;
			}
			elseif ($option["limit"] == -1)
			{
				$option["limit"] = "";
			}

			if (!isset($option["offset"]))
			{
				$option["offset"] = 0;
			}

			if ($this->rows_limit != "")
			{
				$this->db->limit($option["limit"], $option["offset"]);
			}

			$rs = array();

			if ($query = $this->db->get())
			{
				foreach ($query->result($classname) as $obj)
				{
					$rs[] = $obj;
				}
				return $rs?(object)$rs:$rs;
			}
		}
		else
		{
			$this->db->select('COUNT(*) AS total');
			if ($query = $this->db->get())
			{
				return $query->row()->total;
			}
		}

		return FALSE;
	}
}

/* End of file action_dao.php */
/* Location: ./system/application/libraries/dao/Action_dao.php */