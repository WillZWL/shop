<?php

include_once "SupplierHelper.php";

class Supplier extends SupplierHelper
{
    private $appId = "SUP0001";
    private $lang_id = "en";

    public function __construct()
    {
        parent::__construct();
        $this->sc['Authorization']->checkAccessRights($this->getAppId(), "");
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

        $option['limit'] = ($this->input->get('limit') != '') ? $this->input->get('limit') : 20;
        $option['offset'] = ($this->input->get('per_page') != '') ? $this->input->get('per_page') : '';

        if (empty($sort))
            $sort = "id";

        if (empty($order))
            $order = "asc";

        $option["orderby"] = $sort . " " . $order;

        $data["objlist"] = $this->sc['Supplier']->getDao('Supplier')->getListWithName($where, $option);
        $data["total"] = $this->sc['Supplier']->getDao('Supplier')->getListWithName($where, ["num_rows" => 1]);

        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->getLangId() . ".php");
        $data["lang"] = $lang;

        $config['base_url'] = base_url('supply/supplier/');
        $config['total_rows'] = $data["total"];
        $config['page_query_string'] = true;
        $config['reuse_query_string'] = true;
        $config['per_page'] = $option['limit'];
        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();

        $data["notice"] = notice($lang);

        $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
        $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
        $data["searchdisplay"] = "";

        $this->load->view('supply/supplier/supplier_index_v', $data);
    }

    public function getLangId()
    {
        return $this->lang_id;
    }

    public function add()
    {

        $sub_app_id = $this->getAppId() . "01";

        if ($this->input->post("posted")) {
            if (isset($_SESSION["supplier_vo"])) {

                $data["supplier"] = unserialize($_SESSION["supplier_vo"]);

                $_POST["status"] = 1;
                set_value($data["supplier"], $_POST);


                if ($new_obj = $this->sc['Supplier']->getDao('Supplier')->insert($data["supplier"])) {
                    unset($_SESSION["supplier_vo"]);
                    $id = $new_obj->getId();
                    redirect(base_url() . "supply/supplier/view/" . $id);
                } else {
                    $_SESSION["NOTICE"] = "submit_error";
                }
            }
        }

        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->getLangId() . ".php");
        $data["lang"] = $lang;

        if (empty($data["supplier"])) {
            if (($data["supplier"] = $this->sc['Supplier']->getDao('Supplier')->get()) === FALSE) {
                $_SESSION["NOTICE"] = "sql_error";
            } else {
                $_SESSION["supplier_vo"] = serialize($data["supplier"]);
            }
        }

        $data["fc_list"] = $this->sc['Supplier']->getDao('FulfillmentCentre')->getList();

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
                    // $this->supplier_model->include_vo("dao");
                    $data["supplier"] = unserialize($_SESSION["supplier_obj"][$id]);

                    set_value($data["supplier"], $_POST);

                    if ($this->sc['Supplier']->getDao('Supplier')->update($data["supplier"])) {
                        unset($_SESSION["supplier_obj"]);
                        redirect(base_url() . "supply/supplier/view/" . $id . ($isnote ? "/1" : ""));
                    } else {
                        $_SESSION["NOTICE"] = $this->db->_error_message();
                    }
                }
            }

            include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->getLangId() . ".php");
            $data["lang"] = $lang;

            if (empty($data["supplier"])) {
                if (($data["supplier"] = $this->sc['Supplier']->getDao('Supplier')->get(["id" => $id])) === FALSE) {
                    $_SESSION["NOTICE"] = $this->db->_error_message();
                } else {
                    $_SESSION["supplier_obj"][$id] = serialize($data["supplier"]);
                }
            }

            $data["fc_list"] = $this->sc['Supplier']->getDao('FulfillmentCentre')->getList();

            $data["notice"] = notice($lang);
            $data["cmd"] = "edit";
            $this->load->view('supply/supplier/supplier_' . ($isnote ? "note" : "detail") . '_v', $data);
        }
    }

    public function delete($id = "")
    {
        if (($supplier_vo = $this->sc['Supplier']->getDao('Supplier')->get(["id" => $id])) === FALSE) {
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


