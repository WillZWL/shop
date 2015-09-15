<?php
class RefundItemProdDto
{
    //class variable
    private $refund_id;
    private $line_no;
    private $item_sku;
    private $qty;
    private $refund_amount;
    private $item_status;
    private $status;
    private $refund_type;
    private $create_on;
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;
    private $username;
    private $name;

    //instance method
    public function getRefundId()
    {
        return $this->refund_id;
    }

    public function setRefundId($value)
    {
        $this->refund_id = $value;
    }

    public function getLineNo()
    {
        return $this->line_no;
    }

    public function setLineNo($value)
    {
        $this->line_no = $value;
    }

    public function getItemSku()
    {
        return $this->item_sku;
    }

    public function setItemSku($value)
    {
        $this->item_sku = $value;
    }

    public function getQty()
    {
        return $this->qty;
    }

    public function setQty($value)
    {
        $this->qty = $value;
    }

    public function getRefundAmount()
    {
        return $this->refund_amount;
    }

    public function setRefundAmount($value)
    {
        $this->refund_amount = $value;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($value)
    {
        $this->status = $value;
    }

    public function getRefundType()
    {
        return $this->refund_type;
    }

    public function setRefundType($value)
    {
        $this->refund_type = $value;
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

    public function getItemStatus()
    {
        return $this->item_status;
    }

    public function setItemStatus($value)
    {
        $this->item_status = $value;
    }

    public function getPrimaryKey()
    {
        return $this->primary_key;
    }

    public function getIncrementField()
    {
        return $this->increment_field;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($value)
    {
        $this->username = $value;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($value)
    {
        $this->name = $value;
    }
}
