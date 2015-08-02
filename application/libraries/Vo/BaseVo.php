<?php

namespace AtomV2\Vo;

abstract class BaseVo
{
    abstract public function getPrimaryKey();

    public function getCreateOn()
    {
        return $this->create_on;
    }

    public function setCreateOn($create_on)
    {
        $this->create_on = $create_on;
    }

    public function getCreateAt()
    {
        return $this->create_at;
    }

    public function setCreateAt($create_at)
    {
        $this->create_at = $create_at;
    }

    public function getCreateBy()
    {
        return $this->create_by;
    }

    public function setCreateBy($create_by)
    {
        $this->create_by = $create_by;
    }

    public function getModifyOn()
    {
        return $this->modify_on;
    }

    public function setModifyOn($modify_on)
    {
        $this->modify_on = $modify_on;
    }

    public function getModifyAt()
    {
        return $this->modify_at;
    }

    public function setModifyAt($modify_at)
    {
        $this->modify_at = $modify_at;
    }

    public function getModifyBy()
    {
        return $this->modify_by;
    }

    public function setModifyBy($modify_by)
    {
        $this->modify_by = $modify_by;
    }
}
