<?php
include_once "Base_vo.php";

class So_risk_vo extends Base_vo
{

    private $so_no = '';

    //class variable
    private $risk_requested = '0';
    private $risk_var1;
    private $risk_var2;
    private $risk_var3;
    private $risk_var4;
    private $risk_var5;
    private $risk_var6;
    private $risk_var7;
    private $risk_var8;
    private $risk_var9;
    private $risk_var10;
    private $create_on = '0000-00-00 00:00:00';
    private $create_at = '127.0.0.1';
    private $create_by;
    private $modify_on;
    private $modify_at = '127.0.0.1';
    private $modify_by;
    private $primary_key = array("so_no");

    //primary key
    private $increment_field = "";


    //instance method

    public function get_so_no()
    {
        return $this->so_no;
    }

    public function set_so_no($value)
    {
        $this->so_no = $value;
        return $this;
    }

    public function get_risk_requested()
    {
        return $this->risk_requested;
    }

    public function set_risk_requested($value)
    {
        $this->risk_requested = $value;
        return $this;
    }

    public function get_risk_var1()
    {
        return $this->risk_var1;
    }

    public function set_risk_var1($value)
    {
        $this->risk_var1 = $value;
        return $this;
    }

    public function get_risk_var2()
    {
        return $this->risk_var2;
    }

    public function set_risk_var2($value)
    {
        $this->risk_var2 = $value;
        return $this;
    }

    public function get_risk_var3()
    {
        return $this->risk_var3;
    }

    public function set_risk_var3($value)
    {
        $this->risk_var3 = $value;
        return $this;
    }

    public function get_risk_var4()
    {
        return $this->risk_var4;
    }

    public function set_risk_var4($value)
    {
        $this->risk_var4 = $value;
        return $this;
    }

    public function get_risk_var5()
    {
        return $this->risk_var5;
    }

    public function set_risk_var5($value)
    {
        $this->risk_var5 = $value;
        return $this;
    }

    public function get_risk_var6()
    {
        return $this->risk_var6;
    }

    public function set_risk_var6($value)
    {
        $this->risk_var6 = $value;
        return $this;
    }

    public function get_risk_var7()
    {
        return $this->risk_var7;
    }

    public function set_risk_var7($value)
    {
        $this->risk_var7 = $value;
        return $this;
    }

    public function get_risk_var8()
    {
        return $this->risk_var8;
    }

    public function set_risk_var8($value)
    {
        $this->risk_var8 = $value;
        return $this;
    }

    public function get_risk_var9()
    {
        return $this->risk_var9;
    }

    public function set_risk_var9($value)
    {
        $this->risk_var9 = $value;
        return $this;
    }

    public function get_risk_var10()
    {
        return $this->risk_var10;
    }

    public function set_risk_var10($value)
    {
        $this->risk_var10 = $value;
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
