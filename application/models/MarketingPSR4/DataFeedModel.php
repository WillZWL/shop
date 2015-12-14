<?php
namespace ESG\Panther\Models\Marketing;
use ESG\Panther\Service\SearchspringProductFeedService;
use ESG\Panther\Service\SellingPlatformService;
use ESG\Panther\Service\AdminProductFeedService;

class DataFeedModel extends \CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAllActivePlatform()
    {
        $sellingPlatformService = new SellingPlatformService();
        return $sellingPlatformService->getDao()->getList(["status" => 1, "type" => "WEBSITE"], ["limit" => -1]);
    }

    public function genSearchspringProductFeed($platform_id)
    {
        $searchSpringService = new SearchspringProductFeedService();
        $searchSpringService->genDataFeed($platform_id);
    }

    public function genAdminProductFeed($platform_type)
    {
        $adminProductFeedService = new AdminProductFeedService();
        return $adminProductFeedService->genDataFeed($platform_type);
    }
}

?>