<?php
defined('BASEPATH') OR exit('No direct script access allowed');

DEFINE ('ALLOW_REDIRECT_DOMAIN', 1);

class Display extends PUB_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url', 'directory', 'datetime', 'tbswrapper'));
        $this->load->model("website/home_model");
        $this->load->library('service/affiliate_service');
        $this->load->library('service/ip2country_service');
        $this->load->library('service/deliverytime_service');
    }

    public function view($page = '')
    {
        if (
            ($page != "shipping")
            && ($page != "conditions_of_use")
            && ($page != "about_us")
            && ($page != "privacy_policy")
            && ($page != "contact")
            && ($page != "contact_us")
        ) {
//very important to do page parameter validation
            show_404();
        }

        if ($page == 'contact') {
            $server_name = str_replace(['www'], '', $_SERVER['SERVER_NAME']);
            $data['contact_url_1'] = 'http://contact'  . $server_name . '/support/tickets/new?genaftersales=true';
            $data['contact_url_2'] = 'http://contact'  . $server_name . '/support/tickets/new?presales=true';
            $data['contact_url_3'] = 'http://contact'  . $server_name . '/support/tickets/new?faultorreturn=true';
        }

        $data["content"] = "display/" . $page;
        $this->load->view('display/view', $data);
    }

    public function promotions($page = '')
    {
        if (!$this->_is_special_promotion($page)) {
            show_404();
        }

        $data["page"] = $page;
        if ($page == "drone")
            $data["page"] = $page . "_" . strtolower(PLATFORMCOUNTRYID);
        $this->load_tpl('content', 'tbs_promotions', $data, TRUE);
    }

    private function _is_special_promotion($page)
    {
        if (($page != "audio-visual")
            && ($page != "drone")
        )
            return false;
        else
            return true;
    }
}

?>
