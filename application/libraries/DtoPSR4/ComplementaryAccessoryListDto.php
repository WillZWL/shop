<?php
class ComplementaryAccessoryListDto
{
    private $id;
    private $mainprod_sku;
    private $accessory_sku;
    private $dest_country_id;
    private $ca_status;
    private $name;
    private $image_file;
    private $cost;
    private $currency_id;
    private $rrp;
    private $ext;
    private $website_status;
    private $colour;
    private $category;
    private $sub_cat;
    private $sub_sub_cat;
    private $brand;
    private $proc_status;
    private $status;
    private $website_quantity;
    private $quantity;
    private $master_sku;
    private $warranty_in_month;

    private $create_on;
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;

    public function getId()
    {
        return $this->id;
    }

    public function setId($value)
    {
        $this->id = $value;
        return $this;
    }

    public function getMainprodSku()
    {
        return $this->mainprod_sku;
    }

    public function setMainprodSku($value)
    {
        $this->mainprod_sku = $value;
        return $this;
    }

    public function getAccessorySku()
    {
        return $this->accessory_sku;
    }

    public function setAccessorySku($value)
    {
        $this->accessory_sku = $value;
        return $this;
    }

    public function getDestCountryId()
    {
        return $this->dest_country_id;
    }

    public function setDestCountryId($value)
    {
        $this->dest_country_id = $value;
        return $this;
    }

    public function getCaStatus()
    {
        return $this->ca_status;
    }

    public function setCaStatus($value)
    {
        $this->ca_status = $value;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($value)
    {
        $this->name = $value;
    }

    public function getImageFile()
    {
        return $this->image_file;
    }

    public function setImageFile($value)
    {
        $this->image_file = $value;
    }

    public function getCost()
    {
        return $this->cost;
    }

    public function setCost($value)
    {
        $this->cost = $value;
    }

    public function getCurrencyId()
    {
        return $this->currency_id;
    }

    public function setCurrencyId($value)
    {
        $this->currency_id = $value;
    }

    public function getExt()
    {
        return $this->ext;
    }

    public function setExt($value)
    {
        $this->ext = $value;
    }

    public function getRrp()
    {
        return $this->rrp;
    }

    public function setRrp($value)
    {
        $this->rrp = $value;
    }

    public function getWebsiteStatus()
    {
        return $this->website_status;
    }

    public function setWebsiteStatus($value)
    {
        $this->website_status = $value;
    }

    public function getColour()
    {
        return $this->colour;
    }

    public function setColour($value)
    {
        $this->colour = $value;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function setCategory($value)
    {
        $this->category = $value;
    }

    public function getSubCat()
    {
        return $this->sub_cat;
    }

    public function setSubCat($value)
    {
        $this->sub_cat = $value;
    }

    public function getSubSubCat()
    {
        return $this->sub_sub_cat;
    }

    public function setSubSubCat($value)
    {
        $this->sub_sub_cat = $value;
    }

    public function getBrand()
    {
        return $this->brand;
    }

    public function setBrand($value)
    {
        $this->brand = $value;
    }

    public function getProcStatus()
    {
        return $this->proc_status;
    }

    public function setProcStatus($value)
    {
        $this->proc_status = $value;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($value)
    {
        $this->status = $value;
    }

    public function getWebsiteQuantity()
    {
        return $this->website_quantity;
    }

    public function setWebsiteQuantity($value)
    {
        $this->website_quantity = $value;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setQuantity($value)
    {
        $this->quantity = $value;
    }

    public function getMasterSku()
    {
        return $this->master_sku;
    }

    public function setMasterSku($value)
    {
        $this->master_sku = $value;
    }

    public function getWarrantyInMonth()
    {
        return $this->warranty_in_month;
    }

    public function setWarrantyInMonth($value)
    {
        $this->warranty_in_month = $value;
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
}
