<html>
<head>
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
</head>
<body>
<div id="main">
    <?= $notice["img"] ?>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td height="30" class="title"><?= $lang["title"] ?></td>
            <td width="400" align="right" class="title">
            </td>
        </tr>
        <tr>
            <td height="2" class="line"></td>
            <td height="2" class="line"></td>
        </tr>
    </table>
    <table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
        <tr>
            <td height="70" style="padding-left:8px"><b
                    style="font-size:14px"><?= $lang["header"] ?></b><br><?= $lang["header_message"] ?></td>
        </tr>
    </table>
    <form name="fm_edit" method="post" onSubmit="return CheckForm(this)">
        <?php
        if ($class_list) {
            ?>
            <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_main">
                <tr class="header" style="height:25px;">
                    <td><?= $lang["price_range"] ?></td>
                    <?php
                    $cols = 2;
                    foreach ($class_list as $class_id => $class_obj) {
                        $str = is_null($price2 = $class_obj->get_price2()) ? " > " . $class_obj->get_price() : $class_obj->get_price() . " - " . $price2;
                        $cols++;
                        ?>
                        <td><?= $str ?></td>
                    <?php
                    }
                    ?>
                    <td rowspan="2"><?= $lang["min_reset_qty"] ?></td>
                </tr>
                <tr class="header" style="height:25px;">
                    <td><?= $lang["qty_control_range"] ?></td>
                    <?php
                    foreach ($class_list as $class_id => $class_obj) {
                        $str = is_null($qty2 = $class_obj->get_qty2()) ? $class_obj->get_qty() : $class_obj->get_qty() . " - " . $qty2;
                        ?>
                        <td><?= $str ?></td>
                    <?php
                    }
                    ?>
                </tr>
                <?php
                if ($cat_list) {
                    foreach ($cat_list as $cat_id => $cat_obj) {
                        ?>
                        <tr>
                            <td class="field"><?= $cat_obj->get_name() ?></td>
                            <?php
                            foreach ($class_list as $class_id => $class_obj) {
                                ?>
                                <td class="value"><input min="0" name="factor[<?= $cat_id ?>][<?= $class_id ?>]"
                                                         class="int_input"
                                                         value="<?= isset($factor_list[$cat_id][$class_id]) ? htmlspecialchars($factor_list[$cat_id][$class_id]->get_factor()) : "" ?>">
                                </td>
                            <?php
                            }
                            ?>
                            <td class="value"><input min="0" name="category[<?= $cat_id ?>]" class="int_input"
                                                     value="<?= $cat_obj->get_min_display_qty() ?>"></td>
                        </tr>
                    <?php
                    }
                }
                ?>
                <tr>
                    <td class="bfield1"><?= $lang["default"] ?></td>
                    <?php
                    foreach ($class_list as $class_id => $class_obj) {
                        ?>
                        <td class="bvalue1"><input min="0" name="class[<?= $class_id ?>][default_factor]"
                                                   class="int_input" value="<?= $class_obj->get_default_factor() ?>">
                        </td>
                    <?php
                    }
                    ?>
                    <td class="bvalue1"><input min="0" name="default_min_display_qty" class="int_input"
                                               value="<?= isset($default_min_display_qty) ? $default_min_display_qty->get_value() : "" ?>">
                    </td>
                </tr>
                <tr>
                    <td class="bfield0"><?= $lang["drop_qty_by"] ?></td>
                    <?php
                    foreach ($class_list as $class_id => $class_obj) {
                        ?>
                        <td class="bvalue0"><input min="0" name="class[<?= $class_id ?>][drop_qty]" class="int_input"
                                                   value="<?= $class_obj->get_drop_qty() ?>"></td>
                    <?php
                    }
                    ?>
                    <td class="bvalue0"></td>
                </tr>
                <tr>
                    <td colspan="<?= $cols ?>" align="right" style="padding-right:8px;" height="40" class="tb_detail">
                        <input type="submit" value="<?= $lang['update'] ?>">
                    </td>
                </tr>
            </table>
            <?= _form_ru() ?>
            <input type="hidden" name="posted" value="1">
        <?php
        }
        ?>
    </form>
    <?= $notice["js"] ?>
</div>
</body>
</html>