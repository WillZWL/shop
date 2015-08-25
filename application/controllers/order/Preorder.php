<?php

class Preorder extends MY_Controller
{
    const PAGE_LIMIT = 100;
    private $appId = "ORD0022";
    private $lang_id = "en";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('order/preorder_model');
        $this->load->model('order/so_model');
        $this->load->helper(array('url', 'notice', 'object'));
        $this->load->library('service/pagination_service');
    }

    public function index()
    {
        $sub_app_id = $this->getAppId() . "01";

        $search = $this->input->get_post('search');
        $where = array();
        $option = array();
        $where["so.refund_status"] = "0";
        $where["so.hold_status"] = "0";
        $where["so.status >"] = "2";
        $where["so.status <"] = "5";
        $where["soi.website_status"] = "P";
        $sort = $this->input->get_post("sort");
        $order = $this->input->get_post("order");
        if (empty($sort))
            $sort = "create_on";
        if (empty($order))
            $order = "desc";

        $selected_order = $this->input->get_post('check');
        if ($selected_order) {
            foreach ($selected_order as $so) {
                $action = $this->input->get_post("order_action");
                if ($action == "delay_email")
                    $this->preorder_model->so_service->fire_preorder_delay_email($so);
            }
///redirect it to prevent user refresh the page and the action will be re-do
            $query_string = "so_no=" . trim($this->input->get_post("so_no"));
            $query_string .= "&prod_sku=" . trim($this->input->get_post("prod_sku"));
            $query_string .= "&search=1";
            redirect("order/preorder?" . $query_string);
        }

        if ($search) {
            $_SESSION["LISTPAGE"] = base_url() . "order/preorder/?" . $_SERVER['QUERY_STRING'];
            if ($this->input->get_post("so_no") != "") {
                $where["so.so_no"] = trim($this->input->get_post("so_no"));
            }
            if ($this->input->get_post("prod_sku") != "") {
                $where["soi.prod_sku"] = trim($this->input->get_post("prod_sku"));
            }
            $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
            $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
        }
        $limit = self::PAGE_LIMIT;
        $pconfig['base_url'] = $_SESSION["LISTPAGE"];
        $option["limit"] = $pconfig['per_page'] = $limit;

        if ($option["limit"]) {
            $option["offset"] = $this->input->get_post("per_page");
        }

        $option["orderby"] = $sort . " " . $order;
        $data["preorder_list"] = $this->preorder_model->so_service->get_dao()->get_preorder_list($where, $option);
        $data["total"] = $this->preorder_model->so_service->get_dao()->get_preorder_list($where, array("num_rows" => 1));
        $pconfig['total_rows'] = $data['total'];
        $this->pagination_service->set_show_count_tag(TRUE);
        $this->pagination_service->initialize($pconfig);

//      print $this->preorder_model->so_service->get_dao()->db->last_query();

        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
        $data["lang"] = $lang;
        $data["notice"] = notice($lang);
        $this->load->view('order/preorder/preorder_index', $data);
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }
}


