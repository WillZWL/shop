<?php
namespace ESG\Panther\Service;

class EventService extends BaseService
{

    function __construct()
    {
        parent::__construct();
    }

    public function fireEvent($dto, $get_email_html = FALSE)
    {
        $result = $this->fireEventOnce($dto, $get_email_html);

        $CI =& get_instance();
        $testEmail = $CI->config->item('language_test_email');
        if (($testEmail)
            && ($testEmail['enable'] == 1)
        ) {
            $dto->set_lang_id($testEmail['lang_id']);
            $dto->replace_ini_only($testEmail['lang_id']);
            $dto->set_mail_to("test_language_email@eservicesgroup.com");
            $this->fireEventOnce($dto);
        }
        return $result;
    }

    public function fireEventOnce($dto, $get_email_html = FALSE)
    {
        if ($dto) {
            if ($acts = $this->getDao('Event')->getEventAction($dto->getEventId(), "ActionVo")) {
                foreach ($acts as $act_obj) {
                    $classname = $act_obj->getAction();

                    $classfile = APPPATH . "libraries/service/" . ucfirst(strtolower($classname)) . "Service.php";

                    if (file_exists($classfile)) {
                        include_once($classfile);
                        $classname = ucfirst($classname) . "Service";
                        $obj_act = new $classname();

                        if ($get_email_html === FALSE) {
                            $obj_act->run($dto);
                        } else {
                            if (method_exists($classname, "getEmailTemplate")) {
                                $email_msg = $obj_act->getEmailTemplate($dto);
                                return $email_msg;
                            } else {
                                return "eventService.php Line " . __LINE__ . " function getEmailTemplate()
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


