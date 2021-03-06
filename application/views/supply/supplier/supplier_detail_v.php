<html>
<head>
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>supply/supplierHelper/js_currency"></script>
    <script type="text/javascript" src="<?= base_url() ?>mastercfg/regionHelper/js_sourcing_region"></script>
</head>
<body>
<div id="main">
    <?= $notice["img"] ?>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td height="30" class="title"><?= $lang["title"] ?></td>
            <td width="400" align="right" class="title"><input type="button" value="<?= $lang["list_button"] ?>"
                                                               class="button"
                                                               onclick="Redirect('<?= site_url('supply/supplier/') ?>')">
                &nbsp; <input type="button" value="<?= $lang["add_button"] ?>" class="button"
                              onclick="Redirect('<?= site_url('supply/supplier/add/') ?>')"></td>
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
    <form name="fm" method="post" onSubmit="return CheckForm(this);">
        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_main">
            <col width="200">
            <col>
            <col width="200">
            <col width="430">
            <tr class="header">
                <td height="20" colspan="4"><?= $lang["table_header"] ?></td>
            </tr>
            <tr>
                <td class="field"><?= $lang["id"] ?></td>
                <td class="value"><?= htmlspecialchars($supplier->getId()) ?></td>
                <td class="field"><?= $lang["status"] ?> <font color="red">*</font></td>
                <td class="value">
                    <select name="status" notEmpty>
                        <?php
                        if ($supplier->getStatus() != "") {
                            $selected_ar[$supplier->getStatus()] = "SELECTED";
                        } else {
                            $selected_ar[1] = "SELECTED";
                        }
                        ?>
                        <option value="0" <?= $selected_ar[0] ?>><?= $lang["inactive"] ?>
                        <option value="1" <?= $selected_ar[1] ?>><?= $lang["active"] ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="field"><?= $lang["supplier_name"] ?> <font color="red">*</font></td>
                <td class="value"><input name="name" class="input"
                                         value="<?= htmlspecialchars($supplier->getName()) ?>" notEmpty></td>
                <td class="field"><?= $lang["currency"] ?> <font color="red">*</font></td>
                <td class="value">
                    <select name="currency_id" notEmpty>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="field"><?= $lang["supplier_region"] ?> <font color="red">*</font></td>
                <td class="value">
                    <select name="supplier_reg" notEmpty>
                    </select>
                </td>
                <td class="field"><?= $lang["creditor"] ?> <font color="red">*</font></td>
                <td class="value">
                    <select name="creditor" notEmpty>
                        <?php
                        if ($supplier->getCreditor() != "") {
                            $selected_c[$supplier->getCreditor()] = "SELECTED";
                        }
                        ?>
                        <option value="0" <?= $selected_c[0] ?>><?= $lang["no"] ?>
                        <option value="1" <?= $selected_c[1] ?>><?= $lang["yes"] ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="field"><?= $lang["sourcing_region"] ?> <font color="red">*</font></td>
                <td class="value">
                    <select name="sourcing_reg" notEmpty>
                    </select>
                </td>
                <td class="field"><?= $lang["email"] ?></td>
                <td class="value"><input name="email" class="input"
                                         value="<?= htmlspecialchars($supplier->getEmail()) ?>" validEmail></td>
            </tr>
            <?php
            $select = array();
            $select[$supplier->getFcId()] = "SELECTED";
            ?>
            <tr>
                <td class="field"><?= $lang["fulfillment_centre"] ?></td>
                <td class="value" colspan="3"><select name="fc_id">
                        <?php
                        if ($fc_list) {
                            foreach ($fc_list as $fc_obj) {
                                $fc_id = $fc_obj->getId();
                                ?>
                                <option value="<?= $fc_id ?>" <?= $select[$fc_id] ?>><?= $fc_obj->getName() ?></option>
                            <?php
                            }
                        }
                        ?></select>
                </td>
            </tr>
            <tr>
                <td class="field"><?= $lang["address"] ?> 1</td>
                <td class="value" colspan="3"><input name="address1" class="input"
                                                     value="<?= htmlspecialchars($supplier->getAddress1()) ?>"></td>
            </tr>
            <tr>
                <td class="field"><?= $lang["address"] ?> 2</td>
                <td class="value" colspan="3"><input name="address2" class="input"
                                                     value="<?= htmlspecialchars($supplier->getAddress2()) ?>"></td>
            </tr>
            <tr>
                <td class="field"><?= $lang["address"] ?> 3</td>
                <td class="value" colspan="3"><input name="address3" class="input"
                                                     value="<?= htmlspecialchars($supplier->getAddress3()) ?>"></td>
            </tr>
            <tr>
                <td class="field"><?= $lang["phone"] ?> 1</td>
                <td class="value"><input name="phone1" class="input"
                                         value="<?= htmlspecialchars($supplier->getPhone1()) ?>"></td>
                <td class="field"><?= $lang["fax"] ?> 1</td>
                <td class="value"><input name="fax1" class="input"
                                         value="<?= htmlspecialchars($supplier->getFax1()) ?>"></td>
            </tr>
            <tr>
                <td class="field"><?= $lang["phone"] ?> 2</td>
                <td class="value"><input name="phone2" class="input"
                                         value="<?= htmlspecialchars($supplier->getPhone2()) ?>"></td>
                <td class="field"><?= $lang["fax"] ?> 2</td>
                <td class="value"><input name="fax2" class="input"
                                         value="<?= htmlspecialchars($supplier->getFax2()) ?>"></td>
            </tr>
            <tr>
                <td class="field"><?= $lang["phone"] ?> 3</td>
                <td class="value"><input name="phone3" class="input"
                                         value="<?= htmlspecialchars($supplier->getPhone3()) ?>"></td>
                <td class="field"><?= $lang["fax"] ?> 3</td>
                <td class="value"><input name="fax3" class="input"
                                         value="<?= htmlspecialchars($supplier->getFax3()) ?>"></td>
            </tr>
            <tr>
                <td class="field"><?= $lang["supplier_note"] ?></td>
                <td class="value" colspan="3"><textarea class="input" name="note"
                                                        rows="6"><?= htmlspecialchars($supplier->getNote()) ?></textarea>
                </td>
            </tr>
            <?php
            if ($cmd != "add") {
                ?>
                <tr>
                    <td class="field"><?= $lang["create_on"] ?></td>
                    <td class="value"><?= $supplier->getCreateOn() ?></td>
                    <td class="field"><?= $lang["modify_on"] ?></td>
                    <td class="value"><?= $supplier->getModifyOn() ?></td>
                </tr>
                <tr>
                    <td class="field"><?= $lang["create_at"] ?></td>
                    <td class="value"><?= $supplier->getCreateAt() ?></td>
                    <td class="field"><?= $lang["modify_at"] ?></td>
                    <td class="value"><?= $supplier->getModifyAt() ?></td>
                </tr>
                <tr>
                    <td class="field"><?= $lang["create_by"] ?></td>
                    <td class="value"><?= $supplier->getCreateBy() ?></td>
                    <td class="field"><?= $lang["modify_by"] ?></td>
                    <td class="value"><?= $supplier->getModifyBy() ?></td>
                </tr>
            <?php
            }
            ?>
            <tr class="tb_detail">
                <td colspan="2" height="40"><input type="button" name="back" value="<?= $lang['back_list'] ?>"
                                                   onClick="Redirect('<?= isset($_SESSION['LISTPAGE']) ? $_SESSION['LISTPAGE'] : base_url() . '/supply/supplier' ?>')">
                </td>
                <td colspan="2" align="right" style="padding-right:8px;">
                    <?php
                    if ($cmd == "add") {
                        ?>
                        <input type="submit" value="<?= $lang['header'] ?>">
                    <?php
                    } elseif ($cmd == "edit") {
                        ?>
                        <input type="submit" value="<?= $lang['update_button'] ?>">
                    <?php
                    }
                    ?>
                </td>
            </tr>
        </table>
        <input name="id" type="hidden" value="<?= $supplier->getId() ?>">
        <input type="hidden" name="posted" value="1">
    </form>
    <?= $notice["js"] ?>
</div>
<script>
    InitCurr(document.fm.currency_id);
    ChangeCurr('<?=$supplier->getCurrencyId()?>', document.fm.currency_id);
    InitSrcReg(document.fm.supplier_reg);
    ChangeSrcReg('<?=$supplier->getSupplierReg()?>', document.fm.supplier_reg);
    InitSrcReg(document.fm.sourcing_reg);
    ChangeSrcReg('<?=$supplier->getSourcingReg()?>', document.fm.sourcing_reg);
</script>
</body>
</html>