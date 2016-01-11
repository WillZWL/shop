<?php

class Common_data_prepare_model extends CI_Model
{
    public $controller = null;
    public $lang_id = 'en';

    public function __construct()
    {
        parent::__construct();

        $this->lang_id = strtolower(substr(PLATFORM, 3));

        // Don't load any helper/service here in order to reduce resource consumption
        // Load anything you need inside the method itself
    }

    public function set_lang_id($lang_id)
    {
        $this->set_lang_id($lang_id);
    }

    public function get_lang_id()
    {
        return $this->lang_id;
    }

    public function get_data_array($controller = null, $url_paras = array(), $i_class = '', $method = '')
    {
        if (empty($i_class)) {
            $input_class = $this->router->class;
        } else {
            $input_class = $i_class;
        }

        if (empty($method)) {
            $input_method = $this->router->method;
        } else {
            $input_method = $method;
        }

        $call_method = $input_class . '__' . $input_method;
        if (method_exists($this, $call_method)) {

            return $this->$call_method(($controller == null) ? "" : $controller, $url_paras);
        } else {
            return array();
        }
    }

    public function checkout_onepage__ajax_cal_del_surcharge($controller, $url_paras)
    {
        $total = 0;
        $data = $this->checkout_model->index_content();

        $chk_cart = $data['chk_cart'];
        for ($i = 0; $i < count($chk_cart); $i++) {
            $total += $chk_cart[$i]["price"] * $chk_cart[$i]["qty"];
            $total += $chk_cart[$i]["gst"];
        }

        $promo_disc_amount = '';
        $promo = $data["promo"];
        if ($promo["valid"] && isset($promo["disc_amount"])) {
            $promo_disc_amount = $promo["disc_amount"];
        }
        $grand_total = $total + $data["dc_default"]["charge"] - $promo_disc_amount;

        $result = $this->checkout_onepage__calculate_delivery_surcharge($controller, $url_paras);
        if ($result !== FALSE) {
            echo platform_curr_format($result['surcharge']) . '||' . platform_curr_format(($grand_total + $result['surcharge']));
        } else {
            echo '||' . platform_curr_format($grand_total);
        }
    }

    protected function checkout_onepage__calculate_delivery_surcharge($controller, $url_paras)
    {
        if (($url_paras["country_id"] == '') || ($url_paras["postcode"] == '')) {
            return FALSE;
        }

        $this->delivery_service->delivery_country_id = trim($url_paras["country_id"]);
        $this->delivery_service->delivery_postcode = trim($url_paras["postcode"]);

        $this->delivery_service->item_list = $_SESSION["cart"][PLATFORM];
        if (($rs = $this->delivery_service->get_del_surcharge(TRUE)) && $rs["surcharge"]) {
            $result = array('currency_id' => $rs["currency_id"], 'surcharge' => $rs["surcharge"]);
            return $result;
        } else {
            return FALSE;
        }
    }

    protected function checkout_onepage__is_same_listing_changing_country($controller, $url_paras)
    {
        $platform_obj = $this->platform_biz_var_service->get_list_w_country_name(array("pbv.selling_platform_id like 'WEB%'" => NULL, 'c.id' => $new_country_id), array('limit' => 1));
        $platform_id = $platform_obj->get_selling_platform_id();

        $sku_list = array();
        foreach ($_SESSION['cart'][PLATFORM] as $sku => $qty) {
            $sku_list[] = $sku;
        }

        if (sizeof($sku_list) > 0) {
            $listing_list = $this->price_service->get_listing_info_list($sku_list, $platform_id);

            foreach ($listing_list as $obj) {
                if ($obj === FALSE) {
                    echo 'FALSE';
                    exit;
                } elseif (!(($obj->get_status() == 'I') && ($obj->get_qty() > 0))) {
                    echo 'FALSE';
                    exit;
                }
            }
        }
        echo 'TRUE';
    }

