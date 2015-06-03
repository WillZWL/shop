<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>
<script type="text/javascript" src="<?=base_url()?>mastercfg/freight_helper/js_freight_cat"></script>
<script language="javascript">
<!--
shiptype = new Array();
<?php
	if(!empty($shiptype))
	{
		foreach($shiptype as $key=>$value)
		{
?>shiptype[<?=$key?>] = "<?=$value?>";<?php
			echo "\n";
		}
	}
?>

function lockqty(value)
{
	if(value == 'O')
	{
		document.list.webqty.readOnly = true;
	}
	else
	{
		document.list.webqty.readOnly = false;
	}
}
-->
</script>
<script type="text/javascript" src="<?=base_url()?>marketing/pricing_tool_amuk/get_js"></script>
</head>
<body marginheight="0" marginwidth="0" topmargin="0" leftmargin="0" class="frame_left">
<?=$notice["img"]?>
<?php
	$ar_ws_status = array("I" => $lang["instock"], "O" => $lang["outstock"], "P" => $lang["pre-order"], "A" => $lang["arriving"]);
	$ar_src_status = array("A" => $lang["available"], "C" => $lang["stock_constraint"], "O" => $lang["temp_out_of_stock"], "L" => $lang["last_lot"], "D" => $lang["discontinued"]);
