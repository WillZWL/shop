<?php

/**
 *
 * Payment gateway
 * Payment transaction id
 * Shipped on
 * Created on
 * Refund score
 * Score date
 *
 * Shipped On Date (if any display the date)
 * Special order (if any display the Y= Yes or N= No)
 * Refund reason (no comments)
 **/
class Refund extends MY_Controller
{
    private $appId = 'CS0002';
    private $lang_id = 'en';

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $langfile = $this->getAppId() . "00_" . $this->getLangId() . ".php";
        include_once APPPATH . "language/" . $langfile;
        $data["lang"] = $lang;
        $data["app_id"] = $this->getAppId();
        $this->load->view('cs/refund/index', $data);
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function reason($id = "", $offset = 0)
    {
        if (check_app_feature_access_right($this->getAppId(), "CS000200_refund_btn")) {
            if ($this->input->post('posted')) {
                if ($this->input->post('action') == 'add') {
                    $reason_obj = $this->sc['Refund']->getDao('RefundReason')->get();
                    $reason_obj->setReasonCat($this->input->post('r_cat'));
                    $reason_obj->setDescription($this->input->post('r_desc'));

                    $ret = $this->sc['Refund']->getDao('RefundReason')->insert($reason_obj);
                    if ($ret === FALSE) {
                        $_SESSION["NOTICE"] = "failed_to_add_reason";
                    }
                } else if ($this->input->post('action') == 'edit') {
                    $reason_obj = $this->sc['Refund']->getDao('RefundReason')->get(['id' => $this->input->post('id')]);
                    $reason_obj->setReasonCat($this->input->post('ecat'));
                    $reason_obj->setDescription($this->input->post('edesc'));

                    $ret = $this->sc['Refund']->getDao('RefundReason')->update($reason_obj);

                    if ($ret === FALSE) {
                        $_SESSION["NOTICE"] = "failed_to_update_reason";
                    }
                } elseif ($this->input->post('action') == 'delete') {
                    $reason_obj = $this->sc['Refund']->getDao('RefundReason')->get(['id' => $this->input->post('id')]);
                    $reason_obj->setStatus(0);

                    $ret = $this->sc['Refund']->getDao('RefundReason')->update($reason_obj);

                    if ($ret === FALSE) {
                        $_SESSION["NOTICE"] = "failed_to_delete_reason";
                    }
                }
            }

            $_SESSION["LISTPAGE"] = base_url() . "cs/refund/reason?" . $_SERVER['QUERY_STRING'];

            $langfile = $this->getAppId() . "01_" . $this->getLangId() . ".php";
            include_once APPPATH . "language/" . $langfile;

            $where["status"] = 1;
            if ($this->input->get("cat") != "") {
                $where["reason_cat"] = $this->input->get("cat");
            }

            if ($this->input->get("desc") != "") {
                $where["description LIKE "] = '%' . $this->input->get("desc") . '%';
            }

            $limit = 20;
            $option["limit"] = $limit;
            $option["offset"] = $offset;

            $sort = $this->input->get('sort');
            if ($sort == "") {
                $sort = "id";
            }

            $order = $this->input->get('order');
            if (empty($order)) {
                $order = "asc";
            }

            $option["orderby"] = $sort . " " . $order;

            // print_r($option);die;

            $data = $this->sc['Refund']->getReasonList($where, $option);
            $data["lang"] = $lang;
            $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
            $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
            $option["orderby"] = $sort . " " . $order;

            $config['base_url'] = base_url('cs/refund/reason/$id');
            $config['total_rows'] = $data["total"];
            $config['per_page'] = $limit;

            $data["notice"] = notice($lang);

            if ($id != "") {
                $data["action"] = "edit";
                $_SESSION["refund_reason"] = serialize($this->sc['Refund']->getDao('RefundReason')->get(["id" => $id]));
                $data["eid"] = $id;
            }
            $data["app_id"] = $this->getAppId();
            $this->load->view('cs/refund/index_reason', $data);
        } else {
            show_error("Access Denied!");
        }
    }

    public function create($offset = 0)
    {
        $where = [];
        $option = [];

        $search = $this->input->get('search');
        if ($search) {

            $option["create"] = 1;

            if ($this->input->get('so_no') != "") {
                $where["so_no LIKE"] = '%' . $this->input->get('so_no') . '%';
            }

            if ($this->input->get('cname') != "") {
                $where["bill_name LIKE"] = '%' . $this->input->get('cname') . '%';
            }

            if ($this->input->get('platform_id') != "") {
                $where["platform_id"] = $this->input->get('platform_id');
            }

            if ($this->input->get('platform_order_id') != "") {
                $where["platform_order_id"] = $this->input->get('platform_order_id');
            }

            $sort = $this->input->get('sort');
            if ($sort == "") {
                $sort = "so_no";
            }

            $order = $this->input->get('order');
            if (empty($order)) {
                $order = "asc";
            }

            $limit = 20;

            $option["limit"] = $limit;

            $option["offset"] = $offset;

            $_SESSION["LISTPAGE"] = base_url() . "cs/refund/create?" . $_SERVER['QUERY_STRING'];

            $option["orderby"] = $sort . " " . $order;

            $data = $this->sc['Refund']->getOrderList($where, $option);
            $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
            $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
            $option["orderby"] = $sort . " " . $order;

            $config['base_url'] = base_url('cs/refund/create');
            $config['total_rows'] = $data["total"];
            $config['per_page'] = $limit;

            $this->pagination->initialize($config);
            $data['links'] = $this->pagination->create_links();
        }

        $langfile = $this->getAppId() . "02_" . $this->getLangId() . ".php";
        include_once APPPATH . "language/" . $langfile;

        $data["notice"] = notice($lang);
        $data["lang"] = $lang;

        $this->load->view('cs/refund/index_create', $data);
    }

