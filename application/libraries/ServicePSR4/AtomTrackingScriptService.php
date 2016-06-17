<?php
namespace ESG\Panther\Service;
use ESG\Panther\Service\LoadSiteParameterService;

abstract class AtomTrackingScriptService extends MultipageTrackingScriptService
{
    protected $current_page_info = array();
    protected $exchange_rate_service;
    protected $current_af = "";

    public function __construct()
    {
           
        include_once(APPPATH . "libraries/service/Exchange_rate_service.php");
        
        //$this->set_exchange_rate_service(new Exchange_rate_service());
    }

    /***************************************
     **  is_registered_page($page)
     **  return true if this controller/method need to display a specific tracking code
     ****************************************/
    abstract public function isRegisteredPage($page);

    /***************************************
     **  is_registered_page($page)
     **  return true if there exist tracking code to put into everypages
     ****************************************/
    abstract public function needToShowGenericTrackingPage();

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
    abstract public function getSpecificCode($page = array(), $var = array());

    /******************************************************
     **  get_all_page_code,
     **  return the generic tracking code for all the web pages
     *******************************************************/
    abstract public function getAllPageCode($page = array(), $var = array());

    protected function isBankTransferSuccessPage($page)
    {
        if ($page['class'] == "checkout_redirect_method") {
            //payment success / failure
            if ($page['method'] == "checkout_order_acknowledge_frame") {
                return true;
            }
        }
        return false;
    }

    protected function isPaymentSuccessPage($page)
    {
        if ($this->isPaymentResultPage($page)) {
            if ($page['method_parameter1'] == 1) {
                return true;
            } else if ($page['method'] == "order_confirmation") {
                //this is PH order confirmation page
                return true;
            }
        }
        return false;
    }

    protected function isPaymentResultPage($page)
    {
        if (($page['class'] == "checkout_redirect_method")
            || ($page['class'] == "Checkout")
            || ($page['class'] == "checkout_onepage")
        ) {
            //payment success / failure
            if (($page['method'] == "paymentResult")
                || ($page['method'] == "order_confirmation")
            ) {
                return true;
            }
        }
        return false;
    }

    protected function isPaymentFailurePage($page)
    {
        if ($this->isPaymentResultPage($page)) {
           
            if ($page['method_parameter1'] == 0) {
                return true;
            }
        }
        return false;
    }

    protected function isCheckoutPage($page)
    {
        if (($page['class'] == "checkout_onepage")
            && ($page['method'] == "index")
        ) {
            return true;
        }
        return false;
    }

    protected function isHomePage($page)
    {
        if ($page['class'] == "redirect_controller") {
            return true;
        }
        return false;
    }

    protected function isCatPage($page)
    {

        if ($page['class'] == "Cat") {
            return true;
        }
        return false;
    }

    protected function isMainproductPage($page)
    {
        if ($page['class'] == "MainProduct") {
            return true;
        }
        return false;
    }

    protected function isReviewPage($page)
    {
        if ($page['class'] == "ReviewOrder") {
            return true;
        }
        return false;
    }

    protected function isRaPage($page)
    {
        if ($page['class'] == "Cart") {
            return true;
        }
        return false;
    }

    protected function isAboutUs($page)
    {
       
        if (($page['class'] == "Display")
            && ($page['method'] == "view")
            && ($page['method_parameter1'] == "about_us")
        ) {
            return true;
        }
        return false;
    }

    protected function isConditionsOfUse($page)
    {
        if (($page['class'] == "Display")
            && ($page['method'] == "view")
            && ($page['method_parameter1'] == "conditions_of_use")
        ) {
            return true;
        }
        return false;
    }

    protected function isPrivacyPolicy($page)
    {
        if (($page['class'] == "Display")
            && ($page['method'] == "view")
            && ($page['method_parameter1'] == "privacy_policy")
        ) {
            return true;
        }
        return false;
    }

    protected function isShipping($page)
    {
        if (($page['class'] == "Display")
            && ($page['method'] == "view")
            && ($page['method_parameter1'] == "shipping")
        ) {
            return true;
        }
        return false;
    }

    protected function isFaq($page)
    {
        if (($page['class'] == "Display")
            && ($page['method'] == "view")
            && ($page['method_parameter1'] == "faq")
        ) {
            return true;
        }
        return false;
    }

    protected function isNewsletterThankYou($page)
    {
        if (($page['class'] == "Display")
            && ($page['method'] == "view")
            && ($page['method_parameter1'] == "newsletter_thank_you")
        ) {
            return true;
        }
        return false;
    }

    protected function isContactUsPage($page)
    {
        if ($page['class'] == "contact") {
            return true;
        }
        return false;
    }

    protected function isMyaccountPage($page)
    {
        if ($page['class'] == "Myaccount") {
            return true;
        }
        return false;
    }

    protected function isLoginPage($page)
    {
        if ($page['class'] == "Login") {
            return true;
        }
        return false;
    }

    protected function convertAmount($amount, $from_currency, $to_currency)
    {
        $ex_rate_obj = $this->sc["ExchangeRate"]->get(array("from_currency_id" => $from_currency, "to_currency_id" => $to_currency));
        if ($ex_rate_obj) {
            $ex_rate = $ex_rate_obj->getRate();
            return number_format(($amount * $ex_rate), 2, '.', '');
        }
        return $amount;
    }

    protected function getCurrentAffiliate()
    {
        return $_COOKIE["af"];
    }
    

    //jimmy update
    protected function set_exchange_rate_service($value){

        $this->exchange_rate_service=$value;
    }

    protected function get_exchange_rate_service(){

        return $this->exchange_rate_service;
    }

}
