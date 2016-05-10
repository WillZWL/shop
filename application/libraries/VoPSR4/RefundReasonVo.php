<?php
class RefundReasonVo extends \BaseVo
{
    private $id;
    private $reason_cat;
    private $description;
    private $status = '1';


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

    public function setReasonCat($reason_cat)
    {
        if ($reason_cat !== null) {
            $this->reason_cat = $reason_cat;
        }
    }

    public function getReasonCat()
    {
        return $this->reason_cat;
    }

    public function setDescription($description)
    {
        if ($description !== null) {
            $this->description = $description;
        }
    }

    public function getDescription()
    {
        return $this->description;
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
