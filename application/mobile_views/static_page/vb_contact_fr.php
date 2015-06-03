<script type="text/javascript" src="[base_url]contact/generate_webform_js/kayako"></script>


<div id="content">







<div id="contact_us">







<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="padding-top:20px">



  <tr>



    <td align="center"><img src="[cdn_url]images/contactus/Contact_Banner_fr.jpg" width="942" height="156" /></td>



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



    <td><div style="padding: 14px 10px 14px 10px; line-height: 24px; color: #494949; font-family: Arial; font-size: 13px;">Vous avez un problème? Essayez notre <a href="[base_url;noerr;]display/view/faq" style="color:#F60; text-decoration: underline;">Bureau d’Aide et FAQ</a> afin de trouver une réponse à votre question.<br />



    Il existe aussi de nombreuses façons afin de contacter notre équipe de vente et notre service client dévoué et expérimenté. </div>



    </td>



  </tr>



</table>



</td>



  </tr>



  <tr>



    <td align="center"><table width="942" border="0" cellspacing="0" cellpadding="0">



      <tr>



        <td width="10">&nbsp;</td>


<!--         SBF #2486 remove fr hotline
        <td width="300" align="left" style="padding: 5px 5px 5px 5px; line-height: 24px; color: #F60; font-weight: bold; font-family: rokkitt; font-size: 21px;"><img src="[cdn_url]images/contactus/byPhone.jpg" width="44" height="42" /> Par Téléphone<br></td> -->



        <td width="10">&nbsp;</td>



        <td width="300" align="left" style="padding: 5px 5px 5px 5px; line-height: 24px; color: #F60; font-weight: bold; font-family: rokkitt; font-size: 21px;"><img src="[cdn_url]images/contactus/byMail.jpg" width="44" height="42" /> Par E-mail<br></td>



        <td width="10">&nbsp;</td>



        <td width="302" align="left" ><table width="0" border="0" cellspacing="0" cellpadding="0">



          <tr>



              <td width="32"><img src="[cdn_url]images/contactus/byAddresses.jpg" width="27" height="42" /></td>



              <td width="220" valign="bottom" style=" line-height: 18px; color: #F60; font-weight: bold; font-family: rokkitt; font-size: 20px;">Information sur les Retours</td>



            </tr>



        </table></td>



        <td width="10">&nbsp;</td>



      </tr>



    </table></td>



  </tr>



  <tr>



    <td align="center"><table width="942" height="560" border="0" cellspacing="0" cellpadding="0">



      <tr>


<!-- SBF #2486
        <td width="10" background="[cdn_url]images/contactus/line_00.jpg">&nbsp;</td>
        <td width="300" valign="top" style="padding: 5px 5px 20px 5px; line-height: 16px; color: #494949; font-family: Arial; font-size: 12px;">
		<p>
		Si vous souhaitez nous contacter par téléphone, veuillez trouver ci-dessous nos coordonnées : 
            <br />
            <br />
       <br /></p> -->

	<!--phone txt fr -->

