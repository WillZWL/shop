<?php

class Ixtens_reprice_rule extends MY_Controller
{
    private $appId = "MKT0060";
    private $lang_id = "en";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('marketing/ixtens_reprice_rule_model');
        $this->load->helper(array('url', 'notice'));
        $this->load->library('service/pagination_service');
    }

    public function index()
    {
        $sub_app_id = $this->getAppId() . "00";

        if ($this->input->post('posted')) {
            if ($this->input->post('action') == "add") {
                $obj = $this->ixtens_reprice_rule_model->get();
                $obj->set_platform_id(trim($this->input->post("platform_id")));
                $obj->set_id(trim($this->input->post("id")));
                $obj->set_status($this->input->post("status"));

                $ret = $this->ixtens_reprice_rule_model->insert($obj);
            }

            if ($this->input->post('action') == "edit") {
                $this->ixtens_reprice_rule_model->get_dao()->trans_start();
                $obj = $this->ixtens_reprice_rule_model->get(array("platform_id" => $this->input->post('prev_platform_id'), "id" => $this->input->post('prev_id')));
                if ($ret = $this->ixtens_reprice_rule_model->q_delete($obj)) {
                    $obj->set_platform_id(trim($this->input->post("platform_id")));
                    $obj->set_id(trim($this->input->post("id")));
                    $obj->set_status($this->input->post("status"));
                    $ret = $this->ixtens_reprice_rule_model->insert($obj);
                }
                $this->ixtens_reprice_rule_model->get_dao()->trans_complete();
            }

            if ($this->input->post('action') == "delete") {
                $obj = $this->ixtens_reprice_rule_model->get(array("platform_id" => $this->input->post('prev_platform_id'), "id" => $this->input->post('prev_id')));
                $ret = $this->ixtens_reprice_rule_model->q_delete($obj);
            }

            if ($ret === FALSE) {
                $_SESSION["NOTICE"] = __LINE__ . " : " . $this->db->_error_message();
            }
        }

        $where = array();
        $option = array();

        $_SESSION["MC_QUERY"] = base_url() . "marketing/ixtens_reprice_rule/?" . $_SERVER["QUERY_STRING"];

        if ($this->input->get("platform_id") != "") {
            $where["platform_id LIKE"] = '%' . $this->input->get("platform_id") . '%';
        }

        if ($this->input->get("id") != "") {
            $where["id LIKE"] = '%' . $this->input->get('id') . '%';
        }

        if ($this->input->get("status") != "") {
            $where["status"] = $this->input->get("status");
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
            $sort = "id";

        if (empty($order))
            $order = "asc";

        $option["orderby"] = $sort . " " . $order;

        $data["list"] = $this->ixtens_reprice_rule_model->get_list($where, $option);
        //var_dump($this->db->last_query()." ".$this->db->_error_message());
        $data["total"] = $this->ixtens_reprice_rule_model->get_list($where, array("num_row" => 1));

        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
        $data["lang"] = $lang;

        $pconfig['total_rows'] = $data['total'];
        $this->pagination_service->set_show_count_tag(TRUE);
        $this->pagination_service->initialize($pconfig);

        $data["notice"] = notice($lang);

        $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
        $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
//      $data["searchdisplay"] = ($where["brand_name"]=="" && $where["regions"]=="")?'style="display:none"':"";
        $data["searchdisplay"] = "";

        $data["platform_list"] = $this->ixtens_reprice_rule_model->get_amazon_platform_list();

        $this->load->view('marketing/ixtens_reprice_rule/irr_index_v', $data);
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


?>