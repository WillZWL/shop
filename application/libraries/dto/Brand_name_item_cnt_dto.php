<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Brand_name_item_cnt_dto extends Base_dto {

    private $brand_id;
    private $brand_name;
    private $total;

    public function __construct()
    {
        parent::__construct();
    }

    public function get_brand_id()
    {
        return $this->brand_id;
    }

    public function set_brand_id($value)
    {
        $this->brand_id = $value;
    }

    public function get_brand_name()
    {
        return $this->brand_name;
    }

    public function set_brand_name($value)
    {
        $this->brand_name = $value;
    }

    public function get_total()
    {
        return $this->total;
    }

    public function set_total($value)
    {
        $this->total = $value;
    }
}


/* End of file base_dto.php */
/* Location: ./system/application/libraries/dto/base_dto.php */