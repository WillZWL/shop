<?php
namespace ESG\Panther\Service;

class DisplayQtyService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
    }

    public function calcDisplayQty($cat_id, $website_qty, $price, $currency = "HKD")
    {
        $display_qty = $website_qty;
        if ($website_qty) {
            if ($class_obj = $this->getDisplayQtyClass($price, $currency)) {
                $factor = $class_obj->getDefaultFactor();
                if ($factor_obj = $this->getDao('DisplayQtyFactor')->get(["cat_id" => $cat_id, "class_id" => $class_obj->getId()])) {
                    $factor = $factor_obj->getFactor();
                }
                $display_qty = round((is_null($class_obj->getQty2()) ? $class_obj->getQty() : rand($class_obj->getQty(), $class_obj->getQty2())) * $factor);
            }
        }
        return $display_qty;
    }

    public function getDisplayQtyClass($price, $currency = "HKD")
    {
        if ($currency != "HKD") {
            if ($ex_obj = $this->getService('Exchangerate')->getExchangeRate($currency, "HKD")) {
                $price = $price * $ex_obj->getRate();
            }
        }
        return $this->getDao('DisplayQtyClass')->getList(["price < " => $price], ["orderby" => "price DESC", "limit" => 1]);
    }

}
