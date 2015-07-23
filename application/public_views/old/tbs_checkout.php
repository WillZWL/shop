<?php
if ($_GET["hidden"]) {
    $this->tbswrapper->tbsLoadTemplate('resources/template/checkout_lytebox_' . get_lang_id() . '.html');
} else {
    include VIEWPATH . 'tbs_header.php';
    $this->tbswrapper->tbsLoadTemplate('resources/template/checkout_' . get_lang_id() . '.html');
}
$chk_cart = $data['chk_cart'];
if ($chk_cart) {
    $cart_status = 1;
}
$debug = $data['debug'];
$idx = 0;
$pur_prod_arr = array();
for ($j = 0; $j < count($chk_cart); $j++) {
    $idx = $j + 1;
    $item = $data['cart_item'][$chk_cart[$j]["sku"]];
    $pur_prod_arr[$j]['idx'] = $idx;
    $pur_prod_arr[$j]['display_name'] = $item->get_content_prod_name() ? $item->get_content_prod_name() : $item->get_prod_name();
    $pur_prod_arr[$j]['prod_url'] = base_url() . "mainproduct/info/" . $chk_cart[$j]["sku"];
    $pur_prod_arr[$j]['unit_price'] = platform_curr_format(PLATFORMID, $chk_cart[$j]["price"]);
    $pur_prod_arr[$j]['item_price'] = platform_curr_format(PLATFORMID, $chk_cart[$j]["price"] * $chk_cart[$j]["qty"]);
    $pur_prod_arr[$j]['prod_img'] = base_url() . get_image_file($item->get_image(), 'm', $item->get_sku());
    $pur_prod_arr[$j]['change_qty_url'] = base_url() . 'checkout/update/' . $chk_cart[$j]["sku"] . "/";

    if (!$chk_cart[$j]["promo"]) {
        $pur_prod_arr[$j]['remove_url'] = base_url() . "checkout/remove/" . $chk_cart[$j]["sku"] . ($debug ? "/$debug" : '');
    } else {
        $pur_prod_arr[$j]['remove_url'] = "";
    }
    $total += $chk_cart[$j]["price"] * $chk_cart[$j]["qty"];
}
$this->tbswrapper->tbsMergeBlock('pur_prod_arr', $pur_prod_arr);

$idx = 0;
for ($j = 0; $j < count($chk_cart); $j++) {
    $idx = $j + 1;
    $qty = array();
    if (!$chk_cart[$j]["promo"]) {
        if ($item->get_display_quantity()) {
            $quantity = min($item->get_website_quantity(), $item->get_display_quantity());
        } else {
            $quantity = $item->get_website_quantity();
        }
        $max_qty = max($quantity, $chk_cart[$j]["qty"]);
        $qty = array();
        ($max_qty > 10) ? $max_qty = 10 : "";
        for ($key = 0; $key < $max_qty; $key++) {
            $qty[$key]["value"] = $key;
            if ($key == $chk_cart[$j]["qty"]) {
                $qty[$key]["selected"] = "SELECTED";
            } else {
                $qty[$key]["selected"] = "";
            }
        }
    } else {
        $qty[0]["value"] = $chk_cart[$j]["qty"];
        $qty[0]["selected"] = "SELECTED";
    }
    $this->tbswrapper->tbsMergeBlock('qty' . $idx, $qty);
}
if ($data['promo']["valid"] !== NULL) {
    if (!$data['promo']["valid"] || $data['promo']["error"]) {
        $promo['msg'] = "Sorry, Promotion Code Invalid";
        $promo['msg_color'] = "red";
    } else {
        $promo['msg'] = "Promotion Code Accepted";
        $promo['msg_color'] = "green";
    }
}
if ($_SESSION["promotion_code"]) {
    $promo["code"] = $_SESSION["promotion_code"];
    if ($data['promo']["valid"] && isset($data['promo']["disc_amount"])) {
        $promo_disc_amount = $data['promo']["disc_amount"];
    }
}
$delivery = 0;
$granttotal = $total + $delivery + $surcharge - $promo_disc_amount;
$total = platform_curr_format(PLATFORMID, $total);
$delivery = platform_curr_format(PLATFORMID, $delivery);
$grant_total = platform_curr_format(PLATFORMID, $granttotal);
if ($promo_disc_amount > 0) {
    $promo['disc_amount'] = "-" . platform_curr_format(PLATFORMID, $promo_disc_amount);
    $promo['url'] = base_url() . "checkout" . ($debug ? "/index/$debug" : '');
}

