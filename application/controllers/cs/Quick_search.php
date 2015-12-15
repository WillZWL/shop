<?php

class Quick_search extends MY_Controller
{
    private $appId = 'CS0001';
    private $lang_id = 'en';

    public function __construct()
    {
        parent::__construct();
    }

    public function update_edd($so_no = null)
    {
        if ($so_no) {
            $this->sc['QuickSearch']->saveExpectDeliveryDate($so_no, $_POST["expect_delivery_date"]);
        }

        Redirect(base_url() . "cs/quick_search/view/" . $so_no);
    }

    public function update_cs_order_query($so_no = null)
    {
        if ($so_no) {
            $this->sc['QuickSearch']->saveChasingOrder($so_no, $_POST["chasing_order"]);
        }

        Redirect(base_url() . "cs/quick_search/view/" . $so_no);
    }

    public function index()
    {
        $sub_app_id = $this->getAppId() . "01";

        $_SESSION["LISTPAGE"] = current_url() . "?" . $_SERVER['QUERY_STRING'];

        $where = [];
        $option = [];

        $search = $this->input->get("search");

        if ($search) {
            if (trim($this->input->get("ip_address")) != "") {
                $where["so.create_at"] = addslashes(trim($this->input->get("ip_address")));
            }

            if ($this->input->get("so_no") != "") {
                $where["so.so_no"] = trim($this->input->get("so_no"));
            }

            if ($this->input->get("platform_id") != "") {
                $where["platform_id"] = $this->input->get("platform_id");
            }

            if ($this->input->get("platform_order_id") != "") {
                $where["platform_order_id LIKE "] = "%" . $this->input->get("platform_order_id") . "%";
            }

            if ($this->input->get("ext_client_id") != "") {
                $where["ext_client_id"] = $this->input->get("ext_client_id");
            }

            if ($this->input->get("txn_id") != "") {
                $where["txn_id LIKE"] = '%' . $this->input->get('txn_id') . '%';
            }

            if ($this->input->get("tracking_no") != "") {
                $where["tracking_no LIKE "] = "%" . $this->input->get("tracking_no") . "%";
            }

            if ($this->input->get("surname") != "") {
                $where["surname LIKE"] = '%' . $this->input->get("surname") . '%';
            }

            if ($this->input->get("cemail") != "") {
                $where["email"] = trim($this->input->get("cemail"));
            }

            if ($this->input->get("delivery_name") != "") {
                $where["delivery_name LIKE"] = '%' . $this->input->get("delivery_name") . '%';
            }

            if ($this->input->get("password") != "") {
                $password = $this->encrypt->encode($this->input->get("password"));
                $where["password"] = $password;
            }

            if ($this->input->get("platform_id") != "") {
                $where["platform_id"] = $this->input->get("platform_id");
            }

            if ($this->input->get("order_create_date")) {
                fetch_operator($where, "order_create_date", $this->input->get("order_create_date"));
            }

            if ($this->input->get("amount")) {
                fetch_operator($where, "amount", $this->input->get("amount"));
            }

            if ($this->input->get("expect_delivery_date")) {
                fetch_operator($where, "expect_delivery_date", $this->input->get("expect_delivery_date"));
            }

            if ($this->input->get("order_status") != "") {
                $where["so.status"] = $this->input->get('order_status');
            }

            if ($this->input->get("payment_gateway_id") != "") {
                $where["sops.payment_gateway_id"] = $this->input->get("payment_gateway_id");
            }

            if ($this->input->get("tel") != "") {
                $where["CONCAT_WS(' ',c.tel_1,c.tel_2,c.tel_3) LIKE '%" . $this->input->get("tel") . "%'"] = null;
            }

            if ($this->input->get("dispatch_date")) {
                fetch_operator($where, "dispatch_date", $this->input->get("dispatch_date"));
            }

            if ($this->input->get("refund_status") != "") {
                $where["refund_status"] = $this->input->get('refund_status');
            }

            if ($this->input->get("hold_status") != "") {
                $where["hold_status"] = $this->input->get('hold_status');
            }

            $sort = $this->input->get("sort");
            $order = $this->input->get("order");

            $option['limit'] = ($this->input->get('limit') != '') ? $this->input->get('limit') : '20';
            $option['offset'] = ($this->input->get('per_page') != '') ? $this->input->get('per_page') : '';

            if (empty($sort)) {
                $sort = "so.so_no";
            }

            if (empty($order)) {
                $order = "asc";
            }

            $option["orderby"] = $sort . " " . $order;
            $data["result"] = $this->sc['So']->orderQuickSearch($where, $option);
            $data["total"] = $this->sc['So']->orderQuickSearch($where, ["num_rows" => 1]);


            $config['base_url'] = base_url('cs/quick_search/index');
            $config['total_rows'] = $data["total"];
            $config['page_query_string'] = true;
            $config['reuse_query_string'] = true;
            $config['per_page'] = $option['limit'];
            $this->pagination->initialize($config);
            $data['links'] = $this->pagination->create_links();
            $data["notice"] = notice($lang);
            $data["search"] = $search;
            $data["refresh"] = $this->input->get("refresh");
            $data["added"] = $this->input->get("added");
            $data["updated"] = $this->input->get("updated");

            $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
            $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
            $data["searchdisplay"] = "";
        }
        $data["display_finance_ship_date"] = false;
        if ($data["result"]) {
            $objectResult = (array)$data["result"];
            $data["display_finance_ship_date"] = [];
            for ($i = 0; $i < sizeof($objectResult); $i++) {
                $soResult = $objectResult[$i];
                $data["display_finance_ship_date"][$soResult->getSoNo()] = (check_finance_role() && ($soResult->getStatus() >= 5) && ($soResult->getDispatchDate() != "") && ($soResult->getDispatchDate() != null));
            }
        }

        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->getLangId() . ".php");
        $data["lang"] = $lang;
        $data["app_id"] = $this->getAppId();
        $this->load->view('cs/quick_search/index', $data);
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function print_invoice()
    {
        $data = [];
        if ($this->input->post('print_invoice') == 1) {
            $data["invoice_content"] = $this->sc['So']->getInvoiceContent($this->input->post('order_no'));
        }

        $this->load->view("cs/quick_search/invoice", $data);
    }

