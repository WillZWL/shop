<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Pmgw_voucher.php";

class Pmgw_paypal_service extends Pmgw_voucher
{
//paid by UK test account with credit card, not bank transfer can bring user to retry
    const MAXIMUM_NUMBER_OF_RETRIES = 2;
    public $pp_credit_check_amount = array("PL" => 350
    , "NZ" => 200
    , "MX" => 750);
    public $add_log = TRUE;
    public $return_false = FALSE;
    private $pp_acct = array("AU" => array(
        "user_name" => "paypal.au_api1.valuebasket.com",
        "password" => "D9Y2GJQ6UTUFRV99",
        "signature" => "A4g9iT0sUSG.P7zZbzg1Zzk45G2dAEArphtc64Bdk0AGRL1FH6Z7uwyz",
        "paypal_email_address" => "paypal.au@valuebasket.com"
    ),
        "NZ" => array(
            "user_name" => "paypal.nz_api1.valuebasket.com",
            "password" => "BKSWD8UL8ATWTRL9",
            "signature" => "ABmenUa3rzXzSL01wJgaPySeJFWHAk6NKFTDzNtr4PJ3zWDshZEY.k-j",
            "paypal_email_address" => "paypal.nz@valuebasket.com"
        ),
        "HK" => array(
            "user_name" => "paypal.value_api1.valuebasket.com",
            "password" => "MFPNL4R3L4RZMMD9",
            "signature" => "AcAzi6cdOUqlwwNTQdJJvBYf50OGANNcCBDh6bcE3STN3dw3Y6NhEhiB",
            "paypal_email_address" => "paypal.value@valuebasket.com"
        )
    );
    private $pp_debug_acct = array("AU" => array(
        "user_name" => "oswald_api1.eservicesgroup.com",
        "password" => "1380249734",
        "signature" => "AVESqx77e6GffwXNNVtYA3e5OMRXAYHVsr--ya8m-hYLSj9fTUgxlbc7",
        "paypal_email_address" => "oswald@eservicesgroup.com"
    ),
        "FR" => array(
            "user_name" => "oswald_1344414203_biz_api1.eservicesgroup.net",
            "password" => "1344414229",
            "signature" => "AMfVHpWfaR2TtXa0gTOjNp1cbyZ.AwRwMFUmcoXTl-ZoS5p-.CHVreXr",
            "paypal_email_address" => "oswald_1344414203_biz@eservicesgroup.net"
        ),
        "GB" => array(
            "user_name" => "oswald_1344402161_biz_api1.eservicesgroup.net",
            "password" => "1344402186",
            "signature" => "AJalutgZ0QlqdfRK3MRN5tqq73OgAq5SkWV2-vpKNp7ESSN2-3NCVw9C",
            "paypal_email_address" => "oswald_1344402161_biz@eservicesgroup.net"
        ),
        "US" => array(
            "user_name" => "oswald-facilitator_api1.eservicesgroup.com",
            "password" => "1380248968",
            "signature" => "AFcWxV21C7fd0v3bYYYRCpSSRl31AtRa4wXM1GDM6YUniq2PMOx.o21B",
            "paypal_email_address" => "oswald-facilitator@eservicesgroup.com"
        )
    );
    private $_sitedown_email = "oswald-alert@eservicesgroup.com";
    private $api_username;
    private $api_password;
    private $signature;
    private $paypal_email_address;
    private $payment_methods;
    private $api_url = "https://api-3t.paypal.com/nvp";
    private $paypal_url = "https://www.paypal.com/cgi-bin/webscr?";
    private $post_array = array();
    private $promo;
    private $so_item_list;

    private $error_type = 1;
    private $payment_status;
    private $remark;
    private $display_message;

    public function __construct()
    {
        parent::__construct();
        $CI =& get_instance();
        $CI->load->helper('url');
        $this->input = $CI->input;
        $this->api_username = $this->pp_acct["HK"]["user_name"];
        $this->api_password = $this->pp_acct["HK"]["password"];
        $this->signature = $this->pp_acct["HK"]["signature"];
        $this->paypal_email_address = $this->pp_acct["HK"]["paypal_email_address"];
    }

