<?php

include_once "Base_service.php";

class Category_mapping_service extends Base_service
{

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/dao/Category_mapping_dao.php");
        $this->set_dao(new Category_mapping_dao());
    }

    public function insert_cat_map($obj)
    {
        return $this->get_dao()->insert($obj);
    }

    public function update_cat_map($obj)
    {
        return $this->get_dao()->update($obj);
    }

    public function update_or_insert_mapping($sku, $lang_id_temp, $country_id, $google_cat_id, $google_product_name)
    {
        if ($cat_map_obj = $this->get_cat_map(array("ext_party" => "GOOGLEBASE", "level" => "0", "id" => $sku, "lang_id" => $lang_id_temp, "country_id" => $country_id, "status" => 1))) {
            $cat_map_action = "update";
            //$cat_map_obj->set_ext_id($google_cat_id);
            $cat_map_obj->set_product_name($google_product_name);
            $cat_map_obj->set_lang_id($lang_id_temp);
            $cat_map_obj->set_country_id($country_id);
        } else {
            $cat_map_action = "insert";
            $cat_map_obj = $this->get_cat_map();
            $cat_map_obj->set_ext_party("GOOGLEBASE");
            //$cat_map_obj->set_ext_id($google_cat_id);
            $cat_map_obj->set_product_name($google_product_name);
            $cat_map_obj->set_level(0);
            $cat_map_obj->set_id($sku);
            $cat_map_obj->set_lang_id($lang_id_temp);
            $cat_map_obj->set_country_id($country_id);
            $cat_map_obj->set_status(1);
        }
        $this->{$cat_map_action . "_cat_map"}($cat_map_obj);
    }

    public function get_cat_map($where = array())
    {
        return $this->get_dao()->get($where);
    }

    public function get_googlebase_cat_list_w_country($where = array(), $option = array())
    {
        return $this->get_dao()->get_googlebase_cat_list_w_country($where, $option);
    }

}

/* End of file category_mapping_service.php */
/* Location: ./app/libraries/service/Category_mapping_service.php */