<?php
namespace ESG\Panther\Service;

use ESG\Panther\Dao\PricingRulesDao;

class PricingRulesService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
        $this->setDao(new PricingRulesDao);
    }

    public function getPricingRules($where = [], $option = [])
    {
        $data["pricingruleslist"] = $this->getDao('PricingRules')->getPricingRules($where, $option, "PricingRulesDto");
        $data["total"] = $this->getDao('PricingRules')->getPricingRules($where, ["num_rows" => 1]);
        return $data;
    }

}

