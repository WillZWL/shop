<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron_product_delivery extends MY_Controller
{
    private $appId = "CRN0024";

    function __construct()
    {
        parent::__construct();
        $this->load->library('service/deliverytime_service');

        $this->load->library("service/selling_platform_service");
        $this->load->library("service/price_service");
        $this->load->library("service/so_service");
        $this->load->library("dao/delivery_time_dao");
        $this->load->library("dao/product_dao");
        $this->load->library("dao/price_dao");
    }

    public function update_product_delivery_scenario($platform_type = "WEBSITE", $platform_id)
    {
        /*
            SBF #4020
            This function updates each product (per country) with a scenariod in price table.
            Front end for customers will show delivery time frames per product page
            based  on country, which is determined by delivery time management admin
        */
        // $this->deliverytime_service->update_product_delivery_scenario($platform_type, $platform_id);
        # need this line to set the correct pricing tool
        DEFINE("PLATFORM_TYPE", strtoupper($platform_type));
        $platform_id = strtoupper($platform_id);
        $price_service = $this->price_service;
        $debug_msg = "";

        if ($allow_sell_list = $this->selling_platform_service->get_platform_list_w_allow_sell_country($platform_type)) {
            foreach ($allow_sell_list as $key => $allowsellobj) {
                if ($platform_id !== strtoupper($allowsellobj["platform_id"])) {
                    // skip loop if it's not the platform_id passed in
                    continue;
                } else {
                    $this->platform_id = $platform_id;
                    $country_id = $allowsellobj["country_id"];
                }

                set_time_limit(0);
                $debug_msg = "<hr></hr><br>START PLATFORM $platform_id";

                # this will get the delivery_time for HighMargin scenario in resspective country
                if ($highmargin_dt_obj = $this->deliverytime_service->get_deliverytime_obj($country_id, 5)) {
                    # required margin to meet for HighMargin scenario. if empty, skip whole country
                    if (($dt_highmargin = $highmargin_dt_obj->get_margin()) == "") {
                        $error_msg = __FILE__ . " Line " . __LINE__ . " \nHighMargin scenario has no margin input for platform_id<$platform_id>. All products listed in this platform will not have delivery scenarios updated.";
                        $debug_msg .= "<br>ERROR. $error_msg";
                        $this->send_notification_email("warning", $error_msg);
                        echo $debug_msg;
                        continue;
                    }
                } else {
                    # without this HighMargin  scenario, logic cannot work, so skip the whole country
                    $error_msg = __FILE__ . " Line " . __LINE__ . " \nHighMargin scenario has no time frame input for platform_id<$platform_id>. All products listed in this platform will not have delivery scenarios updated.";
                    $debug_msg .= "<br>ERROR. $error_msg";
                    $this->send_notification_email("warning", $error_msg);
                    echo $debug_msg;
                    continue;
                }


                # get all products listing in this platform and are not discontinued
                $where = array(
                    "platform_id" => $platform_id,
                    "supplier_status != 'D'" => null
                );

                if ($objlist = $this->product_dao->get_listed_prod_supplier_info($where, $option)) {
                    // var_dump($this->db->last_query());die();
                    $i = 1;
                    $count = count((array)$objlist);
                    $update_list = array();

                    foreach ($objlist as $prod_info) {
                        $sku = $prod_info->get_sku();
                        $name = $prod_info->get_name();
                        $surplus_quantity = $prod_info->get_surplus_quantity();
                        $slow_move_7_days = $prod_info->get_slow_move_7_days();
                        $price = $prod_info->get_price();
                        $git = $prod_info->get_git();
                        $supplier_id = $prod_info->get_supplier_id();
                        $supplier_status = $prod_info->get_supplier_status();
                        $supplier_name = $prod_info->get_supplier_name();
                        $sourcing_status = strtoupper($prod_info->get_sourcing_status());
                        $origin_country = strtoupper($prod_info->get_origin_country());

                        $pending_orders = $this->so_service->get_orders_by_sku_and_status($sku, 2, array(), array("num_row" => 1));
                        $pending_qty = $pending_orders["total_qty"];

                        $scenarioid = $scenarioname = "";

                        # !!!! This line takes up large amount of resources
                        if (($json = $price_service->get_profit_margin_json($platform_id, $sku, $price)) === FALSE) {
                            $error_msg = __FILE__ . " Line " . __LINE__ . " \nCannot get profit margin json for platform_id<$platform_id> - SKU<$sku>. \nDB error: " . $this->db->_error_message();
                            $this->send_notification_email("retrieve_error", $error_msg);
                            $debug_msg .= "<br>ERROR. $error_msg";
                            continue;
                        } else {
                            /***************************************************************************************
                             * All needed info has been successfully set up, start scenario logic from this point!
                             * Instead of the usual update db record per row, we will consolidate $update_list by
                             * scenarioid and update in bulk. Else, it may timeout or just die from too much memory
                             * usage.
                             ***************************************************************************************/

                            $json_arr = json_decode($json, TRUE);
                            $prod_margin = $json_arr["get_margin"];
                            $json = null;
                            $eu_array = array("GB", "DE", "PL", "UA");

                            // if (( ($git - $pending_qty) >= 4)|| ($surplus_quantity !== null && $surplus_quantity !== 0 && (($surplus_quantity < 4 && $slow_move_7_days == 'Y') || $surplus_quantity >= 4)))
                            if ((($git - $pending_qty) >= 4) || ($surplus_quantity !== null && $surplus_quantity !== 0 && ($slow_move_7_days == 'Y' || $surplus_quantity >= 4))) {
                                # has surplus; scenario = Fast
                                ## if surplus < 5, check if slow moving in past 7 days
                                $scenarioid = 4;
                                $scenarioname = "Fast";
                            } else {


                                if ($supplier_status == 'C' || $supplier_status == 'O') {
                                    # stock constraint or temp. OOS; scenario = Slow
                                    $scenarioid = 3;
                                    $scenarioname = "Slow";
                                } else {
                                    if ($origin_country == 'US') {
                                        # supplier country is US; scenario = US
                                        $scenarioid = 2;
                                        $scenarioname = "US";
                                    } elseif ($origin_country == 'C1' || $origin_country == 'C2') {
                                        # supplier country is CN; scenario = CN
                                        $scenarioid = 6;
                                        $scenarioname = "CN";
                                    } elseif (in_array($origin_country, $eu_array) !== FALSE) {
                                        # supplier country is EU; scenario = EU
                                        $scenarioid = 7;
                                        $scenarioname = "EU";
                                    } else {
                                        if ($prod_margin >= $dt_highmargin) {
                                            # stock constraint or temp. OOS; scenario = HighMargin
                                            $scenarioid = 5;
                                            $scenarioname = "HighMargin";
                                        } else {
                                            # all above conditions not met; scenario = Default
                                            $scenarioid = 1;
                                            $scenarioname = "Default";
                                        }
                                    }
                                }
                            }

                            // store each SKU info for update later
                            $update_list[$scenarioid] .= "'$sku',";
                        }

                        $debug_msg .= "<br>$platform_id - $sku - scenario $scenarioid ($scenarioname)";
                        if ($i % 1000 == 0 || $i == $count) {
                            // update the list of SKUs in bulk
                            if (($result = $this->deliverytime_service->bulk_update_delivery_scenario($platform_id, $update_list)) === false) {
                                // error message
                                $debug_msg .= $this->error_msg;
                            }
                            $update_list = array();
                        }

                        $i++;
                    }
                } else {
                    $error_msg = __FILE__ . " Line " . __LINE__ . " \nCannot get product list for $platform_id. All products on this platform will not have delivery scenarios updated. \nDB error: " . $this->db->_error_message();
                    $this->send_notification_email("retrieve_error", $error_msg);
                    $debug_msg .= "<br>ERROR. $error_msg";
                    continue;
                }

                $debug_msg .= "<br>END PLATFORM $platform_id. Total: $i <hr></hr>";
                echo $debug_msg;
            }
        } else {
            $error_msg = __FILE__ . " Line " . __LINE__ . " \nCannot get selling platforms list. No products updated. \nDB error: " . $this->db->_error_message();
            $this->send_notification_email("retrieve_error", $error_msg);
            echo $debug_msg;
        }
    }

    private function send_notification_email($error_type, $error_msg = "")
    {
        include_once(BASEPATH . "plugins/phpmailer/phpmailer_pi.php");
        $phpmail = new phpmailer();
        $phpmail->IsSMTP();
        $phpmail->From = "Admin <admin@valuebasket.net>";

        switch ($error_type) {
            case "retrieve_error":
                $message = $error_msg;
                $title = "UPDATE DELIVERY SCENARIO RETRIEVE_ERROR - [{$this->platform_id}] Problem retrieving data.";
                break;

            case "warning":
                $message = $error_msg;
                $title = "UPDATE DELIVERY SCENARIO WARNING - [{$this->platform_id}] Empty time frames for platform.";
                break;

            case "update_fail":
                $message = $error_msg;
                $title = "UPDATE DELIVERY SCENARIO FAILED - [{$this->platform_id}] Error updating delivery scenario.";
                break;

        }

        $phpmail->AddAddress("itsupport@eservicesgroup.net");

        $phpmail->Subject = "$title";
        $phpmail->IsHTML(false);
        $phpmail->Body = $message;

        if (strpos($_SERVER["HTTP_HOST"], 'dev') === false)
            $result = $phpmail->Send();

    }


    public function getAppId()
    {
        return $this->appId;
    }

}