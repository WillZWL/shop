<?php
class ExtCategoryMappingVo extends \BaseVo
{
    private $id;
    private $ext_party;
    private $category_id;
    private $ext_id;
    private $country_id;
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

    public function setExtParty($ext_party)
    {
        if ($ext_party !== null) {
            $this->ext_party = $ext_party;
        }
    }

    public function getExtParty()
    {
        return $this->ext_party;
    }

    public function setCategoryId($category_id)
    {
        if ($category_id !== null) {
            $this->category_id = $category_id;
        }
    }

    public function getCategoryId()
    {
        return $this->category_id;
    }

    public function setExtId($ext_id)
    {
        if ($ext_id !== null) {
            $this->ext_id = $ext_id;
        }
    }

    public function getExtId()
    {
        return $this->ext_id;
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
