<?php $this->load->view('/default/header') ?>
<div id="content">
	<h5 class="side_title"><?= _("My Account") ?></h5>
	<div id="my_acount" class="product-tabs-info">
		<ul class="tabs">
			<li><a href="javascript:;" title=""><?= _("Order History") ?></a></li>
			<!-- <li style="display:none"><a href="javascript:;" title=""><?= _("Returns Request") ?></a></li> -->
			<li class="active"><a href="javascript:;" title=""><?= _("Edit Your Profile") ?></a></li>
		</ul>

		<div class="text silver_box items item1" [order.block;noerr;htmlconv=no;]>
				<?= _('Your order has been split at no extra cost to ensure all item(s) purchased are received at the soonest available opportunity.') ?>
			<br><br>
			<table class="acount-orders" border="0">
				<col width="120"><col width="70"><col width="100"><col><col width="70"><col width="250">
				<tr>
					<th><?= _("Order") ?></th>
					<th><?= _("Date") ?></th>
					<th><?= _("Shipped to") ?></th>
					<th><?= _("Product") ?></th>
					<th><?= _("Order total") ?></th>
					<th><?= _("Status") ?></th>
				</tr>
				<?
				if ($orderlist) {
					foreach ($orderlist as $order_obj) {
				?>
				<tr>
					<td><?=$order_obj->get_client_id();?> - <?=$orderlist->get_join_split_so_no();?><?=$orderlist->get_print_invoice_html();?></td>
					<td><?=$orderlist->get_order_date();?></td>
					<td><?=$orderlist->get_delivery_name();?></td>
					<td><?=$orderlist->get_product_name();?></td>
					<td><?=$orderlist->get_total_amount();?></td>
					<td><b><?=$orderlist->get_order_status();?></b><br /><?=$orderlist->get_status_desc();?></td>
				</tr>
				<?
					}
				}
				?>
			</table>
			<br><br>
				<? if ($show_bank_transfer_contact) { ?>
					<p><?= _('Have you paid by bank transfer?<br>Contact us ') ?>
						<a href="<?=base_url().'contact/show_enquiry/sales';?>"><?= _('here') ?></a>.
					</p>
				<? } ?>
				<? if ($unpaid_orderlist) { ?>

				<? } ?>
		</div>

		<div class="silver_box items order_form item2" id="returns_request" style="display:none">
			<form name="fm_rma" action="<?=base_url()?>myaccount/rma" method="post" class="form-holder" onSubmit="return (CheckForm(document.fm_rma) && CheckSubmit(this));">
				<p>[confirm_notice;noerr;htmlconv=no;]</p>
				<p class="red clear" style="font-size:14px"><?= _("Important Notice") ?></p><br />
				<p class="clear">
					<b><?= _("Please read the following before filling in the web form below.") ?></b>
				</p>
				<p style="margin-left:1em"><?= _("- Any items returned without a returns form enclosed will not be entertained") ?></p>
				<p style="margin-left:1em"><?= _("- Read our returns policy that can be found at the bottom of this page") ?></p>
				<p style="margin-left:1em"><?= _("- Kindly use our webform for &quot;Faulty goods or returned items&quot; to confirm your intention to return your goods and we will then advise you with our returns address") ?></p>
				<div class="separator"></div>
				<p class="upper clear"><?= _("Personal Details") ?></p>
				<ul>
					<li class="clear">
						<label><?= _("First Name") ?>: *</label>
						<fieldset class="size_304"><input type="text" value="<?=$rma_obj->get_forename();?>" name="forename" dname="<?= _('First Name') ?>" size="40"  notEmpty/></fieldset>
					</li>
					<li>
						<label><?= _("Surname") ?>: *</label>
						<fieldset class="size_304"><input type="text" value="<?=$rma_obj->get_surname();?>" name="surname" dname="<?= _('Surname') ?>" size="40"  notEmpty/></fieldset>
					</li>
					<li class="clear select">
						<label><?= _("Country") ?>: *</label>
						<select id="rma_country_id" name="country_id" dname="<?= _('Country') ?>" class="[select_box_style]"  notEmpty onchange="update_state_attribute('rma', this.value);update_postcode_attribute('rma', this.value);">
							<option value=""></option>
							[bill_country_arr2;block=begin;noerr;]
							<option value="[bill_country_arr2.id]" [bill_country_arr2.selected]>[bill_country_arr2.display_name]</option>
							[bill_country_arr2;block=end;noerr;]
						</select>
					</li>
					<li class="clear">
						<label><?= _("City") ?>: *</label>
						<fieldset class="size_304"><input type="text" value="<?=$rma_obj->get_city();?>" name="city" dname="<?= _('City') ?>" size="40"  notEmpty isLatin/></fieldset>
					</li>
					<li class="clear">
						<label><?= _("State") ?>: <span id="rma_asterisk">*</span></label>
						<fieldset class="size_304"><input type="text" value="<?=$rma_obj->get_state();?>" name="state" dname="<?= _('State') ?>" id="rma_state" size="40"  notEmpty isLatin/></fieldset>
					</li>
					<li class="clear">
						<label><?= _("Address Line 1") ?>: *</label>
						<fieldset class="size_304"><input type="text" value="<?=$rma_obj->get_address_1();?>" name="address_1" dname="<?= _('Address Line 1') ?>" size="40"  notEmpty isLatin/></fieldset>
					</li>
					<li>
						<label><?= _("Address Line 2") ?>: </label>
						<fieldset class="size_304"><input type="text" value="<?=$rma_obj->get_address_2();?>" name="address_2" dname="<?= _('Address Line 2') ?>" size="40"  isLatin/></fieldset>
					</li>
					<li class="clear">
						<label><?= _("Postcode") ?>: <span id="rma_postcode_asterisk">*</span></label>
						<fieldset class="size_304"><input type="text" value="<?=$rma_obj->get_postcode();?>" name="postcode" dname="<?= _('Postcode') ?>" id="rma_postcode" size="40" validPostal="country_id"  isLatin notEmpty/></fieldset>
					</li>
				</ul>

				<div class="separator"></div>

				<p class="upper clear"><?= _("Order Details") ?> *</p>
				<ul>
					<li>
						<label><?= _("Order number") ?></label>
						<fieldset class="size_304"><input type="text" value="<?=$rma_obj->get_so_no();?>" dname="<?= _('Order number') ?>" name="so_no" size="40" /></fieldset>
					</li>
				</ul>

				<div class="separator"></div>

				<p class="upper clear"><?= _("Product Details") ?></p>
				<ul>
					<li class="clear">
						<label><?= _("Product Returned") ?>: *</label>
						<fieldset class="size_304"><input type="text" dname="<?= _('Product Returned') ?>" value="<?=$rma_obj->get_product_returned();?>" name="product_returned" size="40" /></fieldset>
					</li>
					<li class="select">
						<label><?= _("Categories") ?>: *</label>
						<select name="category" dname="<?= _('Categories') ?>" class="[select_box_style]" id="return_categories" >
							[category;block=begin;noerr;]
							<option value="[category.key;noerr;htmlconv=no;]" [category.selected;noerr;]>[category.value;noerr;htmlconv=no;]</option>
							[category;block=end;noerr;]
						</select>
					</li>
					<li class="clear">
						<label><?= _('Serial No') ?>: </label>
						<fieldset class="size_304"><input type="text" value="<?=$rma_obj->get_serial_no();?>" dname="[lang_text.serial_number;noerr;htmlconv=no;]" name="serial_no" size="40" [disabled;noerr;]/></fieldset>
					</li>
				</ul>

				<div class="separator"></div>

				<p class="upper clear"><?= _('Returns Details') ?></p>
				<ul>
					<li class="clear_left select">
						<label><?= _("Reasons for Returns") ?> *</label>
						<select name="reason" dname="<?= _('Reasons for Returns') ?>" class="[select_box_style]" id="return_reason" [disabled;noerr;]>
							[reason;block=begin;noerr;]
							<option value="[reason.key;noerr;htmlconv=no;]" [reason.selected;noerr;]>[reason.value;noerr;htmlconv=no;]</option>
							[reason;block=end;noerr;]
						</select>
					</li>
					<li class="no_right_margin select">
						<label><?= _("Action Required") ?> *</label>
						<select name="action_request" dname="<?= _('Action Required') ?>" class="[select_box_style]" id="return_action" [disabled;noerr;]>
							[action_request;block=begin;noerr;]
							<option value="[action_request.key;noerr;htmlconv=no;]" [action_request.selected;noerr;]>[action_request.value;noerr;htmlconv=no;]</option>
							[action_request;block=end;noerr;]
						</select>
					</li>
					<li class="no_right_margin">
						<label><?= _('Detailed Description of Fault') ?> *</label>
						<fieldset class="textarea"><textarea name="details" dname="<?= _('Detailed Description of Fault') ?>" rows="6" cols="70" [disabled;noerr;]><?=$rma_obj->get_details();?></textarea></fieldset>
					</li>
				</ul>

				<div class="separator"></div>

				<div class="text clear">
					<p>
						<?= _('ValueBasket Returns Policy') ?><br />
						<br />
						<ol class="rma">
							<li><?= _("Where the dispatch has been made, you may request for a refund up to 14 days after the goods are delivered.") ?></li>
							<li><?= _("To submit a returns request, simply email us to start the process.  Where you are eligible for a refund, Valuebasket.com will provide authorization and a returns address if appropriate.") ?></li>
							<li><?= _("Returning an order within the 14-day grace period from delivery date;") ?>
								<ol >
									<li style="list-style-type:lower-roman"><?= _("that are new unopened item, will be eligible for refund") ?></li>
									<li style="list-style-type:lower-roman"><?= _("that are found to be faulty will be eligible for replacement order or refund") ?></li>
								</ol>
							</li>
							<li><?= _("Good returned for a refund must be in their original condition with all original packing, accessories and included materials.") ?></li>
							<li><?= _("You must take all due reasonable care of the goods and return them in its original and undamaged condition.") ?></li>
							<li><?= _("You will agree to bear the costs of returning the item(s).  The cost incurred in returning the item(s) may be eligible for reimbursement where the item(s) are found to be defective and if proper authorization from ValueBasket.com is sought and provided prior to the return of the item(s).") ?></li>
							<li><?= _("Where the purchases are opened and are of usable software, consumable goods, or items impacted by hygiene, such items will be ineligible for refund.") ?></li>
							<li><?= _("A restocking fee may be applicable at the discretion of ValueBasket.com") ?></li>
							<li><?= _("An admin fee may be incurred if no fault found or fault caused by user") ?></li>
						</ol>
					</p>
				</div>
				<div class="text clear">
					<p>
						<br class="clear" />
						<?= _("Declaration") ?><br />
						<br />

						<label class="checkbox">
							<input type="checkbox" name="agreed" value="1" dname="<?= _('Declaration') ?>" [disabled;noerr;] [agreed;noerr;] notEmpty/>
							<span><?= _("I declare that I have read and agree to the Returns Policy") ?></span>
						</label>
						<br />
					</p>
				</div>
				<input type="hidden" name="rma_id" value="<?=$rma_obj->get_id();?>">
				<input type="hidden" name="posted" value="1">
				<button type="submit"><?= _('Submit') ?></button>
			</form>
		</div>

		<div class="text silver_box items item3" [profile.block;noerr;htmlconv=no;]>
			<form name="fm_edit_profile" action="[base_url;noerr;]myaccount/profile" method="post" class="form-holder" onSubmit="return CheckForm(document.fm_edit_profile);">
				<ins class="no_top_padding"><?= _('Profile') ?></ins>
				<ul>
					<li>
						<label><?= _("Email Address") ?></label>
						<fieldset><input type="text" name="email" value="<?=$client_obj->get_email();?>" disabled/></fieldset>
					</li>
					<div class="clear"></div>
					<li>
						<label><?= _("Old Password") ?></label>
						<fieldset><input type="password" dname="<?= _('Old Password') ?>" name="old_password" size="40" /></fieldset>
					</li>
					<div class="clear"></div>
					<p style="color:#5b5b5b;font-size:11px;line-height: 14px; padding-left: 14px; padding-right: 20px"><?= _('Password must be between 6 to 20 characters in length. We recommend that you make your password secure by including a mixture of upper and lower case characters, numbers and symbols (e.g. #, @, !)') ?></p></br>
					</li>
					<li>
						<label><?= _("Choose a New Password") ?> <?= _("(Optional)") ?></label>
						<fieldset><input type="password" name="password" dname="<?= _('Choose a New Password') ?>" size="40" minLen="6" maxLen="20"/></fieldset>
					</li>
					<li>
						<label><?= _("Re-enter New Password") ?> <?= _("(Optional)") ?>]</label>
						<fieldset><input type="password" name="confirm_password" dname="<?= _("Re-enter New Password") ?>" size="40" match="password" onpaste="return false;"/></fieldset>
					</li>
					<li class="clear width_800">
						<label><?= _('Title') ?> *</label>
						<select name="name_prefix" class="[select_box_style]" id="profile_title">
							[title;block=begin;noerr;]
							<option value="[title.value;noerr;]" [title.selected;noerr;]>[title.value;noerr;]</option>
							[title;block=end;noerr;]
						</select>
					</li>
					<li class="clear">
						<label><?= _('First Name') ?> *</label>
						<fieldset><input type="text" name="forename" dname="<?= _('First Name') ?>" size="10" value="<?=$client_obj->get_forename();?>" notEmpty/></fieldset>
					</li>
					<li class="no_right_margin">
						<label><?= _("Surname") ?> *</label>
						<fieldset><input type="text" name="surname" dname="<?= _('Surname') ?>" size="10" value="<?=$client_obj->get_surname();?>" notEmpty/></fieldset>
					</li>
					<li class="clear">
						<label><?= _('Company Name') ?></label>
						<fieldset><input type="text" name="companyname" dname="<?= _('Company Name') ?>" value="<?=$client_obj->get_companyname();?>" size="40" /></fieldset>
					</li>
				</ul>

				<ins><?= _("Billing Address") ?></ins>

				<ul>
					<li class="select">
						<label><?= _('Country') ?> *</label>
						<select id="profile_country_id" name="country_id" class="[select_box_style]" id="profile_billing_country" onchange="update_state_attribute('profile', this.value);update_postcode_attribute('profile', this.value);">
							[bill_country_arr;block=begin;noerr;]
							<option value="[bill_country_arr.id]" [bill_country_arr.selected] >[bill_country_arr.display_name]</option>
							[bill_country_arr;block=end;noerr;]
						</select>
					</li>
					<li class="no_right_margin select">
						<label><?= _('City') ?> *</label>
						<fieldset><input type="text" name="city" dname="<?= _('City') ?>" id="profile_billing_city" size="40" value="<?=$client_obj->get_city();?>" notEmpty isLatin/></fieldset>
					</li>
					<li class="clear width_800">
						<label><?= _('State') ?> <span id="profile_asterisk">*</span></label>
						<fieldset><input type="text" name="state" dname="<?= _('State') ?>" id="profile_state" size="40" value="<?=$client_obj->get_state();?>" notEmpty isLatin/></fieldset>
					</li>
					<li class="clear width_800">
						<label><?= _('Address') ?> *</label>
						<fieldset class="large"><input type="text" name="address_1" dname="<?= _('Address Line 1') ?>" size="40" value="<?=$client_obj->get_address_1();?>" notEmpty isLatin/></fieldset>
						<fieldset class="large"><input type="text" name="address_2" dname="<?= _('Address Line 2') ?>" size="40" value="<?=$client_obj->get_address_2();?>" isLatin/></fieldset>
					</li>
					<li class="clear">
						<label><?= _('Postcode') ?> <span id="profile_postcode_asterisk">*</span></label>
						<fieldset><input type="text" name="postcode" id="profile_postcode" size="40" value="<?=$client_obj->get_postcode();?>" validPostal="profile_country_id" dname="<?= _('Postcode') ?>" isLatin notEmpty/></fieldset>
					</li>
				</ul>
					<ins><?= _('Phone') ?></ins>
				<ul>
					<li class="clear">
						<label><?= _('Country Code') ?> *</label>
						<fieldset class="very_small"><input type="text" name="tel_1" dname="<?= _('Country Code') ?>" size="16" value="<?=$client_obj->get_tel_1();?>" notEmpty /></fieldset>
					</li>
					<li>
						<label><?= _('Area Code') ?> *</label>
						<fieldset class="very_small"><input type="text" name="tel_2" dname="<?= _('Area Code') ?>" size="16" value="<?=$client_obj->get_tel_2();?>" notEmpty /></fieldset>
					</li>
					<li>
						<label><?= _('Number') ?> *</label>
						<fieldset class="medium"><input type="text" name="tel_3" dname="<?= _('Number') ?>" size="16" value="<?=$client_obj->get_tel_3();?>" notEmpty /></fieldset>
					</li>
					<li class="clear">
						<button type="submit" class="border-radius-2"><?= _('Submit') ?></button>
					</li>
				</ul>
				<input type="hidden" name="posted" value="1">
			</form>
		</div>
		</div>
	</div>
