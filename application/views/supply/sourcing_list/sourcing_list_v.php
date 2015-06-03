<html>
<head>
<title><?=$lang["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>
<script type="text/javascript" src="<?=base_url()?>supply/supplier_helper/js_supplist"></script>
<script type="text/javascript" src="<?=base_url()?>mastercfg/region_helper/js_sourcing_region"></script>
<script type="text/javascript" src="<?=base_url()?>js/calendar.js"></script>
<link rel="stylesheet" href="<?=base_url()?>css/calendar.css" type="text/css" media="all"/>
</head>
<body>
<div id="main">
<?=$notice["img"]?>
<?php
	$ar_src_status = array("A" => $lang["available"], "C" => $lang["stock_constraint"], "O" => $lang["temp_out_of_stock"], "L" => $lang["last_lot"], "D" => $lang["discontinued"]);
?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="30" class="title"><?=$lang["title"]?></td>
		<td width="400" align="right" class="title"></td>
	</tr>
	<tr>
		<td height="2" class="line"></td>
		<td height="2" class="line"></td>
	</tr>
</table>
<form name="fm" method="get">
<table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
	<tr>
			<?php
				$prevdate = date("Y-m-d", strtotime($list_date) - 86400);
				$nextdate = date("Y-m-d", strtotime($list_date) + 86400);
			?>
		<td height="70" style="padding-left:8px"><b style="font-size:14px"><?=$lang["header"]?> (<?=$list_date?>)</b><br><?=$lang["header_message"]?><br>
			<input type="button" onclick="document.fm.list_date.value='<?=$mindate?>';submitForm()" value="|<">
			<input type="button" onclick="document.fm.list_date.value='<?=$prevdate?>';submitForm()" value="<">
			<input name="list_date" size="9" value="<?=htmlspecialchars($list_date)?>">
			<img src="/images/cal_icon.gif" class="pointer" onclick="showcalendar(event, document.fm.list_date, false, 'submitForm', false, '<?=$mindate?>', '<?=$maxdate?>')" align="absmiddle"> <input type="button" onclick="document.fm.list_date.value='<?=$nextdate?>';submitForm()" value=">"> <input type="button" onclick="document.fm.list_date.value='<?=$maxdate?>';submitForm()" value=">|">
		</td>
	</tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" bgcolor="#000000" width="100%" class="tb_list">
	<col width="20"><col width="90"><col width="80"><col>
	<?php
			if ($platform)
			{
				foreach ($platform as $pf)
				{
	?>
			<col width="50">
	<?php
				}
			}
	?>
	<col width="90"><col width="70"><col width="140"><col><col width="26">
	<tr class="header">
		<td height="20"><img src="<?=base_url()?>images/expand.png" class="pointer" onClick="Expand(document.getElementById('tr_search'));"></td>
		<td><a href="#" onClick="SortCol(document.fm, 'master_sku', '<?=$xsort["master_sku"]?>')"><?=$lang["master_sku"]?> <?=$sortimg["master_sku"]?></a></td>
		<td><a href="#" onClick="SortCol(document.fm, 'item_sku', '<?=$xsort["item_sku"]?>')"><?=$lang["sku"]?> <?=$sortimg["item_sku"]?></a></td>
		<td><a href="#" onClick="SortCol(document.fm, 'prod_name', '<?=$xsort["prod_name"]?>')"><?=$lang["prod_name"]?> <?=$sortimg["prod_name"]?></a></td>
	<?php
			if ($platform)
			{
				foreach ($platform as $pf)
				{
	?>
			<td><?=$pf?></td>
	<?php
				}
			}
	?>
		<td TITLE="<?=$lang["budget"]?>"><a href="#" onClick="SortCol(document.fm, 'budget', '<?=$xsort["budget"]?>')"><?=$lang["budget"]?> <?=$sortimg["budget"]?></a></td>
		<td TITLE="<?=$lang["required_qty"]?>"><a href="#" onClick="SortCol(document.fm, 'required_qty', '<?=$xsort["required_qty"]?>')"><?=$lang["req_qty"]?> <?=$sortimg["required_qty"]?></a></td>
		<td TITLE="<?=$lang["prioritized_qty"]?>"><?=$lang["prioritized_qty"]?></td>
		<td TITLE="<?=$lang["sourced_qty"]?>"><a href="#" onClick="SortCol(document.fm, 'sourced_qty', '<?=$xsort["sourced_qty"]?>')"><?=$lang["src_qty"]?> <?=$sortimg["sourced_qty"]?></a></td>
		<td TITLE="<?=$lang["sourced_pcent"]?>"><a href="#" onClick="SortCol(document.fm, 'sourced_pcent', '<?=$xsort["sourced_pcent"]?>')"><?=$lang["src_pcent"]?> <?=$sortimg["sourced_pcent"]?></a></td>
		<td TITLE="<?=$lang["sourcing_status"]?>"><a href="#" onClick="SortCol(document.fm, 'sourcing_status', '<?=$xsort["sourcing_status"]?>')"><?=$lang["src_status"]?> <?=$sortimg["src_status"]?></a></td>
		<td><a href="#" onClick="SortCol(document.fm, 'comments', '<?=$xsort["comments"]?>')"><?=$lang["comments"]?> <?=$sortimg["comments"]?></a></td>
		<td></td>
	</tr>
	<tr class="search" id="tr_search" <?=$searchdisplay?>>
		<td></td>
		<td><input name="master_sku" class="input" value="<?=htmlspecialchars($this->input->get("master_sku"))?>"></td>
		<td><input name="item_sku" class="input" value="<?=htmlspecialchars($this->input->get("item_sku"))?>"></td>
		<td><input name="prod_name" class="input" value="<?=htmlspecialchars($this->input->get("prod_name"))?>"></td>
	<?php
			if ($platform)
			{
				foreach ($platform as $pf)
				{
	?>
			<td></td>
	<?php
				}
			}
	?>
		<td></td>
		<td><input name="req_qty" class="int_input" value="<?=htmlspecialchars($this->input->get("req_qty"))?>"></td>
		<td>&nbsp;</td>
		<td><input name="sourced_qty" class="int_input" value="<?=htmlspecialchars($this->input->get("sourced_qty"))?>"></td>
		<td><input name="sourced_pcent" class="int_input" value="<?=htmlspecialchars($this->input->get("sourced_pcent"))?>"></td>
		<td>
			<select name="sourcing_status" onChange="this.form.submit();">
				<option value="">
			<?php
				foreach ($ar_src_status as $rskey=>$rsvalue)
				{
			?>
				<option value="<?=$rskey?>" <?=$selected_src[$rskey]?>><?=$rsvalue?>
			<?php
				}
			?>
			</select>
		</td>
		<td><input name="comments" class="input" value="<?=htmlspecialchars($this->input->get("comments"))?>"></td>
		<td align="center"><input type="submit" name="searchsubmit" value="" class="search_button" style="background: url('<?=base_url()?>images/find.gif') no-repeat;"></td>
	</tr>
<input type="hidden" name="sort" value='<?=$this->input->get("sort")?>'>
<input type="hidden" name="order" value='<?=$this->input->get("order")?>'>
</form>
<form name="fm_edit" method="post">
<?php
	$i=0;
	if ($objlist)
	{
		foreach ($objlist as $obj)
		{
			$cur_sku = $obj->get_item_sku();
			$cur_master_sku = $obj->get_master_sku();
?>

	<tr class="row<?=$i%2?>" onMouseOver="AddClassName(this, 'highlight')" onMouseOut="RemoveClassName(this, 'highlight')">
		<td height="20"><img src="<?=base_url()?>images/info.gif" title='<?=$lang["create_on"]?>:<?=$obj->get_create_on()?>&#13;<?=$lang["create_at"]?>:<?=$obj->get_create_at()?>&#13;<?=$lang["create_by"]?>:<?=$obj->get_create_by()?>&#13;<?=$lang["modify_on"]?>:<?=$obj->get_modify_on()?>&#13;<?=$lang["modify_at"]?>:<?=$obj->get_modify_at()?>&#13;<?=$lang["modify_by"]?>:<?=$obj->get_modify_by()?>'></td>
		<td><?=$cur_master_sku?></td>
		<td><?=$cur_sku?></td>
		<td><?=$obj->get_prod_name()?></td>
	<?php
			if ($platform)
			{
				foreach ($platform as $pf)
				{
					$pg = $obj->get_platform_qty();
	?>
			<td><?=$pg->$pf?></td>
	<?php
				}
			}
	?>
		<td><?=$obj->get_supplier_curr_id() . " " . $obj->get_budget()?></td>
		<td><?=$obj->get_required_qty()?></td>
		<td><?=$obj->get_prioritized_qty()?></td>
		<td><input class="int_input" name="src[<?=$cur_sku?>][sourced_qty]" value="<?=htmlspecialchars($obj->get_sourced_qty())?>"></td>
		<td><?=$obj->get_sourced_pcent()?>%</td>
		<td><?=$ar_src_status[$obj->get_sourcing_status()]?></td>
		<td><input class="input" name="src[<?=$cur_sku?>][comments]" value="<?=htmlspecialchars($obj->get_comments())?>"></td>
		<td><input type='hidden' name='check[<?=$cur_sku?>]' value='<?=$cur_sku?>'></td>
	</tr>
<?php
			$i++;
		}
	}
?>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="50%" style="padding-top:5px;position:relative;float:right">
	<tr>
		<td align="right" style="padding-right:8px;">
			<input type="button" value="<?=$lang['print_list']?>" class="button" onClick="window.frames['printframe'].location.href='<?=base_url()?>supply/sourcing_list/index/print?list_date=<?=$list_date?>'"> &nbsp; &nbsp; &nbsp;
			<input type="button" value="<?=$lang['gen_csv']?>" class="button" onClick="window.frames['printframe'].location.href='<?=base_url()?>supply/sourcing_list/index/csv?<?=$_SERVER['QUERY_STRING']?>'"> &nbsp; &nbsp; &nbsp;
			<input type="button" value="<?=$lang['gen_xml']?>" class="button" onClick="window.frames['printframe'].location.href='<?=base_url()?>supply/sourcing_list/index/xml?<?=$_SERVER['QUERY_STRING']?>'"> &nbsp; &nbsp; &nbsp;
			<input type="button" value="<?=$lang['cmd_button']?>" class="button" onClick="this.form.submit()">
		</td>
	</tr>
<input type="hidden" name="posted" value="1">
<input type="hidden" name="list_date" value="<?=$list_date?>">
</table>
</form>
<table border="0" cellpadding="0" cellspacing="0" width="50%" style="padding-top:5px;">
	<tr>
		<td align="left" style="padding-left:8px;">
			<form name="fm_import" method="post" action="<?=base_url()?>supply/sourcing_list/import/<?=$list_date?>" onSubmit="return CheckForm(this);" enctype="multipart/form-data">
				<input type="file" name="csv_file" size="40">
				<input type="button" value="<?=$lang['import_csv']?>" class="button" onClick="this.form.submit()"> (<a href="#" onClick="javascript:window.frames['printframe'].location.href='<?=base_url()?>supply/sourcing_list/download'">Download Example</a>)
			</form>
		</td>
	</tr>
</table>
<?=$this->pagination_service->create_links_with_style()?>
<?=$notice["js"]?>
<script>
InitSupp(document.fm.supplier_id);
document.fm.supplier_id.value = '<?=$this->input->get("supplier_id")?>';
InitSrcReg(document.fm.sourcing_reg);
document.fm.sourcing_reg.value = '<?=$this->input->get("sourcing_reg")?>';

function submitForm()
{
	document.fm.submit();
}
</script>
</div>
<iframe name="printframe" src="" width="0" height="0" frameborder="0" scrolling="no"></iframe>
</body>
</html>