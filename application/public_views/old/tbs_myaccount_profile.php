<?php
	
include VIEWPATH.'tbs_header.php';
$this->tbswrapper->tbsLoadTemplate('resources/template/myaccount_profile_'.get_lang_id().'.html');
$url["myaccount"] = base_url()."myaccount/ws_myaccount";
$url["rma"] = base_url()."myaccount/ws_rma";
$url["profile"] = base_url()."myaccount/profile";
$url["logout"] = base_url()."logout/ws_logout";
$url["action"] = $data["action"];

$client_obj["email"] = $data["client_obj"]->get_email();
$client_obj["forename"] = $data["client_obj"]->get_forename();
$client_obj["surname"] = $data["client_obj"]->get_surname();
$client_obj["companyname"] = $data["client_obj"]->get_companyname();
$client_obj["address_1"] = $data["client_obj"]->get_address_1();
$client_obj["address_2"] = $data["client_obj"]->get_address_2();
$client_obj["city"] = $data["client_obj"]->get_city();
$client_obj["state"] = $data["client_obj"]->get_state();
$client_obj["postcode"] = $data["client_obj"]->get_postcode();
$client_obj["tel_1"] = $data["client_obj"]->get_tel_1();
$client_obj["tel_2"] = $data["client_obj"]->get_tel_2();
$client_obj["tel_3"] = $data["client_obj"]->get_tel_3();
$client_obj["subscriber"] = $data["client_obj"]->get_subscriber()?"CHECKED":'';

$title[0]["value"] = "Mr";
$title[1]["value"] = "Mrs";
$title[2]["value"] = "Miss";
$title[3]["value"] = "Dr";
foreach($title AS $key=>$value)
{
	if($title[$key]["value"] == $data["client_obj"]->get_title())
	{
		$title[$key]["selected"]="SELECTED";
	}
	else
	{
		$title[$key]["selected"]="";
	}
}

if($data["bill_to_list"])
{
	$i = 0;
	foreach($data["bill_to_list"] AS $cobj)
	{
		$bill_country_arr[$i]["id"] = $cobj->get_id();
		$bill_country_arr[$i]["display_name"] = $cobj->get_lang_name();
		if($data["client_obj"]->get_country_id() == $data["client_obj"]->get_country_id())
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


$skype_cert = array('url'=> base_url().'search/?from=c&filter=skypecert');
$skype_phone = array('url'=> base_url().'search/?from=c&catid=9');

$this->tbswrapper->tbsMergeField('skype_cert', $skype_cert);
$this->tbswrapper->tbsMergeField('skype_phone', $skype_phone);
$this->tbswrapper->tbsMergeField('base_url', base_url());
$this->tbswrapper->tbsMergeField('url', $url);
$this->tbswrapper->tbsMergeField('client_obj', $client_obj);
$this->tbswrapper->tbsMergeBlock('bill_country_arr', $bill_country_arr);
$this->tbswrapper->tbsMergeBlock('title', $title);

$this->tbswrapper->tbsMergeField('cdn_url', base_cdn_url());

echo $this->tbswrapper->tbsRender();
include VIEWPATH.'tbs_footer.php';
?>