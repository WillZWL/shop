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

<form name="fm" action="<?=base_url()?>report/competitor_report/query" method="post">
	<table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
		<col width="150"><col width="420"><col width="170"><col width="420"><col>
		<tr>
			<td style="padding-right:8px" align="right"><b><?=$lang["country_id"]?> *</b> </td>
			<td colspan='3'>
			<select name="country_id" onChange="Redirect('<?=base_url()?>report/competitor_report/index/'+this.value)">
			<option></option>
			<?

				foreach($country_list as $country_obj)
				{
			?>
			<option value="<?=$country_obj->get_id()?>" <?=$country_id == $country_obj->get_id()?"SELECTED":""?>><?=$country_obj->get_id()?> - <?=$country_obj->get_name()?></option>
			<?php
				}
			?></select>
			</td>
		</tr>

		<tr style="<?=$country_id?"":"display:none";?>;">
			<td style="padding-right:8px" align="right"><b><?=$lang["competitor_name"]?></b> </td>
			<td colspan='3'>
			<select name="competitor_id">
			<option></option>
			<?

				foreach($all_competitors_list as $competitor_obj)
				{
			?>
			<option value="<?=$competitor_obj->get_id()?>" <?=$this->input->get("competitor_id") == $competitor_obj->get_id()?"SELECTED":""?>><?=$competitor_obj->get_competitor_name()?></option>
			<?php
				}
			?></select>
			</td>
		</tr>



		<tr style="<?=$country_id?"":"display:none";?>;">
			<td style="padding-right:8px" align="right"><b><?=$lang["category"]?></b> </td>
			<td>
				<select name="cat_id" class="input" onChange="ChangeCat(this.value, this.form.sub_cat_id, this.form.sub_sub_cat_id)">
					<option value="">
				</select>
			</td>
			<td style="padding-right:8px" align="right"><b><?=$lang["brand"]?></b> </td>
			<td>
				<select name="brand_id" class="input">
					<option value="">
				</select>
			</td>
			<td rowspan="3" align="center"><input type="submit" value="<?=$lang["export_csv_button"]?>"></td>
		</tr>
		<tr style="<?=$country_id?"":"display:none";?>;">
			<td style="padding-right:8px" align="right"><b><?=$lang["sub_cat"]?></b> </td>
			<td>
				<select name="sub_cat_id" class="input">
					<option value="">
				</select>
			</td>
			<td style="padding-right:8px" align="right"><b><?=$lang["price_listing_status"]?></b> </td>
			<td>
				<select name="price_listing_status" class="input">
					<option></option>
					<option value = "L">L - Listed</option>
					<option value = "N">N - Not Listed</option>
				</select>
			</td>
		</tr>
		<tr >
			<td style="padding-top:5px;"  colspan="5"></td>
		</tr>
		<tr>
			<td height="2" bgcolor="#000033" colspan="5"></td>
		</tr>
	</table>




	<input type="hidden" name="is_query" value="1">
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