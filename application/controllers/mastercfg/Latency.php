<?php

class Latency extends MY_Controller
{
    private $appId = "MST0005";
    private $lang_id = "en";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('mastercfg/latency_model');
        $this->load->library('input');
        $this->load->helper('url');
        $this->selling_platform_list = $this->latency_model->get_selling_platform_list();
    }

    public function index()
    {
        $data = array();
        include_once APPPATH . '/language/' . $this->getAppId() . '00_' . $this->_get_lang_id() . '.php';
        $data["lang"] = $lang;
        $data["selling_platform_list"] = $this->selling_platform_list;
        $this->load->view("mastercfg/latency/latency_index", $data);
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }

    public function view($value = "")
    {
        $data = array();
        $data["updated"] = 0;
        $data["editable"] = 1;
        if (isset($_POST["submit"])) {
            $this->latency_model->__autoload();
            $obj = unserialize($_SESSION["profit_obj"]);
            //$obj = $this->profit_var_model->get_platform_biz_var($value);
            $obj->set_selling_platform_id($this->input->post('id'));
            $obj->set_latency_in_stock($_POST["in_stock"]);
            $obj->set_latency_out_of_stock($_POST["out_of_stock"]);


            if ($this->input->post("type") == "update") {
                $ret = $this->latency_model->update($obj);
            } else {
                $ret = $this->latency_model->add($obj);
            }
            if ($ret === FALSE) {
                $_SESSION["notice"] = "update_failed";
            } else {
                unset($_SESSION["notice"]);
                $data["updated"] = 1;
            }
        }
        //determine whether user has the rights to edit
        $canedit = 0;
        if ($canedit) {
            $data["editable"] = 1;
        }
        //end determination

        $platform = $this->latency_model->check_platform($value);
        if (empty($platform)) {
            unset($data);
            $_SESSION["notice"] = "invalid_information";
            Redirect(base_url() . "mastercfg/latency/index/");
        } else {
            $data["action"] = "update";
            $platform_bizvar_obj = $this->latency_model->get_platform_biz_var($value);
            if (empty($platform_bizvar_obj)) {
                $platform_bizvar_obj = $this->latency_model->get_platform_biz_var();
                $data["action"] = "add";
            }
        }
        $data["profit_obj"] = $platform_bizvar_obj;
        include_once APPPATH . '/language/' . $this->getAppId() . '02_' . $this->_get_lang_id() . '.php';
        $data["lang"] = $lang;
        $data["selling_platform_list"] = $this->selling_platform_list;
        $_SESSION["profit_obj"] = serialize($data["profit_obj"]);
        $data["id"] = $value;
        $data["header"] = "";
        $data["title"] = "";
        $this->load->view("mastercfg/latency/latency_view", $data);
    }

}

?>