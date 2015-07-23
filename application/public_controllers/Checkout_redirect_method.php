<?php

$ws_array = array(NULL, 'index');
if (in_array($GLOBALS["URI"]->segments[2], $ws_array)) {
    DEFINE ('ALLOW_REDIRECT_DOMAIN', 1);
}

include_once(CTRLPATH . "checkout.php");

class Checkout_redirect_method extends Checkout
{
    public $checkout_model;
    private $_debug = 0;
    private $_payment_type = "";    //which payment gateway to use
    private $_card_type = "";           //which card to use
    private $_card_code = "";       //which payment gateway-card to use

    public function Checkout_redirect_method()
    {
// We do not need the parent method for the payment_notification method
// Especially no need https redirection for altapay payment_form & payment_response, testing environment has no valid SSL cert
        if ((stripos($_SERVER['REQUEST_URI'], '/payment_notification') === false)
            && (stripos($_SERVER['REQUEST_URI'], '/payment_form') === false)
            && (stripos($_SERVER['REQUEST_URI'], '/payment_redirect_form') === false)
            && (stripos($_SERVER['REQUEST_URI'], '/payment_response') === false)
        ) {
            parent::Checkout();
        } else {
            parent::Checkout(false);
        }

        $this->_payment_type = $this->input->get_post("payment_type");
        $this->_card_type = $this->input->get_post("card_type");
        $this->_card_code = $this->input->get_post("card_code");

        if ($this->input->get_post("debug") == 1)
            $this->_debug = 1;
        else
            $this->_debug = 0;

        if ($this->_payment_type) {
            $this->_set_checkout_model($this->_payment_type, $this->_debug);
        }
    }

    /***************************************
     *   set_checkout_model, this function should be called for checkout page
     *   to get the correct payment gatway model
     ****************************************/
    private function _set_checkout_model($payment_type, $debug = 0)
    {
        switch ($payment_type) {
            case 'moneybookers_ctpe':
                include_once(APPPATH . "models/website/checkout_redirect_moneybookers_ctpe_model.php");
                $this->checkout_model = new Checkout_redirect_moneybookers_ctpe_model($debug);
                break;
            case 'worldpay':
                include_once(APPPATH . "models/website/checkout_redirect_worldpay_model.php");
                $this->checkout_model = new Checkout_redirect_worldpay_model($debug);
                break;
            case 'cybersource':
                include_once(APPPATH . "models/website/checkout_redirect_cybersource_model.php");
                $this->checkout_model = new Checkout_redirect_cybersource_model($debug);
                break;
            case 'm_bank_transfer':
                include_once(APPPATH . "models/website/checkout_redirect_manual_bank_transfer_model.php");
                $this->checkout_model = new Checkout_redirect_manual_bank_transfer_model($debug);
                break;
            case 'w_bank_transfer':
                include_once(APPPATH . "models/website/checkout_redirect_website_bank_transfer_model.php");
                $this->checkout_model = new Checkout_redirect_website_bank_transfer_model($debug);
                break;
            case 'trustly':
                include_once(APPPATH . "models/website/checkout_redirect_trustly_model.php");
                $this->checkout_model = new Checkout_redirect_trustly_model($debug);
                break;
            case 'inpendium_ctpe':
                include_once(APPPATH . "models/website/checkout_redirect_inpendium_ctpe_model.php");
                $this->checkout_model = new Checkout_redirect_inpendium_ctpe_model($debug);
                break;
            case 'yandex':
                include_once(APPPATH . "models/website/checkout_redirect_yandex_model.php");
                $this->checkout_model = new Checkout_redirect_yandex_model($debug);
                break;
            case 'global_collect':
                include_once(APPPATH . "models/website/checkout_redirect_global_collect_model.php");
                $this->checkout_model = new Checkout_redirect_global_collect_model($debug);
                break;
            case 'altapay':
                include_once(APPPATH . "models/website/checkout_redirect_altapay_model.php");
                $this->checkout_model = new Checkout_redirect_altapay_model($debug);
                break;
            case 'adyen':
                include_once(APPPATH . "models/website/checkout_redirect_adyen_model.php");
                $this->checkout_model = new Checkout_redirect_adyen_model($debug);
                break;
            default:
                /*
                    This will use checkout.php $this->checkout_model
                */
        }
    }

    public function empty_page()
    {
        $this->load_view('checkout/empty_page', null);
    }

    public function get_debug()
    {
        return $this->_debug;
    }

