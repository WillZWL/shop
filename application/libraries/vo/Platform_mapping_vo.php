<?php
include_once 'Base_vo.php';

class Platform_mapping_vo extends Base_vo
{

    //class variable
    private $ext_system;
    private $ext_mapping_key;
    private $ext_remark;
    private $selling_platform;
    private $account;
    private $create_on = '0000-00-00 00:00:00';
    private $create_at = '127.0.0.1';
    private $create_by;
    private $modify_on;
    private $modify_at = '127.0.0.1';
    private $modify_by;

    //primary key
    private $primary_key = array("ext_system", "ext_mapping_key");

    //auo increment
    private $increment_field = "";

    //instance method
    public function get_ext_system()
    {
        return $this->ext_system;
    }

    public function set_ext_system($value)
    {
        $this->ext_system = $value;
        return $this;
    }

    public function get_ext_mapping_key()
    {
        return $this->ext_mapping_key;
    }

    public function set_ext_mapping_key($value)
    {
        $this->ext_mapping_key = $value;
        return $this;
    }

    public function get_ext_remark()
    {
        return $this->ext_remark;
    }

    public function set_ext_remark($value)
    {
        $this->ext_remark = $value;
        return $this;
    }

    public function get_selling_platform()
    {
        return $this->selling_platform;
    }

    public function set_selling_platform($value)
    {
        $this->selling_platform = $value;
        return $this;
    }

    public function get_account()
    {
        return $this->account;
    }

    public function set_account($value)
    {
        $this->account = $value;
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