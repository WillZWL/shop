<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Flex_refund_dao extends Base_dao
{
	private $table_name="flex_refund";
	private $vo_class_name="Flex_refund_vo";
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

	public function get_no_of_refund_status($so_no)
	{
		$sql = "SELECT count(*) total
				FROM
				(
					SELECT * FROM flex_refund WHERE so_no = '" . $so_no . "'
					GROUP BY status
				)a";

		if($query = $this->db->query($sql))
		{
			return $query->row()->total;
		}
	}

	public function get_refunds($where, $option)
	{
		$option["limit"] = -1;
		$this->db->from("flex_refund fr");
		$this->db->group_by("fr.so_no");
		return $this->common_get_list($where, $option, "flex_refund_vo", "so_no, flex_batch_id, gateway_id, internal_txn_id, txn_id, txn_time, currency_id, sum(amount) as amount, status, create_on, create_at, create_by, modify_on, modify_at, modify_by");
	}
}

/* End of file flex_refund_dao.php */
/* Location: ./system/application/libraries/dao/Flex_refund_dao.php */
