<?php
include_once(APPPATH."helpers/string_helper.php");

$this->tbswrapper->tbsLoadTemplate('resources/template/footer.html', '', '', $data['lang_text']);
if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) 
{
	$http = "https";
}
else
{
	$http = "http";
}



switch (strtolower(PLATFORMCOUNTRYID))
{
	case 'au' : $kayako_proactive_chat = 'EN'; $kayako_site_badge = 'EN'; break;
	case 'be' : $kayako_proactive_chat = '';   $kayako_site_badge = 'FR'; break;
	case 'fi' : $kayako_proactive_chat = '';   $kayako_site_badge = '';   break;
	case 'fr' : $kayako_proactive_chat = 'FR'; $kayako_site_badge = 'FR'; break;
	case 'gb' : $kayako_proactive_chat = 'EN'; $kayako_site_badge = 'EN'; break;
	case 'hk' : $kayako_proactive_chat = 'EN'; $kayako_site_badge = '';   break;
	case 'ie' : $kayako_proactive_chat = 'EN'; $kayako_site_badge = '';   break;
	case 'it' : $kayako_proactive_chat = '';   $kayako_site_badge = 'IT'; break;
	case 'my' : $kayako_proactive_chat = 'EN'; $kayako_site_badge = '';   break;
	case 'nz' : $kayako_proactive_chat = 'EN'; $kayako_site_badge = '';   break;
	case 'sg' : $kayako_proactive_chat = 'EN'; $kayako_site_badge = 'EN'; break;
	case 'es' : $kayako_proactive_chat = '';   $kayako_site_badge = 'ES'; break;
	case 'us' : $kayako_proactive_chat = 'EN'; $kayako_site_badge = '';   break;
	case 'ph' : $kayako_proactive_chat = '';   $kayako_site_badge = 'PH'; break;
	case 'pl' : $kayako_proactive_chat = '';   $kayako_site_badge = 'PL'; break;
	case 'pt' : $kayako_proactive_chat = '';   $kayako_site_badge = 'ES'; break;
	case 'ru' : $kayako_proactive_chat = '';   $kayako_site_badge = 'RU'; break;
	

	default   : $kayako_proactive_chat = ''; $kayako_site_badge = '';
}

$this->tbswrapper->tbsMergeField('kayako_proactive_chat', $kayako_proactive_chat);
$this->tbswrapper->tbsMergeField('kayako_site_badge', $kayako_site_badge);



$fdl_banner["url"] = base_url().'display/view/shipping';
$support_banner["url"] = base_url().'contact';
if($free_delivery_limit > 0)
{
	$fdl_banner["limit"] = $data['lang_text']['footer_text_for_order_above'] . " " . $PLATFORMCURRSIGN . " " . $free_delivery_limit;
}
else
{
	$fdl_banner["limit"] = $data['lang_text']['default_text_for_all_orders'] . "<sup>*</sup>";
}
//print strtolower(PLATFORMID);
$menu_script = file_get_contents(VIEWPATH . "template/menu/" . get_lang_id() . "/footer_menu_" . strtolower(PLATFORMID) . ".html", true);
$this->tbswrapper->tbsMergeField('menu', $menu_script);

$this->tbswrapper->tbsMergeField('fdl_banner', $fdl_banner);
$this->tbswrapper->tbsMergeField('support_banner', $support_banner);

$capital_lang_id = strtoupper(get_lang_id());

$sitemap = array("url"=>base_url(), "display_name"=>"Sitemap");
$aboutus = array("url"=>base_url().'display/view/about_us', "display_name"=>$data['lang_text']['footer_text_about_us']);
$terms = array("url"=>base_url().'display/view/conditions_of_use', "display_name"=>$data['lang_text']['footer_text_conditions_of_use']);
$warranty_tc = array("url"=>base_url().'display/view/warranty_tc', "display_name"=>$data['lang_text']['footer_text_warranty_tc']);
$affiliate = array("display_name"=>$data['lang_text']['footer_text_affiliate']);
$policy = array("url"=>base_url().'display/view/privacy_policy', "display_name"=>$data['lang_text']['footer_text_privacy_policy']);
$blog = array("url"=>'/blog', "display_name"=>$data['lang_text']['footer_text_blog']);
$shipping = array("url"=>base_url().'display/view/shipping', "display_name"=>$data['lang_text']['default_menu_shipping']);

$this->tbswrapper->tbsMergeField('base_url', base_url());
$this->tbswrapper->tbsMergeField('site_url', site_url());
if ($controller_path == "/common/redirect_controller/index")
	$controller_path = "";
$this->tbswrapper->tbsMergeField('controller_path', $controller_path);
$this->tbswrapper->tbsMergeField('sitemap', $sitemap);
$this->tbswrapper->tbsMergeField('policy', $policy);
$this->tbswrapper->tbsMergeField('terms', $terms);
$this->tbswrapper->tbsMergeField('warranty_tc', $warranty_tc);
$this->tbswrapper->tbsMergeField('affiliate', $affiliate);
$this->tbswrapper->tbsMergeField('aboutus', $aboutus);
$this->tbswrapper->tbsMergeField('blog', $blog);
$this->tbswrapper->tbsMergeField('shipping', $shipping);

// $this->tbswrapper->tbsMergeField('bulk_sales', $bulk_sales);
// $this->tbswrapper->tbsMergeField('allow_bulk_sales', $allow_bulk_sales);
$pf = array();
$selected_country = "";
$lang_country_pair = '';
foreach($platform_list AS $key=>$obj)
{
	$pf[$key]['country_id'] = $obj->get_language_id() . '_' . $obj->get_id();
	$pf[$key]['country_name'] = $obj->get_name();
	$platform_currency[$obj->get_id()] = $obj->get_currency_id();
	if($obj->get_id() == PLATFORMCOUNTRYID)
	{
		$pf[$key]['selected'] = 'SELECTED';
		$selected_country = $obj->get_id();
		$lang_country_pair = $obj->get_language_id() . '_' . $obj->get_id();
	}
	else
	{
		
		$pf[$key]['selected'] = '';
	}
}

