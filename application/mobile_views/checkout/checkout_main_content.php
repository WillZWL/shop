<script type="text/javascript">
<?php
print "var countryMessages = Array(\"" . $lang_text['country_us_message'] . "\", \"" . $lang_text['country_gb_message'] . "\", \"" . $lang_text['country_other_message'] . "\");\n";
print "var countryChangesConfirmation = Array(\"" . $lang_text['country_changes_confirmation_1'] . "\", \"" . $lang_text['country_changes_confirmation_2'] . "\", \"" . $lang_text['country_changes_confirmation_3'] . "\");\n";
print "var globalStateText = Array(\"" . $lang_text['state'] . "\", \"" . $lang_text['state_province'] . "\", \"" . $lang_text['gb_county'] . "\");\n";
print "var globalPostalCode = \"" . $lang_text['postal_code'] . "\";\n";
print "var globalCountryRestrictionMessage = \"" . $lang_text['country_restriction_message'] . "\";\n";
print "var globalDeliverySurcharge = \"" . $lang_text['delivery_surcharge'] . "\";\n";
print "var globalDeliverySurchargeMessage = \"" . $lang_text['delivery_surcharge_message'] . "\";\n";
print "var globalBillingAddressTitle = \"" . $lang_text['billing_address'] . "\";\n";
print "var globalShippingAddressTitle = \"" . $lang_text['shipping_address'] . "\";\n";
print "var globalActionPath = \"" . (($controller_path == '/checkout_onepage/index') ? "/checkout_onepage" : $controller_path) . "\";\n";
print "var globalQueryString = \"" . ($_SERVER["QUERY_STRING"] ? "?" . $_SERVER["QUERY_STRING"] : "") . "\";\n";
print "var isMobileSite = 1";
?>
//<![CDATA[
	<?php
		if (!is_null($state_list) && !empty($state_list))
		{
			echo "var arr_state_option = new Array();\n";

			$cur_country_id = '';
			foreach($state_list as $state)
			{
				if ($cur_country_id != $state->get_country_id())
				{
					$cur_country_id = $state->get_country_id();
					echo "arr_state_option['" . $cur_country_id . "'] = new Array();\n";
					echo "arr_state_option['" . $cur_country_id . "'][''] = '" . str_replace("'", "\'", $lang_text['select_state']) . "';\n";
				}
				echo "arr_state_option['" . $cur_country_id . "']['" . $state->get_state_id() . "'] = '" . $state->get_name() . "';\n";
			}
		}
		else
		{
			echo "var arr_state_option = {};\n";
		}
	?>
	var chk_cart_cookie = "<?=$chk_cart_cookie?>";
//]]>
var display_block_list = "<?php print $display_block_list;?>";
var currentActiveBlock = "<?php print $current_step;?>";
//var ajax = createAjaxObject();
var payment_type = '';

function setActiveBlockRunning(inputBlockName)
{
	displayBlock(inputBlockName);
}

function displayBlock(blockName)
{
	var parent = jQuery('#opc-' + blockName).parents('div').first();
	jQuery('#opc-' + blockName).parents('.accordion').first().find('div.active').removeClass('active');
	parent.toggleClass('active');
	currentActiveBlock = blockName;
	if (blockName == 'payment')
	{
		document.getElementById('payment_gateway_block').style.display = 'none';
		document.getElementById('checkout-step-payment').style.height = "";
		document.getElementById('payment_frame').style.height = "";
//		document.getElementById('payment_frame').src = "<?php print base_url() . 'checkout_redirect_method/empty_page'; ?>";
		jQuery('#card_list').show();
		ChangeCardOnePage(document.getElementById('shipping_country_id').options[document.getElementById('shipping_country_id').options.selectedIndex].value, document.getElementById('card_list'));
	}
}

