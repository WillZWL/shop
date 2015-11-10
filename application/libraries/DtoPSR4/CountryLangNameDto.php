<?php
class CountryLangNameDto
{
    private $id;
    private $country_id;
    private $name;
    private $lang_name;
    private $fc_id;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getCountryId()
    {
        return $this->country_id;
    }

    public function setCountryId($country_id)
    {
        $this->country_id = $country_id;
    }

    public function getFcId()
    {
        return $this->fc_id;
    }

    public function setFcId($fc_id)
    {
        $this->fc_id = $fc_id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getLangName()
    {
        return $this->lang_name;
    }

    public function setLangName($lang_name)
    {
        $this->lang_name = $lang_name;
    }
}
