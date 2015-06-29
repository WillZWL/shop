<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Reevoo_customer_feed_dto extends Base_dto
{

    private $forename;
    private $surname;
    private $email;
    private $purchase_date;
    private $dispatch_date;
    private $client_id;
    private $postcode;
    private $delivery_country_id;
    private $so_no;
    private $item_sku;
    private $currency_id;
    private $amount;

    public function __construct()
    {
        parent::__construct();
    }

    public function get_forename()
    {
        return $this->forename;
    }

    public function set_forename($value)
    {
        $this->forename = $value;
    }

    public function get_surname()
    {
        return $this->surname;
    }

    public function set_surname($value)
    {
        $this->surname = $value;
    }

    public function get_email()
    {
        return $this->email;
    }

    public function set_email($value)
    {
        $this->email = $value;
    }

    public function set_purchase_date($value)
    {
        $this->purchase_date = $value;
    }

    public function get_purchase_date()
    {
        return $this->purchase_date;
    }

    public function set_dispatch_date($value)
    {
        $this->dispatch_date = $value;
    }

    public function get_dispatch_date()
    {
        return $this->dispatch_date;
    }

    public function get_client_id()
    {
        return $this->client_id;
    }

    public function set_client_id($value)
    {
        $this->client_id = $value;
    }

    public function get_postcode()
    {
        return $this->postcode;
    }

    public function set_postcode($value)
    {
        $this->postcode = $value;
    }

    public function get_delivery_country_id()
    {
        return $this->delivery_country_id;
    }

    public function set_delivery_country_id($value)
    {
        $this->delivery_country_id = $value;
    }

    public function get_so_no()
    {
        return $this->so_no;
    }

    public function set_so_no($value)
    {
        $this->so_no = $value;
    }

    public function get_item_sku()
    {
        return $this->item_sku;
    }

    public function set_item_sku($value)
    {
        $this->item_sku = $value;
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

/* End of file reevoo_customer_feed_dto.php */
/* Location: ./system/application/libraries/dto/reevoo_customer_feed_dto.php */