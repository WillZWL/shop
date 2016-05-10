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
                                                               onclick="Redirect('<?= base_url() ?>marketing/pricingRules/')">
                &nbsp; <input type="button" value="<?= $lang["add_button"] ?>" class="button"
                              onclick="Redirect('<?= base_url() ?>marketing/pricingRules/add/')"></td>
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
							<option value="<?= $country_obj->getCountryId() ?>" <?= ($pricingrule->getCountryId() == $country_obj->getCountryId() ? "SELECTED" : "") ?>>
							<?= $country_obj->getCountryId() . " - " . $country_obj->getName() ?>
							</option>
						<?php
						endforeach;
						?>
					</select>
				</td>
			</tr>
            <tr>
				<td class="field"><?= $lang["range"] ?></td>
                <td class="value" colspan="3">
					<input name="range_min" width="100px" value="<?= htmlspecialchars($pricingrule->getRangeMin()) ?>" notEmpty isNumber min=0>
					&nbsp;-&nbsp
					<input name="range_max" width="100px" value="<?= htmlspecialchars($pricingrule->getRangeMax()) ?>" notEmpty isNumber min=0>
				</td>
            </tr>
			<tr>
				<td class="field"><?= $lang["mark_up"] ?></td>
				<td class="value" colspan="3">
					<input name="mark_up_value" width="100px" value="<?= htmlspecialchars($pricingrule->getMarkUpValue()) ?>" notEmpty isNumber>
					&nbsp;&nbsp
					<?php
					$selected[$pricingrule->getMarkUpType()] = "SELECTED";
					?>
					<select name="mark_up_type" width="150px" notEmpty>
						<option value="">
						<option value="A"<?= !empty($selected["A"]) ? $selected["A"] : "" ?>><?= $lang["mark_up_A"] ?>
						<option value="P"<?= !empty($selected["P"]) ? $selected["P"] : "" ?>><?= $lang["mark_up_P"] ?>
					</select>
				</td>
			</tr>
            <tr>
                <td class="field"><?= $lang["min_margin"] ?></td>
                <td class="value" colspan="3">
                    <input name="min_margin" width="100px" value="<?= htmlspecialchars($pricingrule->getMinMargin()) ?>" notEmpty isNumber>
                </td>
            </tr>
			<tr>
				<td class="field"><?= $lang["application_days"] ?></td>
				<td class="value" colspan="3">
					<label><input type="checkbox" name="monday" value="1" <?=$pricingrule->getMonday()?"CHECKED":""?>><?= $lang["monday"] ?></label>
					<label><input type="checkbox" name="tuesday" value="1" <?=$pricingrule->getTuesday()?"CHECKED":""?>><?= $lang["tuesday"] ?></label>
					<label><input type="checkbox" name="wednesday" value="1" <?=$pricingrule->getWednesday()?"CHECKED":""?>><?= $lang["wednesday"] ?></label>
					<label><input type="checkbox" name="thursday" value="1" <?=$pricingrule->getThursday()?"CHECKED":""?>><?= $lang["thursday"] ?></label>
					<label><input type="checkbox" name="friday" value="1" <?=$pricingrule->getFriday()?"CHECKED":""?>><?= $lang["friday"] ?></label>
					<label><input type="checkbox" name="saturday" value="1" <?=$pricingrule->getSaturday()?"CHECKED":""?>><?= $lang["saturday"] ?></label>
					<label><input type="checkbox" name="sunday" value="1" <?=$pricingrule->getSunday()?"CHECKED":""?>><?= $lang["sunday"] ?></label>
				</td>
			</tr>
            <tr>
                <td class="field"><?= $lang["status"] ?></td>
                <td class="value" colspan="3">
                    <select name="status">
                        <option value="0" <?php if($pricingrule->getStatus()=="0") echo "selected"?>>
                            <?= $lang["disable"] ?>
                        </option>
                        <option value="1" <?php if($pricingrule->getStatus()=="1") echo "selected"?>>
                             <?= $lang["enable"] ?>
                        </option>
                    </select>
                </td>
            </tr>
            <?php
            if ($cmd != "add") :
                ?>
                <tr>
                    <td class="field"><?= $lang["create_on"] ?></td>
                    <td class="value"><?= $pricingrule->getCreateOn() ?></td>
                    <td class="field"><?= $lang["modify_on"] ?></td>
                    <td class="value"><?= $pricingrule->getModifyOn() ?></td>
                </tr>
                <tr>
                    <td class="field"><?= $lang["create_at"] ?></td>
                    <td class="value"><?= $pricingrule->getCreateAt() ?></td>
                    <td class="field"><?= $lang["modify_at"] ?></td>
                    <td class="value"><?= $pricingrule->getModifyAt() ?></td>
                </tr>
                <tr>
                    <td class="field"><?= $lang["create_by"] ?></td>
                    <td class="value"><?= $pricingrule->getCreateBy() ?></td>
                    <td class="field"><?= $lang["modify_by"] ?></td>
                    <td class="value"><?= $pricingrule->getModifyBy() ?></td>
                </tr>
            <?php
            endif;
            ?>
            <tr class="tb_detail">
                <td colspan="2" height="40"><input type="button" name="back" value="<?= $lang['back_list'] ?>"
                                                   onClick="Redirect('<?= isset($_SESSION['LISTPAGE']) ? $_SESSION['LISTPAGE'] : base_url() . '/marketing/pricing_rules' ?>')">
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
        <input name="id" type="hidden" value="<?= $pricingrule->getId() ?>">
        <input type="hidden" name="cmd" value="edit">
        <input type="hidden" name="posted" value="1">
    </form>
</div>
<?= $notice["js"] ?>
</body>
</html>