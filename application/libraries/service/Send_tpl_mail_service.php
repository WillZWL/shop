<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once(APPPATH . "libraries/service/Handle_action_service.php");

class Send_tpl_mail_service extends Handle_action_service
{

    function Send_tpl_mail_service(Base_dto $dto)
    {
        parent::Handle_action_service();
        $this->set_dto($dto);
    }

    public function run()
    {
        include_once(APPPATH . "libraries/service/Email_service.php");
        $email_service = new email_service();
        $dto = $this->get_dto();
        @$tpl_id = $dto->get_tpl_id();
        if ($tpl_id) {
            $from = $dto->get_mailfrom();
            $to = $dto->get_mailto();

            $class_methods = get_class_methods($dto);
            foreach ($class_methods as $fct_name) {
                if (substr($fct_name, 0, 4) == "get_") {
                    $rsvalue = call_user_func(array($dto, $fct_name));
                    $rskey = substr($fct_name, 4);
                    $replace[$rskey] = $rsvalue;
                }
            }
            return $email_service->sendmail_template($from, $to, $tpl_id, $replace);
        } else
            return FALSE;
    }

}

/* End of file send_tpl_mail_service.php */
/* Location: ./system/application/libraries/service/Send_tpl_mail_service.php */