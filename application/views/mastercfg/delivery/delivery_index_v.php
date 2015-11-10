<html>
<head>
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>js/ext-js/resources/css/ext-all.css"/>
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>js/ext-js/resources/css/tab-scroller-menu.css"/>
    <script type="text/javascript" src="<?= base_url() ?>js/ext-js/adapter/ext/ext-base.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/ext-js/ext-all.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/ext-js/TabScrollerMenu.js"></script>
</head>
<body>
<div id="main">
    <?= $notice["img"] ?>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td height="30" class="title"><?= $lang["title"] ?></td>
            <td width="400" align="right" class="title">
                <input type="button" value="<?= $lang["content_management"] ?>" style="padding:0px 4px 0px 4px;"
                       onclick="Redirect('<?= site_url('mastercfg/delivery') ?>')">
                &nbsp; <input type="button" value="<?= $lang["region_management"] ?>" style="padding:0px 4px 0px 4px;"
                              onclick="Redirect('<?= site_url('mastercfg/delivery/region') ?>')">
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
        <?php if ($lang_list) :
            ?>
            <div id="div_tabs">
                <?php
                foreach ($lang_list as $lang_obj) :
                    $cur_lang_id = $lang_obj->getLangId();
                    // $cur_name = $cat_ext[$cat_id][$cur_lang_id] ? $cat_ext[$cat_id][$cur_lang_id]->getLangName() : "";
                    $cur_title = (isset($func_opt_list[$cur_lang_id]) || isset($del_opt_list[$cur_lang_id])) ? $lang_obj->getLangName() : "<font color='red'>{$lang_obj->getLangName()}</font>";
                    ?>
                    <div id="div_tab_<?= $cur_lang_id ?>" class="x-tab" title="<?= $cur_title ?>">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_main">
                            <col width="300">
                            <col>
                            <tr class="header">
                                <td height="20" colspan="2"><?= $lang["general_content"] ?></td>
                            </tr>
                            <?php
                            if ($delivery_type_list) :
                                foreach ($delivery_type_list as $dt_obj) :
                                    $dt_id = strtolower($dt_obj->getDeliveryTypeId());
                                    if ($func_opt_list["en"]["free_" . $dt_id]) :
                                        ?>
                                        <tr>
                                            <td class="field"><?= $func_opt_list["en"]["free_" . $dt_id]->getText() ?></td>
                                            <td class="value"><input
                                                    name="func_opt[<?= $cur_lang_id ?>][free_<?= $dt_id ?>][<?= ($exists = isset($func_opt_list[$cur_lang_id]["free_" . $dt_id])) ? $func_opt_list[$cur_lang_id]["free_" . $dt_id]->getId() : "new" ?>]"
                                                    class="input"
                                                    value="<?= $exists ? htmlspecialchars($func_opt_list[$cur_lang_id]["free_" . $dt_id]->getText()) : "" ?>">
                                            </td>
                                        </tr>
                                    <?php
                                    endif;
                                endforeach;
                            endif;
                            ?>
                            <tr>
                                <td class="field"><?= $lang["working_days"] ?></td>
                                <td class="value"><input
                                        name="func_opt[<?= $cur_lang_id ?>][working_days][<?= ($exists = isset($func_opt_list[$cur_lang_id]["working_days"])) ? $func_opt_list[$cur_lang_id]["working_days"]->getId() : "new" ?>]"
                                        class="input"
                                        value="<?= $exists ? htmlspecialchars($func_opt_list[$cur_lang_id]["working_days"]->getText()) : "" ?>">
                                </td>
                            </tr>
                            <tr class="header">
                                <td height="20" colspan="2"><?= $lang["del_opt_content"] ?></td>
                            </tr>
                            <?php
                            if ($delivery_type_list) :
                                foreach ($delivery_type_list as $dt_obj) :
                                    ?>
                                    <tr>
                                        <td class="field"><?= $dt_id = $dt_obj->getDeliveryTypeId() ?>
                                            - <?= $dt_obj->getName() ?></td>
                                        <td class="value"><input
                                                name="del_opt[<?= $cur_lang_id ?>][<?= $dt_id ?>][<?= ($exists = isset($del_opt_list[$cur_lang_id][$dt_id])) ? $del_opt_list[$cur_lang_id][$dt_id]->getId() : "new" ?>]"
                                                class="input"
                                                value="<?= $exists ? htmlspecialchars($del_opt_list[$cur_lang_id][$dt_id]->getDisplayName()) : "" ?>">
                                        </td>
                                    </tr>
                                <?php
                                endforeach;
                            endif;
                            ?>
                        </table>
                    </div>
                <?php
                endforeach;
                ?>
            </div>
        <?php
        endif;
        ?>
        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_list">
            <tr>
                <td align="right" style="padding-right:8px;" height="40" class="tb_detail">
                    <input type="submit" value="<?= $lang['update'] ?>">
                </td>
            </tr>
        </table>
        <?= $set_form_ru ?>
        <input type="hidden" name="posted" value="1">
    </form>
    <script>
        var scrollerMenu = new Ext.ux.TabScrollerMenu({
            maxText: 64,
            pageSize: 1
        });

        var name_tabs = new Ext.TabPanel({
            applyTo: 'div_tabs',
            autoTabs: true,
            activeTab: 0,
            deferredRender: false,
            border: true,
            enableTabScroll: true,
            autoHeight: true,
            defaults: {autoScroll: true},
            plugins: [scrollerMenu]
        });
    </script>
    <?= $notice["js"] ?>
</div>
</body>
</html>