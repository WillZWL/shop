<?php
$this->load->helper('tbswrapper');
$this->tbswrapper = new Tbswrapper();
$this->tbswrapper->tbsLoadTemplate('resources/template/myaccount.html', '', '', $data['lang_text']);

if ($data["page"] == "profile") {
    $profile["active"] = 'class="active"';
    $profile["block"] = 'style="display:block;"';
} elseif ($data["page"] == "rma") {
    $rma["active"] = 'class="active"';
    $rma["block"] = 'style="display:block;"';

} else {
    $order["active"] = 'class="active"';
    $order["block"] = 'style="display:block;"';
}

$this->tbswrapper->tbsMergeField('order', $order);
$this->tbswrapper->tbsMergeField('rma', $rma);
$this->tbswrapper->tbsMergeField('profile', $profile);
// order history
$orderlist = array();
if ($data["orderlist"]) {
    $i = 0;
    foreach ($data["orderlist"] AS $so_no => $order_arr) {
        $orderlist[$i]["so_no"] = $so_no;
        $orderlist[$i]["join_split_so_no"] = $order_arr["join_split_so_no"];
        $orderlist[$i]["client_id"] = $order_arr["client_id"];
        $orderlist[$i]["order_date"] = $order_arr["order_date"];
        $orderlist[$i]["delivery_name"] = $order_arr["delivery_name"];
        $orderlist[$i]["order_status"] = $data['lang_text'][$order_arr["order_status_ini"]];
        $orderlist[$i]["print_invoice_html"] = "";
        if ($order_arr["is_shipped"] && (strtotime($order_arr["order_date"]) > strtotime('3 months ago'))) {
            $orderlist[$i]["print_invoice_html"] = '<br /><a href="' . base_url() . 'myaccount/print_invoice/' . $so_no . '" target="_blank" style="font-size:10px;"><u>' . $data['lang_text']['print_invoice'] . '</u></a>';
        }
        switch ($order_arr["status_desc_ini"]) {
            case "shipped_w_tracking_desc":
                $orderlist[$i]["status_desc"] = $data['lang_text'][$order_arr["status_desc_ini"] . "_1"] . $order_arr["courier_name"] . $data['lang_text'][$order_arr["status_desc_ini"] . "_2"] . '<a href="' . $order_arr["tracking_link"]->get_tracking_link() . $order_arr["tracking_number"] . '" target="_vb">' . $order_arr["tracking_number"] . '</a>';
                break;
            case "shipped_wo_tracking_desc":
                $orderlist[$i]["status_desc"] = $data['lang_text'][$order_arr["status_desc_ini"] . "_1"] . $order_arr["courier_name"] . $data['lang_text'][$order_arr["status_desc_ini"] . "_2"];
                break;
            case "shipped_w_tracking_1_desc":
            case "shipped_w_tracking_2_desc":
            case "shipped_w_tracking_3_desc":
            case "shipped_w_tracking_4_desc":
            case "shipped_w_tracking_5_desc":
                $orderlist[$i]["status_desc"] = $data['lang_text'][$order_arr["status_desc_ini"]] . '<br/>' . $data['lang_text'][$order_arr["status_desc_ini"] . "_1"] . $order_arr["courier_name"] . $data['lang_text'][$order_arr["status_desc_ini"] . "_2"] . '<a href="' . $order_arr["tracking_link"]->get_tracking_link() . $order_arr["tracking_number"] . '" target="_vb">' . $order_arr["tracking_number"] . '</a>';
                break;
            default:
                $orderlist[$i]["status_desc"] = $data['lang_text'][$order_arr["status_desc_ini"]];
        }
        $orderlist[$i]["product_name"] = $order_arr["product_name"];
        $orderlist[$i]["total_amount"] = platform_curr_format($order_arr["platform_id"], $order_arr["total_amount"]);
        $i++;
    }

}
$this->tbswrapper->tbsMergeBlock('orderlist', $orderlist);

