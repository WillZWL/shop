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
    <?php $ar_status = [$lang["inactive"], $lang["active"]];?>
    <?php $week_day = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] ?>
    <?php
        $time_zone_arr = [
            'GMT+00:00' => 'GMT+00:00 (GMT/UTC)',
            'GMT+01:00' => 'GMT+01:00 (ECT)',
            'GMT+02:00' => 'GMT+02:00 (EET/ART)',
            'GMT+03:00' => 'GMT+03:00 (EAT)',
            'GMT+03:30' => 'GMT+03:30 (MET)',
            'GMT+04:00' => 'GMT+04:00 (NET)',
            'GMT+05:00' => 'GMT+05:00 (PLT)',
            'GMT+05:30' => 'GMT+05:30 (IST)',
            'GMT+06:00' => 'GMT+06:00 (BST)',
            'GMT+07:00' => 'GMT+07:00 (VST)',
            'GMT+08:00' => 'GMT+08:00 (HKT)',
            'GMT+09:00' => 'GMT+09:00 (JST)',
            'GMT+09:30' => 'GMT+09:30 (ACT)',
            'GMT+10:00' => 'GMT+10:00 (AET)',
            'GMT+11:00' => 'GMT+11:00 (SST)',
            'GMT+12:00' => 'GMT+12:00 (NST)',
            'GMT-11:00' => 'GMT-11:00 (MIT)',
            'GMT-10:00' => 'GMT-10:00 (HST)',
            'GMT-09:00' => 'GMT-09:00 (AST)',
            'GMT-08:00' => 'GMT-08:00 (PST)',
            'GMT-07:00' => 'GMT-07:00 (PNT/MST)',
            'GMT-06:00' => 'GMT-06:00 (CST)',
            'GMT-05:00' => 'GMT-05:00 (EST/IET)',
            'GMT-04:00' => 'GMT-04:00 (PRT)',
            'GMT-03:30' => 'GMT-03:30 (CNT/AGT/BET)',
            'GMT-03:00' => 'GMT-03:00 (BRST)',
            'GMT-02:00' => 'GMT-02:00 (MAT)',
            'GMT-01:00' => 'GMT-01:00 (CAT)',
        ];
    ?>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td height="30" class="title"><?= $lang["title"] ?></td>
            <td width="400" align="right" class="title">
                <input type="button" value="<?= $lang["list_button"] ?>" class="button" onclick="Redirect('<?= site_url('marketing/promotion_code/') ?>')">&nbsp;
                <input type="button" value="<?= $lang["add_button"] ?>" class="button" onclick="Redirect('<?= site_url('marketing/promotion_code/add/') ?>')">
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
                <b>
                <?= $lang["header"] ?>
                <?php if ($cmd != "add") : ?>
                    - <?= $promotion_code->getCode() ?></b><br><?= $lang["no_taken"] ?> : <?= $promotion_code->getNoTaken() ?>
                <?php else : ?>
                </b>
                <?php endif; ?>
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
                <?php if ($cmd == "add") :
                ?>
                <td class="value">
                    <input name="prefix" size="13" maxLength="20" value="<?= htmlspecialchars($this->input->post("prefix")) ?>" notEmpty>
                    <font color="blue">(<?= $lang["prefix_desc"] ?>)</font>
                <?php else: ?>
                <td class="value">
                    <b><?= $promotion_code->getCode() ?></b>
                <?php endif; ?>
                </td>
                <td class="field"><?= $lang["expire_date"] ?> (<?= $lang["if_applicable"] ?>)</td>
                <td class="value">
                    <input name="expire_date" size="10" maxLength="10" value="<?= htmlspecialchars($promotion_code->getExpireDate()) ?>">
                    <img src="/images/cal_icon.gif" class="pointer" onclick="showcalendar(event, document.fm.expire_date, false, false, false, '2010-01-01')" align="absmiddle"> (YYYY-MM-DD)
                </td>
            </tr>
            <tr>
                <td class="field"><?= $lang["promotion_schedule"] ?></td>
                <td class="value">
                    <?php $r_selected[$promotion_code->getPromotionSchedule()] = " SELECTED"; ?>
                    <select name="promotion_schedule"<?= $is_disabled ?>>
                        <option value="1" <?= $r_selected[1] ?>><?= $lang["Y"] ?></option>
                        <option value="0" <?= $r_selected[0] ?>><?= $lang["N"] ?></option>
                    </select>
                </td>
                <td class="field"><?= $lang["week_day"] ?></td>
                <td class="value">
                <?php
                    $weekday_count = 0;
                    $weekday = $promotion_code->getWeekDay();
                    if ($weekday) :
                        $weekday_arr = explode(',', $weekday);
                        $weekday_count = count($weekday_arr);
                        foreach ($weekday_arr as $value) :
                            $wd_checked[$value] = " checked";
                        endforeach;
                    endif;
                    $is_disabled = $weekday_count == 0 ? " disabled" : "";
                ?>
                <?php for ($i = 0; $i < count($week_day); $i++) :?>
                    <div style="float: left; margin-right:20px">
                    <span><?=$week_day[$i]?></span> <input type='checkbox'<?= $wd_checked[$week_day[$i]]?> name='week_day[]' onclick="fc_week()" value="<?= htmlspecialchars($week_day[$i]) ?>">
                    </div>
                <?php endfor; ?>
                </td>
            </tr>
            <tr>
                <td class="field"><?= $lang["start_time"] ?></td>
                <td class="value">
                    <select id="s_hh" onchange="fc_start_time()"<?= $is_disabled ?>>
                        <option value="">--</option>
                        <?php for ($i=1; $i<24; $i++): ?>
                        <option><?=sprintf ( "%02d", $i);?></option>
                        <?php endfor ?>
                    </select>
                    :
                    <select id="s_mm" onchange="fc_start_time()"<?= $is_disabled ?>>
                        <?php for ($i=0; $i<60; $i++): ?>
                        <option><?=sprintf ( "%02d", $i);?></option>
                        <?php endfor ?>
                    </select>
                    :
                    <select id="s_ss" onchange="fc_start_time()"<?= $is_disabled ?>>
                        <?php for ($i=0; $i<60; $i++): ?>
                        <option><?=sprintf ( "%02d", $i);?></option>
                        <?php endfor ?>
                    </select>
                    <?= $lang['hh_mm_ss']?>
                    <input name="start_time" type="hidden"  maxLength="8" value="<?= $promotion_code->getStartTime() ?>">
                </td>
                <td class="field"><?= $lang["time_zone"] ?></td>
                <td class="value">
                    <?php $t_selected[$promotion_code->getTimeZone()] = " SELECTED"; ?>
                    <select name="time_zone"<?= $is_disabled ?>>
                    <?php foreach ($time_zone_arr as $key => $value) : ?>
                        <option value="<?= $key ?>" <?= $t_selected[$key] ?>><?= $value ?></option>
                    <?php endforeach;?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="field"><?= $lang["end_time"] ?></td>
                <td class="value">
                    <select id="e_hh" onchange="fc_end_time()"<?= $is_disabled ?>>
                        <option value="">--</option>
                        <?php for ($i=1; $i<24; $i++): ?>
                        <option><?=sprintf ( "%02d", $i);?></option>
                        <?php endfor ?>
                    </select>
                    :
                    <select id="e_mm" onchange="fc_end_time()"<?= $is_disabled ?>>
                        <?php for ($i=0; $i<60; $i++): ?>
                        <option><?=sprintf ( "%02d", $i);?></option>
                        <?php endfor ?>
                    </select>
                    :
                    <select id="e_ss" onchange="fc_end_time()"<?= $is_disabled ?>>
                        <?php for ($i=0; $i<60; $i++): ?>
                        <option><?=sprintf ( "%02d", $i);?></option>
                        <?php endfor ?>
                    </select>
                    <?= $lang['hh_mm_ss']?>
                    <input name="end_time" type="hidden" maxLength="8" value="<?= $promotion_code->getEndTime() ?>">
                </td>
                <td class="field"><?= $lang["repeat"] ?></td>
                <td class="value">
                    <?php $r_selected[$promotion_code->getRepeat()] = " SELECTED"; ?>
                    <select name="repeat"<?= $is_disabled ?>>
                        <option value="1" <?= $r_selected[1] ?>><?= $lang["Y"] ?></option>
                        <option value="0" <?= $r_selected[0] ?>><?= $lang["N"] ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="field"><?= $lang["description"] ?></td>
                <td class="value" colspan="3">
                    <input name="description" class="input" value="<?= htmlspecialchars($promotion_code->getDescription()) ?>" notEmpty>
                </td>
            </tr>
            <tr>
                <td class="field"><?= $lang["disc_type"] ?></td>
                <td class="value" colspan="3">
                    <div id="div_tabs" style="padding:2px;"></div>
                    <div id="div_content_1">
                        <table width="650" border="0" cellpadding="0" cellspacing="0" class="tb_main">
                            <col width="150">
                            <col>
                            <tr>
                                <td>
                                    <span><?= $lang["discount"] ?></span>
                                </td>
                                <td>
                                    <input name="discount_1" value="<?= htmlspecialchars($promotion_code->getDiscount1()) ?>" class="int_input"> &nbsp;
                                    <input name="discount_2" value="<?= htmlspecialchars($promotion_code->getDiscount2()) ?>" class="int_input"> &nbsp;
                                    <input name="discount_3" value="<?= htmlspecialchars($promotion_code->getDiscount3()) ?>" class="int_input"> &nbsp;
                                    <input name="discount_4" value="<?= htmlspecialchars($promotion_code->getDiscount4()) ?>" class="int_input"> &nbsp;
                                    <input name="discount_5" value="<?= htmlspecialchars($promotion_code->getDiscount5()) ?>" class="int_input">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span><?= $lang["total_over"] ?></span>
                                </td>
                                <td>
                                    <input name="over_amount_1" value="<?= htmlspecialchars($promotion_code->getOverAmount1()) ?>" class="int_input"> &nbsp;
                                    <input name="over_amount_2" value="<?= htmlspecialchars($promotion_code->getOverAmount2()) ?>" class="int_input"> &nbsp;
                                    <input name="over_amount_3" value="<?= htmlspecialchars($promotion_code->getOverAmount3()) ?>" class="int_input"> &nbsp;
                                    <input name="over_amount_4" value="<?= htmlspecialchars($promotion_code->getOverAmount4()) ?>" class="int_input"> &nbsp;
                                    <input name="over_amount_5" value="<?= htmlspecialchars($promotion_code->getOverAmount5()) ?>" class="int_input">

                                    <?php
                                    $disc_level = $promotion_code->getDiscLevel();
                                    $dl_checked[$disc_level] = " CHECKED";
                                    $ar_disc_level_value = ($promotion_code->getDiscType() == "FD") ? array() : @explode(",", $promotion_code->getDiscLevelValue());
                                    ?>

                                    <!-- #### -->
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div id="div_content_2">
                        <table width="650" border="0" cellpadding="0" cellspacing="0" class="tb_main">
                            <col width="150">
                            <col>
                            <tr id="tr_FI">
                                <td>
                                    <span><?= $lang["free_item"] ?></span>
                                </td>
                                <td>
                                    <input name="free_item_sku" size="8" value="<?= htmlspecialchars($promotion_code->getFreeItemSku()) ?>" maxLen="11">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span><?= $lang["total_over"] ?></span>
                                </td>
                                <td>
                                    <input name="over_amount" value="<?= htmlspecialchars($promotion_code->getOverAmount()) ?>" class="int_input">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span><?= $lang["delivery_option"] ?></span>
                                </td>
                                <td>
                                    <select name="disc_level_value[FD]">
                                        <option>All</option>
                                        <?php foreach ($delivery_option_list as $delivery_option_obj) :?>
                                            <option value="<?= $delivery_option_obj->getCourierId() ?>"
                                            <?= (($promotion_code->getDiscLevelValue() == $delivery_option_obj->getCourierId()) ? 'selected' : '') ?>
                                            ><?= $delivery_option_obj->getCourierName() ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div id="div_content_3">
                        <table width="650" border="0" cellpadding="0" cellspacing="0" class="tb_main">
                            <col width="150">
                            <col>
                            <tr>
                                <td>
                                    <span><?= $lang["redemption_item"] ?></span>
                                </td>
                                <td>
                                    <?php
                                    $ar_redemption_prod_value = explode(',', $promotion_code->getRedemptionProdValue());
                                    for ($i = 0; $i < 5; $i++) :
                                        $endstr = $i == 4 ? "<br>" : " &nbsp; ";
                                    ?>
                                        <input name="redemption_prod_value[<?= $i ?>]" size="11" value="<?= htmlspecialchars($ar_redemption_prod_value[$i]) ?>" maxLen="11"><?= $endstr ?>
                                    <?php
                                    endfor;
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span><?= $lang["redemption_amount"] ?></span>
                                </td>
                                <td>
                                    <input name="redemption_amount" value="<?= htmlspecialchars($promotion_code->getRedemptionAmount()) ?>" class="int_input">
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div id="div_content_4"  style="margin-top:10px;">
                        <table border="0" cellpadding="0" cellspacing="0" width="650" class="tb_main">
                            <col width="150">
                            <col>
                            <tr class="header">
                                <td height="20" colspan="2"><?= $lang["discount_level"] ?></td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="radio" name="disc_level"value="ALL"<?= $dl_checked["ALL"] ?>> <?= $lang["all_purchases"] ?>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="radio" name="disc_level"value="CAT"<?= $dl_checked["CAT"] ?>> <?= $lang["category"] ?>
                                </td>
                                <td>
                                    <select name="disc_level_value[CAT]" class="input" onChange="ChangeCat(this.value, this.form.elements['disc_level_value[SCAT]'])">
                                        <option value="">
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="radio" name="disc_level" value="SCAT"<?= $dl_checked["SCAT"] ?>> <?= $lang["sub_cat"] ?>
                                </td>
                                <td>
                                    <select name="disc_level_value[SCAT]" class="input" onChange="ChangeCat(this.value, this.form.elements['disc_level_value[SSCAT]'])">
                                        <option value="">
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="radio" name="disc_level" value="SSCAT"<?= $dl_checked["SSCAT"] ?>> <?= $lang["sub_sub_cat"] ?>
                                </td>
                                <td>
                                    <select name="disc_level_value[SSCAT]" class="input">
                                        <option value="">
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="radio" name="disc_level" value="BN"<?= $dl_checked["BN"] ?>> <?= $lang["brand"] ?>
                                </td>
                                <td>
                                    <select name="disc_level_value[BN]" class="input">
                                        <option value="">
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="radio" name="disc_level" value="PD"<?= $dl_checked["PD"] ?>> <?= $lang["products"] ?>
                                </td>
                                <td>
                                    <?php
                                    if ($disc_level == "PD") :
                                        $ar_disc_prod = $ar_disc_level_value;
                                    endif;
                                    for ($i = 0; $i < 30; $i++) :
                                        $endstr = $i == 4 ? "<br>" : " &nbsp; ";
                                    ?>
                                        <input name="disc_level_value[PD][<?= $i ?>]" size="11" value="<?= htmlspecialchars($ar_disc_prod[$i]) ?>" maxLen="11"><?= $endstr ?>
                                    <?php
                                    endfor;
                                    ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="field"><?= $lang["promo_message"] ?></td>
                <td class="value" colspan="3">
                    <input name="promo_message" class="input" value="<?= htmlspecialchars($promotion_code->getPromoMessage()) ?>" notEmpty>
                </td>
            </tr>
            <tr>
                <td class="field"><?= $lang["display_message"] ?></td>
                <td class="value">
                    <?php $d_selected[$promotion_code->getDisplayMessage()] = " SELECTED"; ?>
                    <select name="display_message">
                        <option value="1" <?= $d_selected[1] ?>><?= $lang["Y"] ?></option>
                        <option value="0" <?= $d_selected[0] ?>><?= $lang["N"] ?></option>
                    </select>
                </td>
                <td class="field"></td>
                <td class="value">
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
                        <?php if ($country_list) :
                            $c_selected[$promotion_code->getCountryId()] = " SELECTED";
                            foreach ($country_list as $id => $name) :
                        ?>
                        <option value="<?= $id ?>"<?= $c_selected[$id] ?>><?= $name ?>
                        <?php
                            endforeach;
                        endif; ?>
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
                    $relevant_prod = explode(",", $promotion_code->getRelevantProd());
                    for ($i = 0; $i < 30; $i++) :
                    ?>
                        <input name="relevant_prod[<?= $i ?>]" size="11" value="<?= htmlspecialchars($relevant_prod[$i]) ?>" maxLen="11"> &nbsp;
                    <?php
                    endfor;
                    ?>
                </td>
            </tr>
            <tr>
                <td class="field"><?= $lang["email"] ?></td>
                <td class="value"><input name="email" value="<?= htmlspecialchars($promotion_code->getEmail()) ?>">
                    (<?= $lang["percent_matches"] ?>)
                </td>
                <td class="field"><?= $lang["status"] ?></td>
                <td class="value">
                <?php
                if ($cmd != "add") :
                ?>
                    <select name="status" class="input">
                        <?php
                        $selected_s[$promotion_code->getStatus()] = "SELECTED";
                        foreach ($ar_status as $rskey => $rsvalue) :
                        ?>
                        <option value="<?= $rskey ?>" <?= $selected_s[$rskey] ?>><?= $rsvalue ?>
                        <?php endforeach;?>
                    </select>
                <?php
                endif;
                ?>
                </td>
            </tr>
            <tr>
                <td class="field"><?= $lang["redemption_per"] ?></td>
                <td class="value">
                    <input name="redemption" value="<?= htmlspecialchars($promotion_code->getRedemption()) ?>" notEmpty>
                    (<?= $lang["ulimited"] ?>)
                </td>
                <td class="field"><?= $lang["total_redemption"] ?></td>
                <td class="value">
                    <input name="total_redemption" value="<?= htmlspecialchars($promotion_code->getTotalRedemption()) ?>" notEmpty>
                    (<?= $lang["ulimited"] ?>)
                </td>
            </tr>
            <?php if ($cmd == "edit") : ?>
                <tr>
                    <td class="field"><?= $lang["create_on"] ?></td>
                    <td class="value"><?= $promotion_code->getCreateOn() ?></td>
                    <td class="field"><?= $lang["modify_on"] ?></td>
                    <td class="value"><?= $promotion_code->getModifyOn() ?></td>
                </tr>
                <tr>
                    <td class="field"><?= $lang["create_at"] ?></td>
                    <td class="value"><?= $promotion_code->getCreateAt() ?></td>
                    <td class="field"><?= $lang["modify_at"] ?></td>
                    <td class="value"><?= $promotion_code->getModifyAt() ?></td>
                </tr>
                <tr>
                    <td class="field"><?= $lang["create_by"] ?></td>
                    <td class="value"><?= $promotion_code->getCreateBy() ?></td>
                    <td class="field"><?= $lang["modify_by"] ?></td>
                    <td class="value"><?= $promotion_code->getModifyBy() ?></td>
                </tr>
            <?php endif; ?>
            <tr>
                <td colspan="2" height="40" style="border-right:0px;" class="tb_detail">
                    <input type="button" name="back" value="<?= $lang['back_list'] ?>" onClick="Redirect('<?= isset($_SESSION['LISTPAGE']) ? $_SESSION['LISTPAGE'] : base_url() . '/marketing/promotion_code' ?>')">
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
        ChangeCurr('<?=$promotion_code->getCurrencyId()?>', document.fm.currency_id);
        InitBrand(document.fm.brand_id);
        InitBrand(document.fm.elements['disc_level_value[BN]']);
        document.fm.brand_id.value = '<?=$promotion_code->getBrandId()?>';
        //InitCourierReg(document.fm.region_id);
        //ChangeCourierReg('<?=$promotion_code->getRegionId()?>', document.fm.region_id);
        ChangeCat('0', document.fm.cat_id);
        document.fm.cat_id.value = '<?=$promotion_code->getCatId()?>';
        ChangeCat('<?=$promotion_code->getCatId()?>', document.fm.sub_cat_id);
        document.fm.sub_cat_id.value = '<?=$promotion_code->getSubCatId()?>';
        ChangeCat('<?=$promotion_code->getSubCatId()?>', document.fm.sub_sub_cat_id);
        document.fm.sub_sub_cat_id.value = '<?=$promotion_code->getSubSubCatId()?>';

        ChangeCat('0', document.fm.elements['disc_level_value[CAT]']);
        <?php
            $ar_cat = array("CAT", "SCAT", "SSCAT");
            if (in_array($disc_level, $ar_cat)) :
        ?>
        document.fm.elements['disc_level_value[CAT]'].value = '<?=$ar_disc_level_value[0]?>';
        ChangeCat('<?=$ar_disc_level_value[0]?>', document.fm.elements['disc_level_value[SCAT]']);
        <?php
            endif;
            array_shift($ar_cat);
            if (in_array($disc_level, $ar_cat)) :
        ?>
        document.fm.elements['disc_level_value[SCAT]'].value = '<?=$ar_disc_level_value[1]?>';
        ChangeCat('<?=$ar_disc_level_value[1]?>', document.fm.elements['disc_level_value[SSCAT]']);
        <?php
            endif;
            array_shift($ar_cat);
            if (in_array($disc_level, $ar_cat)) :
        ?>
        document.fm.elements['disc_level_value[SSCAT]'].value = '<?=$ar_disc_level_value[2]?>';
        <?php
            elseif ($disc_level == "BN") :
        ?>
        document.fm.elements['disc_level_value[BN]'].value = '<?=$ar_disc_level_value[0]?>';
        <?php
            endif;
            $ar_actived = array("FD"=>0, "A"=>1, "P"=>2, "FI"=>3, "R"=>4);
            if ($promotion_code->getDiscType() == "") :
                $promotion_code->setDiscType("FD");
            endif;
            $disc_type = $promotion_code->getDiscType();
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
                },
                {
                    tabIndex: 'R',
                    listeners: {activate: changeTab},
                    title: '<input id="disc_type_R" type="radio" name="disc_type" value="R"<?=$t_checked["R"]?>> <?=$lang["redemption"]?>'
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
                    document.getElementById('div_content_3').style.display = 'none';
                    break;
                case "FD":
                    document.getElementById('div_content_1').style.display = 'none';
                    document.getElementById('div_content_2').style.display = '';
                    document.getElementById('div_content_3').style.display = 'none';
                    document.getElementById('tr_FI').style.display = 'none';
                    break;
                case "FI":
                    document.getElementById('div_content_1').style.display = 'none';
                    document.getElementById('div_content_2').style.display = '';
                    document.getElementById('div_content_3').style.display = 'none';
                    document.getElementById('tr_FI').style.display = '';
                    break;
                case "R":
                    document.getElementById('div_content_1').style.display = 'none';
                    document.getElementById('div_content_2').style.display = 'none';
                    document.getElementById('div_content_3').style.display = '';
                    document.getElementById('tr_FI').style.display = 'none';
                    break;
            }
        }

        if ( start_time = document.fm.elements['start_time'].value ) {
            var strs= new Array();

            if ( strs = start_time.split(":") ) {
                var select = document.fm.elements['s_hh'];
                var next_shh = strs[0];
                for(var i=0; i<select.options.length; i++){
                    if(select.options[i].innerHTML == next_shh){
                        select.options[i].selected = true;
                        break;
                    }
                }
                var select = document.fm.elements['s_mm'];
                var next_smm =  strs[1];
                for(var i=0; i<select.options.length; i++){
                    if(select.options[i].innerHTML == next_smm){
                        select.options[i].selected = true;
                        break;
                    }
                }
                var select = document.fm.elements['s_ss'];
                var next_sss =  strs[2];
                for(var i=0; i<select.options.length; i++){
                    if(select.options[i].innerHTML == next_sss){
                        select.options[i].selected = true;
                        break;
                    }
                }
            }
        } else {
            document.fm.elements['start_time'].value = "00:00:00";
        }

        if ( end_time = document.fm.elements['end_time'].value ) {
            var strs= new Array();

            if ( strs = end_time.split(":") ) {
                var select = document.fm.elements['e_hh'];
                var next_ehh = strs[0];
                for(var i=0; i<select.options.length; i++){
                    if(select.options[i].innerHTML == next_ehh){
                        select.options[i].selected = true;
                        break;
                    }
                }
                var select = document.fm.elements['e_mm'];
                var next_emm =  strs[1];
                for(var i=0; i<select.options.length; i++){
                    if(select.options[i].innerHTML == next_emm){
                        select.options[i].selected = true;
                        break;
                    }
                }
                var select = document.fm.elements['e_ss'];
                var next_ess =  strs[2];
                for(var i=0; i<select.options.length; i++){
                    if(select.options[i].innerHTML == next_ess){
                        select.options[i].selected = true;
                        break;
                    }
                }
            }
        } else {
            document.fm.elements['end_time'].value = "00:00:00";
        }

        function fc_start_time() {
            var s_hh = document.getElementById('s_hh').value;
            s_hh = s_hh ? s_hh : "00";
            if (s_hh == "00") {
                document.fm.elements['s_mm'].options[0].selected = true;
                document.fm.elements['s_ss'].options[0].selected = true;
            }
            var s_mm = document.getElementById('s_mm').value;
            var s_ss = document.getElementById('s_ss').value;
            document.fm.elements['start_time'].value = s_hh +":"+ s_mm +":"+ s_ss;
        }

        function fc_end_time() {
            var e_hh = document.getElementById('e_hh').value;
            e_hh = e_hh ? e_hh : "00";
            if (e_hh == "00") {
                document.fm.elements['e_mm'].options[0].selected = true;
                document.fm.elements['e_ss'].options[0].selected = true;
            }
            var e_mm = document.getElementById('e_mm').value;
            var e_ss = document.getElementById('e_ss').value;
            document.fm.elements['end_time'].value = e_hh +":"+ e_mm +":"+ e_ss;
        }

        function fc_week() {
            var checks = document.getElementsByName("week_day[]");
            n = 0;
            for(i=0; i<checks.length; i++){
                if(checks[i].checked)
                    n++;
            }
            if (n) {
                document.getElementById('s_hh').disabled = false;
                document.getElementById('s_mm').disabled = false;
                document.getElementById('s_ss').disabled = false;
                document.getElementById('e_hh').disabled = false;
                document.getElementById('e_mm').disabled = false;
                document.getElementById('e_ss').disabled = false;
                document.fm.elements['time_zone'].disabled = false;
                document.fm.elements['repeat'].disabled = false;
            } else {
                document.getElementById('s_hh').disabled = true;
                document.getElementById('s_mm').disabled = true;
                document.getElementById('s_ss').disabled = true;
                document.getElementById('e_hh').disabled = true;
                document.getElementById('e_mm').disabled = true;
                document.getElementById('e_ss').disabled = true;
                document.fm.elements['time_zone'].disabled = true;
                document.fm.elements['repeat'].disabled = true;
            }
        }
    </script>
    <?= $notice["js"] ?>
</div>
</body>
</html>