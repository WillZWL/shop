<?php
namespace AtomV2\Service;

class LatestArrivalsService extends LandpageListingService
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getLatestArrivalProduct($where, $option)
    {
        return $this->product_service->getHomeProduct($where, $option);
    }
}