    public function init($vars)
    {
        $pbv_srv = $this->get_pbv_srv();
        $platform_obj = $pbv_srv->get_dao()->get(array("selling_platform_id" => $vars["platform_id"]));
        $vars["currency_id"] = $platform_obj->get_platform_currency_id();
        $so_srv = $this->get_so_srv();
        $so_obj = $so_srv->cart_to_so($vars);
        $this->payment_methods = $vars["payment_methods"];

        if ($so_obj === FALSE) {
            return FALSE;
        }
        $this->so = $so_obj;
        $this->store_af_info();
        $this->sops = $vars["sops"];
        $this->promo = $vars["promo"];
        $this->so_item_list = $vars["so_item_list"];

//      $this->logheader["message"] = var_export($this->so, true);
//      $this->logger->write_log($this->logheader);

        $this->client = $this->get_client_srv()->get(array("id" => $this->so->get_client_id()));
        if ($this->client === FALSE) {
            return FALSE;
        }

//      $this->logheader["message"] = var_export($this->client, true);
//      $this->logger->write_log($this->logheader);

    }

    public function checkout($debug = 0)
    {
        $this->check_debug($debug);

        $ar_result = $this->set_express_checkout_api();

        if ($ar_result["ACK"] == "Success") {
            $this->sops->set_mac_token($ar_result["TOKEN"]);
            $this->sops->set_retry(1);

            if ($this->get_so_srv()->get_sops_dao()->update($this->sops)) {
                if (defined('ENTRYPOINT') && (ENTRYPOINT == "MOBILE"))
                    $cmd = "_express-checkout-mobile";
                else
                    $cmd = "_express-checkout";
                $url = $this->paypal_url . "cmd=" . $cmd . "&token=" . $ar_result["TOKEN"];
                jsredirect($url, "", "top");
            } else {
                $this->redirect_fail();
            }
        } else {
            $this->alert_fail($ar_result);
        }
    }

    public function check_debug($debug = 0)
    {
        if ($debug && !$this->get_config()->value_of("payment_debug_allow")) {
            $debug = 0;
        }

        if ($debug) {
            $this->api_url = "https://api-3t.sandbox.paypal.com/nvp";
            $this->paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr?";
        }
        $this->debug = $debug;
    }

