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

        $data['banners'] = $banners = $this->sc['Banner']->getDao('Banner')->getList(['platform_id' => PLATFORM, 'type'=> 1, 'location' => 1, 'status' => 1], ['limit' => 5]);
        $data['banner_total'] = count((array) $banners);
        $this->load->view('index', $data);
    }

}
