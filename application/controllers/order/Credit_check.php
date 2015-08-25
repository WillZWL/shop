<?php

class Credit_check extends MY_Controller
{

    private $appId = "ORD0002";
    private $lang_id = "en";


    public function __construct()
    {
        parent::__construct();
        $this->load->model('order/credit_check_model');
        $this->load->helper(array('url', 'notice', 'object', 'operator'));
        $this->load->library('service/pagination_service');
        $this->load->library('service/event_service');
        $this->load->library('service/delivery_option_service');
        $this->load->library('service/context_config_service');
        $this->load->library('service/platform_biz_var_service');
        $this->load->library('encrypt');
        $this->load->library('service/so_refund_score_service');
    }

    public function index($pagetype = "")
    {
        $sub_app_id = $this->getAppId() . "00";

        $_SESSION["CCLISTPAGE"] = base_url() . "order/credit_check/" . ($pagetype ? "index/" . $pagetype : "") . "?" . $_SERVER['QUERY_STRING'];
        $_SESSION["CC_QSTRING"] = $_SERVER['QUERY_STRING'];

        $where = array();
        $option = array();

        $where["so.hold_status"] = "0";

        $type = "";
        $sort = $this->input->get("sort");
        $order = $this->input->get("order");

        if ($pagetype == "pending") {
            $where["sops.payment_status"] = "P";
            $where["sops.payment_gateway_id"] = "moneybookers";
            $where["so.status"] = "1";
            $where["so.biz_type <> "] = "OFFLINE";
        } elseif ($pagetype == "challenged") {
            $where["sops.payment_status"] = "P";
            $where["sops.pending_action"] = "CC";
            $where["sops.payment_gateway_id"] = "global_collect";
            $where["so.status"] = "1";
            $where["so.biz_type <> "] = "OFFLINE";
        } elseif ($pagetype == "comcenter") {
            $where["so.hold_status"] = "1";
            $where["so.status >"] = "1";
            $where["so.status <"] = "6";
            $where["sohr.reason NOT LIKE"] = '%_log_app';
            $where["sohr.reason in ('cscc', 'csvv')"] = NULL;

            if (empty($sort)) {
                $sort = "order_create_date";
            }

            $type = "comcenter";
        } else {
            $where["so.status"] = "2";
            $where["so.biz_type <> "] = "SPECIAL";
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

        if ($this->input->get("currency_id") != "") {
            $where["so.currency_id"] = $this->input->get("currency_id");
            $submit_search = 1;
        }

        if ($this->input->get('cybs') != "") {
            $where["sor.risk_var1 LIKE"] = "%" . $this->input->get("cybs") . "%";
            $submit_search = 1;
        }

        $limit = '20';

        $pconfig['base_url'] = $_SESSION["CCLISTPAGE"];
        $option["limit"] = $pconfig['per_page'] = $limit;

        if ($option["limit"]) {
            $option["offset"] = $this->input->get("per_page");
        }

        if (empty($sort)) {
            $sort = "so_no";
        }

        if (empty($order)) {
            $order = "desc";
        }

        $option["orderby"] = $sort . " " . $order;

        //$data["objlist"] = $this->credit_check_model->so_service->get_dao()->get_credit_check_list($where, $option);
        $data["objlist"] = $this->credit_check_model->get_credit_check_list($where, $option, $type);

        //$data["total"] = $this->credit_check_model->so_service->get_dao()->get_credit_check_list($where, array("num_rows"=>1));
        $data["total"] = $this->credit_check_model->get_credit_check_list_count($where, array("num_rows" => 1), $type);

        $data["del_opt_list"] = end($this->delivery_option_service->get_list_w_key(array("lang_id" => "en")));
        $pmgw_card_list = $this->credit_check_model->get_pmgw_card_list();
        foreach ($pmgw_card_list as $card_obj) {
            $data["pmgw_card_list"][$card_obj->get_card_id()] = $card_obj->get_card_name();
        }

        if ($data["objlist"]) {
            include_once(APPPATH . "libraries/service/payment_gateway_redirect_cybersource_service.php");
            $cybs = new Payment_gateway_redirect_cybersource_service();

            foreach ($data["objlist"] AS $obj) {
                $temp = array();

                if ($pagetype == "comcenter") {
                    $note_list = $this->credit_check_model->get_order_note(array("so_no" => $obj->get_so_no(), "type" => "O"));
                    foreach ($note_list AS $note) {
                        $temp[] = $note->get_note() . ' (' . $note->get_create_on() . ')';
                    }
                } else {
                    $note_list = $this->credit_check_model->get_order_note(array("so_no" => $obj->get_so_no(), "type" => "C"));
                    foreach ($note_list AS $note) {
                        $temp[] = $note->get_note();
                    }
                }

                if ($obj->get_sor_obj()) {
                    $data["risk1"][$obj->get_so_no()] = $cybs->risk_indictor_risk1($obj->get_sor_obj()->get_risk_var1());
                    $data["risk2"][$obj->get_so_no()] = $cybs->risk_indictor_avs_risk2($obj->get_sor_obj()->get_risk_var2());
                    $data["risk3"][$obj->get_so_no()] = $cybs->risk_indictor_cvn_risk3($obj->get_sor_obj()->get_risk_var3());
                }
                $data["order_note"][$obj->get_so_no()] = implode('<br />', $temp);
            }
        }
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
        $data["lang"] = $lang;

        $pconfig['total_rows'] = $data['total'];
        $this->pagination_service->set_show_count_tag(TRUE);
        $this->pagination_service->initialize($pconfig);

        $data["notice"] = notice($lang);

        $data["pagetype"] = $pagetype;
        $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
        $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
//      $data["searchdisplay"] = ($submit_search)?"":'style="display:none"';
        $data["searchdisplay"] = "";
        $this->load->view('order/credit_check/credit_check_index_v', $data);
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

            $_SESSION["LISTPAGE"] = base_url() . "order/credit_check/chk_pw/" . $password . "/?" . $_SERVER['QUERY_STRING'];

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

        if (isset($_SESSION["CCLISTPAGE"])) {
            redirect($_SESSION["CCLISTPAGE"]);
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
                if (!$this->credit_check_model->update("dao", $so_obj)) {
                    $_SESSION["NOTICE"] = $this->db->_error_message();
                }
            }
        }
        if (isset($_SESSION["CCLISTPAGE"])) {
            redirect($_SESSION["CCLISTPAGE"]);
        } else {
            redirect(current_url());
        }
    }

