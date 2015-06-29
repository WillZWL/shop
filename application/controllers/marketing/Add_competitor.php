<?php

class Add_competitor extends MY_Controller
{

    public $default_platform_id;
    private $app_id = 'MKT0076';

    //must set to public for view
    private $lang_id = 'en';

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url', 'notice', 'image'));
        $this->load->library('input');
        $this->load->model('marketing/add_competitor_model');
        $this->load->model('marketing/competitor_model');
        $this->load->library('service/country_service');
        $this->load->library('dao/competitor_dao');
        $this->load->library('service/pagination_service');

        $this->load->library('service/context_config_service');
        $this->default_platform_id = $this->context_config_service->value_of("default_platform_id");
    }

    public function index($country_id = "")
    {
        $data = array();
        $data["lang"] = $lang;

        if ($this->input->post('posted')) {
            if ($this->input->post('action') == 'add_comp') {
                # add new competitors
                $ret = $this->add_new_competitor();

                if ($ret["status"] === FALSE) {
                    $_SESSION["NOTICE"] = $ret["error_msg"];
                } else {
                    $_SESSION["NOTICE"] = "New competitor added!";
                }
            }

            if ($this->input->post('action') == 'edit') {
                # update existing competitors
                $ret = $this->edit_competitor();

                if ($ret["status"] === FALSE) {
                    $_SESSION["NOTICE"] = $ret["error_msg"];
                } else {
                    $_SESSION["NOTICE"] = "Competitor updated!";
                    Redirect(base_url() . "marketing/add_competitor/");
                }
            }
        }

        include_once APPPATH . "language/" . $this->_get_app_id() . "00_" . $this->_get_lang_id() . ".php";
        $data["lang"] = $lang;
        $data["country_list"] = $this->country_service->get_sell_country_list();
        if ($country_id) {
            $data["country_id"] = $country_id;
        }

        foreach ($data["country_list"] as $country_obj) {
            if ($country_id == $country_obj->get_id()) {
                $data["currency"] = $country_obj->get_currency_id();
                break;
            }
        }

        $where = array();
        $option = array();

        if ($this->input->get("country_id") != "") {
            $where["country_id LIKE"] = '%' . $this->input->get("country_id") . '%';
        }

        if ($this->input->get("competitor_name") != "") {
            $where["competitor_name LIKE"] = '%' . $this->input->get('competitor_name') . '%';
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

        # default order
        if (empty($sort))
            $sort = "country_id";

        if (empty($order))
            $order = "asc";

        $option["orderby"] = $sort . " " . $order;

        $data["list"] = $this->competitor_dao->get_list($where, $option);
        $data["total"] = $this->competitor_dao->get_list_index($where, array("num_row" => 1));
        $pconfig['total_rows'] = $data['total'];

        $this->pagination_service->set_show_count_tag(TRUE);
        $this->pagination_service->initialize($pconfig);

        $data["notice"] = notice($lang);
        $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
        $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";


        $this->load->view("marketing/add_competitor/add_competitor_index", $data);
    }

    private function add_new_competitor()
    {
        if (!($status = $this->input->post('status'))) $status = 1;
        $result = array();
        $result["status"] = FALSE;

        $competitor_dao = $this->competitor_dao;

        # check for duplicate
        if (!($competitor_dao->get(array('competitor_name' => $competitor_name, 'country_id' => $country_id)))) {
            $competitor_vo = $competitor_dao->get();
            $competitor_vo->set_competitor_name($this->input->post('name'));
            $competitor_vo->set_country_id($this->input->post('country'));
            $competitor_vo->set_status($status);

            if ($competitor_dao->insert($competitor_vo)) {
                $result["status"] = TRUE;
            } else {
                $result["error_msg"] = "Error adding competitor. \\nDB error msg: <" . $this->db->_error_message() . ">\\n" . __FILE__ . " Line: " . __LINE__;
            }
        } else {
            $result["error_msg"] = "Competitor already exists in selected country.";
        }

        return $result;
    }

    private function edit_competitor()
    {
        $result = array();
        $result["status"] = FALSE;

        $competitor_dao = $this->competitor_dao;
        $id = $this->input->post('comp_id');
        $name = $this->input->post('name');
        $old_country_id = $this->input->post('old_country_id'); # to determine what to update
        $new_country_id = $this->input->post('country_id');

        if ($competitor_obj = $competitor_dao->get(array("id" => $id, "country_id" => $old_country_id))) {
            $competitor_obj->set_competitor_name($name);
            $competitor_obj->set_country_id($new_country_id);
            $competitor_obj->set_status($this->input->post('status'));

            if ($competitor_dao->update($competitor_obj)) {
                $result["status"] = TRUE;
            } else {
                $result["error_msg"] = "Update failed - \\nDB error msg: <" . $this->db->_error_message() . ">\\n" . __FILE__ . " Line: " . __LINE__;
            }
        } else {
            $result["error_msg"] = "Update failed - Competitor doesn't exist.";
        }

        return $result;
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

?>