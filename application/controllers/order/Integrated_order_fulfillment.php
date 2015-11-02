<?php

class Integrated_order_fulfillment extends MY_Controller
{

    private $appId = "ORD0023";
    private $lang_id = "en";
    private $metapack_path;
    private $courier_path;
    private $default_delivery;

    public function __construct()
    {
        parent::__construct(FALSE);

        $public_method = ['invoice', 'custom_invoice', 'delivery_note', 'cron_generate_courier_file'];

        if (in_array(strtolower($this->router->fetch_method()), $public_method) === FALSE) {
            $this->sc['Authorization']->checkAccessRights($this->getAppId(), "");
        }

        // $this->load->library('service/so_service');

        // $this->load->library('service/batch_tracking_info_service');

        // $this->load->library('service/integrated_order_fulfillment_service');
        // $this->load->library('dao/courier_feed_dao.php');
        // $this->courier_feed_dao = new Courier_feed_dao();


        //$this->metapack_path = $this->sc['ContextConfig']->valueOf('metapack_path');
        $this->courier_path = $this->sc['ContextConfig']->valueOf('courier_path');
        $this->metapack_path = $this->sc['ContextConfig']->valueOf('metapack_path');
        $this->default_delivery = $this->sc['ContextConfig']->valueOf("default_delivery_type");

        # add on to this list whatever courier user want to display
        $this->courier_list = [
            "DHL",
            "DHLHKD",
            "DHLBBX",
            "HK_Post",
            "IM",
            "TOLL",
            "TNT",
            "DPD",
            "ARAMEX",
            "ARAMEX_COD",
            "RMR",
            "FEDEX",
            "FEDEX2",
            "DPD_NL",
            "MRW",
            "NEW_QUANTIUM",
            "QUANTIUM",
            "SF_EXPRESS",
            "TAQBIN"
        ];
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function index($warehouse = "ES_HK",  $offset = 0)
    {
        $sub_app_id = $this->getAppId() . "00";

        $_SESSION["LISTPAGE"] = base_url() . "order/integrated_order_fulfillment/" . ($warehouse == "" ? "" : "index/" . $warehouse) . "?" . $_SERVER['QUERY_STRING'];

        $allow_partial = 0;

        if ($this->input->post("posted")) {
            $checkSoNo = [];
            if ($_POST["allocate_type"] == "m") {
                if (empty($_POST["check"])) {
                    redirect($_SESSION["LISTPAGE"]);
                }

                $checkSoNo = $_POST["check"];
            }

            $this->getWmsAllocationPlanOrder($checkSoNo);

            redirect(current_url() . "?" . $_SERVER['QUERY_STRING']);
        }

        $where = [];
        $option = [];

        if ($this->input->get("so_no")) {
            $where["iof.so_no"] = $this->input->get("so_no");
        }

        if ($this->input->get("platform_order_id")) {
            $where["platform_order_id"] = $this->input->get("platform_order_id");
        }

        if ($this->input->get("platform_id")) {
            $where["platform_id"] = $this->input->get("platform_id");
        }

        if ($this->input->get("rec_courier")) {
            $where["iof.rec_courier"] = $this->input->get("rec_courier");
        }

        if ($this->input->get("order_create_date")) {
            fetch_operator($where, "order_create_date", $this->input->get("order_create_date"));
        }

        if ($this->input->get("expect_delivery_date")) {
            fetch_operator($where, "expect_delivery_date", $this->input->get("expect_delivery_date"));
        }

        if ($this->input->get("multiple") === '1') {
            $where["order_total_sku >"] = 1;
        } elseif ($this->input->get("multiple") === '0') {
            $where["order_total_sku <="] = 1;
        }

        if ($this->input->get("express")) {
            if ($this->input->get("express") == "Y") {
                $where["iof.delivery_type_id <>"] = $this->default_delivery;
            } else {
                $where["iof.delivery_type_id"] = $this->default_delivery;
            }
        }

        if ($this->input->get("item_sku")) {
            $where["item_sku LIKE "] = "%" . $this->input->get("item_sku") . "%";
        }

        if ($this->input->get("prod_name")) {
            $where["prod_name LIKE "] = "%" . $this->input->get("prod_name") . "%";
        }

        if ($this->input->get("outstanding_qty")) {
            fetch_operator($where, "outstanding_qty", $this->input->get("outstanding_qty"));
        }

        if ($this->input->get("delivery_name")) {
            $where["delivery_name LIKE "] = "%" . $this->input->get("delivery_name") . "%";
        }

        if ($this->input->get("delivery_country_id")) {
            if ($this->input->get("delivery_country_id") == "nonAPAC") {
                $where["delivery_country_id not in ('AU', 'NZ', 'SG', 'MY', 'HK', 'PH')"] = null;
            } else
                $where["delivery_country_id"] = $this->input->get("delivery_country_id");
        }

        if ($this->input->get("note")) {
            $where["note LIKE "] = "%" . $this->input->get("note") . "%";
        }

        $where["iof.status >"] = "2";
        $where["iof.status <"] = "5";

        $where["iof.hold_status"] = "0";
        $where["iof.refund_status"] = "0";

        if ($this->input->get("payment_gateway_id")) {
            $where["payment_gateway_id"] = $this->input->get("payment_gateway_id");
        }

        if ($this->input->get("website_status")) {
            $where["website_status"] = $this->input->get("website_status");
        }

        $sort = $this->input->get("sort");
        $order = $this->input->get("order");

        $limit = '500';

        $option["limit"] = $limit;
        $option["offset"] = $offset;

        # $sort is responsible for the ascending/descending arrow on frontend
        # for $sortstr, if there is no sort selected,
        # so_no must be the first ORDER BY so that orders with same so_no are grouped together
        if (empty($sort)) {
            $sort = "expect_delivery_date";
            $sortstr = "so_no, $sort $order";
        } else {
            $sortstr = "$sort $order";
        }

        if (empty($order))
            $order = "asc";


        $option["orderby"] = $sortstr;

        $data["default_delivery"] = $this->default_delivery;
        $data["whlist"] = $this->sc['Warehouse']->getDao('Warehouse')->getList([], ["limit" => -1, "result_type" => "array"]);
        $data["cclist"] = $this->sc['So']->getDao('So')->getCcList(["status >" => 2, "status <" => 5, "hold_status" => 0, "refund_status" => 0], ["orderby" => "delivery_country_id", "limit" => -1]);
        $option["warehouse_id"] = $data["warehouse"] = $warehouse ? $warehouse : $data["whlist"][0]["id"];
        $option["notes"] = 1;
        $option["hide_client"] = 1;
        $option["hide_payment"] = 0;
        $option["show_git"] = 1;
        $option["hide_shipped_item"] = 1;

        $temp_objlist = $this->sc['So']->getDao('So')->getIntegratedFulfillmentListWithName($where, $option);
        $data["objlist"] = $this->sc['IntegratedOrderFulfillment']->renovateData($temp_objlist);

        $data["total_order"] = $this->sc['So']->getDao('So')->getIntegratedFulfillmentListWithName($where, ["num_rows" => 1, "total_order_row" => 1, "hide_client" => 1, "hide_payment" => 0, "hide_shipped_item" => 1]);
        $data["total_item"] = $pconfig['total_rows'] = $this->sc['So']->getDao('So')->getIntegratedFulfillmentListWithName($where, ["total_items" => 1, "hide_shipped_item" => 1]);

        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->getLangId() . ".php");
        $data["lang"] = $lang;

        $config['base_url'] = base_url('order/integrated_order_fulfillment/index/'.$warehouse);
        $config['total_rows'] = $data["total_order"];
        $config['per_page'] = $limit;

        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();

        $data["notice"] = notice($lang);
        $data["valid_website_status"] = $this->sc['soModel']->getValidWebsiteStatusList();
        $data["courier_list"] = $this->courier_list;
        $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
        $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
        $data["searchdisplay"] = "";
        $this->load->view('order/integrated_order_fulfillment/integrated_order_fulfillment_index_v', $data);
    }

