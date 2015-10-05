<?php
namespace ESG\Panther\Service;

use ESG\Panther\Service\WmsWarehouseService;
use ESG\Panther\Service\ExtCategoryMappingService;

class PriceWebsiteService extends PriceService
{
    private $wms_wh_srv;

    public function __construct($platformType = null)
    {
        parent::__construct($platformType);
        $this->wmsWarehouseService = new WmsWarehouseService;
        $this->extCategoryMappingService = new ExtCategoryMappingService;
    }

    public function get_product_overview_tr($where = array(), $option = array(), $classname = "Product_cost_dto", $lang = array())
    {
        $this->set_tool_path('marketing/Pricing_tool_' . strtolower(PLATFORM_TYPE));

        $option["active_supplier"] = 1;
        $option["wms_inventory"] = 1;
        $option["delivery_time"] = 1;
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
            $wms_wh_list = $this->wmsWarehouseService->getList(['status' => 1], ['limit' => -1, 'orderby' => 'warehouse_id']);
            $i = 0;
            foreach ($objlist as $obj) {
                //$this->set_dto($obj);

                $sub_cat_margin = $this->get_product_service()->get_sub_cat_margin(array("sku" => $obj->get_sku(), "platform_id" => $obj->get_platform_id()));
                if ($sub_cat_margin) {
                    $obj->set_sub_cat_margin($sub_cat_margin);
                }
                if ($supplier_obj = $this->get_supplier_service()->get(array("id" => $obj->get_supplier_id()))) {
                    $supplier_prod_obj = $this->get_supplier_service()->get_sp_dao()->get(array("supplier_id" => $supplier_obj->get_id(), "prod_sku" => $obj->get_sku()));
                    $supplier_status = $supplier_prod_obj->get_supplier_status();
                } else {
                    $supplier_status = "";
                }
                $country_obj = $this->get_country_service()->get(array("id" => $obj->get_platform_country_id()));
                $this->calcLogisticCost($obj);
                $this->calculateProfit($obj);
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
                $auto_price_cb = $obj->get_auto_price() == 'Y' ? "CHECKED" : "";
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
                                    <td>
                                    <a href='" . base_url() . $this->get_tool_path() . "/view/{$sku}?target=overview' target='" . str_replace("-", "_", $sku) . "' onClick=\"Pop('" . base_url() . "marketing/pricing_tool/view/{$sku}?target=overview','" . str_replace("-", "_", $sku) . "');\">{$master_sku}</a></td>
                                    <td>
                                    {$obj->get_prod_name()}</td>
                                    <td>
                                        <select name='product[{$platform}][{$sku}][clearance]' class='input' onChange='needToConfirm=true'>
                                            {$scr_status}
                                        </select>
                                        <a class='iframe cboxElement' href='/marketing/pricing_tool_website/manage_feed_platform'>Map feeds</a><br>
                                    </td>
                                    <td>
                                        <select name='price[{$platform}][{$sku}][listing_status]' class='input' onChange='needToConfirm=true'>
                                        {$sls_status}
                                        </select>
                                        <a class='iframe cboxElement' href='/marketing/pricing_tool_website/manage_feed/$sku/{$obj->get_platform_id()}'>Edit feeds</a>
                                    </td>
                                    <td><input name='product[{$platform}][{$sku}][website_quantity]' value='{$obj->get_website_quantity()}' style='width:70px' isNumber min='0' onChange='needToConfirm=true'></td>
                                    <td>
                                        <select name='product[{$platform}][{$sku}][website_status]' class='input' onChange='needToConfirm=true'>
                                            {$sws_status}
                                        </select><br>
                                        <!-- shipping / delivery time frames -->
                                        <br>Ship Days: {$obj->get_ship_day()}
                                        <br>Del Days: {$obj->get_delivery_day()}
                                    </td>
                                    {$wms_inv_td}
                                    <td>
                                        <!-- surplusqty -->
                                        {$obj->get_surplus_quantity()}
                                    </td>
                                    <td>{$ar_src_status[$supplier_status]}</td>
                                    <td{$dclass}>{$updated}</td>
                                    <td align='right'>" . $obj->get_platform_currency_id() . " <input class='read' name='cost[{$sku}]' value='" . number_format($cost, 2) . "' style='width:55px; text-align:right'></td>
                                    <td><input type='checkbox' id='auto_price_cb[{$platform}][{$sku}]' name='price[{$platform}][{$sku}][auto_price]' " . $auto_price_cb . " onclick=\"CheckMargin('{$platform}','{$sku}')\" onChange='needToConfirm=true'></td>
                                    <td align='right'>" . $obj->get_platform_currency_id() . " <input name='price[{$platform}][{$sku}][price]' id='sp[{$platform}][{$sku}]' value='{$obj->get_current_platform_price()}' style='width:70px' onKeyUp=\"CalcProfit('{$platform}','{$sku}', this.value)\" isNumber min='0' onChange='needToConfirm=true'>" . (!($obj->get_current_platform_price() * 1) && $obj->get_default_platform_converted_price() * 1 ? "<span class='special'>" . $obj->get_platform_currency_id() . " {$obj->get_default_platform_converted_price()}</span>" : "") . "</td>
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
                $auto_calc_price = round(($obj->get_auto_total_charge() - $obj->get_default_delivery_charge() > $obj->get_free_delivery_limit()) ? $obj->get_auto_total_charge() : $obj->get_auto_total_charge() - $obj->get_default_delivery_charge(), 2);

                $data["js"] .= "prod['{$platform}']['{$sku}'] = {'clearance':{$obj->get_clearance()},'declared_rate':{$declared_pcent},'payment_charge_rate':{$obj->get_payment_charge_percent()},'vat_percent':{$obj->get_vat_percent()},'duty_percent':{$obj->get_duty_pcent()},'free_delivery_limit':{$obj->get_free_delivery_limit()},'default_delivery_charge':{$default_delivery_charge},'admin_fee':{$admin_fee},'logistic_cost':{$obj->get_logistic_cost()},'supplier_cost':{$obj->get_supplier_cost()}, 'delivery_charge':{$delivery_charge}, 'comm':{$comm},'commrate':" . ($obj->get_platform_commission() * 1) . ",'sub_cat_margin':" . ($obj->get_sub_cat_margin() * 1) . ",'auto_calc_price':" . ($auto_calc_price) . ",'origin_price':" . ($obj->get_price()) . ",'listing_fee':" . ($obj->get_listing_fee() * 1) . ",'forex_fee_percent':" . ($obj->get_forex_fee_percent()) . "};
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

    public function get_product_overview_tr_v2($where = array(), $option = array(), $classname = "Product_cost_dto", $lang = array())
    {
        $this->set_tool_path('marketing/Pricing_tool_' . strtolower(PLATFORM_TYPE));

        $option["active_supplier"] = 1;
        // $option["wms_inventory"] = 1;
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
            $autoprice_type = array("N" => "Manual", "Y" => "Auto", "C" => "CompReprice");
            $wms_wh_list = $this->wmsWarehouseService->getList(['status' => 1], ['limit' => -1, 'orderby' => 'warehouse_id']);
            $i = 0;
            $subtractcount = 0;
            foreach ($objlist as $obj) {
                //$this->set_dto($obj);
                $plachecked = $pladisable = $plastyle = $internal_gsc_comment = $gsc_comment = $adGroup_status = $adGroupdisable = $adwords_status_str = "";

                // ADWORDS
                if ($obj->get_ad_status() !== NULL) {
                    $adwords_input = "Advertised";
                    if ($obj->get_ad_status() == 0) {
                        $adGroupdisable = " disabled";
                        $adwords_status_str = "<font color = '#FF0000'>Paused</font>";
                    } else {
                        $adwords_status_str = "<font color = '#00FF00'>Enabled</font>";
                    }

                    // adwords API status
                    if ($obj->get_ad_api_request_result() == 1) {
                        $api_result = "<font color = '#00FF00'>Success</font>";
                        $adGroup_status = "<br> - <br>$api_result <br>$adwords_status_str";
                    } else {
                        $api_result = "<font color = '#FF0000'>Fail</font>";
                        $adGroup_status = "<br> - <br>$api_result";
                    }

                    // if($option["adw"] == "N")
                    // {
                    //  # If Adwords filter = "NotAdvertised", then we skip this row
                    //  $subtractcount++;
                    //  continue;
                    // }
                } else {
                    //if adGroup not exists before, then default is enabled.
                    // $adwords_input = "<input id='product[{$obj->get_sku()}][google_adwords][{$obj->get_platform_id()}]' type='checkbox' name='product[{$obj->get_sku()}][google_adwords][{$obj->get_platform_id()}]'>";
                    $adwords_input = "<input id='product[google_adwords][{$obj->get_platform_id()}][{$obj->get_sku()}]' type='checkbox' name='google_adwords[{$obj->get_platform_id()}][]' value='{$obj->get_sku()}' onClick='needToConfirm=true'>";

                    // if($option["adw"] == "Y")
                    // {
                    //  # If Adwords filter = "Advertised", then we skip this row
                    //  $subtractcount++;
                    //  continue;
                    // }
                }


                // PLA
                $gsc_where = $gsc_option = array();
                $gsc_where['cm.id'] = $obj->get_sku();
                $gsc_where['cm.ext_party'] = "GOOGLEBASE";
                $gsc_where['cm.country_id'] = $obj->get_platform_country_id();
                $gsc_where['cm.status'] = 1;
                $gsc_option['limit'] = 1;
                $google_cat_obj = $this->ext_category_mapping_service->get_category_mapping_srv()->get_googlebase_cat_list_w_country($gsc_where, $gsc_option);

                if (!$google_cat_obj || !$google_cat_obj->get_product_name() || !$google_cat_obj->get_ext_name() || !$obj->get_mpn() || !$obj->get_detail_desc()) {
                    // conditions for disabling PLA checkbox; taken from pricing_tool_website
                    $pladisable = "disabled";
                    $plastyle = "style='background-color:lightgray;'";

                    if (!$obj->get_mpn())
                        $internal_gsc_comment .= "<br><font color='red'>No MPN</font>";

                    if (!$google_cat_obj) {
                        $internal_gsc_comment .= "<br><font color='red'>No google product title/category </font>";
                    } else {
                        if (!$google_cat_obj->get_product_name())
                            $internal_gsc_comment .= "<br><font color='red'>No google product title </font>";
                        if (!$google_cat_obj->get_ext_name())
                            $internal_gsc_comment .= "<br><font color='red'>No google category </font>";

                    }
                    if (!$obj->get_detail_desc())
                        $internal_gsc_comment .= "<br><font color='red'>No detail desc </font>";

                    // if is_advertised search filter passed in, ignore those with disabled PLA
                    // if(isset($where["pr.is_advertised"]))
                    // {
                    //  $subtractcount++;
                    //  continue;
                    // }
                }

                $gsc_comment = "";
                if ($obj->get_gsc_status() == 0) {
                    $gsc_comment_word = "PAUSE";
                    if ($obj->get_api_request_result() == 0) {
                        $gsc_comment_word .= " - FAIL";
                    } else {
                        $gsc_comment_word .= " - SUCCESS";
                    }
                    $gsc_comment .= "<br><font color = '#ff0000'>$gsc_comment_word</font>";
                } else {
                    if (($obj->get_api_request_result() == 0) || $obj->get_is_advertised() != 'Y') {
                        $gsc_comment .= $obj->get_comment();
                        if (!$gsc_comment) {
                            //get internal failed reason if no gsc_comment
                            $gsc_comment .= $internal_gsc_comment;
                        } else {
                            $gsc_temp_list = explode(';', $gsc_comment);
                            $listcnt = count($gsc_temp_list);
                            $gsc_comment = $gsc_temp_list[$listcnt - 1];
                            if ($internal_gsc_comment) {
                                $gsc_comment .= $internal_gsc_comment . "<br>" . $gsc_comment;
                            }
                        }
                        // if is_advertised search filter passed in, ignore those with disabled PLA
                        // if(isset($where["pr.is_advertised"]))
                        // {
                        //  $subtractcount++;
                        //  continue;
                        // }
                    } elseif ($obj->get_api_request_result() == 1 && $obj->get_is_advertised() == 'Y') {
                        $gsc_comment .= "<br><font color = 'green'>Success</font>";
                    }
                }

                if ($internal_gsc_comment) $gsc_comment = $internal_gsc_comment . "<br>" . $gsc_comment;
                if ($obj->get_is_advertised() == 'Y')
                    $plachecked = " CHECKED";

                $sub_cat_margin = $this->get_product_service()->get_sub_cat_margin(array("sku" => $obj->get_sku(), "platform_id" => $obj->get_platform_id()));
                if ($sub_cat_margin) {
                    $obj->set_sub_cat_margin($sub_cat_margin);
                }
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
                // $auto_price_cb = $obj->get_auto_price() == 'Y'?"CHECKED":"";
                $ap_status = "";
                $selected_ap = array();
                $selected_ap[$obj->get_auto_price()] = " SELECTED";
                foreach ($autoprice_type as $apkey => $apvalue) {
                    $ap_status .= "<option value='{$apkey}'{$selected_ap[$apkey]}>{$apvalue}</option>";
                }
                $profit = $obj->get_profit();
                $margin = $obj->get_margin();
                $updated = substr($obj->get_purchaser_updated_date(), 0, 10);
                $dclass = ((mktime() - strtotime($updated)) > 2592000) ? " class='warn'" : "";
                $pclass = ($profit < 0) ? " class='warn'" : "";

                $wms_inv = array();
                // if (!is_null($obj->get_wms_inv()))
                // {
                //  $tmp_array = explode('|', $obj->get_wms_inv());
                //  foreach ($tmp_array as $inv)
                //  {
                //      list($wh, $inv, $git) = explode(',', $inv);
                //      $wms_inv[$wh]['inv'] = $inv;
                //      $wms_inv[$wh]['git'] = $git;
                //  }
                // }

                // $wms_inv_td = '';
                // $wms_inv_td_count = 0;
                // if (!empty($wms_wh_list))
                // {
                //  foreach ($wms_wh_list as $wms_wh)
                //  {
                //      if (array_key_exists($wms_wh->get_warehouse_id(), $wms_inv))
                //      {
                //          $wms_inv_td .= '<td align="right">' . $wms_inv[$wms_wh->get_warehouse_id()]['inv'] . '</td>';
                //      }
                //      else
                //      {
                //          $wms_inv_td .= '<td align="right">0</td>';
                //      }

                //      $wms_inv_td_count++;
                //  }
                // }

                // v2
                $data["tr"] .= "
                                <tr class='row{$cur_row}' onMouseOver=\"AddClassName(this, 'highlight')\" onMouseOut=\"RemoveClassName(this, 'highlight')\">
                                    <td></td>
                                    <td>{$obj->get_platform_id()}</td>
                                    <td>
                                    <a href='" . base_url() . $this->get_tool_path() . "/view/{$sku}?target=overview' target='" . str_replace("-", "_", $sku) . "' onClick=\"Pop('" . base_url() . "marketing/pricing_tool/view/{$sku}?target=overview','" . str_replace("-", "_", $sku) . "');\">{$master_sku}</a></td>
                                    <td>
                                    {$obj->get_prod_name()}</td>
                                    <td>
                                        <select name='product[{$platform}][{$sku}][clearance]' class='input' onChange='needToConfirm=true'>
                                            {$scr_status}
                                        </select>
                                        <a class='iframe cboxElement' href='/marketing/pricing_tool_website/manage_feed_platform'>Map feeds</a><br>
                                    </td>
                                    <td>
                                        <select name='price[{$platform}][{$sku}][listing_status]' class='input' onChange='needToConfirm=true'>
                                        {$sls_status}
                                        </select>
                                        <a class='iframe cboxElement' href='/marketing/pricing_tool_website/manage_feed/$sku/{$obj->get_platform_id()}'>Edit feeds</a>
                                    </td>
                                    <td><input name='product[{$platform}][{$sku}][website_quantity]' value='{$obj->get_website_quantity()}' style='width:70px' isNumber min='0' onChange='needToConfirm=true'></td>
                                    <td>
                                        <select name='product[{$platform}][{$sku}][website_status]' class='input' onChange='needToConfirm=true'>
                                            {$sws_status}
                                        </select><br>
                                        <!-- shipping / delivery time frames -->
                                        <br>Ship Days: {$obj->get_ship_day()}
                                        <br>Del Days: {$obj->get_delivery_day()}
                                    </td>
                                    {$wms_inv_td}
                                    <td>
                                        <!-- surplusqty -->
                                        {$obj->get_surplus_quantity()}
                                    </td>
                                    <td>{$ar_src_status[$supplier_status]}</td>
                                    <td{$dclass}>{$updated}</td>
                                    <!-- PLA -->
                                    <td $plastyle align='center'><input type='checkbox' id='pla_cb[{$platform}][{$sku}]' name='is_advertised[{$platform}][]' value = '$sku' $plachecked $pladisable onClick='needToConfirm=true'>$gsc_comment</td>
                                    <!-- Adwords -->
                                    <td align='center'>$adwords_input $adGroup_status</td>
                                    <td align='right'>" . $obj->get_platform_currency_id() . " <input class='read' name='cost[{$sku}]' value='" . number_format($cost, 2) . "' style='width:55px; text-align:right'></td>
                                    <td>
                                        <select name='price[{$platform}][{$sku}][auto_price]' id='price[{$platform}][{$sku}][auto_price]' class='input' onChange=\"CheckMargin_v2('{$platform}','{$sku}');needToConfirm=true\">
                                            {$ap_status}
                                        </select>
                                    </td>
                                    <td align='right'>"
                    . $obj->get_platform_currency_id() . "
                                        <input name='price[{$platform}][{$sku}][price]' id='sp[{$platform}][{$sku}]' value='{$obj->get_current_platform_price()}' style='width:70px' onKeyUp=\"CalcProfit('{$platform}','{$sku}', this.value)\" isNumber min='0' onChange='needToConfirm=true'>"
                    . (!($obj->get_current_platform_price() * 1) && $obj->get_default_platform_converted_price() * 1 ? "<span class='special'>" . $obj->get_platform_currency_id() . " {$obj->get_default_platform_converted_price()}</span>" : "")
                    . '<input type="hidden" id="hidden_profit[' . $platform . '][' . $sku . ']" name="hidden_profit[' . $platform . '][' . $sku . ']" value="' . number_format($profit, 2) . '">
                                        <input type="hidden" id="hidden_margin[' . $platform . '][' . $sku . ']" name="hidden_margin[' . $platform . '][' . $sku . ']" value="' . number_format($margin, 2) . '">' . "
                                    </td>
                                    <td id='profit[{$platform}][{$sku}]' align='right'{$pclass}>"
                    . number_format($profit, 2)
                    . "</td>
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
                $auto_calc_price = round(($obj->get_auto_total_charge() - $obj->get_default_delivery_charge() > $obj->get_free_delivery_limit()) ? $obj->get_auto_total_charge() : $obj->get_auto_total_charge() - $obj->get_default_delivery_charge(), 2);

                $data["js"] .= "prod['{$platform}']['{$sku}'] = {'clearance':{$obj->get_clearance()},'declared_rate':{$declared_pcent},'payment_charge_rate':{$obj->get_payment_charge_percent()},'vat_percent':{$obj->get_vat_percent()},'duty_percent':{$obj->get_duty_pcent()},'free_delivery_limit':{$obj->get_free_delivery_limit()},'default_delivery_charge':{$default_delivery_charge},'admin_fee':{$admin_fee},'logistic_cost':{$obj->get_logistic_cost()},'supplier_cost':{$obj->get_supplier_cost()}, 'delivery_charge':{$delivery_charge}, 'comm':{$comm},'commrate':" . ($obj->get_platform_commission() * 1) . ",'sub_cat_margin':" . ($obj->get_sub_cat_margin() * 1) . ",'auto_calc_price':" . ($auto_calc_price) . ",'origin_price':" . ($obj->get_price()) . ",'listing_fee':" . ($obj->get_listing_fee() * 1) . ",'forex_fee_percent':" . ($obj->get_forex_fee_percent()) . "};
                                ";
                $i++;
            }

            $data["subtractcount"] = $subtractcount;
            if ($i > 0) {
                $colspan = 19 + $wms_inv_td_count;
                $data["tr"] .= "<tr class='tb_brow' align='right'><td colspan='{$colspan}'>{$lang['currency']}: <b>{$obj->get_platform_currency_id()}</b></td></tr>";
                $data["js"] .= "var country_id = '{$obj->get_platform_country_id()}';
                </script>";
            } else {
                $data["tr"] = $data["js"] = "";
            }
        }
        return $data;
    }

    public function update_rrp_factor()
    {
        $price_list = $this->get_list(array('rrp_factor is NULL' => NULL), array('limit' => -1));
        foreach ($price_list as $price_obj) {
            $rrp_factor = $this->get_rrp_factor_by_sku($price_obj->get_sku());
            $price_obj->set_rrp_factor($rrp_factor);
            $this->update($price_obj);
        }
        return $this->get_dao()->update_rrp_factor();
    }

    public function get_rrp_factor_by_sku($sku = '')
    {
        $default_rrp_factor = 1.34;
        $rrp_factor = NULL;

        if ($sku == '') {
            $rrp_factor = $default_rrp_factor;
        } else {
            $price_obj = $this->get(array('sku' => $sku));
            if ($price_obj) {
                $rrp_factor = $price_obj->get_rrp_factor();
            } else {
                $product_obj = $this->get_product_service()->get(array('sku' => $sku));

                $where = array();
                $where['p.cat_id'] = $product_obj->get_cat_id();
                $where['p.sub_cat_id'] = $product_obj->get_sub_cat_id();
                $where['p.sub_sub_cat_id'] = $product_obj->get_sub_sub_cat_id();
                $where['p.sku != '] = $sku;

                $list = $this->get_product_service()->get_list_having_price($where, array('limit' => 1));
                if (count($list) == 0) {
                    $rrp_factor = $default_rrp_factor;
                } else {
                    $product_obj = $list[0];
                    $price_obj = $this->get(array('sku' => $product_obj->get_sku()));
                    if ($price_obj) {
                        $rrp_factor = $price_obj->get_rrp_factor();
                    } else {
                        $rrp_factor = $default_rrp_factor;
                    }
                }
            }
        }

        if (is_null($rrp_factor)) {
            $rrp_factor = $default_rrp_factor;
        }

        return $rrp_factor;
    }

    public function isDisplaySavingMessage($country_id = NULL)
    {
        if (is_null($country_id)) {
            $country_id = PLATFORMCOUNTRYID;
        }

        switch (strtolower($country_id)) {
            case 'au' :
            case 'hk' :
            case 'my' :
            case 'nz' :
            case 'ph' :
            case 'sg' :
                $result = 'T';
                break;

            default   :
                $result = 'F';
        }

        return $result;
    }
}


