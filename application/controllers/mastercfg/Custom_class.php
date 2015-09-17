<?php
class Custom_class extends MY_Controller
{

    private $appId = "MST0008";
    private $lang_id = "en";


    public function __construct()
    {
        parent::__construct();
    }

    public function add()
    {

        $sub_app_id = $this->getAppId() . "01";

        if ($this->input->post("posted")) {

            if (isset($_SESSION["cc_vo"])) {
                $data["cc"] = unserialize($_SESSION["cc_vo"]);

                set_value($data["cc"], $_POST);
                $proc = $this->sc['customClassModel']->getCustomClass(["country_id" => $data["cc"]->getCountryId(), "code" => $data["cc"]->getCode()]);
                if (!empty($proc)) {
                    $_SESSION["NOTICE"] = "code_existed";
                } else {

                    if ($new_obj = $this->sc['customClassModel']->addCustomClass($data["cc"])) {
                        unset($_SESSION["cc_vo"]);
                        redirect(base_url() . "mastercfg/custom_class/index/" . $this->input->post("country_id") . "/?" . $_SERVER['QUERY_STRING']);
                    } else {
                        $_SESSION["NOTICE"] = "submit_error";
                    }
                }
            }
        }

        $this->index($this->input->post("country_id"));
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function index($country_id = "", $cc_id = "", $offset = 0)
    {
        $sub_app_id = $this->getAppId() . "00";

        $_SESSION["LISTPAGE"] = base_url() . "mastercfg/custom_class/" . ($country_id == "" ? "" : "index/" . $country_id) . ($cc_id == "" ? "" : "/" . $cc_id) ."?" . $_SERVER['QUERY_STRING'];

        $where = array();
        $option = array();

        $submit_search = 0;

        if ($country_id != "") {
            $where["country_id"] = $country_id;
        }
        if ($this->input->get("id") != "") {
            $where["id"] = $this->input->get("id");
            $submit_search = 1;
        }
        if ($this->input->get("code") != "") {
            $where["code LIKE "] = "%" . $this->input->get("code") . "%";
            $submit_search = 1;
        }
        if ($this->input->get("description") != "") {
            $where["description LIKE "] = "%" . $this->input->get("description") . "%";
            $submit_search = 1;
        }
        if ($this->input->get("duty_pcent") != "") {
            fetch_operator($where, "duty_pcent", $this->input->get("duty_pcent"));
            $submit_search = 1;
        }

        $sort = $this->input->get("sort");
        $order = $this->input->get("order");

        $limit = '20';

        $option["limit"] = $limit;
        $option["offset"] = $offset;

        if (empty($sort))
            $sort = "id";

        if (empty($order))
            $order = "asc";

        $option["orderby"] = $sort . " " . $order;

        if ($country_id) {
            $data = $this->sc['customClassModel']->getCustomClassObjList($where, $option);
        } else {
            $data['total'] = 0;
        }

        $data["countrylist"] = $this->sc['customClassModel']->getCountryList(array("status" => 1), array("limit" => -1, "orderby" => "name"));

        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->getLangId() . ".php");
        $data["lang"] = $lang;
        $ccid = $cc_id ? $cc_id : 0;
        $config['base_url'] = base_url("mastercfg/custom_class/index/$country_id/$ccid/");
        $config['total_rows'] = $data["total"];
        $config['per_page'] = $limit;

        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();

        $data["notice"] = notice($lang);

        $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
        $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";

        $data["searchdisplay"] = "";


        if (empty($_SESSION["cc_vo"])) {
            if (($cc_vo = $this->sc['customClassModel']->getCustomClass()) === FALSE) {
                $_SESSION["NOTICE"] = "sql_error";
            } else {
                $_SESSION["cc_vo"] = serialize($cc_vo);
            }
        }

        if (empty($_SESSION["cc_obj"][$cc_id])) {
            if (($data["cc_obj"] = $this->sc['customClassModel']->getCustomClass(array("id" => $cc_id))) === FALSE) {
                $_SESSION["NOTICE"] = "sql_error";
            } else {
                unset($_SESSION["cc_obj"]);
                $_SESSION["cc_obj"][$cc_id] = serialize($data["cc_obj"]);
            }
        }

        $data["cmd"] = ($cc_id == "") ? $this->input->post("cmd") : "edit";
        $data["country_id"] = $country_id;
        $data["cc_id"] = $cc_id;
        $data["offset"] = $offset;

        $this->load->view('mastercfg/custom_class/custom_class_index_v', $data);
    }

