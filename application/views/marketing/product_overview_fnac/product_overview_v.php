<html>
<head>
<title><?=$lang["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>
<script type="text/javascript" src="<?=base_url().$this->overview_path?>/js_overview"></script>
<script type="text/javascript" src="<?=base_url()?>marketing/product/js_catlist"></script>
<script type="text/javascript" src="<?=base_url()?>mastercfg/brand/js_brandlist"></script>
<script type="text/javascript" src="<?=base_url()?>supply/supplier_helper/js_supplist/1"></script>
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
		<td width="400" align="right" class="title"><input type="button" value="<?=$lang["list_button"]?>" class="button" onclick="Redirect('<?=base_url().$this->overview_path?>')"></td>
	</tr>
		<td height="2" class="line"></td>
		<td height="2" class="line"></td>
	</tr>
</table>
<form name="fm" method="get" onSubmit="return CheckForm(this)">
<table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
	<col width="150"><col width="420"><col width="170"><col width="420"><col>
	<tr>
		<td colspan="4">&nbsp;</td>
	</tr>
	<tr>
		<td style="padding-right:8px" align="right"><b><?=$lang["platform_id"]?></b> </td>
		<td colspan='3'>
		<select name="platform_id">
		<?
			foreach($clist as $cobj)
			{
		?>
		<option value="<?=$cobj->get_id()?>" <?=$this->input->get("platform_id") == $cobj->get_id()?"SELECTED":""?>><?=$cobj->get_id()?> - <?=$cobj->get_name()?></option>
		<?php
			}
		?></select>
		</td>
	</tr>
	<tr>
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
		<td rowspan="3" align="center"><input type="button" value="<?=$lang["cmd_search_button"]?>" onClick="if (CheckForm(this.form)) this.form.submit();"></td>
	</tr>
	<tr>
		<td style="padding-right:8px" align="right"><b><?=$lang["sub_cat"]?></b> </td>
		<td>
			<select name="sub_cat_id" class="input">
				<option value="">
			</select>
		</td>
		<td style="padding-right:8px" align="right"><b><?=$lang["supplier"]?></b> </td>
		<td>
			<select name="supplier_id" class="input">
				<option value="">
			</select>
		</td>
	</tr>
	<tr>
		<td style="padding-right:8px" align="right"></td>
		<td></td>
		<td style="padding-right:8px" align="right">
		<b>Surplus Quantity</b>
		</td>
		<td>
			<select id="surplusqty_prefix" name="surplusqty_prefix">
				<option value="2" <?=$this->input->get("surplusqty_prefix") == "2"?"SELECTED":""?>>Smaller <=</option>
				<option value="1" <?=$this->input->get("surplusqty_prefix") == "1"?"SELECTED":""?>>> 0 and Smaller <=</option>
				<option value="3" <?=$this->input->get("surplusqty_prefix") == "3"?"SELECTED":""?>>Greater >=</option>
			</select>
			<input name="surplusqty" class="input" value="<?=$this->input->get("surplusqty")==""?"10000":$this->input->get("surplusqty")?>" style="width:200px">
		</td>
	</tr>
	<tr>
		<td colspan="4">&nbsp;</td>
	</tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_list">
	<col width="20"><col width="50"><col width="60"><col width="60"><col><col width="60"><col width="80"><col width="80"><col width="100">
	<?php
		foreach($wms_wh as $wh)
		{
	?>
			<col width="50">
	<?php
		}
	?>
	<col width="80"><col width="75"><col width="110"><col width="70"><col width="50"><col width="50">
	<tr class="header">
		<td height="20">
				<img src="<?=base_url()?>images/expand.png" class="pointer" onClick="Expand(document.getElementById('tr_search'));">
		</td>
		<td title="<?=$lang["platform_id"]?>"><a href="#" onClick="SortCol(document.fm, 'platform_id', '<?=$xsort["platform_id"]?>')"><?=$lang["plat_id"]?> <?=$sortimg["platform_id"]?></a></td>
		<td title="<?=$lang["master_sku"]?>"><a href="#" onClick="SortCol(document.fm, 'master_sku', '<?=$xsort["master_sku"]?>')"><?=$lang["master_sku"]?> <?=$sortimg["master_sku"]?></a></td>
		<td title="<?=$lang["sku"]?>"><?=$lang["sku"]?></td>
		<td title="<?=$lang["product_name"]?>"><a href="#" onClick="SortCol(document.fm, 'prod_name', '<?=$xsort["prod_name"]?>')"><?=$lang["product_name"]?> <?=$sortimg["prod_name"]?></a></td>
		<td title="<?=$lang["clearance"]?>"><a href="#" onClick="SortCol(document.fm, 'clearance', '<?=$xsort["clearance"]?>')"><?=$lang["clear"]?> <?=$sortimg["clearance"]?></a></td>
		<td title="<?=$lang["listing_status"]?>"><?=$lang["listing_status"]?> <?=$sortimg["listing_status"]?></td>
		<td title="<?=$lang["ext_qty"]?>"><a href="#" onClick="SortCol(document.fm, 'ext_qty', '<?=$xsort["ext_qty"]?>')"><?=$lang["ext_qty"]?> <?=$sortimg["ext_qty"]?></a></td>
		<td title="EAN">EAN</td>

		<?php
			foreach($wms_wh as $wh)
			{
		?>
				<td title="<?=$wh->get_warehouse_id() . ' ' .$lang["qty"]?>"><?=$wh->get_warehouse_id() . ' ' .$lang["qty"]?></td>
		<?php
			}
		?>
		<td title="SURPLUS"><a href="#" onClick="SortCol(document.fm, 'surplus_quantity', '<?=$xsort["surplus_quantity"]?>')">SURPLUS <?=$sortimg["surplus_quantity"]?></a></td>
		<td title="<?=$lang["supplier_status"]?>"><a href="#" onClick="SortCol(document.fm, 'supplier_status', '<?=$xsort["supplier_status"]?>')"><?=$lang["supplier_status"]?> <?=$sortimg["supplier_status"]?></a></td>
		<td title="<?=$lang["purchaser_updated_date"]?>"><a href="#" onClick="SortCol(document.fm, 'purchaser_updated_date', '<?=$xsort["purchaser_updated_date"]?>')"><?=$lang["updated"]?> <?=$sortimg["purchaser_updated_date"]?></a></td>
		<td title="<?=$lang["total_cost"]?>"><?=$lang["cost"]?></td>
		<td title="<?=$lang["selling_price"]?>"><a href="#" onClick="SortCol(document.fm, 'price', '<?=$xsort["price"]?>')"><?=$lang["price"]?> <?=$sortimg["price"]?></a><br /><span class="special" style="font-size:7pt"><?=$this->default_platform_id?> <?=$lang["conv"]?></span></td>
		<td title="<?=$lang["profit"]?>"><a href="#" onClick="SortCol(document.fm, 'profit', '<?=$xsort["profit"]?>')"><?=$lang["profit"]?> <?=$sortimg["profit"]?></a></td>
		<td title="<?=$lang["profit_margin"]?>"><a href="#" onClick="SortCol(document.fm, 'margin', '<?=$xsort["margin"]?>')"><?=$lang["margin"]?> <?=$sortimg["margin"]?></a></td>
		<td title="<?=$lang["check_all"]?>"><input type="checkbox" name="chkall" value="1" onClick="checkall(document.fm_edit, this);"></td>
	</tr>
	<tr class="search" id="tr_search" <?=$searchdisplay?>>
		<td></td>
		<td></td>
		<td><input name="master_sku" class="input" value="<?=htmlspecialchars($this->input->get("master_sku"))?>"></td>
		<td></td>
		<td><input name="prod_name" class="input" value="<?=htmlspecialchars($this->input->get("prod_name"))?>"></td>
		<td>
			<?php
				if ($this->input->get("clearance") != "")
				{
					$selected_cr[$this->input->get("clearance")] = "SELECTED";
				}
			?>
			<select name="clearance" class="input">
				<option value="">
				<option value="0"<?=$selected_cr[0]?>><?=$lang["no"]?>
				<option value="1"<?=$selected_cr[1]?>><?=$lang["yes"]?>
			</select>
		</td>
		<td>
			<?php
				if ($this->input->get("listing_status") != "")
				{
					$selected_cr[$this->input->get("listing_status")] = "SELECTED";
				}
			?>
			<select name="listing_status" class="input">
				<option value="">
				<option value="L"<?=$selected_cr["L"]?>><?=$lang["listed"]?>
				<option value="N"<?=$selected_cr["N"]?>><?=$lang["not_listed"]?>
			</select>
		</td>
		<td><input name="ext_qty" class="input" value="<?=htmlspecialchars($this->input->get("ext_qty"))?>"></td>
		<td>&nbsp;</td>

		<?php
			foreach($wms_wh as $wh)
			{
		?>
				<td></td>
		<?php
			}
		?>

		<td>&nbsp;</td>
		<td>
			<?php
				if ($this->input->get("supplier_status") != "")
				{
					$selected_src[$this->input->get("supplier_status")] = "SELECTED";
				}
			?>
			<select name="supplier_status" class="input">
				<option value="">
			<?php
				foreach ($ar_src_status as $rskey=>$rsvalue)
				{
			?>
				<option value="<?=$rskey?>" <?=$selected_src[$rskey]?>><?=$rsvalue?>
			<?php
				}
			?>
			</select>
		</td>
		<td><input name="purchaser_updated_date" class="input" value="<?=htmlspecialchars($this->input->get("purchaser_updated_date"))?>"></td>
		<td></td>
		<td><input name="price" class="input" value="<?=htmlspecialchars($this->input->get("price"))?>"></td>
		<td><input name="profit" class="input" value="<?=htmlspecialchars($this->input->get("profit"))?>"></td>
		<td><input name="margin" class="input" value="<?=htmlspecialchars($this->input->get("margin"))?>"></td>
		<td align="center"><input type="submit" name="searchsubmit" value="" class="search_button" style="background: url('<?=base_url()?>images/find.gif') no-repeat;"></td>
	</tr>
<input type="hidden" name="sort" value='<?=$this->input->get("sort")?>'>
<input type="hidden" name="order" value='<?=$this->input->get("order")?>'>
<input type="hidden" name="search" value='1'>
</form>
<form name="fm_edit" method="post">
<?=$objlist["tr"]?>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="100%" style="padding-top:5px;">
	<tr>
		<td align="right" style="padding-right:8px;">
			<input type="button" style="width:180px" value="<?=$lang['sync_button']?>" class="button" onClick="if (CheckProfit(this.form)){ document.getElementById('sync').value=1;needToConfirm=false;this.form.submit()}">
		</td>

		<td align="right" style="padding-right:8px; width:120px">
			<input type="button" value="<?=$lang['cmd_button']?>" class="button" onClick="if (CheckProfit(this.form)){needToConfirm=false;this.form.submit()}">
		</td>
	</tr>
</table>
<input type="hidden" name="posted" value="1">
<input type="hidden" id="sync" name="sync" value='0'>
</form>
<?=$this->pagination_service->create_links_with_style()?>
<?=$notice["js"]?>
</div>
<?=$objlist["js"]?>
<script>
InitBrand(document.fm.brand_id);
document.fm.brand_id.value = '<?=$this->input->get("brand_id")?>';
InitSupp(document.fm.supplier_id);
document.fm.supplier_id.value = '<?=$this->input->get("supplier_id")?>';
ChangeCat('0', document.fm.cat_id);
document.fm.cat_id.value = '<?=$this->input->get("cat_id")?>';
ChangeCat('<?=$this->input->get("cat_id")?>', document.fm.sub_cat_id);
document.fm.sub_cat_id.value = '<?=$this->input->get("sub_cat_id")?>';

function CheckProfit(f)
{
	var margin = "";
	var margin_value = 0;
	if (needToConfirm)
	{
		check_eles = document.getElementsByName("check[]");
		count=false;
		for (i=0; i<check_eles.length; i++)
		{
			check_ele = check_eles[i];
			if (check_ele.checked)
			{
				token = check_ele.value.indexOf("||");
				platform = check_ele.value.substring(0,token);
				sku = check_ele.value.substring(token+2);
				price_ele = f.elements["price["+platform+"]["+ sku +"][price]"];
				if (!CheckSetElements(check_ele.parentNode.parentNode, sku))
				{
					return false;
				}

				margin = document.getElementById("margin["+platform+"]["+sku+"]").innerHTML;
				margin_value = margin.substring(0,(margin.length - 2)) * 1;
				if (margin_value < 0 && !prod[platform][sku]["clearance"])
				//if (f.elements["cost["+platform+"]["+ sku +"]"].value*1 > price_ele.value*1 && !prod[sku]["clearance"])
				{
					if (!confirm(sku + "<?=$lang["make_loss"]?>"))
					{
						price_ele.focus();
						return false;
					}
				}
				count=true;
			}
		}
		return count;
	}
	return false;
}
</script>
</body>
</html>