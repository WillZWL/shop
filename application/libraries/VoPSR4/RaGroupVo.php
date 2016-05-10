<?php
class RaGroupVo extends \BaseVo
{
    private $group_id;
    private $group_name;
    private $status = '1';
    private $warranty = '0';
    private $ignore_qty_bundle = '0';

    protected $primary_key = ['group_id'];
    protected $increment_field = 'group_id';

    public function setGroupId($group_id)
    {
        if ($group_id !== null) {
            $this->group_id = $group_id;
        }
    }

    public function getGroupId()
    {
        return $this->group_id;
    }

    public function setGroupName($group_name)
    {
        if ($group_name !== null) {
            $this->group_name = $group_name;
        }
    }

    public function getGroupName()
    {
        return $this->group_name;
    }

    public function setStatus($status)
    {
        if ($status !== null) {
            $this->status = $status;
        }
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setWarranty($warranty)
    {
        if ($warranty !== null) {
            $this->warranty = $warranty;
        }
    }

    public function getWarranty()
    {
        return $this->warranty;
    }

    public function setIgnoreQtyBundle($ignore_qty_bundle)
    {
        if ($ignore_qty_bundle !== null) {
            $this->ignore_qty_bundle = $ignore_qty_bundle;
        }
    }

    public function getIgnoreQtyBundle()
    {
        return $this->ignore_qty_bundle;
    }

}
