<?php

    $country_code = strtolower(PLATFORMCOUNTRYID);

    $this->load->helper('tbswrapper');
    $this->tbswrapper = new Tbswrapper($data['lang_text']);
    $this->tbswrapper->tbsLoadTemplate("resources/template/vb_contact_{$country_code}.html", '', '', $data['lang_text']);

    $this->tbswrapper->tbsMergeField('base_url', base_url());
    // $this->tbswrapper->tbsMergeBlock('contact_info', $data["contact_info"]);
    $this->tbswrapper->tbsMergeField('show_enquiry', $data["show_enquiry"]);
    $this->tbswrapper->tbsMergeField('question_id', $data["question_id"]);
    $this->tbswrapper->tbsMergeField('cdn_url', base_cdn_url());

#   SBF #2210 get client info for auto-filled Pre-Sales contact form in payment failure
    
    $info['showpopup'] = '0';
    if ($_GET) 
    {
        $info['showpopup'] = '1';
        $info['fullname'] = $_GET['fullname'];
        $info['email'] = $_GET['email'];
        $info['phone_no'] = $_GET['phone_no'];
        $info['item_country'] = $_GET['item_country'];  
    }
     $this->tbswrapper->tbsMergeField('info', $info);

    
    # default messages that will show
    $xmas_period = "Christmas: 24th December from GMT 01:00pm and will resume on 27th December GMT 8:00am";
    $new_year_period = "New Years: 31st December from GMT 01:00pm and will resume on 2nd January GMT 8:00am";
    $easter_period = "29th March 2013 from GMT 11:00pm and will resume on 2nd April 2013 GMT 8:00am";

    switch(PLATFORMCOUNTRYID)
    {
        case "FR":
            $xmas_period = "Noël: le 24 décembre à partir de 1h GMT et reprendront le 27 décembre à 8h GMT";
            $new_year_period = "Nouvel an: le 31 décembre à partir de 1h GMT et reprendront le 2 janvier à 8h GMT";
            $easter_period = "29th March 2013 from GMT 11:00pm and will resume on 2nd April 2013 GMT 8:00am";
            break;
        case "US":
            $xmas_period = "Christmas: 24th December from EST 12:00am and will resume on 27th December EST 8:00am";
            $new_year_period = "New Years: 31st December from EST 12:00am and will resume on 2nd January EST 8:00am";
            $easter_period = "29th March 2013 from GMT 11:00pm and will resume on 2nd April 2013 GMT 8:00am";
            break;
        case "NZ":
            $xmas_period = "Christmas: 24th December from NZDT 12:00am and will resume on 27th December NZDT 12:00pm";
            $new_year_period = "New Years: 31st December from NZDT 12:00am and will resume on 2nd January NZDT 12:00pm";
            $easter_period = "29th March 2013 from GMT 11:00pm and will resume on 2nd April 2013 GMT 8:00am";
            break;
        case "AU":
            $xmas_period = "Christmas: 24th December from AEST 11:00pm and will resume on 27th December AEST 9:00am";
            $new_year_period = "New Years: 31st December from AEST 11:00pm and will resume on 2nd January AEST 9:00am";
            $easter_period = "29th March 2013 from GMT 11:00pm and will resume on 2nd April 2013 GMT 8:00am";
            break;
        case "SG":
        case "MY":
        case "HK":
            $xmas_period = "Christmas: 24th December from 09:00pm and will resume on 27th December 07:00am";
            $new_year_period = "New Years: 31st December from 09:00pm and will resume on 2nd January 07:00am";
            $easter_period = "29th March 2013 from GMT 11:00pm and will resume on 2nd April 2013 GMT 8:00am";
            break;
        case "ES":
        case "FI":
        case "GB":
        case "IE":
    }

    # set period = "" to disable seasonal messages

    $period = "";
    // $period = "easter";

    if ($period != "")
    {
        switch(get_lang_id())
        {
            case "en":
                $seasonal_message = <<<en_smsg
Easter Holiday Notice

Please note that our phone hotlines will be closed, and we will have limited email support over the Easter long weekend. Our phone support will be suspended from 11pm GMT on 29th March, and will resume at 8am GMT on 2nd April.

We will endeavour to deal with any resulting email backlog as quickly as possible, but please bear with us for a couple of days as it may take longer than normal to respond to your query
en_smsg;
                break;
            case "fr":          
                $seasonal_message = <<<fr_smsg
Avis de suspension de service durant la période de Pâques

Nous vous remercions de noter que nous ne serons pas joignables sur notre ligne téléphone dédiée et nous aurons une assistance limitée aux courriers électroniques durant le long week-end de Pâques. Notre support par téléphone sera suspendu le 29 Mars à partir de 23h00, et reprendra le 2 Avril à 8h00.

Nous nous efforcerons de traiter vos demandes par courriel le plus rapidement possible. Veuillez bien vouloir nous excuser par avance de l’extension du délai de réponse causée par cette période de congés.
fr_smsg;
                break;
        }
    }

    $seasonal_message = str_replace("\n", "<br>", $seasonal_message);

    $this->tbswrapper->tbsMergeField('seasonal_message', $seasonal_message);

    //$this->tbswrapper->tbsMergeField('lang_text', $data['lang_text']);

    $kayako_live_chat_banner = '';
    switch (strtolower(PLATFORMCOUNTRYID))
    {
        case 'gb' :
        case 'au' : $kayako_live_chat_banner = 'EN'; break;

        case 'fi' :
        case 'us' :
        case 'hk' :
        case 'ie' :
        case 'my' :
        case 'nz' :
        case 'sg' :
        case 'fr' : 
        case 'be' : 
        case 'es' : $kayako_live_chat_banner = ''; break;
        default   : $kayako_live_chat_banner = '';
    }
    $this->tbswrapper->tbsMergeField('kayako_live_chat_banner', $kayako_live_chat_banner);

    echo $this->tbswrapper->tbsRender();