?>
<div id="main" style="width:auto">
<?php
	if(!$valid_supplier)
	{
	?>
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td height="60" align="left" style="padding-left:8px;"><b>No Valid Supplier Cost</b></td>
		</tr>
		</table>

	<?php
	}

	if($value != "")
	{
		if($canedit)
		{
?>
<form name="list" action="<?=base_url()?>marketing/pricing_tool_amuk/view/<?=$prod_obj->get_sku().($this->input->get('target') == ""?"":"?target=".$this->input->get('target'))?>" method="POST" onSubmit="return CheckForm(this);">
<input type="hidden" name="sku" value="<?=$value?>">
<input type="hidden" name="posted" value="1">
<input type="hidden" name="formtype" value="<?=$action?>">
<input type="hidden" name="target" value="<?=$this->input->get('target')?>">
<?php
		}

		$link = "";
		$tar = "";
		if($price_obj->get_platform_code() != "")
		{
			$link = "http://www.amazon.co.uk/dp/".$price_obj->get_platform_code();
			$tar = "target='ukwebsite'";
		}
?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
	<td height="60" align="left" style="padding-left:8px;">
		<div style="float:left"><img src='<?=get_image_file($prod_obj->get_image(),'m',$prod_obj->get_sku())?>'> &nbsp;</div>
		<div style="float:right"><img src='<?=base_url()?>/images/<?=$lang["platform_country_image"]?>'> &nbsp;</div>

		<b style="font-size: 12px; color: rgb(0, 0, 0);"><?=$lang["header"]?></b><br><?=$lang["header_message"]." - "?><?php if($link != ""){?><a href="<?=$link?>" <?=$tar?> title="Link to Website Page"><?}?><font style="font-size:14px; color:#000000; text-decoration:none; font-weight:bold;"><?=$prod_obj->get_sku()." - ".$prod_obj->get_name()?></font><?if($link != ""){?></a><?}?><br><?=$lang["asin"]." : ".$price_obj->get_platform_code()?>

	</td>
</tr>
<?php
		if ($objcount)
		{
?>
<tr class="header">
	<td height="20" align="left" style="padding-left:8px;"><b style="font-size: 12px; color: rgb(255, 255, 255);"><?=$lang["pricing_info"]."<br>".$lang["listing_currency"].$currency_obj->get_id()." - ".$currency_obj->get_name()?></b></td>
</tr>
<tr>
	<td align="left">
	<table border="0" cellpadding="0" cellspacing="0" width="100%" id="pst" class="tb_main">
<?php
	eval($table_header);
	echo $header;
	echo $table_row;
?>
	</table>
	</td>
</tr>
<?php
	if($price_obj->get_listing_status() != "L")
	{
?>
<tr>
	<td height="30" class="field">&nbsp;</td>
</tr>
<tr height="20" bgcolor="ee3333">
	<td align="center"><font style="font-weight:bold; color:#dddddd;"><?=$lang["currrent_not_listed"]?></font></td>
</tr>
<?php
	}
	else
	{
?>
<tr>
	<td height="50" class="field">&nbsp;</td>
</tr>
<?php
	}
?>
<tr class="header">
	<td height="20" align="left" style="padding-left:8px;"><b style="font-size: 12px; color: rgb(255, 255, 255);"><?=$lang["selling_info"]?></b></td>
</tr>
<tr>
	<td width="100%">
	<table cellpadding="0" cellspacing="0" border="0" width="100%" class="tb_main">
	<tr>
		<td width="20%" class="field"><?=$lang["prodname"]?></td>
		<td width="30%" class="value"><?=$prod_obj->get_name()?></td>
		<td width="20%" class="field"><?=$lang["sku"]?></td>
		<td width="30%" class="value"><?=$prod_obj->get_sku()?></td>
	</tr>
	<tr >
		<td width="20%" class="field"><?=$lang["status"]?></td>
		<td width="30%" class="value"><select name="status" style="width:130px;" onChange="lockqty(this.value);"><?php
			foreach( $ar_ws_status as $key=>$value)
			{
				?><option value="<?=$key?>" <?=($prod_obj->get_website_status() == $key?"SELECTED":"")?>><?=$value?></option><?php
			}
		?></select></td>
		<td width="20%" class="field"><?=$lang["webqty"]?></td>
		<td width="30%" class="value"><input type="text" name="webqty" value="<?=$prod_obj->get_website_quantity()?>" isNatural></td>
	</tr>
	<tr>
		<td width="20%" class="field"><?=$lang["sourcing_status"]?></td>
		<td width="30%" class="value"><?=$ar_src_status[$prod_obj->get_sourcing_status()]?></td>
		<td width="20%" class="field"><?=$lang["latency"]?></td>
		<td width="30%" class="value"><input type="text"  name="latency" value="<?=$price_obj->get_latency()==""?0:$price_obj->get_latency()?>" isNatural min=0></td>
	</tr>
<?php
if(count($inv))
{
	$ttl_inv = $ttl_git = 0;
	$inventory .= "<table border='0' cellpadding='0' cellspacing='1' width='100%'>";
	$inventory .= "<tr><td>".$lang["warehouse"]."</td><td>".$lang["inventory"]."</td><td>".$lang["git"]."</td></tr>";
	foreach($inv as $iobj)
	{

		$inventory .= "<tr><td>".$iobj->get_warehouse_id()."</td><td>".$iobj->get_inventory()."</td><td>".$iobj->get_git()."</td></tr>";
		$ttl_inv += $iobj->get_inventory();
		$ttl_git += $iobj->get_git();
	}
	$inventory .= "<tr><td>".$lang["total"]."</td><td>".$ttl_inv."</td><td>".$ttl_git."</td></tr>";
	$inventory .= "</table>";
}
else
{
	$inventory = 0;
}
?>
	<tr >
		<td width="20%" class="field"><?=$lang["inventory"]?></td>
		<td width="30%" class="value"><?=$inventory?></td>
		<td width="20%" class="field"><?=$lang["qty_in_orders"]?></td>
		<td width="30%" class="value"><?=$qty_in_orders?></td>
	</tr>

	<tr >
		<td width="20%" class="field" valign="top" style="padding-top:2px; padding-bottom:2px;"><?=$lang["listing_status"]?></td>
		<td width="30%" class="value" valign="top" style="padding-top:2px; padding-bottom:2px;"><select name="listing_status" style="width:130px;"><option value="N"><?=$lang["not_listed"]?></option><option value="L" <?=($price_obj->get_listing_status() == "L"?"SELECTED":"")?>><?=$lang["listed"]?></option></select></td>
		<td width="20%" class="field"><?=$lang["asin"]?></td>
		<td width="30%" class="value"><input name="platform_code" type="text" value="<?=$price_obj->get_platform_code()?>"></td>
	</tr>
	<tr>
		<td width="20%" class="field"><?=$lang["max_order_qty"]?></td>
		<td width="30%" class="value"><input type="text"  name="max_order_qty" value="<?=$price_obj->get_max_order_qty()==""?100:$price_obj->get_max_order_qty()?>"></td>
		<td width="20%" class="field"><?=$lang["autoprice"]?></td>
		<td width="30%" class="value"><select name="auto_price"><option value="N" <?=$price_obj->get_auto_price() == "N"?"SELECTED":""?>><?=$lang["disabled"]?></option><option value="Y" <?=$price_obj->get_auto_price() == "Y"?"SELECTED":""?>><?=$lang["enabled"]?></option></select></td>
	</tr>
	<tr >
		<td width="20%" class="field"><?=$lang["on_clearance"]?></td>
		<td width="30%" class="value"><select name="clearance" class="input"><option value="0" <?=!$prod_obj->get_clearance()?"SELECTED":""?>><?=$lang["no"]?></option><option value="1" <?=$prod_obj->get_clearance()?"SELECTED":""?>><?=$lang["yes"]?></option></select></td>
		<td width="20%" class="field"><?=$lang["hitcount"]?></td>
		<td width="30%" class="value"><?=$hitcount?></td>
	</tr>
	<tr>
		<td width="20%" class="field"><?=$lang["current_supplier"]?></td>
		<td width="30%" class="value"><?=$supplier?></td>
		<td width="20%" class="field"><?=$lang["freight_cat"]?></td>
		<td width="30%" class="value">
			<select name="freight_cat_id" class="input" onChange="ChangeFCat(this.value, this.form.freight_weight)" notEmpty>
				<option value="">
			</select>
		</td>
	</tr>

	<tr >
		<td width="20%" class="field"><?=$lang["allow_express"]?></td>
		<td width="30%" class="value"><input type="checkbox" name="allow_express" <?=($price_obj->get_allow_express()=='Y'?"CHECKED":"")?>></td>
		<td width="20%" class="field"><?=$lang["advertised"]?></td>
		<td width="30%" class="value"><input type="checkbox" name="is_advertised" <?=($price_obj->get_is_advertised()=='Y'?"CHECKED":"")?>></td>
	</tr>

	<tr >
		<td width="20%" class="field" valign="top" style="padding-top:2px; padding-bottom:2px;"><?=$lang["marketing_notes"]?></td>
		<td width="30%" class="value" valign="top" style="padding-top:2px; padding-bottom:2px;"><div><?=$lang["current_notes"]?></div><?php
			if(!empty($mkt_note_obj))
			{

				foreach($mkt_note_obj as $nobj)
				{
					?><div><?=$nobj->get_note()?><br><span style="font-size:9px; color:#888888; font-style:italic;"><?=$lang["create_by"].$nobj->get_username().$lang["on"].$nobj->get_create_on()?></span></div><?php
				}
			}
			else
			{
				echo "<div>".$lang["no_notes"]."</div>";
			}
		?><div><?=$lang["create_note"]?></div><div><input name="m_note" maxlength="255" type="text"></div></td>
		<td width="20%" class="field" valign="top" style="padding-top:2px; padding-bottom:2px;"><?=$lang["sourcing_notes"]?></td>
		<td width="30%" class="value" valign="top" style="padding-top:2px; padding-bottom:2px;" colspan="3"><div><?=$lang["current_notes"]?></div>
		<?php
			if(!empty($src_note_obj))
			{

				foreach($src_note_obj as $nobj)
				{
			?>
				<div><?=$nobj->get_note()?><br><span style="font-size:9px; color:#888888; font-style:italic;"><?=$lang["create_by"].$nobj->get_username().$lang["on"].$nobj->get_create_on()?></span></div>
			<?php
				}
			}
			else
			{
				echo "<div>".$lang["no_notes"]."</div>";
			}
		?><div><?=$lang["create_note"]?></div><div><input name="s_note" maxlength="255" type="text"></div></td>
	</tr>
	<tr >
		<td width="20%" class="field"><?=$lang["create_on"]?></td>
		<td width="30%" class="value"><?=$price_obj->get_create_on()?></td>
		<td width="20%" class="field"><?=$lang["modify_on"]?></td>
		<td width="30%" class="value"><?=$price_obj->get_modify_on()?></td>
	</tr>
	<tr >
		<td width="20%" class="field"><?=$lang["create_at"]?></td>
		<td width="30%" class="value"><?=$price_obj->get_create_at()?></td>
		<td width="20%" class="field"><?=$lang["modify_at"]?></td>
		<td width="30%" class="value"><?=$price_obj->get_modify_at()?></td>
	</tr>
	<tr >
		<td width="20%" class="field"><?=$lang["create_by"]?></td>
		<td width="30%" class="value"><?=$price_obj->get_create_by()?></td>
		<td width="20%" class="field"><?=$lang["modify_by"]?></td>
		<td width="30%" class="value"><?=$price_obj->get_modify_by()?></td>
	</tr>
	<tr >
		<td width="20%" class="field"><?=$lang["ean"]?></td>
		<td width="30%" class="value"><input type="text" name="ean" value="<?=$prod_obj->get_ean()?>"></td>
		<td width="20%" class="field"><?=$lang["mapping_code"]?></td>
		<td width="30%" class="value"><input type="text" name="ext_mapping_code" value="<?=$price_obj->get_ext_mapping_code()?>" style="width:130px;"></td>
	</tr>
	<tr>
		<td width="20%" class="field"><?=$lang["mpn"]?></td>
		<td width="30%" class="value"><input type="text" name="mpn" value="<?=$prod_obj->get_mpn()?>"></td>
		<td width="20%" class="field"><?=$lang["upc"]?></td>
		<td width="30%" class="value"><input type="text" name="upc" value="<?=$prod_obj->get_upc()?>" style="width:130px;"></td>
	</tr>
	</table>
	</td>
</tr>
</table>
<?php
			if($canedit)
			{
?>
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_detail">
<tr>
	<td align="right" style="padding-right:8px;" height="30"><input type="submit" value="Update Pricing Tool" class="button"></td>
</tr>
</table>
</form>
<div style="margin-top:20px; margin-bottom:20px;  text-align:left; padding-left:10px;">
Formula for this pricing tool:<br>
<b>AMAZON DEL</b> = Amazon Standard UK Mainland Delivery based on Weight Category (AMUK_StdDom)<br>
<b>VAT</b> = (DECLARED + BULK ADMIN) * 17.5%<br>
<b>DECLARED</b> = (SALE PRICE * DECLARED %)<br>
<i>DECLARED % is based on Freight Category</i><br>
<b>COST</b> = COST from supplier in GBP<br>
<b>POSTAGE</b> is cost of sending item to customer -> maintained at weight category : weight_cost (AMUK_StdDom)<br>
<b>BULK ADMIN</b> is a fixed rate from External warehouse for handling each item.<br>
<b>BULK FREIGHT</b> = BULK FREIGHT from HK to UK<br>
<b>COMMISSION</b> = (SALE PRICE + AMAZON DELIVERY) * COMMISSION %<br>
<i>COMMISSION % is based on sub category/ platform</i><br>
<br>
Bulk model:<br>
<b>TOTAL COST</b> =  VAT + COST + POSTAGE + BULK ADMIN + BULK FREIGHT + COMMISSION <br>
<b>PROFIT</b> = SALE PRICE + AMAZON DEL - TOTAL COST<br>
<br>
PG model:<br>
<b>TOTAL COST</b> =  COST + POSTAGE + BULK ADMIN + COMMISSION <br>
<b>PROFIT</b> = SALE PRICE + AMAZON DEL - TOTAL COST<br>
</div>
<?php
			}
		}
		else
		{
?>
		<tr>
			<td style="padding-left:8px;">
				<br>
				<b><a class="warn" href="<?=base_url()?>marketing/category/view_scpv/?subcat_id=<?=$prod_obj->get_sub_cat_id()?>&platform=AMUK"><?=$lang["sub_cat_no_set"]?></a></b>
			</td>
		</tr>
	</table>
<?php
		}
	}
?>
</div>
<?=$notice["js"]?>
<?php

	if($prompt_notice)
	{
?><script language="javascript">alert('<?=$lang["update_notice"]?>')</script><?php
	}
?>
<script language="javascript">
InitFCat(document.list.freight_cat_id);
<?php
	if (@call_user_func(array($prod_obj, "get_freight_cat_id")))
	{
?>
document.list.freight_cat_id.value = '<?=$prod_obj->get_freight_cat_id()?>';
<?php
	}
?>

if(document.list)
{
lockqty(document.list.status.value);
}
</script>
</body>
</html>
