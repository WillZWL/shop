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
                <!-- <input type="button" value="<?= $lang["content_management"] ?>" style="padding:0px 4px 0px 4px;" onclick="Redirect('<?= site_url('mastercfg/delivery') ?>')"> -->
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
        <tr>
            <td style="padding-left:8px; padding-bottom:10px">
                <table width="30%" border="1" style="border:1px solid gray;border-collapse:collapse">
                    <col width="20%">
                    <col width="30%">
                    <tr>
                        <!-- using count so will be flexible if have more scenarios in future -->
                        <td rowspan="<?= count((array)$scenario_list) + 1 ?>" style="text-align:center;">Legend:</td>
                        <td style="text-align:right;padding-right:8px"><b><?= $lang["scenario"] ?></b></td>
                        <td style="padding-left:8px"><b><?= $lang["occurrence"] ?></b></td>
                    </tr>
                    <?php
                    if ($scenario_list) {
                        // display Legend
                        foreach ($scenario_list as $scenarioobj) {
                            $scenarioname = $scenarioobj->name;
                            $scenariodesc = $scenarioobj->description;
                            ?>
                            <tr>
                                <td style="text-align:right;padding-right:8px"><?= $scenarioname ?></td>
                                <td style="padding-left:8px"><?= $scenariodesc ?></td>
                            </tr>
                        <?php
                        }
                    }
                    ?>
                </table>
            </td>
        </tr>
    </table>
    <form name="fm_edit" method="post" onSubmit="return CheckForm(this)">
        <table border="0" cellpadding="0" cellspacing="0" height="70" class="tb_main" width="100%">
            <col width="3%">
            <col width="10%">
            <col width="20%">
            <col width="20%">
            <?php if ($country_list) :
                foreach ($country_list as $ctry_obj) :
                    $ctry_id = $ctry_obj->getCountryId();
                    $ctry_name = $ctry_obj->getName();
                    ?>
                    <tr class="header">
                        <td colspan="5"><?= "$ctry_id - $ctry_name" ?></td>
                    </tr>
                    <tr class="header2">
                        <td>&nbsp;</td>
                        <td><?= $lang["scenario"] ?></td>
                        <td><?= $lang["ship_days"] ?></td>
                        <td><?= $lang["delivery_days"] ?></td>
                        <td>&nbsp;</td>
                    </tr>
                    <?php
                    if ($scenario_list) :
                        foreach ($scenario_list as $scenarioobj) :
                            $scenarioid = $scenarioobj->id;
                            $scenarioname = $scenarioobj->name;
                            $info = [];

                            if (empty($del_list_by_country[$ctry_id])) :
                                continue;
                            endif;
                            if ($del_list = $del_list_by_country[$ctry_id]) :
                                foreach ($del_list as $delobj) :
                                    $del_scenarioid = $delobj->getScenarioid();
                                    $info[$del_scenarioid]["ship_min_day"] = $delobj->getShipMinDay();
                                    $info[$del_scenarioid]["ship_max_day"] = $delobj->getShipMaxDay();
                                    $info[$del_scenarioid]["del_min_day"] = $delobj->getDelMinDay();
                                    $info[$del_scenarioid]["del_max_day"] = $delobj->getDelMaxDay();
                                    $info[$del_scenarioid]["margin"] = $delobj->getMargin();
                                    $info[$del_scenarioid]["create_oarrayn"] = $delobj->getCreateOn();
                                    $info[$del_scenarioid]["create_at"] = $delobj->getCreateAt();
                                    $info[$del_scenarioid]["create_by"] = $delobj->getCreateBy();
                                    $info[$del_scenarioid]["modify_on"] = $delobj->getModifyOn();
                                    $info[$del_scenarioid]["modify_at"] = $delobj->getModifyAt();
                                    $info[$del_scenarioid]["modify_by"] = $delobj->getModifyBy();
                                endforeach;
                            endif;

                            if (!empty($postdata) && $postobj = $postdata[$ctry_id]) :
                                if ($postobj[$scenarioid]["ship_min_day"] !== $info[$scenarioid]["ship_min_day"]) :
                                    $info[$scenarioid]["ship_min_day"] = $postobj[$scenarioid]["ship_min_day"];
                                endif;

                                if ($postobj[$scenarioid]["ship_max_day"] !== $info[$scenarioid]["ship_max_day"]) :
                                    $info[$scenarioid]["ship_max_day"] = $postobj[$scenarioid]["ship_max_day"];
                                endif;

                                if ($postobj[$scenarioid]["del_min_day"] !== $info[$scenarioid]["del_min_day"]) :
                                    $info[$scenarioid]["del_min_day"] = $postobj[$scenarioid]["del_min_day"];
                                endif;

                                if ($postobj[$scenarioid]["del_max_day"] !== $info[$scenarioid]["del_max_day"]) :
                                    $info[$scenarioid]["del_max_day"] = $postobj[$scenarioid]["del_max_day"];
                                endif;

                                if (!empty($postobj[$scenarioid]["margin"]) && $info[$scenarioid]["margin"] && $postobj[$scenarioid]["margin"] !== $info[$scenarioid]["margin"]) :
                                    $info[$scenarioid]["margin"] = $postobj[$scenarioid]["margin"];
                                endif;
                            endif;
                            ?>
                            <tr>
                                <td class="field" style="cursor:pointer;"><img src="<?= base_url() ?>images/info.gif"
                                                                               title='<?= $lang["create_on"] ?>:<?= $info[$scenarioid]["create_on"] ?>&#13;<?= $lang["create_at"] ?>:<?= $info[$scenarioid]["create_at"] ?>&#13;<?= $lang["create_by"] ?>:<?= $info[$scenarioid]["create_by"] ?>&#13;<?= $lang["modify_on"] ?>:<?= $info[$scenarioid]["modify_on"] ?>&#13;<?= $lang["modify_at"] ?>:<?= $info[$scenarioid]["modify_at"] ?>&#13;<?= $lang["modify_by"] ?>:<?= $info[$scenarioid]["modify_by"] ?>'>
                                </td>
                                <td class="field"><b><?= $scenarioname ?></b></td>
                                <td class="field">
                                    <input size="5" type="text"
                                           value="<?= $info[$scenarioid]["ship_min_day"] !== "" ? $info[$scenarioid]["ship_min_day"] : "" ?>"
                                           name="postdata[<?= $ctry_id ?>][<?= $scenarioid ?>][ship_min_day]"
                                           id="postdata[<?= $ctry_id ?>][<?= $scenarioid ?>][ship_min_day]" isNumber>
                                    &nbsp;&nbsp; - &nbsp;&nbsp;
                                    <input size="5" type="text"
                                           value="<?= $info[$scenarioid]["ship_max_day"] !== "" ? $info[$scenarioid]["ship_max_day"] : "" ?>"
                                           name="postdata[<?= $ctry_id ?>][<?= $scenarioid ?>][ship_max_day]"
                                           id="postdata[<?= $ctry_id ?>][<?= $scenarioid ?>][ship_max_day]" isNumber>
                                </td>
                                <td class="field">
                                    <input size="5" type="text"
                                           value="<?= $info[$scenarioid]["del_min_day"] !== "" ? $info[$scenarioid]["del_min_day"] : "" ?>"
                                           name="postdata[<?= $ctry_id ?>][<?= $scenarioid ?>][del_min_day]"
                                           id="postdata[<?= $ctry_id ?>][<?= $scenarioid ?>][del_min_day]" isNumber>
                                    &nbsp;&nbsp; - &nbsp;&nbsp;
                                    <input size="5" type="text"
                                           value="<?= $info[$scenarioid]["del_max_day"] !== "" ? $info[$scenarioid]["del_max_day"] : "" ?>"
                                           name="postdata[<?= $ctry_id ?>][<?= $scenarioid ?>][del_max_day]"
                                           id="postdata[<?= $ctry_id ?>][<?= $scenarioid ?>][del_max_day]" isNumber>
                                </td>
                                <?php
                                if ($scenarioid == 5) :
                                    ?>
                                    <td class="field">
                                        Product margin >= &nbsp;&nbsp;
                                        <input size="5" type="text"
                                               value="<?= $info[$scenarioid]["margin"] !== "" ? $info[$scenarioid]["margin"] : "" ?>"
                                               name="postdata[<?= $ctry_id ?>][<?= $scenarioid ?>][margin]"
                                               id="postdata[<?= $ctry_id ?>][<?= $scenarioid ?>][margin]" isNumber>
                                        %
                                    </td>
                                <?php
                                else :
                                    ?>
                                    <td class="field">&nbsp;</td>
                                <?php
                                endif;
                                ?>
                            </tr>
                        <?php
                        endforeach;
                    endif;
                endforeach;
            endif;
            ?>
        </table>
        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_list">
            <tr>
                <td align="right" style="padding-right:8px;" height="40" class="tb_detail">
                    <input type="submit" value="<?= $lang['update'] ?>">
                </td>
            </tr>
        </table>

        <input type="hidden" name="posted" value="1">
    </form>
    <script>

    </script>
    <?= $notice["js"] ?>
</div>
</body>
</html>