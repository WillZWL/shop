
<?php
$this->tbswrapper->tbsLoadTemplate('resources/template/header.html', '', '', $data['lang_text']);

$http = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
$this->tbswrapper->tbsMergeField('http', $http);

// search engine selection
$search_engine = '';
//if ((strtolower(PLATFORMCOUNTRYID) == 'es') || (strtolower(PLATFORMCOUNTRYID) == 'gb') || (strtolower(PLATFORMCOUNTRYID) == 'ph'))
{
	$search_engine = 'searchspring';
	$search_url = implode('.', get_domain());

	switch (strtolower(PLATFORMCOUNTRYID))
	{
		case 'au' : $searchspring_site_id = 'vm182w'; break;
		case 'be' : $searchspring_site_id = 'nbc1w6'; break;
		case 'fi' : $searchspring_site_id = 'mlcmi6'; break;
		case 'fr' : $searchspring_site_id = 'hi80z4'; break;
		case 'gb' : $searchspring_site_id = 'z7q84w'; break;
		case 'hk' : $searchspring_site_id = 'jx3nzf'; break;
		case 'ie' : $searchspring_site_id = 'evot87'; break;
		case 'my' : $searchspring_site_id = '9amw38'; break;
		case 'nz' : $searchspring_site_id = 'tbp112'; break;
		case 'sg' : $searchspring_site_id = 'zkx7z6'; break;
		case 'es' : $searchspring_site_id = 'kfwha5'; break;
		case 'us' : $searchspring_site_id = 'oap9ds'; break;
		case 'ph' : $searchspring_site_id = 'ule0ej'; break;
		case 'it' : $searchspring_site_id = 'zk4kkc'; break;
		case 'mt' : $searchspring_site_id = 'aoi27k'; break;
		case 'ch' : $searchspring_site_id = 'jmx5qq'; break;
		case 'pt' : $searchspring_site_id = 'lom5se'; break;
		case 'ru' : $searchspring_site_id = 'oocsgg'; break;
		case 'pl' : $searchspring_site_id = 'cuaf4k'; break;
		case 'mx' : $searchspring_site_id = 'np9uum'; break;
		case 'nl' : $searchspring_site_id = 'cuaf4k'; break;
		case 'se' : $searchspring_site_id = 'cuaf4k'; break;

		default   : $searchspring_site_id = '';
	}
	$this->tbswrapper->tbsMergeField('searchspring_site_id', $searchspring_site_id);
}
/*
else
{
	$search_engine = 'sli';
	$search_url = 'search.' . DOMAIN;
}
*/
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
	case 'mx' : $kayako_proactive_chat = '';   $kayako_site_badge = ''; break;
	case 'nl' : $kayako_proactive_chat = '';   $kayako_site_badge = ''; break;
	case 'se' : $kayako_proactive_chat = '';   $kayako_site_badge = ''; break;


	default   : $kayako_proactive_chat = ''; $kayako_site_badge = '';
}

$this->tbswrapper->tbsMergeField('kayako_proactive_chat', $kayako_proactive_chat);
$this->tbswrapper->tbsMergeField('kayako_site_badge', $kayako_site_badge);

$search_lang_country_pair = "";

#SBF 3551 - es Valueblog
$blog_url = "/blog";
if(PLATFORMID == "WEBES" || PLATFORMID == "WEBPT" || PLATFORMID == "WEBMX" )
{
	$blog_url = "/blog_".strtolower(get_lang_id());
}

$myaccount = array("url"=>base_url().'myaccount', "display_name"=>$data['lang_text']['default_menu_my_account']);
$shipping = array("url"=>base_url().'display/view/shipping', "display_name"=>$data['lang_text']['default_menu_shipping']);
$warranty = array("url"=>base_url().'display/view/warranty', "display_name"=>$data['lang_text']['default_menu_warranty']);
$contactus = array("url"=>base_url().'contact', "display_name"=>$data['lang_text']['default_menu_contact_us']);
$faq = array("url"=>base_url().'display/view/faq', "display_name"=>$data['lang_text']['default_menu_help']);
$blog = array("url"=>$blog_url, "display_name"=>$data['lang_text']['default_menu_blog']);
$bulk_sales = array("url"=>'/display/view/bulk_sales', "display_name"=>$data['lang_text']['default_menu_bulk_sales']);
$on_sale = array("url"=>base_url().'search/search_by_ss?q=#/?_=1&filter.clearance.low=100&filter.clearance.high=200&page=1&sort.create_date=desc', "display_name"=>$data['lang_text']['default_menu_on_sale']);
$fdl_banner["url"] = base_url().'display/view/shipping';

$contry_specified_shipping_logo = array('AU','CH','FI','GB','HK','IE','MT','MY','NZ','PH','PL','SG','US','ES','PT','BE','FR','IT','RU','MX');

