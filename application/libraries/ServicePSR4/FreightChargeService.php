<?php

namespace ESG\Panther\Service;

class FreightChargeService extends BaseService
{
    public function calculateLogisticCost(\PriceWithCostDto $dto)
    {
        $logistic_cost = $this->getDao('FreightCatCharge')->calculateLogisticCost($dto->getPlatformId(), $dto->getSku());
        $dto->setLogisticCost($logistic_cost);
    }
}
