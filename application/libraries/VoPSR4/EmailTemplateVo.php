<?php
class EmailTemplateVo extends \BaseVo
{
    private $id;
    private $tpl_id;
    private $tpl_name;
    private $platform_id;
    private $description = '';
    private $subject = '';
    private $bcc = '';
    private $cc = '';
    private $reply_to = '';
    private $sender = '';
    private $tpl_file_name = '';
    private $tpl_alt_file_name = '';
    private $status = '1';
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

    public function setTplId($tpl_id)
    {
        $this->tpl_id = $tpl_id;
    }

    public function getTplId()
    {
        return $this->tpl_id;
    }

    public function setTplName($tpl_name)
    {
        $this->tpl_name = $tpl_name;
    }

    public function getTplName()
    {
        return $this->tpl_name;
    }

    public function setPlatformId($platform_id)
    {
        $this->platform_id = $platform_id;
    }

    public function getPlatformId()
    {
        return $this->platform_id;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setBcc($bcc)
    {
        $this->bcc = $bcc;
    }

    public function getBcc()
    {
        return $this->bcc;
    }

    public function setCc($cc)
    {
        $this->cc = $cc;
    }

    public function getCc()
    {
        return $this->cc;
    }

    public function setReplyTo($reply_to)
    {
        $this->reply_to = $reply_to;
    }

    public function getReplyTo()
    {
        return $this->reply_to;
    }

    public function setSender($sender)
    {
        $this->sender = $sender;
    }

    public function getSender()
    {
        return $this->sender;
    }

    public function setTplFileName($tpl_file_name)
    {
        $this->tpl_file_name = $tpl_file_name;
    }

    public function getTplFileName()
    {
        return $this->tpl_file_name;
    }

    public function setTplAltFileName($tpl_alt_file_name)
    {
        $this->tpl_alt_file_name = $tpl_alt_file_name;
    }

    public function getTplAltFileName()
    {
        return $this->tpl_alt_file_name;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
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
