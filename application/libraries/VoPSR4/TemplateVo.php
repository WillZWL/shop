<?php
class TemplateVo extends \BaseVo
{
    private $id;
    private $type = '1';
    private $tpl_id;
    private $tpl_name;
    private $platform_id;
    private $description = '';
    private $subject = '';
    private $bcc = '';
    private $cc = '';
    private $reply_to = '';
    private $from = '';
    private $tpl_file_name = '';
    private $tpl_alt_file_name = '';
    private $message_html;
    private $message_alt;
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
        if ($id != null) {
            $this->id = $id;
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function setType($type)
    {
        if ($type != null) {
            $this->type = $type;
        }
    }

    public function getType()
    {
        return $this->type;
    }

    public function setTplId($tpl_id)
    {
        if ($tpl_id != null) {
            $this->tpl_id = $tpl_id;
        }
    }

    public function getTplId()
    {
        return $this->tpl_id;
    }

    public function setTplName($tpl_name)
    {
        if ($tpl_name != null) {
            $this->tpl_name = $tpl_name;
        }
    }

    public function getTplName()
    {
        return $this->tpl_name;
    }

    public function setPlatformId($platform_id)
    {
        if ($platform_id != null) {
            $this->platform_id = $platform_id;
        }
    }

    public function getPlatformId()
    {
        return $this->platform_id;
    }

    public function setDescription($description)
    {
        if ($description != null) {
            $this->description = $description;
        }
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setSubject($subject)
    {
        if ($subject != null) {
            $this->subject = $subject;
        }
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setBcc($bcc)
    {
        if ($bcc != null) {
            $this->bcc = $bcc;
        }
    }

    public function getBcc()
    {
        return $this->bcc;
    }

    public function setCc($cc)
    {
        if ($cc != null) {
            $this->cc = $cc;
        }
    }

    public function getCc()
    {
        return $this->cc;
    }

    public function setReplyTo($reply_to)
    {
        if ($reply_to != null) {
            $this->reply_to = $reply_to;
        }
    }

    public function getReplyTo()
    {
        return $this->reply_to;
    }

    public function setFrom($from)
    {
        if ($from != null) {
            $this->from = $from;
        }
    }

    public function getFrom()
    {
        return $this->from;
    }

    public function setTplFileName($tpl_file_name)
    {
        if ($tpl_file_name != null) {
            $this->tpl_file_name = $tpl_file_name;
        }
    }

    public function getTplFileName()
    {
        return $this->tpl_file_name;
    }

    public function setTplAltFileName($tpl_alt_file_name)
    {
        if ($tpl_alt_file_name != null) {
            $this->tpl_alt_file_name = $tpl_alt_file_name;
        }
    }

    public function getTplAltFileName()
    {
        return $this->tpl_alt_file_name;
    }

    public function setMessageHtml($message_html)
    {
        if ($message_html != null) {
            $this->message_html = $message_html;
        }
    }

    public function getMessageHtml()
    {
        return $this->message_html;
    }

    public function setMessageAlt($message_alt)
    {
        if ($message_alt != null) {
            $this->message_alt = $message_alt;
        }
    }

    public function getMessageAlt()
    {
        return $this->message_alt;
    }

    public function setStatus($status)
    {
        if ($status != null) {
            $this->status = $status;
        }
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setCreateOn($create_on)
    {
        if ($create_on != null) {
            $this->create_on = $create_on;
        }
    }

    public function getCreateOn()
    {
        return $this->create_on;
    }

    public function setCreateAt($create_at)
    {
        if ($create_at != null) {
            $this->create_at = $create_at;
        }
    }

    public function getCreateAt()
    {
        return $this->create_at;
    }

    public function setCreateBy($create_by)
    {
        if ($create_by != null) {
            $this->create_by = $create_by;
        }
    }

    public function getCreateBy()
    {
        return $this->create_by;
    }

    public function setModifyOn($modify_on)
    {
        if ($modify_on != null) {
            $this->modify_on = $modify_on;
        }
    }

    public function getModifyOn()
    {
        return $this->modify_on;
    }

    public function setModifyAt($modify_at)
    {
        if ($modify_at != null) {
            $this->modify_at = $modify_at;
        }
    }

    public function getModifyAt()
    {
        return $this->modify_at;
    }

    public function setModifyBy($modify_by)
    {
        if ($modify_by != null) {
            $this->modify_by = $modify_by;
        }
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
