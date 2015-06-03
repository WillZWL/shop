<?php
$this->load->helper('tbswrapper');
$this->tbswrapper = new Tbswrapper();
$country = strtolower(PLATFORMCOUNTRYID);
$this->tbswrapper->tbsLoadTemplate("resources/template/about_us_$country.html", '', '', $data['lang_text']);
$this->tbswrapper->tbsMergeField('cdn_url', base_cdn_url());

$this->tbswrapper->tbsMergeField('base_url', base_url());
echo $this->tbswrapper->tbsRender();
?>