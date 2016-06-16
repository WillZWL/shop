<?php
namespace ESG\Panther\Service;

use ESG\Panther\Dao\ProductWarrantyDao;
use ESG\Panther\Service\SkuMappingService;


class VbDataTransferProductWarrantyService extends VbDataTransferService
{

	public function __construct()
	{
		parent::__construct();
        $this->setDao(new ProductWarrantyDao);
        $this->skuMappingService = new SkuMappingService;
	}

	/*******************************************************************
	*	processVbData, get the VB data to save it in the price table
	********************************************************************/
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
		$xml[] = '<products task_id="' . $task_id . '">';
		$error_message = '';
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

			if(!$pc_obj_atomv2 = $this->getDao()->get(array("sku"=>$sku, "platform_id"=>$pc->platform_id)))
			{
				$fail_reason .= "SKU/Lang not specified, ";
			}
			try
			{
				if ($fail_reason == "")
				{
					//Update the AtomV2 product data
					$where = array("sku"=>$sku, "platform_id"=>$pc->platform_id);

					$new_pc_obj = array();

					$new_pc_obj["warranty_in_month"] = $pc->warranty_in_month;

					$this->getDao()->qUpdate($where, $new_pc_obj);

					//return result
					$xml[] = '<product>';
					$xml[] = '<sku>' . $pc->sku . '</sku>';
					$xml[] = '<platform_id>' . $pc->platform_id . '</platform_id>';
					$xml[] = '<master_sku>' . $pc->master_sku . '</master_sku>';
					$xml[] = '<status>5</status>';	 //updated
					$xml[] = '<is_error>' . $pc->is_error . '</is_error>';
					$xml[] = '<reason>update</reason>';
					$xml[] = '</product>';
				}
				elseif ($sku != "" && $sku != null)
				{
					//insert
					$new_pc_obj = $this->getDao()->get();

					$new_pc_obj->setSku($sku);
					$new_pc_obj->setPlatformId($pc->platform_id);
					$new_pc_obj->setWarrantyInMonth($pc->warranty_in_month);

					$this->getDao()->insert($new_pc_obj);

					//return result
					$xml[] = '<product>';
					$xml[] = '<sku>' . $pc->sku . '</sku>';
					$xml[] = '<platform_id>' . $pc->platform_id . '</platform_id>';
					$xml[] = '<master_sku>' . $pc->master_sku . '</master_sku>';
					$xml[] = '<status>5</status>';	 //updated
					$xml[] = '<is_error>' . $pc->is_error . '</is_error>';
					$xml[] = '<reason>insert</reason>';
					$xml[] = '</product>';
				}
				elseif ($sku == "" || $sku == null)
				{
					//if the master_sku is not found in atomv2, we have to store that sku in an xml string to send it to VB
					$xml[] = '<product>';
					$xml[] = '<sku>' . $pc->sku . '</sku>';
					$xml[] = '<platform_id>' . $pc->platform_id . '</platform_id>';
					$xml[] = '<master_sku>' . $pc->master_sku . '</master_sku>';
					$xml[] = '<status>2</status>';	//not found
					$xml[] = '<is_error>' . $pc->is_error . '</is_error>';
					$xml[] = '<reason>' . $fail_reason . '</reason>';
					$xml[] = '</product>';
				}
				else
				{
					$xml[] = '<product>';
					$xml[] = '<sku>' . $pc->sku . '</sku>';
					$xml[] = '<platform_id>' . $pc->platform_id . '</platform_id>';
					$xml[] = '<master_sku>' . $pc->master_sku . '</master_sku>';
					$xml[] = '<status>3</status>';	//not updated
					$xml[] = '<is_error>' . $pc->is_error . '</is_error>';
					$xml[] = '<reason>' . $fail_reason . '</reason>';
					$xml[] = '</product>';
				}
			}
			catch(Exception $e)
			{
				$xml[] = '<product>';
				$xml[] = '<sku>' . $pc->sku . '</sku>';
				$xml[] = '<platform_id>' . $pc->platform_id . '</platform_id>';
				$xml[] = '<master_sku>' . $pc->master_sku . '</master_sku>';
				$xml[] = '<status>4</status>';	//error
				$xml[] = '<is_error>' . $pc->is_error . '</is_error>';
				$xml[] = '<reason>' . $e->getMessage() . '</reason>';
				$xml[] = '</product>';
				$error_message .= $pc->sku .'-'. $pc->platform_id .'-'. $pc->master_sku .'-'. $pc->is_error .'-'. $e->getMessage()."\r\n";
			}
		 }

		$xml[] = '</products>';

		$return_feed = implode("", $xml);
		if ($error_message) {
            mail('data_transfer@eservicesgroup.com', 'Product Warranty Transfer Failed', "Error Message :".$error_message);
        }
        unset($xml);
        unset($xml_vb);
		return $return_feed;
	}
}