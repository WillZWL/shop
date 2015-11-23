<?php
DEFINE("PLATFORM_TYPE", "WEBSITE");

class Pricing_tool_website extends MY_Controller
{

    public $tool_path;
    public $default_platform_id;

    //must set to public for view
    private $appId = 'MKT0043';
    private $lang_id = 'en';

    public function __construct()
    {
        parent::__construct();

        // $this->load->model('integration/integration_model');
        $this->tool_path = 'marketing/pricing_tool_' . strtolower(PLATFORM_TYPE);
        $this->load->helper(array('url', 'notice', 'image'));
        $this->load->library('input');
        $this->load->model($this->tool_path . '_model', 'pricing_tool_model');
        $this->load->model('marketing/product_model');
        $this->load->library('service/pagination_service');
        $this->load->library('service/context_config_service');
        $this->load->library('service/display_qty_service');
        $this->load->library('service/wms_inventory_service');
        $this->load->library('service/inventory_service');
        $this->load->library('service/sku_mapping_service');

        $this->load->library('service/selling_platform_service');
        $this->load->library('service/affiliate_service');
        $this->load->library('service/affiliate_sku_platform_service');
        $this->load->library('service/price_margin_service');
        $this->load->library('service/adwords_service');
        $this->load->library('service/ext_category_mapping_service');
        $this->load->library('service/product_identifier_service');
        $this->load->library('service/product_update_followup_service');
        $this->load->library('service/deliverytime_service');
        $this->load->library('dao/competitor_map_dao');

        $this->default_platform_id = $this->sc['ContextConfig']->valueOf("default_platform_id");
    }

