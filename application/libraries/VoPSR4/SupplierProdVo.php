<?php
class SupplierProdVo extends \BaseVo
{
    private $id;
    private $supplier_id;
    private $prod_sku;
    private $currency_id;
    private $cost;
    private $pricehkd;
    private $lead_day = '0';
    private $moq = '0';
    private $location = '';
    private $region = '';
    private $surplus_qty = '0';
    private $order_default = '1';
    private $region_default = '0';
    private $supplier_status = 'A';
    private $comments = '';
    private $create_on = '0000-00-00 00:00:00';
    private $create_at = '2130706433';
    private $create_by = 'system';
    private $modify_on = '';
    private $modify_at = '2130706433';
    private $modify_by = 'system';

    private $primary_key = ['id'];
    private $increment_field = 'id';

    public function setId($id)
    {
        if ($id != null) {
            $this->id = $id;
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function setSupplierId($supplier_id)
    {
        if ($supplier_id != null) {
            $this->supplier_id = $supplier_id;
        }
    }

    public function getSupplierId()
    {
        return $this->supplier_id;
    }

    public function setProdSku($prod_sku)
    {
        if ($prod_sku != null) {
            $this->prod_sku = $prod_sku;
        }
    }

    public function getProdSku()
    {
        return $this->prod_sku;
    }

    public function setCurrencyId($currency_id)
    {
        if ($currency_id != null) {
            $this->currency_id = $currency_id;
        }
    }

    public function getCurrencyId()
    {
        return $this->currency_id;
    }

    public function setCost($cost)
    {
        if ($cost != null) {
            $this->cost = $cost;
        }
    }

    public function getCost()
    {
        return $this->cost;
    }

    public function setPricehkd($pricehkd)
    {
        if ($pricehkd != null) {
            $this->pricehkd = $pricehkd;
        }
    }

    public function getPricehkd()
    {
        return $this->pricehkd;
    }

    public function setLeadDay($lead_day)
    {
        if ($lead_day != null) {
            $this->lead_day = $lead_day;
        }
    }

    public function getLeadDay()
    {
        return $this->lead_day;
    }

    public function setMoq($moq)
    {
        if ($moq != null) {
            $this->moq = $moq;
        }
    }

    public function getMoq()
    {
        return $this->moq;
    }

    public function setLocation($location)
    {
        if ($location != null) {
            $this->location = $location;
        }
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function setRegion($region)
    {
        if ($region != null) {
            $this->region = $region;
        }
    }

    public function getRegion()
    {
        return $this->region;
    }

    public function setSurplusQty($surplus_qty)
    {
        if ($surplus_qty != null) {
            $this->surplus_qty = $surplus_qty;
        }
    }

    public function getSurplusQty()
    {
        return $this->surplus_qty;
    }

    public function setOrderDefault($order_default)
    {
        if ($order_default != null) {
            $this->order_default = $order_default;
        }
    }

    public function getOrderDefault()
    {
        return $this->order_default;
    }

    public function setRegionDefault($region_default)
    {
        if ($region_default != null) {
            $this->region_default = $region_default;
        }
    }

    public function getRegionDefault()
    {
        return $this->region_default;
    }

    public function setSupplierStatus($supplier_status)
    {
        if ($supplier_status != null) {
            $this->supplier_status = $supplier_status;
        }
    }

    public function getSupplierStatus()
    {
        return $this->supplier_status;
    }

    public function setComments($comments)
    {
        if ($comments != null) {
            $this->comments = $comments;
        }
    }

    public function getComments()
    {
        return $this->comments;
    }

    public function setCreateOn($create_on)
    {
        if ($create_on != null) {
            $this->create_on = $create_on;
        }
    }

    public function getCreateOn()
    {
        return $this->create_on;
    }

    public function setCreateAt($create_at)
    {
        if ($create_at != null) {
            $this->create_at = $create_at;
        }
    }

    public function getCreateAt()
    {
        return $this->create_at;
    }

    public function setCreateBy($create_by)
    {
        if ($create_by != null) {
            $this->create_by = $create_by;
        }
    }

    public function getCreateBy()
    {
        return $this->create_by;
    }

    public function setModifyOn($modify_on)
    {
        if ($modify_on != null) {
            $this->modify_on = $modify_on;
        }
    }

    public function getModifyOn()
    {
        return $this->modify_on;
    }

    public function setModifyAt($modify_at)
    {
        if ($modify_at != null) {
            $this->modify_at = $modify_at;
        }
    }

    public function getModifyAt()
    {
        return $this->modify_at;
    }

    public function setModifyBy($modify_by)
    {
        if ($modify_by != null) {
            $this->modify_by = $modify_by;
        }
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
