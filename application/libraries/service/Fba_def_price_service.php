<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "base_price_service.php";

class Fba_def_price_service extends Base_price_service
{
    private $supplier_service;
    private $supplier_region;
    private $supplier_fc;
    private $customer_fc;
    private $ccountry;
    private $weight_cat;
    private $price;

    private $valid_platform = array("AMUK", "AMFR", "AMDE", "AMUS");    // available platform for AMAZON

    public function Fba_def_price_service($supplier_region = "", $supplier_fc = "", $customer_fc = "", $ccountry = "", $weight_cat = "", $price = "")
    {
        parent::Base_price_service();
        include_once(APPPATH . "libraries/service/Exchange_rate_service.php");
        $this->set_exchange_rate_service(new Exchange_rate_service());
        include_once APPPATH . "libraries/dto/product_cost_dto.php";
        $this->set_dto(new Product_cost_dto());
        if ($supplier_region != "") {
            $this->supplier_region = $supplier_region;
        }
        if ($supplier_fc != "") {
            $this->supplier_fc = $supplier_fc;
        }

        if ($customer_fc != "") {
            $this->customer_fc = $customer_fc;
        }

        if ($ccountry != "") {
            $this->ccountry = $ccountry;
        }

        if ($weight_cat != "") {
            $this->weight_cat = $weight_cat;
        }

        if ($price != "") {
            $this->price = $price;
        }

        include_once APPPATH . "libraries/service/Supplier_service.php";
        $this->set_supplier_service(new Supplier_service());
    }

    public function set_exchange_rate_service(Base_service $svc)
    {
        $this->exchange_rate_service = $svc;
    }

    public function get_supp_to_fc_cost($freight_cat = "", $supplier = "")
    {
        if ($this->supplier_fc == "" || $this->supplier_region == "") {
            if ($supplier != "" || $freight_cat == "") {
                $supplier_obj = $this->get_supplier_service()->get_dao()->get(array("id" => $supplier));
                //$sfc = @call_user_func(array($supplier_obj, "get_warehouse_id"));
                $sfc = @call_user_func(array($supplier_obj, "get_fc_id"));
                $sr = @call_user_func(array($supplier_obj, "get_supplier_region"));
            } else {
                //no actual supplier provided
                return FALSE;
            }
        } else {
            $sfc = $this->supplier_fc;
            $sr = $this->supplier_region;
        }

        list($loc, $tmp) = explode('_', $sfc);
        $courier_id = 'FR_' . $loc . 'FC';

        $charge = $this->get_fcc_service()->get_fcc_dao()->get(array("fcat_id" => $this->weight_cat, "region_id" => $sr, "courier_id" => $courier_id));

        if ($charge) {
            return array("amount" => $charge->get_amount(), "currency" => $charge->get_currency_id());
        } else {
            return array("amount" => 0, "currency" => "");
        }
    }

    public function get_supplier_service()
    {
        return $this->supplier_service;
    }

    public function set_supplier_service(Base_service $svc)
    {
        $this->supplier_service = $svc;
        return $this;
    }

    public function get_wh_fc_cost($freight_cat = "", $from_wh = "", $to_wh = "", $has_enf = false)
    {
        if ($this->supplier_fc == "" || $this->customer_fc == "") {
            if ($from_wh == "" || $to_wh == "" || $freight_cat == "") {
                return FALSE;
            }

            //$fwh_obj = $this->get_warehouse_service()->get_dao()->get(array("id"=>$from_wh));
            //$twh_obj = $this->get_warehouse_service()->get_dao()->get(array("id"=>$to_wh));

            //$sfc = substr(0,2,$fwh_obj->get_warehouse_id());
            //$cfc = substr(0,2,$twh_obj->get_warehouse_id());
            $sfc = substr(0, 2, $from_wh);
            $cfc = substr(0, 2, $to_wh);

        } else {
            $tf1 = explode("_", $this->supplier_fc);
            $cf1 = explode("_", $this->customer_fc);
            $sfc = $tf1[0];
            $cfc = $cf1[0];
            unset($tf1);
            unset($cf1);
        }

        $weight_cat = $this->weight_cat;
        $courier_id = "BULK_" . $sfc . "_" . $cfc;

        $charge = $this->get_fcc_service()->get_fcc_dao()->get(array("courier_id" => $courier_id, "fcat_id" => $weight_cat));
        if ($charge) {
            // convert the charge to platform currency
            $ex_rate = $this->get_exchange_rate_service()->get_dao()->get(array("to_currency_id" => $this->platform_curr_id, "from_currency_id" => $charge->get_currency_id()));
            if ($ex_rate) {
                $charge = $charge->get_amount() * $ex_rate->get_rate();
            } else {
                var_dump("Line " . __LINE__ . ": ex_rate does not exist");
            }
        } else {
            $charge = 0;
        }

        // efn cost for Amazon
        $efn_hash_map = $this->get_efn_hash_map();
        $efn_cost = $efn_hash_map[$this->platform_id][$this->fulfillment_centre_id];

        $total_cost = $charge + $efn_cost * 1;

        if ($total_cost) {
            return array("amount" => $total_cost, "currency" => $this->platform_curr_id, "efn_cost" => $efn_cost);
        } else {
            return array("amount" => 0, "currency" => "");
        }
    }

