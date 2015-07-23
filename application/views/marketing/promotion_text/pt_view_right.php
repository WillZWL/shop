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
<div id="main" style="width:auto">
    <?= $notice["img"] ?>
    <?php
    $ar_status = array($lang["inactive"], $lang["active"]);
    $ar_type = array($lang['skype'] => $lang['skype'], $lang['website'] => $lang['website']);
    ?>
    <table border="0" cellpadding="0" cellspacing="1" height="20" class="page_header" width="100%">
        <col width="20">
        <col width="80">
        <col width="80">
        <col width="180">
        <col width="80">
        <col>
        <?php
        foreach ($ar_type as $type)
        {
        if ($platform_type == strtolower($type) || $platform_type == 'all')
        {
        ?>
        <tr class="header">
            <td width="150" height="20" colspan="8">&nbsp;&nbsp;<?= $type ?></td>
        </tr>
        <?php

        ?>
        <tr class="add_header">
            <td></td>
            <td style="color:#F0F0F0"><?= $lang['platform_type'] ?></td>
            <td style="color:#F0F0F0"><?= $lang['lang_id'] ?></td>
            <td style="color:#F0F0F0"><?= $lang['platform_id'] ?></td>
            <td style="color:#F0F0F0"><?= $lang['sku'] ?></td>
            <td style="color:#F0F0F0"><?= $lang['promotional_text'] ?></td>
        </tr>

        <form name="fm"
              action="<?= base_url() ?>marketing/promotion_text/view_right/<?= $platform_type . "/" . $lang_id . "/" . $platform_id . "/" . $sku . "/" ?>"
              method="post" onSubmit="return CheckForm(this)">
            <?php
            $i = 0;
            foreach ($lang_list as $lang_obj) {
                if (!empty($platform_list)) {
                    ?>
                    <input type="hidden" name="plat_level" value="1">
                    <?php
                    if ($lang_obj->get_id() == $lang_id) {
                        foreach ($platform_list as $platform_obj) {
                            if ($promo_text_obj_w_platform[$type][$lang_obj->get_id()][$platform_obj->get_selling_platform_id()]) {

                                ?>
                                <tr class="row<?= $i % 2 ?> pointer" onMouseOver="AddClassName(this, 'highlight')"
                                    onMouseOut="RemoveClassName(this, 'highlight')">
                                    <td height="20"></td>
                                    <td>&nbsp;&nbsp;<?= $type ?></td>
                                    <td>
                                        &nbsp;&nbsp;<?= $promo_text_obj_w_platform[$type][$lang_obj->get_id()][$platform_obj->get_selling_platform_id()]->get_lang_id() ?></td>
                                    <td>
                                        &nbsp;&nbsp;<?= $platform_name[$promo_text_obj_w_platform[$type][$lang_obj->get_id()][$platform_obj->get_selling_platform_id()]->get_platform_id()] ?></td>
                                    <td>
                                        &nbsp;&nbsp;<?= $promo_text_obj_w_platform[$type][$lang_obj->get_id()][$platform_obj->get_selling_platform_id()]->get_sku() ?></td>
                                    <td>&nbsp;&nbsp;<input
                                            name="promo_text[<?= $promo_text_obj_w_platform[$type][$lang_id][$platform_obj->get_selling_platform_id()]->get_platform_type() ?>][<?= $promo_text_obj_w_platform[$type][$lang_obj->get_id()][$platform_obj->get_selling_platform_id()]->get_lang_id() ?>][<?= $promo_text_obj_w_platform[$type][$lang_obj->get_id()][$platform_obj->get_selling_platform_id()]->get_platform_id() ?>][<?= $promo_text_obj_w_platform[$type][$lang_obj->get_id()][$platform_obj->get_selling_platform_id()]->get_id() ?>]"
                                            class="input"
                                            value="<?= $promo_text_obj_w_platform[$type][$lang_obj->get_id()][$platform_obj->get_selling_platform_id()]->get_promo_text() ?>"
                                            notEmpty maxLen=256></td>
                                </tr>
                                <?php
                                $i++;
                            } else {
                                ?>
                                <tr class="row<?= $i % 2 ?> pointer" onMouseOver="AddClassName(this, 'highlight')"
                                    onMouseOut="RemoveClassName(this, 'highlight')">
                                    <td height="20"></td>
                                    <td>&nbsp;&nbsp;<?= $type ?></td>
                                    <td>&nbsp;&nbsp;<?= $lang_obj->get_id() ?></td>
                                    <td>&nbsp;&nbsp;<?= $platform_obj->get_platform_name() ?></td>
                                    <td>&nbsp;&nbsp;<??></td>
                                    <td>&nbsp;&nbsp;<input
                                            name="promo_text[<?= $type ?>][<?= $lang_id ?>][<?= $platform_obj->get_selling_platform_id() ?>][0]"
                                            class="input" value="" notEmpty maxLen=256></td>
                                </tr>
                                <?php
                                $i++;
                            }
                        }
                    }
                } else {
                    ?>
                    <input type="hidden" name="lang_level" value="1">
                    <?php
                    if ($promo_text_obj_w_lang[$type][$lang_obj->get_id()]) {
                        ?>
                        <tr class="row<?= $i % 2 ?> pointer" onMouseOver="AddClassName(this, 'highlight')"
                            onMouseOut="RemoveClassName(this, 'highlight')">
                            <td height="20"></td>
                            <td>&nbsp;&nbsp;<?= $type ?></td>
                            <td>
                                &nbsp;&nbsp;<?= $promo_text_obj_w_lang[$type][$lang_obj->get_id()]->get_lang_id() ?></td>
                            <td>
                                &nbsp;&nbsp;<?= $promo_text_obj_w_lang[$type][$lang_obj->get_id()]->get_platform_id() ?></td>
                            <td>&nbsp;&nbsp;<?= $promo_text_obj_w_lang[$type][$lang_obj->get_id()]->get_sku() ?></td>
                            <td>&nbsp;&nbsp;<input
                                    name="promo_text[<?= $promo_text_obj_w_lang[$type][$lang_obj->get_id()]->get_platform_type() ?>][<?= $promo_text_obj_w_lang[$type][$lang_obj->get_id()]->get_lang_id() ?>][<?= $promo_text_obj_w_lang[$type][$lang_obj->get_id()]->get_id() ?>]"
                                    class="input"
                                    value="<?= $promo_text_obj_w_lang[$type][$lang_obj->get_id()]->get_promo_text() ?>"
                                    notEmpty maxLen=256></td>
                        </tr>
                        <?php
                        $i++;
                    } else {
                        ?>
                        <tr class="row<?= $i % 2 ?> pointer" onMouseOver="AddClassName(this, 'highlight')"
                            onMouseOut="RemoveClassName(this, 'highlight')">
                            <td height="20"></td>
                            <td>&nbsp;&nbsp;<?= $type ?></td>
                            <td>&nbsp;&nbsp;<?= $lang_obj->get_id() ?></td>
                            <td>&nbsp;&nbsp;<??></td>
                            <td>&nbsp;&nbsp;<??></td>
                            <td>&nbsp;&nbsp;<input name="promo_text[<?= $type ?>][<?= $lang_obj->get_id() ?>]"
                                                   class="input" value="" notEmpty maxLen=256></td>
                        </tr>
                        <?php
                        $i++;
                    }
                }
            }
            }
            }
            ?>
            <input type="hidden" name="posted" value="1">
            <input type="hidden" name="cmd" value="update">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_list">
                <tr>
                    <td align="right" style="padding-right:8px;" height="40" class="tb_detail">
                        <input type="button" onclick="document.fm.submit()" value="<?= $lang['update'] ?>">
                        <!--                <input type="button" onclick="if(confirm('WARNING: Changes made here will immediately update Website\'s footer menu.\nConfirm update?')){document.fm.submit()}" value="<?= "Update" ?>">-->
                    </td>
                </tr>
            </table>
            <input type="hidden" name="posted" value="1">
            <input type="hidden" name="cmd" value="generate">
        </form>
    </table>
    <script language="javascript">
    </script>
    <?= $notice["js"] ?>

</body>
</html>