<?php
class CurrencyVo extends \BaseVo
{
    //class variable
    private $id;
    private $sign;
    private $name;
    private $description;
    private $round_up;
    private $sign_pos;
    private $dec_place;
    private $dec_point;
    private $thousands_sep;
    private $create_on = '0000-00-00 00:00:00';
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;

    private $primary_key = ["id"];

    private $increment_field = "";

    public function getId()
    {
        return $this->id;
    }

    public function setId($value)
    {
        $this->id = $value;
        return $this;
    }

    public function getSign()
    {
        return $this->sign;
    }

    public function setSign($value)
    {
        $this->sign = $value;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($value)
    {
        $this->name = $value;
        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($value)
    {
        $this->description = $value;
        return $this;
    }

    public function getRoundUp()
    {
        return $this->round_up;
    }

    public function setRoundUp($value)
    {
        $this->round_up = $value;
        return $this;
    }

    public function getSignPos()
    {
        return $this->sign_pos;
    }

    public function setSignPos($value)
    {
        $this->sign_pos = $value;
        return $this;
    }

    public function getDecPlace()
    {
        return $this->dec_place;
    }

    public function setDecPlace($value)
    {
        $this->dec_place = $value;
        return $this;
    }

    public function getDecPoint()
    {
        return $this->dec_point;
    }

    public function setDecPoint($value)
    {
        $this->dec_point = $value;
        return $this;
    }

    public function getThousandsSep()
    {
        return $this->thousands_sep;
    }

    public function setThousandsSep($value)
    {
        $this->thousands_sep = $value;
        return $this;
    }

    public function getCreateOn()
    {
        return $this->create_on;
    }

    public function setCreateOn($value)
    {
        $this->create_on = $value;
        return $this;
    }

    public function getCreateAt()
    {
        return $this->create_at;
    }

    public function setCreateAt($value)
    {
        $this->create_at = $value;
        return $this;
    }

    public function getCreateBy()
    {
        return $this->create_by;
    }

    public function setCreateBy($value)
    {
        $this->create_by = $value;
        return $this;
    }

    public function getModifyOn()
    {
        return $this->modify_on;
    }

    public function setModifyOn($value)
    {
        $this->modify_on = $value;
        return $this;
    }

    public function getModifyAt()
    {
        return $this->modify_at;
    }

    public function setModifyAt($value)
    {
        $this->modify_at = $value;
        return $this;
    }

    public function getModifyBy()
    {
        return $this->modify_by;
    }

    public function setModifyBy($value)
    {
        $this->modify_by = $value;
        return $this;
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
