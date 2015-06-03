<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Refund_history_dao extends Base_dao
{
	private $table_name="refund_history";
	private $vo_class_name="Refund_history_vo";
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

	public function get_history_list($where = array(), $classname="Refund_hist_uname_dto")
	{
		$sql = "SELECT h.*, s.reason, u.username, rr.description, rr.reason_cat
				FROM refund_history h
				JOIN (
					SELECT id, reason
					FROM refund
					WHERE so_no = ?
					) as s
				ON s.id = h.refund_id
				JOIN user u
					ON u.id = h.create_by
				JOIN refund_reason rr
					ON rr.id = s.reason";
		if($where["refund_id"] != NULL)
		{
			$sql .= " WHERE h.refund_id = '".$where["refund_id"]."'";
		}
		$sql .=	" ORDER BY h.create_on ASC";

		$this->include_dto($classname);

		$rs = array();

		if($query = $this->db->query($sql, $where["so_no"]))
		{
			foreach($query->result($classname) as $obj)
			{
				$rs[] = $obj;
			}
			return $rs;
		}
		echo $this->db->last_query()." ".$this->db->_error_message();
		return FALSE;
	}
}