    public function view($order_no = "", $viewtype = "")
    {
        if (check_app_feature_access_right($this->getAppId(), 'CS000102_deactivate_client') === TRUE && $this->input->post('action') == 'deactivate_client') {
            $client_ids = $this->sc['So']->getDao('So')->getDistinctClientIdList(['so_no' => $order_no]);

            if (!empty($client_ids[0]) && ($client = $this->sc['Client']->getDao('Client')->get(['id' => $client_ids[0]])) != null) {
                $client_email = $client->getEmail();

                $client->setEmail($client_email . 'deactivate');
                $client->setStatus(0);

                if (($res = $this->sc['Client']->getDao('Client')->update($client)) !== FALSE) {
                    mail('ming@valuebasket.com', '[Panther] Client - ' . $client_email . ' is inactivate', 'Please inactivate ' . $client_email, "From: admin@valuebasket.com\r\n");
                }
                $_SESSION['NOTICE'] = ($res !== FALSE) ? 'client_account_deactivated' : $this->db->display_error();
            } else {
                $_SESSION['NOTICE'] = $this->db->display_error();
            }
            redirect('cs/quick_search/view/' . $order_no . '/' . $viewtype);
        }

        if ($this->input->post('addnote') == 1) {
            $this->sc['QuickSearch']->saveOrderNotes($order_no, $this->input->post('note'));
            Redirect(base_url() . "cs/quick_search/view/" . $order_no . "/" . $viewtype);
        }

        if (($this->input->post('ca') == 1) && (check_app_feature_access_right($this->getAppId(), "CS000102_change_delivery_addr"))) {
            $obj = $this->sc['So']->getDao('So')->get(["so_no" => $order_no]);
            if (!$obj) {
                $_SESSION["NOTICE"] = $this->db->display_error();
            } else {

                $obj->setDeliveryName($this->input->post("dname"));
                $obj->setDeliveryCompany($this->input->post("dcomp"));
                $obj->setDeliveryAddress($this->input->post("daddr1") . "|" . $this->input->post("daddr2") . "|" . $this->input->post("daddr3"));
                $obj->setDeliveryCity($this->input->post("dcity"));
                $obj->setDeliveryState($this->input->post("dstate"));
                $obj->setDeliveryPostcode($this->input->post("dpostcode"));
                $obj->setDeliveryCountryId($this->input->post("dcountry"));

                $ret = $this->sc['So']->getDao('So')->update($obj);
                if ($ret !== FALSE) {
                    $obj = $this->sc['QuickSearch']->getOrderNotes();
                    $obj->setSoNo($order_no);
                    $obj->setType('O');
                    $obj->setNote("Delivery Address Updated");

                    $ret = $this->sc['So']->getDao('OrderNotes')->insert($obj);

                    if ($ret === FALSE) {
                        $_SESSION["NOTICE"] = "add_note_failed";
                    } else {
                        // if have split child, then duplicate the changed delivery address
                        if ($split_child_list = $this->sc['So']->getDao('So')->getList(["split_so_group" => $order_no, "status != 0" => NULL])) {
                            $error_mssage = "";
                            foreach ($split_child_list as $key => $childobj) {
                                $child_so_no = $childobj->getSoNo();
                                $childobj->setDeliveryName($this->input->post("dname"));
                                $childobj->setDeliveryCompany($this->input->post("dcomp"));
                                $childobj->setDeliveryAddress($this->input->post("daddr1") . "|" . $this->input->post("daddr2") . "|" . $this->input->post("daddr3"));
                                $childobj->setDeliveryCity($this->input->post("dcity"));
                                $childobj->setDeliveryState($this->input->post("dstate"));
                                $childobj->setDeliveryPostcode($this->input->post("dpostcode"));
                                $childobj->setDeliveryCountryId($this->input->post("dcountry"));

                                $childret = $this->sc['So']->getDao('So')->update($childobj);
                                if ($childret !== FALSE) {
                                    $child_ordernotes_obj = $this->sc['QuickSearch']->getOrderNotes();
                                    $child_ordernotes_obj->setSoNo($child_so_no);
                                    $child_ordernotes_obj->setType('O');
                                    $child_ordernotes_obj->setNote("Delivery Address Updated");

                                    $childnotesret = $this->sc['So']->getDao('OrderNotes')->insert($child_ordernotes_obj);
                                    if ($childnotesret === FALSE) {
                                        $error_mssage .= "add_note_failed for child $child_so_no \n";
                                    }
                                } else {
                                    $error_mssage .= "Error update del add for child $child_so_no. DB error: " . $this->db->display_error() . "\n";
                                }
                            }
                            if ($error_mssage)
                                $_SESSION["NOTICE"] = $error_mssage;
                        }
                    }
                } else {
                    $_SESSION["NOTICE"] = $this->db->display_error();
                }
            }
            Redirect(base_url() . "cs/quick_search/view/" . $order_no . "/" . $viewtype);
        }

        if ($order_no == "") {
            exit;
        }

        $sub_app_id = $this->getAppId() . "02";

        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->getLangId() . ".php");
        $data["lang"] = $lang;

