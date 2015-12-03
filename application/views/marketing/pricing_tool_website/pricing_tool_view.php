<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <link rel="stylesheet" href="<?= base_url() ?>css/colorbox.css"/>
    <script type="text/javascript" src="<?= base_url() ?>js/jquery-colorbox.min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
    <script language="javascript">

        <!--
        function lockqty(value) {
            if (value == 'O') {
                document.list.webqty.readOnly = true;
            }
            else {
                document.list.webqty.readOnly = false;
            }
        }

        function check_sub_cat_margin(platform) {
            var auto_price = document.getElementById('auto_price_cb[' + platform + ']');
            var selected = auto_price.options[auto_price.selectedIndex].value;
            if (selected) {
                if (selected == "Y") {
                    if (!document.getElementById('sub_cat_margin[' + platform + ']').value) {
                        alert('Please set the Sub Cat Margin before auto pricing');
                        selected.selectedIndex = 'N';
                    }
                    else {
                        document.getElementById('sp[' + platform + ']').readOnly = true;
                        document.getElementById('sp[' + platform + ']').value = document.getElementById('auto_calc_price[' + platform + ']').value;
                        rePrice(platform, '<?=$prod_obj->getSku();?>');
                    }
                }
                else {
                    document.getElementById('sp[' + platform + ']').readOnly = false;
                    document.getElementById('sp[' + platform + ']').value = document.getElementById('origin_price[' + platform + ']').value;
                    rePrice(platform, '<?=$prod_obj->getSku();?>');
                }
            }
        }

        function showHide_with_eleid(target_ele) {
            console.log(target_ele);
            var target = document.getElementById(target_ele);
            console.log(target);
            target.style.display = 'block';
        }

        function showHide(platform) {
            if (platform) {
                var target = 'prow_' + platform;
                var sign = 'sign_' + platform;
                var sp = 'sp_' + platform;
                var tobj = document.getElementById(target);
                var sobj = document.getElementById(sign);
                var spobj = document.getElementById(sp);
                if (tobj && sobj && spobj) {
                    if (tobj.style.display == 'block') {
                        tobj.style.display = 'none';
                        sobj.innerHTML = '+';
                        spobj.style.display = 'block';
                    }
                    else if (tobj.style.display == 'none') {
                        tobj.style.display = 'block';
                        sobj.innerHTML = '-';
                        spobj.style.display = 'none';
                    }
                    else {
                        return;
                    }
                }
            }
            if (document.getElementById('auto_price_cb[' + platform + ']').checked == true) {
                if (document.getElementById('sub_cat_margin[' + platform + ']').value) {
                    document.getElementById('sp[' + platform + ']').readOnly = true;
                }
            }
        }
        -->
    </script>
    <script type="text/javascript" src="<?= base_url() . $this->tool_path ?>/get_js/"></script>
</head>
<body marginheight="0" marginwidth="0" topmargin="0" leftmargin="0" class="frame_left">
<script type="text/javascript">
    $(document).ready
    (
        function () {
            $(".iframe").colorbox({iframe: true, width: "40%", height: "80%"});
        }
    );
</script>

