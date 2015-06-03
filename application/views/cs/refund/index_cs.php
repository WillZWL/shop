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
		<td height="30" class="title"><?=$lang["title"]?>  (<a href="/cs/refund/export_cs_csv?<?=$q?>">Export as CSV</a>)</td>
		<td width="400" align="right" class="title"></td>
	</tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
	<tr>
		<td height="70" style="padding-left:8px"><b style="font-size:14px"><?=$lang["header"]?></b><br><?=$lang["header_message"]?></td>
	</tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_list">
	<col width="20"><col width="150"><col width="150"><col><col width="150"><col width="120"><col width="120"><col width="120"><col width="120"><col width="20"><col width="20"><col width="20">
<form name="fm" method="get" onSubmit="return CheckForm(this)">
	<tr class="header">
		<td height="20"><img src="<?=base_url()?>images/expand.png" class="pointer" onClick="Expand(document.getElementById('tr_search'));"></td>
		<td><a href="#" onClick="SortCol(document.fm, 'r.id', '<?=$xsort["r.id"]?>')"><?=$lang["refund_id"]?> <?=$sortimg["r.id"]?></a></td>
		<td><a href="#" onClick="SortCol(document.fm, 'r.so_no', '<?=$xsort["r.so_no"]?>')"><?=$lang["so_no"]?> <?=$sortimg["r.so_no"]?></a></td>
		<td><a href="#" onClick="SortCol(document.fm, 's.platform_order_id', '<?=$xsort["s.platform_order_id"]?>')"><?=$lang["platform_order_id"]?> <?=$sortimg["s.platform_order_id"]?></a></td>

		<td><a href="#" coords="<?=$multiSort['multi_sort_pg']?>" class='multiSortMode' onClick="MultiSort('multi_sort_pg')" name="multi_sort_pg" ><?=$lang["payment_gateway"]?> <?=$sortimg["pg.name"]?></a></td>

		<td><a href="#" onClick="SortCol(document.fm, 's.platform_id', '<?=$xsort["s.platform_id"]?>')"><?=$lang["platform_id"]?> <?=$sortimg["s.platform_id"]?></a></td>
		<td><a href="#" onClick="SortCol(document.fm, 'r.total_refund_amount', '<?=$xsort["r.total_refund_amount"]?>')"><?=$lang["amount"]?> <?=$sortimg["r.total_refund_amount"]?></a></td>
		<td><a href="#" onClick="SortCol(document.fm, 'r.order_date', '<?=$xsort["r.order_date"]?>')"><?=$lang["order_date"]?> <?=$sortimg["r.order_date"]?></a></td>

		<td><a href="#" onClick="SortCol(document.fm, 's.dispatch_date', '<?=$xsort["s.dispatch_date"]?>')"><?=$lang["shipped_on"]?> <?=$sortimg["s.dispatch_date"]?></a></td>

		<td><a href="#" onClick="SortCol(document.fm, 'r.create_on', '<?=$xsort["r.create_on"]?>')"><?=$lang["create_on"]?> <?=$sortimg["r.create_on"]?></a></td>
		<td><a href="#" onClick="SortCol(document.fm, 'r.create_by', '<?=$xsort["r.create_by"]?>')"><?=$lang["create_by"]?> <?=$sortimg["r.create_by"]?></a></td>

		<td><a href="#" onClick="SortCol(document.fm, 's.special_order', '<?=$xsort["s.special_order"]?>')"><?=$lang["special_order"]?> <?=$sortimg["s.special_order"]?></a></td>
		<td><a href="#" onClick="SortCol(document.fm, 'r.reason', '<?=$xsort["r.reason"]?>')"><?=$lang["reason"]?> <?=$sortimg["r.reason"]?></a></td>

		<td><a href="#" onClick="SortCol(document.fm, 'sors.score', '<?=$xsort["sors.score"]?>')"><?=$lang["refund_score"]?> <?=$sortimg["sors.score"]?></a></td>
		<td><a href="#" coords="<?=$multiSort['multi_sort_rsd']?>" class='multiSortMode' onClick="MultiSort('multi_sort_rsd')" name="multi_sort_rsd" ><?=$lang["refund_score_date"]?> <?=$sortimg["sors.modify_on"]?></a></td>
		<td><a href="#" coords="<?=$multiSort['multi_sort_pd']?>" class='multiSortMode' onClick="MultiSort('multi_sort_pd')" name="multi_sort_pd" ><?=$lang["pack_date"]?> <?=$sortimg["s.pack_date"]?></a></td>
		<td></td>
	</tr>
	<tr class="search" id="tr_search" <?=$searchdisplay?>>
		<td></td>
		<td><input name="rid" type="text" class="input" value="<?=$this->input->get("rid")?>"></td>
		<td><input name="so" type="text" class="input" value="<?=$this->input->get("so")?>"></td>
		<td><input name="platform_order_id" type="text" class="input" value="<?=$this->input->get("platform_order_id")?>"></td>
		<td>
			<select name="payment_gateway">
			<?php
				$payment_gateway_list = array('','amazon','Amazon - UK','Amazon - US','Cyber Source','FNAC - FR','Global Collect','MoneyBookers','MoneyBookers CTPE','Manual bank transfer','PayPal','Trustly','World Pay','World Pay Moto','World Pay Moto + Cash for COD');
				foreach($payment_gateway_list as $value)
				{
					$selected = strtolower($this->input->get("payment_gateway")) == strtolower($value) ? 'selected': null;
			?>
				<option <?=$selected?> value="<?=$value?>"> <?=$value?></option>
			<?php
				}
			?>
			</select>
		</td>
		<td><select name="platform_id" class="input"><option value=""></option><script language="javascript">drawList("<?=$this->input->get('platform_id')?>");</script></select> </td>

		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td align="center"><input type="submit" name="searchsubmit" value="" class="search_button" style="background: url('<?=base_url()?>images/find.gif') no-repeat;"></td>
	</tr>
