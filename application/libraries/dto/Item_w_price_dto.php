<?php
include_once 'Base_dto.php';

class Item_w_price_dto extends Base_dto
{

    //class variable
    private $prod_sku;
    private $discount;
    private $item_sku;
    private $listing_status;

    //instance method
    public function get_prod_sku()
    {
        return $this->prod_sku;
    }

    public function set_prod_sku($value)
    {
        $this->prod_sku = $value;
        return $this;
    }

    public function get_discount()
    {
        return $this->discount;
    }

    public function set_discount($value)
    {
        $this->discount = $value;
        return $this;
    }

    public function get_item_sku()
    {
        return $this->item_sku;
    }

    public function set_item_sku($value)
    {
        $this->item_sku = $value;
        return $this;
    }

    public function get_price()
    {
        return $this->price;
    }

    public function set_price($value)
    {
        $this->price = $value;
        return $this;
    }

    public function get_listing_status()
    {
        return $this->listing_status;
    }

    public function set_listing_status($value)
    {
        $this->listing_status = $value;
        return $this;
    }

}
?>