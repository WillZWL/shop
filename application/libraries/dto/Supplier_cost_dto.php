<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dto.php';

class Supplier_cost_dto extends Base_dto
{
    private $sku;
    private $ext_sku;
    private $prod_name;
    private $supplier_cost;

    public function __construct()
    {
        parent::__construct();
    }

    public function get_sku()
    {
        return $this->sku;
    }

    public function set_sku($value)
    {
        $this->sku = $value;
    }

    public function get_ext_sku()
    {
        return $this->ext_sku;
    }

    public function set_ext_sku($value)
    {
        $this->ext_sku = $value;
    }

    public function get_prod_name()
    {
        return $this->prod_name;
    }

    public function set_prod_name($value)
    {
        $this->prod_name = $value;
    }

    public function get_supplier_cost()
    {
        return $this->supplier_cost;
    }

    public function set_supplier_cost($value)
    {
        $this->supplier_cost = $value;
    }
}
