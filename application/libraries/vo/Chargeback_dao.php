<?php
include_once 'Base_vo.php';

class Chargeback_vo extends Base_vo
{

    //class variable
$id;
$so_no;
$chargeback_status_id;
$chargeback_reason_id;
$chargeback_reason;
$chargeback_remark_id;
$chargeback_remark;
$document;
$create_on = '0000-00-00 00:00:00';
$create_at;
$create_by;
$modify_on;
$modify_at;
$modify_by;

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

    public function get_so_no()
    {
        return $this->so_no;
    }

    public function set_so_no($value)
    {
        $this->so_no = $value;
        return $this;
    }

    public function get_chargeback_status_id()
    {
        return $this->chargeback_status_id;
    }

    public function set_chargeback_status_id($value)
    {
        $this->chargeback_status_id = $value;
        return $this;
    }

    public function get_chargeback_reason_id()
    {
        return $this->chargeback_reason_id;
    }

    public function set_chargeback_reason_id($value)
    {
        $this->chargeback_reason_id = $value;
        return $this;
    }

    public function get_chargeback_reason()
    {
        return $this->chargeback_reason;
    }

    public function set_chargeback_reason($value)
    {
        $this->chargeback_reason = $value;
        return $this;
    }

    public function get_chargeback_remark_id()
    {
        return $this->chargeback_remark_id;
    }

    public function set_chargeback_remark_id($value)
    {
        $this->chargeback_remark_id = $value;
        return $this;
    }

    public function get_chargeback_remark()
    {
        return $this->chargeback_remark;
    }

    public function set_chargeback_remark($value)
    {
        $this->chargeback_remark = $value;
        return $this;
    }

    public function get_document()
    {
        return $this->document;
    }

    public function set_document($value)
    {
        $this->document = $value;
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