<?php 
namespace ESG\Panther\Service;

use ESG\Panther\Dao\ProductKeywordDao;
use ESG\Panther\Service\SkuMappingService;

class VbDataTransferProductKeywordService extends VbDataTransferService
{
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function getDao()
	{
		return $this->ProductKeywordDao;
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
						
		$current_sku = "";
		
		$c = count($xml_vb->product);
		foreach($xml_vb->product as $pc)
		{
			$c--;
			
			//Get the master sku to search the corresponding sku in atomv2 database
			$master_sku = $pc->master_sku;
						
			$master_sku = strtoupper($master_sku);
			$sku = $this->SkuMappingService->getLocalSku($master_sku);
						
			$fail_reason = "";
            if ($master_sku == "" || $master_sku == null) $fail_reason .= "No master SKU mapped, ";
            if ($sku == "" || $sku == null) $fail_reason .= "SKU not specified, ";
			
			try
			{			
				if ($fail_reason == "")
				{
					if($sku != $current_sku)
					{
						//First, we delete the AtomV2 product data 					
						$where = array("sku"=>$sku);		
						$this->getDao()->qDelete($where);
						
						$current_sku = $sku;
					}
					
					//After deleting, we insert de VB data				
					$new_pc_obj = $this->getDao()->get();
					
					$new_pc_obj->set_sku($sku);
					$new_pc_obj->setLangId($pc->lang_id); 
					$new_pc_obj->setKeyword($pc->keyword); 
					$new_pc_obj->setType($pc->type);	
					
					$this->getDao()->insert($new_pc_obj);
					
					//return result
					$xml[] = '<product>';
					$xml[] = '<sku>' . $pc->sku . '</sku>';
					$xml[] = '<platform_id>' . $pc->lang_id . '</platform_id>';
					$xml[] = '<master_sku>' . $pc->master_sku . '</master_sku>';					
					$xml[] = '<status>5</status>'; //updated
					$xml[] = '<is_error>' . $pc->is_error . '</is_error>';
					$xml[] = '</product>';
				}
				elseif ($sku == "" || $sku == null)
				{				
					//if the master_sku is not found in atomv2, we have to store that sku in an xml string to send it to VB
					$xml[] = '<product>';
					$xml[] = '<sku>' . $pc->sku . '</sku>';
					$xml[] = '<platform_id>' . $pc->lang_id . '</platform_id>';
					$xml[] = '<master_sku>' . $pc->master_sku . '</master_sku>';					
					$xml[] = '<status>2</status>';		
					$xml[] = '<is_error>' . $pc->is_error . '</is_error>';
					$xml[] = '</product>';
				}
				else
				{			
					//if the master_sku is not found in atomv2, we have to store that sku in an xml string to send it to VB
					$xml[] = '<product>';
					$xml[] = '<sku>' . $pc->sku . '</sku>';
					$xml[] = '<platform_id>' . $pc->lang_id . '</platform_id>';
					$xml[] = '<master_sku>' . $pc->master_sku . '</master_sku>';					
					$xml[] = '<status>2</status>';	
					$xml[] = '<is_error>' . $pc->is_error . '</is_error>';	
					$xml[] = '</product>';				
				}
			
			}	
			catch(Exception $e)
			{
				$xml[] = '<product>';
				$xml[] = '<sku>' . $pc->sku . '</sku>';
				$xml[] = '<platform_id>' . $pc->lang_id . '</platform_id>';
				$xml[] = '<master_sku>' . $pc->master_sku . '</master_sku>';					
				$xml[] = '<status>4</status>';	//error
				$xml[] = '<is_error>' . $pc->is_error . '</is_error>';
				$xml[] = '</product>';	
			}
		 }
		 
		$xml[] = '</products>';
		
		$return_feed = implode("\n", $xml);	
			
		return $return_feed;
	}
}