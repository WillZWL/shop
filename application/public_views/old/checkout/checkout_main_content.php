<script type="text/javascript">
jQuery(function() {
	jQuery('input').focusout(function(){
		if(jQuery(this).val() == jQuery(this).attr('title')){
			jQuery(this).val('');
		}
	});
});
<?php
print "var countryMessages = Array(\"" . $data['data']['lang_text']['country_us_message'] . "\", \"" . $data['data']['lang_text']['country_gb_message'] . "\", \"" . $data['data']['lang_text']['country_other_message'] . "\");\n";
print "var countryChangesConfirmation = Array(\"" . $data['data']['lang_text']['country_changes_confirmation_1'] . "\", \"" . $data['data']['lang_text']['country_changes_confirmation_2'] . "\", \"" . $data['data']['lang_text']['country_changes_confirmation_3'] . "\");\n";
print "var globalStateText = Array(\"" . $data['data']['lang_text']['state'] . "\", \"" . $data['data']['lang_text']['state_province'] . "\", \"" . $data['data']['lang_text']['gb_county'] . "\");\n";
print "var globalPostalCode = \"" . $data['data']['lang_text']['postal_code'] . "\";\n";
print "var globalCountryRestrictionMessage = \"" . $data['data']['lang_text']['country_restriction_message'] . "\";\n";
print "var globalDeliverySurcharge = \"" . $data['data']['lang_text']['delivery_surcharge'] . "\";\n";
print "var globalDeliverySurchargeMessage = \"" . $data['data']['lang_text']['delivery_surcharge_message'] . "\";\n";
print "var globalBillingAddressTitle = \"" . $data['data']['lang_text']['billing_address'] . "\";\n";
print "var globalShippingAddressTitle = \"" . $data['data']['lang_text']['shipping_address'] . "\";\n";
print "var globalActionPath = \"" . (($controller_path == '/checkout_onepage/index') ? "/checkout_onepage" : $controller_path) . "\";\n";
print "var globalQueryString = \"" . ($_SERVER["QUERY_STRING"] ? "?" . $_SERVER["QUERY_STRING"] : "") . "\";\n";
print "var global_card_id='';"
?>
</script>
<div id="content">
	<div id="checkoutOnepageBox">
		<script type="text/javascript">
		//<![CDATA[
			<?php
				if (!is_null($data['state_list']) && !empty($data['state_list']))
				{
					echo "var arr_state_option = new Array();\n";

					$cur_country_id = '';
					foreach($data['state_list'] as $state)
					{
						if ($cur_country_id != $state->get_country_id())
						{
							$cur_country_id = $state->get_country_id();
							echo "arr_state_option['" . $cur_country_id . "'] = new Array();\n";
							echo "arr_state_option['" . $cur_country_id . "'][''] = '" . str_replace("'", "\'", $data['data']['lang_text']['select_state']) . "';\n";
						}
						echo "arr_state_option['" . $cur_country_id . "']['" . $state->get_state_id() . "'] = '" . str_replace("'", "\'", $state->get_name()) . "';\n";
					}
				}
				else
				{
					echo "var arr_state_option = {};\n";
				}
			?>
			var chk_cart_cookie = "<?=$data['chk_cart_cookie']?>";
		//]]>
		</script>

		<div class="checkoutOnepageBox_left left">
			<ol class="opc" id="checkoutSteps">
<?php
				//	var_dump($client);
				$client = $data['client'];
				$section_number = 1;
?>
				<li id="opc-login" class="<?=$data["show_block"]["login"]?>">
					<div class="step-title">
						<span class="number"><?=($data["show_block"]["login"] != "hide") ? $section_number++ : "";?></span>
						<h2><?=$data['data']['lang_text']['checkout_method']?></h2>
						<a href="#"><?=$data['data']['lang_text']['edit']?></a>
					</div>
					<div id="checkout-step-login" class="step a-item" style="display:none">
<?php
						if ($data['no_login_before'])
						{
?>
							<div class="col2-set">
								<div class="col-1">
									<h3><?=$data['data']['lang_text']['guest_checkout_head']?></h3>
									<ul class="form-list">
										<li><?=$data['data']['lang_text']['guest_checkout_content']?></li>
<?php
		if (!$data["allow_login"])
		{
?>
										<li>
											<label for="email" class="required"><em>*</em><?=$data['data']['lang_text']['email']?></label>
											<div class="input-box">
												<input type="text" class="input-text required-entry validate-email" title="<?=$data['data']['lang_text']['email']?>" dname="Email" notEmpty validEmail id="guest_email" name="guest_email" value="" />
											</div>
										</li>
<?php
		}
?>
									</ul>
								</div>
<?php
		if ($data["allow_login"])
		{
?>
								<div class="col-2">
									<h3><?=$data['data']['lang_text']['login']?></h3>
<?php
									if (!empty($_SESSION["NOTICE"]))
									{
?>
										<ul class="messages">
											<li class="error-msg">
											<ul>
												<li>
													<span><?=$_SESSION["NOTICE"];?></span>
												</li>
												</ul>
											</li>
										</ul>
<?php
									}
?>
									<form name='login_form' id="login_form" action="<?=base_url()?>login/checkout_login?back=checkout_onepage" method="post">
										<fieldset>
											<h4><?=$data['data']['lang_text']['already_registered']?></h4>
											<p><?=$data['data']['lang_text']['please_login_in']?></p>
											<ul class="form-list">
												<li>
													<label for="email" class="required"><em>*</em><?=$data['data']['lang_text']['email']?></label>
													<div class="input-box">
														<input type="text" class="input-text required-entry validate-email" title="<?=$data['data']['lang_text']['email']?>" dname="Email" notEmpty validEmail id="email" name="email" value="" />
													</div>
												</li>
												<li>
													<label for="password" class="required"><em>*</em><?=$data['data']['lang_text']['password']?></label>
													<div class="input-box">
														<input type="password" class="input-text required-entry" title="<?=$data['data']['lang_text']['password']?>" id="password" name="password" notEmpty />
													</div>
												</li>
											</ul>
											<input name="context" type="hidden" value="checkout" />
											<input type='hidden' name='posted' id='posted' value='1'>
										</fieldset>
									</form>
								</div>
<?php
		}
?>
							</div>

							<div class="col2-set">
								<div class="col-1">
									<div class="buttons-set">
										<p class="required">&nbsp;</p>
										<button id="onepage-guest-register-button" type="button" class="magbutton" onclick="if (checkGuestEmail(<?php print ($data["allow_login"]) ? "true" : "false";?>, '<?php print $data["data"]["lang_text"]["warning_put_email"];?>')) setActiveBlockRunning('<?=$data['next_step']?>')"><span><span><?=$data['data']['lang_text']['continue']?></span></span></button>
									</div>
								</div>
<?php
		if ($data["allow_login"])
		{
?>
								<div class="col-2">
									<div class="buttons-set normal-href">
										<p class="required">* <?=$data['data']['lang_text']['required_field']?></p>
										<a href="<?=base_url()?>forget_password?back=checkout_onepage" rel="lyteframe" rev="width: 600px; height:240px; scrolling: auto;padding: 40px;" title="" class="f-left"><?=$data['data']['lang_text']['forgot_password']?></a>
										<button id="onepage-guest-login-button" type="submit" class="magbutton" onclick="if(CheckForm(document.login_form))document.login_form.submit();"><span><span><?=$data['data']['lang_text']['login']?></span></span></button>
									</div>
								</div>
<?php
		}
?>
							</div>
<?php
		if ($data["allow_login"])
		{
?>
							<script type="text/javascript">
							//<![CDATA[
								var loginForm = document.login_form;
								$('password').observe('keypress', bindLoginPost);
								function bindLoginPost(evt){
									if (evt.keyCode == Event.KEY_RETURN) {
										loginForm.submit();
									}
								}
							//]]>
							</script>
<?php
		}
?>
<?php
						}
						else
						{
?>
							<div class="col1-layout">
								<div class="col-main">
									<h3><?=$data['data']['lang_text']['logged_in_before']?> (<?=$data['data']['lang_text']['login_as']?> <?=$client["email"]?>)</h3>
									<div class="buttons-set">
										<button id="onepage-already-login-button" type="button" class="magbutton" onclick="setActiveBlockRunning('billing')">
											<span><?=$data['data']['lang_text']['continue']?></span>
										</button>
									</div>
								</div>
							</div>
<?php
						}
