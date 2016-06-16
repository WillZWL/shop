<?php
namespace ESG\Panther\Service;

class VbDataTransferProductImageService extends VbDataTransferService
{

    /*******************************************************************
    *   processVbData, get the VB data to save it in the price table
    ********************************************************************/
    public function processVbData ($feed)
    {
        //Read the data sent from VB
        $xml_vb = simplexml_load_string($feed);
        unset($feed);
        $task_id = $xml_vb->attributes()->task_id;

        //Create return xml string
        $xml = array();
        $xml[] = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml[] = '<product_images task_id="' . $task_id . '">';

        $current_sku = "";

        $error_message = '';
        foreach($xml_vb->product_image as $pc)
        {
            try
            {
                //Get the master sku to search the corresponding sku in atomv2 database
                $master_sku = (string) $pc->master_sku;
                $sku = $this->getService('SkuMapping')->getLocalSku($master_sku);

                if (empty($sku)) {
                    $xml[] = '<product_image>';
                    $xml[] = '<id>' . $pc->id . '</id>';
                    $xml[] = '<sku>' . $pc->sku . '</sku>';
                    $xml[] = '<master_sku>' . $master_sku. '</master_sku>';
                    $xml[] = '<status>2</status>';
                    $xml[] = '<is_error>' . $pc->is_error . '</is_error>';
                    $xml[] = '<reason>No SKU mapping</reason>';
                    $xml[] = '</product_image>';
                    continue;
                }

                $vb_image = (string) $pc->sku.'_'.(string) $pc->id.'.'.(string) $pc->image;

                $pi_obj = $this->getService('Product')->getDao('ProductImage')->get(['vb_image' => $vb_image]);

                $reason = 'insert_or_update';
                if ($pi_obj) {
                    //we need to check the stop_sync_image value to stop the update when needed (only for update)
                    $stop_sync_image = $pi_obj->getStopSyncImage();

                    if ($stop_sync_image != 1)
                    {
                        $this->getService('ProductImage')->updateProductImage($pi_obj, $pc);
                        $pi_obj->setImageSaved(0);
                        if ($this->getService('ProductImage')->getDao('ProductImage')->update($pi_obj)) {
                            $process_status = 5;
                        } else {
                            $process_status = 3;
                        }
                    }
                    else
                    {
                        $reason = 'stop_sync_image';
                    }
                } else {
                    $pi_obj = $this->getService('ProductImage')->createNewProductImage($sku, $pc);
                    $pi_obj->setImageSaved(0);
                    if ($this->getService('ProductImage')->getDao('ProductImage')->insert($pi_obj)) {
                        $process_status = 5;
                    } else {
                        $process_status = 3;
                    }
                }

                $xml[] = '<product_image>';
                $xml[] = '<id>' . $pc->id . '</id>';
                $xml[] = '<sku>' . $pc->sku . '</sku>';
                $xml[] = '<master_sku>' . $master_sku. '</master_sku>';
                $xml[] = '<status>' . $process_status . '</status>'; //updated
                $xml[] = '<is_error>' . $pc->is_error . '</is_error>';
                $xml[] = '<reason>'.$reason.'</reason>';
                $xml[] = '</product_image>';
            } catch(Exception $e) {
                $xml[] = '<product_image>';
                $xml[] = '<id>' . $pc->id . '</id>';
                $xml[] = '<sku>' . $pc->sku . '</sku>';
                $xml[] = '<master_sku>' . $master_sku . '</master_sku>';
                $xml[] = '<status>4</status>';
                $xml[] = '<is_error>' . $pc->is_error . '</is_error>';
                $xml[] = '<reason>' . $e->getMessage() . '</reason>';
                $xml[] = '</product_image>';
                $error_message .= $pc->sku .'-'. $master_sku .'-'. $pc->is_error .'-'. $e->getMessage() ."\r\n";
            }
        }

        $xml[] = '</product_images>';
        $return_feed = implode("", $xml);
        if ($error_message) {
            mail('data_transfer@eservicesgroup.com', 'Product Identifier Transfer Failed', "Error Message :".$error_message);
        }
        unset($xml);
        unset($xml_vb);
        return $return_feed;
    }
}
