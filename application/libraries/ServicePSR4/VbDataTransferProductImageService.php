<?php
namespace ESG\Panther\Service;

class VbDataTransferProductImageService extends VbDataTransferService
{

	/*******************************************************************
	*	processVbData, get the VB data to save it in the price table
	********************************************************************/
	public function processVbData ($feed)
	{
		//Read the data sent from VB
		$xml_vb = simplexml_load_string($feed);

		$task_id = $xml_vb->attributes()->task_id;

		//Create return xml string
		$xml = array();
		$xml[] = '<?xml version="1.0" encoding="UTF-8"?>';
		$xml[] = '<product_images task_id="' . $task_id . '">';

		$current_sku = "";

		$c = count($xml_vb->product_image);
		foreach($xml_vb->product_image as $pc)
		{
			$c--;

			try
			{
                //Get the master sku to search the corresponding sku in atomv2 database
                $master_sku = $pc->master_sku;

                $master_sku = strtoupper($master_sku);
                $sku = $this->getService('SkuMapping')->getLocalSku($master_sku);

                if (empty($sku)) {
                    $xml[] = '<product_image>';
                    $xml[] = '<id>' . $pc->id . '</id>';
                    $xml[] = '<sku>' . $pc->sku . '</sku>';
                    $xml[] = '<master_sku>' . $pc->master_sku. '</master_sku>';
                    $xml[] = '<status>2</status>'; //updated
                    $xml[] = '<is_error>' . $pc->is_error . '</is_error>';
                    $xml[] = '<reason>No SKU mapping</reason>';
                    $xml[] = '</product_image>';
                    continue;
                }

                $pi_obj = $this->getService('Product')->getDao('ProductImage')->get(['id' => $pc->id]);

				$reason = "";
                if ($pi_obj) {
                    // update
                    $reason = "update";

                    $pi_obj->setId($pc->id);
                    $pi_obj->setSku($sku);
                    $pi_obj->setPriority($pc->priority);
                    $pi_obj->setImage($pc->image);
                    $pi_obj->setAltText($sku . "_" . $pc->id . "." . $pc->image;);
                    $pi_obj->setImageSaved(0);
                    $pi_obj->setVBAltText($pc->alt_text);
                    $pi_obj->setStatus($pc->status);

                    //$this->getService('Product')->updateProductContent($pc_obj, $pc);
                    if ($this->getService('Product')->getDao('ProductImage')->update($pi_obj)) {
                        $process_status = 5;    // update success
                    } else {
                        $process_status = 3;    // update failure
                    }
				}
				else
				{
					//insert
                    $reason = "insert";

					$new_pc_obj = array();

					$new_pc_obj["id"] = $pc->id;
					$new_pc_obj["sku"] = $sku;
					$new_pc_obj["priority"] = $pc->priority;
					$new_pc_obj["image"] = $pc->image;
					$new_pc_obj["alt_text"] = $sku . "_" . $pc->id . "." . $pc->image; //$pc->alt_text;
					$new_pc_obj["image_saved"] = 0;
					$new_pc_obj["VB_alt_text"] = $pc->alt_text;
					$new_pc_obj["status"] = $pc->status;

                    if ($this->getService('Product')->getDao('ProductImage')->qInsert($new_pc_obj)) {
                        $process_status = 5;    // insert success
                    } else {
                        $process_status = 3;    // insert failure
                    }
				}

                //return result
                $xml[] = '<product_image>';
                $xml[] = '<id>' . $pc->id . '</id>';
                $xml[] = '<sku>' . $pc->sku . '</sku>';
                $xml[] = '<master_sku>' . $pc->master_sku. '</master_sku>';
                $xml[] = '<status>' . $process_status . '</status>'; //updated
                $xml[] = '<is_error>' . $pc->is_error . '</is_error>';
                $xml[] = '<reason>' . $reason . '</reason>';
                $xml[] = '</product_image>';

			}
			catch(Exception $e)
			{
				$xml[] = '<product_image>';
				$xml[] = '<id>' . $pc->id . '</id>';
				$xml[] = '<sku>' . $pc->sku . '</sku>';
				$xml[] = '<master_sku>' . $pc->master_sku . '</master_sku>';
				$xml[] = '<status>4</status>';	//error
				$xml[] = '<is_error>' . $pc->is_error . '</is_error>';
				$xml[] = '<reason>' . $e->getMessage() . '</reason>';
				$xml[] = '</product_image>';
			}
		 }

		$xml[] = '</product_images>';

		$return_feed = implode("\n", $xml);

		return $return_feed;
	}
}
