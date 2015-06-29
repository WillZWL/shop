<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dto.php';

class Supplier_status_dto extends Base_dto
{
    private $sku;
    private $ext_sku;
    private $prod_name;
    private $supplier_id;
    private $supplier_name;
    private $supplier_status;
    private $supplier_status_desc;

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

    public function get_supplier_id()
    {
        return $this->supplier_id;
    }

    public function set_supplier_id($value)
    {
        $this->supplier_id = $value;
    }

    public function get_supplier_name()
    {
        return $this->supplier_name;
    }

    public function set_supplier_name($value)
    {
        $this->supplier_name = $value;
    }

    public function get_supplier_status()
    {
        return $this->supplier_status;
    }

    public function set_supplier_status($value)
    {
        $this->supplier_status = $value;
    }

    public function get_supplier_status_desc()
    {
        return $this->supplier_status_desc;
    }

    public function set_supplier_status_desc($value)
    {
        $this->supplier_status_desc = $value;
    }
}


