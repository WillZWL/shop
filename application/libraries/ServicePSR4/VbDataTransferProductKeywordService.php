<?php

namespace ESG\Panther\Service;

class VbDataTransferProductKeywordService extends VbDataTransferService
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

        $current_sku = "";

        $error_message = '';
        foreach($xml_vb->product as $pc)
        {
            try {
                $master_sku = $pc->master_sku;
                $sku = $this->getService('SkuMapping')->getLocalSku($master_sku);

                if (empty($sku)) {
                    $xml[] = '<product>';
                    $xml[] = '<sku>' . $pc->sku . '</sku>';
                    $xml[] = '<platform_id>' . $pc->lang_id . '</platform_id>';
                    $xml[] = '<master_sku>' . $master_sku . '</master_sku>';
                    $xml[] = '<status>2</status>';
                    $xml[] = '<is_error>' . $pc->is_error . '</is_error>';
                    $xml[] = '<reason>' . $fail_reason . '</reason>';
                    $xml[] = '</product>';
                    continue;
                }



            } catch (Exception $e) {

            }





            //Get the master sku to search the corresponding sku in atomv2 database




            $fail_reason = "";
            if ($master_sku == "" || $master_sku == null) $fail_reason .= "No master SKU mapped, ";
            if ($sku == "" || $sku == null) $fail_reason .= "SKU not specified, ";

            try
            {
                if ($fail_reason == "")
                {
                    if($sku != $current_sku)
                    {
                        //First, we delete the AtomV2 product data
                        $where = array("sku"=>$sku);
                        $this->getDao('ProductKeyword')->qDelete($where);

                        $current_sku = $sku;
                    }

                    //After deleting, we insert de VB data
                    $new_pc_obj = $this->getDao('ProductKeyword')->get();

                    $new_pc_obj->set_sku($sku);
                    $new_pc_obj->setLangId($pc->lang_id);
                    $new_pc_obj->setKeyword($this->replaceSpecialChars($pc->keyword));
                    $new_pc_obj->setType($pc->type);

                    $this->getDao('ProductKeyword')->insert($new_pc_obj);

                    //return result
                    $xml[] = '<product>';
                    $xml[] = '<sku>' . $pc->sku . '</sku>';
                    $xml[] = '<platform_id>' . $pc->lang_id . '</platform_id>';
                    $xml[] = '<master_sku>' . $pc->master_sku . '</master_sku>';
                    $xml[] = '<status>5</status>'; //updated
                    $xml[] = '<is_error>' . $pc->is_error . '</is_error>';
                    $xml[] = '<reason>insert</reason>';
                    $xml[] = '</product>';
                }
                elseif ($sku == "" || $sku == null)
                {
                    //if the master_sku is not found in atomv2, we have to store that sku in an xml string to send it to VB
                    $xml[] = '<product>';
                    $xml[] = '<sku>' . $pc->sku . '</sku>';
                    $xml[] = '<platform_id>' . $pc->lang_id . '</platform_id>';
                    $xml[] = '<master_sku>' . $pc->master_sku . '</master_sku>';
                    $xml[] = '<status>2</status>';
                    $xml[] = '<is_error>' . $pc->is_error . '</is_error>';
                    $xml[] = '<reason>' . $fail_reason . '</reason>';
                    $xml[] = '</product>';
                }
                else
                {
                    //if the master_sku is not found in atomv2, we have to store that sku in an xml string to send it to VB
                    $xml[] = '<product>';
                    $xml[] = '<sku>' . $pc->sku . '</sku>';
                    $xml[] = '<platform_id>' . $pc->lang_id . '</platform_id>';
                    $xml[] = '<master_sku>' . $pc->master_sku . '</master_sku>';
                    $xml[] = '<status>2</status>';
                    $xml[] = '<is_error>' . $pc->is_error . '</is_error>';
                    $xml[] = '<reason>' . $fail_reason . '</reason>';
                    $xml[] = '</product>';
                }

            }
            catch(Exception $e)
            {
                $xml[] = '<product>';
                $xml[] = '<sku>' . $pc->sku . '</sku>';
                $xml[] = '<platform_id>' . $pc->lang_id . '</platform_id>';
                $xml[] = '<master_sku>' . $pc->master_sku . '</master_sku>';
                $xml[] = '<status>4</status>';  //error
                $xml[] = '<is_error>' . $pc->is_error . '</is_error>';
                $xml[] = '<reason>' . $e->getMessage() . '</reason>';
                $xml[] = '</product>';
                $error_message .= $pc->sku .'-'. $pc->lang_id .'-'. $pc->master_sku .'-'. $pc->is_error .'-'. $e->getMessage()."\r\n";
            }
         }

        $xml[] = '</products>';

        $return_feed = implode("", $xml);
        if ($error_message) {
            mail('data_transfer@eservicesgroup.com', 'Product Keywords Transfer Failed', "Error Message :".$error_message);
        }
        unset($xml);
        unset($xml_vb);
        return $return_feed;
    }
}