        $temp_obj = $this->sc['So']->orderQuickSearch(["so.so_no" => $order_no], ["detail" => 1, "limit" => 1, "so_no" => $order_no]);

        foreach ($temp_obj as $obj) {
            $order_obj = $obj;
        }

        $data["order_obj"] = $order_obj;
        $data["so_obj"] = $this->sc['So']->getDao('So')->get(["so_no" => $order_no]);
        $data["status"] = $this->sc['soModel']->getOrderStatus($data["so_obj"]);

        // if this is a split child, redirect to the parent split order
        $split_so_group = $data["so_obj"]->getSplitSoGroup();

        if (!empty($split_so_group) && ($split_so_group != $order_no)) {
            Redirect(base_url() . "cs/quick_search/view/" . $data["so_obj"]->getSplitSoGroup() . "/" . $viewtype);
        }

        //by Nero
        $sosh_obj = $this->sc['So']->getShippingInfo(["soal.so_no" => $order_no]);
        $result = $this->sc['Aftership']->getDynamicShipmentStatus($data["so_obj"]->getSoNo());
        $dynamic_tag = "";
        $retry_api = 0;

        $data["status"]["status"] = $lang[$data["status"]['id'] . "_status"];
        $data["status"]["desc"] = $lang[$data["status"]['id'] . "_desc"];

        $data["so_extend_obj"] = $this->sc['So']->getDao('SoExtend')->getSoExtWithReason(["so_no" => $order_no], ["limit" => 1]);
        $data["socc_obj"] = $this->sc['So']->getDao('SoCreditChk')->get(["so_no" => $order_no]);
        $data["client_obj"] = $this->sc['Client']->getDao('Client')->get(["id" => $order_obj->getClientId()]);
        $data["item_list"] = $this->sc['So']->getDao('SoItemDetail')->getList(["so_no" => $order_no]);
        $data["item_profit"] = $this->sc['So']->getDao('SoItemDetail')->getListWithProdname(["soid.so_no" => $order_no], ["limit" => -1]);
        $data["order_note"] = $this->sc['QuickSearch']->getOrderNotes(["so_no" => $order_no, "type" => "O"]);
        $data["history_obj"] = $this->sc['QuickSearch']->getOrderHistory(["so_no" => $order_no]);
        $scores = $this->sc['QuickSearch']->getPriorityScore($order_no, $data["so_obj"]->getBizType());
        $data["priority_score"] = $scores['score'];
        $data["priority_score_highlight"] = $scores["highlight"];
        $data["sops_last_modify"] = $this->sc['SoPriorityScore']->getDao('SoPriorityScore')->get(["so_no" => $order_no, "status" => 1]);
        $data["sops_history_obj"] = $this->sc['SoPriorityScore']->getPriorityScoreHistoryList(["so_no" => $order_no, "score !=" => $data["priority_score"]], ["orderby" => "id desc", "limit" => 1]);

        #       SBF #2250 insert history of agents changing order score
        $data["sops_history_obj_list"] = $this->sc['SoPriorityScore']->getPriorityScoreHistoryList(["so_no" => $order_no], ["orderby" => "id desc"]);

        if ($data["sops_obj"] = $this->sc['So']->getDao('SoPaymentStatus')->get(["so_no" => $order_no])) {
            $data["rr_obj"] = $this->sc['So']->getDao('RiskRef')->get(["payment_gateway_id" => $data["sops_obj"]->getPaymentGatewayId(), "risk_ref" => $data["sops_obj"]->getRiskRef1()]);
            if ($data["sops_obj"]->getPaymentGatewayId() != "paypal") {
                $data["pmgw_card_obj"] = $this->sc['QuickSearch']->getDao('PmgwCard')->get(["payment_gateway_id" => $data["sops_obj"]->getPaymentGatewayId(), "card_id" => $data["sops_obj"]->getCardId()]);
            }
            $data["result_remark"] = $data["sops_obj"]->getRemark();
        } else {
            $data["result_remark"] = "";
        }

        if ($order_obj->getPaymentGatewayId() == "inpendium_ctpe") {
            $result_arr = parse_ini_file(APPPATH . "/libraries/service/ctpe/resultcodes.ini");
            $extract_inpendium_result = explode('|', $data["sops_obj"]->getRemark());

            if (sizeof($extract_inpendium_result) > 0) {
                $data["inpendium_result"]['result'] = $result_arr[$extract_inpendium_result[0]];
                $data["result_remark"] .= " " . $data["inpendium_result"]['result'];
            }
            if ($data["sops_obj"]->getRiskRef3()) {
                $extract_3d_inpendium_result = explode('|', $data["sops_obj"]->getRiskRef3());
                if (sizeof($extract_3d_inpendium_result) > 0) {
                    $data["inpendium_result"]['3d'] = $result_arr[$extract_3d_inpendium_result[0]];
                }
            } else {
                $data["inpendium_result"]['3d'] = "";
            }
        }

