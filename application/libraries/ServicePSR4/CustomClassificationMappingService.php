<?php
namespace ESG\Panther\Service;

use ESG\Panther\Dao\CustomClassificationMappingDao;
use ESG\Panther\Dao\CountryDao;

class CustomClassificationMappingService extends BaseService
{

    function __construct()
    {
        parent::__construct();
        $this->setDao(new CustomClassificationMappingDao);
        $this->setCountryDao(new CountryDao);
    }

    public function setCountryDao($dao)
    {
        $this->country_dao = $dao;
    }

    public function getCustomClassMapping($where = [])
    {
        return $this->getDao('CustomClassificationMapping')->get($where);
    }

    public function insertCustomClassMapping($obj)
    {
        return $this->getDao('CustomClassificationMapping')->insert($obj);
    }

    public function updateCustomClassMapping($obj)
    {
        return $this->getDao('CustomClassificationMapping')->update($obj);
    }

    public function addCustomClassMapping($obj)
    {
        return $this->getDao('CustomClassificationMapping')->insert($obj);
    }

    public function getCustomClassMappingList($where = [], $option = [])
    {
        if ($objlist = $this->getDao('CustomClassificationMapping')->getCustomClassMappingList($where, $option)) {
            foreach ($objlist as $obj) {
                $data["ccmlist"][$obj->getSubCatId()] = $obj;
            }
        }
        $option["limit"] = -1;
        $allRecord = $this->getDao('CustomClassificationMapping')->getCustomClassMappingList($where, $option);

        $data["total"] = count((array) $allRecord);
        return $data;
    }

    public function getCustomClassMappingBySubCatId($sub_cat_id = "")
    {
        if ($country_list = $this->getCountryDao()->getList(["allow_sell" => 1], ["limit" => -1])) {
            foreach ($country_list as $country_obj) {
                $ccm_obj = $this->getDao('CustomClassificationMapping')->getCustomClassMappingList(["sub_cat_id" => $sub_cat_id, "ccm.country_id" => $country_obj->getCountryId()], ["limit" => 1]);
                $rs[$country_obj->getCountryId()] = $ccm_obj;
            }
            return $rs;
        }
        return false;
    }

    public function getCountryDao()
    {
        return $this->country_dao;
    }

    public function getAllCustomClassMappingBySubCatId($sub_cat_id = "")
    {
        return $this->getDao('CustomClassificationMapping')->getAllCustomClassMappingList($sub_cat_id, $option = '');
    }
}


