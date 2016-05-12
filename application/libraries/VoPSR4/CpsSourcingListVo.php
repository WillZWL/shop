<?php

class CpsSourcingListVo extends \BaseVo
{
    private $list_date;
    private $item_sku;
    private $order_info;
    private $required_info;
    private $required_qty;
    private $avg_cost;
    private $status = '0';

    protected $primary_key = ['list_date', 'item_sku'];
    protected $increment_field = '';

    public function setListDate($list_date)
    {
        if ($list_date !== null) {
            $this->list_date = $list_date;
        }
    }

    public function getListDate()
    {
        return $this->list_date;
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

    public function setOrderInfo($order_info)
    {
        if ($order_info !== null) {
            $this->order_info = $order_info;
        }
    }

    public function getOrderInfo()
    {
        return $this->order_info;
    }

    public function setRequiredInfo($required_info)
    {
        if ($required_info !== null) {
            $this->required_info = $required_info;
        }
    }

    public function getRequiredInfo()
    {
        return $this->required_info;
    }

    public function setRequiredQty($required_qty)
    {
        if ($required_qty !== null) {
            $this->required_qty = $required_qty;
        }
    }

    public function getRequiredQty()
    {
        return $this->required_qty;
    }

    public function setAvgCost($avg_cost)
    {
        if ($avg_cost !== null) {
            $this->avg_cost = $avg_cost;
        }
    }

    public function getAvgCost()
    {
        return $this->avg_cost;
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
