<?php

namespace ESG\Panther\Service;

class VbDataTransferCategoryMappingService extends VbDataTransferService
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
        $xml[] = '<categories_mapping task_id="'.$task_id.'">';
        $error_message = '';
        foreach ($xml_vb->category_mapping as $cat_map) {
            try {
                $vb_id = $cat_map->id; //for the result_xml
                $prod_obj= $this->getDao('SkuMapping')->get(['vb_sku' => $cat_map->id]);
                if ($prod_obj) {
                    $cat_map->id = $prod_obj->getSku();
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
                    $xml[] = '<id>'.$vb_id.'</id>';
                    $xml[] = '<lang_id>'.$cat_map->lang_id.'</lang_id>';
                    $xml[] = '<country_id>'.$cat_map->country_id.'</country_id>';
                    $xml[] = '<status>5</status>'; //updated
                    $xml[] = '<is_error>'.$cat_map->is_error.'</is_error>';
                    $xml[] = '<reason>'.$reason.'</reason>';
                    $xml[] = '</category_mapping>';
                }
            } catch (Exception $e) {
                $xml[] = '<category_mapping>';
                $xml[] = '<ext_party>'.$cat_map->ext_party.'</ext_party>';
                $xml[] = '<level>'.$cat_map->level.'</level>';
                $xml[] = '<id>'.$vb_id.'</id>';
                $xml[] = '<lang_id>'.$cat_map->lang_id.'</lang_id>';
                $xml[] = '<country_id>'.$cat_map->country_id.'</country_id>';
                $xml[] = '<status>4</status>'; //error
                $xml[] = '<is_error>'.$cat_map->is_error.'</is_error>';
                $xml[] = '<reason>'.$e->getMessage().'</reason>';
                $xml[] = '</category_mapping>';

                $error_message .= $cat_map->ext_party .'-'. $cat_map->level .'-'. $cat_map->lang_id .'-'. $cat_map->country_id .'-'. $cat_map->is_error .'-'. $e->getMessage()."\r\n";
            }
        }

        $xml[] = '</categories_mapping>';

        $return_feed = implode("", $xml);

        if ($error_message) {
            mail('data_transfer@eservicesgroup.com', 'CategoryMapping Transfer Failed', "Error Message :".$error_message);
        }
        unset($xml);
        unset($xml_vb);
        return $return_feed;
    }
}
