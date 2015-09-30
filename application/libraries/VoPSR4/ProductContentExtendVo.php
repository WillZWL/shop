<?php
class ProductContentExtendVo extends \BaseVo
{
    private $id;
    private $prod_sku;
    private $lang_id;
    private $feature;
    private $feature_original;
    private $specification;
    private $spec_original;
    private $requirement;
    private $instruction;
    private $apply_enhanced_listing = 'N';
    private $enhanced_listing;
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

    public function setProdSku($prod_sku)
    {
        $this->prod_sku = $prod_sku;
    }

    public function getProdSku()
    {
        return $this->prod_sku;
    }

    public function setLangId($lang_id)
    {
        $this->lang_id = $lang_id;
    }

    public function getLangId()
    {
        return $this->lang_id;
    }

    public function setFeature($feature)
    {
        $this->feature = $feature;
    }

    public function getFeature()
    {
        return $this->feature;
    }

    public function setFeatureOriginal($feature_original)
    {
        $this->feature_original = $feature_original;
    }

    public function getFeatureOriginal()
    {
        return $this->feature_original;
    }

    public function setSpecification($specification)
    {
        $this->specification = $specification;
    }

    public function getSpecification()
    {
        return $this->specification;
    }

    public function setSpecOriginal($spec_original)
    {
        $this->spec_original = $spec_original;
    }

    public function getSpecOriginal()
    {
        return $this->spec_original;
    }

    public function setRequirement($requirement)
    {
        $this->requirement = $requirement;
    }

    public function getRequirement()
    {
        return $this->requirement;
    }

    public function setInstruction($instruction)
    {
        $this->instruction = $instruction;
    }

    public function getInstruction()
    {
        return $this->instruction;
    }

    public function setApplyEnhancedListing($apply_enhanced_listing)
    {
        $this->apply_enhanced_listing = $apply_enhanced_listing;
    }

    public function getApplyEnhancedListing()
    {
        return $this->apply_enhanced_listing;
    }

    public function setEnhancedListing($enhanced_listing)
    {
        $this->enhanced_listing = $enhanced_listing;
    }

    public function getEnhancedListing()
    {
        return $this->enhanced_listing;
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
