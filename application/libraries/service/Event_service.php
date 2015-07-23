<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Event_service extends Base_service
{

    function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/dao/Event_dao.php");
        $this->set_dao(new Event_dao());
    }

    public function fire_event(Base_dto $dto, $get_email_html = FALSE)
    {
//send normal email
        $result = $this->fire_event_once($dto, $get_email_html);

//send duplicate email
        $CI =& get_instance();
        $testEmail = $CI->config->item('language_test_email');
        if (($testEmail)
            && ($testEmail['enable'] == 1)
        ) {
            $dto->set_lang_id($testEmail['lang_id']);
            $dto->replace_ini_only($testEmail['lang_id']);
            $dto->set_mail_to("test_language_email@eservicesgroup.com");
            $this->fire_event_once($dto);
        }
        return $result;
    }

    public function fire_event_once(Base_dto $dto, $get_email_html = FALSE)
    {
        if ($dto) {
            include_once(APPPATH . "libraries/dao/Action_dao.php");
            $action_dao = new Action_dao();
            $action_dao->include_vo();
            if ($acts = $this->get_dao()->get_event_action($dto->get_event_id(), "Action_vo")) {
                foreach ($acts as $act_obj) {
                    $classname = $act_obj->get_action();
                    $classfile = APPPATH . "libraries/service/" . strtolower($classname) . "_service.php";
                    if (file_exists($classfile)) {
                        include_once($classfile);
                        $classname = ucfirst($classname) . "_service";
                        $obj_act = new $classname();

                        if ($get_email_html === FALSE) {
                            $obj_act->run($dto);
                        } else {
                            if (method_exists($classname, "get_email_template")) {
                                // $obj_act->run($dto);
                                $email_msg = $obj_act->get_email_template($dto);
                                return $email_msg;
                            } else {
                                return "event_service.php Line " . __LINE__ . " function get_email_template()
                                        does not exist in classname=$classname";
                            }
                        }
                    }
                }
            }
        } else {
            return FALSE;
        }
    }

}


