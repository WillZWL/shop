<?php
namespace ESG\Panther\Service;

class VbDataTransferRaGroupProductService extends VbDataTransferService
{

	/**********************************************************************
	*	processVbData, get the VB data to save it in the ra_group_product table
	***********************************************************************/
	public function processVbData ($feed)
	{
		//Read the data sent from VB
		$xml_vb = simplexml_load_string($feed);

		$task_id = $xml_vb->attributes()->task_id;

		//Create return xml string
		$xml = array();
		$xml[] = '<?xml version="1.0" encoding="UTF-8"?>';
		$xml[] = '<ra_group_products task_id="' . $task_id . '">';

		foreach($xml_vb->ra_group_product as $ra_group_product)
		{
			try
			{
				//Get the master sku to search the corresponding sku in atomv2 database
				$master_sku = (string)$ra_group_product->master_sku;
                $sku = $this->getService('SkuMapping')->getLocalSku($master_sku);

                if (empty($sku)) {
					$xml[] = '<ra_group_product>';
					$xml[] = '<id>' . $ra_group_product->ra_group_id . '</id>';
					$xml[] = '<sku>' . $ra_group_product->sku . '</sku>';
					$xml[] = '<master_sku>' . $ra_group_product->master_sku . '</master_sku>';
					$xml[] = '<status>2</status>'; //updated
					$xml[] = '<is_error>' . $ra_group_product->is_error . '</is_error>';
					$xml[] = '<reason>No SKU mapping</reason>';
					$xml[] = '</ra_group_product>';
                    continue;
                }

				$is_delete =  $ra_group_product->is_delete;

				$ra_group_product_obj = $this->getDao('RaGroupProduct')->get(['ra_group_id' => $ra_group_product->ra_group_id, 'sku' => $sku]);

				if ($ra_group_product_obj) {
					if ($is_delete)	{
						$reason = 'delete';
						$this->getDao('RaGroupProduct')->delete($ra_group_product_obj);
					}
					else
					{
						$reason = 'update';
						$ra_group_product_obj->setPriority($ra_group_product->priority);
						$ra_group_product_obj->setBuildBundle($ra_group_product->build_bundle);

						$this->getDao('RaGroupProduct')->update($ra_group_product_obj);
					}
				}
				else
				{
					$reason = 'insert';

					$ra_group_product_obj = new \RaGroupProductVo(); //$this->getDao()->get();
					$ra_group_product_obj->setRaGroupId($ra_group_product->ra_group_id);
					$ra_group_product_obj->setSku($sku);
					$ra_group_product_obj->setPriority($ra_group_product->priority);
					$ra_group_product_obj->setBuildBundle($ra_group_product->build_bundle);

					$this->getDao('RaGroupProduct')->insert($ra_group_product_obj);
				}

				$xml[] = '<ra_group_product>';
				$xml[] = '<id>' . $ra_group_product->ra_group_id . '</id>';
				$xml[] = '<sku>' . $ra_group_product->sku . '</sku>';
				$xml[] = '<master_sku>' . $ra_group_product->master_sku . '</master_sku>';
				$xml[] = '<status>5</status>'; //updated
				$xml[] = '<is_error>' . $ra_group_product->is_error . '</is_error>';
				$xml[] = '<reason>'.$reason.'</reason>';
				$xml[] = '</ra_group_product>';

			}
			catch(Exception $e)
			{
				$xml[] = '<ra_group_product>';
				$xml[] = '<id>' . $ra_group_product->ra_group_id . '</id>';
				$xml[] = '<sku>' . $ra_group_product->sku . '</sku>';
				$xml[] = '<master_sku>' . $ra_group_product->master_sku . '</master_sku>';
				$xml[] = '<status>4</status>'; //error
				$xml[] = '<is_error>' . $ra_group_product->is_error . '</is_error>';
				$xml[] = '<reason>' . $e->getMessage() . '</reason>';
				$xml[] = '</ra_group_product>';
			}
		 }

		$xml[] = '</ra_group_products>';


		$return_feed = implode("\n", $xml);

		return $return_feed;
	}
}