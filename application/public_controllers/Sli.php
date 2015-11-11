<?php
defined('BASEPATH') OR exit('No direct script access allowed');

DEFINE ('ALLOW_REDIRECT_DOMAIN', 1);

class Sli extends PUB_Controller
{
    public function Sli()
    {
        parent::PUB_Controller();
        $this->template->set_template('sli');
        $this->load->helper(array('tbswrapper'));
        $this->load->library('service/affiliate_service');
    }

    public function index()
    {
        $this->load_tpl('content', 'tbs_sli', "", TRUE);
    }
}

?>
