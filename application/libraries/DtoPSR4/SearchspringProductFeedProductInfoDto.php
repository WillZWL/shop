<?php
class SearchspringProductFeedProductInfoDto
{
    private $sku;
    private $prod_name;
    private $image;
    private $image_url;
    private $thumb_image_url;
    private $add_cart_url;
    private $cat_name;
    private $sub_cat_name;
    private $mpn;
    private $ean;
    private $upc;
    private $prod_condition;
    private $brand_name;
    private $short_desc;
    private $detail_desc;
    private $inbundle;
    private $clearance;
    private $create_date;

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

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImageUrl($image_url)
    {
        $this->image_url = $image_url;
    }

    public function getImageUrl()
    {
        return $this->image_url;
    }

    public function setThumbImageUrl($thumb_image_url)
    {
        $this->thumb_image_url = $thumb_image_url;
    }

    public function getThumbImageUrl()
    {
        return $this->thumb_image_url;
    }

    public function setAddCartUrl($add_cart_url)
    {
        $this->add_cart_url = $add_cart_url;
    }

    public function getAddCartUrl()
    {
        return $this->add_cart_url;
    }

    public function setCatName($cat_name)
    {
        $this->cat_name = $cat_name;
    }

    public function getCatName()
    {
        return $this->cat_name;
    }

    public function setSubCatName($sub_cat_name)
    {
        $this->sub_cat_name = $sub_cat_name;
    }

    public function getSubCatName()
    {
        return $this->sub_cat_name;
    }

    public function setMpn($mpn)
    {
        $this->mpn = $mpn;
    }

    public function getMpn()
    {
        return $this->mpn;
    }

    public function setEan($ean)
    {
        $this->ean = $ean;
    }

    public function getEan()
    {
        return $this->ean;
    }

    public function setUpc($upc)
    {
        $this->upc = $upc;
    }

    public function getUpc()
    {
        return $this->upc;
    }

    public function setProdCondition($prod_condition)
    {
        $this->prod_condition = $prod_condition;
    }

    public function getProdCondition()
    {
        return $this->prod_condition;
    }

    public function setBrandName($brand_name)
    {
        $this->brand_name = $brand_name;
    }

    public function getBrandName()
    {
        return $this->brand_name;
    }

    public function setShortDesc($short_desc)
    {
        $this->short_desc = $short_desc;
    }

    public function getShortDesc()
    {
        return $this->short_desc;
    }

    public function setDetailDesc($detail_desc)
    {
        $this->detail_desc = $detail_desc;
    }

    public function getDetailDesc()
    {
        return $this->detail_desc;
    }

    public function setInbundle($inbundle)
    {
        $this->inbundle = $inbundle;
    }

    public function getInbundle()
    {
        return $this->inbundle;
    }

    public function setClearance($clearance)
    {
        $this->clearance = $clearance;
    }

    public function getClearance()
    {
        return $this->clearance;
    }

    public function setCreateDate($create_date)
    {
        $this->create_date = $create_date;
    }

    public function getCreateDate()
    {
        return $this->create_date;
    }

}
