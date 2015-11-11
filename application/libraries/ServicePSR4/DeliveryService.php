<?php
namespace ESG\Panther\Service;

class DeliveryService extends BaseService
{
    public $platform_id;
    public $vat_percent = 0;
    public $delivery_country_id;
    public $delivery_state;
    public $delivery_postcode;
    public $free_delivery = FALSE;
    public $over_free_delivery_limit = FALSE;
    public $customised_dc = 0;
    public $weight = 0;
    public $special = 0;
    public $item_list = [];
    public $inv_list = [];
    private $exp_valid = 1;
    private $all_virtual_valid = 1;
    private $delivery_charge;
    private $valid_exp_check = 0;
    private $valid_all_virtual_check = 0;
    private $promotion_disc_level_value = NULL;
    private $free_default_delivery = FALSE;

    public function __construct()
    {
        parent::__construct();
        $this->contextConfigService = new ContextConfigService;
        $this->courierService = new CourierService;
        $this->WeightCatService = new WeightCatService;
        $this->productService = new ProductService;
        $this->deliveryOptionService = new DeliveryOptionService;
        $this->funcOptionService = new FuncOptionService;
        $this->deliveryTypeService = new DeliveryTypeService;
        $this->inventoryService = new InventoryService;
        $this->deliverySurchargeService = new DeliverySurchargeService;

        $this->default_delivery = $this->contextConfigService->valueOf("default_delivery_type");
    }

    public function getListWithKey($where = [], $option = [])
    {
        $data = [];
        if ($objList = $this->getDao('Delivery')->getList($where, $option)) {
            foreach ($objList as $obj) {
                $data[$obj->getDeliveryTypeId()][$obj->getCountryId()] = $obj;
            }
        }
        return $data;
    }

    public function getEdd($delivery_type_id, $country_id, $ts = "")
    {
        if ($ts == "") {
            $ts = mktime();
        }
        $cur_day = date('N', $ts);
        $adj_n = ($cur_day > 5) ? 5 : $cur_day;

        $latency = $this->get_latency($delivery_type_id, $country_id);
        $week_adj = (floor($latency / 5)) * 2;
        $day_adj = ($adj_n + ($latency % 5)) > 5 ? 2 : 0;
        $correct_days = $latency + $week_adj + $day_adj - ($cur_day - $adj_n);
        return date('Y-m-d H:i:s', $ts + 86400 * $correct_days);
    }

    public function getLatency($delivery_type_id, $country_id)
    {
        if (!($days = $this->getDao('Delivery')->getLatency($delivery_type_id, $country_id))) {
            $days = $this->contextConfigService->valueOf("default_delivery_max_day");
        }
        return $days;
    }

    public function getWorkingDays($delivery_type_id, $country_id)
    {
        $this->validAllVirtualItems();
        if ($this->all_virtual_valid) {
            return 0;
        } else {
            $max_day = $min_day = null;
            if ($obj = $this->getDao('Delivery')->getList(["delivery_type_id" => $delivery_type_id, "country_id" => $country_id], ["limit" => 1])) {
                $min_day = $obj->getMinDay();
                $max_day = $obj->getMaxDay();
            }
            return $this->correctWorkingDays($min_day, $max_day);
        }
    }

    protected function validAllVirtualItems()
    {
        if (!$this->valid_all_virtual_check) {
            if ($this->item_list) {
                $this->all_virtual_valid = $this->productService->check_all_virtual($this->item_list);
            } else {
                $this->all_virtual_valid = 0;
            }
            $this->valid_all_virtual_check = 1;
        }
    }

    private function correctWorkingDays($min_day, $max_day, $get_default = 1)
    {
        $days = [];
        if ($min_day) {
            $days[] = $min_day;
        }
        if ($max_day) {
            $days[] = $max_day;
        }

        if (!$days && $get_default) {
            $days[] = $this->contextConfigService->valueOf("default_delivery_min_day");
            $days[] = $this->contextConfigService->valueOf("default_delivery_max_day");
        }
        return @implode("-", $days);
    }

    public function getDeliveryTypeList()
    {
        return $this->deliveryTypeService->getList([], ["limit" => -1, "orderby" => "id <> '{$this->default_delivery}'"]);
    }

