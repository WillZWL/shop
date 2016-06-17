<?php

namespace ESG\Panther\Service;

class VbDataTransferProductsService extends VbDataTransferService
{
    /*******************************************************************
    *   processVbData, get the VB data to save it in the price table
    ********************************************************************/
    public function processVbData($feed)
    {
        //Read the data sent from VB
        $xml_vb = simplexml_load_string($feed);
        unset($feed);

        $task_id = $xml_vb->attributes()->task_id;
        $is_error_task = $xml_vb->attributes()->is_error_task;

        //Create return xml string
        $xml = array();
        $xml[] = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml[] = '<products task_id="'.$task_id.'" is_error_task="'.$is_error_task.'">';

        $error_message = '';
        foreach ($xml_vb->product as $product) {
            try {
                $master_sku = (string) $product->master_sku;
                $vb_sku = (string) $product->sku;


                $mapping_sku = $this->getService('SkuMapping')->getDao('SkuMapping')->get(['ext_sku' => $master_sku]);
                if ($mapping_sku && $mapping_sku->getVbSku() != $vb_sku){ //exists mapping but not for the same vb_sku
                    $mapping_sku->setExtSku($master_sku);
                    $mapping_sku->setVbSku($vb_sku);
                    $this->getService('SkuMapping')->getDao('SkuMapping')->update($mapping_sku);
                }

                $mapping = $this->getService('SkuMapping')->getDao('SkuMapping')->get(['vb_sku' => $vb_sku]);

                if ($mapping) {
                    // mapping changed in VB, update mapping in panther
                    if ($mapping->getExtSku() != $master_sku) {
                        $mapping->setExtSku($master_sku);
                        $this->getService('SkuMapping')->getDao('SkuMapping')->update($mapping);
                    }

                    $product_obj = $this->getService('Product')->getDao('Product')->get(['sku' => $mapping->getSku()]);
                    $this->getService('Product')->updateProduct($product_obj, $product);
                    if ($this->getService('Product')->getDao('Product')->update($product_obj)) {
                        $process_status = 5;    // update success
                    } else {
                        $process_status = 3;    // update failure
                    }
                }  else {
                    // mapping doesn't exists, as a new product
                    $product_obj = $this->getService('Product')->createNewProduct($product);
                    if ($this->getService('Product')->getDao('Product')->insert($product_obj)) {
                        $sku_mpaping_obj = $this->getService('SkuMapping')->createNewSkuMapping($product_obj->getSku(), $master_sku, $vb_sku);
                        $this->getService('SkuMapping')->getDao('SkuMapping')->insert($sku_mpaping_obj);
                        $process_status = 5;    // insert product success
                    } else {
                        $process_status = 3;    // inset failure
                    }
                }

                $xml[] = '<product>';
                $xml[] = '<sku>'.$vb_sku.'</sku>';
                $xml[] = '<master_sku>'.$master_sku.'</master_sku>';
                $xml[] = '<status>'.$process_status.'</status>';
                $xml[] = '<is_error>'.$product->is_error.'</is_error>';
                $xml[] = '<reason>'.$reason.'</reason>';
                $xml[] = '</product>';
            } catch (Exception $e) {
                $xml[] = '<product>';
                $xml[] = '<sku>'.$vb_sku.'</sku>';
                $xml[] = '<master_sku>'.$master_sku.'</master_sku>';
                $xml[] = '<status>4</status>';  //error
                $xml[] = '<is_error>'.$product->is_error.'</is_error>';
                $xml[] = '<reason>'.$e->getMessage().'</reason>';
                $xml[] = '</product>';
                $error_message .= $vb_sku .'-'. $master_sku .'-'. $product->is_error .'-'. $e->getMessage() ."\r\n";
            }
        }

        $xml[] = '</products>';
        $return_feed = implode("", $xml);
        if ($error_message) {
            mail('data_transfer@eservicesgroup.com', 'Product Transfer Failed', "Error Message :".$error_message);
        }
        unset($xml);
        unset($xml_vb);
        return $return_feed;
    }
}
