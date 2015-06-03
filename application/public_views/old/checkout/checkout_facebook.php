<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon"/>
<title>ChatandVision - Specialising in Skype Certified Headsets, Speakers, Webcams and Mobile Phones</title>
<link href="/css/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="/css/lytebox.css" type="text/css" media="screen" />
<link rel="stylesheet" href="/css/lytebox_ext.css" type="text/css" media="screen" />
<script src="<?=base_url()?>js/top_up-min.js" type="text/javascript"></script>
<script src="<?=base_url()?>js/jquery.js" type="text/javascript"></script>
<script src="<?=base_url()?>js/common.js" type="text/javascript"></script>
<script src="<?=base_url()?>js/lytebox_cv.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>js/checkform.js?lang=<?=get_lang_id()?>" type="text/javascript"></script>
<script type="text/javascript" language="javasript">
function ChgPromoMsg(rs, framecall, msg)
{
	if (!rs)
	{
		alert_msg = msg?msg:'Sorry, Promotion Code Invalid';
		document.getElementById('promo_msg').style.color = 'red';
		document.getElementById('promo_msg').innerHTML = alert_msg;
	}
}

function ProcessPaymentForm()
{
	document.getElementById('tblCart').style.display = 'none';
	document.getElementById('psform').style.display = 'block';
}
</script>
</head>

