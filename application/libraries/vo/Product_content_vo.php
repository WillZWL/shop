<?php
include_once 'Base_vo.php';

class Product_content_vo extends Base_vo
{

    //class variable
    private $prod_sku;
    private $lang_id;
    private $prod_name;
    private $prod_name_original;
    private $short_desc;
    private $contents;
    private $contents_original;
    private $series;
    private $keywords;
    private $keywords_original;
    private $model_1;
    private $model_2;
    private $model_3;
    private $model_4;
    private $model_5;
    private $detail_desc;
    private $detail_desc_original;
    private $extra_info;
    private $website_status_long_text;
    private $website_status_short_text;
    private $youtube_id_1;
    private $youtube_id_2;
    private $youtube_caption_1;
    private $youtube_caption_2;
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

    public function get_prod_name()
    {
        return $this->prod_name;
    }

    public function set_prod_name($value)
    {
        $this->prod_name = $value;
        return $this;
    }

    public function get_prod_name_original()
    {
        return $this->prod_name_original;
    }

    public function set_prod_name_original($value)
    {
        $this->prod_name_original = $value;
    }

    public function get_short_desc()
    {
        return $this->short_desc;
    }

    public function set_short_desc($value)
    {
        $this->short_desc = $value;
        return $this;
    }

    public function get_contents()
    {
        return $this->contents;
    }

    public function set_contents($value)
    {
        $this->contents = $value;
        return $this;
    }

    public function get_series()
    {
        return $this->series;
    }

    public function set_series($value)
    {
        $this->series = $value;
        return $this;
    }

    public function get_keywords()
    {
        return $this->keywords;
    }

    public function set_keywords($value)
    {
        $this->keywords = $value;
        return $this;
    }

    public function get_model_1()
    {
        return $this->model_1;
    }

    public function set_model_1($value = '')
    {
        $this->model_1 = $value;
        return $this;
    }

    public function get_model_2()
    {
        return $this->model_2;
    }

    public function set_model_2($value)
    {
        $this->model_2 = $value;
        return $this;
    }

    public function get_model_3()
    {
        return $this->model_3;
    }

    public function set_model_3($value)
    {
        $this->model_3 = $value;
        return $this;
    }

    public function get_model_4()
    {
        return $this->model_4;
    }

    public function set_model_4($value)
    {
        $this->model_4 = $value;
        return $this;
    }

    public function get_model_5()
    {
        return $this->model_5;
    }

    public function set_model_5($value)
    {
        $this->model_5 = $value;
        return $this;
    }

    public function get_detail_desc()
    {
        return $this->detail_desc;
    }

    public function set_detail_desc($value)
    {
        $this->detail_desc = $value;
        return $this;
    }

    public function get_extra_info()
    {
        return $this->extra_info;
    }

    public function set_extra_info($value)
    {
        $this->extra_info = $value;
        return $this;
    }

    public function get_website_status_long_text()
    {
        return $this->website_status_long_text;
    }

    public function set_website_status_long_text($value)
    {
        $this->website_status_long_text = $value;
        return $this;
    }

    public function get_website_status_short_text()
    {
        return $this->website_status_short_text;
    }

    public function set_website_status_short_text($value)
    {
        $this->website_status_short_text = $value;
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

    public function get_youtube_id_1()
    {
        return $this->youtube_id_1;
    }

    public function set_youtube_id_1($value)
    {
        $this->youtube_id_1 = $value;
        return $this;
    }

    public function get_youtube_id_2()
    {
        return $this->youtube_id_2;
    }

    public function set_youtube_id_2($value)
    {
        $this->youtube_id_2 = $value;
        return $this;
    }

    public function get_youtube_caption_1()
    {
        return $this->youtube_caption_1;
    }

    public function set_youtube_caption_1($value)
    {
        $this->youtube_caption_1 = $value;
        return $this;
    }

    public function get_youtube_caption_2()
    {
        return $this->youtube_caption_2;
    }

    public function set_youtube_caption_2($value)
    {
        $this->youtube_caption_2 = $value;
        return $this;
    }

    public function get_detail_desc_original()
    {
        return $this->detail_desc_original;
    }

    public function set_detail_desc_original($value)
    {
        $this->detail_desc_original = $value;
        return $this;
    }

    public function get_keywords_original()
    {
        return $this->keywords_original;
    }

    public function set_keywords_original($value)
    {
        $this->keywords_original = $value;
        return $this;
    }

    public function get_contents_original()
    {
        return $this->contents_original;
    }

    public function set_contents_original($value)
    {
        $this->contents_original = $value;
        return $this;
    }



}
?>