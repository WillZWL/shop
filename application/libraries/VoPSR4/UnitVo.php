<?php
class UnitVo extends \BaseVo
{
    private $id;
    private $unit_id;
    private $unit_type_id;
    private $unit_name;
    private $func_unit_name;
    private $standardize_value = '1.0000';
    private $description;
    private $status = '1';
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

    public function setUnitId($unit_id)
    {
        $this->unit_id = $unit_id;
    }

    public function getUnitId()
    {
        return $this->unit_id;
    }

    public function setUnitTypeId($unit_type_id)
    {
        $this->unit_type_id = $unit_type_id;
    }

    public function getUnitTypeId()
    {
        return $this->unit_type_id;
    }

    public function setUnitName($unit_name)
    {
        $this->unit_name = $unit_name;
    }

    public function getUnitName()
    {
        return $this->unit_name;
    }

    public function setFuncUnitName($func_unit_name)
    {
        $this->func_unit_name = $func_unit_name;
    }

    public function getFuncUnitName()
    {
        return $this->func_unit_name;
    }

    public function setStandardizeValue($standardize_value)
    {
        $this->standardize_value = $standardize_value;
    }

    public function getStandardizeValue()
    {
        return $this->standardize_value;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
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