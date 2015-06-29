<?php

class Category_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/category_service');
        $this->load->library('service/website_service');
        $this->load->library('service/sub_cat_platform_var_service');
        $this->load->library('service/platform_biz_var_service');
        $this->load->library('service/selling_platform_service');
        $this->load->library('service/currency_service');
        $this->load->library('service/custom_class_service');
        $this->load->library('service/product_service');
        $this->load->library('service/language_service');
        $this->load->library('service/product_spec_service');
        $this->load->library('service/unit_service');
    }

    public function get_cat_obj($id="")
    {
        return $this->category_service->get($id);
    }

    public function getlistcount($level, $id="")
    {
        return $this->category_service->get_list_with_child_count($level,$id);
    }

    public function get_list($where=array())
    {
        return $this->category_service->get_list($where);
    }

    public function get_parent($level, $id)
    {
        return $this->category_service->get_parent($level, $id);
    }

    public function update_category($obj)
    {
        return $this->category_service->update($obj);
    }

    public function add_category($obj)
    {
        return $this->category_service->add($obj);
    }

    public function __autoload()
    {
        $this->category_service->load_vo();
    }

    public function __autoload_scpv()
    {
        $this->sub_cat_platform_var_service->load_vo();
    }

    public function get_product_by_sscat($id)
    {
        return $this->product_service->get_dao()->get_list(array("sub_sub_cat_id"=>$id));
    }

    public function count_product($id)
    {
        return $this->product_service->get_dao()->get_num_rows(array("sub_sub_cat_id"=>$id));
    }

    public function get_cat_list_index($where,$option)
    {
        return $this->category_service->get_cat_list_index($where,$option);
    }

    public function get_scpv_obj($where=array())
    {
        return $this->sub_cat_platform_var_service->get_dao()->get($where);
    }

    public function get_replace_scpv_obj($where=array())
    {
        return $this->platform_biz_var_service->get_dao()->get($where);
    }

    public function get_scpv_obj_new()
    {
        return $this->sub_cat_platform_var_service->get_dao()->get();
    }

    public function insert_scpv($obj)
    {
        return $this->sub_cat_platform_var_service->get_dao()->insert($obj);
    }

    public function update_scpv($obj)
    {
        return $this->sub_cat_platform_var_service->get_dao()->update($obj);
    }

    public function get_selling_platform($where=array(), $option=array())
    {
        return $this->selling_platform_service->get_list($where, $option);
    }

    public function get_currency_list()
    {
        return $this->currency_service->get_dao()->get_list();
    }

    public function get_custom_class_list()
    {
        return $this->custom_class_service->get_dao()->get_list();
    }

    public function get_custom_class_list_w_platform_id($platform_id = "WEBHK")
    {
        return $this->custom_class_service->get_dao()->get_custom_class_list_w_platform_id($platform_id);
    }

    public function get_website_cat_page_product_list($where = array(), $option = array())
    {
        return $this->product_service->get_website_cat_page_product_list($where, $option);
    }

    public function get_cat_filter_grid_info($level, $where = array(), $option = array())
    {
        return $this->category_service->get_cat_filter_grid_info($level, $where, $option);
    }

    public function get_brand_filter_grid_info($where = array(), $option = array())
    {
        return $this->category_service->get_brand_filter_grid_info($where, $option);
    }

    public function get_display_list($catid, $type="cat",$brand="",$platform_id="",$min_price="",$max_price="")
    {
        return $this->category_service->get_display_list($catid,$type,$brand,$platform_id,$min_price,$max_price);
    }

    public function get_video_display_list($catid, $type="cat",$brand="",$platform_id="",$min_price="",$max_price="")
    {
        return $this->category_service->get_video_display_list($catid,$type,$brand,$platform_id,$min_price,$max_price);
    }

    public function get_display_catlist($catid)
    {
        return $this->category_service->get_display_catlist($catid);
    }

    public function getlistcnt($level,$id,$status)
    {
        return $this->category_service->get_dao()->get_child_w_count($level,$id,$status);
    }

    public function get_colour_code()
    {
        return $this->category_service->get_colour_list();
    }

    public function get_listed_cat($platform_id = "")
    {
        return $this->category_service->get_listed_cat($platform_id);
    }

    public function get_full_cat_list()
    {
        return $this->category_service->get_full_cat_list();
    }

    public function get_listed_cat_tree($platform_id = "WEBHK")
    {
        return $this->category_service->get_listed_cat_tree($platform_id);
    }

    public function get_category($where=array())
    {
        return $this->category_service->get_category($where);
    }

    public function get_prod_spec_group_list($where = array(), $option = array())
    {
        return $this->product_spec_service->get_prod_spec_group_list($where, $option);
    }

    public function get_unit_list($where = array(), $option = array())
    {
        return $this->unit_service->get_unit_list($where, $option);
    }

    public function get_unit_type_list($where = array(), $option = array())
    {
        return $this->unit_service->get_unit_type_list($where, $option);
    }

    public function get_prod_spec($where = array())
    {
        return $this->product_spec_service->get_prod_spec($where);
    }

    public function get_prod_spec_list($where = array(), $option = array())
    {
        return $this->product_spec_service->get_prod_spec_list($where, $option);
    }

    public function get_no_of_row_psl($where = array())
    {
        return $this->product_spec_service->get_no_of_row_psl($where);
    }

    public function add_prod_spec($prod_spec_obj)
    {
        return $this->product_spec_service->add_prod_spec($prod_spec_obj);
    }

    public function update_prod_spec($prod_spec_obj)
    {
        return $this->product_spec_service->update_prod_spec($prod_spec_obj);
    }

    public function get_cat_ext_obj($where=array())
    {
        return $this->category_service->get_cat_ext_obj($where);
    }

    public function get_cat_ext_list($where=array(), $option=array())
    {
        return $this->category_service->get_cat_ext_list($where, $option);
    }

    public function get_cat_cont_obj($where=array())
    {
        return $this->category_service->get_cat_cont_obj($where);
    }

    public function get_cat_cont_list($where=array(), $option=array())
    {
        return $this->category_service->get_cat_cont_list($where, $option);
    }

    public function get_cat_prod_spec_list($where=array(), $option=array())
    {
        return $this->product_spec_service->get_cat_prod_spec_list($where, $option);
    }

    public function get_full_cps_list($cat_id)
    {
        return $this->product_spec_service->get_full_cps_list($cat_id);
    }

    public function get_cps($where=array())
    {
        return $this->product_spec_service->get_cps($where);
    }

    public function insert_cps($obj)
    {
        return $this->product_spec_service->insert_cps($obj);
    }

    public function update_cps($obj)
    {
        return $this->product_spec_service->update_cps($obj);
    }

    public function get_parent_cat_id($cat_id)
    {
        return $this->category_service->get_parent_cat_id($cat_id);
    }

    public function get_cat_url($cat_id, $relative_path = FALSE)
    {
        return $this->website_service->get_cat_url($cat_id, $relative_path);
    }

    public function get_prod_url($sku, $relative_path = FALSE)
    {
        return $this->website_service->get_prod_url($sku, $relative_path);
    }

    public function get_cat_info_w_lang($where = array(), $option = array())
    {
        return $this->category_service->get_cat_info_w_lang($where, $option);
    }
}

?>