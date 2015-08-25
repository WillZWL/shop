<?php

class Version extends MY_Controller
{

    private $appId = "MST0011";
    private $lang_id = "";

    public function __construct()
    {
        parent::__construct();
        $this->load->model("mastercfg/version_model");
        $this->load->helper(array("url", "notice"));
        $this->load->library("service/pagination_service");
        $this->_set_lang_id(function_exists(get_lang_id) ? get_lang_id() : "en");
    }

    public function index($edit = "", $eid = "")
    {
        $_SESSION["LISTPAGE"] = base_url() . "mastercfg/version/?" . $_SERVER["QUERY_STRING"];
        $sub_app_id = $this->getAppId() . "01";

        if ($this->input->post("posted")) {
            if ($this->input->post('action') == "add") {
                $obj = $this->version_model->get();
                $obj->set_id($this->input->post("id"));
                $function = "insert";
            } else {
                $obj = $this->version_model->get(array("id" => $this->input->post("id")));
                $function = "update";
            }

            $obj->set_desc($this->input->post("desc"));
            $obj->set_status($this->input->post("status"));

            $ret = $this->version_model->$function($obj);
            if ($ret === FALSE) {
                $_SESSION["notice"] = __LINE__ . " : " . $this->db->_error_message();
            } else {
                unset($_SESSION["notice"]);
            }
        }

        $where = array();
        $option = array();

        if ($this->input->get("id") != "") {
            $where['id LIKE'] = '%' . $this->input->get("id") . '%';
        }

        if ($this->input->get("desc") != "") {
            $where['desc LIKE'] = '%' . $this->input->get('desc') . '%';
        }

        if ($this->input->get('status') != "") {
            $where['status'] = $this->input->get('status');
        }

        $sort = $this->input->get('sort');
        $order = $this->input->get('order');

        $pconfig['base_url'] = $_SESSION["LISTPAGE"];
        $option["limit"] = $pconfig['per_page'] = $limit;
        if ($option["limit"]) {
            $option["offset"] = $this->input->get("per_page");
        }

        if (empty($sort))
            $sort = "id";

        if (empty($order))
            $order = "desc";

        $option["orderby"] = $sort . " " . $order;

        $data = $this->version_model->get_list_w_cnt($where, $option);

        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
        $data["lang"] = $lang;

        $pconfig['total_rows'] = $data['total'];
        $this->pagination_service->set_show_count_tag(TRUE);
        $this->pagination_service->initialize($pconfig);

        $data["notice"] = notice($lang);
        $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
        $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
        $data["searchdisplay"] = ($submit_search) ? "" : 'style="display:none"';
        $data["searchdisplay"] = "";

        $data["eid"] = $eid;
        $data["edit"] = $edit;

        $this->load->view("mastercfg/version/index_v", $data);
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }

    public function _set_lang_id($value)
    {
        $this->lang_id = $value;
    }
}

?>