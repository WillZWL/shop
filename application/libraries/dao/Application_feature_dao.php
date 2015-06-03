<?php

include_once 'Base_dao.php';

class Application_feature_dao extends Base_dao
{
	private $table_name = "application_feature";
	private $vo_classname = "Application_feature_vo";
	private $seq_name = "";
	private $seq_mapping_field = "";

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

	public function get_application_feature_access_right($where = array(), $option = array(), $classname = "application_feature_right_dto")
	{
		$role = "'no'";
		if (isset($where['role_id']))
		{
			$role = "";
			$role_id_arr = $where['role_id'];
			foreach ($role_id_arr as $single_role)
			{
				$role .= "'" . $single_role . "',";
			}
			$role = substr($role, 0, (strlen($role) - 1));
		}
		$sql =
		"select
			af.*
		from
			application_feature_right afr
		inner join
			application_feature af
		on
			af.app_feature_id = afr.app_feature_id and af.status = 1 and afr.status = 1
		where
			1
		and
			role_id in ({$role})
		and
			app_id='{$where['app_id']}'
		";

//		print $sql;
		$rs = array();
		$this->include_dto($classname);
		if ($query = $this->db->query($sql))
		{
			foreach ($query->result($classname) as $obj)
			{
				$rs[] = $obj;
			}
			return empty($rs)?$rs:(object) $rs;
		}
		else
		{
			return FALSE;
		}
	}
}

/* End of file application_feature_dao.php */
/* Location: ./app/libraries/dao/Application_feature_dao.php */