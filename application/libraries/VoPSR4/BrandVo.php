<?php

class BrandVo extends \BaseVo
{
    private $id;
    private $brand_name;
    private $description = '';
    private $accelerator = '0';
    private $customer_code = '';
    private $status = '1';

    protected $primary_key = ['id'];
    protected $increment_field = 'id';

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

    public function setAccelerator($accelerator)
    {
        if ($accelerator !== null) {
            $this->accelerator = $accelerator;
        }
    }

    public function getAccelerator()
    {
        return $this->accelerator;
    }

    public function setCustomerCode($customer_code)
    {
        if ($customer_code !== null) {
            $this->customer_code = $customer_code;
        }
    }

    public function getCustomerCode()
    {
        return $this->customer_code;
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
