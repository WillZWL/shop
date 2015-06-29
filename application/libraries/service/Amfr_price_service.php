<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Platform_price_service.php";

class Amfr_price_service extends Platform_price_service
{

    public function __construct()
    {
        parent::Platform_price_service();
        $classname = "Product_cost_dto";
        $this->set_dto_classname($classname);
        $file_path = APPPATH . "libraries/dto/" . strtolower($classname) . ".php";
        if (file_exists($file_path)) {
            include_once $file_path;
            $this->set_dto(new $classname());
        }

        include_once(APPPATH . "libraries/service/Price_service.php");
        $this->price_service = new Price_service();

        include_once(APPPATH . "libraries/service/Product_service.php");
        $this->product_service = new Product_service();

        include_once(APPPATH . "libraries/service/Shiptype_service.php");
        $this->shiptype_service = new Shiptype_service();

        include_once(APPPATH . "libraries/service/Warehouse_service.php");
        $this->warehouse_service = new Warehouse_service();

        include_once(APPPATH . "libraries/service/Platform_biz_var_service.php");
        $this->platform_biz_var_service = new Platform_biz_var_service();

        include_once(APPPATH . "libraries/service/Price_margin_service.php");
        $this->price_margin_service = new Price_margin_service();
    }

    public function set_dto_classname($classname)
    {
        $this->dto_classname = $classname;
    }

    public function set_dto(Base_dto $obj)
    {
        $this->dto = $obj;
        $this->get_dto()->set_delivery_charge(0);
        $this->get_dto()->set_default_delivery_charge($this->get_dto()->get_delivery_charge());
        $this->get_dto()->set_declared_pcent(100);
        $this->get_dto()->set_admin_fee(0);

    }

    public function get_dto()
    {
        return $this->dto;
    }

    public function get_dto_classname()
    {
        return $this->dto_classname;
    }

    public function set_price($value)
    {
        $this->get_dto()->set_price($value);
    }

    //The calculation below are all based from what we have on SE right now
    //Commented by Jack 3rd September 2009

    public function draw_table_header_row()
    {

        $decl_vat = "";
        if ($this->show_decl_vat) {
            $decl_vat = "
                        <td>\$lang[declared] (" . $this->get_dto()->get_declared_pcent() . "%)</td>
                        <td>\$lang[vat] (" . $this->get_dto()->get_vat_percent() . "%)</td>
            ";
        }

        $header .= "\$header = \"<tr class='header'>
                        <td>&nbsp;</td>
                        <td>\$lang[shipping]</td>
                        <td>\$lang[selling_price]</td>
                        $decl_vat
                        <td>\$lang[duty] (" . $this->get_dto()->get_duty_pcent() . "%)</td>
                        <td>\$lang[charge]</td>
                        <td>\$lang[admin_fee]</td>
                        <td>\$lang[freight]</td>
                        <td>\$lang[postage]</td>
                        <td>\$lang[platform_commission] (" . $this->get_dto()->get_platform_commission() . "%)</td>
                        <td>\$lang[cost]</td>
                        <td>\$lang[total_cost]</td>
                        <td>\$lang[delivery]</td>
                        <td>\$lang[total]</td>
                        <td>\$lang[profit]</td>
                        <td>\$lang[margin]</td>
                        <td>&nbsp;</td>
                    </tr>\";";
        return $header;
    }

