<html>
<head>
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>marketing/product/js_catlist"></script>
    <script type="text/javascript" src="<?= base_url() ?>mastercfg/freight_helper/js_freight_cat"></script>
</head>
<body>
<div id="main">
    <?= $notice["img"] ?>
    <?php
    $ar_status = array($lang["inactive"], $lang["created"], $lang["listed"]);
    $ar_ws_status = array("I" => $lang["instock"], "O" => $lang["outstock"], "P" => $lang["pre-order"], "A" => $lang["arriving"], "IS" => $lang["instock_supplier"]);
    $ar_src_status = array("A" => $lang["available"], "C" => $lang["stock_constraint"], "O" => $lang["temp_out_of_stock"], "L" => $lang["last_lot"], "D" => $lang["discontinued"]);
    ?>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td height="30" class="title"><?= $lang["title"] ?></td>
            <td width="400" align="right" class="title"><input type="button" value="<?= $lang["list_button"] ?>"
                                                               class="button"
                                                               onclick="Redirect('<?= site_url('marketing/bundle/') ?>')">
            </td>
        </tr>
        <tr>
            <td height="2" class="line"></td>
            <td height="2" class="line"></td>
        </tr>
    </table>
    <table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
        <tr>
            <td height="70" style="padding-left:8px">
                <div style="float:left"><img
                        src='<?= get_image_file($product->get_image(), 's', $product->get_sku()) ?>?<?= $product->get_modify_on() ?>'>
                    &nbsp;</div>
                <?= $lang["header"] ?><br><b
                    style="font-size:14px; color:#000000;"><?= $product->get_name() ?></b><br><?= $lang["sku"] ?>
                : <?= $product->get_sku() ?>
            </td>
        </tr>
    </table>
    <form name="fm" method="post" onSubmit="return CheckForm(this);" enctype="multipart/form-data">
        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_main">
            <col width="200">
            <col width="430">
            <col width="200">
            <col>
            <tr class="header">
                <td height="20" colspan="4"><?= $lang["table_header"] ?></td>
            </tr>
            <tr>
                <td class="field"><?= $lang["bundle_name"] ?></td>
                <td class="value"><input name="name" class="input" value="<?= htmlspecialchars($product->get_name()) ?>"
                                         notEmpty></td>
                <td class="field"><?= $lang["discount"] ?></td>
                <td class="value"><input name="discount" class="int_input" min="0"
                                         value="<?= htmlspecialchars($product->get_discount()) ?>"> %
                </td>
            </tr>
            <!--
    <tr>
        <td class="field"><?= $lang["quantity"] ?></td>
        <td class="value"><input name="quantity" class="int_input" value="<?= htmlspecialchars($product->get_quantity()) ?>" notEmpty isNatural></td>
        <td class="field"><?= $lang["rrp"] ?></td>
        <td class="value"><input name="rrp" class="int_input read" value="<?= htmlspecialchars($product->get_rrp()) ?>" READONLY> <?= $default_curr ?></td>
    </tr>
-->
            <tr>
                <td class="field"><?= $lang["website_status"] ?></td>
                <td class="value">
                    <select name="website_status" class="input" onChange="ChkOutStock(this)">
                        <?php
                        $selected_wss[$product->get_website_status()] = "SELECTED";
                        foreach ($ar_ws_status as $rskey => $rsvalue)
                        {
                        ?>
                        <option value="<?= $rskey ?>" <?= $selected_wss[$rskey] ?>><?= $rsvalue ?>
                            <?php
                            }
                            ?>
                    </select>
                </td>
                <!--
                <td class="field"><?= $lang["sourcing_status"] ?></td>
        <td class="value"><?= $ar_src_status[$product->get_sourcing_status()] ?></td>
