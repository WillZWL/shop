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

    public function setSubCatId($sub_cat_id)
    {
        $this->sub_cat_id = $sub_cat_id;
    }

    public function getSubCatId()
    {
        return $this->sub_cat_id;
    }

    public function setPlatformId($platform_id)
    {
        $this->platform_id = $platform_id;
    }

    public function getPlatformId()
    {
        return $this->platform_id;
    }

    public function setCurrencyId($currency_id)
    {
        $this->currency_id = $currency_id;
    }

    public function getCurrencyId()
    {
        return $this->currency_id;
    }

    public function setPlatformCommission($platform_commission)
    {
        $this->platform_commission = $platform_commission;
    }

    public function getPlatformCommission()
    {
        return $this->platform_commission;
    }

    public function setDlvryChrg($dlvry_chrg)
    {
        $this->dlvry_chrg = $dlvry_chrg;
    }

    public function getDlvryChrg()
    {
        return $this->dlvry_chrg;
    }

    public function setCustomClassId($custom_class_id)
    {
        $this->custom_class_id = $custom_class_id;
    }

    public function getCustomClassId()
    {
        return $this->custom_class_id;
    }

    public function setFixedFee($fixed_fee)
    {
        $this->fixed_fee = $fixed_fee;
    }

    public function getFixedFee()
    {
        return $this->fixed_fee;
    }

    public function setProfitMargin($profit_margin)
    {
        $this->profit_margin = $profit_margin;
    }

    public function getProfitMargin()
    {
        return $this->profit_margin;
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
