<html>
<head>
<title><?=$lang["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>
<script type="text/javascript" src="<?=base_url()?>mastercfg/selling_platform/get_js"></script>
<script language="javascript">
<!--
function drawList(value)
{
	var selected = "";
	var output="";
	for(var i in platform)
	{
		selected = platform[i][0] == value?"SELECTED":"";
		output = "<option value='"+platform[i][0]+"' "+selected+">"+platform[i][0]+"</option>";
		document.write(output);
	}
}
-->
</script>
</head>
<body>
<div id="main">
<?=$notice["img"]?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="30" class="title"><?=$lang["title"]?></td>
		<td width="400" align="right" class="title"></td>
	</tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
	<tr>
		<td height="70" style="padding-left:8px"><b style="font-size:14px"><?=$lang["header"]?></b><br><?=$lang["header_message"]?></td>
	</tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_list">
	<col width="20"><col width="100"><col width="100"><col width="120"><col width="240"><col><col width="150"><col width="20">
<form name="fm" method="get" onSubmit="return CheckForm(this)">
	<tr class="header">
		<td height="20"><img src="<?=base_url()?>images/expand.png" class="pointer" onClick="Expand(document.getElementById('tr_search'));"></td>
		<td><a href="#" onClick="SortCol(document.fm, 'so.so_no', '<?=$xsort["so.so_no"]?>')"><?=$lang["so_no"]?> <?=$sortimg["so.so_no"]?></a></td>
		<td><a href="#" onClick="SortCol(document.fm, 'so.platform_id', '<?=$xsort["so.platform_id"]?>')"><?=$lang["platform_id"]?> <?=$sortimg["so.platform_id"]?></a></td>
		<td><a href="#" onClick="SortCol(document.fm, 'cp.item_sku', '<?=$xsort["cp.item_sku"]?>')"><?=$lang["prod_sku"]?> <?=$sortimg["cp.item_sku"]?></a></td>
		<td><a href="#" onClick="SortCol(document.fm, 'p.name', '<?=$xsort["p.name"]?>')"><?=$lang["prod_name"]?> <?=$sortimg["p.name"]?></a></td>
		<td><a href="#" onClick="SortCol(document.fm, 'cph.note', '<?=$xsort["cph.note"]?>')"><?=$lang["reason"]?> <?=$sortimg["cph.note"]?></a></td>
		<td><a href="#" onClick="SortCol(document.fm, 'cp.create_on', '<?=$xsort["cp.create_on"]?>')"><?=$lang["request_on"]?> <?=$sortimg["cp.create_on"]?></a></td>
		<td></td>
	</tr>
	<tr class="search" id="tr_search" <?=$searchdisplay?>>
		<td></td>
		<td><input name="so" type="text" class="input" value="<?=$this->input->get("so")?>"></td>
		<td><select name="platform_id" class="input"><option value=""></option><script language="javascript">drawList("<?=$this->input->get('platform_id')?>");</script></select> </td>
		<td><input name="sku" type="text" class="input" value="<?=$this->input->get("sku")?>"></td>
		<td><input name="prod_name" type="text" class="input" value="<?=$this->input->get("prod_name")?>"></td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td align="center"><input type="submit" name="searchsubmit" value="" class="search_button" style="background: url('<?=base_url()?>images/find.gif') no-repeat;"></td>
	</tr>
<input type="hidden" name="sort" value='<?=$this->input->get("sort")?>'>
<input type="hidden" name="order" value='<?=$this->input->get("order")?>'>
</form>
<?php
	$i=0;
	if (!empty($list))
	{
		foreach ($list as $obj)
		{
?>

	<tr class="row<?=$i%2?> pointer" onMouseOver="AddClassName(this, 'highlight')" onMouseOut="RemoveClassName(this, 'highlight')" <?if (!($is_edit)){?>onClick="Pop('<?=site_url('cs/compensation/manager_approval_view/'.$obj->get_compensation_id().'/'.$obj->get_so_no())?>/?<?=$_SERVER['QUERY_STRING']?>')"<?}?>>


		<td height="20"></td>
		<td><?=$obj->get_so_no()?></td>
		<td><?=$obj->get_platform_id()?></td>
		<td><?=$obj->get_item_sku()?></td>
		<td><?=$obj->get_prod_name()?></td>
		<td><?=$obj->get_note()?></td>
		<td><?=$obj->get_request_on()?></td>
		<td>&nbsp;</td>
<?php
			$i++;
		}
	}
?>
<?php
if(check_app_feature_access_right($app_id, "CS000405_back_to_list_btn"))
{
?>
	<tr class="header">
		<td></td>
		<td colspan="9"><input type="button" onClick="Redirect('<?=base_url()?>cs/compensation/');" value="<?=$lang["back_to_main"]?>"></td>
	</tr>
<?php
}
?>
</table>
<?=$this->pagination_service->create_links_with_style()?>
<?=$notice["js"]?>
</div>
</body>
</html>