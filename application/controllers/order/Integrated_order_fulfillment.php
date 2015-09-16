<?php

class Integrated_order_fulfillment extends MY_Controller
{

    private $appId = "ORD0023";
    private $lang_id = "en";
    private $metapack_path;
    private $courier_path;
    private $default_delivery;

    public function __construct()
    {
        parent::__construct(FALSE);

        $public_method = array('invoice', 'custom_invoice', 'delivery_note', 'cron_generate_courier_file');

        if (in_array(strtolower($this->router->fetch_method()), $public_method) === FALSE) {
            $this->sc['Authorization']->checkAccessRights($this->getAppId(), "");
        }

        // $this->load->library('service/so_service');

        // $this->load->library('service/batch_tracking_info_service');

        // $this->load->library('service/integrated_order_fulfillment_service');
        // $this->load->library('service/batch_service');
        // $this->load->library('dao/courier_feed_dao.php');
        // $this->courier_feed_dao = new Courier_feed_dao();


        //$this->metapack_path = $this->sc['ContextConfig']->valueOf('metapack_path');
        $this->courier_path = $this->sc['ContextConfig']->valueOf('courier_path');
        $this->metapack_path = $this->sc['ContextConfig']->valueOf('metapack_path');
        $this->default_delivery = $this->sc['ContextConfig']->valueOf("default_delivery_type");

        # add on to this list whatever courier user want to display
        $this->courier_list = array(
            "DHL",
            "DHLHKD",
            "DHLBBX",
            "HK_Post",
            "IM",
            "TOLL",
            "TNT",
            "DPD",
            "ARAMEX",
            "ARAMEX_COD",
            "RMR",
            "FEDEX",
            "FEDEX2",
            "DPD_NL",
            "MRW",
            "NEW_QUANTIUM",
            "QUANTIUM",
            "SF_EXPRESS",
            "TAQBIN"
        );
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function index($warehouse = "ES_HK")
    {
        $sub_app_id = $this->getAppId() . "00";

        $_SESSION["LISTPAGE"] = base_url() . "order/integrated_order_fulfillment/" . ($warehouse == "" ? "" : "index/" . $warehouse) . "?" . $_SERVER['QUERY_STRING'];

        $allow_partial = 0;

        if ($this->input->post("posted")) {
            $check_so_no = array();
            if ($_POST["allocate_type"] == "m") {
                if (empty($_POST["check"])) {
                    redirect($_SESSION["LISTPAGE"]);
                }

                $check_so_no = $_POST["check"];
            }

            $this->getWmsAllocationPlanOrder($check_so_no);

            redirect(current_url() . "?" . $_SERVER['QUERY_STRING']);
        }

        $where = array();
        $option = array();

        if ($this->input->get("so_no")) {
            $where["iof.so_no"] = $this->input->get("so_no");
        }

        if ($this->input->get("platform_order_id")) {
            $where["platform_order_id"] = $this->input->get("platform_order_id");
        }

        if ($this->input->get("platform_id")) {
            $where["platform_id"] = $this->input->get("platform_id");
        }

        if ($this->input->get("rec_courier")) {
            $where["iof.rec_courier"] = $this->input->get("rec_courier");
        }

        if ($this->input->get("order_create_date")) {
            fetch_operator($where, "order_create_date", $this->input->get("order_create_date"));
        }

        if ($this->input->get("expect_delivery_date")) {
            fetch_operator($where, "expect_delivery_date", $this->input->get("expect_delivery_date"));
        }

        if ($this->input->get("multiple") === '1') {
            $where["order_total_sku >"] = 1;
        } elseif ($this->input->get("multiple") === '0') {
            $where["order_total_sku <="] = 1;
        }

        if ($this->input->get("express")) {
            if ($this->input->get("express") == "Y") {
                $where["iof.delivery_type_id <>"] = $this->default_delivery;
            } else {
                $where["iof.delivery_type_id"] = $this->default_delivery;
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
            if ($this->input->get("delivery_country_id") == "nonAPAC") {
                $where["delivery_country_id not in ('AU', 'NZ', 'SG', 'MY', 'HK', 'PH')"] = null;
            } else
                $where["delivery_country_id"] = $this->input->get("delivery_country_id");
        }

        if ($this->input->get("note")) {
            $where["note LIKE "] = "%" . $this->input->get("note") . "%";
        }


        $where["iof.status >"] = "2";
        $where["iof.status <"] = "5";

        $where["iof.hold_status"] = "0";
        $where["iof.refund_status"] = "0";

        //2214 add payment mode
        if ($this->input->get("payment_gateway_id")) {
            $where["payment_gateway_id"] = $this->input->get("payment_gateway_id");
        }

        if ($this->input->get("website_status")) {
            $where["website_status"] = $this->input->get("website_status");
        }

        $sort = $this->input->get("sort");
        $order = $this->input->get("order");

        $limit = '1000';

        $option["limit"] = $limit;
        $option["offset"] = $offset;

        # $sort is responsible for the ascending/descending arrow on frontend
        # for $sortstr, if there is no sort selected,
        # so_no must be the first ORDER BY so that orders with same so_no are grouped together
        if (empty($sort)) {
            $sort = "expect_delivery_date";

            # sequence of ORDER BY so_no is impt, else may cause display problem
            $sortstr = "so_no, $sort $order";
        } else {
            $sortstr = "$sort $order";
        }

        if (empty($order))
            $order = "asc";


        $option["orderby"] = $sortstr;

        $data["default_delivery"] = $this->default_delivery;
        $data["whlist"] = $this->sc['Warehouse']->getDao('Warehouse')->getList([], ["limit" => -1, "result_type" => "array"]);
        $data["cclist"] = $this->sc['So']->getDao('So')->get_cc_list(array("status >" => 2, "status <" => 5, "hold_status" => 0, "refund_status" => 0), array("orderby" => "delivery_country_id", "limit" => -1));
        $option["warehouse_id"] = $data["warehouse"] = $warehouse ? $warehouse : $data["whlist"][0]["id"];
        $option["notes"] = 1;
        $option["hide_client"] = 1;
        $option["hide_payment"] = 0;
        $option["show_git"] = 1;
        $option["hide_shipped_item"] = 1;

        $temp_objlist = $this->sc['So']->getDao('So')->getIntegratedFulfillmentListWithName($where, $option);
        $data["objlist"] = $this->sc['IntegratedOrderFulfillment']->renovateData($temp_objlist);
        $data["total_order"] = $this->sc['So']->getDao('So')->getIntegratedFulfillmentListWithName($where, array("num_rows" => 1, "hide_client" => 1, "hide_payment" => 0, "hide_shipped_item" => 1));
        $data["total_item"] = $pconfig['total_rows'] = $this->sc['So']->getDao('So')->getIntegratedFulfillmentListWithName($where, array("total_items" => 1, "hide_shipped_item" => 1));

        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->getLangId() . ".php");
        $data["lang"] = $lang;

        $config['base_url'] = base_url('order/integrated_order_fulfillment');
        $config['total_rows'] = $data["total_order"];
        $config['per_page'] = $limit;

        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();

        $data["notice"] = notice($lang);
        $data["valid_website_status"] = $this->sc['soModel']->getValidWebsiteStatusList();
        $data["courier_list"] = $this->courier_list;
        $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
        $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
        $data["searchdisplay"] = "";
        $this->load->view('order/integrated_order_fulfillment/integrated_order_fulfillment_index_v', $data);
    }

    public function getWmsAllocationPlanOrder($check_so_no = array())
    {
        if ($check_so_no) {
            $this->allow_Allocation(null, $check_so_no);
        } else {
            $this->cron_job__wms_allocation_plan_order($check_so_no);
        }

        redirect(base_url() . "order/integrated_order_fulfillment/?" . $_SERVER['QUERY_STRING']);
    }

    public function allow_Allocation($wms_so_no = array(), $check_so_no = array())
    {
        if ($check_so_no) {
            $solist = $check_so_no;
        } else {
            $solist = $wms_so_no;
        }

        if ($solist) {
            $bodytext = '';
            $mail_send = FALSE;
            $rsresult = "";
            $shownotice = 0;
            $u_where["modify_on <"] = date("Y-m-d H:i:s");
            $soid_vo = $this->sc['So']->getDao('SoItemDetail')->get();
            foreach ($solist as $key => $so_no) {
                if ($so_obj = $this->sc['So']->getDao('So')->get(array("so_no" => $so_no))) {
                    if (($so_obj->get_status() > 2 && $so_obj->get_status() < 5) && $so_obj->get_refund_status() == 0 && $so_obj->get_hold_status() == 0) {
                        if ($ffi_objlist = $this->sc['So']->getDao('SoItemDetail')->get_fulfil(array("so.so_no" => $so_no))) {
                            $update_so = array();
                            foreach ($ffi_objlist as $obj) {
                                $this->sc['So']->getDao('So')->trans_start();

                                $new_obj = clone $soid_vo;
                                set_value($new_obj, $obj);
                                $so_no = $obj->get_so_no();
                                $line_no = $obj->get_line_no();
                                $item_sku = $obj->get_item_sku();
                                $update_so[$line_no][$item_sku] = $new_obj;
                            }

                            if ($update_so) {
                                $soal_vo = $this->sc['So']->getDao('SoAllocate')->get();
                                $error = "";
                                $success = 1;
                                $this->sc['So']->getDao('So')->trans_start();
                                foreach ($update_so as $line_no => $soid_list) {
                                    foreach ($soid_list as $item_sku => $soid_obj) {
                                        if ($this->sc['So']->getDao('SoItemDetail')->update($soid_obj, $u_where)) {
                                            $al_where = array();
                                            $al_where["so_no"] = $so_no;
                                            $al_where["line_no"] = $line_no;
                                            $al_where["item_sku"] = $item_sku;
                                            $al_where["warehouse_id"] = "HK"; //warehouse_id;
                                            $al_where["status"] = "1";
                                            $action = "update";
                                            if (!($soal_obj = $this->sc['So']->getDao('SoAllocate')->get($al_where))) {
                                                unset($soal_obj);
                                                $soal_obj = clone $soal_vo;
                                                $action = "insert";
                                                set_value($soal_obj, $soid_obj);
                                                $soal_obj->set_warehouse_id("HK"); //warehouse_id;
                                                $soal_obj->set_status("1");

                                                if ($this->sc['So']->getDao('SoAllocate')->$action($soal_obj) == FALSE) {
                                                    $success = 0;
                                                    $error = __LINE__ . " " . $this->db->_error_message();
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                }
                            }

                            if ($success) {
                                if ($so_obj = $this->sc['So']->getDao('So')->get(array("so_no" => $so_no))) {
                                    $so_obj->set_status("5");
                                    $run_update = 1;

                                    if ($run_update) {
                                        if (!$this->sc['So']->getDao('So')->update($so_obj, $u_where)) {
                                            $success = 0;
                                            $error = __LINE__ . " " . $this->db->_error_message();
                                        }
                                    }
                                } else {
                                    $success = 0;
                                    $error = __LINE__ . " " . $this->db->_error_message();
                                }
                            }
                            if (!$success) {
                                $this->sc['So']->getDao('So')->trans_rollback();
                                $shownotice = 1;
                            }
                            $rsresult .= "{$so_no} -> {$success} " . ($success ? "" : "(error:{$error})") . "\\n";
                            $this->sc['So']->getDao('So')->trans_complete();
                        }
                    } else {
                        $mail_send = TRUE;
                        $bodytext .= "New order so_no {$so_no} > 'to ship' is abnormal, Order status: {$so_obj->get_status()}, refund_status: {$so_obj->get_refund_status()}, hold_status:{$so_obj->get_hold_status()}<br />";
                    }
                }
            }

            if ($shownotice) {
                $_SESSION["NOTICE"] = $rsresult;
            }

            if ($mail_send) {
                $header = "From: admin@eservicesgroup.com\r\n";
                $subject = "[VB] Alert, WMS Allocation Plan New order so_no > 'to ship' is abnormal";
                mail("alice.wu@eservicesgroup.com", "{$subject}", "{$bodytext}", "{$header}");
            }

            return TRUE;
        }

        return FALSE;
    }

    public function cron_job__wms_allocation_plan_order($check_so_no = array())
    {
        $wms_so_no = $this->sc['soModel']->getWmsAllocationPlanOrder();
        if ($wms_so_no[0]) {
            $this->allow_Allocation(array_unique($wms_so_no[0]), $check_so_no);
        }
    }

    public function to_ship()
    {
        set_time_limit(60);
        $sub_app_id = $this->getAppId() . "01";

        $_SESSION["LISTPAGE"] = base_url() . "order/integrated_order_fulfillment/to_ship/?" . $_SERVER['QUERY_STRING'];

        if ($this->input->post("posted") && $_POST["check"] && $_POST["dispatch_type"] != 'c') {
            $rsresult = "";
            $shownotice = 0;

            $u_where["modify_on <="] = date("Y-m-d H:i:s");
            $r_where["soal.status"] = 1;
            if ($_POST["dispatch_type"] != 'r') {
                $r_where["hold_status"] = "0";
                $r_where["refund_status"] = "0";
            }
            $r_option["limit"] = -1;
            $r_option["solist"] = $_POST["check"];
            $rlist = $this->sc['So']->getDao('SoAllocate')->getInSoList($r_where, $r_option);

            $success_so = $update_so = array();
            $this->sc['So']->getDao('So')->trans_start();
            foreach ($rlist as $obj) {
                $so_no = $obj->get_so_no();
                $line_no = $obj->get_line_no();
                $item_sku = $obj->get_item_sku();
                $al_id = $obj->get_id();
                $update_so[$so_no][$al_id] = $obj;
            }
            if ($update_so) {
                foreach ($this->input->post('check') as $so_no) {
                    if (!isset($update_so[$so_no])) continue;

                    $soal_list = $update_so[$so_no];

                    $error = "";
                    $success = 1;
                    $this->sc['So']->getDao('So')->trans_start();

                    if ($_POST["dispatch_type"] == 'r') {
                        foreach ($soal_list as $al_id => $soal_obj) {
                            $soid_where["so_no"] = $so_no;
                            $soid_where["line_no"] = $soal_obj->get_line_no();
                            $soid_where["item_sku"] = $soal_obj->get_item_sku();
                            $cur_u_where = isset($soid_u_where[$soid_where["so_no"]][$soid_where["line_no"]][$soid_where["item_sku"]]) ? array("modify_on <=" => $soid_u_where[$soid_where["so_no"]][$soid_where["line_no"]][$soid_where["item_sku"]]) : $u_where;
                            if ($soid_obj = $this->sc['So']->getDao('SoItemDetail')->get($soid_where)) {
                                if (!($rs1 = $this->sc['So']->getDao('SoAllocate')->delete($soal_obj))) {
                                    $success = 0;
                                    $error = __LINE__ . "[" . ($rs1 ? 1 : 0) . "]" . $this->db->_error_message();
                                    break;
                                } else {
                                    $soid_u_where[$soid_where["so_no"]][$soid_where["line_no"]][$soid_where["item_sku"]] = date("Y-m-d H:i:s");
                                }
                            } else {
                                $success = 0;
                                $error = __LINE__ . "[" . ($soid_obj ? 1 : 0) . "]" . $this->db->_error_message();
                                break;
                            }
                        }
                        if ($success) {
                            $so_obj = $this->sc['So']->getDao('So')->get(array("so_no" => $so_no));
                            if ($this->sc['So']->getDao('SoAllocate')->get_num_rows(array("so_no" => $so_no))) {
                                $so_obj->set_status("4");
                                $so_obj->set_finance_dispatch_date(null);
                            } else {
                                $so_obj->set_status("3");
                                $so_obj->set_finance_dispatch_date(null);
                            }
                            if (!$this->sc['So']->getDao('So')->update($so_obj)) {
                                $success = 0;
                                $error = __LINE__ . " " . $this->db->_error_message();
                            }
                        }
                    } else {
                        $so_obj = $this->sc['So']->getDao('So')->get(array("so_no" => $so_no));
                        $sh_no = $this->so_service->get_next_sh_no($so_no);

                        $courier_id = $this->_check_courier($this->input->post("courier_id"), $so_obj, $_POST["courier"][$so_no]);
                        $sosh_vo = $this->sc['So']->getDao('SoShipment')->get();
                        $sosh_vo->set_sh_no($sh_no);
                        $sosh_vo->set_courier_id($courier_id);
                        $sosh_vo->set_status(1);
                        if ($rs1 = $this->sc['So']->getDao('SoShipment')->insert($sosh_vo)) {
                            foreach ($soal_list as $al_id => $soal_obj) {
                                $soal_obj->set_sh_no($sh_no);
                                $soal_obj->set_status(2);
                                if (!($rs2 = $this->sc['So']->getDao('SoAllocate')->update($soal_obj))) {
                                    $success = 0;
                                    $error = __LINE__ . "[" . ($rs1 ? 1 : 0) . ($rs2 ? 1 : 0) . "]" . $this->db->_error_message();
                                    break;
                                }
                            }
                        } else {
                            $success = 0;
                            $error = __LINE__ . " " . $this->db->_error_message();
                        }
                    }

                    if (!$success) {
                        $this->sc['So']->getDao('So')->trans_rollback();
                        $shownotice = 1;
                    } else {
                        $success_so[] = $so_no;
                    }
                    $rsresult .= "{$so_no} -> {$success} " . ($success ? "" : "(error:{$error})") . "\\n";

                    $this->sc['So']->getDao('So')->trans_complete();
                }
            }
            if ($shownotice) {
                $_SESSION["NOTICE"] = $rsresult;
            }
            if ($_POST["dispatch_type"] == 'r') {
                redirect(current_url() . "?" . $_SERVER['QUERY_STRING']);
            } else {
                $this->generateCourierFile($success_so);
            }
        }

        $where = array();
        $option = array();

        if ($this->input->get("so_no")) {
            $where["iof.so_no"] = $this->input->get("so_no");
        }

        if ($this->input->get("platform_order_id")) {
            $where["platform_order_id"] = $this->input->get("platform_order_id");
        }

        if ($this->input->get("platform_id")) {
            $where["platform_id"] = $this->input->get("platform_id");
        }

        if ($this->input->get("rec_courier")) {
            $where["iof.rec_courier"] = $this->input->get("rec_courier");
        }

        if ($this->input->get("order_create_date")) {
            fetch_operator($where, "order_create_date", $this->input->get("order_create_date"));
        }

        if ($this->input->get("amount")) {
            fetch_operator($where, "iof.amount", $this->input->get("amount"));
        }

        if ($this->input->get("multiple") === '1') {
            $where["order_total_sku >"] = 1;
        } elseif ($this->input->get("multiple") === '0') {
            $where["order_total_sku <="] = 1;
        }

        if ($this->input->get("express")) {
            if ($this->input->get("express") == "Y") {
                $where["iof.delivery_type_id <>"] = $this->default_delivery;
            } else {
                $where["iof.delivery_type_id"] = $this->default_delivery;
            }
        }

        if ($this->input->get("item_sku")) {
            $where["item_sku"] = $this->input->get("item_sku");
        }

        if ($this->input->get("prod_name")) {
            $where["prod_name LIKE "] = $this->input->get("prod_name");
        }

        if ($this->input->get("outstanding_qty")) {
            fetch_operator($where, "outstanding_qty", $this->input->get("outstanding_qty"));
        }

        if ($this->input->get("delivery_name")) {
            $where["delivery_name LIKE "] = "%" . $this->input->get("delivery_name") . "%";
        }

        if ($this->input->get("delivery_postcode")) {
            $where["delivery_postcode"] = $this->input->get("delivery_postcode");
        }

        if ($this->input->get("delivery_country_id")) {
            $where["delivery_country_id"] = $this->input->get("delivery_country_id");
        }

        if ($this->input->get("warehouse_id")) {
            $where["soal.warehouse_id"] = $this->input->get("warehouse_id");
        }

        if ($this->input->get("delivery")) {
            $where["iof.delivery_type_id"] = $this->input->get("delivery");
        }

        if ($this->input->get("note")) {
            $where["note LIKE "] = "%" . $this->input->get("note") . "%";
        }

        if ($this->input->get("payment_gateway_id")) {
            $where["payment_gateway_id"] = $this->input->get("payment_gateway_id");
        }


        $where["iof.status >"] = "3";
        $where["iof.status <"] = "6";


        $sort = $this->input->get("sort");
        $order = $this->input->get("order");

        $limit = '1000';

        $pconfig['base_url'] = $_SESSION["LISTPAGE"];
        $option["limit"] = $pconfig['per_page'] = $limit;
        if ($option["limit"]) {
            $option["offset"] = $this->input->get("per_page");
        }

        # $sort is responsible for the ascending/descending arrow on frontend
        # for $sortstr, if there is no sort selected,
        # so_no must be the first ORDER BY so that orders with same so_no are grouped together
        if (empty($sort)) {
            $sort = "expect_delivery_date";

            # sequence of ORDER BY so_no is impt, else may cause display problem
            $sortstr = "so_no, $sort $order";
        } else {
            $sortstr = "$sort $order";
        }

        if (empty($order))
            $order = "asc";

        $option["orderby"] = $sortstr;

        $option["list_type"] = "toship";

        $data["default_delivery"] = $this->default_delivery;
        $data["whlist"] = $this->sc['Warehouse']->getDao('Warehouse')->getList(array(), array("limit" => -1, "result_type" => "array"));
        $data["courier_list"] = $this->courier_list;
        $option["notes"] = 1;
        $option["hide_client"] = 1;
        $option["hide_payment"] = 0;

        $temp_objlist = $this->sc['So']->getDao('SoAllocate')->get_integrated_allocate_list($where, $option);

        $data["objlist"] = $this->sc['IntegratedOrderFulfillment']->renovateData($temp_objlist);


        $data["total_order"] = $this->sc['So']->getDao('SoAllocate')->get_integrated_allocate_list($where, array("num_rows" => 1, "list_type" => "toship", "hide_client" => 1));
        $data["total_item"] = $pconfig['total_rows'] = $this->sc['So']->getDao('SoAllocate')->get_integrated_allocate_list($where, array("total_items" => 1, "list_type" => "toship"));

        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->getLangId() . ".php");
        $data["lang"] = $lang;

        $this->pagination_service->set_show_count_tag(FALSE);
        $this->pagination_service->initialize($pconfig);

        $data["notice"] = notice($lang);

        $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
        $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
        $data["searchdisplay"] = "";

        $this->load_view('order/integrated_order_fulfillment/integrated_order_fulfillment_to_ship_v', $data);
        if ($_POST["dispatch_type"] == 'c') {
            $this->generate_allocate_file();
        } else {
            redirect(current_url()."?".$_SERVER['QUERY_STRING']);
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

    public function generateCourierFile($checked)
    {
        if ($checked) {
            $courier = $this->input->post("courier_id");
            $mawb = $this->input->post("mawb");
            $so_no_str = json_encode($checked);

            $courier_feed_vo = $this->courier_feed_dao->get();
            $courier_feed_vo->set_so_no_str($so_no_str);
            $courier_feed_vo->set_courier_id($courier);
            $courier_feed_vo->set_mawb($mawb);
            $courier_feed_vo->set_exec(0);

            $this->courier_feed_dao->insert($courier_feed_vo);
            $batch_id = $courier_feed_vo->get_batch_id();
            $this->batch_service->schedule_php_process(1, "order/integrated_order_fulfillment/cron_generate_courier_file/" . $batch_id);
        }
    }

    public function generate_allocate_file()
    {
        $ret = $this->sc['soModel']->generateAllocateFile();
        $_SESSION['allocate_file'] = $ret;
        redirect(current_url() . "?" . $_SERVER['QUERY_STRING']);
    }

    public function dispatch()
    {
        set_time_limit(60);
        $sub_app_id = $this->getAppId() . "02";

        $_SESSION["LISTPAGE"] = base_url() . "order/integrated_order_fulfillment/dispatch/?" . $_SERVER['QUERY_STRING'];
        if ($this->input->post("posted") && $_POST["check"]) {
            $rsresult = "";
            $shownotice = 0;

            if ($_POST["dispatch_type"] == 'c') {
                $success_so = array();
                $u_where["modify_on <="] = $this->input->post("db_time");
                $u_where["status"] = 1;
                foreach ($_POST["check"] as $sh_no => $so_no) {
                    $error = "";
                    $success = 1;
                    $u_where["sh_no"] = $sh_no;
                    if ($sh_obj = $this->sc['So']->getDao('SoShipment')->get($u_where)) {
                        $so_obj = $this->sc['So']->getDao('So')->get(array("so_no" => $so_no));
                        $courier_id = $this->_check_courier($this->input->post("courier_id"), $so_obj, $_POST["courier"][$sh_no]);
                        $sh_obj->set_courier_id($courier_id);
                        $sh_obj->set_tracking_no("");
                        if ($this->sc['So']->getDao('SoShipment')->update($sh_obj) === FALSE) {
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
                $rlist = $this->sc['So']->getDao('SoAllocate')->get_in_so_list($r_where, $r_option);
                $update_sh = array();
                $this->sc['So']->getDao('So')->trans_start();
                foreach ($rlist as $obj) {
                    $sh_no = $obj->get_sh_no();
                    $line_no = $obj->get_line_no();
                    $item_sku = $obj->get_item_sku();
                    $al_id = $obj->get_id();
                    $update_sh[$sh_no][$al_id] = $obj;
                }

                if ($update_sh) {
                    foreach ($update_sh as $sh_no => $soal_list) {
                        $error = "";
                        $success = 1;
                        $this->sc['So']->getDao('So')->trans_start();
                        $sosh_obj = $this->sc['So']->getDao('SoShipment')->get(array("sh_no" => $sh_no));

                        if ($_POST["dispatch_type"] == 'r') {
                            foreach ($soal_list as $al_id => $soal_obj) {
                                $so_no = $soal_obj->get_so_no();
                                $soid_where["so_no"] = $so_no;
                                $soid_where["line_no"] = $soal_obj->get_line_no();
                                $soid_where["item_sku"] = $soal_obj->get_item_sku();
                                $cur_u_where = isset($soid_u_where[$soid_where["so_no"]][$soid_where["line_no"]][$soid_where["item_sku"]]) ? array("modify_on <=" => $soid_u_where[$soid_where["so_no"]][$soid_where["line_no"]][$soid_where["item_sku"]]) : $u_where;
                                if ($soid_obj = $this->sc['So']->getDao('SoItemDetail')->get($soid_where)) {
                                    if (!($rs1 = $this->sc['So']->getDao('SoAllocate')->delete($soal_obj))) {
                                        $success = 0;
                                        $error = __LINE__ . "[" . ($rs1 ? 1 : 0) . "]" . $this->db->_error_message();
                                        break;
                                    } else {
                                        $soid_u_where[$soid_where["so_no"]][$soid_where["line_no"]][$soid_where["item_sku"]] = date("Y-m-d H:i:s");
                                    }
                                } else {
                                    $success = 0;
                                    $error = __LINE__ . "[" . ($soid_obj ? 1 : 0) . "]" . $this->db->_error_message();
                                    break;
                                }
                            }
                            if ($success) {
                                $so_obj = $this->sc['So']->getDao('So')->get(array("so_no" => $so_no));
                                if ($this->sc['So']->getDao('SoAllocate')->get_num_rows(array("so_no" => $so_no))) {
                                    $so_obj->set_status("4");
                                    $so_obj->set_finance_dispatch_date(null);
                                } else {
                                    $so_obj->set_status("3");
                                    $so_obj->set_finance_dispatch_date(null);
                                }
                                if (!(($rs1 = $this->sc['So']->getDao('So')->update($so_obj)) && ($rs2 = $this->sc['So']->getDao('SoShipment')->delete($sosh_obj)))) {
                                    $success = 0;
                                    $error = __LINE__ . "[" . ($rs1 ? 1 : 0) . ($rs2 ? 1 : 0) . "]" . $this->db->_error_message();
                                }
                            }
                        } else {
                            $sosh_obj->set_status("2");
                            $sosh_obj->set_tracking_no($_POST["tracking"][$sh_no]);
                            if ($this->sc['So']->getDao('SoShipment')->update($sosh_obj)) {
                                foreach ($soal_list as $al_id => $soal_obj) {
                                    $soal_obj->set_status("3");
                                    if (!$this->sc['So']->getDao('SoAllocate')->update($soal_obj)) {
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
                                $so_obj = $this->sc['So']->getDao('So')->get(array("so_no" => $soal_obj->get_so_no()));
                                if ($this->sc['So']->getDao('SoAllocate')->get_num_rows(array("so_no" => $so_no, "status" => 1)) == 0) {
                                    if (!$this->so_service->update_complete_order($so_obj, 0)) {
                                        $success = 0;
                                        $error = __LINE__ . " " . $this->db->_error_message();
                                    }

                                    // todo
                                    if ($so_obj->get_biz_type() == 'SPECIAL') {
                                        $special_orders[] = $so_obj->get_so_no();
                                    }
                                }

                                if (substr($so_obj->get_platform_id(), 0, 2) != "AM" && substr($so_obj->get_platform_id(), 0, 2) != "TS" && $so_obj->get_biz_type() != "SPECIAL") {
                                    $this->so_service->fire_dispatch($so_obj, $sh_no);
                                }
                            }
                        }
                        if (!$success) {
                            $this->sc['So']->getDao('So')->trans_rollback();
                            $shownotice = 1;
                        }
                        $rsresult .= "{$sh_no} -> {$success} " . ($success ? "" : "(error:{$error})") . "\\n";
                        $this->sc['So']->getDao('So')->trans_complete();
                    }
                    if ($special_orders) {
                        foreach ($special_orders as $key => $so_no) {
                            $so_w_reason = $this->sc['So']->getDao('So')->get_so_w_reason(array('so.so_no' => $so_no), array('limit' => 1));

                            if ($so_w_reason->get_reason_id() == '34') {
                                $aps_direct_order[] = $so_w_reason->get_so_no();
                            }

                        }

                        $aps_direct_orders = implode(',', $aps_direct_order);
                        $where = "where so.so_no in (" . $aps_direct_orders . ")";
                        $content = $this->sc['So']->getDao('So')->get_aps_direct_order_csv($where);

                        include_once(BASEPATH . "plugins/phpmailer/phpmailer_pi.php");
                        $phpmail = new phpmailer();

                        $phpmail->IsSMTP();
                        $phpmail->From = "VB APS ORDER ALERT <do_not_reply@valuebasket.com>";
                        $phpmail->AddAddress("bd.platformteam@eservicesgroup.net");
                        //$phpmail->AddAddress("nicolove.ni@eservicesgroup.com");

                        $phpmail->Subject = " DIRECT APS ORDERS";
                        $phpmail->IsHTML(false);
                        $phpmail->Body = "Attached: DIRECT APS ORDERS.";
                        $phpmail->AddStringAttachment($content, "direct_aps_info.csv");
                        $result = $phpmail->Send();

                    }
                }
            }

            if ($shownotice) {
                $_SESSION["NOTICE"] = $rsresult;
            }

            if ($_POST["dispatch_type"] == 'c') {
                $this->generateCourierFile($success_so);
            } else {
                redirect(current_url() . "?" . $_SERVER['QUERY_STRING']);
            }
        }

        $where = array();
        $option = array();

        if ($this->input->get("so_no")) {
            $where["iof.so_no"] = $this->input->get("so_no");
        }

        if ($this->input->get("platform_order_id")) {
            $where["platform_order_id"] = $this->input->get("platform_order_id");
        }

        if ($this->input->get("platform_id")) {
            $where["platform_id"] = $this->input->get("platform_id");
        }

        if ($this->input->get("rec_courier")) {
            $where["iof.rec_courier"] = $this->input->get("rec_courier");
        }

        if ($this->input->get("order_create_date")) {
            fetch_operator($where, "order_create_date", $this->input->get("order_create_date"));
        }

        if ($this->input->get("packing_date")) {
            fetch_operator($where, "sosh.create_on", $this->input->get("packing_date"));
        }

        if ($this->input->get("expect_delivery_date")) {
            fetch_operator($where, "expect_delivery_date", $this->input->get("expect_delivery_date"));
        }

        if ($this->input->get("amount")) {
            fetch_operator($where, "iof.amount", $this->input->get("amount"));
        }

        if ($this->input->get("multiple") === '1') {
            $where["order_total_sku >"] = 1;
        } elseif ($this->input->get("multiple") === '0') {
            $where["order_total_sku <="] = 1;
        }

        if ($this->input->get("express")) {
            if ($this->input->get("express") == "Y") {
                $where["iof.delivery_type_id <>"] = $this->default_delivery;
            } else {
                $where["iof.delivery_type_id"] = $this->default_delivery;
            }
        }

        if ($this->input->get("item_sku")) {
            $where["item_sku"] = $this->input->get("item_sku");
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
            $where["delivery_postcode"] = $this->input->get("delivery_postcode");
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


        $where["iof.status >"] = "3";
        $where["iof.status <"] = "6";


        $sort = $this->input->get("sort");
        $order = $this->input->get("order");

        $limit = '1000';

        $pconfig['base_url'] = $_SESSION["LISTPAGE"];
        $option["limit"] = $pconfig['per_page'] = $limit;
        if ($option["limit"]) {
            $option["offset"] = $this->input->get("per_page");
        }

        # $sort is responsible for the ascending/descending arrow on frontend
        # for $sortstr, if there is no sort selected,
        # so_no must be the first ORDER BY so that orders with same so_no are grouped together
        if (empty($sort)) {
            $sort = "expect_delivery_date";

            # sequence of ORDER BY so_no is impt, else may cause display problem
            $sortstr = "so_no, $sort $order";
        } else {
            $sortstr = "$sort $order";
        }

        if (empty($order))
            $order = "asc";

        $option["orderby"] = $sortstr;
        $option["list_type"] = "dispatch";

        $data["default_delivery"] = $this->default_delivery;
        $data["whlist"] = $this->sc['Warehouse']->getDao('Warehouse')->getList(array(), array("limit" => -1, "result_type" => "array"));
        $data["courier_list"] = $this->courier_list;
        $option["notes"] = 1;
        $option["hide_client"] = 1;

        //$data["objlist"] = $this->sc['So']->getDao('SoAllocate')->get_integrated_allocate_list($where, $option);
        $temp_objlist = $this->sc['So']->getDao('SoAllocate')->get_integrated_allocate_list($where, $option);
        $data["objlist"] = $this->sc['IntegratedOrderFulfillment']->renovateData($temp_objlist);
        $data["total_order"] = $this->sc['So']->getDao('SoAllocate')->get_integrated_allocate_list($where, array("num_rows" => 1, "list_type" => "dispatch", "hide_client" => 1));
        $data["total_item"] = $pconfig['total_rows'] = $this->sc['So']->getDao('SoAllocate')->get_integrated_allocate_list($where, array("total_items" => 1, "list_type" => "dispatch"));

        $data["db_time"] = $this->so_service->get_db_time();

        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->getLangId() . ".php");
        $data["lang"] = $lang;

        $this->pagination_service->set_show_count_tag(FALSE);
        $this->pagination_service->initialize($pconfig);

        $data["notice"] = notice($lang);

        $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
        $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
        $data["searchdisplay"] = "";

        $this->load_view('order/integrated_order_fulfillment/integrated_order_fulfillment_dispatch_v', $data);
    }

    public function add_note($so_no = "", $line = "")
    {
        if ($so_no && $line != "") {
            $sub_app_id = $this->getAppId() . "00";
            $_SESSION["LISTPAGE"] = base_url() . "order/integrated_order_fulfillment/add_note/$so_no/$line?" . $_SERVER['QUERY_STRING'];
            if ($this->input->post("posted")) {
                if (isset($_SESSION["obj"])) {
                    $this->sc['So']->getDao('OrderNotes')->include_vo();
                    $data["obj"] = unserialize($_SESSION["obj"]);
                    $data["obj"]->set_note($this->input->post("note"));
                    if (!$this->sc['So']->getDao('OrderNotes')->insert($data["obj"])) {
                        $_SESSION["NOTICE"] = __LINE__ . ":" . $this->db->_error_message();
                        $_SESSION["NOTICE"] = __LINE__ . ":" . $this->db->last_query();
                    } else {
                        unset($_SESSION["obj"]);
                        $data["success"] = 1;
                    }
                }
            }
            include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->getLangId() . ".php");
            $data["lang"] = $lang;

            if (empty($data["obj"])) {
                if (($data["obj"] = $this->sc['So']->getDao('OrderNotes')->get()) === FALSE) {
                    $_SESSION["NOTICE"] = __LINE__ . ":" . $this->db->_error_message();
                } else {
                    $data["obj"]->set_so_no($so_no);
                    $data["obj"]->set_type("O");
                    $_SESSION["obj"] = serialize($data["obj"]);
                }
            }

            $data["objlist"] = $this->sc['So']->getDao('OrderNotes')->get_list(array("so_no" => $so_no), array("orderby" => "create_on desc", "limit" => 5));
            $data["so_no"] = $so_no;
            $data["line"] = $line;
            $data["notice"] = notice($lang);
            $this->load_view('order/integrated_order_fulfillment/integrated_order_fulfillment_add_note_v', $data);
        } else {
            show_404();
        }
    }

    public function error_in_allocate_file()
    {
        $ret = $this->sc['soModel']->errorInAllocateFile();
    }

    public function get_allocate_file($filename = "")
    {
        if ($filename == "") {
            exit;
        } else {
            $data['filename'] = $this->courier_path . $filename;
            $data['output_filename'] = $filename;
            $this->load->view('order/integrated_order_fulfillment/readfile', $data);
        }
    }

    public function cron_generate_courier_file($batch_id)
    {
        if ($courier_feed_obj = $this->courier_feed_dao->get(array("batch_id" => $batch_id))) {
            $so_no_list = json_decode($courier_feed_obj->get_so_no_str());
            $mawb = $courier_feed_obj->get_mawb();
            $courier = $courier_feed_obj->get_courier_id();
            $ret = $this->sc['soModel']->generateCourierFile($so_no_list, $courier, $mawb);
            $courier_feed_obj->set_exec(1);
            $name = $courier_feed_obj->get_create_by();
            $this->courier_feed_dao->update($courier_feed_obj);

            $file_path = $this->sc['ContextConfig']->valueOf('courier_path') . $ret;
            //var_dump($file_path);die();

            $bodytext = "";
            if ($user_obj = $this->sc['User']->getDao('user')->get(array("id" => $name))) {
                $email_addr = $user_obj->get_email();
            } else {
                $email_addr = "nero@eservicesgroup.com";
                $bodytext .= "user email not found <br>";
            }

            foreach ($so_no_list as $o) {
                $bodytext .= $o . "<br/>";
            }

            include_once(BASEPATH . "plugins/phpmailer/phpmailer_pi.php");
            $phpmail = new phpmailer();
            $phpmail->IsSMTP();
            $phpmail->From = "courier_feed@eservicesgroup.com";
            $phpmail->Subject = "courier feed: $ret";
            $phpmail->AddAddress($email_addr);
            $phpmail->IsHTML(true);

            if (file_exists($file_path)) {
                $phpmail->AddAttachment($file_path);
            } else {
                $bodytext = "courier file can not be found<br />" . $bodytext;
            }

            $phpmail->Body = $bodytext;

            $phpmail->Send();
        }
    }

    public function get_courier_file($filename = "")
    {
        if ($filename == "") {
            exit;
        } else {
            $data['filename'] = $this->courier_path . $filename;
            $data['output_filename'] = $filename;
            $this->load->view('order/integrated_order_fulfillment/readfile', $data);
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
            $this->load->view('order/integrated_order_fulfillment/readfile', $data);
        }
    }

    public function invoice()
    {
        $sub_app_id = $this->getAppId() . "03";
        $data["message"] = $this->so_service->get_print_invoice_content($_POST["check"]);
        $this->load->view("order/integrated_order_fulfillment/invoice", $data);

    }

    public function custom_invoice($currency = "")
    {
        $new_shipper_name = trim($_POST["shipper_name"]);
        $sub_app_id = $this->getAppId() . "03";
        $data["message"] = $this->so_service->get_custom_invoice_content($_POST["check"], $new_shipper_name, $currency);
        $this->load->view("order/integrated_order_fulfillment/invoice", $data);
    }

    public function delivery_note()
    {
        $sub_app_id = $this->getAppId() . "03";
        $data["message"] = $this->so_service->get_delivery_note_content($_POST["check"]);
        $this->load->view("order/integrated_order_fulfillment/invoice", $data);
    }

    public function order_packing_slip()
    {
        $sub_app_id = $this->getAppId() . "03";
        $data["message"] = $this->so_service->get_order_packing_slip_content($_POST["check"]);
        $this->load->view("order/integrated_order_fulfillment/invoice", $data);
    }

    public function import_trackingfile()
    {
        set_time_limit(120);
        $wh_list = array("ams", "im", "rmr");
        foreach ($wh_list as $wh) {
            $this->batch_tracking_info_service->cron_tracking_info($wh);
        }
        $data["result"] = $_SESSION["result"];
        $this->load->view("order/integrated_order_fulfillment/tracking_info_result_v", $data);
    }

    public function _get_metapack_path()
    {
        return $this->metapack_path;
    }

    public function _get_courier_path()
    {
        return $this->courier_path;
    }

    public function remove_redundant_record()
    {
        //every time when access this controller, auto delete the record from the integrated_order_fulfillment table
        //where status = 0, 1, 6 and modify_on is 21 days ago.
        $iof_where['`status` in (0, 1, 6)'] = null;
        $iof_where['DATEDIFF(NOW(),modify_on) >'] = 21;
        $obsolete_iof_record_number = $this->sc['IntegratedOrderFulfillment']->get_dao()->q_delete($iof_where);

        //completed refunded order and more than 21 days unmodified
        $iof_where = array();
        $iof_where['refund_status'] = 4;
        $iof_where['DATEDIFF(NOW(),modify_on) >'] = 21;
        $obsolete_iof_record_number = $this->sc['IntegratedOrderFulfillment']->get_dao()->q_delete($iof_where);
    }

    public function get_barcode($so_no = '')
    {
        if ($so_no == '') exit();
        include_once(BASEPATH . "plugins/barcode/barcode.php");

        DEFINE('CANVAS_WIDTH', 220);
        DEFINE('CANVAS_HEIGHT', 70);
        DEFINE('BARCODE_HEIGHT', 30);
        DEFINE('BARCODE_WIDTH', 2);

        $x = CANVAS_WIDTH / 2; // barcode center
        $y = CANVAS_HEIGHT / 2 - 10; // barcode center
        $height = BARCODE_HEIGHT; // barcode height in 1D ; module size in 2D
        $width = BARCODE_WIDTH; // barcode height in 1D ; not use in 2D
        $angle = 0; // rotation in degrees : nb : non horizontable barcode might not be usable because of pixelisation

        $code = $so_no; // barcode
        $type_list = array('int25', 'std25', 'ean8', 'ean13', 'upc', 'code11', 'code39', 'code93', 'code128', 'codabar', 'msi', 'datamatrix');
        //6, 8, 11 work at this task
        $type = $type_list[8];

        $im = imagecreatetruecolor(CANVAS_WIDTH, CANVAS_HEIGHT);
        $black = ImageColorAllocate($im, 0x00, 0x00, 0x00);
        $white = ImageColorAllocate($im, 0xff, 0xff, 0xff);
        $red = ImageColorAllocate($im, 0xff, 0x00, 0x00);
        $blue = ImageColorAllocate($im, 0x00, 0x00, 0xff);
        imagefilledrectangle($im, 0, 0, 300, 300, $white);

        // BARCODE
        $data = Barcode::gd($im, $black, $x, $y, $angle, $type, array('code' => $code), $width, $height);

        imagestring($im, 70, 100, 42, $code, $black);

        imageline($im, 0, 0, CANVAS_WIDTH, 0, $black);
        imageline($im, 0, CANVAS_HEIGHT - 1, CANVAS_WIDTH, CANVAS_HEIGHT - 1, $black);
        imageline($im, 0, 0, 0, CANVAS_HEIGHT, $black);
        imageline($im, CANVAS_WIDTH - 1, 0, CANVAS_WIDTH - 1, CANVAS_HEIGHT, $black);

        header('Content-type: image/png');
        imagepng($im);
        imagedestroy($im);
    }

}
