<?php
class Promotion_code extends MY_Controller
{

	private $app_id="MKT0017";
	private $lang_id="en";


	public function __construct()
	{
		parent::__construct();
		$this->load->model('marketing/promotion_code_model');
		$this->load->helper(array('url', 'notice', 'object', 'operator'));
		$this->load->library('service/pagination_service');
		$this->load->model('mastercfg/region_model');
	}

	public function index()
	{
		$sub_app_id = $this->_get_app_id()."00";
		$_SESSION["LISTPAGE"] = current_url()."?".$_SERVER['QUERY_STRING'];

		$where = array();
		$option = array();

		$submit_search = 0;

		if ($this->input->get("code") != "")
		{
			$where["code LIKE "] = "%".$this->input->get("code")."%";
			$submit_search = 1;
		}

		if ($this->input->get("description") != "")
		{
			$where["description LIKE "] = "%".$this->input->get("description")."%";
			$submit_search = 1;
		}


		if ($this->input->get("expire_date") != "")
		{
			fetch_operator($where, "expire_date", $this->input->get("expire_date"));
			$submit_search = 1;
		}

		if ($this->input->get("no_taken") != "")
		{
			fetch_operator($where, "no_taken", $this->input->get("no_taken"));
			$submit_search = 1;
		}


		if ($this->input->get("status") !="")
		{
			$where["status"] = $this->input->get("status");
			$submit_search = 1;
		}

		$sort = $this->input->get("sort");
		$order = $this->input->get("order");

		$limit = '20';

		$pconfig['base_url'] = $_SESSION["LISTPAGE"];
		$option["limit"] = $pconfig['per_page'] = $limit;
		if ($option["limit"])
		{
			$option["offset"] = $this->input->get("per_page");
		}

		if (empty($sort))
		{
			$sort = "expire_date desc, create_on";
		}

		if (empty($order))
		{
			$order = "desc";
		}

		$option["orderby"] = $sort." ".$order;

		$data["objlist"] = $this->promotion_code_service->get_list($where, $option);
		$data["total"] = $this->promotion_code_service->get_num_rows($where);

		include_once(APPPATH."language/".$sub_app_id."_".$this->_get_lang_id().".php");
		$data["lang"] = $lang;

		$pconfig['total_rows'] = $data['total'];
		$this->pagination_service->set_show_count_tag(TRUE);
		$this->pagination_service->initialize($pconfig);

		$data["notice"] = notice($lang);

		$data["sortimg"][$sort] = "<img src='".base_url()."images/".$order.".gif'>";
		$data["xsort"][$sort] = $order=="asc"?"desc":"asc";
//		$data["searchdisplay"] = ($submit_search)?"":'style="display:none"';
		$data["searchdisplay"] = "";
		$this->load->view('marketing/promotion_code/promotion_code_index_v', $data);
	}

	public function add()
	{

		$sub_app_id = $this->_get_app_id()."01";

		if ($this->input->post("posted"))
		{
			if (isset($_SESSION["promotion_code_vo"]))
			{
				$this->promotion_code_service->include_vo();
				$data["promotion_code"] = unserialize($_SESSION["promotion_code_vo"]);
				set_value($data["promotion_code"], $_POST);
				foreach ($_POST["relevant_prod"] as $d_product)
				{
					if ($d_product != "")
					{
						$relevant_prod[] = $d_product;
					}
				}
				if ($_POST['disc_type'] == 'FD')
				{
					$disc_level_value = $_POST['disc_level_value']['FD'];
				}
				else
				{
					switch ($_POST["disc_level"])
					{
						case "SCAT":
							$disc_level_value = $_POST["disc_level_value"]["CAT"].",".$_POST["disc_level_value"][$_POST["disc_level"]];
							break;
						case "SSCAT":
							$disc_level_value = $_POST["disc_level_value"]["CAT"].",".$_POST["disc_level_value"]["SCAT"].",".$_POST["disc_level_value"][$_POST["disc_level"]];
							break;
						case "PD":
							$disc_level_value = trim(@implode(",", $_POST["disc_level_value"][$_POST["disc_level"]]), ',');
							break;
						default:
							$disc_level_value = $_POST["disc_level_value"][$_POST["disc_level"]];
					}
				}
				$data["promotion_code"]->set_disc_level_value($disc_level_value);
				$data["promotion_code"]->set_relevant_prod(trim(@implode(",", $relevant_prod), ','));
				if (substr($prefix = rtrim($this->input->post("prefix")), -1) == "%")
				{
					$new_promotion_code = substr($prefix, 0, -1).hash("crc32", mktime());
				}
				else
				{
					$new_promotion_code = $prefix;
				}
				$data["promotion_code"]->set_code($new_promotion_code);
				if ($new_obj = $this->promotion_code_service->insert($data["promotion_code"]))
				{
					unset($_SESSION["promotion_code_vo"]);
					redirect($_SESSION["LISTPAGE"]);
				}
				else
				{
					$_SESSION["NOTICE"] = $this->db->_error_message();
				}
			}
		}

		include_once(APPPATH."language/".$sub_app_id."_".$this->_get_lang_id().".php");
		$data["lang"] = $lang;

		if (empty($data["promotion_code"]))
		{
			if (($data["promotion_code"] = $this->promotion_code_service->get()) === FALSE)
			{
				$_SESSION["NOTICE"] = $this->db->_error_message();
			}
			else
			{
				$_SESSION["promotion_code_vo"] = serialize($data["promotion_code"]);
			}
		}

		$data["country_list"] =  $this->region_service->get_sell_country_list();
		$data['delivery_option_list'] = $this->promotion_code_model->get_delivery_option_list();
		$data["notice"] = notice($lang);
		$data["cmd"] = "add";
		$this->load->view('marketing/promotion_code/promotion_code_detail_v',$data);
	}

