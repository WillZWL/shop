<?php
namespace ESG\Panther\Service;

class VbDataTransferProductCustomClassService extends VbDataTransferService
{
    /*******************************************************************
    *   processVbData, get the VB data to save it in the price table
    ********************************************************************/
    public function processVbData ($feed)
    {
        $xml_vb = simplexml_load_string($feed);
        unset($feed);
        $task_id = $xml_vb->attributes()->task_id;

        //Create return xml string
        $xml = array();
        $xml[] = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml[] = '<products task_id="' . $task_id . '">';

        foreach($xml_vb->product as $pcc)
        {
            try
            {
                //Get the master sku to search the corresponding sku in atomv2 database
                $master_sku = (string)$pcc->master_sku;
                $sku = $this->getService('SkuMapping')->getLocalSku($master_sku);

                if (empty($sku)) {
                    $xml[] = '<product>';
                    $xml[] = '<sku>' . $pcc->prod_sku . '</sku>';
                    $xml[] = '<platform_id>' . $pcc->country_id . '</platform_id>';
                    $xml[] = '<master_sku>' . $pcc->master_sku . '</master_sku>';
                    $xml[] = '<status>2</status>';  // no mapping
                    $xml[] = '<is_error>' . $pcc->is_error . '</is_error>';
                    $xml[] = '<reason>No SKU mapping</reason>';
                    $xml[] = '</product>';
                    continue;
                }

                $pcc_obj = $this->getService('Product')->getDao('ProductCustomClassification')->get(['sku' => $sku, 'country_id' => $pcc->country_id]);
                $reason = "";
                if ($pcc_obj) {
                    // update
                    $reason = "update";
                    $this->getService('Product')->updateProductCustomClass($pcc_obj, $pc);
                    if ($this->getService('Product')->getDao('ProductCustomClassification')->update($pcc_obj)) {
                        $process_status = 5;    // update success
                    } else {
                        $process_status = 3;    // update failure
                    }
                } else {
                    // insert
                    $reason = "insert";
                    $pcc_obj = $this->getService('Product')->createNewProductCustomClass($sku, $pcc);
                    if ($this->getService('Product')->getDao('ProductCustomClassification')->insert($pcc_obj)) {
                        $process_status = 5;    // insert success
                    } else {
                        $process_status = 3;    // insert failure
                    }
                }

                $xml[] = '<product>';
                $xml[] = '<sku>' . $pcc->prod_sku . '</sku>';
                $xml[] = '<platform_id>' . $pcc->country_id . '</platform_id>';
                $xml[] = '<master_sku>' . $pcc->master_sku . '</master_sku>';
                $xml[] = '<status>' . $process_status . '</status>';
                $xml[] = '<is_error>' . $pcc->is_error . '</is_error>';
                $xml[] = '<reason>' . $reason . '</reason>';
                $xml[] = '</product>';
            }
            catch(Exception $e)
            {
                $xml[] = '<product>';
                $xml[] = '<sku>' . $pcc->prod_sku . '</sku>';
                $xml[] = '<platform_id>' . $pcc->lang_id . '</platform_id>';
                $xml[] = '<master_sku>' . $pcc->master_sku . '</master_sku>';
                $xml[] = '<status>4</status>';  //error
                $xml[] = '<is_error>' . $pcc->is_error . '</is_error>';
                $xml[] = '<reason>' . $e->getMessage() . '</reason>';
                $xml[] = '</product>';
            }
        }
        $xml[] = '</products>';
        $return_feed = implode("\n", $xml);

        return $return_feed;
    }
}

