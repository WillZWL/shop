<?php
include_once "freight_helper.php";

class Freight extends Freight_helper
{
	private $app_id="MST0009";
	private $lang_id="en";

	public function __construct()
	{
		parent::Freight_helper();
		$this->authorization_service->check_access_rights($this->_get_app_id(), "");
	}

	public function index($cat_type="freight", $cat_id="")
	{
		$sub_app_id = $this->_get_app_id()."00";

		$where = array();
		$option = array();

		if ($this->input->get("weight") != "")
		{
			$where["weight"] = $this->input->get("weight");
		}

		$sort = $this->input->get("sort");
		$order = $this->input->get("order");
/*
		$limit = '20';

		$pconfig['base_url'] = $_SESSION["LISTPAGE"];

		$option["limit"] = $pconfig['per_page'] = $limit;
		if ($option["limit"])
		{
			$option["offset"] = $this->input->get("per_page");
		}
*/
		if (empty($sort))
		{
			$sort = $cat_type=="weight"?"weight":"id";
		}

		if (empty($order))
		{
			$order = "asc";
		}

		$option["orderby"] = $sort." ".$order;

		if ($this->input->get("declared_pcent") != "")
		{
			$where["declared_pcent"] = $this->input->get("declared_pcent");
		}
		if ($this->input->get("bulk_admin_chrg") != "")
		{
			$where["bulk_admin_chrg"] = $this->input->get("bulk_admin_chrg");
		}

		$_SESSION["LISTPAGE"] = base_url()."mastercfg/freight/index/{$cat_type}/{$cat_id}?".$_SERVER['QUERY_STRING'];
		if ($this->input->get("name") != "")
		{
			$where["name"] = "%".$this->input->get("name")."%";
		}

		if ($this->input->get("declared_pcent") != "")
		{
			$where["declared_pcent"] = $this->input->get("declared_pcent");
		}

		if ($this->input->get("bulk_admin_chrg") != "")
		{
			$where["bulk_admin_chrg"] = $this->input->get("bulk_admin_chrg");
		}

		$data["objlist"] = $this->freight_model->get_freight_cat_list($where, $option);
		$data["total"] = $this->freight_model->get_freight_cat_total($where, $option);
//			$data["searchdisplay"] = ($this->input->get("name") =="" && $this->input->get("weight")=="" && $this->input->get("declared_pcent")=="" && $this->input->get("bulk_admin_chrg")=="")?'style="display:none"':"";
		$data["searchdisplay"] = "";

		if (empty($_SESSION["freight_cat_vo"]))
		{
			if (($freight_cat_vo = $this->freight_model->get_freight_cat()) === FALSE)
			{
				$_SESSION["NOTICE"] = "ERROR ".__LINE__." : ".$this->db->_error_message();
			}
			else
			{
				$_SESSION["freight_cat_vo"] = serialize($freight_cat_vo);
			}
		}

		if (empty($_SESSION["freight_cat_obj"][$cat_id]))
		{
			if (($data["freight_cat_obj"] = $this->freight_model->get_freight_cat(array("id"=>$cat_id))) === FALSE)
			{
				$_SESSION["NOTICE"] = "ERROR ".__LINE__." : ".$this->db->_error_message();
			}
			else
			{
				unset($_SESSION["freight_cat_obj"]);
				$_SESSION["freight_cat_obj"][$cat_id] = serialize($data["freight_cat_obj"]);
			}
		}
		$view_file = 'mastercfg/freight/freight_index_v';

		//$data["courierlist"] = $this->freight_model->get_courier_list(array(), array("orderby"=>"type, id, weight_type"));
		$data["origin_country_list"] = $this->freight_model->get_origin_country_list();

		include_once(APPPATH."language/".$sub_app_id."_".$this->_get_lang_id().".php");
		$data["lang"] = $lang;
/*
		$pconfig['total_rows'] = $data['total'];
		$this->pagination_service->set_show_count_tag(TRUE);
		$this->pagination_service->initialize($pconfig);
*/
		$data["notice"] = notice($lang);

		$data["sortimg"][$sort] = "<img src='".base_url()."images/".$order.".gif'>";
		$data["xsort"][$sort] = $order=="asc"?"desc":"asc";

		$data["cmd"] = ($cat_id=="")?$this->input->post("cmd"):"edit";
		$data["cat_id"] = $cat_id;
		$data["cat_type"] = $cat_type;
		$this->load->view($view_file, $data);
	}

