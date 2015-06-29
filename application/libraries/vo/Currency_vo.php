<?php
include_once 'Base_vo.php';

class Currency_vo extends Base_vo
{

    //class variable
    private $id;
    private $sign;
    private $name;
    private $description;
    private $round_up;
    private $sign_pos;
    private $dec_place;
    private $dec_point;
    private $thousands_sep;
    private $create_on = '0000-00-00 00:00:00';
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;

    //primary key
    private $primary_key = array("id");

    //auo increment
    private $increment_field = "";

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

    public function get_sign()
    {
        return $this->sign;
    }

    public function set_sign($value)
    {
        $this->sign = $value;
        return $this;
    }

    public function get_name()
    {
        return $this->name;
    }

    public function set_name($value)
    {
        $this->name = $value;
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

    public function get_round_up()
    {
        return $this->round_up;
    }

    public function set_round_up($value)
    {
        $this->round_up = $value;
        return $this;
    }

    public function get_sign_pos()
    {
        return $this->sign_pos;
    }

    public function set_sign_pos($value)
    {
        $this->sign_pos = $value;
        return $this;
    }

    public function get_dec_place()
    {
        return $this->dec_place;
    }

    public function set_dec_place($value)
    {
        $this->dec_place = $value;
        return $this;
    }

    public function get_dec_point()
    {
        return $this->dec_point;
    }

    public function set_dec_point($value)
    {
        $this->dec_point = $value;
        return $this;
    }

    public function get_thousands_sep()
    {
        return $this->thousands_sep;
    }

    public function set_thousands_sep($value)
    {
        $this->thousands_sep = $value;
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