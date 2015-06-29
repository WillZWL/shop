<?php
include_once 'Base_dto.php';

class Listed_video_list_dto extends Base_dto
{
    //class variable
    private $id;
    private $cat_id;
    private $sub_cat_id;
    private $sub_sub_cat_id;
    private $sku;
    private $platform_id;
    private $lang_id;
    private $type;
    private $src;
    private $ref_id;
    private $description;
    private $view_count;
    private $status;
    private $create_on;
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;

    //instance method
    public function get_id()
    {
        return $this->id;
    }

    public function set_id($value)
    {
        $this->id = $value;
        return $this;
    }

    public function get_cat_id()
    {
        return $this->cat_id;
    }

    public function set_cat_id($value)
    {
        $this->cat_id = $value;
        return $this;
    }

    public function get_sub_cat_id()
    {
        return $this->sub_cat_id;
    }

    public function set_sub_cat_id($value)
    {
        $this->sub_cat_id = $value;
        return $this;
    }

    public function get_sub_sub_cat_id()
    {
        return $this->sub_sub_cat_id;
    }

    public function set_sub_sub_cat_id($value)
    {
        $this->sub_sub_cat_id = $value;
        return $this;
    }

    public function get_sku()
    {
        return $this->sku;
    }

    public function set_sku($value)
    {
        $this->sku = $value;
        return $this;
    }

    public function get_platform_id()
    {
        return $this->platform_id;
    }

    public function set_platform_id($value)
    {
        $this->platform_id = $value;
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

    public function get_type()
    {
        return $this->type;
    }

    public function set_type($value)
    {
        $this->type = $value;
        return $this;
    }

    public function get_src()
    {
        return $this->src;
    }

    public function set_src($value)
    {
        $this->src = $value;
        return $this;
    }

    public function get_ref_id()
    {
        return $this->ref_id;
    }

    public function set_ref_id($value)
    {
        $this->ref_id = $value;
        return $this;
    }

    public function get_description()
    {
        return $this->description;
    }

    public function set_description($value)
    {
        $this->description = $value;
        return $this;
    }

    public function get_view_count()
    {
        return $this->view_count;
    }

    public function set_view_count($value)
    {
        $this->view_count = $value;
        return $this;
    }

    public function get_status()
    {
        return $this->status;
    }

    public function set_status($value)
    {
        $this->status = $value;
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
}

/* End of file listed_video_list_dto.php */
/* Location: ./system/application/libraries/dto/listed_video_list_dto.php */