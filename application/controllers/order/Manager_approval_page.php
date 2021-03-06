<?php

class Manager_approval_page extends MY_Controller
{

    private $appId = "ORD0013";
    private $lang_id = "en";


    public function __construct()
    {
        parent::__construct();
        $this->load->model('order/credit_check_model');
        $this->load->helper(array('url', 'notice', 'object', 'operator'));
        $this->load->library('service/pagination_service');
        $this->load->library('encrypt');
    }

    public function index($pmghold = 0)
    {
        $sub_app_id = $this->getAppId() . "01";

        $_SESSION["LISTPAGE"] = base_url() . "order/manager_approval_page/?" . $_SERVER['QUERY_STRING'];

        $where = array();
        $option = array();

        $where["so.hold_status"] = "2";
        $where["so.status"] = "2";
        //$where["(so.biz_type = 'ONLINE' OR so.biz_type = 'SKYPE' OR so.biz_type = 'EBAY')"] = NULL;

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

        //$data["objlist"] = $this->credit_check_model->so_service->get_dao()->get_credit_check_list($where, $option);
        $data["objlist"] = $this->credit_check_model->get_credit_check_list($where, $option, "cs");
        //$data["total"] = $this->credit_check_model->so_service->get_dao()->get_credit_check_list($where, array("num_rows"=>1));
        $data["total"] = $this->credit_check_model->get_credit_check_list_count($where, array("num_rows" => 1), "cs");

        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
        $data["lang"] = $lang;

        $pconfig['total_rows'] = $data['total'];
        $this->pagination_service->set_show_count_tag(TRUE);
        $this->pagination_service->initialize($pconfig);

        $data["notice"] = notice($lang);

        $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
        $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
//      $data["searchdisplay"] = ($submit_search)?"":'style="display:none"';
        $data["searchdisplay"] = "";
        $this->load->view('order/credit_check/manager_approval_v', $data);
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }

    public function chk_pw()
    {
        $password = $this->input->get("pw");
        if ($password) {
            $sub_app_id = $this->getAppId() . "00";

            $_SESSION["LISTPAGE"] = base_url() . "order/manager_approval_page/chk_pw/" . $password . "/?" . $_SERVER['QUERY_STRING'];

            $where = array();
            $option = array();

            $where["c.password"] = $password;
            $where["so.status"] = "1";

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

            $data["objlist"] = $this->credit_check_model->so_service->get_dao()->get_credit_check_list($where, $option);

            $data["total"] = $this->credit_check_model->so_service->get_dao()->get_credit_check_list($where, array("num_rows" => 1));

            include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
            $data["lang"] = $lang;

            $pconfig['total_rows'] = $data['total'];
            $this->pagination_service->set_show_count_tag(TRUE);
            $this->pagination_service->initialize($pconfig);

            $data["notice"] = notice($lang);

            $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
            $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
            //      $data["searchdisplay"] = ($submit_search)?"":'style="display:none"';
            $data["searchdisplay"] = "";
            $this->load->view('order/credit_check/credit_check_chk_pw_v', $data);
        }
    }

    public function delete($so_no = "")
    {
        if (($so_obj = $this->credit_check_model->get("dao", array("so_no" => $so_no))) === FALSE) {
            $_SESSION["NOTICE"] = $this->db->_error_message();
        } else {
            if (empty($so_obj)) {
                $_SESSION["NOTICE"] = "so_not_found";
            } else {
                $so_obj->set_status(0);
                if (!$this->credit_check_model->update("dao", $so_obj)) {
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

    public function approve($so_no = "")
    {
        if (($so_obj = $this->credit_check_model->get("dao", array("so_no" => $so_no))) === FALSE) {
            $_SESSION["NOTICE"] = $this->db->_error_message();
        } else {
            if (empty($so_obj)) {
                $_SESSION["NOTICE"] = "so_not_found";
            } else {
                $so_obj->set_status(3);
                $so_obj->set_hold_status(0);
                $so_obj->set_refund_status(0);
                if (!$this->credit_check_model->update("dao", $so_obj)) {
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

    public function hold($so_no = "")
    {
        if (($so_obj = $this->credit_check_model->get("dao", array("so_no" => $so_no))) === FALSE) {
            $_SESSION["NOTICE"] = $this->db->_error_message();
        } else {
            if (empty($so_obj)) {
                $_SESSION["NOTICE"] = "so_not_found";
            } else {
                $so_obj->set_hold_status(1);
                if ($this->credit_check_model->update("dao", $so_obj)) {
                    if (($sohr_vo = $this->credit_check_model->get("sohr_dao")) !== FALSE) {
                        $sohr_vo->set_so_no($so_no);
                        $sohr_vo->set_reason($this->input->post("reason"));
                        if (!$this->credit_check_model->add("sohr_dao", $sohr_vo)) {
                            $_SESSION["NOTICE"] = $this->db->_error_message();
                        }
                        if (in_array($this->input->post("reason"), array("cscc", "csvv"))) {
                            $this->credit_check_model->fire_cs_request($so_no, $this->input->post("reason"));
                        }
                        if ($this->input->post("reason") == "confirmed_fraud") {
                            $action = "update";
                            $socc_obj = $this->credit_check_model->so_service->get_socc_dao()->get(array("so_no" => $so_no));
                            if (!$socc_obj) {
                                $socc_obj = $this->credit_check_model->so_service->get_socc_dao()->get();
                                $action = "insert";
                            }
                            $this->credit_check_model->so_service->get_socc_dao()->trans_start();
                            $socc_obj->set_so_no($so_no);
                            $socc_obj->set_fd_status(2);
                            $this->credit_check_model->so_service->get_socc_dao()->$action($socc_obj);

                            $so_obj->set_status(0);
                            $so_obj->set_hold_status(0);
                            if (!$this->credit_check_model->update("dao", $so_obj)) {
                                $_SESSION["NOTICE"] = $this->db->_error_message();
                            }
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


