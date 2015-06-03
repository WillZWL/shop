<?php
$country_code = strtolower(PLATFORMCOUNTRYID);

$this->load->helper('tbswrapper');
$this->tbswrapper = new Tbswrapper();
$this->tbswrapper->tbsLoadTemplate('resources/template/bulk_sales_' . $country_code . '.html', '', '', $data['lang_text']);

# SBF #2441
$this->tbswrapper->tbsMergeField('base_url', base_url());
$this->tbswrapper->tbsMergeField('cdn_url', base_cdn_url());
$this->tbswrapper->tbsMergeField('platform_currency_id', $_SESSION["domain_platform"]["platform_currency_id"]);
$this->tbswrapper->tbsMergeField('cs_phone_no', $cs_phone_no);

echo $this->tbswrapper->tbsRender();
?>