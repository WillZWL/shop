<?php

class Manual_order extends MY_Controller
{

    private $app_id = "ORD0017";
    private $lang_id = "en";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('marketing/product_model');
        $this->load->model('order/so_model');
        $this->load->model('website/checkout_model');
        $this->load->helper(array('url', 'notice', 'object', 'image'));
        $this->load->library('service/pagination_service');
        $this->load->library('service/context_config_service');
        $this->load->library('service/selling_platform_service');
        $this->load->library('service/payment_gateway_service');
        $this->load->library('service/platform_biz_var_service');
        $this->load->library('service/order_reason_service');
        $this->load->model('mastercfg/region_model');
        $this->load->library('encrypt');
        $this->load->library('service/authorization_service');

        if (isset($_SESSION["cart"]) && $_SESSION["cart_type"] != "MANUAL") {
            unset($_SESSION["cart"]);
        }
        $_SESSION["cart_type"] = "MANUAL";
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
                    if (!($client_obj = $this->client_service->get(array("email" => $_POST["client"]["email"])))) {
                        $client_obj = $this->client_service->get();
                        $_POST["client"]["password"] = (trim($_POST["client"]["password"]) != "" ? $this->encrypt->encode(strtolower($_POST["client"]["password"])) : $this->encrypt->encode(mktime()));
                        set_value($client_obj, $_POST["client"]);
                        $client_obj->set_mobile($_POST["client"]["mtel_1"] . $_POST["client"]["mtel_2"] . $_POST["client"]["mtel_3"]);

                        if ($_POST["billaddr"] != 1) {
                            $client_obj->set_del_name($_POST["client"]["title"] . " " . $_POST["client"]["forename"] . " " . $_POST["client"]["surname"]);
                            $client_obj->set_del_company($_POST["client"]["companyname"]);
                            $client_obj->set_del_address_1($_POST["client"]["address_1"]);
                            $client_obj->set_del_address_2($_POST["client"]["address_2"]);
                            $client_obj->set_del_city($_POST["client"]["city"]);
                            $client_obj->set_del_state($_POST["client"]["state"]);
                            $client_obj->set_del_country_id($_POST["client"]["country_id"]);
                            $client_obj->set_del_mobile($_POST["client"]["mtel_1"] . $_POST["client"]["mtel_2"] . $_POST["client"]["mtel_3"]);
                            $client_obj->set_del_name($_POST["client"]["title"] . " " . $_POST["client"]["forename"] . " " . $_POST["client"]["surname"]);
                        } else {
                            $client_obj->set_del_mobile($_POST["client"]["del_mtel_1"] . $_POST["client"]["del_mtel_2"] . $_POST["client"]["del_mtel_3"]);
                            $client_obj->set_del_name($_POST["client"]["del_title"] . " " . $_POST["client"]["del_forename"] . " " . $_POST["client"]["del_surname"]);
                        }
                        $client_obj->set_party_subscriber(0);
                        $client_obj->set_status(1);

                        if ($client_obj = $this->client_service->insert($client_obj)) {
                            $_POST["client"]["id"] = $client_obj->get_id();
                        } else {
                            $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->db->_error_message();
                        }
                    } else {
                        $_POST["client"]["id"] = $client_obj->get_id();
                        $_POST["client"]["password"] = (trim($_POST["client"]["password"]) != "" ? $this->encrypt->encode(strtolower($_POST["client"]["password"])) : $this->encrypt->encode(mktime()));
                        set_value($client_obj, $_POST["client"]);
                        $client_obj->set_mobile($_POST["client"]["mtel_1"] . $_POST["client"]["mtel_2"] . $_POST["client"]["mtel_3"]);
                        if ($_POST["billaddr"] != 1) {
                            $client_obj->set_del_name($_POST["client"]["title"] . " " . $_POST["client"]["forename"] . " " . $_POST["client"]["surname"]);
                            $client_obj->set_del_company($_POST["client"]["companyname"]);
                            $client_obj->set_del_address_1($_POST["client"]["address_1"]);
                            $client_obj->set_del_address_2($_POST["client"]["address_2"]);
                            $client_obj->set_del_city($_POST["client"]["city"]);
                            $client_obj->set_del_state($_POST["client"]["state"]);
                            $client_obj->set_del_country_id($_POST["client"]["country_id"]);
                            $client_obj->set_del_mobile($_POST["client"]["mtel_1"] . $_POST["client"]["mtel_2"] . $_POST["client"]["mtel_3"]);
                            $client_obj->set_del_name($_POST["client"]["title"] . " " . $_POST["client"]["forename"] . " " . $_POST["client"]["surname"]);
                        } else {
                            $client_obj->set_del_mobile($_POST["client"]["del_mtel_1"] . $_POST["client"]["del_mtel_2"] . $_POST["client"]["del_mtel_3"]);
                            $client_obj->set_del_name($_POST["client"]["del_title"] . " " . $_POST["client"]["del_forename"] . " " . $_POST["client"]["del_surname"]);
                        }
                        if ($this->client_service->update($client_obj) === FALSE) {
                            $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->db->_error_message();
                        }
                    }
                } else {
                    $client_obj = $this->client_service->get(array("id" => $_POST["client"]["id"]));
                    $_POST["client"]["password"] = (trim($_POST["client"]["password"]) != "" ? $this->encrypt->encode(strtolower($_POST["client"]["password"])) : $this->encrypt->encode(mktime()));
                    set_value($client_obj, $_POST["client"]);
                    $client_obj->set_mobile($_POST["client"]["mtel_1"] . $_POST["client"]["mtel_2"] . $_POST["client"]["mtel_3"]);
                    if ($_POST["billaddr"] != 1) {
                        $client_obj->set_del_name($_POST["client"]["title"] . " " . $_POST["client"]["forename"] . " " . $_POST["client"]["surname"]);
                        $client_obj->set_del_company($_POST["client"]["companyname"]);
                        $client_obj->set_del_address_1($_POST["client"]["address_1"]);
                        $client_obj->set_del_address_2($_POST["client"]["address_2"]);
                        $client_obj->set_del_city($_POST["client"]["city"]);
                        $client_obj->set_del_state($_POST["client"]["state"]);
                        $client_obj->set_del_country_id($_POST["client"]["country_id"]);
                        $client_obj->set_del_mobile($_POST["client"]["mtel_1"] . $_POST["client"]["mtel_2"] . $_POST["client"]["mtel_3"]);
                        $client_obj->set_del_name($_POST["client"]["title"] . " " . $_POST["client"]["forename"] . " " . $_POST["client"]["surname"]);
                    } else {
                        $client_obj->set_del_mobile($_POST["client"]["del_mtel_1"] . $_POST["client"]["del_mtel_2"] . $_POST["client"]["del_mtel_3"]);
                        $client_obj->set_del_name($_POST["client"]["del_title"] . " " . $_POST["client"]["del_forename"] . " " . $_POST["client"]["del_surname"]);
                    }
                    if ($this->client_service->update($client_obj) === FALSE) {
                        $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->db->_error_message();
                    }
                }
                if (empty($_SESSION["NOTICE"])) {
                    unset($_SESSION["cart"]);
                    unset($_SESSION["cart_type"]);
                    $soi_price = array();
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

                    $vars = $_POST;
                    $vars["client"] = $client_obj;
                    $vars["platform_id"] = $platform_id;
                    $vars["platform_type"] = $platform_type;
                    $vars["biz_type"] = "manual";
                    $vars["special"] = $special;
                    $vars["soi_price"] = $soi_price;
                    $vars["vat_exempt"] = $this->input->post('vat_exempt');
                    //$vars["free_delivery"] = 1;
                    $vars["customized_delivery"] = $this->input->post('delivery_charge');
                    //$vars["delivery"] = "STDPG";
                    $vars["txn_id"] = $this->input->post('txn_id');
                    $vars["platform_order_id"] = $this->input->post('platform_order_id');
                    $vars["payment_date"] = $this->input->post('payment_date');
                    $vars["payment_gateway"] = $this->input->post('payment_gateway');
                    $pg_obj = $this->payment_gateway_service->get(array("id" => $this->input->post('payment_gateway')));
                    $vars["pay_to_account"] = $pg_obj->get_ref_id();

                    if (empty($_SESSION["NOTICE"])) {
                        if ($so_obj = $this->so_service->cart_to_so($vars)) {
                            if ($platform_type == 'QOO10') {
                                #SBF #2858 update db so that update_shipment_status() can run automatically
                                $so_no = $so_obj->get_so_no();
                                $so_obj_updated = $this->so_service->process_qoo10_manual_orders($so_no);

                                if ($so_obj_updated) {
                                    $_SESSION["DISPLAY"] = array($so_obj_updated->get_so_no() . " Created Success", "success");
                                    redirect($_SESSION["LISTPAGE"]);
                                } else {
                                    $_SESSION["NOTICE"] = "SO Service " . $_SESSION["NOTICE"];
                                }
                            }

                            $_SESSION["DISPLAY"] = array($so_obj->get_so_no() . " Created Success", "success");
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
            $data["country_list"] = $this->region_service->get_full_country_list();
            $data["pbv_obj"] = $this->platform_biz_var_service->get(array("selling_platform_id" => $platform_id));
            $data["default_curr"] = $data["pbv_obj"]->get_platform_currency_id();
            $data["payment_gateway_list"] = $this->payment_gateway_service->get_list(array("status" => 1));
        }
        $data["order_reason_list"] = $this->order_reason_service->get_list(array("status" => 1, "option_in_manual" => 1), array("limit" => -1, "orderby" => "priority"));
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
        $data["lang"] = $lang;
        $data["notice"] = notice($lang, TRUE);
        $data["sp_type_list"] = $this->selling_platform_service->get_platform_type_list();
        $data["sp_list"] = $this->selling_platform_service->get_list(array("type" => $platform_type), array("orderby" => "name", "limit" => -1));
        $data["platform_id"] = $platform_id;
        $data["platform_type"] = $platform_type;
        $this->load->view('order/manual_order/manual_order_v', $data);
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
        if ($platform_id == "LAMY")
            $platform_id = "WEBMY";
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
        $this->load->view('order/manual_order/manual_order_prod_list_v', $data);
    }

    public function check_email($email = "")
    {
        if ($email) {
            $sub_app_id = $this->_get_app_id() . "00";
            $_SESSION["LISTPAGE"] = current_url() . "?" . $_SERVER['QUERY_STRING'];
            include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
            $data["lang"] = $lang;
            $data["client"] = $this->client_service->get(array("email" => $email));
            $this->load->view('order/manual_order/manual_order_check_email_v', $data);
        } else {
            show_404();
        }
    }

    public function on_hold()
    {
        $data['sub_app_id'] = $sub_app_id = $this->_get_app_id() . "01";
        $this->authorization_service->check_access_rights($sub_app_id, "On Hold");

        $_SESSION["LISTPAGE"] = current_url() . "?" . $_SERVER['QUERY_STRING'];

        if ($this->input->post("posted")) {
            if ($so_obj = $this->so_service->get(array("so_no" => $this->input->post("so_no")))) {
                $status = $this->input->post("status");
                if ($status == 2) {
                    $platform_obj = $this->so_service->get_pbv_srv()->get_dao()->get(array("selling_platform_id" => $so_obj->get_platform_id()));
                    $so_obj->set_expect_delivery_date(date("Y-m-d H:i:s", time() + $platform_obj->get_latency_in_stock() * 86400));
                }
                $so_obj->set_status($status);
                if (!$this->so_service->update($so_obj)) {
                    $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->db->_error_message();
                } else {
                    redirect($_SESSION["LISTPAGE"]);
                }
            } else {
                $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->db->_error_message();
            }
        }


        $where = array();
        $option = array();

        $where["biz_type in ('MANUAL', 'QOO10')"] = null;

        $where["so.status"] = "0";
        $where["so.hold_status"] = "0";
        $option["so_item"] = "1";
        $option["hide_payment"] = "1";

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

        $this->load->view('order/manual_order/manual_order_on_hold_v', $data);
    }

    public function pending()
    {
        $data['sub_app_id'] = $sub_app_id = $this->_get_app_id() . "02";
        $this->authorization_service->check_access_rights($sub_app_id, "Pending");

        $_SESSION["LISTPAGE"] = current_url() . "?" . $_SERVER['QUERY_STRING'];

        if ($this->input->post("posted")) {

            if ($type = $this->input->post("type")) {
                if ($so_obj = $this->so_service->get(array("so_no" => $this->input->post("so_no")))) {
                    switch ($type) {
                        case "b":
                            $so_obj->set_status('0');
                            break;
                        case "c":
                            $so_obj->set_hold_status('1');
                            break;
                        case "p":
                            $so_obj->set_status('3');
                            break;
                    }
                    if (!$this->so_service->update($so_obj)) {
                        $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->db->_error_message();
                    } else {
                        redirect($_SESSION["LISTPAGE"]);
                    }
                } else {
                    $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->db->_error_message();
                }
            }
        }


        $where = array();
        $option = array();

        // $where["biz_type"] = "MANUAL";
        $where["biz_type in ('MANUAL', 'QOO10')"] = null;
        $where["so.status"] = "1";
        $where["so.hold_status"] = "0";
        $option["so_item"] = "1";
        $option["hide_payment"] = "1";

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

        $this->load->view('order/manual_order/manual_order_pending_v', $data);
    }
}


