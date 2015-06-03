<script type="text/javascript" src="[base_url]contact/generate_webform_js/kayako"></script>



  <!-- include the Tools -->



  <script src="/resources/js/contactus.js"></script>



 



  <!-- by phone flag --> 



 <script type="text/javascript">

  var imagesPhoneIdArr = new Array("aup","nzp","hkp","sgp","usp","fip","gbp","iep","frp","esp","bep","mtp","chp");
  var imagesMapIdArr = new Array("aum","nzm","hkm","sgm","usm","fim","gbm","iem","frm","esm","bem","mtm","chm");
  var imagesNameArr = new Array("au","nz","hk","sg","us","fi","gb","ie","fr","es","be","mt","ch");

  function mouseOver(obj,type)
  {
	var id = obj.id;
	
	if(type == 'p')
	{
		for(var i=0;i<imagesPhoneIdArr.length;i++){
			var temp = document.getElementById(imagesPhoneIdArr[i]);
			if(temp != null){
				if(id == imagesPhoneIdArr[i]){
					temp.src ="[cdn_url]images/contactus/icon-"+imagesNameArr[i]+".png";
				}else
					temp.src ="[cdn_url]images/contactus/iconBW-"+imagesNameArr[i]+".png";
			}
		}
	}
	else if(type == 'm')
	{
		for(var i=0;i<imagesMapIdArr.length;i++){
			var temp = document.getElementById(imagesMapIdArr[i]);
			if(temp != null){
				if(id == imagesMapIdArr[i]){
					temp.src ="[cdn_url]images/contactus/icon-"+imagesNameArr[i]+".png";
				}else
					temp.src ="[cdn_url]images/contactus/iconBW-"+imagesNameArr[i]+".png";
			}
		}
	}
  }

</script>










<div id="content">



<div id="contact_us">







<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="padding-top:20px">



  <tr>



    <td align="center"><img src="[cdn_url]images/contactus/Contact_Banner.jpg" width="942" height="156" /></td>



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

								<input type="text" notempty islatin dname="Name" id="enquiry_box_fullname" name="fullname">

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

							<label style="display:inline">[lang_text.iframe_field_change_enquiry;noerr;htmlconv=no;]:</label>

							<select id="enquiry_type_selection" style="display:inline">

							</select>

							<a href="#" onclick="ChangeEnquiryType(); return false;" style="float:none">[lang_text.iframe_go;noerr;htmlconv=no;]</a>

						</td>

					</tr>

				</table>

			</form>

			<script type="text/javascript">
				var availableEnquiry = new Array();
				availableEnquiry['Client Support'] = 1;
				availableEnquiry['Pre-Sales'] = 1;
				availableEnquiry['Returns'] = 1;
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



    Otherwise, there are a number of ways you can get in touch with our experienced Sales and Customer Service teams. </div>

	

	

	

	

	

	

	



    </td>



  </tr>



</table>



</td>



  </tr>



  <tr>



    <td align="center"><table width="942" border="0" cellspacing="0" cellpadding="0">



      <tr>



        <td width="10">&nbsp;</td>



        <td width="300" align="left" style="padding: 5px 5px 5px 5px; line-height: 24px; color: #F60; font-weight: bold; font-family: rokkitt; font-size: 21px;"><img src="[cdn_url]images/contactus/byPhone.jpg" width="44" height="42" /> By Phone<br></td>



        <td width="10">&nbsp;</td>



        <td width="300" align="left" style="padding: 5px 5px 5px 5px; line-height: 24px; color: #F60; font-weight: bold; font-family: rokkitt; font-size: 21px;"><img src="[cdn_url]images/contactus/byMail.jpg" width="44" height="42" /> By E-mail<br></td>



        <td width="10">&nbsp;</td>



        <td width="302" align="left" style="padding: 5px 5px 5px 5px; line-height: 24px; color: #F60; font-weight: bold; font-family: rokkitt; font-size: 20px;"><img src="[cdn_url]images/contactus/byAddresses.jpg" width="27" height="42" /> Mail Us and Return Addresses<br></td>



        <td width="10">&nbsp;</td>



      </tr>



    </table></td>



  </tr>



  <tr>



    <td align="center"><table width="942" height="550" border="0" cellspacing="0" cellpadding="0">



      <tr>



        <td width="10" background="[cdn_url]images/contactus/line_00.jpg">&nbsp;</td>



        <td width="300" valign="top" style="padding: 5px 5px 20px 5px; line-height: 16px; color: #494949; font-family: Arial; font-size: 12px;">



		<p>



		If you'd like to get in touch with us by Phone, please select the relevant country below for contact details:



            <br />



            <br />



       <br /></p>



        



        



