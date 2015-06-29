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
<form action="<?=$_SERVER["PHP_SELF"]?>" method="post" name="tform" style="padding:0; margin:0">
<input type="hidden" name="id" value="<?=$id?>">
<?php
    }
?>
<table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">

<tr>
<td height="70" style="padding-left:8px">
<b style="font-size:14px"><?=$lang["header"]?></b><br>
<?=$lang["header_message"]?><select onChange="goToView(this.value)" style="width:300px;"><option value=""> -- <?=$lang["please_select"]?> --</option><?php
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
<td height="20" width="150"><b>&nbsp;&nbsp;<?=$lang["latency_type"]?></b></td>
<td ><b>&nbsp;&nbsp;<?=$lang["latency_value"]?></b></td>
</tr>
<tr>
<td width="150" class="field">&nbsp;&nbsp;<?=$lang["in_stock"]?></td>
<td height="20" class="value">&nbsp;&nbsp;<input type="text" name="in_stock" value="<?=$profit_obj->get_latency_in_stock()?>" style="font-size:11px;width:60px" <?=(!$editable?"readonly":"")?>></td>
</tr>
<tr>
<td width="150" class="field">&nbsp;&nbsp;<?=$lang["out_of_stock"]?></td>
<td height="20" class="value">&nbsp;&nbsp;<input type="text" name="out_of_stock" value="<?=$profit_obj->get_latency_out_of_stock()?>" style="font-size:11px;width:60px" <?=(!$editable?"readonly":"")?>></td>
</tr>
</table>
<?php
    if($editable)
    {
?>
<table border="0" cellpadding="0" cellspacing="0" height="40" class="page_header" width="100%">
<tr>
<td align="right" style="padding-right:8px"><input type="submit" name="submit" value="<?=$lang["update_latency"]?>" style="font-size:11px"></td>
</tr>
</table>
<input type="hidden" name="type" value="<?=$action?>">
</form>
<?php
    }
?>
<?php
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
</body>
</html>
