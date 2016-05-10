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

    public function setUnitId($unit_id)
    {
        if ($unit_id !== null) {
            $this->unit_id = $unit_id;
        }
    }

    public function getUnitId()
    {
        return $this->unit_id;
    }

    public function setUnitTypeId($unit_type_id)
    {
        if ($unit_type_id !== null) {
            $this->unit_type_id = $unit_type_id;
        }
    }

    public function getUnitTypeId()
    {
        return $this->unit_type_id;
    }

    public function setUnitName($unit_name)
    {
        if ($unit_name !== null) {
            $this->unit_name = $unit_name;
        }
    }

    public function getUnitName()
    {
        return $this->unit_name;
    }

    public function setFuncUnitName($func_unit_name)
    {
        if ($func_unit_name !== null) {
            $this->func_unit_name = $func_unit_name;
        }
    }

    public function getFuncUnitName()
    {
        return $this->func_unit_name;
    }

    public function setStandardizeValue($standardize_value)
    {
        if ($standardize_value !== null) {
            $this->standardize_value = $standardize_value;
        }
    }

    public function getStandardizeValue()
    {
        return $this->standardize_value;
    }

    public function setDescription($description)
    {
        if ($description !== null) {
            $this->description = $description;
        }
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setStatus($status)
    {
        if ($status !== null) {
            $this->status = $status;
        }
    }

    public function getStatus()
    {
        return $this->status;
    }

}
