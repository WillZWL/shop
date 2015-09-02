<?php
namespace AtomV2\Models\Mastercfg;

use AtomV2\Service\PlatformBizVarService;
use AtomV2\Service\RegionService;
use AtomV2\Service\CountryService;
use AtomV2\Service\CourierService;
use AtomV2\Service\DeliveryTypeService;
use AtomV2\Service\ShiptypeService;
use AtomV2\Service\SellingPlatformService;
use AtomV2\Service\LanguageService;

class ProfitVarModel extends \CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->platformBizVarService = new PlatformBizVarService;
        $this->regionService = new RegionService;
        $this->countryService = new CountryService;
        $this->courierService = new CourierService;
        $this->deliveryTypeService = new DeliveryTypeService;
        $this->shiptypeService = new ShiptypeService;
        $this->sellingPlatformService = new SellingPlatformService;
        $this->languageService = new LanguageService;
    }

    public function getSellingPlatformList($where = [], $option = [])
    {
        return $this->platformBizVarService->getSellingPlatformList($where, $option);
    }

    public function getCurrencyList()
    {
        return $this->platformBizVarService->getCurrencyList();
    }

    public function getPlatformBizVar($id = "")
    {
        return $this->platformBizVarService->getPlatformBizVar($id);
    }

    public function checkPlatform($value)
    {
        return $this->sellingPlatformService->getDao()->get(["selling_platform_id" => $value]);
    }

    public function update($data)
    {
        return $this->platformBizVarService->update($data);
    }

    public function add($data)
    {
        return $this->platformBizVarService->getDao()->insert($data);
    }

    public function autoload()
    {
        $this->platformBizVarService->loadVo();
    }

    public function getCourierRegionList()
    {
        return $this->regionService->getDao()->getList(["type" => "C"]);
    }

    public function getCourierList()
    {
        return $this->courierService->getDao()->getList(["type" => "W", "weight_type <>" => "CO"]);
    }

    public function getCountryList($where = [], $option = [])
    {
        return $this->countryService->getDao()->getList($where, $option);
    }

    public function getDeliveryTypeList()
    {
        return $this->deliveryTypeService->getDao()->getList();
    }

    public function getShiptypeList($where = [])
    {
        return $this->shiptypeService->getDao()->getList($where);
    }
}

?>