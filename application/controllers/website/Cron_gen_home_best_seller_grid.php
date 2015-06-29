<?php

class Cron_gen_home_best_seller_grid extends MY_Controller
{

    function __construct()
    {

        // load controller parent
        parent::__construct();
        $this->load->model('website/home_model');
        $this->load->helper('url');
    }

    function index()
    {
        $this->home_model->gen_home_best_seller_grid();

        echo "Done!";
    }
}


