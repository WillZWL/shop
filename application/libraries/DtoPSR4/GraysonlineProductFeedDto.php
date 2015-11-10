<?php
class GraysonlineProductFeedDto
{
    private $sku;
    private $ext_sku;
    private $prod_name;
    private $specification;
    private $detail_desc;
    private $cat_name;
    private $sub_cat_id;
    private $sub_cat_name;
    private $cost;
    private $suggested_selling_price;
    private $image_url;
    private $shipping;
    private $sourcing_status;
    private $price;
    private $supplier_status;
    private $lead_day;
    private $last_week_updated;
    private $supplier_cost;

    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function setExtSku($ext_sku)
    {
        $this->ext_sku = $ext_sku;
    }

    public function getExtSku()
    {
        return $this->ext_sku;
    }

    public function setProdName($prod_name)
    {
        $this->prod_name = $prod_name;
    }

    public function getProdName()
    {
        return $this->prod_name;
    }

    public function setSpecification($specification)
    {
        $this->specification = $specification;
    }

    public function getSpecification()
    {
        return $this->specification;
    }

    public function setDetailDesc($detail_desc)
    {
        $this->detail_desc = $detail_desc;
    }

    public function getDetailDesc()
    {
        return $this->detail_desc;
    }

    public function setCatName($cat_name)
    {
        $this->cat_name = $cat_name;
    }

    public function getCatName()
    {
        return $this->cat_name;
    }

    public function setSubCatId($sub_cat_id)
    {
        $this->sub_cat_id = $sub_cat_id;
    }

    public function getSubCatId()
    {
        return $this->sub_cat_id;
    }

    public function setSubCatName($sub_cat_name)
    {
        $this->sub_cat_name = $sub_cat_name;
    }

    public function getSubCatName()
    {
        return $this->sub_cat_name;
    }

    public function setCost($cost)
    {
        $this->cost = $cost;
    }

    public function getCost()
    {
        return $this->cost;
    }

    public function setSuggestedSellingPrice($suggested_selling_price)
    {
        $this->suggested_selling_price = $suggested_selling_price;
    }

    public function getSuggestedSellingPrice()
    {
        return $this->suggested_selling_price;
    }

    public function setImageUrl($image_url)
    {
        $this->image_url = $image_url;
    }

    public function getImageUrl()
    {
        return $this->image_url;
    }

    public function setShipping($shipping)
    {
        $this->shipping = $shipping;
    }

    public function getShipping()
    {
        return $this->shipping;
    }

    public function setSourcingStatus($sourcing_status)
    {
        $this->sourcing_status = $sourcing_status;
    }

    public function getSourcingStatus()
    {
        return $this->sourcing_status;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setSupplierStatus($supplier_status)
    {
        $this->supplier_status = $supplier_status;
    }

    public function getSupplierStatus()
    {
        return $this->supplier_status;
    }

    public function setLeadDay($lead_day)
    {
        $this->lead_day = $lead_day;
    }

    public function getLeadDay()
    {
        return $this->lead_day;
    }

    public function setLastWeekUpdated($last_week_updated)
    {
        $this->last_week_updated = $last_week_updated;
    }

    public function getLastWeekUpdated()
    {
        return $this->last_week_updated;
    }

    public function setSupplierCost($supplier_cost)
    {
        $this->supplier_cost = $supplier_cost;
    }

    public function getSupplierCost()
    {
        return $this->supplier_cost;
    }

}