    public function create_view($orderid = "")
    {
        if ($orderid == "") {
            Redirect(base_url() . "cs/refund/create/");
            exit;
        }

        if ($this->input->post('posted')) {
            $refund_obj = $this->sc['Refund']->getDao('Refund')->get();
            $refund_item_obj = $this->sc['Refund']->getDao('RefundItem')->get();
            $refund_history_obj = $this->sc['Refund']->getDao('RefundHistory')->get();
            $refund = $this->input->post('refund');

            if ($refund) {
                $ro_list = [];

                $rqty = $this->input->post('rqty');
                $ramount = $this->input->post('ramount');
                $rsku = $this->input->post('rsku');
                foreach ($refund as $key => $obj) {
                    $tmp = clone $refund_item_obj;
                    $tmp->setItemSku($rsku[$key]);
                    $tmp->setQty($rqty[$key]);
                    if ($rqty[$key] > 0)
                        $tmp->setRefundAmount($ramount[$key] / $rqty[$key]);
                    else
                        $tmp->setRefundAmount(0);
                    $tmp->setStatus('N');
                    $tmp->setRefundType('R');

                    $ro_list[] = $tmp;
                }
            }

            $status = 1;
            if ($this->input->post('cashback') > 0) {
                $tmp = clone $refund_item_obj;
                $tmp->setItemSku("");
                $tmp->setQty(1);
                $tmp->setRefundAmount($this->input->post('cashback'));
                $tmp->setStatus('N');
                $tmp->setRefundType('C');
                $status = 2;


                $ro_list[] = $tmp;
            }

            $refund_history_obj->setStatus('N');
            $refund_history_obj->setNotes($this->input->post('rnotes'));


            $refund_obj->setSoNo($orderid);
            $refund_obj->setStatus('I');
            $refund_obj->setTotalRefundAmount($this->input->post('total'));
            $refund_obj->setReason($this->input->post("reason"));

            $err = 0;

            if (count($ro_list)) {
                $success = 1;
                $this->sc['Refund']->getDao('Refund')->db->trans_start();

                $so_obj = $this->sc['So']->getDao('So')->get(["so_no" => $orderid]);
                //added by Jack for bypassing refund

                if ($so_obj->getStatus() > 3 && $so_obj->getStatus() < 6) {
                    $status = 1;
                } else {
                    $status = 2;
                }

                //end added by Jack
                $so_obj->setRefundStatus($status);
                if ($this->sc['So']->getDao('So')->update($so_obj) !== FALSE) {

                    if ($result = $this->sc['Refund']->getDao('Refund')->insert($refund_obj)) {
                        $refund_id = $result->getId();

                        foreach ($ro_list as $key => $obj) {
                            $obj->setRefundId($refund_id);
                            if ($status == 2) {
                                $obj->setStatus('LG');
                            }
                            $obj->setLineNo($key + 1);

                            if (!$this->sc['Refund']->getDao('RefundItem')->insert($obj)) {
                                $success = 0;
                                $_SESSION["NOTICE"] = "ERROR: @" . __LINE__ . " " . $this->db->display_error();
                                $this->sc['Refund']->getDao('Refund')->db->trans_rollback();
                                break;
                            }
                        }

                        if ($success) {
                            $refund_history_obj->setRefundId($refund_id);
                            $refund_history_obj->setAppStatus('A');

                            if (!$this->sc['Refund']->getDao('RefundHistory')->insert($refund_history_obj)) {
                                $success = 0;
                                $_SESSION["NOTICE"] = "ERROR: @" . __LINE__ . " " . $this->db->display_error();
                                $this->sc['Refund']->getDao('Refund')->db->trans_rollback();
                            } else {
                                // if current so_obj is a split child, find its parent and update refund status
                                // get_split_so_group() gives the parent so_no that it belongs to
                                if ($split_so_group = $so_obj->getSplitSoGroup()) {
                                    if ($split_so_group && ($so_obj->getSoNo() != $split_so_group)) {
                                        if ($split_parent_obj = $this->sc['So']->getDao('So')->get(["so_no" => $split_so_group])) {
                                            $split_parent_obj->setRefundStatus($status);
                                            if ($this->sc['So']->getDao('So')->update($split_parent_obj) === FALSE) {
                                                $_SESSION["NOTICE"] = "ERROR: @" . __LINE__ . " Error update split parent so_no $split_so_group. " . $this->db->display_error();
                                            }

                                        }
                                    }
                                }
                            }

                            //SBF #2607 add the default refund score when order funded
                            $this->sc['SoRefundScore']->insertInitialRefundScore($orderid);
                        }
                    } else {
                        $success = 0;
                        $_SESSION["NOTICE"] = "ERROR: @" . __LINE__ . " " . $this->db->display_error();
                        $this->sc['Refund']->getDao('Refund')->db->trans_rollback();
                    }
                } else {
                    $success = 0;
                    $_SESSION["NOTICE"] = "ERROR: @" . __LINE__ . " " . $this->db->display_error();
                    $this->sc['Refund']->getDao('Refund')->db->trans_rollback();
                }

                $this->sc['Refund']->getDao('Refund')->db->trans_complete();

                if ($success) {
                    // $this->sc['Refund']->fireEmail($refund_id, $status, "approve", "cs");
                    Redirect(base_url() . "cs/refund/create/");
                }

            } else {
                $_SESSION["NOTICE"] = "Cannot_create_empty_refund";
            }
        }

        $history_list = $this->sc['Refund']->getDao('RefundHistory')->getHistoryList(["so_no" => $orderid]);

        $item_list = $this->sc['So']->getDao('SoItemDetail')->getListWithProdname(["so_no" => $orderid]);

        $data["history"] = $history_list;
        $data["itemlist"] = $item_list;
        $data["itemcnt"] = count((array)$data["itemlist"]);
        $data["orderobj"] = $so = $this->sc['So']->getDao('So')->get(["so_no" => $orderid, "refund_status" => 0, "status > " => 2]);

        $split_child_list_html = "";
        $split_so_group = $so->getSplitSoGroup();
        if ($split_so_group) {
            // get list of other refundable split child orders in the same group
            if ($split_group_list = $this->sc['So']->getDao('So')->getList(["split_so_group" => $split_so_group, "refund_status" => 0, "status > " => 2])) {
                foreach ($split_group_list as $key => $childobj) {
                    $split_child_list_html .= "<a href='" . base_url() . "cs/refund/create_view/{$childobj->getSoNo()}'> >> {$childobj->getSoNo()}<br>";
                }
            }
        }
        $data["split_child_list_html"] = $split_child_list_html;

        if (!count($so)) {
            Redirect(base_url() . "cs/refund/create/");
            exit;
        }

        $data["refund"] = $this->sc['Refund']->getDao('Refund')->get(["so_no" => $orderid, "status" => "I"]);

        $langfile = $this->getAppId() . "03_" . $this->getLangId() . ".php";
        include_once APPPATH . "language/" . $langfile;

        $reasonlist = $this->sc['Refund']->getReasonList(["status" => 1], ["orderby" => "reason_cat , id"]);

        $data["reason"] = $reasonlist;
        $data["lang"] = $lang;
        $data["orderid"] = $orderid;
        $data["notice"] = notice($lang);

        $this->load->view('cs/refund/view_create', $data);
    }

