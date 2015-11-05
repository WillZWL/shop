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

                $pi_obj = $this->getService('Product')->getDao('ProductImage')->get(['id' => $pc->id]);

                if ($pi_obj) {
                    $this->getService('ProductImage')->updateProductImage($pi_obj, $pc);
                    $pi_obj->setImageSaved(0);
                    if ($this->getService('ProductImage')->getDao('ProductImage')->update($pi_obj)) {
                        $process_status = 5;
                    } else {
                        $process_status = 3;
                    }
                } else {
                    $pi_obj = $this->getService('ProductImage')->createNewProductImage($sku, $pc);
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
                $xml[] = '<reason>update</reason>';
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
            }
        }

        $xml[] = '</product_images>';
        $return_feed = implode("\n", $xml);
        unset($xml);

        return $return_feed;
    }
}
