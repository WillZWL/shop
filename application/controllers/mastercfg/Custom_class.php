<?php
class Custom_class extends MY_Controller
{

	private $app_id="MST0008";
	private $lang_id="en";


	public function __construct()
	{
		parent::__construct();
		$this->load->model('mastercfg/custom_class_model');
		$this->load->helper(array('url', 'notice', 'object', 'operator'));
		$this->load->library('service/pagination_service');
	}

	public function index($country_id="", $cc_id="")
	{
		$sub_app_id = $this->_get_app_id()."00";

		$_SESSION["LISTPAGE"] = base_url()."mastercfg/custom_class/".($region_id==""?"":"index/".$region_id).($cc_id==""?"":"/".$cc_id)."?".$_SERVER['QUERY_STRING'];

		$where = array();
		$option = array();

		$submit_search=0;

		if ($country_id != "")
		{
			$where["country_id"] = $country_id;
		}
		if ($this->input->get("id") != "")
		{
			$where["id"] = $this->input->get("id");
			$submit_search=1;
		}
		if ($this->input->get("code") != "")
		{
			$where["code LIKE "] = "%".$this->input->get("code")."%";
			$submit_search=1;
		}
		if ($this->input->get("description") != "")
		{
			$where["description LIKE "] = "%".$this->input->get("description")."%";
			$submit_search=1;
		}
		if ($this->input->get("duty_pcent") != "")
		{
			fetch_operator($where, "duty_pcent", $this->input->get("duty_pcent"));
			$submit_search=1;
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
			$sort = "id";

		if (empty($order))
			$order = "asc";

		$option["orderby"] = $sort." ".$order;

		if ($country_id)
		{
			$data = $this->custom_class_model->get_cc_list($where, $option);
		}

		$data["countrylist"] = $this->custom_class_model->get_country_list(array("status"=>1), array("limit"=>-1, "orderby"=>"name"));

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


		if (empty($_SESSION["cc_vo"]))
		{
			if (($cc_vo = $this->custom_class_model->get_cc()) === FALSE)
			{
				$_SESSION["NOTICE"] = "sql_error";
			}
			else
			{
				$_SESSION["cc_vo"] = serialize($cc_vo);
			}
		}

		if (empty($_SESSION["cc_obj"][$cc_id]))
		{
			if (($data["cc_obj"] = $this->custom_class_model->get_cc(array("id"=>$cc_id))) === FALSE)
			{
				$_SESSION["NOTICE"] = "sql_error";
			}
			else
			{
				unset($_SESSION["cc_obj"]);
				$_SESSION["cc_obj"][$cc_id] = serialize($data["cc_obj"]);
			}
		}

		$data["cmd"] = ($cc_id=="")?$this->input->post("cmd"):"edit";
		$data["country_id"] = $country_id;
		$data["cc_id"] = $cc_id;

		$this->load->view('mastercfg/custom_class/custom_class_index_v', $data);
	}

	public function add()
	{

		$sub_app_id = $this->_get_app_id()."01";

		if ($this->input->post("posted"))
		{

			if (isset($_SESSION["cc_vo"]))
			{
				$this->custom_class_model->include_cc_vo();
				$data["cc"] = unserialize($_SESSION["cc_vo"]);

				set_value($data["cc"], $_POST);
				$proc = $this->custom_class_model->get_cc(array("region_id"=>$data["cc"]->get_country_id(), "code"=>$data["cc"]->get_code()));
				if (!empty($proc))
				{
					$_SESSION["NOTICE"] = "code_existed";
				}
				else
				{

					if ($new_obj = $this->custom_class_model->add_cc($data["cc"]))
					{
						unset($_SESSION["cc_vo"]);
						redirect(base_url()."mastercfg/custom_class/index/".$this->input->post("country_id")."/?".$_SERVER['QUERY_STRING']);
					}
					else
					{
						$_SESSION["NOTICE"] = "submit_error";
					}
				}
			}
		}

		$this->index($this->input->post("region_id"));
	}

	public function edit($id)
	{
		$sub_app_id = $this->_get_app_id()."02";

		if ($this->input->post("posted"))
		{
			unset($_SESSION["NOTICE"]);

			if (isset($_SESSION["cc_obj"][$id]))
			{
				$this->custom_class_model->include_cc_vo();
				$data["cc"] = unserialize($_SESSION["cc_obj"][$id]);
				if ($data["cc"]->get_id() != $_POST["id"])
				{
					$proc = $this->custom_class_model->get_cc(array("id"=>$_POST["id"]));
					if (!empty($proc))
					{
						$_SESSION["NOTICE"] = "custom_classification_existed";
					}
				}
				if(empty($_SESSION["NOTICE"]))
				{
					set_value($data["cc"], $_POST);

					if ($this->custom_class_model->update_cc($data["cc"]))
					{
						unset($_SESSION["cc_obj"]);
						redirect(base_url()."mastercfg/custom_class/index/".$this->input->post("country_id")."/?".$_SERVER['QUERY_STRING']);
					}
					else
					{
						$_SESSION["NOTICE"] = "submit_error";
					}
				}
			}
		}

		$this->index($this->input->post("region_id"), $_POST["id"]);

	}

	public function delete($id="")
	{

	}

	public function sku($country_id="", $sku="")
	{
		$sub_app_id = $this->_get_app_id()."00";

		$_SESSION["LISTPAGE"] = base_url()."mastercfg/custom_class/".($country_id==""?"":"sku/".$country_id).($sku==""?"":"/".$sku)."?".$_SERVER['QUERY_STRING'];

		$where = array();
		$option = array();

		$submit_search=0;

		if ($country_id != "")
		{
			$where["country_id"] = $country_id;
		}
		if ($this->input->get("sku") != "")
		{
			$where["pcc.sku LIKE "] = "%".$this->input->get("sku")."%";
			$submit_search=1;
		}

		if ($this->input->get("prod_name") != "")
		{
			$where["p.name LIKE "] = "%".$this->input->get("prod_name")."%";
			$submit_search=1;
		}

		if ($this->input->get("sub_cat_name") != "")
		{
			$where["sc.name LIKE "] = "%".$this->input->get("sub_cat_name")."%";
			$submit_search=1;
		}

		if ($this->input->get("code") != "")
		{
			$where["code LIKE "] = "%".$this->input->get("code")."%";
			$submit_search=1;
		}
		if ($this->input->get("description") != "")
		{
			$where["description LIKE "] = "%".$this->input->get("description")."%";
			$submit_search=1;
		}
		if ($this->input->get("duty_pcent") != "")
		{
			fetch_operator($where, "duty_pcent", $this->input->get("duty_pcent"));
			$submit_search=1;
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
			$sort = "sku";

		if (empty($order))
			$order = "asc";

		$option["orderby"] = $sort." ".$order;

		if ($country_id)
		{
			$data = $this->custom_class_model->get_pcc_list($where, $option);
		}

		$data["countrylist"] = $this->custom_class_model->get_country_list(array("status"=>1), array("limit"=>-1, "orderby"=>"name"));

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


		if (empty($_SESSION["pcc_vo"]))
		{
			if (($cc_vo = $this->custom_class_model->get_pcc()) === FALSE)
			{
				$_SESSION["NOTICE"] = "sql_error";
			}
			else
			{
				$_SESSION["pcc_vo"] = serialize($pcc_vo);
			}
		}

		if (empty($_SESSION["pcc_obj"][$sku]))
		{
			if (($data["pcc_obj"] = $this->custom_class_model->get_pcc(array("country_id"=>$country_id, "sku"=>$sku))) === FALSE)
			{
				$_SESSION["NOTICE"] = "sql_error";
			}
			else
			{
				unset($_SESSION["pcc_obj"]);
				$_SESSION["pcc_obj"][$sku] = serialize($data["pcc_obj"]);
			}
		}

		$data["cmd"] = ($sku=="")?$this->input->post("cmd"):"edit";
		$data["country_id"] = $country_id;
		$data["sku"] = $sku;

		$this->load->view('mastercfg/custom_class/custom_class_sku_v', $data);
	}

	public function edit_sku($sku)
	{
		$sub_app_id = $this->_get_app_id()."02";

		if ($this->input->post("posted"))
		{
			unset($_SESSION["NOTICE"]);

			if (isset($_SESSION["pcc_obj"][$sku]))
			{
				$this->custom_class_model->include_pcc_vo();
				$data["pcc"] = unserialize($_SESSION["pcc_obj"][$sku]);
				if ($data["pcc"]->get_sku() != $_POST["sku"])
				{
					$proc = $this->custom_class_model->get_pcc(array("sku"=>$_POST["sku"], "country_id"=>$_POST["country_id"]));
					if (!empty($proc))
					{
						$_SESSION["NOTICE"] = "custom_classification_existed";
					}
				}
				if(empty($_SESSION["NOTICE"]))
				{
					set_value($data["pcc"], $_POST);

					if ($this->custom_class_model->update_pcc($data["pcc"]))
					{
						unset($_SESSION["pcc_obj"]);
						redirect(base_url()."mastercfg/custom_class/sku/".$this->input->post("country_id")."/?".$_SERVER['QUERY_STRING']);
					}
					else
					{
						$_SESSION["NOTICE"] = "submit_error";
					}
				}
			}
		}

		$this->sku($this->input->post("country_id"), $_POST["sku"]);
	}

	public function sub_cat($country_id="", $sub_cat_id="")
	{
		$sub_app_id = $this->_get_app_id()."00";

		$_SESSION["LISTPAGE"] = base_url()."mastercfg/custom_class/".($country_id==""?"":"sub_cat/".$country_id).($sub_cat_id==""?"":"/".$sub_cat_id)."?".$_SERVER['QUERY_STRING'];

		$where = array();
		$option = array();

		$submit_search=0;

		if ($country_id != "")
		{
			$where["ccm.country_id"] = $country_id;
		}

		if ($this->input->get("cat_name"))
		{
			$where["cat.name LIKE"] = "%".$this->input->get("cat_name")."%";
			$submit_search=1;
		}

		if ($this->input->get("sub_cat_name"))
		{
			$where["sc.name LIKE"] = "%".$this->input->get("sub_cat_name")."%";
			$submit_search=1;
		}

		if ($this->input->get("code") != "")
		{
			$condition = "(code LIKE '%" . $this->input->get("code") . "%') OR (description LIKE '%" . $this->input->get("description") . "%')";
			$where[$condition] = "%".$this->input->get("code")."%";
			$submit_search=1;
		}
		if ($this->input->get("description") != "")
		{
			$where["description LIKE "] = "%".$this->input->get("description")."%";
			$submit_search=1;
		}
		if ($this->input->get("duty_pcent") != "")
		{
			fetch_operator($where, "duty_pcent", $this->input->get("duty_pcent"));
			$submit_search=1;
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
			$sort = "sub_cat_id";

		if (empty($order))
			$order = "asc";

		$option["orderby"] = $sort." ".$order;

		if ($country_id)
		{
			$data = $this->custom_class_model->get_ccm_list($where, $option);
		}

		$data["countrylist"] = $this->custom_class_model->get_country_list(array("status"=>1), array("limit"=>-1, "orderby"=>"name"));
		$data["subcatlist"] = $this->custom_class_model->get_sub_cat_list(array("level"=>2), array("limit"=>-1, "orderby"=>"name"));
		$data["custom_class_list"] = $this->custom_class_model->get_custom_class_list(array("country_id"=>$country_id), array("limit"=>-1, "orderby"=>"id"));

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


		if (empty($_SESSION["ccm_vo"]))
		{
			if (($ccm_vo = $this->custom_class_model->get_ccm()) === FALSE)
			{
				$_SESSION["NOTICE"] = "sql_error";
			}
			else
			{
				$_SESSION["ccm_vo"] = serialize($ccm_vo);
			}
		}

		if (empty($_SESSION["ccm_obj"][$sub_cat_id]))
		{
			if (($data["ccm_obj"] = $this->custom_class_model->get_ccm(array("country_id"=>$country_id, "sub_cat_id"=>$sub_cat_id))) === FALSE)
			{
				$_SESSION["NOTICE"] = "sql_error";
			}
			else
			{
				if(empty($data["ccm_obj"]))
				{
					$data["ccm_obj"] = $this->custom_class_model->get_ccm(array());
				}
				unset($_SESSION["ccm_obj"]);
				$_SESSION["ccm_obj"][$sub_cat_id] = serialize($data["ccm_obj"]);
			}
		}

		$data["cmd"] = ($sub_cat_id=="")?$this->input->post("cmd"):"edit";
		$data["country_id"] = $country_id;
		$data["sub_cat_id"] = $sub_cat_id;

		$this->load->view('mastercfg/custom_class/custom_class_sub_cat_v', $data);
	}

	public function edit_sub_cat($sub_cat_id)
	{
		$sub_app_id = $this->_get_app_id()."02";

		if ($this->input->post("posted"))
		{
			unset($_SESSION["NOTICE"]);

			if (isset($_SESSION["ccm_obj"][$sub_cat_id]))
			{
				$this->custom_class_model->include_ccm_vo();
				$data["ccm"] = unserialize($_SESSION["ccm_obj"][$sub_cat_id]);

				if ($data["ccm"]->get_sub_cat_id() != $_POST["sub_cat_id"])
				{
					$proc = $this->custom_class_model->get_ccm(array("sub_cat_id"=>$_POST["sub_cat_id"], "country_id"=>$_POST["country_id"]));
					if (!empty($proc))
					{
						$_SESSION["NOTICE"] = "custom_classification_existed";
					}
				}
				if(empty($_SESSION["NOTICE"]))
				{
					set_value($data["ccm"], $_POST);

					$ccm_obj = $this->custom_class_model->get_ccm(array("sub_cat_id"=>$_POST["sub_cat_id"], "country_id"=>$_POST["country_id"]));
					if(empty($ccm_obj))
					{
						$action = "insert";
					}
					else
					{
						$action = "update";
					}

					if ($this->custom_class_model->{$action."_ccm"}($data["ccm"]))
					{
						unset($_SESSION["pcc_obj"]);
						redirect(base_url()."mastercfg/custom_class/sub_cat/".$this->input->post("country_id")."/?".$_SERVER['QUERY_STRING']);
					}
					else
					{
						$_SESSION["NOTICE"] = "submit_error";
					}
				}
			}
		}

		$this->sub_cat($this->input->post("country_id"), $_POST["sub_cat_id"]);

	}

	public function _get_app_id(){
		return $this->app_id;
	}

	public function _get_lang_id(){
		return $this->lang_id;
	}
}

/* End of file custom_class.php */
/* Location: ./system/application/controllers/custom_class.php */