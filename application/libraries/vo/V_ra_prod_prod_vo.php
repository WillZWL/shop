<?php
include_once 'Base_vo.php';

class V_ra_prod_prod_vo extends Base_vo
{

    //class variable
    private $sku;
    private $name;
    private $rcm_prod_id_1;
    private $rcm_prod_name_1;
    private $rcm_prod_website_status_1 = 'I';
    private $rcm_prod_id_2;
    private $rcm_prod_name_2;
    private $rcm_prod_website_status_2 = 'I';
    private $rcm_prod_id_3;
    private $rcm_prod_name_3;
    private $rcm_prod_website_status_3 = 'I';
    private $rcm_prod_id_4;
    private $rcm_prod_name_4;
    private $rcm_prod_website_status_4 = 'I';
    private $rcm_prod_id_5;
    private $rcm_prod_name_5;
    private $rcm_prod_website_status_5 = 'I';
    private $rcm_prod_id_6;
    private $rcm_prod_name_6;
    private $rcm_prod_website_status_6 = 'I';
    private $rcm_prod_id_7;
    private $rcm_prod_name_7;
    private $rcm_prod_website_status_7 = 'I';
    private $rcm_prod_id_8;
    private $rcm_prod_name_8;
    private $rcm_prod_website_status_8 = 'I';

    //primary key
    private $primary_key = array();

    //auo increment
    private $increment_field = "";

    //instance method
    public function get_sku()
    {
        return $this->sku;
    }

    public function set_sku($value)
    {
        $this->sku = $value;
        return $this;
    }

    public function get_name()
    {
        return $this->name;
    }

    public function set_name($value)
    {
        $this->name = $value;
        return $this;
    }

    public function get_rcm_prod_id_1()
    {
        return $this->rcm_prod_id_1;
    }

    public function set_rcm_prod_id_1($value)
    {
        $this->rcm_prod_id_1 = $value;
        return $this;
    }

    public function get_rcm_prod_name_1()
    {
        return $this->rcm_prod_name_1;
    }

    public function set_rcm_prod_name_1($value)
    {
        $this->rcm_prod_name_1 = $value;
        return $this;
    }

    public function get_rcm_prod_website_status_1()
    {
        return $this->rcm_prod_website_status_1;
    }

    public function set_rcm_prod_website_status_1($value)
    {
        $this->rcm_prod_website_status_1 = $value;
        return $this;
    }

    public function get_rcm_prod_id_2()
    {
        return $this->rcm_prod_id_2;
    }

    public function set_rcm_prod_id_2($value)
    {
        $this->rcm_prod_id_2 = $value;
        return $this;
    }

    public function get_rcm_prod_name_2()
    {
        return $this->rcm_prod_name_2;
    }

    public function set_rcm_prod_name_2($value)
    {
        $this->rcm_prod_name_2 = $value;
        return $this;
    }

    public function get_rcm_prod_website_status_2()
    {
        return $this->rcm_prod_website_status_2;
    }

    public function set_rcm_prod_website_status_2($value)
    {
        $this->rcm_prod_website_status_2 = $value;
        return $this;
    }

    public function get_rcm_prod_id_3()
    {
        return $this->rcm_prod_id_3;
    }

    public function set_rcm_prod_id_3($value)
    {
        $this->rcm_prod_id_3 = $value;
        return $this;
    }

    public function get_rcm_prod_name_3()
    {
        return $this->rcm_prod_name_3;
    }

    public function set_rcm_prod_name_3($value)
    {
        $this->rcm_prod_name_3 = $value;
        return $this;
    }

    public function get_rcm_prod_website_status_3()
    {
        return $this->rcm_prod_website_status_3;
    }

    public function set_rcm_prod_website_status_3($value)
    {
        $this->rcm_prod_website_status_3 = $value;
        return $this;
    }

    public function get_rcm_prod_id_4()
    {
        return $this->rcm_prod_id_4;
    }

    public function set_rcm_prod_id_4($value)
    {
        $this->rcm_prod_id_4 = $value;
        return $this;
    }

    public function get_rcm_prod_name_4()
    {
        return $this->rcm_prod_name_4;
    }

    public function set_rcm_prod_name_4($value)
    {
        $this->rcm_prod_name_4 = $value;
        return $this;
    }

    public function get_rcm_prod_website_status_4()
    {
        return $this->rcm_prod_website_status_4;
    }

    public function set_rcm_prod_website_status_4($value)
    {
        $this->rcm_prod_website_status_4 = $value;
        return $this;
    }

    public function get_rcm_prod_id_5()
    {
        return $this->rcm_prod_id_5;
    }

    public function set_rcm_prod_id_5($value)
    {
        $this->rcm_prod_id_5 = $value;
        return $this;
    }

    public function get_rcm_prod_name_5()
    {
        return $this->rcm_prod_name_5;
    }

    public function set_rcm_prod_name_5($value)
    {
        $this->rcm_prod_name_5 = $value;
        return $this;
    }

    public function get_rcm_prod_website_status_5()
    {
        return $this->rcm_prod_website_status_5;
    }

    public function set_rcm_prod_website_status_5($value)
    {
        $this->rcm_prod_website_status_5 = $value;
        return $this;
    }

    public function get_rcm_prod_id_6()
    {
        return $this->rcm_prod_id_6;
    }

    public function set_rcm_prod_id_6($value)
    {
        $this->rcm_prod_id_6 = $value;
        return $this;
    }

    public function get_rcm_prod_name_6()
    {
        return $this->rcm_prod_name_6;
    }

    public function set_rcm_prod_name_6($value)
    {
        $this->rcm_prod_name_6 = $value;
        return $this;
    }

    public function get_rcm_prod_website_status_6()
    {
        return $this->rcm_prod_website_status_6;
    }

    public function set_rcm_prod_website_status_6($value)
    {
        $this->rcm_prod_website_status_6 = $value;
        return $this;
    }

    public function get_rcm_prod_id_7()
    {
        return $this->rcm_prod_id_7;
    }

    public function set_rcm_prod_id_7($value)
    {
        $this->rcm_prod_id_7 = $value;
        return $this;
    }

    public function get_rcm_prod_name_7()
    {
        return $this->rcm_prod_name_7;
    }

    public function set_rcm_prod_name_7($value)
    {
        $this->rcm_prod_name_7 = $value;
        return $this;
    }

    public function get_rcm_prod_website_status_7()
    {
        return $this->rcm_prod_website_status_7;
    }

    public function set_rcm_prod_website_status_7($value)
    {
        $this->rcm_prod_website_status_7 = $value;
        return $this;
    }

    public function get_rcm_prod_id_8()
    {
        return $this->rcm_prod_id_8;
    }

    public function set_rcm_prod_id_8($value)
    {
        $this->rcm_prod_id_8 = $value;
        return $this;
    }

    public function get_rcm_prod_name_8()
    {
        return $this->rcm_prod_name_8;
    }

    public function set_rcm_prod_name_8($value)
    {
        $this->rcm_prod_name_8 = $value;
        return $this;
    }

    public function get_rcm_prod_website_status_8()
    {
        return $this->rcm_prod_website_status_8;
    }

    public function set_rcm_prod_website_status_8($value)
    {
        $this->rcm_prod_website_status_8 = $value;
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