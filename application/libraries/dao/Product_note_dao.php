<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Product_note_dao extends Base_dao
{
	private $table_name="product_note";
	private $vo_class_name="Product_note_vo";
	private $seq_name="";
	private $seq_mapping_field="";

	public function __construct(){
		parent::__construct();
	}

	public function get_vo_classname(){
		return $this->vo_class_name;
	}

	public function get_table_name(){
		return $this->table_name;
	}

	public function get_seq_name(){
		return $this->seq_name;
	}

	public function get_seq_mapping_field(){
		return $this->seq_mapping_field;
	}

	public function get_note_with_author_name($platform="", $sku, $type, $classname="Product_note_user_dto")
	{
		$this->include_dto($classname);

		$sql = "SELECT *
				FROM
				(
					SELECT n.*, u.username
					FROM product_note n
					JOIN user u
						ON u.id = n.create_by
					WHERE sku = ?
					AND type = ?
				";
		$where= array($sku, $type);

		if ($platform != "")
		{
			$sql .= "	AND platform_id = ?";
			$where[] = $platform;
		}

		$sql .= "	ORDER BY create_on DESC
					LIMIT 5
				) n
				ORDER BY create_on ASC
				";
		$rs = array();
		if ($query = $this->db->query($sql, $where))
		{
			foreach ($query->result($classname) as $obj)
			{
				$rs[] = $obj;
			}
			return (object) $rs;
		}
		else
		{
			return FALSE;
		}
	}

}
/* End of file product_note_dao.php */
/* Location: ./system/application/libraries/dao/Currency_dao.php */