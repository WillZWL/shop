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
        $public_method = ['invoice', 'custom_invoice', 'delivery_note'];

        if (in_array(strtolower($this->router->fetch_method()), $public_method) === FALSE) {
            $this->sc['Authorization']->checkAccessRights($this->getAppId(), "");
        }

        $this->courier_path = $this->sc['ContextConfig']->valueOf('courier_path');
        $this->metapack_path = $this->sc['ContextConfig']->valueOf('metapack_path');
        $this->default_delivery = $this->sc['ContextConfig']->valueOf("default_delivery_type");
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

            $this->getWmsAllocationPlanOrder(1, $checkSoNo);

            redirect(current_url() . "?" . $_SERVER['QUERY_STRING']);
        }

        $where = [];
        $option = [];

        if ($this->input->get("so_no")) {
            $where["so.so_no"] = $this->input->get("so_no");
        }

        if ($this->input->get("platform_order_id")) {
            $where["platform_order_id"] = $this->input->get("platform_order_id");
        }

        if ($this->input->get("platform_id")) {
            $where["platform_id"] = $this->input->get("platform_id");
        }

        if ($this->input->get("rec_courier")) {
            $where["so.rec_courier"] = $this->input->get("rec_courier");
        }

        if ($this->input->get("order_create_date")) {
            fetch_operator($where, "order_create_date", $this->input->get("order_create_date"));
        }

        if ($this->input->get("expect_delivery_date")) {
            fetch_operator($where, "expect_delivery_date", $this->input->get("expect_delivery_date"));
        }

        if ($this->input->get("multiple") === '1') {
            $where["so.order_total_item >"] = 1;
        } elseif ($this->input->get("multiple") === '0') {
            $where["so.order_total_item <="] = 1;
        }

        if ($this->input->get("express")) {
            if ($this->input->get("express") == "Y") {
                $where["so.delivery_type_id <>"] = $this->default_delivery;
            } else {
                $where["so.delivery_type_id"] = $this->default_delivery;
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

        $where["so.status >"] = "2";
        $where["so.status <"] = "5";

        $where["so.hold_status"] = "0";
        $where["so.refund_status"] = "0";

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
            $sort = "so.expect_delivery_date";
            $sortstr = "so.so_no, $sort $order";
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

        $data["total_order"] = $this->sc['So']->getDao('So')->getIntegratedFulfillmentListWithName($where, ["num_rows" => 1, "total_orders" => 1]);
        $data["total_item"] = $pconfig['total_rows'] = $this->sc['So']->getDao('So')->getIntegratedFulfillmentListWithName($where, ["total_items" => 1]);

        $option["distinct_so_no_list"] = 1;
        $soNoArr = [];
        # Need take 500 orders Instead of take 500 order items, If only show 500 order items will cannot fully displayed on the same page.
        $soObjlist = $this->sc['So']->getDao('So')->getIntegratedFulfillmentListWithName($where, $option);
        if ((array) $soObjlist) {
            foreach ($soObjlist as $soObj) {
                $soNoArr[] = $soObj->getSoNo();
            }

            $option["solist"] = $soNoArr;

            unset($option["distinct_so_no_list"]);
            unset($option["limit"]);
            unset($option["offset"]);
            $option["limit"] = -1;

            $data["objlist"] = $this->sc['So']->getDao('So')->getIntegratedFulfillmentListWithName($where, $option);
        }


        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->getLangId() . ".php");
        $data["lang"] = $lang;

        $config['base_url'] = base_url('order/integrated_order_fulfillment/index/'.$warehouse);
        $config['total_rows'] = $data["total_order"];
        $config['per_page'] = $limit;

        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();

        $data["notice"] = notice($lang);
        $data["valid_website_status"] = $this->sc['soModel']->getValidWebsiteStatusList();
        $data["courier_list"] =  $this->sc['Courier']->getDao('Courier')->getList(['show_status'=>1], ['limit'=>-1]);
        $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
        $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
        $data["searchdisplay"] = "";
        $this->load->view('order/integrated_order_fulfillment/integrated_order_fulfillment_index_v', $data);
    }

    public function getWmsAllocationPlanOrder($redirectUrl = 0, $so_no_list = [])
    {
        if (empty($so_no_list)) {
            if ($wms_so_no = $this->sc['WmsInventory']->getWmsSoNoList()) {
                $so_no_list = array_unique($wms_so_no[0]);
            }
        }

        if (!empty($so_no_list)) {
            $this->sc['So']->wmsAllocationPlanOrder($so_no_list);
        }

        if ($redirectUrl) {
            redirect(base_url() . "order/integrated_order_fulfillment/?" . $_SERVER['QUERY_STRING']);
        }
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
            $where["so.so_no"] = $this->input->get("so_no");
        }

        if ($this->input->get("platform_order_id")) {
            $where["platform_order_id"] = $this->input->get("platform_order_id");
        }

        if ($this->input->get("platform_id")) {
            $where["platform_id"] = $this->input->get("platform_id");
        }

        if ($this->input->get("rec_courier")) {
            $where["so.rec_courier"] = $this->input->get("rec_courier");
        }

        if ($this->input->get("order_create_date")) {
            fetch_operator($where, "order_create_date", $this->input->get("order_create_date"));
        }

        if ($this->input->get("amount")) {
            fetch_operator($where, "so.amount", $this->input->get("amount"));
        }

        if ($this->input->get("multiple") === '1') {
            $where["so.order_total_item >"] = 1;
        } elseif ($this->input->get("multiple") === '0') {
            $where["so.order_total_item <="] = 1;
        }

        if ($this->input->get("express")) {
            if ($this->input->get("express") == "Y") {
                $where["so.delivery_type_id <>"] = $this->default_delivery;
            } else {
                $where["so.delivery_type_id"] = $this->default_delivery;
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
            $where["so.delivery_type_id"] = $this->input->get("delivery");
        }

        if ($this->input->get("note")) {
            $where["note LIKE "] = "%" . $this->input->get("note") . "%";
        }

        if ($this->input->get("payment_gateway_id")) {
            $where["payment_gateway_id"] = $this->input->get("payment_gateway_id");
        }


        $where["so.status >"] = "3";
        $where["so.status <"] = "6";


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
        $data["courier_list"] =  $this->sc['Courier']->getDao('Courier')->getList(['show_status'=>1], ['limit'=>-1]);
        $option["notes"] = 1;
        $option["hide_client"] = 1;
        $option["hide_payment"] = 0;

        $data["total_order"] = $this->sc['So']->getDao('SoAllocate')->getIntegratedAllocateList($where, ["num_rows" => 1, "list_type" => "toship"]);
        $data["total_item"] = $pconfig['total_rows'] = $this->sc['So']->getDao('SoAllocate')->getIntegratedAllocateList($where, ["total_items" => 1, "list_type" => "toship"]);

        $option["distinct_so_no_list"] = 1;
        $soNoArr = [];
        # Need take 500 orders Instead of take 500 order items, If only show 500 order items will cannot fully displayed on the same page.
        $soObjlist = $this->sc['So']->getDao('SoAllocate')->getIntegratedAllocateList($where, $option);
        if ((array) $soObjlist) {
            foreach ($soObjlist as $soObj) {
                $soNoArr[] = $soObj->getSoNo();
            }

            $option["solist"] = $soNoArr;

            unset($option["distinct_so_no_list"]);
            unset($option["limit"]);
            unset($option["offset"]);
            $option["limit"] = -1;

            $data["objlist"] = $this->sc['So']->getDao('SoAllocate')->getIntegratedAllocateList($where, $option);
        }

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
            $this->generateAllocateFile();
        }
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
            $where["so.so_no"] = $this->input->get("so_no");
        }

        if ($this->input->get("platform_order_id")) {
            $where["platform_order_id"] = $this->input->get("platform_order_id");
        }

        if ($this->input->get("platform_id")) {
            $where["platform_id"] = $this->input->get("platform_id");
        }

        if ($this->input->get("rec_courier")) {
            $where["so.rec_courier"] = $this->input->get("rec_courier");
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
            fetch_operator($where, "so.amount", $this->input->get("amount"));
        }

        if ($this->input->get("multiple") === '1') {
            $where["so.order_total_item >"] = 1;
        } elseif ($this->input->get("multiple") === '0') {
            $where["so.order_total_item <="] = 1;
        }

        if ($this->input->get("express")) {
            if ($this->input->get("express") == "Y") {
                $where["so.delivery_type_id <>"] = $this->default_delivery;
            } else {
                $where["so.delivery_type_id"] = $this->default_delivery;
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


        $where["so.status >"] = "3";
        $where["so.status <"] = "6";


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
        $data["courier_list"] =  $this->sc['Courier']->getDao('Courier')->getList(['show_status'=>1], ['limit'=>-1]);

        $data["total_order"] = $this->sc['So']->getDao('SoAllocate')->getIntegratedAllocateList($where, ["num_rows" => 1, "list_type" => "dispatch"]);
        $data["total_item"] = $pconfig['total_rows'] = $this->sc['So']->getDao('SoAllocate')->getIntegratedAllocateList($where, ["total_items" => 1, "list_type" => "dispatch"]);

        $option["distinct_so_no_list"] = 1;
        $soNoArr = [];
        # Need take 500 orders Instead of take 500 order items, If only show 500 order items will cannot fully displayed on the same page.
        $soObjlist = $this->sc['So']->getDao('SoAllocate')->getIntegratedAllocateList($where, $option);
        if ((array) $soObjlist ) {
            foreach ($soObjlist as $soObj) {
                $soNoArr[] = $soObj->getSoNo();
            }

            $option["solist"] = $soNoArr;

            unset($option["distinct_so_no_list"]);
            unset($option["limit"]);
            unset($option["offset"]);
            $option["limit"] = -1;

            $data["objlist"] = $this->sc['So']->getDao('SoAllocate')->getIntegratedAllocateList($where, $option);
        }

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

    public function testCourierFeed($courier='DHL')
    {
        $so_no = $this->input->get("so_no");

        $ret = $this->sc['CourierFeed']->generateCourierFile($so_no, $courier, 'Test Mawb', true);
        $_SESSION['courier_file'] = $ret;
        $this->getCourierFile($ret);
    }

    public function generateCourierFile($checked = "")
    {
        if ($checked) {
            $courier = $this->input->post("courier_id");
            $mawb = $this->input->post("mawb");
            $soNoStr = json_encode($checked);

            $courierFeedVo = $this->sc['CourierFeed']->getDao('CourierFeed')->get();
            $courierFeedObj = clone $courierFeedVo;

            $courierFeedObj->setSoNoStr($soNoStr);
            $courierFeedObj->setCourierId($courier);
            $courierFeedObj->setMawb($mawb);
            $courierFeedObj->setExec(0);

            $courierFeedObj = $this->sc['CourierFeed']->getDao('CourierFeed')->insert($courierFeedObj);
            $id = $courierFeedObj->getId();

            $ret = $this->sc['CourierFeed']->getGenerateCourierFile($id);
            $_SESSION['courier_file'] = $ret;
            redirect(current_url()."?".$_SERVER['QUERY_STRING']);
        }
    }

    public function generateAllocateFile()
    {
        $ret = $this->sc['So']->generateAllocateFile();
        $_SESSION['allocate_file'] = $ret;
        redirect(current_url() . "?" . $_SERVER['QUERY_STRING']);
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

    public function getAllocateFile($filename = "")
    {
        if ($filename == "") {
            exit;
        } else {
            $data['filename'] = $this->courier_path . $filename;
            $data['output_filename'] = $filename;
            $this->load->view('order/integrated_order_fulfillment/readfile', $data);
        }
    }

    public function getCourierFile($filename = "")
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
            $this->sc["BatchTrackingInfo"]->cronTrackingInfo($wh);
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
