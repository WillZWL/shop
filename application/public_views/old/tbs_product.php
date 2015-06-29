<?php
include 'reevoo_mark.php'; 
$reevoo_mark = new ReevooMark("reevoo_cache", "http://mark.reevoo.com/widgets/offers", "EVB", $data['sku']);

// var_dump($this); die();
// $this->mainproducttest();

$this->load->helper('tbswrapper');
$this->tbswrapper = new Tbswrapper();

include_once('tbs_upselling.php');
$data['need_checkout_button'] = FALSE;
$data['need_show_topbar'] = TRUE;
$upselling_content = generate_upselling_content($this->tbswrapper, $data);

#2583 if the product website_status is 'A' (Arriving), then do not show the 'Free shipping' content which is next to the website_status
if($data['listing_status'] == 'A' || $data['listing_status'] == 'O')
{
    $data['lang_text']['free_shipping'] = '';
}
#SBF2652 since the tbs cannot escape the single quote, then Do the escape here
foreach($data['lang_text'] as $k => $v)
{
    $data['lang_text']["$k"] = htmlspecialchars($v, ENT_QUOTES);
}

$this->tbswrapper->tbsLoadTemplate('resources/template/product.html', '', '', $data['lang_text']);

if (get_lang_id() != 'en')
{
    $formatted_evb = '_' . strtoupper(get_lang_id());
    $this->tbswrapper->tbsMergeField('evb_value', $formatted_evb);
}
else
{
    $this->tbswrapper->tbsMergeField('evb_value', '');
}

$this->tbswrapper->tbsMergeField('lang_id', get_lang_id());
foreach($data['breadcrumb'] AS $key=>$value)
{
    foreach($value AS $name=>$url)
    {
        $breadcrumb[] = array("name"=>$name, "url"=>$url);
    }
}
//#1512 add youtube thumbnail
$youtube_id_w_caption=array();
foreach($data["youtube_id_w_caption"] AS $key=>$value)
{
    $value["id"] = trim($value["id"]);
    if(!empty($value["id"]))
    {
        $youtube_id_w_caption[] = array("id"=>$value["id"], "caption"=>$value["caption"]);
    }
}

$this->tbswrapper->tbsMergeBlock('youtube_id_w_caption', $youtube_id_w_caption);
$this->tbswrapper->tbsMergeBlock('breadcrumb', $breadcrumb);

{ # SBF 1627
    $cash_on_delivery_image = "";
//  $cash_on_delivery_image = $data['cash_on_delivery_image'];
    $this->tbswrapper->tbsMergeField('cash_on_delivery_image', $cash_on_delivery_image);
}

{ # SBF 2190 hotline support images
    $hotline_support_image = "resources/images/points/en/hotline_support.png";

#   SBF #2425, #2486 remove hotlines for FR, ES
    // $hotline_support_countries_list = array('FR', 'BE');
    // if (in_array(PLATFORMCOUNTRYID, $hotline_support_countries_list))
    // {    
    //  $hotline_support_image = "resources/images/points/en/hotline_support_non24hr.png";
    // }
    if (PLATFORMCOUNTRYID == 'ES' || PLATFORMCOUNTRYID == 'FR' || PLATFORMCOUNTRYID == 'BE' || PLATFORMCOUNTRYID == 'RU') 
    {
        $hotline_support_image = '';
    }
    
    $this->tbswrapper->tbsMergeField('hotline_support_image', $hotline_support_image);
}

$default = TURE;
$prod_img_arr = array();
if($data['prod_image'])
{
    foreach($data['prod_image'] AS $key=>$img_arr)
    {
        if($default)
        {
            $default_prod_img['image_icon'] = $img_arr["image_icon"];
            $default_prod_img['image'] = $img_arr["image"];
            $default = FALSE;
        }
        if(count($data['prod_image']) > 1)
        {
            $prod_img_arr[$key]['image_icon'] = $img_arr["image_icon"];
            $prod_img_arr[$key]['image'] = $img_arr["image"];
        }
    }
}
else
{
    $default_prod_img['image_icon'] = '/images/product/imageunavailable_s.jpg';
    $default_prod_img['image'] = '/images/product/imageunavailable_l.jpg';
}
$prod_info['display_name'] = htmlspecialchars($data['prod_name'], ENT_QUOTES);
$prod_info['add_cart'] = base_url()."cart/add_item_qty/".$data['sku'];
$prod_info['price'] = platform_curr_format(PLATFORMID, $data['prod_price']);
$prod_info['prod_rrp_price'] = platform_curr_format(PLATFORMID, $data['prod_rrp_price']);
$prod_info['discount'] = $data['discount'];
$prod_info['stock_status'] = $data["stock_status"];
$prod_info['expected_delivery_date'] = $data["expected_delivery_date"];
$prod_info['website_status_long_text'] = $data["website_status_long_text"];
$prod_info['website_status_short_text'] = $data["website_status_short_text"];
$prod_info['extra_info'] = $data['extra_info'];

