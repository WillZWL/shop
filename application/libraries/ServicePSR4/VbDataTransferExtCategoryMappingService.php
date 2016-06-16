<?php

namespace ESG\Panther\Service;

class VbDataTransferExtCategoryMappingService extends VbDataTransferService
{
    /**********************************************************************
    *	process_vb_data, get the VB data to save it in the category table
    ***********************************************************************/
    public function processVbData(&$feed)
    {
        //Read the data sent from VB
        $xml_vb = simplexml_load_string($feed);
        unset($feed);
        $task_id = $xml_vb->attributes()->task_id;

        //Create return xml string
        $xml = array();
        $xml[] = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml[] = '<ext_categories_mapping task_id="'.$task_id.'">';
        $error_message = '';
        foreach ($xml_vb->ext_category_mapping as $ext_category_mapping) {
            try {
                if ($cat_obj = $this->getDao('ExtCategoryMapping')->get(['ext_party' => $ext_category_mapping->ext_party,
                                                                        'category_id' => $ext_category_mapping->category_id,
                                                                        'ext_id' => $ext_category_mapping->ext_id,
                                                                        'country_id' => $ext_category_mapping->country_id])) {
                	$this->getService('ExtCategoryMapping')->updateExtCategoryMapping($cat_obj, $ext_category_mapping);
                    if ($this->getService('ExtCategoryMapping')->getDao('ExtCategoryMapping')->update($cat_obj)) {
                        $process_status = 5;    // update success
                    } else {
                        $process_status = 3;    // update failure
                    }
                	$reason = 'update';
                } else {
                	$cat_obj = $this->getService('ExtCategoryMapping')->createNewExtCategoryMapping($ext_category_mapping);
                    if ($this->getService('ExtCategoryMapping')->getDao('ExtCategoryMapping')->insert($cat_obj)) {
                        $process_status = 5;    // insert success
                    } else {
                        $process_status = 3;    // insert failure
                    }
                	$reason = 'insert';
                }

                $xml[] = '<ext_category_mapping>';
                $xml[] = '<id>'.$ext_category_mapping->id.'</id>';
                $xml[] = '<status>' . $process_status . '</status>'; //updated
                $xml[] = '<is_error>'.$ext_category_mapping->is_error.'</is_error>';
                $xml[] = '<reason>'.$reason.'</reason>';
                $xml[] = '</ext_category_mapping>';
            } catch (Exception $e) {
                $xml[] = '<ext_category_mapping>';
                $xml[] = '<id>'.$ext_category_mapping->id.'</id>';
                $xml[] = '<status>4</status>'; //error
                $xml[] = '<is_error>'.$ext_category_mapping->is_error.'</is_error>';
                $xml[] = '<reason>'.$e->getMessage().'</reason>';
                $xml[] = '</ext_category_mapping>';

                $error_message .= $ext_category_mapping->id .'-'. $ext_category_mapping->is_error .'-'. $e->getMessage()."\r\n";
            }
        }

        $xml[] = '</ext_categories_mapping>';
        $return_feed = implode("", $xml);
        if ($error_message) {
            mail('data_transfer@eservicesgroup.com', 'ExtCategoryMapping Transfer Failed', "Error Message :".$error_message);
        }
        unset($xml);
        unset($xml_vb);
        return $return_feed;
    }
}
