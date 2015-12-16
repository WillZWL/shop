<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <link rel="stylesheet" href="<?= base_url() ?>css/colorbox.css" />
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/jquery-colorbox.min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/pricing_tools/main.js"></script>
</head>
<script type="text/javascript">
    $(document).ready
    (
        function () {
            $(".iframe").colorbox({iframe: true, width: "40%", height: "80%"});
        }
    );
</script>
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
    <?php if ($sku != "") :
            if ($canedit) :
        ?>
            <form name="list" action="<?= base_url() . $this->tool_path ?>/view/<?= $platform_type ?>/<?= $prod_obj->getSku() . ($this->input->get('target') == "" ? "" : "?target=" . $this->input->get('target')) ?>" method="POST" onSubmit="return CheckForm(this)">
            <input type="hidden" name="sku" value="<?= $sku ?>">
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
            // if ($price_list[$platform_id]->getId()) :
                $price_obj = $price_list[$platform_id];
            // endif;
            $pobj = $value["pdata"]["dst"];

            ?>
                <tr class="header">
                    <td height="20" align="left" style="padding-left:8px;">
                        <b style="font-size: 12px; color: rgb(255, 255, 255);">
                            <a href="javascript:showHide('<?= $platform_type ?>', '<?= $platform ?>');"><span style="padding-right:15px;" id='sign_<?= $platform ?>'>+</span></a>
                            <?= $platform_id . " - " . $value["obj"]->getPlatformCountry() . " | " . $value["obj"]->getPlatformCurrencyId() . " | " ?>
                            <span id="title_<?= $platform ?>">

                                <?php
                                if ($pobj->getPrice()) :
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
                                   <input type="button" id="update_pricing_<?= $platform ?>" value="Update Pricing For <?= $platform ?>" onclick="update_pricing_for_platform('<?= $platform_type ?>', '<?= $platform ?>', '<?= $sku ?>');">
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
                            <input type="button" id="update_pricing_tool" value="<?= $lang['update_prod_for_price_tool'] ?>" onClick="update_product_for_pricing_tool('<?= $platform_type ?>', '<?= $sku ?>','all')">
                            <input type="submit" id="submit_all_changes" value="<?= $lang['submit_all_changes'] ?>" />
                            <div style="float:left" id='note_for_product'></div>
                        </td>
                    </tr>
                </table>
            </form>
            <?php endif;?>

    <?php else : ?>

            <tr>
                <td style="padding-left:8px;">
                    <br>
                    <b><a class="warn" target="_blank" href="<?= base_url() ?>marketing/category/view_scpv/?subcat_id=<?= $prod_obj->getSubCatId() ?>&platform=WEBSITE"><?= $lang["sub_cat_no_set"] ?></a></b>
                </td>
            </tr>
        </table>

    <?php endif; ?>
<?php endif; ?>
</div>
<?= $notice["js"] ?>
<?= $objlist["js"] ?>
<?php

if ($prompt_notice) :
    ?>
    <script language="javascript">alert('<?=$lang["update_notice"]?>')</script><?php
endif;
?>
</body>
</html>