<?php
class RaGroupVo extends \BaseVo
{
    private $group_id;
    private $group_name;
    private $status = '1';
    private $warranty = '0';
    private $ignore_qty_bundle = '0';
    private $create_on = '0000-00-00 00:00:00';
    private $create_at = '2130706433';
    private $create_by = 'system';
    private $modify_on = '';
    private $modify_at = '2130706433';
    private $modify_by = 'system';

    private $primary_key = ['group_id'];
    private $increment_field = 'group_id';

    public function setGroupId($group_id)
    {
        if ($group_id != null) {
            $this->group_id = $group_id;
        }
    }

    public function getGroupId()
    {
        return $this->group_id;
    }

    public function setGroupName($group_name)
    {
        if ($group_name != null) {
            $this->group_name = $group_name;
        }
    }

    public function getGroupName()
    {
        return $this->group_name;
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

    public function setWarranty($warranty)
    {
        if ($warranty != null) {
            $this->warranty = $warranty;
        }
    }

    public function getWarranty()
    {
        return $this->warranty;
    }

    public function setIgnoreQtyBundle($ignore_qty_bundle)
    {
        if ($ignore_qty_bundle != null) {
            $this->ignore_qty_bundle = $ignore_qty_bundle;
        }
    }

    public function getIgnoreQtyBundle()
    {
        return $this->ignore_qty_bundle;
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
