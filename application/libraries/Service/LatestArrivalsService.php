<?php
namespace AtomV2\Service;

class LatestArrivalsService extends LandpageListingService
{
	private $product_service;

    public function __construct()
    {
        parent::__construct();
        $this->product_service = new ProductService;
    }

    public function getLatestArrivalProduct($where, $option)
    {
    	return $this->product_service->getLatestArrivalProduct($where, $option);
    }
}
