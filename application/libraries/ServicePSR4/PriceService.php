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
        $prod_obj = $this->getDao('Price')->getProductPriceWithCost($where, $option);

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

    private function to2Decimal($value)
    {
        return number_format($value, 2, ".", "");
    }

    public function getProfitMarginJson($platform_id, $sku, $required_selling_price, $required_cost_price = -1)
    {
        $dto = $this->getDao('Price')->getProductPriceWithCost(['p.sku'=>$sku, 'pbv.selling_platform_id'=>$platform_id], ['limit'=>1]);

        if ($dto == null) {
            return false;
        }

        $this->performBusinessLogic($dto, $required_selling_price, $required_cost_price);

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

    private function performBusinessLogic($dto, $required_selling_price = -1, $required_cost_price = -1)
    {
        $this->calcComplementaryAccCost($dto);
        $this->calcLogisticCost($dto);

        if ($required_cost_price != -1) {
            $dto->setSupplierCost($required_cost_price);
        }
        $dto->setSupplierCost(521.68);
        $dto->setLogisticCost(20.10);
        $required_margin = -1;

        if ($required_selling_price <= 0) {
            $required_margin = $dto->getSubCatMargin();
        }

        if ($required_margin >= 0) {
            $this->calcAutoPriceByRequiredMargin($required_margin);
        } else {
            $this->calcMarginByPrice($dto, $required_selling_price);
        }
    }

    public function calcAutoPriceByRequiredMargin($required_margin)
    {
        echo $price = $this->getTotalCost($dto);
    }

    public function calcMarginByPrice($dto, $price)
    {
        $total_cost = $this->getTotalCost($dto, $price);

        $total_cost = $this->to2Decimal($total_cost);
        $dto->setCost($total_cost);

        $profit = $price - $total_cost;
        if ($price > 0) {
            $margin = $profit / $price * 100;
        } else {
            $margin = 0;
        }

        $profit = $this->to2Decimal($profit);
        $margin = $this->to2Decimal($margin);

        $dto->setProfit($profit);
        $dto->setMargin($margin);
    }

    public function getTotalCost($dto, $price = 0)
    {
        $dto->setPrice($price);
        $this->calcDeclaredValue($dto);
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

        $total_cost = $supplier_cost + $logistic_cost + $listing_fee + $Sales_commission_cost + $payment_charge_cost + $forex_fee + $vat_cost + $duty_cost + $complementary_acc_cost - $delivery_cost;

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
            if ($cadto = $this->getDao('Price')->getProductPriceWithCost(
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
        if ($tmp_objlist = $this->getDao('Price')->getProductPriceWithCost(['p.sku'=>$sku, "pbv.selling_platform_id in ({$platform_list})"=>null], ['limit'=>-1])) {
            foreach ($tmp_objlist  as $tmp_obj) {

                $this->calculateProfit($tmp_obj);

                $declared_pcent = $this->checkDeclaredPcent($tmp_obj->getPlatformCountryId(), $tmp_obj->getPrice());
                $tmp_obj->setDeclaredPcent($declared_pcent);

                $ret[$tmp_obj->getPlatformId()]["dst"] = $tmp_obj;
            }
        }

        return $ret;
    }

    public function calculateProfit($dto = null)
    {
        // $this->calcLogisticCost($dto);
        $this->calcDtoPrice($dto);
        // $this->calcCost();

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

    public function calcLogisticCost(&$dto)
    {
        if ($lc = $this->getDao('FreightCatCharge')->calcLogisticCost($dto->getPlatformId(), $dto->getSku())) {
            $dto->setLogisticCost($lc['converted_amount']);
        } else {
            $dto->setLogisticCost(0);
        }
    }

    public function calcDtoPrice($dto = null)
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

    public function getDefaultPrice($dto = null)
    {
        $default_price = 0;
        if ($default_obj = $this->getDao('Price')->getDefaultConvertedPrice(["pr.sku" => $dto->getSku(), "pbv.selling_platform_id" => $dto->getPlatformId()], ['limit'=> 1])) {
            $default_price = $default_obj->getDefaultPlatformConvertedPrice();
        }

        return $default_price;
    }

    public function calcDeliveryCharge($dto = null)
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

    public function calcDeclaredValue($dto = NULL)
    {

        $value = $dto->getPrice() + $dto->getDeliveryCharge();

        $this->calculateDeclaredValue($dto, $value);
    }

    public function calcCommission($dto = NULL)
    {
        $dto->setSalesCommission(number_format(($dto->getPrice() + $dto->getDeliveryCharge()) * $dto->getPlatformCommission() / 100, 2, ".", ""));
    }

    public function calcDuty($dto = NULL)
    {
        $duty = number_format($dto->getDeclaredValue() * $dto->getDutyPcent() / 100, 2, ".", "");
        $dto->setDuty($duty);
    }

    public function calcPaymentCharge($dto = NULL)
    {
        $dto->setPaymentCharge(number_format(($dto->getPrice() + $dto->getDeliveryCharge()) * $dto->getPaymentChargePercent() / 100, 2, ".", ""));
    }

    public function calcForexFee($dto = NULL)
    {
        $dto->setForexFee(number_format(($dto->getPrice() + $dto->getDeliveryCharge()) * $dto->getForexFeePercent() / 100, 2, ".", ""));
    }

    public function calcVat($dto = NULL)
    {

        if ($dto->getPlatformCountryId() == "NZ") {
            $dto->setVatPercent(0);

            $value = $dto->getPrice();
            if ($value > 400) {
                $dto->setVat(number_format(($dto->getDeclaredValue() * $dto->getVatPercent() / 100), 2, ".", ""));
            } else {
                $dto->setVat(0.00);
            }
        } else {
            $dto->setVat(number_format(($dto->getDeclaredValue()) * $dto->getVatPercent() / 100, 2, ".", ""));
        }
    }

    public function calcAutoPriceValue($dto = NULL)
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

    public function checkDeclaredPcent($country_id, $price)
    {
        switch ($country_id) {
            case 'GB':
                $declared_pcent = 30;
                break;

            case 'AU':
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

        return $declared_pcent;
    }

}
