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
        $this->updatePrice($newObj, $obj);

        return $newObj;
    }

    public function updatePrice($newObj, $obj)
    {
        $newObj->setPlatformId((string) $obj->platform_id);
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

    public function getTrailCalcuProfitMargin(PriceVo $price_obj)
    {
        $this->set_tool_path('marketing/pricing_tool_'.strtolower(PLATFORM_TYPE));

        $dto = $this->getDao('Price')->getPriceCostDto($sku, $platform_id);

        $this->performBusinessLogic($dto, 5, $required_selling_price, $required_cost_price);

        $array = array(
            'local_sku' => $sku,
            'based_on' => $price_obj->getPrice(),
            'get_margin' => $dto->getMargin(),

            'get_price' => $dto->getPrice(),

            'get_delivery_cost' => $dto->getDeliveryCost(),
            'get_declared_value' => $this->to2Decimal($dto->getDeclaredValue()),

            'get_vat_percent' => $dto->getVatPercent(),
            'get_vat' => $dto->getVat(),

            'get_sales_commission' => $dto->getSalesCommission(),

            'get_duty_pcent' => $dto->getDutyPcent(),
            'get_duty' => $dto->getDuty(),

            'get_payment_charge_percent' => $dto->getPaymentChargePercent(),
            'get_payment_charge' => $dto->getPaymentCharge(),

            'get_forex_fee_percent' => $dto->getForexFeePercent(),
            'get_forex_fee' => $dto->getForexFee(),

            'get_listing_fee' => $dto->getListingFee(),

            'get_logistic_cost' => $dto->getLogisticCost(),
            'get_supplier_cost' => $dto->getSupplierCost(),

            'get_complementary_acc_cost' => $dto->getComplementaryAccCost(),

            'get_cost' => $dto->getCost(),
            'get_price' => $this->to2Decimal($dto->getPrice()),
            'get_profit' => $this->to2Decimal($dto->getPrice()) - $dto->getCost(),

        );

        return json_encode($array);
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

        for (;;) {
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

            $this->calcVat();

            $this->calcPaymentCharge();
            $d2 = $dto->getPaymentCharge();

            $this->calcCommission();
            $x2 = $dto->getSalesCommission();

            $this->calcDuty($dto);

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

                if ($margin >= ($required_margin * 100)) {
                    break;
                }
                $required_selling_price += $increment_unit;
            } else {
                break;
            }
        }

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

        echo '<hr><pre>';
        var_dump('a: '.$a);
        var_dump('required_selling_price: '.$required_selling_price);

        echo '<hr><pre>';
        var_dump('k: '.$k);
        var_dump('l: '.$l);
        var_dump('v: '.$v);
        var_dump('d1: '.$d1);
        var_dump('d2: '.$d2);
        var_dump('f1: '.$f1);
        var_dump('f2: '.$f2);
        var_dump('h (declared_pcent): '.$h);
        var_dump('a1: '.$a1);
        var_dump('y: '.$y);

        var_dump('value_to_declare: '.$value_to_declare);
        var_dump('declared_value: '.$declared_value);
        var_dump('get_vat: '.$dto->getVat());
        var_dump('get_vat_percent: '.$dto->getVatPercent());

        var_dump('get_sales_commission: '.$dto->getSalesCommission());

        var_dump('total_cost(d): '.$total_cost_d);

        var_dump('Selling_price: '.$selling_price);
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



//********** Old code *********/

    // public function getPrice(&$prod)
    // {
    //     if ($prod->getPrice()) {
    //         return $prod->getPrice();
    //     } else {
    //         return $this->getWDefaultPrice($prod->getSku(), $prod->getPlatform_id());
    //     }
    // }

    // public function getWDefaultPrice($sku, $platform_id)
    // {
    //     $price_obj = $this->getDao('Price')->get(['sku' => $sku, 'platform_id' => $platform_id]);
    //     if (!$price_obj || !(call_user_func([$price_obj, 'getPrice']) * 1)) {
    //         if (!($default_obj = $this->getDao('Price')->getDefaultConvertedPrice(['sku' => $sku, 'platform_id' => $platform_id]))) {
    //             return 0;
    //         }
    //         $defaultPlatformConvertedPrice = $default_obj->getDefaultPlatformConvertedPrice();
    //     }

    //     return $defaultPlatformConvertedPrice ? $defaultPlatformConvertedPrice : $price_obj->getPrice();
    // }

    // public function calcLogisticCost(&$dto)
    // {
    //     if ($lc = $this->getDao('FreightCatCharge')->calcLogisticCost($dto->getPlatformId(), $dto->getSku())) {
    //         $dto->setLogisticCost($lc['converted_amount']);
    //     } else {
    //         $dto->setLogisticCost(0);
    //     }
    // }

    // public function calculateProfit($dto = null)
    // {
    //     $this->initDto($dto);
    //     $this->checkDtoPrice();
    //     $this->calcDeliveryCharge();
    //     $this->calcCost();

    //     $price = $dto->getPrice();

    //     $this->performBusinessLogic($dto, 5, $price);

    //     return;

    //     $profit = $price + $dto->getDeliveryCharge() - $dto->getCost();

    //     if ($price > 0) {
    //         $margin = number_format($profit / ($price + $dto->getDeliveryCharge()) * 100, 2, '.', '');
    //     } else {
    //         $margin = 0;
    //     }
    //     $dto->setProfit(number_format($profit, 2, '.', ''));
    //     $dto->setMargin($margin);
    // }

    // private function performBusinessLogic($dto, $value_to_return, $required_selling_price = -1, $required_cost_price = -1)
    // {
    //     return $this->performBusinessLogicV2($dto, $value_to_return, $required_selling_price, $required_cost_price);
    // }

    // private function performBusinessLogicV2($dto, $value_to_return, $required_selling_price = -1, $required_cost_price = -1)
    // {
    //     if ($dto == null) {
    //         $this->bt();
    //     }

    //     $required_margin = -1;
    //     if ($required_selling_price <= 0) {
    //         $required_margin = $dto->getSubCatMargin() / 100;
    //     }

    //     $this->calcComplementaryAccCost($dto);
    //     $this->calcLogisticCost($dto);
    //     $this->calcForexFee($dto);
    //     $this->calcCommission($dto);

    //     for (;;) {
    //         $c = 0;
    //         $x = 0;
    //         $b = $required_selling_price;
    //         $bc = $b + $c;

    //         $l = $dto->getLogisticCost();
    //         $d1 = $dto->getPaymentChargePercent() / 100;
    //         $f1 = $dto->getForexFeePercent() / 100;
    //         $f2 = $f1 * $bc;

    //         $ca = 0;
    //         if (method_exists($dto, 'getComplementaryAccCost')) {
    //             $ca = $dto->getComplementaryAccCost();
    //         }

    //         if ($required_cost_price != -1) {
    //             $k = $required_cost_price;
    //             $dto->setSupplierCost($k);
    //         } else {
    //             $k = $dto->getSupplierCost();
    //         }

    //         $declared_value = $this->calculateDeclaredValue($dto, $dto->getPlatformCountryId(), $b);
    //         $dto->setDeclaredValue($declared_value);

    //         $z = $dto->getVatPercent() / 100;
    //         $h = $dto->getDeclaredPcent() / 100;
    //         $a1 = $h * $b;
    //         $y = $a1 * $z;

    //         $dto->setPrice($b);

    //         $this->calcCost($dto);
    //         $v = $dto->getListingFee();

    //         $this->calcVat();

    //         $this->calcPaymentCharge();
    //         $d2 = $dto->getPaymentCharge();

    //         $this->calcCommission();
    //         $x2 = $dto->getSalesCommission();

    //         $this->calcDuty($dto);

    //         $y = $dto->getVat();

    //         $f = $dto->getDutyPcent() / 100;

    //         $e = $a1 * $f;
    //         $c = $dto->getDeliveryCost();

    //         $total_cost_d = $k + $l + $v + $x2 + $d2 + $f2 + $y + $e + $ca - $c;

    //         $profit = $b - $total_cost_d;
    //         if ($bc > 0) {
    //             $margin = $profit / $bc * 100;
    //         } else {
    //             $margin = 0;
    //         }

    //         if ($required_margin >= 0) {
    //             if ($required_selling_price < $total_cost_d) {
    //                 $required_selling_price = $total_cost_d;
    //                 $increment_unit = $total_cost_d * 1 / 100;
    //                 if ($increment_unit <= 0) {
    //                     $increment_unit = 0.1;
    //                 }
    //             }
    //             $b = $required_selling_price;
    //             $bc = $b + $c;

    //             if ($margin >= ($required_margin * 100)) {
    //                 break;
    //             }
    //             $required_selling_price += $increment_unit;
    //         } else {
    //             break;
    //         }
    //     }

    //     $total_cost_d = $this->to2Decimal($total_cost_d);
    //     $dto->setCost($total_cost_d);

    //     $current_selling_price = $dto->getPrice();
    //     if ($current_selling_price > 0) {
    //         $profit = $current_selling_price - $total_cost_d;
    //         $margin = (1 - ($total_cost_d / ($current_selling_price + $c)) - $x - $d1 - $f1) * 100;
    //         $profit = $this->to2Decimal($profit);
    //         $margin = $this->to2Decimal($margin);

    //         $dto->setProfit($profit);
    //         $dto->setMargin($margin);
    //     }

    //     if ($value_to_return == 4) {
    //         return $total_cost_d;
    //     }

    //     $profit = $b - $total_cost_d;
    //     if ($bc > 0) {
    //         $margin = $profit / $bc * 100;
    //     } else {
    //         $margin = 0;
    //     }

    //     $profit = $this->to2Decimal($profit);
    //     $margin = $this->to2Decimal($margin);

    //     $dto->setProfit($profit);
    //     $dto->setMargin($margin);
    //     $dto->setPrice($required_selling_price);

    //     $this->calcPaymentCharge($dto);

    //     return $required_selling_price;

    //     echo '<hr><pre>';
    //     var_dump('a: '.$a);
    //     var_dump('required_selling_price: '.$required_selling_price);

    //     echo '<hr><pre>';
    //     var_dump('k: '.$k);
    //     var_dump('l: '.$l);
    //     var_dump('v: '.$v);
    //     var_dump('d1: '.$d1);
    //     var_dump('d2: '.$d2);
    //     var_dump('f1: '.$f1);
    //     var_dump('f2: '.$f2);
    //     var_dump('h (declared_pcent): '.$h);
    //     var_dump('a1: '.$a1);
    //     var_dump('y: '.$y);

    //     var_dump('value_to_declare: '.$value_to_declare);
    //     var_dump('declared_value: '.$declared_value);
    //     var_dump('get_vat: '.$dto->getVat());
    //     var_dump('get_vat_percent: '.$dto->getVatPercent());

    //     var_dump('get_sales_commission: '.$dto->getSalesCommission());

    //     var_dump('total_cost(d): '.$total_cost_d);

    //     var_dump('Selling_price: '.$selling_price);
    // }

    // private function to2Decimal($value)
    // {
    //     return number_format($value, 2, '.', '');
    // }

    // public function calcComplementaryAccCost(&$dto)
    // {
    //     if (method_exists($dto, 'setComplementaryAccCost')) {
    //         $total_cost = 0;

    //         $check_platform_arr = array('WEB');
    //         $platform_id = $dto->getPlatformId();
    //         $platform_country_id = $dto->getPlatformCountryId();

    //         {
    //             $mainprod_sku = $dto->getSku();
    //             $where['pca.dest_country_id'] = $platform_country_id;
    //             $where['pca.mainprod_sku'] = $mainprod_sku;
    //             $where['pca.status'] = 1;

    //             if ($mapped_ca_list = $this->getDao('ProductComplementaryAcc')->getMappedAccListWithName($where)) {
    //                 foreach ($mapped_ca_list as $caobj) {
    //                     $cadto = $this->getDao('Price')->getPriceCostDto($caobj->getAccessorySku(), $dto->getPlatformId());
    //                     $total_cost += $cadto->getSupplierCost();
    //                 }
    //             }
    //         }

    //         $dto->setComplementaryAccCost($total_cost);
    //     }
    // }

    // public function calcDeliveryCharge($dto = null)
    // {
    //     $this->initDto($dto);

    //     if ($this->getDao('ProductType')->getNumRows(['sku' => $dto->getSku(), 'type_id' => 'VIRTUAL'])) {
    //         $delivery_charge = 0;
    //     } else {
    //         $delivery_charge = $this->getDao('WeightCatCharge')->getCountryWeightChargeByPlatform($dto->getPlatformId(), $dto->getProdWeight(), $this->getDao('Config')->valueOf('default_delivery_type'));
    //     }
    //     $dto->setDefaultDeliveryCharge($delivery_charge);
    //     $fdl = $dto->getFreeDeliveryLimit();
    //     if ($dto->getPrice() > $fdl * 1) {
    //         $dto->setDeliveryCharge(0);
    //     } else {
    //         $dto->setDeliveryCharge($delivery_charge);
    //     }
    // }

    // public function checkDtoPrice($dto = null)
    // {
    //     $this->initDto($dto);

    //     if ($dto->getPrice()) {
    //         $price = $dto->getPrice();
    //     } else {
    //         $price_obj = $this->getDao('Price')->get(['sku' => $dto->getSku(), 'platform_id' => $dto->getPlatformId()]);
    //         if ($price_obj) {
    //             $price = $price_obj->getPrice();
    //             $dto->setCurrentPlatformPrice($price);
    //             if (!($price * 1)) {
    //                 if ($default_obj = $this->getDao('Price')->getDefaultConvertedPrice(['sku' => $dto->getSku(), 'platform_id' => $dto->getPlatformId()])) {
    //                     $defaultPlatformConvertedPrice = $default_obj->getDefaultPlatformConvertedPrice();
    //                     $price = $defaultPlatformConvertedPrice;
    //                     $dto->setDefaultPlatformConvertedPrice($price);
    //                 }
    //             }
    //             $dto->setPrice($price);
    //         } else {
    //             $dto->setPrice(0);
    //             $price = 0;
    //         }
    //     }
    // }

    // public function calcCost($dto = null)
    // {
    //     $this->initDto($dto);
    //     $this->calcDtoData();
    //     $dto->setCost(number_format($dto->getVat()
    //         + $dto->getSupplierCost()
    //         + $dto->getAdminFee()
    //         + $dto->getLogisticCost()
    //         + $dto->getPaymentCharge()
    //         + $dto->getForexFee()
    //         + $dto->getSalesCommission()
    //         + $dto->getListingFee()
    //         + $dto->getDuty(), 2, '.', ''));
    // }

    // public function calcDtoData($dto = null)
    // {
    //     $this->initDto($dto);
    //     $this->calcDeclaredValue();
    //     $this->calcCommission();
    //     $this->calcDuty();
    //     $this->calcPaymentCharge();
    //     $this->calcForexFee();
    //     $this->calcVat();
    //     $this->calcAutoPriceValue();
    // }

    // public function calcAutoPriceValue()
    // {
    //     $recalVat = 0;
    //     $this->initDto($dto);
    //     $tmp_cost = $dto->getSupplierCost() + $dto->getLogisticCost() + $dto->getListingFee();
    //     $markup_percent = $dto->getSubCatMargin() + $dto->getPlatformCommission() + $dto->getPaymentChargePercent() + $dto->getForexFeePercent();
    //     $auto_declared = $tmp_cost / (1 - ($markup_percent / 100)) * ($dto->getDeclaredPcent() / 100);
    //     $country_id = substr($dto->getPlatformId(), -2);
    //     if ($obj = $this->getDao('SubjectDomain')->get(['subject' => "MAX_DECLARE_VALUE.{$country_id}"])) {
    //         $max_value = $obj->getValue();
    //         $auto_declared = min($max_value, $auto_declared);
    //     }

    //     $auto_vat = $auto_declared * $dto->getVatPercent() / 100;
    //     $auto_duty = $auto_declared * $dto->getDutyPcent() / 100;

    //     if ($country_id == 'NZ') {
    //         $auto_vat = 0;
    //     }

    //     $total_cost = $tmp_cost + $auto_vat + $auto_duty;
    //     $auto_total_charge = $total_cost / (1 - ($markup_percent / 100));
    //     if ($country_id == 'NZ') {
    //         if ($auto_total_charge > 750) {
    //             if ($auto_total_charge > 800) {
    //                 $dto->setDeclaredPcent(50);
    //                 $auto_declared = $auto_total_charge * $dto->getDeclaredPcent() / 100;
    //             } else {
    //                 $auto_declared = 400;
    //             }
    //             $dto->setVatPercent(15);
    //             $recalVat = $auto_declared * $dto->getVatPercent() / 100 + 38.07;
    //         }
    //     }
    //     $dto->setAutoTotalCharge($auto_total_charge + $recalVat);
    // }

    // public function calcForexFee($dto = null)
    // {
    //     $this->initDto($dto);
    //     $dto->setForexFee(number_format(($dto->getPrice() + $dto->getDeliveryCharge()) * $dto->getForexFeePercent() / 100, 2, '.', ''));
    // }

    // public function calcVat($dto = null)
    // {
    //     $this->initDto($dto);

    //     if ($dto->getPlatformCountryId() == 'NZ') {
    //         $value = $dto->getPrice();

    //         if ($value > 400) {
    //             $dto->setVatPercent(0);
    //             $dto->setVat(number_format(($dto->getDeclaredValue() * $dto->getVatPercent() / 100), 2, '.', ''));
    //         } else {
    //             $dto->setVatPercent(0);
    //             $dto->setVat(0.00);
    //         }

    //         if (1 == 0) {
    //             if ($dto->getDeclaredValue() > 400) {
    //                 $dto->setVat(number_format((($dto->getDeclaredValue()) * $dto->getVatPercent() / 100) + 38.07, 2, '.', ''));
    //             } else {
    //                 $dto->setVat(0.00);
    //             }
    //         }
    //     } else {
    //         $dto->setVat(number_format(($dto->getDeclaredValue()) * $dto->getVatPercent() / 100, 2, '.', ''));
    //     }
    // }

    // public function calcDuty($dto = null)
    // {
    //     $this->initDto($dto);
    //     $duty = number_format($dto->getDeclaredValue() * $dto->getDutyPcent() / 100, 2, '.', '');
    //     $dto->setDuty($duty);
    // }

    // public function calcPaymentCharge($dto = null)
    // {
    //     $this->initDto($dto);
    //     $dto->setPaymentCharge(number_format(($dto->getPrice() + $dto->getDeliveryCharge()) * $dto->getPaymentChargePercent() / 100, 2, '.', ''));
    // }

    // public function calcCommission($dto = null)
    // {
    //     $this->initDto($dto);
    //     $dto->setSalesCommission(number_format(($dto->getPrice() + $dto->getDeliveryCharge()) * $dto->getPlatformCommission() / 100, 2, '.', ''));
    // }

    // public function calcDeclaredValue($dto = null)
    // {
    //     $this->initDto($dto);

    //     $value = $dto->getPrice() + $dto->getDeliveryCharge();

    //     $country_id = $dto->getPlatformCountryId();
    //     $temp = $this->calculateDeclaredValue($dto, $country_id, $value);

    //     $dto->setDeclaredValue($temp);

    //     return;

    //     if ($dto->getPlatformCountryId() == 'NZ') {
    //         if ($value > 750 && 800 > $value) {
    //             $dto->setDeclaredPcent(100);
    //             $dto->setVatPercent(15);
    //             $declared = 400;
    //         } elseif ($value >= 800) {
    //             $dto->setDeclaredPcent(100);
    //             $dto->setVatPercent(15);
    //             $declared = $value / 2;
    //         } else {
    //             $declared = $value * $dto->getDeclaredPcent() / 100;
    //         }
    //     } elseif ($obj = $this->getDao('SubjectDomain')->get(['subject' => "MAX_DECLARE_VALUE.{$dto->getPlatformCountryId()}"])) {
    //         $dto->setDeclaredPcent(100);
    //         $max_value = $obj->getValue();
    //         $declared = min($max_value, $value);
    //     } else {
    //         $declared = $value * $dto->getDeclaredPcent() / 100;
    //     }
    //     $dto->setDeclaredValue($declared);
    // }

    // public function calculateDeclaredValue($prod_obj = '', $country_id = '', $price = '')
    // {
    //     $max_declared_value = -1;
    //     $declared_pcent = 100;
    //     $declared = -1;

    //     $this->declared_value_debug = '';

    //     #if($prod_obj)
    //     {
    //         switch ($country_id) {
    //             case 'AU':
    //                 if ($price < 950) {
    //                     $declared_pcent = 100;
    //                 } else {
    //                     $max_declared_value = 950;
    //                 }
    //                 break;

    //             case 'SG':
    //                 if ($price < 350) {
    //                     $declared_pcent = 100;
    //                 } else {
    //                     $max_declared_value = 350;
    //                 }
    //                 break;

    //             case 'NZ':
    //                 if ($price >= 400) {
    //                     $declared_pcent = 80;
    //                 } else {
    //                     $declared_pcent = 100;
    //                 }
    //                 break;

    //             // based on SBF#1790
    //             case 'AE':
    //             case 'AF':
    //             case 'AG':
    //             case 'AI':
    //             case 'AM':
    //             case 'AN':
    //             case 'AO':
    //             case 'AQ':
    //             case 'AR':
    //             case 'AS':
    //             case 'AW':
    //             case 'AZ':
    //             case 'BB':
    //             case 'BD':
    //             case 'BF':
    //             case 'BH':
    //             case 'BI':
    //             case 'BJ':
    //             case 'BM':
    //             case 'BN':
    //             case 'BO':
    //             case 'BR':
    //             case 'BS':
    //             case 'BT':
    //             case 'BV':
    //             case 'BW':
    //             case 'BZ':
    //             case 'CA':
    //             case 'CC':
    //             case 'CD':
    //             case 'CF':
    //             case 'CG':
    //             case 'CI':
    //             case 'CK':
    //             case 'CL':
    //             case
    //             'CM':
    //             case 'CN':
    //             case 'CO':
    //             case 'CR':
    //             case 'CU':
    //             case 'CV':
    //             case 'CX':
    //             case 'CY':
    //             case 'DJ':
    //             case 'DM':
    //             case 'DO':
    //             case 'DZ':
    //             case 'EC':
    //             case 'EG':
    //             case 'EH':
    //             case 'ER':
    //             case 'ET':
    //             case 'FJ':
    //             case 'FK':
    //             case 'FM':
    //             case 'GA':
    //             case 'GD':
    //             case 'GE':
    //             case 'GF':
    //             case 'GH':
    //             case 'GL':
    //             case 'GM':
    //             case 'GN':
    //             case 'GP':
    //             case 'GQ':
    //             case 'GS':
    //             case 'GT':
    //             case 'GU':
    //             case 'GW':
    //             case 'GY':
    //             case 'HK':
    //             case
    //             'HM':
    //             case 'HN':
    //             case 'HT':
    //             case 'ID':
    //             case 'IL':
    //             case 'IN':
    //             case 'IO':
    //             case 'IQ':
    //             case 'IR':
    //             case 'JM':
    //             case 'JO':
    //             case 'JP':
    //             case 'KE':
    //             case 'KG':
    //             case 'KH':
    //             case 'KI':
    //             case 'KM':
    //             case 'KN':
    //             case 'KP':
    //             case 'KR':
    //             case 'KW':
    //             case 'KY':
    //             case 'KZ':
    //             case 'LA':
    //             case 'LB':
    //             case 'LC':
    //             case 'LK':
    //             case 'LR':
    //             case 'LS':
    //             case 'LY':
    //             case 'MA':
    //             case 'ME':
    //             case 'MG':
    //             case 'MH':
    //             case 'ML':
    //             case 'MM':
    //             case
    //             'MN':
    //             case 'MO':
    //             case 'MP':
    //             case 'MQ':
    //             case 'MR':
    //             case 'MS':
    //             case 'MU':
    //             case 'MV':
    //             case 'MW':
    //             case 'MX':
    //             case 'MY':
    //             case 'MZ':
    //             case 'NA':
    //             case 'NC':
    //             case 'NE':
    //             case 'NF':
    //             case 'NG':
    //             case 'NI':
    //             case 'NP':
    //             case 'NR':
    //             case 'NU':
    //             case 'OM':
    //             case 'PA':
    //             case 'PE':
    //             case 'PF':
    //             case 'PG':
    //             case 'PH':
    //             case 'PK':
    //             case 'PM':
    //             case 'PN':
    //             case 'PR':
    //             case 'PS':
    //             case 'PW':
    //             case 'PY':
    //             case 'QA':
    //             case
    //             'RE':
    //             case 'RU':
    //             case 'RW':
    //             case 'SA':
    //             case 'SB':
    //             case 'SC':
    //             case 'SD':
    //             case 'SH':
    //             case 'SL':
    //             case 'SN':
    //             case 'SO':
    //             case 'SR':
    //             case 'ST':
    //             case 'SV':
    //             case 'SY':
    //             case 'SZ':
    //             case 'TC':
    //             case 'TD':
    //             case 'TF':
    //             case 'TG':
    //             case 'TH':
    //             case 'TJ':
    //             case 'TK':
    //             case 'TL':
    //             case 'TM':
    //             case 'TN':
    //             case 'TO':
    //             case 'TR':
    //             case 'TT':
    //             case 'TV':
    //             case 'TW':
    //             case 'TZ':
    //             case 'UG':
    //             case 'UM':
    //             case 'US':
    //             case
    //             'UY':
    //             case 'UZ':
    //             case 'VC':
    //             case 'VE':
    //             case 'VG':
    //             case 'VI':
    //             case 'VN':
    //             case 'VU':
    //             case 'WF':
    //             case 'WS':
    //             case 'YE':
    //             case 'YT':
    //             case 'ZA':
    //             case 'ZM':
    //             case 'ZW':
    //                 $declared_pcent = 10;
    //                 break;
    //             case 'AD':
    //             case 'AL':
    //             case 'AT':
    //             case 'AX':
    //             case 'BA':
    //             case 'BE':
    //             case 'BG':
    //             case 'BL':
    //             case 'BY':
    //             case 'CH':
    //             case 'CZ':
    //             case 'DE':
    //             case 'DK':
    //             case 'EE':
    //             case 'ES':
    //             case 'FI':
    //             case 'FO':
    //             case 'FR':
    //             case 'GG':
    //             case
    //             'GI':
    //             case 'GR':
    //             case 'HR':
    //             case 'HU':
    //             case 'IE':
    //             case 'IM':
    //             case 'IS':
    //             case 'IT':
    //             case 'JE':
    //             case 'LI':
    //             case 'LT':
    //             case 'LU':
    //             case 'LV':
    //             case 'MC':
    //             case 'MD':
    //             case 'MF':
    //             case 'MK':
    //             case 'MT':
    //             case 'NL':
    //             case 'NO':
    //             case
    //             'PL':
    //             case 'PT':
    //             case 'RO':
    //             case 'RS':
    //             case 'SE':
    //             case 'SI':
    //             case 'SJ':
    //             case 'SK':
    //             case 'SM':
    //             case 'UA':
    //             case 'VA':
    //             case 'GB':
    //                 $declared_pcent = 10;
    //                 break;
    //             // $declared_pcent = 30;
    //             // break;

    //             default:    # all other countries
    //                 $declared_pcent = 10;
    //                 break;
    //                 if ($fc_obj = $this->getDao('FreightCatCharge')->get(['id' => $prod_obj->getFreightCatId()])) {
    //                     $declared_pcent = $fc_obj->getDeclaredPcent();
    //                     $this->declared_value_debug .= "1. declared pcent is $declared_pcent\r\n";
    //                 } else {
    //                     // default value
    //                     $declared_pcent = $this->getDao('Config')->valueOf('default_declared_pcent');
    //                     $this->declared_value_debug .= "2. declared pcent is $declared_pcent\r\n";
    //                 }

    //                 if ($obj = $this->getDao('SubjectDomain')->get(['subject' => "MAX_DECLARE_VALUE.{$country_id}"])) {
    //                     $max_value = $obj->getValue();
    //                     $declared = min($max_value, $price);
    //                     $this->declared_value_debug .= "3. (max, price, chosen) is ($max_value, $price, $declared)\r\n";
    //                 } else {
    //                     $declared = $price * $declared_pcent / 100;
    //                     $this->declared_value_debug .= "4. (price, declared_pcent, final) is ($price, $declared_pcent, $declared)\r\n";
    //                 }
    //         }

    //         if ($declared == -1) {
    //             # we have to use max declared value
    //             if ($max_declared_value != -1) {
    //                 if ($price > $max_declared_value) {
    //                     $declared = $max_declared_value;
    //                 } else {
    //                     $declared = $price;
    //                 }
    //             } else {
    //                 # we have to use declared percent
    //                 $declared = $price * $declared_pcent / 100;
    //             }
    //             $prod_obj->setDeclaredPcent($declared_pcent);
    //         }
    //     }

    //     $this->declared_value_debug .= "5. declared_at $declared\r\n";

    //     return $declared;
    // }

    // public function initDto(&$dto)
    // {
    //     if (is_null($dto)) {
    //         $dto = $this->getDto();
    //     } else {
    //         $this->setDto($dto);
    //     }
    // }

    // public function getDto()
    // {
    //     return $this->dto;
    // }

    // public function setDto($dto)
    // {
    //     $this->dto = $dto;
    // }

    // public function get_tool_path()
    // {
    //     return $this->tool_path;
    // }

    // public function set_tool_path($value)
    // {
    //     $this->tool_path = $value;

    //     return $this;
    // }

    // public function calcWebsiteProductRrp($price = 0, $fixed_rrp = 'Y', $rrp_factor = 1.18)
    // {
    //     if ($price > 0) {
    //         if ($fixed_rrp == 'Y') {
    //             $markup = $price * 1.18;
    //         } else {
    //             if ($rrp_factor < 10) {
    //                 $markup = $price * $rrp_factor;
    //             } else {
    //                 return number_format($rrp_factor, 2, '.', '');
    //             }
    //         }

    //         $remainder = fmod($markup, 5);
    //         $add_to = 5 - $remainder;
    //         $rrp = number_format($markup - (-$add_to) - .01, 2, '.', '');

    //         return number_format($rrp, 2, '.', '');
    //     }

    //     return 0;
    // }

    // public function getProfitMarginJson($platform_id, $sku, $required_selling_price, $required_cost_price = -1, $throw_exception = true)
    // {
    //     // var_dump("HI");
    //     $this->set_tool_path('marketing/pricing_tool_'.strtolower(PLATFORM_TYPE));

    //     $dto = $this->getDao('Price')->getPriceCostDto($sku, $platform_id);
    //     if ($dto == null) {
    //         if ($throw_exception) {
    //             throw new Exception("[$sku] on [$platform_id] cannot be found, unable to calculate profit/margin");
    //         }

    //         return false;
    //     }

    //     $this->performBusinessLogic($dto, 5, $required_selling_price, $required_cost_price);
    //     if (1 == 10) {
    //         echo '<pre>';
    //         var_dump('get_supplier_cost:          '.$dto->getSupplierCost());
    //         var_dump('get_logistic_cost:          '.$dto->getLogisticCost());
    //         var_dump('get_listing_fee:            '.$dto->getListingFee());
    //         var_dump('get_payment_charge_percent: '.$dto->getPaymentChargePercent());
    //         var_dump('get_vat_percent:            '.$dto->getVatPercent());
    //         var_dump('get_complementary_acc_cost: '.$dto->getComplementaryAccCost());
    //         var_dump('get_cost:                   '.$dto->getCost());
    //         die();
    //     }

    //     // if ($dto->getMargin() >= -30)
    //     {
    //         $array = array(
    //             'local_sku' => $sku,
    //             'based_on' => $required_selling_price,
    //             'get_margin' => $dto->getMargin(),

    //             'get_price' => $dto->getPrice(),

    //             'get_delivery_cost' => $dto->getDeliveryCost(),
    //             'get_declared_value' => $this->to2Decimal($dto->getDeclaredValue()),

    //             'get_vat_percent' => $dto->getVatPercent(),
    //             'get_vat' => $dto->getVat(),

    //             'get_sales_commission' => $dto->getSalesCommission(),

    //             'get_duty_pcent' => $dto->getDutyPcent(),
    //             'get_duty' => $dto->getDuty(),

    //             'get_payment_charge_percent' => $dto->getPaymentChargePercent(),
    //             'get_payment_charge' => $dto->getPaymentCharge(),

    //             'get_forex_fee_percent' => $dto->getForexFeePercent(),
    //             'get_forex_fee' => $dto->getForexFee(),

    //             'get_listing_fee' => $dto->getListingFee(),

    //             'get_logistic_cost' => $dto->getLogisticCost(),
    //             'get_supplier_cost' => $dto->getSupplierCost(),

    //             'get_complementary_acc_cost' => $dto->getComplementaryAccCost(),

    //             'get_cost' => $dto->getCost(),
    //             'get_price' => $this->to2Decimal($dto->getPrice()),
    //             'get_profit' => $this->to2Decimal($dto->getPrice()) - $dto->getCost(),

    //         );
    //     }

    //     return json_encode($array);
    // }

    // public function updateSkuPrice($platform_id = '', $local_sku = '', $price = '', $vb_price = '', $commit = false)
    // {
    //     $affected = $this->getDao('Price')->updateSkuPrice($platform_id, $local_sku, $price, $vb_price, $commit);

    //     //print $this->get_dao()->db->last_query();

    //     if ($affected) {
    //         $this->getService('PriceMargin')->refreshAllPlatformMargin(array('id' => $platform_id), $local_sku);
    //     }

    //     return $affected;
    // }
}
