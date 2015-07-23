<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Tracking_service extends Base_service
{
    private $subject_domain_service;

    public function __construct()
    {
        parent::__construct();

        include_once(APPPATH . 'libraries/service/Subject_domain_service.php');
        $this->set_subject_domain_service(new Subject_domain_service());
    }

    public function get_google_account_code($key = 'WEBSITE')
    {
        $obj = $this->get_subject_domain_service()->get(array('subject' => 'WEB.GOOGLE_ANALYTICS.ACCT_CD.' . $key));

        if ($obj) {
            return $obj->get_value();
        }

        return FALSE;
    }

    public function get_subject_domain_service()
    {
        return $this->subject_domain_service;
    }

    public function set_subject_domain_service($serv)
    {
        return $this->subject_domain_service = $serv;
    }
}