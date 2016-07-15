<?php

class Compensation_create extends MY_Controller
{
    private $appId = 'CS0005';
    private $lang_id = 'en';

    public function __construct()
    {
        parent::__construct();
    }

    public function create($offset = 0)
    {
        $where = [];
        $option = [];

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
            $limit = 20;
            $option["limit"] = $limit;
            $option["offset"] = $offset;

            $_SESSION["LISTPAGE"] = base_url() . "cs/compensation/create?" . $_SERVER['QUERY_STRING'];

            $option["orderby"] = $sort . " " . $order;

            $data['list'] = $this->sc['So']->getDao('SoCompensation')->getOrdersEligibleForCompensation($where, $option);

            $data['total'] = $this->sc['So']->getDao('SoCompensation')->getOrdersEligibleForCompensation($where, array_merge($option, ['num_rows'=>1]));

            $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
            $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";

            $config['base_url'] = base_url("cs/compensation/create/");
            $config['total_rows'] = $data["total"];
            $config['per_page'] = $limit;

            $this->pagination->initialize($config);
            $data['links'] = $this->pagination->create_links();
        }

        $langfile = "CS000403_" . $this->getLangId() . ".php";
        include_once APPPATH . "language/" . $langfile;

        $data["notice"] = notice($lang);
        $data["lang"] = $lang;

