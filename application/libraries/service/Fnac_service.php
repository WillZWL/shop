<?php
defined('BASEPATH') OR exit('No direct script access allowed');
DEFINE ('PLATFORM_TYPE', 'FNAC');

class Fnac_service
{
    private $price_srv;
    private $http_obj;
    private $log_config;
    private $tokenForSession;

    private $notification_email = "celine@eservicesgroup.com, romuald@eservicesgroup.com, itsupport@eservicesgroup.com";
    //private $notification_email = "csng1017@gmail.com";
    private $debug = 0;
    private $enable_log = 0;

    public function __construct()
    {
        $CI =& get_instance();
        $CI->load->helper('url', 'string', 'object');
        $this->input=$CI->input;
        include_once(APPPATH."libraries/service/Http_connector.php");
        $this->set_http(new Http_connector());
        include_once(APPPATH."libraries/service/Schedule_job_service.php");
        $this->set_sj_srv(new Schedule_job_service());
        include_once(APPPATH."libraries/service/Batch_service.php");
        $this->set_batch_srv(new Batch_service());
        include_once(APPPATH."libraries/service/Platform_biz_var_service.php");
        $this->set_pbv_srv(new Platform_biz_var_service());
        include_once(APPPATH."libraries/service/Context_config_service.php");
        $this->set_config(new Context_config_service());
        include_once(APPPATH."libraries/service/Validation_service.php");
        $this->set_valid(new Validation_service());
        include_once(APPPATH."libraries/service/Http_info_service.php");
        $this->set_http_info(new Http_info_service());
        include_once(APPPATH."libraries/service/So_service.php");
        $this->set_so_srv(new So_service());
        include_once(APPPATH."libraries/dao/Transmission_log_dao.php");
        $this->set_iso_dao(new Interface_so_dao());
        include_once (APPPATH."libraries/dao/Interface_so_payment_status_dao.php");
        $this->set_isops_dao(new Interface_so_payment_status_dao());
        include_once (APPPATH."libraries/dao/Interface_client_dao.php");
        $this->set_ic_dao(new Interface_client_dao());
        include_once (APPPATH."libraries/dao/Interface_so_item_dao.php");
        $this->set_isoi_dao(new Interface_so_item_dao());
        include_once (APPPATH."libraries/dao/Interface_so_item_detail_dao.php");
        $this->set_isoid_dao(new Interface_so_item_detail_dao());
        include_once (APPPATH."libraries/dao/Exchange_rate_dao.php");
        $this->set_xrate_dao(new Exchange_rate_dao());
        include_once (APPPATH."libraries/dao/So_dao.php");
        $this->set_so_dao(new So_dao());
        include_once (APPPATH."libraries/dao/So_payment_status_dao.php");
        $this->set_sops_dao(new So_payment_status_dao());
        include_once(APPPATH."libraries/dao/So_priority_score_dao.php");
        $this->set_priority_score_dao(new So_priority_score_dao());
        include_once (APPPATH."libraries/dao/Client_dao.php");
        $this->set_client_dao(new Client_dao());
        include_once (APPPATH."libraries/dao/So_item_dao.php");
        $this->set_soi_dao(new So_item_dao());
        include_once (APPPATH."libraries/dao/So_item_detail_dao.php");
        $this->set_soid_dao(new So_item_detail_dao());
        include_once (APPPATH."libraries/service/Product_service.php");
        $this->set_product_srv(new Product_service());
        include_once(APPPATH."libraries/service/Bundle_service.php");
        $this->set_bundle_srv(new Bundle_service());
        include_once APPPATH."libraries/service/Price_fnac_service.php";
        $this->set_price_srv(new Price_fnac_service());
        include_once APPPATH."libraries/service/Country_service.php";
        $this->set_country_srv(new Country_service());
        include_once APPPATH."libraries/service/Delivery_service.php";
        $this->set_del_srv(new Delivery_service());
        include_once APPPATH."libraries/service/Complementary_acc_service.php" ;
        $this->set_ca_service(new Complementary_acc_service());
        include_once(APPPATH."libraries/service/Supplier_service.php");
        $this->set_sup_srv(new Supplier_service());
    }

    public function get_http()
    {
        return $this->http;
    }

    public function set_http($value)
    {
        $this->http = $value;
    }

    public function get_sj_srv()
    {
        return $this->sj_srv;
    }

    public function set_sj_srv($value)
    {
        $this->sj_srv = $value;
    }

    public function get_batch_srv()
    {
        return $this->batch_srv;
    }

    public function set_batch_srv($value)
    {
        $this->batch_srv = $value;
    }

    public function get_pbv_srv()
    {
        return $this->pbv_srv;
    }

    public function set_pbv_srv($value)
    {
        $this->pbv_srv = $value;
    }

    public function get_config()
    {
        return $this->config;
    }

    public function set_config($value)
    {
        $this->config = $value;
    }

    public function get_price_srv()
    {
        return $this->price_srv;
    }

    public function set_price_srv($value)
    {
        $this->price_srv = $value;
    }

    public function get_valid()
    {
        return $this->valid;
    }

    public function set_valid($value)
    {
        $this->valid = $value;
    }

    public function get_http_info()
    {
        return $this->http_info;
    }

    public function set_http_info($value)
    {
        $this->http_info = $value;
    }

    public function get_so_srv()
    {
        return $this->so_srv;
    }

    public function set_so_srv($value)
    {
        $this->so_srv = $value;
    }

    public function get_iso_dao()
    {
        return $this->iso_dao;
    }

    public function set_iso_dao(Base_dao $value)
    {
        $this->iso_dao = $value;
    }

    public function get_isops_dao()
    {
        return $this->isops_dao;
    }

    public function set_isops_dao(Base_dao $value)
    {
        $this->isops_dao = $value;
    }

    public function get_ic_dao()
    {
        return $this->ic_dao;
    }

    public function set_ic_dao(Base_dao $dao)
    {
        $this->ic_dao = $dao;
    }

    public function get_isoi_dao()
    {
        return $this->isoi_dao;
    }

    public function set_isoi_dao(Base_dao $dao)
    {
        $this->isoi_dao = $dao;
    }

    public function get_isoid_dao()
    {
        return $this->isoid_dao;
    }

    public function set_isoid_dao(Base_dao $dao)
    {
        $this->isoid_dao = $dao;
    }

    public function get_xrate_dao()
    {
        return $this->xrate_dao;
    }

    public function set_xrate_dao(Base_dao $dao)
    {
        $this->xrate_dao = $dao;
    }

    public function get_so_dao()
    {
        return $this->so_dao;
    }

    public function set_so_dao(Base_dao $value)
    {
        $this->so_dao = $value;
    }

    public function get_sops_dao()
    {
        return $this->sops_dao;
    }

    public function set_sops_dao(Base_dao $value)
    {
        $this->sops_dao = $value;
    }

    public function get_priority_score_dao()
    {
        return $this->priority_score_dao;
    }

    public function set_priority_score_dao(Base_dao $value)
    {
        $this->priority_score_dao = $value;
    }

    public function get_client_dao()
    {
        return $this->client_dao;
    }

    public function set_client_dao(Base_dao $dao)
    {
        $this->client_dao = $dao;
    }

    public function get_soi_dao()
    {
        return $this->soi_dao;
    }

    public function set_soi_dao(Base_dao $dao)
    {
        $this->soi_dao = $dao;
    }

    public function get_soid_dao()
    {
        return $this->soid_dao;
    }

    public function set_soid_dao(Base_dao $dao)
    {
        $this->soid_dao = $dao;
    }

    public function get_product_srv()
    {
        return $this->product_srv;
    }

    public function set_product_srv($value)
    {
        $this->product_srv = $value;
    }

    public function get_bundle_srv()
    {
        return $this->bundle_srv;
    }

    public function set_bundle_srv($value)
    {
        $this->bundle_srv = $value;
    }

    public function get_country_srv()
    {
        return $this->country_srv;
    }

    public function set_country_srv($value)
    {
        $this->country_srv = $value;
    }

    public function get_del_srv()
    {
        return $this->del_srv;
    }

    public function set_del_srv($value)
    {
        $this->del_srv = $value;
    }

    public function get_tokenForSession()
    {
        return $this->tokenForSession;
    }

    public function set_tokenForSession($value)
    {
        $this->tokenForSession = $value;
    }

    public function get_debug()
    {
        return $this->debug;
    }

    public function set_debug($value)
    {
        $this->debug = $value;
    }

    public function get_enable_log()
    {
        return $this->enable_log;
    }

    public function set_enable_log($value)
    {
        $this->enable_log = $value;
    }

    public function get_ca_service()
    {
        return $this->ca_service;
    }

    public function set_ca_service($value)
    {
        $this->ca_service = $value;
    }

    public function get_sup_srv()
    {
        return $this->sup_srv;
    }

    public function set_sup_srv($value)
    {
        $this->sup_srv = $value;
    }

    public function init_http_obj()
    {
        $http_name = "fnac_{$this->country_id}";
        $http_type = "D";
        if(strpos($_SERVER["HTTP_HOST"], "dev") === FALSE && !$this->debug)
        {
            # in development, always go to test shop
            $http_type = "P";
        }

        // $http_type = "P";
        if (!$this->http_obj)
        {
            if(!$this->http_obj = $this->get_http_info()->get(array("name"=>$http_name, "type"=>$http_type)))
            {
                $this->send_notification_email("HTTP_INFO_ERROR");
                exit;
            }
        }
        // echo "Connecting to " . $this->http_obj->get_server()."<br>";
    }

    public function init_api_call()
    {
        if(empty($this->country_id))
            $this->country_id = "ES";

        if(!$this->get_tokenForSession())
        {
            $tokenForSession = $this->send_authentication_request();
            $this->set_tokenForSession($tokenForSession);
        }
    }

    public function do_post_request($call_name, $data, $optional_headers = null)
    {
        $this->add_log("request", $call_name, $data);
        $this->init_http_obj();
        $ch = curl_init();
        $url = $this->http_obj->get_server() . $call_name;
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        // curl_setopt($ch, CURLOPT_SSLVERSION , 3);
        $response = curl_exec($ch);
        $error = curl_error($ch);
        $errno = curl_errno($ch);
        $curlinfo = $this->format_arr_to_str(curl_getinfo($ch));
        curl_close($ch);
    /*
        //debugmail
        if(is_array($response))
            $response_str = $this->format_arr_to_str($response);
        else
            $response_str = $response;
        $data_str = $this->format_arr_to_str($data);
        mail("ping@eservicesgroup.com", "FNACES DO_POST_REQUEST", __LINE__."$url \r\n ------------------------------- data:\r\n $data_str \r\n "
                                    ."---------------------------------------------- \r\n $curlinfo \r\n\r\n --------------------------- $response_str \r\n\r\n ---------------------- \r\n".$error);
    */
        if ($response)
        {
            return $response;
        }
        else
        {
            $error_message = "fnac_service ".__LINE__." Call name: $call_name \nCurl err: [errno: {$errno}] $error, \nCurl header: $curlinfo \r\n postdata: ".$this->format_arr_to_str($data);
            $this->send_notification_email("CONNECT_ERROR", $error_message);
            return false;
        }

    }

    public function call_fnac_api($xml, $call_name)
    {
        libxml_use_internal_errors(true);
        $valid = $this->xml_schema_validation($xml, $call_name);
        if($valid = $this->xml_schema_validation($xml, $call_name))
        {
            if($response = $this->do_post_request($call_name, $xml))
            {
            /*
                //debugmail
                mail("ping@eservicesgroup.com", "FNAC call_fnac_api", __LINE__. " callname: $call_name \r\n\n xml \r\n".$xml . "\r\n\r\n ------------------------------------------ \r\n\ " . $response);
            */
                $xmlResponse = simplexml_load_string(trim($response), 'SimpleXMLElement', LIBXML_NOCDATA);

                if(!$xmlResponse)
                {
                    $this->_call_fnac_api_error_message = __LINE__. " Error in xml response. \r\nResponse: $xmlResponse \r\nXML error: " .$this->_xml_error_to_string();
                }
                else
                {
                    if($xmlResponse->attributes()->status != "OK" && $xmlResponse->attributes()->status != "RUNNING"  && $xmlResponse->attributes()->status != "ACTIVE")
                    {
                        if($xmlResponse->error->attributes()->code != "ERR_023")  #Batch not found
                            $this->send_notification_email("API_CALL_ERROR", "fnac_service ".__LINE__." API call: " . $call_name . "\n\nRequest: " . $xml . "\n\nResponse: " . $response);
                    }

                    return $xmlResponse;
                }
            }
            else
            {
                $this->_call_fnac_api_error_message = __LINE__." Error in post request \r\nXML: \r\n". $xml;
            }
        }
        else
        {
            $this->_call_fnac_api_error_message = __LINE__." Not valid XML \r\nXML: \r\n". $xml;
        }

        $this->send_notification_email("API_CALL_ERROR", $this->_call_fnac_api_error_message);
        return false;
    }

    private function _xml_error_to_string()
    {
        foreach(libxml_get_errors() as $error)
        {
            $error_message .= "\n". $error->message;
        }
        return $error_message;
    }

    public function send_authentication_request()
    {
        $this->init_http_obj();
        include_once(BASEPATH."libraries/Encrypt.php");
        $encrypt = new CI_Encrypt();
        $cert_key = $encrypt->decode($this->http_obj->get_signature());
        $xml =
"<?xml version='1.0' encoding='utf-8'<auth xmlns='http://www.fnac.com/schemas/mp-dialog.xsd'>
    <partner_id>" . $this->http_obj->get_username() . "</partner_id>
    <shop_id>" . $this->http_obj->get_application_id() . "</shop_id>
    <key>" . $cert_key . "</key>
</auth>";


        if(@$this->xml_schema_validation($xml, "auth"))
        {
            if($response = $this->do_post_request("auth", $xml))
            {
                if($xmlResponse = simplexml_load_string(trim($response), 'SimpleXMLElement', LIBXML_NOCDATA))
                {
                    if($xmlResponse->attributes()->status == "OK")
                    {
                        return $xmlResponse->token;
                    }
                }
                $this->send_notification_email("AUTHENTICATION_ERROR", "\n\nRequest: " . $xml . "\n\nResponse: " . $response);
            }
        }

        return false;
    }

    public function format_arr_to_str($dataarr=array())
    {
        /* this function formats array into string for email */

        $message = "";
        if(is_array($dataarr))
        {
            foreach ($dataarr as $key => $value)
            {
                if(is_array($value))
                {
                    $message .= $this->format_arr_to_str($value);
                }
                else
                {
                    $message .= "\n$key: $value";
                }
            }
        }

        if($message == "")
            $message = $dataarr;

        return $message;
    }

    public function get_fnac_add_or_update_item_xml($sku_list = array(), $tokenForSession)
    {
        // We do not delete listings on FNAC, if unlist, just set quantity to 0 //

        $ret["status"] = false;
        if(empty($sku_list))
        {
            return $ret;
        }

        if(!is_array($sku_list))
        {
            $sku_list = (array)$sku_list;
        }

        if($sku_list)
        {

            $xml =
"<?xml version='1.0' encoding='utf-8'<offers_update partner_id='" . $this->http_obj->get_username() . "' shop_id='" . $this->http_obj->get_application_id() . "' token='" . $tokenForSession . "' xmlns='http://www.fnac.com/schemas/mp-dialog.xsd'>";

            $count = 0;
            foreach($sku_list as $sku)
            {
                if($item_dto = $this->get_product_srv()->get_fnac_additem_info(array("p.sku"=>$sku, "platform_id"=>"FNAC".$this->country_id), array("limit"=>1)))
                {
                    if(!$item_dto->get_ean())
                    {
                        $this->send_notification_email("OFFERS_QUERY_ERROR", __LINE__." No EAN found for {$item_dto->get_sku()}");
                        continue;
                    }

                    # if product set as Not Listed, set quantity to 0
                    if($item_dto->get_listing_status() == 'N')
                    {
                        $quantity = 0;
                    }
                    else
                    {
                        $quantity = $item_dto->get_ext_qty();
                    }

                    if($item_dto)
                    {
                        $xml .= "
  <offer>";

                        $xml .= "
    <product_reference type='Ean'>" . $item_dto->get_ean() . "</product_reference>
    <offer_reference type='SellerSku'>" . $item_dto->get_sku() . "</offer_reference>
    <price>" . $item_dto->get_price() . "</price>
    <product_state>11</product_state>
    <quantity>" . $quantity . "</quantity>";

                        if(trim($item_dto->get_note()) != "")
                        {
                            $xml .= "
    <description><![CDATA[" . $item_dto->get_note() . "]]></description>";
                        }

                        $xml .= "
  </offer>";
                        $count++;
                    }
                }
            }

            $xml .="
</offers_update>";

            $ret["xml"] = $xml;

            if($count >0)
                $ret["status"] = true;

            return $ret;
        }
    }