    public function approve_challenged($so_no = "")
    {
        if (($sops_obj = $this->credit_check_model->so_service->get_sops_dao()->get(array("so_no" => $so_no))) === FALSE) {
            $_SESSION["NOTICE"] = $this->db->_error_message();
        } else {
            if (empty($sops_obj)) {
                $_SESSION["NOTICE"] = "so_not_found";
            } else {
                $sops_obj->set_pending_action('P');
                if (!$this->credit_check_model->so_service->get_sops_dao()->update($sops_obj)) {
                    $_SESSION["NOTICE"] = $this->db->_error_message();
                }
            }
        }
        if (isset($_SESSION["CCLISTPAGE"])) {
            redirect($_SESSION["CCLISTPAGE"]);
        } else {
            redirect(current_url());
        }
    }

    public function reject_challenged($so_no = "")
    {
        if (($sops_obj = $this->credit_check_model->so_service->get_sops_dao()->get(array("so_no" => $so_no))) === FALSE) {
            $_SESSION["NOTICE"] = $this->db->_error_message();
        } else {
            if (empty($sops_obj)) {
                $_SESSION["NOTICE"] = "so_not_found";
            } else {
                $sops_obj->set_pending_action('R');
                if (!$this->credit_check_model->so_service->get_sops_dao()->update($sops_obj)) {
                    $_SESSION["NOTICE"] = $this->db->_error_message();
                }
            }
        }
        if (isset($_SESSION["CCLISTPAGE"])) {
            redirect($_SESSION["CCLISTPAGE"]);
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
                if (in_array($this->input->post("reason"), array("cscc", "csvv", "confirmed_fraud"))) {
                    $so_obj->set_hold_status(1);
                } else {
                    $so_obj->set_hold_status(2);
                }

                if ($this->credit_check_model->update("dao", $so_obj)) {
                    if (($sohr_vo = $this->credit_check_model->get("sohr_dao")) !== FALSE) {
                        $sohr_vo->set_so_no($so_no);
                        $sohr_vo->set_reason($this->input->post("reason"));
                        if (!$this->credit_check_model->add("sohr_dao", $sohr_vo)) {
                            $_SESSION["NOTICE"] = $this->db->_error_message();
                        }
                        if (in_array($this->input->post("reason"), array("cscc", "csvv"))) {
                            //Commented out as requested by CS, send email only after manager reviewed
                            //$this->credit_check_model->fire_cs_request($so_no,$this->input->post("reason"));
                        }
                        if (in_array($this->input->post("reason"), array("change_of_address", "confirmation_required", "customer_request"))) {
                            $so_obj->set_hold_status(1);
                            if (!$this->credit_check_model->update("dao", $so_obj)) {
                                $_SESSION["NOTICE"] = $this->db->_error_message();
                            }
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
                            $this->credit_check_model->so_service->get_socc_dao()->trans_complete();
                        }
                    } else {
                        $_SESSION["NOTICE"] = $this->db->_error_message();
                    }
                } else {
                    $_SESSION["NOTICE"] = $this->db->_error_message();
                }
            }
        }
        if (isset($_SESSION["CCLISTPAGE"])) {
            redirect($_SESSION["CCLISTPAGE"]);
        } else {
            redirect(current_url());
        }
    }

    public function send_cc_reminder()
    {
        $where = array();
        $where["so.hold_status"] = "1";
        $where["so.status >"] = "1";
        $where["so.status <"] = "6";
        $where["cc_reminder_schedule_date <="] = date('Y-m-d H:i:s', time());

        if (($so_list = $this->credit_check_model->get_list("dao", $where, array('limit' => '-1'))) === FALSE) {
            $_SESSION["NOTICE"] = $this->db->_error_message();
        } else {
            foreach ($so_list as $so) {
                if ($socc_obj = $this->credit_check_model->get('socc_dao', array('so_no' => $so->get_so_no()))) {
                    // bit 1 is save order action
                    if ($socc_obj->get_cc_action() & 2) {
                        $not_send_orders[] = $so->get_so_no();
                        continue;
                    }
                }

                if (strtoupper($so->get_cc_reminder_type()) == 'HIGH_RISK_CC') {
                    $this->send_high_risk_cc_reminder($so);
                } elseif (strtoupper($so->get_cc_reminder_type()) == 'LOW_RISK_CC') {
                    $this->send_low_risk_cc_reminder($so);
                }

                $so->set_cc_reminder_type(NULL);
                $so->set_cc_reminder_schedule_date(NULL);
                if (!$this->credit_check_model->update("dao", $so)) {
                    $_SESSION["NOTICE"] = $this->db->_error_message();
                }
            }
        }
    }

    public function send_high_risk_cc_reminder($so_obj = NULL)
    {
        if (!empty($so_obj)) {
            // Send email
            $replace = array();
            $this->communication_center_email_common($so_obj, $replace, "high_risk_cc_reminder", "high_risk_cc_reminder");

            // Add notes
            $this->credit_check_model->add_order_note($so_obj->get_so_no(), 'High risk CC reminder sent');
        }
    }

    protected function communication_center_email_common($so_obj, $replace, $event_id, $tpl_id)
    {
        $pbv_obj = $this->platform_biz_var_service->get_platform_biz_var($so_obj->get_platform_id());
        if (($pbv_obj->get_platform_country_id() == '') || ($pbv_obj->get_language_id() == '')) {
            $lang_id = "en";
            $country_id = "US";
        } else {
            $lang_id = trim($pbv_obj->get_language_id());
            $country_id = trim($pbv_obj->get_platform_country_id());
        }
        include_once(APPPATH . "hooks/country_selection.php");
        $replace = array_merge($replace, Country_selection::get_template_require_text($lang_id, $country_id));

        $client = $this->credit_check_model->get_client(array("id" => $so_obj->get_client_id()));
        $replace["forename"] = $client->get_forename();
        $replace["so_no"] = $so_obj->get_so_no();
        $replace["client_id"] = $so_obj->get_client_id();

        if ($lang_id == "fr") {
            $from_email = "sophie@valuebasket.com";
        } else if ($lang_id == "es") {
            $from_email = "alicia@valuebasket.es";
        } else {
            $from_email = "agatha@valuebasket.com";
        }

        $this->credit_check_model->so_service->include_dto("Event_email_dto");
        $dto = new Event_email_dto();
        $dto->set_mail_from($from_email);
        $dto->set_mail_to($client->get_email());
        $dto->set_lang_id($lang_id);
        $dto->set_event_id($event_id);
        $dto->set_tpl_id($tpl_id);
        $dto->set_replace($replace);

        return $this->event_service->fire_event($dto);
    }

    public function send_low_risk_cc_reminder($so_obj = NULL)
    {
        if (!empty($so_obj)) {
            // Send email
            $replace = array();
            $this->communication_center_email_common($so_obj, $replace, "low_risk_cc_reminder", "low_risk_cc_reminder");

            // Add notes
            $this->credit_check_model->add_order_note($so_obj->get_so_no(), 'Low risk CC reminder sent');
        }
    }

    public function apr_fulfill($so_no = "")
    {
        if (($so_obj = $this->credit_check_model->get("dao", array("so_no" => $so_no))) === FALSE) {
            $_SESSION["NOTICE"] = $this->db->_error_message();
        } else {
            if (empty($so_obj)) {
                $_SESSION["NOTICE"] = "so_not_found";
            } else {
                // Send email
                $replace = array();
                $this->communication_center_email_common($so_obj, $replace, "apr_fulfill", "apr_fulfill");

                // Update SO
                $so_obj->set_cc_reminder_schedule_date(NULL);
                $so_obj->set_cc_reminder_type(NULL);
                $so_obj->set_hold_status(0);
                if ($so_obj->get_status() < 3) {
                    $so_obj->set_status(3);
                }

                if (!$this->credit_check_model->update("dao", $so_obj)) {
                    $_SESSION["NOTICE"] = $this->db->_error_message();
                }

                // Add notes
                $this->credit_check_model->add_order_note($so_no, 'Approved order with email confirmation');
            }
        }
        if (isset($_SESSION["CCLISTPAGE"])) {
            redirect($_SESSION["CCLISTPAGE"]);
        } else {
            redirect(current_url());
        }
    }

    public function further_check($so_no = "")
    {
        if (($so_obj = $this->credit_check_model->get("dao", array("so_no" => $so_no))) === FALSE) {
            $_SESSION["NOTICE"] = $this->db->_error_message();
        } else {
            if (empty($so_obj)) {
                $_SESSION["NOTICE"] = "so_not_found";
            } else {
                // Send email
                $replace = array();
                $this->communication_center_email_common($so_obj, $replace, "further_check", "further_check");

                // Update SO
                if (!is_null($so_obj->get_cc_reminder_schedule_date())) {
                    $so_obj->set_cc_reminder_schedule_date(NULL);
                    $so_obj->set_cc_reminder_type(NULL);
                    if (!$this->credit_check_model->update("dao", $so_obj)) {
                        $_SESSION["NOTICE"] = $this->db->_error_message();
                    }
                }

                // Add notes
                $this->credit_check_model->add_order_note($so_no, 'Further check email sent');
            }
        }
        if (isset($_SESSION["CCLISTPAGE"])) {
            redirect($_SESSION["CCLISTPAGE"]);
        } else {
            redirect(current_url());
        }
    }

    public function failcc_refund($so_no = "")
    {
        if (($so_obj = $this->credit_check_model->get("dao", array("so_no" => $so_no))) === FALSE) {
            $_SESSION["NOTICE"] = $this->db->_error_message();
        } else {
            if (empty($so_obj)) {
                $_SESSION["NOTICE"] = "so_not_found";
            } else {
                // Send email
                $replace = array();
                $this->communication_center_email_common($so_obj, $replace, "failcc_refund", "failcc_refund");

                // Update SO
                if (!is_null($so_obj->get_cc_reminder_schedule_date())) {
                    $so_obj->set_cc_reminder_schedule_date(NULL);
                    $so_obj->set_cc_reminder_type(NULL);
                    if (!$this->credit_check_model->update("dao", $so_obj)) {
                        $_SESSION["NOTICE"] = $this->db->_error_message();
                    }
                }

                // Create refund
                $refund_parameter = array();
                $refund_parameter['refund_reason_description'] = 'Failed Credit Check';
                if (!$this->credit_check_model->create_refund_from_communication_center($so_no, $refund_parameter)) {
                    $_SESSION["NOTICE"] = "failed_create_refund";
                }

                // Add notes
                $this->credit_check_model->add_order_note($so_no, 'Fail CC email send');
                //SBF #2607 add the default refund score when order funded
                $this->so_refund_score_service->insert_initial_refund_score($so_no);

            }
        }
        if (isset($_SESSION["CCLISTPAGE"])) {
            redirect($_SESSION["CCLISTPAGE"]);
        } else {
            redirect(current_url());
        }
    }

    public function refuse_cc_refund($so_no = "")
    {
        if (($so_obj = $this->credit_check_model->get("dao", array("so_no" => $so_no))) === FALSE) {
            $_SESSION["NOTICE"] = $this->db->_error_message();
        } else {
            if (empty($so_obj)) {
                $_SESSION["NOTICE"] = "so_not_found";
            } else {
                // Update SO
                if (!is_null($so_obj->get_cc_reminder_schedule_date())) {
                    $so_obj->set_cc_reminder_schedule_date(NULL);
                    $so_obj->set_cc_reminder_type(NULL);
                    if (!$this->credit_check_model->update("dao", $so_obj)) {
                        $_SESSION["NOTICE"] = $this->db->_error_message();
                    }
                }

                // Create refund
                $refund_parameter = array();
                $refund_parameter['refund_reason_description'] = 'Refused Credit Check';
                if (!$this->credit_check_model->create_refund_from_communication_center($so_no, $refund_parameter)) {
                    $_SESSION["NOTICE"] = "refuse_cc_refund";
                }

                // Add notes
                $this->credit_check_model->add_order_note($so_no, 'Refuse CC refund');
                //SBF #2607 add the default refund score when order funded
                $this->so_refund_score_service->insert_initial_refund_score($so_no);
            }
        }
        if (isset($_SESSION["CCLISTPAGE"])) {
            redirect($_SESSION["CCLISTPAGE"]);
        } else {
            redirect(current_url());
        }
    }

    public function confirmed_fraud($so_no = "")
    {
        if (($so_obj = $this->credit_check_model->get("dao", array("so_no" => $so_no))) === FALSE) {
            $_SESSION["NOTICE"] = $this->db->_error_message();
        } else {
            if (empty($so_obj)) {
                $_SESSION["NOTICE"] = "so_not_found";
            } else {
                if (($sohr_vo = $this->credit_check_model->get("sohr_dao")) !== FALSE) {
                    $sohr_vo->set_so_no($so_no);
                    $sohr_vo->set_reason("confirmed_fraud");
                    if (!$this->credit_check_model->add("sohr_dao", $sohr_vo)) {
                        $_SESSION["NOTICE"] = $this->db->_error_message();
                    }

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
                    if (!is_null($so_obj->get_cc_reminder_schedule_date())) {
                        $so_obj->set_cc_reminder_schedule_date(NULL);
                        $so_obj->set_cc_reminder_type(NULL);
                    }

                    if (!$this->credit_check_model->update("dao", $so_obj)) {
                        $_SESSION["NOTICE"] = $this->db->_error_message();
                    }
                    $this->credit_check_model->so_service->get_socc_dao()->trans_complete();
                }
            }
        }
        if (isset($_SESSION["CCLISTPAGE"])) {
            redirect($_SESSION["CCLISTPAGE"]);
        } else {
            redirect(current_url());
        }
    }

    public function high_risk_cc($so_no = "")
    {
        if (($so_obj = $this->credit_check_model->get("dao", array("so_no" => $so_no))) === FALSE) {
            $_SESSION["NOTICE"] = $this->db->_error_message();
        } else {
            if (empty($so_obj)) {
                $_SESSION["NOTICE"] = "so_not_found";
            } else {
                // Send email
                $replace = array();
                $replace['order_create_date'] = date("d/m/Y", strtotime($so_obj->get_order_create_date()));
                $list = $this->credit_check_model->so_service->get_soi_dao()->get_items_w_name(array("so_no" => $so_no));
                $item_list = array();
                foreach ($list as $obj) {
                    $item_list[] = "- " . $obj->get_name();
                }
                $replace["item_list_html"] = implode("<br>", $item_list);
                $replace["item_list_text"] = implode("\n", $item_list);

                $this->communication_center_email_common($so_obj, $replace, "high_risk_cc", "high_risk_cc");

                // Update SO
                $added_day = 0;
                $schedule_day = 2;  // Schedule 2 days after (not include Sat & Sun)
                $schedule_ts = time();

                while ($schedule_day > $added_day) {
                    $schedule_ts += 86400;

                    if (!in_array(date("l", $schedule_ts), array("Saturday", "Sunday"))) {
                        $added_day++;
                    }
                }
                $schedule_date = date('Y-m-d H:i:s', $schedule_ts);

                $so_obj->set_cc_reminder_schedule_date($schedule_date);
                $so_obj->set_cc_reminder_type('high_risk_cc');
                if (!$this->credit_check_model->update("dao", $so_obj)) {
                    $_SESSION["NOTICE"] = $this->db->_error_message();
                }

                // Add notes
                $this->credit_check_model->add_order_note($so_no, 'High risk CC email sent');
            }
        }
        if (isset($_SESSION["CCLISTPAGE"])) {
            redirect($_SESSION["CCLISTPAGE"]);
        } else {
            redirect(current_url());
        }
    }

    public function low_risk_cc($so_no = "")
    {
        if (($so_obj = $this->credit_check_model->get("dao", array("so_no" => $so_no))) === FALSE) {
            $_SESSION["NOTICE"] = $this->db->_error_message();
        } else {
            if (empty($so_obj)) {
                $_SESSION["NOTICE"] = "so_not_found";
            } else {
                // Send email
                $replace = array();
                $replace['order_create_date'] = date("d/m/Y", strtotime($so_obj->get_order_create_date()));

                $list = $this->credit_check_model->so_service->get_soi_dao()->get_items_w_name(array("so_no" => $so_no));
                $item_list = array();
                foreach ($list as $obj) {
                    $item_list[] = "- " . $obj->get_name();
                }
                $replace["item_list_html"] = implode("<br>", $item_list);
                $replace["item_list_text"] = implode("\n", $item_list);

                $this->communication_center_email_common($so_obj, $replace, "low_risk_cc", "low_risk_cc");

                // Update SO
                $added_day = 0;
                $schedule_day = 2;  // Schedule 2 days after (not include Sat & Sun)
                $schedule_ts = time();

                while ($schedule_day > $added_day) {
                    $schedule_ts += 86400;

                    if (!in_array(date("l", $schedule_ts), array("Saturday", "Sunday"))) {
                        $added_day++;
                    }
                }
                $schedule_date = date('Y-m-d H:i:s', $schedule_ts);

                $so_obj->set_cc_reminder_schedule_date($schedule_date);
                $so_obj->set_cc_reminder_type('low_risk_cc');
                if (!$this->credit_check_model->update("dao", $so_obj)) {
                    $_SESSION["NOTICE"] = $this->db->_error_message();
                }

                // Add notes
                $this->credit_check_model->add_order_note($so_no, 'Low risk CC email sent');
            }
        }
        if (isset($_SESSION["CCLISTPAGE"])) {
            redirect($_SESSION["CCLISTPAGE"]);
        } else {
            redirect(current_url());
        }
    }

    public function operation_integrator()
    {
        $operation = $this->input->post('operation');
        if (!$operation)
            redirect($_SESSION["CCLISTPAGE"]);

        $reason = $this->input->post('reason');
        /*
        $operation = array(
                232453 => 'approve',
                232429 => 'hold',
                232428 => 'delete');
        ** $reason contains ALL the so_no's corresponding hold data, no matter that so_no order is selected or not.
        $reason = array(
                232453 => 'change_of_address',
                232429 => 'confirmation_required',
                232428 => 'confirmed_fraud');
        */
        foreach ($operation as $so_no => $operation_type) {
            if (!$this->all_in_one($so_no, $operation_type, $reason[$so_no])) {
                if (isset($_SESSION["CCLISTPAGE"])) {
                    redirect($_SESSION["CCLISTPAGE"]);
                } else {
                    redirect(current_url());
                }
            }
        }

        if (isset($_SESSION["CCLISTPAGE"])) {
            redirect($_SESSION["CCLISTPAGE"]);
        } else {
            redirect(current_url());
        }
    }

    #2309
    //this function will handle 'Delete', 'Approve' and 'Hold' operations, it's the integration of the above three function, but still keep the above functions.
    //also it can handle multiple orders at one time

    public function all_in_one($so_no, $operation_type, $reason)
    {
        if ($operation_type == 'delete') {
            if (($so_obj = $this->credit_check_model->get("dao", array("so_no" => $so_no))) === FALSE) {
                $_SESSION["NOTICE"] = $this->db->_error_message();
            } else {
                if (empty($so_obj)) {
                    $_SESSION["NOTICE"] = "$so_no order not found";
                } else {
                    $so_obj->set_status(0);
                    if (!$this->credit_check_model->update("dao", $so_obj)) {
                        $_SESSION["NOTICE"] = $this->db->_error_message();
                    }
                }
            }
        }

        if ($operation_type == 'approve') {
            if (($so_obj = $this->credit_check_model->get("dao", array("so_no" => $so_no))) === FALSE) {
                $_SESSION["NOTICE"] = $this->db->_error_message();
            } else {
                if (empty($so_obj)) {
                    $_SESSION["NOTICE"] = "$so_no order not found";
                } else {
                    $so_obj->set_status(3);
                    if (!$this->credit_check_model->update("dao", $so_obj)) {
                        $_SESSION["NOTICE"] = $this->db->_error_message();
                    }
                }
            }
        }

        if ($operation_type == 'hold') {
            if (($so_obj = $this->credit_check_model->get("dao", array("so_no" => $so_no))) === FALSE) {
                $_SESSION["NOTICE"] = $this->db->_error_message();
            } else {
                if (empty($so_obj)) {
                    $_SESSION["NOTICE"] = "$so_no order not found";
                } else {
                    if (in_array($reason, array("cscc", "csvv", "confirmed_fraud"))) {
                        $so_obj->set_hold_status(1);
                    } else {
                        $so_obj->set_hold_status(2);
                    }

                    if ($this->credit_check_model->update("dao", $so_obj)) {
                        if (($sohr_vo = $this->credit_check_model->get("sohr_dao")) !== FALSE) {
                            $sohr_vo->set_so_no($so_no);
                            $sohr_vo->set_reason($reason);
                            if (!$this->credit_check_model->add("sohr_dao", $sohr_vo)) {
                                $_SESSION["NOTICE"] = $this->db->_error_message();
                            }
                            if (in_array($reason, array("cscc", "csvv"))) {
                                //Commented out as requested by CS, send email only after manager reviewed
                                //$this->credit_check_model->fire_cs_request($so_no,$this->input->post("reason"));
                            }
                            if (in_array($reason, array("change_of_address", "confirmation_required", "customer_request"))) {
                                $so_obj->set_hold_status(1);
                                if (!$this->credit_check_model->update("dao", $so_obj)) {
                                    $_SESSION["NOTICE"] = $this->db->_error_message();
                                }
                            }
                            if ($reason == "confirmed_fraud") {
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
                                $this->credit_check_model->so_service->get_socc_dao()->trans_complete();
                            }
                        } else {
                            $_SESSION["NOTICE"] = $this->db->_error_message();
                        }
                    } else {
                        $_SESSION["NOTICE"] = $this->db->_error_message();
                    }
                }
            }
        }

        if ($_SESSION["NOTICE"])
            return false;
        else
            return true;
    }

    //#2309 this function handle delete, approve and hold requests all together

    function bulk_update_post()
    {
        if (trim($this->input->post("order_list")) == "") {
            header("Location: /order/credit_check/bulk_update");
        } else {
            // var_dump($_POST);
            $order_list = explode("\n", $this->input->post("order_list"));

            $order_list = array_filter($order_list);
            // var_dump($order_list);

            $note = $this->input->post("note");
            foreach ($order_list as $k => $so_no) {
                if ($this->input->post("approve_if_paid") == 1) {
                    if ($this->approve_if_paid($so_no)) {
                        echo "SUCCESS: SO#$so_no marked as credit checked";
                        if ($note <> "") {
                            $this->credit_check_model->add_order_note($so_no, $note);
                            echo ", added note";
                        } else
                            echo ", blank note note added";

                        echo "<br>\r\n";
                    } else
                        echo "FAILED: to mark SO#$so_no as credit checked, note not added<br>\r\n";
                } else {
                    if ($note <> "") {
                        $this->credit_check_model->add_order_note($so_no, $note);
                        echo "SUCCESS: Added note to SO#$so_no<br>\r\n";
                    } else
                        echo "FAILED: Blank note given, no action on SO#$so_no<br>\r\n";
                }
            }
        }
        die();
    }

    public function approve_if_paid($so_no = "")
    {
        if (($so_obj = $this->credit_check_model->get("dao", array("so_no" => $so_no))) === FALSE) {
            $_SESSION["NOTICE"] = $this->db->_error_message();
        } else {
            if (empty($so_obj)) {
                $_SESSION["NOTICE"] = "so_not_found";
            } else {
                if ($so_obj->get_status() == 2) {
                    $so_obj->set_status(3);
                    if (!$this->credit_check_model->update("dao", $so_obj))
                        $_SESSION["NOTICE"] = $this->db->_error_message();

                    return true;
                }
            }
        }
        return false;
    }

    function bulk_update()
    {
        $data["title"] = "Bulk update";

        $langfile = $this->getAppId() . "01_" . $this->_get_lang_id() . ".php";
        include_once APPPATH . "language/" . $langfile;
        $data["lang"] = $lang;

        $this->load->view('order/credit_check/bulk_update', $data);
    }

}