<!--phone flag -->



          <div style="width:280" id="byPhone">





            <a>



            <img onmouseover="mouseOver(this,'p')" class="" src="[cdn_url]images/contactus/icon-sg.png" width="24" height="24" border=0 alt="sg-phone version" id="sgp" /></a>            

            <a>



            <img onmouseover="mouseOver(this,'p')" class="" src="[cdn_url]images/contactus/iconBW-au.png" width="24" height="24" border=0 alt="au-phone version" id="aup" /></a>

 
<!-- 		SBF #2486
            <a onmouseover="mouseOver_bep()">

            <img class="" src="[cdn_url]images/contactus/iconBW-be.png" width="24" height="24" border=0 alt="fr-phone version" id="bep" /></a> -->

                       

           <a>



            <img onmouseover="mouseOver(this,'p')" class="" src="[cdn_url]images/contactus/iconBW-fi.png" width="24" height="24" border=0 alt="fi-phone version" id="fip" /></a>

            
<!-- 		SBF #2486
            <a onmouseover="mouseOver_frp()">
            <img class="" src="[cdn_url]images/contactus/iconBW-fr.png" width="24" height="24" border=0 alt="fr-phone version" id="frp" /></a>
 -->


            <a>



            <img onmouseover="mouseOver(this,'p')" class="" src="[cdn_url]images/contactus/iconBW-hk.png" width="24" height="24" border=0 alt="hk-phone version" id="hkp" /></a>

            

            <a>



            <img onmouseover="mouseOver(this,'p')"  class="" src="[cdn_url]images/contactus/iconBW-ie.png" width="24" height="24" border=0 alt="ie-phone version" id="iep" /></a>

                        

            <a>



            <img onmouseover="mouseOver(this,'p')" class="" src="[cdn_url]images/contactus/iconBW-nz.png" width="24" height="24" border=0 alt="nz-phone version" id="nzp" /></a> 



<!--        SBF #2425
			<a onmouseover="mouseOver_esp()">
            <img class="" src="[cdn_url]images/contactus/iconBW-es.png" width="24" height="24" border=0 alt="es-phone version" id="esp" /></a>    -->                     

            <a>



            <img onmouseover="mouseOver(this,'p')" class="" src="[cdn_url]images/contactus/iconBW-gb.png" width="24" height="24" border=0 alt="my-phone version" id="gbp" /></a>



            <a>



            <img onmouseover="mouseOver(this,'p')" class="" src="[cdn_url]images/contactus/iconBW-us.png" width="24" height="24" border=0 alt="us-phone version" id="usp" /></a> 



        </div>





<!--phone txt SG -->



<div style="display: block; width:240" class="PhoneDescription" id="sg-phone">



  <div class="arrow">&nbsp;</div>



  <p><font style="color:#F60; font-weight: bold; font-family: Arial; font-size: 14px;">Singapore<br />



    <br />



</font>(+65) 3158 4249<br />



  SGT 7:00 am (Mon) - 7:00 am (Sat)<br /></p>



</div>

            



<!--phone txt AU -->



   <div style="display: block; width:240" class="PhoneDescription" id="au-phone">



  <div class="arrow">&nbsp;</div>



  <p><font style="color:#F60; font-weight: bold; font-family: Arial; font-size: 14px;">Australia<br />



    <br />



  </font>(+61) 08 7100 1133<br />



    EST 10:00 am (Mon) - 10:00 am (Sat)<br /></p>



   </div>







<!--phone txt be -->

