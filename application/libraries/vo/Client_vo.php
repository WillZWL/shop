<?php
include_once 'Base_vo.php';

class Client_vo extends Base_vo
{

    //class variable
    private $id;
    private $ext_client_id;
    private $client_id_no;
    private $email;
    private $password;
    private $title;
    private $forename;
    private $surname;
    private $companyname;
    private $address_1;
    private $address_2;
    private $address_3;
    private $postcode;
    private $city;
    private $state;
    private $country_id;
    private $del_name;
    private $del_company;
    private $del_address_1;
    private $del_address_2;
    private $del_address_3;
    private $del_postcode;
    private $del_city;
    private $del_state;
    private $del_country_id;
    private $tel_1;
    private $tel_2;
    private $tel_3;
    private $mobile;
    private $del_tel_1;
    private $del_tel_2;
    private $del_tel_3;
    private $del_mobile;
    private $subscriber = '0';
    private $party_subscriber = '0';
    private $vip = '0';
    private $vip_joined_date;
    private $status = '1';
    private $create_on = '0000-00-00 00:00:00';
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;

    //primary key
    private $primary_key = array("id");

    //auo increment
    private $increment_field = "id";

    //instance method
    public function get_id()
    {
        return $this->id;
    }

    public function set_id($value)
    {
        $this->id = $value;
        return $this;
    }

    public function get_ext_client_id()
    {
        return $this->ext_client_id;
    }

    public function set_ext_client_id($value)
    {
        $this->ext_client_id = $value;
        return $this;
    }

    public function get_client_id_no()
    {
        return $this->client_id_no;
    }

    public function set_client_id_no($value)
    {
        $this->client_id_no = $value;
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

    public function get_password()
    {
        return $this->password;
    }

    public function set_password($value)
    {
        $this->password = $value;
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

    public function get_companyname()
    {
        return $this->companyname;
    }

    public function set_companyname($value)
    {
        $this->companyname = $value;
        return $this;
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

    public function get_del_name()
    {
        return $this->del_name;
    }

    public function set_del_name($value)
    {
        $this->del_name = $value;
        return $this;
    }

    public function get_del_company()
    {
        return $this->del_company;
    }

    public function set_del_company($value)
    {
        $this->del_company = $value;
        return $this;
    }

    public function get_del_address_1()
    {
        return $this->del_address_1;
    }

    public function set_del_address_1($value)
    {
        $this->del_address_1 = $value;
        return $this;
    }

    public function get_del_address_2()
    {
        return $this->del_address_2;
    }

    public function set_del_address_2($value)
    {
        $this->del_address_2 = $value;
        return $this;
    }

    public function get_del_address_3()
    {
        return $this->del_address_3;
    }

    public function set_del_address_3($value)
    {
        $this->del_address_3 = $value;
        return $this;
    }

    public function get_del_postcode()
    {
        return $this->del_postcode;
    }

    public function set_del_postcode($value)
    {
        $this->del_postcode = $value;
        return $this;
    }

    public function get_del_city()
    {
        return $this->del_city;
    }

    public function set_del_city($value)
    {
        $this->del_city = $value;
        return $this;
    }

    public function get_del_state()
    {
        return $this->del_state;
    }

    public function set_del_state($value)
    {
        $this->del_state = $value;
        return $this;
    }

    public function get_del_country_id()
    {
        return $this->del_country_id;
    }

    public function set_del_country_id($value)
    {
        $this->del_country_id = $value;
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

    public function get_mobile()
    {
        return $this->mobile;
    }

    public function set_mobile($value)
    {
        $this->mobile = $value;
        return $this;
    }

    public function get_del_tel_1()
    {
        return $this->del_tel_1;
    }

    public function set_del_tel_1($value)
    {
        $this->del_tel_1 = $value;
        return $this;
    }

    public function get_del_tel_2()
    {
        return $this->del_tel_2;
    }

    public function set_del_tel_2($value)
    {
        $this->del_tel_2 = $value;
        return $this;
    }

    public function get_del_tel_3()
    {
        return $this->del_tel_3;
    }

    public function set_del_tel_3($value)
    {
        $this->del_tel_3 = $value;
        return $this;
    }

    public function get_del_mobile()
    {
        return $this->del_mobile;
    }

    public function set_del_mobile($value)
    {
        $this->del_mobile = $value;
        return $this;
    }

    public function get_subscriber()
    {
        return $this->subscriber;
    }

    public function set_subscriber($value)
    {
        $this->subscriber = $value;
        return $this;
    }

    public function get_party_subscriber()
    {
        return $this->party_subscriber;
    }

    public function set_party_subscriber($value)
    {
        $this->party_subscriber = $value;
        return $this;
    }

    public function get_vip()
    {
        return $this->vip;
    }

    public function set_vip($value)
    {
        $this->vip = $value;
        return $this;
    }

    public function get_vip_joined_date()
    {
        return $this->vip_joined_date;
    }

    public function set_vip_joined_date($value)
    {
        $this->vip_joined_date = $value;
        return $this;
    }

    public function get_status()
    {
        return $this->status;
    }

    public function set_status($value)
    {
        $this->status = $value;
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

?>