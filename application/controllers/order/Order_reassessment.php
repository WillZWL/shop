<?php

class Order_reassessment extends MY_Controller
{

    private $appId = "ORD0014";
    private $lang_id = "en";


    public function __construct()
    {
        parent::__construct();
        // $this->load->model('order/credit_check_model');
        // $this->load->helper(array('html', 'url', 'notice', 'object', 'operator'));
        // $this->load->library('service/pagination_service');
        // $this->load->library('encrypt');
        // $this->load->library('dao/so_hold_reason_dao');
        // $this->load->library('dao/order_notes_dao');
    }

    public function index($pmghold = 0)
    {
        $sub_app_id = $this->getAppId() . "01";

        $_SESSION["LISTPAGE"] = $_SESSION["CCLISTPAGE"] = base_url() . "order/order_reassessment/?" . $_SERVER['QUERY_STRING'];

        $where = array();
        $option = array();

        $where["so.refund_status"] = '0';
        //not getting OOS order in
        $where["so.hold_status <>"] = '3';

        if (!$this->input->post("type")) {
            $where["so.biz_type"] = "OFFLINE";
        } else {
            $where["so.biz_type <> "] = "OFFLINE";
        }

        if ($this->input->post("so_no") != "") {
            $where["so.so_no LIKE "] = "%" . $this->input->post("so_no") . "%";
            $submit_search = 1;
        }

        if ($this->input->post("platform_order_id") != "") {
            $where["so.platform_order_id LIKE "] = "%" . $this->input->post("platform_order_id") . "%";
            $submit_search = 1;
        }

        if ($this->input->post("payment_gateway_id") != "") {
            $where["sops.payment_gateway_id"] = $this->input->post("payment_gateway_id");
            $submit_search = 1;
        }

        if ($this->input->post("txn_id") != "") {
            $where["txn_id"] = $this->input->post("txn_id");
            $submit_search = 1;
        }

        if ($this->input->post("amount") != "") {
            fetch_operator($where, "amount", $this->input->post("amount"));
            $submit_search = 1;
        }

        if ($this->input->post("t3m_result") != "") {
            fetch_operator($where, "t3m_result", $this->input->post("t3m_result"));
            $submit_search = 1;
        }

        $sd = $this->input->post("start_date");
        $ed = $this->input->post("end_date");
        if ($sd != "" and $ed != "") {
            $str = "so.create_on between '$sd' and '$ed'";
            $where[$str] = NULL;
            $data["start_date"] = $sd;
            $data["end_date"] = $ed;
        }
        if ($this->input->post("reason") != "") {
            $where["so.hold_reason"] = $this->input->post("reason");
        }

        $sort = $this->input->post("sort");
        $order = $this->input->post("order");

        $option['limit'] = ($this->input->get('limit') != '') ? $this->input->get('limit') : '20';
        $option['offset'] = ($this->input->get('per_page') != '') ? $this->input->get('per_page') : '';

        if (empty($sort))
            $sort = "so_no";

        if (empty($order))
            $order = "desc";

        $option["orderby"] = $sort . " " . $order;

        if ($this->input->post("type") !== false) {
            $data["objlist"] = $this->sc['So']->getCreditCheckList($where, $option, "ora");
            $data["total"] = $this->sc['So']->getDao('So')->getCreditCheckList($where, array("num_rows" => 1), "ora");
        }
        $data["reason_list"] = $this->sc['So']->getDao('HoldReason')->getList(array_merge(['status'=>1]), ['orderby'=>'reason_cat asc, description asc', 'limit'=>-1]);
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->getLangId() . ".php");
        $data["lang"] = $lang;

        $pconfig['base_url'] = $_SESSION["CCLISTPAGE"];
        $config['total_rows'] = $data["total"];
        $config['page_query_string'] = true;
        $config['reuse_query_string'] = true;
        $config['per_page'] = $option['limit'];
        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();

        $data["notice"] = notice($lang);

