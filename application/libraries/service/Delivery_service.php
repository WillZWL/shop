<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Delivery_service extends Base_service
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
    public $item_list = array();
    public $inv_list = array();
    private $exp_valid = 1;
    private $all_virtual_valid = 1;
    private $delivery_charge;
    private $valid_exp_check = 0;
    private $valid_all_virtual_check = 0;
    private $del_opt_srv;
    private $func_opt_srv;
    private $courier_srv;
    private $prod_srv;
    private $del_surcharge_srv;
    private $config;
    private $default_delivery;
    private $promotion_disc_level_value = NULL;
    private $free_default_delivery = FALSE;

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/dao/Delivery_dao.php");
        $this->set_dao(new Delivery_dao());
        include_once(APPPATH . "libraries/service/Delivery_option_service.php");
        $this->set_del_opt_srv(new Delivery_option_service());
        include_once(APPPATH . "libraries/service/Func_option_service.php");
        $this->set_func_opt_srv(new Func_option_service());
        include_once(APPPATH . "libraries/service/Courier_service.php");
        $this->set_courier_srv(new Courier_service());
        include_once(APPPATH . "libraries/service/Delivery_type_service.php");
        $this->set_delivery_type_srv(new Delivery_type_service());
        include_once(APPPATH . "libraries/service/Weight_cat_service.php");
        $this->set_wc_srv(new Weight_cat_service());
        include_once(APPPATH . "libraries/service/Inventory_service.php");
        $this->set_inv_srv(new Inventory_service());
        include_once(APPPATH . "libraries/service/Product_service.php");
        $this->set_prod_srv(new Product_service());
        include_once(APPPATH . "libraries/service/Delivery_surcharge_service.php");
        $this->set_del_surcharge_srv(new Delivery_surcharge_service());
        include_once(APPPATH . "libraries/service/Context_config_service.php");
        $this->set_config(new Context_config_service());
        $this->default_delivery = $this->get_config()->value_of("default_delivery_type");
    }

    public function set_delivery_type_srv($value)
    {
        $this->delivery_type_srv = $value;
    }

    //get latency

    public function set_wc_srv($value)
    {
        $this->wc_srv = $value;
    }

    //get expected delivery date

    public function set_inv_srv($value)
    {
        $this->inv_srv = $value;
    }

    //get the working days for confirmation email, skip handle EXP_SAT currently

    public function get_config()
    {
        return $this->config;
    }

    public function set_config($value)
    {
        $this->config = $value;
    }

    /*
        public function get_courier_list()
        {
            return $this->get_courier_srv()->get_list(array("type"=>"W", "(weight_type = 'CH' OR weight_type = 'B')"=>NULL), array("limit"=>-1, "orderby"=>"id <> '{$this->default_delivery}'"));
        }
    */

    public function get_list_w_key($where = array(), $option = array())
    {
        $data = array();
        if ($obj_list = $this->get_list($where, $option)) {
            foreach ($obj_list as $obj) {
                $data[$obj->get_delivery_type_id()][$obj->get_country_id()] = $obj;
            }
        }
        return $data;
    }

    public function get_edd($delivery_type_id, $country_id, $ts = "")
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

    public function get_latency($delivery_type_id, $country_id)
    {
        if (!($days = $this->get_dao()->get_latency($delivery_type_id, $country_id))) {
            $days = $this->get_config()->value_of("default_delivery_max_day");
        }
        return $days;
    }

    public function get_working_days($delivery_type_id, $country_id)
    {
        $this->valid_all_virtual_items();
        if ($this->all_virtual_valid) {
            return 0;
        } else {
            $max_day = $min_day = null;
            if ($obj = $this->get_dao()->get_list(array("delivery_type_id" => $delivery_type_id, "country_id" => $country_id), array("limit" => 1))) {
                $min_day = $obj->get_min_day();
                $max_day = $obj->get_max_day();
            }
            return $this->correct_working_days($min_day, $max_day);
        }
    }

    protected function valid_all_virtual_items()
    {
        if (!$this->valid_all_virtual_check) {
            if ($this->item_list) {
                $this->all_virtual_valid = $this->get_prod_srv()->check_all_virtual($this->item_list);
            } else {
                $this->all_virtual_valid = 0;
            }
            $this->valid_all_virtual_check = 1;
        }
    }

    public function get_prod_srv()
    {
        return $this->prod_srv;
    }

    public function set_prod_srv($value)
    {
        $this->prod_srv = $value;
    }

    private function correct_working_days($min_day, $max_day, $get_default = 1)
    {
        $days = array();
        if ($min_day) {
            $days[] = $min_day;
        }
        if ($max_day) {
            $days[] = $max_day;
        }

        if (!$days && $get_default) {
            $days[] = $this->get_config()->value_of("default_delivery_min_day");
            $days[] = $this->get_config()->value_of("default_delivery_max_day");
        }
        return @implode("-", $days);
    }

    public function get_delivery_type_list()
    {
        return $this->get_delivery_type_srv()->get_list(array(), array("limit" => -1, "orderby" => "id <> '{$this->default_delivery}'"));
    }

    public function get_delivery_type_srv()
    {
        return $this->delivery_type_srv;
    }

    public function get_delivery_options()
    {
        $ret["dc_courier"] = $ret["dc"] = array();
        $lang_id = get_lang_id();

        if (!($del_opt_list = $this->get_del_opt_srv()->get_list_w_key(array("lang_id" => $lang_id), array("limit" => -1)))) {
            $lang_id = "en";
            $del_opt_list = $this->get_del_opt_srv()->get_list_w_key(array("lang_id" => "en"), array("limit" => -1));
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

        $delivery_list = $this->get_dao()->get_list($where, array("limit" => -1, "orderby" => "delivery_type_id <> '{$this->default_delivery}'"));

        $this->valid_all_virtual_items();

        foreach ($delivery_list as $obj) {
            $cur_courier = $obj->get_delivery_type_id();
            if ($cur_courier != $this->default_delivery) {
                $this->valid_exp_items();
                if (!$this->exp_valid) {
                    continue;
                }
            }

            $ret["dc"][$cur_courier] = array(
                "display_name" => $del_opt_list[$lang_id][$cur_courier]->get_display_name(),
                "working_days" => $this->all_virtual_valid ? 0 : $this->correct_working_days($obj->get_min_day(), $obj->get_max_day(), $this->default_delivery != $cur_courier)
            );

            if ($cur_courier == $this->default_delivery && $skip_get_default_charge) {
                continue;
            }


            if ($this->special) {
                $ret["dc"][$cur_courier]["surcharge"] = 0;
                $ret["dc"][$cur_courier]["charge"] = $this->customised_dc;
            } else {
                $surcharge = $this->get_del_surcharge();
                $ret["dc"][$cur_courier]["surcharge"] = number_format($surcharge, 2, ".", "");
                if ($this->over_free_delivery_limit || ($this->free_delivery && ($this->promotion_disc_level_value == "All" || $this->promotion_disc_level_value == $cur_courier)) || ($this->free_default_delivery && $cur_courier == $this->default_delivery)) {
                    $ret["dc"][$cur_courier]["charge"] = 0;
                } else {
                    $ret["dc"][$cur_courier]["charge"] = number_format(
                        $this->delivery_country_id ? $this->get_wc_srv()->get_wcc_dao()->get_country_weight_charge_by_dest_country($this->platform_id, $this->weight, $cur_courier, $this->delivery_country_id) : $this->get_wc_srv()->get_wcc_dao()->get_country_weight_charge_by_platform($this->platform_id, $this->weight, $cur_courier)
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
            $ret["dc_default"]["working_days"] = $this->correct_working_days(NULL, NULL);
            if (count($ret["dc"]) > 1) {
                $ret["dc"][$this->default_delivery]["working_days"] = $ret["dc_default"]["working_days"];
            }
        }

        $this->set_delivery_charge($ret["dc_default"]["charge"]);
        return $ret;
    }

    public function get_del_opt_srv()
    {
        return $this->del_opt_srv;
    }

    public function set_del_opt_srv($value)
    {
        $this->del_opt_srv = $value;
    }

    protected function valid_exp_items()
    {
        if (!$this->valid_exp_check) {
            if ($this->item_list) {
                foreach ($this->item_list as $sku => $value) {
                    if ($sku_list = $this->get_prod_srv()->get_dao()->get_item_contain($sku)) {
                        foreach ($sku_list as $chk_sku) {
                            if (!isset($this->inv_list[$chk_sku])) {
                                $this->inv_list[$chk_sku] = $this->get_inv_srv()->get_dao()->get_prod_sum_inv_git_by_country($chk_sku, $this->delivery_country_id) * 1;
                                $this->inv_list[$chk_sku] -= $this->get_inv_srv()->get_dao()->get_fc_pending_by_country($chk_sku, $this->delivery_country_id) * 1;
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

    public function get_inv_srv()
    {
        return $this->inv_srv;
    }

    public function get_del_surcharge($return_code_type = FALSE)
    {
        $this->valid_all_virtual_items();
        if ($this->all_virtual_valid) {
            return 0;
        } else {
            $del_surcharge_srv = $this->get_del_surcharge_srv();
            $del_surcharge_srv->delivery_country_id = $this->delivery_country_id;
            $del_surcharge_srv->delivery_state = $this->delivery_state;
            $del_surcharge_srv->delivery_postcode = $this->delivery_postcode;
            return $del_surcharge_srv->get_del_surcharge($return_code_type);
        }
    }

    public function get_del_surcharge_srv()
    {
        return $this->del_surcharge_srv;
    }

    public function set_del_surcharge_srv($value)
    {
        $this->del_surcharge_srv = $value;
    }

    public function get_wc_srv()
    {
        return $this->wc_srv;
    }

    private function set_delivery_charge($value)
    {
        $this->delivery_charge = $value;
    }

    public function get_func_opt_srv()
    {
        return $this->func_opt_srv;
    }

    public function set_func_opt_srv($value)
    {
        $this->func_opt_srv = $value;
    }

    public function get_courier_srv()
    {
        return $this->courier_srv;
    }

    public function set_courier_srv($value)
    {
        $this->courier_srv = $value;
    }

    public function get_delivery_charge()
    {
        return $this->delivery_charge;
    }

    public function get_default_delivery()
    {
        return $this->default_delivery;
    }

    public function get_promotion_disc_level_value()
    {
        return $this->promotion_disc_level_value;
    }

    public function set_promotion_disc_level_value($value)
    {
        $this->promotion_disc_level_value = $value;
    }

    public function set_free_default_delivery($value)
    {
        $this->free_default_delivery = $value;
    }

    private function set_default_delivery($value)
    {
        $this->default_delivery = $value;
    }
}