-->
                <td class="field"><?= $lang["status"] ?></td>
                <td class="value">
                    <select name="status" class="input">
                        <?php
                        $selected_s[$product->get_status()] = "SELECTED";
                        foreach ($ar_status as $rskey => $rsvalue)
                        {
                        ?>
                        <option value="<?= $rskey ?>" <?= $selected_s[$rskey] ?>><?= $rsvalue ?>
                            <?php
                            }
                            ?>
                    </select>
                </td>

            </tr>
            <tr>
                <td class="field"><?= $lang["image_file"] ?></td>
                <td class="value">
                    <?php
                    if ($product->get_image()) {
                        $img_f = $product->get_sku() . "." . $product->get_image();
                        if (file_exists(IMG_PH . $img_f)) {
                            ?>
                            <a href="<?= base_url() ?>images/product/<?= $img_f ?>" target="preview"><?= $img_f ?></a>
                        <?php
                        }
                    }
                    ?>
                    <input type="file" name="image_file" size="60" accept="jpg,jpeg,gif,png" isSquare minHeight="400"
                           onChange="checkAccept(this);set_image(this);">

                    <div id="image_file_h"
                         style="position:absolute;left:-100000px;top:-100000px;filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod='image');height:1px;width:1px;">
                </td>
                <td class="field">&nbsp;</td>
                <td class="value">&nbsp;</td>
                <!--
        <td class="field"><?= $lang["youtube_id"] ?></td>
        <td class="value"><input name="youtube_id" class="input" value="<?= htmlspecialchars($product->get_youtube_id()) ?>"></td>
    <tr>
        <td class="field"><?= $lang["flash_file"] ?></td>
        <td class="value">
            <?php
                if ($product->get_flash()) {
                    $flash_f = $product->get_sku() . "." . $product->get_flash();
                    if (file_exists(IMG_PH . $flash_f)) {
                        ?>
                    <a href="<?= base_url() ?>images/product/<?= $flash_f ?>" target="preview"><?= $flash_f ?></a>
            <?php
                    }
                }
                ?>
            <input type="file" name="flash_file" size="65" class="input" accept="swf" onChange="checkAccept(this)">
        </td>
        <td class="field"><?= $lang["ean"] ?></td>
        <td class="value"><input name="ean" class="input" value="<?= htmlspecialchars($product->get_ean()) ?>"></td>
    </tr>
    <tr>
        <td class="field"><?= $lang["clearance"] ?></td>
        <td class="value"><input type="checkbox" name="clearance" value="1" <?= $product->get_clearance() ? "CHECKED" : "" ?>></td>
        <td class="field"><?= $lang["mpn"] ?></td>
        <td class="value"><input name="mpn" class="input" value="<?= htmlspecialchars($product->get_mpn()) ?>"></td>
    </tr>
    <tr>
        <td class="field"><?= $lang["ex_demo"] ?></td>
        <td class="value"><input type="checkbox" name="ex_demo" value="1" <?= $product->get_ex_demo() ? "CHECKED" : "" ?>></td>
        <td class="field"><?= $lang["upc"] ?></td>
        <td class="value"><input name="upc" class="input" value="<?= htmlspecialchars($product->get_upc()) ?>"></td>
    </tr>
-->
            <tr>
                <td class="field"><?= $lang["create_on"] ?></td>
                <td class="value"><?= $product->get_create_on() ?></td>
                <td class="field"><?= $lang["modify_on"] ?></td>
                <td class="value"><?= $product->get_modify_on() ?></td>
            </tr>
            <tr>
                <td class="field"><?= $lang["create_at"] ?></td>
                <td class="value"><?= $product->get_create_at() ?></td>
                <td class="field"><?= $lang["modify_at"] ?></td>
                <td class="value"><?= $product->get_modify_at() ?></td>
            </tr>
            <tr>
                <td class="field"><?= $lang["create_by"] ?></td>
                <td class="value"><?= $product->get_create_by() ?></td>
                <td class="field"><?= $lang["modify_by"] ?></td>
                <td class="value"><?= $product->get_modify_by() ?></td>
            </tr>
        </table>
        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_list">
            <col width="60">
            <col>
            <col width="50">
            <col width="50">
            <col width="50">
            <col width="50">
            <col width="50">
            <col width="50">
            <col width="50">
            <col width="50">
            <col width="50">
            <col width="50">
            <col width="50">
            <col width="50">
            <col width="50">
            <col width="50">
            <?= $components ?>
        </table>
        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_detail">
            <tr>
                <td colspan="2" height="40"><input type="button" name="back" value="<?= $lang['back_list'] ?>"
                                                   onClick="Redirect('<?= isset($_SESSION['LISTPAGE']) ? $_SESSION['LISTPAGE'] : base_url() . '/marketing/bundle' ?>')">
                </td>
                <td colspan="2" align="right" style="padding-right:8px;">
                    <input type="submit" value="<?= $lang['cmd_button'] ?>"
                           <? if ($cmd == "edit") { ?>onClick="if (top.frames['ra_prod'].document.forms[0]) top.frames['ra_prod'].document.forms[0].submit();"<? } ?>>
                </td>
            </tr>
        </table>
        <input name="sku" type="hidden" value="<?= $product->get_sku() ?>">
        <input name="colour_id" type="hidden" value="<?= $product->get_colour_id() ?>">
        <input type="hidden" name="posted" value="1">
    </form>
    <script>
        function ChkOutStock(el) {
            if (outstock != "") {
                alert(outstock + ' <?=$lang["outstock"]?>');
                el.value = 'O';
            }
        }
    </script>
    <?= $notice["js"] ?>
</div>
</body>
</html>