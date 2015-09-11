<?php

class Footer extends MY_Controller
{
    private $appId = "MKT0050";
    private $lang_id = "en";

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url', 'directory', 'notice'));
        $this->load->model('marketing/menu_model');
        $this->load->model('website/home_model');
        $this->load->library('service/authorization_service');
        $this->load->library('service/context_config_service');
    }

    public function index($menu_item_id = '')
    {
        include_once APPPATH . "language/" . $this->getAppId() . "00_" . $this->_get_lang_id() . ".php";
        $data["lang"] = $lang;
        $data["notice"] = notice($lang);

        $menu = $this->menu_model->get_footer_menu_list("en", array("menu_type" => "F"), array("orderby" => "status DESC, priority ASC"));
        $data["menu_list"] = $menu["menu_list"];
        $data["menu_item_list"] = $menu["menu_item_list"];
        $data["menu_item_id"] = $menu_item_id;

        $data["cmd"] = ($menu_item_id == "") ? $this->input->post("cmd") : "edit";
        $this->load->view('marketing/footer/footer_menu_view.php', $data);
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
            unset($_SESSION["NOTICE"]);

            $code = str_replace(" ", "_", trim($this->input->post('item_code')));
            $menu_item_id = $this->input->post('menu_group') . "." . $code;

            if (!$this->menu_model->get(array("menu_item_id" => $menu_item_id))) {
                $parent_obj = $this->menu_model->get(array("menu_type" => "F", "menu_item_id" => $this->input->post("menu_group"), "status" => 1));
                $menu_obj = $this->menu_model->get();
                $menu_obj->set_menu_type('F');
                $menu_obj->set_parent_id($parent_obj->get_menu_id());
                $menu_obj->set_level(1);
                $menu_obj->set_menu_item_id($menu_item_id);
                $menu_obj->set_code($code);
                $menu_obj->set_name($this->input->post('menu_row_name'));
                $menu_obj->set_link_type('I');
                $menu_obj->set_link($this->input->post('hyperlink'));
                $menu_obj->set_priority($this->input->post('priority'));
                $menu_obj->set_status($this->input->post('status'));

                if (!$this->menu_model->add_menu_item($menu_obj)) {
                    $_SESSION["NOTICE"] = $this->db->_error_message();
                }

                if (!$_SESSION["NOTICE"]) {
                    $func_opt = $this->menu_model->get_func_option();
                    $func_opt->set_func_id($menu_item_id);
                    $func_opt->set_lang_id('en');
                    $func_opt->set_text($this->input->post('menu_row_name'));
                    if (!$this->menu_model->insert_func_opt($func_opt)) {
                        $_SESSION["NOTICE"] = "ERROR: " . str_replace(APPPATH, "", __FILE__) . "@" . __LINE__ . " " . $this->db->_error_message();
                    }
                }
            } else {
                $_SESSION["NOTICE"] = "same_id_error";
            }

            redirect(base_url() . "marketing/footer/");

        } elseif ($cmd == "update") {
            unset($_SESSION["NOTICE"]);
            $menu_item_id = $this->input->post("menu_item_id");

            $menu_obj = $this->menu_model->get(array("menu_type" => "F", "menu_item_id" => $menu_item_id));
            if ($menu_obj) {
                $menu_obj->set_name($this->input->post("name"));
                $menu_obj->set_link($this->input->post("hyperlink"));
                $menu_obj->set_priority($this->input->post("priority"));
                $menu_obj->set_status($this->input->post("status"));
                if (!$this->menu_model->update_menu_item($menu_obj)) {
                    $_SESSION["NOTICE"] = $this->db->_error_message();
                }
                redirect(base_url() . "marketing/footer/");
            } else {
                $_SESSION["NOTICE"] = "Menu Item Does Not Exist.";
            }

            redirect(base_url() . "marketing/footer/");
        } elseif ($cmd == "generate") {
            $this->home_model->gen_footer_cat_menu();
            redirect(base_url() . "marketing/footer/");
        }
    }

    public function language()
    {
        if ($this->input->post('posted')) {
            $vo["func_opt"] = $this->menu_model->get_func_option();
            $data["func_opt_list"] = unserialize($_SESSION["func_opt_list"]);

            $this->menu_model->check_serialize('func_opt_list', $data);
            if ($this->menu_model->update_content($vo, $data)) {
                unset($_SESSION["func_opt_list"]);
//              redirect($this->_get_ru());
            }
            if (!$_SESSION["NOTICE"]) {
                $this->home_model->gen_footer_cat_menu();
            }
        }

        $menu = $this->menu_model->get_footer_menu_list("en", array("menu_type" => "F", "m.status" => "1"), array("orderby" => "priority ASC"));

        $data["menu_list"] = $menu["menu_list"];
        $data["menu_item_list"] = $menu["menu_item_list"];

        $where["(func_id LIKE \"footer.%\")"] = NULL;
        $this->menu_model->check_serialize('func_opt_list', $data, $where);

        include_once APPPATH . "language/" . $this->getAppId() . "01_" . $this->_get_lang_id() . ".php";

        $data["lang_list"] = $this->menu_model->get_lang_list(array("status" => 1), array("orderby" => "id='en' DESC", "limit" => -1));
        $data["lang"] = $lang;
        $data["notice"] = notice($lang);

        $this->load->view('marketing/footer/footer_menu_lang.php', $data);
    }
}