    public function payment_form()
    {
        $this->load_view('checkout/payment_form', null);
    }

    public function payment_redirect_form()
    {
        $this->load_view('checkout/payment_redirect_form', null);
    }

    public function query_transaction($so_no)
    {
        if (empty($so_no))
            exit;
//      $this->checkout_model->query_transaction_in_general($so_no);
    }

    /****************************************
     * function sumbit_to_payment_gateway
     * for payment gateway that need post form directly in order to pay
     *****************************************/
    public function sumbit_to_payment_gateway($payment_type, $so_number, $card_type)
    {
        $this->_payment_type = $payment_type;

        if ($this->input->get("debug") == 1)
            $this->_debug = 1;
        else
            $this->_debug = 0;
        $this->_set_checkout_model($this->_payment_type, $this->_debug);
        $input_variable = array("card_type" => $card_type);
        $data["form_data"] = $this->checkout_model->pmgw_redirect_service->post_to_payment_gateway($so_number, $input_variable);
        $data["form_action"] = $this->checkout_model->pmgw_redirect_service->get_form_action();
        if (!$data["form_data"]) {
            show_404();
        } else
            $this->load_view('checkout/submit_form', $data);
    }

    /****************************************
     * function test_ctpe
     * for testing purpose
     *****************************************/
    public function test_ctpe()
    {
        $data["debug"] = $this->_debug;
        $data += $this->checkout_model->psform_content();
//      unset($_SESSION["cart"]);
//      var_dump($_SESSION["cart"]);
        $this->load_view('checkout/test_ctpe_payment', $data);
    }

    public function payment_result_top($success = "", $so_no = "")
    {
        if ($_SERVER["QUERY_STRING"])
            $queryString = "?" . $_SERVER["QUERY_STRING"];
        else
            $queryString = "";

        $data['url'] = base_url() . "checkout_redirect_method/payment_result/" . $success . "/" . $so_no . $queryString;
        $this->load_view('checkout/payment_result_top', $data);
    }

    public function payment_cancel_top()
    {
        if ($_SERVER["QUERY_STRING"])
            $queryString = "&" . $_SERVER["QUERY_STRING"];
        else
            $queryString = "";

        $data['url'] = base_url() . "checkout_onepage?cancel_from_pmgw=1" . $queryString;
        $this->load_view('checkout/payment_result_top', $data);
    }

    /*******************************
     * unset the cart only when we got the success page load
     ********************************/
    public function payment_result($success = "", $so_no = "")
    {
        if ($success == 1) {
            unset($_SESSION["cart"]);
            unset($_SESSION["cart_from_url"]);
            unset($_SESSION["ra_items"]);
            unset($_SESSION["warranty"]);
            unset($_SESSION["promotion_code"]);
            unset($_SESSION["POSTFORM"]);
            include_once(APPPATH . "hooks/country_selection.php");
            $country_selection = new Country_selection();
//  set the cart cookie, to rebuild cart if domain changes
            $country_selection->set_cart_cookie("");
        }
        parent::payment_result($success, $so_no);
    }

