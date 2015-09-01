<html>
<head>
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>js/ext-js/resources/css/ext-all.css"/>
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>js/ext-js/resources/css/LockingGridView.css"/>
    <script type="text/javascript" src="<?= base_url() ?>js/ext-js/adapter/ext/ext-base.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/ext-js/ext-all.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/ext-js/ux-all.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/ext-js/TableGridConfig.js"></script>
</head>
<body>
<div id="main">
    <?= $notice["img"] ?>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td height="30" class="title"><?= $lang["title"] ?></td>
            <td width="400" align="right" class="title"></td>
        </tr>
        <?php include("freight_header_button_v.php"); ?>
        <tr>
            <td height="2" class="line"></td>
            <td height="2" class="line"></td>
        </tr>
    </table>
    <table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
        <tr>
            <td height="70" style="padding-left:8px"><b style="font-size:14px">Freight Cost(<?= $origin_country ?>
                    ) <?= $lang["header"] ?></b><br><?= $lang["header_message"] ?>
            </td>
        </tr>
    </table>
    <form name="fm_edit" method="post" onSubmit="return CheckForm(this)">
        <input type="hidden" name="posted" value="1">
        <input type="hidden" name="cmd" value="edit">
        <input type="hidden" name="origin_country" value="<?= $origin_country ?>">
        <table border="0" cellpadding="0" cellspacing="0" class="tb_list" id="tb_data">
            <thead>
            <tr>
                <?php
                $count_key = count((array)$key_country_list);
                $cur_datatype = "";
                $need_locked = $count_key > 1 ? " locked" : "";
                ?>
                <th height="22" width="20"<?= $need_locked ?>></th>
                <th<?= $need_locked ?> sortable<?= $cur_datatype ?>><?= $lang['freight_cat'] ?></th>

                <?php
                $cols = 2;
                if ($key_country_list) :
                    $i = 0;
                    foreach ($key_country_list as $country_id => $country_name) :
                        ?>
                        <th width="90"<?= "";//$cur_locked ?> title="<?= $country_name ?>"><?= $country_name ?></th>
                        <?php
                        $cols++;
                        $i++;
                    endforeach;
                endif;
                ?>
            </tr>
            </thead>
            <tbody>
            <?php
            $i = 0;
            if ($key_freight_list) :
                foreach ($key_freight_list as $cur_cat_id => $cur_cat_name) :
                    ?>
                    <tr>
                        <td height="22"></td>
                        <td nowrap style="white-space:nowrap;"><?=$i?> / <?= $cur_cat_name ?></td>
                        <?php
                        foreach ($key_country_list as $country_id => $country_name) :
                            if ($fcc_obj = $objlist[$cur_cat_id][$country_id]) :
                                $cur_currency_id = $fcc_obj->getCurrencyId();
                                ?>
                                <td nowrap style="white-space:nowrap;">
                                    <?= $cur_currency_id ?>
                                    <input name="value[<?= $cur_cat_id ?>][<?= $country_id ?>]" class="int_input" value="<?= $fcc_obj->getAmount() ?>" notEmpty isNumber min=0>
                                </td>
                            <?php
                            endif;
                        endforeach;
                        ?>
                    </tr>
                    <?php
                    $i++;
                endforeach;
            endif;
            ?>
            </tbody>
        </table>
        <div id="div_list"></div>
        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_list">
            <tr>
                <td align="right" style="padding-right:8px;" height="40" class="tb_detail">
                    <input type="submit" value="<?= $lang['update_button'] ?>">
                </td>
            </tr>
        </table>
    </form>
    <br>
    <?= $notice["js"] ?>
    <script>
        Ext.onReady(function () {
            var grid_config = new Ext.ux.grid.TableGridConfig("tb_data");

            var grid = new Ext.grid.GridPanel({
                'ds': grid_config.ds,
                colModel: new Ext.ux.grid.LockingColumnModel(grid_config.cols),
                stripeRows: true,
                height: Ext.getBody().getViewSize().height - 220,
                width: '100%',
                view: new Ext.ux.grid.LockingGridView()
            });
            grid.render("div_list");
        });
    </script>
</div>
</body>
</html>