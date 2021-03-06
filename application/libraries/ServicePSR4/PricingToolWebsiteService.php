<?php
namespace ESG\Panther\Service;

class PricingToolWebsiteService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
    }

    public function updatePricingForWebsite($vars)
    {
        $requireSendGoogleApi = false;
    	$arr = [];
        $action = "update";
    	$action = $vars['formtype'] == "update" ? "update" : "insert";
        $price_obj = unserialize($_SESSION["price_obj_" . $vars['platform']]);
        if (($price_obj->getPrice() * 1 != $vars['sp'] * 1)
            || ($price_obj->getListingStatus() != $vars['cur_listing_status'])
            || ($price_obj->getIsAdvertised() != $vars['is_advertised'])) {
            $requireSendGoogleApi = true;
        }
		if ($price_obj->getPrice() * 1 != $vars['sp'] * 1 ||
            $price_obj->getListingStatus() != $vars['cur_listing_status'] ||
            // $price_obj->getAllowExpress() != $vars['ae'] ||
            $price_obj->getIsAdvertised() != $vars['ia'] ||
            $price_obj->getAutoPrice() != $vars['ap'] ||
            $price_obj->getFixedRrp() != $vars['frrp'] ||
            (($vars['frrp'] == 'N') && ($vars['rrp_factor'] != '') && ($price_obj->getRrpFactor() != $vars['rrp_factor']))
        ) {
            $price_obj->setPlatformId($vars['platform']);
            $price_obj->setSku($vars['sku']);
            $price_obj->setListingStatus($vars['cur_listing_status']);
            $price_obj->setPrice($vars['sp']);
            // $price_obj->setAllowExpress($vars['ae']);
            $price_obj->setIsAdvertised($vars['ia']);
            $price_obj->setAutoPrice($vars['ap']);
            $price_obj->setFixedRrp($vars['frrp']);

            if ($vars['special_update']) {
                $price_obj->setExtMappingCode($vars['ext_mapping_code']);
                $price_obj->setMaxOrderQty($vars['max_order_qty']);
            }

            if (($vars['frrp'] == 'N') && ($vars['rrp_factor'] != '')) {
                $price_obj->setRrpFactor($vars['rrp_factor']);
            }

            if (is_null($price_obj->getRrpFactor())) {
                $price_obj->setRrpFactor($this->getService('PricingTool')->getRrpFactorBySku($vars['sku']));
            }

            $ret =  $this->getDao('Price')->$action($price_obj);

            if ($ret === FALSE) {
                $arr['fail'] = true;
                $arr['fail'] = "update_failed ". $this->db->display_error();
            } else {
                $arr['success'] = true;
                $arr['price'] = $vars['sp'];
                $arr['listing_status'] = $vars['cur_listing_status'];
                $arr['margin'] = $vars['margin'];
                unset($_SESSION["price_obj_" . $vars['platform']]);
                $_SESSION["price_obj_" . $vars['platform']] = serialize($price_obj);
            }
        } else {
            $arr['no_update'] = true;
        }

        if ($requireSendGoogleApi) {
            $this->getService("PriceUpdateTrigger")->triggerGoogleApi($vars['sku'], $vars['platform']);
        }

        return $arr;
    }

    public function getPrivateDataForWebsite($param)
    {
        $platform_id = $param['platform_id'];
        $data = $pdata = [];
        $data['delivery_info'] = $this->getDeliveryInfo($param);
        $data["feed_include"] = $this->getDao('AffiliateSkuPlatform')->getFeedListBySku($param['prod_sku'], $platform_id, 2);
        $data["feed_exclude"] = $this->getDao('AffiliateSkuPlatform')->getFeedListBySku($param['prod_sku'], $platform_id, 1);
        $data["competitor"] = $this->getCompetitor($param);
        // $data["adwords_obj"] = $this->getAdwordsData($param);

        return $data;
    }

    public function getAdwordsData($param)
    {
        if ($obj = $this->getDao('AdwordsData')->get(["sku" => $param['prod_sku'], "platform_id" => $param['platform_id']])) {
            return $obj;
        }

        return false;
    }

    public function getGoogleGscComment(\ProductGoogleGscCommentDto $dto)
    {
        $internal_gsc_comment = "";
        if (!$prod_identifer_obj = $this->getDao('ProductIdentifier')->get(["prod_grp_cd" => $dto->getProdGrpCd(), "colour_id" => $dto->getColourId(), "country_id" => $dto->getCountryId()])) {
            $internal_gsc_comment = "No mpn value. ";
        } else if (!$prod_identifer_obj->getMpn()) {
            $internal_gsc_comment = "No mpn value. ";
        }

        if ($dto->getProdStatus()) {
            if ($dto->getProdStatus() != 2) {
                $internal_gsc_comment .= "/Product is not listed in product Mgmt. ";
            }
        }

        if ($product_content_obj = $this->getDao('ProductContent')->get(["prod_sku" => $dto->getSku(), "lang_id" => $dto->getLangId()])) {
            if (!$product_content_obj->getDetailDesc()) {
                $internal_gsc_comment .= "/No detail desc. ";
            }
        }

        $gsc_where = $gsc_option = [];
        $gsc_where['cm.category_mapping_id'] = $dto->getSku();
        $gsc_where['cm.ext_party'] = "GOOGLEBASE";
        $gsc_where['cm.country_id'] = $dto->getCountryId();
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

        $google_arr["gsc_comment"] = $internal_gsc_comment;
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

}