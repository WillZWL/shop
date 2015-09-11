<?php
namespace ESG\Panther\Models\Mastercfg;

use ESG\Panther\Service\BrandService;
use ESG\Panther\Service\RegionService;

class BrandModel extends \CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->brandService = new BrandService;
        $this->regionService = new RegionService;
    }

    public function getBrandList($where = [], $option = [])
    {
        return $this->brandService->getBrandListWRegion($where, $option);
    }

    public function getBrand($where = [])
    {
        return $this->brandService->getDao()->get($where);
    }

    public function updateBrand($obj)
    {
        return $this->brandService->getDao()->update($obj);
    }

    public function getRegionList($where = [], $option = [])
    {
        return $this->regionService->getList($where);
    }

    public function includeBrandVo()
    {
        return $this->brandService->getDao()->get();
    }

    public function addBrand($obj)
    {
        return $this->brandService->getDao()->insert($obj);
    }

    public function getBrandRegion($where = [])
    {
        return $this->brandService->getBrDao()->get($where);
    }

    public function getBrandRegionList($where = [])
    {
        return $this->brandService->getBrDao()->getList($where);
    }

    public function delBrandRegion($where)
    {
        return $this->brandService->getBrDao()->delete($where);
    }

    public function addBrandRegion($obj)
    {
        return $this->brandService->getBrDao()->insert($obj);
    }

    public function includeBrandRegionVo()
    {
        return $this->brandService->getBrDao()->get();
    }

}
