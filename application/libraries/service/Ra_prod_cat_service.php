<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Ra_prod_cat_service extends Base_service
{

    private $cat_srv;

    public function __construct()
    {
        parent::__construct();
        $CI =& get_instance();
        $this->load = $CI->load;
        include_once(APPPATH . "libraries/dao/Ra_prod_cat_dao.php");
        $this->set_dao(new Ra_prod_cat_dao());
        include_once(APPPATH . "libraries/service/Category_service.php");
        $this->set_cat_srv(new Category_service());
    }

    public function get_ra_prod_cat_w_ext_name_list($cat_id, $lang_id = "en")
    {
        $data = array();
        if ($ra_prod_cat = $this->get(array("ss_cat_id" => $cat_id))) {
            for ($i = 1; $i < 9; $i++) {
                $getter = "get_rcm_ss_cat_id_" . $i;
                if ($cur_ra_cat_id = $ra_prod_cat->$getter()) {
                    if ($cat_ext_obj = $this->get_cat_srv()->get_cat_ext_default_w_key_list(array("c.id" => $cur_ra_cat_id, "l.id" => $lang_id), array("limit" => 1))) {
                        $data[] = $cat_ext_obj;
                    }
                }
            }
        }
        return $data;
    }

    public function get_cat_srv()
    {
        return $this->cat_srv;
    }

    public function set_cat_srv($service)
    {
        $this->cat_srv = $service;
    }

}

/* End of file ra_prod_cat_service.php */
/* Location: ./system/application/libraries/service/Ra_prod_cat_service.php */