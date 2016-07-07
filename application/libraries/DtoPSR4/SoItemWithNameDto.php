<?php
class SoItemWithNameDto
{
    private $so_no;
    private $line_no;
    private $item_sku;
    private $qty;
    private $unit_price;
    private $vat_total;
    private $gst_total;
    private $amount;
    private $declared_value;
    private $website_status;
    private $create_on;
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;
    private $name;
    private $cat_name;
    private $main_prod_sku;
    private $main_img_ext;
    private $item_weight;
    private $warranty_in_month;

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

    public function setDeclaredValue($value){
        $this->declared_value = $value;
    }
    
    public function getDeclaredValue(){
        return $this->declared_value;
    }

    public function setWebsiteStatus($website_status)
    {
        $this->website_status = $website_status;
    }

    public function getWebsiteStatus()
    {
        return $this->website_status;
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

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setCatName($cat_name)
    {
        $this->cat_name = $cat_name;
    }

    public function getCatName()
    {
        return $this->cat_name;
    }

    public function setMainProdSku($main_prod_sku)
    {
        $this->main_prod_sku = $main_prod_sku;
    }

    public function getMainProdSku()
    {
        return $this->main_prod_sku;
    }

    public function setMainImgExt($main_img_ext)
    {
        $this->main_img_ext = $main_img_ext;
    }

    public function getMainImgExt()
    {
        return $this->main_img_ext;
    }

    public function setItemWeight($item_weight)
    {
        $this->item_weight = $item_weight;
    }

    public function getItemWeight()
    {
        return $this->item_weight;
    }

    public function setWarrantyInMonth($warranty_in_month)
    {
        $this->warranty_in_month = $warranty_in_month;
    }

    public function getWarrantyInMonth()
    {
        return $this->warranty_in_month;
    }

}
