<?php
class CurrencyVo extends \BaseVo
{
    private $id;
    private $currency_id;
    private $sign;
    private $name = '';
    private $description = '';
    private $round_up = '';
    private $round_up_nearest_for_price_table = '0.99';
    private $sign_pos = '';
    private $dec_place = '0';
    private $dec_point = '';
    private $thousands_sep = '';
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

    public function setSign($sign)
    {
        if ($sign != null) {
            $this->sign = $sign;
        }
    }

    public function getSign()
    {
        return $this->sign;
    }

    public function setName($name)
    {
        if ($name != null) {
            $this->name = $name;
        }
    }

    public function getName()
    {
        return $this->name;
    }

    public function setDescription($description)
    {
        if ($description != null) {
            $this->description = $description;
        }
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setRoundUp($round_up)
    {
        if ($round_up != null) {
            $this->round_up = $round_up;
        }
    }

    public function getRoundUp()
    {
        return $this->round_up;
    }

    public function setRoundUpNearestForPriceTable($round_up_nearest_for_price_table)
    {
        if ($round_up_nearest_for_price_table != null) {
            $this->round_up_nearest_for_price_table = $round_up_nearest_for_price_table;
        }
    }

    public function getRoundUpNearestForPriceTable()
    {
        return $this->round_up_nearest_for_price_table;
    }

    public function setSignPos($sign_pos)
    {
        if ($sign_pos != null) {
            $this->sign_pos = $sign_pos;
        }
    }

    public function getSignPos()
    {
        return $this->sign_pos;
    }

    public function setDecPlace($dec_place)
    {
        if ($dec_place != null) {
            $this->dec_place = $dec_place;
        }
    }

    public function getDecPlace()
    {
        return $this->dec_place;
    }

    public function setDecPoint($dec_point)
    {
        if ($dec_point != null) {
            $this->dec_point = $dec_point;
        }
    }

    public function getDecPoint()
    {
        return $this->dec_point;
    }

    public function setThousandsSep($thousands_sep)
    {
        if ($thousands_sep != null) {
            $this->thousands_sep = $thousands_sep;
        }
    }

    public function getThousandsSep()
    {
        return $this->thousands_sep;
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
