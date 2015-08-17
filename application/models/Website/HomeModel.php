<?php
namespace AtomV2\Models\Website;

use AtomV2\Service\WebsiteService;

class HomeModel extends \CI_Model
{
    private $websiteService;

    public function __construct()
    {
        parent::__construct();
        $this->websiteService = new WebsiteService;
    }

    public function getContent()
    {
        return $this->websiteService->getHomeContent(SITE_LANG);
    }
}
