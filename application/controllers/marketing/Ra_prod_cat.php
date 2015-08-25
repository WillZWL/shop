<?php

class Ra_prod_cat extends MY_Controller
{
    private $appId = "MKT0004";
    private $lang_id = "en";

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('input');
        $this->load->model('marketing/ra_prod_cat_model');
    }

    public function index()
    {
        $data = array();
        include APPPATH . "language/" . $this->getAppId() . "01_" . $this->_get_lang_id() . ".php";
        $data["lang"] = $lang;
        $data["sscat_list"] = $this->ra_prod_cat_model->get_scat_list();
        $this->load->view('marketing/ra_prod_cat/ra_index', $data);
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }

    public function view()
    {
        $data = array();
        $data["canedit"] = 1;
        if ($this->input->post('posted') == 1) {
            if ($this->input->post('type') == "insert") {
                $ra_obj = $this->ra_prod_cat_model->get_ra_obj();
                $ra_obj->set_ss_cat_id($this->input->post('sscat'));
            } else {
                $this->ra_prod_cat_model->__autoload();
                $ra_obj = unserialize($_SESSION["ra_obj"]);
            }

            for ($j = 1; $j < 9; $j++) {
                $func = "set_rcm_ss_cat_id_" . $j;
                $ra_obj->$func($this->input->post('sscat' . $j));
            }
            $ra_obj->set_warranty_cat($this->input->post('warranty_cat'));
            $ra_obj->set_status($this->input->post('status'));

            if ($this->input->post('type') == "insert") {
                $ret = $this->ra_prod_cat_model->insert($ra_obj);
                if ($ret === FALSE) {
                    $_SESSION["notice"] = "Update Unsucessful";
                }
            } else {
                $ret = $this->ra_prod_cat_model->update($ra_obj);
                if ($ret === FALSE) {
                    $_SESSION["notice"] = "Update Unsucessful";
                } else {
                    unset($_SESSION["ra_obj"]);
                }
            }
        }

        $ra_obj = $this->ra_prod_cat_model->get_ra_obj($this->input->get('sscat'));

        if (empty($ra_obj)) {
            $ra_obj = $this->ra_prod_cat_model->get_ra_obj();
            $data["type"] = "insert";
        } else {
            $_SESSION["ra_obj"] = serialize($ra_obj);
            $data["type"] = "update";
        }
        $data["ra_obj"] = $ra_obj;
        include APPPATH . "language/" . $this->getAppId() . "02_" . $this->_get_lang_id() . ".php";
        $data["lang"] = $lang;
        $data["sscat_list"] = $this->ra_prod_cat_model->get_scat_list();
        $data["warr_cat_list"] = $this->ra_prod_cat_model->get_warranty_cat_list();
        $this->load->view('marketing/ra_prod_cat/ra_view', $data);
    }

}


