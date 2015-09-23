<?php
namespace ESG\Panther\Service;

use ESG\Panther\Service\FtpConnector;


class BatchService extends BaseService
{
    private $ftp;

    private $valid;
    private $ip_dao;
    private $iss_dao;
    private $iso_dao;
    private $ic_dao;
    private $isoi_dao;
    private $isoid_dao;

    function __construct()
    {
        parent::__construct();
        $this->ftpConnector = new  FtpConnector;

        // include_once(APPPATH . "libraries/service/Validation_service.php");
        // $this->set_valid(new Validation_service());
        // include_once(APPPATH . "libraries/service/Event_service.php");
        // $this->set_event(new Event_service());
        // include_once(APPPATH . "libraries/service/Data_exchange_service.php");
        // $this->set_dex(new Data_exchange_service());
        // include_once(APPPATH . "libraries/dao/Interface_price_dao.php");
        // $this->set_ip_dao(new Interface_price_dao());
        // include_once(APPPATH . "libraries/dao/Interface_so_shipment_dao.php");
        // $this->set_iss_dao(new Interface_so_shipment_dao());
        // include_once(APPPATH . "libraries/dao/Interface_so_dao.php");
        // $this->set_iso_dao(new Interface_so_dao());
        // include_once(APPPATH . "libraries/dao/Interface_client_dao.php");
        // $this->set_ic_dao(new Interface_client_dao());
        // include_once(APPPATH . "libraries/dao/Interface_so_item_dao.php");
        // $this->set_isoi_dao(new Interface_so_item_dao());
        // include_once(APPPATH . "libraries/dao/Interface_so_item_detail_dao.php");
        // $this->set_isoid_dao(new Interface_so_item_detail_dao());
    }

    function cron_ixtens_reprice($platform = "AMUK")
    {
        $func = "ixtens_reprice_" . strtolower($platform);
        include_once(BASEPATH . "libraries/Encrypt.php");
        $encrypt = new CI_Encrypt();
        $local_path = (rtrim($this->getDao('Config')->valueOf($func . "_path"), '/')) . '/';
        $ftp = $this->ftpConnector;
        $ftp_obj = $this->getDao('FtpInfo')->get(array("name" => "IXTENS_" . $platform));
        $ftp->set_remote_site($server = $ftp_obj->get_server());
        $ftp->set_username($ftp_obj->get_username());
        $ftp->set_password($encrypt->decode($ftp_obj->get_password()));
        $ftp->set_port($ftp_obj->get_port());
        $ftp->set_is_passive($ftp_obj->get_pasv());
        $folder = array('AMUS' => '', 'AMUK' => 'ToUK/', 'AMDE' => 'ToDE/', 'AMFR' => 'ToFR/');
        $remote_path = "ToSupplier/{$folder[$platform]}";
        $dao = $this->getDao('Batch');
        $tlog_dao = $this->getDao('TransmissionLog');
        $tlog_vo = $tlog_dao->get();

        $valid = $this->get_valid();
        $event = $this->get_event();
        if ($ftp->connect() !== FALSE) {
            if ($ftp->login() !== FALSE) {
                foreach ($ftp->ftplist($remote_path) as $rsfile) {
                    if (strpos($gfile = $rsfile, "RePricing") === 0 || strpos($gfile = ltrim($rsfile, $remote_path), "RePricing") === 0) {
                        if ($ftp->getfile($local_path . $gfile, $remote_path . $gfile)) {
                            $filelist[] = $gfile;
                            $ftp->delfile($remote_path . $gfile);
                            $ftp->putfile($local_path . $gfile, $remote_path . "done/" . $gfile);
                        }
                    } else {
                        if (strpos($gfile = $rsfile, "AmazonNewOrders") !== 0 && strpos($gfile = ltrim($rsfile, $remote_path), "AmazonNewOrders") !== 0 && $rsfile != '.' && $rsfile != '..') {
                            //$ftp->delfile($remote_path.$rsfile);
                        }
                    }
                }
            } else {
                $tlog_obj = clone $tlog_vo;
                $tlog_obj->set_func_name($func);
                $tlog_obj->set_message("failed_login_to_server '" . $server . "'");
                $tlog_dao->insert($tlog_obj);
            }
            $ftp->close();
        } else {
            $tlog_obj = clone $tlog_vo;
            $tlog_obj->set_func_name($func);
            $tlog_obj->set_message("cannot_connect_to_server '" . $server . "'");
            $tlog_dao->insert($tlog_obj);
        }

        $batch_vo = $dao->get();

        if ($filelist) {
            foreach ($filelist as $rsfile) {
                $tlog_obj = clone $tlog_vo;
                $tlog_obj->set_func_name($func);
                $batch_obj = $dao->get(array("remark" => $rsfile));
                $success = 1;
                if (!empty($batch_obj)) {
                    $tlog_obj->set_message($rsfile . " already_in_batch");
                    $tlog_dao->insert($tlog_obj);
                } else {
                    $rules[0] = array("not_empty");
                    $rules[1] = array("not_empty", "is_number", "min=0");
                    $valid->set_rules($rules);
                    if ($handle = @fopen($local_path . $rsfile, "r")) {
                        $tmp = fgets($handle);
                        while (!feof($handle)) {
                            $tmp = trim(fgets($handle));
                            if (!empty($tmp)) {
                                $ar_tmp = @explode("\t", $tmp);
                            }
                            $valid->set_data($ar_tmp);

                            $rs = FALSE;
                            try {
                                $rs = $valid->run();
                            } catch (Exception $e) {
                                $tlog_obj->set_message($e->getMessage());
                                $tlog_dao->insert($tlog_obj);
                                //$event->fire_event($func, $dto_obj);
                            }
                            if (!$rs) {
                                $success = 0;
                                break;
                            }
                        }
                        fclose($handle);
                        if ($success) {
                            $batch_obj = clone $batch_vo;
                            $batch_obj->set_func_name($func);
                            $batch_obj->set_status("N");
                            $batch_obj->set_listed(1);
                            $batch_obj->set_remark($rsfile);
                            $dao->insert($batch_obj);
                        }
                    } else {
                        $tlog_obj->set_message($rsfile . " not_found");
                        $tlog_dao->insert($tlog_obj);
                        //$event->fire_event($func, $dto_obj);
                    }
                }
            }
        }
        $this->batch_ixtens_reprice($platform);
    }

    function batch_ixtens_reprice($platform = "AMUK")
    {
        $func = "ixtens_reprice_" . strtolower($platform);
        $dao = $this->getDao('Batch');
        $valid = $this->get_valid();
        $dex = $this->get_dex();
        $local_path = (rtrim($this->getDao('Config')->valueOf($func . "_path"), '/')) . '/';

        $int_price_dao = $this->get_ip_dao();

        include_once(APPPATH . "libraries/dao/Price_dao.php");
        $price_dao = new Price_dao();

        include_once(APPPATH . "libraries/service/Price_amazon_service.php");
        $plat_price = new Price_amazon_service();
        $plat_price->set_tool_path('marketing/pricing_tool_amazon');

        include_once(APPPATH . "helpers/object_helper.php");
        $int_price_dao->include_dto("Interface_price_dto");
        $int_price_dto = new Interface_price_dto();

        $int_price_vo = $int_price_dao->get();
        $objlist = $dao->get_list(array("func_name" => $func, "status" => "N"));
        if ($objlist) {
            foreach ($objlist as $obj) {
                $success = 1;
                $batch_id = $obj->get_id();
                $obj_csv = new Csv_to_xml($local_path . $obj->get_remark(), APPPATH . 'data/ixtens_repice_pg_csv2xml.txt', TRUE, "\t", FALSE);
                $out_vo = new Xml_to_vo("", APPPATH . 'data/ixtens_repice_pg_xml2vo.txt');
                $output = $dex->convert($obj_csv, $out_vo);

                if ($output) {
                    foreach ($output as $int_price_obj) {
                        $int_price_obj->set_batch_id($batch_id);
                        $int_price_obj->set_platform_id($platform);
                        $int_price_obj->set_batch_status("N");
                        $int_price_dao->insert($int_price_obj);
                    }
                }

                $price_data = $plat_price->get_ixtens_reprice_list(array("batch_id" => $batch_id, "vpo.platform_id" => $platform));

                $obj->set_status("P");
                $dao->update($obj);
                $valid->set_exists_in(array("sku" => $price_data["sku"]));

                foreach ($output as $int_price_obj) {
                    $int_price_obj->set_batch_id($batch_id);
                    $int_price_obj->set_platform_id($platform);
                    $rules["sku"] = array("exists_in=sku");
                    $rules["price"] = array("min=" . $price_data["min_price"][$int_price_obj->get_sku()]);
                    $valid->set_rules($rules);
                    $valid->set_data($int_price_obj);
                    $rs = FALSE;
                    try {
                        $rs = $valid->run();
                    } catch (Exception $e) {
                        $int_price_obj->set_failed_reason($e->getMessage());
                    }
                    if ($rs) {
                        if ($int_price_obj->get_price() != $price_data["current_price"][$int_price_obj->get_sku()]) {
                            $price_obj = $price_dao->get(array("sku" => $int_price_obj->get_sku(), "platform_id" => $platform));
                            if ($price_obj && $price_obj->get_auto_price() == "Y") {
                                $int_price_obj->set_batch_status("R");
                            } else {
                                $int_price_obj->set_failed_reason("Auto-pricing is disabled for this item");
                                $int_price_obj->set_batch_status("S");
                            }
                        } else {
                            $int_price_obj->set_batch_status("S");
                        }
                    } else {
                        $int_price_obj->set_batch_status("F");
                        $success = 0;
                    }
                    $int_price_dao->update($int_price_obj);
                }

                if (!$success) {
                    $obj->set_status("CE");
                    $obj->set_end_time(date("Y-m-d H:i:s"));
                    $dao->update($obj);
                }

                $int_price_dao->sp_ixtens_reprice_pg($batch_id);
            }
        }
    }

    public function generate_sli_prod_feed($output = "file", $where = array())
    {
        $func = "sli_prod_feed";
        $status = array("I" => "In Stock", "O" => "Out Of Stock", "P" => "Pre-order", "A" => "Arriving");
        $local_path = rtrim($this->getDao('Config')->valueOf($func . "_path"), '/') . '/';
        $dex = $this->get_dex();
        include_once(APPPATH . "libraries/service/Product_service.php");
        $prod_svc = new Product_service();
        include_once APPPATH . "libraries/service/Log_service.php";
        $log_svc = new Log_service();
        include_once(APPPATH . "libraries/dto/event_email_dto.php");
        $email_dto = new Event_email_dto();

        $dto_list = $prod_svc->get_dao()->get_sli_feed();
        //echo $output."\n\n";
        if ($output == "web") {
            $obj_vo = new Vo_to_xml($dto_list, APPPATH . 'data/sli_prod_feed_vo2xml.txt', 'product_list');
            $out_xml = new Xml_to_xml();
            $output = $dex->convert($obj_vo, $out_xml);
            $output = str_replace("<entity>", "<product_list>", $output);
            $output = str_replace("</entity>", "</product_list>", $output);
            return $output;
        } else {
            if ($output == "xml") {
                $obj_vo = new Vo_to_xml($dto_list, APPPATH . 'data/sli_prod_feed_vo2xml.txt', 'product_list');
                $out_xml = new Xml_to_xml();
                $output = $dex->convert($obj_vo, $out_xml);
                $output = str_replace("<entity>", "<product_list>", $output);
                $output = str_replace("</entity>", "</product_list>", $output);
                $filepath = $this->getDao('Config')->valueOf("sli_xml_path");
                $filename = "SLIFeed_" . date("Ymd") . ".xml";
                $fp = @fopen($filepath . $filename, "w");
            } else {

                $obj_vo = new Vo_to_xml($dto_list, APPPATH . 'data/sli_prod_feed_vo2xml.txt', 'product_list');
                $out_csv = new Xml_to_csv("", APPPATH . 'data/sli_prod_feed_xml2csv.txt', TRUE, "\t");
                $output = $dex->convert($obj_vo, $out_csv);

                /*
                $output = "product_number\tmanufacturer_name\tproduct_name\tdescription\tcategory\tavailability\tavailability_url_main\tregular_price\tsales_price\tbuy_url\tlarge_image_url\tmodel_number\tean\tbrand\tcurrency\tkeywords\tshipping_cost\tspec\teuroprice\r\n";
                foreach($dto_list as $obj)
                {
                    $output .= $obj->get_sku()."\t";
                    $output .= $obj->get_manufacturer_name()."\t";
                    $output .= $obj->get_prod_name()."\t";
                    $output .= $obj->get_short_desc()."\t";
                    $output .= $obj->get_category()."\t";
                    $output .= $obj->get_website_status()."\t";
                    $output .= $obj->get_availability_url()."\t";
                    $output .= $obj->get_retail_price()."\t";
                    $output .= $obj->get_priceGBP()."\t";
                    $output .= $obj->get_link()."\t";
                    $output .= $obj->get_image_link()."\t";
                    $output .= $obj->get_mpn()."\t";
                    $output .= $obj->get_ean()."\t";
                    $output .= $obj->get_brand_name()."\t";
                    $output .= $obj->get_platform_currency_id()."\t";
                    $output .= $obj->get_keywords()."\t";
                    $output .= $obj->get_delivery_charge()."\t";
                    $output .= $obj->get_detail_desc()."\t";
                    $output .= $obj->get_priceEUR()."\t";
                    $output .= "\r\n";
                }
                */
                $filepath = $this->getDao('Config')->valueOf("sli_xml_path");
                $filename = "SLIFeed_" . date("Ymd") . ".txt";
                $fp = @fopen($filepath . $filename, "w");
            }
            if ($fp === FALSE) {
                $logheader = $log_svc->get_log_header();
                $logheader["type"] = "ERROR";
                $logheader["user"] = $_SESSION["user"]["id"];
                $logheader["userip"] = $_SERVER["REMOTE_ADDR"];
                $logheader["file"] = __FILE__;
                $logheader["line_number"] = __LINE__ - 8;
                $logheader["message"] = "Failed to write to file ";
                $log_svc->write_log($logheader);
            } else {
                @fwrite($fp, $output);
                @fclose($fp);

                $upload = "SLIFeed.txt";
                @unlink($filepath . $upload);
                @copy($filepath . $filename, $filepath . $upload);

                include_once(BASEPATH . "libraries/Encrypt.php");
                $encrypt = new CI_Encrypt();
                $ftp = $this->ftpConnector;
                $ftp_obj = $this->getDao('FtpInfo')->get(array("name" => "SLI"));

                $ftp->set_remote_site($server = $ftp_obj->get_server());
                $ftp->set_username($ftp_obj->get_username());
                $ftp->set_password($encrypt->decode($ftp_obj->get_password()));
                $ftp->set_port($ftp_obj->get_port());
                $ftp->set_is_passive($ftp_obj->get_pasv());

                $remote_path = '';
                $err = 0;

                if ($ftp->connect() !== FALSE) {
                    if ($ftp->login() !== FALSE) {
                        $result = $ftp->putfile($filepath . $upload, $remote_path . $upload);
                        if ($result === FALSE) {
                            $err = 1;
                        }
                    } else {
                        $err = 1;
                    }
                    $ftp->close();
                } else {
                    $err = 1;
                }

                if ($err) {
                    $tmp = clone $dto;
                    $tmp->set_event_id("notification");
                    $tmp->set_mail_to(array("tommy@eservicesgroup.net"));
                    $tmp->set_mail_from("do_not_reply@valuebasket.com");
                    $tmp->set_tpl_id("general_alert");
                    $tmp->set_replace(array("title" => "FTP Upload Alert - SLI feed upload failed - " . date("Y-m-d"), "message" => "Upload failed. Please upload manually. File is located at $filepath.$filename"));
                    $this->get_event()->fire_event($tmp);
                }
            }
        }
    }

