<?php
class ProductContentExtendVo extends \BaseVo
{
    private $id;
    private $prod_sku;
    private $lang_id;
    private $feature;
    private $feature_original = '0';
    private $specification;
    private $spec_original = '0';
    private $requirement;
    private $instruction;
    private $apply_enhanced_listing = 'N';
    private $enhanced_listing;
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

    public function setProdSku($prod_sku)
    {
        if ($prod_sku != null) {
            $this->prod_sku = $prod_sku;
        }
    }

    public function getProdSku()
    {
        return $this->prod_sku;
    }

    public function setLangId($lang_id)
    {
        if ($lang_id != null) {
            $this->lang_id = $lang_id;
        }
    }

    public function getLangId()
    {
        return $this->lang_id;
    }

    public function setFeature($feature)
    {
        if ($feature != null) {
            $this->feature = $feature;
        }
    }

    public function getFeature()
    {
        return $this->feature;
    }

    public function setFeatureOriginal($feature_original)
    {
        if ($feature_original != null) {
            $this->feature_original = $feature_original;
        }
    }

    public function getFeatureOriginal()
    {
        return $this->feature_original;
    }

    public function setSpecification($specification)
    {
        if ($specification != null) {
            $this->specification = $specification;
        }
    }

    public function getSpecification()
    {
        return $this->specification;
    }

    public function setSpecOriginal($spec_original)
    {
        if ($spec_original != null) {
            $this->spec_original = $spec_original;
        }
    }

    public function getSpecOriginal()
    {
        return $this->spec_original;
    }

    public function setRequirement($requirement)
    {
        if ($requirement != null) {
            $this->requirement = $requirement;
        }
    }

    public function getRequirement()
    {
        return $this->requirement;
    }

    public function setInstruction($instruction)
    {
        if ($instruction != null) {
            $this->instruction = $instruction;
        }
    }

    public function getInstruction()
    {
        return $this->instruction;
    }

    public function setApplyEnhancedListing($apply_enhanced_listing)
    {
        if ($apply_enhanced_listing != null) {
            $this->apply_enhanced_listing = $apply_enhanced_listing;
        }
    }

    public function getApplyEnhancedListing()
    {
        return $this->apply_enhanced_listing;
    }

    public function setEnhancedListing($enhanced_listing)
    {
        if ($enhanced_listing != null) {
            $this->enhanced_listing = $enhanced_listing;
        }
    }

    public function getEnhancedListing()
    {
        return $this->enhanced_listing;
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