$forget_pw_url = base_url() . "forget_password?back=checkout";

$this->tbswrapper->tbsMergeField('debug', $debug);
$this->tbswrapper->tbsMergeField('base_url', base_url());
$this->tbswrapper->tbsMergeField('cart_status', $cart_status);
$this->tbswrapper->tbsMergeField('total', $total);
$this->tbswrapper->tbsMergeField('delivery', $delivery);
$this->tbswrapper->tbsMergeField('surcharge', $surcharge);
$this->tbswrapper->tbsMergeField('granttotal', $granttotal);
$this->tbswrapper->tbsMergeField('grant_total', $grant_total);
$this->tbswrapper->tbsMergeField('promo', $promo);
$this->tbswrapper->tbsMergeField('forget_pw_url', $forget_pw_url);


if (!$_SESSION["client"]["logged_in"]) {
    $form['login'] = 'customer_login';
    $form['purchaser_form'] = 'New Customers';
} else {
    $form['purchaser_form'] = 'Purchasing Information';
    $form['login'] = '';
}
//$bill$_SESSION["POSTFORM"]["country_id"]
$i = 0;
$bc_selected = $_SESSION["POSTFORM"]["country_id"] ? $_SESSION["POSTFORM"]["country_id"] : $data["thiscountry"];
if ($data['bill_to_list']) {
    foreach ($data['bill_to_list'] AS $c_obj) {
        $bill_to_list[$i]['id'] = $c_obj->get_id();
        $bill_to_list[$i]['display_name'] = $c_obj->get_lang_name() ? $c_obj->get_lang_name() : $c_obj->get_name();
        if ($bc_selected == $c_obj->get_id()) {
            $bill_to_list[$i]['selected'] = "selected";
        }
        $i++;
    }
}
if ($data['bill_to_list']) {
    foreach ($data['sell_to_list'] AS $c_obj) {
        $sell_to_list[$i]['id'] = $c_obj->get_id();
        $sell_to_list[$i]['display_name'] = $c_obj->get_lang_name() ? $c_obj->get_lang_name() : $c_obj->get_name();
        if ($bc_selected == $c_obj->get_id()) {
            $sell_to_list[$i]['selected'] = "selected";
        }
        $i++;
    }
}

$sess_fm = $_SESSION["POSTFORM"];
$_SESSION["POSTFORM"]["subscriber"] = 1;
$sess_fm["subscriber"] = $_SESSION["POSTFORM"]["subscriber"] ? "CHECKED" : "";
$this->tbswrapper->tbsMergeBlock('bill_to_list', $bill_to_list);
$this->tbswrapper->tbsMergeBlock('sell_to_list', $sell_to_list);
$this->tbswrapper->tbsMergeField('form', $form);
$this->tbswrapper->tbsMergeField('sess_fm', $sess_fm);
$this->tbswrapper->tbsMergeField('sess_fm_1', $sess_fm);

if ($data['all_trial'] && $data['all_virtual']) {
    $require_client_detail = 0;
    $require_star = "";
    $js_card_func = "HideCard";
} else {
    $require_client_detail = 1;
    $require_star = "*";
    $require_notempty = "notEmpty";
    $js_card_func = "ChangeCard";
}
$this->tbswrapper->tbsMergeField('p_enc', $data['p_enc']);
$this->tbswrapper->tbsMergeField('all_trial', $data['all_trial']);
$this->tbswrapper->tbsMergeField('all_virtual', $data['all_virtual']);
$this->tbswrapper->tbsMergeField('require_client_detail', $require_client_detail);
$this->tbswrapper->tbsMergeField('require_star', $require_star);
$this->tbswrapper->tbsMergeField('js_card_func', $js_card_func);
$this->tbswrapper->tbsMergeField('cdn_url', base_cdn_url());


$this->xajax->printJavascript();
?>
<script src="<?= base_url() ?>js/jquery.showLoading.min.js" type="text/javascript"></script>
<link href="<?= base_url() ?>css/showLoading.css" rel="stylesheet" type="text/css"/>
<script language="javascript">
    createCallBack(['country_id', 'del_state', 'del_postcode']);
</script><?php
echo $this->tbswrapper->tbsRender();
if (!$_GET["hidden"]) {
    include VIEWPATH . 'tbs_footer.php';
}
?>