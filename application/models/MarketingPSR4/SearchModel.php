<?php
namespace ESG\Panther\Models\Marketing;

use ESG\Panther\Service\ProductSearchService;

class SearchModel extends \CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->productSearchService = new ProductSearchService;
    }

    public function getProductSearchListForSsLivePrice($platformId, $sku, $with_rrp)
    {
        return $this->productSearchService->getProductSearchListForSsLivePrice($platformId, $sku, $with_rrp);
    }
}

?>