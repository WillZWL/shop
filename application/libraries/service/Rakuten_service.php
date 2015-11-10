<?php
defined('BASEPATH') OR exit('No direct script access allowed');

DEFINE ('PLATFORM_TYPE', 'RAKUTEN');


/*
    =================================================
    IMPORTANT READ ME!
    =================================================
    Setting $this->test = TRUE or in admindev, it will log in to Rakuten's DEMOSHOP
        -> look at function get_login_info().

    Every new Public function, set $this->platform_id, $this->country_id.
    This is for calling Rakuten API and debugging / error emails.

    Root ticket SBF #4564
    =================================================
*/

class Rakuten_service
{
    private $notification_email = "itsupport@eservicesgroup.net";

    public function __construct()
    {
        include_once(APPPATH . "libraries/service/Order_integration_service.php");
        $this->set_order_integration(new Order_integration_service());
        include_once(APPPATH . "libraries/service/Client_integration_service.php");
        $this->set_client_integration(new Client_integration_service());

        include_once(APPPATH . "libraries/service/Http_info_service.php");
        $this->set_http_info(new Http_info_service());
        include_once(APPPATH . "libraries/service/Schedule_job_service.php");
        $this->set_sj_srv(new Schedule_job_service());
        include_once(APPPATH . "libraries/dao/So_item_dao.php");
        $this->set_so_item_dao(new So_item_dao());
        include_once(APPPATH . "libraries/dao/So_dao.php");
        $this->set_so_dao(new So_dao());
        include_once(APPPATH . "libraries/service/So_service.php");
        $this->set_so_srv(new So_service());
        include_once(APPPATH . "libraries/service/Product_service.php");
        $this->set_product_srv(new Product_service());
        include_once(APPPATH . 'libraries/service/Price_service.php');
        $this->set_price_srv(New Price_service());
        include_once(APPPATH . "libraries/dao/Category_mapping_dao.php");
        $this->set_cat_mapping_dao(new Category_mapping_dao());

        include_once(APPPATH . "libraries/service/Context_config_service.php");
        $this->set_config_srv(new Context_config_service());
    }

    public function set_order_integration($value)
    {
        $this->order_integration = $value;
    }

    public function set_client_integration($value)
    {
        $this->client_integration = $value;
    }

    public function set_http_info($value)
    {
        $this->http_info = $value;
    }

    public function set_sj_srv($value)
    {
        $this->sj_srv = $value;
    }

    public function set_so_item_dao(Base_dao $dao)
    {
        $this->so_item_dao = $dao;
    }

    public function set_so_dao(Base_dao $value)
    {
        $this->so_dao = $value;
    }

    public function set_so_srv($value)
    {
        $this->so_srv = $value;
    }

    public function set_product_srv($value)
    {
        $this->product_srv = $value;
    }

    public function set_price_srv($value)
    {
        $this->price_srv = $value;
    }

    public function set_cat_mapping_dao(Base_dao $srv)
    {
        $this->cat_mapping_dao = $srv;
    }

    public function set_config_srv(Base_service $srv)
    {
        $this->config_srv = $srv;
    }

    public function get_order_integration()
    {
        return $this->order_integration;
    }

    public function get_client_integration()
    {
        return $this->client_integration;
    }

    public function get_so_item_dao()
    {
        return $this->so_item_dao;
    }

    public function get_config_srv()
    {
        return $this->config_srv;
    }

    public function import_orders_new($country_id = "ES", $orderno = "", $test = FALSE)
    {
        ini_set("default_charset", "UTF-8");
        ob_start();
        $this->test = $test;
        $this->isdev = strpos($_SERVER["HTTP_HOST"], "admindev") === false ? FALSE : TRUE;

        /////// comment out before LIVE; no data in demoshop for testing
        // $this->demoshop = FALSE;
        $this->process = "import_orders_new";
        $this->country_id = strtoupper($country_id);
        $this->platform_id = "RAKU" . strtoupper($country_id);
        $this->http_name = 'RAKUTEN_VALUEBASKET';
        $website = $this->website = "valuebasket";

        $so_dao = $this->get_so_dao();
        $shoppercomment = array();

        switch ($country_id) {
            case 'ES':
                $currency_id = "EUR";
                $paymentgatewayid = "raku_es";  # refer to payment_gateway db
                break;

            default:
                $currency_id = "EUR";
                $paymentgatewayid = "raku_es";
                break;
        }

        if ($test === TRUE) {
            /* this goes to test function with pre-set values */
            /* ONLY RUN ON DEV!!!! */
        } else {
            try {
                $client_intergration_srv = new Client_integration_service();
                $client_intergration_srv->set_platform_id($this->platform_id);
                $client_intergration_srv->set_website(strtoupper($website));

                $order_integration_srv = new Order_integration_service();
                $order_integration_srv->set_notification_email($this->notification_email);

                $order_integration_srv->set_biz_type('RAKUTEN');
                $order_integration_srv->set_platform_id($this->platform_id);
                $order_integration_srv->set_website(strtoupper($website));

                /**
                 * We must always call get_batch_id before creating any orders using OIS
                 * Everytime import_orders run, this portion checks if batch_id exists, else it will create one
                 **/
                $now_time = $this->now_time = date("YmdHis", mktime());
                $batch_remark = "Rakuten_" . $website . "_" . $now_time;
                $batch_id = $order_integration_srv->get_batch_id($batch_remark, $this->platform_id, $website);
                $order_integration_srv->set_batch_id($batch_id);

                if ($batch_id) {
                    echo "<pre>---[[ DEBUG: START BATCH <$batch_id> ]]---</pre>";

                    { // if too many orders caused API to die, pass in page numbers from browser
                        $page = 0;
                        if (isset($_GET["page"]))
                            $page = $_GET["page"];
                    }

                    if (($mainorderinfo = $this->get_rakuten_orders_api_new("NotShipped", $page)) !== FALSE) {
                        // Run through each order to get item details
                        $ordersarr = $this->process_order_data_new($mainorderinfo);

                        $this->process = "import_orders";   # reset for error emails

                        if (empty($ordersarr)) {
                            echo "<pre>---[[ DEBUG: NO ORDERS AVAILABLE ]]---</pre>";
                            if ($this->test === false && $this->isdev === false)
                                $this->get_sj_srv()->update_last_process_time($this->http_name, $now_time);

                            # this line updates batch status and end time
                            $order_integration_srv->commit_platform_batch();
                            exit();
                        }

                        // generates a CSV report for imported && notSHipped orders
                        {
                            $csv = $this->convert_order_data_to_csv($ordersarr);
                            $csvheader = $this->get_order_data_header();

                            $debug_content = ob_get_contents();
                            ob_end_clean();

                            $csv_data = $csvheader . $csv;
                            $filename = "{$this->platform_id}_notShipped_{$this->now_time}.csv";
                            if ($fp = @fopen("/var/data/valuebasket.com/orders/rakuten/{$this->country_id}/$filename", 'w')) {
                                @fwrite($fp, $csv_data);
                                @fclose($fp);
                            }

                            if (isset($_GET["getfile"])) {
                                // echo out file for saving on front end
                                header("Content-Type: text/csv");
                                header("Content-Disposition: attachment; filename=$filename");
                                echo $csv_data;
                                die();
                            }

                            $email_list = "melody.yu@eservicesgroup.com,celine@eservicesgroup.com,romuald@eservicesgroup.com,ping@eservicesgroup.com,jerrylim@eservicesgroup.com";
                            $this->send_report_email($csv_data, "notShipped Report", $filename, $email_list);
                            echo $debug_content;
                        }


                        echo "<pre>DEBUG TOTAL ORDERS COUNT: " . count($ordersarr) . "</pre> ";
                        $orderloop = 0;
                        foreach ($ordersarr as $key => $order) {
                            // echo "<pre>";  echo "order";
                            // var_dump($ordersarr); die();
                            $order_no = $order["orderNumber"];                // 0014232-140725-2716923314

                            $orderstatus = $order["orderStatus"];
                            $buyername = $order["buyerName"];
                            $buyeremail = $order["buyerEmail"];

                            # orderTotal = itemTotal + shippingfee (itemTotal = sum(unitPrice*qty - discount))
                            $ordertotal = $order["orderTotal"];
                            $orderdate = date("Y-m-d H:i:s", strtotime($order["orderDate"]));

                            $shipping = $order["shipping"];
                            $orderpackageid = $shipping["orderPackageId"];  //83702f8c-c373-4cab-aeae-31fa07e1801f
                            $ship_status = $shipping["shippingStatus"];
                            $trackingnumber = $shipping["trackingNumber"];
                            $ship_name = $shipping["shippingAddress"]["name"];
                            $ship_tel = $shipping["shippingAddress"]["phoneNumber"];
                            $ship_postcode = $shipping["shippingAddress"]["postalCode"];
                            $ship_countryid = $shipping["shippingAddress"]["countryCode"];
                            $ship_state = $shipping["shippingAddress"]["stateCode"];
                            $ship_city = $shipping["shippingAddress"]["city"];
                            $ship_add = $shipping["shippingAddress"]["address1"];
                            if ($shipping["shippingAddress"]["address2"])
                                $ship_add .= "||" . $shipping["shippingAddress"]["address2"];
                            if ($shipping["shippingAddress"]["address3"])
                                $ship_add .= "||" . $shipping["shippingAddress"]["address3"];
                            if ($shipping["shippingAddress"]["address3"])
                                $ship_add .= "||" . $shipping["shippingAddress"]["address3"];
                            if ($shipping["shippingAddress"]["address4"])
                                $ship_add .= "||" . $shipping["shippingAddress"]["address4"];
                            if ($shipping["shippingAddress"]["address5"])
                                $ship_add .= "||" . $shipping["shippingAddress"]["address5"];


                            $payment = $order["payment"];
                            $pay_id = $payment["orderPaymentId"];  // b77d7a5e-71f6-43f5-bf16-6384d4d84423
                            $pay_mtd = $payment["paymentMethod"];
                            $pay_status = $payment["paymentStatus"];
                            $pay_amt = $payment["payAmount"];
                            $pay_pointamt = $payment["pointAmount"];
                            $pay_date = date("Y-m-d H:i:s", strtotime($payment["paymentDate"]));

                            $shoppercomment[$order_no] = trim($order["shopperComment"], '{}');


                            echo "<hr></hr><pre>DEBUG LOOP $key: PROCESSING - ORDERNO: $order_no. || PACKAGEID: $orderpackageid</pre> ";
                            /** Set all info into CIS and OIS to automate process of writing to db **/
                            /* ============= set info into client_integration_service ============= */
                            $client_intergration_srv->set_ext_client_id($buyeremail);
                            $client_intergration_srv->set_client_name($buyername);
                            if ($ship_tel) $client_intergration_srv->set_client_tel($ship_tel);
                            $client_intergration_srv->set_client_mobile("");
                            $client_intergration_srv->set_client_email($buyeremail);
                            $client_intergration_srv->set_client_address($ship_countryid, $ship_postcode, $ship_add, "", "", $ship_city, $ship_state);
                            $client_intergration_srv->set_del_name($ship_name);
                            if ($ship_tel) $client_intergration_srv->set_del_tel($ship_tel);
                            $client_intergration_srv->set_del_mobile("");
                            $client_intergration_srv->set_del_address($ship_countryid, $ship_postcode, $ship_add, "", "", $ship_city, $ship_state);
                            $password = (time() + $orderloop++);      # randomly generate passsword
                            $client_intergration_srv->set_password($password);

                            /* REQUIRED: create client info in a temp place */
                            $client_vo = $client_intergration_srv->create_client($batch_id);

                            /* ============= set info into order_integration_service ============= */
                            $order_integration_srv->set_interface_client_vo($client_vo);
                            $order_integration_srv->set_bill_name($buyername);
                            $order_integration_srv->set_bill_address($ship_countryid, $ship_postcode, $ship_add, $ship_city, $ship_state);
                            $order_integration_srv->set_payer_email($buyeremail);
                            $order_integration_srv->set_create_order_time($orderdate);
                            $order_integration_srv->set_pay_date($pay_date);
                            $order_integration_srv->set_currency_id($currency_id);
                            $order_integration_srv->set_txn_id($orderpackageid);
                            $order_integration_srv->set_payment_gateway_id($paymentgatewayid);
                            $order_integration_srv->set_pay_to_account('rakuten');
                            $order_integration_srv->set_payer_ref($pay_id);
                            $order_integration_srv->set_order_as_paid(TRUE);    # TRUE = order has been paid

                            # TEMPORARY NEED CREDIT CHECK until encoding problem solved. This will mark so.status = 2
                            $order_integration_srv->need_credit_check(FALSE);

                            if ($items = $order["orderItem"]) {
                                echo "<pre>DEBUG TOTAL ITEMS COUNT: " . count($items) . "</pre> ";
                                foreach ($items as $ikey => $item) {
                                    $prod_name = $item["name"]["value"];
                                    $prod_sku = $item["baseSKU"];
                                    $ext_item_cd = $item["orderItemId"];
                                    $qty = $item["quantity"];
                                    $unitprice = number_format(($item["unitPrice"] - ($item["discount"] / $qty)), 2, '.', '');

                                    # itemTotal = sum(unitPrice*quantity - discount)
                                    $itemtotal = number_format($item["itemTotal"], 2, '.', '');

                                    $order_integration_srv->set_qty($qty);
                                    $order_integration_srv->set_unit_price($unitprice);
                                    $order_integration_srv->set_amount($ordertotal);
                                    $order_integration_srv->set_platform_order_id($order_no);
                                    $order_integration_srv->set_delivery_charge(0);
                                    $order_integration_srv->set_weight(1);
                                    $order_integration_srv->set_prod_sku($prod_sku);
                                    $order_integration_srv->set_prod_name($prod_name);
                                    $order_integration_srv->set_ext_item_cd($ext_item_cd);

                                    echo "<pre>DEBUG ITEMLOOP $ikey: $prod_name -- sku <$prod_sku | $ext_item_cd>, qty <$qty>, unitprice <$unitprice> </pre> ";

                                    /* REQUIRED: create so-related info in a temp place */
                                    $order_integration_srv->create_platform_batch();
                                }
                            }
                        }
                    }
                }

                /* REQUIRED: insert client info and SO details into db */
                $success_so_list = $order_integration_srv->commit_platform_batch();

                // update time in schedule_job db
                if ($this->test === false && $this->isdev === false)
                    $this->get_sj_srv()->update_last_process_time($this->http_name, $now_time);

                if ($success_so_list) {
                    foreach ($success_so_list as $key => $v) {
                        $so_no = $v["so_no"];
                        $platform_order_id = $v["platform_order_id"];
                        $ordernotes = $shoppercomment[$platform_order_id];

                        for (; ;) {
                            $so_obj = null;
                            $so_obj = $so_dao->get(array("so_no" => $so_no));

                            if ($so_obj == null) {
                                // THIS IS A HACK!! Sometimes die when too many loops
                                sleep(1);
                                echo "falling asleep $s, so_no $so_no<br>";
                                // $s++;
                            } else
                                break;
                        }

                        if ($so_obj) {
                            $order_integration_srv->insert_so_priority_score($so_no, '1110', $this->platform_id, $website);

                            if ($ordernotes) {
                                $order_integration_srv->insert_order_notes($so_no, $ordernotes, $this->platform_id, $website);
                                echo "<pre>---[[ DEBUG: inserting order notes // so_no: $so_no // db platform_order_id: $platform_order_id ]]---</pre>";
                            }

                        }
                    }
                }
                echo "<pre>DEBUG WHOLE LOOP COMPLETED</pre><hr></hr>";
            } catch (Exception $e) {
                $error_msg = "rakuten_service line " . __LINE__ . " {$this->process} PLEASE RERUN IMPORT_ORDERS JOB. \nCaught exception: \n nl2br($e->getMessage()) ";
                echo $error_msg;
                $this->send_notification_email("DE", $error_msg);
                return FALSE;
            }
        }
    }

    public function get_so_dao()
    {
        return $this->so_dao;
    }