    public function edit($id)
    {
        $sub_app_id = $this->getAppId() . "02";

        if ($this->input->post("posted")) {
            unset($_SESSION["NOTICE"]);

            if (isset($_SESSION["cc_obj"][$id])) {
                $data["cc"] = unserialize($_SESSION["cc_obj"][$id]);
                if ($data["cc"]->getId() != $_POST["id"]) {
                    $proc = $this->sc['customClassModel']->getCustomClass(array("id" => $_POST["id"]));
                    if (!empty($proc)) {
                        $_SESSION["NOTICE"] = "custom_classification_existed";
                    }
                }
                if (empty($_SESSION["NOTICE"])) {
                    set_value($data["cc"], $_POST);

                    if ($this->sc['customClassModel']->updateCustomClass($data["cc"])) {
                        unset($_SESSION["cc_obj"]);
                        redirect(base_url() . "mastercfg/custom_class/index/" . $this->input->post("country_id") . "/?" . $_SERVER['QUERY_STRING']);
                    } else {
                        $_SESSION["NOTICE"] = "submit_error";
                    }
                }
            }
        }

        $this->index($this->input->post("country_id"), $_POST["id"]);

    }

    public function delete($id = "")
    {

    }

    public function edit_sku($sku)
    {
        $sub_app_id = $this->getAppId() . "02";

        if ($this->input->post("posted")) {
            unset($_SESSION["NOTICE"]);

            if (isset($_SESSION["pcc_obj"][$sku])) {
                $data["pcc"] = unserialize($_SESSION["pcc_obj"][$sku]);
                if ($data["pcc"]->getSku() != $_POST["sku"]) {
                    $proc = $this->sc['customClassModel']->getProductCustomClass(array("sku" => $_POST["sku"], "country_id" => $_POST["country_id"]));
                    if (!empty($proc)) {
                        $_SESSION["NOTICE"] = "custom_classification_existed";
                    }
                }
                if (empty($_SESSION["NOTICE"])) {
                    set_value($data["pcc"], $_POST);

                    if ($this->sc['customClassModel']->updateProductCustomClass($data["pcc"])) {
                        unset($_SESSION["pcc_obj"]);
                        redirect(base_url() . "mastercfg/custom_class/sku/" . $this->input->post("country_id") . "/?" . $_SERVER['QUERY_STRING']);
                    } else {
                        $_SESSION["NOTICE"] = "submit_error";
                    }
                }
            }
        }

        $this->sku($this->input->post("country_id"), $_POST["sku"]);
    }

    public function sku($country_id = "", $sku = "", $offset = 0)
    {
        $sub_app_id = $this->getAppId() . "00";

        $_SESSION["LISTPAGE"] = base_url() . "mastercfg/custom_class/" . ($country_id == "" ? "" : "sku/" . $country_id) . ($sku == "" ? "" : "/" . $sku) . ($offset ? "/".$offset : "") . "?" . $_SERVER['QUERY_STRING'];

        $where = array();
        $option = array();

        $submit_search = 0;

        if ($country_id != "") {
            $where["pcc.country_id"] = $country_id;
        }
        if ($this->input->get("sku") != "") {
            $where["pcc.sku LIKE "] = "%" . $this->input->get("sku") . "%";
            $submit_search = 1;
        }

        if ($this->input->get("prod_name") != "") {
            $where["p.name LIKE "] = "%" . $this->input->get("prod_name") . "%";
            $submit_search = 1;
        }

        if ($this->input->get("sub_cat_name") != "") {
            $where["sc.name LIKE "] = "%" . $this->input->get("sub_cat_name") . "%";
            $submit_search = 1;
        }

        if ($this->input->get("code") != "") {
            $where["code LIKE "] = "%" . $this->input->get("code") . "%";
            $submit_search = 1;
        }
        if ($this->input->get("description") != "") {
            $where["pcc.description LIKE "] = "%" . $this->input->get("description") . "%";
            $submit_search = 1;
        }
        if ($this->input->get("duty_pcent") != "") {
            fetch_operator($where, "duty_pcent", $this->input->get("duty_pcent"));
            $submit_search = 1;
        }

        $sort = $this->input->get("sort");
        $order = $this->input->get("order");

        $limit = '20';

        $option["limit"] = $limit;
        $option["offset"] = $offset;

        if (empty($sort))
            $sort = "pcc.sku";

        if (empty($order))
            $order = "asc";

        $option["orderby"] = $sort . " " . $order;

        if ($country_id) {
            $data = $this->sc['customClassModel']->getProductCustomClassList($where, $option);
        } else {
            $data["total"] = 0;
        }

        $data["countrylist"] = $this->sc['Country']->getDao('Country')->getList(array("status" => 1), array("limit" => -1, "orderby" => "name"));

        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->getLangId() . ".php");
        $data["lang"] = $lang;

