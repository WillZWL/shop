<?php
class FullCpsWithCatIdDto
{
    private $psg_id;
    private $psg_name;
    private $ps_func_id;
    private $ps_name;
    private $unit_type_id;
    private $unit_type_name;
    private $cat_id;
    private $unit_id;
    private $unit_name;
    private $priority;
    private $status;

    public function getPsgId()
    {
        return $this->psg_id;
    }

    public function setPsgId($value)
    {
        $this->psg_id = $value;
    }

    public function getPsgName()
    {
        return $this->psg_name;
    }

    public function setPsgName($value)
    {
        $this->psg_name = $value;
    }

    public function getPsFuncId()
    {
        return $this->ps_func_id;
    }

    public function setPsFuncId($value)
    {
        $this->ps_func_id = $value;
    }

    public function getPsName()
    {
        return $this->ps_name;
    }

    public function setPsName($value)
    {
        $this->ps_name = $value;
    }

    public function getUnitTypeId()
    {
        return $this->unit_type_id;
    }

    public function setUnitTypeId($value)
    {
        $this->unit_type_id = $value;
    }

    public function getUnitTypeName()
    {
        return $this->unit_type_name;
    }

    public function setUnitTypeName($value)
    {
        $this->unit_type_name = $value;
    }

    public function getCatId()
    {
        return $this->cat_id;
    }

    public function setCatId($value)
    {
        $this->cat_id = $value;
    }

    public function getUnitId()
    {
        return $this->unit_id;
    }

    public function setUnitId($value)
    {
        $this->unit_id = $value;
    }

    public function getUnitName()
    {
        return $this->unit_name;
    }

    public function setUnitName($value)
    {
        $this->unit_name = $value;
    }

    public function getPriority()
    {
        return $this->priority;
    }

    public function setPriority($value)
    {
        $this->priority = $value;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($value)
    {
        $this->status = $value;
    }
}

