<?php
class SoHoldStatusHistoryVo extends \BaseVo
{
    private $id;
    private $so_no;
    private $hold_status;

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

}