$this->tbswrapper->tbsMergeField('sbf5335_img_tag', $data['sbf5335_img_tag']);  # please remember to remove from product.html as well

//sbf5987 Registered post delivery alert message
if($data['prod_price'] <= 100)
{
    $delivery_alert['show'] = 1;
    if(get_lang_id() == 'en')
    {
        $platform_id = PLATFORMID;
        $curr_id = $_SESSION["PLATFORM_CURRENCY"][$platform_id]["currency_id"];
        $curr_id = "USD";
        $delivery_alert_msg = $data['lang_text']['delivery_alert_1'].$curr_id.$data['lang_text']['delivery_alert_2'];
    }
    else
    {
        $delivery_alert_msg = $data['lang_text']['delivery_alert_1'];
    }
    $this->tbswrapper->tbsMergeField('delivery_alert_msg', $delivery_alert_msg );   
}
else
{
    $delivery_alert['show'] = 0;
}
$this->tbswrapper->tbsMergeField('delivery_alert', $delivery_alert);




#SBF 2781 include product banners
$product_banner['show'] = '0';
$base_cdn_url = base_cdn_url();
if($data["product_banner"] && $data["product_banner"]["filepath"])
{
    # only show if db has record and file exists
    $product_banner['show'] = '1';
    if ($data["product_banner"]["target_url"]) 
    {
        $product_banner_target_1 = <<<url_tag
            <a href="{$data["product_banner"]["target_url"]}" target="{$data["product_banner"]["target_type"]}">
url_tag;
        $product_banner_target_2 = "</a>";
    }
    else
    {
        $product_banner_target_1 = "";
        $product_banner_target_2 = "";
    }
    
    $product_banner_img = <<<img_tag
        <div>
            $product_banner_target_1
            <img src="{$base_cdn_url}{$data["product_banner"]["filepath"]}" target='_blank' alt="{$data["product_banner"]["alt_text"]}"/>
            $product_banner_target_2
        </div>
img_tag;
}
$this->tbswrapper->tbsMergeField('product_banner', $product_banner);
$this->tbswrapper->tbsMergeField('product_banner_img', $product_banner_img);

if($data["qty"] > 0)
{
    
    for($i = 1;$i <= $data["qty"] ; $i++)
    {
        $allow_buy_qty[$i]["qty"] = $i;
    }
$this->tbswrapper->tbsMergeBlock('allow_buy_qty', $allow_buy_qty);
}
if (($data["listing_status"] != "I") && ($data["listing_status"] != "P"))
{
    $prod_info["css_stock_status"] = "out_stock";
    $data["add_to_basket"] = NULL;
}
else
{
    $prod_info["css_stock_status"] = "in_stock";
    $data["add_to_basket"] = $data['lang_text']['add_basket'];
}

if($data["listing_status"] == "A")
{
    $prod_info["css_stock_status"] = "in_stock";
    $data["add_to_basket"] = NULL;
}


$fdl_banner["url"] = base_url().'display/view/shipping';
if($free_delivery_limit > 0)
{
    $fdl_banner["limit"] = "over ".$PLATFORMCURRSIGN." ".$free_delivery_limit;
}
else
{
    $fdl_banner["limit"] = "";
}
$this->tbswrapper->tbsMergeField('fdl_banner', $fdl_banner);

//start of product tab
$video = array();
$tab_no = 1;
$default_tab = 'overview';

if($upselling_content)
{
    $upselling['title'] = $data['lang_text']['upselling'];
    $upselling['content'] = $upselling_content;
    if($default_tab == 'upselling')
    {
        $upselling_content['header'] = 'active';
        $upselling_content['block'] = 'style="display:block;"';
    }
    $upselling['tab_no'] = $tab_no;
    $tab_no++;
}

if(($data['youtube_id'] != "") && ($data['video_desc']!= ""))
{
    $video['content'] = $data['video_desc'];
    $video['title'] = $data['lang_text']['video_review'];
    if($data['is_http'])
    {
        $video['youtube'] = "http://www.youtube.com/embed/".$data['youtube_id'];
    }
    else
    {
        $video['youtube'] = "https://www.youtube.com/embed/".$data['youtube_id'];
    }
    if($default_tab == 'youtube')
    {
        $video['header'] = 'active';
        $video['block'] = 'style="display:block;"';
    }
    $video['tab_no'] = $tab_no;
    $tab_no++;
}



