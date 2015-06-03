<?php
	if($data["notice"]["js"])
	{
		echo $data["notice"]["js"];
	}
?>

<div id="content">
	<h5 class="side_title"><?=$lang_text['heading']?></h5>
	
	<div id="my_acount" class="product-tabs-info">
		<ul class="tabs">
			<li <?=$order['active']?>><a href="javascript:;" title=""><?=$lang_text['order_history']?></a></li>
			<li <?=$rma['active']?>><a href="javascript:;" title=""><?=$lang_text['returns_request']?></a></li>
			<li <?=$profile['active']?>><a href="javascript:;" title=""><?=$lang_text['edit_profile']?></a></li>
		</ul>

		<div class="text silver_box items item1" <?=$order['block']?>>
			<?=$partial_ship_text?>
			<table class="acount-orders" border="0">
				<!-- <col width="120"><col width="70"><col width="100"><col><col width="70"><col width="250"> -->
				<tr>
					<th><?=$lang_text['order_number']?></th>
					<th><?=$lang_text['order_date']?></th>
					<th><?=$lang_text['shipped_to']?></th>
					<th><?=$lang_text['product_name']?></th>
					<th><?=$lang_text['order_total']?></th>
					<th><?=$lang_text['order_status']?></th>
				</tr>

				<?php
					foreach ($orderlist as $order)
					{
				?>
						<tr>
							<td><?=$order['client_id']?> - <?=$order['join_split_so_no']?><?=$order['print_invoice_html']?></td>
							<td><?=$order['order_date']?></td>
							<td><?=$order['delivery_name']?></td>
							<td><?=$order['product_name']?></td>
							<td><?=$order['total_amount']?></td>
							<td><b><?=$order['order_status']?></b><br /><?=$order['status_desc']?></td>
							<!--
								Using tooltip, Thomas 20120712
							<td><p ONMOUSEOVER="ddrivetip('[orderlist.status_desc;noerr;]', '#E6E6E6', 300)"; ONMOUSEOUT="hideddrivetip()">[orderlist.order_status;noerr;]</p></td>
							-->
						</tr>
				<?php
					}
				?>
			</table>
		</div>

		<div class="silver_box items order_form item2" id="returns_request" <?=$rma['block']?>>
			<form name="fm_rma" action="<?=$base_url?>myaccount/rma" method="post" class="form-holder" onSubmit="return (CheckForm(document.fm_rma) && CheckSubmit(this));">
				<p><?=$confirm_notice?></p>
				<p class="red clear" style="font-size:14px"><?=$lang_text['important_note']?></p><br />
				<p class="clear">
					<b><?=$lang_text['instruction_title']?></b>
				</p>
				<p style="margin-left:1em"><?=$lang_text['instruction_line_1']?></p>
				<p style="margin-left:1em"><?=$lang_text['instruction_line_2']?></p>
				<p style="margin-left:1em"><?=$lang_text['instruction_line_3']?></p>
				<div class="separator"></div>
				<p class="upper clear"><?=$lang_text['personal_details']?></p>
				<ul>
					<li class="clear">
						<label><?=$lang_text['first_name']?>: *</label>
						<fieldset class="size_304"><input type="text" value="<?=$rma_obj['forename']?>" name="forename" dname="<?=$lang_text['first_name']?>" size="40" <?=$disabled?> notEmpty/></fieldset>
					</li>
					<li class="clear">
						<label><?=$lang_text['surname']?>: *</label>
						<fieldset class="size_304"><input type="text" value="<?=$rma_obj['surname']?>" name="surname" dname="<?=$lang_text['surname']?>" size="40" <?=$disabled?> notEmpty/></fieldset>
					</li>
					<li class="clear select">
						<label><?=$lang_text['country']?>: *</label>
						<select id="rma_country_id" name="country_id" dname="<?=$lang_text['country']?>" class="<?=$select_box_style?>" <?=$disabled?> notEmpty onchange="update_state_attribute('rma', this.value);update_postcode_attribute('rma', this.value);">
							<option value=""></option>
							<?php
								foreach ($bill_country_arr2 as $bill_country)
								{
							?>
									<option value="<?=$bill_country['id']?>" <?=$bill_country['selected']?>><?=$bill_country['display_name']?></option>
							<?php
								}
							?>
						</select>
					</li>
					<li class="clear">
						<label><?=$lang_text['city']?>: *</label>
						<fieldset class="size_304"><input type="text" value="<?=$rma_obj['city']?>" name="city" dname="<?=$lang_text['city']?>" size="40" <?=$disabled?> notEmpty isLatin/></fieldset>
					</li>
					<li class="clear">
						<label><?=$lang_text['state']?>: <span id="rma_asterisk">*</span></label>
						<fieldset class="size_304"><input type="text" value="<?=$rma_obj['state']?>" name="state" dname="<?=$lang_text['state']?>" id="rma_state" size="40" <?=$disabled?> notEmpty isLatin/></fieldset>
					</li>
					<li class="clear">
						<label><?=$lang_text['addr_line_1']?>: *</label>
						<fieldset class="size_304"><input type="text" value="<?=$rma_obj['address_1']?>" name="address_1" dname="<?=$lang_text['addr_line_1']?>" size="40" <?=$disabled?> notEmpty isLatin/></fieldset>
					</li>
					<li class="clear">
						<label><?=$lang_text['addr_line_2']?>: </label>
						<fieldset class="size_304"><input type="text" value="<?=$rma_obj['address_2']?>" name="address_2" dname="<?=$lang_text['addr_line_2']?>" size="40" <?=$disabled?> isLatin/></fieldset>
					</li>
					<li class="clear">
						<label><?=$lang_text['postcode']?>: <span id="rma_postcode_asterisk">*</span></label>
						<fieldset class="size_304"><input type="text" value="<?=$rma_obj['postcode']?>" name="postcode" dname="<?=$lang_text['postcode']?>" id="rma_postcode" size="40" validPostal="country_id" <?=$disabled?> isLatin notEmpty/></fieldset>
					</li>
				</ul>
				
				<div class="separator"></div>
			
				<p class="upper clear"><?=$lang_text['order_details']?> *</p>
				<ul>
					<li>
						<label><?=$lang_text['order_number']?></label>
						<fieldset class="size_304"><input type="text" value="<?=$rma_obj['so_no']?>" dname="<?=$lang_text['order_number']?>" name="so_no" size="40" <?=$disabled?>/></fieldset>
					</li>
				</ul>	
				
				<div class="separator"></div>
				
				<p class="upper clear"><?=$lang_text['product_details']?></p>
				<ul>	
					<li class="clear">
						<label><?=$lang_text['product_returned']?>: *</label>
						<fieldset class="size_304"><input type="text" dname="<?=$lang_text['product_returned']?>" value="<?=$rma_obj['product_returned']?>" name="product_returned" size="40" <?=$disabled?>/></fieldset>
					</li>
					<li class="clear select">
						<label><?=$lang_text['categories']?>: *</label>
						<select name="category" dname="<?=$lang_text['categories']?>" class="<?=$select_box_style?>" id="return_categories" <?=$disabled?>>
							<?php
								foreach ($category as $node)
								{
							?>
									<option value="<?=$node['key']?>" <?=$node['selected']?>><?=$node['value']?></option>
							<?php
								}
							?>
						</select>
					</li>
					<li class="clear">
						<label><?=$lang_text['serial_number']?>: </label>
						<fieldset class="size_304"><input type="text" value="<?=$rma_obj['serial_no']?>" dname="<?=$lang_text['serial_number']?>" name="serial_no" size="40" <?=$disabled?>/></fieldset>
					</li>
				</ul>
				
				<div class="separator"></div>
				
				<p class="upper clear"><?=$lang_text['returns_details']?></p>
				<ul>
					<li class="clear_left select">
						<label><?=$lang_text['return_reasons']?> *</label>
						<select name="reason" dname="<?=$lang_text['return_reasons']?>" class="<?=$select_box_style?>" id="return_reason" <?=$disabled?>>
							<?php
								foreach ($reason as $node)
								{
							?>
									<option value="<?=$node['key']?>" <?=$node['selected']?>><?=$node['value']?></option>
							<?php
								}
							?>
						</select>
					</li>	
					<li class="clear no_right_margin select">	
						<label><?=$lang_text['action_required']?> *</label>
						<select name="action_request" dname="<?=$lang_text['action_required']?>" class="<?=$select_box_style?>" id="return_action" <?=$disabled?>>
							<?php
								foreach ($action_request as $node)
								{
							?>
									<option value="<?=$node['key']?>" <?=$node['selected']?>><?=$node['value']?></option>
							<?php
								}
							?>
						</select>
					</li>
					<li class="clear no_right_margin">
						<label><?=$lang_text['desc_of_fault']?> *</label>
						<fieldset class="textarea"><textarea name="details" dname="<?=$lang_text['desc_of_fault']?>" rows="6" cols="30" <?=$disabled?>><?=$rma_obj['details']?></textarea></fieldset>
					</li>
				</ul>

				<div class="separator"></div>

				<div class="text clear">
					<p>
						<?=$lang_text['return_policy_title']?><br />
						<br />
						<ol class="rma">
							<li><?=$lang_text['return_policy_1']?></li>
							<li><?=$lang_text['return_policy_2']?></li>
							<li><?=$lang_text['return_policy_3']?>
								<ol >
									<li style="list-style-type:lower-roman"><?=$lang_text['return_policy_3_1']?></li>
									<li style="list-style-type:lower-roman"><?=$lang_text['return_policy_3_2']?></li>
								</ol>
							</li>
							<li><?=$lang_text['return_policy_4']?></li>
							<li><?=$lang_text['return_policy_5']?></li>
							<li><?=$lang_text['return_policy_6']?></li>
							<li><?=$lang_text['return_policy_7']?></li>
							<li><?=$lang_text['return_policy_8']?></li>
							<li><?=$lang_text['return_policy_9']?></li>
						</ol>
					</p>
				</div>
				<div class="text clear">
					<p>	
						<br class="clear" />
						<?=$lang_text['declaration']?><br />
						<br />
						
						<label class="checkbox">
							<input type="checkbox" name="agreed" value="1" dname="<?=$lang_text['declaration']?>" <?=$disabled?> <?=$agreed?> notEmpty/>
							<span><?=$lang_text['declare_agree']?></span>
						</label>
						<br />
					</p>
				</div>
				<input type="hidden" name="rma_id" value="<?=$rma_obj['id']?>">
				<input type="hidden" name="posted" value="1">
				<button type="submit"><?=$lang_text['submit']?></button>
			</form>
		</div>
		
		<div class="text silver_box items item3" <?=$profile['block']?>>
			<form name="fm_edit_profile" action="<?=$base_url?>myaccount/profile" method="post" class="form-holder" onSubmit="return CheckForm(document.fm_edit_profile);">
				<ins class="no_top_padding"><?=$lang_text['profile']?></ins>
				<ul>
					<li>
						<label><?=$lang_text['email_addr']?></label>
						<fieldset><input type="text" name="email" value="<?=$client_obj['email']?>" disabled/></fieldset>
					</li>
					<div class="clear"></div>
					<li>
						<label><?=$lang_text['old_password']?></label>
						<fieldset><input type="password" dname="<?=$lang_text['old_password']?>" name="old_password" size="40" /></fieldset>
					</li>
					<div class="clear"></div>
					<p style="color:#5b5b5b;font-size:11px;line-height: 14px; padding-left: 14px; padding-right: 20px"><?=$lang_text['password_notice']?></p></br>
					<li>
						<label><?=$lang_text['choose_new_password']?> <?=$lang_text['optional']?></label>
						<fieldset><input type="password" name="password" dname="<?=$lang_text['new_password']?>" size="40" minLen="6" maxLen="20"/></fieldset>
					</li>
					<div class="clear"></div>
					<li>
						<label><?=$lang_text['reenter_password']?> <?=$lang_text['optional']?></label>
						<fieldset><input type="password" name="confirm_password" dname="<?=$lang_text['reenter_password']?>" size="40" match="password" onpaste="return false;"/></fieldset>
					</li>
					<li class="clear width_800">
						<label><?=$lang_text['title']?> *</label>
						<select name="name_prefix" class="<?=$select_box_style?>" id="profile_title">
							<?php
								foreach ($title as $node)
								{
							?>
									<option value="<?=$node['value']?>" <?=$node['selected']?>><?=$node['value']?></option>
							<?php
								}
							?>
						</select>
					</li>
					<li class="clear">
						<label><?=$lang_text['first_name']?> *</label>
						<fieldset><input type="text" name="forename" dname="<?=$lang_text['first_name']?>" size="10" value="<?=$client_obj['forename']?>" notEmpty/></fieldset>
					</li>
					<li class="clear no_right_margin">
						<label><?=$lang_text['surname']?> *</label>
						<fieldset><input type="text" name="surname" dname="<?=$lang_text['surname']?>" size="10" value="<?=$client_obj['surname']?>" notEmpty/></fieldset>
					</li>	
					<li class="clear">
						<label><?=$lang_text['company_name']?></label>
						<fieldset><input type="text" name="companyname" dname="<?=$lang_text['company_name']?>" value="<?=$client_obj['companyname']?>" size="40" /></fieldset>
					</li>
				</ul>
				
				<ins><?=$lang_text['billing_addr']?></ins>
				<ul>
					<li class="clear select">
						<label><?=$lang_text['country']?> *</label>
						<select id="profile_country_id" name="country_id" class="<?=$select_box_style?>" id="profile_billing_country" onchange="update_state_attribute('profile', this.value);update_postcode_attribute('profile', this.value);">
							<?php
								foreach ($bill_country_arr as $bill_country)
								{
							?>
									<option value="<?=$bill_country['id']?>" <?=$bill_country['selected']?> ><?=$bill_country['display_name']?></option>
							<?php
								}
							?>
						</select>
					</li>
					<li class="clear no_right_margin select">	
						<label><?=$lang_text['city']?> *</label>
						<fieldset><input type="text" name="city" dname="<?=$lang_text['city']?>" id="profile_billing_city" size="40" value="<?=$client_obj['city']?>" notEmpty isLatin/></fieldset>
					</li>
					<li class="clear width_800">
						<label><?=$lang_text['state']?> <span id="profile_asterisk">*</span></label>
						<fieldset><input type="text" name="state" dname="<?=$lang_text['state']?>" id="profile_state" size="40" value="<?=$client_obj['state']?>" notEmpty isLatin/></fieldset>
					</li>
					<li class="clear width_800">
						<label><?=$lang_text['address']?> *</label>
						<fieldset class="large"><input type="text" name="address_1" dname="<?=$lang_text['addr_line_1']?>" size="40" value="<?=$client_obj['address_1']?>" notEmpty isLatin/></fieldset>
						<fieldset class="large"><input type="text" name="address_2" dname="<?=$lang_text['addr_line_2']?>" size="40" value="<?=$client_obj['address_2']?>" isLatin/></fieldset>
					</li>
					<li class="clear">
						<label><?=$lang_text['postal_code']?> <span id="profile_postcode_asterisk">*</span></label>
						<fieldset><input type="text" name="postcode" id="profile_postcode" size="40" value="<?=$client_obj['postcode']?>" validPostal="profile_country_id" dname="<?=$lang_text['postal_code']?>" isLatin notEmpty/></fieldset>
					</li>
				</ul>

				<ins><?=$lang_text['phone']?></ins>
				<ul>
					<li class="clear">
						<label><?=$lang_text['country_code']?> *</label>
						<fieldset class="very_small"><input type="text" name="tel_1" dname="<?=$lang_text['country_code']?>" size="16" value="<?=$client_obj['tel_1']?>" notEmpty /></fieldset>
					</li>
					<li class="clear">
						<label><?=$lang_text['area_code']?> *</label>
						<fieldset class="very_small"><input type="text" name="tel_2" dname="<?=$lang_text['area_code']?>" size="16" value="<?=$client_obj['tel_2']?>" notEmpty /></fieldset>
					</li>
					<li class="clear">
						<label><?=$lang_text['number']?> *</label>
						<fieldset class="medium"><input type="text" name="tel_3" dname="<?=$lang_text['number']?>" size="16" value="<?=$client_obj['tel_3']?>" notEmpty /></fieldset>
					</li>
					<!--
					<li class="clear">
						<input type="checkbox" class="checkbox" value="1" [client_obj.subscriber;noerr;] name="subscriber" /><var><?=$lang_text['subscribe']?></var>
					</li>
					-->
					<li class="clear">
						<button type="submit" class="border-radius-2"><?=$lang_text['submit']?></button>
					</li>
				</ul>
				<input type="hidden" name="posted" value="1">
			</form>
		</div>
	</div>
