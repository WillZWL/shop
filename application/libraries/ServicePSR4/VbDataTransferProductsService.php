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
                // no mapping, as a new product
                $product_obj = $this->getService('Product')->createNewProduct($product);
                if ($this->getService('Product')->getDao('Product')->insert($product_obj)) {
                    $sku_mpaping_obj = $this->getService('SkuMapping')->createNewSkuMapping($product_obj->getSku(), $master_sku);
                    $this->getService('SkuMapping')->getDao('SkuMapping')->insert($sku_mpaping_obj);
                    $process_status = 1;    // insert product success
                } else {
                    $process_status = 2;    // inset failure
                }
            } else {
                $product_obj = $this->getService('Product')->getDao('Product')->get(['sku' => $sku]);
                $this->getService('Product')->updateProduct($product_obj, $product);
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