$this->tbswrapper->tbsMergeField('selected_country', $selected_country);
$this->tbswrapper->tbsMergeBlock('platform_list', $pf);
$this->tbswrapper->tbsMergeField('lang_part', lang_part());

$currency_str = json_encode($platform_currency);
//$back_url = urlencode(str_replace(site_url(), site_url().$_SESSION["domain_platform"]["platform_country_id"].'/', current_url()).($_SERVER["QUERY_STRING"] ? "?".$_SERVER["QUERY_STRING"] : ""));
$back_url = urlencode(base_url() . uri_string() . ($_SERVER["QUERY_STRING"] ? "?".$_SERVER["QUERY_STRING"] : ""));
$this->tbswrapper->tbsMergeField('query_string', urlencode($_SERVER["QUERY_STRING"] ? "?".$_SERVER["QUERY_STRING"] : ""));
$domain = check_domain();
$chk_cart = '';
if($_SESSION["cart"][PLATFORMID])
{
	$chk_cart = base64_encode(serialize($_SESSION["cart"][PLATFORMID]));
	$this->tbswrapper->tbsMergeField('chk_cart', $chk_cart);
	$this->tbswrapper->tbsMergeField('empty_cart', 2);
}
else
{
	$this->tbswrapper->tbsMergeField('empty_cart', 1);
}

if (PLATFORMCOUNTRYID == 'PH')
{
	// $money_back = "";
	// $warranty = "<span style='display:table-cell;'><a href='/hesk_ph/knowledgebase.php?article=20' title='' class='footer-banner_bank_transfer'>
	// 			<ins>" . $data['lang_text']['footer_text_bank_transfer'] . "</ins>
	// 			<span>" . $data['lang_text']['footer_text_click_for_details'] . "</span>
	// 		</a></span>";

	$url["warranty"] = "http://www.valuebasket.com.ph/hesk_ph/knowledgebase.php?article=20";
	$warranty_img = "{$http}://cdn.valuebasket.com/808AA1/vb/resources/images/footer-bank_transfer.jpg";


}
else
{
	// $money_back = "		<span style='display:table-cell;'>
	// 		<a href='" . base_url() . "display/view/faq' title='' class='footer-banner-02'>
	// 			<ins>" . $data['lang_text']['footer_text_money_back'] . "</ins>
	// 			<span>" . $data['lang_text']['footer_text_money_back_guarantee'] . "</span>
	// 		</a>
	// 	</span>";
	// $warranty = "<span style='display:table-cell;'><a href='" . base_url() . "display/view/warranty' title='' class='footer-banner-04'>
	// 			<ins>" . $data['lang_text']['footer_text_warranty'] . "</ins>
	// 			<span>" . $data['lang_text']['footer_text_warranty_detail'] . "</span>
	// 		</a></span>";

	$money_back_title = $data['lang_text']['footer_text_money_back'];
	$money_back_detail = $data['lang_text']['footer_text_money_back_guarantee'];

	$url["warranty"] = base_url() . "display/view/warranty";

}

$url["faq"] = base_url(). "display/view/faq";


$lang_part = lang_part();

