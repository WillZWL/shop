<?php
namespace ESG\Panther\Service;

use ESG\Panther\Dao\ProductDao;
use ESG\Panther\Dao\SkuMappingDao;
use ESG\Panther\Service\SkuMappingService;
use ESG\Panther\Service\ProductIdentifierService;

class VbDataTransferProductsService extends VbDataTransferService
{

    public function getDao()
    {
        return $this->product_dao;
    }

    /*******************************************************************
    *   processVbData, get the VB data to save it in the price table
    ********************************************************************/
    public function processVbData ($feed)
    {
        //print $feed; exit;
        //Read the data sent from VB
        $xml_vb = simplexml_load_string($feed);

        $task_id = $xml_vb->attributes()->task_id;
        $is_error_task = $xml_vb->attributes()->is_error_task;

        //Create return xml string
        $xml = array();
        $xml[] = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml[] = '<products task_id="' . $task_id . '" is_error_task="' . $is_error_task . '">';

        foreach($xml_vb->product as $product) {
            //Get the master sku to search the corresponding sku in atomv2 database
            $master_sku = (string)$product->master_sku;

            $sku = $this->getService('SkuMapping')->getLocalSku($master_sku);

            if (empty($sku)) {
                // no mapping, need insert sku_mapping table
                $sku_mapping_vo = new \SkuMappingVo();
                $sku = $this->getService('Product')->getDao('Product')->getNewSku();
                $sku_mapping_vo->setSku($sku);
                $sku_mapping_vo->setExtSys('WMS');
                $sku_mapping_vo->setExtSku($master_sku);
                $this->getService('SkuMapping')->getDao('SkuMapping')->insert($sku_mapping_vo);
            }

            $product_obj = $this->getService('Product')->getDao('Product')->get(['sku' => $sku]);

            if (empty($product_obj)) {
                // insert product
                $product_obj = new \ProductVo();
                $product_obj->setSku($sku);
                $product_obj->setProdGrpCd((string)$product->prod_grp_cd);
                $product_obj->setColourId((string)$product->colour_id);
                $product_obj->setVersionId((string)$product->version_id);
                $product_obj->setName((string)$product->name);
                $product_obj->setFreightCatId((string)$product->freight_cat_id);
                $product_obj->setCatId((string)$product->cat_id);
                $product_obj->setSubCatId((string)$product->sub_cat_id);
                $product_obj->setSubSubCatId((string)$product->sub_sub_cat_id);
                $product_obj->setBrandId((string)$product->brand_id);
                $product_obj->setClearance((string)$product->clearance);
                $product_obj->setSurplusQuantity((string)$product->surplus_quantity);
                $product_obj->setQuantity((string)$product->quantity);
                $product_obj->setDisplayQuantity((string)$product->display_quantity);
                $product_obj->setWebsiteQuantity((string)$product->website_quantity);
                $product_obj->setChinaOem((string)$product->china_oem);
                $product_obj->setRrp((string)$product->rrp);
                $product_obj->setImage((string)$product->image);
                $product_obj->setFlash((string)$product->flash);
                $product_obj->setYoutubeId((string)$product->youtube_id);
                $product_obj->setEan((string)$product->ean);
                $product_obj->setMpn((string)$product->mpn);
                $product_obj->setUpc((string)$product->upc);
                $product_obj->setDiscount((string)$product->discount);
                $product_obj->setProcStatus((string)$product->proc_status);
                $product_obj->setWebsiteStatus((string)$product->website_status);
                $product_obj->setSourcingStatus((string)$product->sourcing_status);
                $product_obj->setExpectedDeliveryDate((string)$product->expected_delivery_date);
                $product_obj->setWarrantyInMonth((string)$product->warranty_in_month);
                $product_obj->setCatUpselling((string)$product->cat_upselling);
                $product_obj->setLangRestricted((string)$product->lang_restricted);
                $product_obj->setShipmentRestrictedType((string)$product->shipment_restricted_type);
                $product_obj->setStatus((string)$product->status);

                if ($this->getService('Product')->getDao('Product')->insert($product_obj)) {
                    $process_status = 1;    // insert success
                } else {
                    $process_status = 2;    // inset failure
                }
            } else {
                // update product
                $product_obj->setSku($sku);
                $product_obj->setProdGrpCd((string)$product->prod_grp_cd);
                $product_obj->setColourId((string)$product->colour_id);
                $product_obj->setVersionId((string)$product->version_id);
                $product_obj->setName((string)$product->name);
                $product_obj->setFreightCatId((string)$product->freight_cat_id);
                $product_obj->setCatId((string)$product->cat_id);
                $product_obj->setSubCatId((string)$product->sub_cat_id);
                $product_obj->setSubSubCatId((string)$product->sub_sub_cat_id);
                $product_obj->setBrandId((string)$product->brand_id);
                $product_obj->setClearance((string)$product->clearance);
                $product_obj->setSurplusQuantity((string)$product->surplus_quantity);
                $product_obj->setQuantity((string)$product->quantity);
                $product_obj->setDisplayQuantity((string)$product->display_quantity);
                $product_obj->setWebsiteQuantity((string)$product->website_quantity);
                $product_obj->setChinaOem((string)$product->china_oem);
                $product_obj->setRrp((string)$product->rrp);
                $product_obj->setImage((string)$product->image);
                $product_obj->setFlash((string)$product->flash);
                $product_obj->setYoutubeId((string)$product->youtube_id);
                $product_obj->setEan((string)$product->ean);
                $product_obj->setMpn((string)$product->mpn);
                $product_obj->setUpc((string)$product->upc);
                $product_obj->setDiscount((string)$product->discount);
                $product_obj->setProcStatus((string)$product->proc_status);
                $product_obj->setWebsiteStatus((string)$product->website_status);
                $product_obj->setSourcingStatus((string)$product->sourcing_status);
                $product_obj->setExpectedDeliveryDate((string)$product->expected_delivery_date);
                $product_obj->setWarrantyInMonth((string)$product->warranty_in_month);
                $product_obj->setCatUpselling((string)$product->cat_upselling);
                $product_obj->setLangRestricted((string)$product->lang_restricted);
                $product_obj->setShipmentRestrictedType((string)$product->shipment_restricted_type);
                $product_obj->setStatus((string)$product->status);

                if ($this->getService('Product')->getDao('Product')->update($product_obj)) {
                    $process_status = 3;    // update success
                } else {
                    $process_status = 4;    // update failure
                }
            }

            $xml[] = '<product>';
            $xml[] = '<sku>' . (string)$product->sku . '</sku>';
            $xml[] = '<master_sku>' . (string)$product->master_sku . '</master_sku>';
            $xml[] = '<status>' . $process_status . '</status>';
            $xml[] = '</product>';
         }

        $xml[] = '</products>';
        $return_feed = implode("\n", $xml);

        return $return_feed;
    }
}