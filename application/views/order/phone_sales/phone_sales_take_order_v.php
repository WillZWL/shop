<html>
<head>
<title><?=$lang["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>
<script type="text/javascript" language="javascript" src="<?=base_url()?>js/lytebox.js"></script>
<script src="<?=base_url()?>js/jquery.js" type="text/javascript"></script>
<script type="text/javascript" src="<?=base_url()?>js/calendar.js"></script>
<link rel="stylesheet" href="<?=base_url()?>css/calendar.css" type="text/css" media="all"/>
<script language="javascript">
function init()
{
	update_state_field();
}

function update_state_field()
{
	if(document.getElementById('country_id').value == 'US')
	{
		document.getElementById('state').style.display = 'none';
		document.getElementById('state').disabled = true;
		document.getElementById('state_us').style.display = '';
		document.getElementById('state_us').disabled = false;
		document.getElementById('warn').innerHTML = '<span class="warn">*</span>';
	}
	else
	{
		document.getElementById('state_us').style.display = 'none';
		document.getElementById('state_us').disabled = true;
		document.getElementById('state').style.display = '';
		document.getElementById('state').disabled = false;
		document.getElementById('warn').innerHTML = '';
	}

	if(document.getElementById('d_country_id').value == 'US')
	{
		document.getElementById('d_state').style.display = 'none';
		document.getElementById('d_state').disabled = true;
		document.getElementById('d_state_us').style.display = '';
		document.getElementById('d_state_us').disabled = false;
		document.getElementById('d_warn').innerHTML = '<span class="warn">*</span>';
	}
	else
	{
		document.getElementById('d_state_us').style.display = 'none';
		document.getElementById('d_state_us').disabled = true;
		document.getElementById('d_state').style.display = '';
		document.getElementById('d_state').disabled = false;
		document.getElementById('d_warn').innerHTML = '';
	}
}

function showBaddr()
{
	var fm = document.fm_checkout;
	if (!fm.billaddr.checked)
	{
		document.getElementById('del_country_id').style.display = "none";
		document.getElementById('del_company').style.display = "none";
		document.getElementById('del_name').style.display = "none";
		document.getElementById('del_address').style.display = "none";
		document.getElementById('del_city').style.display = "none";
		document.getElementById('del_state').style.display = "none";
		document.getElementById('del_postcode').style.display = "none";
		document.getElementById('del_tel').style.display = "none";
		document.getElementById('del_mobile').style.display = "none";
		document.getElementById('del_name').removeAttribute("notEmpty");
		document.getElementById('del_address_1').removeAttribute("notEmpty");
		document.getElementById('del_city_town').removeAttribute("notEmpty");
		document.getElementById('del_postcode').removeAttribute("validPostal");
	}
	else
	{
		document.getElementById('del_country_id').style.display = "";
		document.getElementById('del_company').style.display = "";
		document.getElementById('del_name').style.display = "";
		document.getElementById('del_address').style.display = "";
		document.getElementById('del_city').style.display = "";
		document.getElementById('del_state').style.display = "";
		document.getElementById('del_postcode').style.display = "";
		document.getElementById('del_tel').style.display = "";
		document.getElementById('del_mobile').style.display = "";
		document.getElementById('del_name').setAttribute("notEmpty" , "");
		document.getElementById('del_address_1').setAttribute("notEmpty" , "");
		document.getElementById('del_city_town').setAttribute("notEmpty" , "");
		document.getElementById('del_postcode').setAttribute("validPostal" , "");
	}
}
</script>
</head>
<body style="width:auto" onload="init();">
<div style="width:auto">
<?=$notice["img"]?>
<form name="fm_checkout" method="post" onSubmit="return CheckSubmit(this)">
	<table width="100%" cellspacing="0" cellpadding="4">
		<tr style="font-weight:bold;background:#DDDDDD">
			<td width="50" style="border:1px solid #BBBBBB; border-width:1px 0px 1px 1px;">&nbsp;</td>
			<td align="left" style="border:1px solid #BBBBBB; border-width:1px 0px 1px 1px;">Product</td>
			<td align="right" width="60" style="border:1px solid #BBBBBB; border-width:1px 0px 1px 1px;">Price</td>
			<td align="right" width="40" style="border:1px solid #BBBBBB; border-width:1px 0px 1px 1px;">Qty</td>
			<td align="right" width="60" style="border:1px solid #BBBBBB; border-width:1px 0px 1px 1px;">Sub-Total</td>
			<td align="right" width="40" style="border:1px solid #BBBBBB; border-width:1px 0px 1px 1px;">VAT</td>
			<td align="right" width="60" style="border:1px solid #BBBBBB; border-width:1px 1px 1px 1px;">Total</td>
		</tr>
	<?php
		$promo_disc_amount = $sub_total = $total_vat = $total = 0;
		if ($promo["valid"] && !$promo["error"])
		{
			$promo_disc_amount = $promo["disc_amount"];
		}
		for($i=0; $i<count($cart); $i++)
		{
			$price = $cart[$i]["price"] - $cart[$i]["vat_total"]/$cart[$i]["qty"];
			$cur_sub_total = $price*$cart[$i]["qty"];
			$sub_total += $cur_sub_total;
			$total_vat += $cart[$i]["vat_total"];
			$total += $cart[$i]["total"];
	?>
		<tr>
			<td align="center" style="border-left:1px solid #BBBBBB;">
				<img src="<?=get_image_file($cart[$i]["image"], "s", $cart[$i]["sku"])?>">
			</td>
			<td align="left" style="border-left:1px solid #BBBBBB;"><?=$cart[$i]["name"]?></td>
			<td align="right" style="border-left:1px solid #BBBBBB;"><?=number_format($price, 2)?></td>
			<td align="right" style="border-left:1px solid #BBBBBB;">
				<?=$cart[$i]["qty"]?>
			</td>
			<td align="right" style="border-left:1px solid #BBBBBB;"><?=number_format($cur_sub_total,2)?></td>
			<td align="right" style="border-left:1px solid #BBBBBB;"><?=number_format($cart[$i]["vat_total"],2)?></td>
			<td align="right" style="border-left:1px solid #BBBBBB;border-right:1px solid #BBBBBB;"><b><?=number_format($cart[$i]["total"],2)?></b></td>
		</tr>
	<?php
		}
	?>
		<tr>
			<td colspan="2" style="border-top:1px solid #BBBBBB;">&nbsp;</td>
			<td colspan="2" align="right" bgcolor="#DDDDDD" style="border:1px solid #BBBBBB; border-width:1px 0px 1px 1px;"><b>Cost of Items</b></td>
			<td align="right" bgcolor="#F0F0F0" style="border:1px solid #BBBBBB; border-width:1px 0px 1px 1px;"><?=number_format($sub_total,2)?></td>
			<td align="right" bgcolor="#F0F0F0" style="border:1px solid #BBBBBB; border-width:1px 0px 1px 1px;"><?=number_format($total_vat,2)?></td>
			<td align="right" bgcolor="#FFEEAA" style="border:1px solid #BBBBBB; border-width:1px 1px 1px 1px;"><b><?=number_format($total,2)?></b></td>
		</tr>
		<tr>
			<td colspan="2" align="right">
			</td>
			<td colspan="2" width="100px" align="right" bgcolor="#DDDDDD" style="border-left:1px solid #BBBBBB;"><b>
			<?php


				$noofdelopts = count($dc);

				$del_str = '<select name="delivery">';
				$cur_courier = $this->input->get_post("delivery");
				$d_selected[$cur_courier] = " SELECTED";

				if ($dc)
				{
					foreach ($dc as $rskey=>$rsvalue){
						$courier[] = $rskey;
						$del_str .= "<option value='{$rskey}'{$d_selected[$rskey]}>{$rsvalue["display_name"]}";
					}
				}

				$cur_delivery = $cur_courier?$cur_courier:$courier[0];
				$del_str .= '</select>';
//					echo $noofdelopts>1?$del_str:$dc[$cur_delivery]["display_name"];
				echo "<input name='delivery' type='hidden' value='{$cur_delivery}'>".$dc[$cur_delivery]["display_name"];
			?>
			Insured Delivery</b></td>
			<td align="right" bgcolor="#F0F0F0" style="border-left:1px solid #BBBBBB;"><?=number_format($dc_sub_total = ($dc[$cur_delivery]["charge"]-$dc[$cur_delivery]["vat"]),2)?></td>
			<td align="right" bgcolor="#F0F0F0" style="border-left:1px solid #BBBBBB;"><?=number_format($dc[$cur_delivery]["vat"],2)?></td>
			<td align="right" bgcolor="#FFEEAA" style="border:1px solid #BBBBBB; border-width:0px 1px 0px 1px;"><b><?=number_format($dc[$cur_delivery]["charge"],2)?></b></td>
		</tr>
		<?php
			if ($_SESSION["promotion_code"])
			{
		?>
				<tr>
					<td colspan="2" align="right" style="border-top:1px solid #BBBBBB;"></td>
					<td colspan="2" align="right" bgcolor="#DDDDDD" style="border:1px solid #BBBBBB; border-width:1px 0px 1px 1px;"><b><?=$lang["promotion_code"]?></b></td>
					<td colspan="3" align="right" bgcolor="#F0F0F0" style="border:1px solid #BBBBBB; border-width:1px 1px 1px 1px;"><?=$_SESSION["promotion_code"]?></td>
				</tr>
		<?php
			}
		?>
		<?php
			$offline_fee = $this->input->post("offline_fee")*1;
			if (!$offline_fee)
			{
				$offline_fee = $_POST["so_extend"]["offline_fee"];
			}
			if ($offline_fee)
			{
		?>
			<input type="hidden" name="so_extend[offline_fee]" value="<?=$offline_fee?>">
		<tr>
			<td colspan="2">&nbsp;</td>
			<td colspan="2" align="right" bgcolor="#DDDDDD" style="border:1px solid #BBBBBB; border-width:1px 0px 1px 1px;"><b>Offline Fee</b></td>
			<td align="right" bgcolor="#F0F0F0" style="border:1px solid #BBBBBB; border-width:1px 0px 1px 1px;">&nbsp;</td>
			<td align="right" bgcolor="#F0F0F0" style="border:1px solid #BBBBBB; border-width:1px 0px 1px 1px;">&nbsp;</td>
			<td align="right" bgcolor="#FFEEAA" style="border:1px solid #BBBBBB; border-width:1px 1px 1px 1px;"><b><?=number_format($offline_fee, 2)?></b></td>
		</tr>
		<?php
			}
		?>
		<tr>
			<td colspan="2" style="height:2px;line-height:2px;padding:0px;"></td>
			<td colspan="5" bgcolor="#000000" style="height:2px;line-height:2px;padding:0px;"></td>
		</tr>
		<tr>
			<td colspan="2"></td>
			<td colspan="2" align="right" bgcolor="#DDDDDD" style="border:1px solid #BBBBBB; border-width:0px 0px 1px 1px;"><b>TOTAL</b></td>
			<td align="right" bgcolor="#F0F0F0" style="border:1px solid #BBBBBB; border-width:0px 0px 1px 1px;"><?=number_format($sub_total + $dc_sub_total,2)?></td>
			<td align="right" bgcolor="#F0F0F0" style="border:1px solid #BBBBBB; border-width:0px 0px 1px 1px;"><?=number_format($total_vat + $dc[$cur_delivery]["vat"],2)?></td>
			<td align="right" bgcolor="#FF9933" style="border:1px solid #BBBBBB; border-width:0px 1px 1px 1px;font-size:9pt"><b><?=number_format($total + $dc[$cur_delivery]["charge"] + $offline_fee - $promo_disc_amount, 2)?></b></td>
		</tr>
		<?php
			if (isset($promo))
			{
				if (!$promo["valid"] || $promo["error"])
				{
					$display_color = "red";
					$display_msg = ($promo["valid"] && $promo["error"]=="FI")?$lang["free_item_outstock_inactive"]:$lang["promotion_code_invalid"];
				}
				else
				{
					$display_color = "green";
					$display_msg = $lang["promotion_code_accepted"];
				}
		?>
		<tr>
			<td colspan="7" align="right" style="color:<?=$display_color?>;font-weight:bold;"><?=$display_msg?></td>
			<td></td>
		</tr>
		<?php
			}
		?>
	</table>
	<table cellpadding="4" cellspacing="0" width="60%" class="bg_row">
		<tr>
			<td width="150"><span class="warn">*</span> Email address: </td>
			<td>
					<input name="client[email]" dname="Email Address" class="text" value="<?=htmlspecialchars($_POST["client"]["email"])?>" notEmpty validEmail> <input type="button" value="Check Email" onClick="if (document.fm_checkout.elements['client[email]'].value != '') {document.getElementById('a_check').href='<?=base_url()?>/order/phone_sales/check_email/'+document.fm_checkout.elements['client[email]'].value+'/<?=$country_id?>';document.getElementById('a_check').onclick()}"> <a id="a_check" href="<?=base_url()?>/order/phone_sales/check_email/" rel="lyteframe" rev="width: 300px; height: 275px; scrolling: auto;" title="Check Email"></a>
			</td>
		</tr>
		<tr>
			<td>Password: <br> (At least 5 characters)</td>
			<td><input type="password" dname="Password" name="client[password]" class="text"></td>
		</tr>
		<tr>
			<td colspan="2" height="20px"><hr></td>
		</tr>
		<tr>
			<td width="23" colspan="2"><input type="checkbox" name="billaddr" id="billaddr" onclick="showBaddr()" value="1"/>Click here if Billing Address and Cardholder Name is different from Delivery Detail</td>
		</tr>
		<tr>
			<td height="10px"></td>
		</tr>
		<tr>
			<td><span class="warn">*</span> Location: </td>
			<td>
				<select id="country_id" name="client[country_id]" class="text" onchange="document.getElementById('state').style.display = 'none'; document.getElementById('state').disabled = true;update_state_field()">
					<?php
						if($country_list)
						{
							if ($_POST["client"]["country_id"])
							{
								$c_selected[$_POST["client"]["country_id"]] = " SELECTED";
							}
							else
							{
								$c_selected[$pbv_obj->get_platform_country_id()] = " SELECTED";
							}
							foreach($country_list as $id=>$name)
							{
					?>
					<option value="<?=$id?>"<?=$c_selected[$id]?>><?=$name?>
					<?php
							}
						}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td> Register Company Name: </td>
			<td><input name="client[companyname]" dname="Company" class="text2" value="<?=htmlspecialchars($_POST["client"]["companyname"])?>"></td>
		</tr>
		<tr>
			<td><span class="warn">*</span> Register Name: </td>
			<td>
				<?$t_selected[$_POST["client"]["title"]] = " SELECTED";?>
				<select name="client[title]">
					<option value="Mr"<?=$t_selected["Mr"]?>>Mr
					<option value="Mrs"<?=$t_selected["Mrs"]?>>Mrs
					<option value="Miss"<?=$t_selected["Miss"]?>>Miss
					<option value="Dr"<?=$t_selected["Dr"]?>>Dr
				</select>
				<input name="client[forename]" dname="First Name" class="text2" value="<?=htmlspecialchars($_POST["client"]["forename"])?>" notEmpty>
				<input name="client[surname]" dname="Last Name"  class="text2" value="<?=htmlspecialchars($_POST["client"]["surname"])?>" notEmpty>
			</td>
		</tr>

		<tr id="JS_nc">
			<td>NIF / CIF:</td>
			<td><input name="client[client_id_no]" dname="NIF / CIF" class="text" value="<?=htmlspecialchars($_POST["client"]["client_id_no"])?>"></td>
		</tr>

		<tr>
			<td><span class="warn">*</span> Billing Address: </td>
			<td>
				<input name="client[address_1]" dname="Address Line 1" class="text" value="<?=htmlspecialchars($_POST["client"]["address_1"])?>" notEmpty>
				<input name="client[address_2]" dname="Address Line 2" class="text" value="<?=htmlspecialchars($_POST["client"]["address_2"])?>">
			</td>
		</tr>
		<tr>
			<td><span class="warn">*</span> City/Town: </td>
			<td>
				<input name="client[city]" dname="City" class="text" value="<?=htmlspecialchars($_POST["client"]["city"])?>" notEmpty>
			</td>
		</tr>
		<tr>
			<td><span id="warn"></span> State: </td>
			<td>
				<input id="state" name="client[state]" dname="State" class="text" value="<?=htmlspecialchars($_POST["client"]["state"])?>" >
				<select id="state_us" name="client[state_us]" class="text">
					<option value=""></option>
					<?php
						if($state_list)
						{
							if ($_POST["client"]["state"])
							{
								$c_selected[$_POST["client"]["state"]] = " SELECTED";
							}

							foreach($state_list as $state_obj)
							{
					?>
					<option value="<?=$state_obj->get_state_id()?>"<?=$c_selected[$state_obj->get_state_id()]?>><?=$state_obj->get_name()?>
					<?php
							}
						}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td>Postal Code: </td>
			<td>
				<input name="client[postcode]" dname="Postcode" class="text" value="<?=htmlspecialchars($_POST["client"]["postcode"])?>">
			</td>
		</tr>
		<tr>
			<td>Telephone number: </td>
			<td>
				<input name="client[tel_1]" dname="Telephone Country Code" size="3" value="<?=htmlspecialchars($_POST["client"]["tel_1"])?>"> -
				<input name="client[tel_2]" dname="Telephone Area Code"  size="3" value="<?=htmlspecialchars($_POST["client"]["tel_2"])?>"> -
				<input name="client[tel_3]" dname="Telephone" style="width:190px" value="<?=htmlspecialchars($_POST["client"]["tel_3"])?>">
			</td>
		</tr>
		<tr>
			<td>Mobile number: </td>
			<td>
				<input name="client[mtel_1]" dname="Mobile Country Code" size="3" value=""> -
				<input name="client[mtel_2]" dname="Mobile Area Code"  size="3" value=""> -
				<input name="client[mtel_3]" dname="Mobile" style="width:190px" value="<?=htmlspecialchars($_POST["client"]["mtel_1"]).htmlspecialchars($_POST["client"]["mtel_2"]).htmlspecialchars($_POST["client"]["mtel_3"])?>">
			</td>
		</tr>
		<tr>
			<td height="10px"></td>
		</tr>
		<tr id='del_country_id' style="display:none">
			<td><span class="warn">*</span> Delivery Location: </td>
			<td>
				<select id='d_country_id' name="client[del_country_id]" class="text" onchange="update_state_field()">
					<?php
						if($country_list)
						{
							if ($_POST["client"]["del_country_id"])
							{
								$c_selected[$_POST["client"]["del_country_id"]] = " SELECTED";
							}
							else
							{
								$c_selected[$pbv_obj->get_platform_country_id()] = " SELECTED";
							}
							foreach($country_list as $id=>$name)
							{
					?>
					<option value="<?=$id?>"<?=$c_selected[$id]?>><?=$name?>
					<?php
							}
						}
					?>
				</select>
			</td>
		</tr>
		<tr id='del_company' style="display:none">
			<td> Delivery Company Name: </td>
			<td><input name="client[del_company]" dname="Delivery Company" class="text2" value="<?=htmlspecialchars($_POST["client"]["del_company"])?>"></td>
		</tr>
		<tr id='del_name' style="display:none">
			<td><span class="warn">*</span> Delivery Name: </td>
			<td>
				<?$t_selected[$_POST["client"]["del_title"]] = " SELECTED";?>
				<select name="client[del_title]">
					<option value="Mr"<?=$t_selected["Mr"]?>>Mr
					<option value="Mrs"<?=$t_selected["Mrs"]?>>Mrs
					<option value="Miss"<?=$t_selected["Miss"]?>>Miss
					<option value="Dr"<?=$t_selected["Dr"]?>>Dr
				</select>
				<input id="del_name" name="client[del_name]" dname="Delivery First Name" class="text2" value="<?=htmlspecialchars($_POST["client"]["del_name"])?>" >
			</td>
		</tr>
		<tr id='del_address' style="display:none">
			<td><span class="warn">*</span> Delivery Address: </td>
			<td>
				<input id="del_address_1" name="client[del_address_1]" dname="Delivery Address Line 1" class="text" value="<?=htmlspecialchars($_POST["client"]["del_address_1"])?>">
				<input name="client[del_address_2]" dname="Delivery Address Line 2" class="text" value="<?=htmlspecialchars($_POST["client"]["del_address_2"])?>">
			</td>
		</tr>
		<tr id='del_city' style="display:none">
			<td><span class="warn">*</span> Delivery City/Town: </td>
			<td>
				<input id="del_city_town" name="client[del_city]" dname="Delivery City" class="text" value="<?=htmlspecialchars($_POST["client"]["del_city"])?>">
			</td>
		</tr>
		<tr id='del_state' style="display:none">
			<td><span id="d_warn"></span> Delivery State: </td>
			<td>
				<input id="d_state" name="client[del_state]" dname="Delivery State" class="text" value="<?=htmlspecialchars($_POST["client"]["del_state"])?>">
				<select id="d_state_us" name="client[del_state_us]" class="text">
					<option></option>
					<?php
						if($state_list)
						{
							if ($_POST["client"]["del_state"])
							{
								$c_selected[$_POST["client"]["del_state"]] = " SELECTED";
							}

							foreach($state_list as $state_obj)
							{
					?>
					<option value="<?=$state_obj->get_state_id()?>"<?=$c_selected[$state_obj->get_state_id()]?>><?=$state_obj->get_name()?>
					<?php
							}
						}
					?>
				</select>
			</td>
		</tr>
		<tr id='del_postcode' style="display:none">
			<td>Delivery Postal Code: </td>
			<td>
				<input name="client[del_postcode]" dname="Delivery Postcode" class="text" value="<?=htmlspecialchars($_POST["client"]["del_postcode"])?>">
			</td>
		</tr>
		<tr id='del_tel' style="display:none">
			<td>Delivery Telephone number: </td>
			<td>
				<input name="client[del_tel_1]" dname="Delivery Telephone Country Code" size="3" value="<?=htmlspecialchars($_POST["client"]["del_tel_1"])?>"> -
				<input name="client[del_tel_2]" dname="Delivery Telephone Area Code"  size="3" value="<?=htmlspecialchars($_POST["client"]["del_tel_2"])?>"> -
				<input name="client[del_tel_3]" dname="Delivery Telephone" style="width:190px" value="<?=htmlspecialchars($_POST["client"]["del_tel_3"])?>">
			</td>
		</tr>
		<tr id='del_mobile' style="display:none">
			<td>Delivery Mobile number: </td>
			<td>
				<input name="client[del_mtel_1]" dname="Delivery Mobile Country Code" size="3" value=""> -
				<input name="client[del_mtel_2]" dname="Delivery Mobile Area Code"  size="3" value=""> -
				<input name="client[del_mtel_3]" dname="Delivery Mobile" style="width:190px" value="<?=htmlspecialchars($_POST["client"]["del_mtel_1"]).htmlspecialchars($_POST["client"]["del_mtel_2"]).htmlspecialchars($_POST["client"]["del_mtel_3"])?>">
			</td>
		</tr>
		<tr>
			<td colspan="2" height="20px"><hr></td>
		</tr>
		<tr>
			<td><span class="warn">*</span> Reason For Order: </td>
			<td>
				<select name="so_extend[order_reason]" dname="Reason For Order" notEmpty>
					<option value=""> </option>
<?php
	$or_selected[$_POST["so_extend"]["order_reason"]] = " SELECTED";
	foreach($order_reason_list as $reason)
	{
		print "<option value='" . $reason->get_reason_id() . "' " . $or_selected[$reason->get_reason_id()] . ">" . $reason->get_reason_display_name() . "</option>";
	}
?>
				</select>
			</td>
		</tr>
		<tr>
			<td>Additional Note: </td>
			<td>
				<input type="text" name="so_extend[notes]" class="input" value="<?=htmlspecialchars($_POST["so_extend"]["notes"])?>" maxLen="255">
			</td>
		</tr>
		<tr height="20px">
		</tr>
		<tr>
			<td colspan="2" style="text-align:center"><input type="submit" value="Procced"></td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
	</table>
	<input type="hidden" name="posted" value="1">
	<input type="hidden" name="payment_gateway" value="worldpay_moto"> <!-- offline order default to worldpay -->
	<input type="hidden" name="vat_exempt" value="<?=$vat_exempt?>">
	<input type="hidden" name="free_delivery" value="<?=$free_delivery?>">
	<input type="hidden" name="client[id]" value="<?=$_POST["client"]["id"]?>">
	<input type="hidden" name="country" value="<?=$this->input->post("country")?>">
	<input type="hidden" name="promotion_code" value="<?=$_SESSION["promotion_code"]?>">
</form>

</div>
<?=$notice["js"]?>
<script>

function nif_cif(){
	country_id = jQuery("#country_id  option:selected").val();
	if (country_id == "IT" || country_id == "ES") {
		jQuery("#JS_nc").show();
	} else {
		jQuery("#JS_nc").hide();
	};
}
jQuery(function(){
	nif_cif();
  	jQuery("#country_id").live("click",function(){
    	nif_cif();
  	});
});

function response(str)
{
	var fm = document.fm_checkout;
	client = fetch_params('?'+str);
	fm.elements['client[id]'].value = client["id"];
	fm.elements['client[email]'].value = client["email"];
	fm.elements['client[country_id]'].value = client["country_id"];
	fm.elements['client[del_country_id]'].value = client["del_country_id"];
	update_state_field();
	fm.elements['client[companyname]'].value = client['companyname'];
	fm.elements['client[title]'].value = client["title"];
	fm.elements['client[forename]'].value = client["forename"];
	fm.elements['client[surname]'].value = client["surname"];
	fm.elements['client[client_id_no]'].value = client["client_id_no"];
	fm.elements['client[address_1]'].value = client["address_1"];
	fm.elements['client[address_2]'].value = client["address_2"];
	fm.elements['client[city]'].value = client["city"];
	fm.elements['client[state]'].value = client["state"];
	fm.elements['client[state_us]'].value = client["state"];
	fm.elements['client[postcode]'].value = client["postcode"];
	fm.elements['client[tel_1]'].value = client["tel_1"];
	fm.elements['client[tel_2]'].value = client["tel_2"];
	fm.elements['client[tel_3]'].value = client["tel_3"];
	fm.elements['client[mtel_3]'].value = client["mobile"];
	fm.elements['client[del_name]'].value = client["del_name"];
	fm.elements['client[del_company]'].value = client["del_company"];
	fm.elements['client[del_address_1]'].value = client["del_address_1"];
	fm.elements['client[del_address_2]'].value = client["del_address_2"];
	fm.elements['client[del_city]'].value = client["del_city"];
	fm.elements['client[del_state]'].value = client["del_state"];
	fm.elements['client[del_state_us]'].value = client["del_state"];
	fm.elements['client[del_postcode]'].value = client["del_postcode"];
	fm.elements['client[del_tel_1]'].value = client["del_tel_1"];
	fm.elements['client[del_tel_2]'].value = client["del_tel_2"];
	fm.elements['client[del_tel_3]'].value = client["del_tel_3"];
	fm.elements['client[del_mtel_3]'].value = client["del_mobile"];
	fm.elements['client[password]'].disabled = true;
	fm.elements['client[email]'].focus();

}

function CheckSubmit(fm)
{
	if (document.getElementById('country_id').value == 'US'
		&& document.getElementById('state_us').value == '')
	{
		alert('Please select a state');
		return false;
	}

	if (fm.billaddr.checked && document.getElementById('d_country_id').value == 'US'
		&& document.getElementById('d_state_us').value == '')
	{
		alert('Please select a delivery state');
		return false;
	}

	return CheckForm(fm);
}

<?php
	if($left_reload)
	{
?>
		parent.frames["fcart"].document.fm_cart.target = 'fcart';
		parent.frames["fcart"].document.fm_cart.elements["country"].value = '<?=$country_id?>';
		parent.frames["fcart"].document.fm_cart.submit();
<?php
	}
?>

<?php
	if($client != "")
	{
?>
		response('<?=html_entity_decode($client, ENT_QUOTES, 'UTF-8')?>');
<?php
	}
?>
</script>
</body>
</html>