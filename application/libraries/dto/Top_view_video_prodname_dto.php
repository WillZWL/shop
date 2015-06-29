<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Top_view_video_prodname_dto extends Base_dto
{
    private $catid;
    private $rank;
    private $ref_id;
    private $video_type;
    private $src;
    private $sku;
    private $name;
    private $mode;
    private $image;
    private $image_file;
    private $quantity;
    private $website_quantity;
    private $price;
    private $website_status;

    public function get_catid()
    {
        return $this->catid;
    }

    public function set_catid($value)
    {
        $this->catid = $value;
    }

    public function get_rank()
    {
        return $this->rank;
    }

    public function set_rank($value)
    {
        $this->rank = $value;
    }

    public function get_ref_id()
    {
        return $this->ref_id;
    }

    public function set_ref_id($value)
    {
        $this->ref_id = $value;
    }

    public function get_video_type()
    {
        return $this->video_type;
    }

    public function set_video_type($value)
    {
        $this->video_type = $value;
    }

    public function get_src()
    {
        return $this->src;
    }

    public function set_src($value)
    {
        $this->src = $value;
    }

    public function get_sku()
    {
        return $this->sku;
    }

    public function set_sku($value)
    {
        $this->sku = $value;
    }

    public function get_name()
    {
        return $this->name;
    }

    public function set_name($value)
    {
        $this->name = $value;
    }

    public function get_mode()
    {
        return $this->mode;
    }

    public function set_mode($value)
    {
        $this->mode = $value;
    }

    public function get_image()
    {
        return $this->image;
    }

    public function set_image($value)
    {
        $this->image = $value;
    }

    public function get_image_file()
    {
        return $this->image_file;
    }

    public function set_image_file($value)
    {
        $this->image_file = $value;
    }

    public function get_quantity()
    {
        return $this->quantity;
    }

    public function set_quantity($value)
    {
        $this->quantity = $value;
    }

    public function get_website_quantity()
    {
        return $this->website_quantity;
    }

    public function set_website_quantity($value)
    {
        $this->website_quantity = $value;
    }

    public function get_price()
    {
        return $this->price;
    }

    public function set_price($value)
    {
        $this->price = $value;
    }

    public function get_website_status()
    {
        return $this->website_status;
    }

    public function set_website_status($value)
    {
        $this->website_status = $value;
    }



}

?>