<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Wow_email_service extends Base_service
{

    private $so_dao;

    function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/dao/So_dao.php");
        $this->set_so_dao(new So_dao());
        include_once(APPPATH . "libraries/service/Event_service.php");
        $this->set_event(new Event_service());
    }

    public function set_event($value)
    {
        $this->event = $value;
    }

    function get_wow_mail_list($where)
    {
        $this->get_so_dao()->get_wow_mail_list();
    }

    public function get_so_dao()
    {
        return $this->so_dao;
    }

    public function set_so_dao(Base_dao $dao)
    {
        $this->so_dao = $dao;
    }

    public function get_event()
    {
        return $this->event;
    }
}




