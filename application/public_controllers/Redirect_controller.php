<?php
defined('BASEPATH') OR exit('No direct script access allowed');

DEFINE ('ALLOW_REDIRECT_DOMAIN', 1);

Class Redirect_controller extends PUB_Controller
{
	public function __construct()
	{
		parent::__construct();
		// $this->load->library('template');
		// $this->template->set_template('default');
		// $this->load->helper(array('url', 'tbswrapper'));
		$this->load->model('website/home_model');
		$this->load->library('service/affiliate_service');
		$this->load->library('service/price_website_service');
	}

	function index()
	{
		#SBF #2284 Tradedoubler tracking variable js portion
		// MANDATORY initialization: the tradedoubler_tracking_script_service with our current country
		// $this->tradedoubler_tracking_script_service->set_country_id(PLATFORMCOUNTRYID);
		// $td_variable_code = $this->tradedoubler_tracking_script_service->get_variable_code("homepage","","");
		// $this->template->add_js($td_variable_code, "print");
		// $this->load->view('new_head', array());
		// $this->load->view('index');

		// $data['data']['lang_text'] = $this->_get_language_file();
		// $listing_status = array("I"=>$data['data']['lang_text']['index_in_stock'], "O"=>$data['data']['lang_text']['index_out_of_stock'], "P"=>$data['data']['lang_text']['index_pre_order'], "A"=>$data['data']['lang_text']['index_arriving']);
		// $this->affiliate_service->add_af_cookie($_GET);

		// if ($this->input->get("wysiwyg"))
		// {
		// 	$_SESSION["origin_website"] = $this->input->get("wysiwyg");
		// }

		// if ($this->input->get("tduid") != "")
		// {
		// 	$cookieDuration = 3600*24*30;
		// 	setcookie('tduid', $this->input->get("tduid"), time()+$cookieDuration, "/");
		// }
		$value = $this->home_model->get_content();

		if ($value["best_seller"]) {
			$best_seller = array();
			foreach ($value["best_seller"] AS $key => $obj) {
				$best_seller[$key]["sku"] = $obj->get_sku();
				$best_seller[$key]["prod_name"] = $obj->get_prod_name();
				$best_seller[$key]["listing_status"] = $obj->get_status();
				// $best_seller[$key]["stock_status"] =  ($obj->get_status() == 'I') ? $obj->get_qty()." ".$listing_status[$obj->get_status()] : $listing_status[$obj->get_status()];
				$best_seller[$key]["price"] = $obj->get_price();
				$best_seller[$key]["rrp_price"] = $obj->get_rrp_price();
				$best_seller[$key]["discount"] = number_format(($obj->get_rrp_price() == 0 ? 0 : ($obj->get_rrp_price() - $obj->get_price()) / $obj->get_rrp_price() * 100), 0);
				$best_seller[$key]["prod_url"] = $this->home_model->get_prod_url($obj->get_sku());
				$best_seller[$key]["short_desc"] = $obj->get_short_desc();
				$best_seller[$key]["image_ext"] = $obj->get_image_ext();
			}
			$data["best_seller"] = $best_seller;
		}

		if ($value["latest_arrival"]) {
			$latest_arrival = array();
			foreach ($value["latest_arrival"] AS $key => $obj) {
				$latest_arrival[$key]["sku"] = $obj->get_sku();
				$latest_arrival[$key]["prod_name"] = $obj->get_prod_name();
				$latest_arrival[$key]["listing_status"] = $obj->get_status();
				// $latest_arrival[$key]["stock_status"] =  ($obj->get_status() == 'I') ? $obj->get_qty()." ".$listing_status[$obj->get_status()] : $listing_status[$obj->get_status()];
				$latest_arrival[$key]["price"] = $obj->get_price();
				$latest_arrival[$key]["rrp_price"] = $obj->get_rrp_price();
				$latest_arrival[$key]["discount"] = number_format(($obj->get_rrp_price() == 0 ? 0 : ($obj->get_rrp_price() - $obj->get_price()) / $obj->get_rrp_price() * 100), 0);
				$latest_arrival[$key]["prod_url"] = $this->home_model->get_prod_url($obj->get_sku());
				$latest_arrival[$key]["short_desc"] = $obj->get_short_desc();
				$latest_arrival[$key]["image_ext"] = $obj->get_image_ext();
			}
			$data["latest_arrival"] = $latest_arrival;
		}

		if ($value["clearance_product"]) {
			$clearance_product = array();
			foreach ($value["clearance_product"] AS $key => $obj) {
				$clearance_product[$key]["sku"] = $obj->get_sku();
				$clearance_product[$key]["prod_name"] = $obj->get_prod_name();
				$clearance_product[$key]["listing_status"] = $obj->get_status();
				// $clearance_product[$key]["stock_status"] = ($obj->get_status() == 'I') ? $obj->get_qty()." ".$listing_status[$obj->get_status()] : $listing_status[$obj->get_status()];
				$clearance_product[$key]["price"] = $obj->get_price();
				$clearance_product[$key]["rrp_price"] = $obj->get_rrp_price();
				$clearance_product[$key]["discount"] = number_format(($obj->get_rrp_price() == 0 ? 0 : ($obj->get_rrp_price() - $obj->get_price()) / $obj->get_rrp_price() * 100), 0);
				$clearance_product[$key]["prod_url"] = $this->home_model->get_prod_url($obj->get_sku());
				$clearance_product[$key]["short_desc"] = $obj->get_short_desc();
				$clearance_product[$key]["image_ext"] = $obj->get_image_ext();
			}
			$data["clearance_product"] = $clearance_product;
		}

// var_dump($data);

		$this->load->view('index', $data);
		// $data["show_discount_text"] = $this->price_website_service->is_display_saving_message();

		// $this->template->add_title($data['data']['lang_text']['index_meta_title']);
		// $this->template->add_meta(array('name'=>'description','content'=>$data['data']['lang_text']['index_meta_desc']));
		// $this->template->add_meta(array('name'=>'keywords','content'=>$data['data']['lang_text']['index_meta_keywords']));
		// $enable_mediaforge_country = array('GB', 'AU', 'FR', 'ES');
		// if(in_array(PLATFORMCOUNTRYID, $enable_mediaforge_country))
		// {
		// 	// mediaforge - added by SBF#1902
		// 	$enable_mediaforge = true;
		// 	if ($enable_mediaforge)
		// 	{
		// 		if (PLATFORMCOUNTRYID == 'GB') $account_no = 1038;
		// 		if (PLATFORMCOUNTRYID == 'AU') $account_no = 1059;
		// 		if (PLATFORMCOUNTRYID == 'FR') $account_no = 1411; #SBF#2229
		// 		if (PLATFORMCOUNTRYID == 'ES') $account_no = 1519; #SBF#2404

		// 		// function add_js($script, $type = 'import', $defer = FALSE, $position = "header")
		// 		$this->template->add_js("//tags.mediaforge.com/js/$account_no", "import", FALSE, "body");
		// 	}

		// 		// criteo - removed by SBF#1902
		// 	$enable_criteo = false;
		// 	if ($enable_criteo)
		// 	{
		// 		if ($data['is_http']) {
		// 			$this->template->add_js("http://static.criteo.net/criteo_ld3.js");
		// 		}
		// 		else
		// 		{
		// 			$this->template->add_js("https://static.criteo.net/criteo_ld3.js");
		// 		}
		// 		$criteo_script =
		// 		'
		// 			document.write(\'<div id=\"cto_se_7719983_ac\" style=\"display:none\">\');
		// 			document.write(\'<div class=\"ctoWidgetServer\">http:\/\/valuebasketuk.widget.criteo.com\/pvx\/<\/div>\');
		// 			document.write(\'<div class=\"ctoDataType\">sendEvent<\/div>\');
		// 			document.write(\'<div class=\"ctoParams\">wi=7719983&pt1=0&pt2=1<\/div>\');
		// 			document.write(\'<\/div>\');
		// 		';
		// 		$this->template->add_js($criteo_script, 'embed');
		// 	}
		// }

		// $data["header_menu"] = "big_";
		// $this->template->add_link("rel='canonical' href='".base_url()."'");
		// $this->load_tpl('content', 'tbs_home', $data, TRUE);
	}

	function display_banner()
	{
		$header_banner = $this->home_model->display_banner_service->get_publish_banner(0, 1, PLATFORMCOUNTRYID, get_lang_id(), "PB");
		$banner = $header_banner;
		include APPPATH."public_views/banner_publish/publish_".$banner["publish_key"].".php";
	}
}
