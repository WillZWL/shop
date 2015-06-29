<?php
class Translate_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('service/translate_service');
    }
}
