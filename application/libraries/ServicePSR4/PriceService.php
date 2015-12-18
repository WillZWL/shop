<?php

namespace ESG\Panther\Service;

class PriceService extends BaseService
{
    public function createNewPrice($sku, $obj)
    {
        if (!$this->getDao('Product')->get(['sku' => $sku])) {
            return false;
        }

        $newObj = new \PriceVo();

        $newObj->setSku($sku);
        $newObj->setPlatformId((string) $obj->platform_id);
        $this->updatePrice($newObj, $obj);

        return $newObj;
    }

    public function updatePrice($newObj, $obj)
    {
        $newObj->setDefaultShiptype((string) $obj->default_shiptype);
        $newObj->setSalesQty((string) $obj->sales_qty);
        $newObj->setPrice($obj->required_selling_price);
        $newObj->setVbPrice((string) $obj->prod_price);
        $newObj->setStatus((string) $obj->status);
        $newObj->setAllowExpress((string) $obj->allow_express);
        $newObj->setIsAdvertised((string) $obj->is_advertised);
        $newObj->setGooglePromoId((string) $obj->google_promo_id);
        $newObj->setExtMappingCode((string) $obj->ext_mapping_code);
        $newObj->setLatency((string) $obj->latency);
        $newObj->setOosLatency((int) $obj->oos_letency);
        $newObj->setListingStatus((string) $obj->listing_status);
        $newObj->setPlatformCode((string) $obj->platform_code);
        $newObj->setMaxOrderQty((string) $obj->max_order_qty);
        $newObj->setAutoPrice((string) $obj->auto_price);
        $newObj->setFixedRrp((string) $obj->fixed_rrp);
        $newObj->setRrpFactor((string) $obj->rrp_factor);
        $newObj->setDeliveryScenarioid((string) $obj->delivery_scenarioid);
    }

    public function calcWebsiteProductRrp($price = 0, $fixed_rrp = 'Y', $rrp_factor = 1.18)
    {
        if ($price > 0) {
            if ($fixed_rrp == 'Y') {
                $markup = $price * 1.18;
            } else {
                if ($rrp_factor < 10) {
                    $markup = $price * $rrp_factor;
                } else {
                    return number_format($rrp_factor, 2, '.', '');
                }
            }

            $remainder = fmod($markup, 5);
            $add_to = 5 - $remainder;
            $rrp = number_format($markup - (-$add_to) - .01, 2, '.', '');

            return number_format($rrp, 2, '.', '');
        }

        return 0;
    }

    public function getListingInfoList($sku_arr = [], $platform_id = '', $lang_id = 'en', $option = [])
    {
        set_time_limit(600);
        ini_set('memory_limit', '500M');

        if (empty($sku_arr)) {
            return false;
        } else {
            foreach ($sku_arr as $obj) {
                $sku_list[$obj->getSku()] = '';
            }
        }

        if ($result = $this->getDao('Price')->getListingInfo($sku_list, $platform_id, $lang_id, $option)) {
            $category_table = $this->getService('Category')->getCategoryName();
            if (is_array($result)) {
                foreach ($result as $obj) {
                    $prod_url = base_url().$category_table[$obj->getCatId()].'/'.$category_table[$obj->getSubCatId()].'/'.str_replace(' ', '-', parse_url_char($obj->getProdName())).'/product/'.$obj->getSku();
                    $obj->setProductUrl($prod_url);
                    $obj->setPrice(random_markup($obj->getPrice()));
                    $obj->setRrpPrice(random_markup($this->calcWebsiteProductRrp($obj->getPrice(), $obj->getFixedRrp(), $obj->getRrpFactor())));
                }
                $rs = $result;

                foreach ($rs as $rs_obj) {
                    $sku_list[$rs_obj->getSku()] = $rs_obj;
                }
            } else {
                $result->setPrice(random_markup($result->getPrice()));
                $sku_list[$result->getSku()] = $result;
            }
        }

        return $sku_list;
    }

