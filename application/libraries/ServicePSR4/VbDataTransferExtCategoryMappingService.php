<?php

namespace ESG\Panther\Service;

class VbDataTransferExtCategoryMappingService extends VbDataTransferService
{
    /**********************************************************************
    *	process_vb_data, get the VB data to save it in the category table
    ***********************************************************************/
    public function processVbData($feed)
    {
        //Read the data sent from VB
        $xml_vb = simplexml_load_string($feed);

        $task_id = $xml_vb->attributes()->task_id;

        //Create return xml string
        $xml = array();
        $xml[] = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml[] = '<ext_categories_mapping task_id="'.$task_id.'">';

        foreach ($xml_vb->ext_category_mapping as $ext_category_mapping) {
            try {
                if ($cat_obj = $this->getDao('ExtCategoryMapping')->get(['id' => $ext_category_mapping->id])) {
                	$this->getService('ExtCategoryMapping')->updateExtCategoryMapping($cat_obj, $ext_category_mapping);
                	$this->getDao('ExtCategoryMapping')->update($cat_obj);
                	$reason = 'update';
                } else {
                	$cat_obj = $this->getService('ExtCategoryMapping')->createNewExtCategoryMapping($ext_category_mapping);
                	$this->getDao('ExtCategoryMapping')->insert($cat_obj);
                	$reason = 'insert';
                }

                $xml[] = '<ext_category_mapping>';
                $xml[] = '<id>'.$ext_category_mapping->id.'</id>';
                $xml[] = '<status>5</status>'; //updated
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
            }
        }

        $xml[] = '</ext_categories_mapping>';

        $return_feed = implode("\n", $xml);

        return $return_feed;
    }
}
