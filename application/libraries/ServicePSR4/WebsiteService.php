<?php
namespace ESG\Panther\Service;

class WebsiteService extends BaseService
{
    private $price_service;
    private $latest_arrivals_service;
    private $best_seller_service;

    public function __construct()
    {
        // $this->setDao(new SiteConfigDao);
        $this->price_service = new PriceService;
        $this->latest_arrivals_service = new LatestArrivalsService;
        $this->best_seller_service = new BestSellerService;
    }

    public function getHomeContent($lang_id = 'en')
    {
        $grid_display_limit = 6;
        $option = ['limit' => $grid_display_limit];
        $where = ['ll.platform_id' => PLATFORM];
        $where['ll.type'] = 'LA';

        // latest arrival
        if ($la_list = $this->getLatestArrivalProduct($where, $option)) {
            if ($la_info_list = $this->price_service->getListingInfoList($la_list, PLATFORM, substr(SITE_LANG, 0, 2), $option)) {
                foreach ($la_info_list as $sku => $result) {
                    if ($result) {
                        $latest_arrival_arr[$sku] = $result;
                    }
                }
            }
        }
        $data["latest_arrival"] = $latest_arrival_arr;

        // best seller
        $where['ll.type'] = 'BS';
        if ($bs_list = $this->getBestSellerProduct($where, $option)) {
            if ($bs_info_list = $this->price_service->getListingInfoList($bs_list, PLATFORM, substr(SITE_LANG, 0, 2), $option)) {
                foreach ($bs_info_list as $sku => $result) {
                    if ($result) {
                        $best_seller_arr[$sku] = $result;
                    }
                }
            }
        }
        $data["best_seller"] = $best_seller_arr;

        return $data;
    }

    public function getLatestArrivalProduct($where, $option)
    {
        return $this->latest_arrivals_service->getLatestArrivalProduct($where, $option);
    }

    public function getBestSellerProduct($where, $option)
    {
        return $this->best_seller_service->getBestSellerProduct($where, $option);
    }
}
