<?php
namespace ESG\Panther\Service;

class PricingToolEbayService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
    }

    public function updatePricingForEbay($vars)
    {
        $arr = [];
        $action = "update";
        if (!$price_obj = $this->getDao('Price')->get(["platform_id" => $vars['platform'], "sku" => $vars['sku']])) {
            $action = "insert";
            $price_obj = $this->getDao('Price')->get();
        }

        $price_ext_need_update = 0;
        if (!$price_ext_obj = $this->getDao('PriceExtend')->get(["platform_id" => $vars['platform'], "sku" => $vars['sku']])) {
            $price_ext_obj = $this->getDao('PriceExtend')->get();
        }
        $vars['ext_ref_1'];
                $vars['ext_ref_2'];
                $vars['ext_ref_3'];
                $vars['ext_ref_4'];
                $vars['ext_qty'];
                $vars['title'];
                $vars['handling_time'];
                $vars['action'];
        if (call_user_func(array($price_ext_obj, "getPlatformId"))) {
            $priceExtAction = "update";
            if ($price_ext_obj->getExtRef1() != $vars['ext_ref_1'] ||
                $price_ext_obj->getExtRef2() != $vars['ext_ref_2'] ||
                $price_ext_obj->getExtRef3() != $vars['ext_ref_3'] ||
                $price_ext_obj->getExtRef4() != $vars['ext_ref_4'] ||
                $price_ext_obj->getExtQty() != $vars['ext_qty'] ||
                $price_ext_obj->getTitle() != $vars['title'] ||
                $price_ext_obj->getHandlingTime() != $vars['handling_time'] ||
                $vars['action']
            ) {
                $price_ext_need_update = 1;
            }
        } else {
            $price_ext_obj->setSku($vars['sku'])->setPlatformId($vars['platform']);
            $priceExtAction = "insert";
            if ($vars['ext_qty']) {
                $price_ext_need_update = 1;
            }
        }

        if ($price_obj->getPrice() * 1 != $vars['sp'] * 1 ||
            $price_obj->getListingStatus() != $vars['cur_listing_status'] ||
            // $price_obj->getAllowExpress() != $vars['ae'] ||
            $price_obj->getIsAdvertised() != $vars['ia'] ||
            $price_ext_need_update
        ) {
            $price_obj->setPlatformId($vars['platform']);
            $price_obj->setSku($vars['sku']);
            $price_obj->setListingStatus($vars['cur_listing_status']);
            $price_obj->setPrice($vars['sp']);
            // $price_obj->setAllowExpress($vars['ae']);
            $price_obj->setIsAdvertised($vars['ia']);
            $price_obj->setAutoPrice('N');

            $ret = $this->getDao('Price')->$action($price_obj);

            if ($ret === FALSE) {
                $arr['fail'] = true;
                $arr['fail'] = "update_failed ". $this->db->display_error();

                return $arr;
            } else {
                $this->getService('PriceMargin')->insertOrUpdateMargin($vars['sku'], $vars['platform'], $vars['sp'], $vars['profit'], $vars['margin']);

                if ($price_ext_need_update) {
                    set_value($price_ext_obj, $vars);
                    if ($vars['action'] == "R") {
                        $price_ext_obj->setAction(NULL);
                        $price_ext_obj->setRemark(NULL);
                        $price_ext_obj->setExtItemId(NULL);
                        $price_ext_obj->setExtStatus(NULL);
                    } elseif ($vars['action'] == "E") {
                        $price_ext_obj->setAction("E");
                        $price_ext_obj->setRemark($vars['reason']);
                    }

                    if ($this->getDao('PriceExtend')->$priceExtAction($price_ext_obj) === FALSE) {
                        $arr['fail'] = true;
                        $arr['fail'] = "update_failed ". $this->db->display_error();

                        return $arr;
                    } else {
                        if ($vars['action'] == "E") {
                            $res = $this->getService('Ebay')->endItem($vars['platform'], $vars['sku']);
                            if ($res["response"]) {
                                $price_obj->setListingStatus("N");
                                if ($this->getDao('Price')->update($price_obj) === FALSE) {
                                    $arr['fail'] = true;
                                    $arr['fail'] = "update_failed ". $this->db->display_error();

                                    return $arr;
                                }

                                $price_ext_obj->setAction(null);
                                $price_ext_obj->setRemark(null);
                                $price_ext_obj->setExtItemId(NULL);
                                $price_ext_obj->setExtStatus("E");
                                if ($this->getDao('PriceExtend')->update($price_ext_obj) === FALSE) {
                                    $arr['fail'] = true;
                                    $arr['fail'] = "update_failed ". $this->db->display_error();

                                    return $arr;
                                }
                            }
                            $_SESSION["NOTICE"] .= $res["message"];
                        } elseif ($vars['action'] == "RE") {
                            $res = $this->getService('Ebay')->reviseItem($vars['platform'], $vars['sku']);
                            $_SESSION["NOTICE"] .= $res["message"];
                        }
                    }

                    echo $this->getDao('PriceExtend')->db->last_query();
                }

                $arr['success'] = true;
                $arr['price'] = $vars['sp'];
                $arr['listing_status'] = $vars['cur_listing_status'];
                $arr['margin'] = $vars['margin'];
            }
        } else {
            $arr['no_update'] = true;
        }

        return $arr;
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
}