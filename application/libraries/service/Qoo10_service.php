<?php
defined('BASEPATH') OR exit('No direct script access allowed');

DEFINE ('PLATFORM_TYPE', 'QOO10');


/*
    =================================================
    IMPORTANT READ ME!
    =================================================
    Running functions on dev will update QOO10 live.
    When testing, search for '#####' in your functions
    and comment out the line that sends to QOO10.
    Then, hardcode Qoo10 response code (usually success = 0)
    to test.

    Root ticket SBF #2538
    =================================================
*/

class Qoo10_service
{
    private $notification_email = "celine@eservicesgroup.com, romuald@eservicesgroup.com";
    public function __construct()
    {
        include_once(APPPATH."libraries/service/Order_integration_service.php");
        $this->set_order_integration(new Order_integration_service());
        include_once(APPPATH."libraries/service/Client_integration_service.php");
        $this->set_client_integration(new Client_integration_service());

        include_once(APPPATH."libraries/service/Http_info_service.php");
        $this->set_http_info(new Http_info_service());
        include_once(APPPATH."libraries/service/Schedule_job_service.php");
        $this->set_sj_srv(new Schedule_job_service());
        include_once (APPPATH."libraries/dao/So_item_dao.php");
        $this->set_so_item_dao(new So_item_dao());
        include_once (APPPATH."libraries/dao/So_dao.php");
        $this->set_so_dao(new So_dao());
        include_once(APPPATH."libraries/service/So_service.php");
        $this->set_so_srv(new So_service());
        include_once (APPPATH."libraries/service/Product_service.php");
        $this->set_product_srv(new Product_service());
        include_once(APPPATH . 'libraries/service/Price_service.php');
        $this->set_price_srv(New Price_service());

        include_once(APPPATH."libraries/service/Context_config_service.php");
        $this->set_config_srv(new Context_config_service());
    }

    public function get_order_integration()
    {
        return $this->order_integration;
    }

    public function set_order_integration($value)
    {
        $this->order_integration = $value;
    }

    public function get_client_integration()
    {
        return $this->client_integration;
    }

    public function set_client_integration($value)
    {
        $this->client_integration = $value;
    }

    public function get_http_info()
    {
        return $this->http_info;
    }

    public function set_http_info($value)
    {
        $this->http_info = $value;
    }

    public function get_sj_srv()
    {
        return $this->sj_srv;
    }

    public function set_sj_srv($value)
    {
        $this->sj_srv = $value;
    }

    public function get_so_item_dao()
    {
        return $this->so_item_dao;
    }

    public function set_so_item_dao(Base_dao $dao)
    {
        $this->so_item_dao = $dao;
    }

    public function get_so_dao()
    {
        return $this->so_dao;
    }

    public function set_so_dao(Base_dao $value)
    {
        $this->so_dao = $value;
    }

    public function get_so_srv()
    {
        return $this->so_srv;
    }

    public function set_so_srv($value)
    {
        $this->so_srv = $value;
    }


    public function get_product_srv()
    {
        return $this->product_srv;
    }

    public function set_product_srv($value)
    {
        $this->product_srv = $value;
    }

    public function get_price_srv()
    {
        return $this->price_srv;
    }

    public function set_price_srv($value)
    {
        $this->price_srv = $value;
    }

    public function get_config_srv()
    {
        return $this->config_srv;
    }

    public function set_config_srv(Base_service $srv)
    {
        $this->config_srv = $srv;
    }