    public function draw_table_row_for_pricing_tool($default_shiptype = "")
    {

        $delivery = $this->get_dto()->get_platform_delivery_charge();


        $total = $this->get_dto()->get_price() + $this->get_dto()->get_platform_delivery_charge();


        $bgcolor = $total > $this->get_dto()->get_cost() ? "#ddffdd" : "#ffdddd";
        $profit = $total - $this->get_dto()->get_cost();
        if ($this->get_dto()->get_price() > 0) {
            $margin = number_format($profit / ($this->get_dto()->get_price() - $this->get_dto()->get_vat()) * 100, 2, ".", ",");
        } else {
            $margin = 0;
        }
        $type = $this->get_dto()->get_shiptype();

        $this->get_dto()->set_freight_cost(round($this->get_dto()->get_freight_cost() * 1.01, 2));
        $this->get_dto()->set_supplier_cost(round($this->get_dto()->get_supplier_cost() * 1.01, 2));

        $decl_vat = "";
        if ($this->show_decl_vat) {
            $decl_vat = '
                        <td id="declare[' . $type . ']">' . number_format($this->get_dto()->get_declared_value(), 2) . '</td>
                        <td id="vat[' . $type . ']">' . number_format($this->get_dto()->get_vat(), 2) . '</td>
            ';
        }

        $table_row .= '<tr id="row[' . $type . ']" style="background-color:' . $bgcolor . '">
                        <td><input type="radio" name="default_shipping" value="' . $type . '" ' . (($default_shiptype == $this->get_dto()->get_shiptype() || ($default_shiptype == "" && $type == $this->get_dto()->get_platform_default_shiptype())) ? "CHECKED" : "") . '></td>
                        <td>' . $this->get_dto()->get_shiptype_name() . '</td>
                        <td><input type="text" name="selling_price" value="' . $this->get_dto()->get_price() . '" id="sp[' . $type . ']" onKeyup="rePrice(' . $type . ')" style="width:50px;" notEmpty></td>

                        <td id="duty[' . $type . ']">' . number_format($this->get_dto()->get_duty(), 2) . '</td>
                        <td id="pc[' . $type . ']">' . number_format($this->get_dto()->get_payment_charge(), 2) . '</td>
                        <td id="admin_fee[' . $type . ']">' . number_format($this->get_dto()->get_admin_fee(), 2) . '</td>
                        <td id="freight_cost[' . $type . ']">' . number_format($this->get_dto()->get_freight_cost(), 2) . '</td>
                        <td id="delivery_cost[' . $type . ']">' . number_format($this->get_dto()->get_delivery_cost(), 2) . '</td>

                        <td id="platform_comm[' . $type . ']">' . number_format(($this->get_dto()->get_platform_commission() / 100 * ($delivery + $this->get_dto()->get_price())), 2) . '</td>
                        <td id="supplier_cost[' . $type . ']">' . number_format($this->get_dto()->get_supplier_cost(), 2) . '</td>
                        <td id="total_cost[' . $type . ']">' . number_format($this->get_dto()->get_cost(), 2) . '</td>
                        <td id="delivery_charge[' . $type . ']">' . number_format($this->get_dto()->get_platform_delivery_charge(), 2) . '</td>
                        <td id="total[' . $type . ']">' . number_format(($this->get_dto()->get_price() + $delivery), 2) . '</td>
                        <td id="profit[' . $type . ']">' . number_format($profit, 2) . '</td>
                        <td id="margin[' . $type . ']">' . number_format($margin, 2) . '%</td>
                        <td><input type="hidden" id="declared_rate[' . $type . ']" value="' . $this->get_dto()->get_declared_pcent() . '">
                            <input type="hidden" id="payment_charge_rate[' . $type . ']" value="' . $this->get_dto()->get_payment_charge_percent() . '">
                            <input type="hidden" id="vat_percent[' . $type . ']" value="' . $this->get_dto()->get_vat_percent() . '">
                            <input type="hidden" id="duty_percent[' . $type . ']" value="' . $this->get_dto()->get_duty_pcent() . '">
                            <input type="hidden" id="free_delivery_limit[' . $type . ']" value="' . $this->get_dto()->get_free_delivery_limit() . '">
                            <input type="hidden" id="default_delivery_charge[' . $type . ']" value="' . $this->get_dto()->get_default_delivery_charge() . '">
                            <input type="hidden" id="platform_commission[' . $type . ']" value = "' . $this->get_dto()->get_platform_commission() . '">
                            <input type="hidden" id="scost[' . $type . ']" value="' . number_format($this->get_dto()->get_supplier_cost(), 2) . '">
                        </td>
                     </tr>
                    ' . "\n";
        return $table_row;
    }

    //This function can only be called after get_decalred_value

