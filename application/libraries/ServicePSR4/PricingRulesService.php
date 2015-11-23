<?php

namespace ESG\Panther\Service;

class PricingRulesService extends BaseService
{
    public function getPricingRules($where = [], $option = [])
    {
        $data["pricingruleslist"] = $this->getDao('PricingRules')->getPricingRules($where, $option, "PricingRulesDto");
        $data["total"] = $this->getDao('PricingRules')->getPricingRules($where, ["num_rows" => 1]);

        return $data;
    }

	public function getExistingRule($where = [])
    {
        $data["existing"] = $this->getDao('PricingRules')->getExistingRule($where, ["num_rows" => 1]);

        return $data;
    }

	public function getPricingRulesByPlatform($where = [], $option = [])
    {
        return $this->getDao('PricingRules')->getPricingRulesByPlatform($where, $option);
    }
}