    public function generate_prod_feed($platform = "AMUK")
    {
//      echo "$platform";
        $plat = strtolower($platform);
        if ($platform == "") {
            return FALSE;
        }

        include_once APPPATH . "data/prod_feed_inc.php";
        $profit_err = array();
        $str = array();
        $info_price = array();
        $price_engine = array();
        $lt_margin = array();

        include_once(APPPATH . "libraries/service/Price_service.php");
        $p_svc = new Price_service();

        include_once APPPATH . "libraries/service/Log_service.php";
        $log_svc = new Log_service();

        include_once APPPATH . "libraries/service/Product_service.php";
        $prod_svc = new Product_service();

        include_once APPPATH . "libraries/dao/Disc_prod_list_dao.php";
        $dpl_dao = new Disc_prod_list_dao();
        $dpl_obj = $dpl_dao->get();

        include_once APPPATH . "libraries/dao/Price_extend_dao.php";
        $price_ext_dao = new Price_extend_dao();

        include_once(APPPATH . "libraries/service/Price_amazon_service.php");
        $price_svc = new Price_amazon_service();

        include_once(APPPATH . "helpers/string_helper.php");


        include_once(APPPATH . "libraries/dto/event_email_dto.php");
        $email_dto = new Event_email_dto();

        $feed_obj = $prod_svc->get_dao()->get_product_feed($platform);
        if ($feed_obj) {
            foreach ($feed_obj as $obj) {
                $product_cost_dto = $p_svc->get_dao()->get_price_cost_dto($obj->get_sku(), $platform, $obj->get_shiptype());
                $p_svc->check_dto_price($product_cost_dto);
                $ship_mode_svc = $p_svc->get_price_service_from_dto($product_cost_dto, $product_cost_dto->get_price());
                $ship_mode_svc->set_platform_id($product_cost_dto->get_platform_id());
                $ship_mode_svc->set_platform_curr_id($product_cost_dto->get_platform_currency_id());

                // get fulfillment centre id for amazon
                $price_ext_obj = $price_ext_dao->get(array("sku" => $product_cost_dto->get_sku(), "platform_id" => $product_cost_dto->get_platform_id()));
                if (!$price_ext_obj || !$fc_id = $price_ext_obj->get_fulfillment_centre_id()) {
                    $fc_id = "DEFAULT";
                }
                $ship_mode_svc->set_fulfillment_centre_id($fc_id);

                $price_svc->calc_freight_cost($product_cost_dto, $ship_mode_svc, $product_cost_dto->get_platform_currency_id());

                $product_cost_dto->set_price($obj->get_price());
                $price_svc->set_dto($product_cost_dto);

                //price checking for negative profit
                $result = $price_svc->check_profit($product_cost_dto);
                if ($result["profitable"] || $obj->get_clearance() == '1' || $obj->get_listing_status != 'L') {
                    //set minprice to be price if price is less than minprice
                    if ($obj->get_price() < $result["minprice"]) {
                        //$this->set_price($result["minprice"]);
                        $lt_margin[] = $obj->get_sku();
                    }

                    $repricing = "";
                    if ($obj->get_auto_price() == "Y") {
                        if ($price_ext_obj) {
                            $repricing = $price_ext_obj->get_amazon_reprice_name();
                        }
                    }

                    $line = "";
                    $line .= $obj->get_sku() . ",";
                    $line .= ",,";
                    $line .= $obj->get_platform_code() . ",";
                    $line .= $ProductIDType . "," . $AFProductTaxCode . "," . $AFLaunchDate . ",,,";
                    $line .= "\"" . $obj->get_condition() . "\"" . "," . "\"" . $obj->get_condition_note() . "\"" . ",,,,,";

                    $cur_content = $obj->get_contents() == "" ? "NA" : $obj->get_contents();
                    $brand_name = str_replace(array("'", ","), "", $obj->get_brand_name());
                    $contents = str_replace(array("'", ",", "|"), "", nl2br($cur_content));
                    $contents = str_replace(array("<br>", "<br >", "<BR >", "<BR />", "<br />", "\r\n", "\n"), "-", $contents);
                    $contents = str_replace(array(",", "'", "|"), "", $contents);
                    $contents = strip_tags($contents);
                    $contents = strip_invalid_xml($contents);
                    if (strlen($contents) > 1800) {
                        $contents = cutstr($contents, 1800, "....");
                    }

                    $prod_name = $obj->get_prod_name() ? $obj->get_prod_name() : $obj->get_name();
                    $prod_name = str_replace(array(",", "\"", "'"), "", $prod_name);

                    $line .= $prod_name . "," . $brand_name . ",," . $contents . ",,,,,,,,,";
                    $line .= $obj->get_weight() . "," . $AFShippingWeightUnit . ",," . $obj->get_moq() . ",,,,";
                    $line .= $obj->get_brand_name() . "," . $obj->get_mpn() . ",";
                    $line .= ",,," . $AFIsGiftWrapAvailable . "," . $AFIsGiftMessageAvailable . ",,,,,,,,,,,,";
                    $search1 = $search2 = $search3 = $search4 = $search5 = "";
                    $s_arr = explode(",", $obj->get_keywords());
                    for ($i = 0; $i < count($s_arr) && $i < 5; $i++) {
                        ${"search" . ($i + 1)} = $s_arr[$i];
                    }
                    $line .= $search1 . "," . $search2 . "," . $search3 . "," . $search4 . "," . $search5 . ",";
                    $line .= ",,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,";
                    $line .= $AFDeliveryChannel . ",," . $obj->get_price() . ",,,,,,,,,,,,,,,";

                    if ($obj->get_inv_qty() > 0) {
                        $latency = $obj->get_latency() > 0 ? $obj->get_latency() : "";
                    } else {
                        $latency = $obj->get_oos_latency() > 0 ? $obj->get_oos_latency() : "";
                    }


                    if ($obj->get_listing_status() == 'L') {
                        $fc_id = ($fc_id == 'AMAZON_EU') ? 'AMAZON_NA' : $fc_id;
                        $ext_qty = $obj->get_ext_qty();
                    } else {
                        // If product is not listed change it to FBM and quantity 0, so it will be out of stock in Amazon webpage
                        // The reason was due to Ixtens using the same product list across all channels.
                        // We can't discontinue the unlisted product or else all platform will have the product discontinued.
                        $fc_id = 'DEFAULT';
                        $ext_qty = 0;
                    }

                    $line .= $fc_id . "," . $AFIsAvailable . "," . $ext_qty . "," . $latency . ",";
                    $line .= ",,,,,,";
                    $line .= $repricing . ",,";

                    $str[] = $line;

                    $info_price[] = $obj->get_sku() . "," . $obj->get_price() . ",,,";

                    $price_engine[] = $obj->get_sku() . "," . ($obj->get_auto_price() == 'Y' ? "true" : "false") . "," . $obj->get_platform_code() . ",,,Electronics," . round($result["minprice"], 2);

                    $info_inv[] = $obj->get_sku() . "," . $fc_id . ",false," . $ext_qty . ",,";
                } else {
                    $profit_error[$obj->get_sku()] = $obj->get_name();
                    $dplobj = clone $dpl_obj;
                    $dplobj->set_platform_id($platform);
                    $dplobj->set_sku($obj->get_sku());
                    $dpl_dao->insert($dplobj);
                }
            }
        }

        if (count($profit_err)) {
            $msg = $lang["profit_alert"] . "\n";
            foreach ($profit_err as $key => $value) {
                $msg .= "\r\n" . $key . " - " . $value;
            }
            $dto = clone $email_dto;
            $dto->set_event_id("notification");
            $dto->set_mail_to(array('steven@eservicesgroup.net'));
            $dto->set_mail_from('do_not_reply@valuebasket.com');
            $dto->set_tpl_id($plat . "_nprofit_alert");
            $dto->set_replace(array("msg" => $msg));
            $this->get_event()->fire_event($dto);
        }

        if (count($lt_margin)) {
            $msg = implode("\r\n", $lt_margin);
            $dto = clone $email_dto;
            $dto->set_event_id("notification");
            $dto->set_mail_to(array('steven@eservicesgroup.net'));
            $dto->set_mail_from('do_not_reply@valuebasket.com');
            $dto->set_tpl_id($plat . "_profit_alert");
            $dto->set_replace(array("msg" => $msg));
            $this->get_event()->fire_event($dto);

        }

        if (count($str)) {
            $plat = strtoupper($plat);
            $filepath = "/var/data/valuebasket.com/product/$platform/";
            $filepathp = "/var/data/valuebasket.com/price/$plat/";
            $filepathpe = "/var/data/valuebasket.com/priceEngine/$plat/";
            $filepathpi = "/var/data/valuebasket.com/inventory/$plat/";

            $time = date("YmdHis");
            $date = date("Ymd");
            $pffile = "prod_" . $time . ".xls";
            $pricefile = "price_" . $time . ".xls";
            $pefile = "priceEngine_" . $time . ".xls";
            $pifile = "Inventory" . $time . ".xls";

            //write file to local folder
            $fp = fopen($filepath . $pffile, "w+");
            $fpp = fopen($filepathp . $pricefile, "w+");
            $fpe = fopen($filepathpe . $pefile, "w+");
            $fpi = fopen($filepathpi . $pifile, "w+");

            if (!$fp || !$fpp || !$fpe) {
                $logheader = $log_svc->get_log_header();
                $logheader["type"] = "ERROR";
                $logheader["user"] = $_SESSION["user"]["id"];
                $logheader["userip"] = $_SERVER["REMOTE_ADDR"];
                $logheader["file"] = __FILE__;
                $logheader["line_number"] = __LINE__ - 8;
                $logheader["message"] = "Failed to create file for $platform product feed";
                $log_svc->write_log($logheader);

                $tmp = clone $email_dto;
                $tmp->set_event_id("notification");
                $tmp->set_mail_to(array("steven@eservicesgroup.net"));
                $tmp->set_mail_from("do_not_reply@valuebasket.com");
                $tmp->set_tpl_id("general_alert");
                $tmp->set_replace(array("title" => "Failed to generate product feed", "message" => "Cannot create file", "att_file" => $path . $filename));
                $this->get_event()->fire_event($tmp);
                return FALSE;
            } else {
                @fwrite($fp, $FeedHeader . "\r\n");
                @fwrite($fp, implode("\r\n", $str));
                @fclose($fp);

                @fwrite($fpp, $headersPrice);
                @fwrite($fpp, implode("\r\n", $info_price));
                @fclose($fpp);

                @fwrite($fpe, $headersPEngine);
                @fwrite($fpe, implode("\r\n", $price_engine));
                @fclose($fpe);

                @fwrite($fpi, $headersInv);
                @fwrite($fpi, implode("\r\n", $info_inv));
                @fclose($fpi);

                //ftp upload
                include_once(BASEPATH . "libraries/Encrypt.php");
                $encrypt = new CI_Encrypt();
                $ftp = $this->ftpConnector;
                $ftp_obj = $this->getDao('FtpInfo')->get(array("name" => "IXTENS_" . $platform));
                $ftp->set_remote_site($server = $ftp_obj->get_server());
                $ftp->set_username($ftp_obj->get_username());
                $ftp->set_password($encrypt->decode($ftp_obj->get_password()));
                $ftp->set_port($ftp_obj->get_port());
                $ftp->set_is_passive($ftp_obj->get_pasv());

                $folder = array('AMUS' => '', 'AMUK' => 'ToUK/', 'AMDE' => 'ToDE/', 'AMFR' => 'ToFR/');
                $remote_path = "FromSupplier/{$folder[$platform]}";

                $err = 0;

                if ($ftp->connect() !== FALSE) {
                    if ($ftp->login() !== FALSE) {
                        $result = $ftp->putfile($filepath . $pffile, $remote_path . $pffile);
                        $result2 = $ftp->putfile($filepathp . $pricefile, $remote_path . $pricefile);
                        $result3 = $ftp->putfile($filepathpe . $pefile, $remote_path . $pefile);
                        $result4 = $ftp->putfile($filepathpi . $pifile, $remote_path . $pifile);
                        if ($result === FALSE || $result2 === FALSE || $result3 === FALSE || $result4 === FALSE) {
                            $err = 1;
                        }
                    } else {
                        $err = 1;
                    }
                    $ftp->close();
                } else {
                    $err = 1;
                }

                if ($err) {
                    $logheader = $log_svc->get_log_header();
                    $logheader["type"] = "ERROR";
                    $logheader["user"] = $_SESSION["user"]["id"];
                    $logheader["userip"] = $_SERVER["REMOTE_ADDR"];
                    $logheader["file"] = __FILE__;
                    $logheader["line_number"] = __LINE__ - 28;
                    $logheader["message"] = "Failed to upload file to " . $ftp_obj->get_server();
                    $log_svc->write_log($logheader);

                    $tmp = clone $email_dto;
                    $tmp->set_event_id("notification");
                    $tmp->set_mail_to(array("steven@eservicesgroup.net"));
                    $tmp->set_mail_from("do_not_reply@valuebasket.com");
                    $tmp->set_tpl_id("general_alert");
                    $tmp->set_replace(array("title" => "Product feed for $platform was not uploaded", "message" => "Product feed for $platform was not uploaded on " . date("Y-m-d H:i:s") . ". Please upload the file manually"));
                    $this->get_event()->fire_event($tmp);
                } else {
                    $tmp = clone $email_dto;
                    $tmp->set_event_id("notification");
                    $tmp->set_mail_to(array("steven@eservicesgroup.net"));
                    $tmp->set_mail_from("do_not_reply@valuebasket.com");
                    $tmp->set_tpl_id("general_alert");
                    $tmp->set_replace(array("title" => "Product feed for $platform was successfully generated", "message" => "Product feed for $platform was successfully generated on " . date("Y-m-d H:i:s")));
                    $this->get_event()->fire_event($tmp);
                    return FALSE;
                }
            }
        } else {
            //nothing to feed, notice?
            $dto = clone $email_dto;
            $dto->set_event_id("notification");
            $dto->set_mail_to(array('steven@eservicesgroup.net'));
            $dto->set_mail_from('do_not_reply@valuebasket.com');
            $dto->set_tpl_id($plat . "_no_feed");
            $dto->set_replace(array("time" => date("Y-m-d H:i:s")));
            $this->get_event()->fire_event($dto);
        }
    }

    public function cron_get_amazon_order($platform = "AMUK")
    {
        $func = strtolower($platform) . "_fbm_order";
        $profit_err = array();
        $str = array();
        $info_price = array();
        $price_engine = array();
        $dao = $this->getDao('Batch');

        include_once(APPPATH . "libraries/dto/event_email_dto.php");
        $email_dto = new Event_email_dto();

        $tlog_dao = $this->getDao('TransmissionLog');
        $tlog_vo = $tlog_dao->get();

        $batch_vo = $dao->get();

        $localpath = "/var/data/valuebasket.com/orders/" . strtolower($platform) . "/";

        include_once(BASEPATH . "libraries/Encrypt.php");
        $encrypt = new CI_Encrypt();
        $ftp = $this->ftpConnector;
        $ftp_obj = $this->getDao('FtpInfo')->get(array("name" => "IXTENS_" . $platform));
        $ftp->set_remote_site($server = $ftp_obj->get_server());
        $ftp->set_username($ftp_obj->get_username());
        $ftp->set_password($encrypt->decode($ftp_obj->get_password()));
        $ftp->set_port($ftp_obj->get_port());
        $ftp->set_is_passive($ftp_obj->get_pasv());
        $valid = $this->get_valid();

        $thisfile = array();
        $list = array();

        if ($ftp->connect() !== FALSE) {
            if ($ftp->login() !== FALSE) {
                $folder = array('AMUS' => '', 'AMUK' => 'ToUK/', 'AMDE' => 'ToDE/', 'AMFR' => 'ToFR/');
                $remotepath = "ToSupplier/{$folder[$platform]}";
                $list = $ftp->ftplist($remotepath);
                //var_dump($list);
                if (is_array($list)) {
                    foreach ($list as $value) {
                        //capture all the available file
                        if (eregi("^AmazonNewOrders", $value) || eregi("^" . $remotepath . "AmazonNewOrders", $value)) {
                            $thisfile[] = eregi_replace("^" . $remotepath, "", $value);
                        }
                    }
                }
            } else {
                $tlog_obj = clone $tlog_vo;
                $tlog_obj->set_func_name($func);
                $tlog_obj->set_message("failed_login_to_server '" . $server . "'");
                $tlog_dao->insert($tlog_obj);
            }
        } else {
            $tlog_obj = clone $tlog_vo;
            $tlog_obj->set_func_name($func);
            $tlog_obj->set_message("failed_connect_to_server '" . $server . "'");
            $tlog_dao->insert($tlog_obj);
        }

        if (count($thisfile)) {
            foreach ($thisfile as $file) {
                $tlog_obj = clone $tlog_vo;
                $tlog_obj->set_func_name($func);
                $batch_obj = $dao->get(array("remark" => $file));
                $success = 1;
                if (!empty($batch_obj)) {
                    $tlog_obj->set_message($file . " already_in_batch");
                    $tlog_dao->insert($tlog_obj);
                } else {
                    $rules[0] = array("not_empty");
                    $rules[1] = array("not_empty");
                    $rules[2] = array("not_empty", "valid_email");
                    $rules[3] = array("not_empty");
                    $rules[5] = array("not_empty");
                    $rules[5] = array("not_empty", "is_number", "min=1");
                    $rules[9] = array("not_empty", "is_number");
                    $rules[17] = array("not_empty");
                    $rules[18] = array("not_empty");
                    $rules[21] = array("not_empty");
                    //$rules[22] = array("not_empty");
                    //$rules[23] = array("not_empty");
                    $rules[24] = array("not_empty");

                    //Order Found
                    $localfile = $localpath . $file;
                    //echo $localfile."\n";
                    $remotefile = $remotepath . $file;
                    //echo $remotefile;
                    $ftp->getfile($localfile, $remotefile);

                    if ($fp = @fopen($localfile, "r")) {
                        $valid->set_rules($rules);
                        //Skipping header line
                        $line = fgets($fp);
                        $success = 0;
                        $tlog_obj->set_message("file is empty");
                        while (!feof($fp)) {
                            $success = 1;
                            $line = trim(fgets($fp));

                            $rs = FALSE;
                            if (!empty($line)) {
                                $line_arr = @explode("\t", $line);
                            }
                            $valid->set_data($line_arr);

                            try {
                                $rs = $valid->run();
                            } catch (Exception $e) {
                                echo $e->getMessage();
                                $tlog_obj->set_message($e->getMessage());
                                //$tlog_dao->insert($tlog_obj);
                                //$event->fire_event($func, $dto_obj);
                            }

                            if (!$rs) {
                                echo "Error\n";
                                $tlog_dao->insert($tlog_obj);
                                $success = 0;
                                break;
                            }
                        }

                        @fclose($fp);
                        if ($success) {
                            $batch_obj = clone $batch_vo;
                            $batch_obj->set_func_name($func);
                            $batch_obj->set_status("N");
                            $batch_obj->set_listed(1);
                            $batch_obj->set_remark($file);
                            $dao->insert($batch_obj);

                            //backing up file on server
                            $result2 = $ftp->putfile($localfile, $remotepath . "done/" . $file);
                            $result3 = $ftp->delfile($remotefile);
                        }
                    }//if($fp = fopen())
                    else {
                        $tlog_obj->set_message($file . " not_found");
                        $tlog_dao->insert($tlog_obj);
                        //$event->fire_event($func, $dto_obj);
                    }
                }//if(!empty(batch_obj)
            }//foreach($thisfile as $file)
            $ftp->close();

        }//if(count($thisfile))
        else {
            //notification for order not found
            include_once APPPATH . "libraries/dto/event_email_dto.php";
            $dto = new Event_email_dto();

            $dto->set_event_id("notification");
            $dto->set_mail_to(array('steven@eservicesgroup.net'));
            $dto->set_mail_from('do_not_reply@valuebasket.com');
            $dto->set_tpl_id(strtolower($platform) . "_onf_notice");
            $dto->set_replace(array("date" => date("Y-m-d H:i:s")));
            $this->get_event()->fire_event($dto);
        }
        $this->batch_get_amazon_order($platform);
    }

