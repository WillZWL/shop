<?php

class Upselling extends MY_Controller
{

    private $app_id = "MKT0011";
    private $lang_id = "en";


    public function __construct()
    {
        parent::__construct();
        $this->load->model('marketing/upselling_model');
        $this->load->helper('url');
        $this->load->helper('notice');
        $this->load->helper('object');
        $this->load->library('service/pagination_service');
        $this->load->library('service/context_config_service');
        $this->load->library('service/translate_service');
    }

    public function index()
    {
        $sub_app_id = $this->_get_app_id() . "00";
        $_SESSION["LISTPAGE"] = base_url() . "marketing/upselling/?" . $_SERVER['QUERY_STRING'];

        $where = array();
        $option = array();

        $submit_search = 0;

        if ($this->input->get("proc_status") === FALSE) {
            $_GET["proc_status"] = "0";
            $where["proc_status"] = "0";
        } else {
            if ($this->input->get("proc_status") != "") {
                $where["proc_status"] = $this->input->get("proc_status");
            }
        }

        $where["website_status"] = "I";

        if ($this->input->get("sku") != "") {
            $where["sku"] = $this->input->get("sku");
            $submit_search = 1;
        }

        if ($this->input->get("name") != "") {
            $where["name"] = $this->input->get("name");
            $submit_search = 1;
        }

        if ($this->input->get("colour") != "") {
            $where["colour"] = $this->input->get("colour");
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

        if ($this->input->get("create_on") != "") {
            $where["create_on"] = $this->input->get("create_on");
            $submit_search = 1;
        }

        if ($this->input->get("status") != "") {
            $where["status"] = $this->input->get("status");
            $submit_search = 1;
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
            $sort = "create_on";

        if (empty($order))
            $order = "desc";

        $option["orderby"] = $sort . " " . $order;
        $option["exclude_bundle"] = 1;

        $data["objlist"] = $this->upselling_model->get_product_list($where, $option);
        $data["total"] = $this->upselling_model->get_product_list_total($where);

        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
        $data["lang"] = $lang;

        $pconfig['total_rows'] = $data['total'];
        $this->pagination_service->set_show_count_tag(TRUE);
        $this->pagination_service->initialize($pconfig);

        $data["notice"] = notice($lang);

        $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
        $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
        $data["searchdisplay"] = "";
        $this->load->view('marketing/upselling/upselling_index_v', $data);
    }

    public function _get_app_id()
    {
        return $this->app_id;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }

    public function proc($sku, $proc = 3)
    {
        if ($sku) {
            if ($obj = $this->upselling_model->get("product", array("sku" => $sku))) {
                $obj->set_proc_status($proc);
                if ($this->upselling_model->update("product", $obj)) {
                    if (isset($_SESSION["LISTPAGE"])) {
                        redirect($_SESSION["LISTPAGE"]);
                    } else {
                        redirect(base_url() . "marketing/upselling");
                    }
                } else {
                    $_SESSION["NOTICE"] = $this->db->_error_message();
                }
            } else {
                $_SESSION["NOTICE"] = "product_not_found";
            }
        }
    }

    function add($sku = "")
    {
        if ($sku == "") {
            show_404();
        }

        if ($data["product"] = $this->upselling_model->product_service->get_dao()->get_prod_wo_bundle(array("sku" => $sku), array("limit" => 1))) {
            $sub_app_id = $this->_get_app_id() . "01";
            $data["sku"] = $sku;
            include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
            $data["lang"] = $lang;
            $this->load->view('marketing/upselling/upselling_detail_v', $data);
        } else {
            show_404();
        }
    }

    function group_list()
    {
        $sub_app_id = $this->_get_app_id() . "02";
        $_SESSION["LISTPAGE"] = base_url() . "marketing/upselling/group_list/?" . $_SERVER['QUERY_STRING'];

        $where = array();
        $option = array();

        $submit_search = 0;

        if ($this->input->get("name") != "") {
            $where["group_name like '%" . $this->input->get("name") . "%'"] = NULL;
            $submit_search = 1;
        }

        if ($this->input->get("status") != "") {
            $where["status"] = $this->input->get("status");
            $submit_search = 1;
        }

        if ($this->input->get("warranty") != "") {
            $where["warranty"] = $this->input->get("warranty");
            $submit_search = 1;
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
            $sort = "group_name";

        if (empty($order))
            $order = "asc";

        $option["orderby"] = $sort . " " . $order;

        $data["objlist"] = $this->upselling_model->get_ra_group_list($where, $option);
        $data["total"] = $this->upselling_model->get_ra_group_list_total($where);

        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
        $data["lang"] = $lang;

        $pconfig['total_rows'] = $data['total'];
        $this->pagination_service->set_show_count_tag(TRUE);
        $this->pagination_service->initialize($pconfig);

        $data["notice"] = notice($lang);
        $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
        $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
        $data["searchdisplay"] = "";
        $this->load->view('marketing/upselling/upselling_group_index_v', $data);
    }

    public function add_group()
    {
        if ($this->input->post('posted')) {
            $skui = $this->input->post('sku');
            if (!is_array($skui)) {
                $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . "should_have_item";
                Redirect(base_url() . 'marketing/upselling/add_group/');
                return;
            }

            $err = 0;
            $ra_group_obj = $this->upselling_model->get_ra_group();
            $ra_group_obj->set_group_name($this->input->post('group_name'));
            $ra_group_obj->set_status(1);

            $warranty = $this->input->post('warranty');
            if (isset($warranty))
                $ra_group_obj->set_warranty($warranty);
            else
                $ra_group_obj->set_warranty(0);

            $this->upselling_model->start_transaction();
            $result = $this->upselling_model->insert_ra_group($ra_group_obj);
            if ($result === FALSE) {
                $err++;
                $_SESSION['NOTICE'] = 'ERROR ' . __LINE__ . ' : ' . $this->db->_error_message();
            } else {
                $priority = $this->input->post('priority');

                foreach ($skui as $value) {
                    $ra_group_product_obj = $this->upselling_model->get_ra_group_product();
                    $ra_group_product_obj->set_ra_group_id($ra_group_obj->get_group_id());
                    $ra_group_product_obj->set_sku($value);
                    $ra_group_product_obj->set_priority($priority[$value]);

                    $ret = $this->upselling_model->insert_ra_group_product($ra_group_product_obj);
                    if ($ret === FALSE) {
                        $err++;
                        $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->db->_error_message();
                    }
                }
            }
            $this->upselling_model->end_transaction();

            if (!$err) {
                unset($_SESSION['NOTICE']);
                Redirect(base_url() . 'marketing/upselling/group_list/');
            }
            Redirect(base_url() . 'marketing/upselling/add_group/');
        }

        $this->load->view('marketing/upselling/upselling_group_detail_add', $data);
    }

    public function group_add_left()
    {
        $where = array();
        $option = array();
        $sub_app_id = $this->_get_app_id() . '03';
        include_once(APPPATH . 'language/' . $sub_app_id . "_" . $this->_get_lang_id() . '.php');
        $data['lang'] = $lang;

        if (($sku = $this->input->get('sku')) != '' || ($prod_name = $this->input->get('name')) != '') {
            $data['search'] = 1;

            if ($sku != '') {
                $where['sku'] = $sku;
            }

            if ($prod_name != '') {
                $where['name'] = $prod_name;
            }

            $sort = $this->input->get('sort');
            $order = $this->input->get('order');

            $limit = '20';

            $pconfig['base_url'] = current_url() . '?' . $_SERVER['QUERY_STRING'];
            $option['limit'] = $pconfig['per_page'] = $limit;

            if ($option['limit']) {
                $option['offset'] = $this->input->get('per_page');
            }

            if (empty($sort))
                $sort = 'sku';

            if (empty($order))
                $order = 'asc';

            $option['orderby'] = $sort . ' ' . $order;

            $data['objlist'] = $this->upselling_model->get_product_list($where, $option);
            $data['total'] = $this->upselling_model->get_product_list_total($where);
            $pconfig['total_rows'] = $data['total'];
            $this->pagination_service->set_show_count_tag(TRUE);
            $this->pagination_service->msg_br = TRUE;
            $this->pagination_service->initialize($pconfig);

            $data['sortimg'][$sort] = '<img src="' . base_url() . 'images/' . $order . '.gif">';
            $data['xsort'][$sort] = $order == 'asc' ? 'desc' : 'asc';
        }

        $this->load->view('marketing/upselling/v_prodlist', $data);
    }

    public function group_add_right()
    {
        $sub_app_id = $this->_get_app_id() . '04';
        include_once(APPPATH . 'language/' . $sub_app_id . '_' . $this->_get_lang_id() . '.php');
        $data['lang'] = $lang;
        $data['notice'] = notice($lang);

        $this->load->view('marketing/upselling/v_group_add', $data);
    }

    public function view_group($group_id = '', $lang_id = "en")
    {
        if ($group_id == '') {
            Redirect(base_url() . 'marketing/upselling/group_list');
        }

        if ($this->input->post('posted')) {
            $group_name = $this->input->post('group_name');
            $status = $this->input->post('status');
            $update_on = $this->input->post('modify_on');

            $warranty = $this->input->post('warranty');
            if (!isset($warranty))
                $warranty = 0;

            $ra_group_obj = $this->upselling_model->get_ra_group($group_id);
            if (!$ra_group_obj) {
                $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . "cannot get ra_group_obj";
                Redirect(base_url() . 'marketing/upselling/group_list');
            }

            if ($ra_group_obj->get_modify_on() > $update_on) {
                $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . "updated_by_other";
                Redirect(base_url() . 'marketing/upselling/group_list');
            }

            $err = 0;
            $this->upselling_model->start_transaction();

            $ra_group_obj->set_group_name($group_name);
            $ra_group_obj->set_status($status);
            $ra_group_obj->set_warranty($warranty);

            $result = $this->upselling_model->update_ra_group($ra_group_obj);
            if ($result === FALSE) {
                $err++;
                $_SESSION['NOTICE'] = 'ERROR ' . __LINE__ . ' : ' . $this->db->_error_message();
            } else {
                $ra_group_content_obj = $this->upselling_model->get_ra_group_content($group_id, $this->input->post('lang_id'));
                if (!$ra_group_content_obj) {
                    $ra_group_content_obj = $this->upselling_model->ra_group_service->get_rgc_dao()->get();
                    $ra_group_content_obj->set_group_id($group_id)->set_lang_id($this->input->post('lang_id'))->set_group_display_name($this->input->post('group_display_name'));

                    $resultOfRGC = $this->upselling_model->insert_ra_group_content($ra_group_content_obj);
                } else {
                    $ra_group_content_obj->set_group_display_name($this->input->post('group_display_name'));
                    $resultOfRGC = $this->upselling_model->update_ra_group_content($ra_group_content_obj);
                }

                if ($resultOfRGC === FALSE) {
                    $err++;
                    $_SESSION['NOTICE'] = 'ERROR ' . __LINE__ . ' : ' . $this->db->_error_message();
                } else {
                    $this->upselling_model->remove_ra_group_product($group_id);

                    $skulist = $this->input->post('sku');
                    $priority = $this->input->post('priority');
                    foreach ($skulist as $value) {
                        $ra_group_product_obj = $this->upselling_model->get_ra_group_product();
                        $ra_group_product_obj->set_ra_group_id($group_id);
                        $ra_group_product_obj->set_sku($value);
                        $ra_group_product_obj->set_priority($priority[$value]);

                        $ret = $this->upselling_model->insert_ra_group_product($ra_group_product_obj);
                        if ($ret === FALSE) {
                            $err++;
                            $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->db->_error_message();
                        }
                    }
                }
            }
            $this->upselling_model->end_transaction();

            if (!$err) {
                unset($_SESSION['NOTICE']);
                Redirect(base_url() . 'marketing/upselling/view_group/' . $group_id . '/' . $lang_id);
            }
        }

        $data['group_id'] = $group_id;
        $data['lang_id'] = $lang_id;
        $this->load->view('marketing/upselling/upselling_group_detail_view', $data);
    }

    function group_view_right($group_id = '', $lang_id = "en")
    {
        $ra_group_obj = $this->upselling_model->get_ra_group($group_id);
        if (empty($ra_group_obj)) {
            Redirect(base_url() . 'marketing/upselling/group_list');
        } else {
            $sub_app_id = $this->_get_app_id() . '05';
            include_once(APPPATH . 'language/' . $sub_app_id . '_' . $this->_get_lang_id() . '.php');

            $ra_group_content_obj = $this->upselling_model->get_ra_group_content($group_id, $lang_id);
            if (!$ra_group_content_obj)
                $ra_group_content_obj = $this->upselling_model->ra_group_service->get_rgc_dao()->get();

            $data['ra_group_obj'] = $ra_group_obj;
            $data['ra_group_content_obj'] = $ra_group_content_obj;
            $data['ra_group_product'] = $this->upselling_model->get_ra_group_product_list_w_name($group_id);
            $data['lang'] = $lang;
            $data['notice'] = notice($lang);

            $data["lang_list"] = $this->upselling_model->get_list("language", array("status" => 1), array("orderby" => "name ASC"));
            $data["lang_list_str"] = '';
            foreach ($data["lang_list"] as $v) {
                $data["lang_list_str"] .= $v->get_id() . ",";
            }
            $data["lang_list_str"] = rtrim($data["lang_list_str"], ',');

            $data['lang_id'] = $lang_id;

            $this->load->view('marketing/upselling/v_group_view', $data);
        }
    }

    public function translate_ra_group_content($group_id = "", $lang_id = "en")
    {
        $lang_id_list = explode(',', $lang_id);
        foreach ($lang_id_list as $lang_id) {
            $this->upselling_model->ra_group_service->translate_ra_group_content($group_id, $lang_id);
        }
        Redirect(base_url() . "marketing/upselling/group_view_right/" . $group_id . "/" . $lang_id);
    }
}

/* End of file product.php */
/* Location: ./app/controllers/product.php */