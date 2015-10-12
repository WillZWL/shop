<?php

class On_hold_admin extends MY_Controller
{

    private $appId = "ORD0007";
    private $lang_id = "en";


    public function __construct()
    {
        parent::__construct();
        // $this->load->model('order/on_hold_admin_model');
        // $this->load->model('order/credit_check_model');
    }

    public function index($pmghold = 0, $offset = 0)
    {
        $search = $this->input->get('search');
        $sub_app_id = $this->getAppId() . "01";

        if ($search) {

            $_SESSION["LISTPAGE"] = base_url() . "order/on_hold_admin/?" . $_SERVER['QUERY_STRING'];

            $where = [];
            $option = [];

            $where["so.hold_status"] = "0";
            $where["so.status >"] = "2";
            $where["so.status <"] = "6";

            if ($this->input->get("so_no") != "") {
                $where["so.so_no"] = trim($this->input->get("so_no"));
                $submit_search = 1;
            }

            if ($this->input->get("platform_order_id") != "") {
                $where["so.platform_order_id LIKE "] = "%" . $this->input->get("platform_order_id") . "%";
                $submit_search = 1;
            }

            if ($this->input->get("payment_gateway_id") != "") {
                $where["sops.payment_gateway_id"] = $this->input->get("payment_gateway_id");
                $submit_search = 1;
            }

            if ($this->input->get("txn_id") != "") {
                $where["txn_id"] = $this->input->get("txn_id");
                $submit_search = 1;
            }

            if ($this->input->get("amount") != "") {
                fetch_operator($where, "amount", $this->input->get("amount"));
                $submit_search = 1;
            }

            if ($this->input->get("t3m_result") != "") {
                fetch_operator($where, "t3m_result", $this->input->get("t3m_result"));
                $submit_search = 1;
            }

            $sort = $this->input->get("sort");
            $order = $this->input->get("order");

            $limit = '20';

            $option["limit"] = $limit;
            $option["offset"] = $offset;

            if (empty($sort))
                $sort = "so_no";

            if (empty($order))
                $order = "desc";

            $option["orderby"] = $sort . " " . $order;
            $data["objlist"] = $this->sc['So']->getCreditCheckList($where, $option, "hold");
            if ($data["objlist"]) {

                $cybs = $this->sc['PaymentGatewayRedirectCybersource'];

                foreach ($data["objlist"] AS $obj) {
                    if ($obj->getSorObj()) {
                        $data["risk2"][$obj->getSoNo()] = $cybs->riskIndictorAvsRisk2($obj->getSorObj()->getRiskVar2());
                        $data["risk3"][$obj->getSoNo()] = $cybs->riskIndictorCvnRisk3($obj->getSorObj()->getRiskVar3());
                    }
                }
            }

            $data["total"] = $this->sc['So']->getDao('So')->getCreditCheckList($where, ["num_rows" => 1], "hold");

            $config['base_url'] = base_url('cs/order/on_hold_admin/'.$pmghold)."?" . $_SERVER['QUERY_STRING'];
            $config['total_rows'] = $data["total"];
            $config['per_page'] = $limit;

            $this->pagination->initialize($config);
            $data['links'] = $this->pagination->create_links();


            $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
            $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
            $data["searchdisplay"] = "";
        }

        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->getLangId() . ".php");
        $data["lang"] = $lang;

        $data["notice"] = notice($lang);

