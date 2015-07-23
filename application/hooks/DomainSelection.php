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

    private function getDomainByCountry()
    {
        $countryCode = strtoupper($this->getCountryCode());

        define('PLATFORMID', 'WEB'.$countryCode);

        switch ($countryCode) {
            case 'GB':
                $domain = 'v2.digitaldiscount.co.uk';
                $this->setLang('en_GB');
                define(SITENAME, 'Digital Discount');
                define(LOGO, 'digitaldiscount.png');
                break;
            case 'ES':
                $domain = 'v2.buholoco.es';
                $this->setLang('es_ES');
                define(SITENAME, 'Buholoco');
                define(LOGO, 'buholoco.png');
                break;
            case 'FR':
                $domain = 'v2.numeristock.fr';
                $this->setLang('fr_FR');
                define(SITENAME, 'Numeri Stock');
                define(LOGO, 'numeristock.jpg');
                break;
            case 'BE':
                $domain = 'v2.numeristock.be';
                $this->setLang('fr_BE');
                define(SITENAME, 'Numeri Stock');
                define(LOGO, 'numeristock.jpg');
                break;
            case 'AU':
                $domain = 'v2.aheaddigital.com.au';
                $this->setLang('en_GB');
                define(SITENAME, 'Aheaddigital');
                define(LOGO, 'aheaddigital.jpg');
                break;
            case 'NZ':
                $domain = 'v2.aheaddigital.co.nz';
                $this->setLang('en_GB');
                define(SITENAME, 'Aheaddigital');
                define(LOGO, 'aheaddigital.jpg');
                break;
            case 'PL':
                $domain = 'v2.elektroraj.pl';
                $this->setLang('pl_PL');
                define(SITENAME, 'elektroraj');
                define(LOGO, 'elektroraj.jpg');
                break;
            case 'IT':
                $domain = 'v2.nuovadigitale.it';
                $this->setLang('it_IT');
                define(SITENAME, 'nuovadigitale');
                define(LOGO, 'nuovadigitale.jpg');
                break;

            default:
                $domain = 'v2.digitaldiscount.co.uk';
                define(SITENAME, 'Digital Discount');
                define(LOGO, 'digitaldiscount.png');
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

        $ip = $_GET['ip'];

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

    public function setLocalization()
    {
        setcookie('lang', $this->getLang(), time()+3600, '/', $this->getDomain());
        putenv('LANG=' . $this->getLang());
        setlocale(LC_CTYPE, $this->getLang());
        setlocale(LC_NUMERIC, 'en_US');

        $domain = 'message';
        bindtextdomain($domain, I18N."Locale");

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
        if ($_GET['ip']) {
            $this->setCountryCode($this->convertIp2Country());
        } elseif(isset($_COOKIE['countryCode'])) {
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
