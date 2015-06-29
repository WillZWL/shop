<?php
DEFINE("PLATFORM_TYPE", "AMAZON");

class Pricing_tool_amazon extends MY_Controller
{

    public $tool_path;
    public $default_platform_id;

    //must set to public for view
    private $app_id = 'MKT0058';
    private $lang_id = 'en';

    public function __construct()
    {
        parent::__construct();
        $this->tool_path = 'marketing/pricing_tool_' . strtolower(PLATFORM_TYPE);
        $this->load->helper(array('url', 'notice', 'image', 'object'));
        $this->load->library('input');
        $this->load->model($this->tool_path . '_model', 'pricing_tool_model');
        $this->load->model('marketing/product_model');
        $this->load->library('service/pagination_service');
        $this->load->library('service/context_config_service');
        $this->load->library('service/display_qty_service');
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

                $dship = $this->input->post('default_shipping');
                $splist = $this->input->post("selling_price");
                $pae = $this->input->post('allow_express');
                $pia = $this->input->post('is_advertised');
                $pft = $this->input->post('formtype');
                $pls = $this->input->post('listing_status');

                foreach ($dship as $key => $st) {
                    $sp = $splist[$key];
                    $cur_listing_status = $pls[$key];
                    if ($pae[$key]) {
                        $ae = 'Y';
                    } else {
                        $ae = 'N';
                    }

                    if ($pia[$key]) {
                        $ia = 'Y';
                    } else {
                        $ia = 'N';
                    }

                    $this->pricing_tool_model->__autoload();
                    $price_obj = unserialize($_SESSION["price_obj_" . $key]);

                    $price_ext_need_update = 0;
                    $this->pricing_tool_model->price_service->get_price_ext_dao()->include_vo();
                    $price_ext_obj = unserialize($_SESSION["price_ext"][$key]);

                    if (call_user_func(array($price_ext_obj, "get_platform_id"))) {
                        $price_ext_action = "update";
                        if ($price_ext_obj->get_ext_qty() != $_POST["price_ext"][$key]["ext_qty"] ||
                            $price_ext_obj->get_fulfillment_centre_id != $_POST["price_ext"][$key]["fulfillment_centre_id"] ||
                            $price_ext_obj->get_repricing_name != $_POST["price_ext"][$key]["repricing_name"] ||
                            $price_ext_obj->get_ext_condition != $_POST["price_ext"][$key]["ext_condition"] ||
                            $price_ext_obj->get_note != $_POST["price_ext"][$key]["note"] ||
                            $_POST["action"][$key]
                        ) {
                            $price_ext_need_update = 1;
                        }
                    } else {
                        $price_ext_obj->set_sku($value)->set_platform_id($key);
                        $price_ext_action = "insert";
                        if ($_POST["price_ext"][$key]["ext_qty"]) {
                            $price_ext_need_update = 1;
                        }
                    }


                    if ($price_obj->get_price() * 1 != $sp * 1 ||
                        $price_obj->get_listing_status() != $cur_listing_status ||
                        $price_obj->get_allow_express() != $ae ||
                        $price_obj->get_is_advertised() != $ia ||
                        $price_obj->get_default_shiptype() != $dship ||
                        $price_obj->get_platform_code() != $_POST["price"][$key]["platform_code"] ||
                        $price_obj->get_auto_price() != $_POST["price"][$key]["auto_price"] ||
                        $price_obj->get_latency() != $_POST["price"][$key]["latency"] ||
                        $price_obj->get_oos_latency() != $_POST["price"][$key]["oos_latency"] ||
                        $price_ext_need_update
                    ) {
                        $price_obj->set_platform_id($key);
                        $price_obj->set_sku($value);
                        $price_obj->set_status($this->input->post('status'));
                        $price_obj->set_default_shiptype($st);
                        $price_obj->set_ext_mapping_code($this->input->post('ext_mapping_code'));
                        $price_obj->set_listing_status($cur_listing_status);
                        $sp = $splist[$key];
                        $price_obj->set_price($sp);

                        $price_obj->set_allow_express($ae);
                        $price_obj->set_is_advertised($ia);

                        $price_obj->set_platform_code($_POST["price"][$key]["platform_code"]);
                        $price_obj->set_auto_price($_POST["price"][$key]["auto_price"]);
                        $price_obj->set_latency($_POST["price"][$key]["latency"]);
                        $price_obj->set_oos_latency($_POST["price"][$key]["oos_latency"]);
                        $price_obj->set_max_order_qty($_POST["price"][$key]["max_order_qty"]);

                        if ($pft[$key] == "update") {
                            $ret = $this->pricing_tool_model->update($price_obj);
                        } else {
                            $ret = $this->pricing_tool_model->add($price_obj);
                        }

                        if ($ret === FALSE) {
                            $_SESSION["NOTICE"] = "update_failed " . $this->db->_error_message();
                        } else {
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

                    if ($vpo_obj = $prod_dao->get_prod_overview_wo_cost_w_rate($vpo_where, $vpo_option)) {
                        $display_qty = $this->display_qty_service->calc_display_qty($vpo_obj->get_cat_id(), $this->input->post('webqty'), $vpo_obj->get_price());
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
                Redirect(base_url() . $this->tool_path . "/view/" . $value);
            }

            $shiptype_list = $this->pricing_tool_model->get_shiptype_list();

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

            $product_cost_dto = $this->pricing_tool_model->get_product_cost_dto($value, "WSGB");

            $data["inv"] = $this->pricing_tool_model->get_inventory(array("sku" => $value));

            $tmpx = $this->pricing_tool_model->get_quantity_in_orders($value);

            foreach ($tmpx as $key => $val) {
                $data["qty_in_orders"] .= $lang["last"] . " " . $key . " " . $lang["days"] . " : " . $val . "<br>";
            }

            $data["qty_in_orders"] = ereg_replace("<br>$", "", $data["qty_in_orders"]);

            $shiptype = $this->pricing_tool_model->get_shiptype_list(PLATFORM_TYPE);
            $data["shiptype"] = $shiptype;

            $pdata = array();
            if ($value != "") {
                if ($platform_list = $this->pricing_tool_model->platform_biz_var_service->get_list_w_platform_name(array("type" => PLATFORM_TYPE, "status" => 1))) {
                    foreach ($platform_list as $platform_obj) {
                        $platform_id = $platform_obj->get_selling_platform_id();
                        $pdata[$platform_id]["obj"] = $platform_obj;

                        $tmp = $this->pricing_tool_model->get_pricing_tool_info($platform_id, $value, $this->_get_app_id());
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
                    }
                }
                $data["pdata"] = $pdata;
                $data["objcount"] = $objcount;
                $data["mkt_note_obj"] = $this->pricing_tool_model->get_note($value, "M");
                $data["src_note_obj"] = $this->pricing_tool_model->get_note($value, "S");
                $data["value"] = $value;
                $prod_obj = $this->pricing_tool_model->get_prod($value);
            }

            $data["prod_obj"] = $prod_obj;
            $data["supplier"] = $this->pricing_tool_model->get_current_supplier($value);
            $mapping_obj = $this->pricing_tool_model->get_mapping_obj(array('sku' => $value, 'ext_sys' => 'WMS', 'status' => 1));
            if ($mapping_obj && trim($mapping_obj->get_ext_sku()) != "") {
                $data['master_sku'] = $mapping_obj->get_ext_sku();
            }

            $data['condition_list'] = $this->pricing_tool_model->get_amazon_condition_list();
            $data['reprice_name_list'] = $this->pricing_tool_model->get_ixten_reprice_rule_list();
            $fulfillment_centre_list = $this->pricing_tool_model->get_list_w_subject(array("sd.subject LIKE 'MKT.AMAZON.FULFILLMENT_CENTRE_ID%'" => null), array("orderby" => "sdd.value='DEFAULT' DESC"));


            foreach ($fulfillment_centre_list as $fc) {
                $fc_arr[str_replace('MKT.AMAZON.FULFILLMENT_CENTRE_ID.', '', $fc->get_subject())][] = $fc->get_value();
            }
            $data['fulfillment_centre_list'] = $fc_arr;
            $landpage_url_list = $this->pricing_tool_model->get_list_w_subject(array("sd.subject" => 'MKT.AMAZON.LANDPAGE_URL'));
            foreach ($landpage_url_list as $url_obj) {
                $data['amazon_landpage_url_list'][$url_obj->get_subkey()] = $url_obj;
            }

            if ($t = $this->pricing_tool_model->get_freight_cat($prod_obj->get_freight_cat_id())) {
                $data["freight_cat"] = $t->get_name();
            }
            unset($t);
            $_SESSION["prod_obj"] = serialize($prod_obj);
        }

        $this->load->view($this->tool_path . "/pricing_tool_view", $data);

    }

    public function get_js()
    {
        $this->pricing_tool_model->print_pricing_tool_js();
    }
}

/* End of file pricing_tool_ebay.php */
/* Location: ./system/application/controllers/pricing_tool_ebay.php */