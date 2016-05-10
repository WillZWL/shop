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

    public function setId($id)
    {
        if ($id !== null) {
            $this->id = $id;
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function setSupplierId($supplier_id)
    {
        if ($supplier_id !== null) {
            $this->supplier_id = $supplier_id;
        }
    }

    public function getSupplierId()
    {
        return $this->supplier_id;
    }

    public function setProdSku($prod_sku)
    {
        if ($prod_sku !== null) {
            $this->prod_sku = $prod_sku;
        }
    }

    public function getProdSku()
    {
        return $this->prod_sku;
    }

    public function setCurrencyId($currency_id)
    {
        if ($currency_id !== null) {
            $this->currency_id = $currency_id;
        }
    }

    public function getCurrencyId()
    {
        return $this->currency_id;
    }

    public function setCost($cost)
    {
        if ($cost !== null) {
            $this->cost = $cost;
        }
    }

    public function getCost()
    {
        return $this->cost;
    }

    public function setPricehkd($pricehkd)
    {
        if ($pricehkd !== null) {
            $this->pricehkd = $pricehkd;
        }
    }

    public function getPricehkd()
    {
        return $this->pricehkd;
    }

    public function setLeadDay($lead_day)
    {
        if ($lead_day !== null) {
            $this->lead_day = $lead_day;
        }
    }

    public function getLeadDay()
    {
        return $this->lead_day;
    }

    public function setMoq($moq)
    {
        if ($moq !== null) {
            $this->moq = $moq;
        }
    }

    public function getMoq()
    {
        return $this->moq;
    }

    public function setLocation($location)
    {
        if ($location !== null) {
            $this->location = $location;
        }
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function setRegion($region)
    {
        if ($region !== null) {
            $this->region = $region;
        }
    }

    public function getRegion()
    {
        return $this->region;
    }

    public function setSurplusQty($surplus_qty)
    {
        if ($surplus_qty !== null) {
            $this->surplus_qty = $surplus_qty;
        }
    }

    public function getSurplusQty()
    {
        return $this->surplus_qty;
    }

    public function setOrderDefault($order_default)
    {
        if ($order_default !== null) {
            $this->order_default = $order_default;
        }
    }

    public function getOrderDefault()
    {
        return $this->order_default;
    }

    public function setRegionDefault($region_default)
    {
        if ($region_default !== null) {
            $this->region_default = $region_default;
        }
    }

    public function getRegionDefault()
    {
        return $this->region_default;
    }

    public function setSupplierStatus($supplier_status)
    {
        if ($supplier_status !== null) {
            $this->supplier_status = $supplier_status;
        }
    }

    public function getSupplierStatus()
    {
        return $this->supplier_status;
    }

    public function setComments($comments)
    {
        if ($comments !== null) {
            $this->comments = $comments;
        }
    }

    public function getComments()
    {
        return $this->comments;
    }

}
