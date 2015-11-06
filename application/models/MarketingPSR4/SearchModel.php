<?php
namespace ESG\Panther\Models\Marketing;

class SearchModel extends \CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/product_search_service');
    }

    public function getProductSearchListForSsLivePrice($platform_id, $sku, $with_rrp)
    {
        return $this->product_search_service->get_product_search_list_for_ss_live_price($platform_id, $sku, $with_rrp);
    }
}

?>