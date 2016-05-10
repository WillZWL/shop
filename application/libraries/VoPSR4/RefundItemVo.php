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

    public function setLineNo($line_no)
    {
        if ($line_no !== null) {
            $this->line_no = $line_no;
        }
    }

    public function getLineNo()
    {
        return $this->line_no;
    }

    public function setItemSku($item_sku)
    {
        if ($item_sku !== null) {
            $this->item_sku = $item_sku;
        }
    }

    public function getItemSku()
    {
        return $this->item_sku;
    }

    public function setQty($qty)
    {
        if ($qty !== null) {
            $this->qty = $qty;
        }
    }

    public function getQty()
    {
        return $this->qty;
    }

    public function setRefundAmount($refund_amount)
    {
        if ($refund_amount !== null) {
            $this->refund_amount = $refund_amount;
        }
    }

    public function getRefundAmount()
    {
        return $this->refund_amount;
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

    public function setRefundType($refund_type)
    {
        if ($refund_type !== null) {
            $this->refund_type = $refund_type;
        }
    }

    public function getRefundType()
    {
        return $this->refund_type;
    }

    public function setItemStatus($item_status)
    {
        if ($item_status !== null) {
            $this->item_status = $item_status;
        }
    }

    public function getItemStatus()
    {
        return $this->item_status;
    }

    public function setStockbackDate($stockback_date)
    {
        if ($stockback_date !== null) {
            $this->stockback_date = $stockback_date;
        }
    }

    public function getStockbackDate()
    {
        return $this->stockback_date;
    }

    public function setStockbackWarehouse($stockback_warehouse)
    {
        if ($stockback_warehouse !== null) {
            $this->stockback_warehouse = $stockback_warehouse;
        }
    }

    public function getStockbackWarehouse()
    {
        return $this->stockback_warehouse;
    }

}
