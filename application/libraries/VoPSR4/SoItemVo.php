<?php
class SoItemVo extends \BaseVo
{
    private $id;
    private $so_no;
    private $line_no;
    private $prod_sku;
    private $prod_name;
    private $ext_item_cd;
    private $qty;
    private $unit_price;
    private $vat_total;
    private $gst_total = '0.00';
    private $amount;
    private $website_status;
    private $warranty_in_month;
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

    public function setProdSku($prod_sku)
    {
        $this->prod_sku = $prod_sku;
    }

    public function getProdSku()
    {
        return $this->prod_sku;
    }

    public function setProdName($prod_name)
    {
        $this->prod_name = $prod_name;
    }

    public function getProdName()
    {
        return $this->prod_name;
    }

    public function setExtItemCd($ext_item_cd)
    {
        $this->ext_item_cd = $ext_item_cd;
    }

    public function getExtItemCd()
    {
        return $this->ext_item_cd;
    }

    public function setQty($qty)
    {
        $this->qty = $qty;
    }

    public function getQty()
    {
        return $this->qty;
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

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setWebsiteStatus($website_status)
    {
        $this->website_status = $website_status;
    }

    public function getWebsiteStatus()
    {
        return $this->website_status;
    }

    public function setWarrantyInMonth($warranty_in_month)
    {
        $this->warranty_in_month = $warranty_in_month;
    }

    public function getWarrantyInMonth()
    {
        return $this->warranty_in_month;
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