    /************************************************
     *   confirmation page for bank transfer option (Philippines)
     *
     ************************************************/
    public function order_confirmation()
    {
//      var_dump($_SESSION["M_TRANSFER_ORDER"]);
//      var_dump($_SESSION["M_TRANSFER_ORDER_CART_LIST"]);

        if ($_SESSION["M_TRANSFER_ORDER"]) {
            $data["client_email"] = $_SESSION["M_TRANSFER_ORDER"]["email"];
            $data["client_name"] = $_SESSION["M_TRANSFER_ORDER"]["del_first_name"] . " " . $_SESSION["M_TRANSFER_ORDER"]["del_last_name"];
            $data["address"] = $_SESSION["M_TRANSFER_ORDER"]["del_address_1"] . " " . $_SESSION["M_TRANSFER_ORDER"]["del_address_2"] . " " . $_SESSION["M_TRANSFER_ORDER"]["del_address_3"];
            $data["city"] = $_SESSION["M_TRANSFER_ORDER"]["del_city"];
            $data["postcode"] = $_SESSION["M_TRANSFER_ORDER"]["del_postcode"];
            $data["country_id"] = $_SESSION["M_TRANSFER_ORDER"]["del_country_id"];
            $country_obj = $this->checkout_model->region_service->country_dao->get(array("id" => $data["country_id"]));
            $data["country_name"] = $country_obj->get_name();
            $data["telephone"] = $_SESSION["M_TRANSFER_ORDER"]["del_tel_1"] . " " . $_SESSION["M_TRANSFER_ORDER"]["del_tel_2"] . " " . $_SESSION["M_TRANSFER_ORDER"]["del_tel_3"];

            $data["so_no"] = $_SESSION["M_TRANSFER_ORDER"]["so_no"];
            $item_detail = "";
            $soi_list = array();
            include_once(APPPATH . "libraries/dto/so_item_w_name_dto.php");

            foreach ($_SESSION["M_TRANSFER_ORDER_CART_LIST"]["cart"] as $item) {
                $item_detail .= $item["qty"] . " x " . $item["name"] . "<br>";
                $total_amount = ($item["total"] + $item["gst"]);
                $soi = new So_item_w_name_dto();
                $soi->set_so_no($_SESSION["M_TRANSFER_ORDER"]["so_no"]);
                $soi->set_name($item["name"]);
                $soi->set_unit_price($item["total"]);

                $soi->set_qty($item["qty"]);
                $soi->set_website_status("I");
                $soi->set_prod_sku($item["sku"]);
                array_push($soi_list, $soi);
            }
            $data["item_detail"] = $item_detail;

//prepare google tag manager data
            $af_info = $this->affiliate_service->get_af_record();
            $data["tracking_data"]["affiliate_name"] = $af_info["af"];

            $data["tracking_data"]["total_amount"] = $total_amount;
            include_once(APPPATH . "libraries/vo/so_vo.php");
            $so_vo = new So_vo();
            $so_vo->set_order_create_date(date('Y-m-d H:i:s'));
            $so_vo->set_so_no($_SESSION["M_TRANSFER_ORDER"]["so_no"]);
            $so_vo->set_biz_type("ONLINE");
            $so_vo->set_delivery_charge(0);
            $so_vo->set_currency_id("PHP");
            $so_vo->set_promotion_code($data["postcode"]);

            $data["tracking_data"]["so"] = $so_vo;
            $data["tracking_data"]["soi"] = $soi_list;

            include_once(APPPATH . "libraries/vo/so_payment_status_vo.php");
            $sops = new So_payment_status_vo();
            $sops->set_payment_gateway_id('m_bank_transfer');
            $data["tracking_data"]["sops"] = $sops;

            $this->affiliate_service->remove_af_record();

            $this->load_tpl('content', 'checkout/checkout_order_confirmation', $data, TRUE);
        }
    }