<?php
$ar_ws_status = ["I" => $lang["instock"], "O" => $lang["outstock"], "P" => $lang["pre-order"], "A" => $lang["arriving"]];
$ar_src_status = ["A" => $lang["available"], "C" => $lang["stock_constraint"], "O" => $lang["temp_out_of_stock"], "L" => $lang["last_lot"], "D" => $lang["discontinued"]];
$ar_l_status = ["L" => $lang["listed"], "N" => $lang["not_listed"]];
$ar_l_status_color = ["L" => "#00FF00", "N" => "#FF0000"];
$ar_comp_stock_status = [0 => $lang["instock"], 1 => $lang["outstock"], 2 => $lang["pre-order"], 3 => $lang["arriving"]];
$ar_match = [0 => $lang["ignore"], 1 => $lang["active"]];
$auto_price_action = ["N" => "No action", "Y" => "Auto Price", "C" => "Competitor Reprice"];
?>
<div id="main" style="width:auto">
    <?= $notice["img"] ?>
    <?php
    if (!$valid_supplier) :
        ?>
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td height="60" align="left" style="padding-left:8px;"><b>No Valid Supplier Cost</b></td>
            </tr>
        </table>

    <?php
    endif;
    if ($value != "") :
        if ($canedit) :
            ?>
            <form name="list" action="<?= base_url() . $this->tool_path ?>/view/<?= $prod_obj->getSku() . ($this->input->get('target') == "" ? "" : "?target=" . $this->input->get('target')) ?>" method="POST" onSubmit="return CheckForm(this)">
            <input type="hidden" name="sku" value="<?= $value ?>">
            <input type="hidden" name="posted" value="1">
            <input type="hidden" name="formtype" value="<?= $action ?>">
            <input type="hidden" name="target" value="<?= $target ?>">
        <?php
        endif;
        ?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
    <td height="60" align="left" style="padding-left:8px;">
    <div style="float:left"><img src='<?= get_image_file($prod_obj->getImage(), 's', $prod_obj->getSku()) ?>'> &nbsp;</div>
    <b style="font-size: 12px; color: rgb(0, 0, 0);"><?= $lang["header"] ?></b><br><?= $lang["header_message"] . " - " ?><b><a href="<?= $website_link . "mainproduct/view/" . $prod_obj->getSku() ?>" target="_blank"><font style="text-decoration:none; color:#000000; font-size:14px;"><?= $prod_obj->getSku() . " - " . $prod_obj->getName() ?><?= $prod_obj->getClearance() ? " <span style='color:#0072E3; font-size:14px;'>(Clearance)</span>" : "" ?></font></a></b><br><?= $lang["master_sku"] . " " . $master_sku ?></td>
