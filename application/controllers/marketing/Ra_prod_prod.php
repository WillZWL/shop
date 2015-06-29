<?php

class Ra_prod_prod extends MY_Controller
{
    private $app_id = "MKT0005";
    private $lang_id = "en";

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url', 'object'));
        $this->load->library('input');
        $this->load->model('marketing/ra_prod_prod_model');
    }

    public function get_ra_product()
    {
        $data = array();
        $data["canedit"] = 1;
        $prodid = $this->input->get('sku');
        $sscat = $this->input->get('sscat');
        $ra_obj = $this->ra_prod_prod_model->get_ra_obj($sscat);
        if (empty($ra_obj)) {
            $data["message"] = "Setting RA items for this product is prohibited";

        } else {
            $data["ra_obj"] = $ra_obj;
            if ($ra_obj->get_status() == 0) {
                $data["message"] = "Setting RA items for this product is prohibited";
            } else {
                $scat_arr = array();
                for ($i = 1; $i < 9; $i++) {
                    $func = "get_rcm_ss_cat_id_" . $i;
                    $ra_sscat = $ra_obj->$func();
                    if ($ra_sscat <> "" && $scat_arr[$ra_sscat] == "") {
                        $scat_arr[$ra_sscat] = $this->ra_prod_prod_model->get_scat_prod($ra_sscat);
                    }
                }
                $data["scat_arr"] = $scat_arr;
                $data["ra_prods"] = $this->ra_prod_prod_service->get_ra_prods_w_sku_key($this->input->get('sku'));
            }
        }
        include_once APPPATH . "language/" . $this->_get_app_id() . "01_" . $this->_get_lang_id() . ".php";
        $data["lang"] = $lang;
        $this->load->view('marketing/ra_prod_prod/ra_product_list', $data);
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
            $this->ra_prod_prod_model->__autoload();
            $rpp_obj = unserialize($_SESSION["ra_prod_obj"]);

            $success = $got_ra_prod = 0;
            $this->ra_prod_prod_service->get_dao()->q_delete(array("sku" => $sku));
            $ra_prod_vo = $this->ra_prod_prod_model->get_raprod_prod_obj();
            $ra_prod_vo->set_sku($sku);
            for ($j = 1; $j < 9; $j++) {
                if ($_POST["rprod" . $j]) {
                    foreach ($_POST["rprod" . $j] as $rprod_sku) {
                        $ra_prod_vo->set_rcm_prod_id_1($rprod_sku);
                        if ($ret = $this->ra_prod_prod_model->insert($ra_prod_vo)) {
                            $success = 1;
                            $got_ra_prod = 1;
                        } else {
                            $_SESSION["notice"] = "Update Failed";
                        }
                    }
                }
            }

            if ($success) {
                $changed = 0;
                $prod_obj = $this->ra_prod_prod_model->get_product(array("sku" => $sku));
                $proc_status = $prod_obj->get_proc_status();
                if ($got_ra_prod) {
                    if ($proc_status == 0) {
                        $prod_obj->set_proc_status("1");
                        $changed = 1;
                    }
                    if ($proc_status == 2) {
                        $prod_obj->set_proc_status("4");
                        $changed = 1;
                    }
                } else {
                    if ($proc_status == 1) {
                        $prod_obj->set_proc_status("0");
                        $changed = 1;
                    }
                    if ($proc_status == 4) {
                        $prod_obj->set_proc_status("2");
                        $changed = 1;
                    }
                }
                if ($changed) {
                    $this->ra_prod_prod_model->update_product($prod_obj);
                }
                redirect(base_url() . "marketing/ra_prod_prod/get_ra_product/?sku=" . $sku . "&sscat=" . $this->input->post('sscat'));
            }
        }
    }

}


