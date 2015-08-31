<?php
namespace AtomV2\Service;

use AtomV2\Dao\PriceDao;
use AtomV2\Dao\ProductComplementaryAccDao;
use AtomV2\Service\FreightCatService;
use AtomV2\Service\ProductService;
use AtomV2\Service\WeightCatService;
use AtomV2\Service\ContextConfigService;
use AtomV2\Service\SubjectDomainService;

class PriceService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
        $this->setDao(new PriceDao);
        $this->setCaDao(new ProductComplementaryAccDao);
        $this->freightCatService = new FreightCatService;
        $this->productService = new ProductService;
        $this->weightCatService = new WeightCatService;
        $this->configService = new ContextConfigService;
        $this->subjectDomainService = new SubjectDomainService;
    }

    public function getListingInfoList($sku_arr = [], $platform_id = "", $lang_id = 'en', $option = [])
    {
        set_time_limit(600);
        ini_set("memory_limit", "500M");

        if (empty($sku_arr)) {
            return false;
        } else {
            foreach ($sku_arr as $obj) {
                $sku_list[$obj->getSku()] = '';
            }
        }

        if ($result = $this->getDao()->getListingInfo($sku_list, $platform_id, $lang_id, $option)) {
            if (is_array($result)) {
                foreach ($result as $obj) {
                    $obj->setPrice(random_markup($obj->getPrice()));
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

    public function getPrice(&$prod)
    {
        if ($prod->getPrice()) {
            return $prod->getPrice();
        } else {
            return $this->getWDefaultPrice($prod->getSku(), $prod->getPlatform_id());
        }
    }

    public function getWDefaultPrice($sku, $platform_id)
    {
        $price_obj = $this->getDao()->get(["sku" => $sku, "platform_id" => $platform_id]);
        if (!$price_obj || !(call_user_func([$price_obj, "get_price"]) * 1)) {
            if (!($default_obj = $this->getDao()->getDefaultConvertedPrice(["sku" => $sku, "platform_id" => $platform_id]))) {
                return 0;
            }
            $default_platform_converted_price = $default_obj->get_default_platform_converted_price();
        }
        return $default_platform_converted_price ? $default_platform_converted_price : $price_obj->get_price();
    }

    public function calcLogisticCost(&$dto)
    {
        if ($lc = $this->freightCatService->getFccDao()->calcLogisticCost($dto->getPlatformId(), $dto->getSku())) {
            $dto->setLogisticCost($lc['converted_amount']);
        } else {
            $dto->setLogisticCost(0);
        }
    }

    public function calculateProfit($dto = null)
    {
        $this->initDto($dto);
        $this->checkDtoPrice();
        $this->calcDeliveryCharge();
        $this->calcCost();

        $price = $dto->getPrice();

        $this->performBusinessLogic($dto, 5, $price);
        return;

        $profit = $price + $dto->getDeliveryCharge() - $dto->getCost();

        if ($price > 0) {
            $margin = number_format($profit / ($price + $dto->getDeliveryCharge()) * 100, 2, ".", "");
        } else {
            $margin = 0;
        }
        $dto->setProfit(number_format($profit, 2, ".", ""));
        $dto->setMargin($margin);
    }

    private function performBusinessLogic($dto, $value_to_return, $required_selling_price = -1, $required_cost_price = -1)
    {
        return $this->performBusinessLogicV2($dto, $value_to_return, $required_selling_price, $required_cost_price);
    }

    private function performBusinessLogicV2($dto, $value_to_return, $required_selling_price = -1, $required_cost_price = -1)
    {
        if ($dto == null) {
            $this->bt();
        }

        $required_margin = -1;
        if ($required_selling_price <= 0)
            $required_margin = $dto->getSubCatMargin() / 100;

        $this->calcComplementaryAccCost($dto);
        $this->calcLogisticCost($dto);
        $this->calcForexFee($dto);
        $this->calcCommission($dto);

        for (; ;) {
            $c = 0;
            $x = 0;
            $b = $required_selling_price;
            $bc = $b + $c;

            $l = $dto->getLogisticCost();
            $d1 = $dto->getPaymentChargePercent() / 100;
            $f1 = $dto->getForexFeePercent() / 100;
            $f2 = $f1 * $bc;

            $ca = 0;
            if (method_exists($dto, "getComplementaryAccCost")) {
                $ca = $dto->getComplementaryAccCost();
            }

            if ($required_cost_price != -1) {
                $k = $required_cost_price;
                $dto->set_supplier_cost($k);
            } else
                $k = $dto->getSupplierCost();

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
            if ($bc > 0)
                $margin = $profit / $bc * 100;
            else
                $margin = 0;

            if ($required_margin >= 0) {
                if ($required_selling_price < $total_cost_d) {
                    $required_selling_price = $total_cost_d;
                    $increment_unit = $total_cost_d * 1 / 100;
                    if ($increment_unit <= 0) $increment_unit = 0.1;
                }
                $b = $required_selling_price;
                $bc = $b + $c;

                if ($margin >= ($required_margin * 100)) break;
                $required_selling_price += $increment_unit;
            } else
                break;
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

        if ($value_to_return == 4) return $total_cost_d;

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

        echo "<hr><pre>";
        var_dump("a: " . $a);
        var_dump("required_selling_price: " . $required_selling_price);

        echo "<hr><pre>";
        var_dump("k: " . $k);
        var_dump("l: " . $l);
        var_dump("v: " . $v);
        var_dump("d1: " . $d1);
        var_dump("d2: " . $d2);
        var_dump("f1: " . $f1);
        var_dump("f2: " . $f2);
        var_dump("h (declared_pcent): " . $h);
        var_dump("a1: " . $a1);
        var_dump("y: " . $y);

        var_dump("value_to_declare: " . $value_to_declare);
        var_dump("declared_value: " . $declared_value);
        var_dump("get_vat: " . $dto->getVat());
        var_dump("get_vat_percent: " . $dto->getVatPercent());

        var_dump("get_sales_commission: " . $dto->getSalesCommission());

        var_dump("total_cost(d): " . $total_cost_d);

        var_dump("Selling_price: " . $selling_price);
    }

    private function to2Decimal($value)
    {
        return number_format($value, 2, ".", "");
    }

    public function calcComplementaryAccCost(&$dto)
    {
        if (method_exists($dto, "setComplementaryAccCost")) {
            $total_cost = 0;

            $check_platform_arr = array("WEB");
            $platform_id = $dto->getPlatformId();
            $platform_country_id = $dto->getPlatformCountryId();

            {
                $mainprod_sku = $dto->getSku();
                $where["pca.dest_country_id"] = $platform_country_id;
                $where["pca.mainprod_sku"] = $mainprod_sku;
                $where["pca.status"] = 1;

                if ($mapped_ca_list = $this->getCaDao()->getMappedAccListWName($where)) {
                    foreach ($mapped_ca_list as $caobj) {
                        $cadto = $this->getDao()->getPriceCostDto($caobj->getAccessorySku(), $dto->getPlatformId());
                        $total_cost += $cadto->getSupplierCost();
                    }
                }
            }

            $dto->setComplementaryAccCost($total_cost);
        }
    }

    public function calcDeliveryCharge($dto = NULL)
    {
        $this->initDto($dto);

        if ($this->productService->getProductTypeDao()->getNumRows(["sku" => $dto->getSku(), "type_id" => "VIRTUAL"])) {
            $delivery_charge = 0;
        } else {
            $delivery_charge = $this->weightCatService->getWccDao()->getCountryWeightChargeByPlatform($dto->getPlatformId(), $dto->getProdWeight(), $this->configService->valueOf("default_delivery_type"));
        }
        $dto->setDefaultDeliveryCharge($delivery_charge);
        $fdl = $dto->getFreeDeliveryLimit();
        if ($dto->getPrice() > $fdl * 1) {
            $dto->setDeliveryCharge(0);
        } else {
            $dto->setDeliveryCharge($delivery_charge);
        }
    }

    public function checkDtoPrice($dto = NULL)
    {
        $this->initDto($dto);

        if ($dto->getPrice()) {
            $price = $dto->getPrice();
        } else {
            $price_obj = $this->getDao()->get(["sku" => $dto->get_sku(), "platform_id" => $dto->getPlatformId()]);
            if ($price_obj) {
                $price = $price_obj->get_price();
                $dto->set_current_platform_price($price);
                if (!($price * 1)) {
                    if ($default_obj = $this->getDao()->getDefaultConvertedPrice(["sku" => $dto->get_sku(), "platform_id" => $dto->getPlatformId()])) {
                        $default_platform_converted_price = $default_obj->get_default_platform_converted_price();
                        $price = $default_platform_converted_price;
                        $dto->set_default_platform_converted_price($price);
                    }
                }
                $dto->set_price($price);
            } else {
                $dto->set_price(0);
                $price = 0;
            }
        }
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

    public function calcAutoPriceValue()
    {
        $recalVat = 0;
        $this->initDto($dto);
        $tmp_cost = $dto->getSupplierCost() + $dto->getLogisticCost() + $dto->getListingFee();
        $markup_percent = $dto->getSubCatMargin() + $dto->getPlatformCommission() + $dto->getPaymentChargePercent() + $dto->getForexFeePercent();
        $auto_declared = $tmp_cost / (1 - ($markup_percent / 100)) * ($dto->getDeclaredPcent() / 100);
        $country_id = substr($dto->getPlatformId(), -2);
        if ($obj = $this->subjectDomainService->getDao()->get(["subject" => "MAX_DECLARE_VALUE.{$country_id}"])) {
            $max_value = $obj->get_value();
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
                $dto->set_vat_percent(15);
                $recalVat = $auto_declared * $dto->getVatPercent() / 100 + 38.07;
            }
        }
        $dto->setAutoTotalCharge($auto_total_charge + $recalVat);
    }

    public function calcForexFee($dto = NULL)
    {
        $this->initDto($dto);
        $dto->setForexFee(number_format(($dto->getPrice() + $dto->getDeliveryCharge()) * $dto->getForexFeePercent() / 100, 2, ".", ""));
    }

    public function calcVat($dto = NULL)
    {
        $this->initDto($dto);

        if ($dto->getPlatformCountryId() == "NZ") {
            $value = $dto->getPrice();# + $dto->get_delivery_charge();

            if ($value > 400) {
                $dto->setVatPercent(0);
                $dto->setVat(number_format(($dto->getDeclaredValue() * $dto->getVatPercent() / 100), 2, ".", ""));
            } else {
                $dto->setVatPercent(0);
                $dto->setVat(0.00);
            }

            if (1 == 0) {
                if ($dto->getDeclaredValue() > 400) {
                    $dto->setVat(number_format((($dto->getDeclaredValue()) * $dto->getVatPercent() / 100) + 38.07, 2, ".", ""));
                } else
                    $dto->set_vat(0.00);
            }
        } else {
            $dto->setVat(number_format(($dto->getDeclaredValue()) * $dto->getVatPercent() / 100, 2, ".", ""));
        }
    }

    public function calcDuty($dto = NULL)
    {
        $this->initDto($dto);
        $duty = number_format($dto->getDeclaredValue() * $dto->getDutyPcent() / 100, 2, ".", "");
        $dto->setDuty($duty);
    }

    public function calcPaymentCharge($dto = NULL)
    {
        $this->initDto($dto);
        $dto->setPaymentCharge(number_format(($dto->getPrice() + $dto->getDeliveryCharge()) * $dto->getPaymentChargePercent() / 100, 2, ".", ""));
    }

    public function calcCommission($dto = NULL)
    {
        $this->initDto($dto);
        $dto->setSalesCommission(number_format(($dto->getPrice() + $dto->getDeliveryCharge()) * $dto->getPlatformCommission() / 100, 2, ".", ""));
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
        } elseif ($obj = $this->subjectDomainService->getDao()->get(["subject" => "MAX_DECLARE_VALUE.{$dto->get_platform_country_id()}"])) {
            $dto->setDeclaredPcent(100);
            $max_value = $obj->getValue();
            $declared = min($max_value, $value);
        } else {
            $declared = $value * $dto->getDeclaredPcent() / 100;
        }
        $dto->setDeclaredValue($declared);
    }


    public function calculateDeclaredValue($prod_obj = "", $country_id = "", $price = "")
    {
        $max_declared_value = -1;
        $declared_pcent = 100;
        $declared = -1;

        $this->declared_value_debug = "";

        #if($prod_obj)
        {
            switch ($country_id) {
                case "AU":
                    if ($price < 950)
                        $declared_pcent = 100;
                    else
                        $max_declared_value = 950;
                    break;

                case "SG":
                    if ($price < 350)
                        $declared_pcent = 100;
                    else
                        $max_declared_value = 350;
                    break;

                case "NZ":
                    if ($price >= 400) {
                        $declared_pcent = 80;
                    } else {
                        $declared_pcent = 100;
                    }
                    break;

                // based on SBF#1790
                case "AE":
                case "AF":
                case "AG":
                case "AI":
                case "AM":
                case "AN":
                case "AO":
                case "AQ":
                case "AR":
                case "AS":
                case "AW":
                case "AZ":
                case "BB":
                case "BD":
                case "BF":
                case "BH":
                case "BI":
                case "BJ":
                case "BM":
                case "BN":
                case "BO":
                case "BR":
                case "BS":
                case "BT":
                case "BV":
                case "BW":
                case "BZ":
                case "CA":
                case "CC":
                case "CD":
                case "CF":
                case "CG":
                case "CI":
                case "CK":
                case "CL":
                case
                "CM":
                case "CN":
                case "CO":
                case "CR":
                case "CU":
                case "CV":
                case "CX":
                case "CY":
                case "DJ":
                case "DM":
                case "DO":
                case "DZ":
                case "EC":
                case "EG":
                case "EH":
                case "ER":
                case "ET":
                case "FJ":
                case "FK":
                case "FM":
                case "GA":
                case "GD":
                case "GE":
                case "GF":
                case "GH":
                case "GL":
                case "GM":
                case "GN":
                case "GP":
                case "GQ":
                case "GS":
                case "GT":
                case "GU":
                case "GW":
                case "GY":
                case "HK":
                case
                "HM":
                case "HN":
                case "HT":
                case "ID":
                case "IL":
                case "IN":
                case "IO":
                case "IQ":
                case "IR":
                case "JM":
                case "JO":
                case "JP":
                case "KE":
                case "KG":
                case "KH":
                case "KI":
                case "KM":
                case "KN":
                case "KP":
                case "KR":
                case "KW":
                case "KY":
                case "KZ":
                case "LA":
                case "LB":
                case "LC":
                case "LK":
                case "LR":
                case "LS":
                case "LY":
                case "MA":
                case "ME":
                case "MG":
                case "MH":
                case "ML":
                case "MM":
                case
                "MN":
                case "MO":
                case "MP":
                case "MQ":
                case "MR":
                case "MS":
                case "MU":
                case "MV":
                case "MW":
                case "MX":
                case "MY":
                case "MZ":
                case "NA":
                case "NC":
                case "NE":
                case "NF":
                case "NG":
                case "NI":
                case "NP":
                case "NR":
                case "NU":
                case "OM":
                case "PA":
                case "PE":
                case "PF":
                case "PG":
                case "PH":
                case "PK":
                case "PM":
                case "PN":
                case "PR":
                case "PS":
                case "PW":
                case "PY":
                case "QA":
                case
                "RE":
                case "RU":
                case "RW":
                case "SA":
                case "SB":
                case "SC":
                case "SD":
                case "SH":
                case "SL":
                case "SN":
                case "SO":
                case "SR":
                case "ST":
                case "SV":
                case "SY":
                case "SZ":
                case "TC":
                case "TD":
                case "TF":
                case "TG":
                case "TH":
                case "TJ":
                case "TK":
                case "TL":
                case "TM":
                case "TN":
                case "TO":
                case "TR":
                case "TT":
                case "TV":
                case "TW":
                case "TZ":
                case "UG":
                case "UM":
                case "US":
                case
                "UY":
                case "UZ":
                case "VC":
                case "VE":
                case "VG":
                case "VI":
                case "VN":
                case "VU":
                case "WF":
                case "WS":
                case "YE":
                case "YT":
                case "ZA":
                case "ZM":
                case "ZW":
                    $declared_pcent = 10;
                    break;
                case "AD":
                case "AL":
                case "AT":
                case "AX":
                case "BA":
                case "BE":
                case "BG":
                case "BL":
                case "BY":
                case "CH":
                case "CZ":
                case "DE":
                case "DK":
                case "EE":
                case "ES":
                case "FI":
                case "FO":
                case "FR":
                case "GG":
                case
                "GI":
                case "GR":
                case "HR":
                case "HU":
                case "IE":
                case "IM":
                case "IS":
                case "IT":
                case "JE":
                case "LI":
                case "LT":
                case "LU":
                case "LV":
                case "MC":
                case "MD":
                case "MF":
                case "MK":
                case "MT":
                case "NL":
                case "NO":
                case
                "PL":
                case "PT":
                case "RO":
                case "RS":
                case "SE":
                case "SI":
                case "SJ":
                case "SK":
                case "SM":
                case "UA":
                case "VA":
                case "GB":
                    $declared_pcent = 10;
                    break;
                // $declared_pcent = 30;
                // break;

                default:    # all other countries
                    $declared_pcent = 10;
                    break;
                    if ($fc_obj = $this->freightCatService->getDao()->get(["id" => $prod_obj->get_freight_cat_id()])) {
                        $declared_pcent = $fc_obj->get_declared_pcent();
                        $this->declared_value_debug .= "1. declared pcent is $declared_pcent\r\n";
                    } else {
                        // default value
                        $declared_pcent = $this->configService->valueOf("default_declared_pcent");
                        $this->declared_value_debug .= "2. declared pcent is $declared_pcent\r\n";
                    }

                    if ($obj = $this->subjectDomainService->getDao()->get(["subject" => "MAX_DECLARE_VALUE.{$country_id}"])) {
                        $max_value = $obj->get_value();
                        $declared = min($max_value, $price);
                        $this->declared_value_debug .= "3. (max, price, chosen) is ($max_value, $price, $declared)\r\n";
                    } else {
                        $declared = $price * $declared_pcent / 100;
                        $this->declared_value_debug .= "4. (price, declared_pcent, final) is ($price, $declared_pcent, $declared)\r\n";
                    }
            }

            if ($declared == -1) {
                # we have to use max declared value
                if ($max_declared_value != -1) {
                    if ($price > $max_declared_value)
                        $declared = $max_declared_value;
                    else
                        $declared = $price;
                } else {
                    # we have to use declared percent
                    $declared = $price * $declared_pcent / 100;
                }
                $prod_obj->setDeclaredPcent($declared_pcent);
            }
        }

        $this->declared_value_debug .= "5. declared_at $declared\r\n";
        return $declared;
    }

    public function initDto(&$dto)
    {
        if (is_null($dto)) {
            $dto = $this->getDto();
        } else {
            $this->setDto($dto);
        }
    }

    public function getCaDao()
    {
        return $this->caDao;
    }

    public function setCaDao($value)
    {
        $this->caDao = $value;
    }

    public function getDto()
    {
        return $this->dto;
    }

    public function setDto($dto)
    {
        $this->dto = $dto;
    }

    public function get_tool_path()
    {
        return $this->tool_path;
    }

    public function set_tool_path($value)
    {
        $this->tool_path = $value;
        return $this;
    }
}
