<?php
	foreach ($skuinfo as $sku)
	{
		$cat_name 	.= "{$sku['cat_name']};";
		$sc_name 	.= "{$sku['sc_name']};";
		$brand 		.= "{$sku['brand']};";
	}

	$cat_name	= trim($cat_name,";");
	$sc_name 	= trim($sc_name,";");
	$brand 		= trim($brand,";");

	$so_date = date("Y-m-d",strtotime($so->get_order_create_date()));

	echo "<img border=\"0\" alt=\"\" src=\"http://ase.emv3.com/P?
    	&emv_value={$so->get_amount()}
		&emv_currency={$so->get_currency_id()}
		&emv_transid={$so->get_so_no()}
		&emv_text1=$cat_name
		&emv_text2=$sc_name
		&emv_text3=$brand
		&emv_date=$so_date
		&emv_client_id=110103736
 	\">";
?>
<?php
	#sbf 1669
	$org_id = 1773704;
	$event = 267717;
	$orderNumber = $so->get_so_no();
	$orderValue = $so->get_amount();
	$currency = trim($so->get_currency_id());
	$reportInfo = "";

	$exp_delivery_date = date("Y-m-d",(3*60*60*24) + strtotime($so->get_order_create_date()));	# add 3 days to our purchase date

	$review = "name={$client->get_forename()} {$client->get_surname()}&email={$client->get_email()}&expDeliveryDate=$exp_delivery_date";
    $review = urlencode($review);

    if ($so_items)
    {
            $f = "";
            foreach ($so_items as $item)
            {
                    $f1 = urlencode($item->get_prod_sku());
                    $f2 = urlencode($item->get_name());
                    $f3 = urlencode($item->get_amount());
                    $f4 = urlencode($item->get_qty());
                    $f .= "f1=$f1&f2=$f2&f3=$f3&f4=$f4|";
            }
            $reportInfo = trim($f, "|");
    }

    echo "<img src=\"http://tbs.tradedoubler.com/report?organization="
            .$org_id."&event="
            .$event."&orderNumber="
            .$orderNumber."&orderValue="
            .$orderValue."&currency="
            .$currency." &reportInfo="
            .$reportInfo."&review="
            .$review."\" >";
