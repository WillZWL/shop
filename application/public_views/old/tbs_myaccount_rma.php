<?php

include VIEWPATH . 'tbs_header.php';
$this->tbswrapper->tbsLoadTemplate('resources/template/myaccount_rma_' . get_lang_id() . '.html');
$url["myaccount"] = base_url() . "myaccount/ws_myaccount";
$url["rma"] = base_url() . "myaccount/ws_rma";
$url["profile"] = base_url() . "myaccount/profile";
$url["logout"] = base_url() . "logout/ws_logout";

if ($data["components_list"]) {
    foreach ($data["components_list"] AS $key => $name) {
        if ($key < 5) {
            $ret_prod_arr_1[$key]['name'] = $name;
            $ret_prod_arr_1[$key]['key'] = $key;
        } elseif ($key < 10) {
            $ret_prod_arr_2[$key]['name'] = $name;
            $ret_prod_arr_2[$key]['key'] = $key;
        } elseif ($key < 14) {
            $ret_prod_arr_3[$key]['name'] = $name;
            $ret_prod_arr_3[$key]['key'] = $key;
        }
    }
}
$this->tbswrapper->tbsMergeBlock('ret_prod_arr_1', $ret_prod_arr_1);
$this->tbswrapper->tbsMergeBlock('ret_prod_arr_2', $ret_prod_arr_2);
$this->tbswrapper->tbsMergeBlock('ret_prod_arr_3', $ret_prod_arr_3);

$skype_cert = array('url' => base_url() . 'search/?from=c&filter=skypecert');
$skype_phone = array('url' => base_url() . 'search/?from=c&catid=9');

$this->tbswrapper->tbsMergeField('skype_cert', $skype_cert);
$this->tbswrapper->tbsMergeField('skype_phone', $skype_phone);
$this->tbswrapper->tbsMergeField('base_url', base_url());
$this->tbswrapper->tbsMergeField('url', $url);

$this->tbswrapper->tbsMergeField('cdn_url', base_cdn_url());
echo $this->tbswrapper->tbsRender();
include VIEWPATH . 'tbs_footer.php';
?>