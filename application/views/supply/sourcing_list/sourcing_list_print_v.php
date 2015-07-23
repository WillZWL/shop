<html>
<head>
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
</head>
<body style="overflow:inherit"
      onLoad="if (parent.frames['printframe']){parent.frames['printframe'].focus();parent.frames['printframe'].print();}else{print();}">
<?php
$ar_src_status = array("A" => $lang["available"], "C" => $lang["stock_constraint"], "O" => $lang["temp_out_of_stock"], "L" => $lang["last_lot"], "D" => $lang["discontinued"]);
?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td height="30" class="title"><?= $lang["title"] ?></td>
        <td width="400" align="right" class="title"><?= $list_date ?></td>
    </tr>
    <tr>
        <td height="2" class="line"></td>
        <td height="2" class="line"></td>
    </tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" bgcolor="#000000" width="100%" class="tb_list">
    <tr class="header">
        <td><?= $lang["master_sku"] ?></td>
        <td><?= $lang["sku"] ?></td>
        <td><?= $lang["prod_name"] ?></td>
        <?php
        if ($platform) {
            foreach ($platform as $pf) {
                ?>
                <td><?= $pf ?></td>
            <?php
            }
        }
        ?>
        <td TITLE="<?= $lang["budget"] ?>"><?= $lang["budget"] ?></td>
        <td TITLE="<?= $lang["required_qty"] ?>"><?= $lang["req_qty"] ?></td>
        <td><?= $lang["comments"] ?></td>
    </tr>
    <?php
    $i = 0;
    if ($objlist) {
        foreach ($objlist as $obj) {
            $cur_master_sku = $obj->get_master_sku();
            $cur_sku = $obj->get_item_sku();
            ?>

            <tr class="row<?= $i % 2 ?>">
                <td><?= $cur_master_sku ?></td>
                <td><?= $cur_sku ?></td>
                <td><?= $obj->get_prod_name() ?></td>
                <?php
                if ($platform) {
                    foreach ($platform as $pf) {
                        $pg = $obj->get_platform_qty();
                        ?>
                        <td><?= $pg->$pf ?></td>
                    <?php
                    }
                }
                ?>
                <td><?= $obj->get_supplier_curr_id() . " " . $obj->get_budget() ?></td>
                <td><?= $obj->get_required_qty() ?></td>
                <td><?= $obj->get_comments() ?></td>
            </tr>
            <?php
            $i++;
        }
    }
    ?>
</table>
</body>
</html>