<?php
namespace ESG\Panther\Service;

class ProductService extends BaseProductService
{
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
        //$newObj->setSurplusQuantity((string)$oldObj->surplus_quantity);
        //$newObj->setQuantity((string)$oldObj->quantity);
        //$newObj->setDisplayQuantity((string)$oldObj->display_quantity);
        //$newObj->setWebsiteQuantity((string)$oldObj->website_quantity);
        $newObj->setChinaOem((string)$oldObj->china_oem);
        //$newObj->setRrp((string)$oldObj->rrp);
        $newObj->setImage((string)$oldObj->image);
        //$newObj->setFlash((string)$oldObj->flash);
        //$newObj->setYoutubeId((string)$oldObj->youtube_id);
        $newObj->setEan((string)$oldObj->ean);
        $newObj->setMpn((string)$oldObj->mpn);
        $newObj->setUpc((string)$oldObj->upc);
        //$newObj->setDiscount((string)$oldObj->discount);
        $newObj->setProcStatus((string)$oldObj->proc_status);
        $newObj->setWebsiteStatus((string)$oldObj->website_status);
        //$newObj->setSourcingStatus((string)$oldObj->sourcing_status);
        $newObj->setExpectedDeliveryDate((string)$oldObj->expected_delivery_date);
        //$newObj->setWarrantyInMonth((string)$oldObj->warranty_in_month);
        //$newObj->setCatUpselling((string)$oldObj->cat_upselling);
        $newObj->setLangRestricted((string)$oldObj->lang_restricted);
        $newObj->setShipmentRestrictedType((string)$oldObj->shipment_restricted_type);
        $newObj->setProductWarrantyType((string)$oldObj->product_warranty_type);
        $newObj->setAccelerator((string)$oldObj->accelerator);
        $newObj->setStatus((string)$oldObj->status);
    }

    public function createNewProductContent($sku, $oldObj)
    {
        if ( ! $prod_obj = $this->getDao('Product')->get(['sku' => $sku])) {
            return false;
        }


        $newObj = new \ProductContentVo();
        $newObj->setProdSku($sku);
        $newObj->setLangId((string)$oldObj->lang_id);
        $this->updateProductContent($newObj, $oldObj);

        return $newObj;
    }

    public function updateProductContent($newObj, $oldObj)
    {
        $sku = $newObj->getProdSku();
        if ( ! $prod_obj = $this->getDao('Product')->get(['sku' => $sku])) {
            return false;
        }

        $category_table = $this->getService('Category')->getCategoryName((string)$oldObj->lang_id);
        $prod_url = '/'. $category_table[$prod_obj->getCatId()].'/'.$category_table[$prod_obj->getSubCatId()].'/'.str_replace(' ', '-', parse_url_char((string)$oldObj->prod_name)).'/product/'.$prod_obj->getSku();
        $newObj->setProductUrl($prod_url);

        $newObj->setProdName(replace_special_chars((string)$oldObj->prod_name));
        $newObj->setProdNameOriginal(replace_special_chars((string)$oldObj->prod_name_original));
        $newObj->setShortDesc(replace_special_chars((string)$oldObj->short_desc));
        $newObj->setContents(replace_special_chars((string)$oldObj->contents));
        $newObj->setContentsOriginal(replace_special_chars((string)$oldObj->contents_original));
        //$newObj->setSeries(replace_special_chars((string)$oldObj->series));
        $newObj->setKeywords(replace_special_chars((string)$oldObj->keywords));
        $newObj->setKeywordsOriginal(replace_special_chars((string)$oldObj->keywords_original));
        //$newObj->setModel1(replace_special_chars((string)$oldObj->model_1));
        //$newObj->setModel2(replace_special_chars((string)$oldObj->model_2));
        //$newObj->setModel3(replace_special_chars((string)$oldObj->model_3));
        //$newObj->setModel4(replace_special_chars((string)$oldObj->model_4));
        //$newObj->setModel5(replace_special_chars((string)$oldObj->model_5));
        $newObj->setDetailDesc(replace_special_chars((string)$oldObj->detail_desc));
        $newObj->setDetailDescOriginal(replace_special_chars((string)$oldObj->detail_desc_original));
        $newObj->setExtraInfo(replace_special_chars((string)$oldObj->extra_info));
        $newObj->setWebsiteStatusLongText(replace_special_chars((string)$oldObj->website_status_long_text));
        $newObj->setWebsiteStatusShortText(replace_special_chars((string)$oldObj->website_status_short_text));
        //$newObj->setYoutubeId1(replace_special_chars((string)$oldObj->youtube_id_1));
        //$newObj->setYoutubeId2(replace_special_chars((string)$oldObj->youtube_id_2));
        //$newObj->setYoutubeCaption1(replace_special_chars((string)$oldObj->youtube_caption_1));
        //$newObj->setYoutubeCaption2(replace_special_chars((string)$oldObj->youtube_caption_2));
        $newObj->setStopSync((string)$oldObj->stop_sync);
    }

    public function updateProductUrl($sku)
    {
        $lang_list = $this->getDao('Language')->getList(['status' => 1]);

        foreach ($lang_list as $lang_obj) {
            $lang_id = $lang_obj->getLangId();
            $category_table = $this->getService('Category')->getCategoryName($lang_id);
            $prod_obj = $this->getDao('ProductContent')->getProductWithUrl($sku, $lang_id);
            $prod_url = '/'. $category_table[$prod_obj->getCatId()].'/'.$category_table[$prod_obj->getSubCatId()].'/'.str_replace(' ', '-', parse_url_char($prod_obj->getProdName())).'/product/'.$prod_obj->getSku();

            $this->getDao('ProductContent')->updateProductUrl($prod_url, $sku, $lang_id);
        }
    }

    public function createNewProductContentExtend($sku, $oldObj)
    {
        if ( ! $this->getDao('Product')->get(['sku' => $sku])) {
            return false;
        }

        $newObj = new \ProductContentExtendVo();
        $newObj->setProdSku($sku);
        $newObj->setLangId((string)$oldObj->lang_id);
        $this->updateProductContentExtend($newObj, $oldObj);

        return $newObj;
    }

    public function updateProductContentExtend($newObj, $oldObj)
    {
        $newObj->setFeature((string)$oldObj->feature);
        $newObj->setFeatureOriginal((string)$oldObj->feature_original);
        $newObj->setSpecification((string)$oldObj->specification);
        $newObj->setSpecOriginal((string)$oldObj->spec_original);
        $newObj->setRequirement((string)$oldObj->requirement);
        $newObj->setInstruction((string)$oldObj->instruction);
        $newObj->setApplyEnhancedListing((string)$oldObj->apply_enhanced_listing);
        $newObj->setEnhancedListing((string)$oldObj->enhanced_listing);
        $newObj->setStopSync((string)$oldObj->stop_sync);
    }

    public function createNewProductCustomClass($sku, $oldObj)
    {
        if ( ! $this->getDao('Product')->get(['sku' => $sku])) {
            return false;
        }

        $newObj = new \ProductCustomClassificationVo();
        $newObj->setSku($sku);
        $newObj->setCountryId((string)$oldObj->country_id);
        $this->updateProductCustomClass($newObj, $oldObj);

        return $newObj;
    }

    public function updateProductCustomClass($newObj, $oldObj)
    {
        $newObj->setCode((string)$oldObj->code);
        $newObj->setDescription((string)$oldObj->description);
        $newObj->setDutyPcent((string)$oldObj->duty_pcent);
    }

    public function createNewProductComplementaryAcc($id, $oldObj, $sku_mainprod, $sku_acc)
    {
        $newObj = new \ProductComplementaryAccVo();
        $newObj->setId($id);
        $this->updateProductComplementaryAcc($newObj, $oldObj, $sku_mainprod, $sku_acc);

        return $newObj;
    }

    public function updateProductComplementaryAcc($newObj, $oldObj, $sku_mainprod, $sku_acc)
    {
        $newObj->setMainprodSku((string)$sku_mainprod);
        $newObj->setAccessorySku((string)$sku_acc);
        $newObj->setDestCountryId((string)$oldObj->dest_country_id);
        $newObj->setStatus((string)$oldObj->status);
    }

    public function getProductOverview($where = [], $option = [])
    {
        $prod_obj_list = $this->getDao('Product')->getProductOverview($where, $option);

        if (is_array($prod_obj_list)) {
            foreach ($prod_obj_list as $key => $prod_obj) {
                $obj = new \ProductGoogleGscCommentDto();

                $obj->setSku($prod_obj->getSku());
                $obj->setProdGrpCd($prod_obj->getProdGrpCd());
                $obj->setColourId($prod_obj->getColourId());
                $obj->setLangId($prod_obj->getLanguageId());
                $obj->setCountryId($prod_obj->getPlatformCountryId());
                $obj->setProdStatus($prod_obj->getStatus());

                $result = $this->getService('PricingToolWebsite')->getGoogleGscComment($obj);
                $prod_obj->setGscComment($result['gsc_comment']);
                $prod_obj->setEnabledPlaCheckbox($result['enabled_pla_checkbox']);
            }
        }

        return $prod_obj_list;
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

    public function isClearance($sku)
    {
        return $this->getDao('Product')->isClearance($sku);
    }

    public function getProductCategoryReport($where, $option)
    {
        //include_once(APPPATH . "libraries/service/Data_exchange_service.php");
        $dex = new DataExchangeService();

        $report_list = $this->getDao('Product')->getProductCategoryList($where, $option);
        $this->voToXml = new VoToXml;
        $this->xmlToCsv = new XmlToCsv;

        $this->voToXml->VoToXml($report_list, APPPATH . 'data/product_category_report_vo2xml.txt');
        $this->xmlToCsv->XmlToCsv("", APPPATH . 'data/product_category_report_xml2csv.txt', TRUE, ',');

        return $dex->convert($this->voToXml, $this->xmlToCsv);
    }
}