    protected function checkout_onepage__index($controller, $url_paras)
    {
        $data = $this->checkout_model->index_content();
        if (!$data['cart_item']) {
            Redirect(base_url() . "review_order");
        }

        if (!$_SESSION["client"]["logged_in"]) {
            $controller->set_current_step(Checkout_onepage::STEP_LOGIN);
            $data["no_login_before"] = true;
        } else {
            if (PLATFORM == "WEBPH")
                $controller->set_current_step(Checkout_onepage::STEP_SHIPPING_INFORMATION);
            else
                $controller->set_current_step(Checkout_onepage::STEP_BILLING_INFORMATION);
            $data["no_login_before"] = false;
            $data["client"] = $_SESSION["client"];
        }
        $data["state_list"] = $this->country_service->get_country_state_srv()->get_list(array("status" => 1), array("limit" => -1, "array_list" => 1, "orderby" => "country_id"));
        $data["current_step"] = $controller->get_current_step();
        $data["next_step"] = $controller->get_next_step($controller->get_current_step());
        $data["active_block_id"] = $controller->getBlockTitle($controller->get_current_step());

//allow login is for PH checkout only
        $data["allow_login"] = $controller->getAllowLogin();
        $data["display_block_list"] = $controller->getDisplayBlockList();
        $data["show_block"] = $controller->getBlockStyle();
        $data["country_list"] = $this->checkout_model->get_allow_sell_country();

        $data["cart"] = $_SESSION["cart"];

        if ($_SESSION["cart"][PLATFORM]) {
            include_once(APPPATH . "helpers/string_helper.php");
            $data['chk_cart_cookie'] = base64_encode(serialize($_SESSION["cart"][PLATFORM]));
        }

        $total = 0;
        $cart_item = $data['cart_item'];
        $chk_cart = $data['chk_cart'];

// SBF #2236, GST checking
        $need_gst_display = FALSE;
        if (PLATFORMCOUNTRYID == 'NZ') {
            $need_gst_display = TRUE;
        }

        $gst_total = 0;
        $gst_order = FALSE;
        if ($need_gst_display) {
            foreach ($chk_cart AS $key => $val) {
                $gst_total += $val["gst"];
            }

            if ($gst_total > 0) {
                $gst_order = TRUE;
            }
        }

        $data["need_gst_display"] = $need_gst_display;
        $data["gst_order"] = $gst_order;
        // End of SBF #2236, GST checking

        $total = 0;
        for ($j = 0; $j < count($chk_cart); $j++) {
            $total += $chk_cart[$j]["price"] * $chk_cart[$j]["qty"];
        }

        $promo_disc_amount = '';
        $promo = $data["promo"];
        if ($promo["valid"] && isset($promo["disc_amount"])) {
            $promo_disc_amount = $promo["disc_amount"];
        }
        $data['subtotal'] = $total;
        $data['gst_total'] = $gst_total;
        $data['promo_disc_amount'] = $promo_disc_amount;
        $data['grand_total'] = $total + $data["dc_default"]["charge"] - $promo_disc_amount + $gst_total;

        //if (isset($client["country_id"]))
        //  $selected_country = $client["country_id"];
        //else
        //  $selected_country = $_SESSION['domain_platform']['platform_country_id'];
        $data['selected_country'] = $_SESSION['domain_platform']['platform_country_id'];

        switch ($data['selected_country']) {
            case 'GB' :
                $check_pobox_amount = 100;
                break;
            case 'IE' :
                $check_pobox_amount = 100;
                break;
            case 'FR' :
                $check_pobox_amount = 200;
                break;
            case 'ES' :
                $check_pobox_amount = 100;
                break;
            case 'FI' :
                $check_pobox_amount = 200;
                break;
            case 'MT' :
                $check_pobox_amount = 100;
                break;
            case 'IT' :
                $check_pobox_amount = 100;
                break;
            case 'US' :
                $check_pobox_amount = 100;
                break;
            case 'AU' :
                $check_pobox_amount = 100;
                break;
            case 'NZ' :
                $check_pobox_amount = 280;
                break;
            case 'HK' :
                $check_pobox_amount = 2000;
                break;
            case 'CH' :
                $check_pobox_amount = 240;
                break;
            case 'PT' :
                $check_pobox_amount = 60;
                break;
            case 'RU' :
                $check_pobox_amount = 2300;
                break;
            case 'PL' :
                $check_pobox_amount = 250;
                break;
            default   :
                $check_pobox_amount = 0;
        }

        if ($data['grand_total'] > $check_pobox_amount) {
            $data['allow_pobox'] = false;
        } else {
            $data['allow_pobox'] = true;
        }

        $this->template->add_js('/js/checkform.js');
        $this->template->add_js('/js/checkout.js');
        $this->template->add_js('/js/common.js');
        $this->template->add_js('/js/payment_gateway.js');

        include_once(APPPATH . "libraries/service/cybersource/cybersource_integrator.php");
        $cybs_integrator = new Cybersource_integrator();
        $merchant_info = $cybs_integrator->get_merchant_Id($data['selected_country'], $_SESSION["domain_platform"]["platform_currency_id"]);
        $data["cybersource_fingerprint"] = session_id();
        $data["cybersource_fingerprint_label"] = $merchant_info["merchantId"] . session_id();
        $data["debug"] = $controller->get_debug();
        $data["cybersource_fingerprint_id"] = $cybs_integrator->get_fingerprint_org_id($data["debug"]);

        $data += $this->checkout_model->psform_content();

        $data["show_client_id"] = FALSE;
        if ((PLATFORMCOUNTRYID == "ES") || (PLATFORMCOUNTRYID == "RU")) {
            $data["show_client_id"] = TRUE;
        }

        $this->checkout_model->prepare_js_credit_card_parameter($data);
        $this->template->add_js('/checkout_redirect_method/js_credit_card/' . $data['platform_curr'] . '/' . $data['total_amount']);

        return $data;
    }

    protected function review_order__update($controller, $url_paras)
    {
        return $this->review_order__index($controller, $url_paras);
    }

    protected function review_order__index($controller, $url_paras)
    {
        $data['lang_text'] = $controller->get_language_file('', '', 'index');
        $item_status = $this->input->get('item_status');
        $removed_sku = $this->input->get('not_valid_sku');
        $need_restriction_message = false;

        if (isset($item_status) && isset($removed_sku)) {
            require_once(BASEPATH . 'plugins/My_plugin/validator/digits_validator.php');
            $digits_validator_allow_empty = new Digits_validator(array("allow_empty" => true));

            require_once(BASEPATH . 'plugins/My_plugin/validator/regex_validator.php');
            $regex_validator = new Regex_validator(Regex_validator::REGX_SKU_FORMAT);

            if ($digits_validator_allow_empty->is_valid($item_status) && $regex_validator->is_valid($removed_sku)) {
                if (($item_status == Cart_session_service::NOT_ALLOW_PREORDER_ARRIVING_ITEM_AFTER_NORMAL_ITEM)
                    || ($item_status == Cart_session_service::NOT_ALLOW_NORMAL_ITEM_AFTER_PREORDER_ARRIVING_ITEM)
                    || ($item_status == Cart_session_service::DIFFERENT_PREORDER_ITEM)
                    || ($item_status == Cart_session_service::DIFFERENT_ARRIVING_ITEM)
                ) {
                    $need_restriction_message = true;
                }
            }
        }

        if (!$need_restriction_message) {
            $data['lang_text']['restriction'] = "";
        }

        if (isset($_POST["promotion_code"])) {
            if ($this->input->post("promotion_code")) {
                $_SESSION["promotion_code"] = $_POST["promotion_code"];
                $email = $this->input->post("email");
                $_SESSION["POSTFORM"]["email"] = $email;
                $this->cart_session_service->set_email($email);
            } else {
                unset($_SESSION["promotion_code"]);
            }
        } elseif (isset($_POST["del_country_id"])) {
            $_SESSION["POSTFORM"] = $_POST;
        }

        $result = $this->cart_session_model->get_detail(PLATFORM);
        $item_total = 0;

        // SBF #2236, GST checking
        $need_gst_display = FALSE;
        if (PLATFORMCOUNTRYID == 'NZ') {
            $need_gst_display = TRUE;
        }

        $gst_total = 0;
        $gst_order = FALSE;
        if ($need_gst_display) {
            foreach ($result["cart"] AS $key => $val) {
                $gst_total += $val["gst"];
            }

            if ($gst_total > 0) {
                $gst_order = TRUE;
            }
        }

        $data["need_gst_display"] = $need_gst_display;
        $data["gst_order"] = $gst_order;

        $data["allow_bulk_sales"] = FALSE;
        foreach ($result["cart"] AS $key => $val) {
            $item_total += $val["total"];
            $data["cart"][$val["sku"]]["prod_name"] = $val["name"];
            $data["cart"][$val["sku"]]["price"] = platform_curr_format($val["price"]);
            $data["cart"][$val["sku"]]["qty"] = $val["qty"];
            $data["cart"][$val["sku"]]["increase_url"] = base_url() . "review_order/update/" . $val["sku"] . "/" . ($val["qty"] + 1);
            if ($val["qty"] - 1 > 0) {
                $data["cart"][$val["sku"]]["decrease_url"] = base_url() . "review_order/update/" . $val["sku"] . "/" . ($val["qty"] - 1);
            }
            $data["cart"][$val["sku"]]["sub_total"] = platform_curr_format($val["total"]);
            $data["cart"][$val["sku"]]["remove_url"] = base_url() . "review_order/remove/" . $val["sku"];
            $data["cart"][$val["sku"]]["prod_url"] = $this->cart_session_model->get_prod_url($val["sku"]);

#           SBF #2441 Pop-up for contact us if bulk sales criteria met
            if ($val["qty"] >= 10 && $val["total"] >= 5000) {
                $data["allow_bulk_sales"] = TRUE;  #meets bulk sale criteria - show popup to contact us
            }
        }
        $data["delivery_charge"] = platform_curr_format($result["dc_default"]["charge"]);
        $data["item_amount"] = platform_curr_format($item_total);
        $data["gst_total"] = platform_curr_format($gst_total);
        $total = $item_total - $result["dc_default"]["charge"] * 1;
        if ($result['promo']['disc_amount']) {
            $total = $item_total - $result['promo']['disc_amount'];
            if ($result['promo']['disc_amount'] > 0) {
                $result['promo']['display_disc_amount'] = "-" . platform_curr_format($result['promo']["disc_amount"]);
            }
        }
        $data["total"] = platform_curr_format($total + $gst_total);
        $data['promo'] = $result['promo'];

        foreach ($result["cart"] as $key => $cart_obj) {
            $tracking_list["sku"] = $cart_obj["sku"];
            $tracking_list["unit_price"] = $cart_obj["price"];
            $tracking_list["currency"] = $cart_obj["product_cost_obj"]->get_platform_currency_id();
            $tracking_list["product_name"] = $cart_obj["name"];
            $tracking_list["qty"] = $cart_obj["qty"];
            $tracking_list["total"] = $cart_obj["total"];
            $tracking_products[] = $tracking_list;
        }
        $data["tracking_data"]["products"] = $tracking_products;
        $data["tracking_data"]["total_amount"] = $total + $gst_total;

        $data["show_battery_message_w_amount"] = $this->cart_session_model->cart_session_service->check_battery_inside_cart_valid_or_not($total, PLATFORM);
        return $data;
    }

