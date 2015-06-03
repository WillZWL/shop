<html>
<head>
<title><?=$lang["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>
<script language="javascript">
<!--
/*function prepareSubmit()
{
	var keyword = document.getElementById('prod_name').value;
	var sku = document.getElementById('psku').value;
	plistframe.list.keyword.value = keyword;
	plistframe.list.sku.value = sku;
	plistframe.list.submit();
}*/
-->
</script>
</head>
<?php
$today = getdate();
?>
<body onResize="SetFrameFullHeight(document.getElementById('report'));">
<div id="main">
<table cellpadding="0" cellspacing="0" width="100%" border="0">
<tr>
	<td align="left" class="title" height="30"><b style="font-size: 16px; color: rgb(0, 0, 0);"><?=$lang["title"]?></b></td>
	<td align="right" class="title"  >
		<input type="button" value="<?=$lang["title"]?>" class="button" onclick="Redirect('<?=base_url()."report/sales_report/index"?>')">&nbsp;
		<input type="button" value="<?=$lang["title_split_orders"]?>" class="button" onclick="Redirect('<?=base_url()."report/sales_report/split_orders_report"?>')">&nbsp;
	</td>

</tr>
<tr>
	<td height="2" bgcolor="#000033" colspan="2"></td>
</tr>
</table>
<form name="fm" action="<?=base_url()."report/$controller/query"?>" method="post" target="report">
<input type="hidden" name="is_query" value="1">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="page_header">
<tr height="70">
	<td align="left" style="padding-left:8px;"><b style="font-size: 14px; color: rgb(0, 0, 0);"><?=$lang["header"]?></b><br><?=$lang["header_message"]?></td>
	<td align="right">
	<table border="0" cellpadding="2" cellspacing="0">
	<col width="120"><col width="120"><col width="100"><col width="100"><col width="100"><col width="100"><col width="140"><col width="200"><col width="40">
	<tr>
		<td align='right'>Report type:</td>
		<td>
			<select name="clearance">
				<option>All sales</option>
				<option value="clearance">Clearance sales only</option>
				<option value="exclude_negative_clearance">All sales except negative profit clearance</option>
			</select>


		</td>
		<td align='right'><?=$lang["payment_gateway"]?></td>
		<td>
			<select name='payment_gateway' id='payment_gateway'>
				<option value='-1'>-- Please select --</option>
<?php
				foreach($gateways as $gateway)
				{
					print "<option value='" . $gateway->get_id() . "'>" . $gateway->get_name(). "</option>";
				}
?>
			</select>
		</td>
		<td align='right'><?=$lang["currency"]?></td>
		<td>
			<select name='currency' id='currency'>
				<option value='-1'>-- Please select --</option>
<?php
				foreach($currencys as $currency)
				{
					print "<option value='" . $currency->get_currency_id() . "'>" . $currency->get_currency_id(). "</option>";
				}
?>
			</select>
		</td>
		<td align='right'><b><?=$lang["start_date"]?></b></td>
		<td><b><select name="from_day">
<?php
	for ($i = 1; $i <= 31; $i++)
	{
?>
					<option value="<?php echo ($i<10)? "0$i" : "$i";?>" <?php echo ($i == $today['mday']) ? "selected" : "";?>>
						<?php echo $i;?>
					</option>
<?php
	}
?>
				</select>
				<select name="from_month">
<?php
	for ($i = 1; $i <= 12; $i++)
	{
?>
					<option value="<?php echo ($i<10)? "0$i" : "$i";?>" <?php echo ($i == $today['mon']) ? "selected" : "";?>>
						<?php echo $i;?>
					</option>
<?php
	}
?>
				</select>
				<select name="from_year">
<?php
	$this_year = $today['year'];

	$start = $this_year - 3;
	$end = $this_year + 3;

	for ($i = $start; $i <= $end; $i++)
	{
?>
					<option value="<?php echo $i;?>" <?php echo ($i == $this_year) ? "selected" : "";?>>
						<?php echo $i;?>
					</option>
<?php
	}
?>
				</select>
			</b>
		</td>
		<td rowspan="2" align="center"><input type="submit" value="" class="search_button" style="background: url('<?=base_url()?>/images/find.gif') #CCCCCC no-repeat center; width: 30px; height: 25px;"> &nbsp; </td>
	</tr>
	<tr>
		<?if($controller == "dispatch_report"){?>
			<td colspan="1"></td>
			<td><a href="<?=$controller?>/no_finance_dispatch_report" title="check orders (2014-04-01 afterwards) that are LOGISTICS dispatched but no Financially dispatched. ">Checking</a></td>
		<?}else{?>
			<td>
				<input type="hidden" name="is_sales_rpt" id="is_sales_rpt" value="1">
			</td>
			<td>
				 <input type="checkbox" value=1 name="light_version" style="vertical-align:middle;padding:0;margin:0 5px 0 0;"> Light Version Sales Report
			</td>

		<?}?>
		<td align='right'><?=$lang["country"]?></td>
		<td>
			<select name='country' id='country'>
				<option value='-1'>-- Please select --</option>
<?php
				foreach($countrys as $country)
				{
					print "<option value='" . $country->get_id() . "'>" . $country->get_name(). "</option>";
				}
?>
			</select>
		</td>
		<td><?=$lang["is_china_oem"]?></td>
		<td>
			<select name="china_oem" id="china_oem">
				<option value="-1"><?=$lang["include"]?></option>
				<option value="0"><?=$lang["exclude"]?></option>
				<option value="1"><?=$lang["only_show"]?></option>
			</select>
		</td>
		<td align='right'><b><?=$lang["end_date"]?></b></td>
		<td>
				<select name="to_day">
<?php
	for ($i = 1; $i <= 31; $i++)
	{
?>
					<option value="<?php echo ($i<10)? "0$i" : "$i";?>" <?php echo ($i == $today['mday']) ? "selected" : "";?>>
						<?php echo $i;?>
					</option>
<?php
	}
?>
				</select>
				<select name="to_month">
<?php
	for ($i = 1; $i <= 12; $i++)
	{
?>
					<option value="<?php echo ($i<10)? "0$i" : "$i";?>" <?php echo ($i == $today['mon']) ? "selected" : "";?>>
						<?php echo $i;?>
					</option>
<?php
	}
?>
				</select>
				<select name="to_year">
<?php
	$this_year = $today['year'];

	$start = $this_year - 3;
	$end = $this_year + 3;

	for ($i = $start; $i <= $end; $i++)
	{
?>
					<option value="<?php echo $i;?>" <?php echo ($i == $this_year) ? "selected" : "";?>>
						<?php echo $i;?>
					</option>
<?php
	}
?>
				</select>

		</td>
	</tr>
	</table>
	</td>
</tr>
<tr>
	<td height="2" bgcolor="#000033" colspan="3"></td>
</tr>
</table>
</form>
<iframe name="report" id="report" src="<?=base_url()."report/$controller/query"?>" width="1259" style="float:left;border-right:1px solid #000000;" noresize frameborder="0" marginwidth="0" marginheight="0" hspace=0 vspace=0 onLoad="SetFrameFullHeight(this)"></iframe>
</div>
</body>
</html>