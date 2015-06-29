<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Dispatch_email_service extends Base_service
{

    private $so_dao;

    function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/dao/Schedule_job_dao.php");
        $this->set_sjob_dao(new Schedule_job_dao());
        include_once(APPPATH . "libraries/dao/So_dao.php");
        $this->set_so_dao(new So_dao());
        include_once(APPPATH . "libraries/service/Event_service.php");
        $this->set_event(new Event_service());
    }

    public function set_sjob_dao(Base_dao $dao)
    {
        $this->sjob_dao = $dao;
    }

    public function set_event($value)
    {
        $this->event = $value;
    }

    function dispatch_email()
    {
        include_once APPPATH . "libraries/dto/event_email_dto.php";
        $email_dto = new Event_email_dto();

        $id = "dispatch_email";
        $now_access_time = date("Y-m-d H:i:s");
        $sjob_obj = $this->get_sjob_dao()->get(array("id" => $id, "status" => "1"));
        $last_access_time = $sjob_obj->get_last_access_time();

        $result = $this->get_so_dao()->get_dispatch_email_list($now_access_time, $last_access_time);
        if ($result) {
            foreach ($result as $obj) {
                $dispatch_email = $obj["email"];
                $bill_name = $obj["bill_name"];

                $tmp = clone $email_dto;
                $tmp->set_event_id("confirm_dispatch");
                $tmp->set_mail_to(array($dispatch_email));
                $tmp->set_mail_from("calista.smith@valuebasket.com");
                $tmp->set_tpl_id("dispatch_email");
                $tmp->set_replace(array("buyer" => $bill_name));
                $this->get_event()->fire_event($tmp);
            }
            $sjob_obj->set_last_access_time($now_access_time);
            $this->get_sjob_dao()->update($sjob_obj);
        }
    }

    public function get_sjob_dao()
    {
        return $this->sjob_dao;
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