    protected function review_order__remove($controller, $url_paras)
    {
        return $this->review_order__index($controller, $url_paras);
    }

    protected function display__view($controller, $url_paras)
    {
        $page = $url_paras['page'];
        $subscribe_email = $url_paras['subscribe-email'];

        if (empty($page)) {
            show_404();
        }

        $data = $this->display___init_meta_content($controller, $page);

        #SBF #2441
        if ($page == 'bulk_sales') {
            $bulk_sales_country = 'AU|BE|FI|FR|GB|HK|IE|MY|NZ|PH|SG|ES|US|MT|CH|';
            if (strpos($bulk_sales_country, PLATFORMCOUNTRYID) === FALSE) {
                redirect(base_url());
            }
        }

        // echo '<pre>Dumping $_POST<br>'; var_dump($_POST); echo "</pre>";
        #var_dump(PLATFORMCOUNTRYID);
        if ($page == "newsletter_thank_you") {
            if (isset($subscribe_email)) {
                $email = urlencode($subscribe_email);

                $currency = PLATFORMCURR;
                $url = "";
                switch (PLATFORMCOUNTRYID) {
                    case "ES": # SBF#2119
                        $url = "http://p6trc.emv2.com/D2UTF8?emv_tag=1651E8080005CD12&emv_ref=EdX7CqkmjTao8SA9MOPvpMvWLkl7aaXD8jjde6xFLMHbKxw&EMAIL_FIELD=$email&SOURCE_FIELD=WEBFORM&LANGUAGE_ID_FIELD=ES&CURRENCY_FIELD=EUR";
                        break;

                    case "FR":  # SBF#1896
                        $url = "http://p6trc.emv2.com/D2UTF8?emv_tag=C8E78020000F53B6&emv_ref=EdX7CqkmjTKa8SA9MOPvpMukIDl9FK3B-jjde98zW7LfK9w&EMAIL_FIELD=$email&SOURCE_FIELD=WEBFORM&LANGUAGE_ID_FIELD=FR&CURRENCY_FIELD=EUR";
                        break;

                    case "IT": # SBF#2871
                        $url = "http://p6trc.emv2.com/D2UTF8?emv_tag=4AA1EE2BA080804A&emv_ref=EdX7CqkmjSAq8SA9MOPvpMvTWT17adjBiEndc6k-WMSoKFo&EMAIL_FIELD=$email&SOURCE_FIELD=WEBFORM&LANGUAGE_ID_FIELD=IT&CURRENCY_FIELD=EUR";
                        break;

                    case "PH":  # SBF#2454
                        $url = "http://p6trc.emv2.com/D2UTF8?emv_tag=1F8080007E045FAB&emv_ref=EdX7CqkmjT1R8SA9MOPvpMvWXkR6FK3D-j-oe60zLrGrK7w&EMAIL_FIELD=$email&SOURCE_FIELD=WEBFORM&LANGUAGE_ID_FIELD=EN&COUNTRY_ID_FIELD=PH&CURRENCY_FIELD=PHP";
                        break;

                    case "SG":  # SBF#2882
                        $url = "http://p6trc.emv2.com/D2UTF8?emv_tag=7F64C97504047F64&emv_ref=EdX7CqkmjSA68SA9MOPvpMvQXkp-b6TE_zjZe60xLsbdKEc&EMAIL_FIELD=$email&SOURCE_FIELD=WEBFORM&LANGUAGE_ID_FIELD=EN&COUNTRY_ID_FIELD=SG&CURRENCY_FIELD=SGD";
                        break;

                    case "RU":  # SBF#3627
                        $url = "http://p6trc.emv2.com/D2UTF8?emv_tag=135E3505C8802000&emv_ref=EdX7CqkmjSfd8SA9MOPvpMvWK0kPH6jD_0vVc6k0WMDZK3M&EMAIL_FIELD=$email&SOURCE_FIELD=WEBFORM&LANGUAGE_ID_FIELD=RU&CURRENCY_FIELD=RUB";
                        break;

                    case "NZ":
                        $url = "http://p6trc.emv2.com/D2UTF8?emv_tag=100004C6A4DBB440&emv_ref=EdX7CqkmjSad8SA9MOPvpMvWKEx6HKmw_EnZD9tEXMTZK-8&EMAIL_FIELD=$email&SOURCE_FIELD=WEBFORM&LANGUAGE_ID_FIELD=EN&CURRENCY_FIELD=NZD";
                        break;

                    case "MY":
                        $url = "http://p6trc.emv2.com/D2UTF8?emv_tag=20080005E7F27DDA&emv_ref=EdX7CqkmjSac8SA9MOPvpMvVKExyHK3D_03aDasxLLSoK_4&EMAIL_FIELD=$email&SOURCE_FIELD=WEBFORM&LANGUAGE_ID_FIELD=EN&CURRENCY_FIELD=MYR";
                        break;

                    case "PL":
                        $url = "http://p6trc.emv2.com/D2UTF8?emv_tag=8583C6A048010858&emv_ref=EdX7CqkmjS6x8SA9MOPvpMvfLUR5b6uy-jzVe6g2UMXRK8o&EMAIL_FIELD=$email&SOURCE_FIELD=WEBFORM&LANGUAGE_ID_FIELD=PL&CURRENCY_FIELD=PLN";
                        break;

                    case "US":
                        $url = "http://p6trc.emv2.com/D2UTF8?emv_tag=BD772C9602000028&emv_ref=EdX7CqkmjRWR8SA9MOPvpMulXEt9Ht7K_Djfe6k2WMLRK-U&EMAIL_FIELD=$email&SOURCE_FIELD=WEBFORM&LANGUAGE_ID_FIELD=US&CURRENCY_FIELD=USD";
                        break;

                    default:    # SBF#1740
                        $url = "http://p6trc.emv2.com/D2UTF8?emv_tag=49DFB38B9D808004&emv_ref=EdX7CqkmjTPl8SA9MOPvpMvTITgMbq7LiDGpc6k-WMDdKwg&EMAIL_FIELD=$email&SOURCE_FIELD=WEBFORM&LANGUAGE_ID_FIELD=EN&CURRENCY_FIELD=$currency";
                        break;
                }

                $use_curl = true;
                if ($use_curl) {
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                    curl_exec($ch);
                    curl_close($ch);
                } else
                    file_get_contents($url);
            }
        } elseif ($page == "bulk_sales") {
            $controller->template->add_js('/js/checkform.js');
        }

        return $data;
    }

