<?php

include_once "supplier_helper.php";

class Supplier extends Supplier_helper
{
    private $appId = "SUP0001";
    private $lang_id = "en";

    public function __construct()
    {
        parent::Supplier_helper();
        $this->authorization_service->check_access_rights($this->getAppId(), "");
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function index()
    {
        $sub_app_id = $this->getAppId() . "00";

        $_SESSION["LISTPAGE"] = base_url() . "supply/supplier/?" . $_SERVER['QUERY_STRING'];

        $where = array();
        $option = array();

        if ($this->input->get("id") != "") {
            $where["id"] = $this->input->get("id");
            $submit_search = 1;
        }

        if ($this->input->get("name") != "") {
            $where["name"] = $this->input->get("name");
            $submit_search = 1;
        }

        if ($this->input->get("currency_id") != "") {
            $where["currency_id"] = $this->input->get("currency_id");
            $submit_search = 1;
        }

        if ($this->input->get("supplier_reg") != "") {
            $where["supplier_reg"] = $this->input->get("supplier_reg");
            $submit_search = 1;
        }

        if ($this->input->get("sourcing_reg") != "") {
            $where["sourcing_reg"] = $this->input->get("sourcing_reg");
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
            $sort = "id";

        if (empty($order))
            $order = "asc";

        $option["orderby"] = $sort . " " . $order;

        $data["objlist"] = $this->supplier_model->get_supplier_list($where, $option);
        $data["total"] = $this->supplier_model->get_supplier_list_total($where);

        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
        $data["lang"] = $lang;

        $pconfig['total_rows'] = $data['total'];
        $this->pagination_service->set_show_count_tag(TRUE);
        $this->pagination_service->initialize($pconfig);

        $data["notice"] = notice($lang);

        $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
        $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
//      $data["searchdisplay"] = ($submit_search)?"":'style="display:none"';
        $data["searchdisplay"] = "";
        $this->load->view('supply/supplier/supplier_index_v', $data);
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }

    public function add()
    {

        $sub_app_id = $this->getAppId() . "01";

        if ($this->input->post("posted")) {
            if (isset($_SESSION["supplier_vo"])) {
                $this->supplier_model->include_vo("dao");
                $data["supplier"] = unserialize($_SESSION["supplier_vo"]);

                $_POST["status"] = 1;
                set_value($data["supplier"], $_POST);


                if ($new_obj = $this->supplier_model->add("dao", $data["supplier"])) {
                    unset($_SESSION["supplier_vo"]);
                    $id = $new_obj->get_id();
                    redirect(base_url() . "supply/supplier/view/" . $id);
                } else {
                    $_SESSION["NOTICE"] = "submit_error";
                }
            }
        }

        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
        $data["lang"] = $lang;

        if (empty($data["supplier"])) {
            if (($data["supplier"] = $this->supplier_model->get("dao")) === FALSE) {
                $_SESSION["NOTICE"] = "sql_error";
            } else {
                $_SESSION["supplier_vo"] = serialize($data["supplier"]);
            }
        }

        $data["fc_list"] = $this->supplier_model->fulfillment_centre_service->get_list();

        $data["notice"] = notice($lang);
        $data["cmd"] = "add";
        $this->load->view('supply/supplier/supplier_detail_v', $data);
    }

    public function view($id = "", $isnote = 0)
    {
        if ($id) {
            $sub_app_id = $this->getAppId() . "02";

            if ($this->input->post("posted")) {

                if (isset($_SESSION["supplier_obj"][$id])) {
                    $this->supplier_model->include_vo("dao");
                    $data["supplier"] = unserialize($_SESSION["supplier_obj"][$id]);

                    set_value($data["supplier"], $_POST);

                    if ($this->supplier_model->update("dao", $data["supplier"])) {
                        unset($_SESSION["supplier_obj"]);
                        redirect(base_url() . "supply/supplier/view/" . $id . ($isnote ? "/1" : ""));
                    } else {
                        $_SESSION["NOTICE"] = $this->db->_error_message();
                    }
                }
            }

            include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
            $data["lang"] = $lang;

            if (empty($data["supplier"])) {
                if (($data["supplier"] = $this->supplier_model->get("dao", array("id" => $id))) === FALSE) {
                    $_SESSION["NOTICE"] = $this->db->_error_message();
                } else {
                    $_SESSION["supplier_obj"][$id] = serialize($data["supplier"]);
                }
            }

            $data["fc_list"] = $this->supplier_model->fulfillment_centre_service->get_list();

            $data["notice"] = notice($lang);
            $data["cmd"] = "edit";
            $this->load->view('supply/supplier/supplier_' . ($isnote ? "note" : "detail") . '_v', $data);
        }
    }

    public function delete($id = "")
    {
        if (($supplier_vo = $this->supplier_model->get_supplier(array("id" => $id))) === FALSE) {
            $_SESSION["NOTICE"] = "submit_error";
        } else {
            if (empty($supplier_vo)) {
                $_SESSION["NOTICE"] = "supplier_not_found";
            } else {
                if (!$this->supplier_model->inactive_supplier($supplier_vo)) {
                    $_SESSION["NOTICE"] = "submit_error";
                }
            }
        }
        if (isset($_SESSION["LISTPAGE"])) {
            redirect($_SESSION["LISTPAGE"]);
        } else {
            redirect(current_url());
        }

    }
}


