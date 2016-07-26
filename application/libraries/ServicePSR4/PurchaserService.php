<?php
namespace ESG\Panther\Service;

class PurchaserService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getProdStProfit($where)
    {
        if ($objlist = $this->getService('Shiptype')->getDao('Shiptype')->getProductShiptype($where)) {
            $data["low_profit"] = $data["max_cost"] = [];
            foreach ($objlist as $obj) {
                $priceWithCost = new \PriceWithCostDto();
                set_value($priceWithCost, $obj);
                // $priceWithCost->setPrice($obj->getPrice());
                $priceWithCost->setPlatformCountryId($obj->getPlatformCountryId());

                $this->getService('Price')->calculateProfitAndMargin($priceWithCost);

                $obj->setMargin($priceWithCost->getMargin());
                $obj->setPrice($priceWithCost->getPrice());
                $obj->setPlatformId($priceWithCost->getPlatformId());
                if (empty($data["low_profit"])) {
                    $obj->setProfit($priceWithCost->getProfit());
                    $data["low_profit"] = $obj;
                } elseif ($obj->getProfit() < $priceWithCost->getProfit()) {
                    $obj->setProfit($priceWithCost->getProfit());
                    $data["low_profit"] = $obj;
                }
            }

            return $data;
        }

        return FALSE;
    }
}