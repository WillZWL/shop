<?php

class Fireman_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/fireman_service');
    }

    public function send_report($type)
    {
        if ($type)
            $this->fireman_service->send_report($type);
    }
}

