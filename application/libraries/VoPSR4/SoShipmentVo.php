<?php
class SoShipmentVo extends \BaseVo
{
    private $id;
    private $sh_no;
    private $courier_id;
    private $tracking_no = '';
    private $courier_feed_sent = '0';
    private $status = '0';

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

    public function setCourierFeedSent($courier_feed_sent)
    {
        if ($courier_feed_sent !== null) {
            $this->courier_feed_sent = $courier_feed_sent;
        }
    }

    public function getCourierFeedSent()
    {
        return $this->courier_feed_sent;
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
