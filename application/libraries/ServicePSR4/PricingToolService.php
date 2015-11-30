<?php
namespace ESG\Panther\Service;

class PricingToolService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getPricingToolPanel($prod_sku = "", $platform_type = "", $master_sku = "", $prod_obj = "")
    {
        if (empty($prod_sku) || empty($platform_type)) {
            return false;
        }

        $platform_objlist = $this->getDao('PlatformBizVar')->getPricingToolPlatformList($prod_sku, $platform_type);

        if ($platform_objlist) {
            $objcount = 0;
            $pdata = [];

            if ($platform_type == "WEBSITE") {
                $scenario_arr = $this->getScenarioData();
            }

            foreach ($platform_objlist as $platform_obj) {
                $platform_id = $platform_obj->getSellingPlatformId();

                $tmp = $this->getService('Price')->getPricingToolInfo($platform_id, $prod_sku);
                $pdata[$platform_id]["pdata"] = $tmp;
                $pdata[$platform_id]["obj"] = $platform_obj;

                $type = "update";
                $price_obj = $this->getDao('Price')->get(["sku" => $prod_sku, "platform_id" => $platform_id]);
                if ( !$price_obj ) {
                    $price_obj = $this->getDao('Price')->get();
                    $type = "add";
                }

                $data["formtype"][$platform_id] = $type;
                $data["price_list"][$platform_id] = $price_obj;
                $_SESSION["price_obj_" . $platform_id] = serialize($price_obj);

                # Get private data by platform type
                switch ($platform_type) {
                    case 'EBAY':
                        $param = [];
                        $param['platform_id'] = $platform_id;
                        $param['country_id'] = $platform_obj->getPlatformCountryId();
                        $param['prod_sku'] = $prod_sku;

                        $ebay_data = $this->getPrivateDataForEbay($param);

                        $data["sub_cat_margin"][$platform_id] = $tmp["dst"]->getSubCatMargin();
                        $data["price_ext"][$platform_id] = $ebay_data["price_ext"];
                        $data["store_cat_list"][$platform_id] = $ebay_data["store_cat_list"];
                        $data["store_cat_2_list"][$platform_id] = $ebay_data["store_cat_2_list"];
                        break;

                    case 'WEBSITE':
                        $param = [];
                        $param['platform_id'] = $platform_id;
                        $param['country_id'] = $platform_obj->getPlatformCountryId();
                        $param['master_sku'] = $master_sku;
                        $param['prod_sku'] = $prod_sku;
                        $param['scenario_arr'] = $scenario_arr;
                        $param['auto_price'] = $price_obj->getAutoPrice();
                        $param['scenarioid'] = $price_obj->getDeliveryScenarioid();
                        $param['is_advertised'] = $price_obj->getIsAdvertised();
                        $param['current_price'] = $tmp["dst"]->getCurrentPlatformPrice();
                        $param['lang_id'] = $platform_obj->getLanguageId();
                        $param['prod_obj'] = $prod_obj;

                        $website_data = $this->getPrivateDataForWebsite($param);

                        $data['delivery_info'][$platform_id] = $website_data['delivery_info'];
                        $data['feed_include'][$platform_id] = $website_data['feed_include'];
                        $data['feed_exclude'][$platform_id] = $website_data['feed_exclude'];
                        $pdata[$platform_id]["competitor"] = $website_data["competitor"];
                        $pdata[$platform_id]["adwords_obj"] = $website_data["adwords_obj"];
                        $pdata[$platform_id]["gsc_comment"] = $website_data["google_comment"]["gsc_comment"];
                        $pdata[$platform_id]["enabled_pla_checkbox"] = $website_data["google_comment"]["enabled_pla_checkbox"];
                        break;

                    default:
                        # code...
                        break;
                }

                $objcount++;
            }
            $data["objcount"] = $objcount;
            $data['pdata'] = $pdata;
        }
        return $data;
    }

    public function getPrivateDataForEbay($param)
    {
        if (!($priceExtObj = $this->getDao('PriceExtend')->get(["sku" => $param['prod_sku'], "platform_id" => $param['platform_id']]))) {
            if (!isset($priceExtVo)) {
                $priceExtVo = $this->getDao('PriceExtend')->get();
            }
            $priceExtObj = clone $priceExtVo;
        }

        if (!$priceExtObj->getHandlingTime()) {
            switch (strtoupper($param['platform_id'])) {
                case 'EBAYAU':
                case 'EBAYSG':
                case 'EBAYUK':
                    $default_handling_time = 10;
                    break;
                case 'EBAYUS':
                    $default_handling_time = 1;
                    break;
                default:
                    $default_handling_time = 10;
                    break;
            }
            $priceExtObj->setHandlingTime($default_handling_time);
        }

        $ebay_data = [];
        $_SESSION["price_ext"] = serialize($priceExtObj);
        $ebay_data["price_ext"] = $priceExtObj;
        $ebay_data["store_cat_list"] = $this->getObjListForExtCate('EBAY_STORE', 1, $param['country_id']);
        $ebay_data["store_cat_2_list"] = $this->getObjListForExtCate('EBAY_STORE', 2, $param['country_id']);

        return $ebay_data;
    }

    public function getPrivateDataForWebsite($param)
    {
        $website_data = [];
        $website_data['delivery_info'] = $this->getDeliveryInfo($param);
        $website_data["feed_include"] = $this->getDao('AffiliateSkuPlatform')->getFeedListBySku($param['prod_sku'], $param['platform_id'], 2);
        $website_data["feed_exclude"] = $this->getDao('AffiliateSkuPlatform')->getFeedListBySku($param['prod_sku'], $param['platform_id'], 1);
        $website_data["competitor"] = $this->getCompetitor($param);
        $website_data["adwords_obj"] = $this->getAdwordsData($param);
        $website_data["google_comment"] = $this->getGoogleGscComment($param);

        return $website_data;
    }

    public function getAdwordsData($param)
    {
        if ($obj = $this->getDao('AdwordsData')->get(["sku" => $param['prod_sku'], "platform_id" => $param['platform_id']])) {
            return $obj;
        }

        return false;
    }

    public function getGoogleGscComment($param)
    {
        $internal_gsc_comment = "";
        if (!$prod_identifer_obj = $this->getDao('ProductIdentifier')->get(["prod_grp_cd" => $param['prod_obj']->getProdGrpCd(), "colour_id" => $param['prod_obj']->getColourId(), "country_id" => $param['country_id']])) {
            $internal_gsc_comment = "No mpn value. ";
        } else if (!$prod_identifer_obj->getMpn()) {
            $internal_gsc_comment = "No mpn value. ";
        }

        if ($param['prod_obj']) {
            if ($param['prod_obj']->getStatus() != 2) {
                $internal_gsc_comment .= "/Product is not listed in product Mgmt. ";
            }
        }

        if ($product_content_obj = $this->getDao('ProductContent')->get(["prod_sku" => $param['prod_sku'], "lang_id" => $param['lang_id']])) {
            if (!$product_content_obj->getDetailDesc()) {
                $internal_gsc_comment .= "/No detail desc. ";
            }
        }

        $gsc_where = $gsc_option = [];
        $gsc_where['cm.id'] = $param['prod_sku'];
        $gsc_where['cm.ext_party'] = "GOOGLEBASE";
        $gsc_where['cm.country_id'] = $param['country_id'];
        $gsc_where['cm.status'] = 1;
        $gsc_option['limit'] = 1;

        if (!$google_cat_obj = $this->getDao('CategoryMapping')->getGooglebaseCatListWithCountry($gsc_where, $gsc_option)) {
            $internal_gsc_comment .= " No google product title/category. ";
        } else {
            if (!$google_cat_obj->getProductName()) {
                $internal_gsc_comment .= " No google product title. ";
            }

            if (!$google_cat_obj->getExtName()) {
                $internal_gsc_comment .= " No google category.";
            }
        }

        $enabled_pla_checkbox = $internal_gsc_comment ? 0 : 1;
        $gsc_comment = "";
        $google_arr = [];
        if ($goog_shop_obj = $this->getDao('GoogleShopping')->get(["sku" => $param['prod_sku'], "platform_id" => $param['platform_id']])) {
            $goog_shop_result = $goog_shop_obj->getApiRequestResult();
            if ($goog_shop_obj->getStatus() == 0) {
                $gsc_comment = "PAUSE";
                if ($goog_shop_result == 0) {
                    $gsc_comment .= " - Fail";
                } else {
                    $gsc_comment .= " - Success";
                }
            } else {
                if (!$goog_shop_result || $param['is_advertised'] != "Y") {
                    $gsc_comment = $goog_shop_obj->getComment();
                    if (!$gsc_comment) {
                        $gsc_comment = $internal_gsc_comment;
                    } else {
                        $gsc_temp_list = explode(';', $gsc_comment);
                        $gsc_comment = array_pop($gsc_temp_list);
                    }

                    if (!$internal_gsc_comment) {
                        $enabled_pla_checkbox = 1;
                    }
                } else {
                    $gsc_comment = "Success";
                }
            }
        }
        if ($internal_gsc_comment) {
            $gsc_comment = $internal_gsc_comment . "<br>" . $gsc_comment;
        }
        $google_arr["gsc_comment"] = $gsc_comment ? $gsc_comment : $internal_gsc_comment;
        $google_arr["enabled_pla_checkbox"] = $enabled_pla_checkbox;

        return $google_arr;
    }
    public function getDeliveryInfo($param = [])
    {
        if ($deliverytime_obj = $this->getDao('DeliveryTime')->getDeliverytimeObj($param['country_id'], $param['scenarioid'])) {
            $info_arr = [];
            $info_arr["scenarioid"] = $deliverytime_obj->getScenarioid();
            $info_arr["scenarioname"] = $param['scenario_arr'][$deliverytime_obj->getScenarioid()];
            $info_arr["del_min_day"] = $deliverytime_obj->getDelMinDay();
            $info_arr["del_max_day"] = $deliverytime_obj->getDelMaxDay();
            $info_arr["margin"] = $deliverytime_obj->getMargin();
            return $info_arr;
        }

        return false;
    }

    public function getScenarioData()
    {
        $scenario_arr = [];
        if ($delivery_scenario_list = $this->getDao('DeliveryTime')->getDeliveryScenarioList()) {
            foreach ($delivery_scenario_list as $key => $obj) {
                $scenario_arr[$obj->id] = $obj->name;
            }
        }

        return $scenario_arr;
    }

    public function getCompetitor($param)
    {
        if (!$param['master_sku']) {
            return false;
        }

        $comp_mapping_list = $this->getDao('CompetitorMap')->getActiveComp($param['master_sku'], $param['country_id']);
        if ($comp_mapping_list) {
            $comp_arr = [];
            if ($param['auto_price'] == "C") {
                $hasactivematch = 0;
            } else {
                $hasactivematch = 1;
            }

            $comp_arr["comp_mapping_list"] = $comp_mapping_list;
            $comp_arr["price_diff"] = [];
            foreach ($comp_mapping_list as $row) {
                $ship_charge = $row->getCompShipCharge();
                if (empty($ship_charge)) {
                    $ship_charge = 0;
                }

                $comp_arr["total_price"] = number_format(($row->getNowPrice() + $ship_charge), 2, '.', '');
                $comp_arr["price_diff"][$row->getCompetitorId()] = number_format(($comp_arr["total_price"] - $param['current_price']), 2);

                if ($row->getMatch() == 1) {
                    $hasactivematch++;
                }
            }
            $comp_arr["hasactivematch"] = $hasactivematch;
            asort($comp_arr["price_diff"]);

            return $comp_arr;
        }

        return false;
    }

    public function getRrpFactorBySku($sku='')
    {
        $default_rrp_factor = 1.34;
        $rrp_factor = NULL;

        if ($sku == '') {
            $rrp_factor = $default_rrp_factor;
        } else {
            $price_obj = $this->getDao('Price')->get(['sku'=>$sku]);
            if ($price_obj) {
                $rrp_factor = $price_obj->getRrpFactor();
            } else {
                $product_obj = $this->getDao('Product')->get(['sku'=>$sku]);

                $where = [];
                $where['p.cat_id'] = $product_obj->getCatId();
                $where['p.sub_cat_id'] = $product_obj->getSubCatId();
                $where['p.sub_sub_cat_id'] = $product_obj->getSubSubCatId();
                $where['p.sku != '] = $sku;

                $list = $this->getDao('Product')->getListHavingPrice($where, array('limit'=>1));
                if (count($list) == 0) {
                    $rrp_factor = $default_rrp_factor;
                } else {
                    $product_obj = $list[0];
                    $price_obj = $this->getDao('Price')->get(['sku'=>$product_obj->getSku()]);
                    if ($price_obj) {
                        $rrp_factor = $price_obj->getRrpFactor();
                    } else {
                        $rrp_factor = $default_rrp_factor;
                    }
                }
            }
        }

        if (is_null($rrp_factor)) {
            $rrp_factor = $default_rrp_factor;
        }

        return $rrp_factor;
    }

    public function getProductUrl($prod_sku)
    {
        if ($obj = $this->getDao('Product')->getProdUrl(['p.sku'=>$prod_sku], ['limit'=>1])) {
            $website_link = $this->getService('ContextConfig')->valueOf("website_domain");
            $prod_url = $obj->getProductUrl();

            return $website_link . $prod_url;
        }
        return false;
    }

    public function getQtyInOrders($prod_sku)
    {
        $qty_arr[7] = $this->getDao('So')->getQuantityInOrders($prod_sku, 7);
        $qty_arr[30] = $this->getDao('So')->getQuantityInOrders($prod_sku, 30);
        return $qty_arr;
    }

    public function getFreightCatById($id)
    {
        if ($t = $this->getDao('FreightCategory')->get(['id'=>$id])) {
            return $t->getName();
        }
        return false;
    }

    public function getObjListForExtCate($ext_party, $level, $country_id)
    {
        return $this->getDao('ExternalCategory')->getList([
                    "ext_party" => $ext_party,
                    "level" => $level,
                    "country_id" => $country_id,
                    "status" => 1],
                    ["orderby" => "ext_name ASC",
                    "limit" => -1
                ]);
    }


    public function setSkuFeedStatus($affiliate_id, $sku, $platform_id, $status_id)
    {
        if ($obj = $this->getDao('AffiliateSkuPlatform')->get(['sku'=>$sku, 'affiliate_id'=>$affiliate_id])) {
            $obj->setPlatformId($platform_id);
            $obj->setStatus($status_id);

            $this->getDao('AffiliateSkuPlatform')->update($obj);
        } else {
            $obj = $this->getDao('AffiliateSkuPlatform')->get();
            $obj->setSku($sku);
            $obj->setAffiliateId($affiliate_id);
            $obj->setPlatformId($platform_id);
            $obj->setStatus($status_id);

            $this->getDao('AffiliateSkuPlatform')->insert($obj);
        }
    }

    public function setFeedPlatform($affiliate_id, $platform_id)
    {
        $obj = $this->getDao('Affiliate')->get(['affiliate_id'=>$affiliate_id]);
        $obj->setPlatformId($platform_id);
        $this->getDao('Affiliate')->update($obj);
    }

    public function print_pricing_tool_js($tool_path)
    {
        header("Content-type: text/javascript; charset: UTF-8");
        header("Cache-Control: must-revalidate");
        $offset = 60 * 60 * 24;
        $ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
        header($ExpStr);
        $js = "
        var result_recv = false;

        function rePrice(platform, sku)
        {
            var remote_url;
            var sp = document.getElementById('sp['+platform+']').value * 1;

            if (platform.substring(0, 3).toUpperCase() == 'WEB') {
                var auto_price = document.getElementById('auto_price_cb['+platform+']');
                var selected_auto_price = auto_price.options[auto_price.selectedIndex].value;

                if (selected_auto_price != null) {
                    if (selected_auto_price == 'Y') {
                        document.getElementById('sp['+platform+']').value = 0;
                        sp = 0;
                    }
                }
            } else {
                if (document.getElementById('auto_price_cb['+platform+']') != null) {
                    if (document.getElementById('auto_price_cb['+platform+']').checked == true) {
                        document.getElementById('sp['+platform+']').value = 0;
                        sp = 0;
                    }
                }

            }
            remote_url = '/{$tool_path}/get_profit_margin_json/' + platform + '/' + sku +'/' + sp;
            result_recv = false;
            $.ajax({
                type: 'POST',
                url: remote_url,
                contentType: 'application/json; charset=utf-8',
                dataType: 'json',
                success: function(msg)
                {
                    declared = msg.get_declared_value;
                    vat = msg.get_vat;
                    duty = msg.get_duty;
                    payment = msg.get_payment_charge;
                    forex_fee = msg.get_forex_fee;
                    delivery_charge = msg.get_delivery_cost;
                    commission = msg.get_sales_commission;
                    cost = msg.get_cost;
                    total = msg.get_price;
                    profit = msg.get_profit;
                    margin = msg.get_margin;

                    if (!result_recv) {
                        sp = document.getElementById('sp['+platform+']').value * 1;
                        a = parseFloat(sp);
                        b = parseFloat(msg.based_on);

                        if (a != b) {
                            var pfto = document.getElementById('profit['+platform+']');
                            pfto.innerHTML = 'Wait...';
                            var mgno = document.getElementById('margin['+platform+']');
                            mgno.innerHTML = '-';

                            var hidden_profit = document.getElementById('hidden_profit['+platform+']');
                            hidden_profit.value = profit;
                            var hidden_margin = document.getElementById('hidden_margin['+platform+']');
                            hidden_margin.value = margin;

                            return; // if input price is different, ignore this response
                        }
                    } else {

                        return; // we already received the result, return
                    }

                    result_recv = true;

                    if (platform.substring(0, 3).toUpperCase() == 'WEB') {
                        if (selected_auto_price != null) {
                            if(selected_auto_price == 'Y') {
                                document.getElementById('sp['+platform+']').readOnly = false;
                                document.getElementById('sp['+platform+']').value = total;
                                document.getElementById('sp['+platform+']').readOnly = true;
                            }
                        }
                    } else {
                        if (document.getElementById('auto_price_cb['+platform+']') != null) {
                            if(document.getElementById('auto_price_cb['+platform+']').checked == true) {
                                document.getElementById('sp['+platform+']').readOnly = false;
                                document.getElementById('sp['+platform+']').value = total;
                                document.getElementById('sp['+platform+']').readOnly = true;
                            }
                        }
                    }

                    if(margin > 0) {
                        color = '#ddffdd';
                    } else {
                        color = '#ffdddd';
                    }

                    if(platform.substring(0, 5).toUpperCase() == 'QOO10') {
                        if(margin < 5) {
                            alert('Warning: The profit margin at the selected selling price is less than 5%. Please check and confirm.');
                        }
                        if(sp==0 || sp =='') {
                            disable_element('update_pricing_tool');
                            alert('Warning: Selling Price cannot be empty or zero. Please amend.')
                        } else {
                            enable_element('update_pricing_tool');
                        }
                    }

                    var rowo = document.getElementById('row['+platform+']');
                    rowo.style.backgroundColor = color;

                    var declo = document.getElementById('declare['+platform+']');
                    if (declo != null) {
                        declo.innerHTML = declared;
                    }
                    var vato = document.getElementById('vat['+platform+']');
                    if (vato != null) {
                        vato.innerHTML = vat;
                    }
                    var dutyo = document.getElementById('duty['+platform+']');
                    dutyo.innerHTML = duty;
                    var pmo = document.getElementById('pc['+platform+']');
                    pmo.innerHTML = payment;
                    var ffo = document.getElementById('forex_fee['+platform+']');
                    ffo.innerHTML = forex_fee;
                    var dco = document.getElementById('delivery_charge['+platform+']');
                    dco.innerHTML = delivery_charge;
                    var comm = document.getElementById('comm['+platform+']');
                    comm.innerHTML = commission;
                    var tco = document.getElementById('total_cost['+platform+']');
                    tco.innerHTML = cost;
                    var ttlo = document.getElementById('total['+platform+']');
                    ttlo.innerHTML = total;
                    var pfto = document.getElementById('profit['+platform+']');
                    pfto.innerHTML = profit;
                    var mgno = document.getElementById('margin['+platform+']');
                    mgno.innerHTML = margin + '%';
                    var hidden_profit = document.getElementById('hidden_profit['+platform+']');
                    hidden_profit.value = profit;
                    var hidden_margin = document.getElementById('hidden_margin['+platform+']');
                    hidden_margin.value = margin;
                },
                error: function(err) {
                    // alert('AJAX GET not working, ' + remote_url);
                    if (err.status == 200) {
                        // ParseResult(err);
                    }
                    // else { alert('Error:' + err.responseText + '  Status: ' + err.status); }
                }
            });
            return true;
        }";
        echo $js;
    }

}
