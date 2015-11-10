<?php
use GeoIp2\Database\Reader;

class DomainSelection
{
    private $domain = '';
    private $countryCode = 'GB';
    private $lang = 'en_GB';

    public function __construct()
    {
        if (strpos($_SERVER['HTTP_HOST'], ':') === false) {
            $this->setDomain($_SERVER['HTTP_HOST']);
        } else {
            list($domain) = explode(':', $_SERVER['HTTP_HOST']);
            $this->setDomain($domain);
        }
    }

    public function checkDomain()
    {
        $_SESSION['countryCodeFromHook'] = $this->getCountryCode();
    }

    public function convertIp2Country($ip = '')
    {
        $countryCode = '';

        if ($ip == "")
        {
            if (isset($_GET['ip']))
                $ip = $_GET['ip'];
        }

        try {
            $reader = new Reader(config_item('maxmind_db_path'));

            $ip = $ip ? $ip : $_SERVER['REMOTE_ADDR'];
            $record = $reader->city($ip);
            $countryCode = $record->country->isoCode;
            $reader->close();
        } catch (Exception $e) {
            // IP not in database
        }

        if (empty($countryCode)) {
            $countryCode = 'HK';
        }
        setcookie('countryCode', $countryCode, time()+3600, '/', $this->getDomain());
        return $countryCode;
    }

    public function getDomain()
    {
        return $this->domain;
    }

    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

    public function getCountryCode()
    {
        if (isset($_GET['ip']) && $_GET['ip']) {
            $this->setCountryCode($this->convertIp2Country());
        } elseif (isset($_COOKIE['countryCode'])) {
            $this->setCountryCode($_COOKIE['countryCode']);
        } else {
            $this->setCountryCode($this->convertIp2Country());
        }

        return $this->countryCode;
    }

    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;
    }

    public function getLang()
    {
        return $this->lang;
    }

    public function setLang($lang)
    {
        $this->lang = $lang;
    }
}
