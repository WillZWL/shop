<?php
class PriceExtendVo extends \BaseVo
{
    private $id;
    private $sku;
    private $platform_id;
    private $title;
    private $note;
    private $ext_desc;
    private $ext_ref_1;
    private $ext_ref_2;
    private $ext_ref_3;
    private $ext_ref_4;
    private $ext_qty = '0';
    private $ext_item_id;
    private $ext_condition;
    private $ext_status;
    private $last_update_result = '';
    private $handling_time;
    private $action;
    private $remark;
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
        if ($id !== null) {
            $this->id = $id;
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function setSku($sku)
    {
        if ($sku !== null) {
            $this->sku = $sku;
        }
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function setPlatformId($platform_id)
    {
        if ($platform_id !== null) {
            $this->platform_id = $platform_id;
        }
    }

    public function getPlatformId()
    {
        return $this->platform_id;
    }

    public function setTitle($title)
    {
        if ($title !== null) {
            $this->title = $title;
        }
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setNote($note)
    {
        if ($note !== null) {
            $this->note = $note;
        }
    }

    public function getNote()
    {
        return $this->note;
    }

    public function setExtDesc($ext_desc)
    {
        if ($ext_desc !== null) {
            $this->ext_desc = $ext_desc;
        }
    }

    public function getExtDesc()
    {
        return $this->ext_desc;
    }

    public function setExtRef1($ext_ref_1)
    {
        if ($ext_ref_1 !== null) {
            $this->ext_ref_1 = $ext_ref_1;
        }
    }

    public function getExtRef1()
    {
        return $this->ext_ref_1;
    }

    public function setExtRef2($ext_ref_2)
    {
        if ($ext_ref_2 !== null) {
            $this->ext_ref_2 = $ext_ref_2;
        }
    }

    public function getExtRef2()
    {
        return $this->ext_ref_2;
    }

    public function setExtRef3($ext_ref_3)
    {
        if ($ext_ref_3 !== null) {
            $this->ext_ref_3 = $ext_ref_3;
        }
    }

    public function getExtRef3()
    {
        return $this->ext_ref_3;
    }

    public function setExtRef4($ext_ref_4)
    {
        if ($ext_ref_4 !== null) {
            $this->ext_ref_4 = $ext_ref_4;
        }
    }

    public function getExtRef4()
    {
        return $this->ext_ref_4;
    }

    public function setExtQty($ext_qty)
    {
        if ($ext_qty !== null) {
            $this->ext_qty = $ext_qty;
        }
    }

    public function getExtQty()
    {
        return $this->ext_qty;
    }

    public function setExtItemId($ext_item_id)
    {
        if ($ext_item_id !== null) {
            $this->ext_item_id = $ext_item_id;
        }
    }

    public function getExtItemId()
    {
        return $this->ext_item_id;
    }

    public function setExtCondition($ext_condition)
    {
        if ($ext_condition !== null) {
            $this->ext_condition = $ext_condition;
        }
    }

    public function getExtCondition()
    {
        return $this->ext_condition;
    }

    public function setExtStatus($ext_status)
    {
        if ($ext_status !== null) {
            $this->ext_status = $ext_status;
        }
    }

    public function getExtStatus()
    {
        return $this->ext_status;
    }

    public function setLastUpdateResult($last_update_result)
    {
        if ($last_update_result !== null) {
            $this->last_update_result = $last_update_result;
        }
    }

    public function getLastUpdateResult()
    {
        return $this->last_update_result;
    }

    public function setHandlingTime($handling_time)
    {
        if ($handling_time !== null) {
            $this->handling_time = $handling_time;
        }
    }

    public function getHandlingTime()
    {
        return $this->handling_time;
    }

    public function setAction($action)
    {
        if ($action !== null) {
            $this->action = $action;
        }
    }

    public function getAction()
    {
        return $this->action;
    }

    public function setRemark($remark)
    {
        if ($remark !== null) {
            $this->remark = $remark;
        }
    }

    public function getRemark()
    {
        return $this->remark;
    }

    public function setCreateOn($create_on)
    {
        if ($create_on !== null) {
            $this->create_on = $create_on;
        }
    }

    public function getCreateOn()
    {
        return $this->create_on;
    }

    public function setCreateAt($create_at)
    {
        if ($create_at !== null) {
            $this->create_at = $create_at;
        }
    }

    public function getCreateAt()
    {
        return $this->create_at;
    }

    public function setCreateBy($create_by)
    {
        if ($create_by !== null) {
            $this->create_by = $create_by;
        }
    }

    public function getCreateBy()
    {
        return $this->create_by;
    }

    public function setModifyOn($modify_on)
    {
        if ($modify_on !== null) {
            $this->modify_on = $modify_on;
        }
    }

    public function getModifyOn()
    {
        return $this->modify_on;
    }

    public function setModifyAt($modify_at)
    {
        if ($modify_at !== null) {
            $this->modify_at = $modify_at;
        }
    }

    public function getModifyAt()
    {
        return $this->modify_at;
    }

    public function setModifyBy($modify_by)
    {
        if ($modify_by !== null) {
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
