<?php
namespace AtomV2\Models\Website;

use AtomV2\Service\WebsiteService;

class HomeModel extends \CI_Model
{
    private $website_service;

    public function __construct()
    {
        parent::__construct();

        $this->website_service = new WebsiteService;

        // $this->load->library('service/language_service');
        // $this->load->library('service/country_service');
        // $this->load->library('service/selling_platform_service');
        // $this->load->library('service/display_banner_service');
        // $this->load->library('service/platform_biz_var_service');
        // $this->load->library('service/customer_service_info_service');
    }

    public function getContent()
    {
        return $this->website_service->getHomeContent(SITE_LANG);
    }
}
