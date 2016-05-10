<?php
class BrandVo extends \BaseVo
{
    private $id;
    private $brand_name;
    private $description = '';
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

    public function setBrandName($brand_name)
    {
        if ($brand_name !== null) {
            $this->brand_name = $brand_name;
        }
    }

    public function getBrandName()
    {
        return $this->brand_name;
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
