<?php


use ESG\Panther\Service\PaginationService;

class Credit_check extends MY_Controller
{

    private $appId = "ORD0002";
    private $lang_id = "en";


    public function __construct()
    {
        parent::__construct();

        $this->paginationService = new PaginationService;
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
            $where["so.hold_reason NOT LIKE"] = '%_log_app';
            $where["so.hold_reason in ('cscc', 'csvv')"] = NULL;

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

        if ($this->input->get("currency_id") != "") {
            $where["so.currency_id"] = $this->input->get("currency_id");
            $submit_search = 1;
        }

        if ($this->input->get('cybs') != "") {
            $where["sor.risk_var_1 LIKE"] = "%" . $this->input->get("cybs") . "%";
            $submit_search = 1;
        }

        $option['limit'] = ($this->input->get('limit') != '') ? $this->input->get('limit') : '20';
        $option['offset'] = ($this->input->get('per_page') != '') ? $this->input->get('per_page') : '';


        if (empty($sort)) {
            $sort = "so_no";
        }

        if (empty($order)) {
            $order = "desc";
        }

        $option["orderby"] = $sort . " " . $order;

        //$data["objlist"] = $this->sc['creditCheckModel']->so_service->get_dao()->get_credit_check_list($where, $option);
        $data["objlist"] = $this->sc['So']->getCreditCheckList($where, $option, $type);

        //$data["total"] = $this->sc['creditCheckModel']->so_service->get_dao()->get_credit_check_list($where, array("num_rows"=>1));
        $data["total"] = $this->sc['So']->getDao('So')->getCreditCheckList($where, array("num_rows" => 1), $type);

        $data["del_opt_list"] = end($this->sc['DeliveryOption']->getListWithKey(array("lang_id" => "en")));
        $pmgw_card_list = $this->sc['creditCheckModel']->getPmgwCardList();
        foreach ($pmgw_card_list as $card_obj) {
            $data["pmgw_card_list"][$card_obj->getCardId()] = $card_obj->getCardName();
        }

        if ($data["objlist"]) {
            //include_once(APPPATH . "libraries/service/Payment_gateway_redirect_cybersource_service.php");
            $cybs = $this->sc["PaymentGatewayRedirectCybersource"];

            foreach ($data["objlist"] AS $obj) {
                $temp = array();

                if ($pagetype == "comcenter") {
                    $note_list = $this->sc['So']->getDao('OrderNotes')->getList(array("so_no" => $obj->getSoNo(), "type" => "O"));
                    foreach ($note_list AS $note) {
                        $temp[] = $note->getNote() . ' (' . $note->getCreateOn() . ')';
                    }
                } else {
                    $note_list = $this->sc['So']->getDao('OrderNotes')->getList(array("so_no" => $obj->getSoNo(), "type" => "C"));
                    foreach ($note_list AS $note) {
                        $temp[] = $note->getNote();
                    }
                }

                if ($obj->getSorObj()) {
                    $data["risk1"][$obj->getSoNo()] = $cybs->riskIndictorRisk1($obj->getSorObj()->getRiskVar1());
                    $data["risk2"][$obj->getSoNo()] = $cybs->riskIndictorAvsRisk2($obj->getSorObj()->getRiskVar2());
                    $data["risk3"][$obj->getSoNo()] = $cybs->riskIndictorCvnRisk3($obj->getSorObj()->getRiskVar3());
                }
                $data["order_note"][$obj->getSoNo()] = implode('<br />', $temp);
            }
        }

        $r_where = [];
        $r_where["reason_type in (
            'change_of_address',
            'confirmation_required',
            'customer_request',
            'oc_fraud',
            'csvv',
            'cscc'
        ) and reason_cat != 'OT' "] = null;

        $data["reason_list"] = $this->sc['So']->getDao('HoldReason')->getList(array_merge($r_where,['status'=>1]), ['orderby'=>'reason_cat asc, description asc', 'limit'=>-1]);


        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
        $data["lang"] = $lang;

        $pconfig['base_url'] = $_SESSION["CCLISTPAGE"];
        $config['total_rows'] = $data["total"];
        $config['page_query_string'] = true;
        $config['reuse_query_string'] = true;
        $config['per_page'] = $option['limit'];
        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();

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

            $data["objlist"] = $this->sc['So']->getDao('So')->getCreditCheckList($where, $option);
            $data["total"] = $this->sc['So']->getDao('So')->getCreditCheckList($where, array("num_rows" => 1));

            include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
            $data["lang"] = $lang;

            $pconfig['total_rows'] = $data['total'];
            $this->paginationService->setShowCountTag(TRUE);
            $this->paginationService->initialize($pconfig);

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
        if (($so_obj = $this->sc['So']->getDao('So')->get(array("so_no" => $so_no))) === FALSE) {
            $_SESSION["NOTICE"] = $this->db->_error_message();
        } else {
            if (empty($so_obj)) {
                $_SESSION["NOTICE"] = "so_not_found";
            } else {
                $so_obj->setStatus(0);
                if (!$this->sc['So']->getDao('So')->update($so_obj)) {
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
        if (($so_obj = $this->sc['So']->getDao('So')->get(array("so_no" => $so_no))) === FALSE) {
            $_SESSION["NOTICE"] = $this->db->_error_message();
        } else {
            if (empty($so_obj)) {
                $_SESSION["NOTICE"] = "so_not_found";
            } else {
                $so_obj->setStatus(3);
                if (!$this->sc['So']->getDao('So')->update($so_obj)) {
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

        if (($sops_obj = $this->sc['So']->getDao('SoPaymentStatus')->get(array("so_no" => $so_no))) === FALSE) {
            $_SESSION["NOTICE"] = $this->db->_error_message();
        } else {
            if (empty($sops_obj)) {
                $_SESSION["NOTICE"] = "so_not_found";
            } else {
                $sops_obj->setPendingAction('P');
                if (!$this->sc['So']->getDao('SoPaymentStatus')->update($sops_obj)) {
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
        if (($sops_obj = $this->sc['So']->getDao('SoPaymentStatus')->get(array("so_no" => $so_no))) === FALSE) {
            $_SESSION["NOTICE"] = $this->db->_error_message();
        } else {
            if (empty($sops_obj)) {
                $_SESSION["NOTICE"] = "so_not_found";
            } else {
                $sops_obj->setPendingAction('R');
                if (!$this->sc['So']->getDao('SoPaymentStatus')->update($sops_obj)) {
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
        if (($so_obj = $this->sc['So']->getDao('So')->get(array("so_no" => $so_no))) === FALSE) {
            $_SESSION["NOTICE"] = $this->db->_error_message();
        } else {
            if (empty($so_obj)) {
                $_SESSION["NOTICE"] = "so_not_found";
            } else {
                if (in_array($this->input->post("reason"), array("cscc", "csvv", "oc_fraud"))) {
                    $so_obj->setHoldStatus(1);
                    // $so_obj->setHoldReason($this->input->post("reason"));
                } else {
                    $so_obj->setHoldStatus(2);
                    // $so_obj->setHoldReason($this->input->post("reason"));
                }

                if ($this->sc['So']->getDao('So')->update($so_obj)) {
                    if (($sohr_vo = $this->sc['So']->getDao('SoHoldReason')->get()) !== FALSE) {
                        $sohr_vo->setSoNo($so_no);
                        $sohr_vo->setReason($this->input->post("reason"));
                        if (!$this->sc['So']->getDao('SoHoldReason')->insert($sohr_vo)) {
                            $_SESSION["NOTICE"] = $this->db->_error_message();
                        }
                        if (in_array($this->input->post("reason"), array("cscc", "csvv"))) {
                            //Commented out as requested by CS, send email only after manager reviewed
                            //$this->sc['creditCheckModel']->fire_cs_request($so_no,$this->input->post("reason"));
                        }
                        if (in_array($this->input->post("reason"), array("change_of_address", "confirmation_required", "customer_request"))) {
                            $so_obj->setHoldStatus(1);
                            $so_obj->setHoldReason($this->input->post("reason"));
                            if (!$this->sc['So']->getDao('So')->update($so_obj)) {
                                $_SESSION["NOTICE"] = $this->db->_error_message();
                            }
                        }
                        if ($this->input->post("reason") == "oc_fraud") {
                            $action = "update";
                            $socc_obj = $this->sc['So']->getDao('SoCreditChk')->get(array("so_no" => $so_no));
                            if (!$socc_obj) {
                                $socc_obj = $this->sc['So']->getDao('SoCreditChk')->get();
                                $action = "insert";
                            }
                            $this->sc['So']->getDao('SoCreditChk')->db->trans_start();
                            $socc_obj->setSoNo($so_no);
                            $socc_obj->setFdStatus(2);
                            $this->sc['So']->getDao('SoCreditChk')->$action($socc_obj);

                            $so_obj->setStatus(0);
                            $so_obj->setHoldStatus(0);
                            if (!$this->sc['So']->getDao('So')->update($so_obj)) {
                                $_SESSION["NOTICE"] = $this->db->_error_message();
                            }
                            $this->sc['So']->getDao('SoCreditChk')->db->trans_complete();
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

        if (($so_list = $this->sc['So']->getDao('So')->getList($where, array('limit' => '-1'))) === FALSE) {
            $_SESSION["NOTICE"] = $this->db->_error_message();
        } else {
            foreach ($so_list as $so) {
                if ($socc_obj = $this->sc['So']->getDao('SoCreditChk')->get(array('so_no' => $so->getSoNo()))) {
                    // bit 1 is save order action
                    if ($socc_obj->getCcAction() & 2) {
                        $not_send_orders[] = $so->getSoNo();
                        continue;
                    }
                }

                if (strtoupper($so->getCcReminderType()) == 'HIGH_RISK_CC') {
                    $this->send_high_risk_cc_reminder($so);
                } elseif (strtoupper($so->getCcReminderType()) == 'LOW_RISK_CC') {
                    $this->send_low_risk_cc_reminder($so);
                }

                $so->setCcReminderType(NULL);
                $so->setCcReminderScheduleDate(NULL);
                if (!$this->sc['So']->getDao('So')->update($so)) {
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
            $this->sc['creditCheckModel']->addOrderNote($so_obj->getSoNo(), 'High risk CC reminder sent');
        }
    }

    protected function communication_center_email_common($so_obj, $replace, $event_id, $tpl_id)
    {
        $pbv_obj = $this->sc['PlatformBizVar']->getPlatformBizVar($so_obj->getPlatformId());
        if (($pbv_obj->getPlatformCountryId() == '') || ($pbv_obj->getLanguageId() == '')) {
            $lang_id = "en";
        } else {
            $lang_id = trim($pbv_obj->getLanguageId());
        }

        $cur_platform_id = $so_obj->getPlatformId();

        if ($site_config_arr = $this->sc['So']->getSiteConfig($cur_platform_id)) {
            $replace = array_merge($replace, $site_config_arr);
        }

        $client = $this->sc['Client']->getDao('Client')->get(array("id" => $so_obj->getClientId()));
        $replace["forename"] = $client->getForename();
        $replace["so_no"] = $so_obj->getSoNo();
        $replace["client_id"] = $so_obj->getClientId();

        //TO DO
        $from_email = "no-reply@digitaldiscount.co.uk";

        $dto = new \EventEmailDto();

        $dto->setPlatformId($cur_platform_id);
        $dto->setMailFrom($from_email);
        $dto->setMailTo($client->getEmail());
        $dto->setLangId($lang_id);
        $dto->setEventId($event_id);
        $dto->setTplId($tpl_id);
        $dto->setReplace($replace);

        return $this->sc['Event']->fireEvent($dto);
    }

    public function send_low_risk_cc_reminder($so_obj = NULL)
    {
        if (!empty($so_obj)) {
            // Send email
            $replace = array();
            $this->communication_center_email_common($so_obj, $replace, "low_risk_cc_reminder", "low_risk_cc_reminder");

            // Add notes
            $this->sc['creditCheckModel']->addOrderNote($so_obj->getSoNo(), 'Low risk CC reminder sent');
        }
    }

    public function apr_fulfill($so_no = "")
    {
        if (($so_obj = $this->sc['So']->getDao('So')->get(array("so_no" => $so_no))) === FALSE) {
            $_SESSION["NOTICE"] = $this->db->_error_message();
        } else {
            if (empty($so_obj)) {
                $_SESSION["NOTICE"] = "so_not_found";
            } else {
                // Send email
                $replace = array();
                $this->communication_center_email_common($so_obj, $replace, "apr_fulfill", "apr_fulfill");

                // Update SO
                $so_obj->setCcReminderScheduleDate(NULL);
                $so_obj->setCcReminderType(NULL);
                $so_obj->setHoldStatus(0);
                if ($so_obj->getStatus() < 3) {
                    $so_obj->setStatus(3);
                }

                if (!$this->sc['So']->getDao('So')->update($so_obj)) {
                    $_SESSION["NOTICE"] = $this->db->_error_message();
                }

                // Add notes
                $this->sc['creditCheckModel']->addOrderNote($so_no, 'Approved order with email confirmation');
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
        if (($so_obj = $this->sc['So']->getDao('So')->get(array("so_no" => $so_no))) === FALSE) {
            $_SESSION["NOTICE"] = $this->db->_error_message();
        } else {
            if (empty($so_obj)) {
                $_SESSION["NOTICE"] = "so_not_found";
            } else {
                // Send email
                $replace = array();
                $this->communication_center_email_common($so_obj, $replace, "further_check", "further_check");

                // Update SO
                if (!is_null($so_obj->getCcReminderScheduleDate())) {
                    $so_obj->setCcReminderScheduleDate(NULL);
                    $so_obj->setCcReminderType(NULL);

                    if(!$this->sc['So']->getDao('So')->update($so_obj)){
                    //if (!$this->sc['So']->getDao('So')->update($so_obj)) {
                        $_SESSION["NOTICE"] = $this->db->_error_message();
                    }
                }

                // Add notes
                $this->sc['creditCheckModel']->addOrderNote($so_no, 'Further check email sent');
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
        if (($so_obj = $this->sc['So']->getDao('So')->get(array("so_no" => $so_no))) === FALSE) {
            $_SESSION["NOTICE"] = $this->db->_error_message();
        } else {
            if (empty($so_obj)) {
                $_SESSION["NOTICE"] = "so_not_found";
            } else {
                // Send email
                $replace = array();
                $this->communication_center_email_common($so_obj, $replace, "failcc_refund", "failcc_refund");

                // Update SO
                if (!is_null($so_obj->getCcReminderScheduleDate())) {
                    $so_obj->setCcReminderScheduleDate(NULL);
                    $so_obj->setCcReminderType(NULL);
                    if (!$this->sc['So']->getDao('So')->update($so_obj)) {
                        $_SESSION["NOTICE"] = $this->db->_error_message();
                    }
                }

                // Create refund
                $refund_parameter = array();
                $refund_parameter['refund_reason_description'] = 'Failed Credit Check';
                if (!$this->sc['creditCheckModel']->createRefundFromCommunicationCenter($so_no, $refund_parameter)) {
                    $_SESSION["NOTICE"] = "failed_create_refund";
                }

                // Add notes
                $this->sc['creditCheckModel']->addOrderNote($so_no, 'Fail CC email send');
                //SBF #2607 add the default refund score when order funded
                $this->sc['SoRefundScore']->insertInitialRefundScore($so_no);

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
        if (($so_obj = $this->sc['So']->getDao('So')->get(array("so_no" => $so_no))) === FALSE) {
            $_SESSION["NOTICE"] = $this->db->_error_message();
        } else {
            if (empty($so_obj)) {
                $_SESSION["NOTICE"] = "so_not_found";
            } else {
                // Update SO
                if (!is_null($so_obj->getCcReminderScheduleDate())) {
                    $so_obj->setCcReminderScheduleDate(NULL);
                    $so_obj->setCcReminderType(NULL);
                    if (!$this->sc['So']->getDao('So')->update($so_obj)) {
                        $_SESSION["NOTICE"] = $this->db->_error_message();
                    }
                }

                // Create refund
                $refund_parameter = array();
                $refund_parameter['refund_reason_description'] = 'Refused Credit Check';
                if (!$this->sc['creditCheckModel']->createRefundFromCommunicationCenter($so_no, $refund_parameter)) {
                    $_SESSION["NOTICE"] = "refuse_cc_refund";
                }

                // Add notes
                $this->sc['creditCheckModel']->addOrderNote($so_no, 'Refuse CC refund');
                //SBF #2607 add the default refund score when order funded
                $this->sc['SoRefundScore']->insertInitialRefundScore($so_no);
            }
        }
        if (isset($_SESSION["CCLISTPAGE"])) {
            redirect($_SESSION["CCLISTPAGE"]);
        } else {
            redirect(current_url());
        }
    }

    public function oc_fraud($so_no = "", $reason_id = "")
    {
        if (($so_obj = $this->sc['So']->getDao('So')->get(array("so_no" => $so_no))) === FALSE) {
            $_SESSION["NOTICE"] = $this->db->_error_message();
        } else {
            if (empty($so_obj)) {
                $_SESSION["NOTICE"] = "so_not_found";
            } else {
                if (($sohr_vo = $this->sc['So']->getDao('SoHoldReason')->get()) !== FALSE) {
                    $sohr_vo->setSoNo($so_no);
                    $sohr_vo->setReason($reason_id);
                    if (!$this->sc['So']->getDao('SoHoldReason')->insert($sohr_vo)) {
                        $_SESSION["NOTICE"] = $this->db->_error_message();
                    }

                    $action = "update";
                    $socc_obj = $this->sc['So']->getDao('SoCreditChk')->get(array("so_no" => $so_no));
                    if (!$socc_obj) {
                        $socc_obj = $this->sc['So']->getDao('SoCreditChk')->get();
                        $action = "insert";
                    }
                    $this->sc['So']->getDao('SoCreditChk')->db->trans_start();
                    $socc_obj->setSoNo($so_no);
                    $socc_obj->setFdStatus(2);
                    $this->sc['So']->getDao('SoCreditChk')->$action($socc_obj);

                    $so_obj->setStatus(0);
                    if (!is_null($so_obj->getCcReminderScheduleDate())) {
                        $so_obj->setCcReminderScheduleDate(NULL);
                        $so_obj->setCcReminderType(NULL);
                    }

                    if (!$this->sc['So']->getDao('So')->update($so_obj)) {
                        $_SESSION["NOTICE"] = $this->db->_error_message();
                    }
                    $this->sc['So']->getDao('SoCreditChk')->db->trans_complete();
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
        if (($so_obj = $this->sc['So']->getDao('So')->get(array("so_no" => $so_no))) === FALSE) {
            $_SESSION["NOTICE"] = $this->db->_error_message();
        } else {
            if (empty($so_obj)) {
                $_SESSION["NOTICE"] = "so_not_found";
            } else {
                // Send email
                $replace = array();
                $replace['order_create_date'] = date("d/m/Y", strtotime($so_obj->getOrderCreateDate()));
                $list = $this->sc['So']->getDao('SoItemDetail')->getItemsWithName(array("so_no" => $so_no));
                $item_list = array();
                foreach ($list as $obj) {
                    $item_list[] = "- " . $obj->getName();
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

                $so_obj->setCcReminderScheduleDate($schedule_date);
                $so_obj->setCcReminderType('high_risk_cc');
                if (!$this->sc['So']->getDao('So')->update($so_obj)) {
                    $_SESSION["NOTICE"] = $this->db->_error_message();
                }

                // Add notes
                $this->sc['creditCheckModel']->addOrderNote($so_no, 'High risk CC email sent');
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
        if (($so_obj = $this->sc['So']->getDao('So')->get(array("so_no" => $so_no))) === FALSE) {
            $_SESSION["NOTICE"] = $this->db->_error_message();
        } else {
            if (empty($so_obj)) {
                $_SESSION["NOTICE"] = "so_not_found";
            } else {
                // Send email
                $replace = array();
                $replace['order_create_date'] = date("d/m/Y", strtotime($so_obj->getOrderCreateDate()));

                $list = $this->sc['So']->getDao('SoItemDetail')->getItemsWithName(array("so_no" => $so_no));
                $item_list = array();
                foreach ($list as $obj) {
                    $item_list[] = "- " . $obj->getName();
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

                $so_obj->setCcReminderScheduleDate($schedule_date);
                $so_obj->setCcReminderType('low_risk_cc');
                if (!$this->sc['So']->getDao('So')->update($so_obj)) {
                    $_SESSION["NOTICE"] = $this->db->_error_message();
                }

                // Add notes
                $this->sc['creditCheckModel']->addOrderNote($so_no, 'Low risk CC email sent');
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

        $reasonArr = $this->input->post('reason');

        foreach ($operation as $so_no => $operation_type) {
            if (!$this->all_in_one($so_no, $operation_type, $reasonArr[$so_no])) {
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


    public function all_in_one($so_no, $operation_type, $reason_id)
    {
        $reasonObj = $this->sc['So']->getDao('HoldReason')->get(['id'=>$reason_id]);
        $reason_type = $reasonObj->getReasonType();

        if ($operation_type == 'delete') {
            if (($so_obj = $this->sc['So']->getDao('So')->get(array("so_no" => $so_no))) === FALSE) {
                $_SESSION["NOTICE"] = $this->db->_error_message();
            } else {
                if (empty($so_obj)) {
                    $_SESSION["NOTICE"] = "$so_no order not found";
                } else {
                    $so_obj->setStatus(0);
                    if (!$this->sc['So']->getDao('So')->update($so_obj)) {
                        $_SESSION["NOTICE"] = $this->db->_error_message();
                    }
                }
            }
        }

        if ($operation_type == 'approve') {
            if (($so_obj = $this->sc['So']->getDao('So')->get(array("so_no" => $so_no))) === FALSE) {
                $_SESSION["NOTICE"] = $this->db->_error_message();
            } else {
                if (empty($so_obj)) {
                    $_SESSION["NOTICE"] = "$so_no order not found";
                } else {
                    $so_obj->setStatus(3);
                    if (!$this->sc['So']->getDao('So')->update($so_obj)) {
                        $_SESSION["NOTICE"] = $this->db->_error_message();
                    }
                }
            }
        }

        if ($operation_type == 'hold') {
            if (($so_obj = $this->sc['So']->getDao('So')->get(array("so_no" => $so_no))) === FALSE) {
                $_SESSION["NOTICE"] = $this->db->_error_message();
            } else {
                if (empty($so_obj)) {
                    $_SESSION["NOTICE"] = "$so_no order not found";
                } else {
                    if (in_array($reason_type, array("cscc", "csvv", "oc_fraud"))) {
                        $so_obj->setHoldStatus(1);
                    } else {
                        $so_obj->setHoldStatus(2);
                    }

                    if ($this->sc['So']->getDao('So')->update($so_obj)) {
                        if (($sohr_vo = $this->sc['So']->getDao('SoHoldReason')->get()) !== FALSE) {
                            $sohr_vo->setSoNo($so_no);
                            $sohr_vo->setReason($reason_id);
                            if (!$this->sc['So']->getDao('SoHoldReason')->insert($sohr_vo)) {
                                $_SESSION["NOTICE"] = $this->db->_error_message();
                            }

                            if (in_array($reason_type, array("change_of_address", "confirmation_required", "customer_request"))) {
                                $so_obj->setHoldStatus(1);
                                if (!$this->sc['So']->getDao('So')->update($so_obj)) {
                                    $_SESSION["NOTICE"] = $this->db->_error_message();
                                }
                            }
                            if ($reason == "oc_fraud") {
                                $action = "update";


                                $socc_obj = $this->sc['So']->getDao('SoCreditChk')->get(array("so_no" => $so_no));
                                if (!$socc_obj) {
                                    $socc_obj = $this->sc['So']->getDao('SoCreditChk')->get();
                                    $action = "insert";
                                }
                                $this->sc['So']->getDao('SoCreditChk')->db->trans_start();
                                $socc_obj->setSoNo($so_no);
                                $socc_obj->setFdStatus(2);
                                $this->sc['So']->getDao('SoCreditChk')->$action($socc_obj);

                                $so_obj->setStatus(0);
                                $so_obj->setHoldStatus(0);
                                if (!$this->sc['So']->getDao('So')->update($so_obj)) {
                                    $_SESSION["NOTICE"] = $this->db->_error_message();
                                }
                                $this->sc['So']->getDao('SoCreditChk')->db->trans_complete();
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
                            $this->sc['creditCheckModel']->addOrderNote($so_no, $note);
                            echo ", added note";
                        } else
                            echo ", blank note note added";

                        echo "<br>\r\n";
                    } else
                        echo "FAILED: to mark SO#$so_no as credit checked, note not added<br>\r\n";
                } else {
                    if ($note <> "") {
                        $this->sc['creditCheckModel']->addOrderNote($so_no, $note);
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
        if (($so_obj = $this->sc['So']->getDao('So')->get(array("so_no" => $so_no))) === FALSE) {
            $_SESSION["NOTICE"] = $this->db->_error_message();
        } else {
            if (empty($so_obj)) {
                $_SESSION["NOTICE"] = "so_not_found";
            } else {
                if ($so_obj->getStatus() == 2) {
                    $so_obj->setStatus(3);
                    if (!$this->sc['So']->getDao('So')->update($so_obj))
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


