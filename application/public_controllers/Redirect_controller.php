<?php

use ESG\Panther\Models\Website\HomeModel;

class Redirect_controller extends PUB_Controller
{
    private $home_model;

    public function __construct()
    {
        parent::__construct();
        $this->home_model = new HomeModel;
    }

    public function index()
    {
        $data = [];

        $data['product'] = $this->home_model->getContent();

        $this->load->view('index', $data);
    }

}