<script language="javascript">
jQuery.noConflict();
//<!--
document.getElementById('returns_request').innerText =' ';
function CheckSubmit(f)
{
	var ret = true;
	if (!f.agreed.checked) {
		alert("[lang_text.alert_terms;noerr;htmlconv=no;]");
		f.agreed.focus();
		ret = false;
	}
	if( ret && trim(f.so_no.value) == '') {
		alert("[lang_text.alert_order_number;noerr;htmlconv=no;]");
		f.so_no.focus();
		ret = false;
	}
	if(ret && trim(f.product_returned.value) == '') {
		alert("[lang_text.alert_product_name;noerr;htmlconv=no;]");
		f.product_returned.focus();
		ret = false;
	}
	if(ret && trim(f.details.value) == '') {
		alert("[lang_text.alert_description;noerr;htmlconv=no;]");
		f.details.focus();
		ret = false;
	}
	return ret;
}
//-->

// complusary state for US
if(document.getElementById("rma_country_id").value != 'US') {
	jQuery("#rma_state").removeAttr("notEmpty");
	jQuery("#rma_asterisk").html("");
} else {
	jQuery("#rma_state").attr("notEmpty", "");
	jQuery("#rma_asterisk").html("*");
}
if(document.getElementById("profile_country_id").value != 'US') {
	jQuery("#profile_state").removeAttr("notEmpty");
	jQuery("#profile_asterisk").html("");
} else {
	jQuery("#profile_state").attr("notEmpty", "");
	jQuery("#profile_asterisk").html("*");
}