    public function get_exchange_rate_service()
    {
        return $this->exchange_rate_service;
    }

    public function get_efn_hash_map($platform_id = "")
    {
        if (!($platform_id == "" || in_array($platform_id, $this->valid_platform))) {
            return false;
        }

        // hash map for efn cost
        $efn_cost["AMUK"] = array("DEFAULT" => 0, "AMAZON_EU" => 0, "AMAZON_NA" => null);
        $efn_cost["AMFR"] = array("DEFAULT" => 0, "AMAZON_EU" => 2.4, "AMAZON_NA" => null);
        $efn_cost["AMDE"] = array("DEFAULT" => 0, "AMAZON_EU" => 2.6, "AMAZON_NA" => null);
        $efn_cost["AMUS"] = array("DEFAULT" => 0, "AMAZON_EU" => null, "AMAZON_NA" => 0);

        if ($platform_id != "") {
            return $efn_cost[$platform_id];
        } else {
            return $efn_cost;
        }
    }

    public function get_fc_to_customer_cost($fc = "", $ccountry = "")
    {
        if ($this->ccountry == "" || $this->customer_fc == "") {
            if ($weight_cat == "" || $fc == "" || $cregion == "") {
                return FALSE;
            }

        } else {
            $ccountry = $this->ccountry;
            $fc = $this->customer_fc;
        }

        $weight_cat = $this->get_wcc_service()->get_wc_from_fc($this->weight_cat);
        list($fcloc, $t) = explode("_", $fc);
        $courier_id = "DHL_" . $fcloc;
        $dregion = $this->get_region_service()->get_dao()->get_dregion($courier_id, $ccountry);

        // For Amazon Postage is FBA Cost
        $cost = $this->get_fba_cost($this->platform_id);
        //$cost = $this->get_wcc_service()->get_wcc_dao()->get(array("courier_id"=>$courier_id,"wcat_id"=>$weight_cat,"region_id"=>$dregion,'type'=>'CO'));

        $charge = $this->get_wcc_service()->get_wcc_dao()->get(array("courier_id" => $courier_id, "wcat_id" => $weight_cat, "region_id" => $dregion, 'type' => 'CH'));
        $ret = array("coamount" => 0, "cocurrency" => "", "chamount" => 0, "chcurrency" => "");
        if ($cost) {
            $ret["coamount"] = $cost["total"];
            $ret["cocurrency"] = $cost["currency"];
        }
        if ($charge) {
            $ret["chamount"] = $charge->get_amount();
            $ret["chcurrency"] = $charge->get_currency_id();
        }

        return $ret;
    }

