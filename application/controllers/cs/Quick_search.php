<?php

class Quick_search extends MY_Controller
{
    private $appId = 'CS0001';
    private $lang_id = 'en';

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url', 'notice', 'object', 'operator', 'image'));
        $this->load->model('cs/quick_search_model');
        $this->load->library('service/pagination_service');
        $this->load->library('service/aftership_service');
        $this->load->library('service/split_order_service');
        $this->load->library('service/supplier_service');
        $this->load->library('service/product_service');
        $this->load->library('encrypt');
    }

    public function update_edd($so_no = null)
    {
        if ($so_no) {
            $expect_delivery_date = $_POST["expect_delivery_date"];
            $inputValue = array("expect_delivery_date" => $expect_delivery_date);
            $update_result = $this->quick_search_model->update_cs_order_query($so_no, $inputValue);

            if ($expect_delivery_date !== false) {
                $order_note_obj = $this->quick_search_model->get_order_notes();
                $order_note_obj->set_so_no($so_no);
                $order_note_obj->set_type("O");
                $order_note_obj->set_note("FM New EDD - " . $expect_delivery_date);
                $this->quick_search_model->add_notes($order_note_obj);

                // duplicate to other split child orders
                if ($so_obj = $this->so_service->get_dao()->get(array("so_no" => $so_no))) {
                    $split_so_group = $so_obj->get_split_so_group();

                    if ($split_so_group && $so_no != $split_so_group) {
                        if ($split_child_list = $this->so_service->get_dao()->get_list(array("split_so_group" => $split_so_group, "so_no != $so_no" => null, "status != 0" => NULL))) {
                            foreach ($split_child_list as $key => $childobj) {
                                $update_result = $this->quick_search_model->update_cs_order_query($childobj->get_so_no(), $inputValue);

                                $order_note_obj = $this->quick_search_model->get_order_notes();
                                $order_note_obj->set_so_no($childobj->get_so_no());
                                $order_note_obj->set_type("O");
                                $order_note_obj->set_note("FM New EDD - " . $expect_delivery_date);
                                $this->quick_search_model->add_notes($order_note_obj);
                            }
                        }
                    }
                }
            }
        }

        Redirect(base_url() . "cs/quick_search/view/" . $so_no);
    }

    public function update_cs_order_query($so_no = null)
    {
        if ($so_no) {
            $chasing_order = $_POST["chasing_order"];
            $inputValue = array("chasing_order" => $chasing_order);
            $this->quick_search_model->update_cs_order_query($so_no, $inputValue);

            // duplicate to other split child orders
            if ($so_obj = $this->so_service->get_dao()->get(array("so_no" => $so_no))) {
                $split_so_group = $so_obj->get_split_so_group();

                if ($split_so_group && $so_no != $split_so_group) {
                    if ($split_child_list = $this->so_service->get_dao()->get_list(array("split_so_group" => $split_so_group, "so_no != $so_no" => null, "status != 0" => NULL))) {
                        $error_mssage = "";
                        foreach ($split_child_list as $key => $childobj) {
                            $this->quick_search_model->update_cs_order_query($childobj->get_so_no(), $inputValue);
                        }
                    }
                }
            }
        }

        Redirect(base_url() . "cs/quick_search/view/" . $so_no);
    }

    public function index()
    {
        $sub_app_id = $this->getAppId() . "01";

        $_SESSION["LISTPAGE"] = current_url() . "?" . $_SERVER['QUERY_STRING'];

        $where = array();
        $option = array();

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
                $where["payment_gateway_id"] = $this->input->get("payment_gateway_id");
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

            $limit = $this->pagination_service->get_num_records_per_page();

            $pconfig['base_url'] = $_SESSION["LISTPAGE"];
            $option["limit"] = $pconfig['per_page'] = $limit;
            if ($option["limit"]) {
                $option["offset"] = $this->input->get("per_page");
            }

            if (empty($sort))
                $sort = "so.so_no";

            if (empty($order))
                $order = "asc";

            $option["orderby"] = $sort . " " . $order;
            $data["result"] = $this->quick_search_model->search_order($where, $option);
            $data["total"] = $this->quick_search_model->search_order($where, array("num_rows" => 1));


            $pconfig['total_rows'] = $data['total'];
            $this->pagination_service->initialize($pconfig);
            $this->pagination_service->set_show_count_tag(TRUE);

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
            $data["display_finance_ship_date"] = array();
            for ($i = 0; $i < sizeof($objectResult); $i++) {
                $soResult = $objectResult[$i];
                $data["display_finance_ship_date"][$soResult->get_so_no()] = (check_finance_role() && ($soResult->get_status() >= 5) && ($soResult->get_dispatch_date() != "") && ($soResult->get_dispatch_date() != null));
            }
        }

        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
        $data["lang"] = $lang;
        $data["app_id"] = $this->getAppId();
        $this->load->view('cs/quick_search/index', $data);
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }

    public function print_invoice()
    {
        $data = array();
        if ($this->input->post('print_invoice') == 1) {
            $data["invoice_content"] = $this->quick_search_model->get_invoice_content($this->input->post('order_no'));
        }

        $this->load->view("cs/quick_search/invoice", $data);
    }

    public function view($order_no, $viewtype = "")
    {
        if (check_app_feature_access_right($this->getAppId(), 'CS000102_deactivate_client') === TRUE && $this->input->post('action') == 'deactivate_client') {
            $client_ids = $this->quick_search_model->so_service->get_dao()->get_distinct_client_id_list(array('so_no' => $order_no));

            if (!empty($client_ids[0]) && ($client = $this->quick_search_model->get_client(array('id' => $client_ids[0]))) != null) {
                $client_email = $client->get_email();

                $client->set_email($client_email . 'deactivate');
                $client->set_status(0);

                if (($res = $this->quick_search_model->update_client($client)) !== FALSE) {
                    mail('ming@valuebasket.com', '[VB] Client - ' . $client_email . ' is inactivate', 'Please inactivate ' . $client_email, "From: admin@valuebasket.com\r\n");
                }
                $_SESSION['NOTICE'] = ($res !== FALSE) ? 'client_account_deactivated' : $this->db->_error_message();
            } else {
                $_SESSION['NOTICE'] = $this->db->_error_message();
            }
            redirect('cs/quick_search/view/' . $order_no . '/' . $viewtype);
        }

        if ($this->input->post('addnote') == 1) {
            $obj = $this->quick_search_model->get_order_notes();
            $obj->set_so_no($order_no);
            $obj->set_type('O');
            $obj->set_note($this->input->post('note'));

            $ret = $this->quick_search_model->add_notes($obj);

            if ($ret === FALSE) {
                $_SESSION["NOTICE"] = "add_note_failed";
            }

            Redirect(base_url() . "cs/quick_search/view/" . $order_no . "/" . $viewtype);
        }

        if (($this->input->post('ca') == 1) && (check_app_feature_access_right($this->getAppId(), "CS000102_change_delivery_addr"))) {
            $obj = $this->quick_search_model->get(array("so_no" => $order_no));
            if (!$obj) {
                $_SESSION["NOTICE"] = $this->db->_error_message();
            } else {

                $obj->set_delivery_name($this->input->post("dname"));
                $obj->set_delivery_company($this->input->post("dcomp"));
                $obj->set_delivery_address($this->input->post("daddr1") . "|" . $this->input->post("daddr2") . "|" . $this->input->post("daddr3"));
                $obj->set_delivery_city($this->input->post("dcity"));
                $obj->set_delivery_state($this->input->post("dstate"));
                $obj->set_delivery_postcode($this->input->post("dpostcode"));
                $obj->set_delivery_country_id($this->input->post("dcountry"));

                $ret = $this->quick_search_model->update_so($obj);
                if ($ret !== FALSE) {
                    $obj = $this->quick_search_model->get_order_notes();
                    $obj->set_so_no($order_no);
                    $obj->set_type('O');
                    $obj->set_note("Delivery Address Updated");

                    $ret = $this->quick_search_model->add_notes($obj);

                    if ($ret === FALSE) {
                        $_SESSION["NOTICE"] = "add_note_failed";
                    } else {
                        // if have split child, then duplicate the changed delivery address
                        if ($split_child_list = $this->so_service->get_dao()->get_list(array("split_so_group" => $order_no, "status != 0" => NULL))) {
                            $error_mssage = "";
                            foreach ($split_child_list as $key => $childobj) {
                                $child_so_no = $childobj->get_so_no();
                                $childobj->set_delivery_name($this->input->post("dname"));
                                $childobj->set_delivery_company($this->input->post("dcomp"));
                                $childobj->set_delivery_address($this->input->post("daddr1") . "|" . $this->input->post("daddr2") . "|" . $this->input->post("daddr3"));
                                $childobj->set_delivery_city($this->input->post("dcity"));
                                $childobj->set_delivery_state($this->input->post("dstate"));
                                $childobj->set_delivery_postcode($this->input->post("dpostcode"));
                                $childobj->set_delivery_country_id($this->input->post("dcountry"));

                                $childret = $this->quick_search_model->update_so($childobj);
                                if ($childret !== FALSE) {
                                    $child_ordernotes_obj = $this->quick_search_model->get_order_notes();
                                    $child_ordernotes_obj->set_so_no($child_so_no);
                                    $child_ordernotes_obj->set_type('O');
                                    $child_ordernotes_obj->set_note("Delivery Address Updated");

                                    $childnotesret = $this->quick_search_model->add_notes($child_ordernotes_obj);
                                    if ($childnotesret === FALSE) {
                                        $error_mssage .= "add_note_failed for child $child_so_no \n";
                                    }
                                } else {
                                    $error_mssage .= "Error update del add for child $child_so_no. DB error: " . $this->db->_error_message() . "\n";
                                }
                            }
                            if ($error_mssage)
                                $_SESSION["NOTICE"] = $error_mssage;
                        }
                    }
                } else {
                    $_SESSION["NOTICE"] = $this->db->_error_message();
                }
            }
            Redirect(base_url() . "cs/quick_search/view/" . $order_no . "/" . $viewtype);
        }

        if ($order_no == "") {
            exit;
        }

        $sub_app_id = $this->getAppId() . "02";

        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
        $data["lang"] = $lang;

        $temp_obj = $this->quick_search_model->search_order(array("so.so_no" => $order_no), array("detail" => 1, "limit" => 1, "so_no" => $order_no));

        foreach ($temp_obj as $obj) {
            $order_obj = $obj;
        }

        $data["order_obj"] = $order_obj;
        $data["so_obj"] = $this->quick_search_model->get(array("so_no" => $order_no));
        $data["status"] = $this->quick_search_model->get_order_status($data["so_obj"]);

        // if this is a split child, redirect to the parent split order
        $split_so_group = $data["so_obj"]->get_split_so_group();
        if (isset($split_so_group) && ($split_so_group != $order_no))
            Redirect(base_url() . "cs/quick_search/view/" . $data["so_obj"]->get_split_so_group() . "/" . $viewtype);

        //by Nero

        //var_dump($obj);die();
        $sosh_obj = $this->quick_search_model->so_service->get_shipping_info(array("soal.so_no" => $order_no));
        $result = $this->aftership_service->get_dynamic_shipment_status($data["so_obj"]->get_so_no());
        $dynamic_tag = "";
        $retry_api = 0;
        // Change by Jerry, Not using Dynamic Tag anymore
        // if($result)
        // {
        //  $dynamic_tag = $result["status"];
        //  $retry_api =  $result["retry"];
        // }

        // if($lang[$dynamic_tag."_status"])
        // {
        //  $data["status"]["status"] = $lang[$dynamic_tag."_status"];
        //  if($lang[$dynamic_tag."_desc"])
        //  {
        //      $data["status"]["desc"] = $lang[$dynamic_tag."_desc"];
        //  }
        // }

        $data["status"]["status"] = $lang[$data["status"]['id'] . "_status"];
        $data["status"]["desc"] = $lang[$data["status"]['id'] . "_desc"];

        $data["so_extend_obj"] = $this->quick_search_model->so_service->get_soext_dao()->get_so_ext_w_reason(array("so_no" => $order_no), array("limit" => 1));
        $data["socc_obj"] = $this->quick_search_model->so_service->get_socc_dao()->get(array("so_no" => $order_no));
        $data["client_obj"] = $this->quick_search_model->get_client(array("id" => $order_obj->get_client_id()));
        $data["item_list"] = $this->quick_search_model->get_ordered_item(array("so_no" => $order_no));
        $data["item_profit"] = $this->quick_search_model->so_service->get_soid_dao()->get_list_w_prodname(array("soid.so_no" => $order_no), array("limit" => -1));
        $data["order_note"] = $this->quick_search_model->get_order_notes(array("so_no" => $order_no, "type" => "O"));
        $data["history_obj"] = $this->quick_search_model->get_order_history(array("so_no" => $order_no));
        $scores = $this->quick_search_model->get_priority_score($order_no, $data["so_obj"]->get_biz_type());
        $data["priority_score"] = $scores['score'];
        $data["priority_score_highlight"] = $scores["highlight"];

        $data["sops_last_modify"] = $this->quick_search_model->get_priority_score_obj($order_no);
        $data["sops_history_obj"] = $this->quick_search_model->get_priority_score_history_list(array("so_no" => $order_no, "score !=" => $data["priority_score"]), array("orderby" => "id desc", "limit" => 1));

#       SBF #2250 insert history of agents changing order score
        $data["sops_history_obj_list"] = $this->quick_search_model->get_priority_score_history_list(array("so_no" => $order_no), array("orderby" => "id desc"));

        if ($data["sops_obj"] = $this->so_service->get_sops_dao()->get(array("so_no" => $order_no))) {
            $data["rr_obj"] = $this->so_service->get_rr_dao()->get(array("payment_gateway_id" => $data["sops_obj"]->get_payment_gateway_id(), "risk_ref" => $data["sops_obj"]->get_risk_ref1()));
            if ($data["sops_obj"]->get_payment_gateway_id() != "paypal") {
                $data["pmgw_card_obj"] = $this->quick_search_service->get_pmgw_card_dao()->get(array("payment_gateway_id" => $data["sops_obj"]->get_payment_gateway_id(), "card_id" => $data["sops_obj"]->get_card_id()));
            }
            $data["result_remark"] = $data["sops_obj"]->get_remark();
        } else
            $data["result_remark"] = "";

        if ($order_obj->get_payment_gateway_id() == "inpendium_ctpe") {
            $result_arr = parse_ini_file(APPPATH . "/libraries/service/ctpe/resultcodes.ini");
            $extract_inpendium_result = explode('|', $data["sops_obj"]->get_remark());

            if (sizeof($extract_inpendium_result) > 0) {
                $data["inpendium_result"]['result'] = $result_arr[$extract_inpendium_result[0]];
                $data["result_remark"] .= " " . $data["inpendium_result"]['result'];
            }
            if ($data["sops_obj"]->get_risk_ref3()) {
                $extract_3d_inpendium_result = explode('|', $data["sops_obj"]->get_risk_ref3());
                if (sizeof($extract_3d_inpendium_result) > 0) {
                    $data["inpendium_result"]['3d'] = $result_arr[$extract_3d_inpendium_result[0]];
                }
            } else
                $data["inpendium_result"]['3d'] = "";
        }
        $item_website_status_when_order = "";
        $current_item_edd = "";
        foreach ($data["item_list"] as $item) {
            $item_website_status_when_order = $data["lang"][$item->get_website_status()];
            if (($item->get_website_status() == "P") || ($item->get_website_status() == "A")) {
                $product_obj = $this->quick_search_model->so_service->get_prod_srv()->get(array("sku" => $item->get_prod_sku()));
                $current_item_edd = $product_obj->get_expected_delivery_date();
                break;
            }
        }

        $total_profit = 0;
        $total_gst = 0;
        foreach ($data["item_profit"] as $value) {
            // var_dump($value);
            $individual_sku = $value->get_item_sku();
            $profit[$individual_sku] = $value->get_profit() * $value->get_qty();
            $margin[$individual_sku] = $value->get_margin();
            $gst_total[$individual_sku] = $value->get_gst_total();

            $total_profit += $profit[$individual_sku];
            $total_gst += $gst_total[$individual_sku];
        }

        $data["total_profit"] = $total_profit;
        $data["total_gst"] = $total_gst;

        $data["item_profit"] = $profit;
        $data["item_margin"] = $margin;
        $data["item_gst_total"] = $gst_total;

//          $data["expected_delivery_date"] = $order_obj->get_expect_delivery_date() . (($current_item_edd != "") ? " (Current EDD: " . $current_item_edd . ")" : "");
        $data["website_status"] = $item_website_status_when_order;
        include_once(APPPATH . "libraries/service/payment_gateway_redirect_cybersource_service.php");
        $data["sor_obj"] = $this->quick_search_model->so_service->get_sor_dao()->get(array("so_no" => $order_no));
        $cybs = new Payment_gateway_redirect_cybersource_service();
        if ($data["sor_obj"]) {
            $data["risk1"] = $cybs->risk_indictor_risk1($data["sor_obj"]->get_risk_var1());
            $data["risk2"] = $cybs->risk_indictor_avs_risk2($data["sor_obj"]->get_risk_var2());
            $data["risk3"] = $cybs->risk_indictor_cvn_risk3($data["sor_obj"]->get_risk_var3());
            $data["risk4"] = $cybs->risk_indictor_afs_factor_risk4($data["sor_obj"]->get_risk_var4());
            $data["risk5"] = $cybs->risk_indictor_score_risk5($data["sor_obj"]->get_risk_var5());
            $data["risk6"] = $cybs->risk_indictor_suspicious_risk6($data["sor_obj"]->get_risk_var6());
            $data["risk7"] = $cybs->risk_indictor_velocity_risk7($data["sor_obj"]->get_risk_var7());
            $data["risk8"] = $cybs->risk_indictor_internet_risk8($data["sor_obj"]->get_risk_var8());
            $data["risk9"] = array(0 => array("style" => "normal", "value" => $data["sor_obj"]->get_risk_var9()));
        }
//      var_dump($data["sor_obj"]);
        $data["refund_history"] = $this->quick_search_model->get_refund_history($order_no);
        $data["viewtype"] = $viewtype;
        $data["country_list"] = $this->quick_search_model->get_country_code();
        $data["hold_history"] = $this->quick_search_model->get_hold_history($order_no);
        $data["del_opt_list"] = end($this->delivery_option_service->get_list_w_key(array("lang_id" => "en")));
        $data["notice"] = notice($lang);
        $data["app_id"] = $this->getAppId();

        #sbf 2607
        if ($sorf_obj = $this->quick_search_model->get_refund_score_vo($order_no)) {
            $data["refund_score"] = $sorf_obj->get_score();
            $data["sorf_history_obj_list"] = $this->quick_search_model->get_refund_score_history_list(array("so_no" => $order_no), array("orderby" => "id desc"));
            $data["sorf_history_last_obj"] = $this->quick_search_model->get_refund_score_history_list(array("so_no" => $order_no, "score !=" => $data["refund_score"]), array("orderby" => "id desc", "limit" => 1));

        } else {
            $data["refund_score"] = null;
        }

        $data["so_list"] = $this->quick_search_model->prepareLinkedOrders($data["so_obj"]);

        if ($data["so_obj"]->get_hold_status() == 15) {
            if ($split_child_list = $this->so_service->get_dao()->get_list(array("split_so_group" => $order_no, "status != 0" => NULL))) {
                $data["child"] = $this->_prepareSplitChild((array)$split_child_list);
            }
        }
        $data["base_split_url"] = base_url() . "cs/quick_search/process_split/";

        #INSERT INTO `application_feature` (`feature_name`) VALUES ('CS000102_release_button')
        #INSERT INTO `application_feature_right` (`app_id`, `app_feature_id`, `role_id`, `status`) VALUES ('CS0001', '20', 'cs_ext_man', '1');
        $data["allow_release"] = check_app_feature_access_right($this->getAppId(), "CS000102_release_button");
        $data["allow_split"] = check_app_feature_access_right($this->getAppId(), "CS000102_process_split_order");
        //echo $this->db->Last_query();die();
        $data["release_history"] = $this->quick_search_model->get_release_order_history_list(array("so_no" => $order_no), array("orderby" => "modify_on desc"));
        $this->load->view('cs/quick_search/view_detail', $data);
    }

    private function _prepareSplitChild($split_child_list = array())
    {
        $child = array();
        if (!empty($split_child_list)) {
            foreach ($split_child_list as $key => $child_so_obj) {
                $child_so_no = $child_so_obj->get_so_no();
                $temp_child_obj = $this->quick_search_model->search_order(array("so.so_no" => $child_so_no), array("detail" => 1, "limit" => 1, "so_no" => $child_so_no));
                $child[$child_so_no]["order_obj"] = $order_obj = $temp_child_obj[0];

                $courier = "<i>Not Yet Shipped</i>";
                $tracking_no = "<i>Not Yet Shipped</i>";
                $packed_on = "<i>Not Yet Shipped</i>";
                $shipped_on = "<i>Not Yet Shipped</i>";
                if ($order_obj->get_dispatch_date()) {
                    $shipped_on = $order_obj->get_dispatch_date();
                }
                if ($order_obj->get_tracking_no()) {
                    $tmp = explode("||", $order_obj->get_tracking_no());
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
                $child[$child_so_no]["status"] = $this->quick_search_model->get_order_status($child_so_obj);

                $sosh_obj = $this->quick_search_model->so_service->get_shipping_info(array("soal.so_no" => $child_so_no));
                $result = $this->aftership_service->get_dynamic_shipment_status($child[$child_so_no]["so_obj"]->get_so_no());
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

                $child[$child_so_no]["so_extend_obj"] = $this->quick_search_model->so_service->get_soext_dao()->get_so_ext_w_reason(array("so_no" => $child_so_no), array("limit" => 1));
                $child[$child_so_no]["socc_obj"] = $this->quick_search_model->so_service->get_socc_dao()->get(array("so_no" => $child_so_no));
                $child[$child_so_no]["item_list"] = $this->quick_search_model->get_ordered_item(array("so_no" => $child_so_no));
                $child[$child_so_no]["order_note"] = $this->quick_search_model->get_order_notes(array("so_no" => $child_so_no, "type" => "O"));
                $child[$child_so_no]["history_obj"] = $this->quick_search_model->get_order_history(array("so_no" => $child_so_no));
                $scores = $this->quick_search_model->get_priority_score($child_so_no, $child[$child_so_no]["so_obj"]->get_biz_type());
                $child[$child_so_no]["priority_score"] = $scores['score'];
                $child[$child_so_no]["priority_score_highlight"] = $scores["highlight"];
                $child[$child_so_no]["sops_last_modify"] = $this->quick_search_model->get_priority_score_obj($child_so_no);
                $child[$child_so_no]["sops_history_obj"] = $this->quick_search_model->get_priority_score_history_list(array("so_no" => $child_so_no, "score !=" => $child[$child_so_no]["priority_score"]), array("orderby" => "id desc", "limit" => 1));

                $child[$child_so_no]["sops_history_obj_list"] = $this->quick_search_model->get_priority_score_history_list(array("so_no" => $child_so_no), array("orderby" => "id desc"));
                $child[$child_so_no]["refund_history"] = $this->quick_search_model->get_refund_history($child_so_no);
                $child[$child_so_no]["hold_history"] = $this->quick_search_model->get_hold_history($child_so_no);

                if ($sorf_obj = $this->quick_search_model->get_refund_score_vo($child_so_no)) {
                    $child[$child_so_no]["refund_score"] = $sorf_obj->get_score();
                    $child[$child_so_no]["sorf_history_obj_list"] = $this->quick_search_model->get_refund_score_history_list(array("so_no" => $child_so_no), array("orderby" => "id desc"));
                    $child[$child_so_no]["sorf_history_last_obj"] = $this->quick_search_model->get_refund_score_history_list(array("so_no" => $child_so_no, "score !=" => $child[$child_so_no]["refund_score"]), array("orderby" => "id desc", "limit" => 1));
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
            $data["itemrow"] = $this->process_post_data();
        } else {
            if ($order_no == "") {
                echo "Please input so_no.";
                die();
            }

            // generate logic of the furthest split available for this order
            $group_result = $this->split_order_service->gen_split_order_logic($order_no);

            if ($group_result["status"] === FALSE) {
                $_SESSION["NOTICE"] = "gen_split_order_logic fail. Message: {$group_result["message"]}.";
            } else {
                if ($group = $group_result["group"]) {
                    if (count($group) <= 1)
                        $reach_max_split = TRUE;

                    foreach ($group as $grpkey => $grparr) {
                        $mainprod_sku = $grparr["sku"];
                        $mainprodarr = $this->build_split_order_item_info($order_no, $mainprod_sku, "MAIN");
                        $mainprodarr["main_ca_uid"] = "$mainprod_sku::$grpkey";

                        # the unique ID that ties the CAs with this mainprod SKU. Pass it into the form so that link isn't broken after user change group
                        $order_group[$grpkey][] = $mainprodarr;

                        if ($calist = $grparr["calist"]) {
                            foreach ($calist as $cakey => $ca_sku) {
                                $ca_group = $this->build_split_order_item_info($order_no, $ca_sku, "CA");
                                $ca_group["main_ca_uid"] = "$mainprod_sku::$grpkey";
                                $order_group[$grpkey][] = $ca_group;
                            }
                        }
                        if ($ralist = $grparr["ralist"]) {
                            foreach ($ralist as $rakey => $ra_sku) {
                                $ra_group = $this->build_split_order_item_info($order_no, $ra_sku, "RA");
                                $order_group[$grpkey][] = $ra_group;
                            }
                        }
                    }

                    // create HTML for each item row
                    $data["itemrow"] = $this->create_split_group_rows($order_no, $order_group, $reach_max_split);
                }
                // echo "<pre>"; var_dump($order_group);die();
            }
        }

        $data["so_obj"] = $this->quick_search_model->so_service->get_dao()->get(array("so_no" => $order_no));

        // the following are not allowed to split; we overwrite itemrow
        $quicksearchurl = base_url() . "cs/quick_search/view/$order_no";
        if ($data["so_obj"]->get_hold_status() == 15) {
            $data["itemrow"] = "FORBIDDEN: THIS ORDER ALREADY CONTAINS SPLIT CHILD - <a href='$quicksearchurl'>$quicksearchurl</a><br><br>";
        } elseif ($data["so_obj"]->get_hold_status() != 0 || $data["so_obj"]->get_refund_status() != 0 || $data["so_obj"]->get_status() < 3) {
            $data["itemrow"] = "FORBIDDEN: THIS ORDER IS / NOT CREDITCHECKED / ON HOLD / REFUND REQUEST - <a href='$quicksearchurl'>$quicksearchurl</a><br><br>";
        } elseif (substr($data["so_obj"]->get_platform_id(), 0, 3) !== "WEB") {
            $data["itemrow"] = "FORBIDDEN: THIS IS MARKETPLACE ORDER - <a href='$quicksearchurl'>$quicksearchurl</a><br><br>";
        }

        $data["notice"] = notice();
        $this->load->view('cs/quick_search/split_order_view', $data);
    }

    private function process_post_data()
    {
        $i = 0;
        $data = "";
        $regroup = $order_group = array();
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
            $data = $this->create_split_group_rows($order_no, $order_group);
            return $data;
        } else {

            $result = $this->quick_search_model->so_service->split_order_to_so($order_no, $order_group);
            $_SESSION["NOTICE"] = $result["message"];

            if ($result["status"] === FALSE)
                return $data;
            else {
                // everything success, redirect to OQS
                Redirect(base_url() . "cs/quick_search/view/" . $order_no);
            }
        }
    }

    private function create_split_group_rows($order_no, $order_group = array(), $reach_max_split = FALSE)
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

    private function build_split_order_item_info($order_no, $sku, $type = "MAIN")
    {
        $iteminfo = array();
        $iteminfo["sku"] = $sku;
        $iteminfo["type"] = $type;

        # get supplier info
        $iteminfo["suppcountry"] = "";
        if ($supplierinfo = $this->supplier_service->get_dao()->get_supplier($sku))
            $iteminfo["suppcountry"] = $supplierinfo->get_origin_country();

        # get prodict info
        $iteminfo["name"] = $iteminfo["slow_move_7_days"] = "";
        if ($productinfo = $this->product_service->get_dao()->get(array("sku" => $sku))) {
            $iteminfo["name"] = $productinfo->get_name();
            $sourcingstatus = $productinfo->get_sourcing_status();
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
            $iteminfo["slow_move_7_days"] = $productinfo->get_slow_move_7_days();
            $iteminfo["surplus"] = $productinfo->get_surplus_quantity();
        }

        $iteminfo["mastersku"] = "";
        if ($master_sku_obj = $this->product_service->get_master_sku(array("sku" => $sku, "ext_sys" => "WMS", "status" => 1))) {
            $iteminfo["mastersku"] = $master_sku_obj->get_ext_sku();
        }

        # get item_detail info
        $iteminfo["profit"] = $iteminfo["profit_raw"] = $iteminfo["margin"] = $iteminfo["profit_raw"] = "";
        // if(count((array)$so_item_detail) > 0)
        if ($so_item_detail = $this->quick_search_model->so_service->get_soid_dao()->get_list_w_prodname(array("soid.so_no" => $order_no, "soid.item_sku" => $sku))) {
            foreach ($so_item_detail as $soid_obj) {
                // $total_discount = $soid_obj->get_promo_disc_amt(); # total discount applied to this sku
                $qty = $soid_obj->get_qty(); # total qty for this sku
                $total_amount = $soid_obj->get_amount(); # total paid for this sku
                $iteminfo["unit_amt"] = number_format($total_amount / $qty, 2, '.', '');
                $iteminfo["profit"] = $soid_obj->get_profit();
                $iteminfo["profit_raw"] = $soid_obj->get_profit_raw();
                $iteminfo["margin"] = $soid_obj->get_margin();
                $iteminfo["margin_raw"] = $soid_obj->get_margin_raw();
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

        $soext_dao = $this->quick_search_model->so_service->get_soext_dao();
        $where["so_no"] = $so_no;
        if ($so_extend_obj = $this->quick_search_model->so_service->get_soext_dao()->get($where)) {
            if ($so_extend_obj->get_fulfilled() == "N") {
                $so_extend_obj->set_fulfilled("Y");
                if ($soext_dao->update($so_extend_obj) === FALSE) {
                    $_SESSION["NOTICE"] = "Cannot update so_extend as fulfilled. DB Error: " . $soext_dao->db->_error_message();
                }
            }

        } else {
            $_SESSION["NOTICE"] = "Cannot find so_extend obj with so_no <$so_no>";
        }

        Redirect(base_url() . "cs/quick_search/view/" . $so_no);
    }

    public function update_priority_score($so_no)
    {
        $no = $this->quick_search_model->get_sops_history_number(array("so_no" => $so_no));
        if ($no == 0) {
            $scores = $this->quick_search_model->get_priority_score($so_no, $_POST["biz_type"]);
            $auto_score = $scores['score'];
            $this->quick_search_model->insert_sops($so_no, $auto_score);
        }
        $this->quick_search_model->update_sops($so_no, $_POST["priority_score"]);
        Redirect(base_url() . "cs/quick_search/view/" . $so_no);
    }

    public function update_refund_score($so_no)
    {

        $new_score = $_POST['new_refund_score'];
        //var_dump($new_score);die();
        if ($sorf_vo = $this->quick_search_model->get_refund_score_vo($so_no)) {
            $current_score = $sorf_vo->get_score();
            if ($current_score == $new_score) {
                Redirect(base_url() . "cs/quick_search/view/" . $so_no);
            }
            if ($this->quick_search_model->update_refund_score($so_no, $new_score)) {
                $this->quick_search_model->insert_refund_score_history($so_no, $new_score);
            }

        } else {
            if ($this->quick_search_model->insert_refund_score($so_no, $new_score)) {
                $this->quick_search_model->insert_refund_score_history($so_no, $new_score);
            }
        }
        Redirect(base_url() . "cs/quick_search/view/" . $so_no);
    }

    private function _prepareLinkedOrders($so_obj)
    {
        $where = array("status >=" => 1);
        $option = array("limit" => -1, "orderby" => "so_no");
        if (($so_obj->get_parent_so_no() != null) && ($so_obj->get_parent_so_no() != "")) {
            $where["parent_so_no"] = $so_obj->get_parent_so_no();
            $first_so = $this->so_model->so_service->get_dao()->get(array("so_no" => $so_obj->get_parent_so_no()));
        } else {
            $where["parent_so_no"] = $so_obj->get_so_no();
        }
        $so_list = $this->so_model->so_service->get_dao()->get_list($where, $option);

        if ($first_so) {
            if (sizeof((array)$so_list) > 0)
                return array_merge(array(0 => $first_so), (array)$so_list);
            else
                return array();
        } else {
            if (sizeof((array)$so_list) > 0)
                return array_merge(array(0 => $so_obj), (array)$so_list);
            else
                return array();
        }
    }

}

?>