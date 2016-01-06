<?php

namespace ESG\Panther\Service;

class PriceMarginService extends BaseService
{
    public function refreshProfitAndMargin($platform_id = '', $sku = '')
    {
        if ($sku !== '') {
            $where['p.sku'] = $sku;
        }

        if ($platform_id !== '') {
            $where['pbv.selling_platform_id'] = $platform_id;
        }

        $option = ['limit' => -1];
        $prod_obj_list = $this->getDao('Price')->getPriceWithCost($where, $option);

        foreach ($prod_obj_list as $prod_obj) {
            $sku = $prod_obj->getSku();
            $platform_id = $prod_obj->getPlatformId();
            $price_obj = $this->getDao('Price')->get(['sku' => $sku, 'platform_id' => $platform_id]);

            if ($price_obj) {
                $price_margin_obj = $this->getDao('PriceMargin')->get(['sku' => $sku, 'platform_id' => $platform_id]);
                if (!$price_margin_obj) {
                    $price_margin_obj = new \PriceMarginVo();
                }

                $profitMarginJson = json_decode($this->getService('Price')->getProfitMarginJson($platform_id, $sku, $price_obj->getPrice()));

                if (isset($require_price_json->error)) {
                    continue;
                }

                $price_margin_obj->setSku($sku);
                $price_margin_obj->setPlatformId($platform_id);
                $price_margin_obj->setSellingPrice($profitMarginJson->get_price);
                $price_margin_obj->setVat($profitMarginJson->get_vat);
                $price_margin_obj->setLogisticCost($profitMarginJson->get_logistic_cost);
                $price_margin_obj->setSupplierCost($profitMarginJson->get_supplier_cost);
                $price_margin_obj->setPaymentCharge($profitMarginJson->get_payment_charge);
                $price_margin_obj->setListingFee($profitMarginJson->get_listing_fee);
                $price_margin_obj->setDuty($profitMarginJson->get_duty);
                $price_margin_obj->setForexFee($profitMarginJson->get_forex_fee);
                $price_margin_obj->setTotalCost($profitMarginJson->get_cost);
                $price_margin_obj->setProfit($profitMarginJson->get_profit);
                $price_margin_obj->setMargin($profitMarginJson->get_margin);

                $this->getDao('PriceMargin')->update($price_margin_obj);
            }
        }
    }
}