	public function add()
	{

		$sub_app_id = $this->_get_app_id()."01";
		$cat_type = $this->input->post("cat_type");

		if ($this->input->post("posted"))
		{

			if (isset($_SESSION["freight_cat_vo"]))
			{
				$this->freight_model->include_freight_cat_vo();
				$data["freight_cat"] = unserialize($_SESSION["freight_cat_vo"]);

				$_POST["status"] = 1;
				set_value($data["freight_cat"], $_POST);

				$name = $data["freight_cat"]->get_name();
				$proc = $this->freight_model->get_freight_cat(array("name"=>$name));
				if (!empty($proc))
				{
					$_SESSION["NOTICE"] = "freight_cat_existed";
				}
				else
				{

					if ($newobj = $this->freight_model->add_freight_cat($data["freight_cat"]))
					{
						if ($objlist = $this->freight_model->get_fcc_nearest_amount($newobj->get_id(), $data["freight_cat"]->get_weight()))
						{
							foreach ($objlist as $obj)
							{
								$obj->set_fcat_id($newobj->get_id());
								$this->freight_model->add_fcc($obj);
							}
						}
						unset($_SESSION["freight_cat_vo"]);
						redirect(base_url()."mastercfg/freight/index/".$cat_type."?".$_SERVER['QUERY_STRING']);
					}
					else
					{
						$_SESSION["NOTICE"] = "ERROR ".__LINE__." : ".$this->db->_error_message();
					}
				}
			}
		}

		$this->index($cat_type);
	}

	public function view($origin_country = "")
	{
		if($origin_country)
		{
			$sub_app_id = $this->_get_app_id()."02";
			include_once(APPPATH."language/".$sub_app_id."_".$this->_get_lang_id().".php");
			$data["lang"] = $lang;
			if($this->input->post("posted"))
			{
				$fcc_vo = $this->freight_model->get_freight_cat_charge_obj();
				foreach($_POST["value"] AS $fcat_id=>$country_value_arr)
				{
					foreach($country_value_arr AS $dest_country=>$value)
					{
						$obj = $this->freight_model->get_freight_cat_charge_obj(array("origin_country"=>$origin_country, "fcat_id"=>$fcat_id, "dest_country"=>$dest_country));
						if(!$obj)
						{
							$obj = clone $fcc_vo;
							$obj->set_fcat_id($fcat_id);
							$obj->set_origin_country($origin_country);
							$obj->set_dest_country($dest_country);
							$obj->set_currency_id("HKD");
							$action = "insert_fcc";
						}
						else
						{
							$action = "update_fcc";
						}
						$obj->set_amount($value);

						if(!$this->freight_model->$action($obj))
						{
							$_SESSION["NOTICE"] = "ERROR ".__LINE__." : ".$this->db->_error_message();
						}
					}
				}
				if (empty($_SESSION["NOTICE"]))
				{
					redirect(current_url()."?".$_SERVER['QUERY_STRING']);
				}
			}

			$sort = $this->input->get("sort");
			$order = $this->input->get("order");

			if (empty($sort))
			{
				$sort = $cat_type=="weight"?"cat_name":"weight";
			}

			if (empty($order))
			{
				$order = "asc";
			}

			$option["orderby"] = $sort." ".$order;

			$full_list= $this->freight_model->get_full_freight_cat_charge_list(array("origin_country"=>$origin_country), array("orderby"=>"fcat_id ASC", "limit"=>-1));
			$data["objlist"] = $full_list["value_list"];
			$data["key_freight_list"] = $full_list["key_list"]["frieght_cat_arr"];
			$data["key_country_list"] = $full_list["key_list"]["dest_country_arr"];

			include_once(APPPATH."language/".$sub_app_id."_".$this->_get_lang_id().".php");
			$data["lang"] = $lang;

			$data["origin_country_list"] = $this->freight_model->get_origin_country_list();
			$data["origin_country"] = $origin_country;
			$data["notice"] = notice($lang);
			$data["cmd"] = "edit";
			$data["sortimg"][$sort] = "<img src='".base_url()."images/".$order.".gif'>";
			$data["xsort"][$sort] = $order=="asc"?"desc":"asc";
			$this->load->view('mastercfg/freight/freight_detail_v',$data);
		}
	}

