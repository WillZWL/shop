<?php
include_once 'Base_vo.php';

class Category_vo extends Base_vo
{

    //class variable
    private $id;
    private $name;
    private $description;
    private $parent_cat_id = '0';
    private $level = '1';
    private $add_colour_name = '1';
    private $priority = '9';
    private $bundle_discount;
    private $min_display_qty;
    private $status = '1';
    private $create_on = '0000-00-00 00:00:00';
    private $create_at = '127.0.0.1';
    private $create_by;
    private $modify_on;
    private $modify_at = '127.0.0.1';
    private $modify_by;

    //primary key
    private $primary_key = array("id");

    //auo increment
    private $increment_field = "id";

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

    public function get_parent_cat_id()
    {
        return $this->parent_cat_id;
    }

    public function set_parent_cat_id($value)
    {
        $this->parent_cat_id = $value;
        return $this;
    }

    public function get_level()
    {
        return $this->level;
    }

    public function set_level($value)
    {
        $this->level = $value;
        return $this;
    }

    public function get_add_colour_name()
    {
        return $this->add_colour_name;
    }

    public function set_add_colour_name($value)
    {
        $this->add_colour_name = $value;
        return $this;
    }

    public function get_priority()
    {
        return $this->priority;
    }

    public function set_priority($value)
    {
        $this->priority = $value;
        return $this;
    }

    public function get_bundle_discount()
    {
        return $this->bundle_discount;
    }

    public function set_bundle_discount($value)
    {
        $this->bundle_discount = $value;
        return $this;
    }

    public function get_min_display_qty()
    {
        return $this->min_display_qty;
    }

    public function set_min_display_qty($value)
    {
        $this->min_display_qty = $value;
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