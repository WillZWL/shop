<?php
defined('BASEPATH') OR exit('No direct script access allowed');

DEFINE ('ALLOW_REDIRECT_DOMAIN', 1);

class Selector extends PUB_Controller
{
    public function Selector()
    {
        parent::PUB_Controller();
        $this->load->library('template');
        $this->load->helper(array('url', 'directory', 'datetime', 'tbswrapper'));
        $this->load->model("website/home_model");
        $this->load->library('service/affiliate_service');
    }

    public function Index($type = '', $title = '', $preset = '')
    {

        $data["primastar_type"] = $type;
        $data["primastar_title"] = str_replace("_", " ", $title);
        $data["primastar_preset"] = $preset;

        #$pb = new primastar_banner();

        $this->load_tpl('content', 'tbs_selector', $data, TRUE);
    }
}
