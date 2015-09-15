<?php
namespace ESG\Panther\Service;

class LoadSiteParameterService extends BaseService
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

    public function initSite()
    {
        $where = [
            'sc.domain' => $this->getDomain()
            , 'sc.status' => 1
            , 'sp.status' => 1
        ];

        $site_config_obj = $this->getSiteConfigService()->getDao()->getSiteInitialParameter($where);

        // set default site
        if (empty($site_config_obj)) {
            $where['sc.domain'] = 'digitaldiscount.co.uk';
            $site_config_obj = $this->getSiteConfigService()->getDao()->getSiteInitialParameter($where);
        }

        define('SITE_DOMAIN', $this->getDomain());
        define('SITE_NAME', $site_config_obj->getSiteName());
        define('SITE_LOGO', $site_config_obj->getLogo());
        define('SITE_EMAIL', $site_config_obj->getEmail());
        define('SITE_LANG', $site_config_obj->getLang());
        define('LANG_ID', $site_config_obj->getLangId());
        define('PLATFORM', $site_config_obj->getPlatform());
//        $this->set_lang_id($site_config_obj->getLangId());
        $this->setLocalization();
        return $site_config_obj;
    }

    private function setLocalization()
    {
        setcookie('lang', SITE_LANG, time()+3600, '/', $this->getDomain());
        putenv('LANG=' . SITE_LANG);
//        setlocale(LC_MESSAGES, SITE_LANG);
        setlocale(LC_NUMERIC, 'en_US');

        $domain = 'message';
        bindtextdomain($domain, I18N."Locale");
        bind_textdomain_codeset($domain, 'UTF-8');

        textdomain($domain);
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