    private function display___init_meta_content($controller, $page)
    {
        $data['data']['lang_text'] = $controller->get_language_file();

        switch ($page) {
            case 'newsletter_thank_you':
                $meta_title = $data['data']['lang_text']['meta_title_newsletter'] . ' | ValueBasket';
                #$meta_desc = $data['data']['lang_text']['meta_description_default'];
                #$meta_keyword = $data['data']['lang_text']['meta_keyword_shipping'];
                break;
            case 'shipping':
                $meta_title = $data['data']['lang_text']['meta_title_shipping'] . ' | ValueBasket';
                $meta_desc = $data['data']['lang_text']['meta_description_shipping'];
                $meta_keyword = $data['data']['lang_text']['meta_keyword_shipping'];
                break;
            case 'about_us':
                $meta_title = $data['data']['lang_text']['meta_title_about_us'] . ' | ValueBasket';
                $meta_desc = $data['data']['lang_text']['meta_description_about_us'];
                $meta_keyword = $data['data']['lang_text']['meta_keyword_about_us'];
                break;
            case 'conditions_of_use':
                $meta_title = $data['data']['lang_text']['meta_title_condition'] . ' | ValueBasket';
                $meta_desc = $data['data']['lang_text']['meta_description_condition'];
                $meta_keyword = $data['data']['lang_text']['meta_keyword_condition'];
                break;
            case 'privacy_policy':
                $meta_title = $data['data']['lang_text']['meta_title_privacy'] . ' | ValueBasket';
                $meta_desc = $data['data']['lang_text']['meta_description_privacy'];
                $meta_keyword = $data['data']['lang_text']['meta_keyword_privacy'];
                break;
            case 'faq':
                $meta_title = $data['data']['lang_text']['meta_title_faq'] . ' | ValueBasket';
                $meta_desc = $data['data']['lang_text']['meta_description_faq'];
                $meta_keyword = $data['data']['lang_text']['meta_keyword_faq'];
                break;
            default:
                return false;
        }
        $controller->template->add_title($meta_title);
        $controller->template->add_meta(array('name' => 'description', 'content' => $meta_desc));
        $controller->template->add_meta(array('name' => 'keywords', 'content' => $meta_keyword));

        return $data;
    }

    protected function contact__index($controller, $url_paras)
    {
        $data = array();
        $data['lang_text'] = $controller->get_language_file();

        $countryid = PLATFORMCOUNTRYID;
        if (PLATFORMCOUNTRYID == "MY") $countryid = "SG";

        // moving towards a per country contact us page
        // $data['contact_info'] = $this->website_model->get_cs_contact_list_by_country(array("type"=>"WEBSITE", "lang_id"=> get_lang_id()));
        $data['contact_info'] = $this->website_model->get_cs_contact_list_by_country(array("type" => "WEBSITE", "platform_country_id" => $countryid));

        #SBF 2200 to get respective contact info from db according to browser lang
        $contact_info_list = $data['contact_info'];
        foreach ($contact_info_list as $contact_info_row) {
            $trim_lang_id = substr(lang_part(), 0, stripos(lang_part(), "_"));
            if ($contact_info_row["lang_id"] == $trim_lang_id) {
                $contact_info[] = $contact_info_row;
            }
        }

        if (count($contact_info_list) > 1)
            $data['contact_info'] = $contact_info;

        return $data;
    }

    protected function cart__add_item($controller, $url_paras)
    {
        $this->cart__add_item_qty($controller, $url_paras);
    }

