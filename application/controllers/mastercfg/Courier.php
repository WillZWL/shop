<?php

class Courier extends MY_Controller
{

    private $appId = "MST0007";
    private $lang_id = "en";


    public function __construct()
    {
        parent::__construct();
        $this->load->model('mastercfg/courier_model');
        $this->load->helper('url');
        $this->load->helper('notice');
        $this->load->helper('object');
        $this->load->library('service/pagination_service');
    }

    public function add()
    {

        $sub_app_id = $this->getAppId() . "01";

        if ($this->input->post("posted")) {

            if (isset($_SESSION["courier_vo"])) {
                $this->courier_model->include_courier_vo();
                $data["courier"] = unserialize($_SESSION["courier_vo"]);

                set_value($data["courier"], $_POST);

                $id = $data["courier"]->get_id();
                $proc = $this->courier_model->get_courier(array("id" => $id));
                if (!empty($proc)) {
                    $_SESSION["NOTICE"] = "courier_existed";
                } else {

                    if ($this->courier_model->add_courier($data["courier"])) {
                        unset($_SESSION["courier_vo"]);
                        redirect(base_url() . "mastercfg/courier/?" . $_SERVER['QUERY_STRING']);
                    } else {
                        $_SESSION["NOTICE"] = "submit_error";
                    }
                }
            }
        }

        $this->index();
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function index($courier_id = "")
    {
        $sub_app_id = $this->getAppId() . "00";

        $_SESSION["LISTPAGE"] = base_url() . "mastercfg/courier/?" . $_SERVER['QUERY_STRING'];

        $where = array();
        $option = array();

        if ($this->input->get("id") != "") {
            $where["id LIKE "] = "%" . $this->input->get("id") . "%";
        }
        if ($this->input->get("courier_name") != "") {
            $where["courier_name LIKE "] = "%" . $this->input->get("courier_name") . "%";
        }
        if ($this->input->get("description") != "") {
            $where["description LIKE "] = "%" . $this->input->get("description") . "%";
        }
        if ($this->input->get("type")) {
            $where["type"] = $this->input->get("type");
        }
        if ($this->input->get("currency_id") != "") {
            $where["currency_id"] = $this->input->get("currency_id");
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

        $data = $this->courier_model->get_courier_list($where, $option);

        $data["currencylist"] = $this->courier_model->get_currency_list();

        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
        $data["lang"] = $lang;

        $pconfig['total_rows'] = $data['total'];
        $this->pagination_service->set_show_count_tag(TRUE);
        $this->pagination_service->initialize($pconfig);

        $data["notice"] = notice($lang);

        $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
        $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
//      $data["searchdisplay"] = ($this->input->get("id")=="" && $this->input->get("courier_name")=="" && $this->input->get("description")=="" && $this->input->get("type")=="")?'style="display:none"':"";
        $data["searchdisplay"] = "";


        if (empty($_SESSION["courier_vo"])) {
            if (($courier_vo = $this->courier_model->get_courier()) === FALSE) {
                $_SESSION["NOTICE"] = "sql_error";
            } else {
                $_SESSION["courier_vo"] = serialize($courier_vo);
            }
        }

        if (empty($_SESSION["courier_obj"][$courier_id])) {
            if (($data["courier_obj"] = $this->courier_model->get_courier(array("id" => $courier_id))) === FALSE) {
                $_SESSION["NOTICE"] = "sql_error";
            } else {
                unset($_SESSION["courier_obj"]);
                $_SESSION["courier_obj"][$courier_id] = serialize($data["courier_obj"]);
            }
        }

        $data["cmd"] = ($courier_id == "") ? $this->input->post("cmd") : "edit";
        $data["courier_id"] = $courier_id;

        $this->load->view('mastercfg/courier/courier_index_v', $data);
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }

    public function edit($id)
    {
        $sub_app_id = $this->getAppId() . "02";

        if ($this->input->post("posted")) {
            unset($_SESSION["NOTICE"]);

            if (isset($_SESSION["courier_obj"][$id])) {
                $this->courier_model->include_courier_vo();
                $data["courier"] = unserialize($_SESSION["courier_obj"][$id]);
                if ($data["courier"]->get_id() != $_POST["id"]) {
                    $proc = $this->courier_model->get_courier(array("id" => $_POST["id"]));
                    if (!empty($proc)) {
                        $_SESSION["NOTICE"] = "courier_existed";
                    }
                }

                if (empty($_SESSION["NOTICE"])) {
                    set_value($data["courier"], $_POST);
                    if ($this->courier_model->update_courier($data["courier"])) {
                        unset($_SESSION["courier_obj"]);
                        redirect(base_url() . "mastercfg/courier/?" . $_SERVER['QUERY_STRING']);
                    } else {
                        $_SESSION["NOTICE"] = "submit_error";
                    }
                }
            }
        }

        $this->index($_POST["id"]);
    }

    public function js_courierlist($type = "")
    {
        header("Content-type: text/javascript; charset: UTF-8");
        header("Cache-Control: must-revalidate");
        $offset = 60 * 60 * 24;
        $ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
        header($ExpStr);
        $where = array();
        if ($type) {
            $where["type"] = $type;
        }
        $objlist = $this->courier_model->courier_service->get_dao()->get_list($where, array("order by" => "id asc", "limit" => -1));
        foreach ($objlist as $obj) {
            $sid = str_replace("'", "\'", $obj->get_id());
            $name = str_replace("'", "\'", $obj->get_courier_name());
            $slist[] = "'" . $sid . "':'" . $name . "'";
        }
        $js = "courierlist = {" . implode(", ", $slist) . "};";
        $js .= "
            function InitCourier(obj)
            {
                for (var i in courierlist){
                    obj.options[obj.options.length]=new Option(courierlist[i], i);
                }
            }";
        echo $js;
    }

    public function delete($id = "")
    {

    }
}


