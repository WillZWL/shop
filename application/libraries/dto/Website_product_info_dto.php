<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Website_product_info_dto extends Base_dto
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
    private $website_status_long_text;
    private $website_status_short_text;
    private $contents_original;
    private $keywords_original;
    private $detail_desc_original;
    private $feature_original;
    private $spec_original;
    private $apply_enhanced_listing;
    private $enhanced_listing;
    private $lang_restricted;

    public function __construct()
    {
        parent::__construct();
    }

    public function get_sku()
    {
        return $this->sku;
    }

    public function set_sku($value)
    {
        $this->sku = $value;
    }

    public function get_lang_id()
    {
        return $this->lang_id;
    }

    public function set_lang_id($value)
    {
        $this->lang_id = $value;
    }

    public function get_prod_name()
    {
        return $this->prod_name;
    }

    public function set_prod_name($value)
    {
        $this->prod_name = $value;
    }

    public function get_prod_name_original()
    {
        return $this->prod_name_original;
    }

    public function set_prod_name_original($value)
    {
        $this->prod_name_original = $value;
    }

    public function get_youtube_id()
    {
        return $this->youtube_id;
    }

    public function set_youtube_id($value)
    {
        $this->youtube_id = $value;
    }

    public function get_short_desc()
    {
        return $this->short_desc;
    }

    public function set_short_desc($value)
    {
        $this->short_desc = $value;
    }

    public function get_detail_desc()
    {
        return $this->detail_desc;
    }

    public function set_detail_desc($value)
    {
        $this->detail_desc = $value;
    }

    public function get_extra_info()
    {
        return $this->extra_info;
    }

    public function set_extra_info($value)
    {
        $this->extra_info = $value;
    }

    public function get_contents()
    {
        return $this->contents;
    }

    public function set_contents($value)
    {
        $this->contents = $value;
    }

    public function get_feature()
    {
        return $this->feature;
    }

    public function set_feature($value)
    {
        $this->feature = $value;
    }

    public function get_specification()
    {
        return $this->specification;
    }

    public function set_specification($value)
    {
        $this->specification = $value;
    }

    public function get_requirement()
    {
        return $this->requirement;
    }

    public function set_requirement($value)
    {
        $this->requirement = $value;
    }

    public function get_instruction()
    {
        return $this->instruction;
    }

    public function set_instruction($value)
    {
        $this->instruction = $value;
    }

    public function get_cat_id()
    {
        return $this->cat_id;
    }

    public function set_cat_id($value)
    {
        $this->cat_id = $value;
    }

    public function get_cat_name()
    {
        return $this->cat_name;
    }

    public function set_cat_name($value)
    {
        $this->cat_name = $value;
    }

    public function get_sub_cat_id()
    {
        return $this->sub_cat_id;
    }

    public function set_sub_cat_id($value)
    {
        $this->sub_cat_id = $value;
    }

    public function get_sub_cat_name()
    {
        return $this->sub_cat_name;
    }

    public function set_sub_cat_name($value)
    {
        $this->sub_cat_name = $value;
    }

    public function get_sub_sub_cat_id()
    {
        return $this->sub_sub_cat_id;
    }

    public function set_sub_sub_cat_id($value)
    {
        $this->sub_sub_cat_id = $value;
    }

    public function get_sub_sub_cat_name()
    {
        return $this->sub_sub_cat_name;
    }

    public function set_sub_sub_cat_name($value)
    {
        $this->sub_sub_cat_name = $value;
    }

    public function get_brand_name()
    {
        return $this->brand_name;
    }

    public function get_website_status_short_text()
    {
        return $this->website_status_short_text;
    }

    public function set_website_status_short_text($value)
    {
        $this->website_status_short_text = $value;
    }

    public function get_website_status_long_text()
    {
        return $this->website_status_long_text;
    }

    public function set_website_status_long_text($value)
    {
        $this->website_status_long_text = $value;
    }

    public function get_expected_delivery_date()
    {
        return $this->expected_delivery_date;
    }

    public function set_expected_delivery_date($value)
    {
        $this->expected_delivery_date = $value;
    }

    public function get_delivery_scenarioid()
    {
        return $this->delivery_scenarioid;
    }

    public function set_delivery_scenarioid($value)
    {
        $this->delivery_scenarioid = $value;
    }

    public function get_contents_original()
    {
        return $this->contents_original;
    }

    public function set_contents_original($value)
    {
        $this->contents_original = $value;
    }

    public function get_keywords_original()
    {
        return $this->keywords_original;
    }

    public function set_keywords_original($value)
    {
        $this->keywords_original = $value;
    }

    public function get_detail_desc_original()
    {
        return $this->detail_desc_original;
    }

    public function set_detail_desc_original($value)
    {
        $this->detail_desc_original = $value;
    }

    public function get_feature_original()
    {
        return $this->feature_original;
    }

    public function set_feature_original($value)
    {
        $this->feature_original = $value;
    }

    public function get_spec_original()
    {
        return $this->spec_original;
    }

    public function set_spec_original($value)
    {
        $this->spec_original = $value;
    }

    public function get_apply_enhanced_listing()
    {
        return $this->apply_enhanced_listing;
    }

    public function set_apply_enhanced_listing($value)
    {
        $this->apply_enhanced_listing = $value;
        return $this;
    }

    public function get_enhanced_listing()
    {
        return $this->enhanced_listing;
    }

    public function set_enhanced_listing($value)
    {
        $this->enhanced_listing = $value;
        return $this;
    }

    public function get_lang_restricted()
    {
        return $this->lang_restricted;
    }

    public function set_lang_restricted($value)
    {
        $this->lang_restricted = $value;
        return $this;
    }
}

/* End of file website_product_info_dto.php */
/* Location: ./system/application/libraries/dto/website_product_info_dto.php */