    public function getProfitMarginJson($platform_id, $sku, $required_selling_price = 0)
    {
        $dto = $this->getDao('Price')->getPriceWithCost(['p.sku' => $sku, 'pbv.selling_platform_id' => $platform_id], ['limit' => 1]);

        if ( ! $dto) {
            return json_encode(['error' => "can't get PriceWithCostDto"]);
        }

        if ($required_selling_price != 0) {
            // calculate profit using assumed selling price.
            $dto->setPrice($required_selling_price);
        } else {
            // calculate cost base on auto price.
            $this->calculateAutoPrice($dto);
        }

        $this->calculateProfitAndMargin($dto);

        $data_arr = [
            "local_sku" => $sku,
            "based_on" => $required_selling_price,
            "get_delivery_cost" => $dto->getDeliveryCost(),
            "get_declared_value" => $dto->getDeclaredValue(),
            "get_vat_percent" => $dto->getVatPercent(),
            "get_vat" => $dto->getVat(),
            "get_sales_commission" => $dto->getPlatformCommission(),
            "get_duty_pcent" => $dto->getDutyPcent(),
            "get_duty" => $dto->getDuty(),
            "get_payment_charge_percent" => $dto->getPaymentChargePercent(),
            "get_payment_charge" => $dto->getPaymentCharge(),
            "get_forex_fee_percent" => $dto->getForexFeePercent(),
            "get_forex_fee" => $dto->getForexFee(),
            "get_listing_fee" => $dto->getListingFee(),
            "get_logistic_cost" => $dto->getLogisticCost(),
            "get_supplier_cost" => $dto->getSupplierCost(),
            "get_complementary_acc_cost" => $dto->getComplementaryAccCost(),
            "get_price" => $dto->getPrice(),
            "get_cost" => $dto->getCost(),
            "get_profit" => $dto->getProfit(),
            "get_margin" => $dto->getMargin()
        ];

        return json_encode($data_arr);
    }

    public function calculateProfitAndMargin(\PriceWithCostDto $dto)
    {
        $this->calculateCost($dto);

        $total_cost = $dto->getCost();
        $profit = $dto->getPrice() - $total_cost;

        if ($dto->getPrice() > 0) {
            $margin = $profit / $dto->getPrice() * 100;
        } else {
            $margin = 0;
        }

        $dto->setProfit(number_format($profit, 2, '.', ''));
        $dto->setMargin(number_format($margin, 2, '.', ''));
    }

    public function calculateCost(\PriceWithCostDto $dto)
    {
        $this->getService('PlatformBizVar')->calculateDeclaredValue($dto);

        $this->getService('FreightCharge')->calculateLogisticCost($dto);
        $this->getService('SubCatPlatformVar')->calculatePlatformCommission($dto);
        $this->getService('PlatformBizVar')->calculatePaymentCharge($dto);
        $this->getService('PlatformBizVar')->calculateForexFee($dto);
        $this->getService('PlatformBizVar')->calculateVat($dto);
        $this->getService('ComplementaryAcc')->calculateComplementaryAccCost($dto);
        $this->getService('ProductCustomClassification')->calculateDuty($dto);

        $logistic_cost = $dto->getLogisticCost();
        $platform_commission = $dto->getPlatformCommission();
        $payment_charge = $dto->getPaymentCharge();
        $forex_fee = $dto->getForexFee();
        $vat = $dto->getVat();
        $complementary_acc_cost = $dto->getComplementaryAccCost();
        $duty = $dto->getDuty();

        $supplier_cost = $dto->getSupplierCost();
        $listing_fee = $dto->getListingFee();

        $total_cost = $supplier_cost + $logistic_cost + $listing_fee + $platform_commission + $payment_charge + $forex_fee + $vat + $duty + $complementary_acc_cost;

        $dto->setCost($total_cost);
    }

    public function calculateAutoPrice(\PriceWithCostDto $dto)
    {
        $required_margin = $dto->getSubCatMargin();

        $dto->setPrice(0);
        $this->calculateCost($dto);

        do {
            $auto_price = ($dto->getCost()) / (1 - $required_margin / 100);
            $auto_price = number_format($auto_price, 2, '.', '');
            $dto->setPrice($auto_price);
            $this->calculateProfitAndMargin($dto);
        } while (($dto->getMargin()  - $required_margin) < 0);

        // calculate cost base on finally auto price.
        $this->calculateProfitAndMargin($dto);
    }

    public function getPricingToolInfo($platform_list = "", $sku = "")
    {
        $ret = [];
        if ($tmp_objlist = $this->getDao('Price')->getPriceWithCost(['p.sku'=>$sku, "pbv.selling_platform_id in ({$platform_list})"=>null], ['limit'=>-1])) {
            foreach ($tmp_objlist  as $tmp_obj) {
                $this->initPrice($tmp_obj);
                $this->calculateProfitAndMargin($tmp_obj);

                $ret[$tmp_obj->getPlatformId()]["dst"] = $tmp_obj;
            }
        }

        return $ret;
    }

    public function initPrice(\PriceWithCostDto $dto)
    {
        $default_obj = $this->getDao('Price')->getDefaultConvertedPrice(
                                    ["pr.sku" => $dto->getSku(), "pbv.selling_platform_id" => $dto->getPlatformId()],
                                    ['limit' => 1]
                                );

        $default_price = $default_obj ? $default_obj->getDefaultPlatformConvertedPrice() : 0;

        if ($dto->getPrice() > 0) {
            $dto->setCheckPrice(1);
        } else {
            $dto->setPrice($default_price);
            $dto->setCheckPrice(0);
        }

        $dto->setDefaultPlatformConvertedPrice($default_price);
    }
}
