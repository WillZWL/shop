<?php
defined('BASEPATH') OR exit('No direct script access allowed');

DEFINE ('ALLOW_REDIRECT_DOMAIN', 1);

class MainProduct extends PUB_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('website/website_model');
        $this->load->model('website/common_data_prepare_model');
        $this->load->model('marketing/product_model');
        $this->load->model('marketing/category_model');
        $this->load->model('marketing/best_seller_model');
        $this->load->model('marketing/upselling_model');
        $this->load->model('marketing/warranty_model');
        $this->load->library('service/affiliate_service');
        $this->load->library('service/price_website_service');
        $this->load->library('service/context_config_service');
        $this->load->library('service/price_margin_service');
        $this->load->library('service/deliverytime_service');
    }

    public function view($sku = '', $sv = false)
    {
        $data = array();

        $data['prod_info'] = $this->common_data_prepare_model->get_data_array($this, array("sku" => $sku, "type" => "web"));
		$data['sv'] = $sv;
        $data["tracking_data"]=array(
            "sku"=>$data['prod_info']["sku"],
            "product_name"=>$data['prod_info']["prod_name"],
            "category_name"=>$data['prod_info']["category_name"],
            "price"=>$data['prod_info']["prod_price"],
            );

        $siteobj = \PUB_Controller::$siteInfo;
        $data["countryid"] = $siteobj->getPlatformCountryId();
        $this->load->view('product', $data);
    }
}
