<?php
namespace ESG\Panther\Service;

class VbDataTransferProductContentExtendService extends VbDataTransferService
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

        foreach($xml_vb->product as $pce)
        {
            try
            {

                //Get the master sku to search the corresponding sku in atomv2 database
                $master_sku = (string)$pce->master_sku;
                $sku = $this->getService('SkuMapping')->getLocalSku($master_sku);

                if (empty($sku)) {
                    $xml[] = '<product>';
                    $xml[] = '<sku>' . $pce->prod_sku . '</sku>';
                    $xml[] = '<platform_id>' . $pce->lang_id . '</platform_id>';
                    $xml[] = '<master_sku>' . $pce->master_sku . '</master_sku>';
                    $xml[] = '<status>2</status>';  // no mapping
                    $xml[] = '<is_error>' . $pce->is_error . '</is_error>';
                    $xml[] = '<reason>No SKU mapping</reason>';
                    $xml[] = '</product>';
                    continue;
                }

                $pce_obj = $this->getService('Product')->getDao('ProductContentExtend')->get(['prod_sku' => $sku, 'lang_id' => $pce->lang_id]);
                $reason = "";
                if ($pce_obj) {
                    // update
                    $reason = "update";
                    $this->getService('Product')->updateProductContentExtend($pce_obj, $pc);
                    if ($this->getService('Product')->getDao('ProductContentExtend')->update($pce_obj)) {
                        $process_status = 5;    // update success
                    } else {
                        $process_status = 3;    // update failure
                    }
                } else {
                    // insert
                    $reason = "insert";
                    $pce_obj = $this->getService('Product')->createNewProductContentExtend($sku, $pce);
                    if ($this->getService('Product')->getDao('ProductContentExtend')->insert($pce_obj)) {
                        $process_status = 5;    // insert success
                    } else {
                        $process_status = 3;    // insert failure
                    }
                }

                $xml[] = '<product>';
                $xml[] = '<sku>' . $pce->prod_sku . '</sku>';
                $xml[] = '<platform_id>' . $pce->lang_id . '</platform_id>';
                $xml[] = '<master_sku>' . $pce->master_sku . '</master_sku>';
                $xml[] = '<status>' . $process_status . '</status>';
                $xml[] = '<is_error>' . $pce->is_error . '</is_error>';
                $xml[] = '<reason>' . $reason . '</reason>';
                $xml[] = '</product>';
            }
            catch(Exception $e)
            {
                $xml[] = '<product>';
                $xml[] = '<sku>' . $pce->prod_sku . '</sku>';
                $xml[] = '<platform_id>' . $pce->lang_id . '</platform_id>';
                $xml[] = '<master_sku>' . $pce->master_sku . '</master_sku>';
                $xml[] = '<status>4</status>';  //error
                $xml[] = '<is_error>' . $pce->is_error . '</is_error>';
                $xml[] = '<reason>' . $e->getMessage() . '</reason>';
                $xml[] = '</product>';
            }
         }

        $xml[] = '</products>';

        $return_feed = implode("\n", $xml);

        return $return_feed;
    }

}