    public function batch_get_amazon_order($platform = "AMUK")
    {
        echo "Entering Batch\n ";

        $platform_currency = array("AMUK" => "GBP", "AMFR" => "EUR", "AMDE" => "EUR", "AMUS" => "USD");

        $func = strtolower($platform) . "_fbm_order";
        $dao = $this->getDao('Batch');
        $valid = $this->get_valid();
        $dex = $this->get_dex();
        $local_path = "/var/data/valuebasket.com/orders/" . strtolower($platform) . "/";

        include_once(APPPATH . "libraries/dao/Interface_so_dao.php");
        $iso_dao = new Interface_so_dao();
        include_once(APPPATH . "libraries/dao/Interface_so_item_dao.php");
        $isoi_dao = new Interface_so_item_dao();
        include_once(APPPATH . "libraries/dao/Interface_so_item_detail_dao.php");
        $isoid_dao = new Interface_so_item_detail_dao();
        include_once(APPPATH . "libraries/dao/Interface_so_payment_status_dao.php");
        $isops_dao = new Interface_so_payment_status_dao();
        include_once(APPPATH . "libraries/dao/Interface_client_dao.php");
        $ic_dao = new Interface_client_dao();
        include_once(BASEPATH . "libraries/Encrypt.php");
        $encrypt = new CI_Encrypt();
        include_once(APPPATH . "libraries/dao/Client_dao.php");
        $client_dao = new Client_dao();
        include_once APPPATH . "libraries/service/Price_service.php";
        $price_svc = new Price_service();
        include_once APPPATH . "libraries/dao/So_dao.php";
        $so_dao = new So_dao();
        include_once APPPATH . "libraries/dao/Platform_biz_var_dao.php";
        $pbv_dao = new Platform_biz_var_dao();

        $iso_vo = $iso_dao->get();
        $isoi_vo = $isoi_dao->get();
        $isoid_vo = $isoid_dao->get();
        $isops_vo = $isops_dao->get();
        $ic_vo = $ic_dao->get();

        $pbv_obj = $pbv_dao->get(array("selling_platform_id" => strtoupper($platform)));
        $platform_country_id = $pbv_obj->get_platform_country_id();
        $platform_currency_id = $pbv_obj->get_platform_currency_id();

        $client_vo = $client_dao->get();

        $objlist = $dao->get_list(array("func_name" => $func, "status" => "N"));
        if ($objlist) {
            include_once "product_service.php";
            $prod_svc = new Product_service();
            $prod_list = $prod_svc->get_pblist_array();

            $xcnt = 0;
            foreach ($objlist as $bobj) {
                $batch_err = 0;
                $success = 1;
                $err_msg = "";

                $batch_id = $bobj->get_id();

                $obj_csv = new Csv_to_xml($local_path . $bobj->get_remark(), APPPATH . 'data/amazon_order_csv2xml.txt', TRUE, "\t", FALSE);
                $out_intobj = new Xml_to_xml();

                $output = $dex->convert($obj_csv, $out_intobj);
                $xml = simplexml_load_string($output, 'SimpleXMLElement', LIBXML_NOCDATA);

                ${"last_" . strtolower($platform) . "_orderid"} = "";
                $last_client_id = "";
                $last_so_no = "";
                $last_line_no = "";

                $dao->trans_start();
                foreach ($xml as $key => $xobj) {
                    $reason = "";
                    //client information
                    if (${"last_" . strtolower($platform) . "_orderid"} == $xobj->platform_order_id) {
                        //update int_so
                        $soobj = $iso_dao->get(array("trans_id" => $last_so_no));
                        $soobj->set_amount($soobj->get_amount() + (float)$xobj->price);
                        $soobj->set_delivery_charge($soobj->get_delivery_charge() + (float)$xobj->delivery_charge);
                        $ret = $iso_dao->update($soobj);
                        if ($ret === FALSE) {
                            var_dump($iso_dao->db->_error_message());
                            $err_msg .= "LINE: " . __LINE__ . "\nREASON: " . $iso_dao->db->_error_message() . "\nSQL: " . $iso_dao->db->last_query() . "\n\n\n";
                            $batch_err = 1;
                            break;
                        }
                        //int_so_item
                        $last_line_no++;
                    } else {
                        //int_client
                        $cobj = clone $ic_vo;
                        $cobj->set_batch_id($batch_id);
                        $cobj->set_email((string)$xobj->email);
                        $cobj->set_password($encrypt->encode(time() + $xcnt++));

                        $name = (string)$xobj->client_name;
                        $narr = explode(" ", $name);
                        $surname = $narr[count($narr) - 1];
                        $forename = substr($name, 0, -strlen($surname));
                        if ($forename == "") {
                            $forename = $surname;
                            $surname = " ";
                        }
                        $cobj->set_forename($forename);
                        $cobj->set_surname($surname);
                        $cobj->set_address_1((string)$xobj->send_to_addr1);
                        $cobj->set_address_2((string)$xobj->send_to_addr2);
                        $cobj->set_address_3((string)$xobj->send_to_addr3);
                        $cobj->set_city((string)$xobj->send_to_city);
                        $cobj->set_state((string)$xobj->send_to_state);
                        $cobj->set_postcode((string)$xobj->send_to_postal_code);
                        $cobj->set_country_id((string)$xobj->send_to_country);
                        $cobj->set_del_name((string)$name);
                        $cobj->set_del_address_1((string)$xobj->send_to_addr1);
                        $cobj->set_del_address_2((string)$xobj->send_to_addr2);
                        $cobj->set_del_address_3((string)$xobj->send_to_addr3);
                        $cobj->set_del_city((string)$xobj->send_to_city);
                        $cobj->set_del_state((string)$xobj->send_to_state);
                        $cobj->set_del_postcode((string)$xobj->send_to_postal_code);
                        $cobj->set_del_country_id((string)$xobj->send_to_country);
                        $cobj->set_tel_3((string)$xobj->send_to_phone);
                        $cobj->set_batch_status('N');
                        $ret = $ic_dao->insert($cobj);
                        if ($ret === FALSE) {
                            var_dump($ic_dao->db->_error_message());
                            $err_msg .= "LINE: " . __LINE__ . "\nREASON: " . $ic_dao->db->_error_message() . "\nSQL: " . $ic_dao->db->last_query() . "\n\n\n";
                            $batch_err = 1;
                            break;
                        }

                        $last_client_id = $ret->get_trans_id();

                        //int_so
                        $soobj = clone $iso_vo;
                        $soobj->set_batch_id($batch_id);
                        $soobj->set_platform_order_id((string)$xobj->platform_order_id);
                        $soobj->set_platform_id(strtoupper($platform));
                        $soobj->set_client_trans_id($last_client_id);
                        $soobj->set_biz_type('AMAZON');
                        $soobj->set_amount((float)$xobj->price + (float)$xobj->delivery_charge);
                        $soobj->set_cost((float)$xobj->commission);
                        $soobj->set_vat_percent($pbv_obj->get_vat_percent());
                        $soobj->set_delivery_charge((float)$xobj->delivery_charge);
                        $soobj->set_delivery_type_id('STD');
                        $soobj->set_weight(1);
                        $soobj->set_currency_id($platform_currency[strtoupper($platform)]);
                        $soobj->set_bill_name((string)$xobj->send_to_name);
                        $soobj->set_bill_address((string)$xobj->send_to_addr1 . "|" . (string)$xobj->send_to_addr2 . "|" . (string)$xobj->send_to_addr3);
                        $soobj->set_bill_postcode((string)$xobj->send_to_postal_code);
                        $soobj->set_bill_city((string)$xobj->send_to_city);
                        $soobj->set_bill_state((string)$xobj->send_to_state);
                        $soobj->set_bill_country_id((string)$xobj->send_to_country);
                        $soobj->set_delivery_name((string)$xobj->send_to_name);
                        $soobj->set_delivery_address((string)$xobj->send_to_addr1 . "|" . (string)$xobj->send_to_addr2 . "|" . (string)$xobj->send_to_addr3);
                        $soobj->set_delivery_postcode((string)$xobj->send_to_postal_code);
                        $soobj->set_delivery_city((string)$xobj->send_to_city);
                        $soobj->set_delivery_state((string)$xobj->send_to_state);
                        $soobj->set_delivery_country_id((string)$xobj->send_to_country);
                        $soobj->set_order_create_date(date("Y-m-d H:i:s", strtotime((string)$xobj->order_date)));

                        $soobj->set_batch_status('N');
                        $ret2 = $iso_dao->insert($soobj);
                        if ($ret2 === FALSE) {
                            var_dump($iso_dao->db->_error_message());
                            $err_msg .= "LINE: " . __LINE__ . "\nREASON: " . $iso_dao->db->_error_message() . "\nSQL: " . $iso_dao->db->last_query() . "\n\n\n";
                            $batch_err = 1;
                            break;
                        }
                        $last_so_no = $ret2->get_trans_id();

                        //int_so_payment_status
                        $sopsobj = clone $isops_vo;
                        $sopsobj->set_batch_id($batch_id);
                        $sopsobj->set_so_trans_id($last_so_no);
                        $sopsobj->set_payment_gateway_id("amazon");
                        $sopsobj->set_payment_status("P");
                        $sopsobj->set_batch_status('N');
                        $ret4 = $isops_dao->insert($sopsobj);
                        if ($ret4 === FALSE) {
                            var_dump($isops_dao->db->_error_message());
                            $err_msg .= "LINE: " . __LINE__ . "\nREASON: " . $isops_dao->db->_error_message() . "\nSQL: " . $isops_dao->db->last_query() . "\n\n\n";
                            $batch_err = 1;
                            break;
                        }

                        //int_so_item
                        $last_line_no = 1;
                    }
                    $soiobj = clone $isoi_vo;
                    $soiobj->set_batch_id($batch_id);
                    $soiobj->set_so_trans_id($last_so_no);
                    $soiobj->set_line_no($last_line_no);
                    $soiobj->set_prod_sku((string)$xobj->sku);
                    $soiobj->set_prod_name((string)$xobj->prod_name);
                    $soiobj->set_ext_item_cd((string)$xobj->order_item_code);
                    $soiobj->set_qty((string)$xobj->order_qty);
                    $soiobj->set_unit_price((float)$xobj->price / (int)$xobj->order_qty);
                    $soiobj->set_vat_total(0);
                    $soiobj->set_amount((float)$xobj->price);

                    $soiobj->set_batch_status('N');
                    $ret = $isoi_dao->insert($soiobj);
                    if ($ret3 === FALSE) {
                        var_dump($isoi_dao->db->_error_message());
                        $err_msg .= "LINE: " . __LINE__ . "\nREASON: " . $isoi_dao->db->_error_message() . "\nSQL: " . $isoi_dao->db->last_query() . "\n\n\n";
                        $batch_err = 1;
                        break;
                    }
                    ${"last_" . strtolower($platform) . "_orderid"} = (string)$xobj->platform_order_id;
                }
                $dao->trans_complete();
                if ($batch_err) {
                    $bobj->set_status("BE");
                    $dao->update($bobj);
                    continue;
                }
                $bobj->set_status("P");
                $dao->update($bobj);
                $client_list = $ic_dao->get_list(array("batch_id" => $batch_id));

                $cnt = $rcnt = 0;
                foreach ($client_list as $cobj) {
                    $reason = 0;
                    ++$rcnt;
                    $thisrow = TRUE;
                    $rules["email"] = array("valid_email");
                    $rules["forename"] = array("not_empty");
                    $rules["del_name"] = array("not_empty");
                    $valid->set_data($cobj);
                    $valid->set_rules($rules);
                    $rs = FALSE;
                    try {
                        $rs = $valid->run();
                    } catch (Exception $e) {
                        $cobj->set_failed_reason($e->getMessage());
                        $reason .= $e->getMessage();
                    }
                    if ($rs) {
                        $cobj->set_batch_status("R");
                    } else {
                        $cobj->set_batch_status("F");
                        $thisrow = FALSE;
                        $err_msg .= "LINE: " . __LINE__ . "\nREASON: Validation Error at interface_client " . $reason . "\n\n\n";
                        $success = 0;
                    }

                    $ic_dao->update($cobj);

                    $iso_vo = $iso_dao->get(array("batch_id" => $batch_id, "client_trans_id" => $cobj->get_trans_id()));
                    $isops_vo = $isops_dao->get(array("batch_id" => $batch_id, "so_trans_id" => $iso_vo->get_trans_id()));
                    $isoi_list = $isoi_dao->get_list(array("batch_id" => $batch_id, "so_trans_id" => $iso_vo->get_trans_id()));

                    $isoi_arr = array();
                    $isoid_arr = array();

                    $amazon_order_list = $so_dao->get_amazon_order_number($platform);
                    //validate iso_vo
                    $isorules = array("platform_order_id" => array("not_empty", "not_exists_in=platform_order_id"),
                        "amount" => array("not_empty", "is_number", "min=0.01"),
                        "bill_name" => array("not_empty"),
                        "bill_address" => array("not_empty"),
                        "delivery_name" => array("not_empty")
                    );
                    $valid->set_not_exists_in(array("platform_order_id" => $amazon_order_list));
                    $valid->set_rules($isorules);
                    $valid->set_data($iso_vo);
                    $rs = FALSE;
                    try {
                        $rs = $valid->run();
                    } catch (Exception $e) {
                        $iso_vo->set_failed_reason($e->getMessage());
                        $reason .= $e->getMessage();
                    }
                    if ($rs && $thisrow) {
                        $iso_vo->set_batch_status("R");
                    } else {
                        $iso_vo->set_batch_status("F");
                        $thisrow = FALSE;
                        $err_msg .= "LINE: " . __LINE__ . "\nREASON: Validation Error at interface_so " . $reason . "\n\n\n";
                        $success = 0;
                    }

                    //validate isops_vo
                    $isopsrules = array("so_trans_id" => array("not_empty"),
                        "payment_gateway_id" => array("not_empty"),
                        "payment_status" => array("not_empty"),
                    );
                    $valid->set_rules($isopsrules);
                    $valid->set_data($isops_vo);
                    $rs = FALSE;
                    try {
                        $rs = $valid->run();
                    } catch (Exception $e) {
                        var_dump($e->getMessage());
                        $isops_vo->set_failed_reason($e->getMessage());
                        $reason .= $e->getMessage();
                    }

                    if ($rs && $thisrow) {
                        $isops_vo->set_batch_status("R");
                    } else {
                        $isops_vo->set_batch_status("F");
                        $thisrow = FALSE;
                        $err_msg .= "LINE: " . __LINE__ . "\nREASON: Validation Error at interface_so_payment_status " . $reason . "\n\n\n";
                        $success = 0;
                    }

                    //validate isoi_vo
                    $isoirules = array("so_trans_id" => array("not_empty"),
                        "line_no" => array("not_empty", "is_number"),
                        "prod_sku" => array("not_empty", "exists_in=sku"),
                        "qty" => array("not_empty", "is_number"),
                        "amount" => array("not_empty", "is_number"),
                        "unit_price" => array("not_empty", "is_number"),
                        "ext_item_cd" => array("not_empty")
                    );
                    $valid->set_exists_in(array("sku" => $prod_list));
                    $valid->set_rules($isoirules);

                    foreach ($isoi_list as $isoi_vo) {
                        $valid->set_data($isoi_vo);

                        $rs = FALSE;
                        try {
                            $rs = $valid->run();
                        } catch (Exception $e) {
                            $isoi_vo->set_failed_reason($e->getMessage());
                            $reason .= $e->getMessage();
                        }
                        unset($e);
                        if ($rs && $thisrow) {
                            $isoi_vo->set_batch_status('R');
                            $isoi_vo->set_failed_reason("");

                            $list = $price_svc->get_dao()->get_items_w_price(array("prod_sku" => $isoi_vo->get_prod_sku(), "platform_id" => $platform));

                            foreach ($list as $item) {
                                $isoid_obj = clone $isoid_vo;
                                $isoid_obj->set_batch_id($batch_id);
                                $isoid_obj->set_so_trans_id($isoi_vo->get_so_trans_id());
                                $isoid_obj->set_line_no($isoi_vo->get_line_no());
                                $isoid_obj->set_item_sku($item->get_item_sku());
                                $isoid_obj->set_qty($isoi_vo->get_qty());
                                $isoid_obj->set_outstanding_qty($isoi_vo->get_qty());
                                $isoid_obj->set_unit_price($item->get_price());
                                $isoid_obj->set_vat_total(0);
                                $isoid_obj->set_amount($item->get_price() * $isoi_vo->get_qty());
                                $isoid_obj->set_cost(0);
                                $isoid_obj->set_batch_status('R');
                                $isoid_arr[] = $isoid_obj;
                            }
                        } else {
                            $isoi_vo->set_batch_status("F");
                            $thisrow = FALSE;
                            $err_msg .= "LINE: " . __LINE__ . "\nREASON: Validation Error at interface_so_item " . $reason . "\n\n\n";
                            $success = 0;
                        }

                        $isoi_arr[] = $isoi_vo;
                    }
                    if (!$thisrow) {
                        $iso_vo->set_batch_status('F');
                        $iso_vo->set_failed_reason($iso_vo->get_failed_reason() . "," . $reason);
                    }

                    $dao->trans_start();
                    $iso_dao->update($iso_vo);

                    foreach ($isoi_arr as $obj) {
                        $isoi_dao->update($obj);

                    }
                    if (count($isoid_arr)) {
                        foreach ($isoid_arr as $obj) {
                            if (!$isoid_dao->insert($obj)) {
                                var_dump($isoid_dao->db->_error_message());
                                $err_msg .= "LINE: " . __LINE__ . "\nREASON: " . $isoid_dao->db->_error_message() . "\nSQL: " . $isoid_dao->db->last_query() . "\n\n\n";
                                $batch_err = 1;
                                $success = 0;
                            }
                        }
                    }
                    $dao->trans_complete();
                    $reason = "";

                    if ($thisrow) {
                        $cnt++;
                    }
                }

                if ($cnt == $rcnt && $success) {
                    $bobj->set_status("C");
                    $bobj->set_end_time(date("Y:m:d H:i:s"));
                    $dao->update($bobj);
                    $this->proceed_amazon_batch($batch_id, $platform);
                } else {
                    $bobj->set_status("CE");
                    $bobj->set_end_time(date("Y-m-d H:i:s"));
                    $dao->update($bobj);
                    $this->proceed_amazon_batch($batch_id, $platform);
                }

                if (!$success || $batch_err) {
                    mail("steven@eservicesgroup.net", "[SE] Error Occurs when Batching Amazon Orders", $err_msg, "From: Admin <itsupport@eservicesgroup.net>\r\n");
                }
            }
        }
    }