    public function logistics($offset = 0)
    {
        if (check_app_feature_access_right($this->getAppId(), "CS000200_log_btn")) {
            $where = [];
            $option = [];

            $where["rstatus"] = "N";
            $where["refund_type"] = 'R';

            if ($this->input->get('rid') != "") {
                $where["rid"] = $this->input->get('rid');
            }

            if ($this->input->get('so') != "") {
                $where["so"] = $this->input->get('so');
            }

            if ($this->input->get('platform_id') != "") {
                $where["platform_id"] = $this->input->get('platform_id');
            }

            if ($this->input->get('platform_order_id') != "") {
                $where["platform_order_id"] = $this->input->get('platform_order_id');
            }

            $sort = $this->input->get('sort');
            if ($sort == "") {
                $sort = "r.id";
            }

            $order = $this->input->get('order');
            if (empty($order)) {
                $order = "asc";
            }

            $limit = 20;
            $option["limit"] = $limit;
            $option["offset"] = $offset;

            $_SESSION["LISTPAGE"] = base_url() . "cs/refund/logistics?" . $_SERVER['QUERY_STRING'];
            $_SESSION["QUERY_STRING"] = $_SERVER['QUERY_STRING'];

            $option["orderby"] = $sort . " " . $order;

            $langfile = $this->getAppId() . "04_" . $this->getLangId() . ".php";
            include_once APPPATH . "language/" . $langfile;

            $data = $this->sc['Refund']->getRefundSoList($where, $option);

            $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
            $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
            $option["orderby"] = $sort . " " . $order;

            $config['base_url'] = base_url('cs/refund/logistics');
            $config['total_rows'] = $data["total"];
            $config['per_page'] = $limit;

            $this->pagination->initialize($config);
            $data['links'] = $this->pagination->create_links();

            $data["notice"] = notice($lang);
            $data["lang"] = $lang;

            $this->load->view('cs/refund/index_logistics', $data);
        } else {
            show_error("Access Denied!");
        }
    }

