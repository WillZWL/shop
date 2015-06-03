<html>
<head>
<title><?=$lang["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>
</head>
<body>
<div id="main">
<?=$notice["img"]?>
<?php
	$ar_type = array("F"=>$lang["freight_cat"], "W"=>$lang["weight_cat"]);
	$ar_weight_type = array("B"=>$lang["both"], "CH"=>$lang["charge"], "CO"=>$lang["cost"]);
?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="30" class="title"><?=$lang["title"]?></td>
		<td width="400" align="right" class="title"><input type="button" value="<?=$lang["list_button"]?>" class="button" onclick="Redirect('<?=site_url('mastercfg/courier/')?>')"></td>
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
	<col width="20"><col width="80"><col width="200"><col><col width="100"><col width="100"><col width="130">
	<tr class="add_header">
		<td height="20"></td>
		<td><?=$lang["id"]?></td>
		<td><?=$lang["courier"]?></td>
		<td><?=$lang["description"]?></td>
		<td><?=$lang["type"]?></td>
		<td><?=$lang["delivery_type"]?></td>
		<td></td>
	</tr>
<form name="fm_add" action="<?=base_url()?>mastercfg/courier/add/?<?=$_SERVER['QUERY_STRING']?>" method="post" onSubmit="return CheckForm(this)">
	<tr class="add_row">
		<td></td>
		<?php
			if ($cmd == "add")
			{
		?>
		<td><input name="id" class="input" value="<?=$this->input->post("id")?>" notEmpty maxLen=16></td>
		<td><input name="courier_name" class="input" value="<?=$this->input->post("courier_name")?>" notEmpty maxLen=32></td>
		<td><input name="description" class="input" value="<?=$this->input->post("description")?>" maxLen=255></td>
		<td>
			<select name="type" class="input" onChange="ChageWeightType()">
				<option value="W" <?=$this->input->post("type")=="W"?"SELECTED":""?>><?=$ar_type["W"]?>
				<option value="F"><?=$ar_type["F"]?>
			</select>
		</td>
		<td>
			<select name="weight_type" class="input">
			<?php
				foreach ($ar_weight_type as $type=>$name)
				{
			?>
				<option value="<?=$type?>"><?=$name?>
			<?php
				}
			?>
			</select>
		</td>
		<?php
			}
			else
			{
		?>
		<td><input name="id" class="input" notEmpty maxLen=16></td>
		<td><input name="courier_name" class="input" notEmpty maxLen=32></td>
		<td><input name="description" class="input" maxLen=255></td>
		<td>
			<select name="type" class="input" onChange="ChageWeightType()">
				<option value="W"><?=$ar_type["W"]?>
				<option value="F"><?=$ar_type["F"]?>
			</select>
		</td>
		<td>
			<select name="weight_type" class="input">
			<?php
				foreach ($ar_weight_type as $type=>$name)
				{
			?>
				<option value="<?=$type?>"><?=$name?>
			<?php
				}
			?>
			</select>
		</td>
		<?php
			}
		?>
		<td align="center"><input type="submit" value="<?=$lang["add"]?>"></td>
	</tr>
	<tr class="empty_row">
		<td colspan="7"><hr></hr></td>
	</tr>
	<input type="hidden" name="posted" value="1">
	<input type="hidden" name="cmd" value="add">
</form>
<form name="fm" method="get">
	<tr class="header">
		<td height="20"><img src="<?=base_url()?>images/expand.png" class="pointer" onClick="Expand(document.getElementById('tr_search'));"></td>
		<td><a href="#" onClick="SortCol(document.fm, 'id', '<?=$xsort["id"]?>')"><?=$lang["id"]?> <?=$sortimg["id"]?></a></td>
		<td><a href="#" onClick="SortCol(document.fm, 'courier_name', '<?=$xsort["courier_name"]?>')"><?=$lang["courier"]?> <?=$sortimg["courier_name"]?></a></td>
		<td><a href="#" onClick="SortCol(document.fm, 'description', '<?=$xsort["description"]?>')"><?=$lang["description"]?> <?=$sortimg["description"]?></a></td>
		<td><a href="#" onClick="SortCol(document.fm, 'type', '<?=$xsort["type"]?>')"><?=$lang["type"]?> <?=$sortimg["type"]?></a></td>
		<td><a href="#" onClick="SortCol(document.fm, 'weight_type', '<?=$xsort["weight_type"]?>')"><?=$lang["delivery_type"]?> <?=$sortimg["weight_type"]?></a></td>
		<td></td>
	</tr>
	<tr class="search" id="tr_search" <?=$searchdisplay?>>
		<td></td>
		<td><input name="id" class="input" value="<?=htmlspecialchars($this->input->get("id"))?>"></td>
		<td><input name="courier_name" class="input" value="<?=htmlspecialchars($this->input->get("courier_name"))?>"></td>
		<td><input name="description" class="input" value="<?=htmlspecialchars($this->input->get("description"))?>"></td>
		<td>
			<select name="type" class="input">
				<option value="">
				<option value="W" <?=$this->input->get("type")=="W"?"SELECTED":""?>><?=$ar_type["W"]?>
				<option value="F" <?=$this->input->get("type")=="F"?"SELECTED":""?>><?=$ar_type["F"]?>
			</select>
		</td>
		<td></td>
		<td align="center"><input type="submit" name="searchsubmit" value="" class="search_button" style="background: url('<?=base_url()?>images/find.gif') no-repeat;"></td>
	</tr>
<input type="hidden" name="sort" value='<?=$this->input->get("sort")?>'>
<input type="hidden" name="order" value='<?=$this->input->get("order")?>'>
</form>
<?php
	$i=0;
	if (!empty($courierlist))
	{
		foreach ($courierlist as $courier)
		{
			$is_edit = ($cmd == "edit" && $courier_id == $courier->get_id());
?>

	<tr class="row<?=$i%2?> pointer" onMouseOver="AddClassName(this, 'highlight')" onMouseOut="RemoveClassName(this, 'highlight')" <?if (!($is_edit)){?>onClick="Redirect('<?=site_url('mastercfg/courier/index/'.$courier->get_id())?>/?<?=$_SERVER['QUERY_STRING']?>')"<?}?>>
		<td height="20"><img src="<?=base_url()?>images/info.gif" title='<?=$lang["create_on"]?>:<?=$courier->get_create_on()?>&#13;<?=$lang["create_at"]?>:<?=$courier->get_create_at()?>&#13;<?=$lang["create_by"]?>:<?=$courier->get_create_by()?>&#13;<?=$lang["modify_on"]?>:<?=$courier->get_modify_on()?>&#13;<?=$lang["modify_at"]?>:<?=$courier->get_modify_at()?>&#13;<?=$lang["modify_by"]?>:<?=$courier->get_modify_by()?>'></td>
		<td><?=htmlspecialchars($courier->get_id())?></td>
		<?php
			if ($is_edit)
			{
		?>
		<form name="fm_edit" action="<?=base_url()?>mastercfg/courier/edit/<?=$courier->get_id()?>/?<?=$_SERVER['QUERY_STRING']?>" method="post" onSubmit="return CheckForm(this)">
			<input type="hidden" name="posted" value="1">
			<input type="hidden" name="cmd" value="edit">
			<input name="id" type="hidden" value="<?=htmlspecialchars($courier->get_id())?>">
			<?php
				if ($this->input->post("posted"))
				{
			?>
				<td><input name="courier_name" class="input" value="<?=htmlspecialchars($this->input->post("courier_name"))?>" notEmpty maxLen=32></td>
				<td><input name="description" class="input" value="<?=htmlspecialchars($this->input->post("description"))?>" maxLen=255></td>
			<?php
				}
				else
				{
			?>
				<td><input name="courier_name" class="input" value="<?=htmlspecialchars($courier->get_courier_name())?>" notEmpty maxLen=32></td>
				<td><input name="description" class="input" value="<?=htmlspecialchars($courier->get_description())?>" maxLen=255></td>
			<?php
				}
			?>
				<td><input name="type" type="hidden" value="<?=$courier->get_type()?>"><?=$ar_type[$courier->get_type()]?></td>
				<td>
				<?php
					if ($courier->get_type() == 'W')
					{
						$wt_selected[$courier->get_weight_type()] = " SELECTED";
				?>
					<select name="weight_type" class="input">
					<?php
						foreach ($ar_weight_type as $type=>$name)
						{
					?>
						<option value="<?=$type?>"<?=$wt_selected[$type]?>><?=$name?>
					<?php
				}
			?>
			</select>
				<?php
					}
				?>
				</td>
				<td align="center"><input type="submit" value="<?=$lang["update"]?>"> &nbsp; <input type="button" value="<?=$lang["back"]?>" onClick="Redirect('<?=site_url('mastercfg/courier/')?>?<?=$_SERVER['QUERY_STRING']?>')"></td>
		</form>
		<?php
			}
			else
			{
		?>
		<td><?=$courier->get_courier_name()?></td>
		<td><?=$courier->get_description()?></td>
		<td><?=$ar_type[$courier->get_type()]?></td>
		<td>
		<?php
			if ($courier->get_type() == "W")
			{
		?>
			<?=$ar_weight_type[$courier->get_weight_type()]?>
		<?php
			}
		?></td>
		<td>&nbsp;</td>
		<?php
			}
		?>
	</tr>
<?php
			$i++;
		}
	}
?>
<script>
function ChageWeightType()
{
	el = document.fm_add.type.value;
	if (el == 'F')
	{
		document.fm_add.weight_type.disabled = true;
	}
	else
	{
		document.fm_add.weight_type.disabled = false;
	}
}
ChageWeightType();
</script>
</table>
<?=$this->pagination_service->create_links_with_style()?>
<?=$notice["js"]?>
</div>
</body>
</html>