    protected function cart__add_item_qty($controller, $url_paras)
    {
        require_once(BASEPATH . 'plugins/My_plugin/validator/regex_validator.php');
        require_once(BASEPATH . 'plugins/My_plugin/validator/digits_validator.php');

        $data['lang_text'] = $controller->get_language_file('', '', 'add_item_qty');
        $this->affiliate_service->add_af_cookie($_GET);
        $listing_status = array("I" => $data['lang_text']['status_in_stock'], "O" => $data['lang_text']['status_out_stock'], "P" => $data['lang_text']['status_pre_order'], "A" => $data['lang_text']['status_arriving']);

        $sku = $url_paras["sku"];
        $qty = $url_paras["qty"];
        if (empty($sku)) {
            $sku = $_REQUEST["sku"];
        }
        if (empty($qty)) {
            $qty = $_REQUEST["qty"];
        }

        $regex_validator = new Regex_validator(Regex_validator::REGX_SKU_FORMAT);
        $digits_validator = new Digits_validator(array("allow_empty" => false));
        if (!$digits_validator->is_valid($qty) || !$regex_validator->is_valid($sku)) {
            show_404('page');
        }

        $allow_result = $this->cart_session_model->cart_session_service->is_allow_to_add($sku, 1, PLATFORM);
        if ($allow_result <= Cart_session_service::DECISION_POINT) {
            if (!empty($sku) || !empty($qty)) {
                $chk_cart = $this->cart_session_model->add($sku, $qty, PLATFORM);
            }
        } else {
            redirect(base_url() . "review_order?item_status=" . $allow_result . "&not_valid_sku=" . $sku);
        }
//      var_dump($_SESSION["cart"]);
        if (($allow_result == Cart_session_service::ALLOW_AND_IS_PREORDER)
            || ($allow_result == Cart_session_service::ALLOW_AND_IS_ARRIVING)
            || ($allow_result == Cart_session_service::SAME_PREORDER_ITEM)
            || ($allow_result == Cart_session_service::SAME_ARRIVING_ITEM)
        ) {
            redirect(base_url() . "review_order");
        }

        if ($this->upselling_model->get_ra($data, $sku, PLATFORM, get_lang_id(), $listing_status)) {
            redirect(base_url() . "review_order");
        } else {
            redirect(base_url() . "review_order");
        }
    }