    public function index()
    {
        $data = [];
        include_once APPPATH . "language/" . $this->getAppId() . "00_" . $this->getLangId() . ".php";
        $data["lang"] = $lang;
        $this->load->view($this->tool_path . "/pricing_tool_index", $data);
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function bulk_list_post()
    {
        $sku_list = explode("\n", $this->input->post("sku_list"));
        // remove the extra
        $sku_list = array_filter($sku_list);


        foreach ($sku_list as $sku) {
            if ($platform_list = $this->sc['PlatformBizVar']->getDao('PlatformBizVar')->getPricingToolPlatformList($sku, PLATFORM_TYPE)) {
                foreach ($platform_list as $platform_obj) {
                    $platform_id = $platform_obj->getSellingPlatformId();
                    $json = $this->get_profit_margin_json($platform_id, $sku, 0, -1, "WEBSITE", true);
                    $m = json_decode($json, TRUE);
                    $fail_reason = "";
                    if ($m["getMargin"] == 0) $fail_reason .= "Margin is 0%, ";
                    if ($platform_id == "TMNZ") $fail_reason .= "TMNZ to be omitted, SBF#3308";
                    if ($platform_id == "LAMY") $fail_reason .= "LAMY to be omitted, SBF#3308";
                    $price = $m["get_price"];

                    if ($fail_reason == "") {
                        $price_obj = $this->sc['Price']->getDao('Price')->get(["sku" => $sku, "platform_id" => $platform_id]);
                        if (!$price_obj) {
                            $price_obj = $this->sc['Price']->getDao('Price')->get();
                            $type = "add";

                            $price_obj->set_platform_id($platform_id);
                            $price_obj->set_sku($sku);
                            // $price_obj->set_status($this->input->post('status'));
                            // $price_obj->set_ext_mapping_code($this->input->post('ext_mapping_code'));
                            $price_obj->set_listing_status("L");
                            $price_obj->set_price($price);

                            // $price_obj->set_allow_express($ae);
                            // $price_obj->set_is_advertised($ia);
                            $price_obj->set_auto_price("Y");
                            $price_obj->set_fixed_rrp("Y");
                            // $price_obj->set_max_order_qty($this->input->post('max_order_qty'));

                            if (is_null($default_rrp_factor)) {
                                $default_rrp_factor = $this->pricing_tool_model->get_rrp_factor_by_sku($price_obj->get_sku());
                            }
                            $price_obj->set_rrp_factor($default_rrp_factor);

                            $ret = $this->pricing_tool_model->add($price_obj);
                        } else {
                            $price_obj->set_listing_status("L");
                            $price_obj->set_auto_price("Y");
                            $price_obj->set_price($price);
                            $ret = $this->pricing_tool_model->update($price_obj);
                            $default_rrp_factor = $price_obj->get_rrp_factor();
                        }

                        // success, so update these as well SBF#3357
                        $prod_obj = $this->product_model->get('product', array('sku' => $sku));
                        $prod_obj->set_website_quantity(20);
                        $prod_obj->set_website_status('I');
                        $ret = $this->product_model->update("product", $prod_obj);
                    }

                    if ($fail_reason == "")
                        $msg .= "SUCCESS: Selling $sku @ {$m["get_price"]} on $platform_id (instock, website_qty={$prod_obj->get_website_quantity()})<br>\r\n";
                    else
                        $msg .= "FAILED: $sku $platform_id, $fail_reason<br>\r\n";
                }
            }
            // $this->processPostedData($line, false, true, true);
        }

        header("Content-Type: text/html");
        echo "<html><head></head><body>";
        echo "<a href='/marketing/pricing_tool_website/bulk_list'>Return to pricing tool</a><br><br>$msg";

        die();
    }

    public function get_profit_margin_json($platform_id, $sku, $required_selling_price = 0, $required_cost_price = -1, $platform_type = "WEBSITE", $return_json = false)
    {
        // if user input master sku, then convert it to local
        $sku = strtoupper($sku);
        if ($sku[0] == 'M') {
            $sku = substr($sku, 1);
            $sku = $this->sc['SkuMapping']->getLocalSku($sku);
        }

        $price_list = $this->sc['Price']->getDao('Price')->getPlatformPriceList(array("sp.type" => $platform_type, "sku" => $sku, "platform_id" => $platform_id));

        header('Content-type: application/json');
        if ($price_list) {
            foreach ($price_list AS $price_obj) {
                $json = $this->sc['Price']->getProfitMarginJson($price_obj->getPlatformId(), $price_obj->getSku(), $required_selling_price, $required_cost_price);
                if ($return_json) return $json; else echo $json;
            }
        } else {
            # this will get called if an unpriced item is passed in
            $json = $this->sc['Price']->getProfitMarginJson($platform_id, $sku, $required_selling_price, $required_cost_price);
            if ($return_json) return $json; else echo $json;
        }
    }

    public function bulk_list()
    {
        $data = [];
        include_once APPPATH . "language/" . $this->getAppId() . "00_" . $this->getLangId() . ".php";
        $data["lang"] = $lang;
        $this->load->view($this->tool_path . "/pricing_tool_bulk_list", $data);
    }

    public function plist($offset = 0)
    {
        $where = [];
        $option = [];
        $sub_app_id = $this->getAppId() . "02";
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->getLangId() . ".php");
        $data["lang"] = $lang;

        $sku = $this->input->get("sku");
        $prod_name = $this->input->get("name");
        $master_sku = $this->input->get("master_sku");

        if ($sku != "" || $prod_name != "" || $master_sku != "") {
            $data["search"] = 1;
            if ($sku != "") {
                $where["sku"] = $sku;
            }

            if ($master_sku != "") {
                $where['master_sku'] = $master_sku;
            }

            if ($prod_name != "") {
                $where["name"] = $prod_name;
            }

            $sort = $this->input->get("sort");
            $order = $this->input->get("order");

            $limit = '20';

            $option["limit"] = $limit;

            $option["offset"] = $offset;

            if (empty($sort)) {
                $sort = "sku";
            }

            if (empty($order)) {
                $order = "asc";
            }

            $option["orderby"] = $sort . " " . $order;

            $option["exclude_bundle"] = 1;
            $data["objlist"] = $this->sc['Product']->getDao('Product')->getListWithName($where, $option);
            $data["total"] = $this->sc['Product']->getDao('Product')->getListWithName($where, array_merge(['num_rows'=>1], $option));

            $config['base_url'] = base_url('/marketing/pricing_tool_website/plist');
            $config['total_rows'] = $data["total"];
            $config['per_page'] = $limit;

            $this->pagination->initialize($config);
            $data['links'] = $this->pagination->create_links();

            $data["notice"] = notice($lang);

            $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
            $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
        }

        $this->load->view($this->tool_path . '/pricing_tool_list', $data);
    }

