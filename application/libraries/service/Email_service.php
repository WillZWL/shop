<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

include_once "Base_service.php";
include_once "actable_service.php";

class Email_service extends Base_service implements Actable_service
{

    private $event_dto;

    function Email_service($dto = "")
    {
        parent::__construct();

        if ($dto) {
            $this->event_dto = $dto;
        }
    }

    public function init()
    {
    }

    public function get_email_template($dto = "")
    {
        # new function to return html output of emails for testing.
        # currently used in \valuebasket.com\app\libraries\service\event_service.php
        if ($dto) {
            $tpl_id = $dto->get_tpl_id();
            $replace = $dto->get_replace();
            $lang_id = $dto->get_lang_id();
            $platform_id = $dto->get_platform_id();

            if (!(empty($tpl_id))) {
                include_once(APPPATH . "libraries/service/Template_service.php");
                $tpl_srv = new Template_service();
                $tpl_obj = $tpl_srv->get_msg_tpl_w_att(array("id" => $tpl_id, "lang_id" => $lang_id), $replace);
                if ($tpl_obj = $tpl_srv->get_msg_tpl_w_att(array("id" => $tpl_id, "lang_id" => $lang_id, "platform_id" => $platform_id), $replace)) {
                    if ($html_msg = $tpl_obj->template->get_message()) {
                        return $html_msg;
                    } elseif ($text = $tpl_obj->template->get_alt_message()) {
                        return $html_msg;
                    }
                }
            } else {
                echo "email_service.php - Line " . __LINE__ . " No tpl_id found.";
            }
        } else
            echo "email_service.php - Line " . __LINE__ . " No dto found.";
    }

    public function run($dto)
    {
        if ($dto) {
            $event_id = $dto->get_event_id();
            switch ($event_id) {
                default:
                    $this->sendmail_template($dto->get_mail_from(), $dto->
                    get_mail_to(), $dto->get_tpl_id(), $dto->get_replace(), $dto
                        ->get_lang_id(), $dto->get_mail_cc(), $dto->get_mail_bcc(), $dto->get_platform_id());
            }
        } else {
            return FALSE;
        }
    }

    public function sendmail_template($from = "", $to = "", $tpl_id = "", $replace = "", $lang_id = "en", $cc = "", $bcc = "", $platform_id = "WEBGB")
    {
        if ($bcc) {
            $default_bcc = array('valuebasketbccemail@gmail.com');
            $bcc = array_merge($bcc, $default_bcc);
        } else {
            $bcc = array('valuebasketbccemail@gmail.com');
        }
        $CI =& get_instance();
        if (!$CI->config->item('allow_email_sending')) {
            return TRUE;
        }
        if (!(empty($from) || empty($to) || empty($tpl_id))) {
            include_once(APPPATH . "libraries/service/Template_service.php");
            $tpl_srv = new Template_service();
            if ($tpl_obj = $tpl_srv->get_msg_tpl_w_att(array("id" => $tpl_id,
                "lang_id" => $lang_id, "platform_id" => $platform_id), $replace, $platform_id)
            ) {
                include_once(APPPATH .
                    "libraries/service/Context_config_service.php");
                $cconfig = new Context_config_service();

                include_once(BASEPATH . "plugins/phpmailer/phpmailer_pi.php");
                $phpmail = new phpmailer();
                $phpmail->IsSMTP();
                $phpmail->From = $from;

                if ($smtphost = $cconfig->value_of("smtp_host")) {
                    $phpmail->Host = $smtphost;
                    $phpmail->SMTPAuth = $cconfig->value_of("smtp_auth");
                    $phpmail->Username = $cconfig->value_of("smtp_user");
                    $phpmail->Password = $cconfig->value_of("smtp_pass");
                }

                if (is_array($to)) {
                    foreach ($to as $to_address) {
                        $phpmail->AddAddress($to_address);
                    }
                } else {
                    $phpmail->AddAddress($to);
                }

                if ($cc) {
                    if (is_array($cc)) {
                        foreach ($cc AS $cc_address) {
                            $phpmail->AddCC($cc_address);
                        }
                    } else {
                        $phpmail->AddCC($cc);
                    }
                }

                if ($bcc) {
                    if (is_array($bcc)) {
                        foreach ($bcc AS $bcc_address) {
                            $phpmail->AddBCC($bcc_address);
                        }
                    } else {
                        $phpmail->AddBCC($bcc);
                    }
                }

                if ($html_msg = $tpl_obj->template->get_message()) {
                    $phpmail->IsHTML(true);
                    $phpmail->Body = $html_msg;
                    if ($text = $tpl_obj->template->get_alt_message()) {
                        $phpmail->AltBody = $text;
                    }
                } elseif ($text = $tpl_obj->template->get_alt_message()) {
                    $phpmail->IsHTML(false);
                    $phpmail->Body = $text;
                }

                $phpmail->Subject = $tpl_obj->template->get_subject();

                foreach ($tpl_obj->attachment as $att) {
                    $attpath = "";

                    if (is_file($att->get_att_file())) {
                        $attpath = $att->get_att_file();
                    } elseif (is_file($cconfig->value_of("tpl_path") . $tpl_obj->
                        template->get_id() . "/" . $att->get_att_file())) {
                        $attpath = $cconfig->value_of("tpl_path") . $tpl_obj->
                            template->get_id() . "/" . $att->get_att_file();
                    }
                    if ($attpath) {
                        $phpmail->AddAttachment($attpath);
                    }
                }
                return $phpmail->Send();
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }
}


