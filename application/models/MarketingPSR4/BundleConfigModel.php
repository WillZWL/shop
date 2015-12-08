<?php
namespace ESG\Panther\Models\Marketing;

use ESG\Panther\Service\BundleConfigService;

class BundleConfigModel extends \CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->BundleConfigService = new BundleConfigService;
    }

    public function getBundleConfigList($where = [], $option = [])
    {
        return $this->BundleConfigService->getBundleConfig($where, $option);
    }

    public function getBundleConfig($where = [])
    {
        return $this->BundleConfigService->getDao('BundleConfig')->get($where);
    }

    public function updateBundleConfig($obj)
    {
        return $this->BundleConfigService->getDao('BundleConfig')->update($obj);
    }

    public function includeBundleConfigVo()
    {
        return $this->BundleConfigService->getDao('BundleConfig')->get();
    }

    public function addBundleConfig($obj)
    {
        return $this->BundleConfigService->getDao('BundleConfig')->insert($obj);
    }

}
