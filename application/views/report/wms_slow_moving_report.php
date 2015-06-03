<html>
	<head>
		<title><?=$lang["title"]?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
		<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
		<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>
		<script type="text/javascript" src="<?=base_url()?>js/calendar.js"></script>
		<link rel="stylesheet" href="<?=base_url()?>css/calendar.css" type="text/css" media="all"/>
	</head>

	<body onResize="SetFrameFullHeight(document.getElementById('report'));">
		<div id="main">
			<table cellpadding="0" cellspacing="0" width="100%" border="0">
				<tr>
					<td align="left" class="title" height="30"><b style="font-size: 16px; color: rgb(0, 0, 0);"><?=$lang["title"]?></b></td>
				</tr>
				<tr>
					<td height="2" bgcolor="#000033"></td>
				</tr>
			</table>

			<form name="fm" action="<?=base_url()."report/wms_slow_moving_report/query"?>" method="post" target="report">
				<input type="hidden" name="is_query" value="1">

				<table cellpadding="0" cellspacing="0" border="0" width="100%" class="page_header">
					<tr height="70">
						<td align="left" style="padding-left:8px;">
							<b style="font-size: 14px; color: rgb(0, 0, 0);"><?=$lang["header"]?></b><br><?=$lang["header_message"]?>
						</td>

						<td align="right">
							<table border="0" cellpadding="0" cellspacing="0" width="220">
								<col width="80"><col width="120"><col width="50">
								<tr>
									<td><b><?=$lang["date_after"]?></b></td>
									<td><input name="date_after" value='' size="10" notEmpty><img src="/images/cal_icon.gif" class="pointer" onclick="showcalendar(event, document.fm.date_after, false, false, false, '2010-01-01')" align="absmiddle"></td>

									<td align="center">
										<input type="submit" value="" class="search_button" style="background: url('<?=base_url()?>/images/find.gif') #CCCCCC no-repeat center; width: 30px; height: 25px;"> &nbsp;
									</td>
								</tr>
							</table>
						</td>
					</tr>

					<tr>
						<td height="2" bgcolor="#000033" colspan="2"></td>
					</tr>
				</table>
			</form>
		</div>
	</body>
</html>