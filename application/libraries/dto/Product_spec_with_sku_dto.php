<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Product_spec_with_sku_dto extends Base_dto
{

    //class variable
    private $psg_func_id;
    private $ps_func_id;
    private $unit_id;
    private $text;
    private $start_value;
    private $end_value;
    private $final_value;


    public function get_psg_func_id()
    {
        return $this->psg_func_id;
    }

    public function set_psg_func_id($value)
    {
        $this->psg_func_id = $value;
    }

    public function get_ps_func_id()
    {
        return $this->ps_func_id;
    }

    public function set_ps_func_id($value)
    {
        $this->ps_func_id = $value;
    }

    public function get_unit_id()
    {
        return $this->unit_id;
    }

    public function set_unit_id($value)
    {
        $this->unit_id = $value;
    }

    public function get_text()
    {
        return $this->text;
    }

    public function set_text($value)
    {
        $this->text = $value;
    }

    public function get_start_value()
    {
        return $this->start_value;
    }

    public function set_start_value($value)
    {
        $this->start_value = $value;
    }

    public function get_end_value()
    {
        return $this->end_value;
    }

    public function set_end_value($value)
    {
        $this->end_value = $value;
    }

    public function get_final_value()
    {
        return $this->final_value;
    }

    public function set_final_value($value)
    {
        $this->final_value = $value;
    }
}


