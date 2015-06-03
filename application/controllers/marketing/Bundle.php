<?php
class Bundle extends MY_Controller
{

	private $app_id="MKT0006";
	private $lang_id="en";


	public function __construct()
	{
		parent::__construct();
		$this->load->model('marketing/bundle_model');
		$this->load->helper(array('url','notice','object','image'));
		$this->load->library('service/pagination_service');
		$this->load->library('service/context_config_service');
	}

	public function index($cmd="list", $prod_grp_cd="")
	{
		$sub_app_id = $this->_get_app_id().($cmd=="list"?"00":"01");
		$_SESSION["LISTPAGE"] = ($prod_grp_cd==""?base_url()."marketing/bundle/index/{$cmd}?":current_url()).$_SERVER['QUERY_STRING'];

		$where = array();
		$option = array();

		$submit_search = 0;

		if ($prod_grp_cd != "")
		{
			$where["prod_grp_cd"] = $prod_grp_cd;
		}

		if ($this->input->get("sku") != "")
		{
			$where["sku"] = $this->input->get("sku");
			$submit_search = 1;
		}

		if ($this->input->get("name") != "")
		{
			$where["name"] = $this->input->get("name");
			$submit_search = 1;
		}

		if ($this->input->get("colour") != "")
		{
			$where["colour"] = $this->input->get("colour");
			$submit_search = 1;
		}

		if ($this->input->get("cat_id") != "")
		{
			$where["cat_id"] = $this->input->get("cat_id");
			$submit_search = 1;
		}

		if ($this->input->get("sub_cat_id") != "")
		{
			$where["sub_cat_id"] = $this->input->get("sub_cat_id");
			$submit_search = 1;
		}

		if ($this->input->get("sub_sub_cat_id") != "")
		{
			$where["sub_sub_cat_id"] = $this->input->get("sub_sub_cat_id");
			$submit_search = 1;
		}

		if ($this->input->get("brand") != "")
		{
			$where["brand"] = $this->input->get("brand");
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
			$sort = "p.create_on";

		if (empty($order))
			$order = "desc";

		$option["orderby"] = $sort." ".$order;

		if ($cmd == "list")
		{
			$data["objlist"] = $this->bundle_model->get_bundle_list($where, $option);
			$data["total"] = $this->bundle_model->get_bundle_list_total($where);
		}
		else
		{
			$data["objlist"] = $this->bundle_model->get_ra_prod_list($where, $option);
			$data["total"] = $this->bundle_model->get_ra_prod_list_total($where);
		}
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
		$data["prod_grp_cd"] = $prod_grp_cd;
		$data["cmd"] = $cmd;
		$this->load->view('marketing/bundle/bundle_index_v', $data);
	}

	public function add($prod_sku="")
	{

		if ($prod_sku)
		{
			$sub_app_id = $this->_get_app_id()."01";

			if ($this->input->post("posted"))
			{
				$comp_sku = explode(",", $_POST["comp_sku"]);
				sort($comp_sku);
				$components = @implode(",", $comp_sku);
				if ($this->bundle_model->get_bundle_list_total(array("components"=>$components)))
				{
					$_SESSION["NOTICE"] = "bundle_existed";
				}
				else
				{
					if (isset($_SESSION["bundle_vo"]) && isset($_SESSION["product_vo"]))
					{
						$this->bundle_model->include_vo("product");
						$this->bundle_model->include_vo("bundle");
						$data["bundle"] = unserialize($_SESSION["bundle_vo"]);
						$data["product"] = unserialize($_SESSION["product_vo"]);
						$prod_grp_cd = $this->bundle_model->seq_next_val();
						$sku = $prod_grp_cd."-".$this->input->post("version_id")."-".$this->input->post("colour_id");
						$data["bundle"]->set_prod_sku($sku);

						$_POST["status"] = 2;
						$_POST["rrp"] = $_POST["clearance"] = 0;
						$_POST["proc_status"] = '4';
						$_POST["website_status"] = 'I';
						$_POST["sourcing_status"] = 'A';
						$_POST["website_quantity"] = $_POST["quantity"] = 0;
						set_value($data["product"], $_POST);

						$data["product"]->set_sku($sku);
						$data["product"]->set_prod_grp_cd($prod_grp_cd);
						$data["product"]->set_version_id($this->input->post("version_id"));
						$data["product"]->set_colour_id($this->input->post("colour_id"));
						if ($this->bundle_model->add("product", $data["product"]))
						{
							$prod_name = "";
							$i=0;
							foreach ($this->input->post("components") as $component)
							{
								if ($component != "")
								{
									list($rsprod_sku, $rsprod_name) = explode("::", $component);
									$data["bundle"]->set_component_sku($rsprod_sku);
									$comp_prod_sku[$i] = $rsprod_sku;
									$data["bundle"]->set_component_order($i);
									$prod_name[] = $rsprod_name;
									if (!($new_obj = $this->bundle_model->add("bundle", $data["bundle"])))
									{
										$_SESSION["NOTICE"] = "submit_error";
									}
									$i++;
								}
							}

							$this->bundle_model->update_seq($prod_grp_cd);
							$data["product"]->set_name(@implode(" + ", $prod_name));
							$this->bundle_model->update("product", $data["product"]);

							$main_prod = $this->bundle_model->get("product", array("sku"=>$comp_prod_sku[0]));
							$main_prod->set_proc_status("4");
							$this->bundle_model->update("product", $main_prod);

							if (empty($_SESSION["NOTICE"]))
							{
								unset($_SESSION["product_vo"]);
								unset($_SESSION["bundle_vo"]);
								echo "<script>top.document.location.href='".base_url()."marketing/bundle/index/list/{$prod_grp_cd}'</script>";
								exit;
							}
						}
						else
						{
							$_SESSION["NOTICE"] = $this->db->last_query();
						}
					}
				}
			}

			include_once(APPPATH."language/".$sub_app_id."_".$this->_get_lang_id().".php");
			$data["lang"] = $lang;

			if (empty($data["product"]))
			{
				if (($data["product"] = $this->bundle_model->get("product", array("sku"=>$prod_sku))) === FALSE)
				{
					$_SESSION["NOTICE"] = "sql_error";
				}
			}

			if (!isset($_SESSION["product_vo"]))
			{
				if ($product_vo = $this->bundle_model->get("product"))
				{
					$_SESSION["product_vo"] = serialize($product_vo);
				}
			}

			if (!isset($_SESSION["bundle_vo"]))
			{
				if ($bundle_vo = $this->bundle_model->get("bundle"))
				{
					$_SESSION["bundle_vo"] = serialize($bundle_vo);
				}
			}

			$data["ra_prod"] = $this->bundle_model->get_ra_prod_tr($prod_sku, "WEBHK", $lang);

			$data["notice"] = notice($lang);
			$data["cmd"] = "add";
			$this->load->view('marketing/bundle/bundle_add_v',$data);
		}
	}

	public function view($sku="")
	{
		if ($sku == "" || !$this->bundle_model->bundle_service->get(array("prod_sku"=>$sku)))
		{
			show_404();
		}

		$sub_app_id = $this->_get_app_id()."02";

		define('IMG_PH', $this->context_config_service->value_of("prod_img_path"));
		$img_size = array("l", "m", "s");
		if ($this->input->post("posted"))
		{
			unset($_SESSION["NOTICE"]);
			if (isset($_SESSION["product_obj"][$sku]))
			{
				$this->bundle_model->include_vo("product");
				$data["product"] = unserialize($_SESSION["product_obj"][$sku]);

				if ($data["product"]->get_name() != $_POST["name"])
				{
					$proc = $this->bundle_model->get("product", array("name"=>$name));
					if (!empty($proc))
					{
						$_SESSION["NOTICE"] = "product_existed";
					}
				}
				if (empty($_SESSION["NOTICE"]))
				{
					if ($_POST["ex_demo"] == "")
					{
						$_POST["ex_demo"] = 0;
					}
					if ($_POST["clearance"] == "")
					{
						$_POST["clearance"] = 0;
					}
					//$_POST["status"] = 2;
					set_value($data["product"], $_POST);

					$config['upload_path'] = IMG_PH;
					$config['allowed_types'] = 'gif|jpg|jpeg|png';
					$config['file_name'] = $sku;
					$config['overwrite'] = TRUE;
					$config['is_image'] = TRUE;
					$this->load->library('upload', $config);

					if (!empty($_FILES["image_file"]["name"])) {
						@unlink(IMG_PH.$sku.".".$data["product"]->get_image());
						if ($this->upload->do_upload("image_file"))
						{
							$res = $this->upload->data();
							$ext = substr($res["file_ext"], 1);
							$data["product"]->set_image($ext);
							list($width, $height) = explode("x", $this->context_config_service->value_of("thumb_w_x_h"));
							thumbnail(IMG_PH.$sku.".".$ext, $width, $height, IMG_PH.$sku.".".$ext);
							watermark(IMG_PH.$sku.".".$ext, "images/watermark.png", "B", "R", "", "#000000");
							foreach ($img_size as $size)
							{
								list($width, $height) = explode("x", $this->context_config_service->value_of("thumb_{$size}_w_x_h"));
								thumbnail(IMG_PH.$sku.".".$ext, $width, $height, IMG_PH.$sku."_{$size}.".$ext);
							}
						}
						else
						{
							$_SESSION["NOTICE"] = $this->upload->display_errors();;
						}
					}

					if (!empty($_FILES["flash_file"]["name"])) {
						$config['allowed_types'] = 'swf';
						$config['is_image'] = FALSE;
						$config['max_size'] = '1024';
						$this->upload->initialize($config);
						if ($this->upload->do_upload("flash_file"))
						{
							$res = $this->upload->data();
							$ext = substr($res["file_ext"], 1);
							$data["product"]->set_flash($ext);
						}
						else
						{
							$_SESSION["NOTICE"] = $this->upload->display_errors();;
						}
					}

					if ($this->bundle_model->update("product", $data["product"]))
					{
						unset($_SESSION["product_obj"]);
						redirect(base_url()."marketing/bundle/view/".$sku);
					}
					else
					{
						$_SESSION["NOTICE"] = "submit_error";
					}
				}
			}
		}

		include_once(APPPATH."language/".$sub_app_id."_".$this->_get_lang_id().".php");
		$data["lang"] = $lang;

		if (empty($data["product"]))
		{
			if (($data["product"] = $this->bundle_model->get("product", array("sku"=>$sku))) === FALSE)
			{
				$_SESSION["NOTICE"] = "sql_error";
			}
			else
			{
				unset($_SESSION["product_obj"]);
				$_SESSION["product_obj"][$sku] = serialize($data["product"]);
			}
		}

		$data["default_curr"] = $this->context_config_service->value_of("website_default_curr");
		$data["components"] = $this->bundle_model->get_components_tr(array("sku"=>$sku, "platform_id"=>"WSUS"), array("orderby"=>"component_order"), $lang);

		$data["notice"] = notice($lang);
		$data["cmd"] = "edit";
		$this->load->view('marketing/bundle/bundle_detail_v',$data);
	}

	public function _get_app_id(){
		return $this->app_id;
	}

	public function _get_lang_id(){
		return $this->lang_id;
	}
}

/* End of file bundle.php */
/* Location: ./system/application/controllers/bundle.php */