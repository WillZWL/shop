<?php
include_once 'Base_dto.php';

class So_w_client_and_item_dto extends Base_dto
{
//class variable
    public $so_item = array();
    protected $so_no;
    protected $fingerprintId;
    protected $line_no;
    protected $prod_sku;
    protected $prod_name;
    protected $qty;
    protected $unit_price;
    protected $forename;
    protected $surname;
    protected $email;
    protected $payer_email;
    protected $companyname;
    protected $payment_gateway_id;
    protected $risk_ref3;
    protected $risk_ref4;
    protected $tel_1;
    protected $tel_2;
    protected $tel_3;
    protected $currency_id;
    protected $amount;
    protected $lang_id;
    protected $client_id_no;
    protected $address_1;
    protected $address_2;
    protected $address_3;
    protected $postcode;
    protected $city;
    protected $state;
    protected $country_id;
    protected $del_name;
    protected $del_address_1;
    protected $del_address_2;
    protected $del_address_3;
    protected $del_postcode;
    protected $del_city;
    protected $del_state;
    protected $del_country_id;
    protected $del_company;
    protected $create_on;
    protected $create_at;
    protected $create_by;
    protected $modify_on;
    protected $modify_at;
    protected $modify_by;

//instance method
    public function get_so_no()
    {
        return $this->so_no;
    }

    public function set_so_no($value)
    {
        $this->so_no = $value;
        return $this;
    }

    public function get_fingerprintId()
    {
        return $this->fingerprintId;
    }

    public function set_fingerprintId($value)
    {
        $this->fingerprintId = $value;
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

    public function get_payment_gateway_id()
    {
        return $this->payment_gateway_id;
    }

    public function set_payment_gateway_id($value)
    {
        $this->payment_gateway_id = $value;
        return $this;
    }

    public function get_risk_ref3()
    {
        return $this->risk_ref3;
    }

    public function set_risk_ref3($value)
    {
        $this->risk_ref3 = $value;
        return $this;
    }

    public function get_risk_ref4()
    {
        return $this->risk_ref4;
    }

    public function set_risk_ref4($value)
    {
        $this->risk_ref4 = $value;
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

    public function get_currency_id()
    {
        return $this->currency_id;
    }

    public function set_currency_id($value)
    {
        $this->currency_id = $value;
        return $this;
    }

    public function get_amount()
    {
        return $this->amount;
    }

    public function set_amount($value)
    {
        $this->amount = $value;
        return $this;
    }

    public function get_lang_id()
    {
        return $this->lang_id;
    }

    public function set_lang_id($value)
    {
        $this->lang_id = $value;
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

    public function get_del_company()
    {
        return $this->del_company;
    }

    public function set_del_company($value)
    {
        $this->del_company = $value;
        return $this;
    }

    public function get_line_no()
    {
        return $this->line_no;
    }

    public function set_line_no($value)
    {
        $this->line_no = $value;
        return $this;
    }

    public function get_prod_sku()
    {
        return $this->prod_sku;
    }

    public function set_prod_sku($value)
    {
        $this->prod_sku = $value;
        return $this;
    }

    public function get_prod_name()
    {
        return $this->prod_name;
    }

    public function set_prod_name($value)
    {
        $this->prod_name = $value;
        return $this;
    }

    public function get_qty()
    {
        return $this->qty;
    }

    public function set_qty($value)
    {
        $this->qty = $value;
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

    public function get_payer_email()
    {
        return $this->payer_email;
    }

    public function set_payer_email($value)
    {
        $this->payer_email = $value;
        return $this;
    }

    public function get_unit_price()
    {
        return $this->unit_price;
    }

    public function set_unit_price($value)
    {
        $this->unit_price = $value;
        return $this;
    }
}

?>