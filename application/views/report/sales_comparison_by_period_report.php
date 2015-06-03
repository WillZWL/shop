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
<form name="fm" action="<?=base_url()."report/sales_comparison_by_period_report/query"?>" method="post" target="report">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="page_header">
<tr height="70">
	<td align="left" style="padding-left:8px;">
		<b style="font-size: 14px; color: rgb(0, 0, 0);"><?=$lang["header"]?></b><br>
	</td>
	<td align="right">
	<table border="0" cellpadding="0" cellspacing="0" width="700" style="line-height:8px;">
	<col width="140"><col width="160"><col width="140"><col width="160"><col width="40">
	<tr>
		<td><b><?=$lang["from_date1"]?></b></td>
		<td><input name="from_date1" value='<?=htmlspecialchars($start_date)?>' notEmpty><img src="/images/cal_icon.gif" class="pointer" onclick="showcalendar(event, document.fm.from_date1, false, false, false, '2010-01-01')" align="absmiddle"></td>
		<td><b><?=$lang["to_date1"]?></b></td>
		<td><input name="to_date1" value='<?=htmlspecialchars($end_date)?>' notEmpty><img src="/images/cal_icon.gif" class="pointer" onclick="showcalendar(event, document.fm.to_date1, false, false, false, '2010-01-01')" align="absmiddle"></td>
		<td rowspan="2" align="center"> &nbsp; </td>
	</tr><!--
	<tr>
		<td><b><?=$lang["to_date1"]?></b></td>
		<td><input name="to_date1" value='<?=htmlspecialchars($end_date)?>' notEmpty><img src="/images/cal_icon.gif" class="pointer" onclick="showcalendar(event, document.fm.to_date1, false, false, false, '2010-01-01')" align="absmiddle"></td>
	</tr>
	--><tr>
		<td><b><?=$lang["from_date2"]?></b></td>
		<td><input name="from_date2" value='<?=htmlspecialchars($start_date)?>' notEmpty><img src="/images/cal_icon.gif" class="pointer" onclick="showcalendar(event, document.fm.from_date2, false, false, false, '2010-01-01')" align="absmiddle"></td>
		<td><b><?=$lang["to_date2"]?></b></td>
		<td><input name="to_date2" value='<?=htmlspecialchars($end_date)?>' notEmpty><img src="/images/cal_icon.gif" class="pointer" onclick="showcalendar(event, document.fm.to_date2, false, false, false, '2010-01-01')" align="absmiddle"></td>
		<td rowspan="2" align="center"><input type="submit" value="" class="search_button" style="background: url('<?=base_url()?>/images/find.gif') #CCCCCC no-repeat center; width: 30px; height: 25px;"><input type="hidden" name="post" value="1"> &nbsp; </td>
	</tr><!--
	<tr>
		<td><b><?=$lang["to_date2"]?></b></td>
		<td><input name="to_date2" value='<?=htmlspecialchars($end_date)?>' notEmpty><img src="/images/cal_icon.gif" class="pointer" onclick="showcalendar(event, document.fm.to_date2, false, false, false, '2010-01-01')" align="absmiddle"></td>
	</tr>
	--></table>
	</td>
</tr>
<tr>
	<td height="2" bgcolor="#000033" colspan="3"></td>
</tr>
</table>
</form>
<iframe name="report" id="report" src="<?=base_url()?>report/sales_comparison_by_period_report/query" width="1259" style="float:left;border-right:1px solid #000000;" noresize frameborder="0" marginwidth="0" marginheight="0" hspace=0 vspace=0 onLoad="SetFrameFullHeight(this)"></iframe>

<?=$notice["js"]?>
</div>
</body>
</html>