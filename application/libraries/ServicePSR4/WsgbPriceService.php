<?php
namespace ESG\Panther\Service;

use ESG\Panther\Service\PriceMarginService;
use ESG\Panther\Service\ShiptypeService;
use ESG\Panther\Service\PriceService;
use ESG\Panther\Service\ProductService;

class WsgbPriceService extends PlatformPriceService
{
    public function __construct()
    {
        parent::__construct();

        $this->setDtoClassname('ProductCostDto');

        $classname = $this->getDtoClassname();
        $this->setDtoClassname($classname);
        $file_path = APPPATH . "libraries/dto/" . strtolower($classname) . ".php";
        if (file_exists($file_path)) {
            include_once $file_path;
            $this->setDto(new $classname());
        }


        // include_once(APPPATH . "libraries/service/Warehouse_service.php");
        // include_once(APPPATH . "libraries/service/Platform_biz_var_service.php");
        // include_once(APPPATH . "libraries/service/Ra_prod_prod_service.php");
        // include_once(APPPATH . "libraries/dao/Platform_courier_dao.php");
        // $this->set_pc_dao(new Platform_courier_dao());

        $this->priceMarginService = new PriceMarginService;
        $this->shiptypeService = new ShiptypeService;
        $this->productService = new ProductService;
        $this->priceService = new PriceService;
    }

    // Please avoid using this function.
    public function setDto($obj)
    {
        $this->dto = $obj;
        $this->getDto()->setDefaultDeliveryCharge($this->getDto()->getDeliveryCharge());
    }

    public function getDto()
    {
        return $this->dto;
    }

    //The calculation below are all based from what we have on SE right now
    //Commented by Jack 3rd September 2009

    public function setPrice($value)
    {
        $this->getDto()->setPrice($value);
    }

