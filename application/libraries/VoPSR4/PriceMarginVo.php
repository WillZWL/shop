<?php
class PriceMarginVo extends \BaseVo
{
    private $id;
    private $sku;
    private $platform_id;
    private $selling_price = '0.00';
    private $vat = '0.00';
    private $supplier_cost = '0.00';
    private $admin_fee = '0.00';
    private $logistic_cost = '0.00';
    private $payment_charge = '0.00';
    private $forex_fee = '0.00';
    private $listing_fee = '0.00';
    private $duty = '0.00';
    private $total_cost = '0.00';
    private $profit = '0.00';
    private $margin = '0.00';
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
        if ($id !== null) {
            $this->id = $id;
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function setSku($sku)
    {
        if ($sku !== null) {
            $this->sku = $sku;
        }
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function setPlatformId($platform_id)
    {
        if ($platform_id !== null) {
            $this->platform_id = $platform_id;
        }
    }

    public function getPlatformId()
    {
        return $this->platform_id;
    }

    public function setSellingPrice($selling_price)
    {
        if ($selling_price !== null) {
            $this->selling_price = $selling_price;
        }
    }

    public function getSellingPrice()
    {
        return $this->selling_price;
    }

    public function setVat($vat)
    {
        if ($vat !== null) {
            $this->vat = $vat;
        }
    }

    public function getVat()
    {
        return $this->vat;
    }

    public function setSupplierCost($supplier_cost)
    {
        if ($supplier_cost !== null) {
            $this->supplier_cost = $supplier_cost;
        }
    }

    public function getSupplierCost()
    {
        return $this->supplier_cost;
    }

    public function setAdminFee($admin_fee)
    {
        if ($admin_fee !== null) {
            $this->admin_fee = $admin_fee;
        }
    }

    public function getAdminFee()
    {
        return $this->admin_fee;
    }

    public function setLogisticCost($logistic_cost)
    {
        if ($logistic_cost !== null) {
            $this->logistic_cost = $logistic_cost;
        }
    }

    public function getLogisticCost()
    {
        return $this->logistic_cost;
    }

    public function setPaymentCharge($payment_charge)
    {
        if ($payment_charge !== null) {
            $this->payment_charge = $payment_charge;
        }
    }

    public function getPaymentCharge()
    {
        return $this->payment_charge;
    }

    public function setForexFee($forex_fee)
    {
        if ($forex_fee !== null) {
            $this->forex_fee = $forex_fee;
        }
    }

    public function getForexFee()
    {
        return $this->forex_fee;
    }

    public function setListingFee($listing_fee)
    {
        if ($listing_fee !== null) {
            $this->listing_fee = $listing_fee;
        }
    }

    public function getListingFee()
    {
        return $this->listing_fee;
    }

    public function setDuty($duty)
    {
        if ($duty !== null) {
            $this->duty = $duty;
        }
    }

    public function getDuty()
    {
        return $this->duty;
    }

    public function setTotalCost($total_cost)
    {
        if ($total_cost !== null) {
            $this->total_cost = $total_cost;
        }
    }

    public function getTotalCost()
    {
        return $this->total_cost;
    }

    public function setProfit($profit)
    {
        if ($profit !== null) {
            $this->profit = $profit;
        }
    }

    public function getProfit()
    {
        return $this->profit;
    }

    public function setMargin($margin)
    {
        if ($margin !== null) {
            $this->margin = $margin;
        }
    }

    public function getMargin()
    {
        return $this->margin;
    }

    public function setCreateOn($create_on)
    {
        if ($create_on !== null) {
            $this->create_on = $create_on;
        }
    }

    public function getCreateOn()
    {
        return $this->create_on;
    }

    public function setCreateAt($create_at)
    {
        if ($create_at !== null) {
            $this->create_at = $create_at;
        }
    }

    public function getCreateAt()
    {
        return $this->create_at;
    }

    public function setCreateBy($create_by)
    {
        if ($create_by !== null) {
            $this->create_by = $create_by;
        }
    }

    public function getCreateBy()
    {
        return $this->create_by;
    }

    public function setModifyOn($modify_on)
    {
        if ($modify_on !== null) {
            $this->modify_on = $modify_on;
        }
    }

    public function getModifyOn()
    {
        return $this->modify_on;
    }

    public function setModifyAt($modify_at)
    {
        if ($modify_at !== null) {
            $this->modify_at = $modify_at;
        }
    }

    public function getModifyAt()
    {
        return $this->modify_at;
    }

    public function setModifyBy($modify_by)
    {
        if ($modify_by !== null) {
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
