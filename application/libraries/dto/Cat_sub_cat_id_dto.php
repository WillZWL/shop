<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Cat_sub_cat_id_dto extends Base_dto {

    private $id;
    private $name;

    public function __construct()
    {
        parent::__construct();
    }

    public function get_id()
    {
        return $this->id;
    }

    public function set_id($value)
    {
        $this->id = $value;
    }

    public function get_name()
    {
        return $this->name;
    }

    public function set_name($value)
    {
        $this->name = $value;
    }
}


/* End of file base_dto.php */
/* Location: ./system/application/libraries/dto/base_dto.php */