    private function set_express_checkout_api()
    {
        unset($this->post_array);
        $this->post_array["METHOD"] = "SetExpressCheckout";
        $this->post_array["PAYMENTREQUEST_0_PAYMENTACTION"] = "Sale";
        $this->post_array["PAYMENTREQUEST_0_INVNUM"] = $this->so->get_so_no();
        $this->post_array["PAYMENTREQUEST_0_AMT"] = $this->so->get_amount();
        $this->post_array["PAYMENTREQUEST_0_CURRENCYCODE"] = $this->so->get_currency_id();
        $this->post_array["PAYMENTREQUEST_0_SHIPPINGAMT"] = $this->so->get_delivery_charge();
        if ($this->payment_methods != "paypal") {
//checking the card id
            $this->post_array["LANDINGPAGE"] = "Billing";
        }

        if ($this->so_item_list) {
            foreach ($this->so_item_list as $soi_obj) {
                $line_no = $soi_obj->get_line_no() - 1;
                $this->post_array["L_PAYMENTREQUEST_0_NAME" . $line_no] = $soi_obj->get_prod_name();
                $this->post_array["L_PAYMENTREQUEST_0_NUMBER" . $line_no] = $soi_obj->get_prod_sku();
                $this->post_array["L_PAYMENTREQUEST_0_QTY" . $line_no] = $soi_obj->get_qty();
                $this->post_array["L_PAYMENTREQUEST_0_AMT" . $line_no] = $soi_obj->get_unit_price();
            }
        }

        if ($this->promo["valid"]) {
            $line_no++;
            $this->post_array["L_PAYMENTREQUEST_0_NAME" . $line_no] = $this->promo["promotion_code_obj"]->get_code();
            $this->post_array["L_PAYMENTREQUEST_0_DESC" . $line_no] = substr($this->promo["promotion_code_obj"]->get_description(), 0, 125);
            $this->post_array["L_PAYMENTREQUEST_0_AMT" . $line_no] = $this->promo["disc_amount"] * -1;
        }

        $this->post_array["PAYMENTREQUEST_0_ITEMAMT"] = $this->so->get_amount() - $this->so->get_delivery_charge();


        $this->post_array["RETURNURL"] = str_replace('http://', 'https://', base_url()) . "checkout_onepage/order_confirm/paypal/{$this->debug}?so_no={$this->so->get_so_no()}";
        //$this->post_array["CANCELURL"] = str_replace('http://', 'https://', base_url()).($this->so->get_biz_type()=="ONLINE"?"checkout_onepage":"product_skype").($this->debug?"/index/1":"");
        $this->post_array["CANCELURL"] = str_replace('http://', 'https://', base_url()) . "checkout_onepage" . ($this->debug ? "/index/1" : "") . "?cancel_from_pmgw=1";

        $this->post_array["LOCALECODE"] = $this->client->get_country_id();
        $this->post_array["HDRIMG"] = "https://" . $_SERVER['HTTP_HOST'] . "/images/value_basket_logo.png";

//      $this->post_array["NOSHIPPING"] = 1;
        $this->post_array["ADDROVERRIDE"] = 1;
        $this->post_array["PAYMENTREQUEST_0_SHIPTONAME"] = $this->so->get_delivery_name();
        $ar_del_add = explode("|", $this->so->get_delivery_address());
        $this->post_array["PAYMENTREQUEST_0_SHIPTOSTREET"] = $ar_del_add[0];
        $this->post_array["PAYMENTREQUEST_0_SHIPTOSTREET2"] = $ar_del_add[1];
        $this->post_array["PAYMENTREQUEST_0_SHIPTOCITY"] = $this->so->get_delivery_city();
        $this->post_array["PAYMENTREQUEST_0_SHIPTOSTATE"] = $this->so->get_delivery_state();
        $this->post_array["PAYMENTREQUEST_0_SHIPTOZIP"] = $this->so->get_delivery_postcode();
        $this->post_array["PAYMENTREQUEST_0_SHIPTOCOUNTRY"] = $this->so->get_delivery_country_id();

        return $this->connect();
    }

    private function connect($need_set_acct = 1)
    {
        if ($need_set_acct)
            $this->_set_pp_acct();
        $http = $this->get_http();
        $http->set_remote_site($this->api_url);
        $post_fields = @http_build_query($this->get_postfields());
        if ($this->add_log) {
            $this->get_sopl_srv()->add_log($this->so->get_so_no(), "O", str_replace("&", "\n&", urldecode($post_fields)));
        }
        $http->get_hcs()->set_postfields($post_fields);
        $http->set_timeout(45);
        if ($rs = $http->get_content()) {
            $rs = urldecode($rs);
            if ($this->add_log) {
                $this->get_sopl_srv()->add_log($this->so->get_so_no(), "I", str_replace("&", "\n&", $rs));
            }
            parse_str($rs, $ar_result);
            return $ar_result;
        } else {
            mail($this->_sitedown_email, "[VB] Paypal payment issue", "No message provided, timeout value=45", 'From: website@valuebasket.com');
            if ($this->return_false) {
                return FALSE;
            } else {
                $this->redirect_fail();
            }
        }
    }

