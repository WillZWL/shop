<html>
	<head>
		<title><?=$lang["title"]?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
		<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
	</head>

	<body>
		<div id="main">
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td height="30" class="title"><?=$lang["title"]?></td>
					<td width="600" align="right" class="title">
					</td>
				</tr>
				<tr>
					<td height="2" class="line"></td>
					<td height="2" class="line"></td>
				</tr>
			</table>

			<form name="fm" action="<?=base_url()."account/product_category_report/get_csv"?>" method="post" target="_self">
				<input type="hidden" name="search" value="1">

				<table cellpadding="0" cellspacing="0" border="0" width="100%" class="page_header">
					<tr height="70">
						<td align="left" style="padding-left:8px;"><b style="font-size: 14px; color: rgb(0, 0, 0);"><?=$lang["header"]?></b><br><?=$lang["header_message"]?></td>
						<td align="right">
							<input type="submit" value="" class="search_button" style="background: url('<?=base_url()?>/images/find.gif') #CCCCCC no-repeat center; width: 30px; height: 25px;"> &nbsp;
						</td>
					</tr>
					<tr>
						<td height="2" bgcolor="#000033" colspan="3"></td>
					</tr>
				</table>
			</form>
		</div>
	</body>
</html>