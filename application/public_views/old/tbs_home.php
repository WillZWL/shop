<?php
$this->load->helper('tbswrapper');
$this->tbswrapper = new Tbswrapper();
if (PLATFORMCOUNTRYID == 'PH')
{
    $data['lang_text']['reason_youtube_video_id'] = "Zc0FfoiNQJc";
}

$this->tbswrapper->tbsLoadTemplate('resources/template/home.html', '', '', $data['lang_text']);

if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) 
{
    $http = "https";
}
else
{
    $http = "http";
}
$this->tbswrapper->tbsMergeField('http', $http);

$bs_list = $la_list = $cl_list = array();
$banner_url["right"] = base_url().'display/view/shipping';
$banner_url["left"] = base_url().'Tablets/cat/view/6';
$this->tbswrapper->tbsMergeField('banner_url', $banner_url);
if($data["best_seller"])
{
    foreach($data["best_seller"] AS $key=>$value)
    {
        $bs_list[$key]["sku"] = $value["sku"];
        $bs_list[$key]["prod_name"] = $value["prod_name"];
        $bs_list[$key]["listing_status"] = $value["listing_status"];
        $bs_list[$key]["stock_status"] =  $value["stock_status"];
        $bs_list[$key]["price"] = platform_curr_format(PLATFORMID, $value["price"]);
        $bs_list[$key]["rrp_price"] = platform_curr_format(PLATFORMID, $value["rrp_price"]);
        $bs_list[$key]["discount"] = $value["discount"];
        $bs_list[$key]["prod_url"] = $value["prod_url"];
        $bs_list[$key]["short_desc"] = $value["short_desc"];
        $bs_list[$key]["image"] = get_image_file($value["image_ext"], "m", $value["sku"]);
        if (($bs_list[$key]["listing_status"] == 'I') || ($bs_list[$key]["listing_status"] == 'P') || ($bs_list[$key]["listing_status"] == 'A'))
        {
            $bs_list[$key]["add_cart"] = base_url()."cart/add_item/".$value["sku"];
            $bs_list[$key]["css_stock_status"] = "in_stock";
        }
        else
        {
            $bs_list[$key]["css_stock_status"] = "out_stock";
        }
    }
}
if($data["latest_arrival"])
{
    foreach($data["latest_arrival"] AS $key=>$value)
    {
        $la_list[$key]["sku"] = $value["sku"];
        $la_list[$key]["prod_name"] = $value["prod_name"];
        $la_list[$key]["listing_status"] = $value["listing_status"];
        $la_list[$key]["stock_status"] =  $value["stock_status"];
        $la_list[$key]["price"] = platform_curr_format(PLATFORMID, $value["price"]);
        $la_list[$key]["rrp_price"] = platform_curr_format(PLATFORMID, $value["rrp_price"]);
        $la_list[$key]["discount"] = $value["discount"];
        $la_list[$key]["prod_url"] = $value["prod_url"];
        $la_list[$key]["short_desc"] = $value["short_desc"];
        $la_list[$key]["image"] = get_image_file($value["image_ext"], "m", $value["sku"]);
        if (($la_list[$key]["listing_status"] == 'I') || ($la_list[$key]["listing_status"] == 'P'))
        {
            $la_list[$key]["add_cart"] = base_url()."cart/add_item/".$value["sku"];
            $la_list[$key]["css_stock_status"] = "in_stock";
        }
        else
        {
            $la_list[$key]["css_stock_status"] = "out_stock";
        }
    }
}

if($data["clearance_product"])
{
    foreach($data["clearance_product"] AS $key=>$value)
    {
        $cl_list[$key]["sku"] = $value["sku"];
        $cl_list[$key]["prod_name"] = $value["prod_name"];
        $cl_list[$key]["listing_status"] = $value["listing_status"];
        $cl_list[$key]["stock_status"] =  $value["stock_status"];
        $cl_list[$key]["price"] = platform_curr_format(PLATFORMID, $value["price"]);
        $cl_list[$key]["rrp_price"] = platform_curr_format(PLATFORMID, $value["rrp_price"]);
        $cl_list[$key]["discount"] = $value["discount"];
        $cl_list[$key]["prod_url"] = $value["prod_url"];
        $cl_list[$key]["short_desc"] = $value["short_desc"];
        $cl_list[$key]["image"] = get_image_file($value["image_ext"], "m", $value["sku"]);
        if (($cl_list[$key]["listing_status"] == 'I') || ($cl_list[$key]["listing_status"] == 'P'))
        {
            $cl_list[$key]["add_cart"] = base_url()."cart/add_item/".$value["sku"];
            $cl_list[$key]["css_stock_status"] = "in_stock";
        }
        else
        {
            $cl_list[$key]["css_stock_status"] = "out_stock";
        }
    }
}

$this->tbswrapper->tbsMergeBlock('bs_list', $bs_list);
$this->tbswrapper->tbsMergeBlock('la_list', $la_list);
$this->tbswrapper->tbsMergeBlock('cl_list', $cl_list);
$this->tbswrapper->tbsMergeField('base_url', base_url());
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
$this->tbswrapper->tbsMergeField('cdn_url', base_cdn_url());
$this->tbswrapper->tbsMergeField('lang_part', lang_part());
$this->tbswrapper->tbsMergeField('show_discount_text', $data['show_discount_text']);