        $config['base_url'] = base_url("mastercfg/custom_class/sku/$country_id/$sku/");
        $config['total_rows'] = $data["total"];
        $config['per_page'] = $limit;

        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();
        $data["notice"] = notice($lang);

        $data["notice"] = notice($lang);

        $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
        $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
        $data["searchdisplay"] = "";


        if (empty($_SESSION["pcc_vo"])) {
            if (($cc_vo = $this->sc['customClassModel']->getProductCustomClass()) === FALSE) {
                $_SESSION["NOTICE"] = "sql_error";
            } else {
                $_SESSION["pcc_vo"] = serialize($pcc_vo);
            }
        }

        if (empty($_SESSION["pcc_obj"][$sku])) {
            if (($data["pcc_obj"] = $this->sc['customClassModel']->getProductCustomClass(array("country_id" => $country_id, "sku" => $sku))) === FALSE) {
                $_SESSION["NOTICE"] = "sql_error";
            } else {
                unset($_SESSION["pcc_obj"]);
                $_SESSION["pcc_obj"][$sku] = serialize($data["pcc_obj"]);
            }
        }

        $data["cmd"] = ($sku == "") ? $this->input->post("cmd") : "edit";
        $data["country_id"] = $country_id;
        $data["sku"] = $sku;
        $data["offset"] = $offset;