    public function get_product_overview_tr($where = array(), $option = array(), $classname = "", $lang = array())
    {
        if ($option["refresh_margin"] == 1) {
            //$this->price_margin_service->refresh_margin('AMFR');
            $this->price_margin_service->refresh_latest_margin(array("v_prod_overview_w_update_time.platform_id" => "AMFR"));
        }

        if ($objlist = $this->product_service->get_dao()->get_product_overview($where, $option, $classname)) {
            include_once BASEPATH . "helpers/url_helper.php";
            $data["tr"] = $data["js"] = "";

            $data["js"] .= "
                            <script>
                                var prod = new Array();
                            ";

            $ar_ws_status = array("I" => $lang["instock"], "O" => $lang["outstock"], "P" => $lang["pre-order"], "A" => $lang["arriving"]);
            $ar_src_status = array("A" => $lang["available"], "C" => $lang["stock_constraint"], "O" => $lang["temp_out_of_stock"], "L" => $lang["last_lot"], "D" => $lang["discontinued"]);
            $ar_list_status = array("L" => $lang["listed"], "N" => $lang["not_listed"]);
            $cr_array = array($lang["no"], $lang["yes"]);
            $i = 0;
            foreach ($objlist as $obj) {
                $this->set_dto($obj);
                $this->calc_profit();
                $cur_row = $i % 2;
                $selected_ws = array();
                $selected_ls = array();
                $sws_status = "";
                $list_status = "";
                $selected_ws[$obj->get_website_status()] = "SELECTED";
                $selected_ls[$obj->get_listing_status()] = "SELECTED";
                foreach ($ar_ws_status as $rskey => $rsvalue) {
                    $sws_status .= "<option value='{$rskey}' {$selected_ws[$rskey]}>{$rsvalue}";
                }
                foreach ($ar_list_status as $rkey => $rvalue) {
                    $list_status .= "<option value='{$rkey}' {$selected_ls[$rkey]}>{$rvalue}</option>";
                }
                $sku = $obj->get_sku();
                $price = $obj->get_price() * 1;
                $cost = $obj->get_cost();
                $profit = $obj->get_profit();
                $margin = $obj->get_margin();
                $asin = $obj->get_platform_code();
                $weblink = ($asin == "" ? "#" : "http://www.amazon.fr/dp/" . $asin);
                $updated = substr($obj->get_purchaser_updated_date(), 0, 10);
                $dclass = ((mktime() - strtotime($updated)) > 2592000) ? " class='warn'" : "";
                $pclass = ($profit < 0) ? " class='warn'" : "";
                $data["tr"] .= "
                                <tr class='row{$cur_row}' onMouseOver=\"AddClassName(this, 'highlight')\" onMouseOut=\"RemoveClassName(this, 'highlight')\">
                                    <td></td>
                                    <td><a href='" . base_url() . "marketing/pricing_tool_amfr/view/{$sku}?target=overview' target='amfrpt' onClick=\"Pop('" . base_url() . "marketing/pricing_tool_amfr/view/{$sku}?target=overview','amfrpt');\">{$sku}</a></td>
                                    <td>{$obj->get_prod_name()}</td>
                                    <td><input class='input' name='price[{$sku}][platform_code]' value='{$this->get_dto()->get_platform_code()}' onChange='needToConfirm=true'></td>
                                    <td>{$cr_array[$obj->get_clearance()]}</td>
                                    <td>
                                        <select name='price[{$sku}][listing_status]' onChange='needToConfirm=true'>
                                                {$list_status}
                                        </select>
                                    </td>
                                    <td>{$obj->get_inventory()}</td>
                                    <td><input type='hidden' name='price[{$sku}][default_shiptype]' value='{$this->get_dto()->get_shiptype()}'><input class='input' name='product[{$sku}][website_quantity]' value='{$obj->get_website_quantity()}' notEmpty isInteger onChange='needToConfirm=true'></td>
                                    <td>
                                        <select name='product[{$sku}][website_status]' class='input' onChange='needToConfirm=true'>
                                            {$sws_status}
                                        </select>
                                    </td>
                                    <td>{$ar_src_status[$obj->get_sourcing_status()]}</td>
                                    <td{$dclass}>{$updated}</td>
                                    <td>{$obj->get_shiptype_name()}</td>
                                    <td align='right'><input class='int2_input read' name='cost[{$sku}]' value='" . number_format($cost, 2) . "'></td>
                                    <td><input class='int2_input' name='price[{$sku}][price]' value='{$obj->get_price()}' onKeyUp=\"CalcProfit('{$sku}', this.value)\" isNumber min='0' onChange='needToConfirm=true'></td>
                                    <td id='profit[{$sku}]' align='right'{$pclass}>" . number_format($profit, 2) . "</td>
                                    <td id='margin[{$sku}]' align='right'{$pclass}>" . number_format($margin, 2) . "%</td>
                                    <td><input type='checkbox' name='check[]' value='{$sku}' onClick='Marked(this);'></td>
                                </tr>
                                ";
                $this->get_dto()->set_freight_cost(round($this->get_dto()->get_freight_cost() * 1.01, 2));
                $this->get_dto()->set_supplier_cost(round($this->get_dto()->get_supplier_cost() * 1.01, 2));

                $data["js"] .= "prod['{$sku}'] = {'clearance':{$this->get_dto()->get_clearance()},'declared_rate':{$this->get_dto()->get_declared_pcent()},'payment_charge_rate':{$this->get_dto()->get_payment_charge_percent()},'vat_percent':{$this->get_dto()->get_vat_percent()},'duty_percent':{$this->get_dto()->get_duty_pcent()},'free_delivery_limit':{$this->get_dto()->get_free_delivery_limit()},'default_delivery_charge':{$this->get_dto()->get_default_delivery_charge()},'admin_fee':{$this->get_dto()->get_admin_fee()},'freight_cost':{$this->get_dto()->get_freight_cost()},'delivery_cost':{$this->get_dto()->get_delivery_cost()},'supplier_cost':{$this->get_dto()->get_supplier_cost()},'platform_delivery_charge':{$this->get_dto()->get_platform_delivery_charge()},'platform_commission':{$this->get_dto()->get_platform_commission()}};
                                ";
                $i++;
            }
            if ($i > 0) {
                $data["tr"] .= "<tr class='tb_brow' align='right'><td colspan='17'>{$lang['currency']}: <b>{$obj->get_platform_currency_id()}<b></td></tr>";
                $data["js"] .= "</script>";
            } else {
                $data["tr"] = $data["js"] = "";
            }
        }
        return $data;
    }

