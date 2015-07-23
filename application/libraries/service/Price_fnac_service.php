<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Price_service.php";

class Price_fnac_service extends Price_service
{
    private $wms_wh_srv;

    public function __construct()
    {
        parent::__construct();
        $this->set_tool_path('marketing/pricing_tool_' . strtolower(PLATFORM_TYPE));

        include_once(APPPATH . "libraries/service/Wms_warehouse_service.php");
        $this->set_wms_wh_srv(new Wms_warehouse_service());
    }

    public function get_product_overview_tr($where = array(), $option = array(), $classname = "Product_cost_dto", $lang = array())
    {
        $option["active_supplier"] = 1;
        $option["price_extend"] = 1;
        $option["wms_inventory"] = 1;
        if ($option["refresh_margin"]) {
            set_time_limit(300);
            if ($option["refresh_platform_list"]) {
                foreach ($option["refresh_platform_list"] as $platform_id) {
                    $this->get_price_margin_service()->refresh_margin(trim($platform_id));
                }
            }
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
            $ar_cr_status = array($lang["no"], $lang["yes"]);
            $ar_ws_status = array("I" => $lang["instock"], "O" => $lang["outstock"], "P" => $lang["pre-order"], "A" => $lang["arriving"]);
            $ar_src_status = array("A" => $lang["available"], "C" => $lang["stock_constraint"], "O" => $lang["temp_out_of_stock"], "L" => $lang["last_lot"], "D" => $lang["discontinued"]);
            $ar_ls_status = array("N" => $lang["not_listed"], "L" => $lang["listed"]);
            $cr_array = array($lang["no"], $lang["yes"]);
            $wms_wh_list = $this->get_wms_wh_srv()->get_list(array('status' => 1), array('limit' => -1, 'orderby' => 'warehouse_id'));
            $i = 0;
            foreach ($objlist as $obj) {
                //$this->set_dto($obj);
                if ($supplier_obj = $this->get_supplier_service()->get(array("id" => $obj->get_supplier_id()))) {
                    $supplier_prod_obj = $this->get_supplier_service()->get_sp_dao()->get(array("supplier_id" => $supplier_obj->get_id(), "prod_sku" => $obj->get_sku()));
                    $supplier_status = $supplier_prod_obj->get_supplier_status();
                } else {
                    $supplier_status = "";
                }
                $country_obj = $this->get_country_service()->get(array("id" => $obj->get_platform_country_id()));
                $this->calc_logistic_cost($obj);
                $this->calculate_profit($obj);

                unset($supplier_obj);
                unset($country_obj);
                $cur_row = $i % 2;
                $selected_cr = array();
                $scr_status = 0;
                $selected_cr[$obj->get_clearance()] = "SELECTED";
                foreach ($ar_cr_status as $rskey => $rsvalue) {
                    $scr_status .= "<option value='{$rskey}' {$selected_cr[$rskey]}>{$rsvalue}";
                }
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
                $sku = $obj->get_sku();
                $master_sku = $obj->get_master_sku();
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
                $updated = substr($obj->get_purchaser_updated_date(), 0, 10);
                $dclass = ((mktime() - strtotime($updated)) > 2592000) ? " class='warn'" : "";
                $pclass = ($profit < 0) ? " class='warn'" : "";

                $wms_inv = array();
                if (!is_null($obj->get_wms_inv())) {
                    $tmp_array = explode('|', $obj->get_wms_inv());
                    foreach ($tmp_array as $inv) {
                        list($wh, $inv, $git) = explode(',', $inv);
                        $wms_inv[$wh]['inv'] = $inv;
                        $wms_inv[$wh]['git'] = $git;
                    }
                }

                $wms_inv_td = '';
                $wms_inv_td_count = 0;
                if (!empty($wms_wh_list)) {
                    foreach ($wms_wh_list as $wms_wh) {
                        if (array_key_exists($wms_wh->get_warehouse_id(), $wms_inv)) {
                            $wms_inv_td .= '<td align="right">' . $wms_inv[$wms_wh->get_warehouse_id()]['inv'] . '</td>';
                        } else {
                            $wms_inv_td .= '<td align="right">0</td>';
                        }

                        $wms_inv_td_count++;
                    }
                }

                $data["tr"] .= "
                                <tr class='row{$cur_row}' onMouseOver=\"AddClassName(this, 'highlight')\" onMouseOut=\"RemoveClassName(this, 'highlight')\">
                                    <td></td>
                                    <td>{$obj->get_platform_id()}</td>
                                    <td><a href='" . base_url() . $this->get_tool_path() . "/view/{$sku}?target=overview' target='" . str_replace("-", "_", $sku) . "' onClick=\"Pop('" . base_url() . "marketing/pricing_tool/view/{$sku}?target=overview','" . str_replace("-", "_", $sku) . "');\">{$sku}</a></td>
                                    <td>{$sku}</td>
                                    <td>{$obj->get_prod_name()}</td>
                                    <td>
                                        <select name='product[{$platform}][{$sku}][clearance]' class='input' onChange='needToConfirm=true'>
                                            {$scr_status}
                                        </select>
                                    </td>
                                    <td>
                                        <select name='price[{$platform}][{$sku}][listing_status]' class='input' onChange='needToConfirm=true'>
                                        {$sls_status}
                                        </select>
                                    </td>
                                    <td><input name='price_extend[{$platform}][{$sku}][ext_qty]' value='{$obj->get_ext_qty()}' style='width:70px' isNumber min='0' onChange='needToConfirm=true'></td>
                                    <td>
                                        <select name='product[{$platform}][{$sku}][website_status]' class='input' onChange='needToConfirm=true'>
                                            {$sws_status}
                                        </select>
                                    </td>
                                    {$wms_inv_td}
                                    <td>{$ar_src_status[$supplier_status]}</td>
                                    <td{$dclass}>{$updated}</td>
                                    <td align='right'>" . $obj->get_platform_currency_id() . " <input class='read' name='cost[{$sku}]' value='" . number_format($cost, 2) . "' style='width:55px; text-align:right'></td>
                                    <td align='right'>" . $obj->get_platform_currency_id() . " <input id='sp[{$platform}][{$sku}]' name='price[{$platform}][{$sku}][price]' value='{$obj->get_current_platform_price()}' style='width:70px' onKeyUp=\"CalcProfit('{$platform}','{$sku}', this.value)\" isNumber min='0' onChange='needToConfirm=true'>" . (!($obj->get_current_platform_price() * 1) && $obj->get_default_platform_converted_price() * 1 ? "<span class='special'>" . $obj->get_platform_currency_id() . " {$obj->get_default_platform_converted_price()}</span>" : "") . "</td>
                                    <td id='profit[{$platform}][{$sku}]' align='right'{$pclass}>" . number_format($profit, 2) . "</td>
                                    <td id='margin[{$platform}][{$sku}]' align='right'{$pclass}>" . number_format($margin, 2) . "%</td>
                                    <td><input type='checkbox' name='check[]' value='{$platform}||{$sku}' onClick='Marked(this);'></td>
                                </tr>
                                ";
                $obj->set_freight_cost(round($obj->get_logistic_cost(), 2));
                $obj->set_supplier_cost(round($obj->get_supplier_cost(), 2));
                $declared_pcent = !$obj->get_declared_pcent() ? 0 : $obj->get_declared_pcent();
                $comm = ($obj->get_price() + $obj->get_delivery_charge()) * $obj->get_platform_commission() / 100;
                $admin_fee = !$obj->get_admin_fee() ? 0 : $obj->get_admin_fee();
                $default_delivery_charge = $obj->get_default_delivery_charge() * 1;
                $delivery_charge = $obj->get_delivery_charge() * 1;
                $data["js"] .= "prod['{$platform}']['{$sku}'] = {'clearance':{$obj->get_clearance()},'declared_rate':{$declared_pcent},'payment_charge_rate':{$obj->get_payment_charge_percent()},'vat_percent':{$obj->get_vat_percent()},'duty_percent':{$obj->get_duty_pcent()},'free_delivery_limit':{$obj->get_free_delivery_limit()},'default_delivery_charge':{$default_delivery_charge},'admin_fee':{$admin_fee},'logistic_cost':{$obj->get_logistic_cost()},'supplier_cost':{$obj->get_supplier_cost()}, 'delivery_charge':{$delivery_charge}, 'comm':{$comm},'commrate':" . ($obj->get_platform_commission() * 1) . ",'forex_fee_percent':" . ($obj->get_forex_fee_percent() * 1) . ", 'listing_fee':" . ($obj->get_listing_fee() * 1) . "};
                                ";
                $i++;
            }
            if ($i > 0) {
                $colspan = 17 + $wms_inv_td_count;
                $data["tr"] .= "<tr class='tb_brow' align='right'><td colspan='{$colspan}'>{$lang['currency']}: <b>{$obj->get_platform_currency_id()}<b></td></tr>";
                $data["js"] .= "var country_id = '{$obj->get_platform_country_id()}';
                </script>";
            } else {
                $data["tr"] = $data["js"] = "";
            }
        }
        return $data;
    }

