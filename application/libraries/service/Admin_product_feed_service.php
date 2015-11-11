<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Data_feed_service.php";

class Admin_product_feed_service extends Data_feed_service
{
    protected $id = "Admin Product Feed";
    private $prod_srv;
    private $price_srv;
    private $filename;
    private $filepath;
    private $error;

    public function __construct()
    {
        parent::Data_feed_service();
        include_once(APPPATH . 'libraries/service/Price_service.php');
        $this->set_price_srv(New Price_service());
        // $this->load->model('marketing/pricing_tool_model');
        $this->set_output_delimiter(",");
        include_once(APPPATH . 'libraries/service/Selling_platform_service.php');
        $this->set_selling_platform_service(New Selling_platform_service());
    }

    public function set_selling_platform_service(Base_service $srv)
    {
        $this->selling_platform_service = $srv;
    }

    public function get_price_srv()
    {
        return $this->price_srv;
    }

    public function set_price_srv(Base_service $srv)
    {
        $this->price_srv = $srv;
    }

    public function get_selling_platform_service()
    {
        return $this->selling_platform_service;
    }

    public function gen_data_feed($platform_type = NULL)
    {
        set_time_limit(120);
        define('DATAPATH', $this->get_config_srv()->value_of("data_path"));

        if (isset($platform_type) && $platform_type == "marketplace")
            $this->filename = "admin_marketplace_product_feed.csv";
        else
            $this->filename = "admin_product_feed.csv";

        $this->filepath = DATAPATH . "feeds/admin/";

        $data_feed = $this->get_data_feed(TRUE, $platform_type);
        if ($data_feed === TRUE) {
            if (isset($platform_type) && $platform_type == "marketplace")
                $this->send_report($platform_type);
            else
                $this->send_report();
        } else {
            if (isset($platform_type) && $platform_type == "marketplace")
                $subject = "Failed to create Admin Marketplace Product Feed File";
            else
                $subject = "Failed to create Admin Product Feed File";

            $message = "FILE: " . __FILE__ . "\r\nLINE: " . __LINE__;
            $this->send_notification_email($subject, $message);
        }

    }