    public function import_orders($country_id = "SG", $test = FALSE)
    {
        $this->process = "import_orders";
        $this->platform_id = "QOO10".strtoupper($country_id);
        $website = $this->website = "valuebasket";
        $this->http_name = 'QOO10_VALUEBASKET';

        if ($test === TRUE)
        {
            /* this goes to test function with pre-set values */
            /* ONLY RUN ON DEV!!!! */
            // $this->test_import_function($this->platform_id, $website);
        }
        else
        {

            try
            {
                $client_intergration_srv = new Client_integration_service();
                $client_intergration_srv->set_platform_id($this->platform_id);
                $client_intergration_srv->set_website(strtoupper($website));

                $order_integration_srv = new Order_integration_service();
                $order_integration_srv->set_biz_type('QOO10');
                $order_integration_srv->set_platform_id($this->platform_id);
                $order_integration_srv->set_website(strtoupper($website));

                /**
                We must always call get_batch_id before creating any orders using OIS
                Everytime import_orders run, this portion checks if batch_id exists, else it will create one
                **/
                $now_time = date("YmdHis", mktime());
                $batch_remark = "Qoo10_".$website."_".$now_time;
                $batch_id = $order_integration_srv->get_batch_id($batch_remark, $this->platform_id, $website);
                $order_integration_srv->set_batch_id($batch_id);

                if ($batch_id)
                {
                    if($xml = $this->get_qoo10_orders_xml())
                    {
                        $xcnt = $i = 0;
                        $arrcount = count($xml);
                        echo "<pre>Total array count $arrcount  </pre>";
                        foreach ($xml as $key => $xml_obj)
                        {
                            {
                                /**
                                This check is Qoo10 specific.  Check if manual orders have been done
                                using txn_id (Qoo10 orderNo).
                                **/
                                set_time_limit(30);
                                $order_no = (string)$xml_obj->orderNo;
                                $so_dao = $this->get_so_dao();

                                $where["biz_type"] = "QOO10";
                                $where["platform_id"] = $this->platform_id;
                                $so_obj = $so_dao->get_so_by_txn_id($order_no, $where);

                                if ($so_obj)
                                {
                                    echo "<pre>DEBUG LOOP $key: EXISTING ORDER - ORDERNO: $order_no // Pack No {$xml_obj->packNo} will be ignored.</pre> ";
                                    continue;
                                }
                                else
                                {
                                    echo "<pre>DEBUG LOOP $key: PROCESSING - ORDERNO: $order_no // Pack No {$xml_obj->packNo}.</pre> ";
                                    /** This portion extracts relevant info from qoo10 xml **/

                                    //----------- client's info
                                    $client_email       = (string)$xml_obj->buyerEmail;
                                    $ext_client_id      =  $client_email;   # Qoo10 doesn't have its own client_id so we use email
                                    $client_forename    = (string)$xml_obj->buyer;
                                    $client_tel         = (string)$xml_obj->buyerTel;
                                    $client_mobile      = (string)$xml_obj->buyerMobile;
                                    $client_address     = (string)trim($xml_obj->senderAddr);
                                    $client_postcode    = (string)trim($xml_obj->senderZipCode);
                                    $client_country_id  = (string)trim($xml_obj->senderNation);
                                    # sender's add is optional on qoo10's website, so if empty, get shipping address
                                    if (!$client_address)
                                        { $client_address = (string)$xml_obj->shippingAddr;}
                                    if (!$client_postcode)
                                        { $client_postcode = (string)$xml_obj->zipCode; }
                                    if (!$client_country_id)
                                        { $client_country_id = (string)$xml_obj->PaymentNation; }

                                    //----------- delivery info
                                    $del_name           = (string)$xml_obj->receiver;
                                    $del_tel            = (string)$xml_obj->receiverTel;
                                    $del_mobile         = (string)$xml_obj->receiverMobile;
                                    $del_address        = (string)$xml_obj->shippingAddr;
                                    $del_postcode       = (string)$xml_obj->zipCode;
                                    $del_country_id     = (string)$xml_obj->shippingCountry;

                                    //----------- order-related info
                                    /**
                                    A single Qoo10 transaction will generate a cart no on Qoo10 website, which
                                    is called packNo in the xml.
                                    We match packNo to our platform_order_id, which is parallel to our so_no.
                                    **/
                                    $platform_order_id  = (string)$xml_obj->packNo;

                                    echo "<pre>---[[ DEBUG: LOOP$key CREATING INTERFACE - platform_order_id = $platform_order_id ]]---</pre>";

                                    # sku is usually found in qoo10's option or optionCode
                                    $regex = '/(\\d+)(-)([a-z])([a-z])(-)([a-z])([a-z])/is';  #regex for a sku string
                                    $option = (string)$xml_obj->option;
                                    preg_match($regex, $option, $matches);
                                    $prod_sku = $matches[0];
                                    $prod_sku_source = "xml_obj->option";

                                    if(!$prod_sku)
                                    {
                                        $option_code = (string)$xml_obj->optionCode;
                                        preg_match($regex, $option_code, $matches);
                                        $prod_sku = $matches[0];
                                        $prod_sku_source = "xml_obj->optionCode";

                                        if(!$prod_sku)
                                        {
                                            $seller_item_code = (string)$xml_obj->sellerItemCode;
                                            preg_match($regex, $seller_item_code, $matches);
                                            $prod_sku = $matches[0];
                                            $prod_sku_source = "xml_obj->sellerItemCode";

                                            if(!$prod_sku)
                                            {
                                                $order_no   = (string)$xml_obj->orderNo;

                                                $so_dao = $this->get_so_dao();
                                                $so_obj = $so_dao->get_so_by_txn_id($order_no);

                                                if($so_obj)
                                                {
                                                    $error_msg = "Warning: SKU is empty in Qoo10 packNo: $platform_order_id / orderNo: $order_no. \nOrder has been created in so_no <{$so_obj->so_no}>. If this order info is correct, ignore this email.";
                                                    $this->send_notification_email("SKU", $error_msg);  #empty_sku
                                                }
                                                else
                                                {
                                                    $error_msg = "Error in Qoo10 import orders \nError Msg: SKU is empty. Please create manual order. Order info as below -
                                                                    \n\nplatform_order_id [Qoo10 packNo]: $platform_order_id / transaction_no [Qoo10 orderNo]: $order_no.
                                                                    \nClient's email: $client_email *NOTE: Pls check that client doesn't have existing unshipped packNo*;
                                                                    \nClient's name: $client_forename; \nClient's contact no.: <$client_tel>(Tel) / <$client_mobile>(Mobile);
                                                                    \nClient's add: $client_address \nPostcode: $client_postcode \nCountry_id: $client_country_id;
                                                                    \nDelivery name: $del_name; \nDelivery contact no.: <$del_tel>(Tel) / <$del_mobile>(Mobile);
                                                                    \nDelivery add: $del_address \nPostcode: $del_postcode \nCountry_id: $del_country_id";
                                                    $this->send_notification_email("SKU", $error_msg);  #empty_sku
                                                }

                                                echo "<pre>$error_msg<pre><hr></hr>";
                                                continue;
                                            }
                                        }
                                    }
                                    echo "prod_sku ($prod_sku) received from $prod_sku_source  </pre>";

                                    $prod_name                  = (string)$xml_obj->itemTitle;
                                    $ext_item_cd                = (string)$xml_obj->itemCode;
                                    $delivery_charge            = (float)$xml_obj->ShippingRate;
                                    $qty                        = (int)$xml_obj->orderQty;
                                    $discount                   = ($xml_obj->discount); # qoo10 xml: discount = discount_per_sku * qty
                                    $total_amount_paid          = (float)$xml_obj->total; # what we receive per sku
                                    $unit_price                 = $total_amount_paid / $qty; # what we receive per unit for the sku
                                    $currency_id                = (string)strtoupper($xml_obj->Currency);
                                    $create_order_time          = (string)$xml_obj->orderDate;
                                    $pay_date                   = (string)$xml_obj->PaymentDate;

                                    if($del_country_id == 'SG')
                                    {
                                        $city = "Singapore";
                                    }
                                    else
                                    {
                                        $city = $del_country_id;
                                    }

                                    /** Set all info into CIS and OIS to automate process of writing to db **/
                                    /* ============= set xml info into client_integration_service ============= */
                                    $client_intergration_srv->set_ext_client_id($ext_client_id);
                                    $client_intergration_srv->set_client_name($client_forename);
                                    $client_intergration_srv->set_client_tel($client_tel);
                                    $client_intergration_srv->set_client_mobile($client_mobile);
                                    $client_intergration_srv->set_client_email($client_email);
                                    $client_intergration_srv->set_client_address($client_country_id, $client_postcode, $client_address, "", "", $city);
                                    $client_intergration_srv->set_del_name($del_name);
                                    $client_intergration_srv->set_del_tel($del_tel);
                                    $client_intergration_srv->set_del_mobile($del_mobile);
                                    $client_intergration_srv->set_del_address($del_country_id, $del_postcode, $del_address, "", "", $city);
                                    $password = (time()+$xcnt++);   # randomly generate passsword
                                    $client_intergration_srv->set_password($password);

                                    /* REQUIRED: create client info in a temp place */
                                    $client_vo = $client_intergration_srv->create_client($batch_id);

                                    /* ============= set xml info into order_integration_service ============= */
                                    $order_integration_srv->set_interface_client_vo($client_vo);
                                    $order_integration_srv->set_bill_name($client_forename);
                                    $order_integration_srv->set_bill_address($client_country_id, $client_postcode, $client_address, $city);
                                    $order_integration_srv->set_payer_email($client_email);
                                    $order_integration_srv->set_create_order_time($create_order_time);
                                    $order_integration_srv->set_pay_date($pay_date);
                                    $order_integration_srv->set_currency_id($currency_id);
                                    $order_integration_srv->set_qty($qty);
                                    $order_integration_srv->set_unit_price($unit_price);
                                    // $order_integration_srv->set_total_amt_paid_per_sku($total_amt_paid_per_sku);

                                    # At this point qoo10 only has the amt paid per orderNo.
                                    # Need to consolidate amt paid for whole packNo after commit below
                                    $order_integration_srv->set_amount($total_amount_paid);
                                    $order_integration_srv->set_platform_order_id($platform_order_id);
                                    $order_integration_srv->set_delivery_charge($delivery_charge);
                                    $order_integration_srv->set_weight(1);
                                    $order_integration_srv->set_prod_sku($prod_sku);
                                    $order_integration_srv->set_prod_name($prod_name);
                                    $order_integration_srv->set_ext_item_cd($ext_item_cd);
                                    $order_integration_srv->set_txn_id();
                                    $order_integration_srv->set_payment_gateway_id('qoo10');
                                    $order_integration_srv->set_pay_to_account('qoo10');
                                    $order_integration_srv->set_order_as_paid(TRUE); #TRUE = order has been paid

                                    /* REQUIRED: create so-related info in a temp place */
                                    $order_integration_srv->create_platform_batch();
                                }
                            }
                        }
                    }
                }

                /* REQUIRED: insert client info and SO details into db */
                $success_so_list = $order_integration_srv->commit_platform_batch();

                // conver time back to GMT
                $lasthour  = mktime(date("H")-1, date("i"), date("s"), date("m"),   date("d"),   date("Y"));
                $gmttime = date("Y-m-d H:i:s", $lasthour);
                $this->get_sj_srv()->update_last_process_time($this->http_name, $gmttime);

                /**
                This portion onwards is Qoo10-specific.
                In Qoo10,
                one orderNo contains only one SKU, but
                one packNo may contain several orderNo. We loop through xml again and add orderNo in db so.txn_id.
                Append orderNo to previous one if it already exists (e.g. orderNo01-orderNo02).
                **/
                $so_dao = $this->get_so_dao();
                foreach ($success_so_list as $key => $so_arr)
                {
                    $so_no = $so_arr["so_no"];
                    for(;;)
                    {
                        $so_obj = null;
                        $so_obj = $so_dao->get(array("so_no"=>$so_no));

                        if($so_obj == null)
                        {
                            // THIS IS A HACK!! Sometimes die when too many loops
                            sleep(1);
                            echo "falling asleep $s, so_no $so_no<br>";
                            // $s++;
                        }
                        else
                            break;
                    }

                    if ($so_obj)
                    {
                        $platform_order_id = $so_obj->get_platform_order_id();

                        foreach ($xml as $xml_obj)
                        {
                            $pack_no    = (string)$xml_obj->packNo;
                            $order_no   = (string)$xml_obj->orderNo;
                            if($platform_order_id == $pack_no)
                            {
                                echo "<pre>---[[ DEBUG: inserting txn_id // so_no: $so_no // db platform_order_id: $platform_order_id // order_no: $order_no ]]---</pre>";
                                /**
                                 One packNo may have multiple orderNo.
                                 This portion checks if any orderNo has been inserted. Append if more than one orderNo.
                                 We store orderNo under transaction.
                                 **/
                                $txn_id = $so_obj->get_txn_id();
                                if (!$txn_id)
                                {
                                    $so_obj->set_txn_id($order_no);
                                }
                                else
                                {
                                    if(strpos($txn_id, $order_no) === FALSE)
                                    {
                                        $so_obj->set_txn_id("{$txn_id}-{$order_no}");
                                    }
                                    else
                                    {
                                        $so_obj->set_txn_id($order_no);
                                    }

                                }
                                if(!$so_dao->update($so_obj))
                                {
                                    $error_msg = "qoo10_service Line: " .__LINE__. ", unable to update Qoo10 orderNo ($order_no) in so_no ($so_no). \n".$so_dao->db->_error_message();
                                    $this->send_notification_email("UE", $error_msg); #update_order_no_error
                                }
                                else
                                {
                                    echo "<pre>DEBUG: txn_id inserted </pre>";
                                }

                                /* Qoo10 only has total amount paid for each orderNo, so need to consolidate
                                    and update total amount per so_no */
                                $so_item_dao = $this->get_so_item_dao();
                                $so_item_num_rows = $so_item_dao->get_num_rows(array("so_no"=>$so_no));
                                if($so_item_num_rows > 1)
                                {
                                    echo "<pre>---[[ DEBUG: SO.AMOUNT NEED UPDATE - so_no ($so_no) has more than one item. ]]---</pre>";
                                    $so_final_amount = "";
                                    $so_initial_amount = $so_obj->get_amount();
                                    $so_item_list = $so_item_dao->get_list(array("so_no"=>$so_no));
                                    if($so_item_list)
                                    {
                                        foreach ($so_item_list as $so_item_obj)
                                        {
                                            $so_final_amount += (float)$so_item_obj->get_amount();
                                        }

                                        # if so_item is a complementary accessory, amount = 0, so don't update
                                        if($so_initial_amount != $so_final_amount)
                                        {
                                            $so_obj->set_amount($so_final_amount);
                                            if(!$so_dao->update($so_obj))
                                            {
                                                $error_msg = "qoo10_service Line: " .__LINE__. ", unable to update Qoo10 final amount ($so_final_amount) in so_no ($so_no) / platform_order_id ($platform_order_id). \n".$so_dao->db->_error_message();
                                                $this->send_notification_email("UE", $error_msg); #update_order_error
                                            }
                                            else
                                            {
                                                echo "<pre>DEBUG: SO.AMOUNT UPDATE SUCCESS. // so_no: $so_no // Final total amount: $so_final_amount.";
                                            }
                                        }
                                    }
                                    else
                                    {
                                        $error_msg = "qoo10_service Line: " .__LINE__. ", unable to update Qoo10 db so.amount. so_item does not exist where so_no = < $so_no >. \n".$so_dao->db->_error_message();
                                        $this->send_notification_email("UE", $website, $error_msg); #update_order_error

                                    }
                                }
                                else
                                {
                                    echo "<pre>---[[ DEBUG: SO.AMOUNT - NO UPDATE NEEDED, so_no ($so_no) only has one item. ]]---</pre>";
                                }

                                if($order_notes = (string)$xml_obj->ShippingMsg)
                                {
                                    echo "<pre>DEBUG: order notes found in xml ========== so_no: $so_no;</pre>";
                                    var_dump($order_notes);
                                    $order_integration_srv->insert_order_notes($so_no, $order_notes, $this->platform_id, $website);
                                }

                                $order_integration_srv->insert_so_priority_score($so_no, '901', $this->platform_id, $website);
                                echo "<pre>DEBUG WHOLE LOOP COMPLETED</pre><hr></hr>";
                            }
                        }
                    }
                }
            }
            catch (Exception $e)
            {
                echo 'Caught exception: ' , "<br>", nl2br($e->getMessage()), "\n";
            }
        }
    }


