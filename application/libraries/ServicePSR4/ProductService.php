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

    public function createNewProduct($oldObj)
    {
        $sku = $this->getDao('Product')->getNewSku();
        $newObj = new \ProductVo();
        $newObj->setSku($sku);
        $this->updateProduct($newObj, $oldObj);

        return $newObj;
    }

    public function updateProduct($newObj, $oldObj)
    {
        $newObj->setProdGrpCd((string)$oldObj->prod_grp_cd);
        $newObj->setColourId((string)$oldObj->colour_id);
        $newObj->setVersionId((string)$oldObj->version_id);
        $newObj->setName((string)$oldObj->name);
        $newObj->setFreightCatId((string)$oldObj->freight_cat_id);
        $newObj->setCatId((string)$oldObj->cat_id);
        $newObj->setSubCatId((string)$oldObj->sub_cat_id);
        $newObj->setSubSubCatId((string)$oldObj->sub_sub_cat_id);
        $newObj->setBrandId((string)$oldObj->brand_id);
        $newObj->setClearance((string)$oldObj->clearance);
        $newObj->setSurplusQuantity((string)$oldObj->surplus_quantity);
        $newObj->setQuantity((string)$oldObj->quantity);
        $newObj->setDisplayQuantity((string)$oldObj->display_quantity);
        $newObj->setWebsiteQuantity((string)$oldObj->website_quantity);
        $newObj->setChinaOem((string)$oldObj->china_oem);
        $newObj->setRrp((string)$oldObj->rrp);
        $newObj->setImage((string)$oldObj->image);
        $newObj->setFlash((string)$oldObj->flash);
        $newObj->setYoutubeId((string)$oldObj->youtube_id);
        $newObj->setEan((string)$oldObj->ean);
        $newObj->setMpn((string)$oldObj->mpn);
        $newObj->setUpc((string)$oldObj->upc);
        $newObj->setDiscount((string)$oldObj->discount);
        $newObj->setProcStatus((string)$oldObj->proc_status);
        $newObj->setWebsiteStatus((string)$oldObj->website_status);
        $newObj->setSourcingStatus((string)$oldObj->sourcing_status);
        $newObj->setExpectedDeliveryDate((string)$oldObj->expected_delivery_date);
        $newObj->setWarrantyInMonth((string)$oldObj->warranty_in_month);
        $newObj->setCatUpselling((string)$oldObj->cat_upselling);
        $newObj->setLangRestricted((string)$oldObj->lang_restricted);
        $newObj->setShipmentRestrictedType((string)$oldObj->shipment_restricted_type);
        $newObj->setStatus((string)$oldObj->status);
    }

    public function createNewProductContent($sku, $oldObj)
    {
        if ( ! $this->getDao('Product')->get(['sku' => $sku])) {
            return false;
        }

        $newObj = new \ProductContentVo();
        $newObj->setProdSku($sku);
        $newObj->setLangId($oldObj->lang_id);
        $this->updateProductContent($newObj, $oldObj);

        return $newObj;
    }

    public function updateProductContent($newObj, $oldObj)
    {
        $newObj->setProdName(replace_special_chars($oldObj->prod_name));
        $newObj->setProdNameOriginal(replace_special_chars($oldObj->prod_name_original));
        $newObj->setShortDesc(replace_special_chars($oldObj->short_desc));
        $newObj->setContents(replace_special_chars($oldObj->contents));
        $newObj->setContentsOriginal(replace_special_chars($oldObj->contents_original));
        $newObj->setSeries(replace_special_chars($oldObj->series));
        $newObj->setKeywords(replace_special_chars($oldObj->keywords));
        $newObj->setKeywordsOriginal(replace_special_chars($oldObj->keywords_original));
        $newObj->setModel1(replace_special_chars($oldObj->model_1));
        $newObj->setModel2(replace_special_chars($oldObj->model_2));
        $newObj->setModel3(replace_special_chars($oldObj->model_3));
        $newObj->setModel4(replace_special_chars($oldObj->model_4));
        $newObj->setModel5(replace_special_chars($oldObj->model_5));
        $newObj->setDetailDesc(replace_special_chars($oldObj->detail_desc));
        $newObj->setDetailDescOriginal(replace_special_chars($oldObj->detail_desc_original));
        $newObj->setExtraInfo(replace_special_chars($oldObj->extra_info));
        $newObj->setWebsiteStatusLongText(replace_special_chars($oldObj->website_status_long_text));
        $newObj->setWebsiteStatusShortText(replace_special_chars($oldObj->website_status_short_text));
        $newObj->setYoutubeId1(replace_special_chars($oldObj->youtube_id_1));
        $newObj->setYoutubeId2(replace_special_chars($oldObj->youtube_id_2));
        $newObj->setYoutubeCaption1(replace_special_chars($oldObj->youtube_caption_1));
        $newObj->setYoutubeCaption2(replace_special_chars($oldObj->youtube_caption_2));
    }

    public function createNewProductContentExtend($sku, $oldObj)
    {
        if ( ! $this->getDao('Product')->get(['sku' => $sku])) {
            return false;
        }

        $newObj = new \ProductContentExtendVo();
        $newObj->setProdSku($sku);
        $newObj->setLangId($oldObj->lang_id);
        $this->updateProductContentExtend($newObj, $oldObj);

        return $newObj;
    }

    public function updateProductContentExtend($newObj, $oldObj)
    {
        $newObj->setFeature($oldObj->feature);
        $newObj->setFeatureOriginal($oldObj->feature_original);
        $newObj->setSpecification($oldObj->specification);
        $newObj->setSpecOriginal($oldObj->spec_original);
        $newObj->setRequirement($oldObj->requirement);
        $newObj->setInstruction($oldObj->instruction);
        $newObj->setApplyEnhancedListing($oldObj->apply_enhanced_listing);
        $newObj->setEnhancedListing($oldObj->enhanced_listing);
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
        $this->sc['skuMppingDao']->insert($obj);
    }

    public function getHomeProduct($where, $option)
    {
        return $this->getDao('Product')->getHomeProduct($where, $option);
    }

    public function getListedProductList($platform_id = 'WSGB', $classname = 'WebsiteProdInfoDto')
    {
        return $this->getDao('Product')->getListedProductList($platform_id, $classname);
    }

    public function getProductWPriceInfo($platform_id = 'WEBGB', $sku = "", $classname = 'WebsiteProdInfoDto')
    {
        return $this->getDao('Product')->getProductWPriceInfo($platform_id, $sku, $classname);
    }

    public function getProductWMarginReqUpdate($where = [], $classname = 'WebsiteProdInfoDto')
    {
        return $this->getDao('Product')->getProductWMarginReqUpdate($where, $classname);
    }

    public function getWebsiteCatPageProductList($where = array(), $option = array())
    {
        return $this->getDao('Product')->getWebsiteCatPageProductList($where, $option);
    }

    public function getCreateProductOptions()
    {
        $data['brandList'] = $this->brandService->getList([], ['orderby' => 'brand_name ASC', 'limit' => -1]);
        $data['colourList'] = $this->colourService->getList(['status' => 1], ['orderby' => 'colour_id ASC', 'limit' => -1]);
        $data['versionList'] = $this->versionService->getList(['status' => A], ['orderby' => 'colour_id ASC', 'limit' => -1]);

        $data["version_list"] = $this->product_model->get_list("version", array("status" => 'A'));
        $data["type_list"] = $this->subject_domain_service->get_subj_list_w_subj_lang("MKT.PROD_TYPE.PROD_TYPE_ID", "en");
    }

    public function isClearance($sku)
    {
        return $this->getDao('Product')->isClearance($sku);
    }
}