?>
					</div>
				</li>

				<li id="opc-billing" class="<?=$data["show_block"]["billing"]?>">
					<div class="step-title">
						<span class="number"><?=($data["show_block"]["billing"] != "hide") ? $section_number++ : "";?></span>
						<h2><?=$data['data']['lang_text']['billing_information']?></h2>
						<a href="#"><?=$data['data']['lang_text']['edit']?></a>
					</div>

					<div id="checkout-step-billing" class="step a-item" style="display:none;">
						<form id="co-billing-form" action="">
							<fieldset>
								<ul class="form-list">
									<li id="billing-new-address-form">
										<fieldset>
											<ul>
												<li class="fields">
													<div class="delivery-country">
														<div class="field">
															<br>
															<label><?=$data['data']['lang_text']['billing_delivery_subject']?></label>
														</div>
														<div class="field">
															<label for="billing_country_id" class="required"><em>*</em><?=$data['data']['lang_text']['country']?></label>
															<div class="input-box">
<?php
																	$options_string = "";
																	$selectedCountryName = "";
																	foreach($data['country_list'] as $country)
																	{
																		if ($country->get_id() == $data['billing_data']['billing_country_id'])
																		{
																			$selected = "selected";
																			$selectedCountryName = $country->get_name();
																		}
																		else
																		{
																			$selected = "";
																		}
																		$options_string .= "<option value='" . $country->get_id() . "' " . $selected . ">" . $country->get_name() . "</option>";
																	}
?>
																<select notEmpty onchange="checkCountry('<?=$data['selected_country']?>', this, '<?php print get_lang_id();?>', '<?php print $selectedCountryName;?>'); calculateDeliverySurcharge();" name="billing_country_id" id="billing_country_id" class="validate-select" title="<?=$data['data']['lang_text']['country']?>">
																<?php print $options_string; ?>
																</select>
															</div>
														</div>
														<div id='shippingMessage'>
														</div>
													</div>
												</li>

												<li class="fields">
													<div class="customer-name">
														<div class="field name-firstname">
															<label for="billing_firstname" class="required"><em>*</em><?=$data['data']['lang_text']['first_name']?></label>
															<div class="input-box">
																<input notEmpty type="text" id="billing_firstname" name="billing_firstname" value="<?=$data['billing_data']['billing_firstname']?>" title="<?=$data['data']['lang_text']['first_name']?>" class="input-text required-entry"  />
															</div>
														</div>

														<div class="field name-lastname">
															<label for="billing_lastname" class="required"><em>*</em><?=$data['data']['lang_text']['last_name']?></label>
															<div class="input-box">
																<input notEmpty type="text" id="billing_lastname" name="billing_lastname" value="<?=$data['billing_data']['billing_lastname']?>" title="<?=$data['data']['lang_text']['last_name']?>" class="input-text required-entry"  />
															</div>
														</div>
													</div>
												</li>

												<li class="fields">
													<div class="field">
														<label for="billing_company"><?=$data['data']['lang_text']['company']?></label>
														<div class="input-box">
															<input type="text" id="billing_company" name="billing_company" value="<?=$data['billing_data']['billing_company']?>" title="<?=$data['data']['lang_text']['company']?>" class="input-text" />
														</div>
													</div>

<?php
													if ($data['no_login_before'])
													{
?>
														<div class="field">
															<label for="billing_email" class="required"><em>*</em><?=$data['data']['lang_text']['email_address']?></label>
															<div class="input-box">
																<input notEmpty validEmail type="text" name="billing_email" id="billing_email" value="<?=$data['billing_data']['billing_email']?>" title="<?=$data['data']['lang_text']['email_address']?>" class="input-text validate-email required-entry" />
															</div>
														</div>
<?php
													}
?>
												</li>

<?php
												$pobox_check = '';
												if (!$data['allow_pobox'])
												{
													$pobox_check = 'notMatchRegExI="((p[/) \.]*o[ \.\-]*|p[/) \.]*|post[ \.]*office[ \.]*|post[ ]*|mail[ ]*)box|box[ ]*[\+][ ]*[0-9]{2,4})" warningMsg="' . $data['data']['lang_text']['cannot_po_box'] . '"';
												}
?>
<?php
												if($data["show_client_id"])
												{
?>
												<li class="fields">
													<div class="field">
														<label for="client_id_no"><?=$data['data']['lang_text']['client_id_no']?> <a href ="" title="<?=$data['data']['lang_text']['client_id_title']?>">[?]</a></label>
														<div class="input-box">
															<input type="text" id="client_id_no" name="client_id_no" value="<?=$data['billing_data']['billing_client_id_no']?>" title="<?=$data['data']['lang_text']['client_id_no']?>" class="input-text" />
														</div>
													</div>
												</li>
<?php
												}