function setActiveBlock(blockName)
{
	var display_block_list_arr = display_block_list.split(",");
//find the active index first
	for (i=0;i<display_block_list_arr.length;i++)
	{
		if (display_block_list_arr[i] == blockName)
			activeIndex = i;
		if (currentActiveBlock == display_block_list_arr[i])
			currentActiveBlockIndex = i;
	}

	if (activeIndex > currentActiveBlockIndex)
		return;

	displayBlock(blockName);
}

jQuery(document).ready(function()
{
    jQuery('.checkout-as-a-guest').on('click', function(){
        displayBlock('billing');
    });
	jQuery('#login_continue').on('click', function(){
        displayBlock('billing');
    });
	jQuery('#opc-login').on('click', function(){
		setActiveBlock('login');
	});
	jQuery('#opc-billing').on('click', function(){
		setActiveBlock('billing');
	});
	jQuery('#opc-shipping').on('click', function(){
		setActiveBlock('shipping');
	});

	var display_block_list_arr = display_block_list.split(",");
	jQuery('.opc').hide();
	for (i=0;i<display_block_list_arr.length;i++)
	{
		jQuery('#opc-' + display_block_list_arr[i]).parents('div.opc').show();
		jQuery('#opc-' + display_block_list_arr[i]).first().find('span').html((i + 1));
	}

	var allow_login = <?php print ($allow_login) ? "true" : "false";?>;
	if (allow_login)
	{
		checkCountry(document.getElementById('billing_country_id').options[document.getElementById('billing_country_id').options.selectedIndex].value, document.getElementById('billing_country_id'), '<?php print get_lang_id();?>', document.getElementById('billing_country_id').options[document.getElementById('billing_country_id').options.selectedIndex].text);
	}
	else
	{
		document.getElementById('shipping_country_id').disabled = false;
	}
	calculateDeliverySurcharge();
	displayBlock('<?php print $current_step;?>');
});

