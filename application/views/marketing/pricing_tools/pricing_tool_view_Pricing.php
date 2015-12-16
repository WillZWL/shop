<?php
    $hasVatPermission = true;

    if (check_app_feature_access_right($app_id, "MKT004400_display_decl_vat")) :
        $hasVatPermission = true;
    endif;

    $decl_vat = "";
    if ($hasVatPermission) :
        $decl_vat = '
            <td>' . $lang['declared'] . '</td>
            <td>' . $lang['vat'] . '<br>(' . $pobj->getVatPercent() . '%)</td>
        ';
    endif;

    $header = '<tr class=\'header\'>
                    <td>&nbsp;</td>
                    <td>' . $lang['selling_price'] . '</td>
                    <td>' . $lang['delivery'] . '</td>
                    ' . $decl_vat . '
                    <td>' . $lang['platform_commission'] . '<br>(' . $pobj->getPlatformCommission() . '%)</td>
                    <td>' . $lang['duty'] . '<br>(' . $pobj->getDutyPcent() . '%)</td>
                    <td>' . $lang['pmgw'] . '<br>(' . $pobj->getPaymentChargePercent() . '%)</td>
                    <td>' . $lang['forex'] . '<br>(' . $pobj->getForexFeePercent() . '%)</td>
                    <td>' . $lang['listing_fee'] . '</td>
                    <td>' . $lang['logistic_cost'] . '</td>
                    <td>' . $lang['ca_cost'] . '</td>
                    <td>' . $lang['cost'] . '</td>
                    <td>' . $lang['total_cost'] . '</td>
                    <td>' . $lang['total'] . '</td>
                    <td>' . $lang['profit'] . '</td>
                    <td>' . $lang['gpm'] . '</td>
                    <td>&nbsp;</td>
                </tr>';

    $auto_calc_price = ($pobj->getAutoTotalCharge() - $pobj->getDefaultDeliveryCharge() > $pobj->getFreeDeliveryLimit()) ? $pobj->getAutoTotalCharge() : $pobj->getAutoTotalCharge() - $pobj->getDefaultDeliveryCharge();
    $bgcolor = ($pobj->getPrice() + $pobj->getDeliveryCharge()) > $pobj->getCost() ? "#ddffdd" : "#ffdddd";
    // $platform = $pobj->getPlatformId();

    $decl_vat_row = "";
    if ($hasVatPermission) :
        $decl_vat_row = '<td id="declare[' . $platform . ']">' . number_format($pobj->getDeclaredValue(), 2, ".", "") . '</td>
                         <td id="vat[' . $platform . ']">' . number_format($pobj->getVat(), 2, ".", "") . '</td>';
    endif;

    $table_row = '<tr id="row[' . $platform . ']" style="background-color:' . $bgcolor . '">
                    <td></td>
                    <td>
                        <input type="text" name="selling_price[' . $platform . ']" value="' . ($pobj->getPrice()) . '" id="sp[' . $platform . ']"
                        onKeyup="value=value.replace(/[^\d.]/g,\'\');rePrice(\''.$platform.'\',\'' . $platform . '\',\'' . $pobj->getSku() . '\')" style="width:80px;">
                    </td>
                    <td id="delivery_charge[' . $platform . ']">' . number_format($pobj->getDeliveryCharge(), 2, ".", "") . '</td>
                    ' . $decl_vat_row . '
                    <td id="comm[' . $platform . ']">' . number_format($pobj->getPlatformCommission(), 2, ".", "") . '</td>
                    <td id="duty[' . $platform . ']">' . number_format($pobj->getDuty(), 2, ".", "") . '</td>
                    <td id="pc[' . $platform . ']">' . number_format($pobj->getPaymentCharge(), 2, ".", "") . '</td>
                    <td id="forex_fee[' . $platform . ']">' . number_format($pobj->getForexFee(), 2, ".", "") . '</td>
                    <td id="listing_fee[' . $platform . ']">' . number_format($pobj->getListingFee(), 2, ".", "") . '</td>
                    <td id="logistic_cost[' . $platform . ']">' . number_format($pobj->getLogisticCost(), 2, ".", "") . '</td>
                    <td id="complementary_acc_cost[' . $platform . ']">' . number_format($pobj->getComplementaryAccCost(), 2, ".", "") . '</td>
                    <td id="supplier_cost[' . $platform . ']">' . number_format($pobj->getSupplierCost(), 2, ".", "") . '</td>
                    <td id="total_cost[' . $platform . ']">' . number_format($pobj->getCost(), 2, ".", "") . '</td>
                    <td id="total[' . $platform . ']">' . number_format(($pobj->getPrice() + $pobj->getDeliveryCharge()), 2, ".", "") . '</td>
                    <td id="profit[' . $platform . ']">' . number_format($pobj->getProfit(), 2, ".", "") . '</td>
                    <td id="margin[' . $platform . ']">' . number_format($pobj->getMargin(), 2, ".", "") . '%</td>
                    <input type="hidden" id="hidden_profit[' . $platform . ']" name="hidden_profit[' . $platform . ']" value="' . number_format($pobj->getProfit(), 2, ".", "") . '">
                    <input type="hidden" id="hidden_margin[' . $platform . ']" name="hidden_margin[' . $platform . ']" margin="'. number_format($pobj->getMargin(), 2, ".", "") .'" value="' . number_format($pobj->getMargin(), 2, ".", "") . '">
                    <td>
                        <input type="hidden" id="declared_rate[' . $platform . ']" value="' . $pobj->getDeclaredPcent() . '">
                        <input type="hidden" id="payment_charge_rate[' . $platform . ']" value="' . $pobj->getPaymentChargePercent() . '">
                        <input type="hidden" id="vat_percent[' . $platform . ']" value="' . $pobj->getVatPercent() . '">
                        <input type="hidden" id="duty_percent[' . $platform . ']" value="' . $pobj->getDutyPcent() . '">
                        <input type="hidden" id="forex_fee_percent[' . $platform . ']" value="' . $pobj->getForexFeePercent() . '">
                        <input type="hidden" id="free_delivery_limit[' . $platform . ']" value="' . $pobj->getFreeDeliveryLimit() . '">
                        <input type="hidden" id="default_delivery_charge[' . $platform . ']" value="' . $pobj->getDefaultDeliveryCharge() . '">
                        <input type="hidden" id="scost[' . $platform . ']" value="' . $pobj->getSupplierCost() . '">
                        <input type="hidden" id="commrate[' . $platform . ']" value="' . $pobj->getPlatformCommission() . '">
                        <input type="hidden" id="country_id[' . $platform . ']" value="' . $pobj->getPlatformCountryId() . '">
                        <input type="hidden" id="prod_weight[' . $platform . ']" value="' . $pobj->getProdWeight() . '">
                        <input type="hidden" id="default_freight_cost[' . $platform . ']" value="' . ($pobj->getWhfcCost() - $pobj->getAmazonEfnCost() * 1) . '">
                        <input type="hidden" id="sub_cat_margin[' . $platform . ']" value="' . $pobj->getSubCatMargin() . '">
                        <input type="hidden" id="auto_calc_price[' . $platform . ']" value="' . number_format($auto_calc_price, 2, ".", "") . '">
                        <input type="hidden" id="origin_price[' . $platform . ']" default_price="'. number_format($pobj->getDefaultPlatformConvertedPrice(), 2, ".", "") .'" value="' . number_format($pobj->getPrice(), 2, ".", "") . '">
                    </td>
                 </tr>
                ';
?>
<table border="0" cellpadding="0" cellspacing="0" width="100%" id="pst" class="tb_main" style="width:1042px">
    <?= $header . $table_row ?>
</table>