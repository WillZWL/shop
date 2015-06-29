<?php
class Lang extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
    }

    function update_lang_id()
    {
        $lang_id = '';

        $valid_lang = $this->config->item('valid_lang');
        include_once(APPPATH."helpers/string_helper.php");

        $url_segment = $_SERVER['REQUEST_URI'];
        list(, $language_country_id) = explode('/', $url_segment);

        if ($language_country_id != '') {
            list($lang_id,) = explode('_', strtolower($language_country_id));
        }

        if (in_array($lang_id, $valid_lang)) {
            $_SESSION["lang_id"] = $lang_id;
            $this->config->set_item('lang_id', $lang_id);
        } else {
            //default to config['lang_id']
            if (defined('DOMAIN')) {
                if (strtoupper(DOMAIN) == 'VALUEBASKET.FR') {
                    $_SESSION["lang_id"] = 'fr';
                } elseif (strtoupper(DOMAIN) == 'VALUEBASKET.ES') {
                    $_SESSION["lang_id"] = 'es';
                } elseif (strtoupper(DOMAIN) == 'VALUEBASKET.IT') {
                    $_SESSION["lang_id"] = 'it';
                } elseif (strtoupper(DOMAIN) == 'VALUEBASKET.RU') {
                    $_SESSION["lang_id"] = 'ru';
                } elseif (strtoupper(DOMAIN) == 'VALUEBASKET.COM.MX') {
                    $_SESSION["lang_id"] = 'es';
                } else {
                    $_SESSION["lang_id"] = $this->config->item('lang_id');
                }
            } else {
                $_SESSION["lang_id"] = $this->config->item('lang_id');
            }
        }

        $tmp = strpos($_SERVER['HTTP_HOST'], 'valuebasket');
        $domain = substr($_SERVER['HTTP_HOST'], ($tmp ? $tmp : 0));


        setcookie("lang_id", "", 0, "/", $_SERVER['HTTP_HOST']);
        setcookie("lang_id", "", 0, "/", ".".$domain);
        // setcookie("lang_id", $_SESSION["lang_id"], time()+86400, "/", ".".$domain);
        $this->_setcookie("lang_id", $_SESSION["lang_id"]);
        // $_COOKIE["lang_id"] = $_SESSION["lang_id"];

        // var_dump($_SESSION["lang_id"]);
    }

    private function _setcookie($cookie_name, $value)
    {
        setcookie($cookie_name, $value, time()+86400, "/",  ".valuebasket.com");
        setcookie($cookie_name, $value, time()+86400, "/",  ".valuebasket.com.au");
        setcookie($cookie_name, $value, time()+86400, "/",  ".valuebasket.co.nz");
        setcookie($cookie_name, $value, time()+86400, "/",  ".valuebasket.com.sg");
        setcookie($cookie_name, $value, time()+86400, "/",  ".valuebasket.fr");
        setcookie($cookie_name, $value, time()+86400, "/",  ".valuebasket.es");
        setcookie($cookie_name, $value, time()+86400, "/",  ".valuebasket.com.mx");
        setcookie($cookie_name, $value, time()+86400, "/",  ".valuebasket.ru");
        setcookie($cookie_name, $value, time()+86400, "/",  ".valuebasket.pt");
        setcookie($cookie_name, $value, time()+86400, "/",  ".valuebasket.com.ph");
        setcookie($cookie_name, $value, time()+86400, "/",  ".valuebasket.be");
        setcookie($cookie_name, $value, time()+86400, "/",  ".valuebasket.it");
        setcookie($cookie_name, $value, time()+86400, "/",  ".valuebasket.pl");
    }
}