    public function drawTableHeaderRow()
    {
        $decl_vat = "";
        if ($this->show_decl_vat) {
            $decl_vat = "
                        <td>\$lang[declared](" . $this->getDto()->getDeclaredPcent() . "%)</td>
                        <td>\$lang[vat](" . $this->getDto()->getVatPercent() . "%)</td>
            ";
        }

        $header .= "\$header = \"<tr class='header'>
                        <td>&nbsp;</td>
                        <td>\$lang[shipping]</td>
                        <td>\$lang[selling_price]</td>
                        $decl_vat
                        <td>\$lang[duty](" . $this->getDto()->getDutyPcent() . "%)</td>
                        <td>\$lang[charge]</td>
                        <td>\$lang[admin_fee]</td>
                        <td>\$lang[freight]</td>
                        <td>\$lang[postage]</td>
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

    public function drawTableRowForPricingTool($default_shiptype = "")
    {

        $delivery = ($this->getDto()->getFreeDeliveryLimit() > 0 && $this->getDto()->getPrice() > $this->getDto()->getFreeDeliveryLimit() ? 0.00 : $this->getDto()->getDeliveryCharge());
        if ($delivery == NULL) {
            $delivery = 0.00;
        }

        $total = $this->getDto()->getPrice() + $this->getDto()->getDeliveryCharge();

        $bgcolor = $total > $this->getDto()->getCost() ? "#ddffdd" : "#ffdddd";
        $profit = $total - $this->getDto()->getCost();
        if ($this->getDto()->getPrice() > 0) {
            $margin = number_format($profit / ($this->getDto()->getPrice() - $this->getDto()->getVat()) * 100, 2, ".", ",");
        } else {
            $margin = 0;
        }

        $type = $this->getDto()->getShiptype();

        $this->getDto()->setFreightCost(round($this->getDto()->getFreightCost() * 1.01, 2));
        $this->getDto()->setSupplierCost(round($this->getDto()->getSupplierCost() * 1.01, 2));

        $decl_vat = "";
        if ($this->show_decl_vat) {
            $decl_vat = '
                        <td id="declare[' . $type . ']">' . number_format($this->getDto()->getDeclaredValue(), 2) . '</td>
                        <td id="vat[' . $type . ']">' . number_format($this->getDto()->getVat(), 2) . '</td>
            ';
        }

        $table_row .= '<tr id="row[' . $type . ']" style="background-color:' . $bgcolor . '">
                        <td><input type="radio" name="default_shipping" value="' . $type . '" ' . (($default_shiptype == $this->getDto()->getShiptype() || ($default_shiptype == "" && $type == $this->getDto()->getPlatformDefaultShiptype())) ? "CHECKED" : "") . '></td>
                        <td>' . $this->getDto()->getShiptypeName() . '</td>
                        <td><input type="text" name="selling_price" value="' . $this->getDto()->getPrice() . '" id="sp[' . $type . ']" onKeyup="rePrice(' . $type . ')" style="width:50px;" notEmpty></td>
                        ' . $decl_vat . '
                        <td id="duty[' . $type . ']">' . number_format($this->getDto()->getDuty(), 2) . '</td>
                        <td id="pc[' . $type . ']">' . number_format($this->getDto()->getPaymentCharge(), 2) . '</td>
                        <td id="admin_fee[' . $type . ']">' . number_format($this->getDto()->getAdminFee(), 2) . '</td>
                        <td id="freight_cost[' . $type . ']">' . number_format($this->getDto()->getFreightCost(), 2) . '</td>
                        <td id="delivery_cost[' . $type . ']">' . number_format($this->getDto()->getDeliveryCost(), 2) . '</td>
                        <td id="supplier_cost[' . $type . ']">' . number_format($this->getDto()->getSupplierCost(), 2) . '</td>
                        <td id="total_cost[' . $type . ']">' . number_format($this->getDto()->getCost(), 2) . '</td>
                        <td id="delivery_charge[' . $type . ']">' . number_format($delivery, 2) . '</td>
                        <td id="total[' . $type . ']">' . number_format(($this->getDto()->getPrice() + $delivery), 2) . '</td>
                        <td id="profit[' . $type . ']">' . number_format($profit, 2) . '</td>
                        <td id="margin[' . $type . ']">' . number_format($margin, 2) . '%</td>
                        <td><input type="hidden" id="declared_rate[' . $type . ']" value="' . $this->getDto()->getDeclaredPcent() . '">
                            <input type="hidden" id="payment_charge_rate[' . $type . ']" value="' . $this->getDto()->getPaymentChargePercent() . '">
                            <input type="hidden" id="vat_percent[' . $type . ']" value="' . $this->getDto()->getVatPercent() . '">
                            <input type="hidden" id="duty_percent[' . $type . ']" value="' . $this->getDto()->getDutyPcent() . '">
                            <input type="hidden" id="free_delivery_limit[' . $type . ']" value="' . $this->getDto()->getFreeDeliveryLimit() . '">
                            <input type="hidden" id="default_delivery_charge[' . $type . ']" value="' . $this->getDto()->getDefaultDeliveryCharge() . '">
                            <input type="hidden" id="scost[' . $type . ']" value="' . $this->getDto()->getSupplierCost() . '">
                        </td>
                     </tr>
                    ' . "\n";
        return $table_row;
    }

    //This function can only be called after get_decalred_value

    public function getProductOverviewTr($where = [], $option = [], $classname = "ProductCostDto", $lang = [])
    {
        if ($objlist = $this->productService->getDao('Product')->getProductOverview($where, $option, $classname)) {
            include_once BASEPATH . "helpers/url_helper.php";
            $data["tr"] = $data["js"] = "";

            $data["js"] .= "
                            <script>
                                var prod = new [];
                            ";

            $ar_ws_status = array("I" => $lang["instock"], "O" => $lang["outstock"], "P" => $lang["pre-order"], "A" => $lang["arriving"]);
            $ar_src_status = array("A" => $lang["available"], "C" => $lang["stock_constraint"], "O" => $lang["temp_out_of_stock"], "L" => $lang["last_lot"], "D" => $lang["discontinued"]);
            $cr_array = array($lang["no"], $lang["yes"]);
            $i = 0;
            foreach ($objlist as $obj) {
                $this->setDto($obj);
                $this->calcProfit();
                $cur_row = $i % 2;
                $selected_ws = [];
                $sws_status = "";
                $selected_ws[$obj->getWebsiteStatus()] = "SELECTED";
                foreach ($ar_ws_status as $rskey => $rsvalue) {
                    $sws_status .= "<option value='{$rskey}' {$selected_ws[$rskey]}>{$rsvalue}";
                }
                $sku = $obj->getSku();
                $price = $obj->getPrice() * 1;
                $cost = $obj->getCost();
                $profit = $obj->getProfit();
                $margin = $obj->getMargin();
                $updated = substr($obj->getPurchaserUpdatedDate(), 0, 10);
                $dclass = ((mktime() - strtotime($updated)) > 2592000) ? " class='warn'" : "";
                $pclass = ($profit < 0) ? " class='warn'" : "";
                $data["tr"] .= "
                                <tr class='row{$cur_row}' onMouseOver=\"AddClassName(this, 'highlight')\" onMouseOut=\"RemoveClassName(this, 'highlight')\">
                                    <td></td>
                                    <td><a href='" . base_url() . "marketing/pricing_tool/view/{$sku}?target=overview' target='" . str_replace("-", "_", $sku) . "' onClick=\"Pop('" . base_url() . "marketing/pricing_tool/view/{$sku}?target=overview','" . str_replace("-", "_", $sku) . "');\">{$sku}</a></td>
                                    <td>{$obj->getProdName()}</td>
                                    <td>{$cr_array[$obj->getClearance()]}</td>
                                    <td>{$obj->getInventory()}</td>
                                    <td><input type='hidden' name='price[{$sku}][default_shiptype]' value='{$this->getDto()->getShiptype()}'><input class='input' name='product[{$sku}][website_quantity]' value='{$obj->getWebsiteQuantity()}' notEmpty isInteger onChange='needToConfirm=true'></td>
                                    <td>
                                        <select name='product[{$sku}][website_status]' class='input' onChange='needToConfirm=true'>
                                            {$sws_status}
                                        </select>
                                    </td>
                                    <td>{$ar_src_status[$obj->getSourcingStatus()]}</td>
                                    <td{$dclass}>{$updated}</td>
                                    <td>{$obj->getShiptypeName()}</td>
                                    <td align='right'><input class='int2_input read' name='cost[{$sku}]' value='" . number_format($cost, 2) . "'></td>
                                    <td><input class='int2_input' name='price[{$sku}][price]' value='{$obj->getPrice()}' onKeyUp=\"CalcProfit('{$sku}', this.value)\" isNumber min='0' onChange='needToConfirm=true'></td>
                                    <td id='profit[{$sku}]' align='right'{$pclass}>" . number_format($profit, 2) . "</td>
                                    <td id='margin[{$sku}]' align='right'{$pclass}>" . number_format($margin, 2) . "%</td>
                                    <td><input type='checkbox' name='check[]' value='{$sku}' onClick='Marked(this);'></td>
                                </tr>
                                ";
                $this->getDto()->setFreightCost(round($this->getDto()->getFreightCost() * 1.01, 2));
                $this->getDto()->setSupplierCost(round($this->getDto()->getSupplierCost() * 1.01, 2));
                $data["js"] .= "prod['{$sku}'] = {'clearance':{$this->getDto()->getClearance()},'declared_rate':{$this->getDto()->getDeclaredPcent()},'payment_charge_rate':{$this->getDto()->getPaymentChargePercent()},'vat_percent':{$this->getDto()->getVatPercent()},'duty_percent':{$this->getDto()->getDutyPcent()},'free_delivery_limit':{$this->getDto()->getFreeDeliveryLimit()},'default_delivery_charge':{$this->getDto()->getDefaultDeliveryCharge()},'admin_fee':{$this->getDto()->getAdminFee()},'freight_cost':{$this->getDto()->getFreightCost()},'delivery_cost':{$this->getDto()->getDeliveryCost()},'supplier_cost':{$this->getDto()->getSupplierCost()}};
                                ";
                $i++;
            }
            if ($i > 0) {
                $data["tr"] .= "<tr class='tb_brow' align='right'><td colspan='15'>{$lang['currency']}: <b>{$obj->getPlatformCurrencyId()}<b></td></tr>";
                $data["js"] .= "</script>";
            } else {
                $data["tr"] = $data["js"] = "";
            }
        }
        return $data;
    }

    public function calcProfit($src = NULL)
    {
        $dto = $src;

        if (!$dto) {
            $dto = $this->getDto();
        }

        $this->calcDeclaredValue($dto);
        $this->calcVat($dto);
        $this->calcItemVat($dto);
        $this->calcDuty($dto);
        $this->calcPaymentCharge($dto);
        $this->calcDeliveryCharge($dto);


        $dto->setCost(
            round($dto->getVat(), 2)
            + round($dto->getDuty(), 2)
            + round($dto->getPaymentCharge(), 2)
            + round($dto->getAdminFee(), 2)
            + round($dto->getFreightCost() * 1.01, 2)
            + round($dto->getDeliveryCost(), 2)
            + round($dto->getSupplierCost() * 1.01, 2)
        );

        if ($dto->getPrice() != "") {
            $price = $dto->getPrice() * 1;
            $cost = $dto->getCost();
            $profit = number_format($price ? $price + $dto->getDeliveryCharge() - $cost : 0, 2, ".", "");
            $dto->setProfit($profit);
            $margin = number_format($price && ($price - $dto->getVat()) ? $profit / ($price - $dto->getVat()) * 100 : 0, 2, ".", "");
            $dto->setMargin($margin);
        }
    }

    private function calcDeclaredValue($src = NULL)
    {
        $dto = $src;

        if (!$dto) {
            $dto = $this->getDto();
        }

        $declared_value = $dto->getPrice() * $dto->getDeclaredPcent() / 100;
        $dto->setDeclaredValue(number_format($declared_value, 2, ".", ""));
    }

    private function calcVat($src = NULL)
    {
        $dto = $src;

        if (!$dto) {
            $dto = $this->getDto();
        }

        $vat = ($dto->getDeclaredValue() * 1 + $dto->getDeliveryCharge() * 1) * $dto->getVatPercent() / (100 + $dto->getVatPercent());
        $dto->setVat(number_format($vat, 2, ".", ""));
    }

    public function calcItemVat($src = NULL)
    {

        $dto = $src;

        if (!$dto) {
            $dto = $this->getDto();
        }


        $item_vat = $dto->getPrice() * 1 * ($dto->getVatPercent() / (100 + $dto->getVatPercent()));
        $dto->setItemVat(number_format($item_vat, 2, ".", ""));
    }

    private function calcDuty($src = NULL)
    {
        $dto = $src;

        if (!$dto) {
            $dto = $this->getDto();
        }

        $duty = $dto->getDeclaredValue() * $dto->getDutyPcent() / 100;
        $dto->setDuty($duty);
    }

    private function calcPaymentCharge($src = NULL)
    {
        $dto = $src;

        if (!$dto) {
            $dto = $this->getDto();
        }

        $payment_charge = $dto->getPaymentChargePercent() * $dto->getPrice() / 100;
        $dto->setPaymentCharge($payment_charge);
    }

    private function calcDeliveryCharge($src = NULL)
    {
        $dto = $src;

        if (!$dto) {
            $dto = $this->getDto();
        }

        $fdl = $dto->getFreeDeliveryLimit();
        $price = $dto->getPrice();

        if ($fdl > 0 && $price >= $fdl) {
            $dc = 0;
        } else {
            $dc = $dto->getDeliveryCharge();
        }

        $dto->setDeliveryCharge($dc);
    }

    public function getComponentsTr($where = [], $option = [], $classname = "ProductCostDto", $lang = [])
    {
        if ($option["refresh_margin"] == 1) {
            $this->priceMarginService->refreshLatestMargin(["v_prod_overview_w_update_time.platform_id" => "WSGB"]);
        }

        if ($objlist = $this->productService->getDao('Product')->getComponentsWithName($where, $option, $classname)) {
            $data = "";
            $main_qty = $i = 0;
            $outstock = [];
            foreach ($objlist as $obj) {
                if ($i == 0) {
                    $main_qty = $obj->getInventory();
                }


                $cur_row = $i % 2;
                $this->setDto($obj);
                $this->calcProfit();
                $sku = $obj->getSku();

                if ($obj->getWebsiteStatus() == "O") {
                    $outstock[] = $sku;
                }

                $price = $obj->getPrice() * 1;
                $cost = $obj->getCost();
                $profit = $obj->getProfit();
                $margin = $obj->getMargin();
                $updated = substr($obj->getPurchaserUpdatedDate(), 0, 10);
                $declared_pcent = $obj->getDeclaredPcent() * 1;
                $vat_pcent = $obj->getVatPercent() * 1;
                $duty_pcent = $obj->getDutyPcent() * 1;
                $delivery_charge = $obj->getDeliveryCharge();
                $total_price = $price + $delivery_charge;
                $data .= "
                        <tr class='xrow{$cur_row}'>
                            <td>{$sku}</td>
                            <td>{$obj->getProdName()}</td>
                            <td align='right'>" . number_format($price, 2) . "</td>
                            <td align='right'>" . number_format($obj->getDeclaredValue(), 2) . "</td>
                            <td align='right'>" . number_format($obj->getVat(), 2) . "</td>
                            <td align='right'>{$obj->getDuty()}</td>
                            <td align='right'>{$obj->getPaymentCharge()}</td>
                            <td align='right'>{$obj->getAdminFee()}</td>
                            <td align='right'>{$obj->getFreightCost()}</td>
                            <td align='right'>" . number_format($obj->getCost(), 2) . "</td>
                            <td align='right'>{$delivery_charge}</td>
                            <td align='right'>" . number_format($total_price, 2) . "</td>
                            <td align='right'>" . number_format($profit, 2) . "</td>
                            <td align='right'>" . number_format($margin, 2) . "%</td>
                        </tr>
                        ";
                $i++;
            }
            if ($i > 0) {
                if ($outstock) {
                    $js_outstock = "document.fm.status.value = 'O';";
                }
                $data = "<tr class='header'><td>{$lang["sku"]}</td><td>{$lang["item"]}</td><td>{$lang["selling_price"]}</td><td>{$lang["decl"]} ({$declared_pcent}%)</td><td>{$lang["vat"]} ({$vat_pcent}%)</td><td>{$lang["duty"]} ({$duty_pcent}%)</td><td>{$lang["charge"]}</td><td>{$lang["admin_fee"]}</td><td>{$lang["freight"]}</td><td>{$lang["cost"]}</td><td>{$lang["delivery"]}</td><td>{$lang["total"]}</td><td>{$lang["profit"]}</td><td>{$lang["margin"]}</td></tr>"
                    . $data .
                    "<tr class='tb_row' align='right'><td colspan='14'>{$lang['currency']}: <b>{$obj->getPlatformCurrencyId()}<b></td></tr>"
                    . "<script>document.fm.inventory.value = {$main_qty};{$js_outstock}outstock = '" . @implode(", ", $outstock) . "'</script>";
            } else {
                $data = "";
            }
        }
        return $data;
    }

    public function getProductOverview($where = [], $option = [], $classname = "ProductCostDto")
    {
        return $this->productService->getDao('Product')->getProductOverview($where, $option, $classname);
    }

    public function getProductListWithProfit($where = [], $option = [], $classname = "ProductCostDto")
    {
        if ($objlist = $this->productService->getDao('Product')->getProductOverview($where, $option, $classname)) {
            foreach ($objlist as $obj) {
                $this->setDto($obj);
                $this->calcProfit();
            }
            return $objlist;
        }
        return FALSE;
    }

    public function getProductShiptype($where = [], $option = [], $classname = "ProductCostDto")
    {
        if ($objlist = $this->shiptypeService->getDao('Shiptype')->getProductShiptype($where, $option, $classname)) {
            foreach ($objlist as $obj) {
                $this->setDto($obj);
                $this->calcProfit();
            }
            return $objlist;
        }
        return FALSE;
    }

    public function getProdStProfit($where = [], $option = [], $classname = "ProductCostDto")
    {
        $where["platform_id"] = "WSGB";
        if ($objlist = $this->shiptypeService->getDao('Shiptype')->getProductShiptype($where, $option, $classname)) {
            $data["low_profit"] = $data["max_cost"] = [];
            foreach ($objlist as $obj) {
                $this->setDto($obj);
                $this->calcProfit();

                if (empty($data["low_profit"])) {
                    $data["low_profit"] = $obj;
                } elseif ($obj->getProfit() < $data["low_profit"]->getProfit()) {
                    $data["low_profit"] = $obj;
                }

                $newobj = clone $obj;
                $this->setDto($newobj);
                $this->calcRegionFreightCost($newobj, $option["freight_region"]);
                $this->calcProfit();
                $this->calcMaxSuppCost();
                if (empty($data["max_cost"])) {
                    $data["max_cost"] = $newobj;
                } elseif ($newobj->getSupplierCost() < $data["max_cost"]->getSupplierCost()) {
                    $data["max_cost"] = $newobj;
                }
            }
            return $data;
        }
        return FALSE;
    }

    public function calcRegionFreightCost($src = NULL, $region_id)
    {
        $dto = $src;

        if (!$dto) {
            $dto = $this->getDto();
        }

        $fc = $this->shiptypeService->getDao('Shiptype')->getFreightRegProdShiptype($dto->getShiptype(), $region_id, $dto->getSku());
        $dto->setFreightCost(round($fc, 2));
    }

    public function calcMaxSuppCost($src = NULL)
    {
        $dto = $src;

        if (!$dto) {
            $dto = $this->getDto();
        }

        $dto->setSupplierCost(number_format(
            (round($dto->getPrice(), 2)
                + round($dto->getDeliveryCharge(), 2)
                - round($dto->getCost(), 2)
                + round($dto->getSupplierCost() * 1.01, 2)
            ) / 1.01 - 0.005
            , 2, ".", ""));
    }

    public function getRaProdTr($prod_sku = "", $platform_id = "", $classname = "ProductCostDto", $lang = [])
    {
        if ($objlist = $this->productService->getDao('Product')->getRaProductOverview($prod_sku, $platform_id, $classname)) {
            $data = [];
            $i = 1;
            foreach ($objlist as $obj) {
                $this->setDto($obj);
                $this->calcProfit();
                $sku = $obj->getSku();
                $price = $obj->getPrice() * 1;
                $cost = $obj->getCost();
                $profit = $obj->getProfit();
                $margin = $obj->getMargin();
                $updated = substr($obj->getPurchaserUpdatedDate(), 0, 10);
                $declared_pcent = $obj->getDeclaredPcent() * 1;
                $vat_pcent = $obj->getVatPercent() * 1;
                $duty_pcent = $obj->getDutyPcent() * 1;
                $delivery_charge = $obj->getDeliveryCharge();
                $total_price = $price + $delivery_charge;

                if ($sku == $prod_sku) {
                    $prod_order = 0;
                } else {
                    $prod_order = $i;
                }
                $prod_check = ($prod_order == 0) ? " onClick='return false;' CHECKED" : "onClick='ChangeName()'";
                $data[$prod_order] = "
                            <td nowrap style='white-space: nowrap'>{$sku}</td>
                            <td>{$obj->getProdName()}</td>
                            <td align='right'>" . number_format($price, 2) . "</td>
                            <td align='right'>" . number_format($obj->getDeclaredValue(), 2) . "</td>
                            <td align='right'>" . number_format($obj->getVat(), 2) . "</td>
                            <td align='right'>" . number_format($obj->getDuty(), 2) . "</td>
                            <td align='right'>" . number_format($obj->getPaymentCharge(), 2) . "</td>
                            <td align='right'>" . number_format($obj->getAdminFee(), 2) . "</td>
                            <td align='right'>" . number_format($obj->getFreightCost(), 2) . "</td>
                            <td align='right'>" . number_format($obj->getCost(), 2) . "</td>
                            <td align='right'>{$delivery_charge}</td>
                            <td align='right'>" . number_format($total_price, 2) . "</td>
                            <td align='right'>" . number_format($profit, 2) . "</td>
                            <td align='right'>" . number_format($margin, 2) . "%</td>
                            <td><input name='components[{$prod_order}]' type='checkbox' value='{$sku}::{$obj->getProdName()}'{$prod_check}></td>
                        </tr>
                        ";
                $i++;
            }
            if ($i > 1) {
                ksort($data);
                $new_data = "";
                $k = 0;
                foreach ($data as $prod_order => $rsdata) {
                    $cur_row = $k % 2;
                    $new_data .= "<tr class='xrow{$cur_row}'>" . $rsdata;
                    $k++;
                }
                unset($data);
                $data = "<tr class='header'><td>{$lang["sku"]}</td><td>{$lang["item"]}</td><td>{$lang["selling_price"]}</td><td>{$lang["decl"]} ({$declared_pcent}%)</td><td>{$lang["vat"]} ({$vat_pcent}%)</td><td>{$lang["duty"]} ({$duty_pcent}%)</td><td>{$lang["charge"]}</td><td>{$lang["admin_fee"]}</td><td>{$lang["freight"]}</td><td>{$lang["cost"]}</td><td>{$lang["delivery"]}</td><td>{$lang["total"]}</td><td>{$lang["profit"]}</td><td>{$lang["margin"]}</td><td></td></tr>"
                    . $new_data .
                    "<tr class='tb_row' align='right'><td colspan='15'>{$lang['currency']}: <b>{$obj->getPlatformCurrencyId()}<b></td></tr>";
            } else {
                $data = "";
            }
        }
        return $data;
    }

    public function getPrice($sku)
    {
        $where["platform_id"] = "WSGB";
        $where["prod_sku"] = $sku;
        $total = 0;
        if ($objlist = $this->priceService->getDao('Price')->getItemsWithPrice($where)) {
            foreach ($objlist as $obj) {
                if (($cur_price = $this->getItemPrice($obj)) !== FALSE) {
                    $total += $cur_price;
                } else {
                    return FALSE;
                }
            }
            return number_format($total, 2, ".", "");
        }
        return FALSE;
    }

    public function getItemPrice($obj)
    {
        if (method_exists($obj, "getPrice") && method_exists($obj, "getDiscount")) {
            $price = $obj->getPrice();
            if (!is_null($price)) {
                return number_format($price * (100 - $obj->getDiscount() * 1) / 100, 2, ".", "");
            }
        }
        return FALSE;
    }

}
