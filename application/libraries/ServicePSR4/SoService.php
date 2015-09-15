<?php
namespace ESG\Panther\Service;

use ESG\Panther\Service\ExchangeRateService;
use ESG\Panther\Service\EventService;
use ESG\Panther\Service\EntityService;

class SoService extends BaseService
{
    const PERMANENT_HOLD_STATUS = 10;
    private $declared_value_debug = "";
    private $sub_domain_cache = null;

    public function __construct()
    {
        parent::__construct();

        $this->exchangeRateService = new ExchangeRateService;
        $this->eventService = new EventService;
        $this->entityService = new EntityService;


        // include_once(APPPATH . "libraries/service/Cart_session_service.php");
        // $this->set_cart_srv(new Cart_session_service());
        // include_once(APPPATH . "helpers/image_helper.php");
        //
        // include_once(APPPATH . "libraries/service/Data_exchange_service.php");
        // $this->set_dex_service(new Data_exchange_service());


        // include_once(APPPATH . 'libraries/service/Domain_platform_service.php');
        // $this->set_domain_platform_service(new Domain_platform_service());
        // include_once(APPPATH . 'libraries/service/Price_service.php');
        // $this->set_price_service(new Price_service());
        // include_once(APPPATH . 'libraries/service/Wow_email_service.php');
        // $this->set_wow_email_service(new Wow_email_service());

        // include_once(APPPATH . 'libraries/service/Pdf_rendering_service.php');
        // $this->set_pdf_rendering_srv(new Pdf_rendering_service());

        // include_once APPPATH . "libraries/dao/Payment_gateway_dao.php";
        // $this->set_payment_gateway_dao(new Payment_gateway_dao());

        // include_once APPPATH . "libraries/service/Subject_domain_service.php";
        // $this->set_sub_domain_srv(new Subject_domain_service());
        // include_once APPPATH . "libraries/service/So_priority_score_service.php";
        // $this->set_so_ps_srv(new So_priority_score_service());


        // include_once APPPATH . "libraries/service/Email_referral_list_service.php";
        // $this->set_email_referral_list_service(new Email_referral_list_service());
        // include_once APPPATH . "libraries/service/Fraudulent_order_service.php";
        // $this->set_fraudulent_order_service(new Fraudulent_order_service());
        // include_once APPPATH . "libraries/service/Complementary_acc_service.php";
        // $this->set_ca_service(new Complementary_acc_service());


        // include_once APPPATH . "libraries/service/Review_fianet_service.php";
        // $this->set_review_fianet_service(new Review_fianet_service());
        // include_once APPPATH . "libraries/service/Sequence_service.php";
        // $this->set_sequence_service(new Sequence_service());
        // include_once APPPATH . "libraries/service/Delayed_order_service.php";
        // $this->set_delay_order_service(new Delayed_order_service());

    }

    public function cart_to_so(&$vars)
    {
        if ($vars["platform_id"] == "") {
            $vars["platform_id"] = PLATFORMID;
        }

        $dao = $this->getDao('So');
        $sops_dao = $this->getDao('SoPaymentStatus');

        if (isset($_SESSION["so_no"])) {
            if (($ps_obj = $this->getDao('SoPaymentStatus')->get(array("so_no" => $_SESSION["so_no"]))) !== FALSE) {
                if (empty($ps_obj) || ($ps_obj->get_payment_status() == "N" && $ps_obj->get_payment_gateway_id() == "google")) {
                    $so_no = $_SESSION["so_no"];
                    $so_vo = $dao->get(array("so_no" => $so_no));
                    $sops_vo = $sops_dao->get(array("so_no" => $so_no));
                    $process = "update";
                }
            }
        }
        if (empty($so_no)) {
            unset($_SESSION["so_no"]);
            $so_vo = $dao->get();
            $sops_vo = $sops_dao->get();
            $process = "insert";
        }

        if ($vars["promotion_code"] == "" && $_SESSION["promotion_code"]) {
            $vars["promotion_code"] = $_SESSION["promotion_code"];
        }

        if (!$vars["delivery"] && !($vars["delivery"] = $cart_list["dc_default"]["courier"])) {
            $vars["delivery"] = $this->getDao('Config')->valueOf("default_delivery_type");
        }

        $this->get_cart_srv()->set_delivery_mode($vars["delivery"]);

        switch ($vars["biz_type"]) {
            case "offline":
            case "manual":
            case "special":
                if ($vars["client"]) {
                    if (substr(strtoupper($vars["platform_id"]), 0, 3) == "WEB") {
                        $delivery_country = $vars["client"]->get_del_country_id();
                    } else {
                        $delivery_country = '';
                    }
                } else {
                    $delivery_country = NULL;
                }
                break;
            default:
                if (($sp_obj = $this->getDao('SellingPlatform')->get(array("selling_platform_id" => $vars["platform_id"]))) && $sp_obj->get_type() == "SKYPE") {
                    $delivery_country = NULL;
                } else {
                    $delivery_country = (isset($_SESSION["client"]["del_country_id"]) ? $_SESSION["client"]["del_country_id"] : NULL);
                }
                break;
        }

        if (($cart_list = $this->get_cart_srv()->get_detail($vars["platform_id"], 1, 1, 1, ($vars["biz_type"] == "special" || $vars["biz_type"] == "manual"), $vars["vat_exempt"], $vars["free_delivery"], $vars["customized_delivery"], $vars["promotion_code"], 0, $delivery_country)) === FALSE) {
            return FALSE;
        }
        $vars["promo"] = $cart_list["promo"];

        $card_id = "";

        if ($vars["biz_type"] != "manual" && $vars["biz_type"] != "offline") {
            switch ($vars["payment_gateway"]) {
                case "bibit":
                case "moneybookers":
                case "moneybookers_ctpe":
                case "cybersource":
                case "worldpay":
                case "trustly":
                case "inpendium_ctpe":
                case "worldpay_moto":
                case "paypal":
                case "global_collect":
                case "w_bank_transfer":
                case "yandex":
                case "altapay":
                case "adyen":

                    if ($_SESSION["client"]) {
                        $so_vo->set_client_id($_SESSION["client"]["id"]);
                        $so_vo->set_bill_name($_SESSION["client"]["title"] . " " . $_SESSION["client"]["forename"] . " " . $_SESSION["client"]["surname"]);
                        $so_vo->set_bill_company($_SESSION["client"]["companyname"]);
                        $so_vo->set_bill_address(trim($_SESSION["client"]["address_1"] . "|" . $_SESSION["client"]["address_2"] . "|" . $_SESSION["client"]["address_3"]));
                        $so_vo->set_bill_postcode($_SESSION["client"]["postcode"]);
                        $so_vo->set_bill_city($_SESSION["client"]["city"]);
                        $so_vo->set_bill_state($_SESSION["client"]["state"]);
                        $so_vo->set_bill_country_id($_SESSION["client"]["country_id"]);
                        $so_vo->set_delivery_charge($cart_list["dc"][$vars["delivery"]]["charge"] * 1 + $cart_list["dc"][$vars["delivery"]]["surcharge"] * 1);
                        $so_vo->set_delivery_type_id($vars["delivery"]);
                        if ($vars["payment_gateway"] == "bibit") {

                            $vars["ordercontent"] = '<link rel="stylesheet" type="text/css" href="https://' . $_SERVER['HTTP_HOST'] . '/css/style_redirect.css">
    <hr><table><tr><td valign="top">Delivery Address:</td>
    <td>' . str_replace("|", "<br />", $so_vo->get_bill_address()) . '<br />' . $so_vo->get_bill_city() . ' ' . $so_vo->get_bill_postcode() . '<br />' . $so_vo->get_bill_country_id() . '</td></tr></table><hr>
    <center><table width="100%" cellspacing="0" id="checkout"><tr class="header"><td>Product</td><td>Qty</td><td>Total</td></tr>';
                        } else {
                            $card_id = $vars["payment_methods"];
                            $so_vo->set_delivery_name($_SESSION["client"]["del_name"]);
                            $so_vo->set_delivery_company($_SESSION["client"]["del_company"]);
                            $so_vo->set_delivery_address(trim($_SESSION["client"]["del_address_1"] . "|" . $_SESSION["client"]["del_address_2"] . "|" . $_SESSION["client"]["del_address_3"]));
                            $so_vo->set_delivery_postcode($_SESSION["client"]["del_postcode"]);
                            $so_vo->set_delivery_city($_SESSION["client"]["del_city"]);
                            $so_vo->set_delivery_state($_SESSION["client"]["del_state"]);
                            $so_vo->set_delivery_country_id($_SESSION["client"]["del_country_id"]);
                        }
                        $so_vo->set_fingerprintId($vars["cybersource_fingerprint"]);
                    } else {
                        return FALSE;
                    }
                    break;
                case "google":
                    $so_vo->set_delivery_charge($cart_list["dc_default"]["charge"] * 1 + $cart_list["dc_default"]["surcharge"] * 1);
                    $so_vo->set_delivery_type_id($vars["delivery"]);
                    break;
            }
        }

        $soi_dao = $this->getDao('SoItem');
        $soi_vo = $soi_dao->get();

        #SBF #4324 - apply to all other marketplaces
        $get_ca = true;
        if ($_SESSION["client"]["del_country_id"]) {
            $del_country_id = $_SESSION["client"]["del_country_id"];
        } else {
            switch ($vars["biz_type"]) {
                case "offline":
                case "manual":
                    $del_country_id = $vars["client"]->get_del_country_id();
                    break;
                case "special":
                    include_once(APPPATH . "libraries/service/Client_service.php");
                    $client_service = new Client_service();
                    $client_last_order = $client_service->get_client_last_order($vars["client"]->get_email());
                    $del_country_id = $client_last_order->get_delivery_country_id();
                    break;
            }
        }
        $amount = $curr_line_no = 0;
        $ca_soi_list = $all_del_info = array();
        foreach ($cart_list["cart"] as $line_no => $soi) {
            $curr_line_no = $line_no + 1;
            $soi_obj = clone $soi_vo;
            $soi_obj->set_line_no($line_no + 1);
            $soi_obj->set_prod_sku($soi["sku"]);
            $soi_obj->set_prod_name($soi["name"]);
            $soi_obj->set_qty($soi["qty"]);
            $soi_obj->set_vat_total($soi["vat_total"]);
            $soi_obj->set_gst_total($soi["gst"]);
            if ($vars["biz_type"] == "special") {
                $soi["cost"] *= 1;
            }

            $soi_obj->set_unit_price($soi["price"]);
            $soi_obj->set_amount($soi["total"]);
            $amount += ($soi["total"] + $soi["gst"]);

            if ($get_ca === true) {
                $where["dest_country_id"] = $del_country_id;
                $where["mainprod_sku"] = $soi["sku"];
                if ($mapped_ca_list = $this->get_ca_service()->get_mapped_acc_list_w_name($where, $option, true)) {
                    foreach ($mapped_ca_list as $obj) {
                        $obj->set_quantity($soi["qty"]);
                        $ca_soi_list[$soi["sku"]][] = $obj;
                    }
                }
            }

            if (isset($soi["product_cost_obj"])) {
                $soi_obj->set_warranty_in_month($soi["product_cost_obj"]->get_warranty_in_month());
                $soi_obj->set_website_status($soi["product_cost_obj"]->get_website_status());
            }

            if ($price_obj = $this->get_price_service()->get(array("sku" => $soi["sku"], "platform_id" => $vars["platform_id"]))) {
                # SBF #4020 - each price obj has scenarioid with delivery time frames
                if ($delivery_scenarioid = $price_obj->get_delivery_scenarioid())
                    $delivery_obj = $this->getDao('DeliveryTime')->getDeliverytimeObj($del_country_id, $delivery_scenarioid);

                if (!empty($delivery_obj)) {
                    // get delivery time frames for all products in basket to compare later
                    $all_del_info["del_min"][$delivery_scenarioid] = $delivery_obj->get_del_min_day();
                    $all_del_info["del_max"][$delivery_scenarioid] = $delivery_obj->get_del_max_day();
                    $all_del_info["ship_min"][$delivery_scenarioid] = $delivery_obj->get_ship_min_day();
                    $all_del_info["ship_max"][$delivery_scenarioid] = $delivery_obj->get_ship_max_day();
                }
            }

            $cost += $soi["cost"];

            # only create row / set other info if item is not complementary accessory
            $is_complementary_acc = $this->get_ca_service()->check_cat($soi["sku"], true);
            if ($is_complementary_acc["status"] !== true) {
                if ($vars["payment_gateway"] == "bibit") {
                    $vars["ordercontent"] .= '<tr><td>' . $soi["name"] . '</td><td>' . $soi["qty"] . '</td><td>' . number_format($soi["total"], 2) . '</td></tr>';
                }
            }

            $so_item_list[] = $so_item_list_without_ca[] = $soi_obj;
        }

        if ($all_del_info) {
            // Going forward, this should be replacing "set_expected_delivery_date()" above
            // after looping through all items, see which has the largest delivery days and use that scenario
            $largest_day = max($all_del_info["del_max"]);
            $selected_scenarioid = array_search($largest_day, $all_del_info["del_max"]);
            $selected_del_info["shipping"] = $all_del_info["ship_min"][$selected_scenarioid] . " - " . $all_del_info["ship_max"][$selected_scenarioid];
            $selected_del_info["delivery"] = $all_del_info["del_min"][$selected_scenarioid] . " - " . $all_del_info["del_max"][$selected_scenarioid];

            $so_vo->set_expect_ship_days($selected_del_info["shipping"]);
            $so_vo->set_expect_del_days($selected_del_info["delivery"]);
        }

        # add on complementary accessories in so_item
        if ($ca_soi_list) {
            $ca_line_no = $curr_line_no + 1;
            foreach ($ca_soi_list as $arr) {
                foreach ($arr as $obj) {
                    $soi_obj = clone $soi_vo;
                    $soi_obj->set_line_no($ca_line_no);
                    $soi_obj->set_prod_sku($obj->get_accessory_sku());
                    $soi_obj->set_prod_name($obj->get_name());
                    $soi_obj->set_qty($obj->get_quantity());
                    $soi_obj->set_vat_total(0.00);
                    $soi_obj->set_gst_total(0.00);
                    $soi_obj->set_unit_price(0.00);
                    $soi_obj->set_amount(0);

                    $so_item_list[] = $soi_obj;
                    $ca_line_no++;
                }
            }
        }

        // prevent complementary accessories from showing on customer's payment gateways
        $vars["so_item_list"] = $so_item_list_without_ca;

        if ($vars["payment_gateway"] == "bibit") {
            $vars["ordercontent"] .= '</table>';
        }

        if ($vars["special"]) {
            if ($cart_list["cart"]) {
                $line_no++;
            }
            if (!$pbv_obj) {
                $pbv_obj = $this->getDao('PlatformBizVar')->get(array("selling_platform_id" => $vars["platform_id"]));
            }
            $vat_percent = $pbv_obj->get_vat_percent();
            $declared_pcent = 20;
            foreach ($vars["special"] as $soi) {
                $soi_obj = clone $soi_vo;
                $soi_obj->set_line_no($line_no + 1);
                $soi_obj->set_prod_sku($soi["sku"]);
                $soi_obj->set_prod_name($soi["name"]);
                $soi_obj->set_qty($soi["qty"]);

                if ($obj = $this->get_sub_domain_srv()->get(array("subject" => "MAX_DECLARE_VALUE.{$pbv_obj->get_platform_country_id()}"))) {
                    $max_value = $obj->get_value();
                    $declared = min($max_value, $soi["total"]);
                } else {
                    $declared = $soi["total"] * $declared_pcent / 100;
                }

                $vat = round($declared * $vat_percent / 100, 2);
                $pbvat = $soi["total"] - $vat;
                $soi_obj->set_unit_price(round($pbvat, 2));
                $soi_obj->set_vat_total(round($vat * $soi["qty"] * (1 - $vars["vat_exempt"]), 2));
                $soi_obj->set_gst_total($soi["gst"]);
                if ($vars["vat_exempt"]) {
                    $soi_obj->set_amount(round($pbvat * $soi["qty"], 2));
                } else {
                    $soi_obj->set_amount(round($soi["total"] * $soi["qty"], 2));
                }
                $so_item_list[] = $soi_obj;
                $special_list[] = $soi_obj;
                $amount += ($vars["vat_exempt"] ? $pbvat * $soi["qty"] : $soi["total"] * $soi["qty"]);
                $amount += $soi["gst"];
                $cost += $soi_obj->get_vat_total();
                $line_no++;
            }
        }

        $so_vo->set_platform_id($vars["platform_id"]);
        $so_vo->set_lang_id(get_lang_id());
        if ($vars["biz_type"] == "manual") {
            $so_vo->set_status(0);
        } else {
            $so_vo->set_status(1);
        }
        $so_vo->set_refund_status(0);
        $so_vo->set_hold_status(0);
        $so_vo->set_amount($amount + $so_vo->get_delivery_charge());
        $vars["total_so_amount"] = $amount + $so_vo->get_delivery_charge();
        if ($vars["biz_type"] == "special") {
            $cost = $cost * 1;
        }
        $so_vo->set_cost($cost);

        switch ($vars["biz_type"]) {
            case "offline":
            case "manual":
            case "special":
                if ($vars["client"]) {
                    $so_vo->set_delivery_charge($cart_list["dc"][$vars["delivery"]]["charge"] * 1 + $cart_list["dc"][$vars["delivery"]]["surcharge"] * 1);
                    $so_vo->set_client_id($vars["client"]->get_id());
                    if ($vars["biz_type"] != "special") {
                        $so_vo->set_bill_name($vars["client"]->get_forename() . " " . $vars["client"]->get_surname());
                        $so_vo->set_bill_company($vars["client"]->get_companyname());
                        $so_vo->set_bill_address(trim($vars["client"]->get_address_1() . "|" . $vars["client"]->get_address_2() . "|" . $vars["client"]->get_address_3()));
                        $so_vo->set_bill_postcode($vars["client"]->get_postcode());
                        $so_vo->set_bill_city($vars["client"]->get_city());
                        $so_vo->set_bill_state($vars["client"]->get_state());
                        $so_vo->set_bill_country_id($vars["client"]->get_country_id());
                    }
                    if ($vars["biz_type"] == "offline") {
                        $so_vo->set_delivery_type_id($vars["delivery"]);
                        $so_vo->set_amount($so_vo->get_amount() + $so_vo->get_delivery_charge() + $vars["so_extend"]["offline_fee"]);
                        $so_vo->set_biz_type("OFFLINE");
                        $so_vo->set_txn_id($vars["txn_id"]);
                        $so_vo->set_delivery_name($vars["client"]->get_del_name());
                        $so_vo->set_delivery_company($vars["client"]->get_del_company());
                        $so_vo->set_delivery_address(trim($vars["client"]->get_del_address_1() . "|" . $vars["client"]->get_del_address_2() . "|" . $vars["client"]->get_del_address_3()));
                        $so_vo->set_delivery_postcode($vars["client"]->get_del_postcode());
                        $so_vo->set_delivery_city($vars["client"]->get_del_city());
                        $so_vo->set_delivery_state($vars["client"]->get_del_state());
                        $so_vo->set_delivery_country_id($vars["client"]->get_del_country_id());
                    } elseif ($vars["biz_type"] == "manual") {
                        $so_vo->set_biz_type("MANUAL");
                        $so_vo->set_txn_id($vars["txn_id"]);
                        $so_vo->set_delivery_type_id($this->getDao('PlatformBizVar')->get(array("selling_platform_id" => $vars["platform_id"]))->get_delivery_type());
                        $so_vo->set_amount($so_vo->get_amount() + $so_vo->get_delivery_charge());
                        $so_vo->set_delivery_name($vars["client"]->get_del_name());
                        $so_vo->set_delivery_company($vars["client"]->get_del_company());
                        $so_vo->set_delivery_address(trim($vars["client"]->get_del_address_1() . "|" . $vars["client"]->get_del_address_2() . "|" . $vars["client"]->get_del_address_3()));
                        $so_vo->set_delivery_postcode($vars["client"]->get_del_postcode());
                        $so_vo->set_delivery_city($vars["client"]->get_del_city());
                        $so_vo->set_delivery_state($vars["client"]->get_del_state());
                        $so_vo->set_delivery_country_id($vars["client"]->get_del_country_id());
                    } else {
                        $so_vo->set_biz_type("SPECIAL");
                        include_once(APPPATH . "libraries/service/Client_service.php");
                        $client_service = new Client_service();
                        $client_last_order = $client_service->get_client_last_order($vars["client"]->get_email());
                        $so_vo->set_parent_so_no($vars["parent_so_no"]);
                        $so_vo->set_split_so_group($vars["split_so_group"]);
                        $so_vo->set_bill_name($client_last_order->get_bill_name());
                        $so_vo->set_bill_company($client_last_order->get_bill_company());
                        $so_vo->set_bill_address($client_last_order->get_bill_address());
                        $so_vo->set_bill_postcode($client_last_order->get_bill_postcode());
                        $so_vo->set_bill_city($client_last_order->get_bill_city());
                        $so_vo->set_bill_state($client_last_order->get_bill_state());
                        $so_vo->set_bill_country_id($client_last_order->get_bill_country_id());

                        $so_vo->set_delivery_name($client_last_order->get_delivery_name());
                        $so_vo->set_delivery_company($client_last_order->get_delivery_company());
                        $so_vo->set_delivery_address($client_last_order->get_delivery_address());
                        $so_vo->set_delivery_postcode($client_last_order->get_delivery_postcode());
                        $so_vo->set_delivery_city($client_last_order->get_delivery_city());
                        $so_vo->set_delivery_state($client_last_order->get_delivery_state());
                        $so_vo->set_delivery_country_id($client_last_order->get_delivery_country_id());

                        $so_vo->set_delivery_type_id($this->getDao('PlatformBizVar')->get(array("selling_platform_id" => $vars["platform_id"]))->get_delivery_type());
                        $so_vo->set_amount($so_vo->get_amount() + $so_vo->get_delivery_charge());
                    }
                    $platform_obj = $this->getDao('PlatformBizVar')->get(array("selling_platform_id" => $vars["platform_id"]));
                    $vars["currency_id"] = $platform_obj->get_platform_currency_id();
                    $soext_dao = $this->getDao('SoExtend');
                    $soext_vo = $soext_dao->get();
                    $son_dao = $this->getDao('OrderNotes');
                    $son_vo = $son_dao->get();
                    include_once(APPPATH . "helpers/object_helper.php");
                    set_value($soext_vo, $vars["so_extend"]);
                    $soext_vo->set_vatexempt($vars["vat_exempt"]);

                } else {
                    $_SESSION["NOTICE"] = "Error: " . __LINE__ . $dao->db->_error_message();
                    return FALSE;
                }
                break;
            default:
                if (defined("ENTRYPOINT") && (ENTRYPOINT == "MOBILE")) {
                    $so_vo->set_biz_type("MOBILE");
                } else {
                    $so_vo->set_biz_type("ONLINE");
                }
                break;
        }

        if ($so_vo->get_delivery_name() == "") {
            $so_vo->set_delivery_name($so_vo->get_bill_name());

            if ($so_vo->get_delivery_company() == "") {
                $so_vo->set_delivery_company($so_vo->get_bill_company());
            }
        }

        if ($so_vo->get_delivery_address() == "") {
            $so_vo->set_delivery_address($so_vo->get_bill_address());
        }
        if ($so_vo->get_delivery_postcode() == "") {
            $so_vo->set_delivery_postcode($so_vo->get_bill_postcode());
        }
        if ($so_vo->get_delivery_city() == "") {
            $so_vo->set_delivery_city($so_vo->get_bill_city());
        }
        if ($so_vo->get_delivery_state() == "") {
            $so_vo->set_delivery_state($so_vo->get_bill_state());
        }
        if ($so_vo->get_delivery_country_id() == "") {
            $so_vo->set_delivery_country_id($so_vo->get_bill_country_id());
        }

        $so_vo->set_currency_id($vars["currency_id"]);
        $so_vo->set_order_create_date(date("Y-m-d H:i:s"));
        $so_vo->set_weight($cart_list["weight"] * 1);

        if ($pbv_obj = $this->getDao('PlatformBizVar')->get(array("selling_platform_id" => $vars["platform_id"]))) {
            $so_vo->set_vat_percent($pbv_obj->get_vat_percent());
            $lang_id = $pbv_obj->get_language_id();
        }

        $type = $this->getDao('SellingPlatform')->get(array("id" => $vars["platform_id"]))->get_type();

        $to_currency_id = $this->getDao('Config')->valueOf("func_curr_id");
        if ($er_obj = $this->getDao('ExchangeRate')->get(array("from_currency_id" => $vars["currency_id"], "to_currency_id" => $to_currency_id))) {
            $so_vo->set_rate($er_obj->get_rate());
        }

        //added by Jack for rate in EUR
        if ($er_eur_obj = $this->getDao('ExchangeRate')->get(array("from_currency_id" => $vars["currency_id"], "to_currency_id" => "EUR"))) {
            $so_vo->set_ref_1($er_eur_obj->get_rate());
        }

        //Promotion Code
        $so_vo->set_promotion_code(@call_user_func(array($vars["promo"]["promotion_code_obj"], "get_code")));
        $so_vo->set_client_promotion_code($vars["promotion_code"]);
        $so_vo->set_amount($so_vo->get_amount());

        $soid_dao = $this->getDao('SoItemDetail');
        $soid_vo = $soid_dao->get();
        $dao->trans_start();

        if (!isset($_SESSION["so_no"])) {
            $next_val = $dao->seq_next_val();
            $so_no = $next_val;
            $so_vo->set_so_no($so_no);
            if ($vars["biz_type"] == "manual") {
                if ($vars["platform_order_id"]) {
                    $so_vo->set_platform_order_id($vars["platform_order_id"]);
                } else {
                    $so_vo->set_platform_order_id($so_no);
                }
            } else {
                $so_vo->set_platform_order_id($so_no);
            }
        }

        if ($new_so_vo = $dao->$process($so_vo)) {
            $failed = 0;
            if (isset($_SESSION["so_no"]) && ($soid_dao->q_delete(array("so_no" => $_SESSION["so_no"])) === FALSE || $soi_dao->q_delete(array("so_no" => $_SESSION["so_no"])) === FALSE || $sops_dao->q_delete(array("so_no" => $_SESSION["so_no"])) === FALSE)) {
                if ($so_vo->get_biz_type() == "ONLINE" || $so_vo->get_biz_type() == "MOBILE") {
                    $_SESSION["NOTICE"] = "Error: " . __LINE__;
                } else {
                    $_SESSION["NOTICE"] = "Error: " . __LINE__ . $dao->db->_error_message();
                }
                $failed = 1;
            }
            if (!$failed) {
                foreach ($so_item_list as $soi_obj) {
                    $soi_obj->set_so_no($so_no);
                    if (!$soi_dao->insert($soi_obj)) {
                        if ($so_vo->get_biz_type() == "ONLINE" || $so_vo->get_biz_type() == "MOBILE") {
                            $_SESSION["NOTICE"] = "Error: " . __LINE__;
                        } else {
                            $_SESSION["NOTICE"] = "Error: " . __LINE__ . $dao->db->_error_message();
                        }
                        $failed = 1;
                        break;
                    }
                }
            }
            if (!$failed) {
                if ($cart_list["detail"]) {
                    $ca_soid_list = array();
                    foreach ($cart_list["detail"] as $line_no => $soi) {
                        $curr_line_no = $line_no + 1;
                        foreach ($soi as $soid) {
                            if (is_array($soid["qty"])) {
                                $qty = $soid["qty"]["qty"];
                            } else {
                                $qty = $soid["qty"];
                            }
                            $soid_obj = clone $soid_vo;
                            $soid_obj->set_so_no($so_no);
                            $soid_obj->set_line_no($curr_line_no);
                            $soid_obj->set_item_sku($soid["sku"]);
                            $soid_obj->set_qty($qty);
                            $soid_obj->set_outstanding_qty($qty);
                            $soid_obj->set_unit_price($soid["price"] * 1);
                            $soid_obj->set_vat_total($soid["vat_total"]);
                            $soid_obj->set_gst_total($soid["gst"]);
                            $soid_obj->set_discount($soid["discount"]);
                            $soid_obj->set_amount($soid["total"]);
                            $soid_obj->set_promo_disc_amt($soid["promo_disc_amt"] * 1);
                            $soid_obj->set_cost($soid["cost"]);
                            $soid_obj->set_item_unit_cost($soid["product_cost_obj"]->get_item_cost());

                            if ($get_ca === true) {
                                $where["dest_country_id"] = $del_country_id;
                                $where["mainprod_sku"] = $soid["sku"];
                                if ($mapped_ca_list = $ca_soi_list[$soid["sku"]]) {
                                    foreach ($mapped_ca_list as $obj) {
                                        $obj->set_quantity($qty);
                                        $ca_soid_list[] = $obj;
                                    }
                                }
                            }

                            $this->set_profit_info($soid_obj);

                            #sbf #4424 - set raw profit info without promo codes
                            $this->set_profit_info_raw($soid_obj, $vars["platform_id"]);

                            if (!$soid_dao->insert($soid_obj)) {
                                // Front End don't show db error
                                if ($so_vo->get_biz_type() == "ONLINE" || $so_vo->get_biz_type() == "MOBILE") {
                                    $_SESSION["NOTICE"] = "Error: " . __LINE__;
                                } else {
                                    $_SESSION["NOTICE"] = "Error: " . __LINE__ . $dao->db->_error_message();
                                }
                                $failed = 1;
                                break;
                            }
                        }
                        if ($failed) {
                            break;
                        }
                    }

                    if ($ca_soid_list) {
                        $ca_line_no = $curr_line_no + 1;
                        foreach ($ca_soid_list as $obj) {
                            $this->init_price_service($type);
                            $soid_obj = clone $soid_vo;
                            # the cost for complementary accessory is supplier cost
                            $ca_priceobj = $this->price_service->get_dao()->get_list_with_bundle_checking($obj->get_accessory_sku(), $vars["platform_id"], $lang_id);
                            if ($ca_priceobj) {
                                foreach ($ca_priceobj as $value) {
                                    $soid_obj->set_item_unit_cost($value->get_item_cost());
                                    $cost = $value->get_supplier_cost();
                                }
                            }
                            $soid_obj->set_so_no($so_no);
                            $soid_obj->set_line_no($ca_line_no);
                            $soid_obj->set_item_sku($obj->get_accessory_sku());
                            $soid_obj->set_qty($obj->get_quantity());
                            $soid_obj->set_outstanding_qty($obj->get_quantity());
                            $soid_obj->set_unit_price(0.00);
                            $soid_obj->set_vat_total(0.00);
                            $soid_obj->set_gst_total(0.00);
                            $soid_obj->set_discount(0.00);
                            $soid_obj->set_amount(0);
                            $soid_obj->set_promo_disc_amt(0.00);
                            $soid_obj->set_cost($cost * $obj->get_quantity());
                            $soid_obj->set_profit(0.00);
                            $soid_obj->set_margin(0.00);

                            $ca_line_no++;

                            if (!$soid_dao->insert($soid_obj)) {
                                // Front End don't show db error
                                if ($so_vo->get_biz_type() == "ONLINE" || $so_vo->get_biz_type() == "MOBILE") {
                                    $_SESSION["NOTICE"] = "Error: " . __LINE__;
                                } else {
                                    $_SESSION["NOTICE"] = "Error: " . __LINE__ . $dao->db->_error_message();
                                }
                                $failed = 1;
                                break;
                            }

                            if ($failed) {
                                break;
                            }
                        }
                    }
                }
                if ($special_list && !$failed) {
                    foreach ($special_list as $soid) {
                        $soid_obj = clone $soid_vo;
                        $soid_obj->set_so_no($so_no);
                        $soid_obj->set_line_no($soid->get_line_no());
                        $soid_obj->set_item_sku($soid->get_prod_sku());
                        $soid_obj->set_item_sku($soid->get_prod_sku());
                        $soid_obj->set_qty($soid->get_qty());
                        $soid_obj->set_outstanding_qty($soid->get_qty());
                        $soid_obj->set_unit_price($soid->get_unit_price());
                        $soid_obj->set_vat_total($soid->get_vat_total());
                        $soid_obj->set_gst_total($soid->get_gst_total());
                        $soid_obj->set_discount(0);
                        $soid_obj->set_amount($soid->get_amount());
                        $soid_obj->set_cost($soid->get_vat_total());

                        $is_complementary_acc = $this->get_ca_service()->check_cat($soid["sku"], true);
                        if ($is_complementary_acc["status"] === false) {
                            $this->set_profit_info($soid_obj);
                        }

                        if (!$soid_dao->insert($soid_obj)) {
                            $_SESSION["NOTICE"] = "Error: " . __LINE__ . ": " . $son_dao->db->_error_message();
                            $failed = 1;
                            break;
                        }
                        if ($failed) {
                            break;
                        }
                    }
                }

                if (!$failed) {
                    if ($so_vo->get_biz_type() == "ONLINE" || $so_vo->get_biz_type() == "MOBILE") {
                        if (!($so_vo->get_amount() == 0 && $vars["all_trial"] && $vars["all_virtual"])) {
                            $sops_vo->set_so_no($so_no);
                            $sops_vo->set_payment_gateway_id($vars["payment_gateway"]);
                            $sops_vo->set_payment_status('N');
                            if ($card_id) {
                                $sops_vo->set_card_id($card_id);
                            }
                            if (($vars["sops"] = $sops_dao->insert($sops_vo)) === FALSE) {
                                $_SESSION["NOTICE"] = "Error: " . __LINE__;
                                $failed = 1;
                            }
                        }
                    } elseif ($so_vo->get_biz_type() == "OFFLINE" || $so_vo->get_biz_type() == "SPECIAL" || $so_vo->get_biz_type() == "MANUAL") {
                        $entity_id = $this->entityService->getEntityId($so_vo->get_amount(), $so_vo->get_currency_id());
                        $soext_vo->set_entity_id($entity_id);
                        $soext_vo->set_so_no($so_no);
                        $son_vo->set_so_no($so_no);
                        $son_vo->set_note($vars["so_extend"]["notes"]);

                        if ($soext_dao->insert($soext_vo) === FALSE) {
                            $_SESSION["NOTICE"] = "Error: " . __LINE__ . ": " . $son_dao->db->_error_message();
                            $failed = 1;
                        } elseif ($vars["so_extend"]["notes"] && $son_dao->insert($son_vo) === FALSE) {
                            $_SESSION["NOTICE"] = "Error: " . __LINE__ . ": " . $son_dao->db->_error_message();
                            $failed = 1;
                        }

                        if ($vars["so_extend"]["order_reason"] == 31) {
                            #SBF #2450 auto update so_priority_score to 1000 when "bulk sales" selected on phone sales
                            $this->get_so_ps_srv()->insert_sops($so_no, 1000);
                        }
                    }
                    if ($so_vo->get_biz_type() == "MANUAL" || $so_vo->get_biz_type() == "OFFLINE") {
                        if (!($so_vo->get_amount() == 0 && $vars["all_trial"] && $vars["all_virtual"])) {
                            $sops_vo->set_so_no($so_no);
                            $sops_vo->set_payment_gateway_id($vars["payment_gateway"]);
                            $sops_vo->set_pay_date($vars["payment_date"]);
                            $sops_vo->set_pay_to_account($vars["pay_to_account"]);
                            $sops_vo->set_payment_status('S');
                            if ($card_id) {
                                $sops_vo->set_card_id($card_id);
                            }
                            if (($vars["sops"] = $sops_dao->insert($sops_vo)) === FALSE) {
                                $_SESSION["NOTICE"] = "Error: " . __LINE__;
                                $failed = 1;
                            }
                        }
                    }
                }
                if (!$failed && !isset($_SESSION["so_no"])) {
                    $dao->update_seq($next_val);
                }
            }
        } else {
            if ($so_vo->get_biz_type() == "ONLINE" || $so_vo->get_biz_type() == "MOBILE") {
                $_SESSION["NOTICE"] = "Error: " . __LINE__;
            } else {
                $_SESSION["NOTICE"] = "Error: " . __LINE__ . " " . $dao->db->_error_message();
            }
            $failed = 1;
        }
        if ($failed) {
            $dao->trans_rollback();
            $dao->trans_complete();
            return FALSE;
        } else {

            if ($vars["payment_gateway"] == "w_bank_transfer") {
                $_SESSION["W_TRANSFER_ORDER"] = $vars;
                $_SESSION["W_TRANSFER_ORDER"]["so_no"] = $so_no;
            }
            $dao->trans_complete();
            return isset($_SESSION["so_no"]) ? $so_vo : $new_so_vo;
        }
    }


