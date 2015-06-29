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
    private $app_id = 'CS0002';
    private $lang_id = 'en';

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url', 'notice', 'object', 'operator'));
        $this->load->model('cs/refund_model');
        $this->load->model('cs/quick_search_model');
        $this->load->library('service/pagination_service');
        $this->load->library('service/event_service');
        //SBF 2607
        $this->load->library('service/so_refund_score_service');
        //$this->load->library('service/authorization_service');
        $this->load->library('service/flex_service');
        $this->load->library('dao/so_hold_reason_dao');

    }

    public function index()
    {
        //$sub_app_id = $this->_get_app_id()."00";
        //$this->authorization_service->check_access_rights($sub_app_id, "List");


        $langfile = $this->_get_app_id() . "00_" . $this->_get_lang_id() . ".php";
        include_once APPPATH . "language/" . $langfile;
        $data["lang"] = $lang;
        $data["app_id"] = $this->_get_app_id();
        $this->load->view('cs/refund/index', $data);
    }

    public function _get_app_id()
    {
        return $this->app_id;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }

    public function reason($id = "")
    {
        if (check_app_feature_access_right($this->_get_app_id(), "CS000200_refund_btn")) {
            if ($this->input->post('posted')) {
                if ($this->input->post('action') == 'add') {
                    $reason_obj = $this->refund_model->get_reason();
                    $reason_obj->set_reason_cat($this->input->post('r_cat'));
                    $reason_obj->set_description($this->input->post('r_desc'));

                    $ret = $this->refund_model->add_reason($reason_obj);
                    echo $this->db->_error_message();
                    if ($ret === FALSE) {
                        $_SESSION["NOTICE"] = "failed_to_add_reason";
                    }
                } else if ($this->input->post('action') == 'edit') {
                    $reason_obj = $this->refund_model->get_reason(array('id' => $this->input->post('id')));
                    $reason_obj->set_reason_cat($this->input->post('ecat'));
                    $reason_obj->set_description($this->input->post('edesc'));

                    $ret = $this->refund_model->update_reason($reason_obj);

                    if ($ret === FALSE) {
                        $_SESSION["NOTICE"] = "failed_to_update_reason";
                    }
                } elseif ($this->input->post('action') == 'delete') {
                    $reason_obj = $this->refund_model->get_reason(array('id' => $this->input->post('id')));
                    $reason_obj->set_status(0);

                    $ret = $this->refund_model->update_reason($reason_obj);

                    if ($ret === FALSE) {
                        $_SESSION["NOTICE"] = "failed_to_delete_reason";
                    }
                }
            }

            $_SESSION["LISTPAGE"] = base_url() . "cs/refund/reason?" . $_SERVER['QUERY_STRING'];

            $langfile = $this->_get_app_id() . "01_" . $this->_get_lang_id() . ".php";
            include_once APPPATH . "language/" . $langfile;

            $where["status"] = 1;
            if ($this->input->get("cat") != "") {
                $where["reason_cat"] = $this->input->get("cat");
            }

            if ($this->input->get("desc") != "") {
                $where["description LIKE "] = '%' . $this->input->get("desc") . '%';
            }

            $sort = $this->input->get("sort");
            $order = $this->input->get("order");

            $pconfig['base_url'] = base_url() . "cs/refund/reason";
            $option["limit"] = $pconfig['per_page'] = 20;
            if ($option["limit"]) {
                $option["offset"] = $this->input->get("per_page");
            }

            if (empty($sort))
                $sort = "id";

            if (empty($order))
                $order = "asc";

            $option["orderby"] = $sort . " " . $order;

            $data = $this->refund_model->get_reason_list($where, $option);
            $data["lang"] = $lang;
            $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
            $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
            $option["orderby"] = $sort . " " . $order;

            $pconfig['total_rows'] = $data['cnt'];
            $this->pagination_service->set_show_count_tag(TRUE);
            $this->pagination_service->initialize($pconfig);

            $data["notice"] = notice($lang);

            if ($id != "") {
                $data["action"] = "edit";
                $_SESSION["refund_reason"] = serialize($this->refund_model->get_reason(array("id" => $id)));
                $data["eid"] = $id;
            }
            $data["app_id"] = $this->_get_app_id();
            $this->load->view('cs/refund/index_reason', $data);
        } else {
            show_error("Access Denied!");
        }
    }

    public function create()
    {
        //$sub_app_id = $this->_get_app_id()."02";
        //$this->authorization_service->check_access_rights($sub_app_id, "List");

        $where = array();
        $option = array();

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

            $option["limit"] = $pconfig['per_page'] = 20;

            if ($option["limit"]) {
                $option["offset"] = $this->input->get("per_page");
            }

            $_SESSION["LISTPAGE"] = base_url() . "cs/refund/create?" . $_SERVER['QUERY_STRING'];

            $option["orderby"] = $sort . " " . $order;

            $data = $this->refund_model->get_order_list($where, $option);
            $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
            $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
            $option["orderby"] = $sort . " " . $order;

            $pconfig['base_url'] = $_SESSION["LISTPAGE"];
            $pconfig['total_rows'] = $data['total'];
            $this->pagination_service->set_show_count_tag(TRUE);
            $this->pagination_service->initialize($pconfig);
        }

        $langfile = $this->_get_app_id() . "02_" . $this->_get_lang_id() . ".php";
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

        //$sub_app_id = $this->_get_app_id()."03";
        //$this->authorization_service->check_access_rights($sub_app_id, "Edit");

        if ($this->input->post('posted')) {
            $refund_obj = $this->refund_model->get_refund();
            $refund_item_obj = $this->refund_model->get_refund_item();
            $refund_history_obj = $this->refund_model->get_refund_history();
            $refund = $this->input->post('refund');

            if ($refund) {
                $ro_list = array();

                $rqty = $this->input->post('rqty');
                $ramount = $this->input->post('ramount');
                $rsku = $this->input->post('rsku');
                foreach ($refund as $key => $obj) {
                    $tmp = clone $refund_item_obj;
                    $tmp->set_item_sku($rsku[$key]);
                    $tmp->set_qty($rqty[$key]);
                    if ($rqty[$key] > 0)
                        $tmp->set_refund_amount($ramount[$key] / $rqty[$key]);
                    else
                        $tmp->set_refund_amount(0);
                    $tmp->set_status('N');
                    $tmp->set_refund_type('R');

                    $ro_list[] = $tmp;
                }
            }

            $status = 1;
            if ($this->input->post('cashback') > 0) {
                $tmp = clone $refund_item_obj;
                $tmp->set_item_sku("");
                $tmp->set_qty(1);
                $tmp->set_refund_amount($this->input->post('cashback'));
                $tmp->set_status('N');
                $tmp->set_refund_type('C');
                $status = 2;


                $ro_list[] = $tmp;
            }

            $refund_history_obj->set_status('N');
            $refund_history_obj->set_notes($this->input->post('rnotes'));


            $refund_obj->set_so_no($orderid);
            $refund_obj->set_status('I');
            $refund_obj->set_total_refund_amount($this->input->post('total'));
            $refund_obj->set_reason($this->input->post("reason"));

            $err = 0;

            if (count($ro_list)) {
                $success = 1;
                $this->refund_model->_trans_start();

                $so_obj = $this->refund_model->get_so(array("so_no" => $orderid));
                //added by Jack for bypassing refund

                if ($so_obj->get_status() > 3 && $so_obj->get_status() < 6) {
                    $status = 1;
                } else {
                    $status = 2;
                }

                //end added by Jack
                $so_obj->set_refund_status($status);
                if ($this->refund_model->update_so($so_obj) !== FALSE) {

                    if ($result = $this->refund_model->insert_refund($refund_obj)) {
                        $refund_id = $result->get_id();

                        foreach ($ro_list as $key => $obj) {
                            $obj->set_refund_id($refund_id);
                            if ($status == 2) {
                                $obj->set_status('LG');
                            }
                            $obj->set_line_no($key + 1);

                            if (!$this->refund_model->insert_refund_item($obj)) {
                                $success = 0;
                                $_SESSION["NOTICE"] = "ERROR: @" . __LINE__ . " " . $this->db->_error_message();
                                $this->refund_service->get_dao()->trans_rollback();
                                break;
                            }
                        }

                        if ($success) {
                            $refund_history_obj->set_refund_id($refund_id);
                            $refund_history_obj->set_app_status('A');

                            if (!$this->refund_model->insert_refund_history($refund_history_obj)) {
                                $success = 0;
                                $_SESSION["NOTICE"] = "ERROR: @" . __LINE__ . " " . $this->db->_error_message();
                                $this->refund_service->get_dao()->trans_rollback();
                            } else {
                                // if current so_obj is a split child, find its parent and update refund status
                                // get_split_so_group() gives the parent so_no that it belongs to
                                if ($split_so_group = $so_obj->get_split_so_group()) {
                                    if ($split_so_group && ($so_obj->get_so_no() != $split_so_group)) {
                                        if ($split_parent_obj = $this->refund_model->get_so(array("so_no" => $split_so_group))) {
                                            $split_parent_obj->set_refund_status($status);
                                            if ($this->refund_model->update_so($split_parent_obj) === FALSE) {
                                                $_SESSION["NOTICE"] = "ERROR: @" . __LINE__ . " Error update split parent so_no $split_so_group. " . $this->db->_error_message();
                                            }

                                        }
                                    }
                                }
                            }

                            //SBF #2607 add the default refund score when order funded
                            $this->so_refund_score_service->insert_initial_refund_score($orderid);
                        }
                    } else {
                        $success = 0;
                        $_SESSION["NOTICE"] = "ERROR: @" . __LINE__ . " " . $this->db->_error_message();
                        $this->refund_service->get_dao()->trans_rollback();
                    }
                } else {
                    $success = 0;
                    $_SESSION["NOTICE"] = "ERROR: @" . __LINE__ . " " . $this->db->_error_message();
                    $this->refund_service->get_dao()->trans_rollback();
                }

                $this->refund_model->_trans_complete();

                if ($success) {
                    $this->refund_model->fire_email($refund_id, $status, "approve", "cs");
                    Redirect(base_url() . "cs/refund/create/");
                }

            } else {
                $_SESSION["NOTICE"] = "Cannot_create_empty_refund";
            }
        }

        $history_list = $this->refund_model->get_history_list(array("so_no" => $orderid));

        $item_list = $this->refund_model->get_item_list(array("so_no" => $orderid));

        $data["history"] = $history_list;
        $data["itemlist"] = $item_list;
        $data["itemcnt"] = count((array)$data["itemlist"]);
        // $data["orderobj"] = $so = $this->refund_model->get_so(array("so_no"=>$orderid, "status >"=>1, "refund_status"=>0));
        $data["orderobj"] = $so = $this->refund_model->get_so(array("so_no" => $orderid, "refund_status" => 0, "status > " => 2));

        $split_child_list_html = "";
        $split_so_group = $so->get_split_so_group();
        if ($split_so_group) {
            // get list of other refundable split child orders in the same group
            if ($split_group_list = $this->refund_model->get_so_list(array("split_so_group" => $split_so_group, "refund_status" => 0, "status > " => 2))) {
                foreach ($split_group_list as $key => $childobj) {
                    $split_child_list_html .= "<a href='" . base_url() . "cs/refund/create_view/{$childobj->get_so_no()}'> >> {$childobj->get_so_no()}<br>";
                }
            }
        }
        $data["split_child_list_html"] = $split_child_list_html;

        if (!count($so)) {
            Redirect(base_url() . "cs/refund/create/");
            exit;
        }

        $data["refund"] = $this->refund_model->get_refund(array("so_no" => $orderid, "status" => "I"), array("orderby" => "create_on DESC", "limit" => 1));

        $langfile = $this->_get_app_id() . "03_" . $this->_get_lang_id() . ".php";
        include_once APPPATH . "language/" . $langfile;

        $reasonlist = $this->refund_model->get_reason_list(array("status" => 1), array("orderby" => "reason_cat , id"));

        $data["reason"] = $reasonlist;
        $data["lang"] = $lang;
        $data["orderid"] = $orderid;
        $data["notice"] = notice($lang);

        $this->load->view('cs/refund/view_create', $data);
    }

    public function logistics()
    {
        if (check_app_feature_access_right($this->_get_app_id(), "CS000200_log_btn")) {
            $where = array();
            $option = array();

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

            $option["limit"] = $pconfig['per_page'] = 20;

            if ($option["limit"]) {
                $option["offset"] = $this->input->get("per_page");
            }

            $_SESSION["LISTPAGE"] = base_url() . "cs/refund/logistics?" . $_SERVER['QUERY_STRING'];
            $_SESSION["QUERY_STRING"] = $_SERVER['QUERY_STRING'];

            $option["orderby"] = $sort . " " . $order;

            $langfile = $this->_get_app_id() . "04_" . $this->_get_lang_id() . ".php";
            include_once APPPATH . "language/" . $langfile;

            $data = $this->refund_model->get_refund_so_list($where, $option);

            $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
            $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
            $option["orderby"] = $sort . " " . $order;

            $pconfig['base_url'] = $_SESSION["LISTPAGE"];
            $pconfig['total_rows'] = $data['total'];
            $this->pagination_service->set_show_count_tag(TRUE);
            $this->pagination_service->initialize($pconfig);

            $data["notice"] = notice($lang);
            $data["lang"] = $lang;

            $this->load->view('cs/refund/index_logistics', $data);
        } else {
            show_error("Access Denied!");
        }
    }

    public function logistics_view($refundid = "")
    {
        if (check_app_feature_access_right($this->_get_app_id(), "CS000200_log_btn")) {
            if ($refundid == "") {
                Redirect(base_url() . "cs/refund/logistics/");
            }

            //$sub_app_id = $this->_get_app_id()."05";
            //$this->authorization_service->check_access_rights($sub_app_id, "Edit");

            if ($this->input->post('posted')) {
                $amount_change = 0;
                $refund_obj = $this->refund_model->get_refund(array("id" => $refundid, "status" => "I"));

                $deny = $this->input->post('denyitem');
                $istatus = $this->input->post('ritem');
                $sbdate = $this->input->post('sbdate');
                $sbwh = $this->input->post('sbwh');
                $item_arr = array();

                $denial = 0;
                $appr = 0;
                foreach ($deny as $key => $val) {
                    if ($val) {
                        //refund approved
                        $obj = $this->refund_model->get_refund_item(array("refund_id" => $refundid, "line_no" => $key));
                        $obj->set_item_status($istatus[$key]);
                        $obj->set_status("LG");
                        list($day, $month, $year) = explode("/", $sbdate[$key]);
                        $obj->set_stockback_date(date("Y-m-d", strtotime("$year-$month-$day")));
                        $obj->set_stockback_warehouse($sbwh[$key]);
                        $item_arr[] = $obj;

                        $appr++;
                    } else {
                        //refund denied
                        $obj = $this->refund_model->get_refund_item(array("refund_id" => $refundid, "line_no" => $key));
                        $obj->set_item_status($istatus[$key]);
                        $obj->set_status("D");
                        $amount_change += $obj->get_refund_amount();
                        $item_arr[] = $obj;

                        $denial++;
                    }
                }

                $hobj = $this->refund_model->get_refund_history();
                $hobj->set_refund_id($refundid);
                $hobj->set_status("LG");
                $hobj->set_notes($this->input->post("rnotes"));

                $err = 0;


                $this->refund_model->_trans_start();

                foreach ($item_arr as $obj) {
                    $ret = $this->refund_model->update_refund_item($obj);
                    if ($ret === FALSE) {
                        $err++;
                        break;
                    }
                }

                //$this->refund_model->check_action($refund_id,"cs");
                $refund_obj = $this->refund_model->get_refund(array("id" => $refundid));

                if ($amount_change > 0) {

                    $refund_obj->set_total_refund_amount($refund_obj->get_total_refund_amount() - $amount_change);
                    $ret = $this->refund_model->update_refund($refund_obj);

                    if ($ret === FALSE) {
                        $err++;
                    }
                }

                $type = "";
                $so_obj = $this->refund_model->get_so(array("so_no" => $refund_obj->get_so_no()));
                if ($denial && !$appr) {
                    $status = 0;
                    $type = "deny";
                    $app_status = 'D';
                    if (($so_obj->get_status() != 1) || ($so_obj->get_status() != 6)) {
                        $hold = '1';
                    }
                } else {
                    $status = 2;
                    $type = "approve";
                    if ($denial) {
                        $app_status = 'AD';
                        if (($so_obj->get_status() != 1) || ($so_obj->get_status() != 6)) {
                            $hold = '1';
                        }
                    } else {
                        $app_status = 'A';
                    }
                }
                if ($hold == '1') {
                    $so_obj->set_hold_status($hold);
                }
                $so_obj->set_refund_status($status);
                $ret = $this->refund_model->update_so($so_obj);
                if ($ret) {
                    if ($hold == '1') {
                        if ($so_hold_reason_obj = $this->so_hold_reason_dao->get()) {
                            $holdrea = clone $so_hold_reason_obj;
                            $holdrea->set_so_no($so_obj->get_so_no());
                            $holdrea->set_reason('Confirmation Required');
                            $this->so_hold_reason_dao->insert($holdrea);
                        }
                    }
                }
                if ($ret === FALSE) {
                    $err++;
                }

                $hobj->set_app_status($app_status);
                $ret = $this->refund_model->insert_refund_history($hobj);

                if ($ret === FALSE) {
                    $err++;
                }

                $this->refund_model->check_action($refundid, "LG");
                $this->refund_model->_trans_complete();


                if ($err) {
                    $_SESSION["NOTICE"] = "update_fail";
                } else {
                    $this->refund_model->fire_email($refundid, 2, $type, "log");
                    Redirect(base_url() . "cs/refund/logistics");
                }
            }


            $refund_obj = $this->refund_model->get_refund(array("id" => $refundid, "status" => "I"));

            if ($refund_obj->get_id() == "") {
                Redirect(base_url() . "cs/refund/logistics/");
            }

            $history_list = $this->refund_model->get_history_list(array("so_no" => $refund_obj->get_so_no()));
            $order_item_list = $this->refund_model->get_item_list(array("so_no" => $refund_obj->get_so_no()));

            $item_list = $this->refund_model->get_refund_item_list(array("ri.refund_id" => $refundid, "ri.refund_type" => "R"), array("sortby" => "line_no ASC"));
            $data["history"] = $history_list;
            $data["itemlist"] = $item_list;
            $data["order_item_list"] = $order_item_list;
            $data["orderobj"] = $this->refund_model->get_so(array("so_no" => $refund_obj->get_so_no()));
            $data["refund_obj"] = $refund_obj;
            $langfile = $this->_get_app_id() . "05_" . $this->_get_lang_id() . ".php";
            include_once APPPATH . "language/" . $langfile;

            $reasonlist = $this->refund_model->get_reason_list(array(), array());

            $data["reason"] = $reasonlist;
            $data["lang"] = $lang;
            $data["isCod"] = $this->_view_cod_order_highlight($refund_obj->get_so_no());

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
        if ($this->refund_model->is_cod_order($so_no)) {
            return true;
        } else
            return false;
    }

    public function export_cs_csv()
    {
        if (check_app_feature_access_right($this->_get_app_id(), "CS000200_cs_btn")) {
            list($where, $option) = $this->build_cs_query();
            $option["limit"] = -1;
            $data = $this->refund_model->get_refund_so_list($where, $option);

            // var_dump($data); die();
            $filename = "refund_report.csv";
            $output = "ID,SO#,platform_id,payment_gateway,platform_id,amount,order_date,dispatch_date,create_on,create_by,special_order,refund_reason,refund_score,refund_score_date,pack_date\r\n";
            foreach ($data["list"] as $obj) {
                $special_order = "";
                if ($obj->get_special_order() != 0) $special_order = "Y";

                $line = "{$obj->get_id()},
                    {$obj->get_so_no()},
                    {$obj->get_platform_order_id()},
                    {$obj->get_payment_gateway()},
                    {$obj->get_platform_id()},
                    {$obj->get_currency_id()} {$obj->get_total_refund_amount()},
                    {$obj->get_order_date()},
                    {$obj->get_dispatch_date()},
                    {$obj->get_create_on()},
                    {$obj->get_create_by()},
                    $special_order,
                    {$obj->get_refund_reason()},
                    {$obj->get_refund_score()},
                    {$obj->get_refund_score_date()},
                    {$obj->get_pack_date()}";

                $line = str_replace("\n", "", $line);
                $line .= "\r\n";

                $output .= $line;
            }

            header("Content-type: application/vnd.ms-excel");
            header("Content-disposition: filename=$filename");
            echo $output;
        }
    }

    private function build_cs_query()
    {
        $where["rstatus"] = "LG";
        $where["check_cb"] = 1;

        if ($this->input->get('rid') != "")
            $where["rid"] = $this->input->get('rid');

        if ($this->input->get('so') != "")
            $where["so"] = $this->input->get('so');

        if ($this->input->get('platform_id') != "")
            $where["platform_id"] = $this->input->get('platform_id');

        if ($this->input->get('platform_order_id') != "")
            $where["platform_order_id"] = $this->input->get('platform_order_id');

        if ($this->input->get('payment_gateway') != "")
            $where["payment_gateway"] = $this->input->get('payment_gateway');

        $sort = $this->input->get('sort');
        if ($sort == "")
            $sort = "r.id";

        $order = $this->input->get('order');
        if (empty($order))
            $order = "asc";

        $option["need_pack_date"] = True;
        $option["limit"] = $pconfig['per_page'] = 20;

        if ($option["limit"])
            $option["offset"] = $this->input->get("per_page");

        $_SESSION["LISTPAGE"] = base_url() . "cs/refund/cs?" . $_SERVER['QUERY_STRING'];
        $_SESSION["QUERY_STRING"] = $_SERVER['QUERY_STRING'];

        $option["orderby"] = $sort . " " . $order;

        return array($where, $option);
    }

    public function cs()
    {
        if (check_app_feature_access_right($this->_get_app_id(), "CS000200_cs_btn")) {
            $q = "";
            foreach ($_GET as $k => $v)
                $q .= "&$k=" . urlencode($v);

            $langfile = $this->_get_app_id() . "06_" . $this->_get_lang_id() . ".php";
            include_once APPPATH . "language/" . $langfile;

            list($where, $option) = $this->build_cs_query();
            $data = $this->refund_model->get_refund_so_list($where, $option);

            $sort = $this->input->get("sort");
            $order = $this->input->get("order");

            if (empty($sort))
                $sort = "id";

            if (empty($order))
                $order = "asc";

            $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
            $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
            $option["orderby"] = $sort . " " . $order;

            $pconfig['base_url'] = $_SESSION["LISTPAGE"];
            $pconfig['total_rows'] = $data['total'];
            $this->pagination_service->set_show_count_tag(TRUE);
            $this->pagination_service->initialize($pconfig);

            $data["notice"] = notice($lang);
            $data["lang"] = $lang;
            $data["q"] = $q;

            // $obj = $this->refund_model->get_refund(array("id"=>5));
            $this->load->view('cs/refund/index_cs', $data);
        } else {
            show_error("Access Denied!");
        }
    }

    public function cs_view($refundid = "")
    {
        if (check_app_feature_access_right($this->_get_app_id(), "CS000200_cs_btn")) {
            if ($refundid == "") {
                Redirect(base_url() . "cs/refund/cs/");
                exit;
            }

            //$sub_app_id = $this->_get_app_id()."07";
            //$this->authorization_service->check_access_rights($sub_app_id, "Edit");

            if ($this->input->post('posted')) {
                $amount_change = 0;
                $refund_obj = $this->refund_model->get_refund(array("id" => $refundid, "status" => "I"));

                $deny = $this->input->post('denyitem');
                $refund = $this->input->post('refund');


                $denial = 0;
                $appr = 0;
                $item_arr = array();
                foreach ($deny as $key => $val) {
                    if ($val) {
                        //refund approved
                        $obj = $this->refund_model->get_refund_item(array("refund_id" => $refundid, "line_no" => $key));
                        $org_amount = $obj->get_refund_amount() * $obj->get_qty();
                        $obj->set_refund_amount($refund[$key] / $obj->get_qty());
                        $obj->set_status("CS");
                        $item_arr[] = $obj;
                        $amount_change += $refund[$key] - $org_amount;

                        $appr++;
                    } else {
                        //refund denied
                        $obj = $this->refund_model->get_refund_item(array("refund_id" => $refundid, "line_no" => $key));
                        $org_amount = $obj->get_refund_amount() * $obj->get_qty();
                        $obj->set_status("D");
                        $amount_change -= $org_amount;
                        $item_arr[] = $obj;

                        $denial++;
                    }
                }

                $hobj = $this->refund_model->get_refund_history();
                $hobj->set_refund_id($refundid);
                $hobj->set_status("CS");
                $hobj->set_notes($this->input->post("rnotes"));

                $err = 0;


                $this->refund_model->_trans_start();

                foreach ($item_arr as $obj) {
                    $ret = $this->refund_model->update_refund_item($obj);
                    if ($ret === FALSE) {
                        $err++;
                        break;
                    }
                }

                $refund_obj = $this->refund_model->get_refund(array("id" => $refundid));

                if (($amount_change != 0) && (!(abs($amount_change) <= 0.01))) {
                    $refund_obj = $this->refund_model->get_refund(array("id" => $refundid));
                    $refund_obj->set_total_refund_amount($refund_obj->get_total_refund_amount() + $amount_change);
                    $ret = $this->refund_model->update_refund($refund_obj);

                    if ($ret === FALSE) {
                        $err++;
                    }
                }

                $so_obj = $this->refund_model->get_so(array("so_no" => $refund_obj->get_so_no()));
                if ($denial && !$appr) {
                    $status = 0;
                    $app_status = 'D';
                    if (($so_obj->get_status() != 1) || ($so_obj->get_status() != 6)) {
                        $hold = '1';
                    }
                } else {
                    $status = 3;
                    if ($denial) {
                        $app_status = 'AD';
                        if (($so_obj->get_status() != 1) || ($so_obj->get_status() != 6)) {
                            $hold = '1';
                        }
                    } else {
                        $app_status = 'A';
                    }
                }
                if ($hold == '1') {
                    $so_obj->set_hold_status($hold);
                }
                $so_obj->set_refund_status($status);
                $ret = $this->refund_model->update_so($so_obj);
                $this->refund_model->check_action($refundid, "CS");

                $hobj->set_app_status($app_status);
                $ret = $this->refund_model->insert_refund_history($hobj);

                $split_so_group = $so_obj->get_split_so_group();
                if ($split_so_group) {
                    $split_parent_obj = $this->refund_model->get_so(array("so_no" => $split_so_group));
                    $split_parent_obj->set_refund_status($status);
                    $ret = $this->refund_model->update_so($split_parent_obj);
                }

                if ($ret) {
                    if ($hold == '1') {
                        if ($so_hold_reason_obj = $this->so_hold_reason_dao->get()) {
                            $holdrea = clone $so_hold_reason_obj;
                            $holdrea->set_so_no($so_obj->get_so_no());
                            $holdrea->set_reason('Confirmation Required');
                            $this->so_hold_reason_dao->insert($holdrea);
                        }
                    }
                }

                if ($ret === FALSE) {
                    $err++;
                }


                $this->refund_model->_trans_complete();

                if ($err) {
                    $_SESSION["NOTICE"] = "update_fail";
                } else {
                    Redirect(base_url() . "cs/refund/cs");
                    exit;
                }
            }

            $refund_obj = $this->refund_model->get_refund(array("id" => $refundid, "status" => "I"));

            if ($refund_obj->get_id() == "") {
                Redirect(base_url() . "cs/refund/cs/");
                exit;
            }

            $history_list = $this->refund_model->get_history_list(array("so_no" => $refund_obj->get_so_no()));
            $order_item_list = $this->refund_model->get_item_list(array("so_no" => $refund_obj->get_so_no()));
            $item_list = $this->refund_model->get_refund_item_list(array("ri.refund_id" => $refundid, "ri.status <>" => 'D'), array("sortby" => "line_no ASC"));

            $data["history"] = $history_list;
            $data["itemlist"] = $item_list;
            $data["order_item_list"] = $order_item_list;
            $data["orderobj"] = $this->refund_model->get_so(array("so_no" => $refund_obj->get_so_no()));
            $data["refund_obj"] = $refund_obj;
            $langfile = $this->_get_app_id() . "07_" . $this->_get_lang_id() . ".php";
            include_once APPPATH . "language/" . $langfile;

            $reasonlist = $this->refund_model->get_reason_list(array(), array());

            $data["reason"] = $reasonlist;
            $data["isCod"] = $this->_view_cod_order_highlight($refund_obj->get_so_no());
            $data["lang"] = $lang;


            $this->load->view('cs/refund/view_cs', $data);
        } else {
            show_error("Access Denied!");
        }
    }

    // record yandex refund order to flex_refund

    public function account()
    {
        if (check_app_feature_access_right($this->_get_app_id(), "CS000200_acc_btn")) {
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
            if ($sort == "")
                $sort = "r.id";

            $order = $this->input->get('order');
            if (empty($order))
                $order = "asc";

            $option["limit"] = $pconfig['per_page'] = 20;

            if ($option["limit"])
                $option["offset"] = $this->input->get("per_page");

            $_SESSION["LISTPAGE"] = base_url() . "cs/refund/account?" . $_SERVER['QUERY_STRING'];
            $_SESSION["QUERY_STRING"] = $_SERVER['QUERY_STRING'];

            $option["orderby"] = $sort . " " . $order;

            $sortField = array('multi_sort_rs', 'multi_sort_pg', 'multi_sort_od', 'multi_sort_rsd');

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

            $langfile = $this->_get_app_id() . "08_" . $this->_get_lang_id() . ".php";
            include_once APPPATH . "language/" . $langfile;
            $option["limit"] = $pconfig['per_page'] = 5000;
            $data = $this->refund_model->get_refund_so_list($where, $option);

            $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
            $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
            $option["orderby"] = $sort . " " . $order;

            $pconfig['base_url'] = $_SESSION["LISTPAGE"];
            $pconfig['total_rows'] = $data['total'];
            $this->pagination_service->set_show_count_tag(TRUE);
            $this->pagination_service->initialize($pconfig);

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
        if (check_app_feature_access_right($this->_get_app_id(), "CS000200_acc_btn")) {

            if ($refundid == "") {
                Redirect(base_url() . "cs/refund/account/");
                exit;
            }

            if ($this->input->post('addnote') == 1) {
                $obj = $this->quick_search_model->get_order_notes();
                $obj->set_so_no($this->input->post('orderid'));
                $obj->set_type('O');
                $obj->set_note($this->input->post('note'));

                $ret = $this->quick_search_model->add_notes($obj);

                if ($ret === FALSE) {
                    $_SESSION["NOTICE"] = "add_note_failed";
                }

                Redirect(base_url() . "cs/refund/account_view/" . $refundid);
            }

            //$sub_app_id = $this->_get_app_id()."09";
            //$this->authorization_service->check_access_rights($sub_app_id, "Edit");

            if ($this->input->post('posted')) {
                $amount_change = 0;
                $refund_obj = $this->refund_model->get_refund(array("id" => $refundid, "status" => "I"));

                $deny = $this->input->post('denyitem');
                $refund = $this->input->post('refund');

                $denial = 0;
                $item_arr = array();
                foreach ($deny as $key => $val) {
                    if ($val) {
                        //refund approved
                        $obj = $this->refund_model->get_refund_item(array("refund_id" => $refundid, "line_no" => $key));
                        $org_amount = $obj->get_refund_amount() * $obj->get_qty();
                        $obj->set_refund_amount($refund[$key] / $obj->get_qty());
                        $obj->set_status("C");
                        $item_arr[] = $obj;
                        $amount_change += $refund[$key] - $org_amount;
                    } else {
                        //refund denied
                        $obj = $this->refund_model->get_refund_item(array("refund_id" => $refundid, "line_no" => $key));
                        $org_amount = $obj->get_refund_amount() * $obj->get_qty();
                        $obj->set_status("D");
                        $amount_change -= $org_amount;
                        $item_arr[] = $obj;
                        $denial = 1;
                    }
                }


                $hobj = $this->refund_model->get_refund_history();
                $hobj->set_refund_id($refundid);
                if ($denial) {
                    $hobj->set_status("AC");
                    $hobj->set_app_status("D");
                } else {
                    $hobj->set_status("C");
                    $hobj->set_app_status("A");
                }
                $hobj->set_notes($this->input->post("rnotes"));

                $err = 0;

                $this->refund_model->_trans_start();

                foreach ($item_arr as $obj) {
                    $ret = $this->refund_model->update_refund_item($obj);
                    if ($ret === FALSE) {
                        $err++;
                        break;
                    }
                }

                $ret = $this->refund_model->insert_refund_history($hobj);

                if ($ret === FALSE) {
                    $err++;
                }

                if (($amount_change != 0) && (!(abs($amount_change) <= 0.01))) {
                    $refund_obj = $this->refund_model->get_refund(array("id" => $refundid));
                    $refund_obj->set_total_refund_amount($refund_obj->get_total_refund_amount() + $amount_change);
                    $ret = $this->refund_model->update_refund($refund_obj);

                    if ($ret === FALSE) {
                        $err++;
                    }
                }

                if (!$err) {
                    if ($denial) {
                        $so_obj = $this->refund_model->get_so(array("so_no" => $refund_obj->get_so_no()));
                        $so_obj->set_refund_status(0);
                        if (($so_obj->get_status() != 1) || ($so_obj->get_status() != 6)) {
                            $so_obj->set_hold_status(1);
                        }
                        $ret = $this->refund_model->update_so($so_obj);
                        if ($ret) {
                            if (($so_obj->get_status() != 1) || ($so_obj->get_status() != 6)) {
                                if ($so_hold_reason_obj = $this->so_hold_reason_dao->get()) {
                                    $holdrea = clone $so_hold_reason_obj;
                                    $holdrea->set_so_no($so_obj->get_so_no());
                                    $holdrea->set_reason('Confirmation Required');
                                    $this->so_hold_reason_dao->insert($holdrea);
                                }
                            }
                        }
                        $this->refund_model->check_action($refundid, "D");
                    } else {
                        if ($this->input->post("auto_refund"))
                            $auto_refund = true;
                        else
                            $auto_refund = false;
                        $ret = $this->refund_model->check_action($refundid, "A", $auto_refund);
                    }

                    if ($ret === FALSE) {
                        $err++;
                    }
                }

                if ($err) {
                    $this->refund_service->get_dao()->trans_rollback();
                    $this->refund_model->_trans_complete();
                    $_SESSION["NOTICE"] = "update_fail";
                } else {
                    $this->refund_model->_trans_complete();
                    // hook , record yandex refund order to flex_refund table
                    $this->yandex_refund_order_to_flex_refund($refund_obj);

                    Redirect(base_url() . "cs/refund/account/");
                }
            }

            $refund_obj = $this->refund_model->get_refund(array("id" => $refundid, "status" => "I"));

            if ($refund_obj->get_id() == "") {
                Redirect(base_url() . "cs/refund/account/");
            }

            $history_list = $this->refund_model->get_history_list(array("so_no" => $refund_obj->get_so_no()));
            $order_item_list = $this->refund_model->get_item_list(array("so_no" => $refund_obj->get_so_no()));
            $item_list = $this->refund_model->get_refund_item_list(array("ri.refund_id" => $refundid, "ri.status <>" => 'D'), array("sortby" => "line_no ASC"));

            $data["history"] = $history_list;
            $data["itemlist"] = $item_list;
            $data["order_item_list"] = $order_item_list;
            $data["orderobj"] = $this->refund_model->get_so(array("so_no" => $refund_obj->get_so_no()));
            $data["refund_obj"] = $refund_obj;
            $langfile = $this->_get_app_id() . "09_" . $this->_get_lang_id() . ".php";
            include_once APPPATH . "language/" . $langfile;

            $reasonlist = $this->refund_model->get_reason_list(array(), array());

            $data["can_do_auto_refund"] = $this->refund_model->refund_service->maybe_require_to_do_auto_refund($data["orderobj"]);

            $data["notice"] = notice($lang);
            $data["reason"] = $reasonlist;
            $data["isCod"] = $this->_view_cod_order_highlight($refund_obj->get_so_no());
            $data["lang"] = $lang;

            $data["order_note"] = $this->quick_search_model->get_order_notes(array("so_no" => $refund_obj->get_so_no(), "type" => "O"));

            $this->load->view('cs/refund/view_account', $data);
        } else {
            show_error("Access Denied!");
        }
    }

    public function yandex_refund_order_to_flex_refund($refund_obj)
    {
        $where = array('so.so_no' => $refund_obj->get_so_no());
        $temp_obj = $this->quick_search_model->search_order($where, $option = array('limit' => 1));

        foreach ($temp_obj as $obj) {
            if ($obj->get_payment_gateway_name() === 'Yandex') {

                try {
                    $result = $this->flex_service->platfrom_order_insert_flex_refund('yandex', $refund_obj);
                    if ($result) {
                        mail('handy.hon@eservicesgroup.com', '[VB]-Yandex flex refund insert success', 'so_no : ' . $refund_obj->get_so_no(), 'From: website@valuebasket.com');
                    } else {
                        mail('handy.hon@eservicesgroup.com', '[VB]-Yandex flex refund insert failed', 'so_no : ' . $refund_obj->get_so_no(), 'From: website@valuebasket.com');
                    }
                } catch (Exception $e) {
                    mail('handy.hon@eservicesgroup.com', '[VB]-Yandex flex refund failed', $e->getMessage(), 'From: website@valuebasket.com');
                }
            }
        }
    }
}

?>