    public function send_offers_update_request($item_list = array(), $country_id = "ES")
    {
        $this->country_id = $country_id;
        $this->init_api_call();
        $this->process = "send_offers_update_request";

        if(is_array($item_list))
            $item_list_str = implode(" , ", $item_list);
        else
            $item_list_str = $item_list;

        if($tokenForSession = $this->get_tokenForSession())
        {
            $xml_ret = $this->get_fnac_add_or_update_item_xml($item_list, $tokenForSession);
            $xml = $xml_ret["xml"];
            if($xml_ret["status"] === true)
            {
                if($this->xml_schema_validation($xml, "offers_update"))
                {
                    if($response = $this->do_post_request("offers_update", $xml))
                    {
                        $xmlResponse = simplexml_load_string(trim($response), 'SimpleXMLElement', LIBXML_NOCDATA);


                        //debugmail
                        // mail("ping@eservicesgroup.com", "FNACES send_offers_update_request", __LINE__. " \r\n xml \r\n" . $xml . "\r\n\r ------------------------------ response \r\n\r" . $response);


                        if($xmlResponse === false)
                        {
                            $error_message = __LINE__ . $this->_xml_error_to_string();
                            $this->send_notification_email("UPDATE_OFFER_ERROR", $error_message);
                        }
                        else
                        {
                            if($xmlResponse->attributes()->status == "OK")
                            {
                                if($batch_id = $xmlResponse->batch_id)
                                {
                                    return $this->send_batch_status_request($batch_id, $country_id);
                                    // $batch_response = $this->send_batch_status_request($batch_id, $country_id);
                                    // var_dump($batch_response);
                                    // die();
                                }
                            }
                            else
                            {
                                $error_message = __LINE__ . " fnac_service \nitem SKUs: $item_list_str \nstatus error: " . $xmlResponse->attributes()->status;
                                $this->send_notification_email("UPDATE_OFFER_ERROR", $error_message);
                                // mail($this->notification_email, "Update Offer Error", __LINE__ . " status error: " . $xmlResponse->attributes()->status, "From: Admin <itsupport@eservicesgroup.net>\r\n");
                            }

                        }
                    }
                    else
                    {
                        // mail($this->notification_email, "Update Offer Error", __LINE__ . " post error: " . $response, "From: Admin <itsupport@eservicesgroup.net>\r\n");
                    }
                }
                else
                {
                    $error_message = __LINE__ . " fnac_service. \nitem SKUs: $item_list_str \nxml validation error: " . $xml;
                    $this->send_notification_email("UPDATE_OFFER_ERROR", $error_message);
                    // mail($this->notification_email, "Update Offer Error", __LINE__ . " xml validation error: " . $xml, "From: Admin <itsupport@eservicesgroup.net>\r\n");
                }
            }
            else
            {
                $error_message = __LINE__ . " fnac_service. \nThis may be triggered due to empty EAN. Pls check for email(s) with subject 'Fnac Offers Query Warning'.\nitem SKUs: $item_list_str \nxml: " . $xml;
                // $this->send_notification_email("UPDATE_OFFER_ERROR", $error_message);
                // mail($this->notification_email, "Update Offer Error", __LINE__ . " xml: " . $xml, "From: Admin <itsupport@eservicesgroup.net>\r\n");
            }
        }
        else
        {
            $error_message = __LINE__ . " fnac_service. \nitem SKUs: $item_list_str \ntokenForSession: " . $tokenForSession;
            $this->send_notification_email("UPDATE_OFFER_ERROR", $error_message);
            // mail($this->notification_email, "Update Offer Error", __LINE__ . " tokenForSession: " . $tokenForSession, "From: Admin <itsupport@eservicesgroup.net>\r\n");
        }

        if($error_message)
            $ret["error_message"] = $error_message;

        $ret["status"] = false;
        return $ret;
    }

    public function check_unlist_item($item_list = array(), $country_id = "ES")
    {
        $existing_offerlist = array();
        if($item_list)
        {
            if(!is_array($item_list))
                $item_list = (array)$item_list;

            $unlisted_item_list = $this->get_database_unlisted_items($item_list, $country_id);
            if($unlisted_item_list)
            {
                // check on FNAC to see if the item is still listed on FNAC
                if($xml = $this->get_fnac_offersquery_byskulist_xml($item_list))
                {
                    if($this->xml_schema_validation($xml, "offers_query"))
                    {
                        $response = $this->do_post_request("offers_query", $xml);
                        if($response)
                        {
                            $xmlResponse = simplexml_load_string(trim($response), 'SimpleXMLElement', LIBXML_NOCDATA);
                            $status = $xmlResponse->attributes()->status;
                            if($status == "OK")
                            {
                                if(isset($xmlResponse->offer))
                                {
                                    foreach($xmlResponse->offer as $offer)
                                    {
                                        $existing_offerlist[] = (string)$offer->offer_seller_id;
                                    }
                                }
                            }
                            else
                            {
                                $error_message = __LINE__ . " fnac_service \nFail to retrieve offers info for SKUs ". implode("," , $item_list) . " \r\n XML Response: $response ";
                                $this->send_notification_email("OFFERS_QUERY_ERROR", $error_message);
                            }
                        }
                    }
                    else
                    {
                        $error_message = __LINE__ . " fnac_service. \nFail to retrieve offers at page {$this->page} \nxml validation error: " . $xml;
                        $this->send_notification_email("OFFERS_QUERY_ERROR", $error_message);
                    }
                }
            }
        }

        return $existing_offerlist;
    }

    public function send_orders_query_request($start_time = "", $end_time = "", $date_type = "CreatedAt", $order_status = "Created", $paging = 1)
    {
        $this->init_api_call();
        $this->process = "send_orders_query_request";

        if($this->get_tokenForSession())
        {
            $curr_time = mktime();
            if($start_time == "")
            {
                $start_time = date("Y-m-d\TH:i:s+02:00", $curr_time - 3600 * 24 * 14);
            }
            else
            {
                $start_time = $start_time."T00:00:00+02:00";
            }
            if($end_time == "")
            {
                $end_time = date("Y-m-d\TH:i:s+02:00", $curr_time + 24 * 60 * 60);
            }
            else
            {
                $end_time = $end_time."T23:59:59+02:00";
            }

            $xml =
"<?xml version='1.0' encoding='utf-8'<orders_query results_count='100' partner_id='" . $this->http_obj->get_username() . "' shop_id='" . $this->http_obj->get_application_id() . "' token='" . $this->get_tokenForSession() . "' xmlns='http://www.fnac.com/schemas/mp-dialog.xsd'>
  <paging>$paging</paging>
  <date type='" . $date_type . "'>
    <min>" . $start_time . "</min>
    <max>" . $end_time . "</max >
  </date>
  <state>" . $order_status . "</state>
  <sort_by>ASC</sort_by>
</orders_query>";

            if($xmlResponse = $this->call_fnac_api($xml, "orders_query"))
            {
                if($xmlResponse->attributes()->status == "OK")
                {
                    return $xmlResponse;
                }
            }
        }

        return false;
    }

    public function get_fnac_offersquery_byskulist_xml($unlisted_sku_arr=array())
    {
        $this->init_api_call();
        $this->process = "get_fnac_offersquery_byskulist_xml";

        if(empty($unlisted_sku_arr))
            return false;
        else
        {
            if(!is_array($unlisted_sku_arr))
                $unlisted_sku_arr = (array)$unlisted_sku_arr;
        }

        if($tokenForSession = $this->get_tokenForSession())
        {
            $xml =
                "<?xml version='1.0' encoding='utf-8'               <offers_query results_count='100' partner_id='" . $this->http_obj->get_username() . "' shop_id='" . $this->http_obj->get_application_id() . "' token='" . $tokenForSession . "' xmlns='http://www.fnac.com/schemas/mp-dialog.xsd'>
                 ";
            foreach ($unlisted_sku_arr as $key => $sku)
            {
                $xml .= "<offer_seller_id>$sku</offer_seller_id>";
            }
            $xml .= "</offers_query>";

            return $xml;
        }

        return false;
    }

    public function get_fnac_offersquery_bydate_xml($start_date="", $end_date, $page=1, $date_type = "Created")
    {
        $this->init_api_call();
        $this->process = "get_fnac_offersquery_bydate_xml";
        if($this->get_tokenForSession())
        {
            $curr_time = mktime();
            if($start_date == "")
            {
                $start_date = "2014-01-01";
            }
            if($end_date == "")
            {
                $end_date = date("Y-m-d", $curr_time);
            }

            $start_ts = $start_date."T00:00:00+02:00";
            $end_ts = $end_date."T23:59:59+02:00";

            $xml =
                "<?xml version='1.0' encoding='utf-8'               <offers_query results_count='100' partner_id='" . $this->http_obj->get_username() . "' shop_id='" . $this->http_obj->get_application_id() . "' token='" . $this->get_tokenForSession() . "' xmlns='http://www.fnac.com/schemas/mp-dialog.xsd'>
                  <paging>$page</paging>
                  <date type='" . $date_type . "'>
                    <min>" . $start_ts . "</min>
                    <max>" . $end_ts . "</max >
                  </date>
                </offers_query>";

            return $xml;
        }

        return false;
    }

    public function send_orders_ack_request($so_list)
    {
        $this->init_api_call();
        $this->process = "send_orders_ack_request";
        if($so_list)
        {
            if($this->get_tokenForSession())
            {
                $this->init_http_obj();

                $xml =
"<?xml version='1.0' encoding='utf-8'<orders_update partner_id='" . $this->http_obj->get_username() . "' shop_id='" . $this->http_obj->get_application_id() . "' token='" . $this->get_tokenForSession() . "' xmlns='http://www.fnac.com/schemas/mp-dialog.xsd'>";

                foreach($so_list as $so_obj)
                {
                    $xml .= "
  <order order_id='" . $so_obj->get_platform_order_id() . "' action='accept_all_orders'>
    <order_detail>
      <action>Accepted</action>
    </order_detail>
  </order>";
            }

                $xml .= "
</orders_update>";

                $xmlResponse = $this->call_fnac_api($xml, "orders_update");

                return $xmlResponse;
            }
        }

        return false;
    }

    public function send_check_pending_payment_request($country_id = "ES")
    {
        $platform_id = "FNAC".$country_id;
        $this->process = "send_check_pending_payment_request";

        $num_rows = $this->get_so_srv()->get_fnac_pending_payment_orders(array("platform_id"=>$platform_id), array("num_rows"=>1));

        $n = ceil($num_rows / 50);
        for($i = 0; $i < $n; $i++)
        {
            $offset = $i*50;
            if($pending_list = $this->get_so_srv()->get_fnac_pending_payment_orders(array("platform_id"=>$platform_id), array("offset"=>$offset, "limit"=>50)))
            {
                $this->init_api_call();

                if($this->get_tokenForSession())
                {
                    $xml =
"<?xml version='1.0' encoding='utf-8'<orders_query results_count='100' partner_id='" . $this->http_obj->get_username() . "' shop_id='" . $this->http_obj->get_application_id() . "' token='" . $this->get_tokenForSession() . "' xmlns='http://www.fnac.com/schemas/mp-dialog.xsd'>
  <paging>1</paging>
  <orders_fnac_id>";

                    foreach($pending_list as $so_obj)
                    {
                        $xml .=
"
    <order_fnac_id>" . $so_obj->get_platform_order_id() . "</order_fnac_id>";
                    }

                    $xml .= "
  </orders_fnac_id>
</orders_query>";

                    if($xmlResponse = $this->call_fnac_api($xml, "orders_query"))
                    {
                        if($xmlResponse->attributes()->status == "OK")
                        {
                            return $xmlResponse;
                        }
                    }
                }
            }

            return false;
        }
    }

    public function send_orders_fulfilled_request($fulfill_list = array())
    {
        $this->process = "send_orders_fulfilled_request";

        if($fulfill_list)
        {
            $this->init_api_call();

            if($this->get_tokenForSession())
            {
                $this->init_http_obj();

                $xml =
"<?xml version='1.0' encoding='utf-8'<orders_update partner_id='" . $this->http_obj->get_username() . "' shop_id='" . $this->http_obj->get_application_id() . "' token='" . $this->get_tokenForSession() . "' xmlns='http://www.fnac.com/schemas/mp-dialog.xsd'>";

                foreach($fulfill_list as $fulfill_dto)
                {
                    $xml .= "
  <order order_id='" . $fulfill_dto->get_platform_order_id() . "' action='confirm_all_to_send'>
    <order_detail>
      <action>Shipped</action>
      <tracking_number>" . $fulfill_dto->get_tracking_no() . "</tracking_number>
      <tracking_company>" . $fulfill_dto->get_courier_id() . "</tracking_company>
    </order_detail>
  </order>";
                }

                $xml .= "
</orders_update>";

                if($xmlResponse = $this->call_fnac_api($xml, "orders_update"))
                {
                    return $xmlResponse;
                }
            }
        }

        return false;
    }

    public function send_batch_status_request($batch_id, $country_id= "ES")
    {
        $this->country_id = $country_id;

        $this->init_api_call();
        $this->process .= " > send_batch_status_request";
        if($tokenForSession = $this->get_tokenForSession())
        {
            $xml =
"<?xml version='1.0' encoding='utf-8'<batch_status partner_id='" . $this->http_obj->get_username() . "' shop_id='" . $this->http_obj->get_application_id() . "' token='" . $tokenForSession . "' xmlns='http://www.fnac.com/schemas/mp-dialog.xsd'>
  <batch_id>" . $batch_id . "</batch_id>
</batch_status>";

            $xmlResponse = $this->call_fnac_api($xml, "batch_status");

            return $xmlResponse;
        }

        return false;
    }

