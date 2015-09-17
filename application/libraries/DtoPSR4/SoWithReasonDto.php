<?php
class SoWithReasonDto
{
    private $so_no;
    private $biz_type;
    private $platform_id;
    private $reason_id;
    private $reason_display_name;
    private $order_reason_note;
    private $status;
    private $refund_status;
    private $hold_status;
    private $hold_reason;
    private $refund_status_progress;

    public function setSoNo($so_no)
    {
        $this->so_no = $so_no;
    }

    public function getSoNo()
    {
        return $this->so_no;
    }

    public function setBizType($biz_type)
    {
        $this->biz_type = $biz_type;
    }

    public function getBizType()
    {
        return $this->biz_type;
    }

    public function setPlatformId($platform_id)
    {
        $this->platform_id = $platform_id;
    }

    public function getPlatformId()
    {
        return $this->platform_id;
    }

    public function setReasonId($reason_id)
    {
        $this->reason_id = $reason_id;
    }

    public function getReasonId()
    {
        return $this->reason_id;
    }

    public function setReasonDisplayName($reason_display_name)
    {
        $this->reason_display_name = $reason_display_name;
    }

    public function getReasonDisplayName()
    {
        return $this->reason_display_name;
    }

    public function setOrderReasonNote($order_reason_note)
    {
        $this->order_reason_note = $order_reason_note;
    }

    public function getOrderReasonNote()
    {
        return $this->order_reason_note;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
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

    public function setHoldReason($hold_reason)
    {
        $this->hold_reason = $hold_reason;
    }

    public function getHoldReason()
    {
        return $this->hold_reason;
    }

    public function setRefundStatusProgress($refund_status_progress)
    {
        $this->refund_status_progress = $refund_status_progress;
    }

    public function getRefundStatusProgress()
    {
        return $this->refund_status_progress;
    }

}
