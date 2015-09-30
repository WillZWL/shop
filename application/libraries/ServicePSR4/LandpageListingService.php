<?php
namespace ESG\Panther\Service;

abstract class LandpageListingService extends BaseService
{
    public $product_service;

    public function __construct()
    {
        parent::__construct();
        $this->product_service = new ProductService;
    }
}
