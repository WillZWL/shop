<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Wms_connection_service extends Base_service
{
    private $config;
    private $htaccess_username;
    private $htaccess_password;
    private $base_url;

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/service/Context_config_service.php");
        $this->set_config(new Context_config_service());

        $this->init();
    }

    public function init()
    {
        $this->set_htaccess_username($this->get_config()->value_of('wms_htaccess_username'));
        $this->set_htaccess_password($this->get_config()->value_of('wms_htaccess_password'));
        $this->set_base_url($this->get_config()->value_of('wms_base_url'));
    }

    public function get_config()
    {
        return $this->config;
    }

    public function set_config($value)
    {
        $this->config = $value;
    }

    public function get_xml($url, $post_data)
    {
        $ch = $this->init_curl();
        curl_setopt($ch, CURLOPT_URL, $this->get_base_url() . $url);
        curl_setopt($ch, CURLOPT_POST, count($post_data));
        curl_setopt($ch, CURLOPT_POSTFIELDS, implode('&', $post_data));

        $xml = curl_exec($ch);
        curl_close($ch);

        return $xml;
    }

    public function init_curl()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, false);
        curl_setopt($ch, CURLOPT_USERPWD, $this->get_htaccess_username() . ':' . $this->get_htaccess_password());
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        return $ch;
    }

    public function get_htaccess_username()
    {
        return $this->htaccess_username;
    }

    public function set_htaccess_username($username)
    {
        $this->htaccess_username = $username;
    }

    public function get_htaccess_password()
    {
        return $this->htaccess_password;
    }

    public function set_htaccess_password($password)
    {
        $this->htaccess_password = $password;
    }

    public function get_base_url()
    {
        return $this->base_url;
    }

    public function set_base_url($url)
    {
        $this->base_url = $url;
    }
}

?>