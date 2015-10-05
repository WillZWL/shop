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
        if (($page != "shipping")
            && ($page != "conditions_of_use")
            && ($page != "about_us")
            && ($page != "privacy_policy")
            && ($page != "contact"))
        {
//very important to do page parameter validation
            show_404();
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
