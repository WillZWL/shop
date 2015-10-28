<?php
namespace ESG\Panther\Service;

use ESG\Panther\Dao\ProductCustomClassificationDao;
use ESG\Panther\Service\SkuMappingService;

class VbDataTransferProductCustomClassService extends VbDataTransferService
{

	public function __construct()
	{
		parent::__construct();
		$this->setDao(new ProductCustomClassificationDao);
        $this->skuMappingService = new SkuMappingService;
	}


	/*******************************************************************
	*	processVbData, get the VB data to save it in the price table
	********************************************************************/
	public function processVbData ($feed)
	{
		// print $feed;
		// exit;
		//Read the data sent from VB
		$xml_vb = simplexml_load_string($feed);

		$task_id = $xml_vb->attributes()->task_id;

		//Create return xml string
		$xml = array();
		$xml[] = '<?xml version="1.0" encoding="UTF-8"?>';
		$xml[] = '<products task_id="' . $task_id . '">';

		$c = count($xml_vb->product);
		foreach($xml_vb->product as $pc)
		{
			$c--;

			//Get the master sku to search the corresponding sku in atomv2 database
			$master_sku = $pc->master_sku;

			$master_sku = strtoupper($master_sku);
			$sku = $this->skuMappingService->getLocalSku($master_sku);

			$fail_reason = "";
            if ($master_sku == "" || $master_sku == null) $fail_reason .= "No master SKU mapped, ";
            if ($sku == "" || $sku == null) $fail_reason .= "SKU not specified, ";

			if(!$pc_obj_atomv2 = $this->getDao()->get(array("sku"=>$sku, "country_id"=>$pc->country_id)))
			{
				$fail_reason .= "SKU/Country not specified, ";
			}

			try
			{
				if ($fail_reason == "")
				{
					//Update the AtomV2 product data
					$where = array("sku"=>$sku, "country_id"=>$pc->country_id);

					$new_pc_obj = array();

					$new_pc_obj["code"] = $pc->code;
					$new_pc_obj["description"] = $this->replaceSpecialChars($pc->description);
					$new_pc_obj["duty_pcent"] = $pc->duty_pcent;

					$this->getDao()->qUpdate($where, $new_pc_obj);

					//return result
					$xml[] = '<product>';
					$xml[] = '<sku>' . $pc->sku . '</sku>';
					$xml[] = '<platform_id>' . $pc->country_id . '</platform_id>';
					$xml[] = '<master_sku>' . $pc->master_sku . '</master_sku>';
					$xml[] = '<status>5</status>'; //updated
					$xml[] = '<is_error>' . $pc->is_error . '</is_error>';
					$xml[] = '<reason>update</reason>';
					$xml[] = '</product>';
				}
				elseif ($sku != "" && $sku != null)
				{
					//insert
					$new_pc_obj = $this->getDao()->get();
					$new_pc_obj->setSku($sku);
					$new_pc_obj->setCountryId($pc->country_id);
					$new_pc_obj->setCode($pc->code);
					$new_pc_obj->setDescription($this->replaceSpecialChars($pc->description));
					$new_pc_obj->setDutyPcent($pc->duty_pcent);

					$this->getDao()->insert($new_pc_obj);

					//return result
					$xml[] = '<product>';
					$xml[] = '<sku>' . $pc->sku . '</sku>';
					$xml[] = '<platform_id>' . $pc->country_id . '</platform_id>';
					$xml[] = '<master_sku>' . $pc->master_sku . '</master_sku>';
					$xml[] = '<status>5</status>'; //updated
					$xml[] = '<is_error>' . $pc->is_error . '</is_error>';
					$xml[] = '<reason>insert</reason>';
					$xml[] = '</product>';
				}
				elseif ($sku == "" || $sku == null)
				{
					//if the master_sku is not found in atomv2, we have to store that sku in an xml string to send it to VB
					$xml[] = '<product>';
					$xml[] = '<sku>' . $pc->sku . '</sku>';
					$xml[] = '<platform_id>' . $pc->country_id . '</platform_id>';
					$xml[] = '<master_sku>' . $pc->master_sku . '</master_sku>';
					$xml[] = '<status>2</status>'; //not found
					$xml[] = '<is_error>' . $pc->is_error . '</is_error>';
					$xml[] = '<reason>' . $fail_reason . '</reason>';
					$xml[] = '</product>';
				}
				else
				{
					$xml[] = '<product>';
					$xml[] = '<sku>' . $pc->sku . '</sku>';
					$xml[] = '<platform_id>' . $pc->country_id . '</platform_id>';
					$xml[] = '<master_sku>' . $pc->master_sku . '</master_sku>';
					$xml[] = '<status>3</status>'; //not updated
					$xml[] = '<is_error>' . $pc->is_error . '</is_error>';
					$xml[] = '<reason>' . $fail_reason . '</reason>';
					$xml[] = '</product>';
				}

			}
			catch(Exception $e)
			{
				$xml[] = '<product>';
				$xml[] = '<sku>' . $pc->sku . '</sku>';
				$xml[] = '<platform_id>' . $pc->country_id . '</platform_id>';
				$xml[] = '<master_sku>' . $pc->master_sku . '</master_sku>';
				$xml[] = '<status>4</status>';	//error
				$xml[] = '<is_error>' . $pc->is_error . '</is_error>';
				$xml[] = '<reason>' . $e->getMessage() . '</reason>';
				$xml[] = '</product>';
			}
		 }

		$xml[] = '</products>';

		$return_feed = implode("\n", $xml);

		return $return_feed;
	}
}