	public function region($courier_id="")
	{
		if ($courier_id)
		{
			$sub_app_id = $this->_get_app_id()."02";

			$courier = $this->freight_model->get_courier(array("id"=>$courier_id));
			$data["objlist"] = $this->freight_model->get_courier_region_country(array("courier_id"=>$courier_id));

			include_once(APPPATH."language/".$sub_app_id."_".$this->_get_lang_id().".php");
			$data["lang"] = $lang;

			$data["cmd"] = "view";
			$this->load->view('mastercfg/freight/freight_region_v',$data);
		}
	}

	public function edit($cat_id)
	{
		$sub_app_id = $this->_get_app_id()."02";
		$cat_type = $this->input->post("cat_type");

		if ($this->input->post("posted"))
		{
			unset($_SESSION["NOTICE"]);

			if ($cat_type == "freight")
			{
				if (isset($_SESSION["freight_cat_obj"][$cat_id]))
				{
					$this->freight_model->include_freight_cat_vo();
					$data["freight_cat"] = unserialize($_SESSION["freight_cat_obj"][$cat_id]);

					if ($data["freight_cat"]->get_name() != $_POST["name"])
					{
						$proc = $this->freight_model->get_freight_cat(array("name"=>$_POST["name"]));
						if (!empty($proc))
						{
							$_SESSION["NOTICE"] = "freight_cat_existed";
						}
					}
					if(empty($_SESSION["NOTICE"]))
					{
						set_value($data["freight_cat"], $_POST);

						if ($this->freight_model->update_freight_cat($data["freight_cat"]))
						{
							unset($_SESSION["freight_cat_obj"]);
							redirect(base_url()."mastercfg/freight/index/".$cat_type."?".$_SERVER['QUERY_STRING']);
						}
						else
						{
							$_SESSION["NOTICE"] = "ERROR ".__LINE__." : ".$this->db->_error_message();
						}
					}
				}
			}
			else
			{
				if (isset($_SESSION["weight_cat_obj"][$cat_id]))
				{
					$this->freight_model->include_weight_cat_vo();
					$data["weight_cat"] = unserialize($_SESSION["weight_cat_obj"][$cat_id]);

					if ($data["weight_cat"]->get_weight() != $_POST["weight"])
					{
						$proc = $this->freight_model->get_weight_cat(array("weight"=>$_POST["weight"]));
						if (!empty($proc))
						{
							$_SESSION["NOTICE"] = "weight_cat_existed";
						}
					}
					if(empty($_SESSION["NOTICE"]))
					{
						set_value($data["weight_cat"], $_POST);

						if ($this->freight_model->update_weight_cat($data["weight_cat"]))
						{
							unset($_SESSION["weight_cat_obj"]);
							redirect(base_url()."mastercfg/freight/index/".$cat_type."?".$_SERVER['QUERY_STRING']);
						}
						else
						{
							$_SESSION["NOTICE"] = "ERROR ".__LINE__." : ".$this->db->_error_message();
						}
					}
				}
			}
		}
		$this->index($cat_type, $_POST["id"]);

	}

	public function delete($id="")
	{

	}

	public function _get_app_id(){
		return $this->app_id;
	}

	public function _get_lang_id(){
		return $this->lang_id;
	}
}

/* End of file freight.php */
/* Location: ./system/application/controllers/freight.php */
