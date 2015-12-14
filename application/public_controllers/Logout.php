<?php

class Logout extends PUB_Controller
{
    public function __construct()
    {
        DEFINE("SKIPCUR", 1);
        parent::__construct();
        $this->load->helper('url');
    }

    public function index()
    {
        unset($_SESSION["client"]);
        unset($_SESSION["NOTICE"]);
        Redirect(base_url() . "login/index");
    }

}
