<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon"/>
<title>ValueBasket - Specialising in Skype Certified Headsets, Speakers, Webcams and Mobile Phones</title>
<link href="/css/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="/css/lytebox.css" type="text/css" media="screen" />
<link rel="stylesheet" href="/css/lytebox_ext.css" type="text/css" media="screen" />
<script src="/js/jquery.js" type="text/javascript"></script>
<script src="/js/common.js" type="text/javascript"></script>
<script src="/js/lytebox_cv.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>js/checkform.js?lang=<?=get_lang_id()?>" type="text/javascript"></script>
<script type="text/javascript" language="javasript">
function adjFrame()
{
	SetFrameHeight(document.getElementById("psform"), $("#content_bottom").offset().top - $("#psform").offset().top, false);
}

function ChgPromoMsg(rs, framecall, msg)
{
	if (rs)
	{
		document.getElementById('promo_msg').style.color = 'green';
		document.getElementById('promo_msg').innerHTML = 'Promotion Code Accepted';
	}
	else
	{
		alert_msg = msg?msg:'Sorry, Promotion Code Invalid';
		document.getElementById('promo_msg').style.color = 'red';
		document.getElementById('promo_msg').innerHTML = alert_msg;
		document.getElementById('promotion_code').value = '';
		if (framecall)
		{
			top.frames["psform"].myLytebox.end();
			alert(alert_msg);
			document.fm_promo.submit();
		}
	}
}

function GetEmail(fm)
{
	try
	{
		email_val = top.frames["psform"].document.fm_pmgw.email.value;
		if (email_val)
		{
			fm.email.value = email_val;
		}
	}
	catch(err){};
}
</script>
<script src="//cdn.optimizely.com/js/8554725.js"></script>
</head>

<body link="#60D4FF">
<table width="1024" border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
	<td width="100%">
		<?php include VIEWPATH . 'header.php';?>
	</td>
