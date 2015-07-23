<?php

class Faqadmin extends MY_Controller
{
    private $app_id = "CS0003";
    private $lang_id = "en";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('cs/faqadmin_model');
        $this->load->helper(array("url", "notice"));
        $this->load->library('service/pagination_service');
    }


    public function index($edit = "", $eid = "")
    {
        $sub_app_id = $this->_get_app_id() . "01";
        $_SESSION["LISTPAGE"] = ($prod_grp_cd == "" ? base_url() . "marketing/product/?" : current_url()) . $_SERVER['QUERY_STRING'];

        if ($this->input->post("posted")) {

            if ($this->input->post("action") == "add") {
                $obj = $this->faqadmin_model->get();
                $method = "insert";
                $obj->set_lang_id($this->input->post("lang_id"));
            } else {
                $obj = $this->faqadmin_model->get(array("lang_id" => $this->input->post("lang_id")));
                $method = "update";
            }
            $obj->set_faq_ver($this->input->post("faq_ver"));

            if ($this->faqadmin_model->$method($obj) === FALSE) {
                $_SESSION["notice"] = $this->db->_error_message();
            }

            Redirect(base_url() . "cs/faqadmin/?" . $_SERVER["QUERY_STRING"]);
        }

        $where = array();
        $option = array();

        if ($this->input->get("lang_id") != "") {
            $where["lang_id LIKE"] = '%' . $this->input->get("lang_id") . '%';
        }

        if ($this->input->get("faq_ver") != "") {
            $where["faq_ver LIKE"] = '%' . $this->input->get("lang_id") . '%';
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
            $sort = "lang_id";

        if (empty($order))
            $order = "ASC";

        $option["orderby"] = $sort . " " . $order;


        $data = $this->faqadmin_model->get_list_cnt($where, $option);

        include_once APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php";
        $data["lang"] = $lang;

        unset($where);
        unset($option);

        $data["faq_version"] = array("cveng" => $lang["english"], "cv-fr" => $lang["french"], "cv-de" => $lang["german"], "cv-es" => $lang["espanol"]);
        $data["edit"] = $edit;
        $data["eid"] = $eid;
        $data["notice"] = notice($lang);
        $this->load->view("cs/faqadmin/v_index", $data);

    }

    public function _get_app_id()
    {
        return $this->app_id;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }

}

