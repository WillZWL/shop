<?php

    $this->load->helper('tbswrapper');
    $this->tbswrapper = new Tbswrapper($data['lang_text']);

    $lang_id = $_SESSION["lang_id"];
    if (empty($lang_id)) $lang_id = 'en';

    if (PLATFORMCOUNTRYID == "PH")
    {
        $this->tbswrapper->tbsLoadTemplate('resources/template/faq_hesk.html', '', '', $data['lang_text']);
            $this->tbswrapper->tbsMergeField('lang_id', strtolower(PLATFORMCOUNTRYID));
    }
    else
    {
        switch($lang_id)
        {
            case "en":
                // this uses kayako as our knowledgebase
                $this->tbswrapper->tbsLoadTemplate('resources/template/faq_kb.html', '', '', $data['lang_text']);
                break;

            case "it":
            case "fr": 
            case "es": 
            case "ru": 
            case "pl":          
                // this uses hesk as our knowledgebase
                $this->tbswrapper->tbsLoadTemplate('resources/template/faq_hesk.html', '', '', $data['lang_text']);
                break;

            default: 
                #$this->tbswrapper->tbsLoadTemplate('resources/template/faq_es.html', '', '', $data['lang_text']);
                #break;
                $this->tbswrapper->tbsLoadTemplate('resources/template/faq_static.html', '', '', $data['lang_text']);
                break;
        }
        $this->tbswrapper->tbsMergeField('lang_id', $lang_id);
    }

    $faq['content'] = $data['content'];
    $this->tbswrapper->tbsMergeField('base_url', base_url());
    $this->tbswrapper->tbsMergeField('faq', $faq);
    $this->tbswrapper->tbsMergeField('cdn_url', base_cdn_url());

    echo $this->tbswrapper->tbsRender();
