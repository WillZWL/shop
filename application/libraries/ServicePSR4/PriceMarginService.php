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

                $price = $price_obj->getPrice();
                $prod_obj->setPrice($price);
                $this->calculateDeclaredValue($prod_obj);
                $this->calcVat($prod_obj);
                $this->calcDeliveryCharge($prod_obj);
                $this->calcLogisticCost($prod_obj);
                $this->calcPaymentCharge($prod_obj);
                $this->calcForexFee($prod_obj);
                $this->calcDuty($prod_obj);
                $this->calcComplementaryAccCost($prod_obj);

                $vat = $prod_obj->getVat();
                $logistic_cost = $prod_obj->getLogisticCost();
                $supplier_cost = $prod_obj->getSupplierCost();
                $payment_charge_cost = $prod_obj->getPaymentCharge();
                $listing_fee = $prod_obj->getListingFee();
                $duty_cost = $prod_obj->getDuty();
                $forex_fee = $prod_obj->getForexFee();
                $complementary_acc_cost = $prod_obj->getComplementaryAccCost();

                $total_cost = $vat + $logistic_cost + $supplier_cost + $payment_charge_cost + $listing_fee + $duty_cost + $forex_fee + $complementary_acc_cost;
                $profit = $price - $total_cost;
                $margin = $profit / $price * 100;

                $price_margin_obj->setSku($sku);
                $price_margin_obj->setPlatformId($platform_id);
                $price_margin_obj->setSellingPrice($price);
                $price_margin_obj->setVat($vat);
                $price_margin_obj->setLogisticCost($logistic_cost);
                $price_margin_obj->setSupplierCost($supplier_cost);
                $price_margin_obj->setPaymentCharge($payment_charge_cost);
                $price_margin_obj->setListingFee($listing_fee);
                $price_margin_obj->setDuty($duty_cost);
                $price_margin_obj->setForexFee($forex_fee);
                $price_margin_obj->setTotalCost($total_cost);
                $price_margin_obj->setProfit($profit);
                $price_margin_obj->setMargin($margin);

                $this->getDao('PriceMargin')->update($price_margin_obj);
            }
        }
    }


    public function refreshMarginForTopDeal()
    {
        if ($obj_list = $this->platformBizVarService->getDao('SellingPlatform')->getList(["status" => 1])) {
            foreach ($obj_list as $obj) {
                $this->refreshMargin($obj->getSellingPlatformId());
            }
        }
    }

    public function refreshMargin($platform = 'WEBHK')
    {
        $prod_list = $this->productService->getListedProductList($platform, 'ProductCostDto');
        $this->updateMargin($prod_list, $platform);
    }

    public function updateMargin($prod_list, $platform = 'WEBHK')
    {
        if ($platform == "") {
            return FALSE;
        }

        $pr_svc = $this->classFactoryService->getPlatformPriceService($platform);

        $sample_vo = $this->getDao('PriceMargin')->get();
        foreach ($prod_list as $prod) {
            $margin_vo = clone $sample_vo;

            $prod->setPrice($pr_svc->getPrice($prod));
            $pr_svc->calcLogisticCost($prod);
            $pr_svc->calculateProfit($prod);
            set_value($margin_vo, $prod);
            $this->getDao('PriceMargin')->replace($margin_vo);
        }

        unset($pr_svc);
        unset($p_svc);
    }

    public function refreshAllPlatformMargin($platform_where = [], $skulist = "")
    {
        $ret = [];
        $ret["status"] = FALSE;
        $platform_where["status"] = 1;

        if ($sp_list = $this->platformBizVarService->getDao('SellingPlatform')->getList($platform_where)) {
            $updatelist = "";
            foreach ($sp_list as $key => $sellingplatform_obj) {

                set_time_limit(600);
                ini_set("memory_limit", "500M");

                $platform_id = $sellingplatform_obj->getSellingPlatformId();
                $updatelist .= $platform_id . ",\n";
                if ($skulist == "") {
                    echo "<br>Updating price_margin $platform_id,";
                    $this->refreshMargin($platform_id);
                } else {
                    $listedprod = $this->productService->getProductWPriceInfo($platform_id, $skulist, 'ProductCostDto');
                    if (count($listedprod) > 0) {
                        echo "<br>Updating price_margin $platform_id $skulist,";
                        $this->updateMargin($listedprod, $platform_id);
                    }
                }
            }

            $ts = date("Y-m-d H:i:s");
            $ret["status"] = TRUE;
            $ret["updatelist"] = $updatelist;
            return $ret;
        } else {
            $ret["error_message"] = __LINE__ . "price_margin_service. Unable to retrieve sellling platform list. DB error ";
        }

        return $ret;
    }

    public function refresh_margin2($platform = 'WEBHK')
    {
        $prod_list = $this->productService->getListedProductList($platform, 'ProductCostDto');
        $this->updateMargin2($prod_list, $platform);
    }

    public function updateMargin2($prod_list, $platform = 'WEBHK')
    {
        if ($platform == "") {
            $platform = "WEBHK";
        }

        $pf_var = $this->platformBizVarService->getPlatformBizVar($platform);

        $shiptype = 1;

        if ($pf_var) {
            $shiptype = $pf_var->getDefaultShiptype();
        }


        $price_srv = $this->classFactoryService->getPriceService($platform);
        $sample_vo = $this->getDao('PriceMargin')->get();

        foreach ($prod_list as $prod) {
            $margin_vo = clone $sample_vo;
            $prod->setShiptype($shiptype);
            $prod->setPrice($price_srv->getPrice($prod->getSku()));

            $price_srv->calcProfit($prod);
            set_value($margin_vo, $prod);

            $this->getDao('PriceMargin')->replace($margin_vo);
            if ($prod->getSku() == '10051-NA') {
                var_dump($this->getDao('PriceMargin')->db->last_query());
                var_dump($prod);
                exit;
            }

        }
    }

    public function refreshMarginAmazon($platform = 'AMUS')
    {
        $prod_list = $this->productService->getListedProductList($platform, 'ProductCostDto');
        $this->_updateMarginAmazon($prod_list, $platform);
    }

    public function _updateMarginAmazon($prod_list, $platform = 'AMUS')
    {
        if ($platform == "") {
            return FALSE;
        }

        $pr_svc = $this->classFactoryService->getPlatformPriceService($platform);

        $sample_vo = $this->getDao('PriceMargin')->get();
        foreach ($prod_list as $prod) {
            $p_srv = $pr_svc->getPriceServiceFromDto($prod);
            $p_srv->setPlatformId($prod->getPlatformId());
            $p_srv->setPlatformCurrId($prod->getPlatformCurrencyId());

            // get fulfillment centre id for amazon
            $price_ext_obj = $pr_svc->get_Price_ext_dao()->get(["sku" => $prod->getSku(), "platform_id" => $prod->getPlatformId()]);
            if (!$price_ext_obj || !$fc_id = $price_ext_obj->getFulfillmentCentreId()) {
                $fc_id = "DEFAULT";
            }
            $p_srv->setFulfillmentCentreId($fc_id);

            $margin_vo = clone $sample_vo;

            $prod->setPrice($pr_svc->getPrice($prod));
            $pr_svc->calcFreightCost($prod, $p_srv, $prod->getPlatformCurrencyId());
            $pr_svc->calculateProfit($prod);
            set_value($margin_vo, $prod);

            $this->getDao('PriceMargin')->replace($margin_vo);
        }
        unset($pr_svc);
        unset($p_svc);
    }

    public function refresh_latest_margin($where = [])
    {
        $prod_list = $this->productService->getProductWMarginReqUpdate($where, 'ProductCostDto');
        $this->updateMargin($prod_list, $where["v_prod_overview_w_update_time.platform_id"]);
    }

    /*public function getPriceService()
    {
        return $this->price_service;
    }

    public function setPriceService($svc)
    {
        $this->price_service = $svc;
        return $this;
    }*/

    public function insertOrUpdateMargin($sku, $platform_id, $price = null, $profit, $margin)
    {
        if ($price_margin_obj = $this->getDao('PriceMargin')->get(['sku' => $sku, 'platform_id' => $platform_id])) {
            if (!$temp_price_margin_obj = $this->getDao('PriceMargin')->get(['sku' => $sku, 'platform_id' => $platform_id, 'profit' => $profit, 'margin' => $margin])) {
                $price_margin_obj->setProfit($profit);
                $price_margin_obj->setSellingPrice($price);
                $price_margin_obj->setMargin($margin);
                $this->getDao('PriceMargin')->update($price_margin_obj);
            }
        } else {
            $price_margin_obj = $this->getDao('PriceMargin')->get();
            $price_margin_obj->setSku($sku);
            $price_margin_obj->setPlatformId($platform_id);
            $price_margin_obj->setSellingPrice($price);
            $price_margin_obj->setProfit($profit);
            $price_margin_obj->setMargin($margin);
            $this->getDao('PriceMargin')->insert($price_margin_obj);
        }
    }

    public function getCrossSellProduct($prod_info, $platform_id, $language_id, $price, $price_adjustment)
    {
        return $this->getDao('PriceMargin')->getCrossSellProduct($prod_info, $platform_id, $language_id, $price, $price_adjustment);
    }
}


