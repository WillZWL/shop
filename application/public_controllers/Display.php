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
        if ($_SERVER["HTTPS"]=="on") {
            $xredir="http://".$_SERVER["SERVER_NAME"].
            $_SERVER["REQUEST_URI"];
            header("Location: ".$xredir);
        }

        if (
            ($page != "shipping")
            && ($page != "conditions_of_use")
            && ($page != "about_us")
            && ($page != "privacy_policy")
            && ($page != "contact")
            && ($page != "contact_us")
            && ($page != "faq")
        ) {
//very important to do page parameter validation
            show_404();
        }

        $http_type = (
                                (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
                                || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
                             ) ? 'https://' : 'http://';

        $data['server_name'] = str_replace(['www.'], '', $_SERVER['SERVER_NAME']);
        $data['server_name']  = ($data['server_name'] == "dduk.dev") ? "digitaldiscount.co.uk" : $data['server_name'] ;

        if ($page == 'contact') {
            $data['contact_url_1'] = $http_type . 'contact.'  . $data['server_name'] . '/support/tickets/new?genaftersales=true';
            $data['contact_url_2'] = $http_type . 'contact.'  . $data['server_name'] . '/support/tickets/new?presales=true';
            $data['contact_url_3'] = $http_type . 'contact.'  . $data['server_name'] . '/support/tickets/new?faultorreturn=true';

            $contact_info = $this->get_contact_info(PLATFORM);
            $data['contact_email'] = $contact_info['email'];
            $data['contact_tel'] = $contact_info['tel'];
            $data['contact_hotline'] = $contact_info['hotline'];

        }
        $data["http_type"] = $http_type;
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

    private function get_contact_info($platform_id = 'WEBGB')
    {
        $contact = [
            'WEBGB' => [
                'email' => 'support@digitaldiscount.co.uk',
                'tel' => '02071934191',
                'hotline'=>"(hotline support coming soon)",
            ],
            'WEBAU' => [
                'email' => 'support@aheaddigital.net',
                'tel' => '02071934191',
            ],
            'WEBNZ' => [
                'email' => 'support@aheaddigital.co.nz',
                'tel' => '',
            ],
            'WEBFR' => [
                'email' => 'support@numeristock.fr',
                'tel' => '02071934191',
                'hotline'=>"(Support téléphonique disponible prochainement)",
            ],
            'WEBBE' => [
                'email' => 'support@numeristock.be',
                'tel' => '02071934191',
            ],
            'WEBES' => [
                'email' => 'soporte@buholoco.es',
                'tel' => '02071934191',
                'hotline' =>'(Télefono de Atención al Cliente disponible pronto)',
            ],
            'WEBPL' => [
                'email' => 'support@elektroraj.pl',
                'tel' => '02071934191',
            ],
            'WEBIT' => [
                'email' => 'assistenza@nuovadigitale.it',
                'tel' => '0294755798',
                'hotline' => '(Supporto telefonico prossimamente)',
            ],
             'WEBNL' => [
                'email' => 'support@9digital.nl',
                'tel' => '02071934191',
            ],
        ];
        return $contact[$platform_id];
    }
}

?>