// non-complusary postcode for HK and Ireland
if(document.getElementById("rma_country_id").value == 'HK' || document.getElementById("rma_country_id").value == 'IE') {
	jQuery("#rma_postcode").removeAttr("notEmpty");
	jQuery("#rma_postcode_asterisk").html("");
} else {
	jQuery("#rma_postcode").attr("notEmpty", "");
	jQuery("#rma_postcode_asterisk").html("*");
}
if(document.getElementById("profile_country_id").value == 'HK' || document.getElementById("profile_country_id").value == 'IE') {
	jQuery("#profile_postcode").removeAttr("notEmpty");
	jQuery("#profile_postcode_asterisk").html("");
} else {
	jQuery("#profile_postcode").attr("notEmpty", "");
	jQuery("#profile_postcode_asterisk").html("*");
}

function update_state_attribute(f, country_id)
{
	// complusary state for US
	if(country_id != 'US') {
		if(f == 'rma') {
			jQuery("#rma_state").removeAttr("notEmpty");
			jQuery("#rma_asterisk").html("");
		} else {
			jQuery("#profile_state").removeAttr("notEmpty");
			jQuery("#profile_asterisk").html("");
		}
	} else {
		if(f == 'rma') {
			jQuery("#rma_state").attr("notEmpty", "");
			jQuery("#rma_asterisk").html("*");
		} else {
			jQuery("#profile_state").attr("notEmpty", "");
			jQuery("#profile_asterisk").html("*");
		}
	}
}

function update_postcode_attribute(f, country_id)
{
	// non-complusary postcode for HK and Ireland
	if(country_id == 'HK' || country_id == 'IE') {
		if(f == 'rma') {
			jQuery("#rma_postcode").removeAttr("notEmpty");
			jQuery("#rma_postcode_asterisk").html("");
		} else {
			jQuery("#profile_postcode").removeAttr("notEmpty");
			jQuery("#profile_postcode_asterisk").html("");
		}
	} else {
		if(f == 'rma') {
			jQuery("#rma_postcode").attr("notEmpty", "");
			jQuery("#rma_postcode_asterisk").html("*");
		} else {
			jQuery("#profile_postcode").attr("notEmpty", "");
			jQuery("#profile_postcode_asterisk").html("*");
		}
	}
}
</script>
<?php $this->load->view('/default/footer') ?>
