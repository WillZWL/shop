<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Delayed_order_dao extends Base_dao
{
	private $table_name="delayed_order";
	private $vo_class_name="delayed_order_vo";
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

	public function get_all_minor_delay_order($where = array(), $option = array())
	{
		$this->db->from("so");
		$this->db->select("so.so_no");
		$this->db->join("so_payment_status sops", "sops.so_no = so.so_no", "INNER");
		return $this->common_get_list($where, $option, $classname="");
	}

	public function has_oos_status($where = array(), $option = array())
	{
		$this->db->from("so_hold_reason sohr");
		$this->db->select("sohr.so_no");
		return $this->common_get_list($where, $option, $classname="");
	}

	public function get_delay_order($where = array(), $option = array())
	{
		$this->db->from("delayed_order deor");
		$this->db->join("so", "so.so_no = deor.so_no", "INNER");
		$this->db->join("client", "client.id = so.client_id", "INNER");
		$this->db->select("deor.so_no, client.forename, client.country_id, so.platform_id, so.client_id, so.lang_id");
		return $this->common_get_list($where, $option, $classname="");
	}

	public function is_delay_order($where = array(), $option = array())
	{
		$this->db->from("delayed_order deor");
		return $this->common_get_list($where, $option, $classname="");
	}
}
/* End of file delayed_order_dao.php */
/* Location: ./system/application/libraries/dao/Delayed_order_dao.php */