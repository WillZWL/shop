<?php
class SubCatPlatformVarVo extends \BaseVo
{
    private $id;
    private $sub_cat_id;
    private $platform_id;
    private $currency_id;
    private $platform_commission;
    private $dlvry_chrg;
    private $custom_class_id;
    private $fixed_fee = '0.00';
    private $profit_margin = '0.00';
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

    public function setSubCatId($sub_cat_id)
    {
        if ($sub_cat_id != null) {
            $this->sub_cat_id = $sub_cat_id;
        }
    }

    public function getSubCatId()
    {
        return $this->sub_cat_id;
    }

    public function setPlatformId($platform_id)
    {
        if ($platform_id != null) {
            $this->platform_id = $platform_id;
        }
    }

    public function getPlatformId()
    {
        return $this->platform_id;
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

    public function setPlatformCommission($platform_commission)
    {
        if ($platform_commission != null) {
            $this->platform_commission = $platform_commission;
        }
    }

    public function getPlatformCommission()
    {
        return $this->platform_commission;
    }

    public function setDlvryChrg($dlvry_chrg)
    {
        if ($dlvry_chrg != null) {
            $this->dlvry_chrg = $dlvry_chrg;
        }
    }

    public function getDlvryChrg()
    {
        return $this->dlvry_chrg;
    }

    public function setCustomClassId($custom_class_id)
    {
        if ($custom_class_id != null) {
            $this->custom_class_id = $custom_class_id;
        }
    }

    public function getCustomClassId()
    {
        return $this->custom_class_id;
    }

    public function setFixedFee($fixed_fee)
    {
        if ($fixed_fee != null) {
            $this->fixed_fee = $fixed_fee;
        }
    }

    public function getFixedFee()
    {
        return $this->fixed_fee;
    }

    public function setProfitMargin($profit_margin)
    {
        if ($profit_margin != null) {
            $this->profit_margin = $profit_margin;
        }
    }

    public function getProfitMargin()
    {
        return $this->profit_margin;
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
