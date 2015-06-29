<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Price_service.php";

class Price_amazon_service extends Price_service
{
    public function __construct()
    {
        parent::__construct();
        $this->set_tool_path('marketing/pricing_tool_' . strtolower(PLATFORM_TYPE));
    }

    public function calc_delivery_charge($dto = NULL)
    {
        $this->init_dto($dto);

        $dto->set_default_delivery_charge(0);
        $dto->set_delivery_charge(0);
    }

    public function check_profit($dto)
    {
        if ($dto == NULL) {
            return FALSE;
        } else {
            $this->set_dto($dto);
            $this->calc_profit();
            $minprice = $this->get_min_price(6);

            return array("profitable" => round($this->get_dto()->get_profit(), 2) >= 0.00 ? 1 : 0, "minprice" => $minprice);
        }
    }

    public function calc_profit($src = NULL)
    {
        $dto = $src;

        if (!$dto) {
            $dto = $this->get_dto();
        } else {
            $this->set_dto($src);
        }

        //echo $dto->get_price();
        $this->calc_declared_value();
        $this->calc_vat();
        $this->calc_duty();
        $this->calc_payment_charge();
        //$this->calc_delivery_charge();

        $ship_mode_svc = $this->get_price_service_from_dto($dto, $dto->get_price());
        $ship_mode_svc->set_platform_id($dto->get_platform_id());
        $ship_mode_svc->set_platform_curr_id($dto->get_platform_currency_id());
        $ship_mode_svc->set_fulfillment_centre_id($dto->get_fulfillment_centre_id());
        $this->calc_freight_cost($dto, $ship_mode_svc, $dto->get_platform_currency_id());

        $dto->set_cost(
            round($dto->get_vat(), 2)
            + round($dto->get_duty(), 2)
            + round($dto->get_payment_charge(), 2)
            + round($dto->get_admin_fee(), 2)
            + round($dto->get_freight_cost(), 2)
            + round($dto->get_delivery_cost(), 2)
            + round($dto->get_supplier_cost(), 2)
            + round(($dto->get_platform_commission() / 100 * ($dto->get_price() + $dto->get_platform_delivery_charge())), 2)
            + round($dto->get_suppfc_cost(), 2)
            + round($dto->get_whfc_cost(), 2)
            + round($dto->get_fccus_cost(), 2)
        );

        if ($dto->get_price() != "") {
            $price = $dto->get_price() * 1;
            $cost = $dto->get_cost();
            $profit = number_format($price ? $price + $dto->get_platform_delivery_charge() - $cost : 0, 2, ".", "");
            $dto->set_profit($profit);
            $margin = number_format($price && ($price - $dto->get_vat()) ? $profit / ($price - $dto->get_vat()) * 100 : 0, 2, ".", "");
            $dto->set_margin($margin);
        }
    }

    public function calc_freight_cost(Product_cost_dto &$tmp, Base_service $p_srv, $pcurr)
    {
        $sc = $p_srv->get_supp_to_fc_cost();
        if ($sc["amount"]) {
            $ex_rate = $this->get_exchange_rate_service()->get_dao()->get(array("to_currency_id" => $pcurr, "from_currency_id" => $sc["currency"]));
            $tmp->set_suppfc_cost($ex_rate->get_rate() * $sc["amount"]);
        } else {
            $tmp->set_suppfc_cost(0);
        }

        $wc = $p_srv->get_wh_fc_cost();
        if ($wc["amount"]) {
            $ex_rate = $this->get_exchange_rate_service()->get_dao()->get(array("to_currency_id" => $pcurr, "from_currency_id" => $wc["currency"]));
            $tmp->set_whfc_cost(@call_user_func(array($ex_rate, "get_rate")) * $wc["amount"]);
            $tmp->set_amazon_efn_cost($wc["efn_cost"]);
        } else {
            $tmp->set_whfc_cost(0);
        }

        $dc = $p_srv->get_fc_to_customer_cost();
        if ($dc["coamount"]) {
            $ex_rate = $this->get_exchange_rate_service()->get_dao()->get(array("to_currency_id" => $pcurr, "from_currency_id" => $dc["cocurrency"]));
            $tmp->set_fccus_cost(@call_user_func(array($ex_rate, "get_rate")) * $dc["coamount"]);
        } else {
            $tmp->set_fccus_cost(0);
        }

        if ($dc["chamount"]) {
            $ex_rate = $this->get_exchange_rate_service()->get_dao()->get(array("to_currency_id" => $pcurr, "from_currency_id" => $dc["chcurrency"]));
            $tmp->set_delivery_charge(@call_user_func(array($ex_rate, "get_rate")) * $dc["chamount"]);
        } else {
            $tmp->set_delivery_charge(0);
        }
    }

