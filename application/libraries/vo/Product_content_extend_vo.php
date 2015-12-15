<?php
include_once 'Base_vo.php';

class Product_content_extend_vo extends Base_vo
{

    //class variable
    private $prod_sku;
    private $lang_id;
    private $feature;
    private $feature_original;
    private $specification;
    private $spec_original;
    private $requirement;
    private $instruction;
    private $apply_enhanced_listing;
    private $enhanced_listing;
    private $stop_sync;
    private $create_on = '0000-00-00 00:00:00';
    private $create_at = '127.0.0.1';
    private $create_by;
    private $modify_on;
    private $modify_at = '127.0.0.1';
    private $modify_by;

    //primary key
    private $primary_key = array("prod_sku", "lang_id");

    //auo increment
    private $increment_field = "";

    //instance method
    public function get_prod_sku()
    {
        return $this->prod_sku;
    }

    public function set_prod_sku($value)
    {
        $this->prod_sku = $value;
        return $this;
    }

    public function get_lang_id()
    {
        return $this->lang_id;
    }

    public function set_lang_id($value)
    {
        $this->lang_id = $value;
        return $this;
    }

    public function get_feature()
    {
        return $this->feature;
    }

    public function set_feature($value)
    {
        $this->feature = $value;
        return $this;
    }

    public function get_specification()
    {
        return $this->specification;
    }

    public function set_specification($value)
    {
        $this->specification = $value;
        return $this;
    }

    public function get_requirement()
    {
        return $this->requirement;
    }

    public function set_requirement($value)
    {
        $this->requirement = $value;
        return $this;
    }

    public function get_instruction()
    {
        return $this->instruction;
    }

    public function set_instruction($value)
    {
        $this->instruction = $value;
        return $this;
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

    public function get_create_on()
    {
        return $this->create_on;
    }

    public function set_create_on($value)
    {
        $this->create_on = $value;
        return $this;
    }

    public function get_create_at()
    {
        return $this->create_at;
    }

    public function set_create_at($value)
    {
        $this->create_at = $value;
        return $this;
    }

    public function get_create_by()
    {
        return $this->create_by;
    }

    public function set_create_by($value)
    {
        $this->create_by = $value;
        return $this;
    }

    public function get_modify_on()
    {
        return $this->modify_on;
    }

    public function set_modify_on($value)
    {
        $this->modify_on = $value;
        return $this;
    }

    public function get_modify_at()
    {
        return $this->modify_at;
    }

    public function set_modify_at($value)
    {
        $this->modify_at = $value;
        return $this;
    }

    public function get_modify_by()
    {
        return $this->modify_by;
    }

    public function set_modify_by($value)
    {
        $this->modify_by = $value;
        return $this;
    }

    public function _get_primary_key()
    {
        return $this->primary_key;
    }

    public function _get_increment_field()
    {
        return $this->increment_field;
    }

    public function get_spec_original()
    {
        return $this->spec_original;
    }

    public function set_spec_original($value)
    {
        $this->spec_original = $value;
        return $this;
    }

    public function get_feature_original()
    {
        return $this->feature_original;
    }

    public function set_feature_original($value)
    {
        $this->feature_original = $value;
        return $this;
    }

    public function get_stop_sync()
    {
        return $this->stop_sync;
    }

    public function set_stop_sync($value)
    {
        $this->stop_sync = $value;
        return $this;
    }
}

?>