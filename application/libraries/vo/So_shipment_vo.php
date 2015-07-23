<?php
include_once 'Base_vo.php';

class So_shipment_vo extends Base_vo
{

    //class variable
    private $sh_no;
    private $courier_id;
    private $tracking_no;
    private $courier_feed_sent;
    private $status = '0';
    private $create_on = '0000-00-00 00:00:00';
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;

    //primary key
    private $primary_key = array("sh_no");

    //auo increment
    private $increment_field = "";

    //instance method
    public function get_sh_no()
    {
        return $this->sh_no;
    }

    public function set_sh_no($value)
    {
        $this->sh_no = $value;
        return $this;
    }

    public function get_courier_id()
    {
        return $this->courier_id;
    }

    public function set_courier_id($value)
    {
        $this->courier_id = $value;
        return $this;
    }

    public function get_tracking_no()
    {
        return $this->tracking_no;
    }

    public function set_tracking_no($value)
    {
        $this->tracking_no = $value;
        return $this;
    }

    public function get_courier_feed_sent()
    {
        return $this->courier_feed_sent;
    }

    public function set_courier_feed_sent($value)
    {
        $this->courier_feed_sent = $value;
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