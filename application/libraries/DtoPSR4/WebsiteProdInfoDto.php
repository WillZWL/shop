<?php
class WebsiteProdInfoDto
{
    private $sku;
    private $name;
    private $cat_id;
    private $sub_cat_id;
    private $sub_sub_cat_id;
    private $brand_name;
    private $colour_id;
    private $website_status;
    private $website_quantity;
    private $quantity;
    private $thumbnail;
    private $price;
    private $fixed_rrp;
    private $rrp_factor;
    private $currency;

    public function getCurrency()
    {
        return $this->currency;
    }

    public function setCurrency($value)
    {
        $this->currency = $value;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($value)
    {
        $this->price = $value;
    }

    public function getFixedRrp()
    {
        return $this->fixed_rrp;
    }

    public function setFixedRrp($value)
    {
        $this->price = $fixed_rrp;
    }

    public function getRrpFactor()
    {
        return $this->rrp_factor;
    }

    public function setRrpFactor($value)
    {
        $this->price = $rrp_factor;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($value)
    {
        $this->image = $value;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setQuantity($value)
    {
        $this->quantity = $value;
    }

    public function getWebsiteQuantity()
    {
        return $this->website_quantity;
    }

    public function setWebsiteQuantity($value)
    {
        $this->website_quantity = $value;
    }

    public function getWebsiteStatus()
    {
        return $this->website_status;
    }

    public function setWebsiteStatus($value)
    {
        $this->website_status = $value;
    }

    public function getColourId()
    {
        return $this->colour_id;
    }

    public function setColourId($value)
    {
        $this->colour_id = $value;
    }

    public function getBrandName()
    {
        return $this->brand_name;
    }

    public function setBrandName($value)
    {
        $this->brand_name = $value;
    }

    public function getSubSubCatId()
    {
        return $this->sub_sub_cat_id;
    }

    public function setSubSubCatId($value)
    {
        $this->sub_sub_cat_id = $value;
    }

    public function getSubCatId()
    {
        return $this->sub_cat_id;
    }

    public function setSubCatId($value)
    {
        $this->sub_cat_id = $value;
    }

    public function getCatId()
    {
        return $this->cat_id;
    }

    public function setCatId($value)
    {
        $this->cat_id = $value;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($value)
    {
        $this->name = $value;
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function setSku($value)
    {
        $this->sku = $value;
    }

    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    public function setThumbnail($value)
    {
        $this->thumbnail = $value;
    }
}