?>
												<li class="wide">
													<label for="address1" class="required"><em>*</em><?=$data['data']['lang_text']['address']?></label>
													<div class="input-box">
														<input notEmpty <?=$pobox_check?> type="text" title="<?=$data['data']['lang_text']['address']?>" name="address1" id="address1" value="<?=$data['billing_data']['address1']?>" maxlength=40 class="input-text required-entry" />
													</div>
												</li>

												<li class="wide">
													<div class="input-box">
														<input <?=$pobox_check?> type="text" title="" name="address2" id="address2" value="<?=$data['billing_data']['address2']?>" maxlength=40 class="input-text" />
													</div>
												</li>

												<li class="wide">
													<div class="input-box">
														<input <?=$pobox_check?> type="text" title="" name="address3" id="address3" value="<?=$data['billing_data']['address3']?>" maxlength=40 class="input-text" />
													</div>
												</li>

												<li class="fields">
													<div class="field">
														<label for="billing_city" class="required"><em>*</em><?=$data['data']['lang_text']['city']?></label>
														<div class="input-box">
															<input notEmpty type="text" title="<?=$data['data']['lang_text']['city']?>" name="billing_city" value="<?=$data['billing_data']['billing_city']?>" class="input-text required-entry" id="billing_city" />
														</div>
													</div>

													<div class="field">
														<label for="billing_state" id="billing_state_label" class="required"><em>*</em><?=$data['data']['lang_text']['state_province']?></label>
														<div class="input-box">
															<select notEmpty id="billing_state" name="billing_state" title="<?=$data['data']['lang_text']['state_province']?>" class="validate-select" style="">
															</select>
														</div>
													</div>
												</li>

												<li class="fields">
													<div class="field">
														<label for="billing_post_code" id="billing_post_code_label" class="required"><em>*</em><?=$data['data']['lang_text']['postal_code']?></label>
														<div class="input-box">
															<input validPostal="billing_country_id" type="text" title="<?=$data['data']['lang_text']['postal_code']?>" name="billing_post_code" id="billing_post_code" value="<?=$data['billing_data']['billing_post_code']?>" class="input-text validate-zip-international required-entry" />
														</div>
													</div>

												</li>
												<li class="fields">
													<label class="required"><?=$data['data']['lang_text']['telephone']?></label>
												</li>
												<li class="fields">
													<div class="tel_field">
														<label for="billing_telephone1" class="required"><?=$data['data']['lang_text']['country_code']?></label>
														<div class="">
															<input isNumber type="text" name="billing_telephone1" value="<?=$data['billing_data']['billing_telephone1']?>" title="<?=$data['data']['lang_text']['country_code']?>" class="tel12_input-text required-entry" id="billing_telephone1" />
														</div>
													</div>
													<div class="tel_field_space"></div>
													<div class="tel_field">
														<label for="billing_telephone2" class="required"><?=$data['data']['lang_text']['area_code']?></label>
														<div class="input-box">
															<input isNumber type="text" name="billing_telephone2" value="<?=$data['billing_data']['billing_telephone2']?>" title="<?=$data['data']['lang_text']['area_code']?>" class="tel12_input-text required-entry" id="billing_telephone2" />
														</div>
													</div>
													<div class="tel_field_space"></div>
													<div class="tel_field">
														<label for="billing_telephone3" class="required"><em>*</em><?=$data['data']['lang_text']['number']?></label>
														<div class="input-box">
															<input notEmpty isNumber type="text" name="billing_telephone3" value="<?=$data['billing_data']['billing_telephone3']?>" title="<?=$data['data']['lang_text']['telephone']?>" class="tel_input-text required-entry" id="billing_telephone3" />
														</div>
													</div>
												</li>
												<li class="no-display"><input type="hidden" name="billing_save_in_address_book" value="1" /></li>
											</ul>

											<div id="window-overlay" class="window-overlay" style="display:none;"></div>

											<div id="remember-me-popup" class="remember-me-popup" style="display:none;">
												<div class="remember-me-popup-head">
													<h3>What's this?</h3>
													<a href="#" class="remember-me-popup-close" title="Close">Close</a>
												</div>

												<div class="remember-me-popup-body">
													<p>Checking &quot;Remember Me&quot; will let you access your shopping cart on this computer when you are logged out</p>

													<div class="remember-me-popup-close-button a-right">
														<a href="#" class="remember-me-popup-close button" title="Close"><span>Close</span></a>
													</div>
												</div>
											</div>

											<script type="text/javascript">
											//<![CDATA[
												function toggleRememberMepopup(event){
													if($('remember-me-popup')){
														var viewportHeight = document.viewport.getHeight(),
															docHeight      = $$('body')[0].getHeight(),
															height         = docHeight > viewportHeight ? docHeight : viewportHeight;
														$('remember-me-popup').toggle();
														$('window-overlay').setStyle({ height: height + 'px' }).toggle();
													}
													Event.stop(event);
												}

												document.observe("dom:loaded", function() {
													new Insertion.Bottom($$('body')[0], $('window-overlay'));
													new Insertion.Bottom($$('body')[0], $('remember-me-popup'));

													$$('.remember-me-popup-close').each(function(element){
														Event.observe(element, 'click', toggleRememberMepopup);
													})
													$$('#remember-me-box a').each(function(element) {
														Event.observe(element, 'click', toggleRememberMepopup);
													});
												});
											//]]>
											</script>
										</fieldset>
									</li>

<?php
								if ($data['no_login_before'])
								{
?>
									<li class="fields">
										<div class="line-separator">
										<?=$data['data']['lang_text']['put_a_password']?>
										</div>
										<div class="field">
											<label for="billing_password"><?=$data['data']['lang_text']['password']?></label>
											<div class="input-box">
												<input type="password" autocomplete="off" title="<?=$data['data']['lang_text']['password']?>" name="billing_password" value="" class="input-text required-entry" id="billing_password" />
											</div>
										</div>

										<div class="field">
											<label for="billing_confirm_password"><?=$data['data']['lang_text']['confirm_password']?></label>
											<div class="input-box">
												<input type="password" autocomplete="off" title="<?=$data['data']['lang_text']['confirm_password']?>" name="billing_confirm_password" value="" class="input-text required-entry" id="billing_confirm_password" />
											</div>
										</div>
									</li>
<?php
								}
