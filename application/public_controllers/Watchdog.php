<?php
defined('BASEPATH') OR exit('No direct script access allowed');

DEFINE ('ALLOW_REDIRECT_DOMAIN', 1);

class Watchdog extends PUB_Controller
{

    public function Watchdog()
    {
        parent::PUB_Controller();
        $this->load->helper(array('url'));
        #$this->load->model('marketing/product_model');
        $this->load->model('marketing/category_model');
        #$this->load->model('marketing/best_seller_model');
        #$this->load->model('marketing/latest_arrivals_model');
        #$this->load->model('marketing/top_deals_model');
        #$this->load->model('marketing/banner_model');
    }

    public function index()
    {
        # get some data, if DB has problems, we will die here
        $datafound = count($this->category_model->get_colour_code());

        if ($datafound > 0) echo "DB is OK";
        die();
    }
}