    /************************************************
     *   confirmation page for website bank transfer option, opens in iframe
     * thus cannot use load_tpl(), needs str_replace in template instead
     *
     ************************************************/
    public function order_acknowledge_frame()
    {
        include_once(APPPATH . "libraries/dto/so_item_w_name_dto.php");
        include_once(APPPATH . "libraries/service/complementary_acc_service.php");
        $ca_srv = new Complementary_acc_service();
        $replace = $search = $data = array();


        if ($_SESSION["W_TRANSFER_ORDER"]) {
            // echo "<pre>"; var_dump($_SESSION["client"]); die();
            $data["client_id"] = $_SESSION["client"]["id"];
            $data["client_email"] = $_SESSION["client"]["email"];
            $data["client_name"] = $_SESSION["client"]["forename"] . " " . $_SESSION["client"]["surname"];
            $data["address"] = $_SESSION["client"]["address_1"] . " " . $_SESSION["client"]["address_2"] . " " . $_SESSION["client"]["address_3"];
            $data["city"] = $_SESSION["client"]["city"];
            $data["postcode"] = $_SESSION["client"]["postcode"];
            $data["country_id"] = $_SESSION["client"]["country_id"];
            $country_obj = $this->checkout_model->region_service->country_dao->get(array("id" => $data["country_id"]));
            $data["country_name"] = $country_obj->get_name();
            $data["telephone"] = $_SESSION["client"]["tel_1"] . " " . $_SESSION["client"]["tel_2"] . " " . $_SESSION["client"]["tel_3"];
            $data["so_no"] = $_SESSION["W_TRANSFER_ORDER"]["so_no"];
            $item_detail = "";
            $soi_list = array();
            $total_so_amount = platform_curr_format(PLATFORMID, $_SESSION["W_TRANSFER_ORDER"]["total_so_amount"]);

            include_once(APPPATH . "libraries/dao/so_item_dao.php");
            $soi_dao = new So_item_dao();
            if ($soi_items = $soi_dao->get_list(array("so_no" => $data["so_no"]))) {
                foreach ($soi_items as $item) {
                    $is_complementary_acc = $ca_srv->check_cat($item->get_prod_sku(), true);
                    if ($is_complementary_acc["status"] === true) {
                        continue;
                    }

                    $item_detail .= $item->get_qty() . " x " . $item->get_prod_name() . "<br>";
                    $total_amount += $item->get_amount();

                    $soi = new So_item_w_name_dto();
                    $soi->set_so_no($data["so_no"]);
                    $soi->set_name($item->get_prod_name());
                    $soi->set_unit_price($item->get_amount());
                    $soi->set_qty($item->get_qty());
                    $soi->set_website_status("I");
                    $soi->set_prod_sku($item->get_prod_sku());
                    array_push($soi_list, $soi);
                }
            }
            $item_detail .= "<br><b>TOTAL: $total_so_amount</b><br>";
            $data["item_detail"] = $item_detail;
            $replace = $this->replace = $data;
            $replace["lang_country_pair"] = lang_part();
            $replace["site_url"] = $_SERVER["HTTP_HOST"];

//prepare google tag manager data
            $af_info = $this->affiliate_service->get_af_record();
            $data["tracking_data"]["affiliate_name"] = $af_info["af"];
            $data["tracking_data"]["total_amount"] = $total_amount;
            include_once(APPPATH . "libraries/dao/so_dao.php");
            $so_dao = new So_dao();
            $so_obj = $so_dao->get(array("so_no" => $data["so_no"]));

            $data["tracking_data"]["so"] = $so_obj;
            $data["tracking_data"]["soi"] = $soi_list;
            include_once(APPPATH . "libraries/dao/so_payment_status_dao.php");
            $sops_dao = new So_payment_status_dao();
            $sops_obj = $sops_dao->get(array("so_no" => $data["so_no"]));
            $data["tracking_data"]["sops"] = $sops_obj;

            $this->affiliate_service->remove_af_record();
//          $this->load_tpl('content', 'checkout/checkout_order_confirmation', $data, TRUE);
        }

        # SBF #3836 - templates by country, no longer need to merge with ini files
        // $lang_id = $_SESSION["lang_id"];
        // $replace = $this->get_template_content_arr($lang_id, "order_acknowledge_frame");

        foreach ($replace as $rskey => $rsvalue) {
            $search[] = "[:" . $rskey . ":]";
            $value[] = $rsvalue;
        }

        include_once(APPPATH . "libraries/service/google_tag_manager_tracking_script_service.php");
        $tracking_obj = new Google_tag_manager_tracking_script_service();
        $tracking_code = $tracking_obj->get_all_page_code(array("class" => "checkout_redirect_method", "method" => "checkout_order_acknowledge_frame"), array());
        $search[] = "[:tracking:]";
        $value[] = $tracking_code;
        $template_path = APPPATH . "public_views/checkout/checkout_order_acknowledge_frame_" . strtolower($data["country_id"]) . ".php";

        if (is_file($template_path)) {
            $template = file_get_contents($template_path);
            $content = str_replace($search, $value, $template);
        }

        # this will clear shopping cart
        unset($_SESSION["cart"]);
        unset($_SESSION["cart_from_url"]);
        unset($_SESSION["ra_items"]);
        unset($_SESSION["warranty"]);
        unset($_SESSION["promotion_code"]);
        unset($_SESSION["POSTFORM"]);

        echo $content;
    }

    /******************************************
     *   payment_success, display static successful page
     *
     ******************************************/
    public function payment_success()
    {
        redirect("/index");
    }

    /******************************************************
     *   function payment_response, once payment completed, either success or fail,
     *   this should be the function to call get do the correct database operation and display
     *   the correct payment page
     *******************************************************/
    public function payment_response()
    {
        $this->checkout_model->process_payment_status_in_general($_POST, $_GET);
    }

    /*****************************************************
     *   payment_notification, only for WorldPay, Trustly temporily
     ******************************************************/
    public function test_trustly_notification()
    {
//      $this->checkout_model->pmgw_redirect_service->test_notification();
    }

    public function trustly_payment_notification($payment_type, $debug = 0)
    {
        if (count($this->router->uri->rsegments) >= 3) {
            $this->_payment_type = $this->router->uri->rsegments[3];
            if (count($this->router->uri->rsegments) >= 4)
                $this->_debug = $this->router->uri->rsegments[4];
            else
                $this->_debug = 0;

            if ($this->_payment_type == "trustly") {
                $this->_set_checkout_model($this->_payment_type, $this->_debug);
                $json_data = file_get_contents('php://input');
                $this->checkout_model->payment_notification($json_data);
            }
        }
    }

