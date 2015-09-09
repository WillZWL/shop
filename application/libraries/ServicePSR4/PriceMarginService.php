<?php
namespace ESG\Panther\Service;

use ESG\Panther\Dao\PriceMarginDao;
use ESG\Panther\Service\ClassFactoryService;
use ESG\Panther\Service\ProductService;
use ESG\Panther\Service\PlatformBizVarService;

class PriceMarginService extends BaseService
{
    private $classFactoryService;

    public function __construct()
    {
        parent::__construct();
        $this->setDao(new PriceMarginDao);
        $this->classFactoryService = new ClassFactoryService;
        $this->productService = new ProductService;
        $this->platformBizVarService = new PlatformBizVarService;
    }

    public function refresh_margin_for_top_deal()
    {
        if ($obj_list = $this->platformBizVarService->getSellingPlatformDao()->getList(["status" => 1])) {
            foreach ($obj_list as $obj) {
                $this->refreshMargin($obj->get_id());
            }
        }
    }

    public function refreshMargin($platform = 'WEBHK')
    {
        $prod_list = $this->productService->getListedProductList($platform, 'ProductCostDto');
        $this->updateMargin($prod_list, $platform);
    }

    public function updateMargin($prod_list, $platform = 'WEBHK')
    {
        if ($platform == "") {
            return FALSE;
        }

        $pr_svc = $this->classFactoryService->get_platform_price_service($platform);

        $sample_vo = $this->getDao()->get();
        foreach ($prod_list as $prod) {
            $margin_vo = clone $sample_vo;

            $prod->setPrice($pr_svc->getPrice($prod));
            $pr_svc->calcLogisticCost($prod);
            $pr_svc->calculateProfit($prod);
            set_value($margin_vo, $prod);
            $this->getDao()->replace($margin_vo);
        }

        unset($pr_svc);
        unset($p_svc);
    }

    public function refreshAllPlatformMargin($platform_where = [], $skulist = "")
    {
        $ret = [];
        $ret["status"] = FALSE;
        $platform_where["status"] = 1;

        if ($sp_list = $this->platformBizVarService->getSellingPlatformDao()->getList($platform_where)) {
            $updatelist = "";
            foreach ($sp_list as $key => $sellingplatform_obj) {

                set_time_limit(600);
                ini_set("memory_limit", "500M");

                $platform_id = $sellingplatform_obj->get_id();
                $updatelist .= $platform_id . ",\n";
                if ($skulist == "") {
                    echo "<br>Updating price_margin $platform_id,";
                    $this->refreshMargin($platform_id);
                } else {
                    $listedprod = $this->productService->getProductWPriceInfo($platform_id, $skulist, 'ProductCostDto');
                    if (count($listedprod) > 0) {
                        echo "<br>Updating price_margin $platform_id $skulist,";
                        $this->updateMargin($listedprod, $platform_id);
                    }
                }
            }

            $ts = date("Y-m-d H:i:s");
            // mail("itsupport@eservicesgroup.net", "VB price_margin platforms update", "price_margin refreshed for following platforms @ GMT+0 $ts: \n$updatelist");
            $ret["status"] = TRUE;
            $ret["updatelist"] = $updatelist;
            return $ret;
        } else {
            $ret["error_message"] = __LINE__ . "price_margin_service. Unable to retrieve sellling platform list. DB error "
                . $platformBizVarService->getSellingPlatformDao()->db->_error_message();
        }

        return $ret;
    }

    public function refresh_margin2($platform = 'WEBHK')
    {
        $prod_list = $this->productService->getListedProductList($platform, 'ProductCostDto');
        $this->updateMargin2($prod_list, $platform);
    }