?>
									<div id="shipping_delivery_option">
										<li class="control">
											<input type="radio" name="billing_use_for_shipping" id="billing_use_for_shipping_yes" value="1" <?=$data['billing_data']['billing_use_for_shipping']=='1'?'checked="checked"':'';?> title="<?=$data['data']['lang_text']['ship_to_this']?>" class="radio" /><label for="billing_use_for_shipping_yes"><?=$data['data']['lang_text']['ship_to_this']?></label>
										</li>
										<li class="control">
											<input type="radio" name="billing_use_for_shipping" id="billing_use_for_shipping_no" value="0" <?=$data['billing_data']['billing_use_for_shipping']!='1'?'checked="checked"':'';?> title="<?=$data['data']['lang_text']['ship_to_different']?>" class="radio" /><label for="billing_use_for_shipping_no"><?=$data['data']['lang_text']['ship_to_different']?></label>
										</li>
									</div>
								</ul>

								<div class="buttons-set" id="billing-buttons-container">
									<p class="required">* <?=$data['data']['lang_text']['required_field']?></p>

									<div class="conditions_agreement">
										<?php
											$conditions_agreement_available_country = 'GB|AU|FI|HK|IE|MY|MT|CH|US|ES|SG|IT|BE|FR|NZ|';
											if (strpos($conditions_agreement_available_country, $data['selected_country'].'|') !== FALSE)
											{
										?>
												<span class="agreement">
													<?=$data['data']['lang_text']['conditions_agreement_1']?>
													<span class="hyper_link" onclick="window.open('<?=base_url().'display/view/conditions_of_use'?>', 'TC', 'width=600,height=400,left=50,resizeable=yes,scrollbars=yes,titlebar=yes,resizeable=1,scrollbars=1,titlebar=1');">
														<?=$data['data']['lang_text']['conditions_agreement_2']?>
													</span>
													<?=$data['data']['lang_text']['conditions_agreement_3']?>
												</span>
										<?php
											}
										?>

										<button id="onepage_billing_continue" type="button" title="<?=$data['data']['lang_text']['continue']?>" class="magbutton" onclick="if (CheckForm(this.form)) {billingFormContinue(); calculateDeliverySurcharge();}">
											<span><span><?=$data['data']['lang_text']['continue']?></span></span>
										</button>
									</div>
								</div>
							</fieldset>
						</form>
					</div>
				</li>

				<li id="opc-shipping" class="<?=$data["show_block"]["shipping"]?>">
					<div class="step-title">
						<span class="number"><?=($data["show_block"]["shipping"] != "hide") ? $section_number++ : "";?></span>
						<h2><?=$data['data']['lang_text']['shipping_information']?></h2>
						<a href="#"><?=$data['data']['lang_text']['edit']?></a>
					</div>

					<div id="checkout-step-shipping" class="step a-item" style="display:none;">
						<form action="" id="co-shipping-form">
							<ul class="form-list">
								<li id="shipping-new-address-form">
									<fieldset>
										<ul>
											<li class="fields">
												<div class="customer-name">
													<div class="field name-firstname">
														<label for="shipping_firstname" class="required"><em>*</em><?=$data['data']['lang_text']['first_name']?></label>
														<div class="input-box">
															<input notEmpty type="text" id="shipping_firstname" name="shipping_firstname" value="<?=$data['shipping_data']['shipping_firstname']?>" title="<?=$data['data']['lang_text']['first_name']?>" class="input-text required-entry" onchange="setDifferentFromBillingAddr();" />
														</div>
													</div>

													<div class="field name-lastname">
														<label for="shipping_lastname" class="required"><em>*</em><?=$data['data']['lang_text']['last_name']?></label>
														<div class="input-box">
															<input notEmpty type="text" id="shipping_lastname" name="shipping_lastname" value="<?=$data['shipping_data']['shipping_lastname']?>" title="<?=$data['data']['lang_text']['last_name']?>" class="input-text required-entry" onchange="setDifferentFromBillingAddr();" />
														</div>
													</div>
												</div>
											</li>

											<li class="fields">
												<div class="fields">
													<label for="shipping_company"><?=$data['data']['lang_text']['company']?></label>
													<div class="input-box">
														<input type="text" id="shipping_company" name="shipping_company" value="<?=$data['shipping_data']['shipping_company']?>" title="<?=$data['data']['lang_text']['company']?>" class="input-text" onchange="setDifferentFromBillingAddr();" />
													</div>
												</div>
											</li>

											<li class="wide">
												<label for="shipping_address1" class="required"><em>*</em><?=$data['data']['lang_text']['address']?></label>
												<div class="input-box">
													<input notEmpty <?=$pobox_check?> type="text" title="<?=$data['data']['lang_text']['address']?>" name="shipping_address1" id="shipping_address1" value="<?=$data['shipping_data']['shipping_address1']?>" maxlength=40 class="input-text required-entry" onchange="setDifferentFromBillingAddr();" />
												</div>
											</li>

											<li class="wide">
												<div class="input-box">
													<input <?=$pobox_check?> type="text" title="<?=$data['data']['lang_text']['address']?> 2" name="shipping_address2" id="shipping_address2" value="<?=$data['shipping_data']['shipping_address2']?>" maxlength=40 class="input-text" onchange="setDifferentFromBillingAddr();" />
												</div>
											</li>

											<li class="wide">
												<div class="input-box">
													<input <?=$pobox_check?> type="text" title="<?=$data['data']['lang_text']['address']?> 3" name="shipping_address3" id="shipping_address3" value="<?=$data['shipping_data']['shipping_address3']?>" maxlength=40 class="input-text" onchange="setDifferentFromBillingAddr();" />
												</div>
											</li>

											<li class="fields">
												<div class="field">
													<label for="shipping_city" class="required"><em>*</em><?=$data['data']['lang_text']['city']?></label>
													<div class="input-box">
														<input notEmpty type="text" title="<?=$data['data']['lang_text']['city']?>" name="shipping_city" value="<?=$data['shipping_data']['shipping_city']?>" class="input-text required-entry" id="shipping_city" onchange="setDifferentFromBillingAddr();" />
													</div>
												</div>
<?php
		if ($data["allow_login"])
		{
?>
												<div class="field">
													<label for="shipping_state" id="shipping_state_label" class="required"><em>*</em><?=$data['data']['lang_text']['state_province']?></label>
													<div class="input-box">
														<select notEmpty id="shipping_state" name="shipping_state" title="<?=$data['data']['lang_text']['state_province']?>" class="validate-select" onchange="setDifferentFromBillingAddr();">
														</select>
													</div>
												</div>
<?php
		}
?>
											</li>

											<li class="fields">
												<div class="field">
													<label for="shipping_post_code" id="shipping_post_code_label" class="required"><em>*</em><?=$data['data']['lang_text']['postal_code']?></label>
													<div class="input-box">
														<input validPostal="shipping_country_id" type="text" title="<?=$data['data']['lang_text']['postal_code']?>" name="shipping_post_code" id="shipping_post_code" value="<?=$data['shipping_data']['shipping_post_code']?>" class="input-text validate-zip-international required-entry" onchange="setDifferentFromBillingAddr(); calculateDeliverySurcharge();" />
													</div>
												</div>

												<div class="field">
													<label for="shipping_country_id" class="required"><em>*</em><?=$data['data']['lang_text']['country']?></label>
													<div class="input-box">
														<select notEmpty name="shipping_country_id" id="shipping_country_id" class="validate-select" title="<?=$data['data']['lang_text']['country']?>" disabled onchange="setDifferentFromBillingAddr();">
<?php
															if ($data["allow_login"])
															{
																foreach($data['country_list'] as $country)
																{
																	print "<option value='" . $country->get_id() . "'" . ($country->get_id()==$data['shipping_data']['shipping_country_id']?' selected ':'') . ">" . $country->get_name() . "</option>";
																}
															}
															else
															{
																foreach($data['country_list'] as $country)
																{
																	if ($data['selected_country'] == $country->get_id())
																		print "<option value='" . $country->get_id() . "'" . ($country->get_id()==$data['shipping_data']['shipping_country_id']?' selected ':'') . ">" . $country->get_name() . "</option>";
																}
															}
?>
														</select>
													</div>
												</div>
											</li>

												<li class="fields">
													<label class="required"><?=$data['data']['lang_text']['telephone']?></label>
												</li>
												<li class="fields">
													<div class="tel_field">
														<label for="shipping_telephone1" class="required"><?=$data['data']['lang_text']['country_code']?></label>
														<div class="">
															<input isNumber type="text" name="shipping_telephone1" value="<?=$data['shipping_data']['shipping_telephone1']?>" title="<?=$data['data']['lang_text']['country_code']?>" class="tel12_input-text required-entry" id="shipping_telephone1" />
														</div>
													</div>
													<div class="tel_field_space"></div>
													<div class="tel_field">
														<label for="shipping_telephone2" class="required"><?=$data['data']['lang_text']['area_code']?></label>
														<div class="input-box">
															<input isNumber type="text" name="shipping_telephone2" value="<?=$data['shipping_data']['shipping_telephone2']?>" title="<?=$data['data']['lang_text']['area_code']?>" class="tel12_input-text required-entry" id="shipping_telephone2" />
														</div>
													</div>
													<div class="tel_field_space"></div>
													<div class="tel_field">
														<label for="shipping_telephone3" class="required"><em>*</em><?=$data['data']['lang_text']['number']?></label>
														<div class="input-box">
															<input notEmpty isNumber type="text" name="shipping_telephone3" value="<?=$data['shipping_data']['shipping_telephone3']?>" title="<?=$data['data']['lang_text']['telephone']?>" class="tel_input-text required-entry" id="shipping_telephone3" onchange="setDifferentFromBillingAddr();" />
														</div>
													</div>
												</li>
											<li class="no-display"><input type="hidden" name="shipping_save_in_address_book" value="1" /></li>
										</ul>
									</fieldset>
								</li>
