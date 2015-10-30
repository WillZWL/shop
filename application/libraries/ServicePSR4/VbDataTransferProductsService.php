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
        unset($feed);

        $task_id = $xml_vb->attributes()->task_id;
        $is_error_task = $xml_vb->attributes()->is_error_task;

        //Create return xml string
        $xml = array();
        $xml[] = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml[] = '<products task_id="' . $task_id . '" is_error_task="' . $is_error_task . '">';

        foreach($xml_vb->product as $product)
        {
            try
            {
                //Get the master sku to search the corresponding sku in atomv2 database
                $master_sku = (string)$product->master_sku;
                $sku = $this->getService('SkuMapping')->getLocalSku($master_sku);

                /*
                MASTER SKU VALIDATION
                1.- sku from VB exists in AV2 product table in the sku_vb field
                    1.1.- Look for the master sku un AV2, get the local sku
                    1.2.- Compare the local sku in mapping table with local sku in product table
                        1.2.1.- Both skus are equal -> update product
                        1.2.2.- Different skus -> update mapping with the new master, update product
                2.- sku from VB doesnt exist in AV2 product table in the sku_vb field
                    Normal process - Look for the master sku un AV2:
                        2.1.- exists -> if the product exists, update, if the product doesnt exist, insert product
                        2.2.- doesnt exist: no update
                */


                //get the sku for the product table with the VB sku
                $sku_table = "";
                if($obj_prod = $this->getService('Product')->getDao('Product')->get(array("sku_vb"=> $product->sku)))
                {
                    $sku_table = $obj_prod->getSku();
                }

                $berror_mapping = false;
                //if the VB sku exists in product table
                if ($sku_table != "" && $sku_table != null)
                {
                    //if the mapping for the new master sku doesnt exist, we change the mapping and continue the update
                    if ($sku == "" || $sku == null)
                    {
                        $bchange_mapping = true;
                        $sku = $sku_table;
                        $master_sku = $product->master_sku;
                    }
                    //if the new mapping has a different sku, we dont continue with the update, we return a message error
                    elseif($sku != $sku_table)
                    {
                        $berror_mapping = true;
                    }
                    //elseif $sku == $sku_table --> normal update
                }

                if ($berror_mapping == true)
                {
                    $xml[] = '<product>';
                    $xml[] = '<sku>' . $product->sku . '</sku>';
                    $xml[] = '<master_sku>' . $product->master_sku . '</master_sku>';
                    $xml[] = '<status>6</status>'; //mapping error
                    $xml[] = '<is_error>' . $product->is_error . '</is_error>';
                    $xml[] = '<reason>Different master sku. Sku exists</reason>';
                    $xml[] = '</product>';
                    continue;
                }

                //if the sku is mapped, we get the atomv prod_gro_id
                $master_prod_grp_id = "";
                $master_prod_grp_id = $this->getService('ProductIdentifier')->getProdGrpCdBySku($sku);

                if (empty($sku))
                {
                    // no mapping, as a new product
                    $product_obj = $this->getService('Product')->createNewProduct($product);
                    $product_obj->setProdGrpCd($master_prod_grp_id);
                    if ($this->getService('Product')->getDao('Product')->insert($product_obj)) {
                        $sku_mpaping_obj = $this->getService('SkuMapping')->createNewSkuMapping($product_obj->getSku(), $master_sku);
                        $this->getService('SkuMapping')->getDao('SkuMapping')->insert($sku_mpaping_obj);

                        $process_status = 5;    // insert product success
                    } else {
                        $process_status = 3;    // inset failure
                    }
                } else {

                    if ($bchange_mapping == true)
                    {
                        //update sku mapping
                        $where = array("sku"=>$sku_table);

                        $sku_map_obj = array();
                        $sku_map_obj["ext_sku"] = $product->master_sku;

                        $this->$this->getService('SkuMapping')->getDao('SkuMapping')->qUpdate($where, $sku_map_obj);
                    }

                    $product_obj = $this->getService('Product')->getDao('Product')->get(['sku' => $sku]);
                    $product_obj->setProdGrpCd($master_prod_grp_id);
                    $this->getService('Product')->updateProduct($product_obj, $product);
                    if ($this->getService('Product')->getDao('Product')->update($product_obj)) {
                        $process_status = 5;    // update success
                    } else {
                        $process_status = 3;    // update failure
                    }
                }


                $xml[] = '<product>';
                $xml[] = '<sku>' . (string)$pce->prod_sku . '</sku>';
                $xml[] = '<master_sku>' . (string)$pce->master_sku . '</master_sku>';
                $xml[] = '<status>' . $process_status . '</status>';
                $xml[] = '<is_error>' . $product->is_error . '</is_error>';
                $xml[] = '<reason>' . $reason . '</reason>';
                $xml[] = '</product>';
            }
            catch(Exception $e)
            {
                $xml[] = '<product>';
                $xml[] = '<sku>' . (string)$product->prod_sku . '</sku>';
                $xml[] = '<master_sku>' . (string)$product->master_sku . '</master_sku>';
                $xml[] = '<status>4</status>';  //error
                $xml[] = '<is_error>' . $product->is_error . '</is_error>';
                $xml[] = '<reason>' . $e->getMessage() . '</reason>';
                $xml[] = '</product>';
            }
         }

        $xml[] = '</products>';
        $return_feed = implode("\n", $xml);

        return $return_feed;
    }
}
