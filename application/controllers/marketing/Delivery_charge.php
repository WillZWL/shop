<?php

class Delivery_charge extends MY_Controller
{

    private $app_id = "MKT0062";
    private $lang_id = "en";


    public function __construct()
    {
        parent::__construct();
        $this->load->model('marketing/delivery_charge_model');
        $this->load->helper('url');
        $this->load->helper('notice');
        $this->load->helper('object');
        $this->load->library('service/pagination_service');
        $this->load->library('service/context_config_service');
        $this->load->library('service/platform_biz_var_service');
    }

    public function add()
    {
        $sub_app_id = $this->_get_app_id() . "01";

        if ($this->input->post("posted")) {
            if (isset($_SESSION["weight_cat_vo"])) {
                $this->delivery_charge_model->include_weight_cat_vo();
                $data["weight_cat"] = unserialize($_SESSION["weight_cat_vo"]);

                set_value($data["weight_cat"], $_POST);

                $weight = $data["weight_cat"]->get_weight();
                $proc = $this->delivery_charge_model->get_weight_cat(array("weight" => $weight));
                if (!empty($proc)) {
                    $_SESSION["NOTICE"] = "weight_cat_existed";
                } else {

                    if ($newobj = $this->delivery_charge_model->add_weight_cat($data["weight_cat"])) {
                        if ($objlist = $this->delivery_charge_model->get_wcc_nearest_amount($newobj->get_id(), $data["weight_cat"]->get_weight())) {
                            foreach ($objlist as $obj) {
                                $obj->set_wcat_id($newobj->get_id());
                                $this->delivery_charge_model->add_wcc($obj);
                            }
                        }
                        unset($_SESSION["weight_cat_vo"]);
                        redirect(base_url() . "marketing/delivery_charge/index/weight?" . $_SERVER['QUERY_STRING']);
                    } else {
                        $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->db->_error_message();
                    }
                }
            }
        }
        $this->index();
    }

    public function _get_app_id()
    {
        return $this->app_id;
    }

    public function index($cat_type = "weight", $cat_id = "")
    {
        $sub_app_id = $this->_get_app_id() . "00";

        $where = array();
        $option = array();

        $sort = $this->input->get("sort");
        $order = $this->input->get("order");

        if ($this->input->get("weight") != "") {
            $where["weight"] = $this->input->get("weight");
        }

        if (empty($sort)) {
            $sort = "weight";
        }

        if (empty($order)) {
            $order = "asc";
        }

        $option["orderby"] = $sort . " " . $order;
        $_SESSION["LISTPAGE"] = base_url() . "marketing/delivery_charge/index/?" . $_SERVER['QUERY_STRING'];

        $data["objlist"] = $this->delivery_charge_model->get_weight_cat_list($where, $option);
        $data["total"] = $this->delivery_charge_model->get_weight_cat_total($where, $option);
        $data["searchdisplay"] = "";

        if (empty($_SESSION["weight_cat_vo"])) {
            if (($weight_cat_vo = $this->delivery_charge_model->get_weight_cat()) === FALSE) {
                $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->db->_error_message();
            } else {
                $_SESSION["weight_cat_vo"] = serialize($weight_cat_vo);
            }
        }

        if (empty($_SESSION["weight_cat_obj"][$cat_id])) {
            if (($data["weight_cat_obj"] = $this->delivery_charge_model->get_weight_cat(array("id" => $cat_id))) === FALSE) {
                $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->db->_error_message();
            } else {
                unset($_SESSION["weight_cat_obj"]);
                $_SESSION["weight_cat_obj"][$cat_id] = serialize($data["weight_cat_obj"]);
            }
        }
        $view_file = 'marketing/delivery_charge/delivery_charge_index_v';

        $data["delivery_type_list"] = $this->delivery_charge_model->get_delivery_type_list();

        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
        $data["lang"] = $lang;

        $data["notice"] = notice($lang);

        $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
        $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";

        $data["cat_id"] = $cat_id;

        $this->load->view($view_file, $data);
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }

