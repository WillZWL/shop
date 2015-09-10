<?php

class Ra_prod_cat extends MY_Controller
{
    private $appId = "MKT0004";
    private $lang_id = "en";

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data = [];
        include APPPATH . "language/" . $this->getAppId() . "01_" . $this->getLangId() . ".php";
        $data["lang"] = $lang;
        $data["sscat_list"] = $this->container['raProdCatModel']->getScatList();
        $this->load->view('marketing/ra_prod_cat/ra_index', $data);
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function view()
    {
        $data = [];
        $data["canedit"] = 1;
        if ($this->input->post('posted') == 1) {
            if ($this->input->post('type') == "insert") {
                $ra_obj = $this->container['raProdCatModel']->getRaObj();
                $ra_obj->set_ss_cat_id($this->input->post('sscat'));
            } else {
                $this->container['raProdCatModel']->autoload();
                $ra_obj = unserialize($_SESSION["ra_obj"]);
            }

            for ($j = 1; $j < 9; $j++) {
                $func = "setRcmSsCatId" . $j;
                $ra_obj->$func($this->input->post('sscat' . $j));
            }
            $ra_obj->set_warranty_cat($this->input->post('warranty_cat'));
            $ra_obj->set_status($this->input->post('status'));

            if ($this->input->post('type') == "insert") {
                $ret = $this->container['raProdCatModel']->insert($ra_obj);
                if ($ret === FALSE) {
                    $_SESSION["notice"] = "Update Unsucessful";
                }
            } else {
                $ret = $this->container['raProdCatModel']->update($ra_obj);
                if ($ret === FALSE) {
                    $_SESSION["notice"] = "Update Unsucessful";
                } else {
                    unset($_SESSION["ra_obj"]);
                }
            }
        }

        $ra_obj = $this->container['raProdCatModel']->getRaObj($this->input->get('sscat'));

        if (empty($ra_obj)) {
            $ra_obj = $this->container['raProdCatModel']->getRaObj();
            $data["type"] = "insert";
        } else {
            $_SESSION["ra_obj"] = serialize($ra_obj);
            $data["type"] = "update";
        }
        $data["ra_obj"] = $ra_obj;
        include APPPATH . "language/" . $this->getAppId() . "02_" . $this->getLangId() . ".php";
        $data["lang"] = $lang;
        $data["sscat_list"] = $this->container['raProdCatModel']->getScatList();
        $data["warr_cat_list"] = $this->container['raProdCatModel']->getWarrantyCatList();
        $this->load->view('marketing/ra_prod_cat/ra_view', $data);
    }

}


