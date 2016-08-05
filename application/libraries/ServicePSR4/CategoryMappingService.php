<?php
namespace ESG\Panther\Service;

class CategoryMappingService extends BaseService
{

    public function __construct()
    {
        parent::__construct();
    }

    public function insertCatMap($obj)
    {
        return $this->getDao('CategoryMapping')->insert($obj);
    }

    public function updateCatMap($obj)
    {
        return $this->getDao('CategoryMapping')->update($obj);
    }

    public function updateOrInsertMapping($sku, $lang_id_temp, $country_id, $google_cat_id, $google_product_name)
    {
        if ($cat_map_obj = $this->getCatMap(["ext_party" => "GOOGLEBASE", "level" => "0", "category_mapping_id" => $sku, "lang_id" => $lang_id_temp, "country_id" => $country_id, "status" => 1])) {
            $cat_map_action = "update";
            $cat_map_obj->setProductName($google_product_name);
            $cat_map_obj->setLangId($lang_id_temp);
            $cat_map_obj->setCountryId($country_id);
        } else {
            $cat_map_action = "insert";
            $cat_map_obj = $this->getCatMap();
            $cat_map_obj->setExtParty("GOOGLEBASE");
            $cat_map_obj->setProductName($google_product_name);
            $cat_map_obj->setLevel(0);
            $cat_map_obj->setCategoryMappingId($sku);
            $cat_map_obj->setLangId($lang_id_temp);
            $cat_map_obj->setCountryId($country_id);
            $cat_map_obj->setStatus(1);
        }
        $this->{$cat_map_action . "CatMap"}($cat_map_obj);
    }

    public function getCatMap($where = [])
    {
        return $this->getDao('CategoryMapping')->get($where);
    }

    public function getGooglebaseCatListWithCountry($where = [], $option = [])
    {
        return $this->getDao('CategoryMapping')->getGooglebaseCatListWithCountry($where, $option);
    }

    public function createNewCategoryMapping($obj)
    {
        $newObj = new \CategoryMappingVo();

        // id come from VB is not reliable, should use auto-increment id
        $newObj->setExtParty((string) $obj->ext_party);
        $newObj->setLevel((string) $obj->level);
        $newObj->setCategoryMappingId((string) $obj->id);
        $newObj->setLangId((string) $obj->lang_id);
        $newObj->setCountryId((string) $obj->country_id);
        $this->updateCategoryMapping($newObj, $obj);
        return $newObj;
    }

    public function updateCategoryMapping($newObj, $oldObj)
    {
        $newObj->setExtId((string) $oldObj->ext_id);
        $newObj->setExtName((string) $oldObj->ext_name);
        $newObj->setProductName((string) $oldObj->product_name);
        $newObj->setStatus((string) $oldObj->status);
    }

}