<?php
	if ($data["allow_login"])
	{
?>
								<li class="control">
									<input type="checkbox" name="shipping_same_as_billing" id="shipping_same_as_billing" value="1" <?=($data['shipping_data']['shipping_same_as_billing']==1?' checked':'');?> title="<?=$data['data']['lang_text']['use_billing_address']?>" onclick="useBillingAddress(this.checked);" />
									<label for="shipping_same_as_billing"><?=$data['data']['lang_text']['use_billing_address']?></label>
								</li>
<?php
	}
?>

							</ul>

							<div class="buttons-set" id="shipping-buttons-container">
								<p class="required">* <?=$data['data']['lang_text']['required_field']?></p>

								<div class="conditions_agreement">
									<?php
										if ($data['selected_country'] == 'PH')
										{
									?>
											<span class="agreement">
												<?=$data['data']['lang_text']['conditions_agreement_1']?>
												<span class="hyper_link" onclick="window.open('<?=base_url().'display/view/conditions_of_use'?>', 'TC', 'width=600,height=400,left=50,resizeable=yes,scrollbars=yes,titlebar=yes,resizeable=1,scrollbars=1,titlebar=1');">
													<?=$data['data']['lang_text']['conditions_agreement_2']?>
												</span>
												<?=$data['data']['lang_text']['conditions_agreement_3']?>
											</span>
									<?php
										}
									?>

									<button id="onepage_shipping_continue" type="button" class="magbutton" title="<?=$data['data']['lang_text']['continue']?>" onclick="if (CheckForm(this.form)) {updateShippingAddrInSummaryBox(); setActiveBlockRunning('payment');}">
										<span><span><?=$data['data']['lang_text']['continue']?></span></span>
									</button>
								</div>
							</div>
						</form>
					</div>
				</li>

				<li id="opc-payment" class="<?=$data["show_block"]["payment"]?>">
					<div class="step-title">
						<span class="number"><?=($data["show_block"]["payment"] != "hide") ? $section_number++ : "";?></span>

						<h2><?=$data['data']['lang_text']['payment_information']?></h2>
						<a href="#"><?=$data['data']['lang_text']['edit']?></a>
					</div>

					<div id="checkout-step-payment" class="step a-item" style="display:none;">
						<div id='card_form'>
							<div id='select_a_card'><b><?=$data['data']['lang_text']['please_a_payment_options']?></b><br><br></div>
							<form name="card_list_form" id="card_list_form">
							<div id='card_list' name='card_list'>
<?php if (PLATFORMID == "WEBRU")
		{
 ?>
<div>
<!--<input type="radio" align="absmiddle" value="PSP%%yandex%%mb_PSP" id="card_mb_PSP" name="payment_methods" onclick="showCardExplanation('yandex');">-->
	<div id="kiosk-block">
		<div class="card-block-title"><?=$data['data']['lang_text']['ru_payment_form_kiosk_title']?></div>
		<div class="card_row">
			<a href="javascript::void(0)" onclick="loadingPaymentGateway('GP%%yandex%%yd_ATM')" ><div style="float:left;"><img src="/images/90x45/cash_kiosk1.png" border="0"></div></a>
			<div style="float:left;width:400px;"><?=$data['data']['lang_text']['ru_payment_form_kiosk_paragraph']?></div>
		</div>
		<div id="kiosk" class="card_row">
			<a href="javascript::void(0)" onclick="loadingPaymentGateway('GP%%yandex%%yd_ATM')" ><img src="/images/90x45/cash_kiosk2.png" border="0"></a>
			<a href="javascript::void(0)" onclick="loadingPaymentGateway('GP%%yandex%%yd_ATM')"><img src="/images/90x45/cash_kiosk3.png" border="0"></a>
			<a href="javascript::void(0)" onclick="loadingPaymentGateway('GP%%yandex%%yd_ATM')"><img src="/images/90x45/cash_kiosk4.png" border="0"></a>
			<a href="javascript::void(0)" onclick="loadingPaymentGateway('GP%%yandex%%yd_ATM')"><img src="/images/90x45/cash_kiosk5.png" border="0"></a>
			<a href="javascript::void(0)" onclick="loadingPaymentGateway('GP%%yandex%%yd_ATM')"><img src="/images/90x45/cash_kiosk6.png" border="0"></a>
			<span><?=$data['data']['lang_text']['ru_payment_form_kiosk_etc']?><span>
		</div>
<!--
		<div class="card_row">
			<img src="/images/90x45/cash_kiosk1.png" border="0">
		</div>
-->
	</div>
	<div id="card-block">
		<div class="card-block-title"><?=$data['data']['lang_text']['ru_payment_form_credit_card_title']?></div>
		<div class="card_row">
			<a href="javascript::void(0)" onclick="loadingPaymentGateway('AC%%yandex%%yd_VSA')"><img src="/images/90x45/powered-btn-visa-90x45.png" border="0"></a>
			<a href="javascript::void(0)" onclick="loadingPaymentGateway('AC%%yandex%%yd_VSE')"><img src="/images/90x45/powered-btn-visacredit-90x45.png" border="0"></a>
			<a href="javascript::void(0)" onclick="loadingPaymentGateway('AC%%yandex%%yd_VSE')"><img src="/images/90x45/powered-btn-mc-90x45.png" border="0"></a>
		</div>
		<div class="card_row">
			<a href="javascript::void(0)" onclick="loadingPaymentGateway('Maestro%%paypal%%paypal_MAE')"><img src="/images/90x45/powered-btn-maestro-90x45.png" border="0"></a>
			<a href="javascript::void(0)" onclick="loadingPaymentGateway('AMX%%paypal%%paypal_AMX')"><img src="/images/90x45/powered-btn-amex-90x45.png" border="0"></a>
			<a href="javascript::void(0)" onclick="loadingPaymentGateway('DISCOVER%%paypal%%paypal_DIS')"><img src="/images/90x45/powered-btn-discover-90x45.png" border="0"></a>
		</div>
	</div>
	<div id="web-money-block">
		<div class="card-block-title"><?=$data['data']['lang_text']['ru_payment_form_web_money_title']?></div>
		<div class="card_row"><a href="javascript::void(0)" onclick="loadingPaymentGateway('paypal%%paypal%%paypal')"><img src="/images/btn_paypal.png" border="0"></a></div>
		<div class="card_row"><a href="javascript::void(0)" onclick="loadingPaymentGateway('PC%%yandex%%yd_money')"><img src="/images/90x45/yandex-money-90x45.png" border="0"></a></div>
		<div class="card_row"><a href="javascript::void(0)" onclick="loadingPaymentGateway('WM%%yandex%%yd_WM')"><img src="/images/90x45/yandex-wm-90x45.png" border="0"></a></div>
<!--		<div class="card_row"><img src="/images/90x45/yandex-wm-90x45.png" border="0"></div> -->
	</div>
</div>
 <?php
		}
 ?>
							</div>
							<div style="clear:both;"></div>
							<div id='trustly_container' style="display:none;">
								<div id='trustly_div'></div>
								<div id='trustly_div_text'><a id='fancy_trustly' style="text-decoration:underline;" class='fancybox iframe' title='https://trustly.com/whatistrustly' href='https://trustly.com/whatistrustly'><?=$data['data']['lang_text']['trustly_message_1']?><span class='frame'></span></a><br><b><?=$data['data']['lang_text']['trustly_message_2']?></b></div>
								<div style="clear:both;"></div>
								<div id='trustly_point'>
									<ul>
										<li><?=$data['data']['lang_text']['trustly_message_point_1']?></li>
										<li><?=$data['data']['lang_text']['trustly_message_point_2']?></li>
									</ul>
								</div>
							</div>
							<div style="clear:both;"></div>
							<div id='bank_transfer_container' style="display:none;">
								<div id='bank_transfer_div'></div>
								<div id='bank_transfer_div_text' style="position:relative;float:left;"><?=$data['data']['lang_text']['bank_transfer_message']?></div>
								<div style="clear:both;"></div>
								<div id='bank_transfer_point'>
								</div>
							</div>
							</form>
						</div>
						<div style="clear:both;"></div>

						<?if(PLATFORMID == "WEBIT"){?>
								<div id="payment_tips" style="display:inline-block; border: 1px solid #E4E4E4;padding:10px 10px; background-color:white; margin-top:10px;">
									<?=$data['data']['lang_text']['payment_tips']?>
								</div>
						<?}?>
						<div class="buttons-set" id="payment-buttons-container">
