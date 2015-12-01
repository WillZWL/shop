<?php
class RefundItemVo extends \BaseVo
{
    private $id;
    private $refund_id;
    private $line_no;
    private $item_sku = '';
    private $qty;
    private $refund_amount = '0.00';
    private $status = 'CS';
    private $refund_type = 'C';
    private $item_status = '';
    private $stockback_date = '0000-00-00';
    private $stockback_warehouse = '';
    private $create_on = '0000-00-00 00:00:00';
    private $create_at = '2130706433';
    private $create_by = 'system';
    private $modify_on = '';
    private $modify_at = '2130706433';
    private $modify_by = 'system';

    private $primary_key = ['id'];
    private $increment_field = 'id';

    public function setId($id)
    {
        if ($id != null) {
            $this->id = $id;
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function setRefundId($refund_id)
    {
        if ($refund_id != null) {
            $this->refund_id = $refund_id;
        }
    }

    public function getRefundId()
    {
        return $this->refund_id;
    }

    public function setLineNo($line_no)
    {
        if ($line_no != null) {
            $this->line_no = $line_no;
        }
    }

    public function getLineNo()
    {
        return $this->line_no;
    }

    public function setItemSku($item_sku)
    {
        if ($item_sku != null) {
            $this->item_sku = $item_sku;
        }
    }

    public function getItemSku()
    {
        return $this->item_sku;
    }

    public function setQty($qty)
    {
        if ($qty != null) {
            $this->qty = $qty;
        }
    }

    public function getQty()
    {
        return $this->qty;
    }

    public function setRefundAmount($refund_amount)
    {
        if ($refund_amount != null) {
            $this->refund_amount = $refund_amount;
        }
    }

    public function getRefundAmount()
    {
        return $this->refund_amount;
    }

    public function setStatus($status)
    {
        if ($status != null) {
            $this->status = $status;
        }
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setRefundType($refund_type)
    {
        if ($refund_type != null) {
            $this->refund_type = $refund_type;
        }
    }

    public function getRefundType()
    {
        return $this->refund_type;
    }

    public function setItemStatus($item_status)
    {
        if ($item_status != null) {
            $this->item_status = $item_status;
        }
    }

    public function getItemStatus()
    {
        return $this->item_status;
    }

    public function setStockbackDate($stockback_date)
    {
        if ($stockback_date != null) {
            $this->stockback_date = $stockback_date;
        }
    }

    public function getStockbackDate()
    {
        return $this->stockback_date;
    }

    public function setStockbackWarehouse($stockback_warehouse)
    {
        if ($stockback_warehouse != null) {
            $this->stockback_warehouse = $stockback_warehouse;
        }
    }

    public function getStockbackWarehouse()
    {
        return $this->stockback_warehouse;
    }

    public function setCreateOn($create_on)
    {
        if ($create_on != null) {
            $this->create_on = $create_on;
        }
    }

    public function getCreateOn()
    {
        return $this->create_on;
    }

    public function setCreateAt($create_at)
    {
        if ($create_at != null) {
            $this->create_at = $create_at;
        }
    }

    public function getCreateAt()
    {
        return $this->create_at;
    }

    public function setCreateBy($create_by)
    {
        if ($create_by != null) {
            $this->create_by = $create_by;
        }
    }

    public function getCreateBy()
    {
        return $this->create_by;
    }

    public function setModifyOn($modify_on)
    {
        if ($modify_on != null) {
            $this->modify_on = $modify_on;
        }
    }

    public function getModifyOn()
    {
        return $this->modify_on;
    }

    public function setModifyAt($modify_at)
    {
        if ($modify_at != null) {
            $this->modify_at = $modify_at;
        }
    }

    public function getModifyAt()
    {
        return $this->modify_at;
    }

    public function setModifyBy($modify_by)
    {
        if ($modify_by != null) {
            $this->modify_by = $modify_by;
        }
    }

    public function getModifyBy()
    {
        return $this->modify_by;
    }

    public function getPrimaryKey()
    {
        return $this->primary_key;
    }

    public function getIncrementField()
    {
        return $this->increment_field;
    }
}
