<?php
include_once 'Base_dto.php';

class Event_email_dto extends Base_dto
{

    //class variable
    private $event_id;
    private $mail_from;
    private $mail_to;
    private $mail_cc;
    private $mail_bcc;
    private $tpl_id;
    private $lang_id = 'en';
    private $platform_id;
    private $replace;

    //instance method
    public function __construct()
    {
    }

    public function get_event_id()
    {
        return $this->event_id;
    }

    public function set_event_id($value)
    {
        $this->event_id = $value;
    }

    public function get_mail_from()
    {
        return $this->mail_from;
    }

    public function set_mail_from($value)
    {
        $this->mail_from = $value;
    }

    public function get_mail_to()
    {
        return $this->mail_to;
    }

    public function set_mail_to($value)
    {
        $this->mail_to = $value;
    }

    public function get_mail_cc()
    {
        return $this->mail_cc;
    }

    public function set_mail_cc($value)
    {
        $this->mail_cc = $value;
    }

    public function get_mail_bcc()
    {
        return $this->mail_bcc;
    }

    public function set_mail_bcc($value)
    {
        $this->mail_bcc = $value;
    }

    public function get_tpl_id()
    {
        return $this->tpl_id;
    }

    public function set_tpl_id($value)
    {
//      mail("ethan@eservicesgroup.com", "[VB] " . $value, "", 'From: website@valuebasket.com');
        $this->tpl_id = $value;
    }

    public function get_lang_id()
    {
        return $this->lang_id;
    }

    public function set_lang_id($value)
    {
        $this->lang_id = $value;
        return $this;
    }

    public function get_platform_id()
    {
        return $this->platform_id;
    }

    public function set_platform_id($value)
    {
        $this->platform_id = $value;
        return $this;
    }

    public function get_replace()
    {
        return $this->replace;
    }

    public function set_replace($value)
    {
        $this->replace = $value;
        $this->_get_email_content_arr($this->lang_id);
        $this->_get_pdf_content_arr($this->lang_id);
    }

    public function replace_ini_only($lang_id)
    {
        $this->_get_email_content_arr($lang_id);
    }

    private function _get_email_content_arr($lang_id)
    {
        $data_arr = null;

        $language_path = $lang_id . "/" . $this->tpl_id . ".ini";
        if (file_exists(APPPATH . "language/template_service/" . $language_path))
        {
            $data_arr = parse_ini_file(APPPATH . "language/template_service/" . $language_path);
            if ((isset($data_arr["email_from"])) && ($data_arr["email_from"] != ""))
            {
                $this->set_mail_from($data_arr["email_from"]);
            }
        }
        if (!is_null($data_arr))
        {
            $this->replace = array_merge($this->replace, $data_arr);

            # uncomment this line if want to dump out email template
            # with content but WITHOUT client specific info
            // $this->replace = $data_arr;
        }

        return $data_arr;
    }

    private function _get_pdf_content_arr($lang_id)
    {
        $data_arr = null;

        # set your ini file name as [event_id]_pdf.ini
        $language_path = $lang_id . "/" . $this->tpl_id . "_pdf.ini";
        if (file_exists(APPPATH . "language/template_service/" . $language_path))
        {
            $data_arr = parse_ini_file(APPPATH . "language/template_service/" . $language_path);
        }
        if (!is_null($data_arr))
        {
            $this->replace = array_merge($this->replace, $data_arr);
        }

        return $data_arr;
    }
}

/* End of file payment_success_dto.php */
/* Location: ./system/application/libraries/dto/payment_success_dto */