<?php
class ProductCustomClassificationVo extends \BaseVo
{
    private $id;
    private $sku;
    private $country_id;
    private $code;
    private $description = '';
    private $duty_pcent = '0.00';


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

    public function setSku($sku)
    {
        if ($sku !== null) {
            $this->sku = $sku;
        }
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function setCountryId($country_id)
    {
        if ($country_id !== null) {
            $this->country_id = $country_id;
        }
    }

    public function getCountryId()
    {
        return $this->country_id;
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

    public function setDutyPcent($duty_pcent)
    {
        if ($duty_pcent !== null) {
            $this->duty_pcent = $duty_pcent;
        }
    }

    public function getDutyPcent()
    {
        return $this->duty_pcent;
    }

}
