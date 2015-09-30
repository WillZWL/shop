<?php
class ProdSupplierInfoDto
{
    private $sku;
    private $name;
    private $surplus_quantity;
    private $slow_move__7_days;
    private $sourcing_status;
    private $platform_id;
    private $price;
    private $supplier_id;
    private $supplier_status;
    private $origin_country;
    private $supplier_name;
    private $git;

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

    public function setSurplusQuantity($surplus_quantity)
    {
        $this->surplus_quantity = $surplus_quantity;
    }

    public function getSurplusQuantity()
    {
        return $this->surplus_quantity;
    }

    public function setSlowMove7Days($slow_move__7_days)
    {
        $this->slow_move__7_days = $slow_move__7_days;
    }

    public function getSlowMove7Days()
    {
        return $this->slow_move__7_days;
    }

    public function setSourcingStatus($sourcing_status)
    {
        $this->sourcing_status = $sourcing_status;
    }

    public function getSourcingStatus()
    {
        return $this->sourcing_status;
    }

    public function setPlatformId($platform_id)
    {
        $this->platform_id = $platform_id;
    }

    public function getPlatformId()
    {
        return $this->platform_id;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setSupplierId($supplier_id)
    {
        $this->supplier_id = $supplier_id;
    }

    public function getSupplierId()
    {
        return $this->supplier_id;
    }

    public function setSupplierStatus($supplier_status)
    {
        $this->supplier_status = $supplier_status;
    }

    public function getSupplierStatus()
    {
        return $this->supplier_status;
    }

    public function setOriginCountry($origin_country)
    {
        $this->origin_country = $origin_country;
    }

    public function getOriginCountry()
    {
        return $this->origin_country;
    }

    public function setSupplierName($supplier_name)
    {
        $this->supplier_name = $supplier_name;
    }

    public function getSupplierName()
    {
        return $this->supplier_name;
    }

    public function setGit($git)
    {
        $this->git = $git;
    }

    public function getGit()
    {
        return $this->git;
    }

}
