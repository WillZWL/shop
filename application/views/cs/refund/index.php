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
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="30" class="title"><?=$lang["title"]?></td>
		<td width="400" align="right" class="title"></td>
	</tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
	<tr>
		<td height="70" style="padding-left:8px"><b style="font-size:14px"><?=$lang["header"]?></b><br><?=$lang["header_message"]?></td>
	</tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="page_header">
<tr height="30">
	<td style="padding-left:20px;"><input class="button" type="button" onClick="Redirect('<?=base_url()?>cs/refund/create/')" value="<?=$lang["create"]?>"></td>
	<td style="padding-left:20px;"><?=$lang["create_desc"]?></td>
</tr>
<?php
if(check_app_feature_access_right($app_id, "CS000200_log_btn"))
{
?>
<tr height="30">
	<td style="padding-left:20px;"><input class="button" type="button" onClick="Redirect('<?=base_url()?>cs/refund/logistics')" value="<?=$lang["logistic"]?>"></td>
	<td style="padding-left:20px;"><?=$lang["logistic_desc"]?></td>
</tr>
<?php
}
?>
<?php
if(check_app_feature_access_right($app_id, "CS000200_cs_btn"))
{
?>

<tr height="30">
	<td style="padding-left:20px;"><input class="button" type="button" onClick="Redirect('<?=base_url()?>cs/refund/cs')" value="<?=$lang["cs"]?>"></td>
	<td style="padding-left:20px;"><?=$lang["cs_desc"]?></td>
</tr>
<?php
}
?>
<?php
if(check_app_feature_access_right($app_id, "CS000200_acc_btn"))
{
?>

<tr height="30">
	<td style="padding-left:20px;"><input class="button" type="button" onClick="Redirect('<?=base_url()?>cs/refund/account')" value="<?=$lang["account"]?>"></td>
	<td style="padding-left:20px;"><?=$lang["account_desc"]?></td>
</tr>
<?php
}
?>
<!--tr height="30">
	<td style="padding-left:20px;"><input class="button" type="button" onClick="Redirect('<?=base_url()?>cs/refund/report/')" value="<?=$lang["report"]?>"></td>
	<td style="padding-left:20px;"><?=$lang["report_desc"]?></td>
</tr-->
<tr>
	<td colspan="2"><hr></td>
</tr>
<?php
if(check_app_feature_access_right($app_id, "CS000200_refund_btn"))
{
?>
<tr height="40">
	<td style="padding-left:20px;"><input class="button" type="button" onClick="Redirect('<?=base_url()?>cs/refund/reason/')" value="<?=$lang["reason"]?>"></td>
	<td style="padding-left:20px;"><?=$lang["reason_desc"]?></td>
</tr>
<?php
}
?>
</table>
</div>
</body>
</html>