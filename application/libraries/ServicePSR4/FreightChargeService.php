<?php

namespace ESG\Panther\Service;

class FreightChargeService extends BaseService
{
    public function calculateLogisticCost(\PriceWithCostDto $dto)
    {
        $this->getDao('FreightCatCharge')->calcLogisticCost($dto->getPlatformId(), $dto->getSku());

        if ($lc = $this->getDao('FreightCatCharge')->calcLogisticCost($dto->getPlatformId(), $dto->getSku())) {
            $dto->setLogisticCost($lc['converted_amount']);
        } else {
            $dto->setLogisticCost(0);
        }
    }
}
