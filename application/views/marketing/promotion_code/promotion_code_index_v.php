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
	$ar_status = array("inactive", "active");
?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="30" class="title"><?=$lang["title"]?></td>
		<td width="400" align="right" class="title">
			<input type="button" value="<?=$lang["list_button"]?>" class="button" onclick="Redirect('<?=site_url('marketing/promotion_code/')?>')">
			 &nbsp; <input type="button" value="<?=$lang["add_button"]?>" class="button" onclick="Redirect('<?=site_url('marketing/promotion_code/add/')?>')">
		</td>
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
<form name="fm" method="get">
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_list">
	<col width="20"><col width="150"><col><col width="150"><col width="70"><col width="100"><col width="26">
	<tr class="header">
		<td height="20">
				<img src="<?=base_url()?>images/expand.png" class="pointer" onClick="Expand(document.getElementById('tr_search'));">
		</td>
		<td><a href="#" onClick="SortCol(document.fm, 'code', '<?=$xsort["code"]?>')"><?=$lang["code"]?> <?=$sortimg["code"]?></a></td>
		<td><a href="#" onClick="SortCol(document.fm, 'description', '<?=$xsort["description"]?>')"><?=$lang["description"]?> <?=$sortimg["description"]?></a></td>
		<td><a href="#" onClick="SortCol(document.fm, 'expire_date', '<?=$xsort["expire_date"]?>')"><?=$lang["expire_date"]?> <?=$sortimg["expire_date"]?></a></td>
		<td><a href="#" onClick="SortCol(document.fm, 'status', '<?=$xsort["status"]?>')"><?=$lang["status"]?> <?=$sortimg["status"]?></a></td>
		<td><a href="#" onClick="SortCol(document.fm, 'no_taken', '<?=$xsort["no_taken"]?>')"><?=$lang["no_taken"]?> <?=$sortimg["no_taken"]?></a></td>
		<td></td>
	</tr>
	<tr class="search" id="tr_search" <?=$searchdisplay?>>
		<td></td>
		<td><input name="code" class="input" value="<?=htmlspecialchars($this->input->get("code"))?>"></td>
		<td><input name="description" class="input" value="<?=htmlspecialchars($this->input->get("description"))?>"></td>
		<td><input name="expire_date" class="input" value="<?=htmlspecialchars($this->input->get("expire_date"))?>"></td>
		<td>
			<?php
				if ($this->input->get("status") != "")
				{
					$selected[$this->input->get("status")] = "SELECTED";
				}
			?>
			<select name="status" class="input">
				<option value="">
				<option value="0" <?=$selected[0]?>><?=$lang[$ar_status[0]]?>
				<option value="1" <?=$selected[1]?>><?=$lang[$ar_status[1]]?>
			</select>
		</td>
		<td><input name="no_taken" class="input" value="<?=htmlspecialchars($this->input->get("no_taken"))?>"></td>
		<td align="center"><input type="submit" name="searchsubmit" value="" class="search_button" style="background: url('<?=base_url()?>images/find.gif') no-repeat;"></td>
	</tr>
<?php
	$i=0;
	if ($objlist)
	{
		foreach ($objlist as $obj)
		{
?>

	<tr class="row<?=$i%2?> pointer" onMouseOver="AddClassName(this, 'highlight')" onMouseOut="RemoveClassName(this, 'highlight')" onClick="Redirect('<?=site_url('marketing/promotion_code/view/'.$obj->get_code())?>')">
		<td height="20"><img src="<?=base_url()?>images/info.gif" title='<?=$lang["create_on"]?>:<?=$obj->get_create_on()?>&#13;<?=$lang["create_at"]?>:<?=$obj->get_create_at()?>&#13;<?=$lang["create_by"]?>:<?=$obj->get_create_by()?>&#13;<?=$lang["modify_on"]?>:<?=$obj->get_modify_on()?>&#13;<?=$lang["modify_at"]?>:<?=$obj->get_modify_at()?>&#13;<?=$lang["modify_by"]?>:<?=$obj->get_modify_by()?>'></td>
		<td><?=$obj->get_code()?></td>
		<td><?=$obj->get_description()?></td>
		<td><?=$obj->get_expire_date()?></td>
		<td><?=$lang[$ar_status[$obj->get_status()]]?></td>
		<td><?=$obj->get_no_taken()?></td>
		<td></td>
	</tr>
<?php
			$i++;
		}
	}
?>
</table>
<input type="hidden" name="sort" value='<?=$this->input->get("sort")?>'>
<input type="hidden" name="order" value='<?=$this->input->get("order")?>'>
</form>
<?=$this->pagination_service->create_links_with_style()?>
<?=$notice["js"]?>
</div>
</body>
</html>