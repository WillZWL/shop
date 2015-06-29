<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Review_fianet_service extends Base_service
{


    public function __construct()
    {
        parent::__construct();
        // include_once(APPPATH."libraries/dao/Product_feed_dao.php");
        // $this->set_dao(new Product_feed_dao());
        // include_once(APPPATH."libraries/dao/Product_feed_cat_dao.php");
        // $this->set_pfc_dao(new Product_feed_cat_dao());
    }


    public function send_order_data($so_obj, $client_obj)
    {
        # SBF #4168 - Send order info to Fianet
        // $debug = true;
        $debug = false;
        if (strpos($_SERVER["HTTP_HOST"], 'dev') !== false)
            $debug = true;

        $message = "";
        if ($so_obj && $client_obj) {
            $platform_id = $so_obj->get_platform_id();
            $biz_type = $so_obj->get_biz_type();

            $allow_platform_id = array("WEBFR");
            $allow_biz_type = array("ONLINE");

            if (in_array($platform_id, $allow_platform_id) && in_array($biz_type, $allow_biz_type)) {
                if ($debug) {
                    $fianet_url = "https://www.fia-net.com/engine/preprod/sendrating.cgi";  # fianet's pre-production url
                } else {
                    # THIS IS FIANET's LIVE SITE. DO NOT USE THIS WHEN DOING TESTING !!!!
                    $fianet_url = "https://www.fia-net.com/engine/sendrating.cgi";
                }

                $siteid = "57616";
                $privatekey = "93674ec3f5cd1c71166fb65af88690d1";

                $so_no = $so_obj->get_so_no();
                $billname = $so_obj->get_bill_name();
                $ipaddress = $so_obj->get_create_at();
                $order_create_date = $so_obj->get_order_create_date();
                $totalamount = $so_obj->get_amount();
                $clientemail = strtolower($client_obj->get_email());
                $fianet_crypt = md5("{$privatekey}_{$so_no}+{$order_create_date}=$clientemail");  # fianet's authentication key

                $dom = new DOMDocument('1.0', 'UTF-8');
                $root = $dom->createElement('control');
                $root = $dom->appendChild($root);

                $user = $dom->createElement('utilisateur');
                $root->appendChild($user);
                $name = $dom->createElement('nom');
                $user->appendChild($name);
                $text = $dom->createTextNode($billname);
                $name->appendChild($text);

                $email = $dom->createElement('email');
                $user->appendChild($email);
                $text = $dom->createTextNode($clientemail);
                $email->appendChild($text);

                $orderinfo = $dom->createElement('infocommande');
                $root->appendChild($orderinfo);
                $site_id = $dom->createElement('siteid');
                $orderinfo->appendChild($site_id);
                $text = $dom->createTextNode($siteid);
                $site_id->appendChild($text);

                $refid = $dom->createElement('refid');
                $orderinfo->appendChild($refid);
                $text = $dom->createTextNode($so_no);
                $refid->appendChild($text);

                $amount = $dom->createElement('montant');
                $orderinfo->appendChild($amount);
                $text = $dom->createTextNode($totalamount);
                $amount->appendChild($text);

                $amount_attr_currency = $dom->createAttribute("devise");
                $amount->appendChild($amount_attr_currency);
                $text = $dom->createTextNode('EUR');
                $amount_attr_currency->appendChild($text);

                $ip = $dom->createElement('ip');
                $orderinfo->appendChild($ip);
                $text = $dom->createTextNode($ipaddress);
                $ip->appendChild($text);

                $ip_attr_timestamp = $dom->createAttribute("timestamp");
                $ip->appendChild($ip_attr_timestamp);
                $text = $dom->createTextNode($order_create_date);
                $ip_attr_timestamp->appendChild($text);

                $crypt = $dom->createElement('crypt');
                $root->appendChild($crypt);
                $text = $dom->createTextNode($fianet_crypt);
                $crypt->appendChild($text);

                ###### DEBUG LINE BELOW ######
                // header("Content-Type: text/xml");    # for readability during debug
                // echo $dom->saveXML(); die();

                if ($xml = $dom->saveXML()) {
                    $fianet_checksum = md5($xml);
                    $postdata = (array(
                        "SiteID" => $siteid,
                        "XMLInfo" => $xml,
                        "CheckSum" => $fianet_checksum
                    )
                    );

                    $fields_string = http_build_query($postdata);

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $fianet_url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HEADER, 0);    // set to 1 for debug
                    curl_setopt($ch, CURLOPT_FAILONERROR, true);

                    // curl_setopt($ch, CURLOPT_CONNECTTIMEOUT , 500);
                    // curl_setopt($ch, CURLOPT_TIMEOUT, 800); //timeout in seconds

                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

                    //execute post
                    $content = curl_exec($ch);

                    $info = curl_getinfo($ch);
                    $header = curl_getinfo($ch);
                    $error = curl_error($ch);

                    curl_close($ch);
                    if (empty($error)) {
                        libxml_use_internal_errors(true);
                        if ($response_xml = simplexml_load_string($content, 'SimpleXMLElement', LIBXML_NOCDATA)) {
                            // echo $response_xml;
                            // die();
                            $result = (array)$response_xml["type"];
                            $detail = (array)$response_xml->detail;

                            if (strtoupper($result[0]) == 'OK') {
                                $message = "SENT TO FIANET SUCCESS so_no $so_no <br>Fianet's response: {$detail[0]}";
                            } else {
                                $message = "review_fianet_service.php line: " . __LINE__ . " Error in Fianet's response for so_no <$so_no>. \nFianet's error message {$detail[0]}";
                                $this->send_notification_email("response", $message);
                            }
                        }
                    } else {
                        $errors = libxml_get_errors();
                        $xml = explode('\n', $content);
                        foreach ($errors as $error) {
                            $libxml_errors .= $this->display_xml_error($error, $xml);
                        }

                        $message = "review_fianet_service.php line: " . __LINE__ . " Error in sending xml (curl) for so_no <$so_no>. \n XML error: $libxml_errors";
                        $this->send_notification_email("response", $message);

                    }
                } else {
                    $message = "review_fianet_service.php line: " . __LINE__ . " Error in saving xml for so_no <$so_no>: \nXML:\n $xml";
                    $this->send_notification_email("xml", $message);
                }
            }
        } else {
            $so_obj_text = print_r($so_obj, true);
            $client_obj_text = print_r($client_obj, true);
            $message = "review_fianet_service.php line: " . __LINE__ . " Empty so obj or client obj: \n $so_obj_text \n\n$client_obj_text";
            $this->send_notification_email("nodata", $message);
        }

        if ($message && $debug) {
            echo $message;
            die();
        }

        return true;
    }

    private function send_notification_email($type = "", $message = "")
    {
        $notification_email = "itsupport@eservicesgroup.net,aymeric@eservicesgroup.com";

        switch ($type) {
            case "xml":
                $title = "[FIANET] Problem forming XML";
                break;

            case "nodata":
                $title = "[FIANET] Empty SO/Client data";
                break;

            case "response":
                $title = "[FIANET] Error in Fianet's response";
                break;

        }

        mail($notification_email, $title, $message);
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

/* End of file review_fianet_service.php */
/* Location: ./system/application/libraries/service/Review_fianet_service.php */