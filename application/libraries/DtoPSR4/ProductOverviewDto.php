<?php

class ProductOverviewDto
{
    private $sku;
    private $ext_sku;
    private $platform_id;
    private $name;
    private $clearance;
    private $surplus_quantity;
    private $website_quantity;
    private $website_status;
    private $auto_restock;
    private $listing_status;
    private $price;
    private $vb_price;
    private $is_advertised;
    private $auto_price;
    private $total_cost;
    private $profit;
    private $margin;
    private $currency_id;
    private $supplier_status;
    private $modify_on;
    private $platform_currency_id;
    private $image;
    private $category;
    private $sub_category;
    private $sub_sub_category;
    private $brand_name;

    public function getSku()
    {
        return $this->sku;
    }

    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    public function getExtSku()
    {
        return $this->ext_sku;
    }

    public function setExtSku($ext_sku)
    {
        $this->ext_sku = $ext_sku;
    }

    public function getPlatformId()
    {
        return $this->platform_id;
    }

    public function setPlatformId($platform_id)
    {
        $this->platform_id = $platform_id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getClearance()
    {
        return $this->clearance;
    }

    public function setClearance($clearance)
    {
        $this->clearance = $clearance;
    }

    public function getSurplusQuantity()
    {
        return $this->surplus_quantity;
    }

    public function setSurplusQuantity($surplus_quantity)
    {
        $this->surplus_quantity = $surplus_quantity;
    }

    public function getWebsiteQuantity()
    {
        return $this->website_quantity;
    }

    public function setWebsiteQuantity($website_quantity)
    {
        $this->website_quantity = $website_quantity;
    }

    public function getWebsiteStatus()
    {
        return $this->website_status;
    }

    public function setWebsiteStatus($website_status)
    {
        $this->website_status = $website_status;
    }

    public function getAutoRestock()
    {
        return $this->auto_restock;
    }

    public function setAutoRestock($auto_restock)
    {
        $this->auto_restock = $auto_restock;
    }

    public function getListingStatus()
    {
        return $this->listing_status;
    }

    public function setListingStatus($listing_status)
    {
        $this->listing_status = $listing_status;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getVbPrice()
    {
        return $this->vb_price;
    }

    public function setVbPrice($vb_price)
    {
        $this->vb_price = $vb_price;
    }

    public function getIsAdvertised()
    {
        return $this->is_advertised;
    }

    public function setIsAdvertised($is_advertised)
    {
        $this->is_advertised = $is_advertised;
    }

    public function getAutoPrice()
    {
        return $this->auto_price;
    }

    public function setAutoPrice($auto_price)
    {
        $this->auto_price = $auto_price;
    }

    public function getTotalCost()
    {
        return $this->total_cost;
    }

    public function setTotalCost($total_cost)
    {
        $this->total_cost = $total_cost;
    }

    public function getProfit()
    {
        return $this->profit;
    }

    public function setProfit($profit)
    {
        $this->profit = $profit;
    }

    public function getMargin()
    {
        return $this->margin;
    }

    public function setMargin($margin)
    {
        $this->margin = $margin;
    }

    public function getCurrencyId()
    {
        return $this->currency_id;
    }

    public function setCurrencyId($currency_id)
    {
        $this->currency_id = $currency_id;
    }

    public function getSupplierStatus()
    {
        return $this->supplier_status;
    }

    public function setSupplierStatus($supplier_status)
    {
        $this->supplier_status = $supplier_status;
    }

    public function getModifyOn()
    {
        return $this->modify_on;
    }

    public function setModifyOn($modify_on)
    {
        $this->modify_on = $modify_on;
    }

    public function getPlatformCurrencyId()
    {
        return $this->platform_currency_id;
    }

    public function setPlatformCurrencyId($platform_currency_id)
    {
        $this->platform_currency_id = $platform_currency_id;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function setCategory($category)
    {
        $this->category = $category;
    }

    public function getSubCategory()
    {
        return $this->sub_category;
    }

    public function setSubCategory($sub_category)
    {
        $this->sub_category = $sub_category;
    }

    public function getSubSubCategory()
    {
        return $this->sub_sub_category;
    }

    public function setSubSubCategory($sub_sub_category)
    {
        $this->sub_sub_category = $sub_sub_category;
    }

    public function getBrandName()
    {
        return $this->brand_name;
    }

    public function setBrandName($brand_name)
    {
        $this->brand_name = $brand_name;
    }
}