if ($data['apply_enhanced_listing'] == 'Y')
{
    if ($data['enhanced_listing'])
    {
        $overview['title'] = $data['lang_text']['overview'];
        $overview['content'] = $data['enhanced_listing'];

        if($default_tab == 'overview')
        {
            $overview['header'] = 'active';
            $overview['block'] = 'style="display:block;"';
        }

        $overview['tab_no'] = $tab_no;
        $tab_no++;
    }
}
else
{
    if(($data['overview']) || ($data['feature']))
    {
        $overview['title'] = $data['lang_text']['overview'];
        
        if ($data['overview']) {

            if(strpos($data['overview'],"Please check product specifications to see if network frequency is compatible with your own carrier."))
            {
                $replaceStr = "Please check product specifications to see if network frequency is compatible with your own carrier.";
            }

            if(strpos($data['overview'],"Per favore, controlla le specifiche tecniche della rete 4G-LTE (se supportata dal prodotto) per la compatibilità con il tuo gestore telefonico."))
            {
                $replaceStr = "Per favore, controlla le specifiche tecniche della rete 4G-LTE (se supportata dal prodotto) per la compatibilità con il tuo gestore telefonico.";
            }

            if(strpos($data['overview'],"Merci de consulter les informations relatives au produit afin de vérifier si les fréquences réseau sont compatibles avec votre opérateur mobile."))
            {
                $replaceStr = "Merci de consulter les informations relatives au produit afin de vérifier si les fréquences réseau sont compatibles avec votre opérateur mobile.";
            }

            if(strpos($data['overview'],"Por favor, compruebe las especificaciones del producto y asegúrese de que las frecuencias o bandas de telefonía son compatibles con su operador."))
            {
                $replaceStr = "Por favor, compruebe las especificaciones del producto y asegúrese de que las frecuencias o bandas de telefonía son compatibles con su operador.";
            }

            if(strpos($data['overview'],"Proszę sprawdzić specyfikację produktu, aby potwierdzić czy produkt będzie działał z Pana usługodawcą."))
            {
                $replaceStr = "Proszę sprawdzić specyfikację produktu, aby potwierdzić czy produkt będzie działał z Pana usługodawcą.";
            }

            $new_overview = str_replace($replaceStr, "<b style='color:green'>".$replaceStr."</b>", $data['overview']);
            $overview['content'] = $new_overview;
        }

        if ($data['feature'])
        {
            $overview['feature'] = $data['feature'];
            $overview['featureblock'] = 'style="display:block;"';
        }
        else
            $overview['featureblock'] = 'style="display:none;"';

        if($default_tab == 'overview')
        {
            $overview['header'] = 'active';
            $overview['block'] = 'style="display:block;"';
        }

        if($data['machine_translate_hint'])
        {
            $overview['machine_translate_hint']  = '<p><i>'.$data['lang_text']['machine_translate_hint'].'</p></i>';
        }
        else
        {
            $overview['machine_translate_hint']  = "";
        }
        
        $overview['tab_no'] = $tab_no;
        $tab_no++;
    }
}



if($data['specification'])
{
    $specification['title'] = $data['lang_text']['specification'];
    $specification['content'] = str_replace("\n", "<br/>", $data['specification']);
    if($default_tab == 'specification')
    {
        $specification['header'] = 'active';
        $specification['block'] = 'style="display:block;"';
    }
    $specification['tab_no'] = $tab_no;
    $tab_no++;
}

if($data['in_the_box'])
{
    $in_the_box['title'] = $data['lang_text']['in_the_box'];
    $in_the_box['content'] = $data['in_the_box'];
    if($default_tab == 'in_the_box')
    {
        $in_the_box['header'] = 'active';
        $in_the_box['block'] = 'style="display:block;"';
    }
    $in_the_box['tab_no'] = $tab_no;
    $tab_no++;
}
if($data['accessories'])
{
    $accessories['title'] = 'Accessories';
    $accessories['content'] = $data['accessories'];
    if($default_tab == 'accessories')
    {
        $accessories['header'] = 'active';
        $accessories['block'] = 'style="display:block;"';
    }
    $accessories['tab_no'] = $tab_no;
    $tab_no++;
}
//end of product tab

// RA list remove by nero
#SBF2652 cross sell

if($data["has_csp"])
{
    foreach($data["cross_sell_product_list"] as $key=>$csp)
    {
        $csp_arr[$key]["display_name"] = htmlspecialchars($csp["prod_name"], ENT_QUOTES);
        $csp_arr[$key]["image"] =  $csp["image"];
        $csp_arr[$key]["add_cart"] = base_url()."cart/add_item/".$csp['sku'];
        $csp_arr[$key]["price"] = platform_curr_format(PLATFORMID, $csp['prod_price']);
        $csp_arr[$key]["rrp_price"] = platform_curr_format(PLATFORMID, $csp['prod_rrp_price']);
        $csp_arr[$key]["stock_status"] = $csp["stock_status"];
        $csp_arr[$key]["prod_url"] = $csp["prod_url"];
    }
    $this->tbswrapper->tbsMergeBlock('csp_arr', $csp_arr);
    $csp_head["title_1"] = $data['lang_text']['cross_sell_title'];
    $this->tbswrapper->tbsMergeField('csp_head', $csp_head);
    $data["has_csp"] = TRUE;
}
$this->tbswrapper->tbsMergeField('has_csp', $data["has_csp"]);