    public function get_fnac_orders_list($country_id = "ES")
    {
        $order = array();
        set_time_limit(300);
        $this->country_id = $country_id;
        $selling_platform_id = "FNAC".strtoupper($country_id);

        $filename = "FNAC{$country_id}_orderlist_".date("Ymd_His").".csv";
        $now_ts = mktime();
        $allstatus = array("Created","Accepted","Refused","ToShip","Shipped","NotReceived","Received","Cancelled","Refunded","Error");
        $start_time = $end_time = $order_status = "";
        if($_GET["start"])
            $start_time = $_GET["start"];
        if($_GET["end"])
            $end_time = $_GET["end"];
        if($_GET["order_status"])
            $selectedstatus = $_GET["order_status"];

        if($start_time == "")
            $start_time = date("Y-m-d", $now_ts - 90 * 24 * 60 * 60);
        if($end_time == "")
            $end_time = date("Y-m-d", $now_ts + 24 * 60 * 60);

        $make_header = TRUE;

        foreach ($allstatus as $status)
        {
            # if order status is specified, we ignore the rest
            if($order_status != "" && $status != $selectedstatus)
                continue;

            // if($xml = $this->send_orders_query_request($start_time, $end_time, "CreatedAt", "Accepted"))
            if($xml = $this->send_orders_query_request($start_time, $end_time, "CreatedAt", $status))
            {
                if($xml->attributes()->status == "OK")
                {
                    if(isset($xml->order))
                    {
                        $csv .= $this->orderobjecttocsv($xml, $make_header);

                        # if the first status already has orders, means we created headers, so set it to false
                        # else, it will create headers when the next status with orders come
                        $make_header = FALSE;
                    }

                    $totalpage = $xml->total_paging;
                    if($totalpage > 1)
                    {
                        for ($i=2; $i <= $totalpage ; $i++)
                        {
                            if($addon_xml = $this->send_orders_query_request($start_time, $end_time, "CreatedAt", $status, $i))
                            {
                                if($addon_xml->attributes()->status == "OK")
                                {
                                    if(isset($addon_xml))
                                    {
                                        $csv .= $this->orderobjecttocsv($addon_xml);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        ob_end_clean();
        header( 'Content-Type: text/csv' );
        header( 'Content-Disposition: attachment;filename='.$filename);
        $fp = fopen('php://output', 'w');
        fwrite($fp, $csv);
        fclose($fp);

    }

    private function orderobjecttocsv($orderxml, $make_header = FALSE)
    {
        $csv = "";
        $headercsv = "shop_id,client_id,client_firstname,client_lastname,order_id,state,created_at,fees,nb_messages,ship_firstname,ship_lastname,ship_company,ship_address1,ship_address2,ship_address3,ship_zipcode,ship_city,ship_country,ship_phone,ship_mobile,bill_firstname,bill_lastname,bill_company,bill_address1,bill_address2,bill_address3,bill_zipcode,bill_city,bill_country,bill_phone,bill_mobile,product_name,order_detail_id,state,quantity,price,fees,product_fnac_id,offer_fnac_id,offer_seller_id,product_state,description,internal_comment,shipping_price,shipping_method,created_at,received_at,product_url,tracking_number,\n";

        if($orderxml)
        {
            if($orderarr = $this->convertobjectotarray($orderxml))
            {

                if(is_array($orderarr))
                {
                    # if only one order, then response is not multidimensional array. So we form a new orderlist multidimensional array.
                    if(!is_array($orderarr["order"][0]))
                        $orderlist[0] = $orderarr["order"];
                    else
                        $orderlist = $orderarr["order"];


                    foreach ($orderlist as $key => $value)
                    {
                        $maininfo = "";
                        // one order may have multiple items, so we create a main info for every new order
                        $maininfo .= "{$value["shop_id"]},{$value["client_id"]},{$value["client_firstname"]},{$value["client_lastname"]},{$value["order_id"]},{$value["state"]},{$value["created_at"]},{$value["fees"]},{$value["nb_messages"]},";

                        if(is_array($value["shipping_address"]))
                        {
                            $shipadd = $value["shipping_address"];
                            // implode because some fields comes in a multidimensional array
                            // we also construct each row because some fields may be empty, causing csv columns to shift

                            if(is_array($shipadd["firstname"])) $shipadd["firstname"] = implode(" || ", $shipadd["firstname"]);
                            $maininfo .= str_replace(',', ' ', $shipadd["firstname"]). ",";

                            if(is_array($shipadd["lastname"]))  $shipadd["lastname"] = implode(" || ", $shipadd["lastname"]);
                            $maininfo .= str_replace(',', ' ', $shipadd["lastname"]). ",";

                            if(is_array($shipadd["company"]))   $shipadd["company"] = implode(" || ", $shipadd["company"]);
                            $maininfo .= str_replace(',', ' ', $shipadd["company"]). ",";

                            if(is_array($shipadd["address1"]))  $shipadd["address1"] = implode(" || ", $shipadd["address1"]);
                            $maininfo .= str_replace(',', ' ', $shipadd["address1"]). ",";

                            if(is_array($shipadd["address2"]))  $shipadd["address2"] = implode(" || ", $shipadd["address2"]);
                            $maininfo .= str_replace(',', ' ', $shipadd["address2"]). ",";

                            if(is_array($shipadd["address3"]))  $shipadd["address3"] = implode(" || ", $shipadd["address3"]);
                            $maininfo .= str_replace(',', ' ', $shipadd["address3"]). ",";

                            if(is_array($shipadd["zipcode"]))   $shipadd["zipcode"] = implode(" || ", $shipadd["zipcode"]);
                            $maininfo .= str_replace(',', ' ', $shipadd["zipcode"]). ",";

                            if(is_array($shipadd["city"]))      $shipadd["city"] = implode(" || ", $shipadd["city"]);
                            $maininfo .= str_replace(',', ' ', $shipadd["city"]). ",";

                            if(is_array($shipadd["country"]))   $shipadd["country"] = implode(" || ", $shipadd["country"]);
                            $maininfo .= str_replace(',', ' ', $shipadd["country"]). ",";

                            if(is_array($shipadd["phone"]))     $shipadd["phone"] = implode(" || ", $shipadd["phone"]);
                            $maininfo .= str_replace(',', ' ', $shipadd["phone"]). ",";

                            if(is_array($shipadd["mobile"]))        $shipadd["mobile"] = implode(" || ", $shipadd["mobile"]);
                            $maininfo .= str_replace(',', ' ', $shipadd["mobile"]). ",";

                        }
                        else
                        {
                            $maininfo .= ",,,,,,,,,";
                        }

                        if(is_array($value["billing_address"]))
                        {
                            $billadd = $value["billing_address"];

                            // implode because some fields comes in a multidimensional array
                            // we also construct each row because some fields may be empty, causing csv columns to shift

                            if(is_array($billadd["firstname"])) $billadd["firstname"] = implode(" || ", $billadd["firstname"]);
                            $maininfo .= str_replace(',', ' ', $billadd["firstname"]). ",";

                            if(is_array($billadd["lastname"]))  $billadd["lastname"] = implode(" || ", $billadd["lastname"]);
                            $maininfo .= str_replace(',', ' ', $billadd["lastname"]). ",";

                            if(is_array($billadd["company"]))   $billadd["company"] = implode(" || ", $billadd["company"]);
                            $maininfo .= str_replace(',', ' ', $billadd["company"]). ",";

                            if(is_array($billadd["address1"]))  $billadd["address1"] = implode(" || ", $billadd["address1"]);
                            $maininfo .= str_replace(',', ' ', $billadd["address1"]). ",";

                            if(is_array($billadd["address2"]))  $billadd["address2"] = implode(" || ", $billadd["address2"]);
                            $maininfo .= str_replace(',', ' ', $billadd["address2"]). ",";

                            if(is_array($billadd["address3"]))  $billadd["address3"] = implode(" || ", $billadd["address3"]);
                            $maininfo .= str_replace(',', ' ', $billadd["address3"]). ",";

                            if(is_array($billadd["zipcode"]))   $billadd["zipcode"] = implode(" || ", $billadd["zipcode"]);
                            $maininfo .= str_replace(',', ' ', $billadd["zipcode"]). ",";

                            if(is_array($billadd["city"]))      $billadd["city"] = implode(" || ", $billadd["city"]);
                            $maininfo .= str_replace(',', ' ', $billadd["city"]). ",";

                            if(is_array($billadd["country"]))   $billadd["country"] = implode(" || ", $billadd["country"]);
                            $maininfo .= str_replace(',', ' ', $billadd["country"]). ",";

                            if(is_array($billadd["phone"]))     $billadd["phone"] = implode(" || ", $billadd["phone"]);
                            $maininfo .= str_replace(',', ' ', $billadd["phone"]). ",";

                            if(is_array($billadd["mobile"]))        $billadd["mobile"] = implode(" || ", $billadd["mobile"]);
                            $maininfo .= str_replace(',', ' ', $billadd["mobile"]). ",";

                        }
                        else
                        {
                            $maininfo .= ",,,,,,,,,";
                        }

                        // record each order item details, else create new line
                        if(is_array($value["order_detail"]))
                        {
                            // order_detail may be in array or multidimensional array (if multiple items in one order)
                            if(!is_array($value["order_detail"][0]))
                                $orderdetaillist[0] = $value["order_detail"];
                            else
                                $orderdetaillist = $value["order_detail"];

                            foreach ($orderdetaillist as $orderdk => $orderdv)
                            {
                                $csv .= $maininfo;
                                # loop through item details
                                if(is_array($orderdv["product_name"]))      $orderdv["product_name"] = implode(" || ", $orderdv["product_name"]);
                                $csv .= str_replace(',', ' ', $orderdv["product_name"]) . ",";
                                if(is_array($orderdv["order_detail_id"]))   $orderdv["order_detail_id"] = implode(" || ", $orderdv["order_detail_id"]);
                                $csv .= str_replace(',', ' ', $orderdv["order_detail_id"]) . ",";
                                if(is_array($orderdv["state"]))             $orderdv["state"] = implode(" || ", $orderdv["state"]);
                                $csv .= str_replace(',', ' ', $orderdv["state"]) . ",";
                                if(is_array($orderdv["quantity"]))          $orderdv["quantity"] = implode(" || ", $orderdv["quantity"]);
                                $csv .= str_replace(',', ' ', $orderdv["quantity"]) . ",";
                                if(is_array($orderdv["price"]))             $orderdv["price"] = implode(" || ", $orderdv["price"]);
                                $csv .= str_replace(',', ' ', $orderdv["price"]) . ",";
                                if(is_array($orderdv["fees"]))              $orderdv["fees"] = implode(" || ", $orderdv["fees"]);
                                $csv .= str_replace(',', ' ', $orderdv["fees"]) . ",";
                                if(is_array($orderdv["product_fnac_id"]))   $orderdv["product_fnac_id"] = implode(" || ", $orderdv["product_fnac_id"]);
                                $csv .= str_replace(',', ' ', $orderdv["product_fnac_id"]) . ",";
                                if(is_array($orderdv["offer_fnac_id"]))     $orderdv["offer_fnac_id"] = implode(" || ", $orderdv["offer_fnac_id"]);
                                $csv .= str_replace(',', ' ', $orderdv["offer_fnac_id"]) . ",";
                                if(is_array($orderdv["offer_seller_id"]))   $orderdv["offer_seller_id"] = implode(" || ", $orderdv["offer_seller_id"]);
                                $csv .= str_replace(',', ' ', $orderdv["offer_seller_id"]) . ",";
                                if(is_array($orderdv["product_state"]))     $orderdv["product_state"] = implode(" || ", $orderdv["product_state"]);
                                $csv .= str_replace(',', ' ', $orderdv["product_state"]) . ",";
                                if(is_array($orderdv["description"]))       $orderdv["description"] = implode(" || ", $orderdv["description"]);
                                $csv .= str_replace(',', ' ', $orderdv["description"]) . ",";
                                if(is_array($orderdv["internal_comment"]))  $orderdv["internal_comment"] = implode(" || ", $orderdv["internal_comment"]);
                                $csv .= str_replace(',', ' ', $orderdv["internal_comment"]) . ",";
                                if(is_array($orderdv["shipping_price"]))    $orderdv["shipping_price"] = implode(" || ", $orderdv["shipping_price"]);
                                $csv .= str_replace(',', ' ', $orderdv["shipping_price"]) . ",";
                                if(is_array($orderdv["shipping_method"]))   $orderdv["shipping_method"] = implode(" || ", $orderdv["shipping_method"]);
                                $csv .= str_replace(',', ' ', $orderdv["shipping_method"]) . ",";
                                if(is_array($orderdv["created_at"]))        $orderdv["created_at"] = implode(" || ", $orderdv["created_at"]);
                                $csv .= str_replace(',', ' ', $orderdv["created_at"]) . ",";
                                if(is_array($orderdv["received_at"]))       $orderdv["received_at"] = implode(" || ", $orderdv["received_at"]);
                                $csv .= str_replace(',', ' ', $orderdv["received_at"]) . ",";
                                if(is_array($orderdv["product_url"]))       $orderdv["product_url"] = implode(" || ", $orderdv["product_url"]);
                                $csv .= str_replace(',', ' ', $orderdv["product_url"]) . ",";
                                if(is_array($orderdv["tracking_number"]))   $orderdv["tracking_number"] = implode(" || ", $orderdv["tracking_number"]);
                                $csv .= str_replace(',', ' ', $orderdv["tracking_number"]) . ",";
                                $csv .= "\n";   # end of each item
                            }
                        }
                        else
                            $csv .= "\n";   # no item detail found
                    }
                }
            }

        }
        if($make_header == TRUE)
            $csv = $headercsv . $csv;

        return $csv;

    }

    public function cron_retrieve_new_order($country_id = "ES", $start_time = "", $end_time = "")
    {
        //  https://marketplace.ws.fd-recette.net/partenaires/login?email=rod@eservicesgroup.net&password=marketplace

        $this->country_id = $country_id;
        $selling_platform_id = "FNAC".strtoupper($country_id);
        $ca_item_list = array();
        $this->debug_msg = "START SCRIPT";
        set_time_limit(300);
        $now_time = date("YmdHis", mktime());
        //$tpl_path = $this->get_config()->value_of("fnac_tpl_path");
        //if($xml = simplexml_load_file($tpl_path . 'xml_files/orders-query-response-sample.xml',"SimpleXMLElement",LIBXML_NOCDATA))

        if($xml = $this->send_orders_query_request($start_time, $end_time))
        {
            $this->totalfnaccount = $this->totalisocount = $this->totalsocount = 0;
            $batch_remark = "fnac_{$country_id}_valuebasket_".$now_time;
            $batch = $this->get_batch_srv()->get(array("remark"=>$batch_remark));
            if(empty($batch))
            {
                $batch_obj = $this->get_batch_srv()->get();
                $batch_obj->set_func_name("fnac_{$country_id}_valuebasket");
                $batch_obj->set_status("N");
                $batch_obj->set_listed("1");
                $batch_obj->set_remark($batch_remark);
                $this->get_batch_srv()->insert($batch_obj);
            }
            else
            {
                $this->debug_msg .= "ERROR: BATCH_ALREADY_EXIST {$batch->get_remark()}";
                $this->send_notification_email("BATCH_ALREADY_EXIST", "Remark: " . $batch->get_remark());
            }
            if($batch_obj)
            {
                $batch_id = $batch_obj->get_id();
                $this->debug_msg .= "<pre>Starting batch_id $batch_id</pre>";

                if(isset($xml->order))
                {
                    if($xml->order->state != 'Created')
                    {
                        // not new order
                        $this->debug_msg .= "<pre>ERROR: WRONG_ORDER_STATUS {$xml->order->state}</pre>";
                        $this->send_notification_email("WRONG_ORDER_STATUS", $xml);
                    }
                    foreach($xml->order as $order)
                    {
                        // initialize value
                        $client = $so = $soi = $soid = $sops = array();
                        $this->totalfnaccount++;

                        // interface client
                        include_once(BASEPATH."libraries/Encrypt.php");
                        $encrypt = new CI_Encrypt();

                        $client["ext_client_id"] = (string)$order->client_id;
                        $client["email"] = (string)$order->client_id . "@valuebasket.com";
                        $client["password"] = $encrypt->encode(time().(string)$order->client_id);
                        $client["forename"] = (string)$order->client_firstname;
                        if((string)$order->client_firstname == "")
                        {
                            $client["forename"] = (string)$order->client_lastname;
                        }
                        $client["surname"] = (string)$order->client_lastname;
                        if($order->billing_address)
                        {
                            $client["address_1"] = (string)$order->billing_address->address1;
                            $client["address_2"] = (string)$order->billing_address->address2;
                            $client["address_3"] = (string)$order->billing_address->address3;
                            $client["postcode"] = (string)$order->billing_address->zipcode;
                            $client["city"] = (string)$order->billing_address->city;
                            $country_id_3_digit = (string)$order->billing_address->country;
                            if($country_obj = $this->get_country_srv()->get_dao()->get(array("id_3_digit"=>$country_id_3_digit)))
                            {
                                $client["country_id"] = $bill_country_id = $country_obj->get_id();
                            }
                        }
                        if($order->shipping_address)
                        {
                            $client["del_name"] = implode(" ", array((string)$order->shipping_address->firstname, (string)$order->shipping_address->lastname));
                            $client["del_address_1"] = (string)$order->shipping_address->address1;
                            $client["del_address_2"] = (string)$order->shipping_address->address2;
                            $client["del_address_3"] = (string)$order->shipping_address->address3;
                            $client["del_postcode"] = (string)$order->shipping_address->zipcode;
                            $client["del_city"] = (string)$order->shipping_address->city;
                            $del_country_id_3_digit = (string)$order->shipping_address->country;
                            if($country_obj = $this->get_country_srv()->get_dao()->get(array("id_3_digit"=>$del_country_id_3_digit)))
                            {
                                $client["del_country_id"] = $del_country_id = $country_obj->get_id();
                            }
                        }
                        $ic_obj = $this->get_ic_dao()->get();
                        set_value($ic_obj, $client);
                        $ic_obj->set_batch_id($batch_id);
                        $ic_obj->set_batch_status('N');
                        $ic_ret = $this->get_ic_dao()->insert($ic_obj);
                        if($ic_ret === FALSE)
                        {
                            $batch_err_msg = "Error Order {$order->order_id} \nError Table: Interface_client\nError Msg: ".$this->get_ic_dao()->db->_error_message()."\nError SQL:".$this->get_ic_dao()->db->last_query()."\n";
                            $batch_err = 1;
                            $this->debug_msg .= "<hr></hr><pre>ERROR: $batch_err_msg</pre>";
                            $this->send_notification_email("BATCH_ERROR", $batch_err_msg);
                            continue;
                        }
                        $client_trans_id = $ic_ret->get_trans_id();
                        $this->debug_msg .= "<hr></hr><pre>DEBUG: interface_client completed. client_trans_id <$client_trans_id></pre>";

                        if($order->order_detail)
                        {
                            $so_trans_id = "";
                            $i = 0;

                            # loop through each item in order
                            foreach($order->order_detail as $order_detail)
                            {
                                $product_cost_dto = null;

                                if($pbv_obj = $this->get_pbv_srv()->get(array("selling_platform_id"=>$selling_platform_id)))
                                {
                                    $platform_currency_id = $pbv_obj->get_platform_currency_id();
                                    $lang_id = $pbv_obj->get_language_id();
                                    $courier = $pbv_obj->get_delivery_type();
                                    $vat_percent = $pbv_obj->get_vat_percent();
                                    $base_currency = $this->get_config()->value_of("func_curr_id");
                                    $rate_obj = $this->get_xrate_dao()->get(array("from_currency_id"=>$platform_currency_id, "to_currency_id"=>$base_currency));
                                    $rate = $rate_obj->get_rate();
                                    $ref_1_obj = $this->get_xrate_dao()->get(array("from_currency_id"=>$platform_currency_id, "to_currency_id"=>"EUR"));
                                    $ref_1 = $ref_1_obj->get_rate();
                                }
                                else
                                {
                                    var_dump($this->get_pbv_srv()->get_dao()->db->last_query());
                                    $batch_err_msg = "Error Table: Platform_biz_var\nError Msg: ".$this->get_pbv_srv()->get_dao()->db->_error_message()."\nError SQL:".$this->get_pbv_srv()->get_dao()->db->last_query()."\n";
                                    $batch_err = 1;
                                    $this->debug_msg .= "<pre>ERROR: $batch_err_msg</pre>";

                                    $this->send_notification_email("BATCH_ERROR", "Order {$order->order_id}\n".$batch_err_msg);
                                    break;
                                }

                                // contain all product information
                                $product_cost_dto = $this->get_price_srv()->get_dao()->get_price_cost_dto((string)$order_detail->offer_seller_id,$pbv_obj->get_selling_platform_id());
                                if(!$product_cost_dto)
                                {
                                    var_dump($this->get_price_srv()->get_dao()->db->last_query());
                                    $batch_err_msg = "Error: Missing Product_cost_dto\nError Msg: ".$this->get_price_srv()->get_dao()->db->_error_message()."\nError SQL:".$this->get_price_srv()->get_dao()->db->last_query()."\n";
                                    $batch_err = 1;
                                    $this->debug_msg .= "<pre>ERROR: $batch_err_msg</pre>";
                                    $this->send_notification_email("BATCH_ERROR", "Order {$order->order_id}\n".$batch_err_msg);
                                    break;
                                }
                                $product_cost_dto->set_price((float)$order_detail->price);
                                $this->get_price_srv()->set_dto($product_cost_dto);
                                $this->get_price_srv()->calc_logistic_cost($product_cost_dto);
                                $this->get_price_srv()->calculate_profit();

                                if((int)$order_detail->order_detail_id == 1)
                                {
                                    # first item in the order
                                    $so["platform_order_id"] = (string)$order->order_id;
                                    $so["txn_id"] = (string)$order->order_id;
                                    $so["platform_id"] = $pbv_obj->get_selling_platform_id();
                                    $so["biz_type"] = "FNAC";
                                    $so["amount"] = (float)$order_detail->price * (int)$order_detail->quantity;
                                    $so["cost"] = $product_cost_dto->get_cost();
                                    $so["vat_percent"] = (float)$vat_percent;
                                    $so["rate"] = $rate;
                                    $so["ref_1"] = $ref_1;
                                    $so["delivery_charge"] = (float)$order_detail->shipping_price;
                                    $so["delivery_type_id"] = 'STD';
                                    $so["weight"] = $product_cost_dto->get_prod_weight();
                                    $so["currency_id"] = $pbv_obj->get_platform_currency_id();
                                    $so["lang_id"] = $pbv_obj->get_language_id();
                                    $so["bill_name"] = implode(" ", array((string)$order->billing_address->firstname, (string)$order->billing_address->lastname));
                                    $so["bill_address"] = (string)$order->billing_address->address1 . "|" . (string)$order->billing_address->address2 . "|" . (string)$order->billing_address->address1 . "|";
                                    $so["bill_postcode"] = (string)$order->billing_address->zipcode;
                                    $so["bill_city"] = (string)$order->billing_address->city;
                                    $so["bill_country_id"] = $bill_country_id;
                                    $so["delivery_name"] = implode(" ", array((string)$order->shipping_address->firstname, (string)$order->shipping_address->lastname));
                                    $so["delivery_address"] = (string)$order->shipping_address->address1 . "|" . (string)$order->shipping_address->address2 . "|" . (string)$order->shipping_address->address1 . "|";
                                    $so["delivery_postcode"] = (string)$order->shipping_address->zipcode;
                                    $so["delivery_city"] = (string)$order->shipping_address->city;
                                    $so["delivery_country_id"] = $del_country_id;
                                    $so["status"] = 1;
                                    $timestamp = strtotime((string)$order->created_at);
                                    $order_create_date = date("Y-m-d", $timestamp);
                                    $so["order_create_date"] = $order_create_date;

                                    $iso_obj = $this->get_iso_dao()->get();
                                    set_value($iso_obj, $so);
                                    $iso_obj->set_batch_id($batch_id);
                                    $iso_obj->set_batch_status('N');
                                    $iso_obj->set_client_trans_id($client_trans_id);
                                    $iso_ret = $this->get_iso_dao()->insert($iso_obj);
                                    if($iso_ret === FALSE)
                                    {
                                        var_dump($this->get_iso_dao()->db->last_query());
                                        $batch_err_msg = "Error Table: Interface_so\nError Msg: ".$this->get_iso_dao()->db->_error_message()."\nError SQL:".$this->get_iso_dao()->db->last_query()."\n";
                                        $batch_err = 1;
                                        $this->debug_msg .= "<pre>ERROR: $batch_err_msg</pre>";
                                        $this->send_notification_email("BATCH_ERROR", "Order {$order->order_id}\n".$batch_err_msg);
                                        break;
                                    }
                                    $so_trans_id = $iso_ret->get_trans_id();
                                    $this->debug_msg .= "<pre>DEBUG: interface_so inserted. Order {$order->order_id}. so_trans_id <$so_trans_id></pre>";
                                }
                                else
                                {
                                    $so["amount"] += (float)$order_detail->price;
                                    $so["cost"] += $product_cost_dto->get_cost();
                                    $so["weight"] += $product_cost_dto->get_prod_weight();

                                    $iso_obj->set_amount($so["amount"]);
                                    $iso_obj->set_cost($so["cost"]);
                                    $iso_obj->set_weight($so["weight"]);
                                    $iso_ret = $this->get_iso_dao()->update($iso_obj);
                                    if($iso_ret === FALSE)
                                    {
                                        var_dump($this->get_iso_dao()->db->last_query());
                                        $batch_err_msg = "Error Table: Interface_so\nError Msg: ".$this->get_iso_dao()->db->_error_message()."\nError SQL:".$this->get_iso_dao()->db->last_query()."\n";
                                        $batch_err = 1;
                                        $this->debug_msg .= "<pre>ERROR: $batch_err_msg</pre>";
                                        $this->send_notification_email("BATCH_ERROR", "Order {$order->order_id}\n".$batch_err_msg);
                                        break;
                                    }
                                }


                                $soi["line_no"] = (string)$order_detail->order_detail_id;
                                $soi["prod_sku"] = (string)$order_detail->offer_seller_id;
                                $soi["prod_name"] = (string)$order_detail->product_name;
                                $soi["ext_item_cd"] = (string)$order_detail->product_fnac_id;
                                $soi["qty"] = (int)$order_detail->quantity;
                                $soi["unit_price"] = (float)$order_detail->price;
                                $soi["vat_total"] = $product_cost_dto->get_vat() * (int)$order_detail->quantity;
                                $soi["amount"] = (float)$order_detail->price * (int)$order_detail->quantity;
                                $soi["status"] = 0;

                                // store main item list to check for complementary accessory
                                $main_item_list[$i]["prod_sku"] = $soi["prod_sku"];
                                $main_item_list[$i]["qty"] = $soi["qty"];
                                $main_item_list[$i]["del_country_id"] = $del_country_id;

                                $isoi_obj = $this->get_isoi_dao()->get();
                                set_value($isoi_obj, $soi);
                                $isoi_obj->set_batch_id($batch_id);
                                $isoi_obj->set_batch_status('N');
                                $isoi_obj->set_so_trans_id($so_trans_id);
                                $isoi_ret = $this->get_isoi_dao()->insert($isoi_obj);
                                if($isoi_ret === FALSE)
                                {
                                    var_dump($this->get_isoi_dao()->db->last_query());
                                    $batch_err_msg = "Error Table: Interface_so_item\nError Msg: ".$this->get_isoi_dao()->db->_error_message()."\nError SQL:".$this->get_isoi_dao()->db->last_query()."\n";
                                    $batch_err = 1;
                                    $this->debug_msg .= "<pre>ERROR: $batch_err_msg</pre>";
                                    $this->send_notification_email("BATCH_ERROR", "Order {$order->order_id}\n".$batch_err_msg);
                                    break;
                                }
                                $this->debug_msg .= "<pre>DEBUG: interface_so_item completed. sku <{$order_detail->offer_seller_id}>, ext_item_cd <{$order_detail->product_fnac_id}></pre>";

                                // interface_so_item_detail
                                $bundle_cost = 0;
                                //$soi["prod_sku"] = "10408-AA-NA";
                                $bundle_list = $this->get_bundle_srv()->get_list(array("prod_sku"=>$soi["prod_sku"]), array("component_order"=>"ORDERBY component_order ASC", "array_list"=>1));
                                if($bundle_list)
                                {
                                    foreach($bundle_list AS $bundle_obj)
                                    {
                                        $prod_bundle_sku = $bundle_obj->get_component_sku();
                                        $bundle_cost_dto = $this->get_price_srv()->get_dao()->get_price_cost_dto($prod_bundle_sku,$pbv_obj->get_selling_platform_id());
                                        if(!$bundle_cost_dto)
                                        {
                                            var_dump($this->get_price_srv()->get_dao()->db->last_query());
                                            $batch_err_msg = "Error: Missing Bundle_cost_dto\nError Msg: ".$this->get_price_srv()->get_dao()->db->_error_message()."\nError SQL:".$this->get_price_srv()->get_dao()->db->last_query()."\n";
                                            $batch_err = 1;
                                            $debug_msg .= "<pre>ERROR: $batch_err_msg</pre>";
                                            $this->send_notification_email("BATCH_ERROR", "Order {$order->order_id}\n".$batch_err_msg);
                                            break;
                                            // error on bundle_cost_dto
                                        }
                                        $bundle_cost_dto->set_price((float)$order_detail->price);
                                        $this->get_price_srv()->set_dto($bundle_cost_dto);
                                        $this->get_price_srv()->calc_logistic_cost($bundle_cost_dto);
                                        $this->get_price_srv()->calculate_profit();

                                        $soid["line_no"] = (string)$order_detail->order_detail_id;
                                        $soid["item_sku"] = $prod_bundle_sku;
                                        $soid["qty"] = (int)$order_detail->quantity;
                                        $soid["outstanding_qty"] = (int)$order_detail->quantity;
                                        $soid["unit_price"] = (float)$order_detail->price;
                                        $soid["vat_total"] = $bundle_cost_dto->get_vat() * (int)$order_detail->quantity;
                                        $soid["amount"] = (float)$order_detail->price * (int)$order_detail->quantity;
                                        $soid["cost"] = $bundle_cost_dto->get_cost() * (int)$order_detail->quantity;
                                        $soid["profit"] = $bundle_cost_dto->get_profit() * (int)$order_detail->quantity;
                                        $soid["margin"] = $bundle_cost_dto->get_margin();
                                        $soid["status"] = 0;

                                        $isoid_obj = $this->get_isoid_dao()->get();
                                        set_value($isoid_obj, $soid);
                                        $isoid_obj->set_batch_id($batch_id);
                                        $isoid_obj->set_batch_status('N');
                                        $isoid_obj->set_so_trans_id($so_trans_id);
                                        $this->update_cost_profit($isoid_obj, $iso_obj);

                                        $isoid_ret = $this->get_isoid_dao()->insert($isoid_obj);
                                        if($isoid_ret === FALSE)
                                        {
                                            var_dump($this->get_isoid_dao()->db->last_query());
                                            $batch_err_msg = "Error Table: Interface_so_item\nError Msg: ".$this->get_isoid_dao()->db->_error_message()."\nError SQL:".$this->get_isoid_dao()->db->last_query()."\n";
                                            $batch_err = 1;
                                            $this->debug_msg .= "<pre>ERROR: $batch_err_msg</pre>";
                                            $this->send_notification_email("BATCH_ERROR", "Order {$order->order_id}\n".$batch_err_msg);
                                            break;
                                        }
                                    }
                                    $this->debug_msg .= "<pre>DEBUG: interface_so_item_detail (bundle) completed.</pre>";
                                }
                                else
                                {
                                    //interface_so_item_detail
                                    $soid["line_no"] = (string)$order_detail->order_detail_id;
                                    $soid["item_sku"] = (string)$order_detail->offer_seller_id;
                                    $soid["qty"] = (int)$order_detail->quantity;
                                    $soid["outstanding_qty"] = (int)$order_detail->quantity;
                                    $soid["unit_price"] = (float)$order_detail->price;
                                    $soid["vat_total"] = $product_cost_dto->get_vat() * (int)$order_detail->quantity;
                                    $soid["amount"] = (float)$order_detail->price * (int)$order_detail->quantity;
                                    $soid["cost"] = $product_cost_dto->get_cost() * (int)$order_detail->quantity;
                                    $soid["profit"] = $product_cost_dto->get_profit() * (int)$order_detail->quantity;
                                    $soid["margin"] = $product_cost_dto->get_margin();
                                    $soid["status"] = 0;

                                    $isoid_obj = $this->get_isoid_dao()->get();
                                    set_value($isoid_obj, $soid);
                                    $isoid_obj->set_batch_id($batch_id);
                                    $isoid_obj->set_batch_status('N');
                                    $isoid_obj->set_so_trans_id($so_trans_id);
                                    $this->update_cost_profit($isoid_obj, $iso_obj);

                                    $isoid_ret = $this->get_isoid_dao()->insert($isoid_obj);
                                    if($isoid_ret === FALSE)
                                    {
                                        var_dump($this->get_isoid_dao()->db->last_query());
                                        $batch_err_msg = "Error Table: Interface_so_item_detail\nError Msg: ".$this->get_isoid_dao()->db->_error_message()."\nError SQL:".$this->get_isoid_dao()->db->last_query()."\n";
                                        $batch_err = 1;
                                        $this->debug_msg .= "<pre>ERROR: $batch_err_msg";
                                        $this->send_notification_email("BATCH_ERROR", "Order {$order->order_id}\n".$batch_err_msg);
                                        break;
                                    }
                                    $this->debug_msg .= "<pre>DEBUG: interface_so_item_detail completed.</pre>";
                                }

                                $i++;
                            }

                            # add complementary accessory in this order
                            if($main_item_list)
                            {
                                $error_message = "";
                                if($old_isoi_obj = $this->get_isoi_dao()->get_list(array("so_trans_id"=>$so_trans_id), array("limit"=>1, "orderby"=>"line_no DESC")))
                                {
                                    $last_line_no = (int)$old_isoi_obj->get_line_no();
                                    foreach ($main_item_list as $k => $v)
                                    {
                                        $mainprod_sku = $v["prod_sku"];
                                        $mainqty = $v["qty"];
                                        $del_country_id = $v["del_country_id"];
                                        $result = $this->add_complementary_acc_isoi($batch_id, $so_trans_id, $last_line_no, $mainprod_sku, $mainqty, $del_country_id);
                                        if($result["status"] === FALSE)
                                        {
                                            $error_message .= __LINE__. "fnac_service adding Complementary Acc (interface_so_item) error msg: \n{$result["error_msg"]}\n";
                                            $this->debug_msg .= "<pre>ERROR: $error_message</pre>";
                                        }
                                        else
                                        {
                                            $this->debug_msg .= "<pre>Added CA {$result["ca_sku"]} x $mainqty -  dest country: $del_country_id - mainprod $mainprod_sku</pre>";

                                            $casoid_result = $this->add_complementary_acc_isoid($batch_id, $iso_obj, $so_trans_id, $last_line_no, $mainprod_sku, $mainqty, $del_country_id);
                                            if($casoid_result["status"] === FALSE)
                                            {
                                                $error_message .= __LINE__. "fnac_service adding Complementary Acc (interface_so_item_detail) error msg: \n{$casoid_result["error_msg"]}\n";
                                                $this->debug_msg .= "<pre>ERROR: $error_message</pre>";
                                            }
                                            $last_line_no = $result["updated_line_no"];
                                        }
                                    }

                                    if($error_message)
                                        $this->send_notification_email("BATCH_ERROR", $error_message);
                                    $this->debug_msg .= "<pre>DEBUG: Add CA ended for this order</pre>";
                                }
                                else
                                {
                                    $error_message = __LINE__. "fnac_service Error retrieving interface_so_item - Complementary Acc not added\n DB query: ".$this->get_isoi_dao()->db->last_query(). "\n DB error: " . $this->get_isoi_dao()->db->_error_message();
                                    $this->debug_msg .= "<pre>ERROR: $error_message</pre>";
                                    $this->send_notification_email("BATCH_ERROR", $error_message);
                                }
                            }
                        }

                        //interface_so_payment_status
                        $sops["payment_status"] = "N";
                        $sops["payment_gateway_id"] = "fnac_".strtolower($country_id);

                        $isops_obj = $this->get_isops_dao()->get();
                        set_value($isops_obj, $sops);
                        $isops_obj->set_batch_id($batch_id);
                        $isops_obj->set_batch_status('N');
                        $isops_obj->set_so_trans_id($so_trans_id);

                        $isops_ret = $this->get_isops_dao()->insert($isops_obj);
                        if($isops_ret === FALSE)
                        {
                            var_dump($this->get_isops_dao()->db->last_query());
                            $batch_err_msg = "Error Table: Interface_so_payment_status\nError Msg: ".$this->get_isops_dao()->db->_error_message()."\nError Query:".$this->get_isops_dao()->db->last_query()."\nError Error:".$this->get_isops_dao()->db->_error_message()."\n";
                            $batch_err = 1;
                            $this->debug_msg .= "<pre>ERROR: $error_message</pre>";
                            $this->send_notification_email("BATCH_ERROR", "Order {$order->order_id}\n".$batch_err_msg);
                            break;
                        }

                        $this->totalisocount++;
                        $debug_msg .= "<pre>DEBUG: interface_so_payment_status completed.</pre>";
                    }

                    if($batch_err)
                    {
                        $batch_obj->set_status("BE");
                        $this->get_batch_srv()->update($batch_obj);
                        $this->send_notification_email("BATCH_ERROR", $batch_err_msg);

                        if($_GET["continue"])
                        {
                            // in some cases we have small problem, and want to continue on error
                            $this->proceed_order_batch($batch_id);
                            $this->get_sj_srv()->update_last_process_time("FNAC{$country_id}_VALUEBASKET", date("Y-m-d H:i:s"));
                        }
                    }
                    else
                    {
                        $this->proceed_order_batch($batch_id);
                        $this->get_sj_srv()->update_last_process_time("FNAC{$country_id}_VALUEBASKET", date("Y-m-d H:i:s"));
                    }
                }
                else
                {
                    $this->debug_msg .= "<pre>NO ORDERS FROM XML</pre>";
                }
            }
        }
        echo $this->debug_msg . "<pre>Total Fnac {$this->totalfnaccount}, Total interface_so {$this->totalisocount}, Total so {$this->totalsocount}</pre><pre>Script END</pre>";
    }

    public function proceed_order_batch($batch_id)
    {
        $so_dao = $this->get_so_dao();
        $c_dao = $this->get_client_dao();
        $soi_dao = $this->get_soi_dao();
        $soid_dao = $this->get_soid_dao();
        $sops_dao = $this->get_sops_dao();
        $so_priority_score_dao = $this->get_priority_score_dao();
        $iso_dao =$this->get_iso_dao();
        $ic_dao = $this->get_ic_dao();
        $isoi_dao = $this->get_isoi_dao();
        $isoid_dao = $this->get_isoid_dao();
        $isops_dao = $this->get_isops_dao();

        $iso_list = $iso_dao->get_list(array("batch_id"=>$batch_id), array("limit"=>-1));

        $this->debug_msg .= "<pre></pre><hr></hr><hr></hr>PROCEED ORDER BATCH";
        if($iso_list)
        {
            $batch_status = TRUE;

            $c_vo = $c_dao->get();
            $so_vo = $so_dao->get();
            $soi_vo = $soi_dao->get();
            $soid_vo = $soid_dao->get();
            $sops_vo = $sops_dao->get();
            $so_priority_score_vo = $so_priority_score_dao->get();

            foreach($iso_list AS $iso_obj)
            {
                $so_trans_id = $iso_obj->get_trans_id();
                $isoi_list = $isoi_dao->get_list(array("so_trans_id"=>$so_trans_id), array("limit"=>-1));

                $iso_batch_status = TRUE;
                $failed_reason = "";
                foreach($isoi_list AS $isoi_obj)
                {
                    $sku = $isoi_obj->get_prod_sku();
                    if(!$this->get_product_srv()->get(array("sku"=>$sku)))
                    {
                        $iso_batch_status = FALSE;
                        $failed_reason = __LINE__. "Invalid SKU - ".$sku;

                        $this->send_notification_email("BATCH_ERROR", "Order {$iso_obj->get_platform_order_id()}\n".$failed_reason);
                        break;
                    }
                }

                //check if so record existed already
                $so_num = $this->get_so_dao()->get_num_rows(array("platform_order_id"=>$iso_obj->get_platform_order_id(), "platform_id"=>$iso_obj->get_platform_id()));
                if($so_num != 0)
                {
                    /*
                        We go back 14 days in retrieving new orders and may run into duplicated orders, hence, we don't count this as batch error .
                        $iso_batch_status = false will not run acknowledge_new_order() --> is a problem because orders not accepted on FNAC and customers won't pay.
                        In this case, we just update failed reason into interface_so level.
                    */

                    // $iso_batch_status = FALSE;
                    $failed_reason = __LINE__. "Order already Existed.";
                    $this->debug_msg .= "<pre>BATCH ERROR: so_trans_id $so_trans_id - $failed_reason</pre>";
                    $iso_obj->set_batch_status('F');
                    $iso_obj->set_failed_reason($failed_reason);
                    $iso_obj->set_so_no(NULL);
                    $iso_obj->set_client_id(NULL);
                    $iso_dao->update($iso_obj);
                }
                else
                {
                    $ic_obj = $ic_dao->get(array("batch_id"=>$batch_id, "trans_id"=>$iso_obj->get_client_trans_id()));
                    if(!$this->get_valid()->valid_email($ic_obj->get_email()))
                    {
                        $iso_batch_status = FALSE;
                        $failed_reason .= __LINE__. "Invalid Email\n";
                    }
                    else
                    {
                        //start_transaction
                        $c_dao->trans_start();

                        //client
                        $c_obj = $c_dao->get(array("email"=>$ic_obj->get_email()));
                        if($c_obj)
                        {
                            set_value($c_obj, $ic_obj);
                            $c_ret = $c_dao->update($c_obj);
                        }
                        else
                        {
                            $c_obj = clone $c_vo;
                            set_value($c_obj, $ic_obj);
                            $c_ret = $c_dao->insert($c_obj);
                        }
                        if($c_ret !== FALSE)
                        {
                            $client_id = $c_obj->get_id();

                            //update interface_client
                            $ic_obj->set_id($client_id);
                            if($ic_dao->update($ic_obj))
                            {
                                //so
                                $so_obj = clone $so_vo;
                                $seq = $so_dao->seq_next_val();
                                $so_no = $seq;
                                $so_dao->update_seq($seq);
                                $iso_obj->set_so_no($so_no);
                                $iso_obj->set_client_id($client_id);
                                set_value($so_obj, $iso_obj);
                                // $so_obj->set_sub_so_no(0);


                                if($so_dao->insert($so_obj))
                                {
                                    $this->debug_msg .= "<hr></hr><pre>DEBUG: Inserted so. so_no {$so_obj->get_so_no()} || platform_order_id {$so_obj->get_platform_order_id()}</pre>";

                                    //so_payment_status
                                    $isops_obj = $isops_dao->get(array("batch_id"=>$batch_id, "so_trans_id"=>$so_trans_id));
                                    $isops_obj->set_so_no($so_no);
                                    $sops_obj = clone $sops_vo;
                                    set_value($sops_obj, $isops_obj);
                                    if($sops_dao->insert($sops_obj))
                                    {
                                        $isops_obj->set_batch_status('S');
                                        if($isops_dao->update($isops_obj))
                                        {
                                            //so_item
                                            if ($isoi_list = $isoi_dao->get_list(array("batch_id"=>$batch_id, "so_trans_id"=>$so_trans_id)))
                                            {
                                                foreach($isoi_list AS $isoi_obj)
                                                {
                                                    $isoi_obj->set_so_no($so_no);
                                                    $soi_obj = clone $soi_vo;
                                                    set_value($soi_obj, $isoi_obj);
                                                    // $soi_obj->set_sub_so_no(0);
//set warranty and website status
                                                    $prod_obj = $this->get_so_srv()->get_prod_srv()->get(array("sku" => $soi_obj->get_prod_sku()));
                                                    $soi_obj->set_warranty_in_month($prod_obj->get_warranty_in_month());
                                                    $soi_obj->set_website_status($prod_obj->get_website_status());

                                                    if($soi_dao->insert($soi_obj))
                                                    {
                                                        $this->debug_msg .= "<pre>DEBUG: Inserted so_item. sku {$soi_obj->get_prod_sku()} x {$soi_obj->get_qty()}</pre>";
                                                        $isoi_obj->set_batch_status('S');
                                                        if (!$isoi_dao->update($isoi_obj))
                                                        {
                                                            $iso_batch_status = FALSE;
                                                            $failed_reason .= __LINE__. "Interface_so_item: ".$isoi_dao->db->_error_message();
                                                            $this->send_notification_email("BATCH_ERROR", "Order {$iso_obj->get_platform_order_id()}\n".$failed_reason);
                                                            break;
                                                        }
                                                    }
                                                    else
                                                    {
                                                        $iso_batch_status = FALSE;
                                                        $failed_reason .= __LINE__. "so_item: ".$soi_dao->db->_error_message();
                                                        $this->send_notification_email("BATCH_ERROR", "Order {$iso_obj->get_platform_order_id()}\n".$failed_reason);
                                                        break;
                                                    }
                                                }
                                            }
                                            if($iso_batch_status)
                                            {
                                                //so_item_detail
                                                if ($isoid_list = $isoid_dao->get_list(array("batch_id"=>$batch_id, "so_trans_id"=>$so_trans_id)))
                                                {
                                                    foreach($isoid_list AS $isoid_obj)
                                                    {
                                                        $isoid_obj->set_so_no($so_no);
                                                        $soid_obj = clone $soid_vo;
                                                        set_value($soid_obj, $isoid_obj);
                                                        // $soid_obj->set_sub_so_no(0);
                                                        $soid_obj->set_item_unit_cost($this->get_sup_srv()->get_item_cost_in_hkd($isoid_obj->get_item_sku()));
                                                        if($soid_dao->insert($soid_obj))
                                                        {
                                                            $this->debug_msg .= "<pre>DEBUG: Inserted so_item_detail.</pre>";
                                                            $isoid_obj->set_batch_status('S');
                                                            if ($isoid_dao->update($isoid_obj))
                                                            {
                                                                //update website quantity
                                                                $this->get_so_srv()->update_website_display_qty($so_obj);

                                                                //stock alert feed for platform specific quantity
                                                                $prext_dao = $this->get_price_srv()->get_price_ext_dao();
                                                                $prext_obj = $prext_dao->get(array("sku"=>$soid_obj->get_item_sku(),"platform_id"=>"FNACFR"));
                                                                if($prext_obj)
                                                                {
                                                                    $opq = $prext_obj->get_ext_qty();
                                                                    $npq = $opq - $soid_obj->get_qty();

                                                                    if($npq == 5)
                                                                    {
                                                                        $stock_warn[$so_obj->get_platform_order_id()][] = array("sku"=>$soid_obj->get_item_sku(),"name"=>$soi_obj->get_prod_name());
                                                                    }

                                                                    if($npq <= 0)
                                                                    {
                                                                        $npq = 0;
                                                                        $stock_alert[$so_obj->get_platform_order_id()][] = array("sku"=>$soid_obj->get_item_sku(),"name"=>$soi_obj->get_prod_name());
                                                                    }

                                                                    $prext_obj->set_ext_qty($npq);
                                                                    $tmp = $prext_dao->update($prext_obj);
                                                                    if($tmp == FALSE)
                                                                    {
                                                                        $iso_batch_status = FALSE;
                                                                        $failed_reason .= __LINE__. "Price_extend: ".$prext_dao->db->_error_message();
                                                                        $this->send_notification_email("BATCH_ERROR", "Order {$iso_obj->get_platform_order_id()}\n".$failed_reason);
                                                                        break;
                                                                    }
                                                                }
                                                            }
                                                            else
                                                            {
                                                                $iso_batch_status = FALSE;
                                                                $failed_reason .= __LINE__. "Interface_so_item_detail: ".$isoid_dao->db->_error_message();
                                                                $this->send_notification_email("BATCH_ERROR", "Order {$iso_obj->get_platform_order_id()}\n".$failed_reason);
                                                                break;
                                                            }
                                                        }
                                                        else
                                                        {
                                                            $iso_batch_status = FALSE;
                                                            $failed_reason .= __LINE__. "so_item_detail: ".$soid_dao->db->_error_message();
                                                            $this->send_notification_email("BATCH_ERROR", "Order {$iso_obj->get_platform_order_id()}\n".$failed_reason);
                                                            break;
                                                        }
                                                    }
                                                }
                                            }
                                            if($iso_batch_status)
                                            {
                                                $so_priority_score_obj = clone $so_priority_score_vo;
                                                $so_priority_score_obj->set_so_no($so_no);
                                                $so_priority_score_obj->set_score(1110);
                                                $res = $so_priority_score_dao->insert($so_priority_score_obj);
                                                if($res === FALSE)
                                                {
                                                    $iso_batch_status = FALSE;
                                                    $failed_reason .= __LINE__. "So_priority_score: ".$so_priority_score_dao->db->_error_message();
                                                    $this->send_notification_email("BATCH_ERROR", "Order {$iso_obj->get_platform_order_id()}\n".$failed_reason);
                                                    break;
                                                }
                                                else
                                                {
                                                    // ultimate success point, so construct array of orders to accept on FNAC
                                                    $so_obj_arr[] = $so_obj;
                                                }
                                            }
                                        }
                                        else
                                        {
                                            $iso_batch_status = FALSE;
                                            $failed_reason .= __LINE__. "Interface_so_payment_status: ".$isops_dao->db->_error_message();
                                        }
                                    }
                                    else
                                    {
                                        $iso_batch_status = FALSE;
                                        $failed_reason .= __LINE__. "so_payment_status: ".$isops_dao->db->_error_message();
                                    }
                                }
                                else
                                {
                                    $iso_batch_status = FALSE;
                                    $failed_reason .= __LINE__. "so: ".$so_dao->db->_error_message();
                                }
                            }
                            else
                            {
                                $iso_batch_status = FALSE;
                                $failed_reason .= __LINE__. "Interface_client: ".$ic_dao->db->_error_message();
                            }
                        }
                        else
                        {
                            $iso_batch_status = FALSE;
                            $failed_reason .= __LINE__. "client: ".$c_dao->db->_error_message();
                        }

                        if($iso_batch_status == FALSE)
                        {
                            $c_dao->trans_rollback();
                        }
                        else
                        {
                            //update interface_so
                            $iso_obj->set_batch_status('S');
                            $iso_dao->update($iso_obj);
                        }
                        $c_dao->trans_complete();
                    }
                }

                if($iso_batch_status == FALSE)
                {
                    $this->debug_msg .= "<pre>BATCH ERROR: so_trans_id $so_trans_id - $failed_reason</pre>";
                    $iso_obj->set_batch_status('F');
                    $iso_obj->set_failed_reason($failed_reason);
                    $iso_obj->set_so_no(NULL);
                    $iso_obj->set_client_id(NULL);
                    $iso_dao->update($iso_obj);
                    $batch_status = FALSE;

                    $message = "\n\nNOTE: FNAC ORDERS MAY NOT BE ACCEPTED TILL ERROR FIXED. Batch.id $batch_id.";
                    $message .= "\nhttp://admincentre.valuebasket.com/cron/cron_fnac/cron_retrieve_new_order/ES";
                    $this->send_notification_email("BATCH_ERROR", "Order {$iso_obj->get_platform_order_id()}, so_trans_id $so_trans_id\n".$failed_reason.$message);
                }

                $this->debug_msg .= "<pre>DEBUG: end of this so loop</pre>";
                $this->totalsocount++;
            }//end foreach

            $batch_obj = $this->get_batch_srv()->get(array("id"=>$batch_id));

            if($so_obj_arr)
            {
                $this->acknowledge_new_order($so_obj_arr);
            }
            if($batch_status)
            {
                $batch_obj->set_status("C");

                // send stock alert message
                if(count($stock_alert))
                {
                    foreach($stock_alert as $key=>$value)
                    {
                        $message .= "Please be advised that Fnac{$this->country_id} order number ".$key." has triggered the following product(s) to possibly be out of stock:<br><br>";
                        foreach($value as $k=>$c)
                        {
                            $message .= $c["sku"]." - ".$c["name"]."<br>";
                        }
                        $message .="<br><br>";
                    }
                    $message = ereg_replace("<br><br>$","",$message);
                    $this->send_notification_email("STOCK_ALERT", $message);
                }
                if(count($stock_warn))
                {
                    $message = "";
                    foreach($stock_warn as $key=>$value)
                    {
                        $message .= "Please be advised that Fnac{$this->country_id} order number ".$key." has triggered the following product to control quantity 5:<br><br>";
                        foreach($value as $k=>$c)
                        {
                            $message .= $c["sku"]." - ".$c["name"]."<br>";
                        }
                        $message .="<br><br>";
                    }
                    $message = ereg_replace("<br><br>$","",$message);
                    $this->send_notification_email("STOCK_WARN", $message);
                }

            }
            else
            {
                $batch_obj->set_status("CE");
            }
            $batch_obj->set_end_time(date("Y-m-d H:i:s"));

            if (!$this->get_batch_srv()->update($batch_obj))
            {
                $content = "Batch update error\nBatch_id:".$batch_id."\nError Message:".$this->get_batch_srv()->get_dao()->db->_error_message();
                $this->send_notification_email("BATCH_UPDATE_ERROR", $content);
            }
        }
    }
    public function acknowledge_order($country_id="ES", $fnac_order_id_list="")
    {
        //here
        $this->country_id = $country_id;
        $selling_platform_id = "FNAC".strtoupper($country_id);

        $this->debug_msg = "START SCRIPT";

        $so_dao = $this->get_so_dao();

        if($fnac_order_id_list)
        {
            $fnac_order_arr = explode(',', $fnac_order_id_list);
            foreach ($fnac_order_arr as $key => $value)
            {
                if($so_obj = $so_dao->get(array("platform_order_id"=>$value, "platform_id"=>$selling_platform_id)))
                {
                    $so_obj_arr[] = $so_obj;
                    $this->debug_msg .= "<pre>Sending acknowledgement order id $value </pre>";
                }
                else
                {
                    $this->debug_msg .= "<pre>Cannot find so for order id $value </pre>";
                }
            }

            if($so_obj_arr)
            {
                $response = $this->acknowledge_new_order($so_obj_arr);

                if($response)
                    $this->debug_msg .= "<pre>acknowledgement sent.</pre>";
                else
                    $this->debug_msg .= "<pre>acknowledgement failed.</pre>";
            }
        }
        $this->debug_msg.= "Script end.";
        echo $this->debug_msg;

    }

    public function acknowledge_new_order($so_list)
    {
        //$tpl_path = $this->get_config()->value_of("fnac_tpl_path");
        //$xmlResponse = simplexml_load_file($tpl_path . 'xml_files/sample_accept_order_success.xml',"SimpleXMLElement",LIBXML_NOCDATA);
        //$xmlResponse = simplexml_load_file($tpl_path . 'xml_files/orders-update-response-sample.xml',"SimpleXMLElement",LIBXML_NOCDATA);
        if($xmlResponse = $this->send_orders_ack_request($so_list))
        {
            $this->debug_msg .= "<hr></hr><pre>FNAC ACKNOWLEDGE ORDER COMPLETE.</pre>";

            if($xmlResponse->order)
            {
                foreach($xmlResponse->order as $order)
                {
                    $result[(string)$order->order_id] = (string)$order->status;
                }

                $fail_list = array();

                foreach($so_list as $so_obj)
                {
                    //process record change for orders
                    $fnac_order_id = $so_obj->get_platform_order_id();
                    if($result[$fnac_order_id] == "OK")
                    {
                        $so_ext_obj = $this->get_so_srv()->get_soext_dao()->get();
                        $so_ext_obj->set_so_no($so_obj->get_so_no());
                        $so_ext_obj->set_acked('Y');
                        $so_ext_obj->set_fulfilled('N');

                        $so_obj->set_status(1);

                        if ($sops_obj = $this->get_sops_dao()->get(array("so_no"=>$so_obj->get_so_no())))
                        {
                            $sops_obj->set_payment_status('P');
                            $this->get_sops_dao()->update($sops_obj);
                        }

                        $ret = $this->get_so_srv()->update($so_obj);
                        $entity_id = $this->get_so_srv()->get_entity_srv()->get_entity_id($so_obj->get_amount(), $so_obj->get_currency_id());
                        $so_ext_obj->set_entity_id($entity_id);
                        $ret2 = $this->get_so_srv()->get_soext_dao()->insert($so_ext_obj);
                        if(!$ret || !$ret2)
                        {
                            $fail_list[] = $so_obj->get_so_no();
                        }
                    }
                }

                if(count($fail_list))
                {
                    $this->send_notification_email("ACK_REQUEST_ERROR", implode(",",$fail_list) . "\n" . $this->get_so_srv()->get_dao()->db->last_query() . "\n" . $this->get_so_srv()->get_soext_dao()->db->last_query());
                }
                else
                {
                    $this->send_notification_email("ACK_REQUEST_SUCCESS");
                }

                return $xmlResponse;
            }
        }
    }

    private function add_complementary_acc_isoi($batch_id, $so_trans_id, $line_no, $prod_sku, $qty, $country_id)
    {
        #SBF #4324 - include mapped complementary accessories
        $last_line_no = $line_no + 1;
        $result = array();
        $result["updated_line_no"] = "";
        $result["status"] = TRUE;
        $result["error_msg"] = "fnac_service; so_trans_id <$so_trans_id>. Cannot add CA in interface_so_item for main SKU <$prod_sku> qty<$qty>.\n";

        $where["dest_country_id"] = $country_id;
        $where["mainprod_sku"] = $prod_sku;

        if( ($mapped_ca_list = $this->get_ca_service()->get_mapped_acc_list_w_name($where, $option, true)) === FALSE)
        {
            $result["error_msg"] .= "Line ".__LINE__."Error Table: product_complementary_acc\nError Msg: ".$this->get_ca_service()->get_complementary_acc_dao()->db->_error_message()."\nError SQL:".$this->get_ca_service()->get_complementary_acc_dao()->db->last_query()."\n";
            $result["status"] = FALSE;
        }
        else
        {
            if($mapped_ca_list !== NULL)
            {
                foreach ($mapped_ca_list as $ca_obj)
                {
                    //interface_so_item
                    $result["updated_line_no"] = $last_line_no;     # return last_line_no in case there are more main items in the order
                    $result["ca_sku"] .= $ca_obj->get_accessory_sku().", ";

                    $isoi_obj = $this->get_isoi_dao()->get();
                    $isoi_obj->set_batch_id($batch_id);
                    $isoi_obj->set_so_trans_id($so_trans_id);
                    $isoi_obj->set_line_no($last_line_no);
                    $isoi_obj->set_prod_sku($ca_obj->get_accessory_sku());
                    $isoi_obj->set_prod_name($ca_obj->get_name());
                    $isoi_obj->set_ext_item_cd("");
                    $isoi_obj->set_qty($qty);
                    $isoi_obj->set_unit_price(0);
                    $isoi_obj->set_vat_total(0);
                    $isoi_obj->set_amount(0);
                    $isoi_obj->set_status('0');
                    $isoi_obj->set_batch_status('N');
                    $isoi_ret = $this->get_isoi_dao()->insert($isoi_obj);
                    if($isoi_ret === FALSE)
                    {
                        $batch_err_msg .= "LINE ".__LINE__." CA_sku<{$ca_obj->get_accessory_sku()}>\nError Table: Interface_so_item\nError Msg: ".$this->get_isoi_dao()->db->_error_message()."\nError SQL:".$this->get_isoi_dao()->db->last_query()."\n";
                        $result["status"] = FALSE;
                    }

                    $last_line_no++;
                }
            }
        }

        return $result;

    }

    private function add_complementary_acc_isoid($batch_id, $iso_obj, $so_trans_id, $line_no, $prod_sku, $qty, $country_id)
    {
        #SBF #4324 - include mapped complementary accessories
        $last_line_no = $line_no + 1;
        $result = array();
        $result["updated_isoid_line_no"] = "";
        $result["status"] = TRUE;
        $result["error_msg"] = "fnac_service; so_trans_id <$so_trans_id>. Cannot add CA in interface_so_item_detail for main SKU <$prod_sku> qty<$qty>.\n";

        $where["dest_country_id"] = $country_id;
        $where["mainprod_sku"] = $prod_sku;

        if( ($mapped_ca_list = $this->get_ca_service()->get_mapped_acc_list_w_name($where, $option, true)) === FALSE)
        {
            $result["error_msg"] .= "Line ".__LINE__."Error Table: product_complementary_acc\nError Msg: ".$this->get_ca_service()->get_complementary_acc_dao()->db->_error_message()."\nError SQL:".$this->get_ca_service()->get_complementary_acc_dao()->db->last_query()."\n";
            $result["status"] = FALSE;
        }
        else
        {
            if($mapped_ca_list !== NULL)
            {
                foreach ($mapped_ca_list as $ca_obj)
                {
                    $isoid_obj = $this->get_isoid_dao()->get();
                    $isoid_obj->set_batch_id($batch_id);
                    $isoid_obj->set_so_trans_id($so_trans_id);
                    $isoid_obj->set_line_no($last_line_no);
                    $isoid_obj->set_item_sku($ca_obj->get_accessory_sku());
                    $isoid_obj->set_qty($qty);
                    $isoid_obj->set_outstanding_qty($qty);
                    $isoid_obj->set_unit_price(0);
                    $isoid_obj->set_vat_total(0);
                    $isoid_obj->set_discount('0');
                    $isoid_obj->set_amount('0');
                    $isoid_obj->set_cost('0');
                    $isoid_obj->set_profit('0');
                    $isoid_obj->set_margin('0');
                    $isoid_obj->set_status('0');
                    $isoid_obj->set_batch_status('N');

                    # update cost as supplier cost
                    $this->update_cost_profit($isoid_obj, $iso_obj, true);

                    $isoid_ret = $this->get_isoid_dao()->insert($isoid_obj);
                    if($isoid_ret === FALSE)
                    {
                        $result["error_msg"] .= "Line ".__LINE__." CA_sku<{$ca_obj->get_accessory_sku()}>\nError Table: Interface_so_item_detail\nError Msg: ".$this->get_isoid_dao()->db->_error_message()."\nError SQL:".$this->get_isoid_dao()->db->_error_message()."\n";
                    }

                    $last_line_no++;
                }

                $result["updated_isoid_line_no"] = $last_line_no;
            }
        }

        return $result;

    }

    public function cron_update_payment_status($country_id = "ES")
    {
        /*
            * FNAC "Accepted" status refers to seller accepted and pending payment
            * If FNAC changed to "ToShip", it means billed - we set so.status to 3 (credit checked)
        */
        $this->country_id = $country_id;
        $this->debug_msg = "START SCRIPT";
        if($xmlResponse = $this->send_check_pending_payment_request($country_id))
        {
            if($xmlResponse->order)
            {
                foreach($xmlResponse->order as $order)
                {
                    $order_status = (string)$order->state;
                    $fnac_order_id = (string)$order->order_id;
                    switch($order_status)
                    {
                        case "Accepted":
                            break;
                        case "ToShip":
                        {
                            if($so_obj = $this->get_so_srv()->get(array("platform_order_id"=>$fnac_order_id)))
                            {
                                $this->debug_msg .= "<pre>so_no <{$so_obj->get_so_no()}>, fnac order_id <$fnac_order_id>: $order_status</pre>";
                                $so_obj->set_status(3);
                                $so_obj->set_expect_delivery_date($this->get_del_srv()->get_edd($so_obj->get_delivery_type_id(), $so_obj->get_delivery_country_id()));

                                if ($sops_obj = $this->get_sops_dao()->get(array("so_no"=>$so_obj->get_so_no())))
                                {
                                    $curr_date = date("Y-m-d 00:00:00");
                                    $sops_obj->set_pay_date($curr_date);
                                    $sops_obj->set_payment_status("S");
                                    $ret2 = $this->get_sops_dao()->update($sops_obj);
                                }

                                $ret = $this->get_so_srv()->update($so_obj);
                                if(!$ret || !$ret2)
                                {
                                    $fail_list[] = $so_obj->get_so_no();
                                    $this->debug_msg .= "<pre>ERROR: so_no <{$so_obj->get_so_no()}>, fnac order_id <$fnac_order_id>: Unable to update so / payment_status</pre>";
                                }

                                if(count($fail_list))
                                {
                                    $this->send_notification_email("UPDATE_PAY_STATUS_ERROR", implode(",",$fail_list) . "\n" . $this->get_sops_dao()->db->last_query() . "\n" . $this->get_so_srv()->get_dao()->db->last_query());
                                }
                                else
                                {
                                    $this->send_notification_email("UPDATE_PAY_STATUS_SUCCESS");
                                }
                            }
                            else
                            {
                                $this->debug_msg .= "<pre>ERROR: fnac order_id <$fnac_order_id> doesn't exist in VB.</pre>";
                                $this->send_notification_email("UPDATE_PAY_STATUS_ERROR", "Fnac Order ID: " . $fnac_order_id .  "  does not exist in our system.");
                            }
                            break;
                        }
                        case "Cancelled":
                            if($so_obj = $this->get_so_srv()->get(array("platform_order_id"=>$fnac_order_id)))
                            {
                                $this->debug_msg .= "<pre>so_no <{$so_obj->get_so_no()}>, fnac order_id <$fnac_order_id>: $order_status</pre>";
                                $so_obj->set_status(0);
                                $ret = $this->get_so_srv()->update($so_obj);
                                if($ret)
                                {
                                    $this->send_notification_email("ORDER_CANCELLED", "so_no {$so_obj->get_so_no()} - FNAC order id {$fnac_order_id}");
                                }
                            }
                            break;
                        default:
                    }
                }
                var_dump($this->debug_msg. "END SCRIPT");
                return true;
            }
        }

        var_dump("No update list");
        return false;
    }

    public function cron_update_shipment_status($country_id = "ES")
    {
        $this->country_id = $country_id;
        $platform_id = "FNAC".$country_id;

        # so.status = shipped and not refund, so_extend.fulfilled = "N", ship status = Shipped, so_allocate status = Shipped
        $fulfill_list = $this->get_so_srv()->get_dao()->get_fnac_fulfillment_list($platform_id);

        $this->debug_msg = "";

        if($xmlResponse = $this->send_orders_fulfilled_request($fulfill_list))
        {
            if($xmlResponse->order)
            {
                foreach($xmlResponse->order as $order)
                {
                    $order_status = (string)$order->status;
                    if($order_status == 'FATAL')
                    {
                        // If the status returns 'FATAL', check the state and see if it's already 'Received'.
                        // Sometimes order's state changed to 'Received' already before we update it to 'Shipped', hence the 'FATAL' error.
                        $order_state = (string)$order->state;
                        if($order_state == 'Received')
                        {
                            $result[(string)$order->order_id] = "OK";
                        }
                    }
                    else
                    {
                        $result[(string)$order->order_id] = $order_status;
                    }
                }

                $fail_list = array();
            }

            $wrong_fnac_status_message = "";
            foreach($fulfill_list as $fulfill_dto)
            {
                $fnac_order_id = $fulfill_dto->get_platform_order_id();
                if($result[$fnac_order_id] == "OK")
                {
                    $this->debug_msg .= "<pre>Updating so_extend. so_no {$fulfill_dto->get_so_no()} // FNAC order_id $fnac_order_id</pre>";
                    $so_ext_obj = $this->get_so_srv()->get_soext_dao()->get(array("so_no"=>$fulfill_dto->get_so_no()));
                    $so_ext_obj->set_fulfilled('Y');

                    $ret = $this->get_so_srv()->get_soext_dao()->update($so_ext_obj);
                    if(!$ret)
                    {
                        $fail_list[] = $fnac_order_id . "\n" . $this->get_so_srv()->get_soext_dao()->db->last_query(). "\n";
                        $this->debug_msg .= "<pre>ERROR so_no {$fulfill_dto->get_so_no()} // FNAC order_id $fnac_order_id || db error: {$this->get_so_srv()->get_soext_dao()->db->last_query()}</pre>";
                    }
                }
                else
                {
                    $this->debug_msg .= "<pre>ERROR so_no {$fulfill_dto->get_so_no()} // FNAC order_id $fnac_order_id || FNAC status {$result[$fnac_order_id]}</pre>";
                    $wrong_fnac_status_message .= "so_no {$fulfill_dto->get_so_no()} // FNAC order_id $fnac_order_id || FNAC status {$result[$fnac_order_id]}\n";
                }
            }

            if(count($fail_list))
            {
                $this->send_notification_email("FULFILL_REQUEST_ERROR", implode(",",$fail_list) . "\n" . $this->get_so_srv()->get_dao()->db->last_query() . "\n" . $this->get_so_srv()->get_soext_dao()->db->last_query());
            }
            else
            {
                if($wrong_fnac_status)
                    $this->send_notification_email("FULFILL_REQUEST_WARNING", $wrong_fnac_status_message);
                else
                    $this->send_notification_email("FULFILL_REQUEST_SUCCESS");
            }
        }
        var_dump("DEBUG LOG: <br><br> {$this->debug_msg} <br><hr></hr>Script end.");
    }

    public function cron_check_offer_update_status($country_id="ES")
    {
        $this->country_id = $country_id;
        $platform_id = "FNAC".$country_id;
        $this->debug_msg = "";
        $this->process = "cron_check_offer_update_status";
        if($pending_list = $this->get_price_srv()->get_fnac_pending_update_offer_list($platform_id))
        {
            $result = array();
            foreach($pending_list as $obj)
            {
                if($xmlResponse = $this->send_batch_status_request($obj->get_remark(), $country_id))
                {
                    if($xmlResponse->attributes()->status)
                    {
                        if((string)$xmlResponse->attributes()->status == "OK")
                        {
                            $add_note = "";
                            $resp = (string)$xmlResponse->offer->attributes()->status;
                            switch($resp)
                            {
                                case "OK":
                                    $ext_item_id = (string)$xmlResponse->offer->product_fnac_id;
                                    $price_ext_obj = $this->get_price_srv()->get_price_ext_dao()->get(array("sku"=>$obj->get_sku(), "platform_id"=>$platform_id));
                                    $price_ext_obj->set_action(null);
                                    $price_ext_obj->set_remark(null);
                                    $price_ext_obj->set_ext_item_id($ext_item_id);
                                    $ret = $this->get_price_srv()->get_price_ext_dao()->update($price_ext_obj);
                                    $result["Update Success"][] = $obj->get_sku();
                                    break;
                                case "ERROR":
                                    $fnac_error_code = (string)$xmlResponse->offer->error->attributes()->code;
                                    switch ($fnac_error_code)
                                    {
                                        case 'ERR_026':
                                            $price_ext_obj = $this->get_price_srv()->get_price_ext_dao()->get(array("sku"=>$obj->get_sku(), "platform_id"=>$platform_id));
                                            $price_ext_obj->set_action(null);
                                            $price_ext_obj->set_remark(null);
                                            $ret = $this->get_price_srv()->get_price_ext_dao()->update($price_ext_obj);

                                            $price_obj = $this->get_price_srv()->get_dao()->get(array("sku"=>$obj->get_sku(), "platform_id"=>$platform_id));
                                            $price_obj->set_listing_status("N");
                                            $ret = $this->get_price_srv()->get_dao()->update($price_obj);

                                            $add_note = " Not found in Fnac catalogue; unlisted on pricing tool. Contact contact info.mp@fnac.com";
                                            break;

                                        case 'ERR_031':
                                            $price_ext_obj = $this->get_price_srv()->get_price_ext_dao()->get(array("sku"=>$obj->get_sku(), "platform_id"=>$platform_id));
                                            $price_ext_obj->set_action(null);
                                            $price_ext_obj->set_remark(null);
                                            $ret = $this->get_price_srv()->get_price_ext_dao()->update($price_ext_obj);

                                            $price_obj = $this->get_price_srv()->get_dao()->get(array("sku"=>$obj->get_sku(), "platform_id"=>$platform_id));
                                            $price_obj->set_listing_status("N");
                                            $ret = $this->get_price_srv()->get_dao()->update($price_obj);

                                            $add_note = " EAN structure isn't valid; unlisted on pricing tool.";
                                            break;

                                        case 'ERR_103':
                                            // $sku_list = array();
                                            // $price_ext_list = $this->get_price_srv()->get_price_ext_dao()->get_list(array("ext_ref_3"=>$obj->get_ext_ref_3(), "platform_id"=>$platform_id));
                                            // if($price_ext_list)
                                            // {
                                            //  $count = 0;
                                            //  foreach ($price_ext_list as $key => $obj)
                                            //  {
                                            //      $sku_list[] = $obj->get_sku();
                                            //      $count++;
                                            //  }
                                            // }
                                            // if($count > 1)
                                            {
                                                // Two SKUs using the same EAN is not allowed on FNAC.
                                                $sku_list_str = implode(", ", $sku_list);
                                                $add_note = " Check FNAC for another SKU using EAN [{$price_ext_obj->get_ext_ref_3()}]. {$sku} will be unlisted from pricing tool.";

                                                $price_ext_obj = $this->get_price_srv()->get_price_ext_dao()->get(array("sku"=>$obj->get_sku(), "platform_id"=>$platform_id));
                                                $price_ext_obj->set_action(null);
                                                $price_ext_obj->set_remark(null);
                                                $ret = $this->get_price_srv()->get_price_ext_dao()->update($price_ext_obj);

                                                $price_obj = $this->get_price_srv()->get_dao()->get(array("sku"=>$obj->get_sku(), "platform_id"=>$platform_id));
                                                $price_obj->set_listing_status("N");
                                                $ret = $this->get_price_srv()->get_dao()->update($price_obj);
                                            }
                                            // else
                                            // {
                                            //  // If EAN only found in one sku, means already successfully added on FNAC, so remove this API batch remark
                                            //  $price_ext_obj = $this->get_price_srv()->get_price_ext_dao()->get(array("sku"=>$obj->get_sku(), "platform_id"=>$platform_id));
                                            //  $price_ext_obj->set_action(null);
                                            //  $price_ext_obj->set_remark(null);
                                            //  $ret = $this->get_price_srv()->get_price_ext_dao()->update($price_ext_obj);
                                            // }
                                            break;

                                        default:
                                            $add_note = (string)$xmlResponse->offer->error;
                                            break;
                                    }

                                    if($add_note)
                                        $result["Error"][] = $obj->get_sku()." - $fnac_error_code - $add_note" ;

                                    break;

                                default:
                                    break;
                            }
                        }
                        elseif((string)$xmlResponse->attributes()->status == "RUNNING")
                        {
                            $result["Processing"][] = $obj->get_sku();
                        }
                        elseif((string)$xmlResponse->attributes()->status == "ACTIVE")
                        {
                            $result["Pending"][] = $obj->get_sku();
                        }
                        else
                        {
                            $result["Pending"][] = $obj->get_sku();
                        }
                    }
                    else
                    {
                        $result["Pending"][] = $obj->get_sku();
                    }
                }
                else
                {
                    $result["Pending"][] = $obj->get_sku();
                }
            }

            if($result)
            {
                foreach($result as $res=>$arr)
                {
                    $message .= "\n" . $res . ":\n";
                    if($res == "Error")
                    {
                        $message .= "\nTry updating on pricing tool again.\n";
                    }

                    foreach($arr as $sku)
                    {
                        $message .= $sku . "\r\n";
                    }

                    $this->debug_msg .= "<pre>$message</pre>";
                }

                $headers .= 'From: Admin <admin@valuebasket.net>' . "\r\n";
                // $headers .= 'Cc: steven@eservicesgroup.net' . "\r\n";
                mail("celine@eservicesgroup.net, romuald@eservicesgroup.net, ruby@eservicesgroup.net, ping@eservicesgroup.com", "Fnac Offer Update Result", $message, $headers);
            }
        }
        var_dump($this->debug_msg. "<hr></hr>END");
    }

    // called from pricing tool and pricing overview
    public function check_fnac_batch_offers_update_status($xmlResponse, $sku, $batch_id = "", $country_id = "ES")
    {
        $xmlString = $xmlResponse->asXML();

        set_time_limit(300);
        $platform_id = "FNAC".strtoupper($country_id);
        if($xmlResponse)
        {
            if((string)$xmlResponse->attributes()->status == "OK")
            {
                $resp = (string)$xmlResponse->offer->attributes()->status;
                switch($resp)
                {
                    case "OK":
                        $sku = (string)$xmlResponse->offer->offer_seller_id;
                        $ext_item_id = (string)$xmlResponse->offer->product_fnac_id;
                        $action = "update";

                        if (!($price_ext_obj = $this->get_price_srv()->get_price_ext_dao()->get(array("sku"=>$sku, "platform_id"=>$platform_id))))
                        {
                            $action = "insert";
                            if (!isset($price_ext_vo))
                            {
                                $price_ext_vo = $this->get_price_srv()->get_price_ext_dao()->get();
                            }
                            $price_ext_obj = clone $price_ext_vo;
                            $price_ext_obj->set_sku($sku);
                            $price_ext_obj->set_platform_id($platform_id);
                            $price_ext_obj->set_ext_qty(0);
                        }
                        $price_ext_obj->set_action(null);
                        $price_ext_obj->set_remark(null);
                        $price_ext_obj->set_ext_item_id($ext_item_id);
                        $ret = $this->get_price_srv()->get_price_ext_dao()->$action($price_ext_obj);

                        $notice = "Fnac Offer Update Success";
                        break;
                    case "ERROR":
                        $fnac_error_code = (string)$xmlResponse->offer->error->attributes()->code;
                        $price_ext_obj = $this->get_price_srv()->get_price_ext_dao()->get(array("sku"=>$sku, "platform_id"=>$platform_id));

                        if(!$price_ext_obj)
                        {
                            $price_ext_vo = $this->get_price_srv()->get_price_ext_dao()->get();
                            $price_ext_obj = clone $price_ext_vo;
                            $price_ext_obj->set_sku($sku);
                            $price_ext_obj->set_platform_id($platform_id);
                            $price_ext_obj->set_ext_qty(0);
                            $price_ext_obj->set_action(null);
                            $price_ext_obj->set_remark(null);
                            $price_ext_obj->set_ext_item_id($ext_item_id);
                            $ret = $this->get_price_srv()->get_price_ext_dao()->insert($price_ext_obj);

                            $price_ext_obj = $this->get_price_srv()->get_price_ext_dao()->get(array("sku"=>$sku, "platform_id"=>$platform_id));
                        }

                        if($price_ext_obj)
                        {
                            switch ($fnac_error_code)
                            {
                                case 'ERR_026':
                                    $price_ext_obj->set_action(null);
                                    $price_ext_obj->set_remark(null);
                                    $ret = $this->get_price_srv()->get_price_ext_dao()->update($price_ext_obj);

                                    $price_obj = $this->get_price_srv()->get_dao()->get(array("sku"=>$sku, "platform_id"=>$platform_id));
                                    $price_obj->set_listing_status("N");
                                    $ret = $this->get_price_srv()->get_dao()->update($price_obj);

                                    $add_note = " Not found in Fnac catalogue; unlisted on pricing tool. Contact contact info.mp@fnac.com";
                                    break;

                                case 'ERR_031':
                                    $price_ext_obj->set_action(null);
                                    $price_ext_obj->set_remark(null);
                                    $ret = $this->get_price_srv()->get_price_ext_dao()->update($price_ext_obj);

                                    $price_obj = $this->get_price_srv()->get_dao()->get(array("sku"=>$sku, "platform_id"=>$platform_id));
                                    $price_obj->set_listing_status("N");
                                    $ret = $this->get_price_srv()->get_dao()->update($price_obj);

                                    $add_note = " EAN structure isn't valid; unlisted on pricing tool.";
                                    break;

                                case 'ERR_103':
                                    $sku_list = array();
                                    // $price_ext_list = $this->get_price_srv()->get_price_ext_dao()->get_list(array("ext_ref_3"=>$price_ext_obj->get_ext_ref_3(), "platform_id"=>$platform_id));
                                    // if($price_ext_list)
                                    // {
                                    //  $count = 0;
                                    //  foreach ($price_ext_list as $key => $obj)
                                    //  {
                                    //      $sku_list[] = $obj->get_sku();
                                    //      $count++;
                                    //  }
                                    // }
                                    // if($count > 1)
                                    {
                                        // Two SKUs using the same EAN is not allowed on FNAC.
                                        $sku_list_str = implode(", ", $sku_list);
                                        $add_note = " Check FNAC for another SKU using EAN [{$price_ext_obj->get_ext_ref_3()}]. {$sku} will be unlisted from pricing tool.";

                                        $price_ext_obj->set_action(null);
                                        $price_ext_obj->set_remark(null);
                                        $ret = $this->get_price_srv()->get_price_ext_dao()->update($price_ext_obj);

                                        $price_obj = $this->get_price_srv()->get_dao()->get(array("sku"=>$sku, "platform_id"=>$platform_id));
                                        $price_obj->set_listing_status("N");
                                        $ret = $this->get_price_srv()->get_dao()->update($price_obj);
                                    }
                                    // else
                                    // {
                                    //  // If EAN only found in one sku, means already successfully added on FNAC, so remove this API batch remark
                                    //  $price_ext_obj = $this->get_price_srv()->get_price_ext_dao()->get(array("sku"=>$obj->get_sku(), "platform_id"=>$platform_id));
                                    //  $price_ext_obj->set_action(null);
                                    //  $price_ext_obj->set_remark(null);
                                    //  $ret = $this->get_price_srv()->get_price_ext_dao()->update($price_ext_obj);
                                    // }
                                    break;

                                default:
                                    $price_ext_obj->set_action(null);
                                    $price_ext_obj->set_remark(null);
                                    $ret = $this->get_price_srv()->get_price_ext_dao()->update($price_ext_obj);
                                    $add_note = "FNAC Offer Update Failed. $fnac_error_code - ".(string)$xmlResponse->offer->error;
                                    break;
                            }

                            $notice = $add_note;

                        }
                        else
                        {

                            $notice = "$sku - DBerror: ".$this->get_price_srv()->get_price_ext_dao()->db->_error_message()."\n$fnac_error_code - " . (string)$xmlResponse->offer->error;
                        }


                        break;
                    default:
                }

                return $notice;
            }
            elseif(((string)$xmlResponse->attributes()->status == "RUNNING" || (string)$xmlResponse->attributes()->status == "ACTIVE") && $this->connect_count < 3)
            {
                $batch_id = $xmlResponse->batch_id;
                sleep(2);
                $newResponse = $this->send_batch_status_request($batch_id, $country_id);
                $this->connect_count = $this->connect_count + 1;
                return $this->check_fnac_batch_offers_update_status($newResponse, $sku, $batch_id, $country_id);
            }
            else
            {
                if($this->connect_count >= 3)
                {
                    $action = "update";
                    if (!($price_ext_obj = $this->get_price_srv()->get_price_ext_dao()->get(array("sku"=>$sku, "platform_id"=>$platform_id))))
                    {
                        $action = "insert";
                        if (!isset($price_ext_vo))
                        {
                            $price_ext_vo = $this->get_price_srv()->get_price_ext_dao()->get();
                        }
                        $price_ext_obj = clone $price_ext_vo;
                        $price_ext_obj->set_sku($sku);
                        $price_ext_obj->set_platform_id($platform_id);
                        $price_ext_obj->set_ext_qty(0);
                    }
                    $price_ext_obj->set_action("P");
                    $price_ext_obj->set_remark((string)$batch_id);

                    $ret = $this->get_price_srv()->get_price_ext_dao()->$action($price_ext_obj);
                    //mail("steven@eservicesgroup.net", "update error", "Fnac Offer Update In Progress", "From: Admin <itsupport@eservicesgroup.net>\r\n");

                    return "Fnac Offer Update In Progress";
                }
                else
                {
                    // most likely Batch Error occurred here
                    if($xmlResponse->attributes()->status == "ERROR")
                    {
                        $error_code = $xmlResponse->error->attributes()->code;
                        $price_ext_obj = $this->get_price_srv()->get_price_ext_dao()->get(array("sku"=>$sku, "platform_id"=>$platform_id));

                        if(!$price_ext_obj)
                        {
                            $price_ext_vo = $this->get_price_srv()->get_price_ext_dao()->get();
                            $price_ext_obj = clone $price_ext_vo;
                            $price_ext_obj->set_sku($sku);
                            $price_ext_obj->set_platform_id($platform_id);
                            $price_ext_obj->set_ext_qty(0);
                            $price_ext_obj->set_action(null);
                            $price_ext_obj->set_remark(null);
                            $price_ext_obj->set_ext_item_id($ext_item_id);
                            $ret = $this->get_price_srv()->get_price_ext_dao()->insert($price_ext_obj);

                            $price_ext_obj = $this->get_price_srv()->get_price_ext_dao()->get(array("sku"=>$sku, "platform_id"=>$platform_id));
                        }

                        if($price_ext_obj)
                        {
                            $price_ext_obj->set_action(null);
                            $price_ext_obj->set_remark(null);
                            $ret = $this->get_price_srv()->get_price_ext_dao()->update($price_ext_obj);

                            $price_obj = $this->get_price_srv()->get_dao()->get(array("sku"=>$sku, "platform_id"=>$platform_id));
                            $price_obj->set_listing_status("N");
                            $ret = $this->get_price_srv()->get_dao()->update($price_obj);
                        }

                        switch ($error_code)
                        {
                            case 'ERR_150':
                                $message = "fnac_service ".__LINE__. "$platform_id - $sku: Error processing batch. BatchID: $batch_id. \r\n XML: \r\n $xmlString. \r\n {{$xmlResponse->attributes()->status}} {$error_code}";
                                mail("itsupport@eservicesgroup.net", "update error", $message, "From: Admin <itsupport@eservicesgroup.net>\r\n");

                                return "Fnac Error Code: $error_code - An error occured while processing this batch. Please submit it again.";
                                break;

                            default:
                                $message = "fnac_service ".__LINE__. "$platform_id - $sku: FNAC error code $error_code. BatchID: $batch_id. \r\n XML: \r\n $xmlString. \r\n {{$xmlResponse->attributes()->status}} {$xmlResponse->error->attributes()->code}";
                                mail("itsupport@eservicesgroup.net", "fnac update error", $message, "From: Admin <itsupport@eservicesgroup.net>\r\n");
                                return "fnac_service " .__LINE__. " $platform_id - $sku. Fnac Error Code: $error_code - {$xmlResponse->error}";
                                break;
                        }
                    }

                    $message = "fnac_service ".__LINE__. "$platform_id - $sku: FNAC error code $error_code. BatchID: $batch_id. \r\n XML: \r\n $xmlString. \r\n {{$xmlResponse->attributes()->status}} {$xmlResponse->error->attributes()->code}";
                    mail("itsupport@eservicesgroup.net", "fnac update error", $message, "From: Admin <itsupport@eservicesgroup.net>\r\n");
                    return "fnac_service " .__LINE__. " $platform_id - $sku. Unknown error, try again.";

                }
            }
        }
        else
        {
            $newResponse = $this->send_batch_status_request($batch_id, $country_id);
            return $this->check_fnac_batch_offers_update_status($newResponse, $sku, $batch_id, $country_id);
        }

        return "Unable to Connect to Fnac Server. Please Try Again.";
    }

    public function get_fnac_offer_list($country_id = "ES", $debug = 0, $enable_log = 0)
    {
        // This function gets all the offers currently listed on FNAC.
        // date format 2014-09-18

        if($_GET["start"])
            $create_startdate = $_GET["start"];
        else
            $create_startdate = "2014-01-01";

        if($_GET["end"])
            $create_enddate = $_GET["end"];
        else
            $create_enddate = date("Y-m-d");


        $this->country_id = $country_id;
        $offerlist = array();
        $filename = "FNAC{$country_id}_offerlist_".date("Ymd_His").".csv";

        if($offerlist = $this->retrieve_all_offer($create_startdate, $create_enddate))
        {
            ob_end_clean();
            header( 'Content-Type: text/csv' );
            header( 'Content-Disposition: attachment;filename='.$filename);
            $fp = fopen('php://output', 'w');

            fwrite($fp, "Offers created $create_startdate - $create_enddate\n");
            foreach ($offerlist as $key => $offer)
            {
                if($key == 0)
                {
                    # construct headers
                  fputcsv($fp, array_keys($offer));
                }

                foreach ($offer as $offerk => $offerv)
                {
                    # reconstruct the array because some values contain sub arrays
                    if(is_array($offerv))
                        $offer[$offerk] = implode(" || ", $offerv);
                }
                fputcsv($fp, $offer);
            }
             fclose($fp);
        }
        else
            var_dump("No offer list available");

    }

    public function sync_fnac_offer_list($country_id = "ES", $debug = 0, $enable_log = 0)
    {
        $this->country_id = $country_id;
        $platform_id = 'FNAC'.$country_id;

        ob_start();

        $option = array();
        if($_GET["limit"])
            $option["limit"] = $_GET["limit"];
        if($_GET["offset"])
            $option["offset"] = $_GET["offset"];

        $where["platform_id"] = $platform_id;
        if($_GET["sku"])
            $where["sku"] = $_GET["sku"];

        $price_obj_list = $this->get_price_srv()->get_dao()->get_list($where, $option);
        $update = array();
        $i = 0;
        if($price_obj_list)
        {
            foreach ($price_obj_list as $key => $obj)
            {
                set_time_limit(120);
                $do_update = false;

                if($i%50 == 0)
                {
                    sleep(2);
                }

                $sku = $obj->get_sku();

                $update[$sku]["product_fnac_id"] = $update[$sku]["fnac_quantity"] = "";
                $update[$sku]["original_ext_item_id"] = $update[$sku]["original_ext_qty"] = $update[$sku]["price_extend_update_status"] = $update[$sku]["price_extend_action"] = "";
                $update[$sku]["original_price_listing_status"] = $update[$sku]["price_update_status"] = $update[$sku]["price_action"] = "";

                if($xml = $this->get_fnac_offersquery_byskulist_xml($sku))
                {
                    if($this->xml_schema_validation($xml, "offers_query"))
                    {
                        $response = $this->do_post_request("offers_query", $xml);

                        if($response)
                        {
                            $xmlResponse = simplexml_load_string(trim($response), 'SimpleXMLElement', LIBXML_NOCDATA);

                            if(isset($xmlResponse->error))
                            {
                                $error_message .= __LINE__ . " $sku - XMLerror. XML response: \r\n" .$response . "\r\n";
                                // $update[$sku]["error_message"] = __LINE__ . " Error retrieve offers info \r\n XML Response: $response " . "\r\n";
                            }
                            else
                            {
                                $status = $xmlResponse->attributes()->status;

                                if($status == "OK")
                                {
                                    if(isset($xmlResponse->offer))
                                    {
                                        foreach($xmlResponse->offer as $offer)
                                        {
                                            // update FNAC listing info to database
                                            $ret = $this->update_fnac_to_database($platform_id="FNACES", $sku, __LINE__,  "update_from_fnac", $offer);
                                            $update[$sku] = $ret["update"][$sku];
                                            $error_message .= $ret["error_message"];
                                        }
                                    }
                                    else
                                    {
                                        // cannot find any product on FNAC
                                        $ret = $this->update_fnac_to_database($platform_id="FNACES", $sku, __LINE__,  "no_fnac_listing");
                                        $update[$sku] = $ret["update"][$sku];
                                        $error_message .= $ret["error_message"];
                                    }
                                }
                                else
                                {
                                    $error_message .= __LINE__ . " $sku - error status. \r\n XML Response: \r\n$response " . "\r\n";
                                }
                            }
                        }
                        else
                        {
                            $error_message .= __LINE__ . " $sku - no response. \r\n XML: \r\n$xml " . "\r\n";
                        }
                    }
                    else
                    {
                        $error_message .= __LINE__ . " $sku - fail schema validate. \r\n XML: $xml " . "\r\n";
                        // $error_message .= __LINE__ . " fnac_service. \nFail to retrieve offers at page {$this->page} \nxml validation error: " . $xml . "\r\n";
                        // $this->send_notification_email("OFFERS_QUERY_ERROR", $error_message);
                    }
                }
                $i++;

            }
        }

        if(isset($_GET["debug"]))
        {
            echo "<pre>"; echo "ERROR: "; var_dump($error_message); echo "<hr></hr>"; var_dump($update); die();
        }

        if($error_message)
            $error_message = "Errors: \r\n$error_message";

        $email[] = "melody.yu@eservicesgroup.com";
        $email[] = "ping@eservicesgroup.com";
        $email[] = "celine@eservicesgroup.net";
        $email[] = "romuald@eservicesgroup.net";

        $this->gen_and_send_attachment($email, "[FNACES] RE-SYNC FNAC PRODUCTS", $error_message, $update, "resync_fnac_orders_".date('Ymd_His').'.csv');
    }

    private function update_fnac_to_database($platform_id="FNACES", $sku, $line_of_call,  $update_type="no_fnac_listing", $fnac_offer = array())
    {
        $ret = array();
        $update[$sku]["product_fnac_id"] = $update[$sku]["fnac_quantity"] = "";
        $update[$sku]["original_ext_item_id"] = $update[$sku]["original_ext_qty"] = $update[$sku]["price_extend_update_status"] = $update[$sku]["price_extend_action"] = "";
        $update[$sku]["original_price_listing_status"] = $update[$sku]["price_update_status"] = $update[$sku]["price_action"] = "";
        $error_message = "";
        $do_price_extend_update = false;

        if($update_type == "no_fnac_listing")
        {
// Cannot find listing on FNAC, make sure it is set as Not Listed on our end
            $price_ext_obj = $this->get_price_srv()->get_price_ext_dao()->get(array("sku"=>$sku, "platform_id"=>$platform_id));
            if($price_ext_obj)
            {
                $ext_item_id = $update[$sku]["original_ext_item_id"] = $price_ext_obj->get_ext_item_id();
                $action = $price_ext_obj->get_action();
                $remark = $price_ext_obj->get_remark();
                $ext_qty = $update[$sku]["original_ext_qty"] = $price_ext_obj->get_ext_qty();

                if($ext_qty > 0)
                {
                    $price_ext_obj->set_ext_qty(0);
                    $update[$sku]["price_extend_action"] .= "set_0_qty||";
                    $do_price_extend_update = true;
                }
                if(!empty($action) || !empty($remark))
                {
                    $price_ext_obj->set_action(null);
                    $update[$sku]["price_extend_action"] .= "set_null_action||";
                    $do_price_extend_update = true;
                }
                if(!empty($ext_item_id))
                {
                    $price_ext_obj->set_ext_item_id(null);
                    $update[$sku]["price_extend_action"] .= "set_null_ext_item_id||";
                    $do_price_extend_update = true;
                }

                if($do_price_extend_update)
                {
                    $update[$sku]["price_extend_action"] = "NoFnacListing-".$update[$sku]["price_extend_action"];
                    if($rs = $this->get_price_srv()->get_price_ext_dao()->update($price_ext_obj))
                    {
                        $update[$sku]["price_extend_update_status"] = 'success';
                    }
                    else
                    {
                        $update[$sku]["price_extend_update_status"] = 'error:'.$this->get_price_srv()->get_price_ext_dao()->db->_error_message();
                    }
                }
                else
                {
                    $update[$sku]["price_extend_action"] = "no_change";
                    $update[$sku]["price_extend_update_status"] = "na";
                }
            }
            else
            {
                $error_message .= $line_of_call. " $sku - dbretrieveerror: ".$this->get_price_srv()->get_price_ext_dao()->db->_error_message() . "\r\n";
            }

            $price_obj = $this->get_price_srv()->get_dao()->get(array("sku"=>$sku, "platform_id"=>$platform_id));
            if($price_obj)
            {
                $listing_status = $update[$sku]["original_price_listing_status"] = $price_obj->get_listing_status();

                if($listing_status != 'N')
                {
                    $price_obj->set_listing_status('N');
                    $update[$sku]['price_action'] = 'unlist_price_no_fnac_listing';
                    if($this->get_price_srv()->get_dao()->update($price_obj))
                    {
                        $update[$sku]["price_update_status"] = 'success';
                    }
                    else
                    {
                        $update[$sku]["price_update_status"] = 'error:'.$this->get_price_srv()->get_dao()->db->_error_message();
                    }
                }
                else
                {
                    $update[$sku]['price_action'] = 'no_change';
                    $update[$sku]["price_update_status"] = 'na';
                }
            }
            else
            {
                $error_message .= $line_of_call . " $sku - dbretrieveerror: " .$this->get_price_srv()->get_dao()->db->_error_message() . "\r\n";
            }
        }
        elseif($update_type == "update_from_fnac")
        {
// update our database according to FNAC's info
            if($fnac_offer)
            {
                $product_fnac_id = $update[$sku]["product_fnac_id"] = (string)$fnac_offer->product_fnac_id;
                $fnac_quantity = $update[$sku]["fnac_quantity"] = (string)$fnac_offer->quantity;

                $price_ext_obj = $this->get_price_srv()->get_price_ext_dao()->get(array("sku"=>$sku, "platform_id"=>$platform_id));
                if($price_ext_obj)
                {
                    $ext_item_id = $update[$sku]["original_ext_item_id"] = $price_ext_obj->get_ext_item_id();
                    $action = $price_ext_obj->get_action();
                    $remark = $price_ext_obj->get_remark();
                    $ext_qty = $update[$sku]["original_ext_qty"] = $price_ext_obj->get_ext_qty();

                    if($ext_item_id != $product_fnac_id)
                    {
                        $price_ext_obj->set_ext_item_id($product_fnac_id);
                        $update[$sku]["price_extend_action"] .= "update_ext_item_id||";

                        $do_price_extend_update = true;
                    }
                    if(!empty($action) && empty($remark))
                    {
                        // if action is pending but no more batch_id, set to null and update from fnac
                        $price_ext_obj->set_action(null);
                        $update[$sku]["price_extend_action"] .= "set_null_action||";

                        $do_price_extend_update = true;
                    }
                    if($fnac_quantity != $ext_qty && empty($remark))
                    {
                        // if qty not in sync, must make sure there's no current pending job, i.e. $remark
                        $price_ext_obj->set_ext_qty($fnac_quantity);
                        $update[$sku]["price_extend_action"] .= "update_ext_qty||";

                        $do_price_extend_update = true;
                    }

                    if($do_price_extend_update)
                    {

                        $rs = $this->get_price_srv()->get_price_ext_dao()->update($price_ext_obj);

                        if($rs)
                        {
                            $update[$sku]["price_extend_update_status"] = 'success';
                        }
                        else
                        {
                            $update[$sku]["price_extend_update_status"] = 'error:'.$this->get_price_srv()->get_price_ext_dao()->db->_error_message();
                        }
                    }
                    else
                    {
                        $update[$sku]["price_extend_action"] = "no_change";
                        $update[$sku]["price_extend_update_status"] = "na";
                    }
                }
                else
                {
                    $error_message .= $line_of_call. " $sku - dbretrieveerror: ".$this->get_price_srv()->get_price_ext_dao()->db->_error_message() . "\r\n";
                    // $update[$sku]["price_extend_update"] = 'dbretrieveerror:'.$this->get_price_srv()->get_price_ext_dao()->db->_error_message();
                }

                $price_obj = $this->get_price_srv()->get_dao()->get(array("sku"=>$sku, "platform_id"=>$platform_id));
                if($price_obj)
                {
                    $listing_status = $price_obj->get_listing_status();
                    $listing_status = $update[$sku]["original_price_listing_status"] = $price_obj->get_listing_status();

                    if($fnac_quantity > 0 && $listing_status == 'N')
                    {
                        $update[$sku]['price_action'] = 'relist_price';
                        $price_obj->set_listing_status('L');
                        if($this->get_price_srv()->get_dao()->update($price_obj))
                        {
                            $update[$sku]["price_update_status"] = 'success';
                        }
                        else
                        {
                            $update[$sku]["price_update_status"] = 'error:'.$this->get_price_srv()->get_dao()->db->_error_message();
                        }
                    }
                    else
                    {
                        $update[$sku]['price_action'] = 'no_change';
                        $update[$sku]["price_update_status"] = 'na';
                    }
                }
                else
                {
                    $error_message .= $line_of_call . " $sku - dbretrieveerror: " .$this->get_price_srv()->get_dao()->db->_error_message() . "\r\n";
                }
            }
            else
            {
                $error_message .= $line_of_call . " $sku - no fnac offer passed in.\r\n";
            }
        }
        else
        {
            $error_message .= $line_of_call . " $sku - update_type not recognised \r\n";
        }

        $ret["update"] = $update;
        $ret["error_message"] = $error_message;
        return $ret;
    }

    private function gen_and_send_attachment($email_list=array(), $subject, $message="", $data = array(), $filename="")
    {
        include_once(BASEPATH . "plugins/phpmailer/phpmailer_pi.php");
        $phpmail = new phpmailer();
        $phpmail->IsSMTP();
        $phpmail->From = "Admin <admin@valuebasket.net>";

        ob_end_clean();
        if($data)
        {
            if(!$filename)
                $filename = "fnac_".date('Ymd_His').'.txt';

            ob_start();
            $fp = fopen('php://output', 'w');
            $i = 0;
            foreach ($data as $sku => $value)
            {
                if($i == 0)
                {
                    $header_arr = array_keys($value);
                    array_unshift($header_arr, "SKU");
                    fputcsv($fp, $header_arr);
                }

                array_unshift($value , $sku);
                fputcsv($fp, $value);
                $i++;
            }
            $csv_string = ob_get_clean();
            fclose($fp);

            if(isset($_GET["echofile"]))
            {
                header( 'Content-Type: text/csv' );
                header( 'Content-Disposition: attachment;filename='.$filename);
                echo $csv_string;
                die();

            }
            else
            {
                $phpmail->AddStringAttachment($csv_string, $filename, 'base64', 'text/csv');
            }
        }

        $email_list = (array)$email_list;
        if(!$email_list)
            $email_list[] = "ping@eservicesgroup.com";

        foreach ($email_list as $email)
        {
            $phpmail->AddAddress(trim($email));
        }

        $phpmail->Subject = $subject;
        $phpmail->IsHTML(false);
        $phpmail->Body = "Generated @ ". date("Y-m-d H:i:s") ."\r\n$message";
        // $phpmail->SMTPDebug  = 1;

        if(strpos($_SERVER["HTTP_HOST"], "admindev") === FALSE)
            $result = $phpmail->Send();

        echo "Update RESULT: <br><br><hr></hr>";
        foreach ($data as $sku => $value)
        {
            echo "<pre>";
            echo "$sku<br>";
            foreach ($value as $header => $result)
            {
                echo "<br>{$header}={$result}";
            }
            echo "</pre><hr></hr>";
        }
        echo "<hr></hr><br>MESSAGE: <br>$message";
        echo "<br><hr></hr>END";
    }

    private function retrieve_all_offer($create_startdate= "", $createenddate="", $page = 1, $joinpages = TRUE)
    {
        /*
            * We retrieve 100 results per page. If there are more than a page, then call this function again
            * with $joinpages = FALSE.
        */

        $offerlist =  array();
        $this->page = 1;
        if($xml = $this->get_fnac_offersquery_bydate_xml($create_startdate, $createenddate, $page))
        {
            if($this->xml_schema_validation($xml, "offers_query"))
            {
                if($response = $this->do_post_request("offers_query", $xml))
                {
                    $xmlResponse = simplexml_load_string(trim($response), 'SimpleXMLElement', LIBXML_NOCDATA);

                    if($xmlResponse->attributes()->status == "OK")
                    {
                        if(isset($xmlResponse->offer))
                        {
                            foreach($xmlResponse->offer as $order)
                            {
                                # converts xml object into array
                                $offerlist[] = $this->convertobjectotarray($order);
                            }
                        }

                        if($joinpages == TRUE)
                        {
                            # loop though each page and merge the orders
                            $totalpage = $xmlResponse->total_paging;
                            if($totalpage > 1)
                            {
                                for ($i=2; $i <= $totalpage; $i++)
                                {
                                    $this->page = $i;
                                    # call function again to merge orders in subsequent pages
                                    $joinlist = $this->retrieve_all_offer($create_startdate, $createenddate, $i, FALSE);
                                    if(!empty($offerlist))
                                        $offerlist = array_merge($offerlist, $joinlist);
                                }
                            }
                        }
                        return $offerlist;
                    }
                    else
                    {
                        $error_message = __LINE__ . " fnac_service \nFail to retrieve offers at page {$this->page} \nstatus error: " . $xmlResponse->attributes()->status;
                        $this->send_notification_email("OFFERS_QUERY_ERROR", $error_message);
                    }
                }
            }
            else
            {
                $error_message = __LINE__ . " fnac_service. \nFail to retrieve offers at page {$this->page} \nxml validation error: " . $xml;
                $this->send_notification_email("OFFERS_QUERY_ERROR", $error_message);
            }
        }
        else
        {
            $error_message = __LINE__ . " fnac_service. \nFail to retrieve offers at page {$this->page} \nxml: " . $xml;
        }

        $this->error_message .= $error_message."<br>";
        return FALSE;
    }

    private function convertobjectotarray($object)
    {
        # converts xml object into array
        return @json_decode(@json_encode($object),1);
    }

    public function update_cost_profit($soid_obj, $so_obj, $is_ca = false)
    {
        $price_srv = $this->get_price_srv();

        if ($prod_obj = $this->get_product_srv()->get_dao()->get_product_overview(array("sku"=>$soid_obj->get_item_sku(), "platform_id"=>$so_obj->get_platform_id()), array("limit"=>1, "skip_prod_status_checking" => 1)))
        {
            if($is_ca === FALSE)
            {
                # complementary acc cost already included in pricing tool
                $prod_obj->set_price($soid_obj->get_unit_price());
                $price_srv->calc_logistic_cost($prod_obj);
                $price_srv->calc_cost($prod_obj);
                $soid_obj->set_cost($prod_obj->get_cost() * $soid_obj->get_qty());
                $this->get_so_srv()->set_profit_info($soid_obj);
                $this->get_so_srv()->set_profit_info_raw($soid_obj, $so_obj->get_platform_id());
            }
            else
            {
                # if complementary acc, just set cost = supplier cost
                $soid_obj->set_cost($prod_obj->get_supplier_cost() * $soid_obj->get_qty());
            }
        }
        else
        {
            $soid_obj->set_cost('0');
            $soid_obj->set_profit('0');
            $soid_obj->set_margin('0');
        }
    }

    public function xml_schema_validation($xml, $call_name)
    {
        try
        {
            switch($call_name)
            {
                case 'auth':
                    $schema = "xsd/AuthenticationService.xsd";
                    break;
                case 'offers_update':
                    $schema = "xsd/OffersUpdateService.xsd";
                    break;
                case 'batch_status':
                    $schema = "xsd/BatchStatusService.xsd";
                    break;
                case 'orders_query':
                    $schema = "xsd/OrdersQueryService.xsd";
                    break;
                case 'orders_update':
                    $schema = "xsd/OrdersUpdateService.xsd";
                    break;
                case 'offers_query':
                    $schema = "xsd/OffersQueryService.xsd";
                    break;
                default:
                    return true;
            }

            $dom = new DOMDocument;
            $dom->loadXML($xml);
            $tpl_path = $this->get_config()->value_of("fnac_tpl_path");
            $valide = $dom->schemaValidate($tpl_path . $schema);
            if( ! $valide)
            {
              throw new Exception('xmlAuthentication: validation failed !');
            }

            return true;
        }
        catch(Exception $e)
        {
            $this->send_notification_email("XML_VALIDATION_ERROR", $xml . "\n" . $e);
            echo "XML_VALIDATION_ERROR";
        }

        return false;
    }

    public function add_log($connection_type, $call_name ,$content)
    {
        switch ($call_name)
        {
            case "auth":
                break;
            case "offers_update":
            case "batch_status":
            case "orders_query":
            case "orders_update":
            default:
                $data_path = $this->get_config()->value_of("data_path");

                $platform_path = $data_path."fnac";
                $this->check_path($platform_path);

                $feed_path = $platform_path."/".$call_name;
                $this->check_path($feed_path);

                $file_path = $feed_path."/".$connection_type;
                $this->check_path($file_path);

                $filename = $file_path."/".date("YmdHis").".xml";
                file_put_contents($filename, $content);

                break;
        }
    }

    public function check_path($path)
    {
        if (!is_dir($path))
        {
            mkdir($path);
        }
    }

    public function send_notification_email($pending_action, $content="")
    {
        $platform_id = "FNAC".$this->country_id;
        // if(strpos($_SERVER["HTTP_HOST"], "dev") !== FALSE)
        //  echo "<hr></hr>ERROR DEBUG - $pending_action<pre>$content</pre>";

        $send_itsupport_mail = true;
        switch ($pending_action)
        {
            case "HTTP_INFO_ERROR":
                $message = "Please be noted that http_info cannot be retrieved. Please check. \n$content";
                $title = "[VB] Retrieve $platform_id Order problems - HTTP_INFO_ERROR";
                break;
            case "CONNECT_ERROR":
                $message = "Please be noted that we are unable to connect to Fnac serverwhile processing {$this->process}. \r\n $content";
                $title = "[VB] $platform_id problems - CONNECT_ERROR";
                break;
            case "AUTHENTICATION_ERROR":
                $message = "Please be noted that we are unable to get authenticated while processing {$this->process}. \r\n " . $content;
                $title = "[VB] $platform_id problems - AUTHENTICATION_ERROR";
                break;
            case "API_CALL_ERROR":
                $message = "Please be noted that the API call has failed while processing {$this->process}. \r\n " . $content;
                $title = "[VB] $platform_id problems - API_CALL_ERROR";
                break;
            case "BATCH_ALREADY_EXIST":
                $message = "Please be noted that the batch already exists.\n" . $content;
                $title = "[VB] Retrieve $platform_id Order problems - BATCH_ALREADY_EXIST";
                break;
            case "BATCH_ERROR":
                $message = $content;
                $title = "[VB] Retrieve $platform_id Order problems - BATCH_ERROR";
                break;
            case "BATCH_UPDATE_ERROR":
                $message = $content;
                $title = "[VB] Retrieve $platform_id Order problems - BATCH_UPDATE_ERROR";
                break;
            case "XML_VALIDATION_ERROR":
                $message = "Please be noted that the xml format is invalid.\n" . $content;
                $title = "[VB] $platform_id XML schema problems - XML_VALIDATION_ERROR";
                break;
            case "WRONG_ORDER_STATUS":
                $message = $content;
                $title = "[VB] Retrieve $platform_id Order problems - WRONG_ORDER_STATUS";
                break;
            case "ACK_REQUEST_ERROR":
                $message = "Please be noted that the following SO failed to get status changed and marked acknowledged:\n" . $content;
                $title = "[VB] Retrieve $platform_id Order problems - ACK_REQUEST_ERROR";
                break;
            case "ACK_REQUEST_SUCCESS":
                $message = "Completed on ".date("Y-m-d H:i:s");
                $title = "[VB] Successful Alert - Acknowledge Feed for $platform_id";
                $send_itsupport_mail = FALSE;
                break;
            case "FULFILL_REQUEST_ERROR":
                $message = "Please be noted that the following SO failed to get status changed and marked fulfilled:\n" . $content;
                $title = "[VB] Retrieve $platform_id Order problems - FULFILL_REQUEST_ERROR";
                break;
            case "FULFILL_REQUEST_SUCCESS":
                $message = "Completed on ".date("Y-m-d H:i:s");
                $title = "[VB] Successful Alert - Fulfillment Feed for $platform_id";
                $send_itsupport_mail = FALSE;
                break;
            case "FULFILL_REQUEST_WARNING":
                $message = "The following so_no doesn't have OK Fnac status:\n" . $content;
                $title = "[VB] Retrieve $platform_id Order problems - FULFILL_REQUEST_WARNING";
                break;
            case "UPDATE_PAY_STATUS_ERROR":
                $message = "Please be noted that the following SO failed to get payment status updated:\n" . $content;
                $title = "[VB] Retrieve $platform_id Order problems - CRON_PAY_STATUS_ERROR";
                break;
            case "UPDATE_PAY_STATUS_SUCCESS":
                $message = "Completed on ".date("Y-m-d H:i:s");
                $title = "[VB] Successful Alert - Payment Status Updated for $platform_id";
                $send_itsupport_mail = FALSE;
                break;
            case "ORDER_CANCELLED":
                $message = "Please be noted that $platform_id Order " . $content . " has been cancelled";
                $title = "[VB] Order Cancel Alert - $platform_id";
                $send_itsupport_mail = FALSE;
                break;
            case "UPDATE_OFFER_ERROR":
                $message = $content;
                $title = "Fnac Update Offer Error - $platform_id";
                break;
            case "STOCK_ALERT":
                $message = $content;
                $title = "[VB] Fnac Order STOCK ALERT - $platform_id";
                $send_itsupport_mail = FALSE;
                break;
            case "STOCK_WARN":
                $message = $content;
                $title = "[VB] Fnac Order STOCK Warning - $platform_id";
                $send_itsupport_mail = FALSE;
                break;
            case "OFFERS_QUERY_ERROR":
                $message = $content;
                $title = "[VB] Fnac Offers Query Warning - $platform_id";
                break;

            default:
                $title = "[VB] $platform_id - $pending_action";
                $message = $content;
                break;

        }

        if($send_itsupport_mail)
        {
            $notification_email = "celine@eservicesgroup.net, romuald@eservicesgroup.net, itsupport@eservicesgroup.net, melody.yu@eservicesgroup.com, ping@eservicesgroup.com";
            mail($notification_email, $title, $message, "From: Admin <itsupport@eservicesgroup.net>\r\n");
        }
        else
        {
            mail("romuald@eservicesgroup.net,celine@eservicesgroup.net,melody.yu@eservicesgroup.com", $title, $message, "From: Admin <itsupport@eservicesgroup.net>\r\n");
        }
        $this->_show_debug_xml($pending_action, $message);
    }

    public function _show_debug_xml($title, $xml)
    {
        if(!$this->enable_log)
        {
            return false;
        }
        $xml = str_replace(array("'", "\"", "\n", "\r", "&quot;"), array("\"", "\\\"", "", "", ""), $xml);
        print "
            <div href=\"#\">" .  date("Y-m-d H:i:s") . " - "  . $title . "<font color=\"blue\" style=\"font-size:80%;cursor:pointer\"
            onclick='
                var win = window.open(\"\", \"win\");
                win.document.open(\"text/xml\", \"replace\");
        ";
        print "win.document.write(\"" . trim($xml) . "\");";
        print "win.document.close();
            '
            > (Show Detail)</font>
            </div>
        ";
    }
}

/* End of file fnac_service.php */
/* Location: ./system/application/libraries/service/Fnac_service.php */