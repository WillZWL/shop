<?php

namespace ESG\Panther\Service;

class BundleConfigService extends BaseService
{
    public function getBundleConfig($where = [], $option = [])
    {
        $data["bundleconfiglist"] = $this->getDao('BundleConfig')->getBundleConfig($where, $option, "BundleConfigDto");
        $data["total"] = $this->getDao('BundleConfig')->getBundleConfig($where, ["num_rows" => 1]);

        return $data;
    }

	/*public function getBundleConfigByCountry($where = [], $option = [])
    {
        return $this->getDao('BundleConfig')->getBundleConfigByCountry($where, $option);
    }*/
}
