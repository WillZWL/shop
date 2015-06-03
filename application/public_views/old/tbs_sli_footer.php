<?php
include_once(APPPATH."helpers/string_helper.php");

$this->tbswrapper->tbsLoadTemplate('resources/template/sli_footer.html', '', '', $data['lang_text']);

$fdl_banner["url"] = base_url().'display/view/shipping';
$mb_banner["url"] = base_url().'display/view/faq';
$support_banner["url"] = base_url().'contact';
if($free_delivery_limit > 0)
{
	$fdl_banner["limit"] = $data['lang_text']['footer_text_for_order_above'].$PLATFORMCURRSIGN." ".$free_delivery_limit;
}
else
{
	$fdl_banner["limit"] = $data['lang_text']['default_text_for_all_orders']."<sup>*</sup>";
}
$menu_script = file_get_contents(VIEWPATH . "template/menu/".get_lang_id()."/footer_menu_".strtolower(PLATFORMID).".html", true);
$this->tbswrapper->tbsMergeField('menu', $menu_script);

$this->tbswrapper->tbsMergeField('fdl_banner', $fdl_banner);
$this->tbswrapper->tbsMergeField('mb_banner', $mb_banner);
$this->tbswrapper->tbsMergeField('support_banner', $support_banner);



$sitemap = array("url"=>base_url(), "display_name"=>"Sitemap");
$aboutus = array("url"=>base_url().'display/view/about_us', "display_name"=>"About Us");
$terms = array("url"=>base_url().'display/view/conditions_of_use', "display_name"=>"Conditions of Use");
$policy = array("url"=>base_url().'display/view/privacy_policy', "display_name"=>"Privacy Policy");

$this->tbswrapper->tbsMergeField('base_url', base_url());
$this->tbswrapper->tbsMergeField('sitemap', $sitemap);
$this->tbswrapper->tbsMergeField('policy', $policy);
$this->tbswrapper->tbsMergeField('terms', $terms);
$this->tbswrapper->tbsMergeField('aboutus', $aboutus);
$pf = array();

foreach($platform_list AS $key=>$obj)
{
	$pf[$key]['country_id'] = $obj->get_id();
	$pf[$key]['country_name'] = $obj->get_name();
	$platform_currency[$obj->get_id()] = $obj->get_currency_id();
	if($obj->get_id() == PLATFORMCOUNTRYID)
	{
		$pf[$key]['selected'] = 'SELECTED';
	}
	else
	{
		
		$pf[$key]['selected'] = '';
	}
}
$this->tbswrapper->tbsMergeBlock('platform_list', $pf);

$currency_str = json_encode($platform_currency);
$back_url = urlencode(str_replace(site_url(), site_url().$_SESSION["domain_platform"]["platform_country_id"].'/', current_url()).($_SERVER["QUERY_STRING"] ? "?".$_SERVER["QUERY_STRING"] : ""));
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

$this->tbswrapper->tbsMergeField('back_url', $back_url);
$this->tbswrapper->tbsMergeField('currency_str', $currency_str);
$this->tbswrapper->tbsMergeField('domain', $domain);
$this->tbswrapper->tbsMergeField('cdn_url', base_cdn_url());
echo $this->tbswrapper->tbsRender();

?>