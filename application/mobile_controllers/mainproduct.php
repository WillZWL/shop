<?php
defined('BASEPATH') OR exit('No direct script access allowed');

DEFINE ('ALLOW_REDIRECT_DOMAIN', 1);

class Mainproduct extends MOBILE_Controller
{
	public function Mainproduct()
	{
		parent::MOBILE_Controller(array('template'=>'default'));
		$this->load->helper(array('url','image','tbswrapper'));
		$this->load->model('website/website_model');
		$this->load->model('website/common_data_prepare_model');
		$this->load->model('marketing/product_model');
		$this->load->model('marketing/category_model');
		$this->load->model('marketing/best_seller_model');
		$this->load->model('marketing/upselling_model');
		$this->load->library('service/affiliate_service');
		$this->load->library('service/price_website_service');
		$this->load->library('service/context_config_service');
		$this->load->library('service/price_margin_service');
		$this->load->library('service/deliverytime_service');
	}

	public function view($sku="")
	{
		// main page display
		$data = $this->common_data_prepare_model->get_data_array($this, array("sku" => $sku, "type" => "mobile"));

		if ($data) {

			$this->template->add_js("/js/common.js");
			$this->template->add_js("/resources/js/jquery-ui.js");
			$this->template->add_css("resources/css/jquery-ui.css");
			$this->template->add_link("rel='canonical' href='".base_url()."/mainproduct/view/$sku'");
			$this->load_tpl('content', 'product', $data, TRUE);

		} else {
			show_404("page");
		}

	}
}

