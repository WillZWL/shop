<?php $editable = false;?>
<html>
<head>
<meta http-equiv="Content-Language" content="en-gb">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="keywords" content="">
<title><?=$lang["title"]?></title>
<meta http-equiv="imagetoolbar" content="no">
<link rel="stylesheet" type="text/css" href="<?=base_url().'css/style.css'?>">
<script language="javascript" src="<?=base_url()?>js/common.js"></script>
<script language="javascript" src="<?=base_url()?>js/checkform.js"></script>
<script language="javascript">
<!--
function changeBaseCurrency(value)
{
	if(value != "")
	{
		document.location.href = '<?=base_url()?>mastercfg/exchange_rate/view/'+value;
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
<form action="<?=$_SERVER["PHP_SELF"]?>" method="post" name="tform" onSubmit="return CheckForm(this)" style="padding:0; margin:0">
<input type="hidden" name="base" value="<?=$base?>">
<?php
	}
?>
<table border="0" cellpadding="0" cellspacing="1" height="70" class="page_header" width="100%">

<tr>
<td height="70" style="padding-left:8px">
<b style="font-size:14px"><?=$lang["header"]?></b><br>
<?=$lang["header_message"]?> <select onChange="changeBaseCurrency(this.value)" style="width:300px;"><option value=""> -- <?=$lang["please_select"]?> -- </option><?php
	foreach($currency_list as $key=>$value)
	{
?>
		<option value="<?=$key?>" <?=($base == $key?"selected":"")?>><?=$value?></option>
<?php
	}
?></select>
</td>
</tr>
</table>
<table border="0" cellpadding="0" cellspacing="1" height="20" class="tb_list" width="100%">
<tr class="header">
<td height="20" width="30%"><b>&nbsp;&nbsp;<?=$lang["currency_prop"]?></b></td>
<td width="10%"><b>&nbsp;&nbsp;<?=@$lang["id"]?></b></td>
<td width="10%"><b>&nbsp;&nbsp;<?=$lang["sign"]?></b></td>
<td width="25%"><b>&nbsp;&nbsp;<?=$lang["current_exchange_rate"]?></b></td>
<td width="25%"><b>&nbsp;&nbsp;<?=$lang["suggested_exchange_rate"]?></b></td>
</tr>
<?php
	$i=0;
	foreach($currency_full_list as $obj){
?>
<tr class="row<?=$i%2?>"<?php if($exchange_rate[$obj->get_id()] != $exchange_rate_approval[$obj->get_id()]) {?>style="BACKGROUND-COLOR: red"<?php }?> >
	<td height="20" width="30%">&nbsp;&nbsp;<?=$obj->get_name()?></td>
	<td height="20" width="10%">&nbsp;&nbsp;<?=$obj->get_id()?></td>
	<td height="20" width="10%">&nbsp;&nbsp;<?=$obj->get_sign()?></td>
	<td height="20" width="25%" align="left">&nbsp;&nbsp;<?=number_format($exchange_rate[$obj->get_id()],6,'.','')?></td>
	<td height="20" width="25%" align="left">&nbsp;&nbsp;<input type="text" name="<?=$obj->get_id()?>" value="<?=number_format($exchange_rate_approval[$obj->get_id()],6,'.','')?>" style="font-size:11px;width:100px" <?=(!$editable || $obj->get_id()==$base?"readonly":"")?> min=0 isNumber></td>
</tr>
<?php
		$i++;
	}
?>
</table>
<?php
	if($editable)
	{
?>
<table border="0" cellpadding="0" cellspacing="0" height="40" class="page_header" width="100%">
<tr>
	<td align="right" width="500"style="padding-right:8px">
	<?php
	if(!($approval))
	{
	?>
	<td align="right" style="padding-right:12px"><font style="color:#ff0000; font-weight:bold; font-size:14px;">SUBMIT FOR APPROVAL</font>
	<?php
	}
	if($type)
	{
	?>
		<input type="button" value="<?=$lang{$type}?>" onClick="if(CheckForm(this.form)) document.tform.submit();" style="font-size:11px">
	<?php
	}
	?>
	</td>

</tr>
</table>
<input type="hidden" name="posted" value="1">
<input type="hidden" name="type" value="<?=$type?>">
</form>
<?php
	}
?>
</div>
<?php
	if($updated)
	{
?>
<script language="javascript">
	alert('<?=$lang["{$type}_submit"]?>');
</script>
<?php
	}
	echo $notice["js"];
?>
</body>
</html>