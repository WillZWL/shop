<?php
defined('BASEPATH') OR exit('No direct script access allowed');

abstract class Base_dao
{
	abstract function get_vo_classname();
	abstract function get_table_name();
	abstract function get_seq_name();
	abstract function get_seq_mapping_field();

	var $rows_limit;
	private $sequence_table;

	public function __construct($db = "")
	{
		$CI =& get_instance();
		if ($db) {
			$this->db = $CI->load->database($db, TRUE);
		} else {
			$CI->load->database();
			$this->db =& $CI->db;
		}
		$this->rows_limit = $CI->config->item('rows_limit');
	}

	public function get_list($where = array(), $option = array(), $classname = "")
	{
		if (isset($option["orderby"])) {
			if ($this->db->_has_operator($option["orderby"])) {
				$this->db->_protect_identifiers = FALSE;
			}
			$this->db->order_by($option["orderby"]);
		}

		if (empty($option["limit"])) {
			$option["limit"] = $this->rows_limit;
		} elseif ($option["limit"] == -1) {
			$option["limit"] = "";
		}

		if (!isset($option["offset"])) {
			$option["offset"] = 0;
		}

		$vo_classname = $this->get_vo_classname();
		$vo_file = APPPATH."libraries/vo/".ucfirst($vo_classname).".php";
		if (file_exists($vo_file)) {
			include_once($vo_file);
			if ($query = $this->db->get_where($this->get_table_name(), $where, $option["limit"], $option["offset"])) {
				$rs = array();
				if ($classname == "") {
					$classname = $vo_classname;
				}
				foreach ($query->result($classname) as $obj) {
					$rs[] = $obj;
				}
				if ($option["limit"] == 1) {
					return $rs[0];
				} else {
					if (empty($option["result_type"]) && empty($option["array_list"])) {
						return (object) $rs;
					} else {
						return $rs;
					}
				}
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	public function common_get_list($where = array(), $option = array(), $classname, $select = NULL)
	{
		if ($where)
		{
			$this->db->where($where);
		}

		if (empty($option["num_rows"]))
		{
			if (isset($option["orderby"]))
			{
				$this->db->order_by($option["orderby"]);
			}

			if (empty($option["limit"]))
			{
				$option["limit"] = $this->rows_limit;
			}
			elseif ($option["limit"] == -1)
			{
				$option["limit"] = "";
			}

			if (!isset($option["offset"]))
			{
				$option["offset"] = 0;
			}

			if ($this->rows_limit != "")
			{
				$this->db->limit($option["limit"], $option["offset"]);
			}

			$rs = array();

			if ($select != NULL)
				$this->db->select($select, FALSE);

			if ($query = $this->db->get())
			{
				foreach ($query->result($classname) as $obj)
				{
					$rs[] = $obj;
				}
				if ($option["limit"] == 1)
				{
					return $rs[0];
				}
				else
				{
					if ($rs && empty($option["result_type"]) && empty($option["array_list"]))
					{
						return (object) $rs;
					}
					else
					{
						return $rs;
					}
				}
			}
		}
		else
		{
			$this->db->select('COUNT(*) AS total');
			if ($query = $this->db->get())
			{
				return $query->row()->total;
			}
		}

		return FALSE;
	}

	public function get_num_rows($where=array())
	{
		$this->db->select('COUNT(*) AS total');
		if ($query = $this->db->get_where($this->get_table_name(), $where))
		{
			return $query->row()->total;
		}
		else
		{
			return FALSE;
		}
	}

	public function get_db_time()
	{
		$sql  = "SELECT NOW() AS dbtime";

		if ($query = $this->db->query($sql))
		{
			return $query->row()->dbtime;
		}
		else
		{
			return FALSE;
		}
	}

	public function get($where = array(), $classname = "")
	{
		if ($classname == "") {
			$classname = $this->get_vo_classname();
			$rs_include = $this->include_vo();
		} else {
			$rs_include = $this->include_dto($classname);
		}

		if ($rs_include) {
			if (empty($where)) {
				@$obj= new $classname();
				if ($obj) {
					return $obj;
				} else {
					return FALSE;
				}
			} else {
				if ($query = $this->db->get_where($this->get_table_name(), $where, 1, 0)) {
					$rs = $query->result($classname);

					if (empty($rs)) {
						return $rs;
					} else {
						return $rs[0];
					}
				} else {
					return FALSE;
				}
			}
		} else {
			return FALSE;
		}
	}

	public function include_vo()
	{
		$vo_file = APPPATH."libraries/vo/".ucfirst($this->get_vo_classname()).".php";
		if (file_exists($vo_file))
		{
			include_once($vo_file);
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	public function include_dto($dto_name)
	{
		$file_path = APPPATH."libraries/dto/".ucfirst($dto_name).".php";
		if(file_exists($file_path))
		{
			include_once $file_path;
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	public function seq_next_val()
	{
		include_once(APPPATH."libraries/service/Context_config_service.php");
		$cconfig = new Context_config_service();
		$this->sequence_table = $cconfig->value_of("sequence_table");

		if ($this->get_seq_name() != "")
		{
			$this->db->where('seq_name', $this->get_seq_name());
			$query = $this->db->get($this->sequence_table);
			$row = $query->row();
			return $row->value+$row->increment_level;
		}
		else
		{
			return FALSE;
		}
	}

	public function update_seq($new_value)
	{
		$this->db->where('seq_name', $this->get_seq_name());
		if ($this->db->update($this->sequence_table, array('value'=>$new_value)))
		{
			//Tommy added: commit
			if ($this->db->trans_autocommit)
			{
			    $this->db->trans_commit();
			}
			return TRUE;
		}
		else
		{
			if ($this->db->trans_autocommit)
			{
				$this->db->trans_rollback();
			    $this->db->trans_commit();
			}
			return FALSE;
		}
	}

	public function insert($obj=NULL, $use_increment=TRUE)
	{
		if ( ! empty($obj)) {

			$class_methods = get_class_methods($obj);
			if ( ! empty($class_methods)) {
				$ic_field = $obj->_get_increment_field();
				if ($ic_field != "" && $use_increment) {
					call_user_func(array($obj, "set_".$ic_field), '');
				}

				$this->set_create($obj);

				$new_value = FALSE;
				foreach ($class_methods as $fct_name) {
					if (substr($fct_name, 0, 4) == "get_") {
						$rsvalue = call_user_func(array($obj, $fct_name));
						$rskey = substr($fct_name,4);
						if ($rskey == $this->get_seq_mapping_field() && call_user_func(array($obj, "get_".$rskey)) == "" && ($new_value = $this->seq_next_val())) {
							$rsvalue = $new_value;
							call_user_func(array($obj, "set_".$rskey), $rsvalue);
						}
						$this->db->set($rskey, $rsvalue);
					}
				}


				if ($this->db->insert($this->get_table_name())) {
					if ($ic_field != "" && call_user_func(array($obj, "get_".$ic_field))==0) {
						call_user_func(array($obj, "set_".$ic_field), $this->db->insert_id());
					}
					//Tommy commented: no use if have prefix / suffix
/*					if ($new_value)
					{
						$this->update_seq($new_value);
					}*/
					return $obj;
				} else {
					return FALSE;
				}
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	public function update($obj = NULL, $where = array())
	{
		if ( ! empty($obj)) {
			$class_methods = get_class_methods($obj);
			if ( ! empty($class_methods)) {
				$this->set_modify($obj);
				$primary_key = $obj->_get_primary_key();
				foreach ($class_methods as $fct_name) {
					if (substr($fct_name, 0, 4) == "get_") {
						$rsvalue = call_user_func(array($obj, $fct_name));
						$rskey = substr($fct_name,4);
						// Tommy: Should be always 1 on 1 update.
						// if (empty($where) && in_array($rskey, $primary_key))
						if (in_array($rskey, $primary_key)) {
							$this->db->where($rskey, $rsvalue);
						}
						//echo $rskey." ".$rsvalue."<br>";
						$this->db->set($rskey, $rsvalue);
					}
				}

				if (!empty($where)) {
					$this->db->where($where);
				}

				if ($this->db->update($this->get_table_name())) {
					$affected = $this->db->affected_rows();
					return $affected;
				} else {
					return FALSE;
				}
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	public function delete(Base_vo $obj)
	{
		$class_methods = get_class_methods($obj);
		foreach ($class_methods as $fct_name)
		{

			if (substr($fct_name,0,4) == "get_")
			{
				$rsvalue = call_user_func(array($obj, $fct_name));
				$rskey = substr($fct_name,4);
				$this->db->where($rskey, $rsvalue);
			}
		}
		if ($this->db->delete($this->get_table_name()))
		{
			$affected = $this->db->affected_rows();
			if ($this->db->trans_autocommit)
			{
			    $this->db->trans_commit();
			}
			return $affected;
		}
		else
		{
			if ($this->db->trans_autocommit)
			{
				$this->db->trans_rollback();
			    $this->db->trans_commit();
			}
			return FALSE;
		}
	}

	public function q_delete($where=array())
	{

		if (!empty($where))
		{
			$this->db->where($where);
			echo $this->db->query;
			if ($this->db->delete($this->get_table_name()))
			{
				$affected = $this->db->affected_rows();
				if ($this->db->trans_autocommit)
				{
				    $this->db->trans_commit();
				}
				return $affected;
			}
			else
			{
				if ($this->db->trans_autocommit)
				{
					$this->db->trans_rollback();
				    $this->db->trans_commit();
				}
				return FALSE;
			}
		}
		else
		{
			return FALSE;
		}
	}

	public function q_insert($data=array())
	{
		if (!empty($data))
		{
			if ($this->db->insert($this->get_table_name(), $data))
			{
				if ($this->db->trans_autocommit)
				{
				    $this->db->trans_commit();
				}
				return $this->db->insert_id();
			}
			else
			{
				if ($this->db->trans_autocommit)
				{
					$this->db->trans_rollback();
				    $this->db->trans_commit();
				}
				return FALSE;
			}
		}
		else
		{
			return FALSE;
		}
	}

	public function q_update($where=array(), $data=array())
	{
		if (!(empty($where) || empty($data)))
		{
			$this->db->where($where);
			if ($this->db->update($this->get_table_name(), $data))
			{
				$affected = $this->db->affected_rows();
				if ($this->db->trans_autocommit)
				{
				    $this->db->trans_commit();
				}
				return $affected;
			}
			else
			{
				if ($this->db->trans_autocommit)
				{
					$this->db->trans_rollback();
				    $this->db->trans_commit();
				}
				return FALSE;
			}
		}
		else
		{
			return FALSE;
		}
	}

	public function get_max_modify($table_list)
	{
		if (is_string($table_list))
		{
			$table_list = (array)$table_list;
		}

		foreach ($table_list as $table)
		{
			$max_str[] = "(SELECT MAX(modify_on) FROM {$table})";
		}

		$sql = "SELECT GREATEST (".implode(",", $max_str).") AS last_modify";

		if ($query = $this->db->query($sql))
		{
			return $query->row()->last_modify;
		}

		return FALSE;
	}

	public function set_create(&$obj, $value=array())
	{
		$ts = date("Y-m-d H:i:s");
		$ip = $_SERVER["REMOTE_ADDR"]?$_SERVER["REMOTE_ADDR"]:"127.0.0.1";
		$id = empty($_SESSION["user"]["id"])?"system":$_SESSION["user"]["id"];
		@call_user_func(array($obj, "set_create_on"), $ts);
		@call_user_func(array($obj, "set_create_at"), $ip);
		@call_user_func(array($obj, "set_create_by"), $id);
		@call_user_func(array($obj, "set_modify_on"), $ts);
		@call_user_func(array($obj, "set_modify_at"), $ip);
		@call_user_func(array($obj, "set_modify_by"), $id);
	}

	public function set_modify(&$obj, $value=array())
	{
		$ts = date("Y-m-d H:i:s");
		$ip = $_SERVER["REMOTE_ADDR"]?$_SERVER["REMOTE_ADDR"]:"127.0.0.1";
		$id = empty($_SESSION["user"]["id"])?"system":$_SESSION["user"]["id"];
		@call_user_func(array($obj, "set_modify_on"), $ts);
		@call_user_func(array($obj, "set_modify_at"), $ip);
		@call_user_func(array($obj, "set_modify_by"), $id);
	}

	public function trans_start()
	{
		if (!$this->db->reged_function)
		{
			register_shutdown_function(array($this, "_fatal_handler"));
			$this->db->reged_function = 1;
		}
		$this->db->trans_autocommit = 0;
		$this->db->_trans_status = TRUE;
		$this->db->trans_start();
	}

	public function trans_complete()
	{
		$this->db->trans_complete();
		$this->db->trans_autocommit = 1;
	}

	public function trans_rollback()
	{
		$this->db->trans_rollback();
	}

	public function _fatal_handler()
	{
		$e = error_get_last();
		if ($e["type"] == 1)
		{
			$this->db->trans_rollback();
		}
		exit;
	}
}