    public function logistics_view($refundid = "")
    {
        if (check_app_feature_access_right($this->getAppId(), "CS000200_log_btn")) {
            if ($refundid == "") {
                Redirect(base_url() . "cs/refund/logistics/");
            }

            if ($this->input->post('posted')) {
                $amount_change = 0;
                $refund_obj = $this->sc['Refund']->getDao('Refund')->get(["id" => $refundid, "status" => "I"]);

                $deny = $this->input->post('denyitem');
                $istatus = $this->input->post('ritem');
                $sbdate = $this->input->post('sbdate');
                $sbwh = $this->input->post('sbwh');
                $item_arr = [];

                $denial = 0;
                $appr = 0;
                foreach ($deny as $key => $val) {
                    if ($val) {
                        //refund approved
                        $obj = $this->sc['Refund']->getDao('RefundItem')->get(["refund_id" => $refundid, "line_no" => $key]);
                        $obj->setItemStatus($istatus[$key]);
                        $obj->setStatus("LG");
                        list($day, $month, $year) = explode("/", $sbdate[$key]);
                        $obj->setStockbackDate(date("Y-m-d", strtotime("$year-$month-$day")));
                        $obj->setStockbackWarehouse($sbwh[$key]);
                        $item_arr[] = $obj;

                        $appr++;
                    } else {
                        //refund denied
                        $obj = $this->sc['Refund']->getDao('RefundItem')->get(["refund_id" => $refundid, "line_no" => $key]);
                        $obj->setItemStatus($istatus[$key]);
                        $obj->setStatus("D");
                        $amount_change += $obj->getRefundAmount();
                        $item_arr[] = $obj;

                        $denial++;
                    }
                }

                $hobj = $this->sc['Refund']->getDao('RefundHistory')->get();
                $hobj->setRefundId($refundid);
                $hobj->setStatus("LG");
                $hobj->setNotes($this->input->post("rnotes"));

                $err = 0;


                $this->sc['Refund']->getDao('Refund')->db->trans_start();

                foreach ($item_arr as $obj) {
                    $ret = $this->sc['Refund']->getDao('RefundItem')->update($obj);
                    if ($ret === FALSE) {
                        $err++;
                        break;
                    }
                }

                $this->sc['Refund']->checkAction($refund_id,"cs");
                $refund_obj = $this->sc['Refund']->getDao('Refund')->get(["id" => $refundid]);

                if ($amount_change > 0) {

                    $refund_obj->setTotalRefundAmount($refund_obj->getTotalRefundAmount() - $amount_change);
                    $ret = $this->sc['Refund']->getDao('Refund')->update($refund_obj);

                    if ($ret === FALSE) {
                        $err++;
                    }
                }

                $type = "";
                $so_obj = $this->sc['So']->getDao('So')->get(["so_no" => $refund_obj->getSoNo()]);
                if ($denial && !$appr) {
                    $status = 0;
                    $type = "deny";
                    $app_status = 'D';
                    if (($so_obj->getStatus() != 1) || ($so_obj->getStatus() != 6)) {
                        $hold = '1';
                    }
                } else {
                    $status = 2;
                    $type = "approve";
                    if ($denial) {
                        $app_status = 'AD';
                        if (($so_obj->getStatus() != 1) || ($so_obj->getStatus() != 6)) {
                            $hold = '1';
                        }
                    } else {
                        $app_status = 'A';
                    }
                }
                if ($hold == '1') {
                    $so_obj->setHoldStatus($hold);
                }
                $so_obj->setRefundStatus($status);
                $ret = $this->sc['So']->getDao('So')->update($so_obj);
                if ($ret) {
                    if ($hold == '1') {
                        if ($so_hold_reason_obj = $this->sc['So']->getDao('SoHoldReason')->get()) {
                            $holdrea = clone $so_hold_reason_obj;
                            $holdrea->setSoNo($so_obj->getSoNo());
                            $holdrea->setReason('Confirmation Required');
                            $this->sc['So']->getDao('SoHoldReason')->insert($holdrea);
                        }
                    }
                }
                if ($ret === FALSE) {
                    $err++;
                }

                $hobj->setAppStatus($app_status);
                $ret = $this->sc['Refund']->getDao('RefundHistory')->insert($hobj);

                if ($ret === FALSE) {
                    $err++;
                }

                $this->sc['Refund']->checkAction($refundid, "LG");
                $this->sc['Refund']->getDao('Refund')->db->trans_complete();


                if ($err) {
                    $_SESSION["NOTICE"] = "update_fail";
                } else {
                    $this->sc['Refund']->fireEmail($refundid, 2, $type, "log");
                    Redirect(base_url() . "cs/refund/logistics");
                }
            }


            $refund_obj = $this->sc['Refund']->getDao('Refund')->get(["id" => $refundid, "status" => "I"]);

            if ($refund_obj->getId() == "") {
                Redirect(base_url() . "cs/refund/logistics/");
            }

            $history_list = $this->sc['Refund']->getDao('RefundHistory')->getHistoryList(["so_no" => $refund_obj->getSoNo()]);
            $order_item_list = $this->sc['So']->getDao('SoItemDetail')->getListWithProdname(["so_no" => $refund_obj->getSoNo()]);

            $item_list = $this->sc['Refund']->getDao('RefundItem')->getListWithName(["ri.refund_id" => $refundid, "ri.refund_type" => "R"], ["sortby" => "line_no ASC"]);
            $data["history"] = $history_list;
            $data["itemlist"] = $item_list;
            $data["order_item_list"] = $order_item_list;
            $data["orderobj"] = $this->sc['So']->getDao('So')->get(["so_no" => $refund_obj->getSoNo()]);
            $data["refund_obj"] = $refund_obj;
            $langfile = $this->getAppId() . "05_" . $this->getLangId() . ".php";
            include_once APPPATH . "language/" . $langfile;

            $reasonlist = $this->sc['Refund']->getReasonList([], []);

            $data["reason"] = $reasonlist;
            $data["lang"] = $lang;
            $data["isCod"] = $this->_view_cod_order_highlight($refund_obj->getSoNo());

            $this->load->view('cs/refund/view_logistics', $data);

        } else {
            show_error("Access Denied!");
        }
    }

    public function _view_cod_order_highlight($so_no)
    {
//css class
// normal: title
// cod: title_red
//      $indicator = array("title", "cod_highlight");
        if ($this->sc['So']->isCodOrder($so_no)) {
            return true;
        } else
            return false;
    }

