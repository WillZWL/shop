<?php

include_once 'Base_dao.php';

class Interface_flex_rolling_reserve_dao extends Base_dao
{
	private $table_name="interface_flex_rolling_reserve";
	private $vo_classname="Interface_flex_rolling_reserve_vo";
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


	public function get_rolling_reserve_by_batch($batchId)
	{
		$option['limit'] = -1;
		$this->db->from("interface_flex_rolling_reserve AS ifrr");
		$this->db->group_by("ifrr.so_no, ifrr.txn_id, ifrr.status,ifrr.txn_time");
		$where["flex_batch_id"] = $batchId;
		$this->include_vo();
		return $this->common_get_list($where, $option, "Interface_flex_rolling_reserve_vo", 'trans_id, so_no, flex_batch_id, gateway_id, txn_id, internal_txn_id, txn_time, currency_id, sum(amount) as amount, status, batch_status, create_on, create_at, create_by, modify_at, modify_by');

	}
}

/* End of file interface_flex_rolling_reserve_dao.php */
/* Location: ./app/libraries/dao/Interface_flex_rolling_reserve_dao.php */