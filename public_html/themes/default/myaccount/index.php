<?php $this->load->view('header') ?>
<div id="content">
	<div id="my_acount" class="product-tabs-info">
		<h5 class="side_title"><?= _("My Account") ?></h5>
		<?php
			switch ($page) {
				case 'profile':
					$profile["active"] = 'class="active"';
	 				$profile["block"] = 'style="display:block;"';
					break;
				case 'rma':
					$rma["active"] = 'class="active"';
					$rma["block"] = 'style="display:block;"';
					break;
				default:
					$order["active"] = 'class="active"';
					$order["block"] = 'style="display:block;"';
					break;
			}
		?>

		<ul class="tabs">
			<li <?=$order['active'];?>><a href="javascript:;" title=""><?= _("Order History") ?></a></li>
			<li <?=$rma['active']?>><a href="javascript:;" title=""><?= _("Returns Request") ?></a></li>
			<li <?=$profile['active']?>><a href="javascript:;" title=""><?= _("Edit Your Profile") ?></a></li>
		</ul>

		<div class="text silver_box items item1" <?=$order['block']?>>
				<? if($show_partial_ship_text) { ?>
					<?= _('Your order has been split at no extra cost to ensure all item(s) purchased are received at the soonest available opportunity.') ?>
					<br><br>
				<? } ?>
			<table class="acount-orders" border="0">
				<colgroup>
					<col width="110">
					<col width="80">
					<col width="110">
					<col width="450">
					<col width="110">
					<col width="240">
				</colgroup>
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
					foreach ($orderlist as $order_arr) {
				?>
				<tr>
					<td><?=$order_arr['client_id']?>-<?=$order_arr['join_split_so_no']?><?=$order_arr['print_invoice_html']?></td>
					<td><?=$order_arr['order_date']?></td>
					<td><?=$order_arr['delivery_name']?></td>
					<td><?=$order_arr['product_name']?></td>
					<td><?=$order_arr['total_amount']?></td>
					<td><b><?=$order_arr['order_status']?></b><br /><?=$order_arr['status_desc']?></td>
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
						<table class="acount-orders" border="0">
							<tr>
								<th><?= _("Order") ?></th>
								<th><?= _("Date") ?></th>
								<th><?= _("Shipped to") ?></th>
								<th><?= _("Product") ?></th>
								<th><?= _("Order total") ?></th>
								<th><?= _("Status") ?></th>
							</tr>
						<?
							foreach ($unpaid_orderlist as $so_no => $unpaid_obj) {
							$uniqid_status = $unpaid_obj->getUnpaidStatus();
						?>
							<tr>
								<td><?=$unpaid_obj->getClientId()?> - $so_no</td>
								<td><?=$unpaid_obj->getOrderDate();?></td>
								<td><?=$unpaid_obj->getDeliveryName();?></td>
								<td><?=$unpaid_obj->getProductName()?></td>
								<td><?=$unpaid_obj->getTotalAmount();?></td>
								<td><?=$show_unpaid_status["$uniqid_status"]?></td>
							</tr>
						<? } ?>
				<? } ?>
		</div>

		<div class="silver_box items order_form item2" id="returns_request" <?=$rma['block']?>>
			<form name="fm_rma" action="<?=base_url()?>myaccount/rma" method="post" class="form-holder" onSubmit="return (CheckForm(document.fm_rma) && CheckSubmit(this));">
				<? if ($rma_confirm) { ?>
					<p>
							<p class="green">
								<?= _("Your request has been submitted. Please kindly review the information below as your RMA confirmation.") ?><br />
								<?= _("Please click") ?>
								<a href="<?=base_url()?>myaccount/rma_print/<?=$rma_obj->getId();?>" target='rma_print' style='text-decoration:underline;'><?= _("here"); ?></a>
								<?= _("to print this page and send it back to us together with the returned package. Items returned without this RMA form will not be processed.") ?>
							</p>
					</p>
				<? } ?>
				<p class="red clear" style="font-size:14px"><?= _("Important Notice") ?></p>
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
						<fieldset class="size_304"><input type="text" value="<?=$rma_obj->getForename();?>" name="forename" dname="<?= _('First Name') ?>" size="40"  notEmpty/></fieldset>
					</li>
					<li>
						<label><?= _("Surname") ?>: *</label>
						<fieldset class="size_304"><input type="text" value="<?=$rma_obj->getSurname();?>" name="surname" dname="<?= _('Surname') ?>" size="40"  notEmpty/></fieldset>
					</li>
					<li class="clear select">
						<label><?= _("Country") ?>: *</label>
						<select id="rma_country_id" name="country_id" dname="<?= _('Country') ?>" class="[select_box_style]"  notEmpty onchange="update_state_attribute('rma', this.value);update_postcode_attribute('rma', this.value);">
							<option value=""></option>
							<? foreach ($bill_to_list as $bill_country) { ?>
							<option value="<?=$bill_country->getCountryId();?>" <? if($rma_obj->getCountryId() == $bill_country->getCountryId()) { echo 'SELECTED'; } ?>><?=$bill_country->getName();?></option>
							<? } ?>
						</select>
					</li>
					<li class="clear">
						<label><?= _("City") ?>: *</label>
						<fieldset class="size_304"><input type="text" value="<?=$rma_obj->getCity();?>" name="city" dname="<?= _('City') ?>" size="40"  notEmpty isLatin/></fieldset>
					</li>
					<li class="clear">
						<label><?= _("State") ?>: <span id="rma_asterisk">*</span></label>
						<fieldset class="size_304"><input type="text" value="<?=$rma_obj->getState();?>" name="state" dname="<?= _('State') ?>" id="rma_state" size="40"  notEmpty isLatin/></fieldset>
					</li>
					<li class="clear">
						<label><?= _("Address Line 1") ?>: *</label>
						<fieldset class="size_304"><input type="text" value="<?=$rma_obj->getAddress1();?>" name="address_1" dname="<?= _('Address Line 1') ?>" size="40"  notEmpty isLatin/></fieldset>
					</li>
					<li>
						<label><?= _("Address Line 2") ?>: </label>
						<fieldset class="size_304"><input type="text" value="<?=$rma_obj->getAddress2();?>" name="address_2" dname="<?= _('Address Line 2') ?>" size="40"  isLatin/></fieldset>
					</li>
					<li class="clear">
						<label><?= _("Postcode") ?>: <span id="rma_postcode_asterisk">*</span></label>
						<fieldset class="size_304"><input type="text" value="<?=$rma_obj->getPostcode();?>" name="postcode" dname="<?= _('Postcode') ?>" id="rma_postcode" size="40" validPostal="country_id"  isLatin notEmpty/></fieldset>
					</li>
				</ul>

				<div class="separator"></div>

				<p class="upper clear"><?= _("Order Details") ?> *</p>
				<ul>
					<li>
						<label><?= _("Order number") ?></label>
						<fieldset class="size_304"><input type="text" value="<?=$rma_obj->getSoNo();?>" dname="<?= _('Order number') ?>" name="so_no" size="40" /></fieldset>
					</li>
				</ul>

				<div class="separator"></div>

				<p class="upper clear"><?= _("Product Details") ?></p>
				<ul>
					<li class="clear">
						<label><?= _("Product Returned") ?>: *</label>
						<fieldset class="size_304"><input type="text" dname="<?= _('Product Returned') ?>" value="<?=$rma_obj->getProductReturned();?>" name="product_returned" size="40" /></fieldset>
					</li>
					<li class="select">
						<label><?= _("Categories") ?>: *</label>
						<select name="category" dname="<?= _('Categories') ?>" class="[select_box_style]" id="return_categories" >
							<?
								$category_arr = array(0=>"Machine Only", 1=>"Accessory Only", 2=>"Machine and Accessory");
								foreach($category_arr as $key => $value){
							?>
							<option value="<?=$key?>" <? if($rma_obj->getCategory() == $key){ echo 'SELECTED'; } ?>><?=$value?></option>
							<? } ?>
						</select>
					</li>
					<li class="clear">
						<label><?= _('Serial No') ?>: </label>
						<fieldset class="size_304"><input type="text" value="<?=$rma_obj->getSerialNo();?>" dname="[lang_text.serial_number;noerr;htmlconv=no;]" name="serial_no" size="40" [disabled;noerr;]/></fieldset>
					</li>
				</ul>

				<div class="separator"></div>

				<p class="upper clear"><?= _('Returns Details') ?></p>
				<ul>
					<li class="clear_left select">
						<label><?= _("Reasons for Returns") ?> *</label>
						<select name="reason" dname="<?= _('Reasons for Returns') ?>" class="[select_box_style]" id="return_reason" [disabled;noerr;]>
							<?
								$reason_arr = array(0=>"Needs Repair Under Warranty", 1=>"Wrong Product Delivered", 2=>"Wrong Product Purchased", 3=>"Accidently Purchased (conditions apply)");
								foreach ($reason_arr as $key => $value) {
							?>
							<option value="<?=$key?>" <? if($rma_obj->getReason() == $key) { echo "SELECTED";}?>><?=$value?></option>
							<?
								}
							?>
						</select>
					</li>
					<li class="no_right_margin select">
						<label><?= _("Action Required") ?> *</label>
						<select name="action_request" dname="<?= _('Action Required') ?>" class="sbSelector" id="return_action" [disabled;noerr;]>
							<?
								$action_request = array(0=>"Swap", 1=>"Refund", 2=>"Repair");
								foreach ($action_request as $key => $value) {
							?>
							<option value="<?=$key?>" <? if($rma_obj->getActionRequest() == $key) {echo "SELECTED";} ?>><?=$value?></option>
							<?
								}
							?>
						</select>
					</li>
					<li class="no_right_margin">
						<label><?= _('Detailed Description of Fault') ?> *</label>
						<fieldset class="textarea"><textarea name="details" dname="<?= _('Detailed Description of Fault') ?>" rows="6" cols="70" [disabled;noerr;]><?=$rma_obj->getDetails();?></textarea></fieldset>
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
				<input type="hidden" name="rma_id" value="<?=$rma_obj->getId();?>">
				<input type="hidden" name="posted" value="1">
				<button type="submit"><?= _('Submit') ?></button>
			</form>
		</div>

		<div class="text silver_box items item3" <?=$profile['block']?>>
			<form name="fm_edit_profile" action="<?=base_url()?>myaccount/profile" method="post" class="form-holder" onSubmit="return CheckForm(document.fm_edit_profile);">
				<ins class="no_top_padding"><?= _('Profile') ?></ins>
				<ul>
					<li>
						<label><?= _("Email Address") ?></label>
						<fieldset><input type="text" name="email" value="<?=$client_obj->getEmail();?>" disabled/></fieldset>
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
							 <?foreach ($title as $title_row) { ?>
                                <option value="<?=$title_row?>" <? if($client_obj->getTitle() == $title_row) { echo "SELECTED"; }?>><?=$title_row?></option>
                            <? } ?>
						</select>
					</li>
					<li class="clear">
						<label><?= _('First Name') ?> *</label>
						<fieldset><input type="text" name="forename" dname="<?= _('First Name') ?>" size="10" value="<?=$client_obj->getForename();?>" notEmpty/></fieldset>
					</li>
					<li class="no_right_margin">
						<label><?= _("Surname") ?> *</label>
						<fieldset><input type="text" name="surname" dname="<?= _('Surname') ?>" size="10" value="<?=$client_obj->getSurname();?>" notEmpty/></fieldset>
					</li>
					<li class="clear">
						<label><?= _('Company Name') ?></label>
						<fieldset><input type="text" name="companyname" dname="<?= _('Company Name') ?>" value="<?=$client_obj->getCompanyname();?>" size="40" /></fieldset>
					</li>
				</ul>

				<ins><?= _("Billing Address") ?></ins>

				<ul>
					<li class="select">
						<label><?= _('Country') ?> *</label>
						<select id="profile_country_id" name="country_id" class="[select_box_style]" id="profile_billing_country" onchange="update_state_attribute('profile', this.value);update_postcode_attribute('profile', this.value);">
							<? foreach ($bill_to_list as $bill_country) { ?>
								<option value="<?=$bill_country->getCountryId()?>" <? if($client_obj->getCountryId() == $bill_country->getCountryId()){ echo "SELECTED";} ?>><?=$bill_country->getName();?></option>
							<? } ?>
						</select>
					</li>
					<li class="no_right_margin select">
						<label><?= _('City') ?> *</label>
						<fieldset><input type="text" name="city" dname="<?= _('City') ?>" id="profile_billing_city" size="40" value="<?=$client_obj->getCity();?>" notEmpty isLatin/></fieldset>
					</li>
					<li class="clear width_800">
						<label><?= _('State') ?> <span id="profile_asterisk">*</span></label>
						<fieldset><input type="text" name="state" dname="<?= _('State') ?>" id="profile_state" size="40" value="<?=$client_obj->getState();?>" notEmpty isLatin/></fieldset>
					</li>
					<li class="clear width_800">
						<label><?= _('Address') ?> *</label>
						<fieldset class="large"><input type="text" name="address_1" dname="<?= _('Address Line 1') ?>" size="40" value="<?=$client_obj->getAddress1();?>" notEmpty isLatin/></fieldset><br />
						<fieldset class="large"><input type="text" name="address_2" dname="<?= _('Address Line 2') ?>" size="40" value="<?=$client_obj->getAddress2();?>" isLatin/></fieldset>
					</li>
					<li class="clear">
						<label><?= _('Postcode') ?> <span id="profile_postcode_asterisk">*</span></label>
						<fieldset><input type="text" name="postcode" id="profile_postcode" size="40" value="<?=$client_obj->getPostcode();?>" validPostal="profile_country_id" dname="<?= _('Postcode') ?>" isLatin notEmpty/></fieldset>
					</li>
				</ul>
					<ins><?= _('Phone') ?></ins>
				<ul>
					<li class="clear">
						<label><?= _('Country Code') ?> *</label>
						<fieldset class="very_small"><input type="text" name="tel_1" dname="<?= _('Country Code') ?>" size="16" value="<?=$client_obj->getTel1();?>" notEmpty /></fieldset>
					</li>
					<li>
						<label><?= _('Area Code') ?> *</label>
						<fieldset class="very_small"><input type="text" name="tel_2" dname="<?= _('Area Code') ?>" size="16" value="<?=$client_obj->getTel2();?>" notEmpty /></fieldset>
					</li>
					<li>
						<label><?= _('Number') ?> *</label>
						<fieldset class="medium"><input type="text" name="tel_3" dname="<?= _('Number') ?>" size="16" value="<?=$client_obj->getTel3();?>" notEmpty /></fieldset>
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

jQuery('.product-tabs-info > ul > li').click(function(){
	jQuery('.product-tabs-info > ul > li').removeClass('active');
	jQuery(this).addClass('active');
	var index =  jQuery('.product-tabs-info > ul > li').index(this)+1;
	jQuery('.product-tabs-info div.items').hide();
	jQuery('.product-tabs-info div.item'+index).show();
});

//<!--
// document.getElementById('returns_request').innerText =' ';
function CheckSubmit(f) {
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
<?php $this->load->view('footer') ?>
