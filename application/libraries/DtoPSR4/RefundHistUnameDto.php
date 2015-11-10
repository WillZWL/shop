<?php
class RefundHistUnameDto
{
    //class variable
    private $id;
    private $refund_id;
    private $status = 'CS';
    private $reason = '0';
    private $notes;
    private $name;
    private $reason_cat;
    private $app_status;
    private $create_on = '0000-00-00 00:00:00';
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;
    private $description;

    //instance method
    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($value)
    {
        $this->description = $value;
    }

    public function getReasonCat()
    {
        return $this->reason_cat;
    }

    public function setReasonCat($value)
    {
        $this->reason_cat = $value;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($value)
    {
        $this->id = $value;
    }

    public function getName()
    {
        return $this->username;
    }

    public function setName($value)
    {
        $this->username = $value;
    }

    public function getRefundId()
    {
        return $this->refund_id;
    }

    public function setRefundId($value)
    {
        $this->refund_id = $value;
    }

    public function getAppStatus()
    {
        return $this->app_status;
    }

    public function setAppStatus($value)
    {
        $this->app_status = $value;
    }


    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($value)
    {
        $this->status = $value;
    }

    public function getReason()
    {
        return $this->reason;
    }

    public function setReason($value)
    {
        $this->reason = $value;
    }

    public function getNotes()
    {
        return $this->notes;
    }

    public function setNotes($value)
    {
        $this->notes = $value;
    }

    public function getCreateOn()
    {
        return $this->create_on;
    }

    public function setCreateOn($value)
    {
        $this->create_on = $value;
    }

    public function getCreateAt()
    {
        return $this->create_at;
    }

    public function setCreateAt($value)
    {
        $this->create_at = $value;
    }

    public function getCreateBy()
    {
        return $this->create_by;
    }

    public function setCreateBy($value)
    {
        $this->create_by = $value;
    }

    public function getModifyOn()
    {
        return $this->modify_on;
    }

    public function setModifyOn($value)
    {
        $this->modify_on = $value;
    }

    public function getModifyAt()
    {
        return $this->modify_at;
    }

    public function setModifyAt($value)
    {
        $this->modify_at = $value;
    }

    public function getModifyBy()
    {
        return $this->modify_by;
    }

    public function setModifyBy($value)
    {
        $this->modify_by = $value;
    }
}
