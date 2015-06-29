<?php

class Special_order extends MY_Controller
{
    private $app_id = "ORD0011";
    private $lang_id = "en";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('marketing/product_model');
        $this->load->model('supply/supplier_model');
        $this->load->model('order/so_model');
        $this->load->model('website/checkout_model');
        $this->load->helper(array('url', 'notice', 'object', 'image'));
        $this->load->library('service/pagination_service');
        $this->load->library('service/context_config_service');
        $this->load->library('service/selling_platform_service');
        $this->load->library('service/platform_biz_var_service');
        $this->load->library('service/order_reason_service');
        $this->load->library('service/so_service');
        $this->load->model('mastercfg/region_model');
        $this->load->library('encrypt');
        $this->load->library('service/supplier_service');
        $this->load->library('service/so_priority_score_service');
        $this->load->library('service/product_service');
        if (isset($_SESSION["cart"]) && $_SESSION["cart_type"] != "SPECIAL") {
            unset($_SESSION["cart"]);
        }
        $_SESSION["cart_type"] = "SPECIAL";
    }

    public function index($platform_type = "", $platform_id = "")
    {
        $sub_app_id = $this->_get_app_id() . "00";
        $_SESSION["LISTPAGE"] = current_url() . "?" . $_SERVER['QUERY_STRING'];

        if ($platform_id) {
            if ($this->input->post("posted")) {
                //var_dump($_POST);
                //exit;
                if (empty($_POST["client"]["id"])) {
                    if (!($client_obj = $this->client_service->get(array("email" => trim($_POST["client"]["email"]))))) {
                        $client_obj = $this->client_service->get();
                        $_POST["client"]["password"] = (trim($_POST["client"]["password"]) != "" ? $this->encrypt->encode(strtolower($_POST["client"]["password"])) : $this->encrypt->encode(mktime()));
                        set_value($client_obj, $_POST["client"]);
                        $client_obj->set_email(trim($_POST["client"]["email"]));
                        $client_obj->set_tel_1($_POST["client"]["tel_1"]);
                        $client_obj->set_tel_2($_POST["client"]["tel_2"]);
                        $client_obj->set_tel_3($_POST["client"]["tel_3"]);
//                      $client_obj->set_mobile($_POST["client"]["mtel_1"].$_POST["client"]["mtel_2"].$_POST["client"]["mtel_3"]);
                        $client_obj->set_del_name($_POST["client"]["title"] . " " . $_POST["client"]["forename"] . " " . $_POST["client"]["surname"]);
                        $client_obj->set_del_company($_POST["client"]["company_name"]);
                        $client_obj->set_del_address_1($_POST["client"]["address_1"]);
                        $client_obj->set_del_address_2($_POST["client"]["address_2"]);
                        $client_obj->set_del_city($_POST["client"]["city"]);
                        $client_obj->set_del_state($_POST["client"]["state"]);
                        $client_obj->set_del_country_id($_POST["client"]["country_id"]);

                        $client_obj->set_party_subscriber(0);
                        $client_obj->set_status(1);
                        if ($client_obj = $this->client_service->insert($client_obj)) {
                            $_POST["client"]["id"] = $client_obj->get_id();
                        } else {
                            $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->db->_error_message();
                        }
                    } else {
                        $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " no such client in db";
//don't allow client update on special order admin
                        /*
                                                $_POST["client"]["id"] = $client_obj->get_id();
                                                $_POST["client"]["password"] = (trim($_POST["client"]["password"]) != ""?$this->encrypt->encode(strtolower($_POST["client"]["password"])):$this->encrypt->encode(mktime()));
                                                set_value($client_obj, $_POST["client"]);
                                                $client_obj->set_email(trim($_POST["client"]["email"]));
                        //                      $client_obj->set_mobile($_POST["client"]["mtel_1"].$_POST["client"]["mtel_2"].$_POST["client"]["mtel_3"]);
                                                if($this->client_service->update($client_obj) === FALSE)
                                                {
                                                    $_SESSION["NOTICE"] = "ERROR ".__LINE__." : ".$this->db->_error_message();
                                                }
                        */
                    }
                } else {
                    $client_obj = $this->client_service->get(array("id" => $_POST["client"]["id"]));
                    /*
                                        $client_obj->set_mobile($_POST["client"]["mtel_1"].$_POST["client"]["mtel_2"].$_POST["client"]["mtel_3"]);
                                        $_POST["client"]["password"] = (trim($_POST["client"]["password"]) != ""?$this->encrypt->encode(strtolower($_POST["client"]["password"])):$this->encrypt->encode(mktime()));
                                        set_value($client_obj, $_POST["client"]);
                                        $client_obj->set_email(trim($_POST["client"]["email"]));
                                        if($this->client_service->update($client_obj) === FALSE)
                                        {
                                            $_SESSION["NOTICE"] = "ERROR ".__LINE__." : ".$this->db->_error_message();
                                        }
                    */
                }
                if (empty($_SESSION["NOTICE"])) {
                    unset($_SESSION["cart"]);
                    unset($_SESSION["cart_type"]);
                    $soi_price = array();
                    foreach ($_POST["soi"] as $soi_chk) {
                        if ($soi_chk['sku'] != "") {
                            $sku = $soi_chk['sku'];
                            $product_obj = $this->product_service->get(array("sku" => $sku));
                            $supplier_prod_obj = $this->product_model->get_supplier_prod(array('prod_sku' => $sku, "order_default" => 1));

                            $supplier = $this->supplier_service->get(array("id" => $supplier_prod_obj->get_supplier_id()));
                            if (($product_obj->get_slow_move_7_days() == 'Y') || ($supplier->get_origin_country() == 'US')) {
                                //$alertmsg = "NOTE : Please note Item sku: ".$sku." ".$product_obj->get_name()." is a SLOW or a US Item.";
                                //$_SESSION["NOTICE"] = "ERROR : Please note Item sku: ".$sku." ".$product_obj->get_name()." is a SLOW or a US Item.";
                                //alert($alertmsg);


                            }
                        }
                    }

                    if ($_POST["soi"]) {
                        foreach ($_POST["soi"] as $rskey => $rsvalue) {
                            if ($_POST["soi"][$rskey]["sku"] != "") {
                                if (!$this->cart_session_service->add_special($_POST["soi"][$rskey]["sku"], $_POST["soi"][$rskey]["qty"], $_POST["soi"][$rskey]["price"], $platform_id)) {
                                    $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $_POST["soi"][$rskey]["sku"] . " failed add to order";
                                } else {
                                    $soi_price[$_POST["soi"][$rskey]["sku"]] = $_POST["soi"][$rskey]["price"];
                                }
                            }
                        }
                    }

                    if (empty($_SESSION["NOTICE"]) && $_POST["new_prod"]) {
                        $prod_vo = $this->product_model->get("product");
                        $price_vo = $this->product_model->get("price");
                        foreach ($_POST["new_prod"] as $new_prod) {
                            if ($new_prod["name"] != "") {
                                if (!($new_obj = $this->product_model->get("product", array("name" => $new_prod["name"])))) {
                                    $prod_obj = clone $prod_vo;
                                    $new_prod["status"] = 1;
                                    $new_prod["rrp"] = $new_prod["archive"] = $new_prod["clearance"] = 0;
                                    $new_prod["cat_id"] = $new_prod["sub_cat_id"] = $new_prod["sub_sub_cat_id"] = 0;
                                    $new_prod["website_status"] = 'I';
                                    $new_prod["sourcing_status"] = 'A';
                                    $new_prod["website_quantity"] = $new_prod["quantity"] = 0;
                                    set_value($prod_obj, $new_prod);

                                    $prod_grp_cd = $this->product_model->seq_next_val();
                                    $data["supp_prod"] = $this->product_model->get_supplier_prod();

                                    $sub_cat_id = $this->input->post("sub_cat_id");
                                    $sub_cat_obj = $this->product_model->get("category", array("id" => $sub_cat_id));

                                    $sku = str_pad($prod_grp_cd . "-{$new_prod["version"]}-NA", 11, "0", STR_PAD_LEFT);
                                    $prod_obj->set_sku($sku)
                                        ->set_prod_grp_cd($prod_grp_cd)
                                        ->set_version_id($new_prod["version"])
                                        ->set_colour_id("NA")
                                        ->set_proc_status('0')
                                        ->set_name($new_prod["name"]);

                                    if ($new_obj = $this->product_model->add("product", $prod_obj)) {
                                        $this->product_model->update_seq($prod_grp_cd);
                                    } else {
                                        $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->db->_error_message();
                                    }
                                }

                                if ($new_obj) {
                                    $item_sku = $new_obj->get_sku();
                                    if (!$this->price_service->get(array("sku" => $item_sku, "platform_id" => $platform_id))) {
                                        $price_obj = clone $price_vo;
                                        $price_obj->set_sku($item_sku);
                                        $price_obj->set_price($new_prod["price"]);
                                        $price_obj->set_platform_id($platform_id);
                                        $price_obj->set_default_shiptype($this->so_service->get_pbv_srv()->get(array("selling_platform_id" => $platform_id))->get_default_shiptype());
                                        if (!$this->price_service->insert($price_obj)) {
                                            $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->db->_error_message();
                                        }
                                    }

                                    $special[] = array("sku" => $item_sku, "name" => $new_prod["name"], "qty" => $new_prod["qty"], "total" => $new_prod["price"] * $new_prod["qty"]);
                                }
                            }
                        }
                    }

                    $vars = $_POST;
                    $parent_so_obj = $this->so_service->get(array("so_no" => $vars["parent_so_no"]));

                    // see if this is a split child. If it is split child, replace with split so group number
                    if ($parent_so_obj->get_split_so_group()) {
                        if ($split_parent_so_no = $parent_so_obj->get_split_so_group()) {
                            $vars["parent_so_no"] = $split_parent_so_no;
                            $vars["split_so_group"] = $split_parent_so_no;
                        }
                    }

                    $vars["client"] = $client_obj;
                    $vars["platform_id"] = $platform_id;
                    $vars["biz_type"] = "special";
                    $vars["special"] = $special;
                    $vars["soi_price"] = $soi_price;
                    $vars["vat_exempt"] = $this->input->post('vat_exempt');
                    //$vars["free_delivery"] = 1;
                    $vars["customized_delivery"] = $this->input->post('delivery_charge');
                    //$vars["delivery"] = "STDPG";

                    if (empty($_SESSION["NOTICE"])) {
                        if ($so_obj = $this->so_service->cart_to_so($vars)) {
                            $reasonid = $_POST['so_extend']["order_reason"];
                            $new_so = $so_obj->get_so_no();
                            $_SESSION["DISPLAY"] = array($so_obj->get_so_no() . " Created Success", "success");

                            if (($reasonid = '19') || ($reasonid = '20') || ($reasonid = '21') || ($reasonid = '22')) {
                                if (!$this->so_priority_score_service->get_priority_score($so_obj->get_so_no())) {
                                    $this->so_priority_score_service->insert_sops($new_so, '1000');
                                    // $this->so_service->permanent_hold_parent_for_aps($parent_so_obj);
                                } else {
                                    $this->so_priority_score_service->update_sops($new_so, '1000');
                                    // $this->so_service->permanent_hold_parent_for_aps($parent_so_obj);
                                }
                            }

                            redirect($_SESSION["LISTPAGE"]);
                        } else {
                            $_SESSION["NOTICE"] = "SO Service " . $_SESSION["NOTICE"];
                        }
                        unset($_SESSION["cart"]);
                        unset($_SESSION["cart_type"]);
                    }
                }
            }

            $data = $this->cart_session_service->get_detail($platform_id, 1, 0, 0, 0, 0, 1, 0);
            $data["country_list"] = $this->region_service->get_sell_country_list();
            $data["pbv_obj"] = $this->platform_biz_var_service->get(array("selling_platform_id" => $platform_id));
            $data["default_curr"] = $data["pbv_obj"]->get_platform_currency_id();
            $data["version_list"] = $this->product_model->get_list("version", array("status" => 'A'));
        }
        $data["order_reason_list"] = $this->order_reason_service->get_list(array("status" => 1, "option_in_special" => 1), array("limit" => -1, "orderby" => "priority"));
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
        $data["lang"] = $lang;
        $data["notice"] = notice($lang, TRUE);
        $data["sp_type_list"] = $this->selling_platform_service->get_platform_type_list();
        $data["sp_list"] = $this->selling_platform_service->get_list(array("type" => $platform_type), array("orderby" => "name", "limit" => -1));
        $data["platform_id"] = $platform_id;
        $data["platform_type"] = $platform_type;
        $data["app_id"] = $this->_get_app_id();
        $this->load->view('order/special_order/special_order_v', $data);
    }

    public function _get_app_id()
    {
        return $this->app_id;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }

    public function prod_list($line = "", $platform_id = "")
    {
        if ($platform_id == "") {
            show_404();
        }

        $sub_app_id = $this->_get_app_id() . "00";
        $_SESSION["LISTPAGE"] = current_url() . "?" . $_SERVER['QUERY_STRING'];

        $where = array();
        $option = array();
        $where["platform_id"] = $platform_id;
        $submit_search = 0;

        if ($this->input->get("sku") != "") {
            $where["sku LIKE "] = "%" . $this->input->get("sku") . "%";
            $submit_search = 1;
        }

        if ($this->input->get("name") != "") {
            $where["prod_name LIKE "] = "%" . $this->input->get("name") . "%";
            $submit_search = 1;
        }

        if ($this->input->get("cat_id") != "") {
            $where["cat_id"] = $this->input->get("cat_id");
            $submit_search = 1;
        }

        if ($this->input->get("sub_cat_id") != "") {
            $where["sub_cat_id"] = $this->input->get("sub_cat_id");
            $submit_search = 1;
        }

        if ($this->input->get("sub_sub_cat_id") != "") {
            $where["sub_sub_cat_id"] = $this->input->get("sub_sub_cat_id");
            $submit_search = 1;
        }

        if ($this->input->get("brand_id") != "") {
            $where["brand_id"] = $this->input->get("brand_id");
            $submit_search = 1;
        }

        if ($this->input->get("website_status") != "") {
            if ($this->input->get("website_status") == "I") {
                $where["website_status"] = "I";
                $where["website_quantity >"] = "0";
            } elseif ($this->input->get("website_status") == "O") {
                $where["((website_status = 'I' && website_quantity <1) OR website_status = 'O')"] = null;
            } else {
                $where["website_status"] = $this->input->get("website_status");
            }
            $submit_search = 1;
        }

//      $where["p.status"] = 2;

        $sort = $this->input->get("sort");
        $order = $this->input->get("order");

        $limit = '20';

        $pconfig['base_url'] = $_SESSION["LISTPAGE"];
        $option["limit"] = $pconfig['per_page'] = $limit;
        if ($option["limit"]) {
            $option["offset"] = $this->input->get("per_page");
        }

        if (empty($sort)) {
            $sort = "prod_name";
        }

        if (empty($order)) {
            $order = "asc";
        }

        $option["orderby"] = $sort . " " . $order;

        if ($this->input->get("search")) {
            $option["show_name"] = 1;
            $data["objlist"] = $this->product_service->get_dao()->get_product_overview($where, $option);
            $data["total"] = $this->product_service->get_dao()->get_product_overview($where, array("num_rows" => 1));
        }

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
        $data["line"] = $line;
        $data["pbv_obj"] = $this->platform_biz_var_service->get(array("selling_platform_id" => $platform_id));
        $data["default_curr"] = $data["pbv_obj"]->get_platform_currency_id();
        $this->load->view('order/special_order/special_order_prod_list_v', $data);
    }

    public function check_email($email = "")
    {
        if ($email) {
            $email = trim($email);
            $sub_app_id = $this->_get_app_id() . "00";
            $_SESSION["LISTPAGE"] = current_url() . "?" . $_SERVER['QUERY_STRING'];
            include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
            $data["lang"] = $lang;
//          $data["client"] = $this->client_service->get(array("email"=>$email));
            $data["client"] = $this->client_service->get_client_last_order($email);
//          var_dump($data["client"]);
            $this->load->view('order/special_order/special_order_check_email_v', $data);
        } else {
            show_404();
        }
    }

    public function find_all_so_by_email($client_id)
    {
        $where = array("status >=" => 3
        , "client_id" => $client_id
        , "biz_type <> 'SPECIAL'" => null);
        //, "split_so_group" => null );
        $option = array("limit" => -1, "orderby" => "so_no asc");
        $so_list = $this->so_model->so_service->get_dao()->get_list($where, $option);

        $so_arr = array();
        foreach ($so_list as $so) {
            $new_so = array();
            $new_so["so_no"] = $so->get_so_no();
            $new_so["order_create_date"] = $so->get_order_create_date();
            $new_so["status"] = $so->get_status();
            $new_so["amount"] = $so->get_amount();
            $new_so["refund_status"] = $so->get_refund_status();
            $new_so["currency"] = $so->get_currency_id();
            $new_so["hold_status"] = $so->get_hold_status();

            $new_so["split_level"] = $new_so["is_split_child"] = "";
            if ($so->get_hold_status() == 15) {
                $new_so["split_level"] = "";
                $new_so["is_split_child"] = "0";
            }
            if (($so->get_hold_status() != 15) and ($so->get_split_so_group() != '')) {
                # this so is a child of split order
                $new_so["split_level"] = "(split_so_group: " . $so->get_split_so_group() . " )";
                $new_so["is_split_child"] = "1";
            }

            array_push($so_arr, $new_so);
        }
        print json_encode($so_arr);
    }

    public function on_hold($order_reason = "")
    {
        $sub_app_id = $this->_get_app_id() . "01";
        if ($order_reason == "aps_payment") {
            $is_aps_payment_page = true;
            if (!check_app_feature_access_right($this->_get_app_id(), "ORD001101_aps_payment_order_page")) {
                show_error("Access Denied!");
            }
        } else
            $is_aps_payment_page = false;
        $data["is_aps_payment_page"] = $is_aps_payment_page;
        $_SESSION["LISTPAGE"] = current_url() . "?" . $_SERVER['QUERY_STRING'];

        if ($this->input->post("posted")) {
            if ($so_obj = $this->so_service->get(array("so_no" => $this->input->post("so_no")))) {
                $status = $this->input->post("status");
                $hold_status = $this->input->post("hold_status");

                if ($status == 2) {
                    $platform_obj = $this->so_service->get_pbv_srv()->get_dao()->get(array("selling_platform_id" => $so_obj->get_platform_id()));
                    $so_obj->set_expect_delivery_date(date("Y-m-d H:i:s", time() + $platform_obj->get_latency_in_stock() * 86400));
                }
                $so_obj->set_status($status);
                $so_obj->set_hold_status($hold_status);

                if ($is_aps_payment_page) {
                    $txn_id = $this->input->post("txn_id");
                    $so_obj->set_txn_id(trim($txn_id));
                }

                if (!$this->so_service->update($so_obj)) {
                    $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->db->_error_message();
                } else {
// do permanent hold to the original order
                    // $this->so_service->permanent_hold_parent($so_obj);
                    if (!$is_aps_payment_page) {
//send cs notification email
                        if ($hold_status == 3)
                            $this->so_service->send_notification_to_cs($so_obj);
                    } else {
                        $payment_gateway = $this->input->post("payment_gateway");
                        $pay_to_account = $this->input->post("pay_to_account");
                        $ps_obj = $this->so_service->get_sops_dao()->get(array("so_no" => $so_obj->get_so_no()));
                        if (($ps_obj !== FALSE) && ($ps_obj)) {
                            $action = "update";
                        } else {
                            $action = "insert";
                            $sops_vo = $this->so_service->get_sops_dao()->get();
                            $ps_obj = clone $sops_vo;
                            $ps_obj->set_so_no($so_obj->get_so_no());
                        }
                        $ps_obj->set_payment_gateway_id($payment_gateway);
                        $ps_obj->set_payment_status("S");
                        if ($payment_gateway == "paypal") {
                            $ps_obj->set_pay_to_account($pay_to_account);
                        }
                        $update_result = $this->so_service->get_sops_dao()->$action($ps_obj);
                        if ($update_reuslt === FALSE) {
                            $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->so_service->get_sops_dao()->db->_error_message();
                        }
                    }
                    redirect($_SESSION["LISTPAGE"]);
                }
            } else {
                $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->db->_error_message();
            }
        }


        $where = array();
        $option = array();

        $where["biz_type"] = "SPECIAL";
        $where["so.status"] = "1";
        $where["so.hold_status"] = "0";
        $option["so_item"] = "1";
        $option["hide_payment"] = "1";
        $input_platoform_id = $this->input->get("platform_id");
        if (!empty($input_platoform_id)) {
            $where["platform_id"] = $this->input->get("platform_id");
        }
        $input_email = $this->input->get("email");
        if (!empty($input_email)) {
            $where["email"] = $this->input->get("email");
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
        $option["notes"] = TRUE;
        $option["extend"] = TRUE;

        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
        $data["lang"] = $lang;

        if ($is_aps_payment_page) {
            $where["ore.require_payment"] = "1";
            $data["lang"]["title"] = "Sales - APS Order (Payment Required)";
            $data["lang"]["header"] = $data["lang"]["title"];
            $where["so.hold_status"] = "3";
            $where["ore.require_payment"] = 1;
        }

        $data["objlist"] = $this->so_service->get_dao()->get_list_w_name($where, $option);
        $data["total"] = $this->so_service->get_dao()->get_list_w_name($where, array("notes" => 1, "extend" => 1, "num_rows" => 1, "so_item" => 1));

        $data["holdstatus"] = array();
        foreach ($data["objlist"] as $special_order) {
            if (($special_order->get_require_payment() == 1) && (!$is_aps_payment_page)) {
                $data["hold_status"][$special_order->get_so_no()] = 3;
                $data["next_level_order_status"][$special_order->get_so_no()] = 1;
            } else {
                $data["hold_status"][$special_order->get_so_no()] = 0;
                $data["next_level_order_status"][$special_order->get_so_no()] = 2;
            }
        }

        $pconfig['total_rows'] = $data['total'];
        $this->pagination_service->set_show_count_tag(TRUE);
        $this->pagination_service->initialize($pconfig);

        $data["notice"] = notice($lang);

        $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
        $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
        $data["searchdisplay"] = "";
        $data["app_id"] = $this->_get_app_id();
        $this->load->view('order/special_order/special_order_on_hold_v', $data);
    }

    public function pending()
    {
        $sub_app_id = $this->_get_app_id() . "02";
        $_SESSION["LISTPAGE"] = current_url() . "?" . $_SERVER['QUERY_STRING'];

        if ($this->input->post("posted")) {

            if ($type = $this->input->post("type")) {
                if ($so_obj = $this->so_service->get(array("so_no" => $this->input->post("so_no")))) {
                    switch ($type) {
                        case "b":
                            $so_obj->set_status('1');
                            break;
                        case "c":
                            $so_obj->set_hold_status('1');
                            break;
                        case "p":
                            if (!check_app_feature_access_right($this->_get_app_id(), "ORD001102_process_order")) {
                                show_error("Access Denied!");
                            }
                            $so_obj->set_status('3');   // marked as fulfilled/creditchecked
                            break;
                        case "s":
                            if (!check_app_feature_access_right($this->_get_app_id(), "ORD001102_process_order")) {
                                show_error("Access Denied!");
                            }
                            $so_obj->set_status('6');   // marked as shipped
                            break;
                    }

                    if (!$this->so_service->update($so_obj)) {
                        $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->db->_error_message();
                    } else {
                        if ($type == 's' || $type == 'p') {
// do permanent hold for aps order_reason
                            $perm_hold_result = $this->so_service->permanent_hold_parent_for_aps($so_obj);
                            if ($perm_hold_result["status"] === false) {
                                $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $perm_hold_result["error_message"];
                            } else {
                                if (isset($perm_hold_result['update_message'])) {
                                    $_SESSION["NOTICE"] = $perm_hold_result['update_message'];
                                }
                            }
                        }

                        // send notification email to client
                        if ($type == 'p') {
                            if ($so_ext_obj = $this->so_service->get_soext_dao()->get(array("so_no" => $so_obj->get_so_no()))) {
                                if (($so_ext_obj->get_order_reason() == 19)
                                    || ($so_ext_obj->get_order_reason() == 22)
                                ) {
                                    $this->so_service->send_aps_order_client_notification_email($so_obj);
                                }
                            }
                        }
                        redirect($_SESSION["LISTPAGE"]);
                    }
                } else {
                    $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->db->_error_message();
                }
            }
        }

        $where = array();
        $option = array();

        $where["biz_type"] = "SPECIAL";
        $where["so.status"] = "2";
        $where["so.hold_status"] = "0";
        $option["so_item"] = "1";
        $option["hide_payment"] = "1";

        if ($this->input->get('platform_id') != "") {
            $where["platform_id"] = $this->input->get('platform_id');
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
        $option["notes"] = TRUE;
        $option["extend"] = TRUE;

        $data["objlist"] = $this->so_service->get_dao()->get_list_w_name($where, $option);
        $data["total"] = $this->so_service->get_dao()->get_list_w_name($where, array("notes" => 1, "extend" => 1, "num_rows" => 1));


        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
        $data["lang"] = $lang;

        $pconfig['total_rows'] = $data['total'];
        $this->pagination_service->set_show_count_tag(TRUE);
        $this->pagination_service->initialize($pconfig);

        $data["notice"] = notice($lang);

        $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
        $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
        $data["searchdisplay"] = "";

        $data["app_id"] = $this->_get_app_id();
        $this->load->view('order/special_order/special_order_pending_v', $data);
    }

    public function get_so_priority_score_dao()
    {
        return $this->so_priority_score_dao;
    }

    public function set_so_priority_score_dao($value)
    {
        $this->so_priority_score_dao = $value;
    }
}