    public function export_cs_csv($offset = 0)
    {
        if (check_app_feature_access_right($this->getAppId(), "CS000200_cs_btn")) {
            $limit = -1;
            list($where, $option) = $this->build_cs_query($limit, $offset);
            $option["limit"] = $limit;
            $data = $this->sc['Refund']->getRefundSoList($where, $option);

            // var_dump($data); die();
            $filename = "refund_report.csv";
            $output = "ID,SO#,platform_id,payment_gateway,platform_id,amount,order_date,dispatch_date,create_on,create_by,special_order,refund_reason,refund_score,refund_score_date,pack_date\r\n";
            foreach ($data["list"] as $obj) {
                $special_order = "";
                if ($obj->getSpecialOrder() != 0) $special_order = "Y";

                $line = "{$obj->getId()},
                    {$obj->getSoNo()},
                    {$obj->getPlatformOrderId()},
                    {$obj->getPaymentGateway()},
                    {$obj->getPlatformId()},
                    {$obj->getCurrencyId()} {$obj->getTotalRefundAmount()},
                    {$obj->getOrderDate()},
                    {$obj->getDispatchDate()},
                    {$obj->getCreateOn()},
                    {$obj->getCreateBy()},
                    $special_order,
                    {$obj->getRefundReason()},
                    {$obj->getRefundScore()},
                    {$obj->getRefundScoreDate()},
                    {$obj->getPackDate()}";

                $line = str_replace("\n", "", $line);
                $line .= "\r\n";

                $output .= $line;
            }

            header("Content-type: application/vnd.ms-excel");
            header("Content-disposition: filename=$filename");
            echo $output;
        }
    }

    private function build_cs_query($limit, $offset = 0)
    {
        $where["rstatus"] = "LG";
        $where["check_cb"] = 1;

        if ($this->input->get('rid') != "") {
            $where["rid"] = $this->input->get('rid');
        }

        if ($this->input->get('so') != "") {
            $where["so"] = $this->input->get('so');
        }

        if ($this->input->get('platform_id') != "") {
            $where["platform_id"] = $this->input->get('platform_id');
        }

        if ($this->input->get('platform_order_id') != "") {
            $where["platform_order_id"] = $this->input->get('platform_order_id');
        }

        if ($this->input->get('payment_gateway') != "") {
            $where["payment_gateway"] = $this->input->get('payment_gateway');
        }

        $sort = $this->input->get('sort');
        if ($sort == "")
            $sort = "r.id";

        $order = $this->input->get('order');
        if (empty($order))
            $order = "asc";

        $option["need_pack_date"] = True;

        $option["limit"] = $limit;

        $option["offset"] = $offset;

        $_SESSION["LISTPAGE"] = base_url() . "cs/refund/cs?" . $_SERVER['QUERY_STRING'];
        $_SESSION["QUERY_STRING"] = $_SERVER['QUERY_STRING'];

        $option["orderby"] = $sort . " " . $order;

        return array($where, $option);
    }

    public function cs($offset = 0)
    {
        if (check_app_feature_access_right($this->getAppId(), "CS000200_cs_btn")) {
            $q = "";
            foreach ($_GET as $k => $v)
                $q .= "&$k=" . urlencode($v);

            $langfile = $this->getAppId() . "06_" . $this->getLangId() . ".php";
            include_once APPPATH . "language/" . $langfile;

            $limit = 20;
            list($where, $option) = $this->build_cs_query($limit, $offset);
            $data = $this->sc['Refund']->getRefundSoList($where, $option);

            $sort = $this->input->get("sort");
            $order = $this->input->get("order");

            if (empty($sort))
                $sort = "id";

            if (empty($order))
                $order = "asc";

            $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
            $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
            $option["orderby"] = $sort . " " . $order;

            $config['base_url'] = base_url('cs/refund/cs');
            $config['total_rows'] = $data["total"];
            $config['per_page'] = $limit;

            $this->pagination->initialize($config);
            $data['links'] = $this->pagination->create_links();

            $data["notice"] = notice($lang);
            $data["lang"] = $lang;
            $data["q"] = $q;

            $this->load->view('cs/refund/index_cs', $data);
        } else {
            show_error("Access Denied!");
        }
    }

