<?php
namespace AtomV2\Service;

use AtomV2\Dao\SkuMappingDao;

class BaseProductService extends BaseService
{
    private $skuMappingObj;

    public function create()
    {
        $this->addSkuMapping();
        $this->addProductSpec();
        $this->uploadMedia();
        $this->addProductWarranty();
        $this->addProductCustomClassification();
        $this->addProductKeyword();
        // $this->addAdgroupKeywords();
        $this->addProductContent();
    }

    public function addSkuMapping(SkuMappingVo $obj)
    {
        $this->getSkuMappingDao()->insert($obj);
    }

    public function addProductSpec()
    {
        $this->addProductSpceDetail();
    }

    public function uploadMedia()
    {
        $this->uploadImage();
        $this->uploadFlash();
    }

    public function addProductWarranty()
    {

    }

    public function addProductCustomClassification()
    {

    }

    public function addProductKeyword()
    {

    }

    public function addProductContent()
    {
        $this->addProductContentExtend();
    }
}
