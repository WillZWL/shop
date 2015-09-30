<?php
class LinkshareProductFeedDto
{
    private $prod_id;
    private $prod_name;
    private $sku;
    private $cat_name;
    private $sec_cat_name;
    private $product_url;
    private $image_url;
    private $buy_url;
    private $short_desc;
    private $detail_desc;
    private $discount;
    private $disc_type;
    private $sale_price;
    private $price;
    private $begin_date;
    private $end_date;
    private $brand_name;
    private $shipping_fee;
    private $delete_flag;
    private $keyword;
    private $all_flag;
    private $mpn;
    private $manufacturer;
    private $shipping_info;
    private $stock_status;
    private $upc;
    private $class_id;
    private $prod_link_flag;
    private $storefront_flag;
    private $merc_flag;
    private $currency;
    private $m_1;

    public function setProdId($prod_id)
    {
        $this->prod_id = $prod_id;
    }

    public function getProdId()
    {
        return $this->prod_id;
    }

    public function setProdName($prod_name)
    {
        $this->prod_name = $prod_name;
    }

    public function getProdName()
    {
        return $this->prod_name;
    }

    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function setCatName($cat_name)
    {
        $this->cat_name = $cat_name;
    }

    public function getCatName()
    {
        return $this->cat_name;
    }

    public function setSecCatName($sec_cat_name)
    {
        $this->sec_cat_name = $sec_cat_name;
    }

    public function getSecCatName()
    {
        return $this->sec_cat_name;
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

    public function setBuyUrl($buy_url)
    {
        $this->buy_url = $buy_url;
    }

    public function getBuyUrl()
    {
        return $this->buy_url;
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

    public function setDiscount($discount)
    {
        $this->discount = $discount;
    }

    public function getDiscount()
    {
        return $this->discount;
    }

    public function setDiscType($disc_type)
    {
        $this->disc_type = $disc_type;
    }

    public function getDiscType()
    {
        return $this->disc_type;
    }

    public function setSalePrice($sale_price)
    {
        $this->sale_price = $sale_price;
    }

    public function getSalePrice()
    {
        return $this->sale_price;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setBeginDate($begin_date)
    {
        $this->begin_date = $begin_date;
    }

    public function getBeginDate()
    {
        return $this->begin_date;
    }

    public function setEndDate($end_date)
    {
        $this->end_date = $end_date;
    }

    public function getEndDate()
    {
        return $this->end_date;
    }

    public function setBrandName($brand_name)
    {
        $this->brand_name = $brand_name;
    }

    public function getBrandName()
    {
        return $this->brand_name;
    }

    public function setShippingFee($shipping_fee)
    {
        $this->shipping_fee = $shipping_fee;
    }

    public function getShippingFee()
    {
        return $this->shipping_fee;
    }

    public function setDeleteFlag($delete_flag)
    {
        $this->delete_flag = $delete_flag;
    }

    public function getDeleteFlag()
    {
        return $this->delete_flag;
    }

    public function setKeyword($keyword)
    {
        $this->keyword = $keyword;
    }

    public function getKeyword()
    {
        return $this->keyword;
    }

    public function setAllFlag($all_flag)
    {
        $this->all_flag = $all_flag;
    }

    public function getAllFlag()
    {
        return $this->all_flag;
    }

    public function setMpn($mpn)
    {
        $this->mpn = $mpn;
    }

    public function getMpn()
    {
        return $this->mpn;
    }

    public function setManufacturer($manufacturer)
    {
        $this->manufacturer = $manufacturer;
    }

    public function getManufacturer()
    {
        return $this->manufacturer;
    }

    public function setShippingInfo($shipping_info)
    {
        $this->shipping_info = $shipping_info;
    }

    public function getShippingInfo()
    {
        return $this->shipping_info;
    }

    public function setStockStatus($stock_status)
    {
        $this->stock_status = $stock_status;
    }

    public function getStockStatus()
    {
        return $this->stock_status;
    }

    public function setUpc($upc)
    {
        $this->upc = $upc;
    }

    public function getUpc()
    {
        return $this->upc;
    }

    public function setClassId($class_id)
    {
        $this->class_id = $class_id;
    }

    public function getClassId()
    {
        return $this->class_id;
    }

    public function setProdLinkFlag($prod_link_flag)
    {
        $this->prod_link_flag = $prod_link_flag;
    }

    public function getProdLinkFlag()
    {
        return $this->prod_link_flag;
    }

    public function setStorefrontFlag($storefront_flag)
    {
        $this->storefront_flag = $storefront_flag;
    }

    public function getStorefrontFlag()
    {
        return $this->storefront_flag;
    }

    public function setMercFlag($merc_flag)
    {
        $this->merc_flag = $merc_flag;
    }

    public function getMercFlag()
    {
        return $this->merc_flag;
    }

    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function setM1($m_1)
    {
        $this->m_1 = $m_1;
    }

    public function getM1()
    {
        return $this->m_1;
    }

}
