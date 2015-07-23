<?php

class Compensation_create extends MY_Controller
{
    private $app_id = 'CS0005';
    private $lang_id = 'en';

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url', 'notice', 'object', 'operator'));
        $this->load->model('cs/compensation_model');
        $this->load->library('service/pagination_service');
        $this->load->library('service/event_service');
        $this->load->library('service/product_service');
        $this->load->library('service/price_service');
        $this->load->library('service/platform_biz_var_service');
        $this->load->library('service/authorization_service');
        $this->load->library('service/order_notes_service');
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
                $where["so.so_no LIKE"] = '%' . $this->input->get('so_no') . '%';
            }

            if ($this->input->get('cname') != "") {
                $where["so.bill_name LIKE"] = '%' . $this->input->get('cname') . '%';
            }

            if ($this->input->get('platform_id') != "") {
                $where["so.platform_id"] = $this->input->get('platform_id');
            }

            if ($this->input->get('platform_order_id') != "") {
                $where["so.platform_order_id LIKE"] = "%" . $this->input->get('platform_order_id') . "%";
            }

            $sort = $this->input->get('sort');
            if ($sort == "") {
                $sort = "so.so_no";
            }

            $order = $this->input->get('order');
            if (empty($order)) {
                $order = "asc";
            }

            $option["limit"] = $pconfig['per_page'] = 20;

            if ($option["limit"]) {
                $option["offset"] = $this->input->get("per_page");
            }

            $_SESSION["LISTPAGE"] = base_url() . "cs/compensation/create?" . $_SERVER['QUERY_STRING'];

            $option["orderby"] = $sort . " " . $order;

            $data = $this->compensation_model->get_orders_eligible_for_compensation($where, $option);
            $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
            $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
            $option["orderby"] = $sort . " " . $order;

            $pconfig['base_url'] = $_SESSION["LISTPAGE"];
            $pconfig['total_rows'] = $data['total'];
            $this->pagination_service->set_show_count_tag(TRUE);
            $this->pagination_service->initialize($pconfig);
        }

        $langfile = "CS000403_" . $this->_get_lang_id() . ".php";
        include_once APPPATH . "language/" . $langfile;

        $data["notice"] = notice($lang);
        $data["lang"] = $lang;

        $this->load->view('cs/compensation/index_create', $data);
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }

    public function create_view($orderid = "")
    {
        if ($orderid == "") {
            Redirect(base_url() . "cs/compensation_create/create/");
            exit;
        }

        //$sub_app_id = $this->_get_app_id()."03";
        //$this->authorization_service->check_access_rights($sub_app_id, "Edit");
        if ($this->input->post('posted')) {
            $success = 1;
            $cp_sku = $this->input->post('compensate_sku');
            if ($cp_sku) {
                if ($num_rows = $this->compensation_model->get_num_rows_compensation(array("so_no" => $orderid, "status" => 2))) {
                    $_SESSION["NOTICE"] = "The order has already been compensated.";
                    Redirect(base_url() . "cs/compensation_create/create/?so_no=" . $orderid . "&search=1");
                }
                $cp_obj = $this->compensation_model->get_compensation();
                $cp_obj->set_so_no($orderid);
                $cp_obj->set_line_no(1);
                $cp_obj->set_item_sku($cp_sku);
                $cp_obj->set_qty(1);
                $cp_obj->set_status(1);
                if (!$ret = $this->compensation_model->insert_compensation($cp_obj)) {
                    $success = 0;
                    $_SESSION["NOTICE"] = "ERROR: @" . __LINE__ . " " . $this->db->_error_message() . "\n";
                }

                if ($success) {
                    if ($reason_obj = $this->compensation_model->get_compensation_reason(array("id" => $this->input->post("cnotes")))) {
                        if ($reason_obj->get_id() == 13) {
                            $reason = $reason_obj->get_reason_cat() . " - " . $reason_obj->get_description() . ": " . $this->input->post("others_reason");
                        } else {
                            $reason = $reason_obj->get_reason_cat() . " - " . $reason_obj->get_description();
                        }
                    }
                    $cph_obj = $this->compensation_model->get_history();
                    $cph_obj->set_compensation_id($ret->get_id());
                    $cph_obj->set_so_no($orderid);
                    $cph_obj->set_item_sku($cp_sku);
                    $cph_obj->set_note($reason);
                    $cph_obj->set_status(1);
                    if (!$this->compensation_model->insert_history($cph_obj)) {
                        $success = 0;
                        $_SESSION["NOTICE"] = "ERROR: @" . __LINE__ . " " . $this->db->_error_message() . "\n";
                    }

                    $sohr_obj = $this->compensation_model->get_reason();
                    $sohr_obj->set_so_no($orderid);
                    $sohr_obj->set_reason("compensation");
                    if (!$this->compensation_model->insert_reason($sohr_obj)) {
                        $success = 0;
                        $_SESSION["NOTICE"] = "ERROR: @" . __LINE__ . " " . $this->db->_error_message() . "\n";
                    }
                }

                if ($success) {
                    $so_obj = $this->compensation_model->get_so(array("so_no" => $orderid));
                    $so_obj->set_hold_status(1);
                    if (!$this->compensation_model->update_so($so_obj)) {
                        $success = 0;
                        $_SESSION["NOTICE"] = "ERROR: @" . __LINE__ . " " . $this->db->_error_message() . "\n";
                    } else {
                        Redirect(base_url() . "cs/compensation_create/create/");
                    }
                }
            } else {
                $_SESSION["NOTICE"] = "Please select one product for compensation.";
            }
        }

        $history_list = $this->compensation_model->get_history_list(array("so_no" => $orderid), array("array_list" => 1));
        $data["history"] = $history_list;
        $data["itemcnt"] = count((array)$data["itemlist"]);
        $data["orderobj"] = $so = $this->compensation_model->get_so(array("so_no" => $orderid, "refund_status" => 0, "hold_status" => 0, "status" => 3));
        if (!count($so)) {
            $_SESSION["NOTICE"] = "Order No. " . $orderid . " is not eligible for compensation.";
            Redirect(base_url() . "cs/compensation_create/create/");
            exit;
        }

        $langfile = "CS000403_" . $this->_get_lang_id() . ".php";
        include_once APPPATH . "language/" . $langfile;

        $order_item_list = $this->compensation_model->get_item_list(array("so_no" => $orderid));
        $data['order_item_list'] = $order_item_list;

        $reason_list = $this->compensation_model->get_compensation_reason_list();
        $data['reason_list'] = $reason_list;

        $data["lang"] = $lang;
        $data["orderid"] = $orderid;
        $data["notice"] = notice($lang);

        $this->load->view('cs/compensation/view_create', $data);
    }

    public function prod_list($line = "", $platform_id = "")
    {
        if ($platform_id == "") {
            show_404();
        }

        $sub_app_id = $this->_get_app_id() . "01";
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

        include_once(APPPATH . "language/CS000401_" . $this->_get_lang_id() . ".php");
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
        $this->load->view('cs/compensation/view_prod_list', $data);
    }

    public function _get_app_id()
    {
        return $this->app_id;
    }
}

?>