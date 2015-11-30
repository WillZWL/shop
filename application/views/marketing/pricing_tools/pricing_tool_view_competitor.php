<?php if ($pdata[$platform_id]["competitor"]) : ?>
    <table width="100%" border="0" cellpadding="0" cellspacing="0" class="tb_main">
        <col width="15%">
        <col width="8%">
        <col width="8%">
        <col width="8%">
        <col width="8%">
        <col width="8%">
        <col width="10%">
        <col width="12%">
        <col width="15%">
        <tr>
            <td class="row1"><b>Competitor</b></td>
            <td class="row1"><b>Price</b></td>
            <td class="row1"><b>Shipping Cost</b></td>
            <td class="row1"><b>Price Difference</b></td>
            <td class="row1"><b>Reprice Value</b></td>
            <td class="row1"><b>Reprice Min Margin</b></td>
            <td class="row1"><b>Stock Status</b></td>
            <td class="row1"><b>Cheapest Seller</b></td>
            <td class="row1"><b>Spider's last upload time</b></td>
            <td class="row1"><b>Match</b></td>
        </tr>
    <?php if ($pdata[$platform_id]["competitor"]["hasactivematch"] == 0) : ?>
        <tr>
            <td colspan=10 style="background-color:red;color:white;text-align:center"><b>NO ACTIVE MATCH COMPETITOR AVAILABLE</b></td>
        </tr>
    <?php endif; ?>
    <?php
        $islowestmatch = TRUE;
        # this loop arrange competitors' price difference in ascending order (cheapest first)
        foreach ($pdata[$platform_id]["competitor"]["price_diff"] as $key => $price_diff) :
            foreach ($pdata[$platform_id]["competitor"]["comp_mapping_list"] as $obj) :

                if ($key == $obj->getCompetitorId()) :
                    $compmapadmin_url = base_url() . "marketing/competitor_map/index/{$obj->getCountryId()}?master_sku=$master_sku&country_id={$obj->getCountryId()}";

                    if ($islowestmatch == TRUE && $obj->getMatch() == 1) :
                        ?>
                        <tr>
                            <td bgcolor="greenyellow"><b><a target="_blank" href="<?= $obj->getProductUrl() ?>"><?= $obj->getCompetitorName() ?></a></b></td>
                            <td bgcolor="greenyellow"><b><?= $obj->getNowPrice() ?></b></td>
                            <td bgcolor="greenyellow"><b><?= $obj->getCompShipCharge() ?></b></td>
                            <td bgcolor="greenyellow"><b><?= $price_diff ?></b></td>
                            <td bgcolor="greenyellow"><b><?= $obj->getRepriceValue() ?></b></td>
                            <td bgcolor="greenyellow"><b><?= $obj->getRepriceMinMargin() ?></b></td>
                            <td bgcolor="greenyellow"><b><?= $ar_comp_stock_status[$obj->getCompStockStatus()] ?></td>
                            <td bgcolor="greenyellow"><b><?= $obj->getNote1() ?></b></td>
                            <td bgcolor="greenyellow"><b><?= $obj->getSourcefileTimestamp() ?></b></td>
                            <td bgcolor="greenyellow"><b><a target="_blank" href="<?= $compmapadmin_url ?>"><?= $ar_match[$obj->getMatch()] ?></b></a></td>
                        </tr>
                        <?php
                        $islowestmatch = FALSE;
                    else :
                        ?>

                        <tr>
                            <td class="row1"><a target="_blank" href="<?= $obj->getProductUrl() ?>"><?= $obj->getCompetitorName() ?></a></td>
                            <td class="row1"><?= $obj->getNowPrice() ?></td>
                            <td class="row1"><?= $obj->getCompShipCharge() ?></td>
                            <td class="row1"><?= $price_diff ?></td>
                            <td class="row1"><?= $obj->getRepriceValue() ?></td>
                            <td class="row1"><?= $obj->getRepriceMinMargin() ?></td>
                            <td class="row1"><?= $ar_comp_stock_status[$obj->getCompStockStatus()] ?></td>
                            <td class="row1"><?= $obj->getNote1() ?></td>
                            <td class="row1"><?= $obj->getSourcefileTimestamp() ?></td>
                            <td class="row1"><a target="_blank" href="<?= $compmapadmin_url ?>"><?= $ar_match[$obj->getMatch()] ?></a></td>
                        </tr>
                    <?php
                    endif;
                endif;
            endforeach;
        endforeach;
        ?>
    </table>
<?php endif; ?>