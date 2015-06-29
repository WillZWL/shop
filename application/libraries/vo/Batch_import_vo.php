<?php
include_once "base_vo.php";

class Batch_import_vo extends Base_vo
{

    private $batch_id;

    //class variable
    private $function_name;
    private $status;
    private $remark;
    private $end_time = '0000-00-00 00:00:00';
    private $create_on = '0000-00-00 00:00:00';
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;
    private $primary_key = array("batch_id");

    //primary key
    private $increment_field = "batch_id";

    //auo increment

    public function __construct()
    {
        parent::Base_vo();
    }

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

    public function get_function_name()
    {
        return $this->function_name;
    }

    public function set_function_name($value)
    {
        $this->function_name = $value;
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

    public function get_remark()
    {
        return $this->remark;
    }

    public function set_remark($value)
    {
        $this->remark = $value;
        return $this;
    }

    public function get_end_time()
    {
        return $this->end_time;
    }

    public function set_end_time($value)
    {
        $this->end_time = $value;
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


