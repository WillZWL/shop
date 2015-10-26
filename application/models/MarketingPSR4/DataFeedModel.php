<?php
namespace ESG\Panther\Models\Marketing;
use ESG\Panther\Service\SearchspringProductFeedService;

class DataFeedModel extends \CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function genSearchspringProductFeed($platform_id)
    {
        $searchSpringService = new SearchspringProductFeedService();
        $searchSpringService->genDataFeed($platform_id);
    }
}

?>