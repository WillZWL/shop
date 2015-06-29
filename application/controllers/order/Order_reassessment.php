<?php

class Order_reassessment extends MY_Controller
{

    private $app_id = "ORD0014";
    private $lang_id = "en";


    public function __construct()
    {
        parent::__construct();
        $this->load->model('order/credit_check_model');
        $this->load->helper(array('html', 'url', 'notice', 'object', 'operator'));
        $this->load->library('service/pagination_service');
        $this->load->library('encrypt');
        $this->load->library('dao/so_hold_reason_dao');
        $this->load->library('dao/order_notes_dao');
    }

    public function index($pmghold = 0)
    {
        $sub_app_id = $this->_get_app_id() . "01";

        $_SESSION["LISTPAGE"] = base_url() . "order/order_reassessment/?" . $_SERVER['QUERY_STRING'];

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

        if ($this->input->post("reason_list") !== false) {
            $data["reason_list_id"] = $this->input->post("reason_list");
            if ($data["reason_list_id"] != "") {
                $str = "sohr.reason = '{$data["reason_list_id"]}'";
                // var_dump($str);
                $where[$str] = NULL;
            }
        }

        $sort = $this->input->post("sort");
        $order = $this->input->post("order");

        $limit = '20';

        $pconfig['base_url'] = $_SESSION["LISTPAGE"];
        $option["limit"] = $pconfig['per_page'] = $limit;

        if ($option["limit"]) {
            $option["offset"] = $this->input->post("per_page");
        }

        if (empty($sort))
            $sort = "so_no";

        if (empty($order))
            $order = "desc";

        $option["orderby"] = $sort . " " . $order;

        if ($this->input->post("type") !== false) {
            //$data["objlist"] = $this->credit_check_model->so_service->get_dao()->post_credit_check_list($where, $option);
            $data["objlist"] = $this->credit_check_model->get_credit_check_list($where, $option, "ora");

            //$data["total"] = $this->credit_check_model->so_service->post_dao()->post_credit_check_list($where, array("num_rows"=>1));
            $data["total"] = $this->credit_check_model->get_credit_check_list_count($where, array("num_rows" => 1), "ora");
        }

        $data["reason_list"] = $this->so_hold_reason_dao->get_reason_list();

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
        $this->load->view('order/credit_check/order_reassessment_v', $data);
    }

    public function _get_app_id()
    {
        return $this->app_id;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }

    public function chk_pw()
    {
        $password = $this->input->get("pw");
        if ($password) {

            $sub_app_id = $this->_get_app_id() . "00";

            $_SESSION["LISTPAGE"] = base_url() . "order/order_reassessment/chk_pw/" . $password . "/?" . $_SERVER['QUERY_STRING'];

            $where = array();
            $option = array();

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

    public function approve($so_no = "")
    {
        if (($so_obj = $this->credit_check_model->get("dao", array("so_no" => $so_no))) === FALSE) {
            $_SESSION["NOTICE"] = $this->db->_error_message();
        } else {
            if (empty($so_obj)) {
                $_SESSION["NOTICE"] = "so_not_found";
            } else {
                $action = "update";
                $socc_obj = $this->credit_check_model->so_service->get_socc_dao()->get(array("so_no" => $so_no));
                if (!$socc_obj) {
                    $socc_obj = $this->credit_check_model->so_service->get_socc_dao()->get();
                    $action = "insert";
                }
                $this->credit_check_model->so_service->get_socc_dao()->trans_start();
                $socc_obj->set_so_no($so_no);
                $socc_obj->set_fd_status(0);
                $this->credit_check_model->so_service->get_socc_dao()->$action($socc_obj);

                $so_obj->set_status(3);
                $so_obj->set_hold_status(0);
                $so_obj->set_refund_status(0);
                if (!$this->credit_check_model->update("dao", $so_obj)) {
                    $_SESSION["NOTICE"] = $this->db->_error_message();
                }
                $this->credit_check_model->so_service->get_socc_dao()->trans_complete();
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
                        $sohr_vo->set_reason($this->input->post("reason"));
                        $sohr_vo->set_so_no($so_no);

                        if ($this->credit_check_model->add("sohr_dao", $sohr_vo) === FALSE) {
                            $_SESSION["NOTICE"] = $this->db->_error_message();
                        }
                        if (in_array($this->input->post("reason"), array("cscc", "csvv"))) {
                            $this->credit_check_model->fire_cs_request($so_no, $this->input->post("reason"));
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
        if (($so_obj = $this->credit_check_model->get("dao", array("so_no" => $so_no))) === FALSE) {
            $_SESSION["NOTICE"] = $this->db->_error_message();
        } else {
            if (empty($so_obj)) {
                $_SESSION["NOTICE"] = "so_not_found";
            } else {
                # put order back to so.hold = 0
                $so_obj->set_hold_status(0);

                if ($this->credit_check_model->update("dao", $so_obj)) {
                    $order_notes_obj = $this->order_notes_dao->get();
                    $order_notes_obj->set_so_no($so_no);
                    $order_notes_obj->set_type("O");
                    $order_notes_obj->set_note("Hold status released.");

                    if (($this->order_notes_dao->insert($order_notes_obj)) === FALSE) {
                        $_SESSION["NOTICE"] = $this->db->_error_message();
                    }

                    // if (($sohr_vo = $this->credit_check_model->get("sohr_dao")) !== FALSE)
                    // {
                    //  $sohr_vo->set_reason($this->input->post("reason")." - hold status released");
                    //  $sohr_vo->set_so_no($so_no);

                    //  if ($this->credit_check_model->add("sohr_dao", $sohr_vo) === FALSE)
                    //  {
                    //      $_SESSION["NOTICE"] = $this->db->_error_message();
                    //  }
                    // }
                    // else
                    // {
                    //  $_SESSION["NOTICE"] = $this->db->_error_message();
                    // }
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
        if (($so_obj = $this->credit_check_model->get("dao", array("so_no" => $so_no))) === FALSE) {
            $_SESSION["NOTICE"] = $this->db->_error_message();
        } else {
            if (empty($so_obj)) {
                $_SESSION["NOTICE"] = "so_not_found";
            } else {
                if (!$this->credit_check_model->create_refund($so_no)) {
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

/* End of file supplier.php */
/* Location: ./system/application/controllers/supply/supplier.php */