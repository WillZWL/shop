<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
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
        }
        -->
    </script>
    <script type="text/javascript" src="<?= base_url() . $this->tool_path ?>/get_js/"></script>
</head>
<body onload="showHide('EBAYUS');showHide('EBAYAU');showHide('EBAYSG');" marginheight="0" marginwidth="0" topmargin="0"
      leftmargin="0" class="frame_left">
<?php
$ar_ws_status = array("I" => $lang["instock"], "O" => $lang["outstock"], "P" => $lang["pre-order"], "A" => $lang["arriving"]);
$ar_src_status = array("A" => $lang["available"], "C" => $lang["stock_constraint"], "O" => $lang["temp_out_of_stock"], "L" => $lang["last_lot"], "D" => $lang["discontinued"]);
$ar_l_status = array("L" => $lang["listed"], "N" => $lang["not_listed"]);
$ar_l_status_color = array("L" => "#00FF00", "N" => "#FF0000");
?>
<div id="main" style="width:auto">
    <?= $notice["img"] ?>
    <?php
    if (!$valid_supplier) {
        ?>
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td height="60" align="left" style="padding-left:8px;"><b>No Valid Supplier Cost</b></td>
            </tr>
        </table>

    <?php
    }

    if ($value != "") {
        if ($canedit) {
            ?>
            <form name="list" action="<?= base_url() . $this->tool_path ?>/view/<?= $prod_obj->get_sku() . ($this->input->get('target') == "" ? "" : "?target=" . $this->input->get('target')) ?>" method="POST" onSubmit="return CheckForm(this)">
            <input type="hidden" name="sku" value="<?= $value ?>">
            <input type="hidden" name="posted" value="1">
            <input type="hidden" name="formtype" value="<?= $action ?>">
            <input type="hidden" name="target" value="<?= $target ?>">
        <?php
        }
        ?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
    <td height="60" align="left" style="padding-left:8px;">
    <div style="float:left"><img src='<?= get_image_file($prod_obj->get_image(), 's', $prod_obj->get_sku()) ?>'> &nbsp;</div>
    <b style="font-size: 12px; color: rgb(0, 0, 0);"><?= $lang["header"] ?></b><br><?= $lang["header_message"] . " - " ?><b><a href="<?= $website_link . "mainproduct/view/" . $prod_obj->get_sku() ?>" target="_blank"><font style="text-decoration:none; color:#000000; font-size:14px;"><?= $prod_obj->get_sku() . " - " . $prod_obj->get_name() ?><?= $prod_obj->get_clearance() ? " <span style='color:#0072E3; font-size:14px;'>(Clearance)</span>" : "" ?></font></a></b><br><?= $lang["master_sku"] . " " . $master_sku ?></td>
</tr>
<?php
        if ($objcount) {
            foreach ($pdata as $platform_id => $value) {
                $trow = $value["pdata"]["content"];
                $platform = $platform_id;
                $price_obj = $price_list[$platform_id];
                $price_ext_obj = $price_ext[$platform_id];
                $pobj = $value["pdata"]["dst"];
                ?>
                <tr class="header">
                    <td height="20" align="left" style="padding-left:8px;"><b
                            style="font-size: 12px; color: rgb(255, 255, 255);"><a
                                href="javascript:showHide('<?= $platform ?>');"><span style="padding-right:15px;"
                                                                                      id='sign_<?= $platform ?>'>+</span></a><?= $platform_id . " - " . $value["obj"]->get_platform_name() . " | " . $value["obj"]->get_platform_currency_id() . " | " ?>
                            <?php
                            if ($pobj->get_current_platform_price() * 1) {
                                echo $pobj->get_price();
                            } else {
                                echo $lang["price_not_set"] . " <span class='converted'>({$this->default_platform_id} ";
                                if ($pobj->get_default_platform_converted_price() * 1) {
                                    echo "{$lang["converted"]}: {$pobj->get_default_platform_converted_price()}";
                                } else {
                                    echo $lang["price_not_set"];
                                }
                                echo ")</span>";
                            }
                            ?>
                            |
                            <?= "<span style='color:" . $ar_l_status_color[$pobj->get_listing_status()] . "'>" . $ar_l_status[$pobj->get_listing_status()] . "</span> | " . ($pobj->get_margin() > 0 ? '<span style="color:#88ff88;">' . $pobj->get_margin() . '%</span>' : '<span style="color:#ff8888;">' . $pobj->get_margin() . '%</span>') ?>
                            <?php
                            if (($price_ext_obj->get_ext_item_id())) {
                                switch ($platform_id) {
                                    case "EBAYUK":
                                        $ebay_url = "<a href='http://www.ebay.co.uk/itm/" . $price_ext_obj->get_ext_item_id() . "' target='_blank'>http://www.ebay.co.uk/itm/" . $price_ext_obj->get_ext_item_id() . "</a>";
                                        break;
                                    case "EBAYUS":
                                        $ebay_url = "<a href='http://www.ebay.com/itm/" . $price_ext_obj->get_ext_item_id() . "' target='_blank'>http://www.ebay.com/itm/" . $price_ext_obj->get_ext_item_id() . "</a>";
                                        break;
                                    case "EBAYAU":
                                        $ebay_url = "<a href='http://www.ebay.com.au/itm/" . $price_ext_obj->get_ext_item_id() . "' target='_blank'>http://www.ebay.com.au/itm/" . $price_ext_obj->get_ext_item_id() . "</a>";
                                        break;
                                    case "EBAYSG":
                                        $ebay_url = "<a href='http://www.ebay.com.sg/itm/" . $price_ext_obj->get_ext_item_id() . "' target='_blank'>http://www.ebay.com.sg/itm/" . $price_ext_obj->get_ext_item_id() . "</a>";
                                        break;
                                    case "EBAYMY":
                                        $ebay_url = "<a href='http://www.ebay.com.my/itm/" . $price_ext_obj->get_ext_item_id() . "' target='_blank'>http://www.ebay.com.my/itm/" . $price_ext_obj->get_ext_item_id() . "</a>";
                                        break;
                                }
                                echo " | " . $ebay_url;
                            }
                            ?>
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
                        if ($num_of_supplier == 0) {
                            ?>
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr height="20" bgcolor="ffff00">
                                    <td align="center"><font
                                            style="font-weight:bold; color:#ee3333;"><?= $lang["no_default_supplier"] ?></font>
                                    </td>
                                </tr>
                            </table>
                        <?php
                        }

                        if ($price_obj->get_listing_status() == 'N') {
                            ?>
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr height="20" bgcolor="ee3333">
                                    <td align="center"><font
                                            style="font-weight:bold; color:#dddddd;"><?= $lang["currrent_not_listed"] ?></font>
                                    </td>
                                </tr>
                            </table>
                        <?php
                        }

                        ?>
                        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="tb_main">
                            <tr>
                                <td width="20%" class="field"><?= $lang["ebay_title"] ?></td>
                                <td colspan="3" class="value"><input name="price_ext[<?= $platform ?>][title]"
                                                                     value="<?= htmlspecialchars($price_ext_obj->get_title()) ?>"
                                                                     class="input" maxlength="80"></td>
                            </tr>
                            <tr>
                                <td width="20%" class="field"><?= $lang["category"] ?></td>
                                <td width="30%" class="value"><input name="price_ext[<?= $platform ?>][ext_ref_1]"
                                                                     value="<?= htmlspecialchars($price_ext_obj->get_ext_ref_1()) ?>">
                                </td>
                                <td width="20%" class="field"><?= $lang["category2"] ?></td>
                                <td width="30%" class="value"><input name="price_ext[<?= $platform ?>][ext_ref_2]"
                                                                     value="<?= htmlspecialchars($price_ext_obj->get_ext_ref_2()) ?>">
                                </td>
                            </tr>
                            <tr>
                                <td class="field"><?= $lang["store_category"] ?></td>
                                <td class="value">
                                    <select name="price_ext[<?= $platform ?>][ext_ref_3]">
                                        <option></option>
                                        <?php
                                        if ($store_cat_list[$platform]) {
                                            foreach ($store_cat_list[$platform] as $store_cat) {
                                                ?>
                                                <option
                                                value="<?= $store_cat->get_ext_id() ?>" <?= ($price_ext_obj->get_ext_ref_3() == $store_cat->get_ext_id() ? "SELECTED" : "") ?>><?= $store_cat->get_ext_name() ?></option><?php
                                            }
                                        }
                                        ?>
                                </td>
                                <td class="field"><?= $lang["store_category2"] ?></td>
                                <td class="value">
                                    <select name="price_ext[<?= $platform ?>][ext_ref_4]">
                                        <option></option>
                                        <?php
                                        if ($store_cat_2_list[$platform]) {
                                            foreach ($store_cat_2_list[$platform] as $store_cat_2) {
                                                ?>
                                                <option
                                                value="<?= $store_cat_2->get_ext_id() ?>" <?= ($price_ext_obj->get_ext_ref_4() == $store_cat_2->get_ext_id() ? "SELECTED" : "") ?>><?= $store_cat_2->get_ext_name() ?></option><?php
                                            }
                                        }
                                        ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="field"><?= $lang["listing_qty"] ?></td>
                                <td class="value"><input name="price_ext[<?= $platform ?>][ext_qty]"
                                                         value="<?= htmlspecialchars($price_ext_obj->get_ext_qty()) ?>">
                                    <select name="price_ext[<?= $platform ?>][handling_time]" style="width:130px;">
                                        <?php $business_day_array = array(0, 1, 2, 3, 4, 5, 10, 15, 20);
                                        foreach ($business_day_array as $b) {
                                            ?>
                                            <option
                                                value="<?= $b ?>" <?if ($price_ext_obj->get_handling_time() == $b) echo "selected"?>><?= $b . " " . $lang["business_day"] ?></option>
                                        <?php
                                        }
                                        ?>
                                </td>
                                <td class="field"><?= $lang["listing_status"] ?></td>
                                <td class="value"><select name="listing_status[<?= $platform ?>]" style="width:130px;">
                                        <option value="N"><?= $lang["not_listed"] ?></option>
                                        <option
                                            value="L" <?= ($price_obj->get_listing_status() == "L" ? "SELECTED" : "") ?>><?= $lang["listed"] ?></option>
                                    </select><input type="hidden" name="selling_platform[<?= $platform ?>]"
                                                    value="<?= $platform ?>"><input type="hidden"
                                                                                    name="formtype[<?= $platform ?>]"
                                                                                    value="<?= $formtype[$platform] ?>">
                                </td>
                            </tr>
                            <?php
                            if ($price_ext_obj->get_ext_item_id()) {
                                ?>
                                <tr>
                                    <td class="field"><?= $lang["ebay_item_id"] ?></td>
                                    <td class="value"><?= $price_ext_obj->get_ext_item_id() ?></td>
                                    <td class="field"><?= $lang["action"] ?></td>
                                    <td class="value">
                                        <select name="action[<?= $platform ?>]" style="width:130px;"
                                                onChange="if(this.value == 'E') { document.getElementById('end_reason[<?= $platform ?>]').style.display = '' } else { document.getElementById('end_reason[<?= $platform ?>]').style.display = 'none' }">
                                            <option></option>
                                            <option value="R"><?= $lang["re-additem"] ?></option>
                                            <option value="RE"><?= $lang["revise"] ?></option>
                                            <option value="E"><?= $lang["enditem"] ?></option>
                                        </select>
                                        <select id="end_reason[<?= $platform ?>]" name="reason[<?= $platform ?>]"
                                                style="width:130px;display:none;">
                                            <option value="NotAvailable">NotAvailable</option>
                                            <option value="Incorrect">Incorrect</option>
                                            <option value="LostOrBroken">LostOrBroken</option>
                                            <option value="OtherListingError">OtherListingError</option>
                                            <option value="SellToHighBidder">SellToHighBidder</option>
                                            <option value="Sold">Sold</option>
                                        </select>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                            <tr>
                                <td width="20%" class="field"><?= $lang["create_on"] ?></td>
                                <td width="30%" class="value"><?= $price_obj->get_create_on() ?></td>
                                <td width="20%" class="field"><?= $lang["modify_on"] ?></td>
                                <td width="30%" class="value"><?= $price_obj->get_modify_on() ?></td>
                            </tr>
                            <tr>
                                <td width="20%" class="field"><?= $lang["create_at"] ?></td>
                                <td width="30%" class="value"><?= $price_obj->get_create_at() ?></td>
                                <td width="20%" class="field"><?= $lang["modify_at"] ?></td>
                                <td width="30%" class="value"><?= $price_obj->get_modify_at() ?></td>
                            </tr>
                            <tr>
                                <td width="20%" class="field"><?= $lang["create_by"] ?></td>
                                <td width="30%" class="value"><?= $price_obj->get_create_by() ?></td>
                                <td width="20%" class="field"><?= $lang["modify_by"] ?></td>
                                <td width="30%" class="value"><?= $price_obj->get_modify_by() ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            <? }

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
                            <td width="30%" class="value"><?= $prod_obj->get_name() ?></td>
                            <td width="20%" class="field"><?= $lang["sku"] ?></td>
                            <td width="30%" class="value"><?= $prod_obj->get_sku() ?></td>
                        </tr>
                        <tr>
                            <td width="20%" class="field"><?= $lang["status"] ?></td>
                            <td width="30%" class="value"><select name="status" style="width:130px;"
                                                                  onChange="lockqty(this.value)"><?php
                                    foreach ($ar_ws_status as $key => $value) {
                                        ?>
                                        <option
                                        value="<?= $key ?>" <?= ($prod_obj->get_website_status() == $key ? "SELECTED" : "") ?>><?= $value ?></option><?php
                                    }
                                    ?></select></td>
                            <td width="20%" class="field"><?= $lang["webqty"] ?></td>
                            <td width="30%" class="value"><input type="text" name="webqty"
                                                                 value="<?= $prod_obj->get_website_quantity() ?>"
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
                        if (count($inv)) {
                            $ttl_inv = $ttl_git = 0;
                            $inventory .= "<table border='0' cellpadding='0' cellspacing='0' width='100%'>";
                            $inventory .= "<tr><td>" . $lang["warehouse"] . "</td><td>" . $lang["inventory"] . "</td><td>" . $lang["git"] . "</td></tr>";
                            foreach ($inv as $iobj) {

                                $inventory .= "<tr><td>" . $iobj->get_warehouse_id() . "</td><td>" . $iobj->get_inventory() . "</td><td>" . $iobj->get_git() . "</td></tr>";
                                $ttl_inv += $iobj->get_inventory();
                                $ttl_git += $iobj->get_git();
                            }
                            $inventory .= "<tr><td>" . $lang["total"] . "</td><td>" . $ttl_inv . "</td><td>" . $ttl_git . "</td></tr>";
                            $inventory .= "</table>";
                        } else {
                            $inventory = 0;
                        }
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
                                        value="0" <?= !$prod_obj->get_clearance() ? "SELECTED" : "" ?>><?= $lang["no"] ?></option>
                                    <option
                                        value="1" <?= $prod_obj->get_clearance() ? "SELECTED" : "" ?>><?= $lang["yes"] ?></option>
                                </select></td>
                            <td width="20%" class="field"><?= $lang["hitcount"] ?></td>
                            <td width="30%" class="value"><?= $hitcount ?></td>
                        </tr>
                        <tr>
                            <td width="20%" class="field" valign="top"
                                style="padding-top:2px; padding-bottom:2px;"><?= $lang["marketing_notes"] ?></td>
                            <td width="30%" class="value" valign="top" style="padding-top:2px; padding-bottom:2px;">
                                <div><?= $lang["current_notes"] ?></div><?php
                                if (!empty($mkt_note_obj)) {
                                    foreach ($mkt_note_obj as $nobj) {
                                        ?>
                                        <div><?= $nobj->get_note() ?><br><span
                                            style="font-size:9px; color:#888888; font-style:italic;"><?= $lang["create_by"] . $nobj->get_username() . $lang["on"] . $nobj->get_create_on() ?></span>
                                        </div><?php
                                    }
                                } else {
                                    echo "<div>" . $lang["no_notes"] . "</div>";
                                }
                                ?>
                                <div><?= $lang["create_note"] ?></div>
                                <div><input name="m_note" maxlength="255" type="text"></div>
                            </td>
                            <td width="20%" class="field" valign="top"
                                style="padding-top:2px; padding-bottom:2px;"><?= $lang["sourcing_notes"] ?></td>
                            <td width="30%" class="value" valign="top" style="padding-top:2px; padding-bottom:2px;">
                                <div><?= $lang["current_notes"] ?></div><?php
                                if (!empty($src_note_obj)) {
                                    foreach ($src_note_obj as $nobj) {
                                        ?>
                                        <div><?= $nobj->get_note() ?><br><span
                                            style="font-size:9px; color:#888888; font-style:italic;"><?= $lang["create_by"] . $nobj->get_username() . $lang["on"] . $nobj->get_create_on() ?></span>
                                        </div><?php
                                    }
                                } else {
                                    echo "<div>" . $lang["no_notes"] . "</div>";
                                }
                                ?>
                                <div><?= $lang["create_note"] ?></div>
                                <div><input name="s_note" maxlength="255" type="text"></div>
                            </td>
                        </tr>
                        <?= "<!--" ?>
                        <?= $price_obj->get_listing_status() ?>
                        <?= "-->" ?>
                        <tr>
                            <td width="20%" class="field" valign="top"
                                style="padding-top:2px; padding-bottom:2px;"><?= $lang["max_order_qty"] ?></td>
                            <td width="80%" colspan='3' class="value" valign="top"
                                style="padding-top:2px; padding-bottom:2px;"><input type="text" name="max_order_qty"
                                                                                    value="<?= ($price_obj->get_max_order_qty() == "" ? "100" : $price_obj->get_max_order_qty()) ?>">
                            </td>
                        </tr>
                        <tr>
                            <td width="20%" class="field"><?= $lang["create_on"] ?></td>
                            <td width="30%" class="value"><?= $prod_obj->get_create_on() ?></td>
                            <td width="20%" class="field"><?= $lang["modify_on"] ?></td>
                            <td width="30%" class="value"><?= $prod_obj->get_modify_on() ?></td>
                        </tr>
                        <tr>
                            <td width="20%" class="field"><?= $lang["create_at"] ?></td>
                            <td width="30%" class="value"><?= $prod_obj->get_create_at() ?></td>
                            <td width="20%" class="field"><?= $lang["modify_at"] ?></td>
                            <td width="30%" class="value"><?= $prod_obj->get_modify_at() ?></td>
                        </tr>
                        <tr>
                            <td width="20%" class="field"><?= $lang["create_by"] ?></td>
                            <td width="30%" class="value"><?= $prod_obj->get_create_by() ?></td>
                            <td width="20%" class="field"><?= $lang["modify_by"] ?></td>
                            <td width="30%" class="value"><?= $prod_obj->get_modify_by() ?></td>
                        </tr>
                        <tr>
                            <td width="20%" class="field"><?= $lang["ean"] ?></td>
                            <td width="30%" class="value"><input type="text" name="ean"
                                                                 value="<?= $prod_obj->get_ean() ?>"></td>
                            <td width="20%" class="field"><?= $lang["mapping_code"] ?></td>
                            <td width="30%" class="value"><input type="text" name="ext_mapping_code"
                                                                 value="<?= $price_obj->get_ext_mapping_code() ?>"
                                                                 style="width:130px;"></td>
                        </tr>
                        <tr>
                            <td width="20%" class="field"><?= $lang["mpn"] ?></td>
                            <td width="30%" class="value"><input type="text" name="mpn"
                                                                 value="<?= $prod_obj->get_mpn() ?>"></td>
                            <td width="20%" class="field"><?= $lang["upc"] ?></td>
                            <td width="30%" class="value"><input type="text" name="upc"
                                                                 value="<?= $prod_obj->get_upc() ?>"
                                                                 style="width:130px;"></td>
                        </tr>

                    </table>
                </td>
            </tr>
            </table>
            <?php
            if ($canedit) {
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
DECL. = SELLING PRICE * DECL.% <br>
VAT = DECL. * VAT% <br>
DUTY = DECL. * DUTY% <br>
PAYPAL FEE = SELLING PRICE * PAYPAL FEE% + <script>document.write(paypal_fee_adj)</script> <br>
TOTAL COST =  TOTAL COST = VAT + DUTY + EBAY LISTING FEE + EBAY COMMISSION + PAYPAL FEE + LOGISTIC COST + SUPP.COST; <br>
PROFIT = SELLING PRICE + DELIVERY - TOTAL COST; <br>
PROFIT MARGIN = PROFIT / SELLING PRICE * 100%; <br>
</div>
*/ ?>
            <?php
            }
        } else {
            ?>
        <tr>
            <td style="padding-left:8px;">
                <br>
                <b><a class="warn" href="<?= base_url() ?>marketing/category/view_scpv/?subcat_id=<?= $prod_obj->get_sub_cat_id() ?>&platform=WSGB"><?= $lang["sub_cat_no_set"] ?></a></b>
            </td>
        </tr>
    </table>
<?php
        }
    }
    ?>
</div>
<?= $notice["js"] ?>
<?php

if ($prompt_notice) {
    ?>
    <script language="javascript">alert('<?=$lang["update_notice"]?>')</script><?php
}
?>
<script language="javascript">
    if (document.list) {
        lockqty(document.list.status.value);
    }
</script>
</body>
</html>