$contry_specified_subscription_pic = array('RU', 'PL');

if(in_array(PLATFORMCOUNTRYID, $contry_specified_subscription_pic))
{
    $subscription_pic = "bgr-subscribe-top_".PLATFORMCOUNTRYID;
}
else
{
    $subscription_pic = "bgr-subscribe-top";
}


if (PLATFORMCOUNTRYID == 'SG' || PLATFORMCOUNTRYID == 'NZ' || PLATFORMCOUNTRYID == 'AU' || PLATFORMCOUNTRYID == 'US')
{
    $sign_up = "<div class='subscribe-top'>" 
                . $data['lang_text']['index_newsletter_sign_up_SG']  . "
                <form method='POST' action='" . lang_part() . "/display/view/newsletter_thank_you' name='subscribe-top'>
                    <fieldset>
                        <input type='text' onfocus=\"if (this.value == '" . $data['lang_text']['footer_newsletter_sign_up_placeholder'] . "') {this.value = '';}\" onblur=\"if (this.value == '') {this.value = '" . $data['lang_text']['footer_newsletter_sign_up_placeholder'] . "';}\" value='" . $data['lang_text']['footer_newsletter_sign_up_placeholder'] . "' id='subscribe-email' name='subscribe-email'>
                        <button id='subscribe-submit' type='submit'>" . $data['lang_text']['footer_newsletter_sign_up_button'] . "</button>
                    </fieldset>
                </form>
                <img alt='' class='icon' src='/resources/images/".$subscription_pic.".png'>
            </div>";
}
elseif (PLATFORMCOUNTRYID == 'MY')
{
        $sign_up = "<div class='subscribe-top'>" 
                . $data['lang_text']['index_newsletter_sign_up_MY']  . "
                <form method='POST' action='" . lang_part() . "/display/view/newsletter_thank_you' name='subscribe-top'>
                    <fieldset>
                        <input type='text' onfocus=\"if (this.value == '" . $data['lang_text']['footer_newsletter_sign_up_placeholder'] . "') {this.value = '';}\" onblur=\"if (this.value == '') {this.value = '" . $data['lang_text']['footer_newsletter_sign_up_placeholder'] . "';}\" value='" . $data['lang_text']['footer_newsletter_sign_up_placeholder'] . "' id='subscribe-email' name='subscribe-email'>
                        <button id='subscribe-submit' type='submit'>" . $data['lang_text']['footer_newsletter_sign_up_button'] . "</button>
                    </fieldset>
                </form>
                <img alt='' class='icon' src='/resources/images/".$subscription_pic.".png'>
            </div>";
}
elseif (PLATFORMCOUNTRYID == 'PH')
{
    $sign_up = "<div style='float: right;'>
                    <img src='/resources/images/why_buy_from_us.jpg' border='0' class='icon'>
                </div>";
    
}
elseif (PLATFORMCOUNTRYID == 'RU')
{
    $sign_up = "<div style='float: right;'>
                    <a href='/hesk_ru/knowledgebase.php?article=19' target='_blank'><img src='/resources/images/".$subscription_pic.".jpg' border='0' class='icon'></a>
                </div>";
    
}




elseif (PLATFORMCOUNTRYID == 'PL')
{
    $sign_up = "<div style='float: right;'>
                    <a href='/display/view/faq' target='_blank'><img src='/resources/images/".$subscription_pic.".jpg' border='0' class='icon'></a>
                </div>";
}




else
{
    $sign_up = "<div class='subscribe-top'>" 
                . $data['lang_text']['index_newsletter_sign_up']  . "
                <form method='POST' action='" . lang_part() . "/display/view/newsletter_thank_you' name='subscribe-top'>
                    <fieldset>
                        <input type='text' onfocus=\"if (this.value == '" . $data['lang_text']['footer_newsletter_sign_up_placeholder'] . "') {this.value = '';}\" onblur=\"if (this.value == '') {this.value = '" . $data['lang_text']['footer_newsletter_sign_up_placeholder'] . "';}\" value='" . $data['lang_text']['footer_newsletter_sign_up_placeholder'] . "' id='subscribe-email' name='subscribe-email'>
                        <button id='subscribe-submit' type='submit'>" . $data['lang_text']['footer_newsletter_sign_up_button'] . "</button>
                    </fieldset>
                </form>
                <img alt='' class='icon' src='/resources/images/".$subscription_pic.".png'>
            </div>";
}
$this->tbswrapper->tbsMergeField('sign_up', $sign_up);


$selected_country = "";
foreach($platform_list AS $key=>$obj)
{
    $pf[$key]['country_id'] = $obj->get_language_id() . '_' . $obj->get_id();
    $pf[$key]['country_name'] = $obj->get_name();
    $platform_currency[$obj->get_id()] = $obj->get_currency_id();
    if($obj->get_id() == PLATFORMCOUNTRYID)
    {
        $pf[$key]['selected'] = 'SELECTED';
        $selected_country = $obj->get_id();
    }
    else
    {
        
        $pf[$key]['selected'] = '';
    }
}

$this->tbswrapper->tbsMergeField('selected_country', $selected_country);


echo $this->tbswrapper->tbsRender();
?>
