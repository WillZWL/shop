<?php
namespace ESG\Panther\Models\Mastercfg;

use ESG\Panther\Service\CourierService;
use ESG\Panther\Service\FreightCatService;
use ESG\Panther\Service\WeightCatService;
use ESG\Panther\Service\AuthorizationService;

class FreightModel extends \CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->courierService = new CourierService;
        $this->freightCatService = new FreightCatService;
        $this->weightCatService = new WeightCatService;
        $this->authorizationService = new AuthorizationService;
    }

    public function getCourierList($where = [], $option = [])
    {
        return $this->courierService->getDao('Courier')->getList($where, $option);
    }

    public function getCourier($where = [])
    {
        return $this->courierService->getDao('Courier')->get($where);
    }

    public function getCourierListWithRegion($where = [], $option = [])
    {
        return $this->courierService->getDao('Courier')->getListWithName($where, $option, "CourierWithRegionDto");
    }

    public function getCourierRegionCountry($where = [], $option = [])
    {
        return $this->courierService->getDao('Courier')->getRegionCountryList($where, $option, "CourierRegionCountryDto");
    }

    public function getFreightCatList($where = [], $option = [])
    {
        return $this->freightCatService->getDao('FreightCategory')->getList($where, $option);
    }

    public function getFreightCatTotal($where = [])
    {
        return $this->freightCatService->getDao('FreightCategory')->getNumRows($where);
    }

    public function getWeightCatList($where = [], $option = [])
    {
        return $this->weightCatService->getDao('WeightCategory')->getList($where, $option);
    }

    public function getWeightCatTotal($where = [])
    {
        return $this->weightCatService->getDao('WeightCategory')->getNumRows($where);
    }

    public function getFreightCat($where = [])
    {
        return $this->freightCatService->getDao('FreightCategory')->get($where);
    }

    public function getWeightCat($where = [])
    {
        return $this->weightCatService->getDao('WeightCategory')->get($where);
    }

    public function includeFreightCatVo()
    {
        return $this->freightCatService->getDao('FreightCategory')->get();
    }

    public function include_freight_cat_charge_vo()
    {
        return $this->freightCatService->getDao('FreightCatCharge')->get();
    }

    public function include_weight_cat_charge_vo()
    {
        return $this->weightCatService->getDao('WeightCatCharge')->get();
    }

    public function include_freight_cat_w_region_dto()
    {
        return $this->freightCatService->include_dto("FreightCatWithRegionDto");
    }

    public function include_weight_cat_vo()
    {
        return $this->weightCatService->getDao('WeightCategory')->get();
    }

    public function addFreightCat($obj)
    {
        return $this->freightCatService->getDao('FreightCategory')->insert($obj);
    }

    public function addWeightCat($obj)
    {
        return $this->weightCatService->getDao('WeightCategory')->insert($obj);
    }

    public function addCourier($obj)
    {
        return $this->courierService->getDao('Courier')->insert($obj);
    }

    public function getFcc($where = [])
    {
        return $this->freightCatService->getDao('FreightCatCharge')->get($where);
    }

    public function getWcc($where = [])
    {
        return $this->weightCatService->getDao('WeightCatCharge')->get($where);
    }

    public function getFccWithRegList($where = [], $option = [])
    {
        return $this->freightCatService->getFccWithRegList($where, $option);
    }

    public function getWccWithRegList($where = [], $option = [])
    {
        return $this->weightCatService->getWccWithRegList($where, $option);
    }

    public function includeFccVo()
    {
        return $this->freightCatService->getDao('FreightCatCharge')->get();
    }

    public function includeWccVo()
    {
        return $this->weightCatService->getDao('WeightCatCharge')->get();
    }

    public function getFccNearestAmount($fcat_id, $weight)
    {
        return $this->freightCatService->getDao('FreightCatCharge')->getNearestAmount($fcat_id, $weight);
    }

    public function getWccNearestAmount($wcat_id, $weight)
    {
        return $this->weightCatService->getDao('WeightCatCharge')->getNearestAmount($wcat_id, $weight);
    }

    public function addFcc($obj)
    {
        return $this->freightCatService->getDao('FreightCatCharge')->insert($obj);
    }

    public function addWcc($obj)
    {
        return $this->weightCatService->getDao('WeightCatCharge')->insert($obj);
    }

    public function updateFreightCat($obj)
    {
        return $this->freightCatService->getDao('FreightCategory')->update($obj);
    }

    public function updateWeightCat($obj)
    {
        return $this->weightCatService->getDao('WeightCatCharge')->update($obj);
    }

    public function delFcc($where = [])
    {
        return $this->freightCatService->getDao('FreightCatCharge')->delete($where);
    }

    public function delWcc($where = [])
    {
        return $this->weightCatService->getDao('WeightCatCharge')->delete($where);
    }

    public function getOriginCountryList()
    {
        return $this->freightCatService->getOriginCountryList();
    }

    public function getFullFreightCatChargeList($where = [], $option = [])
    {
        return $this->freightCatService->getFullFreightCatChargeList($where, $option);
    }

    public function saveFreightCatCharge($values = [], $origin_country = "")
    {
        return $this->freightCatService->saveFreightCatCharge($values, $origin_country);
    }
}
