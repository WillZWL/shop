<html>
<head>
<meta http-equiv="Content-Language" content="en-gb">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="keywords" content="">
<title><?=$lang["title"]?></title>
<meta http-equiv="imagetoolbar" content="no">
<link rel="stylesheet" type="text/css" href="<?=base_url()."css/style.css"?>">
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>
</head>
<body topmargin="0" leftmargin="0" >
<div id="main">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
	<td height="30" class="title"><b style="font-size:16px;color:#000000"><?=$lang["subtitle"]?></b></td>
	<td width="400" align="right" class="title"><input type="button" name="banlist" value="<?=$lang["banner_list"]?>" style="font-size:11px;width:120px" onclick="self.location.href='<?=base_url()."marketing/adbanner/"?>'">&nbsp;<input type="button" name="addbanner" value="<?=$lang["create_banner"]?>" style="font-size:11px;width:120px" onclick="<?=base_url()."marketing/adbanner/upload"?>'"></td>
</tr>
<tr>
	<td height="2" bgcolor="#000033"></td>
	<td height="2" bgcolor="#000033"></td>
</tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" height="70" bgcolor="#BBBBFF" width="100%">
<tr>
	<td height="70" style="padding-left:8px"><b style="font-size:14px"><?=$lang["title"]?></b><br><?=$lang["subheader"]?><br><?=$lang["accept1"]." ".$type." ".$lang["accept2"]?></td>
</tr>
</table>
<table border="0" cellpadding="0" cellspacing="1" height="20" bgcolor="#000000" width="100%">
<tr>
	<td width="150" height="20" bgcolor="#666666"><font color="#FFFFFF"><b>&nbsp;&nbsp;<?=$lang["create_new_banner"]?></b></font></td>
</tr>
</table>
<?= form_open_multipart('marketing/adbanner/upload',array("name"=>"addbanner","style"=>"padding:0; margin:0"));?>
<input type="hidden" name="create" value="yes">
<input type="hidden" name="fromtemp" value="1">
<input type="hidden" name="preview" value="0">
<input type="hidden" name="tempback" value="">
<input type="hidden" name="tempfore" value="">
<table border="0" cellpadding="4" cellspacing="1" bgcolor="#BBBBFF" width="100%">
<tr>
	<td width="142" valign="top" bgcolor="#DDDDFF"><?=$lang["banner_layout"]?></td>
	<td valign="top" bgcolor="#EEEEFF">
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="160" id="bcell" align="center">
<?php  if($image != "")
	{
?>
		<img src="<?=$image?>" border="0" alt="Banner" id="foreground" name="foreground">
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
	</td>
</tr>
<tr>
	<td height="20" bgcolor="#DDDDFF"><?=$lang["foreground"]?></td>
	<td valign="top" bgcolor="#EEEEFF">
	<input type="file" accept="jpg/gif/png" name="imagefile" style="margin:0px;padding:0px;width:250px;font-size:11px">&nbsp;&nbsp;<?=$lang["max_height"]?> :<?=($max_width?$max_width."px":" No Limit")?>, <?=$lang["max_height"]?>:<?=($max_height?$max_height."px":" No Limit")?>
	</td>
</tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" height="40" bgcolor="#BBBBFF" width="100%">
<tr>
	<td width="50%" style="padding-left:8px"><input type="submit" name="submit2" value="Preview Banner"		onclick="document.addbanner.create.value=0;document.addbanner.preview.value=1" style="font-size:11px"></td>
	<td align="right" style="padding-right:8px"><input type="submit" name="submit" value="Upload Banner Images" style="font-size:11px"></td>
</tr>
</table>
</form>
</div>
</body>
</html>