    public function view($value = "")
    {
        include_once(APPPATH . "helpers/simple_log_helper.php");

        if ($value == "") {
            exit;
        }

        $no_of_valid_supplier = $this->sc['Supplier']->checkValidSupplierCost($value);
        if ($no_of_valid_supplier == 1) {
            $data = [];
            $data["valid_supplier"] = 1;
            $data["prompt_notice"] = 0;
            $data["website_link"] = $this->sc['ContextConfig']->valueOf("website_domain");
            define('IMG_PH', $this->sc['ContextConfig']->valueOf("prod_img_path"));

            $this->processPostedData($value);

            $data["action"] = "update";
            if (empty($price_obj)) {
                $price_obj = $this->sc['Price']->getDao('Price')->get();
                $data["action"] = "add";
            }
            include_once APPPATH . "language/" . $this->getAppId() . "01_" . $this->getLangId() . ".php";
            $data["lang"] = $lang;
            $_SESSION["price_obj"] = serialize($price_obj);
            $data["canedit"] = 1;
            $data["value"] = $value;
            $data["target"] = $this->input->get('target');
            $data["notice"] = notice($lang);

            $data["inv"] = $this->sc['WmsInventory']->getInventoryList(["sku" => $value]);

            $tmpx[7] = $this->sc['So']->getDao('So')->getQuantityInOrders($value, 7);
            $tmpx[30] = $this->sc['So']->getDao('So')->getQuantityInOrders($value, 30);

            foreach ($tmpx as $key => $val) {
                $data["qty_in_orders"] .= $lang["last"] . " " . $key . " " . $lang["days"] . " : " . $val . "<br>";
            }

            if ($delivery_scenario_list = $this->sc['DeliveryTime']->getDeliveryScenarioList()) {
                foreach ($delivery_scenario_list as $key => $obj) {
                    $scenario[$obj->id] = $obj->name;
                }
            }

            $data["qty_in_orders"] = ereg_replace("<br>$", "", $data["qty_in_orders"]);
            $mapping_obj = $this->sc['Product']->getDao('SkuMapping')->get(['sku' => $value, 'ext_sys' => 'WMS', 'status' => 1]);
            if ($mapping_obj && trim($mapping_obj->getExtSku()) != "") {
                $data['master_sku'] = $mapping_obj->getExtSku();
            }
            $pdata = [];
            if ($value != "") {
                if ($platform_list = $this->sc['PlatformBizVar']->getDao('PlatformBizVar')->getPricingToolPlatformList($value, PLATFORM_TYPE)) {
                    foreach ($platform_list as $platform_obj) {
                        $platform_id = $platform_obj->getSellingPlatformId();
                        $platform_country_id = $platform_obj->getPlatformCountryId();
                        $language_id = $platform_obj->getLanguageId();

                        $pdata[$platform_id]["obj"] = $platform_obj;

                        // this is the part where we get the HTML from deep inside the code
                        $tmp = $this->sc['Price']->getPricingToolInfo($platform_id, $value, $this->getAppId());

                        $pdata[$platform_id]["pdata"] = $tmp;
                        $objcount++;
                        $price_obj = $this->sc['Price']->getDao('Price')->get(["sku" => $value, "platform_id" => $platform_id]);

                        $sub_cat_margin = $tmp["dst"]->getSubCatMargin();
                        $type = "update";
                        if (!$price_obj) {
                            $price_obj = $this->sc['Price']->getDao('Price')->get();
                            $type = "add";
                        }

                        $data["formtype"][$platform_id] = $type;
                        $data["sub_cat_margin"][$platform_id] = $sub_cat_margin;
                        $data["price_list"][$platform_id] = $price_obj;

                        #SBF #4020 - delivery scenarios to show different time frames on front end
                        $data["delivery_info"][$platform_id] = [];
                        $delivery_scenarioid = $price_obj->getDeliveryScenarioid();
                        $deliverytime_obj = $this->sc['DeliveryTime']->getDeliverytimeObj($platform_country_id, $delivery_scenarioid);
                        if (!empty($deliverytime_obj)) {
                            $data["delivery_info"][$platform_id]["scenarioid"] = $deliverytime_obj->getScenarioid();
                            $data["delivery_info"][$platform_id]["scenarioname"] = $scenario[$deliverytime_obj->getScenarioid()];
                            $data["delivery_info"][$platform_id]["del_min_day"] = $deliverytime_obj->getDelMinDay();
                            $data["delivery_info"][$platform_id]["del_max_day"] = $deliverytime_obj->getDelMaxDay();
                            $data["delivery_info"][$platform_id]["margin"] = $deliverytime_obj->getMargin();
                        }
                        $_SESSION["price_obj_" . $platform_id] = serialize($price_obj);

                        #SBF2814
                        if ($adwords_data_obj = $this->sc['Adwords']->getDao('AdwordsData')->get(["sku" => $value, "platform_id" => $platform_id])) {
                            $pdata[$platform_id]["adwords"] = $lang["adwords_exists"];
                            //display adGroup status
                            if ($adwords_data_obj->getStatus() == 0) {
                                $adwords_enabled = 0;
                                $adwords_status_str = "<span style='color:#FF0000;width:80%x; height:20px;overflow:hidden'>Paused</span>";
                            } else {
                                $adwords_enabled = 1;
                                $adwords_status_str = "<span style='color:#00FF00;width:80%x; height:20px;overflow:hidden'>Enabled</span>";
                            }

                            if ($adwords_data_obj->getApiRequestResult() == 1) {
                                $api_result = "<span style='color:#00FF00;width:80%x; height:20px;overflow:hidden'>Success</span>";
                                $pdata[$platform_id]["adGroup_status"] = $api_result . ' - ' . $adwords_status_str;
                            } else {
                                $api_result = "<span style='color:#FF0000;width:80%x; height:20px;overflow:hidden'>Fail   <a href='javascript: void(0)' onclick=\"showHide_with_eleid('adGrouperror[$platform_id][$value]')\">[view error]</a></span>";
                                $pdata[$platform_id]["adGroup_error_row"] = "<tr><td colspan='4' class='value'><div id='adGrouperror[$platform_id][$value]' style='display:none;color:#000'><b>AdGroup Error:</b> " . $adwords_data_obj->getComment() . "</div></td></tr>";
                                $pdata[$platform_id]["adGroup_status"] = $api_result;
                            }
                        } else {
                            //if adGroup not exists before, then default is enabled.
                            $pdata[$platform_id]["adwords"] = "<input id='google_adwords[{$platform_id}]' type='checkbox' name='google_adwords[{$platform_id}]'>";
                            $pdata[$platform_id]["adGroup_status"] = "";
                        }

                        //check the mpn
                        list($prod_grp_cd, $version_id, $colour_id) = explode("-", $value);

                        $internal_gsc_comment = "";
                        if (!$prod_identifer_obj = $this->sc['ProductIdentifier']->getDao('ProductIdentifier')->get(["prod_grp_cd" => $prod_grp_cd, "colour_id" => $colour_id, "country_id" => $platform_country_id])) {
                            $internal_gsc_comment = "No mpn value. ";
                        } else {
                            if (!$prod_identifer_obj->getMpn()) {
                                $internal_gsc_comment = "No mpn value. ";
                            }
                        }

                        if ($prod_obj = $this->sc['Product']->getDao('Product')->get(['sku' => $value])) {
                            if ($prod_obj->getStatus() != 2) {
                                $internal_gsc_comment .= "/Product is not listed in product Mgmt. ";
                            }
                        }

                        if ($product_content_obj = $this->sc['Product']->getDao('ProductContent')->get(["prod_sku" => $value, "lang_id" => $language_id])) {
                            if (!$product_content_obj->getDetailDesc()) {
                                $internal_gsc_comment .= "/No detail desc. ";
                            }
                        }

                        $gsc_where = $gsc_option = [];
                        $gsc_where['cm.id'] = $value;
                        $gsc_where['cm.ext_party'] = "GOOGLEBASE";
                        $gsc_where['cm.country_id'] = $platform_country_id;
                        $gsc_where['cm.status'] = 1;
                        $gsc_option['limit'] = 1;

                        if (!$google_cat_obj = $this->sc['CategoryMapping']->getDao('CategoryMapping')->getGooglebaseCatListWithCountry($gsc_where, $gsc_option)) {
                            $internal_gsc_comment .= " No google product title/category. ";
                        } else {
                            if (!$google_cat_obj->getProductName()) {
                                $internal_gsc_comment .= " No google product title. ";
                            }

                            if (!$google_cat_obj->getExtName()) {
                                $internal_gsc_comment .= " No google category.";
                            }
                        }


                        $enabled_pla_checkbox = $internal_gsc_comment ? 0 : 1;

                        $gsc_comment = "";
                        if ($google_shopping_obj = $this->sc['Product']->getDao('GoogleShopping')->get(["sku" => $value, "platform_id" => $platform_id])) {
                            //result: 0 - fail, 1 - success
                            $google_shopping_result = $google_shopping_obj->getApiRequestResult();
                            if ($google_shopping_obj->getStatus() == 0) {
                                $gsc_comment = "PAUSE";
                                if ($google_shopping_result == 0) {
                                    $gsc_comment .= " - Fail";
                                } else {
                                    $gsc_comment .= " - Success";
                                }
                            } else {
                                $pdata[$platform_id]["gsc_request_result"] = $google_shopping_result;
                                if (!$google_shopping_result || $price_obj->getIsAdvertised() != "Y") {
                                    // If previous fail api OR is not advertised, we check if it fulfills the above conditions before enabling (e.g. google cat mapping, mpn, etc)
                                    $gsc_comment = $google_shopping_obj->getComment();
                                    if (!$gsc_comment) {
                                        //get internal failed reason if no gsc_comment
                                        $gsc_comment = $internal_gsc_comment;
                                    } else {
                                        $gsc_temp_list = explode(';', $gsc_comment);
                                        $gsc_comment = array_pop($gsc_temp_list);
                                    }

                                    # if have internal reason, means criteria not fulfilled to be listed
                                    if (!$internal_gsc_comment)
                                        $enabled_pla_checkbox = 1;
                                } else {
                                    $gsc_comment = "Success";
                                }
                            }
                        }
                        if ($internal_gsc_comment) $gsc_comment = $internal_gsc_comment . "<br>" . $gsc_comment;

                        $pdata[$platform_id]["gsc_comment"] = $gsc_comment ? $gsc_comment : $internal_gsc_comment;
                        $pdata[$platform_id]["enabled_pla_checkbox"] = $enabled_pla_checkbox;

                        $current_platform_price = $tmp["dst"]->getCurrentPlatformPrice();

                        if ($data["master_sku"]) {
                            # get list of Active competitor mapping
                            $comp_mapping_list = $this->sc['CompetitorMap']->getDao('CompetitorMap')->getActiveComp($data['master_sku'], $platform_country_id);

                            if ($comp_mapping_list) {
                                # if set to competitor reprice, then we need to check if there's at least ONE competitor with active match
                                # front end will show message if $hasactivematch = 0
                                if ($price_obj->getAutoPrice() == "C")
                                    $hasactivematch = 0;
                                else
                                    $hasactivematch = 1;

                                $pdata[$platform_id]["competitor"]["comp_mapping_list"] = $comp_mapping_list;
                                $pdata[$platform_id]["competitor"]["price_diff"] = [];
                                foreach ($comp_mapping_list as $row) {
                                    $ship_charge = $row->getCompShipCharge();
                                    if (empty($ship_charge)) {
                                        $ship_charge = 0;
                                    }

                                    # current competitor's selling price
                                    $pdata[$platform_id]["competitor"]["total_price"] = number_format(($row->getNowPrice() + $ship_charge), 2, '.', '');

                                    #price difference between vb and competitor
                                    $pdata[$platform_id]["competitor"]["price_diff"][$row->getCompetitorId()] = number_format(($pdata[$platform_id]["competitor"]["total_price"] - $current_platform_price), 2);

                                    # flag to check if all competitors on this platform has IGNORE match
                                    if ($row->getMatch() == 1)
                                        $hasactivematch++;

                                }
                                $pdata[$platform_id]["competitor"]["hasactivematch"] = $hasactivematch;

                                # sort the competitors by price difference between their selling price and VB
                                asort($pdata[$platform_id]["competitor"]["price_diff"]);
                            }
                        }

                        #sbf #3959 - show always include & always exclude list
                        $data["feed_include"][$platform_id] = $this->sc['Product']->getDao('AffiliateSkuPlatform')->getFeedListBySku($value, $platform_id, 2);
                        $data["feed_exclude"][$platform_id] = $this->sc['Product']->getDao('AffiliateSkuPlatform')->getFeedListBySku($value, $platform_id, 1);

                    }
                }
                // echo"<pre>"; var_dump($pdata["WEBES"]["competitor"]);die();

                $data["pdata"] = $pdata;
                $data["objcount"] = $objcount;
                $data["mkt_note_obj"] = $this->sc['Product']->getDao('ProductNote')->getNoteWithAuthorName("WSGB", $value, "M");
                $data["src_note_obj"] = $this->sc['Product']->getDao('ProductNote')->getNoteWithAuthorName(null, $value, "S");
                $data["value"] = $value;
                $prod_obj = $this->sc['Product']->getDao('Product')->get(['sku'=>$value]);
            }

            if ($prod_obj != null) {
                $data["surplus_qty"] = $prod_obj->getSurplusQuantity();
                $data["slow_move"] = $prod_obj->getSlowMove7Days();
            }
            $data["prod_obj"] = $prod_obj;
            $data["supplier"] = $this->sc['Product']->getDao('Product')->getCurrentSupplier($value);
            $data["num_of_supplier"] = $this->sc['Product']->getDao('Product')->getTotalDefaultSupplier($value);
            if ($t = $this->sc['FreightCat']->getDao('FreightCategory')->get(['id'=>$prod_obj->getFreightCatId()])) {
                $data["freight_cat"] = $t->getName();
            }

            unset($t);
            $_SESSION["prod_obj"] = serialize($prod_obj);
        }

        $this->load->view($this->tool_path . "/pricing_tool_view", $data);
    }