</tr>
<?php
        if ($objcount) :
            foreach ($pdata as $platform_id => $value) :
                $trow = $value["pdata"]["content"];
                $platform = $platform_id;
                $price_obj = $price_list[$platform_id];
                $pobj = $value["pdata"]["dst"];
                $delivery_obj = $delivery_info[$platform_id];

                $feed_include_str = $feed_include[$platform_id];
                $feed_exclude_str = $feed_exclude[$platform_id];
                ?>
                <tr class="header">
                    <td height="20" align="left" style="padding-left:8px;"><b
                            style="font-size: 12px; color: rgb(255, 255, 255);"><a
                                href="javascript:showHide('<?= $platform ?>');"><span style="padding-right:15px;"
                                                                                      id='sign_<?= $platform ?>'>+</span></a><?= $platform_id . " - " . $value["obj"]->getPlatformCountry() . " | " . $value["obj"]->getPlatformCurrencyId() . " | " ?>
                            <?php
                            if ($pobj->getCurrentPlatformPrice() * 1) :
                                echo $pobj->getPrice();
                            else :
                                echo $lang["price_not_set"] . " <span class='converted'>({$this->default_platform_id} ";
                                if ($pobj->getDefaultPlatformConvertedPrice() * 1) :
                                    echo ": {$pobj->getDefaultPlatformConvertedPrice()}";
                                else :
                                    echo $lang["price_not_set"];
                                endif;
                                echo ")</span>";
                            endif;
                            ?>
                            |
                            <?= "<span style='color:" . $ar_l_status_color[$pobj->getListingStatus()] . "'>" . $ar_l_status[$pobj->getListingStatus()] . "</span> | " . ($pobj->getMargin() > 0 ? '<span style="color:#88ff88;">' . $pobj->getMargin() . '%</span>' : '<span style="color:#ff8888;">' . $pobj->getMargin() . '%</span>') ?>
                        </b>
                    </td>
                </tr>
                <tr id="sp_<?= $platform ?>" style="display:block;">
                    <td height="5"></td>
                </tr>

                <tr id="prow_<?= $platform ?>" style="display:none;">
                    <td align="left">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" id="pst" class="tb_main"
                               style="width:1042px">
                            <?php
                            eval($value["pdata"]["header"]);
                            echo $header;
                            echo $trow;
                            ?>
                        </table>
                        <?php
                        if ($num_of_supplier == 0) :
                            ?>
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr height="20" bgcolor="ffff00">
                                    <td align="center"><font
                                            style="font-weight:bold; color:#ee3333;"><?= $lang["no_default_supplier"] ?></font>
                                    </td>
                                </tr>
                            </table>
                        <?php
                        endif;

                        if ($price_obj->getListingStatus() == 'N') :
                            ?>
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr height="20" bgcolor="ee3333">
                                    <td align="center"><font
                                            style="font-weight:bold; color:#dddddd;"><?= $lang["currrent_not_listed"] ?></font>
                                    </td>
                                </tr>
                            </table>
                        <?php
                        endif;

                        ?>
                        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="tb_main">
                            <tr>
                                <td width="20%" class="field"><?= $lang["delivery_info"] ?></td>
                                <td width="30%" class="value">
                                    <?php
                                    if ($delivery_obj) :
                                        $delivery_html = "Scenario {$delivery_obj["scenarioname"]} (id:{$delivery_obj["scenarioid"]})
                                    <br>Delivery time: {$delivery_obj["del_min_day"]} - {$delivery_obj["del_max_day"]} ";

                                        if ($delivery_obj["margin"])
                                            $delivery_html .= "<br>Margin >= {$delivery_obj["margin"]}";

                                        echo $delivery_html;
                                    endif;
                                    ?>
                                </td>
                                <td width="20%" class="field">&nbsp;</td>
                                <td width="30%" class="value">&nbsp;</td>
                            </tr>
                            <tr>

                                <td width="20%" class="field"><?= $lang["advertised"] ?></td>
                                <td width="30%" class="value"><input type="checkbox"
                                                                     name="is_advertised[<?= $platform ?>]" <?= ($price_obj->getIsAdvertised() == 'Y' ? "CHECKED" : "") ?> <?php if (!$pdata[$platform_id]["enabled_pla_checkbox"]) echo "disabled";?>>
                                    <?php if ($pdata[$platform_id]["gsc_comment"]) : ?>
                                        <div
                                            style="color:<?= $pdata[$platform_id]["gsc_comment"] == 'Success' ? '#00FF00' : '#FF0000' ?>;width:80%x; height:50px;overflow:hidden"
                                            title='<?= $pdata[$platform_id]["gsc_comment"] ?>'><?= $pdata[$platform_id]["gsc_comment"] ?></div>
                                    <?php endif; ?>
                                </td>
                                <td width="20%" class="field"><?= $lang["listing_status"] ?></td>
                                <td width="30%" class="value"><select name="listing_status[<?= $platform ?>]"
                                                                      style="width:130px;">
                                        <option value="N"><?= $lang["not_listed"] ?></option>
                                        <option
                                            value="L" <?= ($price_obj->getListingStatus() == "L" ? "SELECTED" : "") ?>><?= $lang["listed"] ?></option>
                                    </select><input type="hidden" name="selling_platform[<?= $platform ?>]"
                                                    value="<?= $platform ?>"><input type="hidden"
                                                                                    name="formtype[<?= $platform ?>]"
                                                                                    value="<?= $formtype[$platform] ?>">
                                </td>
                            </tr>
                            <tr>
                                <td width="20%" class="field"><?= $lang["auto_price"] ?></td>
                                <td width="30%" class="value">
                                    <select id="auto_price_cb[<?= $platform ?>]" id="auto_price_cb[<?= $platform ?>]"
                                            name="auto_price[<?= $platform ?>]"
                                            onchange="check_sub_cat_margin('<?= $platform ?>')">
                                        <?php
                                        foreach ($auto_price_action as $key => $action) :
                                            $selected = "";
                                            if (($auto_price_status = $price_obj->getAutoPrice()) == "") :
                                                $auto_price_status = "N";
                                            endif;

                                            if ($auto_price_status == $key) :
                                                $selected = " SELECTED";
                                            endif;
                                            ?>
                                            <option value=<?= $key ?> <?= $selected ?>><?= $action ?></option>
                                        <?php
                                        endforeach;
                                        ?>
                                    </select>
                                </td>
                                <td width="20%" class="field"><?= $lang["sub_cat_margin"] ?></td>
                                <td width="30%"
                                    class="value"><?= $sub_cat_margin[$platform] ? $sub_cat_margin[$platform] : 'Margin not yet set' ?></td>
                            </tr>
                            <tr>
                                <td width="20%" class="field"><?= $lang["rrp"] ?></td>
                                <td width="30%" class="value">
                                    <div><input type="radio" id="fixed_rrp[<?= $platform ?>]"
                                                name="fixed_rrp[<?= $platform ?>]"
                                                value="Y" <?= ($price_obj->getFixedRrp() != 'Y' ? "" : "CHECKED") ?>
                                                onClick="javascript:document.getElementById('rrp_factor[<?= $platform ?>]').readOnly=true"><?= $lang["system_default_rrp"] ?>
                                    </div>
                                    <div>
                                        <input type="radio" id="fixed_rrp[<?= $platform ?>]"
                                               name="fixed_rrp[<?= $platform ?>]"
                                               value="N" <?= ($price_obj->getFixedRrp() != 'Y' ? "CHECKED" : "") ?>
                                               onClick="javascript:document.getElementById('rrp_factor[<?= $platform ?>]').readOnly=false"><?= $lang["random_rrp"] ?>
                                        &nbsp;&nbsp;&nbsp;
                                        <?= (($price_obj->getFixedRrp() == 'N' && $price_obj->getRrpFactor() < 10) ? '(' . $lang["rrp"] . ' = ' . $lang["selling_price"] . ' * ' . $price_obj->getRrpFactor() . ')' : "") ?>
                                    </div>
                                    <div style="padding-left:20px">
                                        <input style="width:70px" type="text" id="rrp_factor[<?= $platform ?>]"
                                               name="rrp_factor[<?= $platform ?>]"
                                               value="<?= ($price_obj->getFixedRrp() == 'N' ? ($price_obj->getRrpFactor() >= 10 ? $price_obj->getRrpFactor() : "") : "") ?>" <?= ($price_obj->getFixedRrp() != 'Y' ? "" : "readOnly") ?>
                                               isNumber>
                                        &nbsp;
                                        <?= $lang["manual_rrp_note"] ?>
                                    </div>
                                </td>
                                <td width="20%" class="field">Feeds Management</td>
                                <td width="30%" class="value">
                                    <?= $feed_include_str ? "<font style='color:purple;'>INCLUDE: <b>$feed_include_str</b></font><br>" : "" ?>
                                    <?= $feed_exclude_str ? "<font style='color:purple;'>EXCLUDE: <b>$feed_exclude_str</b></font><br>" : "" ?>
                                    <a class="iframe cboxElement"
                                       href="/marketing/pricing_tool_website/manage_feed/<?= $prod_obj->getSku() ?>/<?= $platform ?>">Edit</a>
                                    (<a class="iframe cboxElement"
                                        href="/marketing/pricing_tool_website/manage_feed_platform">Manage feed
                                        mappings</a>)
                                </td>
                            </tr>
                            <tr>
                                <td class="field" height="25"><?= $lang["google_adwords"] ?></td>
                                <td class="value"><?= $pdata[$platform_id]['adwords'] ?></td>
                                <td class="field"><?= $lang["google_adwords_status"] ?></td>
                                <td class="value"><?= $pdata[$platform_id]['adGroup_status'] ?></td>
                            </tr>
                            <?= $pdata[$platform_id]['adGroup_error_row'] ?>
                        </table>
                        <?php
                        if ($pdata[$platform_id]["competitor"]) :
                            ?>
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
                                <?php
                                if ($pdata[$platform_id]["competitor"]["hasactivematch"] == 0) :
                                    $nomatch_html = <<<html
                            <tr>
                                <td colspan=10 style="background-color:red;color:white;text-align:center">
                                    <b>NO ACTIVE MATCH COMPETITOR AVAILABLE</b>
                                </td>
                            </tr>
