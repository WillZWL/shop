<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use GeoIp2\Database\Reader;

class CountrySelection
{
    private $port = '';
    private $domain = '';
    private $countryCode = '';
    private $lang = '';

    public function __construct()
    {
        list($this->domain, $this->port) = explode(':', $_SERVER['HTTP_HOST']);
    }

    public function redirectUrl()
    {
        $currentDomain = $this->domain;
        $rewriteDomain = $this->getDomainByCountry();

        if ($currentDomain != $rewriteDomain) {
            $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https://" : "http://";

            if ($_SERVER['SERVER_PORT'] != '80') {
                $newUrl = $protocol . $rewriteDomain . ':' . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];
            } else {
                $newUrl = $protocol . $rewriteDomain . $_SERVER['REQUEST_URI'];
            }

            header("Location: " . $newUrl);
            exit;
        }
    }

    public function getDomainByCountry()
    {
        $countryCode = strtoupper($this->getCountryCode());

        define(PLATFORMID, 'WEB'.$countryCode);

        switch ($countryCode) {
            case 'GB':
                $this->domain = 'v2.digitaldiscount.co.uk';
                break;
            case 'FR':
                $this->domain = 'numeristock.fr';
                break;

            default:
                $this->domain = 'v2.digitaldiscount.co.uk';
                break;
        }

        return $this->domain;
    }

    public function getCountryCode()
    {
        if (isset($_COOKIE['countryCode'])) {
            $countryCode = $_COOKIE['countryCode'];
        } else {
            $countryCode = $this->IptoCountry();
        }

        return $countryCode;
    }

    public function IptoCountry($ip = '')
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

        // hardcode for develop
        $countryCode = 'GB';

        $_SESSION['countryCodeFromHook'] = $countryCode;

        return $countryCode;
    }

}
