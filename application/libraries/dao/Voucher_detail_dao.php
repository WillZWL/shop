<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Voucher_detail_dao extends Base_dao
{
	private $table_name="voucher_detail";
	private $vo_classname="Voucher_detail_vo";
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
/* End of file voucher_detail_dao.php */
/* Location: ./app/libraries/dao/Voucher_detail_dao.php */