    public function proceed_amazon_batch($batch_id, $platform = "AMUK")
    {
        echo "Proceed";
        include_once APPPATH . "libraries/dao/Interface_client_dao.php";
        include_once APPPATH . "libraries/dao/Interface_so_dao.php";
        include_once APPPATH . "libraries/dao/Interface_so_item_dao.php";
        include_once APPPATH . "libraries/dao/Interface_so_item_detail_dao.php";
        include_once APPPATH . "libraries/dao/Interface_so_payment_status_dao.php";
        include_once APPPATH . "libraries/dao/Client_dao.php";
        include_once APPPATH . "libraries/dao/So_dao.php";
        include_once APPPATH . "libraries/dao/So_item_dao.php";
        include_once APPPATH . "libraries/dao/So_item_detail_dao.php";
        include_once APPPATH . "libraries/dao/Platform_biz_var_dao.php";
        include_once APPPATH . "libraries/dao/Exchange_rate_dao.php";
        include_once APPPATH . "libraries/dao/So_payment_status_dao.php";
        include_once(BASEPATH . "libraries/Encrypt.php");
        include_once APPPATH . "libraries/dao/Price_dao.php";
        include_once APPPATH . "libraries/dao/Product_dao.php";
        include_once APPPATH . "libraries/service/Price_amazon_service.php";

        $encrypt = new CI_Encrypt();

        include_once(APPPATH . "libraries/dto/event_email_dto.php");
        $email_dto = new Event_email_dto();

        $ic_dao = new Interface_client_dao();
        $iso_dao = new Interface_so_dao();
        $isoi_dao = new Interface_so_item_dao();
        $isoid_dao = new Interface_so_item_detail_dao();
        $isops_dao = new Interface_so_payment_status_dao();
        $c_dao = new Client_dao();
        $so_dao = new So_dao();
        $soi_dao = new So_item_dao();
        $soid_dao = new So_item_detail_dao();
        $er_dao = new Exchange_rate_dao();
        $pbv_dao = new Platform_biz_var_dao();
        $prc_dao = new Price_dao();
        $prod_dao = new Product_dao();
        $prext_dao = new Price_extend_dao();
        $sops_dao = new So_payment_status_dao();
        $plat_prc_svc = new Price_amazon_service();
        $plat_prc_dto = $plat_prc_svc->get_dto();

        $func_curr = $this->getDao('Config')->valueOf("func_curr_id");
        $pbv_obj = $pbv_dao->get(array("selling_platform_id" => $platform));
        $er_obj = $er_dao->get(array("from_currency_id" => $pbv_obj->get_platform_currency_id(), "to_currency_id" => $func_curr));
        $er_ref_obj = $er_dao->get(array("from_currency_id" => $pbv_obj->get_platform_currency_id(), "to_currency_id" => "EUR"));

        $amazon_order_list = $so_dao->get_amazon_order_number($platform);
        $order_list = $iso_dao->get_list(array("batch_id" => $batch_id, "batch_status" => "R"));
        $seq = 0;
        $stock_alert = array();

        if (!empty($order_list)) {
            foreach ($order_list as $iso_obj) {
                $soi_array = array();
                $isoi_array = array();
                $soid_array = array();
                $isoid_array = array();
                $canupdate = 1;
                $err_msg = "";

                $isoi_list = $isoi_dao->get_list(array("batch_id" => $batch_id, "so_trans_id" => $iso_obj->get_trans_id()));

                //update client
                $ic_obj = $ic_dao->get(array("trans_id" => $iso_obj->get_client_trans_id()));
                if ($ic_obj->get_batch_status() == 'R') {
                    $eclient = $c_dao->get(array("email" => $ic_obj->get_email()));
                    if ($eclient) {
                        $action = "update";
                        //$eclient = $c_dao->get(array("id"=>$ic_obj->get_id()));
                    } else {
                        $action = "insert";
                        $eclient = $c_dao->get();
                        $eclient->set_email($ic_obj->get_email());
                        $eclient->set_password($encrypt->encode((time() + $seq)));
                        $eclient->set_forename($ic_obj->get_forename());
                        $eclient->set_surname($ic_obj->get_surname());
                        $eclient->set_companyname($ic_obj->get_companyname());
                        $eclient->set_address_1($ic_obj->get_address_1());
                        $eclient->set_address_2($ic_obj->get_address_2());
                        $eclient->set_address_3($ic_obj->get_address_3());
                        $eclient->set_city($ic_obj->get_city());
                        $eclient->set_state($ic_obj->get_state());
                        $eclient->set_country_id($ic_obj->get_country_id());
                        $eclient->set_postcode($ic_obj->get_postcode());
                        $seq++;
                    }
                    $eclient->set_del_name($ic_obj->get_del_name());
                    $eclient->set_del_company($ic_obj->get_del_company());
                    $eclient->set_del_address_1($ic_obj->get_del_address_1());
                    $eclient->set_del_address_2($ic_obj->get_del_address_2());
                    $eclient->set_del_address_3($ic_obj->get_del_address_3());
                    $eclient->set_del_city($ic_obj->get_del_city());
                    $eclient->set_del_state($ic_obj->get_del_state());
                    $eclient->set_del_country_id($ic_obj->get_del_country_id());
                    $eclient->set_del_postcode($ic_obj->get_del_postcode());
                    $eclient->set_tel_3($ic_obj->get_tel_3());

                    $c_dao->trans_start();
                    $c_dao->$action($eclient);
                    $ic_obj->set_batch_status('S');
                    $ic_obj->set_id($eclient->get_id());
                    $ic_dao->update($ic_obj);
                    $c_dao->trans_complete();
                }//if($ic_obj->get_batch_status() == 'R')

                $pass = 1;

                $soobj = $so_dao->get();
                $soiobj = $soi_dao->get();
                $soidobj = $soid_dao->get();

                $soobj->set_client_id($ic_obj->get_id());
                $soobj->set_platform_order_id($iso_obj->get_platform_order_id());
                $soobj->set_platform_id($platform);
                $soobj->set_delivery_type_id($pbv_obj->get_delivery_type());
                $soobj->set_biz_type($iso_obj->get_biz_type());
                $soobj->set_amount($iso_obj->get_amount());
                $soobj->set_delivery_charge($iso_obj->get_delivery_charge());
                $soobj->set_weight($iso_obj->get_weight());
                $soobj->set_currency_id($iso_obj->get_currency_id());
                $soobj->set_bill_name($iso_obj->get_bill_name());
                $soobj->set_bill_company($iso_obj->get_bill_company());
                $soobj->set_bill_address($iso_obj->get_bill_address());
                $soobj->set_bill_postcode($iso_obj->get_bill_postcode());
                $soobj->set_bill_city($iso_obj->get_bill_city());
                $soobj->set_bill_state($iso_obj->get_bill_state());
                $soobj->set_bill_country_id($iso_obj->get_bill_country_id());
                $soobj->set_delivery_name($iso_obj->get_delivery_name());
                $soobj->set_delivery_company($iso_obj->get_delivery_company());
                $soobj->set_delivery_address($iso_obj->get_delivery_address());
                $soobj->set_delivery_postcode($iso_obj->get_delivery_postcode());
                $soobj->set_delivery_city($iso_obj->get_delivery_city());
                $soobj->set_delivery_state($iso_obj->get_delivery_state());
                $soobj->set_delivery_country_id($iso_obj->get_delivery_country_id());
                $soobj->set_order_create_date($iso_obj->get_order_create_date());
                $soobj->set_status(1);//$soobj->set_status(1); // The order should passed credit check, once going in to the system.  (Trunks)
                $soobj->set_cost(0);
                $soobj->set_hold_status(0);
                $soobj->set_refund_status(0);
                $soobj->set_rate($er_obj->get_rate());
                $soobj->set_ref_1($er_ref_obj->get_rate());
                $soobj->set_expect_delivery_date(date("Y-m-d H:i:s", strtotime($iso_obj->get_order_create_date()) + 60 * 60 * 24 * 7));
                $soobj->set_vat_percent($pbv_obj->get_vat_percent());

                $isops_obj = $isops_dao->get(array("batch_id" => $batch_id, "so_trans_id" => $iso_obj->get_trans_id()));
                $sops_obj = $sops_dao->get();
                $sops_obj->set_payment_gateway_id($isops_obj->get_payment_gateway_id());
                $sops_obj->set_payment_status($isops_obj->get_payment_status());

                $iso_obj->set_batch_status('S');
                //$soobj, $iso_obj $sops_obj ready
                $total_cost = 0;
                $total_weight = 0;

                foreach ($isoi_list as $isoi_obj) {
                    if ($isoi_obj->get_batch_status() != 'R') {
                        $canupdate = 0;
                        break;
                    } else {
                        $vat_total = 0;
                        $tmp = clone $soiobj;
                        $tmp = $soi_dao->get();
                        $tmp->set_line_no($isoi_obj->get_line_no());
                        $tmp->set_prod_sku($isoi_obj->get_prod_sku());
                        $tmp->set_ext_item_cd($isoi_obj->get_ext_item_cd());
                        $tmp->set_qty($isoi_obj->get_qty());
                        $tmp->set_unit_price($isoi_obj->get_unit_price());
                        $tmp->set_amount($isoi_obj->get_amount());

                        $prc_obj = $prc_dao->get(array("platform_id" => $platform, "sku" => $isoi_obj->get_prod_sku()));
                        $tdto = $prc_dao->get_price_cost_dto($isoi_obj->get_prod_sku(), $platform, $prc_obj->get_default_shiptype());
                        $tdto->set_price($tmp->get_unit_price());
                        $plat_prc_svc->set_dto($tdto);
                        $plat_prc_svc->calc_profit();

                        $total_cost += $plat_prc_svc->get_dto()->get_cost() * $isoi_obj->get_qty();

                        $total_weight += $plat_prc_svc->get_dto()->get_prod_weight() * $isoi_obj->get_qty();
                        $tmp->set_vat_total($plat_prc_svc->get_dto()->get_vat() * $isoi_obj->get_qty());

                        $soi_array[$isoi_obj->get_trans_id()] = $tmp;

                        $isoi_obj->set_batch_status('S');
                        $isoi_array[$isoi_obj->get_trans_id()] = $isoi_obj;

                        $isoid_list = $isoid_dao->get_list(array("batch_id" => $batch_id, "so_trans_id" => $isoi_obj->get_so_trans_id(), "line_no" => $isoi_obj->get_line_no()));
                        foreach ($isoid_list as $isoid_obj) {
                            if ($isoid_obj->get_batch_status() != 'R') {
                                $canupdate = 0;
                                break;
                            }
                            $tmp2 = clone $soidobj;
                            $tmp2->set_line_no($isoid_obj->get_line_no());
                            $tmp2->set_item_sku($isoid_obj->get_item_sku());
                            $tmp2->set_qty($isoid_obj->get_qty());
                            $tmp2->set_outstanding_qty($isoid_obj->get_outstanding_qty());
                            $tmp2->set_discount($isoid_obj->get_discount());
                            $tmp2->set_amount($isoid_obj->get_amount());
                            $tmp2->set_promo_disc_amt(0);
                            $tmp2->set_unit_price($isoid_obj->get_unit_price());
                            $tmp2->set_cost($isoid_obj->get_cost());

                            $prc_obj = $prc_dao->get(array("platform_id" => $platform, "sku" => $isoi_obj->get_prod_sku()));
                            $tdto2 = $prc_dao->get_price_cost_dto($isoi_obj->get_prod_sku(), $platform, $prc_obj->get_default_shiptype());
                            $tdto2->set_price($tmp2->get_unit_price());
                            $plat_prc_svc->set_dto($tdto2);
                            $plat_prc_svc->calc_profit();

                            $tmp2->set_vat_total($plat_prc_svc->get_dto()->get_vat() * $isoid_obj->get_qty());
                            $tmp2->set_cost($plat_prc_svc->get_dto()->get_cost() * $isoid_obj->get_qty());
                            $tmp2->set_profit($isoid_obj->get_amount() - $plat_prc_svc->get_dto()->get_cost() * $isoid_obj->get_qty());
                            $tmp2->set_margin(round(($isoid_obj->get_amount() - $plat_prc_svc->get_dto()->get_cost() * $isoid_obj->get_qty()) / $isoid_obj->get_amount() * 100, 2));

                            $soid_array[$isoi_obj->get_trans_id()][$isoid_obj->get_trans_id()] = $tmp2;

                            $isoid_obj->set_batch_status('S');

                            $isoid_array[$isoid_obj->get_trans_id()] = $isoid_obj;
                        }//foreach($isoid_list as $isoid_obj)
                        //$soid_obj, $isoi_obj list ready
                    }//if($isoi_obj->get_batch_status() != 'R')
                }//foreach($isoi_list as $isoi_obj)
                //$isoi_obj, $soi_obj lists ready

                $soobj->set_cost($total_cost);
                $soobj->set_weight($total_weight);

                if ($canupdate) {
                    $complete = 1;
                    $c_dao->trans_start();
                    //insert so
                    $seq = $so_dao->seq_next_val();
                    $soobj->set_so_no("SO" . sprintf("%06d", $seq));
                    //var_dump(__LINE__."\n"."So Obj: ".$soobj."\n");
                    $so_dao->update_seq($seq);
                    $ret = $so_dao->insert($soobj);
                    //echo __LINE__."\n".$so_dao->db->last_query()."\n".$so_dao->db->_error_message()."\n";
                    if ($ret !== FALSE) {
                        $iso_obj->set_so_no($ret->get_so_no());
                        $iso_obj->set_batch_status('S');
                        $iso_dao->update($iso_obj);
                        //echo __LINE__."\n".$iso_dao->db->last_query()."\n".$iso_dao->db->_error_message()."\n";
                        //echo "SO Number: ".$ret->get_so_no()."\n";
                        //echo "so update success\n";

                        $sops_obj->set_so_no($ret->get_so_no());
                        if ($sops_dao->insert($sops_obj)) {
                            $isops_obj->set_batch_status('S');
                            $isops_dao->update($isops_obj);
                        } else {
                            $err_msg .= $sops_dao->db->last_query() . "\n" . $sops_dao->db->_error_message() . "\n\n";
                            $complete = 0;
                        }

                        //insert so_item
                        foreach ($soi_array as $key => $obj) {
                            $obj->set_so_no($soobj->get_so_no());
                            $ret2 = $soi_dao->insert($obj);
                            //echo __LINE__."\n".$soi_dao->db->last_query()."\n".$soi_dao->db->_error_message()."\n";
                            if ($ret2 !== FALSE) {
                                $isoiobj = $isoi_array[$key];
                                if ($isoiobj !== FALSE) {
                                    $isoiobj->set_so_no($ret2->get_so_no());
                                    $isoiobj->set_batch_status('S');
                                    $isoi_dao->update($isoiobj);
                                    echo $isoi_dao->db->_error_message();
                                    //echo "soi update success\n";
                                    //insert so_item_detail
                                    foreach ($soid_array[$key] as $key2 => $soidobj) {
                                        $soidobj->set_so_no($ret2->get_so_no());
                                        $ret3 = $soid_dao->insert($soidobj);
                                        echo $soid_dao->db->_error_message();
                                        if ($ret3 !== FALSE) {
                                            //stock alert feed for website quantity
                                            $prod_obj = $prod_dao->get(array("sku" => $ret3->get_item_sku()));
                                            if ($prod_obj) {
                                                $owq = $prod_obj->get_website_quantity();
                                                $nwq = $owq - $ret3->get_qty();
                                                if ($nwq <= 0) {
                                                    $nwq = 0;
                                                    $stock_alert[$ret->get_platform_order_id()][] = array("sku" => $ret3->get_item_sku(), "name" => $prod_obj->get_name());
                                                }

                                                $prod_obj->set_website_quantity($nwq);
                                                $tmp = $prod_dao->update($prod_obj);
                                                if ($tmp === FALSE) {
                                                    $err_msg .= $prod_dao->db->last_query() . "\n" . $prod_dao->db->_error_message() . "\n\n";
                                                    $complete = 0;
                                                }
                                            }

                                            //stock alert feed for platform specificed quantity
                                            $prext_obj = $prext_dao->get(array("sku" => $ret3->get_item_sku(), "platform_id" => $platform));
                                            if ($prext_obj) {
                                                $opq = $prext_obj->get_ext_qty();
                                                $npq = $opq - $ret3->get_qty();
                                                if ($npq <= 0) {
                                                    $npq = 0;
                                                    $stock_alert[$ret->get_platform_order_id()][] = array("sku" => $ret3->get_item_sku(), "name" => $prod_obj->get_name());
                                                }

                                                $prext_obj->set_ext_qty($npq);
                                                $tmp = $prext_dao->update($prext_obj);
                                                if ($tmp == FALSE) {
                                                    $err_msg .= $prext_dao->db->last_query() . "\n" . $prext_dao->db->_error_message() . "\n\n";
                                                    $complete = 0;
                                                }
                                            }

                                            $isoidobj = $isoid_array[$key2];
                                            if ($isoidobj !== FALSE) {
                                                $isoidobj->set_so_no($ret3->get_so_no());
                                                $isoidobj->set_batch_status('S');
                                                $isoid_dao->update($isoidobj);
                                                //echo $isoid_dao->db->_error_message();
                                                //echo "soid update success\n";
                                            }
                                        } else {
                                            $err_msg .= $soid_dao->db->last_query() . "\n" . $soid_dao->db->_error_message() . "\n\n";
                                            $complete = 0;
                                        }
                                    }
                                } else {
                                    $err_msg .= "Line " . __LINE__ . " Error\n\n";
                                    $complete = 0;
                                }
                            } else {
                                $err_msg .= $soi_dao->db->last_query() . "\n" . $soi_dao->db->_error_message() . "\n\n";
                                $complete = 0;
                            }
                        }
                    } else {
                        $err_msg .= $so_dao->db->last_query() . "\n" . $so_dao->db->_error_message() . "\n\n";
                        $complete = 0;
                    }
                    $c_dao->trans_complete();
                    if ($complete) {
                        //email notification to customer;
                        //update the status of order to not_ack
                        //$event->fire_event($func, $dto);
                        $to_addr = array("AMUK" => "amazoncentral@valuebasket.com", "AMFR" => "sandrine@valuebasket.com");
                        $dto = clone $email_dto;
                        $dto->set_event_id("notification");
                        $dto->set_mail_to($ic_obj->get_email());
                        $dto->set_mail_from($to_addr[$platform]);
                        $dto->set_tpl_id(strtolower($platform) . "_order_confirm");
                        $dto->set_replace(array("buyername" => $soobj->get_delivery_name(), "amazon_order_no" => $soobj->get_platform_order_id()));
                        //$this->get_event()->fire_event($dto);
                    } else {
                        //email notification to IT for order creation error
                        //$event->fire_event($func, $dto);
                        $bobj = $this->getDao('Batch')->get(array("id" => $batch_id));
                        $bobj->set_status("CE");
                        $bobj->set_end_time(date("Y-m-d H:i:s"));
                        $this->getDao('Batch')->update($bobj);

                        $dto = clone $email_dto;
                        $dto->set_event_id("notification");
                        $dto->set_mail_to(array("steven@eservicesgroup.net"));
                        $dto->set_mail_from("do_not_reply@valuebasket.com");
                        $dto->set_tpl_id("general_alert");
                        $dto->set_replace(array("title" => "Order Insert Failed for $platform", "message" => "$platform Order " . $iso_obj->get_platform_order_id() . " was not added into the database because of a query error. Please investigate the case ASAP.\n" . $err_msg));
                        $this->get_event()->fire_event($dto);
                    }
                }//if($canupdate)
            }//foreach($order_list as $iso_obj)

            if (count($stock_alert)) {
                foreach ($stock_alert as $key => $value) {
                    $message .= "Please be advised that Amazon order number " . $key . " has triggered the following product(s) to possibly be out of stock:<br><br>";
                    foreach ($value as $k => $c) {
                        $message .= $c["sku"] . " - " . $c["name"] . "<br>";
                    }
                    $message .= "<br><br>";
                }

                $message = ereg_replace("<br><br>$", "", $message);
                $title = "Amazon Order Import Warning - " . $platform;
                $dto = clone $email_dto;
                $dto->set_event_id("notification");
                $dto->set_mail_to("steven@eservicesgroup.net");
                $dto->set_mail_from("do_not_reply@valuebasket.com");
                $dto->set_tpl_id("general_alert");
                $dto->set_replace(array("title" => $title, "message" => $message));
                $this->get_event()->fire_event($dto);
            }
        }//if(!empty($order_list))

        return TRUE;
    }

    public function reprocess_amazon($batch_id = "", $trans_id = "", $platform = "AMUK")
    {
        $rules = array();
        $valid = $this->get_valid();
        $success = 1;
        $update_address = 0;

        include_once "Price_service.php";
        $price_svc = new Price_service();

        $iso_obj = $this->get_iso_dao()->get(array("batch_id" => $batch_id, "trans_id" => $trans_id));
        $ic_obj = $this->get_ic_dao()->get(array("trans_id" => $iso_obj->get_client_trans_id(), "batch_id" => $batch_id));
        $isoi_list = $this->get_isoi_dao()->get_list(array("batch_id" => $batch_id, "so_trans_id" => $trans_id));
        $isoid_vo = $this->get_isoid_dao()->get();


        if ($ic_obj->get_batch_status() == 'I') {
            $rules["email"] = array("valid_email");
            $rules["forename"] = array("not_empty");
            $valid->set_data($ic_obj);
            $valid->set_rules($rules);
            $reason = "";
            $rs = FALSE;
            try {
                $rs = $valid->run();
            } catch (Exception $e) {
                $ic_obj->set_failed_reason($e->getMessage());
                $reason .= $e->getMessage() . " ";
            }
            if ($rs) {
                $ic_obj->set_batch_status("R");
                $update_address = 1;
            } else {
                $ic_obj->set_batch_status("F");

                $success = 0;
            }
            $this->get_ic_dao()->update($ic_obj);
        }
        if ($success) {
            if ($update_address) {
                $iso_obj->set_bill_name($ic_obj->get_forename() . " " . $ic_obj->get_surname());
                $iso_obj->set_bill_address($ic_obj->get_address_1() . "|" . $ic_obj->get_address_2() . "|" . $ic_obj->get_address_3());
                $iso_obj->set_bill_postcode($ic_obj->get_postcode());
                $iso_obj->set_bill_city($ic_obj->get_city());
                $iso_obj->set_bill_state($ic_obj->get_state());
                $iso_obj->set_bill_country_id($ic_obj->get_country_id());
                $iso_obj->set_delivery_name($ic_obj->get_forename() . " " . $ic_obj->get_surname());
                $iso_obj->set_delivery_address($ic_obj->get_address_1() . "|" . $ic_obj->get_address_2() . "|" . $ic_obj->get_address_3());
                $iso_obj->set_delivery_postcode($ic_obj->get_postcode());
                $iso_obj->set_delivery_city($ic_obj->get_city());
                $iso_obj->set_delivery_state($ic_obj->get_state());
                $iso_obj->set_delivery_country_id($ic_obj->get_country_id());
            }

            $isorules = array(
                "platform_order_id" => array("not_empty", "exist_in=platform_order_id"),
                "amount" => array("not_empty", "is_number"),
                "qty" => array("not_empty", "is_number"),
                "unit_price" => array("not_empty", "is_number"),
                "bill_name" => array("not_empty"),
                "bill_address" => array("not_empty"),
                "delivery_name" => array("not_empty"),
            );
            $valid->set_exists_in(array("platform_order_id" => $amazon_order_list));
            $valid->set_rules($iso_rules);
            $valid->set_data($iso_obj);
            $rs = FALSE;
            try {
                $rs = $valid->run();
            } catch (Exception $e) {
                $iso_onj->set_failed_reason($e->getMessage());
                $reason .= $e->getMessage() . " ";
            }
            if ($rs) {
                $iso_obj->set_batch_status("R");
                $iso_obj->set_failed_reason("");
            } else {
                $iso_obj->set_batch_status("F");
                $success = 0;
            }

            if ($success) {
                include_once "product_service.php";
                $prod_svc = new Product_service();

                $prod_list = $prod_svc->get_pblist_array();

                $isoirules = array("so_trans_id" => array("not_empty"),
                    "line_no" => array("not_empty", "is_number"),
                    "prod_sku" => array("not_empty", "exists_in=sku"),
                    "qty" => array("not_empty", "is_number"),
                    "amount" => array("not_empty", "is_number"),
                    "unit_price" => array("not_empty", "is_number"),
                    "ext_item_cd" => array("not_empty")
                );
                $valid->set_exists_in(array("sku" => $prod_list));
                $valid->set_rules($isoirules);
                foreach ($isoi_list as $isoi_obj) {
                    $valid->set_data($isoi_obj);

                    $rs = FALSE;
                    try {
                        $rs = $valid->run();
                    } catch (Exception $e) {
                        $isoi_obj->set_failed_reason($e->getMessage());
                        $reason .= $e->getMessage() . " ";
                    }
                    if ($rs) {
                        $isoi_obj->set_batch_status('R');
                        $isoi_obj->set_failed_reason('');

                        $this->get_isoid_dao()->q_delete(array("batch_id" => $batch_id, "so_trans_id" => $trans_id, "line_no" => $isoi_obj->get_line_no()));
                        $list = $price_svc->get_dao()->get_items_w_price(array("prod_sku" => $isoi_obj->get_prod_sku(), "platform_id" => $platform));

                        foreach ($list as $item) {
                            $isoid_obj = clone $isoid_vo;
                            $isoid_obj->set_batch_id($batch_id);
                            $isoid_obj->set_so_trans_id($isoi_obj->get_so_trans_id());
                            $isoid_obj->set_line_no($isoi_obj->get_line_no());
                            $isoid_obj->set_item_sku($item->get_item_sku());
                            $isoid_obj->set_qty($isoi_obj->get_qty());
                            $isoid_obj->set_outstanding_qty($isoi_obj->get_qty());
                            $isoid_obj->set_unit_price($item->get_price());
                            $isoid_obj->set_amount($item->get_price() * $isoi_obj->get_qty());
                            $isoid_obj->set_batch_status('R');

                            $this->get_isoid_dao()->insert($isoid_obj);
                        }
                    } else {
                        $success = 0;
                        $isoi_obj->set_batch_status('F');
                    }

                    $this->get_isoi_dao()->update($isoi_obj);
                }
                $iso_obj = $this->get_iso_dao()->get(array("batch_id" => $batch_id, "trans_id" => $trans_id));
                if (!$success) {
                    $iso_obj->set_batch_status('F');
                    $iso_obj->set_failed_reason($reason);

                    $this->get_iso_dao()->update($iso_obj);
                } else {
                    $iso_obj->set_batch_status('R');
                    $iso_obj->set_failed_reason("");

                    $this->get_iso_dao()->update($iso_obj);
                }
            }
        } else {
            $iso_obj->set_status('F');
            $iso_obj->set_failed_reason($reason);

            $this->get_iso_dao()->update($iso_obj);
        }

        $this->proceed_amazon_batch($batch_id, $iso_obj->get_platform_id());
    }

    public function cron_get_fba_order($platform = "AMUK")
    {
        $func = strtolower($platform) . "_fba_order";
        $profit_err = array();
        $str = array();
        $info_price = array();
        $price_engine = array();
        $dao = $this->getDao('Batch');

        include_once(APPPATH . "libraries/dto/event_email_dto.php");
        $email_dto = new Event_email_dto();

        $tlog_dao = $this->getDao('TransmissionLog');
        $tlog_vo = $tlog_dao->get();
        $batch_vo = $dao->get();

        $localpath = "/var/data/valuebasket.com/orders/" . strtolower($platform) . "/";

        include_once(BASEPATH . "libraries/Encrypt.php");
        $encrypt = new CI_Encrypt();
        $ftp = $this->ftpConnector;
        $ftp_obj = $this->getDao('FtpInfo')->get(array("name" => "IXTENS_" . $platform));
        $ftp->set_remote_site($server = $ftp_obj->get_server());
        $ftp->set_username($ftp_obj->get_username());
        $ftp->set_password($encrypt->decode($ftp_obj->get_password()));
        $ftp->set_port($ftp_obj->get_port());
        $ftp->set_is_passive($ftp_obj->get_pasv());
        $valid = $this->get_valid();

        $thisfile = array();
        $list = array();

        if ($ftp->connect() !== FALSE) {
            if ($ftp->login() !== FALSE) {
                $folder = array('AMUS' => '', 'AMUK' => 'ToUK/', 'AMDE' => 'ToDE/', 'AMFR' => 'ToFR/');
                $remotepath = "ToSupplier/{$folder[$platform]}SettlementReport/";
                $list = $ftp->ftplist($remotepath);
                // var_dump($list);
                if (is_array($list)) {
                    foreach ($list as $f) {
                        if ($ftp->is_ftp_dir($f) && basename($f) != "done") {
                            $file_list = $ftp->ftplist($f);
                            foreach ($file_list as $value) {
                                //capture all the available file
                                if (preg_match("/^OrderSettlement/", basename($value))) {
                                    continue;
                                } elseif (preg_match("/^result./", basename($value))) {
                                    $thisfile[] = $value;
                                }
                            }
                        }
                    }
                }
            } else {
                $tlog_obj = clone $tlog_vo;
                $tlog_obj->set_func_name($func);
                $tlog_obj->set_message("failed_login_to_server '" . $server . "'");
                $tlog_dao->insert($tlog_obj);
            }
        } else {
            $tlog_obj = clone $tlog_vo;
            $tlog_obj->set_func_name($func);
            $tlog_obj->set_message("failed_connect_to_server '" . $server . "'");
            $tlog_dao->insert($tlog_obj);
        }

        if (count($thisfile)) {
            $all_file_success = 1;
            foreach ($thisfile as $file) {
                $new_file = "";
                $tmp = explode('/', $file);
                $path_info = pathinfo($file);
                $report_date = $tmp[count($tmp) - 2];
                $tmp2 = explode('.', basename($file));
                $num = $tmp2[1];
                $new_file = "SettlementReport_{$report_date}_{$num}.csv";

                $tlog_obj = clone $tlog_vo;
                $tlog_obj->set_func_name($func);
                $batch_obj = $dao->get(array("remark" => $new_file));
                $success = 1;
                if (!empty($batch_obj)) {
                    $tlog_obj->set_message($new_file . " already_in_batch");
                    $tlog_dao->insert($tlog_obj);
                } else {
                    $rules[0] = array("not_empty");                         //settlement-id
                    $rules[1] = array("not_empty");                         //settlement-start-date
                    $rules[2] = array("not_empty");                         //settlement-end-date
                    $rules[3] = array("not_empty", "is_number");             //total-amount
                    $rules[4] = array("not_empty");                         //deposit-date
                    $rules[5] = array("not_empty");                         //transaction-type
                    $rules[6] = array("not_empty");                         //posted-date
                    $rules[7] = array("not_empty");                         //order-id
                    $rules[9] = array("not_empty");                         //order-item-id
                    $rules[10] = array("not_empty");                        //sku
                    $rules[11] = array("not_empty", "is_integer");           //quantity-purchased
                    $rules[12] = array("not_empty", "is_number");            //item-price-credit
                    $rules[24] = array("not_empty");                        //platform
                    $rules[25] = array("not_empty");                        //currency

                    //Order Found
                    $localfile = $localpath . $new_file;
                    //echo $localfile."\n";
                    $remotefile = $file;
                    //echo $remotefile;
                    $ftp->getfile($localfile, $remotefile);

                    if ($fp = @fopen($localfile, "r")) {
                        if ($line_arr = $this->_fba_report_xml_to_csv($localfile, $func)) {
                            foreach ($line_arr as $line) {
                                $data[] = implode(chr(9), $line);
                            }
                            $valid->set_rules($rules);
                            $success = 0;
                            $tlog_obj->set_message("file is empty");
                            foreach ($data as $line) {
                                $success = 1;
                                $line = trim($line);

                                $rs = FALSE;
                                if (!empty($line)) {
                                    $line_arr = @explode("\t", $line);
                                }

                                if ($line_arr[5] != "Order") {
                                    continue;
                                }

                                $valid->set_data($line_arr);
                                try {
                                    $rs = $valid->run();
                                } catch (Exception $e) {
                                    echo $e->getMessage();
                                    $tlog_obj->set_message($e->getMessage());
                                    //$tlog_dao->insert($tlog_obj);
                                    //$event->fire_event($func, $dto_obj);
                                }

                                if (!$rs) {
                                    echo "Error\n";
                                    $tlog_dao->insert($tlog_obj);
                                    $success = 0;
                                    break;
                                }
                            }
                        } else {
                            $success = 0;
                        }


                        @fclose($fp);
                        if ($success) {
                            $batch_obj = clone $batch_vo;
                            $batch_obj->set_func_name($func);
                            $batch_obj->set_status("N");
                            $batch_obj->set_listed(1);
                            $batch_obj->set_remark($new_file);
                            $dao->insert($batch_obj);
                        } else {
                            $all_file_success = 0;
                        }
                    }//if($fp = fopen())
                    else {
                        $tlog_obj->set_message($file . " not_found");
                        $tlog_dao->insert($tlog_obj);
                        //$event->fire_event($func, $dto_obj);
                    }
                }//if(!empty(batch_obj)
            }//foreach($thisfile as $file)

            if ($all_file_success) {
                //backing up file on server
                $result2 = $ftp->renamefile($path_info["dirname"], $remotepath . "done/" . $report_date);
            }

            $ftp->close();

        }//if(count($thisfile))
        else {
            //notification for order not found
            include_once APPPATH . "libraries/dto/event_email_dto.php";
            $dto = new Event_email_dto();

            $dto->set_event_id("notification");
            $dto->set_mail_to(array('steven@eservicesgroup.net'));
            $dto->set_mail_from('do_not_reply@valuebasket.com');
            $dto->set_tpl_id(strtolower($platform) . "_onf_notice");
            $dto->set_replace(array("date" => date("Y-m-d H:i:s")));
            $this->get_event()->fire_event($dto);
        }

        $this->batch_get_fba_order($platform);
    }

