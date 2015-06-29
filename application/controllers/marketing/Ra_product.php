<?php

class Ra_product extends MY_Controller
{
    private $app_id = "MKT0005";
    private $lang_id = "en";

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url', 'object'));
        $this->load->library('input');
        $this->load->model('marketing/ra_product_model');
        $this->load->model('marketing/upselling_model');
    }

    public function get_ra_product($sku = '')
    {
        if ($sku == '') {
            show_404();
        }

        include_once APPPATH . 'language/' . $this->_get_app_id() . '01_' . $this->_get_lang_id() . '.php';

        $data = array();
        $data['sku'] = $sku;
        $data['lang'] = $lang;
        $data['ra_group_list'] = $this->upselling_model->get_ra_group_list(array('status' => 1), array('orderby' => 'group_name asc', 'limit' => -1));

        if ($ra_product_obj = $this->ra_product_model->get_ra_product_obj($sku)) {
            $data['ra_product_obj'] = $this->ra_product_model->get_ra_product_obj($sku);
        } else {
            $data['ra_product_obj'] = $this->ra_product_model->get_ra_product_obj();
        }

        $this->load->view('marketing/ra_product/ra_product_list', $data);
    }

    public function _get_app_id()
    {
        return $this->app_id;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }

    public function update()
    {
        $data = array();
        if ($this->input->post('posted')) {
            $sku = $this->input->post('sku');
            $this->ra_product_model->__autoload();

            $got_ra_prod = FALSE;
            if ($ra_prod_vo = $this->ra_product_model->get_ra_product_obj($sku)) {
                $ra_prod_func = 'update';
            } else {
                $ra_prod_vo = $this->ra_product_model->get_ra_product_obj();
                $ra_prod_vo->set_sku($sku);
                $ra_prod_func = 'insert';
            }

            for ($i = 1; $i < 21; $i++) {
                if ($_POST['rprod' . $i] != '') {
                    $got_ra_prod = TRUE;
                }

                $func = 'set_rcm_group_id_' . $i;
                $ra_prod_vo->$func($_POST['rprod' . $i]);
            }

            if (!$this->ra_product_model->$ra_prod_func($ra_prod_vo)) {
                $_SESSION["notice"] = "Update Failed";
            } else {
                $changed = FALSE;
                $prod_obj = $this->ra_product_model->get_product(array("sku" => $sku));
                $proc_status = $prod_obj->get_proc_status();
                if ($got_ra_prod) {
                    if ($proc_status == 0) {
                        $prod_obj->set_proc_status("1");
                        $changed = TRUE;
                    }
                    if ($proc_status == 2) {
                        $prod_obj->set_proc_status("4");
                        $changed = TRUE;
                    }
                } else {
                    if ($proc_status == 1) {
                        $prod_obj->set_proc_status("0");
                        $changed = TRUE;
                    }
                    if ($proc_status == 4) {
                        $prod_obj->set_proc_status("2");
                        $changed = TRUE;
                    }
                }

                if ($changed) {
                    // Need to enable when #2259 go live
                    //$this->ra_product_model->update_product($prod_obj);
                }

                redirect(base_url() . 'marketing/ra_product/get_ra_product/' . $sku);
            }
        }
    }

}


