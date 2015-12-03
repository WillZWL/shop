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
        $prod_obj_list = $this->getDao('Price')->getProductPriceWithCost($where, $option);

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
                $this->calcForexFee($prod_obj);

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

    private function to2Decimal($value)
    {
        return number_format($value, 2, ".", "");
    }

    public function calcDeliveryCharge($dto = NULL)
    {
        $this->initDto($dto);

        if ($this->getDao('ProductType')->getNumRows(["sku" => $dto->getSku(), "type_id" => "VIRTUAL"])) {
            $delivery_charge = 0;
        } else {
            $delivery_charge = $this->getDao('WeightCatCharge')->getCountryWeightChargeByPlatform($dto->getPlatformId(), $dto->getProdWeight(), $this->getDao('Config')->valueOf("default_delivery_type"));
        }
        $dto->setDefaultDeliveryCharge($delivery_charge);
        $fdl = $dto->getFreeDeliveryLimit();
        if ($dto->getPrice() > $fdl * 1) {
            $dto->setDeliveryCharge(0);
        } else {
            $dto->setDeliveryCharge($delivery_charge);
        }
    }

    public function calcAutoPriceValue()
    {
        $recalVat = 0;
        $this->initDto($dto);
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

    public function calcVat($dto = NULL)
    {
        $this->initDto($dto);

        if ($dto->getPlatformCountryId() == "NZ") {
            $value = $dto->getPrice();

            if ($value > 400) {
                $dto->setVatPercent(0);
                $dto->setVat(number_format(($dto->getDeclaredValue() * $dto->getVatPercent() / 100), 2, ".", ""));
            } else {
                $dto->setVatPercent(0);
                $dto->setVat(0.00);
            }
        } else {
            $dto->setVat(number_format(($dto->getDeclaredValue()) * $dto->getVatPercent() / 100, 2, ".", ""));
        }
    }

    public function initDto(&$dto)
    {
        if (is_null($dto)) {
            $dto = $this->getDto();
        } else {
            $this->setDto($dto);
        }
    }

    public function getDto()
    {
        return $this->dto;
    }

    public function setDto($dto)
    {
        $this->dto = $dto;
    }

    public function getProfitMarginJson($platform_id, $sku, $required_selling_price, $required_cost_price = -1)
    {
        $dto = $this->getDao('Price')->getProductPriceWithCost(['p.sku'=>$sku, 'pbv.selling_platform_id'=>$platform_id], ['limit'=>1]);

        if ($dto == null) {
            return false;
        }

        $this->performBusinessLogic($dto, 5, $required_selling_price, $required_cost_price);

        $data_arr = [
            "local_sku" => $sku,
            "based_on" => $required_selling_price,
            "get_margin" => $dto->getMargin(),
            "get_price" => $dto->getPrice(),
            "get_delivery_cost" => $dto->getDeliveryCost(),
            "get_declared_value" => $this->to2Decimal($dto->getDeclaredValue()),
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
            "get_price" => $this->to2Decimal($dto->getPrice()),
            "get_profit" => $this->to2Decimal($dto->getPrice()) - $dto->getCost()

        ];

        return json_encode($data_arr);
    }

    private function performBusinessLogic($dto, $value_to_return, $required_selling_price = -1, $required_cost_price = -1)
    {
        $required_margin = -1;
        if ($required_selling_price <= 0) {
            $required_margin = $dto->getSubCatMargin() / 100;
        }

        $this->calcComplementaryAccCost($dto);
        $this->calcLogisticCost($dto);
        $this->calcForexFee($dto);
        $this->calcCommission($dto);

        // for (;;) {
            $c = 0;
            $x = 0;
            $b = $required_selling_price;
            $bc = $b + $c;

            $l = $dto->getLogisticCost();
            $d1 = $dto->getPaymentChargePercent() / 100;
            $f1 = $dto->getForexFeePercent() / 100;
            $f2 = $f1 * $bc;

            $ca = 0;
            if (method_exists($dto, 'getComplementaryAccCost')) {
                $ca = $dto->getComplementaryAccCost();
            }

            if ($required_cost_price != -1) {
                $k = $required_cost_price;
                $dto->setSupplierCost($k);
            } else {
                $k = $dto->getSupplierCost();
            }

            $declared_value = $this->calculateDeclaredValue($dto, $dto->getPlatformCountryId(), $b);
            $dto->setDeclaredValue($declared_value);

            $z = $dto->getVatPercent() / 100;
            $h = $dto->getDeclaredPcent() / 100;
            $a1 = $h * $b;
            $y = $a1 * $z;

            $dto->setPrice($b);

            $this->calcCost($dto);
            $v = $dto->getListingFee();

            $d2 = $dto->getPaymentCharge();

            $x2 = $dto->getSalesCommission();

            $y = $dto->getVat();

            $f = $dto->getDutyPcent() / 100;

            $e = $a1 * $f;
            $c = $dto->getDeliveryCost();

            $total_cost_d = $k + $l + $v + $x2 + $d2 + $f2 + $y + $e + $ca - $c;

            $profit = $b - $total_cost_d;
            if ($bc > 0) {
                $margin = $profit / $bc * 100;
            } else {
                $margin = 0;
            }

            if ($required_margin >= 0) {
                if ($required_selling_price < $total_cost_d) {
                    $required_selling_price = $total_cost_d;
                    $increment_unit = $total_cost_d * 1 / 100;
                    if ($increment_unit <= 0) {
                        $increment_unit = 0.1;
                    }
                }
                $b = $required_selling_price;
                $bc = $b + $c;

                // if ($margin >= ($required_margin * 100)) {
                //     break;
                // }

                $required_selling_price += $increment_unit;
            }
            //  else {
            //     break;
            // }
        // }

        $total_cost_d = $this->to2Decimal($total_cost_d);
        $dto->setCost($total_cost_d);

        $current_selling_price = $dto->getPrice();
        if ($current_selling_price > 0) {
            $profit = $current_selling_price - $total_cost_d;
            $margin = (1 - ($total_cost_d / ($current_selling_price + $c)) - $x - $d1 - $f1) * 100;
            $profit = $this->to2Decimal($profit);
            $margin = $this->to2Decimal($margin);

            $dto->setProfit($profit);
            $dto->setMargin($margin);
        }

        if ($value_to_return == 4) {
            return $total_cost_d;
        }

        $profit = $b - $total_cost_d;
        if ($bc > 0) {
            $margin = $profit / $bc * 100;
        } else {
            $margin = 0;
        }

        $profit = $this->to2Decimal($profit);
        $margin = $this->to2Decimal($margin);

        $dto->setProfit($profit);
        $dto->setMargin($margin);
        $dto->setPrice($required_selling_price);

        $this->calcPaymentCharge($dto);

        return $required_selling_price;
    }

    public function calcComplementaryAccCost(&$dto)
    {
        if (method_exists($dto, "setComplementaryAccCost")) {
            $total_cost = 0;

            $where["pca.dest_country_id"] = $dto->getPlatformCountryId();
            $where["pca.mainprod_sku"] = $dto->getSku();
            $where["pca.status"] = 1;

            if ($mapped_ca_list = $this->getDao('ProductComplementaryAcc')->getMappedAccListWithName($where)) {
                foreach ($mapped_ca_list as $caobj) {
                    $cadto = $this->getDao('Price')->getProductPriceWithCost(['p.sku'=>$caobj->getAccessorySku(), 'pbv.selling_platform_id'=>$dto->getPlatformId()], ['limit'=>1]);
                    $total_cost += $cadto->getSupplierCost();
                }
            }
            $dto->setComplementaryAccCost($total_cost);
        }
    }


    public function calcLogisticCost(&$dto)
    {
        if ($lc = $this->getDao('FreightCatCharge')->calcLogisticCost($dto->getPlatformId(), $dto->getSku())) {
            $dto->setLogisticCost($lc['converted_amount']);
        } else {
            $dto->setLogisticCost(0);
        }
    }

    public function calcForexFee($dto = NULL)
    {
        $this->initDto($dto);
        $dto->setForexFee(number_format(($dto->getPrice() + $dto->getDeliveryCharge()) * $dto->getForexFeePercent() / 100, 2, ".", ""));
    }

    public function calcCommission($dto = NULL)
    {
        $this->initDto($dto);
        $dto->setSalesCommission(number_format(($dto->getPrice() + $dto->getDeliveryCharge()) * $dto->getPlatformCommission() / 100, 2, ".", ""));
    }

    public function calculateDeclaredValue($prod_obj = "", $country_id = "", $price = "")
    {
        $price = $prod_obj->getPrice();
        $country_id = $prod_obj->getPlatformCountryId();
        $declared_value = $this->getService('So')->getDeclaredValue($prod_obj, $country_id, $price);
        $prod_obj->setDeclaredValue($declared_value);

        return $declared_value;
    }

    public function calcPaymentCharge($dto = NULL)
    {
        $this->initDto($dto);
        $dto->setPaymentCharge(number_format(($dto->getPrice() + $dto->getDeliveryCharge()) * $dto->getPaymentChargePercent() / 100, 2, ".", ""));
    }

    public function calcDuty($dto = NULL)
    {
        $this->initDto($dto);
        $duty = number_format($dto->getDeclaredValue() * $dto->getDutyPcent() / 100, 2, ".", "");
        $dto->setDuty($duty);
    }

    public function getRealPrice(&$prod)
    {
        if ($prod->getPrice()) {
            return $prod->getPrice();
        } else {
            return $this->getDefaultPrice($prod->getSku(), $prod->getPlatformId());
        }
    }

    private function getDefaultPrice($sku, $platform_id)
    {
        $price_obj = $this->getDao('Price')->get(["sku" => $sku, "platform_id" => $platform_id]);
        if (!$price_obj || !(call_user_func([$price_obj, "getPrice"]) * 1)) {
            if (!($default_obj = $this->getDao('Price')->getDefaultConvertedPrice(["pr.sku" => $sku, "pbv.selling_platform_id" => $platform_id], ['limit'=> 1]))) {
                return 0;
            }
            $defaultPlatformConvertedPrice = $default_obj->getDefaultPlatformConvertedPrice();
        }
        return $defaultPlatformConvertedPrice ? $defaultPlatformConvertedPrice : $price_obj->getPrice();
    }

    public function calculateProfit($dto = null)
    {
        $this->initDto($dto);
        $this->checkDtoPrice();
        $this->calcDeliveryCharge();
        // $this->calcCost();

        $price = $dto->getPrice();

        $this->performBusinessLogic($dto, 5, $price);
        return;
    }

    public function calcCost($dto = NULL)
    {
        $this->initDto($dto);
        $this->calcDtoData();
        $dto->setCost(number_format($dto->getVat()
            + $dto->getSupplierCost()
            + $dto->getAdminFee()
            + $dto->getLogisticCost()
            + $dto->getPaymentCharge()
            + $dto->getForexFee()
            + $dto->getSalesCommission()
            + $dto->getListingFee()
            + $dto->getDuty(), 2, ".", ""));
    }

    public function calcDtoData($dto = NULL)
    {
        $this->initDto($dto);
        $this->calcDeclaredValue();
        $this->calcCommission();
        $this->calcDuty();
        $this->calcPaymentCharge();
        $this->calcForexFee();
        $this->calcVat();
        $this->calcAutoPriceValue();
    }

    public function checkDtoPrice($dto = NULL)
    {
        $this->initDto($dto);

        if ($dto->getPrice()) {
            $price = $dto->getPrice();
        } else {
            $price_obj = $this->getDao('Price')->get(["sku" => $dto->getSku(), "platform_id" => $dto->getPlatformId()]);
            if ($price_obj) {
                $price = $price_obj->getPrice();
                $dto->setCurrentPlatformPrice($price);
                if (!($price * 1)) {
                    if ($default_obj = $this->getDao('Price')->getDefaultConvertedPrice(["sku" => $dto->getsku(), "platform_id" => $dto->getPlatformId()], ['limit' => 1])) {
                        $default_platform_converted_price = $default_obj->getDefaultPlatformConvertedPrice();
                        $price = $default_platform_converted_price;
                        $dto->setDefaultPlatformConvertedPrice($price);
                    }
                }
                $dto->setPrice($price);
            } else {
                $dto->setPrice(0);
                $price = 0;
            }
        }
    }

    public function calcDeclaredValue($dto = NULL)
    {
        $this->initDto($dto);

        $value = $dto->getPrice() + $dto->getDeliveryCharge();

        $country_id = $dto->getPlatformCountryId();
        $temp = $this->calculateDeclaredValue($dto, $country_id, $value);

        $dto->setDeclaredValue($temp);
        return;

        if ($dto->getPlatformCountryId() == 'NZ') {
            if ($value > 750 && 800 > $value) {
                $dto->setDeclaredPcent(100);
                $dto->setVatPercent(15);
                $declared = 400;
            } elseif ($value >= 800) {
                $dto->setDeclaredPcent(100);
                $dto->setVatPercent(15);
                $declared = $value / 2;
            } else {
                $declared = $value * $dto->getDeclaredPcent() / 100;
            }
        } elseif ($obj = $this->getDao("SubjectDomain")->get(["subject" => "MAX_DECLARE_VALUE.{$dto->getPlatformCountryId()}"])) {
            $dto->setDeclaredPcent(100);
            $max_value = $obj->getValue();
            $declared = min($max_value, $value);
        } else {
            $declared = $value * $dto->getDeclaredPcent() / 100;
        }
        $dto->setDeclaredValue($declared);
    }

    public function getPricingToolInfo($platform_id = "", $sku = "")
    {
        if ($platform_id != "" && $sku != "") {
            $ret = [];

            $tmp = $this->getDao('Price')->getProductPriceWithCost(['p.sku'=>$sku, 'pbv.selling_platform_id'=>$platform_id], ['limit'=>1]);

            $price_obj = $this->getDao('Price')->get(["sku" => $sku, "platform_id" => $platform_id]);
            if (!$price_obj) {
                $tmp->setPrice($this->getRealPrice($tmp));
                $tmp->setCurrentPlatformPrice(NULL);
                $tmp->setDefaultPlatformConvertedPrice($tmp->getPrice());
            } else {
                $tmp->setCurrentPlatformPrice($tmp->getPrice());
            }

            $this->calcLogisticCost($tmp);
            $this->calculateProfit($tmp);

            if ($tmp->getPlatformCountryId() == "GB") {
                $tmp->setDeclaredPcent(30);
            } else {
                $price = $tmp->getPrice();
                switch ($tmp->getPlatformCountryId()) {
                    case "AU":
                            $declared_pcent = 100;
                        break;

                    case "NZ":
                        if ($price < 400) {
                            $declared_pcent = 100;
                        } else {
                            $declared_pcent = 80;
                        }
                        break;

                    default:
                        $declared_pcent = 10;
                        break;
                }
                $tmp->setDeclaredPcent($declared_pcent);
            }

            $tmp->setListingStatus($price_obj ? $price_obj->getListingStatus() : "N");
            $ret["dst"] = $tmp;

            unset($tmp);
            unset($p_srv);

            return $ret;
        } else {
            return FALSE;
        }
    }
}
