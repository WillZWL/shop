<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Product_spec_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('service/product_spec_service');
		$this->load->library('service/unit_service');
		$this->load->library('service/language_service');
		$this->load->library('service/func_option_service');
	}

	public function get_prod_spec_group_list($where = array(), $option = array())
	{
		return $this->product_spec_service->get_prod_spec_group_list($where, $option);
	}

	public function get_prod_spec_list($where = array(), $option = array())
	{
		return $this->product_spec_service->get_prod_spec_list($where, $option);
	}

	public function get_unit_type_list($where = array(), $option = array())
	{
		return $this->unit_service->get_unit_type_list($where, $option);
	}

	public function get_no_of_row_psl($where = array(), $option = array())
	{
		return $this->product_spec_service->get_no_of_row_psl($where, $option);
	}

	public function get_prod_spec($where = array())
	{
		return $this->product_spec_service->get_prod_spec($where);
	}

	public function get_prod_spec_group($where = array())
	{
		return $this->product_spec_service->get_prod_spec_group($where);
	}

	public function add_prod_spec($obj)
	{
		return $this->product_spec_service->add_prod_spec($obj);
	}

	public function update_prod_spec($obj)
	{
		return $this->product_spec_service->update_prod_spec($obj);
	}

	public function get_lang_list($where=array(), $option=array())
	{
		return $this->language_service->get_list($where, $option);
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
						if ($data["func_opt_list"][$rs_lang_id][$rs_func_id]->get_text() == $rs_text)
						{
							continue;
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
/* End of file product_spec_model.php */
/* Location: ./app/models/marketing/product_spec_model.php */
