<?php
class SoItemDetailVo extends \BaseVo
{
    private $id;
    private $so_no;
    private $line_no;
    private $item_sku;
    private $prod_name = '';
    private $ext_item_cd = '';
    private $qty;
    private $product_type = '0';
    private $outstanding_qty;
    private $unit_price;
    private $vat_total;
    private $gst_total = '0.00';
    private $discount_total = '0.00';
    private $bundle_core_id = '0';
    private $bundle_level = '';
    private $amount;
    private $promo_disc_amt = '0.00';
    private $cost;
    private $item_unit_cost = '0.00';
    private $profit = '0.00';
    private $profit_raw = '0.00';
    private $margin = '0.00';
    private $margin_raw = '0.00';
    private $website_status = '';
    private $warranty_in_month = '0';
    private $supplier_status = '';
    private $status = '0';
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

    public function setProdName($prod_name)
    {
        if ($prod_name !== null) {
            $this->prod_name = $prod_name;
        }
    }

    public function getProdName()
    {
        return $this->prod_name;
    }

    public function setExtItemCd($ext_item_cd)
    {
        if ($ext_item_cd !== null) {
            $this->ext_item_cd = $ext_item_cd;
        }
    }

    public function getExtItemCd()
    {
        return $this->ext_item_cd;
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

    public function setProductType($product_type)
    {
        if ($product_type !== null) {
            $this->product_type = $product_type;
        }
    }

    public function getProductType()
    {
        return $this->product_type;
    }

    public function setOutstandingQty($outstanding_qty)
    {
        if ($outstanding_qty !== null) {
            $this->outstanding_qty = $outstanding_qty;
        }
    }

    public function getOutstandingQty()
    {
        return $this->outstanding_qty;
    }

    public function setUnitPrice($unit_price)
    {
        if ($unit_price !== null) {
            $this->unit_price = $unit_price;
        }
    }

    public function getUnitPrice()
    {
        return $this->unit_price;
    }

    public function setVatTotal($vat_total)
    {
        if ($vat_total !== null) {
            $this->vat_total = $vat_total;
        }
    }

    public function getVatTotal()
    {
        return $this->vat_total;
    }

    public function setGstTotal($gst_total)
    {
        if ($gst_total !== null) {
            $this->gst_total = $gst_total;
        }
    }

    public function getGstTotal()
    {
        return $this->gst_total;
    }

    public function setDiscountTotal($discount_total)
    {
        if ($discount_total !== null) {
            $this->discount_total = $discount_total;
        }
    }

    public function getDiscountTotal()
    {
        return $this->discount_total;
    }

    public function setBundleCoreId($bundle_core_id)
    {
        if ($bundle_core_id !== null) {
            $this->bundle_core_id = $bundle_core_id;
        }
    }

    public function getBundleCoreId()
    {
        return $this->bundle_core_id;
    }

    public function setBundleLevel($bundle_level)
    {
        if ($bundle_level !== null) {
            $this->bundle_level = $bundle_level;
        }
    }

    public function getBundleLevel()
    {
        return $this->bundle_level;
    }

    public function setAmount($amount)
    {
        if ($amount !== null) {
            $this->amount = $amount;
        }
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setPromoDiscAmt($promo_disc_amt)
    {
        if ($promo_disc_amt !== null) {
            $this->promo_disc_amt = $promo_disc_amt;
        }
    }

    public function getPromoDiscAmt()
    {
        return $this->promo_disc_amt;
    }

    public function setCost($cost)
    {
        if ($cost !== null) {
            $this->cost = $cost;
        }
    }

    public function getCost()
    {
        return $this->cost;
    }

    public function setItemUnitCost($item_unit_cost)
    {
        if ($item_unit_cost !== null) {
            $this->item_unit_cost = $item_unit_cost;
        }
    }

    public function getItemUnitCost()
    {
        return $this->item_unit_cost;
    }

    public function setProfit($profit)
    {
        if ($profit !== null) {
            $this->profit = $profit;
        }
    }

    public function getProfit()
    {
        return $this->profit;
    }

    public function setProfitRaw($profit_raw)
    {
        if ($profit_raw !== null) {
            $this->profit_raw = $profit_raw;
        }
    }

    public function getProfitRaw()
    {
        return $this->profit_raw;
    }

    public function setMargin($margin)
    {
        if ($margin !== null) {
            $this->margin = $margin;
        }
    }

    public function getMargin()
    {
        return $this->margin;
    }

    public function setMarginRaw($margin_raw)
    {
        if ($margin_raw !== null) {
            $this->margin_raw = $margin_raw;
        }
    }

    public function getMarginRaw()
    {
        return $this->margin_raw;
    }

    public function setWebsiteStatus($website_status)
    {
        if ($website_status !== null) {
            $this->website_status = $website_status;
        }
    }

    public function getWebsiteStatus()
    {
        return $this->website_status;
    }

    public function setWarrantyInMonth($warranty_in_month)
    {
        if ($warranty_in_month !== null) {
            $this->warranty_in_month = $warranty_in_month;
        }
    }

    public function getWarrantyInMonth()
    {
        return $this->warranty_in_month;
    }

    public function setSupplierStatus($supplier_status)
    {
        if ($supplier_status !== null) {
            $this->supplier_status = $supplier_status;
        }
    }

    public function getSupplierStatus()
    {
        return $this->supplier_status;
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

    public function setCreateOn($create_on)
    {
        if ($create_on !== null) {
            $this->create_on = $create_on;
        }
    }

    public function getCreateOn()
    {
        return $this->create_on;
    }

    public function setCreateAt($create_at)
    {
        if ($create_at !== null) {
            $this->create_at = $create_at;
        }
    }

    public function getCreateAt()
    {
        return $this->create_at;
    }

    public function setCreateBy($create_by)
    {
        if ($create_by !== null) {
            $this->create_by = $create_by;
        }
    }

    public function getCreateBy()
    {
        return $this->create_by;
    }

    public function setModifyOn($modify_on)
    {
        if ($modify_on !== null) {
            $this->modify_on = $modify_on;
        }
    }

    public function getModifyOn()
    {
        return $this->modify_on;
    }

    public function setModifyAt($modify_at)
    {
        if ($modify_at !== null) {
            $this->modify_at = $modify_at;
        }
    }

    public function getModifyAt()
    {
        return $this->modify_at;
    }

    public function setModifyBy($modify_by)
    {
        if ($modify_by !== null) {
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
