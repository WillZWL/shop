<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Graphic_dao extends Base_dao
{
	private $table_name="graphic";
	private $vo_classname="Graphic_vo";
	private $seq_name="graphic";
	private $seq_mapping_field="id";

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
/* End of file graphic_dao.php */
/* Location: ./app/libraries/dao/Graphic_dao.php */