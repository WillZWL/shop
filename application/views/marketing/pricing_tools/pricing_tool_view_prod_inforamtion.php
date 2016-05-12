<table cellpadding="0" cellspacing="0" border="0" width="100%" class="tb_main">
    <tr>
        <td width="20%" class="field"><?= $lang["prodname"] ?></td>
        <td width="30%" class="value"><?= $prod_obj->getName() ?></td>
        <td width="20%" class="field"><?= $lang["sku"] ?></td>
        <td width="30%" class="value"><?= $prod_obj->getSku() ?></td>
    </tr>
    <tr>
        <td width="20%" class="field"><?= $lang["status"] ?></td>
        <td width="30%" class="value">
            <select name="status" style="width:130px;" onChange="lockqty(this.value)">
            <?php foreach ($ar_ws_status as $key => $value) : ?>
                    <option value="<?= $key ?>" <?= ($prod_obj->getWebsiteStatus() == $key ? "SELECTED" : "") ?>><?= $value ?></option>
            <?php endforeach; ?>
            </select>
        </td>
        <td width="20%" class="field"><?= $lang["webqty"] ?></td>
        <td width="30%" class="value">
            <input type="text" name="webqty" value="<?= $prod_obj->getWebsiteQuantity() ?>" onkeyup="value=value.replace(/[^\d.]/g,'')">
             <?=$lang['auto_restock']?>&nbsp;&nbsp;
            <select name="auto_restock">
                <option value='0' <?=($prod_obj->getAutoRestock() == 0?"SELECTED":"")?>>OFF</option>
                <option value='1' <?=($prod_obj->getAutoRestock() == 1?"SELECTED":"")?>>ON</option>
            </select>
        </td>
    </tr>
    <tr>
        <td width="20%" class="field"><?= $lang["current_supplier"] ?></td>
        <td width="30%" class="value"><?= $supplier["name"] ?></td>
        <td width="20%" class="field"><?= $lang["freight_cat"] ?></td>
        <td width="30%" class="value"><?= $freight_cat ?></td>
    </tr>
    <tr>
        <td width="20%" class="field"><?= $lang["supplier_status"] ?></td>
        <td width="30%" class="value"><?= $ar_src_status[$supplier['supplier_status']] ?></td>
        <td width="20%" class="field">Surplus Quantity</td>
        <td width="30%" class="value"><?= $prod_obj->getSurplusQuantity() ?><?= $prod_obj->getSlowMove7Days() == 'Y' ? "  (is slow moving last 7 days)" : "" ?></td>
    </tr>

    <?php
        if (count($inv)) :
            $ttl_inv = $ttl_git = 0;
            $inventory .= "<table border='0' cellpadding='0' cellspacing='0' width='100%'>";
            $inventory .= "<tr><td>" . $lang["warehouse"] . "</td><td>" . $lang["inventory"] . "</td><td>" . $lang["git"] . "</td></tr>";
            foreach ($inv as $iobj) :

                $inventory .= "<tr><td>" . $iobj->getWarehouseId() . "</td><td>" . $iobj->getInventory() . "</td><td>" . $iobj->getGit() . "</td></tr>";
                $ttl_inv += $iobj->getInventory();
                $ttl_git += $iobj->getGit();
            endforeach;
            $inventory .= "<tr><td>" . $lang["total"] . "</td><td>" . $ttl_inv . "</td><td>" . $ttl_git . "</td></tr>";
            $inventory .= "</table>";
        else :
            $inventory = 0;
        endif;
    ?>
    <tr>
        <td width="20%" class="field"><?= $lang["inventory"] ?></td>
        <td width="30%" class="value"><?= $inventory ?></td>
        <td width="20%" class="field"><?= $lang["qty_in_orders"] ?></td>
        <td width="30%" class="value">
        <?php
            $arr_tmp = "";
            foreach ($qty_in_orders as $key => $val) :
                $arr_tmp .= $lang["last"] . " " . $key . " " . $lang["days"] . " : " . $val . "<br>";
            endforeach;
            echo ereg_replace("<br>$", "", $arr_tmp);
        ?>
        </td>
    </tr>
    <tr>
        <td width="20%" class="field"><?= $lang["on_clearance"] ?></td>
        <td width="30%" class="value">
            <select name="clearance" class="input">
                <option value="0" <?= !$prod_obj->getClearance() ? "SELECTED" : "" ?>><?= $lang["no"] ?></option>
                <option value="1" <?= $prod_obj->getClearance() ? "SELECTED" : "" ?>><?= $lang["yes"] ?></option>
            </select>
        </td>
        <td width="20%" class="field"><?= $lang["hitcount"] ?></td>
        <td width="30%" class="value"><?= $hitcount ?></td>
    </tr>
    <tr>
        <td width="20%" class="field" valign="top"
            style="padding-top:2px; padding-bottom:2px;"><?= $lang["marketing_notes"] ?></td>
        <td width="30%" id="td_m_note" class="value" valign="top" style="padding-top:2px; padding-bottom:2px;">
            <?php
                if (!empty($mkt_note_obj)) :
                    foreach ($mkt_note_obj as $nobj) : ?>
                        <div><?= $nobj->getNote() ?><br>
                            <span style="font-size:9px; color:#888888; font-style:italic;">
                                <?= $lang["create_by"] . $nobj->getUsername() . $lang["on"] . $nobj->getCreateOn() ?>
                            </span>
                        </div>
                    <?php
                    endforeach;
                else :
                    echo "<div>" . $lang["no_notes"] . "</div>";
                endif;
            ?>
            <span id="m_create_note"><?= $lang["create_note"] ?></span><br/>
            <span><input name="m_note" maxlength="255" type="text"></span>
        </td>
        <td width="20%" class="field" valign="top"
            style="padding-top:2px; padding-bottom:2px;"><?= $lang["sourcing_notes"] ?></td>
        <td width="30%" id="td_s_note" class="value" valign="top" style="padding-top:2px; padding-bottom:2px;">
            <?php
                if (!empty($src_note_obj)) :
                    foreach ($src_note_obj as $nobj) :
                    ?>
                        <div><?= $nobj->getNote() ?><br>
                            <span style="font-size:9px; color:#888888; font-style:italic;">
                                <?= $lang["create_by"] . $nobj->getUsername() . $lang["on"] . $nobj->getCreateOn() ?>
                            </span>
                        </div>
                    <?php
                    endforeach;
                else :
                    echo "<div>" . $lang["no_notes"] . "</div>";
                endif;
            ?>
            <span id="s_create_note"><?= $lang["create_note"] ?></span><br/>
            <span><input name="s_note" maxlength="255" type="text"></span>
        </td>
    </tr>
    <tr>
        <td width="20%" class="field" valign="top" style="padding-top:2px; padding-bottom:2px;"><?= $lang["max_order_qty"] ?></td>
        <td width="30%" class="value" valign="top" style="padding-top:2px; padding-bottom:2px;">
            <input type="text" name="max_order_qty" value="<?= ($price_obj->getMaxOrderQty() == "" ? "100" : $price_obj->getMaxOrderQty()) ?>" onkeyup="value=value.replace(/[^\d.]/g,'')">
        </td>
    <?php if ($platform_type == "WEBSITE") : ?>
        <td width="20%" class="field"><?= $lang["mapping_code"] ?></td>
        <td width="30%" class="value"><input type="text" name="ext_mapping_code" value="<?= $price_obj->getExtMappingCode() ?>" style="width:130px;"></td>
    <?php else : ?>
        <td width="20%" class="field"></td>
        <td width="30%" class="value"></td>
    <?php endif; ?>
    </tr>
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
    <?php if ( in_array($platform_type, array('FNAC', 'QOO10', 'EBAY')) ) : ?>
        <tr>
            <td width="20%" class="field"><?= $lang["ean"] ?></td>
            <td width="30%" class="value">
                <input type="text" name="ean" value="<?= $prod_obj->getEan() ?>">
                <input type="hidden" name="chk" value="<?= $platform_type ?>">
            </td>
            <td width="20%" class="field"><?= $lang["mapping_code"] ?></td>
            <td width="30%" class="value">
                <input type="text" name="ext_mapping_code" value="<?= $price_obj->getExtMappingCode() ?>" style="width:130px;">
            </td>
        </tr>
        <tr>
            <td width="20%" class="field"><?= $lang["mpn"] ?></td>
            <td width="30%" class="value">
                <input type="text" name="mpn" value="<?= $prod_obj->getMpn() ?>">
            </td>
            <td width="20%" class="field"><?= $lang["upc"] ?></td>
            <td width="30%" class="value">
                <input type="text" name="upc" value="<?= $prod_obj->getUpc() ?>" style="width:130px;">
            </td>
        </tr>
    <?php endif;?>
</table>