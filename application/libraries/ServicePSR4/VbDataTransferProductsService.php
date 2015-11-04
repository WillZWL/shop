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

                Get the local sku (from sku_mapping table)
                Check if the VB sku (in the xml) exists in product table in atomv2 in the field sku_vb

                1.- sku from VB exists in AV2 product table in the sku_vb field
                    1.1.- Compare the local sku in mapping table with local sku in product table
                        1.1.1.- Both skus are equal -> update product
                        1.1.2.- Different skus, local sku (mapping table) DOESNT EXISTS -> update mapping with the new master, update product
                        1.1.3.- Different skus, local sku (mapping table) EXISTS -> return status = 6 in result_xml. No update in AV2 product table (send mail to Chapman from VB)
                2.- sku from VB doesnt exist in AV2 product table in the sku_vb field
                    Normal process - Look for the master sku un AV2:
                        2.1.- exists -> if the product exists, update, if the product doesnt exist, insert product (and mapping if needed)
                */


                //get the sku for the product table with the VB sku
                $sku_table = "";
                if($obj_prod = $this->getService('Product')->getDao('Product')->get(array("sku_vb"=> $product->sku)))
                {
                    $sku_table = $obj_prod->getSku();
                }

                $berror_mapping = false;
                //if the VB sku exists in product table
                if (!empty($sku_table))
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
                else //if the new mapping has a different sku, we dont continue with the update, we return a message error
                {
                    //if the sku is mapped, we get the atomv prod_gro_id
                    //$master_prod_grp_id = "";
                    //$master_prod_grp_id = $this->getService('ProductIdentifier')->getProdGrpCdBySku($master_sku);

                    if (empty($sku))
                    {
                        $reason = "insert";
                        // no mapping, as a new product
                        $product_obj = $this->getService('Product')->createNewProduct($product);
                        //$product_obj["prod_grp_cd"] = $master_prod_grp_id;
                        if ($this->getService('Product')->getDao('Product')->insert($product_obj)) {
                            $sku_mpaping_obj = $this->getService('SkuMapping')->createNewSkuMapping($product_obj->getSku(), $master_sku);
                            $this->getService('SkuMapping')->getDao('SkuMapping')->insert($sku_mpaping_obj);

                            $process_status = 5;    // insert product success
                        } else {
                            $process_status = 3;    // inset failure
                        }
                    } else {

                        $reason = "update";

                        if ($bchange_mapping == true)
                        {
                            //update sku mapping
                            $where = array("sku"=>$sku_table);

                            $sku_map_obj = array();
                            $sku_map_obj["ext_sku"] = $product->master_sku;

                            $this->$this->getService('SkuMapping')->getDao('SkuMapping')->qUpdate($where, $sku_map_obj);
                        }

                        $product_obj = $this->getService('Product')->getDao('Product')->get(['sku' => $sku]);

                        $this->getService('Product')->updateProduct($product_obj, $product);
                        if ($this->getService('Product')->getDao('Product')->update($product_obj)) {
                            $process_status = 5;    // update success
                        } else {
                            $process_status = 3;    // update failure
                        }
                    }


                    $xml[] = '<product>';
                    $xml[] = '<sku>' . (string)$product->prod_sku . '</sku>';
                    $xml[] = '<master_sku>' . (string)$product->master_sku . '</master_sku>';
                    $xml[] = '<status>' . $process_status . '</status>';
                    $xml[] = '<is_error>' . $product->is_error . '</is_error>';
                    $xml[] = '<reason>' . $reason . '</reason>';
                    $xml[] = '</product>';
                }
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
