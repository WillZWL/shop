<?php
$this->load->helper('tbswrapper');
$this->tbswrapper = new Tbswrapper($data['lang_text']);

if (PLATFORMCOUNTRYID == 'PL') {
    $data['lang_text']["shipping_content"] = <<<start
        {$data['lang_text']['pl_shipping_content_3_1']}
        <br /><br />
start;
} else {
    $data['lang_text']["shipping_content"] = <<<start

            {$data['lang_text']['shipping_content_3_1']}
            <br /><br />
            {$data['lang_text']['shipping_content_3_2']}
            <br /><br />
start;
}

//$data['lang_text']["shipping_content_4"] = $data["lang_text"]["shipping_content_4_1_1"] . $data["ship_days"] . $data["lang_text"]["shipping_content_4_1_2"] . $data["del_days"] . $data["lang_text"]["shipping_content_4_1_3"];
$data['lang_text']["shipping_content_4"] = $data["lang_text"]["shipping_content_4_1_1"];
if (strtolower($data["lang_id"]) == 'ru')
    $data['lang_text']["shipping_content_4"] .= $data["lang_text"]["shipping_content_4_1_4"];


$this->tbswrapper->tbsLoadTemplate('resources/template/shipping.html', '', '', $data['lang_text']);
$this->tbswrapper->tbsMergeField('PLATFORMCOUNTRYID', PLATFORMCOUNTRYID);
$this->tbswrapper->tbsMergeField('base_url', base_url());
$this->tbswrapper->tbsMergeField('cdn_url', base_cdn_url());

echo $this->tbswrapper->tbsRender();
?>