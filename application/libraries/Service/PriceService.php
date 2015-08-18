<?php
namespace AtomV2\Service;

use AtomV2\Dao\ProductDao;

class PriceService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
        $this->setDao(new ProductDao);
    }

    public function getListingInfoList($sku_arr = [], $platform_id = "", $lang_id = 'en', $option = [])
    {

        if (empty($sku_arr)) {
            return false;
        } else {
            foreach ($sku_arr as $obj) {
                $sku_list[$obj->getSku()] = '';
            }
        }

        if ($result = $this->getDao()->getListingInfo($sku_list, $platform_id, $lang_id, $option)) {
            if (is_array($result)) {
                foreach ($result as $obj) {
                    //$obj->set_rrp_price($this->calc_website_product_rrp($obj->get_price()));
                    // $obj->setRrpPrice(random_markup($this->calcWebsiteProductRrp($obj->getPrice(), $obj->getFixedRrp(), $obj->getRrpFactor())));
                    $obj->setPrice(random_markup($obj->getPrice()));
                }
                $rs = $result;

                foreach ($rs as $rs_obj) {
                    $sku_list[$rs_obj->getSku()] = $rs_obj;
                }
            } else {
                //$result->set_rrp_price($this->calc_website_product_rrp($result->get_price()));
                // $result->setRrpPrice(random_markup($this->calcWebsiteProductRrp($result->getPrice(), $result->getFixedRrp(), $result->getRrpFactor())));
                $result->setPrice(random_markup($result->getPrice()));
                $sku_list[$result->getSku()] = $result;
            }
        }

        return $sku_list;
    }
}