    private function _set_pp_acct()
    {
        if ($this->debug) {
            if ($this->so->get_currency_id() == "AUD") {
                $this->api_username = $this->pp_debug_acct["AU"]["user_name"];
                $this->api_password = $this->pp_debug_acct["AU"]["password"];
                $this->signature = $this->pp_debug_acct["AU"]["signature"];
                $this->paypal_email_address = $this->pp_debug_acct["AU"]["paypal_email_address"];
            } else if ($this->so->get_currency_id() == "EUR") {
                $this->api_username = $this->pp_debug_acct["FR"]["user_name"];
                $this->api_password = $this->pp_debug_acct["FR"]["password"];
                $this->signature = $this->pp_debug_acct["FR"]["signature"];
                $this->paypal_email_address = $this->pp_debug_acct["FR"]["paypal_email_address"];
            } else if ($this->so->get_currency_id() == "USD") {
                $this->api_username = $this->pp_debug_acct["US"]["user_name"];
                $this->api_password = $this->pp_debug_acct["US"]["password"];
                $this->signature = $this->pp_debug_acct["US"]["signature"];
                $this->paypal_email_address = $this->pp_debug_acct["US"]["paypal_email_address"];
            } else {
                $this->api_username = $this->pp_debug_acct["GB"]["user_name"];
                $this->api_password = $this->pp_debug_acct["GB"]["password"];
                $this->signature = $this->pp_debug_acct["GB"]["signature"];
                $this->paypal_email_address = $this->pp_debug_acct["GB"]["paypal_email_address"];
            }
        } else {
            if ($this->so->get_currency_id() == "AUD") {
                $this->api_username = $this->pp_acct["AU"]["user_name"];
                $this->api_password = $this->pp_acct["AU"]["password"];
                $this->signature = $this->pp_acct["AU"]["signature"];
                $this->paypal_email_address = $this->pp_acct["AU"]["paypal_email_address"];
            } /*
            else if ($this->so->get_currency_id() == "NZD")
            {
                $this->api_username = $this->pp_acct["NZ"]["user_name"];
                $this->api_password = $this->pp_acct["NZ"]["password"];
                $this->signature = $this->pp_acct["NZ"]["signature"];
                $this->paypal_email_address = $this->pp_acct["NZ"]["paypal_email_address"];
            }
*/
            else {
                $this->api_username = $this->pp_acct["HK"]["user_name"];
                $this->api_password = $this->pp_acct["HK"]["password"];
                $this->signature = $this->pp_acct["HK"]["signature"];
                $this->paypal_email_address = $this->pp_acct["HK"]["paypal_email_address"];
            }
        }
    }

    private function get_postfields()
    {
        $postfields = array();
        $postfields["USER"] = $this->api_username;
        $postfields["PWD"] = $this->api_password;
        $postfields["SIGNATURE"] = $this->signature;
        $postfields["VERSION"] = "64.4";
        return $postfields + $this->post_array;
    }

    public function redirect_fail($so_no = "")
    {
        redirect(base_url() . "checkout_onepage/payment_result/0/{$so_no}");
    }

    public function alert_fail($result)
    {
        echo
        "<script>
    alert('{$result["L_LONGMESSAGE0"]}');";
        if ($result["L_ERRORCODE0"] == "10729") {
            echo
            "   top.frames['psform'].document.fm_pmgw.del_state.focus();";

        } elseif ($result["L_ERRORCODE0"] == "10706") {
            echo
            "   top.frames['psform'].document.fm_pmgw.del_postcode.focus();";

        }

        echo
        "   top.frames['psform'].myLytebox.end();
</script>";
    }

