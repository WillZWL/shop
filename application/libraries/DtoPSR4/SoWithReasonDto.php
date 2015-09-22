<?php
class SoWithReasonDto
{
    protected $so_no;
    protected $biz_type;
    protected $platform_id;
    protected $status;
    protected $refund_status;
    protected $hold_status;
    protected $reason_id;
    protected $reason_display_name;
    protected $order_reason_note;
    protected $hold_reason;
    protected $refund_status_progress;

    public function getSoNo()
    {
        return $this->so_no;
    }

    public function setSoNo($value)
    {
        $this->so_no = $value;
        return $this;
    }

    public function getBizType()
    {
        return $this->biz_type;
    }

    public function setBizType($value)
    {
        $this->biz_type = $value;
        return $this;
    }

    public function getPlatformId()
    {
        return $this->platform_id;
    }

    public function setPlatformId($value)
    {
        $this->platform_id = $value;
        return $this;
    }

    public function getReasonId()
    {
        return $this->reason_id;
    }

    public function setReasonId($value)
    {
        $this->reason_id = $value;
        return $this;
    }

    public function getReasonDisplayName()
    {
        return $this->reason_display_name;
    }

    public function setReasonDisplayName($value)
    {
        $this->reason_display_name = $value;
        return $this;
    }

    public function getOrderReasonNote()
    {
        return $this->order_reason_note;
    }

    public function setOrderReasonNote($value)
    {
        $this->order_reason_note = $value;
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($value)
    {
        $this->status = $value;
        return $this;
    }

    public function getRefundStatus()
    {
        return $this->refund_status;
    }

    public function setRefundStatus($value)
    {
        $this->refund_status = $value;
        return $this;
    }

    public function getHoldStatus()
    {
        return $this->hold_status;
    }

    public function setHoldStatus($value)
    {
        $this->hold_status = $value;
        return $this;
    }

    public function getHoldReason()
    {
        return $this->hold_reason;
    }

    public function setHoldReason($value)
    {
        $this->hold_reason = $value;
        return $this;
    }

    public function getRefundStatusProgress()
    {
        return $this->refund_status_progress;
    }

    public function setRefundStatusProgress($value)
    {
        $this->refund_status_progress = $value;
        return $this;
    }
}
