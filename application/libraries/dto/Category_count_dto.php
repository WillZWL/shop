<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Category_count_dto extends Base_dto
{
    private $id;
    private $name;
    private $count_row;

    public function get_id()
    {
        return $this->id;
    }

    public function set_id($value)
    {
        $this->id =$value;
    }

    public function get_name()
    {
        return $this->name;
    }

    public function set_name($value)
    {
        $this->name = $value;
    }

    public function get_count_row()
    {
        return $this->count_row;
    }

    public function set_count_row($value)
    {
        $this->count_row = $value;
    }

}

?>