    protected function _fba_report_xml_to_csv($filename, $func)
    {
        $tlog_dao = $this->getDao('TransmissionLog');
        $tlog_vo = $tlog_dao->get();
        $tlog_obj = clone $tlog_vo;
        $tlog_obj->set_func_name($func);

        if (file_exists($filename)) {
            if ($xml = simplexml_load_file($filename)) {
                if ($report = $xml->Message->SettlementReport) {
                    if ($report->Order) {
                        foreach ($report->Order as $order_info) {
                            if ($order_info->Fulfillment) {
                                if ($order_info->Fulfillment->Item) {
                                    $data = array();
                                    foreach ($order_info->Fulfillment->Item as $item_info) {
                                        $data['settlement_id'] = (string)$report->SettlementData->AmazonSettlementID;
                                        $data['settlement_start_date'] = (string)$report->SettlementData->StartDate;
                                        $data['settlement_end_date'] = (string)$report->SettlementData->EndDate;
                                        $data['total_amount'] = (string)$report->SettlementData->TotalAmount;
                                        $data['deposit_date'] = (string)$report->SettlementData->DepositDate;
                                        $data['transaction_type'] = 'Order';
                                        $data['posted_date'] = (string)$order_info->Fulfillment->PostedDate;
                                        $data['order_id'] = (string)$order_info->AmazonOrderID;
                                        $data['merchant_order_id'] = (string)$order_info->MerchantOrderID;
                                        $data['order_item_id'] = (string)$item_info->AmazonOrderItemCode;
                                        $data['sku'] = (string)$item_info->SKU;
                                        $data['quantity_purchased'] = (string)$item_info->Quantity;
                                        foreach ($item_info->ItemPrice->Component as $price_component) {
                                            switch ($price_component->Type) {
                                                case 'Principal':
                                                    $data['item_price_credit'] = (float)$price_component->Amount;
                                                    $data['item_tax_credit'] = '';
                                                    break;
                                                case 'Shipping':
                                                    $data['shipping_price_credit'] = (float)$price_component->Amount;
                                                    $data['shipping_tax_credit'] = '';
                                                    break;
                                                case 'GiftWrap':
                                                    $data['gift_wrap_price_credit'] = (float)$price_component->Amount;
                                                    $data['gift_wrap_tax_credit'] = '';
                                                default:
                                            }
                                        }

                                        $data['order_related_fees'] = 0;
                                        foreach ($item_info->ItemFees->Fee as $fee_component) {
                                            $data['order_related_fees'] += (float)$fee_component->Amount;
                                        }
                                        $data['other_fees'] = '';
                                        $data['item_promotion_discount'] = '';
                                        $data['item_promotion_id'] = '';
                                        $data['shipping_promotion_discount'] = '';
                                        $data['shipping_promotion_id'] = '';

                                        switch ((string)$order_info->MarketplaceName) {
                                            case 'Amazon.com':
                                                $platform_id = 'AMUS';
                                                break;
                                            case 'Amazon.co.uk':
                                                $platform_id = 'AMUK';
                                                break;
                                            case 'Amazon.de':
                                                $platform_id = 'AMDE';
                                                break;
                                            case 'Amazon.fr':
                                                $platform_id = 'AMFR';
                                                break;
                                            default:
                                                $platform_id = 'AMUS';
                                        }
                                        $data['platform'] = $platform_id;
                                        $data['currency'] = (string)$report->SettlementData->TotalAmount->attributes()->currency;

                                        $line[] = array($data['settlement_id'], $data['settlement_start_date'], $data['settlement_end_date'], $data['total_amount'],
                                            $data['deposit_date'], $data['transaction_type'], $data['posted_date'], $data['order_id'], $data['merchant_order_id'], $data['order_item_id'],
                                            $data['sku'], $data['quantity_purchased'], $data['item_price_credit'], $data['item_tax_credit'], $data['shipping_price_credit'],
                                            $data['shipping_tax_credit'], $data['gift_wrap_price_credit'], $data['gift_wrap_tax_credit'], $data['order_related_fees'],
                                            $data['other_fees'], $data['item_promotion_discount'], $data['item_promotion_id'], $data['shipping_promotion_discount'],
                                            $data['shipping_promotion_id'], $data['platform'], $data['currency']
                                        );
                                    }
                                } else {
                                    $tlog_obj->set_message("Unable to convert " . basename($filename) . " from xml to csv. Unable to locate Item Element.");
                                    $tlog_dao->insert($tlog_obj);
                                    return false;
                                }
                            } else {
                                $tlog_obj->set_message("Unable to convert " . basename($filename) . " from xml to csv. Unable to locate Fulfillment Element.");
                                $tlog_dao->insert($tlog_obj);
                                return false;
                            }
                        }
                    } else {
                        $tlog_obj->set_message("Unable to convert " . basename($filename) . " from xml to csv. Unable to locate Order Element.");
                        $tlog_dao->insert($tlog_obj);
                        return false;
                    }
                } else {
                    $tlog_obj->set_message("Unable to convert " . basename($filename) . " from xml to csv. Unable to locate SettlementReport Element.");
                    $tlog_dao->insert($tlog_obj);
                    return false;
                }

                //success
                if (count($line) > 0) {
                    $new_file = str_replace('.xml', '.csv', $filename);
                    $fp = fopen($new_file, 'w');
                    foreach ($line as $row) {
                        fputcsv($fp, $row, chr(9));
                    }
                    fclose($fp);
                    return $line;
                } else {
                    return false;
                }
            } else {
                $tlog_obj->set_message("Unable to convert " . basename($filename) . " from xml to csv. Unable to load file.");
                $tlog_dao->insert($tlog_obj);
                return false;
            }
        } else {
            $tlog_obj->set_message("Unable to convert " . basename($filename) . " from xml to csv. File does not exist.");
            $tlog_dao->insert($tlog_obj);
            return false;
        }
    }

    public function batch_get_fba_order($platform = "AMUK")
    {
        echo "Entering Batch\n ";

        $func = strtolower($platform) . "_fba_order";
        $dao = $this->getDao('Batch');
        $valid = $this->get_valid();
        $dex = $this->get_dex();
        $local_path = "/var/data/valuebasket.com/orders/" . strtolower($platform) . "/";

        include_once(APPPATH . "libraries/dao/Interface_so_dao.php");
        $iso_dao = new Interface_so_dao();
        include_once(APPPATH . "libraries/dao/Interface_so_item_dao.php");
        $isoi_dao = new Interface_so_item_dao();
        include_once(APPPATH . "libraries/dao/Interface_so_item_detail_dao.php");
        $isoid_dao = new Interface_so_item_detail_dao();
        include_once(APPPATH . "libraries/dao/Interface_so_payment_status_dao.php");
        $isops_dao = new Interface_so_payment_status_dao();
        include_once(APPPATH . "libraries/dao/Interface_client_dao.php");
        $ic_dao = new Interface_client_dao();
        include_once(BASEPATH . "libraries/Encrypt.php");
        $encrypt = new CI_Encrypt();
        include_once(APPPATH . "libraries/dao/Client_dao.php");
        $client_dao = new Client_dao();
        include_once APPPATH . "libraries/service/Price_service.php";
        $price_svc = new Price_service();
        include_once APPPATH . "libraries/dao/So_dao.php";
        $so_dao = new So_dao();
        include_once APPPATH . "libraries/dao/Platform_biz_var_dao.php";
        $pbv_dao = new Platform_biz_var_dao();

        $iso_vo = $iso_dao->get();
        $isoi_vo = $isoi_dao->get();
        $isoid_vo = $isoid_dao->get();
        $isops_vo = $isops_dao->get();
        $ic_vo = $ic_dao->get();

        $client_vo = $client_dao->get();

        $objlist = $dao->get_list(array("func_name" => $func, "status" => "N"));
        if ($objlist) {
            include_once "product_service.php";
            $prod_svc = new Product_service();

            $prod_list = $prod_svc->get_pblist_array();

            $xcnt = 0;
            foreach ($objlist as $bobj) {
                $batch_err = 0;
                $success = 1;
                $err_msg = "";

                $batch_id = $bobj->get_id();
                $obj_csv = new Csv_to_xml($local_path . $bobj->get_remark(), APPPATH . 'data/fba_order_csv2xml.txt', TRUE, "\t", FALSE);

                $out_intobj = new Xml_to_xml();
                $output = $dex->convert($obj_csv, $out_intobj);

                $xml = simplexml_load_string($output, 'SimpleXMLElement', LIBXML_NOCDATA);

                ${"last_" . strtolower($platform) . "_orderid"} = "";
                $last_client_id = "";
                $last_so_no = "";
                $last_line_no = "";

                $dao->trans_start();
                foreach ($xml as $key => $xobj) {
                    $pbv_obj = $pbv_dao->get(array("selling_platform_id" => (string)$xobj->platform));
                    $platform_country_id = $pbv_obj->get_platform_country_id();
                    $platform_currency_id = $pbv_obj->get_platform_currency_id();

                    if ($xobj->transaction_type != "Order") {
                        continue;
                    }

                    // Purpose: Skip Fulfilled By Merchant Orders
                    // Notice: Settlement Report will include all orders include FBA AND FBM and there is no indicator to identify them from each other.
                    // Caution: The cronjob for getting Settlement Report must run at least some time after the report generated to allow all FBM orders to be processed first
                    //         This is to avoid FBM order being mistaken and processed as FBA orders
                    if (!$so_obj = $so_dao->get(array("platform_order_id" => $xobj->order_id))) {
                        $reason = "";
                        $order_amount = (float)$xobj->item_price_credit + (float)$xobj->item_tax_credit + (float)$xobj->shipping_price_credit
                            + (float)$xobj->shipping_tax_credit + (float)$xobj->gift_wrap_price_credit + (float)$xobj->gift_wrap_tax_credit;
                        $order_cost = (float)$xobj->order_related_fees + (float)$xobj->other_fees;

                        //client information
                        if (${"last_" . strtolower($platform) . "_orderid"} == $xobj->order_id) {
                            //update int_so
                            $soobj = $iso_dao->get(array("trans_id" => $last_so_no));
                            $soobj->set_amount($soobj->get_amount() + (float)$xobj->item_price_credit);
                            $soobj->set_cost($soobj->get_cost() + $order_cost);
                            $ret = $iso_dao->update($soobj);
                            if ($ret === FALSE) {
                                var_dump($iso_dao->db->_error_message());
                                $err_msg .= "LINE: " . __LINE__ . "\nREASON: " . $iso_dao->db->_error_message() . "\nSQL: " . $iso_dao->db->last_query() . "\n\n\n";
                                $batch_err = 1;
                                break;
                            }
                            //int_so_item
                            $last_line_no++;
                        } else {
                            //int_client
                            $cobj = clone $ic_vo;
                            $cobj->set_batch_id($batch_id);
                            $amazon_order_id = str_replace("-", "_", (string)$xobj->order_id);
                            $cobj->set_email("do_not_use_" . $amazon_order_id . "@" . strtolower((string)$xobj->platform) . ".com");
                            $cobj->set_password($encrypt->encode((string)$xobj->order_id . time()));
                            $cobj->set_forename((string)$xobj->order_id);
                            $cobj->set_address_1(" ");
                            $cobj->set_country_id($platform_country_id);
                            $cobj->set_del_name((string)$xobj->order_id);
                            $cobj->set_del_address_1(" ");
                            $cobj->set_del_country_id($platform_country_id);
                            $cobj->set_batch_status('N');
                            $ret = $ic_dao->insert($cobj);
                            if ($ret === FALSE) {
                                var_dump($ic_dao->db->_error_message());
                                $err_msg .= "LINE: " . __LINE__ . "\nREASON: " . $ic_dao->db->_error_message() . "\nSQL: " . $ic_dao->db->last_query() . "\n\n\n";
                                $batch_err = 1;
                                break;
                            }
                            $last_client_id = $ret->get_trans_id();

                            //int_so
                            $soobj = clone $iso_vo;
                            $soobj->set_batch_id($batch_id);
                            $soobj->set_platform_order_id((string)$xobj->order_id);
                            $soobj->set_platform_id((string)$xobj->platform);
                            $soobj->set_client_trans_id($last_client_id);
                            $soobj->set_biz_type('FBA');
                            $soobj->set_amount((float)$xobj->item_price_credit);
                            $soobj->set_cost($order_cost);
                            $soobj->set_vat_percent($pbv_obj->get_vat_percent());
                            $soobj->set_delivery_charge(0);
                            $soobj->set_delivery_type_id('STD');
                            $soobj->set_weight(1);
                            $soobj->set_currency_id($platform_currency_id);
                            $soobj->set_bill_name((string)$xobj->order_id);
                            $soobj->set_bill_country_id($platform_country_id);
                            $soobj->set_delivery_name((string)$xobj->order_id);
                            $soobj->set_delivery_country_id($platform_country_id);
                            $soobj->set_status(6);
                            $soobj->set_order_create_date(date("Y-m-d H:i:s", strtotime((string)$xobj->posted_date) - 60 * 60 * 24));
                            $soobj->set_dispatch_date(date("Y-m-d H:i:s", strtotime((string)$xobj->posted_date)));
                            $soobj->set_batch_status('N');
                            $ret2 = $iso_dao->insert($soobj);
                            if ($ret2 === FALSE) {
                                var_dump($iso_dao->db->_error_message());
                                $batch_err = 1;
                                break;
                            }
                            $last_so_no = $ret2->get_trans_id();

                            // int_so_payment_status
                            $sopsobj = clone $isops_vo;
                            $sopsobj->set_batch_id($batch_id);
                            $sopsobj->set_so_trans_id($last_so_no);
                            $sopsobj->set_payment_gateway_id("amazon");
                            $sopsobj->set_payment_status("S");
                            $sopsobj->set_batch_status('N');
                            $ret4 = $isops_dao->insert($sopsobj);
                            if ($ret4 === FALSE) {
                                var_dump($isops_dao->db->_error_message());
                                $err_msg .= "LINE: " . __LINE__ . "\nREASON: " . $isops_dao->db->_error_message() . "\nSQL: " . $isops_dao->db->last_query() . "\n\n\n";
                                $batch_err = 1;
                                break;
                            }

                            //int_so_item
                            $last_line_no = 1;
                        }

                        $soiobj = clone $isoi_vo;
                        $soiobj->set_batch_id($batch_id);
                        $soiobj->set_so_trans_id($last_so_no);
                        $soiobj->set_line_no($last_line_no);
                        $soiobj->set_prod_sku((string)$xobj->sku);
                        $soiobj->set_ext_item_cd((string)$xobj->order_item_id);
                        $soiobj->set_qty((string)$xobj->quantity_purchased);
                        $soiobj->set_unit_price((float)$xobj->item_price_credit / (int)$xobj->quantity_purchased);
                        $soiobj->set_vat_total(0);
                        $soiobj->set_amount((float)$xobj->item_price_credit);
                        $soiobj->set_status(1); // FBA orders are considered shipped
                        $soiobj->set_batch_status('N');
                        $ret3 = $isoi_dao->insert($soiobj);
                        if ($ret3 === FALSE) {
                            var_dump($isoi_dao->db->_error_message());
                            $batch_err = 1;
                            break;
                        }
                        ${"last_" . strtolower((string)$xobj->platform) . "_orderid"} = (string)$xobj->order_id;
                    }
                }
                $dao->trans_complete();
                if ($batch_err) {
                    $bobj->set_status("BE");
                    $dao->update($bobj);
                    continue;
                }
                $bobj->set_status("P");
                $dao->update($bobj);
                $client_list = $ic_dao->get_list(array("batch_id" => $batch_id), array("limit" => -1));

                $cnt = $rcnt = 0;
                foreach ($client_list as $cobj) {
                    $reason = 0;
                    ++$rcnt;
                    $thisrow = TRUE;
                    $rules["email"] = array("valid_email");
                    $rules["forename"] = array("not_empty");
                    $rules["del_name"] = array("not_empty");
                    $valid->set_data($cobj);
                    $valid->set_rules($rules);
                    $rs = FALSE;
                    try {
                        $rs = $valid->run();
                    } catch (Exception $e) {
                        $cobj->set_failed_reason($e->getMessage());
                        $reason .= $e->getMessage();
                    }
                    if ($rs) {
                        $cobj->set_batch_status("R");
                    } else {
                        $cobj->set_batch_status("F");
                        $thisrow = FALSE;
                        $err_msg .= "LINE: " . __LINE__ . "\nREASON: Validation Error at interface_client " . $reason . "\n\n\n";
                        $success = 0;
                    }

                    $ic_dao->update($cobj);

                    $iso_vo = $iso_dao->get(array("batch_id" => $batch_id, "client_trans_id" => $cobj->get_trans_id()));
                    $isops_vo = $isops_dao->get(array("batch_id" => $batch_id, "so_trans_id" => $iso_vo->get_trans_id()));
                    $isoi_list = $isoi_dao->get_list(array("batch_id" => $batch_id, "so_trans_id" => $iso_vo->get_trans_id()), array("limit" => -1));

                    $isoi_arr = array();
                    $isoid_arr = array();

                    $amazon_order_list = $so_dao->get_amazon_order_number((string)$xobj->platform);
                    //validate iso_vo
                    $isorules = array("platform_order_id" => array("not_empty", "not_exists_in=platform_order_id"),
                        "amount" => array("not_empty", "is_number", "min=0.01"),
                        "bill_name" => array("not_empty"),
                        "delivery_name" => array("not_empty")
                    );
                    $valid->set_not_exists_in(array("platform_order_id" => $amazon_order_list));
                    $valid->set_rules($isorules);
                    $valid->set_data($iso_vo);
                    $rs = FALSE;
                    try {
                        $rs = $valid->run();
                    } catch (Exception $e) {
                        $iso_vo->set_failed_reason($e->getMessage());
                        $reason .= $e->getMessage();
                    }
                    if ($rs) {
                        $iso_vo->set_batch_status("R");
                    } else {
                        $iso_vo->set_batch_status("F");
                        $thisrow = FALSE;
                        $err_msg .= "LINE: " . __LINE__ . "\nREASON: Validation Error at interface_so " . $reason . "\n\n\n";
                        $success = 0;
                    }

                    //validate isops_vo
                    $isopsrules = array("so_trans_id" => array("not_empty"),
                        "payment_gateway_id" => array("not_empty"),
                        "payment_status" => array("not_empty"),
                    );
                    $valid->set_rules($isopsrules);
                    $valid->set_data($isops_vo);
                    $rs = FALSE;
                    try {
                        $rs = $valid->run();
                    } catch (Exception $e) {
                        var_dump($e->getMessage());
                        $isops_vo->set_failed_reason($e->getMessage());
                        $reason .= $e->getMessage();
                    }

                    if ($rs && $thisrow) {
                        $isops_vo->set_batch_status("R");
                    } else {
                        $isops_vo->set_batch_status("F");
                        $thisrow = FALSE;
                        $err_msg .= "LINE: " . __LINE__ . "\nREASON: Validation Error at interface_so_payment_status " . $reason . "\n\n\n";
                        $success = 0;
                    }

                    //validate isoi_vo
                    $isoirules = array("so_trans_id" => array("not_empty"),
                        "line_no" => array("not_empty", "is_number"),
                        "prod_sku" => array("not_empty", "exists_in=sku"),
                        "qty" => array("not_empty", "is_number"),
                        "amount" => array("not_empty", "is_number"),
                        "unit_price" => array("not_empty", "is_number"),
                        "ext_item_cd" => array("not_empty")
                    );
                    $valid->set_exists_in(array("sku" => $prod_list));
                    $valid->set_rules($isoirules);

                    foreach ($isoi_list as $isoi_vo) {
                        $valid->set_data($isoi_vo);

                        $rs = FALSE;
                        try {
                            $rs = $valid->run();
                        } catch (Exception $e) {
                            $isoi_vo->set_failed_reason($e->getMessage());
                            $reason .= $e->getMessage();
                        }
                        unset($e);
                        if ($rs) {
                            $isoi_vo->set_batch_status('R');
                            $isoi_vo->set_failed_reason("");

                            $list = $price_svc->get_dao()->get_items_w_price(array("prod_sku" => $isoi_vo->get_prod_sku(), "platform_id" => (string)$xobj->platform));
                            if (count($list) > 1) {
                                foreach ($list as $item) {
                                    $isoid_obj = clone $isoid_vo;
                                    $isoid_obj->set_batch_id($batch_id);
                                    $isoid_obj->set_so_trans_id($isoi_vo->get_so_trans_id());
                                    $isoid_obj->set_line_no($isoi_vo->get_line_no());
                                    $isoid_obj->set_item_sku($item->get_item_sku());
                                    $isoid_obj->set_qty($isoi_vo->get_qty());
                                    $isoid_obj->set_outstanding_qty($isoi_vo->get_qty());
                                    $isoid_obj->set_unit_price($item->get_price());
                                    $isoid_obj->set_vat_total(0);
                                    $isoid_obj->set_amount($item->get_price() * $isoi_vo->get_qty());
                                    $isoid_obj->set_cost(0);
                                    $isoid_obj->set_status(1); // FBA orders are considered shipped
                                    $isoid_obj->set_batch_status('R');
                                    $isoid_arr[] = $isoid_obj;
                                }
                            } else {
                                $isoid_obj = clone $isoid_vo;
                                $isoid_obj->set_batch_id($batch_id);
                                $isoid_obj->set_so_trans_id($isoi_vo->get_so_trans_id());
                                $isoid_obj->set_line_no($isoi_vo->get_line_no());
                                $isoid_obj->set_item_sku($isoi_vo->get_prod_sku());
                                $isoid_obj->set_qty($isoi_vo->get_qty());
                                $isoid_obj->set_outstanding_qty($isoi_vo->get_qty());
                                $isoid_obj->set_unit_price($isoi_vo->get_unit_price());
                                $isoid_obj->set_vat_total(0);
                                $isoid_obj->set_amount($isoi_vo->get_unit_price() * $isoi_vo->get_qty());
                                $isoid_obj->set_cost(0);
                                $isoid_obj->set_status(1); // FBA orders are considered shipped
                                $isoid_obj->set_batch_status('R');
                                $isoid_arr[] = $isoid_obj;
                            }
                        } else {
                            $isoi_vo->set_batch_status("F");
                            $thisrow = FALSE;
                            $success = 0;
                        }

                        $isoi_arr[] = $isoi_vo;
                    }
                    if (!$thisrow) {
                        $iso_vo->set_batch_status('F');
                        $iso_vo->set_failed_reason($iso_vo->get_failed_reason() . "," . $reason);
                    }

                    $dao->trans_start();
                    $iso_dao->update($iso_vo);

                    foreach ($isoi_arr as $obj) {
                        $isoi_dao->update($obj);

                    }
                    if (count($isoid_arr)) {
                        foreach ($isoid_arr as $obj) {

                            if (!$isoid_dao->insert($obj)) {
                                var_dump($isoid_dao->db->_error_message());
                                $err_msg .= "LINE: " . __LINE__ . "\nREASON: " . $isoid_dao->db->_error_message() . "\nSQL: " . $isoid_dao->db->last_query() . "\n\n\n";
                                $batch_err = 1;
                                $success = 0;
                            }
                        }
                    }
                    $dao->trans_complete();
                    $reason = "";

                    if ($thisrow) {
                        $cnt++;
                    }
                }

                if ($cnt == $rcnt && $success) {
                    $bobj->set_status("C");
                    $bobj->set_end_time(date("Y:m:d H:i:s"));
                    $dao->update($bobj);
                } else {
                    $bobj->set_status("CE");
                    $bobj->set_end_time(date("Y-m-d H:i:s"));
                    $dao->update($bobj);
                }

                $this->proceed_fba_batch($batch_id, (string)$xobj->platform);

                if (!$success || $batch_err) {
                    mail("steven@eservicesgroup.net", "[SE] Error Occurs when Batching FBA Orders", $err_msg, "From: Admin <itsupport@eservicesgroup.net>\r\n");
                }
            }
        }
    }

