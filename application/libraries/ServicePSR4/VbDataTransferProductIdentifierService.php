<?php
namespace ESG\Panther\Service;

class VbDataTransferProductIdentifierService extends VbDataTransferService
{
    /*******************************************************************
    *   processVbData, get the VB data to save it in the price table
    ********************************************************************/
    public function processVbData ($feed)
    {
        //Read the data sent from VB
        $xml_vb = simplexml_load_string($feed);
        unset($feed);

        $task_id = $xml_vb->attributes()->task_id;
        //Create return xml string
        $xml = array();
        $xml[] = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml[] = '<product_identifiers task_id="' . $task_id . '">';

        foreach($xml_vb->product_identifier as $pc)
        {
            try
            {
                $pc_obj = $this->getService('Product')->getDao('ProductIdentifier')->get(['prod_grp_cd'=>$pc->prod_grp_cd, 'colour_id'=>$pc->colour_id, 'country_id'=>$pc->country_id]);

                $reason = "";
                if ($pc_obj) {
                    // update
                    $reason = "update";

                    $pc_obj->setProdGrpCd($pc->prod_grp_cd);
                    $pc_obj->setColourId($pc->colour_id);
                    $pc_obj->setCountryId($pc->country_id);
                    $pc_obj->setEan($pc->ean);
                    $pc_obj->setMpn($pc->mpn);
                    $pc_obj->setUpc($pc->upc);
                    $pc_obj->setStatus($pc->status);

                    //$this->getService('Product')->updateProductContent($pc_obj, $pc);
                    if ($this->getService('Product')->getDao('ProductIdentifier')->update($pc_obj)) {
                        $process_status = 5;    // update success
                    } else {
                        $process_status = 3;    // update failure
                    }
                } else {
                    // insert
                    $reason = "insert";

                    //insert the product identifier
                    $new_pi_obj = $this->getService('Product')->getDao('ProductIdentifier')->get();

                    $new_pi_obj->setProdGrpCd( $pc->prod_grp_cd);
                    $new_pi_obj->setColourId($pc->colour_id);
                    $new_pi_obj->setCountryId($pc->country_id);
                    $new_pi_obj->setEan($pc->ean);
                    $new_pi_obj->setMpn($pc->mpn);
                    $new_pi_obj->setUpc($pc->upc);
                    $new_pi_obj->setStatus($pc->status);

                    //$pc_obj = $this->getService('Product')->createNewProductContent($sku, $pc);
                    if ($this->getService('Product')->getDao('ProductIdentifier')->insert($new_pi_obj)){
                        $process_status = 5;    // insert success
                    } else {
                        $process_status = 3;    // insert failure
                    }
                }

                $xml[] = '<product_identifier>';
                $xml[] = '<prod_grp_cd>' . $pc->prod_grp_cd . '</prod_grp_cd>';
                $xml[] = '<colour_id>' . $pc->colour_id . '</colour_id>';
                $xml[] = '<country_id>' . $pc->country_id . '</country_id>';
                $xml[] = '<status>' . $process_status . '</status>';
                $xml[] = '<is_error>' . $pc->is_error . '</is_error>';
                $xml[] = '<reason>' . $reason . '</reason>';
                $xml[] = '</product_identifier>';


            }
            catch(Exception $e)
            {
                $xml[] = '<product_identifier>';
                $xml[] = '<prod_grp_cd>' . $pc->prod_grp_cd . '</prod_grp_cd>';
                $xml[] = '<colour_id>' . $pc->colour_id . '</colour_id>';
                $xml[] = '<country_id>' . $pc->country_id . '</country_id>';
                $xml[] = '<status>4</status>'; //error
                $xml[] = '<is_error>' . $pc->is_error . '</is_error>';
                $xml[] = '<reason>' . $e->getMessage() . '</reason>';
                $xml[] = '</product_identifier>';
            }
        }
        $xml[] = '</product_identifiers>';
        $return_feed = implode("", $xml);

        return $return_feed;
    }
}