<input type="hidden" name="sort" value='<?=$this->input->get("sort")?>'>
<input type="hidden" name="order" value='<?=$this->input->get("order")?>'>
</form>
<?php
	$soldprice = array();
	$i=0;
	if (!empty($list))
	{
		foreach ($list as $obj)
		{
?>

	<tr class="row<?=$i%2?> pointer" onMouseOver="AddClassName(this, 'highlight')" onMouseOut="RemoveClassName(this, 'highlight')" <?if (!($is_edit)){?>onClick="Pop('<?=site_url('cs/refund/cs_view/'.$obj->get_id())?>/?<?=$_SERVER['QUERY_STRING']?>', 'refund_<?=$obj->get_id()?>')"<?}?>>
		<td height="20"></td>
		<td><?=$obj->get_id()?></td>
		<td><?=$obj->get_so_no()?></td>
		<td><?=$obj->get_platform_order_id()?></td>
		<td><?=$obj->get_payment_gateway()?></td>
		<td><?=$obj->get_platform_id()?></td>
		<td><?=$obj->get_currency_id()." ".$obj->get_total_refund_amount()?></td>
		<td><?=$obj->get_order_date()?></td>

		<td><?=$obj->get_dispatch_date()?></td>

		<td><?=$obj->get_create_on()?></td>
		<td><?=$obj->get_create_by()?></td>

		<td><?php if($obj->get_special_order()!=0) echo "Y";?></td>
		<td><?=$obj->get_refund_reason()?></td>

		<td><?=$obj->get_refund_score()?></td>

		<td><?=$obj->get_refund_score_date()?></td>

		<td><?=$obj->get_pack_date()?></td>

		<td>&nbsp;</td>
<?php

			$i++;
		}
	}
?>
	<tr class="header">
		<td></td>
		<td colspan="10><input type="button" onClick="Redirect('<?=base_url()?>cs/refund/');" value="<?=$lang["back_to_main"]?>"></td>
	</tr>
</table>
<?=$this->pagination_service->create_links_with_style()?>
<?=$notice["js"]?>
</div>
</body>
</html>