function loadPaymentGateway()
{
	var payment_array = get_payment_info();
	if (payment_array)
	{
		card_type = payment_array[0];
		payment_type = payment_array[1];
		card_code = payment_array[2];
	}

	var url = "<?php print base_url(); ?>checkout_redirect_method/process_redirect_checkout?payment_type=" + payment_type + "<?php print ($debug)?'&debug=1':'' ?>";
	var parameters_need = loadPaymentGatewayParameter();
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
//			alert(return_url);

				if (return_url.substring(0, 5) == "ERROR")
				{
					displayLoading(false);
					if ((payment_type == 'moneybookers')  || (payment_type == 'yandex') || (payment_type == 'global_collect') || (payment_type == 'altapay') || (payment_type == 'adyen'))
					{
						top.location.href = return_url.substr(7);
					}
					else
						alert(return_url);
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
						|| (payment_type == 'm_bank_transfer')
						|| (payment_type == 'inpendium_ctpe')
						|| (payment_type == 'global_collect')
						|| (payment_type == 'yandex')
						|| (payment_type == 'altapay')
						|| (payment_type == 'trustly')
						|| ((navigator.userAgent.indexOf("Chrome") == -1) && (navigator.userAgent.indexOf("Safari") > -1) && (payment_type != 'adyen'))
						)
					{
						window.self.location = return_url;
					}
					else if((payment_type == 'adyen'))
					{
						var string = return_url.split('?');
						var adyen_url = string[0];
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
//Trustly, Inpendium, Moneybookers
						document.getElementById('checkout-step-payment').style.height = "750px";
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

function loadingPaymentGateway()
{
	if (commonCheckIfCardSelected('<?=$lang_text['please_select_a_card']?>'))
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

		loadPaymentGateway();
	}
}
</script>
<div class="p10">
    <div class="bordered">
        <div class="p10">
            <h2 class="section-title Rokkitt left"><?=$lang_text['order_summary']?></h2>
            <a href="<?php print base_url();?>review_order" title="" class="grey-gradient right back-to-basket"><?=$lang_text['edit_basket']?></a>
            <br class="clear"/>
        </div>
        <ul class="items top-bordered order-summary-list">
<?php
					$total = 0;
					for($j=0; $j<count($chk_cart); $j++)
					{
						$item = $cart_item[$chk_cart[$j]["sku"]];
?>
						<li>
							<a href="javascript:;" title="" class="img-link">
								<img src="<?=get_image_file($item->get_image(), 's', $item->get_sku())?>" alt=""/>
							</a>
							<div>
								<a href="javascript:;" title="">
									<strong><?=$item->get_content_prod_name()?$item->get_content_prod_name():$item->get_prod_name()?></strong>
								</a>
								<span><?=$chk_cart[$j]["qty"]?> @ <strong><?=platform_curr_format(PLATFORMID, $chk_cart[$j]["price"])?></span>
							</div>
						</li>
<?php
						$total += $chk_cart[$j]["price"] * $chk_cart[$j]["qty"];
					}
?>
        </ul>
        <table class="basket-table border-none checkout-summary">
            <colgroup>
                <col style="width: 60%;">
                <col style="width: 40%;">
            </colgroup>
            <tbody>
                <tr>
                    <td class="tar"><?=$lang_text['subtotal']?>:</td>
                    <td><?=platform_curr_format(PLATFORMID, $subtotal)?><br /></td>
                </tr>
<?php
				if ($promo_disc_amount != '')
				{
?>
					<tr>
						<td class="tar"><?=$lang_text['discount']?>:</td>
						<td><?=platform_curr_format(PLATFORMID, $promo_disc_amount)?></td>
					</tr>
<?php
				}
?>
                <tr style="display:none">
                    <td class="tar"><span id="deliverySurcharge"></span></td>
					<td><span id="deliverySurchargeValue"></span></td>
                </tr>
<?php
				if ($gst_order != '')
				{
?>
					<tr>
						<td class="tar"><?=$lang_text['gst']?>:</td>
						<td><?=platform_curr_format(PLATFORMID, $gst_total)?></td>
					</tr>
<?php
				}
?>
                <tr>
                    <td class="tar"><?=$lang_text['shipping_fee']?>:</td>
                    <td><?=platform_curr_format(PLATFORMID, $dc_default["charge"])?></td>
                </tr>
                <tr>
                    <td class="tar"><?=$lang_text['grand_total']?>:</td>
                    <td><strong class="orange"><span id="grandTotal"><?=platform_curr_format(PLATFORMID, $grand_total)?></span></strong></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="p10">
    <div class="accordion checkout-accordion">
        <!-- every div is accordion element -->
        <div class="opc">
            <ins id="opc-login"><span>1</span> <?=$lang_text['checkout_method']?></ins>
            <div>
                <div class="accordion">
<?php
				if (!$allow_login)
				{
?>
					<div class="white-grey-gradient">
						<strong>
							<span>
								<?=$lang_text['guest_checkout_content']?>
							</span>
							<br>
							<ol>
								<li>
									<label for="guest_email"><?=$lang_text['email']?> <sup>*</sup></label>
									<input type="email" title="<?=$lang_text['email']?>" notEmpty validEmail id="guest_email" name="guest_email" value="" />
								</li>
								<li class="m0">
									<div class="tac">
										<a id="guest_email_continue" href="javascript:;" onclick="if (checkGuestEmail(<?php print ($allow_login) ? "true" : "false";?>, '<?php print $lang_text["warning_put_email"];?>')){setActiveBlockRunning('shipping');}" title="" class="orange-gradient next-step"><?=$lang_text['continue']?></a>
									</div>
								</li>
							</ol>
						</strong>
					</div>
<?php
				}
				else if ($no_login_before)
				{
?>
                    <div class="white-grey-gradient checkout-as-a-guest">
                        <strong>
                            <?=$lang_text['guest_checkout_head']?>
                            <span>
								<?=$lang_text['guest_checkout_content']?>
							</span>
                        </strong>
                    </div>
                    <div class="active white-grey-gradient">
                        <strong>
                            <?=$lang_text['login']?>
                            <span>
<?php
									if (!empty($_SESSION["NOTICE"]))
									{
										print "<ins class='red'>" . $_SESSION["NOTICE"] . "</ins>";
									}
									else
									{
										print $lang_text['already_registered'] . " " . $lang_text['please_login_in'];
									}
?>
							</span>
                        </strong>
                        <div>
                            <form action="<?=base_url()?>login/checkout_login?back=checkout_onepage" id="login_form" name="login_form" method="post" class="site-form" onsubmit="return CheckForm(document.login_form);">
                                <fieldset>
                                    <legend>Login form</legend>
                                    <ol>
                                        <li>
                                            <label for="email"><?=$lang_text['email']?> <sup>*</sup></label>
                                            <input type="email" value="" id="email" name="email" title="<?=$lang_text['email']?>" notEmpty validEmail/>
                                        </li>
                                        <li>
                                            <label for="password"><?=$lang_text['password']?> <sup>*</sup></label>
                                            <input autocomplete="off" type="password" value="" name="password" id="password" title="<?=$lang_text['password']?>" notEmpty />
                                        </li>
                                        <li class="m0">
                                            <input type="submit" class="orange-gradient" value="<?=$lang_text['login']?>" id=""/>
											<a href="<?=base_url()?>forget_password?back=checkout_onepage" class="p10" rel="lyteframe" rev="width: 500px; height:240px; scrolling: auto;padding: 40px;" title=""><?=$lang_text['forgot_password']?></a>
                                        </li>
                                    </ol>
                                </fieldset>
								<input type='hidden' name='posted' id='posted' value='1'>
                            </form>
                        </div>
                    </div>
<?php
				}
				else
				{
?>
					<h3><?=$lang_text['logged_in_before']?> (<?=$lang_text['login_as']?> <?=$client["email"]?>)</h3>
					<div class="tac" style="border:0;">
						<a id="login_continue" href="javascript:;" onclick="" title="" class="orange-gradient next-step"><?=$lang_text['continue']?></a>
					</div>
<?php
				}
?>
                </div>
            </div>
        </div>

        <div class="opc">
            <ins id="opc-billing" class="second-step"><span>2</span> <?=$lang_text['billing_information']?></ins>
            <div>
                <form id="co-billing-form" name="co-billing-form" class="site-form">
                    <fieldset>
                        <legend>Billing Information form</legend>
                        <ol>
                            <li><strong><?=$lang_text['billing_delivery_subject']?></strong></li>
                            <li>
                                <label for=""><?=$lang_text['country']?> <sup>*</sup></label>
<?php
								$options_string = "";
								$selectedCountryName = "";
								foreach($country_list as $country)
								{
									if ($country->get_id() == $selected_country)
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
								<select notEmpty onchange="checkCountry('<?=$selected_country?>', this, '<?php print get_lang_id();?>', '<?php print $selectedCountryName;?>'); calculateDeliverySurcharge();" name="billing_country_id" id="billing_country_id" class="validate-select" title="<?=$lang_text['country']?>">
									<?php print $options_string; ?>
                                </select>
								<span id='shippingMessage' class="orange tips">
								</span>
                            </li>
                            <li>
                                <label for="billing_firstname"><?=$lang_text['first_name']?> <sup>*</sup></label>
                                <input notEmpty type="text" title="<?=$lang_text['first_name']?>" value="<?=empty($client["forename"])?"":$client["forename"];?>" id="billing_firstname" name="billing_firstname"/>
                            </li>
                            <li>
                                <label for="billing_lastname"><?=$lang_text['last_name']?> <sup>*</sup></label>
                                <input notEmpty type="text" title="<?=$lang_text['last_name']?>" value="<?=empty($client["surname"])?"":$client["surname"];?>" id="billing_lastname" name="billing_lastname"/>
                            </li>
                            <li>
                                <label for="billing_company"><?=$lang_text['company']?></label>
                                <input type="text" title="<?=$lang_text['company']?>" value="<?=empty($client["companyname"])?"":$client["companyname"];?>" id="billing_company" name="billing_company"/>
                            </li>
<?php
				if ($no_login_before)
				{
?>
                            <li>
                                <label for="billing_email"><?=$lang_text['email_address']?> <sup>*</sup></label>
                                <input notEmpty validEmail type="email" title="<?=$lang_text['email_address']?>" value="<?=empty($client["email"])?"":$client["email"];?>" id="billing_email" name="billing_email"/>
                            </li>
<?php
				}
?>
<?php
							$pobox_check = '';
							if (!$allow_pobox)
							{
								$pobox_check = 'notMatchRegExI="((p[/) \.]*o[ \.\-]*|p[/) \.]*|post[ \.]*office[ \.]*|post[ ]*|mail[ ]*)box|box[ ]*[\+][ ]*[0-9]{2,4})" warningMsg="' . $lang_text['cannot_po_box'] . '"';
							}
							$defaultAddress = "";
							if (!empty($client["address_1"]))
								$defaultAddress = $client["address_1"];
							if (!empty($client["address_2"]))
								$defaultAddress .= $client["address_2"];
?>
<?php
							if($show_client_id)
							{
?>
                            <li>
                                <label for="client_id_no"><?=$lang_text['client_id_no']?> <a href ="" title="<?=$lang_text['client_id_title']?>">[?]</a></label>
                                <input type="text" title="<?=$lang_text['client_id_no']?>" value="<?=empty($client["client_id_no"])?"":$client["client_id_no"];?>" id="client_id_no" name="client_id_no"/>
                            </li>
<?php
							}
?>
                            <li>
                                <label for=""><?=$lang_text['address']?> <sup>*</sup></label>
                                <input notEmpty title="<?=$lang_text['address']?>" <?=$pobox_check?> type="text" value="<?=$defaultAddress?>" name="address1" id="address1"/>
                            </li>
                            <li>
                                <label for="billing_city"><?=$lang_text['city']?> <sup>*</sup></label>
                                <input notEmpty title="<?=$lang_text['city']?>" type="text" value="<?=empty($client["city"])?"":$client["city"];?>" name="billing_city" id="billing_city"/>
                            </li>
                            <li>
                                <label for="billing_state" id="billing_state_label"><?=$lang_text['state']?></label>
									<div class="input-box">
										<select notEmpty id="billing_state" name="billing_state" title="<?=$lang_text['state']?>">
										</select>
									</div>
                            </li>
                            <li>
                                <label for="billing_post_code_label" id="billing_post_code_label"><?=$lang_text['postal_code']?></label>
								<div class="input-box">
									<input validPostal="billing_country_id" type="text" title="<?=$lang_text['postal_code']?>" value="<?=empty($client["postcode"])?"":$client["postcode"];?>" id="billing_post_code" name="billing_post_code"/>
								</div>
                            </li>
                            <li><strong><?=$lang_text['telephone']?></strong></li>
                            <li class="phone-details">
                                <span>
                                    <label for="billing_telephone1"><?=$lang_text['country_code']?></label>
                                    <input isNumber type="text" title="<?=$lang_text['country_code']?>" value="<?=empty($client["tel_1"])?"":$client["tel_1"];?>" id="billing_telephone1" name="billing_telephone1"/>
                                </span>
                                <span>
                                    <label for="billing_telephone2"><?=$lang_text['area_code']?></label>
                                    <input isNumber type="text" title="<?=$lang_text['area_code']?>" value="<?=empty($client["tel_2"])?"":$client["tel_2"];?>" id="billing_telephone2" name="billing_telephone2"/>
                                </span>
                                <span>
                                    <label for="billing_telephone3"><?=$lang_text['number']?> <sup>*</sup></label>
                                    <input notEmpty isNumber type="text" title="<?=$lang_text['number']?>" value="<?=empty($client["tel_3"])?"":$client["tel_3"];?>" id="billing_telephone3" name="billing_telephone3"/>
                                </span>
                            </li>
                            <li>&nbsp;</li>
                            <li class="top-bordered">&nbsp;</li>
                            <li>
                                <span class="tips"><?=$lang_text['put_a_password']?></span>
                            </li>
<?php
		if ($no_login_before)
		{
?>
                            <li>
                                <label for=""><?=$lang_text['password']?></label>
                                <input autocomplete="off" type="password" value="" id="billing_password" name="billing_password"/>
                            </li>
                            <li>
                                <label for=""><?=$lang_text['confirm_password']?></label>
                                <input autocomplete="off" type="password" value="" id="billing_confirm_password" name="billing_confirm_password"/>
                            </li>
<?php
		}
?>
                            <li>
								<div id="shipping_delivery_option">
									<label class="clicker">
										<input type="radio" name="billing_use_for_shipping" value="1" id="billing_use_for_shipping_yes" checked="checked" />
										<?=$lang_text['ship_to_this']?>
									</label>
									<label class="clicker">
										<input type="radio" name="billing_use_for_shipping" value="0" id="billing_use_for_shipping_no"/>
										<?=$lang_text['ship_to_different']?>
									</label>
								</div>
                            </li>
                            <li class="continue-to-step-3">
									<div class="conditions_agreement">
										<?php
											$conditions_agreement_available_country = 'GB|AU|FI|HK|IE|MY|MT|CH|US|ES|SG|IT|BE|FR|NZ|';
											if (strpos($conditions_agreement_available_country, $selected_country . '|') !== FALSE)
											{
										?>
												<span class="tips">
													<?=$lang_text['conditions_agreement_1']?>
													<span class="hyper_link" onclick="window.open('<?=base_url().'display/view/conditions_of_use'?>', 'TC', 'width=600,height=400,left=50,resizeable=yes,scrollbars=yes,titlebar=yes,resizeable=1,scrollbars=1,titlebar=1');">
														<?=$lang_text['conditions_agreement_2']?>
													</span>
													<?=$lang_text['conditions_agreement_3']?>
												</span>
										<?php
											}
										?>
										<div class="tac">
											<a id="billing_continue" href="javascript:;" onclick="if (CheckForm(document.getElementById('co-billing-form'))) {billingFormContinue(); calculateDeliverySurcharge();}" title="" class="orange-gradient next-step"><?=$lang_text['continue']?></a>
										</div>
									</div>
                            </li>
                        </ol>
                    </fieldset>
                </form>
            </div>
        </div>

        <div class="opc">
            <ins id="opc-shipping" class="third-step"><span>3</span> <?=$lang_text['shipping_information']?></ins>
            <div>
                <form id="co-shipping-form" name="co-shipping-form" class="site-form">
                    <fieldset>
                        <legend>Billing Information form</legend>
                        <ol>
                            <li>
                                <label for="shipping_firstname"><?=$lang_text['first_name']?> <sup>*</sup></label>
                                <input notEmpty type="text" title="<?=$lang_text['first_name']?>" value="" id="shipping_firstname" name="shipping_firstname"/>
                            </li>
                            <li>
                                <label for="shipping_lastname"><?=$lang_text['last_name']?> <sup>*</sup></label>
                                <input notEmpty type="text" title="<?=$lang_text['last_name']?>" value="" id="shipping_lastname" name="shipping_lastname"/>
                            </li>
                            <li>
                                <label for="shipping_company"><?=$lang_text['company']?></label>
                                <input type="text" value="" title="<?=$lang_text['company']?>" id="shipping_company" name="shipping_company"/>
                            </li>
                            <li>
                                <label for="shipping_address1"><?=$lang_text['address']?> <sup>*</sup></label>
                                <input notEmpty <?=$pobox_check?> type="text" title="<?=$lang_text['address']?>" value="" id="shipping_address1" name="shipping_address1"/>
                            </li>
                            <li>
                                <label for="shipping_city"><?=$lang_text['city']?> <sup>*</sup></label>
                                <input notEmpty type="text" value="" id="shipping_city" title="<?=$lang_text['city']?>" name="shipping_city"/>
                            </li>
<?php if ($allow_login)
		{
?>
                            <li>
                                <label for="shipping_state" id="shipping_state_label"><?=$lang_text['state']?></label>
									<div class="input-box">
										<select notEmpty id="shipping_state" title="<?=$lang_text['state']?>" name="shipping_state">
										</select>
									</div>
                            </li>
<?php
		}
?>
                            <li>
                                <label for="shipping_post_code" id="shipping_post_code_label"><?=$lang_text['postal_code']?></label>
                                <input validPostal="shipping_country_id" type="text" title="<?=$lang_text['postal_code']?>" value="" id="shipping_post_code" onchange="setDifferentFromBillingAddr(); calculateDeliverySurcharge();"/>
                            </li>
                            <li>
                                <label for="shipping_country_id"><?=$lang_text['country']?> <sup>*</sup></label>
                                <select notEmpty title="<?=$lang_text['country']?>" name="shipping_country_id" id="shipping_country_id" disabled onchange="setDifferentFromBillingAddr();">
<?php
								if ($allow_login)
								{
									foreach($country_list as $country)
									{
										print "<option value='" . $country->get_id() . "'>" . $country->get_name() . "</option>";
									}
								}
								else
								{
									foreach($country_list as $country)
									{
										if ($selected_country == $country->get_id())
											print "<option value='" . $country->get_id() . "'>" . $country->get_name() . "</option>";
									}
								}
?>
                                </select>
                            </li>
                            <li><strong><?=$lang_text['telephone']?></strong></li>
                            <li class="phone-details">
                                <span>
                                    <label for="shipping_telephone1"><?=$lang_text['country_code']?></label>
                                    <input title="<?=$lang_text['country_code']?>" type="text" value="" id="shipping_telephone1" name="shipping_telephone1"/>
                                </span>
                                <span>
                                    <label for="shipping_telephone2"><?=$lang_text['area_code']?></label>
                                    <input title="<?=$lang_text['area_code']?>" type="text" value="" id="shipping_telephone2" name="shipping_telephone2"/>
                                </span>
                                <span>
                                    <label for="shipping_telephone3"><?=$lang_text['number']?> <sup>*</sup></label>
                                    <input title="<?=$lang_text['number']?>" type="text" value="" id="shipping_telephone3" name="shipping_telephone3"/>
                                </span>
                            </li>
<?php if ($allow_login)
		{
?>
                            <li>
                                <label class="clicker">
                                    <input onclick="useBillingAddress(this.checked);" type="checkbox" name="shipping_same_as_billing" value="" id="shipping_same_as_billing"/>
                                    <?=$lang_text['use_billing_address']?>
                                </label>
                            </li>
<?php
		}
?>
                        </ol>
                    </fieldset>
                </form>
				<div class="conditions_agreement">
					<?php
						if ($selected_country == 'PH')
						{
					?>
							<span class="tips">
								<?=$lang_text['conditions_agreement_1']?>
								<span class="hyper_link" onclick="window.open('<?=base_url().'display/view/conditions_of_use'?>', 'TC', 'width=600,height=400,left=50,resizeable=yes,scrollbars=yes,titlebar=yes,resizeable=1,scrollbars=1,titlebar=1');">
									<?=$lang_text['conditions_agreement_2']?>
								</span>
								<?=$lang_text['conditions_agreement_3']?>
							</span>
					<?php
						}
					?>
					<div class="tac">
						<a href="javascript:;" onclick="if (CheckForm(document.getElementById('co-shipping-form'))) {setActiveBlockRunning('payment');}" title="" class="orange-gradient next-step"><?=$lang_text['continue']?></a>
					</div>
				</div>
            </div>
        </div>

        <div class="opc">
            <ins id="opc-payment"><span>4</span> <?=$lang_text['payment_information']?></ins>
            <div id="checkout-step-payment">
						<div id='card_form'>
							<div id='select_a_card'><b><?=$lang_text['please_a_payment_options']?></b><br><br></div>
							<form name="card_list_form" id="card_list_form">
								<div id='card_list' name='card_list'></div>
								<div style="clear:both;"></div>
								<div id='trustly_container' style="display:none;">
									<div id='trustly_div'></div>
									<div id='trustly_div_text'><a id='fancy_trustly' style="text-decoration:underline;" class='fancybox iframe' title='https://trustly.com/whatistrustly' href='https://trustly.com/whatistrustly'><?=$lang_text['trustly_message_1']?><span class='frame'></span></a><br><b><?=$lang_text['trustly_message_2']?></b></div>
									<div style="clear:both;"></div>
									<div id='trustly_point'>
										<ul>
											<li><?=$lang_text['trustly_message_point_1']?></li>
											<li><?=$lang_text['trustly_message_point_2']?></li>
										</ul>
									</div>
								</div>
							</form>
						</div>
						<div style="clear:both;"></div>
						<div class="tac" style="border:0;" id="payment-buttons-container">
							<a id="payment_continue" href="javascript:;" onclick="loadingPaymentGateway()" title="" class="orange-gradient next-step"><?=$lang_text['continue']?></a>
						</div>
						<input type="hidden" name="p_enc" id='p_enc' value="<?=$p_enc;?>">
						<input type="hidden" name="cybersource_fingerprint" id='cybersource_fingerprint' value="<?=$cybersource_fingerprint;?>">
						<div id='loading_display' style="text-align:center;display:none;position:relative;"><img src='/images/loading.gif' border='0'></div>
						<div id='payment_gateway_block'>
							<iframe id="payment_frame" name="payment_frame" style="position:relative;width:100%;height:100%" src="about:blank" frameborder='0' scrolling='auto'>
							</iframe>
						</div>
            </div>
        </div>
		<div id='card_explanation' style="padding-top:10px;display:none;">
			<?=$lang_text['card_message']?>
			<br>
			<span style="display:block;padding-top:10px;text-align:center;">
				<img src=<?php print base_cdn_url() . "resources/images/" . get_lang_id() . "_cards.jpg" ?> border='0'>
			</span>
		</div>
    </div>
</div>
<p style="background:url(https://h.online-metrix.net/fp/clear.png?org_id=<?=$cybersource_fingerprint_id;?>&session_id=<?=$cybersource_fingerprint_label;?>&m=1)"></p>
<img src="https://h.online-metrix.net/fp/clear.png?org_id=<?=$cybersource_fingerprint_id;?>&session_id=<?=$cybersource_fingerprint_label;?>&m=2" alt="">
<object type="application/x-shockwave-flash" data="https://h.online-metrix.net/fp/fp.swf?org_id=<?=$cybersource_fingerprint_id;?>&session_id=<?=$cybersource_fingerprint_label;?>" width="1" height="1" id="thm_fp"><param name="movie" value="https://h.online-metrix.net/fp/fp.swf?org_id=<?=$cybersource_fingerprint_id;?>&session_id=<?=$cybersource_fingerprint_label;?>" />
<div></div>
</object>
<script src="https://h.online-metrix.net/fp/check.js?org_id=<?=$cybersource_fingerprint_id;?>&session_id=<?=$cybersource_fingerprint_label;?>" type="text/javascript">
</script>