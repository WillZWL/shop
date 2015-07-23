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
        <? include("delivery_charge_header_button_v.php"); ?>
        <tr>
            <td height="2" class="line"></td>
            <td height="2" class="line"></td>
        </tr>
    </table>
    <table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
        <tr>
            <td height="70" style="padding-left:8px"><b style="font-size:14px">Delivery
                    Charge(<?= $delivery_type_list[$delivery_type] ?>
                    ) <?= $lang["header"] ?></b><br><?= $lang["header_message"] ?>
            </td>
        </tr>
    </table>
    <form name="fm_edit" method="post" onSubmit="return CheckForm(this)">
        <table border="0" cellpadding="0" cellspacing="0" class="tb_list" id="tb_data">
            <thead>
            <tr>
                <?php
                $count_key = count((array)$key_country_list);
                $cur_datatype = "";
                $need_locked = $count_key > 1 ? " locked" : "";
                ?>
                <th height="22" width="20" locked></th>
                <th locked sortable dataType='float'><?= $lang['weight_cat'] ?></th>

                <?php
                $cols = 2;
                if ($dest_country_list[$platform_type]) {
                    $i = 0;
                    foreach ($dest_country_list[$platform_type] as $key => $val) {
                        ?>
                        <th width="90"<?= $i > 0 ? "" : "locked" ?>
                            title="<?= $val['country_name'] ?>"><?= $val['country_name'] ?></th>
                        <?php
                        $cols++;
                        $i++;
                    }
                }
                ?>
            </tr>
            </thead>
            <tbody>
            <?php
            $i = 0;
            if ($wc_list) {
                foreach ($wc_list as $wc_obj) {
                    ?>
                    <tr>
                        <td height="22"></td>
                        <td nowrap style="white-space:nowrap;"><?= $wc_obj->get_weight() ?></td>
                        <?php
                        foreach ($dest_country_list[$platform_type] as $key => $val) {
                            $wcc_obj = $wcc_list[$wc_obj->get_id()][$val['country_id']];
                            if ($wcc_list[$wc_obj->get_id()][$val['country_id']]) {
                                ?>
                                <td nowrap style="white-space:nowrap;">
                                    <?= $val['currency_id'] . " " ?><input
                                        name="value[<?= $wc_obj->get_id() ?>][<?= $val['country_id'] ?>][amount]"
                                        dname="<?= $val['country_name'] . ' with Weight Cat. ' . $wc_obj->get_weight() . 'kg' ?>"
                                        class="int_input" value="<?= $wcc_obj->get_amount() ?>" notEmpty isNumber min=0>
                                    <input name="value[<?= $wc_obj->get_id() ?>][<?= $val['country_id'] ?>][curr_id]"
                                           type="hidden" value="<?= $val['currency_id'] ?>">
                                </td>
                            <?php
                            } else {
                                ?>
                                <td nowrap style="white-space:nowrap;">
                                    <?= $val['currency_id'] . " " ?><input
                                        name="value[<?= $wc_obj->get_id() ?>][<?= $val['country_id'] ?>][amount]"
                                        dname="<?= $val['country_name'] . ' with Weight Cat. ' . $wc_obj->get_weight() . 'kg' ?>"
                                        class="int_input" value="0" notEmpty isNumber min=0>
                                    <input name="value[<?= $wc_obj->get_id() ?>][<?= $val['country_id'] ?>][curr_id]"
                                           type="hidden" value="<?= $val['currency_id'] ?>">
                                </td>
                            <?php
                            }
                        }
                        ?>
                    </tr>
                    <?php
                    $i++;
                }
            }
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
        <input type="hidden" name="posted" value="1">
        <input type="hidden" name="cmd" value="edit">
        <input type="hidden" name="origin_country" value="<?= $origin_country ?>">
    </form>
    <br>
    <!--<p align="left" style="padding-left:6px"><a href="<?= base_url() ?>mastercfg/freight/region/<?= $courier_id ?>" target="region"><?= $lang["region_list"] ?></p>-->
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