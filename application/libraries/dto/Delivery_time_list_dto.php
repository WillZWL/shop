<?php
include_once 'Base_dto.php';

class Delivery_time_list_dto extends Base_dto
{
    // delivery_time table
    private $id;
    private $scenarioid;
    private $country_id;
    private $ship_min_day;
    private $ship_max_day;
    private $del_min_day;
    private $del_max_day;
    private $margin;
    private $dt_status;
    private $create_on = '0000-00-00 00:00:00';
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;

    // lookup_delivery_scenario table
    private $name;
    private $description;
    private $lookupscenario_status;

    public function __construct()
    {
        parent::__construct();
    }

    // delivery_time table
    public function get_id()
    {
        return $this->id;
    }

    public function set_id($value)
    {
        $this->id = $value;
        return $this;
    }

    public function get_scenarioid()
    {
        return $this->scenarioid;
    }

    public function set_scenarioid($value)
    {
        $this->scenarioid = $value;
        return $this;
    }

    public function get_country_id()
    {
        return $this->country_id;
    }

    public function set_country_id($value)
    {
        $this->country_id = $value;
        return $this;
    }

    public function get_ship_min_day()
    {
        return $this->ship_min_day;
    }

    public function set_ship_min_day($value)
    {
        $this->ship_min_day = $value;
        return $this;
    }

    public function get_ship_max_day()
    {
        return $this->ship_max_day;
    }

    public function set_ship_max_day($value)
    {
        $this->ship_max_day = $value;
        return $this;
    }

    public function get_del_min_day()
    {
        return $this->del_min_day;
    }

    public function set_del_min_day($value)
    {
        $this->del_min_day = $value;
        return $this;
    }

    public function get_del_max_day()
    {
        return $this->del_max_day;
    }

    public function set_del_max_day($value)
    {
        $this->del_max_day = $value;
        return $this;
    }

    public function get_margin()
    {
        return $this->margin;
    }

    public function set_margin($value)
    {
        $this->margin = $value;
        return $this;
    }

    public function get_dt_status()
    {
        return $this->dt_status;
    }

    public function set_dt_status($value)
    {
        $this->dt_status = $value;
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

    // lookup_delivery_scenario table
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

    public function get_lookupscenario_status()
    {
        return $this->lookupscenario_status;
    }

    public function set_lookupscenario_status($value)
    {
        $this->lookupscenario_status = $value;
        return $this;
    }
}

?>