#SBF #3211
switch (PLATFORMCOUNTRYID) 
{
	case 'PH':
				$sign_up_bottom = <<<EOT
		<table>
			<tbody>
			<tr>
				<td align="center">
					<div class='subscribe-bottom' align="left"  style="display:vertical-align:middle;">
						<table  border="0" cellspacing="0" cellpadding="0"  width="695px" height="110px">
							<tr>
								<td align="center">
									<div class='text' align="center" style=" padding:0px 20px 0px 10px;">
										{$data['lang_text']['footer_newsletter_sign_up']}
										<br>
										<form method='post' action='$lang_part/display/view/newsletter_thank_you' name='subscribe-bottom'>
											<fieldset>
												<input type='text' onfocus=\"if (this.value == '{$data['lang_text']['footer_newsletter_sign_up_placeholder']}') {this.value = '';}\" onblur=\"if (this.value == '') {this.value = '{$data['lang_text']['footer_newsletter_sign_up_placeholder']}';}\" value='{$data['lang_text']['footer_newsletter_sign_up_placeholder']}' id='subscribe-email-bottom' name='subscribe-email'>
												<button id='subscribe-submit-bottom' type='submit'>{$data['lang_text']['footer_newsletter_sign_up_button']}</button>
											</fieldset>
										</form>
										<img alt='' class='icon' src='/resources/images/bgr-subscribe-bottom.png'>
									</div>
								</td>
								<td>
									<div>
										<table width="450px" cellspacing="0" table-layout="fixed">
											<tbody>
											<tr>
												<td width="30%" style="padding:11px 0 5px 10px; text-align:center;color:#FFFFFF;">
													<a href="{$fdl_banner["url"]}" style="color:#FFFFFF;"><img src='{$http}://cdn.valuebasket.com/808AA1/vb/resources/images/footer-free-delivery.png'>
													</a>
												</td>									
												<td width="30%" style="padding:9px 0 5px; text-align:center;color:#FFFFFF;">
													<a href="{$url["warranty"]}" style="color:#FFFFFF;">
													<img src='{$http}://cdn.valuebasket.com/808AA1/vb/resources/images/footer-bank_transfer.png'>
														</a>
												</td>
											</tr>
											<tr style="vertical-align:top;">
												<td width="30%" style="padding-left:13px; padding-right:5px; text-align:center;color:#FFFFFF;">
													<a href="{$fdl_banner["url"]}" style="color:#FFFFFF;">
													<font size="2">{$data["lang_text"]["footer_text_free_delivery"]}</font>
													<br><font size="1">{$fdl_banner["limit"]}</font></a>
												</td>
												
												<td width="30%" style="padding:0 9px 0; text-align:center;color:#FFFFFF;">
													<a href="{$url["warranty"]}" style="color:#FFFFFF;">										
														<font size="2">{$data["lang_text"]["footer_text_bank_transfer"]}</font>
														<br><font size="1">{$data["lang_text"]["footer_text_click_for_details"]}</font></a>
												</td>
											</tr>
											</tbody>
										</table>
									</div>
								</td>
							</tr>
						</table>
					</div>
				</td>
			</tr>
			</tbody>
		</table>
EOT;
		break;

	case 'NZ':
	case 'SG':
	case 'US':
	case 'AU':
		$sign_up_bottom = <<<EOT
		<table>
			<tbody>
			<tr>
				<td align="center">
					<div class='subscribe-bottom' align="left"  style="display:vertical-align:middle;">
						<table  border="0" cellspacing="0" cellpadding="0" width="695px" height="110px">
							<tr>
								<td align="center">
									<div class='text' align="center" style=" padding:0px 20px 0px 10px;">
										<p>{$data['lang_text']['footer_newsletter_sign_up_SG']}</p>							
										<form method='post' action='$lang_part/display/view/newsletter_thank_you' name='subscribe-bottom'>
											<fieldset>
												<input type='text' onfocus=\"if (this.value == '{$data['lang_text']['footer_newsletter_sign_up_placeholder']}') {this.value = '';}\" onblur=\"if (this.value == '') {this.value = '{$data['lang_text']['footer_newsletter_sign_up_placeholder']}';}\" value='{$data['lang_text']['footer_newsletter_sign_up_placeholder']}' id='subscribe-email-bottom' name='subscribe-email'>
												<button id='subscribe-submit-bottom' type='submit'>{$data['lang_text']['footer_newsletter_sign_up_button']}</button>
											</fieldset>
										</form>
										<img alt='' class='icon' src='/resources/images/bgr-subscribe-bottom.png'>
									</div>
								</td>
								<td>
									<div>
										<table width="450px" cellspacing="0" table-layout="fixed">
											<tbody>
											<tr>
												<td width="30%" style="padding:11px 0 5px 10px; text-align:center;color:#FFFFFF;">
													<a href="{$fdl_banner["url"]}" style="color:#FFFFFF;"><img src='{$http}://cdn.valuebasket.com/808AA1/vb/resources/images/footer-free-delivery.png'>
													</a>
												</td>
												<td width="30%" style="padding:9px 0 5px; text-align:center;color:#FFFFFF;">
													<a href="{$url["faq"]}" style="color:#FFFFFF;">
													<img src='{$http}://cdn.valuebasket.com/808AA1/vb/resources/images/footer-14day.png'>
														</a>
												</td>
												<td width="30%" style="padding:9px 0 5px; text-align:center;color:#FFFFFF;">
													<a href="{$url["warranty"]}" style="color:#FFFFFF;">
													<img src='{$http}://cdn.valuebasket.com/808AA1/vb/resources/images/footer-36warrantyEN.png'>
														</a>
												</td>
											</tr>
											<tr style="vertical-align:top;">
												<td width="30%" style="padding-left:13px; padding-right:5px; text-align:center;color:#FFFFFF;">
													<a href="{$fdl_banner["url"]}" style="color:#FFFFFF;">
													<font size="2">{$data["lang_text"]["footer_text_free_delivery"]}</font>
													<br><font size="1">{$fdl_banner["limit"]}</font></a>
												</td>
												<td width="30%" style="padding:0 9px 0; text-align:center;color:#FFFFFF;">
													<a href="{$url["faq"]}" style="color:#FFFFFF;">
														<font size="2">{$money_back_title}</font>
														<br><font size="1">{$money_back_detail}</font></a>
												</td>
												<td width="30%" style="padding:0 9px 0; text-align:center;color:#FFFFFF;">
													<a href="{$url["warranty"]}" style="color:#FFFFFF;">										
														<font size="2">{$data["lang_text"]["footer_text_warranty"]}</font>
														<br><font size="1">{$data["lang_text"]["footer_text_warranty_detail"]}</font></a>
												</td>
											</tr>
											</tbody>
										</table>
									</div>
								</td>
							</tr>
						</table>						
					</div>
				</td>
			</tr>
			</tbody>
		</table>
EOT;
		break;
	

	case 'ES':
	case 'PT':
		$sign_up_bottom = <<<EOT
		<table>
			<tbody>
			<tr>
				<td align="center">
					<div class='subscribe-bottom' align="left"  style="display:vertical-align:middle;">
						<table  border="0" cellspacing="0" cellpadding="0"  width="695px" height="110px">
							<tr>
								<td align="center">
									<div class='text' align="center" style=" padding:0px 20px 0px 10px;">
										<p>{$data['lang_text']['footer_newsletter_sign_up']}</p>
										
										<form method='post' action='$lang_part/display/view/newsletter_thank_you' name='subscribe-bottom'>
											<fieldset>
												<input type='text' onfocus=\"if (this.value == '{$data['lang_text']['footer_newsletter_sign_up_placeholder']}') {this.value = '';}\" onblur=\"if (this.value == '') {this.value = '{$data['lang_text']['footer_newsletter_sign_up_placeholder']}';}\" value='{$data['lang_text']['footer_newsletter_sign_up_placeholder']}' id='subscribe-email-bottom' name='subscribe-email'>
												<button id='subscribe-submit-bottom' type='submit'>{$data['lang_text']['footer_newsletter_sign_up_button']}</button>
											</fieldset>
										</form>
										<img alt='' class='icon' src='/resources/images/bgr-subscribe-bottom.png'>
									</div>
								</td>
								<td>
									<div>
										<table width="450px" cellspacing="0" table-layout="fixed">
											<tbody>
											<tr>
												<td width="30%" style="padding:11px 0 5px 10px; text-align:center;color:#FFFFFF;">
													<a href="{$fdl_banner["url"]}" style="color:#FFFFFF;"><img src='{$http}://cdn.valuebasket.com/808AA1/vb/resources/images/footer-free-delivery.png'>
													</a>
												</td>
												<td width="30%" style="padding:9px 0 5px; text-align:center;color:#FFFFFF;">
													<a href="{$url["faq"]}" style="color:#FFFFFF;">
													<img src='{$http}://cdn.valuebasket.com/808AA1/vb/resources/images/footer-14day.png'>
														</a>
												</td>
												<td width="30%" style="padding:9px 0 5px; text-align:center;color:#FFFFFF;">
													<a href="{$url["warranty"]}" style="color:#FFFFFF;">
													<img src='{$http}://cdn.valuebasket.com/808AA1/vb/resources/images/footer-36warrantyES.png'>
														</a>
												</td>
											</tr>
											<tr style="vertical-align:top;">
												<td width="30%" style="padding-left:13px; padding-right:5px; text-align:center;color:#FFFFFF;">
													<a href="{$fdl_banner["url"]}" style="color:#FFFFFF;">
													<font size="2">{$data["lang_text"]["footer_text_free_delivery"]}</font>
													<br><font size="1">{$fdl_banner["limit"]}</font></a>
												</td>
												<td width="30%" style="padding:0 9px 0; text-align:center;color:#FFFFFF;">
													<a href="{$url["faq"]}" style="color:#FFFFFF;">
														<font size="2">{$money_back_title}</font>
														<br><font size="1">{$money_back_detail}</font></a>
												</td>
												<td width="30%" style="padding:0 9px 0; text-align:center;color:#FFFFFF;">
													<a href="{$url["warranty"]}" style="color:#FFFFFF;">										
														<font size="2">{$data["lang_text"]["footer_text_warranty"]}</font>
														<br><font size="1">{$data["lang_text"]["footer_text_warranty_detail"]}</font></a>
												</td>
											</tr>
											</tbody>
										</table>
									</div>
								</td>	
							</tr>
						</table>				
					</div>
				</td>
			</tr>
			</tbody>
		</table>
EOT;
		break;

	case 'BE':
	case 'FR':
		$sign_up_bottom = <<<EOT
		<table>
			<tbody>
			<tr>
				<td align="center">
					<div class='subscribe-bottom' align="left"  style="display:vertical-align:middle;">
						<table  border="0" cellspacing="0" cellpadding="0"  width="695px" height="110px">
							<tr>
								<td align="center">
									<div class='text' align="center" style=" padding:0px 20px 0px 10px;">
										<p>{$data['lang_text']['footer_newsletter_sign_up']}</p>
										
										<form method='post' action='$lang_part/display/view/newsletter_thank_you' name='subscribe-bottom'>
											<fieldset>
												<input type='text' onfocus=\"if (this.value == '{$data['lang_text']['footer_newsletter_sign_up_placeholder']}') {this.value = '';}\" onblur=\"if (this.value == '') {this.value = '{$data['lang_text']['footer_newsletter_sign_up_placeholder']}';}\" value='{$data['lang_text']['footer_newsletter_sign_up_placeholder']}' id='subscribe-email-bottom' name='subscribe-email'>
												<button id='subscribe-submit-bottom' type='submit'>{$data['lang_text']['footer_newsletter_sign_up_button']}</button>
											</fieldset>
										</form>
										<img alt='' class='icon' src='/resources/images/bgr-subscribe-bottom.png'>
									</div>
								</td>
								<td>
									<div>
										<table width="450px" cellspacing="0" table-layout="fixed">
											<tbody>
											<tr>
												<td width="30%" style="padding:11px 0 5px 10px; text-align:center;color:#FFFFFF;">
													<a href="{$fdl_banner["url"]}" style="color:#FFFFFF;"><img src='{$http}://cdn.valuebasket.com/808AA1/vb/resources/images/footer-free-delivery.png'>
													</a>
												</td>
												<td width="30%" style="padding:9px 0 5px; text-align:center;color:#FFFFFF;">
													<a href="{$url["faq"]}" style="color:#FFFFFF;">
													<img src='{$http}://cdn.valuebasket.com/808AA1/vb/resources/images/footer-14day.png'>
														</a>
												</td>
												<td width="30%" style="padding:9px 0 5px; text-align:center;color:#FFFFFF;">
													<a href="{$url["warranty"]}" style="color:#FFFFFF;">
													<img src='{$http}://cdn.valuebasket.com/808AA1/vb/resources/images/footer-36warrantyFR.png'>
														</a>
												</td>
											</tr>
											<tr style="vertical-align:top;">
												<td width="30%" style="padding-left:13px; padding-right:5px; text-align:center;color:#FFFFFF;">
													<a href="{$fdl_banner["url"]}" style="color:#FFFFFF;">
													<font size="2">{$data["lang_text"]["footer_text_free_delivery"]}</font>
													<br><font size="1">{$fdl_banner["limit"]}</font></a>
												</td>
												<td width="30%" style="padding:0 9px 0; text-align:center;color:#FFFFFF;">
													<a href="{$url["faq"]}" style="color:#FFFFFF;">
														<font size="2">{$money_back_title}</font>
														<br><font size="1">{$money_back_detail}</font></a>
												</td>
												<td width="30%" style="padding:0 9px 0; text-align:center;color:#FFFFFF;">
													<a href="{$url["warranty"]}" style="color:#FFFFFF;">										
														<font size="2">{$data["lang_text"]["footer_text_warranty"]}</font>
														<br><font size="1">{$data["lang_text"]["footer_text_warranty_detail"]}</font></a>
												</td>
											</tr>
											</tbody>
										</table>
									</div>
								</td>
							</tr>
						</table>
					</div>
				</td>
			</tr>
			</tbody>
		</table>
EOT;
		break;
		
		case 'RU':
		$sign_up_bottom = <<<EOT
		<table>
			<tbody>
			<tr>
				<td align="center">
					<div class='subscribe-bottom' align="left"  style="display:vertical-align:middle;">
						<table  border="0" cellspacing="0" cellpadding="0"  width="695px" height="110px">
							<tr>
								<td align="center">
									<div class='text' align="center" style=" padding:0px 20px 0px 10px;">
										<p>{$data['lang_text']['footer_newsletter_sign_up']}</p>
										
										<form method='post' action='$lang_part/display/view/newsletter_thank_you' name='subscribe-bottom'>
											<fieldset>
												<input type='text' onfocus=\"if (this.value == '{$data['lang_text']['footer_newsletter_sign_up_placeholder']}') {this.value = '';}\" onblur=\"if (this.value == '') {this.value = '{$data['lang_text']['footer_newsletter_sign_up_placeholder']}';}\" value='{$data['lang_text']['footer_newsletter_sign_up_placeholder']}' id='subscribe-email-bottom' name='subscribe-email'>
												<button id='subscribe-submit-bottom' type='submit'>{$data['lang_text']['footer_newsletter_sign_up_button']}</button>
											</fieldset>
										</form>
										<img alt='' class='icon' src='/resources/images/bgr-subscribe-bottom.png'>
									</div>
								</td>
								<td>
									<div>
										<table width="450px" cellspacing="0" table-layout="fixed">
											<tbody>
											<tr>
												<td width="30%" style="padding:11px 0 5px 10px; text-align:center;color:#FFFFFF;">
													<a href="{$fdl_banner["url"]}" style="color:#FFFFFF;"><img src='{$http}://cdn.valuebasket.com/808AA1/vb/resources/images/footer-free-delivery.png'>
													</a>
												</td>
												<td width="30%" style="padding:9px 0 5px; text-align:center;color:#FFFFFF;">
													<a href="{$url["faq"]}" style="color:#FFFFFF;">
													<img src='{$http}://cdn.valuebasket.com/808AA1/vb/resources/images/footer-14day_ru.png'>
														</a>
												</td>
												<td width="30%" style="padding:9px 0 5px; text-align:center;color:#FFFFFF;">
													<a href="{$url["warranty"]}" style="color:#FFFFFF;">
													<img src='{$http}://cdn.valuebasket.com/808AA1/vb/resources/images/footer-36warranty_ru.png'>
														</a>
												</td>
											</tr>
											<tr style="vertical-align:top;">
												<td width="30%" style="padding-left:13px; padding-right:5px; text-align:center;color:#FFFFFF;">
													<a href="{$fdl_banner["url"]}" style="color:#FFFFFF;">
													<font size="2">{$data["lang_text"]["footer_text_free_delivery"]}</font>
													<br><font size="1">{$fdl_banner["limit"]}</font></a>
												</td>
												<td width="30%" style="padding:0 9px 0; text-align:center;color:#FFFFFF;">
													<a href="{$url["faq"]}" style="color:#FFFFFF;">
														<font size="2">{$money_back_title}</font>
														<br><font size="1">{$money_back_detail}</font></a>
												</td>
												<td width="30%" style="padding:0 9px 0; text-align:center;color:#FFFFFF;">
													<a href="{$url["warranty"]}" style="color:#FFFFFF;">										
														<font size="2">{$data["lang_text"]["footer_text_warranty"]}</font>
														<br><font size="1">{$data["lang_text"]["footer_text_warranty_detail"]}</font></a>
												</td>
											</tr>
											</tbody>
										</table>
									</div>
								</td>
							</tr>
						</table>
					</div>
				</td>
			</tr>
			</tbody>
		</table>
EOT;
		break;
		
		case 'MY':
		$sign_up_bottom = <<<EOT
		<table>
			<tbody>
			<tr>
				<td align="center">
					<div class='subscribe-bottom' align="left"  style="display:vertical-align:middle;">
						<table  border="0" cellspacing="0" cellpadding="0" width="695px" height="110px">
							<tr>
								<td align="center">
									<div class='text' align="center" style=" padding:0px 20px 0px 10px;">
										<p>{$data['lang_text']['footer_newsletter_sign_up_MY']}</p>							
										<form method='post' action='$lang_part/display/view/newsletter_thank_you' name='subscribe-bottom'>
											<fieldset>
												<input type='text' onfocus=\"if (this.value == '{$data['lang_text']['footer_newsletter_sign_up_placeholder']}') {this.value = '';}\" onblur=\"if (this.value == '') {this.value = '{$data['lang_text']['footer_newsletter_sign_up_placeholder']}';}\" value='{$data['lang_text']['footer_newsletter_sign_up_placeholder']}' id='subscribe-email-bottom' name='subscribe-email'>
												<button id='subscribe-submit-bottom' type='submit'>{$data['lang_text']['footer_newsletter_sign_up_button']}</button>
											</fieldset>
										</form>
										<img alt='' class='icon' src='/resources/images/bgr-subscribe-bottom.png'>
									</div>
								</td>
								<td>
									<div>
										<table width="450px" cellspacing="0" table-layout="fixed">
											<tbody>
											<tr>
												<td width="30%" style="padding:11px 0 5px 10px; text-align:center;color:#FFFFFF;">
													<a href="{$fdl_banner["url"]}" style="color:#FFFFFF;"><img src='{$http}://cdn.valuebasket.com/808AA1/vb/resources/images/footer-free-delivery.png'>
													</a>
												</td>
												<td width="30%" style="padding:9px 0 5px; text-align:center;color:#FFFFFF;">
													<a href="{$url["faq"]}" style="color:#FFFFFF;">
													<img src='{$http}://cdn.valuebasket.com/808AA1/vb/resources/images/footer-14day.png'>
														</a>
												</td>
												<td width="30%" style="padding:9px 0 5px; text-align:center;color:#FFFFFF;">
													<a href="{$url["warranty"]}" style="color:#FFFFFF;">
													<img src='{$http}://cdn.valuebasket.com/808AA1/vb/resources/images/footer-36warrantyEN.png'>
														</a>
												</td>
											</tr>
											<tr style="vertical-align:top;">
												<td width="30%" style="padding-left:13px; padding-right:5px; text-align:center;color:#FFFFFF;">
													<a href="{$fdl_banner["url"]}" style="color:#FFFFFF;">
													<font size="2">{$data["lang_text"]["footer_text_free_delivery"]}</font>
													<br><font size="1">{$fdl_banner["limit"]}</font></a>
												</td>
												<td width="30%" style="padding:0 9px 0; text-align:center;color:#FFFFFF;">
													<a href="{$url["faq"]}" style="color:#FFFFFF;">
														<font size="2">{$money_back_title}</font>
														<br><font size="1">{$money_back_detail}</font></a>
												</td>
												<td width="30%" style="padding:0 9px 0; text-align:center;color:#FFFFFF;">
													<a href="{$url["warranty"]}" style="color:#FFFFFF;">										
														<font size="2">{$data["lang_text"]["footer_text_warranty"]}</font>
														<br><font size="1">{$data["lang_text"]["footer_text_warranty_detail"]}</font></a>
												</td>
											</tr>
											</tbody>
										</table>
									</div>
								</td>
							</tr>
						</table>						
					</div>
				</td>
			</tr>
			</tbody>
		</table>
EOT;
		break;
		
		
		case 'PL':
		$sign_up_bottom = <<<EOT
		<table>
			<tbody>
			<tr>
				<td align="center">
					<div class='subscribe-bottom' align="left"  style="display:vertical-align:middle;">
						<table  border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td align="center">
									<div class='text' align="center" style=" padding:0px 20px 0px 10px;">
										<p>{$data['lang_text']['footer_newsletter_sign_up']}</p>
										
										<form method='post' action='$lang_part/display/view/newsletter_thank_you' name='subscribe-bottom'>
											<fieldset>
												<input type='text' onfocus=\"if (this.value == '{$data['lang_text']['footer_newsletter_sign_up_placeholder']}') {this.value = '';}\" onblur=\"if (this.value == '') {this.value = '{$data['lang_text']['footer_newsletter_sign_up_placeholder']}';}\" value='{$data['lang_text']['footer_newsletter_sign_up_placeholder']}' id='subscribe-email-bottom' name='subscribe-email'>
												<button id='subscribe-submit-bottom' type='submit'>{$data['lang_text']['footer_newsletter_sign_up_button']}</button>
											</fieldset>
										</form>
										<img alt='' class='icon' src='/resources/images/bgr-subscribe-bottom.png'>
									</div>
								</td>
								<td>
									<div>
										<table width="450px" cellspacing="0" table-layout="fixed">
											<tbody>
											<tr>
												<td width="30%" style="padding:11px 0 5px 10px; text-align:center;color:#FFFFFF;">
													<a href="{$fdl_banner["url"]}" style="color:#FFFFFF;"><img src='{$http}://cdn.valuebasket.com/808AA1/vb/resources/images/footer-free-delivery.png'>
													</a>
												</td>
												<td width="30%" style="padding:9px 0 5px; text-align:center;color:#FFFFFF;">
													<a href="{$url["faq"]}" style="color:#FFFFFF;">
													<img src='{$http}://cdn.valuebasket.com/808AA1/vb/resources/images/footer-14day.png'>
														</a>
												</td>
												<td width="30%" style="padding:9px 0 5px; text-align:center;color:#FFFFFF;">
													<a href="{$url["warranty"]}" style="color:#FFFFFF;">
													<img src='{$http}://cdn.valuebasket.com/808AA1/vb/resources/images/footer-36warrantyPL.png'>
														</a>
												</td>
											</tr>
											<tr style="vertical-align:top;">
												<td width="30%" style="padding-left:13px; padding-right:5px; text-align:center;color:#FFFFFF;">
													<a href="{$fdl_banner["url"]}" style="color:#FFFFFF;">
													<font size="2">{$data["lang_text"]["footer_text_free_delivery"]}</font>
													<br><font size="1">{$fdl_banner["limit"]}</font></a>
												</td>
												<td width="30%" style="padding:0 9px 0; text-align:center;color:#FFFFFF;">
													<a href="{$url["faq"]}" style="color:#FFFFFF;">
														<font size="2">{$money_back_title}</font>
														<br><font size="1">{$money_back_detail}</font></a>
												</td>
												<td width="30%" style="padding-left:9px; padding-right:5px; text-align:center;color:#FFFFFF;">
													<a href="{$url["warranty"]}" style="color:#FFFFFF;">										
														<font size="2">{$data["lang_text"]["footer_text_warranty"]}</font>
														<br><font size="1">{$data["lang_text"]["footer_text_warranty_detail"]}</font></a>
												</td>
											</tr>
											</tbody>
										</table>
									</div>
								</td>
							</tr>
						</table>
					</div>
				</td>
			</tr>
			</tbody>
		</table>
EOT;
		break;

	// using the same as US for default
	case 'CH':
	case 'FI':
	case 'GB':
	case 'HK':
	case 'IE':
	case 'MT':
	case 'IT':
		$sign_up_bottom = <<<EOT
		<table>
			<tbody>
			<tr>
				<td align="center">
					<div class='subscribe-bottom' align="left"  style="display:vertical-align:middle;">
						<table  border="0" cellspacing="0" cellpadding="0"  width="695px" height="110px">
							<tr>
								<td align="center">
									<div class='text' align="center" style="padding:0px 20px 0px 10px;">
										<p>{$data['lang_text']['footer_newsletter_sign_up']}</p>
										
										<form method='post' action='$lang_part/display/view/newsletter_thank_you' name='subscribe-bottom'>
											<fieldset>
												<input type='text' onfocus=\"if (this.value == '{$data['lang_text']['footer_newsletter_sign_up_placeholder']}') {this.value = '';}\" onblur=\"if (this.value == '') {this.value = '{$data['lang_text']['footer_newsletter_sign_up_placeholder']}';}\" value='{$data['lang_text']['footer_newsletter_sign_up_placeholder']}' id='subscribe-email-bottom' name='subscribe-email'>
												<button id='subscribe-submit-bottom' type='submit'>{$data['lang_text']['footer_newsletter_sign_up_button']}</button>
											</fieldset>
										</form>
										<img alt='' class='icon' src='/resources/images/bgr-subscribe-bottom.png'>
									</div>
								</td>
								<td>
									<div>
										<table width="450px" cellspacing="0" table-layout="fixed">
											<tbody>
											<tr>
												<td width="30%" style="padding:11px 0 5px 10px; text-align:center;color:#FFFFFF;">
													<a href="{$fdl_banner["url"]}" style="color:#FFFFFF;"><img src='{$http}://cdn.valuebasket.com/808AA1/vb/resources/images/footer-free-delivery.png'>
													</a>
												</td>
												<td width="30%" style="padding:9px 0 5px; text-align:center;color:#FFFFFF;">
													<a href="{$url["faq"]}" style="color:#FFFFFF;">
													<img src='{$http}://cdn.valuebasket.com/808AA1/vb/resources/images/footer-14day.png'>
														</a>
												</td>
												<td width="30%" style="padding:9px 0 5px; text-align:center;color:#FFFFFF;">
													<a href="{$url["warranty"]}" style="color:#FFFFFF;">
													<img src="{$http}://cdn.valuebasket.com/808AA1/vb/resources/images/footer-36warranty{$capital_lang_id}.png">
														</a>
												</td>
											</tr>
											<tr style="vertical-align:top;">
												<td width="30%" style="padding-left:13px; padding-right:5px; text-align:center;color:#FFFFFF;">
													<a href="{$fdl_banner["url"]}" style="color:#FFFFFF;">
													<font size="2">{$data["lang_text"]["footer_text_free_delivery"]}</font>
													<br><font size="1">{$fdl_banner["limit"]}</font></a>
												</td>
												<td width="30%" style="padding:0 9px 0; text-align:center;color:#FFFFFF;">
													<a href="{$url["faq"]}" style="color:#FFFFFF;">
														<font size="2">{$money_back_title}</font>
														<br><font size="1">{$money_back_detail}</font></a>
												</td>
												<td width="30%" style="padding-left:9px; padding-right:5px; text-align:center;color:#FFFFFF;">
													<a href="{$url["warranty"]}" style="color:#FFFFFF;">										
														<font size="2">{$data["lang_text"]["footer_text_warranty"]}</font>
														<br><font size="1">{$data["lang_text"]["footer_text_warranty_detail"]}</font></a>
												</td>
											</tr>
											</tbody>
										</table>
									</div>
								</td>
							</tr>
						</table>
					</div>
				</td>
			</tr>
			</tbody>
		</table>
EOT;
		break;
	default:
		$sign_up_bottom = <<<EOT
		<table>
			<tbody>
			<tr>
				<td align="center">
					<div class='subscribe-bottom' align="left"  style="display:vertical-align:middle;">
						<table  border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td align="center">
									<div class='text' align="center" style=" padding:0px 20px 0px 10px;">
										<p>{$data['lang_text']['footer_newsletter_sign_up']}</p>
										
										<form method='post' action='$lang_part/display/view/newsletter_thank_you' name='subscribe-bottom'>
											<fieldset>
												<input type='text' onfocus=\"if (this.value == '{$data['lang_text']['footer_newsletter_sign_up_placeholder']}') {this.value = '';}\" onblur=\"if (this.value == '') {this.value = '{$data['lang_text']['footer_newsletter_sign_up_placeholder']}';}\" value='{$data['lang_text']['footer_newsletter_sign_up_placeholder']}' id='subscribe-email-bottom' name='subscribe-email'>
												<button id='subscribe-submit-bottom' type='submit'>{$data['lang_text']['footer_newsletter_sign_up_button']}</button>
											</fieldset>
										</form>
										<img alt='' class='icon' src='/resources/images/bgr-subscribe-bottom.png'>
									</div>
								</td>
								<td>
									<div>
										<table width="450px" cellspacing="0" table-layout="fixed">
											<tbody>
											<tr>
												<td width="30%" style="padding:11px 0 5px 10px; text-align:center;color:#FFFFFF;">
													<a href="{$fdl_banner["url"]}" style="color:#FFFFFF;"><img src='{$http}://cdn.valuebasket.com/808AA1/vb/resources/images/footer-free-delivery.png'>
													</a>
												</td>
												<td width="30%" style="padding:9px 0 5px; text-align:center;color:#FFFFFF;">
													<a href="{$url["faq"]}" style="color:#FFFFFF;">
													<img src='{$http}://cdn.valuebasket.com/808AA1/vb/resources/images/footer-14day.png'>
														</a>
												</td>
												<td width="30%" style="padding:9px 0 5px; text-align:center;color:#FFFFFF;">
													<a href="{$url["warranty"]}" style="color:#FFFFFF;">
													<img src='{$http}://cdn.valuebasket.com/808AA1/vb/resources/images/footer-36warrantyEN.png'>
														</a>
												</td>
											</tr>
											<tr style="vertical-align:top;">
												<td width="30%" style="padding-left:13px; padding-right:5px; text-align:center;color:#FFFFFF;">
													<a href="{$fdl_banner["url"]}" style="color:#FFFFFF;">
													<font size="2">{$data["lang_text"]["footer_text_free_delivery"]}</font>
													<br><font size="1">{$fdl_banner["limit"]}</font></a>
												</td>
												<td width="30%" style="padding:0 9px 0; text-align:center;color:#FFFFFF;">
													<a href="{$url["faq"]}" style="color:#FFFFFF;">
														<font size="2">{$money_back_title}</font>
														<br><font size="1">{$money_back_detail}</font></a>
												</td>
												<td width="30%" style="padding-left:9px; padding-right:5px; text-align:center;color:#FFFFFF;">
													<a href="{$url["warranty"]}" style="color:#FFFFFF;">										
														<font size="2">{$data["lang_text"]["footer_text_warranty"]}</font>
														<br><font size="1">{$data["lang_text"]["footer_text_warranty_detail"]}</font></a>
												</td>
											</tr>
											</tbody>
										</table>
									</div>
								</td>
							</tr>
						</table>
					</div>
				</td>
			</tr>
			</tbody>
		</table>
EOT;
		break;
}

