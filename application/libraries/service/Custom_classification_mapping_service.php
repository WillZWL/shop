<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Custom_classification_mapping_service extends Base_service
{

    function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/dao/Custom_classification_mapping_dao.php");
        $this->set_dao(new Custom_classification_mapping_dao());
        include_once(APPPATH . "libraries/dao/Country_dao.php");
        $this->set_country_dao(new Country_dao());
    }

    public function set_country_dao(Base_dao $dao)
    {
        $this->country_dao = $dao;
    }

    public function get_ccm($where = array())
    {
        return $this->get($where);
    }

    public function insert_ccm($obj)
    {
        return $this->insert($obj);
    }

    public function update_ccm($obj)
    {
        return $this->update($obj);
    }

    public function include_ccm_vo()
    {
        return $this->include_vo();
    }

    public function add_ccm(Base_vo $obj)
    {
        return $this->insert($obj);
    }

    public function get_ccm_list($where = array(), $option = array())
    {
        if ($objlist = $this->get_dao()->get_ccm_list($where, $option)) {
            foreach ($objlist as $obj) {
                $data["ccmlist"][$obj->get_sub_cat_id()] = $obj;
            }
        }
        $option["num_rows"] = 1;
        $data["total"] = $this->get_dao()->get_ccm_list($where, $option);
        return $data;
    }

    public function get_custom_class_mapping_by_sub_cat_id($sub_cat_id = "")
    {
        if ($country_list = $this->get_country_dao()->get_list(array("allow_sell" => 1), array("limit" => -1))) {
            foreach ($country_list as $country_obj) {
                $ccm_obj = $this->get_dao()->get_ccm_list(array("sub_cat_id" => $sub_cat_id, "ccm.country_id" => $country_obj->get_id()), array("limit" => 1));
                $rs[$country_obj->get_id()] = $ccm_obj;
            }
            return $rs;
        }
        return false;
    }

    public function get_country_dao()
    {
        return $this->country_dao;
    }

    public function get_all_custom_class_mapping_by_sub_cat_id($sub_cat_id = "")
    {
        return $this->get_dao()->get_all_ccm_list($sub_cat_id, $option = '');
    }

    // public function get_all_custom_class_mapping_by_main_cat_id($main_cat_id = "")
    // {
    //  return  $this->get_dao()->get_main_cc_list($sub_cat_id, $option= '');
    // }
}

/* End of file custom_classification_mapping_service.php */
/* Location: ./system/application/libraries/service/Custom_classification_mapping_service.php */