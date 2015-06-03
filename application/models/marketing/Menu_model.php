<?php
class Menu_model extends CI_Model{

	function __construct(){
		parent::__construct();
		$this->load->library('service/category_service');
		$this->load->library('service/menu_service');
		$this->load->library('service/func_option_service');
	}

	public function get($where=array())
	{
		return $this->menu_service->get($where);
	}

	public function add_menu_item($obj)
	{
		return $this->menu_service->insert($obj);
	}

	public function update_menu_item($obj)
	{
		return $this->menu_service->update($obj);
	}

	public function get_lang_list($where=array(), $option=array())
	{
		return $this->language_service->get_list($where, $option);
	}

	public function get_list($where=array(), $option=array())
	{
		return $this->menu_service->get_list($where, $option);
	}

	public function get_menu_list($where=array(), $option=array())
	{
		return $this->category_service->get_menu_list($where, $option);
	}

	public function get_menu_list_w_lang($lang_id="en", $option=array())
	{
		return $this->category_service->get_menu_list_w_lang($lang_id, $option);
	}

	public function get_menu_list_w_platform_id($lang_id="en", $platform_id="WEBGB", $option=array())
	{
		return $this->category_service->get_menu_list_w_platform_id($lang_id, $platform_id, $option);
	}

	public function get_footer_menu_list($lang_id="en", $where=array(), $option=array())
	{
		return $this->menu_service->get_footer_menu_list($lang_id, $where, $option);
	}

	public function check_serialize($name, &$data, $where = array())
	{
		switch ($name)
		{
			case "func_opt_list":
				if (empty($data["func_opt_list"]))
				{
					if (($data["func_opt_list"] = $this->func_option_service->get_list_w_key($where, array("limit"=>-1))) === FALSE)
					{
						$_SESSION["NOTICE"] = $this->db->_error_message();
					}
					else
					{
						$_SESSION["func_opt_list"] = serialize($data["func_opt_list"]);
					}
				}
			break;
		}
	}

	public function get_func_option($where=array())
	{
		return $this->func_option_service->get($where);
	}

	public function insert_func_opt($obj)
	{
		return $this->func_option_service->insert($obj);
	}

	public function update_content($vo, &$data)
	{
		foreach ($_POST["func_opt"] as $rs_lang_id=>$rs_func_list)
		{
			foreach ($rs_func_list as $rs_func_id=>$rs_id_list)
			{
				foreach ($rs_id_list as $rs_id=>$rs_text)
				{
					if ($rs_id == "new")
					{
						if ($rs_text != "")
						{
							$data["func_opt_list"][$rs_lang_id][$rs_func_id] = clone $vo["func_opt"];
							$data["func_opt_list"][$rs_lang_id][$rs_func_id]->set_func_id($rs_func_id);
							$data["func_opt_list"][$rs_lang_id][$rs_func_id]->set_lang_id($rs_lang_id);
							$data["func_opt_list"][$rs_lang_id][$rs_func_id]->set_text($rs_text);
							if (!$this->func_option_service->insert($data["func_opt_list"][$rs_lang_id][$rs_func_id]))
							{
								$_SESSION["NOTICE"] = "ERROR: ".str_replace(APPPATH, "", __FILE__)."@".__LINE__." ".$this->db->_error_message();
								return FALSE;
							}
						}
					}
					else
					{
						if ($data["func_opt_list"][$rs_lang_id][$rs_func_id])
						{
							if ($data["func_opt_list"][$rs_lang_id][$rs_func_id]->get_text() == $rs_text)
							{
								continue;
							}
						}
						elseif ($rs_text == "")
						{
							if (!$this->func_option_service->q_delete(array("id"=>$rs_id)))
							{
								$_SESSION["NOTICE"] = "ERROR: ".str_replace(APPPATH, "", __FILE__)."@".__LINE__." ".$this->db->_error_message();
								return FALSE;
							}
						}
						else
						{
							$data["func_opt_list"][$rs_lang_id][$rs_func_id]->set_text($rs_text);
							if(!$this->func_option_service->update($data["func_opt_list"][$rs_lang_id][$rs_func_id]))
							{
								$_SESSION["NOTICE"] = "ERROR: ".str_replace(APPPATH, "", __FILE__)."@".__LINE__." ".$this->db->_error_message();
								return FALSE;
							}
						}
					}
				}
			}
		}
	}
}

/* End of file menu_model.php */
/* Location: ./system/application/models/menu/menu_model.php */