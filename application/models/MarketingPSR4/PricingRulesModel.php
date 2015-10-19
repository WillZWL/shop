<?php
namespace ESG\Panther\Models\Marketing;

use ESG\Panther\Service\PricingRulesService;

class PricingRulesModel extends \CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->pricingRulesService = new PricingRulesService;
    }

    public function getPricingRulesList($where = [], $option = [])
    {
        return $this->pricingRulesService->getPricingRules($where, $option);
    }

    public function getPricingRule($where = [])
    {
        return $this->pricingRulesService->getDao('PricingRules')->get($where);
    }

    public function updatePricingRules($obj)
    {
        return $this->pricingRulesService->getDao('PricingRules')->update($obj);
    }

    public function includePricingRulesVo()
    {
        return $this->pricingRulesService->getDao('PricingRules')->get();
    }

    public function addPricingRules($obj)
    {
        return $this->pricingRulesService->getDao('PricingRules')->insert($obj);
    }

}
