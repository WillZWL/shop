<?php
namespace ESG\Panther\Service;


class VbDataTransferRaProductService extends VbDataTransferService
{

	/**********************************************************************
	*	processVbData, get the VB data to save it in the ra_product table
	***********************************************************************/
	public function processVbData ($feed)
	{

		//Read the data sent from VB
		$xml_vb = simplexml_load_string($feed);

		$task_sku = $xml_vb->attributes()->task_sku;

		//Create return xml string
		$xml = array();
		$xml[] = '<?xml version="1.0" encoding="UTF-8"?>';
		$xml[] = '<ra_products task_sku="' . $task_sku . '">';

		foreach($xml_vb->ra_product as $ra_product)
		{
			try
			{
				//Get the master sku to search the corresponding sku in atomv2 database
				$master_sku = (string)$ra_product->master_sku;
	            $sku = $this->getService('SkuMapping')->getLocalSku($master_sku);

	            if (empty($sku)) {
					$xml[] = '<ra_product>';
					$xml[] = '<sku>' . $ra_product->sku . '</sku>';
					$xml[] = '<master_sku>' . $ra_product->master_sku . '</master_sku>';
					$xml[] = '<status>5</status>'; //updated
					$xml[] = '<is_error>' . $ra_product->is_error . '</is_error>';
					$xml[] = '<reason>No SKU mapping</reason>';
					$xml[] = '</ra_product>';
	                continue;
	            }

            	if($ra_prod_obj =$this->getDao('RaProduct')->get(['sku'=>$sku]))
				{
					//update
					$ra_prod_obj->setSku($sku);
					$ra_prod_obj->setRcmGroupId1($ra_product->rcm_group_id_1);
					$ra_prod_obj->setBundleUse1($ra_product->bundle_use_1);
					$ra_prod_obj->setRcmGroupId2($ra_product->rcm_group_id_2);
					$ra_prod_obj->setBundleUse2($ra_product->bundle_use_2);
					$ra_prod_obj->setRcmGroupId3($ra_product->rcm_group_id_3);
					$ra_prod_obj->setBundleUse3($ra_product->bundle_use_3);
					$ra_prod_obj->setRcmGroupId4($ra_product->rcm_group_id_4);
					$ra_prod_obj->setBundleUse4($ra_product->bundle_use_4);
					$ra_prod_obj->setRcmGroupId5($ra_product->rcm_group_id_5);
					$ra_prod_obj->setBundleUse5($ra_product->bundle_use_5);
					$ra_prod_obj->setRcmGroupId6($ra_product->rcm_group_id_6);
					$ra_prod_obj->setBundleUse6($ra_product->bundle_use_6);
					$ra_prod_obj->setRcmGroupId7($ra_product->rcm_group_id_7);
					$ra_prod_obj->setBundleUse7($ra_product->bundle_use_7);
					$ra_prod_obj->setRcmGroupId8($ra_product->rcm_group_id_8);
					$ra_prod_obj->setBundleUse8($ra_product->bundle_use_8);
					$ra_prod_obj->setRcmGroupId9($ra_product->rcm_group_id_9);
					$ra_prod_obj->setBundleUse9($ra_product->bundle_use_9);
					$ra_prod_obj->setRcmGroupId10($ra_product->rcm_group_id_10);
					$ra_prod_obj->setBundleUse10($ra_product->bundle_use_10);
					$ra_prod_obj->setRcmGroupId11($ra_product->rcm_group_id_11);
					$ra_prod_obj->setBundleUse11($ra_product->bundle_use_11);
					$ra_prod_obj->setRcmGroupId12($ra_product->rcm_group_id_12);
					$ra_prod_obj->setBundleUse12($ra_product->bundle_use_12);
					$ra_prod_obj->setRcmGroupId13($ra_product->rcm_group_id_13);
					$ra_prod_obj->setBundleUse13($ra_product->bundle_use_13);
					$ra_prod_obj->setRcmGroupId14($ra_product->rcm_group_id_14);
					$ra_prod_obj->setBundleUse14($ra_product->bundle_use_14);
					$ra_prod_obj->setRcmGroupId15($ra_product->rcm_group_id_15);
					$ra_prod_obj->setBundleUse15($ra_product->bundle_use_15);
					$ra_prod_obj->setRcmGroupId16($ra_product->rcm_group_id_16);
					$ra_prod_obj->setBundleUse16($ra_product->bundle_use_16);
					$ra_prod_obj->setRcmGroupId17($ra_product->rcm_group_id_17);
					$ra_prod_obj->setBundleUse17($ra_product->bundle_use_17);
					$ra_prod_obj->setRcmGroupId18($ra_product->rcm_group_id_18);
					$ra_prod_obj->setBundleUse18($ra_product->bundle_use_18);
					$ra_prod_obj->setRcmGroupId19($ra_product->rcm_group_id_19);
					$ra_prod_obj->setBundleUse19($ra_product->bundle_use_19);
					$ra_prod_obj->setRcmGroupId20($ra_product->rcm_group_id_20);
					$ra_prod_obj->setBundleUse20($ra_product->bundle_use_20);

					$reason = 'update';

					$this->getDao('RaProduct')->update($ra_prod_obj);
				}
				else
				{
					//insert
					$ra_prod_obj = new \RaProductVo(); //$this->getDao()->get();
					$ra_prod_obj->setSku($sku);
					$ra_prod_obj->setRcmGroupId1($ra_product->rcm_group_id_1);
					$ra_prod_obj->setBundleUse1($ra_product->bundle_use_1);
					$ra_prod_obj->setRcmGroupId2($ra_product->rcm_group_id_2);
					$ra_prod_obj->setBundleUse2($ra_product->bundle_use_2);
					$ra_prod_obj->setRcmGroupId3($ra_product->rcm_group_id_3);
					$ra_prod_obj->setBundleUse3($ra_product->bundle_use_3);
					$ra_prod_obj->setRcmGroupId4($ra_product->rcm_group_id_4);
					$ra_prod_obj->setBundleUse4($ra_product->bundle_use_4);
					$ra_prod_obj->setRcmGroupId5($ra_product->rcm_group_id_5);
					$ra_prod_obj->setBundleUse5($ra_product->bundle_use_5);
					$ra_prod_obj->setRcmGroupId6($ra_product->rcm_group_id_6);
					$ra_prod_obj->setBundleUse6($ra_product->bundle_use_6);
					$ra_prod_obj->setRcmGroupId7($ra_product->rcm_group_id_7);
					$ra_prod_obj->setBundleUse7($ra_product->bundle_use_7);
					$ra_prod_obj->setRcmGroupId8($ra_product->rcm_group_id_8);
					$ra_prod_obj->setBundleUse8($ra_product->bundle_use_8);
					$ra_prod_obj->setRcmGroupId9($ra_product->rcm_group_id_9);
					$ra_prod_obj->setBundleUse9($ra_product->bundle_use_9);
					$ra_prod_obj->setRcmGroupId10($ra_product->rcm_group_id_10);
					$ra_prod_obj->setBundleUse10($ra_product->bundle_use_10);
					$ra_prod_obj->setRcmGroupId11($ra_product->rcm_group_id_11);
					$ra_prod_obj->setBundleUse11($ra_product->bundle_use_11);
					$ra_prod_obj->setRcmGroupId12($ra_product->rcm_group_id_12);
					$ra_prod_obj->setBundleUse12($ra_product->bundle_use_12);
					$ra_prod_obj->setRcmGroupId13($ra_product->rcm_group_id_13);
					$ra_prod_obj->setBundleUse13($ra_product->bundle_use_13);
					$ra_prod_obj->setRcmGroupId14($ra_product->rcm_group_id_14);
					$ra_prod_obj->setBundleUse14($ra_product->bundle_use_14);
					$ra_prod_obj->setRcmGroupId15($ra_product->rcm_group_id_15);
					$ra_prod_obj->setBundleUse15($ra_product->bundle_use_15);
					$ra_prod_obj->setRcmGroupId16($ra_product->rcm_group_id_16);
					$ra_prod_obj->setBundleUse16($ra_product->bundle_use_16);
					$ra_prod_obj->setRcmGroupId17($ra_product->rcm_group_id_17);
					$ra_prod_obj->setBundleUse17($ra_product->bundle_use_17);
					$ra_prod_obj->setRcmGroupId18($ra_product->rcm_group_id_18);
					$ra_prod_obj->setBundleUse18($ra_product->bundle_use_18);
					$ra_prod_obj->setRcmGroupId19($ra_product->rcm_group_id_19);
					$ra_prod_obj->setBundleUse19($ra_product->bundle_use_19);
					$ra_prod_obj->setRcmGroupId20($ra_product->rcm_group_id_20);
					$ra_prod_obj->setBundleUse20($ra_product->bundle_use_20);

					$reason = 'insert';

					$this->getDao('RaProduct')->insert($ra_prod_obj);
				}


				$xml[] = '<ra_product>';
				$xml[] = '<sku>' . $ra_product->sku . '</sku>';
				$xml[] = '<master_sku>' . $ra_product->master_sku . '</master_sku>';
				$xml[] = '<status>5</status>'; //updated
				$xml[] = '<is_error>' . $ra_product->is_error . '</is_error>';
				$xml[] = '<reason>'.$reason.'</reason>';
				$xml[] = '</ra_product>';
			}
			catch(Exception $e)
			{
				$xml[] = '<ra_product>';
				$xml[] = '<sku>' . $ra_product->sku . '</sku>';
				$xml[] = '<master_sku>' . $ra_product->master_sku . '</master_sku>';
				$xml[] = '<status>4</status>'; //error
				$xml[] = '<is_error>' . $ra_product->is_error . '</is_error>';
				$xml[] = '<reason>' . $e->getMessage() . '</reason>';
				$xml[] = '</ra_product>';
			}
		 }

		$xml[] = '</ra_products>';


		$return_feed = implode("\n", $xml);

		return $return_feed;
	}
}