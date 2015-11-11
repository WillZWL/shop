<?php
namespace ESG\Panther\Service;

class LatestArrivalsService extends LandpageListingService
{
    public function getLatestArrivalProduct($where, $option)
    {
        return $this->product_service->getHomeProduct($where, $option);
    }
}
