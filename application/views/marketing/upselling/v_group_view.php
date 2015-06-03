<html>
	<head>
		<title><?=$lang["title"]?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
		<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/calstyle.css">
		<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
		<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>
		<script language="javascript">
		<!--
			var rowIndex = <?=count((array)$ra_group_product) + 1?> ;

			function addRow(id, sku, name)
			{
				var x=document.getElementById(id).insertRow(rowIndex);

				var a=x.insertCell(0);
				var b=x.insertCell(1);
				var c=x.insertCell(2);
				var d=x.insertCell(3);

				a.setAttribute("class","value");
				b.setAttribute("class","value");
				c.setAttribute("class","value");
				d.setAttribute("class","value");

				a.innerHTML = sku+'<input type="hidden" name="sku[]" value="'+sku+'">';
				b.innerHTML = stripslashes(name);
				c.innerHTML = '<input type="text" name="priority['+sku+']" value="">';
				d.innerHTML = '<input type="button" value="x" onClick="deleteRow(this, \'upselling_group_form\');">';
				rowIndex++;
			}

			function deleteRow(r,id)
			{
				var i=r.parentNode.parentNode.rowIndex;
				document.getElementById(id).deleteRow(i);
				rowIndex--;
			}

			function stripslashes (str) {
				return (str+'').replace(/\\(.?)/g, function (s, n1) {
					switch (n1) {
						case '\\':
							return '\\';
						case '0':
							return '\0';
						case '':
							return '';
						default:
							return n1;
					}
				});
			}

			var selectedVal = '<?=$lang_id?>';

			function SaveChange(el)
			{
				if (confirm("<?=$lang["save_change"]?>"))
				{
					top.location.href = "<?=base_url()."marketing/upselling/view_group/".$ra_group_obj->get_group_id()."/"?>" + el.value;
				}
				else
				{
					for (var i=0;i<el.length;i++)
					{
						if(el.options[i].value == selectedVal)
							el.selectedIndex = i;
					}
					return false;
				}
			}

			function confirmTranslate(group_id, lang_id)
			{
				if(confirm("<?=$lang['translate_all_content']?>"))
				{
					document.location.href = '<?=base_url()?>marketing/upselling/translate_ra_group_content/'+group_id+'/'+lang_id;
				}
				else
				{
					return false;
				}
			}

		</script>
	</head>

	<?php
		$ar_status = array($lang["inactive"], $lang["active"]);
	?>

	<body class="frame_left" style="width:auto;">
		<div id="main" style="width:auto;">
			<?=$notice["img"]?>

			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td height="30" class="title"><?=$lang["subtitle"]?></td>
				</tr>
				<tr>
					<td height="2" bgcolor="#000033"></td>
				</tr>
			</table>

			<table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
				<tr>
					<td height="70" style="padding-left:8px"><b style="font-size:14px"><?=$lang["header"]?></b><br><?=$lang["subheader"]?></td>
					<td width="200" valign="top" align="right" style="padding-right:8px">&nbsp;</td>
				</tr>
			</table>

			<form name="fm" action="<?=base_url()?>marketing/upselling/view_group/<?=$ra_group_obj->get_group_id()?>/<?=$lang_id?>" method="POST" onSubmit="CheckForm(this);" target="_parent">
				<input type="hidden" name="posted" value="1">
				<input type="hidden" name="modify_on" value="<?=$ra_group_obj->get_modify_on()?>">

				<table border="0" cellpadding="0" cellspacing="1" width="100%" class="tb_list">
					<tr>
						<td class="field"><?=$lang["group_name"]?></td>
						<td class="value"><input type="text" name="group_name" value="<?=htmlentities($ra_group_obj->get_group_name())?>" class="input" notEmpty></td>
						<td class="field"><?=$lang["status"]?></td>
						<td class="value">
							<?php
								if ($ra_group_obj->get_status() != "")
								{
									$selected[$ra_group_obj->get_status()] = "SELECTED";
								}
							?>

							<select name="status" class="input">
								<option value="0" <?=$selected[0]?>><?=$ar_status[0]?>
								<option value="1" <?=$selected[1]?>><?=$ar_status[1]?>
							</select>
						</td>
					</tr>
					<tr>
						<td class="field"><?=$lang["website_display_name"]?></td>
						<td class="value"><input type="text" name="group_display_name" value="<?=$ra_group_content_obj->get_group_display_name()?>" style="width:80%" notEmpty>
								<?php if($lang_id != '' && $lang_id != 'en'){?><input type="button" onClick="confirmTranslate('<?=$ra_group_obj->get_group_id()?>','<?=$lang_id?>');" title="<?=$lang["translate_ra_group_content"]?>" value="<?=$lang["translate"]?>"><?}?>
								<?php if($lang_id != '' && $lang_id == 'en'){?><input type="button" onClick="confirmTranslate('<?=$ra_group_obj->get_group_id()?>','<?=$lang_list_str?>');" title="<?=$lang["translate_ra_group_content_all_in_one"]?>" value="<?=$lang["translate"]?>">
								<?}?>
						</td>
						<td class="field"><?=$lang["language"]?></td>
						<td class="value">
							<select id="lang_id" name="lang_id" onchange='return SaveChange(this);' class="input" notEmpty>
								<?php
									if ($lang_list)
									{
										$selected[$lang_id]="SELECTED";
										foreach ($lang_list as $language)
										{
								?>
								<option value="<?=$language->get_id()?>" <?=$selected[$language->get_id()]?>><?=$language->get_name()?></option>
								<?php
										}
									}
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td class="field fieldwidth"><?=$lang["create_on"]?></td>
						<td class="value valuewidth"><?=$ra_group_obj->get_create_on()?></td>
						<td class="field fieldwidth"><?=$lang["modify_on"]?></td>
						<td class="value valuewidth"><?=$ra_group_obj->get_modify_on()?></td>
					</tr>
					<tr>
						<td class="field fieldwidth"><?=$lang["create_by"]?></td>
						<td class="value valuewidth"><?=$ra_group_obj->get_create_by()?></td>
						<td class="field fieldwidth"><?=$lang["modify_by"]?></td>
						<td class="value valuewidth"><?=$ra_group_obj->get_modify_by()?></td>
					</tr>
					<tr>
						<td class="field fieldwidth"><?=$lang["warranty"]?></td>
						<?php $checked = $ra_group_obj->get_warranty() == "1" ? 'checked="checked"' : ""; ?>
						<td class="value valuewidth"><input type="checkbox" name="warranty" value="1" <?= $checked ?> /></td>
						<td class="field fieldwidth"></td>
						<td class="value valuewidth"></td>
					</tr>
				</table>

				<table border="0" cellspacing="1" cellpadding="0" width="100%" class="tb_list">
					<tr class="header">
						<td height="25" style="padding-left:10px;"><font style="color:#ffffff; font-weight:bold; font-size:12px;"><?=$lang["order_detail"]?></font></td>
					</tr>
				</table>

				<table border="0" cellspacing="1" cellpadding="0" width="100%" id="upselling_group_form" class="tb_list">
					<col width="100"><col><col width="30"><col width="30">
					<tr>
						<td class="offield"><?=$lang["sku"]?></td>
						<td class="offield"><?=$lang["prodname"]?></td>
						<td class="offield"><?=$lang["priority"]?></td>
						<td class="offield" width="40"><?=$lang["delete"]?></td>
					</tr>

					<?php
						foreach($ra_group_product as $obj)
						{
					?>
							<tr>
								<td class="value">
									<?=$obj->get_sku()?>
									<input type="hidden" name="sku[]" value="<?=$obj->get_sku()?>">
								</td>
								<td class="value"><?=$obj->get_name()?></td>
								<td class="value"><input type="text" name="priority[<?=$obj->get_sku()?>]" value="<?=$obj->get_priority()?>"></td>
								<td class="value"><input type="button" value="x" onClick="deleteRow(this, 'upselling_group_form');"></td>
							</tr>
					<?php
						}
					?>
				</table>

				<table border="0" cellspacing="1" cellpadding="0" width="100%" bgcolor="#333333">
					<tr>
						<td align="left" style="padding-left:20px;"><input type="button" value="<?=$lang["back"]?>" onClick="parent.location.href = '<?=base_url()."marketing/upselling/group_list/"?>';" class="button"></td>
						<td align="right" height="30" style="padding-right:20px;"><input type="button" value="<?=$lang["submit"]?>" onClick="if(CheckForm(this.form)) this.form.submit();" class="button"></td>
					</tr>
				</table>
			</form>
		</div>

		<?=$notice["js"]?>
	</body>
</html>