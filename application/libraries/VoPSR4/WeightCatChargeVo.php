<?php
class WeightCatChargeVo extends \BaseVo
{
    private $wcat_id;
    private $delivery_type;
    private $dest_country;
    private $currency_id;
    private $amount;
    private $create_on = '0000-00-00 00:00:00';
    private $create_at = '2130706433';
    private $create_by = 'system';
    private $modify_on = '';
    private $modify_at = '2130706433';
    private $modify_by = 'system';

    private $primary_key = ['wcat_id', 'delivery_type', 'dest_country'];
    private $increment_field = '';

    public function setWcatId($wcat_id)
    {
        if ($wcat_id !== null) {
            $this->wcat_id = $wcat_id;
        }
    }

    public function getWcatId()
    {
        return $this->wcat_id;
    }

    public function setDeliveryType($delivery_type)
    {
        if ($delivery_type !== null) {
            $this->delivery_type = $delivery_type;
        }
    }

    public function getDeliveryType()
    {
        return $this->delivery_type;
    }

    public function setDestCountry($dest_country)
    {
        if ($dest_country !== null) {
            $this->dest_country = $dest_country;
        }
    }

    public function getDestCountry()
    {
        return $this->dest_country;
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

    public function setAmount($amount)
    {
        if ($amount !== null) {
            $this->amount = $amount;
        }
    }

    public function getAmount()
    {
        return $this->amount;
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
