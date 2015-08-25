<?php

class Cron_update_rrp_factor extends MY_Controller
{
    private $appId = "CRN0015";

    function __construct()
    {
        parent::__construct();
        $this->load->model('marketing/pricing_tool_website_model');
    }

    public function update_rrp_factor()
    {
        $this->pricing_tool_website_model->update_rrp_factor();
    }

    public function getAppId()
    {
        return $this->appId;
    }
}


