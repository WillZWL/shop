<?php

namespace ESG\Panther\Service;

class VbDataTransferCategoryService extends VbDataTransferService
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
                if ($cat_obj = $this->getDao('Category')->get(['id' => $category->id])) {
                    //we need to add the sponsored value to avoid overwritting it during the transfer
                    $category->addChild('sponsored', $cat_obj->getSponsored());
                    $stop_sync_priority = $cat_obj->getStopSyncPriority();
                    $category->addChild('stop_sync_priority', $stop_sync_priority);
                    if ($stop_sync_priority == 1)
                    {
                        //dont overwrite priority field with the VB value
                        $category->priority = $cat_obj->getPriority();
                    }

                	$this->getService('Category')->updateCategory($cat_obj, $category);
                	$this->getDao('Category')->update($cat_obj);
                	$reason = 'update';
                } else {
                    $category->addChild('sponsored', '0');
                    $category->addChild('stop_sync_priority', '0');

                	$cat_obj = $this->getService('Category')->createNewCategory($category);
                	$this->getDao('Category')->insert($cat_obj);
                	$reason = 'insert';
                }

                $xml[] = '<category>';
                $xml[] = '<id>'.$category->id.'</id>';
                $xml[] = '<status>5</status>'; //updated
                $xml[] = '<is_error>'.$category->is_error.'</is_error>';
                $xml[] = '<reason>'.$reason.'</reason>';
                $xml[] = '</category>';
            } catch (Exception $e) {
                $xml[] = '<category>';
                $xml[] = '<id>'.$category->id.'</id>';
                $xml[] = '<is_error>'.$category->is_error.'</is_error>';
                $xml[] = '<reason>'.$e->getMessage().'</reason>';
                $xml[] = '</category>';
            }
        }

        $xml[] = '</categories>';

        $return_feed = implode("\n", $xml);

        return $return_feed;
    }
}