    public function cs_view($refundid = "")
    {
        if (check_app_feature_access_right($this->getAppId(), "CS000200_cs_btn")) {
            if ($refundid == "") {
                Redirect(base_url() . "cs/refund/cs/");
                exit;
            }

            if ($this->input->post('posted')) {
                $amount_change = 0;
                $refund_obj = $this->sc['Refund']->getDao('Refund')->get(["id" => $refundid, "status" => "I"]);

                $deny = $this->input->post('denyitem');
                $refund = $this->input->post('refund');


                $denial = 0;
                $appr = 0;
                $item_arr = [];
                foreach ($deny as $key => $val) {
                    if ($val) {
                        //refund approved
                        $obj = $this->sc['Refund']->getDao('RefundItem')->get(["refund_id" => $refundid, "line_no" => $key]);
                        $org_amount = $obj->getRefundAmount() * $obj->getQty();
                        $obj->setRefundAmount($refund[$key] / $obj->getQty());
                        $obj->setStatus("CS");
                        $item_arr[] = $obj;
                        $amount_change += $refund[$key] - $org_amount;

                        $appr++;
                    } else {
                        //refund denied
                        $obj = $this->sc['Refund']->getDao('RefundItem')->get(["refund_id" => $refundid, "line_no" => $key]);
                        $org_amount = $obj->getRefundAmount() * $obj->getQty();
                        $obj->setStatus("D");
                        $amount_change -= $org_amount;
                        $item_arr[] = $obj;

                        $denial++;
                    }
                }

                $hobj = $this->sc['Refund']->getDao('RefundHistory')->get();
                $hobj->setRefundId($refundid);
                $hobj->setStatus("CS");
                $hobj->setNotes($this->input->post("rnotes"));

                $err = 0;


                $this->sc['Refund']->getDao('Refund')->db->trans_start();

                foreach ($item_arr as $obj) {
                    $ret = $this->sc['Refund']->getDao('RefundItem')->update($obj);
                    if ($ret === FALSE) {
                        $err++;
                        break;
                    }
                }

                $refund_obj = $this->sc['Refund']->getDao('Refund')->get(["id" => $refundid]);

                if (($amount_change != 0) && (!(abs($amount_change) <= 0.01))) {
                    $refund_obj = $this->sc['Refund']->getDao('Refund')->get(["id" => $refundid]);
                    $refund_obj->setTotalRefundAmount($refund_obj->getTotalRefundAmount() + $amount_change);
                    $ret = $this->sc['Refund']->getDao('Refund')->update($refund_obj);

                    if ($ret === FALSE) {
                        $err++;
                    }
                }

                $so_obj = $this->sc['So']->getDao('So')->get(["so_no" => $refund_obj->getSoNo()]);
                if ($denial && !$appr) {
                    $status = 0;
                    $app_status = 'D';
                    if (($so_obj->getStatus() != 1) || ($so_obj->getStatus() != 6)) {
                        $hold = '1';
                    }
                } else {
                    $status = 3;
                    if ($denial) {
                        $app_status = 'AD';
                        if (($so_obj->getStatus() != 1) || ($so_obj->getStatus() != 6)) {
                            $hold = '1';
                        }
                    } else {
                        $app_status = 'A';
                    }
                }
                if ($hold == '1') {
                    $so_obj->setHoldStatus($hold);
                }
                $so_obj->setRefundStatus($status);
                $ret = $this->sc['So']->getDao('So')->update($so_obj);
                $this->sc['Refund']->checkAction($refundid, "CS");

                $hobj->setAppStatus($app_status);
                $ret = $this->sc['Refund']->getDao('RefundHistory')->insert($hobj);

                $split_so_group = $so_obj->getSplitSoGroup();
                if ($split_so_group) {
                    $split_parent_obj = $this->sc['So']->getDao('So')->get(["so_no" => $split_so_group]);
                    $split_parent_obj->setRefundStatus($status);
                    $ret = $this->sc['So']->getDao('So')->update($split_parent_obj);
                }

                if ($ret) {
                    if ($hold == '1') {
                        if ($so_hold_reason_obj = $this->sc['So']->getDao('SoHoldReason')->get()) {
                            $holdrea = clone $so_hold_reason_obj;
                            $holdrea->setSoNo($so_obj->getSoNo());
                            $holdrea->setReason('Confirmation Required');
                            $this->sc['So']->getDao('SoHoldReason')->insert($holdrea);
                        }
                    }
                }

                if ($ret === FALSE) {
                    $err++;
                }


                $this->sc['Refund']->getDao('Refund')->db->trans_complete();

                if ($err) {
                    $_SESSION["NOTICE"] = "update_fail";
                } else {
                    Redirect(base_url() . "cs/refund/cs");
                    exit;
                }
            }

            $refund_obj = $this->sc['Refund']->getDao('Refund')->get(["id" => $refundid, "status" => "I"]);

            if ($refund_obj->getId() == "") {
                Redirect(base_url() . "cs/refund/cs/");
                exit;
            }

            $history_list = $this->sc['Refund']->getDao('RefundHistory')->getHistoryList(["so_no" => $refund_obj->getSoNo()]);
            $order_item_list = $this->sc['So']->getDao('SoItemDetail')->getListWithProdname(["so_no" => $refund_obj->getSoNo()]);
            $item_list = $this->sc['Refund']->getDao('RefundItem')->getListWithName(["ri.refund_id" => $refundid, "ri.status <>" => 'D'], ["sortby" => "line_no ASC"]);

            $data["history"] = $history_list;
            $data["itemlist"] = $item_list;
            $data["order_item_list"] = $order_item_list;
            $data["orderobj"] = $this->sc['So']->getDao('So')->get(["so_no" => $refund_obj->getSoNo()]);
            $data["refund_obj"] = $refund_obj;
            $langfile = $this->getAppId() . "07_" . $this->getLangId() . ".php";
            include_once APPPATH . "language/" . $langfile;

            $reasonlist = $this->sc['Refund']->getReasonList([], []);

            $data["reason"] = $reasonlist;
            $data["isCod"] = $this->_view_cod_order_highlight($refund_obj->getSoNo());
            $data["lang"] = $lang;


            $this->load->view('cs/refund/view_cs', $data);
        } else {
            show_error("Access Denied!");
        }
    }

    // record yandex refund order to flex_refund

