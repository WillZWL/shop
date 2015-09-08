<?php
class SupplierProdVo extends \BaseVo
{
    private $id;
    private $supplier_id;
    private $prod_sku;
    private $currency_id;
    private $cost;
    private $lead_day;
    private $moq;
    private $order_default = '1';
    private $region_default;
    private $supplier_status = 'A';
    private $create_on = '0000-00-00 00:00:00';
    private $create_at = '2130706433';
    private $create_by = 'system';
    private $modify_on = 'CURRENT_TIMESTAMP';
    private $modify_at = '2130706433';
    private $modify_by = 'system';

    private $primary_key = ['id'];
    private $increment_field = 'id';

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

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

    public function setLeadDay($lead_day)
    {
        $this->lead_day = $lead_day;
    }

    public function getLeadDay()
    {
        return $this->lead_day;
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

    public function getPrimaryKey()
    {
        return $this->primary_key;
    }

    public function getIncrementField()
    {
        return $this->increment_field;
    }
}