	public function view($code="")
	{
		if ($code)
		{
			$sub_app_id = $this->_get_app_id()."02";

			if ($this->input->post("posted"))
			{
				unset($_SESSION["NOTICE"]);
				if ($data["promotion_code"] = $this->promotion_code_service->get(array("code"=>$code)))
				{
					$this->promotion_code_service->include_vo();
					set_value($data["promotion_code"], $_POST);
					foreach ($_POST["relevant_prod"] as $d_product)
					{
						if ($d_product != "")
						{
							$relevant_prod[] = $d_product;
						}
					}
					if ($_POST['disc_type'] == 'FD')
					{
						$disc_level_value = $_POST['disc_level_value']['FD'];
					}
					else
					{
						switch ($_POST["disc_level"])
						{
							case "SCAT":
								$disc_level_value = $_POST["disc_level_value"]["CAT"].",".$_POST["disc_level_value"][$_POST["disc_level"]];
								break;
							case "SSCAT":
								$disc_level_value = $_POST["disc_level_value"]["CAT"].",".$_POST["disc_level_value"]["SCAT"].",".$_POST["disc_level_value"][$_POST["disc_level"]];
								break;
							case "PD":
								$disc_level_value = trim(@implode(",", $_POST["disc_level_value"][$_POST["disc_level"]]), ',');
								break;
							default:
								$disc_level_value = $_POST["disc_level_value"][$_POST["disc_level"]];
						}
					}
					$data["promotion_code"]->set_disc_level_value($disc_level_value);
					$data["promotion_code"]->set_relevant_prod(trim(@implode(",", $relevant_prod), ','));

					if ($this->promotion_code_service->update($data["promotion_code"]))
					{
						unset($_SESSION["promotion_code_obj"]);
						redirect(base_url()."marketing/promotion_code/view/".$code);
					}
					else
					{
						$_SESSION["NOTICE"] = $this->db->_error_message();
					}
				}
			}

			include_once(APPPATH."language/".$sub_app_id."_".$this->_get_lang_id().".php");
			$data["lang"] = $lang;

			if (empty($data["promotion_code"]))
			{
				if (($data["promotion_code"] = $this->promotion_code_service->get(array("code"=>$code))) === FALSE)
				{
					$_SESSION["NOTICE"] = $this->db->_error_message();
				}
				else
				{
					unset($_SESSION["promotion_code_obj"]);
					$_SESSION["promotion_code_obj"][$code] = serialize($data["promotion_code"]);
				}
			}

			$data["country_list"] =  $this->region_service->get_sell_country_list();
			$data['delivery_option_list'] = $this->promotion_code_model->get_delivery_option_list();
			$data["notice"] = notice($lang);
			$data["cmd"] = "edit";
			$this->load->view('marketing/promotion_code/promotion_code_detail_v',$data);
		}
	}

	public function _get_app_id(){
		return $this->app_id;
	}

	public function _get_lang_id(){
		return $this->lang_id;
	}
}

/* End of file promotion_code.php */
/* Location: ./system/application/controllers/promotion_code.php */