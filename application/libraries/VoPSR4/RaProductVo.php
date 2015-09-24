<?php

class RaProductVo extends \BaseVo
{
    private $sku;

    //class variable
    private $rcm_group_id_1;
    private $rcm_group_id_2;
    private $rcm_group_id_3;
    private $rcm_group_id_4;
    private $rcm_group_id_5;
    private $rcm_group_id_6;
    private $rcm_group_id_7;
    private $rcm_group_id_8;
    private $rcm_group_id_9;
    private $rcm_group_id_10;
    private $rcm_group_id_11;
    private $rcm_group_id_12;
    private $rcm_group_id_13;
    private $rcm_group_id_14;
    private $rcm_group_id_15;
    private $rcm_group_id_16;
    private $rcm_group_id_17;
    private $rcm_group_id_18;
    private $rcm_group_id_19;
    private $rcm_group_id_20;
    private $create_on = '0000-00-00 00:00:00';
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;
    private $primary_key = array("sku");

    //primary key
    private $increment_field = "";

    //auo increment

    public function __construct()
    {
        parent::Base_vo();
    }

    //instance method

    public function getSku()
    {
        return $this->sku;
    }

    public function setSku($value)
    {
        $this->sku = $value;
        return $this;
    }

    public function getRcmGroupId1()
    {
        return $this->rcm_group_id_1;
    }

    public function setRcmGroupId1($value)
    {
        $this->rcm_group_id_1 = $value;
        return $this;
    }

    public function getRcmGroupId2()
    {
        return $this->rcm_group_id_2;
    }

    public function setRcmGroupId2($value)
    {
        $this->rcm_group_id_2 = $value;
        return $this;
    }

    public function getRcmGroupId3()
    {
        return $this->rcm_group_id_3;
    }

    public function setRcmGroupId3($value)
    {
        $this->rcm_group_id_3 = $value;
        return $this;
    }

    public function getRcmGroupId4()
    {
        return $this->rcm_group_id_4;
    }

    public function setRcmGroupId4($value)
    {
        $this->rcm_group_id_4 = $value;
        return $this;
    }

    public function getRcmGroupId5()
    {
        return $this->rcm_group_id_5;
    }

    public function setRcmGroupId5($value)
    {
        $this->rcm_group_id_5 = $value;
        return $this;
    }

    public function getRcmGroupId6()
    {
        return $this->rcm_group_id_6;
    }

    public function setRcmGroupId6($value)
    {
        $this->rcm_group_id_6 = $value;
        return $this;
    }

    public function getRcmGroupId7()
    {
        return $this->rcm_group_id_7;
    }

    public function setRcmGroupId7($value)
    {
        $this->rcm_group_id_7 = $value;
        return $this;
    }

    public function getRcmGroupId8()
    {
        return $this->rcm_group_id_8;
    }

    public function setRcmGroupId8($value)
    {
        $this->rcm_group_id_8 = $value;
        return $this;
    }

    public function getRcmGroupId9()
    {
        return $this->rcm_group_id_9;
    }

    public function setRcmGroupId9($value)
    {
        $this->rcm_group_id_9 = $value;
        return $this;
    }

    public function getRcmGroupId10()
    {
        return $this->rcm_group_id_10;
    }

    public function setRcmGroupId10($value)
    {
        $this->rcm_group_id_10 = $value;
        return $this;
    }

    public function getRcmGroupId11()
    {
        return $this->rcm_group_id_11;
    }

    public function setRcmGroupId11($value)
    {
        $this->rcm_group_id_11 = $value;
        return $this;
    }

    public function getRcmGroupId12()
    {
        return $this->rcm_group_id_12;
    }

    public function setRcmGroupId12($value)
    {
        $this->rcm_group_id_12 = $value;
        return $this;
    }

    public function getRcmGroupId13()
    {
        return $this->rcm_group_id_13;
    }

    public function setRcmGroupId13($value)
    {
        $this->rcm_group_id_13 = $value;
        return $this;
    }

    public function getRcmGroupId14()
    {
        return $this->rcm_group_id_14;
    }

    public function setRcmGroupId14($value)
    {
        $this->rcm_group_id_14 = $value;
        return $this;
    }

    public function getRcmGroupId15()
    {
        return $this->rcm_group_id_15;
    }

    public function setRcmGroupId15($value)
    {
        $this->rcm_group_id_15 = $value;
        return $this;
    }

    public function getRcmGroupId16()
    {
        return $this->rcm_group_id_16;
    }

    public function setRcmGroupId16($value)
    {
        $this->rcm_group_id_16 = $value;
        return $this;
    }

    public function getRcmGroupId17()
    {
        return $this->rcm_group_id_17;
    }

    public function setRcmGroupId17($value)
    {
        $this->rcm_group_id_17 = $value;
        return $this;
    }


    public function getRcmGroupId18()
    {
        return $this->rcm_group_id_18;
    }

    public function setRcmGroupId18($value)
    {
        $this->rcm_group_id_18 = $value;
        return $this;
    }

    public function getRcmGroupId19()
    {
        return $this->rcm_group_id_19;
    }

    public function setRcmGroupId19($value)
    {
        $this->rcm_group_id_19 = $value;
        return $this;
    }

    public function getRcmGroupId20()
    {
        return $this->rcm_group_id_20;
    }

    public function setRcmGroupId20($value)
    {
        $this->rcm_group_id_20 = $value;
        return $this;
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