    public function get_wms_wh_srv()
    {
        return $this->wms_wh_srv;
    }

    public function set_wms_wh_srv(Base_service $svc)
    {
        $this->wms_wh_srv = $svc;
    }

    public function get_product_overview_tr_v2($where = array(), $option = array(), $classname = "Product_cost_dto", $lang = array())
    {
        $option["active_supplier"] = 1;
        $option["wms_inventory"] = 1;
        $option["delivery_time"] = 1;
        $option["refresh_margin"] = 1;
        # sbf #4906 - running cron job to refresh so it wouldn't be slow
        // if($option["refresh_margin"])
        // {
        //  // this portion updates db price_margin table with calculated margin so that search margin value will be correct
        //  set_time_limit(300);
        //  if($option["refresh_platform_list"])
        //  {
        //      foreach($option["refresh_platform_list"] as $platform_id)
        //      {
        //          $this->get_price_margin_service()->refresh_margin(trim($platform_id));
        //      }
        //  }
        // }
        if ($objlist = $this->get_product_service()->get_dao()->get_product_overview_v2($where, $option, $classname)) {
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
            $ar_cr_status = array($lang["no"], $lang["yes"]);
            $ar_ws_status = array("I" => $lang["instock"], "O" => $lang["outstock"], "P" => $lang["pre-order"], "A" => $lang["arriving"]);
            $ar_src_status = array("A" => $lang["available"], "C" => $lang["stock_constraint"], "O" => $lang["temp_out_of_stock"], "L" => $lang["last_lot"], "D" => $lang["discontinued"]);
            $ar_ls_status = array("N" => $lang["not_listed"], "L" => $lang["listed"]);
            $cr_array = array($lang["no"], $lang["yes"]);

            $wms_warehouse_where["status"] = 1;
            $wms_warehouse_where["type != 'W'"] = null;
            $wms_wh_list = $this->get_wms_wh_srv()->get_list($wms_warehouse_where, array('limit' => -1, 'orderby' => 'warehouse_id'));

            $i = 0;
            foreach ($objlist as $obj) {
                //$this->set_dto($obj);
                if ($supplier_obj = $this->get_supplier_service()->get(array("id" => $obj->get_supplier_id()))) {
                    $supplier_prod_obj = $this->get_supplier_service()->get_sp_dao()->get(array("supplier_id" => $supplier_obj->get_id(), "prod_sku" => $obj->get_sku()));
                    $supplier_status = $supplier_prod_obj->get_supplier_status();
                } else {
                    $supplier_status = "";
                }
                $country_obj = $this->get_country_service()->get(array("id" => $obj->get_platform_country_id()));

                # we already calculate logistic cost in query and use price_margin table, so skip the slow calculate_profit()
                // $this->calc_logistic_cost($obj);
                // $this->calculate_profit($obj);
                $this->calc_delivery_charge($obj);
                $this->calc_cost($obj);

                unset($supplier_obj);
                unset($country_obj);
                $cur_row = $i % 2;
                $selected_cr = array();
                $scr_status = 0;
                $selected_cr[$obj->get_clearance()] = "SELECTED";
                foreach ($ar_cr_status as $rskey => $rsvalue) {
                    $scr_status .= "<option value='{$rskey}' {$selected_cr[$rskey]}>{$rsvalue}";
                }
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
                $surplus_quantity = $obj->get_surplus_quantity();
                $sku = $obj->get_sku();
                $master_sku = $obj->get_master_sku();
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
                $updated = substr($obj->get_purchaser_updated_date(), 0, 10);
                $dclass = ((mktime() - strtotime($updated)) > 2592000) ? " class='warn'" : "";
                $pclass = ($profit < 0) ? " class='warn'" : "";
                $format_profit = number_format($profit, 2);
                $format_margin = number_format($margin, 2);
                $hidden_profitmargin_html = <<<html
                    <input type="hidden" name="hidden_profit[$platform][$sku]" id="hidden_profit[$platform][$sku]" value = "$format_profit">
                    <input type="hidden" name="hidden_margin[$platform][$sku]" id="hidden_margin[$platform][$sku]" value = "$format_margin">
html;

                $ean_style = "";
                if (!$obj->get_ext_ref_3()) {
                    $ean_style = " style='background-color:#FFC1C1' ";
                }
                $wms_inv = array();
                if (!is_null($obj->get_wms_inv())) {
                    $tmp_array = explode('|', $obj->get_wms_inv());
                    foreach ($tmp_array as $inv) {
                        list($wh, $inv, $git) = explode(',', $inv);
                        $wms_inv[$wh]['inv'] = $inv;
                        $wms_inv[$wh]['git'] = $git;
                    }
                }

                $wms_inv_td = '';
                $wms_inv_td_count = 0;
                if (!empty($wms_wh_list)) {
                    foreach ($wms_wh_list as $wms_wh) {
                        if (array_key_exists($wms_wh->get_warehouse_id(), $wms_inv)) {
                            $wms_inv_td .= '<td align="right">' . $wms_inv[$wms_wh->get_warehouse_id()]['inv'] . '</td>';
                        } else {
                            $wms_inv_td .= '<td align="right">0</td>';
                        }

                        $wms_inv_td_count++;
                    }
                }

                $data["tr"] .= "
                                <tr class='row{$cur_row}' onMouseOver=\"AddClassName(this, 'highlight')\" onMouseOut=\"RemoveClassName(this, 'highlight')\">
                                    <td></td>
                                    <td>{$obj->get_platform_id()}</td>
                                    <td><a href='" . base_url() . $this->get_tool_path() . "/view/{$sku}?target=overview' target='" . str_replace("-", "_", $sku) . "' onClick=\"Pop('" . base_url() . "marketing/pricing_tool/view/{$sku}?target=overview','" . str_replace("-", "_", $sku) . "');\">{$master_sku}</a></td>
                                    <td>{$sku}</td>
                                    <td>{$obj->get_prod_name()}</td>
                                    <td>
                                        <select name='product[{$platform}][{$sku}][clearance]' class='input' onChange='needToConfirm=true'>
                                            {$scr_status}
                                        </select>
                                    </td>
                                    <td>
                                        <select name='price[{$platform}][{$sku}][listing_status]' class='input' onChange='needToConfirm=true'>
                                        {$sls_status}
                                        </select>
                                    </td>
                                    <td><input name='price_extend[{$platform}][{$sku}][ext_qty]' value='{$obj->get_ext_qty()}' style='width:70px' isNumber min='0' onChange='needToConfirm=true'></td>
                                    <td $ean_style>
                                        PI EAN: {$obj->get_ean()}
                                        <input name='price_extend[{$platform}][{$sku}][ext_ref_3]' value='{$obj->get_ext_ref_3()}' style='width:90px' onChange='needToConfirm=true' >
                                        <br><b>*Compulsory</b>
                                    </td>
                                    {$wms_inv_td}
                                    <td align='right'>$surplus_quantity</td>
                                    <td>{$ar_src_status[$supplier_status]}</td>
                                    <td{$dclass}>{$updated}</td>
                                    <td align='right'>" . $obj->get_platform_currency_id() . " <input class='read' name='cost[{$sku}]' value='" . number_format($cost, 2) . "' style='width:55px; text-align:right'></td>
                                    <td align='right'>" . $obj->get_platform_currency_id() . " <input id='sp[{$platform}][{$sku}]' name='price[{$platform}][{$sku}][price]' value='{$obj->get_current_platform_price()}' style='width:70px' onKeyUp=\"CalcProfit('{$platform}','{$sku}', this.value)\" isNumber min='0' onChange='needToConfirm=true'>" . (!($obj->get_current_platform_price() * 1) && $obj->get_default_platform_converted_price() * 1 ? "<span class='special'>" . $obj->get_platform_currency_id() . " {$obj->get_default_platform_converted_price()}</span>" : "") . "
                                    {$hidden_profitmargin_html}
                                    </td>
                                    <td id='profit[{$platform}][{$sku}]' align='right'{$pclass}>" . number_format($profit, 2) . "</td>
                                    <td id='margin[{$platform}][{$sku}]' align='right'{$pclass}>" . number_format($margin, 2) . "%</td>
                                    <td><input type='checkbox' name='check[]' value='{$platform}||{$sku}' onClick='Marked(this);'></td>
                                </tr>
                                ";
                $obj->set_freight_cost(round($obj->get_logistic_cost(), 2));
                $obj->set_supplier_cost(round($obj->get_supplier_cost(), 2));
                $declared_pcent = !$obj->get_declared_pcent() ? 0 : $obj->get_declared_pcent();
                $comm = ($obj->get_price() + $obj->get_delivery_charge()) * $obj->get_platform_commission() / 100;
                $admin_fee = !$obj->get_admin_fee() ? 0 : $obj->get_admin_fee();
                $default_delivery_charge = $obj->get_default_delivery_charge() * 1;
                $delivery_charge = $obj->get_delivery_charge() * 1;
                $data["js"] .= "prod['{$platform}']['{$sku}'] = {'clearance':{$obj->get_clearance()},'declared_rate':{$declared_pcent},'payment_charge_rate':{$obj->get_payment_charge_percent()},'vat_percent':{$obj->get_vat_percent()},'duty_percent':{$obj->get_duty_pcent()},'free_delivery_limit':{$obj->get_free_delivery_limit()},'default_delivery_charge':{$default_delivery_charge},'admin_fee':{$admin_fee},'logistic_cost':{$obj->get_logistic_cost()},'supplier_cost':{$obj->get_supplier_cost()}, 'delivery_charge':{$delivery_charge}, 'comm':{$comm},'commrate':" . ($obj->get_platform_commission() * 1) . ",'forex_fee_percent':" . ($obj->get_forex_fee_percent() * 1) . ", 'listing_fee':" . ($obj->get_listing_fee() * 1) . "};
                                ";
                $i++;
            }
            if ($i > 0) {
                $colspan = 17 + $wms_inv_td_count;
                $data["tr"] .= "<tr class='tb_brow' align='right'><td colspan='{$colspan}'>{$lang['currency']}: <b>{$obj->get_platform_currency_id()}<b></td></tr>";
                $data["js"] .= "var country_id = '{$obj->get_platform_country_id()}';
                </script>";
            } else {
                $data["tr"] = $data["js"] = "";
            }
        }
        return $data;
    }

    public function calc_commission($dto = NULL)
    {
        $this->init_dto($dto);
        $sub_cat_id = $dto->get_sub_cat_id();
        $sub_sub_cat_id = $dto->get_sub_sub_cat_id();
        $price = $dto->get_price();
        if (in_array(strtoupper($sub_cat_id), array(16, 17, 19, 20, 21, 22, 27, 28, 29, 30, 34, 36, 40, 41, 49, 55, 56, 57, 64, 75)) && !($sub_sub_cat_id == 229)) {
            if ($price <= 50) {
                $dto->set_platform_commission(12);
            } elseif ($price > 50 && $price <= 200) {
                $dto->set_platform_commission(10);
            } else {
                $dto->set_platform_commission(8);
            }
        } else {
            $dto->set_platform_commission(8);
        }

        $dto->set_sales_commission(number_format(($dto->get_price() + $dto->get_delivery_charge()) * $dto->get_platform_commission() / 100, 2, ".", ""));
    }

    public function get_fnac_pending_update_offer_list($platform_id = "FNACES")
    {
        return $this->get_price_ext_dao()->get_list(array("platform_id" => $platform_id, "action" => "P", "remark IS NOT NULL" => null));
    }
}


