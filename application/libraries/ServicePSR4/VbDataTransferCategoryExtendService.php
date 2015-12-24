<?php

namespace ESG\Panther\Service;

class VbDataTransferCategoryExtendService extends VbDataTransferService
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
        $xml[] = '<categories task_id="'.$task_id.'">';

        foreach ($xml_vb->category as $category) {
            try {
                $cat_ext_obj = $this->getDao('CategoryExtend')->get(['cat_id' => (string) $category->cat_id, 'lang_id' => (string) $category->lang_id]);

                $reason = 'insert_or_update';
                if ($cat_ext_obj) {
                    //we need to check the stop_sync_name value to stop the update when needed (only for update)
                    $stop_sync_name = $cat_ext_obj->getStopSyncName();
                    if ($stop_sync_name != 1)
                    {
                            $this->getService('Category')->updateCategoryExtend($cat_ext_obj, $category);
                            $this->getDao('CategoryExtend')->update($cat_ext_obj);
                    }
                    else
                    {
                        $reason = 'stop_sync_name';
                    }
                } else {
                    $cat_ext_obj = $this->getService('Category')->createNewCategoryExtend((string) $category->cat_id, $category);
                    $this->getDao('CategoryExtend')->insert($cat_ext_obj);
                }


                $xml[] = '<category>';
                $xml[] = '<id>'.$category->cat_id.'</id>';
                $xml[] = '<lang_id>'.$category->lang_id.'</lang_id>';
                $xml[] = '<status>5</status>'; //insert
                $xml[] = '<is_error>'.$category->is_error.'</is_error>';
                $xml[] = '<reason>'.$reason.'</reason>';
                $xml[] = '</category>';

            } catch (Exception $e) {
                $xml[] = '<category>';
                $xml[] = '<id>'.$category->cat_id.'</id>';
                $xml[] = '<lang_id>'.$category->lang_id.'</lang_id>';
                $xml[] = '<status>4</status>'; //error
                $xml[] = '<is_error>'.$category->is_error.'</is_error>';
                $xml[] = '<reason>'.$e->getMessage().'</reason>';
                $xml[] = '</category>';
            }
        }

        $xml[] = '</categories>';

        $return_feed = implode("", $xml);

        return $return_feed;
    }
}
