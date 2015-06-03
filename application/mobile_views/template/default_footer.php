        <div class="pictograms Rokkitt">
            <ul>
                <li>
                    <a href="<?=$base_url?>display/view/shipping" title="">
                        <img src="<?=$cdn_url?>/resources/mobile/images/free-delivery.png" alt=""/>
                        <strong><?php print $lang_text["footer_text_free_delivery"];?></strong>
                        <span><?php print $lang_text["default_text_for_all_orders"];?>*</span>
                    </a>
                </li>
                <li>
                    <a href="<?=$base_url?>contact" title="">
                        <img src="<?=$cdn_url?>/resources/mobile/images/support.png" alt=""/>
                        <strong><?php print $lang_text["footer_text_support"];?></strong>
                        <span><?php print $lang_text["footer_text_customer_support"];?></span>
                    </a>
                </li>
                <li>
                    <a href="<?=$base_url?>display/view/faq" title="">
                        <img src="<?=$cdn_url?>/resources/mobile/images/money-back.png" alt=""/>
                        <strong><?php print $lang_text["footer_text_money_back"];?></strong>
                        <span><?php print $lang_text["footer_text_money_back_guarantee"];?></span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="subscribe-form orange-gradient Rokkitt">
            <form method="post" action="">
                <fieldset>
                    <legend>Newsletter Subscribe Form</legend>
                    <label><?php print $lang_text["footer_newsletter_sign_up"];?></label>
                    <input type="email" value="" placeholder="<?php print $lang_text["footer_newsletter_sign_up_placeholder"];?>"/>
                    <button type="submit" class="grey-gradient smaller"><?php print $lang_text["footer_newsletter_sign_up_button"];?></button>
                </fieldset>
            </form>
        </div>

        <div class="highlights">
			<br><br>
<!--
            <img src="<?=$cdn_url?>/resources/mobile/images/shop-with-confidence.png" alt=""/>
            <img src="<?=$cdn_url?>/resources/mobile/images/pay-with-confidence.png" alt=""/>
-->
        </div>

		<div class="footer">
            <div class="top">
                <ul>
                    <li><a href="<?=$base_url?>display/view/about_us" title=""><?php print $lang_text["footer_text_about_us"];?></a> / </li>
                    <li><a href="<?=$base_url?>display/view/conditions_of_use" title=""><?php print $lang_text["footer_text_conditions_of_use"];?></a> / </li>
                    <li><a href="<?=$base_url?>display/view/privacy_policy" title=""><?php print $lang_text["footer_text_privacy_policy"];?></a></li>
                    <!-- <li><a href="/blog" title=""><?php print $lang_text["footer_text_blog"];?></a></li> -->
                </ul>
            </div>
            <div class="bottom">
                <form id="" name="" method="post">
                    <fieldset>
                        <legend>Change shipping country form</legend>
                        <label><?php print $lang_text["footer_text_select_shipping_country"];?></label>
                        <select id="footer_custom_country_id" name="footer_custom_country_id" onchange="change_country('<?php print $emptyCart;?>', '<?php print $chk_cart;?>', '<?php print $domain;?>', '<?php print $back_url;?>', '<?php print $site_url;?>', '<?php print $controller_path;?>', '<?php print $query_string;?>', '<?php print $lang_text["cart_change_currency_1"];?>', '<?php print $lang_text["cart_change_currency_2"];?>')">
<?php
$lang_country_pair = '';
foreach($platform_list AS $key => $obj)
{
    if($obj->get_id() != "MX") // Temporary hide Mexico
    {
    	$countryValue = $obj->get_language_id() . '_' . $obj->get_id();
    	$countryName = $obj->get_name();
    	$platform_currency[$obj->get_id()] = $obj->get_currency_id();
    	if ($obj->get_id() == PLATFORMCOUNTRYID)
    	{
    		$selected = "SELECTED";
    		$lang_country_pair = $countryValue;
    	}
    	else
        {
    		$selected = "";
        }
    	print "<option value='" . $countryValue . "%%" . $platform_currency[$obj->get_id()] . "' " . $selected . " data-image=''>" . $countryName . "</option>";
    }
}
?>
                        </select>
                    </fieldset>
                </form>
            </div>
            <div class="smaller" style="padding-bottom:25px"><?php print $lang_text["footer_text_all_rights_reserved"];?></div>

			<span class="sticky-link full-version smaller" onClick="switchSiteMode('full', '<?=$full_site_url . ($lang_country_pair != '' ? '/' . $lang_country_pair : '')?>');"><?php print $lang_text["footer_text_view_full_site"];?></span>
            <a href="javascript:;" class="sticky-link scroll-top smaller" title="Scroll Top"><i class="icon scroll-top">&nbsp;</i></a>
        </div>

		<iframe id='switch_site_mode_iframe' src='' style='display:none' width='0px' height='0px'></iframe>
		<script type="text/javascript" src="/js/switch_site_mode.js"></script>
		<script type="text/javascript">switchSiteMode('mobile', '');</script>