    public function get_min_price($margin, $dto = NULL)
    {
        if (!$dto) {
            $dto = $this->get_dto();
        }
        if ($dto->get_shiptype() == 2) {
            $fc = 0;
            $vat_pcent = 0;
        } else {
            $fc = $dto->get_freight_cost();
            $vat_pcent = $dto->get_vat_percent();
        }
        return number_format(
            (
                round($dto->get_admin_fee(), 2) * ($vat_pcent + 100 - $margin * $vat_pcent / 100)
                + 100 * round($fc, 2)
                + 100 * round($dto->get_delivery_cost(), 2)
                + 100 * round($dto->get_supplier_cost(), 2)
                + 100 * round($dto->get_suppfc_cost(), 2)
                + 100 * round($dto->get_whfc_cost(), 2)
                + 100 * round($dto->get_fccus_cost(), 2)
                + round($dto->get_platform_delivery_charge(), 2) * (round($dto->get_platform_commission(), 2) - 1)
            )
            /
            (
                (100 - $margin) * (1 - $dto->get_declared_pcent() / 100 * $vat_pcent / 100)
                - $dto->get_duty_pcent()
                - $dto->get_payment_charge_percent()
                - $dto->get_platform_commission()
            ) - 0.005
            , 2, ".", "");
    }

    public function get_ixtens_reprice_list($where = array(), $option = array(), $classname = "Product_cost_dto")
    {
        include_once APPPATH . "libraries/dao/Price_extend_dao.php";
        $price_ext_dao = new Price_extend_dao();

        $option["limit"] = "-1";
        if ($objlist = $this->get_product_service()->get_dao()->get_ixtens_repice_list($where, $option, $classname)) {
            $data["min_price"] = $data["sku"] = $data["current_price"] = array();
            foreach ($objlist as $obj) {
                $sku = $obj->get_sku();
                $this->set_dto($obj);
                $this->calc_profit();
                $data["sku"][$sku] = 1;

                // calculate freight cost begins
                $ship_mode_svc = $this->get_price_service_from_dto($obj, $obj->get_price());
                $ship_mode_svc->set_platform_id($obj->get_platform_id());
                $ship_mode_svc->set_platform_curr_id($obj->get_platform_currency_id());

                // get fulfillment centre id for amazon
                $price_ext_obj = $price_ext_dao->get(array("sku" => $obj->get_sku(), "platform_id" => $obj->get_platform_id()));
                if (!$price_ext_obj || !$fc_id = $price_ext_obj->get_fulfillment_centre_id()) {
                    $fc_id = "DEFAULT";
                }
                $ship_mode_svc->set_fulfillment_centre_id($fc_id);

                $this->calc_freight_cost($obj, $ship_mode_svc, $obj->get_platform_currency_id());
                // calculate freight cost ends

                $data["min_price"][$sku] = $this->get_min_price(6, $obj);
                $data["current_price"][$sku] = $obj->get_price();
            }

            return $data;
        }
        return FALSE;
    }