    public function get_cart_srv()
    {
        return $this->cart_srv;
    }

    public function set_cart_srv($value)
    {
        $this->cart_srv = $value;
    }

    public function get_ca_service()
    {
        return $this->ca_service;
    }

    public function set_ca_service($value)
    {
        $this->ca_service = $value;
    }

    public function get_price_service()
    {
        return $this->price_service;
    }

    public function set_price_service(Base_service $srv)
    {
        $this->price_service = $srv;

        return $srv;
    }

    public function get_sub_domain_srv()
    {
        return $this->sub_domain_srv;
    }

    public function set_sub_domain_srv($srv)
    {
        $this->sub_domain_srv = $srv;
        return $srv;
    }

    public function set_profit_info(Base_vo $prod)
    {
        if (!defined('PLATFORM_TYPE'))
            $use_new = true;
        else {
            if (PLATFORM_TYPE == 'EBAY' || PLATFORM_TYPE == 'QOO10' || PLATFORM_TYPE == 'FNAC' || PLATFORM_TYPE == 'RAKUTEN')
                $use_new = false;
            else
                $use_new = true;
        }
        if ($use_new) {
            $cur_platform_id = $this->getDao('So')->get(["so_no" => $prod->get_so_no()])->get_platform_id();
            $type = $this->getDao('SellingPlatform')->get(array("id" => $cur_platform_id))->get_type();

            $this->init_price_service($type);
            $gst = @$prod->get_gst_total();
            $selling_price = ($prod->get_amount() + $gst) / $prod->get_qty();
            $json = $this->price_service->get_profit_margin_json($cur_platform_id, $prod->get_item_sku(), $selling_price);
            file_put_contents("/var/log/vb-json", "{$prod->get_so_no()} || $json", FILE_APPEND);

            $jj = json_decode($json, true);

            $prod->set_cost(round($jj["get_cost"], 2));
            $prod->set_profit(round($jj["get_profit"], 2));
            $prod->set_margin(round($jj["get_margin"], 2));
        } else {
            $prod->set_profit(round($prod->get_amount() - $prod->get_cost(), 2));
            if ($prod->get_amount()) {
                $prod->set_margin(round($prod->get_profit() / $prod->get_amount() * 100, 2));
            } else {
                $prod->set_margin(0);
            }
        }
    }

    public function init_price_service($platform_type)
    {
        if (is_null($platform_type)) {
            include_once APPPATH . "libraries/service/Price_service.php";
            $this->price_service = new Price_service();
        } else {
            $filename = "price_" . strtolower($platform_type) . "_service";
            $classname = ucfirst($filename);
            include_once APPPATH . "libraries/service/{$filename}.php";
            $this->price_service = new $classname();
        }
    }

    public function set_profit_info_raw(Base_vo $prod, $platform_id = "")
    {
        # SBF # 4424 - this function sets profit/margin per unit without applying promo code
        if (!defined('PLATFORM_TYPE'))
            $use_new = true;
        else {
            if (PLATFORM_TYPE == 'EBAY' || PLATFORM_TYPE == 'QOO10' || PLATFORM_TYPE == 'FNAC' || PLATFORM_TYPE == 'RAKUTEN')
                $use_new = false;
            else
                $use_new = true;
        }

        if ($use_new) {
            // website orders come in here

            $platform_id = $this->getDao('So')->get(array("so_no" => $prod->get_so_no()))->get_platform_id();

            if ($platform_id) {
                $type = $this->getDao('SellingPlatform')->get(array("id" => $platform_id))->get_type();

                $this->init_price_service($type);
                $gst = @$prod->get_gst_total();
                $unit_gst = $gst / $prod->get_qty();
                $unit_selling_price = ($prod->get_unit_price() + $unit_gst);
                $json = $this->price_service->get_profit_margin_json($platform_id, $prod->get_item_sku(), $unit_selling_price);

                $jj = json_decode($json, true);

                # WE DO NOT UPDATE COST HERE
                $prod->set_profit_raw(round($jj["get_profit"], 2));
                $prod->set_margin_raw(round($jj["get_margin"], 2));
            }
        } else {
            // mostly marketplaces orders come here. if via API, interface_so_item_detail doesn't have GST, so we calculate differently
            if (!$platform_id) {
                $platform_id = $this->getDao('So')->get(array("so_no" => $prod->get_so_no()))->get_platform_id();
            }

            if ($platform_id) {
                $this->init_price_service(PLATFORM_TYPE);
                $unit_selling_price = $prod->get_unit_price();
                $json = $this->price_service->get_profit_margin_json($platform_id, $prod->get_item_sku(), $unit_selling_price);
                $jj = json_decode($json, true);

                # WE DO NOT UPDATE COST HERE
                $prod->set_profit_raw(round($jj["get_profit"], 2));
                $prod->set_margin_raw(round($jj["get_margin"], 2));
            }
        }
    }

    public function get_so_ps_srv()
    {
        return $this->so_ps_srv;
    }

    public function process_qoo10_manual_orders($so_no)
    {
        // This function updates so and so_extend db so that update_shipment_status() can run
        $failed = 0;
        $dao = $this->getDao('So');
        $soext_dao = $this->getDao('SoExtend');

        if ($so_obj = $dao->get(array("so_no" => $so_no))) {
            $so_obj->set_biz_type('QOO10');
            if ($dao->update($so_obj) === FALSE) {
                $_SESSION["NOTICE"] = "Error: " . __LINE__ . " " . $dao->db->_error_message();
                $failed = 1;
            }
        } else {
            $_SESSION["NOTICE"] = "Error: " . __LINE__ . " " . $dao->db->_error_message();
            $failed = 1;
        }

        if ($soext_obj = $soext_dao->get(array("so_no" => $so_no))) {
            $soext_obj->set_fulfilled('N');
            if ($soext_dao->update($soext_obj) === FALSE) {
                $_SESSION["NOTICE"] = "Error: " . __LINE__ . " " . $soext_dao->db->_error_message();
                $failed = 1;
            }
        } else {
            $_SESSION["NOTICE"] = "Error: " . __LINE__ . " " . $soext_dao->db->_error_message();
            $failed = 1;
        }

        if ($failed) {
            return FALSE;
        } else {
            return $so_obj;
        }
    }

    public function split_order_to_so($so_no, $order_group)
    {
        $so_dao = $this->getDao('So');
        $soext_dao = $this->getDao('SoExtend');
        $soi_dao = $this->getDao('SoItem');
        $soid_dao = $this->getDao('SoItemDetail');
        $so_payment_status_dao = $this->getDao('SoPaymentStatus');
        $ordernotes_dao = $this->getDao('OrderNotes');
        $so_priority_score_dao = $this->get_so_ps_srv()->get_dao();
        $so_holdreason_dao = $this->getDao('SoHoldReason');

        $input_so_no = $so_no;

        if ($so_obj = $so_dao->get(array("so_no" => $so_no))) {
            if ($so_obj->get_status() == 0 || $so_obj->get_status() > 3 || $so_obj->get_hold_status() != 0 || $so_obj->get_refund_status() != 0) {
                $ret["status"] = FALSE;
                $ret["message"] = __LINE__ . " so_service. Error: <$so_no> Order is inactive/not yet credit checked/allocated/shipped/on hold/has split child/refund.";
                return $ret;
            }

            # If so_no is different from from split_so_group number, means this so_no is already a child of a previous split.
            # If so, get the real parent so_no
            $split_so_group = $so_obj->get_split_so_group();
            if ($split_so_group != $so_no && !empty($split_so_group)) {
                $input_so_obj = $so_obj;
                $so_obj = $so_dao->get(array("so_no" => $so_obj->get_split_so_group()));
                $so_no = $so_obj->get_so_no();
            }

            // compile all the same SKUs for checking qty
            foreach ($order_group as $arr) {
                foreach ($arr as $key => $item) {
                    $checkcount[$item["sku"]][] = $item["sku"];
                }
            }

            // CHECKING total qty of each SKU in the form matches with DB so_item and so_item_detail
            if ($checkcount) {
                foreach ($checkcount as $sku => $countv) {
                    $countqty = count($checkcount[$sku]);

                    // check the total qty of the original so_no that was passed in (not the split parent order's qty)
                    $db_qty = $this->getDao('So')->getSoItemTotalQtyBySku($input_so_no, $sku);
                    if (!$db_qty->prod_sku) {
                        $ret["status"] = FALSE;
                        $ret["message"] = __LINE__ . " so_service. Error: Product doesn't exist for $so_no, sku $sku. ";
                        return $ret;
                    } else {
                        if ($countqty != $db_qty->soi_qty) {
                            $ret["status"] = FALSE;
                            $ret["message"] = __LINE__ . " so_service. Error: <$input_so_no> - <$sku> Database so_item qty <{$db_qty->soi_qty}> does not match total split qty <$countqty>.";
                            return $ret;
                        }
                        if ($countqty != $db_qty->soid_qty) {
                            $ret["status"] = FALSE;
                            $ret["message"] = __LINE__ . " so_service. Error: <$input_so_no> - <$sku> Database so_item_detail qty <{$db_qty->soi_qty}> does not match total split qty <$countqty>.";
                            return $ret;
                        }
                    }
                }
            }
            $id = empty($_SESSION["user"]["id"]) ? "system" : $_SESSION["user"]["id"];
            $ts = date('Y-m-d H:i:s');
            $failed = 0;
            $so_dao->trans_start();
            foreach ($order_group as $group => $arr) {
                // if failed on previous loops, don't bother going in
                if ($failed == 0) {
                    // new so_no for each group
                    $next_val = $so_dao->seq_next_val();
                    $new_so_no = $next_val;
                    $new_so_obj = $so_dao->get();
                    set_value($new_so_obj, $so_obj);

                    $new_so_obj->set_so_no($new_so_no);
                    $new_so_obj->set_split_so_group($so_no);
                    $new_so_obj->set_split_status(2);
                    $new_so_obj->set_split_create_on($ts);
                    $new_so_obj->set_split_create_by($id);

                    // if this parent has been split before, reset hold_status = 0
                    if ($so_obj->get_hold_status() == 15)
                        $new_so_obj->set_hold_status(0);

                    # temporarily set as 0
                    $new_so_obj->set_amount(0.00);
                    $new_so_obj->set_cost(0.00);
                    if ($so_dao->insert($new_so_obj)) {
                        $so_amount = $so_cost = $line_no = 0;

                        foreach ($arr as $v) {
                            // each SKU in the same order here

                            // since we already checked the quantity, we just get the first item in so_item and so_item_detail
                            // products recorded at the same order number should have the same price/cost/margin
                            $sku = $v["sku"];
                            $line_no++;

                            if (!($so_item = $soi_dao->get(array("so_no" => $so_no, "prod_sku" => $sku)))) {
                                $ret["status"] = FALSE;
                                $ret["message"] = __LINE__ . " so_service. Error: SO item list not found for $so_no, sku $sku.";
                                return $ret;
                            }

                            if (!($so_item_detail = $soid_dao->get(array("so_no" => $so_no, "item_sku" => $sku)))) {
                                $ret["status"] = FALSE;
                                $ret["message"] = __LINE__ . " so_service. Error: SO item detail list not found for $so_no, sku $sku.";
                                return $ret;
                            }

                            $unit_vat = number_format(($so_item->get_vat_total() / $so_item->get_qty()), 2, '.', '');
                            $unit_gst = number_format(($so_item->get_gst_total() / $so_item->get_qty()), 2, '.', '');
                            $unit_amount_paid = number_format(($so_item->get_amount() / $so_item->get_qty()), 2, '.', '');
                            $so_amount += ($unit_amount_paid + $unit_gst);     # actual selling price

                            // split order logic splits all SKUs into single item
                            $qty = 1;
                            $unit_cost = $so_item_detail->get_cost();
                            $so_cost += ($unit_cost * $qty);
                            $unit_profit = $so_item_detail->get_profit();
                            $unit_promo_disc_amt = number_format(($so_item_detail->get_promo_disc_amt() / $so_item->get_qty()), 2, '.', '');

                            $unit_profit = $so_item_detail->get_profit();
                            $unit_profit_raw = $so_item_detail->get_profit_raw();

                            $new_soi_obj = $soi_dao->get();
                            set_value($new_soi_obj, $so_item);
                            $new_soi_obj->set_so_no($new_so_no);
                            $new_soi_obj->set_line_no($line_no);
                            $new_soi_obj->set_qty($qty);
                            $new_soi_obj->set_vat_total($unit_vat);
                            $new_soi_obj->set_gst_total($unit_gst);
                            $new_soi_obj->set_amount($unit_amount_paid);

                            if ($soi_dao->insert($new_soi_obj)) {
                                $new_soid_obj = $soid_dao->get();
                                set_value($new_soid_obj, $so_item_detail);
                                $new_soid_obj->set_so_no($new_so_no);
                                $new_soid_obj->set_line_no($line_no);

                                # now each qty has single row, so we take the unit amount in soid as well
                                # normal case: soid.amount = unit amount paid * qty
                                $new_soid_obj->set_amount($unit_amount_paid);
                                $new_soid_obj->set_qty($qty);
                                $new_soid_obj->set_outstanding_qty($qty);
                                $new_soid_obj->set_vat_total($unit_vat);
                                $new_soid_obj->set_gst_total($unit_gst);
                                $new_soid_obj->set_promo_disc_amt($unit_promo_disc_amt);

                                if ($soid_dao->insert($new_soid_obj)) {
                                    // success adding so_item and so_item_detail
                                } else {
                                    $message .= __LINE__ . " so_service. Failed so_item. DB error: " . $soid_dao->db->_error_message() . "\n";
                                    $failed = 1;
                                }
                            } else {
                                $message .= __LINE__ . " so_service. Failed so_item. DB error: " . $soi_dao->db->_error_message() . "\n";
                                $failed = 1;
                            }
                        }

                        // no fail for the whole so group, update amount and cost for this new so
                        if (!$failed) {
                            // check if previous has so_extend. If have, create new so_no with duplicated info
                            if ($soext_obj = $soext_dao->get(array("so_no" => $so_no))) {
                                $new_soext_obj = $soext_dao->get();
                                set_value($new_soext_obj, $soext_obj);
                                $new_soext_obj->set_so_no($new_so_no);

                                if ($soext_dao->insert($new_soext_obj) === FALSE) {
                                    $message .= __LINE__ . " so_service. Failed so_extend. DB error: " . $soext_dao->db->_error_message() . "\n";
                                    $failed = 1;
                                }
                            }

                            // check if so_payment_status available
                            if ($so_payment_status_obj = $so_payment_status_dao->get(array("so_no" => $so_no))) {
                                $new_so_pays_obj = $so_payment_status_dao->get();
                                set_value($new_so_pays_obj, $so_payment_status_obj);
                                $new_so_pays_obj->set_so_no($new_so_no);
                                if ($so_payment_status_dao->insert($new_so_pays_obj)) {
                                    // $order_notes, order_reason - did not add yet because specific to each new order
                                } else {
                                    $message .= __LINE__ . " so_service. Failed so_payment_status. DB error: " . $so_payment_status_dao->db->_error_message() . "\n";
                                    $failed = 1;
                                }
                            }

                            // check if previously has priority score
                            if ($so_priority_score_obj = $so_priority_score_dao->get(array("so_no" => $so_no))) {
                                $new_so_prioritys_obj = $so_priority_score_dao->get();
                                set_value($new_so_prioritys_obj, $so_priority_score_obj);
                                $new_so_prioritys_obj->set_so_no($new_so_no);

                                if ($so_priority_score_dao->insert($new_so_prioritys_obj) === FALSE) {
                                    $message .= __LINE__ . " so_service. Failed so_priority_score. DB error: " . $so_priority_score_dao->db->_error_message() . "\n";
                                    $failed = 1;
                                }
                            }

                            // check if previously has order notes
                            if ($ordernotes_obj = $ordernotes_dao->get_list(array("so_no" => $so_no), array("orderby" => "create_on desc", "limit" => 1))) {
                                $new_ordernotes_obj = $ordernotes_dao->get();
                                set_value($new_ordernotes_obj, $ordernotes_obj);
                                $new_ordernotes_obj->set_so_no($new_so_no);

                                if ($ordernotes_dao->insert($new_ordernotes_obj) === FALSE) {
                                    $message .= __LINE__ . " so_service. Failed order_notes. DB error: " . $ordernotes_dao->db->_error_message() . "\n";
                                    $failed = 1;
                                }
                            }

                            if (!$failed) {
                                // all done for this group, update amount and cost for the new so
                                $new_so_obj->set_amount($so_amount);
                                $new_so_obj->set_cost($so_cost);
                                $new_so_obj->set_split_create_on($ts);

                                if ($so_dao->update($new_so_obj)) {
                                    $addedsolist .= "$next_val, ";
                                    $so_dao->update_seq($next_val);

                                } else {
                                    $message .= __LINE__ . " so_service. Failed new so update so_no $so_no. DB error: " . $so_dao->db->_error_message() . "\n";
                                    $failed = 1;
                                }
                            }
                        }

                    } else {
                        $message .= __LINE__ . " so_service. Failed so. DB error: " . $so_dao->db->_error_message() . "\n";
                        $failed = 1;
                    }
                }
            }

            if (!$failed) {
                // all the groups have been procesed, update the parent so.status
                $so_obj->set_hold_status(15);  # set to has split child
                $so_obj->set_split_status(2);
                $so_obj->set_split_create_by($id);
                $so_obj->set_split_create_on($ts);
                if ($so_dao->update($so_obj) === FALSE) {
                    $message .= __LINE__ . " so_service. Failed update parent so $so_no. DB error: " . $so_dao->db->_error_message() . "\n";
                    $failed = 1;
                } else {
                    $sohr_vo = $so_holdreason_dao->get();
                    $sohr_vo->set_so_no($so_no);
                    $sohr_vo->set_reason("created_split");
                    $this->getDao('SoHoldReason')->insert($sohr_vo);
                }

                if (isset($input_so_obj)) {
                    // if we are splitting a child, then we set the child as inactive
                    $input_so_obj->set_status(0);
                    if ($so_dao->update($input_so_obj) === FALSE) {
                        $message .= __LINE__ . " so_service. Failed update original input so_no {$input_so_obj->get_so_no()}. DB error: " . $so_dao->db->_error_message() . "\n";
                        $failed = 1;
                    }
                }
            }

            if ($failed) {
                $so_dao->trans_rollback();
                $so_dao->trans_complete();

                $ret["status"] = FALSE;
                $ret["message"] = $message;
            } else {
                $so_dao->trans_complete();
                $ret["status"] = TRUE;
                $ret["message"] = "Success. Added so_no: $addedsolist";
            }
        } else {
            $ret["status"] = FALSE;
            $ret["message"] = __LINE__ . "so_service. SO not found for $so_no.";
        }
        return $ret;
    }

    public function get_refundable_list($where = array(), $option = array())
    {
        if ($option["num_row"] != "") {
            return $this->getDao('So')->getRefundableOrder($where, array("num_row" => 1, "create" => $option["create"]));
        } else {
            return $this->getDao('So')->getRefundableOrder($where, $option);
        }
    }

    public function checkIfPacked($so_no = "")
    {
        return $this->getDao('SoAllocate')->getList(array("so_no" => $so_no, "status <" => "3", "status >" => "0"));
    }

    public function getShipNoList($type = "object", $service = "")
    {
        return $this->getDao('SoShipment')->getShNoList($type, $service);
    }

    public function get_refund_item_w_name($where = array())
    {
        return $this->getDao('SoItemDetail')->getListWithProdname($where);
    }

    public function getDispatchData($where = array(), $from_date = '', $to_date = '')
    {
        return $this->getDao('So')->getDispatchData($where, $from_date, $to_date);
    }

    public function getItemsWithName($where = array(), $option = array(), $dto = "")
    {
        return $this->getDao('SoItem')->getItemsWithName($where, $option, $dto);
    }