    protected function cat__view($controller, $url_paras)
    {
        require_once(BASEPATH . 'plugins/My_plugin/validator/digits_validator.php');
        $cat_id = $url_paras["cat_id"];

        $digits_validator_allow_empty = new Digits_validator(array("allow_empty" => true));
        $digits_validator = new Digits_validator(array("allow_empty" => false));
        if (!$digits_validator->is_valid($cat_id)) {
            show_404('page');
        }
        $brand_id = $this->input->get('brand_id');
        if (!empty($brand_id) && !$digits_validator_allow_empty->is_valid($brand_id)) {
            show_404('page');
        }
        $page = $this->input->get('page');
        if (!empty($page) && !$digits_validator_allow_empty->is_valid($page)) {
            show_404('page');
        }
        $sort = $this->input->get('sort');
        if (!empty($sort) &&
            ($sort != "pop_desc") && ($sort != "latest_desc") && ($sort != "price_asc") && ($sort != "price_desc")
        ) {
            print __LINE__;
            exit;

            show_404('page');
        }

        $rpp = $url_paras["rpp"];
        $display_range = $url_paras["display_range"];

        $data['lang_text'] = $controller->get_language_file();
        $controller->template->add_link("rel='canonical' href='" . base_url() . "/cat/view/" . $cat_id . "'");  # SEO
        if (!$cat_obj = $controller->category_model->get_cat_info_w_lang(array("c.id" => $cat_id, "ce.lang_id" => get_lang_id(), "c.status" => 1), array("limit" => 1))) {
            $cat_obj = $controller->category_model->get_cat_info_w_lang(array("c.id" => $cat_id, "ce.lang_id" => "en", "c.status" => 1), array("limit" => 1));
        }
        if (empty($cat_id) || !$cat_obj) {
            show_404('page');
        }

        $controller->affiliate_service->add_af_cookie($_GET);
        $level = $cat_obj->get_level();

        $where['pr.platform_id'] = PLATFORM;
        $where['p.status'] = 2;

        switch ($level) {
            case 1:
                $where['p.cat_id'] = $cat_id;
                break;
            case 2:
                $where['p.sub_cat_id'] = $cat_id;
                break;
            case 3:
                $where['p.sub_sub_cat_id'] = $cat_id;
                break;
            default:
        }
        if ($brand_id = $this->input->get('brand_id')) {
            $where['br.id'] = $brand_id;
        }

        if (!$data['sort'] = $sort) {
            //$data['sort'] = 'pop_desc';
            //Category Page display, sort by priority of the sub_category
            $data['sort'] = 'priority_asc';
        }

        switch ($data['sort']) {
            case 'pop_desc':
                $option["orderby"] = "pr.sales_qty desc";
                break;
            case 'price_asc':
                $option["orderby"] = "pr.price ASC";
                break;
            case 'price_desc':
                $option["orderby"] = "pr.price DESC";
                break;
            case 'latest_asc':
                $option["orderby"] = "sc.priority asc, p.create_on ASC";
                break;
            case 'latest_desc':
                $option["orderby"] = "sc.priority asc, p.create_on DESC";
                break;
            //#2580, sort by priority of the sub_category
            case 'priority_asc':
                $option["orderby"] = "sc.priority asc, pr.sales_qty desc";
                break;
            default:
                $option["orderby"] = "sc.priority asc, p.create_on DESC";
                break;
        }

        #SBF2580, push all the Arriving stock to bottom before Out of stock
        $option["orderby"] = "is_arr asc, " . $option["orderby"];

        #SBF1905, push all the Out of stock to bottom
        $option["orderby"] = "is_oos asc, " . $option["orderby"];
        /*
                if(!$rpp = $this->input->get('rpp'))
                {
                    $rpp = 12;
                }
        */
        if (!$page) {
            $page = 1;
        }

        $option['limit'] = $rpp;
        $option['offset'] = $rpp * ($page - 1);

        $total = $this->category_model->get_website_cat_page_product_list($where, array("num_rows" => 1));
        if ($sku_list = $this->category_model->get_website_cat_page_product_list($where, $option)) {
            $obj_list = $this->product_model->get_listing_info_list($sku_list, PLATFORM, get_lang_id(), array());
        }

        $data['show_discount_text'] = $this->price_website_service->is_display_saving_message();

        $show_404 = TRUE;
        if ($obj_list) {
            $i = 1;
            // this flag is used to check against the list to make sure there is at least one available to be listed
            foreach ($obj_list AS $key => $obj) {
                if ($obj) {
                    $product_list[$key]["sku"] = $obj->get_sku();
                    $product_list[$key]["prod_name"] = $obj->get_prod_name();
                    $product_list[$key]["listing_status_text"] = $data['lang_text'][$obj->get_status()];
                    $product_list[$key]["listing_status"] = $obj->get_status();
                    $product_list[$key]["qty"] = $obj->get_qty();
                    $product_list[$key]["price"] = platform_curr_format($obj->get_price());
                    $product_list[$key]["rrp_price"] = platform_curr_format($obj->get_rrp_price());
                    $product_list[$key]["discount"] = number_format(($obj->get_rrp_price() == 0 ? 0 : ($obj->get_rrp_price() - $obj->get_price()) / $obj->get_rrp_price() * 100), 0);
                    $product_list[$key]["prod_url"] = $this->category_model->get_prod_url($obj->get_sku());
                    $product_list[$key]["short_desc"] = $obj->get_short_desc();
                    $product_list[$key]["image_ext"] = $obj->get_image_ext();
                    $product_list[$key]["image"] = get_image_file($obj->get_image_ext(), "m", $obj->get_sku());
                    if ($i < 4) {
                        $criteo_tag .= '&i' . $i . '=' . $obj->get_sku();
                        $i++;
                    }
                    $show_404 = FALSE;
                }
            }
        }
        if ($show_404) {
            show_404('page');
        }

// generate left filter menu
        unset($option['limit']);
        $option['limit'] = -1;
        $full_sku_list = $this->category_model->get_website_cat_page_product_list($where, $option);
        $data['cat_result'] = $controller->get_cat_filter_grid_info($level, $full_sku_list);
        $data['brand_result'] = $controller->get_brand_filter_grid_info($full_sku_list);
        $data["brand_id"] = $brand_id;

        $data['product_list'] = $product_list;
        $data['cat_obj'] = $cat_obj;
        $data['cat_name'] = $cat_obj->get_name();
        $data['level'] = $level;

        // pagination variable
        $data['total_result'] = $total;
        $data['curr_page'] = $page;
        $data['total_page'] = (int)ceil($total / $rpp);
        $data['display_range'] = $display_range;

        $data['rpp'] = $rpp;

        // url
        $parent_cat_id = $this->category_model->get_parent_cat_id($cat_id);
        $data['parent_cat_url'] = null;
        if ($level > 1) {
            $data['parent_cat_url'] = $this->website_model->get_cat_url($parent_cat_id);
        }

        // breadcrumb
        switch (get_lang_id()) {
            case "fr":
                $home_text = "Accueil";
                $home_text = "ValueBasket"; # SEO guru says change it all, #sbf 1572
                break;
            case "en":
            default:
                $home_text = "Home";
                $home_text = "ValueBasket"; # SEO guru says change it all, #sbf 1572
        }
        $data['breadcrumb'][0] = array($home_text => base_url());
        switch ($level) {
            case 3:
                if (!$cat_obj = $this->category_model->get_cat_info_w_lang(array("c.id" => $cat_id, "ce.lang_id" => get_lang_id(), "c.status" => 1), array("limit" => 1))) {
                    $cat_obj = $this->category_model->get_cat_info_w_lang(array("c.id" => $cat_id, "ce.lang_id" => "en", "c.status" => 1), array("limit" => 1));
                }
                $cat_name['sscat'] = $cat_obj->get_name();
                $cat_url = $this->website_model->get_cat_url($cat_id);
                $data['breadcrumb'][3] = array($cat_name['sscat'] => $cat_url);
                $cat_id = $this->category_model->get_parent_cat_id($cat_id);
            case 2:
                if (!$cat_obj = $this->category_model->get_cat_info_w_lang(array("c.id" => $cat_id, "ce.lang_id" => get_lang_id(), "c.status" => 1), array("limit" => 1))) {
                    $cat_obj = $this->category_model->get_cat_info_w_lang(array("c.id" => $cat_id, "ce.lang_id" => "en", "c.status" => 1), array("limit" => 1));
                }
                $cat_name['scat'] = $cat_obj->get_name();
                $cat_url = $this->website_model->get_cat_url($cat_id);
                $data['breadcrumb'][2] = array($cat_name['scat'] => $cat_url);
                $cat_id = $this->category_model->get_parent_cat_id($cat_id);
            case 1:
                if (!$cat_obj = $this->category_model->get_cat_info_w_lang(array("c.id" => $cat_id, "ce.lang_id" => get_lang_id(), "c.status" => 1), array("limit" => 1))) {
                    $cat_obj = $this->category_model->get_cat_info_w_lang(array("c.id" => $cat_id, "ce.lang_id" => "en", "c.status" => 1), array("limit" => 1));
                }
                $cat_name['cat'] = $cat_obj->get_name();
                $cat_url = $this->website_model->get_cat_url($cat_id);
                $data['breadcrumb'][1] = array($cat_name['cat'] => $cat_url);
            default:
                break;
        }
        if ($data['breadcrumb']) {
            ksort($data['breadcrumb']);
        }

        // meta tag

        $meta_title = implode(' - ', $cat_name);
        $controller->template->add_title($meta_title . ' | ValueBasket');
        $controller->template->add_meta(array('name' => 'description', 'content' => $cat_obj->get_description()));
        switch ($level) {
            case 1:
                $meta_keyword = $data['lang_text']['meta_keyword_1'];
                break;
            case 2:
                $meta_keyword = $data['lang_text']['meta_keyword_2'];
                break;
            case 3:
                $meta_keyword = $data['lang_text']['meta_keyword_3'];
                break;
        }
        $controller->template->add_meta(array('name' => 'keywords', 'content' => $meta_keyword));

        $data["tracking_data"] = array("category_name" => $cat_name['cat'], "category_id" => $cat_id);

        return $data;
    }