    public function response($vars, $debug)
    {
        $this->check_debug($debug);
        $this->get_sopl_srv()->add_log($vars["so_no"], "I", str_replace("&", "\n&", $_SERVER['QUERY_STRING']));
        $so_srv = $this->get_so_srv();
        $sops_dao = $so_srv->get_sops_dao();
        if (($this->sops = $sops_dao->get(array("so_no" => $vars["so_no"]))) && ($this->so = $so_srv->get(array("so_no" => $vars["so_no"])))) {

            if ($this->sops->get_payment_status() == 'S' || $this->so->get_status() > 1) {
                $_SESSION["pmgw_message"] = "Order was paid";
                $this->redirect_fail();
            }

            if ($vars["token"] != $this->sops->get_mac_token()) {
                $this->error_type = 1;
                $this->payment_status = "F";
                $this->remark = "token mismatch";
                $this->display_message = $result["display"]["ps"]["ERROR"];
                $this->result();
            } else {
                if ($vars["confirm"]) {
                    $this->sops->set_remark(trim($this->sops->get_remark() . "\n" . "status:pending"));
                    $this->sops->set_payment_status('P');

                    $ar_result = $this->get_express_checkout_details_api();
                    if ($ar_result["ACK"] == "Success") {
                        $this->sops->set_payer_email($ar_result["EMAIL"]);
                        $this->sops->set_payer_ref($ar_result["PAYERID"]);
                        $this->sops->set_risk_ref3(isset($ar_result["ADDRESSSTATUS"]) ? $ar_result["ADDRESSSTATUS"] : $ar_result["PAYMENTREQUEST_0_ADDRESSSTATUS"]);
                        $this->sops->set_risk_ref4($ar_result["PAYERSTATUS"]);
                        $sops_dao->update($this->sops);
                    } else {
                        $sops_dao->update($this->sops);
                        $_SESSION["pmgw_message"] = $result["display"]["ps"]["ERROR"];
                        $this->redirect_fail();
                    }
                    return array("so" => $this->so, "paypal_email" => $ar_result["EMAIL"]);

                } else {
                    if ($vars["pf_email"]) {
                        if (!($this->client = $this->get_client_srv()->get(array("email" => $vars["pf_email"])))) {
                            $this->client = $this->get_client_srv()->get(array("id" => $this->so->get_client_id()));
                            $this->client->set_email($vars["pf_email"]);
                            $this->client->set_forename($this->client->get_del_name());
                            $this->client->set_surname('');
                            $this->client->set_companyname($this->client->get_del_company());
                            $this->client->set_address_1($this->client->get_del_address_1());
                            $this->client->set_address_2($this->client->get_del_address_2());
                            $this->client->set_address_3($this->client->get_del_address_3());
                            $this->client->set_postcode($this->client->get_del_postcode());
                            $this->client->set_city($this->client->get_del_city());
                            $this->client->set_state($this->client->get_del_state());
                            $this->client->set_country_id($this->client->get_del_country_id());
                            $this->client = $this->get_client_srv()->insert($this->client);
                        }
                        if ($this->client) {
                            $this->so->set_client_id($this->client->get_id());
                            $this->get_client_srv()->object_login($this->client);
                        }
                    }

                    $ar_result = $this->do_express_checkout_payment_api();

                    if ($ar_result["ACK"] == "Success") {
                        $this->sops->set_risk_ref1($ar_result["PAYMENTINFO_0_PROTECTIONELIGIBILITY"]);
                        $this->sops->set_risk_ref2($ar_result["PAYMENTINFO_0_PROTECTIONELIGIBILITYTYPE"]);
                        $sops_dao->update($this->sops);
                        $this->so->set_txn_id($ar_result["PAYMENTINFO_0_TRANSACTIONID"]);
                        switch ($ar_result["PAYMENTINFO_0_PAYMENTSTATUS"]) {
                            /*
                                                        case "Pending":
                                                            $ar_pd_result = $this->do_authorization_api();
                                                            switch ($ar_pd_result["PAYMENTSTATUS"])
                                                            {

                                                                case "Completed":
                                                                    $this->error_type = 0;
                                                                    $this->payment_status = "S";
                                                                    $this->remark = "status:processed";
                                                                    $this->display_message = $result["display"]["ps"]["AUTHORISED"];
                                                                    break;
                                                                default:
                                                                    $this->error_type = 0;
                                                                    $this->payment_status = "F";
                                                                    $this->remark = "status:failed";
                                                                    $this->display_message = $result["display"]["ps"]["ERROR"];
                                                            }
                                                            break;
                            */
                            case "Completed":
                                $this->error_type = 0;
                                $this->payment_status = "S";
                                $this->remark = "status:processed";
                                $this->display_message = $result["display"]["ps"]["AUTHORISED"];
                                break;
                            default:
                                $this->error_type = 0;
                                $this->payment_status = "F";
                                $this->remark = "status:failed";
                                $this->display_message = $result["display"]["ps"]["ERROR"];
                        }
                    } else if ($this->sops->get_retry() <= self::MAXIMUM_NUMBER_OF_RETRIES) {
                        $this->payment_status = "F";
                        $this->payment_retry();
                    } else {
                        $this->error_type = 1;
                        $this->payment_status = "F";
                        $this->remark = "status: failed";
                        $this->display_message = $result["display"]["ps"]["ERROR"];
                    }
                    $this->result();
                }
            }
        } else {
            $_SESSION["pmgw_message"] = "Order Not Found";
            $this->redirect_fail();
        }
    }

