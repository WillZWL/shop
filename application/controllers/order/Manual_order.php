<?php
class Manual_order extends MY_Controller
{
    private $appId = "ORD0017";
    private $lang_id = "en";

    public function __construct()
    {
        parent::__construct();
        $this->path = "order/manual_order";
    }

    public function index($platform_type = "", $platform_id = "")
    {
        $sub_app_id = $this->getAppId() . "00";
        $_SESSION["LISTPAGE"] = current_url() . "?" . $_SERVER['QUERY_STRING'];

        if ($platform_id) {
            if ($this->input->post("posted")) {
                $pwd = $_POST["client"]["password"];
                $password = (trim($pwd) != "" ? $this->encryption->encrypt(strtolower($pwd)) : $this->encryption->encrypt(mktime()));

                $this->sc['ManualOrder']->addSoForManualOrder($_POST, $password, $platform_type, $platform_id);
            }
            $data["country_list"] = $this->sc['Region']->getSellCountryList();
            $data["pbv_obj"] = $this->sc['PlatformBizVar']->getdao('PlatformBizVar')->get(["selling_platform_id"=>$platform_id]);
            $data["default_curr"] = $data["pbv_obj"]->getPlatformCurrencyId();
            $data["payment_gateway_list"] = $this->sc['PaymentGateway']->getDao('PaymentGateway')->getList(["status" => 1]);
            $data["order_reason_list"] = $this->sc['So']->getDao('OrderReason')->getList(["status" => 1, "option_in_manual" => 1], ["limit" => -1, "orderby" => "priority"]);
        }

        include_once(APPPATH."language/".$sub_app_id."_".$this->getLangId().".php");
        $data["lang"] = $lang;
        $data["notice"] = notice($lang, TRUE);
        $data["sp_type_list"] = $this->sc['SellingPlatform']->getDao('SellingPlatform')->getPlatformTypeList();
        $data["sp_list"] = $this->sc['SellingPlatform']->getDao('SellingPlatform')->getList(["type"=>$platform_type], ["orderby"=>"name", "limit"=> -1]);
        $data["platform_id"] = $platform_id;
        $data["platform_type"] = $platform_type;
        $this->load->view('order/manual_order/manual_order_v', $data);
    }

    public function prod_list($line = "", $platform_id = "")
    {
        if ($platform_id == "") {
            show_404();
        }

        $sub_app_id = $this->getAppId() . "00";
        $_SESSION["LISTPAGE"] = current_url() . "?" . $_SERVER['QUERY_STRING'];

        $where = [];
        $option = [];
        if ($platform_id == "LAMY") {
            $platform_id = "WEBMY";
        }

        $where["pr.platform_id"] = $platform_id;
        $submit_search = 0;

        if ($this->input->get("sku") != "") {
            $where["p.sku LIKE "] = "%" . $this->input->get("sku") . "%";
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

        $sort = $this->input->get("sort");
        $order = $this->input->get("order");

        $option['limit'] = ($this->input->get('limit') != '') ? $this->input->get('limit') : '20';
        $option['offset'] = ($this->input->get('per_page') != '') ? $this->input->get('per_page') : '';

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
            $data["total"] = $this->sc['Product']->getDao('Product')->getProductOverview($where, ["num_rows"=>1]);
        }

        include_once(APPPATH."language/".$sub_app_id."_".$this->getLangId().".php");
        $data["lang"] = $lang;

        $config['base_url'] = base_url($this->path .'/prod_list/'.  $line .'/'. $platform_id);
        $config['total_rows'] = $data["total"];
        $config['page_query_string'] = true;
        $config['reuse_query_string'] = true;
        $config['per_page'] = $option['limit'];
        $data['per_page']  =  $this->input->get('per_page') ;
        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();

        $data["notice"] = notice($lang);

        $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
        $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
        $data["searchdisplay"] = "";
        $data["line"] = $line;
        $data["pbv_obj"] = $this->sc['PlatformBizVar']->getdao('PlatformBizVar')->get(["selling_platform_id"=>$platform_id]);
        $data["default_curr"] = $data["pbv_obj"]->getPlatformCurrencyId();
        $this->load->view('order/manual_order/manual_order_prod_list_v', $data);
    }

