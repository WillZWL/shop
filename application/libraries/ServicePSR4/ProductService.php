<?php
namespace AtomV2\Service;

use AtomV2\Service\BrandService;
use AtomV2\Dao\ProductDao;



class ProductService extends BaseProductService
{

    public function __construct()
    {
        $this->setDao(new ProductDao);
    }

    public function getHomeProduct($where, $option)
    {
        return $this->getDao()->getHomeProduct($where, $option);
    }

    public function getListedProductList($platform_id = 'WSGB', $classname = 'WebsiteProdInfoDto')
    {
        return $this->getDao()->getListedProductList($platform_id, $classname);
    }

    public function getProductWPriceInfo($platform_id = 'WEBGB', $sku = "", $classname = 'WebsiteProdInfoDto')
    {
        return $this->getDao()->getProductWPriceInfo($platform_id, $sku, $classname);
    }

    public function getProductWMarginReqUpdate($where = [], $classname = 'WebsiteProdInfoDto')
    {
        return $this->getDao()->getProductWMarginReqUpdate($where, $classname);
    }

    public function getCreateProductOptions()
    {
        $data['brandList'] = $this->brandService->getList([], ['orderby' => 'brand_name ASC', 'limit' => -1]);
        $data['colourList'] = $this->colourService->getList(['status' => 1], ['orderby' => 'colour_id ASC', 'limit' => -1]);
        $data['versionList'] = $this->versionService->getList(['status' => A], ['orderby' => 'colour_id ASC', 'limit' => -1]);

        $data["version_list"] = $this->product_model->get_list("version", array("status" => 'A'));
        $data["type_list"] = $this->subject_domain_service->get_subj_list_w_subj_lang("MKT.PROD_TYPE.PROD_TYPE_ID", "en");
    }
}