//bundle
if($data['bundle'])
{
    $show_bundle = 1;
    foreach($data['bundle'] as $key=>$b_obj)
    {
        $count = 0;
        foreach($b_obj->get_component_sku_list() as $component)
        {
            if($component["component_sku"] == $b_obj->get_main_prod_sku())
            {
                $bundle_arr[$key]["image_url"] = base_url().get_image_file($component['component_image_file_ext'],'l',$component['component_sku']);
            }
            /*
            if($count > 0)
            {
                $bundle_name .= " + ";
            }
            $bundle_name .= $component["component_name"];
            */
            $count++;
        }
        $bundle_name = $b_obj->get_bundle_name();
        $bundle_arr[$key]["display_name"] = $bundle_name;
        $bundle_arr[$key]["add_cart"] = base_url()."cart/add_item?sku=".$b_obj->get_prod_sku();
        $bundle_arr[$key]["price"] = platform_curr_format(PLATFORMID, $b_obj->get_total_price());
    }
$this->tbswrapper->tbsMergeBlock('bundle_arr', $bundle_arr);
}
$this->tbswrapper->tbsMergeField('show_bundle', $show_bundle);
$this->tbswrapper->tbsMergeField('base_url', base_url());
$this->tbswrapper->tbsMergeField('sku', $data['sku']);
$this->tbswrapper->tbsMergeField('default_prod_img', $default_prod_img);
$this->tbswrapper->tbsMergeBlock('prod_img_arr', $prod_img_arr);
$this->tbswrapper->tbsMergeField('prod_info', $prod_info);
$this->tbswrapper->tbsMergeField('video', $video);

    $sku = $data['sku'];

    $sbf1597 = "";
    $sbf1597 = $data['sbf1597'];
    $this->tbswrapper->tbsMergeField('sbf1597', $sbf1597);  # please remember to remove from product.html as well

    $sbf2183_img_tag = "";
    $sbf2183_img_tag = $data['sbf2183_img_tag'];
    $this->tbswrapper->tbsMergeField('sbf2183_img_tag', $sbf2183_img_tag);  # please remember to remove from product.html as well

    // var_dump($sku);
    
    #sbf #2950 remove some skus
    $sbf2618_img_tag = "";
    if($country == 'es')
    {
        if (
            // $sku == '10810-AA-BK')
            // || ($sku == '10265-AA-NA')
            ($sku == '11807-AA-BK')
            // || ($sku == '11807-AA-BL')
            // || ($sku == '10265-AA-NA')
            // || ($sku == '13180-AA-NA')
            // || ($sku == '10200-AA-NA')
            // || ($sku == '10563-AA-NA')
            // || ($sku == '12146-AA-BK')
            // || ($sku == '10269-AA-NA')
            // || ($sku == '10452-AA-NA')
            // || ($sku == '10268-AA-NA')
            // || ($sku == '12030-AA-NA')
            // || ($sku == '12703-AA-BL')
            // || ($sku == '12703-AA-PK')
            // || ($sku == '12703-AA-SL')
            // || ($sku == '10186-AA-NA')
            // || ($sku == '11299-AA-WH')
            // || ($sku == '12090-AA-NA')
            // || ($sku == '10720-AA-NA')
            // || ($sku == '13110-AA-OR')
            // || ($sku == '11042-AA-NA')
            // || ($sku == '12821-AA-SL')
            // || ($sku == '11916-AA-WH')
            // || ($sku == '11470-AA-GY')
            // || ($sku == '12903-AA-WH')
            // || ($sku == '12903-AA-RD')
            // || ($sku == '12080-AA-BK')
            // || ($sku == '12080-AA-BN')
            // || ($sku == '12267-AA-RD')
            // || ($sku == '11471-AA-WH')
            // || ($sku == '13069-AA-BN')
            // || ($sku == '10111-AA-BK')
            )
        {
            $sbf2618_img_tag = <<<img_tag
                <img src="http://cdn.valuebasket.com/808AA1/vb/resources/images/ExpeditedDelivery_{$country}.jpg">
img_tag;
        }
    }
    $this->tbswrapper->tbsMergeField('sbf2618_img_tag', $sbf2618_img_tag);  # please remember to remove from product.html as well

    $hcb20130830_img_tag = "";
    $hcb20130830_img_tag = $data['hcb20130830_img_tag'];
    $this->tbswrapper->tbsMergeField('hcb20130830_img_tag', $hcb20130830_img_tag);  # please remember to remove from product.html as well


$lang = get_lang_id();

