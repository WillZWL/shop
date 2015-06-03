<script type="text/javascript" src="<?=$base_url?>contact/generate_webform_js/kayako"></script> 

<div id="contact_us_enquiry">
	<a name="enquiry_box_anchor"></a>
	<div id="cover" class="full_screen_cover"></div>

	<div id="enquiry_processing" class="text silver_box static_page kayako_enquiry_box">
		<div class="form-holder">
			<p class="rokkit_12"><?=$lang_text['processing']?>...</p>
		</div>
	</div>

	<iframe id="enquiry_result_iframe" name="enquiry_result_iframe" src="#" style="display:none;"></iframe>
	<div id="enquiry_result" class="text silver_box static_page kayako_enquiry_box">
		<div class="orange rokkit_24"><?=$lang_text['iframe_message1']?></div><br>
		<div class="form-holder">
			<p><?=$lang_text['iframe_message2']?><br><br><b><?=$lang_text['iframe_message3']?></b></p>
	
			<br><br>

			<p class="orange"><?=$lang_text['iframe_message_general_info']?></p>
			<span class="strong" style="padding-bottom:5px;display:inline-block;width:100px"><?=$lang_text['iframe_field_name']?>:</span><span id="enquiry_result_name"></span><br>
			<span class="strong" style="padding-bottom:5px;display:inline-block;width:100px"><?=$lang_text['iframe_field_email_only']?>:</span><span id="enquiry_result_email"></span><br>
			<span class="strong" style="padding-bottom:5px;display:inline-block;width:100px"><?=$lang_text['iframe_field_enquiry_type']?>:</span><span id="enquiry_result_enquiry_type"></span><br><br><br>

			<p class="orange"><span class="strong" style="display:inline-block;width:60px"><?=$lang_text['iframe_field_subject']?>:</span><span id="enquiry_result_subject"></span></p>
			<p id="enquiry_result_contents"></p>
			<button class="btn" onclick="HideEnquiryResult(); scroll(0,0); return false;" style="cursor:pointer;"><?=$lang_text['iframe_close']?></button>
		</div>
	</div>

	<div id="enquiry_box" class="text silver_box static_page kayako_enquiry_box">
		<div id="enquiry_box_title" class="rokkit_24"></div><br><br>

		<label><span class="red">*</span> <?=$lang_text['iframe_message_denotes_a_required_field']?></label><br>
		<form name="fm_enquiry" class="form-holder" method="post" onsubmit="if (CheckForm(this)) {SubmitEnquiry(this);} return false;" target="enquiry_result_iframe" enctype="multipart/form-data">
			<input type="hidden" name="enquiry_box" value="kayako">
			<input type="hidden" id="enquiry_type" name="enquiry_type" value="">
			<input type="hidden" name="subject" value="">

			<table>
				<tr>
					<td><label><?=$lang_text['iframe_field_name']?><span class="red">*</span></label></td>
					<td colspan="2">
						<fieldset class="medium">
							<input type="text" notempty islatin dname="Name" name="fullname">
						</fieldset>
					</td>
				</tr>

				<tr>
					<td><label><?=$lang_text['iframe_field_email']?><span class="red">*</span></label></td>
					<td colspan="2">
						<fieldset class="medium">
							<input type="text" notempty validEmail dname="Email Address" id="enquiry_box_email" name="email">
						</fieldset>
					</td>
				</tr>

				<tr>
					<td><label><?=$lang_text['iframe_field_phone']?><span class="red" id="enquiry_box_phone_no_required_field">*</span></label></td>
					<td colspan="2">
						<fieldset class="medium">
							<input id="enquiry_box_phone_no" type="text" notEmpty isNumber dname="Phone Number" name="phone">
						</fieldset>
					</td>
				</tr>

				<tr id="enquiry_box_tr_order_number">
					<td><label><?=$lang_text['iframe_field_order_no']?><span class="red">*</span></label></td>
					<td colspan="2">
						<fieldset class="medium">
							<input id="enquiry_box_order_number" type="text" notEmpty dname="Order Number" name="order_number">
						</fieldset>
					</td>
				</tr>

				<tr>
					<td><label><?=$lang_text['iframe_field_question']?><span class="red">*</span></label></td>
					<td colspan="2">
						<select id="enquiry_box_question" notempty dname="Question" name="question">
						</select>
					</td>
				</tr>

				<tr id="enquiry_box_tr_item_country">
					<td><label><?=$lang_text['iframe_field_item_country']?></label></td>
					<td colspan="2">
						<fieldset class="medium">
							<input id="enquiry_box_item_country" type="text" dname="Item/Country" name="item_country">
						</fieldset>
					</td>
				</tr>

				<tr>
					<td><label><?=$lang_text['iframe_field_message']?><span class="red">*</span></label></td>
					<td colspan="2">
						<fieldset class="textarea">
							<textarea notempty dname="Message" name="contents"></textarea>
						</fieldset>
					</td>
				</tr>

				<tr id="enquiry_box_tr_attachment">
					<td><label><?=$lang_text['iframe_field_attachment']?> (5MB <?=$lang_text['iframe_field_or_less']?>)</label></td>
					<td colspan="2">
						<input type="file" id="enquiry_box_attachment1" name="attachment1"><br>
						<input type="file" id="enquiry_box_attachment2" name="attachment2">
					</td>
				</tr>

				<tr style="vertical-align:bottom">
					<td><button class="btn" style="cursor:pointer;"><?=$lang_text['iframe_field_submit']?></button></td>
					<td><a href="#" onclick="HideEnquiryBox(); scroll(0,0); return false;"><?=$lang_text['iframe_field_return_to_web']?></a></td>
					<td style="text-align:right">
						<select id="enquiry_type_selection" style="display:none">
						</select>
					</td>
				</tr>
			</table>
		</form>

		<script type="text/javascript">
			var availableEnquiry = new Array();
			availableEnquiry['Bulk'] = 1;
			SetAvailableEnquiry(availableEnquiry);
		</script>
	</div>