    public function calc_profit($src = NULL)
    {
        $dto = $src;

        if (!$dto) {
            $dto = $this->get_dto();
        }


        //echo $dto->get_price();
        $this->calc_declared_value($dto);

        $this->calc_vat($dto);
        $this->calc_duty($dto);
        $this->calc_payment_charge($dto);
        //$this->calc_delivery_charge();

        $dto->set_cost(
            round($dto->get_vat(), 2)
            + round($dto->get_duty(), 2)
            + round($dto->get_payment_charge(), 2)
            + round($dto->get_admin_fee(), 2)
            + round($dto->get_freight_cost() * 1.01, 2)
            + round($dto->get_delivery_cost(), 2)
            + round($dto->get_supplier_cost() * 1.01, 2)
            + round(($dto->get_platform_commission() / 100 * ($dto->get_price() + $dto->get_platform_delivery_charge())), 2)
        );

        if ($dto->get_price() != "") {
            $price = $dto->get_price() * 1;
            $cost = $dto->get_cost();
            $profit = number_format($price ? $price + $dto->get_platform_delivery_charge() - $cost : 0, 2, ".", "");
            $dto->set_profit($profit);
            $margin = number_format($price && ($price - $dto->get_vat()) ? $profit / ($price - $dto->get_vat()) * 100 : 0, 2, ".", "");
            $dto->set_margin($margin);
        }
        //var_dump($dto);
    }

    private function calc_declared_value($src = NULL)
    {
        $dto = $src;

        if (!$dto) {
            $dto = $this->get_dto();
        }

        $declared_value = $dto->get_price() * $dto->get_declared_pcent() / 100;
        $dto->set_declared_value(number_format($declared_value, 2, ".", ""));
    }

    private function calc_vat($src = NULL)
    {
        $dto = $src;

        if (!$dto) {
            $dto = $this->get_dto();
        }

        $vat = ($dto->get_declared_value() * 1 + $dto->get_platform_delivery_charge() * 1) * $dto->get_vat_percent() / (100 + $dto->get_vat_percent());
        $dto->set_vat(round($vat, 2));
        //$dto->set_vat(number_format($vat,2,".",""));
    }

    private function calc_duty($src = NULL)
    {
        $dto = $src;

        if (!$dto) {
            $dto = $this->get_dto();
        }

        $duty = $dto->get_declared_value() * $dto->get_duty_pcent() / 100;
        $dto->set_duty($duty);
    }

    private function calc_payment_charge($src = NULL)
    {
        $dto = $src;

        if (!$dto) {
            $dto = $this->get_dto();
        }

        $payment_charge = $dto->get_payment_charge_percent() * $dto->get_price() / 100;
        $dto->set_payment_charge($payment_charge);
    }

    public function get_prod_st_profit($where = array(), $option = array(), $classname = "Product_cost_dto")
    {
        $where["platform_id"] = "AMFR";
        if ($objlist = $this->shiptype_service->get_dao()->get_product_shiptype($where, $option, $classname)) {
            $data["low_profit"] = $data["max_cost"] = array();
            foreach ($objlist as $obj) {
                $this->set_dto($obj);
                $this->calc_profit();

                if (empty($data["low_profit"])) {
                    $data["low_profit"] = $obj;
                } elseif ($obj->get_profit() < $data["low_profit"]->get_profit()) {
                    $data["low_profit"] = $obj;
                }

                $newobj = clone $obj;
                $this->set_dto($newobj);
                $this->calc_region_freight_cost($newobj, $option["freight_region"]);
                $this->calc_profit();
                $this->calc_max_supp_cost();
                if (empty($data["max_cost"])) {
                    $data["max_cost"] = $newobj;
                } elseif ($newobj->get_supplier_cost() < $data["max_cost"]->get_supplier_cost()) {
                    $data["max_cost"] = $newobj;
                }
            }
            return $data;
        }
        return FALSE;
    }