        $this->load->view('mastercfg/custom_class/custom_class_sku_v', $data);
    }

    public function edit_sub_cat($sub_cat_id)
    {
        $sub_app_id = $this->getAppId() . "02";

        if ($this->input->post("posted")) {
            unset($_SESSION["NOTICE"]);

            if (isset($_SESSION["ccm_obj"][$sub_cat_id])) {
                $data["ccm"] = unserialize($_SESSION["ccm_obj"][$sub_cat_id]);

                if ($data["ccm"]->getSubCatId() != $_POST["sub_cat_id"]) {
                    $proc = $this->sc['customClassModel']->getCustomClassMapping(array("sub_cat_id" => $_POST["sub_cat_id"], "country_id" => $_POST["country_id"]));
                    if (!empty($proc)) {
                        $_SESSION["NOTICE"] = "custom_classification_existed";
                    }
                }
                if (empty($_SESSION["NOTICE"])) {
                    set_value($data["ccm"], $_POST);

                    $ccm_obj = $this->sc['customClassModel']->getCustomClassMapping(array("sub_cat_id" => $_POST["sub_cat_id"], "country_id" => $_POST["country_id"]));
                    if (empty($ccm_obj)) {
                        $action = "insert";
                    } else {
                        $action = "update";
                    }

                    if ($this->sc['customClassModel']->{$action . "CustomClassMapping"}($data["ccm"])) {
                        unset($_SESSION["pcc_obj"]);
                        redirect(base_url() . "mastercfg/custom_class/sub_cat/" . $this->input->post("country_id") . "/?" . $_SERVER['QUERY_STRING']);
                    } else {
                        $_SESSION["NOTICE"] = "submit_error";
                    }
                }
            }
        }

        $this->sub_cat($this->input->post("country_id"), $_POST["sub_cat_id"]);

    }

    public function sub_cat($country_id = "", $sub_cat_id = "", $offset = 0)
    {
        $sub_app_id = $this->getAppId() . "00";

        $_SESSION["LISTPAGE"] = base_url() . "mastercfg/custom_class/" . ($country_id == "" ? "" : "sub_cat/" . $country_id) . ($sub_cat_id == "" ? "" : "/" . $sub_cat_id) . ($offset ? "/".$offset : "") . "?" . $_SERVER['QUERY_STRING'];

        $where = array();
        $option = array();

        $submit_search = 0;

        if ($country_id != "") {
            $where["ccm.country_id"] = $country_id;
        }

        if ($this->input->get("cat_name")) {
            $where["cat.name LIKE"] = "%" . $this->input->get("cat_name") . "%";
            $submit_search = 1;
        }

        if ($this->input->get("sub_cat_name")) {
            $where["sc.name LIKE"] = "%" . $this->input->get("sub_cat_name") . "%";
            $submit_search = 1;
        }

        if ($this->input->get("code") != "") {
            $condition = "(code LIKE '%" . $this->input->get("code") . "%') OR (description LIKE '%" . $this->input->get("description") . "%')";
            $where[$condition] = "%" . $this->input->get("code") . "%";
            $submit_search = 1;
        }
        if ($this->input->get("description") != "") {
            $where["description LIKE "] = "%" . $this->input->get("description") . "%";
            $submit_search = 1;
        }
        if ($this->input->get("duty_pcent") != "") {
            fetch_operator($where, "duty_pcent", $this->input->get("duty_pcent"));
            $submit_search = 1;
        }

        $sort = $this->input->get("sort");
        $order = $this->input->get("order");

        $limit = '20';

        $option["limit"] = $limit;
        $option["offset"] = $offset;

        if (empty($sort))
            $sort = "sub_cat_id";

        if (empty($order))
            $order = "asc";

        $option["orderby"] = $sort . " " . $order;

        if ($country_id) {
            $data = $this->sc['customClassModel']->getCustomClassMappingList($where, $option);
        } else {
            $data['total'] = 0;
        }

        $data["countrylist"] = $this->sc['customClassModel']->getCountryList(array("status" => 1), array("limit" => -1, "orderby" => "name"));
        $data["subcatlist"] = $this->sc['customClassModel']->getSubCatList(array("level" => 2), array("limit" => -1, "orderby" => "name"));
        $data["custom_class_list"] = $this->sc['customClassModel']->getCustomClassList(array("country_id" => $country_id), array("limit" => -1, "orderby" => "id"));

        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->getLangId() . ".php");
        $data["lang"] = $lang;

        $config['base_url'] = base_url("mastercfg/custom_class/sub_cat/$country_id/$sub_cat_id/");
        $config['total_rows'] = $data["total"];
        $config['per_page'] = $limit;

        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();
        $data["notice"] = notice($lang);

        $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
        $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
        $data["searchdisplay"] = "";


        if (empty($_SESSION["ccm_vo"])) {
            if (($ccm_vo = $this->sc['customClassModel']->getCustomClassMapping()) === FALSE) {
                $_SESSION["NOTICE"] = "sql_error";
            } else {
                $_SESSION["ccm_vo"] = serialize($ccm_vo);
            }
        }

        if (empty($_SESSION["ccm_obj"][$sub_cat_id])) {
            if (($data["ccm_obj"] = $this->sc['customClassModel']->getCustomClassMapping(array("country_id" => $country_id, "sub_cat_id" => $sub_cat_id))) === FALSE) {
                $_SESSION["NOTICE"] = "sql_error";
            } else {
                if (empty($data["ccm_obj"])) {
                    $data["ccm_obj"] = $this->sc['customClassModel']->getCustomClassMapping(array());
                }
                unset($_SESSION["ccm_obj"]);
                $_SESSION["ccm_obj"][$sub_cat_id] = serialize($data["ccm_obj"]);
            }
        }

        $data["cmd"] = ($sub_cat_id == "") ? $this->input->post("cmd") : "edit";
        $data["country_id"] = $country_id;
        $data["sub_cat_id"] = $sub_cat_id;
        $data["offset"] = $offset;

        $this->load->view('mastercfg/custom_class/custom_class_sub_cat_v', $data);
    }
}