    public function proceed_fba_batch($batch_id, $platform = "AMUK")
    {
        echo "Proceed";

        include_once APPPATH . "libraries/dao/Interface_client_dao.php";
        include_once APPPATH . "libraries/dao/Interface_so_dao.php";
        include_once APPPATH . "libraries/dao/Interface_so_item_dao.php";
        include_once APPPATH . "libraries/dao/Interface_so_item_detail_dao.php";
        include_once APPPATH . "libraries/dao/Interface_so_payment_status_dao.php";
        include_once APPPATH . "libraries/dao/Client_dao.php";
        include_once APPPATH . "libraries/dao/So_dao.php";
        include_once APPPATH . "libraries/dao/So_item_dao.php";
        include_once APPPATH . "libraries/dao/So_item_detail_dao.php";
        include_once APPPATH . "libraries/dao/Platform_biz_var_dao.php";
        include_once APPPATH . "libraries/dao/Exchange_rate_dao.php";
        include_once APPPATH . "libraries/dao/So_payment_status_dao.php";
        include_once(BASEPATH . "libraries/Encrypt.php");
        include_once APPPATH . "libraries/dao/Price_dao.php";
        include_once APPPATH . "libraries/dao/Price_extend_dao.php";
        include_once APPPATH . "libraries/dao/Product_dao.php";
        include_once APPPATH . "libraries/service/Shiptype_service.php";
        include_once APPPATH . "libraries/service/Price_amazon_service.php";

        $encrypt = new CI_Encrypt();

        include_once(APPPATH . "libraries/dto/event_email_dto.php");
        $email_dto = new Event_email_dto();

        $ic_dao = new Interface_client_dao();
        $iso_dao = new Interface_so_dao();
        $isoi_dao = new Interface_so_item_dao();
        $isoid_dao = new Interface_so_item_detail_dao();
        $isops_dao = new Interface_so_payment_status_dao();
        $c_dao = new Client_dao();
        $so_dao = new So_dao();
        $soi_dao = new So_item_dao();
        $soid_dao = new So_item_detail_dao();
        $er_dao = new Exchange_rate_dao();
        $pbv_dao = new Platform_biz_var_dao();
        $prc_dao = new Price_dao();
        $prext_dao = new Price_extend_dao();
        $prod_dao = new Product_dao();
        $sops_dao = new So_payment_status_dao();
        $shiptype_svc = new Shiptype_service();
        $plat_prc_svc = new Price_amazon_service();
        $plat_prc_dto = $plat_prc_svc->get_dto();

        $func_curr = $this->getDao('Config')->valueOf("func_curr_id");
        $pbv_obj = $pbv_dao->get(array("selling_platform_id" => $platform));
        $er_obj = $er_dao->get(array("from_currency_id" => $pbv_obj->get_platform_currency_id(), "to_currency_id" => $func_curr));
        $er_ref_obj = $er_dao->get(array("from_currency_id" => $pbv_obj->get_platform_currency_id(), "to_currency_id" => "EUR"));

        $amazon_order_list = $so_dao->get_amazon_order_number($platform);
        $order_list = $iso_dao->get_list(array("batch_id" => $batch_id, "batch_status" => "R"), array("limit" => -1));
        $seq = 0;
        $stock_alert = array();

        if (!empty($order_list)) {
            foreach ($order_list as $iso_obj) {
                $soi_array = array();
                $isoi_array = array();
                $soid_array = array();
                $isoid_array = array();
                $canupdate = 1;
                $err_msg = "";

                $isoi_list = $isoi_dao->get_list(array("batch_id" => $batch_id, "so_trans_id" => $iso_obj->get_trans_id()), array("limit" => -1));

                //update client
                $ic_obj = $ic_dao->get(array("trans_id" => $iso_obj->get_client_trans_id()));
                if ($ic_obj->get_batch_status() == 'R') {
                    $eclient = $c_dao->get(array("email" => $ic_obj->get_email()));
                    if ($eclient) {
                        $action = "update";
                        //$eclient = $c_dao->get(array("id"=>$ic_obj->get_id()));
                    } else {
                        $action = "insert";
                        $eclient = $c_dao->get();
                        $eclient->set_email($ic_obj->get_email());
                        $eclient->set_password($ic_obj->get_password());
                        $eclient->set_forename($ic_obj->get_forename());
                        $eclient->set_country_id($ic_obj->get_country_id());
                        $seq++;
                    }
                    $eclient->set_del_name($ic_obj->get_del_name());
                    $eclient->set_del_country_id($ic_obj->get_del_country_id());

                    $c_dao->trans_start();
                    $c_dao->$action($eclient);
                    $ic_obj->set_batch_status('S');
                    $ic_obj->set_id($eclient->get_id());
                    $ic_dao->update($ic_obj);
                    $c_dao->trans_complete();
                }//if($ic_obj->get_batch_status() == 'R')

                $pass = 1;

                $soobj = $so_dao->get();
                $soiobj = $soi_dao->get();
                $soidobj = $soid_dao->get();

                $soobj->set_client_id($ic_obj->get_id());
                $soobj->set_platform_order_id($iso_obj->get_platform_order_id());
                $soobj->set_platform_id($platform);
                $soobj->set_delivery_type_id($pbv_obj->get_delivery_type());
                $soobj->set_biz_type($iso_obj->get_biz_type());
                $soobj->set_amount($iso_obj->get_amount());
                $soobj->set_delivery_charge($iso_obj->get_delivery_charge());
                $soobj->set_weight($iso_obj->get_weight());
                $soobj->set_currency_id($iso_obj->get_currency_id());
                $soobj->set_bill_name($iso_obj->get_bill_name());
                $soobj->set_bill_company($iso_obj->get_bill_company());
                $soobj->set_bill_address($iso_obj->get_bill_address());
                $soobj->set_bill_postcode($iso_obj->get_bill_postcode());
                $soobj->set_bill_city($iso_obj->get_bill_city());
                $soobj->set_bill_state($iso_obj->get_bill_state());
                $soobj->set_bill_country_id($iso_obj->get_bill_country_id());
                $soobj->set_delivery_name($iso_obj->get_delivery_name());
                $soobj->set_delivery_company($iso_obj->get_delivery_company());
                $soobj->set_delivery_address($iso_obj->get_delivery_address());
                $soobj->set_delivery_postcode($iso_obj->get_delivery_postcode());
                $soobj->set_delivery_city($iso_obj->get_delivery_city());
                $soobj->set_delivery_state($iso_obj->get_delivery_state());
                $soobj->set_delivery_country_id($iso_obj->get_delivery_country_id());
                $soobj->set_order_create_date($iso_obj->get_order_create_date());
                $soobj->set_dispatch_date($iso_obj->get_dispatch_date());
                $soobj->set_status(6);
                $soobj->set_cost(0);
                $soobj->set_hold_status(0);
                $soobj->set_refund_status(0);
                $soobj->set_rate($er_obj->get_rate());
                $soobj->set_ref_1($er_ref_obj->get_rate());
                $soobj->set_expect_delivery_date(null);
                $soobj->set_vat_percent($pbv_obj->get_vat_percent());

                $isops_obj = $isops_dao->get(array("batch_id" => $batch_id, "so_trans_id" => $iso_obj->get_trans_id()));
                $sops_obj = $sops_dao->get();
                $sops_obj->set_payment_gateway_id($isops_obj->get_payment_gateway_id());
                $sops_obj->set_payment_status($isops_obj->get_payment_status());

                $iso_obj->set_batch_status('S');
                //$soobj, $iso_obj $sops_obj ready
                $total_cost = 0;
                $total_weight = 0;

                foreach ($isoi_list as $isoi_obj) {
                    if ($isoi_obj->get_batch_status() != 'R') {
                        $canupdate = 0;
                        break;
                    } else {
                        $vat_total = 0;
                        $tmp = clone $soiobj;
                        $tmp = $soi_dao->get();
                        $tmp->set_line_no($isoi_obj->get_line_no());
                        $tmp->set_prod_sku($isoi_obj->get_prod_sku());
                        $pobj = $prod_dao->get(array("sku" => $isoi_obj->get_prod_sku()));
                        $tmp->set_prod_name($pobj->get_name());
                        $tmp->set_ext_item_cd($isoi_obj->get_ext_item_cd());
                        $tmp->set_qty($isoi_obj->get_qty());
                        $tmp->set_unit_price($isoi_obj->get_unit_price());
                        $tmp->set_amount($isoi_obj->get_amount());
                        $tmp->set_status($isoi_obj->get_status());

                        $st_obj = $shiptype_svc->get(array("name" => "FBA"));
                        $tdto = $prc_dao->get_price_cost_dto($isoi_obj->get_prod_sku(), $platform, $st_obj->get_id());
                        if ($prext_obj = $prext_dao->get(array("sku" => $isoi_obj->get_prod_sku(), "platform_id" => $platform))) {
                            $tdto->set_fulfillment_centre_id($prext_obj->get_fulfillment_centre_id());
                        }
                        $tdto->set_price($tmp->get_unit_price());
                        $plat_prc_svc->set_dto($tdto);
                        $plat_prc_svc->calc_profit();

                        $total_cost += $plat_prc_svc->get_dto()->get_cost() * $isoi_obj->get_qty();

                        $total_weight += $plat_prc_svc->get_dto()->get_prod_weight() * $isoi_obj->get_qty();
                        $tmp->set_vat_total($plat_prc_svc->get_dto()->get_vat() * $isoi_obj->get_qty());

                        $soi_array[$isoi_obj->get_trans_id()] = $tmp;

                        $isoi_obj->set_batch_status('S');
                        $isoi_array[$isoi_obj->get_trans_id()] = $isoi_obj;

                        $isoid_list = $isoid_dao->get_list(array("batch_id" => $batch_id, "so_trans_id" => $isoi_obj->get_so_trans_id(), "line_no" => $isoi_obj->get_line_no()), array("limit" => -1));
                        foreach ($isoid_list as $isoid_obj) {
                            if ($isoid_obj->get_batch_status() != 'R') {
                                $canupdate = 0;
                                break;
                            }
                            $tmp2 = clone $soidobj;
                            $tmp2->set_line_no($isoid_obj->get_line_no());
                            $tmp2->set_item_sku($isoid_obj->get_item_sku());
                            $tmp2->set_qty($isoid_obj->get_qty());
                            $tmp2->set_outstanding_qty($isoid_obj->get_outstanding_qty());
                            $tmp2->set_discount($isoid_obj->get_discount());
                            $tmp2->set_amount($isoid_obj->get_amount());
                            $tmp2->set_promo_disc_amt(0);
                            $tmp2->set_unit_price($isoid_obj->get_unit_price());
                            $tmp2->set_cost($isoid_obj->get_cost());
                            $tmp2->set_status($isoid_obj->get_status());

                            $tdto2 = $prc_dao->get_price_cost_dto($isoi_obj->get_prod_sku(), $platform, $st_obj->get_id());
                            if ($prext_obj = $prext_dao->get(array("sku" => $isoi_obj->get_prod_sku(), "platform_id" => $platform))) {
                                $tdto2->set_fulfillment_centre_id($prext_obj->get_fulfillment_centre_id());
                            }
                            $tdto2->set_price($tmp2->get_unit_price());
                            $plat_prc_svc->set_dto($tdto2);
                            $plat_prc_svc->calc_profit();

                            $tmp2->set_vat_total($plat_prc_svc->get_dto()->get_vat() * $isoid_obj->get_qty());
                            $tmp2->set_cost($plat_prc_svc->get_dto()->get_cost() * $isoid_obj->get_qty());
                            $tmp2->set_profit($isoid_obj->get_amount() - $plat_prc_svc->get_dto()->get_cost() * $isoid_obj->get_qty());
                            $tmp2->set_margin(round(($isoid_obj->get_amount() - $plat_prc_svc->get_dto()->get_cost() * $isoid_obj->get_qty()) / $isoid_obj->get_amount() * 100, 2));

                            $soid_array[$isoi_obj->get_trans_id()][$isoid_obj->get_trans_id()] = $tmp2;

                            $isoid_obj->set_batch_status('S');

                            $isoid_array[$isoid_obj->get_trans_id()] = $isoid_obj;
                        }//foreach($isoid_list as $isoid_obj)
                        //$soid_obj, $isoi_obj list ready
                    }//if($isoi_obj->get_batch_status() != 'R')
                }//foreach($isoi_list as $isoi_obj)
                //$isoi_obj, $soi_obj lists ready
                $soobj->set_cost($total_cost);
                $soobj->set_weight($total_weight);

                if ($canupdate) {
                    $complete = 1;
                    $c_dao->trans_start();
                    //insert so
                    $seq = $so_dao->seq_next_val();
                    $soobj->set_so_no("SO" . sprintf("%06d", $seq));
                    //var_dump(__LINE__."\n"."So Obj: ".$soobj."\n");
                    $so_dao->update_seq($seq);
                    $ret = $so_dao->insert($soobj);
                    //echo __LINE__."\n".$so_dao->db->last_query()."\n".$so_dao->db->_error_message()."\n";
                    if ($ret !== FALSE) {
                        $iso_obj->set_so_no($ret->get_so_no());
                        $iso_obj->set_batch_status('S');
                        $iso_dao->update($iso_obj);
                        //echo __LINE__."\n".$iso_dao->db->last_query()."\n".$iso_dao->db->_error_message()."\n";
                        //echo "SO Number: ".$ret->get_so_no()."\n";
                        //echo "so update success\n";

                        $sops_obj->set_so_no($ret->get_so_no());
                        if ($sops_dao->insert($sops_obj)) {
                            $isops_obj->set_batch_status('S');
                            $isops_dao->update($isops_obj);
                        } else {
                            $err_msg .= $sops_dao->db->last_query() . "\n" . $sops_dao->db->_error_message() . "\n\n";
                            $complete = 0;
                        }

                        //insert so_item
                        foreach ($soi_array as $key => $obj) {
                            $obj->set_so_no($soobj->get_so_no());
                            $ret2 = $soi_dao->insert($obj);
                            //echo __LINE__."\n".$soi_dao->db->last_query()."\n".$soi_dao->db->_error_message()."\n";
                            if ($ret2 !== FALSE) {
                                $isoiobj = $isoi_array[$key];
                                if ($isoiobj !== FALSE) {
                                    $isoiobj->set_so_no($ret2->get_so_no());
                                    $isoiobj->set_batch_status('S');
                                    $isoi_dao->update($isoiobj);
                                    echo $isoi_dao->db->_error_message();
                                    //echo "soi update success\n";
                                    //insert so_item_detail
                                    foreach ($soid_array[$key] as $key2 => $soidobj) {
                                        $soidobj->set_so_no($ret2->get_so_no());
                                        $ret3 = $soid_dao->insert($soidobj);
                                        echo $soid_dao->db->_error_message();
                                        if ($ret3 !== FALSE) {
                                            $isoidobj = $isoid_array[$key2];
                                            if ($isoidobj !== FALSE) {
                                                $isoidobj->set_so_no($ret3->get_so_no());
                                                $isoidobj->set_batch_status('S');
                                                $isoid_dao->update($isoidobj);
                                                //echo $isoid_dao->db->_error_message();
                                                //echo "soid update success\n";
                                            }
                                        } else {
                                            $err_msg .= $soid_dao->db->last_query() . "\n" . $soid_dao->db->_error_message() . "\n\n";
                                            $complete = 0;
                                        }
                                    }
                                } else {
                                    $err_msg .= "Line " . __LINE__ . " Error\n\n";
                                    $complete = 0;
                                }
                            } else {
                                $err_msg .= $soi_dao->db->last_query() . "\n" . $soi_dao->db->_error_message() . "\n\n";
                                $complete = 0;
                            }
                        }
                    } else {
                        $err_msg .= $so_dao->db->last_query() . "\n" . $so_dao->db->_error_message() . "\n\n";
                        $complete = 0;
                    }
                    $c_dao->trans_complete();
                    if ($complete) {
                        //email notification to customer;
                        //update the status of order to not_ack
                        //$event->fire_event($func, $dto);
                        $to_addr = array("AMUK" => "amazoncentral@valuebasket.com", "AMFR" => "sandrine@valuebasket.com");
                        $dto = clone $email_dto;
                        $dto->set_event_id("notification");
                        $dto->set_mail_to($ic_obj->get_email());
                        $dto->set_mail_from($to_addr[$platform]);
                        $dto->set_tpl_id(strtolower($platform) . "_order_confirm");
                        $dto->set_replace(array("buyername" => $soobj->get_delivery_name(), "amazon_order_no" => $soobj->get_platform_order_id()));
                        //$this->get_event()->fire_event($dto);
                    } else {
                        //email notification to IT for order creation error
                        //$event->fire_event($func, $dto);
                        $bobj = $this->getDao('Batch')->get(array("id" => $batch_id));
                        $bobj->set_status("CE");
                        $bobj->set_end_time(date("Y-m-d H:i:s"));
                        $this->getDao('Batch')->update($bobj);

                        $dto = clone $email_dto;
                        $dto->set_event_id("notification");
                        $dto->set_mail_to(array("steven@eservicesgroup.net"));
                        $dto->set_mail_from("do_not_reply@valuebasket.com");
                        $dto->set_tpl_id("general_alert");
                        $dto->set_replace(array("title" => "Order Insert Failed for $platform", "message" => "$platform Order " . $iso_obj->get_platform_order_id() . " was not added into the database because of a query error. Please investigate the case ASAP.\n" . $err_msg));
                        $this->get_event()->fire_event($dto);
                    }
                }//if($canupdate)
            }//foreach($order_list as $iso_obj)

            if (count($stock_alert)) {
                foreach ($stock_alert as $key => $value) {
                    $message .= "Please be advised that Amazon order number " . $key . " has triggered the following product(s) to possibly be out of stock:<br><br>";
                    foreach ($value as $k => $c) {
                        $message .= $c["sku"] . " - " . $c["name"] . "<br>";
                    }
                    $message .= "<br><br>";
                }

                $message = ereg_replace("<br><br>$", "", $message);
                $title = "FBA Order Import Warning - " . $platform;
                $dto = clone $email_dto;
                $dto->set_event_id("notification");
                $dto->set_mail_to("steven@eservicesgroup.net");
                $dto->set_mail_from("do_not_reply@valuebasket.com");
                $dto->set_tpl_id("general_alert");
                $dto->set_replace(array("title" => $title, "message" => $message));
                $this->get_event()->fire_event($dto);
            }
        }//if(!empty($order_list))

        return TRUE;
    }

