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

        if ($latestProductSkuList) {
            foreach ($latestProductSkuList as $obj) {
                $sku[] = $obj->getSku();
            }
            $where["pd.sku in ('" . implode("','", $sku) ."') "] = null;

            $latestProductInfo = $this->latestArrivalsService->getProductInfo($where, $option);

            return $latestProductInfo;
        }

        return [];
    }

    public function getBestSellerProduct($where, $option)
    {
        $bestSellerSkuList = $this->bestSellerService->getBestSellerSku();

        if ($bestSellerSkuList) {
            foreach ($bestSellerSkuList as $obj) {
                $sku[] = $obj->getSku();
            }
            $where["pd.sku in ('" . implode("','", $sku) ."') "] = null;

            $bestSellerProductInfo = $this->bestSellerService->getProductInfo($where, $option);

            return $bestSellerProductInfo;
        }

        return [];
    }
}
