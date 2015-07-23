<?php

include_once 'Base_dto.php';

class Email_referral_w_client_dto extends Base_dto
{
    protected $id;
    protected $client_id;
    protected $email;
    protected $forename;
    protected $surname;
    protected $address;
    protected $address_1;
    protected $address_2;
    protected $address_3;
    protected $postcode;
    protected $city;
    protected $state;
    protected $country_id;
    protected $tel_1;
    protected $tel_2;
    protected $tel_3;
    protected $create_on;
    protected $create_at;

    public function get_id()
    {
        return $this->id;
    }

    public function set_id($value)
    {
        $this->id = $value;
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

    public function get_email()
    {
        return $this->email;
    }

    public function set_email($value)
    {
        $this->email = $value;
        return $this;
    }

    public function get_forename()
    {
        return $this->forename;
    }

    public function set_forename($value)
    {
        $this->forename = $value;
        return $this;
    }

    public function get_surname()
    {
        return $this->surname;
    }

    public function set_surname($value)
    {
        $this->surname = $value;
        return $this;
    }


    public function get_address()
    {
        $temp[] = $this->address_1;
        $temp[] = $this->address_2;
        $temp[] = $this->address_3;
        $temp = array_filter($temp);
        $addr_str = '';
        foreach ($temp as $addr) {
            $addr_str .= $addr . ',';
        }

        return rtrim($addr_str, ',');
    }


    public function get_address_1()
    {
        return $this->address_1;
    }

    public function set_address_1($value)
    {
        $this->address_1 = $value;
        return $this;
    }

    public function get_address_2()
    {
        return $this->address_2;
    }

    public function set_address_2($value)
    {
        $this->address_2 = $value;
        return $this;
    }

    public function get_address_3()
    {
        return $this->address_3;
    }

    public function set_address_3($value)
    {
        $this->address_3 = $value;
        return $this;
    }

    public function get_postcode()
    {
        return $this->postcode;
    }

    public function set_postcode($value)
    {
        $this->postcode = $value;
        return $this;
    }

    public function get_city()
    {
        return $this->city;
    }

    public function set_city($value)
    {
        $this->city = $value;
        return $this;
    }

    public function get_state()
    {
        return $this->state;
    }

    public function set_state($value)
    {
        $this->state = $value;
        return $this;
    }

    public function get_country_id()
    {
        return $this->country_id;
    }

    public function set_country_id($value)
    {
        $this->country_id = $value;
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

    public function get_create_at()
    {
        return $this->create_at;
    }

    public function set_create_at($value)
    {
        $this->create_at = $value;
        return $this;
    }
}



