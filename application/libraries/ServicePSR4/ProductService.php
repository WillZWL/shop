<?php
namespace ESG\Panther\Service;

use ESG\Panther\Service\BrandService;
use ESG\Panther\Dao\ProductDao;

class ProductService extends BaseProductService
{
    private $factory;
    private $data;
    private $vos;

    public function __construct(ProductDataFactory $factory = null)
    {
        $this->setDao(new ProductDao);
        $this->factory = $factory;
    }

    public function addProductData(ProductData $obj)
    {
        $obj->parseData($data);
        $this->vos = $obj->getData();
    }

    public function createProductProcess(ProductCreationInterface $obj = null)
    {
        $data = $this->vos;
        $this->vos = '';

        foreach ($data as $key) {
            $sku = $this->createSkuMapping($data[$key]['skuMappingVo']);
            $sku = $this->createProductContent($data[$key]['productContentVo']);
            $sku = $this->createProduct($data[$key]['productVo']);
        }
    }

    public function createSkuMapping(BaseVo $obj)
    {
        $this->container['skuMppingDao']->insert($obj);
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
