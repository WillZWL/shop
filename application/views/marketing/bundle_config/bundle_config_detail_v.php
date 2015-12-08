<html>
<head>
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>mastercfg/region_helper/js_sourcing_region"></script>
</head>
<body>
<div id="main">
    <?= $notice["img"] ?>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td height="30" class="title"><?= $lang["title"] ?></td>
            <td width="400" align="right" class="title"><input type="button" value="<?= $lang["list_button"] ?>"
                                                               class="button"
                                                               onclick="Redirect('<?= base_url() ?>marketing/bundleConfig/')">
                &nbsp; <input type="button" value="<?= $lang["add_button"] ?>" class="button"
                              onclick="Redirect('<?= base_url() ?>marketing/bundleConfig/add/')"></td>
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
            <col width="160">
            <col width="470">
            <tr class="header">
                <td height="20" colspan="4"><?= $lang["header_message"] ?></td>
            </tr>
            <tr>
                <td class="field"><?= $lang["country_id"] ?></td>
				<td class="value" colspan="3">
					<select type="text" name="country_id" class="input" notEmpty>
						<?php
						foreach ($country_list as $country_obj) :
							?>
							<option value="<?= $country_obj->getCountryId() ?>" <?= ($bundleconfig->getCountryId() == $country_obj->getCountryId() ? "SELECTED" : "") ?>>
							<?= $country_obj->getCountryId() . " - " . $country_obj->getName() ?>
							</option>
						<?php
						endforeach;
						?>
					</select>
				</td>
			</tr>
            <tr>
				<td class="field"><?= $lang["discount_1_item"] ?></td>
                <td class="value" colspan="3">
					<input name="discount_1_item" width="100px" value="<?= htmlspecialchars($bundleconfig->getDiscount1Item()) ?>" notEmpty isNumber min=0>
				</td>
            </tr>
            <tr>
                <td class="field"><?= $lang["discount_2_item"] ?></td>
                <td class="value" colspan="3">
                    <input name="discount_2_item" width="100px" value="<?= htmlspecialchars($bundleconfig->getDiscount2Item()) ?>" notEmpty isNumber min=0>
                </td>
            </tr>
            <tr>
                <td class="field"><?= $lang["discount_3_more_item"] ?></td>
                <td class="value" colspan="3">
                    <input name="discount_3_more_item" width="100px" value="<?= htmlspecialchars($bundleconfig->getDiscount3MoreItem()) ?>" notEmpty isNumber min=0>
                </td>
            </tr>
            <?php
            if ($cmd != "add") :
                ?>
                <tr>
                    <td class="field"><?= $lang["create_on"] ?></td>
                    <td class="value"><?= $bundleconfig->getCreateOn() ?></td>
                    <td class="field"><?= $lang["modify_on"] ?></td>
                    <td class="value"><?= $bundleconfig->getModifyOn() ?></td>
                </tr>
                <tr>
                    <td class="field"><?= $lang["create_at"] ?></td>
                    <td class="value"><?= $bundleconfig->getCreateAt() ?></td>
                    <td class="field"><?= $lang["modify_at"] ?></td>
                    <td class="value"><?= $bundleconfig->getModifyAt() ?></td>
                </tr>
                <tr>
                    <td class="field"><?= $lang["create_by"] ?></td>
                    <td class="value"><?= $bundleconfig->getCreateBy() ?></td>
                    <td class="field"><?= $lang["modify_by"] ?></td>
                    <td class="value"><?= $bundleconfig->getModifyBy() ?></td>
                </tr>
            <?php
            endif;
            ?>
            <tr class="tb_detail">
                <td colspan="2" height="40"><input type="button" name="back" value="<?= $lang['back_list'] ?>"
                                                   onClick="Redirect('<?= isset($_SESSION['LISTPAGE']) ? $_SESSION['LISTPAGE'] : base_url() . '/marketing/bundleConfig' ?>')">
                </td>
                <td colspan="2" align="right" style="padding-right:8px;">
                    <?php
                    if ($cmd == "add") :
                        ?>
                        <input type="submit" value="<?= $lang['header'] ?>">
                    <?php
                    elseif ($cmd == "edit") :
                        ?>
                        <input type="submit" value="<?= $lang['update_button'] ?>">
                    <?php
                    endif;
                    ?>
                </td>
            </tr>
        </table>
        <input name="id" type="hidden" value="<?= $bundleconfig->getId() ?>">
        <input type="hidden" name="cmd" value="edit">
        <input type="hidden" name="posted" value="1">
    </form>
</div>
<?= $notice["js"] ?>
</body>
</html>