    private function processPostedData($sku, $redirect = true, $force_auto_price = false, $force_list = false)
    {
        if ($this->input->post('posted')) {
            $plat = $this->input->post('selling_platform');
            $splist = $this->input->post("selling_price");
            $pae = $this->input->post('allow_express');
            $pia = $this->input->post('is_advertised');
            $pap = $this->input->post('auto_price');
            $pft = $this->input->post('formtype');
            $pls = $this->input->post('listing_status');
            $pfrrp = $this->input->post('fixed_rrp');
            $prrp_factor = $this->input->post('rrp_factor');

            $hidden_profit = $this->input->post("hidden_profit");
            $hidden_margin = $this->input->post("hidden_margin");

            foreach ($plat as $key => $val) {
                $sp = $splist[$key];
                $cur_listing_status = $pls[$key];
                if ($pae[$key]) $ae = 'Y'; else $ae = 'N';
                if ($pia[$key]) $ia = 'Y'; else $ia = 'N';
                if (!$pap[$key]) $ap = 'N'; else $ap = $pap[$key];
                if ($pfrrp[$key] == 'N') $frrp = 'N'; else $frrp = 'Y';
                $rrp_factor = trim($prrp_factor[$key]);

                if ($force_auto_price) $ap = "Y";
                if ($force_list) $cur_listing_status = "L";

                $this->pricing_tool_model->__autoload();
                $price_obj = unserialize($_SESSION["price_obj_" . $key]);

                if ($price_obj->get_price() * 1 != $sp * 1 ||
                    $price_obj->get_listing_status() != $cur_listing_status ||
                    $price_obj->get_allow_express() != $ae ||
                    $price_obj->get_is_advertised() != $ia ||
                    $price_obj->get_auto_price() != $ap ||
                    $price_obj->get_fixed_rrp() != $frrp ||
                    (($frrp == 'N') && ($rrp_factor != '') && ($price_obj->get_rrp_factor() != $rrp_factor))
                ) {
                    $price_obj->set_platform_id($key);
                    $price_obj->set_sku($sku);
                    $price_obj->set_status($this->input->post('status'));
                    $price_obj->set_ext_mapping_code($this->input->post('ext_mapping_code'));
                    $price_obj->set_listing_status($cur_listing_status);
                    $sp = $splist[$key];
                    $price_obj->set_price($sp);

                    $price_obj->set_allow_express($ae);
                    $price_obj->set_is_advertised($ia);
                    $price_obj->set_auto_price($ap);
                    $price_obj->set_fixed_rrp($frrp);
                    $price_obj->set_max_order_qty($this->input->post('max_order_qty'));

                    if (($frrp == 'N') && ($rrp_factor != '')) {
                        $price_obj->set_rrp_factor($rrp_factor);
                    }

                    if (is_null($price_obj->get_rrp_factor())) {
                        $price_obj->set_rrp_factor($this->pricing_tool_model->get_rrp_factor_by_sku($price_obj->get_sku()));
                    }

                    if ($pft[$key] == "update") {
                        $ret = $this->pricing_tool_model->update($price_obj);
                    } else {
                        $ret = $this->pricing_tool_model->add($price_obj);
                    }

                    if ($ret === FALSE) {
                        $_SESSION["NOTICE"] = "update_failed " . $this->db->_error_message();
                    } else {
                        unset($_SESSION["price_obj_" . $key]);
                        if ($this->input->post('target') != "") {
                            $data["prompt_notice"] = 1;
                        }
                    }
                }

                #2652 only if the price change, then insert or update on duplicate
                $profit = $hidden_profit[$key];
                $margin = $hidden_margin[$key];

                //Comment: $sku = sku, $key = platform_id, $sp = selling_price
                $this->price_margin_service->insert_or_update_margin($sku, $key, $sp, $profit, $margin);
            }
            $this->pricing_tool_model->__autoload_product_vo();
            $prod_obj = $this->product_model->get('product', array('sku' => $sku));

            $prod_obj->set_clearance($this->input->post('clearance'));
            $prod_obj->set_website_quantity($this->input->post('webqty'));
            if ($this->input->post('webqty') && ($this->input->post('webqty') != $prev_webqty)) {
                include_once(APPPATH . "libraries/dao/product_dao.php");
                $prod_dao = new Product_dao();
                $vpo_where = array("vpo.sku" => $prod_obj->get_sku());
                $vpo_option = array("to_currency_id" => "GBP", "orderby" => "vpo.price > 0 DESC, vpo.platform_currency_id = 'GBP' DESC, vpo.price *  er.rate DESC", "limit" => 1);
                if ($vpo_obj = $prod_dao->get_prod_overview_wo_shiptype($vpo_where, $vpo_option)) {
                    $display_qty = $this->display_qty_service->calc_display_qty($vpo_obj->get_cat_id(), $vpo_obj->get_website_quantity(), $vpo_obj->get_price());
                    $prod_obj->set_display_quantity($display_qty);
                }
            }
            if ($this->input->post('webqty') == 0) {
                $prod_obj->set_website_status('O');
            } else {
                $prod_obj->set_website_status($this->input->post('status'));
            }

            $ret2 = $this->pricing_tool_model->update_product($prod_obj);

            if ($ret === FALSE) {
                $_SESSION["NOTICE"] = "update_failed";
            } else {
                unset($_SESSION["prod_obj"]);
            }

            if (trim($this->input->post('m_note')) != "") {
                $note_obj = $this->pricing_tool_model->get_note();
                $note_obj->set_sku($sku);
                $note_obj->set_type('M');
                $note_obj->set_note($this->input->post('m_note'));
                if (!($ret = $this->pricing_tool_model->add_note($note_obj))) {
                    $_SESSION["NOTICE"] = "update_note_failed";
                }
            }

            if (trim($this->input->post('s_note')) != "") {
                $note_obj = $this->pricing_tool_model->get_note();
                $note_obj->set_sku($sku);
                $note_obj->set_type('S');
                $note_obj->set_note($this->input->post('s_note'));
                if (!($ret = $this->pricing_tool_model->add_note($note_obj))) {
                    $_SESSION["NOTICE"] = "update_note_failed";
                }
            }

            $this->product_update_followup_service->google_shopping_update($sku);
            $google_adwords_target_platform_list = $this->input->post('google_adwords');
            //$adGroup_status = $this->input->post('adGroup_status');
            $this->product_update_followup_service->adwords_update($sku, $google_adwords_target_platform_list);
            if ($redirect)
                Redirect(base_url() . $this->tool_path . "/view/" . $sku);
        }

        return null;
    }