    public function get_print_invoice_content($so_no_list = array(), $gen_pdf = 0, $lang_id = "")
    {
        $run = 0;
        $website_domain = $this->getDao('Config')->valueOf('website_domain');
        $website_domain = base_url();
        $total_cnt = count($so_no_list);
        $cursign_arr = $this->getDao('Currency')->getList([], ["limit" => -1]);
        # sbf #3746 don't include complementary accessory on front end
        $ca_catid_arr = implode(',', $this->get_ca_service()->get_accessory_catid_arr());

        if (count($so_no_list)) {
            $valid = 0;
            $content = "";

            foreach ($so_no_list as $obj) {
                $run++;
                $so_obj = $this->getDao('So')->get(array("so_no" => $obj));

                if (!$so_obj) {
                    continue;
                } else {
                    $cur_platform_id = $so_obj->get_platform_id();
                    $pbv_obj = $this->getDao('PlatformBizVar')->get(array("selling_platform_id" => $cur_platform_id));
                    if (empty($lang_id)) {
                        $lang_id = $pbv_obj->get_language_id();
                    }

                    $replace = array();

                    // get language template
                    include_once(APPPATH . "hooks/country_selection.php");
                    $country_id = $pbv_obj->get_platform_country_id();

                    $replace = array_merge($replace, Country_selection::get_template_require_text($lang_id, $country_id));

                    if (file_exists(APPPATH . "language/template_service/" . $lang_id . "/customer_invoice.ini")) {
                        $data_arr = parse_ini_file(APPPATH . "language/template_service/" . $lang_id . "/customer_invoice.ini");
                    }
                    if (!is_null($data_arr)) {
                        $replace = array_merge($replace, $data_arr);
                    }

#                   SBF #2960 Add NIF/CIF to invoice if info was supplied
#                   SBF #4330 also for IT page
                    $client_obj = $this->getDao('Client')->get(array("id" => $so_obj->get_client_id()));
                    $client_id_no = $client_obj->get_client_id_no();
                    $replace["company_name"] = $client_obj->get_companyname();
                    if (($cur_platform_id == "WEBES" || $cur_platform_id == "WEBIT") && $client_id_no) {
                        $replace["client_id_no"] = <<<html
                        <p>$client_id_no</p>
                        <p></p>
html;

                    } else {
                        $replace["label_client_id_no"] = "";
                        $replace["client_id_no"] = "";
                    }

                    $tpl_id = "shipped_invoice";
                    include_once(APPPATH . "libraries/service/Template_service.php");
                    $tpl_srv = new Template_service();
                    $tpl_obj = $tpl_srv->get_msg_tpl_w_att(array("id" => $tpl_id, "lang_id" => $lang_id, "platform_id" => $cur_platform_id), $replace);

                    if ($tpl_obj != "") {
                        $html = $tpl_obj->template->get_message();
                    } else {
                        $html = "";
                    }

                    switch ($cur_platform_id) {
                        case "AMUS":
                            $replace["isAmazon"] = 1;
                            $replace["sales_email"] = "amazoncentral@valuebasket.com";
                            $replace["csemail"] = "amazoncentral@valuebasket.com";
                            $replace["return_email"] = "returns@valuebasket.com";
                            break;
                        case "AMDE":
                        case "AMFR":
                        case "AMUK":
                            $replace["isAmazon"] = 1;
                            $replace["sales_email"] = "amazoncentral@valuebasket.com";
                            $replace["csemail"] = "amazoncentral@valuebasket.com";
                            $replace["return_email"] = "returns@valuebasket.com";
                            break;
                        default:
                            $replace["isAmazon"] = 0;
                            $replace["sales_email"] = $this->get_sales_email($lang_id);
                            $replace["csemail"] = $this->get_cs_support_email($lang_id);
                            $replace["return_email"] = $this->get_return_email($lang_id);
                            break;
                    }

                    $itemlist = $this->getDao('SoItem')->getItemsWithName(array("so_no" => $obj, "p.cat_id NOT IN ($ca_catid_arr)" => NULL));
                    $so_ext_obj = $this->getDao('SoExtend')->get(array("so_no" => $obj));

                    $replace["website_domain"] = base_url();
                    $replace["cursign"] = $cursign = $cursign_arr[$so_obj->get_currency_id()];

                    if ($so_obj->get_split_so_group())
                        $new_so_no = $so_obj->get_split_so_group() . "/" . $so_obj->get_so_no();
                    else
                        $new_so_no = $so_obj->get_so_no();

                    $replace["order_no"] = $so_obj->get_client_id() . "-" . $new_so_no;
                    $replace["amazon_order_no"] = $so_obj->get_platform_order_id();
                    $replace["order_date"] = date("d/m/Y", strtotime($so_obj->get_order_create_date()));
                    $bcountry_obj = $this->getDao('Country')->get(array("id" => $so_obj->get_bill_country_id()));
                    list($bill_addr_1, $bill_addr_2, $bill_addr_3) = explode("|", $so_obj->get_bill_address());
                    $bstatezip = trim($so_obj->get_bill_state() . ", " . $so_obj->get_bill_postcode());
                    if ($bstatezip != ",") {
                        $bstatezip = ereg_replace("^, ", "", $bstatezip);
                        $bstatezip = ereg_replace(",$", "", $bstatezip) . "<br>";
                    } else {
                        $bstatezip = "";
                    }
                    $replace["billing_name"] = $so_obj->get_bill_name();
                    $replace["billing_address"] = ($so_obj->get_bill_company() == "" ? "" : $so_obj->get_bill_company() . "<br/>") . $bill_addr_1 . "<br/>" . ($bill_addr_2 == "" ? "" : $bill_addr_2 . "<br/>") . ($bill_addr_3 == "" ? "" : $bill_addr_3 . "<br/>") . $so_obj->get_bill_city() . "<br>" . $bstatezip . $bcountry_obj->get_name();
                    list($delivery_addr_1, $delivery_addr_2, $delivery_addr_3) = explode("|", $so_obj->get_delivery_address());
                    $dcountry_obj = $this->getDao('Country')->get(array("id" => $so_obj->get_delivery_country_id()));
                    $dstatezip = trim($so_obj->get_delivery_state() . ", " . $so_obj->get_delivery_postcode());
                    if ($dstatezip != ",") {
                        $dstatezip = ereg_replace("^, ", "", $dstatezip);
                        $dstatezip = ereg_replace(",$", "", $dstatezip) . "<br>";
                    } else {
                        $dstatezip = "";
                    }
                    $replace["delivery_name"] = $so_obj->get_delivery_name();
                    $replace["delivery_address"] = ($so_obj->get_delivery_company() == "" ? "" : $so_obj->get_delivery_company() . "<br/>") . $delivery_addr_1 . "<br/>" . ($delivery_addr_2 == "" ? "" : $delivery_addr_2 . "<br/>") . ($delivery_addr_3 == "" ? "" : $delivery_addr_3 . "<br/>") . $so_obj->get_delivery_city() . "<br>" . $dstatezip . $dcountry_obj->get_name();

                    $item_information = "";
                    $bvat = 0;
                    $vat = 0;
                    $sum = 0;
                    switch ($country_id) {
                        case 'ES':
                            $warrantyname = "Garantía";
                            $warrantymonth = " Meses";
                            break;

                        case 'PL':
                            $warrantyname = "Gwarancja";
                            $warrantymonth = " Miesięcy";
                            break;

                        case 'IT':
                            $warrantyname = "Garanzia";
                            $warrantymonth = " Mesi";
                            break;

                        case 'FR':
                            $warrantyname = "Garantie";
                            $warrantymonth = " Mois";
                            break;

                        default:
                            $warrantyname = "Warranty";
                            $warrantymonth = " Months";
                            break;
                    }
                    foreach ($itemlist as $item_obj) {

                        $prod_obj = $this->getDao('Product')->get(array("sku" => $item_obj->get_main_prod_sku()));
                        $amount_total = $item_obj->get_amount();
                        $vat_total = $item_obj->get_vat_total();
                        $amount_total_bvat = $amount_total - $vat_total;
                        $unit_price_bvat = round(($amount_total - $vat_total) / $item_obj->get_qty(), 2);
                        $tmp = $this->getDao('Product')->get(array("sku" => $item_obj->get_main_prod_sku()));
                        $imagepath = get_image_file($tmp->get_image(), 's', $tmp->get_sku());
                        $width_col_1 = $width_col_2 = "";
                        $warranty_month = $item_obj->get_warranty_in_month();
                        if ($warranty_month == null) {
                            $warranty_month = "None";
                        } else {
                            $warranty_month .= $warrantymonth;
                        }
                        if ($gen_pdf) {
                            $width_col_1 = 'width="80px"';
                            $width_col_2 = 'width="250px"';
                        }

                        $item_information .= '<tr>
                                                <td ' . $width_col_1 . ' align="center"><img src="' . $imagepath . '"></td>
                                                <td ' . $width_col_2 . ' align="left">' . $item_obj->get_prod_sku() . ' - ' . $item_obj->get_name() . '<br/><br/>' . $warrantyname . ': ' . $warranty_month . '</td>
                                                <td align="right">' . platform_curr_format($cur_platform_id, $item_obj->get_unit_price()) . '</td>
                                                <td align="right">' . $item_obj->get_qty() . '</td>
                                                <td align="right"><b>' . platform_curr_format($cur_platform_id, $amount_total) . '</b></td>
                                            </tr>';
                        $bvat += $amount_total_bvat;
                        $vat += $vat_total;
                        $sum += $amount_total;
                    }
                    $replace["item_information"] = $item_information;

                    $sum_total = platform_curr_round($cur_platform_id, $sum);
                    $sum_vat = platform_curr_round($cur_platform_id, $vat);

                    $sum_bvat = platform_curr_round($cur_platform_id, $bvat);
                    $replace["sum_total"] = platform_curr_format($cur_platform_id, $sum_total);
                    $replace["sum_vat"] = platform_curr_format($cur_platform_id, $sum_vat);
                    $replace["sum_bvat"] = platform_curr_format($cur_platform_id, $sum_bvat);

                    $sid_bvat = "";
                    $sid_vat = "";
                    $sid = $so_obj->get_delivery_charge();
                    if ($so_ext_obj && $so_ext_obj->get_vatexempt() == "0") {
                        $sid_vat = $sid * $so_obj->get_vat_percent() / (100 + $so_obj->get_vat_percent());

                    } else {
                        $sid_vat = 0;
                    }
                    $sid = platform_curr_round($cur_platform_id, $sid);
                    $sid_vat = platform_curr_round($cur_platform_id, $sid_vat);
                    $sid_bvat = platform_curr_round($cur_platform_id, $sid - $sid_vat);
                    $replace["currency"] = $so_obj->get_currency_id();
                    $replace["promotion_code"] = $so_obj->get_promotion_code();
                    $replace["sid"] = platform_curr_format($cur_platform_id, $sid);
                    $replace["sid_vat"] = platform_curr_format($cur_platform_id, $sid_vat);
                    $replace["sid_bvat"] = platform_curr_format($cur_platform_id, $sid_bvat);

                    $ofee = $ofee_vat = $ofee_bvat = 0;
                    $extobj = $this->getDao('SoExtend')->get(array("so_no" => $so_obj->get_so_no()));
                    if ($extobj) {
                        if ($extobj->get_offline_fee() > 0) {
                            $replace["offline_fee"] = '<tr>
                                <td colspan="2">&nbsp;</td>
                                <td colspan="2" align="right" bgcolor="#DDDDDD" style="border:1px solid #BBBBBB; border-width:0px 0px 1px 1px;"><b>' . $replace["offline_fee"] . '</b></td>
                                <td align="right" bgcolor="#F0F0F0" valign="top" style="border:1px solid #BBBBBB; border-width:0px 1px 1px 1px;"><b>' . platform_curr_format($cur_platform_id, $extobj->get_offline_fee()) . '</b></td>
                            </tr>';
                            $ofee = platform_curr_round($cur_platform_id, $extobj->get_offline_fee());
                            $ofee_vat = platform_curr_round($cur_platform_id, $ofee * $so_obj->get_vat_percent() / (100 + $so_obj->get_vat_percent()));
                            $ofee_bvat = platform_curr_round($cur_platform_id, $ofee - $ofee_vat);
                        } else if ($extobj->get_offline_fee() < 0) {
                            $replace["offline_fee"] = '<tr>
                                <td colspan="2">&nbsp;</td>
                                <td colspan="2" align="right" bgcolor="#DDDDDD" style="border:1px solid #BBBBBB; border-width:0px 0px 1px 1px;"><b>' . $replace["discount"] . '</b></td>
                                <td align="right" bgcolor="#F0F0F0" valign="top" style="border:1px solid #BBBBBB; border-width:0px 1px 1px 1px;"><b>' . platform_curr_format($cur_platform_id, $extobj->get_offline_fee()) . '</b></td>
                            </tr>';
                            $ofee = platform_curr_round($cur_platform_id, $extobj->get_offline_fee());
                            $ofee_vat = platform_curr_round($cur_platform_id, $ofee * $so_obj->get_vat_percent() / (100 + $so_obj->get_vat_percent()));
                            $ofee_bvat = platform_curr_round($cur_platform_id, $ofee - $ofee_vat);
                        } else {
                            $replace["offline_fee"] = "";
                        }
                        //#2182 add the processing fee
                        $processing_fee = $extobj->get_offline_fee();
                    }
                    if (is_null($processing_fee)) {
                        $processing_fee = 0;
                    }

                    $replace["processing_fee"] = platform_curr_format($cur_platform_id, $processing_fee);
                    $replace["total"] = $so_obj->get_amount();
                    $replace["total_vat"] = platform_curr_round($cur_platform_id, $replace["total"] * $so_obj->get_vat_percent() / (100 + $so_obj->get_vat_percent()));
                    $replace["total_bvat"] = platform_curr_round($cur_platform_id, $replace["total"] - $replace["total_vat"]);
                    if (!$replace["payment_method"] = $this->get_so_payment_gateway($so_obj->get_so_no())) {
                        $replace["payment_method"] = "N/A";
                    }
                    //#2182 add the processing fee to the total, also add the delivery charge
                    $replace["grand_total"] = platform_curr_format($cur_platform_id, $so_obj->get_amount());

                    if ($gen_pdf) {
                        $body_file = "customer_invoice_body_pdf.html";
                    } else {
                        $body_file = "customer_invoice_body.html";
                    }

                    // SBF #4682 - Added by jerry to move template into template management in SBF
                    if ($html != "") {
                        $msgcheck = 1;
                        // replace contents in template
                        foreach ($replace as $rskey => $rsvalue) {
                            $search[] = "[:" . $rskey . ":]";
                            $value[] = $rsvalue;
                        }
                        $temp .= str_replace($search, $value, $html);

                        if ($run < $total_cnt) {
                            $temp .= $pagebreak;
                        }

                        unset($replace);
                        unset($search);
                        unset($value);
                        $valid++;

                    } else {
                        $temp = @file_get_contents(APPPATH . $this->getDao('Config')->valueOf("tpl_path") . "customer_invoice/" . $body_file);

                        // replace contents in template
                        foreach ($replace as $rskey => $rsvalue) {
                            $search[] = "[:" . $rskey . ":]";
                            $value[] = $rsvalue;
                        }
                        $content .= str_replace($search, $value, $temp);

                        if ($run < $total_cnt) {
                            $content .= $pagebreak;
                        }

                        unset($replace);
                        unset($search);
                        unset($value);
                        $valid++;
                    }


                }
            }

            if ($valid) {
                if ($msgcheck == 1) {
                    return $temp;

                } else {
                    $header = @file_get_contents(APPPATH . $this->getDao('Config')->valueOf("tpl_path") . "customer_invoice/customer_invoice_header.html");
                    $footer = @file_get_contents(APPPATH . $this->getDao('Config')->valueOf("tpl_path") . "customer_invoice/customer_invoice_footer.html");
                    // echo $header.$content.$footer;die();
                    return $header . $content . $footer;
                }

            } else {
                return FALSE;
            }
        }
        return FALSE;
    }

    public function get_sales_email($lang_id)
    {
        switch ($lang_id) {
            default:
                $email = "no-reply@valuebasket.com";
                break;
        }
        return $email;
    }

    public function get_cs_support_email($lang_id)
    {
        switch ($lang_id) {
            default:
                $email = "no-reply@valuebasket.com";
                break;
        }
        return $email;
    }

    public function get_return_email($lang_id)
    {
        switch ($lang_id) {
            default:
                $email = "no-reply@valuebasket.com";
                break;
        }
        return $email;
    }

    public function get_so_payment_gateway($so_no = '')
    {
        if ($so_no != '') {
            if ($sops_obj = $this->getDao('SoPaymentStatus')->get(array('so_no' => $so_no))) {
                if ($pg_obj = $this->get_payment_gateway_dao()->get(array('id' => $sops_obj->get_payment_gateway_id()))) {
                    $payment_gateway = (is_null($pg_obj->get_name()) ? '' : $pg_obj->get_name());
                    return $payment_gateway;
                }
            }
        }

        return '';
    }

    public function get_payment_gateway_dao()
    {
        return $this->payment_gateway_dao;
    }

    public function set_payment_gateway_dao($dao)
    {
        $this->payment_gateway_dao = $dao;
        return $dao;
    }

    public function fire_preorder_delay_email($so_no)
    {
        $so_obj = $this->getDao('So')->get(array("so_no" => $so_no));
        $client = $this->getDao('Client')->get(array("id" => $so_obj->get_client_id()));

        # sbf #3746 don't include complementary accessory on front end
        $ca_catid_arr = implode(',', $this->get_ca_service()->get_accessory_catid_arr());

        $platform_id = $so_obj->get_platform_id();
        $pbv = $this->getDao('PlatformBizVar')->get(array("selling_platform_id" => $platform_id));
        $lang_id = $pbv->get_language_id();
        $so_items = $this->getDao('SoItem')->getItemsWithName(array("so_no" => $so_obj->get_so_no(), "p.cat_id NOT IN ($ca_catid_arr)" => NULL), array("lang_id" => $lang_id));

        $old_edd = $so_obj->get_expect_delivery_date();
        $new_edd = "";
        foreach ($so_items as $item_obj) {
            $prod_obj = $this->getDao('Product')->get(array("sku" => $item_obj->get_main_prod_sku()));
            $new_edd = $prod_obj->get_expected_delivery_date();
            $prod_name .= $item_obj->get_name();
        }

        $email_subject = "[VB] preorder delay email alert";
        $headers .= 'From: Admin <admin@valuebasket.com>' . "\r\n";
        if ($old_edd == $new_edd) {
//alert
            $message = "User sending wrong delay order, so_no:" . $so_obj->get_so_no();
            $message .= ", old:" . $old_edd . "= new:" . $new_edd;
            mail("oswald-alert@eservicesgroup.com", $email_subject, $message, $headers);
        } else if ($new_edd == "") {
//alert
            $message = "User sending unexpected delay order email, so_no:" . $so_obj->get_so_no();
            $message .= ", there is no new:" . $new_edd;
            $message .= ", please check the new product EDD";
            mail("oswald-alert@eservicesgroup.com", $email_subject, $message, $headers);
        } else if (strtotime($old_edd) > strtotime($new_edd)) {
//EDD is earlier, alert
            $message = "User sending wrong delay order, so_no:" . $so_obj->get_so_no();
            $message .= ", old:" . $old_edd . "> new:" . $new_edd;
            $message .= ", please check the new product EDD";
            mail("oswald-alert@eservicesgroup.com", $email_subject, $message, $headers);
        } else {
            $this->include_dto("Event_email_dto");
            $dto = new Event_email_dto();
            $dto->set_event_id("preorder_delay");
            $dto->set_tpl_id("preorder_delay");
            $dto->set_lang_id($lang_id);

            $replace["forename"] = $client->get_forename();
            $replace["so_items_pre_order"] = $prod_name;
            $replace["expect_delivery_date"] = $new_edd;
            $replace["client_id"] = $so_obj->get_client_id();
            $replace["so_no"] = $so_obj->get_so_no();

            include_once(APPPATH . "hooks/country_selection.php");
            $country_id = strtolower($pbv->get_platform_country_id());
            $replace["site_url"] = Country_selection::rewrite_domain_by_country("www.valuebaset.com", $country_id);;
            $replace["site_name"] = Country_selection::rewrite_site_name($replace["site_url"]);
            $replace["image_url"] = $this->getDao('Config')->valueOf("default_url");
            $replace["logo_file_name"] = $this->getDao('Config')->valueOf("logo_file_name");

            $dto->set_replace($replace);
            $dto->set_mail_to($client->get_email());
            $this->eventService->fireEvent($dto);
        }
    }

    public function get_preorder_list($where = array(), $option = array())
    {
        return $this->get_so_dao()->get_preorder_list($where, $option);
    }

    public function get_delivery_note_content($so_no_list = array())
    {
        $content = "";
        if ($so_no_list) {
            include_once(APPPATH . "libraries/service/Template_service.php");
            $tpl_srv = new Template_service();
            $tpl_id = "delivery_note";
            $content .= @file_get_contents(APPPATH . $this->getDao('Config')->valueOf("tpl_path") . $tpl_id . "/" . $tpl_id . "_header.html");

            foreach ($so_no_list as $so_no) {
                if ($so_obj = $this->get(array("so_no" => $so_no))) {
                    $cur_platform_id = $so_obj->get_platform_id();
                    if (!isset($ar_pbv_obj[$cur_platform_id])) {
                        $ar_pbv_obj[$cur_platform_id] = $this->getDao('PlatformBizVar')->get(array("selling_platform_id" => $cur_platform_id));
                    }

                    if ($pbv_obj = $ar_pbv_obj[$cur_platform_id]) {
                        $replace = array();

                        $cur_lang_id = $pbv_obj->get_language_id();
                        if (!isset($ar_lang[$cur_lang_id])) {
                            include_once APPPATH . "language/ORD001001_" . $cur_lang_id . ".php";
                            $ar_lang[$cur_lang_id] = $lang;
                        }

                        if ($so_obj->get_split_so_group())
                            $new_so_no = $so_obj->get_split_so_group() . "/$so_no";
                        else
                            $new_so_no = $so_no;

                        $replace["so_no"] = $new_so_no;
                        $replace["client_id"] = $so_obj->get_client_id();
                        $replace["order_create_date"] = date("d/m/Y", strtotime($so_obj->get_order_create_date()));
                        $replace["delivery_name"] = $so_obj->get_delivery_name();
                        $country = $this->getDao('Country')->get(array("id" => $so_obj->get_delivery_country_id()));
                        $billing_country = $this->getDao('Country')->get(array("id" => $so_obj->get_bill_country_id()));
                        $replace["delivery_address_text"] = ($so_obj->get_delivery_company() ? $so_obj->get_delivery_company() . "\n" : "") . trim(str_replace("|", "\n", $so_obj->get_delivery_address())) . "\n" . $so_obj->get_delivery_city() . " " . $so_obj->get_delivery_state() . " " . $so_obj->get_delivery_postcode() . "\n" . $country->get_name();
                        $replace["delivery_address"] = nl2br($replace["delivery_address_text"]);
                        $replace["billing_name"] = $so_obj->get_bill_name();
                        $replace["billing_address_text"] = ($so_obj->get_bill_company() ? $so_obj->get_bill_company() . "\n" : "") . trim(str_replace("|", "\n", $so_obj->get_bill_address())) . "\n" . $so_obj->get_bill_city() . " " . $so_obj->get_bill_state() . " " . $so_obj->get_bill_postcode() . "\n" . $billing_country->get_name();
                        $replace["billing_address"] = nl2br($replace["billing_address_text"]);

                        $replace["lang_order_no"] = $ar_lang[$cur_lang_id]["order_no"];
                        $replace["lang_order_date"] = $ar_lang[$cur_lang_id]["order_date"];
                        $replace["lang_ship_to"] = $ar_lang[$cur_lang_id]["ship_to"];
                        $replace["lang_bill_to"] = $ar_lang[$cur_lang_id]["bill_to"];
                        $replace["lang_order_details"] = $ar_lang[$cur_lang_id]["order_details"];
                        $replace["lang_description"] = $ar_lang[$cur_lang_id]["description"];
                        $replace["lang_qty"] = $ar_lang[$cur_lang_id]["qty"];
                        $replace["lang_thank_you"] = $ar_lang[$cur_lang_id]["thank_you"];
                        $replace["lang_need_assistance"] = $ar_lang[$cur_lang_id]["need_assistance"];
                        $replace["lang_our_return_policy"] = $ar_lang[$cur_lang_id]["our_return_policy"];
                        $replace["lang_return_policy_part1"] = $ar_lang[$cur_lang_id]["return_policy_part1"];
                        $replace["lang_return_policy_part2"] = $ar_lang[$cur_lang_id]["return_policy_part2"];
                        $replace["return_email"] = $this->get_return_email($cur_lang_id);
                        $replace["cs_support_email"] = $this->get_cs_support_email($cur_lang_id);

                        # sbf #3746 don't include complementary accessory on front end
                        $ca_catid_arr = implode(',', $this->get_ca_service()->get_accessory_catid_arr());
                        $option["show_ca"] = 1; # 4404 - show CA on delivery note only

                        if ($itemlist = $this->getDao('SoItem')->getItemsWithName(array("so_no" => $so_no), $option)) {
                            foreach ($itemlist as $item_obj) {
                                $tmp = $this->getDao('Product')->get(array("sku" => $item_obj->get_main_prod_sku()));
                                $imagepath = base_url() . get_image_file($tmp->get_image(), 's', $tmp->get_sku());
                                $replace["so_items"] .= "
                    <tr>
                        <td align='center'><img src='{$imagepath}'></td>
                        <td valign=top>{$item_obj->get_prod_sku()} - {$item_obj->get_name()}</td>
                        <td valign=top>{$item_obj->get_qty()}</td>
                    </tr>";
                            }
                        }
                        $replace["barcode"] = "<img src='" . base_url() . "order/integrated_order_fulfillment/get_barcode/$so_no' style='float:right'>";
                        if ($tpl_obj = $tpl_srv->get_msg_tpl_w_att(array("id" => $tpl_id, "lang_id" => $cur_lang_id), $replace)) {
                            $content .= $tpl_obj->template->get_message();
                        }
                    }
                }
            }
            $content .= @file_get_contents(APPPATH . $this->getDao('Config')->valueOf("tpl_path") . $tpl_id . "/" . $tpl_id . "_footer.html");
        }

        return $content;
    }

    public function get_order_packing_slip_content($so_no_list = array())
    {
        $content = "";
        if ($so_no_list) {
            include_once(APPPATH . "libraries/service/Template_service.php");
            $tpl_srv = new Template_service();
            $tpl_id = "order_packing_slip";
            $content .= @file_get_contents(APPPATH . $this->getDao('Config')->valueOf("tpl_path") . $tpl_id . "/" . $tpl_id . "_header.html");

            foreach ($so_no_list as $so_no) {
                if ($so_obj = $this->get(array("so_no" => $so_no))) {
                    $cur_platform_id = $so_obj->get_platform_id();
                    if (!isset($ar_pbv_obj[$cur_platform_id])) {
                        $ar_pbv_obj[$cur_platform_id] = $this->getDao('PlatformBizVar')->get(array("selling_platform_id" => $cur_platform_id));
                    }

                    if ($pbv_obj = $ar_pbv_obj[$cur_platform_id]) {
                        $replace = array();

                        $cur_lang_id = 'en';
                        if (!isset($ar_lang[$cur_lang_id])) {
                            include_once APPPATH . "language/ORD001001_" . $cur_lang_id . ".php";
                            $ar_lang[$cur_lang_id] = $lang;
                        }

                        if ($so_obj->get_split_so_group())
                            $new_so_no = $so_obj->get_split_so_group() . "/$so_no";
                        else
                            $new_so_no = $so_no;

                        $replace["so_no"] = $new_so_no;
                        $replace["client_id"] = $so_obj->get_client_id();
                        $replace["order_create_date"] = date("d/m/Y", strtotime($so_obj->get_order_create_date()));
                        $replace["delivery_name"] = $so_obj->get_delivery_name();
                        $country = $this->getDao('Country')->get(array("id" => $so_obj->get_delivery_country_id()));
                        $billing_country = $this->getDao('Country')->get(array("id" => $so_obj->get_bill_country_id()));
                        $replace["delivery_address_text"] = ($so_obj->get_delivery_company() ? $so_obj->get_delivery_company() . "\n" : "") . trim(str_replace("|", "\n", $so_obj->get_delivery_address())) . "\n" . $so_obj->get_delivery_city() . " " . $so_obj->get_delivery_state() . " " . $so_obj->get_delivery_postcode() . "\n" . $country->get_name();
                        $replace["delivery_address"] = nl2br($replace["delivery_address_text"]);
                        $replace["billing_name"] = $so_obj->get_bill_name();
                        $replace["billing_address_text"] = ($so_obj->get_bill_company() ? $so_obj->get_bill_company() . "\n" : "") . trim(str_replace("|", "\n", $so_obj->get_bill_address())) . "\n" . $so_obj->get_bill_city() . " " . $so_obj->get_bill_state() . " " . $so_obj->get_bill_postcode() . "\n" . $billing_country->get_name();
                        $replace["billing_address"] = nl2br($replace["billing_address_text"]);

                        $replace["lang_order_no"] = $ar_lang[$cur_lang_id]["order_no"];
                        $replace["lang_order_date"] = $ar_lang[$cur_lang_id]["order_date"];
                        $replace["lang_ship_to"] = $ar_lang[$cur_lang_id]["ship_to"];
                        $replace["lang_bill_to"] = $ar_lang[$cur_lang_id]["bill_to"];
                        $replace["lang_order_details"] = $ar_lang[$cur_lang_id]["order_details"];
                        $replace["lang_description"] = $ar_lang[$cur_lang_id]["description"];
                        $replace["lang_qty"] = $ar_lang[$cur_lang_id]["qty"];
                        $replace["lang_thank_you"] = $ar_lang[$cur_lang_id]["thank_you"];
                        $replace["lang_need_assistance"] = $ar_lang[$cur_lang_id]["need_assistance"];
                        $replace["lang_our_return_policy"] = $ar_lang[$cur_lang_id]["our_return_policy"];
                        $replace["lang_return_policy_part1"] = $ar_lang[$cur_lang_id]["return_policy_part1"];
                        $replace["lang_return_policy_part2"] = $ar_lang[$cur_lang_id]["return_policy_part2"];
                        $replace["return_email"] = $this->get_return_email($cur_lang_id);
                        $replace["cs_support_email"] = $this->get_cs_support_email($cur_lang_id);

                        # also include complementary accessory
                        if ($itemlist = $this->getDao('SoItemDetail')->getListWithProdname(array("so_no" => $so_no))) {
                            $no_show_sku = array('15772-AA-BK', '15772-AA-WH'); //those sku will not show on packing slip

                            foreach ($itemlist as $item_obj) {
                                $item_obj_sku = $item_obj->get_item_sku();
                                if (in_array($item_obj_sku, $no_show_sku) === false) {
                                    $tmp = $this->getDao('Product')->get(array("sku" => $item_obj->get_item_sku()));
                                    $imagepath = base_url() . get_image_file($tmp->get_image(), 's', $tmp->get_sku());
                                    $replace["so_items"] .= "
                        <tr>
                            <td align='center'><img src='{$imagepath}'></td>
                            <td valign=top style='font-size:20px'>{$item_obj->get_item_sku()} - {$item_obj->get_name()}</td>
                            <td valign=top style='font-size:20px'>{$item_obj->get_qty()}</td>
                            <td></td>
                            <td></td>
                        </tr>";
                                }

                            }
                        }
                        $replace["barcode"] = "<img src='" . base_url() . "order/integrated_order_fulfillment/get_barcode/$so_no' style='float:right'>";
                        if ($tpl_obj = $tpl_srv->get_msg_tpl_w_att(array("id" => $tpl_id, "lang_id" => $cur_lang_id), $replace)) {
                            $content .= $tpl_obj->template->get_message();
                        }
                    }
                }
            }
            $content .= @file_get_contents(APPPATH . $this->getDao('Config')->valueOf("tpl_path") . $tpl_id . "/" . $tpl_id . "_footer.html");
        }

        return $content;
    }

