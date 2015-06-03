<html>
<head>
<title><?=$lang["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>
<style type="text/css">
.add_header td
{
	color: #F5F5F5;
}
</style>
</head>
<body>
<div id="main">
<?=$notice["img"]?>
<?php
	$ar_status = array($lang["inactive"], $lang["active"]);
?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="30" class="title"><?=$lang["title"]?></td>
		<td width="400" align="right" class="title"><input type="button" value="<?=$lang["language_button"]?>" class="button" onclick="Redirect('<?=site_url('marketing/footer/language')?>')"></td>
	</tr>
	<tr>
		<td height="2" class="line"></td>
		<td height="2" class="line"></td>
	</tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
	<tr>
		<td height="70" style="padding-left:8px"><b style="font-size:14px"><?=$lang["header"]?></b><br><?=$lang["header_message"]?></td>
	</tr>
</table>
<table border="0" cellpadding="0" cellspacing="1" width="100%" class="tb_list">
	<col width="20"><col width="120"><col width=180><col><col width="400"><col width="60"><col width="80"><col width="80">
	<tr class="add_header">
		<td height="20"></td>
		<td><?=$lang["id"]?></td>
		<td><?=$lang["menu_group"]?></td>
		<td><?=$lang["menu_row_name"]?></td>
		<td><?=$lang["hyperlink"]?></td>
		<td><?=$lang["priority"]?></td>
		<td><?=$lang["status"]?></td>
		<td></td>
	</tr>
<form name="fm_add" action="<?=base_url()?>marketing/footer/edit/" method="post" onSubmit="return CheckForm(this)">
	<tr class="add_row">
		<td></td>
		<td><input name="item_code" dname="Menu ID" class="input" notEmpty ></td>
		<td>
			<select name="menu_group" dname="Menu Group" class="input" notEmpty>
				<option value=""></option>
				<?php
					if ($menu_list)
					{
						foreach ($menu_list as $menu_obj)
						{
				?>
				<option value="<?=$menu_obj->get_menu_item_id()?>"><?=$menu_obj->get_name()?></option>
				<?php
						}
					}
				?>
			</select>
		</td>
		<td><input name="menu_row_name" dname="Menu Row Name" class="input" notEmpty maxLen=64></td>
		<td><input name="hyperlink" dname="Hyperlink" class="input" notEmpty maxLen=256></td>
		<!--<td>
			<select name="target_window" class="input" onChange="ChangeUnit(this.value, this.form.unit_id)" notEmpty>
				<option value=""></option>
				<?php
					if ($target_window_array)
					{
						foreach ($target_window_array as $tw_id=>$tw_name)
						{
				?>
				<option value="<?=$tw_id?>"><?=$tw_name?></option>
				<?php
						}
					}
				?>
			</select>
		</td>
		-->
		<td><input name="priority" dname="Priority" class="input" notEmpty maxLen=3></td>
		<td>
			<select name="status" class="input">
			<option value="1" ><?=$lang["active"]?>
			<option value="0" ><?=$lang["inactive"]?>
			</select>
		</td>
		<td align="center"><input type="submit" value="<?=$lang["add"]?>"></td>
	</tr>
	<tr class="empty_row">
		<td colspan="8"><hr></hr></td>
	</tr>
	<input type="hidden" id="parent_id" name="parent_id" value="">
	<input type="hidden" name="posted" value="1">
	<input type="hidden" name="cmd" value="add">
</form>

<table border="0" cellpadding="0" cellspacing="1" height="20" class="page_header" width="100%">
<col width="20"><col width="120"><col width=180><col><col width="400"><col width="60"><col width="80"><col width="80">
<?php
	foreach($menu_list AS $menu_obj)
{
?>
<tr class="header">
	<td width="150" height="20" colspan="8">&nbsp;&nbsp;<?=$menu_obj->get_name()?></td>
</tr>
<?php
	if($menu_item_list[$menu_obj->get_menu_id()])
	{
		?>
<tr class="add_header">
		<td></td>
		<td style="color:#F0F0F0"><?=$lang["id"]?></td>
		<td style="color:#F0F0F0"><?=$lang["menu_group"]?></td>
		<td style="color:#F0F0F0"><?=$lang["menu_row_name"]?></td>
		<td style="color:#F0F0F0"><?=$lang["hyperlink"]?></td>
		<td style="color:#F0F0F0"><?=$lang["priority"]?></td>
		<td style="color:#F0F0F0"><?=$lang["status"]?></td>
		<td style="color:#F0F0F0">&nbsp;&nbsp;</td>
</tr>
		<?php
		$i=0;
		foreach($menu_item_list[$menu_obj->get_menu_id()] AS $item_obj)
		{
		$is_edit = ($cmd == "edit" && $menu_item_id == $item_obj->get_menu_item_id());
		?>
<form name="fm_edit" action="<?=base_url()?>marketing/footer/edit/" method="post" onSubmit="return CheckForm(this)">
	<tr class="row<?=$i%2?> pointer" onMouseOver="AddClassName(this, 'highlight')" onMouseOut="RemoveClassName(this, 'highlight')" <?if (!($is_edit)){?>onClick="Redirect('<?=site_url('marketing/footer/index/'.$item_obj->get_menu_item_id())?>/')"<?}?>>
		<?php
		if($is_edit)
		{
		?>
		<td height="20"></td>
		<td>&nbsp;&nbsp;<?=$item_obj->get_code()?></td>
		<td>&nbsp;&nbsp;<?=$menu_obj->get_name()?></td>
		<td><input name="name" class="input" value="<?=$item_obj->get_name()?>" notEmpty maxLen=64></td>
		<td><input name="hyperlink" class="input" value="<?=$item_obj->get_link()?>" notEmpty maxLen=64></td>
		<td><input name="priority" class="input" value="<?=$item_obj->get_priority()?>" notEmpty maxLen=3></td>
		<td>
			<select name="status" class="input">
			<?php
			$selected_s[$item_obj->get_status()] = "SELECTED";
			foreach($ar_status AS $rskey=>$rsvalue)
			{
			?>
			<option value="<?=$rskey?>" <?=$selected_s[$rskey]?>><?=$rsvalue?>
			<?php
			}
			?>
			</select>
		</td>
		<td align="center"><input type="submit" value="<?=$lang["update"]?>"></td>
		<?php
		}
		else
		{
		?>
		<td height="20"></td>
		<td>&nbsp;&nbsp;<?=$item_obj->get_code()?></td>
		<td>&nbsp;&nbsp;<?=$menu_obj->get_name()?></td>
		<td>&nbsp;&nbsp;<?=$item_obj->get_name()?></td>
		<td>&nbsp;&nbsp;<?=$item_obj->get_link()?></td>
		<td>&nbsp;&nbsp;<?=$item_obj->get_priority()?></td>
		<td>&nbsp;&nbsp;<?=$ar_status[$item_obj->get_status()]?></td>
		<td>&nbsp;&nbsp;</td>
		<?php
		}
		?>
	</tr>
<input type="hidden" name="posted" value="1">
<input type="hidden" name="cmd" value="update">
<input type="hidden" name="menu_item_id" value="<?=$item_obj->get_menu_item_id()?>">
</form>
			<?php
		$i++;
		}
	}
}
?>
<form name="fm_gen" action="<?=base_url()?>marketing/footer/edit/" method="post" onSubmit="return CheckForm(this)">
	<table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_list">
		<tr>
			<td align="right" style="padding-right:8px;" height="40" class="tb_detail">
				<input type="button" onclick="if(confirm('WARNING: Changes made here will immediately update Website\'s footer menu.\nConfirm update?')){document.fm_gen.submit()}" value="<?=$lang['generate']?>">
			</td>
		</tr>
	</table>
<input type="hidden" name="posted" value="1">
<input type="hidden" name="cmd" value="generate">
</form>
</table><script language="javascript">
</script>
<?=$notice["js"]?>
</body>
</html>