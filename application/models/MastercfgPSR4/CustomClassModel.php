<?php
namespace AtomV2\Models\Mastercfg;

use AtomV2\Service\RegionService;
use AtomV2\Service\CountryService;
use AtomV2\Service\CategoryService;
use AtomV2\Service\CustomClassService;
use AtomV2\Service\CustomClassificationMappingService;

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

    public function getCountryList($where = [], $option = [])
    {
        return $this->countryService->getList($where, $option);
    }

    public function getSubCatList($where = [], $option = [])
    {
        return $this->categoryService->getList($where, $option);
    }

    public function getCustomClassList($where = [], $option = [])
    {
        return $this->customClassService->getList($where, $option);
    }

    public function getRegionList($where = [], $option = [])
    {
        return $this->regionService->getList($where, $option);
    }

    public function getCustomClassObjList($where = [], $option = [])
    {
        $data["cclist"] = $this->customClassService->getList($where, $option);
        $data["total"] = $this->customClassService->getNumRows($where);
        return $data;
    }

    public function getCustomClass($where = [])
    {
        return $this->customClassService->get($where);
    }

    public function getCustomClassOption($where = [])
    {
        return $this->customClassService->getOption($where);
    }

    public function updateCustomClass($obj)
    {
        return $this->customClassService->update($obj);
    }

    public function includeCustomClassVo()
    {
        return $this->customClassService->get();
    }

    public function addCustomClass($obj)
    {
        return $this->customClassService->insert($obj);
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
