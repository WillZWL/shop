<?php
namespace ESG\Panther\Models\Mastercfg;

use ESG\Panther\Service\RegionService;
use ESG\Panther\Service\CountryService;
use ESG\Panther\Service\CategoryService;
use ESG\Panther\Service\CustomClassService;
use ESG\Panther\Service\CustomClassificationMappingService;

class CustomClassModel extends \CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->regionService = new RegionService;
        $this->countryService = new CountryService;
        $this->categoryService = new CategoryService;
        $this->customClassService = new CustomClassService;
        $this->customClassificationMappingService = new CustomClassificationMappingService;
    }

    public function saveCustomClassMapping($ccmap, $i, $value, $name)
    {
        return $this->customClassService->saveCustomClassMapping($ccmap, $i, $value, $name);
    }

    public function getCountryList($where = [], $option = [])
    {
        return $this->countryService->getDao('Country')->getList($where, $option);
    }

    public function getSubCatList($where = [], $option = [])
    {
        return $this->categoryService->getDao('Category')->getList($where, $option);
    }

    public function getCustomClassList($where = [], $option = [])
    {
        return $this->customClassService->getDao('CustomClassification')->getList($where, $option);
    }

    public function getRegionList($where = [], $option = [])
    {
        return $this->regionService->getDao('Region')->getList($where, $option);
    }

    public function getCustomClassObjList($where = [], $option = [])
    {
        $data["cclist"] = $this->customClassService->getDao('CustomClassification')->getList($where, $option);
        $data["total"] = $this->customClassService->getDao('CustomClassification')->getNumRows($where);
        return $data;
    }

    public function getCustomClass($where = [])
    {
        return $this->customClassService->getDao('CustomClassification')->get($where);
    }

    public function getCustomClassOption($where = [])
    {
        return $this->customClassService->getDao('CustomClassification')->getOption($where);
    }

    public function updateCustomClass($obj)
    {
        return $this->customClassService->getDao('CustomClassification')->update($obj);
    }

    public function includeCustomClassVo()
    {
        return $this->customClassService->getDao('CustomClassification')->get();
    }

    public function addCustomClass($obj)
    {
        return $this->customClassService->getDao('CustomClassification')->insert($obj);
    }

    public function getProductCustomClass($where = [])
    {
        return $this->customClassService->getProductCustomClass($where);
    }

    public function getProductCustomClassList($where = [], $option = [])
    {
        return $this->customClassService->getProductCustomClassList($where, $option);
    }

    public function getFullProductCustomClassBySku($where = [], $option = [])
    {
        return $this->customClassService->getFullProductCustomClassBySku($where, $option);
    }

    public function updateProductCustomClass($obj)
    {
        return $this->customClassService->updateProductCustomClass($obj);
    }

    public function includeProductCustomClassVo()
    {
        return $this->customClassService->includeProductCustomClassVo();
    }

    public function addProductCustomClass($obj)
    {
        return $this->customClassService->addProductCustomClass($obj);
    }

    public function getCustomClassMappingList($where = [], $option = [])
    {
        return $this->customClassificationMappingService->getCustomClassMappingList($where, $option);
    }

    public function getCustomClassMapping($where = [])
    {
        return $this->customClassificationMappingService->getCustomClassMapping($where);
    }

    public function insertCustomClassMapping($obj)
    {
        return $this->customClassificationMappingService->insertCustomClassMapping($obj);
    }

    public function updateCustomClassMapping($obj)
    {
        return $this->customClassificationMappingService->updateCustomClassMapping($obj);
    }

    public function includeCustomClassMapping_vo()
    {
        return $this->customClassificationMappingService->get();
    }

    public function addCustomClassMapping($obj)
    {
        return $this->customClassificationMappingService->addCustomClassMapping($obj);
    }

    public function getCustomClassByCatSubId($sub_cat_id)
    {
        return $this->customClassificationMappingService->getAllCustomClassMappingBySubCatId($sub_cat_id);
    }

}