    public function get_custom_invoice_content($so_no_list = array(), $new_shipper_name = "", $currency = "")
    {
        $so_lang_arr = array("AMUK" => "en", "WSGB" => "en", "AMFR" => "en", "AMDE" => "en", "AMUS" => "en");
        $run = 0;
        $website_domain = $this->getDao('Config')->valueOf('website_domain');
        $total_cnt = count($so_no_list);
        $cursign_arr = array("GBP" => "GBP", "EUR" => "GBP");

        # sbf #3746 don't include complementary accessory on front end
        $ca_catid_arr = implode(',', $this->get_ca_service()->get_accessory_catid_arr());

        if (count($so_no_list)) {
            $valid = 0;
            $content = "";
            include_once APPPATH . "data/custom_invoice.php";
            $clean_body = $body;
            foreach ($so_no_list as $obj) {
                $sum = 0;

                $run++;
                $so_obj = $this->getDao('So')->get(array("so_no" => $obj));
                if (!$so_obj) {
                    continue;
                } else {
                    $data = array();
                    $itemlist = $this->getDao('SoItem')->getItemsWithName(array("so_no" => $obj, "p.cat_id NOT IN ($ca_catid_arr)" => NULL));

                    $client_obj = $this->getDao('Client')->get(array("id" => $so_obj->get_client_id()));

                    for ($i = 1; $i < 7; $i++) {
                        ${"daddr_" . $i} = "&nbsp;";
                    }

                    if ($so_obj->get_split_so_group())
                        $new_so_no = $so_obj->get_split_so_group() . "/" . $so_obj->get_so_no();
                    else
                        $new_so_no = $so_obj->get_so_no();

                    $data["date_of_invoice"] = date("d/m/Y");
                    $data["shipper_name"] = $new_shipper_name ? strtoupper($new_shipper_name) : "VALUEBASKET.COM LIMITED";
                    $data["shipper_contact"] = "";
                    $data["shipper_phone"] = "852-39043034";
                    $data["saddr_1"] = "Workshop A 10/F,";
                    $data["saddr_2"] = "Wah Shing Industrial Building,";
                    $data["saddr_3"] = "18 Cheung Shun Street,";
                    $data["saddr_4"] = "Lai Chi Kok, Kowloon,";
                    $data["saddr_5"] = "HongKong";
                    $data["saddr_6"] = "&nbsp;";
                    $data["date"] = date("d/m/Y");
                    $data["order_number"] = $new_so_no;
                    $data["deliver_name"] = $so_obj->get_delivery_name();
                    $data["client_id"] = $so_obj->get_client_id();


                    $line_no = 1;
                    list($delivery_addr_1, $delivery_addr_2, $delivery_addr_3) = explode("|", $so_obj->get_delivery_address());
                    $data["daddr_" . $line_no] = $delivery_addr_1;
                    $line_no++;
                    if ($delivery_addr_2 != "" || $delivery_addr_3 != "") {
                        if ($delivery_addr_2 != "") {
                            $data["daddr_" . $line_no] = $delivery_addr_2;
                            $line_no++;
                        }

                        if ($delivery_addr_3 != "") {
                            $data["daddr_" . $line_no] = $delivery_addr_3;
                            $line_no++;
                        }
                    }

                    $csz = "";
                    if ($so_obj->get_delivery_city() != "") {
                        $csz .= $so_obj->get_delivery_city() . ", ";
                    }
                    if ($so_obj->get_delivery_state() != "") {
                        $csz .= $so_obj->get_delivery_state();
                    }
                    if ($so_obj->get_delivery_postcode() != "") {
                        $csz .= " " . $so_obj->get_delivery_postcode();
                    }
                    $csz = @preg_replace("{, $}", "", $csz);
                    if (trim($csz)) {
                        $data["daddr_" . $line_no] = $csz;
                        $line_no++;
                    }
                    $data["daddr_" . $line_no] = $so_obj->get_delivery_country_id();

                    $dcountry_obj = $this->getDao('Country')->get(array("id" => $so_obj->get_delivery_country_id()));
                    $dstatezip = trim($so_obj->get_delivery_state() . ", " . $so_obj->get_delivery_postcode());
                    if ($dstatezip != ",") {
                        $dstatezip = @preg_replace("{^, }", "", $dstatezip);
                        $dstatezip = @preg_replace("{,$}", "", $dstatezip) . "<br>";
                    } else {
                        $dstatezip = "";
                    }

                    $item_information = $declared_ratio = "";
                    if (in_array($so_obj->get_delivery_country_id(), array("AU"))) {
                        foreach ($itemlist AS $item_obj) {
                            $prod_obj = $this->getDao('Product')->get(array("sku" => $item_obj->get_main_prod_sku()));
                            $value = round($item_obj->get_amount() / $item_obj->get_qty() * $so_obj->get_rate(), 2);
                            $sum += $value * $item_obj->get_qty();
                        }
                        if ($obj = $this->get_sub_domain_srv()->get(array("subject" => "MAX_DECLARE_VALUE.{$so_obj->get_delivery_country_id()}"))) {
                            if ($obj->get_value() <= $sum) {
                                $declared_ratio = $obj->get_value() / $sum;
                            }
                        }
                    }
                    if (in_array($so_obj->get_delivery_country_id(), array("NZ"))) {
                        foreach ($itemlist AS $item_obj) {
                            $prod_obj = $this->getDao('Product')->get(array("sku" => $item_obj->get_main_prod_sku()));
                            $value = round($item_obj->get_amount() / $item_obj->get_qty() * $so_obj->get_rate(), 2);
                            $sum += $value * $item_obj->get_qty();
                        }
                        if ($obj = $this->get_sub_domain_srv()->get(array("subject" => "SELLING_PRICE_CEILING.{$so_obj->get_delivery_country_id()}"))) {
                            $declared_ratio = 1;
                            if ($obj->get_value() <= $sum) {
                                $declared_ratio = 0.5;
                            }
                        }
                    }
                    $bvat = 0;
                    $vat = 0;
                    $sum = 0;
                    $total_piece = 0;

                    //#2182 add the processing fee
                    if ($extobj = $this->getDao('SoExtend')->get(array("so_no" => $so_obj->get_so_no()))) {
                        $processing_fee = $extobj->get_offline_fee();
                    }
                    if (is_null($processing_fee)) {
                        $processing_fee = 0;
                    }

                    foreach ($itemlist as $item_obj) {
                        $hs_desc = $code = null;
                        $prod_obj = $this->getDao('Product')->get(array("sku" => $item_obj->get_main_prod_sku()));
                        $qty = $item_obj->get_qty();
                        $amount_total = $item_obj->get_amount();
                        if ($pcc_obj = $this->getDao('ProductCustomClassification')->get(array('sku' => $prod_obj->get_sku(), 'country_id' => $so_obj->get_delivery_country_id()))) {
                            $hs_desc = $pcc_obj->get_description();
                            $code = $pcc_obj->get_code();
                        }

                        //SBF #4403 - If hs desc and code not found, get the hs desc and code from sub_cat_id of the product
                        if (!isset($hs_desc) || $hs_desc == '') {
                            $where = array('ccm.sub_cat_id' => $prod_obj->get_sub_cat_id(), 'ccm.country_id' => $so_obj->get_delivery_country_id());
                            $hsDetails = $this->getDao('CustomClassificationMapping')->getHsBySubcatAndCountry($where, $option);
                            $hs_desc = $hsDetails[0]['description'];
                            $code = $hsDetails[0]['code'];
                        }

                        if ($declared_ratio) {
                            $declared_value = round($amount_total / $item_obj->get_qty() * $so_obj->get_rate() * $declared_ratio, 2);
                        } else {
                            $declared_value = round($this->get_declared_value($prod_obj, $so_obj->get_delivery_country_id(), $amount_total / $item_obj->get_qty()), 2);
                        }

                        # sbf 4145 - custom invoice in HKD
                        $declared_value_converted = $declared_value * $so_obj->get_rate();
                        $delivery_charge_converted = $so_obj->get_delivery_charge() * $so_obj->get_rate();
                        $processing_fee_converted = $processing_fee * $so_obj->get_rate();
                        if ($currency) {
                            $original_currency = "USD"; // $so_obj->get_currency_id();
                            $declared_value_converted = $this->convertCurrency($original_currency, $currency, $declared_value_converted);
                            $delivery_charge_converted = $this->convertCurrency($original_currency, $currency, $delivery_charge_converted);
                            $processing_fee_converted = $this->convertCurrency($original_currency, $currency, $processing_fee_converted);
                        } else {
                            $currency = "USD";
                        }
                        $data["currency"] = strtoupper($currency);

                        $item_information .= "<tr bgcolor='#000000'>
                                                <td width='1' class='tborder'></td>
                                                <td class='value'>" . $hs_desc . "</td>
                                                <td width='1' class='tborder'></td>
                                                <td class='value'>" . $item_obj->get_qty() . "</td>
                                                <td width='1' class='tborder'></td>
                                                <td class='value'>" . $code . "</td>
                                                <td width='1' class='tborder'></td>
                                                <td class='value'>" . number_format($declared_value_converted, 2) . "</td>
                                                <td width='1' class='tborder'></td>
                                                <td class='value'>" . number_format($declared_value_converted * $qty, 2) . "</td>
                                                <td width='1' class='tborder'></td>
                                            </tr>
                                            <tr bgcolor='#000000'>
                                                <td colspan='11' height='1'></td>
                                            </tr>";
                        $sum += $declared_value_converted * $qty;
                    }
                    $data["item_info"] = $item_information;
                    //$data["total_cost"] = $so_obj->get_amount() *  $so_obj->get_rate();
                    $data["total_cost"] = number_format($sum, 2);
                    $data["delivery"] = number_format($delivery_charge_converted, 2);
                    $data['processing_fee'] = number_format($processing_fee_converted, 2);

                    $data["total_value"] = number_format($sum + $data["delivery"] + $data['processing_fee'], 2);

                    $content .= $this->get_custom_inv_body($data, $lang, $new_shipper_name);
                    if ($run < $total_cnt) {
                        $content .= $pagebreak;
                    }
                    unset($data);
                    $valid++;
                }
            }
            if ($valid) {
                return $header . $content . $footer;
            } else {
                return FALSE;
            }
        }
        return FALSE;
    }

    public function get_declared_value($prod_obj = "", $country_id = "", $price = "")
    {
        $max_declared_value = -1;
        $declared_pcent = 100;
        $declared = -1;

        $this->declared_value_debug .= "0. country_id $country_id\r\n";

        {
            switch ($country_id) {
                case "AU":
                    if ($price < 950)
                        $declared_pcent = 100;
                    else
                        $max_declared_value = 950;
                    break;

                case "RU":
                    $declared_pcent = 100;
                    break;

                case "NZ":
                    if ($price < 350)
                        $declared_pcent = 100;
                    else
                        $declared_pcent = 80;
                    break;

                case "SG":
                    if ($price < 350)
                        $declared_pcent = 100;
                    else
                        $max_declared_value = 350;
                    break;

                // based on SBF#1790
                case "AE":
                case "AF":
                case "AG":
                case "AI":
                case "AM":
                case "AN":
                case "AO":
                case "AQ":
                case "AR":
                case "AS":
                case "AW":
                case "AZ":
                case "BB":
                case "BD":
                case "BF":
                case "BH":
                case "BI":
                case "BJ":
                case "BM":
                case "BN":
                case "BO":
                case "BR":
                case "BS":
                case "BT":
                case "BV":
                case "BW":
                case "BZ":
                case "CA":
                case "CC":
                case "CD":
                case "CF":
                case "CG":
                case "CI":
                case "CK":
                case "CL":
                case
                "CM":
                case "CN":
                case "CO":
                case "CR":
                case "CU":
                case "CV":
                case "CX":
                case "CY":
                case "DJ":
                case "DM":
                case "DO":
                case "DZ":
                case "EC":
                case "EG":
                case "EH":
                case "ER":
                case "ET":
                case "FJ":
                case "FK":
                case "FM":
                case "GA":
                case "GD":
                case "GE":
                case "GF":
                case "GH":
                case "GL":
                case "GM":
                case "GN":
                case "GP":
                case "GQ":
                case "GS":
                case "GT":
                case "GU":
                case "GW":
                case "GY":
                case "HK":
                case
                "HM":
                case "HN":
                case "HT":
                case "ID":
                case "IL":
                case "IN":
                case "IO":
                case "IQ":
                case "IR":
                case "JM":
                case "JO":
                case "JP":
                case "KE":
                case "KG":
                case "KH":
                case "KI":
                case "KM":
                case "KN":
                case "KP":
                case "KR":
                case "KW":
                case "KY":
                case "KZ":
                case "LA":
                case "LB":
                case "LC":
                case "LK":
                case "LR":
                case "LS":
                case "LY":
                case "MA":
                case "ME":
                case "MG":
                case "MH":
                case "ML":
                case "MM":
                case
                "MN":
                case "MO":
                case "MP":
                case "MQ":
                case "MR":
                case "MS":
                case "MU":
                case "MV":
                case "MW":
                case "MX":
                case "MY":
                case "MZ":
                case "NA":
                case "NC":
                case "NE":
                case "NF":
                case "NG":
                case "NI":
                case "NP":
                case "NR":
                case "NU":
                case "OM":
                case "PA":
                case "PE":
                case "PF":
                case "PG":
                case "PH":
                case "PK":
                case "PM":
                case "PN":
                case "PR":
                case "PS":
                case "PW":
                case "PY":
                case "QA":
                case
                "RE":
                case "RU":
                case "RW":
                case "SA":
                case "SB":
                case "SC":
                case "SD":
                case "SH":
                case "SL":
                case "SN":
                case "SO":
                case "SR":
                case "ST":
                case "SV":
                case "SY":
                case "SZ":
                case "TC":
                case "TD":
                case "TF":
                case "TG":
                case "TH":
                case "TJ":
                case "TK":
                case "TL":
                case "TM":
                case "TN":
                case "TO":
                case "TR":
                case "TT":
                case "TV":
                case "TW":
                case "TZ":
                case "UG":
                case "UM":
                case "US":
                case
                "UY":
                case "UZ":
                case "VC":
                case "VE":
                case "VG":
                case "VI":
                case "VN":
                case "VU":
                case "WF":
                case "WS":
                case "YE":
                case "YT":
                case "ZA":
                case "ZM":
                case "ZW":
                    $declared_pcent = 10;
                    break;

                # these are EU countries
                case "GB":
                case "AD":
                case "AL":
                case "AT":
                case "AX":
                case "BA":
                case "BE":
                case "BG":
                case "BL":
                case "BY":
                case "CH":
                case "CZ":
                case "DE":
                case "DK":
                case "EE":
                case "ES":
                case "FI":
                case "FO":
                case "FR":
                case "GG":
                case
                "GI":
                case "GR":
                case "HR":
                case "HU":
                case "IE":
                case "IM":
                case "IS":
                case "IT":
                case "JE":
                case "LI":
                case "LT":
                case "LU":
                case "LV":
                case "MC":
                case "MD":
                case "MF":
                case "MK":
                case "MT":
                case "NL":
                case "NO":
                case
                "PL":
                case "PT":
                case "RO":
                case "RS":
                case "SE":
                case "SI":
                case "SJ":
                case "SK":
                case "SM":
                case "UA":
                case "VA":
                    $declared_pcent = 10;
                    break;

                default:    # all other countries
                    $declared_pcent = 10;
                    break;
                    if ($fc_obj = $this->getDao('FreightCategory')->get(array("id" => $prod_obj->get_freight_cat_id()))) {
                        $declared_pcent = $fc_obj->get_declared_pcent();
                        $this->declared_value_debug .= "1. declared pcent is $declared_pcent\r\n";
                    } else {
                        // default value
                        $declared_pcent = $this->getDao('Config')->valueOf("default_declared_pcent");
                        $this->declared_value_debug .= "2. declared pcent is $declared_pcent\r\n";
                    }

                    if ($obj = $this->get_sub_domain_srv()->get(array("subject" => "MAX_DECLARE_VALUE.{$country_id}"))) {
                        $max_value = $obj->get_value();
                        $declared = min($max_value, $price);
                        $this->declared_value_debug .= "3. (max, price, chosen) is ($max_value, $price, $declared)\r\n";
                    } else {
                        $declared = $price * $declared_pcent / 100;
                        $this->declared_value_debug .= "4. (price, declared_pcent, final) is ($price, $declared_pcent, $declared)\r\n";
                    }
            }

            if ($declared == -1) {
                # we have to use max declared value
                if ($max_declared_value != -1) {
                    if ($price > $max_declared_value)
                        $declared = $max_declared_value;
                    else
                        $declared = $price;
                } else {
                    # we have to use declared percent
                    $declared = $price * $declared_pcent / 100;
                }
            }
        }

        $this->declared_value_debug .= "5. input price at $price\r\n";
        $this->declared_value_debug .= "5. declared_at $declared\r\n";
        return $declared;
    }

    public function convertCurrency($original_currency, $new_currency, $original_value)
    {
        $rate = $this->exchangeRateService->getExchangeRate($original_currency, $new_currency)->get_rate();
        return $rate * $original_value;
    }

    private function get_custom_inv_body($data = array(), $lang = array(), $new_shipper_name = "")
    {
        foreach ($data as $key => $value) {
            ${$key} = $value;
        }

        $logo_place_holder = $new_shipper_name ? "" : "<img src='" . base_url() . "/images/valuebasket_logo.png' border='0'><br/>";
        include APPPATH . "data/cinv_body.php";

        return $body;
    }

    public function set_pi($so_no)
    {
        return $this->set_profit_info($this->getDao('So')->get(array("so_no" => $so_no)));
    }

    public function fire_log_email_event($so_no = "", $template = "", $option = "")
    {
        if ($so_no == "" || $template == "" || $option == "") {
            return FALSE;
        } else {
            $so_obj = $this->getDao('So')->get(array("so_no" => $so_no));
            if ($so_obj === FALSE || !$so_obj) {
                return FALSE;
            } else {
                $sohr_obj = $this->getDao('SoHoldReason')->getLatestRequest(array("so_no" => $so_no));
                if (!$sohr_obj) {
                    return FALSE;
                }

                $user_obj = $this->getDao('User')->get(array("id" => $sohr_obj->get_create_by()));
                include_once APPPATH . "libraries/dto/event_email_dto.php";
                $dto = new Event_email_dto();

                $replace["so_no"] = $so_no;
                $replace["client_id"] = $so_obj->get_client_id();
                $replace["name"] = $user_obj->get_username();
                $replace["create_date"] = $sohr_obj->get_create_on();
                $replace["reason"] = ereg_replace('_log_app$', '', $sohr_obj->get_reason());

                $dto->set_event_id("notification");
                $dto->set_mail_from('logistics@valuebasket.com');
                $dto->set_mail_to($user_obj->get_email());
                $dto->set_tpl_id("log_reply_" . $option);
                $dto->set_replace($replace);
                $this->eventService->fireEvent($dto);

                return TRUE;
            }
        }
    }

    public function fire_cs2log_email($so_no = "", $reason = "", $user_info = array())
    {
        if ($so_no == "" || $reason == "" || empty($user_info)) {
            return;
        } else {
            include_once APPPATH . "libraries/dto/event_email_dto.php";
            $dto = new Event_email_dto();

            $so_obj = $this->get(array("so_no" => $so_no));
            $replace["so_no"] = $so_no;
            $replace["client_id"] = $so_obj->get_client_id();
            $replace["name"] = $user_info["username"];
            $replace["create_date"] = date("Y-m-d H:i:s");
            $replace["reason"] = $reason;

            $dto->set_event_id("notification");
            $dto->set_mail_to('logistics@valuebasket.com');
            $dto->set_mail_from($user_info["email"]);
            $dto->set_tpl_id("log_notice");
            $dto->set_replace($replace);
            $this->eventService->fireEvent($dto);

            return TRUE;
        }
    }

    public function fire_cs_request($so_no = "", $reason = "")
    {

        if ($so_no == "" || $reason == "") {
            return;
        } else {
            # sbf #3746 don't include complementary accessory on front end
            $ca_catid_arr = implode(',', $this->get_ca_service()->get_accessory_catid_arr());

            $so_obj = $this->getDao('So')->get(array("so_no" => $so_no));
            if ($so_obj) {
                $client_obj = $this->getDao('Client')->get(array("id" => $so_obj->get_client_id()));
                $pbv_obj = $this->getDao('PlatformBizVar')->get(array("selling_platform_id" => $so_obj->get_platform_id()));
                if ($client_obj) {
                    $list = $this->getDao('SoItem')->getItemsWithName(array("so_no" => $so_no, "p.cat_id NOT IN ($ca_catid_arr)" => NULL));

                    $item_list = array();
                    foreach ($list as $obj) {
                        $item_list[] = "- " . $obj->get_name();
                    }

                    include_once APPPATH . "libraries/dto/event_email_dto.php";
                    $dto = new Event_email_dto();

                    $replace["order_number"] = $so_no;
                    $replace["client_id"] = $so_obj->get_client_id();
                    $replace["forename"] = $client_obj->get_forename();
                    $replace["order_create_date"] = date("Y-m-d", strtotime($so_obj->get_order_create_date()));
                    $replace["item_list"] = implode("\n", $item_list);

                    include_once(APPPATH . "hooks/country_selection.php");
                    $order_lang = $pbv_obj ? $pbv_obj->get_language_id() : "";
                    $order_country = $pbv_obj ? $pbv_obj->get_platform_country_id() : "";
                    $replace = array_merge($replace, Country_selection::get_template_require_text($order_lang, $order_country));
                    $email_sender = "Agatha@" . strtolower($replace["site_name"]);

                    $dto->set_event_id("notification");
                    $dto->set_mail_to($client_obj->get_email());
                    //$dto->set_mail_to('itsupport@eservicesgroup.net');
                    $lang = get_lang_id();
                    $support_email = $this->get_cs_support_email($lang);

                    $dto->set_mail_from($email_sender);
                    $dto->set_tpl_id($reason . "_request");
                    $dto->set_lang_id($pbv_obj ? $pbv_obj->get_language_id() : "");
                    $dto->set_replace($replace);
                    $this->eventService->fireEvent($dto);
                }
            }
        }
    }

    public function getCreditCheckList($where = array(), $option = array(), $type = "")
    {
        $ret = array();

        if ($obj_list = $this->getDao('So')->getCreditCheckList($where, $option, $type)) {
            foreach ($obj_list as $obj) {
                $cnt = $this->getDao('So')->getPwdCnt($obj->get_so_no(), $obj->get_client_id());
                $obj->set_pw_count($cnt);
                $item = $this->getDao('So')->getItemDetailStr($obj->get_so_no());
                $obj->set_items($item);
                $sor_obj = $this->getDao('SoRisk')->get(["so_no" => $obj->get_so_no()]);
                $obj->set_sor_obj($sor_obj);
                $ret[] = $obj;
            }
        }

        return $ret;
    }

    public function get_track_order($so_no)
    {
        include_once(APPPATH . "helpers/object_helper.php");
        $order["shipped"] = $order["processing"] = array();
        if ($soid_list = $this->getDao('SoItemDetail')->getListWithProdname(array("soid.so_no" => $so_no), array("limit" => -1))) {
            foreach ($soid_list as $soid_obj) {
                $line_no = $soid_obj->get_line_no();
                $item_sku = $soid_obj->get_item_sku();
                $order["processing"][$line_no][$item_sku] = $soid_obj;
            }
        }
        if ($soal_list = $this->getDao('SoShipment')->getShippedList(array("soal.so_no" => $so_no))) {
            foreach ($soal_list as $soal_obj) {
                $sh_no = $soal_obj->get_sh_no();
                $line_no = $soal_obj->get_line_no();
                $item_sku = $soal_obj->get_item_sku();
                $newobj = clone $order["processing"][$line_no][$item_sku];
                set_value($newobj, $soal_obj);
                $order["shipped"][$sh_no][$line_no][$item_sku] = $newobj;
                $old_qty = $order["processing"][$line_no][$item_sku]->get_qty() * 1;
                $new_qty = $old_qty - $newobj->get_qty() * 1;
                if ($new_qty == 0) {
                    unset($order["processing"][$line_no][$item_sku]);
                    if (empty($order["processing"][$line_no])) {
                        unset($order["processing"][$line_no]);
                    }
                } else {
                    $order["processing"][$line_no][$item_sku]->set_qty($new_qty);
                }
            }
        }
        return $order;
    }

    public function getNextShNo($so_no)
    {
        $last_sh_no = $this->getDao('SoAllocate')->getLastShNo($so_no);
        list($sno, $last_no) = @explode("-", $last_sh_no);
        return $so_no . "-" . sprintf("%02d", $last_no * 1 + 1);
    }

    public function error_in_allocate_file()
    {
        $arr = $this->getAwaitingShipmentInfo();
        echo "The following SKU does not have master SKU<br>";
        if ($arr) {
            foreach ($arr as $row) {
                if (!$row->get_sku()) {
                    echo $row->get_ext_ref_sku() . "<br>";
                }
            }
        }
    }

    public function getAwaitingShipmentInfo()
    {
        return $this->getDao('SoAllocate')->getAwaitingShipmentInfo();
    }

    public function generate_allocate_file()
    {
        $file_content = "";
        $output_path = $this->getDao('Config')->valueOf('courier_path');

        $arr = $this->getAwaitingShipmentInfo();
        if ($arr) {
            foreach ($arr as $row) {
                $row->set_warehouse("HK");
                $row->set_ext_sys("CV");
            }
        }

        $out_xml = new Vo_to_xml($arr, '');
        $out_csv = new Xml_to_csv('', APPPATH . 'data/awaiting_shipment_to_wms.txt', TRUE, ',');
        $file_content = $this->get_dex_service()->convert($out_xml, $out_csv);

        if ($file_content != "") {
            $filename = "cs_awaiting_shipment_" . date("YmdHis") . ".csv";
            if ($fp = @fopen($output_path . $filename, 'w')) {
                @fwrite($fp, $file_content);
                @fclose($fp);

                return $filename;
            }
        }
        return;
    }

    public function get_dex_service()
    {
        return $this->dex_service;
    }

    public function set_dex_service($srv)
    {
        $this->dex_service = $srv;
    }

