<?php
class CountryVo extends \BaseVo
{
    private $id;
    private $country_id;
    private $id_3_digit;
    private $name;
    private $description;
    private $status;
    private $currency_id;
    private $language_id;
    private $fc_id;
    private $allow_sell;
    private $url_enable;
    private $standalone;
    private $create_on = '0000-00-00 00:00:00';
    private $create_at = '2130706433';
    private $create_by = 'system';
    private $modify_on = 'CURRENT_TIMESTAMP';
    private $modify_at = '2130706433';
    private $modify_by = 'system';

    private $primary_key = ['id'];
    private $increment_field = 'id';

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setCountryId($country_id)
    {
        $this->country_id = $country_id;
    }

    public function getCountryId()
    {
        return $this->country_id;
    }

    public function setId3Digit($id_3_digit)
    {
        $this->id_3_digit = $id_3_digit;
    }

    public function getId3Digit()
    {
        return $this->id_3_digit;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setCurrencyId($currency_id)
    {
        $this->currency_id = $currency_id;
    }

    public function getCurrencyId()
    {
        return $this->currency_id;
    }

    public function setLanguageId($language_id)
    {
        $this->language_id = $language_id;
    }

    public function getLanguageId()
    {
        return $this->language_id;
    }

    public function setFcId($fc_id)
    {
        $this->fc_id = $fc_id;
    }

    public function getFcId()
    {
        return $this->fc_id;
    }

    public function setAllowSell($allow_sell)
    {
        $this->allow_sell = $allow_sell;
    }

    public function getAllowSell()
    {
        return $this->allow_sell;
    }

    public function setUrlEnable($url_enable)
    {
        $this->url_enable = $url_enable;
    }

    public function getUrlEnable()
    {
        return $this->url_enable;
    }

    public function setStandalone($standalone)
    {
        $this->standalone = $standalone;
    }

    public function getStandalone()
    {
        return $this->standalone;
    }

    public function setCreateOn($create_on)
    {
        $this->create_on = $create_on;
    }

    public function getCreateOn()
    {
        return $this->create_on;
    }

    public function setCreateAt($create_at)
    {
        $this->create_at = $create_at;
    }

    public function getCreateAt()
    {
        return $this->create_at;
    }

    public function setCreateBy($create_by)
    {
        $this->create_by = $create_by;
    }

    public function getCreateBy()
    {
        return $this->create_by;
    }

    public function setModifyOn($modify_on)
    {
        $this->modify_on = $modify_on;
    }

    public function getModifyOn()
    {
        return $this->modify_on;
    }

    public function setModifyAt($modify_at)
    {
        $this->modify_at = $modify_at;
    }

    public function getModifyAt()
    {
        return $this->modify_at;
    }

    public function setModifyBy($modify_by)
    {
        $this->modify_by = $modify_by;
    }

    public function getModifyBy()
    {
        return $this->modify_by;
    }

    public function getPrimaryKey()
    {
        return $this->primary_key;
    }

    public function getIncrementField()
    {
        return $this->increment_field;
    }
}
