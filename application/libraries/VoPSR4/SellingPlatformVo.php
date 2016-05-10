<?php
class SellingPlatformVo extends \BaseVo
{
    private $id;
    private $selling_platform_id;
    private $type = 'WEBSITE';
    private $name;
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

    public function setSellingPlatformId($selling_platform_id)
    {
        if ($selling_platform_id !== null) {
            $this->selling_platform_id = $selling_platform_id;
        }
    }

    public function getSellingPlatformId()
    {
        return $this->selling_platform_id;
    }

    public function setType($type)
    {
        if ($type !== null) {
            $this->type = $type;
        }
    }

    public function getType()
    {
        return $this->type;
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