    public function generate_courier_file($checked = array(), $courier = "", $mawb = "", $debug_explain = false)
    {
        $file_content = "";
        $output_path = $this->getDao('Config')->valueOf('courier_path');
        $data_out = array();
        foreach ($checked as $key => $value) {
            switch ($courier) {
                case "DHLHKD":
                case "DHL":
                    if ($arr = $this->getShipmentDeliveryInfoDhl($value))
                    {
                        foreach ($arr as $row) {
                            $ar_address = @explode("|", $row->get_delivery_address());
                            $row->set_delivery_address1($ar_address[0]);
                            if (empty($ar_address[1]) && empty($ar_address[2])) {
                                $row->set_delivery_address2('NA');
                            } else {
                                $row->set_delivery_address2(implode(" ", array($ar_address[1], $ar_address[2])));
                            }
                            if (!$row->get_delivery_city()) {
                                $row->set_delivery_address3('NA');
                            } else {
                                $row->set_delivery_address3($row->get_delivery_city());
                            }
                            $row->set_qty(1);
                            $row->set_prod_weight($row->get_prod_weight() * $row->get_qty());
                            $row->set_price($row->get_amount());
                            if ($row->get_tel() == "") {
                                $row->set_tel("0");
                            }
                            if ($row->get_delivery_company() == "") {
                                $row->set_delivery_company($row->get_delivery_name());
                            }
                            $prod_obj = $this->getDao('Product')->get(array("sku" => $row->get_prod_sku()));
                            $declared_value = $this->get_declared_value($prod_obj, $row->get_delivery_country_id(), $row->get_price());

                            // #sbf4096 - Add DHLHKD
                            if ($courier === 'DHLHKD') {
                                // If original (DHL)) is always in USD, then convert it from here
                                $declared_value = $declared_value * $row->get_rate();
                                $declared_value = round($this->convertCurrency("USD", 'HKD', $declared_value), 2);
                            } else {
                                $declared_value = round($declared_value * $row->get_rate(), 2);
                            }

                            $row->set_declared_value($declared_value);

                            $data_out[] = $row;
                            $counter++;
                        }
                    }
                    $out_xml = new Vo_to_xml($data_out, '');

                    // #sbf4096 - Add DHLHKD
                    if ($courier === 'DHLHKD') {
                        $out_csv = new Xml_to_csv('', APPPATH . 'data/shipment_info_to_courier_dhlhkd_xml2csv.txt', FALSE, '|');
                    } else {
                        $out_csv = new Xml_to_csv('', APPPATH . 'data/shipment_info_to_courier_dhl_xml2csv.txt', FALSE, '|');
                    }

                    $file_content = $this->get_dex_service()->convert($out_xml, $out_csv);
                    break;
                case "DHLBBX":
                    if ($arr = $this->getShipmentDeliveryInfoDhl($value)) {
                        foreach ($arr as $row) {
                            $ar_address = @explode("|", $row->get_delivery_address());
                            $row->set_delivery_address1($ar_address[0]);
                            if (empty($ar_address[1])) {
                                $row->set_delivery_address2('NA');
                            } else {
                                $row->set_delivery_address2($ar_address[1]);
                            }
                            if (empty($ar_address[2])) {
                                $row->set_delivery_address3('NA');
                            } else {
                                $row->set_delivery_address3($ar_address[2]);
                            }
                            $row->set_qty(1);
                            $row->set_prod_weight($row->get_prod_weight() * $row->get_qty());
                            $row->set_price($row->get_amount());
                            if ($row->get_tel() == "") {
                                $row->set_tel("0");
                            }
                            if ($row->get_delivery_company() == "") {
                                $row->set_delivery_company($row->get_delivery_name());
                            }
                            $prod_obj = $this->getDao('Product')->get(array("sku" => $row->get_prod_sku()));
                            $declared_value = $this->get_declared_value($prod_obj, $row->get_delivery_country_id(), $row->get_price());
                            $row->set_declared_value(round($declared_value * $row->get_rate(), 2));
                            if (trim($mawb) != "") {
                                $row->set_mawb("MAWB#: " . $mawb);
                            }
                            $data_out[] = $row;
                            $counter++;
                        }
                    }
                    $out_xml = new Vo_to_xml($data_out, '');
                    $out_csv = new Xml_to_csv('', APPPATH . 'data/shipment_info_to_courier_dhlbbx_xml2csv.txt', FALSE, '|');
                    $file_content = $this->get_dex_service()->convert($out_xml, $out_csv);
                    break;
                case "FEDEX":
                    if ($arr = $this->getShipmentDeliveryInfoCourier($value)) {
                        $counter = 0;
                        foreach ($arr as $row) {
                            $ar_address = @explode("|", $row->get_delivery_address());
                            $row->set_delivery_address1($ar_address[0]);
                            array_shift($ar_address);
                            $row->set_delivery_address2(trim(@implode("|", $ar_address), "|"));

                            $row->set_prod_weight($row->get_prod_weight() * $row->get_qty() * 10);
                            $row->set_price($row->get_amount());

                            if ($row->get_delivery_company() == "") {
                                $row->set_delivery_company($row->get_delivery_name());
                            }

                            $prod_obj = $this->getDao('Product')->get(array("sku" => $row->get_prod_sku()));
                            $declared_value = $this->get_declared_value($prod_obj, $row->get_delivery_country_id(), $row->get_price());
                            $row->set_declared_value(round($declared_value * $row->get_rate(), 2) * 100);

                            $file_content .= "0,\"20\"\r\n" .
                                "1,\"{$counter}\"\r\n" .
                                "1274,\"3\"\r\n" .
                                "31,\"VB\"\r\n" .
                                "11,\"{$row->get_delivery_company()}\"\r\n" .
                                "12,\"{$row->get_delivery_name()}\"\r\n" .
                                "13,\"{$row->get_delivery_address1()}\"\r\n" .
                                "14,\"{$row->get_delivery_address2()}\"\r\n" .
                                "16,\"{$row->get_delivery_state()}\"\r\n" .
                                "15,\"{$row->get_delivery_city()}\"\r\n" .
                                "17,\"{$row->get_delivery_postcode()}\"\r\n" .
                                "50,\"{$row->get_delivery_country_id()}\"\r\n" .
                                "18,\"{$row->get_tel()}\"\r\n" .
                                "116,\"1\"\r\n" .
                                "21,\"5\"\r\n" .
                                "119,\"{$row->get_declared_value()}\"\r\n" .
                                "79-1,\"{$row->get_cc_desc()}\"\r\n" .
                                "79-2,\"hscode {$row->get_cc_code()}\"\r\n" .
                                "81,\"{$row->get_cc_code()}\"\r\n" .
                                "80-1,\"JP\"\r\n" .
                                "80-2,\"JP\"\r\n" .
                                "25,\"{$row->get_so_no()}\"\r\n" .
                                "72,\"5\"\r\n" .
                                "23,\"1\"\r\n" .
                                "20,\"319974954\"\r\n" .
                                "68,\"USD\"\r\n" .
                                "70,\"2\"\r\n" .
                                "1273,\"01\"\r\n" .
                                "75,\"KGS\"\r\n" .
                                "190,\"N\"\r\n" .
                                "1116,\"C\"\r\n" .
                                "414,\"PCS\"\r\n" .
                                "113,\"N\"\r\n" .
                                "99,\"\"\r\n";
                            $counter++;
                        }
                    }
                    break;

                case "FEDEX2":
                    if ($arr = $this->getShipmentDeliveryInfoCourier($value)) {
                        $total_declared_value = 0;
                        $total_declared_value_to_6decimals = 0;
                        $this->declared_value_debug .= "8total_declared_value_to_6decimals value: $total_declared_value_to_6decimals\r\n";
                        $counter = 0;
                        $ts = "";
                        foreach ($arr as $row) {
                            $this->declared_value_debug .= "8total_declared_value_to_6decimals value: $total_declared_value_to_6decimals\r\n";
                            $ar_address = @explode("|", $row->get_delivery_address());
                            $row->set_delivery_address1($ar_address[0]);
                            array_shift($ar_address);
                            $row->set_delivery_address2(trim(@implode("|", $ar_address), "|"));

                            $row->set_prod_weight($row->get_prod_weight() * $row->get_qty() * 10);
                            $row->set_price($row->get_amount());

                            if ($row->get_delivery_company() == "") {
                                $row->set_delivery_company($row->get_delivery_name());
                            }

                            $cc = $row->get_currency_id();

                            switch ($cc) {
                                case "GBP":
                                    $cc = "UKL";
                                    break;
                                case "SGD":
                                    $cc = "SID";
                                    break;
                            }

                            if ($counter == 0) $total_declared_value = $row->get_price(); else $total_declared_value += $row->get_price();

                            $prod_obj = $this->getDao('Product')->get(array("sku" => $row->get_prod_sku()));

                            // we pass total_declared_value_to_6decimals in so that we will eventually calculate a declared value
                            // of all the items in the order, e.g. SKU-A: 649, SKU-B: 399, we will calculate declared value based on 649+399
                            $declared_value = $this->get_declared_value($prod_obj, $row->get_delivery_country_id(), $total_declared_value);
                            $this->declared_value_debug .= "declared value: $declared_value\r\n";
                            $this->declared_value_debug .= "1total_declared_value_to_6decimals value: $total_declared_value_to_6decimals\r\n";

                            # convert to USD
                            $convert_to_usd = false;
                            if ($convert_to_usd) {
                                $declared_value = round($declared_value * $row->get_rate(), 2);
                                $row->set_declared_value($declared_value);
                            }

                            $this->declared_value_debug .= "2total_declared_value_to_6decimals value: $total_declared_value_to_6decimals\r\n";

                            $total_declared_value_to_6decimals = $declared_value * 1000000;
                            $this->declared_value_debug .= "3total_declared_value_to_6decimals value: $total_declared_value_to_6decimals\r\n";

                            if ($counter == 0) {
                                $file_content .=
                                    "0,\"20\"\r\n" .
                                    "1,\"{$counter}\"\r\n" .
                                    "1274,\"3\"\r\n" .
                                    "31,\"LIVE ASSET LOGISTICS\"\r\n" .
                                    "11,\"{$row->get_delivery_company()}\"\r\n" .
                                    "12,\"{$row->get_delivery_name()}\"\r\n" .
                                    "13,\"{$row->get_delivery_address1()}\"\r\n" .
                                    "14,\"{$row->get_delivery_address2()}\"\r\n" .
                                    "16,\"{$row->get_delivery_state()}\"\r\n" .
                                    "15,\"{$row->get_delivery_city()}\"\r\n" .
                                    "17,\"{$row->get_delivery_postcode()}\"\r\n" .
                                    "50,\"{$row->get_delivery_country_id()}\"\r\n" .
                                    "18,\"{$row->get_tel()}\"\r\n" .
                                    "116,\"1\"\r\n" .
                                    "21,\"5\"\r\n" .
                                    // "119,\"{$row->get_declared_value()}\"\r\n".
                                    "79-1,\"{$row->get_cc_desc()} hscode {$row->get_cc_code()}\"\r\n" .
                                    // "79-2,\"hscode {$row->get_cc_code()}\"\r\n".
                                    "81-1,\"{$row->get_cc_code()}\"\r\n" .
                                    "80-1,\"JP\"\r\n" .
                                    // "80-2,\"JP\"\r\n".
                                    "25,\"{$row->get_so_no()}\"\r\n" .
                                    "72,\"6\"\r\n" .
                                    "23,\"3\"\r\n" .
                                    "20,\"319974954\"\r\n" .
                                    "68,\"" . $cc . "\"\r\n" .
                                    "70,\"3\"\r\n" .
                                    "1273,\"01\"\r\n" .
                                    "75,\"KGS\"\r\n" .
                                    "190,\"N\"\r\n" .
                                    "1116,\"C\"\r\n" .
                                    "414-1,\"PCS\"\r\n" .
                                    "113,\"Y\"\r\n" .
                                    "82-1,\"1\"\r\n";
                            }
                            $counter++;
                        }

                        $file_content .=
                            "1030-1,\"$total_declared_value_to_6decimals\"\r\n" .
                            "629,\"default\"\r\n" .
                            "71,\"319974954\"\r\n" .
                            "2806,\"Y\"\r\n" .
                            "418-1,\"Package contains lithium ion batteries or cells (PI966)\"\r\n" .
                            "418-2,\"Handle with care, flammability hazard if damage\"\r\n" .
                            "418-3,\"Special procedures must be followed in the event the package is damaged,\"\r\n" .
                            "418-4,\"to include inspection and repacking if necessary\"\r\n" .
                            "418-5,\"Emergency contact no. +852 3153 2766\"\r\n" .
                            "99,\"\"\r\n";
                    }

                    if ($debug_explain) {
                        var_dump($file_content . "*****\r\nDebuggiin stuff below\r\n*****\r\n" . $this->declared_value_debug);
                        var_dump($row);
                        die();
                    }
                    break;

                case "TNT":

                    $counter = 0;
                    if ($arr = $this->getShipmentDeliveryInfoCourierForTnt($value)) {

                        $prev_so_no = "";
                        if (is_array($arr)) {
                            foreach ($arr as $row) {
                                if ($row->get_so_no() != $prev_so_no) {
                                    $ar_address = @explode("|", $row->get_delivery_address());
                                    $row->set_delivery_address1($ar_address[0]);
                                    array_shift($ar_address);
                                    $row->set_delivery_address2(trim(@implode("|", $ar_address), "|"));
                                    if ($row->get_delivery_address2() == "") {
                                        $row->set_delivery_address2(".");
                                    }

                                    $row->set_prod_weight(min(2, $row->get_prod_weight()));
                                    $row->set_price($row->get_amount());

                                    $countryObj = $this->getDao('Country')->get(array("id" => $row->get_delivery_country_id()));
                                    $row->set_country_name($countryObj->get_name());

                                    $row->set_item_no($counter);

                                    if ($row->get_delivery_city() == "") {
                                        $row->set_delivery_city('.');
                                    }

                                    $prev_so_no = $row->get_so_no();

                                    $data_out[] = $row;
                                    $counter++;
                                }
                            }
                        }
                    }

                    $out_xml = new Vo_to_xml($data_out, '');
                    $out_csv = new Xml_to_csv('', APPPATH . 'data/shipment_info_to_courier_tnt_xml2csv.txt', TRUE, '|');
                    $file_content = $this->get_dex_service()->convert($out_xml, $out_csv);
                    break;

                case "NEW_QUANTIUM":
                    $counter = 0;
                    if ($arr = $this->getShipmentDeliveryInfoCourier($value)) {
                        $prev_so_no = "";
                        foreach ($arr as $row) {
                            if ($row->get_so_no() != $prev_so_no) {
                                $ar_address = @explode("|", $row->get_delivery_address());
                                $row->set_delivery_address1($ar_address[0]);
                                array_shift($ar_address);
                                $row->set_delivery_address2(trim(@implode("|", $ar_address), "|"));
                                if ($row->get_delivery_address2() == "") {
                                    $row->set_delivery_address2(".");
                                }

                                $row->set_prod_weight(min(2, $row->get_prod_weight()));
                                $row->set_price($row->get_amount());

                                $countryObj = $this->getDao('Country')->get(array("id" => $row->get_delivery_country_id()));
                                $row->set_country_name($countryObj->get_name());

                                $row->set_item_no($counter);

                                if ($row->get_delivery_company() == "") {
                                    $row->set_delivery_company($row->get_delivery_name());
                                }

                                if ($row->get_delivery_city() == "") {
                                    $row->set_delivery_city('.');
                                }

                                $declared_value = $this->get_declared_value($row, $row->get_delivery_country_id(), $row->get_price());
                                $row->set_declared_value(round($declared_value * $row->get_rate(), 2));

                                $prev_so_no = $row->get_so_no();

                                $data_out[] = $row;
                                $counter++;
                            }
                        }
                    }
                    $out_xml = new Vo_to_xml($data_out, '');
                    $out_csv = new Xml_to_csv('', APPPATH . 'data/shipment_info_to_courier_new_quantium_xml2csv.txt', FALSE, '|');
                    $file_content = $this->get_dex_service()->convert($out_xml, $out_csv);
                    break;

                case "QUANTIUM":
                    $counter = 0;
                    if ($arr = $this->getShipmentDeliveryInfoCourier($value)) {
                        $prev_so_no = "";
                        foreach ($arr as $row) {
                            if ($row->get_so_no() != $prev_so_no) {
                                $ar_address = @explode("|", $row->get_delivery_address());
                                $row->set_delivery_address1($ar_address[0]);
                                array_shift($ar_address);
                                $row->set_delivery_address2(trim(@implode("|", $ar_address), "|"));

                                $row->set_prod_weight(min(2000, $row->get_prod_weight() * 1000));
                                $row->set_price($row->get_amount());

                                $countryObj = $this->getDao('Country')->get(array("id" => $row->get_delivery_country_id()));
                                $row->set_country_name($countryObj->get_name());

                                $row->set_item_no($counter);

                                if ($row->get_delivery_company() == "") {
                                    $row->set_delivery_company($row->get_delivery_name());
                                }

                                $declared_value = $this->get_declared_value($row, $row->get_delivery_country_id(), $row->get_price());
                                $row->set_declared_value(round($declared_value * $row->get_rate(), 2));

                                $prev_so_no = $row->get_so_no();

                                $data_out[] = $row;
                                $counter++;
                            }
                        }
                    }
                    $out_xml = new Vo_to_xml($data_out, '');
                    $out_csv = new Xml_to_csv('', APPPATH . 'data/shipment_info_to_courier_quantium_xml2csv.txt', TRUE, ',');
                    $file_content = $this->get_dex_service()->convert($out_xml, $out_csv);
                    break;

                case "TOLL":
                    if ($arr = $this->getShipmentDeliveryInfoDhl($value)) {
                        foreach ($arr as $row) {
                            $ar_address = @explode("|", $row->get_delivery_address());
                            $row->set_delivery_address1($ar_address[0]);
                            if (empty($ar_address[1]) && empty($ar_address[2])) {
                                $row->set_delivery_address2('NA');
                            } else {
                                $row->set_delivery_address2(implode(" ", array($ar_address[1], $ar_address[2])));
                            }
                            if (!$row->get_delivery_city()) {
                                $row->set_delivery_address3('NA');
                            } else {
                                $row->set_delivery_address3($row->get_delivery_city());
                            }
                            $row->set_qty($row->get_qty());
                            $row->set_prod_weight($row->get_prod_weight() * $row->get_qty());
                            $row->set_price($row->get_amount());
                            if ($row->get_tel() == "") {
                                $row->set_tel("0");
                            }
                            if ($row->get_delivery_company() == "") {
                                $row->set_delivery_company($row->get_delivery_name());
                            }
                            if ($row->get_delivery_country_id() == 'AU') {
                                $valid_city_arr = array("brisbane", "melbourne", "perth", "sydney");
                                if (trim($row->get_delivery_city()) == "" || !in_array(trim(strtolower($row->get_delivery_city())), $valid_city_arr)) {
                                    $row->set_delivery_city('Australia Other');
                                }
                            }

                            $prod_obj = $this->getDao('Product')->get(array("sku" => $row->get_prod_sku()));
                            $declared_value = $this->get_declared_value($prod_obj, $row->get_delivery_country_id(), $row->get_price());
                            $row->set_declared_value(round($declared_value * $row->get_rate(), 2));

                            if ($country_obj = $this->getDao('Country')->get(array("id" => $row->get_delivery_country_id()))) {
                                $country_name = $country_obj->get_name();
                                $row->set_delivery_country_id($country_name);
                            }
                            $data_out[] = $row;
                            $counter++;
                        }
                    }
                    $out_xml = new Vo_to_xml($data_out, '');
                    $out_csv = new Xml_to_csv('', APPPATH . 'data/shipment_info_to_courier_toll_xml2csv.txt', TRUE, ',');
                    $file_content = $this->get_dex_service()->convert($out_xml, $out_csv);
                    break;

                case "TOLL2":   // SBF#1965
                case "DPD":     // TOLL2 changed to DPD
                    if ($arr = $this->getShipmentDeliveryInfoDhl($value)) {
                        foreach ($arr as $row) {
                            $ar_address = @explode("|", $row->get_delivery_address());
                            $row->set_delivery_address1($ar_address[0]);
                            if (empty($ar_address[1]) && empty($ar_address[2])) {
                                $row->set_delivery_address2('NA');
                            } else {
                                $row->set_delivery_address2(implode(" ", array($ar_address[1], $ar_address[2])));
                            }
                            if (!$row->get_delivery_city()) {
                                $row->set_delivery_address3('NA');
                            } else {
                                $row->set_delivery_address3($row->get_delivery_city());
                            }
                            $row->set_qty($row->get_qty());
                            $row->set_prod_weight($row->get_prod_weight() * $row->get_qty());
                            $row->set_price($row->get_amount());
                            if ($row->get_tel() == "") {
                                $row->set_tel("0");
                            }
                            if ($row->get_delivery_company() == "") {
                                $row->set_delivery_company($row->get_delivery_name());
                            }
                            if ($row->get_delivery_country_id() == 'AU') {
                                $valid_city_arr = array("brisbane", "melbourne", "perth", "sydney");
                                if (trim($row->get_delivery_city()) == "" || !in_array(trim(strtolower($row->get_delivery_city())), $valid_city_arr)) {
                                    $row->set_delivery_city('Australia Other');
                                }
                            }

                            $prod_obj = $this->getDao('Product')->get(array("sku" => $row->get_prod_sku()));
                            $declared_value = $this->get_declared_value($prod_obj, $row->get_delivery_country_id(), $row->get_price());
                            $row->set_declared_value(round($declared_value * $row->get_rate(), 2));

                            if ($country_obj = $this->getDao('Country')->get(array("id" => $row->get_delivery_country_id()))) {
                                $country_name = $country_obj->get_name();
                                $row->set_delivery_country_id($country_name);
                            }
                            $data_out[] = $row;
                            $counter++;
                        }
                    }
                    $out_xml = new Vo_to_xml($data_out, '');
                    $out_csv = new Xml_to_csv('', APPPATH . 'data/shipment_info_to_courier_dpd_xml2csv.txt', TRUE, ',');
                    $file_content = $this->get_dex_service()->convert($out_xml, $out_csv);
                    break;
                case "IM":
                case "RMR":
                    $arr = $this->getShipmentDeliveryInfo($value, 'dispatch_list_dto'); // Pass the SO #

                    if (!$arr || ($no_of_line = count($arr)) == 0) {
                        continue;  // No data is found.  It shouldn't happen.
                    }

                    $counter = 1;

                    foreach ($arr as $row) {
                        $row->set_total_item_count($no_of_line);
                        $row->set_item_no($counter);
                        if (($courier == "RMR") && ($row->get_delivery_country_id() != "US")) {
                            $row->set_unit_price(number_format($row->get_unit_price() * 0.1, 2, '.', ''));
                            $row->set_delivery_charge(number_format($row->get_delivery_charge() * 0.1, 2, '.', ''));
                            $row->set_amount(number_format($row->get_amount() * 0.1, 2, '.', ''));
                        }
                        $row->set_subtotal(number_format(
                            $row->get_unit_price() * $row->get_qty()
                            , 2, '.', ''));
                        $row->set_actual_cost(number_format(
                            $row->get_amount() - $row->get_offline_fee()
                            , 2, '.', ''));
                        $row->set_bill_detail('N'); // Always 'N' at the beginning.
                        list($del_address_1, $del_address_2, $del_address_3) = explode("|", $row->get_delivery_address());
                        $row->set_delivery_address_1($del_address_1);
                        $row->set_delivery_address_2($del_address_2);
                        $row->set_delivery_address_3($del_address_3);
                        if ($counter > 1) {
                            $row->set_ship_option('');
                            $row->set_delivery_charge(0.00);
                            $row->set_promotion_code('');
                            $row->set_amount(0.00);
                            $row->set_delivery_type_id('');
                            $row->set_actual_cost(0.00);
                        }
                        $data_out[] = $row;
                        $counter++;
                    }
                    $out_xml = new Vo_to_xml($data_out, '');
                    $out_csv = new Xml_to_csv('', APPPATH . 'data/shipment_info_to_courier_' . strtolower($courier) . '_xml2csv.txt', TRUE, chr(9));
                    $file_content = $this->get_dex_service()->convert($out_xml, $out_csv);

                    // Prepare dispatch list data
                    $counter = 1;
                    foreach ($arr as $row) {
                        $row->set_total_item_count($no_of_line);
                        $row->set_item_no($counter);
                        $row->set_subtotal(number_format($row->get_unit_price() * $row->get_qty(), 2, '.', ''));
                        $row->set_actual_cost(number_format($row->get_amount() - $row->get_offline_fee(), 2, '.', ''));

                        if ($counter > 1) {
                            # code
                        }
                        $row->set_warehouse_id("VB_" . $courier);
                        $row->set_bin("STAG");
                        $dispatch_data_out[] = $row;
                        $counter++;
                    }
                    $dispatch_out_xml = new Vo_to_xml($dispatch_data_out, '');
                    if ($courier == "RMR")
                        $data_file = 'data/dispatch_list_rmr_xml2csv.txt';
                    else
                        $data_file = 'data/dispatch_list_xml2csv.txt';
                    $dispatch_out_csv = new Xml_to_csv('', APPPATH . $data_file, TRUE, ',');
                    $dispatch_content = $this->get_dex_service()->convert($dispatch_out_xml, $dispatch_out_csv);
                    break;
                case "ARAMEX_COD":
                    if ($arr = $this->getShipmentDeliveryInfoDhl($value)) {
                        foreach ($arr as $row) {
                            $ar_address = @explode("|", $row->get_delivery_address());
                            $row->set_delivery_address1($ar_address[0]);
                            $row->set_delivery_address2($ar_address[1]);
                            $row->set_delivery_address3($ar_address[2]);
                            $row->set_qty($row->get_qty());
                            $row->set_prod_weight($row->get_prod_weight() * $row->get_qty());
                            $row->set_price($row->get_amount());
                            if ($row->get_tel() == "") {
                                $row->set_tel("0");
                            }
                            if ($row->get_delivery_company() == "") {
                                $row->set_delivery_company($row->get_delivery_name());
                            }

                            $prod_obj = $this->getDao('Product')->get(array("sku" => $row->get_prod_sku()));
                            $declared_value = $row->get_price();

                            # convert to USD
                            $convert_to_usd = false;
                            if ($convert_to_usd) {
                                $declared_value = round($declared_value * $row->get_rate(), 2);
                            }
                            $row->set_declared_value($declared_value);

                            if ($country_obj = $this->getDao('Country')->get(array("id" => $row->get_delivery_country_id()))) {
                                $country_name = $country_obj->get_name();
                                $row->set_delivery_country_id($country_name);
                            }
                            $data_out[] = $row;
                            $counter++;
                        }
                    }

                    $out_xml = new Vo_to_xml($data_out, '');
                    $out_csv = new Xml_to_csv('', APPPATH . 'data/shipment_info_to_courier_aramex_cod_xml2csv.txt', TRUE, ',');
                    $file_content = $this->get_dex_service()->convert($out_xml, $out_csv);

                    break;

                case "ARAMEX":
                    if ($arr = $this->getShipmentDeliveryInfoDhl($value)) {
                        foreach ($arr as $row) {
                            $ar_address = @explode("|", $row->get_delivery_address());
                            $row->set_delivery_address1($ar_address[0]);
                            $row->set_delivery_address2($ar_address[1]);
                            $row->set_delivery_address3($ar_address[2]);
                            $row->set_qty($row->get_qty());
                            $row->set_prod_weight($row->get_prod_weight() * $row->get_qty());
                            $row->set_price($row->get_amount());
                            if ($row->get_tel() == "") {
                                $row->set_tel("0");
                            }
                            if ($row->get_delivery_company() == "") {
                                $row->set_delivery_company($row->get_delivery_name());
                            }

                            $prod_obj = $this->getDao('Product')->get(array("sku" => $row->get_prod_sku()));
                            $declared_value = $this->get_declared_value($prod_obj, $row->get_delivery_country_id(), $row->get_price());
                            $row->set_declared_value(round($declared_value * $row->get_rate(), 2));

                            if ($country_obj = $this->getDao('Country')->get(array("id" => $row->get_delivery_country_id()))) {
                                $country_name = $country_obj->get_name();
                                $row->set_delivery_country_id($country_name);
                            }
                            $data_out[] = $row;
                            $counter++;
                        }
                    }
                    $out_xml = new Vo_to_xml($data_out, '');
                    $out_csv = new Xml_to_csv('', APPPATH . 'data/shipment_info_to_courier_aramex_xml2csv.txt', TRUE, ',');
                    $file_content = $this->get_dex_service()->convert($out_xml, $out_csv);
                    break;

                //#2507 add DPD_NL courier feed
                case "DPD_NL":
                    if ($arr = $this->getShipmentDeliveryInfoDhl($value)) {
                        foreach ($arr as $row) {
                            $ar_address = @explode("|", $row->get_delivery_address());
                            if ($ar_address[0] == '') {
                                $ar_address[0] = '.';
                            }
                            if ($ar_address[1] == '') {
                                $ar_address[1] = '.';
                            }
                            $row->set_delivery_address2($ar_address[1]);
                            $row->set_delivery_address1($ar_address[0]);
                            $row->set_shipping_date(date('d.m.Y'));

                            $row->set_prod_weight($row->get_prod_weight() * $row->get_qty());

                            if ($row->get_tel() == "") {
                                $row->set_tel(".");
                            }

                            if ($row->get_delivery_postcode() == "") {
                                $row->set_delivery_postcode(".");
                            }

                            $delivery_country_id = $row->get_delivery_country_id();
                            //If delivery country is France,  pls enter FR.
                            //If delivery country is Nederland,  pls enter NL. For other country, pls enter EN
                            if (!in_array($delivery_country_id, array('FR', 'NL'))) {
                                $row->set_delivery_country_id_2('EN');
                            } else {
                                $row->set_delivery_country_id_2($delivery_country_id);
                            }

                            $data_out[] = $row;
                            $counter++;
                        }
                    }
                    $out_xml = new Vo_to_xml($data_out, '');
                    $out_csv = new Xml_to_csv('', APPPATH . 'data/shipment_info_to_courier_DPD_NL_xml2csv.txt', TRUE, ',');
                    $file_content = $this->get_dex_service()->convert($out_xml, $out_csv);
                    break;
                // #2715 MRW's IT integration
                case "MRW":
                    if ($arr = $this->getShipmentDeliveryInfoCourier($value)) {
                        $seqdao = $this->get_sequence_service()->get_dao();
                        $seqdao->set_seq_name("mrw_tracking_id");

                        $tracking_id = $seqdao->seq_next_val();

                        $max_tracking_id = "26931929951";

                        if (strcmp($tracking_id, $max_tracking_id) == 0)
                            mail("itsupport@eservicesgroup.net", "Invalid tracking id of MRW Courier", "Please note that the max value of tracking id have been used for the order sn_no " . $value, 'From: website@valuebasket.com');

                        if (!$tracking_id)
                            $tracking_id = "";

                        $totalweight = 0.0;
                        $totalprice = 0;
                        $totalqty = 0;

                        foreach ($arr as $row) {
                            $totalweight += $row->get_prod_weight() * $row->get_qty();
                            $totalprice += $row->get_price() * $row->get_qty();
                            $totalqty += $row->get_qty();
                        }

                        foreach ($arr as $row) {
                            $ar_address = @explode("|", $row->get_delivery_address());
                            $ar_address = str_replace(";", " ", $ar_address);
                            $row->set_delivery_address(trim(@implode(" ", $ar_address)));
                            $row->set_shipping_date(date('dmY'));
                            $tel = $row->get_tel();
                            if (strlen($tel) > 9)
                                $tel = substr($tel, -9);

                            $file_content .= "\"H\";" .
                                "\"E\";" .
                                "\"0001{$row->get_so_no()}\";" .
                                "\"00826\";" .
                                "\"\";" .
                                "\"{$row->get_shipping_date()}\";" .
                                "\"ALMACEN 1\";" .
                                "\"" . ((strlen($row->get_delivery_name()) > 30) ? substr($row->get_delivery_name(), 0, 30) : $row->get_delivery_name()) . "\";" .
                                "\"\";" .
                                "\"\";" .
                                "\"" . ((strlen($row->get_delivery_address()) > 80) ? substr($row->get_delivery_address(), 0, 80) : $row->get_delivery_address()) . "\";" .
                                "\"" . ((strlen($row->get_delivery_city()) > 20) ? substr($row->get_delivery_city(), 0, 20) : $row->get_delivery_city()) . "\";" .
                                "\"{$row->get_delivery_postcode()}\";" .
                                "\"{$tel}\";" .
                                "\"{$tel}\";" .
                                "\"" . ((strlen($row->get_delivery_city()) > 20) ? substr($row->get_delivery_city(), 0, 20) : $row->get_delivery_city()) . "\";" .
                                "\"{$row->get_delivery_country_id()}\";" .
                                "\"\";" .
                                "\"{$totalweight}\";" .
                                "\"\";" .
                                "\"1\";" .
                                "\"N\";" .
                                "\"\";" .
                                "\"" . ((strlen($row->get_cc_desc()) > 24) ? substr($row->get_cc_desc(), 0, 24) : $row->get_cc_desc()) . "\";" .
                                "\"0{$tracking_id}\";" .
                                "\"D\";" .
                                "\"\";" .
                                "\"{$row->get_client_email()}\";" .
                                "\"VALUEBASKET\"\r\n" .
                                "\"L\";" .
                                "\"0001{$row->get_so_no()}\";" .
                                "\"00826BULTOPAXD\";" .
                                "\"{$totalqty}\";" .
                                "\"{$totalprice}\";" .
                                "\"0\";" .
                                "\"00826\";" .
                                "\"\"\r\n";

                            $seqdao->update_seq($tracking_id);
                            break;
                        }
                    }
                    break;
                default:
                    $arr = $this->getShipmentDeliveryInfo($value, 'dispatch_list_dto'); // Pass the SO #

                    if (!$arr || ($no_of_line = count($arr)) == 0) {
                        continue;  // No data is found.  It shouldn't happen.
                    }

                    $counter = 1;

                    foreach ($arr as $row) {
                        $row->set_total_item_count($no_of_line);
                        $row->set_item_no($counter);
                        $row->set_subtotal(number_format(
                            $row->get_unit_price() * $row->get_qty()
                            , 2, '.', ''));
                        $row->set_actual_cost(number_format(
                            $row->get_amount() - $row->get_offline_fee()
                            , 2, '.', ''));
                        $row->set_bill_detail('N'); // Always 'N' at the beginning.

                        if ($counter > 1) {
                            $row->set_ship_option('');
                            $row->set_delivery_charge(0.00);
                            $row->set_promotion_code('');
                            $row->set_amount(0.00);
                            $row->set_delivery_type_id('');
                            $row->set_actual_cost(0.00);
                        }
                        $data_out[] = $row;
                        $counter++;
                    }
                    $out_xml = new Vo_to_xml($data_out, '');
                    $out_csv = new Xml_to_csv('', APPPATH . 'data/shipment_info_to_courier_xml2csv.txt', TRUE, ',');
                    $file_content = $this->get_dex_service()->convert($out_xml, $out_csv);
//create file for dispatch list import
                    $counter = 1;
                    if (($courier == "AMS")
                        || ($courier == "ILG")
                    ) {
                        foreach ($arr as $row) {
                            $row->set_total_item_count($no_of_line);
                            $row->set_item_no($counter);
                            $row->set_subtotal(number_format($row->get_unit_price() * $row->get_qty(), 2, '.', ''));
                            $row->set_actual_cost(number_format($row->get_amount() - $row->get_offline_fee(), 2, '.', ''));

                            if ($counter > 1) {
                                # code
                            }
                            $row->set_warehouse_id("VB_" . $courier);
                            $row->set_bin("STAG");
                            $dispatch_data_out[] = $row;
                            $counter++;
                        }
                        $dispatch_out_xml = new Vo_to_xml($dispatch_data_out, '');
                        $dispatch_out_csv = new Xml_to_csv('', APPPATH . 'data/dispatch_list_xml2csv.txt', TRUE, ',');
                        $dispatch_content = $this->get_dex_service()->convert($dispatch_out_xml, $dispatch_out_csv);
                    }
                    break;
            }
        }

        if ($file_content != "") {
            $filename = "so_delivery_" . date("YmdHis");
            $path = $output_path;

//create file for dispatch list import
            if (($courier == "AMS")
                || ($courier == "ILG")
                || ($courier == "IM")
                || ($courier == "RMR")
            ) {
                $dispatch_path = $this->getDao('Config')->valueOf('dispath_list_path');
                $this->_create_folder($dispatch_path, date('Y'), date('F'));
                $dispatch_filename = $courier . "_" . $filename . ".csv";
                $dispatch_path = $dispatch_path . date('Y') . "/" . date('F') . "/" . $courier . "/";
                if ($fp = @fopen($dispatch_path . $dispatch_filename, 'w')) //              if ($fp = @fopen($path . $dispatch_filename, 'w'))
                {
                    @fwrite($fp, $dispatch_content);
                    @fclose($fp);
                }
            }

            if ($courier == "MRW") {
                $filename = "IADI_00826_1528_";
                $filename .= date('YmdHis');
                $filename .= ".csv";
            } else
                $filename .= ".txt";

            if ($fp = @fopen($path . $filename, 'w')) {
                @fwrite($fp, $file_content);
                @fclose($fp);

                return $filename;
            }
        }

        return;
    }

    public function getShipmentDeliveryInfoDhl($so_no)
    {
        return $this->getDao('So')->getShipmentDeliveryInfoDhl($so_no);
    }

    public function getShipmentDeliveryInfoCourier($so_no)
    {
        return $this->getDao('So')->getShipmentDeliveryInfoCourier($so_no);
    }

    public function getShipmentDeliveryInfoCourierForTnt($so_no)
    {
        return $this->getDao('So')->getShipmentDeliveryInfoCourierForTnt($so_no);
    }

    public function getShipmentDeliveryInfo($so_no = 'SO000001', $classname = 'shipment_info_to_courier_dto')
    {
        return $this->getDao('So')->getShipmentDeliveryInfo($so_no, $classname);
    }

    public function get_sequence_service()
    {
        return $this->sequence_service;
    }

    public function set_sequence_service($value)
    {
        $this->sequence_service = $value;
    }