    public function result()
    {
        $so_srv = $this->get_so_srv();
        $sops_dao = $so_srv->get_sops_dao();

        $old_ps_status = $this->sops->get_payment_status();

        if ($old_ps_status != $this->payment_status) {
            $this->sops->set_payment_status($this->payment_status);
            $this->sops->set_pay_to_account($this->paypal_email_address);

            $this->sops->set_remark(trim($this->sops->get_remark() . "\n" . $this->remark));

            if ($this->payment_status == "S") {
                $this->sops->set_pay_date(date("Y-m-d H:i:s"));
            }

            $sops_dao->update($this->sops);


            if ($this->payment_status == "S") {
                $pbv_srv = $this->get_pbv_srv();
//              $this->so->set_expect_delivery_date($this->get_del_srv()->get_edd($this->so->get_delivery_type_id(), $this->so->get_delivery_country_id()));
                $this->so->set_status($this->require_credit_check() ? 2 : 3);

                if ($this->require_decision_manager()) {
                    $sor_data = array("risk_requested" => 0);
                    $this->sor_add($sor_data);
                }
                $so_srv->get_dao()->update($this->so);
            } elseif ($this->payment_status == "C" || ($this->payment_status == "F" && $old_ps_status != "S")) {
                $this->so->set_status(0);
                $so_srv->get_dao()->update($this->so);
            }

            if ($this->payment_status == "S") {

                //Add card info to so_credit_chk
                $socc_vo = $so_srv->get_socc_dao()->get();
                $socc_vo->set_so_no($this->so->get_so_no());
                $so_srv->get_socc_dao()->insert($socc_vo);
                $so_srv->update_website_display_qty($this->so);
//              $so_srv->set_profit_info($this->so);
                /*
                            // Tracking
                            $origin_website = isset($_COOKIE['originw'])?$_COOKIE['originw']:($_COOKIE["LS_siteID"] != ''?13:null);
                            $soext_vo = $so_srv->get_soext_dao()->get();
                            $soext_vo->set_so_no($this->so->get_so_no());
                            $soext_vo->set_conv_site_id($origin_website);
                            $soext_vo->set_conv_status(0);

                            if($_COOKIE["LS_siteID"] != '' && $_COOKIE["LS_siteID"] !='siteID')
                            {
                                $soext_vo->set_conv_site_ref($_COOKIE["LS_siteID"]);
                                $soext_vo->set_ls_time_entered($_COOKIE["LS_timeEntered"]);
                                // Insert ls_transaction
                                $this->add_ls_transaction($soext_vo);
                            }

                            $so_srv->get_soext_dao()->insert($soext_vo);
                */

                if ($promo_code = $this->so->get_promotion_code()) {
                    $this->update_promo($promo_code);
                }

                // Fire Event START
                $this->fire_success_event();
                // Fire Event END
            } elseif ($this->payment_status == "P") {
                // Fire Event START
                // Comment out in order to not send acknowledgement email
                //$this->fire_success_event(1);
                // Fire Event END
            }
        }

        $this->check_result();
    }

    public function require_credit_check()
    {
        if ($this->sops->get_risk_ref1() == "Eligible") {
            return FALSE;
        } else {
            if ($this->so->get_amount() <= 150) {
                return FALSE;
            } else
                return true;
        }
    }

    public function require_decision_manager()
    {
        return $this->require_credit_check();
    }

    public function check_result()
    {
        if ($this->sops->get_payment_status() == "N" || $this->sops->get_payment_status() == "F" || $this->so->get_status() == 0) {
            $this->redirect_fail($this->so->get_so_no());
        } else {
            $this->unset_variable();
            $this->redirect_success();
        }
    }

    public function redirect_success()
    {
        redirect(base_url() . "checkout_onepage/payment_result/1/{$this->so->get_so_no()}");
    }

    private function get_express_checkout_details_api()
    {
        unset($this->post_array);
        $this->post_array["METHOD"] = "GetExpressCheckoutDetails";
        $this->post_array["TOKEN"] = $this->sops->get_mac_token();

        return $this->connect();
    }

