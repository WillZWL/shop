<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Qoo10_pending_shipment_orders_dto extends Base_dto
{
    private $so_no;
    private $platform_order_id;
    private $txn_id;
    private $courier_name;
    private $ext_item_cd;
    private $item_count;
    private $courier_id;
    private $tracking_no;
    private $dispatch_date;
    private $platform_country_id;

    public function __construct()
    {
        parent::__construct();
    }

    public function get_so_no()
    {
        return $this->so_no;
    }

    public function set_so_no($value)
    {
        $this->so_no = $value;
    }

    public function get_platform_order_id()
    {
        return $this->platform_order_id;
    }

    public function set_platform_order_id($value)
    {
        $this->platform_order_id = $value;
    }

    public function get_txn_id()
    {
        return $this->txn_id;
    }

    public function set_txn_id($value)
    {
        $this->txn_id = $value;
    }

    public function get_ext_item_cd()
    {
        return $this->ext_item_cd;
    }

    public function set_ext_item_cd($value)
    {
        $this->ext_item_cd = $value;
    }

    public function get_item_count()
    {
        return $this->item_count;
    }

    public function set_item_count($value)
    {
        $this->item_count = $value;
    }

    public function get_courier_id()
    {
        return $this->courier_id;
    }

    public function set_courier_id($value)
    {
        $this->courier_id = $value;
    }

    public function get_courier_name()
    {
        return $this->courier_name;
    }

    public function set_courier_name($value)
    {
        $this->courier_name = $value;
    }

    public function get_tracking_no()
    {
        return $this->tracking_no;
    }

    public function set_tracking_no($value)
    {
        $this->tracking_no = $value;
    }

    public function get_dispatch_date()
    {
        return $this->dispatch_date;
    }

    public function set_dispatch_date($value)
    {
        $this->dispatch_date = $value;
    }

    public function get_platform_country_id()
    {
        return $this->platform_country_id;
    }

    public function set_platform_country_id($value)
    {
        $this->platform_country_id = $value;
    }
}

