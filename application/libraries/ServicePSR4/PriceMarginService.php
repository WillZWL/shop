<?php
namespace ESG\Panther\Service;

use ESG\Panther\Dao\PriceMarginDao;
use ESG\Panther\Service\ClassFactoryService;
use ESG\Panther\Service\ProductService;
use ESG\Panther\Service\PlatformBizVarService;
//use ESG\Panther\Service\PriceService;

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
        //$this->priceService = new PriceService;
    }

    public function refreshMarginForTopDeal()
    {
        if ($obj_list = $this->platformBizVarService->getDao('SellingPlatform')->getList(["status" => 1])) {
            foreach ($obj_list as $obj) {
                $this->refreshMargin($obj->getSellingPlatformId());
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

        $pr_svc = $this->classFactoryService->getPlatformPriceService($platform);

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

        if ($sp_list = $this->platformBizVarService->getDao('SellingPlatform')->getList($platform_where)) {
            $updatelist = "";
            foreach ($sp_list as $key => $sellingplatform_obj) {

                set_time_limit(600);
                ini_set("memory_limit", "500M");

                $platform_id = $sellingplatform_obj->getSellingPlatformId();
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
            $ret["status"] = TRUE;
            $ret["updatelist"] = $updatelist;
            return $ret;
        } else {
            $ret["error_message"] = __LINE__ . "price_margin_service. Unable to retrieve sellling platform list. DB error ";
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

        $pf_var = $this->platformBizVarService->getPlatformBizVar($platform);

        $shiptype = 1;

        if ($pf_var) {
            $shiptype = $pf_var->getDefaultShiptype();
        }


        $price_srv = $this->classFactoryService->getPriceService($platform);
        $sample_vo = $this->getDao()->get();

        foreach ($prod_list as $prod) {
            $margin_vo = clone $sample_vo;
            $prod->setShiptype($shiptype);
            $prod->setPrice($price_srv->getPrice($prod->getSku()));

            $price_srv->calcProfit($prod);
            set_value($margin_vo, $prod);

            $this->getDao()->replace($margin_vo);
            if ($prod->getSku() == '10051-NA') {
                var_dump($this->getDao()->db->last_query());
                var_dump($prod);
                exit;
            }

        }
    }

    public function refreshMarginAmazon($platform = 'AMUS')
    {
        $prod_list = $this->productService->getListedProductList($platform, 'ProductCostDto');
        $this->_updateMarginAmazon($prod_list, $platform);
    }

    public function _updateMarginAmazon($prod_list, $platform = 'AMUS')
    {
        if ($platform == "") {
            return FALSE;
        }

        $pr_svc = $this->classFactoryService->getPlatformPriceService($platform);

        $sample_vo = $this->getDao()->get();
        foreach ($prod_list as $prod) {
            $p_srv = $pr_svc->getPriceServiceFromDto($prod);
            $p_srv->setPlatformId($prod->getPlatformId());
            $p_srv->setPlatformCurrId($prod->getPlatformCurrencyId());

            // get fulfillment centre id for amazon
            $price_ext_obj = $pr_svc->get_Price_ext_dao()->get(["sku" => $prod->getSku(), "platform_id" => $prod->getPlatformId()]);
            if (!$price_ext_obj || !$fc_id = $price_ext_obj->getFulfillmentCentreId()) {
                $fc_id = "DEFAULT";
            }
            $p_srv->setFulfillmentCentreId($fc_id);

            $margin_vo = clone $sample_vo;

            $prod->setPrice($pr_svc->getPrice($prod));
            $pr_svc->calcFreightCost($prod, $p_srv, $prod->getPlatformCurrencyId());
            $pr_svc->calculateProfit($prod);
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

    /*public function getPriceService()
    {
        return $this->price_service;
    }

    public function setPriceService($svc)
    {
        $this->price_service = $svc;
        return $this;
    }*/

    public function insertOrUpdateMargin($sku, $platform_id, $price = null, $profit, $margin)
    {
        if ($price_margin_obj = $this->getDao()->get(['sku' => $sku, 'platform_id' => $platform_id])) {
            if (!$temp_price_margin_obj = $this->getDao()->get(['sku' => $sku, 'platform_id' => $platform_id, 'profit' => $profit, 'margin' => $margin])) {
                $price_margin_obj->setProfit($profit);
                $price_margin_obj->setSellingPrice($price);
                $price_margin_obj->setMargin($margin);
                $this->getDao()->update($price_margin_obj);
            }
        } else {
            $price_margin_obj = $this->getDao()->get();
            $price_margin_obj->setSku($sku);
            $price_margin_obj->setPlatformId($platform_id);
            $price_margin_obj->setSellingPrice($price);
            $price_margin_obj->setProfit($profit);
            $price_margin_obj->setMargin($margin);
            $this->getDao()->insert($price_margin_obj);
        }
    }

    public function getCrossSellProduct($prod_info, $platform_id, $language_id, $price, $price_adjustment)
    {
        return $this->getDao()->getCrossSellProduct($prod_info, $platform_id, $language_id, $price, $price_adjustment);
    }
}


