<html>
<head>
<title><?=$lang["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/calstyle.css">
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/datepicker.js"></script>
<script language="javascript">
<!--
function showHide(elem)
{
	var vis =  document.getElementById(elem).style.display;
	document.getElementById(elem).style.display = (vis == "block"?"none":"block");
}

function checkNote()
{
	if(trim(document.getElementById("cnotes").value) == "")
	{
		alert("Please fill in the reject reason in comment.");
		return false;
	}
	else
	{
		return true;
	}
}
-->
</script>
</head>
<body >
<div id="main" bgcolor="#ddddff">
<?=$notice["image"]?>
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
<!-- recent history -->
<table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#000033">
<tr>
	<td align="left" height="20" style="padding-left:10px;"><a style="font-size:12px; color:#ffffff; font-weight:bold; cursor:pointer;" onClick="showHide('p1')"><?=$lang["compensation_history"]." ".$orderid?></a></td>
</tr>
</table>
<div id="p1" style="width:100%; display:block;">
<table width="100%" cellpadding="0" cellspacing="1" border="0" bgcolor="#cccccc">
<col width="20"><col width="180"><col width=""><col width="180"><col width="180"><col width="20">
<?php
	if(count($history))
	{
?>
<tr class="header">
	<td height="20">&nbsp;</td>
	<td ><?=$lang["date"]?></td>
	<td ><?=$lang["notes"]?></td>
	<td ><?=$lang["process_by"]?></td>
	<td ><?=$lang["status"]?></td>
	<td>&nbsp;</td>
</tr>
<?php
		$i = 0;
		foreach($history as $obj)
		{
?>
<tr class="row<?=$i%2?>">
	<td>&nbsp;</td>
	<td ><?=$obj->get_create_on()?></td>
	<td ><?=$obj->get_note()?></td>
	<td ><?=$obj->get_create_by()?></td>
	<td ><?=$lang["app_status"][$obj->get_status()]?></td>
	<td>&nbsp;</td>
</tr>
<?php
			$i++;
		}
	}
	else
	{
?>
<tr class="row0">
	<td align="center" colspan="7" height="20"><?=$lang["no_history"]?></td>
</tr>
<?php
	}
?>
</table>
</div>
<table border="0" cellpadding="0" cellspacing="0" class="page_header" width="100%">
<tr height="20">
	<td>&nbsp;</td>
</tr>
</table>
<!-- Order information -->
<table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#000033">
<tr>
	<td align="left" height="20" style="padding-left:10px;"><a style="font-size:12px; color:#ffffff; font-weight:bold; cursor:pointer;" onclick="showHide('p2');"><?=$lang["order_information"]?></a></td>
</tr>
</table>
<div id="p2" style="width:100%; display:block;">
<table border="0" cellpadding="0" cellspacing="1" bgcolor="#cccccc" width="100%">
<tr>
	<td width="15%" height="20" align="right" class="field" style="padding-right:10px;"><?=$lang["order_number"]?></td>
	<td width="35%" align="left" class="value" style="padding-left:10px;"><?=$orderobj->get_so_no().($orderobj->get_txn_id() != ""?" (".$lang["txn_id"]." : ".$orderobj->get_txn_id().") ":"")?></td>
	<td width="15%" height="20" align="right" class="field" style="padding-right:10px;"><?=$lang["order_status"]?></td>
	<td width="35%" align="left" class="value" style="padding-left:10px;"><?=$lang["so_status"][$orderobj->get_status()]?></td>
</tr>
<tr>
	<td width="15%" height="20" align="right" class="field" style="padding-right:10px;"><?=$lang["platform"]?></td>
	<td width="35%" align="left" class="value" style="padding-left:10px;"><?=$lang["so_platform"][$orderobj->get_platform_id()]?></td>
	<td width="15%" height="20" align="right" class="field" style="padding-right:10px;"><?=$lang["order_amount"]?></td>
	<td width="35%" align="left" class="value" style="padding-left:10px;"><?=$orderobj->get_currency_id()." ".number_format(($orderobj->get_amount() - $orderobj->get_delivery_charge()),2)?></td>
</tr>
<?php
	if(ereg('^WS',$orderobj->get_platform_id()))
	{
?>
<tr>
	<td width="15%" height="20" align="right" class="field" style="padding-right:10px;"><?=$lang["biztype"]?></td>
	<td width="35%" align="left" class="value" style="padding-left:10px;"><?=$orderobj->get_biz_type()?></td>
	<td width="15%" height="20" align="right" class="field" style="padding-right:10px;"><?=$lang["order_delivery_charge"]?></td>
	<td width="35%" align="left" class="value" style="padding-left:10px;"><?=$orderobj->get_currency_id()." ".number_format($orderobj->get_delivery_charge(),2)?></td>
</tr>
<?php
	}

	else
	{
?>
<tr>
	<td width="15%" height="20" align="right" class="field" style="padding-right:10px;"><?=$lang["platform_order_id"]?></td>
	<td width="35%"align="left" class="value" style="padding-left:10px;"><?=$orderobj->get_platform_order_id()?></td>
	<td width="15%" height="20" align="right" class="field" style="padding-right:10px;"><?=$lang["order_delivery_charge"]?></td>
	<td width="35%" align="left" class="value" style="padding-left:10px;"><?=$orderobj->get_currency_id()." ".$orderobj->get_delivery_charge()?></td>
</tr>
<?php
	}
?>
<tr>
	<td width="15%" height="20" align="right" class="field" style="padding-right:10px;"><?=$lang["client_id_and_name"]?></td>
	<td width="35%" align="left" class="value" style="padding-left:10px;"><?=$orderobj->get_client_id()." - ".$orderobj->get_bill_name()?></td>
	<td width="15%" height="20" align="right" class="field" style="padding-right:10px;"><?=$lang["order_total"]?></td>
	<td width="35%" align="left" class="value" style="padding-left:10px;"><?=$orderobj->get_currency_id()." ".number_format(($orderobj->get_amount()),2)?></td>
</tr>
<tr>
	<td width="15%" height="20" align="right" class="field" style="padding-right:10px;"><?=$lang["order_detail"]?></td>
	<td width="85%" colspan="3" align="left" class="value">
	<table border="0" cellpadding="0" cellspacing="1" bgcolor="#cccccc" width="100%">
	<col width="20"><col width="100"><col><col width="100"><col width="150"><col width="100"><col width="20">
	<tr class="header">
		<td height="20">&nbsp;</td>
		<td style="padding-left:4px;"><?=$lang["item_sku"]?></td>
		<td style="padding-left:4px;"><?=$lang["prod_name"]?></td>
		<td style="padding-left:4px;"><?=$lang["original_price"]?></td>
		<td style="padding-left:4px;"><?=$lang["discounted_price"]?></td>
		<td style="padding-left:4px;"><?=$lang["purchase_qty"]?></td>
		<td height="20">&nbsp;</td>
	</tr>
<?php
	$soldprice = array();
	$i = 0;
	foreach($order_item_list as $obj)
	{
?>
	<tr height="20" class="row<?=$i%2?>">
		<td></td>
		<td style="padding-left:10px;"><?=$obj->get_item_sku()?></td>
		<td style="padding-left:10px;"><?=$obj->get_name()?></td>
		<td style="padding-left:10px;"><?=number_format($obj->get_unit_price() / (1 - $obj->get_discount() / 100),2)?></td>
		<td style="padding-left:10px;"><?=$obj->get_unit_price()?></td>
		<td style="padding-left:10px;"><?=$obj->get_qty()?></td>
		<td></td>
	</tr>
<?php
		$soldprice[$obj->get_item_sku()] = $obj->get_unit_price();
		$i++;
	}
?>
	</table>
	</td>
</tr>
</table>
</div>
<table border="0" cellpadding="0" cellspacing="0" class="page_header" width="100%">
<tr height="20">
	<td>&nbsp;</td>
</tr>
</table>
<!-- setting up compensation request -->
<form name="fm" method="POST" onSubmit="CheckForm(this);" >
<table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#000033">
<tr>
	<td align="left" height="20" style="padding-left:10px;"><font style="font-size:12px; color:#ffffff; font-weight:bold;"><?=$lang["compensation_detail"]?></font></td>
</tr>
<table id="tb_list_item" border="0" cellpadding="0" cellspacing="1" bgcolor="#cccccc" width="100%">
<col width="1%"><col width="18%"><col width="40%"><col width="10%"><col width="10%"><col width="1%">
<tr class="header">
	<td height="20">&nbsp;</td>
	<td style="padding-left:4px;"><?=$lang["item_sku"]?></td>
	<td style="padding-left:4px;"><?=$lang["prod_name"]?></td>
	<td style="padding-left:4px;"><?=$lang["selling_price"]?></td>
	<td style="padding-left:4px;"><?=$lang["qty"]?></td>
	<td>&nbsp;</td>
</tr>
<tr class="row1">
	<td height="20"><input type="hidden" id="compensate_sku" name="compensate_sku" value="" DISABLED></td>
	<td style="padding-left:4px;" id="csku"><?=$compensate_obj->get_item_sku()?></td>
	<td style="padding-left:4px;" id="cname"><?=$compensate_obj->get_prod_name()?></td>
	<td style="padding-left:4px;" id="cprice"><?=$compensate_obj->get_currency_id() . " " . (($compensate_obj->get_price()) ? $compensate_obj->get_price():"0, no ref list price") ?></td>
	<td style="padding-left:4px;" id="cqty">1</td>
	<td>&nbsp;</td>
</tr>
</table>
</table>
<table border="0" cellpadding="0" cellspacing="1" width="100%" bgcolor="#cccccc">
<tr>
	<td width="30%" height="20" align="right" style="padding-right:10px;" class="field"><?=$lang["compensation_notes"]?></td>
	<td width="70%" align="left" style="padding-left:10px;" class="value"><textarea id="cnotes" name="cnotes" class="input" rows="5"></textarea></td>
</tr>
<tr height="25">
	<td width="30%" height="20" align="right" style="padding-right:10px;" class="field">&nbsp;</td>
	<td width="70%" align="right" style="padding-left:10px;" class="value">&nbsp;
	<input type="button" style="width:120px" value="<?=$lang["approve"]?>" onClick="if(CheckForm(this.form)) { document.getElementById('action').value = 'A'; document.fm.submit(); }">&nbsp;
	<input type="button" style="width:120px" value="<?=$lang["reject"]?>" onclick="if(checkNote() && CheckForm(this.form)) { document.getElementById('action').value = 'D'; document.fm.submit(); }">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</td>
</tr>
</table>
<input type="hidden" name="orderid" value="<?=$orderobj->get_so_no()?>">
<input type="hidden" name="posted" value="1">
<input type="hidden" id="action" name="action" value="">
</form>

<table border="0" cellpadding="0" cellspacing="0" class="page_header" width="100%">
<tr height="30" bgcolor="#000033">
<?php
if(check_app_feature_access_right($app_id, "CS000405_back_to_list_btn"))
{
?>
	<td style="padding-left:10px;"><input type="button" value="<?=$lang["back_to_list"]?>" onClick="Redirect('<?=base_url()."cs/compensation/manager_approval/?".$_SESSION["QUERY_STRING"]?>');"></td>
</tr>
<?php
}
?>
<tr height="20">
	<td>&nbsp;</td>
</tr>
</table>
</div>
<?=$notice["js"]?>
</body>
</html>