    public function manage_feed($sku, $platform_id)
    {
        $list = $this->affiliate_sku_platform_service->get_sku_feed_status($sku, $platform_id);
        $html = <<<header
        <html>
        <head>
        <script src="/js/jquery.js"></script>
        </head>
        <body>
        <script>
            function submit(radio)
            {
                url = '/marketing/pricing_tool_website/set_sku_feed_status_json/' + radio.name + '/$sku/$platform_id/' + radio.value;
                $.ajax
                (
                    {
                        type: "GET",
                        url: url,
                        dataType: "json"
                    }
                );
            }
        </script>
        <b>$sku for $platform_id</b>
        <hr>
        <table>
header;
        $c = 1;
        $prev_checked = -1;
        foreach ($list as $item) {
            $box[0] = "";
            $box[1] = "";
            $box[2] = "";
            $box[$item['status']] = "checked";

            $checked = $item['chk'];
            if ($prev_checked == -1) $prev_checked = $checked;
            if ($checked != $prev_checked) $html .= "</table><hr><table>";
            $prev_checked = $checked;
            // <td><input type="checkbox" name="feed{$item['id']}" value="check" $checked onClick="submit(this);"></td>

            $html .= <<<asdf
                <tr>

                    <td>{$c}</td>
                    <td>{$item['id']}</td>
                    <td><input type="radio" name="{$item['id']}" value="0" $box[0] onClick="submit(this);">Auto</td>
                    <td><input type="radio" name="{$item['id']}" value="1" $box[1] onClick="submit(this);">Exclude</td>
                    <td><input type="radio" name="{$item['id']}" value="2" $box[2] onClick="submit(this);">Include</td>
                </tr>
asdf;
            $c++;
        }
        echo $html . "</table>";
    }

