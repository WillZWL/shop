<html>
<head>
<meta http-equiv="Content-Language" content="en-gb">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="keywords" content="">
<title><?=$lang["title"]?></title>
<meta http-equiv="imagetoolbar" content="no">
<link rel="stylesheet" type="text/css" href="<?=base_url()."css/style.css"?>">
<script language="javascript">
<!--
function changeCellImage(a,b,c)
{
	if(c != "")
	{
		document.getElementById(a).innerHTML = '<img src="'+b+c+'" border=0>';
	}
	else
	{
		document.getElementById(a).innerHTML = "";
	}
}
-->
</script>
</head>

<body topmargin="0" leftmargin="0" >
<div id="main">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
	<td height="30" class="title"><b style="font-size:16px;color:#000000"><?=$lang["subtitle"]?></b></td>
	<td width="400" align="right" class="title"><input type="button" name="banlist" value="<?=$lang["banner_list"]?>" style="font-size:11px;width:120px" onclick="self.location.href='<?=base_url()."marketing/adbanner/"?>'">&nbsp;<input type="button" name="addbanner" value="<?=$lang["create_banner"]?>" style="font-size:11px;width:120px" onclick="self.location.href='<?=base_url()."marketing/adbanner/upload"?>'"></td>
</tr>
<tr>
	<td height="2" bgcolor="#000033"></td>
	<td height="2" bgcolor="#000033"></td>
</tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" height="70" bgcolor="#BBBBFF" width="100%">
<tr>
	<td height="70" style="padding-left:8px"><b style="font-size:14px"><?=$lang["title"]?></b><br><?=$lang["subheader"]?></td>
</tr>
</table>
<table border="0" cellpadding="0" cellspacing="1" height="20" bgcolor="#000000" width="100%">
<tr>
	<td width="150" height="20" bgcolor="#666666"><font color="#FFFFFF"><b>&nbsp;&nbsp;<?=$lang["pagename"]?></b></font></td>
	<td bgcolor="#666666"><font color="#FFFFFF"><b>&nbsp;&nbsp;<?=$lang["banner_detail"]?></b></font></td>
</tr>
</table>
<form name="bannerlist" action="<?=$_SERVER["PHP_SELF"]?>" method="POST">
<table cellspacing="1" cellpadding="4" border="0" bgcolor="#bbbbff" width="100%">
<?php
	$image_dir = base_url()."images/adbanner/";
	foreach($adbanner_list as $obj)
	{
?>
<tr>
	<td bgcolor="#ddddff" width="142" valign="top"><?=($obj->get_cat_id()!= 0?$obj->get_name():$lang["top_page"])?></td>
	<td bgcolor="#eeeeff" valign="top">
	<table cellspacing="0" cellpadding="0" border="0" width="100%">
	<tr>
		<td id="bcell<?=$obj->get_id()?>" height="160" align="center">
<?
	if($obj->get_bannerfile() != "")
	{
?>
		<?=($obj->get_bannerlink() != ''?'<a href="'.$obj->get_bannerlink().'" target="_blank">':'')?><img  src="<?=base_url()."images/adbanner/".$obj->get_bannerfile()?>" border="0"><?=($obj->get_bannerlink() != ''?"</a>":"")?>
<?php
	}
	else
	{
		echo "&nbsp;";
	}

?>

		</td>
	</tr>
	</table>
	<div style="margin-top:5px;">
	<span style="padding-right:15px;"><?=$lang["foreground"]?>&nbsp;&nbsp;<select name="bimage<?=$obj->get_id()?>" onChange='changeCellImage("bcell<?=$obj->get_id()?>","<?=$image_dir?>",this.value)' style="width:200px;"><option value="">-- <?=$lang["not_using"]?> --</option><?php

		foreach($option_list as $value)
		{
			if($value != "temp")
			{
?><option value="<?=$value?>" <?=($obj->get_bannerfile() == $value?"SELECTED":"")?>><?=$value?></option><?php
			}
		}
	?></select>&nbsp;&nbsp;<?=$lang["ilink"]?></span><input name="link<?=$obj->get_id()?>" value="<?=$obj->get_bannerlink()?>" style="width:200px;"><input type="hidden" name="id[]" value="<?=$obj->get_id()?>">
	</div>
	</td>
</tr>
<?php

	}
?>
</table>
<table border="0" cellpadding="0" cellspacing="0" height="40" bgcolor="#BBBBFF" width="100%">
<tr>
	<td align="right" style="padding-right:8px"><input type="submit" name="submit" value="<?=$lang["update_banner"]?>" style="font-size:11px"></td>
</tr>
</table>
<input type="hidden" name="posted" value="1">
</form>
<?php
	if($update)
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