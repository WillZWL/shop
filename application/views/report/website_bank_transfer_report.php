<html>
<head>
<title><?=$lang["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>

<script type="text/javascript" src="<?=base_url()?>marketing/product/js_catlist"></script>
<script type="text/javascript" src="<?=base_url()?>mastercfg/brand/js_brandlist"></script>
<script type="text/javascript" src="<?=base_url()?>supply/supplier_helper/js_supplist"></script>
<script type="text/javascript" src="<?=base_url()?>js/calendar.js"></script>
<link rel="stylesheet" href="<?=base_url()?>css/calendar.css" type="text/css" media="all"/>

<script language="javascript" type="text/javascript">
	needToConfirm = false;
	window.onbeforeunload = askConfirm;
	function askConfirm()
	{
		if (needToConfirm){
			return "<?=$lang["usaved_changes"]?>";
		}
	}
</script>
</head>
<body>
<div id="main">
<?=$notice["img"]?>
<?php
	$ar_ws_status = array("I" => $lang["instock"], "O" => $lang["outstock"], "P" => $lang["pre-order"], "A" => $lang["arriving"]);
	$ar_src_status = array("A" => $lang["available"], "C" => $lang["stock_constraint"], "O" => $lang["temp_out_of_stock"], "L" => $lang["last_lot"], "D" => $lang["discontinued"]);
	$ar_ls_status = array("L" =>$lang["listed"], "N"=>$lang["not_listed"]);
?>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="30" class="title"><?=$lang["title"]?></td>
		<td width="400" align="right" class="title"><input type="button" value="<?=$lang["list_button"]?>" class="button" onclick="Redirect('<?=base_url().$this->report_path?>')"></td>
	</tr>
		<td height="2" class="line"></td>
		<td height="2" class="line"></td>
	</tr>
</table>

<form name="fm" method="post">
	<table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
		<col width="150"><col width="420"><col width="170"><col width="420"><col>
		<tr >
			<td style="padding-top:5px;padding-right:8px" align="right"><b><?=$lang["rec_date_from"]?></b> </td>
			<td style="padding-top:5px;"><input name="rec_date_from" id="rec_date_from" value="<?=$now_date?>"><img src="/images/cal_icon.gif" class="pointer" onclick="showcalendar(event, document.getElementById('rec_date_from'), false, false, false, '2010-01-01')" align="absmiddle"></td>
		</tr>
		<tr >
			<td style="padding-top:5px;padding-right:8px" align="right"><b><?=$lang["rec_date_to"]?></b> </td>
			<td style="padding-top:5px;"><input name="rec_date_to" id="rec_date_to" value="<?=$now_date?>"><img src="/images/cal_icon.gif" class="pointer" onclick="showcalendar(event, document.getElementById('rec_date_to'), false, false, false, '2010-01-01')" align="absmiddle"></td>
		</tr>
		<tr >
			<td style="padding-top:5px;padding-right:8px" align="right"><b><?=$lang["order_date_from"]?></b> </td>
			<td style="padding-top:5px;"><input name="order_date_from" id="order_date_from" value=""><img src="/images/cal_icon.gif" class="pointer" onclick="showcalendar(event, document.getElementById('order_date_from'), false, false, false, '2010-01-01')" align="absmiddle"></td>
		</tr>
		<tr >
			<td style="padding-top:5px;padding-right:8px" align="right"><b><?=$lang["order_date_to"]?></b> </td>
			<td style="padding-top:5px;"><input name="order_date_to" id="order_date_to" value=""><img src="/images/cal_icon.gif" class="pointer" onclick="showcalendar(event, document.getElementById('order_date_to'), false, false, false, '2010-01-01')" align="absmiddle"></td>
		</tr>
		<tr>
			<td style="padding-top:5px;padding-right:8px" align="right"><b><?=$lang["bank_acc"]?></b> </td>
			<td style="padding-top:5px;"><select name="bank_acc" id="bank_acc">
				<option></option>
<?php
	foreach ($bank_acc_list as $bank_acc_obj)
	{
?>
				<option value="<?=$bank_acc_obj->get_id()?>"><?=htmlspecialchars($bank_acc_obj->get_acc_no())?></option>
<?php
	}

?>
			</select></td>
		</tr>
		<tr>
			<td></td>
			<td style="padding-top:5px;padding-bottom:5px; padding-right:8px;" align="left"><input type="submit" value="<?=$lang["export_button"]?>"></td>
		<tr >
			<td style="padding-top:5px;"  colspan="5"></td>
		</tr>
		<tr>
			<td height="2" bgcolor="#000033" colspan="5"></td>
		</tr>
	</table>

	<input type="hidden" name="posted" value="1">
</form>
<?=$notice["js"]?>
</div>
<?=$objlist["js"]?>
<script>
InitBrand(document.fm.brand_id);
document.fm.brand_id.value = '<?=$this->input->get("brand_id")?>';
ChangeCat('0', document.fm.cat_id);
document.fm.cat_id.value = '<?=$this->input->get("cat_id")?>';
ChangeCat('<?=$this->input->get("cat_id")?>', document.fm.sub_cat_id);
document.fm.sub_cat_id.value = '<?=$this->input->get("sub_cat_id")?>';


</script>
</body>
</html>