</div>

<div class="p10">
    <div class="filters">
        <h2 class="section-title Rokkitt"><?php print $lang_text["shopping_cart"];?></h2>
        <div class="p10">
            <!-- Every table is single product -->
<?php 
	foreach($cart as $key => $item)
	{
?>
            <table border="0" class="basket-table">
                <thead>
                    <tr>
                        <th colspan="3" class="white-grey-gradient">
                            <div>
								<strong><?php print $item["prod_name"]?></strong>
								<a href="<?php print $item["remove_url"] ?>" class="remove-product" title="Remove product">&nbsp;</a>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="tac">
                    <tr>
                        <td><?php print $lang_text["unit_price"];?>:</td>
                        <td><?php print $lang_text["quantity"];?>:</td>
                        <td><?php print $lang_text["subtotal"];?>:</td>
                    </tr>
                    <tr>
                        <td><strong class="orange"><?php print $item["price"];?></strong></td>
                        <td>
							<div style="width:80px;margin:auto;">
								<div style="padding-left:15px;float:left;">
									<input type="text" size="2" maxlength="2" value="<?php print $item["qty"];?>" name="" id=""/>
								</div>
								<div style="padding-left:10px;float:left;">
									<a href="<?php print $item["increase_url"] ?>" class="increase"></a>
<?php
							if ($item["qty"] > 1)
							{
?>
									<a href="<?php print $item["decrease_url"] ?>" class="decrease"></a>
<?php
							}
?>									
								</div>
								<div style="clear:both;">
							</div>
						</td>
                        <td><strong class="orange"><?php print $item["sub_total"];?></strong></td>
                    </tr>                
                </tbody>            
            </table>
<?php
	}
?>
        </div>    
    </div>
</div>
<div class="p10">
    <div class="bordered basket-bottom">
        <div class="p10">
            <h2 class="section-title Rokkitt">Discount Code</h2>
            <form name="fm_promo" action="" class="form-holder" method="post" onSubmit="return CheckForm(this);">
                <fieldset>
                    <legend>Discount coupon form</legend>
                    <label for=""><?php print $lang_text["enter_coupon"]; ?></label>
                    <input type="text" autocorrect="off" value="<?php print (($promo["valid"]) ? $promo["promotion_code_obj"]->get_code() : ""); ?>" name="promotion_code" id="promotion_code" dname="<?php print $lang_text["discount_code"]; ?>" notEmpty/>
<?php if ($promo["valid"])
		{
?>
					<div style="color:#00DA27"><?php print $lang_text["promotion_code_valid"]; ?></div>
<?php
		}
		elseif ((isset($promo["valid"]) && !$promo["valid"]) || (isset($promo["error"]) && $promo["error"]))
		{
?>
			<div style="color:#FF0000"><?php print $lang_text["promotion_code_invalid"]; ?></div>
<?php
		}
