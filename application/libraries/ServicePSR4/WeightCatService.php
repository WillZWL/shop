<?php
namespace ESG\Panther\Service;

use ESG\Panther\Dao\WeightCategoryDao;
use ESG\Panther\Dao\WeightCatChargeDao;
use ESG\Panther\Service\ColourService;

class WeightCatService extends BaseService
{

    private $wcc_dao;

    public function __construct()
    {
        parent::__construct();
        $this->setDao(new WeightCategoryDao);
        $this->setWeightCatChargeDao(new WeightCatChargeDao);
        $this->colourService = new ColourService;
    }

    public function getWccWithRegList($where = [], $option = [])
    {
        $option["limit"] = -1;
        $cat_w_reg_list = $this->getDao()->get_cat_w_region([], $option, "Freight_cat_w_region_dto");
        $courier_reg_list = $this->colourService->getCrcDao()->getList(["courier_id" => $where["courier_id"]], ["orderby" => "region_id ASC", "limit" => -1]);
        $wcc_list = $this->getWeightCatChargeDao()->getList($where, ["orderby" => "wcat_id ASC, region_id ASC", "limit" => -1]);
        $wcc_vo = $this->getWeightCatChargeDao()->get();

        foreach ($wcc_list as $wcc) {
            $new_wcc[$wcc->getWcatId()][$wcc->getRegionId()] = $wcc;
        }
        foreach ($cat_w_reg_list as $cat) {
            $cat_id = $cat->getCatId();
            foreach ($courier_reg_list as $region) {
                $region_id = $region->getRegionId();
                if (empty($new_wcc[$cat_id][$region_id])) {
                    $vo = clone $wcc_vo;
                    set_value($vo, $region);
                    $vo->setWcatId($cat_id);
                    $new_wcc[$cat_id][$region_id] = $vo;
                }
            }
            if ($new_wcc[$cat_id]) {
                ksort($new_wcc[$cat_id]);
            }
            $cat->setCharge((object)$new_wcc[$cat_id]);
            $new_cat[$cat_id] = $cat;
        }

        return $new_cat;
    }

    public function getWeightCatChargeDao()
    {
        return $this->wcc_dao;
    }

    public function setWeightCatChargeDao($dao)
    {
        $this->wcc_dao = $dao;
    }

    public function getFullWeightCatChargeList($where = [], $option = [])
    {
        if ($objlist = $this->getWeightCatChargeDao()->getFullWeightCatChargeList($where, $option)) {
            foreach ($objlist as $obj) {
                $rs[$obj->getWcatId()][$obj->getDestCountry()] = $obj;
            }
            return $rs;
        }
        return FALSE;
    }

    public function getDefaultDeliveryCharge($platform_id, $shiptype, $weight)
    {
        $dao = $this->getDao();
        return $dao->getDefaultDeliveryCharge($platform_id, $shiptype, $weight);
    }

    public function get_courier_country_list($weight)
    {
        $dao = $this->setWeightCatChargeDao();
        return $dao->get_courier_country_list($weight);
    }

    public function get_wc_from_fc($fc = "")
    {
        return $this->getDao()->getFromFc($fc);
    }

    public function insertWcc($obj)
    {
        return $this->getWeightCatChargeDao()->insert($obj);
    }

    public function updateWcc($obj)
    {
        return $this->getWeightCatChargeDao()->update($obj);
    }
}


