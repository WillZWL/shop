<!DOCTYPE html>
<html>
<head>
<title><?=$lang["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>
<script type="text/javascript">
	function Download(path)
	{
		location.href = path;
	}
</script>
</head>
<body>
<div id="main">
<?=$notice["js"]?>
<?=$notice["img"]?>

	<table border="0" cellpadding="0" cellspacing="0" width="100%"  style='text-align:left'>
		<tr>
			<td height="30" class="title"><?=$lang["title"]?></td>
		</tr>
		<tr>
			<td height="2" class="line"></td>
			<td height="2" class="line"></td>
		</tr>
	</table>
	<form name="uploadForm" method="POST" enctype="multipart/form-data" onsubmit="return CheckForm(this)">
		<table height="70" class="page_header" width="100%" style='text-align:left'>
			<tr>
				<td width="150"><?=$lang["file_type_reminder"]?></td>
				<td><input accept="csv" type="file" class="input" id="courier_process_file" name="courier_process_file" notEmpty></td>
			</tr>
			<tr>
				<td  width="150">&nbsp;</td>
				<td><input type="submit" value="<?=$lang['submit_button']?>"></td>
			</tr>
			<tr>
				<td>CSV format (2 column only):</td>
				<td><div style="font-size:16px;">so_no,recommended courier_id</div></td>
			</tr>
		</table>
		<input type="hidden" name="posted" value='1'>
	</form>
</div>


</body>
</html>
