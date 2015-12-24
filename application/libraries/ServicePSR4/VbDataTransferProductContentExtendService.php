<?php
namespace ESG\Panther\Service;

class VbDataTransferProductContentExtendService extends VbDataTransferService
{
    /*******************************************************************
    *   processVbData, get the VB data to save it in the price table
    ********************************************************************/
    public function processVbData ($feed)
    {
        $xml_vb = simplexml_load_string($feed, 'SimpleXMLElement', LIBXML_COMPACT | LIBXML_PARSEHUGE);
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
                    //0 NA / 1 = feature / 2 = specification / 3 = enhanced_listing
                    $stop_sync_array = array_reverse(str_split(base_convert($pce_obj->getStopSync(), 10, 2)));
                    $pce->addChild('stop_sync', $pce_obj->getStopSync());

                    foreach($stop_sync_array as $k => $v) {
                        if ($k == 1 && $v) {
                            $pce->feature = $pce_obj->getFeature();
                        }
                        if ($k == 2 && $v) {
                            $pce->specification = $pce_obj->getSpecification();
                        }
                        if ($k == 3 && $v) {
                            $pce->enhanced_listing = $pce_obj->getEnhancedListing();
                        }
                    }

                    // update
                    $reason = "update";
                    $this->getService('Product')->updateProductContentExtend($pce_obj, $pce);
                    if ($this->getService('Product')->getDao('ProductContentExtend')->update($pce_obj)) {
                        $process_status = 5;    // update success
                    } else {
                        $process_status = 3;    // update failure
                    }
                } else {
                    // insert
                    $reason = "insert";
                    $pce->addChild('stop_sync', 1);
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

        $return_feed = implode("", $xml);

        return $return_feed;
    }

}