    public function check_and_update_batch_status($batch_id = "")
    {
        if ($batch_id == "") {
            return FALSE;
        }

        return $this->getDao('Batch')->getBatchStatusForOrder($batch_id);
    }

    public function gen_prod_feeds()
    {

        include_once(BASEPATH . "libraries/Encrypt.php");
        $encrypt = new CI_Encrypt();
        $ftp = $this->ftpConnector;

        $feeders[] = "YAHOO";
        $feeders[] = "LINKSHARE";
        $feeders[] = "FROOGLE";
        $feeders[] = "PRICERUNNER";
        $feeders[] = "KELKOO";
//      $feeders[] = "PRICEMINISTER";
        $feeders[] = "PRICEGRABBER";
        $feeders[] = "TRADEDOUBLER";
        $feeders[] = "REEVOO";

        $delimiter[0] = array("YAHOO" => "\t", "LINKSHARE" => "|", "FROOGLE" => "\t", "PRICERUNNER" => ",", "KELKOO" => "\t", "PRICEMINISTER" => ",", "PRICEGRABBER" => "\t", "TRADEDOUBLER" => "|", "REEVOO" => "\t");
        $delimiter[1] = array("REEVOO" => ",");

        $stock_status = array("YAHOO" => "In Stock", "LINKSHARE" => "IN", "FROOGLE" => "In Stock", "PRICERUNNER" => "new", "KELKOO" => "In Stock", "PRICEGRABBER" => "New", "TRADEDOUBLER" => "In Stock");
        $wysiwyg = array("YAHOO" => 16, "LINKSHARE" => 13, "FROOGLE" => 10, "PRICERUNNER" => "2", "KELKOO" => "7", "PRICEGRABBER" => "3", "TRADEDOUBLER" => "9", "REEVOO" => "22");
        $quantity = array("LINKSHARE" => "0", "FROOGLE" => 100, "PRICERUNNER" => "yes", "PRICEGRABBER" => "Yes", "REEVOO" => "Yes");
        $inventory = array("PRICERUNNER" => "1");
        $delivery_time = array("LINKSHARE" => "delivers in 2-5 working days");
        $extra = array("LINKSHARE" => "amount");

        $pre_date = date("Ymd");
        $pre_ts = date("Y-m-d/H:i:s");
        $out_filename[0] = array("YAHOO" => "YahooFeed_{$pre_date}.txt",
            "LINKSHARE" => "24591_nmerchandis_{$pre_date}.txt",
            "FROOGLE" => "PGFroogleFeed.txt",
            "PRICERUNNER" => "PriceRunnerPGFeed.csv",
            "KELKOO" => "Kelkoo valuebasket feed.txt",
            "PRICEMINISTER" => "PriceMinisterProductFeed.csv",
            "PRICEGRABBER" => "CI_valuebasketUK_PriceGrabber_UK.txt",
            "TRADEDOUBLER" => "valuebasketTD.csv",
            "REEVOO" => "RVFeed_{$pre_date}.txt",
        );
        $out_filename[1] = array(
            /*"LINKSHARE"=>"24591_nmerchandis_{$pre_date}.txt",*/
            "REEVOO" => "RVPRICINGFeed.txt",
        );

        $f_header[0] = array("LINKSHARE" => "HDR|24591|valuebasket Limited|" . $pre_ts . "\r\n",
            "FROOGLE" => "title\tdescription\tlink\timage_link\tid\tprice\tcurrency\tpayment_accepted\tpayment_notes\tquantity\tbrand\tcondition\tean\tmpn\tproduct_type\r\n",
            "PRICERUNNER" => "Category,SKU,Price,Product URL,Product name,Product state,Manufacturer SKU,Manufacturer,In Stock,Stock Level,Delivery time,Shipping Cost,Description,Graphic URL,ISBN,EAN or UPC,Catalog Id,Retailer Message,PriceRunner Code\r\n",
            "KELKOO" => "Category\tType\tFieldC\tFieldD\tFieldE\tFieldF\tFieldG\tFieldH\tFieldI\tFieldJ\tFieldK\tSKU\tDescription\tPromotion\tImage\tLinkToProduct\tPrice\tDeliveryCost\tDeliveryTime\tAvailability\tWarranty\tCondition\tOfferType\n",
            "PRICEMINISTER" => "EAN,MPN,Manufacturer,Price,Qty,Condition,CommentaryEN\r\n",
            "PRICEGRABBER" => "Product Name\tManufacturer Name\tManufacturer Part Number\tCategorization\tDetailed Description\tSelling Price\tAvailability\tReferring Product URL\tImage URL\tShipping Cost\tProduct Condition\tEAN\n",
            "REEVOO" => "ean\tmanufacturer\tmodel\tsku\tname\timage_url\tproduct_category\tdescription\n",
        );
        $f_header[1] = array(
            "REEVOO" => "id,ean,brand,model,name,sku,productURL,imageURL,price,description,category name,inStock,shippingCost,deliveryTime\n",
        );

        include_once(APPPATH . "libraries/service/Wsgb_price_service.php");
        $price_srv = new Wsgb_price_service();
        include_once(APPPATH . "libraries/service/Category_service.php");
        $cat_srv = new Category_service();
        include_once(APPPATH . "libraries/service/Brand_service.php");
        $brand_srv = new Brand_service();
        include_once(BASEPATH . "helpers/url_helper.php");
        include_once(APPPATH . "helpers/object_helper.php");
        $where["price >"] = 0;
        $where["price <"] = 8000;
        $where["website_quantity >"] = 0;
        $where['prod_status !='] = '0';
//      $where["website_status !="] = "O";
//      $where["sourcing_status"] = "A";
//      $where["inventory >"] = 0;
        $option["orderby"] = "prod_name";
        $option["limit"] = -1;
        $option["desc_lang"] = "en";
//      $option["inventory"] = TRUE;
        $option["product_feed"] = TRUE;
        $cat_list = $cat_srv->get_name_list_w_id_key(array(), array("limit" => -1));
        $brand_list = $brand_srv->get_name_list_w_id_key(array(), array("limit" => -1));

        $cat_srv->include_dto("Product_feed_dto");
        $pf_dto = new Product_feed_dto();
        if ($prod_list = $price_srv->get_product_overview($where, $option)) {
            foreach ($feeders as $feeder) {
                $sl_feeder = strtolower($feeder);
                $rs_dir = "/var/data/valuebasket.com/feed/";
                if (!is_dir($rs_dir)) {
                    mkdir($rs_dir, 0775) or die("can't create dir: " . __LINE__);;
                }
                $path = "/var/data/valuebasket.com/feed/" . $sl_feeder;
                if (!is_dir($path)) {
                    mkdir($path, 0775) or die("can't create dir: " . __LINE__);;
                }
                foreach ($out_filename as $f_key => $out_file) {
                    if ($rs_file = $out_file[$feeder]) {
                        if ($fp = fopen($path . "/" . $rs_file, 'w')) {
                            if ($rs_header = $f_header[$f_key][$feeder]) {
                                fwrite($fp, $rs_header);
                            }

                            $total_p = $total = 0;
                            foreach ($prod_list as $obj) {
                                $feeds = @explode("||", $obj->get_feeds());
                                $ar_status = array("YAHOO" => 1, "FROOGLE" => 1, "LINKSHARE" => 1, "TRADEDOUBLER" => 1, "REEVOO" => 1);
                                $value_3 = $value_2 = $value_1 = "";
                                if (!$ar_status[$feeder]) {
                                    foreach ($feeds as $feed) {
                                        $ar_feed = @explode("::", $feed);
                                        if ($ar_feed[0] != "") {
                                            $ar_status[$ar_feed[0]] = $ar_feed[4];
                                        }
                                        if ($ar_feed[0] == $feeder) {
                                            $value_1 = $ar_feed[1];
                                            $value_2 = $ar_feed[2];
                                            $value_3 = $ar_feed[3];
                                        }
                                    }
                                    if (!$ar_status[$feeder]) {
                                        continue;
                                    }
                                }
                                $new_obj = clone $pf_dto;
                                set_value($new_obj, $obj);
                                $new_obj->set_detail_desc($this->trim_proddesc($feeder, $new_obj->get_detail_desc()));
                                $new_obj->set_cat_name($cat_list[$new_obj->get_cat_id()]);
                                $new_obj->set_sub_cat_name($cat_list[$new_obj->get_sub_cat_id()]);
                                if ($feeder == "REEVOO") {
                                    $new_name = $this->reevoo_massage($new_obj->get_prod_name(), $brand_list[$new_obj->get_brand_id()]);
                                    $new_obj->set_model($new_name["model"]);
                                    $new_obj->set_brand_name($new_name["brand_name"]);
                                    $new_obj->set_prod_name($new_name["prod_name"]);
                                    $new_obj->set_cat_name($cat_list[$new_obj->get_cat_id()] . " > " . $cat_list[$new_obj->get_sub_cat_id()] . " > " . $cat_list[$new_obj->get_sub_sub_cat_id()]);
                                } else {
                                    $new_obj->set_brand_name($brand_list[$new_obj->get_brand_id()]);
                                }
                                if ($feeder == "LINKSHARE") {
                                    $new_obj->set_content_prod_name($new_obj->get_prod_name());
                                } else {
                                    $new_obj->set_content_prod_name($new_obj->get_prod_name());
                                }
                                $new_obj->set_url($this->getDao('Config')->valueOf("default_url") . "/" . str_replace(' ', '-', parse_url_char($new_obj->get_prod_name())) . "/mainproduct/view/" . $new_obj->get_sku() . "/" . $wysiwyg[$feeder]);
                                $new_obj->set_payment_accepted("Cash,Visa,MasterCard,WireTransfer");
                                $new_obj->set_payment_notes("Google Checkout Accepted");
                                $new_obj->set_quantity($quantity[$feeder]);
                                $new_obj->set_value_1($value_1);
                                $new_obj->set_value_2($value_2);
                                $new_obj->set_value_3($value_3);
                                if ($inventory[$feeder]) {
                                    $new_obj->set_inventory($inventory[$feeder]);
                                }
                                if ($delivery_time[$feeder]) {
                                    $new_obj->set_delivery_time($delivery_time[$feeder]);
                                }
                                if ($condition[$feeder]) {
                                    $new_obj->set_condition($condition[$feeder]);
                                }
                                if ($extra[$feeder]) {
                                    $new_obj->set_extra($extra[$feeder]);
                                }
                                if ($new_obj->get_image()) {
                                    $new_obj->set_image($this->getDao('Config')->valueOf("default_url") . "/images/product/" . $new_obj->get_sku() . "." . $new_obj->get_image());
                                }
                                $new_obj->set_website_status($stock_status[$feeder]);
                                $price_srv->set_dto($new_obj);
                                $price_srv->calc_profit();
                                $dex = $this->get_dex();
                                $mapfile = $f_key ? APPPATH . 'data/prod_feed_' . $sl_feeder . '_' . $f_key . '_vo2xml.txt' : APPPATH . 'data/prod_feed_' . $sl_feeder . '_vo2xml.txt';
                                $mapfile2 = $f_key ? APPPATH . 'data/prod_feed_' . $sl_feeder . '_' . $f_key . '_xml2csv.txt' : APPPATH . 'data/prod_feed_' . $sl_feeder . '_xml2csv.txt';
                                $out_xml = new Vo_to_xml($new_obj, is_file($mapfile) ? $mapfile : '');
                                $out_csv = new Xml_to_csv("", is_file($mapfile2) ? $mapfile2 : '', FALSE, $delimiter[$f_key][$feeder]);
                                $output = $dex->convert($out_xml, $out_csv);
                                fwrite($fp, $output);
                                $total++;
                            }

                            $f_footer[0] = array("LINKSHARE" => "TRL|" . $total . "\n");
                            $f_footer[1] = array(/*"LINKSHARE"=>"TRL|".$total_p."\n"*/);

                            if ($rs_footer = $f_footer[$f_key][$feeder]) {
                                fwrite($fp, $rs_footer);
                            }
                            fclose($fp);
                        } else {
                            echo "can't open file: " . $path . "/" . $rs_file . "\n";
                        }
                    }
                }

                if ($ftp_obj = $this->getDao('FtpInfo')->get(array("name" => $feeder))) {
                    $ftp->set_remote_site($server = $ftp_obj->get_server());
                    $ftp->set_username($ftp_obj->get_username());
                    $ftp->set_password($encrypt->decode($ftp_obj->get_password()));
                    $ftp->set_port($ftp_obj->get_port());
                    $ftp->set_is_passive($ftp_obj->get_pasv());

                    if ($ftp->connect() !== FALSE) {
                        if ($ftp->login() !== FALSE) {
                            foreach ($out_filename as $f_key => $out_file) {
                                if ($rs_file = $out_file[$feeder]) {
                                    $ftp->putfile($path . "/" . $rs_file, $rs_file);
                                }
                            }
                        }
                        $ftp->close();
                    }
                }

            }
        }
    }

    function trim_proddesc($feeder, $proddesc)
    {
        include_once(APPPATH . "helpers/string_helper.php");
        $proddesc = str_replace("'", "", $proddesc);
        $proddesc = str_replace(",", "", $proddesc);
        $proddesc = str_replace("|", "-", $proddesc);

//      $prodchrs = array (chr(20), chr(96), chr(20), chr(10), chr(13), chr(9), chr(124));
//      $prodrepl = array("-","-","-","-","-","-","-");
//      $proddesc = str_replace($prodchrs, $prodrepl, $proddesc);
        $proddesc = str_replace(array("\r\n", "\r", "\n"), "-", $proddesc);

        $proddesc = str_replace('<br />', '-', $proddesc);
        $proddesc = str_replace('<BR />', '-', $proddesc);
        $proddesc = str_replace('<br >', '-', $proddesc);
        $proddesc = str_replace('<BR >', '-', $proddesc);
        $proddesc = str_replace('<b>', '', $proddesc);
        $proddesc = str_replace('</b>', '', $proddesc);
        $proddesc = str_replace('<B>', '', $proddesc);
        $proddesc = str_replace('</B>', '', $proddesc);

        if (strlen($proddesc) > 1900) {
            $CIproddesc = cutstr($proddesc, 1900, ' . . . .');
            $RVproddesc = trim(str_replace("-", " ", $CIproddesc));
        } else {
            $CIproddesc = trim($proddesc);
            $RVproddesc = trim(str_replace("-", " ", $CIproddesc));
        }
        $CIproddesc = trim($CIproddesc);

        if (strlen($proddesc) > 200) {
            $proddesc = cutstr($proddesc, 200, ' . . . .');
        }

        $proddesc = trim($proddesc);

//      $EMAXproddesc = $CIproddesc;

//      if($feeder =='REEVOO'){ $proddesc =$EMAXproddesc; }
        if ($feeder == 'REEVOO') {
            $proddesc = $RVproddesc;
        }

        return $proddesc;
    }

    function reevoo_massage($prodname, $prodbrand)
    {
        $prodchrs = array(chr(9));
        $prodrepl = array("-");
        $prodname = str_replace($prodchrs, $prodrepl, $prodname);

        if ($prodname[0] == "*") {
            $RVprodname = trim(str_replace("*", "", $prodname));
        } else {
            $RVprodname = $prodname;
        }

        $RVpos = strpos($RVprodname, $prodbrand);

        switch ($prodbrand) {
            case "Nabaztag":
                $RVbrand = $prodbrand;
                $RVmodelname = trim(substr($RVprodname, $RVpos + strlen($prodbrand) + 1, (strlen($RVprodname) - ($RVpos + strlen($prodbrand) + 1))));
                $RVprodname = $RVmodelname;
                break;

            case "Hoya":
                $RVbrand = $prodbrand;
                $RVmodelname = trim(substr($RVprodname, $RVpos + strlen($prodbrand), (strlen($RVprodname) - ($RVpos + strlen($prodbrand)))));
                break;

            case "Cameron Sino":
                $RVbrand = $prodbrand;
                $RVmodelname = $RVprodname;
                break;

            case "Misc":
                $RVbrand = $prodbrand;
                $RVmodelname = trim(substr($RVprodname, $RVpos + strlen($prodbrand), (strlen($RVprodname) - ($RVpos + strlen($prodbrand)))));
                $RVnamepos = strpos($RVprodname, "(");
                if ($RVnamepos != 0) {
                    $RVprodname = trim(substr($RVprodname, 0, $RVnamepos));
                }

                $RVmodelpos = strpos($RVmodelname, "(");
                if ($RVmodelpos != 0) {
                    $RVmodelname = trim(substr($RVmodelname, 0, $RVmodelpos));
                }
                break;
            default:
                $itemposb = strpos($RVprodname, " ");
                $RVbrand = $prodbrand ? $prodbrand : trim(substr($RVprodname, 0, $itemposb));
                $RVmodelname = $RVpos !== FALSE ? trim(substr($RVprodname, $RVpos + strlen($prodbrand), (strlen($RVprodname) - ($RVpos + strlen($prodbrand))))) : ($prodbrand ? $RVprodname : trim(substr($RVprodname, $itemposb, (strlen($RVprodname) - ($itemposb)))));

                $RVnamepos = strpos($RVprodname, "(");
                if ($RVnamepos != 0) {
                    $RVprodname = trim(substr($RVprodname, 0, $RVnamepos));
                }

                $RVmodelpos = strpos($RVmodelname, "(");
                if ($RVmodelpos != 0) {
                    $RVmodelname = trim(substr($RVmodelname, 0, $RVmodelpos));
                }
                break;
        }
        return array("model" => $RVmodelname, "brand_name" => $RVbrand, "prod_name" => $RVprodname);
    }

