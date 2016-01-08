<?php
    $delivery_obj = $delivery_info[$platform_id];
    $feed_include_str = $feed_include[$platform_id];
    $feed_exclude_str = $feed_exclude[$platform_id];
?>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tb_main">
    <tr>
        <td width="20%" class="field"><?= $lang["delivery_info"] ?></td>
        <td width="30%" class="value">
            <?php
            if ($delivery_obj) :
                $delivery_html = "Scenario {$delivery_obj["scenarioname"]} (id:{$delivery_obj["scenarioid"]})
                                  <br/>Delivery time: {$delivery_obj["del_min_day"]} - {$delivery_obj["del_max_day"]} ";

                if ($delivery_obj["margin"]) :
                    $delivery_html .= "<br/>Margin >= {$delivery_obj["margin"]}";
                endif;

                echo $delivery_html;
            endif;
            ?>
        </td>
        <td width="20%" class="field"><?= $lang["listing_status"] ?></td>
        <td width="30%" class="value">
            <select name="listing_status[<?= $platform ?>]" style="width:130px;">
                <option value="N"><?= $lang["not_listed"] ?></option>
                <option value="L" <?= ($price_obj->getListingStatus() == "L" ? "SELECTED" : "") ?>><?= $lang["listed"] ?></option>
            </select>
            <input type="hidden" name="selling_platform[<?= $platform ?>]" value="<?= $platform ?>">
            <input type="hidden" name="formtype[<?= $platform ?>]" value="<?= $formtype[$platform] ?>">
        </td>
    </tr>
     <tr>

        <td width="20%" class="field"><?= $lang["advertised"] ?></td>
        <td width="30%" class="value">
            <input type="checkbox" name="is_advertised[<?= $platform ?>]"
            <?= ($price_obj->getIsAdvertised() == 'Y' ? "CHECKED" : "") ?>
            <?=(!$pdata[$platform_id]["enabled_pla_checkbox"]) ? "disabled" : "";?>>

            <?php if ($pdata[$platform_id]["gsc_comment"]) : ?>
                <div style="color:<?= $pdata[$platform_id]["gsc_comment"] == 'Success' ? '#00FF00' : '#FF0000' ?>;width:80%x; height:50px;overflow:hidden" title='<?= $pdata[$platform_id]["gsc_comment"] ?>'>
                    <?= $pdata[$platform_id]["gsc_comment"] ?>
                </div>
            <?php endif; ?>
        </td>
        <td width="20%" class="field"><!--<?= $lang["allow_express"] ?>--></td>
        <td width="30%" class="value">
            <!-- <input type="checkbox" name="allow_express[<?= $platform ?>]" <?= ($price_obj->getAllowExpress() == 'Y' ? "CHECKED" : "") ?>> -->
        </td>
    </tr>
    <tr>
        <td width="20%" class="field"><?= $lang["auto_price"] ?></td>
        <td width="30%" class="value">
            <select id="auto_price_cb[<?= $platform ?>]" name="auto_price[<?= $platform ?>]" onchange="check_sub_cat_margin('<?= $platform_type ?>','<?= $platform ?>', '<?=$prod_obj->getSku();?>')">
                <?php
                foreach ($auto_price_action as $key => $action) :
                    $selected = "";
                    if (($auto_price_status = $price_obj->getAutoPrice()) == "") :
                        $auto_price_status = "N";
                    endif;

                    if ($auto_price_status == $key) :
                        $selected = " SELECTED";
                    endif;
                    ?>
                    <option value=<?= $key ?> <?= $selected ?>><?= $action ?></option>
                <?php
                endforeach;
                ?>
            </select>
        </td>
        <td width="20%" class="field"><?= $lang["sub_cat_margin"] ?></td>
        <td width="30%" class="value"><?= $sub_cat_margin[$platform] ? $sub_cat_margin[$platform] : 'Margin not yet set' ?></td>
    </tr>
    <tr>
        <td width="20%" class="field"><?= $lang["rrp"] ?></td>
        <td width="30%" class="value">
            <div>
                <input type="radio" id="fixed_rrp[<?= $platform ?>]"
                        name="fixed_rrp[<?= $platform ?>]"
                        value="Y" <?= ($price_obj->getFixedRrp() != 'Y' ? "" : "CHECKED") ?>
                        onClick="javascript:document.getElementById('rrp_factor[<?= $platform ?>]').readOnly=true"><?= $lang["system_default_rrp"] ?>
            </div>
            <div>
                <input type="radio" id="fixed_rrp[<?= $platform ?>]"
                       name="fixed_rrp[<?= $platform ?>]"
                       value="N" <?= ($price_obj->getFixedRrp() != 'Y' ? "CHECKED" : "") ?>
                       onClick="javascript:document.getElementById('rrp_factor[<?= $platform ?>]').readOnly=false"><?= $lang["random_rrp"] ?>
                &nbsp;&nbsp;&nbsp;
                <?= (($price_obj->getFixedRrp() == 'N' && $price_obj->getRrpFactor() < 10) ? '(' . $lang["rrp"] . ' = ' . $lang["selling_price"] . ' * ' . $price_obj->getRrpFactor() . ')' : "") ?>
            </div>
            <div style="padding-left:20px">
                <input style="width:70px" type="text" id="rrp_factor[<?= $platform ?>]"
                       name="rrp_factor[<?= $platform ?>]"
                       value="<?= ($price_obj->getFixedRrp() == 'N' ? ($price_obj->getRrpFactor() >= 10 ? $price_obj->getRrpFactor() : "") : "") ?>"
                       <?= ($price_obj->getFixedRrp() != 'Y' ? "" : "readOnly") ?> isNumber>
                &nbsp;
                <?= $lang["manual_rrp_note"] ?>
            </div>
        </td>
        <td width="20%" class="field">Feeds Management</td>
        <td width="30%" class="value">
            <?= $feed_include_str ? "<font style='color:purple;'>INCLUDE: <b>$feed_include_str</b></font><br>" : "" ?>
            <?= $feed_exclude_str ? "<font style='color:purple;'>EXCLUDE: <b>$feed_exclude_str</b></font><br>" : "" ?>
            <a class="iframe cboxElement" href="/<?= $this->tool_path ?>/manage_feed/<?= $prod_obj->getSku() ?>/<?= $platform ?>">Edit</a>
            (<a class="iframe cboxElement" href="/<?= $this->tool_path ?>/manage_feed_platform">Manage feed mappings</a>)
        </td>
    </tr>
    <?php
        $adwords_obj = $pdata[$platform_id]['adwords_obj'];
        if ($adwords_obj) :
            $adwords = $lang["adwords_exists"];
             $adwords_status_str = ($adwords_obj->getStatus() == 0) ? "<span style='color:#FF0000;width:80%x; height:20px;overflow:hidden'>Paused</span>"
                                                                    : "<span style='color:#00FF00;width:80%x; height:20px;overflow:hidden'>Enabled</span>";
            if ($adwords_obj->getApiRequestResult() == 1) :
                $api_result = "<span style='color:#00FF00;width:80%x; height:20px;overflow:hidden'>Success</span>";
                $adGroup_status = $api_result . ' - ' . $adwords_status_str;
            else :
                $adGroup_status = "
                    <span style='color:#FF0000;width:80%x; height:20px;overflow:hidden'>Fail
                        <a href='javascript: void(0)' onclick=\"showHide_with_eleid('adGrouperror[{$platform_id}][{$value}]')\">[view error]</a>
                    </span>
                ";
                $adGroup_error_row = "
                    <tr>
                        <td colspan='4' class='value'>
                            <div id='adGrouperror[{$platform_id}][{$value}]' style='display:none;color:#000'><b>AdGroup Error:</b>
                                " . $adwords_obj->getComment() . "
                            </div>
                        </td>
                    </tr>
                ";
            endif;
        else :
            $adwords = "<input id='google_adwords[{$platform_id}]' type='checkbox' name='google_adwords[{$platform_id}]'>";
            $adGroup_status = "";
            $adGroup_error_row ="";
        endif;
    ?>
<!--     <tr>
        <td class="field" height="25"><?= $lang["google_adwords"] ?></td>
        <td class="value"><?= $adwords ?></td>
        <td class="field"><?= $lang["google_adwords_status"] ?></td>
        <td class="value"><?= $adGroup_status ?></td>
    </tr>
    <?= $adGroup_error_row ?> -->
</table>

<!-- competitor start -->
<?php include 'pricing_tool_view_competitor.php';?>
<!-- competitor  end -->

