<?php

class DeliveryTypeVo extends \BaseVo
{
    private $id;
    private $delivery_type_id = '';
    private $name = '';
    private $platform_type = '';

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

    public function setDeliveryTypeId($delivery_type_id)
    {
        if ($delivery_type_id !== null) {
            $this->delivery_type_id = $delivery_type_id;
        }
    }

    public function getDeliveryTypeId()
    {
        return $this->delivery_type_id;
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

    public function setPlatformType($platform_type)
    {
        if ($platform_type !== null) {
            $this->platform_type = $platform_type;
        }
    }

    public function getPlatformType()
    {
        return $this->platform_type;
    }

}
