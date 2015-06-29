<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Customer_extraction_item_list_dto extends Base_dto {

    private $title;
    private $forename;
    private $surname;
    private $companyname;
    private $email;
    private $address;
    private $postcode;
    private $country_id;
    private $phone_no;
    private $mobile;
    private $transaction_date_list;
    private $transaction_item_list;
    private $transaction_category_list;
    private $transaction_value_list;
    private $transaction_profit_list;
    private $transaction_date;
    private $transaction_item;
    private $transaction_category;
    private $transaction_value;
    private $transaction_profit;
    private $transaction_date_2;
    private $transaction_item_2;
    private $transaction_category_2;
    private $transaction_value_2;
    private $transaction_profit_2;
    private $transaction_date_3;
    private $transaction_item_3;
    private $transaction_category_3;
    private $transaction_value_3;
    private $transaction_profit_3;

    public function __construct()
    {
        parent::__construct();
    }

    public function get_title()
    {
        return $this->title;
    }

    public function set_title($value)
    {
        $this->title = $value;
    }

    public function get_forename()
    {
        $f_name = str_replace(',',' ',$this->forename);
        return $f_name;
    }

    public function set_forename($value)
    {
        $this->forename = $value;
    }

    public function get_surname()
    {
        $s_name = str_replace(',',' ',$this->surname);
        return $s_name;
    }

    public function set_surname($value)
    {
        $this->surname = $value;
    }

    public function get_companyname()
    {
        $c_name = str_replace(',',' ',$this->companyname);
        return $c_name;
    }

    public function set_companyname($value)
    {
        $this->companyname = $value;
    }

    public function get_email()
    {
        $e_mail = str_replace(',',' ',$this->email);
        return $e_mail;
    }

    public function set_email($value)
    {
        $this->email = $value;
    }

    public function get_postcode()
    {
        $p_code = str_replace(',',' ',$this->postcode);
        return $p_code;
    }

    public function set_postcode($value)
    {
        $this->postcode = $value;
    }

    public function get_country_id()
    {
        return $this->country_id;
    }

    public function set_country_id($value)
    {
        $this->country_id = $value;
    }

    public function get_phone_no()
    {
        return $this->phone_no;
    }

    public function set_phone_no($value)
    {
        $this->phone_no = $value;
    }

    public function get_mobile()
    {
        return $this->mobile;
    }

    public function set_mobile($value)
    {
        $this->mobile = $value;
    }

    public function set_transaction_date_list($value)
    {
        $this->transaction_date_list = $value;
    }

    public function set_transaction_item_list($value)
    {
        $this->transaction_item_list = $value;
    }

    public function set_transaction_category_list($value)
    {
        $this->transaction_category_list = $value;
    }


    public function set_transaction_value_list($value)
    {
        $this->transaction_value_list = $value;
    }

    public function set_transaction_profit_list($value)
    {
        $this->transaction_profit_list = $value;
    }


    public function get_transaction_date()
    {
        $this->set_transaction_date_list($this->transaction_date);
        $date = explode('||', $this->transaction_date_list);
        if($date[0])
        {
            $this->set_transaction_date($date[0]);
            return $this->transaction_date;
        }
        else
        {
            return NULL;
        }

    }

    public function set_transaction_date($value)
    {
        $this->transaction_date = $value;
    }

    public function get_transaction_item()
    {
        $t_item = str_replace(',',' ',$this->transaction_item);
        $this->set_transaction_item_list($t_item);
        $item = explode('||', $this->transaction_item_list);
        if($item[0])
        {
            $this->set_transaction_item($item[0]);
            return $this->transaction_item;
        }
        else
        {
            return NULL;
        }
    }

    public function set_transaction_item($value)
    {
        $this->transaction_item = $value;
    }

    public function get_transaction_category()
    {
        $t_cat = str_replace(',',' ',$this->transaction_category);
        $this->set_transaction_category_list($t_cat);
        $cat = explode('||', $this->transaction_category_list);
        if($cat[0])
        {
            $this->set_transaction_category($cat[0]);
            return $this->transaction_category;
        }
        else
        {
            return NULL;
        }
    }

    public function set_transaction_category($value)
    {
        $this->transaction_category = $value;
    }

    public function get_transaction_value()
    {
        $t_value = str_replace(',',' ',$this->transaction_value);
        $this->set_transaction_value_list($t_value);
        $value = explode('||', $this->transaction_value_list);
        if($value[0])
        {
            $this->set_transaction_value($value[0]);
            return number_format($this->transaction_value,2,'.','');
        }
        else
        {
            return NULL;
        }
    }

    public function set_transaction_value($value)
    {
        $this->transaction_value = $value;
    }

    public function get_transaction_profit()
    {
        $t_profit = str_replace(',',' ',$this->transaction_profit);
        $this->set_transaction_profit_list($t_profit);
        $profit = explode('||', $this->transaction_profit_list);
        if($profit[0])
        {
            $this->set_transaction_profit($profit[0]);
            return $this->transaction_profit;
        }
        else
        {
            return NULL;
        }

    }

    public function set_transaction_profit($value)
    {
        $this->transaction_profit = $value;
    }

    public function get_transaction_date_2()
    {
        $date = explode('||', $this->transaction_date_list);
        if($date[1])
        {
            $this->set_transaction_date_2($date[1]);
            return $this->transaction_date_2;
        }
        else
        {
            return NULL;
        }

    }

    public function set_transaction_date_2($value)
    {
        $this->transaction_date_2 = $value;
    }


    public function get_transaction_item_2()
    {
        $item = explode('||', $this->transaction_item_list);
        if($item[1])
        {
            $this->set_transaction_item_2($item[1]);
            return $this->transaction_item_2;
        }
        else
        {
            return NULL;
        }
    }

    public function set_transaction_item_2($value)
    {
        $this->transaction_item_2 = $value;
    }

    public function get_transaction_category_2()
    {
        $cat = explode('||', $this->transaction_category_list);
        if($cat[1])
        {
            $this->set_transaction_category_2($cat[1]);
            return $this->transaction_category_2;
        }
        else
        {
            return NULL;
        }
    }

    public function set_transaction_category_2($value)
    {
        $this->transaction_category_2 = $value;
    }

    public function get_transaction_value_2()
    {
        $value = explode('||', $this->transaction_value_list);
        if($value[1])
        {
            $this->set_transaction_value_2($value[1]);
            return number_format($this->transaction_value_2,2,'.','');

        }
        else
        {
            return NULL;
        }
    }

    public function set_transaction_value_2($value)
    {
        $this->transaction_value_2 = $value;
    }

    public function get_transaction_profit_2()
    {
        $profit = explode('||', $this->transaction_profit_list);
        if($profit[1])
        {
            $this->set_transaction_profit_2($profit[1]);
            return $this->transaction_profit_2;
        }
        else
        {
            return NULL;
        }
    }

    public function set_transaction_profit_2($value)
    {
        $this->transaction_profit_2 = $value;
    }

    public function get_transaction_date_3()
    {
        $date = explode('||', $this->transaction_date_list);
        if($date[2])
        {
            $this->set_transaction_date_3($date[2]);
            return $this->transaction_date_3;
        }
        else
        {
            return NULL;
        }

    }

    public function set_transaction_date_3($value)
    {
        $this->transaction_date_3 = $value;
    }

    public function get_transaction_item_3()
    {
        $item = explode('||', $this->transaction_item_list);
        if($item[2])
        {
            $this->set_transaction_item_3($item[2]);
            return $this->transaction_item_3;
        }
        else
        {
            return NULL;
        }
    }

    public function set_transaction_item_3($value)
    {
        $this->transaction_item_3 = $value;
    }

    public function get_transaction_category_3()
    {
        $cat = explode('||', $this->transaction_category_list);
        if($cat[2])
        {
            $this->set_transaction_category_3($cat[2]);
            return $this->transaction_category_3;
        }
        else
        {
            return NULL;
        }
    }

    public function set_transaction_category_3($value)
    {
        $this->transaction_category_3 = $value;
    }

    public function get_transaction_value_3()
    {
        $value = explode('||', $this->transaction_value_list);
        if($value[2])
        {
            $this->set_transaction_value_3($value[2]);
            return number_format($this->transaction_value_3,2,'.','');
        }
        else
        {
            return NULL;
        }
    }

    public function set_transaction_value_3($value)
    {
        $this->transaction_value_3 = $value;
    }

    public function get_transaction_profit_3()
    {
        $profit = explode('||', $this->transaction_profit_list);
        if($profit[2])
        {
            $this->set_transaction_profit_3($profit[2]);
            return $this->transaction_profit_3;
        }
        else
        {
            return NULL;
        }
    }

    public function set_transaction_profit_3($value)
    {
        $this->transaction_profit_3 = $value;
    }
}


/* End of file customer_extraction_item_list_dto.php */
/* Location: ./system/application/libraries/dto/customer_extraction_item_list_dto.php */