    public function manage_feed_platform()
    {
        $option["limit"] = -1;
        $result = $this->selling_platform_service->get_dao()->get_list(array(), $option);
        foreach ($result as $dbrow)
            $platform_list[] = $dbrow->get_id();

        $current_mapping = $this->affiliate_service->get_feed_platform();

        $html = <<<header
        <html>
        <head>
        <script src="/js/jquery.js"></script>
        </head>
        <body>
        <script>
            function submit(option)
            {
                url = '/marketing/pricing_tool_website/set_feed_platform_json/' + option.value;
                $.ajax
                (
                    {
                        type: "GET",
                        url: url,
                        dataType: "json"
                    }
                );
            }
        </script>
        <b>Mapping for feeds</b>
        <hr>
        <table>
header;
        $c = 1;
        $prev_checked = -1;
        foreach ($current_mapping as $item) {
            $box[0] = "";
            $box[1] = "";
            $box[2] = "";
            $box[$item['status']] = "checked";

            $checked = $item['chk'];
            if ($prev_checked == -1) $prev_checked = $checked;
            if ($checked != $prev_checked) $html .= "</table><hr><table>";
            $prev_checked = $checked;
            // <td><input type="checkbox" name="feed{$item['id']}" value="check" $checked onClick="submit(this);"></td>


            $dropdown = "";
            foreach ($platform_list as $platform) {
                $selected = "";
                if ($item["platform_id"] == $platform) $selected = "selected='selected'";
                $dropdown .= <<<dropdown_item
                    <option onClick="submit(this);" value="{$item["id"]}/$platform" $selected>$platform</option>
dropdown_item;
            }

            $html .= <<<asdf
                <tr>

                    <td>{$c}</td>
                    <td>{$item['id']}</td>
                    <td>
                        <select>
                            <option onClick="submit(this);" value="{$item["id"]}">---</option>
                            $dropdown
                        </select>
                    </td>
                </tr>
asdf;
            $c++;
        }
        echo $html . "</table>";
    }

    public function set_feed_platform_json($affiliate_id, $platform_id = "")
    {
        echo json_encode($this->sc['Affiliate']->getDao('Affiliate')->setFeedPlatform($affiliate_id, $platform_id));
    }

    public function get_feed_platform_json()
    {
        echo json_encode($this->affiliate_service->get_feed_platform());
    }

    public function set_sku_feed_status_json($sku, $platform_id, $affiliate_id, $status_id)
    {
        echo json_encode($this->affiliate_sku_platform_service->set_sku_feed_status($sku, $platform_id, $affiliate_id, $status_id));
    }

    public function get_sku_feed_status_json($sku, $platform_id)
    {
        echo json_encode($this->affiliate_sku_platform_service->get_sku_feed_status($sku, $platform_id));
    }

    public function get_js()
    {
        $this->pricing_tool_model->print_pricing_tool_js();
    }

    public function auto_pricing_by_platform($platform_id)
    {
        $this->price_service->auto_pricing_by_platform($platform_id);
        echo "All $platform_id SKUs marked for auto-price have been updated.";
    }
}