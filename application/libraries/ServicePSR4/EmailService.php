<?php
namespace ESG\Panther\Service;

use PHPMailer;

class EmailService extends BaseService
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

    public function init()
    {
    }

    public function run(\EventEmailDto $obj)
    {
        $email = $this->getEmail($obj);
        $result = $this->sendEmail($email);
        if (! $result) {
            // TODO
            // should report to IT
        }
    }

    private function getEmail($obj)
    {
        $result = ['from', 'from_name', 'subject', 'bcc', 'cc', 'reply_to', 'body', 'to'];

        $where = [
            'tpl_id' => $obj->getTplId(),
            'platform_id' => $obj->getPlatformId()
        ];

        $email = $this->templateService->getEmail($where, $obj->getReplace());

        if ($email) {
            $result['from'] = $email->getFrom();
            $result['from_name'] = $email->getFromName();
            $result['subject'] = $email->getSubject();
            $result['bcc'] = $email->getBcc();
            $result['cc'] = $email->getCc();
            $result['reply_to'] = $email->getReplyTo();
            $result['body'] = $email->getMessageHtml();
            $result['alt_body'] = $email->getMessageAlt();
            $result['to'] = $obj->getMailTo();
            $result['att_file'] = $obj->getAttFile();
        }

        return $result;
    }

    public function sendEmail($email)
    {
        $phpmail = new PHPMailer;
        $phpmail->CharSet = "UTF-8";
        $phpmail->IsSMTP();
        if ($smtphost = $this->getDao('Config')->valueOf("smtp_host")) {
            $phpmail->Host = $smtphost;
            $phpmail->SMTPAuth = $this->getDao('Config')->valueOf("smtp_auth");
            $phpmail->Username = $this->getDao('Config')->valueOf("smtp_user");
            $phpmail->Password = $this->getDao('Config')->valueOf("smtp_pass");
        }

        $phpmail->From = $email['from'];
        $phpmail->FromName = $email['from_name'];
        if (is_array($email['to'])) {
            foreach ($email['to'] as $to_address) {
                $phpmail->AddAddress($to_address);
            }
        } else {
            $phpmail->AddAddress($email['to']);
        }

        if (is_array($email['cc'])) {
            foreach ($email['cc'] AS $cc_address) {
                $phpmail->AddCC($cc_address);
            }
        } else {
            $phpmail->AddCC($email['cc']);
        }

        if (is_array($email['bcc'])) {
            foreach ($email['bcc'] AS $bcc_address) {
                $phpmail->AddBCC($bcc_address);
            }
        } else {
            $phpmail->AddBCC($email['bcc']);
        }

        if ($email['body']) {
            $phpmail->IsHTML(true);
            $phpmail->Body = $email['body'];
        }

        if ($email['alt_body']) {
            $phpmail->AltBody = $email['alt_body'];
        }

        if ($email['att_file']) {
            $phpmail->addAttachment($email['att_file']);
        }


        $phpmail->Subject = $email['subject'];

        return $phpmail->Send();
    }
}
