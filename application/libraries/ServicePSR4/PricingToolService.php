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

                    if ( !($price_obj = $this->getDao('Price')->get(["sku" => $prod_sku, "platform_id" => $platform_id])) ) {
                        $price_obj = $this->getDao('Price')->get();
                    }
                    $data["price_list"][$platform_id] = $price_obj;

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
                            $data = array_merge($website_data['data'], $data);
                            $pdata = array_merge($website_data['pdata'], $pdata);
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
}
