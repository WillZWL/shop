<?php
DEFINE ('ALLOW_REDIRECT_DOMAIN', 0);

include_once(APPPATH . "public_controllers/checkout_redirect_method.php");
class Checkout_onepage extends Checkout_redirect_method
{
    const STEP_LOGIN = "login";
    const STEP_BILLING_INFORMATION = "billing";
    const STEP_SHIPPING_INFORMATION = "shipping";
    const STEP_PAYMENT_INFORMATION = "payment";
//  const STEP_OVERVIEW = "review";
    const MAXIMUM_STEP = STEP_PAYMENT_INFORMATION;
    private $block_list = array("login"=>"login", "billing"=>"billing", "shipping"=>"shipping", "payment"=>"payment");

    private $_step;
    private $_current_step;

    public function __construct()
    {
        parent::Checkout_redirect_method();
        $this->load->library('service/context_config_service');
        $this->load->library('service/price_service');
        $this->load->library('service/platform_biz_var_service');
        $this->load->library('service/delivery_service');
        $this->load->library('template');
        //$this->template->set_template('checkout');
        $this->load->helper('tbswrapper');
        $this->load->model('website/checkout_model');

        $this->load->library('service/affiliate_service');  # SBF#1986

//      $this->template->add_js('js/image.js');
//      $this->template->add_css('css/style.css');
    }

