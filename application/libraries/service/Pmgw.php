<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Pmgw extends Base_service
{
    public $so;
    public $so_bank_transfer_obj;
    public $client;
    public $debug = 0;
    public $sops;
    public $doing_pending = 0;
    public $note;
    public $credit_check_amount = array();
    public $require_dm_amount = array();
    private $so_srv;
    private $sopql_srv;
    private $pbv_srv;
    private $http;
    private $hi_dao;
    private $region_srv;
    private $client_srv;
    private $config;
    private $lstrans_dao;
    private $promo_cd_srv;
    private $af_srv;
    private $prod_srv;
    private $tpl_dao;
    private $ca_srv;
    private $socc;
//each payment gateway can override this variable or set a new value to it.
    private $soext;
    private $sor;

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/service/So_service.php");
        $this->set_so_srv(new So_service());
        include_once(APPPATH . "libraries/service/Platform_biz_var_service.php");
        $this->set_pbv_srv(new Platform_biz_var_service());
        include_once(APPPATH . "libraries/service/Http_connector.php");
        $this->set_http(new Http_connector());
        include_once(APPPATH . "libraries/dao/Http_info_dao.php");
        $this->set_hi_dao(new Http_info_dao());
        include_once(APPPATH . "libraries/service/Region_service.php");
        $this->set_region_srv(new Region_service());
        include_once(APPPATH . "libraries/service/Event_service.php");
        $this->set_event_srv(new Event_service());
        include_once(APPPATH . "libraries/service/Client_service.php");
        $this->set_client_srv(new Client_service());
        include_once(APPPATH . "libraries/service/Context_config_service.php");
        $this->set_config(new Context_config_service());
        include_once(APPPATH . "libraries/service/Product_service.php");
        $this->set_prod_srv(new Product_service());
        include_once(APPPATH . "libraries/dao/Ls_transactions_dao.php");
        $this->set_lstrans_dao(new Ls_transactions_dao());
        include_once(APPPATH . "libraries/service/Promotion_code_service.php");
        $this->set_promo_cd_srv(new Promotion_code_service());
        include_once(APPPATH . "libraries/service/So_payment_log_service.php");
        $this->set_sopl_srv(new So_payment_log_service());
        include_once(APPPATH . "libraries/service/So_payment_query_log_service.php");
        $this->set_sopql_srv(new So_payment_query_log_service());
        include_once(APPPATH . "libraries/dao/So_bank_transfer_dao.php");
        $this->set_so_bank_transfer_dao(new So_bank_transfer_dao());
        include_once(APPPATH . "libraries/service/Delivery_service.php");
        $this->set_del_srv(new Delivery_service());
        include_once(APPPATH . "libraries/service/Affiliate_service.php");
        $this->set_af_srv(new Affiliate_service());
        include_once(APPPATH . "libraries/service/Complementary_acc_service.php");
        $this->set_ca_srv(new Complementary_acc_service());
        include_once(APPPATH . "libraries/service/Log_service.php");
        $this->logger = new Log_service();
        include_once(APPPATH . "libraries/service/Licence_service.php");
        $this->set_licence_srv(new Licence_service());
        include_once(APPPATH . "libraries/dao/Template_dao.php");
        $this->tpl_dao = new Template_dao();

        $this->loglevel = $this->logger->get_loglevel();
        $this->logheader = $this->logger->get_log_header();
        $this->logheader["type"] = 1;
        $this->logheader["file"] = __FILE__;

        $this->require_dm_amount = array("AU" => 250,
            "BG" => 800,
            "DK" => 3500,
            "FI" => 500,
            "FR" => 200,
            "HK" => 4000,
            "ID" => 3550000,
            "IE" => 300,
            "MY" => 1150,
            "MT" => 250,
            "IT" => 300,
            "NZ" => 400,
            "NO" => 3000,
            "SG" => 600,
            "ES" => 250,
            "TH" => 11500,
            "GB" => 450,
            "US" => 300,
            "CH" => 310,
            "PT" => 150,
            "RU" => 2300,
            "NL" => 150,
            "SE" => 1400,
            "MX" => 750,
            "BE" => 500,
            "PL" => 350);

        $this->credit_check_amount = array("AU" => array(150, 300),
            "BG" => array(450, 800),
            "DK" => array(1850, 3500),
            "FI" => array(300, 500),
            "FR" => array(100, 200),
            "HK" => array(1500, 4000),
            "ID" => array(1100000, 3550000),
            "IE" => array(150, 300),
            "MY" => array(380, 1150),
            "MT" => array(125, 250),
            "IT" => array(140, 300),
            "NZ" => array(300, 500),
            "NO" => array(1850, 3000),
            "SG" => array(400, 600),
            "ES" => array(100, 250),
            "TH" => array(4000, 11500),
            "GB" => array(200, 450),
            "US" => array(150, 300),
            "CH" => array(240, 310),
            "PT" => array(80, 150),
            "RU" => array(2300, 2300),
            "NL" => array(150, 350),
            "SE" => array(1400, 3000),
            "MX" => array(750, 750),
            "BE" => array(250, 500),
            "PL" => array(350, 350));
    }

    public function set_event_srv($value)
    {
        $this->event_srv = $value;
    }

    public function set_sopl_srv($value)
    {
        $this->sopl_srv = $value;
    }

    public function set_so_bank_transfer_dao(Base_dao $dao)
    {
        $this->so_bank_transfer_dao = $dao;
    }

    public function set_del_srv($value)
    {
        $this->del_srv = $value;
    }

    public function set_licence_srv($value)
    {
        $this->licence_srv = $value;
    }

    public function require_decision_manager($is_fraud = FALSE)
    {
        if ($is_fraud)
            return false;
        if ($this instanceof Payment_gateway_redirect_service) {
            if ($this->get_payment_gateway_name() == "trustly")
                return false;
        }
        $amount = $this->so->get_amount();
        $country_id = $this->so->get_bill_country_id();
        if (array_key_exists($country_id, $this->require_dm_amount)) {
            if ($amount >= $this->require_dm_amount[$country_id])
                return TRUE;
            else
                return FALSE;
        }
        return TRUE;
    }

    public function require_credit_check($is_fraud = false)
    {
        if ($is_fraud)
            return false;

        if ($this instanceof Payment_gateway_redirect_service) {
            if ($this->get_payment_gateway_name() == "trustly")
                return false;
        }

        $amount = $this->so->get_amount();
        $country_id = $this->so->get_bill_country_id();
        if (array_key_exists($country_id, $this->credit_check_amount)) {
//          if (($amount >= $this->credit_check_amount[$country_id][0])
//              && ($amount < $this->credit_check_amount[$country_id][1]))
            if ($amount >= $this->credit_check_amount[$country_id][0])
                return TRUE;
            else
                return FALSE;
        }
        return TRUE;
    }

    public function fire_collect_payment_event($collect_type = "", $get_email_html = FALSE)
    {
        // causing some TCPDF error in PDF attachment
        error_reporting(0);

        /* this function sends emails regarding payment instructions/payment reminders, esp for bank transfers */
        include_once(APPPATH . "libraries/service/Context_config_service.php");
        $config = new Context_config_service();
        $data_path = $this->get_config()->value_of("data_path");

        $replace["so_no"] = $this->so->get_so_no();
        $replace["client_id"] = $this->so->get_client_id();
        $replace["image_url"] = $this->get_config()->value_of("default_url");

        switch ($collect_type) {
            case "acknowledge_order":
                # immediately aft customer checks out with website bank transfer, send payment instructions for website bank transfer
                $replace["email_attachment_name"] = "proforma_invoice_{$replace['so_no']}";  #name of attachment on customer's email
                $replace["save_invoice_path"] = $data_path . "invoice/website_bank_transfer/proforma_invoice/";

                // frontend; must use original URL
                // $replace["image_url"] = "";
                if ($_SERVER["HTTP_HOST"]) {
                    $replace["image_url"] = "http://" . $_SERVER["HTTP_HOST"];
                }
//temp solution
//$replace["image_url"] = "/var/www/html/valuebasket.com/public_html";
                break;

            case "reminder_partial_payment":
                # send email reminder
                // $replace["email_attachment_name"] = "proforma_invoice_{$replace['so_no']}";  #name of attachment on customer's email
                // $replace["save_invoice_path"] = $data_path."invoice/website_bank_transfer/proforma_invoice/";
                break;

            case "reminder_no_payment":
                # send email reminder
                // $replace["email_attachment_name"] = "proforma_invoice_{$replace['so_no']}";  #name of attachment on customer's email
                // $replace["save_invoice_path"] = $data_path."invoice/website_bank_transfer/proforma_invoice/";
                break;

            default:
                $replace["email_attachment_name"] = "attachment";
                $replace["save_invoice_path"] = $data_path . "invoice/website_bank_transfer/proforma_invoice/";
                break;
        }

        $so_srv = $this->get_so_srv();
        $country = $this->get_region_srv()->country_dao->get(array("id" => $this->so->get_delivery_country_id()));
        // $courier = $so_srv->get_pbv_srv()->get_dt_dao()->get(array("id"=>$this->so->get_delivery_type_id()));
        $client = $so_srv->get_client_dao()->get(array("id" => $this->so->get_client_id()));
        $platform_id = $this->so->get_platform_id();
        $pbv = $this->get_pbv_srv()->get(array("selling_platform_id" => $platform_id));
        $billing_country = $this->get_region_srv()->country_dao->get(array("id" => $this->so->get_bill_country_id()));
        $replace["forename"] = $client->get_forename();
        $replace["surname"] = $client->get_surname();
        $replace["delivery_name"] = $this->so->get_delivery_name();
        $replace["delivery_address_text"] = ($this->so->get_delivery_company() ? $this->so->get_delivery_company() . "\n" : "") . trim(str_replace("|", "\n", $this->so->get_delivery_address())) . "\n" . $this->so->get_delivery_city() . " " . $this->so->get_delivery_state() . " " . $this->so->get_delivery_postcode() . "\n" . $country->get_name();
        $replace["delivery_address"] = nl2br($replace["delivery_address_text"]);
        $replace["billing_name"] = $this->so->get_bill_name();
        $replace["billing_address_text"] = ($this->so->get_bill_company() ? $this->so->get_bill_company() . "\n" : "") . trim(str_replace("|", "\n", $this->so->get_bill_address())) . "\n" . $this->so->get_bill_city() . " " . $this->so->get_bill_state() . " " . $this->so->get_bill_postcode() . "\n" . $billing_country->get_name();
        $replace["billing_address"] = nl2br($replace["billing_address_text"]);
        $replace["promotion_code"] = $this->so->get_promotion_code();
        $replace["currency_id"] = $this->so->get_currency_id();
        $replace['currency_sign'] = (empty($currency_obj) ? $this->so->get_currency_id() : $currency_obj->get_sign());
        $currency_sign = (empty($currency_obj) ? $this->so->get_currency_id() : $currency_obj->get_sign());
        $replace["order_create_date"] = date("d/m/Y", strtotime($this->so->get_order_create_date()));
        $replace["amount"] = platform_curr_format($platform_id, $this->so->get_amount(), 0);
        $replace["tel"] = $client->get_tel_1() . " " . $client->get_tel_2() . " " . $client->get_tel_3();


        $replace["logo_file_name"] = $this->get_config()->value_of("logo_file_name");
        //$replace["site_name"] = $_SESSION["domain_platform"]["site_name"]?$_SESSION["domain_platform"]["site_name"]:$this->get_config()->value_of("site_name");
        $replace["timestamp"] = date("Y-m-d-H:i:s");

        if ($payment_gateway = $so_srv->get_so_payment_gateway($this->so->get_so_no())) {
            $replace["payment_gateway"] = $payment_gateway;
        } else {
            $replace["payment_gateway"] = "N/A";
        }

        $sub_total = $total_vat = $total = 0;
        $i = 1;
        include_once(APPPATH . "helpers/image_helper.php");
        $display_id = 12;
        $lang_id = $pbv->get_language_id();
        $country_id = strtolower($pbv->get_platform_country_id());

        # sbf #3746 don't include complementary accessory on front end
        $ca_catid_arr = implode(',', $this->get_ca_srv()->get_accessory_catid_arr());
        $so_items = $so_srv->get_soi_dao()->get_items_w_name(array("so_no" => $this->so->get_so_no(), "p.cat_id NOT IN ($ca_catid_arr)" => NULL), array("lang_id" => $lang_id));
        include_once(APPPATH . "language/WEB" . str_pad($display_id, 6, '0', STR_PAD_LEFT) . "_" . $lang_id . ".php");

        if ($soid_list = $so_srv->get_soid_dao()->get_list(array("so_no" => $this->so->get_so_no()))) {
            # if need to check for virtual_only in future, uncomment next line and see [comment_virtual_only]
            // $virtual_only = 1; // Any item found as not a virutal product, means not virtual only.

            $have_priced_software = 0; // This flag has to use together with $virtual_only.

            foreach ($soid_list as $soid_obj) {
                $line_no = $soid_obj->get_line_no();

                # [comment_virtual_only] if need to check for VIRTUAL product_type in future, it is done here.
                # refer to set_success_tpl()

                $virtual_only = 0;
                $item_text[$line_no]['text'] .= "";
            }
        }

        $is_preorder = false;
        foreach ($so_items as $item) {
            $website_status = $item->get_website_status();
            if (($website_status == "P") || ($website_status == "A")) {
                $is_preorder = true;
            }
            $cur_qty = $item->get_qty();
            $cur_vat_total = $item->get_vat_total();
            $cur_amount = $item->get_amount();
            $price = $item->get_unit_price() - $item->get_vat_total() / $cur_qty;
            $cur_sub_total = $price * $cur_qty;
            $sub_total += $cur_sub_total;
            $total_vat += $cur_vat_total;
            $total += $cur_amount;
            $item_list[$item->get_prod_sku()] = $cur_qty;

            // $replace["so_items"] .=
            // "<tr>
            //  <td valign=top>".$item->get_name()."<br>".$item_text[$item->get_line_no()]['text']."</td>
            //  <td valign=top>".$cur_qty."</td>
            //  <td valign=top>".platform_curr_format($platform_id, $item->get_unit_price(), 0)."</td>
            //  <td valign=top><b>".platform_curr_format($platform_id, $cur_amount, 0)."</b></td>
            // </tr>";

            $replace["so_items"] .=
                "<tr>
                    <td style='padding:4px 20px; color:#444; font-family:Arial; font-size: 12px;'>" . $item->get_name() . "<br>" . $item_text[$item->get_line_no()]['text'] . "</td>
                    <td align='left' valign='top' style='padding:4px 10px; color:#444; font-family:Arial; font-size: 12px;'>$cur_qty</td>
                    <td align='left' valign='top' style='padding:4px 10px; color:#444; font-family:Arial; font-size: 12px;'>" . $currency_sign . " " . platform_curr_format($platform_id, $item->get_unit_price(), 0) . "</td>
                    <td align='left' valign='top' style='padding:4px 10px; color:#444; font-family:Arial; font-size: 12px;'>" . $currency_sign . " " . platform_curr_format($platform_id, $cur_amount, 0) . "</td>
                </tr>\n";

            $replace["so_items_pre_order"] .= $cur_qty . " x " . $item->get_name() . "<br>";
            $replace["so_items_text"] .= $item->get_name() . " @" . platform_curr_format($platform_id, $item->get_unit_price(), 0) . " x {$cur_qty} = " . platform_curr_format($platform_id, $cur_amount, 0) . "\n\n";
            $i++;
        }

        $this->get_del_srv()->item_list = $item_list;

        #SBF #2789 user input fixed delivery days
        // $replace["delivery_days"] = $this->get_del_srv()->get_working_days($this->so->get_delivery_type_id(), $this->so->get_delivery_country_id());
        $replace["delivery_days"] = "";

        $replace["subtotal"] = platform_curr_format($platform_id, $sub_total, 0);
        $replace["total_vat"] = platform_curr_format($platform_id, $total_vat, 0);
        $replace["total"] = platform_curr_format($platform_id, $total, 0);
        $dc = $this->so->get_delivery_charge();
        $dc_vat = $dc * ($this->so->get_vat_percent() / (100 + $this->so->get_vat_percent()));
        $dc_sub_total = $dc - $dc_vat;

        if ($so_bank_transfer_obj = $this->so_bank_transfer_obj) {
            $sbt_received_amt = $sbt_bank_charge = $sbt_received_date = array();
            if ($bank_acc_id = $this->so_bank_transfer_obj->get_bank_account_id()) {
                $replace["bank_transfer_details"] = $net_received = $total_net_received = "";
                $sbt_received_amt = explode('||', $so_bank_transfer_obj->get_received_amt_localcurr());
                $sbt_bank_charge = explode('||', $so_bank_transfer_obj->get_bank_charge());
                $sbt_received_date = explode('||', $so_bank_transfer_obj->get_received_date_localtime());

                foreach ($sbt_received_amt as $key => $received_amt) {
                    $payment_date = date('d/m/Y', strtotime($sbt_received_date[$key]));
                    $net_received = number_format(($received_amt - $sbt_bank_charge[$key]), 2, '.', '');
                    $total_net_received += $net_received;

                    $replace["bank_transfer_details"] .=
                        "<tr>
                        <td valign=top>" . $payment_date . "</td>
                        <td valign=top>" . platform_curr_format($platform_id, $received_amt, 0) . "</td>
                        <td valign=top>" . platform_curr_format($platform_id, $sbt_bank_charge[$key], 0) . "</td>
                        <td valign=top>" . platform_curr_format($platform_id, $net_received, 0) . "</td>
                    </tr>";

                    $replace["bank_transfer_details_text"] .= $payment_date . " @" . platform_curr_format($platform_id, $received_amt, 0) . " - " . platform_curr_format($platform_id, $sbt_bank_charge[$key], 0) . " = " . platform_curr_format($platform_id, $net_received, 0) . "\n\n";

                }
                $total_net_received = number_format($total_net_received, 2, '.', '');
                $total_payment_outstanding = $this->so->get_amount() - $total_net_received;

                $replace["total_net_received"] = platform_curr_format($platform_id, $total_net_received, 0);
                $replace["total_payment_outstanding"] = platform_curr_format($platform_id, ($this->so->get_amount() - $total_net_received), 0);


            }
        }

        // $replace["courier"] = @call_user_func($courier, "get_name");
        $replace["dc_sub_total"] = platform_curr_format($platform_id, $dc_sub_total, 0);
        $replace["dc_vat"] = platform_curr_format($platform_id, $dc_vat, 0);
        $replace["delivery_charge"] = platform_curr_format($platform_id, $dc, 0);
        $replace["total_sub_total"] = platform_curr_format($platform_id, $sub_total + $dc_sub_total, 0);
        $replace["total_total_vat"] = platform_curr_format($platform_id, $total_vat + $dc_vat, 0);
        $replace["email"] = $client->get_email();
        include_once(BASEPATH . "libraries/Encrypt.php");
        $encrypt = new CI_Encrypt();
        $replace["password"] = $encrypt->decode($client->get_password());
        $lang_id = $pbv->get_language_id();
        $so_srv->include_dto("Event_email_dto");
        $dto = new Event_email_dto();

        include_once(APPPATH . "hooks/country_selection.php");
        $replace = array_merge($replace, Country_selection::get_template_require_text($lang_id, $country_id));
        $from_email = "no-reply@" . strtolower($replace["site_name"]);
        if ($platform_id == "WEBPL")
            $from_email = "transferpl@valuebasket.pl";

        $dto->set_mail_from($from_email);
        $replace["from_email"] = $from_email;
        $replace["client_id_no"] = $client->get_client_id_no() ? "(ID: {$client->get_client_id_no()})" : "";
        $dto->set_mail_to($client->get_email());
        $dto->set_lang_id($lang_id);
        $dto->set_platform_id($platform_id);


        if ($is_preorder) {
            $replace["delivery_address"] = ($this->so->get_delivery_company() ? $this->so->get_delivery_company() . " - " : "");
            $replace["delivery_address"] .= trim(str_replace("|", " ", $this->so->get_delivery_address()));
            $replace["delivery_address"] .= ", " . $this->so->get_delivery_city();
            $replace["delivery_address"] .= ($this->so->get_delivery_state() ? ", " . $this->so->get_delivery_state() : "");
            $replace["delivery_address"] .= ", " . $this->so->get_delivery_postcode() . ", " . $country->get_name();
            $replace["expect_delivery_date"] = $this->so->get_expect_delivery_date();
            $dto = $this->set_preorder_tpl($dto);
        } else {
            $dto = $this->set_collect_payment_tpl($dto, $collect_type, $virtual_only, $have_priced_software);
        }
        if ($so_ext_obj = $this->get_so_srv()->get_soext_dao()->get(array("so_no" => $this->so->get_so_no()))) {
            $replace["voucher_code"] = $so_ext_obj->get_voucher_code();

            //#2182 add the processing fee
            $processing_fee = $so_ext_obj->get_offline_fee();
        }

        if (is_null($processing_fee)) {
            $processing_fee = 0;
        }

        $replace['processing_fee'] = platform_curr_format($platform_id, $processing_fee, 0);
        $replace['licence_key'] = "";

        # get all language files
        $dto->set_replace($replace);
        if ($get_email_html === FALSE) {
            $this->get_event_srv()->fire_event($dto, FALSE);

            # remove pdf invoice aft sent email
            $pdf_file = $replace["save_invoice_path"] . $replace["email_attachment_name"] . ".pdf";
            if (is_file($pdf_file)) {
                unlink($pdf_file);
            }
        } else {
            #debug from email_test.php
            $email_msg = $this->get_event_srv()->fire_event($dto, TRUE);
            return $email_msg;
        }
    }

    public function get_config()
    {
        return $this->config;
    }

    public function set_config($value)
    {
        $this->config = $value;
    }

    public function get_so_srv()
    {
        return $this->so_srv;
    }

    public function set_so_srv($value)
    {
        $this->so_srv = $value;
    }

    public function get_region_srv()
    {
        return $this->region_srv;
    }

    public function set_region_srv($value)
    {
        $this->region_srv = $value;
    }

    public function get_pbv_srv()
    {
        return $this->pbv_srv;
    }

    public function set_pbv_srv($value)
    {
        $this->pbv_srv = $value;
    }

    public function get_ca_srv()
    {
        return $this->ca_srv;
    }

    public function set_ca_srv($value)
    {
        $this->ca_srv = $value;
    }

    public function get_del_srv()
    {
        return $this->del_srv;
    }

    function set_preorder_tpl($dto)
    {
        $dto->set_event_id("preorder_confirmation");
        $dto->set_tpl_id("preorder_confirmation");
        return $dto;
    }

    function set_collect_payment_tpl($dto, $collect_type = "", $virtual_only = 0, $have_priced_software = 0)
    {
        if ($virtual_only) {
            // $tpl_path = APPPATH . "data/template/";
            # as of sbf#3315 we have no case for virtual pdt for w_bank_transfer template
            # refer to set_success_tpl() if needed
        } else {
            switch ($collect_type) {
                case "acknowledge_order":
                    # acknowledge order, send instructions for full payment
                    # remember to add respective lang_id in db template table! current only en & es
                    $dto->set_event_id("wbanktransfer_acknowledge_order");
                    $dto->set_tpl_id("wbanktransfer_acknowledge_order");
                    break;

                case "reminder_no_payment":
                    # send reminder for orders with 0 payment
                    $dto->set_event_id("wbanktransfer_reminder");
                    $dto->set_tpl_id("wbanktransfer_reminder");
                    break;

                case "reminder_partial_payment":
                    # send remind for PARTIAL payments
                    $dto->set_event_id("wbanktransfer_reminder_partial");
                    $dto->set_tpl_id("wbanktransfer_reminder_partial");
                    break;

                default:
                    break;
            }
        }
        return $dto;
    }

    public function get_event_srv()
    {
        return $this->event_srv;
    }

    public function fire_cancel_order_event($cancel_type, $get_email_html = FALSE)
    {
        # $cancel_type = "unpaid" -> order is fully unpaid

        /* this function sends emails regarding payment instructions/payment reminders, esp for bank transfers */
        include_once(APPPATH . "libraries/service/Context_config_service.php");
        $config = new Context_config_service();
        $data_path = $this->get_config()->value_of("data_path");

        $replace["so_no"] = $this->so->get_so_no();
        $replace["client_id"] = $this->so->get_client_id();

        $so_srv = $this->get_so_srv();
        $country = $this->get_region_srv()->country_dao->get(array("id" => $this->so->get_delivery_country_id()));
        // $courier = $so_srv->get_pbv_srv()->get_dt_dao()->get(array("id"=>$this->so->get_delivery_type_id()));
        $client = $so_srv->get_client_dao()->get(array("id" => $this->so->get_client_id()));
        $platform_id = $this->so->get_platform_id();
        $pbv = $this->get_pbv_srv()->get(array("selling_platform_id" => $platform_id));
        $billing_country = $this->get_region_srv()->country_dao->get(array("id" => $this->so->get_bill_country_id()));
        $replace["forename"] = $client->get_forename();
        $replace["surname"] = $client->get_surname();
        $replace["delivery_name"] = $this->so->get_delivery_name();
        $replace["delivery_address_text"] = ($this->so->get_delivery_company() ? $this->so->get_delivery_company() . "\n" : "") . trim(str_replace("|", "\n", $this->so->get_delivery_address())) . "\n" . $this->so->get_delivery_city() . " " . $this->so->get_delivery_state() . " " . $this->so->get_delivery_postcode() . "\n" . $country->get_name();
        $replace["delivery_address"] = nl2br($replace["delivery_address_text"]);
        $replace["billing_name"] = $this->so->get_bill_name();
        $replace["billing_address_text"] = ($this->so->get_bill_company() ? $this->so->get_bill_company() . "\n" : "") . trim(str_replace("|", "\n", $this->so->get_bill_address())) . "\n" . $this->so->get_bill_city() . " " . $this->so->get_bill_state() . " " . $this->so->get_bill_postcode() . "\n" . $billing_country->get_name();
        $replace["billing_address"] = nl2br($replace["billing_address_text"]);
        $replace["promotion_code"] = $this->so->get_promotion_code();
        $replace["currency_id"] = $this->so->get_currency_id();
        $replace['currency_sign'] = (empty($currency_obj) ? $this->so->get_currency_id() : $currency_obj->get_sign());
        $currency_sign = (empty($currency_obj) ? $this->so->get_currency_id() : $currency_obj->get_sign());
        $replace["order_create_date"] = date("d/m/Y", strtotime($this->so->get_order_create_date()));
        $replace["amount"] = platform_curr_format($platform_id, $this->so->get_amount(), 0);
        $replace["tel"] = $client->get_tel_1() . " " . $client->get_tel_2() . " " . $client->get_tel_3();
        $replace["image_url"] = $this->get_config()->value_of("default_url");
        $replace["logo_file_name"] = $this->get_config()->value_of("logo_file_name");
        //$replace["site_name"] = $_SESSION["domain_platform"]["site_name"]?$_SESSION["domain_platform"]["site_name"]:$this->get_config()->value_of("site_name");
        $replace["timestamp"] = date("Y-m-d-H:i:s");

        if ($payment_gateway = $so_srv->get_so_payment_gateway($this->so->get_so_no())) {
            $replace["payment_gateway"] = $payment_gateway;
        } else {
            $replace["payment_gateway"] = "N/A";
        }

        $sub_total = $total_vat = $total = 0;
        $i = 1;
        include_once(APPPATH . "helpers/image_helper.php");
        $display_id = 12;
        $lang_id = $pbv->get_language_id();
        $country_id = strtolower($pbv->get_platform_country_id());

        # sbf #3746 don't include complementary accessory on front end
        $ca_catid_arr = implode(',', $this->get_ca_srv()->get_accessory_catid_arr());
        $so_items = $so_srv->get_soi_dao()->get_items_w_name(array("so_no" => $this->so->get_so_no(), "p.cat_id NOT IN ($ca_catid_arr)" => NULL), array("lang_id" => $lang_id));
        include_once(APPPATH . "language/WEB" . str_pad($display_id, 6, '0', STR_PAD_LEFT) . "_" . $lang_id . ".php");

        if ($soid_list = $so_srv->get_soid_dao()->get_list(array("so_no" => $this->so->get_so_no()))) {
            # if need to check for virtual_only in future, uncomment next line and see [comment_virtual_only]
            // $virtual_only = 1; // Any item found as not a virutal product, means not virtual only.

            $have_priced_software = 0; // This flag has to use together with $virtual_only.

            foreach ($soid_list as $soid_obj) {
                $line_no = $soid_obj->get_line_no();

                # [comment_virtual_only] if need to check for VIRTUAL product_type in future, it is done here.
                # refer to set_success_tpl()

                $virtual_only = 0;
                $item_text[$line_no]['text'] .= "";
            }
        }

        $is_preorder = false;
        foreach ($so_items as $item) {
            $website_status = $item->get_website_status();
            if (($website_status == "P") || ($website_status == "A")) {
                $is_preorder = true;
            }
            $cur_qty = $item->get_qty();
            $cur_vat_total = $item->get_vat_total();
            $cur_amount = $item->get_amount();
            $price = $item->get_unit_price() - $item->get_vat_total() / $cur_qty;
            $cur_sub_total = $price * $cur_qty;
            $sub_total += $cur_sub_total;
            $total_vat += $cur_vat_total;
            $total += $cur_amount;
            $item_list[$item->get_prod_sku()] = $cur_qty;

            $replace["so_items"] .=
                "<tr>
                    <td style='padding:4px 20px; color:#444; font-family:Arial; font-size: 12px;'>" . $item->get_name() . "<br>" . $item_text[$item->get_line_no()]['text'] . "</td>
                    <td align='left' valign='top' style='padding:4px 10px; color:#444; font-family:Arial; font-size: 12px;'>$cur_qty</td>
                    <td align='left' valign='top' style='padding:4px 10px; color:#444; font-family:Arial; font-size: 12px;'>" . $currency_sign . " " . platform_curr_format($platform_id, $item->get_unit_price(), 0) . "</td>
                    <td align='left' valign='top' style='padding:4px 10px; color:#444; font-family:Arial; font-size: 12px;'>" . $currency_sign . " " . platform_curr_format($platform_id, $cur_amount, 0) . "</td>
                </tr>\n";

            $replace["so_items_pre_order"] .= $cur_qty . " x " . $item->get_name() . "<br>";
            $replace["so_items_text"] .= $item->get_name() . " @" . platform_curr_format($platform_id, $item->get_unit_price(), 0) . " x {$cur_qty} = " . platform_curr_format($platform_id, $cur_amount, 0) . "\n\n";
            $i++;
        }

        $this->get_del_srv()->item_list = $item_list;

        #SBF #2789 user input fixed delivery days
        // $replace["delivery_days"] = $this->get_del_srv()->get_working_days($this->so->get_delivery_type_id(), $this->so->get_delivery_country_id());
        $replace["delivery_days"] = "";

        $replace["subtotal"] = platform_curr_format($platform_id, $sub_total, 0);
        $replace["total_vat"] = platform_curr_format($platform_id, $total_vat, 0);
        $replace["total"] = platform_curr_format($platform_id, $total, 0);
        $dc = $this->so->get_delivery_charge();
        $dc_vat = $dc * ($this->so->get_vat_percent() / (100 + $this->so->get_vat_percent()));
        $dc_sub_total = $dc - $dc_vat;

        // $replace["courier"] = @call_user_func($courier, "get_name");
        $replace["dc_sub_total"] = platform_curr_format($platform_id, $dc_sub_total, 0);
        $replace["dc_vat"] = platform_curr_format($platform_id, $dc_vat, 0);
        $replace["delivery_charge"] = platform_curr_format($platform_id, $dc, 0);
        $replace["total_sub_total"] = platform_curr_format($platform_id, $sub_total + $dc_sub_total, 0);
        $replace["total_total_vat"] = platform_curr_format($platform_id, $total_vat + $dc_vat, 0);
        $replace["email"] = $client->get_email();
        include_once(BASEPATH . "libraries/Encrypt.php");
        $encrypt = new CI_Encrypt();
        $replace["password"] = $encrypt->decode($client->get_password());
        $lang_id = $pbv->get_language_id();
        $so_srv->include_dto("Event_email_dto");
        $dto = new Event_email_dto();

        include_once(APPPATH . "hooks/country_selection.php");
        $replace = array_merge($replace, Country_selection::get_template_require_text($lang_id, $country_id));
        $from_email = "no-reply@" . strtolower($replace["site_name"]);

        $dto->set_mail_from($from_email);
        $replace["from_email"] = $from_email;
        $replace["client_id_no"] = $client->get_client_id_no() ? "(ID: {$client->get_client_id_no()})" : "";
        $dto->set_mail_to($client->get_email());
        $dto->set_lang_id($lang_id);
        $dto->set_platform_id($platform_id);


        if ($is_preorder) {
            $replace["delivery_address"] = ($this->so->get_delivery_company() ? $this->so->get_delivery_company() . " - " : "");
            $replace["delivery_address"] .= trim(str_replace("|", " ", $this->so->get_delivery_address()));
            $replace["delivery_address"] .= ", " . $this->so->get_delivery_city();
            $replace["delivery_address"] .= ($this->so->get_delivery_state() ? ", " . $this->so->get_delivery_state() : "");
            $replace["delivery_address"] .= ", " . $this->so->get_delivery_postcode() . ", " . $country->get_name();
            $replace["expect_delivery_date"] = $this->so->get_expect_delivery_date();
            $dto = $this->set_preorder_tpl($dto);
        } else {
            $dto = $this->set_cancel_order_tpl($dto, $cancel_type, $virtual_only, $have_priced_software);
        }
        if ($so_ext_obj = $this->get_so_srv()->get_soext_dao()->get(array("so_no" => $this->so->get_so_no()))) {
            $replace["voucher_code"] = $so_ext_obj->get_voucher_code();

            //#2182 add the processing fee
            $processing_fee = $so_ext_obj->get_offline_fee();
        }

        if (is_null($processing_fee)) {
            $processing_fee = 0;
        }

        $replace['processing_fee'] = platform_curr_format($platform_id, $processing_fee, 0);
        $replace['licence_key'] = "";

        # get all language files
        $dto->set_replace($replace);

        if ($get_email_html === FALSE) {
            $this->get_event_srv()->fire_event($dto, FALSE);

            # remove pdf invoice aft sent email
            $pdf_file = $replace["save_invoice_path"] . $replace["email_attachment_name"] . ".pdf";
            if (is_file($pdf_file)) {
                unlink($pdf_file);
            }
        } else {
            #debug for email_test.php
            $email_msg = $this->get_event_srv()->fire_event($dto, TRUE);
            return $email_msg;
        }
    }

    function set_cancel_order_tpl($dto, $collect_type = "", $virtual_only = 0, $have_priced_software = 0)
    {
        if ($virtual_only) {
            // $tpl_path = APPPATH . "data/template/";
            # as of sbf#3315 we have no case for virtual pdt for w_bank_transfer template
            # refer to set_success_tpl() if needed
        } else {
            switch ($collect_type) {
                case "unpaid":
                    # email to inform order has been cancel because 0 payment
                    $dto->set_event_id("wbanktransfer_cancel_unpaid");
                    $dto->set_tpl_id("wbanktransfer_cancel_unpaid");
                    break;


                default:
                    break;
            }
        }
        return $dto;
    }

    public function fire_success_event($acknowledgement = 0, $get_email_html = FALSE)
    {
        $so_srv = $this->get_so_srv();
        $country = $this->get_region_srv()->country_dao->get(array("id" => $this->so->get_delivery_country_id()));
        $courier = $so_srv->get_pbv_srv()->get_dt_dao()->get(array("id" => $this->so->get_delivery_type_id()));
        $client = $so_srv->get_client_dao()->get(array("id" => $this->so->get_client_id()));
        $platform_id = $this->so->get_platform_id();
        $pbv = $this->get_pbv_srv()->get(array("selling_platform_id" => $platform_id));
        $billing_country = $this->get_region_srv()->country_dao->get(array("id" => $this->so->get_bill_country_id()));
        $replace["so_no"] = $this->so->get_so_no();
        $replace["client_id"] = $this->so->get_client_id();
        $replace["forename"] = $client->get_forename();
        $replace["delivery_name"] = $this->so->get_delivery_name();
        $replace["delivery_address_text"] = ($this->so->get_delivery_company() ? $this->so->get_delivery_company() . "\n" : "") . trim(str_replace("|", "\n", $this->so->get_delivery_address())) . "\n" . $this->so->get_delivery_city() . " " . $this->so->get_delivery_state() . " " . $this->so->get_delivery_postcode() . "\n" . $country->get_name();
        $replace["delivery_address"] = nl2br($replace["delivery_address_text"]);
        $replace["billing_name"] = $this->so->get_bill_name();
        $replace["billing_address_text"] = ($this->so->get_bill_company() ? $this->so->get_bill_company() . "\n" : "") . trim(str_replace("|", "\n", $this->so->get_bill_address())) . "\n" . $this->so->get_bill_city() . " " . $this->so->get_bill_state() . " " . $this->so->get_bill_postcode() . "\n" . $billing_country->get_name();
        $replace["billing_address"] = nl2br($replace["billing_address_text"]);
        $replace["promotion_code"] = $this->so->get_promotion_code();
        $replace["currency_id"] = $this->so->get_currency_id();
        $replace['currency_sign'] = (empty($currency_obj) ? $this->so->get_currency_id() : $currency_obj->get_sign());
        $currency_sign = (empty($currency_obj) ? $this->so->get_currency_id() : $currency_obj->get_sign());
        $replace["order_create_date"] = date("d/m/Y", strtotime($this->so->get_order_create_date()));
        $replace["amount"] = platform_curr_format($platform_id, $this->so->get_amount(), 0);
        $replace["expect_ship_days"] = $this->so->get_expect_ship_days();
        $replace["expect_del_days"] = $this->so->get_expect_del_days();

        $replace["image_url"] = $this->get_config()->value_of("default_url");
        $replace["logo_file_name"] = $this->get_config()->value_of("logo_file_name");
        //$replace["site_name"] = $_SESSION["domain_platform"]["site_name"]?$_SESSION["domain_platform"]["site_name"]:$this->get_config()->value_of("site_name");
        $replace["timestamp"] = date("Y-m-d-H:i:s");
        if ($payment_gateway = $so_srv->get_so_payment_gateway($this->so->get_so_no())) {
            $replace["payment_gateway"] = $payment_gateway;
        } else {
            $replace["payment_gateway"] = "N/A";
        }

        $sub_total = $total_vat = $total = 0;
        $i = 1;
        include_once(APPPATH . "helpers/image_helper.php");

        $display_id = 12;
        $lang_id = $pbv->get_language_id();
        $country_id = strtolower($pbv->get_platform_country_id());

        # sbf #3746 don't include complementary accessory on front end
        $ca_catid_arr = implode(',', $this->get_ca_srv()->get_accessory_catid_arr());
        $so_items = $so_srv->get_soi_dao()->get_items_w_name(array("so_no" => $this->so->get_so_no(), "p.cat_id NOT IN ($ca_catid_arr)" => null), array("lang_id" => $lang_id));

        include_once(APPPATH . "language/WEB" . str_pad($display_id, 6, '0', STR_PAD_LEFT) . "_" . $lang_id . ".php");

        if ($soid_list = $so_srv->get_soid_dao()->get_list(array("so_no" => $this->so->get_so_no()))) {
            $virtual_only = 1; // Any item found as not a virutal product, means not virtual only.
            $have_priced_software = 0; // This flag has to use together with $virtual_only.

            foreach ($soid_list as $soid_obj) {
                $line_no = $soid_obj->get_line_no();

                if ($this->get_prod_srv()->get_pt_dao()->get(array("sku" => $soid_obj->get_item_sku(), "type_id" => "VIRTUAL"))) {
                    if (!$this->get_prod_srv()->get_product_type(array("sku" => $soid_obj->get_item_sku(), "type_id" => "TRIAL"))) {
                        $have_priced_software = 1;
                    }
//print_r($this->get_prod_srv()->get_product_type(array("sku"=>$soid_obj->get_item_sku(), "type_id"=>"TRIAL")));

                    for ($i = 0; $i < $soid_obj->get_qty(); $i++) {
                        $this->assign_licence($soid_obj->get_item_sku(), $soid_obj->get_line_no());
                    }

                    $item_text[$line_no]['text'] .= "<font style='font-size:12px;font-family:Calibri,sans serif'>";

                    $soid_obj->set_outstanding_qty(0);
                    $soid_obj->set_status(1);
                    $so_srv->get_soid_dao()->update($soid_obj);

                    if ($licence_obj = $this->get_prod_srv()->get_licence_dao()->get(array("sku" => $soid_obj->get_item_sku(), "status" => 1))) {
                        $download_link = $licence_obj->get_link();

                        if ($this->get_prod_srv()->get_pt_dao()->get(array("sku" => $soid_obj->get_item_sku(), "type_id" => "TRIAL"))) {
                            $item_text[$line_no]['text'] .= $lang['trial_download_text_1'] . $download_link . $lang['trial_download_text_2'];
                        } else {
                            $item_text[$line_no]['text'] .= $lang['license_text'] . $licence_obj->get_key();
                            $item_text[$line_no]['text'] .= $lang['software_download_text_1'] . $download_link . $lang['software_download_text_2'];
                        }

                        $pcext_obj = $this->get_prod_srv()->get_pcext_dao()->get(array("prod_sku" => $soid_obj->get_item_sku(), "lang_id" => $lang_id));

                        if ($pcext_obj) {
                            if ($pcext_obj->get_instruction()) {
                                $item_text[$line_no]['text'] .= $lang['instruction_text_1'] . $country_id . $lang['instruction_text_2'] . $soid_obj->get_item_sku() . $lang['instruction_text_3'];
                            }
                        }
                    }
                } else {
                    $virtual_only = 0;
                    $item_text[$line_no]['text'] .= "";
                }
            }
        }

        $is_preorder = false;
        foreach ($so_items as $item) {
            $website_status = $item->get_website_status();
            if (($website_status == "P") || ($website_status == "A")) {
                $is_preorder = true;
            }
            $cur_qty = $item->get_qty();
            $cur_vat_total = $item->get_vat_total();
            $cur_amount = $item->get_amount();
            $price = $item->get_unit_price() - $item->get_vat_total() / $cur_qty;
            $cur_sub_total = $price * $cur_qty;
            $sub_total += $cur_sub_total;
            $total_vat += $cur_vat_total;
            $total += $cur_amount;
            $item_list[$item->get_prod_sku()] = $cur_qty;

            /* not distributing serial number in order confirmation email
             * have to wait for credit check

            if($soidl_obj_list = $so_srv->get_soidl_dao()->get_list(array("so_no"=>$this->so->get_so_no(), "item_sku"=>$item->get_prod_sku(), "line_no"=>$item->get_line_no())))
            {
                $ln = 0;
                foreach ($soidl_obj_list as $soidl_obj)
                {
                    if($soidl_obj->get_licence_key())
                    {
                        if($ln == 0)
                        {
                            $text .= "<br>-  Serial Number: ";
                        }
                        $ln++;
                        $text .= "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$ln.": ".$soidl_obj->get_licence_key();
                    }
                }
            }
            */

            $replace["so_items"] .=
                "<tr>
                    <td style='padding:4px 20px; color:#444; font-family:Arial; font-size: 12px;'>" . $item->get_name() . "<br>" . $item_text[$item->get_line_no()]['text'] . "</td>
                    <td align='left' valign='top' style='padding:4px 10px; color:#444; font-family:Arial; font-size: 12px;'>$cur_qty</td>
                    <td align='left' valign='top' style='padding:4px 10px; color:#444; font-family:Arial; font-size: 12px;'>" . $currency_sign . " " . platform_curr_format($platform_id, $item->get_unit_price(), 0) . "</td>
                    <td align='left' valign='top' style='padding:4px 10px; color:#444; font-family:Arial; font-size: 12px;'>" . $currency_sign . " " . platform_curr_format($platform_id, $cur_amount, 0) . "</td>
                </tr>\n";

            $replace["so_items_pre_order"] .= $cur_qty . " x " . $item->get_name() . "<br>";
            $replace["so_items_text"] .= $item->get_name() . " @" . platform_curr_format($platform_id, $item->get_unit_price(), 0) . " x {$cur_qty} = " . platform_curr_format($platform_id, $cur_amount, 0) . "\n\n";
            $i++;
        }

        $this->get_del_srv()->item_list = $item_list;

        #SBF #2789 user input fixed delivery days
        // $replace["delivery_days"] = $this->get_del_srv()->get_working_days($this->so->get_delivery_type_id(), $this->so->get_delivery_country_id());
        $replace["delivery_days"] = "";

        $replace["subtotal"] = platform_curr_format($platform_id, $sub_total, 0);
        $replace["total_vat"] = platform_curr_format($platform_id, $total_vat, 0);
        $replace["total"] = platform_curr_format($platform_id, $total, 0);
        $dc = $this->so->get_delivery_charge();
        $dc_vat = $dc * ($this->so->get_vat_percent() / (100 + $this->so->get_vat_percent()));
        $dc_sub_total = $dc - $dc_vat;

        $replace["courier"] = @call_user_func($courier, "get_name");
        $replace["dc_sub_total"] = platform_curr_format($platform_id, $dc_sub_total, 0);
        $replace["dc_vat"] = platform_curr_format($platform_id, $dc_vat, 0);
        $replace["delivery_charge"] = platform_curr_format($platform_id, $dc, 0);
        $replace["total_sub_total"] = platform_curr_format($platform_id, $sub_total + $dc_sub_total, 0);
        $replace["total_total_vat"] = platform_curr_format($platform_id, $total_vat + $dc_vat, 0);
        $replace["email"] = $client->get_email();
        include_once(BASEPATH . "libraries/Encrypt.php");
        $encrypt = new CI_Encrypt();
        $replace["password"] = $encrypt->decode($client->get_password());
        $lang_id = $pbv->get_language_id();
        $so_srv->include_dto("Event_email_dto");
        $dto = new Event_email_dto();

        include_once(APPPATH . "hooks/country_selection.php");
        $replace = array_merge($replace, Country_selection::get_template_require_text($lang_id, $country_id));
        $from_email = "no-reply@" . strtolower($replace["site_name"]);

//      $from_email = $this->get_sender($lang_id, $replace["site_name"]);
        $dto->set_mail_from($from_email);
        $replace["from_email"] = $from_email;
        $dto->set_mail_to($client->get_email());
        $dto->set_lang_id($lang_id);
        $dto->set_platform_id($platform_id);

//      $this->logheader["message"] = var_export($dto, true);
//      $this->logger->write_log($this->logheader);

        if ($is_preorder) {
            $replace["delivery_address"] = ($this->so->get_delivery_company() ? $this->so->get_delivery_company() . " - " : "");
            $replace["delivery_address"] .= trim(str_replace("|", " ", $this->so->get_delivery_address()));
            $replace["delivery_address"] .= ", " . $this->so->get_delivery_city();
            $replace["delivery_address"] .= ($this->so->get_delivery_state() ? ", " . $this->so->get_delivery_state() : "");
            $replace["delivery_address"] .= ", " . $this->so->get_delivery_postcode() . ", " . $country->get_name();
            $replace["expect_delivery_date"] = $this->so->get_expect_delivery_date();
            $dto = $this->set_preorder_tpl($dto);
        } else {
            $dto = $this->set_success_tpl($dto, $acknowledgement, $virtual_only, $have_priced_software);
        }
        if ($so_ext_obj = $this->get_so_srv()->get_soext_dao()->get(array("so_no" => $this->so->get_so_no()))) {
            $replace["voucher_code"] = $so_ext_obj->get_voucher_code();

            if (!$acknowledgement) {
                $this->soext = $so_ext_obj;
                $this->update_success_af();
            }
            //#2182 add the processing fee
            $processing_fee = $so_ext_obj->get_offline_fee();
        }

        if (is_null($processing_fee)) {
            $processing_fee = 0;
        }

        $replace['processing_fee'] = platform_curr_format($platform_id, $processing_fee, 0);


        $replace['licence_key'] = "";

        // this line merges all info array with language array
        $dto->set_replace($replace);
        if ($get_email_html === FALSE) {
            $this->get_event_srv()->fire_event($dto, FALSE);
        } else {
            # debug for email_test.php
            $email_msg = $this->get_event_srv()->fire_event($dto, TRUE);
            return $email_msg;
        }
    }

    public function get_prod_srv()
    {
        return $this->prod_srv;
    }

    public function set_prod_srv($value)
    {
        $this->prod_srv = $value;
    }

    public function assign_licence($sku, $line_no)
    {
        $so_srv = $this->get_so_srv();
        $so_srv->include_dto("Event_licence_dto");
        $dto = new Event_licence_dto();
        $ev_dto = clone($dto);
        $ev_dto->set_event_id("assign_licence");
        $ev_dto->set_so_no($this->so->get_so_no());
        $ev_dto->set_line_no($line_no);
        $ev_dto->set_sku($sku);
        if ($valid_licence = $this->get_valid_licence($sku)) {
            $ev_dto->set_licence_key($valid_licence->get_key());
        }
        $this->get_event_srv()->fire_event($ev_dto);
    }

    public function get_valid_licence($sku)
    {
        $licence_srv = $this->get_licence_srv();
        /*      $obj = $licence_srv->get_licence_w_detail(array("sku"=>$sku, "sl.distributed"=>"0", "sl.status"=>"1"), array("limit"=>"1"));
                print_r($obj);
                die();*/
        return $licence_srv->get_licence_w_detail(array("sku" => $sku, "sl.distributed" => "0", "sl.status" => "1"), array("limit" => "1"));
    }

    public function get_licence_srv()
    {
        return $this->licence_srv;
    }

    function set_success_tpl($dto, $acknowledgement, $virtual_only = 0, $have_priced_software = 0)
    {
        $tpl_path = APPPATH . "data/template/";
        if ($acknowledgement) {
            $dto->set_event_id("acknowledgement");
            $dto->set_tpl_id("acknowledgement");
            $this->soext_add(array("acked" => 1));
        } else {
            if ($virtual_only) {
                if (!is_file($tpl_path . "virtual_payment_success/virtual_payment_success_" . $dto->get_lang_id() . ".html")) {
                    // default to en if other language file is not found
                    $dto->set_lang_id('en');
                }
                $dto->set_event_id("virtual_payment_success");
                $dto->set_tpl_id("virtual_payment_success");
            } else {
                $dto->set_event_id("payment_success");
                $dto->set_tpl_id("payment_success");
            }
        }
        return $dto;
    }

    public function soext_add($vars = array())
    {
        include_once(APPPATH . "helpers/object_helper.php");
        $soext_dao = $this->get_so_srv()->get_soext_dao();
        if ($this->soext || ($this->soext = $soext_dao->get(array("so_no" => $this->so->get_so_no())))) {
            set_value($this->soext, $vars);
            $soext_dao->update($this->soext);
        } else {
            $soext_obj = $soext_dao->get();
            set_value($soext_obj, $vars);
            $soext_obj->set_so_no($this->so->get_so_no());
            $this->soext = $soext_dao->insert($soext_obj);
        }
    }

    public function update_success_af()
    {
        $soext_dao = $this->get_so_srv()->get_soext_dao();
        if ($this->soext || ($this->soext = $soext_dao->get(array("so_no" => $this->so->get_so_no())))) {
            if (!is_null($this->soext->get_conv_site_id())) {
                $this->soext->set_conv_status(1);
                $soext_dao->update($this->soext);
            }
        }
    }

    public function fire_fail_event($get_email_html = FALSE)
    {
        $so_srv = $this->get_so_srv();
        $country = $this->get_region_srv()->country_dao->get(array("id" => $this->so->get_delivery_country_id()));
        $client = $so_srv->get_client_dao()->get(array("id" => $this->so->get_client_id()));
        $platform_id = $this->so->get_platform_id();
        $pbv = $this->get_pbv_srv()->get(array("selling_platform_id" => $platform_id));
        $lang_id = $pbv->get_language_id();

        $billing_country = $this->get_region_srv()->country_dao->get(array("id" => $this->so->get_bill_country_id()));
        $replace["so_no"] = $this->so->get_so_no();
        $replace["client_id"] = $this->so->get_client_id();

        $replace["forename"] = $client->get_forename();

        $replace["default_url"] = $this->get_config()->value_of("default_url");
        $replace["logo_file_name"] = $this->get_config()->value_of("logo_file_name");

        $replace["email"] = $client->get_email();

        $so_srv->include_dto("Event_email_dto");
        $dto = new Event_email_dto();

        include_once(APPPATH . "hooks/country_selection.php");
        $country_id = strtolower($pbv->get_platform_country_id());
        $replace["site_url"] = Country_selection::rewrite_domain_by_country(SITE_URL, $country_id);;
        $replace["site_name"] = Country_selection::rewrite_site_name($replace["site_url"]);

        $from_email = $this->get_sender($lang_id, $replace["site_name"]);
        $dto->set_mail_from($from_email);
        $replace["from_email"] = $from_email;
        $dto->set_mail_to($client->get_email());
        $dto->set_lang_id($lang_id);
        $dto->set_platform_id($platform_id);

        $dto->set_event_id("payment_fail");
        $dto->set_tpl_id("payment_fail");

        $dto->set_replace($replace);

        if ($get_email_html === FALSE) {
            $this->get_event_srv()->fire_event($dto, FALSE);
        } else {
            $email_msg = $this->get_event_srv()->fire_event($dto, TRUE);
            return $email_msg;
        }

    }

    public function get_sender($lang_id, $site_name)
    {
        switch ($lang_id) {
            case "de":
            case "fr":
            case "es":
            case "pt":
            case "nl":
            case "ja":
            case "it":
            case "pl":
            case "jp":
            case "da":
            case "ko":
            case "tr":
            case "sv":
            case "no":
            case "pt-br":
            case "ru":
            default:
                $from_email = "no-reply@" . strtolower($site_name);
                break;
        }
        return $from_email;
    }

    public function add_ls_transaction(Base_vo $soext_obj)
    {
        include_once(APPPATH . "helpers/object_helper.php");
        $lstrans_vo = $this->get_lstrans_dao()->get();
        $soidlist = $this->get_so_srv()->get_soid_dao()->get_list(array("so_no" => $soext_obj->get_so_no()));
        foreach ($soidlist as $soidobj) {
            $lstrans_obj = clone $lstrans_vo;
            set_value($lstrans_obj, $soidobj);
            $lstrans_obj->set_currency_id($this->so->get_currency_id());
            $new_amount = $lstrans_obj->get_amount() * 100;
            $lstrans_obj->set_amount($new_amount);
            $this->get_lstrans_dao()->insert($lstrans_obj);
        }
    }

    public function get_lstrans_dao()
    {
        return $this->lstrans_dao;
    }

    public function set_lstrans_dao(Base_dao $dao)
    {
        $this->lstrans_dao = $dao;
    }

    public function update_promo($code)
    {
        if ($promo_cd_obj = $this->get_promo_cd_srv()->get(array("code" => $code))) {
            $promo_cd_obj->set_no_taken($promo_cd_obj->get_no_taken() + 1);
            $this->get_promo_cd_srv()->update($promo_cd_obj);
        }
    }

    public function get_promo_cd_srv()
    {
        return $this->promo_cd_srv;
    }

    public function set_promo_cd_srv($value)
    {
        $this->promo_cd_srv = $value;
    }

    public function sor_add($vars = array())
    {
        include_once(APPPATH . "helpers/object_helper.php");
        $sor_dao = $this->get_so_srv()->get_sor_dao();
        $this->sor = $sor_dao->get(array("so_no" => $this->so->get_so_no()));
        if (!$this->sor) {
            $sor_obj = $sor_dao->get();
            set_value($sor_obj, $vars);
            $sor_obj->set_so_no($this->so->get_so_no());
            $this->sor = $sor_dao->insert($sor_obj);
        }
    }

    public function sor_update($vars = array())
    {
        include_once(APPPATH . "helpers/object_helper.php");
        $sor_dao = $this->get_so_srv()->get_sor_dao();
        if ($this->sor = $sor_dao->get(array("so_no" => $this->so->get_so_no()))) {
            set_value($this->sor, $vars);
            $sor_dao->update($this->sor);
        }
    }

    public function socc_add($vars = array())
    {
        include_once(APPPATH . "helpers/object_helper.php");
        $socc_dao = $this->get_so_srv()->get_socc_dao();
        if ($this->socc || ($this->socc = $socc_dao->get(array("so_no" => $this->so->get_so_no())))) {
            set_value($this->socc, $vars);
            $socc_dao->update($this->socc);
        } else {
            $socc_obj = $socc_dao->get();
            set_value($socc_obj, $vars);
            $socc_obj->set_so_no($this->so->get_so_no());
            $this->socc = $socc_dao->insert($socc_obj);
        }
    }

    public function store_af_info($af = NULL)
    {
        if ($this->so) {
            if (is_null($af)) {
                $af_data = $this->get_af_srv()->get_af_record();
            } else {
                $af_data = is_array($af) ? $af : array("af" => $af);
            }

            if ($af_data) {
                $vars["conv_site_id"] = $af_data["af"];
                if (is_array($af_data) && !is_null($af_data["af_ref"])) {
                    $vars["conv_site_ref"] = $af_data["af_ref"];
                }
            }
//put GST
            $entity_id = $this->get_so_srv()->get_entity_srv()->get_entity_id($this->so->get_amount(), $this->so->get_currency_id());
            $vars["entity_id"] = $entity_id;
            $this->soext_add($vars);
        }
    }

    public function get_af_srv()
    {
        return $this->af_srv;
    }

    public function set_af_srv($value)
    {
        $this->af_srv = $value;
    }

    public function add_note()
    {
        if ($this->so) {
            $son_dao = $this->get_so_srv()->get_son_dao();
            $son_vo = $son_dao->get();
            $son_vo->set_type("C");
            $son_vo->set_so_no($this->so->get_so_no());
            $son_vo->set_note($this->note);
            return $son_dao->insert($son_vo);
        }
    }

    public function unset_variable()
    {
        unset($_SESSION["cart"]);
        unset($_SESSION["cart_from_url"]);
        unset($_SESSION["ra_items"]);
        unset($_SESSION["warranty"]);
        unset($_SESSION["promotion_code"]);
        unset($_SESSION["POSTFORM"]);
    }

    public function low_risk_country_rules($amount, $bill_country_id, $del_country_id)
    {
        $country_list = array("BE", "FI", "DK", "NL");
        if ($bill_country_id == $del_country_id) {
            if (in_array($bill_country_id, $country_list) && $amount < 500) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    public function get_http()
    {
        return $this->http;
    }

    public function set_http($value)
    {
        $this->http = $value;
    }

    public function get_hi_dao()
    {
        return $this->hi_dao;
    }

    public function set_hi_dao($value)
    {
        $this->hi_dao = $value;
    }

    public function get_checkout_url()
    {
        return $this->checkout_url;
    }

    public function set_checkout_url($value)
    {
        $this->checkout_url = $value;
    }

    public function get_website_url()
    {
        return $this->website_url;
    }

    public function set_website_url($value)
    {
        $this->website_url = $value;
    }

    public function get_response_url()
    {
        return $this->response_url;
    }

    public function set_response_url($value)
    {
        $this->response_url = $value;
    }

    public function get_client_dao()
    {
        return $this->client_dao;
    }

    public function set_client_dao(Base_dao $dao)
    {
        $this->client_dao = $dao;
    }

    public function get_so_bank_transfer_dao()
    {
        return $this->so_bank_transfer_dao;
    }

    public function get_client_srv()
    {
        return $this->client_srv;
    }

    public function set_client_srv($value)
    {
        $this->client_srv = $value;
    }

    public function get_sopl_srv()
    {
        return $this->sopl_srv;
    }

    public function get_sopql_srv()
    {
        return $this->sopql_srv;
    }

    public function set_sopql_srv($value)
    {
        $this->sopql_srv = $value;
    }
}

/* End of file pmgw_google_service.php */
/* Location: ./system/application/libraries/service/Pmgw_google_service.php */