<?php if (PLATFORMID != "WEBRU")
		{
 ?>

							<button id="onepage_payment_continue" type="button" class="magbutton" onclick="loadingPaymentGateway('')"><span><span><?=$data['data']['lang_text']['continue']?></span></span></button>
<?php
		}
 ?>
						</div>

						<input type="hidden" name="p_enc" id='p_enc' value="<?=$data['p_enc'];?>">
						<input type="hidden" name="cybersource_fingerprint" id='cybersource_fingerprint' value="<?=$data['cybersource_fingerprint'];?>">
						<div id='loading_display' style="text-align:center;display:none;position:relative;"><img src='/images/loading.gif' border='0'></div>
						<div id='payment_gateway_block'>
							<iframe id="payment_frame" name="payment_frame" style="position:relative;width:100%;height:100%" src="about:blank" frameborder='0' scrolling='auto'>
							</iframe>
						</div>

						<script type="text/javascript">
							jQuery(document).ready(function(){
								if (document.getElementById('fancy_trustly'))
								{
									jQuery(".fancybox").fancybox({
										'width'  : 600,           // set the width
										'height' : 600,           // set the height
										'titlePosition' : 'over'
									});
								}
							});
						//<![CDATA[
//							var ajax = createAjaxObject();
							var payment_type = '';

							function loadRuGateway(parameters)
							{
								return parameters.split('%%');
							}

							function loadPaymentGateway(withCarId)
							{
								var payment_array;

								if (withCarId == "")
								{
									payment_array = get_payment_info();
								}
								else
								{
									payment_array = loadRuGateway(withCarId);
								}
								if (payment_array)
								{
									card_type = payment_array[0];
									payment_type = payment_array[1];
									card_code = payment_array[2];
								}

								var url = "<?php print base_url(); ?>checkout_redirect_method/process_redirect_checkout?payment_type=" + payment_type + "<?php print ($data['debug'])?'&debug=1':'' ?>";
								var parameters_need = loadPaymentGatewayParameter(withCarId);
								var frameObj = document.getElementById('payment_frame');
								var paymentGatewayObj = document.getElementById('payment_gateway_block');

								if (!parameters_need)
									alert('wrong parameters');
								else
								{
									displayLoading(true);
									paymentGatewayObj.style.display = "none";
									frameObj.src = "<?php print base_url() . 'checkout_redirect_method/empty_page'; ?>";
									jQuery.ajax({
										type: "POST",
										url: url,
										data: parameters_need,
										datatype: "html",
										success: function(result)
										{
											var return_url = result;
	//										alert(return_url);

											if (return_url.substring(0, 5) == "ERROR")
											{
												displayLoading(false);
												if ((payment_type == 'moneybookers') || (payment_type == 'yandex') || (payment_type == 'global_collect')  || (payment_type == 'altapay') || (payment_type == 'adyen'))
												{
													top.location.href = return_url.substr(7);
												}
												else
													alert(return_url);
											}
											else if (return_url.substring(0, 7) == "SESSION")
											{
												displayLoading(false);
												alert('<?=str_replace("'", "\'", $data['data']['lang_text']['session_invalid']);?>');
											}
											else
											{
												var frameObj = document.getElementById('payment_frame');
												var paymentGatewayObj = document.getElementById('payment_gateway_block');

												if (payment_type == 'paypal')
												{
													extractedUrl = return_url.substring(return_url.indexOf('top.document.location.href'), (return_url.indexOf('script>') - 2));
													eval(extractedUrl);
												}
												else if ((payment_type == 'worldpay')
													|| (payment_type == 'cybersource')
													|| (payment_type == 'yandex')
													|| (payment_type == 'altapay')
													|| (payment_type == 'm_bank_transfer')
													|| ((navigator.userAgent.indexOf("Chrome") == -1) && (navigator.userAgent.indexOf("Safari") > -1) && (payment_type != 'adyen'))
													)
												{
													window.self.location = return_url;
												}
												else if ((payment_type == 'inpendium_ctpe') && (global_card_id == "card_inp_ctpe_SFT"))
												{
													top.location.href = return_url;
												}
												else if(payment_type == 'adyen')
												{
													var string = return_url.split('?');
													var adyen_url = string[0];
													if(string[1])
														var params = JSON.parse(string[1]);
													if(params)
													{
														var form = document.createElement("form");
														form.setAttribute("method", "post");
														form.setAttribute("action", adyen_url);
														for(var key in params)
														{
															if(params.hasOwnProperty(key))
															{
																var hiddenField = document.createElement("input");
																hiddenField.setAttribute("type", "hidden");
																hiddenField.setAttribute("name", key);
																hiddenField.setAttribute("value", params[key]);

																form.appendChild(hiddenField);
															 }
														}
														document.body.appendChild(form);
														form.submit();
													}
												}
												else
												{
	//Trustly, Inpendium, global_collect, altapay
													document.getElementById('checkout-step-payment').style.height = "800px";
													if (payment_type == 'moneybookers')
														frameObj.style.height = "750px";
													else
														frameObj.style.height = "700px";
													document.getElementById('select_a_card').style.display = "none";
													frameObj.src = return_url;
													paymentGatewayObj.style.display = "";
													displayLoading(false);
												}
											}
										},
										error: function(XMLHttpRequest, textStatus, errorThrown)
										{
											displayLoading(false);
											alert("response" + textStatus);
										}
									})
								}
							}

							function checkIfCardSelected()
							{
								var radios = document.getElementsByName("payment_methods");

								for (var i = 0, len = radios.length; i < len; i++)
								{
									if (radios[i].checked)
									{
										global_card_id = radios[i].id;
										return true;
									}
								}
								alert('<?=$data['data']['lang_text']['please_select_a_card']?>');
								return false;
							}
							function loadingPaymentGateway(withCarId)
							{
								if (((withCarId == "") && checkIfCardSelected()) || (withCarId != ""))
								{
									var payment_block = document.getElementById("payment-buttons-container");
									payment_block.style.display = "none";
									var payment_tips_block = document.getElementById("payment_tips");
									if(payment_tips_block)
									{
										payment_tips_block.style.display= "none";
									}

									var card_list = document.getElementById("card_list");
										card_list.style.display = "none";
									var trustly_container = document.getElementById("trustly_container");
										trustly_container.style.display = "none";
									var bank_transfer_container = document.getElementById("bank_transfer_container");
										bank_transfer_container.style.display = "none";
									var bankTransCard = document.getElementById('card_btrans_website');
									if (bankTransCard != null)
									{
										if (!bankTransCard.checked)
											document.getElementById('assistant_block').style.display = "";
									}
									else
									{
										document.getElementById('assistant_block').style.display = "";
									}
									loadPaymentGateway(withCarId);
								}
							}

						//]]>
						</script>
					</div>
				</li>
