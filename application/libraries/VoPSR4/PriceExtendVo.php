<?php
class PriceExtendVo extends \BaseVo
{
    private $id;
    private $sku;
    private $platform_id;
    private $title = '';
    private $note = '';
    private $ext_desc;
    private $ext_ref_1 = '0';
    private $ext_ref_2 = '0';
    private $ext_ref_3 = '';
    private $ext_ref_4 = '';
    private $ext_qty = '0';
    private $ext_item_id = '';
    private $ext_condition = '';
    private $ext_status = '';
    private $fulfillment_centre_id = '';
    private $amazon_reprice_name = '';
    private $handling_time = '0';
    private $action = '';
    private $remark = '';
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

    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function setPlatformId($platform_id)
    {
        $this->platform_id = $platform_id;
    }

    public function getPlatformId()
    {
        return $this->platform_id;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setNote($note)
    {
        $this->note = $note;
    }

    public function getNote()
    {
        return $this->note;
    }

    public function setExtDesc($ext_desc)
    {
        $this->ext_desc = $ext_desc;
    }

    public function getExtDesc()
    {
        return $this->ext_desc;
    }

    public function setExtRef1($ext_ref_1)
    {
        $this->ext_ref_1 = $ext_ref_1;
    }

    public function getExtRef1()
    {
        return $this->ext_ref_1;
    }

    public function setExtRef2($ext_ref_2)
    {
        $this->ext_ref_2 = $ext_ref_2;
    }

    public function getExtRef2()
    {
        return $this->ext_ref_2;
    }

    public function setExtRef3($ext_ref_3)
    {
        $this->ext_ref_3 = $ext_ref_3;
    }

    public function getExtRef3()
    {
        return $this->ext_ref_3;
    }

    public function setExtRef4($ext_ref_4)
    {
        $this->ext_ref_4 = $ext_ref_4;
    }

    public function getExtRef4()
    {
        return $this->ext_ref_4;
    }

    public function setExtQty($ext_qty)
    {
        $this->ext_qty = $ext_qty;
    }

    public function getExtQty()
    {
        return $this->ext_qty;
    }

    public function setExtItemId($ext_item_id)
    {
        $this->ext_item_id = $ext_item_id;
    }

    public function getExtItemId()
    {
        return $this->ext_item_id;
    }

    public function setExtCondition($ext_condition)
    {
        $this->ext_condition = $ext_condition;
    }

    public function getExtCondition()
    {
        return $this->ext_condition;
    }

    public function setExtStatus($ext_status)
    {
        $this->ext_status = $ext_status;
    }

    public function getExtStatus()
    {
        return $this->ext_status;
    }

    public function setFulfillmentCentreId($fulfillment_centre_id)
    {
        $this->fulfillment_centre_id = $fulfillment_centre_id;
    }

    public function getFulfillmentCentreId()
    {
        return $this->fulfillment_centre_id;
    }

    public function setAmazonRepriceName($amazon_reprice_name)
    {
        $this->amazon_reprice_name = $amazon_reprice_name;
    }

    public function getAmazonRepriceName()
    {
        return $this->amazon_reprice_name;
    }

    public function setHandlingTime($handling_time)
    {
        $this->handling_time = $handling_time;
    }

    public function getHandlingTime()
    {
        return $this->handling_time;
    }

    public function setAction($action)
    {
        $this->action = $action;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function setRemark($remark)
    {
        $this->remark = $remark;
    }

    public function getRemark()
    {
        return $this->remark;
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