    private function get_rakuten_orders_api_new($orderstatus = "NotShipped", $pageindex = 0, $maxresult = 100)
    {
        /*
            This function will get list of orders and append each order details (i.e. items info) to each order.
            It will also save a copy of orders w timestamp on server

            - pageindex     = index of page requested, default is 0 (first page)
            - maxresult     = 0 - 100 (rakuten default is 100)
            - orderStatus   = Unfixed / AwaitingPayment / ProcessingPayment / NotShipped
                                / AwaitingCompletion (payment & shipment success; will change to Complete 7 days aft shipped)
                                / Complete  (cannot be cancelled or returned)
                                / Cancelled / CancelledAndRefund

            Passing in $_GET["nowtime"] or $_GET["days"] in URL can shift the date range to grab orders from
        */

        $response = $orders = array();
        $this->process = "get_rakuten_orders_api";
        $last_cron_time = $this->get_sj_srv()->get_last_process_time($this->http_name); # get the schedule for last cron job ran
        $now_time = $this->now_time;
        if (!$last_cron_time) {
            $this->send_notification_email("GT"); # get_cron_time
        } else {
            if (strtotime($now_time) - strtotime($last_cron_time) > 2592000) # exceed 30 days
            {
                echo "<pre>DEBUG: EXCEED TIME RANGE FROM LAST CRON";
                $this->send_notification_email("TR"); # time_range
            } else {
                if (isset($_GET["nowtime"])) {
                    $now_time = $_GET["nowtime"];
                }

                $days = 7;
                if (isset($_GET["days"])) {
                    $days = $_GET["days"];
                }
                $last_time = date("Y-m-d H:i:s", (strtotime($now_time) - $days * 24 * 60 * 60)); # minus $days to catch any missing previously

                $last_rakuten_time = date("Y-m-d\T00:00:00.000\Z", strtotime($last_time));
                $now_rakuten_time = date("Y-m-d\T23:59:59.000\Z", strtotime($now_time));
                if (strtotime($last_time) >= strtotime($now_time)) {
                    $error_msg = "rakuten_service line " . __LINE__ . " {$this->process} last_rakuten_time <$last_rakuten_time> is greater or equal now_rakuten_time <$now_rakuten_time> ";
                    $this->send_notification_email("DE", $error_msg);
                    echo $error_msg;
                    return FALSE;
                }

                $apitype = "/order/list";
                $requestarr = array(
                    "createdAfter" => $last_rakuten_time,
                    "createdBefore" => $now_rakuten_time,
                    "orderStatus" => $orderstatus,
                    "maxResultsPerPage" => $maxresult,
                    "pageIndex" => $pageindex
                );


                if ($response = $this->call_rakuten_api("get", $apitype, $requestarr)) {
                    if ($pageindex == 0) {
                        $orders = $response["orders"];
                        # Rakuten has a max of 100 orders per request, so loop according to totalCount
                        $oloop = 1;
                        $totalcount = $response["totalCount"];
                        if ($totalcount == 0) {
                            return $orders;
                        } else {
                            $ordersdisplayed = count($response["orders"]);  # count actual orders array
                            if ($ordersdisplayed > 0)
                                $ceil = ceil($totalcount / $ordersdisplayed);
                            else {
                                $error_msg = "rakuten_service line " . __LINE__ . " {$this->process} Unable to count Rakuten's total orders @ $now_time.";
                                $this->send_notification_email("DE", $error_msg);
                                return FALSE;
                            }
                            for ($oloop = 1; $oloop < $ceil; $oloop++) {
                                set_time_limit(120);
                                // sleep(5);
                                # CONSOLIDATE: append orders from pageIndex > 0 (aft page 1)
                                if ($this->isdev === false)
                                    $orders = array_merge($orders, $this->get_rakuten_orders_api_new($orderstatus, $oloop));
                            }

                            return $orders;
                        }
                    } else {
                        # if this is not the first page, just return the results so that can consolidate
                        return $response["orders"];
                    }
                }
            }
        }

        return FALSE;
    }

    public function get_sj_srv()
    {
        return $this->sj_srv;
    }

    private function send_notification_email($pending_action, $error_msg = "")
    {
        $website = 'valuebasket';
        $platform_id = $this->platform_id;
        if ($this->demoshop == "") {
            if ($this->demoshop !== FALSE) {
                $this->demoshop = TRUE;
                if ($this->test == "") $this->test = FALSE;

                if ($this->test === false && $this->isdev === false)
                    $this->demoshop = FALSE;
            }
        }
        if ($this->demoshop)
            $isdemo = "\nOCCURRED IN DEMOSHOP, IGNORE MESSAGE.";

        if ($platform_id) {
            switch ($pending_action) {
                case "AF":
                    $message = $error_msg;
                    $title = "[$platform_id - $website] Authentication problems - AUTHENTICATION_FAILURE";
                    break;
                case "HE":
                    $message = $error_msg;
                    $title = "[$platform_id - $website] http_info problems - HTTP_INFO_ERROR";
                    break;
                case "GT":
                    $message = "Please note errors updating db schedule_job on last_process_on field. Please check.";
                    $title = "[$platform_id - $website] Retrieve $platform_id Order problems - GET_CRON_TIME";
                    break;
                case "TR":
                    $message = "Please note that the time range is set to 30 days.";
                    $title = "[$platform_id - $website] Retrieve $platform_id Order problems - TIME_RANGE";
                    break;
                case "DE":
                    $message = $error_msg;
                    $title = "[$platform_id - $website] Retrieve $platform_id Order problems - DATA_ERROR";
                    break;
                case "SKU":
                    $message = $error_msg;
                    $title = "[$platform_id - $website] Retrieve $platform_id Order problems - EMPTY_SKU";
                    break;
                case "UE":
                    $message = $error_msg;
                    $title = "[$platform_id - $website] Retrieve $platform_id Order problems - UPDATE_ORDER_ERROR";
                    break;
                case "US":
                    $message = $error_msg;
                    $title = "[$platform_id - $website] Update shipment status problems - UPDATE_SHIPMENT_STATUS_ERROR";
                    $this->notification_email .= ",rakutenalert@valuebasket.com";
                    break;
                case "AI":
                    $message = $error_msg;
                    $title = "[$platform_id - $website] Listing new item problems - ADD_ITEMS_ERROR";
                    $this->notification_email .= ",rakutenalert@valuebasket.com";
                    break;
                case "UI":
                    $message = $error_msg;
                    $title = "[$platform_id - $website] Update item problems - UPDATE_ITEMS_ERROR";
                    break;
                case "SI":
                    $message = $error_msg;
                    $title = "[$platform_id - $website] Set Image problems - SET_IMAGE_ERROR";
                    break;
                case "RRE":
                    $message = $error_msg;
                    $title = "[$platform_id - $website] Problem getting response from RAKUTEN - RAKUTEN_RESPONSE_ERROR";
                    $this->notification_email .= ",rakutenalert@valuebasket.com";
                    break;
                case "DS":
                    $message = $error_msg;
                    $title = "[$platform_id - $website] Automatic Delisting of SKUs Below 5% margin - DELIST_SKU";
                    $this->notification_email .= ",serene.chung@eservicesgroup.com,romuald@eservicesgroup.com, celine@eservicesgroup.com, jerrylim@eservicesgroup.com";
                    break;

            }

            $message .= $isdemo;
            mail($this->notification_email, $title, $message);

        } else {
            echo "DEBUG ERROR: rakuten_service line_no: " . __LINE__ . ", please input platform_id";
        }

    }

    private function call_rakuten_api($requestmethod, $apitype, $requestarr, $echodebug = false, $email_errorcontent = true)
    {

        /* ========================================================================================================
            *   $requestmethod = get / post
            *   $apitype = Rakuten API string, e.g. "/inventory/get", "/order/get"
            *   $email_errorcontent = Some APIs will have content error if not yet exist on Rakuten, e.g. add_item_images(),
                pass in FALSE if want to hush email
            *   $requestarr = request parameters in array format

            if requestmethod is a GET, you don't need to pass in shopurl and marketplace identifier in $requestarr.
            HOWEVER, if requestmenthod is a POST, you need to create your own shopurl and marketplace identifier
            because it may be embedded several layers down in json (refer to function process_add_item() )
            ========================================================================================================
        */
        $contentjson = array();
        if (!$this->country_id) {
            $this->error_msg = $error_msg = "rakuten_service line " . __LINE__ . " {$this->process} Missing country_id. ";
            $this->send_notification_email("DE", $error_msg);   # RAKUTEN_RESPONSE_ERROR

            if ($echodebug)
                echo "<pre>$error_msg</pre>";

            return FALSE;
        }

        if (empty($requestarr) || is_array($requestarr) === FALSE) {
            $this->error_msg = $error_msg = "rakuten_service line " . __LINE__ . " {$this->process} Request parameter is missing or wrong type. ";
            $this->send_notification_email("DE", $error_msg);   # RAKUTEN_RESPONSE_ERROR
            if ($echodebug)
                echo "<pre>$error_msg</pre>";

            return FALSE;
        }

        // get login info from db according to platform_id
        if ($login = $this->get_login_info()) {
            $shopurl = $login["shopurl"];
            $authkey = $login["password"];
            $base_api_url = $login["base_api_url"];
            $identifier = strtolower($this->country_id);

            if ($requestmethod == "get") {
                foreach ($requestarr as $key => $value) {
                    $fields_string .= "&$key=" . urlencode($value);
                }

                $ch = curl_init();
                // mb_internal_encoding("UTF-8");
                // mb_http_output( "UTF-8" );
                $requeststr = "?marketplaceIdentifier={$identifier}&shopURL={$shopurl}{$fields_string}";

                curl_setopt($ch, CURLOPT_URL, $base_api_url . $apitype . $requeststr);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                // curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    "Authorization: ESA $authkey",
                    "Accept: application/json",
                    "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7",
                    "Content-Type: application/json; charset=\"utf-8\""
                ));

                //execute post
                $content = curl_exec($ch);
                $error = curl_error($ch);
                $errno = curl_errno($ch);
                $curl_errorinfo = $this->format_arr_to_str(curl_getinfo($ch));
            } elseif ($requestmethod == "post") {
                $fields_string = json_encode($requestarr);

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $base_api_url . $apitype);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: ESA $authkey", "Accept: application/json"));
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

                //execute post
                $content = curl_exec($ch);
                $error = curl_error($ch);
                $errno = curl_errno($ch);
                $curl_errorinfo = $this->format_arr_to_str(curl_getinfo($ch));
                //  echo "<pre>";

