<html>
<head>
<meta http-equiv="Content-Language" content="en-gb">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="keywords" content="">
<title><?=$lang["title"]?></title>
<meta http-equiv="imagetoolbar" content="no">
<link rel="stylesheet" type="text/css" href="<?=base_url().'css/style.css'?>">
<script language="javascript">
<!--
function goToView(value)
{
	if(value != "")
	{
		document.location.href = '<?=base_url()?>mastercfg/latency/view/'+value;
	}
}
-->
</script>
</head>

<body topmargin="0" leftmargin="0">
<div id="main">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
<td height="30" class="title"><?=$lang["title"]?></b></td>
</tr>
<tr>
<td height="2" bgcolor="#000033"></td>
</tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
<tr>
<td height="70" style="padding-left:8px">
<b style="font-size:14px"><?=$lang["header"]?></b><br>
<?=$lang["header_message"]?> <select onChange="goToView(this.value)" style="width:300px;"><option value=""> <?=$lang["please_select"]?></option><?php
	foreach($selling_platform_list as $obj)
	{
	?><option value="<?=$obj->get_id()?>"><?=$obj->get_id().' - '.$obj->get_name()?></option><?php
	}
?></select>
</td>
</tr>
</table>
</div>
</body>
</html>