    public function get_data_feed($first_line_headling = TRUE, $platform_type = NULL)
    {
        // THIS IS THE NEW VERSION
        // No need to request to add new ticket when there are new platforms
        // Also, run this from SSH / cron job so that browser timeout will not occur

        set_time_limit(0);
        ini_set('memory_limit', '512M');
        // we need to increase memory limit as a WORK AROUND
        // end of the day we must find a solution on why calling $this->get_margin
        // eats up a huge amount of memory

        // 2nd dec 2013, memory issue root cause found, it was due to CI's $db->save_queries = true, this stores all the queries being executed
        // we set save_queries to false in get_margin
        $this->price_srv->get_dao()->db->save_queries = false;


        if (isset($platform_type) && $platform_type == "marketplace")
            $list = $this->get_data_list(array(), array('platform_type' => $platform_type));
        else
            $list = $this->get_data_list();

        if (!$list) {
            $message = __LINE__ . " No product data retrieved. \r\nDB error: " . $this->get_prod_srv()->get_dao()->db->_error_message();
            $this->send_notification_email("No Product Data", $message);
            echo "ERROR: $message";
            return FALSE;
        }

        //Clear the folder only when generating platform type website
        if (!isset($platform_type) || $platform_type != "marketplace")
            $this->del_dir($this->filepath);

        $fp = fopen($this->filepath . $this->filename, 'w');

        $new_list = $platformlist = array();
        $report_data = $content = "";

        # all these are in group concat, we will separate them out later
        $ignore = array("price", "platform_id", "ship_day", "delivery_day");

        if (isset($platform_type) && $platform_type == "marketplace") {
            if ($allow_sell_list = $this->selling_platform_service->get_platform_list_w_allow_sell_country("MARKETPLACE")) {
                foreach ($allow_sell_list as $key => $value) {
                    //if(strpos($value["platform_id"], "WEB") !== FALSE)
                    $platformlist[] = $value["platform_id"];
                }
            } else {
                $message = __LINE__ . " Could not retrieve selling platform list. \r\nDB error: " . $this->selling_platform_service->db->_error_message();
                $this->send_notification_email("DB Error", $message);
                echo "ERROR $message";
                return;
            }
        } else {
            if ($allow_sell_list = $this->selling_platform_service->get_platform_list_w_allow_sell_country("WEBSITE")) {
                foreach ($allow_sell_list as $key => $value) {
                    if (strpos($value["platform_id"], "WEB") !== FALSE)
                        $platformlist[] = $value["platform_id"];
                }
            } else {
                $message = __LINE__ . " Could not retrieve selling platform list. \r\nDB error: " . $this->selling_platform_service->db->_error_message();
                $this->send_notification_email("DB Error", $message);
                echo "ERROR $message";
                return;
            }
        }

        $j = 0;
        $totalrow = count($list);
        foreach ($list as $res) {
            // $this->get_price_srv()->calculate_profit($row);
            $report_header = "";
            $price_margin_arr = array();
            if ($res) {
                ob_end_clean();
                # split group_concat price/platform_id in product_dao into each platform_id platform
                # group_concat needed else data is too big to run.
                $price = explode(',', $res["price"]);
                $platform_id_arr = explode(',', $res["platform_id"]);
                $ship_day = explode(',', $res["ship_day"]);
                $delivery_day = explode(',', $res["delivery_day"]);
                $price_country_arr = array();

                // this loop below proccesses price, margin, ship and delivery days at single SKU level
                for ($i = 0; $i < count($price); $i++) {
                    if ($platform_id = $platform_id_arr[$i]) {
                        $price_country_arr = array('price' => $price[$i], 'platform_id' => $platform_id);

                        // this line consumes an enormous amount of memory,
                        if ($profit_margin = $this->get_margin($platform_id, $res["sku"], $price[$i])) {
                            $price_margin_arr[$platform_id]["margin"] = $profit_margin;
                            $price_margin_arr[$platform_id]["price"] = $price_country_arr['price'];

                            // if(!$ship_day[$i])
                            //  $ship_day[$i] = " ";
                            // if(!$delivery_day[$i])
                            //  $delivery_day[$i] = " ";

                            $price_margin_arr[$platform_id]["ship_day"] = $ship_day[$i];
                            $price_margin_arr[$platform_id]["delivery_day"] = $delivery_day[$i];
                        } else {
                            // no price margin
                            $price_margin_arr[$platform_id]["margin"] = " ";
                            $price_margin_arr[$platform_id]["price"] = " ";
                            $price_margin_arr[$platform_id]["ship_day"] = " ";
                            $price_margin_arr[$platform_id]["delivery_day"] = " ";
                        }
                    }
                }

                // reformat display
                $website_status = $res["website_status"];
                switch ($website_status) {
                    case 'I':
                        $res["website_status"] = 'Instock';
                        break;

                    case 'O':
                        $res["website_status"] = 'Outstock';
                        break;

                    case 'P':
                        $res["website_status"] = 'Pre-order';
                        break;

                    case 'A':
                        $res["website_status"] = 'Arriving';
                        break;

                    default:
                        $res["website_status"] = ' ';
                        break;
                }

                // reformat display
                $status = $res["status"];
                switch ($status) {
                    case '0':
                        $res["status"] = 'Inactive';
                        break;

                    case '1':
                        $ret["status"] = 'SKU Created';
                        break;

                    case '2':
                        $res["status"] = 'Production Listed';
                        break;

                    default:
                        $res["status"] = ' ';
                        break;
                }

                # construct CSV data and headers at each SKU level
                // => ext_sku, sku, name, price, [...]
                foreach ($res as $key => $value) {
                    if (in_array($key, $ignore))
                        continue;

                    if ($j == 0) {
                        $report_header .= "$key,";
                    }

                    // prevents any empty/null values from screwing up csv
                    if ($value == "" || $value == NULL)
                        $value = " ";

                    $report_data .= str_replace(",", " ", $value) . ",";
                }

                # construct platform-specific data
                // e.g. WEBAU_price, WEBAU_margin, WEBAU_ship_day, WEBAU_delivery_day, WEBBE_price, WEBBE_margin, [...]
                foreach ($platformlist as $id) {
                    if ($j == 0) {
                        $report_header .= "{$id}_price,{$id}_margin,{$id}_ship_day,{$id}_delivery_day,";
                    }

                    if ($price_margin_arr[$id]) {
                        $report_data .= "{$price_margin_arr[$id]["price"]},{$price_margin_arr[$id]["margin"]},{$price_margin_arr[$id]["ship_day"]},{$price_margin_arr[$id]["delivery_day"]},";
                    } else {
                        # if this platform doesn't exist in group_concat, means not listed
                        $report_data .= ",,,,";
                    }
                }

                if ($report_header !== "") {
                    $report_header = trim($report_header, ',') . "\r\n";
                }

                $report_data = trim($report_data, ',') . "\r\n";
                $content .= $report_header . $report_data;
                fwrite($fp, $content);
                $j++;

                # clear memory
                $report_header = $report_data = $content = $price_margin_arr = null;

                // if($j != 0 && $j/300 == 0)
                //  sleep(5);

                // if you're debugging from browser, uncomment the lines below
                // if($j == 300)
                // {
                //  header("Content-type: text/csv");
                //  header("Cache-Control: no-store, no-cache");
                //  header("Content-Disposition: attachment; filename=\"admin_product_feed_DEV.csv\"");

                //  $report_content = file_get_contents($this->filepath . $this->filename);
                //  echo $report_content;
                //  die();
                // }
            }
        }

        $this->timestamp = date('Y-m-d H:i:s');
        $summary = "Total Rows, $totalrow \r\nGenerated on,{$this->timestamp} \r\n";
        fwrite($fp, $summary);
        fclose($fp);

        return TRUE;
    }

