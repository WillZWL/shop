<html>
<head>
<title><?=$lang["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>
</head>
<body>
<div id="main">
<?=$notice["img"]?>
<!--table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <td class="title"><?=$lang["title"]?></td>
</tr>
</table-->
<div style="padding:5px; text-align:left;">
<img src="<?=base_url()."images/DPDheadimg.jpg"?>" border="0">
</div>
<form name="fm" method="POST" enctype="multipart/form-data" onsubmit="return CheckForm(this)">
<table border="0" cellpadding="0" cellspacing="1" bgcolor="#cccccc" width="100%">
<tr height="20">
    <td width="30%" class="field" align="right" style="padding-right:10px;"><?=$lang["file_to_be_uploaded"]?><br></td>
    <td width="70%" class="value" align="left" style="padding-left:10px;"><input type="file" class="input" name="upload_file" notEmpty></td>
</tr>
<tr>
    <td class="field" align="right" style="padding-right:10px;">&nbsp;</td>
    <td class="value" align="left" style="padding-left:10px;"><input type="button" value="<?=$lang["upload"]?>" onClick="if(CheckForm(this.form)) document.fm.submit();"></td>
</td>
</table>
<input type="hidden" name="posted" value="1">
</form>
</div>
</body>
<?=$notice["js"]?>
</html>