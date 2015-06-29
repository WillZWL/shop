<?php

class Cron extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('order/dispatch_email_model');
        $this->load->helper(array("url"));
    }

    function cron_dispatch_email()
    {
        $this->dispatch_email_model->dispatch_email();
    }
}



