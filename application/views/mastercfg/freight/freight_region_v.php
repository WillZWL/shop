<html>
<head>
<title><?=$lang["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
</head>
<body>
<div id="main">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="30" class="title"><?=$lang["title"]?></td>
		<td width="400" align="right" class="title"></td>
	</tr>
	<tr>
		<td height="2" class="line"></td>
		<td height="2" class="line"></td>
	</tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
	<tr>
		<td height="70" style="padding-left:8px"><b style="font-size:14px"><?=$lang["region_header"]?></b><br><?=$lang["region_header_message"]?></td>
	</tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_list">
<?php
	if (!empty($objlist))
	{
		foreach ($objlist as $obj)
		{
?>
	<tr class="header">
		<td><?=$obj->get_region_name()?></td>
	</tr>
<?php
			$ar_country = @explode(",", $obj->get_countries());
			$i=0;
			foreach ($ar_country as $country)
			{
?>
	<tr class="row<?=$i%2?>">
		<td><?=$country?></td>
	</tr>
<?php
				$i++;
			}
		}
	}
?>
</table>
</div>
</body>
</html>