<?php
class CriteoProductFeedDto
{
    private $sku;
    private $prod_name;
    private $prod_url;
    private $image_url_small;
    private $cat_name;
    private $short_desc;
    private $availability;
    private $price;
    private $image_url_large;
    private $rrp;
    private $discount;
    private $sub_cat_name;
    private $image;

    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function setProdName($prod_name)
    {
        $this->prod_name = $prod_name;
    }

    public function getProdName()
    {
        return $this->prod_name;
    }

    public function setProdUrl($prod_url)
    {
        $this->prod_url = $prod_url;
    }

    public function getProdUrl()
    {
        return $this->prod_url;
    }

    public function setImageUrlSmall($image_url_small)
    {
        $this->image_url_small = $image_url_small;
    }

    public function getImageUrlSmall()
    {
        return $this->image_url_small;
    }

    public function setCatName($cat_name)
    {
        $this->cat_name = $cat_name;
    }

    public function getCatName()
    {
        return $this->cat_name;
    }

    public function setShortDesc($short_desc)
    {
        $this->short_desc = $short_desc;
    }

    public function getShortDesc()
    {
        return $this->short_desc;
    }

    public function setAvailability($availability)
    {
        $this->availability = $availability;
    }

    public function getAvailability()
    {
        return $this->availability;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setImageUrlLarge($image_url_large)
    {
        $this->image_url_large = $image_url_large;
    }

    public function getImageUrlLarge()
    {
        return $this->image_url_large;
    }

    public function setRrp($rrp)
    {
        $this->rrp = $rrp;
    }

    public function getRrp()
    {
        return $this->rrp;
    }

    public function setDiscount($discount)
    {
        $this->discount = $discount;
    }

    public function getDiscount()
    {
        return $this->discount;
    }

    public function setSubCatName($sub_cat_name)
    {
        $this->sub_cat_name = $sub_cat_name;
    }

    public function getSubCatName()
    {
        return $this->sub_cat_name;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getImage()
    {
        return $this->image;
    }

}