    public function view($delivery_type = "")
    {
        if ($delivery_type) {
            $sub_app_id = $this->_get_app_id() . "02";
            include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
            $data["lang"] = $lang;
            if ($this->input->post("posted")) {
                $wcc_vo = $this->delivery_charge_model->get_weight_cat_charge_obj();
                foreach ($_POST["value"] AS $wcat_id => $country_value_arr) {
                    foreach ($country_value_arr AS $dest_country => $value) {
                        $obj = $this->delivery_charge_model->get_weight_cat_charge_obj(array("wcat_id" => $wcat_id, "delivery_type" => $delivery_type, "dest_country" => $dest_country));
                        if (!$obj) {
                            $obj = clone $wcc_vo;
                            $obj->set_wcat_id($wcat_id);
                            $obj->set_delivery_type($delivery_type);
                            $obj->set_dest_country($dest_country);
                            $obj->set_currency_id($value['curr_id']);
                            $action = "insert_wcc";
                        } else {
                            $action = "update_wcc";
                        }
                        $obj->set_amount($value['amount']);

                        if (!$this->delivery_charge_model->$action($obj)) {
                            $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->db->_error_message();
                        }
                    }
                }
                if (empty($_SESSION["NOTICE"])) {
                    redirect(current_url() . "?" . $_SERVER['QUERY_STRING']);
                }
            }

            $sort = $this->input->get("sort");
            $order = $this->input->get("order");

            if (empty($sort)) {
                $sort = "weight";
            }

            if (empty($order)) {
                $order = "asc";
            }

            $option["orderby"] = $sort . " " . $order;

            $data["wcc_list"] = $this->delivery_charge_model->weight_cat_service->get_full_weight_cat_charge_list(array("delivery_type" => $delivery_type), array("orderby" => "wcat_id ASC", "limit" => -1));
            $data["wc_list"] = $this->delivery_charge_model->weight_cat_service->get_list(array(), array("orderby" => "weight ASC", "limit" => -1));
            $data["dest_country_list"] = $this->platform_biz_var_service->get_dest_country_w_delivery_type_list();
            $delivery_type_obj = $this->delivery_charge_model->delivery_type_service->get(array("id" => $delivery_type));
            $data["platform_type"] = $delivery_type_obj->get_platform_type();

            $data["objlist"] = $wcc_list;

            include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
            $data["lang"] = $lang;

            $data["delivery_type_list"] = $this->delivery_charge_model->get_delivery_type_list();
            $data["delivery_type"] = $delivery_type;
            $data["notice"] = notice($lang);
            $data["cmd"] = "edit";
            $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
            $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
            $this->load->view('marketing/delivery_charge/delivery_charge_detail_v', $data);
        }
    }

    public function edit($cat_id)
    {
        $sub_app_id = $this->_get_app_id() . "02";

        if ($this->input->post("posted")) {
            unset($_SESSION["NOTICE"]);

            if (isset($_SESSION["weight_cat_obj"][$cat_id])) {
                $this->delivery_charge_model->include_weight_cat_vo();
                $data["weight_cat"] = unserialize($_SESSION["weight_cat_obj"][$cat_id]);

                if ($data["weight_cat"]->get_weight() != $_POST["weight"]) {
                    $proc = $this->delivery_charge_model->get_weight_cat(array("weight" => $_POST["weight"]));
                    if (!empty($proc)) {
                        $_SESSION["NOTICE"] = "weight_cat_existed";
                    }
                }
                if (empty($_SESSION["NOTICE"])) {
                    set_value($data["weight_cat"], $_POST);

                    if ($this->delivery_charge_model->update_weight_cat($data["weight_cat"])) {
                        unset($_SESSION["weight_cat_obj"]);
                        redirect(base_url() . "marketing/delivery_charge/index/weight?" . $_SERVER['QUERY_STRING']);
                    } else {
                        $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->db->_error_message();
                    }
                }
            }
        }
        $this->index($cat_type, $_POST["id"]);

    }

    public function delete($id = "")
    {

    }
}

/* End of file freight.php */
/* Location: ./system/application/controllers/freight.php */