?>
                    <input type="submit" name="" value="<?php print $lang_text["apply_coupon"]; ?>" class="dark-grey-gradient"/>
                </fieldset>
            </form> 
        </div>
    </div>
    <table class="basket-table">
        <colgroup>
            <col style="width: 60%;">
            <col style="width: 40%;">
        </colgroup>
        <tbody>
            <tr>
                <td class="tar"><?php print $lang_text["substotal_cap_first"];?>:</td>
                <td><?php print $item_amount;?></td>
            </tr>
<?php
		if ($gst_order)
		{
?>
            <tr>
                <td class="tar"><?php print $lang_text["gst"];?>:</td>
                <td><?php print $gst_total;?></td>
            </tr>
<?php
		}
?>
            <tr>
                <td class="tar"><?php print $lang_text["shipping_cap_first"];?>:</td>
                <td><?php print $delivery_charge;?></td>
            </tr>
<?php
		if (isset($promo["display_disc_amount"]))
		{
?>
            <tr>
                <td class="tar"><?php print $lang_text["promotion_discount_amount"];?>:</td>
                <td><span style="color:#FF0000;"><?php print $promo["display_disc_amount"];?></span></td>
            </tr>
<?php
		}
?>
            <tr>
                <td class="tar"><?php print $lang_text["total"];?>:</td>
                <td><strong class="orange"><?php print $total;?></strong></td>
            </tr>

<?php
		if ($need_gst_display)
		{
?>
			<tr>
				<td style="border:0px"></td>
				<td style="border:0px">
					<div class="tac">
						<a href="javascript:;" class="toggle-hidden-tip"><?=$lang_text['gst_banner']?></a>
						<p class="hidden-tip">
							<img src="<?=$cdn_url?>resources/images/GST_i_m.png">
							<span><?=$lang_text['gst_policy_1']?></span><br />
							<span><?=$lang_text['gst_policy_2']?></span><br />
							<span><?=$lang_text['gst_policy_3']?></span><br />
							<span><?=$lang_text['gst_policy_4']?></span>
						</p>
					</div>
				</td>
			</tr>
<?php
		}
?>
        </tbody>  
    </table>
<?php
if ($show_battery_message_w_amount)
{
?>
<div class="p10">
    <span style="color:#FF0000;font-size:14px;">
        <?php print $lang_text['battery_message_1'] . " " . platform_curr_format(PLATFORMID, $show_battery_message_w_amount) . " " . $lang_text['battery_message_2'];?>
    </span
</div>
<?php
}
?>
    <div class="tac basket-action-buttons">
		<a href="<?php print base_url(); ?>" title="" class="dark-grey-gradient button-margin-top"><?php print $lang_text["continue_shopping"];?></a>
<?php
if (!$show_battery_message_w_amount)
{
?>
		<a href="<?php print base_url() . "checkout_onepage"; ?>" title="" class="orange-gradient button-margin-top"><?php print $lang_text["continue_checkout"];?></a>
<?php
}
?>
    </div>

<?php
	if ($allow_bulk_sales)
	{
?>
		<script type="text/javascript">
			jQuery(document).ready(function() 
			{
				jQuery.fancybox
				(
					jQuery("#bulk_sales_popup").html(),
				{
					'autoDimensions'	: false,
					'width'        		: 600,
					'height'       		: 'auto',
					'transitionIn'		: 'none',
					'transitionOut'		: 'none',
					'hideOnContentClick': true,
					'closeClick'		: true
				}
				);
			});
		</script>

		<div id="bulk_sales_popup" style="display:none;">
			<br><?=$lang_text['if_interest']?><a href="#enquiry_box_anchor" onclick="ShowEnquiryBox('Bulk');document.getElementById('enquiry_box_question').value = 135; parent.jQuery.fancybox.close();" style="color:#F60;"><u><?=$lang_text['here']?></a></u>.
			<br><br>
			<div class="text silver_box static_page">
				<b><?=$lang_text['we_offer']?></b><br>
				<b>
					<ul style="color:#F60;line-height:2em;">
						<li><?=$lang_text['offer_1']?></li>
						<li><?=$lang_text['offer_2']?></li>
						<li><?=$lang_text['offer_3']?></li>
						<li><?=$lang_text['offer_4']?></li>
					</ul>
				</b>
				<br>
			</div>
		</div>
<?php
	}
?>
</div>