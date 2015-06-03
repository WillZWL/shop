<?php
class Product_identifier extends MY_Controller
{

	private $app_id = 'MKT0065';
	private $lang_id = 'en';

	//must set to public for view
	public $default_platform_id;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('url','notice','image'));
		$this->load->library('input');
		$this->load->model('marketing/product_identifier_model');
		$this->load->model('marketing/product_model');
		$this->load->library('service/pagination_service');
		$this->load->library('service/context_config_service');
		$this->default_platform_id = $this->context_config_service->value_of("default_platform_id");
	}

	public function index()
	{
		$data = array();
		include_once APPPATH."language/".$this->_get_app_id()."00_".$this->_get_lang_id().".php";
		$data["lang"] = $lang;
		$this->load->view("marketing/product_identifier/product_identifier_index",$data);
	}

	public function plist()
	{
		$where = array();
		$option = array();
		$sub_app_id = $this->_get_app_id()."02";
		include_once(APPPATH."language/".$sub_app_id."_".$this->_get_lang_id().".php");
		$data["lang"] = $lang;

		$sku = $this->input->get("sku");
		$prod_name = $this->input->get("name");
		$master_sku = $this->input->get("master_sku");
		$prod_grp_cd = $this->input->get("prod_grp_cd");
		$colour_id = $this->input->get("colour_id");

		if ($sku != "" || $prod_name != "" || $master_sku != "" || $prod_grp_cd != "" || $colour_id != "")
		{
			$data["search"] = 1;
			if ($sku != "")
			{
				$where["sku"] = $sku;
			}

			if ($master_sku != "")
			{
				$where['master_sku'] = $master_sku;
			}

			if ($prod_name != "")
			{
				$where["name"] = $prod_name;
			}

			if ($prod_grp_cd != "")
			{
				$where["prod_grp_cd"] = $prod_grp_cd;
			}

			if ($colour_id != "")
			{
				$where["colour_id"] = $colour_id;
			}

			$sort = $this->input->get("sort");
			$order = $this->input->get("order");

			$limit = '20';

			$pconfig['base_url'] = current_url()."?".$_SERVER['QUERY_STRING'];
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

			$option["exclude_bundle"] = 1;
			$data["objlist"] = $this->product_identifier_model->get_product_list($where, $option);
			$data["total"] = $this->product_identifier_model->get_product_list_total($where, $option);
			$pconfig['total_rows'] = $data['total'];
			$this->pagination_service->set_show_count_tag(TRUE);
			$this->pagination_service->msg_br = TRUE;
			$this->pagination_service->initialize($pconfig);

			$data["notice"] = notice($lang);

			$data["sortimg"][$sort] = "<img src='".base_url()."images/".$order.".gif'>";
			$data["xsort"][$sort] = $order=="asc"?"desc":"asc";
		}

		$this->load->view('marketing/product_identifier/product_identifier_list', $data);
	}

	public function view($value="")
	{
		if($value == "")
		{
			exit;
		}

		$data = array();
		$data["valid_supplier"] = 1;
		$data["prompt_notice"] = 0;
		$data["website_link"] = $this->context_config_service->value_of("website_domain");
		define('IMG_PH', $this->context_config_service->value_of("prod_img_path"));
		if($this->input->post('posted'))
		{
			$pcid = $this->input->post('country_id');
			$pean = $this->input->post('ean');
			$pmpn = $this->input->post('mpn');
			$pupc = $this->input->post('upc');
			$pstatus = $this->input->post('status');

			foreach($pcid as $val)
			{
				$cur_ean = $pean[$val];
				$cur_mpn = $pmpn[$val];
				$cur_upc = $pupc[$val];
				$cur_status = $pstatus[$val]*1;

				$this->product_identifier_model->__autoload_product_identifier_vo();
				//$product_identifier_obj = unserialize($_SESSION["product_identifier_obj"][$val]);
				$sku = $this->input->post('sku');
				list($prod_grp_cd, $version_id, $colour_id) = explode("-", $sku);
				$product_identifier_obj = $this->product_identifier_model->get_product_identifier_obj(array("prod_grp_cd"=>$prod_grp_cd, "colour_id"=>$colour_id, "country_id"=>$val));
				if(!empty($product_identifier_obj) || $cur_ean != "" || $cur_mpn != "" || $cur_upc != "")
				{
					if(!$product_identifier_obj)
					{
						$action = "insert";
						$product_identifier_obj = $this->product_identifier_model->get_product_identifier_obj();
						$product_identifier_obj->set_prod_grp_cd($prod_grp_cd);
						$product_identifier_obj->set_colour_id($colour_id);
						$product_identifier_obj->set_country_id($val);
					}
					else
					{
						$action = "update";
					}

					if($product_identifier_obj->get_ean() != $cur_ean ||
						$product_identifier_obj->get_mpn() != $cur_mpn ||
						$product_identifier_obj->get_upc() != $cur_upc ||
						$product_identifier_obj->get_status() != $cur_status
						)
					{
						$product_identifier_obj->set_ean($cur_ean);
						$product_identifier_obj->set_mpn($cur_mpn);
						$product_identifier_obj->set_upc($cur_upc);
						$product_identifier_obj->set_status($cur_status);

						$ret = $this->product_identifier_model->$action($product_identifier_obj);
						if($ret === FALSE)
						{
							$_SESSION["NOTICE"] = "{$action}_failed ".$this->db->_error_message();
						}
						else
						{
							unset($_SESSION["product_identifier_obj"][$val]);
							if($this->input->post('target') != "")
							{
								$data["prompt_notice"] = 1;
							}
						}
					}
				}
				else
				{
					unset($_SESSION["product_identifier_obj"][$val]);
				}
			}
			Redirect(base_url()."marketing/product_identifier/view/".$value);
		}

		$data["action"] = "update";

		include_once APPPATH."language/".$this->_get_app_id()."01_".$this->_get_lang_id().".php";
		$data["lang"] = $lang;
		$data["canedit"] = 1;
		$data["value"] = $value;
		$data["target"] = $this->input->get('target');
		$data["notice"] = notice($lang);

		$pdata = array();
		if($value != "")
		{
			//unset($_SESSION["product_identifier_obj"]);
			list($prod_grp_cd, $version_id, $colour_id) = explode("-", $value);
			$data["country_list"] = $this->product_identifier_model->get_sell_country_list();
			if($prod_identifer_list = $this->product_identifier_model->product_identifier_service->get_list(array("prod_grp_cd"=>$prod_grp_cd, "colour_id"=>$colour_id)))
			{
				foreach($prod_identifer_list as $pi_obj)
				{
					$objcount++;
					$data["product_identifier_list"][$pi_obj->get_country_id()] = $pi_obj;
					$_SESSION["product_identifier_obj"][$pi_obj->get_country_id()] = serialize($pi_obj);
				}
			}
			$data["pdata"] = $pdata;
			$data["objcount"] = $objcount;
			$data["value"] = $value;
			$prod_obj = $this->product_identifier_model->get_prod($value);
		}

		$data["prod_obj"] = $prod_obj;
		$mapping_obj = $this->product_identifier_model->get_mapping_obj(array('sku'=>$value, 'ext_sys'=>'WMS', 'status'=>1));
		if($mapping_obj && trim($mapping_obj->get_ext_sku()) != "")
		{
			$data['master_sku'] = $mapping_obj->get_ext_sku();
		}
		$_SESSION["prod_obj"] = serialize($prod_obj);

		$this->load->view("marketing/product_identifier/product_identifier_view",$data);

	}

	public function _get_app_id()
	{
		return $this->app_id;
	}

	public function _get_lang_id()
	{
		return $this->lang_id;
	}
}

?>