// ============================== PRODUCT WIDGETS =======================
if($lang=="fr")
{
    /*$ekomi_widget = "
    
    <!-- eKomiWidget START -->
        <div id='eKomiWidget_default'></div>
        <!-- eKomiWidget END -->

        <!-- eKomiLoader START, only needed once per page -->
        <script type='text/javascript'> 
            (function(){
                eKomiIntegrationConfig = new Array(
                    {certId:'22A937523B54E53'}
                );
                if(typeof eKomiIntegrationConfig != 'undefined'){for(var eKomiIntegrationLoop=0;eKomiIntegrationLoop<eKomiIntegrationConfig.length;eKomiIntegrationLoop++){
                    var eKomiIntegrationContainer = document.createElement('script');
                    eKomiIntegrationContainer.type = 'text/javascript'; eKomiIntegrationContainer.defer = true;
                    eKomiIntegrationContainer.src = (document.location.protocol=='https:'?'https:':'http:') +'//connect.ekomi.de/integration_1390368118/' + eKomiIntegrationConfig[eKomiIntegrationLoop].certId + '.js';
                    document.getElementsByTagName('head')[0].appendChild(eKomiIntegrationContainer);
                }}else{if('console' in window){ console.error('connectEkomiIntegration - Cannot read eKomiIntegrationConfig'); }}
            })();
        </script>
    <!-- eKomiLoader END, only needed once per page -->
    
    ";
    */
    
    $ekomi_widget = "
    ";
    
}
elseif($lang=="es")
{
    $ekomi_widget = "
        <!-- eKomiWidget START -->
            <div id='eKomiWidget_default'></div>
            <!-- eKomiWidget END -->

            <!-- eKomiLoader START, only needed once per page -->
            <script type='text/javascript'> 
                (function(){
                    eKomiIntegrationConfig = new Array(
                        {certId:'F0E3263216F0248'}
                    );
                    if(typeof eKomiIntegrationConfig != 'undefined'){for(var eKomiIntegrationLoop=0;eKomiIntegrationLoop<eKomiIntegrationConfig.length;eKomiIntegrationLoop++){
                        var eKomiIntegrationContainer = document.createElement('script');
                        eKomiIntegrationContainer.type = 'text/javascript'; eKomiIntegrationContainer.defer = true;
                        eKomiIntegrationContainer.src = (document.location.protocol=='https:'?'https:':'http:') +'//connect.ekomi.de/integration_1390368309/' + eKomiIntegrationConfig[eKomiIntegrationLoop].certId + '.js';
                        document.getElementsByTagName('head')[0].appendChild(eKomiIntegrationContainer);
                    }}else{if('console' in window){ console.error('connectEkomiIntegration - Cannot read eKomiIntegrationConfig'); }}
                })();
            </script>
        <!-- eKomiLoader END, only needed once per page -->
    ";
}
elseif($lang=="it")
{
    $ekomi_widget = "
        <!-- eKomiWidget START -->
            <div id='eKomiWidget_default'></div>
            <!-- eKomiWidget END -->

            <!-- eKomiLoader START, only needed once per page -->
            <script type='text/javascript'> 
                (function(){
                    eKomiIntegrationConfig = new Array(
                        {certId:'F67CB4A6C695578'}
                    );
                    if(typeof eKomiIntegrationConfig != 'undefined'){for(var eKomiIntegrationLoop=0;eKomiIntegrationLoop<eKomiIntegrationConfig.length;eKomiIntegrationLoop++){
                        var eKomiIntegrationContainer = document.createElement('script');
                        eKomiIntegrationContainer.type = 'text/javascript'; eKomiIntegrationContainer.defer = true;
                        eKomiIntegrationContainer.src = (document.location.protocol=='https:'?'https:':'http:') +'//connect.ekomi.de/integration_1390369125/' + eKomiIntegrationConfig[eKomiIntegrationLoop].certId + '.js';
                        document.getElementsByTagName('head')[0].appendChild(eKomiIntegrationContainer);
                    }}else{if('console' in window){ console.error('connectEkomiIntegration - Cannot read eKomiIntegrationConfig'); }}
                })();
            </script>
        <!-- eKomiLoader END, only needed once per page -->
    ";
}
else
{
    $ekomi_widget = "
    <!-- eKomiWidget START -->
        <div id='eKomiWidget_default'></div>
        <!-- eKomiWidget END -->

        <!-- eKomiLoader START, only needed once per page -->
        <script type='text/javascript'> 
            (function(){
                eKomiIntegrationConfig = new Array(
                    {certId:'UNZCV8FVBAF4HED'}
                );
                if(typeof eKomiIntegrationConfig != 'undefined'){for(var eKomiIntegrationLoop=0;eKomiIntegrationLoop<eKomiIntegrationConfig.length;eKomiIntegrationLoop++){
                    var eKomiIntegrationContainer = document.createElement('script');
                    eKomiIntegrationContainer.type = 'text/javascript'; eKomiIntegrationContainer.defer = true;
                    eKomiIntegrationContainer.src = (document.location.protocol=='https:'?'https:':'http:') +'//connect.ekomi.de/integration_1390367771/' + eKomiIntegrationConfig[eKomiIntegrationLoop].certId + '.js';
                    document.getElementsByTagName('head')[0].appendChild(eKomiIntegrationContainer);
                }}else{if('console' in window){ console.error('connectEkomiIntegration - Cannot read eKomiIntegrationConfig'); }}
            })();
        </script>
    <!-- eKomiLoader END, only needed once per page --> 
";
}   

