<script type="text/javascript" src="[base_url]contact/generate_webform_js/kayako"></script>



  <!-- include the Tools -->

  <script src="/resources/js/contactus.js"></script>





<div id="content">



<div id="contact_us">



<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="padding-top:20px">

  <tr>

    <td align="center"><img src="[cdn_url]images/contactus/Contact_Banner_ph.jpg" width="942" height="156" /></td>

  </tr>
<!-- enquiry forms -->  
  <tr>
  <td width="100%">

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
				availableEnquiry['GE PH'] = 1;
				SetAvailableEnquiry(availableEnquiry);
			</script>
		</div>
</td></tr>
<!-- end enquire forms -->
  <tr>

    <td align="center">

    <table width="942" border="0" cellspacing="0" cellpadding="0">

  <tr>

    <td><div style="padding: 14px 10px 14px 10px; line-height: 24px; color: #494949; font-family: Arial; font-size: 13px;">Having an issue? Try using our <a href="[base_url;noerr;]display/view/faq" style="color:#F60; text-decoration: underline;">Interactive HelpDesk and FAQ's</a> to find an answer to your question.<br />

    Otherwise, there are a number of ways you can get in touch with us. </div>

    </td>

  </tr>

</table>

</td>

  </tr>

  <tr>

    <td align="center"><table width="942" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="20" height="20" background="[cdn_url]images/contactus/line_01.jpg">&nbsp;</td>
        <td width="54" background="[cdn_url]images/contactus/line_02.jpg">&nbsp;</td>
        <td background="[cdn_url]images/contactus/line_02.jpg">&nbsp;</td>
        <td width="20" height="20"  background="[cdn_url]images/contactus/line_03.jpg">&nbsp;</td>
      </tr>
      <tr>
        <td background="[cdn_url]images/contactus/line_08.jpg">&nbsp;</td>
        <td><img src="[cdn_url]images/contactus/byMail.jpg" alt="" width="44" height="42" /></td>
        <td align="left" valign="bottom"><span style="padding: 5px 5px 5px 5px; line-height: 24px; color: #F60; font-weight: bold; font-family: rokkitt; font-size: 21px;">By E-mail</span></td>
        <td background="[cdn_url]images/contactus/line_04.jpg">&nbsp;</td>
      </tr>
      <tr>
        <td height="40" background="[cdn_url]images/contactus/line_08.jpg">&nbsp;</td>
        <td>&nbsp;</td>
        <td style="padding: 10px 5px 10px 10px; line-height: 16px; color: #494949; font-family: Arial; font-size: 12px;"><p>If you'd like to get in touch with us by E-mail, please <a href="#enquiry_box_anchor" onclick="ShowEnquiryBox('GE PH');" style="line-height: 16px; color: #F60; font-family: Arial; font-weight: bold; font-size: 12px; text-decoration: underline;">click here</a>.</p></td>
        <td background="[cdn_url]images/contactus/line_04.jpg">&nbsp;</td>
      </tr>
      <tr>
        <td width="20" height="20" background="[cdn_url]images/contactus/line_07.jpg">&nbsp;</td>
        <td background="[cdn_url]images/contactus/line_06.jpg">&nbsp;</td>
        <td background="[cdn_url]images/contactus/line_06.jpg">&nbsp;</td>
        <td background="[cdn_url]images/contactus/line_05.jpg">&nbsp;</td>
      </tr>
    </table>
   
      <br />
      <table width="942" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="20" height="20" background="[cdn_url]images/contactus/line_01.jpg">&nbsp;</td>
          <td width="54" background="[cdn_url]images/contactus/line_02.jpg">&nbsp;</td>
          <td background="[cdn_url]images/contactus/line_02.jpg">&nbsp;</td>
          <td width="20" height="20"  background="[cdn_url]images/contactus/line_03.jpg">&nbsp;</td>
        </tr>
        <tr>
          <td background="[cdn_url]images/contactus/line_08.jpg">&nbsp;</td>
          <td align="center"><img src="[cdn_url]images/contactus/byAddresses.jpg" alt="" width="27" height="42" /></td>
          <td align="left" valign="bottom"><span style="padding: 10px 5px 10px 10px; line-height: 24px; color: #F60; font-weight: bold; font-family: rokkitt; font-size: 21px;">Mailing Address</span></td>
          <td background="[cdn_url]images/contactus/line_04.jpg">&nbsp;</td>
        </tr>
        <tr>
          <td height="40" background="[cdn_url]images/contactus/line_08.jpg">&nbsp;</td>
          <td>&nbsp;</td>
          <td style="padding: 10px 5px 10px 10px; line-height: 16px; color: #494949; font-family: Arial; font-size: 12px;">3270-C, Armstrong Ave. Brgy.<br />Kalayaan, Pasay City 1300</td>
          <td background="[cdn_url]images/contactus/line_04.jpg">&nbsp;</td>
        </tr>
        <tr>
          <td width="20" height="20" background="[cdn_url]images/contactus/line_07.jpg">&nbsp;</td>
          <td background="[cdn_url]images/contactus/line_06.jpg">&nbsp;</td>
          <td background="[cdn_url]images/contactus/line_06.jpg">&nbsp;</td>
          <td background="[cdn_url]images/contactus/line_05.jpg">&nbsp;</td>
        </tr>
      </table>
      <br />
      <table width="942" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="20" height="20" background="[cdn_url]images/contactus/line_01.jpg">&nbsp;</td>
          <td width="54" background="[cdn_url]images/contactus/line_02.jpg">&nbsp;</td>
          <td background="[cdn_url]images/contactus/line_02.jpg">&nbsp;</td>
          <td width="20" height="20"  background="[cdn_url]images/contactus/line_03.jpg">&nbsp;</td>
        </tr>
        <tr>
          <td background="[cdn_url]images/contactus/line_08.jpg">&nbsp;</td>
          <td><img src="[cdn_url]images/contactus/Enquiries.jpg" alt="" width="44" height="42" /></td>
          <td align="left" valign="bottom"><span style="padding: 10px 5px 10px 10px; line-height: 24px; color: #F60; font-weight: bold; font-family: rokkitt; font-size: 21px;">General Media and Press Enquiries</span></td>
          <td background="[cdn_url]images/contactus/line_04.jpg">&nbsp;</td>
        </tr>
        <tr>
          <td height="40" background="[cdn_url]images/contactus/line_08.jpg">&nbsp;</td>
          <td>&nbsp;</td>
          <td style="padding: 10px 5px 10px 10px; line-height: 16px; color: #494949; font-family: Arial; font-size: 12px;">Kindly send any related enquiries to:<font style="color:#F60; font-family: Arial; font-size: 12px;"> pr[at]valuebasket.com</font>. Please note this contact is only for press and media related queries.</td>
          <td background="[cdn_url]images/contactus/line_04.jpg">&nbsp;</td>
        </tr>
        <tr>
          <td width="20" height="20" background="[cdn_url]images/contactus/line_07.jpg">&nbsp;</td>
          <td background="[cdn_url]images/contactus/line_06.jpg">&nbsp;</td>
          <td background="[cdn_url]images/contactus/line_06.jpg">&nbsp;</td>
          <td background="[cdn_url]images/contactus/line_05.jpg">&nbsp;</td>
        </tr>
      </table></td>

  </tr>




</table>
</div>

</div>


<script language="JavaScript">
<!--

// sbf #2210 this will only popup when linked from web form on payment failure page

	if ([info.showpopup;noerr;htmlconv=no;]==1)
	{
		FillandShowBox();
	}
	
	function FillandShowBox()
	{
		ShowEnquiryBox('Pre-Sales');
		document.getElementById('enquiry_box_fullname').value = '[info.fullname;noerr;htmlconv=no;]';
		document.getElementById('enquiry_box_email').value = '[info.email;noerr;htmlconv=no;]';
		document.getElementById('enquiry_box_phone_no').value = '[info.phone_no;noerr;htmlconv=no;]';
		document.getElementById('enquiry_box_item_country').value = '[info.item_country;noerr;htmlconv=no;]';
		document.getElementById('enquiry_box_question').value = 105;
		return;
	}

-->
</script>