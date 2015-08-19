<?php
<<<<<<< HEAD
defined('BASEPATH') OR exit('No direct script access allowed');

class Redirect_controller extends PUB_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('template');
        $this->load->model('website/home_model');
        $this->load->library('service/affiliate_service');
        $this->load->library('service/price_website_service');
=======

use AtomV2\Models\Website\HomeModel;

class Redirect_controller extends PUB_Controller
{
    private $home_model;

    public function __construct()
    {
        parent::__construct();
        $this->home_model = new HomeModel;
        // $this->load->model('Website/HomeModel', 'home_model');
        // $this->load->library('ervice/affiliate_service');
        // $this->load->library('service/price_website_service');
>>>>>>> 29ccc5cb624371694b2aa3dd7b3ed841fcd15669
    }

    public function index()
    {
        $data = [];

<<<<<<< HEAD
        $data['product'] = $this->home_model->get_content();

        //var_dump($data);die;
=======
        $data['product'] = $this->home_model->getContent();
>>>>>>> 29ccc5cb624371694b2aa3dd7b3ed841fcd15669

        $this->load->view('/default/index', $data);


        // $this->template->set_view('index');
        // Template::content();
        // $this->template->render();
        // die;

        // if ($value["best_seller"]) {
        //  $best_seller = array();
        //  foreach ($value["best_seller"] AS $key => $obj) {
        //      $best_seller[$key]["sku"] = $obj->get_sku();
        //      $best_seller[$key]["prod_name"] = $obj->get_prod_name();
        //      $best_seller[$key]["listing_status"] = $obj->get_status();
        //      // $best_seller[$key]["stock_status"] =  ($obj->get_status() == 'I') ? $obj->get_qty()." ".$listing_status[$obj->get_status()] : $listing_status[$obj->get_status()];
        //      $best_seller[$key]["price"] = $obj->get_price();
        //      $best_seller[$key]["rrp_price"] = $obj->get_rrp_price();
        //      $best_seller[$key]["discount"] = number_format(($obj->get_rrp_price() == 0 ? 0 : ($obj->get_rrp_price() - $obj->get_price()) / $obj->get_rrp_price() * 100), 0);
        //      $best_seller[$key]["prod_url"] = $this->home_model->get_prod_url($obj->get_sku());
        //      $best_seller[$key]["short_desc"] = $obj->get_short_desc();
        //      $best_seller[$key]["image_ext"] = $obj->get_image_ext();
        //  }
        //  $data["best_seller"] = $best_seller;
        // }

        // if ($value["latest_arrival"]) {
        //  $latest_arrival = array();
        //  foreach ($value["latest_arrival"] AS $key => $obj) {
        //      $latest_arrival[$key]["sku"] = $obj->get_sku();
        //      $latest_arrival[$key]["prod_name"] = $obj->get_prod_name();
        //      $latest_arrival[$key]["listing_status"] = $obj->get_status();
        //      // $latest_arrival[$key]["stock_status"] =  ($obj->get_status() == 'I') ? $obj->get_qty()." ".$listing_status[$obj->get_status()] : $listing_status[$obj->get_status()];
        //      $latest_arrival[$key]["price"] = $obj->get_price();
        //      $latest_arrival[$key]["rrp_price"] = $obj->get_rrp_price();
        //      $latest_arrival[$key]["discount"] = number_format(($obj->get_rrp_price() == 0 ? 0 : ($obj->get_rrp_price() - $obj->get_price()) / $obj->get_rrp_price() * 100), 0);
        //      $latest_arrival[$key]["prod_url"] = $this->home_model->get_prod_url($obj->get_sku());
        //      $latest_arrival[$key]["short_desc"] = $obj->get_short_desc();
        //      $latest_arrival[$key]["image_ext"] = $obj->get_image_ext();
        //  }
        //  $data["latest_arrival"] = $latest_arrival;
        // }

        // if ($value["clearance_product"]) {
        //  $clearance_product = array();
        //  foreach ($value["clearance_product"] AS $key => $obj) {
        //      $clearance_product[$key]["sku"] = $obj->get_sku();
        //      $clearance_product[$key]["prod_name"] = $obj->get_prod_name();
        //      $clearance_product[$key]["listing_status"] = $obj->get_status();
        //      $clearance_product[$key]["price"] = $obj->get_price();
        //      $clearance_product[$key]["rrp_price"] = $obj->get_rrp_price();
        //      $clearance_product[$key]["discount"] = number_format(($obj->get_rrp_price() == 0 ? 0 : ($obj->get_rrp_price() - $obj->get_price()) / $obj->get_rrp_price() * 100), 0);
        //      $clearance_product[$key]["prod_url"] = $this->home_model->get_prod_url($obj->get_sku());
        //      $clearance_product[$key]["short_desc"] = $obj->get_short_desc();
        //      $clearance_product[$key]["image_ext"] = $obj->get_image_ext();
        //  }
        //  $data["clearance_product"] = $clearance_product;
        // }

        // $this->load->view('index', $data);
    }

}
