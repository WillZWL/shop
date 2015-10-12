<?php
class EventEmailDto
{
    private $event_id;
    private $mail_from;
    private $mail_to;
    private $mail_cc;
    private $mail_bcc;
    private $tpl_id;
    private $lang_id = 'en';
    private $platform_id;
    private $replace;

    private $template_name;


    public function getTemplateName()
    {
        return $this->template_name;
    }

    public function setTemplateName($template_name)
    {
        $this->template_name = $template_name;
    }


    public function getEventId()
    {
        return $this->event_id;
    }

    public function setEventId($value)
    {
        $this->event_id = $value;
    }

    public function getMailFrom()
    {
        return $this->mail_from;
    }

    public function setMailFrom($value)
    {
        $this->mail_from = $value;
    }

    public function getMailTo()
    {
        return $this->mail_to;
    }

    public function setMailTo($value)
    {
        $this->mail_to = $value;
    }

    public function getMailCc()
    {
        return $this->mail_cc;
    }

    public function setMailCc($value)
    {
        $this->mail_cc = $value;
    }

    public function getMailBcc()
    {
        return $this->mail_bcc;
    }

    public function setMailBcc($value)
    {
        $this->mail_bcc = $value;
    }

    public function getTplId()
    {
        return $this->tpl_id;
    }

    public function setTplId($value)
    {
        $this->tpl_id = $value;
    }

    public function getLangId()
    {
        return $this->lang_id;
    }

    public function setLangId($value)
    {
        $this->lang_id = $value;
        return $this;
    }

    public function getPlatformId()
    {
        return $this->platform_id;
    }

    public function setPlatformId($value)
    {
        $this->platform_id = $value;
        return $this;
    }

    public function getReplace()
    {
        return $this->replace;
    }

    public function setReplace($value)
    {
        $this->replace = $value;
        $this->getEmailContentArr($this->lang_id);
        $this->getPdfContentArr($this->lang_id);
    }

    private function getEmailContentArr($lang_id)
    {
        $data_arr = null;

        $language_path = $lang_id . "/" . $this->tpl_id . ".ini";
        if (file_exists(APPPATH . "language/template_service/" . $language_path)) {
            $data_arr = parse_ini_file(APPPATH . "language/template_service/" . $language_path);
            if ((isset($data_arr["email_from"])) && ($data_arr["email_from"] != "")) {
                $this->setMailFrom($data_arr["email_from"]);
            }
        }
        if (!is_null($data_arr)) {
            $this->replace = array_merge($this->replace, $data_arr);
        }

        return $data_arr;
    }

    private function getPdfContentArr($lang_id)
    {
        $data_arr = null;

        $language_path = $lang_id . "/" . $this->tpl_id . "_pdf.ini";
        if (file_exists(APPPATH . "language/template_service/" . $language_path)) {
            $data_arr = parse_ini_file(APPPATH . "language/template_service/" . $language_path);
        }
        if (!is_null($data_arr)) {
            $this->replace = array_merge($this->replace, $data_arr);
        }

        return $data_arr;
    }

    public function replaceIniOnly($lang_id)
    {
        $this->getEmailContentArr($lang_id);
    }
}


