<?php

	$country_code = strtolower(PLATFORMCOUNTRYID);

	$this->load->helper('tbswrapper');
	$this->tbswrapper = new Tbswrapper($data['lang_text']);
	$this->tbswrapper->tbsLoadTemplate("resources/template/warranty_tc.html", '', '', $data['lang_text']);


	$this->tbswrapper->tbsMergeField('base_url', base_url());
	$this->tbswrapper->tbsMergeField('PLATFORMCOUNTRYID',PLATFORMCOUNTRYID);

	echo $this->tbswrapper->tbsRender();
?>
