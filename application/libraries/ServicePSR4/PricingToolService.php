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

        if ($platform_objlist = $this->getDao('PlatformBizVar')->getPricingToolPlatformList($prod_sku, $platform_type)) {
            $platform_arr = $plat = [];
            foreach ($platform_objlist as $platform_obj) {
                $platform_arr[] = $platform_obj->getSellingPlatformId();
                $plat[$platform_obj->getSellingPlatformId()] = $platform_obj;
                unset($platform_obj);
            }

            $platform_list = "'" . implode("','", $platform_arr) . "'";
            if ($tmp_objlist = $this->getService('Price')->getPricingToolInfo($platform_list, $prod_sku)) {
                if ($platform_type == "WEBSITE") {
                    $scenario_arr = $this->getService('PricingToolWebsite')->getScenarioData();
                }

                $pdata = [];
                $objcount = 0;
                foreach ($tmp_objlist as $platform_id => $tmp_obj) {
                    $pdata[$platform_id]["pdata"] = $tmp_obj;
                    $platform_obj = $plat[$platform_id];
                    $pdata[$platform_id]["obj"] = $platform_obj;

                    $data["formtype"][$platform_id] = "update";
                    if ( !($price_obj = $this->getDao('Price')->get(["sku" => $prod_sku, "platform_id" => $platform_id])) ) {
                        $price_obj = $this->getDao('Price')->get();
                        $data["formtype"][$platform_id] = "add";
                    }
                    $data["price_list"][$platform_id] = $price_obj;
                    $_SESSION["price_obj_" . $platform_id] = serialize($price_obj);
                    $param = [];
                    # public param's array
                    $param['platform_id'] = $platform_id;
                    $param['country_id'] = $platform_obj->getPlatformCountryId();
                    $param['prod_sku'] = $prod_sku;
                    # Get private data by platform type
                    switch ($platform_type) {
                        case 'EBAY':
                            $ebay_data = $this->getService('PricingToolEbay')->getPrivateDataForEbay($param);

                            $data["sub_cat_margin"][$platform_id] = $tmp_obj["dst"]->getSubCatMargin();
                            $data["price_ext"][$platform_id] = $ebay_data["price_ext"];
                            $data["store_cat_list"][$platform_id] = $ebay_data["store_cat_list"];
                            $data["store_cat_2_list"][$platform_id] = $ebay_data["store_cat_2_list"];
                            break;

                        case 'WEBSITE':
                            $param['master_sku'] = $master_sku;
                            $param['scenario_arr'] = $scenario_arr;
                            $param['auto_price'] = $price_obj->getAutoPrice();
                            $param['scenarioid'] = $price_obj->getDeliveryScenarioid();
                            $param['is_advertised'] = $price_obj->getIsAdvertised();
                            $param['current_price'] = $tmp_obj["dst"]->getCurrentPlatformPrice();
                            $param['lang_id'] = $platform_obj->getLanguageId();
                            $param['prod_obj'] = $prod_obj;

                            $website_data = $this->getService('PricingToolWebsite')->getPrivateDataForWebsite($param);

                            $data['delivery_info'][$platform_id] = $website_data['delivery_info'];
                            $data["feed_include"][$platform_id] = $website_data['feed_include'];
                            $data["feed_exclude"][$platform_id] = $website_data['feed_exclude'];
                            $pdata[$platform_id]["competitor"] = $website_data['competitor'];
                             // $pdata[$platform_id]["adwords_obj"] = $website_data['adwords_obj'];
                            // $pdata[$platform_id]["gsc_comment"] = $website_data['gsc_comment'];
                            // $pdata[$platform_id]["enabled_pla_checkbox"] = $website_data['enabled_pla_checkbox'];
                            break;

                        default:
                            # code...
                            break;
                    }

                    unset($platform_obj);
                    $objcount++;
                }
                $data["objcount"] = $objcount;
                $data['pdata'] = $pdata;
            }
        }
        return $data;
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

    public function getDisplayQty($sku)
    {
        $vpo_where = ["p.sku" => $sku];
        $vpo_option = ["to_currency_id" => "GBP", "orderby" => "(prev_price > 0) DESC, (pbv.platform_currency_id = 'GBP') DESC, price DESC", "limit" => 1];
        if ($vpo_obj = $this->getDao('Product')->getProdOverviewWoShiptype($vpo_where, $vpo_option)) {
            $display_qty = $this->getService('DisplayQty')->calcDisplayQty($vpo_obj->getCatId(), $vpo_obj->getWebsiteQuantity(), $vpo_obj->getPrice());
            return $display_qty;
        }

        return false;
    }

    public function addProductNote($sku, $ty, $platform_id = "WEBGB", $note)
    {
        $note_obj = $this->getDao('ProductNote')->get();
        $note_obj->setSku($sku);
        $note_obj->setType($ty);
        $note_obj->setPlatformId($platform_id);
        $note_obj->setNote($note);

        if ($obj = $this->getDao('ProductNote')->insert($note_obj)) {
            return $obj;
        }
        return false;
    }

    public function updatePricingByPlatformSku($arr)
    {
        $wh = [];
        $wh['sku'] = $arr['sku'];
        $data = [
            'status'=>$arr['status'],
            'ext_mapping_code'=> $arr['emc'],
            'max_order_qty'=>$arr['moq']
        ];

        if (!empty($arr['platform_id'])) {
            $wh['platform_id'] = $arr['platform_id'];
            $this->getDao('Price')->qUpdate($wh, $data);
        } else if ($sp_objList = $this->getDao('SellingPlatform')->getList(['type'=>$arr['platform_type']], ['limit'=>-1])) {
            foreach ($sp_objList as $sp_obj) {
                $wh['platform_id'] = $sp_obj->getSellingPlatformId();
                $this->getDao('Price')->qUpdate($wh, $data);
            }
        }

        return true;
    }

    public function setAutoPricingForBulkSku($sku_list, $platform_type)
    {
        $msg = "";

        foreach ($sku_list as $sku) {
            if ($platform_list = $this->getDao('PlatformBizVar')->getPricingToolPlatformList($sku, $platform_type)) {
                foreach ($platform_list as $platform_obj) {
                    $platform_id = $platform_obj->getSellingPlatformId();
                    $json = $this->getService('Price')->getProfitMarginJson($platform_id, $sku, 0, -1);
                    $m = json_decode($json, TRUE);
                    $fail_reason = "";

                    if ($m["get_margin"] == 0) {
                        $fail_reason .= "Margin is 0%, ";
                    }

                    if ($platform_id == "TMNZ") {
                        $fail_reason .= "TMNZ to be omitted, SBF#3308";
                    }

                    if ($platform_id == "LAMY") {
                        $fail_reason .= "LAMY to be omitted, SBF#3308";
                    }

                    $price = $m["get_price"];

                    if ($fail_reason == "") {
                        $price_obj = $this->getDao('Price')->get(["sku" => $sku, "platform_id" => $platform_id]);
                        if (!$price_obj) {
                            $price_obj = $this->getDao('Price')->get();

                            $price_obj->setPlatformId($platform_id);
                            $price_obj->setSku($sku);
                            $price_obj->setListingStatus("L");
                            $price_obj->setPrice($price);
                            $price_obj->setAutoPrice("Y");
                            $price_obj->setFixedRrp("Y");
                            if (is_null($default_rrp_factor)) {
                                $default_rrp_factor = $this->getService('PricingTool')->getRrpFactorBySku($price_obj->getSku());
                            }
                            $price_obj->setRrpFactor($default_rrp_factor);

                            $this->getDao('Price')->insert($price_obj);
                        } else {
                            $price_obj->setListingStatus("L");
                            $price_obj->setAutoPrice("Y");
                            $price_obj->setPrice($price);
                            $this->getDao('Price')->update($price_obj);
                            $default_rrp_factor = $price_obj->getRrpFactor();
                        }

                        $prod_obj = $this->getDao('Product')->get(["sku" => $sku]);
                        $prod_obj->setWebsiteQuantity(20);
                        $prod_obj->setWebsiteStatus('I');
                        $this->getDao('Product')->update($prod_obj);
                    }

                    if ($fail_reason == "")
                        $msg .= "SUCCESS: Selling $sku @ {$m["get_price"]} on $platform_id (instock, website_qty={$prod_obj->getWebsiteQuantity()})<br>\r\n";
                    else
                        $msg .= "FAILED: $sku $platform_id, $fail_reason<br>\r\n";
                }
            }
        }

        return $msg;
    }
}
