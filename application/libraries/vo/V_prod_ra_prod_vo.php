<?php
include_once 'Base_vo.php';

class V_prod_ra_prod_vo extends Base_vo
{

    //class variable
    private $sku;
    private $ra_sku;

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

    public function get_ra_sku()
    {
        return $this->ra_sku;
    }

    public function set_ra_sku($value)
    {
        $this->ra_sku = $value;
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