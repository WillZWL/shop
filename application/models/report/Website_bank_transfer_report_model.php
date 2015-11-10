<?php

class Website_bank_transfer_report_model extends CI_Model
{
    private $tool_path;

    public function __construct()
    {
        parent::__construct();
        $this->tool_path = $tool_path;
        $this->load->library('service/country_service');
    }


}

?>