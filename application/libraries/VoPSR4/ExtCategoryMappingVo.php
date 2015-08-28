<?php
class ExtCategoryMappingVo extends \BaseVo
{
    private $id;
    private $ext_party;
    private $category_id;
    private $ext_id;
    private $country_id;
    private $status = '1';
    private $create_on = '0000-00-00 00:00:00';
    private $create_at;
    private $create_by;
    private $modify_on = 'CURRENT_TIMESTAMP';
    private $modify_at;
    private $modify_by;

    private $primary_key = ['id'];

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setExtParty($ext_party)
    {
        $this->ext_party = $ext_party;
    }

    public function getExtParty()
    {
        return $this->ext_party;
    }

    public function setCategoryId($category_id)
    {
        $this->category_id = $category_id;
    }

    public function getCategoryId()
    {
        return $this->category_id;
    }

    public function setExtId($ext_id)
    {
        $this->ext_id = $ext_id;
    }

    public function getExtId()
    {
        return $this->ext_id;
    }

    public function setCountryId($country_id)
    {
        $this->country_id = $country_id;
    }

    public function getCountryId()
    {
        return $this->country_id;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
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
}
