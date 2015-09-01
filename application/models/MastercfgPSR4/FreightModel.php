<?php
namespace AtomV2\Models\Mastercfg;

use AtomV2\Service\CourierService;
use AtomV2\Service\FreightCatService;
use AtomV2\Service\WeightCatService;
use AtomV2\Service\AuthorizationService;

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
        return $this->courierService->getDao()->getList($where, $option);
    }

    public function getCourier($where = [])
    {
        return $this->courierService->getDao()->get($where);
    }

    public function getCourierListWithRegion($where = [], $option = [])
    {
        return $this->courierService->getDao()->getListWithName($where, $option, "CourierWithRegionDto");
    }

    public function getCourierRegionCountry($where = [], $option = [])
    {
        return $this->courierService->getDao()->getRegionCountryList($where, $option, "CourierRegionCountryDto");
    }

    public function getFreightCatList($where = [], $option = [])
    {
        return $this->freightCatService->getDao()->getList($where, $option);
    }

    public function getFreightCatTotal($where = [])
    {
        return $this->freightCatService->getDao()->getNumRows($where);
    }

    public function getWeightCatList($where = [], $option = [])
    {
        return $this->weightCatService->getDao()->getList($where, $option);
    }

    public function getWeightCatTotal($where = [])
    {
        return $this->weightCatService->getDao()->getNumRows($where);
    }

    public function getFreightCat($where = [])
    {
        return $this->freightCatService->getDao()->get($where);
    }

    public function getWeightCat($where = [])
    {
        return $this->weightCatService->getDao()->get($where);
    }

    public function includeFreightCatVo()
    {
        return $this->freightCatService->getDao()->get();
    }

    public function include_freight_cat_charge_vo()
    {
        return $this->freightCatService->getFreightCatChargeDao()->get();
    }

    public function include_weight_cat_charge_vo()
    {
        return $this->weightCatService->getWeightCatChargeDao()->get();
    }

    public function include_freight_cat_w_region_dto()
    {
        return $this->freightCatService->include_dto("FreightCatWithRegionDto");
    }

    public function include_weight_cat_vo()
    {
        return $this->weightCatService->getDao()->get();
    }

    public function addFreightCat($obj)
    {
        return $this->freightCatService->getDao()->insert($obj);
    }

    public function addWeightCat($obj)
    {
        return $this->weightCatService->getDao()->insert($obj);
    }

    public function addCourier($obj)
    {
        return $this->courierService->getDao()->insert($obj);
    }

    public function getFcc($where = [])
    {
        return $this->freightCatService->getFreightCatChargeDao()->get($where);
    }

    public function getWcc($where = [])
    {
        return $this->weightCatService->getWeightCatChargeDao()->get($where);
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
        return $this->freightCatService->getFreightCatChargeDao()->get();
    }

    public function includeWccVo()
    {
        return $this->weightCatService->getWeightCatChargeDao()->get();
    }

    public function getFccNearestAmount($fcat_id, $weight)
    {
        return $this->freightCatService->getFreightCatChargeDao()->getNearestAmount($fcat_id, $weight);
    }

    public function getWccNearestAmount($wcat_id, $weight)
    {
        return $this->weightCatService->getWeightCatChargeDao()->getNearestAmount($wcat_id, $weight);
    }

    public function addFcc($obj)
    {
        return $this->freightCatService->getFreightCatChargeDao()->insert($obj);
    }

    public function addWcc($obj)
    {
        return $this->weightCatService->getWeightCatChargeDao()->insert($obj);
    }

    public function updateFreightCat($obj)
    {
        return $this->freightCatService->getDao()->update($obj);
    }

    public function updateWeightCat($obj)
    {
        return $this->weightCatService->update($obj);
    }

    public function delFcc($where = [])
    {
        return $this->freightCatService->getFreightCatChargeDao()->delete($where);
    }

    public function delWcc($where = [])
    {
        return $this->weightCatService->getWeightCatChargeDao()->delete($where);
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