# SBF #3591 show unpaid/underpaid orders on my account
$show_unpaid_status = array(0 => $data['lang_text']['unpaid'], 1 => $data['lang_text']['underpaid']);
if ($data["unpaid_orderlist"]) {
    $unpaid_orderlist = <<<EOT

        <table class="acount-orders" border="0">
            <col width="120"><col width="70"><col width="100"><col><col width="70"><col width="250">
            <tr>
                <th>{$data['lang_text']['order_number']}</th>
                <th>{$data['lang_text']['order_date']}</th>
                <th>{$data['lang_text']['shipped_to']}</th>
                <th>{$data['lang_text']['product_name']}</th>
                <th>{$data['lang_text']['order_total']}</th>
                <th>{$data['lang_text']['order_status']}</th>
            </tr>

EOT;

    foreach ($data["unpaid_orderlist"] as $so_no => $unpaid_arr) {
        $unpaid_status = $unpaid_arr['unpaid_status'];
        $unpaid_orderlist .= <<<EOT
            <tr>
                <td>{$unpaid_arr['client_id']} - $so_no</td>
                <td>{$unpaid_arr['order_date']}</td>
                <td>{$unpaid_arr['delivery_name']}</td>
                <td>{$unpaid_arr['product_name']}</td>
                <td>{$unpaid_arr['total_amount']}</td>
                <td>{$show_unpaid_status[$unpaid_status]}</td>
            </tr>

EOT;
    }

    $unpaid_orderlist .= "</table>";
} else {
    $unpaid_orderlist = "";
}
$this->tbswrapper->tbsMergeField('unpaid_orderlist', $unpaid_orderlist);

$wbanktransfer_contact_us = $contact_url = "";
if ($data["show_bank_transfer_contact"]) {
    $contact_url = base_url() . "contact/show_enquiry/sales";
    $wbanktransfer_contact_us = <<<EOT
        <p style ="text-align:right;">{$data['lang_text']['contact_us_for_unpaid_1']}
            <a href='$contact_url'>{$data['lang_text']['contact_us_for_unpaid_2']}</a>.</p>
EOT;

}
$this->tbswrapper->tbsMergeField('wbanktransfer_contact_us', $wbanktransfer_contact_us);

$partial_ship_text = "";
if ($data["show_partial_ship_text"] === TRUE) {
    $partial_ship_text = $data["lang_text"]["partial_ship_1"];
}
$this->tbswrapper->tbsMergeField('partial_ship_text', $partial_ship_text);

// edit profile
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
$client_obj["subscriber"] = $data["client_obj"]->get_subscriber() ? "CHECKED" : '';

$title[0]["value"] = $data['lang_text']['title_mr'];
$title[1]["value"] = $data['lang_text']['title_mrs'];
$title[2]["value"] = $data['lang_text']['title_miss'];


$title[0]["value_EN"] = "Mr";
$title[1]["value_EN"] = "Mrs";
$title[2]["value_EN"] = "Miss";


if ($data['lang_id'] != 'es') {
    $title[3]['value'] = $data['lang_text']['title_dr'];
    $title[3]["value_EN"] = "Dr";
}


foreach ($title AS $key => $value) {
    if ($title[$key]["value_EN"] == $data["client_obj"]->get_title()) {
        $title[$key]["selected"] = "SELECTED";
    } else {
        $title[$key]["selected"] = "";
    }
}

if ($data["bill_to_list"]) {
    $i = 0;
    foreach ($data["bill_to_list"] AS $cobj) {
        $bill_country_arr[$i]["id"] = $cobj->get_id();
        $bill_country_arr[$i]["display_name"] = $cobj->get_lang_name();
        if ($data["client_obj"]->get_country_id() == $cobj->get_id()) {
            $bill_country_arr[$i]["selected"] = "SELECTED";
        } else {
            $bill_country_arr[$i]["selected"] = "";
        }
        $i++;
    }
}
// rma
$rma_obj["id"] = $data["rma_obj"]->get_id();
$rma_obj["so_no"] = $data["rma_obj"]->get_so_no();
$rma_obj["client_id"] = $data["rma_obj"]->get_client_id();
$rma_obj["forename"] = $data["rma_obj"]->get_forename();
$rma_obj["surname"] = $data["rma_obj"]->get_surname();
$rma_obj["address_1"] = $data["rma_obj"]->get_address_1();
$rma_obj["address_2"] = $data["rma_obj"]->get_address_2();
$rma_obj["postcode"] = $data["rma_obj"]->get_postcode();
$rma_obj["city"] = $data["rma_obj"]->get_city();
$rma_obj["state"] = $data["rma_obj"]->get_state();
$rma_obj["country_id"] = $data["rma_obj"]->get_country_id();
$rma_obj["product_returned"] = $data["rma_obj"]->get_product_returned();
$rma_obj["serial_no"] = $data["rma_obj"]->get_serial_no();
$rma_obj["details"] = $data["rma_obj"]->get_details();

