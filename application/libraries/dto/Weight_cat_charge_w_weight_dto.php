<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Weight_cat_charge_w_weight_dto extends Base_dto {

    //class variable
    private $wcat_id;
    private $weight;
    private $delivery_type;
    private $dest_country;
    private $currency_id;
    private $amount;

    function __construct(){
        parent::__construct();
    }

    //instance method
    public function get_wcat_id()
    {
        return $this->wcat_id;
    }

    public function set_wcat_id($value)
    {
        $this->wcat_id = $value;
    }

    public function get_weight()
    {
        return $this->weight;
    }

    public function set_weight($value)
    {
        $this->weight = $value;
    }

    public function get_delivery_type()
    {
        return $this->delivery_type;
    }

    public function set_delivery_type($value)
    {
        $this->delivery_type = $value;
    }

    public function get_dest_country()
    {
        return $this->dest_country;
    }

    public function set_dest_country($value)
    {
        $this->dest_country = $value;
    }

    public function get_currency_id()
    {
        return $this->currency_id;
    }

    public function set_currency_id($value)
    {
        $this->currency_id = $value;
    }

    public function get_amount()
    {
        return $this->amount;
    }

    public function set_amount($value)
    {
        $this->amount = $value;
    }
}

/* End of file weight_cat_charge_w_weight_dto.php */
/* Location: ./system/application/libraries/dto/weight_cat_charge_w_weight_dto.php */