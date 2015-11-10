<?php

class Order_fulfilment extends MY_Controller
{

    private $appId = "ORD0004";
    private $lang_id = "en";
    private $metapack_path;
    private $courier_path;
    private $default_delivery;

    public function __construct()
    {
        parent::__construct(FALSE);

        $public_method = array('invoice', 'custom_invoice', 'delivery_note');
        $this->load->library('service/authorization_service');
        if (in_array(strtolower($this->router->fetch_method()), $public_method) === FALSE) {
            $this->authorization_service->check_access_rights($this->getAppId(), "");
        }

        $this->load->helper(array('url', 'operator', 'notice', 'object'));
        $this->load->library('service/pagination_service');
        $this->load->library('service/context_config_service');
        $this->load->model('order/so_model');
        $this->load->library('service/inv_movement_service');
        $this->load->library('service/batch_tracking_info_service');
        $this->load->model('mastercfg/warehouse_model');

        //$this->metapack_path = $this->context_config_service->value_of('metapack_path');
        $this->courier_path = $this->context_config_service->value_of('courier_path');
        $this->metapack_path = $this->context_config_service->value_of('metapack_path');
        $this->default_delivery = $this->context_config_service->value_of("default_delivery_type");

    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function index($warehouse = "ES_HK")
    {
        $sub_app_id = $this->getAppId() . "00";

        $_SESSION["LISTPAGE"] = base_url() . "order/order_fulfilment/" . ($warehouse == "" ? "" : "index/" . $warehouse) . "?" . $_SERVER['QUERY_STRING'];

        $allow_partial = 0;

        if ($this->input->post("posted")) {
            $rsresult = "";
            $shownotice = 0;
            if ($_POST["allocate_type"] == "m") {
                if (empty($_POST["check"])) {
                    redirect($_SESSION["LISTPAGE"]);
                }
                $ff_option["solist"] = $_POST["check"];
            }

            if (!$allow_partial) {
                $ff_where["o_items"] = 0;
            }

            $u_where["modify_on <"] = date("Y-m-d H:i:s");
            $ff_where["so.status >"] = "2";
            $ff_where["so.status <"] = "5";
            $ff_where["so.hold_status"] = "0";
            $ff_where["so.refund_status"] = "0";
            $ffi_option["warehouse_id"] = $ff_option["warehouse_id"] = $this->input->post("warehouse_id");
            $ff_option["limit"] = -1;
            $ff_option["hide_client"] = 1;
            $ff_option["hide_payment"] = 1;
            $ff_option["hide_shipped_item"] = 1;
            $fulfil_list = $this->so_service->get_dao()->get_list_w_name($ff_where, $ff_option);
            $soid_vo = $this->so_service->get_soid_dao()->get();
            if ($fulfil_list) {
                foreach ($fulfil_list as $ff_obj) {
                    $update_so = $inventory = $update_al = $al_qty = array();
                    $skip = 0;
                    $so_no = $ff_obj->get_so_no();
                    $cur_total_items = count(explode("||", $ff_obj->get_items()));

                    if ($ffi_objlist = $this->so_service->get_soid_dao()->get_fulfil(array("so.so_no" => $so_no), $ffi_option)) {
                        $cur_ff_items = count((array)$ffi_objlist);
                        if (!$allow_partial && $cur_total_items != $cur_ff_items) {
                            $rsresult .= "{$so_no} -> 0 (error" . __LINE__ . ": Not enough real stock)\\n";
                            $shownotice = 1;
                            continue;
                        }

                        foreach ($ffi_objlist as $obj) {
                            foreach (explode("||", $obj->get_items()) as $item) {
                                list($cur_item_sku, $inv_qty) = explode("::", $item);
                                if (!isset($inventory[$cur_item_sku])) {
                                    $inventory[$cur_item_sku] = $inv_qty;
                                }
                                $cur_o_qty = $obj->get_outstanding_qty();
                                if ($inventory[$cur_item_sku] > 0 && $cur_o_qty > 0) {
                                    $adj_qty = ($cur_o_qty > $inventory[$cur_item_sku]) ? $inventory[$cur_item_sku] : $cur_o_qty;
                                    $obj->set_outstanding_qty($cur_o_qty - $adj_qty);
                                    $new_obj = clone $soid_vo;
                                    set_value($new_obj, $obj);
                                    $so_no = $obj->get_so_no();
                                    $line_no = $obj->get_line_no();
                                    $item_sku = $obj->get_item_sku();
                                    $update_so[$line_no][$item_sku] = $new_obj;
                                    $update_al[$line_no][$item_sku][$cur_item_sku] = $adj_qty;
                                    $inventory[$cur_item_sku] -= $adj_qty;
                                }
                            }
                            if ($obj->get_outstanding_qty() > 0) {
                                $rsresult .= "{$so_no} -> 0 (error" . __LINE__ . ":" . $obj->get_item_sku() . " Not enough real stock)\\n";
                                $skip = 1;
                                break;
                            }
                        }

                        if ($skip) {
                            $shownotice = 1;
                            continue;
                        }

                        if ($update_so) {
                            $soal_vo = $this->so_service->get_soal_dao()->get();
                            $inv_mv_vo = $this->inv_movement_service->get_dao()->get();
                            $error = "";
                            $success = 1;
                            $this->so_service->get_dao()->trans_start();
                            foreach ($update_so as $line_no => $soid_list) {
                                foreach ($soid_list as $item_sku => $soid_obj) {
                                    if ($this->so_service->get_soid_dao()->update($soid_obj, $u_where)) {
                                        $al_where["so_no"] = $so_no;
                                        $al_where["line_no"] = $line_no;
                                        $al_where["item_sku"] = $item_sku;
                                        $al_where["status"] = "1";
                                        foreach ($update_al[$line_no][$item_sku] as $item_sku => $al_qty) {
                                            $action = "update";
                                            if (!($soal_obj = $this->so_service->get_soal_dao()->get($al_where))) {
                                                unset($soal_obj);
                                                $soal_obj = clone $soal_vo;
                                                $action = "insert";
                                                set_value($soal_obj, $soid_obj);
                                                $soal_obj->set_status("1");
                                                $soal_obj->set_warehouse_id($_POST["warehouse_id"]);
                                                $soal_obj->set_qty("0");
                                            }
                                            $soal_obj->set_qty($soal_obj->get_qty() + $al_qty);
                                            if ($this->so_service->get_soal_dao()->$action($soal_obj)) {
                                                $ship_ref = $soal_obj->get_id();
                                                if ($action == "update") {
                                                    $inv_where["ship_ref"] = $ship_ref;
                                                    $inv_where["status"] = "AL";
                                                    $inv_mv_obj = $this->inv_movement_service->get_dao()->get($inv_where);
                                                } else {
                                                    $inv_mv_obj = clone $inv_mv_vo;
                                                    $inv_mv_obj->set_ship_ref($ship_ref);
                                                    $inv_mv_obj->set_sku($item_sku);
                                                    $inv_mv_obj->set_type("C");
                                                    $inv_mv_obj->set_from_location($_POST["warehouse_id"]);
                                                    $inv_mv_obj->set_status("AL");
                                                }
                                                $inv_mv_obj->set_qty($inv_mv_obj->get_qty() + $al_qty);
                                                if (!$this->inv_movement_service->get_dao()->$action($inv_mv_obj)) {
                                                    $success = 0;
                                                    $error = __LINE__ . " " . $this->db->_error_message();
                                                    break;
                                                }

                                            } else {
                                                $success = 0;
                                                $error = __LINE__ . " " . $this->db->_error_message();
                                                break;
                                            }
                                        }
                                        if (!$success) {
                                            break;
                                        }
                                    } else {
                                        $success = 0;
                                        $error = __LINE__ . " " . $this->db->_error_message();
                                        break;
                                    }
                                }
                                if (!$success) {
                                    break;
                                }
                            }
                            if ($success) {
                                if ($so_obj = $this->so_service->get_dao()->get(array("so_no" => $so_no))) {
                                    if (($os_qty = $this->so_service->get_soid_dao()->check_outstanding($so_no)) !== FALSE) {
                                        $run_update = 0;
                                        if ($os_qty == 0) {
                                            $so_obj->set_status("5");
                                            $run_update = 1;
                                        } elseif ($os_qty && $so_obj->get_status() != 4) {
                                            $so_obj->set_status("4");
                                            $run_update = 1;
                                        }
                                        if ($run_update) {
                                            if (!$this->so_service->get_dao()->update($so_obj, $u_where)) {
                                                $success = 0;
                                                $error = __LINE__ . " " . $this->db->_error_message();
                                            }
                                        }
                                    } else {
                                        $success = 0;
                                        $error = __LINE__ . " " . $this->db->_error_message();
                                    }
                                } else {
                                    $success = 0;
                                    $error = __LINE__ . " " . $this->db->_error_message();
                                }
                            }
                            if (!$success) {
                                $this->so_service->get_dao()->trans_rollback();
                                $shownotice = 1;
                            }
                            $rsresult .= "{$so_no} -> {$success} " . ($success ? "" : "(error:{$error})") . "\\n";
                            $this->so_service->get_dao()->trans_complete();
                        } else {
                            $rsresult .= "{$so_no} -> 0 (error" . __LINE__ . ": Not enough real stock)\\n";
                            $shownotice = 1;
                        }
                    } else {
                        $rsresult .= "{$so_no} -> 0 (error" . __LINE__ . ":)\\n";
                        $shownotice = 1;
                    }
                }
            }
            if ($shownotice) {
                $_SESSION["NOTICE"] = $rsresult;
            }
            redirect(current_url() . "?" . $_SERVER['QUERY_STRING']);
        }

        $where = array();
        $option = array();

        if ($this->input->get("so_no")) {
            $where["so.so_no LIKE "] = "%" . $this->input->get("so_no") . "%";
        }

        if ($this->input->get("platform_order_id")) {
            $where["platform_order_id LIKE "] = "%" . $this->input->get("platform_order_id") . "%";
        }

        if ($this->input->get("platform_id")) {
            $where["platform_id"] = $this->input->get("platform_id");
        }

        if ($this->input->get("order_create_date")) {
            fetch_operator($where, "order_create_date", $this->input->get("order_create_date"));
        }

        if ($this->input->get("expect_delivery_date")) {
            fetch_operator($where, "expect_delivery_date", $this->input->get("expect_delivery_date"));
        }

        if ($this->input->get("multiple")) {
            $where["multiple"] = $this->input->get("multiple");
        }

        if ($this->input->get("express")) {
            if ($this->input->get("express") == "Y") {
                $where["so.delivery_type_id <>"] = $this->default_delivery;
            } else {
                $where["so.delivery_type_id"] = $this->default_delivery;
            }
        }

        if ($this->input->get("item_sku")) {
            $where["item_sku LIKE "] = "%" . $this->input->get("item_sku") . "%";
        }

        if ($this->input->get("prod_name")) {
            $where["prod_name LIKE "] = "%" . $this->input->get("prod_name") . "%";
        }

        if ($this->input->get("outstanding_qty")) {
            fetch_operator($where, "outstanding_qty", $this->input->get("outstanding_qty"));
        }

        if ($this->input->get("delivery_name")) {
            $where["delivery_name LIKE "] = "%" . $this->input->get("delivery_name") . "%";
        }

        if ($this->input->get("delivery_country_id")) {
            $where["delivery_country_id"] = $this->input->get("delivery_country_id");
        }

        if ($this->input->get("note")) {
            $where["note LIKE "] = "%" . $this->input->get("note") . "%";
        }

        if ($this->input->get("status") == 4) {
            $where["status"] = $this->input->get("status");
        } elseif ($this->input->get("status") == "new") {
            $where["so.status >"] = "2";
            $where["so.status <"] = "4";
        } else {
            $where["so.status >"] = "2";
            $where["so.status <"] = "5";
        }
        $where["so.hold_status"] = "0";
        $where["so.refund_status"] = "0";

        //2214 add payment mode
        if ($this->input->get("payment_gateway_id")) {
            $where["payment_gateway_id"] = $this->input->get("payment_gateway_id");
        }

        if ($this->input->get("website_status")) {
            $where["si.website_status"] = $this->input->get("website_status");
        }

        $sort = $this->input->get("sort");
        $order = $this->input->get("order");

        $limit = '1000';

        $pconfig['base_url'] = $_SESSION["LISTPAGE"];
        $option["limit"] = $pconfig['per_page'] = $limit;
        if ($option["limit"]) {
            $option["offset"] = $this->input->get("per_page");
        }

        if (empty($sort))
            $sort = "expect_delivery_date";

        if (empty($order))
            $order = "asc";

        $option["orderby"] = $sort . " " . $order;

        $data["default_delivery"] = $this->default_delivery;
        $data["whlist"] = $this->warehouse_service->get_list(array(), array("limit" => -1, "result_type" => "array"));
        $data["cclist"] = $this->so_service->get_dao()->get_cc_list(array("status >" => 2, "status <" => 5, "hold_status" => 0, "refund_status" => 0), array("orderby" => "delivery_country_id", "limit" => -1));
        $option["warehouse_id"] = $data["warehouse"] = $warehouse ? $warehouse : $data["whlist"][0]["id"];
        $option["notes"] = 1;
        $option["hide_client"] = 1;
        //2214 add the payment mode
        $option["hide_payment"] = 0;
        $option["show_git"] = 1;
        $option["hide_shipped_item"] = 1;


        if ($sort == "item_sku" || $where["si.website_status"]) {
            $data["objlist"] = $this->so_service->get_dao()->get_list_by_item_sku($where, $option);
            $data["total_order"] = $this->so_service->get_dao()->get_list_w_name($where, array("num_rows" => 1, "hide_client" => 1, "hide_payment" => 0, "hide_shipped_item" => 1));
            $data["total_item"] = $pconfig['total_rows'] = $this->so_service->get_dao()->get_list_by_item_sku($where, array("total_items" => 1, "hide_shipped_item" => 1));
        } else {
            $data["objlist"] = $this->so_service->get_dao()->get_list_w_name($where, $option);
            $data["total_order"] = $pconfig['total_rows'] = $this->so_service->get_dao()->get_list_w_name($where, array("num_rows" => 1, "hide_client" => 1, "hide_payment" => 0, "hide_shipped_item" => 1));
            $data["total_item"] = $this->so_service->get_dao()->get_list_by_item_sku($where, array("total_items" => 1, "hide_shipped_item" => 1));
        }
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
        $data["lang"] = $lang;

        $this->pagination_service->set_show_count_tag(FALSE);
        $this->pagination_service->initialize($pconfig);
        $objlist = $this->so_model->get_allocation_plan_order(array("status" => 1), array("limit" => "-1"));
        if ($objlist) {
            foreach ($objlist AS $obj) {
                $data["cps_allocation_order"][] = $obj->get_so_no();
            }
        }
        $data["notice"] = notice($lang);
        $data["valid_website_status"] = $this->so_model->get_valid_website_status_list();
        $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
        $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
        $data["searchdisplay"] = "";
        $this->load_view('order/order_fulfilment/order_fulfilment_index_v', $data);
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }

    public function test_courier_feed($so_no, $courier = "FEDEX2", $mawb = "")
    {
        echo "<pre>";
        var_dump("Entering debug");
        $success_so[] = $so_no;
        $ret = $this->so_model->generate_courier_file($success_so, $courier, $mawb, true);
    }

    public function to_ship()
    {
        set_time_limit(60);
        $sub_app_id = $this->getAppId() . "01";

        $_SESSION["LISTPAGE"] = base_url() . "order/order_fulfilment/to_ship/?" . $_SERVER['QUERY_STRING'];

        if ($this->input->post("posted") && $_POST["check"] && $_POST["dispatch_type"] != 'c') {
            $rsresult = "";
            $shownotice = 0;

            $u_where["modify_on <="] = date("Y-m-d H:i:s");
            $r_where["soal.status"] = 1;
            if ($_POST["dispatch_type"] != 'r') {
                $r_where["so.hold_status"] = "0";
                $r_where["so.refund_status"] = "0";
            }
            $r_option["limit"] = -1;
            $r_option["solist"] = $_POST["check"];
            $rlist = $this->so_service->get_soal_dao()->get_in_so_list($r_where, $r_option);

            $success_so = $update_so = array();
            $this->so_service->get_dao()->trans_start();
            foreach ($rlist as $obj) {
                $so_no = $obj->get_so_no();
                $line_no = $obj->get_line_no();
                $item_sku = $obj->get_item_sku();
                $al_id = $obj->get_id();
                $update_so[$so_no][$al_id] = $obj;
            }
            if ($update_so) {
                $inv_where["type"] = "C";
                $inv_where["status"] = "AL";
                foreach ($update_so as $so_no => $soal_list) {
                    $error = "";
                    $success = 1;
                    $this->so_service->get_dao()->trans_start();
                    if ($_POST["dispatch_type"] == 'r') {
                        foreach ($soal_list as $al_id => $soal_obj) {
                            $inv_where["ship_ref"] = $al_id;
                            $soid_where["so_no"] = $so_no;
                            $soid_where["line_no"] = $soal_obj->get_line_no();
                            $soid_where["item_sku"] = $soal_obj->get_item_sku();
                            $cur_u_where = isset($soid_u_where[$soid_where["so_no"]][$soid_where["line_no"]][$soid_where["item_sku"]]) ? array("modify_on <=" => $soid_u_where[$soid_where["so_no"]][$soid_where["line_no"]][$soid_where["item_sku"]]) : $u_where;
                            if (($inv_obj = $this->inv_movement_service->get_dao()->get($inv_where)) && ($soid_obj = $this->so_service->get_soid_dao()->get($soid_where))) {
                                $soid_obj->set_outstanding_qty($soid_obj->get_outstanding_qty() + $soal_obj->get_qty());
                                if (!(($rs1 = $this->so_service->get_soal_dao()->delete($soal_obj)) && ($rs2 = $this->inv_movement_service->get_dao()->delete($inv_obj)) && ($rs3 = $this->so_service->get_soid_dao()->update($soid_obj, $cur_u_where)))) {
                                    $success = 0;
                                    $error = __LINE__ . "[" . ($rs1 ? 1 : 0) . ($rs2 ? 1 : 0) . ($rs3 ? 1 : 0) . "]" . $this->db->_error_message();
                                    break;
                                } else {
                                    $soid_u_where[$soid_where["so_no"]][$soid_where["line_no"]][$soid_where["item_sku"]] = date("Y-m-d H:i:s");
                                }
                            } else {
                                $success = 0;
                                $error = __LINE__ . "[" . ($inv_obj ? 1 : 0) . ($soid_obj ? 1 : 0) . "]" . $this->db->_error_message();
                                break;
                            }
                        }
                        if ($success) {
                            $so_obj = $this->so_service->get(array("so_no" => $so_no));
                            if ($this->so_service->get_soal_dao()->get_num_rows(array("so_no" => $so_no))) {
                                $so_obj->set_status("4");
                            } else {
                                $so_obj->set_status("3");
                            }
                            if (!$this->so_service->update($so_obj)) {
                                $success = 0;
                                $error = __LINE__ . " " . $this->db->_error_message();
                            }
                        }
                    } else {
                        $sh_no = $this->so_service->get_next_sh_no($so_no);
                        $so_obj = $this->so_service->get(array("so_no" => $so_no));
                        $courier_id = $this->_check_courier($this->input->post("courier_id"), $so_obj, $_POST["courier"][$so_no]);
                        $sosh_vo = $this->so_service->get_sosh_dao()->get();
                        $sosh_vo->set_sh_no($sh_no);
                        $sosh_vo->set_courier_id($courier_id);
                        $sosh_vo->set_status(1);
                        if ($rs1 = $this->so_service->get_sosh_dao()->insert($sosh_vo)) {
                            foreach ($soal_list as $al_id => $soal_obj) {
                                $inv_where["ship_ref"] = $al_id;
                                if ($inv_obj = $this->inv_movement_service->get_dao()->get($inv_where)) {
                                    $inv_obj->set_ship_ref($sh_no);
                                    $inv_obj->set_status("OT");

                                    $soal_obj->set_sh_no($sh_no);
                                    $soal_obj->set_status(2);
                                    if (!(($rs1 = $this->inv_movement_service->get_dao()->update($inv_obj)) && ($rs2 = $this->so_service->get_soal_dao()->update($soal_obj)))) {
                                        $success = 0;
                                        $error = __LINE__ . "[" . ($rs1 ? 1 : 0) . ($rs2 ? 1 : 0) . "]" . $this->db->_error_message();
                                        break;
                                    }
                                } else {
                                    $success = 0;
                                    $error = __LINE__ . " " . $this->db->_error_message();
                                    break;
                                }
                            }
                        } else {
                            $success = 0;
                            $error = __LINE__ . " " . $this->db->_error_message();
                        }
                    }
                    if (!$success) {
                        $this->so_service->get_dao()->trans_rollback();
                        $shownotice = 1;
                    } else {
                        $success_so[] = $so_no;
                    }
                    $rsresult .= "{$so_no} -> {$success} " . ($success ? "" : "(error:{$error})") . "\\n";
                    $this->so_service->get_dao()->trans_complete();
                }
            }
            if ($shownotice) {
                $_SESSION["NOTICE"] = $rsresult;
            }
            if ($_POST["dispatch_type"] == 'r') {
                redirect(current_url() . "?" . $_SERVER['QUERY_STRING']);
            } else {
                //$this->generate_metapack_file($success_so);
                $this->generate_courier_file($success_so, 'd');
            }
        }

        $where = array();
        $option = array();

        if ($this->input->get("so_no")) {
            $where["so.so_no LIKE "] = "%" . $this->input->get("so_no") . "%";
        }

        if ($this->input->get("platform_order_id")) {
            $where["platform_order_id LIKE "] = "%" . $this->input->get("platform_order_id") . "%";
        }

        if ($this->input->get("platform_id")) {
            $where["platform_id"] = $this->input->get("platform_id");
        }

        if ($this->input->get("order_create_date")) {
            fetch_operator($where, "order_create_date", $this->input->get("order_create_date"));
        }

        if ($this->input->get("amount")) {
            fetch_operator($where, "so.amount", $this->input->get("amount"));
        }

        if ($this->input->get("multiple")) {
            $where["multiple"] = $this->input->get("multiple");
        }

        if ($this->input->get("express")) {
            if ($this->input->get("express") == "Y") {
                $where["so.delivery_type_id <>"] = $this->default_delivery;
            } else {
                $where["so.delivery_type_id"] = $this->default_delivery;
            }
        }

        if ($this->input->get("item_sku")) {
            $where["item_sku LIKE "] = "%" . $this->input->get("item_sku") . "%";
        }

        if ($this->input->get("prod_name")) {
            $where["prod_name LIKE "] = "%" . $this->input->get("prod_name") . "%";
        }

        if ($this->input->get("outstanding_qty")) {
            fetch_operator($where, "outstanding_qty", $this->input->get("outstanding_qty"));
        }

        if ($this->input->get("delivery_name")) {
            $where["delivery_name LIKE "] = "%" . $this->input->get("delivery_name") . "%";
        }

        if ($this->input->get("delivery_postcode")) {
            $where["delivery_postcode LIKE "] = "%" . $this->input->get("delivery_postcode") . "%";
        }

        if ($this->input->get("delivery_country_id")) {
            $where["delivery_country_id"] = $this->input->get("delivery_country_id");
        }

        if ($this->input->get("warehouse_id")) {
            $where["warehouse_id"] = $this->input->get("warehouse_id");
        }

        if ($this->input->get("delivery")) {
            $where["so.delivery_type_id"] = $this->input->get("delivery");
        }

        if ($this->input->get("note")) {
            $where["note LIKE "] = "%" . $this->input->get("note") . "%";
        }

        if ($this->input->get("payment_gateway_id")) {
            $where["payment_gateway_id"] = $this->input->get("payment_gateway_id");
        }

        if ($this->input->get("status")) {
            $where["status"] = $this->input->get("status");
        } else {
            $where["so.status >"] = "3";
            $where["so.status <"] = "6";
        }

        $sort = $this->input->get("sort");
        $order = $this->input->get("order");

        $limit = '1000';

        $pconfig['base_url'] = $_SESSION["LISTPAGE"];
        $option["limit"] = $pconfig['per_page'] = $limit;
        if ($option["limit"]) {
            $option["offset"] = $this->input->get("per_page");
        }

        if (empty($sort))
            $sort = "expect_delivery_date";

        if (empty($order))
            $order = "asc";

        $option["orderby"] = $sort . " " . $order;
        $option["list_type"] = "toship";

        $data["default_delivery"] = $this->default_delivery;
        $data["dellist"] = $this->so_service->get_pbv_srv()->get_pc_dao()->get_list();
        $data["whlist"] = $this->warehouse_service->get_list(array(), array("limit" => -1, "result_type" => "array"));
        $option["notes"] = 1;
        $option["hide_client"] = 1;
        $option["hide_payment"] = 0;
        if ($sort == "item_sku") {
            $data["objlist"] = $this->so_service->get_soal_dao()->get_allocate_list_by_item_sku($where, $option);
            $data["total_item"] = $pconfig['total_rows'] = $this->so_service->get_soal_dao()->get_allocate_list_by_item_sku($where, array("total_items" => 1, "list_type" => "toship"));
            $data["total_order"] = $this->so_service->get_soal_dao()->get_allocate_list($where, array("num_rows" => 1, "list_type" => "toship", "hide_client" => 1));
        } else {
            $data["objlist"] = $this->so_service->get_soal_dao()->get_allocate_list($where, $option);
            $data["total_item"] = $this->so_service->get_soal_dao()->get_allocate_list_by_item_sku($where, array("total_items" => 1, "list_type" => "toship"));
            $data["total_order"] = $pconfig['total_rows'] = $this->so_service->get_soal_dao()->get_allocate_list($where, array("num_rows" => 1, "list_type" => "toship", "hide_client" => 1));
        }

        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
        $data["lang"] = $lang;

        $this->pagination_service->set_show_count_tag(FALSE);
        $this->pagination_service->initialize($pconfig);

        $data["notice"] = notice($lang);

        $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
        $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
        $data["searchdisplay"] = "";

        $this->load_view('order/order_fulfilment/order_fulfilment_to_ship_v', $data);
        if ($_POST["dispatch_type"] == 'c') {
            $this->generate_allocate_file();
        } else {
            //redirect(current_url()."?".$_SERVER['QUERY_STRING']);
        }
    }

    public function _check_courier($courier_id, $so_obj, $courier)
    {
        if ($courier == "") {
            if ($courier_id == "AMS") {
                $func_amount = $so_obj->get_amount() * $so_obj->get_rate();
                return ($func_amount < 80) ? "USPSPM" : "UPS";
            } elseif ($courier_id == "ILG") {
                return ($so_obj->get_delivery_type_id() != $this->default_delivery) ? "trackable service" : $courier_id;
            }
            return $courier_id;
        } else {
            return $courier;
        }
    }

    public function generate_courier_file($checked, $sp = '')
    {
        if ($checked) {
            $courier = $this->input->post("courier_id");
            $mawb = $this->input->post("mawb");
            $ret = $this->so_model->generate_courier_file($checked, $courier, $mawb);

            $_SESSION['courier_file'] = $ret;
            redirect(current_url() . "?" . $_SERVER['QUERY_STRING']);
        }
    }

    public function generate_allocate_file()
    {
        $ret = $this->so_model->generate_allocate_file();
        $_SESSION['allocate_file'] = $ret;
        redirect(current_url() . "?" . $_SERVER['QUERY_STRING']);
    }

    public function dispatch()
    {
        set_time_limit(60);
        $sub_app_id = $this->getAppId() . "02";

        $_SESSION["LISTPAGE"] = base_url() . "order/order_fulfilment/dispatch/?" . $_SERVER['QUERY_STRING'];
        if ($this->input->post("posted") && $_POST["check"]) {
            $rsresult = "";
            $shownotice = 0;

            if ($_POST["dispatch_type"] == 'c') {
                $success_so = array();
                $u_where["modify_on <="] = $this->input->post("db_time");
                $u_where["status"] = 1;
                //$u_where["courier_id !="] = $courier_id;
                foreach ($_POST["check"] as $sh_no => $so_no) {
                    $error = "";
                    $success = 1;
                    $u_where["sh_no"] = $sh_no;
                    if ($sh_obj = $this->so_service->get_sosh_dao()->get($u_where)) {
                        $so_obj = $this->so_service->get(array("so_no" => $so_no));
                        $courier_id = $this->_check_courier($this->input->post("courier_id"), $so_obj, $_POST["courier"][$sh_no]);
                        $sh_obj->set_courier_id($courier_id);
                        $sh_obj->set_tracking_no("");
                        if ($this->so_service->get_sosh_dao()->update($sh_obj) === FALSE) {
                            $success = 0;
                            $error = __LINE__ . " " . $this->db->_error_message();
                        }
                    } else {
                        $success = 0;
                        $error = __LINE__ . " " . $this->db->_error_message();
                    }
                    if (!$success) {
                        $shownotice = 1;
                    } else {
                        $success_so[] = $so_no;
                    }
                    $rsresult .= "{$sh_no} -> {$success} " . ($success ? "" : "(error:{$error})") . "\\n";
                }
            } else {
                $u_where["modify_on <="] = date("Y-m-d H:i:s");
                $r_where["soal.status"] = 2;
                if ($_POST["dispatch_type"] != 'r') {
                    $r_where["so.hold_status"] = "0";
                    $r_where["so.refund_status"] = "0";
                }
                $r_option["limit"] = -1;
                $r_option["shlist"] = array_keys($_POST["check"]);
                $rlist = $this->so_service->get_soal_dao()->get_in_so_list($r_where, $r_option);
                $update_sh = array();
                $this->so_service->get_dao()->trans_start();
                foreach ($rlist as $obj) {
                    $sh_no = $obj->get_sh_no();
                    $line_no = $obj->get_line_no();
                    $item_sku = $obj->get_item_sku();
                    $al_id = $obj->get_id();
                    $update_sh[$sh_no][$al_id] = $obj;
                }
                if ($update_sh) {
                    $inv_where["type"] = "C";
                    $inv_where["status"] = "OT";
                    foreach ($update_sh as $sh_no => $soal_list) {
                        $error = "";
                        $success = 1;
                        $this->so_service->get_dao()->trans_start();
                        $sosh_obj = $this->so_service->get_sosh_dao()->get(array("sh_no" => $sh_no));

                        if ($_POST["dispatch_type"] == 'r') {
                            foreach ($soal_list as $al_id => $soal_obj) {
                                $inv_where["ship_ref"] = $sh_no;
                                $inv_where["sku"] = $soal_obj->get_item_sku();
                                $so_no = $soal_obj->get_so_no();
                                $soid_where["so_no"] = $so_no;
                                $soid_where["line_no"] = $soal_obj->get_line_no();
                                $soid_where["item_sku"] = $soal_obj->get_item_sku();
                                $cur_u_where = isset($soid_u_where[$soid_where["so_no"]][$soid_where["line_no"]][$soid_where["item_sku"]]) ? array("modify_on <=" => $soid_u_where[$soid_where["so_no"]][$soid_where["line_no"]][$soid_where["item_sku"]]) : $u_where;
                                if (($inv_obj = $this->inv_movement_service->get_dao()->get($inv_where)) && ($soid_obj = $this->so_service->get_soid_dao()->get($soid_where))) {
                                    $soid_obj->set_outstanding_qty($soid_obj->get_outstanding_qty() + $soal_obj->get_qty());
                                    if (!(($rs1 = $this->so_service->get_soal_dao()->delete($soal_obj)) && ($rs2 = $this->inv_movement_service->get_dao()->delete($inv_obj)) && ($rs3 = $this->so_service->get_soid_dao()->update($soid_obj, $cur_u_where)))) {
                                        $success = 0;
                                        $error = __LINE__ . "[" . ($rs1 ? 1 : 0) . ($rs2 ? 1 : 0) . ($rs3 ? 1 : 0) . "]" . $this->db->_error_message();
                                        break;
                                    } else {
                                        $soid_u_where[$soid_where["so_no"]][$soid_where["line_no"]][$soid_where["item_sku"]] = date("Y-m-d H:i:s");
                                    }
                                } else {
                                    $success = 0;
                                    $error = __LINE__ . "[" . ($inv_obj ? 1 : 0) . ($soid_obj ? 1 : 0) . "]" . $this->db->_error_message();
                                    break;
                                }
                            }
                            if ($success) {
                                $so_obj = $this->so_service->get(array("so_no" => $so_no));
                                if ($this->so_service->get_soal_dao()->get_num_rows(array("so_no" => $so_no))) {
                                    $so_obj->set_status("4");
                                } else {
                                    $so_obj->set_status("3");
                                }
                                if (!(($rs1 = $this->so_service->update($so_obj)) && ($rs2 = $this->so_service->get_sosh_dao()->delete($sosh_obj)))) {
                                    $success = 0;
                                    $error = __LINE__ . "[" . ($rs1 ? 1 : 0) . ($rs2 ? 1 : 0) . "]" . $this->db->_error_message();
                                }
                            }
                        } else {
                            $sosh_obj->set_status("2");
                            $sosh_obj->set_tracking_no($_POST["tracking"][$sh_no]);
                            if ($this->so_service->get_sosh_dao()->update($sosh_obj)) {
                                foreach ($soal_list as $al_id => $soal_obj) {
                                    $soal_obj->set_status("3");
                                    if (!$this->so_service->get_soal_dao()->update($soal_obj)) {
                                        $success = 0;
                                        $error = __LINE__ . " " . $this->db->_error_message();
                                        break;
                                    }
                                }
                            } else {
                                $success = 0;
                                $error = __LINE__ . " " . $this->db->_error_message();
                            }
                            if ($success) {
                                $so_obj = $this->so_service->get(array("so_no" => $soal_obj->get_so_no()));
                                if ($this->so_service->get_soal_dao()->get_num_rows(array("so_no" => $so_no, "status" => 1)) == 0) {
                                    if (!$this->so_service->update_complete_order($so_obj, 0)) {
                                        $success = 0;
                                        $error = __LINE__ . " " . $this->db->_error_message();
                                    }
                                }

                                if (substr($so_obj->get_platform_id(), 0, 2) != "AM" && substr($so_obj->get_platform_id(), 0, 2) != "TS" && $so_obj->get_biz_type() != "SPECIAL") {
                                    $this->so_service->fire_dispatch($so_obj, $sh_no);
                                }
                            }
                        }
                        if (!$success) {
                            $this->so_service->get_dao()->trans_rollback();
                            $shownotice = 1;
                        }
                        $rsresult .= "{$sh_no} -> {$success} " . ($success ? "" : "(error:{$error})") . "\\n";
                        $this->so_service->get_dao()->trans_complete();
                    }
                }
            }

            if ($shownotice) {
                $_SESSION["NOTICE"] = $rsresult;
            }

            if ($_POST["dispatch_type"] == 'c') {
                //$this->generate_metapack_file($success_so);
                $this->generate_courier_file($success_so);
            } else {
                redirect(current_url() . "?" . $_SERVER['QUERY_STRING']);
            }
        }

        $where = array();
        $option = array();

        if ($this->input->get("so_no")) {
            $where["so.so_no LIKE "] = "%" . $this->input->get("so_no") . "%";
        }

        if ($this->input->get("platform_order_id")) {
            $where["platform_order_id LIKE "] = "%" . $this->input->get("platform_order_id") . "%";
        }

        if ($this->input->get("platform_id")) {
            $where["platform_id"] = $this->input->get("platform_id");
        }

        if ($this->input->get("order_create_date")) {
            fetch_operator($where, "order_create_date", $this->input->get("order_create_date"));
        }

        if ($this->input->get("expect_delivery_date")) {
            fetch_operator($where, "expect_delivery_date", $this->input->get("expect_delivery_date"));
        }

        if ($this->input->get("amount")) {
            fetch_operator($where, "so.amount", $this->input->get("amount"));
        }

        if ($this->input->get("multiple")) {
            $where["multiple"] = $this->input->get("multiple");
        }

        if ($this->input->get("express")) {
            if ($this->input->get("express") == "Y") {
                $where["so.delivery_type_id <>"] = $this->default_delivery;
            } else {
                $where["so.delivery_type_id"] = $this->default_delivery;
            }
        }

        if ($this->input->get("item_sku")) {
            $where["item_sku LIKE "] = "%" . $this->input->get("item_sku") . "%";
        }

        if ($this->input->get("prod_name")) {
            $where["prod_name LIKE "] = "%" . $this->input->get("prod_name") . "%";
        }

        if ($this->input->get("outstanding_qty")) {
            fetch_operator($where, "outstanding_qty", $this->input->get("outstanding_qty"));
        }

        if ($this->input->get("delivery_name")) {
            $where["delivery_name LIKE "] = "%" . $this->input->get("delivery_name") . "%";
        }

        if ($this->input->get("delivery_postcode")) {
            $where["delivery_postcode LIKE "] = "%" . $this->input->get("delivery_postcode") . "%";
        }

        if ($this->input->get("delivery_country_id")) {
            $where["delivery_country_id"] = $this->input->get("delivery_country_id");
        }

        if ($this->input->get("sh_no")) {
            $where["soal.sh_no LIKE"] = "%" . $this->input->get("sh_no") . "%";
        }

        if ($this->input->get("warehouse_id")) {
            $where["warehouse_id"] = $this->input->get("warehouse_id");
        }

        if ($this->input->get("tracking_no")) {
            $where["tracking_no LIKE"] = "%" . $this->input->get("warehouse_id") . "%";
        }

        if ($this->input->get("note")) {
            $where["note LIKE "] = "%" . $this->input->get("note") . "%";
        }

        if ($this->input->get("status")) {
            $where["status"] = $this->input->get("status");
        } else {
            $where["so.status >"] = "3";
            $where["so.status <"] = "6";
        }

        $sort = $this->input->get("sort");
        $order = $this->input->get("order");

        $limit = '1000';

        $pconfig['base_url'] = $_SESSION["LISTPAGE"];
        $option["limit"] = $pconfig['per_page'] = $limit;
        if ($option["limit"]) {
            $option["offset"] = $this->input->get("per_page");
        }

        if (empty($sort))
            $sort = "expect_delivery_date";

        if (empty($order))
            $order = "asc";

        $option["orderby"] = $sort . " " . $order;
        $option["list_type"] = "dispatch";

        $data["default_delivery"] = $this->default_delivery;
        $data["whlist"] = $this->warehouse_service->get_list(array(), array("limit" => -1, "result_type" => "array"));
        $option["notes"] = 1;
        $option["hide_client"] = 1;
        if ($sort == "item_sku") {
            $data["objlist"] = $this->so_service->get_soal_dao()->get_allocate_list_by_item_sku($where, $option);
            $data["total_item"] = $pconfig['total_rows'] = $this->so_service->get_soal_dao()->get_allocate_list_by_item_sku($where, array("total_items" => 1, "list_type" => "dispatch"));
            $data["total_order"] = $this->so_service->get_soal_dao()->get_allocate_list($where, array("num_rows" => 1, "list_type" => "dispatch", "hide_client" => 1));
        } else {
            $data["objlist"] = $this->so_service->get_soal_dao()->get_allocate_list($where, $option);
            $data["total_order"] = $pconfig['total_rows'] = $this->so_service->get_soal_dao()->get_allocate_list($where, array("num_rows" => 1, "list_type" => "dispatch", "hide_client" => 1));
            $data["total_item"] = $this->so_service->get_soal_dao()->get_allocate_list_by_item_sku($where, array("total_items" => 1, "list_type" => "dispatch"));
        }
        $data["db_time"] = $this->so_service->get_db_time();

        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
        $data["lang"] = $lang;

        $this->pagination_service->set_show_count_tag(FALSE);
        $this->pagination_service->initialize($pconfig);

        $data["notice"] = notice($lang);

        $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
        $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
        $data["searchdisplay"] = "";

        $this->load_view('order/order_fulfilment/order_fulfilment_dispatch_v', $data);
    }

    public function add_note($so_no = "", $line = "")
    {
        if ($so_no && $line != "") {
            $sub_app_id = $this->getAppId() . "00";
            $_SESSION["LISTPAGE"] = base_url() . "order/order_fulfilment/add_note/$so_no/$line?" . $_SERVER['QUERY_STRING'];
            if ($this->input->post("posted")) {
                if (isset($_SESSION["obj"])) {
                    $this->so_service->get_son_dao()->include_vo();
                    $data["obj"] = unserialize($_SESSION["obj"]);
                    $data["obj"]->set_note($this->input->post("note"));
                    if (!$this->so_service->get_son_dao()->insert($data["obj"])) {
                        $_SESSION["NOTICE"] = __LINE__ . ":" . $this->db->_error_message();
                        $_SESSION["NOTICE"] = __LINE__ . ":" . $this->db->last_query();
                    } else {
                        unset($_SESSION["obj"]);
                        $data["success"] = 1;
                    }
                }
            }
            include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
            $data["lang"] = $lang;

            if (empty($data["obj"])) {
                if (($data["obj"] = $this->so_service->get_son_dao()->get()) === FALSE) {
                    $_SESSION["NOTICE"] = __LINE__ . ":" . $this->db->_error_message();
                } else {
                    $data["obj"]->set_so_no($so_no);
                    $data["obj"]->set_type("O");
                    $_SESSION["obj"] = serialize($data["obj"]);
                }
            }

            $data["objlist"] = $this->so_service->get_son_dao()->get_list(array("so_no" => $so_no), array("orderby" => "create_on desc", "limit" => 5));
            $data["so_no"] = $so_no;
            $data["line"] = $line;
            $data["notice"] = notice($lang);
            $this->load_view('order/order_fulfilment/order_fulfilment_add_note_v', $data);
        } else {
            show_404();
        }
    }

    public function error_in_allocate_file()
    {
        $ret = $this->so_model->error_in_allocate_file();
    }

    public function get_allocate_file($filename = "")
    {
        if ($filename == "") {
            exit;
        } else {
            $data['filename'] = $this->courier_path . $filename;
            $data['output_filename'] = $filename;
            $this->load->view('order/order_fulfilment/readfile', $data);
        }
    }

    public function get_courier_file($filename = "")
    {
        if ($filename == "") {
            exit;
        } else {
            $data['filename'] = $this->courier_path . $filename;
            $data['output_filename'] = $filename;
            $this->load->view('order/order_fulfilment/readfile', $data);
        }
    }

    public function generate_metapack_file($checked)
    {
        if ($checked) {
            $courier = $this->input->post("courier_id");
            $ret = $this->so_service->generate_metapack_file($checked, $courier);
            $_SESSION["metapack_file"] = $ret;
            redirect(current_url() . "?" . $_SERVER['QUERY_STRING']);
        }
    }

    public function get_metapack_file($filename = "")
    {
        if ($filename == "") {
            exit;
        } else {
            $data["filename"] = $this->metapack_path . $filename;
            $data["output_filename"] = $filename;
            $this->load->view('order/order_fulfilment/readfile', $data);
        }
    }

    public function invoice()
    {
        $sub_app_id = $this->getAppId() . "03";
        $data["message"] = $this->so_service->get_print_invoice_content($_POST["check"]);
        $this->load->view("order/order_fulfilment/invoice", $data);

    }

    public function custom_invoice()
    {
        $sub_app_id = $this->getAppId() . "03";
        $data["message"] = $this->so_service->get_custom_invoice_content($_POST["check"]);
        $this->load->view("order/order_fulfilment/invoice", $data);
    }

    public function delivery_note()
    {
        $sub_app_id = $this->getAppId() . "03";
        $data["message"] = $this->so_service->get_delivery_note_content($_POST["check"]);
        $this->load->view("order/order_fulfilment/invoice", $data);
    }

    public function import_trackingfile()
    {
        set_time_limit(120);
        $wh_list = array("ams", "im", "rmr");
        foreach ($wh_list as $wh) {
            $this->batch_tracking_info_service->cron_tracking_info($wh);
        }
        $data["result"] = $_SESSION["result"];
        $this->load->view("order/order_fulfilment/tracking_info_result_v", $data);
    }

    public function _get_metapack_path()
    {
        return $this->metapack_path;
    }

    public function _get_courier_path()
    {
        return $this->courier_path;
    }
}



