<html>
<head>
<title><?=$lang["title"]?></title>
<STYLE type="text/css">
.button3{
WIDTH: 170px;}
</STYLE>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/calendar.js"></script>
<link rel="stylesheet" href="<?=base_url()?>css/calendar.css" type="text/css" media="all"/>
</head>
<body onResize="SetFrameFullHeight(document.getElementById('report'));">
<div id="main">
<?=$notice["img"]?>
<table cellpadding="0" cellspacing="0" width="100%" border="0">
<tr>
	<td align="left" class="title" height="30"><b style="font-size: 16px; color: rgb(0, 0, 0);"><?=$lang["title"]?></b></td>
</tr>
<tr>
	<td height="2" bgcolor="#000033"></td>
</tr>
</table>
<form name="fm" action="<?=base_url()."report/version_comparison_report/query"?>" method="post" target="report">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="page_header">
<tr height="70">
	<td align="left" style="padding-left:8px;">
		<b style="font-size: 14px; color: rgb(0, 0, 0);"><?=$lang["header"]?></b><br>
	</td>
	<td align="right">
	<table border="0" cellpadding="0" cellspacing="0" width="400" style="line-height:8px;">
	<col width="140"><col width="160"><col width="40">
	<tr>
		<td colspan="2" align="right" style="padding-top:5px; padding-right:8px;"><input type="button" value="<?=$lang["export_csv"]?>" class="button3" onClick="if (CheckForm(this.form)){this.form.submit();}"><input type="hidden" name="posted" value="1"></td>
	</tr>
	</table>
	</td>
</tr>
<tr>
	<td height="2" bgcolor="#000033" colspan="3"></td>
</tr>
</table>
</form>
<iframe name="report" id="report" src="<?=base_url()?>report/skype_report/query" width="1259" style="float:left;border-right:1px solid #000000;" noresize frameborder="0" marginwidth="0" marginheight="0" hspace=0 vspace=0 onLoad="SetFrameFullHeight(this)"></iframe>

<?=$notice["js"]?>
</div>
</body>
</html>