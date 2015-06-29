<?php
defined('BASEPATH') OR exit('No direct script access allowed');

DEFINE ('ALLOW_REDIRECT_DOMAIN', 1);

class Xmasdeals extends PUB_Controller
{

    private $lang_id = "en";

    public function Xmasdeals()
    {
        parent::PUB_Controller();
        $this->load->helper(array('url'));
    }

    public function index()
    {
        $this->load_view("xmasdeals.php",$data);
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }
}


?>