<!--
				<li id="opc-review" class="<?=$data["show_block"]["review"]?>">
					<div class="step-title">
						<span class="number"><?=$section_number++;?></span>
						<h2>Order Review</h2>
						<a href="#">Edit</a>
					</div>

					<div id="checkout-step-review" class="step a-item" style="display:none;">
						<div class="order-review" id="checkout-review-load"></div>
					</div>
				</li>
-->
			</ol>
			<div id='card_explanation' style="padding-top:10px;display:none;">
<?php
			if (isset($data['data']['lang_text']['card_message']) && ($data['data']['lang_text']['card_message'] != ""))
			{
?>
				<?=$data['data']['lang_text']['card_message']?>
				<br>
				<div style="padding-top:10px;text-align:center;">
					<img src=<?php print base_cdn_url() . "/resources/images/" . get_lang_id() . "_cards.jpg" ?> border='0'>
				</div>
<?php
			}
			else
			{
?>
				<div style="padding-top:10px;text-align:left;">
					<div>
						<div style="float:left;padding-top:6px;"><img width="120px" src=<?php print base_cdn_url() . "/resources/images/card_explain_visa.jpg" ?> border='0'></div>
						<div style="float:left;padding-top:10px;width:500px;font-size:13px;">
							<?=$data['data']['lang_text']['card_message_visa']?>
<?php
		$card_url = isset($data['data']['lang_text']['card_message_visa_url_' . strtolower(PLATFORMCOUNTRYID)]) ? $data['data']['lang_text']['card_message_visa_url_' . strtolower(PLATFORMCOUNTRYID)]:"";
		if ($card_url != "")
		{
?>
							<a target="_blank" href="https://<?php print $card_url?>" style="font-weight:bold;text-decoration:underline;">
								<?=$data['data']['lang_text']['card_message_click_here']?>
							</a>
<?php
		}
?>
						</div>
					</div>
					<div style="clear:both;"></div>
					<div>
						<div style="float:left;"><img width="120px" src=<?php print base_cdn_url() . "/resources/images/card_explain_master.jpg" ?> border='0'></div>
						<div style="float:left;padding-top:10px;width:500px;font-size:13px;">
							<?=$data['data']['lang_text']['card_message_master']?>
<?php
		$card_url = isset($data['data']['lang_text']['card_message_master_url_' . strtolower(PLATFORMCOUNTRYID)]) ? $data['data']['lang_text']['card_message_master_url_' . strtolower(PLATFORMCOUNTRYID)]:"";
		if ($card_url != "")
		{
?>
							<a target="_blank" href="https://<?php print $card_url?>" style="font-weight:bold;text-decoration:underline;">
								<?=$data['data']['lang_text']['card_message_click_here']?>
							</a>
<?php
		}
?>
						</div>
					</div>
					<div style="clear:both;"></div>
					<div style="float:left;padding-top:10px;width:600px;font-size:13px;">
					<?=$data['data']['lang_text']['card_message_cvv']?>
<?php
		$cvv_url = isset($data['data']['lang_text']['card_message_cvv_url_' . strtolower(PLATFORMCOUNTRYID)]) ? $data['data']['lang_text']['card_message_cvv_url_' . strtolower(PLATFORMCOUNTRYID)]:"";
?>

							<a target="_blank" href="https://<?php print $cvv_url?>" style="font-weight:bold;text-decoration:underline;">
								<?=$data['data']['lang_text']['card_message_click_here']?>
							</a>
					</div>
				</div>
<?php
			}
