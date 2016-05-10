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

}
