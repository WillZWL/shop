<?php
?>
<link rel="stylesheet" href="/css/lytebox.css" type="text/css" media="screen" />
<link rel="stylesheet" href="/css/lytebox_ext.css" type="text/css" media="screen" />
<script src="/js/common.js" type="text/javascript"></script>
<script src="/js/lytebox_cv.min.js" type="text/javascript"></script>
<?php
$this->load->helper('tbswrapper');
$this->tbswrapper = new Tbswrapper();
$this->tbswrapper->tbsLoadTemplate('resources/template/login.html', '', '', $data['lang_text']);

$this->tbswrapper->tbsMergeField('base_url', base_url());

$email = $this->input->post("page")?"":htmlspecialchars($this->input->post("email"));
$trackno = $data['trackno'];

$login_failed_msg["notice"] = $data["login_failed_msg"];
$register_failed_msg["notice"] = $data["register_failed_msg"];
if($data["bill_to_list"])
{
	$i = 0;
	foreach($data["bill_to_list"] AS $cobj)
	{
		$bill_country_arr[$i]["id"] = $cobj->get_id();
		$bill_country_arr[$i]["display_name"] = $cobj->get_lang_name();
		if($cobj->get_id() == PLATFORMCOUNTRYID)
		{
			$bill_country_arr[$i]["selected"] = "SELECTED";
		}
		else
		{
			$bill_country_arr[$i]["selected"] = "";
		}
		$i++;
	}
}

#SBF #2958 Add NIF/CIF for ES
$show_client_id = 'false';
if ((PLATFORMCOUNTRYID == 'ES') || (PLATFORMCOUNTRYID == 'RU'))
{
	$show_client_id = 'true';
	$client_id_html = <<<html
		<li>
			<label>{$data['lang_text']['client_id_no']} <a href ="" title = "{$data['lang_text']['client_id_title']}">[?]</a></label>
			<fieldset><input type="text" dname="{$data['lang_text']['client_id_no']}" name="client_id_no" /></fieldset>
		</li>
html
;
}

$this->tbswrapper->tbsMergeField('show_client_id', $show_client_id);
$this->tbswrapper->tbsMergeField('client_id_html', $client_id_html);


//these value are displayed to users according to the language_id
$title[0]['value'] = $data['lang_text']['title_mr'];
$title[1]['value'] = $data['lang_text']['title_mrs'];
$title[2]['value'] = $data['lang_text']['title_miss'];
//these value are storied into database, always english version
$title[0]["value_EN"] = "Mr";
$title[1]["value_EN"] = "Mrs";
$title[2]["value_EN"] = "Miss";

if($data['lang_id'] != 'es'){
	$title[3]['value'] = $data['lang_text']['title_dr'];
	$title[3]["value_EN"] = "Dr";
}


$this->tbswrapper->tbsMergeField('back', "back=".$data['back']);

$this->tbswrapper->tbsMergeField('email', $email);
$this->tbswrapper->tbsMergeField('login_failed_msg', $login_failed_msg);
$this->tbswrapper->tbsMergeField('register_failed_msg', $register_failed_msg);
$this->tbswrapper->tbsMergeBlock('bill_country_arr', $bill_country_arr);
$this->tbswrapper->tbsMergeField('cdn_url', base_cdn_url());
$this->tbswrapper->tbsMergeBlock('title', $title);

$this->tbswrapper->tbsMergeField('track-num', $trackno);

echo $this->tbswrapper->tbsRender();
?>