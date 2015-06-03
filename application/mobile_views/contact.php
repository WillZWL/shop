<script type="text/javascript" src="<?=$base_url?>contact/generate_webform_js/kayako/"></script>
<script src="/resources/js/contactus.js"></script>

<!-- enquiry forms -->  
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
			<div>
				<p><?=$lang_text['iframe_message2']?><b><?=$lang_text['iframe_message3']?></b></p>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
				<p class="orange"><?=$lang_text['iframe_message_general_info']?></p>
			</div>

			<div>
				<span class="strong" style="padding-bottom:5px;display:inline-block;width:100px"><?=$lang_text['iframe_field_name']?>:</span>
				<span id="enquiry_result_name"></span>
			</div>

			<div>
				<span class="strong" style="padding-bottom:5px;display:inline-block;width:100px"><?=$lang_text['iframe_field_email_only']?>:</span>
				<span id="enquiry_result_email"></span>
			</div>

			<div>
				<span class="strong" style="padding-bottom:5px;display:inline-block;width:100px"><?=$lang_text['iframe_field_enquiry_type']?>:</span>
				<span id="enquiry_result_enquiry_type"></span>
			</div>

			<div>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
			</div>

			<div>
				<p class="orange">
					<span class="strong" style="display:inline-block;width:60px"><?=$lang_text['iframe_field_subject']?>:</span>
					<span id="enquiry_result_subject"></span>
				</p>
			</div>

			<div id="enquiry_result_contents"></div>

			<div>
				<button class="btn" onclick="HideEnquiryResult(); scroll(0,0); return false;" style="cursor:pointer;"><?=$lang_text['iframe_close']?></button>
			</div>
		</div>
	</div>

	<div id="enquiry_box" class="text silver_box static_page kayako_enquiry_box">
		<div id="enquiry_box_title" class="rokkit_24"></div>

		<div>
			<p>&nbsp;</p>
		</div>

		<div>
			<label><span class="red">*</span> <?=$lang_text['iframe_message_denotes_a_required_field']?></label>
		</div>

		<form name="fm_enquiry" class="form-holder" method="post" onsubmit="if (CheckForm(this)) {SubmitEnquiry(this);} return false;" target="enquiry_result_iframe" enctype="multipart/form-data">
			<input type="hidden" name="enquiry_box" value="kayako">
			<input type="hidden" id="enquiry_type" name="enquiry_type" value="">
			<input type="hidden" name="subject" value="">

			<div class="field_row">
				<span class="field_title"><label><?=$lang_text['iframe_field_name']?><span class="red">*</span></label></span>

				<fieldset class="medium">
					<input type="text" notempty islatin dname="Name" id="enquiry_box_fullname" name="fullname">
				</fieldset>
			</div>

			<div class="field_row">
				<span class="field_title"><label><?=$lang_text['iframe_field_email']?><span class="red">*</span></label></span>

				<fieldset class="medium">
					<input type="text" notempty validEmail dname="Email Address" id="enquiry_box_email" name="email">
				</fieldset>
			</div>

			<div class="field_row">
				<span class="field_title"><label><?=$lang_text['iframe_field_phone']?><span class="red" id="enquiry_box_phone_no_required_field">*</span></label></span>

				<fieldset class="medium">
					<input id="enquiry_box_phone_no" type="text" notEmpty isNumber dname="Phone Number" name="phone">
				</fieldset>
			</div>

			<div id="enquiry_box_tr_order_number" class="field_row">
				<span class="field_title"><label><?=$lang_text['iframe_field_order_no']?><span class="red">*</span></label></span>

				<fieldset class="medium">
					<input id="enquiry_box_order_number" type="text" notEmpty dname="Order Number" name="order_number">
				</fieldset>
			</div>

			<div class="field_row">
				<span class="field_title"><label><?=$lang_text['iframe_field_question']?><span class="red">*</span></label></span>

				<select id="enquiry_box_question" notempty dname="Question" name="question">
				</select>
			</div>

			<div id="enquiry_box_tr_item_country" class="field_row">
				<span class="field_title"><label><?=$lang_text['iframe_field_item_country']?></label></span>

				<fieldset class="medium">
					<input id="enquiry_box_item_country" type="text" dname="Item/Country" name="item_country">
				</fieldset>
			</div>

			<div class="field_row">
				<span class="field_title"><label><?=$lang_text['iframe_field_message']?><span class="red">*</span></label></span>

				<fieldset class="textarea">
					<textarea notempty dname="Message" name="contents"></textarea>
				</fieldset>
			</div>

			<div id="enquiry_box_tr_attachment" class="field_row">
				<span class="field_title"><label><?=$lang_text['iframe_field_attachment']?> (5MB <?=$lang_text['iframe_field_or_less']?>)</label></span>

				<fieldset>
					<input type="file" id="enquiry_box_attachment1" name="attachment1"><br/>
					<input type="file" id="enquiry_box_attachment2" name="attachment2">
				</fieldset>
			</div>

			<div class="field_row">
				<button class="btn" style="cursor:pointer;"><?=$lang_text['iframe_field_submit']?></button>
				<a href="#" onclick="HideEnquiryBox(); scroll(0,0); return false;"><?=$lang_text['iframe_field_return_to_web']?></a>

				<span style="float:right;text-align:right">
					<label style="display:inline"><?=$lang_text['iframe_field_change_enquiry']?>:</label>

					<select id="enquiry_type_selection" style="display:inline">
					</select>

					<a href="#" onclick="ChangeEnquiryType(); return false;" style="float:none"><?=$lang_text['iframe_go']?></a>
				</span>
			</div>
		</form>

		<script type="text/javascript">
			var availableEnquiry = new Array();
			availableEnquiry['Client Support'] = 1;
			availableEnquiry['Pre-Sales'] = 1;
			availableEnquiry['Returns'] = 1;
			SetAvailableEnquiry(availableEnquiry);
		</script>
	</div>
