<?php

    $this->load->helper('tbswrapper');
    $this->tbswrapper = new Tbswrapper($data['lang_text']);
    $this->tbswrapper->tbsLoadTemplate('resources/template/primastar_selector.html', '', '', $data['lang_text']);
    
    // var_dump($data['lang_text']); die();
    
    $primastar_type = $data['primastar_type'];
    $primastar_preset = $data['primastar_preset'];
    
    $primastar_icon = "";
    switch (strtolower($primastar_type))
    {
        case "mobile":  
            $primastar_icon = "/images/primastar_icon_mobile.jpg";
            break;
    }
        
    $this->tbswrapper->tbsMergeField('selector_icon', "$primastar_icon");

    $available = true;
    $country_code = "";
    switch (PLATFORMCOUNTRYID)
    {
        case "AU":
        case "BE":
        case "ES":
        case "FI":
        case "FR":
        case "HK":
        case "IE":
        case "MY":
        case "NZ":
        case "SG":
        case "US":
            break;
        case "GB": 
            $country_code = "UK";
            break;

        default:
            $available = false; 
            break;
    }
    
    if ($available)
    {
        if ($country_code == "") $country_code = PLATFORMCOUNTRYID;
        $country_code = strtolower($country_code);
        $url = "http://atl.prismastar.com/ui/valuebasket/$country_code/v2_2/generate";
        
        # add the preset if there is one
        if ($primastar_preset != "") $url .= "?psPreset=$primastar_preset";
        $selector_src = $url;
    }
    else
        $selector_src = "";
    
    $this->tbswrapper->tbsMergeField('selector_src', $selector_src);
    
    //$this->tbswrapper->tbsMergeField('lang_text', $data['lang_text']);
    echo $this->tbswrapper->tbsRender();
    
    return;
    #die();
?>
<div id="content">
    <h5 class="side_title">Mobile Phone Selector</h5>
    <div class="text silver_box static_page">
    
    <div id="content" align="center">
        <script src="http://atl.prismastar.com/ui/valuebasket/uk/v2_2_staging/generate" type="text/javascript"></script>
    </div>

    </div>
</div>