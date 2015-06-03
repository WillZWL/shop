<?php
$this->load->helper('tbswrapper');
$this->tbswrapper = new Tbswrapper();
$this->tbswrapper->tbsLoadTemplate('resources/template/searchspring_result_' . strtolower(PLATFORMCOUNTRYID) . '.html', '', '', $data['lang_text']);

echo $this->tbswrapper->tbsRender();
?>