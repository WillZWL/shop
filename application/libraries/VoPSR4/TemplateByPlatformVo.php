<?php
class TemplateByPlatformVo extends \BaseVo
{
    private $id;
    private $template_by_platform_id;
    private $platform_id = 'WEBGB';
    private $name = '';
    private $description = '';
    private $status = '1';
    private $tpl_file = '';
    private $tpl_alt_file = '';
    private $subject = '';
    private $message_html;
    private $message_alt;
    private $create_on = '0000-00-00 00:00:00';
    private $create_at = '2130706433';
    private $create_by = 'system';
    private $modify_on = '';
    private $modify_at = '2130706433';
    private $modify_by = 'system';

    private $primary_key = ['id'];
    private $increment_field = 'id';

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setTemplateByPlatformId($template_by_platform_id)
    {
        $this->template_by_platform_id = $template_by_platform_id;
    }

    public function getTemplateByPlatformId()
    {
        return $this->template_by_platform_id;
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

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
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

    public function setCreateOn($create_on)
    {
        $this->create_on = $create_on;
    }

    public function getCreateOn()
    {
        return $this->create_on;
    }

    public function setCreateAt($create_at)
    {
        $this->create_at = $create_at;
    }

    public function getCreateAt()
    {
        return $this->create_at;
    }

    public function setCreateBy($create_by)
    {
        $this->create_by = $create_by;
    }

    public function getCreateBy()
    {
        return $this->create_by;
    }

    public function setModifyOn($modify_on)
    {
        $this->modify_on = $modify_on;
    }

    public function getModifyOn()
    {
        return $this->modify_on;
    }

    public function setModifyAt($modify_at)
    {
        $this->modify_at = $modify_at;
    }

    public function getModifyAt()
    {
        return $this->modify_at;
    }

    public function setModifyBy($modify_by)
    {
        $this->modify_by = $modify_by;
    }

    public function getModifyBy()
    {
        return $this->modify_by;
    }

    public function getPrimaryKey()
    {
        return $this->primary_key;
    }

    public function getIncrementField()
    {
        return $this->increment_field;
    }
}
