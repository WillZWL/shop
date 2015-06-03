<html>
	<head>
		<title><?=$lang["title"]?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
		<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
		<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>

		<script language="javascript">
		<!--
			var rowIndex = 1;

			function addRow(id, sku, name)
			{
				var x=document.getElementById(id).insertRow(rowIndex);

				var a=x.insertCell(0);
				var b=x.insertCell(1);
				var c=x.insertCell(2);
				var d=x.insertCell(3);

				a.setAttribute("class","value");
				b.setAttribute("class","value");
				c.setAttribute("class","value");
				d.setAttribute("class","value");

				a.innerHTML = sku+'<input type="hidden" name="sku[]" value="'+sku+'">';
				b.innerHTML = stripslashes(name);
				c.innerHTML = '<input type="text" name="priority['+sku+']" value="">';
				d.innerHTML = '<input type=\'button\' value="x" onClick=\'deleteRow(this '+','+'"upselling_group_form");\'>';
				rowIndex++;
			}

			function deleteRow(r,id)
			{
				var i=r.parentNode.parentNode.rowIndex;
				document.getElementById(id).deleteRow(i);
				rowIndex--;
			}

			function stripslashes (str) {
				 return (str+'').replace(/\\(.?)/g, function (s, n1) {
					switch (n1) {
						case '\\':
							return '\\';
						case '0':
							return '\0';
						case '':
							return '';
						default:
							return n1;
					}
				});
			}
		-->
		</script>
	</head>

	<body class="frame_left" style="width:auto;">
		<div id="main" style="width:auto;">
			<?=$notice["img"]?>

			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td height="30" class="title"><?=$lang["subtitle"]?></td>
				</tr>
				<tr>
					<td height="2" bgcolor="#000033"></td>
				</tr>
			</table>

			<table border="0" cellpadding="0" cellspacing="0" height="70" width="100%" class="page_header">
				<tr>
					<td height="70" style="padding-left:8px"><b style="font-size:14px"><?=$lang["header"]?></b><br><?=$lang["subheader"]?></td>
					<td width="200" valign="top" align="right" style="padding-right:8px">&nbsp;</td>
				</tr>
			</table>

			<form name="fm" action="<?=base_url()?>marketing/upselling/add_group/" method="POST" onSubmit="CheckForm(this);" target="_parent">
				<input type="hidden" name="posted" value="1">

				<table border="0" cellpadding="0" cellspacing="1" width="100%" class="tb_list">
				<tr>
					<td class="field fieldwidth"><?=$lang["group_name"]?></td>
					<td class="value" colspan="3"><input type="text" name="group_name" value="" class="input" notEmpty></td>
				</tr>
				<tr>
					<td class="field fieldwidth"><?=$lang["warranty"]?></td>
					<td class="value" colspan="3"><input type="checkbox" name="warranty" value="1"></td>
				</tr>
				</table>

				<table border="0" cellspacing="1" cellpadding="0" width="100%" bgcolor="#cccccc">
				<tr>
					<td height="25" bgcolor="#333333" style="padding-left:10px;"><font style="color:#ffffff; font-weight:bold; font-size:12px;"><?=$lang["group_detail"]?></font></td>
				</tr>
				</table>

				<table border="0" cellspacing="1" cellpadding="0" width="100%" id="upselling_group_form" class="tb_list" bgcolor="#cccccc">
					<col width="100"><col><col width="30"><col width="30">
					<tr class="header">
						<td class="offield"><?=$lang["sku"]?></td>
						<td class="offield"><?=$lang["prodname"]?></td>
						<td class="offield"><?=$lang["priority"]?></td>
						<td class="offield" width="30">&nbsp;</td>
					</tr>
				</table>

				<table border="0" cellspacing="1" cellpadding="0" width="100%" bgcolor="#333333">
					<tr>
						<td align="left" style="padding-left:20px;"><input type="button" value="<?=$lang["back"]?>" onClick="parent.location.href = '<?=base_url()."marketing/upselling/group_list/"?>';" class="button"></td>
						<td align="right" height="30" style="padding-right:20px;"><input type="button" value="<?=$lang["submit"]?>" onClick="if(CheckForm(this.form)) this.form.submit();" class="button"></td>
					</tr>
				</table>
			</form>
		</div>
		<?=$notice["js"]?>
	</body>
</html>