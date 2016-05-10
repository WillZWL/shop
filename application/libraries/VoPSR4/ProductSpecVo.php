<?php
class ProductSpecVo extends \BaseVo
{
    private $id;
    private $product_spec_id = '';
    private $psg_id;
    private $code = '';
    private $name = '';
    private $unit_type_id;
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

    public function setProductSpecId($product_spec_id)
    {
        if ($product_spec_id !== null) {
            $this->product_spec_id = $product_spec_id;
        }
    }

    public function getProductSpecId()
    {
        return $this->product_spec_id;
    }

    public function setPsgId($psg_id)
    {
        if ($psg_id !== null) {
            $this->psg_id = $psg_id;
        }
    }

    public function getPsgId()
    {
        return $this->psg_id;
    }

    public function setCode($code)
    {
        if ($code !== null) {
            $this->code = $code;
        }
    }

    public function getCode()
    {
        return $this->code;
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
