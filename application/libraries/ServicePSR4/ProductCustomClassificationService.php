<?php

namespace ESG\Panther\Service;

class ProductCustomClassificationService extends BaseService
{
    public function calculateDuty(\PriceWithCostDto $dto)
    {
        $duty = $dto->getDeclaredValue() * $dto->getDutyPcent() / 100;
        $dto->setDuty(number_format($duty, 2, '.', ''));
    }
}


