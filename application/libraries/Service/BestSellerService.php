<?php
namespace AtomV2\Service;

class BestSellerService extends ProductService
{
    private $productType;
    private $gridDisplayLimit;

    public function __construct()
    {
        parent::__construct();
        $this->productType = 'BS';
    }

    public function getBestSellerSku()
    {

    }

    public function getBestSellerProduct($where, $option)
    {
        $where['ll.type'] = $this->productType;
        $option['limit'] = $this->gridDisplayLimit;
        return $this->product_service->getHomeProduct($where, $option);
    }
}
