<?php

class Video extends MY_Controller
{

	private $app_id = "MKT0040";
	private $lang_id = "en";

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('url','directory','notice'));
		$this->load->model('marketing/video_model');
		$this->load->library('service/pagination_service');
	}

	public function index()
	{
		redirect(base_url()."marketing/video/main");
	}

	public function main($lang_id="", $country_id="")
	{
		$data["country_id"] = $country_id;
		$data["lang_id"] = $lang_id;
		/*
		$country = $this->input->post('country');
		if(!empty($country))
		{
			$data['country_str'] = implode("~", $country);
		}
		*/
		if($lang_id)
		{
			$data["display"] = 1;
		}
		else
		{
			$data["display"] = 0;
		}
		$sub_app_id = $this->_get_app_id()."01";
		include_once(APPPATH."language/".$sub_app_id."_".$this->_get_lang_id().".php");
		$data["lang"] = $lang;

		$data['language_list'] = $this->video_model->get_lang_list(array("status"=>1), array("orderby"=>"name ASC"));
		$data['language_id'] = $lang_id;
		$data['country_list'] = $this->video_model->get_country_list(array("status"=>1), array("orderby"=>"name ASC"));
		$data['country_list_w_lang'] = $this->video_model->get_country_list(array("status"=>1, "language_id"=>$lang_id), array("orderby"=>"name ASC"));

		$data['platform_id_list'] = $this->video_model->get_platform_id_list(array("type"=>"WEBSITE"), array("orderby"=>"id ASC"));
		$this->load->view('marketing/video/video_index', $data);
	}

	public function view_left($lang_id="", $country="")
	{
		if($lang_id =="")
		{
			$lang_id = $this->input->get('lang_id');
		}
		if($country =="")
		{
			$country = $this->input->get('country');
		}

		$data['country'] = $country;
		$data['lang_id'] = $lang_id;

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
				$order = "ASC";

			$option["orderby"] = $sort." ".$order;
			$option["pricegtzero"] = "1";

			$data["objlist"] = $this->video_model->get_product_list($where, $option);
			$data["total"] = $this->video_model->get_product_list_total($where, $option);

			$pconfig['total_rows'] = $data['total'];
			$this->pagination_service->set_show_count_tag(TRUE);
			$this->pagination_service->msg_br = TRUE;
			$this->pagination_service->initialize($pconfig);

			$data["notice"] = notice($lang);

			$data["sortimg"][$sort] = "<img src='".base_url()."images/".$order.".gif'>";
			$data["xsort"][$sort] = $order=="asc"?"desc":"asc";
		}
		$this->load->view('marketing/video/video_view_left',$data);
	}

	public function view_right($sku="", $lang_id="", $country="", $id="")
	{
		$sub_app_id = $this->_get_app_id()."03";
		include_once(APPPATH."language/".$sub_app_id."_".$this->_get_lang_id().".php");
		$data["lang"] = $lang;

		$data["cmd"] = "edit";
		$data["id"] = $id;
		$data["sku"] = $sku;
		$data["country"] = $country;
		$c_list = explode("~",$country);
		$data["lang_id"] = $lang_id;

		if($lang_id == "ALL")
		{
			$data["obj_list"] = $this->video_model->get_video_list_w_country($sku, $c_list);
			$data["num_rows"] = $this->video_model->get_num_rows_w_country($sku, $c_list);
		}
		else
		{
			$data["obj_list"] = $this->video_model->get_list(array("sku"=>$sku, "lang_id"=>$lang_id));
			$data["num_rows"] = $this->video_model->product_service->get_pv_dao()->get_num_rows(array("sku"=>$sku, "lang_id"=>$lang_id));
		}

		$data["lang_list"] = $this->video_model->get_lang_list(array("status"=> 1), array("orderby"=>"id"));
		$data["pbv"] = $this->video_model->get_platform_biz_var(array("platform_country_id"=>'AU'));
		if($this->input->post('update'))
		{
			$video_obj = $this->video_model->get_video_obj(array('id'=>$id));
			$video_obj->set_lang_id($this->input->post('lang'));
			$video_obj->set_type($this->input->post('type'));
			$video_obj->set_src($this->input->post('src'));
			$video_obj->set_ref_id($this->input->post('ref_id'));
			$video_obj->set_description($this->input->post('description'));

			if(!$this->video_model->update_product_video($video_obj))
			{
				$_SESSION["NOTICE"] = $this->db->_error_message();
			}
		}
		elseif($this->input->post('remove'))
		{
			if(!$this->video_model->del_product_video(array("id"=>$id)))
			{
				$_SESSION["NOTICE"] = $this->db->_error_message();
			}
			redirect(base_url().'marketing/video/view_right/'.$sku.'/'.$lang_id.'/'.$country.'/'.$id);
		}
		elseif($this->input->post('add'))
		{
			foreach($c_list as $c)
			{
				$video_obj = $this->video_model->get_video_obj();
				$video_obj->set_sku($sku);
				$video_obj->set_country_id($c);
				$video_obj->set_lang_id($this->input->post('add_lang'));
				$video_obj->set_type($this->input->post('add_type'));
				$video_obj->set_src($this->input->post('add_src'));
				$video_obj->set_ref_id($this->input->post('add_ref_id'));
				$video_obj->set_description($this->input->post('add_desc'));
				$video_obj->set_status(1);

				if(!$this->video_model->add_product_video($video_obj))
				{
					$_SESSION["NOTICE"] = $this->db->_error_message();
				}
			}
			redirect(base_url().'marketing/video/view_right/'.$sku.'/'.$lang_id.'/'.$country.'/'.$id);
		}

		$this->load->view('marketing/video/video_view_right',$data);
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