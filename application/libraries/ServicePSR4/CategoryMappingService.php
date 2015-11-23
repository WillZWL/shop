<?php
namespace ESG\Panther\Service;

class CategoryMappingService extends BaseService
{

    public function __construct()
    {
        parent::__construct();
    }

    public function insert_cat_map($obj)
    {
        return $this->getDao('CategoryMapping')->insert($obj);
    }

    public function update_cat_map($obj)
    {
        return $this->getDao('CategoryMapping')->update($obj);
    }

    public function update_or_insert_mapping($sku, $lang_id_temp, $country_id, $google_cat_id, $google_product_name)
    {
        if ($cat_map_obj = $this->getCatMap(["ext_party" => "GOOGLEBASE", "level" => "0", "id" => $sku, "lang_id" => $lang_id_temp, "country_id" => $country_id, "status" => 1])) {
            $cat_map_action = "update";
            //$cat_map_obj->set_ext_id($google_cat_id);
            $cat_map_obj->set_product_name($google_product_name);
            $cat_map_obj->set_lang_id($lang_id_temp);
            $cat_map_obj->set_country_id($country_id);
        } else {
            $cat_map_action = "insert";
            $cat_map_obj = $this->getCatMap();
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

    public function getCatMap($where = [])
    {
        return $this->getDao('CategoryMapping')->get($where);
    }

    public function getGooglebaseCatListWithCountry($where = [], $option = [])
    {
        return $this->getDao('CategoryMapping')->getGooglebaseCatListWithCountry($where, $option);
    }

}


