<?php
class TplMsgWithAttDto
{
    private $id;
    private $lang_id = "en";
    private $platform_id;
    private $name;
    private $description;
    private $tpl_file;
    private $tpl_alt_file;
    private $subject;
    private $message;
    private $alt_message;
    private $pdf_attachment;
    private $message_html;
    private $message_alt;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setLangId($lang_id)
    {
        $this->lang_id = $lang_id;
    }

    public function getLangId()
    {
        return $this->lang_id;
    }

    public function setPlatformId($platform_id)
    {
        $this->platform_id = $platform_id;
    }

    public function getPlatformId()
    {
        return $this->platform_id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setTplFile($tpl_file)
    {
        $this->tpl_file = $tpl_file;
    }

    public function getTplFile()
    {
        return $this->tpl_file;
    }

    public function setTplAltFile($tpl_alt_file)
    {
        $this->tpl_alt_file = $tpl_alt_file;
    }

    public function getTplAltFile()
    {
        return $this->tpl_alt_file;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setAltMessage($alt_message)
    {
        $this->alt_message = $alt_message;
    }

    public function getAltMessage()
    {
        return $this->alt_message;
    }

    public function setPdfAttachment($pdf_attachment)
    {
        $this->pdf_attachment = $pdf_attachment;
    }

    public function getPdfAttachment()
    {
        return $this->pdf_attachment;
    }

    public function setMessageHtml($message_html)
    {
        $this->message_html = $message_html;
    }

    public function getMessageHtml()
    {
        return $this->message_html;
    }

    public function setMessageAlt($message_alt)
    {
        $this->message_alt = $message_alt;
    }

    public function getMessageAlt()
    {
        return $this->message_alt;
    }

}