<body link="#60D4FF">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<col width="5"><col><col width="5">

		<tr>
			<td background="/images/02category_95.gif" style="background-repeat:repeat"></td>
			<td background="/images/02category_99.gif" height="2"></td>
			<td background="/images/02category_97.gif" style="background-repeat:repeat"></td>
		</tr>

		<tr>
			<td background="/images/02category_95.gif" style="background-repeat:repeat"></td>
			<td>
				<table border="0" cellspacing="0" cellpadding="0" id="tblCart" style="width:100%">
					<col width="30%"><col><col>

					<?php
						$total = 0;
						$cur_item_sub_total = 0;
						for($j=0; $j<count($chk_cart); $j++)
						{
							$idx = $j+1;
							$item = $cart_item[$chk_cart[$j]["sku"]];
							if($item->get_display_quantity())
							{
								$in_stock = min($item->get_website_quantity(),$item->get_display_quantity());
							}
							else
							{
								$in_stock = $item->get_website_quantity();
							}
							$quantity = max($in_stock, $chk_cart[$j]["qty"]);
					?>

							<tr>
								<td rowspan="3" align="center">
									<img src="<?=base_url().get_image_file($item->get_image(), 'm', $item->get_sku())?>" border='0' />
								</td>
								<td style="padding:3px;">
									<font size="2"><strong><?=$item->get_content_prod_name()?$item->get_content_prod_name():$item->get_prod_name()?></strong></font>
								</td>
								<td align="right">
									<font size="2" color="#00aff0"><strong><?=platform_curr_format(PLATFORMID, $chk_cart[$j]["price"])?></strong></font>
								</td>
							</tr>
							<tr>
								<td>
									<b>Quantity</b>
									<?php
										if ($chk_cart[$j]["promo"])
										{
									?>
											<?=$chk_cart[$j]["qty"]?>
									<?php
										}
										else
										{
											if($quantity)
											{
									?>
											<select name="qty<?=$idx?>" id="select" onChange="Redirect('<?=base_url().'checkout_facebook/update/'.$chk_cart[$j]["sku"]?>/' + this.value<?=$debug ? " + '/$debug'" : ''?>)">
											<?php
												for($i = 1;$i <= $quantity; $i++)
												{
											?>
											<option value="<?=$i?>" <?=$chk_cart[$j]["qty"] == $i?"SELECTED":""?>><?=$i?></option>
											<?php
												}
											?>
											</select>
									<?php
											}
										}
									?>
								</td>
								<td></td>
							</tr>
							<tr>
								<td>
									<?php
										if ($in_stock)
										{
											echo '<span style="color:#999999;">' . $in_stock . ' units in stock.</span>';
										}
										else
										{
											echo '<span style="font-size:10px;color:#666666; color:red;">Out of stock</span>';
										}
									?>
								</td>
								<td></td>
							</tr>
							<tr>
								<td align="center">
									<span id="link">
										<font face="Arial, Helvetica, sans-serif" size="1" color="#8e8e8e">
											<a href="<?=base_url().get_image_file($item->get_image(), '', $item->get_sku());?>" class="top_up" target="CV_Facebook">
											<strong>Click for a bigger view</strong>&nbsp;<img border=0px src="<?=base_url()?>images/04products_magglass.gif" width="11" height="11" /></a>
										</font>
									</span>
								</td>
								<td></td>
								<td></td>
							</tr>

					<?php
							$cur_item_sub_total = $chk_cart[$j]["price"] * $chk_cart[$j]["qty"];
							$total += $cur_item_sub_total;
							$idx++;
							$delivery = 0;
						}
					?>

					<tr>
						<td colspan="3"><img height="2" width="100%" src="/images/line_blue.png" /></td>
					</tr>
					<tr>
						<td colspan="3">
							<table cellspacing="5" cellpadding="0" cellspacing="0" border="0" width="100%">
								<tr>
									<td>
										<form id="fm_delivery" name="fm_delivery" action="<?=base_url()?>checkout_facebook<?=$debug?"/index/1":""?>" method="post">
											<?php
												if ($dc)
												{
													$checked_delivery = $promo["free_delivery"]?$promo["promotion_code_obj"]->get_disc_level_value():($dc[$_POST["delivery"]]?$_POST["delivery"]:$dc_default["courier"]);
													$d_checked[$checked_delivery] = " CHECKED";
													$delivery = $dc[$checked_delivery]["charge"];
													$surcharge = $dc[$checked_delivery]["surcharge"];
													foreach ($dc as $courier_id=>$ar_dc)
													{
														$text_courier_id = strtolower($courier_id);
														$display_text = ($dc[$courier_id]["charge"] == 0)?$text_free[$text_courier_id]:$ar_dc["display_name"];
														if ($count_dc == 1)
														{
															continue;
														}
											?>
														<tr>
															<td nowrap style="white-space:nowrap"><input type="radio" name="delivery" align="absbottom" value="<?=$courier_id?>"<?=$d_checked[$courier_id]?> onClick="this.form.submit()"> <?=$display_text?> (<?=$ar_dc["working_days"]?> <?=$text_working_days?>) &nbsp;</td>
															<td nowrap style="white-space:nowrap"><?=platform_curr_format(PLATFORMID, $ar_dc["charge"])?></td>
															<td width="25%">&nbsp</td>
														</tr>
											<?php
													}
												}
											?>
										</form>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td colspan="3"><img height="1" width="100%" src="/images/line_blue.png" /></td>
					</tr>
					<tr>
						<td colspan="3">
							<table border="0" cellpadding="0" cellspacing="0" width="100%" style="line-height:20px;">
								<col width="80%"><col>

								<tr>
									<?php
										$promo_disc_amount = 0;
										if ($promo["valid"] && isset($promo["disc_amount"]))
										{
											$promo_disc_amount = $promo["disc_amount"];
									?>
											<td align="right"><span style="font-weight: bold;">facebook promotion: </span></td>
											<td align="right">
												<span style="font-size:14px; font-weight:bold; color:#c90509">-<?=platform_curr_format(PLATFORMID, $promo_disc_amount)?></span>
											</td>
									<?php
										}
										else
										{
									?>
											<td colspan="2"><span id="promo_msg" style="font-weight:bold;line-height:13px;"></span></td>
									<?php
										}
									?>
								</tr>
								<tr>
									<td align="right"><span style="font-weight: bold;">Grand Total: </span></td>
									<td align="right">
										<span id="span_total" style="font-weight: bold; color: rgb(0, 175, 240); font-size: 14px;"><?=platform_curr_format(PLATFORMID, ($grand_total = $total + $delivery - $promo_disc_amount))?></span>
										<input type="hidden" id="input_total" value="<?=$grand_total?>">
									</td>
								</tr>
							</table>
						</td>
					</tr>

					<tr>
						<td colspan="3">
							<table border="0" cellpadding="10" cellspacing="0" width="100%">
								<tr valign="bottom">
									<td><font color="#00aff0">Brought to you by ChatandVision</font></td>
									<td align="right"><input type="image" src="<?=base_url()?>images/btn_continue_<?=get_lang_id()?>.png" height="30" onClick="ProcessPaymentForm();" /></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>

				<iframe width="<?=$iframe_width?>px" height="<?=$iframe_height?>px" noresize frameborder="0" marginwidth="0" marginheight="0" src="<?=base_url()?>checkout_facebook/psform<?=$debug?"?debug=1":""?>" name='psform' id="psform" style="display:none"></iframe>
			</td>

			<td background="/images/02category_97.gif" style="background-repeat:repeat"></td>
		</tr>

		<tr>
			<td background="/images/02category_98.gif" style="background-repeat:repeat"></td>
			<td background="/images/02category_99.gif" height="2"></td>
			<td background="/images/02category_100.gif" style="background-repeat:repeat"></td>
		</tr>
	</table>

	<?php
		if (isset($promo))
		{
			$msg = ($promo["error"] && $promo["error"] == "FD")?(
										$promo["error_code"]==$default_delivery?"Promotion Code is only valid for {$text_delivery_display}. Please reselect your shipping option.":
																				"No {$text_delivery_display} option available for your order."
										):FALSE;
	?>
	<script>
		ChgPromoMsg(<?=(!$promo["valid"] || $promo["error"])?0:1?><?=$msg?", 0, '{$msg}'":""?>);
	</script>
	<?php
		}
	?>

</body>
</html>