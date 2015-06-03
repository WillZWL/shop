<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class So_bank_transfer_dao extends Base_dao
{
	private $table_name="so_bank_transfer";
	private $vo_class_name="So_bank_transfer_vo";
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

	public function get_so_bank_transfer_list($where=array(), $option=array(), $type="", $received_amt="", $classname="Bank_transfer_list_dto")
	{
		# this function gives list of ALL so orders with so_payment_status.payment_gateway_id = 'w_bank_transfer'
		$this->db->from('so');

		$this->db->join('client AS c', 'c.id = so.client_id', 'INNER');
		$this->db->join('so_payment_status as sops', 'sops.so_no = so.so_no AND sops.payment_gateway_id = \'w_bank_transfer\'', 'INNER');
		$this->db->join('so_bank_transfer as sbt', 'sbt.so_no = so.so_no AND sbt.sbt_status = 1 ', 'LEFT');
		$this->db->join ('(	SELECT a.so_no, a.reason
								FROM so_hold_reason a
								JOIN (SELECT so_no, max(create_on) as create_on
									  FROM so_hold_reason
									  GROUP BY so_no) AS c
								ON a.create_on = c.create_on AND a.so_no = c.so_no
								) AS sohr','sohr.so_no = so.so_no','LEFT');
		// $this->db->join('bank_account as bankacc', 'sbt.so_no = so.so_no', 'INNER');
		if($type == "unpaid_on_hold")
		{
			# new so records, totally unpaid and on hold
			$where["so.status"] = 1;
			$where["sbt.net_diff_status IS NULL"] = NULL;
			$where["sops.payment_status"] = 'N';
			$where["so.hold_status"] = 2;
		}
		elseif($type == "all_and_hold")
		{
			# active so records, includes paid & unpaid, on-hold and not on-hold
			$where["(so.status != 0)"] = NULL;
			$where["so.hold_status in (0, 2)"] = NULL;
		}
		else
		{
			# active so records, not on hold, includes paid & unpaid
			$where["(so.status != 0)"] = NULL;
			$where["so.hold_status"] = 0;
		}

		if($received_amt)
		{
			$where["sbt.received_amt_localcurr"] = $received_amt;
		}
		$this->db->where($where);


		if (empty($option["num_rows"]))
		{
			$this->db->select('
						so.*,
						c.id,
						c.forename, c.surname, c.del_name, c.email, c.password,
						c.tel_1, c.tel_2, c.tel_3,
						c.del_tel_1, c.del_tel_2, c.del_tel_3,
						sops.payment_gateway_id,
						sbt.sbt_status,
						sbt.net_diff_status,
						sohr.reason,
						IFNULL(GROUP_CONCAT(CAST(sbt.ext_ref_no AS CHAR) SEPARATOR "||"), "") AS ext_ref_no,
						IFNULL(GROUP_CONCAT(CAST(sbt.received_amt_localcurr AS CHAR) SEPARATOR "||"), "") AS received_amt_localcurr,
						IFNULL(GROUP_CONCAT(CAST(sbt.bank_account_id AS CHAR) SEPARATOR "||"), "") AS bank_account_id,
						IFNULL(GROUP_CONCAT(CAST(sbt.received_date_localtime AS CHAR) SEPARATOR "||"), "") AS received_date_localtime,
						IFNULL(GROUP_CONCAT(CAST(sbt.bank_charge AS CHAR) SEPARATOR "||"), "") AS bank_charge,
						IFNULL(GROUP_CONCAT(CAST(sbt.notes AS CHAR) SEPARATOR "||"), "") AS notes,
						so.create_on AS so_create_on,
						so.create_at AS so_create_at,
						so.create_by AS so_create_by,
						so.modify_on AS so_modify_on,
						so.modify_at AS so_modify_at,
						so.modify_by AS so_modify_by,
						GROUP_CONCAT(CAST(sbt.create_on AS CHAR) SEPARATOR "||") AS sbt_create_on,
						GROUP_CONCAT(CAST(sbt.create_at AS CHAR) SEPARATOR "||") AS sbt_create_at,
						GROUP_CONCAT(CAST(sbt.create_by AS CHAR) SEPARATOR "||") AS sbt_create_by,
						GROUP_CONCAT(CAST(sbt.modify_on AS CHAR) SEPARATOR "||") AS sbt_modify_on,
						GROUP_CONCAT(CAST(sbt.modify_at AS CHAR) SEPARATOR "||") AS sbt_modify_at,
						GROUP_CONCAT(CAST(sbt.modify_by AS CHAR) SEPARATOR "||") AS sbt_modify_by
						'
						);

			$this->db->group_by("so.so_no");
			$this->include_dto($classname);

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
// $query = $this->db->get();
// var_dump($this->db->last_query());die();

			if ($query = $this->db->get())
			{
				foreach ($query->result($classname) as $obj)
				{
					$rs[] = $obj;
				}
				return (object) $rs;
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



	public function get_unknown_bank_transfer_list($where=array(), $option=array(), $type="", $received_amt="", $classname="Bank_transfer_list_dto")
	{
		$this->db->from('so_bank_transfer AS sbt');

		if (empty($option["num_rows"]))
		{
			$this->include_dto($classname);
			$where["sbt.sbt_status"] = 1;
			$where["sbt.so_no IS NULL"] = NULL;
			if($received_amt)
			{
				$where["sbt.received_amt_localcurr"] = $received_amt;
			}

			$this->db->where($where);

			$this->db->select('
							sbt.so_no, sbt.net_diff_status, sbt.ext_ref_no,
							sbt.received_amt_localcurr, sbt.bank_account_id, sbt.received_date_localtime,
							sbt.bank_charge, sbt.notes,
							sbt.create_on AS sbt_create_on,
							sbt.create_at AS sbt_create_at,
							sbt.create_by AS sbt_create_by,
							sbt.modify_on AS sbt_modify_on,
							sbt.modify_at AS sbt_modify_at,
							sbt.modify_by AS sbt_modify_by
							');

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
// $query = $this->db->get();
// var_dump($this->db->last_query());die();

			if ($query = $this->db->get())
			{
				foreach ($query->result($classname) as $obj)
				{
					$rs[] = $obj;
				}
				return (object) $rs;
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


	public function bank_transfer_report($where=array(), $receive_date=array(), $order_date=array(), $option=array(), $classname="Bank_transfer_list_dto")
	{
		// $receive_date = payment received date; so_bank_transfer.received_date_localtime
		$this->include_dto($classname);
		$this->db->from('so');
		$this->db->join('client as c', 'c.id = so.client_id', 'INNER');
		$this->db->join('so_payment_status as sops', 'sops.so_no = so.so_no AND sops.payment_gateway_id = \'w_bank_transfer\'', 'INNER');
		$this->db->join('so_bank_transfer as sbt', 'sbt.so_no = so.so_no', 'INNER');
		$this->db->join('bank_account as bank_acc', 'bank_acc.id = sbt.bank_account_id', 'INNER');

		# either empty (unpaid) record or is active record
		$where["sbt.sbt_status"] = 1;

		if($receive_date)
		{
			$this->db->where('sbt.received_date_localtime >=', $receive_date["from"]);
			$this->db->where('sbt.received_date_localtime <=', $receive_date["to"]);
		}
		if($order_date)
		{
			$this->db->where('so.order_create_date >=', $order_date["from"]);
			$this->db->where('so.order_create_date <=', $order_date["to"]);
		}
		$this->db->where($where);

		$this->db->select('
						so.so_no, so.amount, so.currency_id, so.order_create_date,
						CONCAT( IFNULL(c.forename,""), IFNULL(c.surname,"")) AS forename,
						c.del_name,
						c.email,
						sbt.net_diff_status,
						IFNULL(GROUP_CONCAT(CAST(sbt.ext_ref_no AS CHAR) ORDER BY sbt.received_date_localtime SEPARATOR "||"), "") AS ext_ref_no,
						IFNULL(GROUP_CONCAT(CAST(sbt.received_amt_localcurr AS CHAR) ORDER BY sbt.received_date_localtime SEPARATOR "||"), "") AS received_amt_localcurr,
						IFNULL(GROUP_CONCAT(CAST(bank_acc.acc_no AS CHAR) ORDER BY sbt.received_date_localtime SEPARATOR "||"), "") AS bank_account_no,
						IFNULL(GROUP_CONCAT(CAST(sbt.received_date_localtime AS CHAR) ORDER BY sbt.received_date_localtime SEPARATOR "||"), "") AS received_date_localtime,
						IFNULL(GROUP_CONCAT(CAST(sbt.bank_charge AS CHAR) ORDER BY sbt.received_date_localtime SEPARATOR "||"), "") AS bank_charge,
						IFNULL(GROUP_CONCAT(CAST(sbt.notes AS CHAR) ORDER BY sbt.received_date_localtime SEPARATOR "||"), "") AS notes,
						GROUP_CONCAT(CAST(sbt.create_on AS CHAR) ORDER BY sbt.received_date_localtime SEPARATOR "||") AS sbt_create_on,
						GROUP_CONCAT(CAST(sbt.create_at AS CHAR) ORDER BY sbt.received_date_localtime SEPARATOR "||") AS sbt_create_at,
						GROUP_CONCAT(CAST(sbt.create_by AS CHAR) ORDER BY sbt.received_date_localtime SEPARATOR "||") AS sbt_create_by,
						GROUP_CONCAT(CAST(sbt.modify_on AS CHAR) ORDER BY sbt.received_date_localtime SEPARATOR "||") AS sbt_modify_on,
						GROUP_CONCAT(CAST(sbt.modify_at AS CHAR) ORDER BY sbt.received_date_localtime SEPARATOR "||") AS sbt_modify_at,
						GROUP_CONCAT(CAST(sbt.modify_by AS CHAR) ORDER BY sbt.received_date_localtime SEPARATOR "||") AS sbt_modify_by
						'
						);

		$this->db->group_by("so.so_no");

		$rs = array();
// $query = $this->db->get();
// var_dump($this->db->last_query());die();

		if ($query = $this->db->get())
		{
			foreach ($query->result($classname) as $obj)
			{
				$rs[] = $obj;
			}
			// echo "<pre>"; var_dump($rs);
			return (object) $rs;
		}

	}

}
/* End of file so_bank_transfer_dao.php */
/* Location: ./system/application/libraries/dao/So_bank_transfer_dao.php */
