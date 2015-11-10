<?php
DEFINE("PLATFORM_TYPE", "QOO10");

class Product_overview_qoo10 extends MY_Controller
{

    public $overview_path;
    public $default_platform_id;

    //must set to public for view
    private $appId = "MKT0072";
    private $lang_id = "en";

    public function __construct()
    {
        parent::__construct();
        $this->overview_path = 'marketing/product_overview_' . strtolower(PLATFORM_TYPE);
        $this->load->model($this->overview_path . '_model', 'product_overview_model');
        $this->tool_path = 'marketing/pricing_tool_' . strtolower(PLATFORM_TYPE);
        $this->load->model($this->tool_path . '_model', 'pricing_tool_model');
        $this->load->helper(array('url', 'notice', 'object', 'operator'));
        $this->load->library('service/pagination_service');
        $this->load->library('service/context_config_service');
        $this->load->library('service/display_qty_service');
        $this->load->library('service/wms_warehouse_service');
        $this->load->library('service/price_margin_service');
        $this->load->library('service/qoo10_service');
        $this->default_platform_id = $this->context_config_service->value_of("default_platform_id");
    }

    public function index($platform_id = "")
    {
        $sub_app_id = $this->getAppId() . "00";
        $_SESSION["LISTPAGE"] = base_url() . $this->overview_path . "/?" . $_SERVER['QUERY_STRING'];

        if ($this->input->post("posted") && $_POST["check"]) {
            $rsresult = "";
            $shownotice = 0;
            foreach ($_POST["check"] as $rssku) {
                $success = 0;
                list($platform, $sku) = explode("||", $rssku);

                $profit = $_POST["hidden_profit"][$platform][$sku];
                $margin = $_POST["hidden_margin"][$platform][$sku];
                $price = $_POST["price"][$platform][$sku]["price"];

                if (($price_obj = $this->product_overview_model->get_price(array("sku" => $sku, "platform_id" => $platform))) !== FALSE) {
                    if (empty($price_obj)) {
                        $price_obj = $this->product_overview_model->get_price();
                        set_value($price_obj, $_POST["price"][$platform][$rssku]);
                        $price_obj->set_sku($sku);
                        $price_obj->set_platform_id($platform);
                        //$price_obj->set_listing_status('L');
                        $price_obj->set_status(1);
                        $price_obj->set_allow_express('N');
                        $price_obj->set_is_advertised('N');
                        $price_obj->set_max_order_qty(100);
                        $price_obj->set_auto_price('N');
                        if ($this->product_overview_model->add_price($price_obj)) {
                            $success = 1;

                            // update price_margin tb for all platforms
                            $this->price_margin_service->insert_or_update_margin($sku, $platform, $price, $profit, $margin);
                        }
                    } else {
                        set_value($price_obj, $_POST["price"][$platform][$sku]);
                        if ($this->product_overview_model->update_price($price_obj)) {
                            $success = 1;

                            // update price_margin tb for all platforms
                            $this->price_margin_service->insert_or_update_margin($sku, $platform, $price, $profit, $margin);
                        }
                    }
                }

                if ($price_ext_obj = $this->pricing_tool_model->price_service->get_price_ext_dao()->get(array("platform_id" => $platform, "sku" => $sku))) {
                    $price_ext_obj_need_update = false;

                    if ($_POST["action"][$platform][$sku]) {
                        $price_ext_obj_need_update = TRUE;
                    }

                    if ($price_ext_obj->get_ext_qty() != $_POST["product"][$platform][$sku]['ext_qty']) {
                        $price_ext_obj->set_ext_qty($_POST["product"][$platform][$sku]['ext_qty']);
                        $price_ext_obj_need_update = TRUE;
                    }

                    // if($price_ext_obj->get_handling_time() != $_POST["handling_time"][$platform][$sku])
                    // {
                    //  $price_ext_obj->set_handling_time($_POST["handling_time"][$platform][$sku]);
                    //  $price_ext_obj_need_update = TRUE;
                    // }

                    if ($_POST["action"][$platform][$sku] == "R") {
                        $price_ext_obj->set_action(NULL);
                        $price_ext_obj->set_remark(NULL);
                        $price_ext_obj->set_ext_item_id(NULL);
                        $price_ext_obj->set_ext_status(NULL);

                    } elseif ($_POST["action"][$platform][$sku] == "E") {
                        $price_ext_obj->set_action("E");
                        $price_ext_obj->set_remark($_POST["reason"][$platform][$sku]);
                    }

                    if ($price_ext_obj_need_update) {
                        if ($this->pricing_tool_model->price_service->get_price_ext_dao()->update($price_ext_obj) === false) {
                            $success = 0;
                        } else {
                            if ($_POST["action"][$platform][$sku] == "E") {
                                $res = $this->qoo10_service->end_item($platform, $sku);
                                if ($res["response"]) {
                                    // update price and price extend if sucessfully ended ebay listing
                                    $price_obj->set_listing_status("N");
                                    if ($this->pricing_tool_model->update($price_obj) === FALSE) {
                                        $success = 0;
                                        $_SESSION["NOTICE"] = __LINE__ . " " . $this->db->_error_message();
                                    }

                                    $price_ext_obj->set_action(null);
                                    $price_ext_obj->set_remark(null);
                                    $price_ext_obj->set_ext_item_id(NULL);
                                    $price_ext_obj->set_ext_qty(0);
                                    $price_ext_obj->set_note(NULL);
                                    $price_ext_obj->set_ext_status("E");
                                    if ($this->pricing_tool_model->price_service->get_price_ext_dao()->update($price_ext_obj) === FALSE) {
                                        $success = 0;
                                        $_SESSION["NOTICE"] = __LINE__ . " " . $this->db->_error_message();
                                    }
                                }
                                $_SESSION["NOTICE"] .= $res["message"];
                            } elseif ($_POST["action"][$platform][$sku] == "RE") {
                                $res = $this->qoo10_service->update_item($platform, $sku);
                                $_SESSION["NOTICE"] .= $res["message"];
                            } elseif ($_POST["action"][$platform][$sku] == "R") {
                                $res = $this->qoo10_service->update_item($platform, $sku);
                                if ($res["response"]) {
                                    // update db price and price_extend if sucessfully ended qoo10 listing
                                    $price_obj->set_listing_status("L");
                                    if ($this->pricing_tool_model->update($price_obj) === FALSE) {
                                        $success = 0;
                                        $_SESSION["NOTICE"] = __LINE__ . " " . $this->db->_error_message();
                                    }

                                    $price_ext_obj->set_ext_status("L");
                                    if ($this->pricing_tool_model->price_service->get_price_ext_dao()->update($price_ext_obj) === FALSE) {
                                        $success = 0;
                                        $_SESSION["NOTICE"] = __LINE__ . " " . $this->db->_error_message();
                                    }
                                }
                                $_SESSION["NOTICE"] .= $res["message"];
                            }
                        }
                    }
                }

                if ($success) {
                    if ($product_obj = $this->product_overview_model->get("product", array("sku" => $sku))) {
                        if ($this->product_overview_model->update("product", $product_obj)) {
                            $success = 1;
                        } else {
                            $success = 0;
                        }
                    } else {
                        $success = 0;
                    }
                }
                if (!$success) {
                    $shownotice = 1;
                }
                $rsresult .= "{$rssku} -> {$success}\\n";
            }
            if ($shownotice) {
                $_SESSION["NOTICE"] = $rsresult;
            }
            redirect(current_url() . "?" . $_SERVER['QUERY_STRING']);
        }

        $where = array();
        $option = array();

        $submit_search = 0;

        $option["inventory"] = 1;

        if ($this->input->get("sku") != "") {
            $where["p.sku LIKE "] = "%" . $this->input->get("sku") . "%";
            $submit_search = 1;
        }

        if ($this->input->get("cat_id") != "") {
            $where["p.cat_id"] = $this->input->get("cat_id");
        }

        if ($this->input->get("sub_cat_id") != "") {
            $where["p.sub_cat_id"] = $this->input->get("sub_cat_id");
        }

        if ($this->input->get("brand_id") != "") {
            $where["p.brand_id"] = $this->input->get("brand_id");
        }

        if ($this->input->get("supplier_id") != "") {
            $where["sp.supplier_id"] = $this->input->get("supplier_id");
        }

        if ($this->input->get("prod_name") != "") {
            $where["p.name LIKE "] = "%" . $this->input->get("prod_name") . "%";
            $submit_search = 1;
        }

        if ($this->input->get("platform_id") != "") {
            $where["pbv.selling_platform_id"] = $this->input->get("platform_id");
            $submit_search = 1;
        }

        if ($this->input->get("clearance") != "") {
            $where["p.clearance"] = $this->input->get("clearance");
            $submit_search = 1;
        }

        if ($this->input->get("listing_status") != "") {
            if ($this->input->get("listing_status") == "N") {
                $where["(pr.listing_status = 'N' or pr.listing_status is null)"] = null;
            } else {
                $where["pr.listing_status"] = $this->input->get("listing_status");
            }
            $submit_search = 1;
        }

        if ($this->input->get("inventory") != "") {
            fetch_operator($where, "inventory", $this->input->get("inventory"));
            $submit_search = 1;
        }

        if ($this->input->get("website_quantity") != "") {
            fetch_operator($where, "p.website_quantity", $this->input->get("website_quantity"));
            $submit_search = 1;
        }

        if ($this->input->get("ext_qty") != "") {
            $where["prx.ext_qty"] = $this->input->get("ext_qty");
            $submit_search = 1;
        }

        if ($this->input->get("website_status") != "") {
            $where["p.website_status"] = $this->input->get("website_status");
            $submit_search = 1;
        }

        if ($this->input->get("sourcing_status") != "") {
            $where["p.sourcing_status"] = $this->input->get("sourcing_status");
            $submit_search = 1;
        }

        if ($this->input->get("purchaser_updated_date") != "") {
            fetch_operator($where, "sp.modify_on", $this->input->get("purchaser_updated_date"));
            $submit_search = 1;
        }

        if ($this->input->get("profit") != "") {
            fetch_operator($where, "pm.profit", $this->input->get("profit"));
            $option["refresh_margin"] = 1;
            $submit_search = 1;
        }

        if ($this->input->get("margin") != "") {
            fetch_operator($where, "pm.margin", $this->input->get("margin"));
            $option["refresh_margin"] = 1;
            $submit_search = 1;
        }

        if ($this->input->get("price") != "") {
            fetch_operator($where, "pr.price", $this->input->get("price"));
            $submit_search = 1;
        }

        if ($this->input->get("surplusqty") != "") {
            switch ($this->input->get("surplusqty_prefix")) {
                case 1:
                    $where["surplus_quantity is not null and surplus_quantity > 0 and surplus_quantity <= {$this->input->get("surplusqty")}"] = null;
                    break;
                case 2:
                    $where["surplus_quantity <= {$this->input->get("surplusqty")}"] = null;
                    break;
                case 3:
                    $where["surplus_quantity >= {$this->input->get("surplusqty")}"] = null;
                    break;
            }
        }

        $sort = $this->input->get("sort");
        $order = $this->input->get("order");

        $limit = '20';

        $pconfig['base_url'] = $_SESSION["LISTPAGE"];
        $option["limit"] = $pconfig['per_page'] = $limit;
        if ($option["limit"]) {
            $option["offset"] = $this->input->get("per_page");
        }

        if (empty($sort)) {
            $sort = "p.name";
        } else {
            if (strpos($sort, "prod_name") !== FALSE)
                $sort = "p.name";
            elseif (strpos($sort, "listing_status") !== FALSE)
                $sort = "pr.listing_status";
        }

        if (empty($order))
            $order = "asc";

        if ($sort == "margin" || $sort == "profit") {
            $option["refresh_margin"] = 1;
        }


        $option["orderby"] = $sort . " " . $order;

        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
        $data["lang"] = $lang;

        if ($this->input->get("search")) {
            $data["objlist"] = $this->product_overview_model->get_product_list_v2($where, $option, $lang);
            $data["total"] = $this->product_overview_model->get_product_list_total_v2($where, $option);
        }

        $pconfig['total_rows'] = $data['total'];
        $this->pagination_service->set_show_count_tag(TRUE);
        $this->pagination_service->initialize($pconfig);

        $wms_warehouse_where["status"] = 1;
        $wms_warehouse_where["type != 'W'"] = null;
        $data["wms_wh"] = $this->wms_warehouse_service->get_list($wms_warehouse_where, array('limit' => -1, 'orderby' => 'warehouse_id'));

        $data["notice"] = notice($lang);
        $data["clist"] = $this->product_overview_model->price_service->get_platform_biz_var_service()->selling_platform_dao->get_list(array("type" => PLATFORM_TYPE, "status" => 1));
        $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
        $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
//      $data["searchdisplay"] = ($submit_search)?"":'style="display:none"';
        $data["searchdisplay"] = "";
        $this->load->view($this->overview_path . '/product_overview_v', $data);
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }

    public function js_overview()
    {
        $this->product_overview_model->print_overview_js();
    }
}


