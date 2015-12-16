<?php

namespace ESG\Panther\Service;

class SubCatPlatformVarService extends BaseService
{
    public function calculatePlatformCommission(\PriceWithCostDto $dto)
    {
        $platform_commission = $dto->getPrice() * $dto->getPlatformCommissionPercent() / 100;
        $dto->setPlatformCommission(number_format($platform_commission, 2, '.', ''));
    }
}