</tr>
<tr>
	<td><table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td height="5"></td>
			</tr>
			<tr>
				<td><table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td height="30" colspan="2"><font size="4" style="padding-left:15px;"><strong>Shopping Basket</strong></font></td>
						</tr>
						<tr>
							<td align="center"><table width="520" border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td width="100" align="center"><font size="2"><strong>Select payment</strong></font></td>
										<td width="60">&nbsp;</td>
										<td width="50" align="center"><font size="2" color="#8E8E8E"><strong>Sign in</strong></font></td>
										<td width="60">&nbsp;</td>
										<td width="60" align="center"><font size="2" color="#8E8E8E"><strong>Payment</strong></font></td>
										<td width="60">&nbsp;</td>
										<td width="130" align="center"><font size="2" color="#8E8E8E"><strong>Order Confirmation</strong></font></td>
									</tr>
									<tr>
										<td width="100" height="20" align="center"><img src="/images/dot_confirmed.gif" width="13" height="13" /></td>
										<td width="60" height="20" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
												<tr>
													<td align="center"><img src="/images/dot_progress.gif" width="6" height="6" /></td>
													<td align="center"><img src="/images/dot_progress.gif" width="6" height="6" /></td>
												</tr>
											</table>
										</td>
										<td width="50" height="20" align="center"><img src="/images/dot_unconfirmed.gif" width="13" height="13" /></td>
										<td width="60" height="20" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
												<tr>
													<td align="center"><img src="/images/dot_progress.gif" width="6" height="6" /></td>
													<td align="center"><img src="/images/dot_progress.gif" width="6" height="6" /></td>
												</tr>
											</table>
										</td>
										<td width="60" height="20" align="center"><img src="/images/dot_unconfirmed.gif" width="13" height="13" /></td>
										<td width="60" height="20" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
												<tr>
													<td align="center"><img src="/images/dot_progress.gif" width="6" height="6" /></td>
													<td align="center"><img src="/images/dot_progress.gif" width="6" height="6" /></td>
												</tr>
											</table>
										</td>
										<td width="130" height="20" align="center"><img src="/images/dot_unconfirmed.gif" width="13" height="13" /></td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td><table width="1024" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td height="15"></td>
							<td height="15"></td>
							<?php
								if ($chk_cart)
								{
							?>
							<td width="289" rowspan="2" valign="top">
								<table width="289" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td height="15" colspan="15"></td>
									</tr>

									<?php
										if (!$_SESSION["client"]["logged_in"])
										{
									?>
									<tr>
										<td height="32" colspan="15" align="center" background="/images/orderformbox_02.gif"><font size="3" color="#ffffff"><strong>Existing Customers</strong></font></td>
									</tr>
									<tr>
										<td background="/images/orderformbox_03.gif"><img src="/images/orderformbox_03.gif" width="15" height="100" /></td>
										<td height="32" colspan="13" valign="top">
											<form name="fm_chk_login" id="fm_chk_login" method="post" action="<?=base_url()?>login/ws_login?back=checkout">
											<table width="258" border="0" cellspacing="0" cellpadding="0">
												<tr>
													<td height="15"></td>
												</tr>
												<tr>
													<td align="center">Sign in if you already have an account with us!</td>
												</tr>
												<tr>
													<td height="15"></td>
												</tr>
												<tr>
													<td align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
															<tr>
																<td>E-mail</td>
																<td width="170"><input type="text" name="email" dname="E-mail" notEmpty validEmail style="width: 170px" /></td>
															</tr>
														</table>
													</td>
												</tr>
												<tr>
													<td height="10"></td>
												</tr>
												<tr>
													<td><table width="100%" border="0" cellspacing="0" cellpadding="0">
															<tr>
																<td>Password</td>
																<td width="170"><input type="password" name="password" dname="Password" notEmpty style="width: 170px" /></td>
															</tr>
														</table>
													</td>
												</tr>
												<tr>
													<td height="10"></td>
												</tr>
												<tr>
													<td><table width="143" border="0" align="center" cellpadding="0" cellspacing="0">
															<tr>
																<td height="39" align="center" background="/images/orderformbox_23.gif" onClick="if (CheckForm(document.fm_chk_login)) {document.fm_chk_login.submit();}" style="cursor:pointer"><font size="2" color="#ffffff"><strong>Sign in and continue</strong></font></td>
															</tr>
														</table>
													</td>
												</tr>
												<tr>
													<td height="10"></td>
												</tr>
												<tr>
													<td align="center"><a id="a_check" href="<?=base_url()?>forget_password?back=checkout" rel="lyteframe" rev="width: 600px; height:400px; scrolling: auto;padding: 40px;">Forgotten password?</a></td>
												</tr>
											</table>
											<input type="hidden" name="posted" value="1">
											</form>
										</td>
										<td background="/images/orderformbox_05.gif"><img src="/images/orderformbox_05.gif" width="15" height="100" /></td>
									</tr>
									<tr>
										<td height="15"><img src="/images/orderformbox_26.gif" width="15" height="15" /></td>
										<td height="15" colspan="13"><img src="/images/orderformbox_27.gif" width="259" height="15" /></td>
										<td height="15"><img src="/images/orderformbox_28.gif" width="15" height="15" /></td>
									</tr>
									<tr>
										<td height="20" colspan="15"></td>
									</tr>
									<?php
										}
									?>

									<tr>
										<td height="32" colspan="15" align="center" background="/images/orderformbox_02.gif"><font size="3" color="#ffffff"><strong><?=$_SESSION["client"]["logged_in"] ? "Purchase Information" : "New customers"?></strong></font></td>
									</tr>
									<tr>
										<td background="/images/orderformbox_03.gif"></td>
										<td colspan="13" valign="top">
											<iframe width="258" height="800" allowtransparency="true" noresize frameborder="0" marginwidth="0" marginheight="0" hspace=0 vspace=0 src="<?=base_url()?>checkout/psform<?=$debug?"?debug=1":""?>" name='psform' id="psform" onLoad="SetFrameHeight(this)"></iframe></td>
										<td background="/images/orderformbox_05.gif"></td>
									</tr>
									<tr>
										<td height="15"><img height="15" width="15" src="/images/orderformbox_26.gif" /></td>
										<td height="16" colspan="13"><img height="15" width="259" src="/images/orderformbox_27.gif" /></td>
										<td height="15"><img height="15" width="15" src="/images/orderformbox_28.gif" /></td>
									</tr>
								</table>
							</td>
							<?php
								}
							?>
						</tr>
						<tr>
							<td valign="top"><table border="0" cellspacing="0" cellpadding="0">
								<?php
									if ($chk_cart)
									{
								?>
									<tr>
										<td><table width="660" border="0" cellpadding="0" cellspacing="0">
												<tr>
													<td><img src="/images/02category_92.gif" width="5" height="28" /></td>
													<td bgcolor="#00AFF0" colspan="7"><font size="3" color="#ffffff" style="padding-left:15px;"><strong>Order Summary</strong></font></td>
													<td><img src="/images/02category_94.gif" width="5" height="28" /></td>
												</tr>
												<tr height="2px">
													<td background="/images/02category_95.gif" style="background-repeat:repeat"><img src="/images/02category_95.gif" width="5" height="2" /></td>
													<td colspan="7"></td>
													<td background="/images/02category_97.gif" style="background-repeat:repeat"><img src="/images/02category_97.gif" width="5" height="2" /></td>
												</tr>
												<tr>
													<td background="/images/02category_95.gif" style="background-repeat:repeat"><img src="/images/02category_95.gif" width="5" height="20" /></td>
													<td bgcolor="#ececec" width="22" align="center"></td>
													<td bgcolor="#ececec" align="center" colspan="2"><font size="2"><strong>Product</strong></font></td>
													<td bgcolor="#ececec" width="80" align="center"><font size="2"><strong>Price</strong></font></td>
													<td bgcolor="#ececec" width="50" align="center"><font size="2"><strong>Qty</strong></font></td>
													<td bgcolor="#ececec" width="80" align="center"><font size="2"><strong>Total</strong></font></td>
													<td bgcolor="#ececec" width="12">&nbsp;</td>
													<td background="/images/02category_97.gif" style="background-repeat:repeat"><img src="/images/02category_97.gif" width="5" height="20" /></td>
												</tr>
												<?php
													$total = 0;
													for($j=0; $j<count($chk_cart); $j++)
													{
														$idx = $j+1;
														$item = $cart_item[$chk_cart[$j]["sku"]];
														if($item->get_display_quantity())
														{
															$quantity = min($item->get_website_quantity(),$item->get_display_quantity());
														}
														else
														{
															$quantity = $item->get_website_quantity();
														}
														$quantity = max($quantity, $chk_cart[$j]["qty"]);
												?>
												<tr>
													<td background="/images/02category_95.gif" style="background-repeat:repeat"></td>
													<td align="right">
													<?php
														if (!$chk_cart[$j]["promo"])
														{
													?>
															<a href="<?=base_url().'checkout/remove/'.$chk_cart[$j]["sku"].($debug?"/$debug":'')?>"><img src="/images/btn_delete.png" width="21" height="21" /></a>
													<?php
														}
													?>
													</td>
													<td width="80" height="60" align="center"><img width="50" src="<?=base_url().get_image_file($item->get_image(), 'm', $item->get_sku())?>" border='0' /></td>
													<td style="padding:3px;">
														<a href="<?=base_url()?>mainproduct/info/<?=$chk_cart[$j]["sku"]?>" rel="lyteframe" rev="width: 600px; height:500px; scrolling: auto;padding: 40px;"><font size="2"><strong><?=$item->get_content_prod_name()?$item->get_content_prod_name():$item->get_prod_name()?></strong></font></a>
													</td>
													<td align="right"><font size="2" color="#00aff0"><strong><?=platform_curr_format(PLATFORMID, $chk_cart[$j]["price"])?></strong></font></td>
													<td align="center">
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
																<select name="qty<?=$idx?>" id="select" onChange="Redirect('<?=base_url().'checkout/update/'.$chk_cart[$j]["sku"]?>/' + this.value<?=$debug ? " + '/$debug'" : ''?>)">
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
													<td align="right"><font size="2" color="#00aff0"><strong><?=platform_curr_format(PLATFORMID, $cur_item_sub_total = $chk_cart[$j]["price"] * $chk_cart[$j]["qty"])?></strong></font></td>
													<td>&nbsp;</td>
													<td background="/images/02category_97.gif" style="background-repeat:repeat"></td>
												</tr>
												  <tr height="2">
													<td background="/images/02category_95.gif" style="background-repeat:repeat"></td>
													<td></td>
													<td colspan="6"><img src="/images/03Subcategory_line.gif" width="600" height="2" /></td>
													<td background="/images/02category_97.gif" style="background-repeat:repeat"></td>
												</tr>

												<?php
														$total += $cur_item_sub_total;
														$idx++;
														$delivery = 0;
													}
												?>
												<tr>
													<td background="/images/02category_95.gif" style="background-repeat:repeat"><img src="/images/02category_95.gif" width="5" height="130" /></td>
													<td colspan="7">
														<table cellspacing="4" cellpadding="0" border="0" width="100%">
																<tr>
																	<td colspan="2">
																		<form id="fm_delivery" name="fm_delivery" action="<?=base_url()?>checkout<?=$debug?"/index/1":""?>" method="post">
																			<table border="0" cellpadding="0" cellspacing="0" width="100%" style="line-height:20px;">
																				<tr>
																					<td>
																						<table border="0" cellpadding="0" cellspacing="0" width="100%">
																						<?php
																							if ($dc)
																							{
																								if (($count_dc = count($dc))>1)
																								{
																						?>
																							<tr>
																								<td colspan="3" style="color:#666666;font-size:12px;"><b>Select a Delivery Method</b></td>
																							</tr>
																						<?php
																								}
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
																						</table>
																					</td>
																					<td>
																						<table border="0" cellpadding="0" cellspacing="0" width="100%">
																							<tr>
																								<td align="right"><span style="font-size:12px; font-weight:bold" >Subtotal: </span></td>
																								<td width="120" align="right" nowrap style="white-space:nowrap"><span style="font-size:14px; font-weight:bold" ><?=platform_curr_format(PLATFORMID, $total)?></span></td>
																							</tr>
																							<tr>
																								<td align="right" nowrap style="white-space:nowrap">
																								<?php
																									if ($count_dc == 1)
																									{
																										$display_working_days = $ar_dc["working_days"] ? "({$ar_dc["working_days"]} {$text_working_days})" : "";
																								?>
																									<input type="hidden" name="delivery" value="<?=$courier_id?>"> <?=$display_text?> <?=$display_working_days?>
																								<?php
																									}
																									else
																									{
																								?>
																									Delivery:
																								<?php
																									}
																								?>
																								</td>
																								<td width="120" align="right"><span style="color:#c90509; font-weight:bold;"><?=platform_curr_format(PLATFORMID, $delivery)?></span></td>
																							</tr>
																							<tr align="right" nowrap style="white-space:nowrap;">
																								<td id="lbl_surcharge"></td>
																								<td>
																									<input type="hidden" name="del_surcharge" value="<?=$surcharge?>">
																									<span style="color:#c90509; font-weight:bold;" id="span_surcharge"><?=$surcharge*1?platform_curr_format(PLATFORMID, $surcharge):""?></span>
																								</td>
																							</tr>
																						</table>
																					</td>
																				</tr>
																			</table>
																		</form>
																	</td>
																	<td width="2">&nbsp;</td>
																</tr>
																<tr>
																	<td align="right" colspan="3"><img height="1" width="100%" src="/images/line_blue.png" /></td>
																</tr>
																<tr>
																	<td colspan="2">
																		<table border="0" cellpadding="0" cellspacing="0" width="100%" style="line-height:20px;">
																			<tr>
																				<td style="color:#666666;font-size:12px;" width="45%"><b>Do you have a promotion code?</b></td>
																				<?php
																					$promo_disc_amount = 0;
																					if ($promo["valid"] && isset($promo["disc_amount"]))
																					{
																						$promo_disc_amount = $promo["disc_amount"];
																				?>
																				<td align="right" rowspan="2" width="38%">
																						Promotion Code(<?=$_SESSION["promotion_code"]?>):
																				</td>
																				<td rowspan="2" align="right"><span style="font-size:14px; font-weight:bold; color:#c90509">-<?=platform_curr_format(PLATFORMID, $promo_disc_amount)?></span>
																				</td>
																				<?php
																					}
																					else
																					{
																				?>
																				<td colspan="2" rowspan="2" align="right">
																				</td>
																				<?php
																					}
																				?>
																			</tr>
																			<tr>
																				<td style="line-height:25px;">
																					<form id="fm_promo" name="fm_promo" action="<?=base_url()?>checkout<?=$debug?"/index/1":""?>" method="post" onSubmit="GetEmail(this)">
																					<input type="hidden" name="email">
																					<input type="hidden" name="delivery" value="<?=$checked_delivery?>">
																					Enter it here <input id="promotion_code" name="promotion_code" value="<?=$_SESSION["promotion_code"]?>">
																					<?php
																						if ($_SESSION["promotion_code"])
																						{
																					?>
																					<input type="button" onClick="this.form.promotion_code.value='';this.form.submit()" value="Remove">
																					<?php
																						}
																						else
																						{
																					?>
																					<input type="submit" value="Go">
																					<?php
																						}
																					?>
																					<br>
																					<span id="promo_msg" style="font-weight:bold;line-height:13px;"></span>
																					</form>

																				</td>
																			</tr>
																		</table>
																	</td>
																	<td>&nbsp;</td>
																</tr>
																<tr bgcolor="#C0EEFF" height="30px" valign="middle">
																	<td colspan="3" bgcolor="#C0EEFF">
																		<table cellspacing="0" cellpadding="0" border="0" width="100%" bgcolor="#C0EEFF">
																			<tbody>
																				<tr>
																					<td align="right"><span style="font-size: 12px; font-weight: bold;">Grand Total: </span></td>
																					<td align="right" width="120">
																						<span id="span_total" style="font-weight: bold; color: rgb(0, 175, 240); font-size: 16px;"><?=platform_curr_format(PLATFORMID, ($grand_total = $total + $delivery + $surcharge - $promo_disc_amount))?></span>
																						<input type="hidden" id="input_total" value="<?=$grand_total?>">
																					</td>
																					<td bgcolor="#C0EEFF" width="2">&nbsp;</td>
																				</tr>
																			</tbody>
																		</table>
																	</td>
																</tr>
														</table>
													</td>
													<td background="/images/02category_97.gif" style="background-repeat:repeat"><img src="/images/02category_97.gif" width="5" height="20" /></td>
												</tr>
												<tr>
													<td width="5" height="2"><img src="/images/02category_98.gif" width="5" height="2" /></td>
													<td colspan="7" background="/images/02category_99.gif" height="2"></td>
													<td width="5" height="2"><img src="/images/02category_100.gif" width="5" height="2" /></td>
												</tr>
											</table>
										</td>
										<td width="100"><img src="/images/proceed_to_details.png" height="30" /></td>
									</tr>
									<tr>
										<td style="padding-top:30px">
										<?php
											$banner = $checkout_banner;
											$banner_file_path = APPPATH."public_views/banner_publish/publish_".$banner["publish_key"].".php";
											if (file_exists($banner_file_path))
											{
												include ($banner_file_path);
											}
										?>
										</td>
									</tr>
									<?php
										if ($ra_list["recommended"])
										{
									?>
									<tr>
										<td>
									<?php include VIEWPATH . 'ra_bs_tab.php';?>
										</td>
									</tr>
									<?php
										}
									?>
								<?php
									}
									else
									{
								?>
									<tr>
										<td valign="top" height="12" align="left">
											<table cellspacing="2" cellpadding="0" border="0" bgcolor="#ccffff" style="width:99%; border-bottom:#7FD7F7 solid 1px; border-top:#7FD7F7 solid 1px;">
												<tr>
													<td width="30"><img width="30" height="30" src="/images/alert.png"></td>
													<td align="left"><span style="color: rgb(0, 0, 0); font-size: 12px; font-weight: bold;">Your shopping cart is empty!</span></td>
												</tr>
											</table>
										</td>
									</tr>
								<?php
									}
								?>
									<tr height="30">
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td>
											<div class="additionalInformation">
												<p><strong>ValueBasket is Skype's Global Merchant Partner</strong>; as such we meet
													Skype's stringent quality requirements so you can be 100% confident that these
													products will deliver a good Skype experience. Our aim is to give you the best
													audio and visual experience when communicating online.
												</p>
											</div>
											<div class="csInformation">
												<p>Should there be any queries in regards to your purchased product or payment at
													any stage during the checkout process, please don't hesitate to contact our Sales
													or Customer Care Team.</p>
												<p>
													Our Contact details can be found by clicking <u><a target="contact" href="<?=base_url()?>display/ws_view?page=ws_contact">here</a></u> , or please try searching possible solutions to your query via our <u><a href="<?=base_url()?>faq/ws_view" target="faq">FAQ page.</a></u></p>
											</div>
										</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td width="100%" align="left">
<a href="<?=base_url()?>">
	<table width="248" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td width="248" height="40" align="center" background="/images/proceedtocheckout.gif" onmouseover="this.style.cursor='pointer';">
				<font size="3" color="#ffffff"><strong>Continue Shopping <img src="/images/whitearrow.png" width="15" height="15" /></strong></font>
			</td>
		</tr>
	</table>
</a>
											<a id="content_bottom">
										</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
									</tr>
								</table>
							</td>
							<td width="10">&nbsp;</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</td>
</tr>
<tr>
	<td height="40"></td>
</tr>
<tr>
	<td width="100%">
		<?php include VIEWPATH . 'footer_web.php';?>
	</td>
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