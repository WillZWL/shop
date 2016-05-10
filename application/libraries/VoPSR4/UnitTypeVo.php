<?php
class UnitTypeVo extends \BaseVo
{
    private $id;
    private $unit_type_id;
    private $name;
    private $description;
    private $default_unit;
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

    public function setName($name)
    {
        if ($name !== null) {
            $this->name = $name;
        }
    }

    public function getName()
    {
        return $this->name;
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

    public function setDefaultUnit($default_unit)
    {
        if ($default_unit !== null) {
            $this->default_unit = $default_unit;
        }
    }

    public function getDefaultUnit()
    {
        return $this->default_unit;
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