        $this->load->view('order/on_hold_admin/index_v', $data);
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function log_approval_page($pmghold = 0)
    {
        $sub_app_id = $this->getAppId() . "02";

        $_SESSION["LISTPAGE"] = base_url() . "order/on_hold_admin/log_approval_page/?" . $_SERVER['QUERY_STRING'];

        $where = [];
        $option = [];

        $where["so.hold_status"] = "1";
        $where["so.status >"] = "2";
        $where["so.status <"] = "6";
        $where["sohr.reason LIKE"] = '%_log_app';

        if ($this->input->get("so_no") != "") {
            $where["so.so_no LIKE "] = "%" . $this->input->get("so_no") . "%";
            $submit_search = 1;
        }

        if ($this->input->get("platform_order_id") != "") {
            $where["so.platform_order_id LIKE "] = "%" . $this->input->get("platform_order_id") . "%";
            $submit_search = 1;
        }

        if ($this->input->get("payment_gateway_id") != "") {
            $where["payment_gateway_id"] = $this->input->get("payment_gateway_id");
            $submit_search = 1;
        }

        if ($this->input->get("txn_id") != "") {
            $where["txn_id"] = $this->input->get("txn_id");
            $submit_search = 1;
        }

        if ($this->input->get("amount") != "") {
            fetch_operator($where, "amount", $this->input->get("amount"));
            $submit_search = 1;
        }

        if ($this->input->get("t3m_result") != "") {
            fetch_operator($where, "t3m_result", $this->input->get("t3m_result"));
            $submit_search = 1;
        }

        $sort = $this->input->get("sort");
        $order = $this->input->get("order");

        $limit = '20';

        $pconfig['base_url'] = $_SESSION["LISTPAGE"];
        $option["limit"] = $pconfig['per_page'] = $limit;

        if ($option["limit"]) {
            $option["offset"] = $this->input->get("per_page");
        }

        if (empty($sort))
            $sort = "so_no";

        if (empty($order))
            $order = "desc";

        $option["orderby"] = $sort . " " . $order;

        $data["objlist"] = $this->sc['So']->getDao('So')->getCreditCheckList($where, $option, "log_app");
        $data["total"] = $this->sc['So']->getDao('So')->getCreditCheckList($where, ["num_rows" => 1], "log_app");

        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->getLangId() . ".php");
        $data["lang"] = $lang;

        $pconfig['total_rows'] = $data['total'];
        $this->pagination_service->set_show_count_tag(TRUE);
        $this->pagination_service->initialize($pconfig);

        $data["notice"] = notice($lang);