    private function _create_folder($upload_path, $this_year, $this_month)
    {
        $full_path = $upload_path . $this_year;
        if (!file_exists($full_path)) {
            mkdir($full_path, 0775);
        }
        $full_path = $upload_path . $this_year . "/" . $this_month;
        if (!file_exists($full_path)) {
            mkdir($full_path, 0775);
        }
        $full_path = $upload_path . $this_year . "/" . $this_month . "/AMS";
        if (!file_exists($full_path)) {
            mkdir($full_path, 0775);
        }
        $full_path = $upload_path . $this_year . "/" . $this_month . "/ILG";
        if (!file_exists($full_path)) {
            mkdir($full_path, 0775);
        }
        $full_path = $upload_path . $this_year . "/" . $this_month . "/IM";
        if (!file_exists($full_path)) {
            mkdir($full_path, 0775);
        }
        $full_path = $upload_path . $this_year . "/" . $this_month . "/RMR";
        if (!file_exists($full_path)) {
            mkdir($full_path, 0775);
        }
    }

    public function generate_metapack_file($checked = array(), $courier = "")
    {
        if ($courier == "" || count($checked) == 0) {
            return;
        }

        $file_content = "";
        $output_path = $this->getDao('Config')->valueOf('metapack_path');
        foreach ($checked as $key => $value) {
            $so_obj = $this->getDao('So')->get(array("so_no" => $value));
            $client_obj = $this->getDao('Client')->get(array("id" => $so_obj->get_client_id()));
            $soa_obj = $this->getDao('SoAllocate')->get(array("so_no" => $value));
            if ($country_obj = $this->getDao('Country')->get(array("id" => $so_obj->get_delivery_country_id()))) {
                $delcountry = $country_obj->get_name();
            }
            $fullname = $so_obj->get_delivery_name();
            $ordernum = $soa_obj->get_sh_no();
            $delpostcode = $so_obj->get_delivery_postcode();
            $delemail = $client_obj->get_email();
            $mobiletel = $client_obj->get_tel_1() . " " . $client_obj->get_tel_2() . " " . $client_obj->get_tel_3();
            $delcity = $so_obj->get_delivery_city();
            $delstate = $so_obj->get_delivery_state();
            $delcountry_id = $so_obj->get_delivery_country_id();
            $weight = $so_obj->get_weight();

            $append = "";
            $z = explode("|", $so_obj->get_delivery_address());
            $cnt = count($z);
            for ($j = $cnt; $j < 3; $j++) {
                $append .= "~";
            }

            switch ($courier) {
                case 'DPD':
                    $deladdress = explode("|", $so_obj->get_delivery_address());
                    $deladdr1 = $deladdress[0];
                    $deladdr2 = $deladdress[1] . ", " . $deladdress[2];
                    $deladdr2 = ereg_replace("^, ", "", $deladdr2);
                    $deladdr2 = ereg_replace(", $", "", $deladdr2);
                    if (in_array($so_obj->get_delivery_country_id(), array('IE', 'GB', 'UK'))) {
                        $dpdobject = "1";
                        $dpdservicecode = "32";
                        if ($so_obj->get_delivery_country_id() == IE) {
                            $dpdservicecode = "11";
                            if (stristr($deladdress, "dublin") === FALSE) {
                                $delpostcode = "ZZ75";
                            } else {
                                $delpostcode = "ZZ71";
                            }
                        }
                    } else {
                        $dpdobject = "5";
                        $dpdservicecode = "19";

                    }
                    $file_content .= $ordernum . "|" . $dpdobject . "|" . $fullname . "|" . $deladdr1 . "|" . $deladdr2 . "|" . $delcity . "|" . $delstate . "|" . $delpostcode . "|||1|" . $dpdservicecode . "|" . $delcountry_id . "|" . $delemail . "|" . $mobiletel . "\r\n";
                    break;

                case 'RM1st':

                    $deladdress = str_replace("|", "~", $so_obj->get_delivery_address()) . $append;
                    $file_content .= "~2~~~" . $fullname . "~" . $fullname . "~" . $deladdress . "~~~~" . $delpostcode . "~~~~~456098002~~~STL01~~" . $ordernum . "~1~100~~~P~~~~\r\n";
                    break;

                case 'RM1stRec';
                    $deladdress = str_replace("|", "~", $so_obj->get_delivery_address()) . $append;
                    $file_content .= "~2~~~" . $fullname . "~" . $fullname . "~" . $deladdress . "~~~~" . $delpostcode . "~~~~~456098002~~~STL01~~" . $ordernum . "~1~100~11~~P~~~~\r\n";
                    break;

                case 'RMSD':
                    $deladdress = str_replace("|", "~", $so_obj->get_delivery_address()) . $append;
                    $file_content .= "~2~~~" . $fullname . "~" . $fullname . "~" . $deladdress . "~~~~" . $delpostcode . "~~~~~456098002~~~SD101~~" . $ordernum . "~1~1000~~~P~~~~\r\n";
                    break;

                case 'RMAir':
                    list($addr1, $addr2, $addr3) = explode("|", $so_obj->get_delivery_address());
                    $addr1 = str_replace("|", ",", $so_obj->get_delivery_address());
                    $addr2 = ($delcity ? $delcity : "-");
                    $addr3 = ($delstate ? $delstate : "-");
                    $deladdress = $addr1 . "~" . $addr2 . "~" . $addr3;
                    //$deladdress = str_replace("|","~",$so_obj->get_delivery_address());
                    $file_content .= "~2~~~" . $fullname . "~~" . $deladdress . "~~~~" . $delpostcode . "~" . $delcountry_id . "~~~~~~~~~" . $ordernum . "~~" . $weight . "~~\r\n";
                    break;

                case 'RMInt':
                    list($addr1, $addr2, $addr3) = explode("|", $so_obj->get_delivery_address());
                    $addr1 = str_replace("|", ",", $so_obj->get_delivery_address());
                    $addr2 = ($delcity ? $delcity : "-");
                    $addr3 = ($delstate ? $delstate : "-");
                    $deladdress = $addr1 . "~" . $addr2 . "~" . $addr3;
                    //$deladdress = str_replace("|","~",$so_obj->get_delivery_address());
                    $file_content .= "~2~~~" . $fullname . "~~" . $deladdress . "~~~~" . $delpostcode . "~" . $delcountry_id . "~~~~~~~~~" . $ordernum . "~~" . $weight . "~~\r\n";
                    break;

                default:
                    break;
            }
        }
        if ($file_content != "") {
            if (ereg("^RM", $courier)) {
                $file_content = "~0~1~" . date("ymd") . "~" . date("His") . "~" . count($checked) . "~~\r\n" . $file_content . "~9~1~" . count($checked) . "~~";
            }

            $filename = $courier . "_" . date("YmdHis") . ".txt";
            //$path = $this->getDao('Config')->valueOf('meatpack_path');
            $path = $output_path;
            if ($fp = @fopen($path . $filename, 'w')) {
                @fwrite($fp, $file_content);
                @fclose($fp);

                return $filename;
            } else {
                return;
            }
        }

        return;
    }

    public function get_hold_history($so_no = "")
    {
        if ($so_no == "") {
            return FALSE;
        }

        return $this->getDao('SoHoldReason')->getListWithUname(array("so_no" => $so_no), array("orderby" => "create_on DESC"));
    }

    public function fire_dispatch($so_obj, $sh_no, $get_email_html = FALSE)
    {
        # sbf #3746 don't include complementary accessory on front end
        $ca_catid_arr = implode(',', $this->get_ca_service()->get_accessory_catid_arr());

        $country = $this->getDao('Country')->get(array("id" => $so_obj->get_delivery_country_id()));
        $so_items = $this->getDao('SoItem')->getItemsWithName(array("so_no" => $so_obj->get_so_no(), "p.cat_id NOT IN ($ca_catid_arr)" => NULL));
        $client = $this->getDao('Client')->get(array("id" => $so_obj->get_client_id()));
        $currency_obj = $this->getDao('Currency')->get(['id' => $so_obj->get_currency_id()]);
        $sh_obj = $this->getDao('SoShipment')->get(['sh_no' => $sh_no]);
        if ($sh_obj->get_courier_id()) {
            if ($courier_obj = $this->getDao('Courier')->get(array("id" => $sh_obj->get_courier_id()))) {
                $courier_id = $courier_obj->get_courier_name();
                if ($sh_obj->get_courier_id() == 'DPD_NL' && $sh_obj->get_tracking_no() && $courier_obj->get_tracking_link()) {
                    $tracking_no = '<a href="' . $courier_obj->get_tracking_link() . $sh_obj->get_tracking_no() . '" target="_blank">' . $sh_obj->get_tracking_no() . '</a>';
                    $track_num = $sh_obj->get_tracking_no();
                } else {
                    $tracking_no = (empty($sh_obj) ? '' : $sh_obj->get_tracking_no());
                    $track_num = $sh_obj->get_tracking_no();
                }
            }
        }
        $platform_id = $so_obj->get_platform_id();
        $pbv_obj = $this->getDao('PlatformBizVar')->get(array('selling_platform_id' => $platform_id));
        $lang_id = $pbv_obj->get_language_id();

        $replace["so_no"] = $so_obj->get_so_no();

        $split_so_group = $so_obj->get_split_so_group();
        if (isset($split_so_group) && $split_so_group != $so_obj->get_so_no()) {
            $replace["so_no"] = $split_so_group . '/' . $so_obj->get_so_no();
        }

        $replace["client_id"] = $so_obj->get_client_id();
        $replace["forename"] = $client->get_forename();
        $replace["email"] = $client->get_email();
        $replace["bill_name"] = $so_obj->get_bill_name();
        $replace["purchase_date"] = $so_obj->get_order_create_date();
        $replace["promotion_code"] = $so_obj->get_promotion_code();
        $replace["delivery_days"] = "2-5";
        $replace["delivery_name"] = $so_obj->get_delivery_name();
        $replace["currency_id"] = $so_obj->get_currency_id();
        if (($so_obj->get_delivery_country_id() == 'ES') || ($so_obj->get_delivery_country_id() == 'PT')) {
            $replace["aftership_url"] = 'envio.aftership.com';
        } elseif (($so_obj->get_delivery_country_id() == 'FR') || ($so_obj->get_delivery_country_id() == 'BE')) {
            $replace["aftership_url"] = 'suivi.aftership.com';
        } elseif ($so_obj->get_delivery_country_id() == 'IT') {
            $replace["aftership_url"] = 'spedizione.aftership.com';
        } else {
            $replace["aftership_url"] = 'shipment.aftership.com';
        }

        $replace["delivery_address_text"] = str_replace("|", "\n", str_replace("||", "|", $so_obj->get_delivery_address()))
            . "\n" . $so_obj->get_delivery_city() . " " . $so_obj->get_delivery_state()
            . " " . $so_obj->get_delivery_postcode() . "\n" . $country->get_name();
        $replace["delivery_address"] = nl2br($replace["delivery_address_text"]);
        $replace["billing_address_text"] = str_replace("|", "\n",
                $so_obj->get_bill_address()) . "\n" . $so_obj->get_bill_city() . " " . $so_obj->get_bill_state()
            . " " . $so_obj->get_bill_postcode() . "\n" . $country->get_name();
        $replace["billing_address"] = nl2br($replace["billing_address_text"]);
        $replace['currency_sign'] = (empty($currency_obj) ? $so_obj->get_currency_id() : $currency_obj->get_sign());
        $currency_sign = (empty($currency_obj) ? $so_obj->get_currency_id() : $currency_obj->get_sign());
        $replace["amount"] = platform_curr_format($platform_id, $so_obj->get_amount(), 0);
        $replace["timestamp"] = date("d/m/Y");
        $replace["sh_no"] = $sh_no;

        // show text only if it is split order
        $replace["partial_ship_text"] = "";
        if (isset($split_so_group) && $split_so_group != $so_obj->get_so_no()) {
            switch ($lang_id) {
                case 'en':
                    $replace["partial_ship_text"] = 'Your order was partially split at no extra cost to ensure all item(s) purchased are received at the soonest available opportunity. The remaining items of your order will ship soon. You may refer to "Useful Information" section below to review /track your order progress.';
                    break;

                case 'es':
                    $replace["partial_ship_text"] = 'Hemos dividido tu pedido sin coste adicional para que puedas recibir los artículos lo más pronto posible. Los productos que aún no han sido expedidos se enviarán muy pronto. Puedes consultar el estado del pedido más abajo.';
                    break;

                case 'fr':
                    $replace["partial_ship_text"] = 'Votre commande a été divisée sans aucun coût additionnel, afin d’assurer que tous les articles achetés vous soient livrés dans les plus brefs délais possibles. Le reste des articles de votre commande sera expédié prochainement. Vous pouvez consulter la rubrique « Informations Utiles » ci-dessous afin de suivre l’avancement de votre commande.';
                    break;

                case 'it':
                    $replace["partial_ship_text"] = 'Abbiamo diviso il tuo ordine, senza costi aggiuntivi per consegne separate, perchè tu possa ricevere quanto prima i prodotti che hai scelto. I prodotti che ancora non sono stati spediti, lo saranno molto presto. Puoi controllare lo stato del tuo ordine qui in basso.';
                    break;

                case 'ru':
                    $replace["partial_ship_text"] = 'Ваш заказ был разделен на несколько частей (без всяких дополнительных платежей), чтобы доставить его Вам как можно быстрее. Оставшиеся товары будут отправилены в самое ближайшее время. Вы можете посмотреть детали и прогресс отправки в разделе "Полезная информация".';
                    break;

                case 'pl':
                    $replace["partial_ship_text"] = 'Twoje zamówienie zostało podzielone bez dodatkowych kosztów, aby mieć pewność, że wszystkie zakupione produkty zostały dostarczone w jak najszybszym czasie. Pozostałe produkty z Twojego zamówienia zostaną wysłane w krótce. Możesz sprawdzić „Przydatne Informacje” w części poniżej, aby sprawdzić/śledzić postepy Twojego zamówienia.';
                    break;

                default:
                    $replace["partial_ship_text"] = '';
                    break;
            }
        }

        switch ($lang_id) {
            case 'de':
//              $email_sender = 'no-reply@valuebasket.com';
                $replace["tracking_id_label"] = (empty($tracking_no) ? '' : '<b>Versandnummer:</b>'); // 'Tracking ID';
                $replace["courier_id_label"] = empty($courier_id) ? '' : "Kurier:"; //Courier ID
                break;

            case 'fr':
//              $email_sender = 'no-reply@valuebasket.com';
                $replace["tracking_id_label"] = (empty($tracking_no) ? '' : "<b>Numero de Suivi:</b>"); // 'Tracking ID';
                $replace["courier_id_label"] = empty($courier_id) ? '' : "Courrier:"; //Courier ID
                break;

            case 'en':
//              $email_sender = 'no-reply@valuebasket.com';
                $replace["tracking_id_label"] = (empty($tracking_no) ? '' : '<b>Tracking ID:</b>'); // 'Tracking ID';
                $replace["courier_id_label"] = empty($courier_id) ? '' : "Courier:"; //Courier ID
                break;

            case 'es':
//              $email_sender = 'no-reply@valuebasket.com';
                $replace["tracking_id_label"] = (empty($tracking_no) ? '' : '<b>Numero de Seguimiento de Envio:</b>'); // 'Tracking ID';
                $replace["courier_id_label"] = empty($courier_id) ? '' : "Correo:"; //Courier ID
                break;

            case 'pt':
//              $email_sender = 'no-reply@valuebasket.com';
                $replace["tracking_id_label"] = (empty($tracking_no) ? '' : '<b>Numero de Rastreamento:</b>'); // 'Tracking ID';
                $replace["courier_id_label"] = empty($courier_id) ? '' : "Correio:"; //Courier ID
                break;

            case 'nl':
//              $email_sender = 'no-reply@valuebasket.com';
                $replace["tracking_id_label"] = (empty($tracking_no) ? '' : '<b>Traceernummer:</b>'); // 'Tracking ID';
                $replace["courier_id_label"] = empty($courier_id) ? '' : "Koerier:"; //Courier ID
                break;

            case 'ja':
//              $email_sender = 'no-reply@valuebasket.com';
                $replace["tracking_id_label"] = (empty($tracking_no) ? '' : '<b>>追跡番号:</b>'); // 'Tracking ID';
                $replace["courier_id_label"] = empty($courier_id) ? '' : "宅配便"; //Courier ID
                break;

            case 'it':
//              $email_sender = 'no-reply@valuebasket.com';
                $replace["tracking_id_label"] = (empty($tracking_no) ? '' : '<b>Numero di Spedizione:</b>'); // 'Tracking ID';
                $replace["courier_id_label"] = empty($courier_id) ? '' : "Corriere:"; //Courier ID
                break;

            case 'pl':
//              $email_sender = 'no-reply@valuebasket.com';
                $replace["tracking_id_label"] = (empty($tracking_no) ? '' : '<b>Numerze przesyłki:</b>'); // 'Tracking ID';
                $replace["courier_id_label"] = empty($courier_id) ? '' : "Kurier:"; //Courier ID
                break;

            case 'da':
//              $email_sender = 'no-reply@valuebasket.com';
                $replace["tracking_id_label"] = (empty($tracking_no) ? '' : '<b>Sporings-ID:</b>'); // 'Tracking ID';
                $replace["courier_id_label"] = empty($courier_id) ? '' : "Courier:"; //Courier ID
                break;

            case 'ko':
//              $email_sender = 'no-reply@valuebasket.com';
                $replace["tracking_id_label"] = (empty($tracking_no) ? '' : '<b>ID:</b>'); // 'Tracking ID';
                $replace["courier_id_label"] = empty($courier_id) ? '' : ":"; //Courier ID
                break;

            case 'tr':
//              $email_sender = 'no-reply@valuebasket.com';
                $replace["tracking_id_label"] = (empty($tracking_no) ? '' : '<b>Takip No:</b>'); // 'Tracking ID';
                $replace["courier_id_label"] = empty($courier_id) ? '' : "Kurye:"; //Courier ID
                break;

            case 'sv':
//              $email_sender = 'no-reply@valuebasket.com';
                $replace["tracking_id_label"] = (empty($tracking_no) ? '' : '<b>Sparnings ID:</b>'); // 'Tracking ID';
                $replace["courier_id_label"] = empty($courier_id) ? '' : "Kurir:"; //Courier ID
                break;

            case 'no':
//              $email_sender = 'no-reply@valuebasket.com';
                $replace["tracking_id_label"] = (empty($tracking_no) ? '' : '<b>Sporings-ID:</b>'); // 'Tracking ID';
                $replace["courier_id_label"] = empty($courier_id) ? '' : "Kurer:"; //Courier ID
                break;

            case 'pt-br':
//              $email_sender = 'no-reply@valuebasket.com';
                $replace["tracking_id_label"] = (empty($tracking_no) ? '' : '<b>Numero de rastreamento:</b>'); // 'Tracking ID';
                $replace["courier_id_label"] = empty($courier_id) ? '' : "Entregador:"; //Courier ID
                break;

            case 'ru':
//              $email_sender = 'no-reply@valuebasket.com';
                $replace["tracking_id_label"] = (empty($tracking_no) ? '' : '<b>?оме? дл? о??леживани? г??за:</b>'); // 'Tracking ID';
                $replace["courier_id_label"] = empty($courier_id) ? '' : "Сл?жба до??авки:"; //Courier ID
                break;

            default:
//              $email_sender = 'no-reply@valuebasket.com';
                $replace["tracking_id_label"] = (empty($tracking_no) ? '' : '<b>Tracking ID:</b>'); // 'Tracking ID';
                $replace["courier_id_label"] = empty($courier_id) ? '' : "Courier:"; //Courier ID
                break;
        }
        include_once(APPPATH . "hooks/country_selection.php");
        $country_id = $pbv_obj->get_platform_country_id();
        $replace = array_merge($replace, Country_selection::get_template_require_text($lang_id, $country_id));
        $email_sender = "no-reply@" . strtolower($replace["site_name"]);
        $replace["support_email"] = $email_sender;
        $replace["image_url"] = $this->getDao('Config')->valueOf("default_url");
        $replace["logo_file_name"] = $this->getDao('Config')->valueOf("logo_file_name");
        if (!empty($courier_id)) {
            $replace["courier_id"] = $courier_id;
            $replace["tracking_id"] = $tracking_no;
            $replace["track_no"] = $track_num;
            $courier_obj = $this->getDao('Courier')->get(array("id" => $courier_id));
            if ($courier_obj) {
                $replace["tracking_link"] = $courier_obj->get_tracking_link();
            }
        } else {
            $replace["courier_id"] = "";
            $replace["courier_id_label"] = "";
            $replace["tracking_id"] = "";
            $replace["tracking_id_label"] = "";
        }

        $sub_total = $total_vat = $total = 0;
        $i = 1;

        include_once(APPPATH . "helpers/image_helper.php");
        foreach ($so_items as $item) {
            $cur_qty = $item->get_qty();
            $cur_vat_total = $item->get_vat_total();
            $cur_amount = $item->get_amount();
            $price = $item->get_unit_price();
            $cur_sub_total = $price * $cur_qty;
            $sub_total += $cur_sub_total;
            $total_vat += $cur_vat_total;
            $total += $cur_amount;
            $space_for_item_name = 52 - strlen($item->get_name());
            if ($space_for_item_name > 0) {
                $item_name_tab = "\t";
                $num_of_tab = floor($space_for_item_name / 4);

                for ($i = 0; $i <= $num_of_tab; $i++) {
                    $item_name_tab .= "\t";
                }
            }

            $replace["so_items_text"] .=
                $item->get_name() . $item_name_tab . $cur_qty . "\t" . platform_curr_format($platform_id, $price, 0) . "\t" . platform_curr_format($platform_id, $cur_sub_total, 0) . "\r\n";
            $replace["so_items"] .=
                "<tr>
                    <td style='padding:4px 20px; color:#444; font-family:Arial; font-size: 12px;'>" . $item->get_name() . "</td>
                    <td align='left' valign='top' style='padding:4px 10px; color:#444; font-family:Arial; font-size: 12px;'>$cur_qty</td>
                    <td align='left' valign='top' style='padding:4px 10px; color:#444; font-family:Arial; font-size: 12px;'>" . $currency_sign . " " . platform_curr_format($platform_id, $price, 0) . "</td>
                    <td align='left' valign='top' style='padding:4px 10px; color:#444; font-family:Arial; font-size: 12px;'>" . $currency_sign . " " . platform_curr_format($platform_id, $cur_sub_total, 0) . "</td>
                </tr>\n";

            $replace["so_items_desc"] .= "<tr><td>$cur_qty x " . $item->get_name() . "</td></tr>\n";

            $i++;
        }

        $dc = $so_obj->get_delivery_charge();
        $total += $dc;
        $replace["subtotal"] = platform_curr_format($platform_id, $sub_total, 0);
        $replace["total_vat"] = platform_curr_format($platform_id, $total_vat, 0);

        //#2182 add the processing fee
        $extobj = $this->getDao('SoExtend')->get(array("so_no" => $so_obj->get_so_no()));
        if ($extobj) {
            $processing_fee = $extobj->get_offline_fee();
        }

        if (is_null($processing_fee)) {
            $processing_fee = 0;
        }
        $replace["processing_fee"] = platform_curr_format($platform_id, $processing_fee, 0);
        //#2182 add the processing fee to the total fee
        $total += $processing_fee;
        $replace["total"] = platform_curr_format($platform_id, $total, 0);

        $dc = $so_obj->get_delivery_charge();
        $total += $dc;
        $dc_vat = $dc * ($so_obj->get_vat_percent() / (100 + $so_obj->get_vat_percent()));
        $dc_sub_total = $dc - $dc_vat;
        $replace["dc_sub_total"] = platform_curr_format($platform_id, $dc_sub_total, 0);
        $replace["dc_vat"] = platform_curr_format($platform_id, $dc_vat, 0);
        $replace["delivery_charge"] = platform_curr_format($platform_id, $dc, 0);
        $replace["total_sub_total"] = platform_curr_format($platform_id, $sub_total + $dc_sub_total, 0);
        $replace["total_total_vat"] = platform_curr_format($platform_id, $total_vat + $dc_vat, 0);

        $this->include_dto("Event_email_dto");
        $dto = new Event_email_dto();

        if ($delay_order = $this->get_delay_order_service()->is_delay_order($so_obj->get_so_no())) {
            $delay_type = $delay_order->status;
            $delay_email_dispatch_id = "";
            if ($delay_type == 1) {
                $delay_email_dispatch_id = 'minor_delay_dispatch_email';
            } elseif ($delay_type == 2) {
                $delay_email_dispatch_id = 'major_delay_dispatch_email';
            }

            $dto->set_event_id($delay_email_dispatch_id);
            $dto->set_mail_from("jenny.leung@valuebasket.com");
            $dto->set_mail_to($client->get_email());
            $dto->set_tpl_id($delay_email_dispatch_id);
            $dto->set_lang_id($lang_id);

            if (file_exists(APPPATH . "language/template_service/" . $lang_id . "/" . $delay_email_dispatch_id . ".ini")) {
                $data_arr = parse_ini_file(APPPATH . "language/template_service/" . $lang_id . "/" . $delay_email_dispatch_id . ".ini");
            }

            if (!is_null($data_arr)) {
                $replace = array_merge($replace, $data_arr);
            }
            $dto->set_replace($replace);

            if ($delay_order_obj = $this->get_delay_order_service()->get(array("so_no" => $so_obj->get_so_no()))) {
                $delay_status = $delay_type + 2;
                $delay_order_obj->set_status($delay_status);
                $this->get_delay_order_service()->get_dao()->update($delay_order_obj);
            }
        } else {

            if ($this->is_filfull_wow_email_criteria($so_obj->get_delivery_country_id(), $so_obj->get_so_no(), $replace['courier'])
                && is_file($this->config->valueOf("wow_tpl_path") . "wow_email.html")
            ) {
                # SBF #4168 - if fulfill wow criteria, send info to FIANET
                $this->get_review_fianet_service()->send_order_data($so_obj, $client);

                $dto->set_event_id("wow_email_dispatch");
                $dto->set_mail_from($email_sender);
                $dto->set_mail_to($client->get_email());
                // bcc send to eKomi
                if (!$this->getDao('SoHoldReason')->getNumRows(array("so_no" => $so_obj->get_so_no(), "reason IN ('change_of_address', 'cscc', 'csvv')" => null))) {
                    switch ($lang_id) {
                        case 'fr':
                            $dto->set_mail_bcc(array("36774-valuebasketcomfrfr-fr@connect.ekomi.de", "valuebasketbccemail@gmail.com", "ming@valuebasket.com", "a9a163bd@trustpilotservice.com"));
                            break;

                        case 'en':
                            $dto->set_mail_bcc(array("27426-valuebasket2-en@connect.ekomi.de", "valuebasketbccemail@gmail.com", "ming@valuebasket.com", "53b4e6ff@trustpilotservice.com"));
                            break;

                        case 'it':
                            $dto->set_mail_bcc(array("44780-valuebasketltd-en@connect.ekomi.de", "valuebasketbccemail@gmail.com", "ming@valuebasket.com", "70039a72@trustpilotservice.com"));
                            break;

                        case 'es':
                            $dto->set_mail_bcc(array("40754-valuebasketes-es@connect.ekomi.de", "valuebasketbccemail@gmail.com", "ming@valuebasket.com", "d4229d8b@trustpilotservice.com"));
                            break;

                        default:
                            break;
                    }
                }
                $bcc = $dto->get_mail_bcc();

                # sbf #4349
                switch ($platform_id) {
                    case 'WEBAU':
                    case 'WEBSG':
                    case 'WEBMY':
                    case 'WEBNZ':
                        $wow_bcc = array("wow-apac@valuebasket.com");
                        break;

                    case 'WEBES':
                    case 'WEBPT':
                    case 'WEBFI':
                        $wow_bcc = array("wow-es@valuebasket.com");
                        break;

                    case 'WEBFR':
                    case 'WEBBE':
                    case 'WEBCH':
                        $wow_bcc = array("wow-fr@valuebasket.com");
                        break;

                    case 'WEBIT':
                        $wow_bcc = array("wow-it@valuebasket.com");
                        break;

                    case 'WEBRU':
                    case 'WEBPL':
                        $wow_bcc = array("wow-ru@valuebasket.com");
                        break;

                    case 'WEBGB':
                    case 'WEBIE':
                        $wow_bcc = array("wow-gb@valuebasket.com", "valuebasket-com@b2b.reviews.co.uk");
                        break;

                    case 'WEBMT':
                        $wow_bcc = array("wow-gb@valuebasket.com");
                        break;

                    default:
                        # code...
                        break;
                }

                if ($wow_bcc) {
                    if ($bcc)
                        $bcc = array_merge($bcc, $wow_bcc);
                    else
                        $bcc = $wow_bcc;

                    $dto->set_mail_bcc($bcc);
                }

                $sendagain = "no-reply@feedback-valuebasket.com";

                if (($so_obj->get_bill_country_id() == 'GB') || ($so_obj->get_delivery_country_id() == 'GB')) {
                    $dto->set_mail_from($email_sender);
                    $dto->set_tpl_id("wow_email_dispatch_gb");
                    $dto->set_lang_id("en");
                } else {
                    $dto->set_tpl_id("wow_email_dispatch");
                    $dto->set_lang_id($lang_id);
                }
                $dto->set_replace($replace);
            } else {
                $dto->set_event_id("confirm_dispatch");
                $dto->set_mail_from($email_sender);
                $dto->set_mail_to($client->get_email());
                // bcc send to eKomi
                $dto->set_mail_bcc(array("valuebasketbccemail@gmail.com"));

                if (($so_obj->get_bill_country_id() == 'GB') || ($so_obj->get_delivery_country_id() == 'GB')) {
                    $dto->set_mail_from($email_sender);
                    $dto->set_tpl_id("confirm_dispatch_gb");
                    $dto->set_lang_id("en");
                } else {
                    $dto->set_tpl_id("confirm_dispatch");
                    $dto->set_lang_id($lang_id);
                }
                $dto->set_replace($replace);
            }

        }
        // attach invoice to dispatch email
        $data_path = $this->getDao('Config')->valueOf("data_path");
        $html = $this->get_invoice_content(array($so_obj->get_so_no()), 1);
        $so_no = $so_obj->get_so_no();
        $att_file = $this->get_pdf_rendering_srv()->convert_html_to_pdf($html, $data_path . "/invoice/Invoice_" . $so_no . ".pdf", "F", $lang_id);
        $replace["att_file"] = $att_file;
        $dto->set_replace($replace);

        if ($get_email_html === FALSE) {
            $this->eventService->fireEvent($dto, FALSE);
        } else {
            $email_msg = $this->eventService->fireEvent($dto, TRUE);
            return $email_msg;
        }

        unlink($att_file);
    }