if(in_array(PLATFORMCOUNTRYID, $contry_specified_shipping_logo))
{
	$shipping_logo = get_lang_id()."_".strtolower(PLATFORMCOUNTRYID);
}
else
{
	$shipping_logo = get_lang_id();
}

$fdl_banner["36_month_warranty_img"] = base_cdn_url() . "resources/images/36ship_" . $shipping_logo . '.png';

/*
if (get_lang_id() == 'en')
{
	$warranty["url"] = "$http://valuebasket.kayako.com/Knowledgebase/Article/View/37/17/do-you-provide-warranties-with-your-products";
}
else
{
	$warranty["url"] = "$http://www.valuebasket." . get_lang_id() . "/hesk_" . get_lang_id() . "/knowledgebase.php?category=7";
}
*/

if($free_delivery_limit > 0)
{
	$fdl_banner["limit"] = "over ".$PLATFORMCURRSIGN." ".$free_delivery_limit;
}
else
{
	$fdl_banner["limit"] = $data['lang_text']['default_text_for_all_orders'] . "<sup>*</sup>";
}
$checkout = array("url"=>base_url().'review_order');
if($_SESSION["client"])
{
	$logout = array("url"=>base_url().'logout', "display_name"=>$data['lang_text']['default_menu_logout']);
}
$cart["total"] = $cart["item"] = 0;
if($cart_info["cart"])
{
	foreach($cart_info["cart"] AS $key=>$arr)
	{
		$cart["total"] += $arr["total"] + $arr["gst"];
		$cart["item"] += $arr["qty"];
	}
}

#SBF #2441
$allow_bulk_sales = '1';
$bulk_sales_country = 'AU|BE|FI|FR|GB|HK|IE|MY|NZ|PH|SG|ES|US|MT|CH|MX|';
if (strpos($bulk_sales_country, PLATFORMCOUNTRYID) === FALSE)
{
	# Countries that don't offer bulk sales = 0 -- do not show bulk sales in header.
	$allow_bulk_sales = '0';
	$bulk_sales = '';
}

if (PLATFORMCOUNTRYID == 'FR' || PLATFORMCOUNTRYID == 'BE' || PLATFORMCOUNTRYID == 'ES')
{
	# SBF #2425, 2486, #3558, #3618
	$cs_phone_no = '';
}

$cart["total"] = platform_curr_format(PLATFORMID, $cart["total"]);
$this->tbswrapper->tbsMergeField('cart', $cart);

$this->tbswrapper->tbsMergeField('base_url', base_url());
$this->tbswrapper->tbsMergeField('myaccount', $myaccount);
$this->tbswrapper->tbsMergeField('shipping', $shipping);
$this->tbswrapper->tbsMergeField('warranty', $warranty);
$this->tbswrapper->tbsMergeField('contactus', $contactus);
$this->tbswrapper->tbsMergeField('search_engine', $search_engine);
$this->tbswrapper->tbsMergeField('search_url', $search_url);
$this->tbswrapper->tbsMergeField('search_lang_country_pair', $search_lang_country_pair);
$this->tbswrapper->tbsMergeField('PLATFORMCOUNTRYID',PLATFORMCOUNTRYID);
$this->tbswrapper->tbsMergeField('PLATFORMCURRSIGN',PLATFORMCURRSIGN);
$this->tbswrapper->tbsMergeField('language_id',get_lang_id());
$this->tbswrapper->tbsMergeField('logout', $logout);
$this->tbswrapper->tbsMergeField('faq', $faq);
$this->tbswrapper->tbsMergeField('blog', $blog);
$this->tbswrapper->tbsMergeField('bulk_sales', $bulk_sales);
$this->tbswrapper->tbsMergeField('on_sale', $on_sale);
$this->tbswrapper->tbsMergeField('allow_bulk_sales', $allow_bulk_sales);
$this->tbswrapper->tbsMergeField('cs_phone_no', $cs_phone_no);
$this->tbswrapper->tbsMergeField('fdl_banner', $fdl_banner);
$this->tbswrapper->tbsMergeField('checkout', $checkout);

//menu_big_webfi_en.html

$menu_script = file_get_contents(VIEWPATH."template/menu/" . get_lang_id() . "/menu_".$data["header_menu"] . strtolower(PLATFORMID) . ".html", true);
// var_dump($menu_script);
// var_dump($f);

$this->tbswrapper->tbsMergeField('menu', $menu_script);
$this->tbswrapper->tbsMergeField('cdn_url', base_cdn_url());

$contry_specified_logo = array('PL');

if(in_array(PLATFORMCOUNTRYID, $contry_specified_logo))
{
	$logo_name = "logo_".PLATFORMCOUNTRYID;
}
else
{
	$logo_name = "logo";
}

$this->tbswrapper->tbsMergeField('logo_url', base_cdn_url()."resources/images/".$logo_name.".jpg");

//[cdn_url]resources/images/logo.jpg

echo $this->tbswrapper->tbsRender();
?>