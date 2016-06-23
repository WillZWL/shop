<?php

namespace ESG\Panther\Service;

class VbDataTransferProductComplementaryAccService extends VbDataTransferService
{
    /**********************************************************************
    *	process_vb_data, get the VB data to save it in the category table
    ***********************************************************************/
    public function processVbData($feed)
    {
        //Read the data sent from VB
        $xml_vb = simplexml_load_string($feed);
        unset($feed);
        $task_id = $xml_vb->attributes()->task_id;

        //Create return xml string
        $xml = array();
        $xml[] = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml[] = '<product_complementary_accs task_id="'.$task_id.'">';
        $error_message = '';
        foreach($xml_vb->product_complementary_acc as $pca)
        {
            try
            {
                //Get the master sku to search the corresponding sku in atomv2 database
                $master_sku = (string)$pca->master_sku_mainprod;
                $sku_mainprod = $this->getService('SkuMapping')->getLocalSku($master_sku);

                if (empty($sku_mainprod)) {
                    $xml[] = '<product_complementary_acc>';
                    $xml[] = '<id>' . $pca->id . '</id>';
                    $xml[] = '<master_sku_mainprod>' . $pca->master_sku_mainprod . '</master_sku_mainprod>';
                    $xml[] = '<master_sku_acc>' . $pca->master_sku_acc . '</master_sku_acc>';
                    $xml[] = '<status>2</status>';  // no mapping
                    $xml[] = '<is_error>' . $pca->is_error . '</is_error>';
                    $xml[] = '<reason>No SKU mapping mainprod</reason>';
                    $xml[] = '</product_complementary_acc>';
                    continue;
                }

                $master_sku = (string)$pca->master_sku_acc;
                $sku_acc = $this->getService('SkuMapping')->getLocalSku($master_sku);

                if (empty($sku_acc)) {
                    $xml[] = '<product_complementary_acc>';
                    $xml[] = '<id>' . $pca->id . '</id>';
                    $xml[] = '<master_sku_mainprod>' . $pca->master_sku_mainprod . '</master_sku_mainprod>';
                    $xml[] = '<master_sku_acc>' . $pca->master_sku_acc . '</master_sku_acc>';
                    $xml[] = '<status>2</status>';  // no mapping
                    $xml[] = '<is_error>' . $pca->is_error . '</is_error>';
                    $xml[] = '<reason>No SKU mapping accesory</reason>';
                    $xml[] = '</product_complementary_acc>';
                    continue;
                }

                $pca_obj = $this->getService('Product')->getDao('ProductComplementaryAcc')->get(['mainprod_sku' => $sku_mainprod, 'accessory_sku' => $sku_acc, 'dest_country_id' => $pca->dest_country_id]);
                //$pca_obj = $this->getService('Product')->getDao('ProductComplementaryAcc')->get(['id' => $pca->id]);
                $reason = "";
                if ($pca_obj) {
                    // update
                    $reason = "update";
                    $this->getService('Product')->updateProductComplementaryAcc($pca_obj, $pca, $sku_mainprod, $sku_acc);
                    if ($this->getService('Product')->getDao('ProductComplementaryAcc')->update($pca_obj)) {
                        $process_status = 5;    // update success
                    } else {
                        $process_status = 3;    // update failure
                    }
                } else {
                    // insert
                    $reason = "insert";
                    $pca_obj = $this->getService('Product')->createNewProductComplementaryAcc($pca->id, $pca, $sku_mainprod, $sku_acc);
                    if ($this->getService('Product')->getDao('ProductComplementaryAcc')->insert($pca_obj)) {
                        $process_status = 5;    // insert success
                    } else {
                        $process_status = 3;    // insert failure
                    }
                }

                $xml[] = '<product_complementary_acc>';
                $xml[] = '<id>' . $pca->id . '</id>';
                $xml[] = '<master_sku_mainprod>' . $pca->master_sku_mainprod . '</master_sku_mainprod>';
                $xml[] = '<master_sku_acc>' . $pca->master_sku_acc . '</master_sku_acc>';
                $xml[] = '<status>' . $process_status . '</status>';
                $xml[] = '<is_error>' . $pca->is_error . '</is_error>';
                $xml[] = '<reason>' . $reason . '</reason>';
                $xml[] = '</product_complementary_acc>';
            }
            catch(Exception $e)
            {
                $xml[] = '<product_complementary_acc>';
                $xml[] = '<id>' . $pca->id . '</id>';
                $xml[] = '<master_sku_mainprod>' . $pca->master_sku_mainprod . '</master_sku_mainprod>';
                $xml[] = '<master_sku_acc>' . $pca->master_sku_acc . '</master_sku_acc>';
                $xml[] = '<status>4</status>';  //error
                $xml[] = '<is_error>' . $pca->is_error . '</is_error>';
                $xml[] = '<reason>' . $e->getMessage() . '</reason>';
                $xml[] = '</product_complementary_acc>';
                $error_message .= $pca->sku .'-'. $pca->master_sku_mainprod .'-'. $pca->master_sku_acc .'-'. $pca->is_error .'-'. $e->getMessage()."\r\n";
            }
        }
        $xml[] = '</product_complementary_accs>';
        $return_feed = implode("", $xml);
        if ($error_message) {
            mail('data_transfer@eservicesgroup.com', 'Product Complementary Acc Transfer Failed', "Error Message :".$error_message);
        }
        unset($xml);
        unset($xml_vb);
        return $return_feed;
    }
}