$this->tbswrapper->tbsMergeField('ekomi_widget', $ekomi_widget);    # please remember to remove from product.html as well

$comparer_widget = "";
if(PLATFORMCOUNTRYID == "BE")
{
    $comparer_widget = <<<EOT
        <table width="133" height="133" border="0" cellspacing="0" cellpadding="0" background="http://cdn.valuebasket.com/808AA1/vb/resources/images/ComparerBE_bg.jpg">
            <tr>
                <td align="center" valign="middle">
                    <a href='http://www.comparer.be/shopinfo/valuebasketbe/review/' target='_blank' title='Voir les avis pour ValueBasketsur COMPARER.BE'>
                    <img src='//static.comparer.be/shopscore/valuebasketbe.gif' border='0' width='120' height='90' alt='Voir les avis pour ValueBasketsur COMPARER.BE' /></a>
                </td>
            </tr>
        </table>
EOT;
}
// elseif(PLATFORMCOUNTRYID == "FR")
// {
//  $comparer_widget = <<<EOT
//      <table width="133" height="133" border="0" cellspacing="0" cellpadding="0" background="http://cdn.valuebasket.com/808AA1/vb/resources/images/ComparerBE_bg.jpg">
//          <tr>
//              <td align="center" valign="middle">
//                  <a href='http://www.comparer.fr/shopinfo/valuebasket/review/' target='_blank' title='Voir les avis pour ValueBasketsur COMPARER.FR'>
//                  <img src='//static.comparer.fr/shopscore/valuebasket.gif' border='0' width='120' height='90' alt='Voir les avis pour ValueBasketsur COMPARER.FR' /></a>
//              </td>
//          </tr>
//      </table>
// EOT;
// }


$this->tbswrapper->tbsMergeField('comparer_widget', $comparer_widget);  

if(PLATFORMCOUNTRYID == "BE")
{
$shopperapproved_widget = <<<EOT
    <table width="133" height="52" border="0" cellspacing="0" cellpadding="0" background="http://cdn.valuebasket.com/808AA1/vb/resources/images/ShopperApproved_BG.jpg">
        <tr>
        <td align="center" valign="middle">     
            <a href="http://shopperapprovedreviews.com/reviews/valuebasket.com/"
            onclick="var nonwin=navigator.appName!='Microsoft Internet Explorer'?'yes':'no'; var certheight=screen.availHeight-90; window.open(this.href,'shopperapproved','location='+nonwin+',scrollbars=yes,width=620,height='+certheight+',menubar=no,toolbar=no'); return false;">
            <img src="https://c683207.ssl.cf2.rackcdn.com/6801-r.gif" style="border: 0" alt=""
            oncontextmenu="var d = new Date(); alert('Copying Prohibited by Law - This image and all included logos are copyrighted by shopperapproved \251 '+d.getFullYear()+'.'); return false;" /></a>
        </td>
        </tr>
    </table>
EOT;
}

else
{
$shopperapproved_widget = <<<EOT
    <div style="background-image:url('http://cdn.valuebasket.com/808AA1/vb/resources/images/Confidence_Approved02_EN.jpg');background-size:100%;height:160px;width:160px">
        <div style="padding:16px 6px">
            <a href="http://shopperapprovedreviews.com/reviews/valuebasket.com/"
            onclick="var nonwin=navigator.appName!='Microsoft Internet Explorer'?'yes':'no'; var certheight=screen.availHeight-90; window.open(this.href,'shopperapproved','location='+nonwin+',scrollbars=yes,width=620,height='+certheight+',menubar=no,toolbar=no'); return false;">
            <img src="https://c683207.ssl.cf2.rackcdn.com/6801-r.gif" width="150" style="border: 0"  alt="" 
            oncontextmenu="var d = new Date(); alert('Copying Prohibited by Law - This image and all included logos are copyrighted by shopperapproved \251 '+d.getFullYear()+'.'); return false;" /></a>
        </div>
    </div>
EOT;
}

$this->tbswrapper->tbsMergeField('shopperapproved_widget', $shopperapproved_widget);

