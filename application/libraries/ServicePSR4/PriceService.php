<?php

namespace ESG\Panther\Service;

class PriceService extends BaseService
{
    private $dto;

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

    public function refreshMargin($platform_id = '', $sku = '')
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

    public function getTrailCalcuMargin($platform_id, $sku, $price)
    {
        $where['p.sku'] = $sku;
        $where['pbv.selling_platform_id'] = $platform_id;
        $option['limit'] = 1;
        $prod_obj = $this->getDao('Price')->getPriceWithCost($where, $option);

        if ($prod_obj) {
            $prod_obj->setPrice($price);
            $this->calculateDeclaredValue($prod_obj);
            $this->calcVat($prod_obj);
            $this->calcDeliveryCharge($prod_obj);
            $this->calcLogisticCost($prod_obj);
            $this->calcPaymentCharge($prod_obj);
            $this->calcForexFee($prod_obj);
            $this->calcDuty($prod_obj);

            $vat = $prod_obj->getVat();
            $logistic_cost = $prod_obj->getLogisticCost();
            $supplier_cost = $prod_obj->getSupplierCost();
            $payment_charge_cost = $prod_obj->getPaymentCharge();
            $listing_fee = $prod_obj->getListingFee();
            $duty_cost = $prod_obj->getDuty();
            $forex_fee = $prod_obj->getForexFee();

            $total_cost = $vat + $logistic_cost + $supplier_cost + $payment_charge_cost + $listing_fee + $duty_cost + $forex_fee;
            $profit = $price - $total_cost;
            $margin = $profit / $price;
        } else {
            $margin = -1;
        }
        return $margin;
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

    public function getProfitMarginJson($platform_id, $sku, $required_selling_price, $required_cost_price = -1)
    {
        $dto = $this->getDao('Price')->getPriceWithCost(['p.sku' => $sku, 'pbv.selling_platform_id' => $platform_id], ['limit' => 1]);

        if ( ! $dto) {
            return json_encode(['error' => "can't get PriceWithCostDto"]);
        }

        $this->calculateCost($dto);

        // $this->performBusinessLogic($dto, $required_selling_price, $required_cost_price);

        $data_arr = [
            "local_sku" => $sku,
            "based_on" => $required_selling_price,
            "get_margin" => $dto->getMargin(),
            "get_price" => $dto->getPrice(),
            "get_delivery_cost" => $dto->getDeliveryCost(),
            "get_declared_value" => number_format($dto->getDeclaredValue(), 2, ".", ""),
            "get_vat_percent" => $dto->getVatPercent(),
            "get_vat" => $dto->getVat(),
            "get_sales_commission" => $dto->getSalesCommission(),
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
            "get_cost" => $dto->getCost(),
            "get_price" => number_format($dto->getPrice(), 2, ".", ""),
            "get_profit" => number_format($dto->getPrice(), 2, ".", "") - $dto->getCost()

        ];

        return json_encode($data_arr);
    }

    public function calculateCost(PriceWithCostDto $dto)
    {
        $this->calculateDeclaredValue($dto);

        $this->calculateLogisticCost($dto);
        $this->calculateVatCost($dto);
        $this->calculateDuty($dto);
        $this->calculateListingCost($dto);
    }

    public function calculateLogisticCost($value='')
    {
        if ($lc = $this->getDao('FreightCatCharge')->calcLogisticCost($dto->getPlatformId(), $dto->getSku())) {
            $dto->setLogisticCost($lc['converted_amount']);
        } else {
            $dto->setLogisticCost(0);
        }
    }

    public function calculateDeclaredValue(PriceWithCostDto $dto)
    {
        $price = $dto->getPrice();
        $country_id = $dto->getPlatformCountryId();

        switch ($country_id) {
            case "AU":
                $declared_value = min($price, 950);
                break;

            case "NZ":
                $declared_value = ($price < 350) ? $price : $price * 80 / 100;
                break;

            default:
                $declared_value = $price * 10 / 100;
                break;
        }

        $dto->setDeclaredValue($declared_value);
    }

    private function performBusinessLogic($dto, $required_selling_price = -1, $required_cost_price = -1)
    {
        $this->calcComplementaryAccCost($dto);
        $this->calcLogisticCost($dto);

        if ($required_cost_price != -1) {
            $dto->setSupplierCost($required_cost_price);
        }

        $required_margin = -1;

        if ($required_selling_price <= 0) {
            $required_margin = $dto->getSubCatMargin();
        }

        if ($required_margin >= 0) {
            $this->calcAutoPriceByRequiredMargin($dto, $required_margin);
        } else {
            $dto->setPrice($required_selling_price);
            $this->calcMarginByPrice($dto);
        }
    }

    public function calcAutoPriceByRequiredMargin($dto, $required_margin)
    {
        $margin = 0;
        $total_cost = $this->getTotalCost($dto);

        if ($required_margin == 0 || $total_cost == 0) {
            if ($total_cost > 0) {
                $margin = $required_margin;
            } else {
                $margin = 0;
            }

            $price = $total_cost;
            $profit = 0;
        } else {
            for (; ;) {
                $price = ($total_cost) / (1 - $required_margin / 100) + 0.01;
                $dto->setPrice($price);
                $total_cost = $this->getTotalCost($dto);

                $total_cost = $total_cost;
                $profit = $dto->getPrice() - $total_cost;
                $margin = $profit / $dto->getPrice() * 100;

                if ( ($margin - $required_margin) >= 0 ) {
                    break;
                }
            }
        }
        $total_cost = number_format($total_cost, 2, ".", "");
        $profit = $dto->getPrice() - $total_cost;
        $margin = $profit / $dto->getPrice() * 100;

        $dto->setPrice($price);
        $dto->setCost($total_cost);
        $dto->setProfit(number_format($profit, 2, ".", ""));
        $dto->setMargin(number_format($margin, 2, ".", ""));
    }

    public function calcMarginByPrice($dto)
    {
        $total_cost = $this->getTotalCost($dto);

        $total_cost = number_format($total_cost, 2, ".", "");
        $dto->setCost($total_cost);

        $profit = $dto->getPrice() - $total_cost;

        $margin = ($dto->getPrice() > 0) ? ($profit / $dto->getPrice() * 100) : 0;

        $dto->setProfit(number_format($profit, 2, ".", ""));
        $dto->setMargin(number_format($margin, 2, ".", ""));
    }

    public function getTotalCost($dto)
    {
        $this->calculateDeclaredValue($dto);
        $this->calcCommission($dto);
        $this->calcDuty($dto);
        $this->calcPaymentCharge($dto);
        $this->calcForexFee($dto);
        $this->calcVat($dto);
        $this->calcAutoPriceValue($dto);

        $supplier_cost = $dto->getSupplierCost();
        $logistic_cost = $dto->getLogisticCost();
        $listing_fee = $dto->getListingFee();
        $Sales_commission_cost = $dto->getSalesCommission();
        $payment_charge_cost = $dto->getPaymentCharge();
        $forex_fee = $dto->getForexFee();
        $vat_cost = $dto->getVat();
        $duty_cost = $dto->getDuty();
        $complementary_acc_cost = $dto->getComplementaryAccCost();

        $total_cost = $supplier_cost + $logistic_cost + $listing_fee + $Sales_commission_cost + $payment_charge_cost + $forex_fee + $vat_cost + $duty_cost + $complementary_acc_cost;

        return $total_cost;
    }

    public function calcComplementaryAccCost(&$dto)
    {
        $total_cost = 0;

        $where["pca.dest_country_id"] = $dto->getPlatformCountryId();
        $where["pca.mainprod_sku"] = $dto->getSku();
        $where["pca.status"] = 1;

        if ($mapped_ca_list = $this->getDao('ProductComplementaryAcc')->getMappedAccListWithName($where)) {
            $sku_arr = [];
            foreach ($mapped_ca_list as $caobj) {
                $sku_arr[] = $caobj->getAccessorySku();
            }
            $sku_list = "'". implode("','", $sku_arr) . "'";
            if ($cadto = $this->getDao('Price')->getPriceWithCost(
                                    [
                                        "p.sku in ({$sku_list})"=>null,
                                        'pbv.selling_platform_id'=>$dto->getPlatformId()
                                    ], [
                                        'sum_complementary_cost'=>1,
                                        'limit'=>1
                                    ])
            ) {
                $total_cost = $cadto->getSupplierCost();
            }
        }
        $dto->setComplementaryAccCost($total_cost);
    }

    public function getPricingToolInfo($platform_list = "", $sku = "")
    {
        $ret = [];
        if ($tmp_objlist = $this->getDao('Price')->getPriceWithCost(['p.sku'=>$sku, "pbv.selling_platform_id in ({$platform_list})"=>null], ['limit'=>-1])) {
            foreach ($tmp_objlist  as $tmp_obj) {

                $this->calculateProfit($tmp_obj);
                $ret[$tmp_obj->getPlatformId()]["dst"] = $tmp_obj;

            }
        }

        return $ret;
    }

    public function calculateProfit($dto)
    {
        $this->calcDtoPrice($dto);
        $this->calcDeclaredPcent($dto);

        $this->performBusinessLogic($dto, $dto->getPrice());
        return;
    }
    public function calculateDeclaredValue($dto = "", $price = "")
    {
        $price = $price ? $price : $dto->getPrice();
        $country_id = $dto->getPlatformCountryId();
        $declared_value = $this->getService('So')->getDeclaredValue($dto, $country_id, $price);
        $dto->setDeclaredValue($declared_value);

        return $declared_value;
    }

    public function calcLogisticCost($dto)
    {
        if ($lc = $this->getDao('FreightCatCharge')->calcLogisticCost($dto->getPlatformId(), $dto->getSku())) {
            $dto->setLogisticCost($lc['converted_amount']);
        } else {
            $dto->setLogisticCost(0);
        }
    }

    public function calcDtoPrice($dto)
    {

        $default_price = $this->getDefaultPrice($dto);
        if ($dto->getPrice() > 0) {
            $dto->setCurrentPlatformPrice($dto->getPrice());
        } else {
            $dto->setPrice($default_price);
            $dto->setCurrentPlatformPrice(null);
        }

        $dto->setDefaultPlatformConvertedPrice($default_price);
    }

    public function getDefaultPrice($dto)
    {
        $default_price = 0;
        if ($default_obj = $this->getDao('Price')->getDefaultConvertedPrice(["pr.sku" => $dto->getSku(), "pbv.selling_platform_id" => $dto->getPlatformId()], ['limit'=> 1])) {
            $default_price = $default_obj->getDefaultPlatformConvertedPrice();
        }

        return $default_price;
    }

    public function calcDeliveryCharge($dto)
    {

        if ($this->getDao('ProductType')->getNumRows(["sku" => $dto->getSku(), "type_id" => "VIRTUAL"])) {
            $delivery_charge = '0.00';
        } else {
            $type = $this->getDao('Config')->valueOf("default_delivery_type");
            $delivery_charge = $this->getDao('WeightCatCharge')->getCountryWeightChargeByPlatform($dto->getPlatformId(), $dto->getProdWeight(), $type);
        }
        $dto->setDefaultDeliveryCharge($delivery_charge);
        $fdl = $dto->getFreeDeliveryLimit();
        if ($dto->getPrice() > $fdl * 1) {
            $dto->setDeliveryCharge('0.00');
        } else {
            $dto->setDeliveryCharge($delivery_charge);
        }
    }

    public function calcCommission($dto)
    {
        $dto->setSalesCommission(number_format($dto->getPrice() * $dto->getPlatformCommission() / 100, 2, ".", ""));
    }

    public function calcDuty($dto)
    {
        $duty = number_format($dto->getDeclaredValue() * $dto->getDutyPcent() / 100, 2, ".", "");
        $dto->setDuty($duty);
    }

    public function calcPaymentCharge($dto)
    {
        $dto->setPaymentCharge(number_format($dto->getPrice() * $dto->getPaymentChargePercent() / 100, 2, ".", ""));
    }

    public function calcForexFee($dto)
    {
        $dto->setForexFee(number_format($dto->getPrice() * $dto->getForexFeePercent() / 100, 2, ".", ""));
    }

    public function calcVat($dto)
    {

        if ($dto->getPlatformCountryId() == "NZ") {
            $dto->setVatPercent(0);

            $vat = ($dto->getPrice() > 400) ? ($dto->getDeclaredValue() * $dto->getVatPercent() / 100) : 0;
            $dto->setVat(number_format($vat, 2, ".", ""));
        } else {
            $dto->setVat(number_format(($dto->getDeclaredValue()) * $dto->getVatPercent() / 100, 2, ".", ""));
        }
    }

    public function calcAutoPriceValue($dto)
    {
        $recalVat = 0;
        $tmp_cost = $dto->getSupplierCost() + $dto->getLogisticCost() + $dto->getListingFee();
        $markup_percent = $dto->getSubCatMargin() + $dto->getPlatformCommission() + $dto->getPaymentChargePercent() + $dto->getForexFeePercent();

        $auto_declared = $tmp_cost / (1 - ($markup_percent / 100)) * ($dto->getDeclaredPcent() / 100);
        $country_id = substr($dto->getPlatformId(), -2);
        if ($obj = $this->getDao("SubjectDomain")->get(["subject" => "MAX_DECLARE_VALUE.{$country_id}"])) {
            $max_value = $obj->getValue();
            $auto_declared = min($max_value, $auto_declared);
        }

        $auto_vat = $auto_declared * $dto->getVatPercent() / 100;
        $auto_duty = $auto_declared * $dto->getDutyPcent() / 100;

        if ($country_id == 'NZ') {
            $auto_vat = 0;
        }

        $total_cost = $tmp_cost + $auto_vat + $auto_duty;
        $auto_total_charge = $total_cost / (1 - ($markup_percent / 100));
        if ($country_id == 'NZ') {
            if ($auto_total_charge > 750) {
                if ($auto_total_charge > 800) {
                    $dto->setDeclaredPcent(50);
                    $auto_declared = $auto_total_charge * $dto->getDeclaredPcent() / 100;
                } else {
                    $auto_declared = 400;
                }
                $dto->setVatPercent(15);
                $recalVat = $auto_declared * $dto->getVatPercent() / 100 + 38.07;
            }
        }
        $dto->setAutoTotalCharge($auto_total_charge + $recalVat);
    }

    public function calcDeclaredPcent($dto)
    {
        $country_id = $dto->getPlatformCountryId();
        $price = $dto->getPrice();
        switch ($country_id) {
            case 'GB':
                $declared_pcent = 30;
                break;

            case 'AU':
                $declared_pcent = 100;
                break;

            case "NZ":
                    $declared_pcent = ($price < 400) ? 100 : 80;
                break;

            default:
                $declared_pcent = 10;
                break;
        }

        $dto->setDeclaredPcent($declared_pcent);
    }

}
