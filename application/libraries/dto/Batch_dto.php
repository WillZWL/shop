<?php
include_once 'Base_dto.php';

class Batch_dto extends Base_dto
{

    //class variable
    private $id;
    private $func_name;
    private $status;
    private $listed;
    private $remark;
    private $end_time;
    private $create_on;
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;
    private $duration;

    //instance method
    public function get_id()
    {
        return $this->id;
    }

    public function set_id($value)
    {
        $this->id = $value;
    }

    public function get_func_name()
    {
        return $this->func_name;
    }

    public function set_func_name($value)
    {
        $this->func_name = $value;
    }

    public function get_status()
    {
        return $this->status;
    }

    public function set_status($value)
    {
        $this->status = $value;
    }

    public function get_listed()
    {
        return $this->listed;
    }

    public function set_listed($value)
    {
        $this->listed = $value;
    }

    public function get_remark()
    {
        return $this->remark;
    }

    public function set_remark($value)
    {
        $this->remark = $value;
    }

    public function get_end_time()
    {
        return $this->end_time;
    }

    public function set_end_time($value)
    {
        $this->end_time = $value;
    }

    public function get_create_on()
    {
        return $this->create_on;
    }

    public function set_create_on($value)
    {
        $this->create_on = $value;
    }

    public function get_create_at()
    {
        return $this->create_at;
    }

    public function set_create_at($value)
    {
        $this->create_at = $value;
    }

    public function get_create_by()
    {
        return $this->create_by;
    }

    public function set_create_by($value)
    {
        $this->create_by = $value;
    }

    public function get_modify_on()
    {
        return $this->modify_on;
    }

    public function set_modify_on($value)
    {
        $this->modify_on = $value;
    }

    public function get_modify_at()
    {
        return $this->modify_at;
    }

    public function set_modify_at($value)
    {
        $this->modify_at = $value;
    }

    public function get_modify_by()
    {
        return $this->modify_by;
    }

    public function set_modify_by($value)
    {
        $this->modify_by = $value;
    }

    public function get_duration()
    {
        return $this->duration;
    }

    public function set_duration($value)
    {
        $this->duration = $value;
    }

}