    private function get_qoo10_orders_xml()
    {
        /* This function imports the xml and change customers' status in set_seller_check */

        $error_msg = $xml_error = "";
        $http_name = $this->http_name;
        $this->process = "get_qoo10_orders_xml";

        $debug = false;
        if(strpos($_SERVER["HTTP_HOST"], 'dev') !== false)
            $debug = true;      # on dev server

        /* Qoo10 generates a seller authentication code everytime you log in before you can use the functions */
        $seller_auth_code = $this->get_authentication_code();

        if($seller_auth_code)
        {
            $last_time = $this->get_sj_srv()->get_last_process_time($http_name);

            if (!$last_time)
            {
                $this->send_notification_email("GT"); #get_cron_time
                echo "<pre>DEBUG: Schedule job time exceeded</pre>";
            }
            else
            {
                $now_time = date("YmdHis", mktime());
                if(strtotime($now_time) - strtotime($last_time) > 2592000) #exceed 30 days
                {
                    $this->send_notification_email("TR"); #time_range
                }
                else
                {
                    // $qoo10_last_date = date("Ymd", strtotime($last_time));
                    $qoo10_last_date = "20130601"; #temporarily hard-code to 1st june because API only allow search by order date, whereas customers can pay many days aft order date
                    $qoo10_now_date = date("Ymd", strtotime($now_time));

                    # set_seller_check will change shippingStatus from 2(before ready to ship) to 3(seller confirm; ready to ship)
#####               !!!TEST LINE. comment out line below when testing on DEV, else will affect LIVE QOO10 listing
                    if($debug === false)
                        $set_seller_check = $this->set_seller_check($now_time, $qoo10_last_date, $qoo10_now_date);
                    else
                        $set_seller_check = true;

                    if($set_seller_check === TRUE)
                    {
                        sleep(5);
                        $info = array();

                        // because keep getting Timeout Expired, we try to loop if fail.
                        for($i=0; $i <= 5; $i++)
                        {
                            if($i >0)   sleep(10);  // second loop onwards, pause first in case qoo10 server busy

                            echo "<pre>DEBUG: Trying to connect; loop $i</pre>";
                            if($seller_auth_code = $this->get_authentication_code())
                            {
                                $shipping_status = "3";  # this is order paid and seller confirmed
                                // $get_orders_url = "{$this->api_url}ShippingBasicService.api/GetShippingInfo?";
                                $url = "{$this->api_url}ShippingBasicService.api/GetShippingInfo";
                                $postdata = (array(
                                                    "ShippingStat" => $shipping_status,
                                                    "search_Sdate" => $qoo10_last_date,
                                                    "search_Edate" => $qoo10_now_date,
                                                    "key" => $seller_auth_code,
                                                )
                                            );

                                //url-ify the data for the POST
                                foreach($postdata as $key=>$value)
                                {
                                    $fields_string .= $key.'='.$value.'&';
                                }
                                rtrim($fields_string, '&');
                                $fields_string = http_build_query($postdata);


                                $ch = curl_init();
                                set_time_limit(1000);
                                curl_setopt($ch, CURLOPT_URL, $url);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($ch, CURLOPT_HEADER, 0);    // set to 1 for debug
                                curl_setopt($ch,CURLOPT_FAILONERROR,true);

                                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT , 500);
                                curl_setopt($ch, CURLOPT_TIMEOUT, 800); //timeout in seconds

                                curl_setopt($ch, CURLOPT_POST, true);
                                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

                                //execute post
                                $after_seller_check_list = curl_exec($ch);

                                $info = curl_getinfo($ch);
                                $header = curl_getinfo($ch);

                                $curl_error = curl_error($ch);
                                curl_close($ch);

                                $curl_errmsg = "\n\nurl: {$info['url']}\nrequest_fields: $fields_string\n";
                                $curl_errmsg .= "Took {$info['connect_time']} seconds to connect.\n";
                                $curl_errmsg .= "Took {$info['total_time']} seconds to send a request.\n";
                                // echo $curl_errmsg;

                                if(empty($curl_error) || $after_seller_check_list !== false)
                                {
                                    /* debug path, get xml file frm folder if qoo10 order list is empty */
                                    // define('DATAPATH', $this->get_config_srv()->value_of("data_path"));
                                    // $get_orders_url = DATAPATH.'orders/qoo10_orders/qoo10_valuebasket_[after_seller_check]_20140221044712.xml';

                                    /* debug line below: */
                                    // header ("Content-Type: application/xml"); $orderlist = file_get_contents($get_orders_url); echo $orderlist; die();

                                    # save a copy of all the confirmed xml orders on server
                                    $filename = "qoo10_valuebasket_[after_seller_check]_$now_time.xml";
                                    if($fp = @fopen("/var/data/valuebasket.com/orders/qoo10_orders/$filename", 'w'))
                                    {
                                        @fwrite($fp, $after_seller_check_list);
                                        @fclose($fp);
                                    }

                                    echo "<pre>DEBUG: File: /var/data/valuebasket.com/orders/qoo10_orders/$filename </pre>";
                                    libxml_use_internal_errors(true);
                                    /* Convert xml into array format */
                                    $ctx = stream_context_create(array(
                                                                        'http' => array(
                                                                            'timeout' => 60
                                                                            )
                                                                        )
                                                                    );
                                    if($content = file_get_contents("/var/data/valuebasket.com/orders/qoo10_orders/$filename", 0, $ctx))
                                    {
                                        $orderlist = simplexml_load_string($content, 'SimpleXMLElement', LIBXML_NOCDATA);
                                        if($orderlist !== false)
                                        {
                                            $totalorder_number = $orderlist->TotalOrder;
                                            if ($orderlist->ResultCode == 0)
                                            {
                                                if ($totalorder_number == 0)
                                                {
                                                    $this->get_sj_srv()->update_last_process_time($http_name, $now_time);
                                                    break;
                                                }
                                                else
                                                {
                                                    /* qoo10 xml tree is in <order1><order2>... format, so use below to loop through */
                                                    $order_info = array();
                                                    for($i=1; $i<=$totalorder_number; $i++ )
                                                    {
                                                        $child = "Order$i";
                                                        $order_info[] = $orderlist->$child;
                                                    }
                                                    return $order_info;
                                                }
                                            }
                                            else
                                            {
                                                $error_msg = "Error retrieving order info xml. \nRequest parameters: search start date ($qoo10_last_date), search end date ($qoo10_now_date). \nQoo10 Response reason for fail: '".(string)$orderlist->ResultMsg."'";
                                                $this->send_notification_email("DE", $error_msg); #data_import_error
                                                // header ("Content-Type: application/xml");
                                                echo "<pre>ERROR DEBUG: $error_msg</pre>";
                                                break;
                                            }
                                        }
                                        else
                                        {
                                            if($i == 5)
                                            {
                                                $errors = libxml_get_errors();
                                                $xml = explode('\n', $after_seller_check_list);
                                                foreach ($errors as $error)
                                                {
                                                    $libxml_errors .= $this->display_xml_error($error, $xml);
                                                }

                                                $error_msg = "{$this->platform_id} qoo10_service line_no: " .__LINE__. "- XML Error. \nOccured while processing get_qoo10_orders_xml. \nXML error msg: $libxml_errors $curl_errmsg";
                                                $this->send_notification_email("DE", $error_msg);  #data_import_error
                                                echo "<pre>ERROR DEBUG: $error_msg</pre>";
                                            }
                                            continue;
                                        }

                                    }
                                    else
                                    {
                                        if($i == 5)
                                        {
                                            $error_msg = "{$this->platform_id} qoo10_service line_no: " .__LINE__. "- File_get_contents Error. \nOccured while processing get_qoo10_orders_xml. $curl_errmsg \nFile: /var/data/valuebasket.com/orders/qoo10_orders/$filename";
                                                $this->send_notification_email("DE", $error_msg);  #data_import_error
                                                echo "<pre>ERROR DEBUG: $error_msg</pre>";
                                                break;
                                        }
                                        continue;
                                    }
                                }
                                else
                                {
                                    if($i==5)
                                    {
                                        $error_msg = "{$this->platform_id} qoo10_service line_no: " .__LINE__. " - Problem getting response from Qoo10. \nOccured while processing {$this->process}. \nURL: $url?$fields_string  \ncurl_error: $curl_error";
                                        $this->send_notification_email("QE", $error_msg);  #qoo10_response_error
                                        echo "<pre>ERROR DEBUG: $error_msg</pre>";
                                        break;
                                    }
                                    continue;
                                }

                            }

                        }

                    }
                }
            }
        }
    }

    private function set_seller_check($now_time, $qoo10_last_date, $qoo10_now_date)
    {
        $this->process = "set_seller_check";
        libxml_use_internal_errors(true);
        if($seller_auth_code = $this->get_authentication_code())
        {
            /* This function sets orders as seller confirm so clients cannot cancel order */
            $shipping_status = "2"; # orders paid, but before seller confirm
            $qoo10_last_date = "20130601";
            // $get_orders_url = "{$this->api_url}ShippingBasicService.api/GetShippingInfo";
            $url = "{$this->api_url}ShippingBasicService.api/GetShippingInfo_v2";

            $postdata = (array(
                                "ShippingStat" => $shipping_status,
                                "Search_Condition"=>2, #search date condition is payment date
                                "search_Sdate"=>$qoo10_last_date,
                                "search_Edate"=>$qoo10_now_date,
                                "key" => $seller_auth_code
                            )
                        );

            //url-ify the data for the POST
            foreach($postdata as $key=>$value)
            {
                $fields_string .= $key.'='.$value.'&';
            }
            rtrim($fields_string, '&');
            $fields_string = http_build_query($postdata);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

            //execute post
            $content = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $header = curl_getinfo($ch);

            $curl_error = curl_error($ch);
            curl_close($ch);

            if(empty($curl_error))
            {
                libxml_use_internal_errors(true);
                /* Convert xml into array format */
                $orderlist = simplexml_load_string($content, 'SimpleXMLElement', LIBXML_NOCDATA);
                if($orderlist)
                {
                    $totalorder_number = $orderlist->TotalOrder;
                    if ($orderlist->ResultCode == 0) #qoo10 success
                    {
                        #save a copy of orders before setting seller confirm
                        $before_seller_check_list = $content; #pure xml format
                        $filename = "qoo10_valuebasket_[before_seller_check]_$now_time.xml";
                        if($fp = @fopen("/var/data/valuebasket.com/orders/qoo10_orders/$filename", 'w'))
                        {
                            @fwrite($fp, $before_seller_check_list);
                            @fclose($fp);
                        }
                        else
                        {
                            $error_msg = "Error saving xml file. Please refer to file '/var/data/valuebasket.com/orders/qoo10_orders/$filename'";
                            $this->send_notification_email("SC", $error_msg); #seller_check_error
                        }

                        if ($totalorder_number == 0)
                        {
                            echo "<pre><hr></hr>DEBUG: SET_SELLER_CHECK - NO ORDERS WITH SHIPSTAT=2<pre><hr></hr>";
                            return TRUE;
                        }
                        else
                        {
                            /* qoo10 xml tree formating is <order1><order2>..., so use method below to loop through */
                            $order_info = array();
                            for($i=1; $i<=$totalorder_number; $i++ )
                            {
                                $child = "Order$i";
                                $order_info[] = $orderlist->$child;
                            }

                            /* here the api sets orders to seller confirm */
                            foreach ($order_info as $order_info_obj)
                            {
                                $order_no = (string)$order_info_obj->orderNo;
                                $seller_check_url = "{$this->api_url}ShippingBasicService.api/SetSellerCheckYN?OrderNo=$order_no&key=$seller_auth_code";

        #####                   !!!TEST LINE. comment out line below when testing on DEV, else will affect LIVE QOO10 listing
                                $response = simplexml_load_string(file_get_contents($seller_check_url), 'SimpleXMLElement', LIBXML_NOCDATA);
                                if($response)
                                {
                                    if($response->ResultCode != 0) #Qoo10 failure
                                    {
                                        $error_msg = "Error in setting Qoo10 order to seller confirm. \nQoo10 orderNo: $order_no \nQoo10 Response reason for failure: '".(string)$response->ResultMsg."'";
                                        echo "<pre>ERROR DEBUG: $error_msg</pre>";
                                        $this->send_notification_email("SC", $error_msg); #seller_check_error
                                    }
                                }
                                else
                                {
                                    $error_msg = "{$this->platform_id} qoo10_service line_no: " .__LINE__. " - Problem getting response from Qoo10. \nOccured while processing {$this->process}";
                                    $this->send_notification_email("QE", $error_msg);  #qoo10_response_error
                                    echo "<pre>ERROR DEBUG: $error_msg</pre>";
                                }

                            }

                            echo "<pre><hr></hr>DEBUG: SET_SELLER_CHECK DONE<pre><hr></hr>";
                            return TRUE;
                        }
                    }
                    else
                    {
                        $error_msg = "Error in retrieving Qoo10 orders with ShippingStat=2. \nQoo10 Response reason for failure: '".(string)$orderlist->ResultMsg."'";
                        echo "<pre>ERROR DEBUG: $error_msg</pre>";
                        $this->send_notification_email("SC", $error_msg); #seller_check_error
                    }
                }
                else
                {
                    $errors = libxml_get_errors();
                    $xml = explode('n', $content);
                    foreach ($errors as $error)
                    {
                        $libxml_errors .= $this->display_xml_error($error, $xml);
                    }

                    $error_msg = "{$this->platform_id} qoo10_service line_no: " .__LINE__. " - Problem getting response from Qoo10. \nOccured while processing {$this->process}. \nURL: $url?$fields_string \nLIBXML error msg: $libxml_errors";
                    $this->send_notification_email("QE", $error_msg);  #qoo10_response_error
                    echo "<pre>ERROR DEBUG: $error_msg</pre>";
                    libxml_clear_errors();
                }
            }
            else
            {
                $error_msg = "{$this->platform_id} qoo10_service line_no: " .__LINE__. " - Problem getting response from Qoo10. \nOccured while processing {$this->process}. \nURL: $url?$fields_string  \ncurl_error: $curl_error";
                $this->send_notification_email("QE", $error_msg);  #qoo10_response_error
                echo "<pre>ERROR DEBUG: $error_msg</pre>";
            }

        }
    }

    public function update_shipment_status($country_id)
    {
        $this->platform_id = "QOO10".strtoupper($country_id);  #QOO10SG

        if($obj_list = $this->get_so_srv()->get_qoo10_pending_shipment_update_orders(array("so.platform_id"=>$this->platform_id)))
        {
            foreach ($obj_list as $obj)
            {
                $order_no = $obj->get_txn_id();

                if((strpos($order_no, '-')) === FALSE)
                {
                    # txn_id contains only one qoo10 orderNo
                    $this->set_sending_info_api($order_no, $obj);
                }
                else
                {
                    # txn_id contains only multiple qoo10 orderNo, so need to loop through each one
                    $order_no_arr = explode('-', $order_no);
                    foreach ($order_no_arr as $key => $order_no)
                    {
                        $this->set_sending_info_api($order_no, $obj);
                    }
                }
            }
        }
        else
        {
            echo "DEBUG: no obj_list for updating shipment status.\n";
        }

    }

    private function set_sending_info_api($order_no, $obj)
    {
        $this->process = "set_sending_info_api";
        /* Qoo10 generates a seller authentication code everytime you log in before you can use the functions */
        $seller_auth_code = $this->get_authentication_code();

        if($seller_auth_code)
        {
            $courier_name = rawurlencode($obj->get_courier_name());
            $tracking_no = $obj->get_tracking_no();
            $setsendinginfo_api = "{$this->api_url}ShippingBasicService.api/SetSendingInfo?OrderNo=$order_no&ShippingCorp=$courier_name&TrackingNo=$tracking_no&key=$seller_auth_code";

#####       !!!TEST LINE. comment out line below when testing on DEV, else will affect LIVE QOO10 listing
            $response = simplexml_load_string(file_get_contents($setsendinginfo_api), 'SimpleXMLElement', LIBXML_NOCDATA);
            echo "<pre>DEBUG: Qoo10 orderNo($order_no) // so_no:  {$obj->get_so_no()}</pre> <pre>api url: $setsendinginfo_api </pre>";

            // $response->ResultCode == '0';
            if($response)
            {
                if ($response->ResultCode == 0) #Qoo10 success
                {
                    $soext_dao = $this->get_so_srv()->get_soext_dao();
                    if($soext_obj = $soext_dao->get(array("so_no" => $obj->get_so_no())))
                    {
                        $soext_obj->set_fulfilled("Y");
                        if(!$ret = $soext_dao->update($soext_obj))
                        {
                            $error_msg = "\nFailed to update so_extend after Qoo10 API has marked orders as shipped successfully. qoo10_service Line: ". __LINE__ .", so_no: ".$obj->get_so_no();
                            echo "<pre>DEBUG ERROR MSG: $error_msg</pre>";
                            $this->send_notification_email("US", $error_msg);  #update_shipment_status_error
                        }
                        echo "<pre>DEBUG: SUCCESS UPDATING SOEXT</pre>";
                    }
                }
                else
                {
                    $error_msg = "Error in update_shipment_status. txn_id: $order_no // so_no:  {$obj->get_so_no()}. \nQoo10 Response reason for failure: '$response->ResultMsg'\n";
                    echo "<pre>ERROR DEBUG: $error_msg</pre>";
                    $this->send_notification_email("US", $error_msg);  #update_shipment_status_error
                }
            }
            else
            {
                $error_msg = "{$this->platform_id} qoo10_service line_no: " .__LINE__. " - Problem getting response from Qoo10. \nOccured while processing {$this->process}";
                $this->send_notification_email("QE", $error_msg);  #qoo10_response_error
                echo "<pre>ERROR DEBUG: $error_msg</pre>";
            }

        }
    }

    public function add_items($country_id)
    {
        $this->platform_id = "QOO10".strtoupper($country_id);  #QOO10SG

        $where = array("platform_id"=>$this->platform_id, "website_quantity > "=>0, "prod_status"=>2, "listing_status"=>'L', "ext_item_id IS NULL"=>NULL/*, "ext_ref_1 IS NOT NULL"=>NULL*/);
        echo "DEBUG: add_items starts. <pre></pre>";
        if ($total = $this->get_product_srv()->get_dao()->get_prod_overview_extended($where, array("num_rows"=>1)))
        {
            echo "<pre>DEBUG: no. of product(s) to update: $total.</pre>";
            $loop_no = 1;

            if ($add_item_list = $this->get_product_srv()->get_dao()->get_prod_overview_extended($where))
            {
                foreach ($add_item_list as $item_obj)
                {
                    echo "<hr></hr><pre>Listing on Qoo10. Loop ".$loop_no++."</pre>";

                    set_time_limit(60);
                    $qoo10_response = $this->additems_api($country_id, $item_obj);
                    if($qoo10_response)
                    {
                        #update qoo10 itemcode into db price_extend
                        $sku = $qoo10_response["sku"];
                        $ext_item_id = $qoo10_response["ext_item_id"];
                        $this->insert_ext_item_id($sku, $ext_item_id);
                    }
                }
            }
        }
        else
        {
            echo "DEBUG: no newly-listed products to update";
        }
    }

    private function additems_api($country_id, $item_obj)
    {
        $this->process = "additems_api";
        $seller_auth_code = $this->get_authentication_code();

        if($seller_auth_code)
        {
            $this->item_obj = $item_obj;

            $secondsubcat = $item_obj->get_ext_ref_3();
            $itemtitle = $item_obj->get_title();  #from price_extend
            $sellercode = $item_obj->get_sku();
            $standardimage = "http://cdn.valuebasket.com/808AA1/vb/images/product/{$item_obj->get_sku()}.{$item_obj->get_image()}";
            $itemdescription = $this->get_item_description($item_obj);
            $rrp = $this->get_rrp($sellercode, $this->platform_id);
            if($rrp["response"] == 1)
            {
                $retailprice = $rrp["message"];
            }
            $itemprice = $item_obj->get_price();
            $itemqty = $item_obj->get_ext_qty();
            $expiredate = date("Y-m-d",(365*60*60*24) + time()); #1 year
            $shippingno = "0"; #free shipping in qoo10 system

            $postdata = (array(
                            "SecondSubCat" => $secondsubcat,
                            "ManufactureNo"=>"",
                            "BrandNo"=>"",
                            "ItemTitle" => $itemtitle,
                            "SellerCode" => $sellercode,
                            "IndustrialCode"=>"",
                            "ProductionPlace"=>"",
                            "AudultYN" => "N",
                            "ContactTel"=>"",
                            "StandardImage" => $standardimage,
                            "ItemDescription" => $itemdescription,
                            "AdditionalOption"=>"",
                            "ItemType"=>"",
                            "RetailPrice" => $retailprice,
                            "ItemPrice" => $itemprice,
                            "ItemQty" => $itemqty,
                            "ExpireDate" => $expiredate,
                            "ShippingNo" => $shippingno,
                            "AvailableDateType"=>"",
                            "AvailableDateValue"=>"",
                            "key" => $seller_auth_code
                        )
                    );

            $url = "{$this->api_url}GoodsBasicService.api/SetNewGoods";

            //url-ify the data for the POST
            foreach($postdata as $key=>$value)
            {
                $fields_string .= $key.'='.$value.'&';
            }
            rtrim($fields_string, '&');
            $fields_string = http_build_query($postdata);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            // curl_setopt($ch, CURLOPT_POST, count($postdata));

            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
            // curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);

            //execute post
            $content = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $header = curl_getinfo($ch);
            curl_close($ch);

            // debug
            // var_dump($httpCode); var_dump($header); var_dump($content);

#####       !!!TEST LINE. comment out line below when testing on DEV, else will affect LIVE QOO10 listing
            $response = simplexml_load_string($content, 'SimpleXMLElement', LIBXML_NOCDATA);
            // $response->ResultCode = 0; $response->ResultMsg = 9991;
            if($response)
            {
                if($response->ResultCode == 0) #Qoo10 success
                {
                    #qoo10 returns unique ItemCode in ResultMsg if successful
                    $qoo10_response["ext_item_id"] = (string)$response->ResultMsg;
                    $qoo10_response["sku"] = $sellercode;
                    echo "<pre>DEBUG: Qoo10's Response Success -- ";
                    var_dump("SKU: {$qoo10_response["sku"]} // QOO10 itemcode: {$qoo10_response["ext_item_id"]}");echo"</pre>";
                    return $qoo10_response;
                }
                else
                {
                    $error_msg = "Error in additems_api for sku <$sellercode>. \nQoo10 Response (reason for failure): ResultCode='".(string)$response->ResultCode."'; ResultMsg='".(string)$response->ResultMsg."'\n";
                    echo "<pre>ERROR DEBUG: $error_msg</pre>";
                    $this->send_notification_email("AI", $error_msg);  #add_items_error
                }
            }
            else
            {
                $curl_error = curl_error($ch);

                $error_msg = "{$this->platform_id} qoo10_service line_no: " .__LINE__. " - Problem getting response from Qoo10. \nOccured while processing {$this->process}. \nSKU: $sellercode \ncurl_error: $curl_error";
                $this->send_notification_email("QE", $error_msg);  #qoo10_response_error
                echo "<pre>ERROR DEBUG: $error_msg</pre>";
            }
        }
    }

    private function insert_ext_item_id($sku, $ext_item_id)
    {
        #update db price_extend once successfully listed on qoo10
        $price_ext_dao = $this->get_price_srv()->get_price_ext_dao();
        if ($price_ext_obj = $price_ext_dao->get(array("sku"=>$sku, "platform_id"=>$this->platform_id)))
        {
            $price_ext_obj->set_ext_status('L');
            $price_ext_obj->set_ext_item_id($ext_item_id);
            $price_ext_dao->update($price_ext_obj);
            echo "<pre>DEBUG: Updated in db. sku: $sku // qoo10 item_code: $ext_item_id </pre><hr></hr>";
        }
        else
        {
            $error_msg = "{$this->platform_id} qoo10_service line_no: " .__LINE__. ". Problem updating ext_item_id in db price_extend."."\nDB Error Message:".$this->get_price_srv()->get_price_ext_dao()->db->_error_message();
            $this->send_notification_email("AI", $error_msg);  #add_items_error
            echo "<pre>ERROR DEBUG: $error_msg</pre>";
        }
    }

    private function get_item_description($item_obj)
    {
        /* This function will replace all [::] in html template with individual item info */

        # product_content db
        if ($pc_obj = $this->get_product_srv()->get_content($item_obj->get_sku(), $item_obj->get_language_id()))
        {
            // echo "<pre>"; var_dump($pc_obj);die();
            $replace["title"] = $item_obj->get_title();
            $replace["image_url"] = "http://cdn.valuebasket.com/808AA1/vb/images/product/{$item_obj->get_sku()}.{$item_obj->get_image()}";
            $desc_content = $replace["youtube_obj"] = $replace["description"] = $replace["nutshell"] = $replace["features"] = $replace["specifications"] = $replace["requirements"] = $replace["contents"] = "";

            $replace["features"] = $this->is_html($pc_obj->get_feature()) ? $pc_obj->get_feature() : nl2br($pc_obj->get_feature());
            $replace["specifications"] = $this->is_html($pc_obj->get_specification()) ? $pc_obj->get_specification() : nl2br($pc_obj->get_specification());
            $replace["requirements"] = nl2br($pc_obj->get_requirement());
            $replace["description"] = nl2br($pc_obj->get_detail_desc());
            $replace["nutshell"] = nl2br($pc_obj->get_short_desc());
            if($pc_obj->get_contents() != "")
            {
                $str = explode("\n",$pc_obj->get_contents());

                foreach($str as $key=>$value)
                {
                    $value = trim($value);
                    if(empty($value))
                    {
                        unset($str[$key]);
                    }
                    if(preg_match('/Hong Kong Post services.*Tracking Number/i', $value))
                    {
                        unset($str[$key]);
                    }

                    if(preg_match('/Once your order.*dispatch date/i', $value))
                    {
                        unset($str[$key]);
                    }

                    $data_in_the_box = implode('<br /><br />', $str);
                    if ($item_obj->get_title() == "")
                    {
                        $item_obj->set_title($pc_obj->get_prod_name());
                    }
                }

                $replace["contents"] = $data_in_the_box;
            }

            if($item_obj->get_price() < 120)
            {
                $replace["shipping_note"] = "
                    ValueBasket offers FREE shipping for this item, which will be shipped using Hong Kong Post services.
                    Do note this type of service usually takes 10-21 days for delivery, with Tracking Number being available.
                    Once your order has been dispatched, ValueBasket will send you an email with your dispatch date.";
            }
            else
            {
                $replace["shipping_note"] = "
                    ValueBasket offers FREE shipping for this item, delivery usually takes between 7 to 14 days.
                    There may be unexpected delays in clearing customs which are outside of our control.
                    Although we endeavor to supply our customers with products in the quickest possible time,
                    customs delays may cause a longer lead time that we quote.";
            }

            $search = array();
            $value = array();
            foreach ($replace as $rskey=>$rsvalue)
            {
                $search[] = "[:".$rskey.":]";
                $value[] = $rsvalue;
            }

            $path = APPPATH."data/template/qoo10_description/qoo10_description_".strtolower($item_obj->get_platform_country_id()).".html";

            if (is_file($path))
            {
                $template = file_get_contents($path);
                $desc_content = str_replace($search, $value, $template);
            }

            return $desc_content;
        }
        else
        {
            $error_msg = "Failed to get info from db product_content. \nqoo10_service line: ". __LINE__ . "DB error: {$this->get_product_srv()->get_dao()->db->_error_message()}";
            echo "DEBUG: $error_msg";
            $this->send_notification_email("ID", $error_msg); #get_item_description
        }
    }

    private function is_html($string)
    {
        $pattern = '/^(<.+>)(.*)(<\/[a-zA-Z ]+>)$/s';

        if(preg_match($pattern, trim($string)))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    private function get_rrp($sku, $platform_id)
    {
        if($sku && $platform_id)
        {
            if($price_obj = $this->get_price_srv()->get(array("sku"=>$sku, "platform_id"=>$platform_id)))
            {
                $rrp = $this->get_price_srv()->calc_website_product_rrp($price_obj->get_price(), $price_obj->get_fixed_rrp(), $price_obj->get_rrp_factor());
                return array("response"=>1, "message"=>"$rrp");

                // return $rrp;
            }
            else
            {
                $error_msg = "Failed to get rrp info from db price. \nqoo10_service line: ". __LINE__ . "DB error: {$this->get_price_srv()->get_dao()->db->_error_message()}";
                $this->send_notification_email("RRP", $error_msg); #get_rrp
                return array("response"=>0, "message"=>"$error_msg");
            }
        }
    }

    public function update_item($platform_id, $sku)
    {
        $this->platform_id = $platform_id;

        $where = array("platform_id"=>$platform_id, "sku"=>$sku, "ext_item_id IS NOT NULL"=>NULL);
        if ($obj = $this->get_product_srv()->get_dao()->get_prod_overview_extended($where, array("limit"=>1)))
        {
            # Qoo10 needs three APIs to update all the info
            $this->item_obj = $obj;
            $update_price_qty_response = $this->set_price_qty_api("update");
            $update_other_info_response = $this->update_other_info_api();
            $update_image_response = $this->update_image();
            $update_item_description = $this->update_item_description();

            if($update_price_qty_response["response"]       == 1
                && $update_other_info_response["response"]  == 1
                && $update_image_response["response"]       == 1
                && $update_item_description["response"]     == 1
                )  #all updates successful
            {
                return array("response"=>1, "message"=>"Update Qoo10 Listing - Success");
            }
            else
            {
                return array("response"=>0, "message"=>"Update Qoo10 Listing - Fail \nDetails: \n{$update_price_qty_response["message"]} \n{$update_other_info_response["message"]} \n{$update_image_response["message"]}");
            }
        }
        else
        {
            $db_message = "";
            if($this->get_product_srv()->get_dao()->db->_error_message() != "" )
            {
                $db_message = "\nDBerror: " . $this->get_product_srv()->get_dao()->db->_error_message();
                $db_message .= "\nSQL: " . $this->get_product_srv()->get_dao()->db->last_query();
            }
            return array("response"=>0, "message"=>"Update Qoo10 Listing - Fail; Object nt found $db_message");
        }
    }

    private function set_price_qty_api($action)
    {
        $this->process = "set_price_qty_api";
        $seller_auth_code = $this->get_authentication_code();
        if($seller_auth_code)
        {
            $item_obj = $this->item_obj;
            $itemcode = $item_obj->get_ext_item_id();
            $itemprice = $item_obj->get_price();

            switch ($action)
            {
                case 'update':
                    $itemqty = $item_obj->get_ext_qty();
                    break;

                case 'delete':
                    # qoo10 api doesn't allow delete listing, so set itemqty=0
                    $itemqty = '0';
                    break;

                default:
                    echo "Please input correct action in set_price_qty_api";
                    break;
            }


            $postdata = (array(
                                "key"       => $seller_auth_code,
                                "ItemCode"  => $itemcode,
                                "Sellercode"=>"",
                                "ItemPrice" =>$itemprice,
                                "ItemQty"   =>$itemqty,
                                "ExpireDate"=>""
                                )
                        );

            $fields_string = http_build_query($postdata);
            $setgoodsprice_api = "{$this->api_url}GoodsOrderService.api/SetGoodsPrice?$fields_string";

#####       !!!TEST LINE. comment out line below when testing on DEV, else will affect LIVE QOO10 listing
            $response = simplexml_load_string(file_get_contents($setgoodsprice_api), 'SimpleXMLElement', LIBXML_NOCDATA);
            // echo "<pre>!!! TEST MODE: not sent to Qoo10 !!!! </pre>"; $response->ResultCode = 0;
            if($response)
            {
                if($response->ResultCode == 0) #Qoo10 success
                {
                    return array("response"=>1, "message"=>"1. Qoo10 API {SetGoodsPrice} - Sucess");
                }
                else
                {
                    return array("response"=>0, "message"=>"1. Qoo10 API {SetGoodsPrice} - Fail reason: '{$response->ResultMsg}'");
                }
            }
            else
            {
                return array("response"=>0, "message"=>"1. Qoo10 API {SetGoodsPrice} - Fail reason: 'Could not get response from Qoo10, please try again.'");
            }
        }
        else
        {
            return array("response"=>0, "message"=>"1. Qoo10 API {SetGoodsPrice} - Fail reason: 'Qoo10 Authentication fail, please try again.' Error_msg: {$this->error_msg}");
        }
    }

    private function update_other_info_api()
    {
        $seller_auth_code = $this->get_authentication_code();

        if($seller_auth_code)
        {
            $item_obj = $this->item_obj;
            $itemcode = $item_obj->get_ext_item_id();
            $secondsubcat = $item_obj->get_ext_ref_3();

            $itemtitle = $item_obj->get_title();  #from price_extend
            $sellercode = $item_obj->get_sku();
            $rrp = $this->get_rrp($sellercode, $this->platform_id);
            if($rrp["response"] == 1)
            {
                $retailprice = $rrp["message"];
            }
            else
            {
                return array("response"=>0, "message"=>"2. Qoo10 API {UpdateGoods} - Fail reason: \n{$rrp["message"]}");
            }

            $shippingno = "0"; #free shipping in qoo10 system

            $postdata = (array(
                            "key"               => $seller_auth_code,
                            "ItemCode"          => $itemcode,
                            "SecondSubCat"      => $secondsubcat,
                            "ManufactureNo"     => "",
                            "BrandNo"           => "",
                            "ItemTitle"         => $itemtitle,
                            "SellerCode"        => $sellercode,
                            "IndustrialCode"    => "",
                            "ProductionPlace"   => "",
                            "ContactTel"        => "",
                            "RetailPrice"       => $retailprice,
                            "ShippingNo"        => $shippingno,
                            "AvailableDateType" => "",
                            "AvailableDateValue"=> ""
                            )
                        );

            $fields_string = http_build_query($postdata);

            $updategoods_api = "{$this->api_url}GoodsBasicService.api/UpdateGoods?$fields_string";

#####       !!!TEST LINE. comment out line below when testing on DEV, else will affect LIVE QOO10 listing
            $response = simplexml_load_string(file_get_contents($updategoods_api), 'SimpleXMLElement', LIBXML_NOCDATA);
            // $response->ResultCode = 0;
            if($response)
            {
                if($response->ResultCode == 0) #Qoo10 success
                {
                    return array("response"=>1, "message"=>"2. Qoo10 API {UpdateGoods} - Success");
                }
                else
                {
                    return array("response"=>0, "message"=>"2. Qoo10 API {UpdateGoods} - Fail reason: '{$response->ResultMsg}'");
                }
            }
            else
            {
                return array("response"=>0, "message"=>"2. Qoo10 API {UpdateGoods} - Fail reason: 'Could not get response from Qoo10, please try again.'");
            }
        }
        else
        {
            return array("response"=>0, "message"=>"2. Qoo10 API {UpdateGoods} - Fail reason: 'Qoo10 Authentication fail, please try again.' Error_msg: {$this->error_msg}");
        }
    }

    private function update_image()
    {
        $seller_auth_code = $this->get_authentication_code();
        if($seller_auth_code)
        {
            $item_obj = $this->item_obj;
            $itemcode = $item_obj->get_ext_item_id();
            $standardimage = "http://cdn.valuebasket.com/808AA1/vb/images/product/{$item_obj->get_sku()}.{$item_obj->get_image()}";
            $postdata = (array(
                            "key"               => $seller_auth_code,
                            "ItemCode"          => $itemcode,
                            "SellerCode"        => "",
                            "StandardImage"     => $standardimage
                            )
                        );

            $fields_string = http_build_query($postdata);

            $editgoodsimage_api = "{$this->api_url}GoodsBasicService.api/EditGoodsImage?$fields_string";

#####       !!!TEST LINE. comment out line below when testing on DEV, else will affect LIVE QOO10 listing
            $response = simplexml_load_string(file_get_contents($editgoodsimage_api), 'SimpleXMLElement', LIBXML_NOCDATA);
            // $response->ResultCode = 0;
            if($response)
            {
                if($response->ResultCode == 0) #Qoo10 success
                {
                    return array("response"=>1, "message"=>"3. Qoo10 API {EditGoodsImage} - Success");
                }
                else
                {
                    return array("response"=>0, "message"=>"3. Qoo10 API {EditGoodsImage} - Fail reason: '{$response->ResultMsg}'");
                }
            }
            else
            {
                return array("response"=>0, "message"=>"3. Qoo10 API {EditGoodsImage} - Fail reason: 'Could not get response from Qoo10, please try again.'");
            }
        }
        else
        {
            return array("response"=>0, "message"=>"3. Qoo10 API {EditGoodsImage} - Fail reason: 'Qoo10 Authentication fail, please try again.' Error_msg: {$this->error_msg}");
        }
    }

    private function update_item_description()
    {
        $seller_auth_code = $this->get_authentication_code();
        if($seller_auth_code)
        {
            $item_obj = $this->item_obj;
            $itemcode = $item_obj->get_ext_item_id();
            $itemdescription = $this->get_item_description($item_obj);

            $postdata = (array(
                                "ItemCode" => $itemcode,
                                "Contents" => $itemdescription,
                                "Sellercode"=>"",
                                "key" => $seller_auth_code
                            )
                        );

            $url = "{$this->api_url}GoodsBasicService.api/EditGoodsContents";

            // url-ify the data for the POST
            $fields_string = http_build_query($postdata);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            // curl_setopt($ch, CURLOPT_POST, count($postdata));

            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

            //execute post
            $content = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $header = curl_getinfo($ch);
            curl_close($ch);


#####       !!!TEST LINE. comment out line below when testing on DEV, else will affect LIVE QOO10 listing
            $response = simplexml_load_string($content, 'SimpleXMLElement', LIBXML_NOCDATA);
// echo "<pre>!!! TEST MODE: not sent to Qoo10 !!!! </pre>"; $response->ResultCode = 0;
            if($response)
            {
                if($response->ResultCode == 0) #Qoo10 success
                {
                    return array("response"=>1, "message"=>"4. Qoo10 API {EditGoodsContents} - Success");
                }
                else
                {
                    return array("response"=>0, "message"=>"4. Qoo10 API {EditGoodsContents} - Fail reason: '{$response->ResultMsg}'");
                }
            }
            else
            {
                return array("response"=>0, "message"=>"4. Qoo10 API {EditGoodsContents} - Fail reason: 'Could not get response from Qoo10, please try again.'");
            }
        }
        else
        {
            return array("response"=>0, "message"=>"4. Qoo10 API {EditGoodsContents} - Fail reason: 'Qoo10 Authentication fail, please try again.' Error_msg: {$this->error_msg}");
        }
    }

    public function end_item($platform_id, $sku)
    {
        $this->platform_id = $platform_id;

        $where = array("platform_id"=>$platform_id, "sku"=>$sku, "ext_item_id IS NOT NULL"=>NULL);
        if ($obj = $this->get_product_srv()->get_dao()->get_prod_overview_extended($where, array("limit"=>1)))
        {
            $this->item_obj = $obj;
            $end_item_response = $this->set_price_qty_api("delete");
            $set_delete_status_response = $this->set_item_status_api("delete");

            if($end_item_response["response"] && $set_delete_status_response["response"])  #successful
            {
                return array("response"=>1, "message"=>"End Qoo10 Listing - Success");
            }
            else
            {
                return array("response"=>0, "message"=>"End Qoo10 Listing - Fail \nDetails: \n{$end_item_response["message"]} \n{$set_delete_status_response["message"]}");
            }
        }
    }

    private function set_item_status_api($action)
    {
        $seller_auth_code = $this->get_authentication_code();
        if($seller_auth_code)
        {
            $item_obj = $this->item_obj;
            $itemcode = $item_obj->get_ext_item_id();

            switch ($action)
            {
                case 'delete':
                    $status = 3;
                    break;

                case 'available':
                    $status = 2;
                    break;

                default:
                    echo "Please input correct action in set_item_status_api";
                    break;
            }

            $postdata = (array(
                                "key"       => $seller_auth_code,
                                "ItemCode"  => $itemcode,
                                "Sellercode"=>"",
                                "Status"    =>$status
                                )
                        );

            $fields_string = http_build_query($postdata);
            $editgoodsstatus_api = "{$this->api_url}GoodsBasicService.api/EditGoodsStatus?$fields_string";

#####       !!!TEST LINE. comment out line below when testing on DEV, else will affect LIVE QOO10 listing
            $response = simplexml_load_string(file_get_contents($editgoodsstatus_api), 'SimpleXMLElement', LIBXML_NOCDATA);
// echo "<pre>!!! TEST MODE: not sent to Qoo10 !!!! </pre>"; $response->ResultCode = 0;
            if($response)
            {
                if($response->ResultCode == 0) #Qoo10 success
                {
                    return array("response"=>1, "message"=>"Qoo10 API {EditGoodsStatus} - Sucess");
                }
                else
                {
                    return array("response"=>0, "message"=>"Qoo10 API {EditGoodsStatus} - Fail reason: '{$response->ResultMsg}'");
                }
            }
            else
            {
                return array("response"=>0, "message"=>"Qoo10 API {EditGoodsStatus} - Fail reason: 'Could not get response from Qoo10, please try again.'");
            }
        }
        else
        {
            return array("response"=>0, "message"=>"Qoo10 API {EditGoodsStatus} - Fail reason: 'Qoo10 Authentication fail, please try again.' Error_msg: {$this->error_msg}");
        }
    }

    private function get_authentication_code()
    {
        // need to log in to get seller authentication code for xml

        if($this->platform_id)
        {
            $http_obj = $this->get_http_info()->get(array("name"=>'QOO10_VALUEBASKET', "type"=>"P"));

            if(!$http_obj)
            {
                $this->send_notification_email("HE");  #http_info_error
            }
            else
            {
                include_once(BASEPATH."libraries/Encrypt.php");
                $encrypt = new CI_Encrypt();
                $this->api_url = $http_obj->get_server();  #common base url for all qoo10 api
                $username = $http_obj->get_username();
                $api_key = $http_obj->get_application_id();
                $password = $encrypt->decode($http_obj->get_password());

                $url = "{$this->api_url}Certification.api/CreateCertificationKey";
                $postdata = (array(
                                "user_id" => $username,
                                "pwd"=> $password, #search date condition is payment date
                                "key" => $api_key
                            )
                        );

                // $authentication_url = "{$this->api_url}Certification.api/CreateCertificationKey?key={$api_key}&user_id={$username}&pwd={$password}";
                //url-ify the data for the POST
                foreach($postdata as $key=>$value)
                {
                    $fields_string .= $key.'='.$value.'&';
                }
                rtrim($fields_string, '&');
                $fields_string = http_build_query($postdata);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

                //execute post
                $content = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $header = curl_getinfo($ch);

                $curl_error = curl_error($ch);
                curl_close($ch);


                // $content = @file_get_contents($authentication_url);
                if($curl_error)
                {
                    $this->error_msg = $error_msg = "{$this->platform_id} qoo10_service line_no: " .__LINE__. " - Couldn't get content from Qoo10's authentication url.";
                    $error_msg .= "\nOccured while processing {$this->process} \n$url?$fields_string. \ncurl_error: $curl_error";
                    $this->send_notification_email("QE", $error_msg);  #qoo10_response_error
                    echo "<pre>ERROR DEBUG: $error_msg</pre>";
                    return FALSE;
                }
                else
                {
                    libxml_use_internal_errors(true);
                    $authentication_xml = simplexml_load_string($content, 'SimpleXMLElement', LIBXML_NOCDATA);

                    if($authentication_xml)
                    {
                        if ($authentication_xml->ResultCode == 0)
                        {
                            $seller_auth_code = (string)$authentication_xml->ResultObject;
                            return $seller_auth_code;
                        }
                        else
                        {
                            $this->error_msg = $error_msg = "$this->platform_id authentication key cannot be retrieved. Qoo10 Response reason for fail: '".(string)$authentication_xml->ResultMsg."'";
                            $this->send_notification_email("AF", $error_msg);  #authentication_failure
                            return FALSE;
                        }
                    }
                    else
                    {
                        $errors = libxml_get_errors();
                        foreach ($errors as $error)
                        {
                            $xmlerror .= "\n$error";
                        }

                        $this->error_msg = $error_msg = "{$this->platform_id} qoo10_service line_no: " .__LINE__. " - Problem getting authentication xml from Qoo10. \nOccured while processing {$this->process} \nXML error msg: \n$xmlerror.";
                        $this->send_notification_email("QE", $error_msg);  #qoo10_response_error
                        echo "<pre>ERROR DEBUG: $error_msg</pre>";
                        return FALSE;
                    }
                }
            }
        }
        else
        {
            echo "DEBUG ERROR: qoo10_service line_no: " .__LINE__. ", please input platform_id";
        }
    }

    private function display_xml_error($error, $xml)
    {
        $return = $xml[$error->line - 1] . "<br />";
        $return .= str_repeat('-', $error->column) . "<br />";

        switch ($error->level)
        {
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

        if ($error->file)
        {
            $return .= "<br />  File: $error->file";
        }

        return $return;
    }


    private function send_notification_email($pending_action, $error_msg="")
    {
        $website = 'valuebasket';
        $platform_id = $this->platform_id;
        if($platform_id)
        {
            switch ($pending_action)
            {
                case "AF":
                    $message = $error_msg;
                    $title = "[$platform_id - $website] Authentication problems - AUTHENTICATION_FAILURE";
                    break;
                case "HE":
                    $message = "Please note that db http_info cannot be retrieved. Please check.";
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
                    $title = "[$platform_id - $website] Retrieve $platform_id Order problems - DATA_IMPORT_ERROR";
                    break;
                case "SC":
                    $message = $error_msg;
                    $title = "[$platform_id - $website] Retrieve $platform_id Order problems - SELLER_CHECK_ERROR";
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
                    $this->notification_email .= ",adrian@eservicesgroup.com";
                    break;
                case "AI":
                    $message = $error_msg;
                    $title = "[$platform_id - $website] Listing new item problems - ADD_ITEMS_ERROR";
                    $this->notification_email .= ",melody.yu@eservicesgroup.com";
                    break;
                case "ID":
                    $message = $error_msg;
                    $title = "[$platform_id - $website] Retrive product_content problems - GET_ITEM_DESCRIPTION";
                    break;
                case "QE":
                    $message = $error_msg;
                    $title = "[$platform_id - $website] Problem getting response from QOO10 - QOO10_RESPONSE_ERROR";
                    break;
            }
            mail($this->notification_email, $title, $message);

        }

        else
        {
            echo "DEBUG ERROR: qoo10_service line_no: " .__LINE__. ", please input platform_id";
        }

    }



}

