<?php
class Promotion_text extends MY_Controller
{

	private $app_id = "MKT0053";
	private $lang_id = "en";

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('url','directory','notice'));
		$this->load->model('marketing/promotion_text_model');
		$this->load->library('service/pagination_service');
		$this->load->library('service/platform_biz_var_service');
	}

	public function index()
	{
		redirect(base_url()."marketing/promotion_text_model/main");
	}

	public function main($lang_id="", $platform_id="")
	{
		$data["platform_id"] = $platform_id;
		$data["lang_id"] = $lang_id;
		$data["display"] = 1;

		$sub_app_id = $this->_get_app_id()."01";
		include_once(APPPATH."language/".$sub_app_id."_".$this->_get_lang_id().".php");
		$data["lang"] = $lang;

		$data['language_list'] = $this->promotion_text_model->get_lang_list(array("status"=>1), array("orderby"=>"name ASC"));
		$data['language_id'] = $lang_id;

		$data['platform_list'] = $this->promotion_text_model->get_platform_id_list(array("type"=>"Skype"), array("orderby"=>"name ASC"));

		$this->load->view('marketing/promotion_text/pt_index', $data);
	}

	public function view_left($platform_type="", $lang_id="", $platform_id="")
	{
		if($lang_id =="")
		{
			$lang_id = $this->input->get('lang_id');
		}
		if($platform_id =="")
		{
			$platform_id = $this->input->get('platform_id');
		}
		if($platform_type =="")
		{
			$platform_type = $this->input->get('platform_type');
		}

		$data['platform_type'] = $platform_type;
		$data['lang_id'] = $lang_id;
		$data['platform_id'] = $platform_id;

		$where = array();
		$option = array();
		$sub_app_id = $this->_get_app_id()."02";
		include_once(APPPATH."language/".$sub_app_id."_".$this->_get_lang_id().".php");
		$data["lang"] = $lang;

		$sku = $this->input->get("sku");
		$prod_name = $this->input->get("name");

		if (($sku  != "" || $prod_name != ""))
		{

			$data["search"] = 1;
			if ($sku != "")
			{
				$where["sku"] = $sku;
			}

			if ($prod_name != "")
			{
				$where["name"] = $prod_name;
			}

			$where["listing_status"] = "1";
			$where["sourcing_status"] = "A";
			$where["website_quantity"] = "1";
			$where["weblist"] = "1";

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
			$option["pricegtzero"] = "1";

			$data["objlist"] = $this->promotion_text_model->get_product_list($where, $option);
			$data["total"] = $this->promotion_text_model->get_product_list_total($where, $option);

			$pconfig['total_rows'] = $data['total'];
			$this->pagination_service->set_show_count_tag(TRUE);
			$this->pagination_service->msg_br = TRUE;
			$this->pagination_service->initialize($pconfig);

			$data["notice"] = notice($lang);

			$data["sortimg"][$sort] = "<img src='".base_url()."images/".$order.".gif'>";
			$data["xsort"][$sort] = $order=="asc"?"desc":"asc";
		}
		$data['language_list'] = $this->promotion_text_model->get_lang_list(array("status"=>1), array("orderby"=>"name ASC"));
		$where = array();
		$where['s.status'] = 1;

		if($lang_id)
		{
			$where['pbv.language_id'] = $lang_id;
		}
		if($platform_type == 'all')
		{
			$where["((s.type='WEBSITE') OR (s.type='SKYPE'))"] = NULL;
		}
		else
		{
			$where['s.type']= $platform_type;
		}

		$data['platform_list'] = $this->platform_biz_var_service->get_list_w_platform_name($where, array("orderby"=>"s.name ASC"));

		$this->load->view('marketing/promotion_text/pt_view_left',$data);
	}

	public function view_right($platform_type="", $lang_id="", $platform_id="", $sku="")
	{
		$data['platform_type'] = $platform_type;
		$data['lang_id'] = $lang_id;
		$data['platform_id'] = $platform_id;
		$data['sku'] = $sku;

		if(isset($_POST['promo_text']))
		{
			if($sku)
			{
				$this->sku_level_update($platform_type, $lang_id, $platform_id, $sku, $_POST['promo_text']);
			}
			else
			{
				if($_POST['plat_level'])
				{
					$this->platform_level_update($platform_type, $lang_id, $platform_id, $_POST['promo_text']);
				}
				else
				{
					$this->language_level_update($platform_type, $lang_id, $_POST['promo_text']);
				}
			}
		}

		if($lang_id != "")
		{
			if($platform_type == 'all')
			{
				$data['platform_list'] = $this->platform_biz_var_service->get_list_w_platform_name(array("s.status"=>1, "pbv.language_id"=>$lang_id, "((s.type='WEBSITE') OR (s.type='SKYPE'))"=>NULL), array("orderby"=>"s.name ASC"));
			}
			else
			{
				if($platform_id != "" && $platform_id != "all")
				{
					$data['platform_list'] = $this->platform_biz_var_service->get_list_w_platform_name(array("s.id"=>$platform_id, "s.type"=>$platform_type, "s.status"=>1, "pbv.language_id"=>$lang_id), array("orderby"=>"s.name ASC"));
				}
				else
				{
					$data['platform_list'] = $this->platform_biz_var_service->get_list_w_platform_name(array("s.type"=>$platform_type, "s.status"=>1, "pbv.language_id"=>$lang_id), array("orderby"=>"s.name ASC"));
				}
			}
			foreach($data['platform_list'] as $obj)
			{
				$data['platform_name'][$obj->get_selling_platform_id()] = $obj->get_platform_name();
			}
		}
		elseif($platform_type != "")
		{
			$data['lang_list'] = $this->promotion_text_model->get_lang_list(array("status"=>1), array("orderby"=>"id ASC"));
		}

		if($platform_type != 'all')
		{
			$promo_text_obj_w_lang = $this->promotion_text_model->get_list(array('platform_type'=>$platform_type, 'platform_id IS NULL'=>NULL, 'sku IS NULL'=>NULL), array("limit"=>-1));
		}
		else
		{
			$promo_text_obj_w_lang = $this->promotion_text_model->get_list(array('platform_id IS NULL'=>NULL, 'sku IS NULL'=>NULL), array("limit"=>-1));
		}

		if($lang_id != "")
		{
			if($platform_type != 'all')
			{
				$promo_text_obj_w_platform = $this->promotion_text_model->get_list(array('platform_type'=>$platform_type, 'lang_id'=>$lang_id, 'platform_id IS NOT NULL'=>NULL,'sku is NULL'=>NULL), array("limit"=>-1));
			}
			else
			{
				$promo_text_obj_w_platform = $this->promotion_text_model->get_list(array('lang_id'=>$lang_id, 'platform_id IS NOT NULL'=>NULL,'sku is NULL'=>NULL), array("limit"=>-1));
			}

		}

		if($sku != "")
		{
			if($platform_type != 'all')
			{
				$promo_text_obj_w_sku = $this->promotion_text_model->get_list(array('platform_type'=>$platform_type, 'lang_id'=>$lang_id, 'platform_id IS NOT NULL'=>NULL,'sku is NOT NULL'=>NULL), array("limit"=>-1));
			}
			else
			{
				$promo_text_obj_w_sku = $this->promotion_text_model->get_list(array('lang_id'=>$lang_id, 'platform_id IS NOT NULL'=>NULL,'sku is NOT NULL'=>NULL), array("limit"=>-1));
			}
		}

		if($promo_text_obj_w_lang)
		{
			foreach($promo_text_obj_w_lang as $obj)
			{
				$data['promo_text_obj_w_lang'][$obj->get_platform_type()][$obj->get_lang_id()] = $obj;
			}
		}

		if($promo_text_obj_w_platform)
		{
			foreach($promo_text_obj_w_platform as $obj)
			{
				$data['promo_text_obj_w_platform'][$obj->get_platform_type()][$obj->get_lang_id()][$obj->get_platform_id()] = $obj;
			}
		}

		if($promo_text_obj_w_sku)
		{
			foreach($promo_text_obj_w_sku as $obj)
			{
				$data['promo_text_obj_w_sku'][$obj->get_platform_type()][$obj->get_lang_id()][$obj->get_platform_id()][$obj->get_sku()] = $obj;
			}
		}

		$data['lang_list'] = $this->promotion_text_model->get_lang_list(array("status"=>1), array("orderby"=>"id ASC"));

		$sub_app_id = $this->_get_app_id()."03";
		include_once(APPPATH."language/".$sub_app_id."_".$this->_get_lang_id().".php");
		$data["lang"] = $lang;

		if($sku)
		{
			$this->load->view('marketing/promotion_text/pt_view_sku', $data);
		}
		else
		{
			$this->load->view('marketing/promotion_text/pt_view_right', $data);
		}
	}

	public function language_level_update($platform_type="", $lang_id="", $data=array())
	{
		foreach($data as $type=>$lang_arr)
		{
			foreach($lang_arr as $lang=>$id_arr)
			{
				if($id_arr)
				{
					foreach($id_arr as $id=>$text)
					{
						$promo_text_obj = $this->promotion_text_model->get(array("id"=>$id, "status"=>1));
						$action = "update";

						if(!$promo_text_obj)
						{
							$promo_text_obj = $this->promotion_text_model->get();
							$action = "insert";
						}
						$promo_text_obj->set_platform_type(strtoupper($type));
						$promo_text_obj->set_lang_id($lang);
						$promo_text_obj->set_promo_text($text);
						$promo_text_obj->set_status(1);

						$this->promotion_text_model->{$action}($promo_text_obj);
					}
				}
				else
				{
					$action = "insert";
					$promo_text_obj = $this->promotion_text_model->get();

					$promo_text_obj->set_platform_type(strtoupper($type));
					$promo_text_obj->set_lang_id($lang);
					$promo_text_obj->set_promo_text($text);
					$promo_text_obj->set_status(1);

					$this->promotion_text_model->{$action}($promo_text_obj);
				}
			}
		}
	}

	public function platform_level_update($platform_type="", $lang_id="", $platform_id="", $data=array())
	{
		foreach($data as $type=>$lang_arr)
		{
			foreach($lang_arr as $lang=>$plat_id_arr)
			{
				if($plat_id_arr)
				{
					foreach($plat_id_arr as $plat=>$plat_id)
					{
						if($plat_id != "")
						{
							foreach($plat_id as $id=>$text)
							{
								$promo_text_obj = $this->promotion_text_model->get(array("id"=>$id, "status"=>1));
								$action = "update";

								if(!$promo_text_obj)
								{
									$promo_text_obj = $this->promotion_text_model->get();
									$action = "insert";
								}
								$promo_text_obj->set_platform_type(strtoupper($type));
								$promo_text_obj->set_lang_id($lang);
								$promo_text_obj->set_platform_id($plat);
								$promo_text_obj->set_promo_text($text);
								$promo_text_obj->set_status(1);

								$this->promotion_text_model->{$action}($promo_text_obj);
							}
						}
						else
						{
							$action = "insert";
							$promo_text_obj = $this->promotion_text_model->get();

							$promo_text_obj->set_platform_type(strtoupper($type));
							$promo_text_obj->set_lang_id($lang);
							$promo_text_obj->set_platform_id($plat);
							$promo_text_obj->set_promo_text($text);
							$promo_text_obj->set_status(1);

							$this->promotion_text_model->{$action}($promo_text_obj);
						}
					}
				}
			}
		}
	}

	public function sku_level_update($platform_type="", $lang_id="", $platform_id="", $sku="", $data=array())
	{
		foreach($data as $type=>$lang_arr)
		{
			foreach($lang_arr as $lang=>$plat_id_arr)
			{
				if($plat_id_arr)
				{
					foreach($plat_id_arr as $plat=>$plat_id)
					{
						if($plat_id != "")
						{
							foreach($plat_id as $id=>$text)
							{
								$promo_text_obj = $this->promotion_text_model->get(array("id"=>$id, "status"=>1));
								$action = "update";

								if(!$promo_text_obj)
								{
									$promo_text_obj = $this->promotion_text_model->get();
									$action = "insert";
								}
								$promo_text_obj->set_platform_type(strtoupper($type));
								$promo_text_obj->set_lang_id($lang);
								$promo_text_obj->set_platform_id($plat);
								$promo_text_obj->set_sku($sku);
								$promo_text_obj->set_promo_text($text);
								$promo_text_obj->set_status(1);

								$this->promotion_text_model->{$action}($promo_text_obj);
							}
						}
						else
						{
							$action = "insert";
							$promo_text_obj = $this->promotion_text_model->get();

							$promo_text_obj->set_platform_type(strtoupper($type));
							$promo_text_obj->set_lang_id($lang);
							$promo_text_obj->set_platform_id($plat);
							$promo_text_obj->set_sku($sku);
							$promo_text_obj->set_promo_text($text);
							$promo_text_obj->set_status(1);

							$this->promotion_text_model->{$action}($promo_text_obj);
						}
					}
				}
			}
		}
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