    protected function redirect_controller__index($controller, $url_paras)
    {
        $controller->affiliate_service->add_af_cookie($_GET);
        $data['lang_text'] = $controller->get_language_file();
        $http = isset($_SERVER['HTTPS']) ? "https" : "http";
        $listing_status = array("I" => $data['lang_text']['index_in_stock'], "O" => $data['lang_text']['index_out_of_stock'], "P" => $data['lang_text']['index_pre_order'], "A" => $data['lang_text']['index_arriving']);
        $value = $controller->home_model->get_content();
        if ($value["best_seller"]) {
            $best_seller = array();
            foreach ($value["best_seller"] AS $key => $obj) {
                $best_seller[$key]["sku"] = $obj->get_sku();
                $best_seller[$key]["prod_name"] = $obj->get_prod_name();
                $best_seller[$key]["listing_status"] = $obj->get_status();
                $best_seller[$key]["stock_status"] = ($obj->get_status() == 'I') ? $obj->get_qty() . " " . $listing_status[$obj->get_status()] : $listing_status[$obj->get_status()];
                $best_seller[$key]["price"] = platform_curr_format($obj->get_price());
                $best_seller[$key]["rrp_price"] = platform_curr_format($obj->get_rrp_price());
                $best_seller[$key]["discount"] = number_format(($obj->get_rrp_price() == 0 ? 0 : ($obj->get_rrp_price() - $obj->get_price()) / $obj->get_rrp_price() * 100), 0);
                $best_seller[$key]["prod_url"] = $controller->home_model->get_prod_url($obj->get_sku());
                $best_seller[$key]["short_desc"] = $obj->get_short_desc();
                $best_seller[$key]["image_ext"] = $obj->get_image_ext();
                $best_seller[$key]["image"] = get_image_file($obj->get_image_ext(), "m", $obj->get_sku());
            }
            $data["best_seller"] = $best_seller;
        }

        if (get_lang_id() == "en") {
            $data["g_plus_link"] = $http . "://plus.google.com/104457367954785921390";
            $data["facebook_link"] = $http . "://www.facebook.com/ValueBasket";
            $data["twitter_link"] = $http . "://www.twitter.com/ValueBasket";
        } else {
            $data["g_plus_link"] = "";
            $data["facebook_link"] = "";
            $data["twitter_link"] = "";
        }
        return $data;
    }

    protected function search__search_by_ss($controller)
    {
        $data = array();
        $data['lang_text'] = $controller->get_language_file('', '', 'index');

        switch (strtolower(PLATFORMCOUNTRYID)) {
            case 'au' :
                $searchspring_site_id = 'vm182w';
                break;
            case 'be' :
                $searchspring_site_id = 'nbc1w6';
                break;
            case 'fi' :
                $searchspring_site_id = 'mlcmi6';
                break;
            case 'fr' :
                $searchspring_site_id = 'hi80z4';
                break;
            case 'gb' :
                $searchspring_site_id = 'z7q84w';
                break;
            case 'hk' :
                $searchspring_site_id = 'jx3nzf';
                break;
            case 'ie' :
                $searchspring_site_id = 'evot87';
                break;
            case 'my' :
                $searchspring_site_id = '9amw38';
                break;
            case 'nz' :
                $searchspring_site_id = 'tbp112';
                break;
            case 'sg' :
                $searchspring_site_id = 'zkx7z6';
                break;
            case 'es' :
                $searchspring_site_id = 'kfwha5';
                break;
            case 'pt' :
                $searchspring_site_id = 'lom5se';
                break;
            case 'us' :
                $searchspring_site_id = 'oap9ds';
                break;
            case 'ph' :
                $searchspring_site_id = 'ule0ej';
                break;
            case 'it' :
                $searchspring_site_id = 'zk4kkc';
                break;
            case 'mt' :
                $searchspring_site_id = 'aoi27k';
                break;
            case 'ch' :
                $searchspring_site_id = 'jmx5qq';
                break;
            case 'ru' :
                $searchspring_site_id = 'oocsgg';
                break;

            default   :
                $searchspring_site_id = '';
        }
        $data['searchspring_site_id'] = $searchspring_site_id;

        return $data;
    }

