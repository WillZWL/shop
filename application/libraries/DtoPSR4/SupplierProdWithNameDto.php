<?php
class SupplierProdWithNameDto
{
    private $supplier_id;
    private $prod_sku;
    private $currency_id;
    private $cost;
    private $moq;
    private $order_default;
    private $region_default;
    private $supplier_status;
    private $create_on;
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;
    private $supplier_name;
    private $origin_country;
    private $region_name;
    private $creditor;
    private $sourcing_reg;
    private $total_cost;

    public function setSupplierId($supplier_id)
    {
        $this->supplier_id = $supplier_id;
    }

    public function getSupplierId()
    {
        return $this->supplier_id;
    }

    public function setProdSku($prod_sku)
    {
        $this->prod_sku = $prod_sku;
    }

    public function getProdSku()
    {
        return $this->prod_sku;
    }

    public function setCurrencyId($currency_id)
    {
        $this->currency_id = $currency_id;
    }

    public function getCurrencyId()
    {
        return $this->currency_id;
    }

    public function setCost($cost)
    {
        $this->cost = $cost;
    }

    public function getCost()
    {
        return $this->cost;
    }

    public function setMoq($moq)
    {
        $this->moq = $moq;
    }

    public function getMoq()
    {
        return $this->moq;
    }

    public function setOrderDefault($order_default)
    {
        $this->order_default = $order_default;
    }

    public function getOrderDefault()
    {
        return $this->order_default;
    }

    public function setRegionDefault($region_default)
    {
        $this->region_default = $region_default;
    }

    public function getRegionDefault()
    {
        return $this->region_default;
    }

    public function setSupplierStatus($supplier_status)
    {
        $this->supplier_status = $supplier_status;
    }

    public function getSupplierStatus()
    {
        return $this->supplier_status;
    }

    public function setCreateOn($create_on)
    {
        $this->create_on = $create_on;
    }

    public function getCreateOn()
    {
        return $this->create_on;
    }

    public function setCreateAt($create_at)
    {
        $this->create_at = $create_at;
    }

    public function getCreateAt()
    {
        return $this->create_at;
    }

    public function setCreateBy($create_by)
    {
        $this->create_by = $create_by;
    }

    public function getCreateBy()
    {
        return $this->create_by;
    }

    public function setModifyOn($modify_on)
    {
        $this->modify_on = $modify_on;
    }

    public function getModifyOn()
    {
        return $this->modify_on;
    }

    public function setModifyAt($modify_at)
    {
        $this->modify_at = $modify_at;
    }

    public function getModifyAt()
    {
        return $this->modify_at;
    }

    public function setModifyBy($modify_by)
    {
        $this->modify_by = $modify_by;
    }

    public function getModifyBy()
    {
        return $this->modify_by;
    }

    public function setSupplierName($supplier_name)
    {
        $this->supplier_name = $supplier_name;
    }

    public function getSupplierName()
    {
        return $this->supplier_name;
    }

    public function setOriginCountry($origin_country)
    {
        $this->origin_country = $origin_country;
    }

    public function getOriginCountry()
    {
        return $this->origin_country;
    }

    public function setRegionName($region_name)
    {
        $this->region_name = $region_name;
    }

    public function getRegionName()
    {
        return $this->region_name;
    }

    public function setCreditor($creditor)
    {
        $this->creditor = $creditor;
    }

    public function getCreditor()
    {
        return $this->creditor;
    }

    public function setSourcingReg($sourcing_reg)
    {
        $this->sourcing_reg = $sourcing_reg;
    }

    public function getSourcingReg()
    {
        return $this->sourcing_reg;
    }

    public function setTotalCost($total_cost)
    {
        $this->total_cost = $total_cost;
    }

    public function getTotalCost()
    {
        return $this->total_cost;
    }

}
