<?php
class ProdBundleDto
{
    private $prod_sku;
    private $main_prod_sku;
    private $name;
    private $total_price;
    private $website_status;
    private $website_quantity;
    private $component_sku_list;
    private $bundle_name;

    public function setProdSku($prod_sku)
    {
        $this->prod_sku = $prod_sku;
    }

    public function getProdSku()
    {
        return $this->prod_sku;
    }

    public function setMainProdSku($main_prod_sku)
    {
        $this->main_prod_sku = $main_prod_sku;
    }

    public function getMainProdSku()
    {
        return $this->main_prod_sku;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setTotalPrice($total_price)
    {
        $this->total_price = $total_price;
    }

    public function getTotalPrice()
    {
        return $this->total_price;
    }

    public function setWebsiteStatus($website_status)
    {
        $this->website_status = $website_status;
    }

    public function getWebsiteStatus()
    {
        return $this->website_status;
    }

    public function setWebsiteQuantity($website_quantity)
    {
        $this->website_quantity = $website_quantity;
    }

    public function getWebsiteQuantity()
    {
        return $this->website_quantity;
    }

    public function setComponentSkuList($component_sku_list)
    {
        $this->component_sku_list = $component_sku_list;
    }

    public function getComponentSkuList()
    {
        return $this->component_sku_list;
    }

    public function setBundleName($bundle_name)
    {
        $this->bundle_name = $bundle_name;
    }

    public function getBundleName()
    {
        return $this->bundle_name;
    }

}
