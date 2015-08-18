<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Surplus_rpt_service extends Base_service
{
    private $notification_email = "itsupport@eservicesgroup.net";

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/dao/Sku_mapping_dao.php");
        $this->set_sku_mapping_dao(new Sku_mapping_dao());
        include_once(APPPATH . "libraries/dao/Selling_platform_dao.php");
        $this->set_selling_platform_dao(new Selling_platform_dao());
        include_once(APPPATH . "libraries/dao/Product_dao.php");
        $this->set_product_dao(new Product_dao());
        include_once(APPPATH . "libraries/dao/Price_dao.php");
        $this->set_price_dao(new Price_dao());

        include_once(APPPATH . "libraries/service/Inventory_service.php");
        $this->inventory_service = new inventory_service();

        include_once(APPPATH . "libraries/service/Context_config_service.php");
        $this->set_config_srv(new Context_config_service());
    }

    public function set_sku_mapping_dao(Base_dao $value)
    {
        $this->sku_mapping_dao = $value;
    }

    public function set_selling_platform_dao(Base_dao $value)
    {
        $this->selling_platform_dao = $value;
    }

    public function set_product_dao(Base_dao $value)
    {
        $this->product_dao = $value;
    }

    public function set_price_dao(Base_dao $value)
    {
        $this->price_dao = $value;
    }

    public function set_config_srv(Base_service $srv)
    {
        $this->config_srv = $srv;
    }

    public function get_unmapped_surplus($format = 'xml')
    {
        # if want to return the xml/csv, set $echo = 0
        define('DATAPATH', $this->get_config_srv()->value_of("unmapped_surplus_report"));
        $this->report = "unmapped_surplus_report";

        $surplus_list = $this->get_surplus_report();
        $reportdate = date('c');

        if (array_key_exists("error_msg", $surplus_list)) {
            $error_msg = str_replace('\n', '<br>', strip_tags($surplus_list['error_msg']));
            echo "<b>ERROR GETTING SURPLUS REPORT</b><br>Error msg: $error_msg";
            mail("itsupport@eservicesgroup.net,bd@eservicesgroup.net,celine@eservicesgroup.com", "Problem running Unmapped Surplus", $error_msg);

        } else {
            $doc = new DOMDocument('1.0');
            $doc->formatOutput = true;
            // $doc->preserveWhiteSpace = false;

            $data = $doc->createElement('data');
            $data = $doc->appendChild($data);

            $date = $doc->createElement('utccacheddate');
            $date = $data->appendChild($date);
            $date_node = $doc->createTextNode($reportdate);
            $date_node = $date->appendChild($date_node);

            $comment = $doc->createElement('comment');
            $comment = $data->appendChild($comment);
            $comment_node = $doc->createTextNode("Data shows surpluses that are not mapped in VB.");
            $comment_node = $comment->appendChild($comment_node);

            $ext_skus = $doc->createElement('ext_skus');
            $ext_skus = $data->appendChild($ext_skus);

            foreach ($surplus_list->skus->product as $sku_obj) {
                $ext_sku = (string)$sku_obj->sku;
                $prod_name = trim((string)$sku_obj->name);
                $qty = (string)$sku_obj->qty;
                $pricehkd = number_format(((float)$sku_obj->pricehkd), 2);

                $mapping_dao = $this->get_sku_mapping_dao();
                $map_obj = $mapping_dao->get(array('ext_sku' => $ext_sku, 'ext_sys' => 'WMS'));
                if (empty($map_obj)) {
                    # those not found in VB, put into xml
                    $prod = $doc->createElement('product');
                    $prod = $ext_skus->appendChild($prod);

                    $ext_skus_child = $doc->createElement('ext_sku');
                    $ext_skus_child = $prod->appendChild($ext_skus_child);
                    $ext_skus_node = $doc->createTextNode($ext_sku);
                    $ext_skus_node = $ext_skus_child->appendChild($ext_skus_node);

                    $name_child = $doc->createElement('prod_name');
                    $name_child = $prod->appendChild($name_child);
                    $name_node = $doc->createTextNode($prod_name);
                    $name_node = $name_child->appendChild($name_node);

                    $qty_child = $doc->createElement('qty');
                    $qty_child = $prod->appendChild($qty_child);
                    $qty_node = $doc->createTextNode($qty);
                    $qty_node = $qty_child->appendChild($qty_node);

                    $pricehkd_child = $doc->createElement('pricehkd');
                    $pricehkd_child = $prod->appendChild($pricehkd_child);
                    $pricehkd_node = $doc->createTextNode($pricehkd);
                    $pricehkd_node = $pricehkd_child->appendChild($pricehkd_node);

                }
            }

            $this->timestamp = date('Ymdhis', strtotime($reportdate));
            $this->filename = "unmapped_surplus_report_{$this->timestamp}";
            $fp = DATAPATH . "/xml/{$this->filename}.xml";
            if ($doc->save($fp)) {
                $list = file_get_contents($fp);

                if ($format == 'xml') {
                    return $list;

                } elseif ($format == 'csv') {
                    $list_ele = array("ext_sku", "prod_name", "qty", "pricehkd"); #xml element names
                    $headers = array("master_sku", "prod_name", "quantity", "price(hkd)");
                    $csv = $this->make_csv($list, $list_ele, $headers);

                    $content["filepath"] = $this->csv_path;
                    $content["timestamp"] = $this->timestamp;
                    $content["csv"] = $csv;
                    return $content;
                } else {
                    echo __FILE__ . "Line: " . __LINE__ . "Please input correct format.";
                }

            }
        }
    }

    public function get_config_srv()
    {
        return $this->config_srv;
    }

    public function get_surplus_report()
    {
        #SBF 3474 - reset surplus quantity before updating
        if ($this->product_dao->reset_surplus_quantity() === FALSE) {
            $ret['error_msg'] = __LINE__ . " surplus_rpt_service. Error reset surplus qty. \n" . $this->db->_error_message();
            return $ret;
        }
        if ($this->product_dao->reset_slow_move() === FALSE) {
            $ret['error_msg'] = __LINE__ . " surplus_rpt_service. Error reset slow move. \n" . $this->db->_error_message();
            return $ret;
        }

        set_time_limit(480);
        ini_set('memory_limit', '128M');
        libxml_use_internal_errors(true);
        $ch = curl_init();
        $url = "http://cps.eservicesgroup.net/surplus_report.php?xml";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $source = curl_exec($ch);

        if ($source !== FALSE) {
            $ret = simplexml_load_string($source, 'SimpleXMLElement', LIBXML_NOCDATA);
            if ($ret === FALSE) {
                foreach (libxml_get_errors() as $error) {
                    $ret['error_msg'] .= "<br>$error->message";
                }
            } else {
                // store in db
                foreach ($ret->skus->product as $sku_obj) {
                    $sku = $sku_obj->sku;
                    $qty = $sku_obj->qty;

                    #SBF #4252 - add info on slow moving products
                    $slow_move = $sku_obj->slow_move_7_days;

                    // $this->inventory_service->set_surplus_quantity($sku, $qty);
                    if ($this->product_dao->set_surplus_quantity($sku, $qty, $slow_move) === FALSE) {
                        $ret["error_msg"] .= "Error setting surplus qty for <$sku>, qty:$qty, slow_move:$slow_move.\n";
                    }
                }
            }
        } else {
            $error_message = __LINE__ . " surplus_rpt_service. Error getting contents from CPS. Curl error: " . curl_error($ch);
            $ret['error_msg'] = $error_message;
        }

        curl_close($ch);
        return $ret;

    }

    public function get_sku_mapping_dao()
    {
        return $this->sku_mapping_dao;
    }

    private function make_csv($list = "", $list_ele = array(), $headers = array())
    {
        # list_ele is an array of your xml element names, arranged in order of your headers
        # headers are the column labels of your csv report
        $ret = simplexml_load_string($list, 'SimpleXMLElement', LIBXML_NOCDATA);
        $this->csv_path = $path = DATAPATH . "/csv/{$this->filename}.csv";

        if (empty($list_ele) || empty($headers)) {
            echo "Please input xml's elements and headers for csv";
            return FALSE;
        }

        if ($ret === FALSE) {
            foreach (libxml_get_errors() as $error) {
                $ret['error_msg'] .= "\\n$error->message";
            }

            $error_msg = "{$this->report} generation failed. XML Error_msg: '{$ret['error_msg']}'. \n" . __FILE__ . " Line: " . __LINE__;
            echo "### DEBUG: $error_msg";
            $this->send_notification_email("XML", $error_msg); #xml_error
            return FALSE;

        } else {
            # parse xml objects into array for csv
            $report_content = array();
            $report_content[0] = $headers;
            foreach ($ret->ext_skus->product as $key => $obj) {
                $val_array = array();
                foreach ($list_ele as $list_key => $ele_name) {
                    # loop through xml. when it reaches an element in your $list_ele, it will grab the value
                    $val = (string)$obj->$ele_name;

                    if (empty($val_array)) {
                        $val_array = (array)$val;
                    } else {
                        array_push($val_array, $val);
                    }
                }

                # create rows
                $report_content[] = $val_array;
            }

            $save_fp = fopen($path, 'w');

            foreach ($report_content as $fields) {
                if (fputcsv($save_fp, $fields) === FALSE) {
                    $error_msg = "Line " . __LINE__ . "Error converting xml into csv file. \nFile: $save_fp";

                    $this->send_notification_email("CSV", $error_msg);
                    echo "<pre>DEBUG: $error_msg</pre>";
                    break;
                }
            }
            unset($report_content);
            fclose($save_fp);
        }

        $content = file_get_contents($path);
        return $content;
    }

    private function send_notification_email($error_type, $error_msg = '')
    {
        $website = 'valuebasket';

        switch ($error_type) {
            case "FE":
                $message = $error_msg;
                $title = "[$website] {$this->report} - FILE_ERROR";
                break;

            case "XML":
                $message = $error_msg;
                $title = "[$website] {$this->report} - XML_ERROR";
                break;

            // case "FE":
            //  $message = $error_msg;
            //  $title = "[$country_id - $website] Competitor price csv mapping - GET_FILE_ERROR";
            //  break;

            // case "UNP":
            //  $message = $error_msg;
            //  $title = "[$country_id - $website] Competitor price csv mapping - UPDATE_NOW_PRICE_ERROR";
            //  break;

            // case "ULP":
            //  $message = $error_msg;
            //  $title = "[$country_id - $website] Competitor price csv mapping - UPDATE_LAST_PRICE_ERROR";
            //  break;

        }

        mail($this->notification_email, $title, $message);

    }

    public function get_unlisted_surplus($format = 'xml')
    {
        set_time_limit(360);

        # if want to return the xml/csv, set $echo = 0
        define('DATAPATH', $this->get_config_srv()->value_of("unlisted_surplus_report"));
        $this->report = "unlisted_surplus_report";

        $surplus_list = $this->get_surplus_report();
        $reportdate = date('c');

        if (array_key_exists("error_msg", $surplus_list) && strpos($surplus_list['error_msg'], 'Curl error') !== FALSE) {
            for ($i = 0; $i < 3; $i++) {
                sleep(5);
                $surplus_list = $this->get_surplus_report();
                if (!array_key_exists("error_msg", $surplus_list))
                    break;

            }
        }

        if (array_key_exists("error_msg", $surplus_list)) {
            $error_msg = str_replace('\n', '<br>', strip_tags($surplus_list['error_msg']));
            echo "<b>ERROR GETTING SURPLUS REPORT</b><br>Error msg: $error_msg";
            mail("itsupport@eservicesgroup.net,bd@eservicesgroup.net,celine@eservicesgroup.com", "Problem running Unlisted Surplus", $error_msg);
        } else {
            if (($selling_platform_list = $this->get_selling_platform_dao()->get_list(array("status" => 1))) === FALSE) {
                $error_msg = "Could not get data from selling_platform. \nDB error: '" . $this->get_selling_platform_dao()->db->_error_message() . "'\n" . __FILE__ . " Line: " . __LINE__;
                $this->send_notification_email("DB", $error_msg);
                echo "###DEBUG: $error_msg";
                return FALSE;
            }

            $doc = new DOMDocument('1.0');
            $doc->formatOutput = true;
            // $doc->preserveWhiteSpace = false;

            $data = $doc->createElement('data');
            $data = $doc->appendChild($data);

            $date = $doc->createElement('utccacheddate');
            $date = $data->appendChild($date);
            $date_node = $doc->createTextNode($reportdate);
            $date_node = $date->appendChild($date_node);

            $comment = $doc->createElement('comment');
            $comment = $data->appendChild($comment);
            $comment_node = $doc->createTextNode("Data shows platforms on which surplus SKUs are not listed.");
            $comment_node = $comment->appendChild($comment_node);

            $ext_skus = $doc->createElement('ext_skus');
            $ext_skus = $data->appendChild($ext_skus);

            foreach ($surplus_list->skus->product as $sku_obj) {
                $ext_sku = (string)$sku_obj->sku;
                $prod_name = trim((string)$sku_obj->name);
                $qty = (string)$sku_obj->qty;
                $pricehkd = number_format(((float)$sku_obj->pricehkd), 2);

                $mapping_dao = $this->get_sku_mapping_dao();
                $price_dao = $this->get_price_dao();

                if ($map_obj = $mapping_dao->get(array('ext_sku' => $ext_sku, 'ext_sys' => 'WMS', 'status' => 1))) {
                    $vb_sku = $map_obj->get_sku();

                    foreach ($selling_platform_list as $obj) {
                        # we loop through all active selling platform in price table for each sku
                        # if empty result, means SKU not listed in this current platform

                        $platform_id = $obj->get_id();
                        $platform_name = $obj->get_name();
                        $price_obj = $price_dao->get(array('sku' => $vb_sku, 'platform_id' => $platform_id, 'listing_status' => 'L'));

                        if ($price_obj === FALSE) {
                            $error_msg = "Could not get data from price table. \nDB error: '" . $price_dao->db->_error_message() . "'\n" .
                                "\nVB sku: $vb_sku; platform_id:$platform_id\n" . __FILE__ . " Line: " . __LINE__;
                            $this->send_notification_email("DB", $error_msg);
                            echo "###DEBUG: $error_msg";
                            continue;
                        } elseif (empty($price_obj)) {
                            if (($prod_obj = $this->get_product_dao()->get(array('sku' => $vb_sku))) === FALSE) {
                                $error_msg = "Could not get data from product table. \nDB error: '" . $this->get_product_dao()->db->_error_message() . "'\n" .
                                    "\nVB sku: $vb_sku" . __FILE__ . " Line: " . __LINE__;
                                $this->send_notification_email("DB", $error_msg);
                                echo "###DEBUG: $error_msg";
                                continue;
                            } else {
                                $website_qty = $prod_obj->get_website_quantity();
                            }

                            # put missing platform_id into xml
                            $prod = $doc->createElement('product');
                            $prod = $ext_skus->appendChild($prod);

                            $ext_skus_child = $doc->createElement('ext_sku');
                            $ext_skus_child = $prod->appendChild($ext_skus_child);
                            $ext_skus_node = $doc->createTextNode($ext_sku);
                            $ext_skus_node = $ext_skus_child->appendChild($ext_skus_node);

                            $name_child = $doc->createElement('prod_name');
                            $name_child = $prod->appendChild($name_child);
                            $name_node = $doc->createTextNode($prod_name);
                            $name_node = $name_child->appendChild($name_node);

                            $qty_child = $doc->createElement('qty');
                            $qty_child = $prod->appendChild($qty_child);
                            $qty_node = $doc->createTextNode($qty);
                            $qty_node = $qty_child->appendChild($qty_node);

                            $pricehkd_child = $doc->createElement('pricehkd');
                            $pricehkd_child = $prod->appendChild($pricehkd_child);
                            $pricehkd_node = $doc->createTextNode($pricehkd);
                            $pricehkd_node = $pricehkd_child->appendChild($pricehkd_node);

                            $website_qty_child = $doc->createElement('website_qty');
                            $website_qty_child = $prod->appendChild($website_qty_child);
                            $website_qty_node = $doc->createTextNode($website_qty);
                            $website_qty_node = $website_qty_child->appendChild($website_qty_node);

                            $not_listed_child = $doc->createElement('not_listed_in');
                            $not_listed_child = $prod->appendChild($not_listed_child);
                            $not_listed_node = $doc->createTextNode($platform_name);
                            $not_listed_node = $not_listed_child->appendChild($not_listed_node);
                        }
                    }
                }
            }

            $this->timestamp = date('Ymdhis', strtotime($reportdate));
            $this->filename = "unlisted_surplus_report_{$this->timestamp}";
            $fp = DATAPATH . "/xml/{$this->filename}.xml";
            if ($doc->save($fp)) {
                $list = file_get_contents($fp);

                if ($format == 'xml') {
                    return $list;
                } elseif ($format == 'csv') {
                    $list_ele = array("ext_sku", "prod_name", "qty", "pricehkd", "website_qty", "not_listed_in"); #xml element names
                    $headers = array("master_sku", "prod_name", "qty", "price(hkd)", "website_qty", "not_listed_in");
                    $csv = $this->make_csv($list, $list_ele, $headers);

                    $content["filepath"] = $this->csv_path;
                    $content["timestamp"] = $this->timestamp;
                    $content["csv"] = $csv;

                    return $content;
                } else {
                    echo __FILE__ . "Line: " . __LINE__ . "Please input correct format.";
                }

            }
        }
    }

    public function get_selling_platform_dao()
    {
        return $this->selling_platform_dao;
    }

    public function get_price_dao()
    {
        return $this->price_dao;
    }

    public function get_product_dao()
    {
        return $this->product_dao;
    }


}