    public function get_product_overview_tr($where = array(), $option = array(), $classname = "Product_cost_dto", $lang = array())
    {
        include_once(APPPATH . "libraries/service/Ixten_reprice_rule_service.php");
        $this->set_irr_srv(new Ixten_reprice_rule_service());

        if ($option["refresh_margin"]) {
            $this->get_price_margin_service()->refresh_margin_amazon($where["platform_id"]);
        }
        if ($objlist = $this->get_product_service()->get_dao()->get_product_overview($where, $option, $classname)) {
            include_once BASEPATH . "helpers/url_helper.php";
            $list_where["status"] = 1;
            if ($where["platform_id"]) {
                $list_where["id"] = $where["platform_id"];
            } elseif (defined(PLATFORM_TYPE)) {
                $list_where["type"] = PLATFORM_TYPE;
            }

            $platform_list = $this->get_platform_biz_var_service()->selling_platform_dao->get_list($list_where, array("limit" => -1));
            $data["tr"] = $data["js"] = "";
            foreach ($platform_list as $platform_obj) {
                $ca[] = "'" . $platform_obj->get_id() . "':new Array()";
            }

            $data["js"] .= "
                            <script>
                                var prod = new Array();
                                prod = {" . implode(",", $ca) . "};
                            ";
            unset($ca);
            $ar_ws_status = array("I" => $lang["instock"], "O" => $lang["outstock"], "P" => $lang["pre-order"], "A" => $lang["arriving"]);
            $ar_src_status = array("A" => $lang["available"], "C" => $lang["stock_constraint"], "O" => $lang["temp_out_of_stock"], "L" => $lang["last_lot"], "D" => $lang["discontinued"]);
            $ar_ls_status = array("N" => $lang["not_listed"], "L" => $lang["listed"], "NC" => $lang["no_content"], "NS" => $lang["unsuit_for_list"]);
            $ar_fc_id = array("AMUS" => array("DEFAULT", "AMAZON_NA"), "AMUK" => array("DEFAULT", "AMAZON_EU"), "AMFR" => array("DEFAULT", "AMAZON_EU"), "AMDE" => array("DEFAULT", "AMAZON_EU"));
            $cr_array = array($lang["no"], $lang["yes"]);
            $ap_array = array("N" => $lang["no"], "Y" => $lang["yes"]);
            $rp_array = $this->get_irr_srv()->get_ixten_reprice_rule_list();

            $i = 0;
            foreach ($objlist as $obj) {
                //$this->set_dto($obj);
                $supplier_obj = $this->get_supplier_service()->get(array("id" => $obj->get_supplier_id()));
                $country_obj = $this->get_country_service()->get(array("id" => $obj->get_platform_country_id()));
                $set = array("supplier_region" => @call_user_func(array($supplier_obj, "get_supplier_reg")), "supplier_fc" => @call_user_func(array($supplier_obj, "get_fc_id")), "customer_fc" => $country_obj->get_fc_id(), "ccountry" => $country_obj->get_id(), "weight_cat" => $obj->get_freight_cat_id(), "price" => $obj->get_price());
                $p_srv = $this->get_factory_service()->get_price_service($obj->get_shiptype_name(), $set);
                $p_srv->set_platform_id($obj->get_platform_id());
                $p_srv->set_platform_curr_id($obj->get_platform_currency_id());

                $this->calc_freight_cost($obj, $p_srv, $country_obj->get_currency_id());
                $this->calculate_profit($obj);
                unset($p_srv);
                unset($supplier_obj);
                unset($country_obj);
                $cur_row = $i % 2;
                $selected_ws = array();
                $sws_status = "";
                $selected_ws[$obj->get_website_status()] = "SELECTED";
                foreach ($ar_ws_status as $rskey => $rsvalue) {
                    $sws_status .= "<option value='{$rskey}' {$selected_ws[$rskey]}>{$rsvalue}";
                }
                $sls_status = "";
                $selected_ls = array();
                $selected_ls[$obj->get_listing_status()] = " SELECTED";
                foreach ($ar_ls_status as $rskey => $rsvalue) {
                    $sls_status .= "<option value='{$rskey}'{$selected_ls[$rskey]}>{$rsvalue}</option>";
                }
                $sclr_status = "";
                $selected_clr = array();
                $selected_clr[$obj->get_clearance()] = " SELECTED";
                foreach ($cr_array as $rskey => $rsvalue) {
                    $sclr_status .= "<option value='{$rskey}'{$selected_clr[$rskey]}>{$rsvalue}</option>";
                }
                $sfc_status = "";
                $selected_fc = array();
                $selected_fc[$obj->get_fulfillment_centre_id()] = " SELECTED";
                if ($ar_fc_id[$obj->get_platform_id()]) {
                    foreach ($ar_fc_id[$obj->get_platform_id()] as $rskey => $rsvalue) {
                        $sfc_status .= "<option value='{$rsvalue}'{$selected_fc[$rsvalue]}>{$rsvalue}</option>";
                    }
                } else {
                    $sfc_status .= "<option></option>";
                }
                $sap_status = "";
                $selected_ap = array();
                $selected_ap[$obj->get_auto_price()] = " SELECTED";
                foreach ($ap_array as $rskey => $rsvalue) {
                    $sap_status .= "<option value='{$rskey}'{$selected_ap[$rskey]}>{$rsvalue}</option>";
                }
                $srp_status = "";
                $selected_rp = array();
                $selected_rp[$obj->get_amazon_reprice_name()] = " SELECTED";
                foreach ($rp_array[$obj->get_platform_id()] as $rskey => $rsvalue) {
                    $srp_status .= "<option value='{$rsvalue->get_id()}'{$selected_rp[$rsvalue->get_id()]}>{$rsvalue->get_id()}</option>";
                }
                $sku = $obj->get_sku();
                $platform = $obj->get_platform_id();
                $price = $obj->get_price() * 1;
                $cost = $obj->get_cost();
                /*
                $fdl = $obj->get_free_delivery_limit();
                if($price > $fdl)
                {
                    $obj->set_default_delivery_charge($obj->get_delivery_charge);
                    $obj->get_delivery_charge(0);
                }
                */
                $profit = $obj->get_profit();
                $margin = $obj->get_margin();
                $freight_cost = $obj->get_suppfc_cost() + $obj->get_whfc_cost();
                $updated = substr($obj->get_purchaser_updated_date(), 0, 10);
                $dclass = ((mktime() - strtotime($updated)) > 2592000) ? " class='warn'" : "";
                $pclass = ($profit < 0) ? " class='warn'" : "";
                $data["tr"] .= "
                                <tr class='row{$cur_row}' onMouseOver=\"AddClassName(this, 'highlight')\" onMouseOut=\"RemoveClassName(this, 'highlight')\">
                                    <td></td>
                                    <td><a href='" . base_url() . $this->get_tool_path() . "/view/{$sku}?target=overview' target='" . str_replace("-", "_", $sku) . "' onClick=\"Pop('" . base_url() . "marketing/pricing_tool/view/{$sku}?target=overview','" . str_replace("-", "_", $sku) . "');\">{$sku}</a></td>
                                    <td>{$obj->get_prod_name()}</td>
                                    <td><input class='input' name='price[{$platform}][{$sku}][platform_code]' value='{$obj->get_platform_code()}' onChange='needToConfirm=true'></td>
                                    <td>
                                        <select name='product[{$platform}][{$sku}][clearance]' class='input' onChange='needToConfirm=true'>
                                        {$sclr_status}
                                        </select>
                                    </td>
                                    <td>
                                        <select name='price[{$platform}][{$sku}][listing_status]' class='input' onChange='needToConfirm=true'>
                                        {$sls_status}
                                        </select>
                                    </td>
                                    <td>{$obj->get_inventory()}</td>
                                    <!--<td><input type='hidden' name='price[{$platform}][{$sku}][default_shiptype]' value='{$obj->get_shiptype()}'><input class='input' name='product[{$platform}][{$sku}][website_quantity]' value='{$obj->get_website_quantity()}' notEmpty isInteger onChange='needToConfirm=true'></td>-->
                                    <td><input class='input' name='price_extend[{$platform}][{$sku}][ext_qty]' value='{$obj->get_ext_qty()}' isInteger onChange='needToConfirm=true'></td>
                                    <td>
                                        <select name='product[{$platform}][{$sku}][website_status]' class='input' onChange='needToConfirm=true'>
                                            {$sws_status}
                                        </select>
                                    </td>
                                    <td>{$ar_src_status[$obj->get_sourcing_status()]}</td>
                                    <td{$dclass}>{$updated}</td>
                                    <td>{$obj->get_shiptype_name()}</td>
                                    <td>
                                        <select name='price_extend[{$platform}][{$sku}][fulfillment_centre_id]' class='input' onChange='needToConfirm=true'>
                                        {$sfc_status}
                                        </select>
                                    </td>
                                    <td>
                                        <select name='price[{$platform}][{$sku}][auto_price]' class='input' onChange='needToConfirm=true'>
                                        {$sap_status}
                                        </select>
                                    </td>
                                    <td>
                                        <select name='price_extend[{$platform}][{$sku}][amazon_reprice_name]' class='input' onChange='needToConfirm=true'>
                                            <option></option>
                                        {$srp_status}
                                        </select>
                                    </td>
                                    <td align='right'><input class='int2_input read' name='cost[{$sku}]' value='" . number_format($cost, 2) . "'></td>
                                    <td align='right'><input class='int2_input' name='price[{$platform}][{$sku}][price]' value='{$obj->get_current_platform_price()}' onKeyUp=\"CalcProfit('{$platform}','{$sku}', this.value)\" isNumber min='0' onChange='needToConfirm=true'>" . (!($obj->get_current_platform_price() * 1) && $obj->get_default_platform_converted_price() * 1 ? "<span class='special'>{$obj->get_default_platform_converted_price()}</span>" : "") . "</td>
                                    <td id='profit[{$platform}][{$sku}]' align='right'{$pclass}>" . number_format($profit, 2) . "</td>
                                    <td id='margin[{$platform}][{$sku}]' align='right'{$pclass}>" . number_format($margin, 2) . "%</td>
                                    <td><input type='checkbox' name='check[]' value='{$platform}||{$sku}' onClick='Marked(this);'></td>
                                </tr>
                                ";
                $obj->set_freight_cost(round($obj->get_freight_cost(), 2));
                $obj->set_supplier_cost(round($obj->get_supplier_cost(), 2));
                $declared_pcent = !$obj->get_declared_pcent() ? 0 : $obj->get_declared_pcent();
                $comm = ($obj->get_price() + $obj->get_delivery_charge()) * $obj->get_platform_commission() / 100;
                $admin_fee = !$obj->get_admin_fee() ? 0 : $obj->get_admin_fee();
                $data["js"] .= "prod['{$platform}']['{$sku}'] = {'clearance':{$obj->get_clearance()},'declared_rate':{$declared_pcent},'payment_charge_rate':{$obj->get_payment_charge_percent()},'vat_percent':{$obj->get_vat_percent()},'duty_percent':{$obj->get_duty_pcent()},'free_delivery_limit':{$obj->get_free_delivery_limit()},'default_delivery_charge':{$obj->get_delivery_charge()},'admin_fee':{$admin_fee},'freight_cost':{$freight_cost},'delivery_cost':{$obj->get_fccus_cost()},'supplier_cost':{$obj->get_supplier_cost()}, 'whtrans_cost':{$obj->get_whfc_cost()}, 'comm':{$comm},'commrate':" . ($obj->get_platform_commission() * 1) . ", 'shiptype':{$obj->get_shiptype()}};
                                ";
                $i++;
            }
            if ($i > 0) {
                $data["tr"] .= "<tr class='tb_brow' align='right'><td colspan='20'>{$lang['currency']}: <b>{$obj->get_platform_currency_id()}<b></td></tr>";
                $data["js"] .= "var country_id = '{$obj->get_platform_country_id()}';
                </script>";
            } else {
                $data["tr"] = $data["js"] = "";
            }
        }
        return $data;
    }

    public function set_irr_srv(Base_service $srv)
    {
        $this->irr_srv = $srv;
    }

    public function get_irr_srv()
    {
        return $this->irr_srv;
    }
}
/* End of file price_amazon_service.php */
/* Location: ./system/application/libraries/service/Price_amazon_service.php */