if ($data["bill_to_list"]) {
    $i = 0;
    foreach ($data["bill_to_list"] AS $cobj) {
        $bill_country_arr2[$i]["id"] = $cobj->get_id();
        $bill_country_arr2[$i]["display_name"] = $cobj->get_lang_name();
        if ($data["rma_obj"]->get_country_id() == $cobj->get_id()) {

            $bill_country_arr2[$i]["selected"] = "SELECTED";
        } else {
            $bill_country_arr2[$i]["selected"] = "";
        }
        $i++;
    }
}

$category[0]["key"] = 0;
$category[1]["key"] = 1;
$category[2]["key"] = 2;
$category[0]["value"] = $data['lang_text']["machine_only"];
$category[1]["value"] = $data['lang_text']["accessory_only"];
$category[2]["value"] = $data['lang_text']["machine_and_accessory"];

foreach ($category AS $key => $value) {
    if ($data["rma_obj"]->get_category() == $key) {
        $category[$key]["selected"] = "SELECTED";
    } else {
        $category[$key]["selected"] = "";
    }
}

$reason[0]["key"] = 0;
$reason[1]["key"] = 1;
$reason[2]["key"] = 2;
$reason[3]["key"] = 3;
$reason[0]["value"] = $data['lang_text']["need_repair"];
$reason[1]["value"] = $data['lang_text']["wrong_product_deliver"];
$reason[2]["value"] = $data['lang_text']["wrong_product_purchase"];
$reason[3]["value"] = $data['lang_text']["accident_purchase"];
foreach ($reason AS $key => $value) {
    if ($data["rma_obj"]->get_reason() == $key) {
        $reason[$key]["selected"] = "SELECTED";
    } else {
        $reason[$key]["selected"] = "";
    }
}

$action_request[0]["key"] = 0;
$action_request[1]["key"] = 1;
$action_request[2]["key"] = 2;
$action_request[0]["value"] = $data['lang_text']["swap"];
$action_request[1]["value"] = $data['lang_text']["refund"];
$action_request[2]["value"] = $data['lang_text']["repair"];
foreach ($action_request AS $key => $value) {
    if ($data["rma_obj"]->get_action_request() == $key) {
        $action_request[$key]["selected"] = "SELECTED";
    } else {
        $action_request[$key]["selected"] = "";
    }
}
if ($data["rma_confirm"]) {
    $confirm_notice = "<p class='green'>";
    $confirm_notice .= $data['lang_text']["request_submit_notice_1"] . "<br>";
    $confirm_notice .= $data['lang_text']["request_submit_notice_2_1"] . " <a href='" . base_url() . "myaccount/rma_print/" . $data["rma_obj"]->get_id() . "'  target='rma_print' style='text-decoration:underline;'>" . $data['lang_text']["request_submit_notice_2_2"] . "</a> " . $data['lang_text']["request_submit_notice_2_3"];
    $confirm_notice .= "</p>";
    $confirm_notice .= "<p style='height:30px' class='clear'></p>";
    $agreed = "CHECKED";
    $disabled = "DISABLED";
    $select_box_style = "styled_select_disabled";
} else {
    $agreed = "";
    $disabled = "";
    $select_box_style = "styled_select";
}

// notice
if ($data["notice"]["js"]) {
    echo $data["notice"]["js"];
}

$this->tbswrapper->tbsMergeField('base_url', base_url());
$this->tbswrapper->tbsMergeField('client_obj', $client_obj);
$this->tbswrapper->tbsMergeField('rma_obj', $rma_obj);
$this->tbswrapper->tbsMergeField('disabled', $disabled);
$this->tbswrapper->tbsMergeField('agreed', $agreed);
$this->tbswrapper->tbsMergeField('confirm_notice', $confirm_notice);
$this->tbswrapper->tbsMergeField('select_box_style', $select_box_style);
$this->tbswrapper->tbsMergeBlock('bill_country_arr', $bill_country_arr);
$this->tbswrapper->tbsMergeBlock('bill_country_arr2', $bill_country_arr2);
$this->tbswrapper->tbsMergeBlock('title', $title);
$this->tbswrapper->tbsMergeBlock('category', $category);
$this->tbswrapper->tbsMergeBlock('reason', $reason);
$this->tbswrapper->tbsMergeBlock('action_request', $action_request);
$this->tbswrapper->tbsMergeField('cdn_url', base_cdn_url());
$this->tbswrapper->tbsMergeField('PLATFORMCOUNTRYID', PLATFORMCOUNTRYID);

echo $this->tbswrapper->tbsRender();
?>