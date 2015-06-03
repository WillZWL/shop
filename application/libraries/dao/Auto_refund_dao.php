<?php

include_once 'Base_dao.php';

class Auto_refund_dao extends Base_dao
{
	private $table_name="auto_refund";
	private $vo_classname="Auto_refund_vo";
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
}

/* End of file auto_refund_dao.php */
/* Location: ./app/libraries/dao/Auto_refund_dao.php */