<?php
defined('BASEPATH') OR exit('No direct script access allowed');

DEFINE ('ALLOW_REDIRECT_DOMAIN', 1);

class Mainproduct extends PUB_Controller
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

    public function view($sku = '')
    {
        $data = array();
        $data = $this->common_data_prepare_model->get_data_array($this, array("sku" => $sku, "type" => "web"));
        $this->load->view('/default/product', $data);
        // $index = strripos($sku, "-", -7);
        // $sku = trim(substr($sku, $index), "- ");

        // // main page display

        // if ($data) {
        //     $this->load->view('/default/product', $data);
        //     // if ($this->upselling_model->get_ra($data, $sku, PLATFORMID, get_lang_id(), $listing_status)) {
        //     //     $this->template->add_js("/resources/js/jquery.gritter.js");
        //     //     $this->template->add_js("/js/common.js");
        //     //     $this->template->add_js("/js/upselling.js", "import", TRUE);
        //     //     $this->template->add_css("resources/css/jquery.gritter.css");
        //     // }

        //     // $this->template->add_js("/resources/js/jquery-ui.js");
        //     // $this->template->add_css("resources/css/jquery-ui.css");

        //     // $this->template->add_link("rel='canonical' href='" . base_url() . "/mainproduct/view/$sku'");
        //     // $this->load_tpl('content', 'tbs_product', $data, TRUE);

        // } else {
        //     show_404("page");
        // }
    }
}

