<?php
class CountryVo extends \BaseVo
{
    private $id;
    private $country_id;
    private $id_3_digit = '';
    private $name = '';
    private $description = '';
    private $status;
    private $currency_id;
    private $language_id = '';
    private $fc_id = '';
    private $allow_sell = '0';
    private $url_enable = '0';
    private $standalone = '0';
    private $create_on = '0000-00-00 00:00:00';
    private $create_at = '2130706433';
    private $create_by = 'system';
    private $modify_on = '';
    private $modify_at = '2130706433';
    private $modify_by = 'system';

    private $primary_key = ['id'];
    private $increment_field = 'id';

    public function setId($id)
    {
        if ($id != null) {
            $this->id = $id;
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function setCountryId($country_id)
    {
        if ($country_id != null) {
            $this->country_id = $country_id;
        }
    }

    public function getCountryId()
    {
        return $this->country_id;
    }

    public function setId3Digit($id_3_digit)
    {
        if ($id_3_digit != null) {
            $this->id_3_digit = $id_3_digit;
        }
    }

    public function getId3Digit()
    {
        return $this->id_3_digit;
    }

    public function setName($name)
    {
        if ($name != null) {
            $this->name = $name;
        }
    }

    public function getName()
    {
        return $this->name;
    }

    public function setDescription($description)
    {
        if ($description != null) {
            $this->description = $description;
        }
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setStatus($status)
    {
        if ($status != null) {
            $this->status = $status;
        }
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setCurrencyId($currency_id)
    {
        if ($currency_id != null) {
            $this->currency_id = $currency_id;
        }
    }

    public function getCurrencyId()
    {
        return $this->currency_id;
    }

    public function setLanguageId($language_id)
    {
        if ($language_id != null) {
            $this->language_id = $language_id;
        }
    }

    public function getLanguageId()
    {
        return $this->language_id;
    }

    public function setFcId($fc_id)
    {
        if ($fc_id != null) {
            $this->fc_id = $fc_id;
        }
    }

    public function getFcId()
    {
        return $this->fc_id;
    }

    public function setAllowSell($allow_sell)
    {
        if ($allow_sell != null) {
            $this->allow_sell = $allow_sell;
        }
    }

    public function getAllowSell()
    {
        return $this->allow_sell;
    }

    public function setUrlEnable($url_enable)
    {
        if ($url_enable != null) {
            $this->url_enable = $url_enable;
        }
    }

    public function getUrlEnable()
    {
        return $this->url_enable;
    }

    public function setStandalone($standalone)
    {
        if ($standalone != null) {
            $this->standalone = $standalone;
        }
    }

    public function getStandalone()
    {
        return $this->standalone;
    }

    public function setCreateOn($create_on)
    {
        if ($create_on != null) {
            $this->create_on = $create_on;
        }
    }

    public function getCreateOn()
    {
        return $this->create_on;
    }

    public function setCreateAt($create_at)
    {
        if ($create_at != null) {
            $this->create_at = $create_at;
        }
    }

    public function getCreateAt()
    {
        return $this->create_at;
    }

    public function setCreateBy($create_by)
    {
        if ($create_by != null) {
            $this->create_by = $create_by;
        }
    }

    public function getCreateBy()
    {
        return $this->create_by;
    }

    public function setModifyOn($modify_on)
    {
        if ($modify_on != null) {
            $this->modify_on = $modify_on;
        }
    }

    public function getModifyOn()
    {
        return $this->modify_on;
    }

    public function setModifyAt($modify_at)
    {
        if ($modify_at != null) {
            $this->modify_at = $modify_at;
        }
    }

    public function getModifyAt()
    {
        return $this->modify_at;
    }

    public function setModifyBy($modify_by)
    {
        if ($modify_by != null) {
            $this->modify_by = $modify_by;
        }
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