<!-- SBF #2486
	<div style="display: block; width:240" class="PhoneDescription" id="fr-phone">
		<p><font style="color:#F60; font-weight: bold; font-family: Arial; font-size: 14px;">France<br />
		<br />
		</font>09 75 18 23 58<br />
		CET 8h00 - 16h00 (Du Lundi au Vendredi)<br /></p>
	</div>
 -->










   </td>



        <td width="10" background="[cdn_url]images/contactus/line_00.jpg">&nbsp;</td>



        <td width="300" valign="top" style="padding: 0px 5px 20px 5px; line-height: 16px; color: #494949; font-family: Arial; font-size: 12px;"><p>Si vous souhaitez nous contacter par Email, veuillez sélectionner l’un des types de renseignements ci-dessous, et cliquez sur le lien <br /></p>


          <p><br />



            Pour tous<strong> renseignements <br />



            généraux et service après-vente</strong>,<br />



            s'il vous plaît <a href="#enquiry_box_anchor" onclick="ShowEnquiryBox('Client Support');" style="line-height: 16px; color: #F60; font-family: Arial; font-weight: bold; font-size: 12px; text-decoration: underline;">cliquez ici</a>.<br /><br /></p>



          <p>Pour tous<strong> renseignements <br />



            précédent votre achat</strong>,<br />



            s'il vous plaît <a href="#enquiry_box_anchor" onclick="ShowEnquiryBox('Pre-Sales');" style="line-height: 16px; color: #F60; font-family: Arial; font-weight: bold; font-size: 12px; text-decoration: underline;">cliquez ici</a>.<br /><br /></p>



          <p>Pour tous<strong> marchandises <br />



            défectueuses ou articles retournés</strong>,<br />



            s'il vous plaît <a href="#enquiry_box_anchor" onclick="ShowEnquiryBox('Returns');" style="line-height: 16px; color: #F60; font-family: Arial; font-weight: bold; font-size: 12px; text-decoration: underline;">cliquez ici</a>.<br /></p>



          </td>



        <td width="10" background="[cdn_url]images/contactus/line_00.jpg">&nbsp;</td>



        <td width="302" valign="top" style="padding: 0px 5px 20px 5px; line-height: 16px; color: #494949; font-family: Arial; font-size: 12px;"><p>Dans le cas où l'article que vous avez reçu est défectueux ou si vous souhaitez retourner votre produit, merci de contacter notre équipe en <a href="#enquiry_box_anchor" onclick="ShowEnquiryBox('Returns');" style="line-height: 16px; color: #F60; font-family: Arial; font-size: 12px; text-decoration: underline;">cliquant ici</a> ou en vous rendant sur la rubrique <a href="http://www.valuebasket.fr/fr_FR/login?back=myaccount" style="line-height: 16px; color: #F60; font-family: Arial; font-size: 12px; text-decoration: underline;">Mon Compte</a> afin d’accéder au formulaire de Demande de Retour.<br/><br /></p>



          <p>Pour votre commodité, voici une présentation de notre procédure de retour.<br />



            <br />



        <br /></p>



          



          



















<!-- map txt image FR -->
<center><table background="[cdn_url]images/contactus/returnmap_bg.jpg" width="280" height="495" border="0" cellpadding="0" cellspacing="0" style="line-height: 10px; color: #494949; font-family: Arial; font-size: 10px;">
            <tr>
              <td align="center"><p><img src="[cdn_url]images/contactus/Map_contact.jpg" width="103" height="103" /><br />
                <br />
                Contactez-nous ou remplissez<br />
                un Formulaire de Retour<br /><br />
<br />

              </p>
                <table width="280" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="105" align="center" valign="top" style="line-height: 10px; color: #494949; font-family: Arial; font-size: 10px;"><p><img src="[cdn_url]images/contactus/Map_send.jpg" width="103" height="103" /><br /><br />

                      Retournez votre <br />
                      colis par le biais des services d&rsquo;un transporteur<br />
                      </p></td>
                    <td>&nbsp;</td>
                    <td width="105" align="center" valign="top" style="line-height: 10px; color: #494949; font-family: Arial; font-size: 10px;"><p><img src="[cdn_url]images/contactus/Map_pack.jpg" width="103" height="103" /><br />
                      <br />
Emballez le colis<br />
soigneusement<br />
                        <br />
                      </p></td>
                  </tr>
              </table>
                <p><br /><img src="[cdn_url]images/contactus/Map_label.jpg" width="103" height="103" /><br />
                  <br />
                  Etiquetez le colis avec <br />
                  notre adresse de retour<br />
                </p></td>
            </tr>
          </table></center>






</td>



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



        <td align="left" valign="bottom"><span style="padding: 10px 5px 10px 10px; line-height: 24px; color: #F60; font-weight: bold; font-family: rokkitt; font-size: 21px;">Renseignement relatifs aux médias et presse</span></td>



        <td background="[cdn_url]images/contactus/line_04.jpg">&nbsp;</td>



      </tr>



      <tr>



        <td height="40" background="[cdn_url]images/contactus/line_08.jpg">&nbsp;</td>



        <td>&nbsp;</td>



        <td style="padding: 10px 5px 10px 10px; line-height: 16px; color: #494949; font-family: Arial; font-size: 12px;">S'il vous plaît envoyer vos demandes de renseignements à :<font style="color:#F60; font-family: Arial; font-size: 12px;">pr[at]valuebasket.com</font>.<br />



          Veuillez noter que le contact ci-dessus est seulement pour les questions de presse et médias.</td>



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
		document.getElementById('enquiry_box_question').value = '132';
		return;
	}
	
	var show_enquiry = '[show_enquiry;noerr;htmlconv=no;protect=no]';
	var question_id = '[question_id;noerr;htmlconv=no;protect=no]';
	if (show_enquiry != '')
	{
		ShowEnquiryBoxByQuestionId(show_enquiry,question_id);
		window.location.hash = 'enquiry_box_anchor'; 
	}

-->
</script>
