<?php
    
include VIEWPATH.'tbs_header.php';
$this->tbswrapper->tbsLoadTemplate('resources/template/myaccount_rma_confirm_'.get_lang_id().'.html');
$url["myaccount"] = base_url()."myaccount/ws_myaccount";
$url["rma"] = base_url()."myaccount/ws_rma";
$url["profile"] = base_url()."myaccount/profile";
$url["logout"] = base_url()."logout/ws_logout";
$cat_arr = array(0=>"Machine Only",1=>"Accessory Only",2=>"Machine and Accessories");
$reason_arr = array(0=>"Needs Repair Under Warranty",1=>"Wrong Product Delivered",2=>"Wrong Product Purchased",3=>"Accidently Purchased (conditions apply)");
$action_arr = array(0=>"Swap",1=>"Refund",2=>"Repair");
if($data["rma_obj"])
{
    $rma_obj["id"] = $data["rma_obj"]->get_id();
    $rma_obj["so_no"] = $data["rma_obj"]->get_so_no();
    $rma_obj["category"] = $cat_arr[$data["rma_obj"]->get_category()];
    $rma_obj["reason"] = $reason_arr[$data["rma_obj"]->get_reason()];
    $rma_obj["action_request"] = $action_arr[$data["rma_obj"]->get_action_request()];
    $rma_obj["details"] = $data["rma_obj"]->get_details();
    $rma_obj["shipfrom"] = $data["rma_obj"]->get_shipfrom();
    $rma_obj["product_returned"] = $data["rma_obj"]->get_product_returned();
}
if($data["order"])
{
    $order_obj["order_create_date"] = date("Y-m-d",strtotime($data["order"]->get_order_create_date()));
}

if($data["components_list"])
{
    $ar_components = @explode("|", $data["rma_obj"]->get_components());
    foreach($data["components_list"] AS $key=>$name)
    {
        if($key < 5)
        {
            $ret_prod_arr_1[$key]['name'] = $name;
            $ret_prod_arr_1[$key]['key'] = $key;
            $ret_prod_arr_1[$key]['checked'] = $ar_components[$key]?" CHECKED":"";
        }
        elseif($key < 10)
        {
            $ret_prod_arr_2[$key]['name'] = $name;
            $ret_prod_arr_2[$key]['key'] = $key;
            $ret_prod_arr_2[$key]['checked'] = $ar_components[$key]?" CHECKED":"";
        }
        elseif($key < 14)
        {
            $ret_prod_arr_3[$key]['name'] = $name;
            $ret_prod_arr_3[$key]['key'] = $key;
            $ret_prod_arr_3[$key]['checked'] = $ar_components[$key]?" CHECKED":"";
        }
    }
    $ret_prod_arr_other["value"] = htmlspecialchars(end($ar_components));
}
$this->tbswrapper->tbsMergeBlock('ret_prod_arr_1', $ret_prod_arr_1);
$this->tbswrapper->tbsMergeBlock('ret_prod_arr_2', $ret_prod_arr_2);
$this->tbswrapper->tbsMergeBlock('ret_prod_arr_3', $ret_prod_arr_3);
$this->tbswrapper->tbsMergeField('ret_prod_arr_other', $ret_prod_arr_other);
$this->tbswrapper->tbsMergeField('rma_obj', $rma_obj);
$this->tbswrapper->tbsMergeField('order_obj', $order_obj);

$skype_cert = array('url'=> base_url().'search/?from=c&filter=skypecert');
$skype_phone = array('url'=> base_url().'search/?from=c&catid=9');

$this->tbswrapper->tbsMergeField('skype_cert', $skype_cert);
$this->tbswrapper->tbsMergeField('skype_phone', $skype_phone);
$this->tbswrapper->tbsMergeField('base_url', base_url());
$this->tbswrapper->tbsMergeField('url', $url);

$this->tbswrapper->tbsMergeField('cdn_url', base_cdn_url());
echo $this->tbswrapper->tbsRender();
include VIEWPATH.'tbs_footer.php';
?>