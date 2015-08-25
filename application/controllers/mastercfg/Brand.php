<?php

class Brand extends MY_Controller
{

    private $appId = "MST0006";
    private $lang_id = "en";


    public function __construct()
    {
        parent::__construct();
        $this->load->model('mastercfg/brand_model');
        $this->load->helper('url');
        $this->load->helper('notice');
        $this->load->helper('object');
        $this->load->library('service/pagination_service');
    }

    public function index()
    {
        $sub_app_id = $this->getAppId() . "00";

        $_SESSION["LISTPAGE"] = base_url() . "mastercfg/brand/?" . $_SERVER['QUERY_STRING'];

        $where = array();
        $option = array();

        if ($this->input->get("brand_name") != "") {
            $where["brand_name LIKE "] = "%" . $this->input->get("brand_name") . "%";
        }
        if ($this->input->get("regions") != "") {
            $where["regions"] = "%" . $this->input->get("regions") . "%";
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
            $sort = "brand_name";

        if (empty($order))
            $order = "asc";

        $option["orderby"] = $sort . " " . $order;

        $data = $this->brand_model->get_brand_list($where, $option);

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
        $this->load->view('mastercfg/brand/brand_index_v', $data);
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }

    public function add()
    {

        $sub_app_id = $this->getAppId() . "01";

        if ($this->input->post("posted")) {
            if (isset($_SESSION["brand_vo"])) {
                $this->brand_model->include_brand_vo();
                $data["brand"] = unserialize($_SESSION["brand_vo"]);

                $_POST["status"] = 1;
                set_value($data["brand"], $_POST);

                $proc = $this->brand_model->get_brand(array("brand_name" => $data["brand"]->get_brand_name()));
                if (!empty($proc)) {
                    $_SESSION["NOTICE"] = "brand_existed";
                } else {

                    if ($new_obj = $this->brand_model->add_brand($data["brand"])) {
                        unset($_SESSION["brand_vo"]);
                        $id = $new_obj->get_id();
                        redirect(base_url() . "mastercfg/brand/view/" . $id);
                    } else {
                        $_SESSION["NOTICE"] = $this->db->_error_message();
                    }
                }
            }
        }

        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
        $data["lang"] = $lang;

        if (empty($data["brand"])) {
            if (($data["brand"] = $this->brand_model->get_brand()) === FALSE) {
                $_SESSION["NOTICE"] = $this->db->_error_message();
            } else {
                $_SESSION["brand_vo"] = serialize($data["brand"]);
            }
        }

        $data["notice"] = notice($lang);
        $data["cmd"] = "add";
        $this->load->view('mastercfg/brand/brand_detail_v', $data);
    }

    public function add_region()
    {
        $sub_app_id = $this->getAppId() . "01";
        global $data;
        if ($this->input->post("posted")) {
            if (isset($_SESSION["brand_vo"])) {
                $this->brand_model->include_brand_region_vo();
                $data["br"] = unserialize($_SESSION["br_vo"]);
                set_value($data["br"], $_POST);
                $proc = $this->brand_model->get_brand_region(array("brand_id" => $data["br"]->get_brand_id(), "sales_region_id" => $data["br"]->get_sales_region_id(), "src_region_id" => $data["br"]->get_src_region_id()));
                if (!empty($proc)) {
                    $_SESSION["NOTICE"] = "regions_existed";
                } else {
                    if ($new_obj = $this->brand_model->add_brand_region($data["br"])) {
                        unset($_SESSION["br_vo"]);
                        unset($data["br"]);
                        redirect(base_url() . "mastercfg/brand/view/" . $this->input->post("brand_id"));
                    } else {
                        $_SESSION["NOTICE"] = $this->db->_error_message();
                    }
                }
                $this->view($this->input->post("brand_id"));
            }
        }
    }

    public function view($id = "")
    {
        if ($id) {
            $sub_app_id = $this->getAppId() . "02";
            global $data;

            if ($this->input->post("posted") && $this->input->post("cmd") == "edit") {

                if (isset($_SESSION["brand_vo"])) {
                    $this->brand_model->include_brand_vo();
                    $data["brand"] = unserialize($_SESSION["brand_vo"]);

                    if ($data["brand"]->get_id() != $_POST["id"]) {
                        $proc = $this->brand_model->get_brand(array("id" => $id));
                        if (!empty($proc)) {
                            $_SESSION["NOTICE"] = "brand_existed";
                        }
                    } else {
                        set_value($data["brand"], $_POST);

                        if ($this->brand_model->update_brand($data["brand"])) {
                            unset($_SESSION["brand_vo"]);
                            redirect(base_url() . "mastercfg/brand/view/" . $id);
                        } else {
                            $_SESSION["NOTICE"] = $this->db->_error_message();
                        }
                    }
                }
            }

            include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
            $data["lang"] = $lang;

            if (empty($data["brand"])) {
                if (($data["brand"] = $this->brand_model->get_brand(array("id" => $id))) === FALSE) {
                    $_SESSION["NOTICE"] = $this->db->_error_message();
                } else {
                    $_SESSION["brand_vo"] = serialize($data["brand"]);
                }
            }

            if (empty($data["br"])) {
                if (($data["br"] = $this->brand_model->get_brand_region()) === FALSE) {
                    $_SESSION["NOTICE"] = $this->db->_error_message();
                } else {
                    $_SESSION["br_vo"] = serialize($data["br"]);
                }
            }

            $data["br_list"] = $this->brand_model->get_brand_region_list(array("brand_id" => $id));

            $data["notice"] = notice($lang);
            $data["cmd"] = "edit";
            $this->load->view('mastercfg/brand/brand_detail_v', $data);
        }
    }

    public function del_region()
    {
        if ($this->input->post("posted") && ($brand_id = $this->input->post("brand_id"))) {
            foreach ($_POST["check"] as $cur_brand) {
                if ($this->brand_model->del_brand_region(array("brand_id" => $brand_id, "sales_region_id" => $_POST["del_sales_region_id"][$cur_brand], "src_region_id" => $_POST["del_src_region_id"][$cur_brand])) === FALSE) {
                    $_SESSION["NOTICE"] = $this->db->_error_message();
                }
            }
            redirect(base_url() . "mastercfg/brand/view/" . $brand_id);
        }
    }

    public function js_brandlist()
    {
        header("Content-type: text/javascript; charset: UTF-8");
        header("Cache-Control: must-revalidate");
        $offset = 60 * 60 * 24;
        $ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
        header($ExpStr);
        $objlist = $this->brand_model->get_brand_list(array("status" => 1), array("orderby" => "brand_name ASC", "limit" => -1));
        foreach ($objlist["brandlist"] as $obj) {
            $sid = str_replace("'", "\'", $obj->get_id());
            $name = str_replace("'", "\'", $obj->get_brand_name());
            $slist[] = "'" . $sid . "':'" . $name . "'";
        }
        $js = "brandlist = {" . implode(", ", $slist) . "};";
        $js .= "
            function InitBrand(obj)
            {
                for (var i in brandlist){
                    obj.options[obj.options.length]=new Option(brandlist[i], i);
                }
            }";
        echo $js;
    }
}


