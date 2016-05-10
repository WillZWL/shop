<?php
class RefundHistoryVo extends \BaseVo
{
    private $id;
    private $refund_id;
    private $status = 'CS';
    private $app_status;
    private $notes;


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

    public function setRefundId($refund_id)
    {
        if ($refund_id !== null) {
            $this->refund_id = $refund_id;
        }
    }

    public function getRefundId()
    {
        return $this->refund_id;
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

    public function setAppStatus($app_status)
    {
        if ($app_status !== null) {
            $this->app_status = $app_status;
        }
    }

    public function getAppStatus()
    {
        return $this->app_status;
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

}