<!-- SBF #2486

  <div style="display: block; width:240" class="PhoneDescription" id="be-phone">
  <div class="arrow">&nbsp;</div>
  <p><font style="color:#F60; font-weight: bold; font-family: Arial; font-size: 14px;">Belgium<br />
   <br />
</font>(+33) 09 75 18 23 58<br />
 CET 8:00 am (Mon) - 4:00 pm (Fri)<br /></p>
</div> -->





<!--phone txt fi -->



  <div style="display: block; width:240" class="PhoneDescription" id="fi-phone">



  <div class="arrow">&nbsp;</div>



  <p><font style="color:#F60; font-weight: bold; font-family: Arial; font-size: 14px;">Finland<br />



    <br />



</font>(+358) 09 4241 9254<br />



  EET 8:00 am (Mon) - 11:00 pm (Fri)<br /></p>



</div>





   



<!--phone txt fr -->


<!-- SBF #2486
<div style="display: block; width:240" class="PhoneDescription" id="fr-phone">
	<div class="arrow">&nbsp;</div>

	<p><font style="color:#F60; font-weight: bold; font-family: Arial; font-size: 14px;">France<br />
	<br />
	</font>(+33) 09 75 18 23 58<br />
CET 8:00 am (Mon) - 4:00 pm (Fri)<br /></p>
</div>
 -->





<!--phone txt HK -->



  <div style="display: block; width:240" class="PhoneDescription" id="hk-phone">



  <div class="arrow">&nbsp;</div>



  <p><font style="color:#F60; font-weight: bold; font-family: Arial; font-size: 14px;">Hong Kong<br />



    <br />



</font>(+852) 5808 3310<br />



  HKT 7:00 am (Mon) - 7:00 am (Sat)<br /></p>



</div>







<!--phone txt ie -->



  <div style="display: block; width:240" class="PhoneDescription" id="ie-phone">



  <div class="arrow">&nbsp;</div>



  <p><font style="color:#F60; font-weight: bold; font-family: Arial; font-size: 14px;">Ireland<br />



    <br />



</font>(+353) 0766 150 001<br />



  GMT 8:00 am (Mon) - 11:00 pm (Fri)<br /></p>



</div>







<!--phone txt NZ -->



  <div style="display: block; width:240" class="PhoneDescription" id="nz-phone">



  <div class="arrow">&nbsp;</div>



  <p><font style="color:#F60; font-weight: bold; font-family: Arial; font-size: 14px;">New Zealand<br />



    <br />



</font>(+64) 09 925 0485<br />



  NZDT 12:00 pm (Mon) - 12:00 pm (Sat)<br /></p>



</div>







<!--phone txt es -->

<!-- SBF #2425
	<div style="display: block; width:240" class="PhoneDescription" id="fr-phone">
	<div class="arrow">&nbsp;</div>
	<p><font style="color:#F60; font-weight: bold; font-family: Arial; font-size: 14px;">Spain<br />
	<br />
	</font>(+34) 931 81 66 45<br />
	CET 8:00 am (Mon) - 11:00 pm (Fri)<br /></p>
	</div> -->



   



<!--phone txt GB -->



   <div style="display: block; width:240" class="PhoneDescription" id="gb-phone">



  <div class="arrow">&nbsp;</div>



  <p><font style="color:#F60; font-weight: bold; font-family: Arial; font-size: 14px;">United Kingdom<br />



    <br />



  </font>(+44) 0203 384 0338<br />



    GMT 8:00 am (Mon) - 11:00 pm (Fri)<br /></p>



   </div>



   



<!--phone txt US -->



   <div style="display: block; width:240" class="PhoneDescription" id="us-phone">



  <div class="arrow">&nbsp;</div>



  <p><font style="color:#F60; font-weight: bold; font-family: Arial; font-size: 14px;">United States<br />



    <br />



  </font>(+1) 857 362-16383<br />



    EST 8:00 am (Mon) - 6:00 pm (Fri)<br /></p>



   </div>















<!-- activate tabs with JavaScript -->



<script>



  $(function() {



      $("#byPhone").tabs("div.PhoneDescription", {event:'mouseover'});



    });



