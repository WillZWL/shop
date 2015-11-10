<?php  defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Fnac_add_item_dto extends Base_dto
{
    private $sku;
    private $ext_item_id;
    private $ean;
    private $price;
    private $ext_qty;
    private $note;
    private $listing_status;

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

    public function get_ext_item_id()
    {
        return $this->ext_item_id;
    }

    public function set_ext_item_id($value)
    {
        $this->ext_item_id = $value;
    }

    public function get_ean()
    {
        return $this->ean;
    }

    public function set_ean($value)
    {
        $this->ean = $value;
    }

    public function get_price()
    {
        return $this->price;
    }

    public function set_price($value)
    {
        $this->price = $value;
    }

    public function get_ext_qty()
    {
        return $this->ext_qty;
    }

    public function set_ext_qty($value)
    {
        $this->ext_qty = $value;
    }

    public function get_note()
    {
        return $this->note;
    }

    public function set_note($value)
    {
        $this->note = $value;
    }

    public function get_listing_status()
    {
        return $this->listing_status;
    }

    public function set_listing_status($value)
    {
        $this->listing_status = $value;
    }
}

?>