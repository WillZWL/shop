<?php
class ShoppingComProductFeedDto
{
    private $sku;
    private $name;
    private $product_url;
    private $image_url;
    private $price;
    private $cat_name;
    private $stock_status;
    private $shipping_rate;
    private $mpn;
    private $ean;
    private $prod_condition;
    private $brand_name;
    private $detail_desc;
    private $stock_desc;
    private $merc_type;
    private $is_bundle;

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

    public function setProductUrl($product_url)
    {
        $this->product_url = $product_url;
    }

    public function getProductUrl()
    {
        return $this->product_url;
    }

    public function setImageUrl($image_url)
    {
        $this->image_url = $image_url;
    }

    public function getImageUrl()
    {
        return $this->image_url;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setCatName($cat_name)
    {
        $this->cat_name = $cat_name;
    }

    public function getCatName()
    {
        return $this->cat_name;
    }

    public function setStockStatus($stock_status)
    {
        $this->stock_status = $stock_status;
    }

    public function getStockStatus()
    {
        return $this->stock_status;
    }

    public function setShippingRate($shipping_rate)
    {
        $this->shipping_rate = $shipping_rate;
    }

    public function getShippingRate()
    {
        return $this->shipping_rate;
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

    public function setDetailDesc($detail_desc)
    {
        $this->detail_desc = $detail_desc;
    }

    public function getDetailDesc()
    {
        return $this->detail_desc;
    }

    public function setStockDesc($stock_desc)
    {
        $this->stock_desc = $stock_desc;
    }

    public function getStockDesc()
    {
        return $this->stock_desc;
    }

    public function setMercType($merc_type)
    {
        $this->merc_type = $merc_type;
    }

    public function getMercType()
    {
        return $this->merc_type;
    }

    public function setIsBundle($is_bundle)
    {
        $this->is_bundle = $is_bundle;
    }

    public function getIsBundle()
    {
        return $this->is_bundle;
    }

}