</script>

   </td>



        <td width="10" background="[cdn_url]images/contactus/line_00.jpg">&nbsp;</td>



        <td width="300" valign="top" style="padding: 0px 5px 20px 5px; line-height: 16px; color: #494949; font-family: Arial; font-size: 12px;"><p>If you'd like to get in touch with us by E-mail, please select one of the following enquiry types and click the link:<br /></p>

          <p><br />

			

            For<strong> General and After-Sales Enquiries</strong>,<br />



            please <a href="#enquiry_box_anchor" onclick="ShowEnquiryBox('Client Support');" style="line-height: 16px; color: #F60; font-family: Arial; font-weight: bold; font-size: 12px; text-decoration: underline;">click here</a>.<br /><br /></p>



          <p>For<strong> Pre-Sales Enquiries</strong>,<br />



            please <a href="#enquiry_box_anchor" onclick="ShowEnquiryBox('Pre-Sales');" style="line-height: 16px; color: #F60; font-family: Arial; font-weight: bold; font-size: 12px; text-decoration: underline;">click here</a>.<br /><br /></p>



          <p>For<strong> Faulty Goods or Returned Items</strong>,<br />



            please <a href="#enquiry_box_anchor" onclick="ShowEnquiryBox('Returns');" style="line-height: 16px; color: #F60; font-family: Arial; font-weight: bold; font-size: 12px; text-decoration: underline;">click here</a>.<br /></p>







          </td>



        <td width="10" background="[cdn_url]images/contactus/line_00.jpg">&nbsp;</td>



        <td width="302" valign="top" style="padding: 0px 5px 20px 5px; line-height: 16px; color: #494949; font-family: Arial; font-size: 12px;"><p>If you need to return a faulty or unwanted product, please contact our Returns Department in the first instance by <a href="#enquiry_box_anchor" onclick="ShowEnquiryBox('Returns');" style="line-height: 16px; color: #F60; font-family: Arial; font-size: 12px; text-decoration: underline;">clicking here</a> or visit our <a href="http://www.valuebasket.com.sg/en_SG/login?back=myaccount" style="line-height: 16px; color: #F60; font-family: Arial; font-size: 12px; text-decoration: underline;">My Account</a> section to access the Returns Request form.<br/><br /></p>



          <p>For your convenience we have set-up returns and mailing addresses in the following locations (please select the relevant country below for details):<br />



            <br />



        <br /></p>



          



          



<!--Map flag -->



          <div id="byMap">



            <a>



            <img onmouseover="mouseOver(this,'m')" class="" src="[cdn_url]images/contactus/icon-sg.png" width="24" height="24" border=0 alt="sg-Address version" id="sgm" /></a>

            

            <a>



            <img onmouseover="mouseOver(this,'m')" class="" src="[cdn_url]images/contactus/iconBW-au.png" width="24" height="24" border=0 alt="au-phone version" id="aum" /></a>


            <a>



            <img onmouseover="mouseOver(this,'m')" class="" src="[cdn_url]images/contactus/iconBW-hk.png" width="24" height="24" border=0 alt="hk-Address version" id="hkm" /></a>



            <a>



            <img onmouseover="mouseOver(this,'m')" class="" src="[cdn_url]images/contactus/iconBW-nz.png" width="24" height="24" border=0 alt="nz-Address version" id="nzm" /></a>


</div>

          



<!-- map txt image SG -->



<div style="display: block;" class="AddressDescription" id="sg-address"><div class="arrow">&nbsp;</div>



  <div class="arrow">



  <br /><font style="color:#F60; font-weight: bold; font-family: Arial; font-size: 14px;">Singapore</font><br /><a style="color: #fff; font-size: 8px;">______</a>



  <br />



  71 Ubi Crescent, Unit 04-09<br />



Postal Code 408571, Singapore<br />



