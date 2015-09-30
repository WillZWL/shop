<?php 
namespace ESG\Panther\Service;

use ESG\Panther\Dao\ProductContentExtendDao;
use ESG\Panther\Service\SkuMappingService;

class VbDataTransferProductContentExtendService extends VbDataTransferService
{
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function getDao()
	{
		return $this->ProductContentExtendDao;
	}
		
	/*******************************************************************
	*	processVbData, get the VB data to save it in the price table
	********************************************************************/
	public function processVbData ($feed)
	{	
		// print $feed;
		// exit;
		//Read the data sent from VB
		$xml_vb = simplexml_load_string($feed, 'SimpleXMLElement', LIBXML_NOCDATA);
		
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
			$sku = $this->SkuMappingService->getLocalSku($master_sku);
						
			$fail_reason = "";
            if ($master_sku == "" || $master_sku == null) $fail_reason .= "No master SKU mapped, ";
            if ($sku == "" || $sku == null) $fail_reason .= "SKU not specified, ";
			
			if(!$pc_obj_atomv2 = $this->getDao()->get(array("prod_sku"=>$sku, "lang_id"=>$pc->lang_id)))
			{
				$fail_reason .= "SKU/Lang not specified, ";
			}
			
			try
			{
				if ($fail_reason == "")
				{
					//Update the AtomV2 product data 					
					$where = array("prod_sku"=>$sku, "lang_id"=>$pc->lang_id);
					
					$new_pc_obj = array();
					
					$new_pc_obj["feature"] = $pc->feature; 
					$new_pc_obj["feature_original"] = $pc->feature_original;	
					$new_pc_obj["specification"] = $pc->specification;	
					$new_pc_obj["spec_original"]  = $pc->spec_original;	  
					$new_pc_obj["requirement"] = $pc->requirement;
					$new_pc_obj["instruction"] = $pc->instruction;
					$new_pc_obj["apply_enhanced_listing"] = $pc->apply_enhanced_listing;
					$new_pc_obj["enhanced_listing"] = $pc->enhanced_listing;	
					
					$this->getDao()->qUpdate($where, $new_pc_obj);
					
					//return result
					$xml[] = '<product>';
					$xml[] = '<sku>' . $pc->prod_sku . '</sku>';
					$xml[] = '<platform_id>' . $pc->lang_id . '</platform_id>';
					$xml[] = '<master_sku>' . $pc->master_sku . '</master_sku>';			
					$xml[] = '<status>5</status>';	//updated
					$xml[] = '<is_error>' . $pc->is_error . '</is_error>';
					$xml[] = '</product>';
				}
				elseif ($sku != "" && $sku != null)
				{
					//insert		

					$new_pc_obj = $this->getDao()->get();
					
					$new_pc_obj->setProdSku($sku); 
					$new_pc_obj->setLangId($pc->lang_id); 				
					$new_pc_obj->setFeature($pc->feature); 
					$new_pc_obj->setFeatureOriginal($pc->feature_original);	
					$new_pc_obj->setSpecification($pc->specification);	
					$new_pc_obj->setSpecOriginal($pc->spec_original);	  
					$new_pc_obj->setRequirement($pc->requirement);
					$new_pc_obj->setInstruction($pc->instruction);
					$new_pc_obj->setApplyEnhancedListing($pc->apply_enhanced_listing);
					$new_pc_obj->setEnhancedListing($pc->enhanced_listing);	
					
					$this->getDao()->insert($new_pc_obj);
					
					//return result
					$xml[] = '<product>';
					$xml[] = '<sku>' . $pc->prod_sku . '</sku>';
					$xml[] = '<platform_id>' . $pc->lang_id . '</platform_id>';
					$xml[] = '<master_sku>' . $pc->master_sku . '</master_sku>';			
					$xml[] = '<status>5</status>';	//updated
					$xml[] = '<is_error>' . $pc->is_error . '</is_error>';
					$xml[] = '</product>';
				}
				elseif ($sku == "" || $sku == null)
				{				
					//if the master_sku is not found in atomv2, we have to store that sku in an xml string to send it to VB
					$xml[] = '<product>';
					$xml[] = '<sku>' . $pc->prod_sku . '</sku>';
					$xml[] = '<platform_id>' . $pc->lang_id . '</platform_id>';
					$xml[] = '<master_sku>' . $pc->master_sku . '</master_sku>';			
					$xml[] = '<status>2</status>';	//not found	
					$xml[] = '<is_error>' . $pc->is_error . '</is_error>';	
					$xml[] = '</product>';
				}
				else
				{
					$xml[] = '<product>';
					$xml[] = '<sku>' . $pc->prod_sku . '</sku>';
					$xml[] = '<platform_id>' . $pc->lang_id . '</platform_id>';
					$xml[] = '<master_sku>' . $pc->master_sku . '</master_sku>';				
					$xml[] = '<status>3</status>';	//not updated	
					$xml[] = '<is_error>' . $pc->is_error . '</is_error>';
					$xml[] = '</product>';			
				}
			}	
			catch(Exception $e)
			{
				$xml[] = '<product>';
				$xml[] = '<sku>' . $pc->prod_sku . '</sku>';
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