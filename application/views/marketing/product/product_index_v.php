<html>
<head>
<title><?=$lang["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script type="text/javascript" src="<?=base_url()?>marketing/product/js_catlist"></script>
<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>
</head>
<body>
<div id="main">
<?=$notice["img"]?>
<?php
	$ar_status = array("inactive", "created", "listed");
?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="30" class="title"><?=$lang["title"]?></td>
		<td width="600" align="right" class="title">
			<input type="button" value="<?=$lang["list_button"]?>" class="button" onClick="Redirect('<?=site_url('marketing/product/')?>')">
			 &nbsp; <input type="button" value="<?=$lang["add_button"]?>" class="button" onClick="Redirect('<?=site_url('marketing/product/add/')?>')">
			<?php
				if ($prod_grp_cd)
				{
			?>
			 &nbsp; <input type="button" value="<?=$lang["add_colour"]?>" class="button" onClick="Redirect('<?=site_url('marketing/product/add_colour/'.$prod_grp_cd)?>')">&nbsp; <input type="button" value="<?=$lang["add_version"]?>" class="button" onClick="Redirect('<?=site_url('marketing/product/add_version/'.$prod_grp_cd.'/'.$version_id)?>')">
			<?php
				}
			?>
			<input type="button" value="<?=$lang["translat_button"]?>" class="button" onclick="Redirect('<?=site_url('marketing/product/translat_all_sku')?>')">
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
		<td>
			Download unmapped SKUs CSV <a href="/marketing/product/download_unmapped_sku">here</a>,
			after modification, upload the file below
			<form action="/marketing/product/upload_mapped_sku" enctype="multipart/form-data" method="post">
			<input type="file" name="datafile" size="40">
			<input type="submit" value="Upload">
			</form>
		</td>
	</tr>
	<tr>
			<td></td>
			<td style="text-align:left;">
			Upload SKU Website Display Name
			<form action="/marketing/product/upload_sku_product_name" enctype="multipart/form-data" method="post">
				<input type="file" name="sku_product_name_file" size="40">
				<input type="submit" value="Upload">
			</form>
		</td>
	</tr>
</table>
<form name="fm" method="get">
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_list">
	<col width="30"><col width="100"><col width="100"><col><col width="80"><col width="130"><col width="130"><col width="130"><col width="100"><col width="70"><col width="26">
	<tr class="header">
		<td height="20">
			<?php
				if ($prod_grp_cd == "")
				{
			?>
				<img src="<?=base_url()?>images/expand.png" class="pointer" onClick="Expand(document.getElementById('tr_search'));">
			<?php
				}
			?>
		</td>
		<td><a href="#" onClick="SortCol(document.fm, 'master_sku', '<?=$xsort["master_sku"]?>')"><?=$lang["master_sku"]?> <?=$sortimg["master_sku"]?></a></td>
		<td><a href="#" onClick="SortCol(document.fm, 'sku', '<?=$xsort["sku"]?>')"><?=$lang["sku"]?> <?=$sortimg["sku"]?></a></td>
		<td><a href="#" onClick="SortCol(document.fm, 'name', '<?=$xsort["name"]?>')"><?=$lang["product_name"]?> <?=$sortimg["name"]?></a></td>
		<td><a href="#" onClick="SortCol(document.fm, 'colour', '<?=$xsort["colour"]?>')"><?=$lang["colour"]?> <?=$sortimg["colour"]?></a></td>
		<td><a href="#" onClick="SortCol(document.fm, 'category', '<?=$xsort["category"]?>')"><?=$lang["category"]?> <?=$sortimg["category"]?></a></td>
		<td><a href="#" onClick="SortCol(document.fm, 'sub_cat', '<?=$xsort["sub_cat"]?>')"><?=$lang["sub_cat"]?> <?=$sortimg["sub_cat"]?></a></td>
		<td><a href="#" onClick="SortCol(document.fm, 'sub_sub_cat', '<?=$xsort["sub_sub_cat"]?>')"><?=$lang["sub_sub_cat"]?> <?=$sortimg["sub_sub_cat"]?></a></td>
		<td><a href="#" onClick="SortCol(document.fm, 'brand', '<?=$xsort["brand"]?>')"><?=$lang["brand"]?> <?=$sortimg["brand"]?></a></td>
		<td><a href="#" onClick="SortCol(document.fm, 'status', '<?=$xsort["status"]?>')"><?=$lang["status"]?> <?=$sortimg["status"]?></a></td>
		<td></td>
	</tr>
	<?php
		if ($prod_grp_cd == "")
		{
	?>
	<tr class="search" id="tr_search" <?=$searchdisplay?>>
		<td></td>
		<td><input name="master_sku" class="input" value="<?=htmlspecialchars($this->input->get("master_sku"))?>"></td>
		<td><input name="sku" class="input" value="<?=htmlspecialchars($this->input->get("sku"))?>"></td>
		<td><input name="name" class="input" value="<?=htmlspecialchars($this->input->get("name"))?>"></td>
		<td><input name="colour" class="input" value="<?=htmlspecialchars($this->input->get("colour"))?>"></td>
		<td><select name="cat_id" class="input" onChange="ChangeCat(this.value, this.form.sub_cat_id, this.form.sub_sub_cat_id)">
				<option value="">
			</select></td>
		<td><select name="sub_cat_id" class="input" onChange="ChangeCat(this.value, this.form.sub_sub_cat_id)">
				<option value="">
			</select></td>
		<td><select name="sub_sub_cat_id" class="input">
				<option value="">
			</select></td>
		<td><input name="brand" class="input" value="<?=htmlspecialchars($this->input->get("brand"))?>"></td>
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
				<option value="2" <?=$selected[2]?>><?=$lang[$ar_status[2]]?>
			</select>
		</td>
		<td align="center"><input type="submit" name="searchsubmit" value="" class="search_button" style="background: url('<?=base_url()?>images/find.gif') no-repeat;"></td>
	</tr>
	<?php
		}
	?>
<?php
	$i=0;
	if ($objlist)
	{
		foreach ($objlist as $obj)
		{
?>

	<tr class="row<?=$i%2?> pointer" onMouseOver="AddClassName(this, 'highlight')" onMouseOut="RemoveClassName(this, 'highlight')" onClick="Redirect('<?=site_url('marketing/product/view/'.$obj->get_sku())?>')">
		<td height="20"><img src="<?=base_url()?>images/info.gif" title='<?=$lang["create_on"]?>:<?=$obj->get_create_on()?>&#13;<?=$lang["create_at"]?>:<?=$obj->get_create_at()?>&#13;<?=$lang["create_by"]?>:<?=$obj->get_create_by()?>&#13;<?=$lang["modify_on"]?>:<?=$obj->get_modify_on()?>&#13;<?=$lang["modify_at"]?>:<?=$obj->get_modify_at()?>&#13;<?=$lang["modify_by"]?>:<?=$obj->get_modify_by()?>'></td>
		<td><?=$obj->get_master_sku()?></td>
		<td><?=$obj->get_sku()?></td>
		<td><?=$obj->get_name()?></td>
		<td><?=$obj->get_colour()?></td>
		<td><?=$obj->get_category()?></td>
		<td><?=$obj->get_sub_cat()?></td>
		<td><?=$obj->get_sub_sub_cat()?></td>
		<td><?=$obj->get_brand()?></td>
		<td><?=$lang[$ar_status[$obj->get_status()]]?></td>
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
<input type="hidden" name="search" value="1">
</form>
<?=$this->pagination_service->create_links_with_style()?>
<?=$notice["js"]?>
</div>
<script language='javascript'>
ChangeCat('0', document.fm.cat_id);
document.fm.cat_id.value = '<?=$this->input->get("cat_id")?>';
ChangeCat('<?=$this->input->get("cat_id")?>', document.fm.sub_cat_id);
document.fm.sub_cat_id.value = '<?=$this->input->get("sub_cat_id")?>';
ChangeCat('<?=$this->input->get("sub_cat_id")?>', document.fm.sub_sub_cat_id);
document.fm.sub_sub_cat_id.value = '<?=$this->input->get("sub_sub_cat_id")?>';
</script>
</body>
</html>