    public function get_fba_cost($platform_id = "")
    {
        if (!($platform_id == "" || in_array($platform_id, $this->valid_platform))) {
            return false;
        }

        // FBA cost consist of 3 components
        // 1) Order Handling (fixed cost)
        // 2) Pick & Pack (fixed cost)
        // 3) Weight Handling (variated cost based on weight)

        $freight_cat_obj = $this->get_fcc_service()->get_dao()->get(array("id" => $this->weight_cat));
        $weight = $freight_cat_obj ? $freight_cat_obj->get_weight() : 0;

        // AMUK
        if ($this->price * 1 < 300) {
            $order_handling["AMUK"] = 0.25;
            $pick_and_pack["AMUK"] = 0.6;

            if ($weight <= 2) {
                // $0.01 per 100g
                $weight_handling["AMUK"] = ceil($weight / 0.1) * 0.1;
            } else {
                // $2.00 + $0.02 per 100g above the first 2kg
                $weight_handling["AMUK"] = 2 + ceil(($weight - 2) / 0.1) * 0.02;
            }

            $fba_cost["AMUK"]["currency"] = "GBP";
            $fba_cost["AMUK"]["total"] = $order_handling["AMUK"] + $pick_and_pack["AMUK"] + $weight_handling["AMUK"];
        } else {
            $fba_cost["AMUK"]["currency"] = "";
            $fba_cost["AMUK"]["total"] = 0;
        }

        // AMFR
        if ($this->price * 1 < 350) {
            $order_handling["AMFR"] = 1;
            $pick_and_pack["AMFR"] = 0.75;

            if ($weight < 0.25) {
                $weight_handling["AMFR"] = 0.5;
            } elseif ($weight >= 0.25 && $weight < 0.5) {
                $weight_handling["AMFR"] = 1;
            } else {
                // over 500g or more
                // $1.00 + $1.25 per kg above 500g
                $weight_handling["AMFR"] = 1 + ceil(($weight - 0.5) / 1) * 1.25;
            }

            $fba_cost["AMFR"]["currency"] = "EUR";
            $fba_cost["AMFR"]["total"] = $order_handling["AMFR"] + $pick_and_pack["AMFR"] + $weight_handling["AMFR"];
        } else {
            $fba_cost["AMFR"]["currency"] = "";
            $fba_cost["AMFR"]["total"] = 0;
        }

        // AMDE
        if ($this->price * 1 < 350) {
            $order_handling["AMDE"] = 1;
            $pick_and_pack["AMDE"] = 0.7;

            switch ($weight) {
                case ($weight < 0.1):
                    $weight_handling["AMDE"] = 0;
                    break;
                case ($weight >= 0.1 && $weight < 0.5):
                    $weight_handling["AMDE"] = 0.5;
                    break;
                case ($weight >= 0.5 && $weight < 1):
                    $weight_handling["AMDE"] = 0.6;
                    break;
                case ($weight >= 1 && $weight < 2):
                    $weight_handling["AMDE"] = 1.8;
                    break;
                case ($weight >= 2 && $weight < 5):
                    $weight_handling["AMDE"] = 2.3;
                    break;
                case ($weight >= 5 && $weight < 10):
                    $weight_handling["AMDE"] = 3.3;
                    break;
                case ($weight >= 10):
                    $weight_handling["AMDE"] = 6.3;
            }

            $fba_cost["AMDE"]["currency"] = "EUR";
            $fba_cost["AMDE"]["total"] = $order_handling["AMDE"] + $pick_and_pack["AMDE"] + $weight_handling["AMDE"];
        } else {
            $fba_cost["AMDE"]["currency"] = "";
            $fba_cost["AMDE"]["total"] = 0;
        }

        // AMUS
        $kg_to_ounce_rate = 35.274;

        if ($this->price * 1 < 25) {
            $order_handling["AMUS"] = 1;
            $pick_and_pack["AMUS"] = 0.75;

            // orders are weighed to the nearest ounce or 1/16 of a pound
            // $0.40 per lb.
            $weight_in_ounce = round($weight * $kg_to_ounce_rate);
            $weight_in_pound = $weight_in_ounce / 16;
            $weight_handling["AMUS"] = $weight_in_pound * 0.4;
        } elseif ($this->price * 1 >= 25 && $this->price * 1 < 300) {
            $order_handling["AMUS"] = 1;
            $pick_and_pack["AMUS"] = 1;

            $weight_in_ounce = round($weight * $kg_to_ounce_rate);
            $weight_in_pound = $weight_in_ounce / 16;
            $weight_handling["AMUS"] = $weight_in_pound * 0.4;
        } elseif ($this->price * 1 >= 300) {
            $order_handling["AMUS"] = 0;
            $pick_and_pack["AMUS"] = 0;
            $weight_handling["AMUS"] = 0;
        }

        $fba_cost["AMUS"]["currency"] = "USD";
        $fba_cost["AMUS"]["total"] = $order_handling["AMUS"] + $pick_and_pack["AMUS"] + $weight_handling["AMUS"];

        if ($platform_id != "") {
            return $fba_cost[$platform_id];
        } else {
            return $fba_cost;
        }
    }

    public function get_platform_id()
    {
        return $this->platform_id;
    }

    public function set_platform_id($platform_id = "")
    {
        $this->platform_id = $platform_id;
    }

    public function get_platform_curr_id()
    {
        return $this->platform_curr_id;
    }

    public function set_platform_curr_id($platform_curr_id = "")
    {
        $this->platform_curr_id = $platform_curr_id;
    }

    public function get_fulfillment_centre_id()
    {
        return $this->fulfillment_centre_id;
    }

    public function set_fulfillment_centre_id($fulfillment_centre_id = "")
    {
        $this->fulfillment_centre_id = $fulfillment_centre_id;
    }
}

?>