        $item_website_status_when_order = "";
        $current_item_edd = "";
        foreach ($data["item_list"] as $item) {
            $item_website_status_when_order = $data["lang"][$item->getWebsiteStatus()];
            if (($item->getWebsiteStatus() == "P") || ($item->getWebsiteStatus() == "A")) {
                $product_obj = $this->sc['Product']->getDao('Product')->get(["sku" => $item->getProdSku()]);
                $current_item_edd = $product_obj->getExpectedDeliveryDate();
                break;
            }
        }

        $total_profit = 0;
        $total_gst = 0;
        foreach ($data["item_profit"] as $value) {
            // var_dump($value);
            $individual_sku = $value->getItemSku();
            $profit[$individual_sku] = $value->getProfit() * $value->getQty();
            $margin[$individual_sku] = $value->getMargin();
            $gst_total[$individual_sku] = $value->getGstTotal();

            $total_profit += $profit[$individual_sku];
            $total_gst += $gst_total[$individual_sku];
        }

        $data["total_profit"] = $total_profit;
        $data["total_gst"] = $total_gst;
        $data["item_profit"] = $profit;
        $data["item_margin"] = $margin;
        $data["item_gst_total"] = $gst_total;
        $data["website_status"] = $item_website_status_when_order;
        $data["sor_obj"] = $this->sc['So']->getDao('SoRisk')->get(["so_no" => $order_no]);

        $cybs = $this->sc['PaymentGatewayRedirectCybersource'];
        if ($data["sor_obj"]) {
            $data["risk1"] = $cybs->riskIndictorRisk1($data["sor_obj"]->getRiskVar1());
            $data["risk2"] = $cybs->riskIndictorAvsRisk2($data["sor_obj"]->getRiskVar2());
            $data["risk3"] = $cybs->riskIndictorCvnRisk3($data["sor_obj"]->getRiskVar3());
            $data["risk4"] = $cybs->riskIndictorAfsFactorRisk4($data["sor_obj"]->getRiskVar4());
            $data["risk5"] = $cybs->riskIndictorScoreRisk5($data["sor_obj"]->getRiskVar5());
            $data["risk6"] = $cybs->riskIndictorSuspiciousRisk6($data["sor_obj"]->getRiskVar6());
            $data["risk7"] = $cybs->riskIndictorVelocityRisk7($data["sor_obj"]->getRiskVar7());
            $data["risk8"] = $cybs->riskIndictorInternetRisk8($data["sor_obj"]->getRiskVar8());
            $data["risk9"] = [0 => ["style" => "normal", "value" => $data["sor_obj"]->getRiskVar9()]];
        }

        $data["refund_history"] = $this->sc['Refund']->getRefundForOrderDetail($order_no);
        $data["viewtype"] = $viewtype;
        $data["country_list"] = $this->sc['Country']->getDao('Country')->getList([], ["limit" => "-1"]);
        $data["hold_history"] = $this->sc['So']->getHoldHistory($order_no);
        $data["del_opt_list"] = end($this->sc['DeliveryOption']->getListWithKey(["lang_id" => "en"]));
        $data["notice"] = notice($lang);
        $data["app_id"] = $this->getAppId();

        #sbf 2607
        if ($sorf_obj = $this->sc['SoRefundScore']->getRefundScoreVo($order_no)) {
            $data["refund_score"] = $sorf_obj->getScore();
            $data["sorf_history_obj_list"] = $this->sc['SoRefundScore']->getRefundScoreHistoryList(["so_no" => $order_no], ["orderby" => "id desc"]);
            $data["sorf_history_last_obj"] = $this->sc['SoRefundScore']->getRefundScoreHistoryList(["so_no" => $order_no, "score !=" => $data["refund_score"]], ["orderby" => "id desc", "limit" => 1]);
        } else {
            $data["refund_score"] = null;
        }

        $data["so_list"] = $this->sc['QuickSearch']->prepareLinkedOrders($data["so_obj"]);

        if ($data["so_obj"]->getHoldStatus() == 15) {
            if ($split_child_list = $this->sc['So']->getDao('So')->getList(["split_so_group" => $order_no, "status != 0" => NULL])) {
                $data["child"] = $this->prepareSplitChild((array)$split_child_list);
            }
        }