    public function on_hold()
    {
        $data['sub_app_id'] = $sub_app_id = $this->getAppId() . "01";
        $this->sc['Authorization']->checkAccessRights($sub_app_id, "On Hold");

        $_SESSION["LISTPAGE"] = current_url() . "?" . $_SERVER['QUERY_STRING'];

        if ($this->input->post("posted")) {
            $post_data['so_no'] = $this->input->post("so_no");
            $post_data['status'] = $this->input->post("status");

            $this->sc['ManualOrder']->processDataForOnHold($post_data);
        }


        $where = $option = [];

        $where["biz_type in ('MANUAL', 'QOO10')"] = null;

        $where["so.status"] = "0";
        $where["so.hold_status"] = "0";
        $option["so_item"] = "1";
        $option["hide_payment"] = "1";

        $sort = $this->input->get("sort");
        $order = $this->input->get("order");

        $option['limit'] = ($this->input->get('limit') != '') ? $this->input->get('limit') : '20';
        $option['offset'] = ($this->input->get('per_page') != '') ? $this->input->get('per_page') : '';

        if (empty($sort)) {
            $sort = "so_no";
        }

        if (empty($order)) {
            $order = "desc";
        }

        $option["orderby"] = $sort . " " . $order;
        $option["notes"] = TRUE;
        $option["extend"] = TRUE;

        $data["objlist"] = $this->sc['So']->getDao('So')->getListWithName($where, $option);
        $data["total"] = $this->sc['So']->getDao('So')->getListWithName($where, ["notes"=>1,"extend"=>1,"num_rows"=>1]);

        include_once(APPPATH."language/".$sub_app_id."_".$this->getLangId().".php");
        $data["lang"] = $lang;

        $config['base_url'] = base_url($this->path .'/on_hold/'.  $order_reason);
        $config['total_rows'] = $data["total"];
        $config['page_query_string'] = true;
        $config['reuse_query_string'] = true;
        $config['per_page'] = $option['limit'];
        $data['per_page']  =  $this->input->get('per_page') ;
        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();
        $data["notice"] = notice($lang);

        $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
        $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
        $data["searchdisplay"] = "";

        $this->load->view('order/manual_order/manual_order_on_hold_v', $data);
    }

    public function pending()
    {
        $data['sub_app_id'] = $sub_app_id = $this->getAppId() . "02";
        $this->sc['Authorization']->checkAccessRights($sub_app_id, "Pending");

        $_SESSION["LISTPAGE"] = current_url() . "?" . $_SERVER['QUERY_STRING'];

        if ($this->input->post("posted")) {

            $post_data['so_no'] = $this->input->post("so_no");
            $post_data['type'] = $this->input->post("type");
            $this->sc['ManualOrder']->processDataForPending($post_data);
        }

        $where = $option = [];

        $where["biz_type in ('MANUAL', 'QOO10')"] = null;
        $where["so.status"] = "1";
        $where["so.hold_status"] = "0";
        $option["so_item"] = "1";
        $option["hide_payment"] = "1";

        $sort = $this->input->get("sort");
        $order = $this->input->get("order");

        $option['limit'] = ($this->input->get('limit') != '') ? $this->input->get('limit') : '20';
        $option['offset'] = ($this->input->get('per_page') != '') ? $this->input->get('per_page') : '';

        if (empty($sort)) {
            $sort = "so_no";
        }

        if (empty($order)) {
            $order = "desc";
        }

        $option["orderby"] = $sort . " " . $order;
        $option["notes"] = TRUE;
        $option["extend"] = TRUE;

        $data["objlist"] = $this->sc['So']->getDao('So')->getListWithName($where, $option);
        $data["total"] = $this->sc['So']->getDao('So')->getListWithName($where, ["notes"=>1,"extend"=>1,"num_rows"=>1]);

        include_once(APPPATH."language/".$sub_app_id."_".$this->getLangId().".php");
        $data["lang"] = $lang;

        $config['base_url'] = base_url($this->path .'/pending/');
        $config['total_rows'] = $data["total"];
        $config['page_query_string'] = true;
        $config['reuse_query_string'] = true;
        $config['per_page'] = $option['limit'];
        $data['per_page']  =  $this->input->get('per_page') ;
        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();

        $data["notice"] = notice($lang);

        $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
        $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
        $data["searchdisplay"] = "";

        $this->load->view('order/manual_order/manual_order_pending_v', $data);
    }

    public function check_email()
    {
        $email = trim($this->input->get("email"));

        if ($email) {
            $sub_app_id = $this->getAppId() . "00";
            $_SESSION["LISTPAGE"] = current_url() . "?" . $_SERVER['QUERY_STRING'];
            include_once(APPPATH."language/".$sub_app_id."_".$this->getLangId().".php");
            $data["lang"] = $lang;
            $data["client"] = $this->sc['Client']->getDao('Client')->get(["email" => $email]);
            $this->load->view('order/manual_order/manual_order_check_email_v', $data);
        } else {
            show_404();
        }
    }

    public function getAppId()
    {
        return $this->appId;
    }
}