<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use GeoIp2\Database\Reader;

class Country_selection
{
    private $port = '';
    private $domain = '';
    private $country_code = '';
    private $lang = '';

    public function __construct()
    {
        list($this->domain, $this->port) = explode(':', $_SERVER['HTTP_HOST']);
    }

    public function redirect_url()
    {
        $current_domain = $this->domain;
        $rewrite_domain = $this->get_rewrite_domain_by_country();

        if ($current_domain != $rewrite_domain) {
            $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https://" : "http://";

            if ($_SERVER['SERVER_PORT'] != '80') {
                $new_url = $protocol . $rewrite_domain . ':' . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];
            } else {
                $new_url = $protocol . $rewrite_domain . $_SERVER['REQUEST_URI'];
            }

            header("Location: " . $new_url);
            exit;
        }
    }

    public function get_rewrite_domain_by_country()
    {
        $country_code = strtoupper($this->get_country_code());

        define(PLATFORMID, 'WEB'.$country_code);

        switch ($country_code) {
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

    public function get_country_code()
    {
        if (isset($_COOKIE['country_code'])) {
            $country_code = $_COOKIE['country_code'];
        } else {
            $country_code = $this->get_country_code_by_ip2country_provider();
        }

        return $country_code;
    }

    public function get_country_code_by_ip2country_provider($ip = '')
    {
        $country_code = '';

        try {
            $reader = new Reader(config_item('maxmind_db_path'));

            $ip = $ip ? $ip : $_SERVER['REMOTE_ADDR'];
            $record = $reader->city($ip);
            $country_code = $record->country->isoCode;
            $reader->close();
        } catch (Exception $e) {
            // IP not in database
        }

        if (empty($country_code)) {
            $country_code = 'HK';
        }

        // hardcode for develop
        $country_code = 'GB';

        $_SESSION['country_code_from_hook'] = $country_code;

        return $country_code;
    }

    public function validate_country_code($country_code)
    {
        if (!preg_match("/^[A-Za-z]{2}$/", $country_code)) {
            return FALSE;
        } else {
            return $country_code;
        }
    }

}
