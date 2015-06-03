<?php

include_once 'Base_dao.php';

class Chargeback_dao extends Base_dao
{
	private $table_name="chargeback_dao";
	private $vo_classname="Chargeback_vo";
	private $seq_name="";
	private $seq_mapping_field="";

	public function __construct()
	{
		parent::__construct();
	}

	public function get_table_name()
	{
		return $this->table_name;
	}

	public function get_vo_classname()
	{
		return $this->vo_classname;
	}

	public function get_seq_name()
	{
		return $this->seq_name;
	}

	public function get_seq_mapping_field()
	{
		return $this->seq_mapping_field;
	}

	public function get_chargeback_reason_list()
	{
		$sql = "select id, name, details from lookup_chargeback_reason lk_cb_reason";
		$query = $this->db->query($sql);

		foreach($query->result() as $tmp)
		{
			$obj[] = $tmp;
		}

		return $obj;
	}

	public function get_chargeback_status_list()
	{
		$sql = "select id, name, details from lookup_chargeback_status lk_cb_status";
		$query = $this->db->query($sql);

		foreach($query->result() as $tmp)
		{
			$obj[] = $tmp;
		}

		return $obj;
	}

	public function get_chargeback_remark_list()
	{
		$sql = "select id, name, details from lookup_chargeback_remark lk_cb_remark";
		$query = $this->db->query($sql);

		foreach($query->result() as $tmp)
		{
			$obj[] = $tmp;
		}

		return $obj;
	}

