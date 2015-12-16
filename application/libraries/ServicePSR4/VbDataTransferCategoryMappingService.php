<?php

namespace ESG\Panther\Service;

class VbDataTransferCategoryMappingService extends VbDataTransferService
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
        $xml[] = '<categories_mapping task_id="'.$task_id.'">';

        foreach ($xml_vb->category_mapping as $cat_map) {
            try {
                if ($cat_obj = $this->getDao('CategoryMapping')->get(['ext_party' => $cat_map->ext_party, 'level' => $cat_map->level, 'category_mapping_id' => $cat_map->id, 'lang_id' => $cat_map->lang_id, 'country_id' => $cat_map->country_id])) {
                	$this->getService('CategoryMapping')->updateCategoryMapping($cat_obj, $cat_map);
                	$this->getDao('CategoryMapping')->update($cat_obj);
                	$reason = 'update';
                } else {
                	$cat_obj = $this->getService('CategoryMapping')->createNewCategoryMapping($cat_map);
                	$this->getDao('CategoryMapping')->insert($cat_obj);
                	$reason = 'insert';
                }

                $xml[] = '<category_mapping>';
                $xml[] = '<ext_party>'.$cat_map->ext_party.'</ext_party>';
                $xml[] = '<level>'.$cat_map->level.'</level>';
                $xml[] = '<id>'.$cat_map->id.'</id>';
                $xml[] = '<lang_id>'.$cat_map->lang_id.'</lang_id>';
                $xml[] = '<country_id>'.$cat_map->country_id.'</country_id>';
                $xml[] = '<status>5</status>'; //updated
                $xml[] = '<is_error>'.$cat_map->is_error.'</is_error>';
                $xml[] = '<reason>'.$reason.'</reason>';
                $xml[] = '</category_mapping>';
            } catch (Exception $e) {
                $xml[] = '<category_mapping>';
                $xml[] = '<ext_party>'.$cat_map->ext_party.'</ext_party>';
                $xml[] = '<level>'.$cat_map->level.'</level>';
                $xml[] = '<id>'.$cat_map->id.'</id>';
                $xml[] = '<lang_id>'.$cat_map->lang_id.'</lang_id>';
                $xml[] = '<country_id>'.$cat_map->country_id.'</country_id>';
                $xml[] = '<status>4</status>'; //error
                $xml[] = '<is_error>'.$cat_map->is_error.'</is_error>';
                $xml[] = '<reason>'.$e->getMessage().'</reason>';
                $xml[] = '</category_mapping>';
            }
        }

        $xml[] = '</categories_mapping>';

        $return_feed = implode("\n", $xml);

        return $return_feed;
    }
}