# consolidate all product widgets' layout here
$all_review_widgets = "";
if(PLATFORMCOUNTRYID == "BE" )
{
    $all_review_widgets = <<<EOT
        <div style="float:left;">$ekomi_widget</div>
        <div style="float:left;padding:40px 10px 0px;">$comparer_widget<br>
        $shopperapproved_widget</div>
EOT;
}
else
{
    $all_review_widgets = <<<EOT
        <div style="float:left;padding:0px 40px 0px;">$ekomi_widget<br>
        $shopperapproved_widget</div>
EOT;
}
$this->tbswrapper->tbsMergeField('all_review_widgets', $all_review_widgets);

// ============================ END PRODUCT WIDGETS =======================


$this->tbswrapper->tbsMergeField('platformcountryid', PLATFORMCOUNTRYID);   
$this->tbswrapper->tbsMergeField('apply_enhanced_listing', $data['apply_enhanced_listing']);
$this->tbswrapper->tbsMergeField('overview', $overview);
$this->tbswrapper->tbsMergeField('feature', $feature);
$this->tbswrapper->tbsMergeField('in_the_box', $in_the_box);
if (PLATFORMCOUNTRYID == "GB")
    $countryName = "UK";
else
    $countryName = PLATFORMCOUNTRYID;
$this->tbswrapper->tbsMergeField('countryId', $countryName);
$this->tbswrapper->tbsMergeField('accessories', $accessories);
$this->tbswrapper->tbsMergeField('specification', $specification);
$this->tbswrapper->tbsMergeField('upselling', $upselling);

$this->tbswrapper->tbsMergeField('add_to_basket', $data['add_to_basket']);
if (!isset($cs_phone_no) || is_null($cs_phone_no))
    $cs_phone_no = " ";
$this->tbswrapper->tbsMergeField('cs_phone_no', $cs_phone_no);
$this->tbswrapper->tbsMergeField('cdn_url', base_cdn_url());
$this->tbswrapper->tbsMergeField('lang_part', lang_part());

#echo "<pre>"; var_dump(['lang_text']["hotline_support"]); die();

$this->tbswrapper->tbsMergeField('show_discount_text', $data['show_discount_text']);
$this->tbswrapper->tbsMergeField('tracking_script', $data['tracking_script']);
$this->tbswrapper->tbsMergeField('gst_msg_type', $data['gst_msg_type']);

$selling_point = "<li><img src='" . base_cdn_url() . "resources/images/points/" . get_lang_id() . "/free_pp.png' /><br><div>" . $data['lang_text']['free_pp'] . "</div></li>";
if (PLATFORMCOUNTRYID != "PH")
    $selling_point .= "<li><img src='" . base_cdn_url() . "resources/images/points/" . get_lang_id() . "/free_shipping_insurance.png' /><br><div>" . $data['lang_text']['free_shipping_insurance'] . "</div></li>";
    //$selling_point .= "<li><img src='" . base_cdn_url() . "resources/images/points/" . get_lang_id() . "/shipping.png' /><br><div>" . $data['lang_text']['shipping_1'] . $data['delivery_min_day'] . $data['lang_text']['shipping_2'] . $data['delivery_max_day'] . $data['lang_text']['shipping_3'] . "</div></li>";
    $selling_point .= "<li><img src='" . base_cdn_url() . "resources/images/points/" . get_lang_id() . "/shipping.png' /><br><div>" . $data['lang_text']['shipping_track'] . "</div></li>";


if (PLATFORMCOUNTRYID != "PH")
{
    //By Nero 4479
    if($data['sku_warranty_in_month'] > 0)
    {
        $selling_point .= "<li><img src='" . base_cdn_url() . "resources/images/points/" . get_lang_id() . "/guarantee_". $data['sku_warranty_in_month']. "month.png' /><br><div>" . $data['lang_text']['month_warranty_text_1']." ".$data['sku_warranty_in_month']." ".$data['lang_text']['month_warranty_text_2']."</div></li>";
    }
    
    $selling_point .= "<li><img src='" . base_cdn_url() . "resources/images/points/" . get_lang_id() . "/money_back.png' /><br><div>" . $data['lang_text']['money_back_guarantee'] . "</div></li>";
    if ($hotline_support_image != '')
    {
        $selling_point .= "<li><img src='" . base_cdn_url() . $hotline_support_image . "'><br><div>" . $data['lang_text']['hotline_support'] . "<br>" . $cs_phone_no . "</div></li>";
    }
    //$selling_point .= "<li><img src='" . base_cdn_url() . "resources/images/points/" . get_lang_id() . "/tech_support.png' /><br><div>" . $data['lang_text']['expert_tech_support'] . "</div></li>";
}
//$selling_point .= "<li><img src='" . base_cdn_url() . "resources/images/points/" . get_lang_id() . "/price.png' /><br><div>" . $data['lang_text']['competitive_prices'] . "</div></li>";