    protected function get_data_list($where = array(), $option = array())
    {
        $this->get_prod_srv()->get_dao()->db->save_queries = false;
        return $this->get_prod_srv()->get_admin_product_feed_dto(array("p.status" => 2), array('limit' => -1, 'platform_type' => $option['platform_type']));
    }

    private function send_notification_email($subject, $error_msg = "")
    {
        include_once(BASEPATH . "plugins/phpmailer/phpmailer_pi.php");
        $phpmail = new phpmailer();
        $phpmail->IsSMTP();
        $phpmail->From = "Admin <admin@valuebasket.net>";

        $phpmail->AddAddress("bd_product_team@eservicesgroup.com");
        $phpmail->AddAddress("itsupport@eservicesgroup.net");

        $phpmail->Subject = "[Export All Product Feed] $subject";
        $phpmail->IsHTML(false);
        $phpmail->Body = $message . "[admin_product_feed.php]";

        if (strpos($_SERVER["HTTP_HOST"], "dev") === false)
            $result = $phpmail->Send();

        return;
    }

    // public function get_data_feed_old($first_line_headling = TRUE)
    // {
    //  set_time_limit(0);
    //      // ini_set('memory_limit','512M');
    //      // we need to increase memory limit as a WORK AROUND
    //      // end of the day we must find a solution on why calling $this->get_margin
    //      // eats up a huge amount of memory

    //      // 2nd dec 2013, memory issue root cause found, it was due to CI's $db->save_queries = true, this stores all the queries being executed
    //      // we set save_queries to false in get_margin
    //  $this->price_srv->get_dao()->db->save_queries = false;

    //  $list = $this->get_data_list();

    //  if (!$list)
    //  {
    //      return;
    //  }

    //  $new_list = array();
    //  foreach ($list as $row)
    //  {
    //      // $this->get_price_srv()->calculate_profit($row);

    //      if($res = $this->process_data_row($row))
    //      {
    //          # split group_concat price/country in product_dao into each country platform
    //          # group_concat needed else data is too big to run.
    //          $price = explode(',', $res->get_price());
    //          $country = explode(',', $res->get_platform_id());
    //          $price_country_arr = array();

    //          for ($i=0; $i < count($price); $i++)
    //          {
    //              if($country[$i])
    //              {
    //                  $price_country_arr = array('price' => $price[$i], 'country' => $country[$i]);

    //                  // this line consumes an enormous amount of memory,
    //                  $profit_margin = $this->get_margin($country[$i], $res->get_sku(), $price[$i]);

    //                  switch ($price_country_arr["country"])
    //                  {
    //                      # set margin by country

    //                      case 'WEBAU':
    //                          $res->set_webau_margin($profit_margin);
    //                          $res->set_webau_price($price_country_arr['price']);
    //                          break;

    //                      case 'WEBBE':
    //                          $res->set_webbe_margin($profit_margin);
    //                          $res->set_webbe_price($price_country_arr['price']);
    //                          break;

    //                      case 'WEBES':
    //                          $res->set_webes_margin($profit_margin);
    //                          $res->set_webes_price($price_country_arr['price']);
    //                          break;

    //                      case 'WEBFI':
    //                          $res->set_webfi_margin($profit_margin);
    //                          $res->set_webfi_price($price_country_arr['price']);
    //                          break;

    //                      case 'WEBFR':
    //                          $res->set_webfr_margin($profit_margin);
    //                          $res->set_webfr_price($price_country_arr['price']);
    //                          break;

    //                      case 'WEBGB':
    //                          $res->set_webgb_margin($profit_margin);
    //                          $res->set_webgb_price($price_country_arr['price']);
    //                          break;

    //                      case 'WEBHK':
    //                          $res->set_webhk_margin($profit_margin);
    //                          $res->set_webhk_price($price_country_arr['price']);
    //                          break;

    //                      case 'WEBIE':
    //                          $res->set_webie_margin($profit_margin);
    //                          $res->set_webie_price($price_country_arr['price']);
    //                          break;

    //                      case 'WEBMY':
    //                          $res->set_webmy_margin($profit_margin);
    //                          $res->set_webmy_price($price_country_arr['price']);
    //                          break;

    //                      case 'WEBNZ':
    //                          $res->set_webnz_margin($profit_margin);
    //                          $res->set_webnz_price($price_country_arr['price']);
    //                          break;