        $data["base_split_url"] = base_url() . "cs/quick_search/process_split/";
        $data["allow_release"] = check_app_feature_access_right($this->getAppId(), "CS000102_release_button");
        $data["allow_split"] = check_app_feature_access_right($this->getAppId(), "CS000102_process_split_order");
        $data["release_history"] = $this->sc['So']->getDao('SoReleaseOrder')->getList(["so_no" => $order_no], ["orderby" => "modify_on desc"]);
// echo "<br/>=========";
//         echo $this->encrypt->decode($data["client_obj"]->getPassword());
        $this->load->view('cs/quick_search/view_detail', $data);
    }

    private function prepareSplitChild($split_child_list = [])
    {
        $child = [];
        if (!empty($split_child_list)) {
            foreach ($split_child_list as $key => $child_so_obj) {
                $child_so_no = $child_so_obj->getSoNo();
                $temp_child_obj = $this->sc['So']->orderQuickSearch(["so.so_no" => $child_so_no], ["detail" => 1, "limit" => 1, "so_no" => $child_so_no]);
                $child[$child_so_no]["order_obj"] = $order_obj = $temp_child_obj[0];

                $courier = "<i>Not Yet Shipped</i>";
                $tracking_no = "<i>Not Yet Shipped</i>";
                $packed_on = "<i>Not Yet Shipped</i>";
                $shipped_on = "<i>Not Yet Shipped</i>";
                if ($order_obj->getDispatchDate()) {
                    $shipped_on = $order_obj->getDispatchDate();
                }
                if ($order_obj->getTrackingNo()) {
                    $tmp = explode("||", $order_obj->getTrackingNo());
                    $tmp2 = explode("::", $tmp[0]);


                    $courier = $tmp2[3];
                    if (count($tmp2) == 6) {
                        $tracking_no = $tmp2[4];
                        $packed_on = $tmp2[5];
                    } else {
                        $tracking_no = "N/A";
                        $packed_on = $tmp2[4];
                    }
                }
                $child[$child_so_no]["courier"] = $courier;
                $child[$child_so_no]["tracking_no"] = $tracking_no;
                $child[$child_so_no]["packed_on"] = $packed_on;
                $child[$child_so_no]["shipped_on"] = $shipped_on;

                $child[$child_so_no]["so_obj"] = $child_so_obj;
                $child[$child_so_no]["status"] = $this->sc['soModel']->getOrderStatus($child_so_obj);

                $sosh_obj = $this->sc['So']->getShippingInfo(["soal.so_no" => $child_so_no]);
                $result = $this->sc['Aftership']->getDynamicShipmentStatus($child[$child_so_no]["so_obj"]->getSoNo());
                $dynamic_tag = "";
                $retry_api = 0;
                if ($result) {
                    $dynamic_tag = $result["status"];
                    $retry_api = $result["retry"];
                }

                if ($lang[$dynamic_tag . "_status"]) {
                    $child[$child_so_no]["status"]["status"] = $lang[$dynamic_tag . "_status"];
                    if ($lang[$dynamic_tag . "_desc"]) {
                        $child[$child_so_no]["status"]["desc"] = $lang[$dynamic_tag . "_desc"];
                    }
                }

                $child[$child_so_no]["so_extend_obj"] = $this->sc['So']->getDao('SoExtend')->getSoExtWithReason(["so_no" => $child_so_no], ["limit" => 1]);
                $child[$child_so_no]["socc_obj"] = $this->sc['So']->getDao('SoCreditChk')->get(["so_no" => $child_so_no]);
                $child[$child_so_no]["item_list"] = $this->sc['So']->getDao('SoItemDetail')->getList(["so_no" => $child_so_no]);
                $child[$child_so_no]["order_note"] = $this->sc['QuickSearch']->getOrderNotes(["so_no" => $child_so_no, "type" => "O"]);
                $child[$child_so_no]["history_obj"] = $this->sc['QuickSearch']->getOrderHistory(["so_no" => $child_so_no]);
                $scores = $this->sc['QuickSearch']->getPriorityScore($child_so_no, $child[$child_so_no]["so_obj"]->getBizType());
                $child[$child_so_no]["priority_score"] = $scores['score'];
                $child[$child_so_no]["priority_score_highlight"] = $scores["highlight"];
                $child[$child_so_no]["sops_last_modify"] = $this->sc['SoPriorityScore']->getDao('SoPriorityScore')->get(["so_no" => $child_so_no, "status" => 1]);
                $child[$child_so_no]["sops_history_obj"] = $this->sc['SoPriorityScore']->getPriorityScoreHistoryList(["so_no" => $child_so_no, "score !=" => $child[$child_so_no]["priority_score"]], ["orderby" => "id desc", "limit" => 1]);

                $child[$child_so_no]["sops_history_obj_list"] = $this->sc['SoPriorityScore']->getPriorityScoreHistoryList(["so_no" => $child_so_no], ["orderby" => "id desc"]);
                $child[$child_so_no]["refund_history"] = $this->sc['Refund']->getRefundForOrderDetail($child_so_no);
                $child[$child_so_no]["hold_history"] = $this->sc['So']->getHoldHistory($child_so_no);

                if ($sorf_obj = $this->sc['SoRefundScore']->getRefundScoreVo($child_so_no)) {
                    $child[$child_so_no]["refund_score"] = $sorf_obj->getScore();
                    $child[$child_so_no]["sorf_history_obj_list"] = $this->sc['SoRefundScore']->getRefundScoreHistoryList(["so_no" => $child_so_no], ["orderby" => "id desc"]);
                    $child[$child_so_no]["sorf_history_last_obj"] = $this->sc['SoRefundScore']->getRefundScoreHistoryList(["so_no" => $child_so_no, "score !=" => $child[$child_so_no]["refund_score"]], ["orderby" => "id desc", "limit" => 1]);
                } else {
                    $child[$child_so_no]["refund_score"] = null;
                }
            }
        }
        return $child;
    }

    public function process_split($order_no = "")
    {
        if (check_app_feature_access_right($this->getAppId(), "CS000102_process_split_order") === FALSE) {
            echo "No access rights";
            die();
        }

        if ($_POST) {
            $data["itemrow"] = $this->processPostData();
        } else {
            if ($order_no == "") {
                echo "Please input so_no.";
                die();
            }

            // generate logic of the furthest split available for this order
            $group_result = $this->sc['SplitOrder']->genSplitOrderLogic($order_no);

            if ($group_result["status"] === FALSE) {
                $_SESSION["NOTICE"] = "genSplitOrderLogic fail. Message: {$group_result["message"]}.";
            } else {
                if ($group = $group_result["group"]) {
                    if (count($group) <= 1)
                        $reach_max_split = TRUE;

                    foreach ($group as $grpkey => $grparr) {
                        $mainprod_sku = $grparr["sku"];
                        $mainprodarr = $this->buildSplitOrderItemInfo($order_no, $mainprod_sku, "MAIN");
                        $mainprodarr["main_ca_uid"] = "$mainprod_sku::$grpkey";

                        # the unique ID that ties the CAs with this mainprod SKU. Pass it into the form so that link isn't broken after user change group
                        $order_group[$grpkey][] = $mainprodarr;

                        if ($calist = $grparr["calist"]) {
                            foreach ($calist as $cakey => $ca_sku) {
                                $ca_group = $this->buildSplitOrderItemInfo($order_no, $ca_sku, "CA");
                                $ca_group["main_ca_uid"] = "$mainprod_sku::$grpkey";
                                $order_group[$grpkey][] = $ca_group;
                            }
                        }
                        if ($ralist = $grparr["ralist"]) {
                            foreach ($ralist as $rakey => $ra_sku) {
                                $ra_group = $this->buildSplitOrderItemInfo($order_no, $ra_sku, "RA");
                                $order_group[$grpkey][] = $ra_group;
                            }
                        }
                    }

                    // create HTML for each item row
                    $data["itemrow"] = $this->createSplitGroupRows($order_no, $order_group, $reach_max_split);
                }
            }
        }

        $data["so_obj"] = $this->sc['So']->getDao('So')->get(["so_no" => $order_no]);

        // the following are not allowed to split; we overwrite itemrow
        $quicksearchurl = base_url() . "cs/quick_search/view/$order_no";
        if ($data["so_obj"]->getHoldStatus() == 15) {
            $data["itemrow"] = "FORBIDDEN: THIS ORDER ALREADY CONTAINS SPLIT CHILD - <a href='$quicksearchurl'>$quicksearchurl</a><br><br>";
        } elseif ($data["so_obj"]->getHoldStatus() != 0 || $data["so_obj"]->getRefundStatus() != 0 || $data["so_obj"]->getStatus() < 3) {
            $data["itemrow"] = "FORBIDDEN: THIS ORDER IS / NOT CREDITCHECKED / ON HOLD / REFUND REQUEST - <a href='$quicksearchurl'>$quicksearchurl</a><br><br>";
        } elseif (substr($data["so_obj"]->getPlatformId(), 0, 3) !== "WEB") {
            $data["itemrow"] = "FORBIDDEN: THIS IS MARKETPLACE ORDER - <a href='$quicksearchurl'>$quicksearchurl</a><br><br>";
        }

        $data["notice"] = notice();
        $this->load->view('cs/quick_search/split_order_view', $data);
    }

    private function processPostData()
    {
        $i = 0;
        $data = "";
        $regroup = $order_group = [];
        if ($postgroup = $_POST) {
            $order_no = $postgroup["so_no"];
            foreach ($postgroup["group"] as $row => $arr) {
                $newgroupkey = $arr["grpno"];
                $regroup[$newgroupkey][$i]["mastersku"] = $arr["mastersku"];
                $regroup[$newgroupkey][$i]["sku"] = $arr["sku"];
                $regroup[$newgroupkey][$i]["main_ca_uid"] = $arr["main_ca_uid"];
                $regroup[$newgroupkey][$i]["type"] = $arr["type"];
                $regroup[$newgroupkey][$i]["name"] = $arr["name"];
                $regroup[$newgroupkey][$i]["suppcountry"] = $arr["suppcountry"];
                $regroup[$newgroupkey][$i]["sourcingstatus"] = $arr["sourcingstatus"];
                $regroup[$newgroupkey][$i]["surplus"] = $arr["surplus"];
                $regroup[$newgroupkey][$i]["unit_amt"] = $arr["unit_amt"];
                $regroup[$newgroupkey][$i]["slow_move_7_days"] = $arr["slow_move_7_days"];

                $i++;
            }

            // if user input everything as one group number, DON'T split.
            if (count($regroup) <= 1) {
                $_SESSION["NOTICE"] = "Items in one group, no split needed.";
                Redirect(base_url() . "cs/quick_search/process_split/" . $order_no);
            }

            # reconstruct the keys because they are not in running numbers after regroup by user
            $rekey = 0;
            foreach ($regroup as $key => $value) {
                $order_group[$rekey] = $value;
                $rekey++;
            }
        }

        if ($postgroup["submittype"] == "preview") {
            $order_group["preview"] = 1;
            $data = $this->createSplitGroupRows($order_no, $order_group);
            return $data;
        } else {
            $result = $this->sc['So']->splitOrderToSo($order_no, $order_group);
            $_SESSION["NOTICE"] = $result["message"];

            if ($result["status"] === FALSE) {
                return $data;
            } else {
                // everything success, redirect to OQS
                Redirect(base_url() . "cs/quick_search/view/" . $order_no);
            }
        }
    }

    private function createSplitGroupRows($order_no, $order_group = [], $reach_max_split = FALSE)
    {
        // construct html display
        $html = "";
        if ($order_group) {
            if ($order_group["preview"]) {
                $html = "<div align='left'><b><font color='red'>Previewing regroup</font></b></div><br>";
                unset($order_group["preview"]);
            }

            $html .= <<<html
            <tr style="background-color:#DCDCDC">
                <td>Master SKU</td>
                <td>SKU</td>
                <td>Name</td>
                <td>Sourcing Info</td>
                <td>Slow move 7 days</td>
                <td>Surplus</td>
                <td>Amt Paid</td>
                <td>Type</td>
                <td>Order Split Group</td>

            <tr>
html;
            $row = 0;
            $colour0 = "#AFEEEE";
            $colour1 = "#FFE4C4";
            foreach ($order_group as $key => $grparr) {
                $bgcolour = ${colour . $key % 2};
                $grpkey = $key + 1; # display purposes; so user won't see 0
                foreach ($grparr as $k => $v) {
                    $marginhtml = "";
                    $main_ca_uid = $v["main_ca_uid"];
                    if ($v["margin"] > 9) {
                        $marginhtml = 'style="color:#DC143C;"';
                    }

                    # Display logic for CA to always be same group number as the main product assigned
                    if (strtoupper($v["type"]) == "CA") {
                        $grpnohtml = <<<html
                            <input type="text" name="group[$row][grpno]" id="group[$row][grpno]" size="4" value="$grpkey" readonly style="background-color:#DCDCDC;">
                            <input type="hidden" id="ca_row[$row][main_ca_uid][$main_ca_uid]" name="ca_row[$main_ca_uid][]" value="$row">
                            <input type="hidden" id="group[$row][main_ca_uid]" name="group[$row][main_ca_uid]" value="$main_ca_uid">
html;
                    } elseif (strtoupper($v["type"]) == "MAIN") {
                        $grpnohtml = <<<html
                            <input type="text" name="group[$row][grpno]" id="group[$row][grpno]" size="4" value="$grpkey" onkeyup="changeCAgroup('$row', '$main_ca_uid');remindPreview()">
                            <input type="hidden" id="group[$row][main_ca_uid]" name="group[$row][main_ca_uid]" value="$main_ca_uid">
html;
                    } else {
                        // Recommended Accessories do not get affected
                        $grpnohtml = <<<html
                            <input type="text" name="group[$row][grpno]" id="group[$row][grpno]" size="4" value="$grpkey" onkeyup="remindPreview()">
html;
                    }

                    $html .= <<<html
                    <tr style="background-color:$bgcolour;">
                        <td>{$v["mastersku"]}</td>
                        <td>{$v["sku"]}</td>
                        <td>{$v["name"]}</td>
                        <td>{$v["suppcountry"]} - {$v["sourcingstatus"]}</td>
                        <td>{$v["slow_move_7_days"]}</td>
                        <td>{$v["surplus"]}</td>
                        <td $marginhtml>{$v["unit_amt"]}</td>
                        <td>{$v["type"]}</td>
                        <td>
                            $grpnohtml
                            <input type="hidden" name="group[$row][mastersku]" id="group[$row][mastersku]" value="{$v["mastersku"]}">
                            <input type="hidden" name="group[$row][sku]" id="group[$row][sku]" value="{$v["sku"]}">
                            <input type="hidden" name="group[$row][type]" id="group[$row][type]" value="{$v["type"]}">
                            <input type="hidden" name="group[$row][name]" id="group[$row][name]" value="{$v["name"]}">
                            <input type="hidden" name="group[$row][suppcountry]" id="group[$row][suppcountry]" value="{$v["suppcountry"]}">
                            <input type="hidden" name="group[$row][sourcingstatus]" id="group[$row][sourcingstatus]" value="{$v["sourcingstatus"]}">
                            <input type="hidden" name="group[$row][slow_move_7_days]" id="group[$row][slow_move_7_days]" value="{$v["slow_move_7_days"]}">
                            <input type="hidden" name="group[$row][surplus]" id="group[$row][surplus]" value="{$v["surplus"]}">
                            <input type="hidden" name="group[$row][unit_amt]" id="group[$row][unit_amt]" value="{$v["unit_amt"]}">
                        </td>
                    <tr>
html;
                    $row++;
                }
            }

            if ($reach_max_split) {
                $html .= "<tr style=\"background-color:#DCDCDC\"><td colspan=\"10\"><br>No further split available by logic.<br></td></tr>";
            }

            $html .= <<<html

                <tr style="background-color:#DCDCDC">
                    <td colspan="10">
                        <br>
                        <button name="submittype" id="submit_process" type="submit" value="split" style="padding:5px 20px;">Process</button>
                        <button name="submittype" id="submit_preview" type="submit" value="preview" style="padding:5px 20px;" onclick="enableProcess()">Preview</button>&nbsp;&nbsp;&nbsp;
                        <br><br>
                    </td>
                </tr>
html;

        }
        return $html;
    }

    private function buildSplitOrderItemInfo($order_no, $sku, $type = "MAIN")
    {
        $iteminfo = [];
        $iteminfo["sku"] = $sku;
        $iteminfo["type"] = $type;

        # get supplier info
        $iteminfo["suppcountry"] = "";
        if ($supplierinfo = $this->sc['So']->getDao('Supplier')->getSupplier($sku))
            $iteminfo["suppcountry"] = $supplierinfo->getOriginCountry();

        # get prodict info
        $iteminfo["name"] = $iteminfo["slow_move_7_days"] = "";
        if ($productinfo = $this->sc['Product']->getDao('Product')->get(["sku" => $sku])) {
            $iteminfo["name"] = $productinfo->getName();
            $sourcingstatus = $productinfo->getSourcingStatus();
            switch ($sourcingstatus) {
                case 'A':
                    $iteminfo["sourcingstatus"] = "Readily Available";
                    break;
                case 'O':
                    $iteminfo["sourcingstatus"] = "Temp of Out Stock";
                    break;
                case 'C':
                    $iteminfo["sourcingstatus"] = "Limited Stock";
                    break;
                case 'L':
                    $iteminfo["sourcingstatus"] = "Last Lot";
                    break;
                case 'D':
                    $iteminfo["sourcingstatus"] = "Discontinued";
                    break;

                default:
                    $iteminfo["sourcingstatus"] = "";
                    break;
            }
            $iteminfo["slow_move_7_days"] = $productinfo->getSlowMove7Days();
            $iteminfo["surplus"] = $productinfo->getSurplusQuantity();
        }

        $iteminfo["mastersku"] = "";
        if ($master_sku_obj = $this->sc['Product']->getDao('SkuMapping')->get(["sku" => $sku, "ext_sys" => "WMS", "status" => 1])) {
            $iteminfo["mastersku"] = $master_sku_obj->getExtSku();
        }

        # get item_detail info
        $iteminfo["profit"] = $iteminfo["profit_raw"] = $iteminfo["margin"] = $iteminfo["profit_raw"] = "";
        if ($so_item_detail = $this->sc['So']->getDao('SoItemDetail')->getListWithProdname(["soid.so_no" => $order_no, "soid.item_sku" => $sku])) {
            foreach ($so_item_detail as $soid_obj) {
                $qty = $soid_obj->getQty(); # total qty for this sku
                $total_amount = $soid_obj->getAmount(); # total paid for this sku
                $iteminfo["unit_amt"] = number_format($total_amount / $qty, 2, '.', '');
                $iteminfo["profit"] = $soid_obj->getProfit();
                $iteminfo["profit_raw"] = $soid_obj->getProfitRaw();
                $iteminfo["margin"] = $soid_obj->getMargin();
                $iteminfo["margin_raw"] = $soid_obj->getMarginRaw();
                break;
            }
        }

        return $iteminfo;
    }

    public function set_fulfill($so_no)
    {
        // this function is mainly for marketplaces to mark so_extend as fulfilled = Y
        // this is for special cases where orders cannot be updated on marketplace, so we mark fulfill
        // and manually update on marketplace side.

        $soext_dao = $this->sc['So']->getDao('SoExtend');
        $where["so_no"] = $so_no;
        if ($so_extend_obj = $this->sc['So']->getDao('SoExtend')->get($where)) {
            if ($so_extend_obj->getFulfilled() == "N") {
                $so_extend_obj->setFulfilled("Y");
                if ($soext_dao->update($so_extend_obj) === FALSE) {
                    $_SESSION["NOTICE"] = "Cannot update so_extend as fulfilled. DB Error: " . $soext_dao->db->display_error();
                }
            }

        } else {
            $_SESSION["NOTICE"] = "Cannot find so_extend obj with so_no <$so_no>";
        }

        Redirect(base_url() . "cs/quick_search/view/" . $so_no);
    }

    public function update_priority_score($so_no)
    {
        $no = $this->sc['SoPriorityScore']->getDao('SoPriorityScore')->getNumRows(["so_no" => $so_no]);
        if ($no == 0) {
            $scores = $this->sc['QuickSearch']->getPriorityScore($so_no, $_POST["biz_type"]);
            $auto_score = $scores['score'];
            $this->sc['SoPriorityScore']->insertSops($so_no, $auto_score);
        }
        $this->sc['SoPriorityScore']->updateSops($so_no, $_POST["priority_score"]);
        Redirect(base_url() . "cs/quick_search/view/" . $so_no);
    }

    public function update_refund_score($so_no)
    {

        $new_score = $_POST['new_refund_score'];
        if ($sorf_vo = $this->sc['SoRefundScore']->getRefundScoreVo($so_no)) {
            $current_score = $sorf_vo->getScore();
            if ($current_score == $new_score) {
                Redirect(base_url() . "cs/quick_search/view/" . $so_no);
            }
            if ($this->sc['SoRefundScore']->updateRefundScore($so_no, $new_score)) {
                $this->sc['SoRefundScore']->insertRefundScoreHistory($so_no, $new_score);
            }

        } else {
            if ($this->sc['SoRefundScore']->insertRefundScore($so_no, $new_score)) {
                $this->sc['SoRefundScore']->insertRefundScoreHistory($so_no, $new_score);
            }
        }
        Redirect(base_url() . "cs/quick_search/view/" . $so_no);
    }

}