    public function get_delivery_options()
    {
        $ret["dc_courier"] = $ret["dc"] = [];
        $lang_id = get_lang_id();

        if (!($del_opt_list = $this->deliveryOptionService->getListWithKey(["lang_id" => $lang_id], ["limit" => -1]))) {
            $lang_id = "en";
            $del_opt_list = $this->deliveryOptionService->getListWithKey(["lang_id" => "en"], ["limit" => -1]);
        }

        $where = array("country_id" => $this->delivery_country_id, "status" => 1);

        $ret["dc_default"]["courier"] = $this->default_delivery;

        $skip_get_default_charge = 0;

        if ($this->special) {
            $ret["dc_default"]["charge"] = $ret["dc"][$this->default_delivery]["charge"] = $this->customised_dc;
            if ($this->vat_exempt) {
                $ret["dc_default"]["vat"] = $ret["dc"][$this->default_delivery]["vat"] = 0;
            } else {
                $ret["dc_default"]["vat"] = $ret["dc"][$this->default_delivery]["vat"] = number_format($ret["dc_default"]["charge"] * ($this->vat_percent / (100 + $this->vat_percent)), 2, ".", "");
            }
            $ret["dc_default"]["surcharge"] = $ret["dc"][$this->default_delivery]["surcharge"] = 0;
            $skip_get_default_charge = 1;
        }

        $delivery_list = $this->getDao('Delivery')->getList($where, ["limit" => -1, "orderby" => "delivery_type_id <> '{$this->default_delivery}'"]);

        $this->validAllVirtualItems();

        foreach ($delivery_list as $obj) {
            $cur_courier = $obj->getDeliveryTypeId();
            if ($cur_courier != $this->default_delivery) {
                $this->validExpItems();
                if (!$this->exp_valid) {
                    continue;
                }
            }

            $ret["dc"][$cur_courier] = array(
                "display_name" => $del_opt_list[$lang_id][$cur_courier]->get_display_name(),
                "working_days" => $this->all_virtual_valid ? 0 : $this->correctWorkingDays($obj->getMinDay(), $obj->getMaxDay(), $this->default_delivery != $cur_courier)
            );

            if ($cur_courier == $this->default_delivery && $skip_get_default_charge) {
                continue;
            }


            if ($this->special) {
                $ret["dc"][$cur_courier]["surcharge"] = 0;
                $ret["dc"][$cur_courier]["charge"] = $this->customised_dc;
            } else {
                $surcharge = $this->getDelSurcharge();
                $ret["dc"][$cur_courier]["surcharge"] = number_format($surcharge, 2, ".", "");
                if ($this->over_free_delivery_limit || ($this->free_delivery && ($this->promotion_disc_level_value == "All" || $this->promotion_disc_level_value == $cur_courier)) || ($this->free_default_delivery && $cur_courier == $this->default_delivery)) {
                    $ret["dc"][$cur_courier]["charge"] = 0;
                } else {
                    $ret["dc"][$cur_courier]["charge"] = number_format(
                        $this->delivery_country_id ? $this->WeightCatService->getWeightCatChargeDao()->getCountryWeightChargeByDestCountry($this->platform_id, $this->weight, $cur_courier, $this->delivery_country_id) : $this->WeightCatService->getWeightCatChargeDao()->getCountryWeightChargeByPlatform($this->platform_id, $this->weight, $cur_courier)
                        , 2, ".", "");

                }
            }

            if ($this->vat_exempt) {
                $ret["dc"][$cur_courier]["vat"] = 0;
            } else {
                $ret["dc"][$cur_courier]["vat"] = number_format($ret["dc"][$cur_courier]["charge"] * ($this->vat_percent / (100 + $this->vat_percent)), 2, ".", "");
            }

            if ($cur_courier == $this->default_delivery) {
                $ret["dc_default"]["charge"] = $ret["dc"][$cur_courier]["charge"];
                $ret["dc_default"]["surcharge"] = $ret["dc"][$cur_courier]["surcharge"];
                $ret["dc_default"]["vat"] = $ret["dc"][$cur_courier]["vat"];
                $ret["dc_default"]["display_name"] = $ret["dc"][$cur_courier]["display_name"];
                $ret["dc_default"]["working_days"] = $ret["dc"][$cur_courier]["working_days"];
            }

        }

        if (!$ret["dc_default"]["working_days"]) {
            $ret["dc_default"]["working_days"] = $this->correctWorkingDays(NULL, NULL);
            if (count($ret["dc"]) > 1) {
                $ret["dc"][$this->default_delivery]["working_days"] = $ret["dc_default"]["working_days"];
            }
        }

        $this->set_delivery_charge($ret["dc_default"]["charge"]);
        return $ret;
    }

    protected function validExpItems()
    {
        if (!$this->valid_exp_check) {
            if ($this->item_list) {
                foreach ($this->item_list as $sku => $value) {
                    if ($sku_list = $this->productService->getDao('Product')->getItemContain($sku)) {
                        foreach ($sku_list as $chk_sku) {
                            if (!isset($this->inv_list[$chk_sku])) {
                                $this->inv_list[$chk_sku] = $this->inventoryService->getDao('inventory')->getProdSumInvGitByCountry($chk_sku, $this->delivery_country_id) * 1;
                                $this->inv_list[$chk_sku] -= $this->inventoryService->getDao('inventory')->getFcPendingByCountry($chk_sku, $this->delivery_country_id) * 1;
                            }
                            if (is_array($value)) {
                                $qty = $value["qty"];
                            } else {
                                $qty = $value;
                            }
                            $this->inv_list[$chk_sku] -= $qty;
                            if ($this->inv_list[$chk_sku] < 0) {
                                $this->exp_valid = 0;
                                break;
                            }
                        }
                    }
                    if (!$this->exp_valid) {
                        break;
                    }
                }
            } else {
                $this->exp_valid = 0;
            }
            $this->valid_exp_check = 1;
        }
    }

    public function getDelSurcharge($return_code_type = FALSE)
    {
        $this->validAllVirtualItems();
        if ($this->all_virtual_valid) {
            return 0;
        } else {
            $deliverySurchargeService->delivery_country_id = $this->delivery_country_id;
            $deliverySurchargeService->delivery_state = $this->delivery_state;
            $deliverySurchargeService->delivery_postcode = $this->delivery_postcode;
            return $deliverySurchargeService->getDelSurcharge($return_code_type);
        }
    }

    public function update($obj, $where = []) {
        return $this->getDao('Delivery')->update($obj, $where);
    }

    public function delete($obj) {
        return $this->getDao('Delivery')->delete($obj);
    }

    public function insert($obj) {
        return $this->getDao('Delivery')->insert($obj);
    }

}


