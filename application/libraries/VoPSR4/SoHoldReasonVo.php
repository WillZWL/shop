<?php
class SoHoldReasonVo extends \BaseVo
{
    private $id;
    private $so_no;
    private $reason;

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

}