        $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
        $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
//      $data["searchdisplay"] = ($submit_search)?"":'style="display:none"';
        $data["searchdisplay"] = "";
        $this->load->view('order/credit_check/order_reassessment_v', $data);
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function chk_pw()
    {
        $password = $this->input->get("pw");
        if ($password) {

            $sub_app_id = $this->getAppId() . "00";

            $_SESSION["LISTPAGE"] = base_url() . "order/order_reassessment/chk_pw/" . $password . "/?" . $_SERVER['QUERY_STRING'];

            $where = array();
            $option = array();

            $where["c.password"] = $password;

            $sort = $this->input->get("sort");
            $order = $this->input->get("order");

            $limit = '20';

            $option['limit'] = ($this->input->get('limit') != '') ? $this->input->get('limit') : '20';
            $option['offset'] = ($this->input->get('per_page') != '') ? $this->input->get('per_page') : '';

            if (empty($sort))
                $sort = "so_no";

            if (empty($order))
                $order = "DESC";

            $option["orderby"] = $sort . " " . $order;
            $option["reason"] = 1;
            $option["item"] = 1;

            $data["objlist"] = $this->sc['So']->getDao('So')->getCreditCheckList($where, $option);
            $data["total"] = $this->sc['So']->getDao('So')->getCreditCheckList($where, array("num_rows" => 1));

            include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->getLangId() . ".php");
            $data["lang"] = $lang;

            $pconfig['base_url'] = $_SESSION["LISTPAGE"];
            $config['total_rows'] = $data["total"];
            $config['page_query_string'] = true;
            $config['reuse_query_string'] = true;
            $config['per_page'] = $option['limit'];
            $this->pagination->initialize($config);
            $data['links'] = $this->pagination->create_links();

            $data["notice"] = notice($lang);

            $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
            $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
            //      $data["searchdisplay"] = ($submit_search)?"":'style="display:none"';
            $data["searchdisplay"] = "";
            $this->load->view('order/credit_check/credit_check_chk_pw_v', $data);
        }
    }

    public function approve($so_no = "")
    {
        if (($so_obj = $this->sc['So']->getDao('So')->get(array("so_no" => $so_no))) === FALSE) {
            $_SESSION["NOTICE"] = $this->db->_error_message();
        } else {
            if (empty($so_obj)) {
                $_SESSION["NOTICE"] = "so_not_found";
            } else {
                $action = "update";
                $socc_obj = $this->sc['So']->getDao('SoCreditChk')->get(array("so_no" => $so_no));
                if (!$socc_obj) {
                    $socc_obj = $this->sc['So']->getDao('SoCreditChk')->get();
                    $action = "insert";
                }
                $this->sc['So']->getDao('SoCreditChk')->trans_start();
                $socc_obj->setSoNo($so_no);
                $socc_obj->setFdStatus(0);
                $this->sc['So']->getDao('SoCreditChk')->$action($socc_obj);

                $so_obj->setStatus(3);
                $so_obj->setHoldStatus(0);
                $so_obj->setRefundStatus(0);
                if (!$this->sc['So']->getDao('So')->update($so_obj)) {
                    $_SESSION["NOTICE"] = $this->db->_error_message();
                }
                $this->sc['So']->getDao('SoCreditChk')->trans_complete();
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
        $reason_id = $this->input->post("reason");
        $reasonObj = $this->sc['So']->getDao('HoldReason')->get(['id'=>$reason_id]);
        $reason_type = $reasonObj->getReasonType();

        if (($so_obj = $this->sc['So']->getDao('So')->get(array("so_no" => $so_no))) === FALSE) {
            $_SESSION["NOTICE"] = $this->db->_error_message();
        } else {
            if (empty($so_obj)) {
                $_SESSION["NOTICE"] = "so_not_found";
            } else {
                $so_obj->setHoldStatus(1);
                if ($this->sc['So']->getDao('So')->update($so_obj)) {
                    if (($sohr_vo = $this->sc['So']->getDao('SoHoldReason')->get()) !== FALSE) {
                        $sohr_vo->setReason($reason_id);
                        $sohr_vo->setSoNo($so_no);

                        if ($this->sc['So']->getDao('SoHoldReason')->insert($sohr_vo) === FALSE) {
                            $_SESSION["NOTICE"] = $this->db->_error_message();
                        }
                        if (in_array($reason_type, array("cscc", "csvv"))) {
                            $this->sc['So']->fireCsRequest($so_no, $reason_type);
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

    public function release_hold($so_no = "")
    {
        #sbf #3676 release hold status
        if (($so_obj = $this->sc['So']->getDao('So')->get(array("so_no" => $so_no))) === FALSE) {
            $_SESSION["NOTICE"] = $this->db->_error_message();
        } else {
            if (empty($so_obj)) {
                $_SESSION["NOTICE"] = "so_not_found";
            } else {
                # put order back to so.hold = 0
                $so_obj->setHoldStatus(0);

                if ($this->sc['So']->getDao('So')->update($so_obj)) {
                    $order_notes_obj = $this->sc['So']->getDao('OrderNotes')->get();
                    $order_notes_obj->setSoNo($so_no);
                    $order_notes_obj->setType("O");
                    $order_notes_obj->setNote("Hold status released.");

                    if (($this->sc['So']->getDao('OrderNotes')->insert($order_notes_obj)) === FALSE) {
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

    public function refund($so_no = "")
    {
        if (($so_obj = $this->sc['So']->getDao('So')->get(array("so_no" => $so_no))) === FALSE) {
            $_SESSION["NOTICE"] = $this->db->_error_message();
        } else {
            if (empty($so_obj)) {
                $_SESSION["NOTICE"] = "so_not_found";
            } else {
                if (!$this->sc['Refund']->createRefund($so_no)) {
                    $_SESSION["NOTICE"] = "failed_create_refund";
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