    public function payment_notification()
    {
        if ($this->_payment_type == "yandex" || $this->_payment_type == "adyen") {
            $this->checkout_model->payment_notification($_POST);
        } else {
            $xml = file_get_contents('php://input');
            $this->checkout_model->payment_notification($xml);
        }
    }

    /**************************************************************************************
     *   function process_redirect_checkout(), the entry point for a redirect model payment gateway
     *   calling by javascript, not redirect by php, but javascript
     *   1. this should call set_checkout_model to get the correct payment gatway
     *   2. get the return url and do redirect by javascript
     *   3. ajax call, so no load view, or return, always print/echo result
     *   4. $this->_payment_type //payment gateway
     *   5. $_POST["card_type"] //card type, e.g. VISA, MASTER, ...
     *   6. $_POST["payment_methods"] //table pmgw_card "code" field
     *   7. $vars["ajax_handler"], this will determine how we handle the failure
     ***************************************************************************************/
    public function process_redirect_checkout()
    {
        if ((!isset($_SESSION["cart"][PLATFORMID])) || (!isset($_SESSION['client']) && !isset($_POST['email']))) {
            print "SESSION: Session not available";
            exit;
            //print "ERROR: unknown error";
        }

        if (get_class($this->checkout_model) == 'Checkout_model') {
            $_POST['payment_methods'] = $this->_card_type;
            parent::process_checkout($this->_card_code, $this->_debug);
            exit;
        }
        $_SESSION["POSTFORM"] = $vars = $_POST;
        if (get_class($this->checkout_model) == 'Checkout_redirect_manual_bank_transfer_model') {
            $return_url = $this->checkout_model->pmgw_redirect_service->checkout($vars);
            exit;
        }

        if ($this->_payment_type == 'w_bank_transfer') {
            $vars["ajax_handler"] = 0;
        } else {
            $vars["ajax_handler"] = 1;
        }
        $vars["platform_id"] = $this->_get_platform_id($_SESSION["POSTFORM"]["p_enc"]);
        $vars["payment_gateway"] = $this->_payment_type;
        $vars["payment_methods"] = $this->_card_type;
        $vars["language_id"] = get_lang_id();

        if ($_SESSION["client"]["logged_in"]) {
            $vars["email"] = $_SESSION["client"]["email"];
        } else {
            if (empty($vars["password"]))
                $vars["password"] = mktime();
        }
//      var_dump($_POST);
        // var_dump($_SESSION["client"]);die();
//      exit;
//we need billaddr, if not, shipping address will be copied to billing address
        $vars["billaddr"] = 1;

        if ($this->client_service->check_email_login($vars)) {
            if ($this->checkout_model->check_promo()) {
                $return_url = $this->checkout_model->pmgw_redirect_service->checkout($vars);
//              print $return_url;
//              if ($return_url)
                exit;
            } else {
                unset($_SESSION["promotion_code"]);
                echo "
                    <script>
                        window.parent.ChgPromoMsg(0, 1);
                    </script>
                    ";
                exit;
            }
        }
        print "ERROR: unknown error";
    }

    private function _get_platform_id($p_enc)
    {
        if ((isset($p_enc)) && (!empty($p_enc))) {
            include_once(BASEPATH . "libraries/Encrypt.php");
            $encrypt = new CI_Encrypt();
            $platform_id = $encrypt->decode($p_enc);
            if ($this->so_service->get_pbv_srv()->selling_platform_dao->get(array("id" => $platform_id))) {
                $return_id = $platform_id;
            } else {
                return FALSE;
            }
        }

        if (!isset($return_id)) {
            $return_id = PLATFORMID;
        }
        return $return_id;
    }

    private function get_template_content_arr($lang_id = 'en', $template)
    {
        $language_path = $lang_id . "/" . "checkout_redirect_method/$template.ini";
        if (file_exists(APPPATH . "language/" . $language_path)) {
            $data_arr = parse_ini_file(APPPATH . "language/" . $language_path);
        }

        if (!is_null($data_arr)) {
            foreach ($data_arr as $key => $value) {
                $key_append = "lang_text_" . $key;
                $data_arr[$key_append] = $value;
                unset($data_arr[$key]);
            }

            if ($this->replace) {
                $data_arr = array_merge($this->replace, $data_arr);
            }
        }

        return $data_arr;
    }
}

?>