    public function account($offset = 0)
    {
        if (check_app_feature_access_right($this->getAppId(), "CS000200_acc_btn")) {
            $where["rstatus"] = array('CS', 'CP');

            if ($this->input->get('rid') != "")
                $where["rid"] = $this->input->get('rid');

            if ($this->input->get('so') != "")
                $where["so"] = $this->input->get('so');

            if ($this->input->get('platform_id') != "")
                $where["platform_id"] = $this->input->get('platform_id');

            if ($this->input->get('txn_id') != "")
                $where["txn_id"] = $this->input->get('txn_id');

            if ($this->input->get('platform_order_id') != "")
                $where["platform_order_id"] = $this->input->get('platform_order_id');

            if ($this->input->get('payment_gateway') != "")
                $where["payment_gateway"] = $this->input->get('payment_gateway');

            $sort = $this->input->get('sort');
            if ($sort == "") {
                $sort = "r.id";
            }

            $order = $this->input->get('order');
            if (empty($order)) {
                $order = "asc";
            }

            $limit = 500;

            $option["limit"] = $limit;

            $option["offset"] = $offset;

            $_SESSION["LISTPAGE"] = base_url() . "cs/refund/account?" . $_SERVER['QUERY_STRING'];
            $_SESSION["QUERY_STRING"] = $_SERVER['QUERY_STRING'];

            $option["orderby"] = $sort . " " . $order;

            $sortField = ['multi_sort_rs', 'multi_sort_pg', 'multi_sort_od', 'multi_sort_rsd'];

            $sortFieldMapping['multi_sort_rs'] = 'sors.score';
            $sortFieldMapping['multi_sort_pg'] = 'sops.payment_gateway_id';
            $sortFieldMapping['multi_sort_od'] = 's.create_on';
            $sortFieldMapping['multi_sort_rsd'] = 'sors.modify_on';

            $isMultiMode = $_GET['field'];

            if ($isMultiMode) {
                $sortingString = '';
                foreach ($sortField as $v) {
                    $sortOrder = $_GET[$v];
                    if ($v == $isMultiMode)
                        $sortOrder = $sortOrder == "DESC" ? "ASC" : "DESC";

                    $sortingString .= $sortFieldMapping["$v"] . " " . $sortOrder . ",";

                    $temp["multiSort"]["$v"] = $sortOrder;
                }

                $option["orderby"] = trim($sortingString, ',');
            } else {
                foreach ($sortField as $v)
                    $temp["multiSort"]["$v"] = 'DESC';
            }

            $langfile = $this->getAppId() . "08_" . $this->getLangId() . ".php";
            include_once APPPATH . "language/" . $langfile;

            $data = $this->sc['Refund']->getRefundSoList($where, $option);

            $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
            $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
            $option["orderby"] = $sort . " " . $order;

            $config['base_url'] = base_url('cs/refund/account');
            $config['total_rows'] = $data["total"];
            $config['per_page'] = $limit;

            $this->pagination->initialize($config);
            $data['links'] = $this->pagination->create_links();

            $data["notice"] = notice($lang);
            $data["lang"] = $lang;
            $data["multiSort"] = $temp["multiSort"];
            $this->load->view('cs/refund/index_account', $data);
        } else {
            show_error("Access Denied!");
        }
    }

