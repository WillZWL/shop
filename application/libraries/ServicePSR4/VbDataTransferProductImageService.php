<?php
namespace ESG\Panther\Service;

class VbDataTransferProductImageService extends VbDataTransferService
{
    /*******************************************************************
    *   process_vb_data, get the VB data to save it in the price table
    ********************************************************************/
    public function process_vb_data ($feed)
    {
        //Read the data sent from VB
        $xml_vb = simplexml_load_string($feed);
        $task_id = $xml_vb->attributes()->task_id;

        //Create return xml string
        $xml = array();
        $xml[] = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml[] = '<product_images task_id="' . $task_id . '">';

        foreach($xml_vb->product_image as $pc)
        {
            //Get the master sku to search the corresponding sku in atomv2 database
            $master_sku = (string)$pc->master_sku;
            $sku = $this->getService('SkuMapping')->getLocalSku($master_sku);

            if (empty($sku)) {
                $xml[] = '<product_image>';
                $xml[] = '<id>' . $pc->id . '</id>';
                $xml[] = '<sku>' . $pc->sku . '</sku>';
                $xml[] = '<master_sku>' . $pc->master_sku. '</master_sku>';
                $xml[] = '<status>2</status>'; // no mapping
                $xml[] = '</product_image>';
                continue;
            }

            $pc_obj = $this->getService('Product')->getDao('ProductImage')->get(['sku' => $sku, 'lang_id' => $pc->lang_id]);

            $fail_reason = "";
            if ($master_sku == "" || $master_sku == null) $fail_reason .= "No master SKU mapped, ";
            if ($sku == "" || $sku == null) $fail_reason .= "SKU not specified, ";

            $id = 0;
            if($pc_obj_atomv2 = $this->get_dao()->get(array("id"=>$pc->id)))
            {
                $id = $pc_obj_atomv2->get_id();
            }
            else
            {
                $fail_reason .= "ID not specified, ";
            }

            try
            {
                //print $fail_reason . " id: " . $id . " id original: " . $pc->id ;
                if ($fail_reason == "")
                {
                    //update the atomv2 product image data
                    //$new_pc_obj =  $this->get_dao()->get(array("id"=>$pc->id));

                    $where = array("id"=>$pc->id);

                    $new_pc_obj = array();

                    $new_pc_obj["id"] = $pc->id;
                    $new_pc_obj["sku"] = $sku;
                    $new_pc_obj["priority"] = $pc->priority;
                    $new_pc_obj["image"] = $pc->image;
                    $new_pc_obj["alt_text"] = $sku . "_" . $pc->id . "." . $pc->image; //$pc->alt_text;
                    $new_pc_obj["image_saved"] = 0;
                    $new_pc_obj["VB_alt_text"] = $pc->alt_text;
                    $new_pc_obj["status"] = $pc->status;

                    $this->get_dao()->q_update($where, $new_pc_obj);

                    //print $this->get_dao()->db->last_query();

                    /*$new_pc_obj->set_id($pc->id);
                    $new_pc_obj->set_sku($sku);
                    $new_pc_obj->set_priority($pc->priority);
                    $new_pc_obj->set_image($pc->image);
                    $new_pc_obj->set_alt_text($sku . "_" . $id . "." . $pc->image);
                    $new_pc_obj->set_image_saved(0);
                    $new_pc_obj->set_VB_alt_text($pc->alt_text);
                    $new_pc_obj->set_status($pc->status);

                    $this->get_dao()->update($new_pc_obj);  */

                    //return result
                    $xml[] = '<product_image>';
                    $xml[] = '<id>' . $pc->id . '</id>';
                    $xml[] = '<sku>' . $pc->sku . '</sku>';
                    $xml[] = '<master_sku>' . $pc->master_sku. '</master_sku>';
                    $xml[] = '<status>5</status>'; //updated
                    $xml[] = '<is_error>' . $pc->is_error . '</is_error>';
                    $xml[] = '</product_image>';
                }
                elseif ($sku != "" && $sku != null)
                {
                    //insert
                    /*$new_pc_obj = $this->get_dao()->get();

                    $new_pc_obj->set_id($pc->id);
                    $new_pc_obj->set_sku($sku);
                    $new_pc_obj->set_priority($pc->priority);
                    $new_pc_obj->set_image($pc->image);
                    $new_pc_obj->set_alt_text($sku . "_" . $id . "." . $pc->image);
                    $new_pc_obj->set_image_saved(0);
                    $new_pc_obj->set_VB_alt_text($pc->alt_text);
                    $new_pc_obj->set_status($pc->status);

                    $this->get_dao()->insert($new_pc_obj);  */

                    $new_pc_obj = array();

                    $new_pc_obj["id"] = $pc->id;
                    $new_pc_obj["sku"] = $sku;
                    $new_pc_obj["priority"] = $pc->priority;
                    $new_pc_obj["image"] = $pc->image;
                    $new_pc_obj["alt_text"] = $sku . "_" . $pc->id . "." . $pc->image; //$pc->alt_text;
                    $new_pc_obj["image_saved"] = 0;
                    $new_pc_obj["VB_alt_text"] = $pc->alt_text;
                    $new_pc_obj["status"] = $pc->status;

                    $this->get_dao()->q_insert($new_pc_obj);

                    //return result
                    $xml[] = '<product_image>';
                    $xml[] = '<id>' . $pc->id . '</id>';
                    $xml[] = '<sku>' . $pc->sku . '</sku>';
                    $xml[] = '<master_sku>' . $pc->master_sku. '</master_sku>';
                    $xml[] = '<status>5</status>'; //updated
                    $xml[] = '<is_error>' . $pc->is_error . '</is_error>';
                    $xml[] = '</product_image>';
                }
                elseif ($sku == "" || $sku == null)
                {
                    //if the master_sku is not found in atomv2, we have to store that sku in an xml string to send it to VB
                    $xml[] = '<product_image>';
                    $xml[] = '<id>' . $pc->id . '</id>';
                    $xml[] = '<sku>' . $pc->sku . '</sku>';
                    $xml[] = '<master_sku>' . $pc->master_sku. '</master_sku>';
                    $xml[] = '<status>2</status>'; //not found
                    $xml[] = '<is_error>' . $pc->is_error . '</is_error>';
                    $xml[] = '</product_image>';
                }
                else
                {
                    $xml[] = '<product_image>';
                    $xml[] = '<id>' . $pc->id . '</id>';
                    $xml[] = '<sku>' . $pc->sku . '</sku>';
                    $xml[] = '<master_sku>' . $pc->master_sku. '</master_sku>';
                    $xml[] = '<status>3</status>'; //not updated
                    $xml[] = '<is_error>' . $pc->is_error . '</is_error>';
                    $xml[] = '</product_image>';
                }

            }
            catch(Exception $e)
            {
                $xml[] = '<product_image>';
                $xml[] = '<id>' . $pc->id . '</id>';
                $xml[] = '<sku>' . $pc->sku . '</sku>';
                $xml[] = '<master_sku>' . $pc->master_sku . '</master_sku>';
                $xml[] = '<status>4</status>';  //error
                $xml[] = '<is_error>' . $pc->is_error . '</is_error>';
                $xml[] = '</product_image>';
            }
         }

        $xml[] = '</product_images>';

        $return_feed = implode("\n", $xml);

        return $return_feed;
    }
}
