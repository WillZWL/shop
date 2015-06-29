<?php

class Custom_class_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/region_service');
        $this->load->library('service/country_service');
        $this->load->library('service/category_service');
        $this->load->library('service/custom_class_service');
        $this->load->library('service/custom_classification_mapping_service');
    }

    public function get_country_list($where = array(), $option = array())
    {
        return $this->country_service->get_list($where, $option);
    }

    public function get_sub_cat_list($where = array(), $option = array())
    {
        return $this->category_service->get_list($where, $option);
    }

    public function get_custom_class_list($where = array(), $option = array())
    {
        return $this->custom_class_service->get_list($where, $option);
    }

    public function get_region_list($where = array(), $option = array())
    {
        return $this->region_service->get_list($where, $option);
    }

    public function get_cc_list($where = array(), $option = array())
    {
        $data["cclist"] = $this->custom_class_service->get_list($where, $option);
        $data["total"] = $this->custom_class_service->get_num_rows($where);
        return $data;
    }

    public function get_cc($where = array())
    {
        return $this->custom_class_service->get($where);
    }

    public function get_cc_option($where = array())
    {
        return $this->custom_class_service->get_option($where);
    }

    public function update_cc($obj)
    {
        return $this->custom_class_service->update($obj);
    }

    public function include_cc_vo()
    {
        return $this->custom_class_service->include_vo();
    }

    public function add_cc(Base_vo $obj)
    {
        return $this->custom_class_service->insert($obj);
    }

    public function get_pcc($where = array())
    {
        return $this->custom_class_service->get_pcc($where);
    }

    public function get_pcc_list($where = array(), $option = array())
    {
        return $this->custom_class_service->get_pcc_list($where, $option);
    }

    public function get_full_pcc_by_sku($where = array(), $option = array())
    {
        return $this->custom_class_service->get_full_pcc_by_sku($where, $option);
    }

    public function update_pcc($obj)
    {
        return $this->custom_class_service->update_pcc($obj);
    }

    public function include_pcc_vo()
    {
        return $this->custom_class_service->include_pcc_vo();
    }

    public function add_pcc(Base_vo $obj)
    {
        return $this->custom_class_service->add_pcc($obj);
    }

    public function get_ccm_list($where = array(), $option = array())
    {
        return $this->custom_classification_mapping_service->get_ccm_list($where, $option);
    }

    public function get_ccm($where = array())
    {
        return $this->custom_classification_mapping_service->get_ccm($where);
    }

    public function insert_ccm($obj)
    {
        return $this->custom_classification_mapping_service->insert_ccm($obj);
    }

    public function update_ccm($obj)
    {
        return $this->custom_classification_mapping_service->update_ccm($obj);
    }

    public function include_ccm_vo()
    {
        return $this->custom_classification_mapping_service->include_vo();
    }

    public function add_ccm(Base_vo $obj)
    {
        return $this->custom_classification_mapping_service->add_ccm($obj);
    }

    public function get_cc_by_cat_sub_id($sub_cat_id)
    {
        return $this->custom_classification_mapping_service->get_all_custom_class_mapping_by_sub_cat_id($sub_cat_id);
    }

    // public function get_cc_for_main_cat_id($main_cat_id)
    // {
    //  return $this->custom_classification_mapping_service->get_all_custom_class_mapping_by_main_cat_id($main_cat_id);
    // }
}

/* End of file custom_class_model.php */
/* Location: ./system/application/models/custom_class_model.php */
?>