    public function lstrans()
    {
        include_once(APPPATH . "libraries/dao/Ls_transactions_dao.php");
        $lstrans_dao = new Ls_transactions_dao();
        $lstrans_vo = $lstrans_dao->get();
        $lstrans_list = $lstrans_dao->get_lstrans_list(array("is_sent" => 'N'), array("limit" => -1));
        $ls_path = "/var/data/valuebasket.com/linkshare";
        $rs_file = "/lstrans.txt";
        if ($fp = fopen($ls_path . $rs_file, 'w')) {
            $i = 0;
            foreach ($lstrans_list as $new_obj) {
                $out_xml = new Vo_to_xml($new_obj);
                $out_csv = new Xml_to_csv("", APPPATH . "data/lstrans_xml2csv.txt", FALSE, "\t");
                $output = $this->get_dex()->convert($out_xml, $out_csv);
                fwrite($fp, $output);
                $new_obj->set_is_sent("Y");
                $lstrans_obj = clone $lstrans_vo;
                set_value($lstrans_obj, $new_obj);
                $lstrans_dao->update($lstrans_obj);
                $i++;
            }
            fclose($fp);
            if ($i == 0) {
                @unlink($ls_path . $rs_file);
            }
        }

        $ls_dir = opendir($ls_path);

        while (false !== ($lsfile = readdir($ls_dir))) {
            if ($lsfile == "lstrans.txt" || eregi("^ldata", $lsfile) == 1) {
                $cmd = "cd {$ls_path};./lstrans -file {$lsfile} -port 443";
                `$cmd`;
            }
        }
    }

    public function gen_purchaser_feed()
    {

        include_once(BASEPATH . "libraries/Encrypt.php");
        $encrypt = new CI_Encrypt();
        $ftp = $this->ftpConnector;

        $feeder = "REEVOO";
        $sl_feeder = strtolower($feeder);
        $enddate = date("Y-m-d H:00:00");
        $startdate = date("Y-m-d H:i:s", strtotime("$enddate -1 month"));
        $pre_date = date("Ymd");

        include_once APPPATH . "libraries/service/So_service.php";
        $so_svc = new So_service();
        $feed_list = $so_svc->get_soal_dao()->get_purchaser_feed_list(array("so.order_create_date >" => $startdate, "so.order_create_date <=" => $enddate), array("limit" => -1));
        $rs_path = "/var/data/valuebasket.com/feed/{$sl_feeder}/";
        $rs_file = "RVPFeed_{$pre_date}.txt";
        if ($feed_list) {
            if ($fp = fopen($rs_path . $rs_file, 'w')) {
                foreach ($feed_list as $feed_obj) {
                    $qty = $feed_obj->get_qty();
                    for ($i = 0; $i < $qty; $i++) {
                        $out_xml = new Vo_to_xml($feed_obj);
                        $out_csv = new Xml_to_csv("", APPPATH . "data/reevoo_purchaser_feed_xml2csv.txt", FALSE, "\t");
                        $output = $this->get_dex()->convert($out_xml, $out_csv);
                        fwrite($fp, $output);
                    }
                }
                fclose($fp);

                if ($ftp_obj = $this->getDao('FtpInfo')->get(array("name" => $feeder))) {
                    $ftp->set_remote_site($server = $ftp_obj->get_server());
                    $ftp->set_username($ftp_obj->get_username());
                    $ftp->set_password($encrypt->decode($ftp_obj->get_password()));
                    $ftp->set_port($ftp_obj->get_port());
                    $ftp->set_is_passive($ftp_obj->get_pasv());

                    if ($ftp->connect() !== FALSE) {
                        if ($ftp->login() !== FALSE) {
                            $ftp->putfile($rs_path . $rs_file, $rs_file);
                        }
                        $ftp->close();
                    }
                }
            }
        }
    }

    public function gen_amazon_ackfeed($platform = "AMUK")
    {
        include_once APPPATH . "libraries/service/So_service.php";
        $so_svc = new So_service();

        include_once APPPATH . "libraries/dao/So_payment_status_dao.php";
        $sops_dao = new So_payment_status_dao();

        include_once APPPATH . "libraries/dto/event_email_dto.php";
        $dto = new Event_email_dto();

        include_once(BASEPATH . "libraries/Encrypt.php");
        $encrypt = new CI_Encrypt();

        $sku_list = array();
        $dex = $this->get_dex();
        $list = $so_svc->get_dao()->get_ack_list($platform);

        $reason = array();
        if (count($list)) {
            $localfile = "OrderAck_" . date("YmdHid") . ".txt";
            $filename = "amazonacknowledgments.txt";

            foreach ($list as $obj) {
                $sku_list[$obj->get_so_no()] = 1;
            }

            $out_xml = new Vo_to_xml($list, APPPATH . 'data/amazon_ack_feed_vo2xml.txt');
            $out_csv = new Xml_to_csv("", "", TRUE, "\t");
            $output = $dex->convert($out_xml, $out_csv);

            $rs_dir = "/var/data/valuebasket.com/amazon_ack/";
            $path = "/var/data/valuebasket.com/amazon_ack/" . strtolower($platform) . "/";

            if (!is_dir($rs_dir)) {
                $ret = mkdir($rs_dir, "0755");
                if ($ret === FALSE) {
                    $reason[] = 'Cannot Create Directory ' . $rs_dir;
                }
            }

            if (!is_dir($path)) {
                $ret = mkdir($path, "0755");
                if ($ret === FALSE) {
                    $reason[] = 'Cannot Create Directory ' . $path;
                }
            }

            if ($handle = fopen($path . $localfile, "w")) {
                fwrite($handle, $output);
                fclose($handle);

                $ftp = $this->ftpConnector;
                $ftp_obj = $this->getDao('FtpInfo')->get(array("name" => "IXTENS_" . $platform));
                $ftp->set_remote_site($server = $ftp_obj->get_server());
                $ftp->set_username($ftp_obj->get_username());
                $ftp->set_password($encrypt->decode($ftp_obj->get_password()));
                $ftp->set_port($ftp_obj->get_port());
                $ftp->set_is_passive($ftp_obj->get_pasv());

                $folder = array('AMUS' => '', 'AMUK' => 'ToUK/', 'AMDE' => 'ToDE/', 'AMFR' => 'ToFR/');
                $remote_path = "FromSupplier/{$folder[$platform]}";

                $err = 0;

                if ($ftp->connect() !== FALSE) {
                    if ($ftp->login() !== FALSE) {
                        $result = $ftp->putfile($path . $localfile, $remote_path . $filename);
                        if ($result === FALSE) {
                            $err = 1;
                        }
                    } else {
                        $err = 1;
                    }
                    $ftp->close();
                } else {
                    $err = 1;
                }

                if ($err) {
                    $reason[] = "Failed to upload file to remote site";
                }
            } else {
                $reason[] = "Failed to open file for writing";
            }

            if (count($reason)) {
                $tmp = clone $dto;
                $tmp->set_event_id("notification");
                $tmp->set_mail_to(array("steven@eservicesgroup.net"));
                $tmp->set_mail_from("do_not_reply@valuebasket.com");
                $tmp->set_tpl_id("amazon_noack_feed");
                $tmp->set_replace(array("platform" => $platform, "time" => date("Y-m-d H:i:s"), "reason" => "\n\n" . implode("\n", $reason)));
                $this->get_event()->fire_event($tmp);
            } else {
                $fail_list = array();
                //process record change for orders
                foreach ($sku_list as $key => $val) {
                    $so_obj = $so_svc->get_dao()->get(array("so_no" => $key));
                    $so_ext_obj = $so_svc->get_soext_dao()->get();

                    $so_ext_obj->set_so_no($key);
                    $so_ext_obj->set_acked('Y');
                    $so_ext_obj->set_fulfilled('N');

                    $so_obj->set_status(3);

                    if ($sops_obj = $sops_dao->get(array("so_no" => $key))) {
                        $sops_obj->set_payment_status('S');
                        $sops_dao->update($sops_obj);
                    }

                    $so_svc->get_dao()->trans_start();
                    $ret = $so_svc->get_dao()->update($so_obj);
                    $ret2 = $so_svc->get_soext_dao()->insert($so_ext_obj);
                    if (!$ret || !$ret2) {
                        $fail_list[] = $key;
                    }
                    $so_svc->get_dao()->trans_complete();
                }

                if (count($fail_list)) {
                    $tmp = clone $dto;
                    $tmp->set_event_id("notification");
                    $tmp->set_mail_to(array("steven@eservicesgroup.net"));
                    $tmp->set_mail_from("do_not_reply@valuebasket.com");
                    $tmp->set_tpl_id("general_alert");
                    $tmp->set_replace(array("title" => "Query Alert - Ack Feed for $platform", "message" => "On " . date("Y-m-d H:i:s") . ", the so listed below failed to get status changed and marked acknowledged:\n\n" . implode(",", $fail_list), "att_file" => $path . $filename));
                    $this->get_event()->fire_event($tmp);
                } else {
                    $tmp = clone $dto;
                    $tmp->set_event_id("notification");
                    $tmp->set_mail_to(array("steven@eservicesgroup.net"));
                    $tmp->set_mail_from("do_not_reply@valuebasket.com");
                    $tmp->set_tpl_id("general_alert");
                    $tmp->set_replace(array("title" => "Successful Alert - Acknowledge Feed for $platform", "message" => "Completed on " . date("Y-m-d H:i:s") . " .\nFile Attached.", "att_file" => $path . $filename));
                    $this->get_event()->fire_event($tmp);
                }
            }
        } else {
            //fire event - email for no ack is uploaded
            $tmp = clone $dto;
            $tmp->set_event_id("notification");
            $tmp->set_mail_to(array("steven@eservicesgroup.net"));
            $tmp->set_mail_from("do_not_reply@valuebasket.com");
            $tmp->set_tpl_id("amazon_noack_feed");
            $tmp->set_replace(array("platform" => $platform, "time" => date("Y-m-d H:i:s"), "reason" => ""));
            $this->get_event()->fire_event($tmp);
        }
    }

    public function gen_amazon_fulfillfeed($platform = "AMUK")
    {
        include_once APPPATH . "libraries/service/So_service.php";
        $so_svc = new So_service();

        include_once APPPATH . "libraries/dto/event_email_dto.php";
        $dto = new Event_email_dto();

        include_once APPPATH . "libraries/dao/Client_dao.php";
        $client_dao = new Client_dao();

        include_once APPPATH . "libraries/dao/So_dao.php";
        $so_dao = new So_dao();

        include_once(BASEPATH . "libraries/Encrypt.php");
        $encrypt = new CI_Encrypt();

        $from_email = array("AMUK" => "mandy@valuebasket.com", "AMFR" => "sandrine@valuebasket.com");
        $sku_list = array();
        $dex = $this->get_dex();
        $list = $so_svc->get_dao()->get_fulfillment_list($platform);
        $reason = array();
        if (count($list)) {
            $filename = "OrderFulfillment_" . date("YmdHis") . ".txt";

            foreach ($list as $obj) {
                $sku_list[$obj->get_so_no()] = 1;
                if ($obj->get_courier_id() == "") {
                    $obj->set_courier_id("NA");
                }
            }

            $out_xml = new Vo_to_xml($list, APPPATH . 'data/amazon_fulfillment_feed_vo2xml.txt');
//          $out =  new Xml_to_xml();
//          $output = $dex->convert($out_xml, $out);

            $out_csv = new Xml_to_csv("", "", TRUE, "\t");
            $output = $dex->convert($out_xml, $out_csv);

            $rs_dir = "/var/data/valuebasket.com/amazon_fulfillment/";
            $path = "/var/data/valuebasket.com/amazon_fulfillment/" . strtolower($platform) . "/";

            if (!is_dir($rs_dir)) {
                $ret = mkdir($rs_dir, "0755");
                if ($ret === FALSE) {
                    $reason[] = 'Cannot Create Directory ' . $rs_dir;
                }
            }
            if (!is_dir($path)) {
                $ret = mkdir($path, "0755");
                if ($ret === FALSE) {
                    $reason[] = 'Cannot Create Directory ' . $path;
                }
            }

            if ($handle = fopen($path . $filename, "w")) {
                fwrite($handle, $output);
                fclose($handle);

                $ftp = $this->ftpConnector;
                $ftp_obj = $this->getDao('FtpInfo')->get(array("name" => "IXTENS_" . $platform));
                $ftp->set_remote_site($server = $ftp_obj->get_server());
                $ftp->set_username($ftp_obj->get_username());
                $ftp->set_password($encrypt->decode($ftp_obj->get_password()));
                $ftp->set_port($ftp_obj->get_port());
                $ftp->set_is_passive($ftp_obj->get_pasv());

                $folder = array('AMUS' => '', 'AMUK' => 'ToUK/', 'AMDE' => 'ToDE/', 'AMFR' => 'ToFR/');
                $remote_path = "FromSupplier/{$folder[$platform]}";

                $err = 0;

                if ($ftp->connect() !== FALSE) {
                    if ($ftp->login() !== FALSE) {
                        $result = $ftp->putfile($path . $filename, $remote_path . $filename);
                        if ($result === FALSE) {
                            $err = 1;
                        }
                    } else {
                        $err = 1;
                    }
                    $ftp->close();
                } else {
                    $err = 1;
                }

                if ($err) {
                    $reason[] = "Failed to upload file to remote site";
                }
            } else {
                $reason[] = "Failed to open file for writing";
            }

            if (count($reason)) {
                $tmp = clone $dto;
                $tmp->set_event_id("notification");
                $tmp->set_mail_to(array("steven@eservicesgroup.net"));
                $tmp->set_mail_from("do_not_reply@valuebasket.com");
                $tmp->set_tpl_id("amazon_noful_feed");
                $tmp->set_replace(array("platform" => $platform, "time" => date("Y-m-d H:i:s"), "reason" => "\n\n" . implode("\n", $reason)));
                $this->get_event()->fire_event($tmp);
            } else {
                $fail_list = array();
                //process record change for orders
                foreach ($sku_list as $key => $val) {
                    $so_ext_obj = $so_svc->get_soext_dao()->get(array("so_no" => $key));

                    $so_ext_obj->set_fulfilled('Y');
                    $so_obj = $so_dao->get(array("so_no" => $key));
                    $client_obj = $client_dao->get(array("id" => $so_obj->get_client_id()));

                    $ret = $so_svc->get_soext_dao()->update($so_ext_obj);
                    if (!$ret) {
                        $fail_list[] = $key;
                    }

                    $tmp = clone $dto;
                    $tmp->set_event_id("notification");
                    $tmp->set_mail_to($client_obj->get_email());
                    $tmp->set_mail_from($from_email[$platform]);
                    $tmp->set_tpl_id(strtolower($platform) . "_dispatch_email");
                    $tmp->set_replace(array("buyername" => $so_obj->get_delivery_name(), "amazon_order_no" => $so_obj->get_platform_order_id()));
                    //$this->get_event()->fire_event($tmp);

                }

                if (count($fail_list)) {
                    $tmp = clone $dto;
                    $tmp->set_event_id("notification");
                    $tmp->set_mail_to(array("steven@eservicesgroup.net"));
                    $tmp->set_mail_from("do_not_reply@valuebasket.com");
                    $tmp->set_tpl_id("general_alert");
                    $tmp->set_replace(array("title" => "Query Alert - Fulfillment Feed for $platform", "message" => "On " . date("Y-m-d H:i:s") . ", the so listed below failed to get status changed and marked acknowledged:\n\n" . implode(",", $fail_list), "att_file" => $path . $filename));
                    $this->get_event()->fire_event($tmp);
                } else {
                    $tmp = clone $dto;
                    $tmp->set_event_id("notification");
                    $tmp->set_mail_to(array("steven@eservicesgroup.net"));
                    $tmp->set_mail_from("do_not_reply@valuebasket.com");
                    $tmp->set_tpl_id("general_alert");
                    $tmp->set_replace(array("title" => "Successful Alert - Fulfillment Feed for $platform - " . date("Y-m-d"), "message" => "Completed on " . date("Y-m-d H:i:s") . " .\nFile Attached.", "att_file" => $path . $filename));
                    $this->get_event()->fire_event($tmp);
                }
            }
        } else {
            //fire event - email for no ack is uploaded
            $tmp = clone $dto;
            $tmp->set_event_id("notification");
            $tmp->set_mail_to(array("steven@eservicesgroup.net"));
            $tmp->set_mail_from("do_not_reply@valuebasket.com");
            $tmp->set_tpl_id("amazon_noful_feed");
            $tmp->set_replace(array("platform" => $platform, "time" => date("Y-m-d H:i:s"), "reason" => ""));
            $this->get_event()->fire_event($tmp);
        }
    }

    public function get_discontinue_feed($platform = "AMUK")
    {
        // This function will now only remove products that are discontinued across all Amazon Channel.
        include_once APPPATH . "libraries/dao/Product_dao.php";
        $prod_dao = new Product_dao();

        include_once APPPATH . "libraries/dto/event_email_dto.php";
        $dto = new Event_email_dto();

        include_once APPPATH . "libraries/dao/Disc_prod_list_dao.php";
        $dpl_dao = new Disc_prod_list_dao();

        include_once(BASEPATH . "libraries/Encrypt.php");
        $encrypt = new CI_Encrypt();

        $reason = array();
        $prod_list = $prod_dao->get_discontinued_product_list($platform);

        $file = "disc_" . date("YmdH") . ".xls";

        $rs_dir = "/var/data/valuebasket.com/discfeed/";
        $path = "/var/data/valuebasket.com/discfeed/" . strtolower($platform) . "/";

        if (!is_dir($rs_dir)) {
            $ret = mkdir($rs_dir, "0755");
            if ($ret === FALSE) {
                $reason[] = 'Cannot Create Directory ' . $rs_dir;
            }
        }

        if (!is_dir($path)) {
            $ret = mkdir($path, "0755");
            if ($ret === FALSE) {
                $reason[] = 'Cannot Create Directory ' . $rs_dir;
            }
        }

        $write_to = $path . $file;

        if ($fp = fopen($write_to, 'wb')) {

            @fwrite($fp, implode("\n", $prod_list));
            @fclose($fp);
            $ftp = $this->ftpConnector;
            $ftp_obj = $this->getDao('FtpInfo')->get(array("name" => "IXTENS_" . $platform));
            $server = $ftp_obj->get_server();
            $ftp->set_remote_site($server);

            $ftp->set_username($ftp_obj->get_username());
            $ftp->set_password($encrypt->decode($ftp_obj->get_password()));
            $ftp->set_port($ftp_obj->get_port());
            $ftp->set_is_passive($ftp_obj->get_pasv());

            $folder = array('AMUS' => '', 'AMUK' => 'ToUK/', 'AMDE' => 'ToDE/', 'AMFR' => 'ToFR/');
            $remote_path = "FromSupplier/{$folder[$platform]}";

            $err = 0;

            if ($ftp->connect() !== FALSE) {
                echo "Connected\n";
                if ($ftp->login() !== FALSE) {
                    $result = $ftp->putfile($write_to, $remote_path . $file);
                    if ($result === FALSE) {
                        $err = 1;
                    }
                } else {
                    $err = 1;
                }
                $ftp->close();
            } else {
                $err = 1;
            }

            if ($err) {
                $reason[] = "Failed to upload file to remote site";
            }
        } else {
            echo "failed\n";
            $reason[] = 'Cannot Open file ' . $write_to . ' for writing';
        }

        if (count($reason)) {
            $tmp = clone $dto;
            $tmp->set_event_id("notification");
            $tmp->set_mail_to(array("steven@eservicesgroup.net"));
            $tmp->set_mail_from("do_not_reply@valuebasket.com");
            $tmp->set_tpl_id("general_alert");
            $tmp->set_replace(array("title" => "Error while preparing Discontinued feed for $platform", "message" => "Discontinued product feed was uploaded successfully for $platform", "att_file" => $write_to));
            $this->get_event()->fire_event($tmp);
            $dpl_dao->q_delete(array("platform_id" => $platform));
        } else {
            $tmp = clone $dto;
            $tmp->set_event_id("notification");
            $tmp->set_mail_to(array("steven@eservicesgroup.net"));
            $tmp->set_mail_from("do_not_reply@valuebasket.com");
            $tmp->set_tpl_id("general_alert");
            $tmp->set_replace(array("title" => "$platform discontinued feed was completed", "message" => "platform discontinued feed was completed on " . date("Y-m-d H:i:s")));
            $this->get_event()->fire_event($tmp);

            $dpl_dao->q_delete(array("platform_id" => $platform));

        }
    }

    public function get_iss_dao()
    {
        return $this->iss_dao;
    }

    public function set_iss_dao(Base_dao $value)
    {
        $this->iss_dao = $value;
    }

    public function generate_password($len = 8)
    {

        $length = $len;
        $characters = "0123456789abcdeifghjkmnopqrstuvwxyz";
        $string = "";

        for ($p = 0; $p < $length; $p++) {
            $string .= $characters[mt_rand(0, strlen($characters))];
        }
        return $string;
    }

    public function schedulePhpProcess($run_minute_after, $php_file)
    {
        $command = 'cd ' . APPPATH . '../admincentre/; ';
        $command .= '/usr/bin/php index.php ' . $php_file;

        $this->createSystemAtJob($run_minute_after, $command);
    }

    public function createSystemAtJob($run_minute_after, $command)
    {
        $file = tempnam(sys_get_temp_dir(), '');

        if (!$handle = @fopen($file, 'w')) {
            error_log('file:' . __FILE__ . ', line:' . __LINE__ . ':Cannot create file handle');
            return;
        }

        if (fwrite($handle, $command) === FALSE) {
            error_log('file:' . __FILE__ . ', line:' . __LINE__ . ':Cannot write command into file');
            return;
        }

        fclose($handle);
        exec('/usr/bin/at -f ' . $file . ' now + ' . $run_minute_after . ' minutes');
        unlink($file);
    }
}




