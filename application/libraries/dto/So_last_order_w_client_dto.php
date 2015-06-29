<?php

include_once 'Base_dto.php';

class So_last_order_w_client_dto extends Base_dto
{
    protected $so_no;
    protected $email;
    protected $client_id;
    protected $delivery_name;
    protected $delivery_company;
    protected $delivery_address;
    protected $delivery_postcode;
    protected $delivery_city;
    protected $delivery_state;
    protected $delivery_country_id;
    protected $bill_name;
    protected $bill_company;
    protected $bill_address;
    protected $bill_postcode;
    protected $bill_city;
    protected $bill_state;
    protected $bill_country_id;
    protected $title;
    protected $tel_1;
    protected $tel_2;
    protected $tel_3;
    protected $create_on;

    public function get_so_no()
    {
        return $this->so_no;
    }

    public function set_so_no($value)
    {
        $this->so_no = $value;
        return $this;
    }

    public function get_email()
    {
        return $this->email;
    }

    public function set_email($value)
    {
        $this->email = $value;
        return $this;
    }

    public function get_client_id()
    {
        return $this->client_id;
    }

    public function set_client_id($value)
    {
        $this->client_id = $value;
        return $this;
    }

    public function get_bill_name()
    {
        return $this->bill_name;
    }

    public function set_bill_name($value)
    {
        $this->bill_name = $value;
        return $this;
    }

    public function get_bill_company()
    {
        return $this->bill_company;
    }

    public function set_bill_company($value)
    {
        $this->bill_company = $value;
        return $this;
    }

    public function get_bill_address()
    {
        return $this->bill_address;
    }

    public function set_bill_address($value)
    {
        $this->bill_address = $value;
        return $this;
    }

    public function get_bill_postcode()
    {
        return $this->bill_postcode;
    }

    public function set_bill_postcode($value)
    {
        $this->bill_postcode = $value;
        return $this;
    }

    public function get_bill_city()
    {
        return $this->bill_city;
    }

    public function set_bill_city($value)
    {
        $this->bill_city = $value;
        return $this;
    }

    public function get_bill_state()
    {
        return $this->bill_state;
    }

    public function set_bill_state($value)
    {
        $this->bill_state = $value;
        return $this;
    }

    public function get_title()
    {
        return $this->title;
    }

    public function set_title($value)
    {
        $this->title = $value;
        return $this;
    }

    public function get_bill_country_id()
    {
        return $this->bill_country_id;
    }

    public function set_bill_country_id($value)
    {
        $this->bill_country_id = $value;
        return $this;
    }

    public function get_delivery_name()
    {
        return $this->delivery_name;
    }

    public function set_delivery_name($value)
    {
        $this->delivery_name = $value;
        return $this;
    }

    public function get_delivery_name_segment($index)
    {
        if ($this->delivery_name)
        {
            $name_segment = explode(" ", $this->delivery_name);
            if (($index == 0) && (sizeof($name_segment) > 0))
            {
                return $name_segment[0];
            }
            else if (($index == 1) && (sizeof($name_segment) > 1))
            {
                return $name_segment[1];
            }
        }
        return "";
    }

    public function get_delivery_forename()
    {
        return $this->get_delivery_name_segment(0);
    }

    public function get_delivery_surname()
    {
        return $this->get_delivery_name_segment(1);
    }

    public function get_delivery_company()
    {
        return $this->delivery_company;
    }

    public function set_delivery_company($value)
    {
        $this->delivery_company = $value;
        return $this;
    }

    public function get_delivery_address()
    {
        return $this->delivery_address;
    }

    public function set_delivery_address($value)
    {
        $this->delivery_address = $value;
        return $this;
    }

    public function get_delivery_address_segment($index)
    {
        if ($this->delivery_address)
        {
            $address_segment = explode("|", $this->delivery_address);
            if (($index == 0) && (sizeof($address_segment) > 0))
            {
                return $address_segment[0];
            }
            else if (($index == 1) && (sizeof($address_segment) > 1))
            {
                return $address_segment[1];
            }
            else if (($index == 2) && (sizeof($address_segment) > 2))
            {
                return $address_segment[2];
            }
        }
        return "";
    }

    public function get_delivery_address_1()
    {
        return $this->get_delivery_address_segment(0);
    }

    public function get_delivery_address_2()
    {
        return $this->get_delivery_address_segment(1);
    }

    public function get_delivery_address_3()
    {
        return $this->get_delivery_address_segment(2);
    }

    public function get_delivery_postcode()
    {
        return $this->delivery_postcode;
    }

    public function set_delivery_postcode($value)
    {
        $this->delivery_postcode = $value;
        return $this;
    }

    public function get_delivery_city()
    {
        return $this->delivery_city;
    }

    public function set_delivery_city($value)
    {
        $this->delivery_city = $value;
        return $this;
    }

    public function get_delivery_state()
    {
        return $this->delivery_state;
    }

    public function set_delivery_state($value)
    {
        $this->delivery_state = $value;
        return $this;
    }

    public function get_delivery_country_id()
    {
        return $this->delivery_country_id;
    }

    public function set_delivery_country_id($value)
    {
        $this->delivery_country_id = $value;
        return $this;
    }

    public function get_tel_1()
    {
        return $this->tel_1;
    }

    public function set_tel_1($value)
    {
        $this->tel_1 = $value;
        return $this;
    }

    public function get_tel_2()
    {
        return $this->tel_2;
    }

    public function set_tel_2($value)
    {
        $this->tel_2 = $value;
        return $this;
    }

    public function get_tel_3()
    {
        return $this->tel_3;
    }

    public function set_tel_3($value)
    {
        $this->tel_3 = $value;
        return $this;
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
}