    public function account_view($refundid = "")
    {
        if (check_app_feature_access_right($this->getAppId(), "CS000200_acc_btn")) {

            if ($refundid == "") {
                Redirect(base_url() . "cs/refund/account/");
                exit;
            }

            if ($this->input->post('addnote') == 1) {
                $obj = $this->sc['So']->getDao('OrderNotes')->get();
                $obj->setSoNo($this->input->post('orderid'));
                $obj->setType('O');
                $obj->setNote($this->input->post('note'));

                $ret = $this->sc['So']->getDao('OrderNotes')->insert($obj);

                if ($ret === FALSE) {
                    $_SESSION["NOTICE"] = "add_note_failed";
                }

                Redirect(base_url() . "cs/refund/account_view/" . $refundid);
            }

            if ($this->input->post('posted')) {
                $amount_change = 0;
                $refund_obj = $this->sc['Refund']->getDao('Refund')->get(["id" => $refundid, "status" => "I"]);

                $deny = $this->input->post('denyitem');
                $refund = $this->input->post('refund');

                $denial = 0;
                $item_arr = [];
                foreach ($deny as $key => $val) {
                    if ($val) {
                        //refund approved
                        $obj = $this->sc['Refund']->getDao('RefundItem')->get(["refund_id" => $refundid, "line_no" => $key]);
                        $org_amount = $obj->getRefundAmount() * $obj->getQty();
                        $obj->setRefundAmount($refund[$key] / $obj->getQty());
                        $obj->setStatus("C");
                        $item_arr[] = $obj;
                        $amount_change += $refund[$key] - $org_amount;
                    } else {
                        //refund denied
                        $obj = $this->sc['Refund']->getDao('RefundItem')->get(["refund_id" => $refundid, "line_no" => $key]);
                        $org_amount = $obj->getRefundAmount() * $obj->getQty();
                        $obj->setStatus("D");
                        $amount_change -= $org_amount;
                        $item_arr[] = $obj;
                        $denial = 1;
                    }
                }


                $hobj = $this->sc['Refund']->getDao('RefundHistory')->get();
                $hobj->setRefundId($refundid);
                if ($denial) {
                    $hobj->setStatus("AC");
                    $hobj->setAppStatus("D");
                } else {
                    $hobj->setStatus("C");
                    $hobj->setAppStatus("A");
                }
                $hobj->setNotes($this->input->post("rnotes"));

                $err = 0;

                $this->sc['Refund']->getDao('Refund')->db->trans_start();

                foreach ($item_arr as $obj) {
                    $ret = $this->sc['Refund']->getDao('RefundItem')->update($obj);
                    if ($ret === FALSE) {
                        $err++;
                        break;
                    }
                }

                $ret = $this->sc['Refund']->getDao('RefundHistory')->insert($hobj);

                if ($ret === FALSE) {
                    $err++;
                }

                if (($amount_change != 0) && (!(abs($amount_change) <= 0.01))) {
                    $refund_obj = $this->sc['Refund']->getDao('Refund')->get(["id" => $refundid]);
                    $refund_obj->setTotalRefundAmount($refund_obj->getTotalRefundAmount() + $amount_change);
                    $ret = $this->sc['Refund']->getDao('Refund')->update($refund_obj);

                    if ($ret === FALSE) {
                        $err++;
                    }
                }

                if (!$err) {
                    if ($denial) {
                        $so_obj = $this->sc['So']->getDao('So')->get(["so_no" => $refund_obj->getSoNo()]);
                        $so_obj->setRefundStatus(0);
                        if (($so_obj->getStatus() != 1) || ($so_obj->getStatus() != 6)) {
                            $so_obj->setHoldStatus(1);
                        }
                        $ret = $this->sc['So']->getDao('So')->update($so_obj);
                        if ($ret) {
                            if (($so_obj->getStatus() != 1) || ($so_obj->getStatus() != 6)) {
                                if ($so_hold_reason_obj = $this->sc['So']->getDao('SoHoldReason')->get()) {
                                    $holdrea = clone $so_hold_reason_obj;
                                    $holdrea->setSoNo($so_obj->getSoNo());
                                    $holdrea->setReason('Confirmation Required');
                                    $this->sc['So']->getDao('SoHoldReason')->insert($holdrea);
                                }
                            }
                        }
                        $this->sc['Refund']->checkAction($refundid, "D");
                    } else {
                        if ($this->input->post("auto_refund"))
                            $auto_refund = true;
                        else
                            $auto_refund = false;
                        $ret = $this->sc['Refund']->checkAction($refundid, "A", $auto_refund);
                    }

                    if ($ret === FALSE) {
                        $err++;
                    }
                }

                if ($err) {
                    $this->sc['Refund']->getDao('Refund')->db->trans_rollback();
                    $this->sc['Refund']->getDao('Refund')->db->trans_complete();
                    $_SESSION["NOTICE"] = "update_fail";
                } else {
                    $this->sc['Refund']->getDao('Refund')->db->trans_complete();
                    // hook , record yandex refund order to flex_refund table
                    $this->yandex_refund_order_to_flex_refund($refund_obj);

                    Redirect(base_url() . "cs/refund/account/");
                }
            }

            $refund_obj = $this->sc['Refund']->getDao('Refund')->get(["id" => $refundid, "status" => "I"]);

            if ($refund_obj->getId() == "") {
                Redirect(base_url() . "cs/refund/account/");
            }

            $history_list = $this->sc['Refund']->getDao('RefundHistory')->getHistoryList(["so_no" => $refund_obj->getSoNo()]);
            $order_item_list = $this->sc['So']->getDao('SoItemDetail')->getListWithProdname(["so_no" => $refund_obj->getSoNo()]);
            $item_list = $this->sc['Refund']->getDao('RefundItem')->getListWithName(["ri.refund_id" => $refundid, "ri.status <>" => 'D'], ["sortby" => "line_no ASC"]);

            $data["history"] = $history_list;
            $data["itemlist"] = $item_list;
            $data["order_item_list"] = $order_item_list;
            $data["orderobj"] = $this->sc['So']->getDao('So')->get(["so_no" => $refund_obj->getSoNo()]);
            $data["refund_obj"] = $refund_obj;
            $langfile = $this->getAppId() . "09_" . $this->getLangId() . ".php";
            include_once APPPATH . "language/" . $langfile;

            $reasonlist = $this->sc['Refund']->getReasonList([], []);

            $data["can_do_auto_refund"] = $this->sc['Refund']->maybeRequireToDoAutoRefund($data["orderobj"]);

            $data["notice"] = notice($lang);
            $data["reason"] = $reasonlist;
            $data["isCod"] = $this->_view_cod_order_highlight($refund_obj->getSoNo());
            $data["lang"] = $lang;

            $data["order_note"] = $this->sc['So']->getDao('OrderNotes')->getListWithName(["so_no" => $refund_obj->getSoNo(), "type" => "O"]);

            $this->load->view('cs/refund/view_account', $data);
        } else {
            show_error("Access Denied!");
        }
    }

    public function yandex_refund_order_to_flex_refund($refund_obj)
    {
        $where = ['so.so_no' => $refund_obj->getSoNo()];
        $temp_obj = $this->sc['So']->orderQuickSearch($where, $option = array('limit' => 1));

        foreach ($temp_obj as $obj) {
            if ($obj->getPaymentGatewayName() === 'Yandex') {

                try {
                    $result = $this->sc['Flex']->platfromOrderInsertFlexRefund('yandex', $refund_obj);
                    if ($result) {
                        mail('brave.liu@eservicesgroup.com', '[Panther]-Yandex flex refund insert success', 'so_no : ' . $refund_obj->getSoNo(), 'From: website@valuebasket.com');
                    } else {
                        mail('brave.liu@eservicesgroup.com', '[Panther]-Yandex flex refund insert failed', 'so_no : ' . $refund_obj->getSoNo(), 'From: website@valuebasket.com');
                    }
                } catch (Exception $e) {
                    mail('brave.liu@eservicesgroup.com', '[Panther]-Yandex flex refund failed', $e->getMessage(), 'From: website@valuebasket.com');
                }
            }
        }
    }
}

?>