// if (PLATFORMCOUNTRYID == 'SG')
// {
// $sign_up_bottom = "<div class='subscribe-bottom'>
// 		<div class='text'>
// 		" . $data['lang_text']['footer_newsletter_sign_up_SG'] . "
// 		</div>
// 		<form method='post' action='" . $lang_part . "/display/view/newsletter_thank_you' name='subscribe-bottom'>
// 		<fieldset>
// 			<input type='text' onfocus=\"if (this.value == '" . $data['lang_text']['footer_newsletter_sign_up_placeholder'] . "') {this.value = '';}\" onblur=\"if (this.value == '') {this.value = '" . $data['lang_text']['footer_newsletter_sign_up_placeholder'] . "';}\" value='" . $data['lang_text']['footer_newsletter_sign_up_placeholder'] . "' id='subscribe-email-bottom' name='subscribe-email'>
// 			<button id='subscribe-submit-bottom' type='submit'>" . $data['lang_text']['footer_newsletter_sign_up_button'] . "</button>
// 			</fieldset>
// 		</form>
// 		<img alt='' class='icon' src='/resources/images/bgr-subscribe-bottom.png'>
// 	</div>";
// }
// else
// {
// $sign_up_bottom = "<div class='subscribe-bottom'>
// 		<div class='text'>
// 		" . $data['lang_text']['footer_newsletter_sign_up'] . "
// 		</div>
// 		<form method='post' action='" . $lang_part . "/display/view/newsletter_thank_you' name='subscribe-bottom'>
// 		<fieldset>
// 			<input type='text' onfocus=\"if (this.value == '" . $data['lang_text']['footer_newsletter_sign_up_placeholder'] . "') {this.value = '';}\" onblur=\"if (this.value == '') {this.value = '" . $data['lang_text']['footer_newsletter_sign_up_placeholder'] . "';}\" value='" . $data['lang_text']['footer_newsletter_sign_up_placeholder'] . "' id='subscribe-email-bottom' name='subscribe-email'>
// 			<button id='subscribe-submit-bottom' type='submit'>" . $data['lang_text']['footer_newsletter_sign_up_button'] . "</button>
// 			</fieldset>
// 		</form>
// 		<img alt='' class='icon' src='/resources/images/bgr-subscribe-bottom.png'>
// 	</div>";
// }
$this->tbswrapper->tbsMergeField('back_url', $back_url);
$this->tbswrapper->tbsMergeField('currency_str', $currency_str);
$this->tbswrapper->tbsMergeField('domain', $domain);
$this->tbswrapper->tbsMergeField('cdn_url', base_cdn_url());
$this->tbswrapper->tbsMergeField('warranty', $warranty);
$this->tbswrapper->tbsMergeField('money_back', $money_back);
$this->tbswrapper->tbsMergeField('sign_up_bottom', $sign_up_bottom);
$this->tbswrapper->tbsMergeField('white_space', $white_space);
$this->tbswrapper->tbsMergeField('http', $http);
if (get_lang_id() == "en")
{
	$g_plus_link = $http . "://plus.google.com/104457367954785921390";
	$facebook_link = $http . "://www.facebook.com/ValueBasket";
	$youtube_link = $http . "://www.youtube.com/user/ValueBasketTV?feature=creators_cornier-%2F%2Fs.ytimg.com%2Fyt%2Fimg%2Fcreators_corner%2FYouTube%2Fyoutube_32x32.png";
	$twitter_link = $http . "://www.twitter.com/ValueBasket";
}
else if (get_lang_id() == "es")
{
	$g_plus_link = "";
	$facebook_link = $http . "://www.facebook.com/valuebasketespana";
	$youtube_link = $http . "://www.youtube.com/user/ValueBasketTV?feature=creators_cornier-%2F%2Fs.ytimg.com%2Fyt%2Fimg%2Fcreators_corner%2FYouTube%2Fyoutube_32x32.png";
	$twitter_link = "";
}
else if (get_lang_id() == "it")
{
	$g_plus_link = "";
	$facebook_link = $http . "://www.facebook.com/valuebasketit";
	$youtube_link = $http . "://www.youtube.com/user/ValueBasketTV?feature=creators_cornier-%2F%2Fs.ytimg.com%2Fyt%2Fimg%2Fcreators_corner%2FYouTube%2Fyoutube_32x32.png";
	$twitter_link = "";
}
else if (get_lang_id() == "pl")
{
	$g_plus_link = "";
	$facebook_link = $http . "://www.facebook.com/pages/valuebasketpl/1423772331250696?fref=nf";
	$youtube_link = $http . "://www.youtube.com/user/ValueBasketTV?feature=creators_cornier-%2F%2Fs.ytimg.com%2Fyt%2Fimg%2Fcreators_corner%2FYouTube%2Fyoutube_32x32.png";
	$twitter_link = "";
}
else
{
	$g_plus_link = "";
	$facebook_link = "";
	$youtube_link = $http . "://www.youtube.com/user/ValueBasketTV?feature=creators_cornier-%2F%2Fs.ytimg.com%2Fyt%2Fimg%2Fcreators_corner%2FYouTube%2Fyoutube_32x32.png";
	$twitter_link = "";
}
$this->tbswrapper->tbsMergeField('google_plus_icon', $g_plus_link);
$this->tbswrapper->tbsMergeField('facebook_icon', $facebook_link);
$this->tbswrapper->tbsMergeField('youtube_icon', $youtube_link);
$this->tbswrapper->tbsMergeField('twitter_icon', $twitter_link);

if ($_SESSION['user_agent'] == 'mobile')
{
	if (isset($_SESSION['init_site_mode_session']) && ($_SESSION['init_site_mode_session'] === TRUE))
		$this->tbswrapper->tbsMergeField('init_site_mode_session', 'T');
	else
		$this->tbswrapper->tbsMergeField('init_site_mode_session', '');

	$this->tbswrapper->tbsMergeField('mobile_url', $http.'://m.valuebasket.com' . ($lang_country_pair != '' ? '/' . $lang_country_pair : ''));
}
else
{
	$this->tbswrapper->tbsMergeField('mobile_url', '');
	$this->tbswrapper->tbsMergeField('init_site_mode_session', '');
}

echo $this->tbswrapper->tbsRender();
?>