    private function do_express_checkout_payment_api()
    {
        unset($this->post_array);
        $this->post_array["METHOD"] = "DoExpressCheckoutPayment";
        $this->post_array["PAYMENTREQUEST_0_PAYMENTACTION"] = "Sale";
        $this->post_array["PAYERID"] = $this->sops->get_payer_ref();
        $this->post_array["PAYMENTREQUEST_0_AMT"] = $this->so->get_amount();
        $this->post_array["PAYMENTREQUEST_0_CURRENCYCODE"] = $this->so->get_currency_id();
        $this->post_array["RETURNFMFDETAILS"] = 1;
        $this->post_array["TOKEN"] = $this->sops->get_mac_token();

        return $this->connect();
    }

    public function payment_retry()
    {
        $this->sops->set_retry($this->sops->get_retry() + 1);
        $token = $this->sops->get_mac_token();
        if ($this->get_so_srv()->get_sops_dao()->update($this->sops)) {
            if (defined('ENTRYPOINT') && (ENTRYPOINT == "MOBILE"))
                $cmd = "_express-checkout-mobile";
            else
                $cmd = "_express-checkout";
            $url = $this->paypal_url . "cmd=" . $cmd . "&token=" . $token;
            redirect($url);
        } else {
            $this->redirect_fail();
        }
    }

    public function get_transaction_details_api($txn_id, $paypal_http_info_obj = "")
    {
        if ($paypal_http_info_obj) {
            $this->set_paypal_account_info($paypal_http_info_obj);
        }
        unset($this->post_array);
        $this->post_array["METHOD"] = "GetTransactionDetails";
        $this->post_array["TRANSACTIONID"] = $txn_id;

        return $this->connect(0);
    }

    private function set_paypal_account_info($paypal_http_info_obj)
    {
        include_once(BASEPATH . "libraries/Encrypt.php");
        $encrypt = new CI_Encrypt();
        $this->set_api_username($paypal_http_info_obj->get_username());
        $this->set_api_password($encrypt->decode($paypal_http_info_obj->get_password()));
        $this->set_signature($encrypt->decode($paypal_http_info_obj->get_signature()));
        $this->set_api_url($paypal_http_info_obj->get_server());
    }

    public function set_api_username($value)
    {
        $this->api_username = $value;
    }

    public function set_api_password($value)
    {
        $this->api_password = $value;
    }

    public function set_signature($value)
    {
        $this->signature = $value;
    }

    public function set_api_url($value)
    {
        $this->api_url = $value;
    }