	public function get_chargeback_data($filter=array())
	{
		$rs = array();
		$classname = "Chargeback_orders_dto";
		// $filter["platform_id"]
		// $filter["order_start_date"]
		// $filter["order_end_date"]
		// $filter["payment_gateway_id"]
		// $filter["hold_reason"]
		// $filter["chargeback_reason"]
		// $filter["chargeback_start_date"]
		// $filter["chargeback_end_date"]
		// $filter["chargeback_status"]
		// $filter["chargeback_remark"]
		// $filter["so_no"]
		// $filter["currency_id"]

		if(!empty($filter))
		{
			if($filter["platform_id"] != "")
			{
				$where["s.platform_id"] = $filter["platform_id"];
			}

			if($filter["payment_gateway_id"] != "")
			{
				$where["sps.payment_gateway_id"] = $filter["payment_gateway_id"];
			}

			if($filter["hold_reason"] != "")
			{
				$where["shr.reason"] = $filter["hold_reason"];
			}

			if($filter["chargeback_reason"] != "")
			{
				$where["cb.chargeback_reason_id"] = $filter["chargeback_reason"];
			}

			if($filter["chargeback_status"] != "")
			{
				$where["cb.chargeback_status_id"] = $filter["chargeback_status"];
			}

			if($filter["chargeback_remark"] != "")
			{
				$where["cb.chargeback_remark_id"] = $filter["chargeback_remark"];
			}
			if($filter["so_no"] != "")
			{
				$where["s.so_no"] = $filter["so_no"];
			}
			if($filter["currency_id"] != "")
			{
				$where["s.currency_id"] = $filter["currency_id"];
			}

			if($filter["order_start_date"] != "")
			{
				$order_start_date = "{$filter["order_start_date"]} 00:00:00";
				if($filter["order_end_date"] != "")
				{
					$order_end_date = "{$filter["order_end_date"]} 23:59:59";
				}
				else
				{
					$order_end_date = date('Y-m-d'). " 23:59:59";
				}

				$where["s.create_on >= '$order_start_date' AND s.create_on <= '$order_end_date'"] = null;
			}


			if($filter["chargeback_start_date"] != "")
			{
				$chargeback_start_date = "{$filter["chargeback_start_date"]} 00:00:00";
				if($filter["chargeback_end_date"] != "")
				{
					$chargeback_end_date = "{$filter["chargeback_end_date"]} 23:59:59";
				}
				else
				{
					$chargeback_end_date = date('Y-m-d'). " 23:59:59";
				}

				$where["cb.create_on >= '$chargeback_start_date' AND cb.create_on <= '$chargeback_end_date'"] = null;

			}

			$this->include_dto($classname);
			$this->db->where($where);
			$this->db->from("so s");
			$this->db->select('s.so_no, s.create_on as order_create_date_time
								, cb.create_on as chargeback_create_date
								, lk_cb_reason.name AS chargeback_reason
								, lk_cb_remark.name AS chargeback_remark
								, lk_cb_status.name as chargeback_status
								, shr.reason as hold_reason, MAX(shr.create_on) as hold_date_time, shr.create_by as hold_staff
								, s.txn_id as payment_transaction_id
								, sps.payment_gateway_id
								, s.amount as order_value
								, sid.qty as item_quantity
								, sid.unit_price as item_value
								, s.currency_id as currency
								, p.name as product_name
								, cat.name as category_name
								, sps.payment_status
								, c.forename as client_forename
								, c.surname as client_surname
								, c.id as client_id
								, c.email
								, s.bill_name
								, s.bill_company
								, s.bill_address
								, s.bill_city
								, s.bill_state
								, s.bill_postcode
								, s.bill_country_id
								, s.delivery_name
								, s.delivery_company
								, s.delivery_address
								, s.delivery_city
								, s.delivery_state
								, s.delivery_postcode
								, s.delivery_country_id
								, c.`password`
								, c.tel_1, c.tel_2, c.tel_3
								, c.mobile
								, s.platform_id as order_type
								, s.delivery_type_id as delivery_mode
								, s.delivery_charge as delivery_cost
								, s.promotion_code
								, sps.card_id as payment_type
								, sor.risk_var1
								, sor.risk_var2
								, sor.risk_var3
								, sor.risk_var4
								, sor.risk_var5
								, sor.risk_var6
								, sor.risk_var7
								, sor.risk_var8
								, sor.risk_var9
								, sor.risk_var10
								, scc.t3m_is_sent as t3m_resp
								, scc.t3m_result as t3m_score
								, scc.card_bin
								, scc.card_type
								, sps.pay_to_account
								, sps.risk_ref1
								, sps.risk_ref2
								, sps.risk_ref3
								, sps.risk_ref4
								, s.create_at as ip_address
								, s.status as order_status
								, s.dispatch_date
								, ri.`status` as refund_status
								, ri.create_on as refund_date
								, rr.description as refund_reason');

			$this->db->join("so_hold_reason shr", "s.so_no=shr.so_no", "left");
			$this->db->join("so_payment_status sps", "sps.so_no=s.so_no", "left");
			$this->db->join("so_item_detail sid", "sid.so_no=s.so_no", "left");
			$this->db->join("product p", "p.sku=sid.item_sku", "inner");
			$this->db->join("category cat", "cat.id=p.cat_id" , "inner");
			$this->db->join("client c", "c.id=s.client_id", "left");
			$this->db->join("so_credit_chk scc", "scc.so_no=s.so_no", "left");
			$this->db->join("refund r", "r.so_no=s.so_no", "left");
			$this->db->join("refund_item ri", "r.id=ri.refund_id and ri.line_no=sid.line_no", "left");
			$this->db->join("refund_reason rr", "r.reason=rr.id", "left");
			$this->db->join("so_risk sor", "s.so_no=sor.so_no", "left");
			$this->db->join("chargeback cb", "s.so_no=cb.so_no", "inner");
			$this->db->join("lookup_chargeback_status lk_cb_status", "lk_cb_status.id = cb.chargeback_status_id", "left");
			$this->db->join("lookup_chargeback_reason lk_cb_reason", "lk_cb_reason.id = cb.chargeback_reason_id", "left");
			$this->db->join("lookup_chargeback_remark lk_cb_remark", "lk_cb_remark.id = cb.chargeback_remark_id", "left");
			$this->db->group_by("s.so_no","sid.item_sku");

			$current_so_number = "";
			$trace_back = $total_quantity = $i = 0;

			if($query = $this->db->get())
			{
			 	foreach ($query->result($classname) as $row)
				{
					// add up all the item qty for the same so_no
					$rs[$i] = $row;
					$rs[$i]->set_order_quantity($rs[$i]->get_item_quantity());
					if ($current_so_number == $row->get_so_no())
					{
						$trace_back++;
						$total_quantity += $rs[$i]->get_item_quantity();
						for ($j=($i - $trace_back);$j<=$i;$j++)
						{
							$rs[$j]->set_order_quantity($total_quantity);
						}
					}
					else
					{
						$trace_back = 0;
						$total_quantity = $rs[$i]->get_item_quantity();
					}
					$current_so_number = $row->get_so_no();
					$i++;
				}
			}
			return $rs;
		}
		else
		{
			return $rs;
		}
	}
}

/* End of file chargeback_dao.php */
/* Location: ./app/libraries/dao/Chargeback_dao.php */