    public function calc_region_freight_cost($src = NULL, $region_id)
    {
        $dto = $src;

        if (!$dto) {
            $dto = $this->get_dto();
        }

        $fc = $this->shiptype_service->get_dao()->get_freight_reg_prod_shiptype($dto->get_shiptype(), $region_id, $dto->get_sku());
        $dto->set_freight_cost(round($fc, 2));
    }

    public function calc_max_supp_cost($src = NULL)
    {
        $dto = $src;

        if (!$dto) {
            $dto = $this->get_dto();
        }

        $dto->set_supplier_cost(number_format(
            (round($dto->get_price(), 2)
                + round($dto->get_platform_delivery_charge(), 2)
                - round($dto->get_cost(), 2)
                + round($dto->get_supplier_cost() * 1.01, 2)
            ) / 1.01 - 0.005
            , 2, ".", ""));
    }

    public function get_product_overview($where = array(), $option = array(), $classname = "Product_cost_dto")
    {
        return $this->product_service->get_dao()->get_product_overview($where, $option, $classname);
    }

    public function get_ixtens_reprice_list($where = array(), $option = array(), $classname = "Product_cost_dto")
    {
        $where["vpo.platform_id"] = "AMFR";
        if ($objlist = $this->product_service->get_dao()->get_ixtens_repice_list($where, $option, $classname)) {

            $data["min_price"] = $data["sku"] = $data["current_price"] = array();
            foreach ($objlist as $obj) {
                $sku = $obj->get_sku();
                $this->set_dto($obj);
                $this->calc_profit();
                $data["sku"][$sku] = 1;

                if ($obj->get_price() <= 1000) {
                    $rate = 0.04;
                } else {
                    $rate = 0.03;
                }

                $factor = 1 - ($rate + $obj->get_vat_percent() / 100) / (1 + $obj->get_vat_percent() / 100) - $obj->get_platform_commission() / 100;

                $data["min_price"][$sku] = round(($obj->get_supplier_cost() * 1.01 + $obj->get_freight_cost() * 1.01 + $obj->get_delivery_cost()) / $factor, 2);
                $data["current_price"][$sku] = $obj->get_price();
            }
            return $data;
        }
        return FALSE;
    }

    public function check_profit($dto)
    {
        if ($dto == NULL) {
            return FALSE;
        } else {

            $this->set_dto($dto);
            $this->calc_profit();

            if ($dto->get_price() <= 1500) {
                $rate = 0.04;
            } else {
                $rate = 0.03;
            }

            $factor = 1 - ($rate + $dto->get_vat_percent() / 100) / (1 + $dto->get_vat_percent() / 100) - $dto->get_platform_commission() / 100;

            $minprice = ($dto->get_supplier_cost() * 1.01 + $dto->get_freight_cost() * 1.01 + $dto->get_delivery_cost()) / $factor;

            return array("profitable" => round($this->get_dto()->get_profit(), 2) >= 0.00 ? 1 : 0, "minprice" => $minprice);
        }
    }

    public function get_price($sku)
    {
        $where["platform_id"] = "AMFR";
        $where["prod_sku"] = $sku;
        $total = 0;
        if ($objlist = $this->price_service->get_dao()->get_items_w_price($where)) {
            foreach ($objlist as $obj) {
                if (($cur_price = $this->get_item_price($obj)) !== FALSE) {
                    $total += $cur_price;
                } else {
                    return FALSE;
                }
            }
            return number_format($total, 2, ".", "");
        }
        return FALSE;
    }

    public function get_item_price($obj)
    {
        if (method_exists($obj, "get_price") && method_exists($obj, "get_discount")) {
            $price = $obj->get_price();
            if (!is_null($price)) {
                return number_format($price * (100 - $obj->get_discount() * 1) / 100, 2, ".", "");
            }
        }
        return FALSE;
    }

    private function calc_delivery_charge($src = NULL)
    {
        $dto = $src;

        if (!$dto) {
            $dto = $this->get_dto();
        }

        $fdl = $dto->get_free_delivery_limit();
        $price = $dto->get_price();

        if ($fdl > 0 && $price >= $fdl) {
            $dc = 0;
        } else {
            $dc = $dto->get_delivery_charge();
        }

        $dto->set_delivery_charge($dc);
    }


}

/* End of file amfr_price_service.php */
/* Location: ./system/application/libraries/service/Amfr_price_service.php */
