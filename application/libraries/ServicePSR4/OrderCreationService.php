<?php
namespace ESG\Panther\Service;

class OrderCreationService extends BaseService 
{
    protected $checkoutFormData = null;

    public function __construct($formValue) {
        parent::__construct();
        $this->checkoutFormData = $formValue;
    }
/*
    protected function buildCart() {
        $cartSessionService = new CartSessionService(TRUE);
        $skuList = [];
        if ($this->checkoutFormData["soi"]) {
            foreach($this->checkoutFormData["soi"] as $item) {
                if ($item["sku"]) {
                    $unitPrice = $item["price"];
                    $qty = $item["qty"];
                    if (isset($skuList[$item["sku"]])) {
                        $qty += $skuList[$item["sku"]]["qty"];
                        $unitPrice = ($unitPrice + $skuList[$item["sku"]]["unitPrice"]) / 2;
                    }
                    $skuList[$item["sku"]] = ["qty" => $item["qty"], "name" => $item["name"], "unitPrice" => $item["price"]];
                }
            }
            $platformId = $this->checkoutFormData["platformId"];
            $platformBizObj = $this->getService("PlatformBizVar")->getDao("PlatformBizVar")->get(["selling_platform_id" => $platformId]);

            $cartSessionService->manualAddItemsToCart($skuList, $platformId, $platformBizObj, $this->checkoutFormData["delivery_charge"]);
            return $cartSessionService->getCart();
        }
        return false;
    }
*/
}
