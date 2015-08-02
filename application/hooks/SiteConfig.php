<?php

use AtomV2\Service\SiteConfigService;

class SiteConfig extends PUB_Controller
{

    private $domain = '';

    private $site_config_service;

    public function __construct()
    {
        parent::__construct();

        if (strpos($_SERVER['HTTP_HOST'], ':') === false) {
            $this->domain = $_SERVER['HTTP_HOST'];
        } else {
            list($domain) = explode(':', $_SERVER['HTTP_HOST']);
            $this->domain = $domain;
        }

        $this->setSiteConfigService(new SiteConfigService);
    }

    public function selectSite()
    {
        $where = [
            'domain' => $this->getDomain(),
            'status' => 1
        ];

        if ($site_config_obj = $this->getSiteConfigService()->getDao()->get($where)) {
            define('SITE_NAME', $site_config_obj->getSiteName());
            define('SITE_LOGO', $site_config_obj->getLogo());
            define('SITE_EMAIL', $site_config_obj->getEmail());
        }
    }

    public function getDomain()
    {
        return $this->domain;
    }

    public function getSiteConfigService()
    {
        return $this->site_config_service;
    }

    public function setSiteConfigService($site_config_service)
    {
        $this->site_config_service = $site_config_service;
    }

}
