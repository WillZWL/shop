<?php

namespace ESG\Panther\Service;

class PriceMarginService extends BaseService
{
    const SCHEDULE_ID= "REFRESH_MARGIN";
    public function refreshProfitAndMargin($platform_id = '', $sku = '')
    {
        if ($sku !== '') {
            $where['p.sku'] = $sku;
        }

        if ($platform_id !== '') {
            $where['pbv.selling_platform_id'] = $platform_id;
        }

        $id = self::SCHEDULE_ID;

        $last_time = $this->getLastTime($id);
        $where["(
                p.modify_on >= '$last_time'
                or fc.modify_on >='$last_time'
                or sp.modify_on >= '$last_time'
                or sper.modify_on >='$last_time'
                or pbv.modify_on >='$last_time'
                or scpv.modify_on >='$last_time'
                or cc.modify_on >= '$last_time'
                or pr.modify_on >= '$last_time'
        )"] = NULL;

        $option = ['limit' => -1];
        $prod_obj_list = $this->getDao('Price')->getPriceWithCost($where, $option);
        foreach ($prod_obj_list as $prod_obj) {
            $sku = $prod_obj->getSku();
            $platform_id = $prod_obj->getPlatformId();
            $price_obj = $this->getDao('Price')->get(['sku' => $sku, 'platform_id' => $platform_id]);

            if ($price_obj) {
                $price_margin_obj = $this->getDao('PriceMargin')->get(['sku' => $sku, 'platform_id' => $platform_id]);
                $action = 'update';

                if (!$price_margin_obj) {
                    $price_margin_obj = new \PriceMarginVo();
                    $action = 'insert';
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

                $this->getDao('PriceMargin')->$action($price_margin_obj);
            }
        }
        unset($prod_obj_list);
    }

    private function getLastTime($id)
    {
        if ($obj = $this->getDao('ScheduleJob')->get(["schedule_job_id" => $id, "status" => 1])) {
            return $obj->getLastAccessTime();
        }
    }

    public function updatLastTime($id, $current_time)
    {
        if ($obj = $this->getDao('ScheduleJob')->get(["schedule_job_id" => $id, "status" => 1])) {
            $obj->setLastAccessTime($current_time);
            return $this->getDao('ScheduleJob')->update($obj);
        }
    }
}
