<?php
namespace AtomV2\Service;

use AtomV2\Dao\BrandDao;
use AtomV2\Dao\BrandRegionDao;

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
        $data["brandlist"] = $this->getDao()->getBrandListWRegion($where, $option, "BrandWRegionDto");
        $data["total"] = $this->getDao()->getBrandListWRegion($where, ["num_rows" => 1]);
        return $data;
    }

    public function getListedBrandByCat($cat_id = '')
    {
        return $this->getDao()->getListedBrandByCat($cat_id);
    }

    public function getNameListWIdKey($where = [], $option = [])
    {
        $option["result_type"] = "array";
        $rslist = [];
        if ($ar_list = $this->getDao()->getList($where, $option)) {
            foreach ($ar_list as $rsdata) {
                $rslist[$rsdata["id"]] = $rsdata["brand_name"];
            }
        }
        return $rslist;
    }

    public function getBrandFilterGridInfo($where = [], $option = [])
    {
        return $this->getDao()->getBrandFilterGridInfo($where, $option);
    }

}


