<?php
class CountryRmaFcDto
{

    //class variable
    private $id;
    private $country_id;
    private $id_3_digit;
    private $name;
    private $description;
    private $status;
    private $currency_id;
    private $language_id;
    private $fc_id;
    private $rma_fc;
    private $allow_sell = '0';
    private $create_on = '0000-00-00 00:00:00';
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;

    //instance method
    public function getId()
    {
        return $this->id;
    }

    public function setId($value)
    {
        $this->id = $value;
        return $this;
    }

    public function getCountryId()
    {
        return $this->country_id;
    }

    public function setCountryId($value)
    {
        $this->country_id = $value;
        return $this;
    }

    public function getId3Digit()
    {
        return $this->id_3_digit;
    }

    public function setId3Digit($value)
    {
        $this->id_3_digit = $value;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($value)
    {
        $this->name = $value;
        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($value)
    {
        $this->description = $value;
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($value)
    {
        $this->status = $value;
        return $this;
    }

    public function getCurrencyId()
    {
        return $this->currency_id;
    }

    public function setCurrencyId($value)
    {
        $this->currency_id = $value;
        return $this;
    }

    public function getLanguageId()
    {
        return $this->language_id;
    }

    public function setLanguageId($value)
    {
        $this->language_id = $value;
        return $this;
    }

    public function getFcId()
    {
        return $this->fc_id;
    }

    public function setFcId($value)
    {
        $this->fc_id = $value;
        return $this;
    }

    public function getAllowSell()
    {
        return $this->allow_sell;
    }

    public function setAllowSell($value)
    {
        $this->allow_sell = $value;
        return $this;
    }

    public function getCreateOn()
    {
        return $this->create_on;
    }

    public function setCreateOn($value)
    {
        $this->create_on = $value;
        return $this;
    }

    public function getCreateAt()
    {
        return $this->create_at;
    }

    public function setCreateAt($value)
    {
        $this->create_at = $value;
        return $this;
    }

    public function getCreateBy()
    {
        return $this->create_by;
    }

    public function setCreateBy($value)
    {
        $this->create_by = $value;
        return $this;
    }

    public function getModifyOn()
    {
        return $this->modify_on;
    }

    public function setModifyOn($value)
    {
        $this->modify_on = $value;
        return $this;
    }

    public function getModifyAt()
    {
        return $this->modify_at;
    }

    public function setModifyAt($value)
    {
        $this->modify_at = $value;
        return $this;
    }

    public function getModifyBy()
    {
        return $this->modify_by;
    }

    public function setModifyBy($value)
    {
        $this->modify_by = $value;
        return $this;
    }

    public function getRmaFc()
    {
        return $this->rma_fc;
    }

    public function setRmaFc($value)
    {
        $this->rma_fc = $value;
    }
}
