<html>
<head>
<meta http-equiv="Content-Language" content="en-gb">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="keywords" content="">
<title><?=$lang["title"]?></title>
<meta http-equiv="imagetoolbar" content="no">
<link rel="stylesheet" type="text/css" href="<?=base_url().'css/style.css'?>">
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>
<script language="javascript">
<!--
function goToView(value)
{
	if(value != "")
	{
		document.location.href = '<?=base_url()?>mastercfg/profit_var/view/'+value;
	}
}
-->
</script>
</head>

<body topmargin="0" leftmargin="0">
<div id="main">
<?=$notice["img"]?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
<td height="30" class="title"><b style="font-size:16px;color:#000000"><?=$lang["title"]?></b></td>
</tr>
<tr>
<td height="2" bgcolor="#000033"></td>
</tr>
</table>

<?php
	if($editable)
    {
?>
<form action="<?=$_SERVER["PHP_SELF"]?>" method="post" name="tform" style="padding:0; margin:0" onSubmit="return CheckForm(this)">
<input type="hidden" name="id" value="<?=$id?>">
<?php
	}
?>
<table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">

<tr>
<td height="70" style="padding-left:8px">
<b style="font-size:14px"><?=$lang["header"]?></b><br>
<?=$lang["header_message"]?><select onChange="goToView(this.value)" style="width:300px;"><option value=""> -- <?=$lang["please_select"]?> -- </option><?php

	foreach($selling_platform_list as $obj)
	{
	?><option value="<?=$obj->get_id()?>" <?=($obj->get_id()==$id?"SELECTED":"")?>><?=$obj->get_id().' - '.$obj->get_name()?></option><?php
	}
?></select>
</td>
</tr>
</table>
<table border="0" cellpadding="0" cellspacing="1" height="20" class="tb_list" width="100%">
<tr class="header">
<td height="20" width="150">&nbsp;&nbsp;<?=$lang["charge_type"]?></td>
<td>&nbsp;&nbsp;<?=$lang["charge_amount"]?></td>
</tr>
<tr>
<td width="150" class="field">&nbsp;&nbsp;<?=$lang["vat_percent"]?></td>
<td height="20" class="value">&nbsp;&nbsp;<input type="text" name="vat" value="<?=$profit_obj->get_vat_percent()?>" style="font-size:11px;width:60px" <?=(!$editable?"readonly":"")?> isNumber min=0>%</td>
</tr>
<tr>
<td width="150" class="field">&nbsp;&nbsp;<?=$lang["payment_chrg_percent"]?></td>
<td height="20" class="value">&nbsp;&nbsp;<input type="text" name="pcp" value="<?=$profit_obj->get_payment_charge_percent()?>" style="font-size:11px;width:60px" <?=(!$editable?"readonly":"")?> isNumber min=0>%</td>
</tr>
<tr>
<td width="150" class="field">&nbsp;&nbsp;<?=$lang["forex_fee_percent"]?></td>
<td height="20" class="value">&nbsp;&nbsp;<input type="text" name="forex_fee_percent" value="<?=$profit_obj->get_forex_fee_percent()?>" style="font-size:11px;width:60px" <?=(!$editable?"readonly":"")?> isNumber min=0>%</td>
</tr>
<tr>
<td width="150" class="field">&nbsp;&nbsp;<?=$lang["admin_fee"]?></td>
<td height="20" class="value">&nbsp;&nbsp;<input type="text" name="admin_fee" value="<?=$profit_obj->get_admin_fee()?>" style="font-size:11px;width:60px" <?=(!$editable?"readonly":"")?> isNumber min=0>&nbsp;<span id="curr"><?=$profit_obj->get_platform_currency_id()?></span></td>
</tr>
<tr>
<td width="150" class="field">&nbsp;&nbsp;<?=$lang["free_delivery_limit"]?></td>
<td height="20" class="value">&nbsp;&nbsp;<?=$lang["above"]?>&nbsp;<input type="text" name="free_dlvry_limit" value="<?=$profit_obj->get_free_delivery_limit()?>" style="font-size:11px;width:60px" <?=(!$editable?"readonly":"")?> isNumber min=0>&nbsp;<span id="curr2"><?=$profit_obj->get_platform_currency_id()?></span></td>
</tr>
<tr>
<td width="150" class="field">&nbsp;&nbsp;<?=$lang["country"]?></td>
<td height="20" class="value">&nbsp;&nbsp;<select name="platform_country_id" class="input" <?=$editable?"":"DISABLED"?>><?php
	foreach($active_country_list as $obj)
	{
	?><option value="<?=$obj->get_id()?>" <?=($obj->get_id() == $profit_obj->get_platform_country_id()?"SELECTED":"")?>><?=$obj->get_name()?></option><?php
	}
?></select></td>
</tr>
<tr>
<td width="150" class="field">&nbsp;&nbsp;<?=$lang["language"]?></td>
<td height="20" class="value">&nbsp;&nbsp;<select name="language_id" class="input" <?=$editable?"":"DISABLED"?>><?php
	foreach($language_list as $obj)
	{
	?><option value="<?=$obj->get_id()?>" <?=($obj->get_id() == $profit_obj->get_language_id()?"SELECTED":"")?>><?=$obj->get_name()?></option><?php
	}
?></select></td>
</tr>
<tr>
<td width="150" class="field">&nbsp;&nbsp;<?=$lang["dlvry_courier"]?></td>
<td height="20" class="value">&nbsp;&nbsp;<select name="delivery_type" class="input" <?=$editable?"":"DISABLED"?>><?php
	foreach($delivery_type_list as $obj)
	{
	?><option value="<?=$obj->get_id()?>" <?=($obj->get_id() == $profit_obj->get_delivery_type()?"SELECTED":"")?>><?=$obj->get_name()?></option><?php
	}
?></select></td>
</tr>
<tr>
<td width="150" class="field">&nbsp;&nbsp;<?=$lang["site_currency"]?></td>
<td height="20" class="value">
&nbsp;&nbsp;<select name="currency" class="input" <?=(!$editable?"disabled":"")?> onChange="document.getElementById('curr').innerHTML=this.value; document.getElementById('curr2').innerHTML=this.value;" <?=$editable?"":"DISABLED"?>>
<?php
		foreach($currency_list as $key=>$value)
		{
?>
	<option value="<?=$key?>" <?=($profit_obj->get_platform_currency_id() == $key?"SELECTED":"")?>><?=$value.'-'.$key?></option>
<?php
		}
?>
	</select>
</td>
</tr>
</table>
<?php
	if($editable)
	{
?>
<table border="0" cellpadding="0" cellspacing="0" height="40" class="page_header" width="100%">
<tr>
<td align="right" style="padding-right:8px"><input type="button" value="<?=$lang["update_var"]?>" style="font-size:11px" onClick="if(CheckForm(this.form)) this.form.submit();"></td>
</tr>
</table>
<input type="hidden" name="type" value="<?=$action?>">
<input type="hidden" name="posted" value="1">
</form>
<?php
	}
	if($updated)
	{
?>
<script language="javascript">
	alert('<?=$lang["update_successful"]?>');
</script>
<?php
	}

?>
</div>
<?=$notice["js"]?>
</body>
</html>
