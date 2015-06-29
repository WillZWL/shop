<?php

$ws_array = array(NULL, 'index');
if (in_array($GLOBALS["URI"]->segments[2], $ws_array))
{
    DEFINE ('ALLOW_REDIRECT_DOMAIN', 1);
}

require_once(BASEPATH . 'plugins/My_plugin/validator/postal_validator.php');

class Checkout extends PUB_Controller
{
    public function Checkout($allow_force_https=true)
    {
        parent::PUB_Controller();
        $this->load->helper(array('url', 'tbswrapper'));
        $this->load->library('template');
        $this->load->library('service/context_config_service');
        $this->load->library('service/display_banner_service');
        $this->load->library('service/affiliate_service');
        $this->load->library('service/exchange_rate_service');
        $this->load->library('service/country_service');
        $this->load->library('service/customer_service_info_service');
        $this->load->library('service/complementary_acc_service');

        $this->postal_validator = new Postal_validator();

        #tracking pixels need it for sbf#1658
        $this->load->model('marketing/category_model');

        if ($allow_force_https && ($this->context_config_service->value_of("force_https")))
        {
            if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != "on")
            {
                $httpsurl = str_replace("http://", "https://", current_url());
                if ($_SERVER['QUERY_STRING'] != "")
                {
                    $httpsurl .= "?".$_SERVER['QUERY_STRING'];
                }
                redirect ($httpsurl);
            }
        }
        $this->load->model('website/checkout_model');
    }

    public function index($debug=0, $tbs = 1)
    {
        $this->affiliate_service->add_af_cookie($_GET);

        if(isset($_SESSION["origin_website"]))
        {
            setcookie("originw", $_SESSION["origin_website"], time()+3600, "/");
        }
        else
        {
            setcookie("originw", ($_COOKIE["LS_siteID"] != ''?13:($_COOKIE["TRADEDOUBLER"] != ''?9:11)), time()+3600, "/");
        }

        unset($_SESSION["review"]);

        $data = $this->checkout_model->index_content();
        $this->checkout_model->payment_gateway_service->init_pmgw_srv("google");

/*
        if ($http_obj = $this->checkout_model->payment_gateway_service->get_pmgw_srv()->get_hi_dao()->get(array("name"=>$debug?"GOOGLE_PG_TEST":"GOOGLE_PG")))
        {
            $data["mid"] = $http_obj->get_username();
        }

        $data["pmgwlist"] = $this->checkout_model->payment_gateway_service->get_pp_dao()->get_list(array("platform_id"=>PLATFORMID, "status"=>1));

*/

        $data["debug"] = $debug;
        $data["step"] = 1;
        $data["notice"] = $_SESSION["NOTICE"];
        $data["message"] = $_SESSION["pmgw_message"];
        $data["bibit_model"] = $this->context_config_service->value_of("bibit_model");

        $data['display_id'] = 12;
        include_once(APPPATH . "language/WEB" . str_pad($data['display_id'], 6, '0', STR_PAD_LEFT) . "_" . get_lang_id() . ".php");
        $data["lang"] = $lang;

        $data["checkout_banner"] = $this->display_banner_service->get_publish_banner(12, 1, PLATFORMCOUNTRYID, get_lang_id(), "PB");

        unset($_SESSION["NOTICE"]);

        // Disable LP
        $data['no_lp'] = 1;

        if($tbs)
        {
            if ($_SESSION["POSTFORM"]["del_first_name"] == "" && $_SESSION["client"]["logged_in"])
            {
                $_SESSION["POSTFORM"] = $_SESSION["client"];
                $space_pos = strrpos($_SESSION["client"]["del_name"], ' ');
                $_SESSION["POSTFORM"]["del_first_name"] = substr($_SESSION["client"]["del_name"], 0, $space_pos);
                $_SESSION["POSTFORM"]["del_last_name"] = substr($_SESSION["client"]["del_name"], $space_pos + 1);
            }

            $this->checkout_model->psform_init_ajax($this, "WEBSITE");

            $data += $this->checkout_model->psform_content();
            $this->load_template("tbs_checkout.php", $data);
            //$this->load_view('checkout/checkout', $data);
        }
        else
        {
            $this->load_view('checkout/checkout', $data);
        }
        //$this->load_view('checkout/checkout', $data);
    }

    public function js_credit_card($platform_curr, $total_amount, $seq=1)
    {
        $data['lang_text'] = $this->_get_language_file();
        $this->checkout_model->js_credit_card($platform_curr, $total_amount, $data, $seq);
    }

    private function get_skuinfo ($so_no)
    {
        $so_items = $this->so_service->get_soi_dao()->get_items_w_name(array("so_no"=>$so_no), array("lang_id" => get_lang_id()));
        foreach($so_items as $value)
        {
            $sku = $value->get_prod_sku();

            if($listing_info = $this->product_model->get_listing_info($sku, PLATFORMID, get_lang_id()))
            {
                if(!$prod_info = $this->product_model->get_website_product_info($sku, PLATFORMID, get_lang_id()))
                {
                    $prod_info = $this->product_model->get_website_product_info($sku, PLATFORMID);
                }
                $brandname = $prod_info->get_brand_name();

                if($this->product_model->price_service->get(array("sku"=>$sku, "listing_status"=>"L", "platform_id"=>PLATFORMID)))
                {
                    if(!$cat_obj = $this->category_model->get_cat_info_w_lang(array("c.id"=>$prod_info->get_cat_id(), "ce.lang_id"=>get_lang_id(), "c.status"=>1), array("limit"=>1)))
                    {
                        $cat_obj = $this->category_model->get_cat_info_w_lang(array("c.id"=>$prod_info->get_cat_id(), "ce.lang_id"=>"en", "c.status"=>1), array("limit"=>1));
                    }

                    $localized_cat_name = $cat_obj->get_name();
                    if(!$sc_obj = $this->category_model->get_cat_info_w_lang(array("c.id"=>$prod_info->get_sub_cat_id(), "ce.lang_id"=>get_lang_id(), "c.status"=>1), array("limit"=>1)))
                    {
                        $sc_obj = $this->category_model->get_cat_info_w_lang(array("c.id"=>$prod_info->get_sub_cat_id(), "ce.lang_id"=>"en", "c.status"=>1), array("limit"=>1));
                    }
                    if (!$sc_obj)
                    {
                        mail("oswald-alert@eservicesgroup.com", "[VB] Product Cat not translated", $this->category_model->category_service->get_dao()->db->last_query(), "From: admin@valuebasket.com\r\n");
                    }
                    $localized_sc_name = $sc_obj->get_name();
                }
            }
            $s["brand"] = $brandname;
            $s["cat_name"] = $localized_cat_name;
            $s["sc_name"] = $localized_sc_name;
            $skuinfo[] = $s;
        }
        return $skuinfo;
    }

    public function order_confirm($pmgw, $debug=0)
    {
        # for tracking pixels
        $skuinfo[0]["brand"] = "";
        $skuinfo[0]["cat_name"] = "";
        $skuinfo[0]["sc_name"] = "";

        if ($pmgw == "paypal")
        {
            $vars["so_no"] = $this->input->get("so_no");

            $vars["token"] = $this->input->get("token");
            $vars["PayerID"] = $this->input->get("PayerID");
            $vars["confirm"] = 1;
            $data = $this->checkout_model->payment_gateway_service->response($pmgw, $vars, $debug);

            $data["so_no"] = $vars["so_no"];
            $data["token"] = $vars["token"];
            $data["PayerID"] = $vars["PayerID"];
            $data["debug"] = $debug;

            $data["delivery_country"] = $this->region_service->country_dao->get(array("id"=>$data["so"]->get_delivery_country_id()));
            $data["courier"] = $this->so_service->get_pbv_srv()->get_dt_dao()->get(array("id"=>$data["so"]->get_delivery_type_id()));
            $data["so_items"] = $this->so_service->get_soi_dao()->get_items_w_name(array("so_no"=>$vars["so_no"]), array("lang_id" => get_lang_id()));
//          var_dump($data["so_items"]);
            $data["client"] = $this->client_service->get(array("id"=>$data["so"]->get_client_id()));

            // Disable LP
            $data['no_lp'] = 1;

            $data["skuinfo"] = $this->get_skuinfo($vars["so_no"]);
            #$data["skuinfo"] = $this->get_sku_info("133586"); var_dump($data["skuinfo"]);  die();

            $data['lang_text'] = $this->_get_language_file('', 'checkout', 'order_confirm');
            $this->load_view('checkout/order_confirm_paypal', $data);
        }
        else
        {
            $data["skuinfo"] = $skuinfo;

            if ($_SESSION["cart"][PLATFORMID])
            {
                // Disable LP
                $data['no_lp'] = 1;

                $vars["platform_id"] = PLATFORMID;
                $data["debug"] = $debug;
                $data["payment_gateway"] = $pmgw;
                if ($pmgw == "bibit")
                {
                    $data["step"] = 3;
                    if ($this->context_config_service->value_of("bibit_model") == "redirect")
                    {
                        $this->load_view('checkout/order_confirm_redirect', $data);
                    }
                    else
                    {
                        if ($this->check_login("checkout/order_confirm/{$pmgw}/{$debug}?".$_SERVER['QUERY_STRING']))
                        {
                            $data["delivery"] = $this->input->get("delivery");
                            $_SESSION["review"] = $data["review"] = $this->input->get("review");
                            $chk_cart = $this->cart_session_service->get_detail(PLATFORMID);
                            $data["chk_cart"] = $chk_cart["cart"];
                            $data["dc"] = $chk_cart["dc"];
                            $data["promo"] = $chk_cart["promo"];
                            $this->load_view('checkout/order_confirm', $data);
                        }
                    }
                }
                elseif ($pmgw == "google")
                {
                    $vars["need_vat"] = 1;
                    $vars["website_url"] = base_url();
                    $vars["checkout_url"] = base_url()."checkout/index/".$debug;
                    $vars["response_url"] = base_url()."checkout/response/google/".$debug;
                    $this->checkout_model->payment_gateway_service->checkout($pmgw, $vars, $debug);
                }
            }
            else
            {
                redirect(base_url()."product_skype/index/".$debug);
            }
        }
    }

    public function process_checkout($card_code="", $debug=0)
    {
        $_SESSION["POSTFORM"] = $vars = $_POST;
        if (isset($_SESSION["POSTFORM"]["p_enc"]))
        {
            include_once(BASEPATH."libraries/Encrypt.php");
            $encrypt = new CI_Encrypt();
            $platform_id = $encrypt->decode($_SESSION["POSTFORM"]["p_enc"]);

            if ($this->so_service->get_pbv_srv()->selling_platform_dao->get(array("id"=>$platform_id)))
            {
                $vars["platform_id"] = $platform_id;
            }
            else
            {
                $this->payment_result(0);
            }
        }

        if (!isset($vars["platform_id"]))
        {
            $vars["platform_id"] = PLATFORMID;
        }

        if ($card_code == "paypal")
        {
            $pmgw = "paypal";
        }
        else
        {
            if ($pc_obj = $this->country_credit_card_service->get_pmgw_card_dao()->get(array("code"=>$card_code)))
            {
                $pmgw = $pc_obj->get_payment_gateway_id();
                $vars["payment_methods"] = $pc_obj->get_card_id();
            }
            else
            {
                $pmgw = $card_code;
            }
        }

        $vars["payment_gateway"] = $pmgw;
        switch ($pmgw)
        {
            case "bibit":
                if ($this->context_config_service->value_of("bibit_model") == "redirect")
                {
                    if ($this->check_login("checkout/index/{$debug}?".$_SERVER['QUERY_STRING']))
                    {
                        $_SESSION["review"] = $this->input->post("review");
                        $this->checkout_model->payment_gateway_service->checkout($pmgw, $vars, $debug);
                    }
                }
                else
                {
                    $this->checkout_model->payment_gateway_service->checkout($pmgw, $vars, $debug);
                }
                break;
            case "moneybookers":
            case "global_collect":
            case "paypal":
            case "w_bank_transfer":
                if ($_SESSION["client"]["logged_in"] && !$vars["email"])
                {
                    $vars["email"] = $_SESSION["client"]["email"];
                }
                if ($this->client_service->check_email_login($vars))
                {
                    if ($this->checkout_model->check_promo())
                    {
                        $this->checkout_model->payment_gateway_service->checkout($pmgw, $vars, $debug);
                    }
                    else
                    {
                        unset($_SESSION["promotion_code"]);
                        echo "
                            <script>
                                window.parent.ChgPromoMsg(0, 1);
                            </script>
                            ";
                        exit;
                    }
                }
                elseif($debug)
                {
                    var_dump("Error ".__LINE__." : ".$this->db->_error_message()." -- ".$this->db->last_query());
                }
                else
                {
                    $browser = get_browser(null, true);
                    $url = base_url()."checkout/payment_result/0";
                    if ($browser["javascript"])
                    {
                        echo "<script>top.document.location.href='$url';</script>";
                        exit;
                    }
                    else
                    {
                        redirect($url);
                    }
                }
                break;
        }
    }

    public function response($pmgw, $debug=0)
    {
        if ($pmgw == "bibit" && $this->context_config_service->value_of("bibit_model") == "redirect")
        {
            $vars["orderKey"] = $this->input->get("orderKey");
            $vars["paymentStatus"] = $this->input->get("paymentStatus");
            $vars["paymentAmount"] = $this->input->get("paymentAmount");
            $vars["paymentCurrency"] = $this->input->get("paymentCurrency");
            $vars["mac"] = $this->input->get("mac");
        }
        else
        {
            $vars = $_POST;
        }
        $this->checkout_model->payment_gateway_service->response($pmgw, $vars, $debug);
    }

    public function payment_result($success="", $so_no="")
    {
        # reset the tracking script first, shopzilla etc will also be appended
        $data['tracking_script'] ="";

        # for tracking pixels
        $skuinfo[0]["brand"] = "";
        $skuinfo[0]["cat_name"] = "";
        $skuinfo[0]["sc_name"] = "";

        // Disable LP
        $data['no_lp'] = 1;

        #$success = 1;
        #$so_no="133557";   works in DEV and most likely LIVE
        # https://dev.valuebasket.com/checkout/payment_result/1/133557?debug=1

        $data["success"] = $success;
        $data["so_no"] = $so_no;
        $data["skuinfo"] = $this->get_skuinfo($so_no);

        if (($success != "1") && ($success != "0"))
        {
            show_404('page');
        }
        if($so_no == "" && $success == 1)
        {
            show_404('page');
        }

        $rightKey = false;
        if ($urlKey = $this->input->get("key"))
        {
//probably yandex
            include_once(APPPATH . "libraries/service/payment_gateway_redirect_yandex_service.php");
            $yandex_service = new Payment_gateway_redirect_yandex_service();
            if ($so_no)
            {
                $calculated_md5 = $yandex_service->get_encoded_url_key($so_no);
                if ($urlKey == $calculated_md5)
                    $rightKey = true;
            }
        }
        if ($so_no)
        {
            if ($data["so"] = $this->checkout_model->so_service->get(array("so_no"=>$so_no)))
            {
                if ($_SESSION["client"]["id"] != $data["so"]->get_client_id() && (!$this->input->get("debug") && !$rightKey))
                {
                    show_404('page');
                }
                $data["client"] = $this->client_service->get(array("id"=>$data["so"]->get_client_id()));
                $data["skuinfo"] = $this->get_skuinfo($data["so_no"]);  # tracking pixels
                $data["country"] = $this->checkout_model->region_service->country_dao->get(array("id"=>$data["so"]->get_delivery_country_id()));
                $data["courier"] = $this->checkout_model->so_service->get_pbv_srv()->get_dt_dao()->get(array("id"=>$data["so"]->get_delivery_type_id()));
                $data["so_items"] = $this->checkout_model->so_service->get_soi_dao()->get_items_w_name(array("so_no"=>$so_no));
                $data["so_ps"] = $this->checkout_model->so_service->get_sops_dao()->get(array("so_no"=>$so_no));
                $data["so_ext"] = $this->checkout_model->so_service->get_soext_dao()->get(array("so_no"=>$so_no));
            }
            else
            {
                show_404('page');
            }
        }

        if (($success && $so_no) || (!$success && $_SESSION["pmgw_message"]))
        {
            $data["message"] = $_SESSION["pmgw_message"];
            unset($_SESSION["pmgw_message"]);
            $data["step"] = 4;

            $data["origin_website"] = isset($_COOKIE['originw'])?$_COOKIE['originw']:($_COOKIE["LS_siteID"] != ''?13:11);
            $data["review"] = $_SESSION["review"];
            $data["adwords"] = "1";
        }

        $data["is_dev_site"] = $this->context_config_service->value_of("is_dev_site");

        if($success)
        {
            $af_info = $this->affiliate_service->get_af_record();
            $data["tracking_data"]["affiliate_name"] = $af_info["af"];

            $data["tracking_data"]["total_amount"] = $data["so"]->get_amount();
            $data["tracking_data"]["so"] = $data["so"];
            $data["tracking_data"]["soi"] = $data["so_items"];
            $data["tracking_data"]["sops"] = $data["so_ps"];
            $data["tracking_data"]["client_email"] = $_SESSION["client"]["email"];
        }

        if($success)
        {
            $is_new_customer = "new";   # or old
            $product_id     = "";
            $product_name   = "";
            $product_price  = "";
            $product_units  = "";
            # calculate total price of cart
            $total_cart_price = 0;
            $total_item = 0;
            $google_prodid = "";
            foreach($data["so_items"] as $key=>$soi_obj)
            {
                $total_cart_price += ($soi_obj->get_unit_price() * $soi_obj->get_qty());
                $total_item += $soi_obj->get_qty();

                $product_id     .= "{$soi_obj->get_prod_sku()},";
                $product_name   .= "{$soi_obj->get_name()},";
                $product_price  .= "{$soi_obj->get_unit_price()},";
                $product_units  .= "{$soi_obj->get_qty()},";
                $product_category   .= "{$soi_obj->get_cat_name()},";
            }
            $total_cart_price = number_format($total_cart_price, 2, ".", "");

            # append the default affiliate tracking codes
            $data['tracking_script'] .= $this->affiliate_tracking($data["so"], $data["so_items"]);
            # SBF#2247
            $adroll = true;
            if ($adroll)
            {
                unset($param);  // remove rubbish, as it might have been used earlier
                $param['price'] = $total_cart_price;
                $param['ORDER_ID'] = $so_no;
                // $param['SKU'] = $product_id;
                // $param['ORDER_VALUE'] = $total_cart_price;
                // $param['PRODUCT_CATEGORY'] = $product_category;
                // $param['COUNTRY'] = PLATFORMCOUNTRYID;
                // $param['CURRENCY'] = $data["so"]->get_currency_id();

                $this->adroll_tracking_script_service->set_country_id(PLATFORMCOUNTRYID);
                $data['tracking_script'] .= $this->adroll_tracking_script_service->get_variable_code("payment_success", $param);
            }

#           SBF #2284 Tradedoubler FR pixel tag, SBF #2382 Tradedoubler ES , SBF #2645 Tradedoubler BE
#           To test this tracking pixel, need to first set cookie - use url in \app\public_controllers\td_redirect.php
            $td_af_id_array = array("TDES", "TDFR", "TDIT");
//remove tradedoubler, fire from GTM
//          if (in_array($af_info["af"], $td_af_id_array))
            if (false)
            {
#           SBF #2284 Tradedoubler variable js portion; only payment success page
                $this->tradedoubler_tracking_script_service->set_country_id(PLATFORMCOUNTRYID);
                $param_list = array();
                foreach($data["so_items"] as $key=>$soi_obj)
                {
                    $param_list["id"]    = $soi_obj->get_prod_sku();
                    $param_list["price"] = $soi_obj->get_unit_price();
                    $param_list["currency"] = $data["so"]->get_currency_id();
                    $param_list["name"]  = $soi_obj->get_name();
                    $param_list["qty"]   = $soi_obj->get_qty();
                    $product_list[]      = $param_list;
                }
                $param["order_id"] = $so_no;
                $param["order_value"] = $total_cart_price;
                $param["currency"] = $data["so"]->get_currency_id();

                $td_variable_code = $this->tradedoubler_tracking_script_service->get_variable_code("payment_success", $product_list, $param);
                $this->template->add_js($td_variable_code, "print");

                $tduid = "";
                $td_voucher = "";
                if (!empty($_SESSION["TRADEDOUBLER"]))
                    {$tduid = $_SESSION["TRADEDOUBLER"];}
                $reportInfo = "";
                $reportInfo = urlencode($reportInfo);
                if (!empty($_COOKIE["TRADEDOUBLER"]))
                    {$tduid = $_COOKIE["TRADEDOUBLER"];}

                switch (PLATFORMCOUNTRYID)
                {
                    case "FR":
                        $tradedoubler_pixel_script = '<img src="http://tbs.tradedoubler.com/report?organization=1830251&event=284280&orderNumber='.$so_no.'&orderValue='.$total_cart_price.'&currency=EUR&tduid='.$tduid.'" height="1" width="1" border="0"/>';
                        $this->template->add_js($tradedoubler_pixel_script, "print");

                        #sbf #3705
/*
                        $tradedoubler_pixel_script_2 = '<img src="http://tbs.tradedoubler.com/report?organization=1830251&event=306914&orderNumber='.$so_no.'&orderValue='.$total_cart_price.'&currency=EUR&tduid='.$tduid.'" height="1" width="1" border="0"/>';
                        $this->template->add_js($tradedoubler_pixel_script_2, "print");
*/
                        break;

                    case "ES":
                        $tradedoubler_pixel_script = '<img src="http://tbs.tradedoubler.com/report?organization=1830251&event=284280&orderNumber='.$so_no.'&orderValue='.$total_cart_price.'&currency=EUR&tduid='.$tduid.'" height="1" width="1" border="0"/>';
                        $this->template->add_js($tradedoubler_pixel_script, "print");
                        break;

                    case "BE":
                        $tradedoubler_pixel_script = '<img src="http://tbs.tradedoubler.com/report?organization=1830251&event=284280&orderNumber='.$so_no.'&orderValue='.$total_cart_price.'&currency=EUR&tduid='.$tduid.'" height="1" width="1" border="0"/>';
                        $this->template->add_js($tradedoubler_pixel_script, "print");
                        break;

                    case "IT":
                        #sbf #3710 include voucher codes

                        $tradedoubler_pixel_script = '<img src="http://tbs.tradedoubler.com/report?organization=1830251&event=284280&orderNumber='.$so_no.'&orderValue='.$total_cart_price.'&currency=EUR&tduid='.$tduid.'" height="1" width="1" border="0"/>';
                        if(200 < $total_cart_price && $total_cart_price <= 349)
                        {
                            $tradedoubler_pixel_script = '<img src="http://tbs.tradedoubler.com/report?organization=1830251&event=284280&voucher=IT2014TD200&orderNumber='.$so_no.'&orderValue='.$total_cart_price.'&currency=EUR&tduid='.$tduid.'" height="1" width="1" border="0"/>';
                        }
                        else if(350 < $total_cart_price && $total_cart_price <= 499)
                        {
                            $tradedoubler_pixel_script = '<img src="http://tbs.tradedoubler.com/report?organization=1830251&event=284280&voucher=IT2014TD350&orderNumber='.$so_no.'&orderValue='.$total_cart_price.'&currency=EUR&tduid='.$tduid.'" height="1" width="1" border="0"/>';
                        }
                        else if(500 < $total_cart_price && $total_cart_price <= 799)
                        {
                            $tradedoubler_pixel_script = '<img src="http://tbs.tradedoubler.com/report?organization=1830251&event=284280&voucher=IT2014TD500&orderNumber='.$so_no.'&orderValue='.$total_cart_price.'&currency=EUR&tduid='.$tduid.'" height="1" width="1" border="0"/>';
                        }
                        else if(800 < $total_cart_price && $total_cart_price <= 999)
                        {
                            $tradedoubler_pixel_script = '<img src="http://tbs.tradedoubler.com/report?organization=1830251&event=284280&voucher=IT2014TD800&orderNumber='.$so_no.'&orderValue='.$total_cart_price.'&currency=EUR&tduid='.$tduid.'" height="1" width="1" border="0"/>';
                        }
                        else if($total_cart_price > 1000)
                        {
                            $tradedoubler_pixel_script = '<img src="http://tbs.tradedoubler.com/report?organization=1830251&event=284280&voucher=IT2014TDJACJACKPOT&orderNumber='.$so_no.'&orderValue='.$total_cart_price.'&currency=EUR&tduid='.$tduid.'" height="1" width="1" border="0"/>';
                        }

                        $this->template->add_js($tradedoubler_pixel_script, "print");
                        break;

                    default:
                        break;
                }
            }

            # SBF#2208 - www.shopperapproved.com
            $shopperapproved = true;
            if ($shopperapproved)
            {
                switch (PLATFORMCOUNTRYID)
                {
                    case "GB":
                    case "US":
                    // case "AU":
                    case "HK":
                    case "IE":
                    case "SG":
                    case "MY":
                    case "NZ":

                    #SBF#2843 - enabled BE and FR
                    case "BE":
                    case "FR":
                        $add = true;
                        break;

                    #SBF2243
                    case "ES":
                        // BE FR n ES removed by SBF2274
                        // $add = true;
                        // break;
                    default: $add = false;
                        break;
                }

                if ($add)
                    $data['tracking_script'] .= <<<shopperapproved
                <script type="text/javascript" src="https://www.shopperapproved.com/thankyou/sv-draw_js.php?site=6801"></script>
                <script src="https://www.shopperapproved.com/thankyou/opt-in.js" type="text/javascript"></script>
shopperapproved;
            }

            # SBF#5476 - ResellerRatings
            $resellerratings = true;
            if ($resellerratings)
            {
                switch (PLATFORMCOUNTRYID)
                {
                    case "AU":
                        $add = true;
                        $sellerid = "48341";
                        break;

                    default: $add = false;
                        break;
                }

                if ($add)
                    $data['tracking_script'] .= <<<resellerratings

                        <script type="text/javascript">
                        var _rrES = {
                            seller_id: $sellerid,
                            email: "{$data["tracking_data"]["client_email"]}",
                            invoice: "$so_no"};
                        (function() {
                            var s=document.createElement('script');s.type='text/javascript';s.async=true;
                            s.src="https://www.resellerratings.com/popup/include/popup.js";var ss=document.getElementsByTagName('script')[0];
                            ss.parentNode.insertBefore(s,ss);
                        })();
                        </script>
resellerratings;
            }

            # SBF#1942
            $shopzilla_fr = true;
            if ($shopzilla_fr)
            {
                $is_new_customer = 1; # new customer
                $is_new_customer = 0; # old customer

                $data['tracking_script'] .= <<<shopzilla_fr
                    <script language="javascript">
                    <!--
                        /* shopzilla_fr Performance Tracking Data */
                        var mid            = '271185';
                        var cust_type      = '$customer_status';
                        var order_value    = '$total_cart_price';
                        var order_id       = '$so_no';
                        var units_ordered  = '$total_item';
                    //-->
                    </script>
                    <script language="javascript" src="https://www.shopzilla.com/css/roi_tracker.js"></script>
shopzilla_fr;
            }

            $become_eu = true;
            if ($become_eu)
            {
                $product_id     = trim($product_id, ",");
                $product_name   = trim($product_name, ",");
                $product_price  = trim($product_price, ",");
                $product_units  = trim($product_units, ",");

                $currency = $data["so"]->get_currency_id();
                $pangora_merchant_id = "59474";

                $data['tracking_script'] .= <<<become_eu
                    <!-- Become Sales Tracking Script V 1.0.0 - All rights reserved -->
                    <script language="JavaScript">
                    var pg_pangora_merchant_id='$pangora_merchant_id';
                    var pg_order_id='$so_no';
                    var pg_cart_size=' $total_item';
                    var pg_cart_value=' $total_cart_price';
                    var pg_currency='$currency';
                    var pg_customer_flag=' $is_new_customer';
                    var pg_product_id=' $product_id';
                    var pg_product_name=' $product_name';
                    var pg_product_price=' $product_price';
                    var pg_product_units=' $product_units';
                    </script>
                    <script language="JavaScript" src="https://clicks.pangora.com/
                    sales-tracking/salesTracker.js"></script>
                    <noscript><img src="https://clicks.pangora.com/
                    sales-tracking/$pangora_merchant_id/salesPixel.do" /></noscript>
become_eu;
            }

            # SBF#1972
            $shopping_com = false;
            if ($shopping_com)
            {
                $data['tracking_script'] .= <<<shopping_com_part1
                    <script type="text/javascript">
                    // shopping_com
                    var _roi = _roi || [];

                    _roi.push(['_setMerchantId',    '513170']); // required
                    _roi.push(['_setOrderId',       '$so_no']); // unique customer order ID
                    _roi.push(['_setOrderAmount',   '$total_cart_price']); // order total without tax and shipping
                    _roi.push(['_setOrderNotes',    '']); // notes on order, up to 50 characters
shopping_com_part1;

                foreach($data["so_items"] as $key=>$soi_obj)
                {
                    $data['tracking_script'] .= <<<shopping_com_part2
                        _roi.push(['_addItem',
                        '{$soi_obj->get_prod_sku()}',       // (Merchant sku)
                        '{$soi_obj->get_name()}',           // (Product name)
                        '{$data["skuinfo"]["cat_name"]}',   // (Category id)
                        '{$data["skuinfo"]["cat_name"]}',   // (Category name)
                        '{$soi_obj->get_unit_price()}',     // (Unit price)
                        '{$soi_obj->get_qty()}'             // (Item quantity)
                        ]);
shopping_com_part2;
                }

                $data['tracking_script'] .= <<<shopping_com_part3
                _roi.push(['_trackTrans']);
                </script>
                <script type="text/javascript" src="https://stat.dealtime.com/ROI/ROI2.js"></script>
shopping_com_part3;
            }

            //criteo script
            $enable_mediaforge_country = array('GB', 'AU', 'FR', 'ES');
            if(in_array(PLATFORMCOUNTRYID, $enable_mediaforge_country))
            {
#               mediaforge - added by SBF#1902
                $enable_mediaforge = true;
                if ($enable_mediaforge)
                {
                    if (PLATFORMCOUNTRYID == 'GB') $account_no = 1038;
                    if (PLATFORMCOUNTRYID == 'AU') $account_no = 1059;
                    if (PLATFORMCOUNTRYID == 'FR') $account_no = 1411; #SBF#2229
                    if (PLATFORMCOUNTRYID == 'ES') $account_no = 1519; #SBF#2404
#                   function add_js($script, $type = 'import', $defer = FALSE, $position = "header")
                    $this->template->add_js("//tags.mediaforge.com/js/$account_no?orderNumber=$so_no&price=$total_cart_price", "import", FALSE, "body");
                }

#               criteo - removed by SBF#1902
                $enable_criteo = false;
                if ($enable_criteo)
                {
                    if($data['is_http'])
                    {
                        $this->template->add_js("http://static.criteo.net/criteo_ld3.js");
                    }
                    else
                    {
                        $this->template->add_js("https://static.criteo.net/criteo_ld3.js");
                    }
                    foreach($data["so_items"] as $key=>$soi_obj)
                    {
                        if($key < 2)
                        {
                            $i = $key + 1;
                            $criteo_tag .= '&i'.$i.'='.$soi_obj->get_prod_sku().'&p'.$i.'='.$soi_obj->get_unit_price().'&q'.$i.'='.$soi_obj->get_qty();
                        }
                    }
                    $criteo_script =
                    '
                        document.write(\'<div id=\"cto_tr_7719984_ac\" style=\"display:none\">\');
                        document.write(\'<div class=\"ctoWidgetServer\">https:\/\/sslwidget.criteo.com\/pvx\/<\/div>\');
                        document.write(\'<div class=\"ctoDataType\">transaction<\/div>\');
                        document.write(\'<div class=\"ctoParams\">wi=7719984&t='.$so_no.'&s=1'.$criteo_tag.'<\/div>\');
                        document.write(\'<\/div>\');
                    ';
                    $this->template->add_js($criteo_script, 'embed');
                }
            }
        }

        $contact_info_list = $this->customer_service_info_service->get_cs_contact_list_by_country(array("type"=>"WEBSITE", "platform_country_id"=>PLATFORMCOUNTRYID));
        foreach ($contact_info_list as $contact_info_row)
        {
            $trim_lang_id = substr(lang_part(), 1, stripos(lang_part(), "_") - 1);
            if ($contact_info_row["lang_id"] == $trim_lang_id)
            {
                $data['contact_info'] = $contact_info_row;
                break;
            }
        }

        if ($success)
        {
            if (isset($_SESSION['1stPaymentFail']))
                unset($_SESSION['1stPaymentFail']);
        }
        else
        {
            $data = array_merge($data, $this->checkout_model->index_content());
            $data["postform"] = $_SESSION["POSTFORM"];

            // SBF #2236, GST checking
            $need_gst_display = FALSE;
            if (PLATFORMCOUNTRYID == 'NZ')
            {
                $need_gst_display = TRUE;
            }

            $gst_total = 0;
            $gst_order = FALSE;
            if ($need_gst_display)
            {
                $chk_cart = $data['chk_cart'];

                foreach($chk_cart AS $key=>$val)
                {
                    $gst_total += $val["gst"];
                }

                if ($gst_total > 0)
                {
                    $gst_order = TRUE;
                }
            }
            $data['gst_order'] = $gst_order;
            $data['gst_total'] = $gst_total;

            $promo_disc_amount = '';
            $promo = $data["promo"];
            if ($promo["valid"] && isset($promo["disc_amount"]))
            {
                $promo_disc_amount = $promo["disc_amount"];
            }
            $data['promo_disc_amount'] = $promo_disc_amount;

            $data['payment_retry'] = TRUE;
            $data['site_down'] = (($this->input->get("type") == "sitedown") || ($this->input->get("type") == "assistant")) ? true:false;
            if (isset($_SESSION['1stPaymentFail']))
            {
                if ((time() - $_SESSION['1stPaymentFail']) > 3600)
                {
                    $data['payment_retry'] = FALSE;
                    unset($_SESSION['1stPaymentFail']);
                }
            }
            else
            {
                $_SESSION['1stPaymentFail'] = time();
            }

            include_once(APPPATH . "libraries/service/cybersource/cybersource_integrator.php");
            $cybs_integrator = new Cybersource_integrator();
            $merchant_info = $cybs_integrator->get_merchant_Id($_SESSION['domain_platform']['platform_country_id'], $_SESSION["domain_platform"]["platform_currency_id"]);
            $data["cybersource_fingerprint"] = session_id();
            $data["cybersource_fingerprint_label"] = $merchant_info["merchantId"] . session_id();
            $data["debug"] = ($this->input->get_post("debug") == 1 ? 1 : 0);
            $data["cybersource_fingerprint_id"] = $cybs_integrator->get_fingerprint_org_id($data["debug"]);

            $data += $this->checkout_model->psform_content();
            $this->checkout_model->prepare_js_credit_card_parameter($data);
            $this->template->add_js('/checkout_redirect_method/js_credit_card/'.$data['platform_curr'].'/'.$data['total_amount'].'/2');
        }

        // meta tag
        $data['data']['lang_text'] = $this->_get_language_file('', 'checkout', 'payment_result');
        if($success)
        {
            $this->template->add_title($data['data']['lang_text']['payment_accepted']);
        }
        else
        {
            $this->template->add_title($data['data']['lang_text']['payment_failure']);
        }
        $this->template->add_meta(array('name'=>'description','content'=>$data['data']['lang_text']['meta_desc']));
        $this->template->add_meta(array('name'=>'keywords', 'content'=>$data['data']['lang_text']['meta_keyword']));
        $this->template->add_js('/js/checkform.js');
        $this->template->add_js('/js/payment_gateway.js');
        $this->template->add_js('/js/checkout.js');
        $this->template->add_css(add_css_helper('css/checkout_onepage.css', TRUE));
        $this->template->add_css(add_css_helper('css/lytebox.css', TRUE));
        $this->template->add_css(add_css_helper('css/lytebox_ext.css', TRUE));
        $this->affiliate_service->remove_af_record();
        $this->load_tpl('content', 'tbs_payment_result', $data, TRUE, FALSE);
        //$this->load_view('checkout/payment_result_'.get_lang_id(),$data);
    }

    public function affiliate_tracking($so_obj, $soi_obj_list)
    {

        $this->load->library('service/affiliate_service');
        $this->load->library('service/platform_biz_var_service');

        $af_info = $this->affiliate_service->get_af_record();

        $payment_gateway_id = $this->so_service->get_so_payment_gateway($so_no);
        # calculate total price of cart
        $total_cart_price = 0;
        $total_item = 0;
        foreach($soi_obj_list as $soi_obj)
        {
            $total_cart_price += ($soi_obj->get_unit_price() * $soi_obj->get_qty());
            $total_item += $soi_obj->get_qty();
        }

        $so_no = $so_obj->get_so_no();
        if($af_info['af'])
        {
            switch($af_info['af'])
            {
                case "TAGSG":   # updated by SBF#2148
                    # SBF#2070
                    $so_no = $so_obj->get_so_no();
                    return <<<enable_TAG
<img src='https://www.tagserve.sg/saleServlet?MID=120&PID=132&CRID=1&ORDERID={$so_no}&ORDERAMNT=$total_cart_price&NUMOFITEMS=$total_item&SUBID=$payment_gateway_id' border='0' width='1' height='1'>
enable_TAG;
                    break;
                case 'LS':
                    $to_currency="GBP";
                    $ls_id="37439";
                case 'LSAU':
                    if($af_info['af'] != 'LS')
                    {
                        $to_currency="AUD";
                        $ls_id="37893";
                    }
                case 'LSNZ':
                    if($af_info['af'] != 'LS')
                    {
                        $to_currency="AUD";
                        $ls_id="37893";
                    }
                    $valid_id = "/^[-a-zA-Z0-9._\/*]{34}$/";
                    if(preg_match($valid_id, $_COOKIE["LS_siteID"]))
                    {
                        $ls_site_id = $_COOKIE["LS_siteID"];
                        $ls_time_enter = $_COOKIE["LS_timeEntered"];

                        if(count($soi_obj_list) > 0)
                        {
                            $pbv_obj = $this->platform_biz_var_service->get(array("selling_platform_id"=>PLATFORMID));
                            $ex_rate_obj = $this->exchange_rate_service->get(array("from_currency_id"=>$so_obj->get_currency_id(), "to_currency_id"=>$to_currency));
                            $ex_rate = $ex_rate_obj->get_rate();

                            foreach($soi_obj_list as $soi_obj)
                            {
                                $sku[$soi_obj->get_line_no()] = $soi_obj->get_prod_sku();
                                $qty[$soi_obj->get_line_no()] = $soi_obj->get_qty();
                                $vat = $soi_obj->get_amount() * $pbv_obj->get_vat_percent() / ($pbv_obj->get_vat_percent() + 100);
                                $amount[$soi_obj->get_line_no()] = round(($soi_obj->get_amount() - $vat) * 100 * $ex_rate);
                            }
                            $skulist = implode("|", $sku);
                            $qlist = implode("|", $qty);
                            $amtlist = implode("|", $amount);
                        }
                        $this->template->add_js("https://track.linksynergy.com/ep?mid={$ls_id}&ord={$so_obj->get_so_no()}&skulist={$skulist}&qlist={$qlist}&amtlist={$amtlist}&cur={$to_currency}");
                    }
                    break;
                case 'SB':
                    if($so_obj)
                    {
                        return "<img src='https://www.shopbot.com.au/tracking/sale?shopID=w1e90wg83gt&amount={$so_obj->get_amount()}&orderID={$so_obj->get_so_no()}' width='0' height='0'>";
                    }
                    break;
                case 'SBNZ':
                    if($so_obj)
                    {
                        return "<img src='https://www.shopbot.com.au/tracking/sale?shopID=n278e49540&amount={$so_obj->get_amount()}&orderID={$so_obj->get_so_no()}' width='0' height='0'>";
                    }
                    break;
                case 'MY':
                    if($so_obj)
                    {
                        return "<img height=0 width=0 src='https://www.myshopping.com.au/sale.asp?mid=26018035&amount={$so_obj->get_amount()}&order={$so_obj->get_so_no()}'>";
                    }
                    break;
                case 'GP':
                    if($so_obj)
                    {
                        return "<!-- Getprice.com.au sales tracking system -->
                                <img height='1' width='1' border='0' src='https://secure.getprice.com.au/affsale.asp?shopid=2849&price={$so_obj->get_amount()}&sid={$so_obj->get_so_no()}'>
                                <!-- End Getprice.com.au -->";
                    }
                    break;
                case "PPSG":
                    if($so_obj)
                    {
                        return "
                                <!-- PricePanda SG Tracking Pixel -->
                                <noscript><iframe src='//www.googletagmanager.com/ns.html?id=GTM-TSM2'
                                height='0' width='0' style='display:none;visibility:hidden'></iframe></noscript>
                                <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0], j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
                                '//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
                                })(window,document,'script','dataLayer','GTM-TSM2');</script>
                                <!-- End PricePanda SG Tracking Pixel -->
                                <input type='hidden' name='pp_amount' id='pp_amount' value='" . $so_obj->get_amount() . "'>
                                <input type='hidden' name='pp_order_id' id='pp_order_id' value='" . $so_obj->get_so_no() . "'>
                        ";
                    }
                    break;
                case "PPMY":
                    if($so_obj)
                    {
                        return "
                                <!-- PricePanda MY Tracking Pixel -->
                                <noscript><iframe src='//www.googletagmanager.com/ns.html?id=GTM-TKT9'
                                height='0' width='0' style='display:none;visibility:hidden'></iframe></noscript>
                                <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0], j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
                                '//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
                                })(window,document,'script','dataLayer','GTM-TKT9');</script>
                                <!-- End PricePanda MY Tracking Pixel -->
                                <input type='hidden' name='pp_amount' id='pp_amount' value='" . $so_obj->get_amount() . "'>
                                <input type='hidden' name='pp_order_id' id='pp_order_id' value='" . $so_obj->get_so_no() . "'>
                        ";
                    }
                    break;
                default:
            }
        }
    }

    public function psform()
    {
        if ($_SESSION["POSTFORM"]["del_first_name"] == "" && $_SESSION["client"]["logged_in"])
        {
            $_SESSION["POSTFORM"] = $_SESSION["client"];
            $space_pos = strrpos($_SESSION["client"]["del_name"], ' ');
            $_SESSION["POSTFORM"]["del_first_name"] = substr($_SESSION["client"]["del_name"], 0, $space_pos);
            $_SESSION["POSTFORM"]["del_last_name"] = substr($_SESSION["client"]["del_name"], $space_pos + 1);
        }
        $this->checkout_model->psform_init_ajax($this, "WEBSITE");
        $data = $this->checkout_model->psform_content();
        $this->checkout_model->prepare_js_credit_card_parameter($data);
        $this->load_view("checkout/psform", $data);
    }

    public function update($sku ="", $qty="", $debug=0)
    {
        if($sku != "" && $qty != "")
        {
            $this->cart_session_model->update($sku, $qty, PLATFORMID);
        }

        $this->index($debug);
    }

    public function remove($sku = "", $debug=0)
    {
        if($sku != "")
        {
            $this->cart_session_model->remove($sku, PLATFORMID);
        }

        $this->index($debug);
    }

    //Make ajax function start with _, restricted direct access
    public function _check_state($country_id="", $type="", $cur_value="")
    {
        return $this->checkout_model->check_state($country_id, $type, $cur_value);
    }

    //Make ajax function start with _, restricted direct access
    public function _check_surcharge($values="", $old_surcharge = 0, $amount = 0)
    {
        return $this->checkout_model->check_surcharge($values, $old_surcharge, $amount);
    }

    public function _check_email_exists($email, $submit = 0)
    {
        return $this->checkout_model->check_email_exists($email, $submit);
    }

    public function cvc()
    {
        $this->load_view('checkout/cvc');
    }

    public function check_sess($platform = NULL)
    {
        $data["platform"] = is_null($platform) ? PLATFORMID : $platform;
        $data["chk_cart"] = $this->cart_session_service->get($platform);
        $this->load_view("checkout/check_sess", $data);
    }

    public function lytebox($publish_key)
    {
        $data['display_id'] = 12;
        include_once(APPPATH . "language/WEB" . str_pad($data['display_id'], 6, '0', STR_PAD_LEFT) . "_" . get_lang_id() . ".php");
        $data["lang"] = $lang;

        $this->load_view('banner/lytebox_'.$publish_key, $data);
    }

    public function is_allowed_postal($country_code, $postal_code)
    {
        $output = "0";  # allowed postal code

        $proceed = $this->postal_validator->is_valid
        (
            array
            (
                "LangCountryPair"       => $country_code,
                "PostalCode"            => $postal_code,
            )
        );

        if ($proceed)
        {
            if (!$this->country_service->is_allowed_postal($country_code, $postal_code))
                $output = "1";  # blocked postal code
        }
        else
            $output = "2";  # invalid postal code

        echo $output;
    }
}

/* End of file checkout.php */
/* Location: ./app/public_controllers/checkout.php */