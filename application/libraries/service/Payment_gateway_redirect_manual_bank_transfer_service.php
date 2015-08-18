<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once(APPPATH . "libraries/service/Payment_gateway_redirect_service.php");

class Payment_gateway_redirect_manual_bank_transfer_service extends Payment_gateway_redirect_service
{
    public function __construct($debug)
    {
        parent::__construct($debug);
    }

    public function get_payment_gateway_name()
    {
        return "m_bank_transfer";
    }

    public function prepare_get_url_request($payment_info = array(), &$request_data)
    {
        return "";
    }

    public function checkout($vars)
    {
        $this->send_email_notification_to_sale($vars);
        $this->unset_variable();
        $this->get_so_srv()->get_cart_srv()->set_cart_cookie(PLATFORMID);
        print $this->get_redirect_url(null, $response_data);
    }

    public function send_email_notification_to_sale($vars)
    {
        $product_list = "";
        $unique_number = $this->get_unique_order_number();
        $del_name = $vars["del_first_name"] . " " . $vars["del_last_name"];
        $del_address = $vars["del_address_1"] . " " . $vars["del_address_2"] . " " . $vars["del_address_3"];
        $tel = $vars["del_tel_1"] . $vars["del_tel_2"] . $vars["del_tel_3"];
//validation of user inputs
//if we develop further, we need to check every parameters.
//currently we check email and country ID only
        include_once(BASEPATH . 'plugins/My_plugin/my_input_helper.php');
        include_once(BASEPATH . 'plugins/My_plugin/validator/regex_validator.php');
        $input_helper = new My_input_helper();
        if (!$input_helper->is_valid(array("email" => array()), array(), $vars["email"]))
            $vars["email"] = "not_valid@client.com";
        $rex = new Regex_validator("/^[A-Z]{2}$/");
        if (!$rex->is_valid($vars["del_country_id"]))
            $vars["del_country_id"] = PLATFORMCOUNTRYID;

        $cart_list = $this->get_so_srv()->get_cart_srv()->get_detail(PLATFORMID);
        $amount = 0;
        $csv_item = "";
        foreach ($cart_list["cart"] as $line_no => $soi) {
            $product_list .= "SKU:" . $soi["sku"] . "x" . $soi["qty"] . " (" . $soi["name"] . ") - " . $soi["total"] . "<br>";
            $amount += ($soi["total"] + $soi["gst"]);
            if ($csv_item != "")
                $csv_item .= "<br>;;;;;;;;;";
            $csv_item .= ";" . $soi["sku"] . ";" . $soi["qty"] . ";" . $soi["name"] . ";" . $soi["total"];
        }
        $csv = $unique_number
            . ";" . $vars["email"]
            . ";" . $del_name
            . ";" . $vars["del_company"]
            . ";" . $del_address
            . ";" . $vars["del_city"]
            . ";" . $vars["del_postcode"]
            . ";" . $vars["del_country_id"]
            . ";" . $tel
            . ";" . $amount . $csv_item;

        if ($_COOKIE["af"]) {
            $af_info = " [AF=" . $_COOKIE["af"] . "]";
        } else {
            $af_info = "";
        }
        $subject = "[VB] " . PLATFORMID . " sales order - " . $unique_number . $af_info;
        $message = "unique identifier:" . $unique_number . "<br>";
        $message .= "Email:" . $vars["email"] . "<br>";
        $message .= "Name:" . $del_name . "<br>";
        $message .= "Company:" . $vars["del_company"] . "<br>";
        $message .= "Address:" . $del_address . "<br>";
        $message .= "City:" . $vars["del_city"] . "<br>";
        $message .= "PostCode:" . $vars["del_postcode"] . "<br>";
        $message .= "Country:" . $vars["del_country_id"] . "<br>";
        $message .= "Tel:" . $tel . "<br>";
        $message .= "Total Paid:" . $amount . "<br>";
        $message .= $product_list . "<br><br>";
        $message .= $csv;

        $receiver = "vb_ph_order@valuebasket.com.ph";
        $headers = "From: ph-sales@valuebasket.com\r\n";
        $headers .= "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type: text/html; charset=utf-8" . "\r\n";
        mail($receiver, $subject, $message, $headers);

        $vars["so_no"] = $unique_number;
        $_SESSION["M_TRANSFER_ORDER"] = $vars;
        $_SESSION["M_TRANSFER_ORDER_CART_LIST"] = $cart_list;
    }

    public function get_unique_order_number()
    {
        return "P" . (microtime(true) * 10000);
    }

    public function get_redirect_url($request_data, &$response_data)
    {
        return "checkout_redirect_method/order_confirmation";
    }

    public function process_payment_status($general_data, $get_data, &$so_number, &$data_from_pmgw, &$data_to_pmgw, &$so_data, &$sops_data, &$socc_data, &$sor_data)
    {
        return Payment_gateway_redirect_service::PAYMENT_STATUS_SUCCESS;
    }

    public function is_need_dm_service($is_fraud = false)
    {
        return parent::require_decision_manager($is_fraud);
    }

    public function payment_notification($input_data)
    {
        print "OK";
    }

    public function query_transaction($input_parameters = array(), &$data_from_pmgw, &$data_to_pmgw, &$so_data, &$socc_data, &$sops_data)
    {
        return TRUE;
    }

    public function process_success_action()
    {
        redirect($this->get_successful_page());
    }

    public function process_failure_action()
    {
        redirect($this->get_failure_page());
    }

    public function process_cancel_action()
    {
        redirect($this->get_cancel_page());
    }

    public function is_payment_need_credit_check($is_fraud = false)
    {
        return parent::is_payment_need_credit_check($is_fraud);
    }

    public function get_technical_support_email()
    {
        return "oswald@eservicesgroup.com";
    }
}



