<?php

include_once "Base_dto.php";

class So_awaiting_shipment_dto extends Base_dto
{
    private $sku;
    private $qty;
    private $ext_ref_sku;
    private $ext_sys;
    private $warehouse;

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

    public function get_qty()
    {
        return $this->qty;
    }

    public function set_qty($value)
    {
        $this->qty = $value;
    }

    public function get_ext_ref_sku()
    {
        return $this->ext_ref_sku;
    }

    public function set_ext_ref_sku($value)
    {
        $this->ext_ref_sku = $value;
    }

    public function get_ext_sys()
    {
        return $this->ext_sys;
    }

    public function set_ext_sys($value)
    {
        $this->ext_sys = $value;
    }

    public function get_warehouse()
    {
        return $this->warehouse;
    }

    public function set_warehouse($value)
    {
        $this->warehouse = $value;
    }

}

?>