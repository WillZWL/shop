<?php
    $price_ext_obj = $price_ext[$platform];
?>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tb_main">
    <tr>
        <td width="20%" class="field"><?= $lang["ebay_title"] ?></td>
        <td colspan="3" class="value">
            <input name="price_ext[<?= $platform ?>][title]" value="<?= htmlspecialchars($price_ext_obj->getTitle()) ?>" class="input" maxlength="80">
        </td>
    </tr>
    <tr>
        <td width="20%" class="field"><?= $lang["category1"] ?></td>
        <td width="30%" class="value">
            <input name="price_ext[<?= $platform ?>][ext_ref_1]" value="<?= htmlspecialchars($price_ext_obj->getExtRef1()) ?>">
        </td>
        <td width="20%" class="field"><?= $lang["category2"] ?></td>
        <td width="30%" class="value">
            <input name="price_ext[<?= $platform ?>][ext_ref_2]" value="<?= htmlspecialchars($price_ext_obj->getExtRef2()) ?>">
        </td>
    </tr>
    <tr>
        <td class="field"><?= $lang["store_category1"] ?></td>
        <td class="value">
            <select name="price_ext[<?= $platform ?>][ext_ref_3]">
                <option></option>
                <?php
                if ($store_cat_list[$platform]) :
                    foreach ($store_cat_list[$platform] as $store_cat) :
                    ?>
                        <option value="<?= $store_cat->getExtId() ?>" <?= ($price_ext_obj->getExtRef3() == $store_cat->getExtId() ? "SELECTED" : "") ?>><?= $store_cat->getExtName() ?></option>
                    <?php
                    endforeach;
                endif;
                ?>
        </td>
        <td class="field"><?= $lang["store_category2"] ?></td>
        <td class="value">
            <select name="price_ext[<?= $platform ?>][ext_ref_4]">
                <option></option>
                <?php
                if ($store_cat_2_list[$platform]) :
                    foreach ($store_cat_2_list[$platform] as $store_cat_2) :
                    ?>
                        <option value="<?= $store_cat_2->getExtId() ?>" <?= ($price_ext_obj->getExtRef4() == $store_cat_2->getExtId() ? "SELECTED" : "") ?>><?= $store_cat_2->getExtName() ?></option>
                    <?php
                    endforeach;
                endif;
                ?>
        </td>
    </tr>
    <tr>
        <td class="field"><?= $lang["listing_qty"] ?></td>
        <td class="value">
            <input name="price_ext[<?= $platform ?>][ext_qty]" value="<?= htmlspecialchars($price_ext_obj->getExtQty()) ?>"  onkeyup="value=value.replace(/[^\d.]/g,'')">
            <select name="price_ext[<?= $platform ?>][handling_time]" style="width:130px;">
            <?php
                  $business_day_array = array(0, 1, 2, 3, 4, 5, 10, 15, 20);
                  foreach ($business_day_array as $b) : ?>
                      <option value="<?= $b ?>" <?php if ($price_ext_obj->getHandlingTime() == $b) echo "selected"?>><?= $b . " " . $lang["business_day"] ?></option>
            <?php endforeach; ?>
            </select>
        </td>
        <td class="field"><?= $lang["listing_status"] ?></td>
        <td class="value">
            <select name="listing_status[<?= $platform ?>]" style="width:130px;">
                <option value="N"><?= $lang["not_listed"] ?></option>
                <option value="L" <?= ($price_obj->getListingStatus() == "L" ? "SELECTED" : "") ?>><?= $lang["listed"] ?></option>
            </select>
            <input type="hidden" name="selling_platform[<?= $platform ?>]" value="<?= $platform ?>">
            <input type="hidden" name="formtype[<?= $platform ?>]" value="<?= $formtype[$platform] ?>">
        </td>
    </tr>
    <?php if ($price_ext_obj->getExtItemId()) : ?>
        <tr>
            <td class="field"><?= $lang["ebay_item_id"] ?></td>
            <td class="value"><?= $price_ext_obj->getExtItemId() ?></td>
            <td class="field"><?= $lang["action"] ?></td>
            <td class="value">
                <select name="action[<?= $platform ?>]" style="width:130px;"
                        onChange="if(this.value == 'E') { document.getElementById('end_reason[<?= $platform ?>]').style.display = '' } else { document.getElementById('end_reason[<?= $platform ?>]').style.display = 'none' }">
                    <option></option>
                    <option value="R"><?= $lang["re-additem"] ?></option>
                    <option value="RE"><?= $lang["revise"] ?></option>
                    <option value="E"><?= $lang["enditem"] ?></option>
                </select>
                <select id="end_reason[<?= $platform ?>]" name="reason[<?= $platform ?>]" style="width:130px;display:none;">
                    <option value="NotAvailable">NotAvailable</option>
                    <option value="Incorrect">Incorrect</option>
                    <option value="LostOrBroken">LostOrBroken</option>
                    <option value="OtherListingError">OtherListingError</option>
                    <option value="SellToHighBidder">SellToHighBidder</option>
                    <option value="Sold">Sold</option>
                </select>
            </td>
        </tr>
    <?php endif; ?>
    <tr>
        <td width="20%" class="field"><?= $lang["create_on"] ?></td>
        <td width="30%" class="value"><?= $price_obj->getCreateOn() ?></td>
        <td width="20%" class="field"><?= $lang["modify_on"] ?></td>
        <td width="30%" class="value"><?= $price_obj->getModifyOn() ?></td>
    </tr>
    <tr>
        <td width="20%" class="field"><?= $lang["create_at"] ?></td>
        <td width="30%" class="value"><?= $price_obj->getCreateAt() ?></td>
        <td width="20%" class="field"><?= $lang["modify_at"] ?></td>
        <td width="30%" class="value"><?= $price_obj->getModifyAt() ?></td>
    </tr>
    <tr>
        <td width="20%" class="field"><?= $lang["create_by"] ?></td>
        <td width="30%" class="value"><?= $price_obj->getCreateBy() ?></td>
        <td width="20%" class="field"><?= $lang["modify_by"] ?></td>
        <td width="30%" class="value"><?= $price_obj->getModifyBy() ?></td>
    </tr>
</table>