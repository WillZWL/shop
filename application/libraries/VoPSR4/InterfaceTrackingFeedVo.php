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

    public function setTrackingId($tracking_id)
    {
        if ($tracking_id !== null) {
            $this->tracking_id = $tracking_id;
        }
    }

    public function getTrackingId()
    {
        return $this->tracking_id;
    }

    public function setSoNo($so_no)
    {
        if ($so_no !== null) {
            $this->so_no = $so_no;
        }
    }

    public function getSoNo()
    {
        return $this->so_no;
    }

    public function setRetailerName($retailer_name)
    {
        if ($retailer_name !== null) {
            $this->retailer_name = $retailer_name;
        }
    }

    public function getRetailerName()
    {
        return $this->retailer_name;
    }

    public function setShNo($sh_no)
    {
        if ($sh_no !== null) {
            $this->sh_no = $sh_no;
        }
    }

    public function getShNo()
    {
        return $this->sh_no;
    }

    public function setTrackingNo($tracking_no)
    {
        if ($tracking_no !== null) {
            $this->tracking_no = $tracking_no;
        }
    }

    public function getTrackingNo()
    {
        return $this->tracking_no;
    }

    public function setHistoryTrackingNo($history_tracking_no)
    {
        if ($history_tracking_no !== null) {
            $this->history_tracking_no = $history_tracking_no;
        }
    }

    public function getHistoryTrackingNo()
    {
        return $this->history_tracking_no;
    }

    public function setWeightInKg($weight_in_kg)
    {
        if ($weight_in_kg !== null) {
            $this->weight_in_kg = $weight_in_kg;
        }
    }

    public function getWeightInKg()
    {
        return $this->weight_in_kg;
    }

    public function setCourierName($courier_name)
    {
        if ($courier_name !== null) {
            $this->courier_name = $courier_name;
        }
    }

    public function getCourierName()
    {
        return $this->courier_name;
    }

    public function setCourierId($courier_id)
    {
        if ($courier_id !== null) {
            $this->courier_id = $courier_id;
        }
    }

    public function getCourierId()
    {
        return $this->courier_id;
    }

    public function setCourierIdNum($courier_id_num)
    {
        if ($courier_id_num !== null) {
            $this->courier_id_num = $courier_id_num;
        }
    }

    public function getCourierIdNum()
    {
        return $this->courier_id_num;
    }

    public function setItems($items)
    {
        if ($items !== null) {
            $this->items = $items;
        }
    }

    public function getItems()
    {
        return $this->items;
    }

    public function setNotes($notes)
    {
        if ($notes !== null) {
            $this->notes = $notes;
        }
    }

    public function getNotes()
    {
        return $this->notes;
    }

    public function setVbCourierId($vb_courier_id)
    {
        if ($vb_courier_id !== null) {
            $this->vb_courier_id = $vb_courier_id;
        }
    }

    public function getVbCourierId()
    {
        return $this->vb_courier_id;
    }

    public function setRefundStatus($refund_status)
    {
        if ($refund_status !== null) {
            $this->refund_status = $refund_status;
        }
    }

    public function getRefundStatus()
    {
        return $this->refund_status;
    }

    public function setHoldStatus($hold_status)
    {
        if ($hold_status !== null) {
            $this->hold_status = $hold_status;
        }
    }

    public function getHoldStatus()
    {
        return $this->hold_status;
    }

    public function setSendEmail($send_email)
    {
        if ($send_email !== null) {
            $this->send_email = $send_email;
        }
    }

    public function getSendEmail()
    {
        return $this->send_email;
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

}