        $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
        $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
        $data["searchdisplay"] = "";
        $this->load->view('order/on_hold_admin/log_approve_index', $data);
    }

    public function oc_index($type = "", $offset = 0)
    {
        if ($type != "" && $type != "cc" && $type != "vv") {
            Redirect(base_url() . "order/on_hold_admin/oc_index/");
        } else {
            $sub_app_id = $this->getAppId() . "03";

            $_SESSION["LISTPAGE"] = base_url() . "order/on_hold_admin/oc_index" . ($type == "" ? "" : "/" . $type) . "/?" . $_SERVER['QUERY_STRING'];

            $where = [];
            $option = [];

            $where["so.hold_status"] = "1";
            $where["so.status >"] = "1";
            $where["so.status <"] = "6";
            $where["so.hold_reason NOT LIKE"] = '%_log_app';

            if ($type != "") {
                $where["so.hold_reason"] = "cs" . $type;
            }

            if ($this->input->get("so_no") != "") {
                $where["so.so_no LIKE "] = "%" . $this->input->get("so_no") . "%";
                $submit_search = 1;
            }

            if ($this->input->get("platform_order_id") != "") {
                $where["so.platform_order_id LIKE "] = "%" . $this->input->get("platform_order_id") . "%";
                $submit_search = 1;
            }

            if ($this->input->get("payment_gateway_id") != "") {
                $where["payment_gateway_id"] = $this->input->get("payment_gateway_id");
                $submit_search = 1;
            }

            if ($this->input->get("txn_id") != "") {
                $where["txn_id"] = $this->input->get("txn_id");
                $submit_search = 1;
            }

            if ($this->input->get("amount") != "") {
                fetch_operator($where, "amount", $this->input->get("amount"));
                $submit_search = 1;
            }

            if ($this->input->get("t3m_result") != "") {
                fetch_operator($where, "t3m_result", $this->input->get("t3m_result"));
                $submit_search = 1;
            }

            $sort = $this->input->get("sort");
            $order = $this->input->get("order");

            $limit = '20';

            $option["limit"] = $limit;
            $option["offset"] = $offset;

            if (empty($sort))
                $sort = "so_no";

            if (empty($order))
                $order = "desc";

            $option["orderby"] = $sort . " " . $order;
            $data["objlist"] = $this->sc['So']->getCreditCheckList($where, $option, "oc");

            if ($data["objlist"]) {

                $cybs = $this->sc['PaymentGatewayRedirectCybersource'];

                foreach ($data["objlist"] AS $obj) {
                    if ($obj->getSorObj()) {
                        $data["risk2"][$obj->getSoNo()] = $cybs->riskIndictorAvsRisk2($obj->getSorObj()->getRiskVar2());
                        $data["risk3"][$obj->getSoNo()] = $cybs->riskIndictorCvnRisk3($obj->getSorObj()->getRiskVar3());
                    }
                }
            }

            $data["total"] = $this->sc['So']->getDao('So')->getCreditCheckList($where, ["num_rows" => 1], "oc");


            include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->getLangId() . ".php");

            if ($this->allowed_to_cancel_order()) {
                $lang["cancel_order"] = "Cancel Order";
            } else {
                $lang["cancel_order"] = "";
            }

            $data["lang"] = $lang;

            $config['base_url'] = base_url('cs/order/on_hold_admin/oc_index'.$type)."?" . $_SERVER['QUERY_STRING'];
            $config['total_rows'] = $data["total"];
            $config['per_page'] = $limit;

            $this->pagination->initialize($config);
            $data['links'] = $this->pagination->create_links();

            $data["notice"] = notice($lang);
            $data["type"] = $type;
            $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
            $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
            $data["searchdisplay"] = "";
            $this->load->view('order/on_hold_admin/index_' . ($type == "" ? "oc" : $type), $data);  #index_oc.php
        }
    }

    public function allowed_to_cancel_order()
    {
        // checks if user is allowed to cancel test order
        return $this->sc['User']->isAllowedToCancelOrder();
    }

    public function chk_pw()
    {
        $password = $this->input->get("pw");
        if ($password) {
            $sub_app_id = $this->getAppId() . "00";

            $_SESSION["LISTPAGE"] = base_url() . "order/on_hold_admin/chk_pw/" . $password . "/?" . $_SERVER['QUERY_STRING'];

            $where = [];
            $option = [];

            $where["c.password"] = $password;

            $sort = $this->input->get("sort");
            $order = $this->input->get("order");

            $limit = '20';

            $pconfig['base_url'] = $_SESSION["LISTPAGE"];
            $option["limit"] = $pconfig['per_page'] = $limit;

            if ($option["limit"]) {
                $option["offset"] = $this->input->get("per_page");
            }

            if (empty($sort))
                $sort = "so_no";

            if (empty($order))
                $order = "DESC";

            $option["orderby"] = $sort . " " . $order;
            $option["reason"] = 1;
            $option["item"] = 1;

            $data["objlist"] = $this->sc['So']->getDao('So')->getCreditCheckList($where, $option);
            $data["total"] = $this->sc['So']->getDao('So')->getCreditCheckList($where, ["num_rows" => 1]);

            include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->getLangId() . ".php");
            $data["lang"] = $lang;

            $pconfig['total_rows'] = $data['total'];
            $this->pagination_service->set_show_count_tag(TRUE);
            $this->pagination_service->initialize($pconfig);

            $data["notice"] = notice($lang);

            $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
            $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
            $data["searchdisplay"] = "";
            $this->load->view('order/credit_check/credit_check_chk_pw_v', $data);
        }
    }

    public function refund($so_no = "")
    {
        if (($so_obj = $this->sc['So']->getDao('So')->get(["so_no" => $so_no])) === FALSE) {
            $_SESSION["NOTICE"] = $this->db->_error_message();
        } else {
            if (empty($so_obj)) {
                $_SESSION["NOTICE"] = "so_not_found";
            } else {
                if ($so_no && !$this->sc['Refund']->createRefund($so_no)) {
                    $_SESSION["NOTICE"] = "failed_create_refund";
                } else {
                    $this->_create_release_order_record($so_no, 'request refund');
                }

                if (!is_null($so_obj->getCcReminderScheduleDate())) {
                    $so_obj->setCcReminderScheduleDate(NULL);
                    $so_obj->setCcReminderType(NULL);
                    if (!$this->sc['So']->getDao('So')->update($so_obj)) {
                        $_SESSION["NOTICE"] = $this->db->_error_message();
                    }
                }
            }
        }

        if (isset($_SESSION["LISTPAGE"])) {
            redirect($_SESSION["LISTPAGE"]);
        } else {
            redirect(current_url());
        }

    }

    public function _create_release_order_record($so_no, $reason)
    {
        $release_order_vo = $this->sc['So']->getDao('ReleaseOrderReport')->get();
        $release_order_vo->setSoNo($so_no);
        $release_order_vo->setReleaseReason("$reason");
        if (!$this->sc['So']->getDao('ReleaseOrderReport')->insert($release_order_vo)) {
            $_SESSION["NOTICE"] = $this->db->_error_message();
        }
    }

    public function delete($so_no = "")
    {
        if (($so_obj = $this->sc['So']->getDao('So')->get(["so_no" => $so_no])) === FALSE) {
            $_SESSION["NOTICE"] = $this->db->_error_message();
        } else {
            if (empty($so_obj)) {
                $_SESSION["NOTICE"] = "so_not_found";
            } else {
                $so_obj->set_status(0);
                if (!$this->sc['So']->getDao('So')->update($so_obj)) {
                    $_SESSION["NOTICE"] = $this->db->_error_message();
                }
            }
        }
        if (isset($_SESSION["LISTPAGE"])) {
            redirect($_SESSION["LISTPAGE"]);
        } else {
            redirect(current_url());
        }
    }

    public function oc_cancel_order($so_no = "")
    {
#       SBF #2396 to allow user to cancel test orders
        if ($this->allowed_to_cancel_order()) {
            if (($so_obj = $this->sc['So']->getDao('So')->get(["so_no" => $so_no])) === FALSE) {
                $_SESSION["NOTICE"] = $this->db->_error_message();
            } else {
                if (empty($so_obj)) {
                    $_SESSION["NOTICE"] = "so_not_found";
                } else {
                    $so_obj->set_status(0);
                    $so_obj->setHoldStatus(0);
                    $so_obj->setRefundStatus(0);
                    if (!$this->sc['So']->getDao('So')->update($so_obj)) {
                        $_SESSION["NOTICE"] = $this->db->_error_message();
                    } else {
                        $this->_create_release_order_record($so_no, $reason = 'cancel order');
                    }
                }
            }
            if (isset($_SESSION["LISTPAGE"])) {
                redirect($_SESSION["LISTPAGE"]);
            } else {
                redirect(current_url());
            }
        }
    }

    public function oc_request($so_no = "", $reason = "")
    {
        $this->oc_contacted($so_no, $reason);
    }

    public function oc_contacted($so_no = "", $reason = "contacted")
    {
        if (($so_obj = $this->sc['So']->getDao('So')->get(["so_no" => $so_no])) === FALSE) {
            $_SESSION["NOTICE"] = $this->db->_error_message();
        } else {
            if (empty($so_obj)) {
                $_SESSION["NOTICE"] = "so_not_found";
            } else {
                if (($sohr_vo = $this->sc['So']->getDao('SoHoldReason')->get()) !== FALSE) {
                    $sohr_vo->setSoNo($so_no);
                    $sohr_vo->setReason($reason);
                    if (!$this->sc['So']->getDao('SoHoldReason')->insert($sohr_vo)) {
                        $_SESSION["NOTICE"] = $this->db->_error_message();
                    }

                    if ($reason == "confirmed_fraud") {
                        $action = "update";
                        $socc_obj = $this->sc['So']->getDao('SoCreditChk')->get(["so_no" => $so_no]);
                        if (!$socc_obj) {
                            $socc_obj = $this->sc['So']->getDao('SoCreditChk')->get();
                            $action = "insert";
                        }
                        $this->sc['So']->getDao('SoCreditChk')->db->trans_start();
                        $socc_obj->setSoNo($so_no);
                        $socc_obj->set_fd_status(2);
                        $this->sc['So']->getDao('SoCreditChk')->$action($socc_obj);
                        $so_obj->set_status(0);

                        if (!is_null($so_obj->getCcReminderScheduleDate())) {
                            $so_obj->setCcReminderScheduleDate(NULL);
                            $so_obj->setCcReminderType(NULL);
                        }

                        if (!$this->sc['So']->getDao('So')->update($so_obj)) {
                            $_SESSION["NOTICE"] = $this->db->_error_message();
                        } else {
                            $this->_create_release_order_record($so_no, $reason);
                        }
                        $this->sc['So']->getDao('SoCreditChk')->db->trans_complete();
                    }

                    if (($reason == "cscc") || ($reason == "csvv")) {
                        $this->sc['So']->fireCsRequest($so_no, $reason);

                        if (!is_null($so_obj->getCcReminderScheduleDate())) {
                            $so_obj->setCcReminderScheduleDate(NULL);
                            $so_obj->setCcReminderType(NULL);

                            if (!$this->sc['So']->getDao('So')->update($so_obj)) {
                                $_SESSION["NOTICE"] = $this->db->_error_message();
                            }
                        }
                    }
                    if ($reason == "contacted") {
                        $this->_create_release_order_record($so_no, $reason);
                    }
                }
            }
        }
        if (isset($_SESSION["LISTPAGE"])) {
            redirect($_SESSION["LISTPAGE"]);
        } else {
            redirect(current_url());
        }
    }

    public function oc_approve($so_no = "")
    {
        $this->log_decline($so_no, "1");
    }

    public function log_decline($so_no = "", $from_oc = "")
    {
        if (($so_obj = $this->sc['So']->getDao('So')->get(["so_no" => $so_no])) === FALSE) {
            $_SESSION["NOTICE"] = $this->db->_error_message();
        } else {
            if (empty($so_obj)) {
                $_SESSION["NOTICE"] = "so_not_found";
            } else {
                $so_obj->setHoldStatus(0);
                if ($so_obj->get_status() < 3) {
                    $so_obj->set_status(3);
                }

                if (!is_null($so_obj->getCcReminderScheduleDate())) {
                    $so_obj->setCcReminderScheduleDate(NULL);
                    $so_obj->setCcReminderType(NULL);
                }

                if (!$this->sc['So']->getDao('So')->update($so_obj)) {
                    $_SESSION["NOTICE"] = $this->db->_error_message();
                } else {
                    $this->_create_release_order_record($so_no, 'approve for fulfillment');
                }

                if ($from_oc == "") {
                    //mail_event
                    $this->sc['So']->fireLogEmailEvent($so_no, "log2cs", "decline");
                }
            }
        }
        if (isset($_SESSION["LISTPAGE"])) {
            redirect($_SESSION["LISTPAGE"]);
        } else {
            redirect(current_url());
        }
    }

    public function oc_fraud($so_no = "")
    {
        $this->oc_contacted($so_no, "confirmed_fraud");
    }

    public function log_approve($so_no = "", $reason = "")
    {
        if (($so_obj = $this->sc['So']->getDao('So')->get(["so_no" => $so_no])) === FALSE) {
            $_SESSION["NOTICE"] = $this->db->_error_message();
        } else {
            if (empty($so_obj)) {
                $_SESSION["NOTICE"] = "so_not_found";
            } else {
                if (($sohr_vo = $this->sc['So']->getDao('SoHoldReason')->get()) !== FALSE) {
                    $sohr_vo->setSoNo($so_no);
                    $sohr_vo->setReason($reason);

                    if (!$this->sc['So']->getDao('SoHoldReason')->insert($sohr_vo)) {
                        $_SESSION["NOTICE"] = $this->db->_error_message();
                    }

                    //mail_event
                    $this->sc['So']->fireLogEmailEvent($so_no, "log2cs", "approve");
                }
            }
        }
        if (isset($_SESSION["LISTPAGE"])) {
            redirect($_SESSION["LISTPAGE"]);
        } else {
            redirect(current_url());
        }
    }

    public function hold($so_no = "")
    {
        #SBF #4646 using $_GET['cf'] to get the varible value that is pass thorugh the url from credit_check_index_v.php
        $reason = $_GET["cf"];
        if (($so_obj = $this->sc['So']->getDao('So')->get(["so_no" => $so_no])) === FALSE) {
            $_SESSION["NOTICE"] = $this->db->_error_message();
        } else {
            if (empty($so_obj)) {
                $_SESSION["NOTICE"] = "so_not_found";
            } else {
                $packed_item = $this->sc['So']->checkIfPacked($so_no);

                $so_obj->setHoldStatus(1);

                if ($this->sc['So']->getDao('So')->update($so_obj)) {
                    if (($sohr_vo = $this->sc['So']->getDao('SoHoldReason')->get()) !== FALSE) {
                        $sohr_vo->setSoNo($so_no);
                        if (count((array)$packed_item)) {
                            if ($reason == '') {
                                $this->sc['So']->fireCs2logEmail($so_no, $this->input->post("reason"), $_SESSION["user"]);
                                $sohr_vo->setReason($this->input->post("reason") . "_log_app");
                            } else {
                                $sohr_vo->setReason($reason . "_log_app");
                            }

                        } else {
                            if ($reason == '') {
                                $sohr_vo->setReason($this->input->post("reason"));
                            } else {
                                $sohr_vo->setReason($reason);
                                $this->sc['So']->addOrderNote($so_no, 'Saved from CC, held wait for customer\'s decision');

                                $socc_obj = $this->sc['So']->getDao('SoCreditChk')->get(["so_no" => $so_no]);
                                $socc_obj->setCcAction(2);  // 0010 = 2, bit 1 is save order
                                $this->sc['So']->getDao('SoCreditChk')->update($socc_obj, ['so_no' => $so_no]);
                            }
                        }

                        if (!$this->sc['So']->getDao('SoHoldReason')->insert($sohr_vo)) {
                            $_SESSION["NOTICE"] = $this->db->_error_message();
                        }
                    } else {
                        $_SESSION["NOTICE"] = $this->db->_error_message();
                    }
                } else {
                    $_SESSION["NOTICE"] = $this->db->_error_message();
                }
            }
        }
        if (isset($_SESSION["LISTPAGE"])) {
            redirect($_SESSION["LISTPAGE"]);
        } else {
            redirect(current_url());
        }

    }
}



