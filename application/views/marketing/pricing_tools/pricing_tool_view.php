<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <link rel="stylesheet" href="<?= base_url() ?>css/colorbox.css"/>
    <script type="text/javascript" src="<?= base_url() ?>js/jquery-colorbox.min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
    <script type="text/javascript">
        function lockqty(value) {
            if (value == 'O') {
                document.list.webqty.readOnly = true;
            }
            else {
                document.list.webqty.readOnly = false;
            }
        }

        function check_sub_cat_margin(platform) {
            if (platform.substring(0, 3).toUpperCase() == 'WEB') {
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
            if (platform.substring(0, 3).toUpperCase() == 'WEB') {
                if (document.getElementById('auto_price_cb[' + platform + ']').checked == true) {
                    if (document.getElementById('sub_cat_margin[' + platform + ']').value) {
                        document.getElementById('sp[' + platform + ']').readOnly = true;
                    }
                }
            }
        }

        function disable_option_value(option_value) {
            $("option[value=" + option_value + "]").attr("disabled", "disabled");
        }

        function enable_option_value(option_value) {
            $("option[value=" + option_value + "]").removeAttr("disabled");
        }

        function disable_element(element_id) {
            document.getElementById(element_id).disabled = true;
        }

        function enable_element(element_id) {
            document.getElementById(element_id).disabled = false;
        }

        function update_pricing_for_platform(type, platform, sku)
        {
            if (type == "WEBSITE") {
                $("#note_"+platform).html("Note:<font color='yellow'>It is run updating, wait...</font>");
                var selling_price = $("input[name='selling_price["+platform+"]']").val();
                var allow_express = $("input[name='allow_express["+platform+"]']:checked").val();
                var is_advertised = $("input[name='is_advertised["+platform+"]']:checked").val();
                var auto_price = $("select[name='auto_price["+platform+"]'] option:selected").val();
                var formtype = $("input[name='formtype["+platform+"]']").val();
                var listing_status = $("select[name='listing_status["+platform+"]'] option:selected").val();
                var fixed_rrp = $("input[name='fixed_rrp["+platform+"]']:checked").val();
                var rrp_factor = $("input[name='rrp_factor["+platform+"]']").val();
                var hidden_profit = $("input[name='hidden_profit["+platform+"]']").val();
                var hidden_margin = $("input[name='hidden_margin["+platform+"]']").val();

                var url = window.location.protocol + '//' + window.location.hostname + ':8000/marketing/pricing_tools/update_pricing_for_platform';
                var post_data = {
                        sku:sku,
                        platform:platform,
                        selling_price:selling_price,
                        allow_express:allow_express,
                        listing_status:listing_status,
                        is_advertised:is_advertised,
                        auto_price:auto_price,
                        formtype:formtype,
                        fixed_rrp:fixed_rrp,
                        rrp_factor:rrp_factor,
                        hidden_profit:hidden_profit,
                        hidden_margin:hidden_margin
                    };

                $.ajax({
                    url:url,
                    type:"POST",
                    dataType:"json",
                    data: post_data,
                    success: function (data) {
                        if (data.success) {
                            $("input[name='formtype["+platform+"]']").val('update');
                            var price = data.price;
                            var listing_status = data.listing_status;
                            var margin = data.margin;
                            if (margin > 0) {
                                var m_color = "88ff88;"
                            } else {
                                var m_color = "ff8888;"
                            }

                            if (listing_status == "L") {
                                var status = "Listed";
                                var s_color = "00FF00";
                            } else {
                                var status = "Not Listed";
                                var s_color = "FF0000";
                            }

                            $("#title_"+platform).html(price + " | <span style='color:#"+s_color+";'>"+ status + "</span> | <span style='color:#"+m_color+";'>"+ margin + "%</span>");
                            $("#note_"+platform).html("Note:<font color='blue'>It is update succeed</font>");
                        }
                        if (data.fail) {
                            $("#note_"+platform).html("Note:<font color='red'>It is update failed</font>");
                        }
                        if (data.no_update) {
                            $("#note_"+platform).html("Note:<font color='red'>No data changes, do not update</font>");
                        }
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        $("#note_"+platform).html("Note:<font color='red'>It is update error, "+errorThrown+"</font>");
                    }
                });

            }
        }

        function update_product_for_pricing_tool(sku)
        {
            var clearance = $("select[name='clearance'] option:selected").val();
            var status = $("select[name='status'] option:selected").val();
            var webqty = $("input[name='webqty']").val();
            var m_note = $("input[name='m_note']").val();
            var s_note = $("input[name='s_note']").val();
            var google_adwords = $("input[name='google_adwords[]']").val();
            var ext_mapping_code = $("input[name='ext_mapping_code']").val();
            var max_order_qty = $("input[name='max_order_qty']").val();



            var url = window.location.protocol + '//' + window.location.hostname + ':8000/marketing/pricing_tools/update_product_for_pricing_tool/'+sku;
            var post_data = {
                    clearance:clearance,
                    status:status,
                    webqty:webqty,
                    m_note:m_note,
                    s_note:s_note,
                    google_adwords:google_adwords,
                    ext_mapping_code:ext_mapping_code,
                    max_order_qty:max_order_qty
                };
            $.ajax({
                url:url,
                type:"POST",
                dataType:"html",
                data: post_data,
                success: function (data) {
                    alert(data);
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    // $("#note_"+platform).html("Note:<font color='red'>It is update error, "+errorThrown+"</font>");
                }
            });

        }
    </script>
    <script type="text/javascript" src="<?= base_url() . $this->tool_path ?>/get_js/"></script>
    <script type="text/javascript">
        $(document).ready
        (
            function () {
                $(".iframe").colorbox({iframe: true, width: "40%", height: "80%"});
            }
        );
    </script>
</head>
<body marginheight="0" marginwidth="0" topmargin="0" leftmargin="0" class="frame_left">
    <div id="main" style="width:auto">
<?php
    $ar_ws_status = ["I" => $lang["instock"], "O" => $lang["outstock"], "P" => $lang["pre-order"], "A" => $lang["arriving"]];
    $ar_src_status = ["A" => $lang["available"], "C" => $lang["stock_constraint"], "O" => $lang["temp_out_of_stock"], "L" => $lang["last_lot"], "D" => $lang["discontinued"]];
    $ar_l_status = ["L" => $lang["listed"], "N" => $lang["not_listed"]];
    $ar_l_status_color = ["L" => "#00FF00", "N" => "#FF0000"];
    $ar_comp_stock_status = [0 => $lang["instock"], 1 => $lang["outstock"], 2 => $lang["pre-order"], 3 => $lang["arriving"]];
    $ar_match = [0 => $lang["ignore"], 1 => $lang["active"]];
    $auto_price_action = ["N" => "No action", "Y" => "Auto Price", "C" => "Competitor Reprice"];
?>
    <?php if (!$valid_supplier) :?>
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td height="60" align="left" style="padding-left:8px;"><b>No Valid Supplier Cost</b></td>
            </tr>
        </table>
        <?php endif; ?>
    <?php if ($sku != "") : ?>

            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td height="60" align="left" style="padding-left:8px;">
                        <div style="float:left"><img src='<?= get_image_file($prod_obj->getImage(), 's', $prod_obj->getSku()) ?>'> &nbsp;</div>
                        <b style="font-size: 12px;"><?= $lang["header"] ?></b><br><?= $lang["header_message"] . " - " ?>
                        <b>
                            <a href="<?= ($website_product_url ? $website_product_url : "javascript:void(0)" )?>" target="_blank">
                                <font style="text-decoration:none; color:#000000; font-size:14px;">
                                    <?php $is_clearance = $prod_obj->getClearance() ? " <span style='color:#0072E3; font-size:14px;'>(Clearance)</span>" : "" ?>
                                    <?= $prod_obj->getSku() . " - " . $prod_obj->getName() . $is_clearance ?>
                                </font>
                            </a>
                        </b><br><?= $lang["master_sku"] . " " . $master_sku ?><br>
                    </td>
                </tr>
    <?php
    if ($objcount) :

        foreach ($pdata as $platform_id => $value) :
            $trow = $value["pdata"]["content"];
            $platform = $platform_id;
            $price_obj = $price_list[$platform_id];
            $pobj = $value["pdata"]["dst"];
            ?>
                <tr class="header">
                    <td height="20" align="left" style="padding-left:8px;">
                        <b style="font-size: 12px; color: rgb(255, 255, 255);">
                            <a href="javascript:showHide('<?= $platform ?>');"><span style="padding-right:15px;" id='sign_<?= $platform ?>'>+</span></a>
                            <?= $platform_id . " - " . $value["obj"]->getPlatformCountry() . " | " . $value["obj"]->getPlatformCurrencyId() . " | " ?>
                            <span id="title_<?= $platform ?>">
                                <?php
                                if ($pobj->getCurrentPlatformPrice() * 1) :
                                    echo $pobj->getPrice();
                                else :
                                    echo $lang["price_not_set"] . " <span class='converted'>({$this->default_platform_id} ".
                                         (($pobj->getDefaultPlatformConvertedPrice() * 1) ? ": {$pobj->getDefaultPlatformConvertedPrice()}" : $lang["price_not_set"]) ." )</span>";
                                endif;
                                ?>
                                |
                                <?= "<span style='color:" . $ar_l_status_color[$pobj->getListingStatus()] . "'>" . $ar_l_status[$pobj->getListingStatus()] . "</span> | " .
                                ($pobj->getMargin() > 0 ? '<span style="color:#88ff88;">' . $pobj->getMargin() . '%</span>' : '<span style="color:#ff8888;">' . $pobj->getMargin() . '%</span>') ?>
                            </span>
                        </b>
                    </td>
                </tr>
                <tr id="sp_<?= $platform ?>" style="display:block;">
                    <td height="5"></td>
                </tr>
                <tr id="prow_<?= $platform ?>" style="display:none;">
                    <td align="left">

                        <?php include ('pricing_tool_view_Pricing.php'); ?>

                        <?php if ($num_of_supplier == 0) : ?>
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr height="20" bgcolor="ffff00">
                                    <td align="center"><font style="font-weight:bold; color:#ee3333;"><?= $lang["no_default_supplier"] ?></font></td>
                                </tr>
                            </table>
                        <?php endif; ?>

                        <?php if ($price_obj->getListingStatus() == 'N') : ?>
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr height="20" bgcolor="ee3333">
                                    <td align="center"><font style="font-weight:bold; color:#dddddd;"><?= $lang["currrent_not_listed"] ?></font></td>
                                </tr>
                            </table>
                        <?php endif; ?>

                        <?php
                            switch ($platform_type) {
                                case 'WEBSITE':
                                    include ('pricing_tool_view__website.php');
                                    break;

                                case 'EBAY':
                                    include ('pricing_tool_view__ebay.php');
                                    break;

                                default:
                                    # code...
                                    break;
                            }
                        ?>

                        <?php if ($canedit) : ?>
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_detail">
                            <tr>
                                <td align="right" style="padding-right:8px;" height="30">
                                    <input type="button" value="Update Pricing For <?= $platform ?>" onclick="update_pricing_for_platform('<?= $platform_type ?>', '<?= $platform ?>', '<?= $sku ?>');">
                                    <div style="float:left" id='note_<?= $platform ?>'></div>
                                </td>
                            </tr>
                        </table>
                        <?php endif;?>
                    </td>
                </tr>
                <?php
            endforeach;
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

                        <?php include ('pricing_tool_view_prod_inforamtion.php'); ?>

                    </td>
                </tr>
            </table>
            <?php if ($canedit) : ?>
                <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_detail">
                    <tr>
                        <td align="right" style="padding-right:8px;" height="30">
                            <input type="button" id="update_pricing_tool" value="<?= $lang['update_prod_for_price_tool'] ?>" onClick="update_product_for_pricing_tool('<?= $sku ?>')">
                        </td>
                    </tr>
                </table>
            <?php endif;?>

    <?php else : ?>

            <tr>
                <td style="padding-left:8px;">
                    <br>
                    <b><a class="warn" href="<?= base_url() ?>marketing/category/view_scpv/?subcat_id=<?= $prod_obj->getSubCatId() ?>&platform=WEBSITE"><?= $lang["sub_cat_no_set"] ?></a></b>
                </td>
            </tr>
        </table>

    <?php endif; ?>
<?php endif; ?>
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