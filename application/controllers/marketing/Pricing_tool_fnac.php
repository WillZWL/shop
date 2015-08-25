<?php
DEFINE("PLATFORM_TYPE", "FNAC");

class Pricing_tool_fnac extends MY_Controller
{
    public $tool_path;
    public $default_platform_id;
    private $appId = 'MKT0063';

    //must set to public for view
    private $lang_id = 'en';
    private $connect_count = 0;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('integration/integration_model');
        $this->tool_path = 'marketing/pricing_tool_' . strtolower(PLATFORM_TYPE);
        $this->load->helper(array('url', 'notice', 'image'));
        $this->load->library('input');
        $this->load->model($this->tool_path . '_model', 'pricing_tool_model');
        $this->load->model('marketing/product_model');
        $this->load->library('service/fnac_service');
        $this->load->library('service/pagination_service');
        $this->load->library('service/context_config_service');
        $this->load->library('service/display_qty_service');
        $this->load->library('service/wms_inventory_service');
        $this->load->library('service/inventory_service');
        $this->load->library('service/product_identifier_service');
        $this->load->library('service/price_margin_service');
        $this->default_platform_id = $this->context_config_service->value_of("default_platform_id");
    }

    public function index()
    {
        $data = array();
        include_once APPPATH . "language/" . $this->getAppId() . "00_" . $this->_get_lang_id() . ".php";
        $data["lang"] = $lang;
        $this->load->view($this->tool_path . "/pricing_tool_index", $data);
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }

    public function plist()
    {
        $where = array();
        $option = array();
        $sub_app_id = $this->getAppId() . "02";
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
        if ($value == "") {
            exit;
        }

        $no_of_valid_supplier = $this->pricing_tool_model->check_valid_supplier_cost($value);
        if ($no_of_valid_supplier == 1) {
            $data = array();
            $data["valid_supplier"] = 1;
            $data["prompt_notice"] = 0;
            $data["website_link"] = $this->context_config_service->value_of("website_domain");
            define('IMG_PH', $this->context_config_service->value_of("prod_img_path"));
            if ($this->input->post('posted')) {
                $plat = $this->input->post('selling_platform');
                $splist = $this->input->post("selling_price");
                $peq = $this->input->post('ext_qty');
                $pft = $this->input->post('formtype');
                $pls = $this->input->post('listing_status');
                $update = $this->input->post("update");
                $hidden_profit = $this->input->post("hidden_profit");
                $hidden_margin = $this->input->post("hidden_margin");

                foreach ($plat as $key => $val) {
                    # if platform not selected for update, we skip
                    if (!isset($update[$key]))
                        continue;
                    $sp = $splist[$key];
                    $cur_listing_status = $pls[$key];
                    $_POST["price_ext"][$key]["ext_ref_3"] = trim($_POST["price_ext"][$key]["ext_ref_3"]);

                    if ($cur_listing_status == 'L' && !$_POST["price_ext"][$key]["ext_ref_3"]) {
                        $_SESSION["NOTICE"] = __LINE__ . " $key - EAN cannot be empty.";
                    } else {
                        $price_ext_need_update = 0;
                        $this->pricing_tool_model->price_service->get_price_ext_dao()->include_vo();
                        $price_ext_dao = $this->pricing_tool_model->price_service->get_price_ext_dao();

                        $price_ext_obj = $price_ext_dao->get(array("platform_id" => $key, "sku" => $value));
                        if ($price_ext_obj) {
                            $price_ext_action = "update";
                            if ($price_ext_obj->get_note() != $_POST["price_ext"][$key]["note"] ||
                                $price_ext_obj->get_ext_qty() != $_POST["price_ext"][$key]["ext_qty"] ||
                                $price_ext_obj->get_ext_ref_3() != $_POST["price_ext"][$key]["ext_ref_3"] ||
                                $_POST["action"][$key]
                            ) {
                                $price_ext_need_update = 1;
                            }
                        } else {
                            $price_ext_obj = $price_ext_dao->get();
                            $price_ext_obj->set_sku($value)->set_platform_id($key)->set_ext_qty(0);
                            $price_ext_action = "insert";
                            if ($_POST["price_ext"][$key]["note"] ||
                                $_POST["price_ext"][$key]["ext_ref_3"] ||
                                $_POST["price_ext"][$key]["ext_qty"]
                            ) {
                                $price_ext_need_update = 1;
                            }
                        }
                        if ($price_ext_obj && $cur_listing_status == 'N') {
                            # if previously tried to add item but fail, we need to clear the action and remark from previous api call
                            if ($price_ext_obj->get_action() || $price_ext_obj->get_remark()) {
                                $price_ext_obj->set_ext_qty(0);
                                $price_ext_obj->set_action(null);
                                $price_ext_obj->set_remark(null);
                                $price_ext_need_update = 1;
                            }
                        }

                        $this->pricing_tool_model->__autoload();
                        // $price_obj = unserialize($_SESSION["price_obj_".$key]);
                        $price_dao = $this->pricing_tool_model->price_service->get_dao();
                        $price_obj = $price_dao->get(array("platform_id" => $key, "sku" => $value));
                        if (!$price_obj) {
                            $price_obj = $price_dao->get();
                        }

                        if ($price_obj) {
                            if ($price_obj->get_price() * 1 != $sp * 1 ||
                                $price_obj->get_listing_status() != $cur_listing_status ||
                                $price_ext_need_update
                            ) {
                                $price_obj->set_platform_id($key);
                                $price_obj->set_sku($value);
                                $price_obj->set_status($this->input->post('status'));
                                $price_obj->set_ext_mapping_code($this->input->post('ext_mapping_code'));
                                $price_obj->set_listing_status($cur_listing_status);
                                $sp = $splist[$key];
                                $price_obj->set_price($sp);

                                $price_obj->set_allow_express($ae);
                                $price_obj->set_is_advertised($ia);
                                $price_obj->set_auto_price('N');
                                $price_obj->set_max_order_qty($this->input->post('max_order_qty'));

                                if ($pft[$key] == "update") {
                                    $ret = $this->pricing_tool_model->update($price_obj);
                                } else {
                                    $price_obj->set_allow_express("N");
                                    $price_obj->set_is_advertised("N");
                                    $ret = $this->pricing_tool_model->add($price_obj);
                                }

                                if ($ret === FALSE) {
                                    $_SESSION["NOTICE"] = "update_failed " . $this->db->_error_message();
                                } else {
                                    #2652 only if the price change, then insert or update on duplicate
                                    $profit = $hidden_profit[$key];
                                    $margin = $hidden_margin[$key];

                                    //Comment: $sku = sku, $key = platform_id, $sp = selling_price
                                    $this->price_margin_service->insert_or_update_margin($value, $key, $sp, $profit, $margin);

                                    $success = 1;
                                    if ($price_ext_need_update) {
                                        set_value($price_ext_obj, $_POST["price_ext"][$key]);
                                        if ($_POST["action"][$key] == "R") {
                                            $price_ext_obj->set_ext_item_id(NULL);
                                            $price_ext_obj->set_ext_status(NULL);
                                        }
                                        if ($this->pricing_tool_model->price_service->get_price_ext_dao()->$price_ext_action($price_ext_obj) === FALSE) {
                                            $success = 0;
                                            $_SESSION["NOTICE"] = __LINE__ . " " . $this->db->_error_message();
                                        }
                                    }

                                    if ($success) ;
                                    {
                                        unset($_SESSION["price_obj_" . $key]);
                                        unset($_SESSION["price_ext"][$key]);
                                        if ($this->input->post('target') != "") {
                                            $data["prompt_notice"] = 1;
                                        }
                                    }
                                }
                            }
                        } else {
                            $_SESSION["NOTICE"] = __LINE__ . " Unable to retrieve price obj  " . $this->db->_error_message();
                        }

                    }
                }
                $this->pricing_tool_model->__autoload_product_vo();
                // $prod_obj = unserialize($_SESSION["prod_obj"]);
                $prod_obj = $this->product_model->get('product', array('sku' => $value));
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
                    $_SESSION["NOTICE"] = "update_failed";
                } else {
                    unset($_SESSION["prod_obj"]);
                }

                if (trim($this->input->post('m_note')) != "") {
                    $note_obj = $this->pricing_tool_model->get_note();
                    $note_obj->set_sku($value);
                    $note_obj->set_type('M');
                    $note_obj->set_note($this->input->post('m_note'));
                    if (!($ret = $this->pricing_tool_model->add_note($note_obj))) {
                        $_SESSION["NOTICE"] = "update_note_failed";
                    }
                }

                if (trim($this->input->post('s_note')) != "") {
                    $note_obj = $this->pricing_tool_model->get_note();
                    $note_obj->set_sku($value);
                    $note_obj->set_type('S');
                    $note_obj->set_note($this->input->post('s_note'));
                    if (!($ret = $this->pricing_tool_model->add_note($note_obj))) {
                        $_SESSION["NOTICE"] = "update_note_failed";
                    }
                }

                if (!$_SESSION["NOTICE"]) {
                    if ($this->input->post('sync')) {
                        $this->connect_count = 0;
                        if ($update) {
                            foreach ($update as $platform_id => $v) {
                                $country_id = substr($platform_id, -2);
                                if ($country_id == "FR") {
                                    $notice_message .= "$country_id - Fnac Cannot be updated at this country. \n";
                                    continue;
                                }
                                $xmlResponse = $this->fnac_service->send_offers_update_request($value, $country_id);
                                if (isset($xmlResponse["error_message"]) || $xmlResponse->error->attributes()->code == "ERR_023") {
                                    $price_obj = $price_dao->get(array("platform_id" => $platform_id, "sku" => $value));
                                    if ($price_obj) {
                                        $price_obj->set_listing_status('N');
                                        $ret = $this->pricing_tool_model->update($price_obj);
                                    }

                                    $price_ext_obj = $price_ext_dao->get(array("platform_id" => $platform_id, "sku" => $value));
                                    if ($price_ext_obj) {
                                        $price_ext_obj->set_remark(NULL);
                                        $this->pricing_tool_model->price_service->get_price_ext_dao()->update($price_ext_obj);
                                    }

                                    if (isset($xmlResponse["error_message"]))
                                        $notice_message .= "$country_id - Fnac Offer Update Failed \n" . $xmlResponse["error_message"];
                                    else
                                        $notice_message .= "$country_id - Fnac Offer Update Failed - FNAC batch not found";
                                } else {
                                    if ($batch_id = (string)$xmlResponse->batch_id) {
                                        sleep(1);
                                        $notice = $this->fnac_service->check_fnac_batch_offers_update_status($xmlResponse, $value, $batch_id, $country_id);
                                        $notice_message .= "$country_id - $notice";
                                    } else {
                                        $notice_message .= __LINE__ . " $country_id - Fnac Offer Update Failed \n";
                                    }
                                }
                            }
                            $_SESSION["NOTICE"] = $notice_message;
                        }
                    }
                }

                Redirect(base_url() . $this->tool_path . "/view/" . $value);
            }

            $data["action"] = "update";
            if (empty($price_obj)) {
                $price_obj = $this->pricing_tool_model->get_price_obj();
                $data["action"] = "add";
            }
            include_once APPPATH . "language/" . $this->getAppId() . "01_" . $this->_get_lang_id() . ".php";
            $data["lang"] = $lang;
            $_SESSION["price_obj"] = serialize($price_obj);
            $data["canedit"] = 1;
            $data["value"] = $value;
            $data["target"] = $this->input->get('target');
            $data["notice"] = notice($lang);

            $product_cost_dto = $this->pricing_tool_model->get_product_cost_dto($value, "WSGB");

            $data["inv"] = $this->wms_inventory_service->get_inventory_list(array("sku" => $value));

            $tmpx = $this->pricing_tool_model->get_quantity_in_orders($value);

            foreach ($tmpx as $key => $val) {
                $data["qty_in_orders"] .= $lang["last"] . " " . $key . " " . $lang["days"] . " : " . $val . "<br>";
            }

            $data["qty_in_orders"] = ereg_replace("<br>$", "", $data["qty_in_orders"]);

            $pdata = array();
            if ($value != "") {
                $prod_obj = $this->pricing_tool_model->get_prod($value);

                if ($platform_list = $this->pricing_tool_model->platform_biz_var_service->get_pricing_tool_platform_list($value, PLATFORM_TYPE)) {
                    foreach ($platform_list as $platform_obj) {
                        $platform_id = $platform_obj->get_selling_platform_id();
                        $pdata[$platform_id]["obj"] = $platform_obj;
                        $tmp = $this->pricing_tool_model->get_pricing_tool_info($platform_id, $value, $this->getAppId());
                        $pdata[$platform_id]["pdata"] = $tmp;
                        $objcount++;
                        $price_obj = $this->pricing_tool_model->get_price_obj(array("sku" => $value, "platform_id" => $platform_id));
                        $type = "update";
                        if (!$price_obj) {
                            $price_obj = $this->pricing_tool_model->get_price_obj();
                            $type = "add";
                        }
                        $data["formtype"][$platform_id] = $type;
                        $data["price_list"][$platform_id] = $price_obj;
                        $_SESSION["price_obj_" . $platform_id] = serialize($price_obj);
                        if (!($price_ext_obj = $this->pricing_tool_model->price_service->get_price_ext_dao()->get(array("sku" => $value, "platform_id" => $platform_id)))) {
                            if (!isset($price_ext_vo)) {
                                $price_ext_vo = $this->pricing_tool_model->price_service->get_price_ext_dao()->get();
                            }
                            $price_ext_obj = clone $price_ext_vo;
                        }
                        $_SESSION["price_ext"][$platform_id] = serialize($price_ext_obj);
                        $data["price_ext"][$platform_id] = $price_ext_obj;

                        $pi_where["colour_id"] = $prod_obj->get_colour_id();
                        $pi_where["prod_grp_cd"] = $prod_obj->get_prod_grp_cd();
                        $pi_where["country_id"] = $platform_obj->get_platform_country_id();
                        $pi_where["status"] = 1;
                        $pdata[$platform_id]["product_identifier"] = $this->product_identifier_service->get($pi_where);
                    }
                }
                $data["pdata"] = $pdata;
                $data["objcount"] = $objcount;
                $data["mkt_note_obj"] = $this->pricing_tool_model->get_note($value, "M");
                $data["src_note_obj"] = $this->pricing_tool_model->get_note($value, "S");
                $data["value"] = $value;
            }

            if ($prod_obj != null) $data["surplus_qty"] = $prod_obj->get_surplus_quantity();
            $data["prod_obj"] = $prod_obj;
            $data["supplier"] = $this->pricing_tool_model->get_current_supplier($value);
            $data["num_of_supplier"] = $this->pricing_tool_model->get_total_default_supplier($value);
            if ($t = $this->pricing_tool_model->get_freight_cat($prod_obj->get_freight_cat_id())) {
                $data["freight_cat"] = $t->get_name();
            }
            $mapping_obj = $this->pricing_tool_model->get_mapping_obj(array('sku' => $value, 'ext_sys' => 'WMS', 'status' => 1));
            if ($mapping_obj && trim($mapping_obj->get_ext_sku()) != "") {
                $data['master_sku'] = $mapping_obj->get_ext_sku();
            }

            unset($t);
            $_SESSION["prod_obj"] = serialize($prod_obj);
        }

        $this->load->view($this->tool_path . "/pricing_tool_view", $data);

    }

    public function get_profit_margin_json($platform_id, $sku, $required_selling_price = 0, $required_cost_price = -1, $platform_type = "FNAC")
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
                echo $this->pricing_tool_model->get_profit_margin_json($price_obj->get_platform_id(), $price_obj->get_sku(), $required_selling_price);
            }
        } else {
            # this will get called if an unpriced item is passed in
            echo $this->pricing_tool_model->get_profit_margin_json($platform_id, $sku, $required_selling_price);
        }
    }

    public function get_js()
    {
        $this->pricing_tool_model->print_pricing_tool_js();
    }
}

?>