$warranty_display = "";
if (PLATFORMCOUNTRYID != "PH")
{
    if($data['sku_warranty_in_month'] > 0)
    {
        $warranty_display = "<div id='warranty' title='" . $data['lang_text']['warranty_desc'] . $data["sku_warranty_in_month"] . $data['lang_text']['warranty_desc_2'] . "'><span id='warranty_value'><a href='/display/view/warranty' style='color:#FF6611'>" . $data["sku_warranty_in_month"] . "</span> " . $data['lang_text']['month_warranty_text'] . "</a><br /><br /></div>";
    }
}
$this->tbswrapper->tbsMergeField('selling_point', $selling_point);
$this->tbswrapper->tbsMergeField('warranty_display', $warranty_display);


$extra_banner = array('RU', 'PL', 'IT');
$extra_pic_url = '';
if(in_array(PLATFORMCOUNTRYID, $extra_banner))
{
    $extra_banner_name = "extra_pic_".PLATFORMCOUNTRYID;

    $extra_pic = "<img src='" . base_cdn_url() . "resources/images/". $extra_banner_name .".jpg'>";
    
    if(PLATFORMCOUNTRYID == "RU")
    {
        $extra_pic_url = '/hesk_ru/knowledgebase.php?article=19';
        $extra_pic = "<span id='extra_pic' style='cursor:pointer'>".$extra_pic."</span>";
    }
    if(PLATFORMCOUNTRYID == "PL")
    {
        $extra_pic_url = '/display/view/faq';
                $extra_pic = "<span id='extra_pic' style='cursor:pointer'>".$extra_pic."</span>";
    }
    
    if(PLATFORMCOUNTRYID == "IT")
    {
        $extra_pic_url = '/display/view/faq';
        $extra_pic = "<span id='extra_pic' style='cursor:pointer'>
                        <span style='color:#B40404;font-size: 26px'>Tutto Incluso!</span><br/>
                        <strong>Il prezzo esposto include:</strong><br/>
                        -Spedizione e consegna con assicurazione<br/>
                        -Tutte le tasse e spese extra<br/>
                        -Garanzia ValueBasket locale<br/>
                        <strong>Non spendi un centesimo in pi&#250;!</strong><br/><br/>
                        <span style='color:#B40404;font-size: 26px'>Metodi di pagamento:</span>
                        <table>
                            <tr>
                                <td><img width='80' src='". base_cdn_url() . "resources/images/VisaCredit.jpg'></td>
                                <td><img width='80' src='". base_cdn_url() . "resources/images/MasterCard.jpg'></td>
                                <td><img width='80' src='". base_cdn_url() . "resources/images/AMEX.jpg'></td>
                                <td><img width='80' src='". base_cdn_url() . "resources/images/Cartasi.jpg'></td>
                            </tr>
                            <tr>
                                <td><img width='80' src='". base_cdn_url() . "resources/images/postepay.jpg'></td>
                                <td><img width='80' src='". base_cdn_url() . "resources/images/trustly.jpg'></td>
                                <td><img width='80' src='/images/bonifico.jpg'></td>
                                <td><img width='80' src='". base_cdn_url() . "resources/images/PayPal.jpg'></td>
                            </tr>
                        </table><br/>
                    </span>";

        $extra_pic2_url = '/display/view/shipping/';
        $extra_pic2 = "<span id='extra_pic2' style='cursor:pointer'>
                        
                        <table>
                            <tr>
                                <td valign='top'>
                                    <span style='color:#B40404;font-size: 26px'>Corrieri:</span>
                                </td>
                                <td>
                                    <img width='80%' src='/images/corrieri.jpg'>
                                </td>
                            </tr>
                        </table>
                        </span>";
    }
    $extra_pic = "<div id='extra_pic' style='margin-bottom:50px'>".$extra_pic."</div>";
    $extra_pic2 = "<div id='extra_pic2' style='padding:20px 0 0 90px;'>".$extra_pic2."</div>";
}
else
{
    $extra_pic = "";
}

$this->tbswrapper->tbsMergeField('extra_pic_url', $extra_pic_url);
$this->tbswrapper->tbsMergeField('extra_pic', $extra_pic);

if (!isset($extra_pic2))
{
    $extra_pic2 = '';
    $extra_pic2_url = '';
}
$this->tbswrapper->tbsMergeField('extra_pic2_url', $extra_pic2_url);
$this->tbswrapper->tbsMergeField('extra_pic2', $extra_pic2);

$this->tbswrapper->tbsMergeField('platform_currency', PLATFORM_CURRENCY);
//if (($data["stock_status"] != "I") && ($data["stock_status"] != "P"))
//{
    $this->tbswrapper->tbsMergeField('g_stock', $data["stock_status"]);
    $this->tbswrapper->tbsMergeField('g_stock_desc', $data["stock_status"]);
//}

echo $this->tbswrapper->tbsRender();
#echo "<pre>"; var_dump($data); echo "</pre>";
