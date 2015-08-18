<?php


abstract class Atom_tracking_script_service extends Multipage_tracking_script_service
{
    protected $current_page_info = array();
    protected $exchange_rate_service;
    protected $current_af = "";

    public function __construct()
    {
        include_once(APPPATH . "libraries/service/Exchange_rate_service.php");
        $this->set_exchange_rate_service(new Exchange_rate_service());
    }

    /***************************************
     **  is_registered_page($page)
     **  return true if this controller/method need to display a specific tracking code
     ****************************************/
    abstract public function is_registered_page($page);

    /***************************************
     **  is_registered_page($page)
     **  return true if there exist tracking code to put into everypages
     ****************************************/
    abstract public function need_to_show_generic_tracking_page();

    /******************************************
     **  page:cat
     **  var that pass to category page
     **  1. category_name
     **  2. category_id
     **
     **  var that pass to mainproduct page
     **  1. sku
     **  2. category -- category name
     **  3. brand -- brand name
     **  4. product_name
     **  5. product_description
     **  6. price
     **  7. currency
     **  8. url -- mainproduct url
     **  9. image_url -- url of the product image
     **
     **  var that pass to review order page
     **  1. products (array)
     **      1.1 sku
     **      1.2 unit_price
     **      1.3 currency
     **      1.4 product_name
     **      1.5 qty
     **      1.6 total -- qty * unit_price
     **  2. total_amount -- total amount of the order, include GST
     **
     **  var that pass to checkout page
     **  1. products (array)
     **      1.1 sku
     **      1.2 unit_price
     **      1.3 currency
     **      1.4 product_name
     **      1.5 qty
     **      1.6 total -- qty * unit_price
     **  2. total_amount -- so->amount
     **  3. so -- so_vo
     **
     **  var that pass to payment success page
     **  1 affiliate_name
     **  2 total_amount
     **  3 so (so_vo)
     **  4 soi (So_item_w_name_dto)
     **  5 sops (sops_vo)
     **  6 client_email
     *******************************************/
    abstract public function get_specific_code($page = array(), $var = array());

    /******************************************************
     **  get_all_page_code,
     **  return the generic tracking code for all the web pages
     *******************************************************/
    abstract public function get_all_page_code($page = array(), $var = array());

    public function get_exchange_rate_service()
    {
        return $this->exchange_rate_service;
    }

    public function set_exchange_rate_service($ex_rate_srv)
    {
        $this->exchange_rate_service = $ex_rate_srv;
    }

    protected function is_bank_transfer_success_page($page)
    {
        if ($page['class'] == "checkout_redirect_method") {
//payment success / failure
            if ($page['method'] == "checkout_order_acknowledge_frame") {
                return true;
            }
        }
        return false;
    }

    protected function is_payment_success_page($page)
    {
        if ($this->is_payment_result_page($page)) {
            if ($page['method_parameter1'] == 1) {
                return true;
            } else if ($page['method'] == "order_confirmation") {
//this is PH order confirmation page
                return true;
            }
        }
        return false;
    }

    protected function is_payment_result_page($page)
    {
        if (($page['class'] == "checkout_redirect_method")
            || ($page['class'] == "checkout")
            || ($page['class'] == "checkout_onepage")
        ) {
//payment success / failure
            if (($page['method'] == "payment_result")
                || ($page['method'] == "order_confirmation")
            ) {
                return true;
            }
        }
        return false;
    }

    protected function is_payment_failure_page($page)
    {
        if ($this->is_payment_result_page($page)) {
            if ($page['method_parameter1'] == 0) {
                return true;
            }
        }
        return false;
    }

    protected function is_checkout_page($page)
    {
        if (($page['class'] == "checkout_onepage")
            && ($page['method'] == "index")
        ) {
            return true;
        }
        return false;
    }

    protected function is_home_page($page)
    {
        if ($page['class'] == "redirect_controller") {
            return true;
        }
        return false;
    }

    protected function is_cat_page($page)
    {
        if ($page['class'] == "cat") {
            return true;
        }
        return false;
    }

    protected function is_mainproduct_page($page)
    {
        if ($page['class'] == "mainproduct") {
            return true;
        }
        return false;
    }

    protected function is_review_page($page)
    {
        if ($page['class'] == "review_order") {
            return true;
        }
        return false;
    }

    protected function is_ra_page($page)
    {
        if ($page['class'] == "cart") {
            return true;
        }
        return false;
    }

    protected function is_about_us($page)
    {
        if (($page['class'] == "display")
            && ($page['method'] == "view")
            && ($page['method_parameter1'] == "about_us")
        ) {
            return true;
        }
        return false;
    }

    protected function is_conditions_of_use($page)
    {
        if (($page['class'] == "display")
            && ($page['method'] == "view")
            && ($page['method_parameter1'] == "conditions_of_use")
        ) {
            return true;
        }
        return false;
    }

    protected function is_privacy_policy($page)
    {
        if (($page['class'] == "display")
            && ($page['method'] == "view")
            && ($page['method_parameter1'] == "privacy_policy")
        ) {
            return true;
        }
        return false;
    }

    protected function is_shipping($page)
    {
        if (($page['class'] == "display")
            && ($page['method'] == "view")
            && ($page['method_parameter1'] == "shipping")
        ) {
            return true;
        }
        return false;
    }

    protected function is_faq($page)
    {
        if (($page['class'] == "display")
            && ($page['method'] == "view")
            && ($page['method_parameter1'] == "faq")
        ) {
            return true;
        }
        return false;
    }

    protected function is_newsletter_thank_you($page)
    {
        if (($page['class'] == "display")
            && ($page['method'] == "view")
            && ($page['method_parameter1'] == "newsletter_thank_you")
        ) {
            return true;
        }
        return false;
    }

    protected function is_contact_us_page($page)
    {
        if ($page['class'] == "contact") {
            return true;
        }
        return false;
    }

    protected function is_myaccount_page($page)
    {
        if ($page['class'] == "myaccount") {
            return true;
        }
        return false;
    }

    protected function is_login_page($page)
    {
        if ($page['class'] == "login") {
            return true;
        }
        return false;
    }

    protected function convert_amount($amount, $from_currency, $to_currency)
    {
        $ex_rate_obj = $this->exchange_rate_service->get(array("from_currency_id" => $from_currency, "to_currency_id" => $to_currency));
        if ($ex_rate_obj) {
            $ex_rate = $ex_rate_obj->get_rate();
            return number_format(($amount * $ex_rate), 2, '.', '');
        }
        return $amount;
    }

    protected function get_current_affiliate()
    {
        return $_COOKIE["af"];
    }
}
