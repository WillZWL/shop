<?php
namespace ESG\Panther\Service;

use ESG\Panther\Dao\BrandDao;
use ESG\Panther\Dao\BrandRegionDao;

class BrandService extends BaseService
{

    private $brDao;

    public function __construct()
    {
        parent::__construct();
        $this->setDao(new BrandDao);
        $this->setBrDao(new BrandRegionDao);
    }

    public function getBrDao()
    {
        return $this->brDao;
    }

    public function setBrDao($dao)
    {
        $this->brDao = $dao;
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


