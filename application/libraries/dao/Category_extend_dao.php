<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

include_once 'Base_dao.php';

class Category_extend_dao extends Base_dao
{
	private $table_name = "category_extend";
	private $vo_class_name = "Category_extend_vo";
	private $seq_name = "";
	private $seq_mapping_field = "";

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

	public function get_cat_ext_default_w_key_list($where = array(), $option = array())
	{
		$this->db->from('category AS c');
		$this->db->join('language AS l', '1=1', 'INNER');
		$this->db->join('category_extend AS ce', 'c.id = ce.cat_id AND ce.lang_id = l.id', 'LEFT');
		$this->include_vo($this->get_vo_classname());
		return $this->common_get_list($where, $option, $this->get_vo_classname(), 'c.id AS cat_id, l.id AS lang_id, COALESCE(ce.name, c.name) AS name');
	}
}
