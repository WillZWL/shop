<?php

namespace ESG\Panther\Service;

class BrandService extends BaseService
{
    public function createNewBrand($brand_id, $obj)
    {
        $newObj = new \BrandVo;

        // id come from VB is not reliable, should change to auto_increment
        $newObj->setId($brand_id);
        $this->updateBrand($newObj, $obj);

        return $newObj;
    }

    public function updateBrand($newObj, $oldObj)
    {
        $newObj->setBrandName((string) $oldObj->brand_name);
        $newObj->setDescription((string) $oldObj->description);
        $newObj->setStatus((string) $oldObj->status);
    }

    public function getBrandListWRegion($where = [], $option = [])
    {
        $data["brandlist"] = $this->getDao('Brand')->getBrandListWRegion($where, $option, "BrandWRegionDto");
        $data["total"] = $this->getDao('Brand')->getBrandListWRegion($where, ["num_rows" => 1]);
        return $data;
    }

    public function getListedBrandByCat($cat_id = '')
    {
        return $this->getDao('Brand')->getListedBrandByCat($cat_id);
    }

    public function getNameListWIdKey($where = [], $option = [])
    {
        $option["result_type"] = "array";
        $rslist = [];
        if ($ar_list = $this->getDao('Brnad')->getList($where, $option)) {
            foreach ($ar_list as $rsdata) {
                $rslist[$rsdata["id"]] = $rsdata["brand_name"];
            }
        }
        return $rslist;
    }

    public function getBrandFilterGridInfo($where = [], $option = [])
    {
        return $this->getDao('Brand')->getBrandFilterGridInfo($where, $option);
    }

}


