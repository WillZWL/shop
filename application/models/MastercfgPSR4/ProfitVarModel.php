<?php
namespace ESG\Panther\Models\Mastercfg;

use ESG\Panther\Service\PlatformBizVarService;
use ESG\Panther\Service\RegionService;
use ESG\Panther\Service\CountryService;
use ESG\Panther\Service\CourierService;
use ESG\Panther\Service\DeliveryTypeService;
use ESG\Panther\Service\ShiptypeService;
use ESG\Panther\Service\SellingPlatformService;
use ESG\Panther\Service\LanguageService;

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
        return $this->sellingPlatformService->getDao('SellingPlatform')->get(["selling_platform_id" => $value]);
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
        return $this->regionService->getDao('Region')->getList(["type" => "C"]);
    }

    public function getCourierList()
    {
        return $this->courierService->getDao('Courier')->getList(["type" => "W", "weight_type <>" => "CO"]);
    }

    public function getCountryList($where = [], $option = [])
    {
        return $this->countryService->getDao('Country')->getList($where, $option);
    }

    public function getDeliveryTypeList()
    {
        return $this->deliveryTypeService->getDao('DeliveryType')->getList();
    }

    public function getShiptypeList($where = [])
    {
        return $this->shiptypeService->getDao()->getList($where);
    }
}
