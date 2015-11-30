<?php
namespace ESG\Panther\Service;

use PHPMailer;
use EventEmailDto;

class SoService extends BaseService
{
    const PERMANENT_HOLD_STATUS = 10;
    private $declared_value_debug = "";
    private $sub_domain_cache = null;

    public function __construct()
    {
        parent::__construct();
        $CI =& get_instance();
        $CI->load->library('dao/sequence_dao');
        $this->sequence_dao = $CI->sequence_dao;

        $this->exchangeRateService = new ExchangeRateService;
        $this->eventService = new EventService;
        $this->entityService = new EntityService;
        $this->delayedOrderService = new DelayedOrderService;
        $this->reviewFianetService = new ReviewFianetService;
        $this->pdfRenderingService = new PdfRenderingService;
        $this->currencyService = new CurrencyService;
        $this->templateService = new TemplateService;
        $this->subjectDomainService = new SubjectDomainService;
        $this->priceService = new PriceService;
        $this->soPriorityScoreService= new SoPriorityScoreService;

        $this->dataExchangeService = new DataExchangeService;
        $this->voToXml = new VoToXml;
        $this->xmlToCsv = new XmlToCsv;

        // include_once(APPPATH . "libraries/service/Cart_session_service.php");
        // $this->set_cart_srv(new Cart_session_service());
        // include_once(APPPATH . "helpers/image_helper.php");

        // include_once(APPPATH . 'libraries/service/Domain_platform_service.php');
        // $this->set_domain_platform_service(new Domain_platform_service());
        // include_once(APPPATH . 'libraries/service/Price_service.php');
        // $this->set_price_service(new Price_service());

        // include_once APPPATH . "libraries/service/Email_referral_list_service.php";
        // $this->set_email_referral_list_service(new Email_referral_list_service());
        // include_once APPPATH . "libraries/service/Fraudulent_order_service.php";
        // $this->set_fraudulent_order_service(new Fraudulent_order_service());
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
                if (empty($ps_obj) || ($ps_obj->getPaymentStatus() == "N" && $ps_obj->getPaymentGatewayId() == "google")) {
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
                if (($sp_obj = $this->getDao('SellingPlatform')->get(array("selling_platform_id" => $vars["platform_id"]))) && $sp_obj->getType() == "SKYPE") {
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
                    $client_last_order = $client_service->get_client_last_order($vars["client"]->getEmail());
                    $del_country_id = $client_last_order->get_delivery_country_id();
                    break;
            }
        }
        $amount = $curr_line_no = 0;
        $ca_soi_list = $all_del_info = [];
        foreach ($cart_list["cart"] as $line_no => $soi) {
            $curr_line_no = $line_no + 1;
            $soi_obj = clone $soi_vo;
            $soi_obj->setLineNo($line_no + 1);
            $soi_obj->setProdSku($soi["sku"]);
            $soi_obj->setProdName($soi["name"]);
            $soi_obj->setQty($soi["qty"]);
            $soi_obj->setVatTotal($soi["vat_total"]);
            $soi_obj->setGstTotal($soi["gst"]);
            if ($vars["biz_type"] == "special") {
                $soi["cost"] *= 1;
            }

            $soi_obj->setUnitPrice($soi["price"]);
            $soi_obj->setAmount($soi["total"]);
            $amount += ($soi["total"] + $soi["gst"]);

            if ($get_ca === true) {
                $where["dest_country_id"] = $del_country_id;
                $where["mainprod_sku"] = $soi["sku"];
                if ($mapped_ca_list = $this->getDao('ProductComplementaryAcc')->getMappedAccListWithName($where, $option, true)) {
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
            $is_complementary_acc = $this->getDao('ProductComplementaryAcc')->checkCat($soi["sku"], true);
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
                    $soi_obj->setLineNo($ca_line_no);
                    $soi_obj->setProdSku($obj->get_accessory_sku());
                    $soi_obj->setProdName($obj->getName());
                    $soi_obj->setQty($obj->get_quantity());
                    $soi_obj->setVatTotal(0.00);
                    $soi_obj->setGstTotal(0.00);
                    $soi_obj->setUnitPrice(0.00);
                    $soi_obj->setAmount(0);

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
                $soi_obj->setLineNo($line_no + 1);
                $soi_obj->setProdSku($soi["sku"]);
                $soi_obj->setProdName($soi["name"]);
                $soi_obj->setQty($soi["qty"]);

                if ($obj = $this->subjectDomainService->getDao('SubjectDomain')->get(["subject" => "MAX_DECLARE_VALUE.{$pbv_obj->getPlatformCountryId()}"])) {
                    $max_value = $obj->getValue();
                    $declared = min($max_value, $soi["total"]);
                } else {
                    $declared = $soi["total"] * $declared_pcent / 100;
                }

                $vat = round($declared * $vat_percent / 100, 2);
                $pbvat = $soi["total"] - $vat;
                $soi_obj->setUnitPrice(round($pbvat, 2));
                $soi_obj->setVatTotal(round($vat * $soi["qty"] * (1 - $vars["vat_exempt"]), 2));
                $soi_obj->setGstTotal($soi["gst"]);
                if ($vars["vat_exempt"]) {
                    $soi_obj->setAmount(round($pbvat * $soi["qty"], 2));
                } else {
                    $soi_obj->setAmount(round($soi["total"] * $soi["qty"], 2));
                }
                $so_item_list[] = $soi_obj;
                $special_list[] = $soi_obj;
                $amount += ($vars["vat_exempt"] ? $pbvat * $soi["qty"] : $soi["total"] * $soi["qty"]);
                $amount += $soi["gst"];
                $cost += $soi_obj->getVatTotal();
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
                        $client_last_order = $client_service->get_client_last_order($vars["client"]->getEmail());
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
            $lang_id = $pbv_obj->getLanguageId();
        }

        $type = $this->getDao('SellingPlatform')->get(array("selling_platform_id" => $vars["platform_id"]))->getType();

        $to_currency_id = $this->getDao('Config')->valueOf("func_curr_id");
        if ($er_obj = $this->getDao('ExchangeRate')->get(array("from_currency_id" => $vars["currency_id"], "to_currency_id" => $to_currency_id))) {
            $so_vo->set_rate($er_obj->getRate());
        }

        //added by Jack for rate in EUR
        if ($er_eur_obj = $this->getDao('ExchangeRate')->get(array("from_currency_id" => $vars["currency_id"], "to_currency_id" => "EUR"))) {
            $so_vo->set_ref_1($er_eur_obj->getRate());
        }

        //Promotion Code
        $so_vo->set_promotion_code(@call_user_func(array($vars["promo"]["promotion_code_obj"], "get_code")));
        $so_vo->set_client_promotion_code($vars["promotion_code"]);
        $so_vo->set_amount($so_vo->get_amount());

        $soid_dao = $this->getDao('SoItemDetail');
        $soid_vo = $soid_dao->get();
        $dao->db->trans_start();

        if (!isset($_SESSION["so_no"])) {
            $seqdao = $this->sequence_dao;
            $seqdao->set_seq_name("customer_order");
            $next_val = $seqdao->seq_next_val();
            $so_no = $next_val;
            $so_vo->setSoNo($so_no);
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
                if ($so_vo->getBizType() == "ONLINE" || $so_vo->getBizType() == "MOBILE") {
                    $_SESSION["NOTICE"] = "Error: " . __LINE__;
                } else {
                    $_SESSION["NOTICE"] = "Error: " . __LINE__ . $dao->db->_error_message();
                }
                $failed = 1;
            }
            if (!$failed) {
                foreach ($so_item_list as $soi_obj) {
                    $soi_obj->setSoNo($so_no);
                    if (!$soi_dao->insert($soi_obj)) {
                        if ($so_vo->getBizType() == "ONLINE" || $so_vo->getBizType() == "MOBILE") {
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
                    $ca_soid_list = [];
                    foreach ($cart_list["detail"] as $line_no => $soi) {
                        $curr_line_no = $line_no + 1;
                        foreach ($soi as $soid) {
                            if (is_array($soid["qty"])) {
                                $qty = $soid["qty"]["qty"];
                            } else {
                                $qty = $soid["qty"];
                            }
                            $soid_obj = clone $soid_vo;
                            $soid_obj->setSoNo($so_no);
                            $soid_obj->setLineNo($curr_line_no);
                            $soid_obj->set_item_sku($soid["sku"]);
                            $soid_obj->setQty($qty);
                            $soid_obj->setOutstandingQty($qty);
                            $soid_obj->set_unit_price($soid["price"] * 1);
                            $soid_obj->setVatTotal($soid["vat_total"]);
                            $soid_obj->setGstTotal($soid["gst"]);
                            $soid_obj->setDiscount($soid["discount"]);
                            $soid_obj->setAmount($soid["total"]);
                            $soid_obj->setPromoDiscAmt($soid["promo_disc_amt"] * 1);
                            $soid_obj->setCost($soid["cost"]);
                            $soid_obj->setItemUnitCost($soid["product_cost_obj"]->getItemCost());

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

                            $this->setProfitInfo($soid_obj);

                            #sbf #4424 - set raw profit info without promo codes
                            $this->set_profit_info_raw($soid_obj, $vars["platform_id"]);

                            if (!$soid_dao->insert($soid_obj)) {
                                // Front End don't show db error
                                if ($so_vo->getBizType() == "ONLINE" || $so_vo->getBizType() == "MOBILE") {
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
                            $this->initPriceService($type);
                            $soid_obj = clone $soid_vo;
                            # the cost for complementary accessory is supplier cost
                            $ca_priceobj = $this->price_service->get_dao()->get_list_with_bundle_checking($obj->get_accessory_sku(), $vars["platform_id"], $lang_id);
                            if ($ca_priceobj) {
                                foreach ($ca_priceobj as $value) {
                                    $soid_obj->setItemUnitCost($value->getItemCost());
                                    $cost = $value->get_supplier_cost();
                                }
                            }
                            $soid_obj->setSoNo($so_no);
                            $soid_obj->setLineNo($ca_line_no);
                            $soid_obj->set_item_sku($obj->get_accessory_sku());
                            $soid_obj->setQty($obj->get_quantity());
                            $soid_obj->setOutstandingQty($obj->get_quantity());
                            $soid_obj->set_unit_price(0.00);
                            $soid_obj->setVatTotal(0.00);
                            $soid_obj->setGstTotal(0.00);
                            $soid_obj->setDiscount(0.00);
                            $soid_obj->setAmount(0);
                            $soid_obj->setPromoDiscAmt(0.00);
                            $soid_obj->setCost($cost * $obj->get_quantity());
                            $soid_obj->set_profit(0.00);
                            $soid_obj->set_margin(0.00);

                            $ca_line_no++;

                            if (!$soid_dao->insert($soid_obj)) {
                                // Front End don't show db error
                                if ($so_vo->getBizType() == "ONLINE" || $so_vo->getBizType() == "MOBILE") {
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
                        $soid_obj->setSoNo($so_no);
                        $soid_obj->setLineNo($soid->get_line_no());
                        $soid_obj->set_item_sku($soid->get_prod_sku());
                        $soid_obj->setQty($soid->get_qty());
                        $soid_obj->setOutstandingQty($soid->get_qty());
                        $soid_obj->set_unit_price($soid->get_unit_price());
                        $soid_obj->setVatTotal($soid->get_vat_total());
                        $soid_obj->setGstTotal($soid->get_gst_total());
                        $soid_obj->setDiscount(0);
                        $soid_obj->setAmount($soid->get_amount());
                        $soid_obj->setCost($soid->get_vat_total());

                        $is_complementary_acc = $this->getDao('ProductComplementaryAcc')->checkCat($soid["sku"], true);
                        if ($is_complementary_acc["status"] === false) {
                            $this->setProfitInfo($soid_obj);
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
                    if ($so_vo->getBizType() == "ONLINE" || $so_vo->getBizType() == "MOBILE") {
                        if (!($so_vo->get_amount() == 0 && $vars["all_trial"] && $vars["all_virtual"])) {
                            $sops_vo->setSoNo($so_no);
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
                    } elseif ($so_vo->getBizType() == "OFFLINE" || $so_vo->getBizType() == "SPECIAL" || $so_vo->getBizType() == "MANUAL") {
                        $entity_id = $this->entityService->getEntityId($so_vo->get_amount(), $so_vo->get_currency_id());
                        $soext_vo->set_entity_id($entity_id);
                        $soext_vo->setSoNo($so_no);
                        $son_vo->setSoNo($so_no);
                        $son_vo->setNote($vars["so_extend"]["notes"]);

                        if ($soext_dao->insert($soext_vo) === FALSE) {
                            $_SESSION["NOTICE"] = "Error: " . __LINE__ . ": " . $son_dao->db->_error_message();
                            $failed = 1;
                        } elseif ($vars["so_extend"]["notes"] && $son_dao->insert($son_vo) === FALSE) {
                            $_SESSION["NOTICE"] = "Error: " . __LINE__ . ": " . $son_dao->db->_error_message();
                            $failed = 1;
                        }

                        if ($vars["so_extend"]["order_reason"] == 31) {
                            #SBF #2450 auto update so_priority_score to 1000 when "bulk sales" selected on phone sales
                            $this->getDao('SoPriorityScore')->insertSops($so_no, 1000);
                        }
                    }
                    if ($so_vo->getBizType() == "MANUAL" || $so_vo->getBizType() == "OFFLINE") {
                        if (!($so_vo->get_amount() == 0 && $vars["all_trial"] && $vars["all_virtual"])) {
                            $sops_vo->setSoNo($so_no);
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
            if ($so_vo->getBizType() == "ONLINE" || $so_vo->getBizType() == "MOBILE") {
                $_SESSION["NOTICE"] = "Error: " . __LINE__;
            } else {
                $_SESSION["NOTICE"] = "Error: " . __LINE__ . " " . $dao->db->_error_message();
            }
            $failed = 1;
        }
        if ($failed) {
            $dao->db->trans_rollback();
            $dao->db->trans_complete();
            return FALSE;
        } else {

            if ($vars["payment_gateway"] == "w_bank_transfer") {
                $_SESSION["W_TRANSFER_ORDER"] = $vars;
                $_SESSION["W_TRANSFER_ORDER"]["so_no"] = $so_no;
            }
            $dao->db->trans_complete();
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

    public function get_price_service()
    {
        return $this->price_service;
    }

    public function set_price_service(Base_service $srv)
    {
        $this->price_service = $srv;

        return $srv;
    }

    public function setProfitInfo($prod)
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
            $cur_platform_id = $this->getDao('So')->get(["so_no" => $prod->getSoNo()])->getPlatformId();
            $type = $this->getDao('SellingPlatform')->get(array("selling_platform_id" => $cur_platform_id))->getType();

            $this->initPriceService($type);
            $gst = @$prod->getGstTotal();
            $selling_price = ($prod->getAmount() + $gst) / $prod->getQty();
            $json = $this->priceService->getProfitMarginJson($cur_platform_id, $prod->getItemSku(), $selling_price);
            file_put_contents("/var/log/vb-json", "{$prod->getSoNo()} || $json", FILE_APPEND);

            $jj = json_decode($json, true);

            $prod->setCost(round($jj["get_cost"], 2));
            $prod->setProfit(round($jj["get_profit"], 2));
            $prod->setMargin(round($jj["get_margin"], 2));
        } else {
            $prod->setProfit(round($prod->getAmount() - $prod->getCost(), 2));
            if ($prod->getAmount()) {
                $prod->setMargin(round($prod->getProfit() / $prod->getAmount() * 100, 2));
            } else {
                $prod->setMargin(0);
            }
        }
    }

    public function initPriceService($platform_type)
    {
        if (is_null($platform_type)) {
            $this->priceService = new PriceService;
        } else {
            $filename = "Price" . ucfirst(strtolower($platform_type)) . "Service";
            $classname = ucfirst($filename);
            $this->priceService = new $classname;
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

            $platform_id = $this->getDao('So')->get(array("so_no" => $prod->getSoNo()))->getPlatformId();

            if ($platform_id) {
                $type = $this->getDao('SellingPlatform')->get(array("selling_platform_id" => $platform_id))->getType();

                $this->initPriceService($type);
                $gst = @$prod->getGstTotal();
                $unit_gst = $gst / $prod->getQty();
                $unit_selling_price = ($prod->get_unit_price() + $unit_gst);
                $json = $this->price_service->get_profit_margin_json($platform_id, $prod->getItemSku(), $unit_selling_price);

                $jj = json_decode($json, true);

                # WE DO NOT UPDATE COST HERE
                $prod->set_profit_raw(round($jj["get_profit"], 2));
                $prod->set_margin_raw(round($jj["get_margin"], 2));
            }
        } else {
            // mostly marketplaces orders come here. if via API, interface_so_item_detail doesn't have GST, so we calculate differently
            if (!$platform_id) {
                $platform_id = $this->getDao('So')->get(array("so_no" => $prod->getSoNo()))->getPlatformId();
            }

            if ($platform_id) {
                $this->initPriceService(PLATFORM_TYPE);
                $unit_selling_price = $prod->get_unit_price();
                $json = $this->price_service->get_profit_margin_json($platform_id, $prod->getItemSku(), $unit_selling_price);
                $jj = json_decode($json, true);

                # WE DO NOT UPDATE COST HERE
                $prod->set_profit_raw(round($jj["get_profit"], 2));
                $prod->set_margin_raw(round($jj["get_margin"], 2));
            }
        }
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

    public function splitOrderToSo($so_no, $order_group)
    {
        $so_dao = $this->getDao('So');
        $soext_dao = $this->getDao('SoExtend');
        $soid_dao = $this->getDao('SoItemDetail');
        $ordernotes_dao = $this->getDao('OrderNotes');
        $so_priority_score_dao = $this->getDao('SoPriorityScore');
        $so_holdreason_dao = $this->getDao('SoHoldReason');

        $input_so_no = $so_no;

        if ($so_obj = $so_dao->get(["so_no" => $so_no])) {
            if ($so_obj->getStatus() == 0 || $so_obj->getStatus() > 3 || $so_obj->getHoldStatus() != 0 || $so_obj->getRefundStatus() != 0) {
                $ret["status"] = FALSE;
                $ret["message"] = __LINE__ . " so_service. Error: <$so_no> Order is inactive/not yet credit checked/allocated/shipped/on hold/has split child/refund.";
                return $ret;
            }

            # If so_no is different from from split_so_group number, means this so_no is already a child of a previous split.
            # If so, get the real parent so_no
            $split_so_group = $so_obj->getSplitSoGroup();
            if ($split_so_group != $so_no && !empty($split_so_group)) {
                $input_so_obj = $so_obj;
                $so_obj = $so_dao->get(["so_no" => $so_obj->getSplitSoGroup()]);
                $so_no = $so_obj->getSoNo();
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
            $so_dao->db->trans_start();
            foreach ($order_group as $group => $arr) {
                // if failed on previous loops, don't bother going in
                if ($failed == 0) {
                    // new so_no for each group
                    $seqdao = $this->sequence_dao;
                    $seqdao->set_seq_name("customer_order");
                    $next_val = $seqdao->seq_next_val();
                    $new_so_no = $next_val;
                    $new_so_obj = $so_dao->get();
                    set_value($new_so_obj, $so_obj);

                    $new_so_obj->setSoNo($new_so_no);
                    $new_so_obj->setSplitSoGroup($so_no);
                    $new_so_obj->setSplitStatus(2);
                    $new_so_obj->setSplitCreateOn($ts);
                    $new_so_obj->setSplitCreateBy($id);

                    // if this parent has been split before, reset hold_status = 0
                    if ($so_obj->getHoldStatus() == 15) {

                        $new_so_obj->setHoldStatus(0);

                        $holdStatus = 0;
                        $this->updateIofHoldStatusBySo($so_no, $holdStatus);
                    }

                    # temporarily set as 0
                    $new_so_obj->setAmount(0.00);
                    $new_so_obj->setCost(0.00);
                    if ($so_dao->insert($new_so_obj)) {
                        $so_amount = $so_cost = $line_no = 0;

                        foreach ($arr as $v) {
                            // each SKU in the same order here

                            // since we already checked the quantity, we just get the first item in so_item and so_item_detail
                            // products recorded at the same order number should have the same price/cost/margin
                            $sku = $v["sku"];
                            $line_no++;

                            if (!($so_item_detail = $soid_dao->get(["so_no" => $so_no, "item_sku" => $sku]))) {
                                $ret["status"] = FALSE;
                                $ret["message"] = __LINE__ . " so_service. Error: SO item detail list not found for $so_no, sku $sku.";
                                return $ret;
                            }

                            $unit_vat = number_format(($so_item->getVatTotal() / $so_item->getQty()), 2, '.', '');
                            $unit_gst = number_format(($so_item->getGstTotal() / $so_item->getQty()), 2, '.', '');
                            $unit_amount_paid = number_format(($so_item->getAmount() / $so_item->getQty()), 2, '.', '');
                            $so_amount += ($unit_amount_paid + $unit_gst);     # actual selling price

                            // split order logic splits all SKUs into single item
                            $qty = 1;
                            $unit_cost = $so_item_detail->getCost();
                            $so_cost += ($unit_cost * $qty);
                            $unit_profit = $so_item_detail->getProfit();
                            $unit_promo_disc_amt = number_format(($so_item_detail->getPromoDiscAmt() / $so_item->getQty()), 2, '.', '');

                            $unit_profit = $so_item_detail->getProfit();
                            $unit_profit_raw = $so_item_detail->getProfitRaw();

                            $new_soid_obj = $soid_dao->get();
                            set_value($new_soid_obj, $so_item_detail);
                            $new_soid_obj->setSoNo($new_so_no);
                            $new_soid_obj->setLineNo($line_no);

                            # now each qty has single row, so we take the unit amount in soid as well
                            # normal case: soid.amount = unit amount paid * qty
                            $new_soid_obj->setAmount($unit_amount_paid);
                            $new_soid_obj->setQty($qty);
                            $new_soid_obj->setOutstandingQty($qty);
                            $new_soid_obj->setVatTotal($unit_vat);
                            $new_soid_obj->setGstTotal($unit_gst);
                            $new_soid_obj->setPromoDiscAmt($unit_promo_disc_amt);

                            if ($soid_dao->insert($new_soid_obj)) {
                                // success adding so_item and so_item_detail
                            } else {
                                $message .= __LINE__ . " so_service. Failed so_item. DB error: " . $soid_dao->db->_error_message() . "\n";
                                $failed = 1;
                            }
                        }

                        // no fail for the whole so group, update amount and cost for this new so
                        if (!$failed) {
                            // check if previous has so_extend. If have, create new so_no with duplicated info
                            if ($soext_obj = $soext_dao->get(["so_no" => $so_no])) {
                                $new_soext_obj = $soext_dao->get();
                                set_value($new_soext_obj, $soext_obj);
                                $new_soext_obj->setSoNo($new_so_no);

                                if ($soext_dao->insert($new_soext_obj) === FALSE) {
                                    $message .= __LINE__ . " so_service. Failed so_extend. DB error: " . $soext_dao->db->_error_message() . "\n";
                                    $failed = 1;
                                }
                            }

                            // check if so_payment_status available
                            if ($so_payment_status_obj = $this->getDao('SoPaymentStatus')->get(["so_no" => $so_no])) {
                                $new_so_pays_obj = $this->getDao('SoPaymentStatus')->get();
                                set_value($new_so_pays_obj, $so_payment_status_obj);
                                $new_so_pays_obj->setSoNo($new_so_no);
                                if ($this->getDao('SoPaymentStatus')->insert($new_so_pays_obj)) {
                                    // $order_notes, order_reason - did not add yet because specific to each new order
                                } else {
                                    $message .= __LINE__ . " so_service. Failed so_payment_status. DB error: " . $this->getDao('SoPaymentStatus')->db->_error_message() . "\n";
                                    $failed = 1;
                                }
                            }

                            // check if previously has priority score
                            if ($so_priority_score_obj = $so_priority_score_dao->get(["so_no" => $so_no])) {
                                $new_so_prioritys_obj = $so_priority_score_dao->get();
                                set_value($new_so_prioritys_obj, $so_priority_score_obj);
                                $new_so_prioritys_obj->setSoNo($new_so_no);

                                if ($so_priority_score_dao->insert($new_so_prioritys_obj) === FALSE) {
                                    $message .= __LINE__ . " so_service. Failed so_priority_score. DB error: " . $so_priority_score_dao->db->_error_message() . "\n";
                                    $failed = 1;
                                }
                            }

                            // check if previously has order notes
                            if ($ordernotes_obj = $ordernotes_dao->getList(array("so_no" => $so_no), array("orderby" => "create_on desc", "limit" => 1))) {
                                $new_ordernotes_obj = $ordernotes_dao->get();
                                set_value($new_ordernotes_obj, $ordernotes_obj);
                                $new_ordernotes_obj->setSoNo($new_so_no);

                                if ($ordernotes_dao->insert($new_ordernotes_obj) === FALSE) {
                                    $message .= __LINE__ . " so_service. Failed order_notes. DB error: " . $ordernotes_dao->db->_error_message() . "\n";
                                    $failed = 1;
                                }
                            }

                            if (!$failed) {
                                // all done for this group, update amount and cost for the new so
                                $new_so_obj->setAmount($so_amount);
                                $new_so_obj->setCost($so_cost);
                                $new_so_obj->setSplitCreateOn($ts);

                                if ($so_dao->update($new_so_obj)) {
                                    $addedsolist .= "$next_val, ";
                                    $seqdao->update_seq($next_val);

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
                $update_hold_status = false;
                if ($so_obj->getHoldStatus() <> 15) {
                    $update_hold_status = true;
                    $holdStatus = 15;
                }
                $so_obj->setHoldStatus(15);  # set to has split child
                $so_obj->setSplitStatus(2);
                $so_obj->setSplitCreateBy($id);
                $so_obj->setSplitCreateOn($ts);
                if ($so_dao->update($so_obj) === FALSE) {
                    $message .= __LINE__ . " so_service. Failed update parent so $so_no. DB error: " . $so_dao->db->_error_message() . "\n";
                    $failed = 1;
                } else {
                    if ($update_hold_status) {
                        $this->updateIofHoldStatusBySo($so_no, $holdStatus);
                    }
                    $hr_obj = $this->sc['So']->getDao('HoldReason')->get(['reason_cat'=>'OT','reason_type'=>'created_split','status'=>1]);
                    if (!$hr_obj) {
                        $reason_obj = $this->sc['So']->getDao('HoldReason')->get();
                        $reason_obj->setReasonCat('OT');
                        $reason_obj->setReasonType('created_split');
                        $reason_obj->setDescription('Created Split');

                        $hr_obj = $this->sc['So']->getDao('HoldReason')->insert($reason_obj);
                    }
                    $sohr_vo = $so_holdreason_dao->get();
                    $sohr_vo->setSoNo($so_no);
                    $sohr_vo->setReason($hr_obj->getId());
                    $this->getDao('SoHoldReason')->insert($sohr_vo);
                }

                if (isset($input_so_obj)) {
                    // if we are splitting a child, then we set the child as inactive
                    $update_status = false;
                    if ($input_so_obj->getStatus() <> 0) {
                        $update_status = true;
                        $status = 0;
                    }

                    $input_so_obj->setStatus(0);
                    if ($so_dao->update($input_so_obj) === FALSE) {
                        $message .= __LINE__ . " so_service. Failed update original input so_no {$input_so_obj->getSoNo()}. DB error: " . $so_dao->db->_error_message() . "\n";
                        $failed = 1;
                    } else {
                        if ($update_status) {
                            $this->updateIofStatusBySo($so_no, $status);
                        }
                    }
                }
            }

            if ($failed) {
                $so_dao->db->trans_rollback();
                $so_dao->db->trans_complete();

                $ret["status"] = FALSE;
                $ret["message"] = $message;
            } else {
                $so_dao->db->trans_complete();
                $ret["status"] = TRUE;
                $ret["message"] = "Success. Added so_no: $addedsolist";
            }
        } else {
            $ret["status"] = FALSE;
            $ret["message"] = __LINE__ . "so_service. SO not found for $so_no.";
        }
        return $ret;
    }

    public function getRefundableList($where = [], $option = [])
    {
        if ($option["num_row"] != "") {
            return $this->getDao('So')->getRefundableOrder($where, ["num_row" => 1, "create" => $option["create"]]);
        } else {
            return $this->getDao('So')->getRefundableOrder($where, $option);
        }
    }

    public function checkIfPacked($so_no = "")
    {
        return $this->getDao('SoAllocate')->getList(["so_no" => $so_no, "status <" => "3", "status >" => "0"]);
    }

    public function getShipNoList($type = "object", $service = "")
    {
        return $this->getDao('SoShipment')->getShNoList($type, $service);
    }

    public function get_refund_item_w_name($where = [])
    {
        return $this->getDao('SoItemDetail')->getListWithProdname($where);
    }

    public function getDispatchData($where = [], $from_date = '', $to_date = '')
    {
        return $this->getDao('So')->getDispatchData($where, $from_date, $to_date);
    }

    public function getItemsWithName($where = [], $option = [], $dto = "")
    {
        return $this->getDao('SoItemDetail')->getItemsWithName($where, $option, $dto);
    }

    public function getPrintInvoiceContent($so_no_list = [], $gen_pdf = 0, $lang_id = "")
    {
        $run = 0;
        $website_domain = $this->getDao('Config')->valueOf('website_domain');
        $website_domain = base_url();
        $total_cnt = count($so_no_list);
        $cursign_arr = $this->currencyService->getNameWithIdKey();
        # sbf #3746 don't include complementary accessory on front end
        $ca_catid_arr = implode(',', $this->getDao('ProductComplementaryAcc')->getAccessoryCatidArr());

        if (count($so_no_list)) {
            $valid = 0;
            $content = "";

            foreach ($so_no_list as $obj) {
                $run++;
                $so_obj = $this->getDao('So')->get(array("so_no" => $obj));

                if (!$so_obj) {
                    continue;
                } else {
                    $cur_platform_id = $so_obj->getPlatformId();
                    $pbv_obj = $this->getDao('PlatformBizVar')->get(array("selling_platform_id" => $cur_platform_id));
                    if (empty($lang_id)) {
                        $lang_id = $pbv_obj->getLanguageId();
                    }

                    $replace = [];

                    // get language template
                    $country_id = $pbv_obj->getPlatformCountryId();

                    if (file_exists(APPPATH . "language/template_service/" . $lang_id . "/customer_invoice.ini")) {
                        $data_arr = parse_ini_file(APPPATH . "language/template_service/" . $lang_id . "/customer_invoice.ini");
                    }
                    if (!is_null($data_arr)) {
                        $replace = array_merge($replace, $data_arr);
                    }

#                   SBF #2960 Add NIF/CIF to invoice if info was supplied
#                   SBF #4330 also for IT page
                    $client_obj = $this->getDao('Client')->get(array("id" => $so_obj->getClientId()));
                    $client_id_no = $client_obj->getClientIdNo();
                    $replace["company_name"] = $client_obj->getCompanyname();
                    if (($cur_platform_id == "WEBES" || $cur_platform_id == "WEBIT") && $client_id_no) {
                        $replace["client_id_no"] = <<<html
                        <p>$client_id_no</p>
                        <p></p>
html;

                    } else {
                        $replace["label_client_id_no"] = "";
                        $replace["client_id_no"] = "";
                    }
                    $replace['invoice'] = 'Invoice';

                    if ($site_config_arr = $this->getSiteConfig($cur_platform_id)) {
                        $replace = array_merge($replace, $site_config_arr);
                    }

                    $html = $this->getService('Template')->getFileTempalte(array("tpl_id" => 'shipped_invoice', "platform_id" => $cur_platform_id), $replace);

                    if ($html === false) {
                        $html = "";
                    }


                    $replace["isAmazon"] = 0;
                    $replace["sales_email"] = $this->getSalesEmail($lang_id);
                    $replace["csemail"] = $this->getCsSupportEmail($lang_id);
                    $replace["return_email"] = $this->getReturnEmail($lang_id);

                    $itemlist = $this->getDao('SoItemDetail')->getItemsWithName(array("so_no" => $obj, "p.cat_id NOT IN ($ca_catid_arr)" => NULL));
                    $so_ext_obj = $this->getDao('SoExtend')->get(["so_no" => $obj]);

                    $replace["website_domain"] = base_url();
                    $replace["cursign"] = $cursign_arr[$so_obj->getCurrencyId()];

                    if ($so_obj->getSplitSoGroup())
                        $new_so_no = $so_obj->getSplitSoGroup() . "/" . $so_obj->getSoNo();
                    else
                        $new_so_no = $so_obj->getSoNo();

                    $replace["order_no"] = $so_obj->getClientId() . "-" . $new_so_no;
                    $replace["amazon_order_no"] = $so_obj->getPlatformOrderId();
                    $replace["order_date"] = date("d/m/Y", strtotime($so_obj->getOrderCreateDate()));
                    $bcountry_obj = $this->getDao('Country')->get(array("country_id" => $so_obj->getBillCountryId()));
                    list($bill_addr_1, $bill_addr_2, $bill_addr_3) = explode("|", $so_obj->getBillAddress());
                    $bstatezip = trim($so_obj->getBillState() . ", " . $so_obj->getBillPostcode());
                    if ($bstatezip != ",") {
                        $bstatezip = ereg_replace("^, ", "", $bstatezip);
                        $bstatezip = ereg_replace(",$", "", $bstatezip) . "<br>";
                    } else {
                        $bstatezip = "";
                    }
                    $replace["billing_name"] = $so_obj->getBillName();
                    $replace["billing_address"] = ($so_obj->getBillCompany() == "" ? "" : $so_obj->getBillCompany() . "<br/>") . $bill_addr_1 . "<br/>" . ($bill_addr_2 == "" ? "" : $bill_addr_2 . "<br/>") . ($bill_addr_3 == "" ? "" : $bill_addr_3 . "<br/>") . $so_obj->getBillCity() . "<br>" . $bstatezip . $bcountry_obj->getName();
                    list($delivery_addr_1, $delivery_addr_2, $delivery_addr_3) = explode("|", $so_obj->getDeliveryAddress());
                    $dcountry_obj = $this->getDao('Country')->get(array("country_id" => $so_obj->getDeliveryCountryId()));
                    $dstatezip = trim($so_obj->getDeliveryState() . ", " . $so_obj->getDeliveryPostcode());
                    if ($dstatezip != ",") {
                        $dstatezip = ereg_replace("^, ", "", $dstatezip);
                        $dstatezip = ereg_replace(",$", "", $dstatezip) . "<br>";
                    } else {
                        $dstatezip = "";
                    }
                    $replace["delivery_name"] = $so_obj->getDeliveryName();
                    $replace["delivery_address"] = ($so_obj->getDeliveryCompany() == "" ? "" : $so_obj->getDeliveryCompany() . "<br/>") . $delivery_addr_1 . "<br/>" . ($delivery_addr_2 == "" ? "" : $delivery_addr_2 . "<br/>") . ($delivery_addr_3 == "" ? "" : $delivery_addr_3 . "<br/>") . $so_obj->getDeliveryCity() . "<br>" . $dstatezip . $dcountry_obj->getName();

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

                        $prod_obj = $this->getDao('Product')->get(array("sku" => $item_obj->getMainProdSku()));
                        $amount_total = $item_obj->getAmount();
                        $vat_total = $item_obj->getVatTotal();
                        $amount_total_bvat = $amount_total - $vat_total;
                        $unit_price_bvat = round(($amount_total - $vat_total) / $item_obj->getQty(), 2);
                        $tmp = $this->getDao('Product')->get(array("sku" => $item_obj->getMainProdSku()));
                        $imagepath = get_image_file($tmp->getImage(), 's', $tmp->getSku());
                        $width_col_1 = $width_col_2 = "";
                        $warranty_month = $item_obj->getWarrantyInMonth();
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
                                                <td ' . $width_col_2 . ' align="left">' . $item_obj->getItemSku() . ' - ' . $item_obj->getName() . '<br/><br/>' . $warrantyname . ': ' . $warranty_month . '</td>
                                                <td align="right">' . platform_curr_format($item_obj->getUnitPrice()) . '</td>
                                                <td align="right">' . $item_obj->getQty() . '</td>
                                                <td align="right"><b>' . platform_curr_format($amount_total) . '</b></td>
                                            </tr>';
                        $bvat += $amount_total_bvat;
                        $vat += $vat_total;
                        $sum += $amount_total;
                    }
                    $replace["item_information"] = $item_information;

                    $sum_total = platform_curr_round($cur_platform_id, $sum);
                    $sum_vat = platform_curr_round($cur_platform_id, $vat);

                    $sum_bvat = platform_curr_round($cur_platform_id, $bvat);
                    $replace["sum_total"] = platform_curr_format($sum_total);
                    $replace["sum_vat"] = platform_curr_format($sum_vat);
                    $replace["sum_bvat"] = platform_curr_format($sum_bvat);

                    $sid_bvat = "";
                    $sid_vat = "";
                    $sid = $so_obj->getDeliveryCharge();
                    if ($so_ext_obj && $so_ext_obj->getVatexempt() == "0") {
                        $sid_vat = $sid * $so_obj->getVatPercent() / (100 + $so_obj->getVatPercent());

                    } else {
                        $sid_vat = 0;
                    }
                    $sid = platform_curr_round($cur_platform_id, $sid);
                    $sid_vat = platform_curr_round($cur_platform_id, $sid_vat);
                    $sid_bvat = platform_curr_round($cur_platform_id, $sid - $sid_vat);
                    $replace["currency"] = $so_obj->getCurrencyId();
                    $replace["promotion_code"] = $so_obj->getPromotionCode();
                    $replace["sid"] = platform_curr_format($sid);
                    $replace["sid_vat"] = platform_curr_format($sid_vat);
                    $replace["sid_bvat"] = platform_curr_format($sid_bvat);

                    $ofee = $ofee_vat = $ofee_bvat = 0;
                    $extobj = $this->getDao('SoExtend')->get(array("so_no" => $so_obj->getSoNo()));
                    if ($extobj) {
                        if ($extobj->getOfflineFee() > 0) {
                            $replace["offline_fee"] = '<tr>
                                <td colspan="2">&nbsp;</td>
                                <td colspan="2" align="right" bgcolor="#DDDDDD" style="border:1px solid #BBBBBB; border-width:0px 0px 1px 1px;"><b>' . $replace["offline_fee"] . '</b></td>
                                <td align="right" bgcolor="#F0F0F0" valign="top" style="border:1px solid #BBBBBB; border-width:0px 1px 1px 1px;"><b>' . platform_curr_format($extobj->getOfflineFee()) . '</b></td>
                            </tr>';
                            $ofee = platform_curr_round($cur_platform_id, $extobj->getOfflineFee());
                            $ofee_vat = platform_curr_round($cur_platform_id, $ofee * $so_obj->getVatPercent() / (100 + $so_obj->getVatPercent()));
                            $ofee_bvat = platform_curr_round($cur_platform_id, $ofee - $ofee_vat);
                        } else if ($extobj->getOfflineFee() < 0) {
                            $replace["offline_fee"] = '<tr>
                                <td colspan="2">&nbsp;</td>
                                <td colspan="2" align="right" bgcolor="#DDDDDD" style="border:1px solid #BBBBBB; border-width:0px 0px 1px 1px;"><b>' . $replace["discount"] . '</b></td>
                                <td align="right" bgcolor="#F0F0F0" valign="top" style="border:1px solid #BBBBBB; border-width:0px 1px 1px 1px;"><b>' . platform_curr_format($extobj->getOfflineFee()) . '</b></td>
                            </tr>';
                            $ofee = platform_curr_round($cur_platform_id, $extobj->getOfflineFee());
                            $ofee_vat = platform_curr_round($cur_platform_id, $ofee * $so_obj->getVatPercent() / (100 + $so_obj->getVatPercent()));
                            $ofee_bvat = platform_curr_round($cur_platform_id, $ofee - $ofee_vat);
                        } else {
                            $replace["offline_fee"] = "";
                        }
                        //#2182 add the processing fee
                        $processing_fee = $extobj->getOfflineFee();
                    }
                    if (is_null($processing_fee)) {
                        $processing_fee = 0;
                    }

                    $replace["processing_fee"] = platform_curr_format($processing_fee);
                    $replace["total"] = $so_obj->getAmount();
                    $replace["total_vat"] = platform_curr_round($cur_platform_id, $replace["total"] * $so_obj->getVatPercent() / (100 + $so_obj->getVatPercent()));
                    $replace["total_bvat"] = platform_curr_round($cur_platform_id, $replace["total"] - $replace["total_vat"]);
                    if (!$replace["payment_method"] = $this->getSoPaymentGateway($so_obj->getSoNo())) {
                        $replace["payment_method"] = "N/A";
                    }
                    //#2182 add the processing fee to the total, also add the delivery charge
                    $replace["grand_total"] = platform_curr_format($so_obj->getAmount());

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

    public function getSiteConfig($platform_id)
    {
        if ($obj = $this->getDao('SiteConfig')->get(['platform'=>$platform_id, 'domain_type'=>'1', 'status'=>1])) {
            $arr = [];
            $arr['site_url'] = str_replace(['http://','https://','/'],"", $obj->getDomain());
            $arr['site_name'] = $obj->getSiteName();
            $arr['lang'] = $obj->getLang();
            $arr['logo'] = $obj->getLogo();
            $arr['email'] = $obj->getEmail();

            return $arr;
        }
        return false;
    }
    public function getSalesEmail($lang_id)
    {
        switch ($lang_id) {
            default:
                $email = "no-reply@valuebasket.com";
                break;
        }
        return $email;
    }

    public function getCsSupportEmail($lang_id)
    {
        switch ($lang_id) {
            default:
                $email = "no-reply@valuebasket.com";
                break;
        }
        return $email;
    }

    public function getReturnEmail($lang_id)
    {
        switch ($lang_id) {
            default:
                $email = "no-reply@valuebasket.com";
                break;
        }
        return $email;
    }

    public function getSoPaymentGateway($so_no = '')
    {
        if ($so_no != '') {
            if ($sops_obj = $this->getDao('SoPaymentStatus')->get(['so_no' => $so_no])) {
                if ($pg_obj = $this->getDao('PaymentGateway')->get(['payment_gateway_id' => $sops_obj->getPaymentGatewayId()])) {
                    $payment_gateway = (is_null($pg_obj->getName()) ? '' : $pg_obj->getName());
                    return $payment_gateway;
                }
            }
        }

        return '';
    }

    public function fire_preorder_delay_email($so_no)
    {
        $so_obj = $this->getDao('So')->get(array("so_no" => $so_no));
        $client = $this->getDao('Client')->get(array("id" => $so_obj->getClientId()));

        # sbf #3746 don't include complementary accessory on front end
        $ca_catid_arr = implode(',', $this->getDao('ProductComplementaryAcc')->getAccessoryCatidArr());

        $platform_id = $so_obj->getPlatformId();
        $pbv = $this->getDao('PlatformBizVar')->get(array("selling_platform_id" => $platform_id));
        $lang_id = $pbv->get_language_id();
        $so_items = $this->getDao('SoItemDetail')->getItemsWithName(array("so_no" => $so_obj->getSoNo(), "p.cat_id NOT IN ($ca_catid_arr)" => NULL), array("lang_id" => $lang_id));

        $old_edd = $so_obj->get_expect_delivery_date();
        $new_edd = "";
        foreach ($so_items as $item_obj) {
            $prod_obj = $this->getDao('Product')->get(array("sku" => $item_obj->getMainProdSku()));
            $new_edd = $prod_obj->get_expected_delivery_date();
            $prod_name .= $item_obj->getName();
        }

        $email_subject = "[Panther] preorder delay email alert";
        $headers .= 'From: Admin <admin@valuebasket.com>' . "\r\n";
        if ($old_edd == $new_edd) {
//alert
            $message = "User sending wrong delay order, so_no:" . $so_obj->getSoNo();
            $message .= ", old:" . $old_edd . "= new:" . $new_edd;
            mail("oswald-alert@eservicesgroup.com", $email_subject, $message, $headers);
        } else if ($new_edd == "") {
//alert
            $message = "User sending unexpected delay order email, so_no:" . $so_obj->getSoNo();
            $message .= ", there is no new:" . $new_edd;
            $message .= ", please check the new product EDD";
            mail("oswald-alert@eservicesgroup.com", $email_subject, $message, $headers);
        } else if (strtotime($old_edd) > strtotime($new_edd)) {
//EDD is earlier, alert
            $message = "User sending wrong delay order, so_no:" . $so_obj->getSoNo();
            $message .= ", old:" . $old_edd . "> new:" . $new_edd;
            $message .= ", please check the new product EDD";
            mail("oswald-alert@eservicesgroup.com", $email_subject, $message, $headers);
        } else {
            $dto = new EventEmailDto;
            $dto->setEventId("preorder_delay");
            $dto->setTplId("preorder_delay");
            $dto->setLangId($lang_id);

            $replace["forename"] = $client->getForename();
            $replace["so_items_pre_order"] = $prod_name;
            $replace["expect_delivery_date"] = $new_edd;
            $replace["client_id"] = $so_obj->getClientId();
            $replace["so_no"] = $so_obj->getSoNo();

            include_once(APPPATH . "hooks/country_selection.php");
            $country_id = strtolower($pbv->get_platform_country_id());
            $replace["site_url"] = Country_selection::rewrite_domain_by_country("www.valuebaset.com", $country_id);;
            $replace["site_name"] = Country_selection::rewrite_site_name($replace["site_url"]);
            $replace["image_url"] = $this->getDao('Config')->valueOf("default_url");
            $replace["logo_file_name"] = $this->getDao('Config')->valueOf("logo_file_name");

            $dto->setReplace($replace);
            $dto->setMailTo($client->getEmail());
            $this->eventService->fireEvent($dto);
        }
    }

    public function getPreorderList($where = [], $option = [])
    {
        return $this->getDao('So')->getPreorderList($where, $option);
    }

    public function getDeliveryNoteContent($so_no_list = [])
    {
        $content = "";
        if ($so_no_list) {
            $tpl_id = "delivery_note";
            $content .= @file_get_contents(APPPATH . $this->getDao('Config')->valueOf("tpl_path") . $tpl_id . "/" . $tpl_id . "_header.html");
            foreach ($so_no_list as $so_no) {
                if ($so_obj = $this->getDao('So')->get(["so_no" => $so_no])) {
                    $cur_platform_id = $so_obj->getPlatformId();
                    if (!isset($ar_pbv_obj[$cur_platform_id])) {
                        $ar_pbv_obj[$cur_platform_id] = $this->getDao('PlatformBizVar')->get(array("selling_platform_id" => $cur_platform_id));
                    }

                    if ($pbv_obj = $ar_pbv_obj[$cur_platform_id]) {
                        $replace = [];

                        $cur_lang_id = $pbv_obj->getLanguageId();
                        if (!isset($ar_lang[$cur_lang_id])) {
                            include_once APPPATH . "language/ORD001001_" . $cur_lang_id . ".php";
                            $ar_lang[$cur_lang_id] = $lang;
                        }

                        if ($so_obj->getSplitSoGroup()) {
                            $new_so_no = $so_obj->getSplitSoGroup() . "/$so_no";
                        } else {
                            $new_so_no = $so_no;
                        }

                        $replace["so_no"] = $new_so_no;
                        $replace["client_id"] = $so_obj->getClientId();
                        $replace["order_create_date"] = date("d/m/Y", strtotime($so_obj->getOrderCreateDate()));
                        $replace["delivery_name"] = $so_obj->getDeliveryName();
                        $country = $this->getDao('Country')->get(array("country_id" => $so_obj->getDeliveryCountryId()));
                        $billing_country = $this->getDao('Country')->get(array("country_id" => $so_obj->getBillCountryId()));
                        $replace["delivery_address_text"] = ($so_obj->getDeliveryCompany() ? $so_obj->getDeliveryCompany() . "\n" : "") . trim(str_replace("|", "\n", $so_obj->getDeliveryAddress())) . "\n" . $so_obj->getDeliveryCity() . " " . $so_obj->getDeliveryState() . " " . $so_obj->getDeliveryPostcode() . "\n" . $country->getName();
                        $replace["delivery_address"] = nl2br($replace["delivery_address_text"]);
                        $replace["billing_name"] = $so_obj->getBillName();
                        $replace["billing_address_text"] = ($so_obj->getBillCompany() ? $so_obj->getBillCompany() . "\n" : "") . trim(str_replace("|", "\n", $so_obj->getBillAddress())) . "\n" . $so_obj->getBillCity() . " " . $so_obj->getBillState() . " " . $so_obj->getBillPostcode() . "\n" . $billing_country->getName();
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
                        $replace["return_email"] = $this->getReturnEmail($cur_lang_id);
                        $replace["cs_support_email"] = $this->getCsSupportEmail($cur_lang_id);

                        # sbf #3746 don't include complementary accessory on front end
                        $ca_catid_arr = implode(',', $this->getDao('ProductComplementaryAcc')->getAccessoryCatidArr());
                        $option["show_ca"] = 1; # 4404 - show CA on delivery note only

                        if ($itemlist = $this->getDao('SoItemDetail')->getItemsWithName(["so_no" => $so_no], $option)) {
                            foreach ($itemlist as $item_obj) {
                                $tmp = $this->getDao('Product')->get(["sku" => $item_obj->getMainProdSku()]);
                                $replace["so_items"] .= "
                                <tr>
                                    <td style='padding: 5' valign='top'>{$item_obj->getItemSku()} - {$tmp->getName()}</td>
                                    <td width='30' align='right' valign='top' style='padding-right: 5'>{$item_obj->getQty()}</td>
                                </tr>";
                            }
                        }
                        $replace["barcode"] = "<img src='" . base_url() . "order/integrated_order_fulfillment/get_barcode/$so_no' style='float:right'>";
                        if ($tpl_content = $this->templateService->getFileTempalte(["tpl_id"=>$tpl_id], $replace)) {
                            $content .= $tpl_content;
                        }
                    }
                }
            }
            $content .= @file_get_contents(APPPATH . $this->getDao('Config')->valueOf("tpl_path") . $tpl_id . "/" . $tpl_id . "_footer.html");
        }
        return $content;
    }

    public function getOrderPackingSlipContent($so_no_list = [])
    {
        $content = "";
        if ($so_no_list) {
            $tpl_id = "order_packing_slip";
            $content .= @file_get_contents(APPPATH . $this->getDao('Config')->valueOf("tpl_path") . $tpl_id . "/" . $tpl_id . "_header.html");

            foreach ($so_no_list as $so_no) {
                if ($so_obj = $this->getDao('So')->get(["so_no" => $so_no])) {
                    $cur_platform_id = $so_obj->getPlatformId();
                    if (!isset($ar_pbv_obj[$cur_platform_id])) {
                        $ar_pbv_obj[$cur_platform_id] = $this->getDao('PlatformBizVar')->get(array("selling_platform_id" => $cur_platform_id));
                    }

                    if ($pbv_obj = $ar_pbv_obj[$cur_platform_id]) {
                        $replace = [];

                        $cur_lang_id = 'en';
                        if (!isset($ar_lang[$cur_lang_id])) {
                            include_once APPPATH . "language/ORD001001_" . $cur_lang_id . ".php";
                            $ar_lang[$cur_lang_id] = $lang;
                        }

                        if ($so_obj->getSplitSoGroup())
                            $new_so_no = $so_obj->getSplitSoGroup() . "/$so_no";
                        else
                            $new_so_no = $so_no;

                        $replace["so_no"] = $new_so_no;
                        $replace["client_id"] = $so_obj->getClientId();
                        $replace["order_create_date"] = date("d/m/Y", strtotime($so_obj->getOrderCreateDate()));
                        $replace["delivery_name"] = $so_obj->getDeliveryName();
                        $country = $this->getDao('Country')->get(array("country_id" => $so_obj->getDeliveryCountryId()));
                        $billing_country = $this->getDao('Country')->get(array("country_id" => $so_obj->getBillCountryId()));
                        $replace["delivery_address_text"] = ($so_obj->getDeliveryCompany() ? $so_obj->getDeliveryCompany() . "\n" : "") . trim(str_replace("|", "\n", $so_obj->getDeliveryAddress())) . "\n" . $so_obj->getDeliveryCity() . " " . $so_obj->getDeliveryState() . " " . $so_obj->getDeliveryPostcode() . "\n" . $country->getName();
                        $replace["delivery_address"] = nl2br($replace["delivery_address_text"]);
                        $replace["billing_name"] = $so_obj->getBillName();
                        $replace["billing_address_text"] = ($so_obj->getBillCompany() ? $so_obj->getBillCompany() . "\n" : "") . trim(str_replace("|", "\n", $so_obj->getBillAddress())) . "\n" . $so_obj->getBillCity() . " " . $so_obj->getBillState() . " " . $so_obj->getBillPostcode() . "\n" . $billing_country->getName();
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
                        $replace["return_email"] = $this->getReturnEmail($cur_lang_id);
                        $replace["cs_support_email"] = $this->getCsSupportEmail($cur_lang_id);

                        # also include complementary accessory
                        if ($itemlist = $this->getDao('SoItemDetail')->getListWithProdname(array("so_no" => $so_no))) {
                            $no_show_sku = array('15772-AA-BK', '15772-AA-WH'); //those sku will not show on packing slip

                            foreach ($itemlist as $item_obj) {
                                $item_obj_sku = $item_obj->getItemSku();
                                if (in_array($item_obj_sku, $no_show_sku) === false) {
                                    $tmp = $this->getDao('Product')->get(array("sku" => $item_obj->getItemSku()));
                                    $imagepath = base_url() . get_image_file($tmp->getImage(), 's', $tmp->getSku());
                                    $replace["so_items"] .= "
                        <tr>
                            <td align='center'><img src='{$imagepath}'></td>
                            <td valign=top style='font-size:20px'>{$item_obj->getItemSku()} - {$item_obj->getName()}</td>
                            <td valign=top style='font-size:20px'>{$item_obj->getQty()}</td>
                            <td></td>
                            <td></td>
                        </tr>";
                                }
                            }
                        }
                        $replace["barcode"] = "<img src='" . base_url() . "order/integrated_order_fulfillment/get_barcode/$so_no' style='float:right'>";
                        if ($tpl_obj = $this->templateService->getMsgTplWithAtt(array("id" => $tpl_id, "lang_id" => $cur_lang_id), $replace)) {
                            $content .= $tpl_obj->template->getMessage();
                        }
                    }
                }
            }
            $content .= @file_get_contents(APPPATH . $this->getDao('Config')->valueOf("tpl_path") . $tpl_id . "/" . $tpl_id . "_footer.html");
        }

        return $content;
    }

    public function getCustomInvoiceContent($so_no_list = [], $new_shipper_name = "", $currency = "")
    {
        $so_lang_arr = ["AMUK" => "en", "WSGB" => "en", "AMFR" => "en", "AMDE" => "en", "AMUS" => "en"];
        $run = 0;
        $website_domain = $this->getDao('Config')->valueOf('website_domain');
        $total_cnt = count($so_no_list);
        $cursign_arr = ["GBP" => "GBP", "EUR" => "GBP"];

        # sbf #3746 don't include complementary accessory on front end
        $ca_catid_arr = implode(',', $this->getDao('ProductComplementaryAcc')->getAccessoryCatidArr());

        if (count($so_no_list)) {
            $valid = 0;
            $content = "";
            include_once APPPATH . "data/customInvoice.php";
            $clean_body = $body;
            foreach ($so_no_list as $obj) {
                $sum = 0;

                $run++;
                $so_obj = $this->getDao('So')->get(["so_no" => $obj]);
                if (!$so_obj) {
                    continue;
                } else {
                    $data = [];
                    $itemlist = $this->getDao('SoItemDetail')->getItemsWithName(["so_no" => $obj, "p.cat_id NOT IN ($ca_catid_arr)" => NULL]);

                    $client_obj = $this->getDao('Client')->get(["id" => $so_obj->getClientId()]);

                    for ($i = 1; $i < 7; $i++) {
                        ${"daddr_" . $i} = "&nbsp;";
                    }
                    $data["date_of_invoice"] = date("d/m/Y");
                    $data["shipper_name"] = "Sunshine Express";
                    $data["shipper_contact"] = "";
                    $data["shipper_phone"] = "852-39043034";
                    $data["saddr_1"] = "Workshop A 10/F,";
                    $data["saddr_2"] = "WAH SHING INDUSTRIAL BUILDING";
                    $data["saddr_3"] = "18 CHEUNG SHUN STREET";
                    $data["saddr_4"] = "LAI CHI KOK";
                    $data["saddr_5"] = "HongKong";
                    $data["saddr_6"] = "&nbsp;";
                    $data["date"] = date("d/m/Y");
                    $data["order_number"] = $so_obj->getSoNo();
                    $data["deliver_name"] = $so_obj->getDeliveryName();
                    $data["client_id"] = $so_obj->getClientId();
                    $line_no = 1;
                    list($delivery_addr_1, $delivery_addr_2, $delivery_addr_3) = explode("|", $so_obj->getDeliveryAddress());
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
                    if ($so_obj->getDeliveryCity() != "") {
                        $csz .= $so_obj->getDeliveryCity() . ", ";
                    }
                    if ($so_obj->getDeliveryState() != "") {
                        $csz .= $so_obj->getDeliveryState();
                    }
                    if ($so_obj->getDeliveryPostcode() != "") {
                        $csz .= " " . $so_obj->getDeliveryPostcode();
                    }
                    $csz = @preg_replace("{, $}", "", $csz);
                    if (trim($csz)) {
                        $data["daddr_" . $line_no] = $csz;
                        $line_no++;
                    }
                    $data["daddr_" . $line_no] = $so_obj->getDeliveryCountryId();

                    $dcountry_obj = $this->getDao('Country')->get(["country_id" => $so_obj->getDeliveryCountryId()]);
                    $dstatezip = trim($so_obj->getDeliveryState() . ", " . $so_obj->getDeliveryPostcode());
                    if ($dstatezip != ",") {
                        $dstatezip = @preg_replace("{^, }", "", $dstatezip);
                        $dstatezip = @preg_replace("{,$}", "", $dstatezip) . "<br>";
                    } else {
                        $dstatezip = "";
                    }

                    $item_information = $declared_ratio = "";
                    if (in_array($so_obj->getDeliveryCountryId(), ["AU"])) {
                        foreach ($itemlist AS $item_obj) {
                            $prod_obj = $this->getDao('Product')->get(["sku" => $item_obj->getMainProdSku()]);
                            $value = round($item_obj->getAmount() / $item_obj->getQty() * $so_obj->getRate(), 2);
                            $sum += $value * $item_obj->getQty();
                        }
                        if ($obj = $this->subjectDomainService->getDao('SubjectDomain')->get(["subject" => "MAX_DECLARE_VALUE.{$so_obj->getDeliveryCountryId()}"])) {
                            if ($obj->getValue() <= $sum) {
                                $declared_ratio = $obj->getValue() / $sum;
                            }
                        }
                    }
                    if (in_array($so_obj->getDeliveryCountryId(), ["NZ"])) {
                        foreach ($itemlist AS $item_obj) {
                            $prod_obj = $this->getDao('Product')->get(["sku" => $item_obj->getMainProdSku()]);
                            $value = round($item_obj->getAmount() / $item_obj->getQty() * $so_obj->getRate(), 2);
                            $sum += $value * $item_obj->getQty();
                        }
                        if ($obj = $this->subjectDomainService->getDao('SubjectDomain')->get(["subject" => "SELLING_PRICE_CEILING.{$so_obj->getDeliveryCountryId()}"])) {
                            $declared_ratio = 1;
                            if ($obj->getValue() <= $sum) {
                                $declared_ratio = 0.5;
                            }
                        }
                    }
                    $bvat = 0;
                    $vat = 0;
                    $sum = 0;
                    $total_piece = 0;

                    //#2182 add the processing fee
                    if ($extobj = $this->getDao('SoExtend')->get(["so_no" => $so_obj->getSoNo()])) {
                        $processing_fee = $extobj->getOfflineFee();
                    }
                    if (is_null($processing_fee)) {
                        $processing_fee = 0;
                    }

                    foreach ($itemlist as $item_obj) {
                        $hs_desc = $code = null;
                        $prod_obj = $this->getDao('Product')->get(["sku" => $item_obj->getMainProdSku()]);
                        $qty = $item_obj->getQty();
                        $amount_total = $item_obj->getAmount();
                        if ($pcc_obj = $this->getDao('ProductCustomClassification')->get(['sku' => $prod_obj->getSku(), 'country_id' => $so_obj->getDeliveryCountryId()])) {
                            $hs_desc = $pcc_obj->getDescription();
                            $code = $pcc_obj->getCode();
                        }

                        //SBF #4403 - If hs desc and code not found, get the hs desc and code from sub_cat_id of the product
                        if (!isset($hs_desc) || $hs_desc == '') {
                            $where = ['ccm.sub_cat_id' => $prod_obj->getSubCatId(), 'ccm.country_id' => $so_obj->getDeliveryCountryId()];
                            $hsDetails = $this->getDao('CustomClassificationMapping')->getHsBySubcatAndCountry($where, $option);
                            $hs_desc = $hsDetails[0]['description'];
                            $code = $hsDetails[0]['code'];
                        }

                        if ($declared_ratio) {
                            $declared_value = round($amount_total / $item_obj->getQty() * $so_obj->getRate() * $declared_ratio, 2);
                        } else {
                            $declared_value = round($this->getDeclaredValue($prod_obj, $so_obj->getDeliveryCountryId(), $amount_total / $item_obj->getQty()), 2);
                        }

                        # sbf 4145 - custom invoice in HKD
                        $declared_value_converted = $declared_value * $so_obj->getRate();
                        $delivery_charge_converted = $so_obj->getDeliveryCharge() * $so_obj->getRate();
                        $processing_fee_converted = $processing_fee * $so_obj->getRate();
                        if ($currency) {
                            $original_currency = "USD"; // $so_obj->getCurrencyId();
                            $declared_value_converted = $this->convertCurrency($original_currency, $currency, $declared_value_converted);
                            $delivery_charge_converted = $this->convertCurrency($original_currency, $currency, $delivery_charge_converted);
                            $processing_fee_converted = $this->convertCurrency($original_currency, $currency, $processing_fee_converted);
                        } else {
                            $currency = "USD";
                        }
                        $data["currency"] = strtoupper($currency);

                        $item_information .= "
                             <tr>
                                <td  align='left'>".$hs_desc."</td>
                                <td align='right'>".$item_obj->getQty()."</td>
                                <td align='right'>".$code."</td>
                                <td align='right'>".number_format($declared_value_converted, 2)."</td>
                                <td align='right'><b>".number_format($declared_value_converted * $qty, 2)."</b></td>
                            </tr>";
                        $sum += $declared_value_converted * $qty;
                    }
                    $data["item_info"] = $item_information;
                    $data["total_cost"] = number_format($sum, 2);
                    $data["delivery"] = number_format($delivery_charge_converted, 2);
                    $data['processing_fee'] = number_format($processing_fee_converted, 2);
                    $data["total_value"] = number_format($sum + $data["delivery"] + $data['processing_fee'], 2);
                    $content .= $this->getCustomInvBody($data, $lang, $new_shipper_name);
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

    public function getDeclaredValue($prod_obj = "", $country_id = "", $price = "")
    {
        $max_declared_value = -1;
        $declared_pcent = 100;
        $declared = -1;

        switch ($country_id) {
            case "AU":
                if ($price < 950) {
                    $declared_pcent = 100;
                } else {
                    $max_declared_value = 950;
                }
                break;

            case "NZ":
                if ($price < 350) {
                    $declared_pcent = 100;
                } else {
                    $declared_pcent = 80;
                }
                break;

            default:
                $declared_pcent = 10;
                break;
        }

        if ($max_declared_value != -1) {
            if ($price > $max_declared_value) {
                $declared = $max_declared_value;
            } else {
                $declared = $price;
            }
        } else {
            $declared = $price * $declared_pcent / 100;
        }

        return $declared;
    }

    public function convertCurrency($original_currency, $new_currency, $original_value)
    {
        $rate = $this->exchangeRateService->getExchangeRate($original_currency, $new_currency)->getRate();
        return $rate * $original_value;
    }

    private function getCustomInvBody($data = [], $lang = [], $new_shipper_name = "")
    {
        foreach ($data as $key => $value) {
            ${$key} = $value;
        }
        include APPPATH . "data/cinvBody.php";

        return $body;
    }

    public function set_pi($so_no)
    {
        return $this->setProfitInfo($this->getDao('So')->get(array("so_no" => $so_no)));
    }

    public function fireLogEmailEvent($so_no = "", $template = "", $option = "")
    {
        if ($so_no == "" || $template == "" || $option == "") {
            return FALSE;
        } else {
            $so_obj = $this->getDao('So')->get(array("so_no" => $so_no));
            if ($so_obj === FALSE || !$so_obj) {
                return FALSE;
            } else {
                $shr_Id = $so_obj->getHoldReason();
                $sohr_obj = $this->getDao('SoHoldReason')->get(array("id" => $shr_Id));
                if (!$sohr_obj) {
                    return FALSE;
                }

                $user_obj = $this->getDao('User')->get(array("id" => $sohr_obj->getCreateBy()));

                $dto = new EventEmailDto;

                $replace["so_no"] = $so_no;
                $replace["client_id"] = $so_obj->getClientId();
                $replace["name"] = $user_obj->getUsername();
                $replace["create_date"] = $sohr_obj->getCreateOn();
                $replace["reason"] = ereg_replace('_log_app$', '', $sohr_obj->getReason());

                $dto->setEventId("notification");
                $dto->setMailFrom('logistics@valuebasket.com');
                $dto->setMailTo($user_obj->getEmail());
                $dto->setTplId("log_reply_" . $option);
                $dto->setReplace($replace);
                $this->eventService->fireEvent($dto);

                return TRUE;
            }
        }
    }

    public function fireCs2logEmail($so_no = "", $reason = "", $user_info = [])
    {
        if ($so_no == "" || $reason == "" || empty($user_info)) {
            return;
        } else {
            $dto = new EventEmailDto;

            $so_obj = $this->getDao('So')->get(["so_no" => $so_no]);
            $replace["so_no"] = $so_no;
            $replace["client_id"] = $so_obj->getClientId();
            $replace["name"] = $user_info["username"];
            $replace["create_date"] = date("Y-m-d H:i:s");
            $replace["reason"] = $reason;

            $dto->setEventId("notification");
            $dto->setMailTo('logistics@valuebasket.com');
            $dto->setMailFrom($user_info["email"]);
            $dto->setTplId("log_notice");
            $dto->setReplace($replace);
            $this->eventService->fireEvent($dto);

            return TRUE;
        }
    }

    public function fireCsRequest($so_no = "", $reason = "", $lang = "en")
    {

        if ($so_no == "" || $reason == "") {
            return;
        } else {
            # sbf #3746 don't include complementary accessory on front end
            $ca_catid_arr = implode(',', $this->getDao('ProductComplementaryAcc')->getAccessoryCatidArr());

            $so_obj = $this->getDao('So')->get(array("so_no" => $so_no));
            if ($so_obj) {
                $client_obj = $this->getDao('Client')->get(array("id" => $so_obj->getClientId()));
                $pbv_obj = $this->getDao('PlatformBizVar')->get(array("selling_platform_id" => $so_obj->getPlatformId()));
                if ($client_obj) {
                    $list = $this->getDao('SoItemDetail')->getItemsWithName(array("so_no" => $so_no, "p.cat_id NOT IN ($ca_catid_arr)" => NULL));

                    $item_list = [];
                    foreach ($list as $obj) {
                        $item_list[] = "- " . $obj->getName();
                    }

                    $dto = new EventEmailDto;

                    $replace["order_number"] = $so_no;
                    $replace["client_id"] = $so_obj->getClientId();
                    $replace["forename"] = $client_obj->getForename();
                    $replace["order_create_date"] = date("Y-m-d", strtotime($so_obj->getOrderCreateDate()));
                    $replace["item_list"] = implode("\n", $item_list);

                    $email_sender = "Agatha@digitaldiscount.com";

                    $dto->setEventId("notification");
                    $dto->setMailTo($client_obj->getEmail());
                    $support_email = $this->getCsSupportEmail($lang);

                    $dto->setMailFrom($email_sender);
                    $dto->setTplId($reason . "_request");
                    $dto->setLangId($pbv_obj ? $pbv_obj->getLanguageId() : "");
                    $dto->setReplace($replace);
                    $this->eventService->fireEvent($dto);
                }
            }
        }
    }

    public function getCreditCheckList($where = [], $option = [], $type = "")
    {
        $ret = [];

        if ($obj_list = $this->getDao('So')->getCreditCheckList($where, $option, $type)) {
            foreach ($obj_list as $obj) {
                $cnt = $this->getDao('So')->getPwdCnt($obj->getSoNo(), $obj->getClientId());
                $obj->setPwCount($cnt);
                $item = $this->getDao('So')->getItemDetailStr($obj->getSoNo());
                $obj->setItems($item);
                $sor_obj = $this->getDao('SoRisk')->get(["so_no" => $obj->getSoNo()]);
                $obj->setSorObj($sor_obj);
                $ret[] = $obj;
            }
        }

        return $ret;
    }

    public function get_track_order($so_no)
    {
        include_once(APPPATH . "helpers/object_helper.php");
        $order["shipped"] = $order["processing"] = [];
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

    public function errorInAllocateFile()
    {
        $arr = $this->getAwaitingShipmentInfo();

        echo "The following SKU does not have master SKU<br>";
        if ($arr) {
            foreach ($arr as $row) {
                if ($row) {
                    if (!$row->getSku()) {
                        echo $row->getExtRefSku() . "<br>";
                    }
                }
            }
        }
    }

    public function getAwaitingShipmentInfo()
    {
        return $this->getDao('SoAllocate')->getAwaitingShipmentInfo();
    }

    public function generateAllocateFile()
    {
        $file_content = "";
        $output_path = $this->getDao('Config')->valueOf('courier_path');

        $arr = $this->getAwaitingShipmentInfo();
        if ($arr) {
            foreach ($arr as $row) {
                $row->setWarehouse("HK");
                $row->setExtSys("CV");
            }
        }

        $this->voToXml->VoToXml($arr, '');
        $this->xmlToCsv->XmlToCsv('', APPPATH . 'data/awaiting_shipment_to_wms.txt', TRUE, ',');

        $file_content = $this->dataExchangeService->convert($this->voToXml, $this->xmlToCsv);

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

    public function strpos_array($haystack, $needles) {
        if ( is_array($needles) ) {
            foreach ($needles as $str) {
                if ( is_array($str) ) {
                    $pos = $this->strpos_array($haystack, $str);
                } else {
                    $pos = strpos($haystack, $str);
                }
                if ($pos !== FALSE) {
                    return $pos;
                }
            }
        } else {
            return strpos($haystack, $needles);
        }
    }


    public function getGenerateCourierFile($batch_id)
    {
        if ($obj = $this->getDao('CourierFeed')->get(["batch_id" => $batch_id])) {
            $so_no_list = json_decode($obj->getSoNoStr());
            $mawb = $obj->getMawb();
            $courier = $obj->getCourierId();
            $ret = $this->generateCourierFile($so_no_list, $courier, $mawb);
            $obj->setExec(1);
            $name = $obj->getCreateBy();
            $this->getDao('CourierFeed')->update($obj);

            $file_path = $this->getDao('Config')->valueOf('courier_path') . $ret;

            $bodytext = "";
            if ($user_obj = $this->getDao('User')->get(["id" => $name])) {
                $email_addr = $user_obj->getEmail();
            } else {
                $email_addr = "nero@eservicesgroup.com";
                $bodytext .= "user email not found <br>";
            }

            foreach ($so_no_list as $o) {
                $bodytext .= $o . "<br/>";
            }

            $phpmail = new PHPMailer;
            $phpmail->IsSMTP();
            $phpmail->From = "courier_feed@eservicesgroup.com";
            $phpmail->Subject = "courier feed: $ret";
            $phpmail->AddAddress($email_addr);
            $phpmail->IsHTML(true);

            if (file_exists($file_path)) {
                $phpmail->AddAttachment($file_path);
            } else {
                $bodytext = "courier file can not be found<br />" . $bodytext;
            }

            $phpmail->Body = $bodytext;

            $phpmail->Send();
        }
    }

    public function generateCourierFile($checked = [], $courier = "", $mawb = "", $debug_explain = false)
    {
        $file_content = "";
        $output_path = $this->getDao('Config')->valueOf('courier_path');
        $data_out = [];
        foreach ($checked as $key => $value) {
            switch ($courier) {
                case "DHLHKD":
                case "DHL":
                    if ($arr = $this->getShipmentDeliveryInfoDhl($value))
                    {
                        foreach ($arr as $row) {
                            $ar_address = @explode("|", $row->getDeliveryAddress());
                            $row->setDeliveryAddress1($ar_address[0]);
                            if (empty($ar_address[1]) && empty($ar_address[2])) {
                                $row->setDeliveryAddress2('NA');
                            } else {
                                $row->setDeliveryAddress2(implode(" ", array($ar_address[1], $ar_address[2])));
                            }
                            if (!$row->getDeliveryCity()) {
                                $row->setDeliveryAddress3('NA');
                            } else {
                                $row->setDeliveryAddress3($row->getDeliveryCity());
                            }
                            $row->setQty(1);
                            $row->setProdWeight($row->getProdWeight() * $row->getQty());
                            $row->setPrice($row->getAmount());
                            if ($row->getTel() == "") {
                                $row->setTel("0");
                            }
                            if ($row->getDeliveryCompany() == "") {
                                $row->setDeliveryCompany($row->getDeliveryName());
                            }
                            $prod_obj = $this->getDao('Product')->get(["sku" => $row->getProdSku()]);
                            $declared_value = $this->getDeclaredValue($prod_obj, $row->getDeliveryCountryId(), $row->getPrice());

                            // #sbf4096 - Add DHLHKD
                            if ($courier === 'DHLHKD') {
                                // If original (DHL)) is always in USD, then convert it from here
                                $declared_value = $declared_value * $row->getRate();
                                $declared_value = round($this->convertCurrency("USD", 'HKD', $declared_value), 2);
                            } else {
                                $declared_value = round($declared_value * $row->getRate(), 2);
                            }

                            $row->setDeclaredValue($declared_value);

                            $data_out[] = $row;
                            $counter++;
                        }
                    }
                    $this->voToXml->VoToXml($data_out, '');

                    // #sbf4096 - Add DHLHKD
                    if ($courier === 'DHLHKD') {
                        $this->xmlToCsv->XmlToCsv('', APPPATH . 'data/shipment_info_to_courier_dhlhkd_xml2csv.txt', FALSE, '|');
                    } else {
                        $this->xmlToCsv->XmlToCsv('', APPPATH . 'data/shipment_info_to_courier_dhl_xml2csv.txt', FALSE, '|');
                    }

                    $file_content = $this->dataExchangeService->convert($this->voToXml, $this->xmlToCsv);
                    break;
                case "DHLBBX":
                    if ($arr = $this->getShipmentDeliveryInfoDhl($value)) {
                        foreach ($arr as $row) {
                            $ar_address = @explode("|", $row->getDeliveryAddress());
                            $row->setDeliveryAddress1($ar_address[0]);
                            if (empty($ar_address[1])) {
                                $row->setDeliveryAddress2('NA');
                            } else {
                                $row->setDeliveryAddress2($ar_address[1]);
                            }
                            if (empty($ar_address[2])) {
                                $row->setDeliveryAddress3('NA');
                            } else {
                                $row->setDeliveryAddress3($ar_address[2]);
                            }
                            $row->setQty(1);
                            $row->setProdWeight($row->getProdWeight() * $row->getQty());
                            $row->setPrice($row->getAmount());
                            if ($row->getTel() == "") {
                                $row->setTel("0");
                            }
                            if ($row->getDeliveryCompany() == "") {
                                $row->setDeliveryCompany($row->getDeliveryName());
                            }
                            $prod_obj = $this->getDao('Product')->get(["sku" => $row->getProdSku()]);
                            $declared_value = $this->getDeclaredValue($prod_obj, $row->getDeliveryCountryId(), $row->getPrice());
                            $row->setDeclaredValue(round($declared_value * $row->getRate(), 2));
                            if (trim($mawb) != "") {
                                $row->setMawb("MAWB#: " . $mawb);
                            }
                            $data_out[] = $row;
                            $counter++;
                        }
                    }
                    $this->voToXml->VoToXml($data_out, '');
                    $this->xmlToCsv->XmlToCsv('', APPPATH . 'data/shipment_info_to_courier_dhlbbx_xml2csv.txt', FALSE, '|');
                    $file_content = $this->dataExchangeService->convert($this->voToXml, $this->xmlToCsv);
                    break;
                case "FEDEX":
                    if ($arr = $this->getShipmentDeliveryInfoCourier($value)) {
                        $counter = 0;
                        foreach ($arr as $row) {
                            $ar_address = @explode("|", $row->getDeliveryAddress());
                            $row->setDeliveryAddress1($ar_address[0]);
                            array_shift($ar_address);
                            $row->setDeliveryAddress2(trim(@implode("|", $ar_address), "|"));

                            $row->setProdWeight($row->getProdWeight() * $row->getQty() * 10);
                            $row->setPrice($row->getAmount());

                            if ($row->getDeliveryCompany() == "") {
                                $row->setDeliveryCompany($row->getDeliveryName());
                            }

                            $prod_obj = $this->getDao('Product')->get(["sku" => $row->getProdSku()]);
                            $declared_value = $this->getDeclaredValue($prod_obj, $row->getDeliveryCountryId(), $row->getPrice());
                            $row->setDeclaredValue(round($declared_value * $row->getRate(), 2) * 100);

                            $file_content .= "0,\"20\"\r\n" .
                                "1,\"{$counter}\"\r\n" .
                                "1274,\"3\"\r\n" .
                                "31,\"VB\"\r\n" .
                                "11,\"{$row->getDeliveryCompany()}\"\r\n" .
                                "12,\"{$row->getDeliveryName()}\"\r\n" .
                                "13,\"{$row->getDeliveryAddress1()}\"\r\n" .
                                "14,\"{$row->getDeliveryAddress2()}\"\r\n" .
                                "16,\"{$row->getDeliveryState()}\"\r\n" .
                                "15,\"{$row->getDeliveryCity()}\"\r\n" .
                                "17,\"{$row->getDeliveryPostcode()}\"\r\n" .
                                "50,\"{$row->getDeliveryCountryId()}\"\r\n" .
                                "18,\"{$row->getTel()}\"\r\n" .
                                "116,\"1\"\r\n" .
                                "21,\"5\"\r\n" .
                                "119,\"{$row->getDeclaredValue()}\"\r\n" .
                                "79-1,\"{$row->getCcDesc()}\"\r\n" .
                                "79-2,\"hscode {$row->getCcCode()}\"\r\n" .
                                "81,\"{$row->getCcCode()}\"\r\n" .
                                "80-1,\"JP\"\r\n" .
                                "80-2,\"JP\"\r\n" .
                                "25,\"{$row->getSoNo()}\"\r\n" .
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
                            $ar_address = @explode("|", $row->getDeliveryAddress());
                            $row->setDeliveryAddress1($ar_address[0]);
                            array_shift($ar_address);
                            $row->setDeliveryAddress2(trim(@implode("|", $ar_address), "|"));

                            $row->setProdWeight($row->getProdWeight() * $row->getQty() * 10);
                            $row->setPrice($row->getAmount());

                            if ($row->getDeliveryCompany() == "") {
                                $row->setDeliveryCompany($row->getDeliveryName());
                            }

                            $cc = $row->getCurrencyId();

                            switch ($cc) {
                                case "GBP":
                                    $cc = "UKL";
                                    break;
                                case "SGD":
                                    $cc = "SID";
                                    break;
                            }

                            if ($counter == 0) $total_declared_value = $row->getPrice(); else $total_declared_value += $row->getPrice();

                            $prod_obj = $this->getDao('Product')->get(["sku" => $row->getProdSku()]);

                            // we pass total_declared_value_to_6decimals in so that we will eventually calculate a declared value
                            // of all the items in the order, e.g. SKU-A: 649, SKU-B: 399, we will calculate declared value based on 649+399
                            $declared_value = $this->getDeclaredValue($prod_obj, $row->getDeliveryCountryId(), $total_declared_value);
                            $this->declared_value_debug .= "declared value: $declared_value\r\n";
                            $this->declared_value_debug .= "1total_declared_value_to_6decimals value: $total_declared_value_to_6decimals\r\n";

                            # convert to USD
                            $convert_to_usd = false;
                            if ($convert_to_usd) {
                                $declared_value = round($declared_value * $row->getRate(), 2);
                                $row->setDeclaredValue($declared_value);
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
                                    "11,\"{$row->getDeliveryCompany()}\"\r\n" .
                                    "12,\"{$row->getDeliveryName()}\"\r\n" .
                                    "13,\"{$row->getDeliveryAddress1()}\"\r\n" .
                                    "14,\"{$row->getDeliveryAddress2()}\"\r\n" .
                                    "16,\"{$row->getDeliveryState()}\"\r\n" .
                                    "15,\"{$row->getDeliveryCity()}\"\r\n" .
                                    "17,\"{$row->getDeliveryPostcode()}\"\r\n" .
                                    "50,\"{$row->getDeliveryCountryId()}\"\r\n" .
                                    "18,\"{$row->getTel()}\"\r\n" .
                                    "116,\"1\"\r\n" .
                                    "21,\"5\"\r\n" .
                                    // "119,\"{$row->getDeclaredValue()}\"\r\n".
                                    "79-1,\"{$row->getCcDesc()} hscode {$row->getCcCode()}\"\r\n" .
                                    // "79-2,\"hscode {$row->getCcCode()}\"\r\n".
                                    "81-1,\"{$row->getCcCode()}\"\r\n" .
                                    "80-1,\"JP\"\r\n" .
                                    // "80-2,\"JP\"\r\n".
                                    "25,\"{$row->getSoNo()}\"\r\n" .
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
                                if ($row->getSoNo() != $prev_so_no) {
                                    $ar_address = @explode("|", $row->getDeliveryAddress());
                                    $row->setDeliveryAddress1($ar_address[0]);
                                    array_shift($ar_address);
                                    $row->setDeliveryAddress2(trim(@implode("|", $ar_address), "|"));
                                    if ($row->getDeliveryAddress2() == "") {
                                        $row->setDeliveryAddress2(".");
                                    }

                                    $row->setProdWeight(min(2, $row->getProdWeight()));
                                    $row->setPrice($row->getAmount());

                                    $countryObj = $this->getDao('Country')->get(["country_id" => $row->getDeliveryCountryId()]);
                                    $row->setCountryName($countryObj->getName());

                                    $row->setItemNo($counter);

                                    if ($row->getDeliveryCity() == "") {
                                        $row->setDeliveryCity('.');
                                    }

                                    $prev_so_no = $row->getSoNo();

                                    $data_out[] = $row;
                                    $counter++;
                                }
                            }
                        }
                    }

                    $this->voToXml->VoToXml($data_out, '');
                    $this->xmlToCsv->XmlToCsv('', APPPATH . 'data/shipment_info_to_courier_tnt_xml2csv.txt', TRUE, '|');
                    $file_content = $this->dataExchangeService->convert($this->voToXml, $this->xmlToCsv);
                    break;

                case "NEW_QUANTIUM":
                    $counter = 0;
                    if ($arr = $this->getShipmentDeliveryInfoCourier($value)) {
                        $prev_so_no = "";
                        foreach ($arr as $row) {
                            if ($row->getSoNo() != $prev_so_no) {
                                $ar_address = @explode("|", $row->getDeliveryAddress());
                                $row->setDeliveryAddress1($ar_address[0]);
                                array_shift($ar_address);
                                $row->setDeliveryAddress2(trim(@implode("|", $ar_address), "|"));
                                if ($row->getDeliveryAddress2() == "") {
                                    $row->setDeliveryAddress2(".");
                                }

                                $row->setProdWeight(min(2, $row->getProdWeight()));
                                $row->setPrice($row->getAmount());

                                $countryObj = $this->getDao('Country')->get(["country_id" => $row->getDeliveryCountryId()]);
                                $row->setCountryName($countryObj->getName());

                                $row->setItemNo($counter);

                                if ($row->getDeliveryCompany() == "") {
                                    $row->setDeliveryCompany($row->getDeliveryName());
                                }

                                if ($row->getDeliveryCity() == "") {
                                    $row->setDeliveryCity('.');
                                }

                                $declared_value = $this->getDeclaredValue($row, $row->getDeliveryCountryId(), $row->getPrice());
                                $row->setDeclaredValue(round($declared_value * $row->getRate(), 2));

                                $prev_so_no = $row->getSoNo();

                                $data_out[] = $row;
                                $counter++;
                            }
                        }
                    }
                    $this->voToXml->VoToXml($data_out, '');
                    $this->xmlToCsv->XmlToCsv('', APPPATH . 'data/shipment_info_to_courier_new_quantium_xml2csv.txt', FALSE, '|');
                    $file_content = $this->dataExchangeService->convert($this->voToXml, $this->xmlToCsv);
                    break;

                case "QUANTIUM":
                    $counter = 0;
                    if ($arr = $this->getShipmentDeliveryInfoCourier($value)) {
                        $prev_so_no = "";
                        foreach ($arr as $row) {
                            if ($row->getSoNo() != $prev_so_no) {
                                $ar_address = @explode("|", $row->getDeliveryAddress());
                                $row->setDeliveryAddress1($ar_address[0]);
                                array_shift($ar_address);
                                $row->setDeliveryAddress2(trim(@implode("|", $ar_address), "|"));

                                $row->setProdWeight(min(2000, $row->getProdWeight() * 1000));
                                $row->setPrice($row->getAmount());

                                $countryObj = $this->getDao('Country')->get(["country_id" => $row->getDeliveryCountryId()]);
                                $row->setCountryName($countryObj->getName());

                                $row->setItemNo($counter);

                                if ($row->getDeliveryCompany() == "") {
                                    $row->setDeliveryCompany($row->getDeliveryName());
                                }

                                $declared_value = $this->getDeclaredValue($row, $row->getDeliveryCountryId(), $row->getPrice());
                                $row->setDeclaredValue(round($declared_value * $row->getRate(), 2));

                                $prev_so_no = $row->getSoNo();

                                $data_out[] = $row;
                                $counter++;
                            }
                        }
                    }
                    $this->voToXml->VoToXml($data_out, '');
                    $this->xmlToCsv->XmlToCsv('', APPPATH . 'data/shipment_info_to_courier_quantium_xml2csv.txt', TRUE, ',');
                    $file_content = $this->dataExchangeService->convert($this->voToXml, $this->xmlToCsv);
                    break;

                case "TOLL":
                    if ($arr = $this->getShipmentDeliveryInfoDhl($value)) {
                        foreach ($arr as $row) {
                            $ar_address = @explode("|", $row->getDeliveryAddress());
                            $row->setDeliveryAddress1($ar_address[0]);
                            if (empty($ar_address[1]) && empty($ar_address[2])) {
                                $row->setDeliveryAddress2('NA');
                            } else {
                                $row->setDeliveryAddress2(implode(" ", array($ar_address[1], $ar_address[2])));
                            }
                            if (!$row->getDeliveryCity()) {
                                $row->setDeliveryAddress3('NA');
                            } else {
                                $row->setDeliveryAddress3($row->getDeliveryCity());
                            }
                            $row->setQty($row->getQty());
                            $row->setProdWeight($row->getProdWeight() * $row->getQty());
                            $row->setPrice($row->getAmount());
                            if ($row->getTel() == "") {
                                $row->setTel("0");
                            }
                            if ($row->getDeliveryCompany() == "") {
                                $row->setDeliveryCompany($row->getDeliveryName());
                            }
                            if ($row->getDeliveryCountryId() == 'AU') {
                                $valid_city_arr = array("brisbane", "melbourne", "perth", "sydney");
                                if (trim($row->getDeliveryCity()) == "" || !in_array(trim(strtolower($row->getDeliveryCity())), $valid_city_arr)) {
                                    $row->setDeliveryCity('Australia Other');
                                }
                            }

                            $prod_obj = $this->getDao('Product')->get(["sku" => $row->getProdSku()]);
                            $declared_value = $this->getDeclaredValue($prod_obj, $row->getDeliveryCountryId(), $row->getPrice());
                            $row->setDeclaredValue(round($declared_value * $row->getRate(), 2));

                            if ($country_obj = $this->getDao('Country')->get(["country_id" => $row->getDeliveryCountryId()])) {
                                $country_name = $country_obj->getName();
                                $row->setDeliveryCountryId($country_name);
                            }
                            $data_out[] = $row;
                            $counter++;
                        }
                    }
                    $this->voToXml->VoToXml($data_out, '');
                    $this->xmlToCsv->XmlToCsv('', APPPATH . 'data/shipment_info_to_courier_toll_xml2csv.txt', TRUE, ',');
                    $file_content = $this->dataExchangeService->convert($this->voToXml, $this->xmlToCsv);
                    break;

                case "TOLL2":   // SBF#1965
                case "DPD":     // TOLL2 changed to DPD
                    if ($arr = $this->getShipmentDeliveryInfoDhl($value)) {
                        foreach ($arr as $row) {
                            $ar_address = @explode("|", $row->getDeliveryAddress());
                            $row->setDeliveryAddress1($ar_address[0]);
                            if (empty($ar_address[1]) && empty($ar_address[2])) {
                                $row->setDeliveryAddress2('NA');
                            } else {
                                $row->setDeliveryAddress2(implode(" ", array($ar_address[1], $ar_address[2])));
                            }
                            if (!$row->getDeliveryCity()) {
                                $row->setDeliveryAddress3('NA');
                            } else {
                                $row->setDeliveryAddress3($row->getDeliveryCity());
                            }
                            $row->setQty($row->getQty());
                            $row->setProdWeight($row->getProdWeight() * $row->getQty());
                            $row->setPrice($row->getAmount());
                            if ($row->getTel() == "") {
                                $row->setTel("0");
                            }
                            if ($row->getDeliveryCompany() == "") {
                                $row->setDeliveryCompany($row->getDeliveryName());
                            }
                            if ($row->getDeliveryCountryId() == 'AU') {
                                $valid_city_arr = array("brisbane", "melbourne", "perth", "sydney");
                                if (trim($row->getDeliveryCity()) == "" || !in_array(trim(strtolower($row->getDeliveryCity())), $valid_city_arr)) {
                                    $row->setDeliveryCity('Australia Other');
                                }
                            }

                            $prod_obj = $this->getDao('Product')->get(["sku" => $row->getProdSku()]);
                            $declared_value = $this->getDeclaredValue($prod_obj, $row->getDeliveryCountryId(), $row->getPrice());
                            $row->setDeclaredValue(round($declared_value * $row->getRate(), 2));

                            if ($country_obj = $this->getDao('Country')->get(["country_id" => $row->getDeliveryCountryId()])) {
                                $country_name = $country_obj->getName();
                                $row->setDeliveryCountryId($country_name);
                            }
                            $data_out[] = $row;
                            $counter++;
                        }
                    }
                    $this->voToXml->VoToXml($data_out, '');
                    $this->xmlToCsv->XmlToCsv('', APPPATH . 'data/shipment_info_to_courier_dpd_xml2csv.txt', TRUE, ',');
                    $file_content = $this->dataExchangeService->convert($this->voToXml, $this->xmlToCsv);
                    break;
                case "IM":
                case "RMR":
                    $arr = $this->getShipmentDeliveryInfo($value, 'DispatchListDto'); // Pass the SO #

                    if (!$arr || ($no_of_line = count($arr)) == 0) {
                        continue;  // No data is found.  It shouldn't happen.
                    }

                    $counter = 1;

                    foreach ($arr as $row) {
                        $row->setTotalItemCount($no_of_line);
                        $row->setItemNo($counter);
                        if (($courier == "RMR") && ($row->getDeliveryCountryId() != "US")) {
                            $row->setUnitPrice(number_format($row->getUnitPrice() * 0.1, 2, '.', ''));
                            $row->setDeliveryCharge(number_format($row->getDeliveryCharge() * 0.1, 2, '.', ''));
                            $row->setAmount(number_format($row->getAmount() * 0.1, 2, '.', ''));
                        }
                        $row->setSubtotal(number_format(
                            $row->getUnitPrice() * $row->getQty()
                            , 2, '.', ''));
                        $row->setActualCost(number_format(
                            $row->getAmount() - $row->getOfflineFee()
                            , 2, '.', ''));
                        $row->setBillDetail('N'); // Always 'N' at the beginning.
                        list($del_address_1, $del_address_2, $del_address_3) = explode("|", $row->getDeliveryAddress());
                        $row->setDeliveryAddress1($del_address_1);
                        $row->setDeliveryAddress2($del_address_2);
                        $row->setDeliveryAddress3($del_address_3);
                        if ($counter > 1) {
                            $row->setShipOption('');
                            $row->setDeliveryCharge(0.00);
                            $row->setPromotionCode('');
                            $row->setAmount(0.00);
                            $row->setDeliveryTypeId('');
                            $row->setActualCost(0.00);
                        }
                        $data_out[] = $row;
                        $counter++;
                    }
                    $this->voToXml->VoToXml($data_out, '');
                    $this->xmlToCsv->XmlToCsv('', APPPATH . 'data/shipment_info_to_courier_' . strtolower($courier) . '_xml2csv.txt', TRUE, chr(9));
                    $file_content = $this->dataExchangeService->convert($this->voToXml, $this->xmlToCsv);

                    // Prepare dispatch list data
                    $counter = 1;
                    foreach ($arr as $row) {
                        $row->setTotalItemCount($no_of_line);
                        $row->setItemNo($counter);
                        $row->setSubtotal(number_format($row->getUnitPrice() * $row->getQty(), 2, '.', ''));
                        $row->setActualCost(number_format($row->getAmount() - $row->getOfflineFee(), 2, '.', ''));

                        if ($counter > 1) {
                            # code
                        }
                        $row->setWarehouseId($courier);
                        $row->setBin("STAG");
                        $dispatch_data_out[] = $row;
                        $counter++;
                    }
                    $this->voToXml->VoToXml($dispatch_data_out, '');
                    if ($courier == "RMR")
                        $data_file = 'data/dispatch_list_rmr_xml2csv.txt';
                    else
                        $data_file = 'data/dispatch_list_xml2csv.txt';
                    $this->xmlToCsv->XmlToCsv('', APPPATH . $data_file, TRUE, ',');
                    $dispatch_content = $this->dataExchangeService->convert($this->voToXml, $this->xmlToCsv);
                    break;
                case "ARAMEX_COD":
                    if ($arr = $this->getShipmentDeliveryInfoDhl($value)) {
                        foreach ($arr as $row) {
                            $ar_address = @explode("|", $row->getDeliveryAddress());
                            $row->setDeliveryAddress1($ar_address[0]);
                            $row->setDeliveryAddress2($ar_address[1]);
                            $row->setDeliveryAddress3($ar_address[2]);
                            $row->setQty($row->getQty());
                            $row->setProdWeight($row->getProdWeight() * $row->getQty());
                            $row->setPrice($row->getAmount());
                            if ($row->getTel() == "") {
                                $row->setTel("0");
                            }
                            if ($row->getDeliveryCompany() == "") {
                                $row->setDeliveryCompany($row->getDeliveryName());
                            }

                            $prod_obj = $this->getDao('Product')->get(["sku" => $row->getProdSku()]);
                            $declared_value = $row->getPrice();

                            # convert to USD
                            $convert_to_usd = false;
                            if ($convert_to_usd) {
                                $declared_value = round($declared_value * $row->getRate(), 2);
                            }
                            $row->setDeclaredValue($declared_value);

                            if ($country_obj = $this->getDao('Country')->get(["country_id" => $row->getDeliveryCountryId()])) {
                                $country_name = $country_obj->getName();
                                $row->setDeliveryCountryId($country_name);
                            }
                            $data_out[] = $row;
                            $counter++;
                        }
                    }

                    $this->voToXml->VoToXml($data_out, '');
                    $this->xmlToCsv->XmlToCsv('', APPPATH . 'data/shipment_info_to_courier_aramex_cod_xml2csv.txt', TRUE, ',');
                    $file_content = $this->dataExchangeService->convert($this->voToXml, $this->xmlToCsv);

                    break;

                case "ARAMEX":
                    if ($arr = $this->getShipmentDeliveryInfoDhl($value)) {
                        foreach ($arr as $row) {
                            $ar_address = @explode("|", $row->getDeliveryAddress());
                            $row->setDeliveryAddress1($ar_address[0]);
                            $row->setDeliveryAddress2($ar_address[1]);
                            $row->setDeliveryAddress3($ar_address[2]);
                            $row->setQty($row->getQty());
                            $row->setProdWeight($row->getProdWeight() * $row->getQty());
                            $row->setPrice($row->getAmount());
                            if ($row->getTel() == "") {
                                $row->setTel("0");
                            }
                            if ($row->getDeliveryCompany() == "") {
                                $row->setDeliveryCompany($row->getDeliveryName());
                            }

                            $prod_obj = $this->getDao('Product')->get(["sku" => $row->getProdSku()]);
                            $declared_value = $this->getDeclaredValue($prod_obj, $row->getDeliveryCountryId(), $row->getPrice());
                            $row->setDeclaredValue(round($declared_value * $row->getRate(), 2));

                            if ($country_obj = $this->getDao('Country')->get(["country_id" => $row->getDeliveryCountryId()])) {
                                $country_name = $country_obj->getName();
                                $row->setDeliveryCountryId($country_name);
                            }
                            $data_out[] = $row;
                            $counter++;
                        }
                    }
                    $this->voToXml->VoToXml($data_out, '');
                    $this->xmlToCsv->XmlToCsv('', APPPATH . 'data/shipment_info_to_courier_aramex_xml2csv.txt', TRUE, ',');
                    $file_content = $this->dataExchangeService->convert($this->voToXml, $this->xmlToCsv);
                    break;

                //#2507 add DPD_NL courier feed
                case "DPD_NL":
                    if ($arr = $this->getShipmentDeliveryInfoDhl($value)) {
                        foreach ($arr as $row) {
                            $ar_address = @explode("|", $row->getDeliveryAddress());
                            if ($ar_address[0] == '') {
                                $ar_address[0] = '.';
                            }
                            if ($ar_address[1] == '') {
                                $ar_address[1] = '.';
                            }
                            $row->setDeliveryAddress2($ar_address[1]);
                            $row->setDeliveryAddress1($ar_address[0]);
                            $row->setShippingDate(date('d.m.Y'));

                            $row->setProdWeight($row->getProdWeight() * $row->getQty());

                            if ($row->getTel() == "") {
                                $row->setTel(".");
                            }

                            if ($row->getDeliveryPostcode() == "") {
                                $row->setDeliveryPostcode(".");
                            }

                            $delivery_country_id = $row->getDeliveryCountryId();
                            //If delivery country is France,  pls enter FR.
                            //If delivery country is Nederland,  pls enter NL. For other country, pls enter EN
                            if (!in_array($delivery_country_id, array('FR', 'NL'))) {
                                $row->setDeliveryCountryId2('EN');
                            } else {
                                $row->setDeliveryCountryId2($delivery_country_id);
                            }

                            $data_out[] = $row;
                            $counter++;
                        }
                    }
                    $this->voToXml->VoToXml($data_out, '');
                    $this->xmlToCsv->XmlToCsv('', APPPATH . 'data/shipment_info_to_courier_DPD_NL_xml2csv.txt', TRUE, ',');
                    $file_content = $this->dataExchangeService->convert($this->voToXml, $this->xmlToCsv);
                    break;
                // #2715 MRW's IT integration
                case "MRW":
                    if ($arr = $this->getShipmentDeliveryInfoCourier($value)) {

                        $seqdao = $this->sequence_dao;
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
                            $totalweight += $row->getProdWeight() * $row->getQty();
                            $totalprice += $row->getPrice() * $row->getQty();
                            $totalqty += $row->getQty();
                        }

                        foreach ($arr as $row) {
                            $ar_address = @explode("|", $row->getDeliveryAddress());
                            $ar_address = str_replace(";", " ", $ar_address);
                            $row->setDeliveryAddress(trim(@implode(" ", $ar_address)));
                            $row->setShippingDate(date('dmY'));
                            $tel = $row->getTel();
                            if (strlen($tel) > 9)
                                $tel = substr($tel, -9);

                            $file_content .= "\"H\";" .
                                "\"E\";" .
                                "\"0001{$row->getSoNo()}\";" .
                                "\"00826\";" .
                                "\"\";" .
                                "\"{$row->getShippingDate()}\";" .
                                "\"ALMACEN 1\";" .
                                "\"" . ((strlen($row->getDeliveryName()) > 30) ? substr($row->getDeliveryName(), 0, 30) : $row->getDeliveryName()) . "\";" .
                                "\"\";" .
                                "\"\";" .
                                "\"" . ((strlen($row->getDeliveryAddress()) > 80) ? substr($row->getDeliveryAddress(), 0, 80) : $row->getDeliveryAddress()) . "\";" .
                                "\"" . ((strlen($row->getDeliveryCity()) > 20) ? substr($row->getDeliveryCity(), 0, 20) : $row->getDeliveryCity()) . "\";" .
                                "\"{$row->getDeliveryPostcode()}\";" .
                                "\"{$tel}\";" .
                                "\"{$tel}\";" .
                                "\"" . ((strlen($row->getDeliveryCity()) > 20) ? substr($row->getDeliveryCity(), 0, 20) : $row->getDeliveryCity()) . "\";" .
                                "\"{$row->getDeliveryCountryId()}\";" .
                                "\"\";" .
                                "\"{$totalweight}\";" .
                                "\"\";" .
                                "\"1\";" .
                                "\"N\";" .
                                "\"\";" .
                                "\"" . ((strlen($row->getCcDesc()) > 24) ? substr($row->getCcDesc(), 0, 24) : $row->getCcDesc()) . "\";" .
                                "\"0{$tracking_id}\";" .
                                "\"D\";" .
                                "\"\";" .
                                "\"{$row->getClientEmail()}\";" .
                                "\"VALUEBASKET\"\r\n" .
                                "\"L\";" .
                                "\"0001{$row->getSoNo()}\";" .
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
                    $arr = $this->getShipmentDeliveryInfo($value, 'DispatchListDto'); // Pass the SO #

                    if (!$arr || ($no_of_line = count($arr)) == 0) {
                        continue;  // No data is found.  It shouldn't happen.
                    }

                    $counter = 1;

                    foreach ($arr as $row) {
                        $row->setTotalItemCount($no_of_line);
                        $row->setItemNo($counter);
                        $row->setSubtotal(number_format(
                            $row->getUnitPrice() * $row->getQty()
                            , 2, '.', ''));
                        $row->setActualCost(number_format(
                            $row->getAmount() - $row->getOfflineFee()
                            , 2, '.', ''));
                        $row->setBillDetail('N'); // Always 'N' at the beginning.

                        if ($counter > 1) {
                            $row->setShipOption('');
                            $row->setDeliveryCharge(0.00);
                            $row->setPromotionCode('');
                            $row->setAmount(0.00);
                            $row->setDeliveryTypeId('');
                            $row->setActualCost(0.00);
                        }
                        $data_out[] = $row;
                        $counter++;
                    }

                    $this->voToXml->VoToXml($data_out, '');
                    $this->xmlToCsv->XmlToCsv('', APPPATH . 'data/shipment_info_to_courier_xml2csv.txt', TRUE, ',');
                    $file_content = $this->dataExchangeService->convert($this->voToXml, $this->xmlToCsv);

                    $counter = 1;
                    if ($courier == "AMS" || $courier == "ILG") {
                        foreach ($arr as $row) {
                            $row->setTotalItemCount($no_of_line);
                            $row->setItemNo($counter);
                            $row->setSubtotal(number_format($row->getUnitPrice() * $row->getQty(), 2, '.', ''));
                            $row->setActualCost(number_format($row->getAmount() - $row->getOfflineFee(), 2, '.', ''));

                            if ($counter > 1) {
                                # code
                            }
                            $row->setWarehouseId($courier);
                            $row->setBin("STAG");
                            $dispatch_data_out[] = $row;
                            $counter++;
                        }
                        $this->voToXml->VoToXml($dispatch_data_out, '');
                        $this->xmlToCsv->XmlToCsv('', APPPATH . 'data/dispatch_list_xml2csv.txt', TRUE, ',');
                        $dispatch_content = $this->dataExchangeService->convert($this->voToXml, $this->xmlToCsv);
                    }
                    break;
            }
        }

        if ($file_content != "") {
            $filename = "so_delivery_" . date("YmdHis");
            $path = $output_path;

//create file for dispatch list import
            if ($courier == "AMS" || $courier == "ILG" || $courier == "IM" || $courier == "RMR") {
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

    public function getShipmentDeliveryInfo($so_no = 'SO000001', $classname = 'ShipmentInfoToCourierDto')
    {
        return $this->getDao('So')->getShipmentDeliveryInfo($so_no, $classname);
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

    public function generate_metapack_file($checked = [], $courier = "")
    {
        if ($courier == "" || count($checked) == 0) {
            return;
        }

        $file_content = "";
        $output_path = $this->getDao('Config')->valueOf('metapack_path');
        foreach ($checked as $key => $value) {
            $so_obj = $this->getDao('So')->get(array("so_no" => $value));
            $client_obj = $this->getDao('Client')->get(array("id" => $so_obj->getClientId()));
            $soa_obj = $this->getDao('SoAllocate')->get(array("so_no" => $value));
            if ($country_obj = $this->getDao('Country')->get(array("country_id" => $so_obj->getDeliveryCountryId()))) {
                $delcountry = $country_obj->getName();
            }
            $fullname = $so_obj->getDeliveryName();
            $ordernum = $soa_obj->get_sh_no();
            $delpostcode = $so_obj->getDeliveryPostcode();
            $delemail = $client_obj->getEmail();
            $mobiletel = $client_obj->get_tel_1() . " " . $client_obj->get_tel_2() . " " . $client_obj->get_tel_3();
            $delcity = $so_obj->getDeliveryCity();
            $delstate = $so_obj->getDeliveryState();
            $delcountry_id = $so_obj->getDeliveryCountryId();
            $weight = $so_obj->get_weight();

            $append = "";
            $z = explode("|", $so_obj->getDeliveryAddress());
            $cnt = count($z);
            for ($j = $cnt; $j < 3; $j++) {
                $append .= "~";
            }

            switch ($courier) {
                case 'DPD':
                    $deladdress = explode("|", $so_obj->getDeliveryAddress());
                    $deladdr1 = $deladdress[0];
                    $deladdr2 = $deladdress[1] . ", " . $deladdress[2];
                    $deladdr2 = ereg_replace("^, ", "", $deladdr2);
                    $deladdr2 = ereg_replace(", $", "", $deladdr2);
                    if (in_array($so_obj->getDeliveryCountryId(), array('IE', 'GB', 'UK'))) {
                        $dpdobject = "1";
                        $dpdservicecode = "32";
                        if ($so_obj->getDeliveryCountryId() == IE) {
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

                    $deladdress = str_replace("|", "~", $so_obj->getDeliveryAddress()) . $append;
                    $file_content .= "~2~~~" . $fullname . "~" . $fullname . "~" . $deladdress . "~~~~" . $delpostcode . "~~~~~456098002~~~STL01~~" . $ordernum . "~1~100~~~P~~~~\r\n";
                    break;

                case 'RM1stRec';
                    $deladdress = str_replace("|", "~", $so_obj->getDeliveryAddress()) . $append;
                    $file_content .= "~2~~~" . $fullname . "~" . $fullname . "~" . $deladdress . "~~~~" . $delpostcode . "~~~~~456098002~~~STL01~~" . $ordernum . "~1~100~11~~P~~~~\r\n";
                    break;

                case 'RMSD':
                    $deladdress = str_replace("|", "~", $so_obj->getDeliveryAddress()) . $append;
                    $file_content .= "~2~~~" . $fullname . "~" . $fullname . "~" . $deladdress . "~~~~" . $delpostcode . "~~~~~456098002~~~SD101~~" . $ordernum . "~1~1000~~~P~~~~\r\n";
                    break;

                case 'RMAir':
                    list($addr1, $addr2, $addr3) = explode("|", $so_obj->getDeliveryAddress());
                    $addr1 = str_replace("|", ",", $so_obj->getDeliveryAddress());
                    $addr2 = ($delcity ? $delcity : "-");
                    $addr3 = ($delstate ? $delstate : "-");
                    $deladdress = $addr1 . "~" . $addr2 . "~" . $addr3;
                    //$deladdress = str_replace("|","~",$so_obj->getDeliveryAddress());
                    $file_content .= "~2~~~" . $fullname . "~~" . $deladdress . "~~~~" . $delpostcode . "~" . $delcountry_id . "~~~~~~~~~" . $ordernum . "~~" . $weight . "~~\r\n";
                    break;

                case 'RMInt':
                    list($addr1, $addr2, $addr3) = explode("|", $so_obj->getDeliveryAddress());
                    $addr1 = str_replace("|", ",", $so_obj->getDeliveryAddress());
                    $addr2 = ($delcity ? $delcity : "-");
                    $addr3 = ($delstate ? $delstate : "-");
                    $deladdress = $addr1 . "~" . $addr2 . "~" . $addr3;
                    //$deladdress = str_replace("|","~",$so_obj->getDeliveryAddress());
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

    public function getHoldHistory($so_no = "")
    {
        if ($so_no == "") {
            return FALSE;
        }

        return $this->getDao('SoHoldReason')->getListWithUname(["so_no" => $so_no], ["orderby" => "create_on DESC"]);
    }

    public function fireDispatch($so_obj, $sh_no, $get_email_html = FALSE)
    {
        # sbf #3746 don't include complementary accessory on front end
        $ca_catid_arr = implode(',', $this->getDao('ProductComplementaryAcc')->getAccessoryCatidArr());

        $country = $this->getDao('Country')->get(["country_id" => $so_obj->getDeliveryCountryId()]);

        $so_items = $this->getDao('SoItemDetail')->getItemsWithName(array("so_no" => $so_obj->getSoNo(), "p.cat_id NOT IN ($ca_catid_arr)" => NULL));
        $client = $this->getDao('Client')->get(array("id" => $so_obj->getClientId()));
        $currency_obj = $this->getDao('Currency')->get(['currency_id' => $so_obj->getCurrencyId()]);
        $sh_obj = $this->getDao('SoShipment')->get(['sh_no' => $sh_no]);
        if ($sh_obj->getCourierId()) {
            if ($courier_obj = $this->getDao('Courier')->get(array("id" => $sh_obj->getCourierId()))) {
                $courier_id = $courier_obj->get_courier_name();
                if ($sh_obj->getCourierId() == 'DPD_NL' && $sh_obj->getTrackingNo() && $courier_obj->getTrackingLink()) {
                    $tracking_no = '<a href="' . $courier_obj->getTrackingLink() . $sh_obj->getTrackingNo() . '" target="_blank">' . $sh_obj->getTrackingNo() . '</a>';
                    $track_num = $sh_obj->getTrackingNo();
                } else {
                    $tracking_no = (empty($sh_obj) ? '' : $sh_obj->getTrackingNo());
                    $track_num = $sh_obj->getTrackingNo();
                }
            }
        }
        $platform_id = $so_obj->getPlatformId();
        $pbv_obj = $this->getDao('PlatformBizVar')->get(array('selling_platform_id' => $platform_id));
        $lang_id = $pbv_obj->getLanguageId();

        $replace["so_no"] = $so_obj->getSoNo();

        $split_so_group = $so_obj->getSplitSoGroup();
        if (isset($split_so_group) && $split_so_group != $so_obj->getSoNo()) {
            $replace["so_no"] = $split_so_group . '/' . $so_obj->getSoNo();
        }

        $replace["client_id"] = $so_obj->getClientId();
        $replace["forename"] = $client->getForename();
        $replace["email"] = $client->getEmail();
        $replace["bill_name"] = $so_obj->getBillName();
        $replace["purchase_date"] = $so_obj->getOrderCreateDate();
        $replace["promotion_code"] = $so_obj->getPromotionCode();
        $replace["delivery_days"] = "2-5";
        $replace["delivery_name"] = $so_obj->getDeliveryName();
        $replace["currency_id"] = $so_obj->getCurrencyId();
        if (($so_obj->getDeliveryCountryId() == 'ES') || ($so_obj->getDeliveryCountryId() == 'PT')) {
            $replace["aftership_url"] = 'envio.aftership.com';
        } elseif (($so_obj->getDeliveryCountryId() == 'FR') || ($so_obj->getDeliveryCountryId() == 'BE')) {
            $replace["aftership_url"] = 'suivi.aftership.com';
        } elseif ($so_obj->getDeliveryCountryId() == 'IT') {
            $replace["aftership_url"] = 'spedizione.aftership.com';
        } else {
            $replace["aftership_url"] = 'shipment.aftership.com';
        }

        $replace["delivery_address_text"] = str_replace("|", "\n", str_replace("||", "|", $so_obj->getDeliveryAddress()))
            . "\n" . $so_obj->getDeliveryCity() . " " . $so_obj->getDeliveryState()
            . " " . $so_obj->getDeliveryPostcode() . "\n" . $country->getName();
        $replace["delivery_address"] = nl2br($replace["delivery_address_text"]);
        $replace["billing_address_text"] = str_replace("|", "\n",
                $so_obj->getBillAddress()) . "\n" . $so_obj->getBillCity() . " " . $so_obj->getBillState()
            . " " . $so_obj->getBillPostcode() . "\n" . $country->getName();
        $replace["billing_address"] = nl2br($replace["billing_address_text"]);
        $replace['currency_sign'] = (empty($currency_obj) ? $so_obj->getCurrencyId() : $currency_obj->getSign());
        $currency_sign = (empty($currency_obj) ? $so_obj->getCurrencyId() : $currency_obj->getSign());
        $replace["amount"] = platform_curr_format($so_obj->getAmount(), 0);
        $replace["timestamp"] = date("d/m/Y");
        $replace["sh_no"] = $sh_no;

        // show text only if it is split order
        $replace["partial_ship_text"] = "";
        if (isset($split_so_group) && $split_so_group != $so_obj->getSoNo()) {
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
        $country_id = $pbv_obj->getPlatformCountryId();
        $email_sender = "no-reply@digitaldiscount.co.uk";
        $replace["support_email"] = $email_sender;
        $replace["image_url"] = $this->getDao('Config')->valueOf("default_url");
        $replace["logo_file_name"] = $this->getDao('Config')->valueOf("logo_file_name");
        if (!empty($courier_id)) {
            $replace["courier_id"] = $courier_id;
            $replace["tracking_id"] = $tracking_no;
            $replace["track_no"] = $track_num;
            $courier_obj = $this->getDao('Courier')->get(array("id" => $courier_id));
            if ($courier_obj) {
                $replace["tracking_link"] = $courier_obj->getTrackingLink();
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
            $cur_qty = $item->getQty();
            $cur_vat_total = $item->getVatTotal();
            $cur_amount = $item->getAmount();
            $price = $item->getUnitPrice();
            $cur_sub_total = $price * $cur_qty;
            $sub_total += $cur_sub_total;
            $total_vat += $cur_vat_total;
            $total += $cur_amount;
            $space_for_item_name = 52 - strlen($item->getName());
            if ($space_for_item_name > 0) {
                $item_name_tab = "\t";
                $num_of_tab = floor($space_for_item_name / 4);

                for ($i = 0; $i <= $num_of_tab; $i++) {
                    $item_name_tab .= "\t";
                }
            }

            $replace["so_items_text"] .=
                $item->getName() . $item_name_tab . $cur_qty . "\t" . platform_curr_format($price, 0) . "\t" . platform_curr_format($cur_sub_total, 0) . "\r\n";
            $replace["so_items"] .=
                "<tr>
                    <td style='padding:4px 20px; color:#444; font-family:Arial; font-size: 12px;'>" . $item->getName() . "</td>
                    <td align='left' valign='top' style='padding:4px 10px; color:#444; font-family:Arial; font-size: 12px;'>$cur_qty</td>
                    <td align='left' valign='top' style='padding:4px 10px; color:#444; font-family:Arial; font-size: 12px;'>" . $currency_sign . " " . platform_curr_format($price, 0) . "</td>
                    <td align='left' valign='top' style='padding:4px 10px; color:#444; font-family:Arial; font-size: 12px;'>" . $currency_sign . " " . platform_curr_format($cur_sub_total, 0) . "</td>
                </tr>\n";

            $replace["so_items_desc"] .= "<tr><td>$cur_qty x " . $item->getName() . "</td></tr>\n";

            $i++;
        }

        $dc = $so_obj->getDeliveryCharge();
        $total += $dc;
        $replace["subtotal"] = platform_curr_format($sub_total, 0);
        $replace["total_vat"] = platform_curr_format($total_vat, 0);

        //#2182 add the processing fee
        $extobj = $this->getDao('SoExtend')->get(["so_no" => $so_obj->getSoNo()]);
        if ($extobj) {
            $processing_fee = $extobj->getOfflineFee();
        }

        if (is_null($processing_fee)) {
            $processing_fee = 0;
        }
        $replace["processing_fee"] = platform_curr_format($processing_fee, 0);
        //#2182 add the processing fee to the total fee
        $total += $processing_fee;
        $replace["total"] = platform_curr_format($total, 0);

        $dc = $so_obj->getDeliveryCharge();
        $total += $dc;
        $dc_vat = $dc * ($so_obj->getVatPercent() / (100 + $so_obj->getVatPercent()));
        $dc_sub_total = $dc - $dc_vat;
        $replace["dc_sub_total"] = platform_curr_format($dc_sub_total, 0);
        $replace["dc_vat"] = platform_curr_format($dc_vat, 0);
        $replace["delivery_charge"] = platform_curr_format($dc, 0);
        $replace["total_sub_total"] = platform_curr_format($sub_total + $dc_sub_total, 0);
        $replace["total_total_vat"] = platform_curr_format($total_vat + $dc_vat, 0);

        $dto = new EventEmailDto;

        if ($delay_order = $this->delayedOrderService->isDelayOrder($so_obj->getSoNo())) {
            $delay_type = $delay_order->getStatus();
            $delayEmailDispatchId = "";
            if ($delay_type == 1) {
                $delayEmailDispatchId = 'minor_delay_dispatch_email';
            } elseif ($delay_type == 2) {
                $delayEmailDispatchId = 'major_delay_dispatch_email';
            }

            $dto->setEventId($delayEmailDispatchId);
            $dto->setMailFrom("jenny.leung@valuebasket.com");
            $dto->setMailTo($client->getEmail());
            $dto->setTplId($delayEmailDispatchId);
            $dto->setLangId($lang_id);

            if (file_exists(APPPATH . "language/template_service/" . $lang_id . "/" . $delayEmailDispatchId . ".ini")) {
                $data_arr = parse_ini_file(APPPATH . "language/template_service/" . $lang_id . "/" . $delayEmailDispatchId . ".ini");
            }

            if (!is_null($data_arr)) {
                $replace = array_merge($replace, $data_arr);
            }
            $dto->setReplace($replace);

            if ($delay_order_obj = $this->delayedOrderService->getDao('DelayedOrder')->get(["so_no" => $so_obj->getSoNo()])) {
                $delay_status = $delay_type + 2;
                $delay_order_obj->setStatus($delay_status);
                $this->delayedOrderService->getDao('DelayedOrder')->update($delay_order_obj);
            }
        } else {

            if ($this->isFilfullWowEmailCriteria($so_obj->getDeliveryCountryId(), $so_obj->getSoNo(), $replace['courier'])
                && is_file($this->config->valueOf("wow_tpl_path") . "wow_email.html")
            ) {
                # SBF #4168 - if fulfill wow criteria, send info to FIANET
                $this->reviewFianetService->sendOrderData($so_obj, $client);

                $dto->setEventId("wow_email_dispatch");
                $dto->setMailFrom($email_sender);
                $dto->setMailTo($client->getEmail());
                // bcc send to eKomi
                if (!$this->getDao('SoHoldReason')->getNumRows(["so_no" => $so_obj->getSoNo(), "reason IN ('change_of_address', 'cscc', 'csvv')" => null])) {
                    switch ($lang_id) {
                        case 'fr':
                            $dto->setMailBcc(["36774-valuebasketcomfrfr-fr@connect.ekomi.de", "valuebasketbccemail@gmail.com", "ming@valuebasket.com", "a9a163bd@trustpilotservice.com"]);
                            break;

                        case 'en':
                            $dto->setMailBcc(["27426-valuebasket2-en@connect.ekomi.de", "valuebasketbccemail@gmail.com", "ming@valuebasket.com", "53b4e6ff@trustpilotservice.com"]);
                            break;

                        case 'it':
                            $dto->setMailBcc(["44780-valuebasketltd-en@connect.ekomi.de", "valuebasketbccemail@gmail.com", "ming@valuebasket.com", "70039a72@trustpilotservice.com"]);
                            break;

                        case 'es':
                            $dto->setMailBcc(["40754-valuebasketes-es@connect.ekomi.de", "valuebasketbccemail@gmail.com", "ming@valuebasket.com", "d4229d8b@trustpilotservice.com"]);
                            break;

                        default:
                            break;
                    }
                }
                $bcc = $dto->getMailBcc();

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

                    $dto->setMailBcc($bcc);
                }

                $sendagain = "no-reply@feedback-valuebasket.com";

                if (($so_obj->getBillCountryId() == 'GB') || ($so_obj->getDeliveryCountryId() == 'GB')) {
                    $dto->setMailFrom($email_sender);
                    $dto->setTplId("wow_email_dispatch_gb");
                    $dto->setLangId("en");
                } else {
                    $dto->setTplId("wow_email_dispatch");
                    $dto->setLangId($lang_id);
                }
                $dto->setReplace($replace);
            } else {
                $dto->setEventId("confirm_dispatch");
                $dto->setMailFrom($email_sender);
                $dto->setMailTo($client->getEmail());
                // bcc send to eKomi
                $dto->setMailBcc(array("valuebasketbccemail@gmail.com"));

                if (($so_obj->getBillCountryId() == 'GB') || ($so_obj->getDeliveryCountryId() == 'GB')) {
                    $dto->setMailFrom($email_sender);
                    $dto->setTplId("confirm_dispatch_gb");
                    $dto->setLangId("en");
                } else {
                    $dto->setTplId("confirm_dispatch");
                    $dto->setLangId($lang_id);
                }
                $dto->setReplace($replace);
            }

        }
        // attach invoice to dispatch email
        $data_path = $this->getDao('Config')->valueOf("data_path");
        $html = $this->getInvoiceContent([$so_obj->getSoNo()], 1);
        $so_no = $so_obj->getSoNo();
        $att_file = $this->pdfRenderingService->convertHtmlToPdf($html, $data_path . "/invoice/Invoice_" . $so_no . ".pdf", "F", $lang_id);
        $replace["att_file"] = $att_file;
        $dto->setReplace($replace);

        if ($get_email_html === FALSE) {
            $this->eventService->fireEvent($dto, FALSE);
        } else {
            $email_msg = $this->eventService->fireEvent($dto, TRUE);
            return $email_msg;
        }

        unlink($att_file);
    }

    function isFilfullWowEmailCriteria($delivery_country_id, $so_no, &$replace_courier = Null)
    {
        //If country is MY, then skip
        $skipWowMailCountryArr = ['MY'];

        if (in_array($delivery_country_id, $skipWowMailCountryArr)) {
            return false;
        }
        //SBF 5678 add courier NOT fulfill the wow_email criteria
        //if NOT fulfill the wow_email criteria, then skip
        $wow_mail_obj = $this->getDao('So')->getWowMailList(["so.so_no" => $so_no, "a.courier_id <> 'deutsch-post'" => null, "a.courier_id <> 'HK_Post'" => null, "a.courier_id <> 'hong-kong-post'" => null, "a.courier_id <> 'Quantium'" => null, "a.courier_id <> 'royal-mail'" => null, "a.courier_id <> 'singapore-post'" => null, "a.courier_id <> 'swiss-post'" => null, "a.courier_id <> 'uk-mail'" => null, "a.courier_id <> 'USPS'" => null, "a.courier_id <> 'USPSPM'" => null, "so.biz_type NOT IN ('SPECIAL', 'MANUAL', 'EBAY')" => null]);

        if (!$wow_mail_obj) {
            return false;
        } else {
            $replace_courier = @call_user_func(array($wow_mail_obj[0], "getCourierId"));
        }
        return true;
    }

    public function getInvoiceContent($so_no_list = [], $gen_pdf = 0)
    {
        # sbf #3746 don't include complementary accessory on front end
        $ca_catid_arr = implode(',', $this->getDao('ProductComplementaryAcc')->getAccessoryCatidArr());

        $run = 0;
        $website_domain = $this->getDao('Config')->valueOf('website_domain');
        $website_domain = base_url();
        $total_cnt = count($so_no_list);
        $cursign_arr = $this->currencyService->getNameWithIdKey();

        if (count($so_no_list)) {
            $valid = 0;
            $content = "";
            if ($gen_pdf) {
                include_once APPPATH . "data/invoicePdf.php";
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
                    $cur_platform_id = $so_obj->getPlatformId();
                    $pbv_obj = $this->getDao('PlatformBizVar')->get(array("selling_platform_id" => $cur_platform_id));
                    $so_lang_id = $pbv_obj->getLanguageId();
                    $data = [];
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
                            $data["sales_email"] = $this->getSalesEmail($so_lang_id);
                            $data["csemail"] = $this->getCsSupportEmail($so_lang_id);
                            $data["return_email"] = $this->getReturnEmail($so_lang_id);
                            break;
                    }

                    $itemlist = $this->getDao('SoItemDetail')->getItemsWithName(array("so_no" => $obj, "p.cat_id NOT IN ($ca_catid_arr)" => NULL));
                    $so_ext_obj = $this->getDao('SoExtend')->get(["so_no" => $obj]);

                    $data["lang"] = $ar_lang[$so_lang_id];
                    $data["website_domain"] = base_url();

                    $data["cursign"] = $cursign_arr[$so_obj->getCurrencyId()];

                    $data["order_no"] = $so_obj->getClientId() . "-" . $so_obj->getSoNo();
                    $data["amazon_order_no"] = $so_obj->getPlatformOrderId();
                    $data["order_date"] = date("d/m/Y", strtotime($so_obj->getOrderCreateDate()));
                    $bcountry_obj = $this->getDao('Country')->get(array("country_id" => $so_obj->getBillCountryId()));
                    list($bill_addr_1, $bill_addr_2, $bill_addr_3) = explode("|", $so_obj->getBillAddress());
                    $bstatezip = trim($so_obj->getBillState() . ", " . $so_obj->getBillPostcode());
                    if ($bstatezip != ",") {
                        $bstatezip = ereg_replace("^, ", "", $bstatezip);
                        $bstatezip = ereg_replace(",$", "", $bstatezip) . "<br>";
                    } else {
                        $bstatezip = "";
                    }
                    $data["billing_name"] = $so_obj->getBillName();
                    $data["billing_address"] = ($so_obj->getBillCompany() == "" ? "" : $so_obj->getBillCompany() . "<br/>") . $bill_addr_1 . "<br/>" . ($bill_addr_2 == "" ? "" : $bill_addr_2 . "<br/>") . ($bill_addr_3 == "" ? "" : $bill_addr_3 . "<br/>") . $so_obj->getBillCity() . "<br>" . $bstatezip . $bcountry_obj->getName();
                    list($delivery_addr_1, $delivery_addr_2, $delivery_addr_3) = explode("|", $so_obj->getDeliveryAddress());
                    $dcountry_obj = $this->getDao('Country')->get(array("country_id" => $so_obj->getDeliveryCountryId()));
                    $dstatezip = trim($so_obj->getDeliveryState() . ", " . $so_obj->getDeliveryPostcode());
                    if ($dstatezip != ",") {
                        $dstatezip = ereg_replace("^, ", "", $dstatezip);
                        $dstatezip = ereg_replace(",$", "", $dstatezip) . "<br>";
                    } else {
                        $dstatezip = "";
                    }
                    $data["delivery_name"] = $so_obj->getDeliveryName();
                    $data["delivery_address"] = ($so_obj->getDeliveryCompany() == "" ? "" : $so_obj->getDeliveryCompany() . "<br/>") . $delivery_addr_1 . "<br/>" . ($delivery_addr_2 == "" ? "" : $delivery_addr_2 . "<br/>") . ($delivery_addr_3 == "" ? "" : $delivery_addr_3 . "<br/>") . $so_obj->getDeliveryCity() . "<br>" . $dstatezip . $dcountry_obj->getName();

                    $item_information = "";
                    $bvat = 0;
                    $vat = 0;
                    $sum = 0;
                    foreach ($itemlist as $item_obj) {

                        $prod_obj = $this->getDao('Product')->get(array("sku" => $item_obj->getMainProdSku()));

                        $amount_total = $item_obj->getAmount();
                        $vat_total = $item_obj->getVatTotal();
                        $amount_total_bvat = $amount_total - $vat_total;
                        $unit_price_bvat = round(($amount_total - $vat_total) / $item_obj->getQty(), 2);
                        $tmp = $this->getDao('Product')->get(array("sku" => $item_obj->getMainProdSku()));
                        if ($gen_pdf) {
                            $imagepath = get_image_file($tmp->getImage(), 's', $tmp->getSku());
                        } else {
                            $imagepath = base_url() . get_image_file($tmp->getImage(), 's', $tmp->getSku());
                        }

                        $item_information .= '<tr>
                                                <td align="center"><img src="' . $imagepath . '"></td>
                                                <td align="left">' . $item_obj->getItemSku() . ' - ' . $item_obj->getName() . '</td>
                                                <td align="right">' . platform_curr_format($item_obj->getUnitPrice()) . '</td>
                                                <td align="right">' . $item_obj->getQty() . '</td>
                                                <td align="right"><b>' . platform_curr_format($amount_total) . '</b></td>
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
                    $sid = $so_obj->getDeliveryCharge();
                    if ($so_ext_obj && $so_ext_obj->getVatexempt() == "0") {
                        $sid_vat = $sid * $so_obj->getVatPercent() / (100 + $so_obj->getVatPercent());

                    } else {
                        $sid_vat = 0;
                    }
                    $sid = platform_curr_round($cur_platform_id, $sid);
                    $sid_vat = platform_curr_round($cur_platform_id, $sid_vat);
                    $sid_bvat = platform_curr_round($cur_platform_id, $sid - $sid_vat);
                    $data["currency"] = $so_obj->getCurrencyId();
                    $data["promotion_code"] = $so_obj->getPromotionCode();
                    $data["sid"] = $sid;
                    $data["sid_vat"] = $sid_vat;
                    $data["sid_bvat"] = $sid_bvat;

                    $ofee = $ofee_vat = $ofee_bvat = 0;
                    $extobj = $this->getDao('SoExtend')->get(array("so_no" => $so_obj->getSoNo()));
                    if ($extobj) {
                        if ($extobj->getOfflineFee() > 0) {
                            $data["offline_fee"] = "<tr>
                                <td colspan='2'>&nbsp;</td>
                                <td colspan='2' align='right' bgcolor='#DDDDDD' style='border:1px solid #BBBBBB; border-width:0px 0px 1px 1px;'><b>" . $ar_lang[$so_lang_id]["offline_fee"] . "</b></td>
                                <td align='right' bgcolor='#F0F0F0' valign='top' style='border:1px solid #BBBBBB; border-width:0px 1px 1px 1px;'><b>" . platform_curr_format($extobj->getOfflineFee()) . "</b></td>
                            </tr>";
                            $ofee = platform_curr_round($cur_platform_id, $extobj->getOfflineFee());
                            $ofee_vat = platform_curr_round($cur_platform_id, $ofee * $so_obj->getVatPercent() / (100 + $so_obj->getVatPercent()));
                            $ofee_bvat = platform_curr_round($cur_platform_id, $ofee - $ofee_vat);
                        } else if ($extobj->getOfflineFee() < 0) {
                            $data["offline_fee"] = "<tr>
                                <td colspan='2'>&nbsp;</td>
                                <td colspan='2' align='right' bgcolor='#DDDDDD' style='border:1px solid #BBBBBB; border-width:0px 0px 1px 1px;'><b>" . $ar_lang[$so_lang_id]["discount"] . "</b></td>
                                <td align='right' bgcolor='#F0F0F0' valign='top' style='border:1px solid #BBBBBB; border-width:0px 1px 1px 1px;'><b>" . platform_curr_format($extobj->getOfflineFee()) . "</b></td>
                            </tr>";
                            $ofee = platform_curr_round($cur_platform_id, $extobj->getOfflineFee());
                            $ofee_vat = platform_curr_round($cur_platform_id, $ofee * $so_obj->getVatPercent() / (100 + $so_obj->getVatPercent()));
                            $ofee_bvat = platform_curr_round($cur_platform_id, $ofee - $ofee_vat);
                        } else {
                            $data["offline_fee"] = "";
                        }
                    }

                    $data["total"] = $so_obj->getAmount();
                    $data["total_vat"] = platform_curr_round($cur_platform_id, $data["total"] * $so_obj->getVatPercent() / (100 + $so_obj->getVatPercent()));
                    $data["total_bvat"] = platform_curr_round($cur_platform_id, $data["total"] - $data["total_vat"]);
                    if (!$data["payment_method"] = $this->getSoPaymentGateway($so_obj->getSoNo())) {
                        $data["payment_method"] = "N/A";
                    }

                    $content .= $this->getInvoiceBody($data, $gen_pdf);
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

    private function getInvoiceBody($data = [], $gen_pdf = 0)
    {
        foreach ($data as $key => $value) {
            ${$key} = $value;
        }

        if ($gen_pdf) {
            include APPPATH . "data/invBodyPdf.php";
        } else {
            include APPPATH . "data/invBody.php";
        }
        return $body;
    }

    public function fire_aftership_thank_you_email($so_obj, $sh_no, $ap_status)
    {

        $country = $this->getDao('Country')->get(array("country_id" => $so_obj->getDeliveryCountryId()));
        $so_items = $this->getDao('SoItemDetail')->getItemsWithName(array("so_no" => $so_obj->getSoNo(), "p.cat_id NOT IN ($ca_catid_arr)" => NULL));
        $client = $this->getDao('Client')->get(array("id" => $so_obj->getClientId()));
        $currency_obj = $this->getDao('Currency')->get(['currency_id' => $so_obj->getCurrencyId()]);
        $sh_obj = $this->getDao('SoShipment')->get(['sh_no' => $sh_no]);
        if ($sh_obj->getCourierId()) {
            if ($courier_obj = $this->getDao('Courier')->get(array("id" => $sh_obj->getCourierId()))) {
                $courier_id = $courier_obj->get_courier_name();
                if ($sh_obj->getCourierId() == 'DPD_NL' && $sh_obj->getTrackingNo() && $courier_obj->getTrackingLink()) {
                    $tracking_no = '<a href="' . $courier_obj->getTrackingLink() . $sh_obj->getTrackingNo() . '" target="_blank">' . $sh_obj->getTrackingNo() . '</a>';
                } else {
                    $tracking_no = (empty($sh_obj) ? '' : $sh_obj->getTrackingNo());
                }
            }
        }
        $platform_id = $so_obj->getPlatformId();
        $pbv_obj = $this->getDao('PlatformBizVar')->get(array('selling_platform_id' => $platform_id));
        $lang_id = $pbv_obj->getLanguageId();

        $replace["so_no"] = $so_obj->getSoNo();
        $replace["client_id"] = $so_obj->getClientId();
        $replace["forename"] = $client->getForename();
        $replace["email"] = $client->getEmail();
        $replace["bill_name"] = $so_obj->getBillName();
        $replace["purchase_date"] = $so_obj->getOrderCreateDate();
        $replace["promotion_code"] = $so_obj->getPromotionCode();
        $replace["delivery_days"] = $so_obj->get_expect_del_days();
        $replace["delivery_name"] = $so_obj->getDeliveryName();
        $replace["currency_id"] = $so_obj->getCurrencyId();

        $replace["delivery_address_text"] = str_replace("|", "\n", str_replace("||", "|", $so_obj->getDeliveryAddress()))
            . "\n" . $so_obj->getDeliveryCity() . " " . $so_obj->getDeliveryState()
            . " " . $so_obj->getDeliveryPostcode() . "\n" . $country->getName();
        $replace["delivery_address"] = nl2br($replace["delivery_address_text"]);
        $replace["billing_address_text"] = str_replace("|", "\n",
                $so_obj->getBillAddress()) . "\n" . $so_obj->getBillCity() . " " . $so_obj->getBillState()
            . " " . $so_obj->getBillPostcode() . "\n" . $country->getName();
        $replace["billing_address"] = nl2br($replace["billing_address_text"]);

        $replace['currency_sign'] = (empty($currency_obj) ? $so_obj->getCurrencyId() : $currency_obj->getSign());
        $currency_sign = (empty($currency_obj) ? $so_obj->getCurrencyId() : $currency_obj->getSign());
        $replace["amount"] = platform_curr_format($so_obj->getAmount(), 0);
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
        $country_id = $pbv_obj->getPlatformCountryId();
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
                $replace["tracking_link"] = $courier_obj->getTrackingLink();
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

        $dc = $so_obj->getDeliveryCharge();
        $total += $dc;
        $replace["subtotal"] = platform_curr_format($sub_total, 0);
        $replace["total_vat"] = platform_curr_format($total_vat, 0);

        //#2182 add the processing fee
        $extobj = $this->getDao('SoExtend')->get(array("so_no" => $so_obj->getSoNo()));
        if ($extobj) {
            $processing_fee = $extobj->getOfflineFee();
        }

        if (is_null($processing_fee)) {
            $processing_fee = 0;
        }
        $replace["processing_fee"] = platform_curr_format($processing_fee, 0);
        //#2182 add the processing fee to the total fee
        $total += $processing_fee;
        $replace["total"] = platform_curr_format($total, 0);

        $dc = $so_obj->getDeliveryCharge();
        $total += $dc;
        $dc_vat = $dc * ($so_obj->getVatPercent() / (100 + $so_obj->getVatPercent()));
        $dc_sub_total = $dc - $dc_vat;
        $replace["dc_sub_total"] = platform_curr_format($dc_sub_total, 0);
        $replace["dc_vat"] = platform_curr_format($dc_vat, 0);
        $replace["delivery_charge"] = platform_curr_format($dc, 0);
        $replace["total_sub_total"] = platform_curr_format($sub_total + $dc_sub_total, 0);
        $replace["total_total_vat"] = platform_curr_format($total_vat + $dc_vat, 0);
        $replace["last_update_time"] = '';

        $dto = new EventEmailDto;
        if (($so_obj->getBizType() == 'ONLINE') && ($courier_id != 'HK POST') && ($courier_id != 'hong kong post') && ($courier_id != 'Quantium')) {
            if ($this->is_filfull_aftership_thank_you_email_criteria($so_obj->getDeliveryCountryId(), $so_obj->getSoNo(), $replace['last_update_time'], $ap_status)) {
                # SBF #4740 - if fulfull thank you email - product delivered on time send info to FIANET
                $send_mail = 1;
                $this->reviewFianetService->sendOrderData($so_obj, $client);

                $dto->setEventId("aftership_thank_you_mail");
                $dto->setMailFrom($email_sender);
                $dto->setMailTo($client->getEmail());
                if (!$this->getDao('SoHoldReason')->getNumRows(array("so_no" => $so_obj->getSoNo(), "reason IN ('change_of_address', 'cscc', 'csvv')" => null))) {
                    switch ($lang_id) {
                        case 'fr':
                            $dto->setMailBcc(array("valuebasketbccemail@gmail.com", "ming@valuebasket.com"));
                            break;

                        case 'en':
                            $dto->setMailBcc(array("valuebasketbccemail@gmail.com", "ming@valuebasket.com"));
                            break;

                        case 'it':
                            $dto->setMailBcc(array("valuebasketbccemail@gmail.com", "ming@valuebasket.com"));
                            break;

                        case 'es':
                            $dto->setMailBcc(array("valuebasketbccemail@gmail.com", "ming@valuebasket.com"));
                            break;

                        default:
                            break;
                    }
                }
                $bcc = $dto->getMailBcc();

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

                if (($so_obj->getBillCountryId() == 'GB') || ($so_obj->getDeliveryCountryId() == 'GB')) {
                    $dto->setMailFrom($email_sender);
                    $dto->setTplId("aftership_thank_you_mail_gb");
                    $dto->setLangId("en");
                } else {
                    $dto->setTplId("aftership_thank_you_mail");
                    $dto->setLangId($lang_id);
                }
                $dto->setReplace($replace);
            } else {
                $dto->setEventId("aftership_late_delivery_mail");
                $dto->setMailFrom($email_sender);
                $dto->setMailTo($client->getEmail());
                $dto->setMailBcc(array("valuebasketbccemail@gmail.com"));
                $dto->set_platform_id($platform_id);

                if (($so_obj->getBillCountryId() == 'GB') || ($so_obj->getDeliveryCountryId() == 'GB')) {
                    $dto->setMailFrom($email_sender);
                    $dto->setTplId("aftership_late_delivery_mail");
                    $dto->setLangId("en");
                } else {
                    $dto->setTplId("aftership_late_delivery_mail");
                    $dto->setLangId($lang_id);
                }
                $dto->setReplace($replace);
            }

        }
        // attach invoice to dispatch email
        $data_path = $this->getDao('Config')->valueOf("data_path");
        $html = $this->getInvoiceContent([$so_obj->getSoNo()], 1);
        $so_no = $so_obj->getSoNo();
        $att_file = $this->pdfRenderingService->convertHtmlToPdf($html, $data_path . "/invoice/Invoice_" . $so_no . ".pdf", "F", $lang_id);
        $replace["att_file"] = $att_file;
        $dto->setReplace($replace);
        $this->eventService->fireEvent($dto);

        unlink($att_file);
    }

    function is_filfull_aftership_thank_you_email_criteria($delivery_country_id, $so_no, &$replace_last_update = Null, $ap_status)
    {
        if ($ap_status == '6') {
            $aftshipThankYouMailObj = $this->getDao('So')->getThankYouMailList(["so.so_no" => $so_no, "a.courier_id NOT LIKE '%HK%POST%'" => null, "a.courier_id NOT LIKE '%hong-kong-post%'" => null, "a.courier_id NOT LIKE '%Quantium%'" => null, "so.biz_type NOT IN ('SPECIAL', 'MANUAL', 'EBAY', 'FNAC', 'RAKUTEN', 'QOO10')" => null], $replace_last_update, $ap_status);
        }

        $ontime = $aftshipThankYouMailObj['delivered_on_time'];

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

            $so_no = $so->getSoNo();
            $soid_list = $this->getDao('SoItemDetail')->getList(array("so_no" => $so_no));
            $stock_alert = [];
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
                    $stock_alert[$prod_obj->getSku()] = $prod_obj->getName();
                }
                if ($nwqty == 5) {
                    $five_alert[$prod_obj->getSku()] = $prod_obj->getName();
                }
                $this->getDao('Product')->update($prod_obj);
            }


            if (count($five_alert)) {
                $email_dto = new EventEmailDto();
                $message .= "Please be advised that order number " . $so->getClientId() . "-" . $so->getSoNo() . " platform " . $so->getPlatformId() . " has triggered the following product to control quantity 5:\n\n";
                foreach ($five_alert as $key => $value) {
                    $message .= $key . " - " . $value . "\n";
                }

                $message = preg_replace("{\n$}", "", $message);
                $title = "[Panther] Website Order Quantity Warning, QTY = 5";
                $dto = clone $email_dto;
                $dto->setEventId("notification");
                $dto->setMailTo(array("bd@eservicesgroup.net"));
                //$dto->setMailTo("merchat@eservicesgroup.net");
                $dto->setMailFrom("do_not_reply@valuebasket.com");
                $dto->setTplId("general_alert");
                $dto->setReplace(array("title" => $title, "message" => $message));
                $this->eventService->fireEvent($dto);
            }

            if (count($stock_alert)) {
                $email_dto = new EventEmailDto();
                $message .= "Please be advised that order number " . $so->getClientId() . "-" . $so->getSoNo() . " platform " . $so->getPlatformId() . " has triggered the following product(s) to possibly be out of stock:\n\n";
                foreach ($stock_alert as $key => $value) {
                    $message .= $key . " - " . $value . "\n";
                }

                $message = preg_replace("{\n$}", "", $message);
                $title = "[Panther] Website Order Quantity Warning, QTY = 0";
                $dto = clone $email_dto;
                $dto->setEventId("notification");
                $dto->setMailTo(array("bd@eservicesgroup.net"));
                //$dto->setMailTo("merchat@eservicesgroup.net");
                $dto->setMailFrom("do_not_reply@valuebasket.com");
                $dto->setTplId("general_alert");
                $dto->setReplace(array("title" => $title, "message" => $message));
                $this->eventService->fireEvent($dto);
            }
        }
    }

    public function orderQuickSearch($where = [], $option = [])
    {
        if ($option["num_rows"] == 1) {
            return $this->getDao('So')->orderQuickSearch($where, $option);
        } else {
            $list = $this->getDao('So')->orderQuickSearch($where, $option);
            $ret = [];
            foreach ($list as $value) {
                if ($value->getStatus() < 6 && $value->getStatus() > 2) {
                    $items = $this->getDao('So')->getOrderItemList($value->getSoNo());
                } else {
                    $items = $this->getDao('So')->getOrderItemListDone($value->getSoNo());
                }
                $value->setItems($items);
                $ret[] = $value;
            }
            return $ret;
        }
    }

    public function updateCompleteOrder($so_obj, $trans_handle = 1)
    {
        if ($soid_list = $this->getDao('SoItemDetail')->getList(["so_no" => $so_obj->getSoNo()])) {
            $success = 1;

            if ($trans_handle) {
                $this->getDao('So')->db->trans_start();
            }

            foreach ($soid_list as $soid_obj) {
                $soid_obj->setStatus(1);
                if ($this->getDao('SoItemDetail')->update($soid_obj) === FALSE) {
                    $success = 0;
                    $error = __LINE__ . " " . $this->db->_error_message();
                    break;
                }
            }
            if ($success) {
                $update_status = false;
                if ($so_obj->getStatus() <> 6) {
                    $update_status = true;
                    $status = 6;
                }
                $so_obj->setStatus(6);
                $so_obj->setDispatchDate(date("Y-m-d H:i:s"));
                if ($this->getDao('So')->update($so_obj) === FALSE) {
                    $success = 0;
                }
                if ($update_status) {
                    $this->updateIofStatusBySo($so_obj->getSoNo(), $status);
                }
            }

            if ($trans_handle) {
                if (!$success) {
                    $this->getDao('So')->db->trans_rollback();
                }
                $this->getDao('So')->db->trans_complete();
            }

            return $success;
        }
    }

    public function isCodOrder($so_no)
    {
        $so_ext_obj = $this->getDao('SoExtend')->get(["so_no" => $so_no]);
        $sops_obj = $this->getDao('SoPaymentStatus')->get(["so_no" => $so_no]);
        $so_obj = $this->getDao('So')->get(["so_no" => $so_no]);
        if ((($so_obj->getBizType() != 'SPECIAL')
                && ($so_ext_obj->getOfflineFee() == 15)
                && ($sops_obj->getPaymentGatewayId() == "worldpay_moto")
                && ($so_obj->getPlatformId() == "WEBSG"))
            ||
            (($so_obj->getBizType() != 'SPECIAL') && ($sops_obj->getPaymentGatewayId() == "worldpay_moto_cash"))
            ||
            (($so_obj->getBizType() != 'SPECIAL') && ($sops_obj->getPaymentGatewayId() == "paypal_cash"))
        ) {
            return true;
        }
        return false;
    }

    public function getSalesComparisonDataByPeriod($where = [], $classname = '')
    {
        return $this->getDao('So')->getSalesComparisonDataByPeriod($where, $classname);
    }

    public function getConfirmedSo($where = [], $from_date = '', $to_date = '', $is_light_version = false, $dispatch_report = false)
    {
        return $this->getDao('So')->getConfirmedSo($where, $from_date, $to_date, $is_light_version, $dispatch_report);
    }

    public function getSplitSoReport($where = [], $option = [], $from_date = '', $to_date = '')
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

    public function getFnacPendingPaymentOrders($where = [], $option = [])
    {
        return $this->getDao('So')->getFnacPendingPaymentOrders($where, $option);
    }

    public function getEbayFeedbackEmailContent($where = [], $option = [])
    {
        return $this->getDao('So')->getEbayFeedbackEmailContent($where, $option);
    }

    function getWorkingDays($start_ts, $end_ts, $holidays = [])
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
        $client_obj = $this->getDao('Client')->get(array("id" => $so_obj->getClientId()));

        $email_dto = new EventEmailDto();
        $email_dto->set_event_id("special_aps_cs_notification");
        $email_dto->set_mail_from("do_not_reply@valuebasket.com");
        $email_dto->set_mail_to(array("salesteam@eservicesgroup.net", "EUTeam@eservicesgroup.com", "jesslyn@eservicesgroup.com"));
        $email_dto->set_mail_cc("csmanager@eservicesgroup.net");
        $email_dto->set_tpl_id("special_aps_cs_notification");
        $email_dto->set_lang_id("en");

        $replace = [];

        $replace["site_name"] = "VB";
        $replace["so_no"] = $so_obj->getSoNo();
        $replace["forename"] = $so_obj->getBillName();
        $replace["tel"] = $client_obj->get_tel_1() . $client_obj->get_tel_2() . $client_obj->get_tel_3();
        $replace["del_address"] = $client_obj->get_del_address_1() . " " . $client_obj->get_del_address_2() . " " . $client_obj->get_del_address_3();
        $replace["del_city"] = $client_obj->get_del_city();
        $replace["del_country"] = $client_obj->get_del_country_id();
        $replace["default_url"] = $this->getDao('Config')->valueOf("default_url");
        $replace["logo_file_name"] = $this->getDao('Config')->valueOf("logo_file_name");

        $so_ext_obj = $this->getDao('SoExtend')->get(array("so_no" => $so_obj->getSoNo()));

        $replace["order_reason"] = $so_ext_obj->get_order_reason();
        $replace["order_notes"] = $so_ext_obj->get_notes();

        $email_dto->set_replace($replace);

        $this->eventService->fireEvent($email_dto);
    }

    public function send_aps_order_client_notification_email($so_obj)
    {
        $client_obj = $this->getDao('Client')->get(array("id" => $so_obj->getClientId()));

        $email_dto = new EventEmailDto();
        $email_dto->set_event_id("special_aps_order_notification");
        $email_dto->set_mail_from("do_not_reply@valuebasket.com");
        $email_dto->set_mail_to($client_obj->getEmail());
        $email_dto->set_tpl_id("special_aps_order_notification");
        $email_dto->set_lang_id("en");

        $replace = [];
        include_once(APPPATH . "hooks/country_selection.php");

        $replace["site_url"] = Country_selection::rewrite_domain_by_country("www.valuebaset.com", $so_obj->getBillCountryId());
        $replace["site_name"] = Country_selection::rewrite_site_name($replace["site_url"]);
        $replace["so_no"] = $so_obj->getSoNo();
        $replace["forename"] = $so_obj->getBillName();
        $replace["default_url"] = $this->getDao('Config')->valueOf("default_url");
        $replace["logo_file_name"] = $this->getDao('Config')->valueOf("logo_file_name");

        $email_dto->set_replace($replace);

        $this->eventService->fireEvent($email_dto);
    }

    public function getOrdersBySkuAndStatus($sku, $so_status = 2, $where = [], $option = [])
    {
        return $this->getDao('So')->getOrdersBySkuAndStatus($sku, $so_status, $where, $option);
    }

    public function getEbayPendingShipmentUpdateOrders($where = [], $option = [])
    {
        return $this->getDao('So')->getEbayPendingShipmentUpdateOrders($where, $option);
    }

    public function getQoo10PendingShipmentUpdateOrders($where = [], $option = [])
    {
        return $this->getDao('So')->getQoo10PendingShipmentUpdateOrders($where, $option);
    }

    public function getRakutenPendingShipmentUpdateOrders($where = [], $option = [])
    {
        return $this->getDao('So')->getRakutenPendingShipmentUpdateOrders($where, $option);
    }

    public function getAutomatedFeedbackEmailContent($where = [], $option = [])
    {
        return $this->getDao('So')->getAutomatedFeedbackEmailContent($where, $option);
    }

    public function getSoPriorityScoreInfo($so_no_array)
    {
        $so_no_list = implode($so_no_array, ",");
        return $this->getDao('So')->getSoPriorityScoreInfo($so_no_list);
    }

    public function getPriorityScore($so_no, $result = [])
    {
        if (empty($result)) {
            $result = [];
            $result = $this->getDao('So')->getPriorityScore($so_no);
            if ( !is_array($result) ) {
                $result = (array) $result;
            }
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

        $eu_country_group = ["fr", "gb", "ie", "be", "nl", "pt", "se", "si", "de", "dk"];
        $apac_country_group = ["sg", "my", "th", "tw", "ph"];

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
            if ($manual = $this->getDao('SoPriorityScore')->get(["so_no" => $so_no, "status" => 1])) {
                $score += $manual->getScore();
            }
        }

        return $score;
    }

    public function getPriorityScoreBase($so_no, $result = null)
    {
        if ($this->sub_domain_cache == null) {
            foreach ($this->subjectDomainService->getDao('SubjectDomain')->getList() as $k => $v) {
                $this->sub_domain_cache[$v->getSubject()] = $v->getValue();
            }
        }

        if ($manual = $this->getDao('SoPriorityScore')->get(["so_no" => $so_no, "status" => 1])) {
            $score = $manual->getScore();
        } else {
            $score = 0;

            #if data was not passed in, we need to query for it
            if ($result === null) {
                $result = $this->getDao('So')->getPriorityScore($so_no);
                $result["order_margin"] = null;
            }

            $days = $this->getDays(strtotime($result["order_create_date"]), mktime());
            if ((($ps_obj = $this->getDao('SoPaymentStatus')->get(array("so_no" => $so_no))) !== FALSE) && ($ps_obj))
                $pay_to_account = $ps_obj->getPayToAccount();
            else
                $pay_to_account = "";
            if ((($margin_score = $this->soPriorityScoreService->hitMarginRule($so_no, $result['biz_type'], $days, false, $result["order_margin"])) > 0)
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
                    if (($ps_obj) && ($ps_obj->getPaymentGatewayId() == "paypal")) {
                        $platform_score = $this->sub_domain_cache["PRIORITY_SCORE.PAYMENT_GATEWAY.PAYPAL.HK"];
                    } else if ((($soex_obj = $this->getDao('SoExtend')->get(["so_no" => $so_no])) !== FALSE) && ($soex_obj)) {
                        if ($soex_obj->getOrderReason() == 31) {
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

    function getDays($start_ts, $end_ts)
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
        return $this->getDao('SoPriorityScore')->get(["so_no" => $so_no, "status" => 1]);
    }

    public function getShippingInfo($where = [])
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

    public function getDistinctClientIdList($where = [], $option = [])
    {
        return $this->getDao('So')->getDistinctClientIdList($where, $option);
    }

    public function getRmaCustomerEmailAddress($past_day)
    {
        return $this->getDao('So')->getRmaCustomerEmailAddress($past_day);
    }

    public function getAftershipData($where = [], $option = [])
    {
        return $this->getDao('So')->getAftershipData($where, $option);
    }

    public function getAftershipReportForFtp($where = [], $option = [])
    {
        return $this->getDao('So')->getAftershipReportForFtp($where, $option);
    }

    public function getWowEmailListData($where = [], $option = [])
    {
        return $this->getDao('So')->getWowEmailListData($where, $option);
    }

    public function is_fraud_order($so_obj = '')
    {
        if (!$so_obj)
            return false;

        $so_no = $so_obj->getSoNo();
        if ($client_obj = $this->getDao('Client')->get(array("id" => $so_obj->getClientId()))) {
            $client_email = $client_obj->getEmail();
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

        $so_no = $so_obj->getSoNo();
        if ($client_obj = $this->getDao('Client')->get(array("id" => $so_obj->getClientId()))) {
            $client_email = $client_obj->getEmail();
            if ($black_list_object = $this->get_email_referral_list_service()->get(array('email' => $client_email, '`status`' => 1))) {
                //insert the order into the fraudulent order table
                if (!$fraud_order_obj = $this->get_fraudulent_order_service()->get(array('so_no' => $so_no))) {
                    $new_fraud_order_obj = $this->get_fraudulent_order_service()->get();
                    $new_fraud_order_obj->setSoNo($so_no);
                    $new_fraud_order_obj->set_status(1);
                    if ($this->get_fraudulent_order_service()->insert($new_fraud_order_obj)) {   //set order status as 1
                        if (($so_obj = $this->getDao('So')->get(array("so_no" => $so_no)))) {
                            $update_hold_status = false;
                            if ($so_obj->getHoldStatus() <> 1) {
                                $update_hold_status = true;
                                $holdStatus = 1;
                            }
                            $so_obj->setHoldStatus(1);
                            if ($this->getDao('So')->update($so_obj)) {   //set the so_hold_reason
                                if ($sohr_vo = $this->getDao('SoHoldReason')->get()) {
                                    $hr_obj = $this->sc['So']->getDao('HoldReason')->get(['reason_cat'=>'OT','reason_type'=>'confirmed_fraud','status'=>1]);
                                    if (!$hr_obj) {
                                        $reason_obj = $this->sc['So']->getDao('HoldReason')->get();
                                        $reason_obj->setReasonCat('OT');
                                        $reason_obj->setReasonType('confirmed_fraud');
                                        $reason_obj->setDescription('Confirmed Fraud');

                                        $hr_obj = $this->sc['So']->getDao('HoldReason')->insert($reason_obj);
                                    }

                                    $sohr_vo->setSoNo($so_no);
                                    $sohr_vo->setReason($hr_obj->getId());
                                    $this->getDao('SoHoldReason')->insert($sohr_vo);

                                    $action = "update";
                                    $socc_obj = $this->getDao('SoCreditChk')->get(array("so_no" => $so_no));
                                    if (!$socc_obj) {
                                        $socc_obj = $this->getDao('SoCreditChk')->get();
                                        $action = "insert";
                                    }
                                    $this->getDao('SoCreditChk')->db->trans_start();
                                    $socc_obj->setSoNo($so_no);
                                    $socc_obj->set_fd_status(2);
                                    $this->getDao('SoCreditChk')->$action($socc_obj);

                                    $update_status = false;
                                    if ($so_obj->getStatus() <> 0) {
                                        $update_status = true;
                                        $status = 0;
                                    }

                                    if ($so_obj->getHoldStatus() <> 0) {
                                        $update_hold_status = true;
                                        $holdStatus = 0;
                                    }

                                    $so_obj->setStatus(0);
                                    $so_obj->setHoldStatus(0);
                                    if ($this->getDao('So')->update($so_obj)) {
                                        if ($update_status) {
                                            $this->updateIofStatusBySo($so_no, $status);
                                        }
                                    }

                                    if ($update_hold_status) {
                                        $this->updateIofHoldStatusBySo($so_no, $holdStatus);
                                    }

                                    $this->getDao('SoCreditChk')->db->trans_complete();

                                    //add an order note
                                    $order_note_vo = $this->getDao('OrderNotes')->get();
                                    $order_note_vo->setSoNo($so_no);
                                    $order_note_vo->setNote("system inactivate, blacklisted client");
                                    $this->getDao('OrderNotes')->insert($order_note_vo);


                                    $date_info = date('Y-m-d');
                                    $body = 'Confirmed fraud: ' . $so_no;

                                    $nero = mail("
                                        Compliance@valuebasket.com,
                                        nero@eservicesgroup.com",

                                        "[Panther] {$date_info}: Confirmed fraud", $body
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
                    $so_ext_obj = $this->getDao('SoExtend')->get(array("so_no" => $so_obj->getSoNo()));
                    if (($so_ext_obj->get_order_reason() >= 19) and ($so_ext_obj->get_order_reason() <= 22)) {
                        $requirePermanentHold = true;
                    }
                }
            }
        }
        if ($requirePermanentHold) {
            $parent_so_obj->set_hold_status(self::PERMANENT_HOLD_STATUS);
            $this->getDao('So')->update($parent_so_obj);
            if ($sohr_vo = $this->getDao('SoHoldReason')->get()) {
                $hr_obj = $this->sc['So']->getDao('HoldReason')->get(['reason_cat'=>'OT','reason_type'=>'perm_hold_sales_aps','status'=>1]);
                if (!$hr_obj) {
                    $reason_obj = $this->sc['So']->getDao('HoldReason')->get();
                    $reason_obj->setReasonCat('OT');
                    $reason_obj->setReasonType('perm_hold_sales_aps');
                    $reason_obj->setDescription('Perm Hold Sales Aps');

                    $hr_obj = $this->sc['So']->getDao('HoldReason')->insert($reason_obj);
                }

                $sohr_vo->setSoNo($parent_so_no);
                $sohr_vo->setReason($hr_obj->getId());
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

            $where["so.so_no"] = $so_obj->getSoNo();
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
                    if ($this->getDao('So')->update($parent_so_obj) === FALSE) {
                        $ret["status"] = false;
                        $ret["error_message"] = __LINE__ . "Cannot update so_no <$parent_so_no> with perm hold. SQL: " . $this->db->last_query() . " DBerror: " . $this->db->_error_message();
                    } else {
                        if ($sohr_vo = $this->getDao('SoHoldReason')->get()) {
                            $hr_obj = $this->sc['So']->getDao('HoldReason')->get(['reason_cat'=>'OT','reason_type'=>'perm_hold_sales_aps','status'=>1]);
                            if (!$hr_obj) {
                                $reason_obj = $this->sc['So']->getDao('HoldReason')->get();
                                $reason_obj->setReasonCat('OT');
                                $reason_obj->setReasonType('perm_hold_sales_aps');
                                $reason_obj->setDescription('Perm Hold Sales Aps');

                                $hr_obj = $this->sc['So']->getDao('HoldReason')->insert($reason_obj);
                            }

                            $sohr_vo->setSoNo($parent_so_no);
                            $sohr_vo->setReason($hr_obj->getId());
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

    public function wmsAllocationPlanOrder($solist = [])
    {
        if (!empty($solist)) {
            set_time_limit(300);
            $bodytext = '';
            $mail_send = FALSE;
            $soid_vo = $this->getDao('SoItemDetail')->get();
            foreach ($solist as $key => $so_no) {
                if ($so_obj = $this->getDao('So')->get(["so_no" => $so_no])) {
                    if (($so_obj->getStatus() > 2 && $so_obj->getStatus() < 5) && $so_obj->getRefundStatus() == 0 && $so_obj->getHoldStatus() == 0) {
                        if ($ffi_objlist = $this->getDao('SoItemDetail')->getFulfil(["so.so_no" => $so_no])) {
                            $update_so = [];
                            $this->getDao('So')->db->trans_start();

                            foreach ($ffi_objlist as $obj) {
                                $new_obj = clone $soid_vo;
                                set_value($new_obj, $obj);
                                $so_no = $obj->getSoNo();
                                $line_no = $obj->getLineNo();
                                $item_sku = $obj->getItemSku();
                                $update_so[$line_no][$item_sku] = $new_obj;
                            }

                            $error = 0;
                            if ($update_so) {
                                if ($this->saveSoAllocate($so_no, $update_so) === FALSE) {
                                    $error +=1;
                                }
                            }

                            if ($so_obj = $this->getDao('So')->get(["so_no" => $so_no])) {

                                $update_status = false;
                                if ($so_obj->getStatus() <> 5) {
                                    $update_status = true;
                                    $status = 5;
                                }

                                $so_obj->setStatus("5");
                                if ($this->getDao('So')->update($so_obj)) {
                                    if ($update_status) {
                                        $this->updateIofStatusBySo($so_no, $status);
                                    }
                                } else {
                                    $error +=1;
                                }

                            } else {
                                $error +=1;
                            }

                            if ($error) {
                                $this->getDao('So')->db->trans_rollback();
                            }

                            $this->getDao('So')->db->trans_complete();
                        }
                    } else {

                        $mail_send = TRUE;
                        $bodytext .= "New order so_no {$so_no} > 'to ship' is abnormal, Order status: {$so_obj->getStatus()}, refund_status: {$so_obj->getRefundStatus()}, hold_status:{$so_obj->getHoldStatus()}<br />";
                    }

                }
            }

            if ($mail_send) {
                $header = "From: admin@eservicesgroup.com\r\n";
                $subject = "[Panther] Alert, WMS Allocation Plan New order so_no > 'to ship' is abnormal";
                mail("alice.wu@eservicesgroup.com", "{$subject}", "{$bodytext}", "{$header}");
            }

            return TRUE;
        }

        return FALSE;
    }

    public function saveSoAllocate($so_no, $update_so = []) {
        $soal_vo = $this->getDao('SoAllocate')->get();
        foreach ($update_so as $line_no => $soid_list) {
            foreach ($soid_list as $item_sku => $soid_obj) {
                $soid_obj->setOutstandingQty(0);
                if ($this->getDao('SoItemDetail')->update($soid_obj)) {

                    $this->updateIofOutstandingQtyBySoid($so_no, $line_no, $item_sku, $soid_obj);

                    $action = "update";
                    if (!($soal_obj = $this->getDao('SoAllocate')->get(['so_no'=>$so_no, 'line_no'=>$line_no, 'item_sku'=>$item_sku, 'warehouse_id'=>'HK', 'status'=>1]))) {
                        unset($soal_obj);
                        $soal_obj = clone $soal_vo;
                        $action = "insert";
                        set_value($soal_obj, $soid_obj);
                        $soal_obj->setWarehouseId("HK");
                        $soal_obj->setStatus("1");
                        if ($this->getDao('SoAllocate')->$action($soal_obj) == false) {
                            return false;
                        }
                    }
                }
            }
        }

        return true;
    }

    public function updateIofStatusBySo($so_no, $status)
    {
        $iofObjList = $this->getDao('IntegratedOrderFulfillment')->getList(['so_no'=>$so_no]);
        if((array) $iofObjList) {
            foreach ($iofObjList as $iofObj) {
                $iofObj->setStatus($status);
                $this->getDao('IntegratedOrderFulfillment')->update($iofObj);
            }

            $this->saveOrderStatusHistory($so_no, $status);
        }
    }

    public function saveOrderStatusHistory($so_no, $status)
    {
        $oshVo = $this->getDao('OrderStatusHistory')->get();
        $oshObj = clone $oshVo;
        $oshObj->setSoNo($so_no);
        $oshObj->setStatus($status);
        $oshVo = $this->getDao('OrderStatusHistory')->insert($oshObj);
    }

    public function updateIofHoldStatusBySo($so_no, $holdStatus)
    {
        $iofObjList = $this->getDao('IntegratedOrderFulfillment')->getList(['so_no'=>$so_no]);
        if((array) $iofObjList) {
            foreach ($iofObjList as $iofObj) {
                $iofObj->setholdStatus($holdStatus);
                $this->getDao('IntegratedOrderFulfillment')->update($iofObj);
            }

            $this->saveSoHoldStatusHistory($so_no, $holdStatus);
        }
    }

    public function saveSoHoldStatusHistory($so_no, $holdStatus)
    {
        $oshVo = $this->getDao('SoHoldStatusHistory')->get();
        $oshObj = clone $oshVo;
        $oshObj->setSoNo($so_no);
        $oshObj->setHoldStatus($holdStatus);
        $oshVo = $this->getDao('SoHoldStatusHistory')->insert($oshObj);
    }

    public function updateIofRefundStatusBySo($so_no, $refundStatus)
    {
        $iofObjList = $this->getDao('IntegratedOrderFulfillment')->getList(['so_no'=>$so_no]);
        if((array) $iofObjList) {
            foreach ($iofObjList as $iofObj) {
                $iofObj->setRefundStatus($refundStatus);
                $this->getDao('IntegratedOrderFulfillment')->update($iofObj);
            }
        }
    }

    public function updateIofOutstandingQtyBySoid($so_no, $line_no, $item_sku, $soid_obj)
    {
        if ($iofObj = $this->getDao('IntegratedOrderFulfillment')->get(['so_no'=>$so_no, 'line_no'=>$line_no,'sku'=>$item_sku])) {
            $iofObj->setOutstandingQty($soid_obj->getOutstandingQty());
            $iofObj = $this->getDao('IntegratedOrderFulfillment')->update($iofObj);
        }

        return true;
    }

    public function dealOrderFulfilmentToShip($valueArr)
    {
        $rsresult = "";
        $shownotice = 0;

        $r_where["soal.status"] = 1;
        if ($valueArr["dispatchType"] != 'r') {
            $r_where["hold_status"] = "0";
            $r_where["refund_status"] = "0";
        }
        $r_option["limit"] = -1;
        $r_option["solist"] = $valueArr["checkSoNo"];
        $rlist = $this->getDao('SoAllocate')->getInSoList($r_where, $r_option);

        $success_so = $update_so = [];

        foreach ($rlist as $obj) {
            $so_no = $obj->getSoNo();
            $line_no = $obj->getLineNo();
            $item_sku = $obj->getItemSku();
            $al_id = $obj->getId();
            $update_so[$so_no][$al_id] = $obj;
        }
        if ($update_so) {
            foreach ($valueArr["checkSoNo"] as $so_no) {
                if (!isset($update_so[$so_no])) continue;

                $soal_list = $update_so[$so_no];

                $error = "";
                $success = 1;
                $this->getDao('So')->db->trans_start();

                if ($valueArr["dispatchType"] == 'r') {
                    foreach ($soal_list as $al_id => $soal_obj) {
                        $soid_where["so_no"] = $so_no;
                        $soid_where["line_no"] = $soal_obj->getLineNo();
                        $soid_where["item_sku"] = $soal_obj->getItemSku();

                        if ($soid_obj = $this->getDao('SoItemDetail')->get($soid_where)) {
                            $soid_obj->setOutstandingQty($soid_obj->getQty());
                            if ($this->getDao('SoItemDetail')->update($soid_obj)){
                                $this->updateIofOutstandingQtyBySoid($so_no, $soal_obj->getLineNo(), $soal_obj->getItemSku(), $soid_obj);
                            }

                            $this->getDao('SoAllocate')->delete($soal_obj);
                        } else {
                            $success = 0;
                            $error = __LINE__ . "[" . ($soid_obj ? 1 : 0) . "]" . $this->db->_error_message();
                            break;
                        }
                    }
                    if ($success) {
                        $update_status = false;
                        $status = 5;
                        $so_obj = $this->getDao('So')->get(["so_no" => $so_no]);
                        if ($this->getDao('SoAllocate')->getNumRows(["so_no" => $so_no])) {
                            if ($so_obj->getStatus() <> 4) {
                                $update_status = true;
                                $status = 4;
                            }
                            $so_obj->setStatus("4");
                        } else {
                            if ($so_obj->getStatus() <> 3) {
                                $update_status = true;
                                $status = 3;
                            }
                            $so_obj->setStatus("3");
                        }
                        if (!$this->getDao('So')->update($so_obj)) {
                            $success = 0;
                            $error = __LINE__ . " " . $this->db->_error_message();
                        }
                        if ($update_status) {
                            $this->updateIofStatusBySo($so_no, $status);
                        }
                    }
                } else {
                    $success = $this->moveOrderToDispatch($so_no, $soal_list, $valueArr["postCourierId"], $valueArr["postCourier"]);
                }

                if (!$success) {
                    $this->getDao('So')->db->trans_rollback();
                    $shownotice = 1;
                } else {
                    $success_so[] = $so_no;
                }
                $rsresult .= "{$so_no} -> {$success} " . ($success ? "" : "(error:{$error})") . "\\n";

                $this->getDao('So')->db->trans_complete();
            }
        }
        if ($shownotice) {
            $_SESSION["NOTICE"] = $rsresult;
        }

        return $success_so;
    }

    public function getNextShNo($so_no)
    {
        $last_sh_no = $this->getDao('SoAllocate')->getLastShNo($so_no);
        list($sno, $last_no) = @explode("-", $last_sh_no);
        return $so_no . "-" . sprintf("%02d", $last_no * 1 + 1);
    }

    public function checkCourier($courier_id, $courier, $so_obj)
    {
        if ($courier == "") {
            if ($courier_id == "AMS") {
                $func_amount = $so_obj->getAmount() * $so_obj->getRate();
                return ($func_amount < 80) ? "USPSPM" : "UPS";
            } elseif ($courier_id == "ILG") {
                return ($so_obj->getDeliveryTypeId() != $this->getDao['Config']->valueOf("default_delivery_type")) ? "trackable service" : $courier_id;
            }

            return $courier_id;
        } else {
            return $courier;
        }
    }

    public function moveOrderToDispatch($so_no, $soal_list, $CourierIdVal, $courierVal)
    {
        $success = 1;
        $so_obj = $this->getDao('So')->get(["so_no" => $so_no]);
        $sh_no = $this->getNextShNo($so_no);

        $courier_id = $this->checkCourier($CourierIdVal, $courierVal[$so_no], $so_obj);
        $soshVo = $this->getDao('SoShipment')->get();
        $soshObj = clone $soshVo;
        $soshObj->setShNo($sh_no);
        $soshObj->setCourierId($courier_id);
        $soshObj->setStatus(1);
        if ($rs1 = $this->getDao('SoShipment')->insert($soshObj)) {
            foreach ($soal_list as $al_id => $soalObj) {
                $soalObj->setShNo($sh_no);
                $soalObj->setStatus(2);
                if (!($rs2 = $this->getDao('SoAllocate')->update($soalObj))) {
                    $success = 0;
                    break;
                }
            }
        } else {
            $success = 0;
        }

        return $success;
    }

    public function dealOrderFulfilmentDispatch($valueArr)
    {
        $rsresult = "";
        $shownotice = 0;

        if ($valueArr["dispatchType"] == 'c') {
            $success_so = $this->changeCourierOnDispatch($valueArr["checkSoNo"], $valueArr["postCourierId"], $valueArr["postCourier"], $valueArr["db_time"]);
            return $success_so;
        } else {
            $u_where["modify_on <="] = date("Y-m-d H:i:s");
            $r_where["soal.status"] = 2;
            if ($valueArr["dispatchType"] != 'r') {
                $r_where["so.hold_status"] = "0";
                $r_where["so.refund_status"] = "0";
            }
            $r_option["limit"] = -1;
            $r_option["shlist"] = array_keys($valueArr["checkSoNo"]);
            $rlist = $this->getDao('SoAllocate')->getInSoList($r_where, $r_option);
            $update_sh = [];
            foreach ($rlist as $obj) {
                $sh_no = $obj->getShNo();
                $line_no = $obj->getLineNo();
                $item_sku = $obj->getItemSku();
                $al_id = $obj->getId();
                $update_sh[$sh_no][$al_id] = $obj;
            }

            if ($update_sh) {
                foreach ($update_sh as $sh_no => $soal_list) {
                    $error = "";
                    $success = 1;
                    $this->getDao('So')->db->trans_start();
                    $sosh_obj = $this->getDao('SoShipment')->get(["sh_no" => $sh_no]);

                    if ($valueArr["dispatchType"] == 'r') {
                        foreach ($soal_list as $al_id => $soal_obj) {
                            $so_no = $soal_obj->getSoNo();
                            $soid_where["so_no"] = $so_no;
                            $soid_where["line_no"] = $soal_obj->getLineNo();
                            $soid_where["item_sku"] = $soal_obj->getItemSku();

                            $cur_u_where = isset($soid_u_where[$soid_where["so_no"]][$soid_where["line_no"]][$soid_where["item_sku"]]) ? ["modify_on <=" => $soid_u_where[$soid_where["so_no"]][$soid_where["line_no"]][$soid_where["item_sku"]]] : $u_where;
                            if ($soid_obj = $this->getDao('SoItemDetail')->get($soid_where)) {
                                $soid_obj->setOutstandingQty($soid_obj->getQty());
                                if ($this->getDao('SoItemDetail')->update($soid_obj, $cur_u_where)){
                                    $this->updateIofOutstandingQtyBySoid($so_no, $soal_obj->getLineNo(), $soal_obj->getItemSku(), $soid_obj);
                                }

                                if ($this->getDao('SoAllocate')->delete($soal_obj)) {
                                    $soid_u_where[$soid_where["so_no"]][$soid_where["line_no"]][$soid_where["item_sku"]] = date("Y-m-d H:i:s");
                                }
                            } else {
                                $success = 0;
                                $error = __LINE__ . "[" . ($soid_obj ? 1 : 0) . "]" . $this->db->_error_message();
                                break;
                            }
                        }
                        if ($success) {
                            $update_status = false;
                            $status = 5;
                            $so_obj = $this->getDao('So')->get(["so_no" => $so_no]);
                            if ($this->getDao('SoAllocate')->getNumRows(["so_no" => $so_no])) {
                                if ($so_obj->getStatus() <> 4) {
                                    $update_status = true;
                                    $status = 4;
                                }
                                $so_obj->setStatus("4");
                            } else {
                                if ($so_obj->getStatus() <> 3) {
                                    $update_status = true;
                                    $status = 3;
                                }
                                $so_obj->setStatus("3");
                            }


                            if (!(($rs1 = $this->getDao('So')->update($so_obj)) && ($rs2 = $this->getDao('SoShipment')->delete($sosh_obj)))) {
                                $success = 0;
                                $error = __LINE__ . "[" . ($rs1 ? 1 : 0) . ($rs2 ? 1 : 0) . "]" . $this->db->_error_message();
                            }
                            if ($update_status) {
                                $this->updateIofStatusBySo($so_no, $status);
                            }
                        }
                    } else {
                        $sosh_obj->setStatus("2");
                        $sosh_obj->setTrackingNo($_POST["tracking"][$sh_no]);
                        if ($this->getDao('SoShipment')->update($sosh_obj)) {
                            foreach ($soal_list as $al_id => $soal_obj) {
                                $soal_obj->setStatus("3");
                                if (!$this->getDao('SoAllocate')->update($soal_obj)) {
                                    $success = 0;
                                    $error = __LINE__ . " " . $this->db->_error_message();
                                    break;
                                }
                            }
                        } else {
                            $success = 0;
                            $error = __LINE__ . " " . $this->db->_error_message();
                        }
                        if ($success) {
                            $so_obj = $this->getDao('So')->get(["so_no" => $soal_obj->getSoNo()]);
                            if ($this->getDao('SoAllocate')->getNumRows(["so_no" => $so_no, "status" => 1]) == 0) {
                                if (!$this->updateCompleteOrder($so_obj, 0)) {
                                    $success = 0;
                                    $error = __LINE__ . " " . $this->db->_error_message();
                                }

                                // todo
                                if ($so_obj->getBizType() == 'SPECIAL') {
                                    $special_orders[] = $so_obj->getSoNo();
                                }
                            }

                            if (substr($so_obj->getPlatformId(), 0, 2) != "AM" && substr($so_obj->getPlatformId(), 0, 2) != "TS" && $so_obj->getBizType() != "SPECIAL") {
                                $this->fireDispatch($so_obj, $sh_no);
                            }
                        }
                    }
                    if (!$success) {
                        $this->getDao('So')->db->trans_rollback();
                        $shownotice = 1;
                    }
                    $rsresult .= "{$sh_no} -> {$success} " . ($success ? "" : "(error:{$error})") . "\\n";
                    $this->getDao('So')->db->trans_complete();
                }
                if ($special_orders) {
                    foreach ($special_orders as $key => $so_no) {
                        $so_w_reason = $this->getDao('So')->getSoWithReason(['so.so_no' => $so_no], ['limit' => 1]);

                        if ($so_w_reason->getReasonId() == '34') {
                            $aps_direct_order[] = $so_w_reason->getSoNo();
                        }
                    }

                    $aps_direct_orders = implode(',', $aps_direct_order);
                    $where = "where so.so_no in (" . $aps_direct_orders . ")";
                    $content = $this->getDao('So')->getApsDirectOrderCsv($where);

                    $phpmail = new PHPMailer;

                    $phpmail->IsSMTP();
                    $phpmail->From = "VB APS ORDER ALERT <do_not_reply@valuebasket.com>";
                    // $phpmail->AddAddress("bd.platformteam@eservicesgroup.net");
                    $phpmail->AddAddress("brave.liu@eservicesgroup.com");

                    $phpmail->Subject = " DIRECT APS ORDERS";
                    $phpmail->IsHTML(false);
                    $phpmail->Body = "Attached: DIRECT APS ORDERS.";
                    $phpmail->AddStringAttachment($content, "direct_aps_info.csv");
                    $result = $phpmail->Send();

                }
            }
        }

        if ($shownotice) {
            $_SESSION["NOTICE"] = $rsresult;
        }
    }

    public function changeCourierOnDispatch($checkSoNo, $postCourierId, $postCourier, $db_time)
    {
        $success_so = [];
        $u_where["modify_on <="] = $db_time;
        $u_where["status"] = 1;
        foreach ($checkSoNo as $sh_no => $so_no) {
            $error = "";
            $success = 1;
            $u_where["sh_no"] = $sh_no;
            if ($sh_obj = $this->getDao('SoShipment')->get($u_where)) {
                $so_obj = $this->getDao('So')->get(["so_no" => $so_no]);
                $courier_id = $this->checkCourier($postCourierId, $postCourier[$sh_no], $so_obj);
                $sh_obj->setCourierId($courier_id);
                $sh_obj->setTrackingNo("");
                if ($this->getDao('SoShipment')->update($sh_obj) === FALSE) {
                    $success = 0;
                }
            } else {
                $success = 0;
            }
            if ($success) {
                $success_so[] = $so_no;
            }
        }
        return $success_so;
    }

    public function addOrderNote($so_no, $notes)
    {
        $obj = $this->getDao('OrderNotes')->get();
        $obj->setSoNo($so_no);
        $obj->setType('O');
        $obj->setNote($notes);
        return $this->getDao('OrderNotes')->insert($obj);
    }

}

