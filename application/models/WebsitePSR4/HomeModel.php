<?php
namespace ESG\Panther\Models\Website;

use ESG\Panther\Service\WebsiteService;

class HomeModel extends \CI_Model
{
    private $website_service;

    public function __construct()
    {
        parent::__construct();

        $this->website_service = new WebsiteService;
    }

    public function getContent()
    {
        return $this->website_service->getHomeContent(SITE_LANG);
    }
}
