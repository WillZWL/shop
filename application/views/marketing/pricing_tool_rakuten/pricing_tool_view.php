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
                        rePrice(platform, '<?=$prod_obj->get_sku();?>');
                    }
                }
                else {
                    document.getElementById('sp[' + platform + ']').readOnly = false;
                    document.getElementById('sp[' + platform + ']').value = document.getElementById('origin_price[' + platform + ']').value;
                    rePrice(platform, '<?=$prod_obj->get_sku();?>');
                }
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
$ar_ws_status = array("I" => $lang["instock"], "O" => $lang["outstock"], "P" => $lang["pre-order"], "A" => $lang["arriving"]);
$ar_src_status = array("A" => $lang["available"], "C" => $lang["stock_constraint"], "O" => $lang["temp_out_of_stock"], "L" => $lang["last_lot"], "D" => $lang["discontinued"]);
$ar_l_status = array("L" => $lang["listed"], "N" => $lang["not_listed"]);
$ar_l_status_color = array("L" => "#00FF00", "N" => "#FF0000");
$ar_comp_stock_status = array(0 => $lang["instock"], 1 => $lang["outstock"], 2 => $lang["pre-order"], 3 => $lang["arriving"]);
$ar_match = array(0 => $lang["ignore"], 1 => $lang["active"]);
$auto_price_action = array("N" => "No action", "Y" => "Auto Price");
$gtin_type_arr = array("EAN", "ISBN", "JAN", "UPC");
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
                $pobj = $value["pdata"]["dst"];
                $delivery_obj = $delivery_info[$platform_id];
                $price_ext_obj = $price_ext[$platform_id]["obj"];
                $ext_inventory = $price_ext[$platform_id]["ext_inventory"];

                $feed_include_str = $feed_include[$platform_id];
                $feed_exclude_str = $feed_exclude[$platform_id];

                $website_price_title = $pdata[$platform]["website"]["price_title"];
                $website_price = $pdata[$platform]["website"]["price"];
                $prod_name_lang = $pdata[$platform]["website"]["prod_name_lang"];
                $detail_desc = $pdata[$platform]["website"]["detail_desc"];

                // customised description
                $ext_desc = $price_ext_obj->get_ext_desc();
                if (!$ext_desc) {
                    $ext_desc = $detail_desc;
                }
                ?>
                <tr class="header">
                    <td height="20" align="left" style="padding-left:8px;"><b
                            style="font-size: 12px; color: rgb(255, 255, 255);"><span style="padding-right:15px;"
                                                                                      id='sign_<?= $platform ?>'>-</span><?= $platform_id . " - " . $value["obj"]->get_platform_name() . " | " . $value["obj"]->get_platform_currency_id() . " | " ?>
                            <!-- <td height="20" align="left" style="padding-left:8px;"><b style="font-size: 12px; color: rgb(255, 255, 255);"><a href="javascript:showHide('<?= $platform ?>');"><span style="padding-right:15px;" id='sign_<?= $platform ?>'>+</span></a><?= $platform_id . " - " . $value["obj"]->get_platform_country() . " | " . $value["obj"]->get_platform_currency_id() . " | " ?> -->
                            <?php
                            if ($pobj->get_current_platform_price() * 1) {
                                echo $pobj->get_price();
                            } else {
                                echo $lang["price_not_set"] . " <span class='converted'>({$this->default_platform_id} ";
                                if ($pobj->get_default_platform_converted_price() * 1) {
                                    echo ": {$pobj->get_default_platform_converted_price()}";
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
                                    case "RAKUES":
                                        $raku_url = "<a href='https://rms.rakuten.es/products/edit/" . $prod_obj->get_sku() . "' target='_blank'>https://rms.rakuten.es/products/edit/" . $prod_obj->get_sku() . "</a>";
                                        break;
                                }
                                echo " | " . $raku_url;
                            }
                            ?>
                        </b>
                    </td>
                </tr>
                <tr id="sp_<?= $platform ?>" style="display:block;">
                    <td height="5"></td>
                </tr>

                <tr id="prow_<?= $platform ?>" style="display:block;">
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
                                <td class="field" width="20%"><?= $website_price_title ?></td>
                                <td class="value" colspan="3"><?= $website_price ?></td>
                            </tr>
                            <tr>
                                <td class="field" width="20%">Title (max 255 char)</td>
                                <td colspan="3" class="value">
                                    <input name="price_ext[<?= $platform ?>][title]"
                                           value="<?= $price_ext_obj->get_title() ? htmlspecialchars($price_ext_obj->get_title()) : htmlspecialchars($prod_name_lang) ?>"
                                           class="input" maxlength="255">
                                    <br><?= (trim($detail_desc) == "") ? "<font color='red'><b>Detail Desc is empty!</b></font>" : ""; ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="field" width="20%">Description (Max 15,000 char)<br><font color='red'><b>*DO
                                            NOT LEAVE THIS EMPTY</b></font></td>
                                <td colspan="3" class="value">
                                    <textarea name="price_ext[<?= $platform ?>][ext_desc]" required="1"
                                              maxlength="15000" cols="150"><?= htmlspecialchars($ext_desc) ?></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td width="20%" class="field">GTIN Type / Value (max 17 char)</td>
                                <td width="30%" class="value">
                                    <?php
                                    if ($price_ext_obj->get_ext_ref_3()) {
                                        list($gtin_type, $gtin_val) = explode("=", $price_ext_obj->get_ext_ref_3());
                                    }

                                    $gtin_html = "<select name=\"price_ext[$platform][gtin][type]\"><option></option>";
                                    foreach ($gtin_type_arr as $key => $value) {
                                        $gtin_selected = "";
                                        if ($value == $gtin_type)
                                            $gtin_selected = " SELECTED";
                                        $gtin_html .= <<<html
                        <option value = $value $gtin_selected>$value</option>
html;
                                    }
                                    $gtin_html .= "</select>";
                                    echo $gtin_html;
                                    ?>
                                    <input name="price_ext[<?= $platform ?>][gtin][val]" class="input" type="text"
                                           value="<?= $gtin_val ? $gtin_val : "" ?>" style="width:180px;"
                                           maxlength="17">
                                    <br>API not working, pls change GTIN type on Rakuten's admin after updating here.
                                </td>
                                <td width="20%" class="field"><?= $lang["listing_status"] ?></td>
                                <td width="30%" class="value">
                                    <select name="listing_status[<?= $platform ?>]" style="width:130px;">
                                        <option value="N"><?= $lang["not_listed"] ?></option>
                                        <option
                                            value="L" <?= ($price_obj->get_listing_status() == "L" ? "SELECTED" : "") ?>><?= $lang["listed"] ?></option>
                                    </select>
                                    <input type="hidden" name="selling_platform[<?= $platform ?>]"
                                           value="<?= $platform ?>">
                                    <input type="hidden" name="formtype[<?= $platform ?>]"
                                           value="<?= $formtype[$platform] ?>">

                                </td>
                            </tr>
                            <tr>
                                <td width="20%" class="field">VB Shop Category in Rakuten</td>
                                <td width="30%" class="value">
                                    <select name="price_ext[<?= $platform ?>][ext_ref_4]">
                                        <option></option>
                                        <?php
                                        if ($store_cat_list[$platform]) {
                                            foreach ($store_cat_list[$platform] as $store_cat) {
                                                ?>
                                                <option
                                                    value="<?= $store_cat->get_ext_id() ?>" <?= ($price_ext_obj->get_ext_ref_4() == $store_cat->get_ext_id() ? "SELECTED" : "") ?>><?= $store_cat->get_ext_name() ?></option>
                                            <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td width="20%" class="field">Listing Qty <br>limitPurchaseQuantity <br>Rakuten Shop
                                    Inventory
                                </td>
                                <td width="30%" class="value">
                                    <input name="price_ext[<?= $platform ?>][ext_qty]" class="input" type="text"
                                           value="<?= $price_ext_obj->get_ext_qty() == "" ? "" : $price_ext_obj->get_ext_qty() ?>"
                                           style="width:80px;"> (-1 = unlimited)<br>
                                    <input name="price_ext[<?= $platform ?>][ext_ref_2]" class="input" type="text"
                                           value="<?= $price_ext_obj->get_ext_ref_2() == "" ? "" : $price_ext_obj->get_ext_ref_2() ?>"
                                           style="width:80px;"> (-1 = unlimited) <br>
                                    <?= $ext_inventory == "" ? 'N.A.' : $ext_inventory ?>
                                </td>
                            </tr>
                            <tr>
                                <td width="20%" class="field"><?= $lang["auto_price"] ?></td>
                                <td width="30%" class="value">
                                    <select id="auto_price_cb[<?= $platform ?>]" id="auto_price_cb[<?= $platform ?>]"
                                            name="auto_price[<?= $platform ?>]"
                                            onchange="check_sub_cat_margin('<?= $platform ?>')">
                                        <?php
                                        foreach ($auto_price_action as $key => $action) {
                                            $selected = "";
                                            if (($auto_price_status = $price_obj->get_auto_price()) == "")
                                                $auto_price_status = "N";

                                            if ($auto_price_status == $key)
                                                $selected = " SELECTED";
                                            ?>
                                            <option value=<?= $key ?> <?= $selected ?>><?= $action ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td width="20%" class="field"><?= $lang["sub_cat_margin"] ?></td>
                                <td width="30%"
                                    class="value"><?= $sub_cat_margin[$platform] ? $sub_cat_margin[$platform] : 'Margin not yet set' ?></td>
                            </tr>
                            <tr>
                                <td width="20%" class="field">RRP (listedPrice)</td>
                                <td width="30%" class="value">
                                    <div><input type="radio" id="fixed_rrp[<?= $platform ?>]"
                                                name="fixed_rrp[<?= $platform ?>]"
                                                value="Y" <?= ($price_obj->get_fixed_rrp() != 'Y' ? "" : "CHECKED") ?>
                                                onClick="javascript:document.getElementById('rrp_factor[<?= $platform ?>]').readOnly=true"><?= $lang["system_default_rrp"] ?>
                                    </div>
                                    <div>
                                        <input type="radio" id="fixed_rrp[<?= $platform ?>]"
                                               name="fixed_rrp[<?= $platform ?>]"
                                               value="N" <?= ($price_obj->get_fixed_rrp() != 'Y' ? "CHECKED" : "") ?>
                                               onClick="javascript:document.getElementById('rrp_factor[<?= $platform ?>]').readOnly=false"><?= $lang["random_rrp"] ?>
                                        &nbsp;&nbsp;&nbsp;
                                        <?= (($price_obj->get_fixed_rrp() == 'N' && $price_obj->get_rrp_factor() < 10) ? '(' . $lang["rrp"] . ' = ' . $lang["selling_price"] . ' * ' . $price_obj->get_rrp_factor() . ')' : "") ?>
                                    </div>
                                    <div style="padding-left:20px">
                                        <input style="width:70px" type="text" id="rrp_factor[<?= $platform ?>]"
                                               name="rrp_factor[<?= $platform ?>]"
                                               value="<?= ($price_obj->get_fixed_rrp() == 'N' ? ($price_obj->get_rrp_factor() >= 10 ? $price_obj->get_rrp_factor() : "") : "") ?>" <?= ($price_obj->get_fixed_rrp() != 'Y' ? "" : "readOnly") ?>
                                               isNumber>
                                        &nbsp;
                                        <?= $lang["manual_rrp_note"] ?>
                                    </div>
                                </td>
                                <td width="20%" class="field"></td>
                                <td width="30%" class="value"></td>
                            </tr>
                            <?php
                            // if have ext_item_id, means lsiting exists on Rakuten
                            if ($price_ext_obj->get_ext_item_id()) {
                                ?>
                                <tr>
                                    <td class="field"></td>
                                    <td class="value"></td>
                                    <td class="field">Action</td>
                                    <td class="value">
                                        <select id="action[<?= $platform ?>]" name="action[<?= $platform ?>]"
                                                style="width:130px;">
                                            <option></option>
                                            <option value="R">Re-add item</option>
                                            <option value="RE">Revise item</option>
                                            <option value="E">End item</option>
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
                        <?php
                        if ($pdata[$platform_id]["competitor"]) {
                            ?>
                            <table width="100%" border="0" cellpadding="0" cellspacing="0" class="tb_main">
                                <col width="15%">
                                <col width="8%">
                                <col width="8%">
                                <col width="10%">
                                <col width="10%">
                                <col width="12%">
                                <col width="15%">
                                <tr>
                                    <td class="row1"><b>Competitor</b></td>
                                    <td class="row1"><b>Price</b></td>
                                    <td class="row1"><b>Shipping Cost</b></td>
                                    <td class="row1"><b>Price Difference</b></td>
                                    <td class="row1"><b>Stock Status</b></td>
                                    <td class="row1"><b>Cheapest Seller</b></td>
                                    <td class="row1"><b>Spider's last upload time</b></td>
                                    <td class="row1"><b>Match</b></td>
                                </tr>
                                <?php
                                $islowestmatch = TRUE;
                                # this loop arrange competitors' price difference in ascending order (cheapest first)
                                foreach ($pdata[$platform_id]["competitor"]["price_diff"] as $key => $price_diff) {
                                    foreach ($pdata[$platform_id]["competitor"]["comp_mapping_obj"] as $obj) {
                                        if ($key == $obj->get_competitor_id()) {
                                            $compmapadmin_url = base_url() . "marketing/competitor_map/index/{$obj->get_country_id()}?master_sku=$master_sku&country_id={$obj->get_country_id()}";

                                            if ($islowestmatch == TRUE && $obj->get_match() == 1) {
                                                ?>
                                                <tr>
                                                    <td bgcolor="greenyellow"><b><a target="_blank"
                                                                                    href="<?= $obj->get_product_url() ?>"><?= $obj->get_competitor_name() ?></a></b>
                                                    </td>
                                                    <td bgcolor="greenyellow"><b><?= $obj->get_now_price() ?></b></td>
                                                    <td bgcolor="greenyellow"><b><?= $obj->get_comp_ship_charge() ?></b>
                                                    </td>
                                                    <td bgcolor="greenyellow"><b><?= $price_diff ?></b></td>
                                                    <td bgcolor="greenyellow">
                                                        <b><?= $ar_comp_stock_status[$obj->get_comp_stock_status()] ?>
                                                    </td>
                                                    <td bgcolor="greenyellow"><b><?= $obj->get_note_1() ?></b></td>
                                                    <td bgcolor="greenyellow">
                                                        <b><?= $obj->get_sourcefile_timestamp() ?></b></td>
                                                    <td bgcolor="greenyellow"><b><a target="_blank"
                                                                                    href="<?= $compmapadmin_url ?>"><?= $ar_match[$obj->get_match()] ?>
                                                        </b></a></td>
                                                </tr>
                                                <?php
                                                $islowestmatch = FALSE;
                                            } else {
                                                ?>

                                                <tr>
                                                    <td class="row1"><a target="_blank"
                                                                        href="<?= $obj->get_product_url() ?>"><?= $obj->get_competitor_name() ?></a>
                                                    </td>
                                                    <td class="row1"><?= $obj->get_now_price() ?></td>
                                                    <td class="row1"><?= $obj->get_comp_ship_charge() ?></td>
                                                    <td class="row1"><?= $price_diff ?></td>
                                                    <td class="row1"><?= $ar_comp_stock_status[$obj->get_comp_stock_status()] ?></td>
                                                    <td class="row1"><?= $obj->get_note_1() ?></td>
                                                    <td class="row1"><?= $obj->get_sourcefile_timestamp() ?></td>
                                                    <td class="row1"><a target="_blank"
                                                                        href="<?= $compmapadmin_url ?>"><?= $ar_match[$obj->get_match()] ?></a>
                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                        }
                                    }
                                }
                                ?>
                            </table>
                        <?php

                        }
                        ?>

                    </td>
                </tr>
            <?php  }// foreach $pdata

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
                                                                  onChange="lockqty(this.value)">
                                    <?php
                                    foreach ($ar_ws_status as $key => $value) {
                                        ?>
                                        <option
                                            value="<?= $key ?>" <?= ($prod_obj->get_website_status() == $key ? "SELECTED" : "") ?>><?= $value ?></option>
                                    <?php
                                    }
                                    ?>
                                </select></td>
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
                                <div><?= $lang["current_notes"] ?></div>
                                <?php
                                if (!empty($mkt_note_obj)) {
                                    foreach ($mkt_note_obj as $nobj) {
                                        ?>
                                        <div><?= $nobj->get_note() ?><br><span
                                                style="font-size:9px; color:#888888; font-style:italic;"><?= $lang["create_by"] . $nobj->get_username() . $lang["on"] . $nobj->get_create_on() ?></span>
                                        </div>
                                    <?php
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
                                <div><?= $lang["current_notes"] ?></div>
                                <?php
                                if (!empty($src_note_obj)) {
                                    foreach ($src_note_obj as $nobj) {
                                        ?>
                                        <div><?= $nobj->get_note() ?><br><span
                                                style="font-size:9px; color:#888888; font-style:italic;"><?= $lang["create_by"] . $nobj->get_username() . $lang["on"] . $nobj->get_create_on() ?></span>
                                        </div>
                                    <?php
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
                            <td width="30%" class="value" valign="top" style="padding-top:2px; padding-bottom:2px;">
                                <input type="text" name="max_order_qty"
                                       value="<?= ($price_obj->get_max_order_qty() == "" ? "100" : $price_obj->get_max_order_qty()) ?>">
                            </td>
                            <!-- <td width="20%" class="field"><?= $lang["mapping_code"] ?></td> -->
                            <!-- <td width="30%" class="value"><input type="text" name="ext_mapping_code" value="<?= $price_obj->get_ext_mapping_code() ?>" style="width:130px;"></td> -->

                            <td width="20%" class="field" valign="top"></td>
                            <td width="30%" class="value" valign="top"></td>
                        </tr>
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
            }
        } else {
            ?>
        <tr>
            <td style="padding-left:8px;">
                <br>
                <b><a class="warn" href="<?= base_url() ?>marketing/category/view_scpv/?subcat_id=<?= $prod_obj->get_sub_cat_id() ?>&platform=RAKU"><?= $lang["sub_cat_no_set"] ?></a></b>
            </td>
        </tr>
    </table>
<?php
        } // end if/else $objcount
    } // end if/else $value
    ?>
</div>
<?= $notice["js"] ?>
<?php

if ($prompt_notice) {
    ?>
    <script language="javascript">alert('<?=$lang["update_notice"]?>')</script>
<?php
}
?>
<script language="javascript">
    if (document.list) {
        lockqty(document.list.status.value);
    }
</script>
</body>
</html>