    protected function mainproduct__view($controller, $url_paras)
    {
        $sku = $url_paras["sku"];
        $type = $url_paras["type"];


        if (!$this->product_model->price_service->get(array("sku" => $sku, "listing_status" => "L", "platform_id" => PLATFORM))) {
            return false;
        }

        if ($sku && $listing_info = $this->product_model->get_listing_info($sku, PLATFORM, $this->get_lang_id())) {
            $data['lang_text'] = $controller->get_language_file();

            if (!$prod_info = $this->product_model->get_website_product_info($sku, PLATFORM, $this->get_lang_id())) {
                $prod_info = $this->product_model->get_website_product_info($sku, PLATFORM);
            }

            $_SESSION['PARENT_PAGE'] = base_url() . "mainproduct/view/" . $sku;

            if ($prod_image_list = $this->product_model->product_service->get_pi_dao()->get_list(array("sku" => $sku, "status" => 1), array("orderby" => "priority ASC, create_on DESC"))) {
                    foreach ($prod_image_list AS $key => $prod_img_obj) {
                        $prod_image[$key]["image_icon"] = get_image_file($prod_img_obj->get_image(), "s", $prod_img_obj->get_sku(), $prod_img_obj->get_id());
                        $prod_image[$key]["image"] = get_image_file($prod_img_obj->get_image(), "l", $prod_img_obj->get_sku(), $prod_img_obj->get_id());
                    }
            }
            // gather product info
            $data["sku"] = $sku;
            $data["prod_name"] = $listing_info->get_prod_name();

            if ($delivery_scenarioid = $listing_info->get_delivery_scenarioid()) {
                $delivery_obj = $this->deliverytime_service->get_deliverytime_obj(PLATFORMCOUNTRYID, $delivery_scenarioid);
            }

            $data["listing_status"] = $listing_info->get_status();
            $data["qty"] = $listing_info->get_qty();
            $data["stock_status"] = $listing_info->get_status() == 'I' ? $listing_info->get_qty() . " " . $listing_status[$listing_info->get_status()] : $listing_status[$listing_info->get_status()];
            $data["prod_price"] = $listing_info->get_price();
            $data["prod_rrp_price"] = $listing_info->get_rrp_price();
            $data["overview"] = nl2br(trim($prod_info->get_detail_desc()));
            $data["lang_restricted"] = trim($prod_info->get_lang_restricted());
            $data['image'] = $prod_info->get_image();
            $data['prod_image'] = $prod_image;
            $data['default_image'] = $prod_image["0"]["image"];
            $data["osd_lang_list"] = $this->product_model->product_service->get_lang_osd_list();
            $data["website_status_long_text"] = trim($prod_info->get_website_status_long_text());
            $data["website_status_short_text"] = trim($prod_info->get_website_status_short_text());

            //#2272 add the warranty.
            $data["warranty_in_month"] = $listing_info->get_warranty_in_month();

            if ($prod_info->get_contents() != "") {
                $str = explode("\n",$prod_info->get_contents());
                foreach ($str as $k => $v) {
                    if (empty($v)) {
                        unset($str[$k]);
                    }
                }
                $data['in_the_box'] = "<ul><li>".implode("</li><li>", $str)."</li></ul>";
            }

            if ($prod_info->get_specification() != "") {
                $data['specification'] = $prod_info->get_specification();
            }

            if (empty($data["website_status_short_text"])) {
                //create default message
                if (file_exists($language_path)) {
                    if ($data["listing_status"]) {
                        if ($del_obj = $this->delivery_service->get(array("delivery_type_id" => $this->context_config_service->value_of("default_delivery_type"), "country_id" => PLATFORMCOUNTRYID))) {
                            $data["working_day"] = implode('-', array($del_obj->get_min_day(), $del_obj->get_max_day()));
                            $data['delivery_min_day'] = $del_obj->get_min_day();
                            $data['delivery_max_day'] = $del_obj->get_max_day();
                        } else {
                            $data["working_day"] = implode('-', array($this->context_config_service->value_of("default_delivery_min_day"), $this->context_config_service->value_of("default_delivery_max_day")));
                            $data['delivery_min_day'] = $this->context_config_service->value_of("default_delivery_min_day");
                            $data['delivery_max_day'] = $this->context_config_service->value_of("default_delivery_max_day");
                        }
                    }
                }
            }

            $cat_url = $this->website_model->get_cat_url($prod_info->get_cat_id());
            $sub_cat_url = $this->website_model->get_cat_url($prod_info->get_sub_cat_id());
            if (!$prod_info->get_sub_sub_cat_id() && $prod_info->get_sub_sub_cat_id() != 0) {
                $sub_sub_cat_url = $this->website_model->get_cat_url($prod_info->get_sub_sub_cat_id());
            }

            if (!$cat_obj = $this->category_model->get_cat_info_w_lang(array("c.id" => $prod_info->get_cat_id(), "ce.lang_id" => $this->get_lang_id(), "c.status" => 1), array("limit" => 1))) {
                $cat_obj = $this->category_model->get_cat_info_w_lang(array("c.id" => $prod_info->get_cat_id(), "ce.lang_id" => "en", "c.status" => 1), array("limit" => 1));
                if ($cat_obj) {
                    $localized_cat_name = $cat_obj->get_name();
                    $category_name = $cat_obj->get_name();
                }
            }

            if (!$sc_obj = $this->category_model->get_cat_info_w_lang(array("c.id" => $prod_info->get_sub_cat_id(), "ce.lang_id" => $this->get_lang_id(), "c.status" => 1), array("limit" => 1))) {
                $sc_obj = $this->category_model->get_cat_info_w_lang(array("c.id" => $prod_info->get_sub_cat_id(), "ce.lang_id" => "en", "c.status" => 1), array("limit" => 1));
                if ($sc_obj) {
                    $localized_sc_name = $sc_obj->get_name();
                }
            }

            $data['breadcrumb'][] = array($home_text => base_url());
            $data['breadcrumb'][] = array($localized_cat_name => $cat_url);
            $data['breadcrumb'][] = array($localized_sc_name => $sub_cat_url);

            if ($sub_sub_cat_url) {
                if (!$cat_obj = $this->category_model->get_cat_info_w_lang(array("c.id" => $prod_info->get_sub_sub_cat_id(), "ce.lang_id" => $this->get_lang_id(), "c.status" => 1), array("limit" => 1))) {
                    $cat_obj = $this->category_model->get_cat_info_w_lang(array("c.id" => $prod_info->get_sub_sub_cat_id(), "ce.lang_id" => "en", "c.status" => 1), array("limit" => 1));
                }
                $localized_ssc_name = $ssc_obj->get_name();
                $data['breadcrumb'][] = array($localized_ssc_name => $sub_sub_cat_url);
            }

            // meta tag
            if ($prod_info->get_sub_sub_cat_id() != 0) // check if there is sub_sub_cat_id
            {
                $meta_title = implode(' - ', array($listing_info->get_prod_name(), $prod_info->get_sub_sub_cat_name(), $prod_info->get_sub_cat_name(), $prod_info->get_cat_name()));
            } else {
                $meta_title = implode(' - ', array($listing_info->get_prod_name(), $prod_info->get_sub_cat_name(), $prod_info->get_cat_name()));
            }
            if ($keyword_list = $this->product_model->get_product_keyword_arraylist($sku, PLATFORM)) {
                $meta_keyword = implode(',', $keyword_list);
            }
            // $this->template->add_title($meta_title . ' | ValueBasket ' . PLATFORMCOUNTRYID);
            $content = $listing_info->get_short_desc();
            if ($content == "") {
                $content = "Buy the " . $listing_info->get_prod_name() . " from ValueBasket " . PLATFORMCOUNTRYID . " with free shipping.";
            }

            $lang_id = $this->get_lang_id();
            if (($lang_id != "en") && (($data["lang_restricted"] & (1 << $data["osd_lang_list"]["NA"])) != 1)) {
                if (array_key_exists(strtoupper($lang_id), $data["osd_lang_list"])) {
                    $lang_restricted = false;
                    if (!($data["lang_restricted"] & (1 << $data["osd_lang_list"][strtoupper($lang_id)]))) {
                        $lang_restricted = true;
                    }
                    if ($lang_restricted)
                        //SBF 5110 change ". $data['content']" to ". $data['overview']"
                        $data['overview'] = "<span style='font-weight:bold;color:#DD1313'>" . $data['lang_text']["english_only"] . "</span><br>" . $data['overview'];
                }
            }

            (trim($data['overview']) != '' ? $data['has_overview'] = TRUE : $data['has_overview'] = FALSE);
            (trim($data['specification']) != '' ? $data['has_specification'] = TRUE : $data['has_specification'] = FALSE);
            (trim($data['in_the_box']) != '' ? $data['has_in_the_box'] = TRUE : $data['has_in_the_box'] = FALSE);

            $data["gst_msg_type"] = '';
            $price = $listing_info->get_price();

            $data['microdata']['price'] = $listing_info->get_price();
            $data['microdata']['currency'] = $listing_info->get_currency_id();
            $data['microdata']['brand'] =  $prod_info->get_brand_name();
            if (strtolower($prod_info->get_cat_name()) == "refurbish") {
                $data['microdata']['itemCondition'] = 'http://schema.org/RefurbishedCondition';
            } else {
                $data['microdata']['itemCondition'] = 'http://schema.org/NewCondition';
            }

            if ($listing_info->get_qty() > 0) {
                $data['microdata']['availability'] = "http://schema.org/InStock";
            } else {
                $data['microdata']['availability'] = "http://schema.org/OutOfStock";
            }

            return $data;
        }
    }

}
