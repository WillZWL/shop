<?php
class LogmessageVo extends \BaseVo
{
    private $id;
    private $type;
    private $file;
    private $linenumber;
    private $message;
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

    public function setFile($file)
    {
        if ($file != null) {
            $this->file = $file;
        }
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setLinenumber($linenumber)
    {
        if ($linenumber != null) {
            $this->linenumber = $linenumber;
        }
    }

    public function getLinenumber()
    {
        return $this->linenumber;
    }

    public function setMessage($message)
    {
        if ($message != null) {
            $this->message = $message;
        }
    }

    public function getMessage()
    {
        return $this->message;
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
