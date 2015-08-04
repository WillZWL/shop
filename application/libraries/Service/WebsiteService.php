<?php
namespace AtomV2\Service;

class WebsiteService extends BaseService
{
    private $price_service;

    public function __construct()
    {
        // $this->setDao(new SiteConfigDao);
        $this->price_service = new PriceService;
        $this->latest_arrivals_service = new LatestArrivalsService;
    }

    public function getHomeContent($lang_id = 'en')
    {
        $grid_display_limit = 6;
        $option = ['limit' => $grid_display_limit];
        $where = ['ll.platform_id' => PLATFORM];

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
        // if ($bs_list = $this->get_best_seller_grid_info(PLATFORM)) {
        //     if ($bs_info_list = $this->price_service->get_listing_info_list($bs_list, PLATFORM, substr(SITE_LANG, 0, 2), $option)) {
        //         foreach ($bs_info_list as $sku => $result) {
        //             if ($result) {
        //                 $best_seller_arr[$sku] = $result;
        //             }
        //         }
        //     }
        // }

        // use latest arrival list as backup if best seller has no results
        // if (count($best_seller_arr) < $grid_display_limit && count($latest_arrival_arr) > 0) {
        //     foreach ($latest_arrival_arr as $sku => $result) {
        //         if ($result) {
        //             $best_seller_arr[$sku] = $result;
        //         }
        //         if (count($best_seller_arr) === $grid_display_limit) {
        //             break;
        //         }
        //     }
        // }
        // $data["best_seller"] = $best_seller_arr;

        return $data;
    }

    public function getLatestArrivalProduct($where, $option)
    {
        return $this->latest_arrivals_service->getLatestArrivalProduct($where, $option);
    }

    // public function get_best_seller_grid_info($platform = "")
    // {
    //     return $this->best_seller_service->get_home_best_seller_grid_info($platform);
    // }
}
