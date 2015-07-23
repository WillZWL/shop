<?php
$this->load->helper('tbswrapper');
$this->tbswrapper = new Tbswrapper();

if ($data['enable_disign_id']) {
    $template = "cat_" . $data['enable_disign_id'] . ".html";

    if (!file_exists('/var/www/html/valuebasket.com/public_html/resources/template/' . $template)) {
        $template = "cat.html";
    }
} else {
    $template = "cat.html";
}


//$this->tbswrapper->tbsLoadTemplate('resources/template/cat.html', '', '', $data['lang_text']);
$this->tbswrapper->tbsLoadTemplate('resources/template/' . $template, '', '', $data['lang_text']);

if ($data["parent_cat_url"]) {
    $parent_cat_url = $data["parent_cat_url"] . '?page=' . $data["curr_page"] . '&rpp=' . $data["rpp"] . '&sort=' . $data["sort"];
}
foreach ($data["breadcrumb"] AS $key => $value) {
    foreach ($value AS $name => $url) {
        $breadcrumb[] = array("name" => $name, "url" => $url);
    }
}
$this->tbswrapper->tbsMergeBlock('breadcrumb', $breadcrumb);

foreach ($data["product_list"] AS $key => $value) {
    $prod_list[$key]["sku"] = $value["sku"];
    $prod_list[$key]["prod_name"] = $value["prod_name"];
    $prod_list[$key]["listing_status"] = $value["listing_status"];
    $prod_list[$key]["stock_status"] = $value["listing_status"] == 'I' ? $value["qty"] . " " . $data['lang_text'][$value["listing_status"]] : $data['lang_text'][$value["listing_status"]];
    $prod_list[$key]["price"] = platform_curr_format(PLATFORMID, $value["price"]);
    $prod_list[$key]["rrp_price"] = platform_curr_format(PLATFORMID, $value["rrp_price"]);
    $prod_list[$key]["discount"] = $value["discount"];
    $prod_list[$key]["prod_url"] = $value["prod_url"];
    $prod_list[$key]["short_desc"] = $value["short_desc"];
    $prod_list[$key]["image"] = get_image_file($value["image_ext"], "m", $value["sku"]);
    if ($prod_list[$key]["listing_status"] == 'I') {
        $prod_list[$key]["add_cart"] = base_url() . "cart/add_item/" . $value["sku"];
        $prod_list[$key]["css_stock_status"] = "in_stock";
    } else {
        $prod_list[$key]["css_stock_status"] = "out_stock";
    }

    #SBF2580 change the 'Arriving' product to "Green" Color

    if ($prod_list[$key]["listing_status"] == 'A') {
        $prod_list[$key]["css_stock_status"] = "in_stock";
    }
}

$page_info = array("total_result" => $data["total_result"], "total_page" => $data["total_page"]);

function pagerange($total, $curr, $delta)
{
    if (((2 * $delta) + 1) > $total) {
        return array("start" => 1, "end" => $total);
    } elseif (($curr - $delta) <= 0) {
        return array("start" => 1, "end" => (2 * $delta) + 1);
    } elseif (($curr + $delta) >= $total) {
        return array("start" => ($total - (2 * $delta)), "end" => $total);
    } else {
        return array("start" => ($curr - $delta), "end" => ($curr + $delta));
    }
}

function pagin_navigation($total, $curr, $delta)
{
    $range = pagerange($total, $curr, $delta);
    if ($curr == $range["start"] && $curr == $range["end"]) {
        $range["prev"] = NULL;
        $range["next"] = NULL;
    } elseif ($curr == $range["start"]) {
        $range["prev"] = NULL;
        $range["next"] = $curr + 1;
    } elseif ($curr == $range["end"]) {
        $range["prev"] = $curr - 1;
        $range["next"] = NULL;
    } else {
        $range["prev"] = $curr - 1;
        $range["next"] = $curr + 1;
    }
    return $range;
}

