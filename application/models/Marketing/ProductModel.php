<?php
namespace AtomV2\Models\Marketing;

use AtomV2\Service\PriceService;

class ProductModel extends \CI_Model
{
	public $priceService;

    public function __construct()
    {
        parent::__construct();
        $this->priceService = new PriceService;
    }

    public function getListingInfo($sku = "", $platform = "", $lang_id = "en", $option = [])
    {
        return $this->price_service->getListingInfo($sku, $platform, $lang_id, $option);
    }
}