<a style="color: #fff; font-size: 12px;">______</a><img src="[cdn_url]images/contactus/map_sg.jpg" alt="" width="288" height="188" /><br /><a href="https://maps.google.com/maps?q=71+Ubi+Crescent,+408571,+Singapore&hl=en&sll=1.326862,103.898324&sspn=0.011198,0.011362&gl=hk&brcurrent=3,0x0:0x0,0&hnear=71+Ubi+Crescent,+Singapore+408571&t=m&z=17" onclick="window.open('https://maps.google.com/maps?q=71+Ubi+Crescent,+408571,+Singapore&hl=en&sll=1.326862,103.898324&sspn=0.011198,0.011362&gl=hk&brcurrent=3,0x0:0x0,0&hnear=71+Ubi+Crescent,+Singapore+408571&t=m&z=17','height=760,width=780,scrollbars=1'); 



return false;" style="color:#0000FF;text-align:left font-size: 8px;">View Larger Map</a></div>



</div>







<!-- map txt image AU -->



<div style="display: block;" class="AddressDescription" id="au-address"><div class="arrow">&nbsp;</div>



  <div class="arrow"><br />



    <font style="color:#F60; font-weight: bold; font-family: Arial; font-size: 14px;">Australia</font><br />



    <br />



    Level 5, 11 Queens Road<br />



Melbourne VIC 3004, Australia<br />



<a style="color: #fff; font-size: 12px;">______</a><img src="[cdn_url]images/contactus/map_au.jpg" width="288" height="188" /><br /><a href="https://maps.google.com/maps?f=q&amp;source=embed&amp;hl=en&amp;geocode=&amp;q=11+Queens+Road+Melbourne+VIC+3004,+Australia&amp;aq=&amp;sll=43.571592,1.424328&amp;sspn=0.008139,0.011812&amp;gl=hk&amp;brcurrent=3,0x0:0x0,0&amp;ie=UTF8&amp;hq=&amp;hnear=11+Queens+Rd,+Melbourne+Victoria+3004,+Australia&amp;t=m&amp;ll=-37.837649,144.974384&amp;spn=0.012744,0.024719&amp;z=14" onclick="window.open('https://maps.google.com/maps?f=q&amp;source=embed&amp;hl=en&amp;geocode=&amp;q=11+Queens+Road+Melbourne+VIC+3004,+Australia&amp;aq=&amp;sll=43.571592,1.424328&amp;sspn=0.008139,0.011812&amp;gl=hk&amp;brcurrent=3,0x0:0x0,0&amp;ie=UTF8&amp;hq=&amp;hnear=11+Queens+Rd,+Melbourne+Victoria+3004,+Australia&amp;t=m&amp;ll=-37.837649,144.974384&amp;spn=0.012744,0.024719&amp;z=14','height=760,width=780,scrollbars=1'); 



return false;" style="color:#0000FF;text-align:left font-size: 8px;">View Larger Map</a></div>



</div>



<!-- map txt image HK -->



<div style="display: block;" class="AddressDescription" id="hk-address"><div class="arrow">&nbsp;</div>



  <div class="arrow">



  <br /><font style="color:#F60; font-weight: bold; font-family: Arial; font-size: 14px;">Hong Kong</font><br />



  <br />



  32/F, Tower 1, Millennium City<br />



388 Kwun Tong Road<br/>Kwun Tong, Kowloon,



Hong Kong SAR<img src="[cdn_url]images/contactus/map_hk.jpg" alt="" width="288" height="188" /><br /><a href="https://maps.google.com/maps?q=Tower+1,+Millennium+City+388+Kwun+Tong+Road+Kwun+Tong,+Kowloon,+Hong+Kong+SAR&hl=en&sll=22.313792,114.221171&sspn=0.010362,0.011362&gl=hk&brcurrent=3,0x3404014aab45cebf:0x4c9fbf6db61e27a,0,0x3404014883a04d95:0xf7ab69df5f85d6b3&hnear=Millennium+City+Tower+1,+388+Kwun+Tong+Rd&t=m&z=17" onclick="window.open('https://maps.google.com/maps?q=Tower+1,+Millennium+City+388+Kwun+Tong+Road+Kwun+Tong,+Kowloon,+Hong+Kong+SAR&hl=en&sll=22.313792,114.221171&sspn=0.010362,0.011362&gl=hk&brcurrent=3,0x3404014aab45cebf:0x4c9fbf6db61e27a,0,0x3404014883a04d95:0xf7ab69df5f85d6b3&hnear=Millennium+City+Tower+1,+388+Kwun+Tong+Rd&t=m&z=17','height=760,width=780,scrollbars=1'); 