$range = pagin_navigation($data['total_page'], $data["curr_page"], $delta = 2);
if ($range["start"] && $range["end"]) {
    $j = 0;
    for ($i = $range["start"]; $i <= $range["end"]; $i++) {
        if ($i == $data["curr_page"]) {
            $page[$j]["active"] = "active";
        }
        $page[$j]["page_no"] = $i;
        $page[$j]["url"] = "?page=" . $i . "&rpp=" . $data["rpp"] . "&sort=" . $data["sort"] . "&brand_id=" . $data["brand_id"];
        $j++;
    }
}
if ($range["prev"]) {
    $prev_page = "?page=" . $range["prev"] . "&rpp=" . $data["rpp"] . "&sort=" . $data["sort"] . "&brand_id=" . $data["brand_id"];
}
if ($range["next"]) {
    $next_page = "?page=" . $range["next"] . "&rpp=" . $data["rpp"] . "&sort=" . $data["sort"] . "&brand_id=" . $data["brand_id"];
}

foreach ($data["brand_result"] AS $key => $arr) {
    $brand[$key]["url"] = $_SERVER['REDIRECT_URL'] . '?page=1&rpp=' . $data["rpp"] . '&sort=' . $data["sort"] . "&brand_id=" . $arr["id"];
    $brand[$key]["name"] = $arr["name"] . " (" . $arr["total"] . ")";
}
if ($data["cat_result"]) {
    foreach ($data["cat_result"] AS $key => $arr) {
        $cat[$key]["url"] = $arr["url"];
        $cat[$key]["name"] = $arr["name"] . " (" . $arr["total"] . ")";
    }
    $this->tbswrapper->tbsMergeBlock('cat', $cat);
} else {
    $this->tbswrapper->tbsMergeField('cat', $cat);
}
$rpp_arr = array("12", "18", "24");
foreach ($rpp_arr AS $key => $val) {
    $rpp[$key]["value"] = $val;
    $rpp[$key]["select"] = ($val == $data["rpp"]) ? "SELECTED" : "";
}
$this->tbswrapper->tbsMergeBlock('rpp', $rpp);

$sort_arr = array(
    "pop_desc" => $data['lang_text']['most_popular'],
    "latest_desc" => $data['lang_text']['new_arrival'],
    "price_asc" => $data['lang_text']['price_low_to_high'],
    "price_desc" => $data['lang_text']['price_high_to_low']
);
foreach ($sort_arr AS $key => $val) {
    $sort[$key]["key"] = $key;
    $sort[$key]["value"] = $val;
    $sort[$key]["select"] = ($key == $data["sort"]) ? "SELECTED" : "";
}
$this->tbswrapper->tbsMergeBlock('sort', $sort);

$get["page"] = $data["curr_page"];
$get["sort"] = $data["sort"];
$get["rpp"] = $data["rpp"];
$get["brand_id"] = $data["brand_id"];
if ($data["brand_id"]) {
    $brand_url = $_SERVER['REDIRECT_URL'] . '?page=' . $data["curr_page"] . '&rpp=' . $data["rpp"] . '&sort=' . $data["sort"];
}
$this->tbswrapper->tbsMergeField('brand_url', $brand_url);

$this->tbswrapper->tbsMergeField('cat_name', $data["cat_name"]);
$this->tbswrapper->tbsMergeField('get', $get);
$this->tbswrapper->tbsMergeField('prev_page', $prev_page);
$this->tbswrapper->tbsMergeField('next_page', $next_page);
$this->tbswrapper->tbsMergeField('prev_page1', $prev_page);
$this->tbswrapper->tbsMergeField('next_page1', $next_page);
$this->tbswrapper->tbsMergeBlock('brand', $brand);
$this->tbswrapper->tbsMergeBlock('page', $page);
$this->tbswrapper->tbsMergeBlock('page1', $page);
$this->tbswrapper->tbsMergeField('page_info', $page_info);
$this->tbswrapper->tbsMergeBlock('product_list', $prod_list);
$this->tbswrapper->tbsMergeField('cdn_url', base_cdn_url());
$this->tbswrapper->tbsMergeField('banner_cat_id', $data['banner_cat_id']);
$this->tbswrapper->tbsMergeField('show_discount_text', $data['show_discount_text']);
$this->tbswrapper->tbsMergeField('tracking_script', $data['tracking_script']);

echo $this->tbswrapper->tbsRender();