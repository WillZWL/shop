<html>
<head>
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="/js/common.js"></script>
    <script type="text/javascript" src="/js/checkform.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>marketing/product/js_catlist"></script>
    <script type="text/javascript" src="<?= base_url() ?>mastercfg/brand/js_brandlist"></script>
    <!--<script type="text/javascript" src="<?= base_url() ?>mastercfg/region/js_courier_region"></script>-->
    <script type="text/javascript" src="<?= base_url() ?>supply/supplier_helper/js_currency"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/calendar.js"></script>
    <link rel="stylesheet" href="<?= base_url() ?>css/calendar.css" type="text/css" media="all"/>
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>js/ext-js/resources/css/ext-all.css"/>
    <script type="text/javascript" src="<?= base_url() ?>js/ext-js/adapter/ext/ext-base.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/ext-js/ext-all.js"></script>
</head>
<body>
<div id="main">
    <?= $notice["img"] ?>
    <?php
    $ar_status = array($lang["inactive"], $lang["active"]);
    ?>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td height="30" class="title"><?= $lang["title"] ?></td>
            <td width="400" align="right" class="title">
                <input type="button" value="<?= $lang["list_button"] ?>" class="button"
                       onclick="Redirect('<?= site_url('marketing/promotion_code/') ?>')">
                &nbsp; <input type="button" value="<?= $lang["add_button"] ?>" class="button"
                              onclick="Redirect('<?= site_url('marketing/promotion_code/add/') ?>')">
            </td>
        </tr>
        <tr>
            <td height="2" class="line"></td>
            <td height="2" class="line"></td>
        </tr>
    </table>
    <table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
        <tr>
            <td height="70" style="padding-left:8px;">
                <b><?= $lang["header"] ?>
                    <?php
                    if ($cmd != "add")
                    {
                    ?>
                    - <?= $promotion_code->get_code() ?></b><br><?= $lang["no_taken"] ?>
                : <?= $promotion_code->get_no_taken() ?>
                <?php
                }
                else {
                    ?>
                </b>
                <?php
                }
                ?>
            </td>
        </tr>
    </table>
    <form name="fm" method="post" onSubmit="return CheckForm(this);" enctype="multipart/form-data">
        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_main">
            <col width="200">
            <col width="410">
            <col width="200">
            <col>
            <tr class="header">
                <td height="20" colspan="4"><?= $lang["table_header"] ?></td>
            </tr>
            <tr>
                <td class="field"><?= $lang["code"] ?></td>
                <?php
                if ($cmd == "add")
                {
                ?>
                <td class="value">
                    <input name="prefix" size="13" maxLength="20"
                           value="<?= htmlspecialchars($this->input->post("prefix")) ?>" notEmpty> <font
                        color="blue">(<?= $lang["prefix_desc"] ?>)</font>
                    <?php
                    }
                    else
                    {
                    ?>
                <td class="value">
                    <b><?= $promotion_code->get_code() ?></b>
                    <?php
                    }
                    ?>
                </td>
                <td class="field"><?= $lang["expire_date"] ?> (<?= $lang["if_applicable"] ?>)</td>
                <td class="value"><input name="expire_date" size="10" maxLength="10"
                                         value="<?= htmlspecialchars($promotion_code->get_expire_date()) ?>"><img
                        src="/images/cal_icon.gif" class="pointer"
                        onclick="showcalendar(event, document.fm.expire_date, false, false, false, '2010-01-01')"
                        align="absmiddle"> (YYYY-MM-DD)
                </td>
            </tr>
            <tr>
                <td class="field"><?= $lang["description"] ?></td>
                <td class="value" colspan="3"><input name="description" class="input"
                                                     value="<?= htmlspecialchars($promotion_code->get_description()) ?>"
                                                     notEmpty></td>
            </tr>
            <tr>
                <td class="field"><?= $lang["disc_type"] ?></td>
                <td class="value" colspan="3">
                    <div id="div_tabs" style="padding:2px;">
                    </div>
                    <div id="div_content_1">
                        <table width="650" border="0" cellpadding="0" cellspacing="0" class="tb_main">
                            <tr>
                                <td style="line-height:25px;">
                                    <span
                                        style="display:inline-block;width:60px;text-align:right"><?= $lang["discount"] ?></span>
                                    &nbsp; <input name="discount_1"
                                                  value="<?= htmlspecialchars($promotion_code->get_discount_1()) ?>"
                                                  class="int_input"> &nbsp; <input name="discount_2"
                                                                                   value="<?= htmlspecialchars($promotion_code->get_discount_2()) ?>"
                                                                                   class="int_input"> &nbsp; <input
                                        name="discount_3"
                                        value="<?= htmlspecialchars($promotion_code->get_discount_3()) ?>"
                                        class="int_input"> &nbsp; <input name="discount_4"
                                                                         value="<?= htmlspecialchars($promotion_code->get_discount_4()) ?>"
                                                                         class="int_input"> &nbsp; <input
                                        name="discount_5"
                                        value="<?= htmlspecialchars($promotion_code->get_discount_5()) ?>"
                                        class="int_input"><br>
                                    <span
                                        style="display:inline-block;width:60px;text-align:right"><?= $lang["total_over"] ?></span>
                                    &nbsp; <input name="over_amount_1"
                                                  value="<?= htmlspecialchars($promotion_code->get_over_amount_1()) ?>"
                                                  class="int_input"> &nbsp; <input name="over_amount_2"
                                                                                   value="<?= htmlspecialchars($promotion_code->get_over_amount_2()) ?>"
                                                                                   class="int_input"> &nbsp; <input
                                        name="over_amount_3"
                                        value="<?= htmlspecialchars($promotion_code->get_over_amount_3()) ?>"
                                        class="int_input"> &nbsp; <input name="over_amount_4"
                                                                         value="<?= htmlspecialchars($promotion_code->get_over_amount_4()) ?>"
                                                                         class="int_input"> &nbsp; <input
                                        name="over_amount_5"
                                        value="<?= htmlspecialchars($promotion_code->get_over_amount_5()) ?>"
                                        class="int_input">

                                    <?php
                                    $disc_level = $promotion_code->get_disc_level();
                                    $dl_checked[$disc_level] = " CHECKED";
                                    $ar_disc_level_value = ($promotion_code->get_disc_type() == "FD") ? array() : @explode(",", $promotion_code->get_disc_level_value());
                                    ?>

                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_main">
                                        <col width="150">
                                        <col>
                                        <tr class="header">
                                            <td height="20" colspan="2"><?= $lang["discount_level"] ?></td>
                                        </tr>
                                        <tr>
                                            <td><input type="radio" name="disc_level"
                                                       value="ALL"<?= $dl_checked["ALL"] ?>> <?= $lang["all_purchases"] ?>
                                            </td>
                                            <td>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><input type="radio" name="disc_level"
                                                       value="CAT"<?= $dl_checked["CAT"] ?>> <?= $lang["category"] ?>
                                            </td>
                                            <td>
                                                <select name="disc_level_value[CAT]" class="input"
                                                        onChange="ChangeCat(this.value, this.form.elements['disc_level_value[SCAT]'])">
                                                    <option value="">
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><input type="radio" name="disc_level"
                                                       value="SCAT"<?= $dl_checked["SCAT"] ?>> <?= $lang["sub_cat"] ?>
                                            </td>
                                            <td>
                                                <select name="disc_level_value[SCAT]" class="input"
                                                        onChange="ChangeCat(this.value, this.form.elements['disc_level_value[SSCAT]'])">
                                                    <option value="">
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><input type="radio" name="disc_level"
                                                       value="SSCAT"<?= $dl_checked["SSCAT"] ?>> <?= $lang["sub_sub_cat"] ?>
                                            </td>
                                            <td>
                                                <select name="disc_level_value[SSCAT]" class="input">
                                                    <option value="">
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><input type="radio" name="disc_level"
                                                       value="BN"<?= $dl_checked["BN"] ?>> <?= $lang["brand"] ?></td>
                                            <td>
                                                <select name="disc_level_value[BN]" class="input">
                                                    <option value="">
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><input type="radio" name="disc_level"
                                                       value="PD"<?= $dl_checked["PD"] ?>> <?= $lang["products"] ?></td>
                                            <td>
                                                <?php
                                                if ($disc_level == "PD") {
                                                    $ar_disc_prod = $ar_disc_level_value;
                                                }
                                                for ($i = 0; $i < 30; $i++) {
                                                    $endstr = $i == 4 ? "<br>" : " &nbsp; ";
                                                    ?>
                                                    <input name="disc_level_value[PD][<?= $i ?>]" size="11"
                                                           value="<?= htmlspecialchars($ar_disc_prod[$i]) ?>"
                                                           maxLen="11"><?= $endstr ?>
                                                <?php
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    </table>

                                </td>
                            </tr>
                        </table>
                    </div>
                    <div id="div_content_2">
                        <table width="700" border="0" cellpadding="0" cellspacing="0" class="tb_main">
                            <tr id="tr_FI">
                                <td>
                                    <span
                                        style="display:inline-block;width:60px;text-align:right"><?= $lang["free_item"] ?></span>
                                    &nbsp; <input name="free_item_sku" size="8"
                                                  value="<?= htmlspecialchars($promotion_code->get_free_item_sku()) ?>"
                                                  maxLen="11">
                                </td>
                            </tr>
                            <tr>
                                <td><span
                                        style="display:inline-block;width:60px;text-align:right"><?= $lang["total_over"] ?></span>
                                    &nbsp; <input name="over_amount"
                                                  value="<?= htmlspecialchars($promotion_code->get_over_amount()) ?>"
                                                  class="int_input"></td>
                            </tr>
                            <tr>
                                <td>
                                    <span
                                        style="display:inline-block;width:80px;text-align:right"><?= $lang["delivery_option"] ?></span>
                                    &nbsp;
                                    <select name="disc_level_value[FD]">
                                        <option>All</option>
                                        <?php
                                        foreach ($delivery_option_list as $delivery_option_obj) {
                                            ?>
                                            <option
                                                value="<?= $delivery_option_obj->get_id() ?>" <?= (($promotion_code->get_disc_level_value() == $delivery_option_obj->get_id()) ? 'selected' : '') ?>><?= $delivery_option_obj->get_courier_name() ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
            <tr class="header">
                <td height="20" colspan="4"><?= $lang["cart_header"] ?></td>
            </tr>
            <tr>
                <td class="field"><?= $lang["currency"] ?></td>
                <td class="value" colspan="3">
                    <select name="currency_id">
                        <option value="">
                    </select>
                </td>
            </tr>
            <tr>
                <td class="field"><?= $lang["category"] ?></td>
                <td class="value">
                    <select name="cat_id" class="input" onChange="ChangeCat(this.value, this.form.sub_cat_id)">
                        <option value="">
                    </select>
                </td>
                <td class="field"><?= $lang["brand"] ?></td>
                <td class="value">
                    <select name="brand_id" class="input">
                        <option value="">
                    </select>
                </td>
            </tr>
            <tr>
                <td class="field"><?= $lang["sub_cat"] ?></td>
                <td class="value">
                    <select name="sub_cat_id" class="input" onChange="ChangeCat(this.value, this.form.sub_sub_cat_id)">
                        <option value="">
                    </select>
                </td>
                <td class="field"><?= $lang["country"] ?></td>
                <td class="value">
                    <select name="country_id" class="input">
                        <option value="">
                            <?php
                            if ($country_list)
                            {
                            $c_selected[$promotion_code->get_country_id()] = " SELECTED";
                            foreach ($country_list as $id => $name)
                            {
                            ?>
                        <option value="<?= $id ?>"<?= $c_selected[$id] ?>><?= $name ?>
                            <?php
                            }
                            }
                            ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="field"><?= $lang["sub_sub_cat"] ?></td>
                <td class="value">
                    <select name="sub_sub_cat_id" class="input">
                        <option value="">
                    </select>
                </td>
                <td class="field"><!--<?= $lang["region"] ?>--></td>
                <td class="value"><!--
            <select name="region_id" class="input">
                <option value="">
            </select>-->
                </td>
            </tr>
            <tr>
                <td class="field"><?= $lang["relevant_products"] ?></td>
                <td class="value" colspan="3">
                    <?php
                    $relevant_prod = explode(",", $promotion_code->get_relevant_prod());
                    for ($i = 0; $i < 30; $i++) {
                        ?>
                        <input name="relevant_prod[<?= $i ?>]" size="11"
                               value="<?= htmlspecialchars($relevant_prod[$i]) ?>" maxLen="11"> &nbsp;
                    <?php
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td class="field"><?= $lang["email"] ?></td>
                <td class="value"><input name="email" value="<?= htmlspecialchars($promotion_code->get_email()) ?>">
                    (<?= $lang["percent_matches"] ?>)
                </td>
                <td class="field"><?= $lang["status"] ?></td>
                <td class="value">
                    <?php
                    if ($cmd != "add") {
                        ?>
                        <select name="status" class="input">
                            <?php
                            $selected_s[$promotion_code->get_status()] = "SELECTED";
                            foreach ($ar_status as $rskey => $rsvalue)
                            {
                            ?>
                            <option value="<?= $rskey ?>" <?= $selected_s[$rskey] ?>><?= $rsvalue ?>
                                <?php
                                }
                                ?>
                        </select>
                    <?php
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td class="field"><?= $lang["redemption"] ?></td>
                <td class="value"><input name="redemption"
                                         value="<?= htmlspecialchars($promotion_code->get_redemption()) ?>" notEmpty>
                    (<?= $lang["ulimited"] ?>)
                </td>
                <td class="field"><?= $lang["total_redemption"] ?></td>
                <td class="value"><input name="total_redemption"
                                         value="<?= htmlspecialchars($promotion_code->get_total_redemption()) ?>"
                                         notEmpty> (<?= $lang["ulimited"] ?>)
                </td>
            </tr>
            <?php
            if ($cmd == "edit") {
                ?>
                <tr>
                    <td class="field"><?= $lang["create_on"] ?></td>
                    <td class="value"><?= $promotion_code->get_create_on() ?></td>
                    <td class="field"><?= $lang["modify_on"] ?></td>
                    <td class="value"><?= $promotion_code->get_modify_on() ?></td>
                </tr>
                <tr>
                    <td class="field"><?= $lang["create_at"] ?></td>
                    <td class="value"><?= $promotion_code->get_create_at() ?></td>
                    <td class="field"><?= $lang["modify_at"] ?></td>
                    <td class="value"><?= $promotion_code->get_modify_at() ?></td>
                </tr>
                <tr>
                    <td class="field"><?= $lang["create_by"] ?></td>
                    <td class="value"><?= $promotion_code->get_create_by() ?></td>
                    <td class="field"><?= $lang["modify_by"] ?></td>
                    <td class="value"><?= $promotion_code->get_modify_by() ?></td>
                </tr>
            <?php
            }
            ?>
            <tr>
                <td colspan="2" height="40" style="border-right:0px;" class="tb_detail"><input type="button" name="back"
                                                                                               value="<?= $lang['back_list'] ?>"
                                                                                               onClick="Redirect('<?= isset($_SESSION['LISTPAGE']) ? $_SESSION['LISTPAGE'] : base_url() . '/marketing/promotion_code' ?>')">
                </td>
                <td colspan="2" align="right" style="border-left:0px; padding-right:8px;" class="tb_detail">
                    <input type="submit" value="<?= $lang['cmd_button'] ?>">
                </td>
            </tr>
        </table>
        <input type="hidden" name="posted" value="1">
    </form>
    <script>
        InitCurr(document.fm.currency_id);
        ChangeCurr('<?=$promotion_code->get_currency_id()?>', document.fm.currency_id);
        InitBrand(document.fm.brand_id);
        InitBrand(document.fm.elements['disc_level_value[BN]']);
        document.fm.brand_id.value = '<?=$promotion_code->get_brand_id()?>';
        //InitCourierReg(document.fm.region_id);
        //ChangeCourierReg('<?=$promotion_code->get_region_id()?>', document.fm.region_id);
        ChangeCat('0', document.fm.cat_id);
        document.fm.cat_id.value = '<?=$promotion_code->get_cat_id()?>';
        ChangeCat('<?=$promotion_code->get_cat_id()?>', document.fm.sub_cat_id);
        document.fm.sub_cat_id.value = '<?=$promotion_code->get_sub_cat_id()?>';
        ChangeCat('<?=$promotion_code->get_sub_cat_id()?>', document.fm.sub_sub_cat_id);
        document.fm.sub_sub_cat_id.value = '<?=$promotion_code->get_sub_sub_cat_id()?>';

        ChangeCat('0', document.fm.elements['disc_level_value[CAT]']);
        <?php
            $ar_cat = array("CAT", "SCAT", "SSCAT");
            if (in_array($disc_level, $ar_cat))
            {
        ?>
        document.fm.elements['disc_level_value[CAT]'].value = '<?=$ar_disc_level_value[0]?>';
        ChangeCat('<?=$ar_disc_level_value[0]?>', document.fm.elements['disc_level_value[SCAT]']);
        <?php
            }
            array_shift($ar_cat);
            if (in_array($disc_level, $ar_cat))
            {
        ?>
        document.fm.elements['disc_level_value[SCAT]'].value = '<?=$ar_disc_level_value[1]?>';
        ChangeCat('<?=$ar_disc_level_value[1]?>', document.fm.elements['disc_level_value[SSCAT]']);
        <?php
            }
            array_shift($ar_cat);
            if (in_array($disc_level, $ar_cat))
            {
        ?>
        document.fm.elements['disc_level_value[SSCAT]'].value = '<?=$ar_disc_level_value[2]?>';
        <?php
            }
            elseif ($disc_level == "BN")
            {
        ?>
        document.fm.elements['disc_level_value[BN]'].value = '<?=$ar_disc_level_value[0]?>';
        <?php
            }
            $ar_actived = array("FD"=>0, "A"=>1, "P"=>2, "FI"=>3);
            if ($promotion_code->get_disc_type() == "")
            {
                $promotion_code->set_disc_type("FD");
            }
            $disc_type = $promotion_code->get_disc_type();
            $t_checked[$disc_type] = " CHECKED";
            $actived = $ar_actived[$disc_type];
        ?>
        dt_tabs = new Ext.TabPanel({
            applyTo: 'div_tabs',
            width: 650,
            activeTab: <?=$actived?>,
            plain: true,
            defaults: {autoHeight: true},
            items: [
                {
                    tabIndex: 'FD',
                    listeners: {activate: changeTab},
                    title: '<input id="disc_type_FD" type="radio" name="disc_type" value="FD"<?=$t_checked["FD"]?>> <?=$lang["free_delivery"]?>'
                },
                {
                    tabIndex: 'A',
                    listeners: {activate: changeTab},
                    title: '<input id="disc_type_A" type="radio" name="disc_type" value="A"<?=$t_checked["A"]?>> <?=$lang["amount"]?>'
                },
                {
                    tabIndex: 'P',
                    listeners: {activate: changeTab},
                    title: '<input id="disc_type_P" type="radio" name="disc_type" value="P"<?=$t_checked["P"]?>> <?=$lang["percent"]?> (%)'
                },
                {
                    tabIndex: 'FI',
                    listeners: {activate: changeTab},
                    title: '<input id="disc_type_FI" type="radio" name="disc_type" value="FI"<?=$t_checked["FI"]?>> <?=$lang["free_item"]?>'
                }
            ]
        });
        function changeTab(tab) {
            disc_type = tab.tabIndex;
            document.getElementById("disc_type_" + disc_type).checked = true;
            switch (disc_type) {
                case "A":
                case "P":
                    document.getElementById('div_content_1').style.display = '';
                    document.getElementById('div_content_2').style.display = 'none';
                    break;
                case "FD":
                    document.getElementById('div_content_1').style.display = 'none';
                    document.getElementById('div_content_2').style.display = '';
                    document.getElementById('tr_FI').style.display = 'none';
                    break;
                case "FI":
                    document.getElementById('div_content_1').style.display = 'none';
                    document.getElementById('div_content_2').style.display = '';
                    document.getElementById('tr_FI').style.display = '';
                    break;
            }
        }
    </script>
    <?= $notice["js"] ?>
</div>
</body>
</html>