    public function getWmsAllocationPlanOrder($so_no_list = [])
    {
        if (empty($so_no_list)) {
            if ($wms_so_no = $this->sc['WmsInventory']->getWmsSoNoList()) {
                $so_no_list = array_unique($wms_so_no[0]);
            }
        }

        if (!empty($so_no_list)) {
            $this->sc['So']->wmsAllocationPlanOrder($so_no_list);
        }

        redirect(base_url() . "order/integrated_order_fulfillment/?" . $_SERVER['QUERY_STRING']);
    }

    public function to_ship($offset = 0)
    {
        set_time_limit(60);
        $sub_app_id = $this->getAppId() . "01";

        $_SESSION["LISTPAGE"] = base_url() . "order/integrated_order_fulfillment/to_ship/?" . $_SERVER['QUERY_STRING'];

        if ($this->input->post("posted") && $_POST["check"] && $_POST["dispatch_type"] != 'c') {
            $valueArr = [
                            "dispatchType"=>$_POST["dispatch_type"],
                            "checkSoNo"=>$_POST["check"],
                            "postCourierId"=>$_POST["courier_id"],
                            "postCourier"=>$_POST["courier"]
                        ];
            $success_so = $this->sc['So']->dealOrderFulfilmentToShip($valueArr);

            if ($_POST["dispatch_type"] == 'r') {
                redirect(current_url() . "?" . $_SERVER['QUERY_STRING']);
            } else {
                $this->generateCourierFile($success_so);
            }
        }

        $where = [];
        $option = [];

        if ($this->input->get("so_no")) {
            $where["iof.so_no"] = $this->input->get("so_no");
        }

        if ($this->input->get("platform_order_id")) {
            $where["platform_order_id"] = $this->input->get("platform_order_id");
        }

        if ($this->input->get("platform_id")) {
            $where["platform_id"] = $this->input->get("platform_id");
        }

        if ($this->input->get("rec_courier")) {
            $where["iof.rec_courier"] = $this->input->get("rec_courier");
        }

        if ($this->input->get("order_create_date")) {
            fetch_operator($where, "order_create_date", $this->input->get("order_create_date"));
        }

        if ($this->input->get("amount")) {
            fetch_operator($where, "iof.amount", $this->input->get("amount"));
        }

        if ($this->input->get("multiple") === '1') {
            $where["order_total_sku >"] = 1;
        } elseif ($this->input->get("multiple") === '0') {
            $where["order_total_sku <="] = 1;
        }

        if ($this->input->get("express")) {
            if ($this->input->get("express") == "Y") {
                $where["iof.delivery_type_id <>"] = $this->default_delivery;
            } else {
                $where["iof.delivery_type_id"] = $this->default_delivery;
            }
        }

        if ($this->input->get("item_sku")) {
            $where["item_sku"] = $this->input->get("item_sku");
        }

        if ($this->input->get("prod_name")) {
            $where["prod_name LIKE "] = $this->input->get("prod_name");
        }

        if ($this->input->get("outstanding_qty")) {
            fetch_operator($where, "outstanding_qty", $this->input->get("outstanding_qty"));
        }

        if ($this->input->get("delivery_name")) {
            $where["delivery_name LIKE "] = "%" . $this->input->get("delivery_name") . "%";
        }

        if ($this->input->get("delivery_postcode")) {
            $where["delivery_postcode"] = $this->input->get("delivery_postcode");
        }

        if ($this->input->get("delivery_country_id")) {
            $where["delivery_country_id"] = $this->input->get("delivery_country_id");
        }

        if ($this->input->get("warehouse_id")) {
            $where["soal.warehouse_id"] = $this->input->get("warehouse_id");
        }

        if ($this->input->get("delivery")) {
            $where["iof.delivery_type_id"] = $this->input->get("delivery");
        }

        if ($this->input->get("note")) {
            $where["note LIKE "] = "%" . $this->input->get("note") . "%";
        }

        if ($this->input->get("payment_gateway_id")) {
            $where["payment_gateway_id"] = $this->input->get("payment_gateway_id");
        }


        $where["iof.status >"] = "3";
        $where["iof.status <"] = "6";


        $sort = $this->input->get("sort");
        $order = $this->input->get("order");

        $limit = '500';

        $option["limit"] = $limit;
        $option["offset"] = $offset;

        # $sort is responsible for the ascending/descending arrow on frontend
        # for $sortstr, if there is no sort selected,
        # so_no must be the first ORDER BY so that orders with same so_no are grouped together
        if (empty($sort)) {
            $sort = "expect_delivery_date";

            # sequence of ORDER BY so_no is impt, else may cause display problem
            $sortstr = "so_no, $sort $order";
        } else {
            $sortstr = "$sort $order";
        }

        if (empty($order))
            $order = "asc";

        $option["orderby"] = $sortstr;

        $option["list_type"] = "toship";

        $data["default_delivery"] = $this->default_delivery;
        $data["whlist"] = $this->sc['Warehouse']->getDao('Warehouse')->getList([], ["limit" => -1, "result_type" => "array"]);
        $data["courier_list"] = $this->courier_list;
        $option["notes"] = 1;
        $option["hide_client"] = 1;
        $option["hide_payment"] = 0;

        $temp_objlist = $this->sc['So']->getDao('SoAllocate')->getIntegratedAllocateList($where, $option);

        $data["objlist"] = $this->sc['IntegratedOrderFulfillment']->renovateData($temp_objlist);
        $data["total_order"] = $this->sc['So']->getDao('SoAllocate')->getIntegratedAllocateList($where, ["num_rows" => 1, "list_type" => "toship", "hide_client" => 1]);
        $data["total_item"] = $pconfig['total_rows'] = $this->sc['So']->getDao('SoAllocate')->getIntegratedAllocateList($where, ["total_items" => 1, "list_type" => "toship"]);

        $config['base_url'] = base_url('order/integrated_order_fulfillment/to_ship/');
        $config['total_rows'] = $data["total_order"];
        $config['per_page'] = $limit;

        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->getLangId() . ".php");
        $data["lang"] = $lang;
        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();

