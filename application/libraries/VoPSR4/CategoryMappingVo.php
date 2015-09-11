<?php
class CategoryMappingVo extends \BaseVo
{
    private $id;
    private $ext_party;
    private $level;
    private $category_mapping_id;
    private $ext_id;
    private $ext_name;
    private $lang_id;
    private $country_id;
    private $product_name;
    private $status = '1';
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

    public function setExtParty($ext_party)
    {
        $this->ext_party = $ext_party;
    }

    public function getExtParty()
    {
        return $this->ext_party;
    }

    public function setLevel($level)
    {
        $this->level = $level;
    }

    public function getLevel()
    {
        return $this->level;
    }

    public function setCategoryMappingId($category_mapping_id)
    {
        $this->category_mapping_id = $category_mapping_id;
    }

    public function getCategoryMappingId()
    {
        return $this->category_mapping_id;
    }

    public function setExtId($ext_id)
    {
        $this->ext_id = $ext_id;
    }

    public function getExtId()
    {
        return $this->ext_id;
    }

    public function setExtName($ext_name)
    {
        $this->ext_name = $ext_name;
    }

    public function getExtName()
    {
        return $this->ext_name;
    }

    public function setLangId($lang_id)
    {
        $this->lang_id = $lang_id;
    }

    public function getLangId()
    {
        return $this->lang_id;
    }

    public function setCountryId($country_id)
    {
        $this->country_id = $country_id;
    }

    public function getCountryId()
    {
        return $this->country_id;
    }

    public function setProductName($product_name)
    {
        $this->product_name = $product_name;
    }

    public function getProductName()
    {
        return $this->product_name;
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

    public function getIncrementField()
    {
        return $this->increment_field;
    }
}