    public function updateMargin2($prod_list, $platform = 'WEBHK')
    {
        if ($platform == "") {
            $platform = "WEBHK";
        }

        $pf_var = $this->platformBizVarService->get_platform_biz_var($platform);

        $shiptype = 1;

        if ($pf_var) {
            $shiptype = $pf_var->get_default_shiptype();
        }


        $price_srv = $this->classFactoryService->get_price_service($platform);
        $sample_vo = $this->getDao()->get();

        foreach ($prod_list as $prod) {
            $margin_vo = clone $sample_vo;
            $prod->set_shiptype($shiptype);
            $prod->set_price($price_srv->get_price($prod->get_sku()));

            $price_srv->calc_profit($prod);
            set_value($margin_vo, $prod);

            $this->getDao()->replace($margin_vo);
            if ($prod->get_sku() == '10051-NA') {
                var_dump($this->getDao()->db->last_query());
                var_dump($prod);
                exit;
            }

        }
    }

    public function refresh_margin_amazon($platform = 'AMUS')
    {
        $prod_list = $this->productService->getListedProductList($platform, 'ProductCostDto');
        $this->_update_margin_amazon($prod_list, $platform);
    }

    public function _update_margin_amazon($prod_list, $platform = 'AMUS')
    {
        if ($platform == "") {
            return FALSE;
        }

        $pr_svc = $this->classFactoryService->get_platform_price_service($platform);

        $sample_vo = $this->getDao()->get();
        foreach ($prod_list as $prod) {
            $p_srv = $pr_svc->get_price_service_from_dto($prod);
            $p_srv->set_platform_id($prod->get_platform_id());
            $p_srv->set_platform_curr_id($prod->get_platform_currency_id());

            // get fulfillment centre id for amazon
            $price_ext_obj = $pr_svc->get_price_ext_dao()->get(["sku" => $prod->get_sku(), "platform_id" => $prod->get_platform_id()]);
            if (!$price_ext_obj || !$fc_id = $price_ext_obj->get_fulfillment_centre_id()) {
                $fc_id = "DEFAULT";
            }
            $p_srv->set_fulfillment_centre_id($fc_id);

            $margin_vo = clone $sample_vo;

            $prod->set_price($pr_svc->get_price($prod));
            $pr_svc->calc_freight_cost($prod, $p_srv, $prod->get_platform_currency_id());
            $pr_svc->calculate_profit($prod);
            set_value($margin_vo, $prod);

            $this->getDao()->replace($margin_vo);
        }
        unset($pr_svc);
        unset($p_svc);
    }

    public function refresh_latest_margin($where = [])
    {
        $prod_list = $this->productService->getProductWMarginReqUpdate($where, 'ProductCostDto');
        $this->updateMargin($prod_list, $where["v_prod_overview_w_update_time.platform_id"]);
    }

    public function get_price_service()
    {
        return $this->price_service;
    }

    public function set_price_service(Base_service $svc)
    {
        $this->price_service = $svc;
        return $this;
    }

    public function insert_or_update_margin($sku, $platform_id, $price = null, $profit, $margin)
    {
        if ($price_margin_obj = $this->getDao()->get(['sku' => $sku, 'platform_id' => $platform_id])) {
            if (!$temp_price_margin_obj = $this->getDao()->get(['sku' => $sku, 'platform_id' => $platform_id, 'profit' => $profit, 'margin' => $margin])) {
                $price_margin_obj->set_profit($profit);
                $price_margin_obj->set_selling_price($price);
                $price_margin_obj->set_margin($margin);
                $this->getDao()->update($price_margin_obj);
            }
        } else {
            $price_margin_obj = $this->getDao()->get();
            $price_margin_obj->set_sku($sku);
            $price_margin_obj->set_platform_id($platform_id);
            $price_margin_obj->set_selling_price($price);
            $price_margin_obj->set_profit($profit);
            $price_margin_obj->set_margin($margin);
            $this->getDao()->insert($price_margin_obj);
        }
    }

    public function get_cross_sell_product($prod_info, $platform_id, $language_id, $price, $price_adjustment)
    {
        return $this->getDao()->get_cross_sell_product($prod_info, $platform_id, $language_id, $price, $price_adjustment);
    }
}


