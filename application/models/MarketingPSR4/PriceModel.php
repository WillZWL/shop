<?php
namespace ESG\Panther\Models\Marketing;

use ESG\Panther\Service\PriceService;

class PriceModel extends \CI_Model
{
    public $priceService;

    public function __construct()
    {
        parent::__construct();
        $this->priceService = new PriceService;
    }

    public function getProductInfo($sku = "", $platform = "", $lang_id = "en", $option = [])
    {
        return $this->PriceService->getProductInfo($sku, $platform, $lang_id, $option);
    }
}
