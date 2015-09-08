<?php
class ProductCustomClassDto
{
    private $sku;
    private $prod_name;
    private $sub_cat_id;
    private $sub_cat_name;
    private $country_id;
    private $code;
    private $description;
    private $duty_pcent;
    private $create_on;
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;

    public function getSku()
    {
        return $this->sku;
    }

    public function setSku($value)
    {
        $this->sku = $value;
    }

    public function getProdName()
    {
        return $this->prod_name;
    }

    public function setProdName($value)
    {
        $this->prod_name = $value;
    }

    public function getCatId()
    {
        return $this->cat_id;
    }

    public function setCatId($value)
    {
        $this->cat_id = $value;
    }

    public function getSubCatName()
    {
        return $this->sub_cat_name;
    }

    public function setSubCatName($value)
    {
        $this->sub_cat_name = $value;
    }

    public function getCountryId()
    {
        return $this->country_id;
    }

    public function setCountryId($value)
    {
        $this->country_id = $value;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($value)
    {
        $this->code = $value;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($value)
    {
        $this->description = $value;
    }

    public function getDutyPcent()
    {
        return $this->duty_pcent;
    }

    public function setDutyPcent($value)
    {
        $this->duty_pcent = $value;
    }

    public function getCreateOn()
    {
        return $this->create_on;
    }

    public function setCreateOn($value)
    {
        $this->create_on = $value;
    }

    public function getCreateAt()
    {
        return $this->create_at;
    }

    public function setCreateAt($value)
    {
        $this->create_at = $value;
    }

    public function getCreateBy()
    {
        return $this->create_by;
    }

    public function setCreateBy($value)
    {
        $this->create_by = $value;
    }

    public function getModifyOn()
    {
        return $this->modify_on;
    }

    public function setModifyOn($value)
    {
        $this->modify_on = $value;
    }

    public function getModifyAt()
    {
        return $this->modify_at;
    }

    public function setModifyAt($value)
    {
        $this->modify_at = $value;
    }

    public function getModifyBy()
    {
        return $this->modify_by;
    }

    public function setModifyBy($value)
    {
        $this->modify_by = $value;
    }

}


