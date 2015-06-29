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
        //redirect($this->input->get("back")?urldecode($this->input->get("back")):base_url());
        Redirect(base_url()."login");
    }

}
