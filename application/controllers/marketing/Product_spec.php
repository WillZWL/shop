<?php

class Product_spec extends MY_Controller
{
    private $appId = "MKT0049";
    private $lang_id = "en";

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url', 'directory', 'notice'));
        $this->load->model('marketing/product_spec_model');
        $this->load->library('service/authorization_service');
        $this->load->library('service/context_config_service');
    }

    public function index($ps_id = '')
    {
        include_once APPPATH . "language/" . $this->getAppId() . "00_" . $this->_get_lang_id() . ".php";
        $data["lang"] = $lang;
        $data["notice"] = notice($lang);
        $data["psg_list"] = $this->product_spec_model->get_prod_spec_group_list(array("status" => 1), array("orderby" => "priority DESC"));
        foreach ($data["psg_list"] AS $psg_obj) {
            $ps_list = $this->product_spec_model->get_prod_spec_list(array("psg_id" => $psg_obj->get_id()), array());
            $no_of_row_psl = $this->product_spec_model->get_no_of_row_psl(array("psg_id" => $psg_obj->get_id()));
            if ($no_of_row_psl) {
                $data["prod_spec_list"][$psg_obj->get_id()] = $this->product_spec_model->get_prod_spec_list(array("psg_id" => $psg_obj->get_id()), array("orderby" => "status DESC, psg_id"));
            }
        }
        $unit_type_list = $this->product_spec_model->get_unit_type_list(array("status" => 1), array("orderby" => "name ASC"));
        foreach ($unit_type_list AS $unit_type_obj) {
            $data["unit_type_array"][$unit_type_obj->get_id()] = $unit_type_obj->get_name();
        }

        $data["ps_id"] = $ps_id;

        $data["cmd"] = ($ps_id == "") ? $this->input->post("cmd") : "edit";
        $this->load->view('marketing/product_spec/prod_spec_view.php', $data);
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }

    public function edit()
    {
        $cmd = $this->input->post("cmd");
        if ($cmd == "add") {
            //replace space with underscore, change to lowercase
            $code = str_replace(" ", "_", strtolower($this->input->post("code")));

            unset($_SESSION["NOTICE"]);

            $psg_obj = $this->product_spec_model->get_prod_spec_group(array("id" => $this->input->post("psg_id")));
            $ps_id = "ps." . $psg_obj->get_code() . "." . $code;

            if (!$this->product_spec_model->get_prod_spec(array("id" => $ps_id))) {
                $ps_obj = $this->product_spec_model->get_prod_spec();
                $ps_obj->set_id($ps_id);
                $ps_obj->set_code($code);
                $ps_obj->set_psg_id($this->input->post('psg_id'));
                $ps_obj->set_name($this->input->post('name'));
                $ps_obj->set_unit_type_id($this->input->post('unit_type_id'));
                $ps_obj->set_status($this->input->post('status'));
                if (!$this->product_spec_model->add_prod_spec($ps_obj)) {
                    $_SESSION["NOTICE"] = $this->db->_error_message();
                }

                if (!$_SESSION["NOTICE"]) {
                    $func_opt = $this->product_spec_model->get_func_option();
                    $func_opt->set_func_id($ps_id);
                    $func_opt->set_lang_id('en');
                    $func_opt->set_text($this->input->post('name'));
                    if (!$this->product_spec_model->insert_func_opt($func_opt)) {
                        $_SESSION["NOTICE"] = "ERROR: " . str_replace(APPPATH, "", __FILE__) . "@" . __LINE__ . " " . $this->db->_error_message();
                    }
                }
            } else {
                $_SESSION["NOTICE"] = "same_id_error";
            }
            redirect(base_url() . "marketing/product_spec/");
        } elseif ($cmd == "update") {
            unset($_SESSION["NOTICE"]);
            $ps_id = $this->input->post("ps_id");
            $ps_obj = $this->product_spec_model->get_prod_spec(array("id" => $ps_id));
            if ($ps_obj) {

                $ps_obj->set_name($this->input->post("name"));
                $ps_obj->set_status($this->input->post("status"));
                if (!$this->product_spec_model->update_prod_spec($ps_obj)) {
                    $_SESSION["NOTICE"] = $this->db->_error_message();
                }
                redirect(base_url() . "marketing/product_spec/");
            } else {
                $_SESSION["NOTICE"] = "Product Specification Not Exist.";
            }
        }
    }

    public function language()
    {
        if ($this->input->post('posted')) {
            $vo["func_opt"] = $this->product_spec_model->get_func_option();
            $data["func_opt_list"] = unserialize($_SESSION["func_opt_list"]);
            $this->product_spec_model->check_serialize('func_opt_list', $data);
            if ($this->product_spec_model->update_content($vo, $data)) {
                unset($_SESSION["func_opt_list"]);
//              redirect($this->_get_ru());
            }
        }
        $data["psg_list"] = $this->product_spec_model->get_prod_spec_group_list(array("status" => 1), array("orderby" => "priority DESC"));
        foreach ($data["psg_list"] AS $psg_obj) {
            $ps_list = $this->product_spec_model->get_prod_spec_list(array("psg_id" => $psg_obj->get_id()), array());
            $no_of_row_psl = $this->product_spec_model->get_no_of_row_psl(array("psg_id" => $psg_obj->get_id()));
            if ($no_of_row_psl) {
                $data["prod_spec_list"][$psg_obj->get_id()] = $this->product_spec_model->get_prod_spec_list(array("psg_id" => $psg_obj->get_id()), array("orderby" => "status DESC, psg_id"));
            }
        }
        $where["(func_id LIKE \"psg.%\" OR func_id LIKE \"ps.%\")"] = NULL;
        $this->product_spec_model->check_serialize('func_opt_list', $data, $where);

        include_once APPPATH . "language/" . $this->getAppId() . "01_" . $this->_get_lang_id() . ".php";

        $data["lang_list"] = $this->product_spec_model->get_lang_list(array("status" => 1), array("limit" => -1));
        $data["lang"] = $lang;
        $data["notice"] = notice($lang);
        $this->load->view('marketing/product_spec/prod_spec_lang.php', $data);
    }
}

