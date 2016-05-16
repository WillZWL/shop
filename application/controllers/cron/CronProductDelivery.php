<?php

class CronProductDelivery extends MY_Controller
{
    private $appId = "CRN0024";

    public function __construct()
    {
        parent::__construct();
    }

    public function updateProductDeliveryScenario($platform_id, $platform_type = "WEBSITE", $debug = false)
    {
        DEFINE("PLATFORM_TYPE", strtoupper($platform_type));
        $platform_id = strtoupper($platform_id);
        $debug_msg = "";
        if ($allow_sell_list = $this->sc['SellingPlatform']->getPlatformListWithAllowSellCountry($platform_type)) {
            foreach ($allow_sell_list as $allowsellobj) {
                if ($platform_id !== strtoupper($allowsellobj->getSellingPlatformId())) {
                    continue;
                } else {
                    $this->platform_id = $platform_id;
                    $country_id = $allowsellobj->getCountryId();
                }
                set_time_limit(0);
                $debug_msg = "<hr></hr><br>START PLATFORM $platform_id";
                if ($highmargin_dt_obj = $this->sc['DeliveryTime']->getDeliverytimeObj($country_id, 5)) {
                    if (($dt_highmargin = $highmargin_dt_obj->getMargin()) == "") {
                        $error_msg = __FILE__ . " Line " . __LINE__ . " \nHighMargin scenario has no margin input for platform_id<$platform_id>. All products listed in this platform will not have delivery scenarios updated.";
                        $debug_msg .= "<br>ERROR. $error_msg";
                        $this->sendNotificationEmail("warning", $error_msg);
                        continue;
                    }
                } else {
                    $error_msg = __FILE__ . " Line " . __LINE__ . " \nHighMargin scenario has no time frame input for platform_id<$platform_id>. All products listed in this platform will not have delivery scenarios updated.";
                    $debug_msg .= "<br>ERROR. $error_msg";
                    $this->sendNotificationEmail("warning", $error_msg);
                    continue;
                }

                $where = array(
                    "platform_id" => $platform_id,
                    "supplier_status != 'D'" => null
                );

                if ($objlist = $this->sc['Product']->getDao('Product')->getListedProductSupplierInfo($where, array('limit'=>-1))) {
                    $i = 1;
                    $count = count((array)$objlist);
                    $update_list = array();
                    foreach ($objlist as $prod_info) {
                        $sku = $prod_info->getSku();
                        $price = $prod_info->getPrice();
                        $name = $prod_info->getName();
                        # !!!! This line takes up large amount of resources
                        if (($json = $this->sc['Price']->getProfitMarginJson($platform_id, $sku, $price)) === FALSE) {
                            $error_msg = __FILE__ . " Line " . __LINE__ . " \nCannot get profit margin json for platform_id<$platform_id> - SKU<$sku>. \nDB error: " . $this->db->_error_message();
                            $this->sendNotificationEmail("retrieve_error", $error_msg);
                            $debug_msg .= "<br>ERROR. $error_msg";
                            continue;
                        } else {
                            $json_arr = json_decode($json, TRUE);
                            $prod_margin = $json_arr["get_margin"];
                            $json = null;
                            $scenarioi_data = $this->getScenarioidDataByProdInfo($sku, $prod_info, $prod_margin);
                            $scenarioid = $scenarioi_data['id'];
                            $scenarioname = $scenarioi_data['name'];
                            // store each SKU info for update later
                            $update_list[$scenarioid] .= "'$sku',";
                        }

                        $debug_msg .= "<br>$platform_id - $sku - scenario $scenarioid ($scenarioname)";

                        if ($i % 1000 == 0 || $i == $count) {
                            if (($result = $this->sc['DeliveryTime']->bulkUpdateDeliveryScenario($platform_id, $update_list)) === false) {
                                $debug_msg .= $this->error_msg;
                            }
                            $update_list = array();
                        }
                        $i++;
                    }
                } else {
                    $error_msg = __FILE__ . " Line " . __LINE__ . " \nCannot get product list for $platform_id. All products on this platform will not have delivery scenarios updated. \nDB error: " . $this->db->_error_message();
                    $this->sendNotificationEmail("retrieve_error", $error_msg);
                    $debug_msg .= "<br>ERROR. $error_msg";
                    continue;
                }
                $debug_msg .= "<br>END PLATFORM $platform_id. Total: $i <hr></hr>";
                $headers = "From: admin@valuebasket.com\r\n";
                $message = "Delivery timeframe triggered for platform_id: $platform_id. Generated @ GMT 0 ".date("Y-m-d H:i:s");
                mail("bd@eservicesgroup.net", "[Panther] $platform_id - Delivery timeframe triggerd", $message, $headers);
            }
        } else {
            $error_msg = __FILE__ . " Line " . __LINE__ . " \nCannot get selling platforms list. No products updated. \nDB error: " . $this->db->_error_message();
            $this->sendNotificationEmail("retrieve_error", $error_msg);
        }

        if ($debug) {
            echo $debug_msg;
        }
    }

    private function getScenarioidDataByProdInfo($sku, $prod_info)
    {
        $sku = $prod_info->getSku();
        $price = $prod_info->getPrice();
        $name = $prod_info->getName();
        $surplus_quantity = $prod_info->getSurplusQuantity();
        $slow_move_7_days = $prod_info->getSlowMove7Days();
        $git = $prod_info->getGit();
        $supplier_id = $prod_info->getSupplierId();
        $supplier_status = $prod_info->getSupplierStatus();
        $supplier_name = $prod_info->getSupplierName();
        $sourcing_status = strtoupper($prod_info->getSourcingStatus());
        $origin_country = strtoupper($prod_info->getOriginCountry());

        $pending_orders = $this->sc['So']->getOrdersBySkuAndStatus($sku, 2, array(), array("num_row" => 1));
        $pending_qty = $pending_orders["total_qty"];
        $scenarioid = $scenarioname = "";

        $eu_array = array("GB", "DE", "PL", "UA");
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
        $data['id'] = $scenarioid;
        $data['name'] = $scenarioname;
        return $data;
    }

    private function sendNotificationEmail($error_type, $error_msg = "")
    {
        switch ($error_type) {
            case "retrieve_error":
                $title = "[Panther] UPDATE DELIVERY SCENARIO RETRIEVE_ERROR - [{$this->platform_id}] Problem retrieving data.";
                break;
            case "warning":
                $title = "[Panther] UPDATE DELIVERY SCENARIO WARNING - [{$this->platform_id}] Empty time frames for platform.";
                break;
            case "update_fail":
                $title = "[Panther] UPDATE DELIVERY SCENARIO FAILED - [{$this->platform_id}] Error updating delivery scenario.";
                break;
        }
        mail('will.zhang@eservicesgroup.com', $title, $error_msg);
    }

    public function getAppId()
    {
        return $this->appId;
    }

}