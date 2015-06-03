<html>
<head>
<title><?=$lang["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<link rel="stylesheet" href="<?=base_url()?>css/colorbox.css" />
<script type="text/javascript" src="<?=base_url()?>js/jquery-colorbox.min.js"></script>
<script type="text/javascript">
	$(document).ready
	(
		function()
		{
			$(".iframe").colorbox({iframe:true, width:"40%", height:"80%"});
		}
	);
</script>

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

	function switchSearch()
	{
		var multidiv = document.getElementById('multifilter');
		var bulklistdiv = document.getElementById('bulklist');
		var filtertype= document.getElementById("filter");
		var confirmclear = confirm("This will clear all search filters. Continue?");

		if(confirmclear == true)
		{
			document.getElementById('fm').reset();

			if(multidiv.style.display == 'block')
			{
				multidiv.style.display = 'none';
				bulklistdiv.style.display = 'block';
				filtertype.value = 2;
			}
			else
			{
				multidiv.style.display = 'block';
				bulklistdiv.style.display = 'none';
				filtertype.value = 1;
			}
		}
		else
			return;
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
	$pla_status = array("Y" => "Yes", "N" => "No");
	$adwords_status = array("Y" => "Advertised", "N" => "NotAdvertised");
	$autoprice_type = array("N" => "Manual", "Y" => "Auto", "C" => "CompReprice");

	$multidiv_display = $bulklistdiv_display = "";
	$searchfilter = $_GET["filter"];
	if(!$searchfilter)
		$searchfilter = 1;

	if($searchfilter == 1)
	{
		$multidiv_display = "display:block;";
		$bulklistdiv_display = "display:none;";
		$filterval = 1; 	# searching by multiple filter type
	}
	elseif($searchfilter == 2)
	{
		$multidiv_display = "display:none";
		$bulklistdiv_display = "display:block";
		$filterval = 2; 	# searching by sku list
	}
?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="30" class="title"><?=$lang["title"]?></td>
		<td width="600" align="right" class="title"><input type="button" value="<?=$lang["list_button"]?>" class="button" onclick="Redirect('<?=base_url().$this->overview_path?>')"></td>
	</tr>
		<td height="2" class="line"></td>
		<td height="2" class="line"></td>
	</tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
	<col width="150"><col width="420"><col width="170"><col width="420"><col>
	<tr>
		<td></td>
		<td>
			Export as CSV <a href="/marketing/product_overview_website_v2?<?=$query_string?>&csv=1">here</a>,
			after modification, upload the file below
			<form action="/marketing/product_overview_website_v2/upload_sku_info" enctype="multipart/form-data" method="post">
				<input type="file" name="datafile" size="40">
				<input type="submit" value="Upload">
			</form>
		</td>
		<td></td>
		<td style="width:50%;text-align:left;">
			Upload the clearance info
			<form action="/marketing/product_overview_website_v2/upload_clearance_sku_info" enctype="multipart/form-data" method="post">
				<input type="file" name="clearance_datafile" size="40">
				<input type="submit" value="Upload">
			</form>
		</td>
	</tr>
	<tr>
		<td colspan="4"></td>
	</tr>
</table>
<form name="fm" id="fm" method="get" onSubmit="return CheckForm(this)">
<div id ="multifilter" style="<?=$multidiv_display?>">
	<table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
		<col width="150"><col width="420"><col width="170"><col width="420"><col>
		<tr>
			<td>&nbsp;</td>
			<td><input type="button" onclick="switchSearch()" value="Change Search Type"></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><b>TIP: Refining your search may produce faster result</b></td>
			<td></td>
			<td></td>
			<td></td>
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
			<td style="padding-right:8px" align="right"><b><?=$lang["platform_id"]?></b></td>
			<td>
				<select name="pfid">
				<?php
					foreach($clist as $cobj)
					{
						?>
						<option value="<?=$cobj->get_id()?>" <?=$this->input->get("pfid") == $cobj->get_id()?"SELECTED":""?>><?=$cobj->get_id()?> - <?=$cobj->get_name()?></option>
						<?php
					}
				?>
				</select>
			</td>

			<td style="padding-right:8px" align="right"><b>Feed filter</b></td>
			<td>
				<select id="affeed" name="affeed" onchange="check_feed_selection(this)">
					<option value="">---</option>
					<?php
						foreach ($affiliate_feed_list as $key=>$value)
						{
							$selected = "";
							if ($key == $affiliate_feed) $selected = "selected";
							echo "<option value='$key' $selected>$value</option>";
						}
					?>
				</select>
				<select id="feed_status" name="feed_status">
					<option value="">---</option>
					<?php
						foreach ($feed_status_list as $key=>$value)
						{
							$selected = "";
							if ($key == $feed_status) $selected = "selected";
							echo "<option value='$key' $selected>$value</option>";
						}
					?>
				</select>
				<script>
					check_feed_selection(document.getElementById("affeed"));
					function check_feed_selection(sel)
					{
						value = sel.options[sel.selectedIndex].value;
						if (value == "")
						{
							document.getElementById("feed_status").selectedIndex = 0;
							document.getElementById("feed_status").disabled = true;
						}
						else
							document.getElementById("feed_status").disabled = false;
					}
				</script>
			</td>
		</tr>
		<tr>
			<td style="padding-right:8px" align="right"><b><?=$lang["category"]?></b> </td>
			<td>
				<select name="catid" class="input" onChange="ChangeCat(this.value, this.form.scatid, this.form.sub_sub_cat_id)">
					<option value=""> </option>
				</select>
			</td>
			<td style="padding-right:8px" align="right"><b><?=$lang["brand"]?></b> </td>
			<td>
				<select name="brand" class="input">
					<option value=""></option>
				</select>
			</td>
			<td rowspan="3" align="center"><input type="button" value="<?=$lang["cmd_search_button"]?>" onClick="if (CheckForm(this.form)) this.form.submit();"></td>
		</tr>
		<tr>
			<td style="padding-right:8px" align="right"><b><?=$lang["sub_cat"]?></b> </td>
			<td>
				<select name="scatid" class="input">
					<option value=""></option>
				</select>
			</td>
			<td style="padding-right:8px" align="right"><b><?=$lang["supplier"]?></b> </td>
			<td>
				<select name="supp" class="input">
					<option value=""></option>
				</select>
			</td>
		</tr>
		<tr>
			<td style="padding-right:8px" align="right"><b>PLA</b></td>
			<td>
			<?php
				if ($this->input->get("pla") != "")
				{
					$selected_pla[$this->input->get("pla")] = "SELECTED";
				}
				$plaoption = "<select name='pla' class='input'><option></option>";
				foreach ($pla_status as $key => $value)
				{
					$plaoption .= "<option value = '$key' {$selected_pla[$key]}>$value</option>";
				}
				$plaoption .= "</select>";
				echo $plaoption;
			?>
			</td>
			<td style="padding-right:8px" align="right"><b>Google Adwords</b></td>
			<td>
			<?php
				if ($this->input->get("adw") != "")
				{
					$selected_adstat[$this->input->get("adw")] = "SELECTED";
				}
				$adwordsoption = "<select name='adw' class='input'><option></option>";
				foreach ($adwords_status as $key => $value)
				{
					$adwordsoption .= "<option value = '$key' {$selected_adstat[$key]}>$value</option>";
				}
				$adwordsoption .= "</select>";
				echo $adwordsoption;
			?>
			</td>
		</tr>
		<tr>
			<td style="padding-right:8px" align="right"><b>Auto Price Type</b></td>
			<td>
			<?php
				if ($this->input->get("auto_price") != "")
				{
					$selected_ap[$this->input->get("auto_price")] = "SELECTED";
				}
				$apoption = "<select name='auto_price' class='input'><option></option>";
				foreach ($autoprice_type as $key => $value)
				{
					$apoption .= "<option value = '$key' {$selected_ap[$key]}>$value</option>";
				}
				$apoption .= "</select>";
				echo $apoption;
			?>
			</td>
			<td style="padding-right:8px" align="right"><b>Results Per Page</b></td>
			<td>
			<?php
				if ($this->input->get("rp") != "")
					$selected_rp[$this->input->get("rp")] = " SELECTED";
				else
					$selected_rp[20] = " SELECTED";

				$resultlimit = "
								<select name='rp' class='input'>
								<option value='20' {$selected_rp[20]}>20</option>
								<option value='50' {$selected_rp[50]}>50</option>
								<option value='100' {$selected_rp[100]}>100</option>
								";
				echo $resultlimit;
			?>
			</td>
		</tr>
		<tr>
			<td colspan="4">&nbsp;</td>
		</tr>
	</table>
</div>
<div id="bulklist" style="<?=$bulklistdiv_display?>">
	<table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
		<col width="150"><col width="420"><col width="170"><col width="420"><col>
		<tr>
			<td></td>
			<td><input type="button" onclick="switchSearch()" value="Change Search Type"></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td colspan="4"> &nbsp;</td>
		</tr>
		<tr>
			<td style="padding-right:8px" align="right"><b><?=$lang["platform_id"]?></b></td>
			<td>
				<select name="pfid2">
				<?php
					foreach($clist as $cobj)
					{
						?>
						<option value="<?=$cobj->get_id()?>" <?=$this->input->get("pfid2") == $cobj->get_id()?"SELECTED":""?>><?=$cobj->get_id()?> - <?=$cobj->get_name()?></option>
						<?php
					}
				?>
				</select>
			</td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td style="padding-right:8px" align="right">Filter by (only 1 will apply):</td>
			<td><textarea rows="5" name="mskulist" placeholder="Master SKU, separated by next line"></textarea></td>
			<td style="padding-right:8px" align="center"> OR </td>
			<td><textarea rows="5" name="skulist" placeholder="Local SKU, separated by next line"></textarea>
			</td>
			<td rowspan="3" align="center"><input type="button" value="<?=$lang["cmd_search_button"]?>" onClick="if (CheckForm(this.form)) this.form.submit();"></td>
		</tr>
		<tr>
			<td colspan="4">&nbsp;</td>
		</tr>
	</table>
</div>
<input type="hidden" name="filter" id="filter" value="<?=$filterval?>">
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_list">
	<col width="20"><col width="50"><col width="60"><col><col width="50"><col width="80"><col width="80"><col width="100"><col width="40"><col width="65">
	<col width="80"><col width="50"><col width="50"><col width="50"><col width="70"><col width="50"><col width="50"><col width="50">
	<tr class="header">
		<td height="20">
				<img src="<?=base_url()?>images/expand.png" class="pointer" onClick="Expand(document.getElementById('tr_search'));">
		</td>
		<td title="<?=$lang["platform_id"]?>"><a href="#" onClick="SortCol(document.fm, 'platform_id', '<?=$xsort["platform_id"]?>')"><?=$lang["plat_id"]?> <?=$sortimg["platform_id"]?></a></td>
		<td title="<?=$lang["master_sku"]?>"><a href="#" onClick="SortCol(document.fm, 'master_sku', '<?=$xsort["master_sku"]?>')"><?=$lang["master_sku"]?> <?=$sortimg["master_sku"]?></a></td>
		<td title="<?=$lang["product_name"]?>"><a href="#" onClick="SortCol(document.fm, 'prod', '<?=$xsort["prod_name"]?>')"><?=$lang["product_name"]?> <?=$sortimg["prod_name"]?></a></td>
		<td title="<?=$lang["clearance"]?>"><a href="#" onClick="SortCol(document.fm, 'clearance', '<?=$xsort["clearance"]?>')"><?=$lang["clearance"]?> <?=$sortimg["clearance"]?></a></td>
		<td title="<?=$lang["listing_status"]?>"><?=$lang["listing_status"]?> <?=$sortimg["listing_status"]?></td>
		<td title="<?=$lang["website_quantity"]?>"><a href="#" onClick="SortCol(document.fm, 'website_quantity', '<?=$xsort["website_quantity"]?>')"><?=$lang["ws_qty"]?> <?=$sortimg["website_quantity"]?></a></td>
		<td title="<?=$lang["website_status"]?>"><a href="#" onClick="SortCol(document.fm, 'website_status', '<?=$xsort["website_status"]?>')"><?=$lang["ws_status"]?> <?=$sortimg["website_status"]?></a></td>
		<td title="Surplus Qty"><a href="#" onClick="SortCol(document.fm, 'surplus_quantity', '<?=$xsort["surplus_quantity"]?>')">Surplus Qty<?=$sortimg["surplus_quantity"]?></a></td>
		<td title="<?=$lang["supplier_status"]?>"><a href="#" onClick="SortCol(document.fm, 'suppstatus', '<?=$xsort["supplier_status"]?>')"><?=$lang["supplier_status"]?> <?=$sortimg["supplier_status"]?></a></td>
		<td title="<?=$lang["purchaser_updated_date"]?>"><a href="#" onClick="SortCol(document.fm, 'purchaser_updated_date', '<?=$xsort["purchaser_updated_date"]?>')"><?=$lang["updated"]?> <?=$sortimg["purchaser_updated_date"]?></a></td>
		<td title="PLA" align="center">PLA</td>
		<td title="Adwords" align="center">Adwords</td>
		<td title="<?=$lang["total_cost"]?>"><?=$lang["cost"]?></td>
		<td title="<?=$lang["auto_price_cb"]?>"><?=$lang["auto_price_cb"]?></td>
		<td title="<?=$lang["selling_price"]?>"><a href="#" onClick="SortCol(document.fm, 'price', '<?=$xsort["price"]?>')"><?=$lang["price"]?> <?=$sortimg["price"]?></a><br /><span class="special" style="font-size:7pt"><?=$this->default_platform_id?> <?=$lang["conv"]?></span></td>
		<td title="<?=$lang["profit"]?>"><a href="#" onClick="SortCol(document.fm, 'profit', '<?=$xsort["profit"]?>')"><?=$lang["profit"]?> <?=$sortimg["profit"]?></a></td>
		<td title="<?=$lang["profit_margin"]?>"><a href="#" onClick="SortCol(document.fm, 'margin', '<?=$xsort["margin"]?>')"><?=$lang["margin"]?> <?=$sortimg["margin"]?></a></td>
		<td title="<?=$lang["check_all"]?>"><input type="checkbox" name="chkall" value="1" onClick="checkall(document.fm_edit, this);"></td>
	</tr>
	<tr class="search" id="tr_search" <?=$searchdisplay?>>
		<td></td>
		<td></td>
		<td><input name="msku" class="input" value="<?=htmlspecialchars($this->input->get("msku"))?>"></td>
		<td><input name="prod" class="input" value="<?=htmlspecialchars($this->input->get("prod"))?>"></td>
		<td>
			<?php
				if ($this->input->get("clear") != "")
				{
					$selected_cr[$this->input->get("clear")] = "SELECTED";
				}
			?>
			<select name="clear" class="input">
				<option value="">
				<option value="0"<?=$selected_cr[0]?>><?=$lang["no"]?>
				<option value="1"<?=$selected_cr[1]?>><?=$lang["yes"]?>
			</select>
		</td>
		<td>
			<?php
				if ($this->input->get("liststatus") != "")
				{
					$selected_cr[$this->input->get("liststatus")] = "SELECTED";
				}
			?>
			<select name="liststatus" class="input">
				<option value="">
				<option value="L"<?=$selected_cr["L"]?>><?=$lang["listed"]?>
				<option value="N"<?=$selected_cr["N"]?>><?=$lang["not_listed"]?>
			</select>
		</td>
		<td><input name="wsqty" class="input" value="<?=htmlspecialchars($this->input->get("wsqty"))?>"></td>
		<td>
			<?php
				if ($this->input->get("wsstatus") != "")
				{
					$selected_ws[$this->input->get("wsstatus")] = "SELECTED";
				}
			?>
			<select name="wsstatus" class="input">
				<option value="">
			<?php
				foreach ($ar_ws_status as $rskey=>$rsvalue)
				{
			?>
				<option value="<?=$rskey?>" <?=$selected_ws[$rskey]?>><?=$rsvalue?>
			<?php
				}
			?>
			</select>
		</td>
		<td>
			<!-- surplusqty -->
		</td>
		<td>
			<?php
				if ($this->input->get("suppstatus") != "")
				{
					$selected_src[$this->input->get("suppstatus")] = "SELECTED";
				}
			?>
			<select name="suppstatus" class="input">
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
		<td><input name="purcupdate" class="input" value="<?=htmlspecialchars($this->input->get("purcupdate"))?>"></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td><input name="price" class="input" value="<?=htmlspecialchars($this->input->get("price"))?>"></td>
		<td><input name="profit" class="input" value="<?=htmlspecialchars($this->input->get("profit"))?>"></td>
		<td><input name="margin" class="input" value="<?=htmlspecialchars($this->input->get("margin"))?>"></td>
		<td align="center"><input type="submit" name="searchsubmit" value="" class="search_button" style="background: url('<?=base_url()?>images/find.gif') no-repeat;">
			<input type="hidden" name="sort" value='<?=$this->input->get("sort")?>'>
			<input type="hidden" name="order" value='<?=$this->input->get("order")?>'>
			<input type="hidden" name="search" value='1'>
		</td>
	</tr>
</form>
<form name="fm_edit" method="post">
<?=$objlist["tr"]?>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="100%" style="padding-top:5px;">
	<tr>
		<td align="right" style="padding-right:8px;">
			<input type="button" value="<?=$lang['cmd_button']?>" class="button" onClick="if (CheckProfit(this.form)){needToConfirm=false;this.form.submit()}">
		</td>
	</tr>
</table>
<input type="hidden" name="posted" value="1">
</form>
<?=$this->pagination_service->create_links_with_style()?>
<?=$notice["js"]?>
</div>
<?=$objlist["js"]?>
<script>
InitBrand(document.fm.brand);
document.fm.brand.value = '<?=$this->input->get("brand")?>';
InitSupp(document.fm.supp);
document.fm.supp.value = '<?=$this->input->get("supp")?>';
ChangeCat('0', document.fm.catid);
document.fm.catid.value = '<?=$this->input->get("catid")?>';
ChangeCat('<?=$this->input->get("catid")?>', document.fm.scatid);
document.fm.scatid.value = '<?=$this->input->get("scatid")?>';

function checklist(checkallboxid, checkname)
{
	var parentcheckbox = document.getElementById(checkallboxid);
	var checkeles = document.getElementsByName(checkname);

	for (var i = 0; i < checkeles.length; i++)
	{
		var isDisabled = checkeles[i].getAttribute('disabled');
		if(isDisabled != false)
		{
			checkeles[i].checked = parentcheckbox.checked;
		}
	}

	return;
}

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
				if (margin_value < 0 && !prod[platform][sku]["clear"])
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
	return true;
}
</script>
</body>
</html>