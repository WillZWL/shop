<?php
include_once 'Base_vo.php';

class Cache_api_request_vo extends Base_vo
{
    private $id;
    private $api;
    private $sku;
    private $platform_id;
    private $stock_update;
    private $price_update;
    private $item_create;
    private $exec;
    private $create_on = '0000-00-00 00:00:00';
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;

    private $primary_key = array("id");
    private $increment_field = "";

    public function get_id()
    {
        return $this->id;
    }

    public function set_id($value)
    {
        $this->id = $value;
    }

    public function get_api()
    {
        return $this->api;
    }

    public function set_api($value)
    {
        $this->api = $value;
    }

    public function get_sku()
    {
        return $this->sku;
    }

    public function set_sku($value)
    {
        $this->sku = $value;
    }

    public function get_platform_id()
    {
        return $this->platform_id;
    }

    public function set_platform_id($value)
    {
        $this->platform_id = $value;
    }

    public function get_stock_update()
    {
        return $this->stock_update;
    }

    public function set_stock_update($value)
    {
        $this->stock_update = $value;
    }

    public function get_price_update()
    {
        return $this->price_update;
    }

    public function set_price_update($value)
    {
        $this->price_update = $value;
    }

    public function get_item_create()
    {
        return $this->item_create;
    }

    public function set_item_create($value)
    {
        $this->item_create = $value;
    }

    public function get_exec()
    {
        return $this->exec;
    }

    public function set_exec($value)
    {
        $this->exec = $value;
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