<?php
include_once 'Base_vo.php';

class Landpage_video_listing_vo extends Base_vo
{

    //class variable
    private $catid = '0';
    private $sku = '';
    private $platform_id = '';
    private $lang_id;
    private $listing_type;
    private $video_type;
    private $mode = 'M';
    private $rank = '0';
    private $ref_id;
    private $src;
    private $create_on = '0000-00-00 00:00:00';
    private $create_at = '127.0.0.1';
    private $create_by;
    private $modify_on;
    private $modify_at = '127.0.0.1';
    private $modify_by;

    //primary key
    private $primary_key = array("catid", "platform_id", "lang_id", "listing_type", "video_type", "mode", "rank");

    //auo increment
    private $increment_field = "";

    //instance method
    public function get_catid()
    {
        return $this->catid;
    }

    public function set_catid($value)
    {
        $this->catid = $value;
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

    public function get_listing_type()
    {
        return $this->listing_type;
    }

    public function set_listing_type($value)
    {
        $this->listing_type = $value;
        return $this;
    }

    public function get_video_type()
    {
        return $this->video_type;
    }

    public function set_video_type($value)
    {
        $this->video_type = $value;
        return $this;
    }

    public function get_mode()
    {
        return $this->mode;
    }

    public function set_mode($value)
    {
        $this->mode = $value;
        return $this;
    }

    public function get_rank()
    {
        return $this->rank;
    }

    public function set_rank($value)
    {
        $this->rank = $value;
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

    public function get_src()
    {
        return $this->src;
    }

    public function set_src($value)
    {
        $this->src = $value;
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

}
?>