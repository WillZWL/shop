<?php
namespace ESG\Panther\Service;

use PHPMailer;
use ESG\Panther\Service\TemplateService;

class EmailService extends BaseService implements ActableService
{

    private $event_dto;

    public function __construct($dto = "")
    {
        parent::__construct();

        if ($dto) {
            $this->event_dto = $dto;
        }
        $this->templateService = new TemplateService;
    }

    public function init() {
    }

    public function getEmailTemplate($dto = "")
    {
        # new function to return html output of emails for testing.
        # currently used in \valuebasket.com\app\libraries\service\event_service.php
        if ($dto) {
            $tpl_id = $dto->getTplId();
            $replace = $dto->getReplace();
            $lang_id = $dto->getLangId();
            $platform_id = $dto->getPlatformId();

            if (!(empty($tpl_id))) {
                $tpl_obj = $this->templateService->getMsgTplWithAtt(array("id" => $tpl_id, "lang_id" => $lang_id), $replace);
                if ($tpl_obj = $this->templateService->getMsgTplWithAtt(array("id" => $tpl_id, "lang_id" => $lang_id, "platform_id" => $platform_id), $replace)) {
                    if ($html_msg = $tpl_obj->template->getMessage()) {
                        return $html_msg;
                    } elseif ($text = $tpl_obj->template->getAltMessage()) {
                        return $html_msg;
                    }
                }
            } else {
                echo "EmailService.php - Line " . __LINE__ . " No tpl_id found.";
            }
        } else
            echo "EmailService.php - Line " . __LINE__ . " No dto found.";
    }

    public function run($dto)
    {
        if ($dto) {
            $event_id = $dto->getEventId();
            switch ($event_id) {
                default:
                    $this->sendmailTemplate($dto->getMailFrom(), $dto->getMailTo(), $dto->getTplId(), $dto->getReplace(), $dto->getLangId(), $dto->getMailCc(), $dto->getMailBcc(), $dto->getPlatformId());
            }
        } else {
            return FALSE;
        }
    }

    public function sendmailTemplate($from = "", $to = "", $tpl_id = "", $replace = "", $lang_id = "en", $cc = "", $bcc = "", $platform_id = "WEBGB")
    {
        if ($bcc) {
            $default_bcc = array('pantherbccemail@gmail.com');
            $bcc = array_merge($bcc, $default_bcc);
        } else {
            $bcc = array('pantherbccemail@gmail.com');
        }
        $CI =& get_instance();
        if (!$CI->config->item('allow_email_sending')) {
            return TRUE;
        }
        if (!(empty($from) || empty($to) || empty($tpl_id))) {
            if ($tpl_obj = $this->templateService->getMsgTplWithAtt(array("id" => $tpl_id,
                "lang_id" => $lang_id, "platform_id" => $platform_id), $replace, $platform_id)
            ) {
                $phpmail = new phpmailer;
                $phpmail->IsSMTP();
                $phpmail->From = $from;

                if ($smtphost = $this->getDao('Config')->valueOf("smtp_host")) {
                    $phpmail->Host = $smtphost;
                    $phpmail->SMTPAuth = $this->getDao('Config')->valueOf("smtp_auth");
                    $phpmail->Username = $this->getDao('Config')->valueOf("smtp_user");
                    $phpmail->Password = $this->getDao('Config')->valueOf("smtp_pass");
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

                if ($html_msg = $tpl_obj->template->getMessage()) {
                    $phpmail->IsHTML(true);
                    $phpmail->Body = $html_msg;
                    if ($text = $tpl_obj->template->getAltMessage()) {
                        $phpmail->AltBody = $text;
                    }
                } elseif ($text = $tpl_obj->template->getAltMessage()) {
                    $phpmail->IsHTML(false);
                    $phpmail->Body = $text;
                }

                $phpmail->Subject = $tpl_obj->template->getSubject();

                return $phpmail->Send();
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }
}


