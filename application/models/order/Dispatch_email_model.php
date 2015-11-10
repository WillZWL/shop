<?php

class Dispatch_email_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('service/dispatch_email_service');
    }

    function dispatch_email()
    {
        $this->dispatch_email_service->dispatch_email();
    }
}
