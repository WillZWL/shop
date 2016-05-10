<?php
class SubCatPlatformVarVo extends \BaseVo
{
    private $id;
    private $sub_cat_id;
    private $platform_id;
    private $currency_id;
    private $platform_commission_percent = '0.00';
    private $dlvry_chrg;
    private $custom_class_id;
    private $fixed_fee = '0.00';
    private $profit_margin = '0.00';

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

    public function setSubCatId($sub_cat_id)
    {
        if ($sub_cat_id !== null) {
            $this->sub_cat_id = $sub_cat_id;
        }
    }

    public function getSubCatId()
    {
        return $this->sub_cat_id;
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

    public function setPlatformCommissionPercent($platform_commission_percent)
    {
        if ($platform_commission_percent !== null) {
            $this->platform_commission_percent = $platform_commission_percent;
        }
    }

    public function getPlatformCommissionPercent()
    {
        return $this->platform_commission_percent;
    }

    public function setDlvryChrg($dlvry_chrg)
    {
        if ($dlvry_chrg !== null) {
            $this->dlvry_chrg = $dlvry_chrg;
        }
    }

    public function getDlvryChrg()
    {
        return $this->dlvry_chrg;
    }

    public function setCustomClassId($custom_class_id)
    {
        if ($custom_class_id !== null) {
            $this->custom_class_id = $custom_class_id;
        }
    }

    public function getCustomClassId()
    {
        return $this->custom_class_id;
    }

    public function setFixedFee($fixed_fee)
    {
        if ($fixed_fee !== null) {
            $this->fixed_fee = $fixed_fee;
        }
    }

    public function getFixedFee()
    {
        return $this->fixed_fee;
    }

    public function setProfitMargin($profit_margin)
    {
        if ($profit_margin !== null) {
            $this->profit_margin = $profit_margin;
        }
    }

    public function getProfitMargin()
    {
        return $this->profit_margin;
    }

}