    public function get_delay_order_service()
    {
        return $this->delay_order_service;
    }

    public function set_delay_order_service($value)
    {
        $this->delay_order_service = $value;
    }

    function is_filfull_wow_email_criteria($delivery_country_id, $so_no, &$replace_courier = Null)
    {
        //If country is MY, then skip
        $skip_wow_mail_country_arr = array('MY');

        if (in_array($delivery_country_id, $skip_wow_mail_country_arr)) {
            return false;
        }
        //SBF 5678 add courier NOT fulfill the wow_email criteria
        //if NOT fulfill the wow_email criteria, then skip
        $wow_mail_obj = $this->wow_email_service->get_so_dao()->get_wow_mail_list(array("so.so_no" => $so_no, "a.courier_id <> 'deutsch-post'" => null, "a.courier_id <> 'HK_Post'" => null, "a.courier_id <> 'hong-kong-post'" => null, "a.courier_id <> 'Quantium'" => null, "a.courier_id <> 'royal-mail'" => null, "a.courier_id <> 'singapore-post'" => null, "a.courier_id <> 'swiss-post'" => null, "a.courier_id <> 'uk-mail'" => null, "a.courier_id <> 'USPS'" => null, "a.courier_id <> 'USPSPM'" => null, "so.biz_type NOT IN ('SPECIAL', 'MANUAL', 'EBAY')" => null));

        if (!$wow_mail_obj) {
            return false;
        } else {
            $replace_courier = @call_user_func(array($wow_mail_obj[0], "get_courier_id"));
        }
        return true;
    }

    public function get_review_fianet_service()
    {
        return $this->review_fianet_service;
    }

    public function get_invoice_content($so_no_list = array(), $gen_pdf = 0)
    {
        # sbf #3746 don't include complementary accessory on front end
        $ca_catid_arr = implode(',', $this->get_ca_service()->get_accessory_catid_arr());

        $run = 0;
        $website_domain = $this->getDao('Config')->valueOf('website_domain');
        $website_domain = base_url();
        $total_cnt = count($so_no_list);
        $cursign_arr = $this->getDao('Currency')->getList([], ["limit" => -1]);

        if (count($so_no_list)) {
            $valid = 0;
            $content = "";
            if ($gen_pdf) {
                include_once APPPATH . "data/invoice_pdf.php";
            } else {
                include_once APPPATH . "data/invoice.php";
            }
            $exists_lang = array("da", "de", "en", "es", "fr", "it", "ja", "ko", "nl", "no", "pl", "pt", "pt-br", "ru", "sv", "tr");
            foreach ($exists_lang as $cur_lang_id) {
                include APPPATH . "language/ORD001001_" . $cur_lang_id . ".php";
                $ar_lang[$cur_lang_id] = $lang;
            }

            $clean_body = $body;
            foreach ($so_no_list as $obj) {
                $run++;
                $so_obj = $this->getDao('So')->get(array("so_no" => $obj));
                if (!$so_obj) {
                    continue;
                } else {
                    $cur_platform_id = $so_obj->get_platform_id();
                    $pbv_obj = $this->getDao('PlatformBizVar')->get(array("selling_platform_id" => $cur_platform_id));
                    $so_lang_id = $pbv_obj->get_language_id();
                    $data = array();
                    $data["platform_id"] = $cur_platform_id;

                    switch ($cur_platform_id) {
                        case "AMUS":
                            $data["isAmazon"] = 1;
                            $data["sales_email"] = "amazoncentral@valuebasket.com";
                            $data["csemail"] = "amazoncentral@valuebasket.com";
                            $data["return_email"] = "returns@valuebasket.com";
                            break;
                        case "AMDE":
                        case "AMFR":
                        case "AMUK":
                            $data["isAmazon"] = 1;
                            $data["sales_email"] = "amazoncentral@valuebasket.com";
                            $data["csemail"] = "amazoncentral@valuebasket.com";
                            $data["return_email"] = "returns@valuebasket.com";
                            break;
                        default:
                            $data["isAmazon"] = 0;
                            $data["sales_email"] = $this->get_sales_email($so_lang_id);
                            $data["csemail"] = $this->get_cs_support_email($so_lang_id);
                            $data["return_email"] = $this->get_return_email($so_lang_id);
                            break;
                    }

                    $itemlist = $this->getDao('SoItem')->getItemsWithName(array("so_no" => $obj, "p.cat_id NOT IN ($ca_catid_arr)" => NULL));
                    $so_ext_obj = $this->getDao('SoExtend')->get(array("so_no" => $obj));

                    $data["lang"] = $ar_lang[$so_lang_id];
                    $data["website_domain"] = base_url();
                    $data["cursign"] = $cursign = $cursign_arr[$so_obj->get_currency_id()];

                    $data["order_no"] = $so_obj->get_client_id() . "-" . $so_obj->get_so_no();
                    $data["amazon_order_no"] = $so_obj->get_platform_order_id();
                    $data["order_date"] = date("d/m/Y", strtotime($so_obj->get_order_create_date()));
                    $bcountry_obj = $this->getDao('Country')->get(array("id" => $so_obj->get_bill_country_id()));
                    list($bill_addr_1, $bill_addr_2, $bill_addr_3) = explode("|", $so_obj->get_bill_address());
                    $bstatezip = trim($so_obj->get_bill_state() . ", " . $so_obj->get_bill_postcode());
                    if ($bstatezip != ",") {
                        $bstatezip = ereg_replace("^, ", "", $bstatezip);
                        $bstatezip = ereg_replace(",$", "", $bstatezip) . "<br>";
                    } else {
                        $bstatezip = "";
                    }
                    $data["billing_name"] = $so_obj->get_bill_name();
                    $data["billing_address"] = ($so_obj->get_bill_company() == "" ? "" : $so_obj->get_bill_company() . "<br/>") . $bill_addr_1 . "<br/>" . ($bill_addr_2 == "" ? "" : $bill_addr_2 . "<br/>") . ($bill_addr_3 == "" ? "" : $bill_addr_3 . "<br/>") . $so_obj->get_bill_city() . "<br>" . $bstatezip . $bcountry_obj->get_name();
                    list($delivery_addr_1, $delivery_addr_2, $delivery_addr_3) = explode("|", $so_obj->get_delivery_address());
                    $dcountry_obj = $this->getDao('Country')->get(array("id" => $so_obj->get_delivery_country_id()));
                    $dstatezip = trim($so_obj->get_delivery_state() . ", " . $so_obj->get_delivery_postcode());
                    if ($dstatezip != ",") {
                        $dstatezip = ereg_replace("^, ", "", $dstatezip);
                        $dstatezip = ereg_replace(",$", "", $dstatezip) . "<br>";
                    } else {
                        $dstatezip = "";
                    }
                    $data["delivery_name"] = $so_obj->get_delivery_name();
                    $data["delivery_address"] = ($so_obj->get_delivery_company() == "" ? "" : $so_obj->get_delivery_company() . "<br/>") . $delivery_addr_1 . "<br/>" . ($delivery_addr_2 == "" ? "" : $delivery_addr_2 . "<br/>") . ($delivery_addr_3 == "" ? "" : $delivery_addr_3 . "<br/>") . $so_obj->get_delivery_city() . "<br>" . $dstatezip . $dcountry_obj->get_name();

                    $item_information = "";
                    $bvat = 0;
                    $vat = 0;
                    $sum = 0;
                    foreach ($itemlist as $item_obj) {

                        $prod_obj = $this->getDao('Product')->get(array("sku" => $item_obj->get_main_prod_sku()));

                        $amount_total = $item_obj->get_amount();
                        $vat_total = $item_obj->get_vat_total();
                        $amount_total_bvat = $amount_total - $vat_total;
                        $unit_price_bvat = round(($amount_total - $vat_total) / $item_obj->get_qty(), 2);
                        $tmp = $this->getDao('Product')->get(array("sku" => $item_obj->get_main_prod_sku()));
                        if ($gen_pdf) {
                            $imagepath = "/" . get_image_file($tmp->get_image(), 's', $tmp->get_sku());
                        } else {
                            $imagepath = base_url() . get_image_file($tmp->get_image(), 's', $tmp->get_sku());
                        }
                        $item_information .= '<tr>
                                                <td align="center"><img src="' . $imagepath . '"></td>
                                                <td align="left">' . $item_obj->get_prod_sku() . ' - ' . $item_obj->get_name() . '</td>
                                                <td align="right">' . platform_curr_format($cur_platform_id, $item_obj->get_unit_price()) . '</td>
                                                <td align="right">' . $item_obj->get_qty() . '</td>
                                                <td align="right"><b>' . platform_curr_format($cur_platform_id, $amount_total) . '</b></td>
                                            </tr>';
                        $bvat += $amount_total_bvat;
                        $vat += $vat_total;
                        $sum += $amount_total;
                    }
                    $data["item_information"] = $item_information;

                    $sum_total = platform_curr_round($cur_platform_id, $sum);
                    $sum_vat = platform_curr_round($cur_platform_id, $vat);
                    $sum_bvat = platform_curr_round($cur_platform_id, $bvat);
                    $data["sum_total"] = $sum_total;
                    $data["sum_vat"] = $sum_vat;
                    $data["sum_bvat"] = $sum_bvat;

                    $sid_bvat = "";
                    $sid_vat = "";
                    $sid = $so_obj->get_delivery_charge();
                    if ($so_ext_obj && $so_ext_obj->get_vatexempt() == "0") {
                        $sid_vat = $sid * $so_obj->get_vat_percent() / (100 + $so_obj->get_vat_percent());

                    } else {
                        $sid_vat = 0;
                    }
                    $sid = platform_curr_round($cur_platform_id, $sid);
                    $sid_vat = platform_curr_round($cur_platform_id, $sid_vat);
                    $sid_bvat = platform_curr_round($cur_platform_id, $sid - $sid_vat);
                    $data["currency"] = $so_obj->get_currency_id();
                    $data["promotion_code"] = $so_obj->get_promotion_code();
                    $data["sid"] = $sid;
                    $data["sid_vat"] = $sid_vat;
                    $data["sid_bvat"] = $sid_bvat;

                    $ofee = $ofee_vat = $ofee_bvat = 0;
                    $extobj = $this->getDao('SoExtend')->get(array("so_no" => $so_obj->get_so_no()));
                    if ($extobj) {
                        if ($extobj->get_offline_fee() > 0) {
                            $data["offline_fee"] = "<tr>
                                <td colspan='2'>&nbsp;</td>
                                <td colspan='2' align='right' bgcolor='#DDDDDD' style='border:1px solid #BBBBBB; border-width:0px 0px 1px 1px;'><b>" . $ar_lang[$so_lang_id]["offline_fee"] . "</b></td>
                                <td align='right' bgcolor='#F0F0F0' valign='top' style='border:1px solid #BBBBBB; border-width:0px 1px 1px 1px;'><b>" . platform_curr_format($cur_platform_id, $extobj->get_offline_fee()) . "</b></td>
                            </tr>";
                            $ofee = platform_curr_round($cur_platform_id, $extobj->get_offline_fee());
                            $ofee_vat = platform_curr_round($cur_platform_id, $ofee * $so_obj->get_vat_percent() / (100 + $so_obj->get_vat_percent()));
                            $ofee_bvat = platform_curr_round($cur_platform_id, $ofee - $ofee_vat);
                        } else if ($extobj->get_offline_fee() < 0) {
                            $data["offline_fee"] = "<tr>
                                <td colspan='2'>&nbsp;</td>
                                <td colspan='2' align='right' bgcolor='#DDDDDD' style='border:1px solid #BBBBBB; border-width:0px 0px 1px 1px;'><b>" . $ar_lang[$so_lang_id]["discount"] . "</b></td>
                                <td align='right' bgcolor='#F0F0F0' valign='top' style='border:1px solid #BBBBBB; border-width:0px 1px 1px 1px;'><b>" . platform_curr_format($cur_platform_id, $extobj->get_offline_fee()) . "</b></td>
                            </tr>";
                            $ofee = platform_curr_round($cur_platform_id, $extobj->get_offline_fee());
                            $ofee_vat = platform_curr_round($cur_platform_id, $ofee * $so_obj->get_vat_percent() / (100 + $so_obj->get_vat_percent()));
                            $ofee_bvat = platform_curr_round($cur_platform_id, $ofee - $ofee_vat);
                        } else {
                            $data["offline_fee"] = "";
                        }
                    }

                    $data["total"] = $so_obj->get_amount();
                    $data["total_vat"] = platform_curr_round($cur_platform_id, $data["total"] * $so_obj->get_vat_percent() / (100 + $so_obj->get_vat_percent()));
                    $data["total_bvat"] = platform_curr_round($cur_platform_id, $data["total"] - $data["total_vat"]);
                    if (!$data["payment_method"] = $this->get_so_payment_gateway($so_obj->get_so_no())) {
                        $data["payment_method"] = "N/A";
                    }

                    $content .= $this->get_invoice_body($data, $gen_pdf);
                    if ($run < $total_cnt) {
                        $content .= $pagebreak;
                    }
                    unset($data);
                    unset($lang);
                    $valid++;
                }
            }
            if ($valid) {
                return $header . $content . $footer;
            } else {
                return FALSE;
            }
        }
        return FALSE;
    }

    private function get_invoice_body($data = array(), $gen_pdf = 0)
    {
        foreach ($data as $key => $value) {
            ${$key} = $value;
        }

        if ($gen_pdf) {
            include APPPATH . "data/inv_body_pdf.php";
        } else {
            include APPPATH . "data/inv_body.php";
        }
        return $body;
    }

    public function get_pdf_rendering_srv()
    {
        return $this->pdf_rendering_srv;
    }

    public function fire_aftership_thank_you_email($so_obj, $sh_no, $ap_status)
    {

        $country = $this->getDao('Country')->get(array("id" => $so_obj->get_delivery_country_id()));
        $so_items = $this->getDao('SoItem')->getItemsWithName(array("so_no" => $so_obj->get_so_no(), "p.cat_id NOT IN ($ca_catid_arr)" => NULL));
        $client = $this->getDao('Client')->get(array("id" => $so_obj->get_client_id()));
        $currency_obj = $this->getDao('Currency')->get(['id' => $so_obj->get_currency_id()]);
        $sh_obj = $this->getDao('SoShipment')->get(['sh_no' => $sh_no]);
        if ($sh_obj->get_courier_id()) {
            if ($courier_obj = $this->getDao('Courier')->get(array("id" => $sh_obj->get_courier_id()))) {
                $courier_id = $courier_obj->get_courier_name();
                if ($sh_obj->get_courier_id() == 'DPD_NL' && $sh_obj->get_tracking_no() && $courier_obj->get_tracking_link()) {
                    $tracking_no = '<a href="' . $courier_obj->get_tracking_link() . $sh_obj->get_tracking_no() . '" target="_blank">' . $sh_obj->get_tracking_no() . '</a>';
                } else {
                    $tracking_no = (empty($sh_obj) ? '' : $sh_obj->get_tracking_no());
                }
            }
        }
        $platform_id = $so_obj->get_platform_id();
        $pbv_obj = $this->getDao('PlatformBizVar')->get(array('selling_platform_id' => $platform_id));
        $lang_id = $pbv_obj->get_language_id();

        $replace["so_no"] = $so_obj->get_so_no();
        $replace["client_id"] = $so_obj->get_client_id();
        $replace["forename"] = $client->get_forename();
        $replace["email"] = $client->get_email();
        $replace["bill_name"] = $so_obj->get_bill_name();
        $replace["purchase_date"] = $so_obj->get_order_create_date();
        $replace["promotion_code"] = $so_obj->get_promotion_code();
        $replace["delivery_days"] = $so_obj->get_expect_del_days();
        $replace["delivery_name"] = $so_obj->get_delivery_name();
        $replace["currency_id"] = $so_obj->get_currency_id();

        $replace["delivery_address_text"] = str_replace("|", "\n", str_replace("||", "|", $so_obj->get_delivery_address()))
            . "\n" . $so_obj->get_delivery_city() . " " . $so_obj->get_delivery_state()
            . " " . $so_obj->get_delivery_postcode() . "\n" . $country->get_name();
        $replace["delivery_address"] = nl2br($replace["delivery_address_text"]);
        $replace["billing_address_text"] = str_replace("|", "\n",
                $so_obj->get_bill_address()) . "\n" . $so_obj->get_bill_city() . " " . $so_obj->get_bill_state()
            . " " . $so_obj->get_bill_postcode() . "\n" . $country->get_name();
        $replace["billing_address"] = nl2br($replace["billing_address_text"]);

        $replace['currency_sign'] = (empty($currency_obj) ? $so_obj->get_currency_id() : $currency_obj->get_sign());
        $currency_sign = (empty($currency_obj) ? $so_obj->get_currency_id() : $currency_obj->get_sign());
        $replace["amount"] = platform_curr_format($platform_id, $so_obj->get_amount(), 0);
        $replace["timestamp"] = date("d/m/Y");
        $replace["sh_no"] = $sh_no;

        switch ($lang_id) {
            case 'de':
//              $email_sender = 'no-reply@valuebasket.com';
                $replace["tracking_id_label"] = (empty($tracking_no) ? '' : '<b>Versandnummer:</b>'); // 'Tracking ID';
                $replace["courier_id_label"] = empty($courier_id) ? '' : "Kurier:"; //Courier ID
                break;

            case 'fr':
//              $email_sender = 'no-reply@valuebasket.com';
                $replace["tracking_id_label"] = (empty($tracking_no) ? '' : "<b>Numero de Suivi:</b>"); // 'Tracking ID';
                $replace["courier_id_label"] = empty($courier_id) ? '' : "Courrier:"; //Courier ID
                break;

            case 'en':
//              $email_sender = 'no-reply@valuebasket.com';
                $replace["tracking_id_label"] = (empty($tracking_no) ? '' : '<b>Tracking ID:</b>'); // 'Tracking ID';
                $replace["courier_id_label"] = empty($courier_id) ? '' : "Courier:"; //Courier ID
                break;

            case 'es':
//              $email_sender = 'no-reply@valuebasket.com';
                $replace["tracking_id_label"] = (empty($tracking_no) ? '' : '<b>Numero de Seguimiento de Envio:</b>'); // 'Tracking ID';
                $replace["courier_id_label"] = empty($courier_id) ? '' : "Correo:"; //Courier ID
                break;

            case 'pt':
//              $email_sender = 'no-reply@valuebasket.com';
                $replace["tracking_id_label"] = (empty($tracking_no) ? '' : '<b>Numero de Rastreamento:</b>'); // 'Tracking ID';
                $replace["courier_id_label"] = empty($courier_id) ? '' : "Correio:"; //Courier ID
                break;

            case 'nl':
//              $email_sender = 'no-reply@valuebasket.com';
                $replace["tracking_id_label"] = (empty($tracking_no) ? '' : '<b>Traceernummer:</b>'); // 'Tracking ID';
                $replace["courier_id_label"] = empty($courier_id) ? '' : "Koerier:"; //Courier ID
                break;

            case 'ja':
//              $email_sender = 'no-reply@valuebasket.com';
                $replace["tracking_id_label"] = (empty($tracking_no) ? '' : '<b>>追跡番号:</b>'); // 'Tracking ID';
                $replace["courier_id_label"] = empty($courier_id) ? '' : "宅配便"; //Courier ID
                break;

            case 'it':
//              $email_sender = 'no-reply@valuebasket.com';
                $replace["tracking_id_label"] = (empty($tracking_no) ? '' : '<b>Numero di Spedizione:</b>'); // 'Tracking ID';
                $replace["courier_id_label"] = empty($courier_id) ? '' : "Corriere:"; //Courier ID
                break;

            case 'pl':
//              $email_sender = 'no-reply@valuebasket.com';
                $replace["tracking_id_label"] = (empty($tracking_no) ? '' : '<b>Numerze przesyłki:</b>'); // 'Tracking ID';
                $replace["courier_id_label"] = empty($courier_id) ? '' : "Kurier:"; //Courier ID
                break;

            case 'da':
//              $email_sender = 'no-reply@valuebasket.com';
                $replace["tracking_id_label"] = (empty($tracking_no) ? '' : '<b>Sporings-ID:</b>'); // 'Tracking ID';
                $replace["courier_id_label"] = empty($courier_id) ? '' : "Courier:"; //Courier ID
                break;

            case 'ko':
//              $email_sender = 'no-reply@valuebasket.com';
                $replace["tracking_id_label"] = (empty($tracking_no) ? '' : '<b>ID:</b>'); // 'Tracking ID';
                $replace["courier_id_label"] = empty($courier_id) ? '' : ":"; //Courier ID
                break;

            case 'tr':
//              $email_sender = 'no-reply@valuebasket.com';
                $replace["tracking_id_label"] = (empty($tracking_no) ? '' : '<b>Takip No:</b>'); // 'Tracking ID';
                $replace["courier_id_label"] = empty($courier_id) ? '' : "Kurye:"; //Courier ID
                break;

            case 'sv':
//              $email_sender = 'no-reply@valuebasket.com';
                $replace["tracking_id_label"] = (empty($tracking_no) ? '' : '<b>Sparnings ID:</b>'); // 'Tracking ID';
                $replace["courier_id_label"] = empty($courier_id) ? '' : "Kurir:"; //Courier ID
                break;

            case 'no':
//              $email_sender = 'no-reply@valuebasket.com';
                $replace["tracking_id_label"] = (empty($tracking_no) ? '' : '<b>Sporings-ID:</b>'); // 'Tracking ID';
                $replace["courier_id_label"] = empty($courier_id) ? '' : "Kurer:"; //Courier ID
                break;

            case 'pt-br':
//              $email_sender = 'no-reply@valuebasket.com';
                $replace["tracking_id_label"] = (empty($tracking_no) ? '' : '<b>Numero de rastreamento:</b>'); // 'Tracking ID';
                $replace["courier_id_label"] = empty($courier_id) ? '' : "Entregador:"; //Courier ID
                break;

            case 'ru':
//              $email_sender = 'no-reply@valuebasket.com';
                $replace["tracking_id_label"] = (empty($tracking_no) ? '' : '<b>?оме? дл? о??леживани? г??за:</b>'); // 'Tracking ID';
                $replace["courier_id_label"] = empty($courier_id) ? '' : "Сл?жба до??авки:"; //Courier ID
                break;

            default:
//              $email_sender = 'no-reply@valuebasket.com';
                $replace["tracking_id_label"] = (empty($tracking_no) ? '' : '<b>Tracking ID:</b>'); // 'Tracking ID';
                $replace["courier_id_label"] = empty($courier_id) ? '' : "Courier:"; //Courier ID
                break;
        }
        include_once(APPPATH . "hooks/country_selection.php");
        $country_id = $pbv_obj->get_platform_country_id();
        $replace = array_merge($replace, Country_selection::get_template_require_text($lang_id, $country_id));
        $email_sender = "no-reply@" . strtolower($replace["site_name"]);
        $replace["support_email"] = $email_sender;
        $replace["image_url"] = $this->getDao('Config')->valueOf("default_url");
        $replace["logo_file_name"] = $this->getDao('Config')->valueOf("logo_file_name");
        if (!empty($courier_id)) {
            $replace["courier_id"] = $courier_id;
            $replace["tracking_id"] = $tracking_no;
            $courier_obj = $this->getDao('Courier')->get(array("id" => $courier_id));
            if ($courier_obj) {
                $replace["tracking_link"] = $courier_obj->get_tracking_link();
            }
        } else {
            $replace["courier_id"] = "";
            $replace["courier_id_label"] = "";
            $replace["tracking_id"] = "";
            $replace["tracking_id_label"] = "";
        }

        $sub_total = $total_vat = $total = 0;
        $i = 1;

        include_once(APPPATH . "helpers/image_helper.php");

        $dc = $so_obj->get_delivery_charge();
        $total += $dc;
        $replace["subtotal"] = platform_curr_format($platform_id, $sub_total, 0);
        $replace["total_vat"] = platform_curr_format($platform_id, $total_vat, 0);

        //#2182 add the processing fee
        $extobj = $this->getDao('SoExtend')->get(array("so_no" => $so_obj->get_so_no()));
        if ($extobj) {
            $processing_fee = $extobj->get_offline_fee();
        }

        if (is_null($processing_fee)) {
            $processing_fee = 0;
        }
        $replace["processing_fee"] = platform_curr_format($platform_id, $processing_fee, 0);
        //#2182 add the processing fee to the total fee
        $total += $processing_fee;
        $replace["total"] = platform_curr_format($platform_id, $total, 0);

        $dc = $so_obj->get_delivery_charge();
        $total += $dc;
        $dc_vat = $dc * ($so_obj->get_vat_percent() / (100 + $so_obj->get_vat_percent()));
        $dc_sub_total = $dc - $dc_vat;
        $replace["dc_sub_total"] = platform_curr_format($platform_id, $dc_sub_total, 0);
        $replace["dc_vat"] = platform_curr_format($platform_id, $dc_vat, 0);
        $replace["delivery_charge"] = platform_curr_format($platform_id, $dc, 0);
        $replace["total_sub_total"] = platform_curr_format($platform_id, $sub_total + $dc_sub_total, 0);
        $replace["total_total_vat"] = platform_curr_format($platform_id, $total_vat + $dc_vat, 0);
        $replace["last_update_time"] = '';

        $this->include_dto("Event_email_dto");
        $dto = new Event_email_dto();
        if (($so_obj->get_biz_type() == 'ONLINE') && ($courier_id != 'HK POST') && ($courier_id != 'hong kong post') && ($courier_id != 'Quantium')) {
            if ($this->is_filfull_aftership_thank_you_email_criteria($so_obj->get_delivery_country_id(), $so_obj->get_so_no(), $replace['last_update_time'], $ap_status)) {
                # SBF #4740 - if fulfull thank you email - product delivered on time send info to FIANET
                $send_mail = 1;
                $this->get_review_fianet_service()->send_order_data($so_obj, $client);

                $dto->set_event_id("aftership_thank_you_mail");
                $dto->set_mail_from($email_sender);
                $dto->set_mail_to($client->get_email());
                if (!$this->getDao('SoHoldReason')->getNumRows(array("so_no" => $so_obj->get_so_no(), "reason IN ('change_of_address', 'cscc', 'csvv')" => null))) {
                    switch ($lang_id) {
                        case 'fr':
                            $dto->set_mail_bcc(array("valuebasketbccemail@gmail.com", "ming@valuebasket.com"));
                            break;

                        case 'en':
                            $dto->set_mail_bcc(array("valuebasketbccemail@gmail.com", "ming@valuebasket.com"));
                            break;

                        case 'it':
                            $dto->set_mail_bcc(array("valuebasketbccemail@gmail.com", "ming@valuebasket.com"));
                            break;

                        case 'es':
                            $dto->set_mail_bcc(array("valuebasketbccemail@gmail.com", "ming@valuebasket.com"));
                            break;

                        default:
                            break;
                    }
                }
                $bcc = $dto->get_mail_bcc();

                # sbf #4349
                switch ($platform_id) {
                    case 'WEBAU':
                    case 'WEBSG':
                    case 'WEBMY':
                    case 'WEBNZ':
                        $wow_bcc = array("wow-apac@valuebasket.com");
                        break;

                    case 'WEBES':
                    case 'WEBPT':
                    case 'WEBFI':
                        $wow_bcc = array("wow-es@valuebasket.com");
                        break;

                    case 'WEBFR':
                    case 'WEBBE':
                    case 'WEBCH':
                        $wow_bcc = array("wow-fr@valuebasket.com");
                        break;

                    case 'WEBIT':
                        $wow_bcc = array("wow-it@valuebasket.com");
                        break;

                    case 'WEBRU':
                    case 'WEBPL':
                        $wow_bcc = array("wow-ru@valuebasket.com");
                        break;

                    case 'WEBGB':
                    case 'WEBIE':
                    case 'WEBMT':
                        $wow_bcc = array("wow-gb@valuebasket.com");
                        break;


                    default:
                        # code...
                        break;
                }

                $sendagain = "no-reply@feedback-valuebasket.com";

                if (($so_obj->get_bill_country_id() == 'GB') || ($so_obj->get_delivery_country_id() == 'GB')) {
                    $dto->set_mail_from($email_sender);
                    $dto->set_tpl_id("aftership_thank_you_mail_gb");
                    $dto->set_lang_id("en");
                } else {
                    $dto->set_tpl_id("aftership_thank_you_mail");
                    $dto->set_lang_id($lang_id);
                }
                $dto->set_replace($replace);
            } else {
                $dto->set_event_id("aftership_late_delivery_mail");
                $dto->set_mail_from($email_sender);
                $dto->set_mail_to($client->get_email());
                $dto->set_mail_bcc(array("valuebasketbccemail@gmail.com"));
                $dto->set_platform_id($platform_id);

                if (($so_obj->get_bill_country_id() == 'GB') || ($so_obj->get_delivery_country_id() == 'GB')) {
                    $dto->set_mail_from($email_sender);
                    $dto->set_tpl_id("aftership_late_delivery_mail");
                    $dto->set_lang_id("en");
                } else {
                    $dto->set_tpl_id("aftership_late_delivery_mail");
                    $dto->set_lang_id($lang_id);
                }
                $dto->set_replace($replace);
            }

        }
        // attach invoice to dispatch email
        $data_path = $this->getDao('Config')->valueOf("data_path");
        $html = $this->get_invoice_content(array($so_obj->get_so_no()), 1);
        $so_no = $so_obj->get_so_no();
        $att_file = $this->get_pdf_rendering_srv()->convert_html_to_pdf($html, $data_path . "/invoice/Invoice_" . $so_no . ".pdf", "F", $lang_id);
        $replace["att_file"] = $att_file;
        $dto->set_replace($replace);
        $this->eventService->fireEvent($dto);

        unlink($att_file);
    }