    //                      case 'WEBSG':
    //                          $res->set_websg_margin($profit_margin);
    //                          $res->set_websg_price($price_country_arr['price']);
    //                          break;

    //                      case 'WEBUS':
    //                          $res->set_webus_margin($profit_margin);
    //                          $res->set_webus_price($price_country_arr['price']);
    //                          break;

    //                      case 'WEBIT':
    //                          $res->set_webit_margin($profit_margin);
    //                          $res->set_webit_price($price_country_arr['price']);
    //                          break;

    //                      case 'WEBCH':
    //                          $res->set_webch_margin($profit_margin);
    //                          $res->set_webch_price($price_country_arr['price']);
    //                          break;

    //                      case 'WEBMT':
    //                          $res->set_webmt_margin($profit_margin);
    //                          $res->set_webmt_price($price_country_arr['price']);
    //                          break;

    //                      default:
    //                          $res->set_webus_price('');
    //                          break;
    //                  }

    //              }

    //          }

    //          $website_status = $res->get_website_status();
    //          switch ($website_status)
    //          {
    //              case 'I':
    //                  $res->set_website_status('Instock');
    //                  break;

    //              case 'O':
    //                  $res->set_website_status('Outstock');
    //                  break;

    //              case 'P':
    //                  $res->set_website_status('Pre-order');
    //                  break;

    //              case 'A':
    //                  $res->set_website_status('Arriving');
    //                  break;

    //              default:
    //                  $res->set_website_status('');
    //                  break;
    //          }

    //          $status = $res->get_status();
    //          switch ($status)
    //          {
    //              case '0':
    //                  $res->set_status('Inactive');
    //                  break;

    //              case '1':
    //                  $res->set_status('SKU Created');
    //                  break;

    //              case '2':
    //                  $res->set_status('Production Listed');
    //                  break;

    //              default:
    //                  $res->set_status('');
    //                  break;
    //          }

    //          // if ((($res->get_price() >= 200) && ($res->get_price() < 400) && ($res->get_margin() >= 10)) ||
    //          //  (($res->get_price() >= 400) && ($res->get_price() < 800) && ($res->get_margin() >= 8)) ||
    //          //  (($res->get_price() >= 800) && ($res->get_price() < 1200) && ($res->get_margin() >= 7)) ||
    //          //  (($res->get_price() >= 1200) && ($res->get_margin() >= 6)))
    //          {
    //              $new_list[] = $res;
    //          }
    //      }
    //  }

    //  $content = $this->convert($new_list, $first_line_headling);
    //  return $content;
    // }

    private function get_margin($platform_id, $sku, $price)
    {
        try {
            if ($json = $this->price_srv->get_profit_margin_json($platform_id, $sku, $price)) {
                $arr = json_decode($json, true);
                return $arr["get_margin"];
            } else {
                return FALSE;
            }
        } catch (Exception $e) {
            $this->error .= "Cannot retrieve price margin for <$platform_id> - SKU <$sku> - Selling Price <$price> \r\n";
        }


    }

    private function send_report($platform_type = NULL)
    {
        include_once(BASEPATH . "plugins/phpmailer/phpmailer_pi.php");
        $report_filepath = $this->filepath . $this->filename;

        $phpmail = new phpmailer();
        $phpmail->IsSMTP();
        $phpmail->From = "Admin <admin@valuebasket.com>";

        $phpmail->AddAddress("bd_product_team@eservicesgroup.com");
        // $phpmail->AddAddress("itsupport@eservicesgroup.net");

        if (isset($platform_type) && $platform_type == "marketplace")
            $phpmail->Subject = "[Export Marketplace Product Feed] Feed Attached";
        else
            $phpmail->Subject = "[Export All Product Feed] Feed Attached";

//      $phpmail->AddAttachment($report_filepath, "admin_product_feed.csv");

        $message = "Report accurate as of {$this->timestamp}. [admin_product_feed.php]";
        if ($this->error)
            $message .= "\r\n" . $this->error;

        $phpmail->Body = $message;

        if (strpos($_SERVER["HTTP_HOST"], "dev") === false)
            $result = $phpmail->Send();
        else {
            echo "DONE AT {$this->timestamp}. View file at $report_filepath";
            echo "<pre> $message";
        }
    }

    public function get_contact_email()
    {
        return 'itsupport@eservicesgroup.net';
    }

    protected function get_default_vo2xml_mapping()
    {
        return '';
    }

    protected function get_default_xml2csv_mapping()
    {
        return APPPATH . 'data/admin_product_feed_xml2csv.txt';
    }

    // protected function get_ftp_name()
    // {
    //  return 'ADMIN';
    // }

    protected function get_sj_id()
    {
        return "ADMIN_PRODUCT_FEED";
    }

    protected function get_sj_name()
    {
        return "Admin Product Feed Cron Time";
    }
}