        $data["notice"] = notice($lang);

        $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
        $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
        $data["searchdisplay"] = "";

        $this->load->view('order/integrated_order_fulfillment/integrated_order_fulfillment_to_ship_v', $data);
        if ($_POST["dispatch_type"] == 'c') {
            $this->generate_allocate_file();
        }
    }

    public function generateCourierFile($checked = "")
    {
        if ($checked) {
            $courier = $this->input->post("courier_id");
            $mawb = $this->input->post("mawb");
            $so_no_str = json_encode($checked);

            $this->sc['Batch']->schedulePhpProcess(1, "order/integrated_order_fulfillment/cron_generate_courier_file/" . $batch_id);
        }
    }

    public function generate_allocate_file()
    {
        $ret = $this->sc['So']->generateAllocateFile();
        $_SESSION['allocate_file'] = $ret;
        redirect(current_url() . "?" . $_SERVER['QUERY_STRING']);
    }

    public function dispatch($offset = 0)
    {
        set_time_limit(60);
        $sub_app_id = $this->getAppId() . "02";

        $_SESSION["LISTPAGE"] = base_url() . "order/integrated_order_fulfillment/dispatch/?" . $_SERVER['QUERY_STRING'];
        if ($this->input->post("posted") && $_POST["check"]) {
            $valueArr = [
                            "dispatchType"=>$_POST["dispatch_type"],
                            "checkSoNo"=>$_POST["check"],
                            "postCourierId"=>$_POST["courier_id"],
                            "postCourier"=>$_POST["courier"],
                            "db_time"=>$_POST["db_time"]
                        ];
            $success_so = $this->sc['So']->dealOrderFulfilmentDispatch($valueArr);

            if ($_POST["dispatch_type"] == 'c') {
                $this->generateCourierFile($success_so);
            } else {
                redirect(current_url() . "?" . $_SERVER['QUERY_STRING']);
            }
        }

        $where = [];
        $option = [];

        if ($this->input->get("so_no")) {
            $where["iof.so_no"] = $this->input->get("so_no");
        }

        if ($this->input->get("platform_order_id")) {
            $where["platform_order_id"] = $this->input->get("platform_order_id");
        }

        if ($this->input->get("platform_id")) {
            $where["platform_id"] = $this->input->get("platform_id");
        }

        if ($this->input->get("rec_courier")) {
            $where["iof.rec_courier"] = $this->input->get("rec_courier");
        }

        if ($this->input->get("order_create_date")) {
            fetch_operator($where, "order_create_date", $this->input->get("order_create_date"));
        }

        if ($this->input->get("packing_date")) {
            fetch_operator($where, "sosh.create_on", $this->input->get("packing_date"));
        }

        if ($this->input->get("expect_delivery_date")) {
            fetch_operator($where, "expect_delivery_date", $this->input->get("expect_delivery_date"));
        }

        if ($this->input->get("amount")) {
            fetch_operator($where, "iof.amount", $this->input->get("amount"));
        }

        if ($this->input->get("multiple") === '1') {
            $where["order_total_sku >"] = 1;
        } elseif ($this->input->get("multiple") === '0') {
            $where["order_total_sku <="] = 1;
        }

        if ($this->input->get("express")) {
            if ($this->input->get("express") == "Y") {
                $where["iof.delivery_type_id <>"] = $this->default_delivery;
            } else {
                $where["iof.delivery_type_id"] = $this->default_delivery;
            }
        }

        if ($this->input->get("item_sku")) {
            $where["item_sku"] = $this->input->get("item_sku");
        }

        if ($this->input->get("prod_name")) {
            $where["prod_name LIKE "] = "%" . $this->input->get("prod_name") . "%";
        }

        if ($this->input->get("outstanding_qty")) {
            fetch_operator($where, "outstanding_qty", $this->input->get("outstanding_qty"));
        }

        if ($this->input->get("delivery_name")) {
            $where["delivery_name LIKE "] = "%" . $this->input->get("delivery_name") . "%";
        }

        if ($this->input->get("delivery_postcode")) {
            $where["delivery_postcode"] = $this->input->get("delivery_postcode");
        }

        if ($this->input->get("delivery_country_id")) {
            $where["delivery_country_id"] = $this->input->get("delivery_country_id");
        }

        if ($this->input->get("sh_no")) {
            $where["soal.sh_no LIKE"] = "%" . $this->input->get("sh_no") . "%";
        }

        if ($this->input->get("warehouse_id")) {
            $where["warehouse_id"] = $this->input->get("warehouse_id");
        }

        if ($this->input->get("tracking_no")) {
            $where["tracking_no LIKE"] = "%" . $this->input->get("warehouse_id") . "%";
        }

        if ($this->input->get("note")) {
            $where["note LIKE "] = "%" . $this->input->get("note") . "%";
        }


        $where["iof.status >"] = "3";
        $where["iof.status <"] = "6";


        $sort = $this->input->get("sort");
        $order = $this->input->get("order");

        $limit = '500';

        $option["limit"] = $limit;
        $option["offset"] = $offset;

        # $sort is responsible for the ascending/descending arrow on frontend
        # for $sortstr, if there is no sort selected,
        # so_no must be the first ORDER BY so that orders with same so_no are grouped together
        if (empty($sort)) {
            $sort = "expect_delivery_date";

            # sequence of ORDER BY so_no is impt, else may cause display problem
            $sortstr = "so_no, $sort $order";
        } else {
            $sortstr = "$sort $order";
        }

        if (empty($order))
            $order = "asc";

        $option["orderby"] = $sortstr;
        $option["list_type"] = "dispatch";

        $data["default_delivery"] = $this->default_delivery;
        $data["whlist"] = $this->sc['Warehouse']->getDao('Warehouse')->getList([], ["limit" => -1, "result_type" => "array"]);
        $data["courier_list"] = $this->courier_list;
        $option["notes"] = 1;
        $option["hide_client"] = 1;

        $temp_objlist = $this->sc['So']->getDao('SoAllocate')->getIntegratedAllocateList($where, $option);
        $data["objlist"] = $this->sc['IntegratedOrderFulfillment']->renovateData($temp_objlist);
        $data["total_order"] = $this->sc['So']->getDao('SoAllocate')->getIntegratedAllocateList($where, ["num_rows" => 1, "list_type" => "dispatch", "hide_client" => 1]);
        $data["total_item"] = $pconfig['total_rows'] = $this->sc['So']->getDao('SoAllocate')->getIntegratedAllocateList($where, ["total_items" => 1, "list_type" => "dispatch"]);

        $data["db_time"] = $this->sc['So']->getDao('So')->getDbTime();

        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->getLangId() . ".php");
        $data["lang"] = $lang;


        $config['base_url'] = base_url('order/integrated_order_fulfillment/dispatch/');
        $config['total_rows'] = $data["total_order"];
        $config['per_page'] = $limit;

        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();
        $data["notice"] = notice($lang);

        $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
        $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
        $data["searchdisplay"] = "";

        $this->load->view('order/integrated_order_fulfillment/integrated_order_fulfillment_dispatch_v', $data);
    }

    public function sync_wms_tracking_feed()
    {
        $this->sc['ClwmsTrackingFeed']->processTrackingFeed();
        redirect(base_url()."order/integrated_order_fulfillment/dispatch/");
    }

    public function add_note($so_no = "", $line = "")
    {
        if ($so_no && $line != "") {
            $sub_app_id = $this->getAppId() . "00";
            $_SESSION["LISTPAGE"] = base_url() . "order/integrated_order_fulfillment/add_note/$so_no/$line?" . $_SERVER['QUERY_STRING'];
            if ($this->input->post("posted")) {
                if (isset($_SESSION["obj"])) {
                    $this->sc['So']->getDao('OrderNotes')->get();
                    $data["obj"] = unserialize($_SESSION["obj"]);
                    $data["obj"]->setNote($this->input->post("note"));
                    if (!$this->sc['So']->getDao('OrderNotes')->insert($data["obj"])) {
                        $_SESSION["NOTICE"] = __LINE__ . ":" . $this->db->_error_message();
                        $_SESSION["NOTICE"] = __LINE__ . ":" . $this->db->last_query();
                    } else {
                        unset($_SESSION["obj"]);
                        $data["success"] = 1;
                    }
                }
            }
            include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->getLangId() . ".php");
            $data["lang"] = $lang;

            if (empty($data["obj"])) {
                if (($data["obj"] = $this->sc['So']->getDao('OrderNotes')->get()) === FALSE) {
                    $_SESSION["NOTICE"] = __LINE__ . ":" . $this->db->_error_message();
                } else {
                    $data["obj"]->setSoNo($so_no);
                    $data["obj"]->setType("O");
                    $_SESSION["obj"] = serialize($data["obj"]);
                }
            }

            $data["objlist"] = $this->sc['So']->getDao('OrderNotes')->getList(["so_no" => $so_no], ["orderby" => "create_on desc", "limit" => 5]);
            $data["so_no"] = $so_no;
            $data["line"] = $line;
            $data["notice"] = notice($lang);
            $this->load->view('order/integrated_order_fulfillment/integrated_order_fulfillment_add_note_v', $data);
        } else {
            show_404();
        }
    }

    public function errorInAllocateFile()
    {
        $ret = $this->sc['So']->errorInAllocateFile();
    }

    public function get_allocate_file($filename = "")
    {
        if ($filename == "") {
            exit;
        } else {
            $data['filename'] = $this->courier_path . $filename;
            $data['output_filename'] = $filename;
            $this->load->view('order/integrated_order_fulfillment/readfile', $data);
        }
    }

    public function cron_generate_courier_file($batch_id)
    {
        $this->sc['So']->getGenerateCourierFile($batch_id);
    }

    public function get_courier_file($filename = "")
    {
        if ($filename == "") {
            exit;
        } else {
            $data['filename'] = $this->courier_path . $filename;
            $data['output_filename'] = $filename;
            $this->load->view('order/integrated_order_fulfillment/readfile', $data);
        }
    }

    public function generate_metapack_file($checked)
    {
        if ($checked) {
            $courier = $this->input->post("courier_id");
            $ret = $this->sc['So']->generate_metapack_file($checked, $courier);
            $_SESSION["metapack_file"] = $ret;
            redirect(current_url() . "?" . $_SERVER['QUERY_STRING']);
        }
    }

    public function get_metapack_file($filename = "")
    {
        if ($filename == "") {
            exit;
        } else {
            $data["filename"] = $this->metapack_path . $filename;
            $data["output_filename"] = $filename;
            $this->load->view('order/integrated_order_fulfillment/readfile', $data);
        }
    }

    public function invoice()
    {
        $sub_app_id = $this->getAppId() . "03";
        $data["message"] = $this->sc['So']->getPrintInvoiceContent($_POST["check"]);
        $this->load->view("order/integrated_order_fulfillment/invoice", $data);

    }

    public function custom_invoice($currency = "")
    {
        $new_shipper_name = trim($_POST["shipper_name"]);
        $sub_app_id = $this->getAppId() . "03";
        $data["message"] = $this->sc['So']->getCustomInvoiceContent($_POST["check"], $new_shipper_name, $currency);
        $this->load->view("order/integrated_order_fulfillment/invoice", $data);
    }

    public function delivery_note()
    {
        $sub_app_id = $this->getAppId() . "03";
        $data["message"] = $this->sc['So']->getDeliveryNoteContent($_POST["check"]);
        $this->load->view("order/integrated_order_fulfillment/invoice", $data);
    }

    public function order_packing_slip()
    {
        $sub_app_id = $this->getAppId() . "03";
        $data["message"] = $this->sc['So']->getOrderPackingSlipContent($_POST["check"]);
        $this->load->view("order/integrated_order_fulfillment/invoice", $data);
    }

    public function import_trackingfile()
    {
        set_time_limit(120);
        $wh_list = ["ams", "im", "rmr"];
        foreach ($wh_list as $wh) {
            $this->batch_tracking_info_service->cron_tracking_info($wh);
        }
        $data["result"] = $_SESSION["result"];
        $this->load->view("order/integrated_order_fulfillment/tracking_info_result_v", $data);
    }

    public function _get_metapack_path()
    {
        return $this->metapack_path;
    }

    public function _get_courier_path()
    {
        return $this->courier_path;
    }

    public function remove_redundant_record()
    {
        //every time when access this controller, auto delete the record from the integrated_order_fulfillment table
        //where status = 0, 1, 6 and modify_on is 21 days ago.
        $iof_where['`status` in (0, 1, 6)'] = null;
        $iof_where['DATEDIFF(NOW(),modify_on) >'] = 21;
        $obsolete_iof_record_number = $this->sc['IntegratedOrderFulfillment']->getDao('IntegratedOrderFulfillment')->delete($iof_where);

        //completed refunded order and more than 21 days unmodified
        $iof_where = [];
        $iof_where['refund_status'] = 4;
        $iof_where['DATEDIFF(NOW(),modify_on) >'] = 21;
        $obsolete_iof_record_number = $this->sc['IntegratedOrderFulfillment']->getDao('IntegratedOrderFulfillment')->delete($iof_where);
    }

    public function get_barcode($so_no = '')
    {
        if ($so_no == '') exit();
        include_once(BASEPATH . "plugins/barcode/barcode.php");

        DEFINE('CANVAS_WIDTH', 220);
        DEFINE('CANVAS_HEIGHT', 70);
        DEFINE('BARCODE_HEIGHT', 30);
        DEFINE('BARCODE_WIDTH', 2);

        $x = CANVAS_WIDTH / 2; // barcode center
        $y = CANVAS_HEIGHT / 2 - 10; // barcode center
        $height = BARCODE_HEIGHT; // barcode height in 1D ; module size in 2D
        $width = BARCODE_WIDTH; // barcode height in 1D ; not use in 2D
        $angle = 0; // rotation in degrees : nb : non horizontable barcode might not be usable because of pixelisation

        $code = $so_no; // barcode
        $type_list = ['int25', 'std25', 'ean8', 'ean13', 'upc', 'code11', 'code39', 'code93', 'code128', 'codabar', 'msi', 'datamatrix'];
        //6, 8, 11 work at this task
        $type = $type_list[8];

        $im = imagecreatetruecolor(CANVAS_WIDTH, CANVAS_HEIGHT);
        $black = ImageColorAllocate($im, 0x00, 0x00, 0x00);
        $white = ImageColorAllocate($im, 0xff, 0xff, 0xff);
        $red = ImageColorAllocate($im, 0xff, 0x00, 0x00);
        $blue = ImageColorAllocate($im, 0x00, 0x00, 0xff);
        imagefilledrectangle($im, 0, 0, 300, 300, $white);

        // BARCODE
        $data = Barcode::gd($im, $black, $x, $y, $angle, $type, ['code' => $code], $width, $height);

        imagestring($im, 70, 100, 42, $code, $black);

        imageline($im, 0, 0, CANVAS_WIDTH, 0, $black);
        imageline($im, 0, CANVAS_HEIGHT - 1, CANVAS_WIDTH, CANVAS_HEIGHT - 1, $black);
        imageline($im, 0, 0, 0, CANVAS_HEIGHT, $black);
        imageline($im, CANVAS_WIDTH - 1, 0, CANVAS_WIDTH - 1, CANVAS_HEIGHT, $black);

        header('Content-type: image/png');
        imagepng($im);
        imagedestroy($im);
    }

}
