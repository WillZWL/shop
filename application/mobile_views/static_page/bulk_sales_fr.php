<script type="text/javascript" src="[base_url]contact/generate_webform_js/kayako"></script>

<div id="content">
	<h5 class="side_title">[lang_text.bulk_sales_heading;noerr;htmlconv=no;]</h5>

	<div id="contact_us">
		<a name="enquiry_box_anchor"></a>
		<div id="cover" class="full_screen_cover"></div>

		<div id="enquiry_processing" class="text silver_box static_page kayako_enquiry_box">
			<div class="form-holder">
				<p class="rokkit_12">[lang_text.processing;noerr;htmlconv=no;]...</p>
			</div>
		</div>

		<iframe id="enquiry_result_iframe" name="enquiry_result_iframe" src="#" style="display:none;"></iframe>
		<div id="enquiry_result" class="text silver_box static_page kayako_enquiry_box">
			<div class="orange rokkit_24">[lang_text.iframe_message1;noerr;htmlconv=no;]</div><br>
			<div class="form-holder">
				<p>[lang_text.iframe_message2;noerr;htmlconv=no;]<br><br><b>[lang_text.iframe_message3;noerr;htmlconv=no;]</b></p>
		
				<br><br>

				<p class="orange">[lang_text.iframe_message_general_info;noerr;htmlconv=no;]</p>
				<span class="strong" style="padding-bottom:5px;display:inline-block;width:100px">[lang_text.iframe_field_name;noerr;htmlconv=no;]:</span><span id="enquiry_result_name"></span><br>
				<span class="strong" style="padding-bottom:5px;display:inline-block;width:100px">[lang_text.iframe_field_email_only;noerr;htmlconv=no;]:</span><span id="enquiry_result_email"></span><br>
				<span class="strong" style="padding-bottom:5px;display:inline-block;width:100px">[lang_text.iframe_field_enquiry_type;noerr;htmlconv=no;]:</span><span id="enquiry_result_enquiry_type"></span><br><br><br>

				<p class="orange"><span class="strong" style="display:inline-block;width:60px">[lang_text.iframe_field_subject;noerr;htmlconv=no;]:</span><span id="enquiry_result_subject"></span></p>
				<p id="enquiry_result_contents"></p>
				<button class="btn" onclick="HideEnquiryResult(); scroll(0,0); return false;" style="cursor:pointer;">[lang_text.iframe_close;noerr;htmlconv=no;]</button>
			</div>
		</div>

		<div id="enquiry_box" class="text silver_box static_page kayako_enquiry_box">
			<div id="enquiry_box_title" class="rokkit_24"></div><br><br>

			<label><span class="red">*</span> [lang_text.iframe_message_denotes_a_required_field;noerr;htmlconv=no;]</label><br>
			<form name="fm_enquiry" class="form-holder" method="post" onsubmit="if (CheckForm(this)) {SubmitEnquiry(this);} return false;" target="enquiry_result_iframe" enctype="multipart/form-data">
				<input type="hidden" name="enquiry_box" value="kayako">
				<input type="hidden" id="enquiry_type" name="enquiry_type" value="">
				<input type="hidden" name="subject" value="">

				<table>
					<tr>
						<td><label>[lang_text.iframe_field_name;noerr;htmlconv=no;]<span class="red">*</span></label></td>
						<td colspan="2">
							<fieldset class="medium">
								<input type="text" notempty islatin dname="Name" name="fullname">
							</fieldset>
						</td>
					</tr>

					<tr>
						<td><label>[lang_text.iframe_field_email;noerr;htmlconv=no;]<span class="red">*</span></label></td>
						<td colspan="2">
							<fieldset class="medium">
								<input type="text" notempty validEmail dname="Email Address" id="enquiry_box_email" name="email">
							</fieldset>
						</td>
					</tr>

					<tr>
						<td><label>[lang_text.iframe_field_phone;noerr;htmlconv=no;]<span class="red" id="enquiry_box_phone_no_required_field">*</span></label></td>
						<td colspan="2">
							<fieldset class="medium">
								<input id="enquiry_box_phone_no" type="text" notEmpty isNumber dname="Phone Number" name="phone">
							</fieldset>
						</td>
					</tr>

					<tr id="enquiry_box_tr_order_number">
						<td><label>[lang_text.iframe_field_order_no;noerr;htmlconv=no;]<span class="red">*</span></label></td>
						<td colspan="2">
							<fieldset class="medium">
								<input id="enquiry_box_order_number" type="text" notEmpty dname="Order Number" name="order_number">
							</fieldset>
						</td>
					</tr>

					<tr>
						<td><label>[lang_text.iframe_field_question;noerr;htmlconv=no;]<span class="red">*</span></label></td>
						<td colspan="2">
							<select id="enquiry_box_question" notempty dname="Question" name="question">

							</select>
						</td>
					</tr>

					<tr id="enquiry_box_tr_item_country">
						<td><label>[lang_text.iframe_field_item_country;noerr;htmlconv=no;]</label></td>
						<td colspan="2">
							<fieldset class="medium">
								<input id="enquiry_box_item_country" type="text" dname="Item/Country" name="item_country">
							</fieldset>
						</td>
					</tr>

					<tr>
						<td><label>[lang_text.iframe_field_message;noerr;htmlconv=no;]<span class="red">*</span></label></td>
						<td colspan="2">
							<fieldset class="textarea">
								<textarea notempty dname="Message" name="contents"></textarea>
							</fieldset>
						</td>
					</tr>

					<tr id="enquiry_box_tr_attachment">
						<td><label>[lang_text.iframe_field_attachment;noerr;htmlconv=no;] (5MB [lang_text.iframe_field_or_less;noerr;htmlconv=no;])</label></td>
						<td colspan="2">
							<input type="file" id="enquiry_box_attachment1" name="attachment1"><br>
							<input type="file" id="enquiry_box_attachment2" name="attachment2">
						</td>
					</tr>

					<tr style="vertical-align:bottom">
						<td><button class="btn" style="cursor:pointer;">[lang_text.iframe_field_submit;noerr;htmlconv=no;]</button></td>
						<td><a href="#" onclick="HideEnquiryBox(); scroll(0,0); return false;">[lang_text.iframe_field_return_to_web;noerr;htmlconv=no;]</a></td>
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

	<div class="text silver_box static_page">
		<p>
			[lang_text.bulk_sales_paragraph_1;noerr;htmlconv=no;]<a href="#enquiry_box_anchor" onclick="ShowEnquiryBox('Bulk');document.getElementById('enquiry_box_question').value = 135;">[lang_text.bulk_sales_contact;noerr;htmlconv=no;]</a>.
			<br /><br />
			[lang_text.bulk_sales_paragraph_2;noerr;htmlconv=no;]
			<br /><br />

			<strong>[lang_text.bulk_sales_min_order;noerr;htmlconv=no;]</strong>
			<br /><br />
			[lang_text.bulk_sales_min_order_1;noerr;htmlconv=no;][lang_text.bulk_sales_min_order_2;noerr;htmlconv=no;][lang_text.bulk_sales_min_order_3;noerr;htmlconv=no;]
			<br /><br />
			[lang_text.bulk_sales_paragraph_2;noerr;htmlconv=no;]
			<br /><br />

			<strong>[lang_text.bulk_sales_payment_mtd;noerr;htmlconv=no;]</strong>
			<br /><br />
			[lang_text.bulk_sales_payment_mtd_1;noerr;htmlconv=no;]
			<br /><br />
			[lang_text.bulk_sales_payment_mtd_2;noerr;htmlconv=no;]
			<br /><br />

			<strong>[lang_text.bulk_sales_shipping_mtd;noerr;htmlconv=no;]</strong>
			<br /><br />
			[lang_text.bulk_sales_shipping_mtd_1;noerr;htmlconv=no;]
			<br>
				<div style="padding:10px 10px 10px;" align="center">
				<img src="http://cdn.valuebasket.com/808AA1/vb//images/aboutus/dhl.jpg" width="130" height="64" /> 
				<img src="http://cdn.valuebasket.com/808AA1/vb//images/aboutus/colissimo.jpg" width="171" height="64" /><br />
				<br />
				<img src="http://cdn.valuebasket.com/808AA1/vb//images/aboutus/tnt.jpg" width="111" height="64" /> 
				<img src="http://cdn.valuebasket.com/808AA1/vb//images/aboutus/laposte.jpg" width="82" height="64" />
				</div>
			<br /><br />

			<strong>[lang_text.bulk_sales_time;noerr;htmlconv=no;]</strong>
			<br /><br />
			[lang_text.bulk_sales_time_1;noerr;htmlconv=no;]
			<br /><br />


			<strong>[lang_text.bulk_sales_cancellation;noerr;htmlconv=no;]</strong>
			<br /><br />
			[lang_text.bulk_sales_cancellation_1;noerr;htmlconv=no;]
			<br /><br />


		</p>
	</div>
</div>