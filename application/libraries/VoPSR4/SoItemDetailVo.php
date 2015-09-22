<?php
class SoItemDetailVo extends \BaseVo
{
    private $id;
    private $so_no;
    private $line_no;
    private $item_sku;
    private $qty;
    private $outstanding_qty;
    private $unit_price;
    private $vat_total;
    private $gst_total = '0.00';
    private $discount_total = '0.00';
    private $discount = '0.00';
    private $bundle_core_id = '0';
    private $bundle_level = "";
    private $amount;
    private $promo_disc_amt = '0.00';
    private $cost;
    private $item_unit_cost = '0.00';
    private $profit = '0.00';
    private $profit_raw = '0.00';
    private $margin = '0.00';
    private $margin_raw = '0.00';
    private $status;
    private $create_on = '0000-00-00 00:00:00';
    private $create_at = '2130706433';
    private $create_by = 'system';
    private $modify_on = 'CURRENT_TIMESTAMP';
    private $modify_at = '2130706433';
    private $modify_by = 'system';

    private $primary_key = ['id'];
    private $increment_field = 'id';

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setSoNo($so_no)
    {
        $this->so_no = $so_no;
    }

    public function getSoNo()
    {
        return $this->so_no;
    }

    public function setLineNo($line_no)
    {
        $this->line_no = $line_no;
    }

    public function getLineNo()
    {
        return $this->line_no;
    }

    public function setItemSku($item_sku)
    {
        $this->item_sku = $item_sku;
    }

    public function getItemSku()
    {
        return $this->item_sku;
    }

    public function setQty($qty)
    {
        $this->qty = $qty;
    }

    public function getQty()
    {
        return $this->qty;
    }

    public function setOutstandingQty($outstanding_qty)
    {
        $this->outstanding_qty = $outstanding_qty;
    }

    public function getOutstandingQty()
    {
        return $this->outstanding_qty;
    }

    public function setUnitPrice($unit_price)
    {
        $this->unit_price = $unit_price;
    }

    public function getUnitPrice()
    {
        return $this->unit_price;
    }

    public function setVatTotal($vat_total)
    {
        $this->vat_total = $vat_total;
    }

    public function getVatTotal()
    {
        return $this->vat_total;
    }

    public function setGstTotal($gst_total)
    {
        $this->gst_total = $gst_total;
    }

    public function getGstTotal()
    {
        return $this->gst_total;
    }

    public function setDiscountTotal($discount_total)
    {
        $this->discount_total = $discount_total;
    }

    public function getDiscountTotal()
    {
        return $this->discount_total;
    }

    public function setDiscount($discount)
    {
        $this->discount = $discount;
    }

    public function getDiscount()
    {
        return $this->discount;
    }

    public function setBundleCoreId($bundle_core_id)
    {
        $this->bundle_core_id = $bundle_core_id;
    }

    public function getBundleCoreId()
    {
        return $this->bundle_core_id;
    }

    public function setBundleLevel($bundle_level)
    {
        $this->bundle_level = $bundle_level;
    }

    public function getBundleLevel()
    {
        return $this->bundle_level;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setPromoDiscAmt($promo_disc_amt)
    {
        $this->promo_disc_amt = $promo_disc_amt;
    }

    public function getPromoDiscAmt()
    {
        return $this->promo_disc_amt;
    }

    public function setCost($cost)
    {
        $this->cost = $cost;
    }

    public function getCost()
    {
        return $this->cost;
    }

    public function setItemUnitCost($item_unit_cost)
    {
        $this->item_unit_cost = $item_unit_cost;
    }

    public function getItemUnitCost()
    {
        return $this->item_unit_cost;
    }

    public function setProfit($profit)
    {
        $this->profit = $profit;
    }

    public function getProfit()
    {
        return $this->profit;
    }

    public function setProfitRaw($profit_raw)
    {
        $this->profit_raw = $profit_raw;
    }

    public function getProfitRaw()
    {
        return $this->profit_raw;
    }

    public function setMargin($margin)
    {
        $this->margin = $margin;
    }

    public function getMargin()
    {
        return $this->margin;
    }

    public function setMarginRaw($margin_raw)
    {
        $this->margin_raw = $margin_raw;
    }

    public function getMarginRaw()
    {
        return $this->margin_raw;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setCreateOn($create_on)
    {
        $this->create_on = $create_on;
    }

    public function getCreateOn()
    {
        return $this->create_on;
    }

    public function setCreateAt($create_at)
    {
        $this->create_at = $create_at;
    }

    public function getCreateAt()
    {
        return $this->create_at;
    }

    public function setCreateBy($create_by)
    {
        $this->create_by = $create_by;
    }

    public function getCreateBy()
    {
        return $this->create_by;
    }

    public function setModifyOn($modify_on)
    {
        $this->modify_on = $modify_on;
    }

    public function getModifyOn()
    {
        return $this->modify_on;
    }

    public function setModifyAt($modify_at)
    {
        $this->modify_at = $modify_at;
    }

    public function getModifyAt()
    {
        return $this->modify_at;
    }

    public function setModifyBy($modify_by)
    {
        $this->modify_by = $modify_by;
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
