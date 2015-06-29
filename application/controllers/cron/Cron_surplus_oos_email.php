<?php

class Cron_surplus_oos_email extends MY_Controller
{
    private $app_id = 'CRN0030';

    function __construct()
    {
        parent::__construct();
        $this->load->library('service/surplus_email_service');
    }

    public function send_surplus_oos_email()
    {
        $this->surplus_email_service->send_surplus_oos_email();
    }

    public function _get_app_id()
    {
        return $this->app_id;
    }
}

/* End of file Cron_surplus_oos_email.php */
/* Location: ./app/controllers/cron_surplus_oos_email.php */
