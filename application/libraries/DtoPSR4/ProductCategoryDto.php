<?php
class ProductCategoryDto
{
    private $ext_sku;
    private $name;
    private $brand_name;
    private $cat_name;
    private $sub_cat_name;
    private $sub_sub_cat_name;
    private $customer_code;

    public function setExtSku($ext_sku)
    {
        $this->ext_sku = $ext_sku;
    }

    public function getExtSku()
    {
        return $this->ext_sku;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setBrandName($brand_name)
    {
        $this->brand_name = $brand_name;
    }

    public function getBrandName()
    {
        return $this->brand_name;
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

    public function setSubSubCatName($sub_sub_cat_name)
    {
        $this->sub_sub_cat_name = $sub_sub_cat_name;
    }

    public function getSubSubCatName()
    {
        return $this->sub_sub_cat_name;
    }


    public function setCustomerCode($customer_code)
    {
        $this->customer_code = $customer_code;
    }

    public function getCustomerCode()
    {
        return $this->customer_code;
    }
}
