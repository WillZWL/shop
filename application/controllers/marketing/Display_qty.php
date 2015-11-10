<?php

class Display_qty extends MY_Controller
{

    private $appId = "MKT0057";
    private $lang_id = "en";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('marketing/display_qty_model');
        $this->load->helper(array('url', 'notice', 'object'));
    }

    public function index()
    {
        $sub_app_id = $this->getAppId() . "00";
        $data["default_min_display_qty"] = $this->display_qty_service->get_config()->get(array("variable" => "default_min_display_qty"));
        $data["cat_list"] = $this->display_qty_service->get_cat_srv()->get_list_w_key(array("level" => 1, "status" => 1), array("limit" => -1));
        $data["class_list"] = $this->display_qty_service->get_class_list_w_key(array(), array("orderby" => "price", "limit" => -1));
        $data["factor_list"] = $this->display_qty_service->get_factor_list_w_key(array(), array("limit" => -1));

        if ($this->input->post('posted')) {
            if ($this->display_qty_model->update_display_qty($data)) {
                redirect($this->_get_ru());
            }
        }

        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
        $data["lang"] = $lang;
        $data["notice"] = notice($lang);
        $this->load->view('marketing/display_qty/display_qty_index_v', $data);
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }
}



