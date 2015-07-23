<?php
DEFINE("PLATFORM_TYPE", "RAKUTEN");

class Pricing_tool_rakuten extends MY_Controller
{

    public $tool_path;
    public $default_platform_id;

    //must set to public for view
    private $app_id = 'MKT0079';
    private $lang_id = 'en';

    public function __construct()
    {
        parent::__construct();

        $this->load->model('integration/integration_model');
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
        $this->load->library('service/rakuten_service');
        $this->load->library('service/price_margin_service');

        $this->default_platform_id = $this->context_config_service->value_of("default_platform_id");
    }

    public function index()
    {
        $data = array();
        include_once APPPATH . "language/" . $this->_get_app_id() . "00_" . $this->_get_lang_id() . ".php";
        $data["lang"] = $lang;
        $this->load->view($this->tool_path . "/pricing_tool_index", $data);
    }

    public function _get_app_id()
    {
        return $this->app_id;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }

    public function plist()
    {
        $where = array();
        $option = array();
        $sub_app_id = $this->_get_app_id() . "02";
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
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

            $pconfig['base_url'] = current_url() . "?" . $_SERVER['QUERY_STRING'];
            $option["limit"] = $pconfig['per_page'] = $limit;

            if ($option["limit"]) {
                $option["offset"] = $this->input->get("per_page");
            }

            if (empty($sort))
                $sort = "sku";

            if (empty($order))
                $order = "asc";

            $option["orderby"] = $sort . " " . $order;

            $option["exclude_bundle"] = 1;
            $data["objlist"] = $this->pricing_tool_model->get_product_list($where, $option);
            $data["total"] = $this->pricing_tool_model->get_product_list_total($where, $option);
            $pconfig['total_rows'] = $data['total'];
            $this->pagination_service->set_show_count_tag(TRUE);
            $this->pagination_service->msg_br = TRUE;
            $this->pagination_service->initialize($pconfig);

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

        $no_of_valid_supplier = $this->pricing_tool_model->check_valid_supplier_cost($value);
        if ($no_of_valid_supplier == 1) {
            if ($this->input->post('posted')) {
                $this->process_post_data($value);
            }

            $data = array();
            $data["valid_supplier"] = 1;
            $data["prompt_notice"] = 0;
            $data["website_link"] = $this->context_config_service->value_of("website_domain");
            define('IMG_PH', $this->context_config_service->value_of("prod_img_path"));


            $data["action"] = "update";
            if (empty($price_obj)) {
                $price_obj = $this->pricing_tool_model->get_price_obj();
                $data["action"] = "add";
            }
            include_once APPPATH . "language/" . $this->_get_app_id() . "01_" . $this->_get_lang_id() . ".php";
            $data["lang"] = $lang;
            $_SESSION["price_obj"] = serialize($price_obj);
            $data["canedit"] = 1;
            $data["value"] = $value;
            $data["target"] = $this->input->get('target');
            $data["notice"] = notice($lang);

            $data["inv"] = $this->wms_inventory_service->get_inventory_list(array("sku" => $value));

            $tmpx = $this->pricing_tool_model->get_quantity_in_orders($value);

            foreach ($tmpx as $key => $val) {
                $data["qty_in_orders"] .= $lang["last"] . " " . $key . " " . $lang["days"] . " : " . $val . "<br>";
            }

            if ($delivery_scenario_list = $this->deliverytime_service->get_delivery_scenario_list()) {
                foreach ($delivery_scenario_list as $key => $obj) {
                    $scenario[$obj->id] = $obj->name;
                }
            }

            $data["qty_in_orders"] = ereg_replace("<br>$", "", $data["qty_in_orders"]);
            $mapping_obj = $this->pricing_tool_model->get_mapping_obj(array('sku' => $value, 'ext_sys' => 'WMS', 'status' => 1));
            if ($mapping_obj && trim($mapping_obj->get_ext_sku()) != "") {
                $data['master_sku'] = $mapping_obj->get_ext_sku();
            }
            $pdata = array();
            if ($value != "") {
                if ($platform_list = $this->pricing_tool_model->platform_biz_var_service->get_pricing_tool_platform_list($value, PLATFORM_TYPE)) {
                    foreach ($platform_list as $platform_obj) {
                        $platform_id = $platform_obj->get_selling_platform_id();
                        $platform_country_id = $platform_obj->get_platform_country_id();
                        $language_id = $platform_obj->get_language_id();
                        $pdata[$platform_id]["website"]["price_title"]["prod_name_lang"] = "";
                        if ($prod_content_obj = $this->product_model->get_product_content(array("prod_sku" => $value, "lang_id" => $language_id))) {
                            $pdata[$platform_id]["website"]["prod_name_lang"] = $prod_content_obj->get_prod_name();
                            $pdata[$platform_id]["website"]["detail_desc"] = $prod_content_obj->get_detail_desc();
                        }

                        /** Display WEBSITE price for users to reference**/
                        $listing_info_obj = $this->product_model->get_listing_info($value, "WEB$platform_country_id");
                        $pdata[$platform_id]["website"]["price_title"] = "WEB$platform_country_id Price:";
                        if ($listing_info_obj) {
                            $pdata[$platform_id]["website"]["price_title"] = "WEB$platform_country_id Price:";
                            $pdata[$platform_id]["website"]["price"] = $listing_info_obj->get_price();
                        } else {
                            $pdata[$platform_id]["website"]["price"] = "Item not listed on WEB$platform_country_id";
                        }
                        /** ------------------------------------------ **/

                        $pdata[$platform_id]["obj"] = $platform_obj;

                        // this is the part where we get the HTML from deep inside the code
                        $tmp = $this->pricing_tool_model->get_pricing_tool_info($platform_id, $value, $this->_get_app_id());

                        $pdata[$platform_id]["pdata"] = $tmp;
                        $objcount++;
                        $price_obj = $this->pricing_tool_model->get_price_obj(array("sku" => $value, "platform_id" => $platform_id));
                        $sub_cat_margin = $tmp["dst"]->get_sub_cat_margin();
                        $type = "update";
                        if (!$price_obj) {
                            $price_obj = $this->pricing_tool_model->get_price_obj();
                            $type = "add";
                        }
                        $data["formtype"][$platform_id] = $type;
                        $data["sub_cat_margin"][$platform_id] = $sub_cat_margin;
                        $data["price_list"][$platform_id] = $price_obj;

                        if (!($price_ext_obj = $this->pricing_tool_model->price_service->get_price_ext_dao()->get(array("sku" => $value, "platform_id" => $platform_id)))) {
                            if (!isset($price_ext_vo)) {
                                $price_ext_vo = $this->pricing_tool_model->price_service->get_price_ext_dao()->get();
                            }
                            $price_ext_obj = clone $price_ext_vo;
                        }
                        $_SESSION["price_ext"][$platform_id]["obj"] = serialize($price_ext_obj);
                        $data["price_ext"][$platform_id]["obj"] = $price_ext_obj;

                        # if item listed on Rakuten, get newest inventory info (some cases Rakuten returns returned goods back to inventory)
                        if ($price_ext_obj->get_ext_item_id()) {
                            $inventory_result = $this->rakuten_service->get_inventory_by_single_sku($platform_country_id, $value);
                            if ($inventory_result["response"])
                                $data["price_ext"][$platform_id]["ext_inventory"] = $inventory_result["message"];
                        }

                        // VB's Store Category ID on Rakuten
                        $data["store_cat_list"][$platform_obj->get_selling_platform_id()] = $this->product_model->external_category_service->get_list(array("ext_party" => "VB_RAKUTEN", "level" => 2, "country_id" => $platform_obj->get_platform_country_id(), "status" => 1), array("orderby" => "ext_name ASC", "limit" => -1));

                        #SBF #4020 - delivery scenarios to show different time frames on front end
                        $data["delivery_info"][$platform_id] = array();
                        $delivery_scenarioid = $price_obj->get_delivery_scenarioid();
                        $deliverytime_obj = $this->deliverytime_service->get_deliverytime_obj($platform_country_id, $delivery_scenarioid);
                        if (!empty($deliverytime_obj)) {
                            $data["delivery_info"][$platform_id]["scenarioid"] = $deliverytime_obj->get_scenarioid();
                            $data["delivery_info"][$platform_id]["scenarioname"] = $scenario[$deliverytime_obj->get_scenarioid()];
                            $data["delivery_info"][$platform_id]["del_min_day"] = $deliverytime_obj->get_del_min_day();
                            $data["delivery_info"][$platform_id]["del_max_day"] = $deliverytime_obj->get_del_max_day();
                            $data["delivery_info"][$platform_id]["margin"] = $deliverytime_obj->get_margin();
                        }
                        $_SESSION["price_obj_" . $platform_id] = serialize($price_obj);

                        $current_platform_price = $tmp["dst"]->get_current_platform_price();

                        if ($data["master_sku"]) {
                            $comp_mapping_obj = $this->competitor_map_dao->get_active_comp($data['master_sku'], $platform_country_id);
                            if ($comp_mapping_obj) {
                                $pdata[$platform_id]["competitor"]["comp_mapping_obj"] = $comp_mapping_obj;
                                $pdata[$platform_id]["competitor"]["price_diff"] = array();
                                foreach ($comp_mapping_obj as $row) {
                                    $ship_charge = $row->get_comp_ship_charge();
                                    if (empty($ship_charge)) {
                                        $ship_charge = 0;
                                    }

                                    $pdata[$platform_id]["competitor"]["total_price"] = number_format(($row->get_now_price() + $ship_charge), 2, '.', '');

                                    #price difference between vb and competitor
                                    $pdata[$platform_id]["competitor"]["price_diff"][$row->get_competitor_id()] = number_format(($pdata[$platform_id]["competitor"]["total_price"] - $current_platform_price), 2);
                                }
                                asort($pdata[$platform_id]["competitor"]["price_diff"]);
                            }
                        }
                    }
                }
                // echo"<pre>"; var_dump($pdata["WEBES"]["competitor"]);die();

                $data["pdata"] = $pdata;
                $data["objcount"] = $objcount;
                $data["mkt_note_obj"] = $this->pricing_tool_model->get_note($value, "M");
                $data["src_note_obj"] = $this->pricing_tool_model->get_note($value, "S");
                $data["value"] = $value;
                $prod_obj = $this->pricing_tool_model->get_prod($value);
            }

            if ($prod_obj != null) {
                $data["surplus_qty"] = $prod_obj->get_surplus_quantity();
                $data["slow_move"] = $prod_obj->get_slow_move_7_days();
            }
            $data["prod_obj"] = $prod_obj;
            $data["supplier"] = $this->pricing_tool_model->get_current_supplier($value);
            $data["num_of_supplier"] = $this->pricing_tool_model->get_total_default_supplier($value);
            if ($t = $this->pricing_tool_model->get_freight_cat($prod_obj->get_freight_cat_id())) {
                $data["freight_cat"] = $t->get_name();
            }


            unset($t);
            $_SESSION["prod_obj"] = serialize($prod_obj);
        }

        $this->load->view($this->tool_path . "/pricing_tool_view", $data);
    }

