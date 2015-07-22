<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
        $currentDomain = $this->getDomain();
        $redirectDomain = $this->getDomainByCountry();

        if ($currentDomain != $redirectDomain) {
            $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https://" : "http://";

            if ($_SERVER['SERVER_PORT'] != '80') {
                $newUrl = $protocol . $redirectDomain . ':' . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];
            } else {
                $newUrl = $protocol . $redirectDomain . $_SERVER['REQUEST_URI'];
            }

            header("Location: " . $newUrl);
            exit;
        }

        $this->setLocalization();

        $_SESSION['countryCodeFromHook'] = $this->getCountryCode();
    }

    public function getDomainByCountry()
    {
        $countryCode = strtoupper($this->getCountryCode());

        define(PLATFORMID, 'WEB'.$countryCode);

        switch ($countryCode) {
            case 'GB':
                $domain = 'v2.digitaldiscount.co.uk';
                break;
            case 'FR':
                $domain = 'v2.numeristock.fr';
                break;

            default:
                $domain = 'v2.digitaldiscount.co.uk';
                break;
        }

        return $domain;
    }

    /**
     * Return country code
     * @return string
     */
    public function convertIp2Country($ip = '')
    {
        $countryCode = '';

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

        // hard code for develop
        $countryCode = 'GB';

        return $countryCode;
    }

    public function setLocalization()
    {
        setcookie('lang', $this->getLang());
        putenv('LANG=' . $this->getLang());
        setlocale(LC_ALL, $this->getLang());

        $domain = 'message';

        bind_textdomain_codeset($domain, 'UTF-8');

        textdomain($domain);
    }

    /**
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param string $domain
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

    /**
     * @return string
     */
    public function getCountryCode()
    {
        if (isset($_COOKIE['countryCode'])) {
            $this->setCountryCode($_COOKIE['countryCode']);
        } else {
            $this->setCountryCode($this->convertIp2Country());
        }

        return $this->countryCode;
    }

    /**
     * @param string $countryCode
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;
    }

    /**
     * @return string
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * @param string $lang
     */
    public function setLang($lang)
    {
        $this->lang = $lang;
    }

}