    public function index()
    {
//      $_SESSION["NOTICE"] = "";
//tbs wrapper

        $data = $this->get_preload_data();
        $data = array_merge($data, $this->checkout_model->index_content());
//end of tbs wrapper

        // Redirect to review_order page to display no product if the cart is empty
        if (!$data['cart_item'])
        {
            Redirect(base_url()."review_order");
        }

//      $data['display_id'] = 12;
//      include_once(APPPATH . "language/WEB" . str_pad($data['display_id'], 6, '0', STR_PAD_LEFT) . "_" . get_lang_id() . ".php");
//      $data["lang"] = $lang;

//      print_r($_SESSION["client"]);
        if (!$_SESSION["client"]["logged_in"])
        {
            $this->_current_step = Checkout_onepage::STEP_LOGIN;
            $data["no_login_before"] = true;
        }
        else
        {
            if (PLATFORMID == "WEBPH")
                $this->_current_step = Checkout_onepage::STEP_SHIPPING_INFORMATION;
            else
                $this->_current_step = Checkout_onepage::STEP_BILLING_INFORMATION;
            $data["no_login_before"] = false;
            $data["client"] = $_SESSION["client"];
        }

        $data["state_list"] = $this->country_service->get_country_state_srv()->get_list(array("status"=>1), array("limit"=>-1, "array_list"=>1, "orderby"=>"country_id"));
//      var_dump($data["state_list"]);
        $data["current_step"] = $this->_current_step;
        $data["next_step"] = $this->get_next_step($this->_current_step);

//      $data["first_allow_list"] = $this->getAllowList($this->_current_step);

//      $data["display_block"] = $this->getDisplayBlocks();
//      $data["block_list"] = $this->getBlockList();
        $data["allow_login"] = $this->getAllowLogin();
        $data["display_block_list"] = $this->getDisplayBlockList();
        $data["show_block"] = $this->getBlockStyle();
        $data["country_list"] = $this->checkout_model->get_allow_sell_country();

        $data["cart"] = $_SESSION["cart"];

        $domain = check_domain();
        if ($_SESSION["cart"][PLATFORMID])
        {
            include_once(APPPATH."helpers/string_helper.php");
            $data['chk_cart_cookie'] = base64_encode(serialize($_SESSION["cart"][PLATFORMID]));
        }

        $total = 0;
        $cart_item = $data['cart_item'];
        $chk_cart = $data['chk_cart'];

        // SBF #3249, Comment out this GST function (#2236)
        $data["need_gst_display"] = FALSE;
        $data["gst_order"] = FALSE;
        $gst_total = 0;

        /*
            $need_gst_display = FALSE;
            if (PLATFORMCOUNTRYID == 'NZ')
            {
                $need_gst_display = TRUE;
            }

            $gst_total = 0;
            $gst_order = FALSE;
            if ($need_gst_display)
            {
                foreach($chk_cart AS $key=>$val)
                {
                    $gst_total += $val["gst"];
                }

                if ($gst_total > 0)
                {
                    $gst_order = TRUE;
                }
            }

            $data["need_gst_display"] = $need_gst_display;
            $data["gst_order"] = $gst_order;
        */
        // End of SBF #3249, Comment out this GST function (#2236)

        for($j=0; $j<count($chk_cart); $j++)
        {
            $total += $chk_cart[$j]["price"] * $chk_cart[$j]["qty"];
        }

        $promo_disc_amount = '';
        $promo = $data["promo"];
        if ($promo["valid"] && isset($promo["disc_amount"]))
        {
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

        switch ($data['selected_country'])
        {
            case 'GB' : $check_pobox_amount = 100; break;
            case 'IE' : $check_pobox_amount = 100; break;
            case 'FR' : $check_pobox_amount = 200; break;
            case 'ES' : $check_pobox_amount = 100; break;
            case 'FI' : $check_pobox_amount = 200; break;
            case 'MT' : $check_pobox_amount = 100; break;
            case 'IT' : $check_pobox_amount = 100; break;
            case 'US' : $check_pobox_amount = 100; break;
            case 'AU' : $check_pobox_amount = 100; break;
            case 'NZ' : $check_pobox_amount = 280; break;
            case 'HK' : $check_pobox_amount = 2000; break;
            case 'CH' : $check_pobox_amount = 240; break;
            case 'PT' : $check_pobox_amount = 60; break;
            case 'RU' : $check_pobox_amount = 2300; break;
            case 'PL' : $check_pobox_amount = 250; break;
            case 'MX' : $check_pobox_amount = 750; break;
            case 'NL' : $check_pobox_amount = 100; break;
            case 'SE' : $check_pobox_amount = 900; break;
            default   : $check_pobox_amount = 0;
        }

        if ($data['grand_total'] > $check_pobox_amount)
        {
            $data['allow_pobox'] = false;
        }
        else
        {
            $data['allow_pobox'] = true;
        }

        #SBF #2958 Add NIF/CIF for ES
        #SBF #4330 Also for IT
        $data["show_client_id"] = FALSE;
        if ((PLATFORMCOUNTRYID == "ES") || (PLATFORMCOUNTRYID == "RU") || (PLATFORMCOUNTRYID == "IT"))
        {
            $data["show_client_id"] = TRUE;
        }

//      $data['menu'] = $preload_data['menu'];
//      $data['platform_list'] = $preload_data['platform_list'];

        $this->template->add_js('/js/checkform.js');
        $this->template->add_js('/js/checkout.js');
        $this->template->add_js('/js/common.js');
        $this->template->add_js('/js/prototype/prototype.js');
        $this->template->add_js('/js/lytebox_cv.min.js');

/* payment gateway code */
        $this->template->add_js('/js/payment_gateway.js');
        $this->template->add_css(add_css_helper('css/checkout_onepage.css', TRUE));
        $this->template->add_css(add_css_helper('css/lytebox.css', TRUE));
        $this->template->add_css(add_css_helper('css/lytebox_ext.css', TRUE));

        include_once(APPPATH . "libraries/service/cybersource/cybersource_integrator.php");
        $cybs_integrator = new Cybersource_integrator();
        $merchant_info = $cybs_integrator->get_merchant_Id($data['selected_country'], $_SESSION["domain_platform"]["platform_currency_id"]);
        $data["cybersource_fingerprint"] = session_id();
        $data["cybersource_fingerprint_label"] = $merchant_info["merchantId"] . session_id();
        $data["debug"] = $this->get_debug();
        $data["cybersource_fingerprint_id"] = $cybs_integrator->get_fingerprint_org_id($data["debug"]);

        $data += $this->checkout_model->psform_content();

        $this->checkout_model->prepare_js_credit_card_parameter($data);
        $this->template->add_js('/checkout_redirect_method/js_credit_card/'.$data['platform_curr'].'/'.$data['total_amount']);
/* end of payment gateway code */
//      var_dump($_SESSION);
//      var_dump($_SESSION["client"]);
        //$this->template->write_view('main_content', 'checkout/checkout_main_content', $data, TRUE);

        // meta info
/*
        $meta_title = 'Secure Checkout | ValueBasket';
        $meta_desc = 'ValueBasket is a global consumer electronics retailer bringing trusted and high quality brands and products to your doorstep at great prices.';
        $meta_keyword = 'gadgets, electronics, camera, camcorder, nikon, canon, apple, iphone, ipad, value, deal, low price, mobile phones, tablet, computing, audio, headphones, samsung, olympus, sony, panasonic, tokina, tamron';
        $this->template->add_title($meta_title);
        $this->template->add_meta(array('name'=>'description','content'=>$meta_desc));
        $this->template->add_meta(array('name'=>'keywords','content'=>$meta_keyword));
*/

        $data['data']['lang_text'] = $this->_get_language_file();
        if (!$data["allow_login"])
        {
            $data['data']['lang_text']['checkout_method'] = $data['data']['lang_text']['no_login_checkout_method'];
            $data['data']['lang_text']['guest_checkout_head'] = $data['data']['lang_text']['no_login_guest_checkout_head'];
        }

        $billing_data = array();
        $shipping_data = array();
        $cancel_from_pmgw =  $this->input->get('cancel_from_pmgw');
        if ($cancel_from_pmgw == 1)
        {
            $data["active_block_id"] = "payment";

            $billing_data['billing_email'] = $_SESSION["POSTFORM"]['email'];
            $billing_data['billing_firstname'] = $_SESSION["POSTFORM"]['forename'];
            $billing_data['billing_lastname'] = $_SESSION["POSTFORM"]['surname'];
            $billing_data['billing_company'] = $_SESSION["POSTFORM"]['companyname'];
            $billing_data['billing_client_id_no'] = $_SESSION["POSTFORM"]['client_id_no'];
            $billing_data['billing_telephone1'] = $_SESSION["POSTFORM"]['tel_1'];
            $billing_data['billing_telephone2'] = $_SESSION["POSTFORM"]['tel_2'];
            $billing_data['billing_telephone3'] = $_SESSION["POSTFORM"]['tel_3'];
            $billing_data['address1'] = $_SESSION["POSTFORM"]['address_1'];
            $billing_data['address2'] = $_SESSION["POSTFORM"]['address_2'];
            $billing_data['address3'] = $_SESSION["POSTFORM"]['address_3'];
            $billing_data['billing_city'] = $_SESSION["POSTFORM"]['city'];
            $billing_data['billing_state'] = $_SESSION["POSTFORM"]['state'];
            $billing_data['billing_post_code'] = $_SESSION["POSTFORM"]['postcode'];
            $billing_data['billing_country_id'] = $_SESSION["POSTFORM"]['country_id'];
            $billing_data['billing_use_for_shipping'] = $_SESSION["POSTFORM"]['ship_to_same_addr'];

            $shipping_data['shipping_firstname'] = $_SESSION["POSTFORM"]['del_first_name'];
            $shipping_data['shipping_lastname'] = $_SESSION["POSTFORM"]['del_last_name'];
            $shipping_data['shipping_company'] = $_SESSION["POSTFORM"]['del_company'];
            $shipping_data['shipping_telephone1'] = $_SESSION["POSTFORM"]['del_tel_1'];
            $shipping_data['shipping_telephone2'] = $_SESSION["POSTFORM"]['del_tel_2'];
            $shipping_data['shipping_telephone3'] = $_SESSION["POSTFORM"]['del_tel_3'];
            $shipping_data['shipping_address1'] = $_SESSION["POSTFORM"]['del_address_1'];
            $shipping_data['shipping_address2'] = $_SESSION["POSTFORM"]['del_address_2'];
            $shipping_data['shipping_address3'] = $_SESSION["POSTFORM"]['del_address_3'];
            $shipping_data['shipping_city'] = $_SESSION["POSTFORM"]['del_city'];
            $shipping_data['shipping_state'] = $_SESSION["POSTFORM"]['del_state'];
            $shipping_data['shipping_post_code'] = $_SESSION["POSTFORM"]['del_postcode'];
            $shipping_data['shipping_country_id'] = $_SESSION["POSTFORM"]['del_country_id'];
            $shipping_data['shipping_same_as_billing'] = $_SESSION["POSTFORM"]['ship_to_same_addr'];
        }
        else
        {
            $data["active_block_id"] = $this->getBlockTitle($this->_current_step);

            $billing_data['billing_email'] = empty($data['client']['email'])?'':$data['client']['email'];
            $billing_data['billing_firstname'] = empty($data['client']['forename'])?'':$data['client']['forename'];
            $billing_data['billing_lastname'] = empty($data['client']['surname'])?'':$data['client']['surname'];
            $billing_data['billing_client_id_no'] = empty($data['client']['client_id_no'])?'':$data['client']['client_id_no'];
            $billing_data['billing_company'] = empty($data['client']['companyname'])?'':$data['client']['companyname'];
            $billing_data['billing_telephone1'] = empty($data['client']['tel_1'])?'':$data['client']['tel_1'];
            $billing_data['billing_telephone2'] = empty($data['client']['tel_2'])?'':$data['client']['tel_2'];
            $billing_data['billing_telephone3'] = empty($data['client']['tel_3'])?'':$data['client']['tel_3'];
            $billing_data['address1'] = empty($data['client']['address_1'])?'':$data['client']['address_1'];
            $billing_data['address2'] = empty($data['client']['address_2'])?'':$data['client']['address_2'];
            $billing_data['address3'] = empty($data['client']['address_3'])?'':$data['client']['address_3'];
            $billing_data['billing_city'] = empty($data['client']['city'])?'':$data['client']['city'];
            $billing_data['billing_state'] = empty($data['client']['state'])?'':$data['client']['state'];
            $billing_data['billing_post_code'] = empty($data['client']['postcode'])?'':$data['client']['postcode'];
            $billing_data['billing_country_id'] = $data['selected_country'];
            $billing_data['billing_use_for_shipping'] = 1;

            $shipping_data['shipping_firstname'] = '';
            $shipping_data['shipping_lastname'] = '';
            $shipping_data['shipping_company'] = '';
            $shipping_data['shipping_telephone1'] = '';
            $shipping_data['shipping_telephone2'] = '';
            $shipping_data['shipping_telephone3'] = '';
            $shipping_data['shipping_address1'] = '';
            $shipping_data['shipping_address2'] = '';
            $shipping_data['shipping_address3'] = '';
            $shipping_data['shipping_city'] = '';
            $shipping_data['shipping_state'] = empty($data['client']['state'])?'':$data['client']['state'];
            $shipping_data['shipping_post_code'] = '';
            $shipping_data['shipping_country_id'] = '';
            $shipping_data['shipping_same_as_billing'] = 0;
        }
        $data['billing_data'] = $billing_data;
        $data['shipping_data'] = $shipping_data;

        $this->load_tpl('content', 'checkout/checkout_main_content', $data, TRUE);
    }

    public function set_current_step($step)
    {
        $this->_current_step = $step;
    }

    public function get_current_step()
    {
        return $this->_current_step;
    }

    public function get_header()
    {
        $this->load_template("template/checkout/checkout_onepage.php", $data);
    }

    public function getBlockStyle()
    {
        $blockStyle = array();

        $first_allow_list = $this->getAllowList($this->_current_step);
        $display_block = $this->getDisplayBlocks();
        foreach($this->block_list as $key => $value)
        {
            if (in_array($key, $first_allow_list) && in_array($key, $display_block))
                $blockStyle[$key] = "section allow";
            else if (in_array($key, $display_block))
                $blockStyle[$key] = "section";
            else
                $blockStyle[$key] = "hide";
        }
        return $blockStyle;
    }

    public function getAllowList($current_step)
    {
        $allow_list = array();
        foreach($this->block_list as $key => $value)
        {
            if ($value == $current_step)
                break;
            $allow_list[] = $value;
        }
        return $allow_list;
    }

    public function get_next_step($current_step)
    {
        $needReturn = false;
        $processing_block_list = $this->getDisplayBlocks();
        if (Checkout_onepage::MAXIMUM_STEP != $current_step)
        {
            foreach ($processing_block_list as $value)
            {
                if ($value == $current_step)
                {
                    $needReturn = true;
                    continue;
                }
                if ($needReturn)
                    return $value;
            }
        }
        else
            return "";
    }

    private function _displayBlock($key)
    {
        if ((PLATFORMID == "WEBPH") && ($key == "billing"))
            return false;
        else
            return true;
    }

    public function getDisplayBlocks()
    {
        $displayBlock = array();
        foreach($this->block_list as $key => $value)
        {
            if (!$this->_displayBlock($key))
                continue;
            $displayBlock[] = $value;
        }
        return $displayBlock;
    }

    public function getAllowLogin()
    {
        return $this->_displayBlock("billing");
    }

    public function getDisplayBlockList()
    {
        $hiddenList = "";
        foreach($this->block_list as $key => $value)
        {
            if (!$this->_displayBlock($key))
                continue;
            if ($hiddenList == "")
                $hiddenList .= $value;
            else
                $hiddenList .= "," . $value;
        }
        return $hiddenList;
    }

    public function getBlockList()
    {
        $blockList = "";
        foreach($this->block_list as $key => $value)
        {
            if ($blockList == "")
                $blockList .= $value;
            else
                $blockList .= "," . $value;
        }
        return $blockList;
    }

    public function getBlockTitle($step)
    {
        return $this->block_list[$step];
/*
        if ($step == Checkout_onepage::STEP_LOGIN)
            return $block_list[Checkout_onepage::STEP_LOGIN];
        else if ($step == Checkout_onepage::STEP_BILLING_INFORMATION)
            return "billing";
        else if ($step == Checkout_onepage::STEP_SHIPPING_INFORMATION)
            return "shipping";
        else if ($step == Checkout_onepage::STEP_PAYMENT_INFORMATION)
            return "payment";
        else if ($step == Checkout_onepage::STEP_OVERVIEW)
            return "overview";
*/
    }

    public function getSteps()
    {
        $steps = array();
/*
        if (!$this->isCustomerLoggedIn())
        {
            $steps['login'] = $this->getCheckout()->getStepData('login');
        }

        $stepCodes = array('billing', 'shipping', 'shipping_method', 'payment', 'review');

        foreach ($stepCodes as $step)
        {
            $steps[$step] = $this->getCheckout()->getStepData($step);
        }
*/
        return $steps;
    }

    public function is_same_listing_changing_country($new_country_id)
    {
        $platform_obj = $this->platform_biz_var_service->get_list_w_country_name(array("pbv.selling_platform_id like 'WEB%'" => NULL, 'c.id'=>$new_country_id), array('limit'=>1));
        $platform_id = $platform_obj->get_selling_platform_id();

//      print($platform_id);
//      echo PLATFORMID;

        $sku_list = array();
        foreach ($_SESSION['cart'][PLATFORMID] as $sku=>$qty)
        {
            $sku_list[] = $sku;
        }

        if (sizeof($sku_list) > 0)
        {
            $listing_list = $this->price_service->get_listing_info_list($sku_list, $platform_id);

            foreach ($listing_list as $obj)
            {
                if ($obj === FALSE)
                {
                    echo 'FALSE';
                    exit;
                }
                elseif (!(($obj->get_status() == 'I') && ($obj->get_qty() > 0)))
                {
                    echo 'FALSE';
                    exit;
                }
            }
        }
        echo 'TRUE';
    }

    public function calculate_delivery_surcharge($country_id = '', $postcode = '')
    {
        if (($country_id == '') || ($postcode == ''))
        {
            return FALSE;
        }

        $this->delivery_service->delivery_country_id = trim($country_id);
        $this->delivery_service->delivery_postcode = trim($postcode);

        $this->delivery_service->item_list = $_SESSION["cart"][PLATFORMID];
        if (($rs = $this->delivery_service->get_del_surcharge(TRUE)) && $rs["surcharge"])
        {
            $result = array('currency_id'=>$rs["currency_id"], 'surcharge'=>$rs["surcharge"]);
            return $result;
        }
        else
        {
            return FALSE;
        }
    }

    public function ajax_cal_del_surcharge($country_id = '', $postcode = '')
    {
        $total = 0;
        $data = $this->checkout_model->index_content();

        $chk_cart = $data['chk_cart'];
        for($i=0; $i<count($chk_cart); $i++)
        {
            $total += $chk_cart[$i]["price"] * $chk_cart[$i]["qty"];
            $total += $chk_cart[$i]["gst"];
        }

        $promo_disc_amount = '';
        $promo = $data["promo"];
        if ($promo["valid"] && isset($promo["disc_amount"]))
        {
            $promo_disc_amount = $promo["disc_amount"];
        }
        $grand_total = $total + $data["dc_default"]["charge"] - $promo_disc_amount;

        $result = $this->calculate_delivery_surcharge($country_id, $postcode);
        if ($result !== FALSE)
        {
            echo platform_curr_format(PLATFORMID, $result['surcharge']) . '||' . platform_curr_format(PLATFORMID, ($grand_total + $result['surcharge']));
        }
        else
        {
            echo '||' . platform_curr_format(PLATFORMID, $grand_total);
        }
    }
}
/* End of file checkout_one_page.php */
/* Location: ./app/public_controllers/checkout_onepage.php */
