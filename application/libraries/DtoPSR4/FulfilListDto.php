<?php
class FulfilListDto
{
    private $id;
    private $so_no;
    private $line_no;
    private $item_sku;
    private $prod_name;
    private $qty;
    private $outstanding_qty;
    private $unit_price;
    private $vat_total;
    private $discount = '0.00';
    private $bundle_core_id = '0';
    private $bundle_level = '0';
    private $amount;
    private $cost;
    private $item_unit_cost = '0.00';
    private $profit = '0.00';
    private $profit_raw = '0.00';
    private $margin = '0.00';
    private $margin_raw = '0.00';
    private $website_status = 'L';
    private $warranty_in_month = '0';
    private $supplier_status = 'A';
    private $status = '0';
    private $create_on = '0000-00-00 00:00:00';
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;
    private $items;

    public function getId()
    {
        return $this->id;
    }

    public function setId($value)
    {
        $this->id = $value;
        return $this;
    }

    public function getSoNo()
    {
        return $this->so_no;
    }

    public function setSoNo($value)
    {
        $this->so_no = $value;
        return $this;
    }

    public function getLineNo()
    {
        return $this->line_no;
    }

    public function setLineNo($value)
    {
        $this->line_no = $value;
        return $this;
    }

    public function getItemSku()
    {
        return $this->item_sku;
    }

    public function setItemSku($value)
    {
        $this->item_sku = $value;
        return $this;
    }

    public function getProdName()
    {
        return $this->prod_name;
    }

    public function setProdName($value)
    {
        $this->prod_name = $value;
        return $this;
    }

    public function getQty()
    {
        return $this->qty;
    }

    public function setQty($value)
    {
        $this->qty = $value;
        return $this;
    }

    public function getOutstandingQty()
    {
        return $this->outstanding_qty;
    }

    public function setOutstandingQty($value)
    {
        $this->outstanding_qty = $value;
        return $this;
    }

    public function getUnitPrice()
    {
        return $this->unit_price;
    }

    public function setUnitPrice($value)
    {
        $this->unit_price = $value;
        return $this;
    }

    public function getVatTotal()
    {
        return $this->vat_total;
    }

    public function setVatTotal($value)
    {
        $this->vat_total = $value;
        return $this;
    }

    public function getDiscount()
    {
        return $this->discount;
    }

    public function setDiscount($value)
    {
        $this->discount = $value;
        return $this;
    }

    public function getBundleCoreId()
    {
        return $this->bundle_core_id;
    }

    public function setBundleCoreId($value)
    {
        $this->bundle_core_id = $value;
        return $this;
    }

    public function getBundleLevel()
    {
        return $this->bundle_level;
    }

    public function setBundleLevel($value)
    {
        $this->bundle_level = $value;
        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($value)
    {
        $this->amount = $value;
        return $this;
    }

    public function getCost()
    {
        return $this->cost;
    }

    public function setCost($value)
    {
        $this->cost = $value;
        return $this;
    }

    public function getItemUnitCost()
    {
        return $this->item_unit_cost;
    }

    public function setItemUnitCost($value)
    {
        $this->item_unit_cost = $value;
        return $this;
    }

    public function getProfit()
    {
        return $this->profit;
    }

    public function setProfit($value)
    {
        $this->profit = $value;
        return $this;
    }

    public function geProfitRaw()
    {
        return $this->profit_raw;
    }

    public function seProfitRaw($value)
    {
        $this->profit_raw = $value;
        return $this;
    }

    public function getMargin()
    {
        return $this->margin;
    }

    public function setMargin($value)
    {
        $this->margin = $value;
        return $this;
    }

    public function getMarginRaw()
    {
        return $this->margin_raw;
    }

    public function setMarginRaw($value)
    {
        $this->margin_raw = $value;
        return $this;
    }

    public function getWebsiteStatus()
    {
        return $this->website_status;
    }

    public function setWebsiteStatus($value)
    {
        $this->website_status = $value;
        return $this;
    }

    public function getWarrantyInMonth()
    {
        return $this->warranty_in_month;
    }

    public function setWarrantyInMonth($value)
    {
        $this->warranty_in_month = $value;
        return $this;
    }

    public function getSupplierStatus()
    {
        return $this->supplier_status;
    }

    public function setSupplierStatus($value)
    {
        $this->supplier_status = $value;
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($value)
    {
        $this->status = $value;
        return $this;
    }

    public function getCreateOn()
    {
        return $this->create_on;
    }

    public function setCreateOn($value)
    {
        $this->create_on = $value;
        return $this;
    }

    public function getCreateAt()
    {
        return $this->create_at;
    }

    public function setCreateAt($value)
    {
        $this->create_at = $value;
        return $this;
    }

    public function getCreateBy()
    {
        return $this->create_by;
    }

    public function setCreateBy($value)
    {
        $this->create_by = $value;
        return $this;
    }

    public function getModifyOn()
    {
        return $this->modify_on;
    }

    public function setModifyOn($value)
    {
        $this->modify_on = $value;
        return $this;
    }

    public function getModifyAt()
    {
        return $this->modify_at;
    }

    public function setModifyAt($value)
    {
        $this->modify_at = $value;
        return $this;
    }

    public function getModifyBy()
    {
        return $this->modify_by;
    }

    public function setModifyBy($value)
    {
        $this->modify_by = $value;
        return $this;
    }

    public function getItems()
    {
        return $this->items;
    }

    public function setItems($value)
    {
        $this->items = $value;
    }

}

