<?php
	$this->load->helper('tbswrapper');
	// $this->tbswrapper = new Tbswrapper($data['lang_text']);
	// $this->tbswrapper->tbsLoadTemplate("resources/template/warranty.html", '', '', $data['lang_text']);
	$this->tbswrapper = new Tbswrapper();
	$country = strtolower(PLATFORMCOUNTRYID);
	$this->tbswrapper->tbsLoadTemplate("resources/template/warranty_info_$country.html", '', '', $data['lang_text']);


	switch (strtolower(PLATFORMCOUNTRYID))
	{
		case 'es' : $warranty_youtube_id = 'nMKfwSE-78g'; break;

		case 'nz' : $warranty_youtube_id = 'lHbfqkxUNww'; break;
		case 'sg' : $warranty_youtube_id = 'RLBOGfQ2y38'; break;
		case 'my' : $warranty_youtube_id = 'HYHLEPUoVtM'; break;
		case 'hk' :
		case 'au' : $warranty_youtube_id = 'ySi1pV2E6ms'; break;

		case 'gb' : $warranty_youtube_id = '3ixvQc9tKDw';   break;
		case 'fi' :
		case 'ie' :
		case 'us' : $warranty_youtube_id = 'upv2j5oA7jM';   break;
		case 'fr' : $warranty_youtube_id = '5wmxgyC_xA0';   break;
		case 'be' : $warranty_youtube_id = '5wmxgyC_xA0';   break;
		case 'it' : $warranty_youtube_id = 'IHArQen8au8';   break;

		default   : $warranty_youtube_id = 'upv2j5oA7jM';
	}
	$this->tbswrapper->tbsMergeField('warranty_youtube_id', $warranty_youtube_id);

	$this->tbswrapper->tbsMergeField('base_url', base_url());
	$this->tbswrapper->tbsMergeField('cdn_url', base_cdn_url());
	echo $this->tbswrapper->tbsRender();
?>