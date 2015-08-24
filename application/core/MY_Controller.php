<?php
defined('BASEPATH') OR exit('No direct script access allowed');

abstract class MY_Controller extends CI_Controller
{
    private $langId = "en";

    public function __construct($check_access_rights = TRUE)
    {
        parent::__construct();
        $this->load->library($this->_get_service());
        $_SESSION["CURRPAGE"] = $_SERVER['REQUEST_URI'];
        $currsign = array("GBP" => "£", "EUR" => "€");
        if ($this->config->item('uri_protocol') != "CLI") {
            $this->_check_authed();
            $this->load->library('service/Authorization_service');
            if ($check_access_rights) {
                $this->authorization_service->check_access_rights($this->getAppId(), "");
                $feature_list = $this->authorization_service->set_application_feature_right($this->getAppId(), "");
            }
        }
    }

    function _get_service()
    {
        return "service/Authentication_service";
    }

    private function _check_authed()
    {
        if (!$this->authentication_service->check_authed()) {
            $data["fail_msg"] = $this->_get_fail_msg();
            redirect($this->_get_login_page());
        }
    }

    function _get_fail_msg()
    {
        return "Please login to the system first!";
    }

    function _get_login_page()
    {
        return "?back=" . urlencode($_SESSION["CURRPAGE"]);
    }

    abstract public function getAppId();

    public function getLangId()
    {
        return $this->langId;
    }

    function _get_ru()
    {
        $ru = $_SESSION["CURRPAGE"];
        if ($pru = $this->input->post("ru")) {
            $this->load->library("encrypt");
            $ru = $this->encrypt->decode($pru);
        }
        return $ru;
    }
}
