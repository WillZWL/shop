<?php
namespace ESG\Panther\Service;

use ESG\Panther\Dao\SkuMappingDao;

class SkuMappingService extends BaseService
{
    public function createNewSkuMapping($sku, $ext_sku, $vb_sku = '', $ext_sys = 'WMS')
    {
        $obj = new \SkuMappingVo();
        $obj->setSku($sku);
        $obj->setExtSku($ext_sku);
        $obj->setVbSku($vb_sku);
        $obj->setExtSys($ext_sys);

        return $obj;
    }

    public function getMasterSku($where = array())
    {
        if ($obj = $this->getDao('SkuMapping')->get($where)) {
            return $obj->getExtSku();
        } else {
            return false;
        }
    }

    public function getLocalSku($master_sku)
    {
        $where = array("ext_sku" => $master_sku);
        if ($obj = $this->getDao('SkuMapping')->get($where)) {
            return $obj->getSku();
        } else {
            return false;
        }
    }
}