?>
			</div>
			<!--
			<div id="assistant_block" style="text-align:center;padding-top:10px;display:none;">
				<?=$data['data']['lang_text']['require_assistance_1']?>
				<a id="assistant_link" href="<?print base_url(). "checkout_onepage/payment_result/0?type=assistant";?>" style="color:#FF1962;text-decoration:underline;"><?=$data['data']['lang_text']['require_assistance_2']?></a>
				<?=$data['data']['lang_text']['require_assistance_3']?>
			</div>
			-->
		</div>

		<script type="text/javascript">
			var onepageNextBlock = "<?php print $data['next_step']; ?>";
			var afterActiveId = 0;
			//<![CDATA[
				function setActiveBlock(activeBlockName, forceActive)
				{
					var allow_login = <?php print ($data["allow_login"]) ? "true" : "false";?>;
					var headerBlockName = 'opc-' + activeBlockName;
					var activeBlock = document.getElementById(headerBlockName);
//					var block_list = "<?php print $data['block_list'];?>";
//					var block_list_arr = block_list.split(",");
					var display_block_list = "<?php print $data['display_block_list'];?>";
					var display_block_list_arr = display_block_list.split(",");
					var isGuest = "<?php print $data['no_login_before']; ?>"
					var i = 0;
					var opcBlock;
//always enable this button
					var payment_block = document.getElementById("payment-buttons-container");
					payment_block.style.display = "";

					var card_list = document.getElementById("card_list");
					card_list.style.display = "";

					if (activeBlockName == "payment")
					{
/*
						if (allow_login)
						{
							document.getElementById('card_explanation').style.display = "";
						}
*/
					}
					else
					{
						document.getElementById('card_explanation').style.display = "none";
					}

					if ((activeBlock.className.search('allow') == -1) && (!forceActive) && (activeBlockName != onepageNextBlock))
					{
//if it is not allow to edit, just do nothing.
						return 0;
					}

					for (i=0;i<display_block_list_arr.length;i++)
					{
						opcBlock = document.getElementById("opc-" + display_block_list_arr[i]);
						if (display_block_list_arr[i] != activeBlockName)
						{
							document.getElementById("checkout-step-" + display_block_list_arr[i]).style.display = "none";

							if (i > afterActiveId)
							{
								opcBlock.className = "section";
								opcBlock.onmousedown = null;
							}
							else
							{
								opcBlock.className = "section allow";
								opcBlock.onmousedown = (function(inputBlockName)
													{
														return function(){setActiveBlockRunning(inputBlockName);}
													}
												   )(display_block_list_arr[i]);
							}

							if (display_block_list_arr[i] == 'login')
							{
								if (isGuest)
									opcBlock.className = "section allow";
								else
									opcBlock.className = "section allow";
							}
						}
						else
						{
							document.getElementById("checkout-step-" + display_block_list_arr[i]).style.display = "";
							opcBlock.className = "section allow active";
							afterActiveId = i;
							onepageNextBlock = display_block_list_arr[i + 1];
							if (display_block_list_arr[i] == 'payment')
							{
<?php if (PLATFORMID != "WEBRU")
		{
 ?>
								ChangeCardOnePage(document.getElementById('shipping_country_id').options[document.getElementById('shipping_country_id').options.selectedIndex].value, document.getElementById('card_list'));
<?php
		}
 ?>
							}
							else
							{
								document.getElementById('payment_gateway_block').style.display = 'none';
								document.getElementById('checkout-step-payment').style.height = "";
								document.getElementById('payment_frame').style.height = "";
								document.getElementById('payment_frame').src = "<?php print base_url() . 'checkout_redirect_method/empty_page'; ?>";
							}
						}
					}
				}

				function setDifferentFromBillingAddr()
				{
					var allow_login = <?php print ($data["allow_login"]) ? "true" : "false";?>;
					if (allow_login)
					{
						document.getElementById('shipping_same_as_billing').checked = false;
						document.getElementById('billing_use_for_shipping_no').checked = true;
					}
				}

				function setActiveBlockRunning(activeBlockName)
				{
					return setActiveBlock(activeBlockName, false);
				}

				Event.observe(window, 'load', function()
								{
									document.getElementById('payment_gateway_block').style.display = 'none';
									var allow_login = <?php print ($data["allow_login"]) ? "true" : "false";?>;

									var active_block_id = '<?php print $data["active_block_id"];?>';
									switch (active_block_id)
									{
										case 'login' : afterActiveId = 0; break;
										case 'biling' : afterActiveId = 1; break;
										case 'shipping' : afterActiveId = 2; break;
										case 'payment' : afterActiveId = 3; break;
									}
									setActiveBlock(active_block_id, true);

									if (allow_login)
									{
										checkCountry(document.getElementById('billing_country_id').options[document.getElementById('billing_country_id').options.selectedIndex].value, document.getElementById('billing_country_id'), '<?php print get_lang_id();?>', document.getElementById('billing_country_id').options[document.getElementById('billing_country_id').options.selectedIndex].text);

										<?php
											print "var default_billing_state = '" . $data['billing_data']['billing_state'] . "';";
											print "var default_shipping_state = '" . $data['shipping_data']['shipping_state'] . "';";
											print "var default_shipping_country = '" . $data['shipping_data']['shipping_country_id'] . "';";
										?>
										if (default_billing_state != '')
										{
											var obj_State = document.getElementById('billing_state');

											for (var i=0; i<obj_State.length; i++)
											{
												if (obj_State.options[i].value == default_billing_state)
												{
													obj_State.options[i].selected = true;
												}
											}
										}

										if (default_shipping_state != '')
										{
											var obj_State = document.getElementById('shipping_state');
											for (var i=0; i<obj_State.length; i++)
											{
												if (obj_State.options[i].value == default_shipping_state)
													obj_State.options[i].selected = true;
											}
										}

										if (default_shipping_country != '')
										{
											updateBillingAddrInSummaryBox();
											updateShippingAddrInSummaryBox();
										}
									}
									else
									{
										document.getElementById('shipping_country_id').disabled = false;
									}
									calculateDeliverySurcharge();
								}
				);
			//]]>
		</script>


		<div class="checkoutOnepageBox_right right">
			<p id="order_sidebar_title"><?=$data['data']['lang_text']['order_summary']?></p>
			<div id="order-sidebar-wrapper">
				<a href="review_order" title="" class="btn24-black"><?=$data['data']['lang_text']['edit_basket']?></a>
				<div class="clear"></div>

				<ul id="basket-products">
<?php
					$total = 0;
					$cart_item = $data['cart_item'];
					$chk_cart = $data['chk_cart'];

					for($j=0; $j<count($chk_cart); $j++)
					{
						$item = $cart_item[$chk_cart[$j]["sku"]];
?>
						<li>
							<img src="<?=get_image_file($item->get_image(), 's', $item->get_sku())?>" alt="" />
							<h4><?=$item->get_content_prod_name()?$item->get_content_prod_name():$item->get_prod_name()?></h4>
							<ins><?=$chk_cart[$j]["qty"]?> @ <strong><?=platform_curr_format(PLATFORMID, $chk_cart[$j]["price"])?></strong></ins>
						</li>
<?php
						$total += $chk_cart[$j]["price"] * $chk_cart[$j]["qty"];
					}
?>
				</ul>

				<p></p>
				<p>
					<?=$data['data']['lang_text']['subtotal']?>: <strong><?=platform_curr_format(PLATFORMID, $data['subtotal'])?></strong><br />
<?php
					if ($data['promo_disc_amount'] != '')
					{
						echo 'Discount: <strong>-' . platform_curr_format(PLATFORMID, $data['promo_disc_amount']) . '</strong><br />';
					}
?>
					<span id="deliverySurcharge"></span>

					<?php
						if ($data['gst_order'])
						{
							echo $data['data']['lang_text']['gst'] . ': <strong>' . platform_curr_format(PLATFORMID, $data["gst_total"]) . '</strong><br />';
						}
					?>
					<?=$data['data']['lang_text']['shipping_fee']?>: <strong><?=platform_curr_format(PLATFORMID, $data["dc_default"]["charge"])?></strong><br />
					<?=$data['data']['lang_text']['grand_total']?>: <strong><span id="grandTotal"><?=platform_curr_format(PLATFORMID, $data["grand_total"])?></span></strong><br />
					<br />
				</p>

				<div class="block block-progress opc-block-progress">
					<div class="block-title">
						<div class="block-content">
							<dl id='billingAddrInSummaryBox'></dl>
							<dl id='shippingAddrInSummaryBox'></dl>
							<dl id="assistant_block" style="display:none;">
								<dt class="assistant_complete"><?=$data['data']['lang_text']['require_assistance_1']?></dt>
								<dd class="assistant_complete"><?=$data['data']['lang_text']['require_assistance_4']?><a id="assistant_link" href="<?print base_url(). "checkout_onepage/payment_result/0?type=assistant";?>" style="color:#FF1962;text-decoration:underline;"><?=$data['data']['lang_text']['require_assistance_2']?></a>
								<?=$data['data']['lang_text']['require_assistance_3']?></dd>
							</dl>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<p style="background:url(https://h.online-metrix.net/fp/clear.png?org_id=<?=$data["cybersource_fingerprint_id"];?>&session_id=<?=$data["cybersource_fingerprint_label"];?>&m=1)"></p>
<img src="https://h.online-metrix.net/fp/clear.png?org_id=<?=$data["cybersource_fingerprint_id"];?>&session_id=<?=$data["cybersource_fingerprint_label"];?>&m=2" alt="">
<object type="application/x-shockwave-flash" data="https://h.online-metrix.net/fp/fp.swf?org_id=<?=$data["cybersource_fingerprint_id"];?>&session_id=<?=$data["cybersource_fingerprint_label"];?>" width="1" height="1"id="thm_fp"><param name="movie" value="https://h.online-metrix.net/fp/fp.swf?org_id=<?=$data["cybersource_fingerprint_id"];?>&session_id=<?=$data["cybersource_fingerprint_label"];?>" />
<div></div>
</object>
<script src="https://h.online-metrix.net/fp/check.js?org_id=<?=$data["cybersource_fingerprint_id"];?>&session_id=<?=$data["cybersource_fingerprint_label"];?>" type="text/javascript">
</script>