return false;" style="color:#0000FF;text-align:left font-size: 8px;">View Larger Map</a></div>



</div>







<!-- map txt image NZ -->



<div style="display: block;" class="AddressDescription" id="nz-address">



<div class="arrow">&nbsp;</div>



  <div class="arrow">



  <br /><font style="color:#F60; font-weight: bold; font-family: Arial; font-size: 14px;">New Zealand</font><br />



  <br />



  Plaza Level 41 Shortland St<br />



Auckland, 1010, New Zealand<br />



<a style="color: #fff; font-size: 12px;">______</a><img src="[cdn_url]images/contactus/map_nz.jpg" alt="" width="288" height="188" /><br /><a href="https://maps.google.com/maps?q=41+Shortland+St+Auckland,+1010,+New+Zealand&hl=en&sll=-36.847265,174.767894&sspn=0.010483,0.023378&brcurrent=3,0x0:0x0,0&hnear=41+Shortland+St,+Auckland,+1010,+New+Zealand&t=m&z=17" onclick="window.open('https://maps.google.com/maps?q=41+Shortland+St+Auckland,+1010,+New+Zealand&hl=en&sll=-36.847265,174.767894&sspn=0.010483,0.023378&brcurrent=3,0x0:0x0,0&hnear=41+Shortland+St,+Auckland,+1010,+New+Zealand&t=m&z=17','height=760,width=780,scrollbars=1'); 



return false;" style="color:#0000FF;text-align:left font-size: 8px;">View Larger Map</a></div>



</div>

<!-- activate tabs with JavaScript -->



<script>



  $(function() {



      $("#byMap").tabs("div.AddressDescription", {event:'mouseover'});



    });



</script></td>



        <td width="10" background="[cdn_url]images/contactus/line_00.jpg">&nbsp;</td>



      </tr>



    </table></td></tr>



  <tr>



    <td align="center" style="padding:10px 0px 10px 0px">



	<table width="942" border="0" cellspacing="0" cellpadding="0">



      <tr>



        <td width="20" height="20" background="[cdn_url]images/contactus/line_01.jpg">&nbsp;</td>



        <td background="[cdn_url]images/contactus/line_02.jpg">&nbsp;</td>



        <td background="[cdn_url]images/contactus/line_02.jpg">&nbsp;</td>



        <td width="20" height="20"  background="[cdn_url]images/contactus/line_03.jpg">&nbsp;</td>



      </tr>



      <tr>



        <td background="[cdn_url]images/contactus/line_08.jpg">&nbsp;</td>



        <td><img src="[cdn_url]images/contactus/Enquiries.jpg" width="44" height="42" /></td>



        <td align="left" valign="bottom"><span style="padding: 10px 5px 10px 10px; line-height: 24px; color: #F60; font-weight: bold; font-family: rokkitt; font-size: 21px;">General Media and Press Enquiries</span></td>



        <td background="[cdn_url]images/contactus/line_04.jpg">&nbsp;</td>



      </tr>



      <tr>



        <td height="40" background="[cdn_url]images/contactus/line_08.jpg">&nbsp;</td>



        <td>&nbsp;</td>



        <td style="padding: 10px 5px 10px 10px; line-height: 16px; color: #494949; font-family: Arial; font-size: 12px;">Kindly send any related enquiries to:<font style="color:#F60; font-family: Arial; font-size: 12px;">pr[at]valuebasket.com</font>.Please note this contact is only for press and media related queries.</td>



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





<script language="JavaScript">

<!--

	var show_enquiry = '[show_enquiry;noerr;htmlconv=no;protect=no]';
	var question_id = '[question_id;noerr;htmlconv=no;protect=no]';
	if (show_enquiry != '')
	{
		ShowEnquiryBoxByQuestionId(show_enquiry,question_id);
		window.location.hash = 'enquiry_box_anchor'; 
	}


-->
</script>

		

		

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