    public function ipn_notification($debug = 0)
    {
        DEFINE("PAYPAL_IPN", $this->get_config()->value_of('paypal_ipn_path'));
        $debug ? $title = "[VBdebug]" : $title = "[VB]";

        ini_set('log_errors', true);
        ini_set('error_log', PAYPAL_IPN . 'ipn_errors.log');
        include_once(BASEPATH . 'plugins/ipnlistener.php');
        $listener = new IpnListener();
        $listener->use_sandbox = $debug;
        try {
            $listener->requirePostMethod();
            $verified = $listener->processIpn();
        } catch (Exception $e) {
            error_log($e->getMessage());
            exit(0);
        }
        if ($verified) {
            $filename = $title . $_POST['invoice'] . ' - ' . $_POST['payment_status'] . " - " . gmdate("YmdHis", time()) . ".txt";
            if ($fp = fopen(PAYPAL_IPN . "verified/" . $filename, 'w')) {
                fwrite($fp, $listener->getTextReport());
                fclose($fp);
            }
            if ($_POST['payment_status'] == 'Completed') {
                $so_srv = $this->get_so_srv();
                $sops_dao = $so_srv->get_sops_dao();

                if ($_POST['invoice']) {
                    if (($this->sops = $sops_dao->get(array("so_no" => $_POST['invoice']))) && ($this->so = $so_srv->get(array("so_no" => $_POST['invoice'])))) {
                        if ($this->so->get_status() < 2 && $this->so->get_refund_status() == 0 && $this->so->get_hold_status() == 0) {
                            $this->sops->set_pay_to_account($_POST['receiver_email']);
//                          $this->so->set_expect_delivery_date($this->get_del_srv()->get_edd($this->so->get_delivery_type_id(), $this->so->get_delivery_country_id()));
                            $this->so->set_status($this->require_credit_check() ? 2 : 3);
                            $this->so->set_txn_id($_POST['txn_id']);
                            $this->sops->set_risk_ref1($_POST["protection_eligibility"]);
                            if ($this->require_credit_check()) {
                                $sor_data = array("risk_requested" => 0);
                                $this->sor_add($sor_data);
                            }
                            $this->sops->set_remark(trim($this->sops->get_remark() . "\n" . "ipnstatus:completed"));
                            $this->sops->set_payment_status('S');
                            //set pay_date when successful record, by Thomas 20130123
                            $this->sops->set_pay_date(date("Y-m-d H:i:s"));

                            $sops_dao->update($this->sops);
                            $so_srv->get_dao()->update($this->so);

                            $subj = $title . ' IPN - ' . $_POST['payment_status'] . " - " . $_POST['invoice'] . " - " . gmdate("YmdHis", time());
                            $msg = $listener->getTextReport();
                            $to = "oswald-alert@eservicesgroup.com";
                            $this->send_error_email($subj, $msg, $to);

                            $socc_vo = $so_srv->get_socc_dao()->get();
                            $socc_vo->set_so_no($this->so->get_so_no());
                            $so_srv->get_socc_dao()->insert($socc_vo);
                            $so_srv->update_website_display_qty($this->so);
                            if ($promo_code = $this->so->get_promotion_code()) {
                                $this->update_promo($promo_code);
                            }
                        } else {
                            $subj = $title . ' IPN - ' . $_POST['payment_status'] . " - " . $_POST['invoice'] . " - " . gmdate("YmdHis", time());
                            $msg = $listener->getTextReport();
                            $to = "compliance@valuebasket.com, jesslyn@eservicesgroup.net, mike@chatandvision.com, joyce@eservicesgroup.net";
                            $this->send_error_email($subj, $msg, $to);
                        }
                    } else {
                        //no so_obj or so_payment_status_obj
                        $subj = $title . ' IPN - ' . $_POST['payment_status'] . " - " . $_POST['invoice'] . " - " . gmdate("YmdHis", time());
                        $msg = $listener->getTextReport();
                        $to = "oswald-alert@eservicesgroup.com";
                        $this->send_error_email($subj, $msg, $to);
                    }
                }
            } else {
                $subj = $title . ' IPN - ' . $_POST['payment_status'] . " - " . $_POST['invoice'] . " - " . gmdate("YmdHis", time());
                $msg = $listener->getTextReport();
                $to = "compliance@valuebasket.com, jesslyn@eservicesgroup.net, mike@chatandvision.com, joyce@eservicesgroup.net";
                $this->send_error_email($subj, $msg, $to);
            }
        } else {
            $filename = $title . $_POST['invoice'] . ' - ' . $_POST['payment_status'] . ".txt";
            if ($fp = fopen(PAYPAL_IPN . "invalid/" . $filename, 'w')) {
                fwrite($fp, $listener->getTextReport());
                fclose($fp);
            }
            //mail(array('oswald@eservicesgroup.net'), $title.' IPN - '.$_POST['payment_status']." - ".$_POST['invoice']." - ".gmdate("YmdHis", time()), $listener->getTextReport());
            $subj = $title . ' IPN - ' . $_POST['payment_status'] . " - " . $_POST['invoice'] . " - " . gmdate("YmdHis", time());
            $msg = $listener->getTextReport();
            $to = "oswald@eservicesgroup.net";
            $this->send_error_email($subj, $msg, $to);
            return FALSE;
        }
    }

    public function send_error_email($subj, $msg, $to = "")
    {
        $headers .= 'From: Admin <admin@valuebasket.com>' . "\r\n";
        $headers .= 'Cc: oswald-alert@eservicesgroup.com' . "\r\n";
        mail($to, $subj, $msg, $headers);
    }

    private function do_authorization_api()
    {
        unset($this->post_array);
        $this->post_array["METHOD"] = "DoAuthorization";
        $this->post_array["TRANSACTIONID"] = $this->so->get_txn_id();
        $this->post_array["AMT"] = $this->so->get_amount();
        $this->post_array["CURRENCYCODE"] = $this->so->get_currency_id();

        return $this->connect();
    }
}