    private function process_post_data($sku = "", $redirect = TRUE)
    {
        if (!$this->input->post('posted'))
            return;

        $plat = $this->input->post('selling_platform');
        $splist = $this->input->post("selling_price");
        // $pae = $this->input->post('allow_express');
        $pap = $this->input->post('auto_price');
        $pia = $this->input->post('is_advertised');
        $pft = $this->input->post('formtype');
        $pls = $this->input->post('listing_status');
        $pfrrp = $this->input->post('fixed_rrp');
        $prrp_factor = $this->input->post('rrp_factor');

        $hidden_profit = $this->input->post("hidden_profit");
        $hidden_margin = $this->input->post("hidden_margin");

        foreach ($plat as $key => $val) {
            // key = platform_id (e.g. RAKUES)

            $sp = $splist[$key];
            $country_id = substr($key, -2);
            $do_not_update = false;
            if (!$pap[$key]) $ap = 'N'; else $ap = $pap[$key];
            if ($pfrrp[$key] == 'N') $frrp = 'N'; else $frrp = 'Y';
            $rrp_factor = trim($prrp_factor[$key]);

            if (!$sp) {
                #SBF #2558 do not update anything if selling price is zero or empty
                $do_not_update = true;
                $_SESSION["NOTICE"] = "ERROR: \nUpdate failed. Selling price cannot be zero or empty.";
            } else {
                $cur_listing_status = $pls[$key];
                $prod_obj = $this->product_model->get('product', array('sku' => $sku));

                $this->pricing_tool_model->__autoload();
                if (!$price_obj = $this->pricing_tool_model->price_service->get_dao()->get(array("platform_id" => $key, "sku" => $sku))) {
                    $price_obj = $this->pricing_tool_model->price_service->get_dao()->get();
                }

                $price_ext_need_update = 0;
                $this->pricing_tool_model->price_service->get_price_ext_dao()->include_vo();
                //$price_ext_obj = unserialize($_SESSION["price_ext"][$key]);
                // if(!$price_ext_obj = $this->pricing_tool_model->price_service->get_price_ext_dao()->get(array("platform_id"=>$key, "sku"=>$sku)))
                // {
                //  $price_ext_obj = $this->pricing_tool_model->price_service->get_price_ext_dao()->get();
                // }

                // concat GTIN info into "type=value", e.g. EAN=1234zz
                if ($_POST["price_ext"][$key]["gtin"]["type"] != "" && $_POST["price_ext"][$key]["gtin"]["val"] != "")
                    $_POST["price_ext"][$key]["ext_ref_3"] = $_POST["price_ext"][$key]["gtin"]["type"] . "=" . $_POST["price_ext"][$key]["gtin"]["val"];
                else
                    $_POST["price_ext"][$key]["ext_ref_3"] = "";

                // RECONSTRUCT DATA HERE
                if ($_POST["action"][$key] == "E") {
                    $_POST["price_ext"][$key]["ext_qty"] = 0;
                    $_POST["price_ext"][$key]["ext_ref_2"] = 0;
                } else {
                    if ($_POST["price_ext"][$key]["ext_qty"] == "") {
                        if ($prod_obj->get_website_quantity() != "")
                            $_POST["price_ext"][$key]["ext_qty"] = $prod_obj->get_website_quantity();
                        else
                            $_POST["price_ext"][$key]["ext_qty"] = -1;
                    }
                    if ($_POST["price_ext"][$key]["ext_qty"] == 0)
                        $_POST["price_ext"][$key]["ext_ref_2"] = "";
                    elseif ($_POST["price_ext"][$key]["ext_ref_2"] == "")
                        $_POST["price_ext"][$key]["ext_ref_2"] = "-1";
                }

                $price_ext_obj = $this->pricing_tool_model->price_service->get_price_ext_dao()->get(array("platform_id" => $key, "sku" => $sku));
                if ($price_ext_obj) {
                    // ext_ref_2(): limitPurchaseQuantity
                    // ext_ref_3(): gtin type & value, e.g MPN=12340pp  // ext_ref_4(): VB's catID on rakuten
                    $price_ext_action = "update";
                    if (
                        $price_ext_obj->get_ext_qty() != $_POST["price_ext"][$key]["ext_qty"] ||
                        $price_ext_obj->get_title() != $_POST["price_ext"][$key]["title"] ||
                        $price_ext_obj->get_note() != $_POST["price_ext"][$key]["note"] ||
                        // $price_ext_obj->get_ext_ref_1() != $_POST["price_ext"][$key]["ext_ref_1"] ||
                        $price_ext_obj->get_ext_ref_2() != $_POST["price_ext"][$key]["ext_ref_2"] ||
                        $price_ext_obj->get_ext_ref_3() != $_POST["price_ext"][$key]["ext_ref_3"] ||
                        $price_ext_obj->get_ext_ref_4() != $_POST["price_ext"][$key]["ext_ref_4"] ||
                        $price_ext_obj->get_ext_desc() != $_POST["price_ext"][$key]["ext_desc"] ||
                        $_POST["action"][$key]
                    ) {
                        $price_ext_need_update = 1;

                        // checking for data types
                        if ($_POST["price_ext"][$key]["ext_qty"]) {
                            $rs_extqty = $this->check_valid("qty", $_POST["price_ext"][$key]["ext_qty"]);
                            if ($rs_extqty["response"] == 0) {
                                $_SESSION["NOTICE"] = __LINE__ . "pricing_tool_rakuten. Listing Qty {$rs_extqty['msg']}";
                                $data["prompt_notice"] = 1;
                                if ($redirect)
                                    Redirect(base_url() . $this->tool_path . "/view/" . $sku);
                            }
                        }

                        if ($_POST["price_ext"][$key]["ext_ref_2"]) {
                            $rs_limit = $this->check_valid("qty", $_POST["price_ext"][$key]["ext_ref_2"]);
                            if ($rs_limit["response"] == 0) {
                                $_SESSION["NOTICE"] = __LINE__ . "pricing_tool_rakuten. limitPurchaseQuantity {$rs_limit['msg']}";
                                $data["prompt_notice"] = 1;
                                if ($redirect)
                                    Redirect(base_url() . $this->tool_path . "/view/" . $sku);
                            }
                        }

                        if (!$_POST["price_ext"][$key]["ext_desc"]) {
                            $_SESSION["NOTICE"] = __LINE__ . "pricing_tool_rakuten. Description cannot be empty";
                            $data["prompt_notice"] = 1;
                            if ($redirect)
                                Redirect(base_url() . $this->tool_path . "/view/" . $sku);
                        }

                    }
                } else {
                    // if price_extend obj not existing, means new listing ==> INSERT
                    $price_ext_obj = $this->pricing_tool_model->price_service->get_price_ext_dao()->get();
                    $price_ext_obj->set_sku($sku);
                    $price_ext_obj->set_platform_id($key);
                    $price_ext_obj->set_ext_qty($_POST["price_ext"][$key]["ext_qty"]);
                    $price_ext_obj->set_title($_POST["price_ext"][$key]["title"]);
                    $price_ext_obj->set_note($_POST["price_ext"][$key]["note"]);
                    $price_ext_obj->set_ext_ref_2($_POST["price_ext"][$key]["ext_ref_2"]);
                    $price_ext_obj->set_ext_ref_3($_POST["price_ext"][$key]["ext_ref_3"]);
                    $price_ext_obj->set_ext_ref_4($_POST["price_ext"][$key]["ext_ref_4"]);
                    $price_ext_obj->set_ext_desc($_POST["price_ext"][$key]["ext_desc"]);
                    $price_ext_obj->set_action("P");

                    $price_ext_action = "insert";
                    if ($_POST["price_ext"][$key]["ext_qty"]) {
                        $price_ext_need_update = 1;
                    }
                }

                if ($price_obj->get_price() * 1 != $sp * 1 ||
                    $price_obj->get_auto_price() != $ap ||
                    $price_obj->get_listing_status() != $cur_listing_status ||
                    $price_obj->get_fixed_rrp() != $frrp ||
                    (($frrp == 'N') && ($rrp_factor != '') && ($price_obj->get_rrp_factor() != $rrp_factor)) ||
                    $price_ext_need_update
                ) {
                    $price_obj->set_platform_id($key);
                    $price_obj->set_sku($sku);
                    $price_obj->set_status($this->input->post('status'));
                    $price_obj->set_ext_mapping_code($this->input->post('ext_mapping_code'));
                    $price_obj->set_listing_status($cur_listing_status);
                    $price_obj->set_auto_price($ap);
                    $price_obj->set_fixed_rrp($frrp);
                    $sp = $splist[$key];
                    $price_obj->set_price($sp);

                    $price_obj->set_max_order_qty($this->input->post('max_order_qty'));

                    if (($frrp == 'N') && ($rrp_factor != '')) {
                        $price_obj->set_rrp_factor($rrp_factor);
                    }

                    // rrp-factor defaults to 1.34 in price table
                    if (is_null($price_obj->get_rrp_factor())) {
                        $price_obj->set_rrp_factor($this->pricing_tool_model->get_rrp_factor_by_sku($price_obj->get_sku()));
                    }

                    if ($pft[$key] == "update") {
                        $ret = $this->pricing_tool_model->update($price_obj);
                    } else {
                        $ret = $this->pricing_tool_model->add($price_obj);
                    }

                    if ($ret === FALSE) {
                        $_SESSION["NOTICE"] = __LINE__ . "pricing_tool_rakuten. update_failed " . $this->pricing_tool_model->price_service->get_dao()->db->_error_message() . $this->pricing_tool_model->price_service->get_dao()->db->last_query();
                    } else {
                        #2652 only if the price change, then insert or update on duplicate
                        $profit = $hidden_profit[$key];
                        $margin = $hidden_margin[$key];

                        //Comment: $sku = sku, $key = platform_id, $sp = selling_price
                        $this->price_margin_service->insert_or_update_margin($sku, $key, $sp, $profit, $margin);

                        $success = 1;
                        if ($price_ext_need_update) {
                            // set all relevant values of POST into price_extend object
                            set_value($price_ext_obj, $_POST["price_ext"][$key]);

                            if ($_POST["action"][$key] == "R") #R: Re-additem
                            {
                                $price_ext_obj->set_action("P");
                                $price_ext_obj->set_remark(NULL);
                                $price_ext_obj->set_ext_item_id(NULL);
                                $price_ext_obj->set_ext_status(NULL);
                            } elseif ($_POST["action"][$key] == "E") #E: end item listing
                            {
                                $price_ext_obj->set_action("E");
                            }

                            /* Update info into db price_extend */
                            if ($this->pricing_tool_model->price_service->get_price_ext_dao()->$price_ext_action($price_ext_obj) === FALSE) {
                                $success = 0;
                                $_SESSION["NOTICE"] = __LINE__ . " " . $this->db->_error_message();
                            } else {
                                if ($_POST["action"][$key] == "E") {
                                    $res = $this->rakuten_service->end_item($country_id, $sku);
                                    if ($res["response"]) {
                                        // update db price and price_extend if sucessfully ended rakuten listing
                                        $price_obj->set_listing_status("N");
                                        if ($this->pricing_tool_model->update($price_obj) === FALSE) {
                                            $success = 0;
                                            $_SESSION["NOTICE"] = __LINE__ . " pricing_tool_rakuten " . $this->db->_error_message();
                                        }

                                        $price_ext_obj->set_action("E");
                                        $price_ext_obj->set_remark(null);
                                        // $price_ext_obj->set_ext_item_id(NULL);  # rakuten retains ext_item_id
                                        $price_ext_obj->set_ext_qty(0);
                                        $price_ext_obj->set_note(NULL);
                                        $price_ext_obj->set_ext_status("E");

                                        if ($this->pricing_tool_model->price_service->get_price_ext_dao()->update($price_ext_obj) === FALSE) {
                                            $success = 0;
                                            $_SESSION["NOTICE"] = __LINE__ . " pricing_tool_rakuten " . $this->db->_error_message();
                                        }
                                    }
                                    $_SESSION["NOTICE"] .= $res["message"];
                                } elseif ($_POST["action"][$key] == "RE") #RE: revise item listing
                                {
                                    $res = $this->rakuten_service->update_item($country_id, $sku);
                                    $_SESSION["NOTICE"] .= $res["message"];
                                }
                                // elseif ($_POST["action"][$key] == "R") # re-add item
                                // {
                                //  $res = $this->rakuten_service->add_items($country_id, $sku);
                                //  if($res["response"])
                                //  {
                                //      // update db price and price_extend if sucessfully re-add rakuten listing
                                //      $price_obj->set_listing_status("L");
                                //      if($this->pricing_tool_model->update($price_obj) === FALSE)
                                //      {
                                //          $success = 0;
                                //          $_SESSION["NOTICE"] = __LINE__." pricing_tool_rakuten ".$this->db->_error_message();
                                //      }

                                //      $price_ext_obj->set_ext_status("L");
                                //      if($this->pricing_tool_model->price_service->get_price_ext_dao()->update($price_ext_obj) === FALSE)
                                //      {
                                //          $success = 0;
                                //          $_SESSION["NOTICE"] = __LINE__." pricing_tool_rakuten ".$this->db->_error_message();
                                //      }
                                //  }
                                //  $_SESSION["NOTICE"] .= $res["message"];
                                // }
                            }
                        }

                        if ($success) ;
                        {
                            unset($_SESSION["price_obj_" . $key]);
                            unset($_SESSION["price_ext"][$key]["obj"]);
                            if ($this->input->post('target') != "") {
                                $data["prompt_notice"] = 1;
                            }
                        }
                    }
                }
            }
        }

        if ($do_not_update === false) {
            $this->pricing_tool_model->__autoload_product_vo();
            // $prod_obj = unserialize($_SESSION["prod_obj"]);
            $prod_obj = $this->product_model->get('product', array('sku' => $sku));
            $prev_webqty = $prod_obj->get_website_quantity();
            $prod_obj->set_ean($this->input->post('ean'));
            $prod_obj->set_mpn($this->input->post('mpn'));
            $prod_obj->set_upc($this->input->post('upc'));
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
            if ($ret2 === FALSE) {
                $_SESSION["NOTICE"] = "update_failed " . $this->pricing_tool_model->product_service->get_dao()->db->_error_message();
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
        }

        if ($redirect)
            Redirect(base_url() . $this->tool_path . "/view/" . $sku);
    }

    private function check_valid($check_type, $value)
    {
        if ($check_type == "qty") {
            if (is_int(abs($value)) === false)
                return array("response" => 0, "msg" => "<$value> is not integer");
            else {
                if ($value < -1)
                    return array("response" => 0, "msg" => "<$value> cannot be lesser than -1");
                else
                    return array("response" => 1, "msg" => "success");
            }
        } elseif ($check_type == "price") {
            if ($value < 0 || !is_numeric($value))
                return array("response" => 0, "msg" => "<$value> is smaller than 0 or not numeric");
            else
                return array("response" => 1, "msg" => "success");
        } else {
            return array("response" => 0, "msg" => "rakuten_service: not recognised type in check_valid()");
        }
    }

    public function get_profit_margin_json($platform_id, $sku, $required_selling_price = 0, $required_cost_price = -1, $platform_type = "RAKUTEN", $return_json = false)
    {
        // if user input master sku, then convert it to local
        $sku = strtoupper($sku);
        if ($sku[0] == 'M') {
            $sku = substr($sku, 1);
            $sku = $this->sku_mapping_service->get_local_sku($sku);
        }

        $price_list = $this->integration_model->get_platform_price_list(array("sp.type" => $platform_type, "sku" => $sku, "platform_id" => $platform_id));

        header('Content-type: application/json');
        if ($price_list) {
            foreach ($price_list AS $price_obj) {
                $json = $this->pricing_tool_model->get_profit_margin_json($price_obj->get_platform_id(), $price_obj->get_sku(), $required_selling_price, $required_cost_price);
                if ($return_json) return $json; else echo $json;
            }
        } else {
            # this will get called if an unpriced item is passed in
            $json = $this->pricing_tool_model->get_profit_margin_json($platform_id, $sku, $required_selling_price, $required_cost_price);
            if ($return_json) return $json; else echo $json;
        }
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
                url = '/marketing/pricing_tool_rakuten/set_sku_feed_status_json/' + radio.name + '/$sku/$platform_id/' + radio.value;
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

    public function set_feed_platform_json($affiliate_id, $platform_id = "")
    {
        echo json_encode($this->affiliate_service->set_feed_platform($affiliate_id, $platform_id));
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