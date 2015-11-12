<?php

namespace ESG\Panther\Service;

class VbDataTransferBrandService extends VbDataTransferService
{
    /**********************************************************************
    *   process_vb_data, get the VB data to save it in the brand table
    ***********************************************************************/
    public function processVbData($feed)
    {
        //Read the data sent from VB
        $xml_vb = simplexml_load_string($feed);

        $task_id = $xml_vb->attributes()->task_id;

        //Create return xml string
        $xml = array();
        $xml[] = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml[] = '<brands task_id="'.$task_id.'">';

        foreach ($xml_vb->brand as $brand) {
            $id = $brand->id;
            try {
                if ($brand_obj = $this->getDao('Brand')->get(['id' => $brand->id])) {
                    $this->getService('Brand')->updateBrand($brand_obj, $brand);
                    $this->getDao('Brand')->update($brand_obj);
                } else {
                    $brand_obj = $this->getService('Brand')->createNewBrand($id, $brand);
                    $this->getDao('Brand')->insert($brand_obj);
                }
                $xml[] = '<brand>';
                $xml[] = '<id>'.$brand->id.'</id>';
                $xml[] = '<status>5</status>'; //updated
                $xml[] = '<is_error>'.$brand->is_error.'</is_error>';
                $xml[] = '<reason>insert_or_update</reason>';
                $xml[] = '</brand>';
            } catch (Exception $e) {
                $xml[] = '<brand>';
                $xml[] = '<id>'.$brand->id.'</id>';
                $xml[] = '<status>4</status>'; //error
                $xml[] = '<is_error>'.$brand->is_error.'</is_error>';
                $xml[] = '<reason>'.$e->getMessage().'</reason>';
                $xml[] = '</brand>';
            }
        }

        $xml[] = '</brands>';

        $return_feed = implode("\n", $xml);

        return $return_feed;
    }
}