</div>
<!-- end enquire forms -->

<div class="p10 clear contacts-page">
	<?php
		if ($phone_contact)
		{
	?>
			<h2 class="section-title Rokkitt" style="padding-top:10px; border:1px solid #d0d0d0; box-shadow:3px 3px 3px #eee;" onclick="triggerContactInfo('phone');"><img src="<?=$cdn_url?>resources/mobile/images/byPhone.jpg" width="35" alt=""/><?=$lang_text['welcome_head']?></h2>
			<div id="contact_phone_info" class="general-text p10 bordered mb10" style="display:none">
				<p><?=$lang_text['contact_phone']?></p>

				<p class="clear">
					<img src="<?=$cdn_url?>resources/mobile/images/flag-HK.png" alt="" class="left pt10"/>
					&nbsp;<?=$lang_text['hk_name']?> - <?=$lang_text['hk_tel']?><br \>
					&nbsp;<?=$lang_text['hk_opening_hour']?>
				</p>    
				<p class="clear">
					<img src="<?=$cdn_url?>resources/mobile/images/flag-FI.png" alt="" class="left pt10"/>
					&nbsp;<?=$lang_text['fi_name']?> - <?=$lang_text['fi_tel']?><br \>
					&nbsp;<?=$lang_text['fi_opening_hour']?>
				</p>
				<p class="clear">
					<img src="<?=$cdn_url?>resources/mobile/images/flag-Ireland.png" alt="" class="left pt10"/>
					&nbsp;<?=$lang_text['ie_name']?> - <?=$lang_text['ie_tel']?><br \>
					&nbsp;<?=$lang_text['ie_opening_hour']?>
				</p>        
				<p class="clear">
					<img src="<?=$cdn_url?>resources/mobile/images/flag-au.png" alt="" class="left pt10"/>
					&nbsp;<?=$lang_text['au_name']?> - <?=$lang_text['au_tel']?><br \>
					&nbsp;<?=$lang_text['au_opening_hour']?>
				</p>  
				<p class="clear">
					<img src="<?=$cdn_url?>resources/mobile/images/flag-us.png" alt="" class="left pt10"/>
					&nbsp;<?=$lang_text['us_name']?> - <?=$lang_text['us_tel']?><br \>
					&nbsp;<?=$lang_text['us_opening_hour']?>
				</p>  
				<p class="clear">
					<img src="<?=$cdn_url?>resources/mobile/images/flag-uk.png" alt="" class="left pt10"/>
					&nbsp;<?=$lang_text['uk_name']?> - <?=$lang_text['uk_tel']?><br \>
					&nbsp;<?=$lang_text['uk_opening_hour']?>
				</p>        
			</div>
	<?php
		}
	?>

    <h2 class="section-title Rokkitt" style="padding-top:10px; border:1px solid #d0d0d0; box-shadow:3px 3px 3px #eee;" onclick="triggerContactInfo('email');"><img src="<?=$cdn_url?>resources/mobile/images/byMail.jpg" width="35" alt=""/><?=$lang_text['to_contact_us_head']?></h2>
    <div id="contact_email_info" class="general-text p10 bordered mb10" style="display:none">
        <p><?=$lang_text['contact_email']?></p>

        <p><?=$lang_text['general_sales']?><a href="#enquiry_box_anchor" onclick="ShowEnquiryBox('Client Support');"><?=$lang_text['here']?></a>.</p>

        <p><?=$lang_text['pre_sales']?><a href="#enquiry_box_anchor" onclick="ShowEnquiryBox('Pre-Sales');"><?=$lang_text['here']?></a>.</p>

        <p><?=$lang_text['rma']?><a href="#enquiry_box_anchor" onclick="ShowEnquiryBox('Returns');"><?=$lang_text['here']?></a>.</p>   
    </div>   
    
    <h2 class="section-title Rokkitt" style="padding-top:10px; border:1px solid #d0d0d0; box-shadow:3px 3px 3px #eee;" onclick="triggerContactInfo('return');"><img src="<?=$cdn_url?>resources/mobile/images/Returns.jpg" width="35" alt=""/><?=$lang_text['return_header']?></h2>
    <div id="contact_return_info" class="general-text p10 bordered" style="display:none">
        <p><?=$lang_text['return_content_1']?><a href="#enquiry_box_anchor" onclick="ShowEnquiryBox('Returns');"><?=$lang_text['clicking_here']?></a><?=$lang_text['return_content_2']?><a href="<?=$base_url?>login?back=myaccount"><?=$lang_text['my_account']?></a><?=$lang_text['return_content_3']?></p>
    </div>        
</div>

<script language="javascript">
	function triggerContactInfo(type)
	{
		if (type == '')
			return false;

		objDiv = document.getElementById('contact_' + type + '_info');
		if (objDiv.style.display == 'none')
			objDiv.style.display = 'block';
		else
			objDiv.style.display = 'none';
	}
</script>