        $this->load->view('cs/compensation/index_create', $data);
    }

    public function create_view($orderid = "")
    {
        if ($orderid == "") {
            Redirect(base_url() . "cs/compensation_create/create/");
            exit;
        }

        if ($this->input->post('posted')) {
            $success = 1;
            $cp_sku = $this->input->post('compensate_sku');
            if ($cp_sku) {
                if ($num_rows = $this->sc['So']->getDao('SoCompensation')->getNumRows(["so_no" => $orderid, "status" => 2])) {
                    $_SESSION["NOTICE"] = "The order has already been compensated.";
                    Redirect(base_url() . "cs/compensation_create/create/?so_no=" . $orderid . "&search=1");
                }
                $cp_obj = $this->sc['So']->getDao('SoCompensation')->get();
                $cp_obj->setSoNo($orderid);
                $cp_obj->setLineNo(1);
                $cp_obj->setItemSku($cp_sku);
                $cp_obj->setQty(1);
                $cp_obj->setStatus(1);
                if (!$ret = $this->sc['So']->getDao('SoCompensation')->insert($cp_obj)) {
                    $success = 0;
                    $_SESSION["NOTICE"] = "ERROR: @" . __LINE__ . " " . $this->db->display_error() . "\n";
                }

                if ($success) {
                    if ($reason_obj = $this->sc['So']->getDao('CompensationReason')->get(["id" => $this->input->post("cnotes")])) {
                        if ($reason_obj->getId() == 13) {
                            $reason = $reason_obj->getReasonCat() . " - " . $reason_obj->getDescription() . ": " . $this->input->post("others_reason");
                        } else {
                            $reason = $reason_obj->getReasonCat() . " - " . $reason_obj->getDescription();
                        }
                    }
                    $cph_obj = $this->sc['So']->getDao('SoCompensationHistory')->get();
                    $cph_obj->setCompensationId($ret->getId());
                    $cph_obj->setSoNo($orderid);
                    $cph_obj->setItemSku($cp_sku);
                    $cph_obj->setNote($reason);
                    $cph_obj->setStatus(1);
                    if (!$this->sc['So']->getDao('SoCompensationHistory')->insert($cph_obj)) {
                        $success = 0;
                        $_SESSION["NOTICE"] = "ERROR: @" . __LINE__ . " " . $this->db->display_error() . "\n";
                    }
                    $hr_obj = $this->sc['So']->getDao('HoldReason')->get(['reason_cat'=>'OT','reason_type'=>'compensation','status'=>1]);
                    if (!$hr_obj) {
                        $reason_obj = $this->sc['So']->getDao('HoldReason')->get();
                        $reason_obj->setReasonCat('OT');
                        $reason_obj->setReasonType('compensation');
                        $reason_obj->setDescription('Compensation');

                        $hr_obj = $this->sc['So']->getDao('HoldReason')->insert($reason_obj);
                    }

                    $sohr_obj = $this->sc['So']->getDao('SoHoldReason')->get();
                    $sohr_obj->setSoNo($orderid);
                    $sohr_obj->setReason($hr_obj->getId());
                    if (!$this->sc['So']->getDao('SoHoldReason')->insert($sohr_obj)) {
                        $success = 0;
                        $_SESSION["NOTICE"] = "ERROR: @" . __LINE__ . " " . $this->db->display_error() . "\n";
                    }
                }

                if ($success) {
                    $so_obj = $this->sc['So']->getDao('So')->get(["so_no" => $orderid]);
                    $update_hold_status = false;
                    if ($so_obj->getHoldStatus() <> 1) {
                        $update_hold_status = true;
                        $holdStatus = 1;
                    }
                    $so_obj->setHoldStatus(1);
                    if (!$this->sc['So']->getDao('So')->update($so_obj)) {
                        $success = 0;
                        $_SESSION["NOTICE"] = "ERROR: @" . __LINE__ . " " . $this->db->display_error() . "\n";
                    } else {
                        if ($update_hold_status) {
                            $this->sc['So']->saveSoHoldStatusHistory($orderid, $holdStatus);
                        }

                        Redirect(base_url() . "cs/compensation_create/create/");
                    }
                }
            } else {
                $_SESSION["NOTICE"] = "Please select one product for compensation.";
            }
        }

        $history_list = $this->sc['So']->getDao('SoCompensationHistory')->getList(["so_no" => $orderid], ["lmit" => -1]);
        $data["history"] = $history_list;
        $data["itemcnt"] = count((array)$data["itemlist"]);
        $data["orderobj"] = $so = $this->sc['So']->getDao('So')->get(["so_no" => $orderid, "refund_status" => 0, "hold_status" => 0, "status" => 3]);
        if (!count($so)) {
            $_SESSION["NOTICE"] = "Order No. " . $orderid . " is not eligible for compensation.";
            Redirect(base_url() . "cs/compensation_create/create/");
            exit;
        }

        $langfile = "CS000403_" . $this->getLangId() . ".php";
        include_once APPPATH . "language/" . $langfile;

        $order_item_list = $this->sc['So']->getDao('SoItemDetail')->getListWithProdname(["so_no" => $orderid], ["sortby" => "line_no ASC"]);
        $data['order_item_list'] = $order_item_list;

        $reason_list = $this->sc['So']->getDao('CompensationReason')->getList([], ['limit'=>-1]);
        $data['reason_list'] = $reason_list;

        $data["lang"] = $lang;
        $data["orderid"] = $orderid;
        $data["notice"] = notice($lang);

        $this->load->view('cs/compensation/view_create', $data);
    }

    public function prod_list($line = "", $platform_id = "", $offset = 0)
    {
        if ($platform_id == "") {
            show_404();
        }

        $sub_app_id = $this->getAppId() . "01";
        $_SESSION["LISTPAGE"] = current_url() . "?" . $_SERVER['QUERY_STRING'];

        $where = [];
        $option = [];
        $where["pbv.selling_platform_id"] = $platform_id;
        $submit_search = 0;

        if ($this->input->get("sku") != "") {
            $where["sku LIKE "] = "%" . $this->input->get("sku") . "%";
            $submit_search = 1;
        }

        if ($this->input->get("name") != "") {
            $where["p.name LIKE "] = "%" . $this->input->get("name") . "%";
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
        $option["limit"] = $limit;
        $option["offset"] = $offset;

        if (empty($sort)) {
            $sort = "name";
        }

        if (empty($order)) {
            $order = "asc";
        }

        $option["orderby"] = $sort . " " . $order;

        if ($this->input->get("search")) {
            $option["show_name"] = 1;
            $data["objlist"] = $this->sc['Product']->getDao('Product')->getProductOverview($where, $option);

            $data["total"] = $this->sc['Product']->getDao('Product')->getProductOverview($where, array("num_rows" => 1));
        }

        include_once(APPPATH . "language/CS000401_" . $this->getLangId() . ".php");
        $data["lang"] = $lang;

        $config['base_url'] = base_url("cs/compensation_create/prod_list/$line/$platform_id");
        $config['total_rows'] = $data["total"];
        $config['per_page'] = $limit;
        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();

        $data["notice"] = notice($lang);

        $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
        $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
        $data["searchdisplay"] = "";
        $data["line"] = $line;
        $data["pbv_obj"] = $this->sc['So']->getDao('PlatformBizVar')->get(array("selling_platform_id" => $platform_id));
        $data["default_curr"] = $data["pbv_obj"]->getPlatformCurrencyId();
        $this->load->view('cs/compensation/view_prod_list', $data);
    }

    public function getAppId()
    {
        return $this->appId;
    }
}