    function is_filfull_aftership_thank_you_email_criteria($delivery_country_id, $so_no, &$replace_last_update = Null, $ap_status)
    {
        if ($ap_status == '6') {
            $aftship_thank_you_mail_obj = $this->wow_email_service->get_so_dao()->get_thank_you_mail_list(array("so.so_no" => $so_no, "a.courier_id NOT LIKE '%HK%POST%'" => null, "a.courier_id NOT LIKE '%hong-kong-post%'" => null, "a.courier_id NOT LIKE '%Quantium%'" => null, "so.biz_type NOT IN ('SPECIAL', 'MANUAL', 'EBAY', 'FNAC', 'RAKUTEN', 'QOO10')" => null), $replace_last_update, $ap_status);
        }

        $ontime = $aftship_thank_you_mail_obj['delivered_on_time'];

        if ($ontime == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function update_website_display_qty(So_vo $so)
    {
        if ($so) {
            include_once(APPPATH . "libraries/service/Display_qty_service.php");
            $display_qty_srv = new Display_qty_service();

            $so_no = $so->get_so_no();
            $soid_list = $this->getDao('SoItemDetail')->getList(array("so_no" => $so_no));
            $stock_alert = array();
            foreach ($soid_list as $soid) {
                $prod_obj = $this->getDao('Product')->get(array("sku" => $soid->get_item_sku()));
                $dqty = $prod_obj->get_display_quantity();
                $wqty = $prod_obj->get_website_quantity();
                $ndqty = max(0, $dqty - $soid->get_qty());
                $nwqty = max(0, $wqty - $soid->get_qty());

                $cat_id = $prod_obj->get_cat_id();
                if ($display_qty_srv->require_update_display_qty($ndqty, $cat_id)) {
                    $ndqty = $display_qty_srv->calc_display_qty($cat_id, $nwqty, $soid->get_unit_price(), $so->get_currency_id());
                }

                $prod_obj->set_display_quantity($ndqty);
                $prod_obj->set_website_quantity($nwqty);
                if ($nwqty === 0) {
                    $stock_alert[$prod_obj->get_sku()] = $prod_obj->get_name();
                }
                if ($nwqty == 5) {
                    $five_alert[$prod_obj->get_sku()] = $prod_obj->get_name();
                }
                $this->getDao('Product')->update($prod_obj);
            }


            if (count($five_alert)) {
                include_once(APPPATH . "libraries/service/Event_service.php");
                $event = new Event_service();
                include_once(APPPATH . "libraries/dto/event_email_dto.php");
                $email_dto = new Event_email_dto();
                $message .= "Please be advised that order number " . $so->get_client_id() . "-" . $so->get_so_no() . " platform " . $so->get_platform_id() . " has triggered the following product to control quantity 5:\n\n";
                foreach ($five_alert as $key => $value) {
                    $message .= $key . " - " . $value . "\n";
                }

                $message = preg_replace("{\n$}", "", $message);
                $title = "[VB] Website Order Quantity Warning, QTY = 5";
                $dto = clone $email_dto;
                $dto->set_event_id("notification");
                $dto->set_mail_to(array("bd@eservicesgroup.net"));
                //$dto->set_mail_to("merchat@eservicesgroup.net");
                $dto->set_mail_from("do_not_reply@valuebasket.com");
                $dto->set_tpl_id("general_alert");
                $dto->set_replace(array("title" => $title, "message" => $message));
                $event->fire_event($dto);
            }

            if (count($stock_alert)) {
                include_once(APPPATH . "libraries/service/Event_service.php");
                $event = new Event_service();
                include_once(APPPATH . "libraries/dto/event_email_dto.php");
                $email_dto = new Event_email_dto();
                $message .= "Please be advised that order number " . $so->get_client_id() . "-" . $so->get_so_no() . " platform " . $so->get_platform_id() . " has triggered the following product(s) to possibly be out of stock:\n\n";
                foreach ($stock_alert as $key => $value) {
                    $message .= $key . " - " . $value . "\n";
                }

                $message = preg_replace("{\n$}", "", $message);
                $title = "[VB] Website Order Quantity Warning, QTY = 0";
                $dto = clone $email_dto;
                $dto->set_event_id("notification");
                $dto->set_mail_to(array("bd@eservicesgroup.net"));
                //$dto->set_mail_to("merchat@eservicesgroup.net");
                $dto->set_mail_from("do_not_reply@valuebasket.com");
                $dto->set_tpl_id("general_alert");
                $dto->set_replace(array("title" => $title, "message" => $message));
                $event->fire_event($dto);
            }
        }
    }

    public function orderQuickSearch($where = array(), $option = array())
    {
        if ($option["num_rows"] == 1) {
            return $this->getDao('So')->orderQuickSearch($where, $option);
        } else {
            $list = $this->getDao('So')->orderQuickSearch($where, $option);
            $ret = array();
            foreach ($list as $value) {
                if ($value->get_status() < 6 && $value->get_status() > 2) {
                    $items = $this->getDao('So')->getOrderItemList($value->get_so_no());
                } else {
                    $items = $this->getDao('So')->getOrderItemListDone($value->get_so_no());
                }
                $value->set_items($items);
                $ret[] = $value;
            }
            return $ret;
        }
    }

    public function update_complete_order($so_obj, $trans_handle = 1)
    {
        if ($soid_list = $this->getDao('SoItemDetail')->getList(array("so_no" => $so_obj->get_so_no()))) {
            $success = 1;

            if ($trans_handle) {
                $this->getDao('So')->trans_start();
            }

            foreach ($soid_list as $soid_obj) {
                $soid_obj->set_status(1);
                if ($this->getDao('SoItemDetail')->update($soid_obj) === FALSE) {
                    $success = 0;
                    $error = __LINE__ . " " . $this->db->_error_message();
                    break;
                }
            }
            if ($success) {
                $so_obj->set_status(6);
                $so_obj->set_dispatch_date(date("Y-m-d H:i:s"));
                if ($this->update($so_obj) === FALSE) {
                    $success = 0;
                }
            }

            if ($trans_handle) {
                if (!$success) {
                    $this->getDao('So')->trans_rollback();
                }
                $this->getDao('So')->trans_complete();
            }

            return $success;
        }
    }

    public function is_cod_order($so_no)
    {
        $so_ext_obj = $this->getDao('SoExtend')->get(array("so_no" => $so_no));
        $sops_obj = $this->getDao('SoPaymentStatus')->get(array("so_no" => $so_no));
        $so_obj = $this->getDao('So')->get(array("so_no" => $so_no));
        if ((($so_obj->get_biz_type() != 'SPECIAL')
                && ($so_ext_obj->get_offline_fee() == 15)
                && ($sops_obj->get_payment_gateway_id() == "worldpay_moto")
                && ($so_obj->get_platform_id() == "WEBSG"))
            ||
            (($so_obj->get_biz_type() != 'SPECIAL') && ($sops_obj->get_payment_gateway_id() == "worldpay_moto_cash"))
            ||
            (($so_obj->get_biz_type() != 'SPECIAL') && ($sops_obj->get_payment_gateway_id() == "paypal_cash"))
        ) {
            return true;
        }
        return false;
    }

    public function getSalesComparisonDataByPeriod($where = array(), $classname = '')
    {
        return $this->getDao('So')->getSalesComparisonDataByPeriod($where, $classname);
    }

    public function getConfirmedSo($where = array(), $from_date = '', $to_date = '', $is_light_version = false, $dispatch_report = false)
    {
        return $this->getDao('So')->getConfirmedSo($where, $from_date, $to_date, $is_light_version, $dispatch_report);
    }

    public function getSplitSoReport($where = array(), $option = array(), $from_date = '', $to_date = '')
    {
        return $this->getDao('So')->getSplitSoReport($where, $option, $from_date, $to_date);
    }

    public function get_domain_platform_service()
    {
        return $this->domain_platform_service;
    }

    public function set_domain_platform_service($srv)
    {
        $this->domain_platform_service = $srv;

        return $srv;
    }

    public function get_wow_email_service()
    {
        return $this->wow_email_service;
    }

    public function set_wow_email_service(Base_service $srv)
    {
        $this->wow_email_service = $srv;

        return $srv;
    }

    public function isTrialSoftware($sku = "")
    {
        return $this->getDao('Product')->isTrialSoftware($sku);
    }

    public function isSoftware($sku = "")
    {
        return $this->getDao('Product')->isSoftware($sku);
    }

    public function getProductTypeWithSku($sku = "")
    {
        return $this->getDao('Product')->getProductTypeWithSku($sku);
    }

    public function getReevooCustomerFeedDto($last_access_time = "")
    {
        return $this->getDao('So')->getReevooCustomerFeedDto($last_access_time);
    }

    public function insert_sops($obj)
    {
        return $this->getDao('SoPaymentStatus')->insert($obj);
    }

    public function update_sops($obj)
    {
        return $this->getDao('SoPaymentStatus')->update($obj);
    }

    public function getRmaVo()
    {
        return $this->getDao('Rma')->get();
    }

    public function insertRma($obj)
    {
        return $this->getDao('Rma')->insert($obj);
    }

    public function getFnacPendingPaymentOrders($where = array(), $option = array())
    {
        return $this->getDao('So')->getFnacPendingPaymentOrders($where, $option);
    }

    public function getEbayFeedbackEmailContent($where = array(), $option = array())
    {
        return $this->getDao('So')->getEbayFeedbackEmailContent($where, $option);
    }

    function get_working_days($start_ts, $end_ts, $holidays = array())
    {
        foreach ($holidays as & $holiday) {
            $holiday = strtotime($holiday);
        }
        $working_days = 0;
        $tmp_ts = $start_ts;
        while ($tmp_ts <= $end_ts) {
            $tmp_day = date('D', $tmp_ts);
            if (!($tmp_day == 'Sun') && !($tmp_day == 'Sat') && !in_array($tmp_ts, $holidays)) {
                $working_days++;
            }
            $tmp_ts = strtotime('+1 day', $tmp_ts);
        }
        return $working_days;
    }

    public function send_notification_to_cs($so_obj)
    {
        $client_obj = $this->getDao('Client')->get(array("id" => $so_obj->get_client_id()));
        include_once APPPATH . "libraries/dto/event_email_dto.php";
        $email_dto = new Event_email_dto();
        $email_dto->set_event_id("special_aps_cs_notification");
        $email_dto->set_mail_from("do_not_reply@valuebasket.com");
        $email_dto->set_mail_to(array("salesteam@eservicesgroup.net", "EUTeam@eservicesgroup.com", "jesslyn@eservicesgroup.com"));
        $email_dto->set_mail_cc("csmanager@eservicesgroup.net");
        $email_dto->set_tpl_id("special_aps_cs_notification");
        $email_dto->set_lang_id("en");

        $replace = array();

        $replace["site_name"] = "VB";
        $replace["so_no"] = $so_obj->get_so_no();
        $replace["forename"] = $so_obj->get_bill_name();
        $replace["tel"] = $client_obj->get_tel_1() . $client_obj->get_tel_2() . $client_obj->get_tel_3();
        $replace["del_address"] = $client_obj->get_del_address_1() . " " . $client_obj->get_del_address_2() . " " . $client_obj->get_del_address_3();
        $replace["del_city"] = $client_obj->get_del_city();
        $replace["del_country"] = $client_obj->get_del_country_id();
        $replace["default_url"] = $this->getDao('Config')->valueOf("default_url");
        $replace["logo_file_name"] = $this->getDao('Config')->valueOf("logo_file_name");

        $so_ext_obj = $this->getDao('SoExtend')->get(array("so_no" => $so_obj->get_so_no()));

        $replace["order_reason"] = $so_ext_obj->get_order_reason();
        $replace["order_notes"] = $so_ext_obj->get_notes();

        $email_dto->set_replace($replace);
        include_once(APPPATH . "libraries/service/Event_service.php");
        $event = new Event_service();
        $event->fire_event($email_dto);
    }

    public function send_aps_order_client_notification_email($so_obj)
    {
        $client_obj = $this->getDao('Client')->get(array("id" => $so_obj->get_client_id()));
        include_once APPPATH . "libraries/dto/event_email_dto.php";
        $email_dto = new Event_email_dto();
        $email_dto->set_event_id("special_aps_order_notification");
        $email_dto->set_mail_from("do_not_reply@valuebasket.com");
        $email_dto->set_mail_to($client_obj->get_email());
        $email_dto->set_tpl_id("special_aps_order_notification");
        $email_dto->set_lang_id("en");

        $replace = array();
        include_once(APPPATH . "hooks/country_selection.php");

        $replace["site_url"] = Country_selection::rewrite_domain_by_country("www.valuebaset.com", $so_obj->get_bill_country_id());
        $replace["site_name"] = Country_selection::rewrite_site_name($replace["site_url"]);
        $replace["so_no"] = $so_obj->get_so_no();
        $replace["forename"] = $so_obj->get_bill_name();
        $replace["default_url"] = $this->getDao('Config')->valueOf("default_url");
        $replace["logo_file_name"] = $this->getDao('Config')->valueOf("logo_file_name");

        $email_dto->set_replace($replace);
        include_once(APPPATH . "libraries/service/Event_service.php");
        $event = new Event_service();
        $event->fire_event($email_dto);
    }

    public function getOrdersBySkuAndStatus($sku, $so_status = 2, $where = array(), $option = array())
    {
        return $this->getDao('So')->getOrdersBySkuAndStatus($sku, $so_status, $where, $option);
    }

    public function getEbayPendingShipmentUpdateOrders($where = array(), $option = array())
    {
        return $this->getDao('So')->getEbayPendingShipmentUpdateOrders($where, $option);
    }

    public function getQoo10PendingShipmentUpdateOrders($where = array(), $option = array())
    {
        return $this->getDao('So')->getQoo10PendingShipmentUpdateOrders($where, $option);
    }

    public function getRakutenPendingShipmentUpdateOrders($where = array(), $option = array())
    {
        return $this->getDao('So')->getRakutenPendingShipmentUpdateOrders($where, $option);
    }

    public function getAutomatedFeedbackEmailContent($where = array(), $option = array())
    {
        return $this->getDao('So')->getAutomatedFeedbackEmailContent($where, $option);
    }

    public function getSoPriorityScoreInfo($so_no_array)
    {
        $so_no_list = implode($so_no_array, ",");
        return $this->getDao('So')->getSoPriorityScoreInfo($so_no_list);
    }

    public function getPriorityScore($so_no, $result = null)
    {
        if ($result === null) {
            $result = $this->getDao('So')->getPriorityScore($so_no);
            $result["order_margin"] = null;
        }
        $score_christmas = $this->getPriorityScoreChristmas($so_no, $result);

        if ($score_christmas > 0)
            return $score_christmas;
        else
            return $this->getPriorityScoreBase($so_no, $result);
    }

    public function getPriorityScoreChristmas($so_no, $result = null)
    {
        $score = 0;
        $year = date("Y");
        $month = date("n");
        $day = date("j");

        $eu_country_group = array("fr", "gb", "ie", "be", "nl", "pt", "se", "si", "de", "dk");
        $apac_country_group = array("sg", "my", "th", "tw", "ph");

        $countryId = strtolower($result["delivery_country_id"]);
        if (($year == 2013) && ($month == 12)) {

            if (($day >= 9) && ($day <= 11)) {
                if ($countryId == "es")
                    $score = 1000;
                else if ($countryId == "au")
                    $score = 800;
                else if (in_array($countryId, $eu_country_group))
                    $score = 600;
            } else if (($day >= 12) && ($day <= 16)) {
                if ($countryId == "au")
                    $score = 1000;
                else if (in_array($countryId, $eu_country_group))
                    $score = 800;
                else if (in_array($countryId, $apac_country_group))
                    $score = 600;
            } else if ($day == 17) {
                if (in_array($countryId, $eu_country_group))
                    $score = 1000;
                else if (in_array($countryId, $apac_country_group))
                    $score = 800;
                else if (($countryId == "es") || ($countryId == "it"))
                    $score = 600;
            } else if ($day == 18) {
                if (in_array($countryId, $apac_country_group))
                    $score = 1000;
                else if ($countryId == "au")
                    $score = 800;
                else if ((in_array($countryId, $eu_country_group)) || ($countryId == "es") || ($countryId == "it"))
                    $score = 600;
            } else if (($day == 19) || ($day == 20)) {
                if (($countryId == "es") || ($countryId == "it"))
                    $score = 1000;
                else if (in_array($countryId, $eu_country_group))
                    $score = 800;
            }
        }

        if ($score > 0) {
            if ($manual = $this->get_so_ps_srv()->get(array("so_no" => $so_no, "status" => 1))) {
                $score += $manual->get_score();
            }
        }

        return $score;
    }

    public function getPriorityScoreBase($so_no, $result = null)
    {
        if ($this->sub_domain_cache == null) {
            foreach ($this->get_sub_domain_srv()->get_list() as $k => $v) {
                $this->sub_domain_cache[$v->get_subject()] = $v->get_value();
            }
        }

        if ($manual = $this->get_so_ps_srv()->get(array("so_no" => $so_no, "status" => 1))) {
            $score = $manual->get_score();
        } else {
            $score = 0;

            #if data was not passed in, we need to query for it
            if ($result === null) {
                $result = $this->getDao('So')->getPriorityScore($so_no);
                $result["order_margin"] = null;
            }

            $days = $this->get_days(strtotime($result["order_create_date"]), mktime());
            if ((($ps_obj = $this->getDao('SoPaymentStatus')->get(array("so_no" => $so_no))) !== FALSE) && ($ps_obj))
                $pay_to_account = $ps_obj->get_pay_to_account();
            else
                $pay_to_account = "";
            if ((($margin_score = $this->get_so_ps_srv()->hit_margin_rule($so_no, $result['biz_type'], $days, false, $result["order_margin"])) > 0)
                && ($pay_to_account != "paypal.au@valuebasket.com") && ($pay_to_account != "paypal.oce@valuebasket.com") && ($pay_to_account != "paypal.nz@valuebasket.com")
            ) {
                // return $margin_score;
                $score = $margin_score;
            } else {
                $platform_score = 0;
                if (!$platform_score = $this->sub_domain_cache["PRIORITY_SCORE.PLATFORM.{$result['biz_type']}"]) {
                    $platform_score = $this->sub_domain_cache["PRIORITY_SCORE.DEFAULT"];
                }

                //affiliate score get here
                if ($affilate_score = $this->sub_domain_cache["PRIORITY_SCORE.AFFILIATE.{$result['conv_site_id']}"]) {
                    $platform_score = $affilate_score;
                }

                //check if payment_gateway = ppau
                if (($pay_to_account == "paypal.au@valuebasket.com") || ($pay_to_account == "paypal.oce@valuebasket.com")) {
                    if ($platform_score < $this->sub_domain_cache["PRIORITY_SCORE.PAYMENT_GATEWAY.PAYPAL.AU"])
                        $platform_score = $this->sub_domain_cache["PRIORITY_SCORE.PAYMENT_GATEWAY.PAYPAL.AU"];
                } else if ($pay_to_account == "paypal.nz@valuebasket.com") {
                    if ($platform_score < $this->sub_domain_cache["PRIORITY_SCORE.PAYMENT_GATEWAY.PAYPAL.NZ"])
                        $platform_score = $this->sub_domain_cache["PRIORITY_SCORE.PAYMENT_GATEWAY.PAYPAL.NZ"];
                } else if ($pay_to_account == "paypal.value@valuebasket.com") {
                    if ($platform_score < $this->sub_domain_cache["PRIORITY_SCORE.PAYMENT_GATEWAY.PAYPAL.HK"])
                        $platform_score = $this->sub_domain_cache["PRIORITY_SCORE.PAYMENT_GATEWAY.PAYPAL.HK"];
                }

                if (($result['biz_type'] == "EBAY")
                    || ($result['biz_type'] == "FNAC")
                    || ($result['biz_type'] == "QOO10")
                ) {
//we check this after PP, because ebay may use PP
                    $platform_day_score = explode("||", $this->sub_domain_cache["PRIORITY_SCORE.PLATFORM.DAYX_SCORE"]);
                    if ($days > $platform_day_score[1])
                        $platform_score = $platform_day_score[0];
                }

                if ($result['biz_type'] == "OFFLINE") {
//OFFLINE BULK SALES
                    if (($ps_obj) && ($ps_obj->get_payment_gateway_id() == "paypal")) {
                        $platform_score = $this->sub_domain_cache["PRIORITY_SCORE.PAYMENT_GATEWAY.PAYPAL.HK"];
                    } else if ((($soex_obj = $this->getDao('SoExtend')->get(array("so_no" => $so_no))) !== FALSE) && ($soex_obj)) {
                        if ($soex_obj->get_order_reason() == 31) {
                            $platform_score = $this->sub_domain_cache["PRIORITY_SCORE.BULK_SALES"];
                        }
                    }
                } else if ($result['biz_type'] == "SPECIAL") {
                    $platform_score = $this->sub_domain_cache["PRIORITY_SCORE.APS_ORDER"];
                }

                $score += $platform_score;
                $min_day_range = $this->sub_domain_cache["PRIORITY_SCORE.DAY_RANGE.MIN"];
                $min_day = $min_day_range;
                $max_day_range = $this->sub_domain_cache["PRIORITY_SCORE.DAY_RANGE.MAX"];
                $max_day = $max_day_range;
                if ($days < $max_day && $min_day < $days) {
                    $score += $days;
                }
                $retailer_score = $this->sub_domain_cache["PRIORITY_SCORE.RETAILERS_SCORE"];
                $score += $retailer_score;
            }
            if (($days < $this->sub_domain_cache["PRIORITY_SCORE.DAY_RANGE.MIN"]) && ($score < 1000))
                $score = 0;
        }

        return $score;
    }

    function get_days($start_ts, $end_ts)
    {
        $working_days = 0;
        $tmp_ts = $start_ts;
        while ($tmp_ts <= $end_ts) {
            $tmp_day = date('D', $tmp_ts);
            $working_days++;
            $tmp_ts = strtotime('+1 day', $tmp_ts);
        }
        return $working_days;
    }

    public function get_priority_score_obj($so_no)
    {
        return $this->get_so_ps_srv()->get(array("so_no" => $so_no, "status" => 1));
    }

    public function getShippingInfo($where = array())
    {
        return $this->getDao('SoShipment')->getShippingInfo($where);
    }

    public function getLifetimeTransactionByClientId($client_id)
    {
        return $this->getDao('So')->getLifetimeTransactionByClientId($client_id);
    }

    public function getLastTenTransactionInfoByClientId($client_id)
    {
        return $this->getDao('So')->getLastTenTransactionInfoByClientId($client_id);
    }

    public function getDistinctClientIdList($where = array(), $option = array())
    {
        return $this->getDao('So')->getDistinctClientIdList($where, $option);
    }

    public function getRmaCustomerEmailAddress($past_day)
    {
        return $this->getDao('So')->getRmaCustomerEmailAddress($past_day);
    }

    public function getAftershipData($where = array(), $option = array())
    {
        return $this->getDao('So')->getAftershipData($where, $option);
    }

    public function getAftershipReportForFtp($where = array(), $option = array())
    {
        return $this->getDao('So')->getAftershipReportForFtp($where, $option);
    }

    public function getWowEmailListData($where = array(), $option = array())
    {
        return $this->getDao('So')->getWowEmailListData($where, $option);
    }

    public function is_fraud_order($so_obj = '')
    {
        if (!$so_obj)
            return false;

        $so_no = $so_obj->get_so_no();
        if ($client_obj = $this->getDao('Client')->get(array("id" => $so_obj->get_client_id()))) {
            $client_email = $client_obj->get_email();
            if ($black_list_object = $this->get_email_referral_list_service()->get(array('email' => $client_email, '`status`' => 1))) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    public function get_email_referral_list_service()
    {
        return $this->email_referral_list_service;
    }

    public function set_email_referral_list_service($value)
    {
        $this->email_referral_list_service = $value;
    }

    public function process_fraud_order($so_obj = '')
    {
        if (!$so_obj)
            return false;

        $so_no = $so_obj->get_so_no();
        if ($client_obj = $this->getDao('Client')->get(array("id" => $so_obj->get_client_id()))) {
            $client_email = $client_obj->get_email();
            if ($black_list_object = $this->get_email_referral_list_service()->get(array('email' => $client_email, '`status`' => 1))) {
                //insert the order into the fraudulent order table
                if (!$fraud_order_obj = $this->get_fraudulent_order_service()->get(array('so_no' => $so_no))) {
                    $new_fraud_order_obj = $this->get_fraudulent_order_service()->get();
                    $new_fraud_order_obj->set_so_no($so_no);
                    $new_fraud_order_obj->set_status(1);
                    if ($this->get_fraudulent_order_service()->insert($new_fraud_order_obj)) {   //set order status as 1
                        if (($so_obj = $this->getDao('So')->get(array("so_no" => $so_no)))) {
                            $so_obj->set_hold_status(1);
                            if ($this->getDao('So')->update($so_obj)) {   //set the so_hold_reason
                                if ($sohr_vo = $this->getDao('SoHoldReason')->get()) {
                                    $sohr_vo->set_so_no($so_no);
                                    $sohr_vo->set_reason("confirmed_fraud");
                                    $this->getDao('SoHoldReason')->insert($sohr_vo);

                                    $action = "update";
                                    $socc_obj = $this->getDao('SoCreditChk')->get(array("so_no" => $so_no));
                                    if (!$socc_obj) {
                                        $socc_obj = $this->getDao('SoCreditChk')->get();
                                        $action = "insert";
                                    }
                                    $this->getDao('SoCreditChk')->trans_start();
                                    $socc_obj->set_so_no($so_no);
                                    $socc_obj->set_fd_status(2);
                                    $this->getDao('SoCreditChk')->$action($socc_obj);

                                    $so_obj->set_status(0);
                                    $so_obj->set_hold_status(0);
                                    $this->getDao('So')->update($so_obj);
                                    $this->getDao('SoCreditChk')->trans_complete();

                                    //add an order note
                                    $order_note_vo = $this->getDao('OrderNotes')->get();
                                    $order_note_vo->set_so_no($so_no);
                                    $order_note_vo->set_note("system inactivate, blacklisted client");
                                    $this->getDao('OrderNotes')->insert($order_note_vo);


                                    $date_info = date('Y-m-d');
                                    $body = 'Confirmed fraud: ' . $so_no;

                                    $nero = mail("
                                        Compliance@valuebasket.com,
                                        nero@eservicesgroup.com",

                                        "[VB] {$date_info}: Confirmed fraud", $body
                                    );
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public function get_fraudulent_order_service()
    {
        return $this->fraudulent_order_service;
    }

    public function set_fraudulent_order_service($value)
    {
        $this->fraudulent_order_service = $value;
    }

    /****************************************************
     **  function permanent_hold_parent
     **  the pass in so_obj will only be a special order
     ****************************************************/
    public function permanent_hold_parent($so_obj)
    {
        $requirePermanentHold = false;
        $parent_so_no = $so_obj->get_parent_so_no();
        if ($parent_so_no) {
            $parent_so_obj = $this->getDao('So')->get(array("so_no" => $parent_so_no));
            if ($parent_so_obj->get_hold_status() != 0) {
                $is_oos = $this->getDao('SoHoldReason')->get(array("so_no" => $parent_so_no, "reason" => "oos"));
                if ($is_oos) {
                    $so_ext_obj = $this->getDao('SoExtend')->get(array("so_no" => $so_obj->get_so_no()));
                    if (($so_ext_obj->get_order_reason() >= 19) and ($so_ext_obj->get_order_reason() <= 22)) {
                        $requirePermanentHold = true;
                    }
                }
            }
        }
        if ($requirePermanentHold) {
            $parent_so_obj->set_hold_status(self::PERMANENT_HOLD_STATUS);
            $this->update($parent_so_obj);
            if ($sohr_vo = $this->getDao('SoHoldReason')->get()) {
                $sohr_vo->set_so_no($parent_so_no);
                $sohr_vo->set_reason("perm_hold_sales_aps");
                $this->getDao('SoHoldReason')->insert($sohr_vo);
            }
        }
    }

    public function permanent_hold_parent_for_aps($so_obj)
    {
        $requirePermanentHold = false;
        $reason_id = false;
        $ret["status"] = true;

        // the list of reason_id that must be put on permanent hold
        $reason_id_list_for_perm_hold = array("19", "20", "21", "22", "34");

        if ($so_obj) {
            $parent_so_no = $so_obj->get_parent_so_no();

            $where["so.so_no"] = $so_obj->get_so_no();
            $option["so_item"] = "1";
            $option["hide_payment"] = "1";
            $option["notes"] = TRUE;
            $option["extend"] = TRUE;
            $option["limit"] = 1;

            $objlist = $this->getDao('So')->getListWithName($where, $option);
            if ($objlist) {
                foreach ($objlist as $key => $obj) {
                    $reason_id = $obj->get_order_reason();
                    break;
                }
            }

            # check if the reason needs permanent hold
            if ($reason_id != FALSE && in_array($reason_id, $reason_id_list_for_perm_hold)) {
                $requirePermanentHold = true;
            }
        }

        if ($requirePermanentHold) {
            $parent_so_obj = $this->getDao('So')->get(array("so_no" => $parent_so_no));

            if ($parent_so_obj) {
                if ($parent_so_obj->get_hold_status() == 15) {
                    $ret["status"] = true;
                    $ret["update_message"] = "parent so_no <$parent_so_no> is already held for split order; no need to perm hold";
                } else {
                    $parent_so_obj->set_hold_status(self::PERMANENT_HOLD_STATUS);
                    if ($this->update($parent_so_obj) === FALSE) {
                        $ret["status"] = false;
                        $ret["error_message"] = __LINE__ . "Cannot update so_no <$parent_so_no> with perm hold. SQL: " . $this->db->last_query() . " DBerror: " . $this->db->_error_message();
                    } else {
                        if ($sohr_vo = $this->getDao('SoHoldReason')->get()) {
                            $sohr_vo->set_so_no($parent_so_no);
                            $sohr_vo->set_reason("perm_hold_sales_aps");
                            if ($this->getDao('SoHoldReason')->insert($sohr_vo) === FALSE) {
                                $ret["status"] = false;
                                $ret["error_message"] = __LINE__ . "Cannot update so_hold_reason <$parent_so_no> with hold reason. SQL: "
                                    . $this->getDao('SoHoldReason')->db->last_query() . " DBerror: " . $this->getDao('SoHoldReason')->db->_error_message();
                            } else {
                                $ret["status"] = true;
                                $ret["update_message"] = "parent so_no <$parent_so_no> updated with permanent hold status";
                            }
                        }
                    }
                }
            } else {
                $ret["status"] = false;
                $ret["error_message"] = __LINE__ . "Cannot retrieve parent_so_obj. SQL: " . $this->getDao('So')->db->last_query() . " DBerror: " . $this->getDao('So')->db->_error_message();
            }
        }

        return $ret;
    }

    public function cancelOrder($age_in_days = 10)
    {
        $this->getDao('So')->cancelOrder($age_in_days);
    }

    public function updateEmptySoItemCost()
    {
        $result = $this->getDao('So')->updateEmptySoItemCost();
    }

    public function getFulfillmentSo($where, $option)
    {
        return $this->getDao('So')->getFulfillmentSo($where, $option);
    }

    public function getSalesVolumeSo($where, $option)
    {
        return $this->getDao('So')->getSalesVolumeSo($where, $option);
    }
}