                //var_dump($base_api_url." " .$apitype." " .$authkey." ". $fields_string);
                //var_dump($content);
                //  die();
                curl_close($ch);
            } else {

                $this->error_msg = $error_msg = "rakuten_service line " . __LINE__ . " Procces: {$this->process} - cannot get response.\nWrong requestmethod <$requestmethod> \nfields_string: $fields_string";
                $this->send_notification_email("RRE", $error_msg);  # RAKUTEN_RESPONSE_ERROR
                if ($echodebug)
                    echo "<pre>$error_msg</pre>";
                return FALSE;
            }

            // handle curl return info
            if ($content === false) {
                $this->error_msg = $error_msg = "rakuten_service line " . __LINE__ . " Procces: {$this->process} - cannot get response.\nCurl err: [errno: {$errno}] $error, \nCurl header: $curl_errorinfo. \n fields_string: $fields_string";
                $this->send_notification_email("RRE", $error_msg);  # RAKUTEN_RESPONSE_ERROR
                if ($echodebug)
                    echo "<pre>$error_msg</pre>";

                return FALSE;
            } else {
                if ($apitype == '/order/get') {
                    $contentjson = json_decode(utf8_encode($content), true);

                    return $contentjson;
                }

                $curlinforstr = print_r($curl_errorinfo, true);
                $this->request_info = "fields: $fields_string \r\nCurl info: \r\n" . $curlinforstr;

                $contentjson = json_decode($content, true);
                if (!empty($contentjson["errors"])) {
                    $info = "requestId => {$contentjson["requestId"]} \n timeStamp => {$contentjson["timeStamp"]} \n marketplaceIdentifier => $identifier";
                    $this->error_msg = $errorinfo = $this->format_arr_to_str($contentjson["errors"]);
                    $error_msg = "rakuten_service line " . __LINE__ . " Procces: {$this->process} - \r\nRakuten response error msg: $errorinfo \n\nADDITIONAL INFO\n $info \n\nCURL INFO: $curl_errorinfo. \nfields_string: $fields_string";


                    if ($email_errorcontent === TRUE) {
                        # errorCode 901 is for product that doesnt exist, e.g. "shortMessage: baseSku 12114-AA-PK does not exist"
                        if (strpos($errorinfo, "errorCode: 901") === FALSE) {
                            $this->send_notification_email("RRE", $error_msg);  # RAKUTEN_RESPONSE_ERROR
                        }
                    } elseif ($email_errorcontent == 2) {
                        return $contentjson;
                    }

                    if ($echodebug)
                        echo "<pre>$error_msg</pre>";

                    return FALSE;
                } else {
                    // NO ERROR MESSAGES FROM RAKUTEN

                    return $contentjson;
                }
            }
        }
        return FALSE;
    }

    private function get_login_info()
    {
        if ($this->demoshop !== FALSE) {
            $this->demoshop = TRUE;
            if ($this->test == "") $this->test = FALSE;

            if ($this->test === false && $this->isdev === false)
                $this->demoshop = FALSE;
        }

        $login = array();

        if ($this->platform_id) {
            switch ($this->platform_id) {
                case 'RAKUES':
                    $name = "RAKUTEN_VALUEBASKET";
                    break;

                default:
                    $name = "RAKUTEN_VALUEBASKET";
                    break;
            }

            if ($this->demoshop)
                $http_obj = $this->get_http_info()->get(array("name" => 'RAKUTEN_VALUEBASKET', "type" => "D"));     #demo shop login
            // $http_obj = $this->get_http_info()->get(array("name"=>$name, "type"=>"P"));
            else
                $http_obj = $this->get_http_info()->get(array("name" => $name, "type" => "P"));

            if (!$http_obj) {
                $error_msg = "rakuten_service line " . __LINE__ . " Process: {$this->process} http_info cannot be retrieved. DB error: " . $this->get_http_info()->db->_error_message();
                $this->send_notification_email("HE", $error_msg);  #http_info_error
            } else {
                include_once(BASEPATH . "libraries/Encrypt.php");
                $encrypt = new CI_Encrypt();
                $login["base_api_url"] = $http_obj->get_server();  #common base url for all rakuten api
                $login["shopurl"] = $http_obj->get_username();
                $login["password"] = $encrypt->decode($http_obj->get_password());

                //  if($this->demoshop)
                //  echo "<pre>DEBUG: shopurl: {$login["shopurl"]}</pre>";
            }
        } else {
            echo "DEBUG ERROR: rakuten_service line_no " . __LINE__ . ", please input platform_id";
        }

        return $login;
    }

    /* =========================================================================================================================================
        THIS PORTION IS NO LONGER IN USE BECAUSE MAY CAUSE API TO DIE.
        SWITCHING OVER TO function import_orders_new()
    ========================================================================================================================================= */
    // public function import_orders($country_id = "ES", $orderno="", $test = FALSE)
    // {
    //  ini_set("default_charset", "UTF-8");
    //  ob_start();
    //  $this->test = $test;
    //  $this->isdev = strpos($_SERVER["HTTP_HOST"], "admindev")===false?FALSE:TRUE;

    //  /////// comment out before LIVE; no data in demoshop for testing
    //  // $this->isdev = FALSE;
    //  $this->process      = "import_orders";
    //  $this->country_id   = strtoupper($country_id);
    //  $this->platform_id  = "RAKU".strtoupper($country_id);
    //  $this->http_name    = 'RAKUTEN_VALUEBASKET';
    //  $website = $this->website = "valuebasket";

    //  $so_dao = $this->get_so_dao();
    //  $shoppercomment = array();

    //  switch ($country_id)
    //  {
    //      case 'ES':
    //          $currency_id = "EUR";
    //          $paymentgatewayid = "raku_es";  # refer to payment_gateway db
    //          break;

    //      default:
    //          $currency_id = "EUR";
    //          $paymentgatewayid = "raku_es";
    //          break;
    //  }

    //  if ($test === TRUE)
    //  {
    //      /* this goes to test function with pre-set values */
    //      /* ONLY RUN ON DEV!!!! */
    //  }
    //  else
    //  {
    //      try
    //      {
    //          $client_intergration_srv = new Client_integration_service();
    //          $client_intergration_srv->set_platform_id($this->platform_id);
    //          $client_intergration_srv->set_website(strtoupper($website));

    //          $order_integration_srv = new Order_integration_service();

    //          # TEMPORARY SET TO WEBSITE FOR PRICING
    //          $order_integration_srv->set_biz_type('RAKUTEN');
    //          $order_integration_srv->set_platform_id($this->platform_id);
    //          $order_integration_srv->set_website(strtoupper($website));

    //          /**
    //              We must always call get_batch_id before creating any orders using OIS
    //              Everytime import_orders run, this portion checks if batch_id exists, else it will create one
    //          **/
    //          $now_time = $this->now_time = date("YmdHis", mktime());
    //          $batch_remark = "Rakuten_".$website."_".$now_time;
    //          $batch_id = $order_integration_srv->get_batch_id($batch_remark, $this->platform_id, $website);
    //          $order_integration_srv->set_batch_id($batch_id);

    //          if ($batch_id)
    //          {
    //              echo "<pre>---[[ DEBUG: START BATCH <$batch_id> ]]---</pre>";
    //              if( ($ordersarr = $this->get_rakuten_orders_api()) !== FALSE)
    //              {
    //                  $this->process = "import_orders";   # reset for error emails
    //                  // echo "<pre>"; var_dump($ordersarr); die();
    //                  if($ordersarr == 0 )
    //                  {
    //                      echo "<pre>---[[ DEBUG: NO ORDERS AVAILABLE ]]---</pre>";
    //                      if($this->test === false && $this->isdev === false)
    //                          $this->get_sj_srv()->update_last_process_time($this->http_name, $now_time);
    //                      exit();
    //                  }

    //                  echo "<pre>DEBUG TOTAL ORDERS COUNT: ".count($ordersarr)."</pre> ";
    //                  $orderloop = 0;
    //                  foreach ($ordersarr as $key => $order)
    //                  {
    //                      // echo "<pre>";  echo "order";
    //                      // var_dump($ordersarr); die();
    //                      $order_no       = $order["orderNumber"];                // 0014232-140725-2716923314

    //                      $orderstatus    = $order["orderStatus"];
    //                      $buyername      = $order["buyerName"];
    //                      $buyeremail     = $order["buyerEmail"];

    //                      # orderTotal = itemTotal + shippingfee (itemTotal = sum(unitPrice*qty - discount))
    //                      $ordertotal     = $order["orderTotal"];
    //                      $orderdate      = date("Y-m-d H:i:s", strtotime($order["orderDate"]));

    //                      $shipping       = $order["shipping"];
    //                      $orderpackageid = $shipping["orderPackageId"];  //83702f8c-c373-4cab-aeae-31fa07e1801f
    //                      $ship_status    = $shipping["shippingStatus"];
    //                      $trackingnumber = $shipping["trackingNumber"];
    //                      $ship_name      = $shipping["shippingAddress"]["name"];
    //                      $ship_tel       = $shipping["shippingAddress"]["phoneNumber"];
    //                      $ship_postcode  = $shipping["shippingAddress"]["postalCode"];
    //                      $ship_countryid = $shipping["shippingAddress"]["countryCode"];
    //                      $ship_state     = $shipping["shippingAddress"]["stateCode"];
    //                      $ship_city      = $shipping["shippingAddress"]["city"];
    //                      $ship_add       = $shipping["shippingAddress"]["address1"];
    //                      if($shipping["shippingAddress"]["address2"])
    //                          $ship_add   .= "||". $shipping["shippingAddress"]["address2"];
    //                      if($shipping["shippingAddress"]["address3"])
    //                          $ship_add   .= "||". $shipping["shippingAddress"]["address3"];
    //                      if($shipping["shippingAddress"]["address3"])
    //                          $ship_add   .= "||". $shipping["shippingAddress"]["address3"];
    //                      if($shipping["shippingAddress"]["address4"])
    //                          $ship_add   .= "||". $shipping["shippingAddress"]["address4"];
    //                      if($shipping["shippingAddress"]["address5"])
    //                          $ship_add   .= "||". $shipping["shippingAddress"]["address5"];


    //                      $payment        = $order["payment"];
    //                      $pay_id         = $payment["orderPaymentId"];  // b77d7a5e-71f6-43f5-bf16-6384d4d84423
    //                      $pay_mtd        = $payment["paymentMethod"];
    //                      $pay_status     = $payment["paymentStatus"];
    //                      $pay_amt        = $payment["payAmount"];
    //                      $pay_pointamt   = $payment["pointAmount"];
    //                      $pay_date       = date("Y-m-d H:i:s", strtotime($payment["paymentDate"]));

    //                      $shoppercomment[$order_no] = trim($order["shopperComment"], '{}');

    //                      $where["biz_type"] = "RAKUTEN";
    //                      $where["platform_id"] = $this->platform_id;
    //                      $so_obj = $so_dao->get_so_by_txn_id($order_no, $where); # CHECK TO SEE IF ORDER CREATED PREVIOUSLY

    //                      if ($so_obj)
    //                      {
    //                          echo "<pre>DEBUG LOOP $key: EXISTING ORDER - ORDERNO: $order_no will be ignored.</pre> ";
    //                          continue;
    //                      }

    //                      echo "<hr></hr><pre>DEBUG LOOP $key: PROCESSING - ORDERNO: $order_no.</pre> ";
    //                      /** Set all info into CIS and OIS to automate process of writing to db **/
    //                      /* ============= set xml info into client_integration_service ============= */
    //                      $client_intergration_srv->set_ext_client_id($buyeremail);
    //                      $client_intergration_srv->set_client_name($buyername);
    //                      if($ship_tel)   $client_intergration_srv->set_client_tel($ship_tel);
    //                      $client_intergration_srv->set_client_mobile("");
    //                      $client_intergration_srv->set_client_email($buyeremail);
    //                      $client_intergration_srv->set_client_address($ship_countryid, $ship_postcode, $ship_add, "", "", $ship_city, $ship_state);
    //                      $client_intergration_srv->set_del_name($ship_name);
    //                      if($ship_tel)   $client_intergration_srv->set_del_tel($ship_tel);
    //                      $client_intergration_srv->set_del_mobile("");
    //                      $client_intergration_srv->set_del_address($ship_countryid, $ship_postcode, $ship_add, "", "", $ship_city, $ship_state);
    //                      $password = (time()+$orderloop++);      # randomly generate passsword
    //                      $client_intergration_srv->set_password($password);

    //                      /* REQUIRED: create client info in a temp place */
    //                      $client_vo = $client_intergration_srv->create_client($batch_id);

    //                      /* ============= set xml info into order_integration_service ============= */
    //                      $order_integration_srv->set_interface_client_vo($client_vo);
    //                      $order_integration_srv->set_bill_name($buyername);
    //                      $order_integration_srv->set_bill_address($ship_countryid, $ship_postcode, $ship_add, $ship_city, $ship_state);
    //                      $order_integration_srv->set_payer_email($buyeremail);
    //                      $order_integration_srv->set_create_order_time($orderdate);
    //                      $order_integration_srv->set_pay_date($pay_date);
    //                      $order_integration_srv->set_currency_id($currency_id);
    //                      $order_integration_srv->set_txn_id($order_no);
    //                      $order_integration_srv->set_payment_gateway_id($paymentgatewayid);
    //                      $order_integration_srv->set_pay_to_account('rakuten');
    //                      $order_integration_srv->set_payer_ref($pay_id);
    //                      $order_integration_srv->set_order_as_paid(TRUE);    # TRUE = order has been paid

    //                      # TEMPORARY NEED CREDIT CHECK until encoding problem solved. This will mark so.status = 2
    //                      $order_integration_srv->need_credit_check(TRUE);

    //                      if($items = $order["orderItem"])
    //                      {
    //                          echo "<pre>DEBUG TOTAL ITEMS COUNT: ".count($items)."</pre> ";
    //                          foreach ($items as $ikey => $item)
    //                          {
    //                              $prod_name      = $item["name"]["value"];
    //                              $prod_sku       = $item["baseSKU"];
    //                              $ext_item_cd    = $item["orderItemId"];
    //                              $qty            = $item["quantity"];
    //                              $unitprice      = number_format( ($item["unitPrice"] - ($item["discount"] / $qty)), 2, '.', '');

    //                              # itemTotal = sum(unitPrice*quantity - discount)
    //                              $itemtotal      = number_format($item["itemTotal"], 2, '.', '');

    //                              $order_integration_srv->set_qty($qty);
    //                              $order_integration_srv->set_unit_price($unitprice);
    //                              $order_integration_srv->set_amount($ordertotal);
    //                              $order_integration_srv->set_platform_order_id($order_no);
    //                              $order_integration_srv->set_delivery_charge(0);
    //                              $order_integration_srv->set_weight(1);
    //                              $order_integration_srv->set_prod_sku($prod_sku);
    //                              $order_integration_srv->set_prod_name($prod_name);
    //                              $order_integration_srv->set_ext_item_cd($ext_item_cd);

    //                              echo "<pre>DEBUG ITEMLOOP $ikey: $prod_name -- sku <$prod_sku | $ext_item_cd>, qty <$qty>, unitprice <$unitprice> </pre> ";

    //                              /* REQUIRED: create so-related info in a temp place */
    //                              $order_integration_srv->create_platform_batch();
    //                          }
    //                      }
    //                  }
    //              }
    //          }

    //          /* REQUIRED: insert client info and SO details into db */
    //          $success_so_list = $order_integration_srv->commit_platform_batch();

    //          // update time in schedule_job db
    //          if($this->test === false && $this->isdev === false)
    //              $this->get_sj_srv()->update_last_process_time($this->http_name, $now_time);

    //          if($success_so_list)
    //          {
    //              foreach ($success_so_list as $key => $v)
    //              {
    //                  $so_no = $v["so_no"];
    //                  $platform_order_id = $v["platform_order_id"];
    //                  $ordernotes = $shoppercomment[$platform_order_id];

    //                  # if no order notes found, skip current loop
    //                  if(empty($ordernotes))
    //                  {
    //                      continue;
    //                  }

    //                  for(;;)
    //                  {
    //                      $so_obj = null;
    //                      $so_obj = $so_dao->get(array("so_no"=>$so_no));

    //                      if($so_obj == null)
    //                      {
    //                          // THIS IS A HACK!! Sometimes die when too many loops
    //                          sleep(1);
    //                          echo "falling asleep $s, so_no $so_no<br>";
    //                          // $s++;
    //                      }
    //                      else
    //                          break;
    //                  }

    //                  if ($so_obj)
    //                  {
    //                      $order_integration_srv->insert_order_notes($so_no, $ordernotes, $this->platform_id, $website);
    //                      echo "<pre>---[[ DEBUG: inserting order notes // so_no: $so_no // db platform_order_id: $platform_order_id ]]---</pre>";

    //                      $order_integration_srv->insert_so_priority_score($so_no, '1110', $this->platform_id, $website);
    //                  }
    //              }
    //          }

    //          echo "<pre>DEBUG WHOLE LOOP COMPLETED</pre><hr></hr>";
    //      }
    //      catch (Exception $e)
    //      {
    //          $error_msg = "rakuten_service line ".__LINE__." {$this->process} PLEASE RERUN IMPORT_ORDERS JOB. \nCaught exception: \n nl2br($e->getMessage()) ";
    //          echo $error_msg;
    //          $this->send_notification_email("DE", $error_msg);
    //          return FALSE;
    //      }
    //  }
    // }


    // private function get_rakuten_orders_api($pageindex = 0, $maxresult = 100, $orderstatus = "NotShipped")
    // {
    //  /*
    //      This function will get list of orders and append each order details (i.e. items info) to each order.
    //      It will also save a copy of orders w timestamp on server

    //      - pageindex     = index of page requested, default is 0 (first page)
    //      - maxresult     = 0 - 100 (rakuten default is 100)
    //      - orderStatus   = Unfixed / AwaitingPayment / ProcessingPayment / NotShipped
    //                          / AwaitingCompletion (payment & shipment success; will change to Complete 7 days aft shipped)
    //                          / Complete  (cannot be cancelled or returned)
    //                          / Cancelled / CancelledAndRefund
    //  */

    //  # limit results in DEV testing to avoid slow speed
    //  // if(strpos($_SERVER["HTTP_HOST"], "admindev") !== FALSE)
    //  //  $maxresult = 10;

    //  $response = array();
    //  $this->process = "get_rakuten_orders_api";
    //  $last_cron_time = $this->get_sj_srv()->get_last_process_time($this->http_name); # get the schedule for last cron job ran


    //  if (!$last_cron_time)
    //  {
    //      $this->send_notification_email("GT"); # get_cron_time
    //  }
    //  else
    //  {
    //      $now_time = $this->now_time;
    //      if(strtotime($now_time) - strtotime($last_cron_time) > 2592000) # exceed 30 days
    //      {
    //          echo "<pre>DEBUG: EXCEED TIME RANGE";
    //          $this->send_notification_email("TR"); # time_range
    //      }
    //      else
    //      {
    //          if(isset($_GET["nowtime"]))
    //          {
    //              $now_time = $_GET["nowtime"];
    //          }

    //          $last_time = date("Y-m-d H:i:s", (strtotime($now_time) - 24*60*60*1)); # minus one day to catch any missing previously

    //          $last_rakuten_time = date("Y-m-d\TH:i:s.000\Z", strtotime($last_time));
    //          $now_rakuten_time = date("Y-m-d\T23:59:59.000\Z", strtotime($now_time));

    //          if(strtotime($last_time) >= strtotime($now_time))
    //          {
    //              $error_msg = "rakuten_service line ".__LINE__." {$this->process} last_rakuten_time <$last_rakuten_time> is greater or equal now_rakuten_time <$now_rakuten_time> ";
    //              $this->send_notification_email("DE", $error_msg);
    //              echo $error_msg;
    //              return FALSE;
    //          }

    //          $apitype = "/order/list";
    //          $requestarr = array(
    //                              "createdAfter"      => $last_rakuten_time,
    //                              "createdBefore"     => $now_rakuten_time,
    //                              "orderStatus"       => $orderstatus,
    //                              "maxResultsPerPage" => $maxresult,
    //                              "pageIndex"         => $pageindex
    //                              );

    //          if($response = $this->call_rakuten_api("get", $apitype, $requestarr))
    //          {
    //              if($pageindex == 0)
    //              {
    //                  $orders = $response["orders"];

    //                  # Rakuten has a max of 100 orders per request, so loop according to totalCount
    //                  $oloop = 1;
    //                  $totalcount = $response["totalCount"];
    //                  if($totalcount == 0)
    //                  {
    //                      return 0;
    //                  }
    //                  else
    //                  {
    //                      $ordersdisplayed = count($response["orders"]);  # count actual orders array
    //                      if($ordersdisplayed > 0)
    //                          $ceil = ceil($totalcount / $ordersdisplayed);
    //                      else
    //                      {
    //                          $error_msg = "rakuten_service line ".__LINE__." {$this->process} Unable to count Rakuten's total orders @ $now_time.";
    //                          $this->send_notification_email("DE", $error_msg);
    //                          return FALSE;
    //                      }

    //                      for ($oloop=1; $oloop < $ceil; $oloop++)
    //                      {
    //                          set_time_limit(120);
    //                          // sleep(5);
    //                          # CONSOLIDATE: append orders from pageIndex > 0 (aft page 1)
    //                          // if($this->isdev === false)
    //                              $orders = array_merge($orders, $this->get_rakuten_orders_api($oloop));
    //                      }

    //                      if($orders)
    //                      {
    //                          # SUCCESS - process data to store; format array to add item details per order
    //                          $orders_w_itemdetails = $this->process_order_data($orders);
    //                          return $orders_w_itemdetails;
    //                      }
    //                      else
    //                      {
    //                          $error_msg = "rakuten_service line ".__LINE__." {$this->process} Empty orders @ $now_time.";
    //                          $this->send_notification_email("DE", $error_msg);
    //                          return FALSE;
    //                      }
    //                  }
    //              }
    //              else
    //              {
    //                  # if this is not the first page, just return the results so that can consolidate
    //                  return $response["orders"];
    //              }
    //          }
    //      }
    //  }

    //  return FALSE;
    // }

    // private function process_order_data($orders)
    // {
    //  $this->process = "process_order_data";
    //  $csv_data = $csv_header = "";
    //  foreach ($orders as $key => $order)
    //  {
    //      $item_csv = $order_csv = "";

    //      if($key%50 == 0)
    //      {
    //          set_time_limit(120);
    //          sleep(5);
    //      }

    //      # RECONSTRUCT $order because some fields are optional, position in array impt for csv
    //      if(!array_key_exists("phoneNumber", $order["shipping"]["shippingAddress"]))
    //      {
    //          $order["shipping"]["shippingAddress"] = array_slice($order["shipping"]["shippingAddress"], 0, 1, true) +
    //                                                  array("phoneNumber"=> " ") +
    //                                                  array_slice($order["shipping"]["shippingAddress"], 1, count($order["shipping"]["shippingAddress"])-1, true);
    //      }
    //      if(!array_key_exists("address2", $order["shipping"]["shippingAddress"]))
    //      {
    //          $order["shipping"]["shippingAddress"] = array_slice($order["shipping"]["shippingAddress"], 0, 7, true) +
    //                                                  array("address2"=> " ") +
    //                                                  array_slice($order["shipping"]["shippingAddress"], 7, count($order["shipping"]["shippingAddress"])-7, true);
    //      }
    //      if(!array_key_exists("address3", $order["shipping"]["shippingAddress"]))
    //      {
    //          $order["shipping"]["shippingAddress"] = array_slice($order["shipping"]["shippingAddress"], 0, 8, true) +
    //                                                  array("address3" => " ") +
    //                                                  array_slice($order["shipping"]["shippingAddress"], 8, count($order["shipping"]["shippingAddress"])-8, true);
    //      }
    //      if(!array_key_exists("address4", $order["shipping"]["shippingAddress"]))
    //      {
    //          $order["shipping"]["shippingAddress"] = array_slice($order["shipping"]["shippingAddress"], 0, 9, true) +
    //                                                  array("address4"=> " ") +
    //                                                  array_slice($order["shipping"]["shippingAddress"], 9, count($order["shipping"]["shippingAddress"])-9, true);
    //      }
    //      if(!array_key_exists("address5", $order["shipping"]["shippingAddress"]))
    //      {
    //          $order["shipping"]["shippingAddress"] = array_slice($order["shipping"]["shippingAddress"], 0, 10, true) +
    //                                                  array("address5"=> " ") +
    //                                                  array_slice($order["shipping"]["shippingAddress"],  10, count($order["shipping"]["shippingAddress"])-10, true);
    //      }
    //      if(!array_key_exists("trackingNumber", $order["shipping"]))
    //      {
    //          $order["shipping"] = $order["shipping"] + array("trackingNumber" => " ");
    //      }
    //      # END RECONSTRUCT =====================================================================

    //      if($key == 0)
    //      {
    //          $csv_header = $this->format_arr_to_csv("key", $order);
    //      }

    //      $order_no       = $order["orderNumber"];

    //      // if($order_no == "0014232-140816-4501061504" || $order_no == "0014232-140816-4501061504" || $order_no == "0014232-140815-4341541726")
    //      // {
    //      //  $order_info = $this->get_order_info($order_no);
    //      //  echo "<pre>"; var_dump($order); var_dump($order_info);  die();
    //      // }

    //      # need to call Rakuten for items in each order
    //      if(($order_info = $this->get_order_info($order_no)) === false)
    //      {
    //          echo "<pre>DEBUG LOOP $key: ORDERNO: $order_no cannot get item.</pre> ";
    //          unset($order);      # if cannot get item details, remove the order
    //          continue;
    //      }
    //      else
    //      {
    //          $this->process = "process_order_data";  # reset the process name for error emails

    //          /*
    //              items per order contain duplicated info from Orders, so we sieve out certain info
    //              - itemTotalBeforeDiscount = sum(unitPrice*quantity) [Total value of all the order items before discount]
    //              - discountTotal = sum(discount) [Total discount value]
    //              - shopperComment = shopper comment/memo
    //              - orderItem = array of Items
    //          */
    //          $filter_wanted = array("itemTotalBeforeDiscount", "discountTotal", "shopperComment", "orderItem");
    //          foreach ($filter_wanted as $fkey => $v)
    //          {
    //              if($key == 0 && $fkey == 0)
    //              {
    //                  $csv_header .= $this->format_arr_to_csv("key", $order_info, $filter_wanted);
    //              }

    //              if($v != "orderItem")
    //              {
    //                  $order[$v] = $order_info[$v];   # append extra details to main order array
    //              }

    //          }

    //          # we separate orderItem processing to construct CSV rows by single item
    //          # if one order has multiple item, then repeat order info on next line and append next item
    //          $order_csv = $this->format_arr_to_csv("value", $order);
    //          foreach ($order_info["orderItem"] as $oikey => $oivalue)
    //          {
    //              $item_csv = rtrim($this->format_arr_to_csv("value", $oivalue), ',') ;       # item details
    //              $csv_data .= $order_csv . $item_csv . "\n" ;                                # order-related info + item detail

    //              $order["orderItem"][] = $oivalue;   # append order's item details
    //          }

    //          $orders[$key] = $order;     # replace with newly constructed order w item details
    //      }
    //  }

    //  $debug_content = ob_get_contents();
    //  ob_end_clean();

    //  $csv_data = $csv_header . "\n" . $csv_data;
    //  $filename = "{$this->platform_id}_notShipped_{$this->now_time}.csv";
    //  if($fp = @fopen("/var/data/valuebasket.com/orders/rakuten/{$this->country_id}/$filename", 'w'))
    //  {
    //      @fwrite($fp, $csv_data);
    //      @fclose($fp);
    //  }
    //  // header ("Content-Type: text/csv"); header("Content-Disposition: attachment; filename=$filename"); echo $csv_data; die();
    //  echo $debug_content;
    //  $email_list = "melody.yu@eservicesgroup.com,celine@eservicesgroup.com,romuald@eservicesgroup.com,ping@eservicesgroup.com";
    //  $this->send_report_email($csv_data, "notShipped Report", $filename, $email_list);
    //  return $orders;
    // }

    /* =========================================================================================================================================
        END OF UN-USED FUNCTIONS
    ========================================================================================================================================= */


    /* =================================================================================================================================================

        NEW IMPORT FUNCTIONS HERE TO PREVENT TIME OUT DURING RE-RUN
     =================================================================================================================================================*/

    public function get_http_info()
    {
        return $this->http_info;
    }

    private function format_arr_to_str($dataarr = array())
    {
        /* this function formats array into string for email */

        $message = "";
        if ($dataarr) {
            foreach ($dataarr as $key => $value) {
                if (is_array($value)) {
                    $message = $this->format_arr_to_str($value);
                } else {
                    $message .= "\n$key: $value";
                }
            }
        }

        return $message;
    }

    private function process_order_data_new($orders = array(), $check_existing = TRUE)
    {
        $this->process = "process_order_data_new";
        if (empty($orders))
            return $orders;

        $so_dao = $this->get_so_dao();

        foreach ($orders as $key => $order) {
            $order_no = $order["orderNumber"];
            $orderpackageid = $order["shipping"]["orderPackageId"];

            set_time_limit(120);

            if ($check_existing === TRUE) {
                // if existing order, don't call API to get item details
                $where["biz_type"] = "RAKUTEN";
                $where["platform_id"] = $this->platform_id;
                $so_obj = $so_dao->get_so_by_txn_id($orderpackageid, $where);   # CHECK TO SEE IF ORDER CREATED PREVIOUSLY

                if ($so_obj) {
                    echo "<pre>DEBUG LOOP $key: EXISTING ORDER - ORDERNO: $order_no || PACKAGEID: $orderpackageid will be ignored.</pre> ";
                    $orders[$key] = array();
                    continue;
                }
            }

            // calls API to get item details in each individual order
            $order_info = $this->get_order_info($order_no);
            if ($order_info === false) {
                echo "<pre>DEBUG LOOP $key: ORDERNO: $order_no cannot get item.</pre> ";
                $orders[$key] = array();        # if cannot get item details, remove the order
                continue;
            } else {
                if ($orderitem = $order_info["order"]) {
                    // append extra info to order
                    $order["itemTotalBeforeDiscount"] = $orderitem["itemTotalBeforeDiscount"];
                    $order["discountTotal"] = $orderitem["discountTotal"];
                    $order["shopperComment"] = $orderitem["shopperComment"];
                } else {
                    $order["itemTotalBeforeDiscount"] = "";
                    $order["discountTotal"] = "";
                    $order["shopperComment"] = "";
                }

                if ($itemdetail = $order_info["orderItem"]) {
                    foreach ($itemdetail as $k => $itemdetailv) {
                        $order["orderItem"][] = $itemdetailv;
                    }
                }
            }
            $neworders[$key] = $order;      # replace with newly constructed order w item details
        }

        return $neworders;
    }

    /* =================================================================================================================================================
        END OF NEW IMPORT FUNCTIONS
     =================================================================================================================================================*/

    private function get_order_info($orderno)
    {
        if ($this->test)
            return $this->get_test_order_info();

        $this->process = "get_order_info";
        $apitype = "/order/get";
        $requestarr = array("orderNumber" => $orderno);

        $response = $this->call_rakuten_api("get", $apitype, $requestarr);
        if (isset($response["errors"])) {
            foreach ($response["errors"] as $key => $value) {
                $rakuten_response_error .= "ErrorCode: {$value["errorCode"]} || shortMessage: {$value["shortMessage"]} || longMessage: {$value["longMessage"]}\r\n";
            }

            $error_msg = "rakuten_service line " . __LINE__ . " {$this->process}, RequestId: {$response["requestId"]}\r\n, RakutenErrorMessage: " . $rakuten_response_error . " - No data returned for orderNumber <$orderno> @ {$this->now_time}.";
            echo "ERROR:<br>" . $error_msg;

            $this->send_notification_email("DE", $error_msg);
            return FALSE;
        } elseif (isset($response["order"])) {
            return $response["order"];
        } else {
            $error_msg = "rakuten_service line " . __LINE__ . " {$this->process}, RequestId: {$response["requestId"]}\r\n, No data returned for orderNumber <$orderno> @ {$this->now_time}.";
            echo "ERROR:<br>" . $error_msg;

            $this->send_notification_email("DE", $error_msg);
            return FALSE;
        }
        return FALSE;
    }

    private function get_test_order_info()
    {
        # construct you own test data here.
        # add/change items in orderItem array
        $ret = array(
            "orderNumber" => "0014232-140730-3027356014",
            "orderStatus" => "NotShipped",
            "orderTotal" => "487.00",
            "itemTotalBeforeDiscount" => "487.00",
            "discountTotal" => "0.00",
            "shippingFee" => "0.00",
            "orderDate" => "2014-07-30T08:24:33.000Z",
            "shopperComment" => "HI THERE PLS BE QUICK",
            "orderItem" =>
                array(
                    0 =>
                        array(
                            "name" =>
                                array(
                                    "value" => "Astro A50 Battlefield 4 edici?n auriculares inal?mbricos",
                                    "en-US" => "Astro A50 Battlefield 4 edici?n auriculares inal?mbricos"
                                ),
                            "baseSKU" => "14553-AA-NA",
                            "orderItemId" => "bd7f7513-44ec-4d49-9f5d-33ce2b82ecfd",
                            "unitPrice" => "229.00",
                            "quantity" => "1",
                            "discount" => "0.00",
                            "itemTotal" => "229.00"
                        ),

                    1 =>
                        array(
                            "name" =>
                                array(
                                    "value" => "Objetivo Canon EF-S 55-250mm f/4-5.6 IS II ",
                                    "en-US" => "Objetivo Canon EF-S 55-250mm f/4-5.6 IS II "
                                ),
                            "baseSKU" => "10265-AA-NA",
                            "orderItemId" => "TESTbd7f7513-44ec-4d49-9f5d-33ce2b82ecfd",
                            "unitPrice" => "129.00",
                            "quantity" => "2",
                            "discount" => "0.00",
                            "itemTotal" => "258.00"
                        )
                ),
            "shipping" =>
                array(
                    "orderPackageId" => "a194bee3-56b1-448b-9509-ab2a56b1e85a",
                    "shippingAddress" => array(
                        "name" => "Robert Bar?n Villa",
                        "phoneNumber" => "665566995",
                        "postalCode" => "08760",
                        "countryCode" => "ES",
                        "stateCode" => "ES-B",
                        "city" => "Martorell",
                        "address1" => "C/ Enric Granados n3 1e 4a"
                    ),
                    "shippingMethod" => "Free Shipping",
                    "shippingStatus" => "NotShipped"
                ),
            "payment" =>
                array(
                    "orderPaymentId" => "30251adc-3d0a-48fb-a231-25edbd9c601d",
                    "paymentMethod" => "PayPal",
                    "paymentStatus" => "Paid",
                    "payAmount" => "229.00",
                    "pointAmount" => "0",
                    "paymentDate" => "2014-07-30T08:28:07.000Z"
                ),
            "buyerName" => "Robert Barin Villa",
            "buyerEmail" => "bar_demoe@hotmail.com"
        );
        return $ret;
    }

    private function convert_order_data_to_csv($orders = array())
    {
        $this->process = "convert_order_data_to_csv";
        $csv = "";
        if (empty($orders))
            return $csv;

        foreach ($orders as $key => $order) {
            $order_no = $order["orderNumber"];

            $buyerName = str_replace(',', ' ', $order["buyerName"]);
            $mainrow = "{$order["orderNumber"]},{$order["shopURL"]},{$order["orderStatus"]},{$order["orderTotal"]},{$order["orderDate"]},{$order["completionDate"]},{$buyerName},{$order["buyerEmail"]},";

            if (is_array($order["shipping"])) {
                $shiparr = $order["shipping"];

                $shipname = str_replace(',', ' ', $shiparr["shippingAddress"]["name"]);
                $shipcity = str_replace(',', ' ', $shiparr["shippingAddress"]["city"]);
                $address1 = str_replace(',', ' ', $shiparr["shippingAddress"]["address1"]);
                $address2 = str_replace(',', ' ', $shiparr["shippingAddress"]["address2"]);
                $address3 = str_replace(',', ' ', $shiparr["shippingAddress"]["address3"]);
                $address4 = str_replace(',', ' ', $shiparr["shippingAddress"]["address4"]);
                $address5 = str_replace(',', ' ', $shiparr["shippingAddress"]["address5"]);

                $mainrow .= "{$shiparr["orderPackageId"]},{$shipname},{$shiparr["shippingAddress"]["phoneNumber"]},{$shiparr["shippingAddress"]["postalCode"]},{$shiparr["shippingAddress"]["countryCode"]},{$shiparr["shippingAddress"]["stateCode"]},{$shipcity},$address1,$address2,$address3,$address4,$address5,{$shiparr["shippingMethod"]},{$shiparr["shippingStatus"]},{$shiparr["trackingNumber"]},{$shiparr["shippingDate"]},";
            } else {
                $mainrow .= ",,,,,,,,,,,,,,,,";
            }

            if (is_array($order["payment"])) {
                $payarr = $order["payment"];
                $mainrow .= "{$payarr["orderPaymentId"]},{$payarr["paymentMethod"]},{$payarr["paymentStatus"]},{$payarr["payAmount"]},{$payarr["pointAmount"]},{$payarr["paymentDate"]},";
            } else {
                $mainrow .= ",,,,,,";
            }

            $shopperComment = str_replace(',', ' ', $order["shopperComment"]);
            $mainrow .= "{$order["itemTotalBeforeDiscount"]},{$order["discountTotal"]},{$shopperComment},";

            $itemdetail = $order["orderItem"];
            if (isset($itemdetail)) {
                foreach ($itemdetail as $key => $itemdetailv) {
                    $itemname = str_replace(',', ' ', $itemdetailv["name"]["value"]);
                    $csv .= $mainrow . "{$itemname},{$itemdetailv["baseSKU"]},{$itemdetailv["orderItemId"]},{$itemdetailv["unitPrice"]},{$itemdetailv["quantity"]},{$itemdetailv["discount"]},{$itemdetailv["itemTotal"]},\n";
                }
            }

        }

        return $csv;
    }

    private function get_order_data_header()
    {
        $csvheader = "orderNumber,shopURL,orderStatus,orderTotal,orderDate,completionDate,buyerName,buyerEmail,ship_orderPackageId,ship_name,ship_phoneNumber,ship_postalCode,ship_countryCode,ship_stateCode,ship_city,ship_address1,ship_address2,ship_address3,ship_address4,ship_address5,ship_shippingMethod,ship_shippingStatus,ship_trackingNumber,ship_shippingDate,orderPaymentId,paymentMethod,paymentStatus,payAmount,pointAmount,paymentDate,itemTotalBeforeDiscount,discountTotal,shopperComment,item_name,baseSKU,orderItemId,unitPrice,quantity,discount,itemTotal,\n";
        return $csvheader;
    }

    private function send_report_email($csv_string = "", $report_type, $filename, $email_list)
    {
        include_once(BASEPATH . "plugins/phpmailer/phpmailer_pi.php");
        $phpmail = new phpmailer();
        $phpmail->IsSMTP();
        $phpmail->From = "Admin <admin@valuebasket.net>";
        if ($email_arr = explode(",", $email_list)) {
            foreach ($email_arr as $email) {
                $phpmail->AddAddress(trim($email));
            }
        }

        $phpmail->Subject = "RAKUTEN {$this->platform_id} $report_type";
        $phpmail->IsHTML(false);
        $phpmail->Body = "Generated @ " . date("Y-m-d H:i:s", $this->now_time) . "\r\n[type: $report_type]";
        $phpmail->AddStringAttachment($csv_string, $filename, 'base64', 'text/csv');

        // $phpmail->SMTPDebug  = 1;

        if (strpos($_SERVER["HTTP_HOST"], "admindev") === FALSE)
            $result = $phpmail->Send();
    }

    public function get_orders_list($country_id = "ES", $orderno = "", $test = FALSE)
    {
        // pass in ?nowtime=2014-10-01 to get specific day order
        $this->platform_id = $platform_id = "RAKU" . strtoupper($country_id);
        $this->process = "get_orders_list";
        $this->country_id = strtoupper($country_id);
        $this->http_name = 'RAKUTEN_VALUEBASKET';


        $this->test = $test;
        $this->isdev = strpos($_SERVER["HTTP_HOST"], "admindev") === false ? FALSE : TRUE;

        // uncomment these two lines during dev to test LIVE data
        // $this->isdev = false;
        $this->demoshop = false;

        if (isset($_GET["special"])) {
            // for own use, go to special_report() and input your list of desired orders
            $this->special_report();
            die();
        }
        if (isset($_GET["importspecial"])) {
            // for own use, go to special_report() and input your list of desired orders
            $this->importspecial($orderno);
            die();
        }

        if ($orderno) {
            // get report for single order number
            $csv = "orderNumber,orderPackageId,orderStatus,orderDate,shippingStatus\n";
            $orderinfo = $this->get_order_info($orderno);
            $csvheader = $this->get_order_data_header();
            $csv = $this->convert_order_data_to_csv(array($orderinfo));
            $csv_data = $csvheader . $csv;
            header("Content-Type: text/csv");
            header("Content-Disposition: attachment; filename=singleorderinfo.csv");
            echo $csv_data;
            die();
        } else {
            $orderstatus = "NotShipped";
            if ($_GET["status"])
                $getstatus = strtolower($_GET["status"]);

            switch ($getstatus) {
                case 'unfixed':
                    $orderstatus = 'Unfixed';
                    break;
                case 'awaitingpayment':
                    $orderstatus = 'AwaitingPayment';
                    break;
                case 'processingpayment':
                    $orderstatus = 'ProcessingPayment';
                    break;
                case 'notshipped':
                    $orderstatus = 'NotShipped';
                    break;
                case 'awaitingcompletion':
                    $orderstatus = 'AwaitingCompletion';
                    break;
                case 'complete':
                    $orderstatus = 'Complete';
                    break;
                case 'cancelled':
                    $orderstatus = 'Cancelled';
                    break;
                case 'cancelledandrefund':
                    $orderstatus = 'CancelledAndRefund';
                    break;

                default:
                    $orderstatus = "NotShipped";
                    break;
            }

            $mainorderinfo = $this->get_rakuten_orders_api_new($orderstatus);
            $orders = $this->process_order_data_new($mainorderinfo, FALSE);
            $csv = $this->convert_order_data_to_csv($orders);
            $csvheader = $this->get_order_data_header();
            $csv_data = $csvheader . $csv;
            $filename = "{$platform_id}_{$orderstatus}_" . date("YmdHis") . ".csv";

            header("Content-Type: text/csv");
            header("Content-Disposition: attachment; filename=$filename");
            echo $csv_data;
            die();
        }
    }

    private function special_report()
    {
        // put list of order number
        $order = array(
            "0014232-141002-8386309012",
            "0014232-141002-8416137512",
            "0014232-141003-7037780603",
            "0014232-141003-7126370815",
            "0014232-141003-7150359022",
            "0014232-141003-7151991626",
            "0014232-141003-7186673523",
            "0014232-141006-7080842407",
            "0014232-141001-7755729624",
            "0014232-141001-7887933028");

        $csv = "orderNumber,orderPackageId,orderStatus,orderDate,shippingStatus\n";
        foreach ($order as $orderno) {
            $orderinfo = $this->get_order_info($orderno);
            $csv .= "{$orderinfo["orderNumber"]},{$orderinfo["shipping"]["orderPackageId"]},{$orderinfo["orderStatus"]},{$orderinfo["orderDate"]},{$orderinfo["shipping"]["shippingStatus"]},\n";
        }
        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename=specialreport.csv");
        echo $csv;
        die();
    }

    private function importspecial($orderlist = "")
    {
        if ($orderlist) {
            $order = explode(',', $orderlist);
        } else {
            // put list of order number
            $order = array(
                "0014232-141009-0312270618",
                "0014232-141009-2241041111",
                "0014232-141009-2432287114",
                "0014232-141009-2473824204",
                "0014232-141009-2673435026",
                "0014232-141009-3137617725",
                "0014232-141009-3176897202",
                "0014232-141009-3327221011",
                "0014232-141009-3931721701",
                "0014232-141009-4260845416"
            );
        }

        if ($order) {
            foreach ($order as $orderno) {
                $this->import_single_order("ES", $orderno);
            }
        }
    }

    public function import_single_order($country_id = "ES", $order_no = "", $test = FALSE)
    {
        if (!$order_no) {
            echo "Input order number.";
            die();
        }

        ini_set("default_charset", "UTF-8");
        ob_start();
        $this->test = $test;
        $this->isdev = strpos($_SERVER["HTTP_HOST"], "admindev") === false ? FALSE : TRUE;

        $this->process = "import_single_order";
        $this->country_id = strtoupper($country_id);
        $this->platform_id = "RAKU" . strtoupper($country_id);
        $this->http_name = 'RAKUTEN_VALUEBASKET';
        $website = $this->website = "valuebasket";

        $so_dao = $this->get_so_dao();
        $shoppercomment = array();

        switch ($country_id) {
            case 'ES':
                $currency_id = "EUR";
                $paymentgatewayid = "raku_es";  # refer to payment_gateway db
                break;

            default:
                $currency_id = "EUR";
                $paymentgatewayid = "raku_es";
                break;
        }

        if ($test === TRUE) {
            /* this goes to test function with pre-set values */
            /* ONLY RUN ON DEV!!!! */
        } else {
            try {
                $client_intergration_srv = new Client_integration_service();
                $client_intergration_srv->set_platform_id($this->platform_id);
                $client_intergration_srv->set_website(strtoupper($website));

                $order_integration_srv = new Order_integration_service();

                $order_integration_srv->set_notification_email($this->notification_email);
                $order_integration_srv->set_biz_type('RAKUTEN');
                $order_integration_srv->set_platform_id($this->platform_id);
                $order_integration_srv->set_website(strtoupper($website));

                /**
                 * We must always call get_batch_id before creating any orders using OIS
                 * Everytime import_orders run, this portion checks if batch_id exists, else it will create one
                 **/
                $now_time = $this->now_time = date("YmdHis", mktime());
                $batch_remark = "Rakuten_" . $website . "_" . $now_time;
                $batch_id = $order_integration_srv->get_batch_id($batch_remark, $this->platform_id, $website);
                $order_integration_srv->set_batch_id($batch_id);

                if ($batch_id) {
                    echo "<pre>---[[ DEBUG: START BATCH <$batch_id> ]]---</pre>";

                    if (($order = $this->get_order_info($order_no)) !== FALSE) {
                        $this->process = "get_order_info";  # reset for error emails
                        $order_no = $order["orderNumber"];                // 0014232-140725-2716923314
                        //$order_no         = $order->{"orderNumber"};


                        $orderstatus = $order["orderStatus"];
                        $buyername = $order["buyerName"];
                        $buyeremail = $order["buyerEmail"];

                        # orderTotal = itemTotal + shippingfee (itemTotal = sum(unitPrice*qty - discount))
                        $ordertotal = $order["orderTotal"];
                        $orderdate = date("Y-m-d H:i:s", strtotime($order["orderDate"]));

                        $shipping = $order["shipping"];
                        $orderpackageid = $shipping["orderPackageId"];  //83702f8c-c373-4cab-aeae-31fa07e1801f
                        $ship_status = $shipping["shippingStatus"];
                        $trackingnumber = $shipping["trackingNumber"];

                        if (is_array($shipping['shippingAddress'])) {
                            $ship_name = $shipping["shippingAddress"]["name"];
                            $ship_tel = $shipping["shippingAddress"]["phoneNumber"];
                            $ship_postcode = $shipping["shippingAddress"]["postalCode"];
                            $ship_countryid = $shipping["shippingAddress"]["countryCode"];
                            $ship_state = $shipping["shippingAddress"]["stateCode"];
                            $ship_city = $shipping["shippingAddress"]["city"];
                            $ship_add = $shipping["shippingAddress"]["address1"];
                            if ($shipping["shippingAddress"]["address2"])
                                $ship_add .= "||" . $shipping["shippingAddress"]["address2"];
                            if ($shipping["shippingAddress"]["address3"])
                                $ship_add .= "||" . $shipping["shippingAddress"]["address3"];
                            if ($shipping["shippingAddress"]["address3"])
                                $ship_add .= "||" . $shipping["shippingAddress"]["address3"];
                            if ($shipping["shippingAddress"]["address4"])
                                $ship_add .= "||" . $shipping["shippingAddress"]["address4"];
                            if ($shipping["shippingAddress"]["address5"])
                                $ship_add .= "||" . $shipping["shippingAddress"]["address5"];
                        }

                        $payment = $order["payment"];
                        $pay_id = $payment["orderPaymentId"];  // b77d7a5e-71f6-43f5-bf16-6384d4d84423
                        $pay_mtd = $payment["paymentMethod"];
                        $pay_status = $payment["paymentStatus"];
                        $pay_amt = $payment["payAmount"];
                        $pay_pointamt = $payment["pointAmount"];
                        $pay_date = date("Y-m-d H:i:s", strtotime($payment["paymentDate"]));

                        $shoppercomment[$order_no] = trim($order["shopperComment"], '{}');

                        $where["biz_type"] = "RAKUTEN";
                        $where["platform_id"] = $this->platform_id;
                        $so_obj = $so_dao->get_so_by_txn_id($order_no, $where); # CHECK TO SEE IF ORDER CREATED PREVIOUSLY

                        if ($so_obj) {
                            echo "<pre>DEBUG LOOP $key: EXISTING ORDER - ORDERNO: $order_no || PACKAGEID: $orderpackageid will be ignored.</pre> ";
                            continue;
                        }

                        echo "<hr></hr><pre>DEBUG LOOP $key: PROCESSING - ORDERNO: $order_no. || PACKAGEID: $orderpackageid</pre> ";
                        /** Set all info into CIS and OIS to automate process of writing to db **/
                        /* ============= set xml info into client_integration_service ============= */
                        $client_intergration_srv->set_ext_client_id($buyeremail);
                        $client_intergration_srv->set_client_name($buyername);
                        if ($ship_tel) $client_intergration_srv->set_client_tel($ship_tel);
                        $client_intergration_srv->set_client_mobile("");
                        $client_intergration_srv->set_client_email($buyeremail);
                        $client_intergration_srv->set_client_address($ship_countryid, $ship_postcode, $ship_add, "", "", $ship_city, $ship_state);
                        $client_intergration_srv->set_del_name($ship_name);
                        if ($ship_tel) $client_intergration_srv->set_del_tel($ship_tel);
                        $client_intergration_srv->set_del_mobile("");
                        $client_intergration_srv->set_del_address($ship_countryid, $ship_postcode, $ship_add, "", "", $ship_city, $ship_state);
                        $password = (time() + $orderloop++);      # randomly generate passsword
                        $client_intergration_srv->set_password($password);

                        /* REQUIRED: create client info in a temp place */
                        $client_vo = $client_intergration_srv->create_client($batch_id);

                        /* ============= set xml info into order_integration_service ============= */
                        $order_integration_srv->set_interface_client_vo($client_vo);
                        $order_integration_srv->set_bill_name($buyername);
                        $order_integration_srv->set_bill_address($ship_countryid, $ship_postcode, $ship_add, $ship_city, $ship_state);
                        $order_integration_srv->set_payer_email($buyeremail);
                        $order_integration_srv->set_create_order_time($orderdate);
                        $order_integration_srv->set_pay_date($pay_date);
                        $order_integration_srv->set_currency_id($currency_id);
                        $order_integration_srv->set_txn_id($orderpackageid);
                        $order_integration_srv->set_payment_gateway_id($paymentgatewayid);
                        $order_integration_srv->set_pay_to_account('rakuten');
                        $order_integration_srv->set_payer_ref($pay_id);
                        $order_integration_srv->set_order_as_paid(TRUE);    # TRUE = order has been paid

                        # TEMPORARY NEED CREDIT CHECK until encoding problem solved. This will mark so.status = 2
                        $order_integration_srv->need_credit_check(TRUE);

                        if ($items = $order["orderItem"]) {
                            echo "<pre>DEBUG TOTAL ITEMS COUNT: " . count($items) . "</pre> ";
                            foreach ($items as $ikey => $item) {
                                if (is_array($item["name"])) {
                                    $prod_name = $item["name"]["value"];
                                }
                                $prod_sku = $item["baseSKU"];
                                $ext_item_cd = $item["orderItemId"];
                                $qty = $item["quantity"];
                                $unitprice = number_format(($item["unitPrice"] - ($item["discount"] / $qty)), 2, '.', '');

                                # itemTotal = sum(unitPrice*quantity - discount)
                                $itemtotal = number_format($item["itemTotal"], 2, '.', '');

                                $order_integration_srv->set_qty($qty);
                                $order_integration_srv->set_unit_price($unitprice);
                                $order_integration_srv->set_amount($ordertotal);
                                $order_integration_srv->set_platform_order_id($order_no);
                                $order_integration_srv->set_delivery_charge(0);
                                $order_integration_srv->set_weight(1);
                                $order_integration_srv->set_prod_sku($prod_sku);
                                $order_integration_srv->set_prod_name($prod_name);
                                $order_integration_srv->set_ext_item_cd($ext_item_cd);

                                echo "<pre>DEBUG ITEMLOOP $ikey: $prod_name -- sku <$prod_sku | $ext_item_cd>, qty <$qty>, unitprice <$unitprice> </pre> ";

                                /* REQUIRED: create so-related info in a temp place */
                                $order_integration_srv->create_platform_batch();
                            }
                        }
                    }
                }

                /* REQUIRED: insert client info and SO details into db */
                $success_so_list = $order_integration_srv->commit_platform_batch();

                if ($success_so_list) {
                    foreach ($success_so_list as $key => $v) {
                        $so_no = $v["so_no"];
                        $platform_order_id = $v["platform_order_id"];
                        $ordernotes = $shoppercomment[$platform_order_id];

                        for (; ;) {
                            $so_obj = null;
                            $so_obj = $so_dao->get(array("so_no" => $so_no));

                            if ($so_obj == null) {
                                // THIS IS A HACK!! Sometimes die when too many loops
                                sleep(1);
                                echo "falling asleep $s, so_no $so_no<br>";
                                // $s++;
                            } else
                                break;
                        }

                        if ($so_obj) {
                            $order_integration_srv->insert_so_priority_score($so_no, '1110', $this->platform_id, $website);

                            if ($ordernotes) {
                                $order_integration_srv->insert_order_notes($so_no, $ordernotes, $this->platform_id, $website);
                                echo "<pre>---[[ DEBUG: inserting order notes // so_no: $so_no // db platform_order_id: $platform_order_id ]]---</pre>";
                            }

                        }
                    }
                }

                echo "<pre>DEBUG WHOLE LOOP COMPLETED</pre><hr></hr>";
            } catch (Exception $e) {
                $error_msg = "rakuten_service line " . __LINE__ . " {$this->process} PLEASE RERUN IMPORT_ORDERS JOB. \nCaught exception: \n nl2br($e->getMessage()) ";
                echo $error_msg;
                $this->send_notification_email("DE", $error_msg);
                return FALSE;
            }
        }
    }

    public function update_shipment_status($country_id = "ES", $so_no = "", $test = FALSE)
    {
        $this->test = $test;
        $this->isdev = strpos($_SERVER["HTTP_HOST"], "admindev") === false ? FALSE : TRUE;

/////// change back to demoshop at get_login_info
        $this->process = "update_shipment_status";
        $this->country_id = strtoupper($country_id);
        $this->platform_id = "RAKU" . strtoupper($country_id);
        $this->http_name = 'RAKUTEN_VALUEBASKET';
        $website = $this->website = "valuebasket";

        $where["so.platform_id"] = $this->platform_id;
        if ($so_no) $where["so.so_no"] = $so_no;

        // Currently using so.biz_type = "WEBSITE" because using WEBSITE pricing tool at import_orders()
        if ($obj_list = $this->get_so_srv()->get_rakuten_pending_shipment_update_orders(array("so.platform_id" => $this->platform_id))) {
            foreach ($obj_list as $obj) {
                echo "<hr></hr><pre>DEBUG: Updating shipment for so_no {$obj->get_so_no()}</pre>";
                // $order_no = $obj->get_platform_order_id();
                if ($rs = $this->update_shipment_api($obj) === false) {
                    $error_msg = __LINE__ . " {$this->process} \nFailed to update shipment status for so_no {$obj->get_so_no()}, platform_order_id {$obj->get_platform_order_id()}.";

                    $error_msg .= "\r\n Click 'Mark as closed' if order is cancelled or refund on Rakuten.";

                    $base_url = base_url();
                    if (strpos($base_url, "admin") === FALSE)
                        $base_url = "http://admincentre.valuebasket.com/";
                    $error_msg .= $base_url . "cs/quick_search/view/{$obj->get_so_no()}";
                    echo "<pre>DEBUG ERROR MSG: $error_msg";
                    $this->send_notification_email("US", $error_msg);  #update_shipment_status_error
                    continue;
                } else {
                    $soext_dao = $this->get_so_srv()->get_soext_dao();
                    if ($soext_obj = $soext_dao->get(array("so_no" => $obj->get_so_no()))) {
                        $soext_obj->set_fulfilled("Y");
                        if (!$ret = $soext_dao->update($soext_obj)) {
                            $error_msg = "rakuten_service line " . __LINE__ . " {$this->process} \nFailed to update so_extend after API success. so_no <$obj->get_so_no()>. \nDB erorr: " . $soext_dao->db->_error_message();
                            echo "<pre>DEBUG ERROR MSG: $error_msg</pre>";
                            $this->send_notification_email("US", $error_msg);  #update_shipment_status_error
                        }
                        echo "<pre>DEBUG: SUCCESS UPDATING SOEXT</pre>";
                    }
                }
            }
        } else {
            echo "DEBUG: no obj_list for updating shipment status.\n";
        }
    }

    public function get_so_srv()
    {
        return $this->so_srv;
    }

    private function update_shipment_api($obj)
    {
        $so_no = $obj->get_so_no();
        $platform_order_id = $obj->get_platform_order_id();
        $txn_id = $obj->get_txn_id();
        $courier_name = $obj->get_courier_name();
        $courier_id = $obj->get_courier_id();
        $tracking_no = $obj->get_tracking_no();
        $ext_item_cd = $obj->get_ext_item_cd();
        $dispatch_date = date("Y-m-d\TH:i:s.000\Z", strtotime($obj->get_dispatch_date()));

        if ($so_no == $platform_order_id) {
            # transition period or if manual order, platform number not input correctly
            $error_msg = "rakuten_service line " . __LINE__ . " {$this->process} platform_order_id <$platform_order_id> is not valid Rakuten orderNumber. Pls update shipment status for so_no <$so_no> manually.";
            $this->send_notification_email("US", $error_msg);   # UPDATE_SHIPMENT_STATUS_ERROR
            echo "<pre>$error_msg</pre>";
            return FALSE;
        } else {
            if ($login = $this->get_login_info()) {
                $shopurl = $login["shopurl"];
                $authkey = $login["password"];
                $base_api_url = $login["base_api_url"];
                $identifier = strtolower($this->country_id);
                $this->process = $apitype = "/order/updateShipmentStatus";

                $requestarr["marketplaceIdentifier"] = $identifier;
                $requestarr["orderNumber"] = $platform_order_id;
                $requestarr["shopURL"] = $shopurl;
                $requestarr["orderPackageId"] = $txn_id;
                $requestarr["shippingStatus"] = "Shipped";
                $requestarr["trackingNumber"] = $tracking_no;
                $requestarr["shippingDate"] = $dispatch_date;

                // this line may email rakuten error due to cancelled/refund orders, so hush the email and handle the email content in update_shipment_status()
                $rs_updateship = $this->call_rakuten_api("post", $apitype, $requestarr, false, false);
                return $rs_updateship;
            }
        }
        return FALSE;
    }

    public function add_item_images($country_id = "ES", $sku = "", $test = FALSE)
    {
        /* =======================================================================================================================
        *   http://admincentre.valuebasket.com/cron/platform_integration/rakuten_orders/add_item_images/ES

            Rakuten uses different API to set external images. But when cron job list new items, Rakuten doesn't register the products yet,
            so unable to call setImage API immediately. Thus, we cron this and check if item exists in Rakuten inventory. If exists, continue set image
        ======================================================================================================================= */

        $this->test = $test;
        $this->now_time = date("YmdHis", mktime());
        $this->platform_id = "RAKU" . strtoupper($country_id);  #e.g. RAKUES
        $this->country_id = strtoupper($country_id);
        $this->isdev = strpos($_SERVER["HTTP_HOST"], "admindev") === false ? FALSE : TRUE;
        $this->process = "add_item_images";

        switch ($this->country_id) {
            case 'ES':
                $this->lang_id = "es";
                break;

            default:
                $this->lang_id = "es";
                break;
        }

        // if($sku)
        //  $where = array_merge($where, array("sku"=>$sku));

        $price_ext_dao = $this->get_price_srv()->get_price_ext_dao();
        $where["ext_item_id IS NOT NULL"] = NULL;
        $where["ext_status"] = "L";
        if ($sku)
            $where["sku"] = $sku;
        $where["platform_id"] = $this->platform_id;
        $where["action"] = "P";
        if ($price_ext_obj_list = $price_ext_dao->get_list($where, array("limit" => 5))) {
            echo "<pre>DEBUG: no. of product(s) to update: $total.</pre>";
            $loop_no = 1;
            foreach ($price_ext_obj_list as $price_ext_obj) {
                $sku = $price_ext_obj->get_sku();
                $item_obj = $this->get_product_srv()->get_dao()->get(array("sku" => $sku));
                echo "<hr></hr><pre>Listing on Rakuten$country_id. Loop " . $loop_no++ . "</pre>";

                $imageurl = "";
                if ($imageext = $item_obj->get_image())
                    $imageurl = "http://cdn.valuebasket.com/808AA1/vb/images/product/$sku.$imageext";

                if ($imageurl) {
                    $apitype = "/inventory/get";
                    $get_inventory_arr["baseSkus"] = $sku;
                    $inventory = $this->call_rakuten_api("get", $apitype, $get_inventory_arr, true, false);

                    if ($inventory["totalCount"] > 0) {
                        echo "<pre>$sku exists on Rakuten. Proceed to add image.</pre>";
                        $response_set_image = $this->set_product_image($sku, $imageurl);

                        if ($response_set_image === FALSE) {
                            // $iteminfo = $this->format_arr_to_str($item_obj);
                            // $error_msg = "rakuten_service line ".__LINE__." {$this->process} Cannot add image <$sku - {$this->platform_id}>. \nVB item info: \n$iteminfo.";
                            // $this->send_notification_email("SI", $error_msg);
                            continue;
                        } else {
                            // update action so cron job doesn't pick it up again
                            $price_ext_obj->set_action(NULL);
                            if ($price_ext_dao->update($price_ext_obj) === FALSE) {
                                $error_msg = "rakuten_service line " . __LINE__ . " {$this->process} Cannot update price_extend after set image <$sku>. \n"
                                    . "VB DB error: " . $price_ext_dao->db->_error_message();
                                $this->send_notification_email("SI", $error_msg);
                                continue;
                            }
                        }
                    } else {
                        echo "<pre>$sku not exists on Rakuten yet</pre>";
                        continue;
                    }
                } else {
                    $iteminfo = $this->format_arr_to_str($item_obj);
                    $error_msg = "rakuten_service line " . __LINE__ . " {$this->process} No image URL to set. \nVB item info: \n$iteminfo.";
                    $this->send_notification_email("AI", $error_msg);
                    continue;
                }
            }
        } else {
            echo "DEBUG: no newly-listed products to update";
        }
    }

    public function get_price_srv()
    {
        return $this->price_srv;
    }

    public function get_product_srv()
    {
        return $this->product_srv;
    }

    private function set_product_image($sku, $imageurl = "")
    {
        if ($imageurl) {
            $this->process = "/product/setImage";
            $apitype = "/product/setImage";
            $product_image["baseSku"] = $sku;
            $product_image["images"] = array("$imageurl");
            $requestarr["product"] = $product_image;
            $rs_setimage = $this->call_rakuten_api("post", $apitype, $requestarr, false, 2);    # return response to handle here

            if ($rs_setimage) {
                if (!empty($rs_setimage["errors"])) {
                    $responsestr = print_r($rs_setimage, TRUE);
                    $info = "requestId => {$rs_setimage["requestId"]} \n timeStamp => {$rs_setimage["timeStamp"]}";
                    $this->error_msg = $errorinfo = $this->format_arr_to_str($rs_setimage["errors"]);
                    $error_msg = "rakuten_service line " . __LINE__ . " SetImage API failed. sku <$sku - {$this->platform_id}> @ {$this->now_time} - \r\nRakuten response error msg: $errorinfo \n\nADDITIONAL INFO\n $info"
                        . "\r\n\r\n" . $this->request_info
                        . "\r\n\r\nRakuten Response: $responsestr";
                    $this->send_notification_email("SI", $error_msg);

                } elseif (strtolower($rs_setimage["operationStatus"]) == "submitted") {
                    return TRUE;
                } else {
                    $responsestr = print_r($rs_setimage, TRUE);
                    $error_msg = "rakuten_service line " . __LINE__ . " {$this->process} SetImage API failed (uncaught error). sku <$sku - {$this->platform_id}> @ {$this->now_time}."
                        . "\r\n\r\nRakuten Response: $responsestr";
                    $this->send_notification_email("SI", $error_msg);
                }
            } else {
                $error_msg = "rakuten_service line " . __LINE__ . " {$this->process} SetImage API failed. sku <$sku - {$this->platform_id}> @ {$this->now_time}.";
                $this->send_notification_email("SI", $error_msg);
            }
        } else {
            $error_msg = "rakuten_service line " . __LINE__ . " {$this->process} SetImage API failed. sku <Empty img url: $imageurl> @ {$this->now_time}.";
            $this->send_notification_email("SI", $error_msg);
        }
        return FALSE;
    }

    public function delist_sku($country_id, $selected_margin, $test = 0)
    {

        $this->platform_id = "RAKU" . strtoupper($country_id);  #e.g. RAKUES
        $this->country_id = strtoupper($country_id);
        $this->isdev = strpos($_SERVER["HTTP_HOST"], "admindev") === false ? FALSE : TRUE;
        $debug_msg = "";
        $this->test = $test;

        $abc[] = array("A", "hello");
        $abc[] = array("B", "helloB");
        if ($test == "DEBUG") {
            $csv_string = $this->gen_and_email_csv($abc, "echo_file", $this->platform_id);
            //var_dump("Asdasda");
            die();
        }

        switch ($this->country_id) {
            case 'ES':
                $this->lang_id = "es";
                break;

            default:
                $this->lang_id = "es";
                break;
        }
        $option["desc_lang"] = $this->lang_id;


        if (empty($selected_margin)) {
            $error_msg = "{$this->platform_id} rakuten_service line_no: " . __LINE__ . ". Profit margin cannot be null";
            $this->send_notification_email("DS", $error_msg);  #add_items_error
            $debug_msg .= "<pre>ERROR DEBUG: $error_msg</pre>";
            return false;
        } else {
            if (!is_numeric($selected_margin)) {
                $error_msg = "{$this->platform_id} rakuten_service line_no: " . __LINE__ . ". Profit margin cannot be string";
                $this->send_notification_email("DS", $error_msg);  #add_items_error
                $debug_msg .= "<pre>ERROR DEBUG: $error_msg</pre>";
                return false;
            }
        }
        $where = array("platform_id" => $this->platform_id, "website_quantity > " => 0, "prod_status" => 2, "listing_status" => 'L', "ext_item_id IS NOT NULL" => NULL, "clearance " => 0/*, "ext_ref_1 IS NOT NULL"=>NULL*/);
        $option["limit"] = -1;
        //if($sku)
        //  $where = array_merge($where, array("sku"=>$sku));
        if ($total = $this->get_product_srv()->get_dao()->get_prod_overview_extended($where, array("num_rows" => 1))) {

            $loop_no = 1;
            $delist_count = 0;
            // Get list of items to list on Rakuten
            if ($add_item_list = $this->get_product_srv()->get_dao()->get_prod_overview_extended($where, $option)) {
                foreach ($add_item_list as $item_obj) {

                    if ($sku = $item_obj->get_sku()) {
                        if (($json = $this->get_price_srv()->get_profit_margin_json($this->platform_id, $sku, $item_obj->get_price())) === FALSE) {
                            $error_msg = __FILE__ . " Line " . __LINE__ . " \nCannot get profit margin json for platform_id<$platform_id> - SKU<$sku>. \nDB error: " . $this->db->_error_message();
                            $this->send_notification_email("DS", $error_msg);
                            $debug_msg .= "<br>ERROR. $error_msg";
                            continue;
                        } else {
                            //$product_name=$item_obj->get_name();
                            $json_arr = json_decode($json, TRUE);
                            $prod_margin = $json_arr["get_margin"];
                            $where2 = array("sku" => $sku);
                            $data["objlist"] = $this->get_product_srv()->get_dao()->get_list_w_name($where2, $option);

                            foreach ($data['objlist'] as $value) {
                                $vb_name = $value->get_name();
                                $master_sku = $value->get_master_sku();
                            }
                            $debug_msg .= "<br/>" . $sku . " " . $prod_margin . " <br/>";
                            if ($prod_margin < $selected_margin) {
                                $apitype = "/inventory/get";
                                $get_inventory_arr["baseSkus"] = $sku;
                                $get_inventory_arr["delist"] = true;
                                // Check if item exist on Rakuten
                                $inventory = $this->call_rakuten_api("get", $apitype, $get_inventory_arr, false, false);
                                $debug_msg .= "APi: " . $inventory["totalCount"] . "<br/>";
                                if ($inventory["totalCount"] > 0) {
                                    $debug_msg .= "<hr></hr>Delist No. " . ++$delist_count;
                                    $debug_msg .= "<pre>Listing on Rakuten$country_id. Loop " . $loop_no++ . "</pre>";

                                    $debug_msg .= "<pre>Sku: " . $sku . " Margin: " . $prod_margin . " Selling Price: " . $item_obj->get_price() . "</pre>";
                                    $debug_msg .= "<strong>Delisting from Rakuten...</strong>";

                                    $res = $this->end_item($country_id, $sku);
                                    if ($res["response"]) {
                                        // update db price and price_extend if sucessfully ended rakuten listing
                                        $debug_msg .= "Delisting from Rakuten: Successful!";
                                        $price_dao = $this->get_price_srv()->get_dao();
                                        if ($price_obj = $price_dao->get(array("sku" => $sku, "platform_id" => $this->platform_id))) {
                                            $price_obj->set_listing_status("N");
                                            if (($response = $price_dao->update($price_obj)) === FALSE) {
                                                $error_msg = "{$this->platform_id} rakuten_service line_no: " . __LINE__ . ". Problem updating listing_status in db price." . "\nDB Error Message:" . $this->get_price_srv()->get_dao()->db->_error_message();
                                                $this->send_notification_email("DS", $error_msg);  #add_items_error
                                                $debug_msg .= "<pre>ERROR DEBUG: $error_msg</pre>";
                                            } else {
                                                $debug_msg .= "<pre>DEBUG: Updated in db price. sku: $sku </pre><hr></hr>";
                                            }
                                        }
                                        $price_ext_dao = $this->get_price_srv()->get_price_ext_dao();
                                        if ($price_ext_obj = $price_ext_dao->get(array("sku" => $sku, "platform_id" => $this->platform_id))) {
                                            $price_ext_obj->set_action("E");
                                            $price_ext_obj->set_remark(null);
                                            $price_ext_obj->set_ext_qty(0);
                                            $price_ext_obj->set_note(NULL);
                                            $price_ext_obj->set_ext_status("E");

                                            if (($response = $price_ext_dao->update($price_ext_obj)) === FALSE) {
                                                $error_msg = "{$this->platform_id} rakuten_service line_no: " . __LINE__ . ". Problem updating ext_item_id in db price_extend." . "\nDB Error Message:" . $this->get_price_srv()->get_price_ext_dao()->db->_error_message();
                                                $this->send_notification_email("DS", $error_msg);
                                                $debug_msg .= "<pre>ERROR DEBUG: $error_msg</pre>";
                                            } else {
                                                $debug_msg .= "<pre>DEBUG: Updated in db. sku: $sku </pre><hr></hr>";
                                                $de_list[] = array($master_sku, $sku, $vb_name, $item_obj->get_price(), $prod_margin);

                                                //return TRUE;
                                            }
                                        }

                                    }
                                    $_SESSION["NOTICE"] .= $res["message"];
                                    continue;
                                } else {
                                    $debug_msg .= "<pre>Listing on VB but not RAKU: " . $sku . "</pre>";
                                }
                            }
                        }


                        set_time_limit(60);
                        //  echo "<pre>Processing {$item_obj->get_sku()}</pre>";

                    } else {
                        $iteminfo = $this->format_arr_to_str($item_obj);
                        $error_msg = "rakuten_service line " . __LINE__ . " {$this->process} No sku. \nVB item info: \n$iteminfo.";
                        $this->send_notification_email("DS", $error_msg);
                        return FALSE;
                    }
                }
            }
            if (!$test) {
                if ($delist_count > 0) {
                    $csv_string = $this->gen_and_email_csv($de_list, "delist_success", $this->platform_id);
                } else {
                    $error_msg = "{$this->platform_id} delisting result: There is no SKU that have profit margin below " . $selected_margin;
                    $this->send_notification_email("DS", $error_msg);
                    $debug_msg .= "<pre>DEBUG: $error_msg</pre>";
                    echo $debug_msg;
                }
            } else {
                if ($delist_count > 0) {
                    $csv_string = $this->gen_and_email_csv($de_list, "echo_file", $this->platform_id);
                } else {
                    $error_msg = "{$this->platform_id} delisting result: There is no SKU that have profit margin below " . $selected_margin;
                    $this->send_notification_email("DS", $error_msg);
                    $debug_msg .= "<pre>DEBUG: $error_msg</pre>";
                    echo $debug_msg;
                }

            }

        }

    }

    private function gen_and_email_csv($data, $report_type = "", $platform_id)
    {
        ob_start();
        $csv_string = "";

        if ($data) {
            $fp = fopen('php://output', 'w');
            fputcsv($fp, array('Master SKU', 'SKU', 'Item Name', 'Price', 'Margin'));
            foreach ($data as $fields) {
                if (fputcsv($fp, $fields) === FALSE) {
                    $error_fields = implode(',', $fields);
                    $error_msg = __FILE__ . " Line " . __LINE__ . "Error writing data to file. " . "\nfields: $error_fields";

                    $this->send_notification_email("CSV", $error_msg);
                    echo "<pre>DEBUG: $error_msg</pre>";
                    break;
                }
            }

            $csv_string = ob_get_clean();
        }

        if ($report_type == "echo_file") {
            header('Content-Type: text/csv; charset=utf-8');
            header("Content-Disposition: attachment; filename=report_compiled_" . date('Ymdhis') . ".csv");
            echo $csv_string;
        } else {
            $email_list = "serene.chung@eservicesgroup.com,romuald@eservicesgroup.com,itsupport@eservicesgroup.net, celine@eservicesgroup.com, jerrylim@eservicesgroup.com";
            $this->send_report_email($csv_string, $report_type, $platform_id . "_report_compiled_" . date('Ymdhis') . ".csv", $email_list);

        }
        // return $csv_string;
    }

    public function end_item($country_id = "ES", $sku)
    {
        // $this->test = $test;
        $this->now_time = date("YmdHis", mktime());
        $this->platform_id = "RAKU" . strtoupper($country_id);  #e.g. RAKUES
        $this->country_id = strtoupper($country_id);
        $this->isdev = strpos($_SERVER["HTTP_HOST"], "admindev") === false ? FALSE : TRUE;

        $rs_deleteitem = $this->rakuten_delete_product($country_id, $sku);
        return $rs_deleteitem;
    }

    private function rakuten_delete_product($country_id = "ES", $sku)
    {
        $product["baseSku"] = $sku;
        $this->process = $apitype = "/product/delete";
        $requestarr["product"] = $product;

        if ($rs_deleteitem = $this->call_rakuten_api("post", $apitype, $requestarr)) {
            if (!$rs_deleteitem["errors"])
                return array("response" => 1, "message" => "Delete Rakuten Listing - Success");
        } else {
            return array("response" => 0, "message" => "FAIL (details in email) - {$this->error_msg}");
        }
    }

    public function add_items($country_id = "ES", $sku = "", $test = FALSE)
    {
        // http://admindev.valuebasket.com/cron/platform_integration/rakuten_orders/add_items/ES
        $this->test = $test;
        $this->now_time = date("YmdHis", mktime());
        $this->platform_id = "RAKU" . strtoupper($country_id);  #e.g. RAKUES
        $this->country_id = strtoupper($country_id);
        $this->isdev = strpos($_SERVER["HTTP_HOST"], "admindev") === false ? FALSE : TRUE;

        switch ($this->country_id) {
            case 'ES':
                $this->lang_id = "es";
                break;

            default:
                $this->lang_id = "es";
                break;
        }

        $option["desc_lang"] = $this->lang_id;
        $where = array("platform_id" => $this->platform_id, "vpoe.website_quantity > " => 0, "prod_status" => 2, "listing_status" => 'L', "ext_item_id IS NULL" => NULL/*, "ext_ref_1 IS NOT NULL"=>NULL*/);
        if ($sku)
            $where = array_merge($where, array("sku" => $sku));

        if ($total = $this->get_product_srv()->get_dao()->get_prod_overview_extended($where, array("num_rows" => 1))) {
            echo "<pre>DEBUG: no. of product(s) to update: $total.</pre>";
            $loop_no = 1;
            // Get list of items to list on Rakuten
            if ($add_item_list = $this->get_product_srv()->get_dao()->get_prod_overview_extended($where, $option)) {
                foreach ($add_item_list as $item_obj) {
                    echo "<hr></hr><pre>Listing on Rakuten$country_id. Loop " . $loop_no++ . "</pre>";

                    if ($sku = $item_obj->get_sku()) {
                        $apitype = "/inventory/get";
                        $get_inventory_arr["baseSkus"] = $sku;

                        // Check if item exist on Rakuten
                        $inventory = $this->call_rakuten_api("get", $apitype, $get_inventory_arr, false, false);
                        if ($inventory["totalCount"] > 0) {
                            echo "<pre>$sku exists on Rakuten</pre>";
                            $response_insertextitemid = $this->insert_ext_item_id($item_obj->get_sku(), $item_obj->get_sku());
                            continue;
                        }

                        set_time_limit(60);
                        echo "<pre>Processing {$item_obj->get_sku()}</pre>";
                        $response = $this->process_add_item($item_obj);
                        if ($response === TRUE) {
                            # update rakuten itemcode into db price_extend
                            # rakuten doesn't return raku-SKU, so we insert vbSKU into ext_item_id
                            $response_insertextitemid = $this->insert_ext_item_id($item_obj->get_sku(), $item_obj->get_sku());

                        }
                    } else {
                        $iteminfo = $this->format_arr_to_str($item_obj);
                        $error_msg = "rakuten_service line " . __LINE__ . " {$this->process} No sku. \nVB item info: \n$iteminfo.";
                        $this->send_notification_email("AI", $error_msg);
                        return FALSE;
                    }
                }
            }
        } else {
            echo "DEBUG: no newly-listed products to update";
        }
    }

    private function insert_ext_item_id($sku, $ext_item_id)
    {
        #update db price_extend once successfully listed on rakuten
        $price_ext_dao = $this->get_price_srv()->get_price_ext_dao();
        if ($price_ext_obj = $price_ext_dao->get(array("sku" => $sku, "platform_id" => $this->platform_id))) {
            $price_ext_obj->set_ext_status('L');
            $price_ext_obj->set_ext_item_id($ext_item_id);
            $price_ext_obj->set_action("P");
            if ($this->ext_qty != "") {
                $price_ext_obj->set_ext_qty($this->ext_qty);
                $price_ext_obj->set_ext_ref_2($this->ext_ref_2);
            }
            if ($this->prod_name)
                $price_ext_obj->set_title($this->prod_name);

            if (($response = $price_ext_dao->update($price_ext_obj)) === FALSE) {
                $error_msg = "{$this->platform_id} rakuten_service line_no: " . __LINE__ . ". Problem updating ext_item_id in db price_extend." . "\nDB Error Message:" . $this->get_price_srv()->get_price_ext_dao()->db->_error_message();
                $this->send_notification_email("AI", $error_msg);  #add_items_error
                echo "<pre>ERROR DEBUG: $error_msg</pre>";
            } else {
                echo "<pre>DEBUG: Updated in db. sku: $sku </pre><hr></hr>";
                return TRUE;
            }
        } else {
            $price_ext_obj = $price_ext_dao->get();
            $price_ext_obj->set_sku($sku);
            $price_ext_obj->set_ext_status('L');
            $price_ext_obj->set_ext_item_id($ext_item_id);
            $price_ext_obj->set_ext_qty($this->ext_qty);
            $price_ext_obj->set_platform_id($this->platform_id);
            if ($this->prod_name)
                $price_ext_obj->set_title($this->prod_name);

            if (($response = $price_ext_dao->insert($price_ext_obj)) === FALSE) {
                $error_msg = "{$this->platform_id} rakuten_service line_no: " . __LINE__ . ". Problem inserting ext_item_id in db price_extend <$sku>." . "\nDB Error Message:" . $this->get_price_srv()->get_price_ext_dao()->db->_error_message();
                $this->send_notification_email("AI", $error_msg);  #add_items_error
                echo "<pre>ERROR DEBUG: $error_msg</pre>";
            } else {
                echo "<pre>DEBUG: Updated in db. sku: $sku </pre><hr></hr>";
                return TRUE;
            }
        }

        return FALSE;
    }

    private function process_add_item($item_obj)
    {
        $this->process = "process_add_item";
        $product = array();
        $this->ext_qty = $this->ext_ref_2 = "";

        $sku = $item_obj->get_sku();
        $subcatid = $item_obj->get_sub_cat_id();
        $platform_id = $item_obj->get_platform_id();
        if (!$sku) {
            $iteminfo = $this->format_arr_to_str($item_obj);
            $error_msg = "rakuten_service line " . __LINE__ . " {$this->process} No sku. \nVB item info: \n$iteminfo.";
            $this->send_notification_email("AI", $error_msg);
        }

        if ($subcatid == "") {
            $error_msg = "rakuten_service line " . __LINE__ . " {$this->process} No VB subcatid for sku <$sku>.";
            $this->send_notification_email("AI", $error_msg);
            return FALSE;
        } else {
            // get Rakuten Universal Category ID from db
            if (!($rakucatid = $this->get_cat_mapping_dao()->get_external_catmapping("RAKUTEN", 2, $subcatid, $this->lang_id, $this->country_id)->ext_id)) {
                $vbsubcatname = $this->get_product_srv()->get_cat_dao()->get(array("id" => $subcatid))->get_name();

                $error_msg = "rakuten_service line " . __LINE__ . " {$this->process} No rakutenCategoryId found for sku<$sku>, VB subcatid<$subcatid-$vbsubcatname>. Do not add products from this category till you request for new category mapping. \nDB error: " . $this->get_cat_mapping_dao()->db->_error_message();
                $this->send_notification_email("AI", $error_msg);
                return FALSE;
            } else {
                # ====================================================================
                # REQUIRED INFORMATION. IF ERROR RETRIEVING, DON'T CONTINUE.
                if (!($login = $this->get_login_info())) {
                    $error_msg = "rakuten_service line " . __LINE__ . " Process: {$this->process} Cannot retrieve login info for sku <$sku>.";
                    $this->send_notification_email("AI", $error_msg);
                    echo $error_msg;
                    return FALSE;
                }

                $price_ext_dao = $this->get_price_srv()->get_price_ext_dao();
                $price_ext_obj = $price_ext_dao->get(array("platform_id" => $platform_id, "sku" => $sku));
                if ($price_ext_obj) {
                    $ext_desc = $price_ext_obj->get_ext_desc();
                    if (!$ext_desc) {
                        # if cannot get customised desc, then try to get from item_obj
                        $ext_desc = $item_obj->get_detail_desc();
                    }
                }

                if (!$ext_desc) {
                    $error_msg = "rakuten_service line " . __LINE__ . " Process: {$this->process} No desc for sku <$sku>.";
                    $this->send_notification_email("AI", $error_msg);
                    echo $error_msg;
                    return FALSE;
                }

                if (strlen($ext_desc) > 15000) {
                    $error_msg = "rakuten_service line " . __LINE__ . " Process: {$this->process} Desc longer than 15000 char for sku <$sku>.";
                    $this->send_notification_email("AI", $error_msg);
                    echo $error_msg;
                    return FALSE;
                }

                if ($item_obj->get_current_platform_price() == "") {
                    $error_msg = "rakuten_service line " . __LINE__ . " Process: {$this->process} No price for sku <$sku>.";
                    $this->send_notification_email("AI", $error_msg);
                    echo $error_msg;
                    return FALSE;
                }
                # END REQUIRED INFORMATION.
                # ====================================================================

                ### putting prod_name through htmlentities caused encoding prob on Rakuten
                if (!($prod_name = $item_obj->get_title()))
                    $prod_name = $item_obj->get_prod_name();
                // $detail_desc = htmlentities(substr($ext_desc, 0, 15000), ENT_NOQUOTES, 'UTF-8');


                //Add only english
                $lang_restricted = $item_obj->get_lang_restricted();
                $osd_lang_list = $this->get_product_srv()->get_lang_osd_list();
                foreach ($osd_lang_list as $osd_lang_id => $bit) {

                    if ($lang_restricted & (1 << $bit)) {
                    } else {
                        if ($osd_lang_id == "ES")
                            $detail_desc = "<span style='font-weight:bold;color:#DD1313'>Modelo internacional: este producto no incluye idioma español. Disponible idioma inglés.</span><br>";
                    }
                }
                $detail_desc .= $ext_desc;

                $this->prod_name = $prod_name;

                // PRODUCT
                $product["baseSku"] = $sku;
                $product["title"] = array("value" => $prod_name);
                $product["description"] = array("value" => $detail_desc);

                // $imageurl = "";
                // if($imageext = $item_obj->get_image())
                //  $imageurl = "http://cdn.valuebasket.com/808AA1/vb/images/product/$sku.$imageext";

                if ($item_obj->get_brand_name())
                    $product["brand"] = htmlentities($item_obj->get_brand_name(), ENT_NOQUOTES, 'UTF-8');

                //globalTradeItems
                if ($item_obj->get_ext_ref_3()) {
                    // Global Trade Item Number (GTIN): type: EAN / ISBN / JAN / UPC
                    list($gtintype, $gtinno) = explode('=', $item_obj->get_ext_ref_3());
                    if ($gtintype && $gtinno) {
                        $product["variants"][0]["globalTradeItems"][0]["globalTradeItemType"] = strtoupper($gtintype);
                        $product["variants"][0]["globalTradeItems"][0]["globalTradeItemNumber"] = trim($gtinno);
                    }
                }

                // we don't have multiple variants, so set array at [0] when neede
                $product["variants"][0]["sku"] = $sku;
                $product["variants"][0]["variantListedShops"][0]["shopURL"] = $login["shopurl"];
                $product["variants"][0]["variantListedShops"][0]["marketplaceIdentifier"] = strtolower($this->country_id);
                $product["variants"][0]["variantListedShops"][0]["price"] = $item_obj->get_current_platform_price();

                // rrp
                $listedprice = $this->calculate_rrp($sku, $this->platform_id);
                if ($listedprice)
                    $product["variants"][0]["variantListedShops"][0]["listedPrice"] = $listedprice;

                // -1: unlimited qty
                // if ext_qty is empty string, we get website_quantity
                $update_price_extend = array();
                $this->ext_qty = $item_obj->get_ext_qty();
                if ($item_obj->get_ext_qty() == "") {
                    if ($item_obj->get_website_quantity() != "")
                        $this->ext_qty = $item_obj->get_website_quantity();
                    else
                        $this->ext_qty = "-1";
                    $update_price_extend["ext_qty"] = $this->ext_qty;
                }
                $product["variants"][0]["variantListedShops"][0]["inventory"]["quantity"] = $this->ext_qty;

                // will error if send in limitPurchaseQuantity = 0 or empty string
                // thus, only send in limitPurchaseQuantity if ext_qty > 0 or -1
                // if limitPurchaseQuantity left empty, rakuten defaults it to -1 (unlimited)
                if ($this->ext_qty > 0 || $this->ext_qty == -1) {
                    if ($item_obj->get_ext_ref_2() > 0 || $item_obj->get_ext_ref_2() == -1) {
                        $product["variants"][0]["variantListedShops"][0]["inventory"]["limitPurchaseQuantity"] = $item_obj->get_ext_ref_2();
                    } else {
                        $product["variants"][0]["variantListedShops"][0]["inventory"]["limitPurchaseQuantity"] = -1;
                        $update_price_extend["ext_ref_2"] = -1;
                    }
                    $this->ext_ref_2 = $product["variants"][0]["variantListedShops"][0]["inventory"]["limitPurchaseQuantity"];
                } elseif ($this->ext_qty == 0 && $item_obj->get_ext_ref_2() != "") {
                    $this->ext_ref_2 = "";
                    $update_price_extend["ext_ref_2"] = "";
                }

                $product["variants"][0]["variantListedShops"][0]["inventory"]["returnProductsToInventoryOnCanceledOrders"] = FALSE;

                $product["productListedShops"][0]["marketplaceIdentifier"] = strtolower($this->country_id);
                $product["productListedShops"][0]["shopURL"] = $login["shopurl"];
                $product["productListedShops"][0]["marketplaceRakutenCategoryId"] = $rakucatid;

                $product["productListedShops"][0]["displayStartDate"] = date("Y-m-d\TH:i:s.000\Z", strtotime($this->now_time));
                $product["productListedShops"][0]["freeShipping"] = "true";
                $product["productListedShops"][0]["shopCategoryIds"] = (array)$item_obj->get_ext_ref_4();

                $this->process = $apitype = "/product/add";
                $requestarr["product"] = $product;

                if ($rs_addproduct = $this->call_rakuten_api("post", $apitype, $requestarr)) {
                    if ($rs_addproduct["product"]) {
                        // must update due to re-constructed data
                        if ($update_price_extend) {
                            $price_ext_dao = $this->get_price_srv()->get_price_ext_dao();
                            $price_ext_obj = $price_ext_dao->get(array("platform_id" => $this->platform_id, "sku" => $sku));
                            foreach ($update_price_extend as $key => $value) {
                                $method = "set_{$key}";
                                if (method_exists($price_ext_obj, $method)) {
                                    $price_ext_obj->$method($value);
                                }

                                if ($price_ext_dao->update($price_ext_obj) === FALSE) {
                                    $error_msg = "rakuten_service line " . __LINE__ . " {$this->process} Error updating price_extend for sku <$sku - {$this->platform_id}> @ {$this->now_time}. \nDB error: {$price_ext_dao->db->_error_message()}";
                                    echo "ERROR $error_msg";
                                    $this->send_notification_email("AI", $error_msg);
                                    return FALSE;
                                }
                            }
                        }
                        return TRUE;
                    }

                    return FALSE;
                } else {
                    $error_msg = "rakuten_service line " . __LINE__ . " {$this->process} No data returned for sku <$sku - {$this->platform_id}> @ {$this->now_time}.";
                    echo "ERROR $error_msg";
                    $this->send_notification_email("AI", $error_msg);
                    return FALSE;
                }
            }
        }
        return FALSE;
    }

    public function get_cat_mapping_dao()
    {
        return $this->cat_mapping_dao;
    }

    private function calculate_rrp($sku, $platform_id)
    {
        $default_rrp_factor = 1.34;
        $price_obj = $this->get_price_srv()->get(array("sku" => $sku, "platform_id" => $platform_id));
        if (!$price_obj) {
            $error_msg = "rakuten_service line " . __LINE__ . " Process: {$this->process}. No product info." . "\nDB Error Message:" . $this->get_price_srv()->get_dao()->db->_error_message();
            $this->send_notification_email("DE", $error_msg);
            echo $error_msg;
            return FALSE;
        } else {
            if ($price_obj->get_fixed_rrp() == 'Y') {
                return number_format($default_rrp_factor * $price_obj->get_price(), 2, '.', '');
            } elseif ($price_obj->get_fixed_rrp() == 'N') {
                if ($price_obj->get_rrp_factor() && is_numeric($price_obj->get_rrp_factor())) {
                    if ($price_obj->get_rrp_factor() < 10)
                        return number_format($price_obj->get_rrp_factor() * $price_obj->get_price(), 2, '.', '');
                    else
                        return number_format($price_obj->get_rrp_factor(), 2, '.', '');     # if >= 10, rrp_factor field is the actual listedprice
                } else {
                    return number_format($default_rrp_factor * $price_obj->get_price(), 2, '.', '');
                }
            }
        }
    }

    public function bulk_update_item($country_id = "ES")
    {
        $array1 = array(
            "10122-AA-NA",
            "10123-AA-NA",
            "10129-AA-NA",
            "10130-AA-NA",
            "10135-AA-NA",
            "10141-AA-NA",
            "10143-AA-NA",
            "10146-AA-NA"
        );
        if ($_GET["no"]) {
            $updatearr = ${"array" . $_GET["no"]};

            if ($updatearr) {
                foreach ($updatearr as $sku) {
                    echo "$sku <br>";
                    $this->update_item("ES", $sku);
                }

            }
        }
        echo "DONE {$_GET["no"]}";

    }

    public function update_item($country_id = "ES", $sku, $update_type = "update_pricingtool")
    {
        /* =======================================================================================
        *   http://admincentre.valuebasket.com/cron/platform_integration/rakuten_orders/update/ES/
        *   $update_type = "update_pricingtool" / "update_autoprice" ; mainly for debug differentiation
        ======================================================================================= */
        $this->process = "update_item[$update_type]";
        $product = array();
        $this->test = $test;
        $this->now_time = date("YmdHis", mktime());
        $this->platform_id = "RAKU" . strtoupper($country_id);  #e.g. RAKUES
        $this->country_id = strtoupper($country_id);
        $this->isdev = strpos($_SERVER["HTTP_HOST"], "admindev") === false ? FALSE : TRUE;

        switch ($this->country_id) {
            case 'ES':
                $this->lang_id = "es";
                break;

            default:
                $this->lang_id = "es";
                break;
        }

        $where = array("platform_id" => $this->platform_id, "vpoe.sku" => $sku, "ext_item_id IS NOT NULL" => NULL);
        $option["desc_lang"] = $this->lang_id;
        $option["limit"] = 1;
        if ($item_obj = $this->get_product_srv()->get_dao()->get_prod_overview_extended($where, $option)) {
            # ====================================================================
            # CHECK REQUIRED INFORMATION

            if (!($login = $this->get_login_info())) {
                $error_msg = "rakuten_service line " . __LINE__ . " Process: {$this->process} Cannot retrieve login info for sku <$sku>.";
                $this->send_notification_email("UI", $error_msg);
                echo $error_msg;
                return FALSE;
            }

            $price_ext_dao = $this->get_price_srv()->get_price_ext_dao();
            $price_ext_obj = $price_ext_dao->get(array("platform_id" => $this->platform_id, "sku" => $sku));
            if ($price_ext_obj) {
                $ext_desc = $price_ext_obj->get_ext_desc();
                if (!$ext_desc) {
                    $ext_desc = $item_obj->get_detail_desc();
                }
            }

            if (!$ext_desc) {
                $error_msg = "rakuten_service line " . __LINE__ . " Process: {$this->process} No desc for sku <$sku>.";
                $this->send_notification_email("UI", $error_msg);
                echo $error_msg;
                return FALSE;
            }

            if (strlen($ext_desc) > 15000) {
                $error_msg = "rakuten_service line " . __LINE__ . " Process: {$this->process} Desc longer than 15000 char for sku <$sku>.";
                $this->send_notification_email("AI", $error_msg);
                echo $error_msg;
                return FALSE;
            }

            if ($item_obj->get_current_platform_price() == "" || $item_obj->get_current_platform_price() == 0) {
                $price = $item_obj->get_current_platform_price();
                $error_msg = "rakuten_service line " . __LINE__ . " Process: {$this->process} Price for sku <$sku> is empty or 0 <pr: $price>.";
                $this->send_notification_email("UI", $error_msg);
                echo $error_msg;
                return FALSE;
            }

            $subcatid = $item_obj->get_sub_cat_id();
            // get Rakuten Universal Category ID from db
            if (!($rakucatid = $this->get_cat_mapping_dao()->get_external_catmapping("RAKUTEN", 2, $subcatid, $this->lang_id, $this->country_id)->ext_id)) {
                $error_msg = "rakuten_service line " . __LINE__ . " {$this->process} No rakutenCategoryId found for sku<$sku>, VB subcatid<$subcatid> \nDB error: " . $this->get_cat_mapping_dao()->db->_error_message();
                $this->send_notification_email("AI", $error_msg);
                return FALSE;
            }

            # END REQUIRED INFORMATION.
            # ====================================================================

            ### putting prod_name through htmlentities caused encoding prob on Rakuten
            if (!($prod_name = $item_obj->get_title()))
                $prod_name = $item_obj->get_prod_name();
            // $detail_desc = htmlentities(substr($ext_desc, 0, 15000), ENT_NOQUOTES, 'UTF-8');

            //Add only english
            $lang_restricted = $item_obj->get_lang_restricted();
            $osd_lang_list = $this->get_product_srv()->get_lang_osd_list();
            foreach ($osd_lang_list as $osd_lang_id => $bit) {

                if ($lang_restricted & (1 << $bit)) {
                } else {
                    if ($osd_lang_id == "ES")
                        $detail_desc = "<span style='font-weight:bold;color:#DD1313'>Modelo internacional: este producto no incluye idioma español. Disponible idioma inglés.</span><br>";
                }
            }
            $detail_desc .= $ext_desc;

            // PRODUCT
            $product["baseSku"] = $sku;
            $product["title"] = array("value" => $prod_name);
            $product["description"] = array("value" => $detail_desc);
            if ($imageext = $item_obj->get_image())
                $imageurl = "http://cdn.valuebasket.com/808AA1/vb/images/product/$sku.$imageext";

            if ($item_obj->get_brand_name())
                $product["brand"] = htmlentities($item_obj->get_brand_name(), ENT_NOQUOTES, 'UTF-8');

            //globalTradeItems
            if ($item_obj->get_ext_ref_3()) {
                // Global Trade Item Number (GTIN): type: EAN / ISBN / JAN / UPC
                list($gtintype, $gtinno) = explode('=', $item_obj->get_ext_ref_3());
                if ($gtintype && $gtinno) {
                    $product["variants"][0]["globalTradeItems"][0]["globalTradeItemType"] = $gtintype;
                    $product["variants"][0]["globalTradeItems"][0]["globalTradeItemNumber"] = trim($gtinno);
                }
            } else {
                # GTIN HOW TO REMOVE???
                $product["variants"][0]["globalTradeItems"] = array();
                // $product["variants"][0]["globalTradeItems"][0]["globalTradeItemNumber"] = "";
            }

            // we don't have multiple variants, so set array at [0] when needed
            $product["variants"][0]["sku"] = $sku;
            $product["variants"][0]["variantListedShops"][0]["shopURL"] = $login["shopurl"];
            $product["variants"][0]["variantListedShops"][0]["marketplaceIdentifier"] = strtolower($this->country_id);
            $product["variants"][0]["variantListedShops"][0]["price"] = $item_obj->get_current_platform_price();

            // rrp
            $listedprice = $this->calculate_rrp($sku, $this->platform_id);
            if ($listedprice)
                $product["variants"][0]["variantListedShops"][0]["listedPrice"] = $listedprice;
            else
                $product["variants"][0]["variantListedShops"][0]["listedPrice"] = "";

            // -1: unlimited qty
            // if ext_qty is empty string, we get website_quantity
            $update_price_extend = array();
            $this->ext_qty = $item_obj->get_ext_qty();
            if ($item_obj->get_ext_qty() == "") {
                if ($item_obj->get_website_quantity() != "")
                    $this->ext_qty = $item_obj->get_website_quantity();
                else
                    $this->ext_qty = "-1";
                $update_price_extend["ext_qty"] = $this->ext_qty;
            }
            $product["variants"][0]["variantListedShops"][0]["inventory"]["quantity"] = $this->ext_qty;

            // will error if send in limitPurchaseQuantity = 0 or empty string
            // thus, only send in limitPurchaseQuantity if ext_qty > 0 or -1
            // if limitPurchaseQuantity left empty, rakuten defaults it to -1 (unlimited)
            if ($this->ext_qty > 0 || $this->ext_qty == -1) {
                if ($item_obj->get_ext_ref_2() > 0 || $item_obj->get_ext_ref_2() == -1) {
                    $product["variants"][0]["variantListedShops"][0]["inventory"]["limitPurchaseQuantity"] = $item_obj->get_ext_ref_2();
                } else {
                    $product["variants"][0]["variantListedShops"][0]["inventory"]["limitPurchaseQuantity"] = -1;
                    $update_price_extend["ext_ref_2"] = -1;
                }
                $this->ext_ref_2 = $product["variants"][0]["variantListedShops"][0]["inventory"]["limitPurchaseQuantity"];
            } elseif ($this->ext_qty == 0 && $item_obj->get_ext_ref_2() != "") {
                $this->ext_ref_2 = "";
                $update_price_extend["ext_ref_2"] = "";
            }

            $product["productListedShops"][0]["marketplaceIdentifier"] = strtolower($this->country_id);
            $product["productListedShops"][0]["shopURL"] = $login["shopurl"];
            $product["productListedShops"][0]["marketplaceRakutenCategoryId"] = $rakucatid;

            $product["productListedShops"][0]["displayStartDate"] = date("Y-m-d\TH:i:s.000\Z", strtotime($this->now_time));
            $product["productListedShops"][0]["freeShipping"] = "true";

            # VB's own category in Rakuten's store
            if ($item_obj->get_ext_ref_4())
                $product["productListedShops"][0]["shopCategoryIds"] = (array)$item_obj->get_ext_ref_4();
            // $product["productListedShops"][0]["shopCategoryIds"] = array("22222");   #EXAMPLE!!!

            $this->process = $apitype = "/product/update";
            $requestarr["product"] = $product;

            // echo "<pre>Updating item on Rakuten$country_id</pre>";
            // echo "<pre>"; var_dump($product); die();

            if ($rs_updateitem = $this->call_rakuten_api("post", $apitype, $requestarr)) {
                if (!$rs_updateitem["errors"]) {
                    // must update due to re-constructed data
                    if ($update_price_extend) {
                        $price_ext_dao = $this->get_price_srv()->get_price_ext_dao();
                        $price_ext_obj = $price_ext_dao->get(array("platform_id" => $this->platform_id, "sku" => $sku));
                        foreach ($update_price_extend as $key => $value) {
                            $method = "set_{$key}";
                            if (method_exists($price_ext_obj, $method)) {
                                $price_ext_obj->$method($value);
                            }

                            if ($price_ext_dao->update($price_ext_obj) === FALSE) {
                                $error_msg = "rakuten_service line " . __LINE__ . " Process: {$this->process}. Error updating price_extend for sku <$sku> - platform_id <{$this->platform_id}>."
                                    . "\nDB Error Message:" . $price_ext_dao->db->_error_message();
                                $this->send_notification_email("UI", $error_msg);
                                echo $error_msg;
                                return array("response" => 0, "message" => $error_msg);
                            }
                        }
                    }
                    if ($this->set_product_image($sku, $imageurl))
                        return array("response" => 1, "message" => "$update_type Rakuten Listing - Success");
                    else
                        return array("response" => 0, "message" => "FAIL (details in email) - {$this->error_msg}");
                }
            } else {
                return array("response" => 0, "message" => "FAIL (details in email) - {$this->error_msg}");
            }
        } else {
            $error_msg = "rakuten_service line " . __LINE__ . " Process: {$this->process}. No product info." . "\nDB Error Message:" . $this->get_product_srv()->get_dao()->db->_error_message();
            $this->send_notification_email("UI", $error_msg);
            echo $error_msg;
            return array("response" => 0, "message" => $error_msg);
        }
    }

    public function get_inventory_by_single_sku($country_id = "ES", $sku, $stocktype = "AllStocks")
    {
        /*
            ActiveStockOnly: In Stock items (quantity > 0)
            AllStocks: Both In Stock and Out of Stock items
            OutOfStockOnly: Out of Stock items (quantity = 0)
        */

        $this->now_time = date("YmdHis", mktime());
        $this->platform_id = "RAKU" . strtoupper($country_id);  #e.g. RAKUES
        $this->country_id = strtoupper($country_id);
        $this->isdev = strpos($_SERVER["HTTP_HOST"], "admindev") === false ? FALSE : TRUE;

        $rs_inventory = $this->rakuten_get_inventory($country_id, $sku, $stocktype);

        return $rs_inventory;
    }

    private function rakuten_get_inventory($country_id, $sku, $stocktype)
    {
        $requestarr["baseSkus"] = $sku;
        $requestarr["stockType"] = $stocktype;
        $this->process = $apitype = "/inventory/get";
        if (!$rs_getinventory["errors"]) {
            if ($rs_getinventory = $this->call_rakuten_api("get", $apitype, $requestarr)) {
                if ($list = $rs_getinventory["products"]) {
                    foreach ($list as $v) {
                        if ($v["variants"]) {
                            foreach ($v["variants"] as $variantv) {
                                $inventory_count = $variantv["variantListedShops"][0]["inventory"]["quantity"];
                                return array("response" => 1, "message" => $inventory_count);
                            }
                        }
                    }
                }
            }
        } else {
            return array("response" => 0, "message" => "FAIL (details in email) - {$this->error_msg}");
        }
    }

    private function format_arr_to_csv($get_type = "value", $dataarr = array(), $filter_wanted = array())
    {
        /*
            $get_type will give you either the value or key of array in the return string,
            $get_type = key: returns array key in csv string; usually used for headers
                            e.g. "Name, Address, Tel, Orderno"
            $get_type = value: returns the value in array; for data
                            e.g. "Jane Doe, 112 Neverland, +1 5854252, SO562520".
        */
        if (empty($dataarr) || !is_array($dataarr)) {
            return;
        }

        foreach ($dataarr as $key => $value) {
            if (!empty($filter_wanted)) {
                # if key doesn't exist in wanted filter, skip.
                if (!in_array($key, $filter_wanted))
                    continue;
            }

            if (is_array($value)) {
                $csv_str .= $this->format_arr_to_csv($get_type, $value);
            } else {
                if (!empty(${$get_type}))
                    $text = '"' . nl2br(str_replace('"', '""', ${$get_type})) . '"';
                $csv_str .= "$text,";
            }
        }

        return $csv_str;
    }

    private function display_xml_error($error, $xml)
    {
        $return = $xml[$error->line - 1] . "<br />";
        $return .= str_repeat('-', $error->column) . "<br />";

        switch ($error->level) {
            case LIBXML_ERR_WARNING:
                $return .= "This is a  libxml Warning $error->code: ";
                break;
            case LIBXML_ERR_ERROR:
                $return .= "This is a libxml Error $error->code: ";
                break;
            case LIBXML_ERR_FATAL:
                $return .= "This is a libxml Error $error->code: ";
                break;
        }

        $return .= "<br />" . trim($error->message) .
            "<br />  Line: $error->line" .
            "<br />  Column: $error->column";

        if ($error->file) {
            $return .= "<br />  File: $error->file";
        }

        return $return;
    }


}