html;
                                    echo $nomatch_html;

                                endif;

                                $islowestmatch = TRUE;
                                # this loop arrange competitors' price difference in ascending order (cheapest first)
                                foreach ($pdata[$platform_id]["competitor"]["price_diff"] as $key => $price_diff) :
                                    foreach ($pdata[$platform_id]["competitor"]["comp_mapping_list"] as $obj) :

                                        if ($key == $obj->getCompetitorId()) :
                                            $compmapadmin_url = base_url() . "marketing/competitor_map/index/{$obj->getCountryId()}?master_sku=$master_sku&country_id={$obj->getCountryId()}";

                                            if ($islowestmatch == TRUE && $obj->getMatch() == 1) :
                                                ?>
                                                <tr>
                                                    <td bgcolor="greenyellow"><b><a target="_blank"
                                                                                    href="<?= $obj->getProductUrl() ?>"><?= $obj->getCompetitorName() ?></a></b>
                                                    </td>
                                                    <td bgcolor="greenyellow"><b><?= $obj->getNowPrice() ?></b></td>
                                                    <td bgcolor="greenyellow"><b><?= $obj->getCompShipCharge() ?></b>
                                                    </td>
                                                    <td bgcolor="greenyellow"><b><?= $price_diff ?></b></td>
                                                    <td bgcolor="greenyellow"><b><?= $obj->getRepriceValue() ?></b>
                                                    </td>
                                                    <td bgcolor="greenyellow">
                                                        <b><?= $obj->getRepriceMinMargin() ?></b></td>
                                                    <td bgcolor="greenyellow">
                                                        <b><?= $ar_comp_stock_status[$obj->getCompStockStatus()] ?>
                                                    </td>
                                                    <td bgcolor="greenyellow"><b><?= $obj->getNote1() ?></b></td>
                                                    <td bgcolor="greenyellow">
                                                        <b><?= $obj->getSourcefileTimestamp() ?></b></td>
                                                    <td bgcolor="greenyellow"><b><a target="_blank"
                                                                                    href="<?= $compmapadmin_url ?>"><?= $ar_match[$obj->getMatch()] ?>
                                                        </b></a></td>
                                                </tr>
                                                <?php
                                                $islowestmatch = FALSE;
                                            else :
                                                ?>

                                                <tr>
                                                    <td class="row1"><a target="_blank"
                                                                        href="<?= $obj->getProductUrl() ?>"><?= $obj->getCompetitorName() ?></a>
                                                    </td>
                                                    <td class="row1"><?= $obj->getNowPrice() ?></td>
                                                    <td class="row1"><?= $obj->getCompShipCharge() ?></td>
                                                    <td class="row1"><?= $price_diff ?></td>
                                                    <td class="row1"><?= $obj->getRepriceValue() ?></td>
                                                    <td class="row1"><?= $obj->getRepriceMinMargin() ?></td>
                                                    <td class="row1"><?= $ar_comp_stock_status[$obj->getCompStockStatus()] ?></td>
                                                    <td class="row1"><?= $obj->getNote1() ?></td>
                                                    <td class="row1"><?= $obj->getSourcefileTimestamp() ?></td>
                                                    <td class="row1"><a target="_blank"
                                                                        href="<?= $compmapadmin_url ?>"><?= $ar_match[$obj->getMatch()] ?></a>
                                                    </td>
                                                </tr>
                                            <?php
                                            endif;
                                        endif;
                                    endforeach;
                                endforeach;
                                ?>
                            </table>
                        <?php

                        endif;
                        ?>

                    </td>
                </tr>
            <?php  endforeach;

            ?>

            <tr>
                <td height="30" class="field">&nbsp;</td>
            </tr>

            <tr class="header">
                <td height="20" align="left" style="padding-left:8px;"><b
                        style="font-size: 12px; color: rgb(255, 255, 255);"><?= $lang["selling_info"] ?></b></td>
            </tr>
            <tr>
                <td width="100%">
                    <table cellpadding="0" cellspacing="0" border="0" width="100%" class="tb_main">
                        <tr>
                            <td width="20%" class="field"><?= $lang["prodname"] ?></td>
                            <td width="30%" class="value"><?= $prod_obj->getName() ?></td>
                            <td width="20%" class="field"><?= $lang["sku"] ?></td>
                            <td width="30%" class="value"><?= $prod_obj->getSku() ?></td>
                        </tr>
                        <tr>
                            <td width="20%" class="field"><?= $lang["status"] ?></td>
                            <td width="30%" class="value"><select name="status" style="width:130px;"
                                                                  onChange="lockqty(this.value)"><?php
                                    foreach ($ar_ws_status as $key => $value) :
                                        ?>
                                        <option
                                        value="<?= $key ?>" <?= ($prod_obj->getWebsiteStatus() == $key ? "SELECTED" : "") ?>><?= $value ?></option><?php
                                    endforeach;
                                    ?></select></td>
                            <td width="20%" class="field"><?= $lang["webqty"] ?></td>
                            <td width="30%" class="value"><input type="text" name="webqty"
                                                                 value="<?= $prod_obj->getWebsiteQuantity() ?>"
                                                                 notEmpty isNatural></td>
                        </tr>
                        <tr>
                            <td width="20%" class="field"><?= $lang["current_supplier"] ?></td>
                            <td width="30%" class="value"><?= $supplier["name"] ?></td>
                            <td width="20%" class="field"><?= $lang["freight_cat"] ?></td>
                            <td width="30%" class="value"><?= $freight_cat ?></td>
                        </tr>
                        <tr>
                            <td width="20%" class="field"><?= $lang["supplier_status"] ?></td>
                            <td width="30%" class="value"><?= $ar_src_status[$supplier['supplier_status']] ?></td>
                            <td width="20%" class="field">Surplus Quantity</td>
                            <td width="30%"
                                class="value"><?= $surplus_qty ?><?= $slow_move == 'Y' ? "  (is slow moving last 7 days)" : "" ?></td>
                        </tr>

                        <?php
                        if (count($inv)) :
                            $ttl_inv = $ttl_git = 0;
                            $inventory .= "<table border='0' cellpadding='0' cellspacing='0' width='100%'>";
                            $inventory .= "<tr><td>" . $lang["warehouse"] . "</td><td>" . $lang["inventory"] . "</td><td>" . $lang["git"] . "</td></tr>";
                            foreach ($inv as $iobj) :

                                $inventory .= "<tr><td>" . $iobj->getWarehouseId() . "</td><td>" . $iobj->getInventory() . "</td><td>" . $iobj->getGit() . "</td></tr>";
                                $ttl_inv += $iobj->getInventory();
                                $ttl_git += $iobj->getGit();
                            endforeach;
                            $inventory .= "<tr><td>" . $lang["total"] . "</td><td>" . $ttl_inv . "</td><td>" . $ttl_git . "</td></tr>";
                            $inventory .= "</table>";
                        else :
                            $inventory = 0;
                        endif;
                        ?>
                        <tr>
                            <td width="20%" class="field"><?= $lang["inventory"] ?></td>
                            <td width="30%" class="value"><?= $inventory ?></td>
                            <td width="20%" class="field"><?= $lang["qty_in_orders"] ?></td>
                            <td width="30%" class="value"><?= $qty_in_orders ?></td>
                        </tr>
                        <tr>
                            <td width="20%" class="field"><?= $lang["on_clearance"] ?></td>
                            <td width="30%" class="value"><select name="clearance" class="input">
                                    <option
                                        value="0" <?= !$prod_obj->getClearance() ? "SELECTED" : "" ?>><?= $lang["no"] ?></option>
                                    <option
                                        value="1" <?= $prod_obj->getClearance() ? "SELECTED" : "" ?>><?= $lang["yes"] ?></option>
                                </select></td>
                            <td width="20%" class="field"><?= $lang["hitcount"] ?></td>
                            <td width="30%" class="value"><?= $hitcount ?></td>
                        </tr>
                        <tr>
                            <td width="20%" class="field" valign="top"
                                style="padding-top:2px; padding-bottom:2px;"><?= $lang["marketing_notes"] ?></td>
                            <td width="30%" class="value" valign="top" style="padding-top:2px; padding-bottom:2px;">
                                <div><?= $lang["current_notes"] ?></div><?php
                                if (!empty($mkt_note_obj)) :
                                    foreach ($mkt_note_obj as $nobj) :
                                        ?>
                                        <div><?= $nobj->getNote() ?><br><span
                                            style="font-size:9px; color:#888888; font-style:italic;"><?= $lang["create_by"] . $nobj->getUsername() . $lang["on"] . $nobj->getCreateOn() ?></span>
                                        </div><?php
                                    endforeach;
                                else :
                                    echo "<div>" . $lang["no_notes"] . "</div>";
                                endif;
                                ?>
                                <div><?= $lang["create_note"] ?></div>
                                <div><input name="m_note" maxlength="255" type="text"></div>
                            </td>
                            <td width="20%" class="field" valign="top"
                                style="padding-top:2px; padding-bottom:2px;"><?= $lang["sourcing_notes"] ?></td>
                            <td width="30%" class="value" valign="top" style="padding-top:2px; padding-bottom:2px;">
                                <div><?= $lang["current_notes"] ?></div><?php
                                if (!empty($src_note_obj)) :
                                    foreach ($src_note_obj as $nobj) :
                                        ?>
                                        <div><?= $nobj->getNote() ?><br><span
                                            style="font-size:9px; color:#888888; font-style:italic;"><?= $lang["create_by"] . $nobj->getUsername() . $lang["on"] . $nobj->getCreateOn() ?></span>
                                        </div><?php
                                    endforeach;
                                else :
                                    echo "<div>" . $lang["no_notes"] . "</div>";
                                endif;
                                ?>
                                <div><?= $lang["create_note"] ?></div>
                                <div><input name="s_note" maxlength="255" type="text"></div>
                            </td>
                        </tr>
                        <?= "<!--" ?>
                        <?= $price_obj->getListingStatus() ?>
                        <?= "-->" ?>
                        <tr>
                            <td width="20%" class="field" valign="top"
                                style="padding-top:2px; padding-bottom:2px;"><?= $lang["max_order_qty"] ?></td>
                            <td width="30%" class="value" valign="top" style="padding-top:2px; padding-bottom:2px;">
                                <input type="text" name="max_order_qty"
                                       value="<?= ($price_obj->getMaxOrderQty() == "" ? "100" : $price_obj->getMaxOrderQty()) ?>">
                            </td>
                            <td width="20%" class="field"><?= $lang["mapping_code"] ?></td>
                            <td width="30%" class="value"><input type="text" name="ext_mapping_code"
                                                                 value="<?= $price_obj->getExtMappingCode() ?>"
                                                                 style="width:130px;"></td>
                        </tr>
                        <tr>
                            <td width="20%" class="field"><?= $lang["create_on"] ?></td>
                            <td width="30%" class="value"><?= $price_obj->getCreateOn() ?></td>
                            <td width="20%" class="field"><?= $lang["modify_on"] ?></td>
                            <td width="30%" class="value"><?= $price_obj->getModifyOn() ?></td>
                        </tr>
                        <tr>
                            <td width="20%" class="field"><?= $lang["create_at"] ?></td>
                            <td width="30%" class="value"><?= $price_obj->getCreateAt() ?></td>
                            <td width="20%" class="field"><?= $lang["modify_at"] ?></td>
                            <td width="30%" class="value"><?= $price_obj->getModifyAt() ?></td>
                        </tr>
                        <tr>
                            <td width="20%" class="field"><?= $lang["create_by"] ?></td>
                            <td width="30%" class="value"><?= $price_obj->getCreateBy() ?></td>
                            <td width="20%" class="field"><?= $lang["modify_by"] ?></td>
                            <td width="30%" class="value"><?= $price_obj->getModifyBy() ?></td>
                        </tr>

                    </table>
                </td>
            </tr>
            </table>
            <?php
            if ($canedit) :
                ?>
                <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_detail">
                    <tr>
                        <td align="right" style="padding-right:8px;" height="30"><input type="button"
                                                                                        value="Update Pricing Tool"
                                                                                        class="button"
                                                                                        onClick="if(CheckForm(this.form)) this.form.submit();">
                        </td>
                    </tr>
                </table>
                </form>

                <?php /*
<div style="margin-top:20px; margin-top:20px; text-align:left; padding-left:10px;">
Formula for this pricing tool:<br>
TOTAL CHARGE = SELLING PRICE + DELIVERY; <br>
DECL. = (TOTAL CHARGE)* DECL.(%); <br>
VAT = DECL. * VAT(%); <br>
DUTY = DECL. * DUTY(%); <br>
COMM. = (TOTAL CHARGE) * COMM(%); <br>
PMGW = (TOTAL CHARGE) * PAYMENT CHARGE(%); <br>
FOREX = (TOTAL CHARGE) * FOREX(%); <br>
(TOTAL COST) = DUTY + SUPP.COST + PMGW + LOGISTIC + LISTING + VAT + FOREX FEE; <br>
PROFIT = (TOTAL CHARGE) - (TOTAL COST); <br>
GPM = (PROFIT)/(TOTAL CHARGE) * 100%; <br>
</div>
*/ ?>
            <?php
            endif;
        else :
            ?>
        <tr>
            <td style="padding-left:8px;">
                <br>
                <b><a class="warn" href="<?= base_url() ?>marketing/category/view_scpv/?subcat_id=<?= $prod_obj->getSubCatId() ?>&platform=WSGB"><?= $lang["sub_cat_no_set"] ?></a></b>
            </td>
        </tr>
    </table>
<?php
        endif;
    endif;
    ?>
</div>
<?= $notice["js"] ?>
<?php

if ($prompt_notice) :
    ?>
    <script language="javascript">alert('<?=$lang["update_notice"]?>')</script><?php
endif;
?>
<script language="javascript">
    if (document.list) {
        lockqty(document.list.status.value);
    }
</script>
</body>
</html>