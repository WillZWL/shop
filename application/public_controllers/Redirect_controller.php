<?php
use AtomV2\Models\Website\HomeModel;

class Redirect_controller extends PUB_Controller
{
    private $homeModel;

    public function __construct()
    {
        parent::__construct();
        $this->homeModel = new HomeModel;
    }

    public function index()
    {
        $data = [];
        $data['product'] = $this->homeModel->getContent();
        $this->load->view('/default/index', $data);
    }
}
