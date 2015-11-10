<?php
class InterfaceTrackingFeedVo extends \BaseVo
{
    private $id;
    private $tracking_id;
    private $so_no;
    private $retailer_name;
    private $sh_no;
    private $tracking_no;
    private $history_tracking_no;
    private $weight_in_kg = '0.00';
    private $courier_name;
    private $courier_id;
    private $courier_id_num = '0';
    private $items;
    private $notes;
    private $vb_courier_id;
    private $refund_status = '0';
    private $hold_status = '0';
    private $send_email = '0';
    private $status = 'N';
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

    public function setTrackingId($tracking_id)
    {
        $this->tracking_id = $tracking_id;
    }

    public function getTrackingId()
    {
        return $this->tracking_id;
    }

    public function setSoNo($so_no)
    {
        $this->so_no = $so_no;
    }

    public function getSoNo()
    {
        return $this->so_no;
    }

    public function setRetailerName($retailer_name)
    {
        $this->retailer_name = $retailer_name;
    }

    public function getRetailerName()
    {
        return $this->retailer_name;
    }

    public function setShNo($sh_no)
    {
        $this->sh_no = $sh_no;
    }

    public function getShNo()
    {
        return $this->sh_no;
    }

    public function setTrackingNo($tracking_no)
    {
        $this->tracking_no = $tracking_no;
    }

    public function getTrackingNo()
    {
        return $this->tracking_no;
    }

    public function setHistoryTrackingNo($history_tracking_no)
    {
        $this->history_tracking_no = $history_tracking_no;
    }

    public function getHistoryTrackingNo()
    {
        return $this->history_tracking_no;
    }

    public function setWeightInKg($weight_in_kg)
    {
        $this->weight_in_kg = $weight_in_kg;
    }

    public function getWeightInKg()
    {
        return $this->weight_in_kg;
    }

    public function setCourierName($courier_name)
    {
        $this->courier_name = $courier_name;
    }

    public function getCourierName()
    {
        return $this->courier_name;
    }

    public function setCourierId($courier_id)
    {
        $this->courier_id = $courier_id;
    }

    public function getCourierId()
    {
        return $this->courier_id;
    }

    public function setCourierIdNum($courier_id_num)
    {
        $this->courier_id_num = $courier_id_num;
    }

    public function getCourierIdNum()
    {
        return $this->courier_id_num;
    }

    public function setItems($items)
    {
        $this->items = $items;
    }

    public function getItems()
    {
        return $this->items;
    }

    public function setNotes($notes)
    {
        $this->notes = $notes;
    }

    public function getNotes()
    {
        return $this->notes;
    }

    public function setVbCourierId($vb_courier_id)
    {
        $this->vb_courier_id = $vb_courier_id;
    }

    public function getVbCourierId()
    {
        return $this->vb_courier_id;
    }

    public function setRefundStatus($refund_status)
    {
        $this->refund_status = $refund_status;
    }

    public function getRefundStatus()
    {
        return $this->refund_status;
    }

    public function setHoldStatus($hold_status)
    {
        $this->hold_status = $hold_status;
    }

    public function getHoldStatus()
    {
        return $this->hold_status;
    }

    public function setSendEmail($send_email)
    {
        $this->send_email = $send_email;
    }

    public function getSendEmail()
    {
        return $this->send_email;
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
