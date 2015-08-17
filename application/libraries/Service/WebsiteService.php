<?php
namespace AtomV2\Service;

class WebsiteService extends BaseService
{
    private $productService;
    private $latestArrivalsService;
    private $bestSellerService;

    public function __construct()
    {
        $this->productService = new ProductService;
        $this->latestArrivalsService = new LatestArrivalsService;
        $this->bestSellerService = new BestSellerService;
    }

    public function getHomeContent($langId = 'en')
    {
        $data['latest_arrival'] = $this->getLatestArrivalProduct($where, $option);
        $data['best_seller'] = $this->getBestSellerProduct($where, $option);

        return $data;
    }

    public function getLatestArrivalProduct($where, $option)
    {
        $latestProductSkuList = $this->latestArrivalsService->getLatestArrivalSku($where, $option);
        $latestProductInfo = $this->latestArrivalsService->getProductInfo($latestProductSkuList);

        return $latestProductInfo;
    }

    public function getBestSellerProduct($where, $option)
    {
        $bestSellerSkuList = $this->bestSellerService->getBestSellerSku();
        $bestSellerProductInfo = $this->bestSellerService->getProductInfo($bestSellerSkuList);

        return $bestSellerProductInfo;
    }
}
