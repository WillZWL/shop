<html>
<head>
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
</head>
<body>
<table cellpadding="1" cellspacing="0" border="0" width="100%" class="page_header" style="table-layout:fixed;">
    <col width="25">
    <col width="200">
    <col width="20">
    <col width="25">
    <col width="20">
    <col width="25">
    <col width="25">
    <col width="25">
    <col width="25">
    <col width="200">
    <tbody>
    <?php
    // Below is product cost increase
    ?>
    <tr class="header">
        <td><?= $lang['sku'] ?></td>
        <td><?= $lang['product_inc'] ?></td>
        <td><?= $lang['status'] ?></td>
        <td align="right"><?= $lang['cost'] ?></td>
        <td align="right"><?= $lang['difference'] ?></td>
        <td align="right"><?= $lang['change'] ?></td>
        <td align="right"><?= $lang['stock'] ?></td>
        <td align="right"><?= $lang['profit'] ?></td>
        <td align="right"><?= $lang['margin'] ?></td>
        <td align="center"><?= $lang['note'] ?></td>
    </tr>
    <?php
    if ($cost_inc && is_array($cost_inc) && count($cost_inc) > 0) {
        $counter = 0;

        foreach ($cost_inc as $obj) {
            $mod = ++$counter % 2;
            ?>
            <tr valign="top" class="row<?= $mod ?>" name="row<?= $mod ?>">
                <td><?php echo $obj->get_sku();?></td>
                <td><?php echo $obj->get_prod_name();?></td>
                <td><?php echo $sourcing_status[$obj->get_sourcing_status()];?></td>
                <td align="right"><?php echo number_format(round($obj->get_supplier_cost(), 2), 2, '.', '');;?></td>
                <td align="right"><?php echo number_format(round($obj->get_cost_diff(), 2), 2, '.', '');;?></td>
                <td align="right"><?php echo number_format(round($obj->get_pcent_chg(), 2), 2, '.', '');;?></td>
                <td align="right"><?php echo $obj->get_inventory();?></td>
                <td align="right"><?php echo number_format(round($obj->get_profit(), 2), 2, '.', '');;?></td>
                <td align="right"><?php echo number_format(round($obj->get_margin(), 2), 2, '.', '');?></td>
                <td><?php echo $obj->get_note();?></td>
            </tr>
        <?php
        }
    } else {
        ?>
        <tr valign="top" class="row1" name="row1">
            <td colspan="10"><?echo $lang['no_data'];?></td>
        </tr>
    <?php
    }
    // Below is product cost decrease
    ?>
    <tr class="header">
        <td><?= $lang['sku'] ?></td>
        <td><?= $lang['product_dec'] ?></td>
        <td><?= $lang['status'] ?></td>
        <td align="right"><?= $lang['cost'] ?></td>
        <td align="right"><?= $lang['difference'] ?></td>
        <td align="right"><?= $lang['change'] ?></td>
        <td align="right"><?= $lang['stock'] ?></td>
        <td align="right"><?= $lang['profit'] ?></td>
        <td align="right"><?= $lang['margin'] ?></td>
        <td align="center"><?= $lang['note'] ?></td>
    </tr>
    <?php
    if ($cost_dec && is_array($cost_dec) && count($cost_dec) > 0) {
        $counter = 0;

        foreach ($cost_dec as $obj) {
            $mod = ++$counter % 2;
            ?>
            <tr valign="top" class="row<?= $mod ?>" name="row<?= $mod ?>">
                <td><?php echo $obj->get_sku();?></td>
                <td><?php echo $obj->get_prod_name();?></td>
                <td><?php echo $sourcing_status[$obj->get_sourcing_status()];?></td>
                <td align="right"><?php echo number_format(round($obj->get_supplier_cost(), 2), 2, '.', '');;?></td>
                <td align="right"><?php echo number_format(round($obj->get_cost_diff(), 2), 2, '.', '');;?></td>
                <td align="right"><?php echo number_format(round($obj->get_pcent_chg(), 2), 2, '.', '');;?></td>
                <td align="right"><?php echo $obj->get_inventory();?></td>
                <td align="right"><?php echo number_format(round($obj->get_profit(), 2), 2, '.', '');;?></td>
                <td align="right"><?php echo number_format(round($obj->get_margin(), 2), 2, '.', '');?></td>
                <td><?php echo $obj->get_note();?></td>
            </tr>
        <?php
        }
    } else {
        ?>
        <tr valign="top" class="row1" name="row1">
            <td colspan="10"><?echo $lang['no_data'];?></td>
        </tr>
    <?php
    }
    // Below is product sourcing status change
    ?>
    <tr class="header">
        <td><?= $lang['sku'] ?></td>
        <td><?= $lang['status_change'] ?></td>
        <td><?= $lang['status'] ?></td>
        <td align="right"><?= $lang['cost'] ?></td>
        <td align="right"><?= $lang['difference'] ?></td>
        <td align="right"><?= $lang['change'] ?></td>
        <td align="right"><?= $lang['stock'] ?></td>
        <td align="right"><?= $lang['profit'] ?></td>
        <td align="right"><?= $lang['margin'] ?></td>
        <td align="center"><?= $lang['note'] ?></td>
    </tr>
    <?php
    if ($status_chg && is_array($status_chg) && count($status_chg) > 0) {
        $counter = 0;

        foreach ($status_chg as $obj) {
            $mod = ++$counter % 2;
            ?>
            <tr valign="top" class="row<?= $mod ?>" name="row<?= $mod ?>">
                <td><?php echo $obj->get_sku();?></td>
                <td><?php echo $obj->get_prod_name();?></td>
                <td><?php echo $sourcing_status[$obj->get_sourcing_status()];?></td>
                <td align="right"><?php echo number_format(round($obj->get_supplier_cost(), 2), 2, '.', '');;?></td>
                <td align="right"><?php echo number_format(round($obj->get_cost_diff(), 2), 2, '.', '');;?></td>
                <td align="right"><?php echo number_format(round($obj->get_pcent_chg(), 2), 2, '.', '');;?></td>
                <td align="right"><?php echo $obj->get_inventory();?></td>
                <td align="right"><?php echo number_format(round($obj->get_profit(), 2), 2, '.', '');;?></td>
                <td align="right"><?php echo number_format(round($obj->get_margin(), 2), 2, '.', '');?></td>
                <td><?php echo $obj->get_note();?></td>
            </tr>
        <?php
        }
    } else {
        ?>
        <tr valign="top" class="row1" name="row1">
            <td colspan="10"><?echo $lang['no_data'];?></td>
        </tr>
    <?php
    }
    // Below is new product
    ?>
    <tr class="header">
        <td><?= $lang['sku'] ?></td>
        <td colspan="2"><?= $lang['new_product'] ?></td>
        <td colspan="2"><?= $lang['status'] ?></td>
        <td align="right" colspan="2"><?= $lang['cost'] ?></td>
        <td align="right" colspan="2"><?= $lang['stock'] ?></td>
        <td align="center">&nbsp;</td>
    </tr>
    <?php
    if ($new_product && is_array($new_product) && count($new_product) > 0) {
        $counter = 0;

        foreach ($new_product as $obj) {
            $mod = ++$counter % 2;
            ?>
            <tr valign="top" class="row<?= $mod ?>" name="row<?= $mod ?>">
                <td><?php echo $obj->get_sku();?></td>
                <td colspan="2"><?php echo $obj->get_prod_name();?></td>
                <td colspan="2"><?php echo $sourcing_status[$obj->get_sourcing_status()];?></td>
                <td align="right"
                    colspan="2"><?php echo number_format(round($obj->get_supplier_cost(), 2), 2, '.', '');;?></td>
                <td align="right" colspan="2"><?php echo $obj->get_inventory();?></td>
                <td>&nbsp;</td>
            </tr>
        <?php
        }
    } else {
        ?>
        <tr valign="top" class="row1" name="row1">
            <td colspan="10"><?echo $lang['no_data'];?></td>
        </tr>
    <?php
    }
    ?>
    <tr>
        <td height="2" bgcolor="#000033" colspan="3"></td>
    </tr>
    </tbody>
</table>
</body>
</html>