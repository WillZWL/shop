<?php
class WebsiteProductInfoDto
{
    private $sku;
    private $cat_id;
    private $cat_name;
    private $sub_cat_id;
    private $sub_cat_name;
    private $sub_sub_cat_id;
    private $sub_sub_cat_name;
    private $brand_id;
    private $brand_name;
    private $lang_id;
    private $prod_name;
    private $prod_name_original;
    private $youtube_id;
    private $short_desc;
    private $detail_desc;
    private $extra_info;
    private $contents;
    private $feature;
    private $specification;
    private $requirement;
    private $instruction;
    private $expected_delivery_date;
    private $delivery_scenarioid;
    private $contents_original;
    private $keywords_original;
    private $detail_desc_original;
    private $feature_original;
    private $spec_original;
    private $apply_enhanced_listing;
    private $enhanced_listing;
    private $lang_restricted;
    private $image;

    public function getSku()
    {
        return $this->sku;
    }

    public function setSku($value)
    {
        $this->sku = $value;
    }

    public function getLangId()
    {
        return $this->lang_id;
    }

    public function setLangId($value)
    {
        $this->lang_id = $value;
    }

    public function getProdName()
    {
        return $this->prod_name;
    }

    public function setProdName($value)
    {
        $this->prod_name = $value;
    }

    public function getProdNameOriginal()
    {
        return $this->prod_name_original;
    }

    public function setProdNameOriginal($value)
    {
        $this->prod_name_original = $value;
    }

    public function getYoutubeId()
    {
        return $this->youtube_id;
    }

    public function setYoutubeId($value)
    {
        $this->youtube_id = $value;
    }

    public function getShortDesc()
    {
        return $this->short_desc;
    }

    public function setShortDesc($value)
    {
        $this->short_desc = $value;
    }

    public function getDetailDesc()
    {
        return $this->detail_desc;
    }

    public function setDetailDesc($value)
    {
        $this->detail_desc = $value;
    }

    public function getExtraInfo()
    {
        return $this->extra_info;
    }

    public function setExtraInfo($value)
    {
        $this->extra_info = $value;
    }

    public function getContents()
    {
        return $this->contents;
    }

    public function setContents($value)
    {
        $this->contents = $value;
    }

    public function getFeature()
    {
        return $this->feature;
    }

    public function setFeature($value)
    {
        $this->feature = $value;
    }

    public function getSpecification()
    {
        return $this->specification;
    }

    public function setSpecification($value)
    {
        $this->specification = $value;
    }

    public function getRequirement()
    {
        return $this->requirement;
    }

    public function setRequirement($value)
    {
        $this->requirement = $value;
    }

    public function getInstruction()
    {
        return $this->instruction;
    }

    public function setInstruction($value)
    {
        $this->instruction = $value;
    }

    public function getCatId()
    {
        return $this->cat_id;
    }

    public function setCatId($value)
    {
        $this->cat_id = $value;
    }

    public function getCatName()
    {
        return $this->cat_name;
    }

    public function setCatName($value)
    {
        $this->cat_name = $value;
    }

    public function getSubCatId()
    {
        return $this->sub_cat_id;
    }

    public function setSubCatId($value)
    {
        $this->sub_cat_id = $value;
    }

    public function getSubCatName()
    {
        return $this->sub_cat_name;
    }

    public function setSubCatName($value)
    {
        $this->sub_cat_name = $value;
    }

    public function getSubSubCatId()
    {
        return $this->sub_sub_cat_id;
    }

    public function setSubSubCatId($value)
    {
        $this->sub_sub_cat_id = $value;
    }

    public function getSubSubCatName()
    {
        return $this->sub_sub_cat_name;
    }

    public function setSubSubCatName($value)
    {
        $this->sub_sub_cat_name = $value;
    }

    public function getBrandName()
    {
        return $this->brand_name;
    }

    public function setBrandName($value)
    {
        $this->brand_name = $value;
    }

    public function getExpectedDeliveryDate()
    {
        return $this->expected_delivery_date;
    }

    public function setExpectedDeliveryDate($value)
    {
        $this->expected_delivery_date = $value;
    }

    public function getDeliveryScenarioid()
    {
        return $this->delivery_scenarioid;
    }

    public function setDeliveryScenarioid($value)
    {
        $this->delivery_scenarioid = $value;
    }

    public function getContentsOriginal()
    {
        return $this->contents_original;
    }

    public function setContentsOriginal($value)
    {
        $this->contents_original = $value;
    }

    public function getKeywordsOriginal()
    {
        return $this->keywords_original;
    }

    public function setKeywordsOriginal($value)
    {
        $this->keywords_original = $value;
    }

    public function getDetailDescOriginal()
    {
        return $this->detail_desc_original;
    }

    public function setDetailDescOriginal($value)
    {
        $this->detail_desc_original = $value;
    }

    public function getFeatureOriginal()
    {
        return $this->feature_original;
    }

    public function setFeatureOriginal($value)
    {
        $this->feature_original = $value;
    }

    public function getSpecOriginal()
    {
        return $this->spec_original;
    }

    public function setSpecOriginal($value)
    {
        $this->spec_original = $value;
    }

    public function getApplyEnhancedListing()
    {
        return $this->apply_enhanced_listing;
    }

    public function setApplyEnhancedListing($value)
    {
        $this->apply_enhanced_listing = $value;
    }

    public function getEnhancedListing()
    {
        return $this->enhanced_listing;
    }

    public function setEnhancedListing($value)
    {
        $this->enhanced_listing = $value;
    }

    public function getLangRestricted()
    {
        return $this->lang_restricted;
    }

    public function setLangRestricted($value)
    {
        $this->lang_restricted = $value;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }
}
