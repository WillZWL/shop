<?php
include_once 'Base_vo.php';

class Courier_feed_vo extends Base_vo
{
    //class variable

    private $batch_id;
    private $so_no_str;
    private $courier_id;
    private $mawb;
    private $exec;
    private $comment;
    private $create_on = '0000-00-00 00:00:00';
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;


    //primary key
    private $primary_key = array("batch_id");

    //auo increment
    private $increment_field = "batch_id";

    //instance method
    public function get_batch_id()
    {
        return $this->batch_id;
    }

    public function set_batch_id($value)
    {
        $this->batch_id = $value;
        return $this;
    }

    public function get_so_no_str()
    {
        return $this->so_no_str;
    }

    public function set_so_no_str($value)
    {
        $this->so_no_str = $value;
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

    public function get_mawb()
    {
        return $this->mawb;
    }

    public function set_mawb($value)
    {
        $this->mawb = $value;
        return $this;
    }

    public function get_exec()
    {
        return $this->exec;
    }

    public function set_exec($value)
    {
        $this->exec = $value;
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

    public function get_comment()
    {
        return $this->comment;
    }

    public function set_comment($value)
    {
        $this->comment = $value;
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