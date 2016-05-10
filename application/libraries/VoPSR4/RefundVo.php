<?php
class RefundVo extends \BaseVo
{
    private $id;
    private $so_no;
    private $reason = '0';
    private $status = 'CS';
    private $total_refund_amount = '0.00';


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

    public function setReason($reason)
    {
        if ($reason !== null) {
            $this->reason = $reason;
        }
    }

    public function getReason()
    {
        return $this->reason;
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

    public function setTotalRefundAmount($total_refund_amount)
    {
        if ($total_refund_amount !== null) {
            $this->total_refund_amount = $total_refund_amount;
        }
    }

    public function getTotalRefundAmount()
    {
        return $this->total_refund_amount;
    }

}
