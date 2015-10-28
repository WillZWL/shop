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
        $xml[] = '<products task_id="' . $task_id . '">';

        foreach($xml_vb->product_identifier as $pc)
        {
            //Get the master sku to search the corresponding sku in atomv2 database
            $master_sku = (string)$pc->master_sku;
            $sku = $this->getService('SkuMapping')->getLocalSku($master_sku);

            if (empty($sku)) {
                $xml[] = '<product>';
                $xml[] = '<sku>' . $pc->prod_sku . '</sku>';
                $xml[] = '<platform_id>' . $pc->lang_id . '</platform_id>';
                $xml[] = '<master_sku>' . $pc->master_sku . '</master_sku>';
                $xml[] = '<status>2</status>'; // no mapping in panther
                $xml[] = '</product>';
                continue;
            }

            $pc_obj = $this->getService('Product')->getDao('ProductIdentifier')->get(['prod_sku' => $sku, 'lang_id' => $pc->lang_id]);

            if ($pc_obj) {
                // update
                $this->getService('Product')->updateProductContent($pc_obj, $pc);
                if ($this->getService('Product')->getDao('ProductContent')->update($pc_obj)) {
                    $process_status = 1;    // update success
                } else {
                    $process_status = 3;    // update failure
                }
            } else {
                // insert
                $pc_obj = $this->getService('Product')->createNewProductContent($sku, $pc);
                if ($this->getService('Product')->getDao('ProductContent')->insert($pc_obj)) {
                    $process_status = 4;    // insert success
                } else {
                    $process_status = 5;    // insert failure
                }
            }

            $xml[] = '<product>';
            $xml[] = '<sku>' . $pc->prod_sku . '</sku>';
            $xml[] = '<platform_id>' . $pc->lang_id . '</platform_id>';
            $xml[] = '<master_sku>' . $pc->master_sku . '</master_sku>';
            $xml[] = '<status>' . $process_status . '</status>';
            $xml[] = '</product>';
        }
        $xml[] = '</products>';
        $return_feed = implode("\n", $xml);

        return $return_feed;




        //Read the data sent from VB
        $xml_vb = simplexml_load_string($feed);
        unset($feed);
        $task_id = $xml_vb->attributes()->task_id;

        //Create return xml string
        $xml = array();
        $xml[] = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml[] = '<product_identifiers task_id="' . $task_id . '">';

        foreach($xml_vb->product_identifier as $product)
        {
            $master_sku = (string)$pce->master_sku;
            $sku = $this->getService('SkuMapping')->getLocalSku($master_sku);

            if (empty($sku)) {
                $xml[] = '<product>';
                $xml[] = '<sku>' . $pce->prod_sku . '</sku>';
                $xml[] = '<platform_id>' . $pce->lang_id . '</platform_id>';
                $xml[] = '<master_sku>' . $pce->master_sku . '</master_sku>';
                $xml[] = '<status>2</status>';  // no mapping
                $xml[] = '</product>';
                continue;
            }

            $prod_id_obj = $this->getService('Product')->getDao('ProductIdentifier')->get(['prod_sku' => $sku, 'lang_id' => $pc->lang_id]);

            $sku = $this->SkuMappingService->getLocalSku($master_sku);
            if ($master_sku == "" || $master_sku == null) $fail_reason .= "No master SKU mapped, ";
            if ($sku == "" || $sku == null) $fail_reason .= "SKU not specified, ";

            //if the sku is mapped, we get the atomv prod_gro_id
            $master_prod_grp_id = "";
            if ($fail_reason == "")
                $master_prod_grp_id = $this->ProductIdentifierService->getProdGrpCdBySku($sku);

            if(!$pc_obj_atomv2 = $this->getDao()->get(array("prod_grp_cd"=>$master_prod_grp_id, "colour_id"=>$product->colour_id, "country_id"=>$product->country_id)))
            {
                $fail_reason .= "Product identifier not specified, ";
            }

            try
            {
                if ($fail_reason == "")
                {
                    //Update the AtomV2 product data
                    $where = array("prod_grp_cd"=>$master_prod_grp_id, "colour_id"=>$product->colour_id, "country_id"=>$product->country_id);

                    $new_prod_obj = array();

                    $new_prod_obj["ean"] = $product->ean;
                    $new_prod_obj["mpn"] = $product->mpn;
                    $new_prod_obj["upc"] = $product->upc;
                    $new_prod_obj["status"] = $product->status;

                    $this->getDao()->qUpdate($where, $new_prod_obj);

                    $xml[] = '<product_identifier>';
                    $xml[] = '<prod_grp_cd>' . $product->prod_grp_cd . '</prod_grp_cd>';
                    $xml[] = '<colour_id>' . $product->colour_id . '</colour_id>';
                    $xml[] = '<country_id>' . $product->country_id . '</country_id>';
                    $xml[] = '<status>5</status>'; //updated
                    $xml[] = '<is_error>' . $product->is_error . '</is_error>';
                    $xml[] = '</product_identifier>';
                }
                elseif ($sku != "" && $sku != null)
                {
                    //the identifier doesnt exist, but the sku is mapped in atomv2
                    //insert the product identifier
                    $new_prod_obj = $this->getDao()->get();

                    $new_prod_obj->setProdGrpCd($master_prod_grp_id);
                    $new_prod_obj->setColourId($product->colour_id);
                    $new_prod_obj->setCountryId($product->country_id);
                    $new_prod_obj->setEan($product->ean);
                    $new_prod_obj->setMpn($product->mpn);
                    $new_prod_obj->setUpc($product->upc);
                    $new_prod_obj->setStatus($product->status);

                    $this->getDao()->insert($new_prod_obj);

                    $xml[] = '<product_identifier>';
                    $xml[] = '<prod_grp_cd>' . $product->prod_grp_cd . '</prod_grp_cd>';
                    $xml[] = '<colour_id>' . $product->colour_id . '</colour_id>';
                    $xml[] = '<country_id>' . $product->country_id . '</country_id>';
                    $xml[] = '<status>5</status>'; //updated
                    $xml[] = '<is_error>' . $product->is_error . '</is_error>';
                    $xml[] = '</product_identifier>';
                }
                elseif ($sku == "" || $sku == null)
                {
                    //if the master_sku is not found in atomv2, we have to store that prod_grp_id in an xml string to send it to VB

                    $xml[] = '<product_identifier>';
                    $xml[] = '<prod_grp_cd>' . $product->prod_grp_cd . '</prod_grp_cd>';
                    $xml[] = '<colour_id>' . $product->colour_id . '</colour_id>';
                    $xml[] = '<country_id>' . $product->country_id . '</country_id>';
                    $xml[] = '<status>2</status>'; //not found
                    $xml[] = '<is_error>' . $product->is_error . '</is_error>';
                    $xml[] = '</product_identifier>';
                }
                else
                {
                    $xml[] = '<product_identifier>';
                    $xml[] = '<prod_grp_cd>' . $product->prod_grp_cd . '</prod_grp_cd>';
                    $xml[] = '<colour_id>' . $product->colour_id . '</colour_id>';
                    $xml[] = '<country_id>' . $product->country_id . '</country_id>';
                    $xml[] = '<status>3</status>'; //not updated
                    $xml[] = '<is_error>' . $product->is_error . '</is_error>';
                    $xml[] = '</product_identifier>';
                }
            }
            catch(Exception $e)
            {
                $xml[] = '<product_identifier>';
                    $xml[] = '<prod_grp_cd>' . $product->prod_grp_cd . '</prod_grp_cd>';
                    $xml[] = '<colour_id>' . $product->colour_id . '</colour_id>';
                    $xml[] = '<country_id>' . $product->country_id . '</country_id>';
                    $xml[] = '<status>4</status>'; //error
                    $xml[] = '<is_error>' . $product->is_error . '</is_error>';
                    $xml[] = '</product_identifier>';
            }
         }

        $xml[] = '</product_identifiers>';

        $return_feed = implode("\n", $xml);

        return $return_feed;
    }
}