<?php
namespace ESG\Panther\Service;

class BestSellerService extends LandpageListingService
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getBestSellerProduct($where, $option)
    {
        return $this->product_service->getHomeProduct($where, $option);
    }
}
