<?php
namespace ESG\Panther\Service;

class LandpageListingService extends BaseService
{
    public $product_service;

    public function __construct()
    {
        parent::__construct();
        $this->product_service = new ProductService;
    }

    public function getLandpageList($where = [], $option = [])
    {
        return $this->getDao('LandpageListing')->getLandpageList($where, $option);
    }
}
