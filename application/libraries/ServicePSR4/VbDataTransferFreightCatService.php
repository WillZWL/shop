<?php
namespace ESG\Panther\Service;

class VbDataTransferFreightCatService extends VbDataTransferService
{

	/**********************************************************************
	*	process_vb_data, get the VB data to save it in the freight_cat table
	***********************************************************************/
	public function processVbData (&$feed)
	{
		// print $feed;
		// exit;
		//Read the data sent from VB
		$xml_vb = simplexml_load_string($feed);
		unset($feed);
		$task_id = $xml_vb->attributes()->task_id;

		//Create return xml string
		$xml = array();
		$xml[] = '<?xml version="1.0" encoding="UTF-8"?>';
		$xml[] = '<freight_cats task_id="' . $task_id . '">';

		$c = count($xml_vb->freight_cat);
		$error_message = '';
		foreach($xml_vb->freight_cat as $freight_cat)
		{
			$c--;

			$id = $freight_cat->id;

			try
			{
				if($this->getDao('FreightCategory')->get(['id' => $freight_cat->id]))
				{
					//update
					// $where = array("id"=>$id);

					// $new_freight_cat_obj = array();

					// $new_freight_cat_obj["name"] = $freight_cat->name;
					// $new_freight_cat_obj["weight"] = $freight_cat->weight;
					// $new_freight_cat_obj["declared_pcent"] = $freight_cat->declared_pcent;
					// $new_freight_cat_obj["bulk_admin_chrg"] = $freight_cat->bulk_admin_chrg;
					// $new_freight_cat_obj["status"] = $freight_cat->status;

					// $this->getDao()->qUpdate($where, $new_freight_cat_obj);

					$xml[] = '<freight_cat>';
					$xml[] = '<id>' . $freight_cat->id . '</id>';
					$xml[] = '<status>2</status>'; //we dont update freight (only insert) --> not updated
					$xml[] = '<is_error>' . $freight_cat->is_error . '</is_error>';
					$xml[] = '<reason>no updated</reason>';
					$xml[] = '</freight_cat>';
				}
				else
				{
					//insert
					$new_freight_cat_obj = new \FreightCategoryVo();//$this->getDao()->get();
					$new_freight_cat_obj->setId($freight_cat->id);
					$new_freight_cat_obj->setName($freight_cat->name);
					$new_freight_cat_obj->setWeight($freight_cat->weight);
					$new_freight_cat_obj->setDeclaredPcent($freight_cat->declared_pcent);
					$new_freight_cat_obj->setBulkAdminChrg($freight_cat->bulk_admin_chrg);
					$new_freight_cat_obj->setStatus($freight_cat->status);

					$this->getDao('FreightCategory')->insert($new_freight_cat_obj);

					$xml[] = '<freight_cat>';
					$xml[] = '<id>' . $freight_cat->id . '</id>';
					$xml[] = '<status>5</status>'; //inserted
					$xml[] = '<is_error>' . $freight_cat->is_error . '</is_error>';
					$xml[] = '<reason>insert</reason>';
					$xml[] = '</freight_cat>';
				}
			}
			catch(Exception $e)
			{
				$xml[] = '<freight_cat>';
				$xml[] = '<id>' . $freight_cat->id . '</id>';
				$xml[] = '<status>4</status>'; //error
				$xml[] = '<is_error>' . $freight_cat->is_error . '</is_error>';
				$xml[] = '<reason>' . $e->getMessage() . '</reason>';
				$xml[] = '</freight_cat>';

				$error_message .= $freight_cat->id .'-'. $freight_cat->is_error .'-'. $e->getMessage()."\r\n";
			}
		 }

		$xml[] = '</freight_cats>';
		$return_feed = implode("", $xml);
		if ($error_message) {
            mail('data_transfer@eservicesgroup.com', 'FreightCategory Transfer Failed', "Error Message :".$error_message);
        }
        unset($xml);
        unset($xml_vb);
		return $return_feed;
	}
}