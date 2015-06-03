<?php
$this->load->helper('tbswrapper');
$this->tbswrapper = new Tbswrapper();

include_once('tbs_upselling.php');
$data['need_checkout_button'] = TRUE;
$data['need_show_topbar'] = TRUE;
$upselling_content = generate_upselling_content($this->tbswrapper, $data);

$this->tbswrapper->tbsLoadTemplate('resources/template/cart.html', '', '', $data['lang_text']);
$this->tbswrapper->tbsMergeField('upselling_content', $upselling_content);
echo $this->tbswrapper->tbsRender();
?>