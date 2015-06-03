<?php
$this->load->helper('tbswrapper');
$this->tbswrapper = new Tbswrapper();
$this->tbswrapper->tbsLoadTemplate('resources/template/privacy_policy.html', '', '', $data['lang_text']);

$this->tbswrapper->tbsMergeField('base_url', base_url());
$this->tbswrapper->tbsMergeField('cdn_url', base_cdn_url());

echo $this->tbswrapper->tbsRender();
?>