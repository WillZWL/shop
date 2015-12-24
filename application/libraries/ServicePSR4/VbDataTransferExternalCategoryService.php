<?php

namespace ESG\Panther\Service;

class VbDataTransferExternalCategoryService extends VbDataTransferService
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
        $xml[] = '<external_categories task_id="'.$task_id.'">';

        foreach ($xml_vb->external_category as $external_category) {
            try {
                if ($cat_obj = $this->getDao('ExternalCategory')->get(['id' => $external_category->id])) {
                	$this->getService('ExternalCategory')->updateExternalCategory($cat_obj, $external_category);
                	$this->getDao('ExternalCategory')->update($cat_obj);
                	$reason = 'update';
                } else {
                	$cat_obj = $this->getService('ExternalCategory')->createNewExternalCategory($external_category);
                	$this->getDao('ExternalCategory')->insert($cat_obj);
                	$reason = 'insert';
                }

                $xml[] = '<external_category>';
                $xml[] = '<id>'.$external_category->id.'</id>';
                $xml[] = '<status>5</status>'; //updated
                $xml[] = '<is_error>'.$external_category->is_error.'</is_error>';
                $xml[] = '<reason>'.$reason.'</reason>';
                $xml[] = '</external_category>';
            } catch (Exception $e) {
                $xml[] = '<external_category>';
                $xml[] = '<id>'.$external_category->id.'</id>';
                $xml[] = '<status>4</status>'; //error
                $xml[] = '<is_error>'.$external_category->is_error.'</is_error>';
                $xml[] = '<reason>'.$e->getMessage().'</reason>';
                $xml[] = '</external_category>';
            }
        }

        $xml[] = '</external_categories>';

        $return_feed = implode("", $xml);

        return $return_feed;
    }
}
