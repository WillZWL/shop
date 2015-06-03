<?php
$this->load->helper('tbswrapper');
$this->tbswrapper = new Tbswrapper();

if(!$data["cart"])
{
	$this->tbswrapper->tbsLoadTemplate('resources/template/review_order_no_prod.html', '', '', $data['lang_text']);
}
else
{
	$this->tbswrapper->tbsLoadTemplate('resources/template/review_order.html', '', '', $data['lang_text']);

	if($data['cart'])
	{
		foreach($data['cart'] AS $sku_arr)
		{
			foreach ($sku_arr as $arr) {
				$item[] = $arr;
			}
		}
	}

// echo '<pre>';
// print_r($item);die;

	if($data['promo']["valid"] !== NULL)
	{
		if(!$data['promo']["valid"] || $data['promo']["error"])
		{
			$promo['msg'] = $data['lang_text']['promotion_code_invalid'];
			$promo['msg_color'] = "red";
		}
		else
		{
			$promo['msg'] = $data['lang_text']['promotion_code_valid'];
			$promo['msg_color'] = "green";
		}
	}

	if($_SESSION["promotion_code"])
	{
		$promo["code"] = $_SESSION["promotion_code"];
		if ($data['promo']["valid"] && isset($data['promo']["disc_amount"]))
		{
			$promo_disc_amount = $data['promo']["disc_amount"];
		}
		if($promo_disc_amount > 0)
		{
			$promo['disc_amount'] = "-".platform_curr_format(PLATFORMID, $promo_disc_amount);
		}
	}
	$this->tbswrapper->tbsMergeField('delivery_charge', $data["delivery_charge"]);
	$this->tbswrapper->tbsMergeField('item_amount', $data["item_amount"]);
	$this->tbswrapper->tbsMergeField('total', $data["total"]);
    $this->tbswrapper->tbsMergeField('show_battery_message', ($data["show_battery_message_w_amount"] ? 'T' : 'F'));
    if ($data["show_battery_message_w_amount"])
        $this->tbswrapper->tbsMergeField('minimum_battery_amount', platform_curr_format(PLATFORMID, $data["show_battery_message_w_amount"]));
	$this->tbswrapper->tbsMergeBlock('item', $item);
	$this->tbswrapper->tbsMergeField('promo', $promo);
}

if($data['need_gst_display'])
{
	if ($data['gst_order'])
		$total_msg = $data['lang_text']['total_with_gst'];
	else
		$total_msg = $data['lang_text']['total_no_gst'];
}
else
{
	$total_msg = $data['lang_text']['total'];
}

if ($data["allow_bulk_sales"])
{
	if (in_array('1', $data["allow_bulk_sales"]))
	{
		$allow_bulk_sales_popup = '1';
	}
	else
	{
		$allow_bulk_sales_popup = '0';
	}
}


$this->tbswrapper->tbsMergeField('need_gst_display', ($data['need_gst_display'] ? 'T' : 'F'));
$this->tbswrapper->tbsMergeField('gst_order', ($data['gst_order'] ? 'T' : 'F'));
$this->tbswrapper->tbsMergeField('gst_total', $data['gst_total']);

$this->tbswrapper->tbsMergeField('total_msg', $total_msg);
$this->tbswrapper->tbsMergeField('base_url', base_url());
$this->tbswrapper->tbsMergeField('cdn_url', base_cdn_url());

$this->tbswrapper->tbsMergeField('tracking_script', $data['tracking_script']);
$this->tbswrapper->tbsMergeField('allow_bulk_sales_popup', $allow_bulk_sales_popup);


echo $this->tbswrapper->tbsRender();
