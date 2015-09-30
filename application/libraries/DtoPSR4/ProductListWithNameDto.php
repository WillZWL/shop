<?php
class ProductListWithNameDto
{
    private $sku;
    private $name;
    private $image_file;
    private $price;
    private $ext;
    private $rrp;
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
    private $create_on;
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;
    private $warranty_in_month;

    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setImageFile($image_file)
    {
        $this->image_file = $image_file;
    }

    public function getImageFile()
    {
        return $this->image_file;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setExt($ext)
    {
        $this->ext = $ext;
    }

    public function getExt()
    {
        return $this->ext;
    }

    public function setRrp($rrp)
    {
        $this->rrp = $rrp;
    }

    public function getRrp()
    {
        return $this->rrp;
    }

    public function setWebsiteStatus($website_status)
    {
        $this->website_status = $website_status;
    }

    public function getWebsiteStatus()
    {
        return $this->website_status;
    }

    public function setColour($colour)
    {
        $this->colour = $colour;
    }

    public function getColour()
    {
        return $this->colour;
    }

    public function setCategory($category)
    {
        $this->category = $category;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function setSubCat($sub_cat)
    {
        $this->sub_cat = $sub_cat;
    }

    public function getSubCat()
    {
        return $this->sub_cat;
    }

    public function setSubSubCat($sub_sub_cat)
    {
        $this->sub_sub_cat = $sub_sub_cat;
    }

    public function getSubSubCat()
    {
        return $this->sub_sub_cat;
    }

    public function setBrand($brand)
    {
        $this->brand = $brand;
    }

    public function getBrand()
    {
        return $this->brand;
    }

    public function setProcStatus($proc_status)
    {
        $this->proc_status = $proc_status;
    }

    public function getProcStatus()
    {
        return $this->proc_status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setWebsiteQuantity($website_quantity)
    {
        $this->website_quantity = $website_quantity;
    }

    public function getWebsiteQuantity()
    {
        return $this->website_quantity;
    }

    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setMasterSku($master_sku)
    {
        $this->master_sku = $master_sku;
    }

    public function getMasterSku()
    {
        return $this->master_sku;
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

    public function setWarrantyInMonth($warranty_in_month)
    {
        $this->warranty_in_month = $warranty_in_month;
    }

    public function getWarrantyInMonth()
    {
        return $this->warranty_in_month;
    }

}
