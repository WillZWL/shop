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
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>

<td height="30" class="title"><b style="font-size:16px;color:#000000"><?=$lang["title"]?></b></td>
</tr>
<tr>
<td height="2" bgcolor="#000033"></td>
</tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">

<tr>
<td height="70" style="padding-left:8px">
<b style="font-size:14px"><?=$lang["header"]?></b><br>
<?=$lang["header_message"]?> <select onChange="changeBaseCurrency(this.value)" style="width:300px;"><option value=""> -- <?=$lang["please_select"]?> -- </option><?php
    foreach($currency_list as $key=>$value)
    {
?>
        <option value="<?=$key?>"><?=$value?></option>
<?php
    }
?></select>
</td>
</tr>
</table>
</div>
</form>
</body>
</html>