</div>

<script language="javascript">
	function CheckSubmit(f)
	{
		var ret = true;

		if (!f.agreed.checked)
		{
			alert("<?=$lang_text['alert_terms']?>");
			f.agreed.focus();
			ret = false;
		}
		if( ret && trim(f.so_no.value) == '')
		{
			alert("<?=$lang_text['alert_order_number']?>");
			f.so_no.focus();
			ret = false;
		}
		if(ret && trim(f.product_returned.value) == '')
		{
			alert("<?=$lang_text['alert_product_name']?>");
			f.product_returned.focus();
			ret = false;
		}
		if(ret && trim(f.details.value) == '')
		{
			alert("<?=$lang_text['alert_description']?>");
			f.details.focus();
			ret = false;
		}

		return ret;
	}
</script>

<script>
	jQuery.noConflict();

	// complusary state for US
	if(document.getElementById("rma_country_id").value != 'US')
	{
		jQuery("#rma_state").removeAttr("notEmpty");
		jQuery("#rma_asterisk").html("");
	}
	else
	{
		jQuery("#rma_state").attr("notEmpty", "");
		jQuery("#rma_asterisk").html("*");
	}
	if(document.getElementById("profile_country_id").value != 'US')
	{
		jQuery("#profile_state").removeAttr("notEmpty");
		jQuery("#profile_asterisk").html("");
	}
	else
	{
		jQuery("#profile_state").attr("notEmpty", "");
		jQuery("#profile_asterisk").html("*");
	}

	// non-complusary postcode for HK and Ireland
	if(document.getElementById("rma_country_id").value == 'HK' || document.getElementById("rma_country_id").value == 'IE')
	{
		jQuery("#rma_postcode").removeAttr("notEmpty");
		jQuery("#rma_postcode_asterisk").html("");
	}
	else
	{
		jQuery("#rma_postcode").attr("notEmpty", "");
		jQuery("#rma_postcode_asterisk").html("*");
	}
	if(document.getElementById("profile_country_id").value == 'HK' || document.getElementById("profile_country_id").value == 'IE')
	{
		jQuery("#profile_postcode").removeAttr("notEmpty");
		jQuery("#profile_postcode_asterisk").html("");
	}
	else
	{
		jQuery("#profile_postcode").attr("notEmpty", "");
		jQuery("#profile_postcode_asterisk").html("*");
	}

	function update_state_attribute(f, country_id)
	{
		// complusary state for US
		if(country_id != 'US')
		{
			if(f == 'rma')
			{
				jQuery("#rma_state").removeAttr("notEmpty");
				jQuery("#rma_asterisk").html("");
			}
			else
			{
				jQuery("#profile_state").removeAttr("notEmpty");
				jQuery("#profile_asterisk").html("");
			}
		}
		else
		{
			if(f == 'rma')
			{
				jQuery("#rma_state").attr("notEmpty", "");
				jQuery("#rma_asterisk").html("*");
			}
			else
			{
				jQuery("#profile_state").attr("notEmpty", "");
				jQuery("#profile_asterisk").html("*");
			}
		}
	}

	function update_postcode_attribute(f, country_id)
	{

		// non-complusary postcode for HK and Ireland
		if(country_id == 'HK' || country_id == 'IE')
		{
			if(f == 'rma')
			{
				jQuery("#rma_postcode").removeAttr("notEmpty");
				jQuery("#rma_postcode_asterisk").html("");
			}
			else
			{
				jQuery("#profile_postcode").removeAttr("notEmpty");
				jQuery("#profile_postcode_asterisk").html("");
			}
		}
		else
		{
			if(f == 'rma')
			{
				jQuery("#rma_postcode").attr("notEmpty", "");
				jQuery("#rma_postcode_asterisk").html("*");
			}
			else
			{
				jQuery("#profile_postcode").attr("notEmpty", "");
				jQuery("#profile_postcode_asterisk").html("*");
			}
		}
	}
</script>