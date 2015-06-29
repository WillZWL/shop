<?php
defined('BASEPATH') OR exit('No direct script access allowed');

DEFINE ('ALLOW_REDIRECT_DOMAIN', 1);
class External_query extends PUB_Controller
{
    // this is for external parties
    // no login to admincentre needed, but still suscepted to htaccess
    function __construct()
    {
        parent::PUB_Controller();
        $this->load->model('marketing/external_query_model');

    }

    public function get_yandex_xml($platform_id = "WEBRU")
    {
        return $this->external_query_model->get_yandex_xml($platform_id);
    }

    public function get_ceneo_xml($platform_id = "WEBPL")
    {
        return $this->external_query_model->get_ceneo_xml($platform_id);
    }

}