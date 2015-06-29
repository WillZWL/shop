<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dto.php';

class Ra_prod_w_name_dto extends Base_dto
{

    //class variable
    private $sku;
    private $rcm_prod_id_1;
    private $rcm_prod_id_2;
    private $rcm_prod_id_3;
    private $rcm_prod_id_4;
    private $rcm_prod_id_5;
    private $rcm_prod_id_6;
    private $rcm_prod_id_7;
    private $rcm_prod_id_8;
    private $rcm_prod_name_1;
    private $rcm_prod_name_2;
    private $rcm_prod_name_3;
    private $rcm_prod_name_4;
    private $rcm_prod_name_5;
    private $rcm_prod_name_6;
    private $rcm_prod_name_7;
    private $rcm_prod_name_8;
    private $create_on;
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;

    //instance method
    public function get_sku()
    {
        return $this->sku;
    }

    public function set_sku($value)
    {
        $this->sku = $value;
    }

    public function get_rcm_prod_id_1()
    {
        return $this->rcm_prod_id_1;
    }

    public function set_rcm_prod_id_1($value)
    {
        $this->rcm_prod_id_1 = $value;
    }

    public function get_rcm_prod_id_2()
    {
        return $this->rcm_prod_id_2;
    }

    public function set_rcm_prod_id_2($value)
    {
        $this->rcm_prod_id_2 = $value;
    }

    public function get_rcm_prod_id_3()
    {
        return $this->rcm_prod_id_3;
    }

    public function set_rcm_prod_id_3($value)
    {
        $this->rcm_prod_id_3 = $value;
    }

    public function get_rcm_prod_id_4()
    {
        return $this->rcm_prod_id_4;
    }

    public function set_rcm_prod_id_4($value)
    {
        $this->rcm_prod_id_4 = $value;
    }

    public function get_rcm_prod_id_5()
    {
        return $this->rcm_prod_id_5;
    }

    public function set_rcm_prod_id_5($value)
    {
        $this->rcm_prod_id_5 = $value;
    }

    public function get_rcm_prod_id_6()
    {
        return $this->rcm_prod_id_6;
    }

    public function set_rcm_prod_id_6($value)
    {
        $this->rcm_prod_id_6 = $value;
    }

    public function get_rcm_prod_id_7()
    {
        return $this->rcm_prod_id_7;
    }

    public function set_rcm_prod_id_7($value)
    {
        $this->rcm_prod_id_7 = $value;
    }

    public function get_rcm_prod_id_8()
    {
        return $this->rcm_prod_id_8;
    }

    public function set_rcm_prod_id_8($value)
    {
        $this->rcm_prod_id_8 = $value;
    }

    public function get_rcm_prod_name_1()
    {
        return $this->rcm_prod_name_1;
    }

    public function set_rcm_prod_name_1($value)
    {
        $this->rcm_prod_name_1 = $value;
    }

    public function get_rcm_prod_name_2()
    {
        return $this->rcm_prod_name_2;
    }

    public function set_rcm_prod_name_2($value)
    {
        $this->rcm_prod_name_2 = $value;
    }

    public function get_rcm_prod_name_3()
    {
        return $this->rcm_prod_name_3;
    }

    public function set_rcm_prod_name_3($value)
    {
        $this->rcm_prod_name_3 = $value;
    }

    public function get_rcm_prod_name_4()
    {
        return $this->rcm_prod_name_4;
    }

    public function set_rcm_prod_name_4($value)
    {
        $this->rcm_prod_name_4 = $value;
    }

    public function get_rcm_prod_name_5()
    {
        return $this->rcm_prod_name_5;
    }

    public function set_rcm_prod_name_5($value)
    {
        $this->rcm_prod_name_5 = $value;
    }

    public function get_rcm_prod_name_6()
    {
        return $this->rcm_prod_name_6;
    }

    public function set_rcm_prod_name_6($value)
    {
        $this->rcm_prod_name_6 = $value;
    }

    public function get_rcm_prod_name_7()
    {
        return $this->rcm_prod_name_7;
    }

    public function set_rcm_prod_name_7($value)
    {
        $this->rcm_prod_name_7 = $value;
    }

    public function get_rcm_prod_name_8()
    {
        return $this->rcm_prod_name_8;
    }

    public function set_rcm_prod_name_8($value)
    {
        $this->rcm_prod_name_8 = $value;
    }

    public function get_rcm_prod_website_status_1()
    {
        return $this->rcm_prod_website_status_1;
    }

    public function set_rcm_prod_website_status_1($value)
    {
        $this->rcm_prod_website_status_1 = $value;
    }

    public function get_rcm_prod_website_status_2()
    {
        return $this->rcm_prod_website_status_2;
    }

    public function set_rcm_prod_website_status_2($value)
    {
        $this->rcm_prod_website_status_2 = $value;
    }

    public function get_rcm_prod_website_status_3()
    {
        return $this->rcm_prod_website_status_3;
    }

    public function set_rcm_prod_website_status_3($value)
    {
        $this->rcm_prod_website_status_3 = $value;
    }

    public function get_rcm_prod_website_status_4()
    {
        return $this->rcm_prod_website_status_4;
    }

    public function set_rcm_prod_website_status_4($value)
    {
        $this->rcm_prod_website_status_4 = $value;
    }

    public function get_rcm_prod_website_status_5()
    {
        return $this->rcm_prod_website_status_5;
    }

    public function set_rcm_prod_website_status_5($value)
    {
        $this->rcm_prod_website_status_5 = $value;
    }

    public function get_rcm_prod_website_status_6()
    {
        return $this->rcm_prod_website_status_6;
    }

    public function set_rcm_prod_website_status_6($value)
    {
        $this->rcm_prod_website_status_6 = $value;
    }

    public function get_rcm_prod_website_status_7()
    {
        return $this->rcm_prod_website_status_7;
    }

    public function set_rcm_prod_website_status_7($value)
    {
        $this->rcm_prod_website_status_7 = $value;
    }

    public function get_rcm_prod_website_status_8()
    {
        return $this->rcm_prod_website_status_8;
    }

    public function set_rcm_prod_website_status_8($value)
    {
        $this->rcm_prod_website_status_8 = $value;
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
}

/* End of file ra_prod_w_name_dto.php */
/* Location: ./system/application/libraries/dto/ra_prod_w_name_dto.php */